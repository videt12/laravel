<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds teacher_assignment_id (nullable) to attendance_sessions so that
     * each session can optionally be linked to a TeacherAssignment record,
     * which already encodes: Employee (Teacher) + ClassSubject (Class + Subject).
     *
     * Also adds a composite unique index on (academic_year_id, class_id,
     * attendance_date, period) to prevent duplicate sessions at the DB level.
     */
    public function up(): void
    {
        // 1. Add teacher_assignment_id column (nullable — preserves all existing rows)
        Schema::table('attendance_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('attendance_sessions', 'teacher_assignment_id')) {
                $table->unsignedBigInteger('teacher_assignment_id')
                    ->nullable()
                    ->after('academic_year_id')
                    ->comment('Optional link to teacher_assignments; gives Teacher + Subject + Class');
            }
        });

        // 2. Add FK for teacher_assignment_id (safe — column is nullable)
        $this->addForeignKeyIfMissing(
            'attendance_sessions',
            'fk_as_teacher_assignment_id',
            'teacher_assignment_id',
            'teacher_assignments',
            'id'
        );

        // 3. Add index on teacher_assignment_id for faster joins
        $this->addIndexIfMissing(
            'attendance_sessions',
            'as_teacher_assignment_id_index',
            'teacher_assignment_id'
        );

        // 4. Add composite unique index to prevent duplicate sessions
        //    (same academic year + class + date + period = duplicate)
        $this->addCompositeUniqueIfMissing(
            'attendance_sessions',
            'as_unique_year_class_date_period',
            ['academic_year_id', 'class_id', 'attendance_date', 'period']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            // Drop unique index first
            $this->dropIndexIfExists('attendance_sessions', 'as_unique_year_class_date_period');
            // Drop FK
            $this->dropForeignIfExists('attendance_sessions', 'fk_as_teacher_assignment_id');
            // Drop index
            $this->dropIndexIfExists('attendance_sessions', 'as_teacher_assignment_id_index');
            // Drop column
            if (Schema::hasColumn('attendance_sessions', 'teacher_assignment_id')) {
                $table->dropColumn('teacher_assignment_id');
            }
        });
    }

    /* ─────────────────────────────────────────────────────────────
    |  Helper Methods
    ───────────────────────────────────────────────────────────── */

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

        if (($exists[0]->cnt ?? 0) == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, fn ($t) => $t->index($column, $indexName));
        }
    }

        private function addCompositeUniqueIfMissing(string $table, string $indexName, array $columns): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                if (!\Illuminate\Support\Facades\Schema::hasIndex($table, $indexName)) {
                    \Illuminate\Support\Facades\Schema::table($table, fn ($tbl) => $tbl->unique($columns, $indexName));
                }
            } catch (\Throwable $e) {}
            return;
        }

        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);

        if (($exists[0]->cnt ?? 0) == 0) {
            // Verify all columns exist before adding index
            $allExist = collect($columns)->every(fn ($c) => Schema::hasColumn($table, $c));
            if ($allExist) {
                Schema::table($table, fn ($t) => $t->unique($columns, $indexName));
            }
        }
    }

        private function addForeignKeyIfMissing(string $table, string $fkName, string $column, string $refTable, string $refColumn): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, $column)) {
                    \Illuminate\Support\Facades\Schema::table($table, function (\Illuminate\Database\Schema\Blueprint $t) use ($fkName, $column, $refTable, $refColumn) {
                        $t->foreign($column, $fkName)->references($refColumn)->on($refTable)->nullOnDelete();
                    });
                }
            } catch (\Throwable $e) {}
            return;
        }

        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_NAME = ?
        ', [$table, $fkName]);

        if (($exists[0]->cnt ?? 0) == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $t) use ($fkName, $column, $refTable, $refColumn) {
                $t->foreign($column, $fkName)
                    ->references($refColumn)
                    ->on($refTable)
                    ->nullOnDelete();
            });
        }
    }

        private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                \Illuminate\Support\Facades\Schema::table($table, fn ($tbl) => $tbl->dropIndex($indexName));
            } catch (\Throwable $e) {}
            return;
        }

        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);

        if (($exists[0]->cnt ?? 0) > 0) {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
        }
    }

        private function dropForeignIfExists(string $table, string $fkName): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                \Illuminate\Support\Facades\Schema::table($table, fn ($tbl) => $tbl->dropForeign($fkName));
            } catch (\Throwable $e) {}
            return;
        }

        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?
        ', [$table, $fkName]);

        if (($exists[0]->cnt ?? 0) > 0) {
            Schema::table($table, fn ($t) => $t->dropForeign($fkName));
        }
    }
};
