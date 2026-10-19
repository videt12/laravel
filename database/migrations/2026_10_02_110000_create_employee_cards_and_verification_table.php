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
        if (! Schema::hasTable('employee_cards')) {
            Schema::create('employee_cards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
                $table->string('card_number', 60)->unique();
                $table->string('card_token', 80)->unique()->index();
                $table->text('qr_payload')->nullable();
                $table->string('status', 20)->default('active')->index(); // active, inactive, lost, revoked
                $table->dateTime('issued_at')->nullable();
                $table->dateTime('expires_at')->nullable();
                $table->dateTime('deactivated_at')->nullable();
                $table->string('deactivation_reason', 255)->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('employee_attendances')) {
            Schema::table('employee_attendances', function (Blueprint $table) {
                if (! Schema::hasColumn('employee_attendances', 'card_id')) {
                    $table->foreignId('card_id')->nullable()->after('employee_id')->constrained('employee_cards')->nullOnDelete();
                }
                if (! Schema::hasColumn('employee_attendances', 'card_token')) {
                    $table->string('card_token', 80)->nullable()->after('card_id')->index();
                }
                if (! Schema::hasColumn('employee_attendances', 'verification_method')) {
                    $table->string('verification_method', 50)->nullable()->after('status');
                }
                if (! Schema::hasColumn('employee_attendances', 'verification_score')) {
                    $table->decimal('verification_score', 5, 2)->nullable()->after('verification_method');
                }
                if (! Schema::hasColumn('employee_attendances', 'liveness_verified')) {
                    $table->boolean('liveness_verified')->default(false)->after('verification_score');
                }
                if (! Schema::hasColumn('employee_attendances', 'device_info')) {
                    $table->string('device_info', 255)->nullable()->after('remarks');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('employee_attendances')) {
            Schema::table('employee_attendances', function (Blueprint $table) {
                if (Schema::hasColumn('employee_attendances', 'card_id')) {
                    $table->dropForeign(['card_id']);
                    $table->dropColumn('card_id');
                }
                if (Schema::hasColumn('employee_attendances', 'card_token')) {
                    $table->dropColumn('card_token');
                }
                if (Schema::hasColumn('employee_attendances', 'verification_method')) {
                    $table->dropColumn('verification_method');
                }
                if (Schema::hasColumn('employee_attendances', 'verification_score')) {
                    $table->dropColumn('verification_score');
                }
                if (Schema::hasColumn('employee_attendances', 'liveness_verified')) {
                    $table->dropColumn('liveness_verified');
                }
                if (Schema::hasColumn('employee_attendances', 'device_info')) {
                    $table->dropColumn('device_info');
                }
            });
        }

        Schema::dropIfExists('employee_cards');
    }
};
