<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters — lookup tables must come before tables that reference them.
     * Run with: php artisan db:seed
     * Or individually: php artisan db:seed --class=SchoolSetupSeeder
     */
    public function run(): void
    {
        $this->call([
            // ─── LAYER 1: Independent Lookup Tables ─────────────────
            // No foreign key dependencies — seed first
            RolesAndPermissionsSeeder::class,
            StudentStatusesSeeder::class,
            StudentCategoriesSeeder::class,
            AttendanceStatusesSeeder::class,
            ExamTypesSeeder::class,
            GradeScalesSeeder::class,
            FeeCategoriesSeeder::class,
            PaymentMethodsSeeder::class,
            LeaveTypesSeeder::class,
            SubjectGroupsSeeder::class,

            // ─── LAYER 2: Employee Structure ─────────────────────────
            // Departments, positions, statuses
            EmployeePositionsDepartmentsSeeder::class,

            // ─── LAYER 3: School Core Setup ───────────────────────────
            // school, shifts, time_slots, academic_year, school_terms,
            // school_settings, grades, sections, classrooms,
            // library_categories, expense_categories
            SchoolSetupSeeder::class,

            // ─── LAYER 4: Employees & Staff Data ──────────────────────
            EmployeesSampleSeeder::class,
        ]);
    }
}
