<?php

namespace App\Http\Controllers;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\TeacherAssignment;
use App\Models\Attendance\AttendanceSession;
use App\Models\Attendance\AttendanceStatus;
use App\Models\Attendance\StudentAttendance;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use App\Models\Employees\Employee;
use App\Models\Students\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /* ──────────────────────────────────────────────────────────────
    |  INDEX — Unified Attendance Register Form
    |
    |  GET /attendance?academic_year_id=&class_id=&teacher_assignment_id=&month=&period=
    ─────────────────────────────────────────────────────────────── */
    public function index(Request $request)
    {
        // Detect read-only mode (monthly register route)
        $readOnly = $request->routeIs('attendance.monthly-register');

        // Resolve authenticated user authorization context
        $authContext = $this->getAuthContext();
        $isTeacher = $authContext['isTeacher'];
        $isAdmin = $authContext['isAdmin'];

        /* ── Academic Years ──────────────────────────────────────── */
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();
        $defaultYear = $academicYears->firstWhere('is_active', 1) ?? $academicYears->first();
        $selectedYearId = $request->input('academic_year_id', $defaultYear?->id);

        /* ── Classes (Filtered by teacher authorization and academic year) ─ */
        $classesBaseQuery = SchoolClass::with(['grade', 'section'])
            ->orderBy('id');

        if ($isTeacher) {
            $classesBaseQuery->whereIn('id', $authContext['authorizedClassIds']);
        }

        $allClasses = $classesBaseQuery->get();

        if ($selectedYearId) {
            $classes = $allClasses->where('academic_year_id', (int) $selectedYearId)->values();
        } else {
            $classes = $allClasses;
        }

        /* ── Selected Class with Server-Side Enforcement ─────────── */
        $requestedClassId = $request->input('class_id');
        if ($isTeacher) {
            if ($requestedClassId) {
                // Reject unauthorized class access
                if (! $authContext['authorizedClassIds']->contains((int) $requestedClassId)) {
                    abort(403, 'Unauthorized access: You are not assigned to teach this class.');
                }
                $selectedClassId = (int) $requestedClassId;
            } else {
                // Default to teacher's first authorized class in this academic year
                $selectedClassId = $classes->first()?->id ?? $authContext['authorizedClassIds']->first();
            }
        } else {
            $selectedClassId = $requestedClassId ? (int) $requestedClassId : null;
        }

        // If selected class does not belong to the selected academic year, reset it
        if ($selectedClassId && $selectedYearId && ! $classes->pluck('id')->contains($selectedClassId)) {
            $selectedClassId = null;
        }

        /* ── Teacher Assignments for selected class ─────────────── */
        $teacherAssignments = collect();
        $selectedAssignment = null;
        $selectedClass = null;
        $requestedAssignmentId = $request->input('teacher_assignment_id');

        if ($selectedClassId) {
            $selectedClass = SchoolClass::with(['grade', 'section'])->find($selectedClassId);

            if ($isTeacher) {
                $teacherAssignments = $authContext['authorizedAssignments']
                    ->filter(function ($a) use ($selectedClassId, $selectedYearId) {
                        $matchesClass = (int) ($a->class_id ?? $a->class_subject?->class_id) === (int) $selectedClassId;
                        $matchesYear = ! $selectedYearId || (int) $a->academic_year_id === (int) $selectedYearId;

                        return $matchesClass && $matchesYear;
                    })->values();

                if ($requestedAssignmentId) {
                    if (! $teacherAssignments->pluck('id')->contains((int) $requestedAssignmentId)) {
                        abort(403, 'Unauthorized access: You are not assigned to this teaching assignment.');
                    }
                    $selectedAssignmentId = (int) $requestedAssignmentId;
                    $selectedAssignment = $teacherAssignments->firstWhere('id', $selectedAssignmentId);
                } else {
                    // Default to first assignment if only one or available
                    $selectedAssignment = $teacherAssignments->first();
                    $selectedAssignmentId = $selectedAssignment?->id;
                }
            } else {
                // Admin access: can view any assignment or overall class attendance
                $teacherAssignments = TeacherAssignment::with([
                    'employee.user',
                    'school_class.grade',
                    'school_class.section',
                    'class_subject.subject',
                    'class_subject.class.grade',
                    'class_subject.class.section',
                    'academic_year',
                ])
                    ->where(function ($q) use ($selectedClassId) {
                        $q->where('class_id', $selectedClassId)
                            ->orWhereHas('class_subject', fn ($sq) => $sq->where('class_id', $selectedClassId));
                    })
                    ->when($selectedYearId, fn ($q) => $q->where('academic_year_id', $selectedYearId))
                    ->get();

                if ($requestedAssignmentId) {
                    $selectedAssignmentId = (int) $requestedAssignmentId;
                    $selectedAssignment = $teacherAssignments->firstWhere('id', $selectedAssignmentId);
                } else {
                    $selectedAssignmentId = null;
                }
            }
        } else {
            $selectedAssignmentId = null;
        }

        /* ── Date & Period Filters ───────────────────────────────── */
        $selectedMonth = $request->input('month', Carbon::today()->format('Y-m'));
        $selectedPeriod = max(1, (int) $request->input('period', 1));

        /* ── Attendance Statuses ─────────────────────────────────── */
        $statuses = AttendanceStatus::orderBy('id')->get();
        $statusMeta = $statuses->map(fn ($s) => array_merge(
            ['id' => $s->id, 'code' => strtoupper($s->code ?? ''), 'name' => $s->name],
            $this->resolveStatusMeta($s->code ?? '')
        ))->values()->toArray();

        /* ── Register matrix (students × dates) ────────────────── */
        $students = collect();
        $monthDates = [];
        $matrix = [];
        $studentStats = [];
        $classStats = ['total_students' => 0, 'P' => 0, 'A' => 0, 'L' => 0, 'Perm' => 0, 'total' => 0];
        $sessionByDate = collect();
        $hasSession = [];
        $parsedMonth = null;
        $sessionCount = 0;
        $attendanceDays = 0;

        if ($selectedClassId && $selectedMonth) {
            $parsedMonth = Carbon::parse($selectedMonth.'-01');
            $daysInMonth = $parsedMonth->daysInMonth;

            /* Build full array of Carbon dates for the month */
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $monthDates[] = $parsedMonth->copy()->addDays($d - 1);
            }

            /* Load current active students in this class (Student Management is MASTER source) */
            $students = Student::with(['user', 'profile'])
                ->where('school_class_id', $selectedClassId)
                ->active()
                ->get();

            /* Load attendance sessions for this class + month + period (+ assignment + year) */
            $sessionsQuery = AttendanceSession::where('class_id', $selectedClassId)
                ->whereYear('attendance_date', $parsedMonth->year)
                ->whereMonth('attendance_date', $parsedMonth->month)
                ->where('period', $selectedPeriod);

            if ($selectedYearId) {
                $sessionsQuery->where('academic_year_id', $selectedYearId);
            }

            if ($selectedAssignmentId) {
                $sessionsQuery->where('teacher_assignment_id', $selectedAssignmentId);
            } else {
                $sessionsQuery->whereNull('teacher_assignment_id');
            }

            $sessionList = $sessionsQuery->get();
            $sessionById = $sessionList->keyBy('id');
            $sessionByDate = $sessionList->keyBy(fn ($s) => $s->attendance_date->format('Y-m-d'));
            $sessionCount = $sessionList->count();
            $attendanceDays = $sessionCount; // One session per day for this period & assignment

            foreach ($monthDates as $date) {
                $hasSession[$date->format('Y-m-d')] = $sessionByDate->has($date->format('Y-m-d'));
            }

            /* Load all student attendances for these sessions */
            $sessionIds = $sessionList->pluck('id');

            // Preserve historical attendance: if students have historical attendance in these sessions,
            // ensure they remain displayed on the register for this month even if later moved/promoted.
            if ($sessionIds->isNotEmpty()) {
                $historicalStudentIds = StudentAttendance::whereIn('attendance_session_id', $sessionIds)
                    ->pluck('student_id')
                    ->unique();

                $missingIds = $historicalStudentIds->diff($students->pluck('id'));
                if ($missingIds->isNotEmpty()) {
                    $historicalStudents = Student::with(['user', 'profile'])
                        ->whereIn('id', $missingIds)
                        ->get();
                    $students = $students->merge($historicalStudents);
                }
            }

            // Sort students alphabetically by name
            $students = $students->sortBy(fn ($s) => $s->user?->name ?? '')->values();
            $studentIds = $students->pluck('id');

            $attendances = $sessionIds->isEmpty() || $studentIds->isEmpty() ? collect() :
                StudentAttendance::with('attendance_status')
                    ->whereIn('attendance_session_id', $sessionIds)
                    ->whereIn('student_id', $studentIds)
                    ->get();

            /* Index by [session_id][student_id] for O(1) lookup */
            $attBySessionStudent = [];
            foreach ($attendances as $att) {
                $attBySessionStudent[$att->attendance_session_id][$att->student_id] = $att;
            }

            /* Build matrix: [student_id][date_str] = cell data array | null */
            foreach ($students as $student) {
                $sid = $student->id;
                $matrix[$sid] = [];
                $studentStats[$sid] = ['P' => 0, 'A' => 0, 'L' => 0, 'Perm' => 0, 'total' => 0, 'pct' => 0];

                foreach ($monthDates as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $session = $sessionByDate->get($dateStr);

                    if (! $session) {
                        /* No session for this day */
                        $matrix[$sid][$dateStr] = null;

                        continue;
                    }

                    $att = $attBySessionStudent[$session->id][$sid] ?? null;

                    if (! $att) {
                        /* Session exists but student not yet marked */
                        $matrix[$sid][$dateStr] = [
                            'status_id' => null,
                            'code' => '',
                            'css_class' => '',
                            'symbol' => '',
                            'att_id' => null,
                            'has_session' => true,
                        ];

                        continue;
                    }

                    $code = strtoupper($att->attendance_status?->code ?? '');
                    $meta = $this->resolveStatusMeta($code);

                    $matrix[$sid][$dateStr] = [
                        'status_id' => $att->attendance_status_id,
                        'code' => $code,
                        'css_class' => $meta['css'],
                        'symbol' => $meta['symbol'],
                        'att_id' => $att->id,
                        'has_session' => true,
                    ];

                    /* Accumulate per-student stats */
                    $studentStats[$sid]['total']++;
                    match ($meta['css']) {
                        'present' => $studentStats[$sid]['P']++,
                        'absent' => $studentStats[$sid]['A']++,
                        'late' => $studentStats[$sid]['L']++,
                        default => $studentStats[$sid]['Perm']++,
                    };
                }

                /* Attendance percentage (present / total marked) */
                $total = $studentStats[$sid]['total'];
                $studentStats[$sid]['pct'] = $total > 0
                    ? round(($studentStats[$sid]['P'] / $total) * 100)
                    : 0;
            }

            /* Class-level summary */
            $classStats['total_students'] = $students->count();
            foreach ($studentStats as $stats) {
                $classStats['P'] += $stats['P'];
                $classStats['A'] += $stats['A'];
                $classStats['L'] += $stats['L'];
                $classStats['Perm'] += $stats['Perm'];
                $classStats['total'] += $stats['total'];
            }
        }

        return view('attendance.index', compact(
            'academicYears', 'classes', 'allClasses', 'statuses', 'statusMeta', 'defaultYear',
            'selectedYearId', 'selectedClassId', 'selectedAssignmentId',
            'selectedMonth', 'selectedPeriod',
            'teacherAssignments', 'selectedAssignment', 'selectedClass',
            'students', 'monthDates', 'matrix', 'studentStats', 'classStats',
            'sessionByDate', 'hasSession', 'parsedMonth',
            'sessionCount', 'attendanceDays', 'readOnly',
            'isTeacher', 'isAdmin'
        ));
    }

    /* ──────────────────────────────────────────────────────────────
    |  MONTHLY REGISTER — read-only alias
    ─────────────────────────────────────────────────────────────── */
    public function monthlyRegister(Request $request)
    {
        return $this->index($request);
    }

    /* ──────────────────────────────────────────────────────────────
    |  SAVE — Batch attendance save (POST)
    |
    |  POST /attendance/save
    |  Body: attendance[YYYY-MM-DD][student_id] = status_id
    ─────────────────────────────────────────────────────────────── */
    public function save(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:school_classes,id',
            'period' => 'required|integer|min:1|max:10',
            'month' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'teacher_assignment_id' => 'nullable|exists:teacher_assignments,id',
            'attendance' => 'nullable|array',
        ]);

        $academicYearId = (int) $request->academic_year_id;
        $classId = (int) $request->class_id;
        $period = (int) $request->period;
        $month = $request->month;
        $teacherAssignmentId = $request->filled('teacher_assignment_id')
                                    ? (int) $request->teacher_assignment_id
                                    : null;
        $attendanceData = $request->input('attendance', []);

        /* ── Server-Side Authorization Check ──────────────────────── */
        $authContext = $this->getAuthContext();
        if ($authContext['isTeacher']) {
            // Teacher cannot save attendance for unauthorized class
            if (! $authContext['authorizedClassIds']->contains($classId)) {
                abort(403, 'Unauthorized access: You are not assigned to teach this class.');
            }
            // Teacher must only use authorized teacher assignments
            if ($teacherAssignmentId && ! $authContext['authorizedAssignments']->pluck('id')->contains($teacherAssignmentId)) {
                abort(403, 'Unauthorized access: This teaching assignment does not belong to you.');
            }
        }

        /* ── Validate teacher_assignment belongs to this class (if supplied) ─ */
        if ($teacherAssignmentId) {
            $assignment = TeacherAssignment::with(['class_subject', 'school_class'])->find($teacherAssignmentId);
            $assignmentClassId = $assignment?->class_id ?? $assignment?->class_subject?->class_id;
            if (! $assignment || (int) $assignmentClassId !== $classId) {
                return redirect()->back()->withInput()
                    ->withErrors(['teacher_assignment_id' => 'Teaching assignment does not belong to the selected class.']);
            }
        }

        /* Retrieve the class's section_id for new session records */
        $schoolClass = SchoolClass::find($classId);
        $sectionId = $schoolClass?->section_id;

        /* Security: only process students that belong to this class in Student Management */
        $validStudentIds = Student::where('school_class_id', $classId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->flip() // O(1) lookup
            ->toArray();

        /* Valid status IDs (hash-map) */
        $validStatusIds = AttendanceStatus::pluck('id')
            ->map(fn ($id) => (int) $id)
            ->flip()
            ->toArray();

        $savedSessions = 0;
        $savedAttendances = 0;

        DB::transaction(function () use (
            $academicYearId, $classId, $sectionId, $teacherAssignmentId, $period,
            $attendanceData, $validStudentIds, $validStatusIds,
            &$savedSessions, &$savedAttendances
        ) {
            foreach ($attendanceData as $dateStr => $studentStatuses) {
                /* Validate date format (YYYY-MM-DD) */
                if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
                    continue;
                }

                /* Skip dates where no valid attendance entries exist */
                $hasValidEntry = false;
                foreach ($studentStatuses as $studentId => $statusId) {
                    if (
                        isset($validStudentIds[(int) $studentId]) &&
                        isset($validStatusIds[(int) $statusId])
                    ) {
                        $hasValidEntry = true;
                        break;
                    }
                }
                if (! $hasValidEntry) {
                    continue;
                }

                /* Create or update the AttendanceSession for this date, period, and teacher assignment */
                $session = AttendanceSession::updateOrCreate(
                    [
                        'academic_year_id' => $academicYearId,
                        'class_id' => $classId,
                        'attendance_date' => $dateStr,
                        'period' => $period,
                        'teacher_assignment_id' => $teacherAssignmentId,
                    ],
                    [
                        'section_id' => $sectionId,
                        'recorded_by' => auth()->id() ?? null,
                        'status' => 'open',
                    ]
                );
                $savedSessions++;

                /* Save each student's attendance record (updates existing record without duplicates) */
                foreach ($studentStatuses as $studentId => $statusId) {
                    $studentId = (int) $studentId;
                    $statusId = (int) $statusId;

                    if (! isset($validStudentIds[$studentId])) {
                        continue;
                    }
                    if (! isset($validStatusIds[$statusId])) {
                        continue;
                    }

                    StudentAttendance::updateOrCreate(
                        [
                            'attendance_session_id' => $session->id,
                            'student_id' => $studentId,
                        ],
                        [
                            'attendance_status_id' => $statusId,
                        ]
                    );
                    $savedAttendances++;
                }
            }
        });

        /* Redirect back to the register with the same filters */
        $redirectParams = array_filter([
            'academic_year_id' => $academicYearId,
            'class_id' => $classId,
            'teacher_assignment_id' => $teacherAssignmentId,
            'period' => $period,
            'month' => $month,
        ], fn ($v) => $v !== null && $v !== '' && $v !== 0);

        return redirect()
            ->route('attendance.index', $redirectParams)
            ->with('success', "Attendance saved successfully. ({$savedSessions} sessions, {$savedAttendances} records updated)");
    }

    /* ──────────────────────────────────────────────────────────────
    |  AJAX — Teacher assignments for a class
    |
    |  GET /api/attendance/teacher-assignments?class_id=&academic_year_id=
    ─────────────────────────────────────────────────────────────── */
    public function getTeacherAssignments(Request $request)
    {
        $classId = $request->input('class_id');
        $academicYearId = $request->input('academic_year_id');

        if (! $classId) {
            return response()->json([]);
        }

        $authContext = $this->getAuthContext();

        // If teacher is logged in, only return their own assignments for this class
        if ($authContext['isTeacher']) {
            if (! $authContext['authorizedClassIds']->contains((int) $classId)) {
                return response()->json([]);
            }

            $assignments = $authContext['authorizedAssignments']
                ->filter(function ($a) use ($classId, $academicYearId) {
                    $matchesClass = (int) ($a->class_subject?->class_id) === (int) $classId;
                    $matchesYear = ! $academicYearId || (int) $a->academic_year_id === (int) $academicYearId;

                    return $matchesClass && $matchesYear;
                })->values();

            return response()->json($assignments->map(fn ($a) => [
                'id' => $a->id,
                'subject_name' => $a->class_subject?->subject?->name ?? '—',
                'teacher_name' => $a->employee?->user?->name ?? '—',
                'year' => $a->academic_year?->name ?? '—',
            ])->values());
        }

        // Admin access: all assignments for this class
        $assignments = TeacherAssignment::with([
            'employee.user',
            'class_subject.subject',
            'academic_year',
        ])
            ->whereHas('class_subject', fn ($q) => $q->where('class_id', $classId))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get();

        return response()->json($assignments->map(fn ($a) => [
            'id' => $a->id,
            'subject_name' => $a->class_subject?->subject?->name ?? '—',
            'teacher_name' => $a->employee?->user?->name ?? '—',
            'year' => $a->academic_year?->name ?? '—',
        ])->values());
    }

    /* ──────────────────────────────────────────────────────────────
    |  Helper — Resolve Authenticated User Authorization Context
    ─────────────────────────────────────────────────────────────── */
    private function getAuthContext(): array
    {
        $user = auth()->user();
        $isAdmin = false;
        $isTeacher = false;
        $employee = null;
        $authorizedAssignments = collect();
        $authorizedClassIds = collect();

        if ($user) {
            $adminSlugs = ['admin', 'super_admin', 'super-admin', 'principal', 'superadmin'];
            $isAdmin = $user->roles()
                ->where(function ($q) use ($adminSlugs) {
                    $q->whereIn('slug', $adminSlugs)
                        ->orWhereIn(DB::raw('LOWER(name)'), ['admin', 'super admin', 'principal']);
                })->exists();

            if (! $isAdmin) {
                $employee = $user->employee ?? Employee::where('user_id', $user->id)->first();
                $hasTeacherRole = $user->roles()->where('slug', 'teacher')->exists();

                if ($employee || $hasTeacherRole) {
                    $isTeacher = true;

                    if ($employee) {
                        $authorizedAssignments = TeacherAssignment::with([
                            'employee.user',
                            'school_class.grade',
                            'school_class.section',
                            'class_subject.subject',
                            'class_subject.class.grade',
                            'class_subject.class.section',
                            'academic_year',
                        ])
                            ->where('employee_id', $employee->id)
                            ->get();

                        $authorizedClassIds = $authorizedAssignments
                            ->map(fn ($a) => $a->class_id ?? $a->class_subject?->class_id)
                            ->filter()
                            ->unique()
                            ->values();
                    }
                }
            }
        }

        return [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'isTeacher' => $isTeacher,
            'employee' => $employee,
            'authorizedAssignments' => $authorizedAssignments,
            'authorizedClassIds' => $authorizedClassIds,
        ];
    }

    /* ──────────────────────────────────────────────────────────────
    |  Helper — resolve CSS class and display symbol from status code
    ─────────────────────────────────────────────────────────────── */
    private function resolveStatusMeta(string $code): array
    {
        return match (strtoupper(trim($code))) {
            'P' => ['css' => 'present',    'symbol' => '✓'],
            'A' => ['css' => 'absent',     'symbol' => 'A'],
            'L' => ['css' => 'late',       'symbol' => 'L'],
            'PERM', 'EA', 'OL', 'SL',
            'EX', 'PE' => ['css' => 'permission', 'symbol' => 'P'],
            default => ['css' => 'permission', 'symbol' => strtoupper($code[0] ?? '?')],
        };
    }
}
