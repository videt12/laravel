<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $typeFilter = $request->get('type', 'all'); // 'all', 'department', 'position'
        $statusFilter = $request->get('status_filter'); // 'active', 'inactive', or null (all)
        $viewMode = $request->get('view', 'unified');

        $deptQuery = EmployeeDepartment::withCount('employees');
        $posQuery = EmployeePosition::withCount('employees');

        if (! empty($search)) {
            $deptQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
            $posQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($statusFilter)) {
            $deptQuery->where('status', $statusFilter);
            $posQuery->where('status', $statusFilter);
        }

        $allDepts = (clone $deptQuery)->orderBy('name')->get()->map(function ($item) {
            $item->setAttribute('entity_type', 'department');

            return $item;
        });

        $allPositions = (clone $posQuery)->orderBy('name')->get()->map(function ($item) {
            $item->setAttribute('entity_type', 'position');

            return $item;
        });

        // Pair departments and positions row-by-row for the single unified table
        $maxRows = max($allDepts->count(), $allPositions->count());
        $paired = collect();
        for ($i = 0; $i < $maxRows; $i++) {
            $paired->push([
                'department' => $allDepts->get($i),
                'position' => $allPositions->get($i),
            ]);
        }

        $page = (int) $request->get('page', 1);
        $perPage = 15;
        $pairedRows = new LengthAwarePaginator(
            $paired->forPage($page, $perPage)->values(),
            $paired->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $departments = $deptQuery->orderBy('name')->paginate(15, ['*'], 'dept_page')->withQueryString();
        $departments->getCollection()->transform(function ($item) {
            $item->setAttribute('entity_type', 'department');

            return $item;
        });

        $positions = $posQuery->orderBy('name')->paginate(15, ['*'], 'pos_page')->withQueryString();
        $positions->getCollection()->transform(function ($item) {
            $item->setAttribute('entity_type', 'position');

            return $item;
        });

        // Summary metrics
        $totalDepartments = EmployeeDepartment::count();
        $activeDepartments = EmployeeDepartment::where('status', 'active')->count();
        $totalPositions = EmployeePosition::count();
        $activePositions = EmployeePosition::where('status', 'active')->count();
        $totalEmployees = Employee::count();
        $totalAll = $totalDepartments + $totalPositions;
        $nextPositionCode = EmployeePosition::generateNextCode();
        $nextDepartmentCode = EmployeeDepartment::generateNextCode();

        return view('employees.departments.index', compact(
            'pairedRows',
            'typeFilter',
            'viewMode',
            'search',
            'departments',
            'positions',
            'totalAll',
            'totalDepartments',
            'activeDepartments',
            'totalPositions',
            'activePositions',
            'totalEmployees',
            'nextPositionCode',
            'nextDepartmentCode'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextDepartmentCode = EmployeeDepartment::generateNextCode();

        return view('employees.departments.create', compact('nextDepartmentCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $request->filled('code')) {
            $request->merge(['code' => EmployeeDepartment::generateNextCode()]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:employee_departments,name',
            'code' => 'required|string|max:50|unique:employee_departments,code',
            'status' => 'nullable|in:active,inactive',
            'description' => 'nullable|string|max:255',
        ]);

        if (! isset($validated['status'])) {
            $validated['status'] = 'active';
        }

        EmployeeDepartment::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'ផ្នែកត្រូវបានបង្កើតដោយជោគជ័យ (Department created successfully).');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeDepartment $department)
    {
        $department->load('employees.user', 'employees.employee_position', 'employees.employee_status');
        $positions = EmployeePosition::withCount('employees')->orderBy('name')->get();

        return view('employees.departments.show', compact('department', 'positions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeDepartment $department)
    {
        return view('employees.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeDepartment $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:employee_departments,name,'.$department->id,
            'code' => 'nullable|string|max:50|unique:employee_departments,code,'.$department->id,
            'status' => 'nullable|in:active,inactive',
            'description' => 'nullable|string|max:255',
        ]);

        if (! isset($validated['status'])) {
            $validated['status'] = 'active';
        }

        $department->update($validated);

        $params = $request->filled('redirect_tab') ? ['tab' => $request->input('redirect_tab')] : [];

        return redirect()->route('departments.index', $params)
            ->with('success', 'ដេប៉ាតឺម៉ង់ត្រូវបានកែប្រែដោយជោគជ័យ (Department updated successfully).');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, EmployeeDepartment $department)
    {
        $params = $request->filled('redirect_tab') ? ['tab' => $request->input('redirect_tab')] : [];

        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index', $params)
                ->with('error', 'មិនអាចលុបដេប៉ាតឺម៉ង់នេះបានទេ ដោយសារមានបុគ្គលិកកំពុងប្រើប្រាស់។');
        }

        $department->delete();

        return redirect()->route('departments.index', $params)
            ->with('success', 'ដេប៉ាតឺម៉ង់ត្រូវបានលុបដោយជោគជ័យ (Department deleted successfully).');
    }
}
