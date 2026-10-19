@extends('layouts.app')

@section('title', 'បង្កើតកំណត់សម្គាល់សិស្ស — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">សេវាកម្មសិស្ស</li>
        <li class="breadcrumb-item"><a href="{{ route('student-notes.index') }}" class="text-decoration-none text-muted">កំណត់សម្គាល់សិស្ស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បង្កើតថ្មី</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER BANNER (Gold/Yellow Theme)
═══════════════════════════════════════════════════════════════ */
.note-page-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-md, 8px);
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.85rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(232, 168, 56, 0.18);
}
.note-page-banner::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 120px; height: 120px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    pointer-events: none;
}
.note-page-banner::after {
    content: '';
    position: absolute;
    bottom: -25px; right: 70px;
    width: 80px; height: 80px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
    pointer-events: none;
}
.note-page-banner .banner-icon {
    width: 36px; height: 36px;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff;
    margin-bottom: 0;
    flex-shrink: 0;
}
.note-page-banner h1 {
    font-size: 1.15rem; font-weight: 700;
    color: #fff; margin: 0 0 .15rem;
    line-height: 1.25;
}
.note-page-banner p {
    font-size: .78rem; color: rgba(255, 255, 255, 0.92);
    margin: 0; line-height: 1.3;
}
.note-page-banner .btn-banner-back {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.35);
    font-size: .78rem;
    border-radius: 6px;
    padding: 0.32rem 0.75rem;
    transition: all .15s ease;
}
.note-page-banner .btn-banner-back:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #fff;
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-1px);
}

