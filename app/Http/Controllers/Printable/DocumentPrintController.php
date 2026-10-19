<?php

namespace App\Http\Controllers\Printable;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Core\School;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeCard;
use App\Models\Examination\Exam;
use App\Models\Examination\ExamResult;
use App\Models\Examination\ExamSubject;
use App\Models\Examination\StudentGrade;
use App\Models\Students\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentPrintController extends Controller
{
    /**
     * Display a single student's official Report Card / Transcript for a specific Exam.
     */
    public function studentReportCard(Request $request, Exam $exam, Student $student)
    {
        $school = School::first();
        $student->load(['studentProfile', 'schoolClass.grade', 'section', 'academicYear']);
        $exam->load(['exam_type', 'academic_year']);

        // 1. Get Subject Results for this Exam and Student
        $examSubjectIds = ExamSubject::whereHas('exam_schedule', function ($q) use ($exam) {
            $q->where('exam_id', $exam->id);
        })->pluck('id');

        $examResults = ExamResult::whereIn('exam_subject_id', $examSubjectIds)
            ->where('student_id', $student->id)
            ->with(['exam_subject.subject'])
            ->get();

        // 2. Get Student Summary Grade
        $studentGrade = StudentGrade::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        // Recalculate on-the-fly if not present yet
        if (! $studentGrade && $examResults->isNotEmpty()) {
            $studentGrade = StudentGrade::recalculateForStudent($exam->id, $student->id);
        }

        // 3. Compute Class Ranking
        $classStudentIds = Student::where('school_class_id', $student->school_class_id)
            ->where('student_status_id', 1)
            ->pluck('id');

        $rankings = StudentGrade::where('exam_id', $exam->id)
            ->whereIn('student_id', $classStudentIds)
            ->orderByDesc('total_marks')
            ->pluck('student_id')
            ->toArray();

        $rankIndex = array_search($student->id, $rankings);
        $rank = ($rankIndex !== false) ? ($rankIndex + 1) : null;
        $totalInClass = count($rankings) ?: $classStudentIds->count();

        // 4. Attendance Summary for the period (if available)
        $attendanceStats = DB::table('student_attendances')
            ->where('student_id', $student->id)
            ->selectRaw("
                SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance_status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN attendance_status = 'late' THEN 1 ELSE 0 END) as late_count,
                SUM(CASE WHEN attendance_status = 'excused' THEN 1 ELSE 0 END) as excused_count
            ")
            ->first();

        return view('printable.report-card', compact(
            'school',
            'exam',
            'student',
            'examResults',
            'studentGrade',
            'rank',
            'totalInClass',
            'attendanceStats'
        ));
    }

    /**
     * Display printable report cards for all students in an entire class (Batch Print).
     */
    public function classReportCards(Request $request, Exam $exam, SchoolClass $schoolClass)
    {
        $school = School::first();
        $exam->load(['exam_type', 'academic_year']);
        $schoolClass->load(['grade']);

        $students = Student::where('school_class_id', $schoolClass->id)
            ->where('student_status_id', 1)
            ->with(['studentProfile', 'schoolClass.grade', 'section', 'academicYear'])
            ->get();

        $examSubjectIds = ExamSubject::whereHas('exam_schedule', function ($q) use ($exam) {
            $q->where('exam_id', $exam->id);
        })->pluck('id');

        $allResults = ExamResult::whereIn('exam_subject_id', $examSubjectIds)
            ->with(['exam_subject.subject'])
            ->get()
            ->groupBy('student_id');

        $grades = StudentGrade::where('exam_id', $exam->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        // Calculate rankings
        $rankings = $grades->sortByDesc('total_marks')->keys()->toArray();
        $totalInClass = count($rankings) ?: $students->count();

        $reportCardsData = [];
        foreach ($students as $student) {
            $sGrade = $grades->get($student->id);
            $sResults = $allResults->get($student->id, collect());

            if (! $sGrade && $sResults->isNotEmpty()) {
                $sGrade = StudentGrade::recalculateForStudent($exam->id, $student->id);
            }

            $rankIndex = array_search($student->id, $rankings);
            $rank = ($rankIndex !== false) ? ($rankIndex + 1) : null;

            $reportCardsData[] = [
                'student' => $student,
                'examResults' => $sResults,
                'studentGrade' => $sGrade,
                'rank' => $rank,
                'totalInClass' => $totalInClass,
            ];
        }

        return view('printable.class-report-cards', compact(
            'school',
            'exam',
            'schoolClass',
            'reportCardsData'
        ));
    }

    /**
     * Display a single student's official ID Card badge.
     */
    public function studentIdCard(Request $request, Student $student)
    {
        $school = School::first();
        $student->load(['studentProfile', 'schoolClass.grade', 'section', 'academicYear']);

        return view('printable.student-id-card', compact('school', 'student'));
    }

    /**
     * Display a print sheet of ID Cards for an entire class.
     */
    public function classIdCards(Request $request, SchoolClass $schoolClass)
    {
        $school = School::first();
        $schoolClass->load('grade');
        $students = Student::where('school_class_id', $schoolClass->id)
            ->where('student_status_id', 1)
            ->with(['studentProfile', 'schoolClass.grade', 'section', 'academicYear'])
            ->get();

        return view('printable.class-id-cards', compact('school', 'schoolClass', 'students'));
    }

    /**
     * Display an employee / staff ID Card badge.
     */
    public function employeeIdCard(Request $request, Employee $employee)
    {
        $school = School::first();
        $employee->load(['department', 'position', 'user', 'employeeContact', 'employee_documents']);
        $card = EmployeeCard::getOrCreateActiveFor($employee, auth()->id());

        return view('printable.employee-id-card', compact('school', 'employee', 'card'));
    }

    /**
     * Display official Certificate of Study / Enrollment (លិខិតបញ្ជាក់ការសិក្សា).
     */
    public function studyCertificate(Request $request, Student $student)
    {
        $school = School::first();
        $student->load(['studentProfile', 'schoolClass.grade', 'section', 'academicYear']);

        return view('printable.study-certificate', compact('school', 'student'));
    }
}
