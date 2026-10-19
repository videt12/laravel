<?php

namespace App\Models\Enrollment;

use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdrawal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'withdrawals';

    protected $fillable = [
        'student_id',
        'withdrawal_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'withdrawal_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
