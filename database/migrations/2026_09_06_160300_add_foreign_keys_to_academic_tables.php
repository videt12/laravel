<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $hasConstraint = function (string $table, string $constraint): bool {
            if (DB::getDriverName() !== 'mysql') {
                return false;
            }
            return count(DB::select('
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = ? 
                  AND CONSTRAINT_NAME = ?
            ', [$table, $constraint])) > 0;
        };

        Schema::table('school_classes', function (Blueprint $table) use ($hasConstraint) {
            if (! $hasConstraint('school_classes', 'fk_classes_grade')) {
                $table->foreign('grade_id', 'fk_classes_grade')
                    ->references('id')->on('grades')->cascadeOnDelete();
            }
            if (! $hasConstraint('school_classes', 'fk_classes_section')) {
                $table->foreign('section_id', 'fk_classes_section')
                    ->references('id')->on('sections')->cascadeOnDelete();
            }
            if (! $hasConstraint('school_classes', 'fk_classes_academic_year')) {
                $table->foreign('academic_year_id', 'fk_classes_academic_year')
                    ->references('id')->on('academic_years')->cascadeOnDelete();
            }
        });

        Schema::table('class_subjects', function (Blueprint $table) use ($hasConstraint) {
            if (! $hasConstraint('class_subjects', 'fk_class_subjects_class')) {
                $table->foreign('class_id', 'fk_class_subjects_class')
                    ->references('id')->on('school_classes')->cascadeOnDelete();
            }
            if (! $hasConstraint('class_subjects', 'fk_class_subjects_subject')) {
                $table->foreign('subject_id', 'fk_class_subjects_subject')
                    ->references('id')->on('subjects')->cascadeOnDelete();
            }
        });

        Schema::table('teacher_assignments', function (Blueprint $table) use ($hasConstraint) {
            if (! $hasConstraint('teacher_assignments', 'fk_teacher_assignments_employee')) {
                $table->foreign('employee_id', 'fk_teacher_assignments_employee')
                    ->references('id')->on('employees')->cascadeOnDelete();
            }
            if (! $hasConstraint('teacher_assignments', 'fk_teacher_assignments_class_subject')) {
                $table->foreign('class_subject_id', 'fk_teacher_assignments_class_subject')
                    ->references('id')->on('class_subjects')->cascadeOnDelete();
            }
            if (! $hasConstraint('teacher_assignments', 'fk_teacher_assignments_academic_year')) {
                $table->foreign('academic_year_id', 'fk_teacher_assignments_academic_year')
                    ->references('id')->on('academic_years')->cascadeOnDelete();
            }
        });

        Schema::table('timetables', function (Blueprint $table) use ($hasConstraint) {
            if (! $hasConstraint('timetables', 'fk_timetables_class')) {
                $table->foreign('class_id', 'fk_timetables_class')
                    ->references('id')->on('school_classes')->cascadeOnDelete();
            }
            if (! $hasConstraint('timetables', 'fk_timetables_academic_year')) {
                $table->foreign('academic_year_id', 'fk_timetables_academic_year')
                    ->references('id')->on('academic_years')->cascadeOnDelete();
            }
            if (! $hasConstraint('timetables', 'fk_timetables_school_term')) {
                $table->foreign('school_term_id', 'fk_timetables_school_term')
                    ->references('id')->on('school_terms')->cascadeOnDelete();
            }
        });

        Schema::table('timetable_entries', function (Blueprint $table) use ($hasConstraint) {
            if (! $hasConstraint('timetable_entries', 'fk_timetable_entries_timetable')) {
                $table->foreign('timetable_id', 'fk_timetable_entries_timetable')
                    ->references('id')->on('timetables')->cascadeOnDelete();
            }
            if (! $hasConstraint('timetable_entries', 'fk_timetable_entries_subject')) {
                $table->foreign('subject_id', 'fk_timetable_entries_subject')
                    ->references('id')->on('subjects')->cascadeOnDelete();
            }
            if (! $hasConstraint('timetable_entries', 'fk_timetable_entries_teacher')) {
                $table->foreign('teacher_id', 'fk_timetable_entries_teacher')
                    ->references('id')->on('employees')->cascadeOnDelete();
            }
            if (! $hasConstraint('timetable_entries', 'fk_timetable_entries_classroom')) {
                $table->foreign('classroom_id', 'fk_timetable_entries_classroom')
                    ->references('id')->on('classrooms')->nullOnDelete();
            }
            if (! $hasConstraint('timetable_entries', 'fk_timetable_entries_time_slot')) {
                $table->foreign('time_slot_id', 'fk_timetable_entries_time_slot')
                    ->references('id')->on('time_slots')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasConstraint = function (string $table, string $constraint): bool {
            if (DB::getDriverName() !== 'mysql') {
                return false;
            }
            return count(DB::select('
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = ? 
                  AND CONSTRAINT_NAME = ?
            ', [$table, $constraint])) > 0;
        };

        Schema::table('timetable_entries', function (Blueprint $table) use ($hasConstraint) {
            if ($hasConstraint('timetable_entries', 'fk_timetable_entries_timetable')) {
                $table->dropForeign('fk_timetable_entries_timetable');
            }
            if ($hasConstraint('timetable_entries', 'fk_timetable_entries_subject')) {
                $table->dropForeign('fk_timetable_entries_subject');
            }
            if ($hasConstraint('timetable_entries', 'fk_timetable_entries_teacher')) {
                $table->dropForeign('fk_timetable_entries_teacher');
            }
            if ($hasConstraint('timetable_entries', 'fk_timetable_entries_classroom')) {
                $table->dropForeign('fk_timetable_entries_classroom');
            }
            if ($hasConstraint('timetable_entries', 'fk_timetable_entries_time_slot')) {
                $table->dropForeign('fk_timetable_entries_time_slot');
            }
        });

        Schema::table('timetables', function (Blueprint $table) use ($hasConstraint) {
            if ($hasConstraint('timetables', 'fk_timetables_class')) {
                $table->dropForeign('fk_timetables_class');
            }
            if ($hasConstraint('timetables', 'fk_timetables_academic_year')) {
                $table->dropForeign('fk_timetables_academic_year');
            }
            if ($hasConstraint('timetables', 'fk_timetables_school_term')) {
                $table->dropForeign('fk_timetables_school_term');
            }
        });

        Schema::table('teacher_assignments', function (Blueprint $table) use ($hasConstraint) {
            if ($hasConstraint('teacher_assignments', 'fk_teacher_assignments_employee')) {
                $table->dropForeign('fk_teacher_assignments_employee');
            }
            if ($hasConstraint('teacher_assignments', 'fk_teacher_assignments_class_subject')) {
                $table->dropForeign('fk_teacher_assignments_class_subject');
            }
            if ($hasConstraint('teacher_assignments', 'fk_teacher_assignments_academic_year')) {
                $table->dropForeign('fk_teacher_assignments_academic_year');
            }
        });

        Schema::table('class_subjects', function (Blueprint $table) use ($hasConstraint) {
            if ($hasConstraint('class_subjects', 'fk_class_subjects_class')) {
                $table->dropForeign('fk_class_subjects_class');
            }
            if ($hasConstraint('class_subjects', 'fk_class_subjects_subject')) {
                $table->dropForeign('fk_class_subjects_subject');
            }
        });

        Schema::table('school_classes', function (Blueprint $table) use ($hasConstraint) {
            if ($hasConstraint('school_classes', 'fk_classes_grade')) {
                $table->dropForeign('fk_classes_grade');
            }
            if ($hasConstraint('school_classes', 'fk_classes_section')) {
                $table->dropForeign('fk_classes_section');
            }
            if ($hasConstraint('school_classes', 'fk_classes_academic_year')) {
                $table->dropForeign('fk_classes_academic_year');
            }
        });
    }
};
