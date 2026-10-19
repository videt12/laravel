<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:clean-test-data', function () {
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('student_payments')->truncate();
    DB::table('invoice_details')->truncate();
    DB::table('student_invoices')->truncate();
    DB::table('school_expenses')->truncate();
    DB::table('fee_structures')->truncate();

    DB::table('payment_methods')->where('id', '>', 7)->delete();
    DB::table('expense_categories')->where('id', '>', 8)->delete();
    DB::table('fee_categories')->whereNotIn('id', [1, 4, 6, 7, 8, 9, 12, 57])->delete();

    DB::table('library_returns')->truncate();
    DB::table('library_issues')->truncate();
    DB::table('library_book_copies')->truncate();
    DB::table('library_books')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $this->info('Successfully deleted all test data in Finance and Library!');
})->purpose('Purge all test data in Finance and Library');
