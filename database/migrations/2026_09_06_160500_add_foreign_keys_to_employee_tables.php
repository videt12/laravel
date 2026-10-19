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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_employees_user')
                ->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('employee_department_id', 'fk_employees_department')
                ->references('id')->on('employee_departments')->restrictOnDelete();
            $table->foreign('employee_position_id', 'fk_employees_position')
                ->references('id')->on('employee_positions')->restrictOnDelete();
            $table->foreign('employee_status_id', 'fk_employees_status')
                ->references('id')->on('employee_statuses')->restrictOnDelete();
        });

        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->foreign('employee_id', 'fk_employee_contacts_employee')
                ->references('id')->on('employees')->cascadeOnDelete();
        });

        Schema::table('employee_documents', function (Blueprint $table) {
            $table->foreign('employee_id', 'fk_employee_documents_employee')
                ->references('id')->on('employees')->cascadeOnDelete();
        });

        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->foreign('employee_id', 'fk_employee_attendances_employee')
                ->references('id')->on('employees')->cascadeOnDelete();
        });

        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->foreign('employee_id', 'fk_employee_leaves_employee')
                ->references('id')->on('employees')->cascadeOnDelete();
            $table->foreign('leave_type_id', 'fk_employee_leaves_type')
                ->references('id')->on('leave_types')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->dropForeign('fk_employee_leaves_employee');
            $table->dropForeign('fk_employee_leaves_type');
        });

        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->dropForeign('fk_employee_attendances_employee');
        });

        Schema::table('employee_documents', function (Blueprint $table) {
            $table->dropForeign('fk_employee_documents_employee');
        });

        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->dropForeign('fk_employee_contacts_employee');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign('fk_employees_user');
            $table->dropForeign('fk_employees_department');
            $table->dropForeign('fk_employees_position');
            $table->dropForeign('fk_employees_status');
        });
    }
};
