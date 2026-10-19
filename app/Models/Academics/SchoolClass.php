<?php

namespace App\Models\Academics;

use App\Models\Core\AcademicYear;
use App\Models\Finance\FeeStructure;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'school_classes';

    protected $fillable = [
        'grade_id',
        'section_id',
        'academic_year_id',
        'classroom_id',
        'capacity',
    ];

    protected $casts = [
        'id' => 'integer',
        'grade_id' => 'integer',
        'section_id' => 'integer',
        'academic_year_id' => 'integer',
        'classroom_id' => 'integer',
        'capacity' => 'integer',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function class_subjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    public function teacher_assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'class_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_class_id');
    }

    public function fee_structures()
    {
        return $this->hasMany(FeeStructure::class, 'class_id');
    }

    public function getNameAttribute(): string
    {
        $gradeName = $this->grade?->name ?? '';
        $sectionName = $this->section?->name ?? '';
        $label = trim("{$gradeName} {$sectionName}");

        return $label !== '' ? $label : "Class #{$this->id}";
    }

    /**
     * Determine shift slug from a name (e.g. section name).
     * Returns: 'morning', 'afternoon', 'evening', or 'all'
     */
    public static function detectShift(?string $name): string
    {
        if (! $name) {
            return 'all';
        }
        $lower = mb_strtolower($name);
        if (str_contains($lower, 'ព្រឹក') || str_contains($lower, 'morning')) {
            return 'morning';
        }
        if (str_contains($lower, 'រសៀល') || str_contains($lower, 'afternoon') || str_contains($lower, 'day') || str_contains($lower, 'ថ្ងៃ')) {
            return 'afternoon';
        }
        if (str_contains($lower, 'យប់') || str_contains($lower, 'evening') || str_contains($lower, 'night') || str_contains($lower, 'ក្រៅម៉ោង')) {
            return 'evening';
        }

        return 'all';
    }

    /**
     * Get Khmer display label for shift.
     */
    public static function getShiftLabel(string $shift): string
    {
        return match ($shift) {
            'morning' => 'ពេលព្រឹក',
            'afternoon' => 'ពេលរសៀល',
            'evening' => 'ពេលយប់/ក្រៅម៉ោង',
            default => 'ពេញមួយថ្ងៃ',
        };
    }

    /**
     * Get current class shift.
     */
    public function getShiftAttribute(): string
    {
        return self::detectShift($this->section?->name);
    }

    /**
     * Get current class shift label.
     */
    public function getShiftLabelAttribute(): string
    {
        return self::getShiftLabel($this->shift);
    }

    /**
     * Find if another class is occupying this room in the same academic year and conflicting shift.
     */
    public static function findRoomConflict(
        int $classroomId,
        int $academicYearId,
        ?int $sectionId = null,
        ?int $excludeClassId = null
    ): ?self {
        $targetSection = $sectionId ? Section::find($sectionId) : null;
        $targetShift = self::detectShift($targetSection?->name);

        $existingClasses = self::query()
            ->with(['grade', 'section'])
            ->where('classroom_id', $classroomId)
            ->where('academic_year_id', $academicYearId)
            ->when($excludeClassId, fn ($q) => $q->where('id', '!=', $excludeClassId))
            ->get();

        foreach ($existingClasses as $existing) {
            $existingShift = self::detectShift($existing->section?->name);

            // Conflicting if same shift OR if either occupies 'all' (unspecified full-day)
            if ($targetShift === 'all' || $existingShift === 'all' || $targetShift === $existingShift) {
                return $existing;
            }
        }

        return null;
    }

    /**
     * Get any class currently conflicting on this class's room.
     */
    public function getConflictingClass(): ?self
    {
        if (! $this->classroom_id || ! $this->academic_year_id) {
            return null;
        }

        return self::findRoomConflict(
            (int) $this->classroom_id,
            (int) $this->academic_year_id,
            (int) $this->section_id,
            $this->id
        );
    }

    /**
     * Batch resolve room conflicts for classes to avoid N+1 queries.
     * Returns array mapping class_id => conflicting SchoolClass.
     *
     * @return array<int, self>
     */
    public static function getRoomConflictMap(int|string|null $academicYearId = null): array
    {
        $query = self::with(['grade', 'section', 'academic_year', 'classroom'])
            ->whereNotNull('classroom_id');

        if ($academicYearId && $academicYearId !== 'all') {
            $query->where('academic_year_id', $academicYearId);
        }

        $classes = $query->get();
        $conflicts = [];

        foreach ($classes as $c1) {
            $shift1 = self::detectShift($c1->section?->name);
            foreach ($classes as $c2) {
                if ($c1->id === $c2->id) {
                    continue;
                }
                if ($c1->academic_year_id !== $c2->academic_year_id || $c1->classroom_id !== $c2->classroom_id) {
                    continue;
                }
                $shift2 = self::detectShift($c2->section?->name);
                if ($shift1 === 'all' || $shift2 === 'all' || $shift1 === $shift2) {
                    $conflicts[$c1->id] = $c2;
                    break;
                }
            }
        }

        return $conflicts;
    }
}
