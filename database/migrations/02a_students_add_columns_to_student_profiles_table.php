<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('student_profiles', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('student_profiles', 'dob')) {
                $table->date('dob')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_profiles', 'gender')) {
                $table->string('gender', 20)->nullable()->after('dob');
            }
            if (! Schema::hasColumn('student_profiles', 'blood_group')) {
                $table->string('blood_group', 10)->nullable()->after('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumnIfExists(['student_id', 'dob', 'gender', 'blood_group']);
        });
    }
};
