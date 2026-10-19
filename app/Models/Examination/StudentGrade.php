<?php

namespace App\Models\Examination;

use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory;

    protected $table = 'student_grades';

    protected $fillable = [
        'exam_id',
        'student_id',
        'total_marks',
        'percentage',
        'grade',
        'gpa',
        'result_status',
    ];

    protected $casts = [
        'id' => 'integer',
        'exam_id' => 'integer',
        'student_id' => 'integer',
        'total_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'gpa' => 'decimal:2',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Recalculate and persist aggregate StudentGrade from subject-level ExamResult entries.
     */
    public static function recalculateForStudent(int $examId, int $studentId): ?self
    {
        $examSubjectIds = ExamSubject::whereHas('exam_schedule', function ($q) use ($examId) {
            $q->where('exam_id', $examId);
        })->pluck('id');

        if ($examSubjectIds->isEmpty()) {
            return null;
        }

        $results = ExamResult::whereIn('exam_subject_id', $examSubjectIds)
            ->where('student_id', $studentId)
            ->with('exam_subject')
            ->get();

        if ($results->isEmpty()) {
            return null;
        }

        $totalObtained = (float) $results->sum('marks_obtained');
        $totalMaxMarks = (float) $results->sum(function ($r) {
            return (float) ($r->exam_subject?->max_marks ?? 100);
        });

        $percentage = $totalMaxMarks > 0 ? round(($totalObtained / $totalMaxMarks) * 100, 2) : 0.0;

        $scale = GradeScale::getScaleForPercentage($percentage);
        $grade = $scale?->grade ?? 'F';
        $gpa = $scale?->gpa_point ?? 0.0;

        // Check if student passed: met pass mark in each paper and not F
        $allPassed = $results->every(function ($r) {
            $passMarks = (float) ($r->exam_subject?->pass_marks ?? 0);

            return (float) $r->marks_obtained >= $passMarks;
        });

        $status = ($allPassed && $grade !== 'F') ? 'passed' : 'failed';

        return static::updateOrCreate(
            ['exam_id' => $examId, 'student_id' => $studentId],
            [
                'total_marks' => $totalObtained,
                'percentage' => $percentage,
                'grade' => $grade,
                'gpa' => $gpa,
                'result_status' => $status,
            ]
        );
    }
}
