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
        // 1. Ensure exam_types columns
        if (Schema::hasTable('exam_types')) {
            Schema::table('exam_types', function (Blueprint $table) {
                if (! Schema::hasColumn('exam_types', 'name')) {
                    $table->string('name')->nullable();
                }
                if (! Schema::hasColumn('exam_types', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }

        // 2. Ensure grade_scales columns
        if (Schema::hasTable('grade_scales')) {
            Schema::table('grade_scales', function (Blueprint $table) {
                if (! Schema::hasColumn('grade_scales', 'name')) {
                    $table->string('name')->nullable();
                }
                if (! Schema::hasColumn('grade_scales', 'min_percentage')) {
                    $table->decimal('min_percentage', 5, 2)->default(0);
                }
                if (! Schema::hasColumn('grade_scales', 'max_percentage')) {
                    $table->decimal('max_percentage', 5, 2)->default(100);
                }
                if (! Schema::hasColumn('grade_scales', 'grade')) {
                    $table->string('grade', 10)->nullable();
                }
                if (! Schema::hasColumn('grade_scales', 'gpa_point')) {
                    $table->decimal('gpa_point', 4, 2)->nullable();
                }
            });
        }

        // 3. Ensure exams columns
        if (Schema::hasTable('exams')) {
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
        }

        // 4. Ensure exam_schedules columns
        if (Schema::hasTable('exam_schedules')) {
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
        }

        // 5. Ensure exam_subjects columns
        if (Schema::hasTable('exam_subjects')) {
            Schema::table('exam_subjects', function (Blueprint $table) {
                if (! Schema::hasColumn('exam_subjects', 'exam_schedule_id')) {
                    $table->unsignedBigInteger('exam_schedule_id')->nullable();
                }
                if (! Schema::hasColumn('exam_subjects', 'subject_id')) {
                    $table->unsignedBigInteger('subject_id')->nullable();
                }
                if (! Schema::hasColumn('exam_subjects', 'exam_date')) {
                    $table->date('exam_date')->nullable();
                }
                if (! Schema::hasColumn('exam_subjects', 'start_time')) {
                    $table->time('start_time')->nullable();
                }
                if (! Schema::hasColumn('exam_subjects', 'end_time')) {
                    $table->time('end_time')->nullable();
                }
                if (! Schema::hasColumn('exam_subjects', 'max_marks')) {
                    $table->decimal('max_marks', 8, 2)->nullable();
                }
                if (! Schema::hasColumn('exam_subjects', 'pass_marks')) {
                    $table->decimal('pass_marks', 8, 2)->nullable();
                }
            });
        }

        // 6. Ensure exam_results columns
        if (Schema::hasTable('exam_results')) {
            Schema::table('exam_results', function (Blueprint $table) {
                if (! Schema::hasColumn('exam_results', 'exam_subject_id')) {
                    $table->unsignedBigInteger('exam_subject_id')->nullable();
                }
                if (! Schema::hasColumn('exam_results', 'student_id')) {
                    $table->unsignedBigInteger('student_id')->nullable();
                }
                if (! Schema::hasColumn('exam_results', 'marks_obtained')) {
                    $table->decimal('marks_obtained', 8, 2)->nullable();
                }
                if (! Schema::hasColumn('exam_results', 'grade')) {
                    $table->string('grade', 10)->nullable();
                }
                if (! Schema::hasColumn('exam_results', 'remarks')) {
                    $table->text('remarks')->nullable();
                }
            });
        }

        // 7. Ensure student_grades columns
        if (Schema::hasTable('student_grades')) {
            Schema::table('student_grades', function (Blueprint $table) {
                if (! Schema::hasColumn('student_grades', 'exam_id')) {
                    $table->unsignedBigInteger('exam_id')->nullable();
                }
                if (! Schema::hasColumn('student_grades', 'student_id')) {
                    $table->unsignedBigInteger('student_id')->nullable();
                }
                if (! Schema::hasColumn('student_grades', 'total_marks')) {
                    $table->decimal('total_marks', 10, 2)->nullable();
                }
                if (! Schema::hasColumn('student_grades', 'percentage')) {
                    $table->decimal('percentage', 5, 2)->nullable();
                }
                if (! Schema::hasColumn('student_grades', 'grade')) {
                    $table->string('grade', 10)->nullable();
                }
                if (! Schema::hasColumn('student_grades', 'gpa')) {
                    $table->decimal('gpa', 4, 2)->nullable();
                }
                if (! Schema::hasColumn('student_grades', 'result_status')) {
                    $table->string('result_status')->default('pending');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
