<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceStatusesSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Present',           'code' => 'P',  'description' => 'Student was present for the session'],
            ['name' => 'Absent',            'code' => 'A',  'description' => 'Student was absent without notification'],
            ['name' => 'Late',              'code' => 'L',  'description' => 'Student arrived late to the session'],
            ['name' => 'Excused Absent',    'code' => 'EA', 'description' => 'Absent with prior approval or valid reason'],
            ['name' => 'Half Day',          'code' => 'HD', 'description' => 'Student attended only half of the session'],
            ['name' => 'On Leave',          'code' => 'OL', 'description' => 'Student is on approved leave'],
            ['name' => 'Sick Leave',        'code' => 'SL', 'description' => 'Student is absent due to illness'],
            ['name' => 'Holiday',           'code' => 'H',  'description' => 'Session falls on a public holiday'],
        ];

        foreach ($statuses as $status) {
            DB::table('attendance_statuses')->updateOrInsert(
                ['code' => $status['code']],
                array_merge($status, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Attendance statuses seeded ('.count($statuses).' records).');
    }
}
