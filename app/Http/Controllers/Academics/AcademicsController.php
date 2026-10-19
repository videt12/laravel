<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\Classroom;
use App\Models\Academics\Grade;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Subject;
use App\Models\Academics\TeacherAssignment;
use App\Models\Core\AcademicYear;
use App\Models\Employees\Employee;
use Illuminate\Http\Request;

class AcademicsController extends Controller
{
    /**
     * Unified Academics management page.
     * Loads data for the active tab only; other tabs get empty placeholders.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        $tabPermissions = [
            'classes' => ['classes.view', 'classes'],
            'subjects' => ['subjects.view', 'subjects'],
            'teacher-assignments' => ['teacher-assignments.view', 'teacher-assignments', 'classes.create', 'classes.edit'],
            'classrooms' => ['classrooms.view', 'classrooms', 'classes.create', 'classes.edit'],
            'academic-years' => ['academic-years.view', 'academic-years', 'academic-years.create'],
        ];

        $allowedTabs = ['classes', 'subjects', 'teacher-assignments', 'classrooms', 'academic-years'];
        $accessibleTabs = array_values(array_filter($allowedTabs, function ($tab) use ($user, $tabPermissions) {
            return $user->canAccess($tabPermissions[$tab] ?? $tab);
        }));

        if (empty($accessibleTabs)) {
            abort(403, 'Unauthorized access: You do not have permission to view academic management.');
        }

        $activeTab = $request->input('tab', $accessibleTabs[0]);
        if ($activeTab === 'class-subjects') {
            return in_array('subjects', $accessibleTabs)
                ? redirect()->route('academics.index', ['tab' => 'subjects'])
                : redirect()->route('academics.index', ['tab' => $accessibleTabs[0]]);
        }
        if (! in_array($activeTab, $accessibleTabs)) {
            $activeTab = $accessibleTabs[0];
        }

        // ── Check if current user is a teacher (view own classes only) ──
        $isTeacherOnly = $user->hasRole('teacher') && ! $user->hasAnyRole(['admin', 'super-admin', 'super_admin', 'principal']);
        $teacherEmployeeId = null;
        if ($isTeacherOnly) {
            $teacherEmployeeId = $user->employee?->id ?? Employee::where('user_id', $user->id)->value('id');
        }

        $classesBaseQuery = SchoolClass::query();
        if ($isTeacherOnly) {
            if ($teacherEmployeeId) {
                $classesBaseQuery->where(function ($q) use ($teacherEmployeeId) {
                    $q->whereHas('teacher_assignments', fn ($ta) => $ta->where('employee_id', $teacherEmployeeId))
                        ->orWhereHas('class_subjects.teacher_assignments', fn ($ta) => $ta->where('employee_id', $teacherEmployeeId));
                });
            } else {
                $classesBaseQuery->whereRaw('1 = 0');
            }
        }

        // ── Shared filter data (needed for filter dropdowns across tabs) ──
        $grades = $isTeacherOnly
            ? Grade::whereHas('classes', fn ($cq) => $cq->whereIn('id', (clone $classesBaseQuery)->select('id')))->orderBy('level')->get()
            : Grade::orderBy('level')->get();
        $subjects = Subject::orderBy('name')->get();
        $classes = (clone $classesBaseQuery)->with(['grade', 'section', 'academic_year'])->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();
        $employees = Employee::teachers()->with(['user', 'employee_position'])->get()->sortBy(fn ($e) => $e->name ?? $e->employee_code);
        $types = Subject::select('type')->distinct()->pluck('type')->filter();
        $buildings = Classroom::whereNotNull('building')->where('building', '!=', '')->distinct()->orderBy('building')->pluck('building');

        // ── Tab counts for navigation badges ──
        $counts = [
            'classes' => (clone $classesBaseQuery)->count(),
            'subjects' => Subject::count(),
            'teacher-assignments' => TeacherAssignment::count(),
            'classrooms' => Classroom::count(),
            'academic-years' => AcademicYear::count(),
        ];

        // ── Lazy-initialise tab data vars ──
        $classes_paginated = null;
        $subjects_paginated = null;
        $classSubjects = null;
        $teacherAssignments = null;
        $classrooms_paginated = null;
        $academicYears_paginated = null;
        $selectedAcademicYearId = null;
        $roomConflicts = [];

        // ── Load only the active tab's data ──
        switch ($activeTab) {
            case 'subjects':
                $query = Subject::with(['class_subjects.class.grade', 'class_subjects.class.section'])
                    ->withCount(['class_subjects', 'timetable_entries']);

                $query->when($request->filled('type'), fn ($q) => $q->where('type', $request->type));
                $query->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('code', 'like', "%{$request->search}%"));

                $subjects_paginated = $query->orderBy('name')->paginate(15)->withQueryString();
                break;

            case 'class-subjects':
                $query = SchoolClass::with([
                    'grade',
                    'section',
                    'academic_year',
                    'class_subjects.subject',
                    'class_subjects.teacher_assignments',
                ])->withCount(['class_subjects', 'students']);

                $query->when($request->filled('class_id'), fn ($q) => $q->where('id', $request->class_id));
                $query->when($request->filled('grade_id'), fn ($q) => $q->where('grade_id', $request->grade_id));
                $query->when($request->filled('academic_year_id'), fn ($q) => $q->where('academic_year_id', $request->academic_year_id));
                $query->when($request->filled('subject_id'), function ($q) use ($request) {
                    $q->whereHas('class_subjects', fn ($cs) => $cs->where('subject_id', $request->subject_id));
                });
                $query->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($sq) use ($search) {
                        $sq->whereHas('grade', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('section', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('class_subjects.subject', fn ($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"));
                    });
                });

                $classSubjects = $query->orderBy('academic_year_id', 'desc')
                    ->orderBy('grade_id')
                    ->orderBy('section_id')
                    ->paginate(15)
                    ->withQueryString();
                break;

            case 'teacher-assignments':
                $query = TeacherAssignment::with([
                    'employee.user',
                    'school_class.grade',
                    'school_class.section',
                    'class_subject.subject',
                    'class_subject.class.grade',
                    'class_subject.class.section',
                    'academic_year',
                ])->withCount('attendance_sessions');

                $query->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id));
                $query->when($request->filled('class_id'), function ($q) use ($request) {
                    $q->where(function ($sq) use ($request) {
                        $sq->where('class_id', $request->class_id)
                            ->orWhereHas('class_subject', fn ($q2) => $q2->where('class_id', $request->class_id));
                    });
                });
                $query->when($request->filled('subject_id'), fn ($q) => $q->whereHas('class_subject', fn ($q2) => $q2->where('subject_id', $request->subject_id)));
                $query->when($request->filled('academic_year_id'), fn ($q) => $q->where('academic_year_id', $request->academic_year_id));
                $query->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($sq) use ($search) {
                        $sq->whereHas('employee.user', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('school_class.grade', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('school_class.section', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('class_subject.subject', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('class_subject.class.grade', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
                    });
                });

                $teacherAssignments = $query->orderByDesc('academic_year_id')->orderBy('employee_id')->paginate(15)->withQueryString();
                break;

            case 'classrooms':
                $query = Classroom::withCount('timetable_entries');

                $query->when($request->filled('building'), fn ($q) => $q->where('building', $request->building));
                $query->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('building', 'like', "%{$search}%");
                    });
                });

                $classrooms_paginated = $query->orderBy('building')->orderBy('name')->paginate(15)->withQueryString();
                break;

            case 'academic-years':
                $query = AcademicYear::withCount(['classes', 'teacher_assignments', 'timetables']);

                $query->when($request->filled('status'), function ($q) use ($request) {
                    if ($request->status === 'active') {
                        $q->where('is_active', 1);
                    } elseif ($request->status === 'inactive') {
                        $q->where('is_active', 0);
                    }
                });

                $query->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where('name', 'like', "%{$search}%");
                });

                $academicYears_paginated = $query->orderByDesc('is_active')->orderByDesc('start_date')->paginate(15)->withQueryString();
                break;

            default: // 'classes'
                $activeTab = 'classes';
                $query = (clone $classesBaseQuery)->with([
                    'grade',
                    'section',
                    'academic_year',
                    'classroom',
                    'students.user',
                    'students.profile',
                    'students.studentStatus',
                ])->withCount(['class_subjects', 'timetables', 'students']);

                // Academic year default logic: default to active academic year or first year
                $activeYear = $academicYears->firstWhere('is_active', true) ?? $academicYears->first();
                $selectedAcademicYearId = $request->input('academic_year_id');
                if ($selectedAcademicYearId === null) {
                    $selectedAcademicYearId = $activeYear ? (string) $activeYear->id : 'all';
                }

                if ($selectedAcademicYearId !== 'all' && ! empty($selectedAcademicYearId)) {
                    $query->where('academic_year_id', $selectedAcademicYearId);
                }

                $query->when($request->filled('grade_id'), fn ($q) => $q->where('grade_id', $request->grade_id));
                $query->when($request->filled('search'), function ($q) use ($request) {
                    $search = $request->search;
                    $q->where(function ($sq) use ($search) {
                        $sq->whereHas('grade', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('section', fn ($q2) => $q2->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('classroom', fn ($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('building', 'like', "%{$search}%"));
                    });
                });

                $classes_paginated = $query->orderBy('academic_year_id', 'desc')
                    ->orderBy('grade_id')
                    ->orderBy('section_id')
                    ->paginate(50)
                    ->withQueryString();

                $roomConflicts = SchoolClass::getRoomConflictMap($selectedAcademicYearId);
                break;
        }

        $nextSubjectCode = Subject::generateNextCode();

        return view('academics.index', compact(
            'activeTab',
            'counts',
            'grades',
            'subjects',
            'classes',
            'academicYears',
            'employees',
            'types',
            'buildings',
            'classes_paginated',
            'subjects_paginated',
            'classSubjects',
            'teacherAssignments',
            'classrooms_paginated',
            'academicYears_paginated',
            'selectedAcademicYearId',
            'roomConflicts',
            'nextSubjectCode'
        ));
    }
}
