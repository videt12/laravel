<?php

namespace App\Http\Controllers\BulkData;

use App\Http\Controllers\Controller;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use App\Models\Core\School;
use App\Models\Examination\Exam;
use App\Models\Examination\StudentGrade;
use App\Models\Students\Guardian;
use App\Models\Students\Student;
use App\Models\Students\StudentStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BulkDataController extends Controller
{
    /**
     * Download sample CSV template for bulk student import.
     */
    public function downloadStudentSample()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bps_student_import_sample.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for proper Khmer and Excel rendering
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'first_name',
                'last_name',
                'first_name_khmer',
                'last_name_khmer',
                'gender',
                'dob',
                'class',
                'section',
                'study_mode',
                'admission_number',
                'roll_number',
                'phone',
                'email',
                'address',
                'guardian_name',
                'guardian_relationship',
                'guardian_phone',
            ]);

            // Sample Row 1: Full-time Student
            fputcsv($handle, [
                'Sokha',
                'Chan',
                'ចាន់',
                'សុខា',
                'Female',
                '2010-05-12',
                'Grade 10',
                'A',
                'full_time',
                'BPS00101',
                '1',
                '012345678',
                'chan.sokha@school.local',
                'Phnom Penh, Cambodia',
                'Chan Dara',
                'Father',
                '012987654',
            ]);

            // Sample Row 2: Part-time Student
            fputcsv($handle, [
                'Vannak',
                'Meas',
                'មាស',
                'វណ្ណៈ',
                'Male',
                '2009-11-20',
                'Grade 10',
                'B',
                'part_time',
                'BPS00102',
                '2',
                '098765432',
                'meas.vannak@school.local',
                'Kandal, Cambodia',
                'Meas Rithy',
                'Father',
                '098112233',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Handle bulk student CSV upload and creation.
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $handle = fopen($filePath, 'r');
        if (! $handle) {
            return back()->with('error', 'មិនអាចបើកឯកសារ CSV បានទេ។ សូមព្យាយាមម្តងទៀត។');
        }

        // Fetch active academic year
        $activeYear = AcademicYear::where('is_active', 1)->first()
            ?? AcademicYear::latest('start_date')->first();

        if (! $activeYear) {
            return back()->with('error', 'មិនទាន់មានឆ្នាំសិក្សាណាមួយត្រូវបានកំណត់ជា Active នៅក្នុងប្រព័ន្ធឡើយ។');
        }

        // Preload classes and sections
        $classes = SchoolClass::with('grade')->get();
        $sections = Section::all();
        $defaultStatus = StudentStatus::first();
        $school = School::first();

        // Read header line
        $rawHeaders = fgetcsv($handle);
        if (! $rawHeaders) {
            fclose($handle);

            return back()->with('error', 'ឯកសារ CSV ទទេ។ គ្មានទិន្នន័យឡើយ។');
        }

        // Clean UTF-8 BOM if present on first header column
        $rawHeaders[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeaders[0]);
        $headers = array_map(function ($h) {
            return strtolower(trim(str_replace([' ', '-'], '_', $h)));
        }, $rawHeaders);

        $successCount = 0;
        $skippedCount = 0;
        $rowErrors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            if (count($row) < count($headers)) {
                $row = array_pad($row, count($headers), '');
            }

            $data = array_combine(array_slice($headers, 0, count($row)), array_slice($row, 0, count($headers)));

            $firstName = trim($data['first_name'] ?? '');
            $lastName = trim($data['last_name'] ?? '');
            $firstNameKhmer = trim($data['first_name_khmer'] ?? '');
            $lastNameKhmer = trim($data['last_name_khmer'] ?? '');

            if (empty($firstName) && empty($firstNameKhmer)) {
                $rowErrors[] = "ជួរដេកទី {$rowNumber}៖ ត្រូវតែមានឈ្មោះសិស្ស (First Name ឬ First Name Khmer)។";
                $skippedCount++;

                continue;
            }

            // Gender mapping
            $rawGender = strtolower(trim($data['gender'] ?? ''));
            $gender = match (true) {
                str_contains($rawGender, 'female') || str_contains($rawGender, 'ស្រី') || $rawGender === 'f' => 'Female',
                str_contains($rawGender, 'other') || str_contains($rawGender, 'ផ្សេងៗ') => 'Other',
                default => 'Male',
            };

            // Date of birth
            $rawDob = trim($data['dob'] ?? '');
            $dob = null;
            if ($rawDob) {
                try {
                    $dob = Carbon::parse($rawDob)->format('Y-m-d');
                } catch (\Exception $e) {
                    $dob = '2010-01-01';
                }
            } else {
                $dob = '2010-01-01';
            }

            // Resolve Class
            $rawClass = trim($data['class'] ?? '');
            $classModel = null;
            if (is_numeric($rawClass)) {
                $classModel = $classes->firstWhere('id', (int) $rawClass);
            }
            if (! $classModel && $rawClass) {
                $classModel = $classes->first(function ($c) use ($rawClass) {
                    return strcasecmp($c->name, $rawClass) === 0
                        || ($c->grade && strcasecmp($c->grade->name, $rawClass) === 0);
                });
            }
            if (! $classModel) {
                $classModel = $classes->first();
            }

            // Resolve Section
            $rawSection = trim($data['section'] ?? '');
            $sectionModel = null;
            if (is_numeric($rawSection)) {
                $sectionModel = $sections->firstWhere('id', (int) $rawSection);
            }
            if (! $sectionModel && $rawSection) {
                $sectionModel = $sections->first(function ($s) use ($rawSection) {
                    return strcasecmp($s->name, $rawSection) === 0;
                });
            }

            // Admission Number
            $admNumber = trim($data['admission_number'] ?? '');
            if (! empty($admNumber) && Student::where('admission_number', $admNumber)->exists()) {
                $rowErrors[] = "ជួរដេកទី {$rowNumber}៖ អត្តលេខ [{$admNumber}] មានក្នុងប្រព័ន្ធរួចហើយ។";
                $skippedCount++;

                continue;
            }

            // Email
            $email = trim($data['email'] ?? '');
            if (! empty($email) && User::where('email', $email)->exists()) {
                $email = ''; // auto-generate fallback email
            }

            try {
                DB::transaction(function () use (
                    $data,
                    $firstName,
                    $lastName,
                    $firstNameKhmer,
                    $lastNameKhmer,
                    $gender,
                    $dob,
                    $classModel,
                    $sectionModel,
                    $activeYear,
                    $defaultStatus,
                    $admNumber,
                    $email,
                    $school
                ) {
                    $admissionNumber = ! empty($admNumber) ? $admNumber : Student::generateNextAdmissionNumber();
                    $userEmail = ! empty($email) ? $email : strtolower($admissionNumber).'@school.local';
                    $fullName = trim($firstName.' '.$lastName) ?: trim($firstNameKhmer.' '.$lastNameKhmer);

                    // 1. Create or link user
                    $user = User::create([
                        'name' => $fullName,
                        'email' => $userEmail,
                        'password' => Hash::make('student123'),
                        'status' => 'active',
                    ]);

                    $studentRoleId = DB::table('roles')->where('slug', 'student')->value('id');
                    if ($studentRoleId) {
                        $user->roles()->syncWithoutDetaching([$studentRoleId]);
                    }

                    $rawStudyMode = strtolower(trim($data['study_mode'] ?? ''));
                    $studyMode = in_array($rawStudyMode, ['part_time', 'part-time', 'part time', 'ក្រៅម៉ោង', 'មួយពេល']) ? 'part_time' : 'full_time';

                    // 2. Create Student
                    $student = Student::create([
                        'school_id' => $school?->id ?? 1,
                        'user_id' => $user->id,
                        'admission_number' => $admissionNumber,
                        'admission_date' => now()->toDateString(),
                        'enrollment_date' => now()->toDateString(),
                        'roll_number' => trim($data['roll_number'] ?? '') ?: null,
                        'student_status_id' => $defaultStatus?->id ?? 1,
                        'academic_year_id' => $activeYear->id,
                        'school_class_id' => $classModel?->id,
                        'section_id' => $sectionModel?->id,
                        'study_mode' => $studyMode,
                    ]);

                    // 3. Create StudentProfile
                    $student->studentProfile()->create([
                        'first_name' => $firstName ?: $firstNameKhmer,
                        'last_name' => $lastName ?: $lastNameKhmer,
                        'first_name_khmer' => $firstNameKhmer ?: null,
                        'last_name_khmer' => $lastNameKhmer ?: null,
                        'gender' => $gender,
                        'dob' => $dob,
                        'phone' => trim($data['phone'] ?? '') ?: null,
                        'nationality' => 'Cambodian',
                    ]);

                    // 4. Create Student Address
                    $address = trim($data['address'] ?? '');
                    if ($address) {
                        $student->studentAddresse()->create([
                            'address_line_1' => $address,
                        ]);
                    }

                    // 5. Guardian Info (if present)
                    $guardianName = trim($data['guardian_name'] ?? '');
                    if ($guardianName) {
                        $guardianPhone = trim($data['guardian_phone'] ?? '') ?: '012000000';
                        $guardianEmail = 'g.'.strtolower($admissionNumber).'@school.local';

                        $guardianUser = User::firstOrCreate(
                            ['email' => $guardianEmail],
                            [
                                'name' => $guardianName,
                                'password' => Hash::make('guardian123'),
                                'status' => 'active',
                            ]
                        );

                        $guardian = Guardian::create([
                            'user_id' => $guardianUser->id,
                            'name' => $guardianName,
                            'phone' => $guardianPhone,
                            'relationship' => trim($data['guardian_relationship'] ?? 'Guardian') ?: 'Parent',
                        ]);

                        $student->guardians()->attach($guardian->id, [
                            'relationship' => $guardian->relationship,
                        ]);
                    }
                });

                $successCount++;
            } catch (\Exception $e) {
                $rowErrors[] = "ជួរដេកទី {$rowNumber}៖ បរាជ័យក្នុងការរក្សាទុក (".$e->getMessage().')';
                $skippedCount++;
            }
        }

        fclose($handle);

        $msg = "ដំណើរការរួចរាល់៖ បាននាំចូលសិស្សចំនួន {$successCount} នាក់ដោយជោគជ័យ!";
        if ($skippedCount > 0) {
            $msg .= " (រំលង/មានបញ្ហា {$skippedCount} នាក់)";
        }

        return back()->with('success', $msg)->with('import_errors', $rowErrors);
    }

    /**
     * Export students to CSV matching active filters.
     */
    public function exportStudents(Request $request)
    {
        abort_if(! auth()->user()?->canAccess(['reports.view', 'students.export', 'admin', 'principal']), 403, 'Unauthorized access to export student records.');

        $search = $request->input('search');
        $filterStatus = $request->input('status');
        $filterGender = $request->input('gender');
        $filterAcademicYear = $request->input('academic_year_id');
        $filterClass = $request->input('class_id');
        $filterSection = $request->input('section_id');
        $filterStudyMode = $request->input('study_mode');

        $query = Student::with([
            'user',
            'studentProfile',
            'schoolClass.grade',
            'section',
            'studentStatus',
            'academicYear',
            'guardians',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                    ->orWhere('roll_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('studentProfile', function ($pq) use ($search) {
                        $pq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('first_name_khmer', 'like', "%{$search}%")
                            ->orWhere('last_name_khmer', 'like', "%{$search}%");
                    });
            });
        }

        if ($filterStatus) {
            $query->where('student_status_id', $filterStatus);
        }
        if ($filterClass) {
            $query->where('school_class_id', $filterClass);
        }
        if ($filterSection) {
            $query->where('section_id', $filterSection);
        }
        if ($filterAcademicYear) {
            $query->where('academic_year_id', $filterAcademicYear);
        }
        if ($filterStudyMode) {
            $query->where('study_mode', $filterStudyMode);
        }

        $students = $query->latest('id')->get();

        $fileName = 'students_export_'.date('Y_m_d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () use ($students) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, [
                'Admission No',
                'Student Name (Latin)',
                'Student Name (Khmer)',
                'Gender',
                'Date of Birth',
                'Class',
                'Section',
                'Study Mode (ទម្រង់សិក្សា)',
                'Academic Year',
                'Status',
                'Phone',
                'Email',
                'Guardian Name',
                'Guardian Phone',
                'Enrollment Date',
            ]);

            foreach ($students as $s) {
                $prof = $s->studentProfile;
                $khmerName = trim(($prof?->last_name_khmer ?? '').' '.($prof?->first_name_khmer ?? ''));
                $latinName = trim(($prof?->first_name ?? '').' '.($prof?->last_name ?? '')) ?: ($s->user?->name ?? '');
                $guardian = $s->guardians->first();

                fputcsv($handle, [
                    $s->admission_number,
                    $latinName,
                    $khmerName,
                    $prof?->gender ?? '—',
                    $prof?->dob ? $prof->dob->format('Y-m-d') : '—',
                    $s->schoolClass?->name ?? ($s->schoolClass?->grade?->name ?? '—'),
                    $s->section?->name ?? '—',
                    $s->study_mode_label,
                    $s->academicYear?->name ?? '—',
                    $s->studentStatus?->name ?? 'Active',
                    $prof?->phone ?? '—',
                    $s->user?->email ?? '—',
                    $guardian?->name ?? '—',
                    $guardian?->phone ?? '—',
                    $s->enrollment_date ? Carbon::parse($s->enrollment_date)->format('Y-m-d') : '—',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export overall student exam grades.
     */
    public function exportExamGrades(Request $request, Exam $exam)
    {
        $classId = $request->input('class_id');
        $query = StudentGrade::where('exam_id', $exam->id)
            ->with(['student.studentProfile', 'student.schoolClass', 'student.section'])
            ->orderByDesc('total_marks');

        if ($classId) {
            $query->whereHas('student', fn ($q) => $q->where('school_class_id', $classId));
        }

        $grades = $query->get();
        $fileName = 'exam_grades_'.str_replace(' ', '_', strtolower($exam->name)).'_'.date('Y_m_d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () use ($grades) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Rank',
                'Admission No',
                'Student Name (Latin)',
                'Student Name (Khmer)',
                'Class',
                'Section',
                'Total Marks',
                'Percentage (%)',
                'Overall Grade',
                'GPA',
                'Result Status',
            ]);

            $rank = 1;
            foreach ($grades as $g) {
                $student = $g->student;
                $prof = $student?->studentProfile;
                $latin = trim(($prof?->first_name ?? '').' '.($prof?->last_name ?? '')) ?: ($student?->user?->name ?? '');
                $khmer = trim(($prof?->last_name_khmer ?? '').' '.($prof?->first_name_khmer ?? ''));

                fputcsv($handle, [
                    $rank++,
                    $student?->admission_number ?? '—',
                    $latin,
                    $khmer,
                    $student?->schoolClass?->name ?? '—',
                    $student?->section?->name ?? '—',
                    number_format((float) $g->total_marks, 2),
                    number_format((float) $g->percentage, 2).'%',
                    $g->grade ?? '—',
                    number_format((float) $g->gpa, 2),
                    ucfirst($g->result_status ?? 'Passed'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
