<?php

namespace App\Models\Academics;

use App\Models\Attendance\AttendanceSession;
use App\Models\Core\AcademicYear;
use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    use HasFactory;

    protected $table = 'teacher_assignments';

    protected $fillable = [
        'employee_id',
        'class_id',
        'class_subject_id',
        'academic_year_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'class_id' => 'integer',
        'class_subject_id' => 'integer',
        'academic_year_id' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function school_class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function class_subject()
    {
        return $this->belongsTo(ClassSubject::class, 'class_subject_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function getClassAttribute(): ?SchoolClass
    {
        return $this->school_class ?? $this->class_subject?->class;
    }

    public function attendance_sessions()
    {
        return $this->hasMany(AttendanceSession::class, 'teacher_assignment_id');
    }
}
