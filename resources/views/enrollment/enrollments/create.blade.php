@extends('layouts.app')

@section('title', 'ចុះឈ្មោះចូលរៀនថ្មី — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការចុះឈ្មោះ</li>
        <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}" class="text-decoration-none text-muted">បញ្ជីចុះឈ្មោះ</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ចុះឈ្មោះចូលរៀនថ្មី</li>
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

/* SHIFT SWITCHER */
.shift-option-card {
    cursor: pointer;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    transition: all 0.2s ease-in-out;
    width: 100%;
    min-height: 48px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 5px 8px;
    border-radius: 6px;
    text-align: center;
}
.shift-option-card:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
}
.shift-option-card .shift-card-title {
    font-size: 0.82rem;
    color: #334155;
    line-height: 1.2;
}
.shift-option-card .shift-card-sub {
    font-size: 0.68rem;
    line-height: 1.2;
    white-space: normal !important;
    word-break: normal;
    overflow-wrap: break-word;
}
.shift-option-card.active-morning {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-color: #f59e0b !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
}
.shift-option-card.active-morning .shift-card-title {
    color: #b45309;
}
.shift-option-card.active-morning .shift-card-sub {
    color: #92400e;
}
.shift-option-card.active-afternoon {
    background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
    border-color: #6366f1 !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
}
.shift-option-card.active-afternoon .shift-card-title {
    color: #4338ca;
}
.shift-option-card.active-afternoon .shift-card-sub {
    color: #3730a3;
}
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="d-flex align-items-center gap-3">
        <div class="banner-icon"><i class="bi bi-person-plus"></i></div>
        <div>
            <h1>ចុះឈ្មោះសិស្សចូលរៀន</h1>
            <p>ចាត់តាំងសិស្សចូលរៀនតាមថ្នាក់សម្រាប់ឆ្នាំសិក្សាជាក់លាក់មួយ។</p>
        </div>
    </div>
    <div class="banner-actions">
        <a href="{{ route('enrollments.index') }}" class="btn btn-sm">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅការចុះឈ្មោះ
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

<form action="{{ route('enrollments.store') }}" method="POST" novalidate id="enrollmentForm">
@csrf

