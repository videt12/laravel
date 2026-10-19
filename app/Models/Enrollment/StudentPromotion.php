<?php

namespace App\Models\Enrollment;

use App\Models\Academics\SchoolClass;
use App\Models\Core\AcademicYear;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $table = 'student_promotions';

    protected $fillable = [
        'student_id',
        'from_academic_year_id',
        'to_academic_year_id',
        'from_class_id',
        'to_class_id',
        'promotion_date',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'from_academic_year_id' => 'integer',
        'to_academic_year_id' => 'integer',
        'from_class_id' => 'integer',
        'to_class_id' => 'integer',
        'promotion_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function from_academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    public function to_academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    public function from_class()
    {
        return $this->belongsTo(SchoolClass::class, 'from_class_id');
    }

    public function to_class()
    {
        return $this->belongsTo(SchoolClass::class, 'to_class_id');
    }
}
