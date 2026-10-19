<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSetupSeeder extends Seeder
{
    public function run(): void
    {
        // ─── SCHOOL ──────────────────────────────────────────────
        $schoolId = DB::table('schools')->insertGetId([
            'name' => 'Greenfield International School',
            'address' => '123 Education Road, Dhaka, Bangladesh',
            'phone' => '+880-2-9876543',
            'email' => 'info@greenfieldschool.edu.bd',
            'website' => 'https://greenfieldschool.edu.bd',
            'logo_path' => null,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ─── SCHOOL SHIFT ────────────────────────────────────────
        $morningShiftId = DB::table('school_shifts')->insertGetId([
            'name' => 'Morning Shift',
            'start_time' => '08:00:00',
            'end_time' => '14:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('school_shifts')->insert([
            'name' => 'Day Shift',
            'start_time' => '10:00:00',
            'end_time' => '16:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ─── TIME SLOTS (Morning Shift) ───────────────────────────
        $slots = [
            ['start_time' => '08:00:00', 'end_time' => '08:45:00', 'is_break' => false],
            ['start_time' => '08:45:00', 'end_time' => '09:30:00', 'is_break' => false],
            ['start_time' => '09:30:00', 'end_time' => '10:00:00', 'is_break' => true],  // Break
            ['start_time' => '10:00:00', 'end_time' => '10:45:00', 'is_break' => false],
            ['start_time' => '10:45:00', 'end_time' => '11:30:00', 'is_break' => false],
            ['start_time' => '11:30:00', 'end_time' => '12:15:00', 'is_break' => false],
            ['start_time' => '12:15:00', 'end_time' => '13:00:00', 'is_break' => true],  // Lunch
            ['start_time' => '13:00:00', 'end_time' => '13:45:00', 'is_break' => false],
            ['start_time' => '13:45:00', 'end_time' => '14:00:00', 'is_break' => false],
        ];

        foreach ($slots as $slot) {
            DB::table('time_slots')->insert(array_merge($slot, [
                'school_shift_id' => $morningShiftId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ─── ACADEMIC YEAR ───────────────────────────────────────
        $academicYearId = DB::table('academic_years')->insertGetId([
            'name' => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ─── SCHOOL TERMS ────────────────────────────────────────
        DB::table('school_terms')->insert([
            [
                'academic_year_id' => $academicYearId,
                'name' => 'First Term',
                'start_date' => '2025-09-01',
                'end_date' => '2025-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'academic_year_id' => $academicYearId,
                'name' => 'Second Term',
                'start_date' => '2026-01-01',
                'end_date' => '2026-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ─── SCHOOL SETTINGS ─────────────────────────────────────
        $settings = [
            ['setting_key' => 'school_name',          'setting_value' => 'Greenfield International School'],
            ['setting_key' => 'academic_year',         'setting_value' => '2025-2026'],
            ['setting_key' => 'currency',              'setting_value' => 'BDT'],
            ['setting_key' => 'currency_symbol',       'setting_value' => '৳'],
            ['setting_key' => 'passing_percentage',    'setting_value' => '33'],
            ['setting_key' => 'attendance_threshold',  'setting_value' => '75'],
            ['setting_key' => 'late_fine_per_day',     'setting_value' => '50'],
            ['setting_key' => 'library_fine_per_day',  'setting_value' => '10'],
            ['setting_key' => 'max_book_issue',        'setting_value' => '3'],
            ['setting_key' => 'max_issue_days',        'setting_value' => '14'],
        ];

        foreach ($settings as $setting) {
            DB::table('school_settings')->updateOrInsert(
                ['school_id' => $schoolId, 'setting_key' => $setting['setting_key']],
                array_merge($setting, [
                    'school_id' => $schoolId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ─── GRADES ──────────────────────────────────────────────
        $grades = [
            ['name' => 'Pre-School',  'level' => 0],
            ['name' => 'Nursery',     'level' => 1],
            ['name' => 'KG 1',        'level' => 2],
            ['name' => 'KG 2',        'level' => 3],
            ['name' => 'Grade 1',     'level' => 4],
            ['name' => 'Grade 2',     'level' => 5],
            ['name' => 'Grade 3',     'level' => 6],
            ['name' => 'Grade 4',     'level' => 7],
            ['name' => 'Grade 5',     'level' => 8],
            ['name' => 'Grade 6',     'level' => 9],
            ['name' => 'Grade 7',     'level' => 10],
            ['name' => 'Grade 8',     'level' => 11],
            ['name' => 'Grade 9',     'level' => 12],
            ['name' => 'Grade 10',    'level' => 13],
            ['name' => 'Grade 11',    'level' => 14],
            ['name' => 'Grade 12',    'level' => 15],
        ];

        foreach ($grades as $grade) {
            DB::table('grades')->updateOrInsert(
                ['name' => $grade['name']],
                array_merge($grade, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ─── SECTIONS ────────────────────────────────────────────
        $sections = [
            ['name' => 'Section A', 'capacity' => 40],
            ['name' => 'Section B', 'capacity' => 40],
            ['name' => 'Section C', 'capacity' => 35],
            ['name' => 'Section D', 'capacity' => 35],
        ];

        foreach ($sections as $section) {
            DB::table('sections')->updateOrInsert(
                ['name' => $section['name']],
                array_merge($section, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ─── CLASSROOMS ──────────────────────────────────────────
        $rooms = [
            ['name' => 'Room 101', 'building' => 'Main Block',    'capacity' => 40],
            ['name' => 'Room 102', 'building' => 'Main Block',    'capacity' => 40],
            ['name' => 'Room 201', 'building' => 'Main Block',    'capacity' => 35],
            ['name' => 'Room 202', 'building' => 'Main Block',    'capacity' => 35],
            ['name' => 'Room 301', 'building' => 'Science Block', 'capacity' => 30],
            ['name' => 'Room 302', 'building' => 'Science Block', 'capacity' => 30],
            ['name' => 'Lab 1',    'building' => 'Science Block', 'capacity' => 25],
            ['name' => 'Lab 2',    'building' => 'Science Block', 'capacity' => 25],
            ['name' => 'Lab IT',   'building' => 'IT Block',      'capacity' => 30],
        ];

        foreach ($rooms as $room) {
            DB::table('classrooms')->updateOrInsert(
                ['name' => $room['name'], 'building' => $room['building']],
                array_merge($room, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ─── LIBRARY CATEGORIES ──────────────────────────────────
        $libCats = [
            ['name' => 'វិទ្យាសាស្ត្រ និងបច្ចេកវិទ្យា',  'description' => 'សៀវភៅរូបវិទ្យា គីមីវិទ្យា ជីវវិទ្យា និងបច្ចេកវិទ្យា'],
            ['name' => 'គណិតវិទ្យា',                  'description' => 'សៀវភៅសិក្សា និងសៀវភៅឯកសារយោងគណិតវិទ្យា'],
            ['name' => 'អក្សរសាស្ត្រ និងអក្សរសិល្ប៍',   'description' => 'ប្រលោមលោក កំណាព្យ និងស្នាដៃអក្សរសិល្ប៍'],
            ['name' => 'ប្រវត្តិសាស្ត្រ និងភូមិវិទ្យា',    'description' => 'ព្រឹត្តិការណ៍ប្រវត្តិសាស្ត្រ និងការសិក្សាភូមិវិទ្យា'],
            ['name' => 'សាសនា និងសីលធម៌',            'description' => 'គម្ពីរសាសនា និងការអប់រំសីលធម៌'],
            ['name' => 'ភាសា និងវេយ្យាករណ៍',           'description' => 'សៀវភៅភាសាខ្មែរ អង់គ្លេស និងភាសាផ្សេងៗ'],
            ['name' => 'សៀវភៅឯកសារយោង',              'description' => 'សព្វវចនាធិប្បាយ វចនានុក្រម និងកាលានុក្រម'],
            ['name' => 'សៀវភៅកុមារ',                  'description' => 'សៀវភៅអំណានសមស្របតាមកម្រិតអាយុកុមារ'],
            ['name' => 'ទស្សនាវដ្តី និងព្រឹត្តិបត្រ',      'description' => 'ព្រឹត្តិបត្រសិក្សា និងទស្សនាវដ្តីសាលា'],
            ['name' => 'កីឡា និងសុខភាព',              'description' => 'សៀវភៅអប់រំកាយ កីឡា និងការថែទាំសុខភាព'],
        ];

        foreach ($libCats as $cat) {
            DB::table('library_categories')->updateOrInsert(
                ['name' => $cat['name']],
                array_merge($cat, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ─── EXPENSE CATEGORIES ──────────────────────────────────
        $expCats = [
            ['name' => 'Salaries & Wages',    'description' => 'Staff salaries, wages, and allowances'],
            ['name' => 'Utilities',            'description' => 'Electricity, water, gas, and internet bills'],
            ['name' => 'Stationery & Supplies', 'description' => 'Office and classroom stationery purchases'],
            ['name' => 'Maintenance & Repair', 'description' => 'Building and equipment maintenance costs'],
            ['name' => 'Events & Functions',   'description' => 'Annual day, sports day, and other events'],
            ['name' => 'Transport',            'description' => 'School vehicle fuel, maintenance, and repairs'],
            ['name' => 'IT & Equipment',       'description' => 'Computer, projector, and equipment purchases'],
            ['name' => 'Miscellaneous',        'description' => 'Other uncategorized expenses'],
        ];

        foreach ($expCats as $cat) {
            DB::table('expense_categories')->updateOrInsert(
                ['name' => $cat['name']],
                array_merge($cat, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('School setup seeded: school, shifts, time slots, academic year, terms, grades, sections, classrooms, library categories, expense categories.');
    }
}
