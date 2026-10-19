<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $table = 'employee_attendances';

    protected $fillable = [
        'employee_id',
        'card_id',
        'card_token',
        'attendance_date',
        'status',
        'verification_method',
        'verification_score',
        'liveness_verified',
        'in_time',
        'out_time',
        'afternoon_in_time',
        'afternoon_out_time',
        'remarks',
        'device_info',
    ];

    protected static function booted(): void
    {
        if (Schema::hasTable('employee_attendances')) {
            if (! Schema::hasColumn('employee_attendances', 'afternoon_in_time')) {
                try {
                    Schema::table('employee_attendances', function (Blueprint $table) {
                        $table->time('afternoon_in_time')->nullable()->after('out_time');
                        $table->time('afternoon_out_time')->nullable()->after('afternoon_in_time');
                    });
                } catch (\Throwable $e) {
                }
            }
        }
    }

    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'card_id' => 'integer',
        'attendance_date' => 'date',
        'verification_score' => 'float',
        'liveness_verified' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employeeCard(): BelongsTo
    {
        return $this->belongsTo(EmployeeCard::class, 'card_id');
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function getSnapshotUrlAttribute(): ?string
    {
        if (! empty($this->remarks) && preg_match('/Snapshot:\s*(attendance-snapshots\/[^\s\]\|]+)/', $this->remarks, $matches)) {
            return asset('storage/'.trim($matches[1]));
        }

        return null;
    }
}
