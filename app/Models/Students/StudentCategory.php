<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'student_categories';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Students belonging to this category.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'student_category_id');
    }

    /**
     * Get the Khmer display name for the category.
     */
    public function getKhmerNameAttribute(): string
    {
        $name = trim($this->name ?? '');
        $lower = mb_strtolower($name);

        if ($name === 'ទូទៅ' || $name === 'អាហារូបករណ៍') {
            return $name;
        }

        if (str_contains($lower, 'scholarship') || str_contains($lower, 'អាហារូបករណ៍')) {
            return 'អាហារូបករណ៍';
        }

        if (str_contains($lower, 'general') || str_contains($lower, 'ទូទៅ')) {
            return 'ទូទៅ';
        }

        return $name;
    }
}
