<?php

namespace App\Models\Assignments;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Academics\Subject;
use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'assignments';

    protected $fillable = [
        'class_id',
        'section_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'due_date',
        'total_marks',
    ];

    protected $casts = [
        'id' => 'integer',
        'class_id' => 'integer',
        'section_id' => 'integer',
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
        'due_date' => 'date',
        'total_marks' => 'decimal:2',
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Employee::class, 'teacher_id');
    }

    public function assignment_attachments()
    {
        return $this->hasMany(AssignmentAttachment::class, 'assignment_id');
    }

    public function assignment_submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id');
    }
}
