@extends('layouts.app')

@section('title', 'Edit Transfer — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Enrollment</li>
        <li class="breadcrumb-item"><a href="{{ route('transfers.index') }}" class="text-decoration-none text-muted">Transfers</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Transfer</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER BANNER
═══════════════════════════════════════════════════════════════ */
.note-page-banner {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 60%, #b45309 100%) !important;
    border-radius: var(--radius-lg);
    padding: 0.85rem 1.25rem !important;
    margin-bottom: 1rem !important;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(217, 119, 6, 0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.note-page-banner::before {
    content: ''; position: absolute; top: -40px; right: -40px; width: 120px; height: 120px;
    background: rgba(255, 255, 255, 0.18); border-radius: 50%; pointer-events: none;
}
.note-page-banner::after {
    content: ''; position: absolute; bottom: -30px; right: 80px; width: 80px; height: 80px;
    background: rgba(255, 255, 255, 0.10); border-radius: 50%; pointer-events: none;
}
.note-page-banner .banner-icon {
    width: 36px; height: 36px; background: rgba(255, 255, 255, 0.22); border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #fff; margin-bottom: 0 !important;
    border: 1px solid rgba(255, 255, 255, 0.3); flex-shrink: 0;
}
.note-page-banner h1 { font-size: 1.1rem !important; font-weight: 700; color: #fff; margin: 0 0 .15rem !important; line-height: 1.25; }
.note-page-banner p { font-size: .78rem !important; color: rgba(255, 255, 255, 0.95); margin: 0 !important; line-height: 1.2; }
.note-page-banner .banner-actions { position: static !important; z-index: 2; }
.note-page-banner .banner-actions .btn {
    background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.45);
    color: #ffffff !important; font-size: 0.80rem !important; padding: 0.25rem 0.75rem !important;
    backdrop-filter: blur(4px); transition: all 0.2s ease; display: inline-flex; align-items: center;
}
.note-page-banner .banner-actions .btn:hover { background: #ffffff; color: #b45309 !important; border-color: #ffffff; }

/* ═══════════════════════════════════════════════════════════════
   FORM CARD
═══════════════════════════════════════════════════════════════ */
.note-form-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 12px rgba(0,0,0,.04);
}
.form-section-head { display: flex; align-items: center; gap: .5rem; margin-bottom: .5rem; }
.form-section-head-bar { width: 3px; height: 1rem; border-radius: 999px; background: #d97706; flex-shrink: 0; }
.form-section-head-label { font-size: .8rem; font-weight: 700; color: var(--text-dark, #1e293b); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }
.form-section { padding: 0.65rem 1rem; border-bottom: 1px solid var(--border-soft); }
.form-section:last-child { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════
   FIELD STYLES
═══════════════════════════════════════════════════════════════ */
.form-label-custom {
    display: block; font-size: 0.8rem; font-weight: 600; color: #334155; margin-bottom: 0.25rem;
}
.field-hint { display: flex; align-items: center; gap: .3rem; font-size: .7rem; color: var(--text-muted); margin-top: .15rem; line-height: 1.25; }
.form-select.fc, .form-control.fc {
    border-color: #cbd5e1; font-size: .83rem; border-radius: var(--radius-sm); color: var(--text-dark); background-color: #fff;
    transition: border-color .15s, box-shadow .15s; padding: .32rem .65rem; min-height: 35px; height: 35px; line-height: 1.4;
}
textarea.form-control.fc { height: auto !important; min-height: 60px !important; }
.form-select.fc { padding-right: 2rem; }
.form-select.fc:focus, .form-control.fc:focus { border-color: #d97706; box-shadow: 0 0 0 2px rgba(217,119,6,.15); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

/* ═══════════════════════════════════════════════════════════════
   FORM ACTIONS BAR
═══════════════════════════════════════════════════════════════ */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: .5rem 1rem; background: #f8fafc; border-top: 1px solid var(--border-soft);
}
.required-note {
    display: flex; align-items: center; gap: .35rem; font-size: .72rem; color: var(--text-muted);
    padding: .3rem .55rem; background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: var(--radius-sm);
}
.required-mark { color: #ef4444; font-weight: 700; }

/* SIDEBAR CARDS */
.sidebar-card { background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.03); margin-bottom: 0.75rem; }
.sidebar-card-header { display: flex; align-items: center; gap: .5rem; padding: .55rem .85rem; border-bottom: 1px solid var(--border-soft); background: #fafafa; }
.sidebar-card-icon { width: 24px; height: 24px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: .75rem; flex-shrink: 0; }
.sidebar-card-title { font-size: .8rem; font-weight: 700; color: var(--text-dark); }
.sidebar-card-body  { padding: .55rem .85rem; }
.guide-item { display: flex; gap: .5rem; padding: .35rem 0; border-bottom: 1px solid var(--border-soft); font-size: .76rem; color: var(--text-body); align-items: flex-start; line-height: 1.4; }
.guide-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="d-flex align-items-center gap-3">
        <div class="banner-icon"><i class="bi bi-pencil"></i></div>
        <div>
            <h1>កែប្រែការផ្ទេរសាលា</h1>
            <p>កែប្រែកំណត់ត្រាផ្ទេរសាលាសម្រាប់ {{ $transfer->student->user->name ?? 'សិស្ស' }}</p>
        </div>
    </div>
    <div class="banner-actions">
        <a href="{{ route('transfers.index') }}" class="btn btn-sm">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅការផ្ទេរសាលា
        </a>
    </div>
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

<form action="{{ route('transfers.update', $transfer) }}" method="POST" novalidate>
@csrf
@method('PUT')

<div class="row g-3 align-items-start">
    {{-- LEFT — MAIN FORM --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- Section 1: Student Details (Read-only for Edit) --}}
            <div class="form-section bg-light">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">ព័ត៌មានលម្អិតអំពីសិស្ស</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label class="form-label-custom text-muted">ថ្នាក់រៀន</label>
                        <input type="text" class="form-control fc" value="{{ ($transfer->student?->schoolClass?->grade?->name ?? '') . ($transfer->student?->schoolClass?->section?->name ? ' — ' . $transfer->student->schoolClass->section->name : 'មិនទាន់មានថ្នាក់') }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom text-muted">សិស្ស</label>
                        <input type="text" class="form-control fc" value="{{ $transfer->student->user->name ?? 'សិស្ស' }} ({{ $transfer->student->admission_number ?? 'គ្មានអត្តលេខ' }})" disabled>
                    </div>
                </div>
            </div>

            {{-- Section 2: Transfer Details --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#6366f1"></div>
                    <span class="form-section-head-label">ការផ្ទេររវាងសាលារៀន</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label for="from_school_name" class="form-label-custom">
                            ពីសាលារៀន <span class="required-mark">*</span>
                        </label>
                        <input type="text" name="from_school_name" id="from_school_name" list="schools_list"
                               class="form-control fc @error('from_school_name') is-invalid @enderror"
                               value="{{ old('from_school_name', $transfer->from_school?->name ?? '') }}"
                               placeholder="វាយបញ្ចូលឈ្មោះសាលារៀនដើម..." required autocomplete="off">
                        @error('from_school_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="field-hint"><i class="bi bi-info-circle"></i> អាចវាយបញ្ចូលឈ្មោះសាលាដោយសេរី ឬជ្រើសពីបញ្ជី</div>
                    </div>

                    <div class="col-md-6">
                        <label for="to_school_name" class="form-label-custom">
                            ទៅសាលារៀន <span class="required-mark">*</span>
                        </label>
                        <input type="text" name="to_school_name" id="to_school_name" list="schools_list"
                               class="form-control fc @error('to_school_name') is-invalid @enderror"
                               value="{{ old('to_school_name', $transfer->to_school?->name ?? '') }}"
                               placeholder="វាយបញ្ចូលឈ្មោះសាលារៀនដែលត្រូវផ្ទេរទៅ..." required autocomplete="off">
                        @error('to_school_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="field-hint"><i class="bi bi-info-circle"></i> អាចវាយបញ្ចូលឈ្មោះសាលាដោយសេរី ឬជ្រើសពីបញ្ជី</div>
                    </div>

                    <datalist id="schools_list">
                        @foreach($schools as $school)
                            <option value="{{ $school->name }}"></option>
                        @endforeach
                    </datalist>

                    <div class="col-md-6">
                        <label for="transfer_date" class="form-label-custom">
                            កាលបរិច្ឆេទផ្ទេរ <span class="required-mark">*</span>
                        </label>
                        <input type="date" name="transfer_date" id="transfer_date"
                               class="form-control fc @error('transfer_date') is-invalid @enderror"
                               value="{{ old('transfer_date', $transfer->transfer_date ? $transfer->transfer_date->format('Y-m-d') : '') }}" required>
                        @error('transfer_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label-custom">
                            ស្ថានភាព <span class="required-mark">*</span>
                        </label>
                        <select name="status" id="status" class="form-select fc @error('status') is-invalid @enderror" required>
                            <option value="Pending" {{ old('status', $transfer->status) == 'Pending' ? 'selected' : '' }}>រង់ចាំការពិនិត្យ (Pending)</option>
                            <option value="Approved" {{ old('status', $transfer->status) == 'Approved' ? 'selected' : '' }}>បានអនុម័ត (Approved)</option>
                            <option value="Completed" {{ old('status', $transfer->status) == 'Completed' ? 'selected' : '' }}>បានបញ្ចប់សព្វគ្រប់ (Completed)</option>
                            <option value="Rejected" {{ old('status', $transfer->status) == 'Rejected' ? 'selected' : '' }}>បដិសេធ (Rejected)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="reason" class="form-label-custom">មូលហេតុនៃការផ្ទេរ</label>
                        <textarea name="reason" id="reason" rows="2" class="form-control fc @error('reason') is-invalid @enderror">{{ old('reason', $transfer->reason) }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle" style="color:#0369a1"></i>
                    ប្រអប់ដែលមានសញ្ញា <span class="required-mark mx-1">*</span> គឺតម្រូវឱ្យបំពេញជាចាំបាច់
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('transfers.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm" style="background:#d97706; border-color:#b45309;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការកែប្រែ
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
                <div class="sidebar-card-icon" style="background:#fef3c7;color:#b45309"><i class="bi bi-info-circle"></i></div>
                <div class="sidebar-card-title">គោលការណ៍នៃការផ្ទេរសាលា</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-shield-exclamation text-warning"></i>
                    <span>សាលាដើមរបស់សិស្សមិនអាចដូចគ្នាជាមួយសាលាថ្មីបានឡើយ។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-check2-circle text-success"></i>
                    <span>ការផ្ទេរត្រូវតែកំណត់ជា 'បានបញ្ចប់សព្វគ្រប់' នៅពេលដែលទិន្នន័យសិស្សត្រូវបានផ្ទេររួចរាល់។</span>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection
