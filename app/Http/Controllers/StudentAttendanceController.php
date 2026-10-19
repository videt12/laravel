<?php

namespace App\Http\Controllers;

use App\Models\Academics\SchoolClass;
use App\Models\Attendance\AttendanceSession;
use App\Models\Attendance\AttendanceStatus;
use App\Models\Attendance\StudentAttendance;
use App\Models\Core\AcademicYear;
use App\Models\Students\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    /**
     * Monthly Attendance Register.
     *
     * Displays all students in a selected class across all 31 calendar days
     * of the selected month, with P/A/L/Permission totals and attendance %.
     */
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->get();
        $defaultYear = $academicYears->firstWhere('is_active', 1) ?? $academicYears->first();

        $selectedYearId = $request->input('academic_year_id', $defaultYear?->id);
        $selectedClassId = $request->input('class_id', $classes->first()?->id);
        $selectedMonth = $request->input('month', Carbon::today()->format('Y-m'));

        $parsedMonth = Carbon::parse($selectedMonth.'-01');
        $daysInMonth = $parsedMonth->daysInMonth;

        // Build full 01–31 array of Carbon dates (ALL days, not just weekdays)
        $monthDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $monthDates[] = $parsedMonth->copy()->addDays($d - 1);
        }

        // Fetch students in this class
        $students = Student::with(['user', 'profile'])
            ->where('school_class_id', $selectedClassId)
            ->get()
            ->sortBy(fn ($s) => $s->user?->name ?? '');

        // Fetch all attendance sessions for this class + month
        $sessions = AttendanceSession::where('class_id', $selectedClassId)
            ->whereYear('attendance_date', $parsedMonth->year)
            ->whereMonth('attendance_date', $parsedMonth->month)
            ->get()
            ->keyBy('id');

        // Fetch all student attendances for these sessions
        $studentIds = $students->pluck('id');
        $sessionIds = $sessions->pluck('id');

        $attendances = StudentAttendance::with(['attendance_status', 'attendance_session'])
            ->whereIn('attendance_session_id', $sessionIds)
            ->whereIn('student_id', $studentIds)
            ->get();

        // Build matrix: student_id → date_string → attendance_status_code
        // For period-based: we just show the "best" status of the day
        // (P wins > L > Perm > A — if any session that day is P, the cell is P)
        // Actually per the spec: show each session's status. For simplicity per column = date,
        // we aggregate by day: if multiple periods exist, show the most-attended status.

        $matrix = [];       // [student_id][date_str] => code
        $studentStats = []; // [student_id] => [P=>n, A=>n, L=>n, Perm=>n]

        foreach ($students as $student) {
            $matrix[$student->id] = [];
            $studentStats[$student->id] = ['P' => 0, 'A' => 0, 'L' => 0, 'Perm' => 0, 'total' => 0];
        }

        // Group attendances by student → date → statuses
        $byStudentDate = [];
        foreach ($attendances as $att) {
            $dateStr = $att->attendance_session->attendance_date->format('Y-m-d');
            $studentId = $att->student_id;
            $code = strtoupper($att->attendance_status?->code ?? '');
            $byStudentDate[$studentId][$dateStr][] = $code;
        }

        // Priority: P > L > Perm > A > (blank)
        $priority = ['P' => 4, 'L' => 3, 'PERM' => 2, 'EA' => 2, 'OL' => 2, 'SL' => 2, 'A' => 1];

        foreach ($students as $student) {
            $sid = $student->id;
            foreach ($monthDates as $date) {
                $dateStr = $date->format('Y-m-d');
                if (empty($byStudentDate[$sid][$dateStr])) {
                    $matrix[$sid][$dateStr] = null;

                    continue;
                }
                // Pick highest-priority code for that day
                $codes = $byStudentDate[$sid][$dateStr];
                $bestCode = $codes[0];
                $bestScore = $priority[$bestCode] ?? 0;
                foreach ($codes as $c) {
                    $score = $priority[$c] ?? 0;
                    if ($score > $bestScore) {
                        $bestCode = $c;
                        $bestScore = $score;
                    }
                }
                $matrix[$sid][$dateStr] = $bestCode;
            }

            // Calculate student stats from matrix
            foreach ($matrix[$sid] as $code) {
                if ($code === null) {
                    continue;
                }
                $studentStats[$sid]['total']++;
                if ($code === 'P') {
                    $studentStats[$sid]['P']++;
                } elseif ($code === 'A') {
                    $studentStats[$sid]['A']++;
                } elseif ($code === 'L') {
                    $studentStats[$sid]['L']++;
                } else {
                    $studentStats[$sid]['Perm']++;
                }
            }
        }

        // Class-level stats
        $classStats = [
            'total_students' => $students->count(),
            'P' => 0, 'A' => 0, 'L' => 0, 'Perm' => 0, 'total' => 0,
        ];
        foreach ($studentStats as $stats) {
            $classStats['P'] += $stats['P'];
            $classStats['A'] += $stats['A'];
            $classStats['L'] += $stats['L'];
            $classStats['Perm'] += $stats['Perm'];
            $classStats['total'] += $stats['total'];
        }

        // Sessions count (unique attendance sessions in this month for this class)
        $sessionCount = $sessions->count();

        // Days with at least one session (attendance days)
        $attendanceDays = $sessions->pluck('attendance_date')
            ->map(fn ($d) => $d->format('Y-m-d'))
            ->unique()
            ->count();

        return view('students.attendance.index', compact(
            'academicYears', 'classes', 'defaultYear',
            'selectedYearId', 'selectedClassId', 'selectedMonth',
            'parsedMonth', 'monthDates', 'daysInMonth',
            'students', 'matrix', 'studentStats', 'classStats',
            'sessionCount', 'attendanceDays'
        ));
    }

    /**
     * Named alias for monthly register (used by dedicated route).
     */
    public function monthlyRegister(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Show individual student attendance record.
     */
    public function show($id)
    {
        $attendance = StudentAttendance::with([
            'student.user',
            'attendance_session.schoolClass.grade',
            'attendance_session.schoolClass.section',
            'attendance_status',
        ])->findOrFail($id);

        return view('students.attendance.show', compact('attendance'));
    }

    /**
     * Edit individual student attendance.
     */
    public function edit($id)
    {
        $attendance = StudentAttendance::with(['student', 'attendance_session'])->findOrFail($id);
        $statuses = AttendanceStatus::all();

        return view('students.attendance.edit', compact('attendance', 'statuses'));
    }

    /**
     * Update individual student attendance.
     */
    public function update(Request $request, $id)
    {
        $attendance = StudentAttendance::findOrFail($id);

        $validated = $request->validate([
            'attendance_status_id' => 'required|exists:attendance_statuses,id',
            'remarks' => 'nullable|string|max:255',
        ]);

        $attendance->update([
            'attendance_status_id' => $validated['attendance_status_id'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()
            ->route('student-attendance.index')
            ->with('success', 'Student attendance updated successfully.');
    }

    /**
     * Delete individual student attendance record.
     */
    public function destroy($id)
    {
        $attendance = StudentAttendance::findOrFail($id);
        $attendance->delete();

        return redirect()
            ->route('student-attendance.index')
            ->with('success', 'Attendance record deleted successfully.');
    }

    // create() and store() removed — student attendance is now only entered
    // through the attendance session take-attendance form.
}
