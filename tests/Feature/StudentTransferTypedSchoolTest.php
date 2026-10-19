<?php

namespace Tests\Feature;

use App\Http\Controllers\Enrollment\TransferController;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Core\School;
use App\Models\Enrollment\StudentTransfer;
use App\Models\Students\Student;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentTransferTypedSchoolTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('student_transfers');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('students');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Admin');
            $table->string('email')->unique();
            $table->timestamps();
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

        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('student_status_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('student_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('from_school_id');
            $table->unsignedBigInteger('to_school_id');
            $table->date('transfer_date');
            $table->string('reason')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('student_transfers');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('students');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    /**
     * Test creating a transfer with newly typed school names creates the schools and associates them correctly.
     */
    public function test_store_creates_typed_schools_and_records_transfer(): void
    {
        $admin = User::create(['name' => 'Admin User', 'email' => 'admin_transfer@test.com']);
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin->roles()->attach($role->id);

        $student = Student::create([]);

        $this->actingAs($admin);

        $controller = new TransferController;
        $request = Request::create(route('transfers.store'), 'POST', [
            'student_id' => $student->id,
            'from_school_name' => 'ប្រទីបបញ្ញា',
            'to_school_name' => 'វិទ្យាល័យ ហ៊ុន សែន ព្រែកអំពិល',
            'transfer_date' => '2026-09-29',
            'status' => 'Pending',
            'reason' => 'ប្តូរទីលំនៅ',
        ]);

        $response = $controller->store($request);

        $this->assertTrue($response->isRedirect(route('transfers.index')));

        // Check schools were created
        $fromSchool = School::where('name', 'ប្រទីបបញ្ញា')->first();
        $toSchool = School::where('name', 'វិទ្យាល័យ ហ៊ុន សែន ព្រែកអំពិល')->first();

        $this->assertNotNull($fromSchool);
        $this->assertNotNull($toSchool);

        // Check transfer record
        $transfer = StudentTransfer::first();
        $this->assertNotNull($transfer);
        $this->assertSame($fromSchool->id, $transfer->from_school_id);
        $this->assertSame($toSchool->id, $transfer->to_school_id);
    }

    /**
     * Test creating a transfer with class_id and student_id succeeds.
     */
    public function test_store_with_class_id_and_student_id(): void
    {
        $admin = User::create(['name' => 'Admin User 2', 'email' => 'admin_transfer2@test.com']);
        $role = Role::create(['name' => 'Admin 2', 'slug' => 'admin']);
        $admin->roles()->attach($role->id);

        $student = Student::create([]);

        $this->actingAs($admin);

        $controller = new TransferController;
        $request = Request::create(route('transfers.store'), 'POST', [
            'class_id' => 1,
            'student_id' => $student->id,
            'from_school_name' => 'សាលាបឋមសិក្សា ហ៊ុន សែន',
            'to_school_name' => 'អនុវិទ្យាល័យ ព្រែកអំពិល',
            'transfer_date' => '2026-09-29',
            'status' => 'Pending',
        ]);

        $response = $controller->store($request);

        $this->assertTrue($response->isRedirect(route('transfers.index')));
        $this->assertDatabaseHas('student_transfers', [
            'student_id' => $student->id,
        ]);
    }
}
