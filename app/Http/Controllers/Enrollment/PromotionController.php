<?php

namespace App\Http\Controllers\Enrollment;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Core\AcademicYear;
use App\Models\Enrollment\Enrollment;
use App\Models\Enrollment\EnrollmentDetail;
use App\Models\Enrollment\StudentPromotion;
use App\Models\Students\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    use AuthorizesEnrollmentAccess;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeEnrollmentView();
        $query = StudentPromotion::with([
            'student.user',
            'from_academic_year',
            'to_academic_year',
            'from_class.grade',
            'from_class.section',
            'to_class.grade',
            'to_class.section',
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

        if ($request->filled('academic_year_id')) {
            $query->where('to_academic_year_id', $request->academic_year_id);
        }

        // Filter by Class (ថ្នាក់រៀនដែលឡើងទៅ - To Class)
        if ($request->filled('class_id')) {
            $query->where('to_class_id', $request->class_id);
        }

        // Filter by Shift (វេនព្រឹក / វេនរសៀល នៃថ្នាក់ឡើងទៅ)
        $shift = $request->input('shift');
        if ($shift === 'morning') {
            $query->whereHas('to_class.section', function ($sq) {
                $sq->where('name', 'like', '%ព្រឹក%')->orWhere('name', 'like', '%morning%');
            });
        } elseif ($shift === 'afternoon') {
            $query->whereHas('to_class.section', function ($sq) {
                $sq->where('name', 'like', '%រសៀល%')->orWhere('name', 'like', '%afternoon%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $groupBy = $request->input('group_by', 'to_class');
        if ($groupBy === 'from_class') {
            $query->orderBy('from_class_id')
                ->orderBy('to_class_id')
                ->orderBy('promotion_date', 'desc');
        } else {
            $query->orderBy('to_class_id')
                ->orderBy('from_class_id')
                ->orderBy('promotion_date', 'desc');
        }

        $promotions = $query->paginate(50)
            ->withQueryString();

        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        $classes = SchoolClass::with('grade', 'section')
            ->get()
            ->sortBy(fn ($c) => ($c->grade?->level ?? 0).($c->section?->name ?? ''));

        return view('enrollment.promotions.index', compact('promotions', 'academicYears', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeEnrollmentManagement();

        $students = Student::with(['user', 'enrollments.enrollmentDetail', 'schoolClass', 'academicYear'])
            ->get()
            ->sortBy(function ($student) {
                return $student->user ? $student->user->name : '';
            });
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::with('grade', 'section')->get();

        return view('enrollment.promotions.create', compact('students', 'academicYears', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeEnrollmentManagement();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'from_academic_year_id' => 'required|exists:academic_years,id',
            'to_academic_year_id' => 'required|exists:academic_years,id',
            'from_class_id' => 'required|exists:school_classes,id',
            'to_class_id' => 'required|exists:school_classes,id',
            'promotion_date' => 'required|date',
            'status' => 'required|in:Promoted,Demoted,Transferred,Pending',
        ], [
            'to_academic_year_id.required' => 'សូមជ្រើសរើសឆ្នាំសិក្សាថ្មី',
            'from_academic_year_id.required' => 'សូមជ្រើសរើសឆ្នាំសិក្សាចាស់',
            'to_class_id.required' => 'សូមជ្រើសរើសថ្នាក់រៀនថ្មី',
            'from_class_id.required' => 'សូមជ្រើសរើសថ្នាក់រៀនចាស់',
        ]);

        if ($request->from_academic_year_id == $request->to_academic_year_id && $request->from_class_id == $request->to_class_id) {
            return back()->withErrors([
                'to_class_id' => 'ថ្នាក់រៀន ឬឆ្នាំសិក្សាថ្មី ត្រូវតែខុសពីថ្នាក់ និងឆ្នាំសិក្សាចាស់ (មិនអាចដូចគ្នាទាំងស្រុងបានទេ)។',
            ])->withInput();
        }

        DB::transaction(function () use ($validated) {
            $promotion = StudentPromotion::create($validated);

            if (in_array($validated['status'], ['Promoted', 'Demoted', 'Transferred'])) {
                $student = Student::findOrFail($validated['student_id']);
                $toClass = SchoolClass::findOrFail($validated['to_class_id']);

                // 1. Synchronize current student placement
                $student->update([
                    'school_class_id' => $toClass->id,
                    'section_id' => $toClass->section_id ?? $student->section_id,
                    'academic_year_id' => $validated['to_academic_year_id'],
                ]);

                // 2. Update previous academic year's active enrollment to Promoted/Demoted/Transferred (only if moving to a different year)
                if ($validated['from_academic_year_id'] != $validated['to_academic_year_id']) {
                    Enrollment::where('student_id', $student->id)
                        ->where('academic_year_id', $validated['from_academic_year_id'])
                        ->where('status', 'Active')
                        ->update(['status' => $validated['status']]);
                }

                // 3. Create or update destination academic year enrollment
                $newEnrollment = Enrollment::where('student_id', $student->id)
                    ->where('academic_year_id', $validated['to_academic_year_id'])
                    ->first();

                if ($newEnrollment) {
                    $newEnrollment->update([
                        'enrollment_date' => $validated['promotion_date'],
                        'status' => 'Active',
                    ]);
                    if ($newEnrollment->enrollmentDetail) {
                        $newEnrollment->enrollmentDetail->update([
                            'class_id' => $toClass->id,
                            'section_id' => $toClass->section_id,
                        ]);
                    } else {
                        EnrollmentDetail::create([
                            'enrollment_id' => $newEnrollment->id,
                            'class_id' => $toClass->id,
                            'section_id' => $toClass->section_id,
                        ]);
                    }
                } else {
                    $newEnrollment = Enrollment::create([
                        'student_id' => $student->id,
                        'academic_year_id' => $validated['to_academic_year_id'],
                        'enrollment_date' => $validated['promotion_date'],
                        'status' => 'Active',
                    ]);
                    EnrollmentDetail::create([
                        'enrollment_id' => $newEnrollment->id,
                        'class_id' => $toClass->id,
                        'section_id' => $toClass->section_id,
                    ]);
                }
            }
        });

        return redirect()->route('promotions.index')
            ->with('success', 'Student promotion recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentPromotion $promotion)
    {
        $this->authorizeEnrollmentView();

        $promotion->load([
            'student.user',
            'from_academic_year',
            'to_academic_year',
            'from_class.grade',
            'from_class.section',
            'to_class.grade',
            'to_class.section',
        ]);

        return view('enrollment.promotions.show', compact('promotion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentPromotion $promotion)
    {
        $this->authorizeEnrollmentManagement();

        $students = Student::with('user')->get()->sortBy(function ($student) {
            return $student->user ? $student->user->name : '';
        });
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::with('grade', 'section')->get();

        return view('enrollment.promotions.edit', compact('promotion', 'students', 'academicYears', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentPromotion $promotion)
    {
        $this->authorizeEnrollmentManagement();

        $validated = $request->validate([
            'from_academic_year_id' => 'required|exists:academic_years,id',
            'to_academic_year_id' => 'required|exists:academic_years,id',
            'from_class_id' => 'required|exists:school_classes,id',
            'to_class_id' => 'required|exists:school_classes,id',
            'promotion_date' => 'required|date',
            'status' => 'required|in:Promoted,Demoted,Transferred,Pending',
        ], [
            'to_academic_year_id.required' => 'សូមជ្រើសរើសឆ្នាំសិក្សាថ្មី',
            'from_academic_year_id.required' => 'សូមជ្រើសរើសឆ្នាំសិក្សាចាស់',
            'to_class_id.required' => 'សូមជ្រើសរើសថ្នាក់រៀនថ្មី',
            'from_class_id.required' => 'សូមជ្រើសរើសថ្នាក់រៀនចាស់',
        ]);

        if ($request->from_academic_year_id == $request->to_academic_year_id && $request->from_class_id == $request->to_class_id) {
            return back()->withErrors([
                'to_class_id' => 'ថ្នាក់រៀន ឬឆ្នាំសិក្សាថ្មី ត្រូវតែខុសពីថ្នាក់ និងឆ្នាំសិក្សាចាស់ (មិនអាចដូចគ្នាទាំងស្រុងបានទេ)។',
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $promotion) {
            $promotion->update($validated);

            if (in_array($validated['status'], ['Promoted', 'Demoted', 'Transferred'])) {
                $student = $promotion->student;
                $toClass = SchoolClass::find($validated['to_class_id']);

                if ($student && $toClass) {
                    $student->update([
                        'school_class_id' => $toClass->id,
                        'section_id' => $toClass->section_id ?? $student->section_id,
                        'academic_year_id' => $validated['to_academic_year_id'],
                    ]);

                    if ($validated['from_academic_year_id'] != $validated['to_academic_year_id']) {
                        Enrollment::where('student_id', $student->id)
                            ->where('academic_year_id', $validated['from_academic_year_id'])
                            ->where('status', 'Active')
                            ->update(['status' => $validated['status']]);
                    }

                    $newEnrollment = Enrollment::where('student_id', $student->id)
                        ->where('academic_year_id', $validated['to_academic_year_id'])
                        ->first();

                    if ($newEnrollment) {
                        $newEnrollment->update([
                            'enrollment_date' => $validated['promotion_date'],
                            'status' => 'Active',
                        ]);
                        if ($newEnrollment->enrollmentDetail) {
                            $newEnrollment->enrollmentDetail->update([
                                'class_id' => $toClass->id,
                                'section_id' => $toClass->section_id,
                            ]);
                        } else {
                            EnrollmentDetail::create([
                                'enrollment_id' => $newEnrollment->id,
                                'class_id' => $toClass->id,
                                'section_id' => $toClass->section_id,
                            ]);
                        }
                    } else {
                        $newEnrollment = Enrollment::create([
                            'student_id' => $student->id,
                            'academic_year_id' => $validated['to_academic_year_id'],
                            'enrollment_date' => $validated['promotion_date'],
                            'status' => 'Active',
                        ]);
                        EnrollmentDetail::create([
                            'enrollment_id' => $newEnrollment->id,
                            'class_id' => $toClass->id,
                            'section_id' => $toClass->section_id,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('promotions.index')
            ->with('success', 'Student promotion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentPromotion $promotion)
    {
        $this->authorizeEnrollmentManagement();

        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', 'Student promotion deleted successfully.');
    }
}
