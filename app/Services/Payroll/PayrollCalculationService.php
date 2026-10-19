<?php

namespace App\Services\Payroll;

use App\Models\Employees\Employee;
use App\Models\Payroll\Payroll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayrollCalculationService
{
    /**
     * Generate a payroll for a given employee, month, and year.
     * All writes are wrapped in a DB::transaction.
     *
     * @throws ValidationException
     * @throws \Throwable
     */
    public function generate(Employee $employee, int $month, int $year, array $extraDeductions = []): Payroll
    {
        // 1. Resolve active contract
        $contract = $employee->activeContract;
        if (! $contract) {
            throw ValidationException::withMessages([
                'employee_id' => "Employee [{$employee->employee_code}] has no active contract.",
            ]);
        }

        // 2. Resolve salary structure
        $structure = $contract->salaryStructure;

        // 3. Resolve effective basic salary
        //    Rule: use contract salary if set (> 0), otherwise fall back to structure base_salary
        $basicSalary = $contract->effectiveSalary();

        // 4. Load active structure-specific components (only those with salary_structure_id set)
        $components = $structure
            ? $structure->salaryComponents()
                ->where('is_active', true)
                ->whereNotNull('salary_structure_id')
                ->orderBy('order')
                ->get()
            : collect();

        return DB::transaction(function () use ($employee, $structure, $basicSalary, $month, $year, $components, $extraDeductions) {
            // 5. Build payroll number
            $payrollNumber = $this->generatePayrollNumber($month, $year);

            // 6. Create payroll record (snapshot)
            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'salary_structure_id' => $structure?->id,
                'payroll_number' => $payrollNumber,
                'month' => $month,
                'year' => $year,
                'basic_salary' => $basicSalary,
                'total_earnings' => $basicSalary,  // will recalculate
                'total_deductions' => 0,
                'net_salary' => $basicSalary,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // 7. Create payroll_details snapshot for each component
            $totalEarningsFromComponents = 0.0;
            $totalDeductionsFromComponents = 0.0;

            foreach ($components as $component) {
                $calculatedAmount = $component->calculateAmount($basicSalary);

                $payroll->payrollDetails()->create([
                    'salary_component_id' => $component->id,
                    'component_name' => $component->name,
                    'component_type' => $component->type,
                    'calculation_type' => $component->calculation_type,
                    'rate' => $component->calculation_type === 'percentage'
                        ? $component->percentage
                        : $component->amount,
                    'amount' => $calculatedAmount,
                ]);

                if ($component->type === 'earning') {
                    $totalEarningsFromComponents += $calculatedAmount;
                } else {
                    $totalDeductionsFromComponents += $calculatedAmount;
                }
            }

            // 8. Create payroll_deductions for ad-hoc deductions
            $totalAdHocDeductions = 0.0;
            foreach ($extraDeductions as $deduction) {
                if (! isset($deduction['amount']) || (float) $deduction['amount'] <= 0) {
                    continue;
                }

                $payroll->payrollDeductions()->create([
                    'deduction_type' => $deduction['deduction_type'],
                    'description' => $deduction['description'],
                    'amount' => $deduction['amount'],
                ]);

                $totalAdHocDeductions += (float) $deduction['amount'];
            }

            // 9. Calculate final totals (locked formula)
            $totalEarnings = $basicSalary + $totalEarningsFromComponents;
            $totalDeductions = $totalDeductionsFromComponents + $totalAdHocDeductions;
            $netSalary = $totalEarnings - $totalDeductions;

            $payroll->update([
                'total_earnings' => $totalEarnings,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
            ]);

            return $payroll->fresh(['payrollDetails', 'payrollDeductions']);
        });
    }

    /**
     * Approve a payroll. Sets status to 'approved' and records the approver.
     *
     * @throws \RuntimeException
     */
    public function approve(Payroll $payroll): Payroll
    {
        if ($payroll->status !== 'pending') {
            throw new \RuntimeException("Only pending payrolls can be approved. Current status: {$payroll->status}");
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return $payroll->fresh();
    }

    /**
     * Mark a payroll as paid.
     *
     * @throws \RuntimeException
     */
    public function markAsPaid(Payroll $payroll, string $paymentMethod, ?string $paymentReference = null, ?string $payDate = null): Payroll
    {
        if ($payroll->status !== 'approved') {
            throw new \RuntimeException("Only approved payrolls can be marked as paid. Current status: {$payroll->status}");
        }

        $payroll->update([
            'status' => 'paid',
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'pay_date' => $payDate ?? now()->toDateString(),
            'paid_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        return $payroll->fresh();
    }

    /**
     * Cancel a payroll. Only draft/pending/approved can be cancelled.
     *
     * @throws \RuntimeException
     */
    public function cancel(Payroll $payroll): Payroll
    {
        if ($payroll->status === 'paid') {
            throw new \RuntimeException('A paid payroll cannot be cancelled.');
        }

        if ($payroll->status === 'cancelled') {
            throw new \RuntimeException('This payroll is already cancelled.');
        }

        $payroll->update(['status' => 'cancelled']);

        return $payroll->fresh();
    }

    /**
     * Generate a unique payroll number in the format PAY-YYYYMM-NNNN.
     */
    private function generatePayrollNumber(int $month, int $year): string
    {
        $prefix = 'PAY-'.$year.str_pad($month, 2, '0', STR_PAD_LEFT).'-';

        $last = Payroll::where('payroll_number', 'like', $prefix.'%')
            ->orderByDesc('payroll_number')
            ->lockForUpdate()
            ->first();

        $nextSeq = 1;
        if ($last) {
            $parts = explode('-', $last->payroll_number);
            $nextSeq = (int) end($parts) + 1;
        }

        return $prefix.str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }
}
