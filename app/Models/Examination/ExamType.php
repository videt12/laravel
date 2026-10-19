<?php

namespace App\Models\Examination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;

    protected $table = 'exam_types';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class, 'exam_type_id');
    }
}
