<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Auth\LoginActivity;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use App\Models\Core\School;
use App\Models\Core\SchoolSetting;
use App\Services\Backup\DatabaseBackupService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SystemSettingsController extends Controller
{
    /**
     * Display the unified System Settings page.
     */
    public function index(Request $request, DatabaseBackupService $backupService)
    {
        $this->syncMissingUserRoles();
        $this->syncSystemPermissions();

        $allowedTabs = ['users', 'roles', 'school-profile', 'system', 'backup-logs'];
        $activeTab = $request->query('tab', 'users');

        if (! in_array($activeTab, $allowedTabs)) {
            $activeTab = 'users';
        }

        $backups = $backupService->listBackups();
        $databaseStats = $backupService->getDatabaseStats();
        $loginActivities = LoginActivity::with('user.roles')->latest('id')->paginate(15, ['*'], 'logs_page')->withQueryString();

        // Shared counts for navigation badges
        $counts = [
            'users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'backups' => count($backups),
        ];

        // Fetch School & System Settings
        $school = School::first();
        if (! $school) {
            $school = School::create([
                'name' => 'សាលារៀន ប្រទីបបញ្ញា',
                'status' => 'active',
                'logo_path' => 'images/school_logo.png',
            ]);
        } elseif ($school->logo_path === 'images/school_logo.png' || empty($school->logo_path) || ! file_exists(public_path($school->logo_path))) {
            $school->logo_path = file_exists(public_path('uploads/school/logo_1789983316.png'))
                ? 'uploads/school/logo_1789983316.png'
                : 'images/school_logo.png';
            $school->save();
        }

        $settingsRaw = SchoolSetting::where('school_id', $school->id ?? 1)->get();
        $settings = $settingsRaw->pluck('setting_value', 'setting_key')->toArray();

        // Active academic years list for selectors
        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('name')->get();

        // ── Tab 1: Users Data ──
        $rolesList = Role::orderBy('name')->get();
        $usersQuery = User::with(['roles'])
            ->withMax('login_activities', 'login_time');

        if ($search = $request->input('search')) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter = $request->input('role')) {
            $usersQuery->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('roles.id', $roleFilter)
                    ->orWhere('roles.slug', $roleFilter);
            });
        }

        if ($statusFilter = $request->input('status')) {
            $usersQuery->where('status', $statusFilter);
        }

        $users = $usersQuery->orderByDesc('id')->paginate(15)->withQueryString();

        // ── Tab 2: Roles & Permissions Data ──
        $roles = Role::withCount(['users', 'permissions'])
            ->with([
                'permissions',
                'users.employee.employee_position',
                'users.employee.employee_department',
                'users.student.studentProfile',
                'users.student.schoolClass',
                'users.guardian',
            ])
            ->orderBy('id')
            ->get();

        $permissions = Permission::orderBy('slug')->get();
        $groupedPermissions = $permissions->groupBy(function ($perm) {
            $parts = explode('.', $perm->slug);

            return $parts[0] ?? 'general';
        });

        return view('settings.index', compact(
            'activeTab',
            'counts',
            'school',
            'settings',
            'academicYears',
            'users',
            'rolesList',
            'roles',
            'permissions',
            'groupedPermissions',
            'backups',
            'databaseStats',
            'loginActivities'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  USERS CRUD
    // ─────────────────────────────────────────────────────────────

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->withoutTrashed()],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'email.unique' => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ (ស្ទួន) សូមប្រើប្រាស់អ៊ីមែលផ្សេង។',
            'email.required' => 'សូមបញ្ចូលអ៊ីមែល។',
            'email.email' => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវទេ។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះពេញ។',
            'password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់។',
            'password.min' => 'ពាក្យសម្ងាត់ត្រូវមានយ៉ាងតិច ៨ តួអក្សរ។',
            'password.confirmed' => 'ការបញ្ជាក់ពាក្យសម្ងាត់មិនត្រូវគ្នាទេ។',
            'role_id.required' => 'សូមជ្រើសរើសតួនាទី។',
        ]);

        // Free up the email if held by any soft-deleted user
        User::onlyTrashed()
            ->where('email', $validated['email'])
            ->get()
            ->each(function ($trashed) {
                $trashed->email = 'deleted_'.time().'_'.$trashed->id.'_'.$trashed->email;
                $trashed->saveQuietly();
            });

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
                'email_verified_at' => now(),
            ]);

            $user->roles()->sync([$validated['role_id']]);
        } catch (UniqueConstraintViolationException|\PDOException $e) {
            return redirect()->back()->withInput()->withErrors([
                'email' => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ (ស្ទួន) សូមប្រើប្រាស់អ៊ីមែលផ្សេង។',
            ]);
        }

        return redirect()->route('settings.index', ['tab' => 'users'])
            ->with('success', "អ្នកប្រើប្រាស់ '{$user->name}' ត្រូវបានបង្កើតដោយជោគជ័យ។");
    }

    /**
     * Update an existing user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)->withoutTrashed()],
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,inactive'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ (ស្ទួន) សូមប្រើប្រាស់អ៊ីមែលផ្សេង។',
            'email.required' => 'សូមបញ្ចូលអ៊ីមែល។',
            'email.email' => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវទេ។',
            'name.required' => 'សូមបញ្ចូលឈ្មោះពេញ។',
            'password.min' => 'ពាក្យសម្ងាត់ត្រូវមានយ៉ាងតិច ៨ តួអក្សរ។',
            'password.confirmed' => 'ការបញ្ជាក់ពាក្យសម្ងាត់មិនត្រូវគ្នាទេ។',
            'role_id.required' => 'សូមជ្រើសរើសតួនាទី។',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Free up the email if held by any soft-deleted user
        User::onlyTrashed()
            ->where('email', $validated['email'])
            ->where('id', '!=', $user->id)
            ->get()
            ->each(function ($trashed) {
                $trashed->email = 'deleted_'.time().'_'.$trashed->id.'_'.$trashed->email;
                $trashed->saveQuietly();
            });

        try {
            $user->update($updateData);
            $user->roles()->sync([$validated['role_id']]);
        } catch (UniqueConstraintViolationException|\PDOException $e) {
            return redirect()->back()->withInput()->withErrors([
                'email' => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ (ស្ទួន) សូមប្រើប្រាស់អ៊ីមែលផ្សេង។',
            ]);
        }

        return redirect()->route('settings.index', ['tab' => 'users'])
            ->with('success', "ព័ត៌មានអ្នកប្រើប្រាស់ '{$user->name}' ត្រូវបានកែប្រែដោយជោគជ័យ។");
    }

    /**
     * Toggle active/inactive status for a user.
     */
    public function toggleUserStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('settings.index', ['tab' => 'users'])
                ->with('error', 'មិនអាចបិទគណនីដែលកំពុងដំណើរការផ្ទាល់ខ្លួនបានទេ។');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $msg = $newStatus === 'active' ? 'បានបើកដំណើរការគណនី' : 'បានផ្អាកដំណើរការគណនី';

        return redirect()->route('settings.index', ['tab' => 'users'])
            ->with('success', "{$msg} '{$user->name}' ដោយជោគជ័យ។");
    }

    /**
     * Reset a user's password.
     */
    public function resetUserPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('settings.index', ['tab' => 'users'])
            ->with('success', "ពាក្យសម្ងាត់របស់អ្នកប្រើប្រាស់ '{$user->name}' ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ។");
    }

    /**
     * Delete (soft-delete) a user.
     */
    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('settings.index', ['tab' => 'users'])
                ->with('error', 'មិនអាចលុបគណនីផ្ទាល់ខ្លួនរបស់អ្នកបានទេ។');
        }

        $userName = $user->name;
        $user->roles()->detach();
        // Rename email with prefix so the email address can be re-registered or reused
        $user->email = 'deleted_'.time().'_'.$user->id.'_'.$user->email;
        $user->saveQuietly();
        $user->delete();

        return redirect()->route('settings.index', ['tab' => 'users'])
            ->with('success', "អ្នកប្រើប្រាស់ '{$userName}' ត្រូវបានលុបដោយជោគជ័យ។");
    }

    // ─────────────────────────────────────────────────────────────
    //  ROLES & PERMISSIONS
    // ─────────────────────────────────────────────────────────────

    /**
     * Store a new role.
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Check if slug already exists
        if (Role::where('slug', $slug)->exists()) {
            $slug = $slug.'-'.time();
        }

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => $slug,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions', []));
        }

        return redirect()->route('settings.index', ['tab' => 'roles'])
            ->with('success', "តួនាទីថ្មី '{$role->name}' ត្រូវបានបង្កើតដោយជោគជ័យ។");
    }

    /**
     * Update an existing role.
     */
    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug,'.$role->id],
        ]);

        $data = ['name' => $validated['name']];

        // Super-admin slug is protected
        if ($role->slug !== 'super-admin' && ! empty($validated['slug'])) {
            $data['slug'] = Str::slug($validated['slug']);
        }

        $role->update($data);

        return redirect()->route('settings.index', ['tab' => 'roles'])
            ->with('success', "តួនាទី '{$role->name}' ត្រូវបានកែប្រែដោយជោគជ័យ។");
    }

    /**
     * Sync permissions assigned to a role.
     */
    public function syncRolePermissions(Request $request, Role $role)
    {
        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);

        return redirect()->route('settings.index', ['tab' => 'roles'])
            ->with('success', "សិទ្ធិប្រើប្រាស់សម្រាប់តួនាទី '{$role->name}' ត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ។");
    }

    /**
     * Delete a role.
     */
    public function destroyRole(Role $role)
    {
        if (in_array($role->slug, ['super-admin', 'admin'])) {
            return redirect()->route('settings.index', ['tab' => 'roles'])
                ->with('error', 'មិនអាចលុបតួនាទីស្នូលរបស់ប្រព័ន្ធបានទេ។');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('settings.index', ['tab' => 'roles'])
                ->with('error', 'មិនអាចលុបតួនាទីនេះបានទេ ដោយសារមានអ្នកប្រើប្រាស់កំពុងកាន់កាប់តួនាទីនេះ។');
        }

        $roleName = $role->name;
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('settings.index', ['tab' => 'roles'])
            ->with('success', "តួនាទី '{$roleName}' ត្រូវបានលុបដោយជោគជ័យ។");
    }

    // ─────────────────────────────────────────────────────────────
    //  SCHOOL PROFILE
    // ─────────────────────────────────────────────────────────────

    /**
     * Update school profile information.
     */
    public function updateSchoolProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:10240'],
        ], [
            'name.required' => 'សូមបញ្ចូលឈ្មោះសាលា។',
            'email.email' => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវទេ។',
            'logo.image' => 'ឯកសារដែលបានបញ្ចូលត្រូវតែជារូបភាព (JPG, PNG, WEBP, SVG)។',
            'logo.mimes' => 'រូបភាព Logo ត្រូវតែជាប្រភេទ jpeg, png, jpg, gif, svg ឬ webp។',
            'logo.max' => 'ទំហំរូបភាព Logo មិនត្រូវលើសពី 10MB ឡើយ។',
        ]);

        $school = School::first();
        if (! $school) {
            $school = new School;
        }

        $school->name = $validated['name'];
        $school->address = $validated['address'] ?? null;
        $school->phone = $validated['phone'] ?? null;
        $school->email = $validated['email'] ?? null;
        $school->website = $validated['website'] ?? null;
        if (isset($validated['status'])) {
            $school->status = $validated['status'];
        }

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $uploadDir = public_path('uploads/school');
            if (! file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = 'logo_'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $school->logo_path = 'uploads/school/'.$fileName;
        }

        $school->save();

        // Synchronize school_name in school_settings if setting exists
        SchoolSetting::updateOrCreate(
            ['school_id' => $school->id, 'setting_key' => 'school_name'],
            ['setting_value' => $school->name]
        );

        return redirect()->route('settings.index', ['tab' => 'school-profile'])
            ->with('success', 'ព័ត៌មានប្រវត្តិរូបសាលាត្រូវបានរក្សាទុកដោយជោគជ័យ។');
    }

    // ─────────────────────────────────────────────────────────────
    //  SYSTEM SETTINGS
    // ─────────────────────────────────────────────────────────────

    /**
     * Update school / system configuration key-value settings.
     */
    public function updateSystemSettings(Request $request)
    {
        $school = School::first();
        $schoolId = $school ? $school->id : 1;

        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            SchoolSetting::updateOrCreate(
                ['school_id' => $schoolId, 'setting_key' => $key],
                ['setting_value' => $value ?? '']
            );
        }

        // Synchronize academic years with configured setting
        if (isset($settings['academic_year']) && ! empty($settings['academic_year'])) {
            AcademicYear::resetConfiguredSettingCache();
            AcademicYear::synchronizeActiveYear($settings['academic_year']);
        }

        return redirect()->route('settings.index', ['tab' => 'system'])
            ->with('success', 'ការកំណត់ប្រព័ន្ធត្រូវបានរក្សាទុកដោយជោគជ័យ។');
    }

    /**
     * Auto-sync any existing student, guardian, or employee accounts that are missing their role assignments.
     */
    private function syncMissingUserRoles(): void
    {
        // 1. Sync students
        $studentRoleId = DB::table('roles')->where('slug', 'student')->value('id');
        if ($studentRoleId) {
            $studentUserIds = DB::table('students')
                ->whereNotNull('user_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('user_roles')
                        ->whereColumn('user_roles.user_id', 'students.user_id');
                })
                ->pluck('user_id')
                ->unique();

            if ($studentUserIds->isNotEmpty()) {
                $now = now();
                $rows = $studentUserIds->map(fn ($uid) => [
                    'user_id' => $uid,
                    'role_id' => $studentRoleId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();
                DB::table('user_roles')->insertOrIgnore($rows);
            }
        }

        // 2. Sync guardians
        $guardianRoleId = DB::table('roles')->where('slug', 'guardian')->value('id');
        if ($guardianRoleId) {
            $guardianUserIds = DB::table('guardians')
                ->whereNotNull('user_id')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('user_roles')
                        ->whereColumn('user_roles.user_id', 'guardians.user_id');
                })
                ->pluck('user_id')
                ->unique();

            if ($guardianUserIds->isNotEmpty()) {
                $now = now();
                $rows = $guardianUserIds->map(fn ($uid) => [
                    'user_id' => $uid,
                    'role_id' => $guardianRoleId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();
                DB::table('user_roles')->insertOrIgnore($rows);
            }
        }

        // 3. Sync employees
        $employeesWithoutRoles = DB::table('employees')
            ->leftJoin('employee_positions', 'employees.employee_position_id', '=', 'employee_positions.id')
            ->whereNotNull('employees.user_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('user_roles')
                    ->whereColumn('user_roles.user_id', 'employees.user_id');
            })
            ->select('employees.user_id', 'employee_positions.name as position_name', 'employee_positions.code as position_code')
            ->get();

        if ($employeesWithoutRoles->isNotEmpty()) {
            $rolesMap = DB::table('roles')->pluck('id', 'slug');
            $now = now();
            $rows = [];

            foreach ($employeesWithoutRoles as $emp) {
                $posName = strtolower($emp->position_name ?? '');
                $posCode = strtoupper($emp->position_code ?? '');

                $slug = match (true) {
                    str_contains($posName, 'principal') || str_contains($posName, 'នាយក') || in_array($posCode, ['POS-PRIN', 'POS-VPRIN']) => 'principal',
                    str_contains($posName, 'librarian') || str_contains($posName, 'បណ្ណារក្ស') || $posCode === 'POS-LIB' => 'librarian',
                    str_contains($posName, 'accountant') || str_contains($posName, 'គណនេយ្យ') || str_contains($posName, 'បេឡា') || $posCode === 'POS-ACCT' => 'accountant',
                    str_contains($posName, 'admin') || $posCode === 'POS-ADMIN' => 'admin',
                    default => 'teacher',
                };

                $roleId = $rolesMap[$slug] ?? $rolesMap['teacher'] ?? null;
                if ($roleId) {
                    $rows[] = [
                        'user_id' => $emp->user_id,
                        'role_id' => $roleId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (! empty($rows)) {
                DB::table('user_roles')->insertOrIgnore($rows);
            }
        }
    }

    /**
     * Automatically sync all core and granular system permissions and assign to Super Admin.
     */
    private function syncSystemPermissions(): void
    {
        $modules = [
            'students', 'guardians', 'employees', 'enrollments',
            'attendance', 'staff-attendance', 'staff-leaves',
            'exams', 'assignments', 'library',
            'finance', 'reports', 'settings', 'roles',
            'classes', 'subjects', 'timetables', 'academic-years',
            'payrolls', 'departments',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $slug = "{$module}.{$action}";
                $name = ucfirst($action).' '.ucwords(str_replace('-', ' ', $module));
                DB::table('permissions')->updateOrInsert(['slug' => $slug], [
                    'name' => $name,
                    'slug' => $slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $customPermissions = [
            // Staff Attendance QR & Shifts
            ['slug' => 'staff-attendance.scan', 'name' => 'Scan QR Staff Attendance'],
            ['slug' => 'staff-attendance.cards', 'name' => 'Generate Staff QR Cards'],
            ['slug' => 'staff-attendance.shifts', 'name' => 'Configure Staff Shift Settings'],

            // Staff Leaves Approval
            ['slug' => 'staff-leaves.approve', 'name' => 'Approve Staff Leaves'],

            // Examinations & Grading
            ['slug' => 'exams.scores', 'name' => 'Enter Exam Scores'],
            ['slug' => 'exams.calculate', 'name' => 'Calculate Exam Grades'],
            ['slug' => 'exams.report-card', 'name' => 'Print Student Report Cards'],

            // Finance Payments & Invoices
            ['slug' => 'finance.payments', 'name' => 'Collect Student Payments'],
            ['slug' => 'finance.receipts', 'name' => 'Print Receipts and Invoices'],
            ['slug' => 'finance.invoices.cancel', 'name' => 'Cancel Invoices'],

            // Payrolls Flow
            ['slug' => 'payrolls.submit', 'name' => 'Submit Payroll Batch'],
            ['slug' => 'payrolls.approve', 'name' => 'Approve Payroll Batch'],
            ['slug' => 'payrolls.pay', 'name' => 'Disburse Payroll Payments'],
            ['slug' => 'payrolls.payslip', 'name' => 'Print Employee Payslip'],

            // Bulk Data Import & Export
            ['slug' => 'bulk-data.view', 'name' => 'View Bulk Data Page'],
            ['slug' => 'bulk-data.import', 'name' => 'Import Students From Excel'],
            ['slug' => 'bulk-data.export', 'name' => 'Export Data to Excel'],

            // Printable Documents
            ['slug' => 'print.id-cards', 'name' => 'Print Student and Staff ID Cards'],
            ['slug' => 'print.certificates', 'name' => 'Print Student Certificates'],

            // System Security & Integrations
            ['slug' => 'system.backup', 'name' => 'Manage Database Backups'],
            ['slug' => 'system.telegram', 'name' => 'Send Telegram Broadcasts'],
        ];

        foreach ($customPermissions as $cp) {
            DB::table('permissions')->updateOrInsert(['slug' => $cp['slug']], [
                'name' => $cp['name'],
                'slug' => $cp['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ensure Super Admin has all permissions
        $superAdminId = DB::table('roles')->whereIn('slug', ['super-admin', 'Super Admin'])->value('id');
        if ($superAdminId) {
            $allPermIds = DB::table('permissions')->pluck('id');
            foreach ($allPermIds as $permId) {
                DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $superAdminId, 'permission_id' => $permId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}

