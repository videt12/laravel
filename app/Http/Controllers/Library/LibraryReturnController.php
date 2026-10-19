<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryIssue;
use App\Models\Library\LibraryReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LibraryReturnController extends Controller
{
    use AuthorizesLibraryAccess;

    public function index(Request $request)
    {
        $this->authorizeLibraryManagement();

        $search = $request->query('search');

        $query = LibraryReturn::with([
            'library_issue.library_book_copy.library_book',
            'library_issue.user.student.studentProfile',
            'library_issue.user.employee',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('library_issue.user', function ($uq) use ($search) {
                    $uq->where('id', $search)
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('student', fn ($sq) => $sq->where('admission_number', 'like', "%{$search}%"))
                        ->orWhereHas('employee', fn ($eq) => $eq->where('employee_code', 'like', "%{$search}%"));
                })->orWhereHas('library_issue.library_book_copy', function ($cq) use ($search) {
                    $cq->where('accession_number', 'like', "%{$search}%")
                        ->orWhereHas('library_book', function ($bq) use ($search) {
                            $bq->where('title', 'like', "%{$search}%")
                                ->orWhere('isbn', 'like', "%{$search}%");
                        });
                });
            });
        }

        $returns = $query->latest('id')->paginate(15)->withQueryString();
        $totalFines = (float) LibraryReturn::sum('fine_amount');

        return view('library.returns.index', compact('returns', 'search', 'totalFines'));
    }

    public function create(LibraryIssue $issue)
    {
        $this->authorizeLibraryManagement();

        if ($issue->libraryReturn()->exists()) {
            return redirect()->route('library.issues.show', $issue)
                ->with('error', 'This book loan has already been returned.');
        }

        $issue->load([
            'library_book_copy.library_book.library_category',
            'user.student.studentProfile',
            'user.employee',
        ]);

        $defaultReturnDate = Carbon::today()->format('Y-m-d');

        // If loan record has no due_date, default to 7 days from issue date so overdue can be calculated
        $hasOriginalDueDate = (bool) $issue->due_date;
        $effectiveDueDate = $issue->due_date ?? ($issue->issue_date ? $issue->issue_date->copy()->addDays(7) : Carbon::today());

        $overdueDays = 0;
        if ($effectiveDueDate && Carbon::parse($defaultReturnDate)->greaterThan($effectiveDueDate)) {
            $overdueDays = (int) $effectiveDueDate->diffInDays(Carbon::parse($defaultReturnDate));
        }

        $defaultDailyRate = 0.50; // $0.50 / day fine
        $defaultFineAmount = number_format($overdueDays * $defaultDailyRate, 2, '.', '');

        return view('library.returns.create', compact('issue', 'defaultReturnDate', 'effectiveDueDate', 'hasOriginalDueDate', 'overdueDays', 'defaultDailyRate', 'defaultFineAmount'));
    }

    public function store(Request $request)
    {
        $this->authorizeLibraryManagement();

        $validated = $request->validate([
            'library_issue_id' => 'required|integer|exists:library_issues,id|unique:library_returns,library_issue_id',
            'return_date' => 'required|date',
            'fine_amount' => 'required|numeric|min:0',
        ]);

        $return = DB::transaction(function () use ($validated) {
            $issue = LibraryIssue::where('id', $validated['library_issue_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // 1. Double check return does not exist
            if ($issue->libraryReturn()->exists()) {
                throw ValidationException::withMessages([
                    'library_issue_id' => 'This issue has already been returned.',
                ]);
            }

            // 2. Create LibraryReturn
            $newReturn = LibraryReturn::create($validated);

            // 3. Update copy status: if 'issued', update to 'available'. If 'lost' or 'damaged', preserve.
            $copy = $issue->library_book_copy;
            if ($copy && $copy->status === 'issued') {
                $copy->update(['status' => 'available']);
            }

            return $newReturn;
        });

        return redirect()->route('library.issues.show', $return->library_issue_id)
            ->with('success', 'Book return recorded successfully.');
    }
}
