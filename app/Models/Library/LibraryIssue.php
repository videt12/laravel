<?php

namespace App\Models\Library;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryIssue extends Model
{
    use HasFactory;

    protected $table = 'library_issues';

    protected $fillable = [
        'library_book_copy_id',
        'user_id',
        'issue_date',
        'due_date',
    ];

    protected $casts = [
        'id' => 'integer',
        'library_book_copy_id' => 'integer',
        'user_id' => 'integer',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public function library_book_copy()
    {
        return $this->belongsTo(LibraryBookCopy::class, 'library_book_copy_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function libraryReturn()
    {
        return $this->hasOne(LibraryReturn::class, 'library_issue_id');
    }

    public function library_return()
    {
        return $this->hasOne(LibraryReturn::class, 'library_issue_id');
    }

    public function scopeActive($query)
    {
        return $query->whereDoesntHave('libraryReturn');
    }

    public function scopeOverdue($query)
    {
        return $query->whereDoesntHave('libraryReturn')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString());
    }

    public function scopeReturned($query)
    {
        return $query->whereHas('libraryReturn');
    }

    public function getIsReturnedAttribute(): bool
    {
        return $this->relationLoaded('libraryReturn')
            ? $this->libraryReturn !== null
            : $this->libraryReturn()->exists();
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->is_returned || ! $this->due_date) {
            return false;
        }

        return $this->due_date->isPast() && ! $this->due_date->isToday();
    }

    public function getOverdueDaysAttribute(): int
    {
        if (! $this->due_date) {
            return 0;
        }

        if ($this->is_returned) {
            $returnDate = $this->libraryReturn ? $this->libraryReturn->return_date : optional($this->library_return)->return_date;
            if ($returnDate && $returnDate->greaterThan($this->due_date)) {
                return (int) $this->due_date->diffInDays($returnDate);
            }

            return 0;
        }

        if ($this->is_overdue) {
            return (int) $this->due_date->diffInDays(now()->startOfDay());
        }

        return 0;
    }

    public function getDerivedStatusAttribute(): string
    {
        if ($this->is_returned) {
            return 'returned';
        }

        if ($this->is_overdue) {
            return 'overdue';
        }

        return 'active';
    }
}
