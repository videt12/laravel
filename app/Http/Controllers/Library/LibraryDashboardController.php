<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryBook;
use App\Models\Library\LibraryBookCopy;
use App\Models\Library\LibraryIssue;
use App\Models\Library\LibraryReturn;
use Illuminate\Http\Request;

class LibraryDashboardController extends Controller
{
    use AuthorizesLibraryAccess;

    public function index(Request $request)
    {
        $this->authorizeLibraryManagement();

        $totalBooks = LibraryBook::count();
        $totalCopies = LibraryBookCopy::count();
        $availableCopies = LibraryBookCopy::where('status', 'available')->count();
        $issuedCopies = LibraryBookCopy::where('status', 'issued')->count();
        $lostCopies = LibraryBookCopy::where('status', 'lost')->count();
        $damagedCopies = LibraryBookCopy::where('status', 'damaged')->count();

        $activeIssues = LibraryIssue::doesntHave('libraryReturn')->count();
        $overdueIssues = LibraryIssue::doesntHave('libraryReturn')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->count();

        $totalFines = (float) LibraryReturn::sum('fine_amount');

        $recentIssues = LibraryIssue::with(['library_book_copy.library_book', 'user', 'libraryReturn'])
            ->latest('id')
            ->take(6)
            ->get();

        $recentReturns = LibraryReturn::with(['library_issue.library_book_copy.library_book', 'library_issue.user'])
            ->latest('id')
            ->take(6)
            ->get();

        $popularBooks = LibraryBook::with(['library_category'])
            ->withCount('library_issues')
            ->orderByDesc('library_issues_count')
            ->take(5)
            ->get();

        return view('library.dashboard.index', compact(
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'issuedCopies',
            'lostCopies',
            'damagedCopies',
            'activeIssues',
            'overdueIssues',
            'totalFines',
            'recentIssues',
            'recentReturns',
            'popularBooks'
        ));
    }
}
