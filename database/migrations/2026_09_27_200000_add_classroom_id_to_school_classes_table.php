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
        Schema::table('school_classes', function (Blueprint $table) {
            if (! Schema::hasColumn('school_classes', 'classroom_id')) {
                $table->unsignedBigInteger('classroom_id')->nullable()->after('capacity');
            }
        });

        Schema::table('school_classes', function (Blueprint $table) {
            $table->foreign('classroom_id', 'fk_school_classes_classroom')
                ->references('id')
                ->on('classrooms')
                ->nullOnDelete();

            $table->index('classroom_id', 'school_classes_classroom_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropForeign('fk_school_classes_classroom');
            $table->dropIndex('school_classes_classroom_id_index');
            $table->dropColumn('classroom_id');
        });
    }
};
