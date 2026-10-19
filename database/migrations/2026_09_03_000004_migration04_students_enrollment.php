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
        // STUDENT_STATUSES  (lookup — no FK deps)
        // ─────────────────────────────────────────
        Schema::table('student_statuses', function (Blueprint $table) {
            if (! Schema::hasColumn('student_statuses', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('student_statuses', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_NOTES
        // (Decision 1 — Option A: add student_id + note)
        // ─────────────────────────────────────────
        Schema::table('student_notes', function (Blueprint $table) {
            if (! Schema::hasColumn('student_notes', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
            }
            if (! Schema::hasColumn('student_notes', 'note')) {
                $table->text('note')->after('student_id');
            }
        });

        // ─────────────────────────────────────────
        // GUARDIANS
        // ─────────────────────────────────────────
        Schema::table('guardians', function (Blueprint $table) {
            if (! Schema::hasColumn('guardians', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('guardians', 'occupation')) {
                $table->string('occupation')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('guardians', 'income')) {
                $table->decimal('income', 12, 2)->nullable()->after('occupation');
            }
            if (! Schema::hasColumn('guardians', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // GUARDIAN_CONTACTS
        // ─────────────────────────────────────────
        Schema::table('guardian_contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('guardian_contacts', 'guardian_id')) {
                $table->unsignedBigInteger('guardian_id')->after('id');
            }
            if (! Schema::hasColumn('guardian_contacts', 'phone')) {
                $table->string('phone', 20)->nullable()->after('guardian_id');
            }
            if (! Schema::hasColumn('guardian_contacts', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_GUARDIANS (pivot enriched)
        // ─────────────────────────────────────────
        Schema::table('student_guardians', function (Blueprint $table) {
            if (! Schema::hasColumn('student_guardians', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
            }
            if (! Schema::hasColumn('student_guardians', 'guardian_id')) {
                $table->unsignedBigInteger('guardian_id')->after('student_id');
            }
            if (! Schema::hasColumn('student_guardians', 'relationship')) {
                $table->string('relationship')->nullable()->after('guardian_id');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_DOCUMENTS — add missing columns
        // ─────────────────────────────────────────
        Schema::table('student_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('student_documents', 'document_type')) {
                $table->string('document_type')->after('student_id');
            }
            if (! Schema::hasColumn('student_documents', 'document_path')) {
                $table->string('document_path')->after('document_type');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_PROFILES — UNIQUE + extra columns check
        // (Decision 3 — safe: 0 duplicates confirmed)
        // ─────────────────────────────────────────
        Schema::table('student_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('student_profiles', 'blood_group')) {
                $table->string('blood_group', 10)->nullable()->after('gender');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_MEDICAL_RECORDS — UNIQUE + extra columns check
        // (Decision 3 — safe: 0 duplicates confirmed)
        // ─────────────────────────────────────────
        Schema::table('student_medical_records', function (Blueprint $table) {
            if (! Schema::hasColumn('student_medical_records', 'chronic_diseases')) {
                $table->text('chronic_diseases')->nullable()->after('allergies');
            }
            if (! Schema::hasColumn('student_medical_records', 'regular_medications')) {
                $table->text('regular_medications')->nullable()->after('chronic_diseases');
            }
        });

        // ─────────────────────────────────────────
        // ENROLLMENTS
        // ─────────────────────────────────────────
        Schema::table('enrollments', function (Blueprint $table) {
            if (! Schema::hasColumn('enrollments', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
            }
            if (! Schema::hasColumn('enrollments', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('student_id');
            }
            if (! Schema::hasColumn('enrollments', 'enrollment_date')) {
                $table->date('enrollment_date')->nullable()->after('academic_year_id');
            }
            if (! Schema::hasColumn('enrollments', 'status')) {
                $table->string('status')->default('active')->after('enrollment_date');
            }
            if (! Schema::hasColumn('enrollments', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // ENROLLMENT_DETAILS
        // ─────────────────────────────────────────
        Schema::table('enrollment_details', function (Blueprint $table) {
            if (! Schema::hasColumn('enrollment_details', 'enrollment_id')) {
                $table->unsignedBigInteger('enrollment_id')->after('id');
            }
            if (! Schema::hasColumn('enrollment_details', 'class_id')) {
                $table->unsignedBigInteger('class_id')->after('enrollment_id');
            }
            if (! Schema::hasColumn('enrollment_details', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            }
            if (! Schema::hasColumn('enrollment_details', 'roll_number')) {
                $table->string('roll_number', 20)->nullable()->after('section_id');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_PROMOTIONS
        // ─────────────────────────────────────────
        Schema::table('student_promotions', function (Blueprint $table) {
            if (! Schema::hasColumn('student_promotions', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
            }
            if (! Schema::hasColumn('student_promotions', 'from_academic_year_id')) {
                $table->unsignedBigInteger('from_academic_year_id')->after('student_id');
            }
            if (! Schema::hasColumn('student_promotions', 'to_academic_year_id')) {
                $table->unsignedBigInteger('to_academic_year_id')->after('from_academic_year_id');
            }
            if (! Schema::hasColumn('student_promotions', 'from_class_id')) {
                $table->unsignedBigInteger('from_class_id')->after('to_academic_year_id');
            }
            if (! Schema::hasColumn('student_promotions', 'to_class_id')) {
                $table->unsignedBigInteger('to_class_id')->after('from_class_id');
            }
            if (! Schema::hasColumn('student_promotions', 'promotion_date')) {
                $table->date('promotion_date')->nullable()->after('to_class_id');
            }
            if (! Schema::hasColumn('student_promotions', 'status')) {
                $table->string('status')->default('promoted')->after('promotion_date');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_TRANSFERS
        // ─────────────────────────────────────────
        Schema::table('student_transfers', function (Blueprint $table) {
            if (! Schema::hasColumn('student_transfers', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
            }
            if (! Schema::hasColumn('student_transfers', 'from_school_id')) {
                $table->unsignedBigInteger('from_school_id')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_transfers', 'to_school_id')) {
                $table->unsignedBigInteger('to_school_id')->nullable()->after('from_school_id');
            }
            if (! Schema::hasColumn('student_transfers', 'transfer_date')) {
                $table->date('transfer_date')->nullable()->after('to_school_id');
            }
            if (! Schema::hasColumn('student_transfers', 'reason')) {
                $table->text('reason')->nullable()->after('transfer_date');
            }
            if (! Schema::hasColumn('student_transfers', 'status')) {
                $table->string('status')->default('pending')->after('reason');
            }
        });

        // ─────────────────────────────────────────
        // WITHDRAWALS
        // ─────────────────────────────────────────
        Schema::table('withdrawals', function (Blueprint $table) {
            if (! Schema::hasColumn('withdrawals', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
            }
            if (! Schema::hasColumn('withdrawals', 'withdrawal_date')) {
                $table->date('withdrawal_date')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('withdrawals', 'reason')) {
                $table->text('reason')->nullable()->after('withdrawal_date');
            }
            if (! Schema::hasColumn('withdrawals', 'status')) {
                $table->string('status')->default('pending')->after('reason');
            }
            if (! Schema::hasColumn('withdrawals', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Students + Enrollment
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('student_notes', 'sn_student_id_index', 'student_id');
        $this->addIndexIfMissing('guardian_contacts', 'gc_guardian_id_index', 'guardian_id');
        $this->addIndexIfMissing('student_guardians', 'sg_student_id_index', 'student_id');
        $this->addIndexIfMissing('student_guardians', 'sg_guardian_id_index', 'guardian_id');
        $this->addIndexIfMissing('student_documents', 'sdoc_student_id_index', 'student_id');
        $this->addIndexIfMissing('enrollments', 'enr_student_id_index', 'student_id');
        $this->addIndexIfMissing('enrollments', 'enr_academic_year_id_index', 'academic_year_id');
        $this->addIndexIfMissing('enrollment_details', 'ed_enrollment_id_index', 'enrollment_id');
        $this->addIndexIfMissing('student_promotions', 'sp_student_id_index', 'student_id');
        $this->addIndexIfMissing('student_transfers', 'st_student_id_index', 'student_id');
        $this->addIndexIfMissing('withdrawals', 'wd_student_id_index', 'student_id');

        // UNIQUE constraints (Decision 3 — all duplicate checks passed SAFE)
        $this->addUniqueIfMissing('student_profiles', 'sp_student_id_unique', 'student_id');
        $this->addUniqueIfMissing('student_medical_records', 'smr_student_id_unique', 'student_id');
        $this->addUniqueIfMissing('enrollment_details', 'end_enrollment_id_unique', 'enrollment_id');
    }

    public function down(): void {}

        private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, $column) && !\Illuminate\Support\Facades\Schema::hasIndex($table, $indexName)) {
                    \Illuminate\Support\Facades\Schema::table($table, fn ($tbl) => $tbl->index($column, $indexName));
                }
            } catch (\Throwable $e) {}
            return;
        }

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
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, $column) && !\Illuminate\Support\Facades\Schema::hasIndex($table, $indexName)) {
                    \Illuminate\Support\Facades\Schema::table($table, fn ($tbl) => $tbl->unique($column, $indexName));
                }
            } catch (\Throwable $e) {}
            return;
        }

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
