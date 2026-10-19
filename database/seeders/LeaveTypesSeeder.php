<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Annual Leave',        'code' => 'LV-ANN',  'is_paid' => true,  'description' => 'Paid annual leave for permanent employees',     'days_allowed' => 21],
            ['name' => 'Sick Leave',          'code' => 'LV-SCK',  'is_paid' => true,  'description' => 'Paid leave due to personal illness or injury',  'days_allowed' => 14],
            ['name' => 'Casual Leave',        'code' => 'LV-CAS',  'is_paid' => true,  'description' => 'Short-notice leave for personal reasons',       'days_allowed' => 10],
            ['name' => 'Maternity Leave',     'code' => 'LV-MAT',  'is_paid' => true,  'description' => 'Paid leave for female employees upon childbirth', 'days_allowed' => 90],
            ['name' => 'Paternity Leave',     'code' => 'LV-PAT',  'is_paid' => true,  'description' => 'Paid leave for male employees upon childbirth', 'days_allowed' => 7],
            ['name' => 'Study Leave',         'code' => 'LV-STD',  'is_paid' => true,  'description' => 'Leave for professional development or exam',    'days_allowed' => 5],
            ['name' => 'Emergency Leave',     'code' => 'LV-EMG',  'is_paid' => true,  'description' => 'Leave for unforeseen urgent circumstances',     'days_allowed' => 3],
            ['name' => 'Unpaid Leave',        'code' => 'LV-UNPD', 'is_paid' => false, 'description' => 'Leave without pay beyond entitled quota',       'days_allowed' => null],
            ['name' => 'Compensatory Leave',  'code' => 'LV-COMP', 'is_paid' => true,  'description' => 'Leave earned for working on holidays/overtime', 'days_allowed' => null],
        ];

        foreach ($types as $type) {
            DB::table('leave_types')->updateOrInsert(
                ['name' => $type['name']],
                array_merge($type, ['updated_at' => now()])
            );
        }

        $this->command->info('Leave types seeded with codes ('.count($types).' records).');
    }
}
