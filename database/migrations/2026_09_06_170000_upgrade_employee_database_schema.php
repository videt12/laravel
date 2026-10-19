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
        // 1. Upgrade employee_departments: add code and status
        Schema::table('employee_departments', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_departments', 'code')) {
                $table->string('code', 50)->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('employee_departments', 'status')) {
                $table->string('status', 20)->default('active')->after('description');
            }
        });

        // 2. Upgrade employee_positions: add code and status
        Schema::table('employee_positions', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_positions', 'code')) {
                $table->string('code', 50)->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('employee_positions', 'status')) {
                $table->string('status', 20)->default('active')->after('description');
            }
        });

        // 3. Upgrade employee_statuses: add code and is_active flag
        Schema::table('employee_statuses', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_statuses', 'code')) {
                $table->string('code', 50)->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('employee_statuses', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
        });

        // 4. Upgrade leave_types: add code and is_paid flag
        Schema::table('leave_types', function (Blueprint $table) {
            if (! Schema::hasColumn('leave_types', 'code')) {
                $table->string('code', 50)->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('leave_types', 'is_paid')) {
                $table->boolean('is_paid')->default(true)->after('days_allowed');
            }
        });

        // 5. Upgrade employee_documents: add title and file_size
        Schema::table('employee_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_documents', 'title')) {
                $table->string('title', 255)->nullable()->after('document_type');
            }
            if (! Schema::hasColumn('employee_documents', 'file_size')) {
                $table->unsignedBigInteger('file_size')->nullable()->after('document_path');
            }
        });

        // 6. Upgrade employee_leaves: add foreign key approved_by -> users(id) and performance indexes
        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->foreign('approved_by', 'fk_employee_leaves_approved_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->index(['employee_id', 'status'], 'idx_el_emp_status');
            $table->index(['start_date', 'end_date', 'status'], 'idx_el_dates_status');
            $table->index('approved_by', 'idx_el_approved_by');
        });

        // 7. Upgrade employee_attendances: unique per employee per date + composite index
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->unique(['employee_id', 'attendance_date'], 'unique_employee_attendance_date');
            $table->index(['attendance_date', 'status'], 'idx_ea_date_status');
        });

        // 8. Upgrade employees: composite status & department index, plus deleted_at
        Schema::table('employees', function (Blueprint $table) {
            $table->index(['employee_status_id', 'employee_department_id'], 'idx_emp_status_dept');
            $table->index('deleted_at', 'idx_emp_deleted_at');
        });

        // 9. Upgrade employee_contacts: index phone & email
        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->index('phone', 'idx_ec_phone');
            $table->index('email', 'idx_ec_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_contacts', function (Blueprint $table) {
            $table->dropIndex('idx_ec_phone');
            $table->dropIndex('idx_ec_email');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('idx_emp_status_dept');
            $table->dropIndex('idx_emp_deleted_at');
        });

        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->dropUnique('unique_employee_attendance_date');
            $table->dropIndex('idx_ea_date_status');
        });

        Schema::table('employee_leaves', function (Blueprint $table) {
            $table->dropForeign('fk_employee_leaves_approved_by');
            $table->dropIndex('idx_el_emp_status');
            $table->dropIndex('idx_el_dates_status');
            $table->dropIndex('idx_el_approved_by');
        });

        Schema::table('employee_documents', function (Blueprint $table) {
            if (Schema::hasColumn('employee_documents', 'file_size')) {
                $table->dropColumn('file_size');
            }
            if (Schema::hasColumn('employee_documents', 'title')) {
                $table->dropColumn('title');
            }
        });

        Schema::table('leave_types', function (Blueprint $table) {
            if (Schema::hasColumn('leave_types', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
            if (Schema::hasColumn('leave_types', 'code')) {
                $table->dropColumn('code');
            }
        });

        Schema::table('employee_statuses', function (Blueprint $table) {
            if (Schema::hasColumn('employee_statuses', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('employee_statuses', 'code')) {
                $table->dropColumn('code');
            }
        });

        Schema::table('employee_positions', function (Blueprint $table) {
            if (Schema::hasColumn('employee_positions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('employee_positions', 'code')) {
                $table->dropColumn('code');
            }
        });

        Schema::table('employee_departments', function (Blueprint $table) {
            if (Schema::hasColumn('employee_departments', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('employee_departments', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
