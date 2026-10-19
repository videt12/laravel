<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * StudentAddress model.
 *
 * NOTE: The class file is named StudentAddresse.php to match Laravel's
 * auto-generated name. The actual DB table is student_addresses.
 * All application code should use Student::address() or this model directly.
 */
class StudentAddresse extends Model
{
    use HasFactory;

    /**
     * Explicit table name — overrides the typo-based default.
     */
    protected $table = 'student_addresses';

    protected $fillable = [
        'student_id',
        'current_address',
        'permanent_address',
        'address_line_1',
        'address_line_2',
        'village',
        'commune',
        'district',
        'province',
        'postal_code',
    ];

    protected $casts = [
        'student_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The student this address record belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
