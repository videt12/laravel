<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectGroupsSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Core Subjects',        'description' => 'Mandatory subjects required for all students'],
            ['name' => 'Science Group',         'description' => 'Physics, Chemistry, Biology and related sciences'],
            ['name' => 'Arts Group',            'description' => 'Literature, History, Geography and Humanities'],
            ['name' => 'Commerce Group',        'description' => 'Accounting, Business Studies, Economics'],
            ['name' => 'Mathematics Group',     'description' => 'Mathematics, Statistics and Applied Maths'],
            ['name' => 'Languages',             'description' => 'Mother tongue, English, and foreign languages'],
            ['name' => 'Technology Group',      'description' => 'ICT, Computer Science and Digital Literacy'],
            ['name' => 'Physical Education',    'description' => 'Physical education, health and sports'],
            ['name' => 'Creative Arts',         'description' => 'Drawing, Music, Drama and Visual Arts'],
            ['name' => 'Vocational',            'description' => 'Practical trade and vocational subjects'],
            ['name' => 'Islamic Studies',       'description' => 'Religious education and Islamic studies'],
            ['name' => 'Electives',             'description' => 'Optional subjects chosen by students'],
        ];

        foreach ($groups as $group) {
            DB::table('subject_groups')->updateOrInsert(
                ['name' => $group['name']],
                array_merge($group, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Subject groups seeded ('.count($groups).' records).');
    }
}
