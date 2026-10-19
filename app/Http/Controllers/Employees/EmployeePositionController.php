<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Employees\EmployeePosition;
use Illuminate\Http\Request;

class EmployeePositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return redirect()->route('departments.index', array_merge(['tab' => 'positions'], $request->all()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextPositionCode = EmployeePosition::generateNextCode();

        return view('employees.positions.create', compact('nextPositionCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $request->filled('code')) {
            $request->merge(['code' => EmployeePosition::generateNextCode()]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:employee_positions,name',
            'code' => 'required|string|max:50|unique:employee_positions,code',
            'status' => 'nullable|in:active,inactive',
            'description' => 'nullable|string|max:255',
        ]);

        if (! isset($validated['status'])) {
            $validated['status'] = 'active';
        }

        EmployeePosition::create($validated);

        $params = $request->filled('redirect_tab') ? ['tab' => $request->input('redirect_tab')] : [];

        return redirect()->route('departments.index', $params)
            ->with('success', 'មុខតំណែងត្រូវបានបង្កើតដោយជោគជ័យ (Position created successfully).');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeePosition $position)
    {
        $position->load('employees.user', 'employees.employee_department', 'employees.employee_status');

        return view('employees.positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeePosition $position)
    {
        return view('employees.positions.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeePosition $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:employee_positions,name,'.$position->id,
            'code' => 'nullable|string|max:50|unique:employee_positions,code,'.$position->id,
            'status' => 'nullable|in:active,inactive',
            'description' => 'nullable|string|max:255',
        ]);

        if (! isset($validated['status'])) {
            $validated['status'] = 'active';
        }

        $position->update($validated);

        $params = $request->filled('redirect_tab') ? ['tab' => $request->input('redirect_tab')] : [];

        return redirect()->route('departments.index', $params)
            ->with('success', 'មុខតំណែងត្រូវបានកែប្រែដោយជោគជ័យ (Position updated successfully).');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, EmployeePosition $position)
    {
        $params = $request->filled('redirect_tab') ? ['tab' => $request->input('redirect_tab')] : [];

        if ($position->employees()->count() > 0) {
            return redirect()->route('departments.index', $params)
                ->with('error', 'មិនអាចលុបមុខតំណែងនេះបានទេ ដោយសារមានបុគ្គលិកកំពុងប្រើប្រាស់។');
        }

        $position->delete();

        return redirect()->route('departments.index', $params)
            ->with('success', 'មុខតំណែងត្រូវបានលុបដោយជោគជ័យ (Position deleted successfully).');
    }
}
