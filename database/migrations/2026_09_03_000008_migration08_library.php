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
        // LIBRARY_CATEGORIES  (lookup — no FK deps)
        // ─────────────────────────────────────────
        Schema::table('library_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('library_categories', 'name')) {
                $table->string('name')->after('id');
            }
            if (! Schema::hasColumn('library_categories', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // ─────────────────────────────────────────
        // LIBRARY_BOOKS  (depends on library_categories)
        // ─────────────────────────────────────────
        Schema::table('library_books', function (Blueprint $table) {
            if (! Schema::hasColumn('library_books', 'title')) {
                $table->string('title')->after('id');
            }
            if (! Schema::hasColumn('library_books', 'author')) {
                $table->string('author')->nullable()->after('title');
            }
            if (! Schema::hasColumn('library_books', 'publisher')) {
                $table->string('publisher')->nullable()->after('author');
            }
            if (! Schema::hasColumn('library_books', 'isbn')) {
                $table->string('isbn', 20)->nullable()->after('publisher');
            }
            if (! Schema::hasColumn('library_books', 'library_category_id')) {
                $table->unsignedBigInteger('library_category_id')->nullable()->after('isbn');
            }
            if (! Schema::hasColumn('library_books', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // ─────────────────────────────────────────
        // LIBRARY_BOOK_COPIES  (depends on library_books)
        // ─────────────────────────────────────────
        Schema::table('library_book_copies', function (Blueprint $table) {
            if (! Schema::hasColumn('library_book_copies', 'library_book_id')) {
                $table->unsignedBigInteger('library_book_id')->after('id');
            }
            if (! Schema::hasColumn('library_book_copies', 'accession_number')) {
                $table->string('accession_number', 50)->nullable()->unique()->after('library_book_id');
            }
            if (! Schema::hasColumn('library_book_copies', 'status')) {
                $table->string('status')->default('available')->after('accession_number');
            }
        });

        // ─────────────────────────────────────────
        // LIBRARY_ISSUES  (depends on library_book_copies, users)
        // ─────────────────────────────────────────
        Schema::table('library_issues', function (Blueprint $table) {
            if (! Schema::hasColumn('library_issues', 'library_book_copy_id')) {
                $table->unsignedBigInteger('library_book_copy_id')->after('id');
            }
            if (! Schema::hasColumn('library_issues', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('library_book_copy_id');
            }
            if (! Schema::hasColumn('library_issues', 'issue_date')) {
                $table->date('issue_date')->after('user_id');
            }
            if (! Schema::hasColumn('library_issues', 'due_date')) {
                $table->date('due_date')->nullable()->after('issue_date');
            }
        });

        // ─────────────────────────────────────────
        // LIBRARY_RETURNS  (depends on library_issues)
        // ─────────────────────────────────────────
        Schema::table('library_returns', function (Blueprint $table) {
            if (! Schema::hasColumn('library_returns', 'library_issue_id')) {
                $table->unsignedBigInteger('library_issue_id')->after('id');
            }
            if (! Schema::hasColumn('library_returns', 'return_date')) {
                $table->date('return_date')->after('library_issue_id');
            }
            if (! Schema::hasColumn('library_returns', 'fine_amount')) {
                $table->decimal('fine_amount', 10, 2)->default(0)->after('return_date');
            }
        });

        // ─────────────────────────────────────────
        // INDEXES for Library
        // ─────────────────────────────────────────
        $this->addIndexIfMissing('library_books', 'lb_category_id_index', 'library_category_id');
        $this->addIndexIfMissing('library_book_copies', 'lbc_book_id_index', 'library_book_id');
        $this->addIndexIfMissing('library_book_copies', 'lbc_status_index', 'status');
        $this->addIndexIfMissing('library_issues', 'li_book_copy_id_index', 'library_book_copy_id');
        $this->addIndexIfMissing('library_issues', 'li_user_id_index', 'user_id');
        $this->addIndexIfMissing('library_issues', 'li_issue_date_index', 'issue_date');
        $this->addIndexIfMissing('library_returns', 'lr_issue_id_index', 'library_issue_id');

        // UNIQUE on library_returns.library_issue_id (one return per issue)
        $this->addUniqueIfMissing('library_returns', 'lr_issue_id_unique', 'library_issue_id');
    }

    public function down(): void {}

    private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);
        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, fn ($t) => $t->index($column, $indexName));
        }
    }

    private function addUniqueIfMissing(string $table, string $indexName, string $column): void
    {
        $exists = DB::select('
            SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?
        ', [$table, $indexName]);
        if ($exists[0]->cnt == 0 && Schema::hasColumn($table, $column)) {
            Schema::table($table, fn ($t) => $t->unique($column, $indexName));
        }
    }
};
