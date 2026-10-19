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
        // FEE_CATEGORIES  (lookup — no FK deps)
        // ─────────────────────────────────────────
        Schema::table('fee_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('fee_categories', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('fee_categories', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // EXPENSE_CATEGORIES  (lookup — no FK deps)
        // ─────────────────────────────────────────
        Schema::table('expense_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('expense_categories', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('expense_categories', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // PAYMENT_METHODS  (lookup — no FK deps)
        // ─────────────────────────────────────────
        Schema::table('payment_methods', function (Blueprint $table) {
            if (! Schema::hasColumn('payment_methods', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('payment_methods', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // FEE_STRUCTURES  (depends on fee_categories, academic_years, school_classes)
        // ─────────────────────────────────────────
        Schema::table('fee_structures', function (Blueprint $table) {
            if (! Schema::hasColumn('fee_structures', 'fee_category_id')) {
                $table->unsignedBigInteger('fee_category_id')->after('id');
            }
            if (! Schema::hasColumn('fee_structures', 'academic_year_id')) {
                $table->unsignedBigInteger('academic_year_id')->after('fee_category_id');
            }
            if (! Schema::hasColumn('fee_structures', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable()->after('academic_year_id');
            }
            if (! Schema::hasColumn('fee_structures', 'amount')) {
                $table->decimal('amount', 12, 2)->after('class_id');
            }
        });

        // ─────────────────────────────────────────
        // SCHOOL_EXPENSES  (depends on expense_categories)
        // ─────────────────────────────────────────
        Schema::table('school_expenses', function (Blueprint $table) {
            if (! Schema::hasColumn('school_expenses', 'expense_category_id')) {
                $table->unsignedBigInteger('expense_category_id')->after('id');
            }
            if (! Schema::hasColumn('school_expenses', 'amount')) {
                $table->decimal('amount', 12, 2)->after('expense_category_id');
            }
            if (! Schema::hasColumn('school_expenses', 'expense_date')) {
                $table->date('expense_date')->after('amount');
            }
            if (! Schema::hasColumn('school_expenses', 'description')) {
                $table->text('description')->nullable()->after('expense_date');
            }
            if (! Schema::hasColumn('school_expenses', 'receipt_path')) {
                $table->string('receipt_path')->nullable()->after('description');
            }
            if (! Schema::hasColumn('school_expenses', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_INVOICES  (depends on students, fee_structures)
        // ─────────────────────────────────────────
        Schema::table('student_invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('student_invoices', 'student_id')) {
                $table->unsignedBigInteger('student_id')->after('id');
            }
            if (! Schema::hasColumn('student_invoices', 'fee_structure_id')) {
                $table->unsignedBigInteger('fee_structure_id')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_invoices', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->after('fee_structure_id');
            }
            if (! Schema::hasColumn('student_invoices', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('total_amount');
            }
            if (! Schema::hasColumn('student_invoices', 'net_amount')) {
                $table->decimal('net_amount', 12, 2)->after('discount_amount');
            }
            if (! Schema::hasColumn('student_invoices', 'status')) {
                $table->string('status')->default('unpaid')->after('net_amount');
            }
            if (! Schema::hasColumn('student_invoices', 'due_date')) {
                $table->date('due_date')->nullable()->after('status');
            }
            if (! Schema::hasColumn('student_invoices', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // INVOICE_DETAILS  (depends on student_invoices, fee_categories)
        // ─────────────────────────────────────────
        Schema::table('invoice_details', function (Blueprint $table) {
            if (! Schema::hasColumn('invoice_details', 'student_invoice_id')) {
                $table->unsignedBigInteger('student_invoice_id')->after('id');
            }
            if (! Schema::hasColumn('invoice_details', 'fee_category_id')) {
                $table->unsignedBigInteger('fee_category_id')->after('student_invoice_id');
            }
            if (! Schema::hasColumn('invoice_details', 'amount')) {
                $table->decimal('amount', 12, 2)->after('fee_category_id');
            }
        });

        // ─────────────────────────────────────────
        // STUDENT_PAYMENTS  (depends on student_invoices, payment_methods)
        // (Decision 4: transaction_id = VARCHAR(100), NOT a FK)
        // ─────────────────────────────────────────
        Schema::table('student_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('student_payments', 'student_invoice_id')) {
                $table->unsignedBigInteger('student_invoice_id')->after('id');
            }
            if (! Schema::hasColumn('student_payments', 'payment_method_id')) {
                $table->unsignedBigInteger('payment_method_id')->nullable()->after('student_invoice_id');
            }
            if (! Schema::hasColumn('student_payments', 'amount')) {
                $table->decimal('amount', 12, 2)->after('payment_method_id');
            }
            if (! Schema::hasColumn('student_payments', 'payment_date')) {
                $table->date('payment_date')->after('amount');
            }
            if (! Schema::hasColumn('student_payments', 'transaction_id')) {
                // Decision 4: VARCHAR(100), plain text identifier — NOT a foreign key
                $table->string('transaction_id', 100)->nullable()->after('payment_date');
            }
            if (! Schema::hasColumn('student_payments', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Finance
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('fee_structures', 'fs_fee_category_id_index', 'fee_category_id');
        $this->addIndexIfMissing('fee_structures', 'fs_academic_year_id_index', 'academic_year_id');
        $this->addIndexIfMissing('school_expenses', 'se_expense_category_id_index', 'expense_category_id');
        $this->addIndexIfMissing('school_expenses', 'se_expense_date_index', 'expense_date');
        $this->addIndexIfMissing('student_invoices', 'si_student_id_index', 'student_id');
        $this->addIndexIfMissing('student_invoices', 'si_status_index', 'status');
        $this->addIndexIfMissing('invoice_details', 'id_student_invoice_id_index', 'student_invoice_id');
        $this->addIndexIfMissing('student_payments', 'pay_student_invoice_id_index', 'student_invoice_id');
        $this->addIndexIfMissing('student_payments', 'pay_payment_date_index', 'payment_date');
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
            Schema::table($table, fn ($t) => $t->index($column, $indexName));
        }
    }
};
