<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentStatusesSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Active',       'description' => 'Currently enrolled and attending classes'],
            ['name' => 'Inactive',     'description' => 'Not currently attending but not formally withdrawn'],
            ['name' => 'Graduated',    'description' => 'Successfully completed the program'],
            ['name' => 'Transferred',  'description' => 'Transferred to another school'],
            ['name' => 'Withdrawn',    'description' => 'Formally withdrawn from the school'],
            ['name' => 'Suspended',    'description' => 'Temporarily suspended pending review'],
            ['name' => 'Expelled',     'description' => 'Permanently removed from the school'],
            ['name' => 'Alumni',       'description' => 'Former student who has graduated'],
        ];

        foreach ($statuses as $status) {
            DB::table('student_statuses')->updateOrInsert(
                ['name' => $status['name']],
                array_merge($status, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Student statuses seeded ('.count($statuses).' records).');
    }
}
