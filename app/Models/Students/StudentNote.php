<?php

namespace App\Models\Students;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StudentNote extends Model
{
    use HasFactory;

    protected $table = 'student_notes';

    protected $fillable = [
        'student_id',
        'note',
        'note_type',
        'note_date',
        'visibility',
        'tags',
        'is_important',
        'follow_up_required',
        'priority',
        'status',
        'created_by',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'created_by' => 'integer',
        'note_date' => 'date',
        'is_important' => 'boolean',
        'follow_up_required' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Constants — used in forms and controllers
    |--------------------------------------------------------------------------
    */

    public const NOTE_TYPES = [
        'academic' => 'Academic',
        'behavioral' => 'Behavioral',
        'health' => 'Health',
        'administrative' => 'Administrative',
        'parent_meeting' => 'Parent Meeting',
        'general' => 'General',
    ];

    public const VISIBILITY_OPTIONS = [
        'private' => 'Private (Staff Only)',
        'staff' => 'All Staff',
        'guardian' => 'Guardian & Staff',
    ];

    public const PRIORITIES = [
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
        'urgent' => 'Urgent',
    ];

    public const STATUSES = [
        'active' => 'Active',
        'resolved' => 'Resolved',
        'archived' => 'Archived',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->author();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /** Short preview of the note for listing views. */
    public function getPreviewAttribute(): string
    {
        return Str::limit($this->note ?? '', 100);
    }

    /** Human-readable note type label. */
    public function getNoteTypeLabelAttribute(): string
    {
        return self::NOTE_TYPES[$this->note_type] ?? ucfirst($this->note_type ?? '—');
    }

    /** Human-readable visibility label. */
    public function getVisibilityLabelAttribute(): string
    {
        return self::VISIBILITY_OPTIONS[$this->visibility] ?? ucfirst($this->visibility ?? '—');
    }

    /** Human-readable priority label. */
    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? ucfirst($this->priority ?? '—');
    }

    /** Human-readable status label. */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status ?? '—');
    }

    /**
     * CSS badge class for priority.
     * Matches the .note-priority-* classes defined in the student notes views.
     */
    public function getPriorityClassAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'note-priority-urgent',
            'high' => 'note-priority-high',
            'low' => 'note-priority-low',
            default => 'note-priority-normal',
        };
    }

    /**
     * CSS badge class for status.
     * Matches the .note-status-* classes defined in the student notes views.
     */
    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'resolved' => 'note-status-resolved',
            'archived' => 'note-status-archived',
            default => 'note-status-active',
        };
    }

    /**
     * CSS badge class for note type.
     */
    public function getNoteTypeClassAttribute(): string
    {
        return match ($this->note_type) {
            'academic' => 'note-type-academic',
            'behavioral' => 'note-type-behavioral',
            'health' => 'note-type-health',
            'administrative' => 'note-type-administrative',
            'parent_meeting' => 'note-type-parent',
            'general' => 'note-type-general',
            default => 'note-type-general',
        };
    }

    /** Tags as a clean trimmed array. */
    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->tags)));
    }
}
