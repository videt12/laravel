<?php

namespace App\Http\Controllers;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\TeacherAssignment;
use App\Models\Attendance\AttendanceSession;
use App\Models\Attendance\AttendanceStatus;
use App\Models\Attendance\StudentAttendance;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use App\Models\Students\Student;
use App\Services\Telegram\TelegramNotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceSessionController extends Controller
{
    /* ─────────────────────────────────────────────────────────────
    |  INDEX
    ───────────────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])
            ->when($request->filled('academic_year_id'), fn ($q) => $q->where('academic_year_id', $request->academic_year_id))
            ->get();

        $query = AttendanceSession::with([
            'schoolClass.grade',
            'schoolClass.section',
            'section',
            'recordedBy',
            'academic_year',
            'teacherAssignment.class_subject.subject',
            'teacherAssignment.employee.user',
        ])->withCount('student_attendances as total_marked');

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }
        if ($request->filled('month')) {
            $query->whereYear('attendance_date', substr($request->month, 0, 4))
                ->whereMonth('attendance_date', substr($request->month, 5, 2));
        }

        $sessions = $query->latest('attendance_date')->paginate(20)->withQueryString();

        // Pre-compute student counts per class
        $classStudentCounts = Student::selectRaw('school_class_id, COUNT(*) as cnt')
            ->groupBy('school_class_id')
            ->pluck('cnt', 'school_class_id');

        return view('attendance_sessions.index', compact(
            'sessions', 'academicYears', 'classes', 'classStudentCounts'
        ));
    }

    /* ─────────────────────────────────────────────────────────────
    |  CREATE
    ───────────────────────────────────────────────────────────── */
    public function create()
    {
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->get();
        $defaultYear = $academicYears->firstWhere('is_active', 1) ?? $academicYears->first();

        // Load ALL teacher assignments with full relations for the form
        $teacherAssignments = TeacherAssignment::with([
            'employee.user',
            'school_class.grade',
            'school_class.section',
            'class_subject.subject',
            'class_subject.class.grade',
            'class_subject.class.section',
            'academic_year',
        ])->get();

        return view('attendance_sessions.create', compact(
            'academicYears', 'classes', 'defaultYear', 'teacherAssignments'
        ));
    }

    /* ─────────────────────────────────────────────────────────────
    |  STORE
    ───────────────────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'attendance_date' => 'required|date',
            'period' => 'required|integer|min:1|max:4',
            'teacher_assignment_id' => 'nullable|exists:teacher_assignments,id',
            'recorded_by' => 'nullable|exists:users,id',
        ]);

        // If teacher_assignment_id is provided, validate it belongs to the selected class
        if (! empty($validated['teacher_assignment_id'])) {
            $assignment = TeacherAssignment::with(['class_subject', 'school_class'])->find($validated['teacher_assignment_id']);
            $assignmentClassId = $assignment?->class_id ?? $assignment?->class_subject?->class_id;
            if (! $assignment || (int) $assignmentClassId !== (int) $validated['class_id']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['teacher_assignment_id' => 'The selected teaching assignment does not belong to the chosen class.']);
            }
        }

        // Duplicate check: same academic_year + class + date + period (including soft-deleted)
        $duplicate = AttendanceSession::withTrashed()
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('class_id', $validated['class_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->where('period', $validated['period'])
            ->first();

        if ($duplicate) {
            if ($duplicate->trashed()) {
                $duplicate->restore();
                $duplicate->update([
                    'teacher_assignment_id' => $validated['teacher_assignment_id'] ?? null,
                    'section_id' => $validated['section_id'] ?? null,
                    'status' => 'open',
                ]);

                return redirect()
                    ->route('attendance-sessions.take-attendance', $duplicate->id)
                    ->with('success', 'Restored previous attendance session for this class and period.');
            }

            return redirect()->back()
                ->withInput()
                ->with('duplicate_session_id', $duplicate->id)
                ->withErrors(['duplicate' => 'An attendance session already exists for this class and period on this date.']);
        }

        $session = AttendanceSession::create([
            'academic_year_id' => $validated['academic_year_id'],
            'teacher_assignment_id' => $validated['teacher_assignment_id'] ?? null,
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'attendance_date' => $validated['attendance_date'],
            'period' => $validated['period'],
            'recorded_by' => $validated['recorded_by'] ?? null,
            'status' => 'open',
        ]);

        return redirect()
            ->route('attendance-sessions.take-attendance', $session->id)
            ->with('success', 'Session created. You can now take attendance.');
    }

    /* ─────────────────────────────────────────────────────────────
    |  SHOW
    ───────────────────────────────────────────────────────────── */
    public function show($id)
    {
        $session = AttendanceSession::with([
            'schoolClass.grade',
            'schoolClass.section',
            'section',
            'recordedBy',
            'academic_year',
            'teacherAssignment.class_subject.subject',
            'teacherAssignment.employee.user',
            'student_attendances.student.user',
            'student_attendances.student.profile',
            'student_attendances.attendance_status',
        ])->findOrFail($id);

        // Calculate summary from actual student_attendances records
        $summary = [
            'total' => $session->student_attendances->count(),
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'permission' => 0,
            'other' => 0,
        ];

        foreach ($session->student_attendances as $att) {
            $code = strtoupper($att->attendance_status?->code ?? '');
            $name = strtolower($att->attendance_status?->name ?? '');

            if ($code === 'P' || str_contains($name, 'present')) {
                $summary['present']++;
            } elseif ($code === 'A' || str_contains($name, 'absent')) {
                $summary['absent']++;
            } elseif ($code === 'L' || str_contains($name, 'late')) {
                $summary['late']++;
            } elseif (in_array($code, ['OL', 'EA', 'SL', 'PERM']) || str_contains($name, 'leave')
                      || str_contains($name, 'excused') || str_contains($name, 'permission')) {
                $summary['permission']++;
            } else {
                $summary['other']++;
            }
        }

        $classStudentCount = Student::where('school_class_id', $session->class_id)->count();

        return view('attendance_sessions.show', compact('session', 'summary', 'classStudentCount'));
    }

    /* ─────────────────────────────────────────────────────────────
    |  EDIT
    ───────────────────────────────────────────────────────────── */
    public function edit($id)
    {
        $session = AttendanceSession::with([
            'schoolClass.grade',
            'schoolClass.section',
            'section',
            'recordedBy',
            'academic_year',
            'teacherAssignment.class_subject.subject',
            'teacherAssignment.employee.user',
        ])->findOrFail($id);

        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->get();
        $users = User::orderBy('name')->get();
        $hasAttendance = $session->student_attendances()->exists();

        $teacherAssignments = TeacherAssignment::with([
            'employee.user',
            'school_class.grade',
            'school_class.section',
            'class_subject.subject',
            'class_subject.class.grade',
            'class_subject.class.section',
        ])->get();

        return view('attendance_sessions.edit', compact(
            'session', 'academicYears', 'classes', 'users', 'hasAttendance', 'teacherAssignments'
        ));
    }

    /* ─────────────────────────────────────────────────────────────
    |  UPDATE
    ───────────────────────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $session = AttendanceSession::findOrFail($id);

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'attendance_date' => 'required|date',
            'period' => 'required|integer|min:1|max:4',
            'teacher_assignment_id' => 'nullable|exists:teacher_assignments,id',
            'recorded_by' => 'nullable|exists:users,id',
            'status' => 'required|in:open,closed',
        ]);

        // Validate teacher_assignment belongs to the class
        if (! empty($validated['teacher_assignment_id'])) {
            $assignment = TeacherAssignment::with(['class_subject', 'school_class'])->find($validated['teacher_assignment_id']);
            $assignmentClassId = $assignment?->class_id ?? $assignment?->class_subject?->class_id;
            if (! $assignment || (int) $assignmentClassId !== (int) $validated['class_id']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['teacher_assignment_id' => 'The selected teaching assignment does not belong to the chosen class.']);
            }
        }

        $session->update($validated);

        return redirect()
            ->route('attendance-sessions.show', $session->id)
            ->with('success', 'Attendance session updated successfully.');
    }

    /* ─────────────────────────────────────────────────────────────
    |  DESTROY (soft delete)
    ───────────────────────────────────────────────────────────── */
    public function destroy($id)
    {
        $session = AttendanceSession::findOrFail($id);
        $session->delete();

        return redirect()
            ->route('attendance-sessions.index')
            ->with('success', 'Attendance session deleted.');
    }

    /* ─────────────────────────────────────────────────────────────
    |  CLOSE SESSION
    ───────────────────────────────────────────────────────────── */
    public function closeSession($id)
    {
        // TODO: Add middleware check here when auth is implemented
        // e.g.: $this->authorize('close', $session);
        $session = AttendanceSession::findOrFail($id);
        $session->update(['status' => 'closed']);

        return redirect()
            ->route('attendance-sessions.show', $session->id)
            ->with('success', 'Session has been closed.');
    }

    /* ─────────────────────────────────────────────────────────────
    |  REOPEN SESSION
    ───────────────────────────────────────────────────────────── */
    public function reopenSession($id)
    {
        // TODO: Admin-only — add role check when auth is implemented
        $session = AttendanceSession::findOrFail($id);
        $session->update(['status' => 'open']);

        return redirect()
            ->route('attendance-sessions.show', $session->id)
            ->with('success', 'Session has been reopened.');
    }

    /* ─────────────────────────────────────────────────────────────
    |  TAKE ATTENDANCE (GET)
    ───────────────────────────────────────────────────────────── */
    public function takeAttendance($id)
    {
        $session = AttendanceSession::with([
            'schoolClass.grade',
            'schoolClass.section',
            'section',
            'academic_year',
            'teacherAssignment.class_subject.subject',
            'teacherAssignment.employee.user',
        ])->findOrFail($id);

        // TODO: Server-side authorization — when auth is live, check:
        // if (auth()->check()) {
        //     $employee = auth()->user()->employee;
        //     if ($employee && $session->teacher_assignment_id) {
        //         abort_unless($session->teacherAssignment->employee_id === $employee->id, 403);
        //     }
        // }

        // Load students from the class (existing working approach: school_class_id)
        $students = Student::with(['user', 'profile'])
            ->where('school_class_id', $session->class_id)
            ->get()
            ->sortBy(fn ($s) => $s->user?->name ?? '');

        // Existing attendance records keyed by student_id
        $existing = StudentAttendance::where('attendance_session_id', $session->id)
            ->get()
            ->keyBy('student_id');

        $statuses = AttendanceStatus::orderBy('id')->get();

        return view('attendance_sessions.take_attendance', compact(
            'session', 'students', 'existing', 'statuses'
        ));
    }

    /* ─────────────────────────────────────────────────────────────
    |  SAVE ATTENDANCE (POST)
    ───────────────────────────────────────────────────────────── */
    public function saveAttendance(Request $request, $id)
    {
        $session = AttendanceSession::findOrFail($id);

        // TODO: Server-side authorization — when auth is live, check:
        // if ($session->status === 'closed') {
        //     abort(403, 'This session is closed. Contact an administrator to reopen it.');
        // }

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.attendance_status_id' => 'required|exists:attendance_statuses,id',
            'attendance.*.remarks' => 'nullable|string|max:500',
        ]);

        $classStudentIds = Student::where('school_class_id', $session->class_id)
            ->pluck('id')
            ->toArray();

        DB::transaction(function () use ($request, $session, $classStudentIds) {
            foreach ($request->attendance as $studentId => $data) {
                // Security: only allow students belonging to this class
                if (! in_array((int) $studentId, $classStudentIds)) {
                    continue;
                }
                StudentAttendance::updateOrCreate(
                    [
                        'attendance_session_id' => $session->id,
                        'student_id' => (int) $studentId,
                    ],
                    [
                        'attendance_status_id' => $data['attendance_status_id'],
                        'remarks' => $data['remarks'] ?? null,
                    ]
                );
            }
        });

        // Trigger Telegram attendance notification if enabled
        try {
            $telegram = app(TelegramNotificationService::class);
            if ($telegram->isEnabled()) {
                $session->load(['school_class.grade', 'student_attendances.attendance_status']);
                $className = $session->school_class?->name ?? 'Class';
                $presentCount = 0;
                $absentCount = 0;
                $lateCount = 0;
                $excusedCount = 0;
                foreach ($session->student_attendances as $sa) {
                    $code = strtoupper($sa->attendance_status?->code ?? '');
                    if ($code === 'P') {
                        $presentCount++;
                    } elseif ($code === 'A') {
                        $absentCount++;
                    } elseif ($code === 'L') {
                        $lateCount++;
                    } else {
                        $excusedCount++;
                    }
                }
                $telegram->sendAttendanceAlert($className, [
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'late' => $lateCount,
                    'excused' => $excusedCount,
                ], $session->session_date ? Carbon::parse($session->session_date)->format('d-M-Y') : null);
            }
        } catch (\Throwable $e) {
            Log::warning('Telegram Attendance Alert error: '.$e->getMessage());
        }

        // Note: session stays OPEN after saving — admin closes explicitly via Close button
        return redirect()
            ->route('attendance-sessions.show', $session->id)
            ->with('success', 'Attendance saved successfully.');
    }

    /* ─────────────────────────────────────────────────────────────
    |  AJAX: Get Teacher Assignments for a Class
    ───────────────────────────────────────────────────────────── */
    public function getClassAssignments(Request $request)
    {
        $classId = $request->input('class_id');

        if (! $classId) {
            return response()->json([]);
        }

        $assignments = TeacherAssignment::with([
            'employee.user',
            'class_subject.subject',
            'academic_year',
        ])->whereHas('class_subject', function ($q) use ($classId) {
            $q->where('class_id', $classId);
        })->get();

        return response()->json($assignments->map(function ($a) {
            return [
                'id' => $a->id,
                'subject_name' => $a->class_subject?->subject?->name ?? '—',
                'teacher_name' => $a->employee?->user?->name ?? '—',
                'academic_year' => $a->academic_year?->name ?? '—',
            ];
        }));
    }
}
