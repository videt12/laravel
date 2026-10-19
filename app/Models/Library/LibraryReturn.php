<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryReturn extends Model
{
    use HasFactory;

    protected $table = 'library_returns';

    protected $fillable = [
        'library_issue_id',
        'return_date',
        'fine_amount',
    ];

    protected $casts = [
        'id' => 'integer',
        'library_issue_id' => 'integer',
        'return_date' => 'date',
        'fine_amount' => 'decimal:2',
    ];

    public function library_issue()
    {
        return $this->belongsTo(LibraryIssue::class, 'library_issue_id');
    }
}
