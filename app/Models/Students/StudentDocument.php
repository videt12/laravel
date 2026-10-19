<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;

    protected $table = 'student_documents';

    protected $fillable = [
        'student_id',
        'document_type',
        'document_path',
    ];

    protected $casts = [
        'student_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The student this document belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Return just the filename part of the document path.
     */
    public function getFileNameAttribute(): string
    {
        return basename($this->document_path ?? '');
    }

    /**
     * Return a human-readable document type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $this->document_type ?? ''));
    }
}
