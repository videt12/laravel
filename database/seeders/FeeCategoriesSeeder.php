<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tuition Fee',         'description' => 'Monthly or term-based academic tuition fee'],
            ['name' => 'Admission Fee',        'description' => 'One-time fee charged at the time of admission'],
            ['name' => 'Examination Fee',      'description' => 'Fee for scheduled examinations and assessments'],
            ['name' => 'Library Fee',          'description' => 'Annual fee for library access and book maintenance'],
            ['name' => 'Laboratory Fee',       'description' => 'Fee for science/computer lab usage and materials'],
            ['name' => 'Sports Fee',           'description' => 'Fee for sports facilities and extracurricular activities'],
            ['name' => 'Transport Fee',        'description' => 'Fee for school bus or transport services'],
            ['name' => 'Uniform Fee',          'description' => 'Fee for school uniform purchase or maintenance'],
            ['name' => 'Stationery Fee',       'description' => 'Fee for stationery and learning materials'],
            ['name' => 'Development Fee',      'description' => 'Fee for school infrastructure and development'],
            ['name' => 'Annual Day Fee',       'description' => 'Fee for annual cultural or sports day events'],
            ['name' => 'Late Payment Fee',     'description' => 'Penalty charged for overdue fee payments'],
        ];

        foreach ($categories as $cat) {
            DB::table('fee_categories')->updateOrInsert(
                ['name' => $cat['name']],
                array_merge($cat, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Fee categories seeded ('.count($categories).' records).');
    }
}
