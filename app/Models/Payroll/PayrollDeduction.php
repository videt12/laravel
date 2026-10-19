<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollDeduction extends Model
{
    protected $table = 'payroll_deductions';

    protected $fillable = [
        'payroll_id',
        'deduction_type',
        'description',
        'amount',
    ];

    protected $casts = [
        'id' => 'integer',
        'payroll_id' => 'integer',
        'amount' => 'decimal:2',
    ];

    public static array $deductionTypes = [
        'unpaid_leave' => 'Unpaid Leave',
        'advance_repayment' => 'Advance Repayment',
        'late_penalty' => 'Late Penalty',
        'fine' => 'Fine',
        'tax_adjustment' => 'Tax Adjustment',
        'other' => 'Other',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getDeductionTypeLabelAttribute(): string
    {
        return static::$deductionTypes[$this->deduction_type] ?? ucfirst(str_replace('_', ' ', $this->deduction_type));
    }
}
