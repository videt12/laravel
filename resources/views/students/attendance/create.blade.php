@extends('layouts.app')

@section('title', 'Record Attendance — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student</li>
        <li class="breadcrumb-item"><a href="{{ route('student-attendance.index', ['date' => $selectedDate]) }}" class="text-decoration-none text-muted">Attendance</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Record Attendance</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER BANNER
═══════════════════════════════════════════════════════════════ */
.note-page-banner {
    background: linear-gradient(135deg, #0f766e 0%, #14b8a6 60%, #2dd4bf 100%);
    border-radius: var(--radius-lg); padding: 1.6rem 1.75rem; margin-bottom: 1.75rem;
    position: relative; overflow: hidden;
}
.note-page-banner::before {
    content: ''; position: absolute; top: -40px; right: -40px; width: 160px; height: 160px;
    background: rgba(255, 255, 255, 0.1); border-radius: 50%;
}
.note-page-banner::after {
    content: ''; position: absolute; bottom: -30px; right: 80px; width: 100px; height: 100px;
    background: rgba(255, 255, 255, 0.05); border-radius: 50%;
}
.note-page-banner .banner-icon {
    width: 44px; height: 44px; background: rgba(255, 255, 255, 0.2); border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center; font-size: 1.15rem; color: #fff; margin-bottom: 0.85rem;
}
.note-page-banner h1 { font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0 0 .25rem; }
.note-page-banner p { font-size: .82rem; color: rgba(255,255,255,.7); margin: 0; }
.note-page-banner .banner-actions { position: absolute; top: 1.25rem; right: 1.25rem; z-index: 2; }

/* ═══════════════════════════════════════════════════════════════
   FORM CARD
═══════════════════════════════════════════════════════════════ */
.note-form-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 12px rgba(0,0,0,.04);
}
.form-section-head { display: flex; align-items: center; gap: .7rem; margin-bottom: 1.1rem; }
.form-section-head-bar { width: 3px; height: 1rem; border-radius: 999px; background: #0f766e; flex-shrink: 0; }
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
.form-select.fc:focus, .form-control.fc:focus { border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15, 118, 110, .1); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

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

.status-radio-group { display: flex; gap: 1rem; flex-wrap: wrap; }
.status-radio-card { flex: 1; }
.status-radio-card input[type="radio"] { display: none; }
.status-radio-card label {
    display: flex; flex-direction: column; align-items: center; padding: 1rem;
    border: 1px solid #e2e8f0; border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s;
    background: #fff; height: 100%;
}
.status-radio-card label:hover { border-color: #cbd5e1; background: #f8fafc; }
.status-radio-card input[type="radio"]:checked + label {
    border-color: #0f766e; background: #f0fdfa; box-shadow: 0 0 0 1px #0f766e;
}
.status-radio-card input[type="radio"]:checked + label .status-title { color: #0f766e; }
.status-title { font-weight: 600; font-size: 0.85rem; color: #475569; margin-top: 0.5rem; margin-bottom: 0; }
.status-icon { font-size: 1.5rem; color: #94a3b8; }
.status-radio-card input[type="radio"]:checked + label .status-icon { color: #0f766e; }

</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="banner-actions">
        <a href="{{ route('student-attendance.index', ['date' => $selectedDate]) }}" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);font-size:.78rem">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
    <div class="banner-icon"><i class="bi bi-clock-history"></i></div>
    <h1>Record Attendance</h1>
    <p>Manually record daily attendance for a student member.</p>
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
@if(session('error'))
    <div class="alert-card alert-card-danger mb-4">
        <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
        <div>
            <strong>Error:</strong>
            <ul class="mb-0 mt-1 ps-4">
                <li style="font-size:.875rem">{{ session('error') }}</li>
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('student-attendance.store') }}" method="POST" novalidate>
@csrf

<div class="row g-4 align-items-start">
    <div class="col-xl-8 col-lg-9 mx-auto">
        <div class="note-form-card">

            {{-- Section 1: Employee & Date --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar"></div>
                    <span class="form-section-head-label">Basic Information</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label-custom">
                            Student <span class="required-mark">*</span>
                        </label>
                        <select name="student_id" id="student_id" class="form-select fc @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Select Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name ?? 'Unknown' }} ({{ $student->admission_no }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="attendance_date" class="form-label-custom">
                            Date <span class="required-mark">*</span>
                        </label>
                        <input type="date" name="attendance_date" id="attendance_date"
                               class="form-control fc @error('attendance_date') is-invalid @enderror"
                               value="{{ old('attendance_date', $selectedDate) }}" required>
                        @error('attendance_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Attendance Status --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar"></div>
                    <span class="form-section-head-label">Status <span class="required-mark">*</span></span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="status-radio-group">
                    @foreach($statuses as $status)
                    @php
                        $icon = 'bi-circle';
                        $color = '#6b7280';
                        if (strtolower($status->name) == 'present') { $icon = 'bi-check-circle-fill'; $color = '#10b981'; }
                        elseif (strtolower($status->name) == 'absent') { $icon = 'bi-x-circle-fill'; $color = '#ef4444'; }
                        elseif (strtolower($status->name) == 'late') { $icon = 'bi-clock-fill'; $color = '#f59e0b'; }
                        elseif (strtolower($status->name) == 'half day') { $icon = 'bi-brightness-alt-high-fill'; $color = '#6366f1'; }
                    @endphp
                    <div class="status-radio-card">
                        <input type="radio" name="attendance_status_id" id="status_{{ $status->id }}" value="{{ $status->id }}" {{ old('attendance_status_id') == $status->id ? 'checked' : '' }} required>
                        <label for="status_{{ $status->id }}">
                            <i class="bi {{ $icon }} status-icon" style="color: {{ $color }};"></i>
                            <p class="status-title">{{ $status->name }}</p>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('attendance_status_id')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Section 3: Time & Remarks --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar"></div>
                    <span class="form-section-head-label">Time Log & Remarks</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">

                    
                    <div class="col-md-12 mt-3">
                        <label for="remarks" class="form-label-custom">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="2"
                                  class="form-control fc @error('remarks') is-invalid @enderror"
                                  placeholder="Optional notes regarding attendance...">{{ old('remarks') }}</textarea>
                        @error('remarks')
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
                    <a href="{{ route('student-attendance.index', ['date' => $selectedDate]) }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm" style="background:#0f766e; border-color:#0f766e;">
                        <i class="bi bi-check-lg me-1"></i>Save Attendance
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</form>

@endsection


