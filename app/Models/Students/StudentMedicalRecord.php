<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentMedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'student_medical_records';

    protected $fillable = [
        'student_id',
        'allergies',
        'chronic_diseases',
        'regular_medications',
    ];

    protected $casts = [
        'student_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The student this medical record belongs to.
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
     * Return true if the student has any recorded allergies.
     */
    public function getHasAllergiesAttribute(): bool
    {
        return ! empty($this->allergies);
    }

    /**
     * Return true if the student takes regular medications.
     */
    public function getHasMedicationsAttribute(): bool
    {
        return ! empty($this->regular_medications);
    }
}
