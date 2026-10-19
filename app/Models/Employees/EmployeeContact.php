<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeContact extends Model
{
    use HasFactory;

    protected $table = 'employee_contacts';

    protected $fillable = [
        'employee_id',
        'phone',
        'emergency_phone',
        'email',
        'current_address',
        'permanent_address',
        'village',
        'commune',
        'district',
        'province',
        'emergency_contact_name',
        'emergency_relationship',
        'emergency_address',
    ];

    protected $casts = [
        'id' => 'integer',
        'employee_id' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
