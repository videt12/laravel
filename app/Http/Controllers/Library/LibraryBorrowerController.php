<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibraryBorrowerController extends Controller
{
    use AuthorizesLibraryAccess;

    public function index(Request $request)
    {
        $this->authorizeLibraryManagement();

        $search = $request->query('search');

        $query = User::with(['roles', 'student.studentProfile', 'employee'])
            ->whereHas('roles', function ($rq) {
                $rq->whereIn('slug', ['student', 'teacher', 'librarian', 'admin', 'super-admin', 'principal'])
                    ->orWhereIn(DB::raw('LOWER(name)'), ['student', 'teacher', 'librarian', 'admin', 'super admin', 'principal']);
            })
            ->withCount([
                'library_issues as active_issues_count' => function ($q) {
                    $q->whereDoesntHave('libraryReturn');
                },
                'library_issues as overdue_issues_count' => function ($q) {
                    $q->whereDoesntHave('libraryReturn')
                        ->whereNotNull('due_date')
                        ->where('due_date', '<', now()->toDateString());
                },
                'library_issues as total_issues_count',
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('admission_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('employee', function ($eq) use ($search) {
                        $eq->where('employee_code', 'like', "%{$search}%");
                    });
            });
        }

        $borrowers = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('library.borrowers.index', compact('borrowers', 'search'));
    }

    public function show(User $user)
    {
        // Enforce: managers can view anyone, teachers/students can only view their own
        $this->authorizeBorrowerAccess($user->id);

        $user->load([
            'roles',
            'student.studentProfile',
            'student.schoolClass',
            'student.section',
            'employee.employeeDepartment',
            'employee.employeePosition',
        ]);

        // Active issues
        $activeIssues = $user->library_issues()
            ->with(['library_book_copy.library_book', 'libraryReturn'])
            ->whereDoesntHave('libraryReturn')
            ->latest('issue_date')
            ->get();

        // Past returned issues
        $returnedIssues = $user->library_issues()
            ->with(['library_book_copy.library_book', 'libraryReturn'])
            ->whereHas('libraryReturn')
            ->latest('issue_date')
            ->paginate(15);

        $totalFines = (float) $user->library_issues()
            ->join('library_returns', 'library_issues.id', '=', 'library_returns.library_issue_id')
            ->sum('library_returns.fine_amount');

        return view('library.borrowers.show', compact('user', 'activeIssues', 'returnedIssues', 'totalFines'));
    }
}
