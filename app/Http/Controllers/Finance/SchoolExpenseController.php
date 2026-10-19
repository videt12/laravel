<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\ExpenseCategory;
use App\Models\Finance\SchoolExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolExpenseController extends Controller
{
    use AuthorizesFinanceAccess;

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $query = SchoolExpense::with('expense_category');

        if ($request->filled('expense_category_id')) {
            $query->where('expense_category_id', $request->input('expense_category_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('description', 'like', "%{$term}%")
                    ->orWhereHas('expense_category', function ($cq) use ($term) {
                        $cq->where('name', 'like', "%{$term}%");
                    });
            });
        }

        $expenses = $query->orderByDesc('expense_date')->orderByDesc('id')->paginate(15)->withQueryString();
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('finance.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $this->authorizeFinanceAccess();

        $categories = ExpenseCategory::orderBy('name')->get();

        return view('finance.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'], // 5MB max, safe mimes
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        SchoolExpense::create([
            'expense_category_id' => $validated['expense_category_id'],
            'amount' => round((float) $validated['amount'], 2),
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'] ?? null,
            'receipt_path' => $receiptPath,
        ]);

        return redirect()->route('school-expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(SchoolExpense $schoolExpense)
    {
        $this->authorizeFinanceAccess();

        $categories = ExpenseCategory::orderBy('name')->get();

        return view('finance.expenses.edit', compact('schoolExpense', 'categories'));
    }

    public function update(Request $request, SchoolExpense $schoolExpense)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $receiptPath = $schoolExpense->receipt_path;

        if ($request->hasFile('receipt')) {
            // Delete old file if present
            if ($schoolExpense->receipt_path && Storage::disk('public')->exists($schoolExpense->receipt_path)) {
                Storage::disk('public')->delete($schoolExpense->receipt_path);
            }
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $schoolExpense->update([
            'expense_category_id' => $validated['expense_category_id'],
            'amount' => round((float) $validated['amount'], 2),
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'] ?? null,
            'receipt_path' => $receiptPath,
        ]);

        return redirect()->route('school-expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(SchoolExpense $schoolExpense)
    {
        $this->authorizeFinanceAccess();

        $schoolExpense->delete();

        return redirect()->route('school-expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
