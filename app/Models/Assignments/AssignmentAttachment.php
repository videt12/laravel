<?php

namespace App\Models\Assignments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentAttachment extends Model
{
    use HasFactory;

    protected $table = 'assignment_attachments';

    protected $fillable = [
        'assignment_id',
        'file_name',
        'file_path',
    ];

    protected $casts = [
        'id' => 'integer',
        'assignment_id' => 'integer',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
