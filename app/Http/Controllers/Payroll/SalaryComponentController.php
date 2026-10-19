<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\SalaryComponent;
use App\Models\Payroll\SalaryStructure;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    use AuthorizesPayrollAccess;

    public function index(Request $request)
    {
        $this->authorizePayrollAccess();

        $query = SalaryComponent::with('salaryStructure');

        if ($request->filled('structure_id')) {
            $query->where('salary_structure_id', $request->structure_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('code', 'like', '%'.$request->search.'%');
            });
        }

        $components = $query->orderBy('salary_structure_id')->orderBy('order')->paginate(20)->withQueryString();
        $structures = SalaryStructure::active()->orderBy('name')->get();

        return view('payroll.salary-components.index', compact('components', 'structures'));
    }

    public function create(Request $request)
    {
        $this->authorizePayrollAccess();

        $structures = SalaryStructure::active()->orderBy('name')->get();
        $selectedStructure = $request->filled('structure_id')
            ? SalaryStructure::find($request->structure_id)
            : null;
        $defaultCode = SalaryComponent::generateNextCode();

        return view('payroll.salary-components.create', compact('structures', 'selectedStructure', 'defaultCode'));
    }

    public function store(Request $request)
    {
        $this->authorizePayrollAccess();

        if ($request->isNotFilled('code')) {
            $request->merge(['code' => SalaryComponent::generateNextCode()]);
        }

        $validated = $request->validate([
            'salary_structure_id' => 'nullable|exists:salary_structures,id',
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:earning,deduction',
            'calculation_type' => 'required|in:fixed,percentage',
            'amount' => 'required_if:calculation_type,fixed|nullable|numeric|min:0',
            'percentage' => 'required_if:calculation_type,percentage|nullable|numeric|min:0|max:100',
            'order' => 'nullable|integer|min:0',
            'is_taxable' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['is_taxable'] = $request->boolean('is_taxable', false);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $request->integer('order', 0);

        // Clear the irrelevant field to prevent confusion
        if ($validated['calculation_type'] === 'fixed') {
            $validated['percentage'] = null;
        } else {
            $validated['amount'] = 0;
        }

        $component = SalaryComponent::create($validated);

        $redirectRoute = $component->salary_structure_id
            ? route('salary-structures.show', $component->salary_structure_id)
            : route('salary-components.index');

        return redirect($redirectRoute)
            ->with('success', "Salary component \"{$component->name}\" created successfully.");
    }

    public function show(SalaryComponent $salary_component)
    {
        $this->authorizePayrollAccess();

        $salary_component->load('salaryStructure');

        return view('payroll.salary-components.show', compact('salary_component'));
    }

    public function edit(SalaryComponent $salary_component)
    {
        $this->authorizePayrollAccess();

        $structures = SalaryStructure::active()->orderBy('name')->get();

        return view('payroll.salary-components.edit', compact('salary_component', 'structures'));
    }

    public function update(Request $request, SalaryComponent $salary_component)
    {
        $this->authorizePayrollAccess();

        $validated = $request->validate([
            'salary_structure_id' => 'nullable|exists:salary_structures,id',
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:earning,deduction',
            'calculation_type' => 'required|in:fixed,percentage',
            'amount' => 'required_if:calculation_type,fixed|nullable|numeric|min:0',
            'percentage' => 'required_if:calculation_type,percentage|nullable|numeric|min:0|max:100',
            'order' => 'nullable|integer|min:0',
            'is_taxable' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['is_taxable'] = $request->boolean('is_taxable', false);
        $validated['is_active'] = $request->boolean('is_active', false);
        $validated['order'] = $request->integer('order', 0);

        if ($validated['calculation_type'] === 'fixed') {
            $validated['percentage'] = null;
        } else {
            $validated['amount'] = 0;
        }

        $salary_component->update($validated);

        return redirect()->route('salary-components.show', $salary_component)
            ->with('success', "Salary component \"{$salary_component->name}\" updated successfully.");
    }

    public function destroy(SalaryComponent $salary_component)
    {
        $this->authorizePayrollAccess();

        $name = $salary_component->name;
        $structureId = $salary_component->salary_structure_id;

        $salary_component->delete();

        $redirectRoute = $structureId
            ? route('salary-structures.show', $structureId)
            : route('salary-components.index');

        return redirect($redirectRoute)
            ->with('success', "Salary component \"{$name}\" deleted.");
    }
}
