<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\TeacherAssignment;
use App\Models\Core\AcademicYear;
use App\Models\Employees\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeacherAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return redirect()->route('academics.index', ['tab' => 'teacher-assignments'] + $request->query());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::teachers()
            ->with(['user', 'employee_position'])
            ->get()
            ->sortBy(fn ($e) => $e->name ?? $e->employee_code);
        $classes = SchoolClass::with([
            'grade',
            'section',
            'academic_year',
        ])->get()->sortBy(fn ($c) => ($c->grade->name ?? '').' '.
            ($c->section->name ?? '')
        );
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();

        return view('academics.teacher_assignments.create', compact('employees', 'classes', 'academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        ]);

        $class = SchoolClass::with('academic_year')->findOrFail($validated['class_id']);

        $employee = Employee::teachers()->find($validated['employee_id']);
        if (! $employee) {
            throw ValidationException::withMessages([
                'employee_id' => 'បុគ្គលិកដែលបានជ្រើសរើសមិនមានតួនាទីជាគ្រូបង្រៀនទេ។ (The selected employee does not have a teacher role).',
            ]);
        }

        // Academic-Year Consistency Enforcement (Prevent cross-academic-year assignments)
        if ($class->academic_year_id && (int) $class->academic_year_id !== (int) $validated['academic_year_id']) {
            $classYearName = $class->academic_year->name ?? "Year #{$class->academic_year_id}";
            throw ValidationException::withMessages([
                'academic_year_id' => "The selected academic year does not match the class's academic year ({$classYearName}). Cross-academic-year teacher assignments are not allowed.",
            ]);
        }

        // Duplicate Assignment Prevention (Application-level safeguard)
        $duplicateExists = TeacherAssignment::where('employee_id', $validated['employee_id'])
            ->where('class_id', $validated['class_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->exists();

        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'employee_id' => 'គ្រូបង្រៀនរូបនេះត្រូវបានចាត់តាំងឱ្យបង្រៀនថ្នាក់នេះសម្រាប់ឆ្នាំសិក្សាដែលបានជ្រើសរើសរួចហើយ។',
            ]);
        }

        $teacherAssignment = TeacherAssignment::create($validated);

        return redirect()->route('academics.index', ['tab' => 'teacher-assignments'])
            ->with('success', 'ការចាត់តាំងគ្រូបង្រៀនត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherAssignment $teacherAssignment)
    {
        $teacherAssignment->load([
            'employee.user',
            'employee.employee_department',
            'employee.employee_position',
            'school_class.grade',
            'school_class.section',
            'school_class.academic_year',
            'class_subject.subject',
            'class_subject.class.grade',
            'class_subject.class.section',
            'class_subject.class.academic_year',
            'academic_year',
            'attendance_sessions' => fn ($q) => $q->orderByDesc('attendance_date')->limit(10),
        ])->loadCount('attendance_sessions');

        return view('academics.teacher_assignments.show', compact('teacherAssignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherAssignment $teacherAssignment)
    {
        $employees = Employee::teachers($teacherAssignment->employee_id)
            ->with(['user', 'employee_position'])
            ->get()
            ->sortBy(fn ($e) => $e->name ?? $e->employee_code);
        $classes = SchoolClass::with([
            'grade',
            'section',
            'academic_year',
        ])->get()->sortBy(fn ($c) => ($c->grade->name ?? '').' '.
            ($c->section->name ?? '')
        );
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();

        return view('academics.teacher_assignments.edit', compact(
            'teacherAssignment',
            'employees',
            'classes',
            'academicYears'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherAssignment $teacherAssignment)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        ]);

        $class = SchoolClass::with('academic_year')->findOrFail($validated['class_id']);

        $employee = Employee::teachers($teacherAssignment->employee_id)->find($validated['employee_id']);
        if (! $employee) {
            throw ValidationException::withMessages([
                'employee_id' => 'បុគ្គលិកដែលបានជ្រើសរើសមិនមានតួនាទីជាគ្រូបង្រៀនទេ។ (The selected employee does not have a teacher role).',
            ]);
        }

        // Academic-Year Consistency Enforcement
        if ($class->academic_year_id && (int) $class->academic_year_id !== (int) $validated['academic_year_id']) {
            $classYearName = $class->academic_year->name ?? "Year #{$class->academic_year_id}";
            throw ValidationException::withMessages([
                'academic_year_id' => "The selected academic year does not match the class's academic year ({$classYearName}). Cross-academic-year teacher assignments are not allowed.",
            ]);
        }

        // Duplicate Assignment Prevention (Ignoring current record)
        $duplicateExists = TeacherAssignment::where('employee_id', $validated['employee_id'])
            ->where('class_id', $validated['class_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('id', '!=', $teacherAssignment->id)
            ->exists();

        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'employee_id' => 'គ្រូបង្រៀនរូបនេះត្រូវបានចាត់តាំងឱ្យបង្រៀនថ្នាក់នេះសម្រាប់ឆ្នាំសិក្សាដែលបានជ្រើសរើសរួចហើយ។',
            ]);
        }

        $teacherAssignment->update($validated);

        return redirect()->route('academics.index', ['tab' => 'teacher-assignments'])
            ->with('success', 'ការចាត់តាំងគ្រូបង្រៀនត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherAssignment $teacherAssignment)
    {
        $teacherAssignment->delete();

        return redirect()->route('academics.index', ['tab' => 'teacher-assignments'])
            ->with('success', 'ការចាត់តាំងគ្រូបង្រៀនត្រូវបានលុបដោយជោគជ័យ។');
    }
}
