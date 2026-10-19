<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeePositionsDepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        // ─── DEPARTMENTS ─────────────────────────────────────────
        $departments = [
            ['name' => 'Administration',     'code' => 'DEP-ADMIN', 'status' => 'active', 'description' => 'School administration, reception, and management office'],
            ['name' => 'Academic',           'code' => 'DEP-ACAD',  'status' => 'active', 'description' => 'Teaching staff and academic affairs'],
            ['name' => 'Finance',            'code' => 'DEP-FIN',   'status' => 'active', 'description' => 'Accounts, payroll, and financial management'],
            ['name' => 'Library',            'code' => 'DEP-LIB',   'status' => 'active', 'description' => 'Library management and resource center'],
            ['name' => 'IT Department',      'code' => 'DEP-IT',    'status' => 'active', 'description' => 'Information technology and computer lab management'],
            ['name' => 'Science Department', 'code' => 'DEP-SCI',   'status' => 'active', 'description' => 'Science labs, equipment, and science teachers'],
            ['name' => 'Physical Education', 'code' => 'DEP-PE',    'status' => 'active', 'description' => 'Sports, physical training, and health education'],
            ['name' => 'Counseling',         'code' => 'DEP-COUNS', 'status' => 'active', 'description' => 'Student counseling, mental health, and welfare'],
            ['name' => 'Security',           'code' => 'DEP-SEC',   'status' => 'active', 'description' => 'School security and premises management'],
            ['name' => 'Maintenance',        'code' => 'DEP-MAINT', 'status' => 'active', 'description' => 'Building maintenance, cleaning, and groundskeeping'],
        ];

        foreach ($departments as $dept) {
            DB::table('employee_departments')->updateOrInsert(
                ['name' => $dept['name']],
                array_merge($dept, ['updated_at' => now()])
            );
        }

        // ─── POSITIONS ───────────────────────────────────────────
        $positions = [
            ['name' => 'Principal',               'code' => 'POS-PRIN',   'status' => 'active', 'description' => 'Head of the school and overall administrator'],
            ['name' => 'Vice Principal',          'code' => 'POS-VPRIN',  'status' => 'active', 'description' => 'Deputy head of the school'],
            ['name' => 'Head of Department',      'code' => 'POS-HOD',    'status' => 'active', 'description' => 'Academic department lead teacher'],
            ['name' => 'Senior Teacher',          'code' => 'POS-SRTCHR', 'status' => 'active', 'description' => 'Experienced teacher with senior designation'],
            ['name' => 'Teacher',                 'code' => 'POS-TCHR',   'status' => 'active', 'description' => 'Classroom teacher responsible for subject delivery'],
            ['name' => 'Assistant Teacher',       'code' => 'POS-ASST',   'status' => 'active', 'description' => 'Supports the main classroom teacher'],
            ['name' => 'Lab Instructor',          'code' => 'POS-LAB',    'status' => 'active', 'description' => 'Conducts and supervises lab sessions'],
            ['name' => 'Librarian',               'code' => 'POS-LIB',    'status' => 'active', 'description' => 'Manages the school library and resources'],
            ['name' => 'Accountant',              'code' => 'POS-ACCT',   'status' => 'active', 'description' => 'Manages school finances and fee collection'],
            ['name' => 'Administrative Officer',  'code' => 'POS-ADMIN',  'status' => 'active', 'description' => 'Handles administrative tasks and records'],
            ['name' => 'IT Technician',           'code' => 'POS-ITTECH', 'status' => 'active', 'description' => 'Manages computer labs and IT infrastructure'],
            ['name' => 'Counselor',               'code' => 'POS-COUNS',  'status' => 'active', 'description' => 'Provides student guidance and counseling'],
            ['name' => 'Security Guard',          'code' => 'POS-SECG',   'status' => 'active', 'description' => 'Responsible for school security and access'],
            ['name' => 'Peon / Office Attendant', 'code' => 'POS-PEON',   'status' => 'active', 'description' => 'Assists in general office duties'],
            ['name' => 'Cleaner / Janitor',       'code' => 'POS-CLN',    'status' => 'active', 'description' => 'Responsible for school cleanliness'],
            ['name' => 'Driver',                  'code' => 'POS-DRV',    'status' => 'active', 'description' => 'School transport vehicle driver'],
        ];

        foreach ($positions as $pos) {
            DB::table('employee_positions')->updateOrInsert(
                ['name' => $pos['name']],
                array_merge($pos, ['updated_at' => now()])
            );
        }

        // ─── EMPLOYEE STATUSES ──────────────────────────────────
        $empStatuses = [
            ['name' => 'Active',       'code' => 'STAT-ACT',  'is_active' => true,  'description' => 'Currently employed and on duty'],
            ['name' => 'On Leave',     'code' => 'STAT-LV',   'is_active' => true,  'description' => 'Currently on approved leave'],
            ['name' => 'Suspended',    'code' => 'STAT-SUSP', 'is_active' => false, 'description' => 'Temporarily suspended pending investigation'],
            ['name' => 'Resigned',     'code' => 'STAT-RES',  'is_active' => false, 'description' => 'Has formally resigned from the school'],
            ['name' => 'Retired',      'code' => 'STAT-RET',  'is_active' => false, 'description' => 'Has retired from service'],
            ['name' => 'Terminated',   'code' => 'STAT-TERM', 'is_active' => false, 'description' => 'Employment has been terminated'],
            ['name' => 'Probation',    'code' => 'STAT-PROB', 'is_active' => true,  'description' => 'Currently serving a probationary period'],
            ['name' => 'Contract',     'code' => 'STAT-CONT', 'is_active' => true,  'description' => 'Employed on a fixed-term contract'],
        ];

        foreach ($empStatuses as $status) {
            DB::table('employee_statuses')->updateOrInsert(
                ['name' => $status['name']],
                array_merge($status, ['updated_at' => now()])
            );
        }

        $this->command->info('Employee positions ('.count($positions).'), departments ('.count($departments).'), and statuses ('.count($empStatuses).') seeded with codes.');
    }
}
