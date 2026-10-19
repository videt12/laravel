<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays';

    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'id' => 'integer',
        'academic_year_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
