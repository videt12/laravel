<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeContract;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Payroll\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeContractController extends Controller
{
    use AuthorizesPayrollAccess;

    public function index(Request $request)
    {
        $this->authorizePayrollAccess();

        $tab = $request->get('tab', 'has_contract');
        if (! in_array($tab, ['has_contract', 'no_contract'])) {
            $tab = 'has_contract';
        }

        // Stats calculation
        $totalContracts = EmployeeContract::count();
        $activeContracts = EmployeeContract::where('status', 'active')->count();
        $expiringSoon = EmployeeContract::where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now()->addDays(30))
            ->count();

        $hasContractCount = Employee::whereHas('activeContract')->count();
        $noContractCount = Employee::whereDoesntHave('activeContract')->count();

        $stats = [
            'total' => $totalContracts,
            'active' => $activeContracts,
            'expiring_soon' => $expiringSoon,
            'has_contract_count' => $hasContractCount,
            'no_contract_count' => $noContractCount,
        ];

        $contracts = null;
        $employeesWithoutContract = null;

        if ($tab === 'no_contract') {
            $empQuery = Employee::with([
                'user',
                'employee_department',
                'employee_position',
                'employee_status',
                'employeeContact',
                'employee_documents',
                'activeContract',
                'employeeContracts' => fn ($q) => $q->latest(),
            ])->whereDoesntHave('activeContract');

            if ($request->filled('search')) {
                $search = $request->search;
                $empQuery->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('first_name_khmer', 'like', "%{$search}%")
                        ->orWhere('last_name_khmer', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                        ->orWhereHas('employeeContact', fn ($cq) => $cq->where('phone', 'like', "%{$search}%"));
                });
            }

            if ($request->filled('department_id')) {
                $empQuery->where('employee_department_id', $request->department_id);
            }

            $employeesWithoutContract = $empQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        } else {
            $query = EmployeeContract::with(['employee.user', 'employee.employee_department', 'employee.employee_position', 'salaryStructure']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('contract_number', 'like', "%{$search}%")
                        ->orWhereHas('employee.user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('employee', fn ($eq) => $eq->where('employee_code', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('first_name_khmer', 'like', "%{$search}%")
                            ->orWhere('last_name_khmer', 'like', "%{$search}%")
                        );
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('contract_type')) {
                $query->where('contract_type', $request->contract_type);
            }

            $contracts = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        }

        $departments = EmployeeDepartment::where('status', 'active')->orderBy('name')->get();
        if ($departments->isEmpty()) {
            $departments = EmployeeDepartment::orderBy('name')->get();
        }

        return view('payroll.contracts.index', compact('contracts', 'employeesWithoutContract', 'stats', 'tab', 'departments'));
    }

    public function create(Request $request)
    {
        $this->authorizePayrollAccess();

        $employees = Employee::with(['user', 'employee_department', 'employee_position', 'activeContract'])
            ->get()
            ->sortBy(fn ($e) => $e->user?->name ?? '');
        $structures = SalaryStructure::active()->orderBy('name')->get();
        $selectedEmployee = $request->filled('employee_id')
            ? Employee::with(['user', 'employee_department', 'employee_position', 'activeContract'])->find($request->employee_id)
            : null;

        $nextContractNumber = EmployeeContract::generateNextContractNumber();

        return view('payroll.contracts.create', compact('employees', 'structures', 'selectedEmployee', 'nextContractNumber'));
    }

    public function store(Request $request)
    {
        $this->authorizePayrollAccess();

        if (! $request->filled('contract_number')) {
            $request->merge(['contract_number' => EmployeeContract::generateNextContractNumber()]);
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_structure_id' => 'nullable|exists:salary_structures,id',
            'contract_number' => 'required|string|max:50|unique:employee_contracts,contract_number',
            'contract_type' => 'required|in:permanent,fixed_term,probation,part_time,intern',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'salary' => 'nullable|numeric|min:0',
            'work_hours_per_week' => 'nullable|numeric|min:0|max:168',
            'status' => 'required|in:draft,active,expired,terminated,renewed',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Enforce single active contract per employee
        if ($validated['status'] === 'active') {
            $existing = EmployeeContract::where('employee_id', $validated['employee_id'])
                ->where('status', 'active')
                ->exists();

            if ($existing) {
                return back()->withErrors([
                    'status' => 'This employee already has an active contract. Please renew or terminate the existing contract first.',
                ])->withInput();
            }
        }

        // Handle document upload
        if ($request->hasFile('document')) {
            $validated['document_path'] = $request->file('document')->store('employee-contracts', 'public');
        }

        $validated['created_by'] = Auth::id();

        $contract = EmployeeContract::create($validated);

        return redirect()->route('employee-contracts.show', $contract)
            ->with('success', "Contract \"{$contract->contract_number}\" created successfully.");
    }

    public function show(EmployeeContract $employee_contract)
    {
        $this->authorizePayrollAccess();

        $employee_contract->load(['employee.user', 'salaryStructure.salaryComponents', 'createdBy']);

        return view('payroll.contracts.show', compact('employee_contract'));
    }

    public function edit(EmployeeContract $employee_contract)
    {
        $this->authorizePayrollAccess();

        $employees = Employee::with('user')->get()->sortBy(fn ($e) => $e->user?->name ?? '');
        $structures = SalaryStructure::active()->orderBy('name')->get();

        return view('payroll.contracts.edit', compact('employee_contract', 'employees', 'structures'));
    }

    public function update(Request $request, EmployeeContract $employee_contract)
    {
        $this->authorizePayrollAccess();

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_structure_id' => 'nullable|exists:salary_structures,id',
            'contract_number' => 'required|string|max:50|unique:employee_contracts,contract_number,'.$employee_contract->id,
            'contract_type' => 'required|in:permanent,fixed_term,probation,part_time,intern',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'salary' => 'nullable|numeric|min:0',
            'work_hours_per_week' => 'nullable|numeric|min:0|max:168',
            'status' => 'required|in:draft,active,expired,terminated,renewed',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Enforce single active contract per employee (excluding this contract)
        if ($validated['status'] === 'active' && $employee_contract->status !== 'active') {
            $existing = EmployeeContract::where('employee_id', $validated['employee_id'])
                ->where('status', 'active')
                ->where('id', '!=', $employee_contract->id)
                ->exists();

            if ($existing) {
                return back()->withErrors([
                    'status' => 'This employee already has an active contract. Close the existing active contract first.',
                ])->withInput();
            }
        }

        // Handle document upload
        if ($request->hasFile('document')) {
            if ($employee_contract->document_path && Storage::disk('public')->exists($employee_contract->document_path)) {
                Storage::disk('public')->delete($employee_contract->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('employee-contracts', 'public');
        }

        $employee_contract->update($validated);

        return redirect()->route('employee-contracts.show', $employee_contract)
            ->with('success', "Contract \"{$employee_contract->contract_number}\" updated successfully.");
    }

    /**
     * Display printable official Khmer employment contract.
     */
    public function print(EmployeeContract $employee_contract)
    {
        $this->authorizePayrollAccess();

        $employee_contract->load([
            'employee.user',
            'employee.employee_department',
            'employee.employee_position',
            'employee.employeeContact',
            'salaryStructure',
        ]);

        return view('payroll.contracts.print', compact('employee_contract'));
    }

    /**
     * Renew a contract: marks current as 'renewed' and redirects to create a new active one.
     */
    public function renew(EmployeeContract $employee_contract)
    {
        $this->authorizePayrollAccess();

        if (! $employee_contract->isActive()) {
            return back()->with('error', 'Only active contracts can be renewed.');
        }

        $employee_contract->update(['status' => 'renewed']);

        return redirect()->route('employee-contracts.create', ['employee_id' => $employee_contract->employee_id])
            ->with('success', "Contract \"{$employee_contract->contract_number}\" marked as renewed. Please create the new contract below.");
    }

    public function destroy(EmployeeContract $employee_contract)
    {
        $this->authorizePayrollAccess();

        $number = $employee_contract->contract_number;

        // Only allow deleting draft contracts to preserve historical integrity
        if ($employee_contract->status !== 'draft') {
            return back()->with('error', 'Only draft contracts can be deleted. Historical contracts must be terminated or expired.');
        }

        if ($employee_contract->document_path && Storage::disk('public')->exists($employee_contract->document_path)) {
            Storage::disk('public')->delete($employee_contract->document_path);
        }

        $employee_contract->delete();

        return redirect()->route('employee-contracts.index')
            ->with('success', "Draft contract \"{$number}\" deleted.");
    }
}
