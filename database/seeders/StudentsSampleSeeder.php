<?php

namespace Database\Seeders;

use App\Models\Academics\Grade;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolShift;
use App\Models\Students\Student;
use App\Models\Students\StudentProfile;
use App\Models\Students\StudentStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentsSampleSeeder extends Seeder
{
    /**
     * Run the database seeds for sample students.
     */
    public function run(): void
    {
        $statusActive = StudentStatus::where('name', 'Active')->first() ?? StudentStatus::first();
        $academicYear = AcademicYear::first();
        $grade = Grade::where('name', 'Grade 1')->first() ?? Grade::first();
        $section = Section::where('name', 'Section A')->first() ?? Section::first();
        $shift = SchoolShift::first();

        // Ensure at least one SchoolClass exists for students to enroll into
        $schoolClass = SchoolClass::firstOrCreate(
            [
                'grade_id' => $grade?->id ?? 1,
                'section_id' => $section?->id ?? 1,
                'academic_year_id' => $academicYear?->id ?? 1,
            ],
            [
                'capacity' => 40,
            ]
        );

        $students = [
            [
                'name' => 'Videt Or',
                'first_name' => 'Or',
                'last_name' => 'Videt',
                'first_name_khmer' => 'Or',
                'last_name_khmer' => 'Videt',
                'email' => 'videtsokhon03@gmail.com',
                'admission_number' => 'BPS00001',
                'phone' => '0999999977',
                'gender' => 'Male',
                'dob' => '2010-10-23',
                'photo' => 'student-photos/wwxgsUaiZTfSGGG7owvhdZh7tpx1igdV54CjaXvV.webp',
                'nationality' => 'Cambodian',
                'study_mode' => 'full_time',
            ],
            [
                'name' => 'Sokha Chan',
                'first_name' => 'Chan',
                'last_name' => 'Sokha',
                'first_name_khmer' => 'ចាន់',
                'last_name_khmer' => 'សុខា',
                'email' => 'sokha.chan@school.edu.kh',
                'admission_number' => 'BPS00002',
                'phone' => '012888999',
                'gender' => 'Female',
                'dob' => '2011-05-15',
                'photo' => null,
                'nationality' => 'Cambodian',
                'study_mode' => 'full_time',
            ],
            [
                'name' => 'Dara Meng',
                'first_name' => 'Meng',
                'last_name' => 'Dara',
                'first_name_khmer' => 'ម៉េង',
                'last_name_khmer' => 'ដារ៉ា',
                'email' => 'dara.meng@school.edu.kh',
                'admission_number' => 'BPS00003',
                'phone' => '098777666',
                'gender' => 'Male',
                'dob' => '2010-08-20',
                'photo' => null,
                'nationality' => 'Cambodian',
                'study_mode' => 'part_time',
            ],
        ];

        foreach ($students as $data) {
            // 1. Create or retrieve User
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                ]
            );

            // Assign student role if available
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('Student');
                } catch (\Throwable $e) {
                    // Ignore if role doesn't exist
                }
            }

            // 2. Create or retrieve Student record
            $student = Student::firstOrCreate(
                ['admission_number' => $data['admission_number']],
                [
                    'user_id' => $user->id,
                    'admission_date' => Carbon::now()->subMonths(2),
                    'enrollment_date' => Carbon::now()->subMonths(2)->toDateString(),
                    'student_status_id' => $statusActive?->id ?? 1,
                    'academic_year_id' => $academicYear?->id ?? 1,
                    'school_class_id' => $schoolClass->id,
                    'section_id' => $section?->id ?? 1,
                    'school_shift_id' => $shift?->id ?? 1,
                    'study_mode' => $data['study_mode'],
                ]
            );

            // 3. Create or retrieve StudentProfile
            StudentProfile::firstOrCreate(
                ['student_id' => $student->id],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'first_name_khmer' => $data['first_name_khmer'],
                    'last_name_khmer' => $data['last_name_khmer'],
                    'dob' => $data['dob'],
                    'gender' => $data['gender'],
                    'nationality' => $data['nationality'],
                    'phone' => $data['phone'],
                    'photo' => $data['photo'],
                ]
            );
        }
    }
}
