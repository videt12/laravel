@extends('layouts.app')

@section('title', 'Edit Promotion — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Enrollment</li>
        <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}" class="text-decoration-none text-muted">Promotions</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Promotion</li>
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
.form-section-head { display: flex; align-items: center; gap: .6rem; margin-bottom: .75rem; }
.form-section-head-bar { width: 3.5px; height: 1.15rem; border-radius: 999px; background: var(--gold); flex-shrink: 0; }
.form-section-head-label { font-size: .84rem; font-weight: 700; color: var(--text-dark, #1e293b); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }
.form-section { padding: 0.95rem 1.25rem; border-bottom: 1px solid var(--border-soft); }
.form-section:last-child { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════
   FIELD STYLES
═══════════════════════════════════════════════════════════════ */
.field-hint { display: flex; align-items: center; gap: .3rem; font-size: .72rem; color: var(--text-muted); margin-top: .15rem; line-height: 1.3; }
.form-select.fc, .form-control.fc {
    border-color: #e2e8f0; font-size: .85rem; border-radius: var(--radius-sm); color: var(--text-dark); background-color: #fff;
    transition: border-color .15s, box-shadow .15s; padding: .38rem .7rem; line-height: 1.45;
}
.form-select.fc { padding-right: 2.25rem; }
.form-select.fc:focus, .form-control.fc:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

/* ═══════════════════════════════════════════════════════════════
   FORM ACTIONS BAR
═══════════════════════════════════════════════════════════════ */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: .65rem 1.25rem; background: #f8fafc; border-top: 1px solid var(--border-soft);
}
.required-note {
    display: flex; align-items: center; gap: .4rem; font-size: .73rem; color: var(--text-muted);
    padding: .35rem .65rem; background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: var(--radius-sm);
}
.required-mark { color: #ef4444; font-weight: 700; }

/* SIDEBAR CARDS */
.sidebar-card { background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.03); margin-bottom: 1rem; }
.sidebar-card-header { display: flex; align-items: center; gap: .65rem; padding: .65rem .95rem; border-bottom: 1px solid var(--border-soft); background: #fafafa; }
.sidebar-card-icon { width: 28px; height: 28px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; }
.sidebar-card-title { font-size: .82rem; font-weight: 700; color: var(--text-dark); }
.sidebar-card-body  { padding: .65rem .95rem; }
.guide-item { display: flex; gap: .65rem; padding: .4rem 0; border-bottom: 1px solid var(--border-soft); font-size: .78rem; color: var(--text-body); align-items: flex-start; }
.guide-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="d-flex align-items-center gap-3">
        <div class="banner-icon"><i class="bi bi-pencil"></i></div>
        <div>
            <h1>កែប្រែការឡើងថ្នាក់</h1>
            <p>កែប្រែកំណត់ត្រាឡើងថ្នាក់សម្រាប់ {{ $promotion->student->user->name ?? 'សិស្ស' }}</p>
        </div>
    </div>
    <div class="banner-actions">
        <a href="{{ route('promotions.index') }}" class="btn btn-sm">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅការឡើងថ្នាក់
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

<form action="{{ route('promotions.update', $promotion) }}" method="POST" novalidate>
@csrf
@method('PUT')

<div class="row g-4 align-items-start">
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

                <div class="row g-3">
                    <div class="col-md-10">
                        <label class="form-label-custom text-muted">សិស្ស</label>
                        <input type="text" class="form-control fc" value="{{ $promotion->student->user->name ?? 'Unknown Student' }} ({{ $promotion->student->admission_number ?? 'No ID' }})" disabled>
                        <div class="field-hint">អត្តសញ្ញាណសិស្សមិនអាចផ្លាស់ប្តូរបានទេបន្ទាប់ពីបានកត់ត្រា។</div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Previous Class/Year --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#64748b"></div>
                    <span class="form-section-head-label">ថ្នាក់ និងឆ្នាំសិក្សាដើម</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="from_academic_year_id" class="form-label-custom">
                            ពីឆ្នាំសិក្សា <span class="required-mark">*</span>
                        </label>
                        <select name="from_academic_year_id" id="from_academic_year_id" class="form-select fc @error('from_academic_year_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសឆ្នាំសិក្សា —</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('from_academic_year_id', $promotion->from_academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('from_academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="from_class_id" class="form-label-custom">
                            ពីថ្នាក់រៀន <span class="required-mark">*</span>
                        </label>
                        <select name="from_class_id" id="from_class_id" class="form-select fc @error('from_class_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសថ្នាក់រៀន —</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('from_class_id', $promotion->from_class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name ?? '' }} — {{ $class->section->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('from_class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Promotion Destination --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#f59e0b"></div>
                    <span class="form-section-head-label">ថ្នាក់ និងឆ្នាំសិក្សាថ្មី</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="to_academic_year_id" class="form-label-custom">
                            ទៅឆ្នាំសិក្សា <span class="required-mark">*</span>
                        </label>
                        <select name="to_academic_year_id" id="to_academic_year_id" class="form-select fc @error('to_academic_year_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសឆ្នាំសិក្សា —</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('to_academic_year_id', $promotion->to_academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint"><i class="bi bi-info-circle"></i> អាចជ្រើសឆ្នាំសិក្សាថ្មីបន្ទាប់ ឬឆ្នាំដដែល (ករណីផ្ទេរថ្នាក់)</div>
                        @error('to_academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="to_class_id" class="form-label-custom">
                            ទៅថ្នាក់រៀន <span class="required-mark">*</span>
                        </label>
                        <select name="to_class_id" id="to_class_id" class="form-select fc @error('to_class_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសថ្នាក់រៀន —</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('to_class_id', $promotion->to_class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name ?? '' }} — {{ $class->section->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint"><i class="bi bi-info-circle"></i> ជ្រើសរើសថ្នាក់ដែលត្រូវឡើង ឬផ្ទេរទៅ</div>
                        @error('to_class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="promotion_date" class="form-label-custom">
                            កាលបរិច្ឆេទឡើងថ្នាក់ <span class="required-mark">*</span>
                        </label>
                        <input type="date" name="promotion_date" id="promotion_date"
                                class="form-control fc @error('promotion_date') is-invalid @enderror"
                                value="{{ old('promotion_date', $promotion->promotion_date ? $promotion->promotion_date->format('Y-m-d') : '') }}" required>
                        @error('promotion_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="status" class="form-label-custom">
                            ស្ថានភាព <span class="required-mark">*</span>
                        </label>
                        <select name="status" id="status" class="form-select fc @error('status') is-invalid @enderror" required>
                            <option value="Promoted" {{ old('status', $promotion->status) == 'Promoted' ? 'selected' : '' }}>បានឡើងថ្នាក់ (Promoted)</option>
                            <option value="Demoted" {{ old('status', $promotion->status) == 'Demoted' ? 'selected' : '' }}>ធ្លាក់ថ្នាក់ (Demoted)</option>
                            <option value="Transferred" {{ old('status', $promotion->status) == 'Transferred' ? 'selected' : '' }}>ផ្ទេរ (Transferred)</option>
                            <option value="Pending" {{ old('status', $promotion->status) == 'Pending' ? 'selected' : '' }}>រង់ចាំ (Pending)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle" style="color:#0369a1"></i>
                    វាលដែលមានសញ្ញា <span class="required-mark mx-1">*</span> ត្រូវតែបំពេញជាដាច់ខាត
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('promotions.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm" style="background:#b45309; border-color:#92400e;">
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
                <div class="sidebar-card-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-lightbulb"></i></div>
                <div class="sidebar-card-title">សេចក្តីណែនាំ</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-info-circle text-info"></i>
                    <span>ការកែប្រែកំណត់ត្រានេះគ្រាន់តែផ្លាស់ប្តូរប្រវត្តិឡើងថ្នាក់ប៉ុណ្ណោះ។ ដើម្បីផ្លាស់ប្តូរថ្នាក់សកម្មបច្ចុប្បន្នរបស់សិស្ស សូមកែប្រែក្នុងកំណត់ត្រាចុះឈ្មោះ (Enrollment)។</span>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection
