<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollDetail extends Model
{
    protected $table = 'payroll_details';

    protected $fillable = [
        'payroll_id',
        'salary_component_id',
        'component_name',
        'component_type',
        'calculation_type',
        'rate',
        'amount',
        'notes',
    ];

    protected $casts = [
        'id' => 'integer',
        'payroll_id' => 'integer',
        'salary_component_id' => 'integer',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
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

    public function salaryComponent(): BelongsTo
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }
}
