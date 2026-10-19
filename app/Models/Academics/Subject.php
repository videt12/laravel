<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'code',
        'type',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function class_subjects()
    {
        return $this->hasMany(ClassSubject::class, 'subject_id');
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subjects', 'subject_id', 'class_id')->withTimestamps();
    }

    public function timetable_entries()
    {
        return $this->hasMany(TimetableEntry::class, 'subject_id');
    }

    /**
     * Generate the next auto-incremented subject code with prefix 'S' (e.g. S001, S002).
     */
    public static function generateNextCode(): string
    {
        $subjects = static::withTrashed()
            ->whereNotNull('code')
            ->where('code', 'LIKE', 'S%')
            ->pluck('code');

        $maxNum = 0;
        foreach ($subjects as $code) {
            if (preg_match('/^S(\d+)$/i', $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        $nextCode = sprintf('S%03d', $nextNum);

        while (static::withTrashed()->where('code', $nextCode)->exists()) {
            $nextNum++;
            $nextCode = sprintf('S%03d', $nextNum);
        }

        return $nextCode;
    }

    /**
     * Get Khmer translation label for subject type.
     */
    public static function typeLabel(?string $type): string
    {
        return match (strtolower(trim((string) $type))) {
            'core' => 'មុខវិជ្ជាស្នូល (Core)',
            'mandatory' => 'កាតព្វកិច្ច (Mandatory)',
            'elective', 'supplementary', 'optional' => 'ជ្រើសរើស (Elective)',
            'theory' => 'ទ្រឹស្តី (Theory)',
            'practical' => 'ការអនុវត្ត (Practical)',
            default => $type ?: 'មិនបានបញ្ជាក់',
        };
    }

    /**
     * Accessor for Khmer type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return static::typeLabel($this->type);
    }
}
