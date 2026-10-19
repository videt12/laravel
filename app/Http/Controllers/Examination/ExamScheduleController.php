<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Examination\Exam;
use App\Models\Examination\ExamSchedule;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    /**
     * Display a listing of exam schedules (redirects to unified examinations management).
     */
    public function index(Request $request)
    {
        return redirect()->route('examinations.index', array_merge(['tab' => 'schedules'], $request->query()));
    }

    /**
     * Authorize exam schedule management (only super-admin, admin, or non-teachers with exams.create).
     */
    protected function authorizeExamManagement(): void
    {
        $user = auth()->user();
        $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
        $canManageExams = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || (! $isTeacher && $user->canAccess('exams.create')));

        if (! $canManageExams) {
            abort(403, 'លោកអ្នកគ្មានសិទ្ធិចូលកាន់ ឬកែសម្រួលកាលវិភាគប្រឡងឡើយ។');
        }
    }

    /**
     * Show the form for creating a new exam schedule.
     */
    public function create(Request $request)
    {
        $this->authorizeExamManagement();

        $selectedExamId = $request->input('exam_id');
        $exams = Exam::with('academic_year')->orderByDesc('id')->get();
        $classes = SchoolClass::with(['grade', 'section', 'academic_year'])->orderBy('id')->get();
        $sections = Section::orderBy('name')->get();

        return view('examination.exam_schedules.create', compact('exams', 'classes', 'sections', 'selectedExamId'));
    }

    /**
     * Store a newly created exam schedule in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeExamManagement();

        $validated = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
        ]);

        // Academic Year consistency check
        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['class_id']);

        if ($schoolClass->academic_year_id !== $exam->academic_year_id) {
            return back()->withErrors([
                'class_id' => 'ថ្នាក់រៀនដែលបានជ្រើសរើស មិនស្ថិតក្នុងឆ្នាំសិក្សានៃការប្រឡងនេះឡើយ។',
            ])->withInput();
        }

        // Duplicate schedule check
        $exists = ExamSchedule::where('exam_id', $validated['exam_id'])
            ->where('class_id', $validated['class_id'])
            ->where(function ($q) use ($validated) {
                if (! empty($validated['section_id'])) {
                    $q->where('section_id', $validated['section_id']);
                } else {
                    $q->whereNull('section_id');
                }
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'class_id' => 'ថ្នាក់រៀននេះត្រូវបានកំណត់កាលវិភាគសម្រាប់ការប្រឡងនេះរួចហើយ។',
            ])->withInput();
        }

        $schedule = ExamSchedule::create($validated);

        return redirect()->route('examinations.index', ['tab' => 'schedules'])
            ->with('success', 'បានបង្កើតកាលវិភាគប្រឡងដោយជោគជ័យ។');
    }

    /**
     * Display the specified exam schedule.
     */
    public function show(ExamSchedule $examSchedule)
    {
        $this->authorizeExamManagement();

        $examSchedule->load([
            'exam.academic_year',
            'exam.exam_type',
            'schoolClass.grade',
            'section',
            'exam_subjects.subject',
            'exam_subjects.exam_results',
        ]);

        return view('examination.exam_schedules.show', compact('examSchedule'));
    }

    /**
     * Show the form for editing the specified exam schedule.
     */
    public function edit(ExamSchedule $examSchedule)
    {
        $this->authorizeExamManagement();

        $exams = Exam::with('academic_year')->orderByDesc('id')->get();
        $classes = SchoolClass::with(['grade', 'section', 'academic_year'])->orderBy('id')->get();
        $sections = Section::orderBy('name')->get();

        return view('examination.exam_schedules.edit', compact('examSchedule', 'exams', 'classes', 'sections'));
    }

    /**
     * Update the specified exam schedule in storage.
     */
    public function update(Request $request, ExamSchedule $examSchedule)
    {
        $this->authorizeExamManagement();

        $validated = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['class_id']);

        if ($schoolClass->academic_year_id !== $exam->academic_year_id) {
            return back()->withErrors([
                'class_id' => 'ថ្នាក់រៀនដែលបានជ្រើសរើស មិនស្ថិតក្នុងឆ្នាំសិក្សានៃការប្រឡងនេះឡើយ។',
            ])->withInput();
        }

        $exists = ExamSchedule::where('exam_id', $validated['exam_id'])
            ->where('class_id', $validated['class_id'])
            ->where('id', '!=', $examSchedule->id)
            ->where(function ($q) use ($validated) {
                if (! empty($validated['section_id'])) {
                    $q->where('section_id', $validated['section_id']);
                } else {
                    $q->whereNull('section_id');
                }
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'class_id' => 'ថ្នាក់រៀននេះត្រូវបានកំណត់កាលវិភាគសម្រាប់ការប្រឡងនេះរួចហើយ។',
            ])->withInput();
        }

        $examSchedule->update($validated);

        return redirect()->route('examinations.index', ['tab' => 'schedules'])
            ->with('success', 'បានកែប្រែកាលវិភាគប្រឡងដោយជោគជ័យ។');
    }

    /**
     * Remove the specified exam schedule from storage.
     */
    public function destroy(ExamSchedule $examSchedule)
    {
        $this->authorizeExamManagement();

        if ($examSchedule->exam_subjects()->exists()) {
            return redirect()->route('examinations.index', ['tab' => 'schedules'])
                ->with('error', 'មិនអាចលុបកាលវិភាគដែលមានភ្ជាប់វិញ្ញាសាប្រឡងរួចហើយនោះទេ។');
        }

        $examSchedule->delete();

        return redirect()->route('examinations.index', ['tab' => 'schedules'])
            ->with('success', 'បានលុបកាលវិភាគប្រឡងដោយជោគជ័យ។');
    }
}
