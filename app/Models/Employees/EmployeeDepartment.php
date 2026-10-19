<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeDepartment extends Model
{
    use HasFactory;

    protected $table = 'employee_departments';

    protected $fillable = [
        'name',
        'code',
        'status',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'employee_department_id');
    }

    /**
     * Scope for active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Generate the next auto-incremented department code (format: DEP-001).
     */
    public static function generateNextCode(): string
    {
        $departments = static::whereNotNull('code')
            ->where(function ($query) {
                $query->where('code', 'LIKE', 'DEP-%')
                    ->orWhere('code', 'LIKE', 'DEP%');
            })
            ->pluck('code');

        $maxNum = 0;
        foreach ($departments as $code) {
            if (preg_match('/^DEP-?(\d+)$/i', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        $nextCode = sprintf('DEP-%03d', $nextNum);

        while (static::where('code', $nextCode)->exists()) {
            $nextNum++;
            $nextCode = sprintf('DEP-%03d', $nextNum);
        }

        return $nextCode;
    }
}
