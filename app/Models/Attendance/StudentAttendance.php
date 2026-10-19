<?php

namespace App\Models\Attendance;

use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $table = 'student_attendances';

    protected $fillable = [
        'attendance_session_id',
        'student_id',
        'attendance_status_id',
        'remarks',
    ];

    protected $casts = [
        'id' => 'integer',
        'attendance_session_id' => 'integer',
        'student_id' => 'integer',
        'attendance_status_id' => 'integer',
    ];

    public function attendance_session()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function attendance_status()
    {
        return $this->belongsTo(AttendanceStatus::class, 'attendance_status_id');
    }

    public function attendance_corrections()
    {
        return $this->hasMany(AttendanceCorrection::class, 'student_attendance_id');
    }
}
