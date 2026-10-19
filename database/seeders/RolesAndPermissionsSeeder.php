<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ─── ROLES ───────────────────────────────────────────────
        $roles = [
            ['name' => 'អ្នកគ្រប់គ្រងប្រព័ន្ធ',  'slug' => 'super-admin'],
            ['name' => 'Admin',        'slug' => 'admin'],
            ['name' => 'Principal',    'slug' => 'principal'],
            ['name' => 'Teacher',      'slug' => 'teacher'],
            ['name' => 'Librarian',    'slug' => 'librarian'],
            ['name' => 'Accountant',   'slug' => 'accountant'],
            ['name' => 'Student',      'slug' => 'student'],
            ['name' => 'Guardian',     'slug' => 'guardian'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['slug' => $role['slug']], array_merge($role, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ─── PERMISSIONS ─────────────────────────────────────────
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

        // Granular & Advanced Permissions
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

        // ─── SUPER ADMIN GETS ALL PERMISSIONS ───────────────────
        $superAdminId = DB::table('roles')->where('slug', 'super-admin')->value('id');
        $allPermIds = DB::table('permissions')->pluck('id');

        foreach ($allPermIds as $permId) {
            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => $superAdminId, 'permission_id' => $permId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // ─── ADMIN GETS ALL EXCEPT ROLES ─────────────────────────
        $adminId = DB::table('roles')->where('slug', 'admin')->value('id');
        $adminPerms = DB::table('permissions')->where('slug', 'not like', 'roles.%')->pluck('id');

        foreach ($adminPerms as $permId) {
            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => $adminId, 'permission_id' => $permId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // ─── TEACHER PERMISSIONS ─────────────────────────────────
        $teacherId = DB::table('roles')->where('slug', 'teacher')->value('id');
        $teacherSlugs = [
            'attendance.view', 'attendance.create', 'attendance.edit',
            'assignments.view', 'assignments.create', 'assignments.edit',
            'exams.view', 'exams.create', 'exams.edit',
            'classes.view', 'subjects.view', 'timetables.view',
        ];
        foreach ($teacherSlugs as $slug) {
            $permId = DB::table('permissions')->where('slug', $slug)->value('id');
            if ($permId) {
                DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $teacherId, 'permission_id' => $permId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // ─── ACCOUNTANT PERMISSIONS ──────────────────────────────
        $accountantId = DB::table('roles')->where('slug', 'accountant')->value('id');
        if ($accountantId) {
            $accountantSlugs = [
                'finance.view', 'finance.create', 'finance.edit',
                'payrolls.view', 'payrolls.create', 'payrolls.edit',
                'reports.view', 'students.view',
            ];
            foreach ($accountantSlugs as $slug) {
                $permId = DB::table('permissions')->where('slug', $slug)->value('id');
                if ($permId) {
                    DB::table('role_permissions')->updateOrInsert(
                        ['role_id' => $accountantId, 'permission_id' => $permId],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }

        // ─── LIBRARIAN PERMISSIONS ───────────────────────────────
        $librarianId = DB::table('roles')->where('slug', 'librarian')->value('id');
        if ($librarianId) {
            $librarianSlugs = [
                'library.view', 'library.create', 'library.edit', 'library.delete',
                'students.view',
            ];
            foreach ($librarianSlugs as $slug) {
                $permId = DB::table('permissions')->where('slug', $slug)->value('id');
                if ($permId) {
                    DB::table('role_permissions')->updateOrInsert(
                        ['role_id' => $librarianId, 'permission_id' => $permId],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }

        // ─── DEFAULT SUPER ADMIN USER (Sophal Chan) ───────────────
        $user = DB::table('users')->where('email', 'sophal.chan@school.edu')->first();
        if (! $user) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Sophal Chan',
                'email' => 'sophal.chan@school.edu',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $user->id;
        }

        if ($userId && $superAdminId) {
            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $userId, 'role_id' => $superAdminId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Also support admin@example.com for saved autofill convenience
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $adminExampleId = DB::table('users')->where('email', 'admin@example.com')->value('id');
        if ($adminExampleId && $superAdminId) {
            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $adminExampleId, 'role_id' => $superAdminId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $this->command->info('Roles, permissions, and user assignment seeded.');
    }
}
