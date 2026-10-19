<?php

namespace App\Http\Controllers\Enrollment;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Enrollment\Enrollment;
use App\Models\Enrollment\Withdrawal;
use App\Models\Students\Student;
use App\Models\Students\StudentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    use AuthorizesEnrollmentAccess;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeEnrollmentView();

        $query = Withdrawal::with([
            'student.user',
            'student.profile',
            'student.address',
            'student.guardians.user',
            'student.schoolClass.grade',
            'student.schoolClass.section',
            'student.academicYear',
            'student.enrollments.enrollmentDetail.class.grade',
            'student.enrollments.enrollmentDetail.class.section',
            'student.enrollments.academicYear',
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

        $withdrawals = $query->orderBy('withdrawal_date', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => Withdrawal::count(),
            'pending' => Withdrawal::where('status', 'Pending')->count(),
            'completed' => Withdrawal::whereIn('status', ['Completed', 'Approved'])->count(),
            'this_month' => Withdrawal::whereMonth('withdrawal_date', now()->month)
                ->whereYear('withdrawal_date', now()->year)
                ->count(),
        ];

        return view('enrollment.withdrawals.index', compact('withdrawals', 'stats'));
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

        return view('enrollment.withdrawals.create', compact('students', 'classes', 'studentsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeEnrollmentManagement();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'nullable',
            'withdrawal_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
        ], [
            'student_id.required' => 'សូមជ្រើសរើសសិស្ស',
            'withdrawal_date.required' => 'សូមបញ្ជាក់កាលបរិច្ឆេទឈប់រៀន',
        ]);

        $data = [
            'student_id' => $validated['student_id'],
            'withdrawal_date' => $validated['withdrawal_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => $validated['status'],
        ];

        DB::transaction(function () use ($data) {
            $withdrawal = Withdrawal::create($data);

            if (in_array(strtolower($data['status']), ['completed', 'approved'])) {
                $student = Student::find($data['student_id']);
                if ($student) {
                    $withdrawnStatusId = StudentStatus::whereRaw('LOWER(name) = ?', ['withdrawn'])->value('id');
                    if ($withdrawnStatusId) {
                        $student->update(['student_status_id' => $withdrawnStatusId]);
                    }

                    Enrollment::where('student_id', $student->id)
                        ->where('status', 'Active')
                        ->update(['status' => 'Withdrawn']);
                }
            }
        });

        return redirect()->route('withdrawals.index')
            ->with('success', 'Student withdrawal recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Withdrawal $withdrawal)
    {
        $this->authorizeEnrollmentView();

        $withdrawal->load([
            'student.user',
            'student.profile',
            'student.address',
            'student.guardians.user',
            'student.schoolClass.grade',
            'student.schoolClass.section',
            'student.academicYear',
            'student.enrollments.enrollmentDetail.class.grade',
            'student.enrollments.enrollmentDetail.class.section',
            'student.enrollments.academicYear',
        ]);

        return view('enrollment.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdrawal $withdrawal)
    {
        $this->authorizeEnrollmentManagement();

        $withdrawal->load([
            'student.user',
            'student.schoolClass.grade',
            'student.schoolClass.section',
        ]);

        $students = Student::with('user')->get()->sortBy(function ($student) {
            return $student->user ? $student->user->name : '';
        });

        return view('enrollment.withdrawals.edit', compact('withdrawal', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Withdrawal $withdrawal)
    {
        $this->authorizeEnrollmentManagement();

        $validated = $request->validate([
            'withdrawal_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        DB::transaction(function () use ($validated, $withdrawal) {
            $withdrawal->update($validated);

            if (in_array(strtolower($validated['status']), ['completed', 'approved'])) {
                $student = $withdrawal->student;
                if ($student) {
                    $withdrawnStatusId = StudentStatus::whereRaw('LOWER(name) = ?', ['withdrawn'])->value('id');
                    if ($withdrawnStatusId) {
                        $student->update(['student_status_id' => $withdrawnStatusId]);
                    }

                    Enrollment::where('student_id', $student->id)
                        ->where('status', 'Active')
                        ->update(['status' => 'Withdrawn']);
                }
            } elseif (strtolower($validated['status']) === 'cancelled') {
                $student = $withdrawal->student;
                if ($student) {
                    $activeStatusId = StudentStatus::whereRaw('LOWER(name) = ?', ['active'])->value('id');
                    if ($activeStatusId) {
                        $student->update(['student_status_id' => $activeStatusId]);
                    }

                    Enrollment::where('student_id', $student->id)
                        ->where('status', 'Withdrawn')
                        ->orderByDesc('id')
                        ->limit(1)
                        ->update(['status' => 'Active']);
                }
            }
        });

        return redirect()->route('withdrawals.index')
            ->with('success', 'Student withdrawal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdrawal $withdrawal)
    {
        $this->authorizeEnrollmentManagement();

        DB::transaction(function () use ($withdrawal) {
            $student = $withdrawal->student;
            $withdrawal->delete();

            if ($student && ! Withdrawal::where('student_id', $student->id)->whereIn('status', ['Completed', 'Approved'])->exists()) {
                $activeStatusId = StudentStatus::whereRaw('LOWER(name) = ?', ['active'])->value('id');
                if ($activeStatusId) {
                    $student->update(['student_status_id' => $activeStatusId]);
                }

                Enrollment::where('student_id', $student->id)
                    ->where('status', 'Withdrawn')
                    ->orderByDesc('id')
                    ->limit(1)
                    ->update(['status' => 'Active']);
            }
        });

        return redirect()->route('withdrawals.index')
            ->with('success', 'Student withdrawal deleted successfully.');
    }
}
