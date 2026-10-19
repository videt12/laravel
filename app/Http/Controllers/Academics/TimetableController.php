<?php

namespace App\Http\Controllers\Academics;

use App\Http\Controllers\Controller;
use App\Models\Academics\Classroom;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Subject;
use App\Models\Academics\TeacherAssignment;
use App\Models\Academics\Timetable;
use App\Models\Academics\TimetableEntry;
use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolTerm;
use App\Models\Core\TimeSlot;
use App\Models\Employees\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Timetable::with(['class.grade', 'class.section', 'academic_year', 'school_term', 'timetable_entries.time_slot'])
            ->withCount('timetable_entries');

        // Filtering
        $query->when($request->filled('academic_year_id'), function ($q) use ($request) {
            $q->where('academic_year_id', $request->academic_year_id);
        });

        $query->when($request->filled('shift'), function ($q) use ($request) {
            if ($request->shift === 'morning') {
                $q->where(function ($sub) {
                    $sub->whereHas('timetable_entries.time_slot', function ($sq) {
                        $sq->whereTime('start_time', '<', '12:00:00');
                    })->orWhereDoesntHave('timetable_entries');
                });
            } elseif ($request->shift === 'afternoon') {
                $q->whereHas('timetable_entries.time_slot', function ($sq) {
                    $sq->whereTime('start_time', '>=', '12:00:00');
                });
            }
        });

        $timetables = $query->orderBy('academic_year_id', 'desc')->paginate(15)->withQueryString();

        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        return view('academics.timetables.index', compact('timetables', 'academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(! auth()->user()?->canAccess('timetables.create'), 403, 'លោកអ្នកគ្មានសិទ្ធិបង្កើតកាលវិភាគឡើយ។');

        $classes = SchoolClass::with(['grade', 'section', 'classroom'])->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $schoolTerms = SchoolTerm::orderBy('start_date', 'desc')->get();
        $classrooms = Classroom::with('classes:id,classroom_id')
            ->orderBy('name')
            ->get();
        $teachers = Employee::teachers()
            ->with(['user', 'employeeContact'])
            ->get()
            ->sortBy(fn ($t) => $t->full_name ?? $t->user?->name ?? $t->employee_code)
            ->values();

        $buildings = Classroom::whereNotNull('building')
            ->where('building', '!=', '')
            ->distinct()
            ->orderBy('building')
            ->pluck('building')
            ->values();

        $classroomsData = $classrooms->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->name,
                'building' => $r->building ?? '',
                'label' => $r->name . ($r->building ? ' (' . $r->building . ')' : ''),
                'assigned_classes' => $r->classes->pluck('id')->toArray(),
            ];
        })->values();

        $classRoomMap = $classes->pluck('classroom_id', 'id');

        $classTeacherMap = TeacherAssignment::with('employee.employeeContact', 'employee.user')
            ->get()
            ->groupBy('class_id')
            ->map(function ($assignments) {
                $first = $assignments->first();
                $employee = $first?->employee;
                return [
                    'teacher_id' => $first?->employee_id,
                    'phone' => $employee?->phone ?? $employee?->user?->phone ?? $employee?->employeeContact?->phone ?? '',
                ];
            });

        return view('academics.timetables.create', compact('classes', 'academicYears', 'schoolTerms', 'teachers', 'classTeacherMap', 'classrooms', 'classRoomMap', 'buildings', 'classroomsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(! auth()->user()?->canAccess('timetables.create'), 403, 'លោកអ្នកគ្មានសិទ្ធិបង្កើតកាលវិភាគឡើយ។');

        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'school_term_id' => 'required|exists:school_terms,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'teacher_id' => 'nullable|exists:employees,id',
            'teacher_phone' => 'nullable|string|max:50',
        ]);

        // Duplicate check: 1 timetable per class + term
        $exists = Timetable::where('class_id', $validated['class_id'])
            ->where('school_term_id', $validated['school_term_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'កាលវិភាគសម្រាប់ថ្នាក់នេះក្នុងឆមាសដែលបានជ្រើសរើសមានរួចហើយ!');
        }

        $timetable = Timetable::create([
            'class_id' => $validated['class_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'school_term_id' => $validated['school_term_id'],
        ]);

        // Update classroom for this class if provided
        if (!empty($validated['classroom_id'])) {
            $schoolClass = SchoolClass::find($validated['class_id']);
            if ($schoolClass && $schoolClass->classroom_id != $validated['classroom_id']) {
                $schoolClass->update(['classroom_id' => $validated['classroom_id']]);
            }
        }

        // Save teacher assignment & phone if provided
        if (!empty($validated['teacher_id'])) {
            TeacherAssignment::updateOrCreate(
                [
                    'class_id' => $validated['class_id'],
                    'academic_year_id' => $validated['academic_year_id'],
                ],
                [
                    'employee_id' => $validated['teacher_id'],
                ]
            );

            if (!empty($validated['teacher_phone'])) {
                $employee = Employee::with(['user', 'employeeContact'])->find($validated['teacher_id']);
                if ($employee) {
                    if ($employee->employeeContact) {
                        $employee->employeeContact->update(['phone' => $validated['teacher_phone']]);
                    } else {
                        $employee->employeeContact()->create(['phone' => $validated['teacher_phone']]);
                    }
                    if ($employee->user) {
                        $employee->user->update(['phone' => $validated['teacher_phone']]);
                    }
                }
            }
        }

        return redirect()->route('timetables.show', $timetable)
            ->with('success', 'កាលវិភាគត្រូវបានបង្កើតដោយជោគជ័យ។ លោកអ្នកអាចកំណត់មុខវិជ្ជាប្រចាំសប្តាហ៍បានឥឡូវនេះ។');
    }

    /**
     * Display the specified resource.
     */
    public function show(Timetable $timetable)
    {
        $timetable->load([
            'class.grade',
            'class.section',
            'class.classroom',
            'academic_year',
            'school_term',
            'timetable_entries.subject',
            'timetable_entries.time_slot',
            'timetable_entries.teacher.user',
            'timetable_entries.teacher.employeeContact',
            'timetable_entries.classroom',
        ]);

        $subjects = Subject::orderBy('name')->get();

        // Get classroom designated for this class
        $classRoomIds = collect();
        if ($timetable->class?->classroom_id) {
            $classRoomIds->push($timetable->class->classroom_id);
        }

        // Also merge any classrooms already used in entries for this timetable
        $allClassroomIds = $classRoomIds
            ->merge($timetable->timetable_entries->pluck('classroom_id'))
            ->filter()
            ->unique();

        if ($allClassroomIds->isNotEmpty()) {
            $classrooms = Classroom::whereIn('id', $allClassroomIds)
                ->orderBy('building')
                ->orderBy('name')
                ->get();
        } else {
            // Fallback if class doesn't have an assigned classroom yet
            $classrooms = Classroom::orderBy('building')->orderBy('name')->get();
        }
        // Get teachers who are assigned to teach this class
        $classTeacherIds = TeacherAssignment::where(function ($q) use ($timetable) {
            $q->where('class_id', $timetable->class_id)
                ->orWhereHas('class_subject', fn ($cq) => $cq->where('class_id', $timetable->class_id));
        })
            ->when($timetable->academic_year_id, function ($q) use ($timetable) {
                $q->where(function ($sub) use ($timetable) {
                    $sub->where('academic_year_id', $timetable->academic_year_id)
                        ->orWhereNull('academic_year_id');
                });
            })
            ->pluck('employee_id');

        // Fallback without academic year if none found for that specific year
        if ($classTeacherIds->isEmpty()) {
            $classTeacherIds = TeacherAssignment::where(function ($q) use ($timetable) {
                $q->where('class_id', $timetable->class_id)
                    ->orWhereHas('class_subject', fn ($cq) => $cq->where('class_id', $timetable->class_id));
            })
                ->pluck('employee_id');
        }

        // Also merge any teachers already assigned to entries in this timetable
        $allTeacherIds = $classTeacherIds
            ->merge($timetable->timetable_entries->pluck('teacher_id'))
            ->filter()
            ->unique();

        if ($allTeacherIds->isNotEmpty()) {
            $teachers = Employee::whereIn('id', $allTeacherIds)
                ->with(['user', 'employeeContact'])
                ->get()
                ->sortBy(fn ($t) => $t->user?->name ?? $t->employee_code)
                ->values();
        } else {
            // Fallback: If no teacher has been assigned to this class yet,
            // only show employees who are actually teachers (never non-teacher staff)
            $teachers = Employee::teachers()
                ->with(['user', 'employeeContact'])
                ->get()
                ->sortBy(fn ($t) => $t->user?->name ?? $t->employee_code)
                ->values();
        }

        // Check whether this timetable belongs to Morning or Afternoon
        $entries = $timetable->timetable_entries;
        $hasMorning = $entries->contains(fn ($e) => $e->time_slot && Carbon::parse($e->time_slot->start_time)->hour < 12);
        $hasAfternoon = $entries->contains(fn ($e) => $e->time_slot && Carbon::parse($e->time_slot->start_time)->hour >= 12);
        $className = ($timetable->class->grade->name ?? '').' '.($timetable->class->section->name ?? '');

        $shiftParam = request('shift');

        if ($shiftParam === 'afternoon' || (! $hasMorning && $hasAfternoon) || str_contains($className, 'រសៀល') || str_contains($className, 'ពេលថ្ងៃ')) {
            $timetableShift = 'afternoon';
            $timeSlots = TimeSlot::whereTime('start_time', '>=', '12:00:00')->orderBy('start_time')->get();
        } else {
            $timetableShift = 'morning';
            $timeSlots = TimeSlot::whereTime('start_time', '<', '12:00:00')->orderBy('start_time')->get();
        }

        // 2D Matrix: [day_of_week][time_slot_id] => TimetableEntry
        $scheduleMatrix = [];
        foreach ($timetable->timetable_entries as $entry) {
            $scheduleMatrix[$entry->day_of_week][$entry->time_slot_id] = $entry;
        }

        // Summary counts
        $totalEntries = $timetable->timetable_entries->count();
        $uniqueSubjects = $timetable->timetable_entries->pluck('subject_id')->unique()->count();
        $uniqueTeachers = $timetable->timetable_entries->pluck('teacher_id')->filter()->unique()->count();
        $teachingSlotsCount = $timeSlots->where('is_break', 0)->count();

        return view('academics.timetables.show', compact(
            'timetable',
            'subjects',
            'classrooms',
            'timeSlots',
            'teachers',
            'scheduleMatrix',
            'totalEntries',
            'uniqueSubjects',
            'uniqueTeachers',
            'teachingSlotsCount',
            'timetableShift'
        ));
    }

    /**
     * Store or update a timetable schedule entry.
     */
    public function storeEntry(Request $request, Timetable $timetable)
    {
        abort_if(! auth()->user()?->canAccess(['timetables.create', 'timetables.edit']), 403, 'លោកអ្នកគ្មានសិទ្ធិកំណត់ម៉ោងបង្រៀនឡើយ។');

        $validated = $request->validate([
            'day_of_week' => 'required|integer|min:1|max:7',
            'time_slot_id' => 'required|exists:time_slots,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:employees,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        if (empty($validated['teacher_id'])) {
            $validated['teacher_id'] = TeacherAssignment::where('class_id', $timetable->class_id)
                ->when($timetable->academic_year_id, fn ($q) => $q->where('academic_year_id', $timetable->academic_year_id))
                ->value('employee_id');
        }

        if (empty($validated['classroom_id'])) {
            $validated['classroom_id'] = $timetable->class?->classroom_id;
        }

        // Overwrite or update existing entry for the same day and time slot
        $existing = $timetable->timetable_entries()
            ->where('day_of_week', $validated['day_of_week'])
            ->where('time_slot_id', $validated['time_slot_id'])
            ->first();

        if ($existing) {
            $existing->update($validated);

            return back()->with('success', 'ម៉ោងបង្រៀនត្រូវបានកែប្រែដោយជោគជ័យ។');
        }

        $timetable->timetable_entries()->create($validated);

        return back()->with('success', 'ម៉ោងបង្រៀនត្រូវបានកំណត់ចូលក្នុងកាលវិភាគដោយជោគជ័យ។');
    }

    /**
     * Delete a timetable schedule entry.
     */
    public function destroyEntry(Timetable $timetable, TimetableEntry $entry)
    {
        abort_if(! auth()->user()?->canAccess('timetables.delete'), 403, 'លោកអ្នកគ្មានសិទ្ធិលុបម៉ោងបង្រៀនឡើយ។');

        if ($entry->timetable_id === $timetable->id) {
            $entry->delete();

            return back()->with('success', 'ម៉ោងបង្រៀនត្រូវបានលុបចេញពីកាលវិភាគដោយជោគជ័យ។');
        }

        return back()->with('error', 'ទិន្នន័យមិនស្ថិតក្នុងកាលវិភាគនេះទេ។');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timetable $timetable)
    {
        abort_if(! auth()->user()?->canAccess('timetables.edit'), 403, 'លោកអ្នកគ្មានសិទ្ធិកែសម្រួលកាលវិភាគឡើយ។');

        $classes = SchoolClass::with(['grade', 'section', 'classroom'])->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $schoolTerms = SchoolTerm::orderBy('start_date', 'desc')->get();
        $classrooms = Classroom::with('classes:id,classroom_id')
            ->orderBy('name')
            ->get();
        $teachers = Employee::teachers()
            ->with(['user', 'employeeContact'])
            ->get()
            ->sortBy(fn ($t) => $t->full_name ?? $t->user?->name ?? $t->employee_code)
            ->values();

        $buildings = Classroom::whereNotNull('building')
            ->where('building', '!=', '')
            ->distinct()
            ->orderBy('building')
            ->pluck('building')
            ->values();

        $classroomsData = $classrooms->map(function ($r) {
            return [
                'id' => $r->id,
                'name' => $r->name,
                'building' => $r->building ?? '',
                'label' => $r->name . ($r->building ? ' (' . $r->building . ')' : ''),
                'assigned_classes' => $r->classes->pluck('id')->toArray(),
            ];
        })->values();

        $currentClassroomId = $timetable->class?->classroom_id;
        $classRoomMap = $classes->pluck('classroom_id', 'id');

        $currentTeacherAssignment = TeacherAssignment::where('class_id', $timetable->class_id)
            ->when($timetable->academic_year_id, fn ($q) => $q->where('academic_year_id', $timetable->academic_year_id))
            ->first();

        $currentTeacherId = $currentTeacherAssignment?->employee_id
            ?? $timetable->timetable_entries->pluck('teacher_id')->filter()->first();

        $currentTeacher = $currentTeacherId ? Employee::with(['user', 'employeeContact'])->find($currentTeacherId) : null;
        $currentPhone = $currentTeacher?->phone ?? $currentTeacher?->user?->phone ?? $currentTeacher?->employeeContact?->phone;

        $classTeacherMap = TeacherAssignment::with('employee.employeeContact', 'employee.user')
            ->get()
            ->groupBy('class_id')
            ->map(function ($assignments) {
                $first = $assignments->first();
                $employee = $first?->employee;
                return [
                    'teacher_id' => $first?->employee_id,
                    'phone' => $employee?->phone ?? $employee?->user?->phone ?? $employee?->employeeContact?->phone ?? '',
                ];
            });

        return view('academics.timetables.edit', compact(
            'timetable',
            'classes',
            'academicYears',
            'schoolTerms',
            'teachers',
            'currentTeacherId',
            'currentPhone',
            'classTeacherMap',
            'classrooms',
            'currentClassroomId',
            'classRoomMap',
            'buildings',
            'classroomsData'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timetable $timetable)
    {
        abort_if(! auth()->user()?->canAccess('timetables.edit'), 403, 'លោកអ្នកគ្មានសិទ្ធិកែសម្រួលកាលវិភាគឡើយ។');

        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'school_term_id' => 'required|exists:school_terms,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'teacher_id' => 'nullable|exists:employees,id',
            'teacher_phone' => 'nullable|string|max:50',
        ]);

        $exists = Timetable::where('class_id', $validated['class_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('school_term_id', $validated['school_term_id'])
            ->where('id', '!=', $timetable->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['class_id' => 'កាលវិភាគសម្រាប់ថ្នាក់នេះក្នុងឆមាសដែលបានជ្រើសរើសមានរួចហើយ។']);
        }

        $timetable->update([
            'class_id' => $validated['class_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'school_term_id' => $validated['school_term_id'],
        ]);

        // Update classroom for this class & sync timetable entries if provided
        if (!empty($validated['classroom_id'])) {
            $schoolClass = SchoolClass::find($validated['class_id']);
            if ($schoolClass && $schoolClass->classroom_id != $validated['classroom_id']) {
                $schoolClass->update(['classroom_id' => $validated['classroom_id']]);
            }
            // Sync all existing entries for this timetable
            $timetable->timetable_entries()->update(['classroom_id' => $validated['classroom_id']]);
        }

        // Save / update teacher assignment & phone
        if (!empty($validated['teacher_id'])) {
            TeacherAssignment::updateOrCreate(
                [
                    'class_id' => $validated['class_id'],
                    'academic_year_id' => $validated['academic_year_id'],
                ],
                [
                    'employee_id' => $validated['teacher_id'],
                ]
            );

            if (!empty($validated['teacher_phone'])) {
                $employee = Employee::with(['user', 'employeeContact'])->find($validated['teacher_id']);
                if ($employee) {
                    if ($employee->employeeContact) {
                        $employee->employeeContact->update(['phone' => $validated['teacher_phone']]);
                    } else {
                        $employee->employeeContact()->create(['phone' => $validated['teacher_phone']]);
                    }
                    if ($employee->user) {
                        $employee->user->update(['phone' => $validated['teacher_phone']]);
                    }
                }
            }

            // Sync entries in this timetable to inherit this teacher if they don't have one
            $timetable->timetable_entries()->whereNull('teacher_id')->update(['teacher_id' => $validated['teacher_id']]);
        }

        return redirect()->route('timetables.show', $timetable)
            ->with('success', 'ព័ត៌មានកាលវិភាគត្រូវបានកែប្រែដោយជោគជ័យ។');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timetable $timetable)
    {
        abort_if(! auth()->user()?->canAccess('timetables.delete'), 403, 'លោកអ្នកគ្មានសិទ្ធិលុបកាលវិភាគឡើយ។');

        $timetable->delete();

        return redirect()->route('timetables.index')
            ->with('success', 'កាលវិភាគត្រូវបានលុបដោយជោគជ័យ។');
    }
}
