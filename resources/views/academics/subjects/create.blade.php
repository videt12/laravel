@extends('layouts.app')

@section('title', 'Create Subject — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Academic</li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}" class="text-decoration-none text-muted">Subjects</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Create Subject</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER BANNER
═══════════════════════════════════════════════════════════════ */
.note-page-banner {
    background: linear-gradient(135deg, #b45309 0%, #d97706 45%, #f59e0b 80%, #fbbf24 100%);
    border-radius: var(--radius-lg); padding: 1.6rem 1.75rem; margin-bottom: 1.75rem;
    position: relative; overflow: hidden;
    border: 1px solid rgba(251, 191, 36, 0.5);
    box-shadow: 0 4px 20px rgba(217, 119, 6, 0.25);
}
.note-page-banner::before {
    content: ''; position: absolute; top: -40px; right: -40px; width: 160px; height: 160px;
    background: rgba(255, 255, 255, 0.2); border-radius: 50%;
}
.note-page-banner::after {
    content: ''; position: absolute; bottom: -30px; right: 80px; width: 100px; height: 100px;
    background: rgba(255, 255, 255, 0.12); border-radius: 50%;
}
.note-page-banner .banner-icon {
    width: 44px; height: 44px; background: rgba(255, 255, 255, 0.25); border: 1.5px solid rgba(255, 255, 255, 0.45);
    border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: #ffffff; margin-bottom: 0.85rem;
    box-shadow: 0 2px 8px rgba(180, 83, 9, 0.25);
}
.note-page-banner h1 { font-size: 1.35rem; font-weight: 700; color: #ffffff; margin: 0 0 .25rem; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25); }
.note-page-banner p { font-size: .84rem; color: rgba(255, 255, 255, 0.95); margin: 0; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); }
.note-page-banner .banner-actions { position: absolute; top: 1.25rem; right: 1.25rem; z-index: 2; }

/* ═══════════════════════════════════════════════════════════════
   FORM CARD
═══════════════════════════════════════════════════════════════ */
.note-form-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 12px rgba(0,0,0,.04);
}
.form-section-head { display: flex; align-items: center; gap: .7rem; margin-bottom: 1.1rem; }
.form-section-head-bar { width: 3px; height: 1rem; border-radius: 999px; background: var(--gold); flex-shrink: 0; }
.form-section-head-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--text-muted); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }
.form-section { padding: 1.35rem 1.5rem; border-bottom: 1px solid var(--border-soft); }
.form-section:last-child { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════
   FIELD STYLES
═══════════════════════════════════════════════════════════════ */
.field-hint { display: flex; align-items: center; gap: .3rem; font-size: .73rem; color: var(--text-muted); margin-top: .3rem; }
.form-select.fc, .form-control.fc {
    border-color: #e2e8f0; font-size: .875rem; border-radius: var(--radius-sm); color: var(--text-dark); background-color: #fff;
    transition: border-color .15s, box-shadow .15s; padding: .48rem .85rem;
}
.form-select.fc { padding-right: 2.25rem; }
.form-select.fc:focus, .form-control.fc:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

/* ═══════════════════════════════════════════════════════════════
   TYPE CARDS (RADIO)
═══════════════════════════════════════════════════════════════ */
.type-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
.type-card {
    position: relative; border: 1px solid var(--border); border-radius: var(--radius-md);
    padding: 0.85rem; cursor: pointer; transition: all 0.2s; background: #fff;
    display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.4rem;
}
.type-card:hover { border-color: #cbd5e1; background: #f8fafc; }
.type-radio { position: absolute; opacity: 0; }
.type-radio:checked + .type-card { border-color: var(--gold); background: var(--gold-subtle); box-shadow: 0 0 0 1px var(--gold); }
.type-icon { font-size: 1.25rem; line-height: 1; }
.type-label { font-weight: 600; font-size: 0.8rem; color: var(--text-dark); }
.type-desc { font-size: 0.65rem; color: var(--text-muted); line-height: 1.2; }

/* ═══════════════════════════════════════════════════════════════
   FORM ACTIONS BAR
═══════════════════════════════════════════════════════════════ */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-soft);
}
.required-note {
    display: flex; align-items: center; gap: .5rem; font-size: .75rem; color: var(--text-muted);
    padding: .65rem .9rem; background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: var(--radius-sm);
}
.required-mark { color: #ef4444; font-weight: 700; }

/* SIDEBAR CARDS */
.sidebar-card { background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.03); margin-bottom: 1rem; }
.sidebar-card-header { display: flex; align-items: center; gap: .65rem; padding: .9rem 1.1rem; border-bottom: 1px solid var(--border-soft); background: #fafafa; }
.sidebar-card-icon { width: 30px; height: 30px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; }
.sidebar-card-title { font-size: .82rem; font-weight: 700; color: var(--text-dark); }
.sidebar-card-body  { padding: .85rem 1.1rem; }
.guide-item { display: flex; gap: .65rem; padding: .5rem 0; border-bottom: 1px solid var(--border-soft); font-size: .79rem; color: var(--text-body); align-items: flex-start; }
.guide-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="banner-actions">
        <a href="{{ route('subjects.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);font-size:.78rem">
            <i class="bi bi-arrow-left me-1"></i>Back to Subjects
        </a>
    </div>
    <div class="banner-icon"><i class="bi bi-book"></i></div>
    <h1>Create New Subject</h1>
    <p>Define a new subject to be included in the school curriculum.</p>
</div>

{{-- VALIDATION ERRORS --}}
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

<form action="{{ route('subjects.store') }}" method="POST" novalidate>
@csrf

<div class="row g-4 align-items-start">
    {{-- LEFT — MAIN FORM --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- Section 1: Basic Info --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">Subject Identification</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="code" class="form-label-custom">
                            កូដមុខវិជ្ជា (Subject Code / ID) <span class="required-mark">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-lock-fill text-warning"></i>
                            </span>
                            <input type="text" name="code" id="code"
                                   class="form-control fc @error('code') is-invalid @enderror font-monospace bg-light border-start-0 fw-bold"
                                   value="{{ old('code', $nextCode ?? '') }}" required readonly tabindex="-1" placeholder="e.g. S001" style="cursor: not-allowed;">
                        </div>
                        <div class="form-text text-muted small mt-1" style="font-size: 0.74rem;">
                            <i class="bi bi-lock-fill text-warning me-1"></i>បង្កើតដោយស្វ័យប្រវត្តិ — ចាក់សោ (Locked) មិនអាចកែប្រែបានទេ
                        </div>
                    </div>

                    <div class="col-md-7">
                        <label for="name" class="form-label-custom">
                            Subject Name <span class="required-mark">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               class="form-control fc @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required placeholder="e.g. Mathematics">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint">The full descriptive name of the subject</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Subject Type --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#8b5cf6"></div>
                    <span class="form-section-head-label">Subject Type</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="type-options">
                    <label>
                        <input type="radio" name="type" value="Core" class="type-radio" {{ old('type', 'Core') == 'Core' ? 'checked' : '' }}>
                        <div class="type-card">
                            <div class="type-icon" style="color:#3730a3"><i class="bi bi-journal-bookmark-fill"></i></div>
                            <div class="type-label">មុខវិជ្ជាស្នូល (Core)</div>
                            <div class="type-desc">មុខវិជ្ជាស្នូលនៃកម្មវិធីសិក្សា</div>
                        </div>
                    </label>
                    <label>
                        <input type="radio" name="type" value="Mandatory" class="type-radio" {{ old('type') == 'Mandatory' ? 'checked' : '' }}>
                        <div class="type-card">
                            <div class="type-icon" style="color:#15803d"><i class="bi bi-check-circle-fill"></i></div>
                            <div class="type-label">កាតព្វកិច្ច (Mandatory)</div>
                            <div class="type-desc">មុខវិជ្ជាកាតព្វកិច្ចដែលសិស្សគ្រប់រូបត្រូវរៀន</div>
                        </div>
                    </label>
                </div>
                @error('type')
                    <div class="text-danger mt-2" style="font-size:0.8rem">{{ $message }}</div>
                @enderror
            </div>

            {{-- Section 3: Assign to Classes --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#10b981"></div>
                    <span class="form-section-head-label">ដាក់សម្រាប់ថ្នាក់ (Assign to Classes)</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small">ជ្រើសរើសថ្នាក់ដែលត្រូវរៀនមុខវិជ្ជានេះ៖</span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2.5" onclick="toggleAllClasses(true)" style="font-size:0.75rem">
                            <i class="bi bi-check-all me-1"></i>ជ្រើសរើសទាំងអស់
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5" onclick="toggleAllClasses(false)" style="font-size:0.75rem">
                            <i class="bi bi-x me-1"></i>ដកទាំងអស់
                        </button>
                    </div>
                </div>

                <div class="row g-2" style="max-height: 280px; overflow-y: auto;">
                    @forelse($classes ?? [] as $classItem)
                        <div class="col-md-6">
                            <label class="class-card-option d-flex align-items-center gap-2 p-2.5 rounded-2 border h-100 cursor-pointer w-100"
                                   for="class_{{ $classItem->id }}"
                                   id="class_label_{{ $classItem->id }}">
                                <input type="checkbox"
                                       name="class_ids[]"
                                       value="{{ $classItem->id }}"
                                       id="class_{{ $classItem->id }}"
                                       class="form-check-input mt-0 class-checkbox flex-shrink-0"
                                       {{ in_array($classItem->id, old('class_ids', [])) ? 'checked' : '' }}
                                       onchange="updateCardStyle(this)">
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-bold text-dark small text-truncate">
                                        {{ $classItem->grade->name ?? 'ថ្នាក់' }}{{ $classItem->section ? ' — ' . $classItem->section->name : '' }}
                                    </div>
                                    @if($classItem->academic_year)
                                        <div class="text-muted" style="font-size:0.72rem">{{ $classItem->academic_year->name }}</div>
                                    @endif
                                </div>
                            </label>
                        </div>
                    @empty
                        <div class="col-12 text-muted small p-2">មិនទាន់មានថ្នាក់រៀនក្នុងប្រព័ន្ធនៅឡើយទេ។</div>
                    @endforelse
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle" style="color:#0369a1"></i>
                    Fields marked <span class="required-mark mx-1">*</span> are required
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('subjects.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm">
                        <i class="bi bi-check-lg me-1"></i>Create Subject
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT — SIDEBAR --}}
    <div class="col-xl-4 col-lg-4">
        {{-- Guidelines --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="sidebar-card-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-lightbulb"></i></div>
                <div class="sidebar-card-title">Subject Guidelines</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-upc-scan text-primary"></i>
                    <span>Subject codes must be unique across the entire school system (e.g. S001).</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-diagram-2 text-success"></i>
                    <span>After creation, this subject can be assigned to multiple classes.</span>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@push('styles')
<style>
.class-card-option {
    cursor: pointer;
    background: #ffffff;
    border-color: #e2e8f0;
    transition: all 0.15s ease;
    user-select: none;
}
.class-card-option:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.class-card-option.is-selected {
    background: #eff6ff;
    border-color: #3b82f6;
}
</style>
@endpush

@push('scripts')
<script>
function updateCardStyle(checkbox) {
    const label = document.getElementById('class_label_' + checkbox.value);
    if (!label) return;
    if (checkbox.checked) {
        label.classList.add('is-selected');
    } else {
        label.classList.remove('is-selected');
    }
}

function toggleAllClasses(select) {
    const checkboxes = document.querySelectorAll('.class-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = select;
        updateCardStyle(cb);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.class-checkbox').forEach(cb => {
        if (cb.checked) {
            updateCardStyle(cb);
        }
    });
});
</script>
@endpush
