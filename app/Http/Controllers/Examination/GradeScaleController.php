<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Examination\GradeScale;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GradeScaleController extends Controller
{
    /**
     * Authorize settings management.
     */
    protected function authorizeSettings(): void
    {
        $user = auth()->user();
        if (! $user || (! $user->isSuperAdmin() && ! $user->hasRole('admin') && ! $user->canAccess('settings'))) {
            abort(403, 'អ្នកមិនមានសិទ្ធិកែប្រែកម្រិតនិទ្ទេសទេ។');
        }
    }

    /**
     * Display a listing of grade scales (redirects to unified examinations management).
     */
    public function index()
    {
        $this->authorizeSettings();

        return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales']);
    }

    /**
     * Show the form for creating a new grade scale.
     */
    public function create()
    {
        $this->authorizeSettings();

        return view('examination.grade_scales.create');
    }

    /**
     * Store a newly created grade scale in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeSettings();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:grade_scales,name'],
            'min_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_percentage' => ['required', 'numeric', 'min:0', 'max:100', 'gte:min_percentage'],
            'grade' => ['required', 'string', 'max:10', 'unique:grade_scales,grade'],
            'gpa_point' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ], [
            'name.unique' => 'ឈ្មោះកម្រិតនិទ្ទេស «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'grade.unique' => 'និទ្ទេស «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'max_percentage.gte' => 'ភាគរយអតិបរមាត្រូវតែធំជាង ឬស្មើភាគរយអប្បបរមា។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះកម្រិតនិទ្ទេស។',
            'min_percentage.required' => 'សូមបញ្ចូលភាគរយអប្បបរមា។',
            'max_percentage.required' => 'សូមបញ្ចូលភាគរយអតិបរមា។',
            'grade.required' => 'សូមបញ្ចូលនិទ្ទេស។',
        ]);

        $min = (float) $validated['min_percentage'];
        $max = (float) $validated['max_percentage'];

        // Prevent overlapping percentage ranges
        $overlap = GradeScale::where(function ($q) use ($min, $max) {
            $q->whereBetween('min_percentage', [$min + 0.001, $max - 0.001])
                ->orWhereBetween('max_percentage', [$min + 0.001, $max - 0.001])
                ->orWhere(function ($sub) use ($min, $max) {
                    $sub->where('min_percentage', '<=', $min)
                        ->where('max_percentage', '>=', $max);
                });
        })->exists();

        if ($overlap) {
            return back()->withErrors([
                'min_percentage' => 'ចន្លោះភាគរយនេះជាន់គ្នាជាមួយកម្រិតនិទ្ទេសដែលមានស្រាប់។',
            ])->withInput();
        }

        GradeScale::create($validated);

        return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales'])
            ->with('success', 'បានបង្កើតកម្រិតនិទ្ទេសដោយជោគជ័យ។');
    }

    /**
     * Show the form for editing the specified grade scale.
     */
    public function edit(GradeScale $gradeScale)
    {
        $this->authorizeSettings();

        return view('examination.grade_scales.edit', compact('gradeScale'));
    }

    /**
     * Update the specified grade scale in storage.
     */
    public function update(Request $request, GradeScale $gradeScale)
    {
        $this->authorizeSettings();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('grade_scales', 'name')->ignore($gradeScale->id)],
            'min_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_percentage' => ['required', 'numeric', 'min:0', 'max:100', 'gte:min_percentage'],
            'grade' => ['required', 'string', 'max:10', Rule::unique('grade_scales', 'grade')->ignore($gradeScale->id)],
            'gpa_point' => ['nullable', 'numeric', 'min:0', 'max:10'],
        ], [
            'name.unique' => 'ឈ្មោះកម្រិតនិទ្ទេស «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'grade.unique' => 'និទ្ទេស «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតស្ទួន។',
            'max_percentage.gte' => 'ភាគរយអតិបរមាត្រូវតែធំជាង ឬស្មើភាគរយអប្បបរមា។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះកម្រិតនិទ្ទេស។',
            'min_percentage.required' => 'សូមបញ្ចូលភាគរយអប្បបរមា។',
            'max_percentage.required' => 'សូមបញ្ចូលភាគរយអតិបរមា។',
            'grade.required' => 'សូមបញ្ចូលនិទ្ទេស។',
        ]);

        $min = (float) $validated['min_percentage'];
        $max = (float) $validated['max_percentage'];

        $overlap = GradeScale::where('id', '!=', $gradeScale->id)
            ->where(function ($q) use ($min, $max) {
                $q->whereBetween('min_percentage', [$min + 0.001, $max - 0.001])
                    ->orWhereBetween('max_percentage', [$min + 0.001, $max - 0.001])
                    ->orWhere(function ($sub) use ($min, $max) {
                        $sub->where('min_percentage', '<=', $min)
                            ->where('max_percentage', '>=', $max);
                    });
            })->exists();

        if ($overlap) {
            return back()->withErrors([
                'min_percentage' => 'ចន្លោះភាគរយនេះជាន់គ្នាជាមួយកម្រិតនិទ្ទេសដែលមានស្រាប់។',
            ])->withInput();
        }

        $gradeScale->update($validated);

        return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales'])
            ->with('success', 'បានកែប្រែកម្រិតនិទ្ទេសដោយជោគជ័យ។');
    }

    /**
     * Remove the specified grade scale from storage.
     */
    public function destroy(GradeScale $gradeScale)
    {
        $this->authorizeSettings();

        $gradeScale->delete();

        return redirect()->route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales'])
            ->with('success', 'បានលុបកម្រិតនិទ្ទេសដោយជោគជ័យ។');
    }
}
