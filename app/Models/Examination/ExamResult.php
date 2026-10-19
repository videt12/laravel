<?php

namespace App\Models\Examination;

use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $table = 'exam_results';

    protected $fillable = [
        'exam_subject_id',
        'student_id',
        'marks_obtained',
        'grade',
        'remarks',
    ];

    protected $casts = [
        'id' => 'integer',
        'exam_subject_id' => 'integer',
        'student_id' => 'integer',
        'marks_obtained' => 'decimal:2',
    ];

    public function exam_subject()
    {
        return $this->belongsTo(ExamSubject::class, 'exam_subject_id');
    }

    public function examSubject()
    {
        return $this->exam_subject();
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
