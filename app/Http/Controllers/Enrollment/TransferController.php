<?php

namespace App\Http\Controllers\Enrollment;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Core\School;
use App\Models\Enrollment\Enrollment;
use App\Models\Enrollment\StudentTransfer;
use App\Models\Students\Student;
use App\Models\Students\StudentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    use AuthorizesEnrollmentAccess;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeEnrollmentView();
        $query = StudentTransfer::with([
            'student.user',
            'from_school',
            'to_school',
        ]);

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('student', function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transfers = $query->orderBy('transfer_date', 'desc')->paginate(15)->withQueryString();

        return view('enrollment.transfers.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeEnrollmentManagement();

        $students = Student::with(['user', 'profile', 'schoolClass.grade', 'schoolClass.section', 'academicYear'])
            ->get()
            ->sortBy(function ($student) {
                return $student->user ? $student->user->name : '';
            });
        $classes = SchoolClass::with('grade', 'section')
            ->get()
            ->sortBy(fn ($c) => ($c->grade?->level ?? 0).($c->section?->name ?? ''));
        $schools = School::orderBy('name')->get();

        $studentsData = $students->map(function ($s) {
            $khmerName = trim(($s->profile?->last_name_khmer ?? '').' '.($s->profile?->first_name_khmer ?? ''));
            $name = $khmerName ?: ($s->user?->name ?? 'សិស្ស');

            return [
                'id' => $s->id,
                'class_id' => $s->school_class_id,
                'name' => $name,
                'admission_number' => $s->admission_number ?? 'គ្មានអត្តលេខ',
                'class_name' => ($s->schoolClass?->grade?->name ?? '').($s->schoolClass?->section?->name ? ' — '.$s->schoolClass->section->name : ''),
            ];
        })->values();

        return view('enrollment.transfers.create', compact('students', 'schools', 'classes', 'studentsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeEnrollmentManagement();

        $fromInput = trim((string) ($request->input('from_school_name') ?: $request->input('from_school_id')));
        $toInput = trim((string) ($request->input('to_school_name') ?: $request->input('to_school_id')));

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'nullable',
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
        ], [
            'student_id.required' => 'សូមជ្រើសរើសសិស្ស',
            'transfer_date.required' => 'សូមបញ្ជាក់កាលបរិច្ឆេទផ្ទេរ',
        ]);

        if (empty($fromInput)) {
            return back()->withErrors(['from_school_name' => 'សូមវាយបញ្ចូល ឬជ្រើសរើសពីសាលារៀន'])->withInput();
        }

        if (empty($toInput)) {
            return back()->withErrors(['to_school_name' => 'សូមវាយបញ្ចូល ឬជ្រើសរើសទៅសាលារៀន'])->withInput();
        }

        if (mb_strtolower($fromInput) === mb_strtolower($toInput)) {
            return back()->withErrors(['to_school_name' => 'សាលារៀនដើម និងសាលារៀនដែលត្រូវផ្ទេរទៅ មិនអាចដូចគ្នាបានទេ'])->withInput();
        }

        $fromSchool = is_numeric($fromInput) && School::where('id', $fromInput)->exists()
            ? School::find($fromInput)
            : School::firstOrCreate(['name' => $fromInput], ['status' => 'active']);

        $toSchool = is_numeric($toInput) && School::where('id', $toInput)->exists()
            ? School::find($toInput)
            : School::firstOrCreate(['name' => $toInput], ['status' => 'active']);

        if ($fromSchool->id === $toSchool->id) {
            return back()->withErrors(['to_school_name' => 'សាលារៀនដើម និងសាលារៀនដែលត្រូវផ្ទេរទៅ មិនអាចដូចគ្នាបានទេ'])->withInput();
        }

        $validated = [
            'student_id' => $request->student_id,
            'from_school_id' => $fromSchool->id,
            'to_school_id' => $toSchool->id,
            'transfer_date' => $request->transfer_date,
            'reason' => $request->reason,
            'status' => $request->status,
        ];

        DB::transaction(function () use ($validated) {
            $transfer = StudentTransfer::create($validated);

            // Safeguard 3: Only set student_status_id = "Transferred" when status is Approved or Completed
            if (in_array(strtolower($validated['status']), ['approved', 'completed'])) {
                $student = Student::find($validated['student_id']);
                if ($student) {
                    $transferredStatusId = StudentStatus::whereRaw('LOWER(name) = ?', ['transferred'])->value('id');
                    if ($transferredStatusId) {
                        $student->update(['student_status_id' => $transferredStatusId]);
                    }

                    // Close active enrollments
                    Enrollment::where('student_id', $student->id)
                        ->where('status', 'Active')
                        ->update(['status' => 'Transferred']);
                }
            }
        });

        return redirect()->route('transfers.index')
            ->with('success', 'Student transfer recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentTransfer $transfer)
    {
        $this->authorizeEnrollmentView();

        $transfer->load([
            'student.user',
            'from_school',
            'to_school',
        ]);

        return view('enrollment.transfers.show', compact('transfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentTransfer $transfer)
    {
        $this->authorizeEnrollmentManagement();

        $students = Student::with(['user', 'schoolClass.grade', 'schoolClass.section', 'academicYear'])
            ->get()
            ->sortBy(function ($student) {
                return $student->user ? $student->user->name : '';
            });
        $classes = SchoolClass::with('grade', 'section')
            ->get()
            ->sortBy(fn ($c) => ($c->grade?->level ?? 0).($c->section?->name ?? ''));
        $schools = School::orderBy('name')->get();

        return view('enrollment.transfers.edit', compact('transfer', 'students', 'schools', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentTransfer $transfer)
    {
        $this->authorizeEnrollmentManagement();

        $fromInput = trim((string) ($request->input('from_school_name') ?: $request->input('from_school_id')));
        $toInput = trim((string) ($request->input('to_school_name') ?: $request->input('to_school_id')));

        $request->validate([
            'transfer_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
        ], [
            'transfer_date.required' => 'សូមបញ្ជាក់កាលបរិច្ឆេទផ្ទេរ',
        ]);

        if (empty($fromInput)) {
            return back()->withErrors(['from_school_name' => 'សូមវាយបញ្ចូល ឬជ្រើសរើសពីសាលារៀន'])->withInput();
        }

        if (empty($toInput)) {
            return back()->withErrors(['to_school_name' => 'សូមវាយបញ្ចូល ឬជ្រើសរើសទៅសាលារៀន'])->withInput();
        }

        if (mb_strtolower($fromInput) === mb_strtolower($toInput)) {
            return back()->withErrors(['to_school_name' => 'សាលារៀនដើម និងសាលារៀនដែលត្រូវផ្ទេរទៅ មិនអាចដូចគ្នាបានទេ'])->withInput();
        }

        $fromSchool = is_numeric($fromInput) && School::where('id', $fromInput)->exists()
            ? School::find($fromInput)
            : School::firstOrCreate(['name' => $fromInput], ['status' => 'active']);

        $toSchool = is_numeric($toInput) && School::where('id', $toInput)->exists()
            ? School::find($toInput)
            : School::firstOrCreate(['name' => $toInput], ['status' => 'active']);

        if ($fromSchool->id === $toSchool->id) {
            return back()->withErrors(['to_school_name' => 'សាលារៀនដើម និងសាលារៀនដែលត្រូវផ្ទេរទៅ មិនអាចដូចគ្នាបានទេ'])->withInput();
        }

        $validated = [
            'from_school_id' => $fromSchool->id,
            'to_school_id' => $toSchool->id,
            'transfer_date' => $request->transfer_date,
            'reason' => $request->reason,
            'status' => $request->status,
        ];

        DB::transaction(function () use ($validated, $transfer) {
            $transfer->update($validated);

            if (in_array(strtolower($validated['status']), ['approved', 'completed'])) {
                $student = $transfer->student;
                if ($student) {
                    $transferredStatusId = StudentStatus::whereRaw('LOWER(name) = ?', ['transferred'])->value('id');
                    if ($transferredStatusId) {
                        $student->update(['student_status_id' => $transferredStatusId]);
                    }

                    Enrollment::where('student_id', $student->id)
                        ->where('status', 'Active')
                        ->update(['status' => 'Transferred']);
                }
            }
        });

        return redirect()->route('transfers.index')
            ->with('success', 'Student transfer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentTransfer $transfer)
    {
        $this->authorizeEnrollmentManagement();

        $transfer->delete();

        return redirect()->route('transfers.index')
            ->with('success', 'Student transfer deleted successfully.');
    }
}
