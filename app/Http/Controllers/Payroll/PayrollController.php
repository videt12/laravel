<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use App\Models\Payroll\Payroll;
use App\Services\Payroll\PayrollCalculationService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PayrollController extends Controller
{
    use AuthorizesPayrollAccess;

    public function __construct(private PayrollCalculationService $payrollService) {}

    public function index(Request $request)
    {
        $this->authorizePayrollAccess();

        $query = Payroll::with([
            'employee.user',
            'employee.employee_department',
            'employee.employee_position',
            'salaryStructure',
        ]);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payroll_number', 'like', "%{$search}%")
                    ->orWhereHas('employee.user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('employee', fn ($eq) => $eq->where('employee_code', 'like', "%{$search}%"));
            });
        }

        $payrolls = $query->orderByDesc('year')->orderByDesc('month')->orderBy('id')->paginate(20)->withQueryString();

        // Report stats for the filtered month/year (or overall)
        $statsQuery = Payroll::query();
        if ($request->filled('month') && $request->filled('year')) {
            $statsQuery->where('month', $request->month)->where('year', $request->year);
        }

        $stats = [
            'total' => $statsQuery->clone()->count(),
            'draft' => $statsQuery->clone()->where('status', 'draft')->count(),
            'pending' => $statsQuery->clone()->where('status', 'pending')->count(),
            'approved' => $statsQuery->clone()->where('status', 'approved')->count(),
            'paid' => $statsQuery->clone()->where('status', 'paid')->count(),
            'total_net' => $statsQuery->clone()->where('status', 'paid')->sum('net_salary'),
        ];

        return view('payroll.payrolls.index', compact('payrolls', 'stats'));
    }

    public function create()
    {
        $this->authorizePayrollAccess();

        $employees = Employee::with([
            'user',
            'employee_department',
            'employee_position',
            'activeContract.salaryStructure',
        ])->get()->sortBy(fn ($e) => $e->user?->name ?? '');

        $departments = EmployeeDepartment::where('status', 'active')->orderBy('name')->get();
        if ($departments->isEmpty()) {
            $departments = EmployeeDepartment::orderBy('name')->get();
        }

        $positions = EmployeePosition::where('status', 'active')->orderBy('name')->get();
        if ($positions->isEmpty()) {
            $positions = EmployeePosition::orderBy('name')->get();
        }

        return view('payroll.payrolls.create', compact('employees', 'departments', 'positions'));
    }

    public function store(Request $request)
    {
        $this->authorizePayrollAccess();

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'deductions' => 'nullable|array',
            'deductions.*.deduction_type' => 'required_with:deductions|in:unpaid_leave,advance_repayment,late_penalty,fine,tax_adjustment,other',
            'deductions.*.description' => 'required_with:deductions|string|max:255',
            'deductions.*.amount' => 'required_with:deductions|numeric|min:0.01',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $extraDeductions = $validated['deductions'] ?? [];

        try {
            $payroll = $this->payrollService->generate(
                $employee,
                (int) $validated['month'],
                (int) $validated['year'],
                $extraDeductions
            );
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (UniqueConstraintViolationException $e) {
            return back()->withErrors([
                'employee_id' => 'A payroll already exists for this employee for the selected month and year.',
            ])->withInput();
        }

        return redirect()->route('payrolls.show', $payroll)
            ->with('success', "Payroll \"{$payroll->payroll_number}\" generated successfully.");
    }

    public function show(Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        $payroll->load([
            'employee.user',
            'employee.employee_department',
            'employee.employee_position',
            'salaryStructure',
            'payrollDetails' => fn ($q) => $q->orderBy('component_type')->orderBy('id'),
            'payrollDeductions',
            'approvedBy',
            'paidBy',
            'createdBy',
        ]);

        return view('payroll.payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        if (! $payroll->isEditable()) {
            return back()->with('error', 'Only draft or pending payrolls can be edited.');
        }

        $payroll->load(['payrollDetails', 'payrollDeductions']);

        return view('payroll.payrolls.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        if (! $payroll->isEditable()) {
            abort(403, 'This payroll cannot be edited.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:2000',
            'pay_date' => 'nullable|date',
            'deductions' => 'nullable|array',
            'deductions.*.id' => 'nullable|integer',
            'deductions.*.deduction_type' => 'required_with:deductions|in:unpaid_leave,advance_repayment,late_penalty,fine,tax_adjustment,other',
            'deductions.*.description' => 'required_with:deductions|string|max:255',
            'deductions.*.amount' => 'required_with:deductions|numeric|min:0.01',
        ]);

        $payroll->update([
            'notes' => $validated['notes'] ?? null,
            'pay_date' => $validated['pay_date'] ?? null,
        ]);

        // Rebuild ad-hoc deductions
        $payroll->payrollDeductions()->delete();
        foreach ($validated['deductions'] ?? [] as $ded) {
            $payroll->payrollDeductions()->create([
                'deduction_type' => $ded['deduction_type'],
                'description' => $ded['description'],
                'amount' => $ded['amount'],
            ]);
        }

        $payroll->calculateTotals();

        return redirect()->route('payrolls.show', $payroll)
            ->with('success', "Payroll \"{$payroll->payroll_number}\" updated.");
    }

    /**
     * Submit draft payroll to pending.
     */
    public function submit(Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Only draft payrolls can be submitted.');
        }

        $payroll->update(['status' => 'pending']);

        return back()->with('success', "Payroll \"{$payroll->payroll_number}\" submitted for approval.");
    }

    /**
     * Approve a pending payroll.
     */
    public function approve(Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        try {
            $this->payrollService->approve($payroll);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Payroll \"{$payroll->payroll_number}\" approved.");
    }

    /**
     * Mark an approved payroll as paid.
     */
    public function pay(Request $request, Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        $validated = $request->validate([
            'payment_method' => 'required|in:bank_transfer,cash,cheque',
            'payment_reference' => 'nullable|string|max:100',
            'pay_date' => 'nullable|date',
        ]);

        try {
            $this->payrollService->markAsPaid(
                $payroll,
                $validated['payment_method'],
                $validated['payment_reference'] ?? null,
                $validated['pay_date'] ?? null
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Payroll \"{$payroll->payroll_number}\" marked as paid.");
    }

    /**
     * Cancel a payroll.
     */
    public function cancel(Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        try {
            $this->payrollService->cancel($payroll);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Payroll \"{$payroll->payroll_number}\" cancelled.");
    }

    /**
     * Payslip / print-friendly view.
     */
    public function payslip(Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        $payroll->load([
            'employee.user',
            'employee.employee_department',
            'employee.employee_position',
            'employee.employeeContact',
            'salaryStructure',
            'payrollDetails' => fn ($q) => $q->orderBy('component_type')->orderBy('id'),
            'payrollDeductions',
        ]);

        return view('payroll.payrolls.payslip', compact('payroll'));
    }

    public function destroy(Payroll $payroll)
    {
        $this->authorizePayrollAccess();

        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Only draft payrolls can be deleted. Use Cancel for other statuses.');
        }

        $number = $payroll->payroll_number;
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', "Draft payroll \"{$number}\" deleted.");
    }
}
