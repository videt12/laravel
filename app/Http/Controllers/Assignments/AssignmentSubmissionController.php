<?php

namespace App\Http\Controllers\Assignments;

use App\Http\Controllers\Controller;
use App\Models\Assignments\Assignment;
use App\Models\Assignments\AssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentSubmissionController extends Controller
{
    public function index(Request $request, $assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $submissions = AssignmentSubmission::with(['student', 'assignmentGrade'])
            ->where('assignment_id', $assignmentId)
            ->paginate(15);

        return view('assignments.submissions.index', compact('assignment', 'submissions'));
    }

    public function show(string $id)
    {
        $submission = AssignmentSubmission::with(['student', 'assignment', 'assignmentGrade'])->findOrFail($id);

        return view('assignments.submissions.show', compact('submission'));
    }
}
