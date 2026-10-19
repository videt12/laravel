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
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->foreign('academic_year_id', 'fk_fee_structures_academic_year')
                ->references('id')->on('academic_years')->cascadeOnDelete();
            $table->foreign('fee_category_id', 'fk_fee_structures_fee_category')
                ->references('id')->on('fee_categories')->restrictOnDelete();
            $table->foreign('class_id', 'fk_fee_structures_class')
                ->references('id')->on('school_classes')->cascadeOnDelete();
        });

        Schema::table('student_invoices', function (Blueprint $table) {
            $table->foreign('student_id', 'fk_student_invoices_student')
                ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('fee_structure_id', 'fk_student_invoices_fee_structure')
                ->references('id')->on('fee_structures')->restrictOnDelete();
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            $table->foreign('student_invoice_id', 'fk_invoice_details_invoice')
                ->references('id')->on('student_invoices')->cascadeOnDelete();
            $table->foreign('fee_category_id', 'fk_invoice_details_fee_category')
                ->references('id')->on('fee_categories')->restrictOnDelete();
        });

        Schema::table('student_payments', function (Blueprint $table) {
            $table->foreign('student_invoice_id', 'fk_student_payments_invoice')
                ->references('id')->on('student_invoices')->cascadeOnDelete();
            $table->foreign('payment_method_id', 'fk_student_payments_method')
                ->references('id')->on('payment_methods')->restrictOnDelete();
        });

        Schema::table('school_expenses', function (Blueprint $table) {
            $table->foreign('expense_category_id', 'fk_school_expenses_category')
                ->references('id')->on('expense_categories')->restrictOnDelete();
        });

        Schema::table('library_books', function (Blueprint $table) {
            $table->foreign('library_category_id', 'fk_library_books_category')
                ->references('id')->on('library_categories')->restrictOnDelete();
        });

        Schema::table('library_book_copies', function (Blueprint $table) {
            $table->foreign('library_book_id', 'fk_library_book_copies_book')
                ->references('id')->on('library_books')->cascadeOnDelete();
        });

        Schema::table('library_issues', function (Blueprint $table) {
            $table->foreign('library_book_copy_id', 'fk_library_issues_copy')
                ->references('id')->on('library_book_copies')->cascadeOnDelete();
            $table->foreign('user_id', 'fk_library_issues_user')
                ->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('library_returns', function (Blueprint $table) {
            $table->foreign('library_issue_id', 'fk_library_returns_issue')
                ->references('id')->on('library_issues')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library_returns', function (Blueprint $table) {
            $table->dropForeign('fk_library_returns_issue');
        });

        Schema::table('library_issues', function (Blueprint $table) {
            $table->dropForeign('fk_library_issues_copy');
            $table->dropForeign('fk_library_issues_user');
        });

        Schema::table('library_book_copies', function (Blueprint $table) {
            $table->dropForeign('fk_library_book_copies_book');
        });

        Schema::table('library_books', function (Blueprint $table) {
            $table->dropForeign('fk_library_books_category');
        });

        Schema::table('school_expenses', function (Blueprint $table) {
            $table->dropForeign('fk_school_expenses_category');
        });

        Schema::table('student_payments', function (Blueprint $table) {
            $table->dropForeign('fk_student_payments_invoice');
            $table->dropForeign('fk_student_payments_method');
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropForeign('fk_invoice_details_invoice');
            $table->dropForeign('fk_invoice_details_fee_category');
        });

        Schema::table('student_invoices', function (Blueprint $table) {
            $table->dropForeign('fk_student_invoices_student');
            $table->dropForeign('fk_student_invoices_fee_structure');
        });

        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropForeign('fk_fee_structures_academic_year');
            $table->dropForeign('fk_fee_structures_fee_category');
            $table->dropForeign('fk_fee_structures_class');
        });
    }
};
