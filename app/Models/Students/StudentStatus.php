<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentStatus extends Model
{
    use HasFactory;

    protected $table = 'student_statuses';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * All students currently holding this status.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'student_status_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Return true if this is the active status.
     */
    public function getIsActiveStatusAttribute(): bool
    {
        return strcasecmp($this->name, 'active') === 0;
    }

    /**
     * Get the Khmer display name for the status.
     */
    public function getKhmerNameAttribute(): string
    {
        return match (strtolower(trim($this->name ?? ''))) {
            'active' => 'កំពុងសិក្សា',
            'inactive' => 'អសកម្ម',
            'graduated' => 'បានបញ្ចប់ការសិក្សា',
            'transferred' => 'ផ្ទេរការសិក្សា',
            'withdrawn' => 'ឈប់រៀន',
            'suspended' => 'ព្យួរការសិក្សា',
            'expelled' => 'លុបឈ្មោះចេញ',
            'alumni' => 'អតីតសិស្ស',
            default => $this->name ?? '',
        };
    }
}
