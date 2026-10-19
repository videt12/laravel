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
        // 1. student_profiles
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('student_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('first_name_khmer')->nullable()->after('last_name');
            $table->string('last_name_khmer')->nullable()->after('first_name_khmer');
            $table->string('nationality')->nullable()->after('blood_group');
            $table->string('national_id')->nullable()->after('nationality');
            $table->string('previous_school')->nullable()->after('national_id');
            $table->string('special_needs')->nullable()->after('previous_school');
            $table->text('medical_information')->nullable()->after('special_needs');
            $table->text('notes')->nullable()->after('medical_information');
        });

        // 2. student_addresses
        Schema::table('student_addresses', function (Blueprint $table) {
            $table->string('address_line_1')->nullable()->after('student_id');
            $table->string('address_line_2')->nullable()->after('address_line_1');
            $table->string('village')->nullable()->after('address_line_2');
            $table->string('commune')->nullable()->after('village');
            $table->string('district')->nullable()->after('commune');
            $table->string('province')->nullable()->after('district');
            $table->string('postal_code')->nullable()->after('province');
        });

        // 3. students
        Schema::table('students', function (Blueprint $table) {
            $table->date('enrollment_date')->nullable()->after('admission_date');
            $table->string('roll_number')->nullable()->after('enrollment_date');

            $table->unsignedBigInteger('student_category_id')->nullable()->after('student_status_id');
            $table->unsignedBigInteger('academic_year_id')->nullable()->after('student_category_id');
            $table->unsignedBigInteger('school_class_id')->nullable()->after('academic_year_id');
            $table->unsignedBigInteger('section_id')->nullable()->after('school_class_id');
            $table->unsignedBigInteger('school_shift_id')->nullable()->after('section_id');
        });

        // 4. student_guardians
        Schema::table('student_guardians', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('relationship');
            $table->boolean('is_emergency')->default(false)->after('is_primary');
        });

        // 5. guardians
        Schema::table('guardians', function (Blueprint $table) {
            $table->text('address')->nullable()->after('income');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'first_name_khmer', 'last_name_khmer',
                'nationality', 'national_id', 'previous_school', 'special_needs',
                'medical_information', 'notes',
            ]);
        });

        Schema::table('student_addresses', function (Blueprint $table) {
            $table->dropColumn([
                'address_line_1', 'address_line_2', 'village', 'commune',
                'district', 'province', 'postal_code',
            ]);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'enrollment_date', 'roll_number', 'student_category_id',
                'academic_year_id', 'school_class_id', 'section_id', 'school_shift_id',
            ]);
        });

        Schema::table('student_guardians', function (Blueprint $table) {
            $table->dropColumn(['is_primary', 'is_emergency']);
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
