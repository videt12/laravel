<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('student_documents', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('student_documents', 'document_type')) {
                $table->string('document_type')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_documents', 'document_path')) {
                $table->string('document_path')->nullable()->after('document_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            $table->dropColumnIfExists(['student_id', 'document_type', 'document_path']);
        });
    }
};
