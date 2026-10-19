<?php

namespace App\Models\Payroll;

use App\Models\Employees\EmployeeContract;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalaryStructure extends Model
{
    protected $table = 'salary_structures';

    protected $fillable = [
        'name',
        'code',
        'description',
        'employee_department_id',
        'employee_position_id',
        'base_salary',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'employee_department_id' => 'integer',
        'employee_position_id' => 'integer',
        'base_salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee_department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'employee_department_id');
    }

    public function department(): BelongsTo
    {
        return $this->employee_department();
    }

    public function employee_position(): BelongsTo
    {
        return $this->belongsTo(EmployeePosition::class, 'employee_position_id');
    }

    public function position(): BelongsTo
    {
        return $this->employee_position();
    }

    public function salaryComponents(): HasMany
    {
        return $this->hasMany(SalaryComponent::class, 'salary_structure_id');
    }

    /**
     * Only the active components belonging to this structure.
     */
    public function activeComponents(): HasMany
    {
        return $this->salaryComponents()->where('is_active', true)->orderBy('order');
    }

    public function employeeContracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class, 'salary_structure_id');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'salary_structure_id');
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

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Count of active components assigned to this structure.
     */
    public function getActiveComponentCountAttribute(): int
    {
        return $this->salaryComponents()->where('is_active', true)->count();
    }

    /**
     * Generate the next salary structure code sequentially (format: TSG00001).
     */
    public static function generateNextCode(): string
    {
        $latest = static::orderByDesc('id')->first();
        $nextSeq = 1;

        if ($latest) {
            if (preg_match('/TSG(\d+)/i', $latest->code, $matches)) {
                $nextSeq = (int) $matches[1] + 1;
            } else {
                $nextSeq = (int) $latest->id + 1;
            }
        }

        $candidate = 'TSG'.str_pad((string) $nextSeq, 5, '0', STR_PAD_LEFT);

        while (static::where('code', $candidate)->exists()) {
            $nextSeq++;
            $candidate = 'TSG'.str_pad((string) $nextSeq, 5, '0', STR_PAD_LEFT);
        }

        return $candidate;
    }
}
