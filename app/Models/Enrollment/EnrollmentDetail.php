<?php

namespace App\Models\Enrollment;

use App\Models\Academics\SchoolClass;
use App\Models\Academics\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentDetail extends Model
{
    use HasFactory;

    protected $table = 'enrollment_details';

    protected $fillable = [
        'enrollment_id',
        'class_id',
        'section_id',
        'roll_number',
    ];

    protected $casts = [
        'id' => 'integer',
        'enrollment_id' => 'integer',
        'class_id' => 'integer',
        'section_id' => 'integer',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
