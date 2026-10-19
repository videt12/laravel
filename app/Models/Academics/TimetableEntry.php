<?php

namespace App\Models\Academics;

use App\Models\Core\TimeSlot;
use App\Models\Employees\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    use HasFactory;

    protected $table = 'timetable_entries';

    protected $fillable = [
        'timetable_id',
        'time_slot_id',
        'day_of_week',
        'subject_id',
        'teacher_id',
        'classroom_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'timetable_id' => 'integer',
        'time_slot_id' => 'integer',
        'day_of_week' => 'integer',
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
        'classroom_id' => 'integer',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id');
    }

    public function time_slot()
    {
        return $this->belongsTo(TimeSlot::class, 'time_slot_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Employee::class, 'teacher_id');
    }
}
