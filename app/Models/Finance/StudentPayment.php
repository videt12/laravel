<?php

namespace App\Models\Finance;

use App\Services\Finance\KhmerNumberSpeller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'student_payments';

    protected $fillable = [
        'student_invoice_id',
        'payment_method_id',
        'amount',
        'payment_date',
        'transaction_id',
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

    protected $casts = [
        'id' => 'integer',
        'student_invoice_id' => 'integer',
        'payment_method_id' => 'integer',
        'amount' => 'decimal:2',
        'amount_khr' => 'decimal:2',
        'payment_date' => 'date',
        'period_months' => 'integer',
        'period_start_date' => 'date',
        'period_end_date' => 'date',
        'next_payment_date' => 'date',
    ];

    public function student_invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'student_invoice_id');
    }

    public function studentInvoice()
    {
        return $this->student_invoice();
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function paymentMethod()
    {
        return $this->payment_method();
    }

    /**
     * Convenient access to student through student_invoice.
     */
    public function getStudentAttribute()
    {
        return $this->student_invoice?->student;
    }

    /**
     * Formatted receipt number for display.
     */
    public function getFormattedReceiptNumberAttribute(): string
    {
        if (! empty($this->receipt_number)) {
            return $this->receipt_number;
        }

        return 'REC-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * KHR amount or auto-converted from USD with default rate 4,100 KHR/USD.
     */
    public function getCalculatedAmountKhrAttribute(): float
    {
        if ($this->amount_khr !== null && (float) $this->amount_khr > 0) {
            return (float) $this->amount_khr;
        }

        return round(((float) $this->amount) * 4100, -2);
    }

    /**
     * Spelled amount in Khmer words.
     */
    public function getSpelledAmountWordsAttribute(): string
    {
        if (! empty($this->amount_in_words)) {
            return $this->amount_in_words;
        }

        return KhmerNumberSpeller::spellCurrency((float) $this->amount, 'USD');
    }

    /**
     * Spelled KHR amount in Khmer words.
     */
    public function getSpelledKhrWordsAttribute(): string
    {
        return KhmerNumberSpeller::spellCurrency($this->calculated_amount_khr, 'KHR');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (StudentPayment $payment) {
            if (empty($payment->receipt_number)) {
                // Generate format BPS-YYYYMM-XXXX
                $prefix = 'BPS-'.now()->format('Ym').'-';
                $latest = static::withTrashed()
                    ->where('receipt_number', 'like', "{$prefix}%")
                    ->orderByDesc('id')
                    ->value('receipt_number');

                $nextSeq = 1;
                if ($latest && preg_match('/-(\d+)$/', $latest, $matches)) {
                    $nextSeq = ((int) $matches[1]) + 1;
                }
                $payment->receipt_number = $prefix.str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
            }

            if (empty($payment->amount_in_words) && $payment->amount > 0) {
                $payment->amount_in_words = KhmerNumberSpeller::spellCurrency((float) $payment->amount, 'USD');
            }

            if (empty($payment->amount_khr) && $payment->amount > 0) {
                $payment->amount_khr = round(((float) $payment->amount) * 4100, -2);
            }
        });

        static::saved(function (StudentPayment $payment) {
            $payment->student_invoice?->recalculateStatus();
        });

        static::deleted(function (StudentPayment $payment) {
            $payment->student_invoice?->recalculateStatus();
        });

        static::restored(function (StudentPayment $payment) {
            $payment->student_invoice?->recalculateStatus();
        });
    }
}
