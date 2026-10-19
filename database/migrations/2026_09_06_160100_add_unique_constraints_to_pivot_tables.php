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
        // 1. student_guardians: prevent duplicate student-guardian pairs
        Schema::table('student_guardians', function (Blueprint $table) {
            $table->unique(['student_id', 'guardian_id'], 'student_guardian_unique');
        });

        // 2. user_roles: prevent duplicate user-role assignments
        Schema::table('user_roles', function (Blueprint $table) {
            $table->unique(['user_id', 'role_id'], 'user_role_unique');
        });

        // 3. role_permissions: prevent duplicate role-permission assignments
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->unique(['role_id', 'permission_id'], 'role_permission_unique');
        });

        // 4. class_subjects: prevent duplicate class-subject allocations
        Schema::table('class_subjects', function (Blueprint $table) {
            $table->unique(['class_id', 'subject_id'], 'class_subject_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_guardians', function (Blueprint $table) {
            $table->dropUnique('student_guardian_unique');
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropUnique('user_role_unique');
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropUnique('role_permission_unique');
        });

        Schema::table('class_subjects', function (Blueprint $table) {
            $table->dropUnique('class_subject_unique');
        });
    }
};
