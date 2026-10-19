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
        // GRADES
        // ─────────────────────────────────────────
        Schema::table('grades', function (Blueprint $table) {
            if (! Schema::hasColumn('grades', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('grades', 'level')) {
                $table->unsignedTinyInteger('level')->nullable()->after('name');
            }
            if (! Schema::hasColumn('grades', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // SECTIONS
        // ─────────────────────────────────────────
        Schema::table('sections', function (Blueprint $table) {
            if (! Schema::hasColumn('sections', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('sections', 'capacity')) {
                $table->unsignedSmallInteger('capacity')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // SCHOOL_CLASSES  (table already named school_classes via migration)
        // ─────────────────────────────────────────
        Schema::table('school_classes', function (Blueprint $table) {
            if (! Schema::hasColumn('school_classes', 'grade_id')) {
                $table->unsignedBigInteger('grade_id')->after('id');
            }
            if (! Schema::hasColumn('school_classes', 'section_id')) {
                $table->unsignedBigInteger('section_id')->after('grade_id');
            }
            if (! Schema::hasColumn('school_classes', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('section_id');
            }
            if (! Schema::hasColumn('school_classes', 'capacity')) {
                $table->unsignedSmallInteger('capacity')->nullable()->after('academic_year_id');
            }
            if (! Schema::hasColumn('school_classes', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // SUBJECTS
        // ─────────────────────────────────────────
        Schema::table('subjects', function (Blueprint $table) {
            if (! Schema::hasColumn('subjects', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('subjects', 'code')) {
                $table->string('code', 20)->nullable()->after('name');
            }
            if (! Schema::hasColumn('subjects', 'type')) {
                $table->string('type')->default('mandatory')->after('code');
            }
            if (! Schema::hasColumn('subjects', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // SUBJECT_GROUPS
        // ─────────────────────────────────────────
        Schema::table('subject_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('subject_groups', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('subject_groups', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // CLASS_SUBJECTS
        // ─────────────────────────────────────────
        Schema::table('class_subjects', function (Blueprint $table) {
            if (! Schema::hasColumn('class_subjects', 'class_id')) {
                $table->unsignedBigInteger('class_id')->after('id');
            }
            if (! Schema::hasColumn('class_subjects', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->after('class_id');
            }
        });

        // ─────────────────────────────────────────
        // CLASSROOMS
        // ─────────────────────────────────────────
        Schema::table('classrooms', function (Blueprint $table) {
            if (! Schema::hasColumn('classrooms', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('classrooms', 'building')) {
                $table->string('building')->nullable()->after('name');
            }
            if (! Schema::hasColumn('classrooms', 'capacity')) {
                $table->unsignedSmallInteger('capacity')->nullable()->after('building');
            }
        });

        // ─────────────────────────────────────────
        // TEACHER_ASSIGNMENTS
        // ─────────────────────────────────────────
        Schema::table('teacher_assignments', function (Blueprint $table) {
            if (! Schema::hasColumn('teacher_assignments', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->after('id');
            }
            if (! Schema::hasColumn('teacher_assignments', 'class_subject_id')) {
                $table->unsignedBigInteger('class_subject_id')->after('employee_id');
            }
            if (! Schema::hasColumn('teacher_assignments', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('class_subject_id');
            }
        });

        // ─────────────────────────────────────────
        // TIMETABLES
        // ─────────────────────────────────────────
        Schema::table('timetables', function (Blueprint $table) {
            if (! Schema::hasColumn('timetables', 'class_id')) {
                $table->unsignedBigInteger('class_id')->after('id');
            }
            if (! Schema::hasColumn('timetables', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('class_id');
            }
            if (! Schema::hasColumn('timetables', 'school_term_id')) {
                $table->unsignedBigInteger('school_term_id')->nullable()->after('academic_year_id');
            }
        });

        // ─────────────────────────────────────────
        // TIMETABLE_ENTRIES
        // ─────────────────────────────────────────
        Schema::table('timetable_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('timetable_entries', 'timetable_id')) {
                $table->unsignedBigInteger('timetable_id')->after('id');
            }
            if (! Schema::hasColumn('timetable_entries', 'time_slot_id')) {
                $table->unsignedBigInteger('time_slot_id')->after('timetable_id');
            }
            if (! Schema::hasColumn('timetable_entries', 'day_of_week')) {
                $table->tinyInteger('day_of_week')->unsigned()->after('time_slot_id')->comment('1=Monday...7=Sunday');
            }
            if (! Schema::hasColumn('timetable_entries', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->after('day_of_week');
            }
            if (! Schema::hasColumn('timetable_entries', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('subject_id');
            }
            if (! Schema::hasColumn('timetable_entries', 'classroom_id')) {
                $table->unsignedBigInteger('classroom_id')->nullable()->after('teacher_id');
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Academics
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('school_classes', 'sc_grade_id_index', 'grade_id');
        $this->addIndexIfMissing('school_classes', 'sc_section_id_index', 'section_id');
        $this->addIndexIfMissing('school_classes', 'sc_academic_year_id_index', 'academic_year_id');
        $this->addIndexIfMissing('class_subjects', 'cs_class_id_index', 'class_id');
        $this->addIndexIfMissing('class_subjects', 'cs_subject_id_index', 'subject_id');
        $this->addIndexIfMissing('teacher_assignments', 'ta_employee_id_index', 'employee_id');
        $this->addIndexIfMissing('teacher_assignments', 'ta_class_subject_id_index', 'class_subject_id');
        $this->addIndexIfMissing('timetables', 'tt_class_id_index', 'class_id');
        $this->addIndexIfMissing('timetables', 'tt_academic_year_id_index', 'academic_year_id');
        $this->addIndexIfMissing('timetable_entries', 'te_timetable_id_index', 'timetable_id');
        $this->addIndexIfMissing('timetable_entries', 'te_teacher_id_index', 'teacher_id');
    }

    public function down(): void {}

    private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::select('
            SELECT COUNT(*) as cnt
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND INDEX_NAME = ?
        ', [$table, $indexName]);

        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $tbl) use ($column, $indexName) {
                $tbl->index($column, $indexName);
            });
        }
    }
};
