<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class EmployeeStatus extends Model
{
    use HasFactory;

    protected $table = 'employee_statuses';

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'employee_status_id');
    }

    /**
     * Scope for active statuses.
     */
    public function scopeActive($query)
    {
        if (Schema::hasColumn('employee_statuses', 'is_active')) {
            return $query->where('is_active', true);
        }

        return $query->where('name', 'like', '%Active%');
    }

    /**
     * Get Khmer translation for status name.
     */
    public function getKhmerNameAttribute(): string
    {
        return static::translateToKhmer($this->name);
    }

    /**
     * Static helper to translate status name to Khmer.
     */
    public static function translateToKhmer(?string $name): string
    {
        return match (strtolower(trim($name ?? ''))) {
            'active' => 'សកម្ម',
            'contract' => 'ជាប់កិច្ចសន្យា',
            'on leave', 'leave' => 'កំពុងសុំច្បាប់',
            'probation' => 'សាកល្បងការងារ',
            'resigned' => 'លាលែងពីការងារ',
            'retired' => 'ចូលនិវត្តន៍',
            'suspended' => 'ព្យួរការងារ',
            'terminated' => 'បញ្ឈប់ពីការងារ',
            default => $name ?? 'មិនទាន់កំណត់',
        };
    }
}
