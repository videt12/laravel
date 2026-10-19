<?php

namespace App\Models\Core;

use App\Models\Academics\Timetable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTerm extends Model
{
    use HasFactory;

    protected $table = 'school_terms';

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

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'school_term_id');
    }
}
