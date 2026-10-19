<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBookCopy extends Model
{
    use HasFactory;

    protected $table = 'library_book_copies';

    protected $fillable = [
        'library_book_id',
        'accession_number',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'library_book_id' => 'integer',
    ];

    public function library_book()
    {
        return $this->belongsTo(LibraryBook::class, 'library_book_id');
    }

    public function library_issues()
    {
        return $this->hasMany(LibraryIssue::class, 'library_book_copy_id');
    }

    public function latestIssue()
    {
        return $this->hasOne(LibraryIssue::class, 'library_book_copy_id')->latestOfMany();
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function hasActiveIssue(): bool
    {
        return $this->library_issues()->whereDoesntHave('libraryReturn')->exists();
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'available';
    }
}
