<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolShift extends Model
{
    use HasFactory;

    protected $table = 'school_shifts';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function time_slots()
    {
        return $this->hasMany(TimeSlot::class, 'school_shift_id');
    }

    /**
     * Get the formatted Khmer display name for the shift.
     */
    public function getKhmerNameAttribute(): string
    {
        $name = trim($this->name ?? '');
        $lower = mb_strtolower($name);

        if ($name === 'ព្រឹក' || $name === 'រសៀល' || $name === 'ក្រៅម៉ោង') {
            return $name;
        }

        if (str_contains($lower, 'ក្រៅម៉ោង') || str_contains($lower, 'part')) {
            return 'ក្រៅម៉ោង';
        }
        if (str_contains($lower, 'រសៀល') || str_contains($lower, 'afternoon')) {
            return 'រសៀល';
        }
        if (str_contains($lower, 'ព្រឹក') || str_contains($lower, 'morning')) {
            return 'ព្រឹក';
        }

        return $name;
    }
}
