<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $table = 'student_profiles';

    protected $fillable = [
        'student_id',
        'photo',
        'phone',
        'first_name',
        'last_name',
        'first_name_khmer',
        'last_name_khmer',
        'dob',
        'gender',
        'blood_group',
        'nationality',
        'national_id',
        'previous_school',
        'special_needs',
        'medical_information',
        'notes',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'dob' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The student this profile belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Human-readable formatted date of birth.
     */
    public function getFormattedDobAttribute(): string
    {
        return $this->dob?->format('d M Y') ?? '—';
    }

    /**
     * Age calculated from date of birth.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob?->age;
    }

    /**
     * Gender label capitalized.
     */
    public function getGenderLabelAttribute(): string
    {
        return ucfirst($this->gender ?? '—');
    }

    /**
     * Full URL for the profile photo or null if not present.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        return asset('storage/'.ltrim($this->photo, '/'));
    }
}
