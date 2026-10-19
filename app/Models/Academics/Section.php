<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'name',
        'capacity',
    ];

    protected $casts = [
        'id' => 'integer',
        'capacity' => 'integer',
    ];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'section_id');
    }
}
