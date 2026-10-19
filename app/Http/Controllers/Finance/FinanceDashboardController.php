<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\SchoolExpense;
use App\Models\Finance\StudentInvoice;
use App\Models\Finance\StudentPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceDashboardController extends Controller
{
    use AuthorizesFinanceAccess;

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $today = Carbon::today();

        // 1. Total Billed: sum non-cancelled invoice net_amount
        $totalBilled = (float) StudentInvoice::where('status', '!=', 'cancelled')->sum('net_amount');

        // 2. Total Collected: sum non-deleted student_payments amount
        $totalCollected = (float) StudentPayment::sum('amount');

        // 3. Total Outstanding: Total Billed - Total Collected
        $totalOutstanding = max(0.0, round($totalBilled - $totalCollected, 2));

        // 4. Total Expenses: sum non-deleted school_expenses amount
        $totalExpenses = (float) SchoolExpense::sum('amount');

        // 5. Net Cash Balance: Total Collected - Total Expenses
        $netCashBalance = round($totalCollected - $totalExpenses, 2);

        // Invoice status counts
        $invoiceCounts = StudentInvoice::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $paidCount = $invoiceCounts['paid'] ?? 0;
        $partialCount = $invoiceCounts['partial'] ?? 0;
        $unpaidCount = $invoiceCounts['unpaid'] ?? 0;
        $cancelledCount = $invoiceCounts['cancelled'] ?? 0;

        // Overdue invoices: due_date < today AND status != paid AND status != cancelled
        $overdueQuery = StudentInvoice::where('due_date', '<', $today)
            ->whereNotIn('status', ['paid', 'cancelled']);

        $overdueCount = $overdueQuery->count();
        $overdueAmount = (float) $overdueQuery->sum('net_amount');

        // Recent Invoices
        $recentInvoices = StudentInvoice::with(['student.user', 'student_payments'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Recent Payments
        $recentPayments = StudentPayment::with(['student_invoice.student.user', 'payment_method'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Recent Expenses
        $recentExpenses = SchoolExpense::with('expense_category')
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Expense Breakdown by Category
        $expensesByCategory = SchoolExpense::join('expense_categories', 'school_expenses.expense_category_id', '=', 'expense_categories.id')
            ->select('expense_categories.name', DB::raw('SUM(school_expenses.amount) as total_amount'))
            ->groupBy('expense_categories.name')
            ->orderByDesc('total_amount')
            ->get();

        return view('finance.dashboard.index', compact(
            'totalBilled',
            'totalCollected',
            'totalOutstanding',
            'totalExpenses',
            'netCashBalance',
            'paidCount',
            'partialCount',
            'unpaidCount',
            'cancelledCount',
            'overdueCount',
            'overdueAmount',
            'recentInvoices',
            'recentPayments',
            'recentExpenses',
            'expensesByCategory'
        ));
    }

    /**
     * Purge all dummy test transactions in Finance and Library.
     */
    public function cleanupTestData(Request $request)
    {
        $this->authorizeFinanceAccess();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('student_payments')->truncate();
        DB::table('invoice_details')->truncate();
        DB::table('student_invoices')->truncate();
        DB::table('school_expenses')->truncate();

        DB::table('library_returns')->truncate();
        DB::table('library_issues')->truncate();
        DB::table('library_book_copies')->truncate();
        DB::table('library_books')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back(fallback: route('finance.dashboard'))
            ->with('success', 'ទិន្នន័យ Test ទាំងអស់ក្នុងផ្នែកហិរញ្ញវត្ថុ និងបណ្ណាល័យត្រូវបានលុបចេញដោយជោគជ័យ!');
    }
}
