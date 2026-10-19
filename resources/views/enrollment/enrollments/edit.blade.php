@extends('layouts.app')

@section('title', 'Edit Enrollment — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Enrollment</li>
        <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}" class="text-decoration-none text-muted">Enrollments</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Enrollment</li>
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
            <h1>កែសម្រួលការចុះឈ្មោះ</h1>
            <p>កែប្រែព័ត៌មានចុះឈ្មោះរបស់សិស្ស {{ $enrollment->student->user->name ?? '' }}</p>
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

<form action="{{ route('enrollments.update', $enrollment) }}" method="POST" novalidate>
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
                    <span class="form-section-head-label">Student Details</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-10">
                        <label class="form-label-custom text-muted">Enrolled Student</label>
                        <input type="text" class="form-control fc" value="{{ $enrollment->student->user->name ?? 'Unknown Student' }} ({{ $enrollment->student->admission_number ?? 'No ID' }})" disabled>
                        <div class="field-hint">Student identity cannot be changed once enrolled.</div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Enrollment Configuration --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#f59e0b"></div>
                    <span class="form-section-head-label">Enrollment Info</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="academic_year_id" class="form-label-custom">
                            Academic Year <span class="required-mark">*</span>
                        </label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select fc @error('academic_year_id') is-invalid @enderror" onchange="filterClassesByYear()" required>
                            <option value="">— Select Academic Year —</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id', $enrollment->academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(បច្ចុប្បន្ន)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="class_id" class="form-label-custom">
                            Assign Class <span class="required-mark">*</span>
                        </label>
                        <select name="class_id" id="class_id" class="form-select fc @error('class_id') is-invalid @enderror" required>
                            <option value="">— Select a Class —</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" data-year-id="{{ $class->academic_year_id }}" {{ old('class_id', $enrollment->enrollmentDetail->class_id ?? null) == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name ?? '' }} — {{ $class->section->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="enrollment_date" class="form-label-custom">
                            Enrollment Date <span class="required-mark">*</span>
                        </label>
                        <input type="date" name="enrollment_date" id="enrollment_date"
                               class="form-control fc @error('enrollment_date') is-invalid @enderror"
                               value="{{ old('enrollment_date', $enrollment->enrollment_date ? $enrollment->enrollment_date->format('Y-m-d') : '') }}" required>
                        @error('enrollment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="status" class="form-label-custom">
                            Status <span class="required-mark">*</span>
                        </label>
                        <select name="status" id="status" class="form-select fc @error('status') is-invalid @enderror" required>
                            <option value="Active" {{ old('status', $enrollment->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $enrollment->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Pending" {{ old('status', $enrollment->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
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
                    Fields marked <span class="required-mark mx-1">*</span> are required
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('enrollments.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm" style="background:#b45309; border-color:#92400e;">
                        <i class="bi bi-check-lg me-1"></i>Update Enrollment
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
                <div class="sidebar-card-title">Editing Guidelines</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-info-circle text-info"></i>
                    <span>Updating the class will automatically move the student into the corresponding section.</span>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
let allEditClassOptions = null;

function initEditClassCache() {
    if (allEditClassOptions !== null) return;
    const classSelect = document.getElementById('class_id');
    if (!classSelect) return;
    allEditClassOptions = [];
    for (let opt of classSelect.options) {
        if (!opt.value) continue;
        allEditClassOptions.push({
            value: opt.value,
            text: opt.textContent.trim(),
            yearId: opt.dataset.yearId || ''
        });
    }
}

function filterClassesByYear() {
    initEditClassCache();
    const classSelect = document.getElementById('class_id');
    const yearSelect = document.getElementById('academic_year_id');
    if (!classSelect || !allEditClassOptions) return;

    const selectedYearId = yearSelect ? String(yearSelect.value).trim() : '';
    const currentClassValue = classSelect.value;

    classSelect.innerHTML = '';

    const placeholderOpt = document.createElement('option');
    placeholderOpt.value = '';

    if (!selectedYearId) {
        placeholderOpt.textContent = '— Please select academic year first —';
        classSelect.appendChild(placeholderOpt);
        return;
    }

    placeholderOpt.textContent = '— Select a Class —';
    classSelect.appendChild(placeholderOpt);

    let matchCount = 0;
    let preserved = false;

    allEditClassOptions.forEach(item => {
        if (!item.yearId || String(item.yearId) === selectedYearId) {
            const opt = document.createElement('option');
            opt.value = item.value;
            opt.textContent = item.text;
            opt.dataset.yearId = item.yearId;

            if (String(item.value) === String(currentClassValue)) {
                opt.selected = true;
                preserved = true;
            }
            classSelect.appendChild(opt);
            matchCount++;
        }
    });

    if (matchCount === 0) {
        placeholderOpt.textContent = '— No classes available in this academic year —';
    }

    if (!preserved && currentClassValue && matchCount > 0) {
        classSelect.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initEditClassCache();
    filterClassesByYear();
});
</script>
@endpush
