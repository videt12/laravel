<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryComponent extends Model
{
    protected $table = 'salary_components';

    protected $fillable = [
        'salary_structure_id',
        'name',
        'code',
        'type',
        'calculation_type',
        'amount',
        'percentage',
        'order',
        'is_taxable',
        'is_active',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'salary_structure_id' => 'integer',
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'order' => 'integer',
        'is_taxable' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    public function payrollDetails(): HasMany
    {
        return $this->hasMany(PayrollDetail::class, 'salary_component_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeEarnings($query)
    {
        return $query->where('type', 'earning');
    }

    public function scopeDeductions($query)
    {
        return $query->where('type', 'deduction');
    }

    /**
     * Only structure-specific components (excludes master templates with null structure).
     */
    public function scopeStructureComponents($query)
    {
        return $query->whereNotNull('salary_structure_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Business Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate this component's amount given a base salary.
     */
    public function calculateAmount(float $baseSalary): float
    {
        if ($this->calculation_type === 'percentage') {
            return round($baseSalary * ((float) $this->percentage / 100), 2);
        }

        return (float) $this->amount;
    }

    /**
     * Generate the next salary component code sequentially (format: TS00001).
     */
    public static function generateNextCode(): string
    {
        $latest = static::orderByDesc('id')->first();
        $nextSeq = 1;

        if ($latest) {
            if (preg_match('/TS(\d+)/i', $latest->code, $matches)) {
                $nextSeq = (int) $matches[1] + 1;
            } else {
                $nextSeq = (int) $latest->id + 1;
            }
        }

        $candidate = 'TS'.str_pad((string) $nextSeq, 5, '0', STR_PAD_LEFT);

        while (static::where('code', $candidate)->exists()) {
            $nextSeq++;
            $candidate = 'TS'.str_pad((string) $nextSeq, 5, '0', STR_PAD_LEFT);
        }

        return $candidate;
    }
}
