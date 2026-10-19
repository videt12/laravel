<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_details';

    protected $fillable = [
        'student_invoice_id',
        'fee_category_id',
        'amount',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_invoice_id' => 'integer',
        'fee_category_id' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function student_invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'student_invoice_id');
    }

    public function fee_category()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }
}
