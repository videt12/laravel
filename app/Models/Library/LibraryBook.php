<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryBook extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'library_books';

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'isbn',
        'library_category_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'library_category_id' => 'integer',
    ];

    public function library_category()
    {
        return $this->belongsTo(LibraryCategory::class, 'library_category_id');
    }

    public function library_book_copies()
    {
        return $this->hasMany(LibraryBookCopy::class, 'library_book_id');
    }

    public function library_issues()
    {
        return $this->hasManyThrough(
            LibraryIssue::class,
            LibraryBookCopy::class,
            'library_book_id',
            'library_book_copy_id'
        );
    }

    public function getTotalCopiesCountAttribute(): int
    {
        return $this->library_book_copies->count();
    }

    public function getAvailableCopiesCountAttribute(): int
    {
        return $this->library_book_copies->where('status', 'available')->count();
    }

    public function getIssuedCopiesCountAttribute(): int
    {
        return $this->library_book_copies->where('status', 'issued')->count();
    }

    public function getLostCopiesCountAttribute(): int
    {
        return $this->library_book_copies->where('status', 'lost')->count();
    }

    public function getDamagedCopiesCountAttribute(): int
    {
        return $this->library_book_copies->where('status', 'damaged')->count();
    }
}
