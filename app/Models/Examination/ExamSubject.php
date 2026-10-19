<?php

namespace App\Models\Examination;

use App\Models\Academics\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;

    protected $table = 'exam_subjects';

    protected $fillable = [
        'exam_schedule_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'max_marks',
        'pass_marks',
    ];

    protected $casts = [
        'id' => 'integer',
        'exam_schedule_id' => 'integer',
        'subject_id' => 'integer',
        'exam_date' => 'date',
        'max_marks' => 'decimal:2',
        'pass_marks' => 'decimal:2',
    ];

    public function exam_schedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function examSchedule()
    {
        return $this->exam_schedule();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function exam_results()
    {
        return $this->hasMany(ExamResult::class, 'exam_subject_id');
    }

    public function examResults()
    {
        return $this->exam_results();
    }

    public function exam()
    {
        return $this->hasOneThrough(
            Exam::class,
            ExamSchedule::class,
            'id',
            'id',
            'exam_schedule_id',
            'exam_id'
        );
    }
}
