<?php

namespace App\Models\Finance;

use App\Models\Core\AcademicYear;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'student_invoices';

    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'total_amount',
        'discount_amount',
        'net_amount',
        'status',
        'due_date',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'fee_structure_id' => 'integer',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function fee_structure()
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id');
    }

    public function academic_year()
    {
        return $this->hasOneThrough(
            AcademicYear::class,
            FeeStructure::class,
            'id',
            'id',
            'fee_structure_id',
            'academic_year_id'
        );
    }

    public function academicYear()
    {
        return $this->academic_year();
    }

    public function feeStructure()
    {
        return $this->fee_structure();
    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class, 'student_invoice_id');
    }

    public function invoiceDetails()
    {
        return $this->invoice_details();
    }

    public function student_payments()
    {
        return $this->hasMany(StudentPayment::class, 'student_invoice_id');
    }

    public function studentPayments()
    {
        return $this->student_payments();
    }

    public function payments()
    {
        return $this->student_payments();
    }

    /**
     * Dynamic total paid from non-deleted student payments.
     */
    public function getTotalPaidAttribute(): float
    {
        return (float) ($this->student_payments()->sum('amount') ?? 0.0);
    }

    /**
     * Dynamic outstanding balance.
     */
    public function getBalanceAttribute(): float
    {
        $net = (float) $this->net_amount;
        $paid = $this->total_paid;

        return max(0.0, round($net - $paid, 2));
    }

    /**
     * Formatted display invoice number.
     */
    public function getInvoiceNumberAttribute(): string
    {
        return '#INV-'.$this->id;
    }

    /**
     * Recalculate and persist invoice status based on non-deleted payments.
     */
    public function recalculateStatus(): void
    {
        if ($this->status === 'cancelled') {
            return;
        }

        $paid = (float) $this->student_payments()->sum('amount');
        $net = (float) $this->net_amount;

        if ($net > 0 && $paid >= $net) {
            $newStatus = 'paid';
        } elseif ($paid > 0) {
            $newStatus = 'partial';
        } else {
            $newStatus = 'unpaid';
        }

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }
}
