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
        Schema::table('attendance_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('attendance_sessions', 'period')) {
                $table->tinyInteger('period')->default(1)->after('attendance_date')->comment('The class period or hour (e.g., 1, 2, 3, 4)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('attendance_sessions', 'period')) {
                $table->dropColumn('period');
            }
        });
    }
};
