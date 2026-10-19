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
        if (Schema::hasTable('employee_attendances')) {
            Schema::table('employee_attendances', function (Blueprint $table) {
                if (! Schema::hasColumn('employee_attendances', 'afternoon_in_time')) {
                    $table->time('afternoon_in_time')->nullable()->after('out_time');
                }
                if (! Schema::hasColumn('employee_attendances', 'afternoon_out_time')) {
                    $table->time('afternoon_out_time')->nullable()->after('afternoon_in_time');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('employee_attendances')) {
            Schema::table('employee_attendances', function (Blueprint $table) {
                if (Schema::hasColumn('employee_attendances', 'afternoon_out_time')) {
                    $table->dropColumn('afternoon_out_time');
                }
                if (Schema::hasColumn('employee_attendances', 'afternoon_in_time')) {
                    $table->dropColumn('afternoon_in_time');
                }
            });
        }
    }
};
