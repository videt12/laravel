<?php

namespace App\Http\Controllers;

use App\Models\Academics\ClassSubject;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Subject;
use App\Models\Academics\TeacherAssignment;
use App\Models\Attendance\AttendanceSession;
use App\Models\Attendance\StudentAttendance;
use App\Models\Core\AcademicYear;
use App\Models\Employees\Employee;
use App\Models\Enrollment\Enrollment;
use App\Models\Finance\StudentInvoice;
use App\Models\Finance\StudentPayment;
use App\Models\Students\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the School Management Dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Role resolution (matching established pattern from AttendanceController)
        $adminSlugs = ['admin', 'super_admin', 'super-admin', 'principal', 'superadmin'];
        $isAdmin = false;
        if ($user) {
            $isAdmin = $user->roles()
                ->where(function ($q) use ($adminSlugs) {
                    $q->whereIn('slug', $adminSlugs)
                        ->orWhereIn(DB::raw('LOWER(name)'), ['admin', 'super admin', 'principal']);
                })->exists();
        }

        $employee = null;
        $hasTeacherRole = false;
        if ($user && ! $isAdmin) {
            $employee = $user->employee ?? Employee::where('user_id', $user->id)->first();
            $hasTeacherRole = $user->roles()->where('slug', 'teacher')->exists();
        }
        $isTeacher = ! $isAdmin && ($employee !== null || $hasTeacherRole);

        // 2. Active Academic Year
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->orderByDesc('id')->get();
        $activeYear = $academicYears->firstWhere('is_active', 1) ?? $academicYears->first();

        // 3. Current Date
        $today = Carbon::today();

        // ── TEACHER DASHBOARD DATA ──────────────────────────────────────────
        if ($isTeacher) {
            $teacherAssignments = collect();
            if ($employee) {
                $teacherAssignments = TeacherAssignment::with([
                    'employee.user',
                    'class_subject.subject',
                    'class_subject.class.grade',
                    'class_subject.class.section',
                    'academic_year',
                ])
                    ->where('employee_id', $employee->id)
                    ->get();
            }

            $teacherClassIds = $teacherAssignments
                ->map(fn ($a) => $a->class_subject?->class_id)
                ->filter()
                ->unique()
                ->values();

            $teacherSubjectIds = $teacherAssignments
                ->map(fn ($a) => $a->class_subject?->subject_id)
                ->filter()
                ->unique()
                ->values();

            $myClassesCount = $teacherClassIds->count();
            $mySubjectsCount = $teacherSubjectIds->count();
            $myAssignmentsCount = $teacherAssignments->count();

            // Students in teacher's assigned classes using existing active scope
            $myStudentsCount = $teacherClassIds->isNotEmpty()
                ? Student::active()->whereIn('school_class_id', $teacherClassIds)->count()
                : 0;

            // Today's attendance scoped to teacher's authorized classes/assignments
            $teacherTodaySessions = AttendanceSession::whereDate('attendance_date', $today)
                ->where(function ($q) use ($employee, $teacherClassIds) {
                    if ($employee) {
                        $q->whereHas('teacherAssignment', fn ($tq) => $tq->where('employee_id', $employee->id))
                            ->orWhereIn('class_id', $teacherClassIds);
                    } else {
                        $q->whereIn('class_id', $teacherClassIds);
                    }
                })
                ->pluck('id');

            $todaySessionsCount = $teacherTodaySessions->count();

            $teacherAttendances = StudentAttendance::whereIn('attendance_session_id', $teacherTodaySessions)
                ->with('attendance_status')
                ->get();

            $todayTotalAttendance = $teacherAttendances->count();

            $todayPresent = $teacherAttendances->filter(function ($a) {
                return strtoupper(trim($a->attendance_status?->code ?? '')) === 'P';
            })->count();

            $todayAbsent = $teacherAttendances->filter(function ($a) {
                return strtoupper(trim($a->attendance_status?->code ?? '')) === 'A';
            })->count();

            $todayLate = $teacherAttendances->filter(function ($a) {
                return strtoupper(trim($a->attendance_status?->code ?? '')) === 'L';
            })->count();

            $todayPermission = $teacherAttendances->filter(function ($a) {
                $code = strtoupper(trim($a->attendance_status?->code ?? ''));

                return in_array($code, ['PERM', 'EA', 'OL', 'SL', 'HD', 'H']);
            })->count();

            $attendanceRate = $todayTotalAttendance > 0
                ? round(($todayPresent / $todayTotalAttendance) * 100, 1)
                : 0.0;

            return view('dashboard.index', [
                'role' => 'teacher',
                'isAdmin' => false,
                'isTeacher' => true,
                'employee' => $employee,
                'activeYear' => $activeYear,
                'today' => $today,
                'myClassesCount' => $myClassesCount,
                'mySubjectsCount' => $mySubjectsCount,
                'myAssignmentsCount' => $myAssignmentsCount,
                'myStudentsCount' => $myStudentsCount,
                'todaySessionsCount' => $todaySessionsCount,
                'todayTotalAttendance' => $todayTotalAttendance,
                'todayPresent' => $todayPresent,
                'todayAbsent' => $todayAbsent,
                'todayLate' => $todayLate,
                'todayPermission' => $todayPermission,
                'attendanceRate' => $attendanceRate,
                'teacherAssignments' => $teacherAssignments,
            ]);
        }

        // ── ADMIN / MANAGEMENT DASHBOARD DATA ───────────────────────────────
        // 1. Students
        $totalStudents = Student::count();
        $activeStudents = Student::active()->count();
        $inactiveStudents = Student::where(function ($q) {
            $q->whereHas('studentStatus', function ($sq) {
                $sq->whereRaw('LOWER(name) != ?', ['active']);
            })->orWhere(function ($sub) {
                $sub->whereNull('student_status_id')
                    ->whereHas('user', function ($uq) {
                        $uq->where('status', '!=', 'active');
                    });
            });
        })->count();

        // 1. Students & Enrollment Status (Accurate New vs Returning based on enrollment history)
        $targetYearId = $activeYear?->id;
        $newStudentsCount = 0;
        $returningStudentsCount = 0;

        if ($targetYearId) {
            $yearStudents = Student::where('academic_year_id', $targetYearId)
                ->orWhereHas('enrollments', fn ($eq) => $eq->where('academic_year_id', $targetYearId));

            $newStudentsCount = (clone $yearStudents)->whereNewStudent($targetYearId)->count();
            $returningStudentsCount = (clone $yearStudents)->whereReturningStudent($targetYearId)->count();
        }

        // Student Health: Students without current enrollment and without class
        $studentsWithoutEnrollmentCount = Student::active()
            ->whereDoesntHave('enrollments', function ($eq) use ($targetYearId) {
                if ($targetYearId) {
                    $eq->where('academic_year_id', $targetYearId);
                }
            })
            ->count();

        $studentsWithoutClassCount = Student::active()
            ->whereNull('school_class_id')
            ->count();

        // Student Financial Health (Distinct Students, not invoices)
        $totalInvoiced = (float) StudentInvoice::where('status', '!=', 'cancelled')->sum('net_amount');
        $totalCollected = (float) StudentPayment::sum('amount');
        $totalOutstanding = max(0.0, round($totalInvoiced - $totalCollected, 2));

        $studentFinanceStats = Student::query()
            ->whereHas('student_invoices', fn ($q) => $q->where('status', '!=', 'cancelled'))
            ->withSum(['student_invoices as invoiced_sum' => fn ($q) => $q->where('status', '!=', 'cancelled')], 'net_amount')
            ->withSum(['student_payments as paid_sum'], 'amount')
            ->withExists(['student_invoices as has_overdue' => fn ($q) => $q->where('status', '!=', 'cancelled')->where('status', '!=', 'paid')->whereDate('due_date', '<', $today)])
            ->get(['id']);

        $paidStudentsCount = 0;
        $partialStudentsCount = 0;
        $unpaidStudentsCount = 0;
        $overdueStudentsCount = 0;

        foreach ($studentFinanceStats as $st) {
            $inv = (float) ($st->invoiced_sum ?? 0);
            $pd = (float) ($st->paid_sum ?? 0);

            if ($st->has_overdue) {
                $overdueStudentsCount++;
            }

            if ($inv > 0 && $pd >= $inv) {
                $paidStudentsCount++;
            } elseif ($pd > 0) {
                $partialStudentsCount++;
            } else {
                $unpaidStudentsCount++;
            }
        }

        // 2. Academics
        $totalClasses = SchoolClass::count();
        $totalSubjects = Subject::count();
        $totalClassSubjects = ClassSubject::count();
        $totalTeacherAssignments = TeacherAssignment::count();

        // 3. Staff
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::active()->count();

        // 4. Enrollments
        $totalEnrollments = Enrollment::count();
        $activeEnrollments = Enrollment::where('status', 'Active')->count();

        // 5. Daily Operations (Today)
        $todayPaymentsCount = StudentPayment::whereDate('payment_date', $today)->count();
        $todayPaymentsSum = (float) StudentPayment::whereDate('payment_date', $today)->sum('amount');
        $todayEnrollmentsCount = Enrollment::whereDate('enrollment_date', $today)->count();

        // 5. Today's Attendance Summary
        $todaySessionIds = AttendanceSession::whereDate('attendance_date', $today)->pluck('id');
        $todaySessionsCount = $todaySessionIds->count();

        $todayAttendances = StudentAttendance::whereIn('attendance_session_id', $todaySessionIds)
            ->with('attendance_status')
            ->get();

        $todayTotalAttendance = $todayAttendances->count();

        $todayPresent = $todayAttendances->filter(function ($a) {
            return strtoupper(trim($a->attendance_status?->code ?? '')) === 'P';
        })->count();

        $todayAbsent = $todayAttendances->filter(function ($a) {
            return strtoupper(trim($a->attendance_status?->code ?? '')) === 'A';
        })->count();

        $todayLate = $todayAttendances->filter(function ($a) {
            return strtoupper(trim($a->attendance_status?->code ?? '')) === 'L';
        })->count();

        $todayPermission = $todayAttendances->filter(function ($a) {
            $code = strtoupper(trim($a->attendance_status?->code ?? ''));

            return in_array($code, ['PERM', 'EA', 'OL', 'SL', 'HD', 'H']);
        })->count();

        $attendanceRate = $todayTotalAttendance > 0
            ? round(($todayPresent / $todayTotalAttendance) * 100, 1)
            : 0.0;

        // 6. Recent Data (compact 5 items)
        $recentStudents = Student::with(['user', 'schoolClass.grade', 'schoolClass.section', 'studentStatus'])
            ->latest()
            ->take(5)
            ->get();

        $recentEnrollments = Enrollment::with([
            'student.user',
            'academic_year',
            'enrollmentDetail.class.grade',
            'enrollmentDetail.class.section',
        ])
            ->latest()
            ->take(5)
            ->get();

        $recentTeacherAssignments = TeacherAssignment::with([
            'employee.user',
            'class_subject.subject',
            'class_subject.class.grade',
            'class_subject.class.section',
            'academic_year',
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', [
            'role' => 'admin',
            'isAdmin' => true,
            'isTeacher' => false,
            'activeYear' => $activeYear,
            'today' => $today,
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'inactiveStudents' => $inactiveStudents,
            'newStudentsThisYear' => $newStudentsCount,
            'newStudentsCount' => $newStudentsCount,
            'returningStudentsCount' => $returningStudentsCount,
            'paidStudentsCount' => $paidStudentsCount,
            'partialStudentsCount' => $partialStudentsCount,
            'unpaidStudentsCount' => $unpaidStudentsCount,
            'overdueStudentsCount' => $overdueStudentsCount,
            'totalInvoiced' => $totalInvoiced,
            'totalCollected' => $totalCollected,
            'totalOutstanding' => $totalOutstanding,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalClassSubjects' => $totalClassSubjects,
            'totalTeacherAssignments' => $totalTeacherAssignments,
            'totalEmployees' => $totalEmployees,
            'activeEmployees' => $activeEmployees,
            'totalEnrollments' => $totalEnrollments,
            'activeEnrollments' => $activeEnrollments,
            'todaySessionsCount' => $todaySessionsCount,
            'todayTotalAttendance' => $todayTotalAttendance,
            'todayPresent' => $todayPresent,
            'todayAbsent' => $todayAbsent,
            'todayLate' => $todayLate,
            'todayPermission' => $todayPermission,
            'attendanceRate' => $attendanceRate,
            'studentsWithoutEnrollmentCount' => $studentsWithoutEnrollmentCount,
            'studentsWithoutClassCount' => $studentsWithoutClassCount,
            'todayPaymentsCount' => $todayPaymentsCount,
            'todayPaymentsSum' => $todayPaymentsSum,
            'todayEnrollmentsCount' => $todayEnrollmentsCount,
            'recentStudents' => $recentStudents,
            'recentEnrollments' => $recentEnrollments,
            'recentTeacherAssignments' => $recentTeacherAssignments,
        ]);
    }
}
