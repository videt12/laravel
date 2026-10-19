<?php

namespace App\Models\Attendance;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Academics\TeacherAssignment;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceSession extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'attendance_sessions';

    protected $fillable = [
        'academic_year_id',
        'teacher_assignment_id',
        'class_id',
        'section_id',
        'attendance_date',
        'period',
        'recorded_by',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'academic_year_id' => 'integer',
        'teacher_assignment_id' => 'integer',
        'class_id' => 'integer',
        'section_id' => 'integer',
        'attendance_date' => 'date',
        'period' => 'integer',
        'recorded_by' => 'integer',
    ];

    /* ──────────────────────────────────────────
     |  Relationships
     ─────────────────────────────────────────── */

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /** @note method named 'class' – valid in PHP 7+ via string call */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /** Safe alias to avoid 'class' keyword collision in some contexts */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Optional link to TeacherAssignment.
     * Via teacherAssignment → classSubject → subject (Subject)
     * Via teacherAssignment → employee → user (Teacher name)
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class, 'teacher_assignment_id');
    }

    public function student_attendances()
    {
        return $this->hasMany(StudentAttendance::class, 'attendance_session_id');
    }

    /* ──────────────────────────────────────────
     |  Accessors
     ─────────────────────────────────────────── */

    /**
     * Returns "Period 1", "Period 2", etc.
     */
    public function getDisplayPeriodAttribute(): string
    {
        return 'Period '.($this->period ?? 1);
    }

    /**
     * Returns a human-readable class label, e.g. "Grade 1 — Section B".
     * Uses already-loaded relations when available.
     */
    public function getClassLabelAttribute(): string
    {
        $grade = $this->relationLoaded('schoolClass')
                    ? ($this->schoolClass?->grade?->name ?? '')
                    : ($this->schoolClass?->grade?->name ?? '');
        $section = $this->relationLoaded('section')
                    ? ($this->section?->name ?? '')
                    : ($this->section?->name ?? '');

        if ($grade && $section) {
            return $grade.' — '.$section;
        }

        return $grade ?: $section ?: '—';
    }

    /**
     * Bootstrap badge class for status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'closed' ? 'bg-secondary' : 'bg-success';
    }

    /**
     * Human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'closed' ? 'Closed' : 'Open';
    }

    /**
     * Subject name derived through teacherAssignment → classSubject → subject.
     * Returns null if not linked.
     */
    public function getSubjectNameAttribute(): ?string
    {
        return $this->teacherAssignment?->class_subject?->subject?->name;
    }

    /**
     * Teacher name derived through teacherAssignment → employee → user.
     * Returns null if not linked.
     */
    public function getTeacherNameAttribute(): ?string
    {
        return $this->teacherAssignment?->employee?->user?->name;
    }
}
