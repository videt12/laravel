<?php

namespace App\Models\Attendance;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
{
    use HasFactory;

    protected $table = 'attendance_corrections';

    protected $fillable = [
        'student_attendance_id',
        'old_status_id',
        'new_status_id',
        'reason',
        'requested_by',
        'approved_by',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_attendance_id' => 'integer',
        'old_status_id' => 'integer',
        'new_status_id' => 'integer',
        'requested_by' => 'integer',
        'approved_by' => 'integer',
    ];

    public function student_attendance()
    {
        return $this->belongsTo(StudentAttendance::class, 'student_attendance_id');
    }

    public function oldStatus()
    {
        return $this->belongsTo(AttendanceStatus::class, 'old_status_id');
    }

    public function newStatus()
    {
        return $this->belongsTo(AttendanceStatus::class, 'new_status_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
