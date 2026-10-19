<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─────────────────────────────────────────
        // EMPLOYEE_POSITIONS
        // ─────────────────────────────────────────
        Schema::table('employee_positions', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_positions', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('employee_positions', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // EMPLOYEE_DEPARTMENTS
        // ─────────────────────────────────────────
        Schema::table('employee_departments', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_departments', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('employee_departments', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // EMPLOYEE_STATUSES
        // ─────────────────────────────────────────
        Schema::table('employee_statuses', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_statuses', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('employee_statuses', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // LEAVE_TYPES
        // ─────────────────────────────────────────
        Schema::table('leave_types', function (Blueprint $table) {
            if (! Schema::hasColumn('leave_types', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('leave_types', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (! Schema::hasColumn('leave_types', 'days_allowed')) {
                $table->unsignedSmallInteger('days_allowed')->nullable()->after('description');
            }
        });

        // ─────────────────────────────────────────
        // EMPLOYEES  (depends on positions, departments, statuses above)
        // ─────────────────────────────────────────
        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('employees', 'employee_department_id')) {
                $table->unsignedBigInteger('employee_department_id')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('employees', 'employee_position_id')) {
                $table->unsignedBigInteger('employee_position_id')->nullable()->after('employee_department_id');
            }
            if (! Schema::hasColumn('employees', 'employee_status_id')) {
                $table->unsignedBigInteger('employee_status_id')->nullable()->after('employee_position_id');
            }
            if (! Schema::hasColumn('employees', 'employee_code')) {
                $table->string('employee_code', 50)->nullable()->unique()->after('employee_status_id');
            }
            if (! Schema::hasColumn('employees', 'date_of_joining')) {
                $table->date('date_of_joining')->nullable()->after('employee_code');
            }
            if (! Schema::hasColumn('employees', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('date_of_joining');
            }
            if (! Schema::hasColumn('employees', 'gender')) {
                $table->string('gender', 10)->nullable()->after('date_of_birth');
            }
            if (! Schema::hasColumn('employees', 'blood_group')) {
                $table->string('blood_group', 10)->nullable()->after('gender');
            }
            if (! Schema::hasColumn('employees', 'basic_salary')) {
                $table->decimal('basic_salary', 12, 2)->nullable()->after('blood_group');
            }
            if (! Schema::hasColumn('employees', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // EMPLOYEE_CONTACTS
        // ─────────────────────────────────────────
        Schema::table('employee_contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_contacts', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->after('id');
            }
            if (! Schema::hasColumn('employee_contacts', 'phone')) {
                $table->string('phone', 20)->nullable()->after('employee_id');
            }
            if (! Schema::hasColumn('employee_contacts', 'emergency_phone')) {
                $table->string('emergency_phone', 20)->nullable()->after('phone');
            }
            if (! Schema::hasColumn('employee_contacts', 'email')) {
                $table->string('email')->nullable()->after('emergency_phone');
            }
            if (! Schema::hasColumn('employee_contacts', 'current_address')) {
                $table->text('current_address')->nullable()->after('email');
            }
            if (! Schema::hasColumn('employee_contacts', 'permanent_address')) {
                $table->text('permanent_address')->nullable()->after('current_address');
            }
        });

        // ─────────────────────────────────────────
        // EMPLOYEE_DOCUMENTS
        // ─────────────────────────────────────────
        Schema::table('employee_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_documents', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->after('id');
            }
            if (! Schema::hasColumn('employee_documents', 'document_type')) {
                $table->string('document_type')->after('employee_id');
            }
            if (! Schema::hasColumn('employee_documents', 'document_path')) {
                $table->string('document_path')->after('document_type');
            }
        });

        // ─────────────────────────────────────────
        // EMPLOYEE_LEAVES
        // ─────────────────────────────────────────
        Schema::table('employee_leaves', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_leaves', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->after('id');
            }
            if (! Schema::hasColumn('employee_leaves', 'leave_type_id')) {
                $table->unsignedBigInteger('leave_type_id')->after('employee_id');
            }
            if (! Schema::hasColumn('employee_leaves', 'start_date')) {
                $table->date('start_date')->after('leave_type_id');
            }
            if (! Schema::hasColumn('employee_leaves', 'end_date')) {
                $table->date('end_date')->after('start_date');
            }
            if (! Schema::hasColumn('employee_leaves', 'reason')) {
                $table->text('reason')->nullable()->after('end_date');
            }
            if (! Schema::hasColumn('employee_leaves', 'status')) {
                $table->string('status')->default('pending')->after('reason');
            }
            if (! Schema::hasColumn('employee_leaves', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            }
        });

        // ─────────────────────────────────────────
        // EMPLOYEE_ATTENDANCES
        // ─────────────────────────────────────────
        Schema::table('employee_attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('employee_attendances', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->after('id');
            }
            if (! Schema::hasColumn('employee_attendances', 'attendance_date')) {
                $table->date('attendance_date')->after('employee_id');
            }
            if (! Schema::hasColumn('employee_attendances', 'status')) {
                $table->string('status')->default('present')->after('attendance_date');
            }
            if (! Schema::hasColumn('employee_attendances', 'in_time')) {
                $table->time('in_time')->nullable()->after('status');
            }
            if (! Schema::hasColumn('employee_attendances', 'out_time')) {
                $table->time('out_time')->nullable()->after('in_time');
            }
            if (! Schema::hasColumn('employee_attendances', 'remarks')) {
                $table->text('remarks')->nullable()->after('out_time');
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Employees
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('employees', 'employees_user_id_index', 'user_id');
        $this->addIndexIfMissing('employees', 'employees_department_id_index', 'employee_department_id');
        $this->addIndexIfMissing('employees', 'employees_position_id_index', 'employee_position_id');
        $this->addIndexIfMissing('employees', 'employees_status_id_index', 'employee_status_id');
        $this->addIndexIfMissing('employee_contacts', 'ec_employee_id_unique', 'employee_id');
        $this->addIndexIfMissing('employee_documents', 'ed_employee_id_index', 'employee_id');
        $this->addIndexIfMissing('employee_leaves', 'el_employee_id_index', 'employee_id');
        $this->addIndexIfMissing('employee_leaves', 'el_leave_type_id_index', 'leave_type_id');
        $this->addIndexIfMissing('employee_attendances', 'ea_employee_id_index', 'employee_id');
        $this->addIndexIfMissing('employee_attendances', 'ea_attendance_date_index', 'attendance_date');

        // UNIQUE on employee_contacts.employee_id (one contact record per employee)
        $this->addUniqueIfMissing('employee_contacts', 'employee_contacts_employee_id_unique', 'employee_id');
    }

    public function down(): void {}

    private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);

        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $tbl) use ($column, $indexName) {
                $tbl->index($column, $indexName);
            });
        }
    }

    private function addUniqueIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);

        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $tbl) use ($column, $indexName) {
                $tbl->unique($column, $indexName);
            });
        }
    }
};
