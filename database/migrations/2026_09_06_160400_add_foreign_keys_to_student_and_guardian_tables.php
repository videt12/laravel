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
        Schema::table('students', function (Blueprint $table) {
            $table->foreign('school_id', 'fk_students_school')
                ->references('id')->on('schools')->restrictOnDelete();
            $table->foreign('user_id', 'fk_students_user')
                ->references('id')->on('users')->nullOnDelete();
            $table->foreign('student_status_id', 'fk_students_status')
                ->references('id')->on('student_statuses')->restrictOnDelete();
            $table->foreign('student_category_id', 'fk_students_category')
                ->references('id')->on('student_categories')->nullOnDelete();
            $table->foreign('academic_year_id', 'fk_students_academic_year')
                ->references('id')->on('academic_years')->nullOnDelete();
            $table->foreign('school_class_id', 'fk_students_class')
                ->references('id')->on('school_classes')->nullOnDelete();
            $table->foreign('section_id', 'fk_students_section')
                ->references('id')->on('sections')->nullOnDelete();
            $table->foreign('school_shift_id', 'fk_students_shift')
                ->references('id')->on('school_shifts')->nullOnDelete();
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_profiles_student')
                ->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::table('student_addresses', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_addresses_student')
                ->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::table('student_medical_records', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_medical_records_student')
                ->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::table('student_documents', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_documents_student')
                ->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_guardians_user')
                ->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('guardian_contacts', function (Blueprint $table) {
            $table->foreign('guardian_id', 'fk_guardian_contacts_guardian')
                ->references('id')->on('guardians')->cascadeOnDelete();
        });

        Schema::table('student_guardians', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_guardians_student')
                ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('guardian_id', 'fk_student_guardians_guardian')
                ->references('id')->on('guardians')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_guardians', function (Blueprint $table) {
            $table->dropForeign('fk_student_guardians_student');
            $table->dropForeign('fk_student_guardians_guardian');
        });

        Schema::table('guardian_contacts', function (Blueprint $table) {
            $table->dropForeign('fk_guardian_contacts_guardian');
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->dropForeign('fk_guardians_user');
        });

        Schema::table('student_documents', function (Blueprint $table) {
            $table->dropForeign('fk_student_documents_student');
        });

        Schema::table('student_medical_records', function (Blueprint $table) {
            $table->dropForeign('fk_student_medical_records_student');
        });

        Schema::table('student_addresses', function (Blueprint $table) {
            $table->dropForeign('fk_student_addresses_student');
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropForeign('fk_student_profiles_student');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign('fk_students_school');
            $table->dropForeign('fk_students_user');
            $table->dropForeign('fk_students_status');
            $table->dropForeign('fk_students_category');
            $table->dropForeign('fk_students_academic_year');
            $table->dropForeign('fk_students_class');
            $table->dropForeign('fk_students_section');
            $table->dropForeign('fk_students_shift');
        });
    }
};
