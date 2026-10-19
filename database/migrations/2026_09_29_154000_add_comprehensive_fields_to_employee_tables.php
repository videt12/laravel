<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add fields to employees table
        Schema::table('employees', function (Blueprint $table) {
            // Personal Information
            if (! Schema::hasColumn('employees', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('employees', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('employees', 'first_name_khmer')) {
                $table->string('first_name_khmer', 100)->nullable()->after('last_name');
            }
            if (! Schema::hasColumn('employees', 'last_name_khmer')) {
                $table->string('last_name_khmer', 100)->nullable()->after('first_name_khmer');
            }
            if (! Schema::hasColumn('employees', 'nationality')) {
                $table->string('nationality', 100)->default('Cambodian')->after('blood_group');
            }
            if (! Schema::hasColumn('employees', 'marital_status')) {
                $table->string('marital_status', 30)->nullable()->after('nationality');
            }
            if (! Schema::hasColumn('employees', 'national_id')) {
                $table->string('national_id', 50)->nullable()->after('marital_status');
            }
            if (! Schema::hasColumn('employees', 'place_of_birth')) {
                $table->string('place_of_birth', 255)->nullable()->after('national_id');
            }

            // Employment Information
            if (! Schema::hasColumn('employees', 'employee_type')) {
                $table->string('employee_type', 50)->nullable()->after('employee_position_id');
            }
            if (! Schema::hasColumn('employees', 'contract_type')) {
                $table->string('contract_type', 50)->nullable()->after('employee_type');
            }
            if (! Schema::hasColumn('employees', 'supervisor_name')) {
                $table->string('supervisor_name', 150)->nullable()->after('contract_type');
            }
            if (! Schema::hasColumn('employees', 'work_location')) {
                $table->string('work_location', 150)->nullable()->after('supervisor_name');
            }

            // Payroll Information
            if (! Schema::hasColumn('employees', 'salary_type')) {
                $table->string('salary_type', 30)->default('Monthly')->after('basic_salary');
            }
            if (! Schema::hasColumn('employees', 'allowance')) {
                $table->decimal('allowance', 12, 2)->default(0.00)->after('salary_type');
            }
            if (! Schema::hasColumn('employees', 'deduction')) {
                $table->decimal('deduction', 12, 2)->default(0.00)->after('allowance');
            }
            if (! Schema::hasColumn('employees', 'payment_method')) {
                $table->string('payment_method', 50)->default('Cash')->after('deduction');
            }
            if (! Schema::hasColumn('employees', 'bank_name')) {
                $table->string('bank_name', 100)->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('employees', 'bank_account_number')) {
                $table->string('bank_account_number', 50)->nullable()->after('bank_name');
            }
            if (! Schema::hasColumn('employees', 'bank_account_name')) {
                $table->string('bank_account_name', 150)->nullable()->after('bank_account_number');
            }
            if (! Schema::hasColumn('employees', 'salary_effective_date')) {
                $table->date('salary_effective_date')->nullable()->after('bank_account_name');
            }

            // Education & Skills
            if (! Schema::hasColumn('employees', 'highest_qualification')) {
                $table->string('highest_qualification', 100)->nullable()->after('salary_effective_date');
            }
            if (! Schema::hasColumn('employees', 'university')) {
                $table->string('university', 255)->nullable()->after('highest_qualification');
            }
            if (! Schema::hasColumn('employees', 'major')) {
                $table->string('major', 150)->nullable()->after('university');
            }
            if (! Schema::hasColumn('employees', 'graduation_year')) {
                $table->string('graduation_year', 10)->nullable()->after('major');
            }
            if (! Schema::hasColumn('employees', 'professional_certificate')) {
                $table->text('professional_certificate')->nullable()->after('graduation_year');
            }
            if (! Schema::hasColumn('employees', 'teaching_qualification')) {
                $table->string('teaching_qualification', 150)->nullable()->after('professional_certificate');
            }
            if (! Schema::hasColumn('employees', 'skills')) {
                $table->text('skills')->nullable()->after('teaching_qualification');
            }
            if (! Schema::hasColumn('employees', 'experience_years')) {
                $table->string('experience_years', 50)->nullable()->after('skills');
            }
        });

        // 2. Add fields to employee_contacts table
        Schema::table('employee_contacts', function (Blueprint $table) {
            // Address Details
            if (! Schema::hasColumn('employee_contacts', 'village')) {
                $table->string('village', 150)->nullable()->after('permanent_address');
            }
            if (! Schema::hasColumn('employee_contacts', 'commune')) {
                $table->string('commune', 150)->nullable()->after('village');
            }
            if (! Schema::hasColumn('employee_contacts', 'district')) {
                $table->string('district', 150)->nullable()->after('commune');
            }
            if (! Schema::hasColumn('employee_contacts', 'province')) {
                $table->string('province', 150)->nullable()->after('district');
            }

            // Emergency Contact Details
            if (! Schema::hasColumn('employee_contacts', 'emergency_contact_name')) {
                $table->string('emergency_contact_name', 150)->nullable()->after('province');
            }
            if (! Schema::hasColumn('employee_contacts', 'emergency_relationship')) {
                $table->string('emergency_relationship', 50)->nullable()->after('emergency_contact_name');
            }
            if (! Schema::hasColumn('employee_contacts', 'emergency_address')) {
                $table->text('emergency_address')->nullable()->after('emergency_relationship');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'first_name_khmer', 'last_name_khmer',
                'nationality', 'marital_status', 'national_id', 'place_of_birth',
                'employee_type', 'contract_type', 'supervisor_name', 'work_location',
                'salary_type', 'allowance', 'deduction', 'payment_method',
                'bank_name', 'bank_account_number', 'bank_account_name', 'salary_effective_date',
                'highest_qualification', 'university', 'major', 'graduation_year',
                'professional_certificate', 'teaching_qualification', 'skills', 'experience_years',
            ]);
        });

        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->dropColumn([
                'village', 'commune', 'district', 'province',
                'emergency_contact_name', 'emergency_relationship', 'emergency_address',
            ]);
        });
    }
};
