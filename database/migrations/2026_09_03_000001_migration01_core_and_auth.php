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
        // USERS — add status + soft deletes
        // ─────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('remember_token');
            }
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // ─────────────────────────────────────────
        // ROLES
        // ─────────────────────────────────────────
        Schema::table('roles', function (Blueprint $table) {
            if (! Schema::hasColumn('roles', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('roles', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // PERMISSIONS
        // ─────────────────────────────────────────
        Schema::table('permissions', function (Blueprint $table) {
            if (! Schema::hasColumn('permissions', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('permissions', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // USER_ROLES
        // ─────────────────────────────────────────
        Schema::table('user_roles', function (Blueprint $table) {
            if (! Schema::hasColumn('user_roles', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
            }
            if (! Schema::hasColumn('user_roles', 'role_id')) {
                $table->unsignedBigInteger('role_id')->after('user_id');
            }
        });

        // ─────────────────────────────────────────
        // ROLE_PERMISSIONS
        // ─────────────────────────────────────────
        Schema::table('role_permissions', function (Blueprint $table) {
            if (! Schema::hasColumn('role_permissions', 'role_id')) {
                $table->unsignedBigInteger('role_id')->after('id');
            }
            if (! Schema::hasColumn('role_permissions', 'permission_id')) {
                $table->unsignedBigInteger('permission_id')->after('role_id');
            }
        });

        // ─────────────────────────────────────────
        // LOGIN_ACTIVITIES
        // ─────────────────────────────────────────
        Schema::table('login_activities', function (Blueprint $table) {
            if (! Schema::hasColumn('login_activities', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('login_activities', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('login_activities', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
            if (! Schema::hasColumn('login_activities', 'login_time')) {
                $table->timestamp('login_time')->nullable()->after('user_agent');
            }
        });

        // ─────────────────────────────────────────
        // SCHOOLS
        // ─────────────────────────────────────────
        Schema::table('schools', function (Blueprint $table) {
            if (! Schema::hasColumn('schools', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('schools', 'address')) {
                $table->text('address')->nullable()->after('name');
            }
            if (! Schema::hasColumn('schools', 'phone')) {
                $table->string('phone', 20)->nullable()->after('address');
            }
            if (! Schema::hasColumn('schools', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('schools', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
            if (! Schema::hasColumn('schools', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('website');
            }
            if (! Schema::hasColumn('schools', 'status')) {
                $table->string('status')->default('active')->after('logo_path');
            }
            if (! Schema::hasColumn('schools', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // SCHOOL_BRANCHES
        // ─────────────────────────────────────────
        Schema::table('school_branches', function (Blueprint $table) {
            if (! Schema::hasColumn('school_branches', 'school_id')) {
                $table->unsignedBigInteger('school_id')->after('id');
            }
            if (! Schema::hasColumn('school_branches', 'name')) {
                $table->string('name')->after('school_id');
            }
            if (! Schema::hasColumn('school_branches', 'address')) {
                $table->text('address')->nullable()->after('name');
            }
            if (! Schema::hasColumn('school_branches', 'contact_number')) {
                $table->string('contact_number', 20)->nullable()->after('address');
            }
            if (! Schema::hasColumn('school_branches', 'status')) {
                $table->string('status')->default('active')->after('contact_number');
            }
            if (! Schema::hasColumn('school_branches', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // SCHOOL_SETTINGS
        // ─────────────────────────────────────────
        Schema::table('school_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('school_settings', 'school_id')) {
                $table->unsignedBigInteger('school_id')->after('id');
            }
            if (! Schema::hasColumn('school_settings', 'setting_key')) {
                $table->string('setting_key')->after('school_id');
            }
            if (! Schema::hasColumn('school_settings', 'setting_value')) {
                $table->text('setting_value')->nullable()->after('setting_key');
            }
        });

        // ─────────────────────────────────────────
        // SCHOOL_SHIFTS
        // ─────────────────────────────────────────
        Schema::table('school_shifts', function (Blueprint $table) {
            if (! Schema::hasColumn('school_shifts', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('school_shifts', 'start_time')) {
                $table->time('start_time')->after('name');
            }
            if (! Schema::hasColumn('school_shifts', 'end_time')) {
                $table->time('end_time')->after('start_time');
            }
        });

        // ─────────────────────────────────────────
        // ACADEMIC_YEARS
        // ─────────────────────────────────────────
        Schema::table('academic_years', function (Blueprint $table) {
            if (! Schema::hasColumn('academic_years', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('academic_years', 'start_date')) {
                $table->date('start_date')->after('name');
            }
            if (! Schema::hasColumn('academic_years', 'end_date')) {
                $table->date('end_date')->after('start_date');
            }
            if (! Schema::hasColumn('academic_years', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('end_date');
            }
            if (! Schema::hasColumn('academic_years', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // SCHOOL_TERMS
        // ─────────────────────────────────────────
        Schema::table('school_terms', function (Blueprint $table) {
            if (! Schema::hasColumn('school_terms', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('id');
            }
            if (! Schema::hasColumn('school_terms', 'name')) {
                $table->string('name')->after('academic_year_id');
            }
            if (! Schema::hasColumn('school_terms', 'start_date')) {
                $table->date('start_date')->after('name');
            }
            if (! Schema::hasColumn('school_terms', 'end_date')) {
                $table->date('end_date')->after('start_date');
            }
        });

        // ─────────────────────────────────────────
        // HOLIDAYS
        // ─────────────────────────────────────────
        Schema::table('holidays', function (Blueprint $table) {
            if (! Schema::hasColumn('holidays', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('holidays', 'name')) {
                $table->string('name')->after('academic_year_id');
            }
            if (! Schema::hasColumn('holidays', 'start_date')) {
                $table->date('start_date')->after('name');
            }
            if (! Schema::hasColumn('holidays', 'end_date')) {
                $table->date('end_date')->after('start_date');
            }
        });

        // ─────────────────────────────────────────
        // TIME_SLOTS
        // ─────────────────────────────────────────
        Schema::table('time_slots', function (Blueprint $table) {
            if (! Schema::hasColumn('time_slots', 'school_shift_id')) {
                $table->unsignedBigInteger('school_shift_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('time_slots', 'start_time')) {
                $table->time('start_time')->after('school_shift_id');
            }
            if (! Schema::hasColumn('time_slots', 'end_time')) {
                $table->time('end_time')->after('start_time');
            }
            if (! Schema::hasColumn('time_slots', 'is_break')) {
                $table->boolean('is_break')->default(false)->after('end_time');
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Core + Auth
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('user_roles', 'user_roles_user_id_index', 'user_id');
        $this->addIndexIfMissing('user_roles', 'user_roles_role_id_index', 'role_id');
        $this->addIndexIfMissing('role_permissions', 'role_permissions_role_id_index', 'role_id');
        $this->addIndexIfMissing('login_activities', 'login_activities_user_id_index', 'user_id');
        $this->addIndexIfMissing('school_settings', 'school_settings_school_id_index', 'school_id');
        $this->addIndexIfMissing('school_terms', 'school_terms_academic_year_id_index', 'academic_year_id');
        $this->addIndexIfMissing('holidays', 'holidays_academic_year_id_index', 'academic_year_id');
        $this->addIndexIfMissing('time_slots', 'time_slots_school_shift_id_index', 'school_shift_id');
    }

    public function down(): void
    {
        // Reversible per column if needed — additive only, data-safe
    }

    private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        if (DB::getDriverName() !== 'mysql') {
            try {
                if (Schema::hasColumn($table, $column) && !Schema::hasIndex($table, $indexName)) {
                    Schema::table($table, function (Blueprint $tbl) use ($column, $indexName) {
                        $tbl->index($column, $indexName);
                    });
                }
            } catch (\Throwable $e) {}
            return;
        }

        $exists = DB::select('
            SELECT COUNT(*) as cnt
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND INDEX_NAME = ?
        ', [$table, $indexName]);

        if (($exists[0]->cnt ?? 0) == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, function (Blueprint $tbl) use ($column, $indexName) {
                $tbl->index($column, $indexName);
            });
        }
    }
};
