<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectGroup extends Model
{
    use HasFactory;

    protected $table = 'subject_groups';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}
