<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $table = 'school_settings';

    protected $fillable = [
        'school_id',
        'setting_key',
        'setting_value',
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
