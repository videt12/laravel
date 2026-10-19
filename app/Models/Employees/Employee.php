<?php

namespace App\Models\Employees;

use App\Models\Academics\TeacherAssignment;
use App\Models\Auth\User;
use App\Models\Payroll\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'first_name_khmer',
        'last_name_khmer',
        'employee_department_id',
        'employee_position_id',
        'employee_status_id',
        'employee_type',
        'contract_type',
        'supervisor_name',
        'work_location',
        'employee_code',
        'date_of_joining',
        'date_of_birth',
        'gender',
        'blood_group',
        'nationality',
        'marital_status',
        'national_id',
        'place_of_birth',
        'birth_village',
        'birth_commune',
        'birth_district',
        'birth_province',
        'basic_salary',
        'salary_type',
        'allowance',
        'deduction',
        'payment_method',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'salary_effective_date',
        'highest_qualification',
        'university',
        'major',
        'graduation_year',
        'professional_certificate',
        'teaching_qualification',
        'skills',
        'experience_years',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'employee_department_id' => 'integer',
        'employee_position_id' => 'integer',
        'employee_status_id' => 'integer',
        'date_of_joining' => 'date',
        'date_of_birth' => 'date',
        'salary_effective_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'deduction' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function (Employee $employee) {
            if ($employee->user_id) {
                $hasRole = DB::table('user_roles')->where('user_id', $employee->user_id)->exists();
                if (! $hasRole) {
                    $pos = $employee->employee_position ?? EmployeePosition::find($employee->employee_position_id);
                    $posName = strtolower($pos?->name ?? '');
                    $posCode = strtoupper($pos?->code ?? '');

                    $roleSlug = match (true) {
                        str_contains($posName, 'principal') || str_contains($posName, 'នាយក') || in_array($posCode, ['POS-PRIN', 'POS-VPRIN']) => 'principal',
                        str_contains($posName, 'librarian') || str_contains($posName, 'បណ្ណារក្ស') || $posCode === 'POS-LIB' => 'librarian',
                        str_contains($posName, 'accountant') || str_contains($posName, 'គណនេយ្យ') || str_contains($posName, 'បេឡា') || $posCode === 'POS-ACCT' => 'accountant',
                        str_contains($posName, 'admin') || $posCode === 'POS-ADMIN' => 'admin',
                        default => 'teacher',
                    };

                    $roleId = DB::table('roles')->where('slug', $roleSlug)->value('id')
                        ?? DB::table('roles')->where('slug', 'teacher')->value('id');

                    if ($roleId) {
                        DB::table('user_roles')->updateOrInsert(
                            ['user_id' => $employee->user_id, 'role_id' => $roleId],
                            ['updated_at' => now()]
                        );
                    }
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function employeeCards(): HasMany
    {
        return $this->hasMany(EmployeeCard::class, 'employee_id');
    }

    public function activeCard(): HasOne
    {
        return $this->hasOne(EmployeeCard::class, 'employee_id')->where('status', 'active')->latest('id');
    }

    public function employee_department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'employee_department_id');
    }

    public function department(): BelongsTo
    {
        return $this->employee_department();
    }

    public function employee_position(): BelongsTo
    {
        return $this->belongsTo(EmployeePosition::class, 'employee_position_id');
    }

    public function position(): BelongsTo
    {
        return $this->employee_position();
    }

    public function employee_status(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_status_id');
    }

    public function status(): BelongsTo
    {
        return $this->employee_status();
    }

    public function employeeContact(): HasOne
    {
        return $this->hasOne(EmployeeContact::class, 'employee_id');
    }

    public function contact(): HasOne
    {
        return $this->employeeContact();
    }

    public function employee_documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }

    public function documents(): HasMany
    {
        return $this->employee_documents();
    }

    public function employee_leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id');
    }

    public function leaves(): HasMany
    {
        return $this->employee_leaves();
    }

    public function employee_attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class, 'employee_id');
    }

    public function attendances(): HasMany
    {
        return $this->employee_attendances();
    }

    public function teacher_assignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'employee_id');
    }

    public function teacherAssignments(): HasMany
    {
        return $this->teacher_assignments();
    }

    public function employeeContracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class, 'employee_id');
    }

    public function activeContract(): HasOne
    {
        return $this->hasOne(EmployeeContract::class, 'employee_id')->where('status', 'active');
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes & Accessors
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->whereHas('employee_status', function ($q) {
            $q->where('name', 'like', '%Active%')
                ->orWhere('is_active', true);
        });
    }

    public function scopeDepartment($query, $departmentId)
    {
        return $query->where('employee_department_id', $departmentId);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('employee_code', 'like', "%{$term}%")
                ->orWhereHas('user', function ($uq) use ($term) {
                    $uq->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                })
                ->orWhereHas('employeeContact', function ($cq) use ($term) {
                    $cq->where('phone', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
        });
    }

    public function scopeTeachers($query, ?int $includeId = null)
    {
        return $query->where(function ($q) use ($includeId) {
            $q->where(function ($sq) {
                $sq->whereHas('user.roles', function ($rq) {
                    $rq->whereIn('slug', ['teacher', 'instructor', 'lecturer'])
                        ->orWhere('name', 'like', '%teacher%')
                        ->orWhere('name', 'like', '%គ្រូ%');
                })->orWhereHas('employee_position', function ($pq) {
                    $pq->where('name', 'like', '%teacher%')
                        ->orWhere('name', 'like', '%instructor%')
                        ->orWhere('name', 'like', '%lecturer%')
                        ->orWhere('name', 'like', '%គ្រូ%')
                        ->orWhere('code', 'like', '%TCHR%');
                });
            });

            if ($includeId) {
                $q->orWhere('employees.id', $includeId);
            }
        });
    }

    public function scopeTeacher($query, ?int $includeId = null)
    {
        return $this->scopeTeachers($query, $includeId);
    }

    public function getNameAttribute(): string
    {
        $khmer = trim(($this->last_name_khmer ?? '').' '.($this->first_name_khmer ?? ''));
        if ($khmer !== '') {
            return $khmer;
        }
        $latin = trim(($this->last_name ?? '').' '.($this->first_name ?? ''));
        if ($latin !== '') {
            return $latin;
        }

        return $this->user->name ?? $this->employee_code ?? 'Staff Member';
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function isTeacher(): bool
    {
        if ($this->relationLoaded('user') && $this->user) {
            $roles = $this->user->relationLoaded('roles') ? $this->user->roles : $this->user->roles()->get();
            $hasTeacherRole = $roles->contains(function ($role) {
                $slug = strtolower($role->slug ?? '');
                $name = strtolower($role->name ?? '');

                return in_array($slug, ['teacher', 'instructor', 'lecturer'])
                    || str_contains($slug, 'teacher')
                    || str_contains($name, 'teacher')
                    || str_contains($name, 'គ្រូ');
            });

            if ($hasTeacherRole) {
                return true;
            }
        } elseif ($this->user_id) {
            $hasTeacherRole = $this->user?->roles()
                ->where(function ($q) {
                    $q->whereIn('slug', ['teacher', 'instructor', 'lecturer'])
                        ->orWhere('name', 'like', '%teacher%')
                        ->orWhere('name', 'like', '%គ្រូ%');
                })->exists() ?? false;

            if ($hasTeacherRole) {
                return true;
            }
        }

        $pos = strtolower($this->employee_position->name ?? '');
        $code = strtoupper($this->employee_position->code ?? '');

        return str_contains($pos, 'teacher')
            || str_contains($pos, 'instructor')
            || str_contains($pos, 'lecturer')
            || str_contains($pos, 'គ្រូ')
            || str_contains($code, 'TCHR');
    }

    public function getFullNameAttribute(): string
    {
        $khmer = trim(($this->last_name_khmer ?? '').' '.($this->first_name_khmer ?? ''));
        if (! empty($khmer)) {
            return $khmer;
        }

        $latin = trim(($this->last_name ?? '').' '.($this->first_name ?? ''));
        if (! empty($latin)) {
            return $latin;
        }

        return $this->user?->name ?? 'បុគ្គលិក';
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->employeeContact?->phone;
    }

    public function getPhotoAttribute(): ?string
    {
        $photoDoc = $this->relationLoaded('employee_documents')
            ? $this->employee_documents->where('document_type', 'photo')->sortByDesc('id')->first()
            : $this->employee_documents()->where('document_type', 'photo')->latest()->first();

        return $photoDoc?->document_path;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        $photoDoc = $this->relationLoaded('employee_documents')
            ? $this->employee_documents->where('document_type', 'photo')->sortByDesc('id')->first()
            : $this->employee_documents()->where('document_type', 'photo')->latest()->first();

        if ($photoDoc && $photoDoc->document_path) {
            if (str_starts_with($photoDoc->document_path, 'http://') || str_starts_with($photoDoc->document_path, 'https://')) {
                return $photoDoc->document_path;
            }

            return asset('storage/'.ltrim($photoDoc->document_path, '/'));
        }

        return null;
    }

    public static function generateNextEmployeeCode(): string
    {
        $lastEmployee = static::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? ($lastEmployee->id + 1) : 1;

        $sample = static::whereNotNull('employee_code')->orderBy('id', 'desc')->first();
        if ($sample && preg_match('/^([A-Za-z\-_]+)(\d+)$/', $sample->employee_code, $matches)) {
            $prefix = $matches[1];
            $digits = max(strlen($matches[2]), 4);

            return sprintf('%s%0'.$digits.'d', $prefix, $nextId);
        }

        return sprintf('EMP-%04d', 1000 + $nextId);
    }
}
