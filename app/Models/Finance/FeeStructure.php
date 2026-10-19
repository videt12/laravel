<?php

namespace App\Models\Finance;

use App\Models\Academics\SchoolClass;
use App\Models\Core\AcademicYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $table = 'fee_structures';

    protected $fillable = [
        'fee_category_id',
        'academic_year_id',
        'class_id',
        'amount',
    ];

    protected $casts = [
        'id' => 'integer',
        'fee_category_id' => 'integer',
        'academic_year_id' => 'integer',
        'class_id' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function fee_category()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function student_invoices()
    {
        return $this->hasMany(StudentInvoice::class, 'fee_structure_id');
    }
}
