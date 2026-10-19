<?php

namespace Tests\Feature;

use App\Http\Controllers\Employees\EmployeeController;
use App\Models\Auth\User;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeContact;
use App\Models\Employees\EmployeeDepartment;
use App\Models\Employees\EmployeePosition;
use App\Models\Employees\EmployeeStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeComprehensiveInformationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure database tables needed for testing are available
        Schema::dropIfExists('employee_contacts');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('employee_departments');
        Schema::dropIfExists('employee_positions');
        Schema::dropIfExists('employee_statuses');
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->default('password');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('employee_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('employee_code')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name_khmer')->nullable();
            $table->string('last_name_khmer')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('national_id')->nullable();
            $table->string('nationality')->default('ខ្មែរ');
            $table->string('place_of_birth')->nullable();
            $table->string('birth_village', 100)->nullable();
            $table->string('birth_commune', 100)->nullable();
            $table->string('birth_district', 100)->nullable();
            $table->string('birth_province', 100)->nullable();
            $table->unsignedBigInteger('employee_department_id')->nullable();
            $table->unsignedBigInteger('employee_position_id')->nullable();
            $table->unsignedBigInteger('employee_status_id')->nullable();
            $table->string('employee_type')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('work_location')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->decimal('basic_salary', 12, 2)->nullable();
            $table->string('salary_type')->default('Monthly');
            $table->decimal('allowance', 12, 2)->default(0);
            $table->decimal('deduction', 12, 2)->default(0);
            $table->string('payment_method')->default('Bank Transfer');
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->date('salary_effective_date')->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->string('graduation_year', 10)->nullable();
            $table->string('professional_certificate')->nullable();
            $table->string('teaching_qualification')->nullable();
            $table->text('skills')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_relationship')->nullable();
            $table->text('emergency_address')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('village')->nullable();
            $table->string('commune')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->timestamps();
        });
    }

    public function test_employee_full_name_attribute_prefers_khmer_then_latin(): void
    {
        $employee = new Employee([
            'last_name_khmer' => 'សុខ',
            'first_name_khmer' => 'ចាន់ដារ៉ា',
            'last_name' => 'Sok',
            'first_name' => 'Dara',
        ]);

        $this->assertSame('សុខ ចាន់ដារ៉ា', $employee->full_name);

        $latinOnly = new Employee([
            'last_name' => 'Sok',
            'first_name' => 'Dara',
        ]);
        $this->assertSame('Sok Dara', $latinOnly->full_name);
    }

    public function test_employee_controller_stores_all_six_categories(): void
    {
        $dept = EmployeeDepartment::create(['name' => 'Information Technology']);
        $pos = EmployeePosition::create(['name' => 'Senior Developer', 'code' => 'DEV']);
        $status = EmployeeStatus::create(['name' => 'Active']);

        $requestData = [
            // Account creation
            'account_type' => 'new',
            'new_name' => 'Keo Vibol',
            'new_email' => 'vibol.keo@example.com',
            'new_password' => 'secret123',

            // 1. Personal Information
            'employee_code' => 'EMP-2026-001',
            'last_name_khmer' => 'កែវ',
            'first_name_khmer' => 'វិបុល',
            'last_name' => 'Keo',
            'first_name' => 'Vibol',
            'gender' => 'Male',
            'date_of_birth' => '1995-05-15',
            'blood_group' => 'O+',
            'marital_status' => 'Single',
            'national_id' => '0123456789',
            'nationality' => 'ខ្មែរ',
            'place_of_birth' => null,
            'birth_village' => 'វត្តលៀប',
            'birth_commune' => 'ចំការសំរោង',
            'birth_district' => 'បាត់ដំបង',
            'birth_province' => 'បាត់ដំបង',
            'phone' => '012345678',
            'contact_email' => 'vibol.contact@example.com',

            // 2. Address
            'current_address' => 'ផ្ទះលេខ ១២ ផ្លូវ ២០០',
            'permanent_address' => 'ភូមិវត្តលៀប ក្រុងបាត់ដំបង',
            'village' => 'វត្តលៀប',
            'commune' => 'ចំការសំរោង',
            'district' => 'បាត់ដំបង',
            'province' => 'បាត់ដំបង',

            // 3. Employment Information
            'employee_position_id' => $pos->id,
            'employee_department_id' => $dept->id,
            'employee_type' => 'Teacher',
            'date_of_joining' => '2026-01-10',
            'employee_status_id' => $status->id,
            'contract_type' => 'Full-time',
            'supervisor_name' => 'លោក ហេង ម៉េង',
            'work_location' => 'Main Campus - Building B',

            // 4. Payroll Information
            'basic_salary' => 850.00,
            'salary_type' => 'Monthly',
            'allowance' => 50.00,
            'deduction' => 10.00,
            'payment_method' => 'Bank Transfer',
            'bank_name' => 'ABA Bank',
            'bank_account_number' => '000123456',
            'bank_account_name' => 'KEO VIBOL',
            'salary_effective_date' => '2026-02-01',

            // 5. Education & Skills
            'highest_qualification' => 'Master of Computer Science',
            'university' => 'Royal University of Phnom Penh',
            'major' => 'Software Engineering',
            'graduation_year' => '2019',
            'teaching_qualification' => 'វិញ្ញាបនបត្រគរុកោសល្យកម្រិតឧត្តម',
            'professional_certificate' => 'AWS Certified Solutions Architect',
            'skills' => 'PHP, Laravel, Vue.js, MySQL, Cloud Architecture',
            'experience_years' => 5,

            // 6. Emergency Contact
            'emergency_contact_name' => 'កែវ សុភា',
            'emergency_relationship' => 'បងស្រី (Sister)',
            'emergency_phone' => '098765432',
            'emergency_address' => 'ភ្នំពេញ',
        ];

        $controller = new EmployeeController;
        $request = new Request($requestData);
        $request->setMethod('POST');

        $response = $controller->store($request);

        $this->assertTrue($response->isRedirection());

        $employee = Employee::where('employee_code', 'EMP-2026-001')->first();
        $this->assertNotNull($employee);
        $this->assertSame('កែវ', $employee->last_name_khmer);
        $this->assertSame('វិបុល', $employee->first_name_khmer);
        $this->assertSame('Teacher', $employee->employee_type);
        $this->assertSame('Master of Computer Science', $employee->highest_qualification);
        $this->assertSame('ABA Bank', $employee->bank_name);
        $this->assertEquals(850.00, $employee->basic_salary);
        $this->assertEquals(5, $employee->experience_years);
        $this->assertSame('វត្តលៀប', $employee->birth_village);
        $this->assertSame('ចំការសំរោង', $employee->birth_commune);
        $this->assertSame('បាត់ដំបង', $employee->birth_district);
        $this->assertSame('បាត់ដំបង', $employee->birth_province);
        $this->assertStringContainsString('ភូមិ វត្តលៀប', $employee->place_of_birth);

        $contact = $employee->employeeContact;
        $this->assertNotNull($contact);
        $this->assertSame('012345678', $contact->phone);
        $this->assertSame('បាត់ដំបង', $contact->province);
        $this->assertSame('កែវ សុភា', $contact->emergency_contact_name);
        $this->assertSame('098765432', $contact->emergency_phone);
        $this->assertSame('បងស្រី (Sister)', $contact->emergency_relationship);

        // User account check
        $this->assertNotNull($employee->user);
        $this->assertSame('vibol.keo@example.com', $employee->user->email);
    }

    public function test_employee_controller_updates_all_six_categories(): void
    {
        $dept = EmployeeDepartment::create(['name' => 'Administration']);
        $pos = EmployeePosition::create(['name' => 'Admin Officer', 'code' => 'ADM']);
        $status = EmployeeStatus::create(['name' => 'Active']);

        $user = User::create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => bcrypt('old'),
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP-EXISTING',
            'first_name' => 'OldFirst',
            'last_name' => 'OldLast',
            'employee_department_id' => $dept->id,
            'employee_position_id' => $pos->id,
            'employee_status_id' => $status->id,
            'date_of_joining' => '2024-01-01',
            'employee_type' => 'Receptionist',
            'basic_salary' => 400.00,
        ]);

        EmployeeContact::create([
            'employee_id' => $employee->id,
            'phone' => '011111111',
            'emergency_contact_name' => 'Old Contact',
        ]);

        $updateData = [
            // 1. Personal
            'employee_code' => 'EMP-EXISTING',
            'last_name_khmer' => 'ចាន់',
            'first_name_khmer' => 'មករា',
            'last_name' => 'Chan',
            'first_name' => 'Makara',
            'gender' => 'Female',
            'date_of_birth' => '1998-03-20',
            'blood_group' => 'A+',
            'marital_status' => 'Married',
            'national_id' => '0987654321',
            'nationality' => 'ខ្មែរ',
            'place_of_birth' => 'កណ្តាល',
            'phone' => '022222222',
            'contact_email' => 'makara.contact@example.com',
            'user_name' => 'ចាន់ មករា',
            'user_email' => 'makara.chan@example.com',

            // 2. Address
            'current_address' => 'ក្រុងតាខ្មៅ',
            'permanent_address' => 'ក្រុងតាខ្មៅ',
            'village' => 'ព្រែកឬស្សី',
            'commune' => 'ព្រែកឬស្សី',
            'district' => 'តាខ្មៅ',
            'province' => 'កណ្តាល',

            // 3. Employment
            'employee_department_id' => $dept->id,
            'employee_position_id' => $pos->id,
            'employee_status_id' => $status->id,
            'employee_type' => 'Administrative Staff',
            'date_of_joining' => '2025-06-01',
            'contract_type' => 'Permanent',
            'supervisor_name' => 'Mr. Boss',
            'work_location' => 'Main Office',

            // 4. Payroll
            'basic_salary' => 600.00,
            'salary_type' => 'Monthly',
            'allowance' => 100.00,
            'deduction' => 0.00,
            'payment_method' => 'Bank Transfer',
            'bank_name' => 'Canadia Bank',
            'bank_account_number' => '123456789',
            'bank_account_name' => 'CHAN MAKARA',
            'salary_effective_date' => '2026-01-01',

            // 5. Education & Skills
            'highest_qualification' => 'Bachelor of Business Administration',
            'university' => 'NUM',
            'major' => 'Management',
            'graduation_year' => '2020',
            'professional_certificate' => 'QuickBooks Certified',
            'teaching_qualification' => '',
            'skills' => 'Administration, Accounting, Coordination',
            'experience_years' => 3,

            // 6. Emergency Contact
            'emergency_contact_name' => 'ចាន់ សុខុម',
            'emergency_relationship' => 'ស្វាមី (Husband)',
            'emergency_phone' => '033333333',
            'emergency_address' => 'ក្រុងតាខ្មៅ',
        ];

        $controller = new EmployeeController;
        $request = new Request($updateData);
        $request->setMethod('PUT');

        $response = $controller->update($request, $employee);
        $this->assertTrue($response->isRedirection());

        $employee->refresh();
        $this->assertSame('ចាន់', $employee->last_name_khmer);
        $this->assertSame('មករា', $employee->first_name_khmer);
        $this->assertSame('Administrative Staff', $employee->employee_type);
        $this->assertEquals(600.00, $employee->basic_salary);
        $this->assertSame('Canadia Bank', $employee->bank_name);
        $this->assertSame('Bachelor of Business Administration', $employee->highest_qualification);

        $contact = $employee->employeeContact;
        $this->assertSame('022222222', $contact->phone);
        $this->assertSame('ចាន់ សុខុម', $contact->emergency_contact_name);
        $this->assertSame('033333333', $contact->emergency_phone);
        $this->assertSame('កណ្តាល', $contact->province);

        // User account updated
        $this->assertSame('makara.chan@example.com', $employee->user->email);
        $this->assertSame('ចាន់ មករា', $employee->user->name);
    }
}
