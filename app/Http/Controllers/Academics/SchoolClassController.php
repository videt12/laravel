<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\Classroom;
use App\Models\Academics\Grade;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Core\AcademicYear;
use App\Models\Employees\Employee;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return redirect()->route('academics.index', ['tab' => 'classes'] + $request->query());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! auth()->user()?->canAccess(['classes.create', 'classes'])) {
            abort(403, 'Unauthorized access: You do not have permission to create classes.');
        }

        $grades = Grade::orderBy('level')->get();
        $sections = Section::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classrooms = Classroom::orderBy('building')->orderBy('name')->get();
        $buildings = Classroom::whereNotNull('building')->where('building', '!=', '')->distinct()->orderBy('building')->pluck('building');

        $occupiedRooms = SchoolClass::with(['grade', 'section', 'academic_year'])
            ->whereNotNull('classroom_id')
            ->get()
            ->map(fn ($c) => [
                'class_id' => $c->id,
                'class_name' => $c->name,
                'classroom_id' => $c->classroom_id,
                'academic_year_id' => $c->academic_year_id,
                'shift' => $c->shift,
                'shift_label' => $c->shift_label,
            ]);

        return view('academics.classes.create', compact('grades', 'sections', 'academicYears', 'classrooms', 'buildings', 'occupiedRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! auth()->user()?->canAccess(['classes.create', 'classes'])) {
            abort(403, 'Unauthorized access: You do not have permission to create classes.');
        }

        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'capacity' => 'required|integer|min:1|max:100',
        ], [
            'classroom_id.required' => 'សូមជ្រើសរើសបន្ទប់រៀន (Classroom) សម្រាប់ថ្នាក់នេះ។',
            'classroom_id.exists' => 'បន្ទប់រៀនដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ]);

        // Ensure this combination doesn't already exist for this academic year
        $exists = SchoolClass::where('grade_id', $validated['grade_id'])
            ->where('section_id', $validated['section_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['section_id' => 'ថ្នាក់រៀន និងបន្ទប់/ផ្នែកនេះមានរួចហើយសម្រាប់កម្រិតថ្នាក់ និងឆ្នាំសិក្សាដែលបានជ្រើសរើស។']);
        }

        // Ensure classroom is not occupied by another class in the same shift and academic year
        if (! empty($validated['classroom_id'])) {
            $conflict = SchoolClass::findRoomConflict(
                (int) $validated['classroom_id'],
                (int) $validated['academic_year_id'],
                (int) $validated['section_id']
            );

            if ($conflict) {
                $room = Classroom::find($validated['classroom_id']);
                $roomName = $room ? "«{$room->name}".($room->building ? " (អគារ {$room->building})»" : '»') : 'បន្ទប់នេះ';
                $shiftText = SchoolClass::getShiftLabel(SchoolClass::detectShift($conflict->section?->name));

                return back()->withInput()->withErrors([
                    'classroom_id' => "{$roomName} ត្រូវបានជ្រើសរើសដោយ «{$conflict->name}» ក្នុងវេន{$shiftText} រួចហើយ! មិនអាចស្ទួនក្នុងវេនតែមួយបានទេ។",
                ]);
            }
        }

        SchoolClass::create($validated);

        return redirect()->route('academics.index', ['tab' => 'classes'])
            ->with('success', 'ថ្នាក់រៀនត្រូវបានបង្កើតដោយជោគជ័យ។');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $class)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['admin', 'super-admin', 'super_admin', 'principal'])) {
            $employeeId = $user->employee?->id ?? Employee::where('user_id', $user->id)->value('id');
            $teachesClass = $employeeId && (
                $class->teacher_assignments()->where('employee_id', $employeeId)->exists()
                || $class->class_subjects()->whereHas('teacher_assignments', fn ($q) => $q->where('employee_id', $employeeId))->exists()
            );

            if (! $teachesClass) {
                abort(403, 'Unauthorized access: You are only allowed to view classes you teach.');
            }
        }

        $class->load([
            'grade',
            'section',
            'academic_year',
            'classroom',
            'class_subjects.subject',
            'timetables',
            'students.user',
            'students.profile',
            'students.studentStatus',
        ]);

        return view('academics.classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class)
    {
        if (! auth()->user()?->canAccess(['classes.edit', 'classes'])) {
            abort(403, 'Unauthorized access: You do not have permission to edit classes.');
        }

        $grades = Grade::orderBy('level')->get();
        $sections = Section::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classrooms = Classroom::orderBy('building')->orderBy('name')->get();
        $buildings = Classroom::whereNotNull('building')->where('building', '!=', '')->distinct()->orderBy('building')->pluck('building');

        $occupiedRooms = SchoolClass::with(['grade', 'section', 'academic_year'])
            ->whereNotNull('classroom_id')
            ->where('id', '!=', $class->id)
            ->get()
            ->map(fn ($c) => [
                'class_id' => $c->id,
                'class_name' => $c->name,
                'classroom_id' => $c->classroom_id,
                'academic_year_id' => $c->academic_year_id,
                'shift' => $c->shift,
                'shift_label' => $c->shift_label,
            ]);

        return view('academics.classes.edit', compact('class', 'grades', 'sections', 'academicYears', 'classrooms', 'buildings', 'occupiedRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        if (! auth()->user()?->canAccess(['classes.edit', 'classes'])) {
            abort(403, 'Unauthorized access: You do not have permission to edit classes.');
        }

        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'capacity' => 'required|integer|min:1|max:100',
        ], [
            'classroom_id.required' => 'សូមជ្រើសរើសបន្ទប់រៀន (Classroom) សម្រាប់ថ្នាក់នេះ។',
            'classroom_id.exists' => 'បន្ទប់រៀនដែលបានជ្រើសរើសមិនត្រឹមត្រូវទេ។',
        ]);

        // Check uniqueness excluding current class
        $exists = SchoolClass::where('grade_id', $validated['grade_id'])
            ->where('section_id', $validated['section_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('id', '!=', $class->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['section_id' => 'ថ្នាក់រៀន និងបន្ទប់/ផ្នែកនេះមានរួចហើយសម្រាប់កម្រិតថ្នាក់ និងឆ្នាំសិក្សាដែលបានជ្រើសរើស។']);
        }

        // Ensure classroom is not occupied by another class in the same shift and academic year
        if (! empty($validated['classroom_id'])) {
            $conflict = SchoolClass::findRoomConflict(
                (int) $validated['classroom_id'],
                (int) $validated['academic_year_id'],
                (int) $validated['section_id'],
                $class->id
            );

            if ($conflict) {
                $room = Classroom::find($validated['classroom_id']);
                $roomName = $room ? "«{$room->name}".($room->building ? " (អគារ {$room->building})»" : '»') : 'បន្ទប់នេះ';
                $shiftText = SchoolClass::getShiftLabel(SchoolClass::detectShift($conflict->section?->name));

                return back()->withInput()->withErrors([
                    'classroom_id' => "{$roomName} ត្រូវបានជ្រើសរើសដោយ «{$conflict->name}» ក្នុងវេន{$shiftText} រួចហើយ! មិនអាចស្ទួនក្នុងវេនតែមួយបានទេ។",
                ]);
            }
        }

        $class->update($validated);

        return redirect()->route('academics.index', ['tab' => 'classes'])
            ->with('success', 'ថ្នាក់រៀនត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        if (! auth()->user()?->canAccess(['classes.delete', 'classes'])) {
            abort(403, 'Unauthorized access: You do not have permission to delete classes.');
        }

        $class->delete();

        return redirect()->route('academics.index', ['tab' => 'classes'])
            ->with('success', 'ថ្នាក់រៀនត្រូវបានលុបដោយជោគជ័យ។');
    }
}
