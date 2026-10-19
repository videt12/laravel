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
        Schema::table('teacher_assignments', function (Blueprint $table) {
            if (! Schema::hasColumn('teacher_assignments', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable()->after('employee_id');
            }
            if (Schema::hasColumn('teacher_assignments', 'class_subject_id')) {
                $table->unsignedBigInteger('class_subject_id')->nullable()->change();
            }
        });

        if (Schema::hasTable('class_subjects') && Schema::hasColumn('teacher_assignments', 'class_id')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement('
                    UPDATE teacher_assignments ta
                    JOIN class_subjects cs ON ta.class_subject_id = cs.id
                    SET ta.class_id = cs.class_id
                    WHERE ta.class_id IS NULL AND ta.class_subject_id IS NOT NULL
                ');
            } else {
                DB::statement('
                    UPDATE teacher_assignments
                    SET class_id = (
                        SELECT class_id FROM class_subjects
                        WHERE class_subjects.id = teacher_assignments.class_subject_id
                    )
                    WHERE class_id IS NULL AND class_subject_id IS NOT NULL
                ');
            }
        }

        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->foreign('class_id', 'fk_teacher_assignments_class')
                ->references('id')
                ->on('school_classes')
                ->cascadeOnDelete();

            $table->index('class_id', 'teacher_assignments_class_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropForeign('fk_teacher_assignments_class');
            $table->dropIndex('teacher_assignments_class_id_index');
            $table->dropColumn('class_id');
            $table->unsignedBigInteger('class_subject_id')->nullable(false)->change();
        });
    }
};
