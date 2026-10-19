@extends('layouts.app')

@section('title', 'Edit Student Note — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student Services</li>
        <li class="breadcrumb-item"><a href="{{ route('student-notes.index') }}" class="text-decoration-none text-muted">Student Notes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('student-notes.show', $studentNote) }}" class="text-decoration-none text-muted">View Note</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Note</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══ BANNER (Gold/Yellow Theme) ═══════════════════════════════ */
.note-page-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-md, 8px);
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.85rem;
    position: relative; overflow: hidden;
    box-shadow: 0 2px 8px rgba(232, 168, 56, 0.18);
}
.note-page-banner::before {
    content: ''; position: absolute;
    top: -30px; right: -30px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,.15); border-radius: 50%;
    pointer-events: none;
}
.note-page-banner::after {
    content: ''; position: absolute;
    bottom: -25px; right: 70px;
    width: 80px; height: 80px;
    background: rgba(255,255,255,.08); border-radius: 50%;
    pointer-events: none;
}
.note-page-banner .banner-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,.25);
    border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff;
    margin-bottom: 0;
    flex-shrink: 0;
}
.note-page-banner h1 { font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0 0 .15rem; line-height: 1.25; }
.note-page-banner p  { font-size: .78rem; color: rgba(255,255,255,.92); margin: 0; line-height: 1.3; }
.note-page-banner .btn-banner-back {
    background: rgba(255,255,255,.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,.35);
    font-size: .78rem;
    border-radius: 6px;
    padding: 0.32rem 0.75rem;
    transition: all .15s ease;
}
.note-page-banner .btn-banner-back:hover {
    background: rgba(255,255,255,.3);
    color: #fff;
    border-color: rgba(255,255,255,.5);
    transform: translateY(-1px);
}
.note-page-banner .meta-strip {
    display: flex; align-items: center; gap: .85rem;
    margin-top: .4rem; padding-top: .35rem;
    border-top: 1px solid rgba(255,255,255,.2);
}
.note-page-banner .meta-item {
    display: flex; align-items: center; gap: .35rem;
    font-size: .72rem; color: rgba(255,255,255,.85);
}

