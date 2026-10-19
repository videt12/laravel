<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_guardians', function (Blueprint $table) {
            if (! Schema::hasColumn('student_guardians', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('student_guardians', 'guardian_id')) {
                $table->unsignedBigInteger('guardian_id')->nullable()->after('student_id');
            }
            if (! Schema::hasColumn('student_guardians', 'relationship')) {
                $table->string('relationship', 50)->nullable()->after('guardian_id');
            }
        });

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
                $table->softDeletes()->after('updated_at');
            }
        });

        Schema::table('guardian_contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('guardian_contacts', 'guardian_id')) {
                $table->unsignedBigInteger('guardian_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('guardian_contacts', 'phone')) {
                $table->string('phone', 30)->nullable()->after('guardian_id');
            }
            if (! Schema::hasColumn('guardian_contacts', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_guardians', function (Blueprint $table) {
            $table->dropColumnIfExists(['student_id', 'guardian_id', 'relationship']);
        });
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropColumnIfExists(['user_id', 'occupation', 'income', 'deleted_at']);
        });
        Schema::table('guardian_contacts', function (Blueprint $table) {
            $table->dropColumnIfExists(['guardian_id', 'phone', 'email']);
        });
    }
};
