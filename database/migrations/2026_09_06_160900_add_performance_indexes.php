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
        $hasIndex = function (string $table, string $indexName): bool {
            if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
                return \Illuminate\Support\Facades\Schema::hasIndex($table, $indexName);
            }

            return count(DB::select('
                SELECT INDEX_NAME 
                FROM information_schema.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = ? 
                  AND INDEX_NAME = ?
            ', [$table, $indexName])) > 0;
        };

        Schema::table('student_attendances', function (Blueprint $table) use ($hasIndex) {
            if (! $hasIndex('student_attendances', 'idx_student_attendances_session_student')) {
                $table->index(['attendance_session_id', 'student_id'], 'idx_student_attendances_session_student');
            }
        });

        Schema::table('attendance_sessions', function (Blueprint $table) use ($hasIndex) {
            if (! $hasIndex('attendance_sessions', 'idx_attendance_sessions_date')) {
                $table->index('attendance_date', 'idx_attendance_sessions_date');
            }
            if (! $hasIndex('attendance_sessions', 'idx_attendance_sessions_status')) {
                $table->index('status', 'idx_attendance_sessions_status');
            }
        });

        Schema::table('assignments', function (Blueprint $table) use ($hasIndex) {
            if (! $hasIndex('assignments', 'idx_assignments_due_date')) {
                $table->index('due_date', 'idx_assignments_due_date');
            }
        });

        Schema::table('assignment_submissions', function (Blueprint $table) use ($hasIndex) {
            if (! $hasIndex('assignment_submissions', 'idx_submissions_assignment_student')) {
                $table->index(['assignment_id', 'student_id'], 'idx_submissions_assignment_student');
            }
            if (! $hasIndex('assignment_submissions', 'idx_submissions_status')) {
                $table->index('status', 'idx_submissions_status');
            }
        });

        Schema::table('student_invoices', function (Blueprint $table) use ($hasIndex) {
            if (! $hasIndex('student_invoices', 'idx_student_invoices_due_date')) {
                $table->index('due_date', 'idx_student_invoices_due_date');
            }
        });

        Schema::table('employees', function (Blueprint $table) use ($hasIndex) {
            if (! $hasIndex('employees', 'idx_employees_joining_date')) {
                $table->index('date_of_joining', 'idx_employees_joining_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasIndex = function (string $table, string $indexName): bool {
            if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
                return \Illuminate\Support\Facades\Schema::hasIndex($table, $indexName);
            }

            return count(DB::select('
                SELECT INDEX_NAME 
                FROM information_schema.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = ? 
                  AND INDEX_NAME = ?
            ', [$table, $indexName])) > 0;
        };

        Schema::table('employees', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('employees', 'idx_employees_joining_date')) {
                $table->dropIndex('idx_employees_joining_date');
            }
        });

        Schema::table('student_invoices', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('student_invoices', 'idx_student_invoices_due_date')) {
                $table->dropIndex('idx_student_invoices_due_date');
            }
        });

        Schema::table('assignment_submissions', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('assignment_submissions', 'idx_submissions_assignment_student')) {
                $table->dropIndex('idx_submissions_assignment_student');
            }
            if ($hasIndex('assignment_submissions', 'idx_submissions_status')) {
                $table->dropIndex('idx_submissions_status');
            }
        });

        Schema::table('assignments', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('assignments', 'idx_assignments_due_date')) {
                $table->dropIndex('idx_assignments_due_date');
            }
        });

        Schema::table('attendance_sessions', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('attendance_sessions', 'idx_attendance_sessions_date')) {
                $table->dropIndex('idx_attendance_sessions_date');
            }
            if ($hasIndex('attendance_sessions', 'idx_attendance_sessions_status')) {
                $table->dropIndex('idx_attendance_sessions_status');
            }
        });

        Schema::table('student_attendances', function (Blueprint $table) use ($hasIndex) {
            if ($hasIndex('student_attendances', 'idx_student_attendances_session_student')) {
                $table->dropIndex('idx_student_attendances_session_student');
            }
        });
    }
};
