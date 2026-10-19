<?php

namespace App\Http\Controllers\Examination;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Academics\Subject;
use App\Models\Academics\TeacherAssignment;
use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolTerm;
use App\Models\Employees\Employee;
use App\Models\Examination\Exam;
use App\Models\Examination\ExamSchedule;
use App\Models\Examination\ExamSubject;
use App\Models\Examination\ExamType;
use App\Models\Examination\GradeScale;
use App\Models\Examination\StudentGrade;
use Illuminate\Http\Request;

class ExaminationsController extends Controller
{
    /**
     * Unified Examinations Management page.
     * Loads data only for the active tab and subtab.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
        $canManageSettings = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || $user->canAccess('settings'));
        $canManageExams = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || (! $isTeacher && $user->canAccess('exams.create')));

        $allowedTabs = ['exams', 'schedules', 'subjects', 'results'];
        if ($canManageSettings) {
            $allowedTabs[] = 'settings';
        }

        $activeTab = $request->input('tab', $isTeacher ? 'results' : 'exams');
        if (! in_array($activeTab, $allowedTabs)) {
            $activeTab = $isTeacher ? 'results' : 'exams';
        }

        // Prevent unauthorized access to settings tab
        if ($request->input('tab') === 'settings' && ! $canManageSettings) {
            return redirect()->route('examinations.index', ['tab' => $isTeacher ? 'results' : 'exams'])
                ->with('error', 'អ្នកមិនមានសិទ្ធិចូលកាន់ការកំណត់ប្រឡងទេ។');
        }

        // Subtab resolution
        $subtab = null;
        if ($activeTab === 'results') {
            $allowedSubtabs = ['results', 'grades'];
            $subtab = $request->input('subtab', 'results');
            if (! in_array($subtab, $allowedSubtabs)) {
                $subtab = 'results';
            }
        } elseif ($activeTab === 'settings' && $canManageSettings) {
            $allowedSubtabs = ['grade-scales', 'exam-types'];
            $subtab = $request->input('subtab', 'grade-scales');
            if (! in_array($subtab, $allowedSubtabs)) {
                $subtab = 'grade-scales';
            }
        }

        // Tab counts for badges
        $counts = [
            'exams' => Exam::count(),
            'schedules' => ExamSchedule::count(),
            'subjects' => ExamSubject::count(),
            'results' => ExamSubject::has('exam_results')->count(),
            'grades' => StudentGrade::count(),
            'grade-scales' => GradeScale::count(),
            'exam-types' => ExamType::count(),
        ];

        // Shared lookup filters
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $schoolTerms = SchoolTerm::orderBy('name')->get();
        $examTypes = ExamType::orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->orderBy('id')->get();
        $sections = Section::orderBy('name')->get();
        $exams = Exam::with(['academic_year', 'exam_type'])->orderByDesc('id')->get();

        // Data containers initialized to null
        $exams_paginated = null;
        $schedules_paginated = null;
        $examSubjects_paginated = null;
        $resultsExamSubjects_paginated = null;
        $studentGrades_paginated = null;
        $gradeScales = null;
        $examTypes_paginated = null;
        $authContext = null;

        // Load data specific to the active tab
        switch ($activeTab) {
            case 'schedules':
                $query = ExamSchedule::with([
                    'exam.academic_year',
                    'exam.exam_type',
                    'schoolClass.grade',
                    'section',
                ])->withCount('exam_subjects');

                if ($request->filled('exam_id')) {
                    $query->where('exam_id', $request->input('exam_id'));
                }

                if ($request->filled('class_id')) {
                    $query->where('class_id', $request->input('class_id'));
                }

                $schedules_paginated = $query->orderByDesc('id')->paginate(15)->withQueryString();
                break;

            case 'subjects':
                $query = ExamSubject::with([
                    'exam_schedule.exam',
                    'exam_schedule.schoolClass.grade',
                    'exam_schedule.section',
                    'subject',
                ])->withCount('exam_results');

                if ($request->filled('exam_schedule_id')) {
                    $query->where('exam_schedule_id', $request->input('exam_schedule_id'));
                }

                if ($request->filled('subject_id')) {
                    $query->where('subject_id', $request->input('subject_id'));
                }

                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->whereHas('subject', fn ($sq) => $sq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                        ->orWhereHas('exam_schedule.exam', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
                }

                $examSubjects_paginated = $query->orderByDesc('id')->paginate(15)->withQueryString();
                break;

            case 'results':
                if ($subtab === 'grades') {
                    $query = StudentGrade::with([
                        'exam.academic_year',
                        'exam.exam_type',
                        'student.user',
                        'student.schoolClass.grade',
                        'student.schoolClass.section',
                    ]);

                    if ($request->filled('exam_id')) {
                        $query->where('exam_id', $request->input('exam_id'));
                    }

                    if ($request->filled('class_id')) {
                        $classId = $request->input('class_id');
                        $query->whereHas('student', fn ($sq) => $sq->where('school_class_id', $classId));
                    }

                    if ($request->filled('result_status')) {
                        $query->where('result_status', $request->input('result_status'));
                    }

                    if ($request->filled('search')) {
                        $search = $request->input('search');
                        $query->whereHas('student.user', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('student', fn ($sq) => $sq->where('admission_number', 'like', "%{$search}%"));
                    }

                    $studentGrades_paginated = $query->orderByDesc('id')->paginate(20)->withQueryString();
                } else {
                    // subtab === 'results'
                    $user = auth()->user();
                    $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
                    $employee = null;
                    if ($isTeacher) {
                        $employee = Employee::where('user_id', $user->id)->first();
                    }

                    $authContext = [
                        'isTeacher' => (bool) $isTeacher,
                        'employee' => $employee,
                    ];

                    $query = ExamSubject::with([
                        'exam_schedule.exam.academic_year',
                        'exam_schedule.schoolClass.grade',
                        'exam_schedule.section',
                        'subject',
                    ])->withCount('exam_results');

                    // Teacher scoping
                    if ($authContext['isTeacher'] && $employee) {
                        $authorizedPairs = TeacherAssignment::where('employee_id', $employee->id)
                            ->with('class_subject')
                            ->get()
                            ->map(fn ($a) => [
                                'class_id' => $a->class_subject?->class_id ?? $a->class_id,
                                'subject_id' => $a->class_subject?->subject_id,
                            ])->filter(fn ($p) => $p['class_id'] && $p['subject_id']);

                        if ($authorizedPairs->isNotEmpty()) {
                            $query->where(function ($q) use ($authorizedPairs) {
                                foreach ($authorizedPairs as $pair) {
                                    $q->orWhere(function ($subQ) use ($pair) {
                                        $subQ->where('subject_id', $pair['subject_id'])
                                            ->whereHas('exam_schedule', fn ($sq) => $sq->where('class_id', $pair['class_id']));
                                    });
                                }
                            });
                        }
                    }

                    if ($request->filled('exam_id')) {
                        $query->whereHas('exam_schedule', fn ($q) => $q->where('exam_id', $request->input('exam_id')));
                    }

                    if ($request->filled('class_id')) {
                        $query->whereHas('exam_schedule', fn ($q) => $q->where('class_id', $request->input('class_id')));
                    }

                    if ($request->filled('search')) {
                        $search = $request->input('search');
                        $query->whereHas('subject', fn ($sq) => $sq->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('exam_schedule.exam', fn ($sq) => $sq->where('name', 'like', "%{$search}%"));
                    }

                    $resultsExamSubjects_paginated = $query->orderByDesc('id')->paginate(15)->withQueryString();
                }
                break;

            case 'settings':
                if ($subtab === 'exam-types') {
                    $examTypes_paginated = ExamType::withCount('exams')
                        ->orderBy('id')
                        ->paginate(15)
                        ->withQueryString();
                } else {
                    // subtab === 'grade-scales'
                    $gradeScales = GradeScale::orderByDesc('min_percentage')->get();
                }
                break;

            default: // 'exams'
                $activeTab = 'exams';
                $query = Exam::with(['exam_type', 'academic_year', 'school_term'])
                    ->withCount(['exam_schedules', 'student_grades']);

                if ($request->filled('academic_year_id')) {
                    $query->where('academic_year_id', $request->input('academic_year_id'));
                }

                if ($request->filled('exam_type_id')) {
                    $query->where('exam_type_id', $request->input('exam_type_id'));
                }

                if ($request->filled('search')) {
                    $term = $request->input('search');
                    $query->where('name', 'like', "%{$term}%");
                }

                $exams_paginated = $query->orderByDesc('id')->paginate(15)->withQueryString();
                break;
        }

        $allSubjects = Subject::orderBy('name')->get();
        $allSchedules = ExamSchedule::with(['exam', 'schoolClass.grade', 'section'])->orderByDesc('id')->get();

        return view('examinations.index', compact(
            'activeTab',
            'subtab',
            'counts',
            'academicYears',
            'schoolTerms',
            'examTypes',
            'classes',
            'sections',
            'exams',
            'allSubjects',
            'allSchedules',
            'exams_paginated',
            'schedules_paginated',
            'examSubjects_paginated',
            'resultsExamSubjects_paginated',
            'studentGrades_paginated',
            'gradeScales',
            'examTypes_paginated',
            'authContext',
            'canManageSettings',
            'canManageExams'
        ));
    }
}
