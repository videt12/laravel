<?php

namespace App\Models\Examination;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $table = 'exam_schedules';

    protected $fillable = [
        'exam_id',
        'class_id',
        'section_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'exam_id' => 'integer',
        'class_id' => 'integer',
        'section_id' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function schoolClass()
    {
        return $this->class();
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function exam_subjects()
    {
        return $this->hasMany(ExamSubject::class, 'exam_schedule_id');
    }
}
