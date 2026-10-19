<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolTerm;
use App\Models\Examination\Exam;
use App\Models\Examination\ExamType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    /**
     * Display a listing of exams (redirects to unified examinations management).
     */
    public function index(Request $request)
    {
        return redirect()->route('examinations.index', array_merge(['tab' => 'exams'], $request->query()));
    }

    /**
     * Authorize exam management (only super-admin, admin, or non-teachers with exams.create).
     */
    protected function authorizeExamManagement(): void
    {
        $user = auth()->user();
        $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
        $canManageExams = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || (! $isTeacher && $user->canAccess('exams.create')));

        if (! $canManageExams) {
            abort(403, 'លោកអ្នកគ្មានសិទ្ធិចូលកាន់ ឬកែសម្រួលការប្រឡងនេះឡើយ។');
        }
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create()
    {
        $this->authorizeExamManagement();

        $examTypes = ExamType::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $schoolTerms = SchoolTerm::orderBy('name')->get();

        return view('examination.exams.create', compact('examTypes', 'academicYears', 'schoolTerms'));
    }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeExamManagement();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('exams')->where(fn ($q) => $q->where('academic_year_id', $request->academic_year_id)),
            ],
            'exam_type_id' => ['required', 'integer', 'exists:exam_types,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'school_term_id' => ['nullable', 'integer', 'exists:school_terms,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'name.unique' => 'An exam with this name already exists for the selected academic year.',
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
        ]);

        $exam = Exam::create($validated);

        return redirect()->route('examinations.index', ['tab' => 'exams'])
            ->with('success', 'Exam created successfully.');
    }

    /**
     * Display the specified exam.
     */
    public function show(Exam $exam)
    {
        $this->authorizeExamManagement();

        $exam->load([
            'exam_type',
            'academic_year',
            'school_term',
            'exam_schedules.schoolClass.grade',
            'exam_schedules.section',
            'exam_schedules.exam_subjects.subject',
            'exam_schedules.exam_subjects.exam_results',
            'student_grades.student.user',
        ]);

        return view('examination.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit(Exam $exam)
    {
        $this->authorizeExamManagement();

        $examTypes = ExamType::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $schoolTerms = SchoolTerm::orderBy('name')->get();

        return view('examination.exams.edit', compact('exam', 'examTypes', 'academicYears', 'schoolTerms'));
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $this->authorizeExamManagement();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('exams')
                    ->where(fn ($q) => $q->where('academic_year_id', $request->academic_year_id))
                    ->ignore($exam->id),
            ],
            'exam_type_id' => ['required', 'integer', 'exists:exam_types,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'school_term_id' => ['nullable', 'integer', 'exists:school_terms,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ], [
            'name.unique' => 'An exam with this name already exists for the selected academic year.',
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
        ]);

        $exam->update($validated);

        return redirect()->route('examinations.index', ['tab' => 'exams'])
            ->with('success', 'Exam updated successfully.');
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy(Exam $exam)
    {
        $this->authorizeExamManagement();

        if ($exam->exam_schedules()->exists() || $exam->student_grades()->exists()) {
            return redirect()->route('examinations.index', ['tab' => 'exams'])
                ->with('error', 'Cannot delete exam with linked schedules or grades. Please remove schedules first.');
        }

        $exam->delete();

        return redirect()->route('examinations.index', ['tab' => 'exams'])
            ->with('success', 'Exam deleted successfully.');
    }
}
