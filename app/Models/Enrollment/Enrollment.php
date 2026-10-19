<?php

namespace App\Models\Enrollment;

use App\Models\Core\AcademicYear;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'enrollments';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'enrollment_date',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'academic_year_id' => 'integer',
        'enrollment_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function academicYear()
    {
        return $this->academic_year();
    }

    public function enrollmentDetail()
    {
        return $this->hasOne(EnrollmentDetail::class, 'enrollment_id');
    }

    public function enrollmentDetails()
    {
        return $this->hasMany(EnrollmentDetail::class, 'enrollment_id');
    }
}
