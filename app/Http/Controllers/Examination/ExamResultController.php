<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Examination\ExamResult;
use App\Models\Examination\ExamSubject;
use App\Models\Examination\GradeScale;
use App\Models\Examination\StudentGrade;
use App\Models\Students\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    /**
     * Display a listing of exam results / papers ready for scoring (redirects to unified examinations management).
     */
    public function index(Request $request)
    {
        return redirect()->route('examinations.index', array_merge(['tab' => 'results', 'subtab' => 'results'], $request->query()));
    }

    /**
     * Display the score entry sheet for an exam subject.
     */
    public function entry(ExamSubject $examSubject)
    {
        $examSubject->load([
            'exam_schedule.exam.academic_year',
            'exam_schedule.schoolClass.grade',
            'exam_schedule.section',
            'subject',
        ]);

        $this->authorizeTeacherForExamSubject($examSubject);

        $classId = $examSubject->exam_schedule->class_id;
        $sectionId = $examSubject->exam_schedule->section_id;

        // Load active students in the schedule's class
        $studentsQuery = Student::active()
            ->where('school_class_id', $classId)
            ->with(['user', 'studentStatus']);

        if ($sectionId) {
            $studentsQuery->where(function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId)->orWhereNull('section_id');
            });
        }

        $students = $studentsQuery->get()->sortBy(fn ($s) => $s->user?->name ?? $s->admission_number);

        $existingResults = ExamResult::where('exam_subject_id', $examSubject->id)
            ->get()
            ->keyBy('student_id');

        return view('examination.results.scores', compact('examSubject', 'students', 'existingResults'));
    }

    public function scoreEntry(ExamSubject $examSubject)
    {
        return $this->entry($examSubject);
    }

    public function storeScores(Request $request, ExamSubject $examSubject)
    {
        return $this->saveScores($request, $examSubject);
    }

    /**
     * Store a single exam result via standard CRUD.
     */
    public function store(Request $request)
    {
        $request->validate([
            'exam_subject_id' => ['required', 'exists:exam_subjects,id'],
            'student_id' => ['required', 'exists:students,id'],
            'marks_obtained' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $examSubject = ExamSubject::with('exam_schedule.exam')->findOrFail($request->exam_subject_id);
        $this->authorizeTeacherForExamSubject($examSubject);

        $maxMarks = (float) ($examSubject->max_marks ?? 100);
        if ((float) $request->marks_obtained > $maxMarks) {
            return back()->withErrors(['marks_obtained' => "Marks obtained cannot exceed maximum marks ({$maxMarks})."])->withInput();
        }

        // Validate student belongs to schedule's class
        $classId = $examSubject->exam_schedule->class_id;
        $student = Student::findOrFail($request->student_id);
        if ($student->school_class_id != $classId) {
            return back()->withErrors(['student_id' => 'Student does not belong to the class scheduled for this exam paper.'])->withInput();
        }

        // Duplicate check
        $exists = ExamResult::where('exam_subject_id', $examSubject->id)
            ->where('student_id', $student->id)
            ->exists();
        if ($exists) {
            return back()->withErrors(['student_id' => 'An exam result for this student and subject paper already exists.'])->withInput();
        }

        // Grade scale
        $percentage = $maxMarks > 0 ? ((float) $request->marks_obtained / $maxMarks) * 100 : 0;
        $scale = GradeScale::getScaleForPercentage($percentage);

        $examResult = ExamResult::create([
            'exam_subject_id' => $examSubject->id,
            'student_id' => $student->id,
            'marks_obtained' => $request->marks_obtained,
            'grade' => $scale ? $scale->grade : null,
            'remarks' => $request->remarks,
        ]);

        // Recalculate overall aggregate StudentGrade for this exam
        $examId = $examSubject->exam_schedule?->exam_id;
        if ($examId) {
            StudentGrade::recalculateForStudent($examId, $student->id);
        }

        return redirect()->route('examinations.index', ['tab' => 'results', 'subtab' => 'results'])
            ->with('success', 'Exam result recorded successfully.');
    }

    /**
     * Update a single exam result via standard CRUD.
     */
    public function update(Request $request, ExamResult $examResult)
    {
        $examSubject = $examResult->exam_subject;
        $this->authorizeTeacherForExamSubject($examSubject);

        $maxMarks = (float) ($examSubject->max_marks ?? 100);

        $request->validate([
            'marks_obtained' => ['required', 'numeric', 'min:0', "max:{$maxMarks}"],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $percentage = $maxMarks > 0 ? ((float) $request->marks_obtained / $maxMarks) * 100 : 0;
        $scale = GradeScale::getScaleForPercentage($percentage);

        $examResult->update([
            'marks_obtained' => $request->marks_obtained,
            'grade' => $scale ? $scale->grade : null,
            'remarks' => $request->remarks,
        ]);

        $examId = $examSubject->exam_schedule?->exam_id;
        if ($examId) {
            StudentGrade::recalculateForStudent($examId, $examResult->student_id);
        }

        return redirect()->route('examinations.index', ['tab' => 'results', 'subtab' => 'results'])
            ->with('success', 'Exam result updated successfully.');
    }

    /**
     * Save bulk scores from the entry sheet.
     */
    public function saveScores(Request $request, ExamSubject $examSubject)
    {
        $this->authorizeTeacherForExamSubject($examSubject);

        $maxMarks = (float) ($examSubject->max_marks ?? 100);

        $request->validate([
            'scores' => ['required', 'array'],
            'scores.*.marks_obtained' => ['nullable', 'numeric', 'min:0', "max:{$maxMarks}"],
            'scores.*.remarks' => ['nullable', 'string', 'max:500'],
        ], [
            'scores.*.marks_obtained.max' => "Marks obtained cannot exceed maximum marks ({$maxMarks}).",
            'scores.*.marks_obtained.min' => 'Marks cannot be negative.',
        ]);

        $scores = $request->input('scores', []);
        $examId = $examSubject->exam_schedule->exam_id;
        $classId = $examSubject->exam_schedule->class_id;

        DB::transaction(function () use ($scores, $examSubject, $maxMarks, $examId, $classId) {
            foreach ($scores as $studentId => $data) {
                // Verify student belongs to this class
                $student = Student::find($studentId);
                if (! $student || $student->school_class_id != $classId) {
                    continue;
                }

                $marks = $data['marks_obtained'] !== null && $data['marks_obtained'] !== ''
                    ? (float) $data['marks_obtained']
                    : null;

                $remarks = $data['remarks'] ?? null;

                if ($marks !== null) {
                    $pct = $maxMarks > 0 ? round(($marks / $maxMarks) * 100, 2) : 0.0;
                    $scale = GradeScale::getScaleForPercentage($pct);
                    $grade = $scale?->grade ?? 'F';

                    ExamResult::updateOrCreate(
                        [
                            'exam_subject_id' => $examSubject->id,
                            'student_id' => $studentId,
                        ],
                        [
                            'marks_obtained' => $marks,
                            'grade' => $grade,
                            'remarks' => $remarks,
                        ]
                    );

                    // Recalculate overall aggregate StudentGrade for this exam
                    StudentGrade::recalculateForStudent($examId, $studentId);
                } else {
                    // If marks was cleared, remove the result and recalculate
                    ExamResult::where('exam_subject_id', $examSubject->id)
                        ->where('student_id', $studentId)
                        ->delete();

                    StudentGrade::recalculateForStudent($examId, $studentId);
                }
            }
        });

        return redirect()->route('exam-subjects.scores', $examSubject->id)
            ->with('success', 'Student scores recorded and grades updated successfully.');
    }

    /**
     * Display the specified exam result.
     */
    public function show(ExamResult $examResult)
    {
        $examResult->load([
            'exam_subject.exam_schedule.exam',
            'exam_subject.subject',
            'student.user',
            'student.schoolClass.grade',
        ]);

        return view('examination.results.show', compact('examResult'));
    }

    /**
     * Remove the specified exam result from storage.
     */
    public function destroy(ExamResult $examResult)
    {
        $examId = $examResult->exam_subject?->exam_schedule?->exam_id;
        $studentId = $examResult->student_id;

        $examResult->delete();

        if ($examId && $studentId) {
            StudentGrade::recalculateForStudent($examId, $studentId);
        }

        return back()->with('success', 'Exam result deleted.');
    }

    /* ──────────────────────────────────────────────────────────────
     |  Authorization Helpers
     ─────────────────────────────────────────────────────────────── */

    private function getAuthContext($user): array
    {
        $adminSlugs = ['admin', 'super_admin', 'super-admin', 'principal', 'superadmin'];
        $isAdmin = false;
        if ($user) {
            $isAdmin = $user->roles()
                ->where(function ($q) use ($adminSlugs) {
                    $q->whereIn('slug', $adminSlugs)
                        ->orWhereIn(DB::raw('LOWER(name)'), ['admin', 'super admin', 'principal']);
                })->exists();
        }

        $employee = null;
        $hasTeacherRole = false;
        if ($user && ! $isAdmin) {
            $employee = $user->employee ?? Employee::where('user_id', $user->id)->first();
            $hasTeacherRole = $user->roles()->where('slug', 'teacher')->exists();
        }

        return [
            'isAdmin' => $isAdmin,
            'isTeacher' => ! $isAdmin && ($employee !== null || $hasTeacherRole),
            'employee' => $employee,
        ];
    }

    private function authorizeTeacherForExamSubject(ExamSubject $examSubject): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access.');
        }

        $auth = $this->getAuthContext($user);
        if ($auth['isAdmin']) {
            return;
        }

        // Allow teachers and users with exam permissions to enter scores
        $isTeacher = $user->hasRole('teacher') || $user->hasRole('Teacher') || ($auth['isTeacher'] ?? false) || $user->employee !== null;
        $canEnterScores = $isTeacher || $user->canAccess('exams.view') || $user->canAccess('exams.create') || $user->canAccess('exams.edit');

        if ($canEnterScores) {
            return;
        }

        abort(403, 'លោកអ្នកគ្មានសិទ្ធិបញ្ចូលពិន្ទុឡើយ។');
    }
}
