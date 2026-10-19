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
        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'birth_village')) {
                $table->string('birth_village', 100)->nullable()->after('place_of_birth');
            }
            if (! Schema::hasColumn('employees', 'birth_commune')) {
                $table->string('birth_commune', 100)->nullable()->after('birth_village');
            }
            if (! Schema::hasColumn('employees', 'birth_district')) {
                $table->string('birth_district', 100)->nullable()->after('birth_commune');
            }
            if (! Schema::hasColumn('employees', 'birth_province')) {
                $table->string('birth_province', 100)->nullable()->after('birth_district');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['birth_village', 'birth_commune', 'birth_district', 'birth_province']);
        });
    }
};
