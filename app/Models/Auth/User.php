<?php

namespace App\Models\Auth;

use App\Models\Employees\Employee;
use App\Models\Library\LibraryIssue;
use App\Models\Students\Guardian;
use App\Models\Students\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    use Notifiable;

    public function user_roles()
    {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function login_activities()
    {
        return $this->hasMany(LoginActivity::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class, 'user_id');
    }

    public function library_issues()
    {
        return $this->hasMany(LibraryIssue::class, 'user_id');
    }

    /**
     * Get user photo URL from related employee or student profile.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->employee?->photo_url ?? $this->student?->photo_url ?? null;
    }

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->roles->contains(function ($role) {
            return in_array($role->slug, ['super-admin', 'Super Admin']);
        });
    }

    /**
     * Check if user has a specific role by slug or name.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains(function ($r) use ($role) {
            return $r->slug === $role || strcasecmp($r->name, $role) === 0;
        });
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array|string $roles): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $roles = (array) $roles;

        return $this->roles->contains(function ($role) use ($roles) {
            return in_array($role->slug, $roles) || in_array($role->name, $roles);
        });
    }

    /**
     * Check if user has permission or access to a module.
     */
    public function canAccess(string|array $moduleOrPermission): bool
    {
        if ($moduleOrPermission === 'dashboard') {
            return true;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        if (is_array($moduleOrPermission)) {
            foreach ($moduleOrPermission as $item) {
                if ($this->canAccess($item)) {
                    return true;
                }
            }

            return false;
        }

        // 1. Direct permission check through roles
        $userPermissions = $this->roles->flatMap->permissions->pluck('slug')->all();
        if (in_array($moduleOrPermission, $userPermissions) || in_array($moduleOrPermission.'.view', $userPermissions)) {
            return true;
        }

        // Teachers can view the classes list
        if ($this->hasAnyRole(['teacher']) && in_array($moduleOrPermission, ['classes', 'classes.view'])) {
            return true;
        }

        foreach ($userPermissions as $perm) {
            if (str_starts_with($perm, $moduleOrPermission.'.')) {
                return true;
            }
        }

        // 2. Module aliases / related permissions
        $aliases = [
            'academics' => ['classes.view', 'subjects.view', 'timetables.view', 'classes', 'subjects', 'timetables'],
            'timetables' => ['timetables.view', 'timetables'],
            'payroll' => ['payrolls.view', 'finance.view', 'finance'],
            'departments' => ['departments.view', 'employees.view', 'employees'],
            'positions' => ['departments.view', 'employees.view', 'employees'],
            'staff-attendance' => ['employees.view', 'employees'],
            'staff-leaves' => ['employees.view', 'employees'],
            'employee-contracts' => ['employees.view', 'employees'],
        ];

        if (isset($aliases[$moduleOrPermission])) {
            foreach ($aliases[$moduleOrPermission] as $alias) {
                if (in_array($alias, $userPermissions) || in_array($alias.'.view', $userPermissions)) {
                    return true;
                }
            }
        }

        return false;
    }
}
