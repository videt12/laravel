<?php

namespace App\Models\Employees;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EmployeeCard extends Model
{
    use HasFactory;

    protected $table = 'employee_cards';

    protected $fillable = [
        'employee_id',
        'card_number',
        'card_token',
        'qr_payload',
        'status',
        'issued_at',
        'expires_at',
        'deactivated_at',
        'deactivation_reason',
        'created_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'created_by' => 'integer',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::ensureTableExists();
    }

    /**
     * Auto-ensure table and attendance columns exist in database.
     */
    public static function ensureTableExists(): void
    {
        try {
            if (! Schema::hasTable('employee_cards')) {
                Schema::create('employee_cards', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
                    $table->string('card_number', 60)->unique();
                    $table->string('card_token', 80)->unique()->index();
                    $table->text('qr_payload')->nullable();
                    $table->string('status', 20)->default('active')->index();
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
        } catch (\Throwable $e) {
            // Silently continue if schema already migrated or user lacks DDL privileges
        }
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive(): bool
    {
        return strtolower($this->status) === 'active';
    }

    /**
     * Deactivate card (e.g. lost, stolen, or replacement issued).
     */
    public function deactivate(?string $reason = 'Deactivated by Administrator'): void
    {
        $this->update([
            'status' => 'inactive',
            'deactivated_at' => now(),
            'deactivation_reason' => $reason,
        ]);
    }

    /**
     * Reactivate an inactive card.
     */
    public function activate(): void
    {
        // Deactivate other cards for this employee first
        static::where('employee_id', $this->employee_id)
            ->where('id', '!=', $this->id)
            ->update([
                'status' => 'inactive',
                'deactivated_at' => now(),
                'deactivation_reason' => 'Superseded by card #'.$this->card_number,
            ]);

        $this->update([
            'status' => 'active',
            'deactivated_at' => null,
            'deactivation_reason' => null,
        ]);
    }

    /**
     * Generate or re-issue a unique QR card for an employee.
     */
    public static function generateFor(Employee $employee, ?int $userId = null): self
    {
        // Deactivate existing active cards for this employee
        static::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->update([
                'status' => 'inactive',
                'deactivated_at' => now(),
                'deactivation_reason' => 'Superseded by newly re-issued card',
            ]);

        $token = 'BPS_CRD_'.strtoupper(Str::random(24));
        $cardNumber = 'CRD-'.now()->format('Y').'-'.str_pad((string) $employee->id, 5, '0', STR_PAD_LEFT);

        // Append suffix if card number already exists
        $count = static::where('employee_id', $employee->id)->count();
        if ($count > 0) {
            $cardNumber .= '-V'.($count + 1);
        }

        $qrData = [
            'type' => 'bps_staff_card',
            'token' => $token,
            'card_no' => $cardNumber,
            'code' => $employee->employee_code,
            'issued' => now()->toDateString(),
        ];

        return static::create([
            'employee_id' => $employee->id,
            'card_number' => $cardNumber,
            'card_token' => $token,
            'qr_payload' => json_encode($qrData, JSON_UNESCAPED_UNICODE),
            'status' => 'active',
            'issued_at' => now(),
            'created_by' => $userId,
        ]);
    }

    /**
     * Get the active card for employee, or automatically generate one if none exists.
     */
    public static function getOrCreateActiveFor(Employee $employee, ?int $userId = null): self
    {
        $card = static::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if (! $card) {
            $card = static::generateFor($employee, $userId);
        }

        return $card;
    }

    /**
     * Resolve card by scanned QR string (supports token, raw JSON payload, or plain staff code fallback).
     */
    public static function resolveFromScannedCode(string $rawCode): ?self
    {
        $clean = trim($rawCode);
        if (empty($clean)) {
            return null;
        }

        // 1. Try parsing JSON payload
        if (str_starts_with($clean, '{') && str_ends_with($clean, '}')) {
            $decoded = json_decode($clean, true);
            if (is_array($decoded)) {
                $targetToken = $decoded['token'] ?? $decoded['card_token'] ?? null;
                if ($targetToken) {
                    $card = static::where('card_token', $targetToken)->first();
                    if ($card) {
                        return $card;
                    }
                }
            }
        }

        // 2. Direct match on card_token
        $card = static::where('card_token', $clean)->first();
        if ($card) {
            return $card;
        }

        // 3. Match on card_number
        $card = static::where('card_number', $clean)->first();
        if ($card) {
            return $card;
        }

        // 4. Fallback: match by employee_code -> find employee's active card
        $emp = Employee::where('employee_code', $clean)
            ->orWhereRaw('LOWER(employee_code) = ?', [strtolower($clean)])
            ->first();

        if ($emp) {
            return static::getOrCreateActiveFor($emp);
        }

        return null;
    }
}
