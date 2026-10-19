<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolExpense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'school_expenses';

    protected $fillable = [
        'expense_category_id',
        'amount',
        'expense_date',
        'description',
        'receipt_path',
    ];

    protected $casts = [
        'id' => 'integer',
        'expense_category_id' => 'integer',
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
