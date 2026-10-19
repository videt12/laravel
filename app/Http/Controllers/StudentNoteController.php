<?php

namespace App\Http\Controllers;

use App\Models\Students\Student;
use App\Models\Students\StudentNote;
use Illuminate\Http\Request;

class StudentNoteController extends Controller
{
    /**
     * Display a paginated list of student notes with optional search + filters.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $priority = $request->input('priority');
        $status = $request->input('status');

        $notes = StudentNote::with(['student.user', 'student.studentProfile'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('note', 'like', "%{$search}%")
                        ->orWhereHas('student.user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('student', fn ($s) => $s->where('admission_number', 'like', "%{$search}%"));
                });
            })
            ->when($type, fn ($q, $v) => $q->where('note_type', $v))
            ->when($priority, fn ($q, $v) => $q->where('priority', $v))
            ->when($status, fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('students.notes.index', compact('notes', 'search', 'type', 'priority', 'status'));
    }

    /**
     * Show the form for creating a new student note.
     */
    public function create()
    {
        $students = Student::with('user')->orderBy('id')->get();

        return view('students.notes.create', compact('students'));
    }

    /**
     * Store a newly created student note.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'note' => 'required|string|max:65535',
            'note_type' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::NOTE_TYPES)),
            'note_date' => 'nullable|date',
            'visibility' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::VISIBILITY_OPTIONS)),
            'tags' => 'nullable|string|max:500',
            'is_important' => 'nullable|boolean',
            'follow_up_required' => 'nullable|boolean',
            'priority' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::PRIORITIES)),
            'status' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::STATUSES)),
        ]);

        $validated['is_important'] = $request->boolean('is_important');
        $validated['follow_up_required'] = $request->boolean('follow_up_required');
        $validated['visibility'] = $validated['visibility'] ?? 'private';
        $validated['priority'] = $validated['priority'] ?? 'normal';
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['created_by'] = auth()->id();

        $note = StudentNote::create($validated);

        return redirect()->route('student-notes.show', $note)
            ->with('success', 'Student note created successfully.');
    }

    /**
     * Display the specified student note.
     */
    public function show(StudentNote $studentNote)
    {
        $studentNote->load(['student.user', 'student.studentProfile', 'author']);

        return view('students.notes.show', compact('studentNote'));
    }

    /**
     * Show the form for editing the specified student note.
     */
    public function edit(StudentNote $studentNote)
    {
        $studentNote->load(['student.user']);
        $students = Student::with('user')->orderBy('id')->get();

        return view('students.notes.edit', compact('studentNote', 'students'));
    }

    /**
     * Update the specified student note.
     */
    public function update(Request $request, StudentNote $studentNote)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'note' => 'required|string|max:65535',
            'note_type' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::NOTE_TYPES)),
            'note_date' => 'nullable|date',
            'visibility' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::VISIBILITY_OPTIONS)),
            'tags' => 'nullable|string|max:500',
            'is_important' => 'nullable|boolean',
            'follow_up_required' => 'nullable|boolean',
            'priority' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::PRIORITIES)),
            'status' => 'nullable|string|in:'.implode(',', array_keys(StudentNote::STATUSES)),
        ]);

        $validated['is_important'] = $request->boolean('is_important');
        $validated['follow_up_required'] = $request->boolean('follow_up_required');
        $validated['visibility'] = $validated['visibility'] ?? 'private';
        $validated['priority'] = $validated['priority'] ?? 'normal';
        $validated['status'] = $validated['status'] ?? 'active';

        $studentNote->update($validated);

        return redirect()->route('student-notes.show', $studentNote)
            ->with('success', 'Student note updated successfully.');
    }

    /**
     * Remove the specified student note.
     */
    public function destroy(StudentNote $studentNote)
    {
        $studentNote->delete();

        return redirect()->route('student-notes.index')
            ->with('success', 'Student note deleted successfully.');
    }
}
