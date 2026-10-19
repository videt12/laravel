<?php

namespace App\Http\Controllers\Assignments;

use App\Http\Controllers\Controller;
use App\Models\Assignments\AssignmentGrade;
use App\Models\Assignments\AssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentGradeController extends Controller
{
    public function create(Request $request, $submissionId)
    {
        $submission = AssignmentSubmission::with(['student', 'assignment'])->findOrFail($submissionId);

        return view('assignments.grades.create', compact('submission'));
    }

    public function store(Request $request, $submissionId)
    {
        $submission = AssignmentSubmission::findOrFail($submissionId);

        $validated = $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:'.$submission->assignment->total_marks,
            'grade' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
        ]);

        $validated['assignment_submission_id'] = $submission->id;
        $validated['graded_by'] = auth()->id() ?? 1; // Fallback to 1 if not logged in for testing

        AssignmentGrade::create($validated);

        return redirect()->route('submissions.show', $submission->id)->with('success', 'Grade saved successfully.');
    }

    public function edit(string $id)
    {
        $grade = AssignmentGrade::with('assignment_submission.student', 'assignment_submission.assignment')->findOrFail($id);
        $submission = $grade->assignment_submission;

        return view('assignments.grades.edit', compact('grade', 'submission'));
    }

    public function update(Request $request, string $id)
    {
        $grade = AssignmentGrade::findOrFail($id);
        $submission = $grade->assignment_submission;

        $validated = $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:'.$submission->assignment->total_marks,
            'grade' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
        ]);

        $grade->update($validated);

        return redirect()->route('submissions.show', $submission->id)->with('success', 'Grade updated successfully.');
    }
}
