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
        // 1. Ensure required columns exist on all assignment tables before creating foreign keys
        Schema::table('assignments', function (Blueprint $table) {
            if (! Schema::hasColumn('assignments', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable();
            }
            if (! Schema::hasColumn('assignments', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable();
            }
            if (! Schema::hasColumn('assignments', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable();
            }
            if (! Schema::hasColumn('assignments', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->nullable();
            }
        });

        Schema::table('assignment_attachments', function (Blueprint $table) {
            if (! Schema::hasColumn('assignment_attachments', 'assignment_id')) {
                $table->unsignedBigInteger('assignment_id')->nullable();
            }
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (! Schema::hasColumn('assignment_submissions', 'assignment_id')) {
                $table->unsignedBigInteger('assignment_id')->nullable();
            }
            if (! Schema::hasColumn('assignment_submissions', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable();
            }
        });

        Schema::table('assignment_grades', function (Blueprint $table) {
            if (! Schema::hasColumn('assignment_grades', 'assignment_submission_id')) {
                $table->unsignedBigInteger('assignment_submission_id')->nullable();
            }
            if (! Schema::hasColumn('assignment_grades', 'graded_by')) {
                $table->unsignedBigInteger('graded_by')->nullable();
            }
        });

        // 2. Ensure required columns exist on all examination tables before creating foreign keys
        Schema::table('exams', function (Blueprint $table) {
            if (! Schema::hasColumn('exams', 'exam_type_id')) {
                $table->unsignedBigInteger('exam_type_id')->nullable();
            }
            if (! Schema::hasColumn('exams', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->nullable();
            }
            if (! Schema::hasColumn('exams', 'school_term_id')) {
                $table->unsignedBigInteger('school_term_id')->nullable();
            }
            if (! Schema::hasColumn('exams', 'name')) {
                $table->string('name')->nullable();
            }
            if (! Schema::hasColumn('exams', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (! Schema::hasColumn('exams', 'end_date')) {
                $table->date('end_date')->nullable();
            }
        });

        Schema::table('exam_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_schedules', 'exam_id')) {
                $table->unsignedBigInteger('exam_id')->nullable();
            }
            if (! Schema::hasColumn('exam_schedules', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable();
            }
            if (! Schema::hasColumn('exam_schedules', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable();
            }
        });

        Schema::table('exam_subjects', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_subjects', 'exam_schedule_id')) {
                $table->unsignedBigInteger('exam_schedule_id')->nullable();
            }
            if (! Schema::hasColumn('exam_subjects', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable();
            }
        });

        Schema::table('exam_results', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_results', 'exam_subject_id')) {
                $table->unsignedBigInteger('exam_subject_id')->nullable();
            }
            if (! Schema::hasColumn('exam_results', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable();
            }
        });

        Schema::table('student_grades', function (Blueprint $table) {
            if (! Schema::hasColumn('student_grades', 'exam_id')) {
                $table->unsignedBigInteger('exam_id')->nullable();
            }
            if (! Schema::hasColumn('student_grades', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable();
            }
        });

        // 3. Add foreign keys safely
        try {
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
        } catch (Throwable $e) {
        }

        try {
            Schema::table('assignment_attachments', function (Blueprint $table) {
                $table->foreign('assignment_id', 'fk_assignment_attachments_assignment')
                    ->references('id')->on('assignments')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('assignment_submissions', function (Blueprint $table) {
                $table->foreign('assignment_id', 'fk_assignment_submissions_assignment')
                    ->references('id')->on('assignments')->cascadeOnDelete();
                $table->foreign('student_id', 'fk_assignment_submissions_student')
                    ->references('id')->on('students')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('assignment_grades', function (Blueprint $table) {
                $table->foreign('assignment_submission_id', 'fk_assignment_grades_submission')
                    ->references('id')->on('assignment_submissions')->cascadeOnDelete();
                $table->foreign('graded_by', 'fk_assignment_grades_employee')
                    ->references('id')->on('employees')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exams', function (Blueprint $table) {
                $table->foreign('academic_year_id', 'fk_exams_academic_year')
                    ->references('id')->on('academic_years')->cascadeOnDelete();
                $table->foreign('exam_type_id', 'fk_exams_exam_type')
                    ->references('id')->on('exam_types')->restrictOnDelete();
                $table->foreign('school_term_id', 'fk_exams_school_term')
                    ->references('id')->on('school_terms')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exam_schedules', function (Blueprint $table) {
                $table->foreign('exam_id', 'fk_exam_schedules_exam')
                    ->references('id')->on('exams')->cascadeOnDelete();
                $table->foreign('class_id', 'fk_exam_schedules_class')
                    ->references('id')->on('school_classes')->cascadeOnDelete();
                $table->foreign('section_id', 'fk_exam_schedules_section')
                    ->references('id')->on('sections')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exam_subjects', function (Blueprint $table) {
                $table->foreign('exam_schedule_id', 'fk_exam_subjects_schedule')
                    ->references('id')->on('exam_schedules')->cascadeOnDelete();
                $table->foreign('subject_id', 'fk_exam_subjects_subject')
                    ->references('id')->on('subjects')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->foreign('exam_subject_id', 'fk_exam_results_subject')
                    ->references('id')->on('exam_subjects')->cascadeOnDelete();
                $table->foreign('student_id', 'fk_exam_results_student')
                    ->references('id')->on('students')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('student_grades', function (Blueprint $table) {
                $table->foreign('student_id', 'fk_student_grades_student')
                    ->references('id')->on('students')->cascadeOnDelete();
                $table->foreign('exam_id', 'fk_student_grades_exam')
                    ->references('id')->on('exams')->cascadeOnDelete();
            });
        } catch (Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('student_grades', function (Blueprint $table) {
                $table->dropForeign('fk_student_grades_student');
                $table->dropForeign('fk_student_grades_exam');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exam_results', function (Blueprint $table) {
                $table->dropForeign('fk_exam_results_subject');
                $table->dropForeign('fk_exam_results_student');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exam_subjects', function (Blueprint $table) {
                $table->dropForeign('fk_exam_subjects_schedule');
                $table->dropForeign('fk_exam_subjects_subject');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exam_schedules', function (Blueprint $table) {
                $table->dropForeign('fk_exam_schedules_exam');
                $table->dropForeign('fk_exam_schedules_class');
                $table->dropForeign('fk_exam_schedules_section');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropForeign('fk_exams_academic_year');
                $table->dropForeign('fk_exams_exam_type');
                $table->dropForeign('fk_exams_school_term');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('assignment_grades', function (Blueprint $table) {
                $table->dropForeign('fk_assignment_grades_submission');
                $table->dropForeign('fk_assignment_grades_employee');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('assignment_submissions', function (Blueprint $table) {
                $table->dropForeign('fk_assignment_submissions_assignment');
                $table->dropForeign('fk_assignment_submissions_student');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('assignment_attachments', function (Blueprint $table) {
                $table->dropForeign('fk_assignment_attachments_assignment');
            });
        } catch (Throwable $e) {
        }

        try {
            Schema::table('assignments', function (Blueprint $table) {
                $table->dropForeign('fk_assignments_class');
                $table->dropForeign('fk_assignments_section');
                $table->dropForeign('fk_assignments_subject');
                $table->dropForeign('fk_assignments_teacher');
            });
        } catch (Throwable $e) {
        }
    }
};
