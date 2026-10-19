<?php

namespace App\Models\Students;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Guardian extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'guardians';

    protected $fillable = [
        'user_id',
        'occupation',
        'age',
        'income',
        'address',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'age' => 'integer',
        'income' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function (Guardian $guardian) {
            if ($guardian->user_id) {
                $guardianRoleId = DB::table('roles')->where('slug', 'guardian')->value('id');
                if ($guardianRoleId) {
                    DB::table('user_roles')->updateOrInsert(
                        ['user_id' => $guardian->user_id, 'role_id' => $guardianRoleId],
                        ['updated_at' => now()]
                    );
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The user account linked to this guardian.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * All students associated with this guardian (many-to-many).
     */
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'student_guardians',
            'guardian_id',
            'student_id'
        )->using(StudentGuardian::class)
            ->withPivot(['id', 'relationship'])
            ->withTimestamps();
    }

    /**
     * Pivot records linking this guardian to students.
     */
    public function studentGuardians()
    {
        return $this->hasMany(StudentGuardian::class, 'guardian_id');
    }

    /**
     * Contact details for this guardian.
     */
    public function contacts()
    {
        return $this->hasMany(GuardianContact::class, 'guardian_id');
    }

    /**
     * Backward-compatible alias.
     */
    public function guardian_contacts()
    {
        return $this->contacts();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Guardian name from linked user account.
     */
    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    /**
     * Guardian email from linked user account.
     */
    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }
}
