@extends('layouts.app')

@section('title', 'ឡើងថ្នាក់សិស្សថ្មី — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការចុះឈ្មោះ</li>
        <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}" class="text-decoration-none text-muted">ការឡើងថ្នាក់</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ឡើងថ្នាក់សិស្សថ្មី</li>
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
.form-section-head-bar { width: 3px; height: 1rem; border-radius: 999px; background: var(--gold); flex-shrink: 0; }
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
.form-select.fc { padding-right: 2rem; }
.form-select.fc:focus, .form-control.fc:focus { border-color: var(--gold); box-shadow: 0 0 0 2px var(--gold-subtle); outline: none; }
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
        <div class="banner-icon"><i class="bi bi-graph-up-arrow"></i></div>
        <div>
            <h1>ឡើងថ្នាក់សិស្ស</h1>
            <p>កត់ត្រាការផ្លាស់ប្តូរឆ្នាំសិក្សា ឬថ្នាក់រៀនថ្មីសម្រាប់សិស្ស។</p>
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
    <div class="alert-card alert-card-danger mb-3">
        <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
        <div>
            <strong>សូមកែតម្រូវកំហុសឆ្គងខាងក្រោម៖</strong>
            <ul class="mb-0 mt-1 ps-4">
                @foreach($errors->all() as $error)
                    <li style="font-size:.875rem">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('promotions.store') }}" method="POST" novalidate>
@csrf

