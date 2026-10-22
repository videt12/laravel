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
        if (Schema::hasTable('employee_statuses')) {
            Schema::table('employee_statuses', function (Blueprint $table) {
                if (! Schema::hasColumn('employee_statuses', 'code')) {
                    $table->string('code', 50)->nullable();
                }
                if (! Schema::hasColumn('employee_statuses', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
