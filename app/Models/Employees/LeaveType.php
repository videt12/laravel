<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_types';

    protected $fillable = [
        'name',
        'code',
        'is_paid',
        'description',
        'days_allowed',
    ];

    protected $casts = [
        'id' => 'integer',
        'days_allowed' => 'integer',
        'is_paid' => 'boolean',
    ];

    public function employee_leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'leave_type_id');
    }

    public function leaves(): HasMany
    {
        return $this->employee_leaves();
    }

    public function getKhmerNameAttribute(): string
    {
        $khmerNames = [
            'Annual Leave' => 'ច្បាប់ឈប់សម្រាកប្រចាំឆ្នាំ',
            'Sick Leave' => 'ច្បាប់ឈឺ',
            'Casual Leave' => 'ច្បាប់ផ្ទាល់ខ្លួន',
            'Maternity Leave' => 'ច្បាប់លំហែមាតុភាព (សម្រាលកូន)',
            'Paternity Leave' => 'ច្បាប់លំហែបិតុភាព',
            'Study Leave' => 'ច្បាប់សិក្សា / បណ្តុះបណ្តាល',
            'Emergency Leave' => 'ច្បាប់បន្ទាន់',
            'Unpaid Leave' => 'ច្បាប់គ្មានប្រាក់បៀវត្ស',
            'Compensatory Leave' => 'ច្បាប់ប្តូរវេន / សងម៉ោង',
        ];

        return $khmerNames[$this->name] ?? $this->name;
    }
}
