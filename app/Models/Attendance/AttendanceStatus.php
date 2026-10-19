<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    use HasFactory;

    protected $table = 'attendance_statuses';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function student_attendances()
    {
        return $this->hasMany(StudentAttendance::class, 'attendance_status_id');
    }

    public function old_attendance_corrections()
    {
        return $this->hasMany(AttendanceCorrection::class, 'old_status_id');
    }

    public function new_attendance_corrections()
    {
        return $this->hasMany(AttendanceCorrection::class, 'new_status_id');
    }

    /**
     * Get Khmer localized name for the status.
     */
    public function getKhmerNameAttribute(): string
    {
        $codeMap = [
            'P'          => 'វត្តមាន',
            'A'          => 'អវត្តមាន',
            'L'          => 'មកយឺត',
            'EA'         => 'អវត្តមានមានច្បាប់',
            'HD'         => 'កន្លះថ្ងៃ',
            'OL'         => 'សុំច្បាប់',
            'SL'         => 'ច្បាប់ឈឺ',
            'H'          => 'ឈប់សម្រាក',
            'PERM'       => 'មានច្បាប់',
            'PERMISSION' => 'មានច្បាប់',
        ];

        $code = strtoupper(trim($this->code ?? ''));
        if (isset($codeMap[$code])) {
            return $codeMap[$code];
        }

        $nameMap = [
            'present'        => 'វត្តមាន',
            'absent'         => 'អវត្តមាន',
            'late'           => 'មកយឺត',
            'excused absent' => 'អវត្តមានមានច្បាប់',
            'half day'       => 'កន្លះថ្ងៃ',
            'on leave'       => 'សុំច្បាប់',
            'sick leave'     => 'ច្បាប់ឈឺ',
            'holiday'        => 'ឈប់សម្រាក',
            'permission'     => 'មានច្បាប់',
        ];

        $lower = strtolower(trim($this->name ?? ''));
        return $nameMap[$lower] ?? ($this->name ?? '');
    }
}
