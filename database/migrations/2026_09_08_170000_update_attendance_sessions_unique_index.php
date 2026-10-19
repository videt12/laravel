<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops the old unique constraint (academic_year_id, class_id, attendance_date, period)
     * which prevented distinct subjects/teacher assignments from holding sessions in the
     * same period on the same day.
     *
     * Adds the composite unique index covering:
     * (academic_year_id, class_id, attendance_date, period, teacher_assignment_id)
     */
    public function up(): void
    {
        // 1. Add the new composite unique index first so InnoDB retains an index on academic_year_id for foreign keys
        $this->addCompositeUniqueIfMissing(
            'attendance_sessions',
            'as_unique_year_class_date_period_assignment',
            ['academic_year_id', 'class_id', 'attendance_date', 'period', 'teacher_assignment_id']
        );

        // 2. Safely drop the old unique constraint
        $this->dropIndexIfExists('attendance_sessions', 'as_unique_year_class_date_period');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Restore old unique index first
        $this->addCompositeUniqueIfMissing(
            'attendance_sessions',
            'as_unique_year_class_date_period',
            ['academic_year_id', 'class_id', 'attendance_date', 'period']
        );

        // 2. Drop the new composite unique index
        $this->dropIndexIfExists('attendance_sessions', 'as_unique_year_class_date_period_assignment');
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
            $allExist = collect($columns)->every(fn ($c) => Schema::hasColumn($table, $c));
            if ($allExist) {
                Schema::table($table, fn ($t) => $t->unique($columns, $indexName));
            }
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
};
