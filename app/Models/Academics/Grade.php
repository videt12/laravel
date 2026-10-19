<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'grades';

    protected $fillable = [
        'name',
        'level',
    ];

    protected $casts = [
        'id' => 'integer',
        'level' => 'integer',
    ];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'grade_id');
    }
}
