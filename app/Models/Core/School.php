<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'schools';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function school_branches()
    {
        return $this->hasMany(SchoolBranch::class, 'school_id');
    }

    public function school_settings()
    {
        return $this->hasMany(SchoolSetting::class, 'school_id');
    }
}
