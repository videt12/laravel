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
        Schema::table('school_branches', function (Blueprint $table) {
            $table->foreign('school_id', 'fk_school_branches_school')
                ->references('id')->on('schools')->cascadeOnDelete();
        });

        Schema::table('school_settings', function (Blueprint $table) {
            $table->foreign('school_id', 'fk_school_settings_school')
                ->references('id')->on('schools')->cascadeOnDelete();
        });

        Schema::table('school_terms', function (Blueprint $table) {
            $table->foreign('academic_year_id', 'fk_school_terms_academic_year')
                ->references('id')->on('academic_years')->cascadeOnDelete();
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->foreign('academic_year_id', 'fk_holidays_academic_year')
                ->references('id')->on('academic_years')->cascadeOnDelete();
        });

        Schema::table('time_slots', function (Blueprint $table) {
            $table->foreign('school_shift_id', 'fk_time_slots_school_shift')
                ->references('id')->on('school_shifts')->cascadeOnDelete();
        });

        Schema::table('login_activities', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_login_activities_user')
                ->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_user_roles_user')
                ->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('role_id', 'fk_user_roles_role')
                ->references('id')->on('roles')->cascadeOnDelete();
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->foreign('role_id', 'fk_role_permissions_role')
                ->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('permission_id', 'fk_role_permissions_permission')
                ->references('id')->on('permissions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropForeign('fk_role_permissions_role');
            $table->dropForeign('fk_role_permissions_permission');
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropForeign('fk_user_roles_user');
            $table->dropForeign('fk_user_roles_role');
        });

        Schema::table('login_activities', function (Blueprint $table) {
            $table->dropForeign('fk_login_activities_user');
        });

        Schema::table('time_slots', function (Blueprint $table) {
            $table->dropForeign('fk_time_slots_school_shift');
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->dropForeign('fk_holidays_academic_year');
        });

        Schema::table('school_terms', function (Blueprint $table) {
            $table->dropForeign('fk_school_terms_academic_year');
        });

        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropForeign('fk_school_settings_school');
        });

        Schema::table('school_branches', function (Blueprint $table) {
            $table->dropForeign('fk_school_branches_school');
        });
    }
};
