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
        Schema::table('student_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('student_payments', 'receipt_number')) {
                $table->string('receipt_number', 50)->nullable()->unique()->after('transaction_id');
            }
            if (! Schema::hasColumn('student_payments', 'student_type')) {
                $table->string('student_type', 20)->nullable()->comment('old, new')->after('receipt_number');
            }
            if (! Schema::hasColumn('student_payments', 'shift')) {
                $table->string('shift', 50)->nullable()->comment('morning_7_1030, afternoon_1_400, etc.')->after('student_type');
            }
            if (! Schema::hasColumn('student_payments', 'english_class_time')) {
                $table->string('english_class_time', 100)->nullable()->after('shift');
            }
            if (! Schema::hasColumn('student_payments', 'book_level')) {
                $table->string('book_level', 100)->nullable()->after('english_class_time');
            }
            if (! Schema::hasColumn('student_payments', 'period_type')) {
                $table->string('period_type', 50)->nullable()->comment('month, trimester, semester, year')->after('book_level');
            }
            if (! Schema::hasColumn('student_payments', 'period_months')) {
                $table->unsignedSmallInteger('period_months')->nullable()->after('period_type');
            }
            if (! Schema::hasColumn('student_payments', 'period_start_date')) {
                $table->date('period_start_date')->nullable()->after('period_months');
            }
            if (! Schema::hasColumn('student_payments', 'period_end_date')) {
                $table->date('period_end_date')->nullable()->after('period_start_date');
            }
            if (! Schema::hasColumn('student_payments', 'next_payment_date')) {
                $table->date('next_payment_date')->nullable()->after('period_end_date');
            }
            if (! Schema::hasColumn('student_payments', 'amount_khr')) {
                $table->decimal('amount_khr', 15, 2)->nullable()->after('next_payment_date');
            }
            if (! Schema::hasColumn('student_payments', 'amount_in_words')) {
                $table->string('amount_in_words', 255)->nullable()->after('amount_khr');
            }
            if (! Schema::hasColumn('student_payments', 'receiver_name')) {
                $table->string('receiver_name', 100)->nullable()->after('amount_in_words');
            }
            if (! Schema::hasColumn('student_payments', 'note')) {
                $table->text('note')->nullable()->after('receiver_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_payments', function (Blueprint $table) {
            $columns = [
                'receipt_number',
                'student_type',
                'shift',
                'english_class_time',
                'book_level',
                'period_type',
                'period_months',
                'period_start_date',
                'period_end_date',
                'next_payment_date',
                'amount_khr',
                'amount_in_words',
                'receiver_name',
                'note',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('student_payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
