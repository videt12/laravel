<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use App\Models\Payroll\SalaryComponent;
use App\Models\Payroll\SalaryStructure;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    use AuthorizesPayrollAccess;

    public function index(Request $request)
    {
        $this->authorizePayrollAccess();

        $query = SalaryStructure::with(['employee_department', 'employee_position'])
            ->withCount(['salaryComponents as component_count' => function ($q) {
                $q->where('is_active', true);
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('employee_department_id', $request->department_id);
        }

        if ($request->filled('position_id')) {
            $query->where('employee_position_id', $request->position_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $structures = $query->orderBy('name')->paginate(15)->withQueryString();

        $departments = EmployeeDepartment::where('status', 'active')->orderBy('name')->get();
        if ($departments->isEmpty()) {
            $departments = EmployeeDepartment::orderBy('name')->get();
        }

        $positions = EmployeePosition::where('status', 'active')->orderBy('name')->get();
        if ($positions->isEmpty()) {
            $positions = EmployeePosition::orderBy('name')->get();
        }

        $stats = [
            'total' => SalaryStructure::count(),
            'active' => SalaryStructure::where('is_active', true)->count(),
            'total_components' => SalaryComponent::whereNotNull('salary_structure_id')->where('is_active', true)->count(),
        ];

        return view('payroll.salary-structures.index', compact('structures', 'stats', 'departments', 'positions'));
    }

    public function create()
    {
        $this->authorizePayrollAccess();

        $defaultCode = SalaryStructure::generateNextCode();
        $departments = EmployeeDepartment::where('status', 'active')->orderBy('name')->get();
        if ($departments->isEmpty()) {
            $departments = EmployeeDepartment::orderBy('name')->get();
        }

        $positions = EmployeePosition::where('status', 'active')->orderBy('name')->get();
        if ($positions->isEmpty()) {
            $positions = EmployeePosition::orderBy('name')->get();
        }

        return view('payroll.salary-structures.create', compact('defaultCode', 'departments', 'positions'));
    }

    public function store(Request $request)
    {
        $this->authorizePayrollAccess();

        if ($request->isNotFilled('code')) {
            $request->merge(['code' => SalaryStructure::generateNextCode()]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:salary_structures,code',
            'employee_department_id' => 'nullable|exists:employee_departments,id',
            'employee_position_id' => 'nullable|exists:employee_positions,id',
            'description' => 'nullable|string|max:1000',
            'base_salary' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'components' => 'nullable|array',
            'components.*.name' => 'required_with:components|string|max:150',
            'components.*.type' => 'required_with:components|in:earning,deduction',
            'components.*.calculation_type' => 'required_with:components|in:fixed,percentage',
            'components.*.amount' => 'nullable|numeric|min:0',
            'components.*.percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $structure = SalaryStructure::create($validated);

        if (! empty($request->components)) {
            $seenNames = [];
            foreach ($request->components as $comp) {
                $name = trim($comp['name'] ?? '');
                if (empty($name)) {
                    continue;
                }
                $normalized = mb_strtolower($name);
                if (isset($seenNames[$normalized])) {
                    continue; // Skip duplicates
                }
                $seenNames[$normalized] = true;

                $calcType = ($comp['calculation_type'] ?? 'fixed') === 'percentage' ? 'percentage' : 'fixed';
                $structure->salaryComponents()->create([
                    'name' => $name,
                    'code' => SalaryComponent::generateNextCode(),
                    'type' => ($comp['type'] ?? 'earning') === 'deduction' ? 'deduction' : 'earning',
                    'calculation_type' => $calcType,
                    'amount' => $calcType === 'fixed' ? (float) ($comp['amount'] ?? 0) : 0,
                    'percentage' => $calcType === 'percentage' ? (float) ($comp['percentage'] ?? 0) : null,
                    'order' => count($seenNames),
                    'is_taxable' => false,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('salary-structures.show', $structure)
            ->with('success', "Salary structure \"{$structure->name}\" created successfully.");
    }

    public function show(SalaryStructure $salary_structure)
    {
        $this->authorizePayrollAccess();

        $salary_structure->load([
            'employee_department',
            'employee_position',
            'salaryComponents' => function ($q) {
                $q->orderBy('type')->orderBy('order');
            },
        ]);

        return view('payroll.salary-structures.show', compact('salary_structure'));
    }

    public function edit(SalaryStructure $salary_structure)
    {
        $this->authorizePayrollAccess();

        $departments = EmployeeDepartment::where('status', 'active')->orderBy('name')->get();
        if ($departments->isEmpty()) {
            $departments = EmployeeDepartment::orderBy('name')->get();
        }

        $positions = EmployeePosition::where('status', 'active')->orderBy('name')->get();
        if ($positions->isEmpty()) {
            $positions = EmployeePosition::orderBy('name')->get();
        }

        return view('payroll.salary-structures.edit', compact('salary_structure', 'departments', 'positions'));
    }

    public function update(Request $request, SalaryStructure $salary_structure)
    {
        $this->authorizePayrollAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:salary_structures,code,'.$salary_structure->id,
            'employee_department_id' => 'nullable|exists:employee_departments,id',
            'employee_position_id' => 'nullable|exists:employee_positions,id',
            'description' => 'nullable|string|max:1000',
            'base_salary' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $salary_structure->update($validated);

        return redirect()->route('salary-structures.show', $salary_structure)
            ->with('success', "Salary structure \"{$salary_structure->name}\" updated successfully.");
    }

    public function destroy(SalaryStructure $salary_structure)
    {
        $this->authorizePayrollAccess();

        // Prefer deactivation. Hard-delete only if no payroll history exists.
        $hasPayrolls = $salary_structure->payrolls()->exists();
        $hasContracts = $salary_structure->employeeContracts()->exists();

        if ($hasPayrolls || $hasContracts) {
            $salary_structure->update(['is_active' => false]);

            return redirect()->route('salary-structures.index')
                ->with('success', "Salary structure \"{$salary_structure->name}\" deactivated (has linked payroll/contract records).");
        }

        $name = $salary_structure->name;
        $salary_structure->delete();

        return redirect()->route('salary-structures.index')
            ->with('success', "Salary structure \"{$name}\" deleted successfully.");
    }
}
