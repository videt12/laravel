@extends('layouts.app')

@section('title', 'Student Note — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student Services</li>
        <li class="breadcrumb-item"><a href="{{ route('student-notes.index') }}" class="text-decoration-none text-muted">Student Notes</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">View Note</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
    .note-full-content {
        font-size: .875rem;
        color: var(--text-dark);
        line-height: 1.8;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .tag-pill {
        display: inline-flex; align-items: center; gap: .25rem;
        background: var(--gold-light); color: var(--gold-dark);
        border-radius: 999px; padding: .15rem .6rem;
        font-size: .73rem; font-weight: 500;
    }
    .flag-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        border-radius: var(--radius-sm); padding: .25rem .65rem;
        font-size: .75rem; font-weight: 600;
    }
    .flag-badge-warning  { background:#fef9c3; color:#854d0e; }
    .flag-badge-primary  { background:#dbeafe; color:#1e40af; }
    .flag-badge-inactive { background:#f1f5f9; color:var(--text-muted); }

    /* Priority badges */
    .note-priority-badge {
        display: inline-flex; align-items: center; gap: .28rem;
        border-radius: var(--radius-sm); padding: .2rem .55rem;
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .4px; white-space: nowrap;
    }
    .note-priority-urgent { background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; }
    .note-priority-high   { background:#fff7ed; color:#c2410c; border:1px solid #fdba74; }
    .note-priority-normal { background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd; }
    .note-priority-low    { background:#f8fafc; color:#64748b; border:1px solid #e2e8f0; }

    /* Status badges */
    .note-status-badge {
        display: inline-flex; align-items: center; gap: .28rem;
        border-radius: 999px; padding: .18rem .65rem;
        font-size: .72rem; font-weight: 600; white-space: nowrap;
    }
    .note-status-active   { background:#dcfce7; color:#15803d; }
    .note-status-resolved { background:#e0e7ff; color:#4338ca; }
    .note-status-archived { background:#f1f5f9; color:#64748b; }
</style>
@endpush

@section('content')

@php
    $name         = $studentNote->student?->user?->name ?? '—';
    $admissionNo  = $studentNote->student?->admission_number ?? null;
    $initials     = strtoupper(substr($name, 0, 1));
    $avatarColors = ['#e8a838','#3b82f6','#10b981','#8b5cf6','#ef4444','#f59e0b'];
    $color        = $avatarColors[crc32($name) % count($avatarColors)];
@endphp

{{-- ── PAGE HEADER ─────────────────────────────────────── --}}
<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Student Note</h1>
        <p class="page-subtitle">
            Recorded on {{ $studentNote->note_date?->format('d M Y') ?? $studentNote->created_at?->format('d M Y') ?? '—' }}
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-shrink-0">
        <a href="{{ route('student-notes.edit', $studentNote) }}" class="btn btn-primary-action btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <div class="dropdown">
            <button class="btn btn-ghost btn-sm dropdown-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">More</button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="font-size:.875rem">
                <li>
                    <button type="button" class="dropdown-item py-2 text-danger"
                            data-bs-toggle="modal" data-bs-target="#delModal">
                        <i class="bi bi-trash me-2"></i>Delete Note
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ── LEFT: NOTE DETAIL ─────────────────────────────── --}}
    <div class="col-xl-8 col-lg-8">

        {{-- Note Content Card --}}
        <div class="form-card mb-4">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="bi bi-journal-text"></i></div>
                <div>
                    <div class="form-card-title">Note Content</div>
                    <div class="form-card-subtitle">Full note record</div>
                </div>
                {{-- Flags --}}
                <div class="d-flex gap-2 ms-auto flex-wrap">
                    @if($studentNote->is_important)
                        <span class="flag-badge flag-badge-warning">
                            <i class="bi bi-bookmark-star-fill"></i>Important
                        </span>
                    @endif
                    @if($studentNote->follow_up_required)
                        <span class="flag-badge flag-badge-primary">
                            <i class="bi bi-clock-history"></i>Follow Up
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-card-body">
                <div class="note-full-content">{{ $studentNote->note ?? '—' }}</div>
            </div>
        </div>

        {{-- Metadata rows --}}
        <div class="form-card">
            <div class="form-card-body p-0">
                <div class="detail-row px-4">
                    <span class="detail-label">Note Type</span>
                    <span class="detail-value">{{ $studentNote->note_type_label ?? '—' }}</span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Note Date</span>
                    <span class="detail-value">{{ $studentNote->note_date?->format('d M Y') ?? '—' }}</span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Priority</span>
                    <span class="detail-value">
                        <span class="note-priority-badge {{ $studentNote->priority_class }}">
                            @if($studentNote->priority === 'urgent') <i class="bi bi-exclamation-circle-fill"></i>
                            @elseif($studentNote->priority === 'high') <i class="bi bi-arrow-up-circle-fill"></i>
                            @elseif($studentNote->priority === 'low') <i class="bi bi-arrow-down-circle"></i>
                            @else <i class="bi bi-dash-circle"></i>
                            @endif
                            {{ $studentNote->priority_label }}
                        </span>
                    </span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="note-status-badge {{ $studentNote->status_class }}">
                            @if($studentNote->status === 'active') <i class="bi bi-circle-fill" style="font-size:.45rem"></i>
                            @elseif($studentNote->status === 'resolved') <i class="bi bi-check-circle-fill" style="font-size:.7rem"></i>
                            @else <i class="bi bi-archive" style="font-size:.7rem"></i>
                            @endif
                            {{ $studentNote->status_label }}
                        </span>
                    </span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Visibility</span>
                    <span class="detail-value">{{ $studentNote->visibility_label ?? '—' }}</span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Tags</span>
                    <span class="detail-value">
                        @if($studentNote->tags_array)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($studentNote->tags_array as $tag)
                                    <span class="tag-pill"><i class="bi bi-tag" style="font-size:.65rem"></i>{{ $tag }}</span>
                                @endforeach
                            </div>
                        @else
                            —
                        @endif
                    </span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Important</span>
                    <span class="detail-value">
                        @if($studentNote->is_important)
                            <span class="flag-badge flag-badge-warning"><i class="bi bi-bookmark-star-fill"></i>Yes</span>
                        @else
                            <span class="flag-badge flag-badge-inactive"><i class="bi bi-bookmark"></i>No</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Follow Up</span>
                    <span class="detail-value">
                        @if($studentNote->follow_up_required)
                            <span class="flag-badge flag-badge-primary"><i class="bi bi-clock-history"></i>Required</span>
                        @else
                            <span class="flag-badge flag-badge-inactive"><i class="bi bi-clock"></i>Not required</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Created By</span>
                    <span class="detail-value">{{ $studentNote->author?->name ?? '—' }}</span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Created</span>
                    <span class="detail-value">{{ $studentNote->created_at?->format('d M Y, H:i') ?? '—' }}</span>
                </div>
                <div class="detail-row px-4">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">{{ $studentNote->updated_at?->format('d M Y, H:i') ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Back link --}}
        <div class="mt-3">
            <a href="{{ route('student-notes.index') }}" class="btn btn-ghost btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Student Notes
            </a>
        </div>

    </div>

    {{-- ── RIGHT: STUDENT INFO ────────────────────────────── --}}
    <div class="col-xl-4 col-lg-4">

        {{-- Student Card --}}
        <div class="form-card mb-4">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="bi bi-person"></i></div>
                <div>
                    <div class="form-card-title">Student</div>
                    <div class="form-card-subtitle">Associated student record</div>
                </div>
            </div>
            <div class="form-card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="student-avatar"
                         style="width:48px;height:48px;font-size:.9rem;flex-shrink:0;background:{{ $color }}1a;color:{{ $color }}">
                        {{ $initials }}
                    </div>
                    <div class="lh-sm">
                        @if($studentNote->student)
                            <a href="{{ route('students.show', $studentNote->student) }}"
                               class="fw-semibold text-dark text-decoration-none student-name-link d-block">
                                {{ $name }}
                            </a>
                        @else
                            <span class="fw-semibold text-dark">{{ $name }}</span>
                        @endif
                        @if($admissionNo)
                            <code class="adm-badge mt-1 d-inline-block">{{ $admissionNo }}</code>
                        @endif
                    </div>
                </div>
                @if($studentNote->student)
                    <a href="{{ route('students.show', $studentNote->student) }}"
                       class="btn btn-ghost btn-sm w-100">
                        <i class="bi bi-arrow-up-right me-1"></i>View Student Profile
                    </a>
                @endif
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon" style="background:#f0fdf4;color:#16a34a">
                    <i class="bi bi-lightning"></i>
                </div>
                <div class="form-card-title">Quick Actions</div>
            </div>
            <div class="form-card-body p-0">
                <a href="{{ route('student-notes.edit', $studentNote) }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2">
                    <i class="bi bi-pencil text-muted"></i>Edit This Note
                </a>
                <a href="{{ route('student-notes.create') }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg text-muted"></i>Add New Note
                </a>
                <a href="{{ route('student-notes.index') }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2">
                    <i class="bi bi-list text-muted"></i>All Student Notes
                </a>
                <button type="button"
                        class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-danger"
                        data-bs-toggle="modal" data-bs-target="#delModal">
                    <i class="bi bi-trash"></i>Delete This Note
                </button>
            </div>
        </div>

    </div>

</div>

{{-- Delete modal --}}
<div class="modal fade" id="delModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10"
                          style="width:48px;height:48px">
                        <i class="bi bi-trash text-danger fs-5"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1">Delete Note?</h6>
                <p class="text-muted small mb-4">
                    This note for <strong>{{ $name }}</strong> will be permanently removed.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('student-notes.destroy', $studentNote) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
