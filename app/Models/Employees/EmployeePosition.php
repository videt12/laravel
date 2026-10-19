<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeePosition extends Model
{
    use HasFactory;

    protected $table = 'employee_positions';

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
        return $this->hasMany(Employee::class, 'employee_position_id');
    }

    /**
     * Scope for active positions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Generate the next auto-incremented position code (format: POS-001).
     */
    public static function generateNextCode(): string
    {
        $positions = static::whereNotNull('code')
            ->where(function ($query) {
                $query->where('code', 'LIKE', 'POS-%')
                    ->orWhere('code', 'LIKE', 'POS%');
            })
            ->pluck('code');

        $maxNum = 0;
        foreach ($positions as $code) {
            if (preg_match('/^POS-?(\d+)$/i', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        $nextCode = sprintf('POS-%03d', $nextNum);

        while (static::where('code', $nextCode)->exists()) {
            $nextNum++;
            $nextCode = sprintf('POS-%03d', $nextNum);
        }

        return $nextCode;
    }
}
