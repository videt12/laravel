<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryBookCopy;
use App\Models\Library\LibraryCategory;
use App\Models\Library\LibraryIssue;
use App\Models\Library\LibraryReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibraryReportController extends Controller
{
    use AuthorizesLibraryAccess;

    public function index(Request $request)
    {
        $this->authorizeLibraryManagement();

        $tab = $request->query('tab', 'inventory');

        // 1. Inventory stats
        $totalBooks = LibraryBook::count();
        $totalCopies = LibraryBookCopy::count();
        $availableCopies = LibraryBookCopy::where('status', 'available')->count();
        $issuedCopies = LibraryBookCopy::where('status', 'issued')->count();
        $lostCopies = LibraryBookCopy::where('status', 'lost')->count();
        $damagedCopies = LibraryBookCopy::where('status', 'damaged')->count();

        $categoriesSummary = LibraryCategory::withCount('library_books')
            ->orderBy('name')
            ->get();

        // 2. Active & Overdue Checkouts
        $activeIssues = LibraryIssue::with(['library_book_copy.library_book', 'user.student.studentProfile', 'user.employee'])
            ->whereDoesntHave('libraryReturn')
            ->latest('issue_date')
            ->get();

        $overdueIssues = $activeIssues->filter(function ($issue) {
            return $issue->is_overdue;
        });

        // 3. Return & Fine History
        $returnsSummary = LibraryReturn::with(['library_issue.library_book_copy.library_book', 'library_issue.user'])
            ->latest('return_date')
            ->take(50)
            ->get();

        $totalFinesCollected = (float) LibraryReturn::sum('fine_amount');

        // 4. Most Borrowed Books
        $popularBooks = LibraryBook::with(['library_category'])
            ->withCount('library_issues')
            ->orderByDesc('library_issues_count')
            ->take(15)
            ->get();

        // 5. Borrowers summary / report
        $search = $request->query('search');
        $borrowersQuery = User::with(['roles', 'student.studentProfile', 'employee'])
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

        if ($search && $tab === 'borrowers') {
            $borrowersQuery->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($sq) => $sq->where('admission_number', 'like', "%{$search}%"))
                    ->orWhereHas('employee', fn ($eq) => $eq->where('employee_code', 'like', "%{$search}%"));
            });
        } else {
            $borrowersQuery->whereHas('library_issues');
        }

        $borrowers = $borrowersQuery->orderByDesc('total_issues_count')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $totalBorrowersCount = User::whereHas('library_issues')->count();

        return view('library.reports.index', compact(
            'tab',
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'issuedCopies',
            'lostCopies',
            'damagedCopies',
            'categoriesSummary',
            'activeIssues',
            'overdueIssues',
            'returnsSummary',
            'totalFinesCollected',
            'popularBooks',
            'borrowers',
            'totalBorrowersCount',
            'search'
        ));
    }
}
