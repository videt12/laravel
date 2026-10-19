<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Models\Library\LibraryBookCopy;
use App\Models\Library\LibraryIssue;
use App\Models\Students\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LibraryIssueController extends Controller
{
    use AuthorizesLibraryAccess;

    public function index(Request $request)
    {
        $this->authorizeLibraryManagement();

        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = LibraryIssue::with([
            'library_book_copy.library_book',
            'user.student.studentProfile',
            'user.employee',
            'libraryReturn',
        ]);

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'overdue') {
            $query->overdue();
        } elseif ($status === 'returned') {
            $query->returned();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('id', $search)
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('student', fn ($sq) => $sq->where('admission_number', 'like', "%{$search}%"))
                        ->orWhereHas('employee', fn ($eq) => $eq->where('employee_code', 'like', "%{$search}%"));
                })->orWhereHas('library_book_copy', function ($cq) use ($search) {
                    $cq->where('accession_number', 'like', "%{$search}%")
                        ->orWhereHas('library_book', function ($bq) use ($search) {
                            $bq->where('title', 'like', "%{$search}%")
                                ->orWhere('isbn', 'like', "%{$search}%");
                        });
                });
            });
        }

        $issues = $query->latest('id')->paginate(15)->withQueryString();

        // Counters for tabs
        $allCount = LibraryIssue::count();
        $activeCount = LibraryIssue::active()->count();
        $overdueCount = LibraryIssue::overdue()->count();
        $returnedCount = LibraryIssue::returned()->count();

        return view('library.issues.index', compact(
            'issues',
            'status',
            'search',
            'allCount',
            'activeCount',
            'overdueCount',
            'returnedCount'
        ));
    }

    public function create(Request $request)
    {
        $this->authorizeLibraryManagement();

        $preselectedCopyId = $request->query('copy_id');

        // Only available copies can be selected
        $availableCopies = LibraryBookCopy::with('library_book')
            ->where('status', 'available')
            ->orderBy('accession_number')
            ->get();

        // Active borrowers (students and employees/teachers)
        $borrowers = User::with([
            'roles',
            'student.studentProfile',
            'student.schoolClass',
            'employee.employee_department',
        ])
            ->where(function ($q) {
                $q->whereNull('status')
                    ->orWhereIn(DB::raw('LOWER(status)'), ['active', '1', 'enabled']);
            })
            ->whereHas('roles', function ($rq) {
                $rq->whereIn('slug', ['student', 'teacher', 'librarian', 'admin', 'super-admin', 'principal'])
                    ->orWhereIn(DB::raw('LOWER(name)'), ['student', 'teacher', 'librarian', 'admin', 'super admin', 'principal']);
            })
            ->orderBy('name')
            ->get();

        $defaultIssueDate = Carbon::today()->format('Y-m-d');
        $defaultDueDate = Carbon::today()->addDays(14)->format('Y-m-d');

        return view('library.issues.create', compact(
            'availableCopies',
            'borrowers',
            'preselectedCopyId',
            'defaultIssueDate',
            'defaultDueDate'
        ));
    }

    public function store(Request $request)
    {
        $this->authorizeLibraryManagement();

        // Support resolving borrower from typed ID / code if user_id is empty
        if (! $request->filled('user_id') && $request->filled('borrower_code')) {
            $code = trim($request->input('borrower_code'));
            $user = User::where('id', $code)->first();
            if (! $user) {
                $student = Student::where('admission_number', $code)->first();
                if ($student) {
                    $user = $student->user;
                }
            }
            if (! $user) {
                $employee = Employee::where('employee_code', $code)->first();
                if ($employee) {
                    $user = $employee->user;
                }
            }
            if ($user) {
                $request->merge(['user_id' => $user->id]);
            }
        }

        $validated = $request->validate([
            'library_book_copy_id' => 'required|integer|exists:library_book_copies,id',
            'user_id' => 'required|integer|exists:users,id',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
        ], [
            'user_id.required' => 'សូមវាយបញ្ចូល ID ឬជ្រើសរើសអ្នកខ្ចី។',
            'library_book_copy_id.required' => 'សូមជ្រើសរើសច្បាប់សៀវភៅ។',
        ]);

        $issue = DB::transaction(function () use ($validated) {
            $copy = LibraryBookCopy::where('id', $validated['library_book_copy_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // 1. Verify physical copy is available
            if ($copy->status !== 'available') {
                throw ValidationException::withMessages([
                    'library_book_copy_id' => "The selected book copy ({$copy->accession_number}) is not available (current status: {$copy->status}).",
                ]);
            }

            // 2. Verify no active unreturned issue exists
            if ($copy->hasActiveIssue()) {
                throw ValidationException::withMessages([
                    'library_book_copy_id' => "The selected book copy ({$copy->accession_number}) is currently checked out.",
                ]);
            }

            // 3. Verify borrower user is active
            $borrower = User::findOrFail($validated['user_id']);
            if ($borrower->status !== null && ! in_array(strtolower($borrower->status), ['active', '1', 'enabled'])) {
                throw ValidationException::withMessages([
                    'user_id' => 'The selected borrower account is inactive.',
                ]);
            }

            // 4. Create Issue record
            $newIssue = LibraryIssue::create($validated);

            // 5. Update physical copy status to 'issued'
            $copy->update(['status' => 'issued']);

            return $newIssue;
        });

        return redirect()->route('library.issues.show', $issue)
            ->with('success', 'Book issued successfully.');
    }

    public function show(LibraryIssue $issue)
    {
        $this->authorizeLibraryManagement();

        $issue->load([
            'library_book_copy.library_book.library_category',
            'user.roles',
            'user.student.studentProfile',
            'user.employee',
            'libraryReturn',
        ]);

        return view('library.issues.show', compact('issue'));
    }
}
