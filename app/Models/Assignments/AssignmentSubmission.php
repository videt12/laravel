<?php

namespace App\Models\Assignments;

use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentSubmission extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'assignment_submissions';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'id' => 'integer',
        'assignment_id' => 'integer',
        'student_id' => 'integer',
        'submission_date' => 'date',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function assignmentGrade()
    {
        return $this->hasOne(AssignmentGrade::class, 'assignment_submission_id');
    }
}
