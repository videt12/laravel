<?php

namespace App\Models\Payroll;

use App\Models\Auth\User;
use App\Models\Employees\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'salary_structure_id',
        'payroll_number',
        'month',
        'year',
        'pay_date',
        'basic_salary',
        'total_earnings',
        'total_deductions',
        'net_salary',
        'status',
        'payment_method',
        'payment_reference',
        'notes',
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
        'created_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'salary_structure_id' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'pay_date' => 'date',
        'basic_salary' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'approved_by' => 'integer',
        'approved_at' => 'datetime',
        'paid_by' => 'integer',
        'paid_at' => 'datetime',
        'created_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    public function payrollDetails(): HasMany
    {
        return $this->hasMany(PayrollDetail::class, 'payroll_id');
    }

    public function payrollDeductions(): HasMany
    {
        return $this->hasMany(PayrollDeduction::class, 'payroll_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /*
    |--------------------------------------------------------------------------
    | Business Methods
    |--------------------------------------------------------------------------
    */

    /**
     * A payroll is editable only when in draft or pending status.
     */
    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    /**
     * A payroll is paid and therefore fully locked.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Returns the human-readable month name.
     */
    public function getMonthNameAttribute(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F');
    }

    /**
     * Returns the formatted payroll period.
     */
    public function getPeriodAttribute(): string
    {
        return $this->month_name.' '.$this->year;
    }

    /**
     * Status badge CSS class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-secondary',
            'pending' => 'bg-warning text-dark',
            'approved' => 'bg-info text-dark',
            'paid' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Recalculate and persist totals from current details and deductions.
     * Does NOT touch paid payrolls.
     */
    public function calculateTotals(): void
    {
        if ($this->isPaid()) {
            return;
        }

        $earningsFromComponents = $this->payrollDetails()
            ->where('component_type', 'earning')
            ->sum('amount');

        $deductionsFromComponents = $this->payrollDetails()
            ->where('component_type', 'deduction')
            ->sum('amount');

        $additionalDeductions = $this->payrollDeductions()->sum('amount');

        $totalEarnings = (float) $this->basic_salary + (float) $earningsFromComponents;
        $totalDeductions = (float) $deductionsFromComponents + (float) $additionalDeductions;
        $netSalary = $totalEarnings - $totalDeductions;

        $this->update([
            'total_earnings' => $totalEarnings,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
        ]);
    }
}
