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
        if (Schema::hasTable('salary_structures')) {
            Schema::table('salary_structures', function (Blueprint $table) {
                if (! Schema::hasColumn('salary_structures', 'employee_department_id')) {
                    $table->foreignId('employee_department_id')
                        ->nullable()
                        ->after('description')
                        ->constrained('employee_departments')
                        ->nullOnDelete();
                }

                if (! Schema::hasColumn('salary_structures', 'employee_position_id')) {
                    $table->foreignId('employee_position_id')
                        ->nullable()
                        ->after('employee_department_id')
                        ->constrained('employee_positions')
                        ->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('salary_structures')) {
            Schema::table('salary_structures', function (Blueprint $table) {
                if (Schema::hasColumn('salary_structures', 'employee_position_id')) {
                    $table->dropForeign(['employee_position_id']);
                    $table->dropColumn('employee_position_id');
                }

                if (Schema::hasColumn('salary_structures', 'employee_department_id')) {
                    $table->dropForeign(['employee_department_id']);
                    $table->dropColumn('employee_department_id');
                }
            });
        }
    }
};
