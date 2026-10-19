<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    use HasFactory;

    protected $table = 'fee_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function fee_structures()
    {
        return $this->hasMany(FeeStructure::class, 'fee_category_id');
    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class, 'fee_category_id');
    }
}
