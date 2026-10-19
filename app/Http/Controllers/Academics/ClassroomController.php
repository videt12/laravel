<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\Classroom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     * Redirects to the unified Academics page on the classrooms tab.
     */
    public function index(Request $request)
    {
        return redirect()->route('academics.index', ['tab' => 'classrooms'] + $request->query());
    }

    /**
     * Store a newly created classroom in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classrooms', 'name')->where(function ($query) use ($request) {
                    return $request->filled('building')
                        ? $query->where('building', trim($request->building))
                        : $query->where(function ($q) {
                            $q->whereNull('building')->orWhere('building', '');
                        });
                }),
            ],
            'building' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ], [
            'name.unique' => 'បន្ទប់រៀន «:input» មានរួចហើយ'.($request->filled('building') ? " ក្នុងអគារ «{$request->building}»" : '').'! សូមកុំបង្កើតបន្ទប់រៀនដែលដូចគ្នា។ (Classroom already exists!)',
            'name.required' => 'សូមបញ្ចូលឈ្មោះ ឬលេខបន្ទប់។',
            'capacity.min' => 'ចំណុះសិស្សយ៉ាងតិច ១ កៅអី។',
            'capacity.max' => 'ចំណុះសិស្សមិនអាចលើសពី ១០០០ កៅអីឡើយ។',
        ]);

        $classroom = Classroom::create($validated);

        return redirect()->route('academics.index', ['tab' => 'classrooms'])
            ->with('success', "បន្ទប់រៀន «{$classroom->name}» ត្រូវបានបង្កើតដោយជោគជ័យ។");
    }

    /**
     * Update the specified classroom in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classrooms', 'name')->where(function ($query) use ($request) {
                    return $request->filled('building')
                        ? $query->where('building', trim($request->building))
                        : $query->where(function ($q) {
                            $q->whereNull('building')->orWhere('building', '');
                        });
                })->ignore($classroom->id),
            ],
            'building' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ], [
            'name.unique' => 'បន្ទប់រៀន «:input» មានរួចហើយ'.($request->filled('building') ? " ក្នុងអគារ «{$request->building}»" : '').'! សូមកុំដាក់ឈ្មោះបន្ទប់រៀនដែលដូចគ្នា។ (Classroom already exists!)',
            'name.required' => 'សូមបញ្ចូលឈ្មោះ ឬលេខបន្ទប់។',
            'capacity.min' => 'ចំណុះសិស្សយ៉ាងតិច ១ កៅអី។',
            'capacity.max' => 'ចំណុះសិស្សមិនអាចលើសពី ១០០០ កៅអីឡើយ។',
        ]);

        $classroom->update($validated);

        return redirect()->route('academics.index', ['tab' => 'classrooms'])
            ->with('success', "បន្ទប់រៀន «{$classroom->name}» ត្រូវបានកែប្រែដោយជោគជ័យ។");
    }

    /**
     * Remove the specified classroom from storage with safe deletion.
     */
    public function destroy(Classroom $classroom)
    {
        // Safe deletion check: prevent deletion if assigned to timetable entries
        if ($classroom->timetable_entries()->exists()) {
            return redirect()->route('academics.index', ['tab' => 'classrooms'])
                ->with('error', "មិនអាចលុបបន្ទប់រៀន «{$classroom->name}» បានទេ ដោយសារបន្ទប់នេះត្រូវបានកំណត់ក្នុងកាលវិភាគរួចហើយ!");
        }

        $name = $classroom->name;
        $classroom->delete();

        return redirect()->route('academics.index', ['tab' => 'classrooms'])
            ->with('success', "បន្ទប់រៀន «{$name}» ត្រូវបានលុបដោយជោគជ័យ។");
    }
}
