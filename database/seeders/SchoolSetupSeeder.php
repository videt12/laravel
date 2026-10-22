<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSetupSeeder extends Seeder
{
    public function run(): void
    {
        // ─── SCHOOL ──────────────────────────────────────────────
        $school = DB::table('schools')->first();
        if (! $school) {
            $schoolId = DB::table('schools')->insertGetId([
                'name' => 'សាលារៀនប្រទឹបបញ្ញា',
                'address' => 'រាជធានីភ្នំពេញ កម្ពុជា',
                'phone' => '+855-12-345678',
                'email' => 'info@school.edu.kh',
                'website' => 'https://school.edu.kh',
                'logo_path' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $schoolId = $school->id;
        }

        // ─── SCHOOL SHIFT ────────────────────────────────────────
        $morningShift = DB::table('school_shifts')->where('name', 'Morning Shift')->first();
        if (! $morningShift) {
            $morningShiftId = DB::table('school_shifts')->insertGetId([
                'name' => 'Morning Shift',
                'start_time' => '08:00:00',
                'end_time' => '14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $morningShiftId = $morningShift->id;
        }

        DB::table('school_shifts')->updateOrInsert(
            ['name' => 'Day Shift'],
            [
                'start_time' => '10:00:00',
                'end_time' => '16:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ─── TIME SLOTS (Morning Shift) ───────────────────────────
        $slots = [
            ['start_time' => '08:00:00', 'end_time' => '08:45:00', 'is_break' => false],
            ['start_time' => '08:45:00', 'end_time' => '09:30:00', 'is_break' => false],
            ['start_time' => '09:30:00', 'end_time' => '10:00:00', 'is_break' => true],
            ['start_time' => '10:00:00', 'end_time' => '10:45:00', 'is_break' => false],
            ['start_time' => '10:45:00', 'end_time' => '11:30:00', 'is_break' => false],
            ['start_time' => '11:30:00', 'end_time' => '12:15:00', 'is_break' => false],
            ['start_time' => '12:15:00', 'end_time' => '13:00:00', 'is_break' => true],
            ['start_time' => '13:00:00', 'end_time' => '13:45:00', 'is_break' => false],
            ['start_time' => '13:45:00', 'end_time' => '14:00:00', 'is_break' => false],
        ];

        foreach ($slots as $slot) {
            DB::table('time_slots')->updateOrInsert(
                ['school_shift_id' => $morningShiftId, 'start_time' => $slot['start_time']],
                array_merge($slot, [
                    'school_shift_id' => $morningShiftId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ─── ACADEMIC YEARS (2024-2025, 2025-2026, 2026-2027) ───
        $years = [
            ['name' => '2024-2025', 'start_date' => '2024-09-01', 'end_date' => '2025-06-30', 'is_active' => false],
            ['name' => '2025-2026', 'start_date' => '2025-09-01', 'end_date' => '2026-06-30', 'is_active' => false],
            ['name' => '2026-2027', 'start_date' => '2026-09-01', 'end_date' => '2027-06-30', 'is_active' => true],
        ];

        $activeYearId = null;
        foreach ($years as $yr) {
            DB::table('academic_years')->updateOrInsert(
                ['name' => $yr['name']],
                array_merge($yr, ['created_at' => now(), 'updated_at' => now()])
            );
            if ($yr['is_active']) {
                $activeYearId = DB::table('academic_years')->where('name', $yr['name'])->value('id');
            }
        }

        if (! $activeYearId) {
            $activeYearId = DB::table('academic_years')->orderByDesc('id')->value('id');
        }

        // ─── SCHOOL TERMS ────────────────────────────────────────
        $terms = [
            ['name' => 'ឆមាសទី ១ (First Term)',  'start_date' => '2026-09-01', 'end_date' => '2027-01-31'],
            ['name' => 'ឆមាសទី ២ (Second Term)', 'start_date' => '2027-02-01', 'end_date' => '2027-06-30'],
        ];

        foreach ($terms as $t) {
            DB::table('school_terms')->updateOrInsert(
                ['academic_year_id' => $activeYearId, 'name' => $t['name']],
                array_merge($t, [
                    'academic_year_id' => $activeYearId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ─── SCHOOL SETTINGS ─────────────────────────────────────
        $settings = [
            ['setting_key' => 'school_name',          'setting_value' => 'សាលារៀនប្រទឹបបញ្ញា'],
            ['setting_key' => 'academic_year',         'setting_value' => '2026-2027'],
            ['setting_key' => 'currency',              'setting_value' => 'USD'],
            ['setting_key' => 'currency_symbol',       'setting_value' => '$'],
            ['setting_key' => 'passing_percentage',    'setting_value' => '50'],
            ['setting_key' => 'attendance_threshold',  'setting_value' => '75'],
            ['setting_key' => 'late_fine_per_day',     'setting_value' => '1'],
            ['setting_key' => 'library_fine_per_day',  'setting_value' => '0.5'],
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
            ['name' => 'ថ្នាក់មត្តេយ្យ (Kindergarten)', 'level' => 0],
            ['name' => 'ថ្នាក់ទី ១ (Grade 1)',         'level' => 1],
            ['name' => 'ថ្នាក់ទី ២ (Grade 2)',         'level' => 2],
            ['name' => 'ថ្នាក់ទី ៣ (Grade 3)',         'level' => 3],
            ['name' => 'ថ្នាក់ទី ៤ (Grade 4)',         'level' => 4],
            ['name' => 'ថ្នាក់ទី ៥ (Grade 5)',         'level' => 5],
            ['name' => 'ថ្នាក់ទី ៦ (Grade 6)',         'level' => 6],
            ['name' => 'ថ្នាក់ទី ៧ (Grade 7)',         'level' => 7],
            ['name' => 'ថ្នាក់ទី ៨ (Grade 8)',         'level' => 8],
            ['name' => 'ថ្នាក់ទី ៩ (Grade 9)',         'level' => 9],
            ['name' => 'ថ្នាក់ទី ១០ (Grade 10)',       'level' => 10],
            ['name' => 'ថ្នាក់ទី ១១ (Grade 11)',       'level' => 11],
            ['name' => 'ថ្នាក់ទី ១២ (Grade 12)',       'level' => 12],
        ];

        foreach ($grades as $grade) {
            DB::table('grades')->updateOrInsert(
                ['name' => $grade['name']],
                array_merge($grade, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ─── SECTIONS ────────────────────────────────────────────
        $sections = [
            ['name' => 'A', 'capacity' => 40],
            ['name' => 'B', 'capacity' => 40],
            ['name' => 'C', 'capacity' => 35],
            ['name' => 'D', 'capacity' => 35],
        ];

        foreach ($sections as $section) {
            DB::table('sections')->updateOrInsert(
                ['name' => $section['name']],
                array_merge($section, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ─── CLASSROOMS ──────────────────────────────────────────
        $rooms = [
            ['name' => 'បន្ទប់ ១០១', 'building' => 'អគារ A', 'capacity' => 40],
            ['name' => 'បន្ទប់ ១០២', 'building' => 'អគារ A', 'capacity' => 40],
            ['name' => 'បន្ទប់ ២០១', 'building' => 'អគារ A', 'capacity' => 35],
            ['name' => 'បន្ទប់ ២០២', 'building' => 'អគារ A', 'capacity' => 35],
            ['name' => 'បន្ទប់ ៣០១', 'building' => 'អគារ B', 'capacity' => 30],
            ['name' => 'បន្ទប់ ៣០២', 'building' => 'អគារ B', 'capacity' => 30],
            ['name' => 'បន្ទប់ IT Lab', 'building' => 'អគារ IT', 'capacity' => 30],
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
            ['name' => 'ប្រាក់បៀវត្ស និងថ្លៃឈ្នួល', 'description' => 'ប្រាក់បៀវត្សបុគ្គលិក និងប្រាក់ឧបត្ថម្ភ'],
            ['name' => 'ទឹក និងភ្លើង (Utilities)',   'description' => 'ថ្លៃអគ្គិសនី ទឹក អ៊ីនធឺណិត'],
            ['name' => 'សម្ភារៈការិយាល័យ និងសិក្សា',  'description' => 'សម្ភារៈការិយាល័យ និងសៀវភៅ'],
            ['name' => 'ការថែទាំ និងជួសជុល',         'description' => 'ការជួសជុលអគារ និងឧបករណ៍'],
            ['name' => 'កម្មវិធី និងពិធីបុណ្យផ្សេងៗ', 'description' => 'ទិវាសាលា កីឡា និងព្រឹត្តិការណ៍'],
            ['name' => 'ការដឹកជញ្ជូន',              'description' => 'ថ្លៃប្រេងឥន្ធនៈ និងយានយន្ត'],
            ['name' => 'ព័ត៌មានវិទ្យា និងឧបករណ៍',   'description' => 'កុំព្យូទ័រ Projector និងឧបករណ៍បច្ចេកវិទ្យា'],
            ['name' => 'ចំណាយផ្សេងៗ',              'description' => 'ចំណាយមិនទាន់ចាត់ថ្នាក់'],
        ];

        foreach ($expCats as $cat) {
            DB::table('expense_categories')->updateOrInsert(
                ['name' => $cat['name']],
                array_merge($cat, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('School setup seeded successfully.');
    }
}