/* ═══════════════════════════════════════════════════════════════
   FORM CARD (Compact)
═══════════════════════════════════════════════════════════════ */
.note-form-card {
    background: var(--form-card-bg, #fff);
    border: 1px solid var(--form-card-border, #e2e8f0);
    border-top: 3px solid #e8a838;
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* Section heading with colored left bar */
.form-section-head {
    display: flex; align-items: center; gap: .5rem;
    margin-bottom: .55rem;
}
.form-section-head-bar {
    width: 3px; height: .85rem;
    border-radius: 999px;
    background: var(--gold);
    flex-shrink: 0;
}
.form-section-head-label {
    font-size: .85rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text-muted);
}
.form-section-head-line {
    flex: 1; height: 1px;
    background: var(--border-soft);
}

/* Section wrapper with compact padding + divider */
.form-section {
    padding: .85rem 1.15rem;
    border-bottom: 1px solid var(--border-soft);
}
.form-section:last-child { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════
   FIELD STYLES (Standardized for Khmer)
═══════════════════════════════════════════════════════════════ */
.form-label-custom {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: .35rem;
    display: block;
}
.field-hint {
    display: flex; align-items: center; gap: .25rem;
    font-size: 0.875rem; color: var(--text-muted); margin-top: .25rem;
}

.form-select.fc, .form-control.fc {
    border-color: #cbd5e1;
    font-size: 0.95rem;
    border-radius: var(--radius-sm, 6px);
    color: var(--text-dark);
    background-color: var(--form-input-bg, #fff);
    transition: border-color .15s, box-shadow .15s;
    padding: .42rem .85rem;
    height: 42px;
}
.form-select.fc { padding-right: 2rem; }
.form-select.fc:focus, .form-control.fc:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px var(--gold-subtle);
    outline: none;
}
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

/* ═══════════════════════════════════════════════════════════════
   VISUAL RADIO BUTTON CARDS (Compact)
═══════════════════════════════════════════════════════════════ */
.option-cards { display: flex; gap: .45rem; flex-wrap: wrap; }

.option-card {
    position: relative;
    flex: 1; min-width: 75px;
}
.option-card input[type="radio"] {
    position: absolute; opacity: 0; width: 0; height: 0;
}
.option-card label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .2rem; cursor: pointer;
    border: 1.5px solid #e2e8f0;
    border-radius: 6px;
    padding: .4rem .35rem;
    font-size: .72rem; font-weight: 600;
    color: var(--text-muted);
    background: #fafbfc;
    transition: all .15s ease;
    text-align: center;
    line-height: 1.2;
    user-select: none;
}
.option-card label i {
    font-size: .88rem;
    margin-bottom: 0;
}
.option-card label:hover {
    border-color: var(--gold);
    background: var(--gold-light);
    color: var(--gold-dark);
}
.option-card input:checked + label {
    border-width: 1.5px;
    font-weight: 700;
    box-shadow: 0 1px 4px rgba(0,0,0,.08);
}

/* Priority variants */
.option-card.priority-low   input:checked + label { border-color:#94a3b8; background:#f8fafc; color:#475569; }
.option-card.priority-normal input:checked + label { border-color:#3b82f6; background:#eff6ff; color:#1d4ed8; }
.option-card.priority-high  input:checked + label { border-color:#f97316; background:#fff7ed; color:#c2410c; }
.option-card.priority-urgent input:checked + label { border-color:#ef4444; background:#fef2f2; color:#dc2626; }

/* Status variants */
.option-card.status-active   input:checked + label { border-color:#16a34a; background:#f0fdf4; color:#15803d; }
.option-card.status-resolved input:checked + label { border-color:#6366f1; background:#eef2ff; color:#4338ca; }
.option-card.status-archived input:checked + label { border-color:#94a3b8; background:#f8fafc; color:#475569; }

/* ═══════════════════════════════════════════════════════════════
   TEXTAREA (Compact)
═══════════════════════════════════════════════════════════════ */
.note-textarea {
    min-height: 95px; resize: vertical; font-size: 0.95rem; line-height: 1.55;
    border: 1px solid #cbd5e1; border-radius: var(--radius-sm, 6px) var(--radius-sm, 6px) 0 0;
    padding: .6rem .85rem; width: 100%; color: var(--text-dark);
    transition: border-color .15s, box-shadow .15s; background: var(--form-input-bg, #fff);
    font-family: inherit;
}
.note-textarea:focus {
    border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none;
}
.note-textarea.is-invalid { border-color: #ef4444; }
.textarea-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: .35rem .85rem; background: #f8fafc;
    border: 1px solid #cbd5e1; border-top: none;
    border-radius: 0 0 var(--radius-sm, 6px) var(--radius-sm, 6px);
}
.textarea-footer .word-count { font-size: .8rem; color: var(--text-muted); font-variant-numeric: tabular-nums; }

/* ═══════════════════════════════════════════════════════════════
   TAGS
═══════════════════════════════════════════════════════════════ */
.tag-preview { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .35rem; min-height: 0; }
.tag-pill {
    display: inline-flex; align-items: center; gap: .25rem;
    background: var(--gold-light); color: var(--gold-dark);
    border-radius: 999px; padding: .2rem .65rem;
    font-size: .82rem; font-weight: 500; animation: tagIn .15s ease;
}
@keyframes tagIn { from { transform: scale(.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

/* ═══════════════════════════════════════════════════════════════
   CHECKBOX ROWS
═══════════════════════════════════════════════════════════════ */
.check-option {
    display: flex; align-items: flex-start; gap: .65rem;
    padding: .65rem .85rem;
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    cursor: pointer;
    transition: all .15s;
    background: #f1f5f9;
}
.check-option:hover { border-color: var(--gold); background: #fffbeb; }
.check-option.checked { border-color: var(--gold); background: #fef3c7; }
.check-option input[type="checkbox"] {
    width: 1.05rem; height: 1.05rem; flex-shrink: 0;
    cursor: pointer; margin-top: .15rem;
    accent-color: var(--gold);
}
.check-option input[type="checkbox"]:focus { outline: 3px solid var(--gold-subtle); }
.check-option-title { font-size: .88rem; font-weight: 600; color: var(--text-dark); }
.check-option-desc  { font-size: .8rem; color: var(--text-muted); margin-top: .1rem; }
.check-option-icon  { font-size: 1.05rem; flex-shrink: 0; margin-top: .05rem; }

/* ═══════════════════════════════════════════════════════════════
   FORM ACTIONS BAR
═══════════════════════════════════════════════════════════════ */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.15rem;
    background: #f8fafc;
    border-top: 1px solid var(--border-soft);
}
.btn-primary-action {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    color: #fff;
    border: none;
    font-size: .95rem;
    font-weight: 600;
    padding: .42rem 1.15rem;
    height: 42px;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(232, 168, 56, 0.3);
}
.btn-primary-action:hover {
    background: linear-gradient(135deg, #df9b28 0%, #b87c14 100%);
    color: #fff;
}

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR CARDS (Compact)
═══════════════════════════════════════════════════════════════ */
.sidebar-card {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.03);
    margin-bottom: .65rem;
}
.sidebar-card-header {
    display: flex; align-items: center; gap: .5rem;
    padding: .5rem .75rem;
    border-bottom: 1px solid var(--border-soft);
    background: #fafafa;
}
.sidebar-card-icon {
    width: 24px; height: 24px; border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; flex-shrink: 0;
}
.sidebar-card-title { font-size: .78rem; font-weight: 700; color: var(--text-dark); }
.sidebar-card-body  { padding: .6rem .75rem; }

.guide-item {
    display: flex; gap: .5rem;
    padding: .3rem 0; border-bottom: 1px solid var(--border-soft);
    font-size: .74rem; color: var(--text-body); align-items: flex-start;
}
.guide-item:last-child { border-bottom: none; }
.guide-item i { flex-shrink: 0; margin-top: .1rem; font-size: .8rem; }

/* Summary preview card */
.summary-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: .28rem 0; border-bottom: 1px dashed var(--border-soft);
    font-size: .74rem;
}
.summary-item:last-child { border-bottom: none; }
.summary-item .label  { color: var(--text-muted); }
.summary-item .value  { font-weight: 600; color: var(--text-dark); }

/* Required note */
.required-note {
    display: flex; align-items: center; gap: .4rem;
    font-size: .71rem; color: var(--text-muted);
    padding: .3rem .6rem;
    background: #fff;
    border: 1px dashed #cbd5e1;
    border-radius: var(--radius-sm, 6px);
}
.required-mark { color: #ef4444; font-weight: 700; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════ BANNER ═════════════════════════════ --}}
<div class="note-page-banner d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="d-flex align-items-center gap-2.5">
        <div class="banner-icon flex-shrink-0"><i class="bi bi-journal-plus"></i></div>
        <div>
            <h1>បង្កើតកំណត់សម្គាល់សិស្សថ្មី</h1>
            <p>កត់ត្រាកំណត់សម្គាល់បន្ថែមទៅក្នុងប្រវត្តិផ្ទាល់ខ្លួនរបស់សិស្ស</p>
        </div>
    </div>
    <div>
        <a href="{{ route('student-notes.index') }}" class="btn btn-sm btn-banner-back">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅកាន់កំណត់សម្គាល់
        </a>
    </div>
</div>

{{-- ═══════════════════════ VALIDATION ERRORS ══════════════════════ --}}
@if($errors->any())
    <div class="alert-card alert-card-danger mb-4">
        <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
        <div>
            <strong>សូមពិនិត្យ និងកែតម្រូវកំហុសឆ្គងខាងក្រោម៖</strong>
            <ul class="mb-0 mt-1 ps-4">
                @foreach($errors->all() as $error)
                    <li style="font-size:.875rem">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('student-notes.store') }}" method="POST" novalidate id="noteForm">
@csrf

<div class="row g-2.5 align-items-start">

    {{-- ══════════════════ LEFT — MAIN FORM (8 cols) ══════════════════ --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- ── Section 1: Student & Type ───────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar"></div>
                    <span class="form-section-head-label">ព័ត៌មានសិស្ស និងប្រភេទ</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-2">

                    {{-- Student --}}
                    <div class="col-md-7">
                        <label for="student_id" class="form-label-custom">
                            សិស្ស <span class="required-mark">*</span>
                        </label>
                        <select name="student_id" id="student_id"
                                class="form-select fc @error('student_id') is-invalid @enderror"
                                required>
                            <option value="">— ជ្រើសរើសសិស្ស —</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user?->name ?? 'មិនស្គាល់' }}
                                    @if($student->admission_number) · {{ $student->admission_number }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint"><i class="bi bi-person-circle"></i>សិស្សដែលត្រូវកត់ត្រាកំណត់សម្គាល់</div>
                        @enderror
                    </div>

                    {{-- Note Type --}}
                    <div class="col-md-5">
                        <label for="note_type" class="form-label-custom">ប្រភេទកំណត់សម្គាល់</label>
                        <select name="note_type" id="note_type"
                                class="form-select fc @error('note_type') is-invalid @enderror">
                            <option value="">— ជ្រើសរើសប្រភេទ —</option>
                            @foreach(\App\Models\Students\StudentNote::NOTE_TYPES as $value => $label)
                                <option value="{{ $value }}" {{ old('note_type') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('note_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint"><i class="bi bi-tag"></i>ចំណាត់ថ្នាក់ប្រភេទនៃកំណត់សម្គាល់នេះ</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Section 2: Date, Visibility ─────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">កាលបរិច្ឆេទ និងភាពមើលឃើញ</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-2">

                    {{-- Date --}}
                    <div class="col-md-5">
                        <label for="note_date" class="form-label-custom">កាលបរិច្ឆេទ</label>
                        <input type="date" name="note_date" id="note_date"
                                class="form-control fc @error('note_date') is-invalid @enderror"
                                value="{{ old('note_date', now()->format('Y-m-d')) }}">
                        @error('note_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint"><i class="bi bi-calendar3"></i>កាលបរិច្ឆេទដែលបានកត់ត្រាទុក</div>
                        @enderror
                    </div>

                    {{-- Visibility --}}
                    <div class="col-md-7">
                        <label for="visibility" class="form-label-custom">ភាពមើលឃើញ (Visibility)</label>
                        <select name="visibility" id="visibility"
                                class="form-select fc @error('visibility') is-invalid @enderror">
                            @foreach(\App\Models\Students\StudentNote::VISIBILITY_OPTIONS as $value => $label)
                                <option value="{{ $value }}"
                                        {{ old('visibility', 'private') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('visibility')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint"><i class="bi bi-eye"></i>អ្នកដែលអាចមើលឃើញកំណត់សម្គាល់នេះបាន</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Section 3: Priority ──────────────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#f97316"></div>
                    <span class="form-section-head-label">កម្រិតអាទិភាព</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="option-cards" role="group" aria-label="Priority">
                    @php $oldPriority = old('priority', 'normal'); @endphp

                    <div class="option-card priority-low">
                        <input type="radio" name="priority" id="priority_low" value="low"
                               {{ $oldPriority === 'low' ? 'checked' : '' }}>
                        <label for="priority_low">
                            <i class="bi bi-arrow-down-circle" style="color:#94a3b8"></i>ទាប (Low)
                        </label>
                    </div>

                    <div class="option-card priority-normal">
                        <input type="radio" name="priority" id="priority_normal" value="normal"
                               {{ $oldPriority === 'normal' ? 'checked' : '' }}>
                        <label for="priority_normal">
                            <i class="bi bi-dash-circle" style="color:#3b82f6"></i>ធម្មតា (Normal)
                        </label>
                    </div>

                    <div class="option-card priority-high">
                        <input type="radio" name="priority" id="priority_high" value="high"
                               {{ $oldPriority === 'high' ? 'checked' : '' }}>
                        <label for="priority_high">
                            <i class="bi bi-arrow-up-circle-fill" style="color:#f97316"></i>ខ្ពស់ (High)
                        </label>
                    </div>

                    <div class="option-card priority-urgent">
                        <input type="radio" name="priority" id="priority_urgent" value="urgent"
                               {{ $oldPriority === 'urgent' ? 'checked' : '' }}>
                        <label for="priority_urgent">
                            <i class="bi bi-exclamation-circle-fill" style="color:#ef4444"></i>បន្ទាន់ (Urgent)
                        </label>
                    </div>
                </div>
                @error('priority')
                    <div class="text-danger mt-2" style="font-size:.8rem"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Section 4: Status ────────────────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#16a34a"></div>
                    <span class="form-section-head-label">ស្ថានភាពកំណត់សម្គាល់</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="option-cards" role="group" aria-label="Status">
                    @php $oldStatus = old('status', 'active'); @endphp

                    <div class="option-card status-active">
                        <input type="radio" name="status" id="status_active" value="active"
                               {{ $oldStatus === 'active' ? 'checked' : '' }}>
                        <label for="status_active">
                            <i class="bi bi-circle-fill" style="color:#16a34a;font-size:.6rem;margin-top:.2rem"></i>សកម្ម (Active)
                        </label>
                    </div>

                    <div class="option-card status-resolved">
                        <input type="radio" name="status" id="status_resolved" value="resolved"
                               {{ $oldStatus === 'resolved' ? 'checked' : '' }}>
                        <label for="status_resolved">
                            <i class="bi bi-check-circle-fill" style="color:#6366f1"></i>ដោះស្រាយរួច (Resolved)
                        </label>
                    </div>

                    <div class="option-card status-archived">
                        <input type="radio" name="status" id="status_archived" value="archived"
                               {{ $oldStatus === 'archived' ? 'checked' : '' }}>
                        <label for="status_archived">
                            <i class="bi bi-archive" style="color:#94a3b8"></i>ប័ណ្ណសារ (Archived)
                        </label>
                    </div>
                </div>
                @error('status')
                    <div class="text-danger mt-2" style="font-size:.8rem"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Section 5: Note Content ──────────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#8b5cf6"></div>
                    <span class="form-section-head-label">ខ្លឹមសារកំណត់សម្គាល់</span>
                    <div class="form-section-head-line"></div>
                </div>

                <label for="note" class="form-label-custom">
                    ខ្លឹមសារ <span class="required-mark">*</span>
                </label>
                <textarea name="note" id="note"
                          class="note-textarea @error('note') is-invalid @enderror"
                          placeholder="សរសេរព័ត៌មានលម្អិតអំពីកំណត់សម្គាល់របស់សិស្ស…"
                          required>{{ old('note') }}</textarea>
                <div class="textarea-footer">
                    <span class="field-hint mb-0" style="margin-top:0"><i class="bi bi-pencil"></i>សរសេរឱ្យបានច្បាស់លាស់ និងប្រកបដោយវិជ្ជាជីវៈ</span>
                    <span class="word-count" id="wordCount">0 ពាក្យ</span>
                </div>
                @error('note')
                    <div class="text-danger mt-2" style="font-size:.8rem"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Section 6: Tags ──────────────────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#e8a838"></div>
                    <span class="form-section-head-label">ស្លាកសម្គាល់ Tags <span style="font-weight:400;letter-spacing:0">(ស្រេចចិត្ត)</span></span>
                    <div class="form-section-head-line"></div>
                </div>

                <label for="tags" class="form-label-custom">ស្លាកសម្គាល់</label>
                <input type="text" name="tags" id="tags"
                       class="form-control fc @error('tags') is-invalid @enderror"
                       placeholder="ឧ. អាកប្បកិរិយា, ការសិក្សា, សុខភាព (ខណ្ឌដោយសញ្ញាក្បៀស)"
                       value="{{ old('tags') }}"
                       autocomplete="off">
                @error('tags')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="field-hint"><i class="bi bi-tags"></i>ខណ្ឌស្លាកនីមួយៗដោយប្រើសញ្ញាក្បៀស (,)</div>
                @enderror
                <div class="tag-preview" id="tagPreview"></div>
            </div>

            {{-- ── Section 7: Options ───────────────────────────────────── --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#d97706"></div>
                    <span class="form-section-head-label">ជម្រើសបន្ថែម</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-2">
                    {{-- Important --}}
                    <div class="col-md-6">
                        <label class="check-option" for="is_important" id="checkImportant">
                            <input type="checkbox" name="is_important" id="is_important" value="1"
                                   {{ old('is_important') ? 'checked' : '' }}>
                            <i class="bi bi-bookmark-star check-option-icon text-warning"></i>
                            <div>
                                <div class="check-option-title">កំណត់សម្គាល់សំខាន់ (Important)</div>
                                <div class="check-option-desc">ដាក់ចំណាំកំណត់សម្គាល់នេះដើម្បីងាយស្រួលរកមើល</div>
                            </div>
                        </label>
                    </div>

                    {{-- Follow Up --}}
                    <div class="col-md-6">
                        <label class="check-option" for="follow_up_required" id="checkFollowUp">
                            <input type="checkbox" name="follow_up_required" id="follow_up_required" value="1"
                                   {{ old('follow_up_required') ? 'checked' : '' }}>
                            <i class="bi bi-clock-history check-option-icon" style="color:#2563eb"></i>
                            <div>
                                <div class="check-option-title">ទាមទារការតាមដានបន្ត (Follow Up)</div>
                                <div class="check-option-desc">ចាំបាច់ត្រូវមានសកម្មភាពតាមដានបន្ត</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ── Form Footer (Actions) ────────────────────────────────── --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle" style="color:#0369a1"></i>
                    ប្រអប់ដែលមានសញ្ញា <span class="required-mark mx-1">*</span> គឺចាំបាច់ត្រូវបំពេញ
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('student-notes.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm">
                        <i class="bi bi-journal-check me-1"></i>រក្សាទុកកំណត់សម្គាល់
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════ RIGHT — SIDEBAR (4 cols) ════════════════════ --}}
    <div class="col-xl-4 col-lg-4">

        {{-- Live Summary --}}
        <div class="sidebar-card mb-3">
            <div class="sidebar-card-header">
                <div class="sidebar-card-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-eye"></i></div>
                <div class="sidebar-card-title">សង្ខេបកំណត់សម្គាល់</div>
            </div>
            <div class="sidebar-card-body">
                <div class="summary-item">
                    <span class="label">សិស្ស</span>
                    <span class="value" id="summaryStudent">—</span>
                </div>
                <div class="summary-item">
                    <span class="label">ប្រភេទ</span>
                    <span class="value" id="summaryType">—</span>
                </div>
                <div class="summary-item">
                    <span class="label">កាលបរិច្ឆេទ</span>
                    <span class="value" id="summaryDate">—</span>
                </div>
                <div class="summary-item">
                    <span class="label">អាទិភាព</span>
                    <span class="value" id="summaryPriority">ធម្មតា</span>
                </div>
                <div class="summary-item">
                    <span class="label">ស្ថានភាព</span>
                    <span class="value" id="summaryStatus">សកម្ម</span>
                </div>
            </div>
        </div>

        {{-- Guidelines --}}
        <div class="sidebar-card mb-3">
            <div class="sidebar-card-header">
                <div class="sidebar-card-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-lightbulb"></i></div>
                <div class="sidebar-card-title">គោលការណ៍ណែនាំ</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-pencil-square text-primary"></i>
                    <span>សរសេរឱ្យបានច្បាស់លាស់ ផ្អែកលើការពិត និងប្រកបដោយវិជ្ជាជីវៈ។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-shield-check text-success"></i>
                    <span>កំណត់សម្គាល់អាចចូលមើលបានតែបុគ្គលិកដែលមានសិទ្ធិប៉ុណ្ណោះ។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-bookmark-star text-warning"></i>
                    <span>ប្រើ "សំខាន់" សម្រាប់កំណត់សម្គាល់ដែលត្រូវការការយកចិត្តទុកដាក់ខ្ពស់។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-clock-history text-info"></i>
                    <span>បើក "ការតាមដានបន្ត" សម្រាប់ករណីដែលត្រូវការចំណាត់ការបន្ត។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                    <span>ចៀសវាងការកត់ត្រាទិន្នន័យផ្ទាល់ខ្លួនដែលមិនចាំបាច់ ឬរសើបខ្លាំង។</span>
                </div>
            </div>
        </div>

        {{-- Quick nav --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="sidebar-card-icon" style="background:#f0f9ff;color:#0369a1"><i class="bi bi-list-ul"></i></div>
                <div class="sidebar-card-title">រុករកទំព័រ</div>
            </div>
            <div class="sidebar-card-body p-0">
                <a href="{{ route('student-notes.index') }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" style="font-size:.8rem">
                    <i class="bi bi-journal-text text-muted"></i>កំណត់សម្គាល់សិស្សទាំងអស់
                </a>
                <a href="{{ route('students.index') }}"
                   class="dropdown-item py-2 px-3 d-flex align-items-center gap-2" style="font-size:.8rem">
                    <i class="bi bi-people text-muted"></i>បញ្ជីឈ្មោះសិស្ស
                </a>
            </div>
        </div>

    </div>
</div>
</form>

@endsection

@section('scripts')
<script>
(function () {
    // ── Word counter ────────────────────────────────────────────
    const noteEl  = document.getElementById('note');
    const countEl = document.getElementById('wordCount');

    function updateWordCount() {
        const trimmed = noteEl.value.trim();
        const count   = trimmed === '' ? 0 : trimmed.split(/\s+/).filter(Boolean).length;
        countEl.textContent = count + ' ពាក្យ';
    }
    noteEl.addEventListener('input', updateWordCount);
    updateWordCount();

    // ── Live tag pills ──────────────────────────────────────────
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

    // ── Checkbox card highlight ─────────────────────────────────
    document.querySelectorAll('.check-option input[type="checkbox"]').forEach(cb => {
        const card = cb.closest('.check-option');
        if (cb.checked) card.classList.add('checked');
        cb.addEventListener('change', () => card.classList.toggle('checked', cb.checked));
    });

    // ── Live summary panel ──────────────────────────────────────
    const studentSel  = document.getElementById('student_id');
    const typeSel     = document.getElementById('note_type');
    const dateSel     = document.getElementById('note_date');

    const summaryStudent  = document.getElementById('summaryStudent');
    const summaryType     = document.getElementById('summaryType');
    const summaryDate     = document.getElementById('summaryDate');
    const summaryPriority = document.getElementById('summaryPriority');
    const summaryStatus   = document.getElementById('summaryStatus');

    function updateSummary() {
        const selOpt = studentSel.options[studentSel.selectedIndex];
        summaryStudent.textContent = (studentSel.value && selOpt) ? selOpt.text.split('·')[0].trim() : '—';

        const typeOpt = typeSel.options[typeSel.selectedIndex];
        summaryType.textContent = typeSel.value ? typeOpt.text : '—';

        summaryDate.textContent = dateSel.value
            ? new Date(dateSel.value).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })
            : '—';

        const checkedPriority = document.querySelector('input[name="priority"]:checked');
        summaryPriority.textContent = checkedPriority
            ? checkedPriority.nextElementSibling.textContent.trim()
            : '—';

        const checkedStatus = document.querySelector('input[name="status"]:checked');
        summaryStatus.textContent = checkedStatus
            ? checkedStatus.nextElementSibling.textContent.trim()
            : '—';
    }

    studentSel.addEventListener('change', updateSummary);
    typeSel.addEventListener('change', updateSummary);
    dateSel.addEventListener('change', updateSummary);
    document.querySelectorAll('input[name="priority"], input[name="status"]')
            .forEach(r => r.addEventListener('change', updateSummary));
    updateSummary();
})();
</script>
@endsection
