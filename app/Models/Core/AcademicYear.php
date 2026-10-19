<?php

namespace App\Models\Core;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\TeacherAssignment;
use App\Models\Academics\Timetable;
use App\Models\Enrollment\Enrollment;
use App\Models\Finance\FeeStructure;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'academic_years';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'integer',
    ];

    protected static ?string $configuredSettingYear = null;

    protected static bool $configuredSettingYearLoaded = false;

    protected static bool $hasSynchronizedOnce = false;

    /**
     * Get the configured academic year from SchoolSetting.
     */
    public static function getConfiguredAcademicYear(): ?string
    {
        if (! static::$configuredSettingYearLoaded) {
            try {
                static::$configuredSettingYear = SchoolSetting::where('setting_key', 'academic_year')->value('setting_value');
            } catch (\Throwable $e) {
                static::$configuredSettingYear = null;
            }
            static::$configuredSettingYearLoaded = true;
        }

        return static::$configuredSettingYear;
    }

    /**
     * Reset the cached setting.
     */
    public static function resetConfiguredSettingCache(): void
    {
        static::$configuredSettingYear = null;
        static::$configuredSettingYearLoaded = false;
        static::$hasSynchronizedOnce = false;
    }

    /**
     * Synchronize the academic_years database table and SchoolSetting so only ONE year is active.
     */
    public static function synchronizeActiveYear(?string $targetYear = null): void
    {
        try {
            $yearToActivate = $targetYear ?? static::getConfiguredAcademicYear();

            if (! empty($yearToActivate)) {
                $target = static::where('name', $yearToActivate)
                    ->orWhere('id', $yearToActivate)
                    ->first();

                if ($target) {
                    $activeCount = static::where('is_active', 1)->count();
                    if ($activeCount !== 1 || ! $target->is_active) {
                        static::query()->where('id', '!=', $target->id)->update(['is_active' => 0]);
                        $target->update(['is_active' => 1]);
                    }

                    if ($target->name !== $yearToActivate) {
                        SchoolSetting::updateOrCreate(
                            ['setting_key' => 'academic_year'],
                            ['setting_value' => $target->name]
                        );
                    }

                    static::$configuredSettingYear = $target->name;
                    static::$configuredSettingYearLoaded = true;
                    static::$hasSynchronizedOnce = true;

                    return;
                }
            }

            // Fallback: If no setting is configured or target year doesn't exist, ensure only one year is active
            $activeCount = static::where('is_active', 1)->count();
            if ($activeCount > 1) {
                $primary = static::where('is_active', 1)->orderByDesc('id')->first();
                if ($primary) {
                    static::query()->where('id', '!=', $primary->id)->update(['is_active' => 0]);
                    SchoolSetting::updateOrCreate(
                        ['setting_key' => 'academic_year'],
                        ['setting_value' => $primary->name]
                    );
                    static::$configuredSettingYear = $primary->name;
                    static::$configuredSettingYearLoaded = true;
                }
            } elseif ($activeCount === 1) {
                $primary = static::where('is_active', 1)->first();
                if ($primary) {
                    SchoolSetting::updateOrCreate(
                        ['setting_key' => 'academic_year'],
                        ['setting_value' => $primary->name]
                    );
                    static::$configuredSettingYear = $primary->name;
                    static::$configuredSettingYearLoaded = true;
                }
            }
            static::$hasSynchronizedOnce = true;
        } catch (\Throwable $e) {
            // Ignore during migrations or initial installation
        }
    }

    /**
     * Ensure database records are synchronized on boot/request.
     */
    public static function ensureSingleActiveYear(): void
    {
        if (static::$hasSynchronizedOnce) {
            return;
        }
        static::synchronizeActiveYear();
    }

    /**
     * Accessor for is_active: strictly honors the system setting.
     */
    public function getIsActiveAttribute($value): int
    {
        $configured = static::getConfiguredAcademicYear();

        if (! empty($configured)) {
            $isActive = ((string) $this->name === (string) $configured || (string) $this->id === (string) $configured);

            return $isActive ? 1 : 0;
        }

        return $value ? 1 : 0;
    }

    public function school_terms()
    {
        return $this->hasMany(SchoolTerm::class, 'academic_year_id');
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class, 'academic_year_id');
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'academic_year_id');
    }

    public function teacher_assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'academic_year_id');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'academic_year_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'academic_year_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'academic_year_id');
    }

    public function fee_structures()
    {
        return $this->hasMany(FeeStructure::class, 'academic_year_id');
    }
}
