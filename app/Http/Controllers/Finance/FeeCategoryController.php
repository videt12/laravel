<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeCategoryController extends Controller
{
    use AuthorizesFinanceAccess;

    public function __construct()
    {
        // Auth is handled in routes or methods
    }

    public function index(Request $request)
    {
        $this->authorizeFinanceAccess();

        $query = FeeCategory::withCount(['fee_structures', 'invoice_details']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        }

        $categories = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('finance.fee_categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorizeFinanceAccess();

        return view('finance.fee_categories.create');
    }

    public function store(Request $request)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:fee_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        FeeCategory::create($validated);

        return redirect()->route('fee-categories.index')
            ->with('success', 'Fee Category created successfully.');
    }

    public function edit(FeeCategory $feeCategory)
    {
        $this->authorizeFinanceAccess();

        return view('finance.fee_categories.edit', compact('feeCategory'));
    }

    public function update(Request $request, FeeCategory $feeCategory)
    {
        $this->authorizeFinanceAccess();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('fee_categories', 'name')->ignore($feeCategory->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $feeCategory->update($validated);

        return redirect()->route('fee-categories.index')
            ->with('success', 'Fee Category updated successfully.');
    }

    public function destroy(FeeCategory $feeCategory)
    {
        $this->authorizeFinanceAccess();

        if ($feeCategory->fee_structures()->exists() || $feeCategory->invoice_details()->exists()) {
            return back()->with('error', 'Cannot delete Fee Category because active fee structures or invoice details depend on it.');
        }

        $feeCategory->delete();

        return redirect()->route('fee-categories.index')
            ->with('success', 'Fee Category deleted successfully.');
    }
}
