<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'General',
                'code' => 'GEN',
                'description' => 'General standard category student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Scholarship',
                'code' => 'SCH',
                'description' => 'Scholarship recipient student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            DB::table('student_categories')->updateOrInsert(
                ['id' => $category['id']],
                $category
            );
        }
    }
}