<div class="row g-3 align-items-start">
    {{-- LEFT — MAIN FORM --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- Shift Selector Header --}}
            <div class="p-3 bg-white border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <label for="school_shift_id" class="form-label-custom fw-bold mb-0 text-dark" style="font-size: 0.84rem;">
                        បែងចែកទម្រង់ចុះឈ្មោះតាមវេន (Enrollment Shift) <span class="required-mark">*</span>
                    </label>
                    <span class="badge rounded-pill px-2.5 py-1 fw-semibold" id="enroll_active_shift_badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-size: 0.75rem;">
                        កំពុងចុះឈ្មោះ៖ វេនព្រឹក
                    </span>
                </div>
                <select name="school_shift_id" id="school_shift_id" class="form-select fc" onchange="onEnrollShiftSelectChange(this)" required>
                    @foreach($shifts as $s)
                        @php
                            $sName = mb_strtolower($s->name);
                            $isAft = (str_contains($sName, 'afternoon') || str_contains($sName, 'day') || str_contains($s->name, 'រសៀល'));
                            $sType = $isAft ? 'afternoon' : 'morning';
                            $sIcon = $isAft ? '🌦️' : '☀️';
                        @endphp
                        <option value="{{ $s->id }}" data-shift-type="{{ $sType }}" {{ old('school_shift_id') == $s->id ? 'selected' : '' }}>
                            {{ $sIcon }} {{ $s->name }} ({{ $isAft ? 'ចុះឈ្មោះចូលថ្នាក់ពេលរសៀល' : 'ចុះឈ្មោះចូលថ្នាក់ពេលព្រឹក' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Section 1: Student Selection --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">ព័ត៌មានលម្អិតអំពីសិស្ស</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-10">
                        <label for="student_id" class="form-label-custom">
                            ជ្រើសរើសសិស្ស <span class="required-mark">*</span>
                        </label>
                        <select name="student_id" id="student_id" class="form-select fc @error('student_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសសិស្ស —</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'សិស្ស' }} ({{ $student->admission_number ?? 'គ្មានអត្តលេខ' }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Enrollment Configuration --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#f59e0b"></div>
                    <span class="form-section-head-label">ព័ត៌មាននៃការចុះឈ្មោះ</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="academic_year_id" class="form-label-custom">
                            ឆ្នាំសិក្សា <span class="required-mark">*</span>
                        </label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select fc @error('academic_year_id') is-invalid @enderror" onchange="onAcademicYearChange()" required>
                            <option value="">— ជ្រើសរើសឆ្នាំសិក្សា —</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id', $academicYears->firstWhere('is_active', 1)?->id ?? '') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(បច្ចុប្បន្ន)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="class_id" class="form-label-custom mb-0">
                                ចាត់ចូលថ្នាក់រៀន <span class="required-mark">*</span>
                            </label>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="checkbox" id="show_all_classes_enroll" onchange="toggleAllEnrollClasses(this.checked)" style="cursor: pointer;">
                                <label class="form-check-label text-muted" for="show_all_classes_enroll" style="font-size: 0.72rem; cursor: pointer;">បង្ហាញគ្រប់ថ្នាក់</label>
                            </div>
                        </div>
                        <select name="class_id" id="class_id" class="form-select fc @error('class_id') is-invalid @enderror" required>
                            <option value="">— ជ្រើសរើសថ្នាក់រៀន —</option>
                            @foreach($classes as $class)
                                @php
                                    $className = ($class->grade->name ?? '') . ' — ' . ($class->section->name ?? '');
                                    $isMorn = (str_contains(mb_strtolower($className), 'ព្រឹក') || str_contains(strtolower($className), 'morning'));
                                    $isAft = (str_contains(mb_strtolower($className), 'រសៀល') || str_contains(strtolower($className), 'afternoon'));
                                    $cShift = $isMorn ? 'morning' : ($isAft ? 'afternoon' : 'both');
                                @endphp
                                <option value="{{ $class->id }}" data-shift="{{ $cShift }}" data-year-id="{{ $class->academic_year_id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $className }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="enrollment_date" class="form-label-custom">
                            កាលបរិច្ឆេទចុះឈ្មោះ <span class="required-mark">*</span>
                        </label>
                        <input type="date" name="enrollment_date" id="enrollment_date"
                               class="form-control fc @error('enrollment_date') is-invalid @enderror"
                               value="{{ old('enrollment_date', date('Y-m-d')) }}" required>
                        @error('enrollment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="status" class="form-label-custom">
                            ស្ថានភាព <span class="required-mark">*</span>
                        </label>
                        <select name="status" id="status" class="form-select fc @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>សកម្ម (Active)</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>អសកម្ម (Inactive)</option>
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
                    <a href="{{ route('enrollments.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm" style="background:#d97706; border-color:#b45309;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការចុះឈ្មោះ
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
                <div class="sidebar-card-title">គោលការណ៍ណែនាំនៃការចុះឈ្មោះ</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-shield-exclamation text-warning"></i>
                    <span>សិស្សម្នាក់អាចចុះឈ្មោះចូលរៀនបានតែម្ដងគត់ក្នុងមួយឆ្នាំសិក្សា។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-check2-circle text-success"></i>
                    <span>សូមប្រាកដថាបានជ្រើសរើសកម្រិត និងបន្ទប់ថ្នាក់ត្រឹមត្រូវ។ ការចាត់ថ្នាក់នេះកំណត់លើកាលវិភាគបង្រៀន និងវត្តមានសិស្ស។</span>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
let currentEnrollShift = 'morning';

// Mapping shifts from backend
const shiftIdMap = {
    @foreach($shifts as $sh)
        @php
            $shLower = strtolower($sh->name);
            $isMornSh = str_contains($shLower, 'morning') || str_contains($shLower, 'ព្រឹក');
            $isAftSh = str_contains($shLower, 'afternoon') || str_contains($shLower, 'day') || str_contains($shLower, 'រសៀល');
            $sType = $isMornSh ? 'morning' : ($isAftSh ? 'afternoon' : 'other');
        @endphp
        '{{ $sType }}': {{ $sh->id }},
    @endforeach
};

function onEnrollShiftSelectChange(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const shiftType = selectedOption ? (selectedOption.dataset.shiftType || 'morning') : 'morning';
    setEnrollShift(shiftType, false);
}

function setEnrollShift(shift, syncDropdown = true) {
    currentEnrollShift = shift;
    const badge = document.getElementById('enroll_active_shift_badge');
    const shiftSelect = document.getElementById('school_shift_id');
    const showAll = document.getElementById('show_all_classes_enroll')?.checked || false;

    if (shift === 'morning') {
        if (badge) {
            badge.style.background = '#fef3c7';
            badge.style.color = '#b45309';
            badge.style.borderColor = '#fde68a';
            badge.innerHTML = 'កំពុងចុះឈ្មោះ៖ វេនព្រឹក';
        }

        if (syncDropdown && shiftSelect && shiftSelect.options) {
            for (let opt of shiftSelect.options) {
                if (opt.dataset.shiftType === 'morning') {
                    shiftSelect.value = opt.value;
                    break;
                }
            }
        }
    } else {
        if (badge) {
            badge.style.background = '#e0e7ff';
            badge.style.color = '#4338ca';
            badge.style.borderColor = '#c7d2fe';
            badge.innerHTML = 'កំពុងចុះឈ្មោះ៖ វេនរសៀល';
        }

        if (syncDropdown && shiftSelect && shiftSelect.options) {
            for (let opt of shiftSelect.options) {
                if (opt.dataset.shiftType === 'afternoon') {
                    shiftSelect.value = opt.value;
                    break;
                }
            }
        }
    }

    filterEnrollClasses(shift, showAll);
}

let allEnrollClassOptions = null;

function initEnrollClassCache() {
    if (allEnrollClassOptions !== null) return;
    const classSelect = document.getElementById('class_id');
    if (!classSelect) return;
    allEnrollClassOptions = [];
    for (let opt of classSelect.options) {
        if (!opt.value) continue;
        allEnrollClassOptions.push({
            value: opt.value,
            text: opt.textContent.trim(),
            shift: opt.dataset.shift || 'both',
            yearId: opt.dataset.yearId || ''
        });
    }
}

function onAcademicYearChange() {
    const showAll = document.getElementById('show_all_classes_enroll')?.checked || false;
    filterEnrollClasses(currentEnrollShift, showAll);
}

function toggleAllEnrollClasses(showAll) {
    filterEnrollClasses(currentEnrollShift, showAll);
}

function filterEnrollClasses(shift, showAll) {
    initEnrollClassCache();
    const classSelect = document.getElementById('class_id');
    const yearSelect = document.getElementById('academic_year_id');
    const showAllCheckbox = document.getElementById('show_all_classes_enroll');
    if (!classSelect || !allEnrollClassOptions) return;

    const selectedYearId = yearSelect ? String(yearSelect.value).trim() : '';
    const currentClassValue = classSelect.value;

    classSelect.innerHTML = '';

    const placeholderOpt = document.createElement('option');
    placeholderOpt.value = '';

    if (!selectedYearId) {
        placeholderOpt.textContent = '— សូមជ្រើសរើសឆ្នាំសិក្សាជាមុនសិន —';
        classSelect.appendChild(placeholderOpt);
        return;
    }

    placeholderOpt.textContent = '— ជ្រើសរើសថ្នាក់រៀន —';
    classSelect.appendChild(placeholderOpt);

    // 1. Get all classes for the selected academic year
    const yearClasses = allEnrollClassOptions.filter(item => !item.yearId || String(item.yearId) === selectedYearId);

    // 2. Filter by shift or show all
    let targetClasses = [];
    if (showAll) {
        targetClasses = yearClasses;
    } else {
        targetClasses = yearClasses.filter(item => item.shift === shift || item.shift === 'both');
        // If no classes match this specific shift, fallback to all classes in this year
        if (targetClasses.length === 0 && yearClasses.length > 0) {
            targetClasses = yearClasses;
            if (showAllCheckbox) {
                showAllCheckbox.checked = true;
            }
        }
    }

    let preserved = false;

    targetClasses.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.value;
        opt.textContent = item.text;
        opt.dataset.shift = item.shift;
        opt.dataset.yearId = item.yearId;

        if (String(item.value) === String(currentClassValue)) {
            opt.selected = true;
            preserved = true;
        }
        classSelect.appendChild(opt);
    });

    if (targetClasses.length === 0) {
        placeholderOpt.textContent = '— មិនមានថ្នាក់រៀនក្នុងឆ្នាំសិក្សានេះទេ —';
    }

    if (!preserved && currentClassValue && targetClasses.length > 0) {
        if (targetClasses.some(c => String(c.value) === String(currentClassValue))) {
            classSelect.value = currentClassValue;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initEnrollClassCache();
    const classSelect = document.getElementById('class_id');
    const shiftSelect = document.getElementById('school_shift_id');
    let initialShift = 'morning';
    if (shiftSelect && shiftSelect.selectedIndex >= 0) {
        const selShift = shiftSelect.options[shiftSelect.selectedIndex];
        initialShift = selShift?.dataset?.shiftType || 'morning';
    } else if (classSelect && classSelect.selectedIndex >= 0) {
        const selOpt = classSelect.options[classSelect.selectedIndex];
        if (selOpt && selOpt.dataset.shift === 'afternoon') {
            initialShift = 'afternoon';
        }
    }
    setEnrollShift(initialShift, false);
});
</script>
@endpush
