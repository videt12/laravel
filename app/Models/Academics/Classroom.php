<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classrooms';

    protected $fillable = [
        'name',
        'building',
        'capacity',
    ];

    protected $casts = [
        'id' => 'integer',
        'capacity' => 'integer',
    ];

    public function timetable_entries()
    {
        return $this->hasMany(TimetableEntry::class, 'classroom_id');
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'classroom_id');
    }
}
