<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\ClassSubject;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Subject;
use App\Models\Academics\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return redirect()->route('academics.index', ['tab' => 'class-subjects'] + $request->query());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = SchoolClass::with(['grade', 'section', 'academic_year'])->get();
        $subjects = Subject::orderBy('name')->get();

        return view('academics.class_subjects.create', compact('classes', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('subject_ids')) {
            return $this->sync($request);
        }

        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
                Rule::unique('class_subjects', 'subject_id')->where('class_id', $request->class_id),
            ],
        ], [
            'subject_id.unique' => 'មុខវិជ្ជានេះត្រូវបានកំណត់សម្រាប់ថ្នាក់នេះរួចហើយ។',
        ]);

        $classSubject = ClassSubject::create($validated);

        return redirect()->route('academics.index', ['tab' => 'class-subjects'])
            ->with('success', 'បានកំណត់មុខវិជ្ជាសម្រាប់ថ្នាក់ជោគជ័យ!');
    }

    /**
     * Sync/Batch assign subjects to a class.
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'subject_ids' => ['nullable', 'array'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],
        ]);

        $classId = $validated['class_id'];
        $subjectIds = $validated['subject_ids'] ?? [];

        // Delete unchecked subjects that are no longer assigned
        ClassSubject::where('class_id', $classId)
            ->whereNotIn('subject_id', $subjectIds)
            ->delete();

        // Create checked subjects that don't exist yet
        foreach ($subjectIds as $subjectId) {
            ClassSubject::firstOrCreate([
                'class_id' => $classId,
                'subject_id' => $subjectId,
            ]);
        }

        return redirect()->route('academics.index', ['tab' => 'class-subjects'])
            ->with('success', 'បានកំណត់មុខវិជ្ជាសម្រាប់ថ្នាក់ជោគជ័យ!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSubject $classSubject)
    {
        $classSubject->load([
            'class.grade',
            'class.section',
            'class.academic_year',
            'class.students.profile',
            'class.students.user',
            'class.students.studentStatus',
            'subject',
            'teacher_assignments.employee.user',
            'teacher_assignments.employee.employee_department',
            'teacher_assignments.employee.employee_position',
            'teacher_assignments.employee.employee_documents',
            'teacher_assignments.academic_year',
        ]);

        $timetableEntries = TimetableEntry::with(['time_slot', 'classroom', 'teacher.user'])
            ->where('subject_id', $classSubject->subject_id)
            ->whereHas('timetable', function ($q) use ($classSubject) {
                $q->where('class_id', $classSubject->class_id);
            })
            ->orderBy('day_of_week')
            ->get();

        return view('academics.class_subjects.show', compact('classSubject', 'timetableEntries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSubject $classSubject)
    {
        $classes = SchoolClass::with(['grade', 'section', 'academic_year'])->get();
        $subjects = Subject::orderBy('name')->get();

        return view('academics.class_subjects.edit', compact('classSubject', 'classes', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSubject $classSubject)
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
                Rule::unique('class_subjects', 'subject_id')
                    ->where('class_id', $request->class_id)
                    ->ignore($classSubject->id),
            ],
        ], [
            'subject_id.unique' => 'មុខវិជ្ជានេះត្រូវបានកំណត់ក្នុងថ្នាក់ដែលបានជ្រើសរើសរួចហើយ។',
        ]);

        $classSubject->update($validated);

        return redirect()->route('academics.index', ['tab' => 'class-subjects'])
            ->with('success', 'មុខវិជ្ជាក្នុងថ្នាក់ត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSubject $classSubject)
    {
        $classSubject->delete();

        return redirect()->route('academics.index', ['tab' => 'class-subjects'])
            ->with('success', 'មុខវិជ្ជាត្រូវបានដកចេញពីថ្នាក់ដោយជោគជ័យ។');
    }
}
