<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolBranch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'school_branches';

    protected $fillable = [
        'school_id',
        'name',
        'address',
        'contact_number',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
