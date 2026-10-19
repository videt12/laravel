<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add priority, status, and created_by to student_notes.
     */
    public function up(): void
    {
        Schema::table('student_notes', function (Blueprint $table) {

            if (! Schema::hasColumn('student_notes', 'priority')) {
                $table->string('priority', 20)->default('normal')->after('follow_up_required');
                // Values: low | normal | high | urgent
            }

            if (! Schema::hasColumn('student_notes', 'status')) {
                $table->string('status', 20)->default('active')->after('priority');
                // Values: active | archived | resolved
            }

            if (! Schema::hasColumn('student_notes', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status');
                $table->foreign('created_by')
                    ->references('id')->on('users')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_notes', function (Blueprint $table) {
            if (Schema::hasColumn('student_notes', 'created_by')) {
                $table->dropForeign(['created_by']);
            }
            $table->dropColumnIfExists(['priority', 'status', 'created_by']);
        });
    }
};
