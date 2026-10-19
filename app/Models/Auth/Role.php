<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function role_permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    public function user_roles()
    {
        return $this->hasMany(UserRole::class, 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get display name in Khmer / bilingual.
     */
    public function getDisplayNameAttribute(): string
    {
        $translations = [
            'super-admin' => 'អ្នកគ្រប់គ្រងប្រព័ន្ធ',
            'admin' => 'អ្នកគ្រប់គ្រង (Admin)',
            'principal' => 'នាយកសាលា (Principal)',
            'teacher' => 'គ្រូបង្រៀន (Teacher)',
            'librarian' => 'បណ្ណារក្ស (Librarian)',
            'accountant' => 'គណនេយ្យករ (Accountant)',
            'student' => 'សិស្ស (Student)',
            'guardian' => 'អាណាព្យាបាល (Guardian)',
        ];

        return $translations[$this->slug] ?? $this->name;
    }
}
