<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Academics\Subject;
use App\Models\Examination\ExamSchedule;
use App\Models\Examination\ExamSubject;
use Illuminate\Http\Request;

class ExamSubjectController extends Controller
{
    /**
     * Display a listing of exam subjects (redirects to unified examinations management).
     */
    public function index(Request $request)
    {
        return redirect()->route('examinations.index', array_merge(['tab' => 'subjects'], $request->query()));
    }

    /**
     * Authorize exam subject management (only super-admin, admin, or non-teachers with exams.create).
     */
    protected function authorizeExamManagement(): void
    {
        $user = auth()->user();
        $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
        $canManageExams = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || (! $isTeacher && $user->canAccess('exams.create')));

        if (! $canManageExams) {
            abort(403, 'លោកអ្នកគ្មានសិទ្ធិចូលកាន់ ឬកែសម្រួលមុខវិជ្ជាប្រឡងឡើយ។');
        }
    }

    /**
     * Show the form for creating a new exam subject.
     */
    public function create(Request $request)
    {
        $this->authorizeExamManagement();

        $selectedScheduleId = $request->input('exam_schedule_id');
        $schedules = ExamSchedule::with(['exam', 'schoolClass.grade', 'section'])->orderByDesc('id')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('examination.exam_subjects.create', compact('schedules', 'subjects', 'selectedScheduleId'));
    }

    /**
     * Store a newly created exam subject in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeExamManagement();

        $validated = $request->validate([
            'exam_schedule_id' => ['required', 'integer', 'exists:exam_schedules,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'exam_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'max_marks' => ['required', 'numeric', 'min:1', 'max:1000'],
            'pass_marks' => ['required', 'numeric', 'min:0', 'lte:max_marks'],
        ], [
            'pass_marks.lte' => 'ពិន្ទុជាប់មិនអាចលើសពីពិន្ទុអតិបរមាឡើយ។',
            'max_marks.required' => 'សូមបញ្ចូលពិន្ទុអតិបរមា។',
            'pass_marks.required' => 'សូមបញ្ចូលពិន្ទុជាប់។',
            'subject_id.required' => 'សូមជ្រើសរើសមុខវិជ្ជា។',
            'exam_schedule_id.required' => 'សូមជ្រើសរើសកាលវិភាគប្រឡង។',
        ]);

        // Duplicate check: Same subject on the same schedule
        $exists = ExamSubject::where('exam_schedule_id', $validated['exam_schedule_id'])
            ->where('subject_id', $validated['subject_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'subject_id' => 'មុខវិជ្ជានេះត្រូវបានភ្ជាប់ទៅកាលវិភាគប្រឡងដែលបានជ្រើសរើសរួចហើយ។',
            ])->withInput();
        }

        // Validate time if both provided
        if (! empty($validated['start_time']) && ! empty($validated['end_time'])) {
            if ($validated['end_time'] <= $validated['start_time']) {
                return back()->withErrors([
                    'end_time' => 'ម៉ោងបញ្ចប់ត្រូវតែនៅក្រោយម៉ោងចាប់ផ្តើម។',
                ])->withInput();
            }
        }

        $examSubject = ExamSubject::create($validated);

        return redirect()->route('examinations.index', ['tab' => 'subjects'])
            ->with('success', 'បានបង្កើតវិញ្ញាសាប្រឡងដោយជោគជ័យ។');
    }

    /**
     * Display the specified exam subject.
     */
    public function show(ExamSubject $examSubject)
    {
        $this->authorizeExamManagement();

        $examSubject->load([
            'exam_schedule.exam.academic_year',
            'exam_schedule.schoolClass.grade',
            'exam_schedule.section',
            'subject',
            'exam_results.student.user',
        ]);

        return view('examination.exam_subjects.show', compact('examSubject'));
    }

    /**
     * Show the form for editing the specified exam subject.
     */
    public function edit(ExamSubject $examSubject)
    {
        $this->authorizeExamManagement();

        $schedules = ExamSchedule::with(['exam', 'schoolClass.grade', 'section'])->orderByDesc('id')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('examination.exam_subjects.edit', compact('examSubject', 'schedules', 'subjects'));
    }

    /**
     * Update the specified exam subject in storage.
     */
    public function update(Request $request, ExamSubject $examSubject)
    {
        $this->authorizeExamManagement();

        $validated = $request->validate([
            'exam_schedule_id' => ['required', 'integer', 'exists:exam_schedules,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'exam_date' => ['nullable', 'date'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'max_marks' => ['required', 'numeric', 'min:1', 'max:1000'],
            'pass_marks' => ['required', 'numeric', 'min:0', 'lte:max_marks'],
        ], [
            'pass_marks.lte' => 'ពិន្ទុជាប់មិនអាចលើសពីពិន្ទុអតិបរមាឡើយ។',
            'max_marks.required' => 'សូមបញ្ចូលពិន្ទុអតិបរមា។',
            'pass_marks.required' => 'សូមបញ្ចូលពិន្ទុជាប់។',
            'subject_id.required' => 'សូមជ្រើសរើសមុខវិជ្ជា។',
            'exam_schedule_id.required' => 'សូមជ្រើសរើសកាលវិភាគប្រឡង។',
        ]);

        $exists = ExamSubject::where('exam_schedule_id', $validated['exam_schedule_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('id', '!=', $examSubject->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'subject_id' => 'មុខវិជ្ជានេះត្រូវបានភ្ជាប់ទៅកាលវិភាគប្រឡងដែលបានជ្រើសរើសរួចហើយ។',
            ])->withInput();
        }

        if (! empty($validated['start_time']) && ! empty($validated['end_time'])) {
            if ($validated['end_time'] <= $validated['start_time']) {
                return back()->withErrors([
                    'end_time' => 'ម៉ោងបញ្ចប់ត្រូវតែនៅក្រោយម៉ោងចាប់ផ្តើម។',
                ])->withInput();
            }
        }

        $examSubject->update($validated);

        return redirect()->route('examinations.index', ['tab' => 'subjects'])
            ->with('success', 'បានកែប្រែវិញ្ញាសាប្រឡងដោយជោគជ័យ។');
    }

    /**
     * Remove the specified exam subject from storage.
     */
    public function destroy(ExamSubject $examSubject)
    {
        $this->authorizeExamManagement();

        if ($examSubject->exam_results()->exists()) {
            return redirect()->route('examinations.index', ['tab' => 'subjects'])
                ->with('error', 'មិនអាចលុបវិញ្ញាសាប្រឡងដែលមានលទ្ធផលពិន្ទុសិស្សរួចហើយនោះទេ។');
        }

        $examSubject->delete();

        return redirect()->route('examinations.index', ['tab' => 'subjects'])
            ->with('success', 'បានលុបវិញ្ញាសាប្រឡងដោយជោគជ័យ។');
    }
}
