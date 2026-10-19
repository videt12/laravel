<?php

namespace App\Models\Enrollment;

use App\Models\Core\School;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTransfer extends Model
{
    use HasFactory;

    protected $table = 'student_transfers';

    protected $fillable = [
        'student_id',
        'from_school_id',
        'to_school_id',
        'transfer_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'from_school_id' => 'integer',
        'to_school_id' => 'integer',
        'transfer_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function from_school()
    {
        return $this->belongsTo(School::class, 'from_school_id');
    }

    public function to_school()
    {
        return $this->belongsTo(School::class, 'to_school_id');
    }
}
