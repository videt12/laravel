<?php

namespace App\Models\Assignments;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentGrade extends Model
{
    use HasFactory;

    protected $table = 'assignment_grades';

    protected $fillable = [
        'assignment_submission_id',
        'marks_obtained',
        'grade',
        'feedback',
        'graded_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'assignment_submission_id' => 'integer',
        'marks_obtained' => 'decimal:2',
        'graded_by' => 'integer',
    ];

    public function assignment_submission()
    {
        return $this->belongsTo(AssignmentSubmission::class, 'assignment_submission_id');
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