/* ═══ FORM CARD (Compact) ══════════════════════════════════════ */
.note-form-card {
    background: var(--form-card-bg, #fff);
    border: 1px solid var(--form-card-border, #e2e8f0);
    border-top: 3px solid #e8a838;
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.form-section {
    padding: .75rem 1rem;
    border-bottom: 1px solid var(--border-soft);
}
.form-section:last-child { border-bottom: none; }
.form-section-head {
    display: flex; align-items: center; gap: .5rem; margin-bottom: .55rem;
}
.form-section-head-bar { width: 3px; height: .85rem; border-radius: 999px; background: var(--gold); flex-shrink: 0; }
.form-section-head-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }

/* ═══ FIELD STYLES (Compact) ═══════════════════════════════════ */
.form-label-custom {
    font-size: .78rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: .25rem;
    display: block;
}
.field-hint { display: flex; align-items: center; gap: .25rem; font-size: .7rem; color: var(--text-muted); margin-top: .2rem; }
.form-select.fc, .form-control.fc {
    border-color: #cbd5e1; font-size: .82rem;
    border-radius: var(--radius-sm, 6px); color: var(--text-dark); background-color: var(--form-input-bg, #fff);
    transition: border-color .15s, box-shadow .15s; padding: .32rem .75rem; height: 35px;
}
.form-select.fc { padding-right: 2rem; }
.form-select.fc:focus, .form-control.fc:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

/* ═══ RADIO CARDS (Compact) ════════════════════════════════════ */
.option-cards { display: flex; gap: .45rem; flex-wrap: wrap; }
.option-card { position: relative; flex: 1; min-width: 75px; }
.option-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.option-card label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .2rem; cursor: pointer;
    border: 1.5px solid #e2e8f0; border-radius: 6px;
    padding: .4rem .35rem; font-size: .72rem; font-weight: 600;
    color: var(--text-muted); background: #fafbfc;
    transition: all .15s ease; text-align: center; line-height: 1.2; user-select: none;
}
.option-card label i { font-size: .88rem; margin-bottom: 0; }
.option-card label:hover { border-color: var(--gold); background: var(--gold-light); color: var(--gold-dark); }
.option-card input:checked + label { border-width: 1.5px; font-weight: 700; box-shadow: 0 1px 4px rgba(0,0,0,.08); }

.option-card.priority-low   input:checked + label { border-color:#94a3b8; background:#f8fafc; color:#475569; }
.option-card.priority-normal input:checked + label { border-color:#3b82f6; background:#eff6ff; color:#1d4ed8; }
.option-card.priority-high  input:checked + label { border-color:#f97316; background:#fff7ed; color:#c2410c; }
.option-card.priority-urgent input:checked + label { border-color:#ef4444; background:#fef2f2; color:#dc2626; }
.option-card.status-active   input:checked + label { border-color:#16a34a; background:#f0fdf4; color:#15803d; }
.option-card.status-resolved input:checked + label { border-color:#6366f1; background:#eef2ff; color:#4338ca; }
.option-card.status-archived input:checked + label { border-color:#94a3b8; background:#f8fafc; color:#475569; }

/* ═══ TEXTAREA (Compact) ═══════════════════════════════════════ */
.note-textarea {
    min-height: 90px; resize: vertical; font-size: .82rem; line-height: 1.5;
    border: 1px solid #cbd5e1; border-radius: var(--radius-sm, 6px) var(--radius-sm, 6px) 0 0;
    padding: .5rem .75rem; width: 100%; color: var(--text-dark);
    transition: border-color .15s, box-shadow .15s; background: var(--form-input-bg, #fff); font-family: inherit;
}
.note-textarea:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }
.note-textarea.is-invalid { border-color: #ef4444; }
.textarea-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: .25rem .75rem; background: #f8fafc;
    border: 1px solid #cbd5e1; border-top: none;
    border-radius: 0 0 var(--radius-sm, 6px) var(--radius-sm, 6px);
}
.word-count { font-size: .7rem; color: var(--text-muted); font-variant-numeric: tabular-nums; }

/* ═══ TAGS ═════════════════════════════════════════════════════ */
.tag-preview { display: flex; flex-wrap: wrap; gap: .3rem; margin-top: .35rem; min-height: 0; }
.tag-pill {
    display: inline-flex; align-items: center; gap: .2rem;
    background: var(--gold-light); color: var(--gold-dark);
    border-radius: 999px; padding: .12rem .5rem;
    font-size: .7rem; font-weight: 500; animation: tagIn .15s ease;
}
@keyframes tagIn { from { transform: scale(.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

/* ═══ CHECK OPTION CARDS (Compact) ═════════════════════════════ */
.check-option {
    display: flex; align-items: flex-start; gap: .55rem;
    padding: .5rem .75rem; border: 1.5px solid #e2e8f0;
    border-radius: 6px; cursor: pointer;
    transition: all .15s; background: #fafbfc;
}
.check-option:hover { border-color: var(--gold); background: var(--gold-light); }
.check-option.checked { border-color: var(--gold); background: var(--gold-light); }
.check-option input[type="checkbox"] { width: .95rem; height: .95rem; flex-shrink: 0; cursor: pointer; margin-top: .15rem; accent-color: var(--gold); }
.check-option-title { font-size: .78rem; font-weight: 600; color: var(--text-dark); }
.check-option-desc  { font-size: .7rem; color: var(--text-muted); margin-top: .05rem; }
.check-option-icon  { font-size: .95rem; flex-shrink: 0; margin-top: .05rem; }

/* ═══ FORM FOOTER (Compact) ════════════════════════════════════ */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: .65rem 1rem; background: #f8fafc;
    border-top: 1px solid var(--border-soft);
}
.required-note {
    display: flex; align-items: center; gap: .4rem;
    font-size: .71rem; color: var(--text-muted);
    padding: .3rem .6rem; background: #fff;
    border: 1px dashed #cbd5e1; border-radius: var(--radius-sm, 6px);
}
.btn-primary-action {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    color: #fff;
    border: none;
    font-size: .8rem;
    font-weight: 600;
    padding: .35rem .85rem;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(232, 168, 56, 0.3);
}
.btn-primary-action:hover {
    background: linear-gradient(135deg, #df9b28 0%, #b87c14 100%);
    color: #fff;
}

/* ═══ SIDEBAR CARDS (Compact) ══════════════════════════════════ */
.sidebar-card { background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-md, 8px); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.03); margin-bottom: .65rem; }
.sidebar-card-header { display: flex; align-items: center; gap: .5rem; padding: .5rem .75rem; border-bottom: 1px solid var(--border-soft); background: #fafafa; }
.sidebar-card-icon { width: 24px; height: 24px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: .78rem; flex-shrink: 0; }
.sidebar-card-title { font-size: .78rem; font-weight: 700; color: var(--text-dark); }
.sidebar-card-body { padding: .6rem .75rem; }

.guide-item { display: flex; gap: .5rem; padding: .3rem 0; border-bottom: 1px solid var(--border-soft); font-size: .74rem; color: var(--text-body); align-items: flex-start; }
.guide-item:last-child { border-bottom: none; }
.guide-item i { flex-shrink: 0; margin-top: .1rem; font-size: .8rem; }

/* Danger zone */
.danger-card {
    background: #fff; border: 1.5px solid #fca5a5;
    border-radius: var(--radius-md, 8px); overflow: hidden;
}
.danger-card-header {
    display: flex; align-items: center; gap: .5rem;
    padding: .5rem .75rem;
    background: #fef2f2; border-bottom: 1px solid #fca5a5;
}
.danger-card-body { padding: .6rem .75rem; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════ BANNER ══════════════════════════════ --}}
<div class="note-page-banner d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="d-flex align-items-center gap-2.5">
        <div class="banner-icon flex-shrink-0"><i class="bi bi-journal-text"></i></div>
        <div>
            <h1 class="mb-0">Edit Student Note</h1>
            <p class="mb-0">Modify the note for {{ $studentNote->student?->user?->name ?? 'this student' }}</p>
            <div class="meta-strip">
                <div class="meta-item"><i class="bi bi-calendar3"></i> Created {{ $studentNote->created_at?->format('d M Y, H:i') ?? '—' }}</div>
                <div class="meta-item"><i class="bi bi-arrow-clockwise"></i> Updated {{ $studentNote->updated_at?->format('d M Y, H:i') ?? '—' }}</div>
            </div>
        </div>
    </div>
    <div>
        <a href="{{ route('student-notes.show', $studentNote) }}" class="btn btn-sm btn-banner-back">
            <i class="bi bi-arrow-left me-1"></i>Back to Note
        </a>
    </div>
</div>

{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert-card alert-card-danger mb-4">
        <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
        <div>
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-1 ps-4">
                @foreach($errors->all() as $error)
                    <li style="font-size:.875rem">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('student-notes.update', $studentNote) }}" method="POST" novalidate id="noteForm">
@csrf
@method('PUT')

<div class="row g-2.5 align-items-start">

    {{-- ═══════════════ LEFT — MAIN FORM ═══════════════════════ --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- Section 1: Student & Type --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar"></div>
                    <span class="form-section-head-label">Student & Category</span>
                    <div class="form-section-head-line"></div>
                </div>
                <div class="row g-2">
                    <div class="col-md-7">
                        <label for="student_id" class="form-label-custom">Student <span class="required-mark">*</span></label>
                        <select name="student_id" id="student_id"
                                class="form-select fc @error('student_id') is-invalid @enderror" required>
                            <option value="">— Select student —</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                        {{ old('student_id', $studentNote->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->user?->name ?? 'Unknown' }}
                                    @if($student->admission_number) · {{ $student->admission_number }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>
                        @else<div class="field-hint"><i class="bi bi-person-circle"></i>The student this note is about</div>@enderror
                    </div>
                    <div class="col-md-5">
                        <label for="note_type" class="form-label-custom">Note Type</label>
                        <select name="note_type" id="note_type"
                                class="form-select fc @error('note_type') is-invalid @enderror">
                            <option value="">— Select type —</option>
                            @foreach(\App\Models\Students\StudentNote::NOTE_TYPES as $value => $label)
                                <option value="{{ $value }}"
                                        {{ old('note_type', $studentNote->note_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('note_type')<div class="invalid-feedback">{{ $message }}</div>
                        @else<div class="field-hint"><i class="bi bi-tag"></i>Category of this note</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Date & Visibility --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">Date & Visibility</span>
                    <div class="form-section-head-line"></div>
                </div>
                <div class="row g-2">
                    <div class="col-md-5">
                        <label for="note_date" class="form-label-custom">Note Date</label>
                        <input type="date" name="note_date" id="note_date"
                               class="form-control fc @error('note_date') is-invalid @enderror"
                               value="{{ old('note_date', $studentNote->note_date?->format('Y-m-d')) }}">
                        @error('note_date')<div class="invalid-feedback">{{ $message }}</div>
                        @else<div class="field-hint"><i class="bi bi-calendar3"></i>When this note was recorded</div>@enderror
                    </div>
                    <div class="col-md-7">
                        <label for="visibility" class="form-label-custom">Visibility</label>
                        <select name="visibility" id="visibility"
                                class="form-select fc @error('visibility') is-invalid @enderror">
                            @foreach(\App\Models\Students\StudentNote::VISIBILITY_OPTIONS as $value => $label)
                                <option value="{{ $value }}"
                                        {{ old('visibility', $studentNote->visibility ?? 'private') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('visibility')<div class="invalid-feedback">{{ $message }}</div>
                        @else<div class="field-hint"><i class="bi bi-eye"></i>Who can view this note</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Priority --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#f97316"></div>
                    <span class="form-section-head-label">Priority Level</span>
                    <div class="form-section-head-line"></div>
                </div>
                @php $editPriority = old('priority', $studentNote->priority ?? 'normal'); @endphp
                <div class="option-cards" role="group" aria-label="Priority">
                    <div class="option-card priority-low">
                        <input type="radio" name="priority" id="priority_low" value="low" {{ $editPriority === 'low' ? 'checked' : '' }}>
                        <label for="priority_low"><i class="bi bi-arrow-down-circle" style="color:#94a3b8"></i>Low</label>
                    </div>
                    <div class="option-card priority-normal">
                        <input type="radio" name="priority" id="priority_normal" value="normal" {{ $editPriority === 'normal' ? 'checked' : '' }}>
                        <label for="priority_normal"><i class="bi bi-dash-circle" style="color:#3b82f6"></i>Normal</label>
                    </div>
                    <div class="option-card priority-high">
                        <input type="radio" name="priority" id="priority_high" value="high" {{ $editPriority === 'high' ? 'checked' : '' }}>
                        <label for="priority_high"><i class="bi bi-arrow-up-circle-fill" style="color:#f97316"></i>High</label>
                    </div>
                    <div class="option-card priority-urgent">
                        <input type="radio" name="priority" id="priority_urgent" value="urgent" {{ $editPriority === 'urgent' ? 'checked' : '' }}>
                        <label for="priority_urgent"><i class="bi bi-exclamation-circle-fill" style="color:#ef4444"></i>Urgent</label>
                    </div>
                </div>
                @error('priority')<div class="text-danger mt-2" style="font-size:.8rem"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
            </div>

            {{-- Section 4: Status --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#16a34a"></div>
                    <span class="form-section-head-label">Note Status</span>
                    <div class="form-section-head-line"></div>
                </div>
                @php $editStatus = old('status', $studentNote->status ?? 'active'); @endphp
                <div class="option-cards" role="group" aria-label="Status">
                    <div class="option-card status-active">
                        <input type="radio" name="status" id="status_active" value="active" {{ $editStatus === 'active' ? 'checked' : '' }}>
                        <label for="status_active"><i class="bi bi-circle-fill" style="color:#16a34a;font-size:.6rem;margin-top:.2rem"></i>Active</label>
                    </div>
                    <div class="option-card status-resolved">
                        <input type="radio" name="status" id="status_resolved" value="resolved" {{ $editStatus === 'resolved' ? 'checked' : '' }}>
                        <label for="status_resolved"><i class="bi bi-check-circle-fill" style="color:#6366f1"></i>Resolved</label>
                    </div>
                    <div class="option-card status-archived">
                        <input type="radio" name="status" id="status_archived" value="archived" {{ $editStatus === 'archived' ? 'checked' : '' }}>
                        <label for="status_archived"><i class="bi bi-archive" style="color:#94a3b8"></i>Archived</label>
                    </div>
                </div>
                @error('status')<div class="text-danger mt-2" style="font-size:.8rem"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
            </div>

            {{-- Section 5: Note Content --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#8b5cf6"></div>
                    <span class="form-section-head-label">Note Content</span>
                    <div class="form-section-head-line"></div>
                </div>
                <label for="note" class="form-label-custom">Note <span class="required-mark">*</span></label>
                <textarea name="note" id="note"
                          class="note-textarea @error('note') is-invalid @enderror"
                          placeholder="Write a detailed note about this student…"
                          required>{{ old('note', $studentNote->note) }}</textarea>
                <div class="textarea-footer">
                    <span class="field-hint mb-0" style="margin-top:0"><i class="bi bi-pencil"></i>Be clear and professional</span>
                    <span class="word-count" id="wordCount">0 words</span>
                </div>
                @error('note')<div class="text-danger mt-2" style="font-size:.8rem"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror
            </div>

            {{-- Section 6: Tags --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#e8a838"></div>
                    <span class="form-section-head-label">Tags <span style="font-weight:400;letter-spacing:0">(optional)</span></span>
                    <div class="form-section-head-line"></div>
                </div>
                <label for="tags" class="form-label-custom">Tags</label>
                <input type="text" name="tags" id="tags"
                       class="form-control fc @error('tags') is-invalid @enderror"
                       placeholder="e.g. behavior, academic, health  (comma separated)"
                       value="{{ old('tags', $studentNote->tags) }}"
                       autocomplete="off">
                @error('tags')<div class="invalid-feedback">{{ $message }}</div>
                @else<div class="field-hint"><i class="bi bi-tags"></i>Separate tags with commas</div>@enderror
                <div class="tag-preview" id="tagPreview"></div>
            </div>

            {{-- Section 7: Options --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#d97706"></div>
                    <span class="form-section-head-label">Additional Options</span>
                    <div class="form-section-head-line"></div>
                </div>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="check-option" for="is_important" id="checkImportant">
                            <input type="checkbox" name="is_important" id="is_important" value="1"
                                   {{ old('is_important', $studentNote->is_important) ? 'checked' : '' }}>
                            <i class="bi bi-bookmark-star check-option-icon text-warning"></i>
                            <div>
                                <div class="check-option-title">Important Note</div>
                                <div class="check-option-desc">Flag this note for quick reference</div>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="check-option" for="follow_up_required" id="checkFollowUp">
                            <input type="checkbox" name="follow_up_required" id="follow_up_required" value="1"
                                   {{ old('follow_up_required', $studentNote->follow_up_required) ? 'checked' : '' }}>
                            <i class="bi bi-clock-history check-option-icon" style="color:#2563eb"></i>
                            <div>
                                <div class="check-option-title">Follow Up Required</div>
                                <div class="check-option-desc">A follow-up action is needed</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle" style="color:#0369a1"></i>
                    Fields marked <span class="required-mark mx-1">*</span> are required
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('student-notes.show', $studentNote) }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm">
                        <i class="bi bi-check-lg me-1"></i>Update Note
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════ RIGHT — SIDEBAR ═════════════════════════ --}}
    <div class="col-xl-4 col-lg-4">

        {{-- Guidelines --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="sidebar-card-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-lightbulb"></i></div>
                <div class="sidebar-card-title">Edit Guidelines</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-pencil-square text-primary"></i>
                    <span>Keep all edits objective and professional.</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-clock-history text-info"></i>
                    <span>The "Updated" timestamp is set automatically on save.</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-bookmark-star text-warning"></i>
                    <span>Update the priority if urgency has changed.</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-check-circle text-success"></i>
                    <span>Set status to "Resolved" when the issue is addressed.</span>
                </div>
            </div>
        </div>

        {{-- Quick nav --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="sidebar-card-icon" style="background:#f0f9ff;color:#0369a1"><i class="bi bi-list-ul"></i></div>
                <div class="sidebar-card-title">Quick Navigation</div>
            </div>
            <div class="sidebar-card-body p-0">
                <a href="{{ route('student-notes.show', $studentNote) }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" style="font-size:.8rem">
                    <i class="bi bi-eye text-muted"></i>View This Note
                </a>
                <a href="{{ route('student-notes.index') }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" style="font-size:.8rem">
                    <i class="bi bi-journal-text text-muted"></i>All Student Notes
                </a>
                <a href="{{ route('student-notes.create') }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" style="font-size:.8rem">
                    <i class="bi bi-plus-lg text-muted"></i>Add New Note
                </a>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="danger-card">
            <div class="danger-card-header">
                <div class="sidebar-card-icon" style="background:#fee2e2;color:#dc2626"><i class="bi bi-trash"></i></div>
                <div class="sidebar-card-title" style="color:#dc2626">Danger Zone</div>
            </div>
            <div class="danger-card-body">
                <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:.85rem">
                    Permanently remove this note. This action <strong>cannot be undone</strong>.
                </p>
                <button type="button" class="btn btn-sm btn-outline-danger w-100"
                        data-bs-toggle="modal" data-bs-target="#delModal">
                    <i class="bi bi-trash me-1"></i>Delete This Note
                </button>
            </div>
        </div>

    </div>
</div>
</form>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="delModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10"
                          style="width:52px;height:52px">
                        <i class="bi bi-trash text-danger" style="font-size:1.3rem"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1">Delete Note?</h6>
                <p class="text-muted small mb-4">
                    This note for <strong>{{ $studentNote->student?->user?->name ?? 'this student' }}</strong> will be permanently deleted.
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

@section('scripts')
<script>
(function () {
    // Word counter
    const noteEl  = document.getElementById('note');
    const countEl = document.getElementById('wordCount');
    function updateWordCount() {
        const trimmed = noteEl.value.trim();
        const count   = trimmed === '' ? 0 : trimmed.split(/\s+/).filter(Boolean).length;
        countEl.textContent = count + (count === 1 ? ' word' : ' words');
    }
    noteEl.addEventListener('input', updateWordCount);
    updateWordCount();

    // Live tag pills
    const tagsEl  = document.getElementById('tags');
    const preview = document.getElementById('tagPreview');
    function renderTags() {
        const tags = tagsEl.value.split(',').map(t => t.trim()).filter(t => t.length > 0);
        preview.innerHTML = tags.map(t =>
            `<span class="tag-pill"><i class="bi bi-tag" style="font-size:.62rem"></i>${t}</span>`
        ).join('');
    }
    tagsEl.addEventListener('input', renderTags);
    renderTags();

    // Checkbox card highlight
    document.querySelectorAll('.check-option input[type="checkbox"]').forEach(cb => {
        const card = cb.closest('.check-option');
        if (cb.checked) card.classList.add('checked');
        cb.addEventListener('change', () => card.classList.toggle('checked', cb.checked));
    });
})();
</script>
@endsection
