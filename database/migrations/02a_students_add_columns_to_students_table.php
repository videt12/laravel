<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'school_id')) {
                $table->unsignedBigInteger('school_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('students', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('school_id');
            }
            if (! Schema::hasColumn('students', 'admission_number')) {
                $table->string('admission_number')->nullable()->unique()->after('user_id');
            }
            if (! Schema::hasColumn('students', 'admission_date')) {
                $table->date('admission_date')->nullable()->after('admission_number');
            }
            if (! Schema::hasColumn('students', 'student_status_id')) {
                $table->unsignedBigInteger('student_status_id')->nullable()->after('admission_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumnIfExists(['school_id', 'user_id', 'admission_number', 'admission_date', 'student_status_id']);
        });
    }
};
