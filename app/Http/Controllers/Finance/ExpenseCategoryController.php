<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    use AuthorizesFinanceAccess;

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $query = ExpenseCategory::withCount('school_expenses');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        }

        $categories = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('finance.expense_categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorizeFinanceAccess();

        return view('finance.expense_categories.create');
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:expense_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('expense-categories.index')
            ->with('success', 'Expense Category created successfully.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        $this->authorizeFinanceAccess();

        return view('finance.expense_categories.edit', compact('expenseCategory'));
    }

    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('expense_categories', 'name')->ignore($expenseCategory->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $expenseCategory->update($validated);

        return redirect()->route('expense-categories.index')
            ->with('success', 'Expense Category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $this->authorizeFinanceAccess();

        if ($expenseCategory->school_expenses()->exists()) {
            return back()->with('error', 'Cannot delete Expense Category because existing school expenses are linked to it.');
        }

        $expenseCategory->delete();

        return redirect()->route('expense-categories.index')
            ->with('success', 'Expense Category deleted successfully.');
    }
}
