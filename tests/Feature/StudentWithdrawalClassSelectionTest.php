<?php

namespace Tests\Feature;

use App\Http\Controllers\Enrollment\WithdrawalController;
use App\Models\Academics\Grade;
use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Students\Student;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentWithdrawalClassSelectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('students');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Admin');
            $table->string('email')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('level')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('2026-2027');
            $table->timestamps();
        });

        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('school_class_id')->nullable();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->string('admission_number')->nullable();
            $table->unsignedBigInteger('student_status_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('first_name_khmer')->nullable();
            $table->string('last_name_khmer')->nullable();
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('withdrawal_date');
            $table->string('reason')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('students');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_create_passes_classes_and_students_data_to_view(): void
    {
        $admin = User::create(['name' => 'Admin User', 'email' => 'admin_withdrawal@test.com']);
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin->roles()->attach($role->id);

        $grade = Grade::create(['name' => 'ថ្នាក់ទី៧', 'level' => 7]);
        $section = Section::create(['name' => 'ក']);
        $class = SchoolClass::create(['grade_id' => $grade->id, 'section_id' => $section->id]);

        $studentUser = User::create(['name' => 'សុខ ចាន់ដា', 'email' => 'sok@test.com']);
        $student = Student::create([
            'user_id' => $studentUser->id,
            'school_class_id' => $class->id,
            'admission_number' => 'STU-001',
        ]);

        $this->actingAs($admin);

        $controller = new WithdrawalController;
        $view = $controller->create();

        $this->assertArrayHasKey('classes', $view->getData());
        $this->assertArrayHasKey('studentsData', $view->getData());

        $studentsData = $view->getData()['studentsData'];
        $this->assertCount(1, $studentsData);
        $this->assertSame($student->id, $studentsData[0]['id']);
        $this->assertSame($class->id, $studentsData[0]['class_id']);
        $this->assertSame('សុខ ចាន់ដា', $studentsData[0]['name']);
    }

    public function test_store_with_class_id_and_student_id_creates_withdrawal(): void
    {
        $admin = User::create(['name' => 'Admin User 2', 'email' => 'admin_withdrawal2@test.com']);
        $role = Role::create(['name' => 'Admin 2', 'slug' => 'admin']);
        $admin->roles()->attach($role->id);

        $student = Student::create([]);

        $this->actingAs($admin);

        $controller = new WithdrawalController;
        $request = Request::create(route('withdrawals.store'), 'POST', [
            'class_id' => 1,
            'student_id' => $student->id,
            'withdrawal_date' => '2026-09-29',
            'status' => 'Pending',
            'reason' => 'ផ្លាស់ប្តូរទីលំនៅ',
        ]);

        $response = $controller->store($request);

        $this->assertTrue($response->isRedirect(route('withdrawals.index')));
        $this->assertDatabaseHas('withdrawals', [
            'student_id' => $student->id,
            'reason' => 'ផ្លាស់ប្តូរទីលំនៅ',
            'status' => 'Pending',
        ]);
    }
}
