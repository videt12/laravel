<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Examination\ExamType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamTypeController extends Controller
{
    /**
     * Authorize settings management.
     */
    protected function authorizeSettings(): void
    {
        $user = auth()->user();
        if (! $user || (! $user->isSuperAdmin() && ! $user->hasRole('admin') && ! $user->canAccess('settings'))) {
            abort(403, 'អ្នកមិនមានសិទ្ធិកែប្រែប្រភេទប្រឡងទេ។');
        }
    }

    /**
     * Display a listing of the exam types (redirects to unified examinations management).
     */
    public function index(Request $request)
    {
        $this->authorizeSettings();

        return redirect()->route('examinations.index', array_merge(['tab' => 'settings', 'subtab' => 'exam-types'], $request->query()));
    }

    /**
     * Show the form for creating a new exam type.
     */
    public function create()
    {
        $this->authorizeSettings();

        return view('examination.exam_types.create');
    }

    /**
     * Store a newly created exam type in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeSettings();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:exam_types,name'],
            'description' => ['nullable', 'string'],
        ], [
            'name.unique' => 'ឈ្មោះប្រភេទការប្រឡង «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះប្រភេទការប្រឡង។',
        ]);

        ExamType::create($validated);

        return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types'])
            ->with('success', 'ប្រភេទការប្រឡងត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * Display the specified exam type.
     */
    public function show(ExamType $examType)
    {
        $examType->load(['exams.academic_year', 'exams.school_term']);

        return view('examination.exam_types.show', compact('examType'));
    }

    /**
     * Show the form for editing the specified exam type.
     */
    public function edit(ExamType $examType)
    {
        $this->authorizeSettings();

        return view('examination.exam_types.edit', compact('examType'));
    }

    /**
     * Update the specified exam type in storage.
     */
    public function update(Request $request, ExamType $examType)
    {
        $this->authorizeSettings();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('exam_types', 'name')->ignore($examType->id)],
            'description' => ['nullable', 'string'],
        ], [
            'name.unique' => 'ឈ្មោះប្រភេទការប្រឡង «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះប្រភេទការប្រឡង។',
        ]);

        $examType->update($validated);

        return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types'])
            ->with('success', 'Exam type updated successfully.');
    }

    /**
     * Remove the specified exam type from storage.
     */
    public function destroy(ExamType $examType)
    {
        $this->authorizeSettings();

        if ($examType->exams()->exists()) {
            return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types'])
                ->with('error', 'Cannot delete exam type that has linked exams.');
        }

        $examType->delete();

        return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types'])
            ->with('success', 'Exam type deleted successfully.');
    }
}
