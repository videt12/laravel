<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeScalesSeeder extends Seeder
{
    public function run(): void
    {
        // Standard A-F grading scale (common in international schools)
        $scales = [
            ['name' => 'ល្អប្រសើរ',  'min_percentage' => 90.00, 'max_percentage' => 100.00, 'grade' => 'A+', 'gpa_point' => 4.00],
            ['name' => 'ល្អណាស់',    'min_percentage' => 80.00, 'max_percentage' => 89.99, 'grade' => 'A',  'gpa_point' => 4.00],
            ['name' => 'ល្អបង្គួរ',    'min_percentage' => 70.00, 'max_percentage' => 79.99, 'grade' => 'B+', 'gpa_point' => 3.50],
            ['name' => 'ល្អ',         'min_percentage' => 60.00, 'max_percentage' => 69.99, 'grade' => 'B',  'gpa_point' => 3.00],
            ['name' => 'សមរម្យ', 'min_percentage' => 50.00, 'max_percentage' => 59.99, 'grade' => 'C+', 'gpa_point' => 2.50],
            ['name' => 'មធ្យម',      'min_percentage' => 40.00, 'max_percentage' => 49.99, 'grade' => 'C',  'gpa_point' => 2.00],
            ['name' => 'ជាប់',         'min_percentage' => 33.00, 'max_percentage' => 39.99, 'grade' => 'D',  'gpa_point' => 1.00],
            ['name' => 'ធ្លាក់',         'min_percentage' => 0.00, 'max_percentage' => 32.99, 'grade' => 'F',  'gpa_point' => 0.00],
        ];

        foreach ($scales as $scale) {
            DB::table('grade_scales')->updateOrInsert(
                ['grade' => $scale['grade']],
                array_merge($scale, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Grade scales seeded ('.count($scales).' records).');
    }
}
