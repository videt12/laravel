<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExamTypesSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('exam_types') || ! Schema::hasColumn('exam_types', 'name')) {
            $this->command?->warn('exam_types table or name column missing. Skipping seeding.');
            return;
        }

        $types = [
            ['name' => 'តេស្តប្រចាំមេរៀន',           'description' => 'ការវាយតម្លៃខ្លីៗលើមេរៀននីមួយៗ'],
            ['name' => 'ការប្រឡងពាក់កណ្តាលឆមាស',        'description' => 'ការប្រឡងនៅពាក់កណ្តាលឆមាសសិក្សា'],
            ['name' => 'ការប្រឡងឆមាស',           'description' => 'ការប្រឡងរួមចុងឆមាសសិក្សា'],
            ['name' => 'តេស្តក្នុងថ្នាក់',           'description' => 'ការវាយតម្លៃក្នុងថ្នាក់ដឹកនាំដោយគ្រូបង្រៀន'],
            ['name' => 'ការប្រឡងអនុវត្ត',       'description' => 'ការវាយតម្លៃជាក់ស្តែងលើជំនាញអនុវត្ត'],
            ['name' => 'ការប្រឡងផ្ទាល់មាត់',            'description' => 'ការវាយតម្លៃផ្ទាល់មាត់ដោយគណៈមេប្រយោគ'],
            ['name' => 'ការវាយតម្លៃតាមកិច្ចការ',     'description' => 'ពិន្ទុដែលទទួលបានពីកិច្ចការដែលបានដាក់'],
            ['name' => 'ការប្រឡងប្រចាំឆ្នាំ',          'description' => 'ការប្រឡងរួមបញ្ចប់ឆ្នាំសិក្សា'],
            ['name' => 'ការប្រឡងសាកល្បង',       'description' => 'ការប្រឡងសាកល្បងមុនការប្រឡងផ្លូវការ'],
        ];

        foreach ($types as $type) {
            DB::table('exam_types')->updateOrInsert(
                ['name' => $type['name']],
                array_merge($type, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Exam types seeded ('.count($types).' records).');
    }
}
