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
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreign('class_id', 'fk_assignments_class')
                ->references('id')->on('school_classes')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_assignments_section')
                ->references('id')->on('sections')->cascadeOnDelete();
            $table->foreign('subject_id', 'fk_assignments_subject')
                ->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('teacher_id', 'fk_assignments_teacher')
                ->references('id')->on('employees')->cascadeOnDelete();
        });

        Schema::table('assignment_attachments', function (Blueprint $table) {
            $table->foreign('assignment_id', 'fk_assignment_attachments_assignment')
                ->references('id')->on('assignments')->cascadeOnDelete();
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->foreign('assignment_id', 'fk_assignment_submissions_assignment')
                ->references('id')->on('assignments')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_assignment_submissions_student')
                ->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::table('assignment_grades', function (Blueprint $table) {
            $table->foreign('assignment_submission_id', 'fk_assignment_grades_submission')
                ->references('id')->on('assignment_submissions')->cascadeOnDelete();
            $table->foreign('graded_by', 'fk_assignment_grades_employee')
                ->references('id')->on('employees')->cascadeOnDelete();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->foreign('academic_year_id', 'fk_exams_academic_year')
                ->references('id')->on('academic_years')->cascadeOnDelete();
            $table->foreign('exam_type_id', 'fk_exams_exam_type')
                ->references('id')->on('exam_types')->restrictOnDelete();
            $table->foreign('school_term_id', 'fk_exams_school_term')
                ->references('id')->on('school_terms')->cascadeOnDelete();
        });

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->foreign('exam_id', 'fk_exam_schedules_exam')
                ->references('id')->on('exams')->cascadeOnDelete();
            $table->foreign('class_id', 'fk_exam_schedules_class')
                ->references('id')->on('school_classes')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_exam_schedules_section')
                ->references('id')->on('sections')->cascadeOnDelete();
        });

        Schema::table('exam_subjects', function (Blueprint $table) {
            $table->foreign('exam_schedule_id', 'fk_exam_subjects_schedule')
                ->references('id')->on('exam_schedules')->cascadeOnDelete();
            $table->foreign('subject_id', 'fk_exam_subjects_subject')
                ->references('id')->on('subjects')->cascadeOnDelete();
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->foreign('exam_subject_id', 'fk_exam_results_subject')
                ->references('id')->on('exam_subjects')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_exam_results_student')
                ->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::table('student_grades', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_grades_student')
                ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('exam_id', 'fk_student_grades_exam')
                ->references('id')->on('exams')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropForeign('fk_student_grades_student');
            $table->dropForeign('fk_student_grades_exam');
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign('fk_exam_results_subject');
            $table->dropForeign('fk_exam_results_student');
        });

        Schema::table('exam_subjects', function (Blueprint $table) {
            $table->dropForeign('fk_exam_subjects_schedule');
            $table->dropForeign('fk_exam_subjects_subject');
        });

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropForeign('fk_exam_schedules_exam');
            $table->dropForeign('fk_exam_schedules_class');
            $table->dropForeign('fk_exam_schedules_section');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign('fk_exams_academic_year');
            $table->dropForeign('fk_exams_exam_type');
            $table->dropForeign('fk_exams_school_term');
        });

        Schema::table('assignment_grades', function (Blueprint $table) {
            $table->dropForeign('fk_assignment_grades_submission');
            $table->dropForeign('fk_assignment_grades_employee');
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropForeign('fk_assignment_submissions_assignment');
            $table->dropForeign('fk_assignment_submissions_student');
        });

        Schema::table('assignment_attachments', function (Blueprint $table) {
            $table->dropForeign('fk_assignment_attachments_assignment');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign('fk_assignments_class');
            $table->dropForeign('fk_assignments_section');
            $table->dropForeign('fk_assignments_subject');
            $table->dropForeign('fk_assignments_teacher');
        });
    }
};
