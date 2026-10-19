<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return redirect()->route('academics.index', ['tab' => 'subjects'] + $request->query());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextCode = Subject::generateNextCode();
        $classes = SchoolClass::with(['grade', 'section', 'academic_year'])->get();

        return view('academics.subjects.create', compact('nextCode', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $request->filled('code')) {
            $request->merge(['code' => Subject::generateNextCode()]);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'name')->whereNull('deleted_at'),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects', 'code')->whereNull('deleted_at'),
            ],
            'type' => 'nullable|string|max:50',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'integer|exists:school_classes,id',
        ], [
            'name.unique' => 'ឈ្មោះមុខវិជ្ជា «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'code.unique' => 'កូដមុខវិជ្ជា «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះមុខវិជ្ជា។',
            'code.required' => 'សូមបញ្ចូលកូដមុខវិជ្ជា។',
        ]);

        $subject = Subject::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'type' => $validated['type'] ?? null,
        ]);

        if ($request->has('class_ids')) {
            $subject->classes()->sync($request->input('class_ids', []));
        }

        return redirect()->route('academics.index', ['tab' => 'subjects'])
            ->with('success', "មុខវិជ្ជា «{$validated['name']}» ត្រូវបានបង្កើតដោយជោគជ័យ។");
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load(['class_subjects.class.grade', 'class_subjects.class.section', 'class_subjects.class.academic_year']);

        return view('academics.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $classes = SchoolClass::with(['grade', 'section', 'academic_year'])->get();
        $assignedClassIds = $subject->classes()->pluck('school_classes.id')->toArray();

        return view('academics.subjects.edit', compact('subject', 'classes', 'assignedClassIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects', 'name')->whereNull('deleted_at')->ignore($subject->id),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects', 'code')->whereNull('deleted_at')->ignore($subject->id),
            ],
            'type' => 'nullable|string|max:50',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'integer|exists:school_classes,id',
        ], [
            'name.unique' => 'ឈ្មោះមុខវិជ្ជា «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'code.unique' => 'កូដមុខវិជ្ជា «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះមុខវិជ្ជា។',
            'code.required' => 'សូមបញ្ចូលកូដមុខវិជ្ជា។',
        ]);

        $subject->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'type' => $validated['type'] ?? null,
        ]);

        if ($request->has('class_ids')) {
            $subject->classes()->sync($request->input('class_ids', []));
        }

        return redirect()->route('academics.index', ['tab' => 'subjects'])
            ->with('success', "មុខវិជ្ជា «{$subject->name}» ត្រូវបានកែប្រែដោយជោគជ័យ។");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $name = $subject->name;
        $subject->delete();

        return redirect()->route('academics.index', ['tab' => 'subjects'])
            ->with('success', "មុខវិជ្ជា «{$name}» ត្រូវបានលុបដោយជោគជ័យ។");
    }
}
