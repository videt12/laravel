<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─────────────────────────────────────────
        // EXAM_TYPES
        // ─────────────────────────────────────────
        Schema::table('exam_types', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_types', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('exam_types', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // GRADE_SCALES
        // ─────────────────────────────────────────
        Schema::table('grade_scales', function (Blueprint $table) {
            if (! Schema::hasColumn('grade_scales', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('grade_scales', 'min_percentage')) {
                $table->decimal('min_percentage', 5, 2)->after('name');
            }
            if (! Schema::hasColumn('grade_scales', 'max_percentage')) {
                $table->decimal('max_percentage', 5, 2)->after('min_percentage');
            }
            if (! Schema::hasColumn('grade_scales', 'grade')) {
                $table->string('grade', 10)->after('max_percentage');
            }
            if (! Schema::hasColumn('grade_scales', 'gpa_point')) {
                $table->decimal('gpa_point', 4, 2)->nullable()->after('grade');
            }
        });

        // ─────────────────────────────────────────
        // EXAMS  (depends on exam_types, academic_years, school_terms)
        // ─────────────────────────────────────────
        Schema::table('exams', function (Blueprint $table) {
            if (! Schema::hasColumn('exams', 'exam_type_id')) {
                $table->unsignedBigInteger('exam_type_id')->after('id');
            }
            if (! Schema::hasColumn('exams', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('exam_type_id');
            }
            if (! Schema::hasColumn('exams', 'school_term_id')) {
                $table->unsignedBigInteger('school_term_id')->nullable()->after('academic_year_id');
            }
            if (! Schema::hasColumn('exams', 'name')) {
                $table->string('name')->after('school_term_id');
            }
            if (! Schema::hasColumn('exams', 'start_date')) {
                $table->date('start_date')->nullable()->after('name');
            }
            if (! Schema::hasColumn('exams', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });

        // ─────────────────────────────────────────
        // EXAM_SCHEDULES  (depends on exams, school_classes, sections)
        // ─────────────────────────────────────────
        Schema::table('exam_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_schedules', 'exam_id')) {
                $table->unsignedBigInteger('exam_id')->after('id');
            }
            if (! Schema::hasColumn('exam_schedules', 'class_id')) {
                $table->unsignedBigInteger('class_id')->after('exam_id');
            }
            if (! Schema::hasColumn('exam_schedules', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            }
        });

        // ─────────────────────────────────────────
        // EXAM_SUBJECTS  (depends on exam_schedules, subjects)
        // ─────────────────────────────────────────
        Schema::table('exam_subjects', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_subjects', 'exam_schedule_id')) {
                $table->unsignedBigInteger('exam_schedule_id')->after('id');
            }
            if (! Schema::hasColumn('exam_subjects', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->after('exam_schedule_id');
            }
            if (! Schema::hasColumn('exam_subjects', 'exam_date')) {
                $table->date('exam_date')->nullable()->after('subject_id');
            }
            if (! Schema::hasColumn('exam_subjects', 'start_time')) {
                $table->time('start_time')->nullable()->after('exam_date');
            }
            if (! Schema::hasColumn('exam_subjects', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
            if (! Schema::hasColumn('exam_subjects', 'max_marks')) {
                $table->decimal('max_marks', 8, 2)->nullable()->after('end_time');
            }
            if (! Schema::hasColumn('exam_subjects', 'pass_marks')) {
                $table->decimal('pass_marks', 8, 2)->nullable()->after('max_marks');
            }
        });

        // ─────────────────────────────────────────
        // EXAM_RESULTS  (depends on exam_subjects, students)
        // ─────────────────────────────────────────
        Schema::table('exam_results', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_results', 'exam_subject_id')) {
                $table->unsignedBigInteger('exam_subject_id')->after('id');
            }
            if (! Schema::hasColumn('exam_results', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('exam_subject_id');
            }
            if (! Schema::hasColumn('exam_results', 'marks_obtained')) {
                $table->decimal('marks_obtained', 8, 2)->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('exam_results', 'grade')) {
                $table->string('grade', 10)->nullable()->after('marks_obtained');
            }
            if (! Schema::hasColumn('exam_results', 'remarks')) {
                $table->text('remarks')->nullable()->after('grade');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_GRADES  (aggregate — depends on exams, students)
        // ─────────────────────────────────────────
        Schema::table('student_grades', function (Blueprint $table) {
            if (! Schema::hasColumn('student_grades', 'exam_id')) {
                $table->unsignedBigInteger('exam_id')->after('id');
            }
            if (! Schema::hasColumn('student_grades', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('exam_id');
            }
            if (! Schema::hasColumn('student_grades', 'total_marks')) {
                $table->decimal('total_marks', 10, 2)->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_grades', 'percentage')) {
                $table->decimal('percentage', 5, 2)->nullable()->after('total_marks');
            }
            if (! Schema::hasColumn('student_grades', 'grade')) {
                $table->string('grade', 10)->nullable()->after('percentage');
            }
            if (! Schema::hasColumn('student_grades', 'gpa')) {
                $table->decimal('gpa', 4, 2)->nullable()->after('grade');
            }
            if (! Schema::hasColumn('student_grades', 'result_status')) {
                $table->string('result_status')->default('pending')->after('gpa');
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Examination
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('exams', 'exams_exam_type_id_index', 'exam_type_id');
        $this->addIndexIfMissing('exams', 'exams_academic_year_id_index', 'academic_year_id');
        $this->addIndexIfMissing('exam_schedules', 'es_exam_id_index', 'exam_id');
        $this->addIndexIfMissing('exam_schedules', 'es_class_id_index', 'class_id');
        $this->addIndexIfMissing('exam_subjects', 'esub_exam_schedule_id_index', 'exam_schedule_id');
        $this->addIndexIfMissing('exam_results', 'er_exam_subject_id_index', 'exam_subject_id');
        $this->addIndexIfMissing('exam_results', 'er_student_id_index', 'student_id');
        $this->addIndexIfMissing('student_grades', 'sg_exam_id_index', 'exam_id');
        $this->addIndexIfMissing('student_grades', 'sg_student_id_index', 'student_id');
    }

    public function down(): void {}

    private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);
        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, fn ($t) => $t->index($column, $indexName));
        }
    }
};
