<?php

namespace App\Models\Core;

use App\Models\Academics\TimetableEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $table = 'time_slots';

    protected $fillable = [
        'school_shift_id',
        'start_time',
        'end_time',
        'is_break',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_shift_id' => 'integer',
        'is_break' => 'integer',
    ];

    public function school_shift()
    {
        return $this->belongsTo(SchoolShift::class, 'school_shift_id');
    }

    public function timetable_entries()
    {
        return $this->hasMany(TimetableEntry::class, 'time_slot_id');
    }
}
