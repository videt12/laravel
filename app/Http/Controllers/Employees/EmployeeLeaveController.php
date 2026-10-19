<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeLeave;
use App\Models\Employees\LeaveType;
use App\Services\Telegram\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmployeeLeave::with(['employee.user', 'leave_type', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $leaves = $query->orderBy('start_date', 'desc')->paginate(15)->withQueryString();

        return view('employees.leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::with('user')->get()->sortBy(function ($emp) {
            return $emp->user ? $emp->user->name : '';
        });

        $leaveTypes = LeaveType::orderBy('name')->get();

        return view('employees.leaves.create', compact('employees', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        // Prevent duplicate or overlapping leave for the same employee
        $overlapping = EmployeeLeave::where('employee_id', $validated['employee_id'])
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($sub) use ($validated) {
                        $sub->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return back()->withInput()->withErrors([
                'start_date' => 'បុគ្គលិកនេះមានពាក្យស្នើសុំច្បាប់ដែលត្រួតស៊ីគ្នា ឬស្ទួនក្នុងកាលបរិច្ឆេទនេះរួចហើយ។',
            ]);
        }

        if ($validated['status'] === 'Approved') {
            $validated['approved_by'] = Auth::id() ?? 1; // Assuming 1 as fallback if not logged in properly during testing
        }

        $leave = EmployeeLeave::create($validated);

        try {
            $telegramRes = app(TelegramNotificationService::class)->sendStaffLeaveAlert($leave, 'created');
            if (! ($telegramRes['success'] ?? false)) {
                Log::warning('Telegram staff leave created notification: '.($telegramRes['message'] ?? 'Failed to send'));
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram staff leave created notification error: '.$e->getMessage());
        }

        return redirect()->route('staff-leaves.index')
            ->with('success', 'បានកត់ត្រាច្បាប់ឈប់សម្រាកដោយជោគជ័យ។');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeLeave $staff_leaf)
    {
        $staff_leaf->load('employee.user', 'leave_type', 'approvedBy');

        return view('employees.leaves.show', compact('staff_leaf'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeLeave $staff_leaf)
    {
        $employees = Employee::with('user')->get();

        if ($staff_leaf->employee_id && ! $employees->contains('id', $staff_leaf->employee_id)) {
            $currentEmployee = Employee::withTrashed()->with('user')->find($staff_leaf->employee_id);
            if ($currentEmployee) {
                $employees->push($currentEmployee);
            }
        }

        $employees = $employees->sortBy(function ($emp) {
            return $emp->user ? $emp->user->name : '';
        });

        $leaveTypes = LeaveType::orderBy('name')->get();

        return view('employees.leaves.edit', compact('staff_leaf', 'employees', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeLeave $staff_leaf)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        // Prevent duplicate or overlapping leave for the same employee
        $overlapping = EmployeeLeave::where('employee_id', $validated['employee_id'])
            ->where('id', '!=', $staff_leaf->id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($sub) use ($validated) {
                        $sub->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return back()->withInput()->withErrors([
                'start_date' => 'បុគ្គលិកនេះមានពាក្យស្នើសុំច្បាប់ដែលត្រួតស៊ីគ្នា ឬស្ទួនក្នុងកាលបរិច្ឆេទនេះរួចហើយ។',
            ]);
        }

        if ($validated['status'] === 'Approved' && $staff_leaf->status !== 'Approved') {
            $validated['approved_by'] = Auth::id() ?? 1;
        } elseif ($validated['status'] !== 'Approved') {
            $validated['approved_by'] = null;
        }

        $oldStatus = $staff_leaf->status;
        $staff_leaf->update($validated);

        $action = match (true) {
            $validated['status'] === 'Approved' && $oldStatus !== 'Approved' => 'approved',
            $validated['status'] === 'Rejected' && $oldStatus !== 'Rejected' => 'rejected',
            default => 'updated',
        };

        try {
            $telegramRes = app(TelegramNotificationService::class)->sendStaffLeaveAlert($staff_leaf->fresh(), $action);
            if (! ($telegramRes['success'] ?? false)) {
                Log::warning('Telegram staff leave updated notification: '.($telegramRes['message'] ?? 'Failed to send'));
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram staff leave updated notification error: '.$e->getMessage());
        }

        return redirect()->route('staff-leaves.index')
            ->with('success', 'បានកែប្រែទិន្នន័យច្បាប់ឈប់សម្រាកដោយជោគជ័យ។');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeLeave $staff_leaf)
    {
        $staff_leaf->delete();

        return redirect()->route('staff-leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }
}
