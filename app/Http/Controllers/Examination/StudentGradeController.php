<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Examination\Exam;
use App\Models\Examination\ExamResult;
use App\Models\Examination\ExamSubject;
use App\Models\Examination\StudentGrade;
use App\Models\Students\Student;
use Illuminate\Http\Request;

class StudentGradeController extends Controller
{
    /**
     * Display overall student grades (redirects to unified examinations management).
     */
    public function index(Request $request)
    {
        return redirect()->route('examinations.index', array_merge(['tab' => 'results', 'subtab' => 'grades'], $request->query()));
    }

    /**
     * Display a comprehensive exam report card for a student.
     */
    public function show(StudentGrade $studentGrade)
    {
        $studentGrade->load([
            'exam.academic_year',
            'exam.exam_type',
            'student.user',
            'student.schoolClass.grade',
            'student.schoolClass.section',
        ]);

        $examId = $studentGrade->exam_id;
        $studentId = $studentGrade->student_id;

        // Fetch all subject-level results for this student in this exam
        $examSubjectIds = ExamSubject::whereHas('exam_schedule', function ($q) use ($examId) {
            $q->where('exam_id', $examId);
        })->pluck('id');

        $results = ExamResult::whereIn('exam_subject_id', $examSubjectIds)
            ->where('student_id', $studentId)
            ->with(['exam_subject.subject'])
            ->get();

        $subjectResults = $results;

        return view('examination.student_grades.show', compact('studentGrade', 'subjectResults', 'results'));
    }

    /**
     * Bulk recalculate all student grades for an exam.
     */
    public function recalculateAll(Request $request, Exam $exam)
    {
        $user = auth()->user();
        $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
        $canManageExams = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || (! $isTeacher && $user->canAccess('exams.create')));

        if (! $canManageExams) {
            abort(403, 'លោកអ្នកគ្មានសិទ្ធិគណនាពិន្ទុរួមឡើយ។');
        }

        $examSubjectIds = ExamSubject::whereHas('exam_schedule', fn ($q) => $q->where('exam_id', $exam->id))->pluck('id');

        $studentIds = ExamResult::whereIn('exam_subject_id', $examSubjectIds)
            ->pluck('student_id')
            ->unique();

        $count = 0;
        foreach ($studentIds as $studentId) {
            StudentGrade::recalculateForStudent($exam->id, $studentId);
            $count++;
        }

        return back()->with('success', "Recalculated grades for {$count} student(s) successfully.");
    }
}
