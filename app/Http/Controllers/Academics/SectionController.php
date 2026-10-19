<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Store a newly created section.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:sections,name'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
        ], [
            'name.unique' => 'បន្ទប់/ផ្នែកដែលមានឈ្មោះនេះមានរួចហើយ។',
        ]);

        Section::create($validated);

        return back()->with('success', 'បន្ទប់/ផ្នែកត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * Update the specified section.
     */
    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:sections,name,'.$section->id],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:500'],
        ], [
            'name.unique' => 'បន្ទប់/ផ្នែកដែលមានឈ្មោះនេះមានរួចហើយ។',
        ]);

        $section->update($validated);

        return back()->with('success', 'បន្ទប់/ផ្នែកត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * Remove the specified section.
     */
    public function destroy(Section $section)
    {
        if ($section->classes()->exists()) {
            return back()->with('error', "មិនអាចលុបបន្ទប់/ផ្នែក «{$section->name}» បានទេ ដោយសារមានថ្នាក់រៀនកំពុងផ្សារភ្ជាប់ជាមួយ!");
        }

        $name = $section->name;
        $section->delete();

        return back()->with('success', "បន្ទប់/ផ្នែក «{$name}» ត្រូវបានលុបដោយជោគជ័យ។");
    }
}
