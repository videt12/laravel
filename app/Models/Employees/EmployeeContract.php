<?php

namespace App\Models\Employees;

use App\Models\Auth\User;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\SalaryStructure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeContract extends Model
{
    protected $table = 'employee_contracts';

    protected $fillable = [
        'employee_id',
        'salary_structure_id',
        'contract_number',
        'contract_type',
        'start_date',
        'end_date',
        'salary',
        'work_hours_per_week',
        'status',
        'document_path',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'salary_structure_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'salary' => 'decimal:2',
        'work_hours_per_week' => 'decimal:2',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id')
            ->where('salary_structure_id', $this->salary_structure_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /*
    |--------------------------------------------------------------------------
    | Business Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Determines if this contract is the currently active contract.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Returns the effective base salary for payroll generation.
     * Prefers the contract's agreed salary over the structure's base salary.
     */
    public function effectiveSalary(): float
    {
        if ($this->salary && (float) $this->salary > 0) {
            return (float) $this->salary;
        }

        if ($this->salaryStructure) {
            return (float) $this->salaryStructure->base_salary;
        }

        return 0.0;
    }

    /**
     * Generate the next contract number sequentially (format: CTR-001).
     */
    public static function generateNextContractNumber(): string
    {
        $contracts = static::whereNotNull('contract_number')
            ->where(function ($query) {
                $query->where('contract_number', 'LIKE', 'CTR-%')
                    ->orWhere('contract_number', 'LIKE', 'CTR%');
            })
            ->pluck('contract_number');

        $maxNum = 0;
        foreach ($contracts as $contractNum) {
            if (preg_match('/^CTR-?(\d+)$/i', $contractNum, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        $nextCode = sprintf('CTR-%03d', $nextNum);

        while (static::where('contract_number', $nextCode)->exists()) {
            $nextNum++;
            $nextCode = sprintf('CTR-%03d', $nextNum);
        }

        return $nextCode;
    }

    /**
     * Get Khmer translation for contract type.
     */
    public function getContractTypeKhAttribute(): string
    {
        return match ($this->contract_type) {
            'permanent' => 'កិច្ចសន្យាមិនកំណត់ថិរវេលា',
            'fixed_term' => 'កិច្ចសន្យាមានកំណត់ថិរវេលា',
            'probation' => 'ការសាកល្បងការងារ',
            'part_time' => 'ការងារក្រៅម៉ោង',
            'intern' => 'កម្មសិក្សាការងារ',
            default => $this->contract_type ?? '—',
        };
    }
}
