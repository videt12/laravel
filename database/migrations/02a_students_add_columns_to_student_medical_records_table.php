<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_medical_records', function (Blueprint $table) {
            if (! Schema::hasColumn('student_medical_records', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('student_medical_records', 'allergies')) {
                $table->text('allergies')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_medical_records', 'chronic_diseases')) {
                $table->text('chronic_diseases')->nullable()->after('allergies');
            }
            if (! Schema::hasColumn('student_medical_records', 'regular_medications')) {
                $table->text('regular_medications')->nullable()->after('chronic_diseases');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_medical_records', function (Blueprint $table) {
            $table->dropColumnIfExists(['student_id', 'allergies', 'chronic_diseases', 'regular_medications']);
        });
    }
};
