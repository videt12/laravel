<?php

namespace App\Models\Students;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use App\Models\Attendance\StudentAttendance;
use App\Models\Auth\User;
use App\Models\Core\AcademicYear;
use App\Models\Core\School;
use App\Models\Core\SchoolShift;
use App\Models\Enrollment\Enrollment;
use App\Models\Examination\ExamResult;
use App\Models\Examination\StudentGrade;
use App\Models\Finance\StudentInvoice;
use App\Models\Finance\StudentPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'user_id',
        'admission_number',
        'admission_date',
        'enrollment_date',
        'roll_number',
        'student_status_id',
        'student_category_id',
        'academic_year_id',
        'school_class_id',
        'section_id',
        'school_shift_id',
        'study_mode',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'school_id' => 'integer',
        'user_id' => 'integer',
        'admission_date' => 'date',
        'student_status_id' => 'integer',
        'student_category_id' => 'integer',
        'academic_year_id' => 'integer',
        'school_class_id' => 'integer',
        'section_id' => 'integer',
        'school_shift_id' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function (Student $student) {
            if ($student->user_id) {
                $studentRoleId = DB::table('roles')->where('slug', 'student')->value('id');
                if ($studentRoleId) {
                    DB::table('user_roles')->updateOrInsert(
                        ['user_id' => $student->user_id, 'role_id' => $studentRoleId],
                        ['updated_at' => now()]
                    );
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * School the student belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * User account associated with the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Student status record.
     */
    public function studentStatus(): BelongsTo
    {
        return $this->belongsTo(StudentStatus::class, 'student_status_id');
    }

    /**
     * Alias for studentStatus relationship.
     */
    public function status(): BelongsTo
    {
        return $this->studentStatus();
    }

    /**
     * Student category record.
     */
    public function studentCategory(): BelongsTo
    {
        return $this->belongsTo(StudentCategory::class, 'student_category_id');
    }

    /**
     * Alias for studentCategory relationship.
     */
    public function category(): BelongsTo
    {
        return $this->studentCategory();
    }

    /**
     * Academic year of the student.
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /**
     * School class of the student.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    /**
     * Section of the student.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * School shift of the student.
     */
    public function schoolShift(): BelongsTo
    {
        return $this->belongsTo(SchoolShift::class, 'school_shift_id');
    }

    /**
     * Student profile details (DOB, gender, blood group).
     */
    public function profile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'student_id');
    }

    /**
     * Backward-compatible alias for profile.
     */
    public function studentProfile(): HasOne
    {
        return $this->profile();
    }

    /**
     * Primary student address.
     */
    public function address(): HasOne
    {
        return $this->hasOne(StudentAddresse::class, 'student_id');
    }

    /**
     * Student addresses collection.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(StudentAddresse::class, 'student_id');
    }

    /**
     * Backward-compatible alias for address.
     */
    public function studentAddresse(): HasOne
    {
        return $this->address();
    }

    /**
     * Guardians associated with the student (many-to-many).
     */
    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardians', 'student_id', 'guardian_id')
            ->using(StudentGuardian::class)
            ->withPivot(['id', 'relationship'])
            ->withTimestamps();
    }

    /**
     * Pivot records for student guardians.
     */
    public function studentGuardians(): HasMany
    {
        return $this->hasMany(StudentGuardian::class, 'student_id');
    }

    /**
     * Backward-compatible alias for studentGuardians.
     */
    public function student_guardians(): HasMany
    {
        return $this->studentGuardians();
    }

    /**
     * Documents uploaded for the student.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class, 'student_id');
    }

    /**
     * Backward-compatible alias for documents.
     */
    public function student_documents(): HasMany
    {
        return $this->documents();
    }

    /**
     * Medical record for the student.
     */
    public function medicalRecord(): HasOne
    {
        return $this->hasOne(StudentMedicalRecord::class, 'student_id');
    }

    /**
     * Medical records collection for the student.
     */
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(StudentMedicalRecord::class, 'student_id');
    }

    /**
     * Backward-compatible alias for medicalRecord.
     */
    public function studentMedicalRecord(): HasOne
    {
        return $this->medicalRecord();
    }

    /**
     * Notes recorded for the student.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(StudentNote::class, 'student_id');
    }

    /**
     * Backward-compatible alias for notes.
     */
    public function student_notes(): HasMany
    {
        return $this->notes();
    }

    /**
     * Academic enrollments for the student.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    /**
     * Subject-level exam results for the student.
     */
    public function exam_results(): HasMany
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }

    /**
     * Overall aggregate exam grades for the student.
     */
    public function student_grades(): HasMany
    {
        return $this->hasMany(StudentGrade::class, 'student_id');
    }

    /**
     * Attendance records for the student.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope query to active students.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereHas('studentStatus', function (Builder $statusQuery) {
                $statusQuery->whereRaw('LOWER(name) = ?', ['active']);
            })->orWhere(function (Builder $sub) {
                $sub->whereNull('student_status_id')
                    ->whereHas('user', function (Builder $userQuery) {
                        $userQuery->where('status', 'active');
                    });
            });
        });
    }

    /**
     * Scope query to search students by admission number, user name, or email.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('admission_number', 'like', "%{$term}%")
                ->orWhereHas('user', function (Builder $userQuery) use ($term) {
                    $userQuery->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
        });
    }

    /**
     * Scope query to eager-load detailed relations for show view.
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with([
            'school',
            'user',
            'studentStatus',
            'profile',
            'address',
            'guardians.user',
            'documents',
            'medicalRecord',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get student name from associated user.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user?->name,
        );
    }

    /**
     * Get full display name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user?->name ?? 'N/A',
        );
    }

    /**
     * Get human-readable display status.
     */
    protected function displayStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->studentStatus) {
                    return $this->studentStatus->name;
                }

                if ($this->user?->status) {
                    return ucfirst($this->user->status);
                }

                return 'Unknown';
            },
        );
    }

    /**
     * Get formatted admission date.
     */
    protected function formattedAdmissionDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->admission_date?->format('d M Y') ?? '—',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Generate the next unique Student ID in BPS##### format.
     *
     * Must be called inside an existing DB transaction so the lock is held
     * until the new student row is committed, preventing duplicate IDs under
     * concurrent creation.
     *
     * @return string e.g. "BPS00001"
     */
    public static function generateNextAdmissionNumber(): string
    {
        if (DB::getDriverName() === 'mysql') {
            $latest = DB::table('students')
                ->where('admission_number', 'regexp', '^BPS[0-9]{5}$')
                ->lockForUpdate()
                ->orderByRaw('CAST(SUBSTRING(admission_number, 4) AS UNSIGNED) DESC')
                ->value('admission_number');
        } else {
            $latest = DB::table('students')
                ->where('admission_number', 'LIKE', 'BPS%')
                ->orderByRaw('CAST(SUBSTR(admission_number, 4) AS INTEGER) DESC')
                ->value('admission_number');
        }

        $nextNumber = $latest
            ? (int) substr($latest, 3) + 1
            : 1;

        // Safety: skip any sequence numbers that are already taken (edge-cases
        // where IDs were inserted outside the normal flow).
        while (
            DB::table('students')
                ->where('admission_number', sprintf('BPS%05d', $nextNumber))
                ->exists()
        ) {
            $nextNumber++;
        }

        return sprintf('BPS%05d', $nextNumber);
    }

    /**
     * Determine if the student is currently active.
     */
    public function isActive(): bool
    {
        if ($this->studentStatus) {
            return strcasecmp($this->studentStatus->name, 'active') === 0;
        }

        return $this->user?->status === 'active';
    }

    /**
     * Determine if the student has an associated user account.
     */
    public function hasUser(): bool
    {
        return ! is_null($this->user_id);
    }

    /**
     * Determine if the student has profile details recorded.
     */
    public function hasProfile(): bool
    {
        return ! is_null($this->profile);
    }

    /**
     * Get the invoices associated with the student.
     */
    public function student_invoices(): HasMany
    {
        return $this->hasMany(StudentInvoice::class, 'student_id');
    }

    /**
     * Alias for student_invoices.
     */
    public function invoices(): HasMany
    {
        return $this->student_invoices();
    }

    /**
     * Get payments associated with the student through invoices.
     */
    public function student_payments(): HasManyThrough
    {
        return $this->hasManyThrough(
            StudentPayment::class,
            StudentInvoice::class,
            'student_id',
            'student_invoice_id',
            'id',
            'id'
        );
    }

    /**
     * Alias for student_payments.
     */
    public function payments(): HasManyThrough
    {
        return $this->student_payments();
    }

    /**
     * Scope query for returning students in a given academic year or overall.
     * Has at least one prior enrollment in an academic year before the target academic year.
     */
    public function scopeWhereReturningStudent(Builder $query, ?int $academicYearId = null): Builder
    {
        if ($academicYearId) {
            $targetYear = AcademicYear::find($academicYearId);
            if ($targetYear) {
                return $query->where(function ($q) use ($targetYear, $academicYearId) {
                    $q->where(function ($sub) use ($academicYearId) {
                        $sub->where('students.academic_year_id', $academicYearId)
                            ->orWhereHas('enrollments', fn ($eq) => $eq->where('academic_year_id', $academicYearId));
                    })
                        ->whereHas('enrollments', function (Builder $eq) use ($targetYear) {
                            $eq->whereHas('academic_year', function (Builder $ayq) use ($targetYear) {
                                $ayq->where('academic_years.id', '!=', $targetYear->id);
                                if ($targetYear->start_date) {
                                    $ayq->where('start_date', '<', $targetYear->start_date);
                                } else {
                                    $ayq->where('academic_years.id', '<', $targetYear->id);
                                }
                            });
                        });
                });
            }
        }

        return $query->has('enrollments', '>', 1);
    }

    /**
     * Scope query for new students in a given academic year or overall.
     * Has NO prior enrollment in any academic year before the target academic year.
     */
    public function scopeWhereNewStudent(Builder $query, ?int $academicYearId = null): Builder
    {
        if ($academicYearId) {
            $targetYear = AcademicYear::find($academicYearId);
            if ($targetYear) {
                return $query->whereDoesntHave('enrollments', function (Builder $eq) use ($targetYear) {
                    $eq->whereHas('academic_year', function (Builder $ayq) use ($targetYear) {
                        $ayq->where('academic_years.id', '!=', $targetYear->id);
                        if ($targetYear->start_date) {
                            $ayq->where('start_date', '<', $targetYear->start_date);
                        } else {
                            $ayq->where('academic_years.id', '<', $targetYear->id);
                        }
                    });
                });
            }
        }

        return $query->has('enrollments', '<=', 1);
    }

    /**
     * Determine if the student is New or Returning for a specified academic year or overall.
     * Returns 'new' or 'returning'.
     */
    public function getStudentTypeForYear(?int $academicYearId = null): string
    {
        // 1. If a student only has 1 enrollment in their entire history, they are always a new student!
        if ($this->relationLoaded('enrollments')) {
            if ($this->enrollments->count() <= 1) {
                return 'new';
            }
        } else {
            if ($this->enrollments()->count() <= 1) {
                return 'new';
            }
        }

        // 2. Determine target academic year to compare against
        $targetYearId = $academicYearId;

        if (! $targetYearId) {
            $targetYearId = $this->academic_year_id;
        } else {
            // Check if student belongs to or is enrolled in targetYearId
            $isEnrolledInTarget = false;
            if ($this->academic_year_id == $targetYearId) {
                $isEnrolledInTarget = true;
            } elseif ($this->relationLoaded('enrollments')) {
                $isEnrolledInTarget = $this->enrollments->contains(fn ($e) => $e->academic_year_id == $targetYearId);
            } else {
                $isEnrolledInTarget = $this->enrollments()->where('academic_year_id', $targetYearId)->exists();
            }

            if (! $isEnrolledInTarget && $this->academic_year_id) {
                $targetYearId = $this->academic_year_id;
            }
        }

        if (! $targetYearId) {
            return 'new';
        }

        $targetYear = AcademicYear::find($targetYearId);
        if (! $targetYear) {
            return 'new';
        }

        // 3. A student is 'returning' in targetYear ONLY IF they have an enrollment BEFORE targetYear
        if ($this->relationLoaded('enrollments')) {
            $hasPrevious = $this->enrollments->contains(function ($enrollment) use ($targetYear) {
                $ay = $enrollment->academic_year ?? $enrollment->academicYear;
                if (! $ay || $ay->id == $targetYear->id) {
                    return false;
                }
                if ($targetYear->start_date && $ay->start_date) {
                    return $ay->start_date < $targetYear->start_date;
                }

                return $ay->id < $targetYear->id;
            });

            return $hasPrevious ? 'returning' : 'new';
        }

        $hasPrevious = $this->enrollments()
            ->whereHas('academic_year', function (Builder $ayq) use ($targetYear) {
                $ayq->where('id', '!=', $targetYear->id);
                if ($targetYear->start_date) {
                    $ayq->where('start_date', '<', $targetYear->start_date);
                } else {
                    $ayq->where('id', '<', $targetYear->id);
                }
            })
            ->exists();

        return $hasPrevious ? 'returning' : 'new';
    }

    /**
     * Get accessible profile photo URL or null.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->studentProfile?->photo_url ?? $this->profile?->photo_url;
    }

    /**
     * Get human-readable study mode label in Khmer and English.
     */
    public function getStudyModeLabelAttribute(): string
    {
        return match ($this->study_mode) {
            'part_time' => 'ក្រៅម៉ោង (Part-Time)',
            'dual' => 'ពេញម៉ោង និង ក្រៅម៉ោង (Dual Study)',
            default => 'ពេញម៉ោង (Full-Time)',
        };
    }

    /**
     * Scope query by study mode (full_time, part_time, dual).
     */
    public function scopeStudyMode(Builder $query, ?string $mode): Builder
    {
        if (blank($mode)) {
            return $query;
        }

        return $query->where('study_mode', $mode);
    }

    /**
     * Determine if the student is studying full-time.
     */
    public function isFullTime(): bool
    {
        return ($this->study_mode ?? 'full_time') === 'full_time';
    }

    /**
     * Determine if the student is studying part-time.
     */
    public function isPartTime(): bool
    {
        return $this->study_mode === 'part_time';
    }

    /**
     * Determine if the student is studying both full-time and part-time.
     */
    public function isDual(): bool
    {
        return $this->study_mode === 'dual';
    }
}
