<?php

namespace App\Http\Controllers\Assignments;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Academics\Subject;
use App\Models\Assignments\Assignment;
use App\Models\Employees\Employee;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::with(['class', 'subject']);

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }
        if ($request->has('subject_id') && $request->subject_id != '') {
            $query->where('subject_id', $request->subject_id);
        }

        $assignments = $query->orderBy('due_date', 'desc')->paginate(15);
        $classes = SchoolClass::all();
        $subjects = Subject::all();

        return view('assignments.index', compact('assignments', 'classes', 'subjects'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $teachers = Employee::all();

        return view('assignments.create', compact('classes', 'sections', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:employees,id',
            'due_date' => 'required|date',
            'total_marks' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Assignment::create($validated);

        return redirect()->route('assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function show(string $id)
    {
        $assignment = Assignment::with(['class.grade', 'section', 'subject', 'teacher', 'assignment_attachments', 'assignment_submissions'])->findOrFail($id);

        $totalStudents = $assignment->class ? $assignment->class->students()->count() : 0;
        $submittedCount = $assignment->assignment_submissions()->where('status', 'Submitted')->count();
        $pendingCount = max(0, $totalStudents - $submittedCount);

        return view('assignments.show', compact('assignment', 'totalStudents', 'submittedCount', 'pendingCount'));
    }

    public function edit(string $id)
    {
        $assignment = Assignment::findOrFail($id);
        $classes = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $teachers = Employee::all();

        return view('assignments.edit', compact('assignment', 'classes', 'sections', 'subjects', 'teachers'));
    }

    public function update(Request $request, string $id)
    {
        $assignment = Assignment::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:employees,id',
            'due_date' => 'required|date',
            'total_marks' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $assignment->update($validated);

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(string $id)
    {
        $assignment = Assignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully.');
    }
}
