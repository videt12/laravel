<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Core\School;
use App\Models\Core\SchoolSetting;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeAttendance;
use App\Models\Employees\EmployeeCard;
use App\Models\Employees\EmployeeLeave;
use App\Services\Telegram\TelegramNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'daily');
        $currentDate = $request->filled('date') ? $request->date : Carbon::today()->format('Y-m-d');
        $currentMonth = $request->input('month', Carbon::parse($currentDate)->format('Y-m'));
        $search = $request->input('search');
        $status = $request->input('status');
        $employeeId = $request->input('employee_id');
        $session = $request->input('session');

        $totalStaff = Employee::count();
        $allEmployees = Employee::with(['user', 'employee_position', 'employee_department'])
            ->get()
            ->sortBy(function ($emp) {
                return $emp->user ? $emp->user->name : $emp->employee_code;
            });

        // ═══════════════════════════════════════════════════════════════════
        // TODAY'S / DASHBOARD STATS
        // ═══════════════════════════════════════════════════════════════════
        $todayRecords = EmployeeAttendance::whereDate('attendance_date', $currentDate)->get();
        $activeLeavesToday = EmployeeLeave::where('status', 'Approved')
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->count();

        $presentCount = $todayRecords->filter(fn ($r) => in_array(strtolower($r->status), ['present']))->count();
        $lateCount = $todayRecords->filter(fn ($r) => in_array(strtolower($r->status), ['late']))->count();
        $absentCount = $todayRecords->filter(fn ($r) => in_array(strtolower($r->status), ['absent']))->count();
        $leaveCount = max($todayRecords->filter(fn ($r) => in_array(strtolower($r->status), ['leave', 'on leave', 'half day']))->count(), $activeLeavesToday);

        $stats = [
            'total_staff' => $totalStaff,
            'recorded' => $todayRecords->count(),
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'leave' => $leaveCount,
            'present_pct' => $totalStaff > 0 ? round(($presentCount / $totalStaff) * 100, 1) : 0,
            'late_pct' => $totalStaff > 0 ? round(($lateCount / $totalStaff) * 100, 1) : 0,
            'absent_pct' => $totalStaff > 0 ? round(($absentCount / $totalStaff) * 100, 1) : 0,
            'leave_pct' => $totalStaff > 0 ? round(($leaveCount / $totalStaff) * 100, 1) : 0,
        ];

        // Recent Activity feed for today (or latest records)
        $recentActivities = EmployeeAttendance::with(['employee.user', 'employee.employee_position'])
            ->whereHas('employee')
            ->whereDate('attendance_date', $currentDate)
            ->orderBy('updated_at', 'desc')
            ->take(8)
            ->get();

        if ($recentActivities->isEmpty()) {
            // Fallback to recent 8 across history so preview is populated
            $recentActivities = EmployeeAttendance::with(['employee.user', 'employee.employee_position'])
                ->whereHas('employee')
                ->orderBy('attendance_date', 'desc')
                ->orderBy('updated_at', 'desc')
                ->take(8)
                ->get();
        }

        // ═══════════════════════════════════════════════════════════════════
        // TAB-SPECIFIC DATA
        // ═══════════════════════════════════════════════════════════════════
        $monthlyRecords = null;
        $monthlyStats = null;
        $reportData = null;

        if ($tab === 'monthly') {
            $monthStart = Carbon::parse($currentMonth.'-01')->startOfMonth()->format('Y-m-d');
            $monthEnd = Carbon::parse($currentMonth.'-01')->endOfMonth()->format('Y-m-d');

            $mQuery = EmployeeAttendance::with(['employee.user', 'employee.employee_department', 'employee.employee_position'])
                ->whereHas('employee')
                ->whereBetween('attendance_date', [$monthStart, $monthEnd]);

            if ($employeeId) {
                $mQuery->where('employee_id', $employeeId);
            }

            if ($search) {
                $mQuery->whereHas('employee', function ($eq) use ($search) {
                    $eq->where('employee_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        });
                });
            }

            if ($status) {
                $mQuery->where('status', $status);
            }

            $monthlyRecords = $mQuery->orderBy('attendance_date', 'desc')->paginate(15)->withQueryString();

            // Monthly stats
            $allMonthLogs = EmployeeAttendance::whereBetween('attendance_date', [$monthStart, $monthEnd])
                ->when($employeeId, fn ($q) => $q->where('employee_id', $employeeId))
                ->get();

            $monthlyStats = [
                'total' => $allMonthLogs->count(),
                'present' => $allMonthLogs->filter(fn ($r) => in_array(strtolower($r->status), ['present']))->count(),
                'late' => $allMonthLogs->filter(fn ($r) => in_array(strtolower($r->status), ['late']))->count(),
                'absent' => $allMonthLogs->filter(fn ($r) => in_array(strtolower($r->status), ['absent']))->count(),
                'leave' => $allMonthLogs->filter(fn ($r) => in_array(strtolower($r->status), ['leave', 'on leave', 'half day']))->count(),
            ];
        } elseif ($tab === 'reports') {
            $monthStart = Carbon::parse($currentMonth.'-01')->startOfMonth()->format('Y-m-d');
            $monthEnd = Carbon::parse($currentMonth.'-01')->endOfMonth()->format('Y-m-d');

            $reportData = $allEmployees->map(function ($emp) use ($monthStart, $monthEnd) {
                $logs = EmployeeAttendance::where('employee_id', $emp->id)
                    ->whereBetween('attendance_date', [$monthStart, $monthEnd])
                    ->get();

                $p = $logs->filter(fn ($r) => in_array(strtolower($r->status), ['present']))->count();
                $l = $logs->filter(fn ($r) => in_array(strtolower($r->status), ['late']))->count();
                $a = $logs->filter(fn ($r) => in_array(strtolower($r->status), ['absent']))->count();
                $lv = $logs->filter(fn ($r) => in_array(strtolower($r->status), ['leave', 'on leave', 'half day']))->count();
                $total = $logs->count();

                $rate = $total > 0 ? round((($p + $l) / $total) * 100) : 0;

                return [
                    'employee' => $emp,
                    'present' => $p,
                    'late' => $l,
                    'absent' => $a,
                    'leave' => $lv,
                    'total' => $total,
                    'rate' => $rate,
                ];
            });
        }

        // Daily table query (used on dashboard and daily view)
        $dQuery = EmployeeAttendance::with(['employee.user', 'employee.employee_department', 'employee.employee_position'])
            ->whereHas('employee')
            ->whereDate('attendance_date', $currentDate);

        if ($employeeId) {
            $dQuery->where('employee_id', $employeeId);
        }

        if ($status) {
            $dQuery->where('status', $status);
        }

        if ($session === 'morning') {
            $dQuery->where(function ($q) {
                $q->whereNotNull('in_time')->orWhereNotNull('out_time');
            });
        } elseif ($session === 'afternoon') {
            $dQuery->where(function ($q) {
                $q->whereNotNull('afternoon_in_time')->orWhereNotNull('afternoon_out_time');
            });
        }

        if ($search) {
            $dQuery->whereHas('employee', function ($eq) use ($search) {
                $eq->where('employee_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $attendances = $dQuery->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        $latestScanned = $recentActivities->first();

        // Check if authenticated user is Superuser / Admin
        $user = auth()->user();
        $isSuperUser = false;
        if ($user) {
            $superSlugs = ['super-admin', 'super_admin', 'superadmin', 'admin', 'principal'];
            $isSuperUser = $user->roles()
                ->where(function ($q) use ($superSlugs) {
                    $q->whereIn('slug', $superSlugs)
                        ->orWhereIn(DB::raw('LOWER(name)'), ['super admin', 'admin', 'principal']);
                })->exists();
        }

        $cardsData = null;
        if ($tab === 'cards') {
            $cQuery = Employee::with(['user', 'employee_position', 'employee_department', 'activeCard', 'employeeCards']);
            if ($search) {
                $cQuery->where(function ($q) use ($search) {
                    $q->where('employee_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        });
                });
            }
            $cardsData = $cQuery->orderBy('employee_code')->paginate(15)->withQueryString();
        }

        $shiftSettings = self::getShiftSettings();

        return view('employees.attendance.index', compact(
            'tab',
            'attendances',
            'currentDate',
            'currentMonth',
            'stats',
            'recentActivities',
            'latestScanned',
            'allEmployees',
            'monthlyRecords',
            'monthlyStats',
            'reportData',
            'cardsData',
            'isSuperUser',
            'shiftSettings'
        ));
    }

    /**
     * AJAX Endpoint for Staff QR Code and ID Scanner.
     */
    public function scanQr(Request $request)
    {
        $rawCode = trim($request->input('code') ?? $request->input('qr_code') ?? '');

        if (empty($rawCode)) {
            return response()->json([
                'success' => false,
                'message' => 'សូមផ្តល់កូដ QR ឬអត្តលេខបុគ្គលិកឱ្យបានត្រឹមត្រូវ។',
            ], 422);
        }

        // Support URLs or JSON inside QR code
        $cleanCode = trim($rawCode);
        if (str_starts_with($cleanCode, '{') && str_ends_with($cleanCode, '}')) {
            $decoded = json_decode($cleanCode, true);
            $cleanCode = $decoded['code'] ?? $decoded['id'] ?? $decoded['employee_code'] ?? $cleanCode;
        } elseif (filter_var($cleanCode, FILTER_VALIDATE_URL)) {
            $parts = parse_url($cleanCode);
            if (isset($parts['query'])) {
                parse_str($parts['query'], $qParams);
                $cleanCode = $qParams['code'] ?? $qParams['employee_code'] ?? $qParams['id'] ?? $cleanCode;
            } else {
                $segments = array_filter(explode('/', trim($parts['path'] ?? '', '/')));
                if (! empty($segments)) {
                    $cleanCode = end($segments);
                }
            }
        }

        // Strip common prefixes from barcode/label text like "STAFF-ID: EMP-1001", "ID: EMP-1001"
        $cleanCode = trim(preg_replace('/^(STAFF[\-_]?ID|STAFF|ID|EMP[\-_]?ID)\s*[:=\-]\s*/i', '', $cleanCode));

        // Find employee by employee_code (exact or case-insensitive) or id
        $employee = Employee::with(['user', 'employee_department', 'employee_position', 'employee_status'])
            ->where('employee_code', $cleanCode)
            ->orWhereRaw('LOWER(employee_code) = ?', [strtolower($cleanCode)])
            ->orWhere('id', $cleanCode)
            ->first();

        if (! $employee) {
            // Also try search by user email or name
            $employee = Employee::with(['user', 'employee_department', 'employee_position', 'employee_status'])
                ->whereHas('user', function ($uq) use ($cleanCode) {
                    $uq->where('name', 'like', "%{$cleanCode}%")
                        ->orWhere('email', $cleanCode);
                })
                ->first();
        }

        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => "រកមិនឃើញបុគ្គលិកដែលមានអត្តលេខ '{$cleanCode}' ទេ។ សូមពិនិត្យកាតឡើងវិញ។",
            ], 404);
        }

        $now = self::localNow();
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i');
        $currentTimeWithSec = $now->format('H:i:s');

        $attendance = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $action = 'Check In';
        $message = 'Check In Successful';

        // Process live webcam snapshot if provided
        $snapshotUrl = null;
        $snapFilename = null;
        if ($request->filled('snapshot')) {
            $snapshotData = $request->input('snapshot');
            if (is_string($snapshotData) && preg_match('/^data:image\/(\w+);base64,/', $snapshotData)) {
                $image = substr($snapshotData, strpos($snapshotData, ',') + 1);
                $decoded = base64_decode($image);
                if ($decoded !== false) {
                    $snapFilename = 'attendance-snapshots/'.$employee->id.'_'.$now->format('Ymd_His').'.jpg';
                    Storage::disk('public')->put($snapFilename, $decoded);
                    $snapshotUrl = asset('storage/'.$snapFilename);
                }
            }
        }

        $shiftSettings = self::getShiftSettings();
        $eval = self::evaluateAttendanceScan($shiftSettings, $now, $attendance);

        if (! $eval['can_record']) {
            return response()->json([
                'success' => false,
                'error' => $eval['error_code'] ?? 'scan_not_allowed',
                'action_label' => $eval['action_label'] ?? 'មិនទាន់ដល់ពេលកំណត់',
                'message' => $eval['message'],
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->full_name ?? $employee->user?->name ?? 'បុគ្គលិក',
                    'code' => $employee->employee_code,
                    'position' => $employee->employee_position?->name ?? 'គ្រូបង្រៀន',
                    'department' => $employee->employee_department?->name ?? 'ទូទៅ',
                    'photo_url' => $employee->photo_url,
                    'has_photo' => ! empty($employee->photo_url),
                    'initials' => strtoupper(mb_substr($employee->user?->name ?? 'EM', 0, 2)),
                ],
            ], 422);
        }

        $action = $eval['action_label'];
        $message = $eval['message'];
        $status = $eval['status'] ?? ($attendance?->status ?? 'Present');

        if ($eval['action'] === 'morning_in') {
            $remarks = 'QR Scanner Attendance (ព្រឹក ចូល)';
            if ($snapFilename) {
                $remarks .= ' [Snapshot: '.$snapFilename.']';
            }

            if (! $attendance) {
                $attendance = EmployeeAttendance::create([
                    'employee_id' => $employee->id,
                    'attendance_date' => $today,
                    'status' => $status,
                    'in_time' => $currentTimeWithSec,
                    'remarks' => $remarks,
                ]);
            } else {
                $attendance->update([
                    'in_time' => $currentTimeWithSec,
                    'status' => $status,
                    'remarks' => $remarks,
                ]);
            }
        } elseif ($eval['action'] === 'morning_out') {
            $updateData = ['out_time' => $currentTimeWithSec];
            if ($snapFilename) {
                $updateData['remarks'] = ($attendance->remarks ?? 'QR Scanner Attendance').' [Morning Out Snapshot: '.$snapFilename.']';
            }
            $attendance->update($updateData);
        } elseif ($eval['action'] === 'afternoon_in') {
            $remarks = 'QR Scanner Attendance (រសៀល ចូលវិញ)';
            if ($snapFilename) {
                $remarks .= ' [Snapshot: '.$snapFilename.']';
            }

            if (! $attendance) {
                $attendance = EmployeeAttendance::create([
                    'employee_id' => $employee->id,
                    'attendance_date' => $today,
                    'status' => $status,
                    'in_time' => $currentTimeWithSec,
                    'afternoon_in_time' => $currentTimeWithSec,
                    'remarks' => $remarks,
                ]);
            } else {
                $attendance->update([
                    'afternoon_in_time' => $currentTimeWithSec,
                    'status' => $status,
                    'remarks' => ($attendance->remarks ?? '').' | '.$remarks,
                ]);
            }
        } elseif ($eval['action'] === 'afternoon_out') {
            $updateData = [
                'afternoon_out_time' => $currentTimeWithSec,
                'out_time' => $currentTimeWithSec,
            ];
            if ($snapFilename) {
                $updateData['remarks'] = ($attendance->remarks ?? 'QR Scanner Attendance').' [Afternoon Out Snapshot: '.$snapFilename.']';
            }
            $attendance->update($updateData);
        }

        // Trigger Telegram Staff Attendance notification
        try {
            $telegram = app(TelegramNotificationService::class);
            if ($telegram->isEnabled()) {
                $snapshotFullPath = $snapFilename ? Storage::disk('public')->path($snapFilename) : null;

                $telegram->sendStaffAttendanceAlert(
                    $employee,
                    $action,
                    $now->format('h:i:s A'),
                    $attendance->status ?? 'Present',
                    'QR Scanner Attendance',
                    $snapshotFullPath
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram Staff Attendance Alert error: '.$e->getMessage());
        }

        $empName = $employee->user->name ?? 'Staff Member';
        $initials = strtoupper(substr($empName, 0, 2));

        return response()->json([
            'success' => true,
            'action' => $action,
            'message' => $message,
            'snapshot_url' => $snapshotUrl ?? $attendance->snapshot_url,
            'employee' => [
                'id' => $employee->id,
                'name' => $empName,
                'code' => $employee->employee_code,
                'position' => $employee->employee_position->name ?? 'Staff',
                'department' => $employee->employee_department->name ?? 'General',
                'email' => $employee->user->email ?? '—',
                'photo_url' => $employee->photo_url,
                'initials' => $initials,
                'status' => $employee->employee_status->name ?? 'Active',
            ],
            'time' => $now->format('h:i:s A'),
            'date' => $now->format('D, d F Y'),
            'attendance' => [
                'id' => $attendance->id,
                'status' => $attendance->status,
                'in_time' => $attendance->in_time ? Carbon::parse($attendance->in_time)->format('h:i A') : '—',
                'out_time' => $attendance->out_time ? Carbon::parse($attendance->out_time)->format('h:i A') : '—',
                'afternoon_in_time' => $attendance->afternoon_in_time ? Carbon::parse($attendance->afternoon_in_time)->format('h:i A') : '—',
                'afternoon_out_time' => $attendance->afternoon_out_time ? Carbon::parse($attendance->afternoon_out_time)->format('h:i A') : '—',
            ],
        ]);
    }

    /**
     * Dedicated Staff Attendance QR Scanner View.
     */
    public function scanner(Request $request)
    {
        $shiftSettings = self::getShiftSettings();
        $activeShiftStatus = self::getActiveShiftStatus($shiftSettings, self::localNow());

        return view('employees.attendance.scanner', compact('shiftSettings', 'activeShiftStatus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employees = Employee::with(['user', 'employee_department', 'employee_position'])
            ->get()
            ->sortBy(function ($emp) {
                return $emp->user ? $emp->user->name : '';
            });

        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        return view('employees.attendance.create', compact('employees', 'selectedDate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|string|max:50',
            'in_time' => 'nullable|date_format:H:i',
            'out_time' => 'nullable|date_format:H:i|after:in_time',
            'remarks' => 'nullable|string|max:255',
        ]);

        $exists = EmployeeAttendance::where('employee_id', $validated['employee_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Attendance record already exists for this employee on this date.');
        }

        EmployeeAttendance::create($validated);

        // Trigger Telegram Staff Attendance notification
        try {
            $telegram = app(TelegramNotificationService::class);
            if ($telegram->isEnabled()) {
                $employee = Employee::find($validated['employee_id']);
                if ($employee) {
                    $action = ! empty($validated['out_time']) ? 'Check Out' : 'Check In';
                    $time = ! empty($validated['out_time']) ? $validated['out_time'] : ($validated['in_time'] ?? now()->format('H:i:s'));
                    $telegram->sendStaffAttendanceAlert(
                        $employee,
                        $action,
                        $time,
                        $validated['status'] ?? 'Present',
                        $validated['remarks'] ?? 'Manual Entry'
                    );
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram Staff Attendance Alert error: '.$e->getMessage());
        }

        return redirect()->route('staff-attendance.index', ['date' => $validated['attendance_date']])
            ->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Display the specified resource / Staff Attendance Detail.
     */
    public function show(EmployeeAttendance $staff_attendance, Request $request)
    {
        $staff_attendance->load('employee.user', 'employee.employee_department', 'employee.employee_position', 'employee.employee_status');

        $employee = $staff_attendance->employee;
        $month = $request->input('month', $staff_attendance->attendance_date->format('Y-m'));

        $monthStart = Carbon::parse($month.'-01')->startOfMonth()->format('Y-m-d');
        $monthEnd = Carbon::parse($month.'-01')->endOfMonth()->format('Y-m-d');

        // All records for this employee in this month
        $monthRecords = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$monthStart, $monthEnd])
            ->orderBy('attendance_date', 'desc')
            ->get();

        $presentCount = $monthRecords->filter(fn ($r) => in_array(strtolower($r->status), ['present']))->count();
        $lateCount = $monthRecords->filter(fn ($r) => in_array(strtolower($r->status), ['late']))->count();
        $absentCount = $monthRecords->filter(fn ($r) => in_array(strtolower($r->status), ['absent']))->count();
        $leaveCount = $monthRecords->filter(fn ($r) => in_array(strtolower($r->status), ['leave', 'on leave', 'half day']))->count();
        $totalDays = $monthRecords->count();

        // Attendance Rate Percentage
        $attendanceRate = $totalDays > 0 ? round((($presentCount + $lateCount) / $totalDays) * 100) : 84; // Default standard if empty

        return view('employees.attendance.show', compact(
            'staff_attendance',
            'employee',
            'month',
            'monthRecords',
            'presentCount',
            'lateCount',
            'absentCount',
            'leaveCount',
            'totalDays',
            'attendanceRate'
        ));
    }

    /**
     * Employee Attendance Detail by Employee ID.
     */
    public function employeeDetail(Employee $employee, Request $request)
    {
        $latestAttendance = EmployeeAttendance::where('employee_id', $employee->id)
            ->latest('attendance_date')
            ->first();

        if (! $latestAttendance) {
            // Create dummy instance for display
            $latestAttendance = new EmployeeAttendance([
                'employee_id' => $employee->id,
                'attendance_date' => Carbon::today(),
                'status' => 'Present',
            ]);
            $latestAttendance->setRelation('employee', $employee);
        }

        return $this->show($latestAttendance, $request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeAttendance $staff_attendance)
    {
        $staff_attendance->load(['employee.user', 'employee.employee_department', 'employee.employee_position']);

        $employees = Employee::with(['user', 'employee_department', 'employee_position'])->get()->sortBy(function ($emp) {
            return $emp->user ? $emp->user->name : '';
        });

        return view('employees.attendance.edit', compact('staff_attendance', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeAttendance $staff_attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|string|max:50',
            'in_time' => 'nullable|date_format:H:i',
            'out_time' => 'nullable|date_format:H:i|after:in_time',
            'remarks' => 'nullable|string|max:255',
        ]);

        $exists = EmployeeAttendance::where('employee_id', $validated['employee_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->where('id', '!=', $staff_attendance->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Another attendance record already exists for this employee on this date.');
        }

        $staff_attendance->update($validated);

        return redirect()->route('staff-attendance.index', ['date' => $validated['attendance_date']])
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeAttendance $staff_attendance)
    {
        $date = $staff_attendance->attendance_date;
        $staff_attendance->delete();

        return redirect()->route('staff-attendance.index', ['date' => $date->format('Y-m-d')])
            ->with('success', 'Attendance record deleted successfully.');
    }

    /**
     * Step 1 of Verification Flow: Validate Scanned Staff QR Card & Return Employee Info + Reference Photo.
     */
    public function verifyCard(Request $request)
    {
        $rawCode = trim($request->input('code') ?? $request->input('qr_code') ?? '');
        if (empty($rawCode)) {
            return response()->json([
                'success' => false,
                'error' => 'empty_code',
                'message' => 'សូមផ្តល់កូដ QR ឬ Token លើកាតបុគ្គលិក។',
            ], 422);
        }

        $card = EmployeeCard::resolveFromScannedCode($rawCode);
        if (! $card) {
            return response()->json([
                'success' => false,
                'error' => 'not_found',
                'message' => '❌ រកមិនឃើញកាតបុគ្គលិក ឬកូដ QR មិនត្រឹមត្រូវឡើយ។',
            ], 404);
        }

        if (! $card->isActive()) {
            $reason = $card->deactivation_reason ? ' ('.$card->deactivation_reason.')' : '';

            return response()->json([
                'success' => false,
                'error' => 'card_deactivated',
                'message' => '❌ កាតនេះត្រូវបានបិទដំណើរការ ('.strtoupper($card->status).')'.$reason.'! សូមទាក់ទងការិយាល័យធនធានមនុស្ស (HR)។',
                'card_number' => $card->card_number,
                'status' => $card->status,
            ], 403);
        }

        $employee = $card->employee;
        if (! $employee) {
            return response()->json([
                'success' => false,
                'error' => 'employee_not_found',
                'message' => '❌ មិនមានព័ត៌មានបុគ្គលិកភ្ជាប់នឹងកាតនេះទេ។',
            ], 404);
        }

        $employee->loadMissing(['user', 'employee_department', 'employee_position', 'employee_status']);

        $empName = $employee->full_name ?? $employee->user?->name ?? 'បុគ្គលិក';
        $empCode = $employee->employee_code;
        $empPos = $employee->employee_position?->name ?? 'គ្រូបង្រៀន';
        $empDept = $employee->employee_department?->name ?? 'ទូទៅ';
        $photoUrl = $employee->photo_url;

        // Shift & Attendance Rule Check for Today
        $shiftSettings = self::getShiftSettings();
        $now = self::localNow();
        $today = $now->format('Y-m-d');

        $att = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $eval = self::evaluateAttendanceScan($shiftSettings, $now, $att);

        if (! $eval['can_record']) {
            return response()->json([
                'success' => false,
                'error' => $eval['error_code'] ?? 'scan_not_allowed',
                'action_label' => $eval['action_label'] ?? 'មិនទាន់ដល់ពេលកំណត់',
                'message' => $eval['message'],
                'employee' => [
                    'id' => $employee->id,
                    'name' => $empName,
                    'code' => $empCode,
                    'position' => $empPos,
                    'department' => $empDept,
                    'photo_url' => $photoUrl,
                    'has_photo' => ! empty($photoUrl),
                    'initials' => strtoupper(mb_substr($empName, 0, 2)),
                ],
                'already_completed' => ! empty($eval['already_completed']),
                'status_note' => $eval['message'],
            ], 422);
        }

        $action = $eval['action_label'];
        $alreadyCompleted = ! empty($eval['already_completed']);
        $statusNote = $eval['message'];

        return response()->json([
            'success' => true,
            'card' => [
                'id' => $card->id,
                'card_number' => $card->card_number,
                'card_token' => $card->card_token,
                'status' => $card->status,
                'issued_at' => $card->issued_at?->format('d-M-Y'),
            ],
            'employee' => [
                'id' => $employee->id,
                'name' => $empName,
                'code' => $empCode,
                'position' => $empPos,
                'department' => $empDept,
                'photo_url' => $photoUrl,
                'has_photo' => ! empty($photoUrl),
                'initials' => strtoupper(mb_substr($empName, 0, 2)),
            ],
            'action' => $action,
            'already_completed' => $alreadyCompleted,
            'status_note' => $statusNote,
            'attendance_today' => $att ? [
                'in_time' => $att->in_time ? Carbon::parse($att->in_time)->format('h:i A') : null,
                'out_time' => $att->out_time ? Carbon::parse($att->out_time)->format('h:i A') : null,
                'afternoon_in_time' => $att->afternoon_in_time ? Carbon::parse($att->afternoon_in_time)->format('h:i A') : null,
                'afternoon_out_time' => $att->afternoon_out_time ? Carbon::parse($att->afternoon_out_time)->format('h:i A') : null,
            ] : null,
        ]);
    }

    /**
     * Step 2 of Flow: Save Attendance After Liveness + 1:1 Face Verification Match.
     * Enforces Anti-Spoofing, 1:1 Match Confidence, Shift Rules, Duplicate Prevention, and Telegram Alert with Staff Card image.
     */
    public function recordVerifiedAttendance(Request $request)
    {
        $cardToken = trim($request->input('card_token', ''));
        $employeeId = (int) $request->input('employee_id');
        $livenessVerified = (bool) $request->input('liveness_verified', false);
        $matchScore = (float) $request->input('verification_score', 0);
        $deviceInfo = trim($request->input('device_info', 'Kiosk Terminal'));

        if (empty($cardToken) || empty($employeeId)) {
            return response()->json([
                'success' => false,
                'message' => 'ទិន្នន័យកាត ឬបុគ្គលិកមិនពេញលេញ។',
            ], 422);
        }

        // 1. Verify Card Validity
        $card = EmployeeCard::where('card_token', $cardToken)->first();
        if (! $card || ! $card->isActive()) {
            return response()->json([
                'success' => false,
                'error' => 'invalid_card',
                'message' => '❌ កាតនេះត្រូវបានបិទដំណើរការ ឬគ្មានសុពលភាព!',
            ], 403);
        }

        if ($card->employee_id !== $employeeId) {
            return response()->json([
                'success' => false,
                'error' => 'card_mismatch',
                'message' => '❌ កាតមិនត្រូវគ្នានឹងទិន្នន័យបុគ្គលិកឡើយ!',
            ], 403);
        }

        // 2. Verify Liveness and Face Match Threshold
        if (! $livenessVerified) {
            return response()->json([
                'success' => false,
                'error' => 'liveness_failed',
                'message' => '❌ ការផ្ទៀងផ្ទាត់មនុស្សពិត (Liveness Detection) មិនបានសម្រេច! មិនអនុញ្ញាតឱ្យប្រើរូបថតជំនួសឡើយ។',
            ], 403);
        }

        if ($matchScore < 45) {
            return response()->json([
                'success' => false,
                'error' => 'face_mismatch',
                'message' => '❌ អ្នកមិនមែនជាម្ចាស់កាតនេះទេ។ មិនអាចចុះវត្តមានបានទេ។ (Face verification match failed)',
            ], 403);
        }

        $employee = $card->employee;
        if (! $employee) {
            return response()->json([
                'success' => false,
                'message' => 'រកមិនឃើញទិន្នន័យបុគ្គលិក។',
            ], 404);
        }

        $now = self::localNow();
        $today = $now->format('Y-m-d');
        $timeStr = $now->format('H:i:s');

        // 3. Shift Rules & Duplicate Prevention
        $att = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $shiftSettings = self::getShiftSettings();
        $eval = self::evaluateAttendanceScan($shiftSettings, $now, $att);

        if (! $eval['can_record']) {
            return response()->json([
                'success' => false,
                'error' => $eval['error_code'] ?? 'scan_not_allowed',
                'action_label' => $eval['action_label'] ?? 'មិនទាន់ដល់ពេលកំណត់',
                'message' => $eval['message'],
            ], 422);
        }

        $action = $eval['action_label'];
        $status = $eval['status'] ?? ($att?->status ?? 'present');

        if ($eval['action'] === 'morning_in') {
            if (! $att) {
                $att = EmployeeAttendance::create([
                    'employee_id' => $employee->id,
                    'card_id' => $card->id,
                    'card_token' => $card->card_token,
                    'attendance_date' => $today,
                    'status' => $status,
                    'in_time' => $timeStr,
                    'verification_method' => 'qr_face_verified',
                    'verification_score' => $matchScore,
                    'liveness_verified' => true,
                    'remarks' => "QR + Face Verification [Score: {$matchScore}% | វេនព្រឹក ចូល | Card: {$card->card_number}]",
                    'device_info' => $deviceInfo,
                ]);
            } else {
                $att->update([
                    'in_time' => $timeStr,
                    'status' => $status,
                    'card_id' => $card->id,
                    'card_token' => $card->card_token,
                    'verification_method' => 'qr_face_verified',
                    'verification_score' => $matchScore,
                    'liveness_verified' => true,
                    'remarks' => ($att->remarks ? $att->remarks.' | ' : '')."វេនព្រឹក ចូល Face Verified [{$matchScore}%]",
                ]);
            }
        } elseif ($eval['action'] === 'morning_out') {
            $att->update([
                'out_time' => $timeStr,
                'card_id' => $card->id,
                'card_token' => $card->card_token,
                'verification_method' => 'qr_face_verified',
                'verification_score' => $matchScore,
                'liveness_verified' => true,
                'remarks' => ($att->remarks ? $att->remarks.' | ' : '')."វេនព្រឹក ចេញ Face Verified [{$matchScore}%]",
            ]);
        } elseif ($eval['action'] === 'afternoon_in') {
            if (! $att) {
                $att = EmployeeAttendance::create([
                    'employee_id' => $employee->id,
                    'card_id' => $card->id,
                    'card_token' => $card->card_token,
                    'attendance_date' => $today,
                    'status' => $status,
                    'in_time' => $timeStr,
                    'afternoon_in_time' => $timeStr,
                    'verification_method' => 'qr_face_verified',
                    'verification_score' => $matchScore,
                    'liveness_verified' => true,
                    'remarks' => "QR + Face Verification [Score: {$matchScore}% | វេនរសៀល ចូលវិញ | Card: {$card->card_number}]",
                    'device_info' => $deviceInfo,
                ]);
            } else {
                $att->update([
                    'afternoon_in_time' => $timeStr,
                    'status' => $status,
                    'card_id' => $card->id,
                    'card_token' => $card->card_token,
                    'verification_method' => 'qr_face_verified',
                    'verification_score' => $matchScore,
                    'liveness_verified' => true,
                    'remarks' => ($att->remarks ? $att->remarks.' | ' : '')."វេនរសៀល ចូលវិញ Face Verified [{$matchScore}%]",
                ]);
            }
        } elseif ($eval['action'] === 'afternoon_out') {
            $att->update([
                'afternoon_out_time' => $timeStr,
                'out_time' => $timeStr,
                'card_id' => $card->id,
                'card_token' => $card->card_token,
                'verification_method' => 'qr_face_verified',
                'verification_score' => $matchScore,
                'liveness_verified' => true,
                'remarks' => ($att->remarks ? $att->remarks.' | ' : '')."វេនរសៀល ចេញ Face Verified [{$matchScore}%]",
            ]);
        }

        // 4. Send Telegram Notification (Requirement 7: Staff Card Image, NOT Live Camera Face)
        try {
            $telegram = app(TelegramNotificationService::class);
            if ($telegram->isEnabled()) {
                $staffCardImagePath = null;
                $photoDoc = $employee->employee_documents()->where('document_type', 'photo')->first();
                if ($photoDoc && $photoDoc->document_path && Storage::disk('public')->exists($photoDoc->document_path)) {
                    $staffCardImagePath = Storage::disk('public')->path($photoDoc->document_path);
                } elseif (! empty($employee->photo) && file_exists(public_path($employee->photo))) {
                    $staffCardImagePath = public_path($employee->photo);
                }

                $telegram->sendFaceVerifiedAttendanceAlert(
                    $employee,
                    $action,
                    $now->format('h:i A'),
                    $att->status ?? 'Present',
                    $staffCardImagePath
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram Notification Error: '.$e->getMessage());
        }

        $empName = $employee->full_name ?? $employee->user?->name ?? 'បុគ្គលិក';

        return response()->json([
            'success' => true,
            'action' => $action,
            'message' => "បានផ្ទៀងផ្ទាត់ និងកត់ត្រាវត្តមាន {$action} ដោយជោគជ័យ!",
            'verification' => [
                'score' => $matchScore,
                'liveness' => 'Verified (Passed)',
                'method' => 'QR + 1:1 Face Verification',
            ],
            'employee' => [
                'id' => $employee->id,
                'name' => $empName,
                'code' => $employee->employee_code,
                'position' => $employee->employee_position?->name ?? 'Staff',
                'department' => $employee->employee_department?->name ?? 'General',
                'photo_url' => $employee->photo_url,
                'card_number' => $card->card_number,
                'initials' => strtoupper(mb_substr($empName, 0, 2)),
            ],
            'time' => $now->format('h:i:s A'),
            'date' => $now->format('D, d F Y'),
            'attendance' => [
                'id' => $att->id,
                'status' => $att->status,
                'in_time' => $att->in_time ? Carbon::parse($att->in_time)->format('h:i A') : null,
                'out_time' => $att->out_time ? Carbon::parse($att->out_time)->format('h:i A') : null,
                'afternoon_in_time' => $att->afternoon_in_time ? Carbon::parse($att->afternoon_in_time)->format('h:i A') : null,
                'afternoon_out_time' => $att->afternoon_out_time ? Carbon::parse($att->afternoon_out_time)->format('h:i A') : null,
            ],
        ]);
    }

    /**
     * Re-issue or generate unique QR card for staff.
     */
    public function generateCard(Request $request, Employee $employee)
    {
        $card = EmployeeCard::generateFor($employee, auth()->id());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "បានបង្កើតកាតថ្មី #{$card->card_number} ជូនបុគ្គលិកជោគជ័យ!",
                'card' => $card,
            ]);
        }

        return back()->with('success', "បានបង្កើតកាតថ្មី #{$card->card_number} ជូនបុគ្គលិកជោគជ័យ!");
    }

    /**
     * Toggle Staff Card Status (Active / Inactive / Lost).
     */
    public function toggleCardStatus(Request $request, EmployeeCard $card)
    {
        if ($card->isActive()) {
            $reason = $request->input('reason', 'Deactivated by Administrator');
            $card->deactivate($reason);
            $msg = "បានបិទដំណើរការកាត #{$card->card_number} រួចរាល់។";
        } else {
            $card->activate();
            $msg = "បានបើកដំណើរការកាត #{$card->card_number} ឡើងវិញដោយជោគជ័យ!";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'card' => $card->fresh(),
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Retrieve staff attendance shift and working hours settings with defaults.
     */
    public static function getShiftSettings(): array
    {
        $saved = [];

        try {
            $school = School::first();
            $schoolId = $school ? $school->id : 1;
            $keys = [
                'staff_morning_in',
                'staff_morning_grace',
                'staff_morning_out',
                'staff_morning_scan_in_start',
                'staff_morning_scan_in_end',
                'staff_morning_scan_out_start',
                'staff_morning_scan_out_end',
                'staff_afternoon_in',
                'staff_afternoon_grace',
                'staff_afternoon_out',
                'staff_afternoon_scan_in_start',
                'staff_afternoon_scan_in_end',
                'staff_afternoon_scan_out_start',
                'staff_afternoon_scan_out_end',
                'staff_shift_split',
            ];

            $saved = SchoolSetting::where('school_id', $schoolId)
                ->whereIn('setting_key', $keys)
                ->pluck('setting_value', 'setting_key')
                ->toArray();
        } catch (\Throwable $e) {
            // Fallback to default values if database table not available
        }

        return [
            'staff_morning_in' => ! empty($saved['staff_morning_in']) ? $saved['staff_morning_in'] : '06:50',
            'staff_morning_grace' => isset($saved['staff_morning_grace']) ? (int) $saved['staff_morning_grace'] : 0,
            'staff_morning_out' => ! empty($saved['staff_morning_out']) ? $saved['staff_morning_out'] : '10:30',
            'staff_morning_scan_in_start' => ! empty($saved['staff_morning_scan_in_start']) ? $saved['staff_morning_scan_in_start'] : '06:00',
            'staff_morning_scan_in_end' => ! empty($saved['staff_morning_scan_in_end']) ? $saved['staff_morning_scan_in_end'] : '09:30',
            'staff_morning_scan_out_start' => ! empty($saved['staff_morning_scan_out_start']) ? $saved['staff_morning_scan_out_start'] : '10:00',
            'staff_morning_scan_out_end' => ! empty($saved['staff_morning_scan_out_end']) ? $saved['staff_morning_scan_out_end'] : '12:00',

            'staff_afternoon_in' => ! empty($saved['staff_afternoon_in']) ? $saved['staff_afternoon_in'] : '13:10',
            'staff_afternoon_grace' => isset($saved['staff_afternoon_grace']) ? (int) $saved['staff_afternoon_grace'] : 0,
            'staff_afternoon_out' => ! empty($saved['staff_afternoon_out']) ? $saved['staff_afternoon_out'] : '16:30',
            'staff_afternoon_scan_in_start' => ! empty($saved['staff_afternoon_scan_in_start']) ? $saved['staff_afternoon_scan_in_start'] : '12:30',
            'staff_afternoon_scan_in_end' => ! empty($saved['staff_afternoon_scan_in_end']) ? $saved['staff_afternoon_scan_in_end'] : '15:00',
            'staff_afternoon_scan_out_start' => ! empty($saved['staff_afternoon_scan_out_start']) ? $saved['staff_afternoon_scan_out_start'] : '16:00',
            'staff_afternoon_scan_out_end' => ! empty($saved['staff_afternoon_scan_out_end']) ? $saved['staff_afternoon_scan_out_end'] : '18:30',

            'staff_shift_split' => ! empty($saved['staff_shift_split']) ? $saved['staff_shift_split'] : '12:00',
        ];
    }

    /**
     * Helper to get current Carbon time in the application timezone (Asia/Phnom_Penh).
     */
    public static function localNow(): Carbon
    {
        $tz = config('app.timezone', 'Asia/Phnom_Penh');
        if ($tz === 'UTC') {
            $tz = 'Asia/Phnom_Penh';
        }

        return Carbon::now($tz);
    }

    /**
     * Helper to convert 'H:i' string to total minutes from midnight.
     */
    public static function timeToMinutes(?string $timeStr): int
    {
        if (empty($timeStr)) {
            return 0;
        }

        $parts = explode(':', $timeStr);

        return ((int) ($parts[0] ?? 0) * 60) + (int) ($parts[1] ?? 0);
    }

    /**
     * Intelligently evaluate whether a scan attempt at $now should be:
     * - 'morning_in': Check In (morning)
     * - 'morning_out': Check Out (morning)
     * - 'afternoon_in': Check In (afternoon)
     * - 'afternoon_out': Check Out (afternoon)
     * - or rejected (duplicate scan, out of scan window, too early, already completed).
     */
    public static function evaluateAttendanceScan(array $shiftSettings, Carbon $now, ?EmployeeAttendance $attendance = null): array
    {
        $splitMins = self::timeToMinutes($shiftSettings['staff_shift_split'] ?? '12:00');
        $hour = (int) $now->format('H');
        $minute = (int) $now->format('i');
        $totalMins = ($hour * 60) + $minute;
        $isAfternoon = ($totalMins >= $splitMins);

        // Morning Window Minutes
        $mIn = self::timeToMinutes($shiftSettings['staff_morning_in'] ?? '06:50');
        $mGrace = (int) ($shiftSettings['staff_morning_grace'] ?? 0);
        $mInLateThreshold = $mIn + $mGrace;
        $mScanInStart = self::timeToMinutes($shiftSettings['staff_morning_scan_in_start'] ?? '06:00');
        $mScanInEnd = self::timeToMinutes($shiftSettings['staff_morning_scan_in_end'] ?? '09:30');
        $mScanOutStart = self::timeToMinutes($shiftSettings['staff_morning_scan_out_start'] ?? '10:00');
        $mScanOutEnd = self::timeToMinutes($shiftSettings['staff_morning_scan_out_end'] ?? '12:00');

        // Afternoon Window Minutes
        $aIn = self::timeToMinutes($shiftSettings['staff_afternoon_in'] ?? '13:10');
        $aGrace = (int) ($shiftSettings['staff_afternoon_grace'] ?? 0);
        $aInLateThreshold = $aIn + $aGrace;
        $aScanInStart = self::timeToMinutes($shiftSettings['staff_afternoon_scan_in_start'] ?? '12:30');
        $aScanInEnd = self::timeToMinutes($shiftSettings['staff_afternoon_scan_in_end'] ?? '15:00');
        $aScanOutStart = self::timeToMinutes($shiftSettings['staff_afternoon_scan_out_start'] ?? '16:00');
        $aScanOutEnd = self::timeToMinutes($shiftSettings['staff_afternoon_scan_out_end'] ?? '18:30');

        $formatTime = fn ($val) => Carbon::parse($val)->format('h:i A');

        if (! $isAfternoon) {
            // ☀️ MORNING SESSION (< split time)

            // 1. Before morning scan-in starts
            if ($totalMins < $mScanInStart) {
                return [
                    'can_record' => false,
                    'action' => 'too_early',
                    'action_label' => 'មិនទាន់ដល់ពេលកំណត់',
                    'error_code' => 'morning_in_not_started',
                    'message' => 'មិនទាន់ដល់ពេលកំណត់ស្កេនចូលវេនព្រឹកទេ! (ម៉ោងស្កេនចាប់ផ្តើមពីម៉ោង '.$formatTime($shiftSettings['staff_morning_scan_in_start']).')',
                ];
            }

            // 2. Inside Morning Scan-In Window
            if ($totalMins >= $mScanInStart && $totalMins <= $mScanInEnd) {
                if (! empty($attendance?->in_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'duplicate_in',
                        'action_label' => 'បានស្កេនចូលរួចហើយ',
                        'error_code' => 'morning_in_duplicate',
                        'message' => 'លោកអ្នកបានស្កេនចូលវេនព្រឹករួចរាល់ហើយនៅម៉ោង '.Carbon::parse($attendance->in_time)->format('h:i A').' (មិនអនុញ្ញាតឱ្យស្កេនចូលស្ទួនទេ)។',
                    ];
                }

                $isLate = ($totalMins > $mInLateThreshold);

                return [
                    'can_record' => true,
                    'action' => 'morning_in',
                    'action_label' => 'Check In (ព្រឹក)',
                    'is_late' => $isLate,
                    'status' => $isLate ? 'late' : 'present',
                    'message' => 'Check In វេនព្រឹកជោគជ័យ'.($isLate ? ' (យឺត)' : ''),
                ];
            }

            // 3. Between Scan-In End and Scan-Out Start (Working Hours: In Expired, Out Not Started)
            if ($totalMins > $mScanInEnd && $totalMins < $mScanOutStart) {
                // If employee HAS checked in: cannot check out yet
                if (! empty($attendance?->in_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'out_not_started',
                        'action_label' => 'មិនទាន់ដល់ពេលកំណត់',
                        'error_code' => 'morning_out_not_started',
                        'message' => 'មិនទាន់ដល់ពេលកំណត់ស្កេនចេញវេនព្រឹកទេ! (ម៉ោងអនុញ្ញាតស្កេនចេញចាប់ពីម៉ោង '.$formatTime($shiftSettings['staff_morning_scan_out_start']).' ដល់ '.$formatTime($shiftSettings['staff_morning_scan_out_end']).')',
                    ];
                }

                // If employee has NOT checked in: Scan-in has EXPIRED!
                return [
                    'can_record' => false,
                    'action' => 'in_expired',
                    'action_label' => 'ផុតកំណត់ស្កេនចូល',
                    'error_code' => 'morning_in_expired',
                    'message' => 'បានផុតកំណត់ម៉ោងស្កេនចូលវេនព្រឹកហើយ! (ម៉ោងស្កេនចូលត្រូវបានបិទត្រឹមម៉ោង '.$formatTime($shiftSettings['staff_morning_scan_in_end']).')',
                ];
            }

            // 4. Inside Morning Scan-Out Window (or up to split time)
            if ($totalMins >= $mScanOutStart) {
                // If already scanned out
                if (! empty($attendance?->out_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'duplicate_out',
                        'action_label' => 'បានស្កេនចេញរួចហើយ',
                        'error_code' => 'morning_out_duplicate',
                        'message' => 'លោកអ្នកបានស្កេនចេញវេនព្រឹករួចរាល់ហើយនៅម៉ោង '.Carbon::parse($attendance->out_time)->format('h:i A').' (ត្រៀមចូលវិញវេនរសៀលម៉ោង '.$formatTime($shiftSettings['staff_afternoon_scan_in_start']).')។',
                    ];
                }

                // If NOT checked in morning: cannot scan out without check-in
                if (empty($attendance?->in_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'no_in_record',
                        'action_label' => 'មិនអាចស្កេនចេញ',
                        'error_code' => 'morning_no_in',
                        'message' => 'មិនអាចស្កេនចេញបានទេ ដោយសារលោកអ្នកមិនបានស្កេនចូលវេនព្រឹក!',
                    ];
                }

                return [
                    'can_record' => true,
                    'action' => 'morning_out',
                    'action_label' => 'Check Out (ព្រឹក)',
                    'is_late' => false,
                    'status' => $attendance?->status ?? 'present',
                    'message' => 'Check Out វេនព្រឹកជោគជ័យ (សម្រាកបាយថ្ងៃត្រង់)',
                ];
            }
        } else {
            // 🌤️ AFTERNOON SESSION (>= split time)

            // 1. Before afternoon scan-in starts
            if ($totalMins < $aScanInStart) {
                // If they checked in morning but missed morning checkout, allow late morning out
                if (! empty($attendance?->in_time) && empty($attendance?->out_time)) {
                    return [
                        'can_record' => true,
                        'action' => 'morning_out',
                        'action_label' => 'Check Out (ព្រឹក យឺត)',
                        'is_late' => false,
                        'status' => $attendance?->status ?? 'present',
                        'message' => 'Check Out វេនព្រឹកជោគជ័យ',
                    ];
                }

                return [
                    'can_record' => false,
                    'action' => 'too_early',
                    'action_label' => 'មិនទាន់ដល់ពេលកំណត់',
                    'error_code' => 'afternoon_in_not_started',
                    'message' => 'មិនទាន់ដល់ពេលកំណត់ស្កេនចូលវិញវេនរសៀលទេ! (ម៉ោងស្កេនចាប់ផ្តើមពីម៉ោង '.$formatTime($shiftSettings['staff_afternoon_scan_in_start']).')',
                ];
            }

            // 2. Inside Afternoon Scan-In Window
            if ($totalMins >= $aScanInStart && $totalMins <= $aScanInEnd) {
                if (! empty($attendance?->afternoon_in_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'duplicate_in',
                        'action_label' => 'បានស្កេនចូលរសៀលរួចហើយ',
                        'error_code' => 'afternoon_in_duplicate',
                        'message' => 'លោកអ្នកបានស្កេនចូលវេនរសៀលរួចរាល់ហើយនៅម៉ោង '.Carbon::parse($attendance->afternoon_in_time)->format('h:i A').' (មិនអនុញ្ញាតឱ្យស្កេនចូលស្ទួនទេ)។',
                    ];
                }

                $isLate = ($totalMins > $aInLateThreshold);
                $prevStatus = $attendance?->status;
                $finalStatus = ($isLate || strtolower((string) $prevStatus) === 'late') ? 'late' : ($prevStatus ?? 'present');

                return [
                    'can_record' => true,
                    'action' => 'afternoon_in',
                    'action_label' => 'Check In (រសៀល)',
                    'is_late' => $isLate,
                    'status' => $finalStatus,
                    'message' => 'Check In ចូលវិញវេនរសៀលជោគជ័យ'.($isLate ? ' (យឺត)' : ''),
                ];
            }

            // 3. Between Afternoon In End and Afternoon Out Start (Working Hours: In Expired, Out Not Started)
            if ($totalMins > $aScanInEnd && $totalMins < $aScanOutStart) {
                // If employee HAS checked in afternoon: cannot check out yet
                if (! empty($attendance?->afternoon_in_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'out_not_started',
                        'action_label' => 'មិនទាន់ដល់ពេលកំណត់',
                        'error_code' => 'afternoon_out_not_started',
                        'message' => 'មិនទាន់ដល់ពេលកំណត់ស្កេនចេញវេនរសៀលទេ! (ម៉ោងអនុញ្ញាតស្កេនចេញចាប់ពីម៉ោង '.$formatTime($shiftSettings['staff_afternoon_scan_out_start']).' ដល់ '.$formatTime($shiftSettings['staff_afternoon_scan_out_end']).')',
                    ];
                }

                // If employee has NOT checked in afternoon: Scan-in has EXPIRED!
                return [
                    'can_record' => false,
                    'action' => 'in_expired',
                    'action_label' => 'ផុតកំណត់ស្កេនចូល',
                    'error_code' => 'afternoon_in_expired',
                    'message' => 'បានផុតកំណត់ម៉ោងស្កេនចូលវេនរសៀលហើយ! (ម៉ោងស្កេនចូលត្រូវបានបិទត្រឹមម៉ោង '.$formatTime($shiftSettings['staff_afternoon_scan_in_end']).')',
                ];
            }

            // 4. Afternoon Check-Out Window or evening
            if ($totalMins >= $aScanOutStart) {
                // If already scanned out
                if (! empty($attendance?->afternoon_out_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'completed',
                        'action_label' => 'បានស្កេនចេញរួចរាល់ហើយ',
                        'already_completed' => true,
                        'error_code' => 'afternoon_out_duplicate',
                        'message' => 'លោកអ្នកបានស្កេនចេញវេនរសៀលរួចរាល់ហើយនៅម៉ោង '.Carbon::parse($attendance->afternoon_out_time)->format('h:i A').' (បានបញ្ចប់ការងារពេញលេញសម្រាប់ថ្ងៃនេះ)។',
                    ];
                }

                // If never checked in afternoon (and never checked in morning)
                if (empty($attendance?->afternoon_in_time) && empty($attendance?->in_time)) {
                    return [
                        'can_record' => false,
                        'action' => 'no_in_record',
                        'action_label' => 'មិនអាចស្កេនចេញ',
                        'error_code' => 'afternoon_no_in',
                        'message' => 'មិនអាចស្កេនចេញបានទេ ដោយសារលោកអ្នកមិនបានស្កេនចូលវេនរសៀល!',
                    ];
                }

                return [
                    'can_record' => true,
                    'action' => 'afternoon_out',
                    'action_label' => 'Check Out (រសៀល)',
                    'is_late' => false,
                    'status' => $attendance?->status ?? 'present',
                    'message' => 'Check Out វេនរសៀលជោគជ័យ (បញ្ចប់ការងារប្រចាំថ្ងៃ)',
                ];
            }
        }

        return [
            'can_record' => false,
            'action' => 'out_of_bounds',
            'action_label' => 'ក្រៅម៉ោងស្កេន',
            'error_code' => 'out_of_bounds',
            'message' => 'មិនស្ថិតក្នុងចន្លោះម៉ោងស្កេនវត្តមានឡើយ។',
        ];
    }

    /**
     * Get active shift status based on shift settings and current time.
     */
    public static function getActiveShiftStatus(array $shiftSettings, Carbon $now): array
    {
        $splitMins = self::timeToMinutes($shiftSettings['staff_shift_split'] ?? '12:00');
        $hour = (int) $now->format('H');
        $minute = (int) $now->format('i');
        $totalMins = ($hour * 60) + $minute;

        $mScanInStart = self::timeToMinutes($shiftSettings['staff_morning_scan_in_start'] ?? '06:00');
        $mScanInEnd = self::timeToMinutes($shiftSettings['staff_morning_scan_in_end'] ?? '09:30');
        $mScanOutStart = self::timeToMinutes($shiftSettings['staff_morning_scan_out_start'] ?? '10:00');
        $mScanOutEnd = self::timeToMinutes($shiftSettings['staff_morning_scan_out_end'] ?? '12:00');

        $aScanInStart = self::timeToMinutes($shiftSettings['staff_afternoon_scan_in_start'] ?? '12:30');
        $aScanInEnd = self::timeToMinutes($shiftSettings['staff_afternoon_scan_in_end'] ?? '15:00');
        $aScanOutStart = self::timeToMinutes($shiftSettings['staff_afternoon_scan_out_start'] ?? '16:00');
        $aScanOutEnd = self::timeToMinutes($shiftSettings['staff_afternoon_scan_out_end'] ?? '18:30');

        $format = fn ($h) => Carbon::parse($h)->format('h:i A');

        if ($totalMins >= $mScanInStart && $totalMins <= $mScanInEnd) {
            return [
                'type' => 'morning_in',
                'label' => 'ស្កេនចូល វេនព្រឹក (Check In)',
                'badge_color' => 'success',
                'range_label' => $format($shiftSettings['staff_morning_scan_in_start']).' – '.$format($shiftSettings['staff_morning_scan_in_end']),
                'is_active' => true,
                'session' => 'morning',
                'action_type' => 'in',
            ];
        }

        if ($totalMins > $mScanInEnd && $totalMins < $mScanOutStart) {
            return [
                'type' => 'morning_waiting_out',
                'label' => 'ម៉ោងបំពេញការងារ (មិនទាន់ដល់ម៉ោងចេញ)',
                'badge_color' => 'dark',
                'range_label' => 'ផុតកំណត់ចូល • ម៉ោងចេញ: '.$format($shiftSettings['staff_morning_scan_out_start']),
                'is_active' => false,
                'session' => 'morning',
                'action_type' => 'waiting_out',
            ];
        }

        if ($totalMins >= $mScanOutStart && $totalMins <= $mScanOutEnd) {
            return [
                'type' => 'morning_out',
                'label' => 'ស្កេនចេញ វេនព្រឹក (Check Out)',
                'badge_color' => 'warning',
                'range_label' => $format($shiftSettings['staff_morning_scan_out_start']).' – '.$format($shiftSettings['staff_morning_scan_out_end']),
                'is_active' => true,
                'session' => 'morning',
                'action_type' => 'out',
            ];
        }

        if ($totalMins >= $aScanInStart && $totalMins <= $aScanInEnd) {
            return [
                'type' => 'afternoon_in',
                'label' => 'ស្កេនចូលវិញ វេនរសៀល (Check In)',
                'badge_color' => 'info',
                'range_label' => $format($shiftSettings['staff_afternoon_scan_in_start']).' – '.$format($shiftSettings['staff_afternoon_scan_in_end']),
                'is_active' => true,
                'session' => 'afternoon',
                'action_type' => 'in',
            ];
        }

        if ($totalMins > $aScanInEnd && $totalMins < $aScanOutStart) {
            return [
                'type' => 'afternoon_waiting_out',
                'label' => 'ម៉ោងបំពេញការងារ (មិនទាន់ដល់ម៉ោងចេញ)',
                'badge_color' => 'dark',
                'range_label' => 'ផុតកំណត់ចូល • ម៉ោងចេញ: '.$format($shiftSettings['staff_afternoon_scan_out_start']),
                'is_active' => false,
                'session' => 'afternoon',
                'action_type' => 'waiting_out',
            ];
        }

        if ($totalMins >= $aScanOutStart && $totalMins <= $aScanOutEnd) {
            return [
                'type' => 'afternoon_out',
                'label' => 'ស្កេនចេញ វេនរសៀល (Check Out)',
                'badge_color' => 'primary',
                'range_label' => $format($shiftSettings['staff_afternoon_scan_out_start']).' – '.$format($shiftSettings['staff_afternoon_scan_out_end']),
                'is_active' => true,
                'session' => 'afternoon',
                'action_type' => 'out',
            ];
        }

        return [
            'type' => 'closed',
            'label' => 'ក្រៅចន្លោះម៉ោងស្កេនវត្តមាន',
            'badge_color' => 'secondary',
            'range_label' => 'បិទការស្កេនជាបណ្ដោះអាសន្ន',
            'is_active' => false,
            'session' => $totalMins < $splitMins ? 'morning' : 'afternoon',
            'action_type' => 'none',
        ];
    }

    /**
     * Save/update staff attendance working hours and shift configuration.
     */
    public function updateShiftSettings(Request $request)
    {
        $validated = $request->validate([
            'staff_morning_in' => 'required|date_format:H:i',
            'staff_morning_out' => 'required|date_format:H:i',
            'staff_morning_grace' => 'nullable|integer|min:0|max:120',
            'staff_morning_scan_in_start' => 'required|date_format:H:i',
            'staff_morning_scan_in_end' => 'required|date_format:H:i',
            'staff_morning_scan_out_start' => 'required|date_format:H:i',
            'staff_morning_scan_out_end' => 'required|date_format:H:i',

            'staff_afternoon_in' => 'required|date_format:H:i',
            'staff_afternoon_out' => 'required|date_format:H:i',
            'staff_afternoon_grace' => 'nullable|integer|min:0|max:120',
            'staff_afternoon_scan_in_start' => 'required|date_format:H:i',
            'staff_afternoon_scan_in_end' => 'required|date_format:H:i',
            'staff_afternoon_scan_out_start' => 'required|date_format:H:i',
            'staff_afternoon_scan_out_end' => 'required|date_format:H:i',

            'staff_shift_split' => 'required|date_format:H:i',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;

        foreach ($validated as $key => $val) {
            SchoolSetting::updateOrCreate(
                ['school_id' => $schoolId, 'setting_key' => $key],
                ['setting_value' => (string) ($val ?? '0')]
            );
        }

        return redirect()->route('settings.index', ['tab' => 'system'])
            ->with('success', 'បានកែប្រែ និងរក្សាទុកម៉ោងកំណត់ការងារ និងចន្លោះម៉ោងស្កេនវត្តមាន (Working Hours & Scan Windows) ដោយជោគជ័យ!');
    }
}
