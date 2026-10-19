<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentGuardian extends Pivot
{
    protected $table = 'student_guardians';

    /**
     * Allow mass-assignment on pivot with extra columns.
     */
    public $incrementing = true;

    protected $fillable = [
        'student_id',
        'guardian_id',
        'relationship',
        'is_primary',
        'is_emergency',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'guardian_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The student in this pivot record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * The guardian in this pivot record.
     */
    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }
}
