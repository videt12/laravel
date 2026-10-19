<?php

namespace App\Models\Academics;

use App\Models\Core\AcademicYear;
use App\Models\Core\SchoolTerm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $table = 'timetables';

    protected $fillable = [
        'class_id',
        'academic_year_id',
        'school_term_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'class_id' => 'integer',
        'academic_year_id' => 'integer',
        'school_term_id' => 'integer',
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function school_term()
    {
        return $this->belongsTo(SchoolTerm::class, 'school_term_id');
    }

    public function timetable_entries()
    {
        return $this->hasMany(TimetableEntry::class, 'timetable_id');
    }
}
