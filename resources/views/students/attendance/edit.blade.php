@extends('layouts.app')

@section('title', 'Edit Student Attendance — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Students</li>
        <li class="breadcrumb-item"><a href="{{ route('student-attendance.index') }}" class="text-decoration-none text-muted">Attendance</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Record</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.note-page-banner {
    background: linear-gradient(135deg, #115e59 0%, #0f766e 60%, #14b8a6 100%);
    border-radius: var(--radius-lg); padding: 1.6rem 1.75rem; margin-bottom: 1.75rem;
    position: relative; overflow: hidden;
}
.note-page-banner .banner-icon {
    width: 44px; height: 44px; background: rgba(255, 255, 255, 0.15); border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center; font-size: 1.15rem; color: #fff; margin-bottom: 0.85rem;
}
.note-page-banner h1 { font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0 0 .25rem; }
.note-page-banner p { font-size: .82rem; color: rgba(255,255,255,.7); margin: 0; }
.note-page-banner .banner-actions { position: absolute; top: 1.25rem; right: 1.25rem; z-index: 2; }

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

.form-select.fc, .form-control.fc {
    border-color: #e2e8f0; font-size: .875rem; border-radius: var(--radius-sm); color: var(--text-dark); background-color: #fff;
    transition: border-color .15s, box-shadow .15s; padding: .48rem .85rem;
}
.form-select.fc:focus, .form-control.fc:focus { border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15, 118, 110, .1); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-soft);
}
.required-note {
    display: flex; align-items: center; gap: .5rem; font-size: .75rem; color: var(--text-muted);
    padding: .65rem .9rem; background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: var(--radius-sm);
}
.required-mark { color: #ef4444; font-weight: 700; }
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="banner-actions">
        <a href="{{ route('student-attendance.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);font-size:.78rem">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
    <div class="banner-icon"><i class="bi bi-pencil"></i></div>
    <h1>Edit Student Attendance Record</h1>
    <p>Update attendance for {{ $attendance->student->user->name ?? 'Unknown Student' }} on {{ $attendance->attendance_session?->attendance_date?->format('F d, Y') ?? 'N/A' }}.</p>
</div>

{{-- VALIDATION ERRORS --}}
@if($errors->any())
    <div class="alert alert-danger mb-4">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <strong>Please correct the following errors:</strong>
        <ul class="mb-0 mt-1 ps-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('student-attendance.update', $attendance->id) }}" method="POST" novalidate>
@csrf
@method('PUT')

<div class="row g-4 align-items-start">
    <div class="col-xl-8 col-lg-9 mx-auto">
        <div class="note-form-card">

            {{-- Section 1: Read-only Student & Session Info --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar"></div>
                    <span class="form-section-head-label">Student & Session Details</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Student</label>
                        <input type="text" class="form-control fc" value="{{ $attendance->student->user->name ?? 'Unknown' }} ({{ $attendance->student->admission_no ?? '—' }})" readonly disabled>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Date</label>
                        <input type="text" class="form-control fc" value="{{ $attendance->attendance_session?->attendance_date?->format('Y-m-d') ?? '—' }}" readonly disabled>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Period</label>
                        <input type="text" class="form-control fc" value="Period {{ $attendance->attendance_session?->period ?? 1 }}" readonly disabled>
                    </div>
                </div>
            </div>

            {{-- Section 2: Attendance Status & Remarks --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar"></div>
                    <span class="form-section-head-label">Attendance Status <span class="required-mark">*</span></span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="attendance_status_id" class="form-label fw-semibold">
                            Status <span class="required-mark">*</span>
                        </label>
                        <select name="attendance_status_id" id="attendance_status_id" class="form-select fc @error('attendance_status_id') is-invalid @enderror" required>
                            @foreach($statuses as $st)
                                <option value="{{ $st->id }}" {{ old('attendance_status_id', $attendance->attendance_status_id) == $st->id ? 'selected' : '' }}>
                                    {{ $st->name }} ({{ $st->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('attendance_status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="remarks" class="form-label fw-semibold">Remarks / Note</label>
                        <textarea name="remarks" id="remarks" rows="3"
                                  class="form-control fc @error('remarks') is-invalid @enderror"
                                  placeholder="Optional note or explanation for attendance status...">{{ old('remarks', $attendance->remarks) }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle text-primary"></i>
                    Fields marked <span class="required-mark mx-1">*</span> are required
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('student-attendance.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm" style="background:#0f766e; border-color:#115e59; color:#fff;">
                        <i class="bi bi-check-lg me-1"></i>Update Record
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</form>

@endsection
