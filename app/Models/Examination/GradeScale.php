<?php

namespace App\Models\Examination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model
{
    use HasFactory;

    protected $table = 'grade_scales';

    protected $fillable = [
        'name',
        'min_percentage',
        'max_percentage',
        'grade',
        'gpa_point',
    ];

    protected $casts = [
        'id' => 'integer',
        'min_percentage' => 'decimal:2',
        'max_percentage' => 'decimal:2',
        'gpa_point' => 'decimal:2',
    ];

    /**
     * Resolve the GradeScale entry corresponding to a given percentage score.
     */
    public static function getScaleForPercentage(float $percentage): ?self
    {
        // Round to 2 decimals matching schema decimal(5,2)
        $percentage = round($percentage, 2);

        return static::where('min_percentage', '<=', $percentage)
            ->where('max_percentage', '>=', $percentage)
            ->orderByDesc('min_percentage')
            ->first();
    }
}
