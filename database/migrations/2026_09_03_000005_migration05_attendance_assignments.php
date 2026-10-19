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
        // ATTENDANCE_STATUSES
        // ─────────────────────────────────────────
        Schema::table('attendance_statuses', function (Blueprint $table) {
            if (! Schema::hasColumn('attendance_statuses', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('attendance_statuses', 'code')) {
                $table->string('code', 10)->nullable()->after('name');
            }
            if (! Schema::hasColumn('attendance_statuses', 'description')) {
                $table->text('description')->nullable()->after('code');
            }
        });

        // ─────────────────────────────────────────
        // ATTENDANCE_SESSIONS
        // ─────────────────────────────────────────
        Schema::table('attendance_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('attendance_sessions', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('id');
            }
            if (! Schema::hasColumn('attendance_sessions', 'class_id')) {
                $table->unsignedBigInteger('class_id')->after('academic_year_id');
            }
            if (! Schema::hasColumn('attendance_sessions', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            }
            if (! Schema::hasColumn('attendance_sessions', 'attendance_date')) {
                $table->date('attendance_date')->after('section_id');
            }
            if (! Schema::hasColumn('attendance_sessions', 'recorded_by')) {
                $table->unsignedBigInteger('recorded_by')->nullable()->after('attendance_date');
            }
            if (! Schema::hasColumn('attendance_sessions', 'status')) {
                $table->string('status')->default('open')->after('recorded_by');
            }
            if (! Schema::hasColumn('attendance_sessions', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_ATTENDANCES
        // ─────────────────────────────────────────
        Schema::table('student_attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('student_attendances', 'attendance_session_id')) {
                $table->unsignedBigInteger('attendance_session_id')->after('id');
            }
            if (! Schema::hasColumn('student_attendances', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('attendance_session_id');
            }
            if (! Schema::hasColumn('student_attendances', 'attendance_status_id')) {
                $table->unsignedBigInteger('attendance_status_id')->after('student_id');
            }
            if (! Schema::hasColumn('student_attendances', 'remarks')) {
                $table->text('remarks')->nullable()->after('attendance_status_id');
            }
        });

        // ─────────────────────────────────────────
        // ATTENDANCE_CORRECTIONS
        // ─────────────────────────────────────────
        Schema::table('attendance_corrections', function (Blueprint $table) {
            if (! Schema::hasColumn('attendance_corrections', 'student_attendance_id')) {
                $table->unsignedBigInteger('student_attendance_id')->after('id');
            }
            if (! Schema::hasColumn('attendance_corrections', 'old_status_id')) {
                $table->unsignedBigInteger('old_status_id')->after('student_attendance_id');
            }
            if (! Schema::hasColumn('attendance_corrections', 'new_status_id')) {
                $table->unsignedBigInteger('new_status_id')->after('old_status_id');
            }
            if (! Schema::hasColumn('attendance_corrections', 'reason')) {
                $table->text('reason')->nullable()->after('new_status_id');
            }
            if (! Schema::hasColumn('attendance_corrections', 'requested_by')) {
                $table->unsignedBigInteger('requested_by')->nullable()->after('reason');
            }
            if (! Schema::hasColumn('attendance_corrections', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('requested_by');
            }
            if (! Schema::hasColumn('attendance_corrections', 'status')) {
                $table->string('status')->default('pending')->after('approved_by');
            }
        });

        // ─────────────────────────────────────────
        // ASSIGNMENTS
        // ─────────────────────────────────────────
        Schema::table('assignments', function (Blueprint $table) {
            if (! Schema::hasColumn('assignments', 'class_id')) {
                $table->unsignedBigInteger('class_id')->after('id');
            }
            if (! Schema::hasColumn('assignments', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            }
            if (! Schema::hasColumn('assignments', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->after('section_id');
            }
            if (! Schema::hasColumn('assignments', 'teacher_id')) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('subject_id');
            }
            if (! Schema::hasColumn('assignments', 'title')) {
                $table->string('title')->after('teacher_id');
            }
            if (! Schema::hasColumn('assignments', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (! Schema::hasColumn('assignments', 'due_date')) {
                $table->date('due_date')->nullable()->after('description');
            }
            if (! Schema::hasColumn('assignments', 'total_marks')) {
                $table->decimal('total_marks', 8, 2)->nullable()->after('due_date');
            }
            if (! Schema::hasColumn('assignments', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // ASSIGNMENT_ATTACHMENTS
        // ─────────────────────────────────────────
        Schema::table('assignment_attachments', function (Blueprint $table) {
            if (! Schema::hasColumn('assignment_attachments', 'assignment_id')) {
                $table->unsignedBigInteger('assignment_id')->after('id');
            }
            if (! Schema::hasColumn('assignment_attachments', 'file_name')) {
                $table->string('file_name')->after('assignment_id');
            }
            if (! Schema::hasColumn('assignment_attachments', 'file_path')) {
                $table->string('file_path')->after('file_name');
            }
        });

        // ─────────────────────────────────────────
        // ASSIGNMENT_SUBMISSIONS
        // ─────────────────────────────────────────
        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (! Schema::hasColumn('assignment_submissions', 'assignment_id')) {
                $table->unsignedBigInteger('assignment_id')->after('id');
            }
            if (! Schema::hasColumn('assignment_submissions', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('assignment_id');
            }
            if (! Schema::hasColumn('assignment_submissions', 'submission_date')) {
                $table->date('submission_date')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('assignment_submissions', 'status')) {
                $table->string('status')->default('submitted')->after('submission_date');
            }
            if (! Schema::hasColumn('assignment_submissions', 'remarks')) {
                $table->text('remarks')->nullable()->after('status');
            }
            if (! Schema::hasColumn('assignment_submissions', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // ASSIGNMENT_GRADES
        // ─────────────────────────────────────────
        Schema::table('assignment_grades', function (Blueprint $table) {
            if (! Schema::hasColumn('assignment_grades', 'assignment_submission_id')) {
                $table->unsignedBigInteger('assignment_submission_id')->after('id');
            }
            if (! Schema::hasColumn('assignment_grades', 'marks_obtained')) {
                $table->decimal('marks_obtained', 8, 2)->nullable()->after('assignment_submission_id');
            }
            if (! Schema::hasColumn('assignment_grades', 'grade')) {
                $table->string('grade', 10)->nullable()->after('marks_obtained');
            }
            if (! Schema::hasColumn('assignment_grades', 'feedback')) {
                $table->text('feedback')->nullable()->after('grade');
            }
            if (! Schema::hasColumn('assignment_grades', 'graded_by')) {
                $table->unsignedBigInteger('graded_by')->nullable()->after('feedback');
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Attendance + Assignments
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('attendance_sessions', 'as_class_id_index', 'class_id');
        $this->addIndexIfMissing('attendance_sessions', 'as_attendance_date_index', 'attendance_date');
        $this->addIndexIfMissing('student_attendances', 'sa_session_id_index', 'attendance_session_id');
        $this->addIndexIfMissing('student_attendances', 'sa_student_id_index', 'student_id');
        $this->addIndexIfMissing('attendance_corrections', 'ac_attendance_id_index', 'student_attendance_id');
        $this->addIndexIfMissing('assignments', 'asgn_class_id_index', 'class_id');
        $this->addIndexIfMissing('assignments', 'asgn_teacher_id_index', 'teacher_id');
        $this->addIndexIfMissing('assignment_attachments', 'aa_assignment_id_index', 'assignment_id');
        $this->addIndexIfMissing('assignment_submissions', 'asub_assignment_id_index', 'assignment_id');
        $this->addIndexIfMissing('assignment_submissions', 'asub_student_id_index', 'student_id');
        $this->addIndexIfMissing('assignment_grades', 'ag_submission_id_index', 'assignment_submission_id');

        // UNIQUE on assignment_grades.assignment_submission_id (one grade per submission)
        $this->addUniqueIfMissing('assignment_grades', 'ag_submission_id_unique', 'assignment_submission_id');
    }

    public function down(): void {}

        private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, $column) && !\Illuminate\Support\Facades\Schema::hasIndex($table, $indexName)) {
                    \Illuminate\Support\Facades\Schema::table($table, fn ($tbl) => $tbl->index($column, $indexName));
                }
            } catch (\Throwable $e) {}
            return;
        }

        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);
        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, fn ($t) => $t->index($column, $indexName));
        }
    }

        private function addUniqueIfMissing(string $table, string $indexName, string $column): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, $column) && !\Illuminate\Support\Facades\Schema::hasIndex($table, $indexName)) {
                    \Illuminate\Support\Facades\Schema::table($table, fn ($tbl) => $tbl->unique($column, $indexName));
                }
            } catch (\Throwable $e) {}
            return;
        }

        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);
        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, fn ($t) => $t->unique($column, $indexName));
        }
    }
};
