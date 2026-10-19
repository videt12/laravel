<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use App\Models\Employees\EmployeeStatus;
use App\Models\Employees\LeaveType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeesSampleSeeder extends Seeder
{
    public function run(): void
    {
        $deptAdmin = EmployeeDepartment::where('name', 'Administration')->first();
        $deptAcad = EmployeeDepartment::where('name', 'Academic')->first();
        $deptFinance = EmployeeDepartment::where('name', 'Finance')->first();
        $deptIT = EmployeeDepartment::where('name', 'IT Department')->first();
        $deptScience = EmployeeDepartment::where('name', 'Science Department')->first();
        $deptLibrary = EmployeeDepartment::where('name', 'Library')->first();

        $posPrincipal = EmployeePosition::where('name', 'Principal')->first();
        $posHOD = EmployeePosition::where('name', 'Head of Department')->first();
        $posSrTeacher = EmployeePosition::where('name', 'Senior Teacher')->first();
        $posTeacher = EmployeePosition::where('name', 'Teacher')->first();
        $posAcct = EmployeePosition::where('name', 'Accountant')->first();
        $posITTech = EmployeePosition::where('name', 'IT Technician')->first();
        $posLibrarian = EmployeePosition::where('name', 'Librarian')->first();

        $statusActive = EmployeeStatus::where('name', 'Active')->first() ?? EmployeeStatus::first();
        $statusLeave = EmployeeStatus::where('name', 'On Leave')->first() ?? $statusActive;

        $leaveAnnual = LeaveType::where('name', 'Annual Leave')->first() ?? LeaveType::first();
        $leaveSick = LeaveType::where('name', 'Sick Leave')->first() ?? LeaveType::first();

        $sampleStaff = [
            [
                'name' => 'Dr. Arthur Sterling',
                'email' => 'arthur.sterling@school.edu',
                'code' => 'EMP-1001',
                'department_id' => $deptAdmin->id ?? 1,
                'position_id' => $posPrincipal->id ?? 1,
                'status_id' => $statusActive->id,
                'joining_date' => '2019-01-15',
                'dob' => '1975-06-20',
                'gender' => 'Male',
                'blood_group' => 'O+',
                'basic_salary' => 8500.00,
                'phone' => '+1 (555) 101-2001',
                'emergency_phone' => '+1 (555) 901-2001',
                'contact_email' => 'sterling.personal@gmail.com',
                'current_address' => '742 Evergreen Terrace, Suite 100, Boston, MA',
                'permanent_address' => '742 Evergreen Terrace, Suite 100, Boston, MA',
            ],
            [
                'name' => 'Prof. Eleanor Vance',
                'email' => 'eleanor.vance@school.edu',
                'code' => 'EMP-1002',
                'department_id' => $deptScience->id ?? $deptAcad->id ?? 1,
                'position_id' => $posHOD->id ?? 2,
                'status_id' => $statusActive->id,
                'joining_date' => '2020-08-01',
                'dob' => '1982-11-14',
                'gender' => 'Female',
                'blood_group' => 'A+',
                'basic_salary' => 6200.00,
                'phone' => '+1 (555) 102-3002',
                'emergency_phone' => '+1 (555) 902-3002',
                'contact_email' => 'eleanor.vance@outlook.com',
                'current_address' => '12 Oak Ridge Lane, Cambridge, MA',
                'permanent_address' => '12 Oak Ridge Lane, Cambridge, MA',
            ],
            [
                'name' => 'Marcus Brody',
                'email' => 'marcus.brody@school.edu',
                'code' => 'EMP-1003',
                'department_id' => $deptAcad->id ?? 1,
                'position_id' => $posSrTeacher->id ?? 3,
                'status_id' => $statusActive->id,
                'joining_date' => '2021-03-10',
                'dob' => '1986-03-25',
                'gender' => 'Male',
                'blood_group' => 'B+',
                'basic_salary' => 5100.00,
                'phone' => '+1 (555) 103-4003',
                'emergency_phone' => '+1 (555) 903-4003',
                'contact_email' => 'marcus.brody@yahoo.com',
                'current_address' => '88 University Crescent, Somerville, MA',
                'permanent_address' => '400 Pine Avenue, Worcester, MA',
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah.jenkins@school.edu',
                'code' => 'EMP-1004',
                'department_id' => $deptAcad->id ?? 1,
                'position_id' => $posTeacher->id ?? 4,
                'status_id' => $statusLeave->id,
                'joining_date' => '2022-09-01',
                'dob' => '1990-09-18',
                'gender' => 'Female',
                'blood_group' => 'AB+',
                'basic_salary' => 4400.00,
                'phone' => '+1 (555) 104-5004',
                'emergency_phone' => '+1 (555) 904-5004',
                'contact_email' => 's.jenkins@gmail.com',
                'current_address' => '45 Maple Grove Court, Medford, MA',
                'permanent_address' => '45 Maple Grove Court, Medford, MA',
            ],
            [
                'name' => 'David K. Wilson',
                'email' => 'david.wilson@school.edu',
                'code' => 'EMP-1005',
                'department_id' => $deptFinance->id ?? 1,
                'position_id' => $posAcct->id ?? 5,
                'status_id' => $statusActive->id,
                'joining_date' => '2021-06-15',
                'dob' => '1984-12-05',
                'gender' => 'Male',
                'blood_group' => 'O-',
                'basic_salary' => 4800.00,
                'phone' => '+1 (555) 105-6005',
                'emergency_phone' => '+1 (555) 905-6005',
                'contact_email' => 'david.wilson.cpa@gmail.com',
                'current_address' => '310 Beacon Street, Apt 4B, Boston, MA',
                'permanent_address' => '310 Beacon Street, Apt 4B, Boston, MA',
            ],
            [
                'name' => 'Alexander Chen',
                'email' => 'alexander.chen@school.edu',
                'code' => 'EMP-1006',
                'department_id' => $deptIT->id ?? 1,
                'position_id' => $posITTech->id ?? 6,
                'status_id' => $statusActive->id,
                'joining_date' => '2023-01-10',
                'dob' => '1994-07-22',
                'gender' => 'Male',
                'blood_group' => 'A-',
                'basic_salary' => 4200.00,
                'phone' => '+1 (555) 106-7006',
                'emergency_phone' => '+1 (555) 906-7006',
                'contact_email' => 'achen.tech@gmail.com',
                'current_address' => '55 Tech Way, Waltham, MA',
                'permanent_address' => '55 Tech Way, Waltham, MA',
            ],
            [
                'name' => 'Miriam Al-Mansoor',
                'email' => 'miriam.almansoor@school.edu',
                'code' => 'EMP-1007',
                'department_id' => $deptLibrary->id ?? 1,
                'position_id' => $posLibrarian->id ?? 7,
                'status_id' => $statusActive->id,
                'joining_date' => '2020-11-01',
                'dob' => '1988-04-30',
                'gender' => 'Female',
                'blood_group' => 'O+',
                'basic_salary' => 4000.00,
                'phone' => '+1 (555) 107-8007',
                'emergency_phone' => '+1 (555) 907-8007',
                'contact_email' => 'miriam.lib@gmail.com',
                'current_address' => '19 Harvard Yard Way, Brookline, MA',
                'permanent_address' => '19 Harvard Yard Way, Brookline, MA',
            ],
        ];

        $adminUser = User::first();

        foreach ($sampleStaff as $staff) {
            // 1. Create or retrieve User
            $user = User::updateOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'password' => Hash::make('password123'),
                    'status' => 'active',
                ]
            );

            // Assign role to user based on position/sample account
            $roleSlug = match ($staff['email']) {
                'arthur.sterling@school.edu' => 'teacher',
                'david.wilson@school.edu' => 'accountant',
                'miriam.almansoor@school.edu' => 'librarian',
                default => 'teacher',
            };
            $roleId = DB::table('roles')->where('slug', $roleSlug)->value('id');
            if ($roleId) {
                DB::table('user_roles')->updateOrInsert(
                    ['user_id' => $user->id, 'role_id' => $roleId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }

            // 2. Create or retrieve Employee
            $employee = Employee::updateOrCreate(
                ['employee_code' => $staff['code']],
                [
                    'user_id' => $user->id,
                    'employee_department_id' => $staff['department_id'],
                    'employee_position_id' => $staff['position_id'],
                    'employee_status_id' => $staff['status_id'],
                    'date_of_joining' => $staff['joining_date'],
                    'date_of_birth' => $staff['dob'],
                    'gender' => $staff['gender'],
                    'blood_group' => $staff['blood_group'],
                    'basic_salary' => $staff['basic_salary'],
                ]
            );

            // 3. Create or update Contact Details
            $employee->employeeContact()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'phone' => $staff['phone'],
                    'emergency_phone' => $staff['emergency_phone'],
                    'email' => $staff['contact_email'],
                    'current_address' => $staff['current_address'],
                    'permanent_address' => $staff['permanent_address'],
                ]
            );

            // 4. Seed recent Attendance records (past 5 working days)
            for ($d = 1; $d <= 5; $d++) {
                $date = Carbon::now()->subDays($d);
                if ($date->isWeekend()) {
                    continue;
                }

                $employee->employee_attendances()->updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'attendance_date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => ($d === 3 && $staff['code'] === 'EMP-1004') ? 'absent' : 'present',
                        'in_time' => '08:'.str_pad(rand(10, 45), 2, '0', STR_PAD_LEFT).':00',
                        'out_time' => '16:'.str_pad(rand(30, 59), 2, '0', STR_PAD_LEFT).':00',
                        'remarks' => 'Standard campus shift',
                    ]
                );
            }

            // 5. Seed a sample Leave request if on leave or recent
            if ($staff['code'] === 'EMP-1004') {
                $employee->employee_leaves()->updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'start_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    ],
                    [
                        'leave_type_id' => $leaveSick->id ?? 1,
                        'end_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                        'reason' => 'Medical recovery leave for seasonal flu',
                        'status' => 'approved',
                        'approved_by' => $adminUser->id ?? $user->id,
                    ]
                );
            } elseif ($staff['code'] === 'EMP-1003') {
                $employee->employee_leaves()->updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'start_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                    ],
                    [
                        'leave_type_id' => $leaveAnnual->id ?? 1,
                        'end_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
                        'reason' => 'Family summer holiday travel',
                        'status' => 'pending',
                        'approved_by' => null,
                    ]
                );
            }
        }

        $this->command->info('Sample employee profiles ('.count($sampleStaff).') with contacts, attendances, and leaves seeded successfully.');
    }
}
