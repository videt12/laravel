<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    use HasFactory;

    protected $table = 'class_subjects';

    protected $fillable = [
        'class_id',
        'subject_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'class_id' => 'integer',
        'subject_id' => 'integer',
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher_assignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'class_subject_id');
    }
}
