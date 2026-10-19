<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Store a newly created grade.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:grades,name'],
            'level' => ['nullable', 'integer', 'min:0', 'max:100', 'unique:grades,level'],
        ], [
            'name.unique' => 'កម្រិតថ្នាក់ដែលមានឈ្មោះនេះមានរួចហើយ។',
            'level.unique' => 'កម្រិតថ្នាក់ដែលមានលេខលំដាប់នេះមានរួចហើយ។',
        ]);

        if (! isset($validated['level']) || $validated['level'] === null || $validated['level'] === '') {
            $validated['level'] = ((int) Grade::max('level')) + 1;
        }

        Grade::create($validated);

        return back()->with('success', 'កម្រិតថ្នាក់ត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * Update the specified grade.
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:grades,name,'.$grade->id],
            'level' => ['nullable', 'integer', 'min:0', 'max:100', 'unique:grades,level,'.$grade->id],
        ], [
            'name.unique' => 'កម្រិតថ្នាក់ដែលមានឈ្មោះនេះមានរួចហើយ។',
            'level.unique' => 'កម្រិតថ្នាក់ដែលមានលេខលំដាប់នេះមានរួចហើយ។',
        ]);

        if (! isset($validated['level']) || $validated['level'] === null || $validated['level'] === '') {
            $validated['level'] = $grade->level ?? (((int) Grade::max('level')) + 1);
        }

        $grade->update($validated);

        return back()->with('success', 'កម្រិតថ្នាក់ត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * Remove the specified grade.
     */
    public function destroy(Grade $grade)
    {
        if ($grade->classes()->exists()) {
            return back()->with('error', "មិនអាចលុបកម្រិតថ្នាក់ «{$grade->name}» បានទេ ដោយសារមានថ្នាក់រៀនកំពុងផ្សារភ្ជាប់ជាមួយ!");
        }

        $name = $grade->name;
        $grade->delete();

        return back()->with('success', "កម្រិតថ្នាក់ «{$name}» ត្រូវបានលុបដោយជោគជ័យ។");
    }
}
