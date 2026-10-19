<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use App\Models\Employees\EmployeeStatus;
use App\Models\Employees\EmployeeContract;
use App\Models\Payroll\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'employee_department', 'employee_position', 'employee_status', 'employeeContact']);

        // Search by employee code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('employeeContact', function ($cq) use ($search) {
                        $cq->where('phone', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by Department
        if ($request->filled('department_id')) {
            $query->where('employee_department_id', $request->department_id);
        }

        // Filter by Status
        if ($request->filled('status_id')) {
            $query->where('employee_status_id', $request->status_id);
        }

        $employees = $query->orderBy('date_of_joining', 'desc')->paginate(15)->withQueryString();
        $departments = EmployeeDepartment::orderBy('name')->get();
        $statuses = EmployeeStatus::orderBy('name')->get();

        $stats = [
            'total' => Employee::count(),
            'active' => Employee::whereHas('employee_status', fn ($q) => $q->where('name', 'like', '%Active%'))->count(),
            'departments' => $departments->count(),
            'positions' => EmployeePosition::count(),
        ];

        return view('employees.employee.index', compact('employees', 'departments', 'statuses', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::doesntHave('employee')
            ->doesntHave('student')
            ->doesntHave('guardian')
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('slug', ['student', 'guardian']);
            })
            ->orderBy('name')
            ->get();
        $departments = EmployeeDepartment::orderBy('name')->get();
        $positions = EmployeePosition::orderBy('name')->get();
        $statuses = EmployeeStatus::orderBy('name')->get();
        $rolesList = Role::orderBy('name')->get();
        $nextEmployeeCode = Employee::generateNextEmployeeCode();
        $nextContractNumber = EmployeeContract::generateNextContractNumber();
        $salaryStructures = SalaryStructure::active()->orderBy('name')->get();

        return view('employees.employee.create', compact('users', 'departments', 'positions', 'statuses', 'rolesList', 'nextEmployeeCode', 'nextContractNumber', 'salaryStructures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $request->filled('employee_code')) {
            $request->merge(['employee_code' => Employee::generateNextEmployeeCode()]);
        }

        $validated = $request->validate([
            'account_type' => 'required|in:existing,new,none',
            'user_id' => 'nullable|required_if:account_type,existing|exists:users,id',
            'new_name' => 'nullable|required_if:account_type,new|string|max:255',
            'new_email' => 'nullable|required_if:account_type,new|email|max:255|unique:users,email',
            'new_password' => 'nullable|string|min:6',
            'role_id' => 'nullable|exists:roles,id',

            // 1. Personal Information
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'first_name_khmer' => 'nullable|string|max:100',
            'last_name_khmer' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|max:50',
            'national_id' => 'nullable|string|max:50',
            'place_of_birth' => 'nullable|string|max:255',
            'birth_village' => 'nullable|string|max:100',
            'birth_commune' => 'nullable|string|max:100',
            'birth_district' => 'nullable|string|max:100',
            'birth_province' => 'nullable|string|max:100',

            // 2. Address Details
            'phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'current_address' => 'nullable|string|max:500',
            'permanent_address' => 'nullable|string|max:500',
            'village' => 'nullable|string|max:150',
            'commune' => 'nullable|string|max:150',
            'district' => 'nullable|string|max:150',
            'province' => 'nullable|string|max:150',

            // 3. Employment Information
            'employee_code' => 'required|string|max:50|unique:employees,employee_code',
            'employee_position_id' => 'required|exists:employee_positions,id',
            'employee_department_id' => 'nullable|exists:employee_departments,id',
            'employee_status_id' => 'required|exists:employee_statuses,id',
            'employee_type' => 'nullable|string|max:100',
            'date_of_joining' => 'required|date',
            'contract_type' => 'nullable|string|max:100',
            'supervisor_name' => 'nullable|string|max:150',
            'work_location' => 'nullable|string|max:150',

            // 4. Payroll Information
            'basic_salary' => 'nullable|numeric|min:0',
            'salary_type' => 'nullable|string|max:50',
            'allowance' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:150',
            'salary_effective_date' => 'nullable|date',

            // 5. Education & Skills
            'highest_qualification' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:150',
            'graduation_year' => 'nullable|string|max:20',
            'professional_certificate' => 'nullable|string|max:500',
            'teaching_qualification' => 'nullable|string|max:150',
            'skills' => 'nullable|string|max:1000',
            'experience_years' => 'nullable|integer|min:0|max:100',

            // 6. Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_relationship' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:50',
            'emergency_address' => 'nullable|string|max:500',

            // Profile Photo
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',

            // 7. Employment Contract (Created simultaneously with employee)
            'create_contract' => 'nullable|boolean',
            'contract_number' => 'nullable|string|max:50|unique:employee_contracts,contract_number',
            'contract_type' => 'nullable|string|max:50',
            'contract_status' => 'nullable|string|max:30',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date|after_or_equal:contract_start_date',
            'contract_salary' => 'nullable|numeric|min:0',
            'contract_work_hours' => 'nullable|numeric|min:0|max:168',
            'contract_salary_structure_id' => 'nullable|exists:salary_structures,id',
            'contract_notes' => 'nullable|string|max:2000',
        ], [
            'photo.max' => 'ទំហំរូបថតមិនត្រូវលើសពី 20MB ឡើយ។',
            'photo.image' => 'ឯកសារត្រូវតែជារូបភាព (JPEG, PNG, JPG, WEBP)។',
            'photo.mimes' => 'រូបថតត្រូវតែជាប្រភេទ jpeg, png, jpg, webp។',
        ]);

        $employee = null;

        DB::transaction(function () use ($validated, $request, &$employee) {
            // Determine User Account
            if ($validated['account_type'] === 'new') {
                $khmerFull = trim(($validated['last_name_khmer'] ?? '').' '.($validated['first_name_khmer'] ?? ''));
                $latinFull = trim(($validated['last_name'] ?? '').' '.($validated['first_name'] ?? ''));
                $userName = $validated['new_name'] ?? ($khmerFull ?: ($latinFull ?: 'បុគ្គលិក'));
                if ($khmerFull && $latinFull && ! str_contains($userName, $khmerFull)) {
                    $userName = $latinFull.' ('.$khmerFull.')';
                }

                $user = User::create([
                    'name' => $userName,
                    'email' => $validated['new_email'],
                    'password' => Hash::make($validated['new_password'] ?? 'password123'),
                    'status' => 'active',
                ]);

                // Determine role: use provided role_id or auto-detect from position
                $roleId = $validated['role_id'] ?? null;
                if (! $roleId && ! empty($validated['employee_position_id'])) {
                    $pos = EmployeePosition::find($validated['employee_position_id']);
                    $posName = strtolower($pos?->name ?? '');
                    $posCode = strtoupper($pos?->code ?? '');

                    $roleSlug = match (true) {
                        str_contains($posName, 'principal') || str_contains($posName, 'នាយក') || in_array($posCode, ['POS-PRIN', 'POS-VPRIN']) => 'principal',
                        str_contains($posName, 'librarian') || str_contains($posName, 'បណ្ណារក្ស') || $posCode === 'POS-LIB' => 'librarian',
                        str_contains($posName, 'accountant') || str_contains($posName, 'គណនេយ្យ') || str_contains($posName, 'បេឡា') || $posCode === 'POS-ACCT' => 'accountant',
                        str_contains($posName, 'admin') || $posCode === 'POS-ADMIN' => 'admin',
                        default => 'teacher',
                    };
                    $roleId = DB::table('roles')->where('slug', $roleSlug)->value('id')
                        ?? DB::table('roles')->where('slug', 'teacher')->value('id');
                }

                if ($roleId) {
                    $user->roles()->syncWithoutDetaching([$roleId]);
                }

                $userId = $user->id;
            } elseif ($validated['account_type'] === 'existing') {
                $userId = $validated['user_id'] ?? null;
                if ($userId) {
                    $existingUser = User::find($userId);
                    if ($existingUser && $existingUser->roles()->count() === 0) {
                        $roleId = $validated['role_id'] ?? null;
                        if (! $roleId && ! empty($validated['employee_position_id'])) {
                            $pos = EmployeePosition::find($validated['employee_position_id']);
                            $posName = strtolower($pos?->name ?? '');
                            $posCode = strtoupper($pos?->code ?? '');
                            $roleSlug = match (true) {
                                str_contains($posName, 'principal') || str_contains($posName, 'នាយក') || in_array($posCode, ['POS-PRIN', 'POS-VPRIN']) => 'principal',
                                str_contains($posName, 'librarian') || str_contains($posName, 'បណ្ណារក្ស') || $posCode === 'POS-LIB' => 'librarian',
                                str_contains($posName, 'accountant') || str_contains($posName, 'គណនេយ្យ') || str_contains($posName, 'បេឡា') || $posCode === 'POS-ACCT' => 'accountant',
                                str_contains($posName, 'admin') || $posCode === 'POS-ADMIN' => 'admin',
                                default => 'teacher',
                            };
                            $roleId = DB::table('roles')->where('slug', $roleSlug)->value('id')
                                ?? DB::table('roles')->where('slug', 'teacher')->value('id');
                        }
                        if ($roleId) {
                            $existingUser->roles()->syncWithoutDetaching([$roleId]);
                        }
                    }
                }
            } else {
                $userId = null;
            }

            // Create Employee
            $employee = Employee::create([
                'user_id' => $userId,
                'first_name' => $validated['first_name'] ?? null,
                'last_name' => $validated['last_name'] ?? null,
                'first_name_khmer' => $validated['first_name_khmer'] ?? null,
                'last_name_khmer' => $validated['last_name_khmer'] ?? null,
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'blood_group' => $validated['blood_group'] ?? null,
                'nationality' => $validated['nationality'] ?? 'Cambodian',
                'marital_status' => $validated['marital_status'] ?? null,
                'national_id' => $validated['national_id'] ?? null,
                'place_of_birth' => $validated['place_of_birth'] ?? (implode(', ', array_filter([
                    ! empty($validated['birth_village']) ? 'ភូមិ '.$validated['birth_village'] : null,
                    ! empty($validated['birth_commune']) ? 'ឃុំ/សង្កាត់ '.$validated['birth_commune'] : null,
                    ! empty($validated['birth_district']) ? 'ស្រុក/ខណ្ឌ '.$validated['birth_district'] : null,
                    ! empty($validated['birth_province']) ? 'ខេត្ត/រាជធានី '.$validated['birth_province'] : null,
                ])) ?: null),
                'birth_village' => $validated['birth_village'] ?? null,
                'birth_commune' => $validated['birth_commune'] ?? null,
                'birth_district' => $validated['birth_district'] ?? null,
                'birth_province' => $validated['birth_province'] ?? null,

                'employee_department_id' => $validated['employee_department_id'] ?? null,
                'employee_position_id' => $validated['employee_position_id'],
                'employee_status_id' => $validated['employee_status_id'],
                'employee_type' => $validated['employee_type'] ?? null,
                'contract_type' => $validated['contract_type'] ?? null,
                'supervisor_name' => $validated['supervisor_name'] ?? null,
                'work_location' => $validated['work_location'] ?? null,
                'employee_code' => $validated['employee_code'],
                'date_of_joining' => $validated['date_of_joining'],

                'basic_salary' => $validated['basic_salary'] ?? null,
                'salary_type' => $validated['salary_type'] ?? 'Monthly',
                'allowance' => $validated['allowance'] ?? 0,
                'deduction' => $validated['deduction'] ?? 0,
                'payment_method' => $validated['payment_method'] ?? 'Cash',
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['bank_account_number'] ?? null,
                'bank_account_name' => $validated['bank_account_name'] ?? null,
                'salary_effective_date' => $validated['salary_effective_date'] ?? null,

                'highest_qualification' => $validated['highest_qualification'] ?? null,
                'university' => $validated['university'] ?? null,
                'major' => $validated['major'] ?? null,
                'graduation_year' => $validated['graduation_year'] ?? null,
                'professional_certificate' => $validated['professional_certificate'] ?? null,
                'teaching_qualification' => $validated['teaching_qualification'] ?? null,
                'skills' => $validated['skills'] ?? null,
                'experience_years' => $validated['experience_years'] ?? null,
            ]);

            // Create Contact Record
            $employee->employeeContact()->create([
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['contact_email'] ?? null,
                'current_address' => $validated['current_address'] ?? null,
                'permanent_address' => $validated['permanent_address'] ?? null,
                'village' => $validated['village'] ?? null,
                'commune' => $validated['commune'] ?? null,
                'district' => $validated['district'] ?? null,
                'province' => $validated['province'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_relationship' => $validated['emergency_relationship'] ?? null,
                'emergency_phone' => $validated['emergency_phone'] ?? null,
                'emergency_address' => $validated['emergency_address'] ?? null,
            ]);

            // Save Profile Photo if uploaded
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('employee-photos', 'public');
                $employee->employee_documents()->create([
                    'document_type' => 'photo',
                    'title' => 'Profile Photo',
                    'document_path' => $photoPath,
                    'file_size' => $request->file('photo')->getSize(),
                ]);
            }

            // Create Contract if requested (enabled by default)
            $shouldCreateContract = $request->boolean('create_contract', true);
            if ($shouldCreateContract) {
                $rawContractType = $validated['contract_type'] ?? $request->input('contract_type', 'probation');
                $contractTypeSlug = match (strtolower(trim($rawContractType))) {
                    'permanent', 'កិច្ចសន្យាអចិន្ត្រៃយ៍', 'កិច្ចសន្យាមិនកំណត់ថិរវេលា' => 'permanent',
                    'fixed term', 'fixed_term', 'កិច្ចសន្យាមានកំណត់', 'កិច្ចសន្យាមានកំណត់ថិរវេលា' => 'fixed_term',
                    'part-time', 'part_time', 'ក្រៅម៉ោង', 'ការងារក្រៅម៉ោង' => 'part_time',
                    'internship', 'intern', 'កម្មសិក្សា', 'កម្មសិក្សាការងារ' => 'intern',
                    default => 'probation',
                };

                $contractNumber = ! empty($validated['contract_number'])
                    ? $validated['contract_number']
                    : EmployeeContract::generateNextContractNumber();

                $startDate = ! empty($validated['contract_start_date'])
                    ? $validated['contract_start_date']
                    : ($validated['date_of_joining'] ?? now()->toDateString());

                $endDate = $validated['contract_end_date'] ?? null;
                if (! $endDate) {
                    if ($contractTypeSlug === 'probation') {
                        $endDate = date('Y-m-d', strtotime($startDate . ' +3 months'));
                    } elseif ($contractTypeSlug === 'fixed_term') {
                        $endDate = date('Y-m-d', strtotime($startDate . ' +1 year'));
                    } elseif ($contractTypeSlug === 'intern') {
                        $endDate = date('Y-m-d', strtotime($startDate . ' +3 months'));
                    }
                }

                $contractSalary = ! empty($validated['contract_salary'])
                    ? $validated['contract_salary']
                    : ($validated['basic_salary'] ?? 0);

                EmployeeContract::create([
                    'employee_id' => $employee->id,
                    'salary_structure_id' => $validated['contract_salary_structure_id'] ?? null,
                    'contract_number' => $contractNumber,
                    'contract_type' => $contractTypeSlug,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'salary' => $contractSalary,
                    'work_hours_per_week' => $validated['contract_work_hours'] ?? 40,
                    'status' => $validated['contract_status'] ?? 'active',
                    'notes' => $validated['contract_notes'] ?? 'កិច្ចសន្យាការងារដំបូង (បង្កើតស្វ័យប្រវត្តិជាមួយទិន្នន័យបុគ្គលិក)',
                    'created_by' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('employees.show', $employee)
            ->with('success', 'បានបង្កើតទិន្នន័យបុគ្គលិក និងកិច្ចសន្យាការងារដោយជោគជ័យ។');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load([
            'user',
            'employee_department',
            'employee_position',
            'employee_status',
            'employeeContact',
            'employee_documents',
            'employee_attendances' => function ($q) {
                $q->latest('attendance_date')->take(10);
            },
            'employee_leaves' => function ($q) {
                $q->with('leave_type')->latest('start_date')->take(10);
            },
            'activeContract',
            'employeeContracts' => function ($q) {
                $q->latest('start_date');
            },
        ]);

        return view('employees.employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee->load(['user', 'employee_department', 'employee_position', 'employee_status', 'employeeContact', 'employee_documents']);
        $users = User::orderBy('name')->get();
        $departments = EmployeeDepartment::orderBy('name')->get();
        $positions = EmployeePosition::orderBy('name')->get();
        $statuses = EmployeeStatus::orderBy('name')->get();

        return view('employees.employee.edit', compact('employee', 'users', 'departments', 'positions', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            // 1. Personal Information
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'first_name_khmer' => 'nullable|string|max:100',
            'last_name_khmer' => 'nullable|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|max:50',
            'national_id' => 'nullable|string|max:50',
            'place_of_birth' => 'nullable|string|max:255',
            'birth_village' => 'nullable|string|max:100',
            'birth_commune' => 'nullable|string|max:100',
            'birth_district' => 'nullable|string|max:100',
            'birth_province' => 'nullable|string|max:100',

            // User Info update
            'user_name' => 'nullable|string|max:255',
            'user_email' => 'nullable|email|max:255|unique:users,email,'.($employee->user_id ?? 0),

            // 2. Address Details
            'phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'current_address' => 'nullable|string|max:500',
            'permanent_address' => 'nullable|string|max:500',
            'village' => 'nullable|string|max:150',
            'commune' => 'nullable|string|max:150',
            'district' => 'nullable|string|max:150',
            'province' => 'nullable|string|max:150',

            // 3. Employment Information
            'employee_code' => 'required|string|max:50|unique:employees,employee_code,'.$employee->id,
            'employee_position_id' => 'required|exists:employee_positions,id',
            'employee_department_id' => 'nullable|exists:employee_departments,id',
            'employee_status_id' => 'required|exists:employee_statuses,id',
            'employee_type' => 'nullable|string|max:100',
            'date_of_joining' => 'required|date',
            'contract_type' => 'nullable|string|max:100',
            'supervisor_name' => 'nullable|string|max:150',
            'work_location' => 'nullable|string|max:150',

            // 4. Payroll Information
            'basic_salary' => 'nullable|numeric|min:0',
            'salary_type' => 'nullable|string|max:50',
            'allowance' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:150',
            'salary_effective_date' => 'nullable|date',

            // 5. Education & Skills
            'highest_qualification' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:150',
            'graduation_year' => 'nullable|string|max:20',
            'professional_certificate' => 'nullable|string|max:500',
            'teaching_qualification' => 'nullable|string|max:150',
            'skills' => 'nullable|string|max:1000',
            'experience_years' => 'nullable|integer|min:0|max:100',

            // 6. Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:150',
            'emergency_relationship' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|max:50',
            'emergency_address' => 'nullable|string|max:500',

            // Profile Photo
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        ], [
            'photo.max' => 'ទំហំរូបថតមិនត្រូវលើសពី 20MB ឡើយ។',
            'photo.image' => 'ឯកសារត្រូវតែជារូបភាព (JPEG, PNG, JPG, WEBP)។',
            'photo.mimes' => 'រូបថតត្រូវតែជាប្រភេទ jpeg, png, jpg, webp។',
        ]);

        DB::transaction(function () use ($validated, $request, $employee) {
            // Update User details if provided
            if ($employee->user && (! empty($validated['user_name']) || ! empty($validated['user_email']))) {
                $employee->user->update([
                    'name' => $validated['user_name'] ?? $employee->user->name,
                    'email' => $validated['user_email'] ?? $employee->user->email,
                ]);
            }

            // Update Employee record
            $employee->update([
                'first_name' => $validated['first_name'] ?? null,
                'last_name' => $validated['last_name'] ?? null,
                'first_name_khmer' => $validated['first_name_khmer'] ?? null,
                'last_name_khmer' => $validated['last_name_khmer'] ?? null,
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'nationality' => $validated['nationality'] ?? 'Cambodian',
                'marital_status' => $validated['marital_status'] ?? null,
                'national_id' => $validated['national_id'] ?? null,
                'place_of_birth' => $validated['place_of_birth'] ?? (implode(', ', array_filter([
                    ! empty($validated['birth_village']) ? 'ភូមិ '.$validated['birth_village'] : null,
                    ! empty($validated['birth_commune']) ? 'ឃុំ/សង្កាត់ '.$validated['birth_commune'] : null,
                    ! empty($validated['birth_district']) ? 'ស្រុក/ខណ្ឌ '.$validated['birth_district'] : null,
                    ! empty($validated['birth_province']) ? 'ខេត្ត/រាជធានី '.$validated['birth_province'] : null,
                ])) ?: null),
                'birth_village' => $validated['birth_village'] ?? null,
                'birth_commune' => $validated['birth_commune'] ?? null,
                'birth_district' => $validated['birth_district'] ?? null,
                'birth_province' => $validated['birth_province'] ?? null,

                'employee_department_id' => $validated['employee_department_id'] ?? null,
                'employee_position_id' => $validated['employee_position_id'],
                'employee_status_id' => $validated['employee_status_id'],
                'employee_type' => $validated['employee_type'] ?? null,
                'contract_type' => $validated['contract_type'] ?? null,
                'supervisor_name' => $validated['supervisor_name'] ?? null,
                'work_location' => $validated['work_location'] ?? null,
                'employee_code' => $validated['employee_code'],
                'date_of_joining' => $validated['date_of_joining'],

                'basic_salary' => $validated['basic_salary'] ?? null,
                'salary_type' => $validated['salary_type'] ?? 'Monthly',
                'allowance' => $validated['allowance'] ?? 0,
                'deduction' => $validated['deduction'] ?? 0,
                'payment_method' => $validated['payment_method'] ?? 'Cash',
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['bank_account_number'] ?? null,
                'bank_account_name' => $validated['bank_account_name'] ?? null,
                'salary_effective_date' => $validated['salary_effective_date'] ?? null,

                'highest_qualification' => $validated['highest_qualification'] ?? null,
                'university' => $validated['university'] ?? null,
                'major' => $validated['major'] ?? null,
                'graduation_year' => $validated['graduation_year'] ?? null,
                'professional_certificate' => $validated['professional_certificate'] ?? null,
                'teaching_qualification' => $validated['teaching_qualification'] ?? null,
                'skills' => $validated['skills'] ?? null,
                'experience_years' => $validated['experience_years'] ?? null,
            ]);

            // Update or create contact details
            $employee->employeeContact()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'phone' => $validated['phone'] ?? null,
                    'email' => $validated['contact_email'] ?? null,
                    'current_address' => $validated['current_address'] ?? null,
                    'permanent_address' => $validated['permanent_address'] ?? null,
                    'village' => $validated['village'] ?? null,
                    'commune' => $validated['commune'] ?? null,
                    'district' => $validated['district'] ?? null,
                    'province' => $validated['province'] ?? null,
                    'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                    'emergency_relationship' => $validated['emergency_relationship'] ?? null,
                    'emergency_phone' => $validated['emergency_phone'] ?? null,
                    'emergency_address' => $validated['emergency_address'] ?? null,
                ]
            );

            // Update profile photo if uploaded
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('employee-photos', 'public');
                $oldPhoto = $employee->employee_documents()->where('document_type', 'photo')->first();
                if ($oldPhoto) {
                    if ($oldPhoto->document_path && Storage::disk('public')->exists($oldPhoto->document_path)) {
                        Storage::disk('public')->delete($oldPhoto->document_path);
                    }
                    $oldPhoto->update([
                        'document_path' => $photoPath,
                        'file_size' => $request->file('photo')->getSize(),
                    ]);
                } else {
                    $employee->employee_documents()->create([
                        'document_type' => 'photo',
                        'title' => 'Profile Photo',
                        'document_path' => $photoPath,
                        'file_size' => $request->file('photo')->getSize(),
                    ]);
                }
            }
        });

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $name = $employee->user->name ?? $employee->employee_code;
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', "Employee {$name} has been deleted successfully.");
    }
}