<div class="row g-3 align-items-start">
    {{-- LEFT — MAIN FORM --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- Section 1: Student Details --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">ព័ត៌មានលម្អិតអំពីសិស្ស</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-2.5">
                    <div class="col-md-5">
                        <label for="filter_class_id" class="form-label-custom">
                            <i class="bi bi-funnel me-1 text-primary"></i> រើសតាមថ្នាក់ (Filter by Class)
                        </label>
                        <select id="filter_class_id" class="form-select fc" style="background-color: #f0fdf4; border-color: #86efac;">
                            <option value="">— គ្រប់ថ្នាក់ (All Classes) —</option>
                            @foreach($classes as $c)
                                @php
                                    $cName = ($c->grade?->name ?? 'Class ' . $c->id) . ($c->section?->name ? ' — ' . $c->section->name : '');
                                @endphp
                                <option value="{{ $c->id }}">{{ $cName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-7">
                        <label for="student_id" class="form-label-custom">
                            ជ្រើសរើសសិស្ស <span class="required-mark">*</span>
                        </label>
                        <select name="student_id" id="student_id" class="form-select fc @error('student_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសសិស្ស —</option>
                            @php
                                $groupedStudents = $students->groupBy(function($s) {
                                    $grade = $s->schoolClass?->grade?->name ?? '';
                                    $sec = $s->schoolClass?->section?->name ?? '';
                                    if ($grade || $sec) {
                                        return trim("{$grade} — {$sec}");
                                    }
                                    return 'សិស្សមិនទាន់មានថ្នាក់ (Unassigned Class)';
                                });
                            @endphp
                            @foreach($groupedStudents as $groupClassName => $groupList)
                                <optgroup label="🏫 {{ $groupClassName }} ({{ $groupList->count() }} នាក់)" data-optgroup-name="{{ $groupClassName }}">
                                    @foreach($groupList as $student)
                                        @php
                                             $currentYearId = $student->academic_year_id;
                                             $currentClassId = $student->school_class_id;
                                             if (!$currentYearId || !$currentClassId) {
                                                 $latestEnrollment = $student->enrollments->where('status', 'Active')->sortByDesc('enrollment_date')->first()
                                                                     ?? $student->enrollments->sortByDesc('enrollment_date')->first();
                                                 if ($latestEnrollment) {
                                                     $currentYearId = $currentYearId ?: $latestEnrollment->academic_year_id;
                                                     $currentClassId = $currentClassId ?: $latestEnrollment->enrollmentDetail?->class_id;
                                                 }
                                             }
                                             $yearName = $student->academicYear?->name ?? '';
                                             $className = ($student->schoolClass?->grade?->name ?? '') . ($student->schoolClass?->section?->name ? ' — ' . $student->schoolClass->section->name : '');
                                        @endphp
                                        <option value="{{ $student->id }}"
                                                data-year-id="{{ $currentYearId ?? '' }}"
                                                data-class-id="{{ $currentClassId ?? '' }}"
                                                data-info="{{ $className && $yearName ? $className . ' (' . $yearName . ')' : ($className ?: ($yearName ?: '')) }}"
                                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                             {{ $student->user->name ?? 'សិស្ស' }} ({{ $student->admission_number ?? 'គ្មានអត្តលេខ' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div id="student_current_info" class="mt-1 text-muted small d-none">
                            <i class="bi bi-info-circle text-primary me-1"></i> ព័ត៌មានបច្ចុប្បន្ន៖ <span id="student_current_details" class="fw-semibold text-dark"></span>
                        </div>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Previous Class/Year --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#64748b"></div>
                    <span class="form-section-head-label">ថ្នាក់ និងឆ្នាំសិក្សាចាស់</span>
                    <span class="badge rounded-pill bg-light text-primary border px-2 py-1 ms-2" id="auto_filled_badge" style="display:none; font-size: 0.72rem;">
                        <i class="bi bi-magic me-1"></i> ជ្រើសស្វ័យប្រវត្តិ (Auto-detected)
                    </span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label for="from_academic_year_id" class="form-label-custom">
                            ពីឆ្នាំសិក្សា <span class="required-mark">*</span>
                        </label>
                        <select name="from_academic_year_id" id="from_academic_year_id" class="form-select fc @error('from_academic_year_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសឆ្នាំសិក្សា —</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('from_academic_year_id') == $year->id ? 'selected' : '' }}>
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
                                <option value="{{ $class->id }}" {{ old('from_class_id') == $class->id ? 'selected' : '' }}>
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

                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label for="to_academic_year_id" class="form-label-custom">
                            ទៅឆ្នាំសិក្សា <span class="required-mark">*</span>
                        </label>
                        <select name="to_academic_year_id" id="to_academic_year_id" class="form-select fc @error('to_academic_year_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសឆ្នាំសិក្សា —</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('to_academic_year_id') == $year->id ? 'selected' : '' }}>
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
                                <option value="{{ $class->id }}" {{ old('to_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name ?? '' }} — {{ $class->section->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint"><i class="bi bi-info-circle"></i> ជ្រើសរើសថ្នាក់ដែលត្រូវឡើង ឬផ្ទេរទៅ</div>
                        @error('to_class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="promotion_date" class="form-label-custom">
                            កាលបរិច្ឆេទឡើងថ្នាក់ <span class="required-mark">*</span>
                        </label>
                        <input type="date" name="promotion_date" id="promotion_date"
                               class="form-control fc @error('promotion_date') is-invalid @enderror"
                               value="{{ old('promotion_date', date('Y-m-d')) }}" required>
                        @error('promotion_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label-custom">
                            ស្ថានភាព <span class="required-mark">*</span>
                        </label>
                        <select name="status" id="status" class="form-select fc @error('status') is-invalid @enderror" required>
                            <option value="Promoted" {{ old('status') == 'Promoted' ? 'selected' : '' }}>ឡើងថ្នាក់ (Promoted)</option>
                            <option value="Demoted" {{ old('status') == 'Demoted' ? 'selected' : '' }}>ត្រួតថ្នាក់ (Demoted)</option>
                            <option value="Transferred" {{ old('status') == 'Transferred' ? 'selected' : '' }}>ផ្ទេរថ្នាក់ (Transferred)</option>
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>រង់ចាំ (Pending)</option>
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
                    ប្រអប់ដែលមានសញ្ញា <span class="required-mark mx-1">*</span> គឺតម្រូវឱ្យបំពេញជាចាំបាច់
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('promotions.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm" style="background:#d97706; border-color:#b45309;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការឡើងថ្នាក់
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
                <div class="sidebar-card-title">គោលការណ៍ណែនាំនៃការឡើងថ្នាក់</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-shield-exclamation text-warning"></i>
                    <span>«ពីឆ្នាំសិក្សា» និង «ទៅឆ្នាំសិក្សា» របស់សិស្សមិនអាចដូចគ្នាបានឡើយ។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-check2-circle text-success"></i>
                    <span>បន្ទាប់ពីឡើងថ្នាក់ដោយជោគជ័យ សូមប្រាកដថាសិស្សត្រូវបានចុះឈ្មោះចូលរៀនជាផ្លូវការក្នុងថ្នាក់ថ្មីរបស់ពួកគេ។</span>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const fromYearSelect = document.getElementById('from_academic_year_id');
    const fromClassSelect = document.getElementById('from_class_id');
    const currentInfoDiv = document.getElementById('student_current_info');
    const currentDetailsSpan = document.getElementById('student_current_details');
    const autoBadge = document.getElementById('auto_filled_badge');

    function syncOldYearAndClass() {
        if (!studentSelect) return;
        const selectedOpt = studentSelect.options[studentSelect.selectedIndex];
        if (!selectedOpt || !selectedOpt.value) {
            if (currentInfoDiv) currentInfoDiv.classList.add('d-none');
            if (autoBadge) autoBadge.style.display = 'none';
            return;
        }

        const yearId = selectedOpt.getAttribute('data-year-id');
        const classId = selectedOpt.getAttribute('data-class-id');
        const info = selectedOpt.getAttribute('data-info');

        if (info && currentInfoDiv && currentDetailsSpan) {
            currentDetailsSpan.textContent = info;
            currentInfoDiv.classList.remove('d-none');
        } else if (currentInfoDiv) {
            currentInfoDiv.classList.add('d-none');
        }

        let autoFilled = false;
        if (yearId && fromYearSelect) {
            fromYearSelect.value = yearId;
            autoFilled = true;
        }
        if (classId && fromClassSelect) {
            fromClassSelect.value = classId;
            autoFilled = true;
        }

        if (autoBadge) {
            autoBadge.style.display = autoFilled ? 'inline-block' : 'none';
        }

        // Visual pulse highlight
        if (autoFilled) {
            fromYearSelect.style.transition = 'all 0.3s ease';
            fromClassSelect.style.transition = 'all 0.3s ease';
            fromYearSelect.style.borderColor = '#3b82f6';
            fromClassSelect.style.borderColor = '#3b82f6';
            fromYearSelect.style.backgroundColor = '#eff6ff';
            fromClassSelect.style.backgroundColor = '#eff6ff';

            setTimeout(() => {
                fromYearSelect.style.borderColor = '';
                fromClassSelect.style.borderColor = '';
                fromYearSelect.style.backgroundColor = '';
                fromClassSelect.style.backgroundColor = '';
            }, 1200);
        }
    }

    const filterClassSelect = document.getElementById('filter_class_id');

    if (filterClassSelect) {
        filterClassSelect.addEventListener('change', function() {
            const chosenClassId = this.value;
            const optgroups = studentSelect.querySelectorAll('optgroup');

            optgroups.forEach(group => {
                let groupHasVisible = false;
                const options = group.querySelectorAll('option');
                options.forEach(opt => {
                    const optClassId = opt.getAttribute('data-class-id');
                    if (!chosenClassId || optClassId === chosenClassId) {
                        opt.style.display = '';
                        opt.disabled = false;
                        groupHasVisible = true;
                    } else {
                        opt.style.display = 'none';
                        opt.disabled = true;
                    }
                });
                group.style.display = groupHasVisible ? '' : 'none';
            });

            // If current selection is hidden/disabled, reset
            const currentSelected = studentSelect.options[studentSelect.selectedIndex];
            if (currentSelected && currentSelected.disabled) {
                studentSelect.value = '';
                syncOldYearAndClass();
            }

            // Pre-select From Class if chosen
            if (chosenClassId && fromClassSelect) {
                fromClassSelect.value = chosenClassId;
            }
        });
    }

    studentSelect.addEventListener('change', syncOldYearAndClass);

    // Run on initial load if student is pre-selected
    if (studentSelect.value) {
        syncOldYearAndClass();
    }
});
</script>
@endpush
