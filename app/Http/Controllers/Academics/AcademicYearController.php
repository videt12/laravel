<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     * Redirects to the unified Academics page on the academic-years tab.
     */
    public function index(Request $request)
    {
        return redirect()->route('academics.index', ['tab' => 'academic-years'] + $request->query());
    }

    /**
     * Store a newly created academic year in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_years', 'name')->whereNull('deleted_at'),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'ឆ្នាំសិក្សា «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបង្កើតឆ្នាំសិក្សាដែលដូចគ្នា។ (Academic year ":input" already exists!)',
            'name.required' => 'សូមបញ្ចូលឆ្នាំសិក្សា។',
            'start_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទចាប់ផ្តើម។',
            'end_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទបញ្ចប់។',
            'end_date.after_or_equal' => 'កាលបរិច្ឆេទបញ្ចប់ត្រូវតែក្រោយ ឬស្មើកាលបរិច្ឆេទចាប់ផ្តើម។',
        ]);

        $validated['is_active'] = $request->boolean('is_active') ? 1 : 0;

        $academicYear = AcademicYear::create($validated);

        if (! empty($validated['is_active'])) {
            AcademicYear::resetConfiguredSettingCache();
            AcademicYear::synchronizeActiveYear($academicYear->name);
        }

        return redirect()->route('academics.index', ['tab' => 'academic-years'])
            ->with('success', "ឆ្នាំសិក្សា «{$validated['name']}» ត្រូវបានបង្កើតដោយជោគជ័យ។");
    }

    /**
     * Update the specified academic year in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_years', 'name')->whereNull('deleted_at')->ignore($academicYear->id),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.unique' => 'ឆ្នាំសិក្សា «:input» មានរួចហើយក្នុងប្រព័ន្ធ! សូមកុំបញ្ចូលឈ្មោះឆ្នាំសិក្សាដែលដូចគ្នា។ (Academic year ":input" already exists!)',
            'name.required' => 'សូមបញ្ចូលឆ្នាំសិក្សា។',
            'start_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទចាប់ផ្តើម។',
            'end_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទបញ្ចប់។',
            'end_date.after_or_equal' => 'កាលបរិច្ឆេទបញ្ចប់ត្រូវតែក្រោយ ឬស្មើកាលបរិច្ឆេទចាប់ផ្តើម។',
        ]);

        $validated['is_active'] = $request->boolean('is_active') ? 1 : 0;

        $academicYear->update($validated);

        if (! empty($validated['is_active'])) {
            AcademicYear::resetConfiguredSettingCache();
            AcademicYear::synchronizeActiveYear($academicYear->name);
        }

        return redirect()->route('academics.index', ['tab' => 'academic-years'])
            ->with('success', "ឆ្នាំសិក្សា «{$academicYear->name}» ត្រូវបានកែប្រែដោយជោគជ័យ។");
    }

    /**
     * Toggle the active status of the specified academic year.
     */
    public function toggleActive(AcademicYear $academicYear)
    {
        $newStatus = ! (bool) $academicYear->is_active;

        if ($newStatus) {
            AcademicYear::resetConfiguredSettingCache();
            AcademicYear::synchronizeActiveYear($academicYear->name);
        } else {
            $academicYear->update(['is_active' => 0]);
            SchoolSetting::where('setting_key', 'academic_year')->update(['setting_value' => '']);
            AcademicYear::resetConfiguredSettingCache();
        }

        $statusLabel = $newStatus ? 'បើកដំណើរការ' : 'បិទដំណើរការ';

        return redirect()->route('academics.index', ['tab' => 'academic-years'])
            ->with('success', "ឆ្នាំសិក្សា «{$academicYear->name}» ត្រូវបាន{$statusLabel}ដោយជោគជ័យ។");
    }

    /**
     * Remove the specified academic year from storage with safe deletion check.
     */
    public function destroy(AcademicYear $academicYear)
    {
        // Safe deletion checks: prevent deleting if linked to classes, students, timetables, etc.
        $reasons = [];

        if ($academicYear->classes()->exists()) {
            $reasons[] = 'ថ្នាក់រៀន (Classes)';
        }
        if ($academicYear->teacher_assignments()->exists()) {
            $reasons[] = 'ការចាត់តាំងគ្រូបង្រៀន (Teacher Assignments)';
        }
        if ($academicYear->timetables()->exists()) {
            $reasons[] = 'កាលវិភាគ (Timetables)';
        }
        if ($academicYear->students()->exists()) {
            $reasons[] = 'សិស្ស (Students)';
        }
        if ($academicYear->enrollments()->exists()) {
            $reasons[] = 'ការចុះឈ្មោះចូលរៀន (Enrollments)';
        }
        if ($academicYear->fee_structures()->exists()) {
            $reasons[] = 'រចនាសម្ព័ន្ធថ្លៃសិក្សា (Fee Structures)';
        }
        if ($academicYear->school_terms()->exists()) {
            $reasons[] = 'ឆមាស/ត្រីមាសសិក្សា (School Terms)';
        }

        if (! empty($reasons)) {
            $linked = implode('、', $reasons);

            return redirect()->route('academics.index', ['tab' => 'academic-years'])
                ->with('error', "មិនអាចលុបឆ្នាំសិក្សា «{$academicYear->name}» បានទេ ដោយសារមានទិន្នន័យផ្សារភ្ជាប់ដូចជា៖ {$linked}។ សូមផ្ទេរ ឬលុបទិន្នន័យទាំងនោះចេញជាមុនសិន!");
        }

        $name = $academicYear->name;
        $academicYear->delete();

        return redirect()->route('academics.index', ['tab' => 'academic-years'])
            ->with('success', "ឆ្នាំសិក្សា «{$name}» ត្រូវបានលុបដោយជោគជ័យ។");
    }
}
