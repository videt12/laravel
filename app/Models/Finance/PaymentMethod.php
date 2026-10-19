<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function student_payments()
    {
        return $this->hasMany(StudentPayment::class, 'payment_method_id');
    }
}
