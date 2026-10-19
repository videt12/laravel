<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $table = 'employee_documents';

    protected $fillable = [
        'employee_id',
        'title',
        'document_type',
        'document_path',
        'file_size',
    ];

    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
        'file_size' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
