<?php

namespace App\Models\Examination;

use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolTerm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'exam_type_id',
        'academic_year_id',
        'school_term_id',
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'id' => 'integer',
        'exam_type_id' => 'integer',
        'academic_year_id' => 'integer',
        'school_term_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function exam_type()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function school_term()
    {
        return $this->belongsTo(SchoolTerm::class, 'school_term_id');
    }

    public function exam_schedules()
    {
        return $this->hasMany(ExamSchedule::class, 'exam_id');
    }

    public function student_grades()
    {
        return $this->hasMany(StudentGrade::class, 'exam_id');
    }

    public function exam_subjects()
    {
        return $this->hasManyThrough(
            ExamSubject::class,
            ExamSchedule::class,
            'exam_id',
            'exam_schedule_id'
        );
    }
}
