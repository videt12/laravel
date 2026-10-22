<?php

namespace App\Providers;

use App\Models\Core\AcademicYear;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || str_contains(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();

        // Enforce that only the single academic year chosen in Settings is active
        try {
            AcademicYear::ensureSingleActiveYear();
        } catch (\Throwable $e) {
            // Database not ready or tables not migrated yet
        }

        // Ensure teacher role strictly does not retain unauthorized permissions (students, subjects, classrooms, teacher assignments, academic years)
        try {
            if (Schema::hasTable('role_permissions') && Schema::hasTable('roles') && Schema::hasTable('permissions')) {
                $teacherId = DB::table('roles')->where('slug', 'teacher')->value('id');
                if ($teacherId) {
                    $disallowedPrefixes = ['students.', 'subjects.', 'classrooms.', 'teacher-assignments.', 'academic-years.'];
                    $disallowedPermIds = DB::table('permissions')
                        ->where(function ($q) use ($disallowedPrefixes) {
                            foreach ($disallowedPrefixes as $prefix) {
                                $q->orWhere('slug', 'like', "{$prefix}%");
                            }
                            $q->orWhereIn('slug', ['students', 'subjects', 'classrooms', 'teacher-assignments', 'academic-years']);
                        })
                        ->pluck('id');

                    if ($disallowedPermIds->isNotEmpty()) {
                        DB::table('role_permissions')
                            ->where('role_id', $teacherId)
                            ->whereIn('permission_id', $disallowedPermIds)
                            ->delete();
                    }

                    // Ensure teacher role has classes.view to access the classes list
                    $classesViewId = DB::table('permissions')->where('slug', 'classes.view')->value('id');
                    if ($classesViewId) {
                        DB::table('role_permissions')->updateOrInsert(
                            ['role_id' => $teacherId, 'permission_id' => $classesViewId],
                            ['created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
            }

            // Ensure super-admin role name is updated to 'អ្នកគ្រប់គ្រងប្រព័ន្ធ'
            if (Schema::hasTable('roles')) {
                DB::table('roles')
                    ->where('slug', 'super-admin')
                    ->where('name', '!=', 'អ្នកគ្រប់គ្រងប្រព័ន្ធ')
                    ->update(['name' => 'អ្នកគ្រប់គ្រងប្រព័ន្ធ']);
            }

            // Sync comprehensive system permissions
            if (Schema::hasTable('permissions') && Schema::hasTable('role_permissions')) {
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

            // Auto-link any employee (such as EMP-0320) that has missing or orphaned user_id, or empty name
            if (Schema::hasTable('employees') && Schema::hasTable('users')) {
                $unlinkedEmployees = DB::table('employees')
                    ->where(function ($q) {
                        $q->whereNull('user_id')
                            ->orWhereNotExists(function ($sq) {
                                $sq->select(DB::raw(1))->from('users')->whereColumn('users.id', 'employees.user_id');
                            });
                    })
                    ->get();
                if ($unlinkedEmployees->isNotEmpty()) {
                    $unlinkedTeacherUsers = DB::table('users')
                        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                        ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                        ->where('roles.slug', 'teacher')
                        ->whereNotExists(function ($q) {
                            $q->select(DB::raw(1))->from('employees')->whereColumn('employees.user_id', 'users.id');
                        })
                        ->select('users.id', 'users.name')
                        ->get();

                    foreach ($unlinkedEmployees as $index => $emp) {
                        if (isset($unlinkedTeacherUsers[$index])) {
                            $tu = $unlinkedTeacherUsers[$index];
                            $updates = ['user_id' => $tu->id];
                            if (empty($emp->first_name) || empty($emp->first_name_khmer) || $emp->first_name === 'sodara') {
                                $updates['first_name_khmer'] = $tu->name;
                                $updates['first_name'] = $tu->name;
                            }
                            DB::table('employees')->where('id', $emp->id)->update($updates);
                        }
                    }
                }

                // If any employee has user_id but empty first_name / last_name, sync from user's name
                $employeesWithoutName = DB::table('employees')
                    ->whereNotNull('user_id')
                    ->where(function ($q) {
                        $q->whereNull('first_name')->orWhere('first_name', '');
                    })
                    ->where(function ($q) {
                        $q->whereNull('first_name_khmer')->orWhere('first_name_khmer', '');
                    })
                    ->get();

                foreach ($employeesWithoutName as $emp) {
                    $uName = DB::table('users')->where('id', $emp->user_id)->value('name');
                    if ($uName) {
                        DB::table('employees')->where('id', $emp->id)->update([
                            'first_name_khmer' => $uName,
                            'first_name' => $uName,
                        ]);
                    }
                }
            }
        } catch (\Throwable) {
            // Ignore during early bootstrap or offline scripts
        }
    }
}
