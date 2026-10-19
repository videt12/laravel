<?php

namespace App\Http\Controllers;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolShift;
use App\Models\Enrollment\Enrollment;
use App\Models\Enrollment\EnrollmentDetail;
use App\Models\Finance\StudentInvoice;
use App\Models\Finance\StudentPayment;
use App\Models\Students\Guardian;
use App\Models\Students\Student;
use App\Models\Students\StudentAddresse;
use App\Models\Students\StudentCategory;
use App\Models\Students\StudentProfile;
use App\Models\Students\StudentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Authorize administrative student management (create, edit, delete).
     */
    protected function authorizeStudentManagement(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        $adminRoles = ['admin', 'super-admin', 'super_admin', 'principal'];
        $isAuthorized = $user->roles()
            ->where(function ($q) use ($adminRoles) {
                $q->whereIn('slug', $adminRoles)
                    ->orWhereIn(DB::raw('LOWER(name)'), ['admin', 'super admin', 'principal']);
            })->exists();

        if (! $isAuthorized) {
            abort(403, 'Unauthorized access: You do not have permission to modify student records.');
        }
    }

    /**
     * Authorize viewing student directory list.
     * Staff (admin, principal, teacher, accountant, librarian) can access.
     */
    protected function authorizeStudentViewIndex(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        if (! $user->canAccess(['students.view', 'students'])) {
            abort(403, 'Unauthorized access: You do not have permission to view the student directory.');
        }
    }

    /**
     * Authorize viewing specific student 360 record (IDOR Protection).
     * Staff with permission can view all students.
     * Student can only view self.
     * Guardian can only view linked child.
     */
    protected function authorizeStudentView(Student $student): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Unauthorized access: Please log in.');
        }

        if ($user->canAccess(['students.view', 'students'])) {
            return;
        }

        // Check if student viewing own record
        $isOwnRecord = ($student->user_id && (int) $student->user_id === (int) $user->id)
            || ($user->student && (int) $user->student->id === (int) $student->id);

        if ($isOwnRecord) {
            return;
        }

        // Check if guardian viewing their child
        $isGuardianOfStudent = $student->guardians()
            ->where(function ($gq) use ($user) {
                $gq->where('guardians.user_id', $user->id)
                    ->orWhereHas('user', fn ($uq) => $uq->where('id', $user->id));
            })->exists();

        if ($isGuardianOfStudent) {
            return;
        }

        abort(403, 'Unauthorized access: You cannot view this student record.');
    }

    /**
     * Display a paginated list of students with optional search and filters.
     */
    public function index(Request $request)
    {
        $this->authorizeStudentViewIndex();

        $search = $request->input('search');
        $filterStatus = $request->input('status');
        $filterGender = $request->input('gender');
        $filterAcademicYear = $request->input('academic_year_id');
        $filterClass = $request->input('class_id');
        $filterSection = $request->input('section_id');
        $filterStudentType = $request->input('student_type'); // 'new', 'returning'
        $filterPaymentStatus = $request->input('payment_status'); // 'paid', 'partial', 'unpaid', 'overdue'
        $filterStudyMode = $request->input('study_mode'); // 'full_time', 'part_time'

        // Determine context academic year
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $defaultActiveYear = $academicYears->firstWhere('is_active', 1) ?? $academicYears->first();
        $selectedYearId = $filterAcademicYear ? (int) $filterAcademicYear : $defaultActiveYear?->id;
        $selectedYear = $academicYears->firstWhere('id', $selectedYearId);

        $today = now()->toDateString();

        $query = Student::query()
            ->with([
                'user',
                'studentProfile',
                'studentStatus',
                'studentCategory',
                'academicYear',
                'schoolClass.grade',
                'schoolClass.section',
                'section',
                'enrollments.academic_year',
            ])
            ->withSum([
                'student_invoices as total_invoiced' => function ($invQ) use ($selectedYearId) {
                    $invQ->where('status', '!=', 'cancelled')
                        ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                            $sq->where(function ($sub) use ($selectedYearId) {
                                $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                                    ->orWhereNull('fee_structure_id');
                            });
                        });
                },
            ], 'net_amount')
            ->withSum([
                'student_payments as total_paid' => function ($payQ) use ($selectedYearId) {
                    $payQ->whereHas('student_invoice', function ($invQ) use ($selectedYearId) {
                        $invQ->where('status', '!=', 'cancelled')
                            ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                                $sq->where(function ($sub) use ($selectedYearId) {
                                    $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                                        ->orWhereNull('fee_structure_id');
                                });
                            });
                    });
                },
            ], 'amount')
            ->withExists([
                'student_invoices as has_overdue' => function ($invQ) use ($today, $selectedYearId) {
                    $invQ->where('status', '!=', 'cancelled')
                        ->where('status', '!=', 'paid')
                        ->whereDate('due_date', '<', $today)
                        ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                            $sq->where(function ($sub) use ($selectedYearId) {
                                $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                                    ->orWhereNull('fee_structure_id');
                            });
                        });
                },
            ]);

        // Search Filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                    ->orWhere('roll_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Student Status Filter
        if ($filterStatus) {
            $query->where('student_status_id', $filterStatus);
        }

        // Gender Filter
        if ($filterGender) {
            $query->whereHas('studentProfile', function ($q) use ($filterGender) {
                $q->where('gender', $filterGender);
            });
        }

        // Academic Year Filter
        if ($filterAcademicYear) {
            $query->where(function ($q) use ($filterAcademicYear) {
                $q->where('academic_year_id', $filterAcademicYear)
                    ->orWhereHas('enrollments', fn ($eq) => $eq->where('academic_year_id', $filterAcademicYear));
            });
        }

        // Class Filter
        if ($filterClass) {
            $query->where(function ($q) use ($filterClass, $selectedYearId) {
                $q->where('school_class_id', $filterClass)
                    ->orWhereHas('enrollments', function ($eq) use ($filterClass, $selectedYearId) {
                        $eq->where('status', 'Active')
                            ->when($selectedYearId, fn ($yq) => $yq->where('academic_year_id', $selectedYearId))
                            ->whereHas('enrollmentDetail', fn ($ed) => $ed->where('class_id', $filterClass));
                    });
            });
        }

        // Section Filter
        if ($filterSection) {
            $query->where(function ($q) use ($filterSection) {
                $q->where('section_id', $filterSection)
                    ->orWhereHas('schoolClass', fn ($cq) => $cq->where('section_id', $filterSection));
            });
        }

        // New / Returning Filter
        if ($filterStudentType) {
            $contextYearId = $filterAcademicYear ? (int) $filterAcademicYear : null;
            if ($filterStudentType === 'new') {
                $query->whereNewStudent($contextYearId);
            } elseif ($filterStudentType === 'returning') {
                $query->whereReturningStudent($contextYearId);
            }
        }

        // Payment Status Filter (using indexed status on student_invoices)
        if ($filterPaymentStatus) {
            $invoiceYearScope = function ($sq) use ($selectedYearId) {
                $sq->when($selectedYearId, function ($sub) use ($selectedYearId) {
                    $sub->where(function ($yq) use ($selectedYearId) {
                        $yq->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                            ->orWhereNull('fee_structure_id');
                    });
                });
            };

            if ($filterPaymentStatus === 'paid') {
                $query->whereHas('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->where('status', 'paid');
                    $invoiceYearScope($q);
                })->whereDoesntHave('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->whereNotIn('status', ['paid', 'cancelled']);
                    $invoiceYearScope($q);
                });
            } elseif ($filterPaymentStatus === 'partial') {
                $query->whereHas('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->where('status', 'partial');
                    $invoiceYearScope($q);
                });
            } elseif ($filterPaymentStatus === 'unpaid') {
                $query->whereHas('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->where('status', 'unpaid');
                    $invoiceYearScope($q);
                })->whereDoesntHave('student_invoices', function ($q) use ($invoiceYearScope) {
                    $q->whereIn('status', ['partial', 'paid']);
                    $invoiceYearScope($q);
                });
            } elseif ($filterPaymentStatus === 'overdue') {
                $query->whereHas('student_invoices', function ($q) use ($today, $invoiceYearScope) {
                    $q->whereNotIn('status', ['paid', 'cancelled'])
                        ->whereDate('due_date', '<', $today);
                    $invoiceYearScope($q);
                });
            }
        }

        // Study Mode Filter (full_time, part_time)
        if ($filterStudyMode) {
            $query->where('study_mode', $filterStudyMode);
        }

        // Calculate Summary Metrics respecting filtered query
        $summaryQuery = clone $query;
        $totalStudents = (clone $summaryQuery)->count('students.id');
        $contextYearId = $filterAcademicYear ? (int) $filterAcademicYear : null;
        $newStudentsCount = (clone $summaryQuery)->whereNewStudent($contextYearId)->count('students.id');
        $returningStudentsCount = (clone $summaryQuery)->whereReturningStudent($contextYearId)->count('students.id');
        $fullTimeCount = (clone $summaryQuery)->where(function ($q) {
            $q->where('study_mode', 'full_time')->orWhereNull('study_mode');
        })->count('students.id');
        $partTimeCount = (clone $summaryQuery)->where('study_mode', 'part_time')->count('students.id');

        $filteredStudentIds = (clone $summaryQuery)->pluck('students.id');
        $totalInvoicedSum = StudentInvoice::whereIn('student_id', $filteredStudentIds)
            ->where('status', '!=', 'cancelled')
            ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                $sq->where(function ($sub) use ($selectedYearId) {
                    $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                        ->orWhereNull('fee_structure_id');
                });
            })
            ->sum('net_amount');

        $totalPaidSum = StudentPayment::whereHas('student_invoice', function ($invQ) use ($filteredStudentIds, $selectedYearId) {
            $invQ->whereIn('student_id', $filteredStudentIds)
                ->where('status', '!=', 'cancelled')
                ->when($selectedYearId, function ($sq) use ($selectedYearId) {
                    $sq->where(function ($sub) use ($selectedYearId) {
                        $sub->whereHas('fee_structure', fn ($fsq) => $fsq->where('academic_year_id', $selectedYearId))
                            ->orWhereNull('fee_structure_id');
                    });
                });
        })->sum('amount');

        $totalOutstandingBalance = max(0.0, round((float) $totalInvoicedSum - (float) $totalPaidSum, 2));

        // Paginate results
        $students = $query->latest('students.id')
            ->paginate(15)
            ->withQueryString();

        // Process dynamic attributes on the paginated slice
        foreach ($students as $student) {
            $invoiced = (float) ($student->total_invoiced ?? 0);
            $paid = (float) ($student->total_paid ?? 0);
            $balance = max(0.0, round($invoiced - $paid, 2));

            if ($student->has_overdue) {
                $pStatus = 'overdue';
            } elseif ($invoiced > 0 && $paid >= $invoiced) {
                $pStatus = 'paid';
            } elseif ($paid > 0) {
                $pStatus = 'partial';
            } else {
                $pStatus = 'unpaid';
            }

            $student->calculated_invoiced = $invoiced;
            $student->calculated_paid = $paid;
            $student->calculated_balance = $balance;
            $student->calculated_payment_status = $pStatus;
            $student->calculated_student_type = $student->getStudentTypeForYear($contextYearId);
        }

        $statuses = StudentStatus::orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->get();
        $sections = Section::orderBy('name')->get();

        $hasFilters = $search || $filterStatus || $filterGender || $filterAcademicYear || $filterClass || $filterSection || $filterStudentType || $filterPaymentStatus || $filterStudyMode;

        return view('students.index', compact(
            'students',
            'search',
            'filterStatus',
            'filterGender',
            'filterAcademicYear',
            'filterClass',
            'filterSection',
            'filterStudentType',
            'filterPaymentStatus',
            'filterStudyMode',
            'selectedYearId',
            'academicYears',
            'classes',
            'sections',
            'statuses',
            'hasFilters',
            'totalStudents',
            'newStudentsCount',
            'returningStudentsCount',
            'fullTimeCount',
            'partTimeCount',
            'totalOutstandingBalance'
        ));
    }

    /**
     * Show the form to create a new student.
     */
    public function create()
    {
        $this->authorizeStudentManagement();

        $statuses = StudentStatus::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->get();
        $sections = Section::orderBy('name')->get();
        $shifts = SchoolShift::orderBy('id')->get();
        $categories = StudentCategory::orderBy('id')->get();

        // Preview the next Student ID — the real generation happens in store().
        $nextStudentId = DB::transaction(fn () => Student::generateNextAdmissionNumber());

        return view('students.create', compact('statuses', 'academicYears', 'classes', 'sections', 'shifts', 'categories', 'nextStudentId'));
    }

    /**
     * Store a newly created student and related records.
     */
    public function store(Request $request)
    {
        $this->authorizeStudentManagement();

        $validated = $request->validate([
            // Basic Info
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'admission_date' => 'required|date',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'first_name_khmer' => 'required|string|max:255',
            'last_name_khmer' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'nationality' => 'nullable|string|max:255',
            'national_id' => ['nullable', 'string', 'max:255', Rule::unique('student_profiles', 'national_id')],

            // Contact & Address
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'village' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:50',

            // Enrollment
            'academic_year_id' => 'required|integer',
            'school_class_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'school_shift_id' => 'nullable|integer',
            'study_mode' => 'nullable|in:full_time,part_time,dual',
            'enrollment_date' => 'required|date',
            'student_status_id' => 'nullable|integer',
            'roll_number' => 'nullable|string|max:50',
            'student_category_id' => 'nullable|integer|exists:student_categories,id',

            // Additional Info
            'blood_group' => 'nullable|string|max:10',
            'previous_school' => 'nullable|string|max:255',
            'special_needs' => 'nullable|string|max:255',
            'medical_information' => 'nullable|string',
            'notes' => 'nullable|string',

            // Guardian / Parents Info
            'father_name' => 'nullable|string|max:255',
            'father_age' => 'nullable|integer|min:15|max:120',
            'father_occupation' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',

            'mother_name' => 'nullable|string|max:255',
            'mother_age' => 'nullable|integer|min:15|max:120',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',

            'primary_guardian' => 'nullable|string|in:father,mother,other',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:50',
            'guardian_age' => 'nullable|integer|min:15|max:120',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_general_email' => 'nullable|email|max:255',
            'guardian_address' => 'nullable|string',
            'guardian_village' => 'nullable|string|max:255',
            'guardian_commune' => 'nullable|string|max:255',
            'guardian_district' => 'nullable|string|max:255',
            'guardian_province' => 'nullable|string|max:255',
            'guardian_is_primary' => 'nullable|boolean',
            'guardian_is_emergency' => 'nullable|boolean',
        ], [
            'first_name_khmer.required' => 'សូមបញ្ចូលនាមខ្លួនជាភាសាខ្មែរ។',
            'last_name_khmer.required' => 'សូមបញ្ចូលនាមត្រកូលជាភាសាខ្មែរ។',
            'admission_date.required' => 'សូមជ្រើសរើសថ្ងៃចូលរៀន។',
            'dob.required' => 'សូមជ្រើសរើសថ្ងៃខែឆ្នាំកំណើត។',
            'dob.before' => 'ថ្ងៃខែឆ្នាំកំណើតត្រូវតែមុនថ្ងៃនេះ។',
            'gender.required' => 'សូមជ្រើសរើសភេទ។',
            'national_id.unique' => 'លេខអត្តសញ្ញាណប័ណ្ណ / សំបុត្រកំណើត «:input» មានរួចហើយក្នុងប្រព័ន្ធ! មិនអាចស្ទួនបានទេ។',
            'email.unique' => 'អ៊ីមែលនេះត្រូវបានប្រើប្រាស់រួចហើយ! សូមជ្រើសរើសអ៊ីមែលផ្សេង។',
            'address_line_1.required' => 'សូមបញ្ចូលអាសយដ្ឋានបច្ចុប្បន្ន។',
            'village.required' => 'សូមបញ្ចូលឈ្មោះភូមិ។',
            'commune.required' => 'សូមបញ្ចូលឈ្មោះឃុំ ឬសង្កាត់។',
            'district.required' => 'សូមបញ្ចូលឈ្មោះស្រុក ឬខណ្ឌ។',
            'province.required' => 'សូមជ្រើសរើសរាជធានី ឬខេត្ត។',
            'academic_year_id.required' => 'សូមជ្រើសរើសឆ្នាំសិក្សា។',
            'school_class_id.required' => 'សូមជ្រើសរើសថ្នាក់រៀន។',
        ]);

        $hasFather = ! empty(trim($request->input('father_name') ?? ''));
        $hasMother = ! empty(trim($request->input('mother_name') ?? ''));
        $hasOther = ! empty(trim($request->input('guardian_name') ?? ''));

        if (! $hasFather && ! $hasMother && ! $hasOther) {
            return back()
                ->withInput()
                ->withErrors([
                    'father_name' => 'សូមបញ្ចូលព័ត៌មានឪពុក ឬម្តាយ ឬអាណាព្យាបាលយ៉ាងហោចណាស់ម្នាក់។',
                ]);
        }

        $hasPhone = ! empty(trim($request->input('father_phone') ?? ''))
            || ! empty(trim($request->input('mother_phone') ?? ''))
            || ! empty(trim($request->input('guardian_phone') ?? ''));

        if (! $hasPhone) {
            return back()
                ->withInput()
                ->withErrors([
                    'father_phone' => 'សូមបញ្ចូលលេខទូរស័ព្ទទំនាក់ទំនងយ៉ាងហោចណាស់មួយខ្សែ (ឪពុក ម្តាយ ឬអាណាព្យាបាល)។',
                ]);
        }

        // ── Check for duplicate student (Same Name + DOB + Gender) ──
        $duplicateProfile = StudentProfile::query()
            ->where('dob', $validated['dob'])
            ->where('gender', $validated['gender'])
            ->where(function ($q) use ($validated) {
                $hasLatin = ! empty($validated['first_name']) && ! empty($validated['last_name']);
                $hasKhmer = ! empty($validated['first_name_khmer']) && ! empty($validated['last_name_khmer']);

                if ($hasLatin) {
                    $q->where(function ($sq) use ($validated) {
                        $sq->whereRaw('LOWER(TRIM(first_name)) = ?', [strtolower(trim($validated['first_name']))])
                            ->whereRaw('LOWER(TRIM(last_name)) = ?', [strtolower(trim($validated['last_name']))]);
                    });
                }
                if ($hasKhmer) {
                    $method = $hasLatin ? 'orWhere' : 'where';
                    $q->$method(function ($sq) use ($validated) {
                        $sq->where('first_name_khmer', trim($validated['first_name_khmer']))
                            ->where('last_name_khmer', trim($validated['last_name_khmer']));
                    });
                }
            })
            ->with('student')
            ->first();

        if ($duplicateProfile && $duplicateProfile->student) {
            $existingId = $duplicateProfile->student->admission_number;
            $existingName = trim(($duplicateProfile->first_name_khmer ?? $duplicateProfile->first_name).' '.($duplicateProfile->last_name_khmer ?? $duplicateProfile->last_name));

            return back()
                ->withInput()
                ->withErrors([
                    'first_name_khmer' => "សិស្សឈ្មោះ «{$existingName}» កើតថ្ងៃទី {$validated['dob']} មានក្នុងប្រព័ន្ធរួចហើយ (អត្តលេខសិស្ស៖ {$existingId})! សូមពិនិត្យមើលឡើងវិញ។",
                ]);
        }

        // ── Check for duplicate Roll Number in the same class and academic year ──
        if (! empty($validated['roll_number'])) {
            $duplicateRoll = Student::query()
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('school_class_id', $validated['school_class_id'])
                ->when(! empty($validated['section_id']), fn ($q) => $q->where('section_id', $validated['section_id']))
                ->where('roll_number', trim($validated['roll_number']))
                ->first();

            if ($duplicateRoll) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'roll_number' => "លេខរៀង (Roll Number) «{$validated['roll_number']}» ត្រូវបានប្រើរួចហើយក្នុងថ្នាក់នេះ (អត្តលេខសិស្ស៖ {$duplicateRoll->admission_number})!",
                    ]);
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $uploadedPhoto = $request->file('photo');
            $uploadedSize = $uploadedPhoto->getSize();
            $uploadedHash = md5_file($uploadedPhoto->getRealPath());

            $existingProfiles = StudentProfile::query()
                ->whereNotNull('photo')
                ->where('photo', '!=', '')
                ->with('student')
                ->get();

            foreach ($existingProfiles as $prof) {
                $fullPath = Storage::disk('public')->path($prof->photo);
                if (file_exists($fullPath) && filesize($fullPath) === $uploadedSize) {
                    if (md5_file($fullPath) === $uploadedHash) {
                        $s = $prof->student;
                        $dupName = trim(($prof->last_name_khmer ?? '').' '.($prof->first_name_khmer ?? ''))
                            ?: trim(($prof->first_name ?? '').' '.($prof->last_name ?? ''));
                        $dupAdm = $s?->admission_number ?? '';

                        return back()
                            ->withInput()
                            ->withErrors([
                                'photo' => "រូបថតនេះត្រូវបានប្រើប្រាស់រួចហើយសម្រាប់សិស្ស «{$dupName}» (អត្តលេខ៖ {$dupAdm})! សូមជ្រើសរើសរូបថតផ្ទាល់ខ្លួនរបស់សិស្ស។",
                            ]);
                    }
                }
            }

            $photoPath = $uploadedPhoto->store('student-photos', 'public');
        }

        DB::transaction(function () use ($validated, $photoPath) {
            $fullName = trim(($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''));
            if ($fullName === '') {
                $fullName = trim(($validated['last_name_khmer'] ?? '').' '.($validated['first_name_khmer'] ?? '')) ?: 'Student';
            }

            // Generate the next BPS##### ID inside the transaction so the lock
            // is held until the student row is committed (concurrency-safe).
            $admissionNumber = Student::generateNextAdmissionNumber();

            // 1. Create or restore User account (using provided email or generating a dummy one)
            $userEmail = $validated['email'] ?? strtolower($admissionNumber).'@school.local';
            $existingStudentUser = User::withTrashed()->where('email', $userEmail)->first();
            if ($existingStudentUser) {
                if ($existingStudentUser->trashed()) {
                    $existingStudentUser->restore();
                }
                $existingStudentUser->update([
                    'name' => $fullName,
                    'status' => 'active',
                ]);
                $user = $existingStudentUser;
            } else {
                $user = User::create([
                    'name' => $fullName,
                    'email' => $userEmail,
                    'password' => bcrypt('student123'),
                    'status' => 'active',
                ]);
            }

            // Auto-assign Student role to user
            $studentRoleId = DB::table('roles')->where('slug', 'student')->value('id');
            if ($studentRoleId) {
                $user->roles()->syncWithoutDetaching([$studentRoleId]);
            }

            $selectedClass = SchoolClass::find($validated['school_class_id']);
            $activeStatusId = StudentStatus::where('name', 'Active')->value('id') ?? 1;

            // 2. Create Student record
            $student = Student::create([
                'user_id' => $user->id,
                'admission_number' => $admissionNumber,
                'admission_date' => $validated['admission_date'],
                'enrollment_date' => $validated['enrollment_date'] ?? $validated['admission_date'],
                'roll_number' => $validated['roll_number'] ?? null,
                'student_status_id' => $validated['student_status_id'] ?? $activeStatusId,
                'student_category_id' => $validated['student_category_id'] ?? null,
                'academic_year_id' => $validated['academic_year_id'],
                'school_class_id' => $validated['school_class_id'],
                'section_id' => $validated['section_id'] ?? $selectedClass?->section_id,
                'school_shift_id' => $validated['school_shift_id'] ?? null,
                'study_mode' => $validated['study_mode'] ?? 'full_time',
            ]);

            // 3. Create StudentProfile
            $student->studentProfile()->create([
                'photo' => $photoPath,
                'phone' => $validated['phone'] ?? null,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'first_name_khmer' => $validated['first_name_khmer'] ?? null,
                'last_name_khmer' => $validated['last_name_khmer'] ?? null,
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                'blood_group' => $validated['blood_group'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'national_id' => $validated['national_id'] ?? null,
                'previous_school' => $validated['previous_school'] ?? null,
                'special_needs' => $validated['special_needs'] ?? null,
                'medical_information' => $validated['medical_information'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // 4. Create StudentAddresse
            $student->studentAddresse()->create([
                'address_line_1' => $validated['address_line_1'] ?? null,
                'address_line_2' => $validated['address_line_2'] ?? null,
                'village' => $validated['village'] ?? null,
                'commune' => $validated['commune'] ?? null,
                'district' => $validated['district'] ?? null,
                'province' => $validated['province'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
            ]);

            // 5. Create Guardians (Father, Mother, Other)
            $guardianAddress = $validated['guardian_address'] ?? null;
            if (empty($guardianAddress)) {
                $addrParts = array_filter([
                    ! empty($validated['guardian_village']) ? 'ភូមិ '.$validated['guardian_village'] : null,
                    ! empty($validated['guardian_commune']) ? 'ឃុំ/សង្កាត់ '.$validated['guardian_commune'] : null,
                    ! empty($validated['guardian_district']) ? 'ស្រុក/ខណ្ឌ '.$validated['guardian_district'] : null,
                    $validated['guardian_province'] ?? null,
                ]);
                $guardianAddress = ! empty($addrParts) ? implode(', ', $addrParts) : null;
            }

            $primaryChoice = $validated['primary_guardian'] ?? 'father';
            $guardianEmail = $validated['guardian_email'] ?? ($validated['guardian_general_email'] ?? null);

            $hasFather = ! empty($validated['father_name']);
            $hasMother = ! empty($validated['mother_name']);
            $hasOther = ! empty($validated['guardian_name']);

            $isFatherPrimary = ($primaryChoice === 'father') || (! $hasMother && ! $hasOther);
            $isMotherPrimary = ($primaryChoice === 'mother') || (! $hasFather && ! $hasOther && $primaryChoice !== 'father');
            $isOtherPrimary = ($primaryChoice === 'other') || (! $hasFather && ! $hasMother);

            if ($hasFather) {
                $this->syncStudentGuardian(
                    $student,
                    $validated['father_name'],
                    'Father',
                    $validated['father_phone'] ?? null,
                    $isFatherPrimary ? $guardianEmail : null,
                    $validated['father_occupation'] ?? null,
                    isset($validated['father_age']) ? (int) $validated['father_age'] : null,
                    $guardianAddress,
                    $isFatherPrimary,
                    true
                );
            }

            if ($hasMother) {
                $this->syncStudentGuardian(
                    $student,
                    $validated['mother_name'],
                    'Mother',
                    $validated['mother_phone'] ?? null,
                    $isMotherPrimary ? $guardianEmail : null,
                    $validated['mother_occupation'] ?? null,
                    isset($validated['mother_age']) ? (int) $validated['mother_age'] : null,
                    $guardianAddress,
                    $isMotherPrimary,
                    true
                );
            }

            if ($hasOther) {
                $this->syncStudentGuardian(
                    $student,
                    $validated['guardian_name'],
                    $validated['guardian_relationship'] ?? 'Other',
                    $validated['guardian_phone'] ?? null,
                    $isOtherPrimary ? $guardianEmail : null,
                    $validated['guardian_occupation'] ?? null,
                    isset($validated['guardian_age']) ? (int) $validated['guardian_age'] : null,
                    $guardianAddress,
                    $isOtherPrimary,
                    true
                );
            }

            // 7. Initial Active Enrollment (Application-level duplicate prevention)
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('academic_year_id', $validated['academic_year_id'])
                ->first();

            if (! $existingEnrollment) {
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $validated['academic_year_id'],
                    'enrollment_date' => $validated['enrollment_date'] ?? $validated['admission_date'],
                    'status' => 'Active',
                ]);

                EnrollmentDetail::create([
                    'enrollment_id' => $enrollment->id,
                    'class_id' => $validated['school_class_id'],
                    'section_id' => $validated['section_id'] ?? null,
                    'roll_number' => $validated['roll_number'] ?? null,
                ]);
            }

            session(['_last_student_id' => $student->id]);
        });

        $studentId = session()->pull('_last_student_id');

        return redirect()->route('students.index')
            ->with('success', 'Student registered successfully.');
    }

    /**
     * Display the specified student's full profile.
     */
    public function show(Student $student)
    {
        $this->authorizeStudentView($student);

        $student->load([
            'user',
            'studentProfile',
            'studentAddresse',
            'studentMedicalRecord',
            'guardians.user',
            'guardians.guardian_contacts',
            'student_documents',
            'studentStatus',
            'studentCategory',
            'academicYear',
            'schoolClass.grade',
            'schoolClass.section',
            'section',
            'schoolShift',
            'notes.user',
            'student_invoices.fee_structure.fee_category',
            'student_invoices.fee_structure.academic_year',
            'student_invoices.invoice_details.fee_category',
            'student_invoices.student_payments.payment_method',
        ]);

        $availableGuardians = Guardian::with('user')->get();

        // Finance Overview & History
        $today = now()->toDateString();
        $invoices = $student->student_invoices()
            ->with([
                'fee_structure.fee_category',
                'fee_structure.academic_year',
                'invoice_details.fee_category',
                'student_payments',
            ])
            ->orderByDesc('id')
            ->get();

        $payments = StudentPayment::whereHas('student_invoice', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })
            ->with(['student_invoice', 'payment_method'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();

        $totalInvoiced = (float) $invoices->where('status', '!=', 'cancelled')->sum('net_amount');
        $totalPaid = (float) $payments->sum('amount');
        $outstandingBalance = max(0.0, round($totalInvoiced - $totalPaid, 2));

        $hasOverdue = $invoices->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'paid')
            ->filter(fn ($inv) => $inv->due_date && $inv->due_date->toDateString() < $today)
            ->isNotEmpty();

        if ($hasOverdue) {
            $paymentStatus = 'overdue';
        } elseif ($totalInvoiced > 0 && $totalPaid >= $totalInvoiced) {
            $paymentStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'partial';
        } else {
            $paymentStatus = 'unpaid';
        }

        $lastPaymentDate = $payments->first()?->payment_date?->format('d M Y') ?? '—';
        $studentType = $student->getStudentTypeForYear($student->academic_year_id);
        $statuses = StudentStatus::orderBy('name')->get();

        $attendances = $student->attendances()
            ->with(['attendance_session.academic_year', 'attendance_status'])
            ->latest('id')
            ->take(10)
            ->get();

        $attendanceStats = [
            'total' => $student->attendances()->count(),
            'present' => $student->attendances()->whereHas('attendance_status', fn ($q) => $q->whereRaw('LOWER(name) = ?', ['present']))->count(),
            'absent' => $student->attendances()->whereHas('attendance_status', fn ($q) => $q->whereRaw('LOWER(name) = ?', ['absent']))->count(),
            'late' => $student->attendances()->whereHas('attendance_status', fn ($q) => $q->whereRaw('LOWER(name) = ?', ['late']))->count(),
            'excused' => $student->attendances()->whereHas('attendance_status', fn ($q) => $q->whereRaw('LOWER(name) = ?', ['excused']))->count(),
        ];

        return view('students.show', compact(
            'student',
            'availableGuardians',
            'invoices',
            'payments',
            'totalInvoiced',
            'totalPaid',
            'outstandingBalance',
            'paymentStatus',
            'lastPaymentDate',
            'studentType',
            'statuses',
            'attendances',
            'attendanceStats'
        ));
    }

    /**
     * Show the form to edit an existing student.
     */
    public function edit(Student $student)
    {
        $this->authorizeStudentManagement();

        $student->load([
            'user',
            'studentProfile',
            'studentAddresse',
            'studentMedicalRecord',
            'studentStatus',
            'studentCategory',
            'guardians.user',
            'guardians.guardian_contacts',
            'academicYear',
            'schoolClass.grade',
            'schoolClass.section',
            'section',
            'schoolShift',
        ]);

        $statuses = StudentStatus::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();
        $classes = SchoolClass::with(['grade', 'section'])->get();
        $sections = Section::orderBy('name')->get();
        $shifts = SchoolShift::orderBy('id')->get();
        $categories = StudentCategory::orderBy('id')->get();

        return view('students.edit', compact('student', 'statuses', 'academicYears', 'classes', 'sections', 'shifts', 'categories'));
    }

    /**
     * Update the specified student and related records.
     */
    public function update(Request $request, Student $student)
    {
        $this->authorizeStudentManagement();

        $validated = $request->validate([
            // Basic Info
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'admission_date' => 'required|date',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'first_name_khmer' => 'required|string|max:255',
            'last_name_khmer' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before:today',
            'nationality' => 'nullable|string|max:255',
            'national_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('student_profiles', 'national_id')->ignore($student->studentProfile?->id),
            ],

            // Contact & Address
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'phone' => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'village' => 'required|string|max:255',
            'commune' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:50',

            // Enrollment
            'academic_year_id' => 'required|integer',
            'school_class_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'school_shift_id' => 'nullable|integer',
            'study_mode' => 'nullable|in:full_time,part_time,dual',
            'enrollment_date' => 'required|date',
            'student_status_id' => 'nullable|integer',
            'roll_number' => 'nullable|string|max:50',
            'student_category_id' => 'nullable|integer|exists:student_categories,id',

            // Additional Info
            'blood_group' => 'nullable|string|max:10',
            'previous_school' => 'nullable|string|max:255',
            'special_needs' => 'nullable|string|max:255',
            'medical_information' => 'nullable|string',
            'notes' => 'nullable|string',

            // Guardian / Parents Info
            'father_name' => 'nullable|string|max:255',
            'father_age' => 'nullable|integer|min:15|max:120',
            'father_occupation' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:50',

            'mother_name' => 'nullable|string|max:255',
            'mother_age' => 'nullable|integer|min:15|max:120',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:50',

            'primary_guardian' => 'nullable|string|in:father,mother,other',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:50',
            'guardian_age' => 'nullable|integer|min:15|max:120',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_general_email' => 'nullable|email|max:255',
            'guardian_address' => 'nullable|string',
            'guardian_village' => 'nullable|string|max:255',
            'guardian_commune' => 'nullable|string|max:255',
            'guardian_district' => 'nullable|string|max:255',
            'guardian_province' => 'nullable|string|max:255',
            'guardian_is_primary' => 'nullable|boolean',
            'guardian_is_emergency' => 'nullable|boolean',
        ], [
            'first_name_khmer.required' => 'សូមបញ្ចូលនាមខ្លួនជាភាសាខ្មែរ។',
            'last_name_khmer.required' => 'សូមបញ្ចូលនាមត្រកូលជាភាសាខ្មែរ។',
            'admission_date.required' => 'សូមជ្រើសរើសថ្ងៃចូលរៀន។',
            'dob.required' => 'សូមជ្រើសរើសថ្ងៃខែឆ្នាំកំណើត។',
            'dob.before' => 'ថ្ងៃខែឆ្នាំកំណើតត្រូវតែមុនថ្ងៃនេះ។',
            'gender.required' => 'សូមជ្រើសរើសភេទ។',
            'national_id.unique' => 'លេខអត្តសញ្ញាណប័ណ្ណ / សំបុត្រកំណើត «:input» មានរួចហើយក្នុងប្រព័ន្ធ! មិនអាចស្ទួនបានទេ។',
            'email.unique' => 'អ៊ីមែលនេះត្រូវបានប្រើប្រាស់រួចហើយ! សូមជ្រើសរើសអ៊ីមែលផ្សេង។',
            'address_line_1.required' => 'សូមបញ្ចូលអាសយដ្ឋានបច្ចុប្បន្ន។',
            'village.required' => 'សូមបញ្ចូលឈ្មោះភូមិ។',
            'commune.required' => 'សូមបញ្ចូលឈ្មោះឃុំ ឬសង្កាត់។',
            'district.required' => 'សូមបញ្ចូលឈ្មោះស្រុក ឬខណ្ឌ។',
            'province.required' => 'សូមជ្រើសរើសរាជធានី ឬខេត្ត។',
            'academic_year_id.required' => 'សូមជ្រើសរើសឆ្នាំសិក្សា។',
            'school_class_id.required' => 'សូមជ្រើសរើសថ្នាក់រៀន។',
        ]);

        $hasFather = ! empty(trim($request->input('father_name') ?? ''));
        $hasMother = ! empty(trim($request->input('mother_name') ?? ''));
        $hasOther = ! empty(trim($request->input('guardian_name') ?? ''));

        if (! $hasFather && ! $hasMother && ! $hasOther) {
            return back()
                ->withInput()
                ->withErrors([
                    'father_name' => 'សូមបញ្ចូលព័ត៌មានឪពុក ឬម្តាយ ឬអាណាព្យាបាលយ៉ាងហោចណាស់ម្នាក់។',
                ]);
        }

        $hasPhone = ! empty(trim($request->input('father_phone') ?? ''))
            || ! empty(trim($request->input('mother_phone') ?? ''))
            || ! empty(trim($request->input('guardian_phone') ?? ''));

        if (! $hasPhone) {
            return back()
                ->withInput()
                ->withErrors([
                    'father_phone' => 'សូមបញ្ចូលលេខទូរស័ព្ទទំនាក់ទំនងយ៉ាងហោចណាស់មួយខ្សែ (ឪពុក ម្តាយ ឬអាណាព្យាបាល)។',
                ]);
        }

        // ── Check for duplicate student (Same Name + DOB + Gender), excluding current student ──
        $duplicateProfile = StudentProfile::query()
            ->where('student_id', '!=', $student->id)
            ->where('dob', $validated['dob'])
            ->where('gender', $validated['gender'])
            ->where(function ($q) use ($validated) {
                $hasLatin = ! empty($validated['first_name']) && ! empty($validated['last_name']);
                $hasKhmer = ! empty($validated['first_name_khmer']) && ! empty($validated['last_name_khmer']);

                if ($hasLatin) {
                    $q->where(function ($sq) use ($validated) {
                        $sq->whereRaw('LOWER(TRIM(first_name)) = ?', [strtolower(trim($validated['first_name']))])
                            ->whereRaw('LOWER(TRIM(last_name)) = ?', [strtolower(trim($validated['last_name']))]);
                    });
                }
                if ($hasKhmer) {
                    $method = $hasLatin ? 'orWhere' : 'where';
                    $q->$method(function ($sq) use ($validated) {
                        $sq->where('first_name_khmer', trim($validated['first_name_khmer']))
                            ->where('last_name_khmer', trim($validated['last_name_khmer']));
                    });
                }
            })
            ->with('student')
            ->first();

        if ($duplicateProfile && $duplicateProfile->student) {
            $existingId = $duplicateProfile->student->admission_number;
            $existingName = trim(($duplicateProfile->first_name_khmer ?? $duplicateProfile->first_name).' '.($duplicateProfile->last_name_khmer ?? $duplicateProfile->last_name));

            return back()
                ->withInput()
                ->withErrors([
                    'first_name_khmer' => "សិស្សឈ្មោះ «{$existingName}» កើតថ្ងៃទី {$validated['dob']} មានក្នុងប្រព័ន្ធរួចហើយ (អត្តលេខសិស្ស៖ {$existingId})! សូមពិនិត្យមើលឡើងវិញ។",
                ]);
        }

        // ── Check for duplicate Roll Number in the same class and academic year, excluding current student ──
        if (! empty($validated['roll_number'])) {
            $duplicateRoll = Student::query()
                ->where('id', '!=', $student->id)
                ->where('academic_year_id', $validated['academic_year_id'])
                ->where('school_class_id', $validated['school_class_id'])
                ->when(! empty($validated['section_id']), fn ($q) => $q->where('section_id', $validated['section_id']))
                ->where('roll_number', trim($validated['roll_number']))
                ->first();

            if ($duplicateRoll) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'roll_number' => "លេខរៀង (Roll Number) «{$validated['roll_number']}» ត្រូវបានប្រើរួចហើយក្នុងថ្នាក់នេះ (អត្តលេខសិស្ស៖ {$duplicateRoll->admission_number})!",
                    ]);
            }
        }

        $photoPath = $student->studentProfile->photo ?? null;
        if ($request->input('remove_photo') === '1') {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        }
        if ($request->hasFile('photo')) {
            $uploadedPhoto = $request->file('photo');
            $uploadedSize = $uploadedPhoto->getSize();
            $uploadedHash = md5_file($uploadedPhoto->getRealPath());

            $existingProfiles = StudentProfile::query()
                ->where('student_id', '!=', $student->id)
                ->whereNotNull('photo')
                ->where('photo', '!=', '')
                ->with('student')
                ->get();

            foreach ($existingProfiles as $prof) {
                $fullPath = Storage::disk('public')->path($prof->photo);
                if (file_exists($fullPath) && filesize($fullPath) === $uploadedSize) {
                    if (md5_file($fullPath) === $uploadedHash) {
                        $s = $prof->student;
                        $dupName = trim(($prof->last_name_khmer ?? '').' '.($prof->first_name_khmer ?? ''))
                            ?: trim(($prof->first_name ?? '').' '.($prof->last_name ?? ''));
                        $dupAdm = $s?->admission_number ?? '';

                        return back()
                            ->withInput()
                            ->withErrors([
                                'photo' => "រូបថតនេះត្រូវបានប្រើប្រាស់រួចហើយសម្រាប់សិស្ស «{$dupName}» (អត្តលេខ៖ {$dupAdm})! សូមជ្រើសរើសរូបថតផ្ទាល់ខ្លួនរបស់សិស្ស។",
                            ]);
                    }
                }
            }

            // Delete old photo if exists
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $uploadedPhoto->store('student-photos', 'public');
        }

        DB::transaction(function () use ($validated, $student, $photoPath) {
            $fullName = trim(($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''));
            if ($fullName === '') {
                $fullName = trim(($validated['last_name_khmer'] ?? '').' '.($validated['first_name_khmer'] ?? '')) ?: 'Student';
            }

            // 1. Update User account
            if ($student->user) {
                $userEmail = $validated['email'] ?? strtolower($student->admission_number).'@school.local';
                $student->user->update([
                    'name' => $fullName,
                    'email' => $userEmail,
                ]);
            }

            $selectedClass = SchoolClass::find($validated['school_class_id']);

            // 2. Update Student record (admission_number is never changed after creation)
            $student->update([
                'admission_date' => $validated['admission_date'],
                'enrollment_date' => $validated['enrollment_date'] ?? $student->enrollment_date,
                'roll_number' => array_key_exists('roll_number', $validated) ? $validated['roll_number'] : $student->roll_number,
                'student_status_id' => $validated['student_status_id'] ?? $student->student_status_id,
                'student_category_id' => $validated['student_category_id'] ?? null,
                'academic_year_id' => $validated['academic_year_id'],
                'school_class_id' => $validated['school_class_id'],
                'section_id' => $validated['section_id'] ?? $selectedClass?->section_id ?? $student->section_id,
                'school_shift_id' => $validated['school_shift_id'] ?? $student->school_shift_id,
                'study_mode' => $validated['study_mode'] ?? 'full_time',
            ]);

            // 3. Update or create StudentProfile
            $student->studentProfile()->updateOrCreate(
                ['student_id' => $student->id],
                [
                    'photo' => $photoPath,
                    'phone' => $validated['phone'] ?? null,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'first_name_khmer' => $validated['first_name_khmer'] ?? null,
                    'last_name_khmer' => $validated['last_name_khmer'] ?? null,
                    'dob' => $validated['dob'],
                    'gender' => $validated['gender'],
                    'blood_group' => $validated['blood_group'] ?? null,
                    'nationality' => $validated['nationality'] ?? null,
                    'national_id' => $validated['national_id'] ?? null,
                    'previous_school' => $validated['previous_school'] ?? null,
                    'special_needs' => $validated['special_needs'] ?? null,
                    'medical_information' => $validated['medical_information'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]
            );

            // 4. Update or create StudentAddresse
            $student->studentAddresse()->updateOrCreate(
                ['student_id' => $student->id],
                [
                    'address_line_1' => $validated['address_line_1'] ?? null,
                    'address_line_2' => $validated['address_line_2'] ?? null,
                    'village' => $validated['village'] ?? null,
                    'commune' => $validated['commune'] ?? null,
                    'district' => $validated['district'] ?? null,
                    'province' => $validated['province'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                ]
            );

            // 5. Guardian Handling (Father, Mother, Other)
            $guardianAddress = $validated['guardian_address'] ?? null;
            if (empty($guardianAddress)) {
                $addrParts = array_filter([
                    ! empty($validated['guardian_village']) ? 'ភូមិ '.$validated['guardian_village'] : null,
                    ! empty($validated['guardian_commune']) ? 'ឃុំ/សង្កាត់ '.$validated['guardian_commune'] : null,
                    ! empty($validated['guardian_district']) ? 'ស្រុក/ខណ្ឌ '.$validated['guardian_district'] : null,
                    $validated['guardian_province'] ?? null,
                ]);
                $guardianAddress = ! empty($addrParts) ? implode(', ', $addrParts) : null;
            }

            $primaryChoice = $validated['primary_guardian'] ?? 'father';
            $guardianEmail = $validated['guardian_email'] ?? ($validated['guardian_general_email'] ?? null);

            $hasFather = ! empty($validated['father_name']);
            $hasMother = ! empty($validated['mother_name']);
            $hasOther = ! empty($validated['guardian_name']);

            $isFatherPrimary = ($primaryChoice === 'father') || (! $hasMother && ! $hasOther);
            $isMotherPrimary = ($primaryChoice === 'mother') || (! $hasFather && ! $hasOther && $primaryChoice !== 'father');
            $isOtherPrimary = ($primaryChoice === 'other') || (! $hasFather && ! $hasMother);

            if ($hasFather) {
                $this->syncStudentGuardian(
                    $student,
                    $validated['father_name'],
                    'Father',
                    $validated['father_phone'] ?? null,
                    $isFatherPrimary ? $guardianEmail : null,
                    $validated['father_occupation'] ?? null,
                    isset($validated['father_age']) ? (int) $validated['father_age'] : null,
                    $guardianAddress,
                    $isFatherPrimary,
                    true
                );
            }

            if ($hasMother) {
                $this->syncStudentGuardian(
                    $student,
                    $validated['mother_name'],
                    'Mother',
                    $validated['mother_phone'] ?? null,
                    $isMotherPrimary ? $guardianEmail : null,
                    $validated['mother_occupation'] ?? null,
                    isset($validated['mother_age']) ? (int) $validated['mother_age'] : null,
                    $guardianAddress,
                    $isMotherPrimary,
                    true
                );
            }

            if ($hasOther) {
                $this->syncStudentGuardian(
                    $student,
                    $validated['guardian_name'],
                    $validated['guardian_relationship'] ?? 'Other',
                    $validated['guardian_phone'] ?? null,
                    $isOtherPrimary ? $guardianEmail : null,
                    $validated['guardian_occupation'] ?? null,
                    isset($validated['guardian_age']) ? (int) $validated['guardian_age'] : null,
                    $guardianAddress,
                    $isOtherPrimary,
                    true
                );
            }

            // 6. Synchronize active enrollment for this academic year (preserving historic enrollments from other years)
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('academic_year_id', $validated['academic_year_id'])
                ->first();

            if ($existingEnrollment) {
                if ($existingEnrollment->enrollmentDetail) {
                    $existingEnrollment->enrollmentDetail->update([
                        'class_id' => $validated['school_class_id'],
                        'section_id' => $validated['section_id'] ?? $existingEnrollment->enrollmentDetail->section_id,
                        'roll_number' => $validated['roll_number'] ?? $existingEnrollment->enrollmentDetail->roll_number,
                    ]);
                } else {
                    EnrollmentDetail::create([
                        'enrollment_id' => $existingEnrollment->id,
                        'class_id' => $validated['school_class_id'],
                        'section_id' => $validated['section_id'] ?? null,
                        'roll_number' => $validated['roll_number'] ?? null,
                    ]);
                }
            } else {
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $validated['academic_year_id'],
                    'enrollment_date' => $validated['enrollment_date'] ?? now()->toDateString(),
                    'status' => 'Active',
                ]);

                EnrollmentDetail::create([
                    'enrollment_id' => $enrollment->id,
                    'class_id' => $validated['school_class_id'],
                    'section_id' => $validated['section_id'] ?? null,
                    'roll_number' => $validated['roll_number'] ?? null,
                ]);
            }
        });

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student safely with historical record protection.
     */
    public function destroy(Student $student)
    {
        $this->authorizeStudentManagement();

        // 1. Safe-delete protection: Check for historical financial, attendance, or academic records
        $invoicesCount = $student->student_invoices()->count();
        $paymentsCount = $student->student_payments()->count();
        $attendancesCount = $student->attendances()->count();
        $examMarksCount = $student->exam_results()->count();

        if ($invoicesCount > 0 || $paymentsCount > 0 || $attendancesCount > 0 || $examMarksCount > 0) {
            $reasons = [];
            if ($invoicesCount > 0) {
                $reasons[] = "{$invoicesCount} វិក្កយបត្រ (Invoices)";
            }
            if ($paymentsCount > 0) {
                $reasons[] = "{$paymentsCount} ប្រវត្តិទូទាត់ប្រាក់ (Payments)";
            }
            if ($attendancesCount > 0) {
                $reasons[] = "{$attendancesCount} កំណត់ត្រាវត្តមាន (Attendances)";
            }
            if ($examMarksCount > 0) {
                $reasons[] = "{$examMarksCount} លទ្ធផលប្រឡង (Exam Marks)";
            }

            $reasonText = implode(', ', $reasons);

            return redirect()->back()
                ->with('error', "មិនអាចលុបសិស្ស «{$student->user?->name}» ({$student->admission_number}) បានទេ ដោយសារមានទិន្នន័យផ្សារភ្ជាប់៖ {$reasonText}។ ដើម្បីរក្សាប្រវត្តិសាស្រ្ត និងរបាយការណ៍ហិរញ្ញវត្ថុ សូមប្តូរស្ថានភាពសិស្សទៅជា «ឈប់រៀន (Dropped)» ឬ «ផ្អាក (Suspended)» ជំនួសវិញ!");
        }

        DB::transaction(function () use ($student) {
            // Delete student uploaded documents
            foreach ($student->student_documents as $doc) {
                if ($doc->document_path && Storage::disk('public')->exists($doc->document_path)) {
                    Storage::disk('public')->delete($doc->document_path);
                }
                $doc->delete();
            }

            // Detach guardians
            $student->guardians()->detach();

            // Delete address
            $student->studentAddresse()?->delete();

            // Delete medical record
            $student->studentMedicalRecord()?->delete();

            // Delete notes
            $student->notes()?->delete();

            // Delete enrollments and details
            foreach ($student->enrollments as $enrollment) {
                $enrollment->enrollmentDetails()->delete();
                $enrollment->delete();
            }

            if ($student->studentProfile) {
                if ($student->studentProfile->photo && Storage::disk('public')->exists($student->studentProfile->photo)) {
                    Storage::disk('public')->delete($student->studentProfile->photo);
                }
                $student->studentProfile->delete();
            }

            if ($student->user) {
                $student->user->delete(); // soft-deletes the user
            }

            $student->delete();
        });

        return redirect()->route('students.index')
            ->with('success', 'សិស្សត្រូវបានលុបចេញពីប្រព័ន្ធដោយជោគជ័យ។');
    }

    /**
     * Attach a guardian to the student.
     */
    public function storeGuardian(Request $request, Student $student)
    {
        $this->authorizeStudentManagement();

        $validated = $request->validate([
            'guardian_id' => 'required|exists:guardians,id',
            'relationship' => 'required|string|max:50',
        ]);

        $student->guardians()->syncWithoutDetaching([
            $validated['guardian_id'] => ['relationship' => $validated['relationship']],
        ]);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Guardian attached successfully.');
    }

    /**
     * Remove a guardian from the student.
     */
    public function removeGuardian(Student $student, Guardian $guardian)
    {
        $this->authorizeStudentManagement();

        $student->guardians()->detach($guardian->id);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Guardian removed successfully.');
    }

    /**
     * Upload a document for the student.
     */
    public function storeDocument(Request $request, Student $student)
    {
        $this->authorizeStudentManagement();

        $validated = $request->validate([
            'document_type' => 'required|string|max:100',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('document')->store('student_documents', 'public');

        $student->documents()->create([
            'document_type' => $validated['document_type'],
            'document_path' => $path,
        ]);

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Delete a student document.
     */
    public function destroyDocument(Student $student, $documentId)
    {
        $this->authorizeStudentManagement();

        $document = $student->documents()->findOrFail($documentId);

        if (Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }

        $document->delete();

        return redirect()->route('students.show', $student->id)
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Live AJAX duplicate check endpoint for student creation and editing.
     */
    public function checkDuplicate(Request $request)
    {
        $excludeStudentId = $request->input('exclude_id');

        // Check Photo Duplicate
        if ($request->hasFile('photo')) {
            $uploadedPhoto = $request->file('photo');
            $uploadedSize = $uploadedPhoto->getSize();
            $uploadedHash = md5_file($uploadedPhoto->getRealPath());

            $existingProfiles = StudentProfile::query()
                ->when($excludeStudentId, fn ($q) => $q->where('student_id', '!=', $excludeStudentId))
                ->whereNotNull('photo')
                ->where('photo', '!=', '')
                ->with('student.user')
                ->get();

            foreach ($existingProfiles as $prof) {
                $fullPath = Storage::disk('public')->path($prof->photo);
                if (file_exists($fullPath) && filesize($fullPath) === $uploadedSize) {
                    if (md5_file($fullPath) === $uploadedHash) {
                        $s = $prof->student;
                        $name = trim(($prof->last_name_khmer ?? '').' '.($prof->first_name_khmer ?? ''))
                            ?: ($s?->user?->name ?? 'សិស្ស');
                        $adm = $s?->admission_number ?? '';

                        return response()->json([
                            'exists' => true,
                            'field' => 'photo',
                            'message' => "រូបថតនេះត្រូវបានប្រើប្រាស់រួចហើយសម្រាប់សិស្ស «{$name}» (អត្តលេខ៖ {$adm})! សូមជ្រើសរើសរូបថតផ្ទាល់ខ្លួនរបស់សិស្ស។",
                        ]);
                    }
                }
            }
        }

        // Check National ID
        if ($nationalId = trim($request->input('national_id', ''))) {
            $duplicateNationalId = StudentProfile::where('national_id', $nationalId)
                ->when($excludeStudentId, fn ($q) => $q->where('student_id', '!=', $excludeStudentId))
                ->with('student.user')
                ->first();

            if ($duplicateNationalId && $duplicateNationalId->student) {
                $s = $duplicateNationalId->student;
                $name = $duplicateNationalId->first_name_khmer
                    ? trim($duplicateNationalId->first_name_khmer.' '.$duplicateNationalId->last_name_khmer)
                    : ($s->user?->name ?? 'សិស្ស');

                return response()->json([
                    'exists' => true,
                    'field' => 'national_id',
                    'message' => "អត្តសញ្ញាណប័ណ្ណ/សំបុត្រកំណើតនេះមានរួចហើយ ជារបស់សិស្ស «{$name}» (អត្តលេខ៖ {$s->admission_number})!",
                ]);
            }
        }

        // Check User Email
        if ($email = trim($request->input('email', ''))) {
            $duplicateEmail = User::where('email', $email)
                ->when($excludeStudentId, function ($q) use ($excludeStudentId) {
                    $student = Student::find($excludeStudentId);
                    if ($student && $student->user_id) {
                        $q->where('id', '!=', $student->user_id);
                    }
                })
                ->first();

            if ($duplicateEmail) {
                return response()->json([
                    'exists' => true,
                    'field' => 'email',
                    'message' => "អ៊ីមែល «{$email}» ត្រូវបានប្រើប្រាស់រួចហើយក្នុងប្រព័ន្ធ!",
                ]);
            }
        }

        // Check Name + DOB
        $firstName = trim($request->input('first_name', ''));
        $lastName = trim($request->input('last_name', ''));
        $firstNameKh = trim($request->input('first_name_khmer', ''));
        $lastNameKh = trim($request->input('last_name_khmer', ''));
        $dob = $request->input('dob');

        if (($firstName || $firstNameKh) && $dob) {
            $duplicateProfile = StudentProfile::query()
                ->when($excludeStudentId, fn ($q) => $q->where('student_id', '!=', $excludeStudentId))
                ->where('dob', $dob)
                ->where(function ($q) use ($firstName, $lastName, $firstNameKh, $lastNameKh) {
                    if ($firstName && $lastName) {
                        $q->where(function ($sq) use ($firstName, $lastName) {
                            $sq->where('first_name', $firstName)
                                ->where('last_name', $lastName);
                        });
                    }
                    if ($firstNameKh && $lastNameKh) {
                        $q->orWhere(function ($sq) use ($firstNameKh, $lastNameKh) {
                            $sq->where('first_name_khmer', $firstNameKh)
                                ->where('last_name_khmer', $lastNameKh);
                        });
                    }
                })
                ->with('student.user')
                ->first();

            if ($duplicateProfile && $duplicateProfile->student) {
                $s = $duplicateProfile->student;
                $name = $duplicateProfile->first_name_khmer
                    ? trim($duplicateProfile->first_name_khmer.' '.$duplicateProfile->last_name_khmer)
                    : ($s->user?->name ?? 'សិស្ស');

                return response()->json([
                    'exists' => true,
                    'field' => 'name_dob',
                    'message' => "សិស្សឈ្មោះ «{$name}» កើតថ្ងៃទី {$dob} មានក្នុងប្រព័ន្ធរួចហើយ (អត្តលេខ៖ {$s->admission_number})!",
                ]);
            }
        }

        // Check Roll Number in Class & Academic Year
        $rollNumber = trim($request->input('roll_number', ''));
        $academicYearId = $request->input('academic_year_id');
        $schoolClassId = $request->input('school_class_id');
        $sectionId = $request->input('section_id');

        if ($rollNumber && $academicYearId && $schoolClassId) {
            $duplicateRoll = Student::query()
                ->when($excludeStudentId, fn ($q) => $q->where('id', '!=', $excludeStudentId))
                ->where('academic_year_id', $academicYearId)
                ->where('school_class_id', $schoolClassId)
                ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
                ->where('roll_number', $rollNumber)
                ->first();

            if ($duplicateRoll) {
                return response()->json([
                    'exists' => true,
                    'field' => 'roll_number',
                    'message' => "លេខរៀង (Roll Number) «{$rollNumber}» មានរួចហើយក្នុងថ្នាក់នេះ (អត្តលេខសិស្ស៖ {$duplicateRoll->admission_number})!",
                ]);
            }
        }

        return response()->json([
            'exists' => false,
        ]);
    }

    /**
     * Quick status update for a student.
     */
    public function updateStatus(Request $request, Student $student)
    {
        $this->authorizeStudentManagement();

        $validated = $request->validate([
            'student_status_id' => 'required|exists:student_statuses,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $status = StudentStatus::findOrFail($validated['student_status_id']);
        $student->update([
            'student_status_id' => $status->id,
        ]);

        // Sync user active status
        if ($student->user) {
            $isActive = strcasecmp($status->name, 'Active') === 0;
            $student->user->update([
                'status' => $isActive ? 'active' : 'inactive',
            ]);
        }

        // Record a note if reason was provided
        if (! empty($validated['reason'])) {
            $student->notes()->create([
                'user_id' => auth()->id(),
                'note_type' => 'Status Change',
                'note' => "ប្តូរស្ថានភាពទៅជា «{$status->khmer_name} ({$status->name})» ដោយ៖ ".(auth()->user()?->name ?? 'Admin')."។ មូលហេតុ៖ {$validated['reason']}",
            ]);
        }

        return redirect()->back()
            ->with('success', "ស្ថានភាពសិស្សត្រូវបានប្តូរទៅជា «{$status->khmer_name}» ដោយជោគជ័យ។");
    }

    /**
     * Create, update, or sync a guardian and link them to a student.
     */
    protected function syncStudentGuardian(
        Student $student,
        ?string $name,
        string $relationship,
        ?string $phone = null,
        ?string $email = null,
        ?string $occupation = null,
        ?int $age = null,
        ?string $address = null,
        bool $isPrimary = false,
        bool $isEmergency = false
    ): ?Guardian {
        $name = trim($name ?? '');
        if (empty($name)) {
            return null;
        }

        // 1. Check if student already has a guardian with this relationship
        $existingGuardian = $student->guardians()
            ->wherePivot('relationship', $relationship)
            ->first();

        $guardianUser = null;
        if ($existingGuardian && $existingGuardian->user) {
            $guardianUser = $existingGuardian->user;
            $userUpdates = ['name' => $name, 'status' => 'active'];
            if (! empty($email)) {
                $userUpdates['email'] = $email;
            }
            $guardianUser->update($userUpdates);
        } elseif (! empty($email)) {
            $guardianUser = User::withTrashed()->where('email', $email)->first();
            if ($guardianUser) {
                if ($guardianUser->trashed()) {
                    $guardianUser->restore();
                }
                $guardianUser->update(['name' => $name, 'status' => 'active']);
            } else {
                $guardianUser = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt('guardian123'),
                    'status' => 'active',
                ]);
            }
        } else {
            $guardianUser = User::create([
                'name' => $name,
                'email' => strtolower(Str::slug($relationship ?: 'guardian')).'_'.uniqid().'@school.local',
                'password' => bcrypt('guardian123'),
                'status' => 'active',
            ]);
        }

        // Auto-assign Guardian role
        $guardianRoleId = DB::table('roles')->where('slug', 'guardian')->value('id');
        if ($guardianRoleId) {
            $guardianUser->roles()->syncWithoutDetaching([$guardianRoleId]);
        }

        // 2. Guardian Record
        $guardian = $existingGuardian ?? Guardian::withTrashed()->where('user_id', $guardianUser->id)->first();
        if ($guardian) {
            if (method_exists($guardian, 'trashed') && $guardian->trashed()) {
                $guardian->restore();
            }
            $guardian->update([
                'occupation' => $occupation ?: $guardian->occupation,
                'age' => $age ?: $guardian->age,
                'address' => $address ?: $guardian->address,
            ]);
        } else {
            $guardian = Guardian::create([
                'user_id' => $guardianUser->id,
                'occupation' => $occupation,
                'age' => $age,
                'address' => $address,
            ]);
        }

        // 3. Contact Phone
        if (! empty($phone)) {
            $contact = $guardian->guardian_contacts()->first();
            if ($contact) {
                $contact->update([
                    'phone' => $phone,
                    'email' => $email ?? $contact->email,
                ]);
            } else {
                $guardian->guardian_contacts()->create([
                    'phone' => $phone,
                    'email' => $email,
                ]);
            }
        }

        // 4. Attach / Update Pivot
        if ($student->guardians()->where('guardian_id', $guardian->id)->exists()) {
            $student->guardians()->updateExistingPivot($guardian->id, [
                'relationship' => $relationship,
                'is_primary' => $isPrimary,
                'is_emergency' => $isEmergency,
            ]);
        } else {
            $student->guardians()->attach($guardian->id, [
                'relationship' => $relationship,
                'is_primary' => $isPrimary,
                'is_emergency' => $isEmergency,
            ]);
        }

        return $guardian;
    }
}
