<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_addresses', function (Blueprint $table) {
            if (! Schema::hasColumn('student_addresses', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('student_addresses', 'current_address')) {
                $table->text('current_address')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_addresses', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('current_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_addresses', function (Blueprint $table) {
            $table->dropColumnIfExists(['student_id', 'current_address', 'permanent_address']);
        });
    }
};
