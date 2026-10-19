<?php

namespace App\Http\Controllers\Enrollment;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolShift;
use App\Models\Enrollment\Enrollment;
use App\Models\Enrollment\EnrollmentDetail;
use App\Models\Enrollment\Withdrawal;
use App\Models\Students\Student;
use App\Models\Students\StudentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentController extends Controller
{
    use AuthorizesEnrollmentAccess;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeEnrollmentView();
        $query = Enrollment::with(['student.schoolShift', 'academic_year', 'enrollmentDetail.class.grade', 'enrollmentDetail.class.section']);

        // Filter by Academic Year
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Shift (Morning or Afternoon)
        $shift = $request->input('shift');
        if ($shift === 'morning') {
            $query->where(function ($q) {
                $q->whereHas('enrollmentDetail.class.section', function ($sq) {
                    $sq->where('name', 'like', '%ព្រឹក%')
                        ->orWhere('name', 'like', '%morning%');
                })->orWhereHas('student', function ($sq) {
                    $sq->whereHas('schoolShift', function ($shq) {
                        $shq->where('name', 'like', '%morning%')
                            ->orWhere('name', 'like', '%ព្រឹក%');
                    });
                });
            });
        } elseif ($shift === 'afternoon') {
            $query->where(function ($q) {
                $q->whereHas('enrollmentDetail.class.section', function ($sq) {
                    $sq->where('name', 'like', '%រសៀល%')
                        ->orWhere('name', 'like', '%afternoon%');
                })->orWhereHas('student', function ($sq) {
                    $sq->whereHas('schoolShift', function ($shq) {
                        $shq->where('name', 'like', '%afternoon%')
                            ->orWhere('name', 'like', '%រសៀល%')
                            ->orWhere('name', 'like', '%day%');
                    });
                });
            });
        }

        // Search by student name or admission number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($sq) use ($search) {
                $sq->where('admission_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $enrollments = $query->orderBy('enrollment_date', 'desc')->paginate(15)->withQueryString();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        return view('enrollment.enrollments.index', compact('enrollments', 'academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeEnrollmentManagement();

        // For simplicity, fetch students not currently enrolled in the active academic year
        $students = Student::with('user')->get()->sortBy(function ($student) {
            return $student->user ? $student->user->name : '';
        });
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::with('grade', 'section')->get();
        $shifts = SchoolShift::orderBy('name')->get();

        return view('enrollment.enrollments.create', compact('students', 'academicYears', 'classes', 'shifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeEnrollmentManagement();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|string',
            'class_id' => 'required|exists:school_classes,id',
            'school_shift_id' => 'nullable|exists:school_shifts,id',
        ]);

        DB::transaction(function () use ($validated) {
            $class = SchoolClass::findOrFail($validated['class_id']);

            $existingEnrollment = Enrollment::where('student_id', $validated['student_id'])
                ->where('academic_year_id', $validated['academic_year_id'])
                ->latest()
                ->first();

            // If the student already has an active enrollment in this academic year
            if ($existingEnrollment && ! in_array(strtolower($existingEnrollment->status), ['withdrawn', 'cancelled', 'inactive'])) {
                // If there was a pending withdrawal, cancel it and keep them enrolled
                $pendingWithdrawal = Withdrawal::where('student_id', $validated['student_id'])
                    ->where('status', 'Pending')
                    ->first();

                if ($pendingWithdrawal) {
                    $pendingWithdrawal->update(['status' => 'Cancelled']);
                } else {
                    throw ValidationException::withMessages([
                        'student_id' => 'សិស្សនេះមានឈ្មោះកំពុងចុះឈ្មោះរៀនរួចហើយ សម្រាប់ឆ្នាំសិក្សាដែលបានជ្រើសរើសនេះ។ (This student is already actively enrolled for the selected academic year.)',
                    ]);
                }
            }

            if ($existingEnrollment) {
                // Reactivate and update existing enrollment
                $existingEnrollment->update([
                    'enrollment_date' => $validated['enrollment_date'],
                    'status' => $validated['status'],
                ]);
                $enrollment = $existingEnrollment;

                EnrollmentDetail::updateOrCreate(
                    ['enrollment_id' => $enrollment->id],
                    [
                        'class_id' => $class->id,
                        'section_id' => $class->section_id,
                        'roll_number' => null,
                    ]
                );
            } else {
                $enrollment = Enrollment::create([
                    'student_id' => $validated['student_id'],
                    'academic_year_id' => $validated['academic_year_id'],
                    'enrollment_date' => $validated['enrollment_date'],
                    'status' => $validated['status'],
                ]);

                EnrollmentDetail::create([
                    'enrollment_id' => $enrollment->id,
                    'class_id' => $class->id,
                    'section_id' => $class->section_id,
                    'roll_number' => null,
                ]);
            }

            // Cancel any pending or completed withdrawal record since student is now re-enrolled
            Withdrawal::where('student_id', $validated['student_id'])
                ->whereIn('status', ['Pending', 'Completed', 'Approved'])
                ->update(['status' => 'Cancelled']);

            // Synchronize Master Student placement
            $student = Student::find($validated['student_id']);
            if ($student && in_array(strtolower($validated['status']), ['active', 'enrolled'])) {
                $updateData = [
                    'school_class_id' => $class->id,
                    'section_id' => $class->section_id ?? $student->section_id,
                    'academic_year_id' => $validated['academic_year_id'],
                ];
                if (! empty($validated['school_shift_id'])) {
                    $updateData['school_shift_id'] = $validated['school_shift_id'];
                }
                $activeStatusId = StudentStatus::whereRaw('LOWER(name) = ?', ['active'])->value('id');
                if ($activeStatusId) {
                    $updateData['student_status_id'] = $activeStatusId;
                }
                $student->update($updateData);
            }
        });

        return redirect()->route('enrollments.index')->with('success', 'Student enrolled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment)
    {
        $this->authorizeEnrollmentView();

        $enrollment->load(['student', 'academic_year', 'enrollmentDetail.class', 'enrollmentDetail.section']);

        return view('enrollment.enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
        $this->authorizeEnrollmentManagement();

        $enrollment->load(['enrollmentDetail']);
        $students = Student::with('user')->get()->sortBy(function ($student) {
            return $student->user ? $student->user->name : '';
        });
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::with('grade', 'section')->get();

        return view('enrollment.enrollments.edit', compact('enrollment', 'students', 'academicYears', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorizeEnrollmentManagement();

        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|string',
            'class_id' => 'required|exists:school_classes,id',
        ]);

        DB::transaction(function () use ($validated, $enrollment) {
            // Prevent duplicate enrollment for the same student and academic year
            $exists = Enrollment::where('student_id', $enrollment->student_id)
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('id', '!=', $enrollment->id)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'academic_year_id' => 'This student is already enrolled for the selected academic year.',
                ]);
            }

            $enrollment->update([
                'academic_year_id' => $validated['academic_year_id'],
                'enrollment_date' => $validated['enrollment_date'],
                'status' => $validated['status'],
            ]);

            $class = SchoolClass::find($validated['class_id']);

            if ($enrollment->enrollmentDetail) {
                $enrollment->enrollmentDetail->update([
                    'class_id' => $class->id,
                    'section_id' => $class->section_id,
                ]);
            } else {
                EnrollmentDetail::create([
                    'enrollment_id' => $enrollment->id,
                    'class_id' => $class->id,
                    'section_id' => $class->section_id,
                ]);
            }

            // Synchronize Master Student placement if enrollment is active
            $student = $enrollment->student;
            if ($student && in_array(strtolower($validated['status']), ['active', 'enrolled'])) {
                $student->update([
                    'school_class_id' => $class->id,
                    'section_id' => $class->section_id ?? $student->section_id,
                    'academic_year_id' => $validated['academic_year_id'],
                ]);
            }
        });

        return redirect()->route('enrollments.index')->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $this->authorizeEnrollmentManagement();

        DB::transaction(function () use ($enrollment) {
            if ($enrollment->enrollmentDetail) {
                $enrollment->enrollmentDetail->delete();
            }
            $enrollment->delete();
        });

        return redirect()->route('enrollments.index')->with('success', 'Enrollment deleted successfully.');
    }
}
