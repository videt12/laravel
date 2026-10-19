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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_enrollments_student')
                ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('academic_year_id', 'fk_enrollments_academic_year')
                ->references('id')->on('academic_years')->cascadeOnDelete();
        });

        Schema::table('enrollment_details', function (Blueprint $table) {
            $table->foreign('enrollment_id', 'fk_enrollment_details_enrollment')
                ->references('id')->on('enrollments')->cascadeOnDelete();
            $table->foreign('class_id', 'fk_enrollment_details_class')
                ->references('id')->on('school_classes')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_enrollment_details_section')
                ->references('id')->on('sections')->cascadeOnDelete();
        });

        Schema::table('student_promotions', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_promotions_student')
                ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('from_class_id', 'fk_student_promotions_from_class')
                ->references('id')->on('school_classes')->restrictOnDelete();
            $table->foreign('to_class_id', 'fk_student_promotions_to_class')
                ->references('id')->on('school_classes')->restrictOnDelete();
            $table->foreign('from_academic_year_id', 'fk_student_promotions_from_year')
                ->references('id')->on('academic_years')->restrictOnDelete();
            $table->foreign('to_academic_year_id', 'fk_student_promotions_to_year')
                ->references('id')->on('academic_years')->restrictOnDelete();
        });

        Schema::table('student_transfers', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_transfers_student')
                ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('from_school_id', 'fk_student_transfers_from_school')
                ->references('id')->on('schools')->restrictOnDelete();
            $table->foreign('to_school_id', 'fk_student_transfers_to_school')
                ->references('id')->on('schools')->restrictOnDelete();
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_withdrawals_student')
                ->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->foreign('class_id', 'fk_attendance_sessions_class')
                ->references('id')->on('school_classes')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_attendance_sessions_section')
                ->references('id')->on('sections')->cascadeOnDelete();
            $table->foreign('academic_year_id', 'fk_attendance_sessions_academic_year')
                ->references('id')->on('academic_years')->cascadeOnDelete();
            $table->foreign('recorded_by', 'fk_attendance_sessions_recorded_by')
                ->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('student_attendances', function (Blueprint $table) {
            $table->foreign('attendance_session_id', 'fk_student_attendances_session')
                ->references('id')->on('attendance_sessions')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_student_attendances_student')
                ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('attendance_status_id', 'fk_student_attendances_status')
                ->references('id')->on('attendance_statuses')->restrictOnDelete();
        });

        Schema::table('attendance_corrections', function (Blueprint $table) {
            $table->foreign('student_attendance_id', 'fk_attendance_corrections_attendance')
                ->references('id')->on('student_attendances')->cascadeOnDelete();
            $table->foreign('requested_by', 'fk_attendance_corrections_requested_by')
                ->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('approved_by', 'fk_attendance_corrections_approved_by')
                ->references('id')->on('users')->nullOnDelete();
            $table->foreign('old_status_id', 'fk_attendance_corrections_old_status')
                ->references('id')->on('attendance_statuses')->restrictOnDelete();
            $table->foreign('new_status_id', 'fk_attendance_corrections_new_status')
                ->references('id')->on('attendance_statuses')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_corrections', function (Blueprint $table) {
            $table->dropForeign('fk_attendance_corrections_attendance');
            $table->dropForeign('fk_attendance_corrections_requested_by');
            $table->dropForeign('fk_attendance_corrections_approved_by');
            $table->dropForeign('fk_attendance_corrections_old_status');
            $table->dropForeign('fk_attendance_corrections_new_status');
        });

        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropForeign('fk_student_attendances_session');
            $table->dropForeign('fk_student_attendances_student');
            $table->dropForeign('fk_student_attendances_status');
        });

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign('fk_attendance_sessions_class');
            $table->dropForeign('fk_attendance_sessions_section');
            $table->dropForeign('fk_attendance_sessions_academic_year');
            $table->dropForeign('fk_attendance_sessions_recorded_by');
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropForeign('fk_withdrawals_student');
        });

        Schema::table('student_transfers', function (Blueprint $table) {
            $table->dropForeign('fk_student_transfers_student');
            $table->dropForeign('fk_student_transfers_from_school');
            $table->dropForeign('fk_student_transfers_to_school');
        });

        Schema::table('student_promotions', function (Blueprint $table) {
            $table->dropForeign('fk_student_promotions_student');
            $table->dropForeign('fk_student_promotions_from_class');
            $table->dropForeign('fk_student_promotions_to_class');
            $table->dropForeign('fk_student_promotions_from_year');
            $table->dropForeign('fk_student_promotions_to_year');
        });

        Schema::table('enrollment_details', function (Blueprint $table) {
            $table->dropForeign('fk_enrollment_details_enrollment');
            $table->dropForeign('fk_enrollment_details_class');
            $table->dropForeign('fk_enrollment_details_section');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign('fk_enrollments_student');
            $table->dropForeign('fk_enrollments_academic_year');
        });
    }
};
