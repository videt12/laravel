<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardianContact extends Model
{
    use HasFactory;

    protected $table = 'guardian_contacts';

    protected $fillable = [
        'guardian_id',
        'phone',
        'email',
    ];

    protected $casts = [
        'guardian_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The guardian this contact belongs to.
     */
    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }
}
