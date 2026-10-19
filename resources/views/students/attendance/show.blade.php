@extends('layouts.app')

@section('title', 'View Student Attendance Record — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Students</li>
        <li class="breadcrumb-item"><a href="{{ route('student-attendance.index') }}" class="text-decoration-none text-muted">Attendance</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">View Record</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-dark">Student Attendance Record</h1>
        <p class="text-muted mb-0">
            {{ $attendance->student->user->name ?? 'Student' }} &bull; 
            {{ $attendance->attendance_session?->attendance_date?->format('F d, Y') ?? 'N/A' }}
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('student-attendance.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Register
        </a>
        <a href="{{ route('student-attendance.edit', $attendance->id) }}" class="btn btn-primary-action btn-sm shadow-sm" style="background:#0f766e; border-color:#0f766e; color:#fff;">
            <i class="bi bi-pencil me-1"></i>Edit Record
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-primary mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-person-badge fs-2 text-teal" style="color: #0f766e;"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ $attendance->student->user->name ?? 'Unknown Student' }}</h4>
                <p class="text-muted small px-3">
                    Admission No: <strong>{{ $attendance->student->admission_no ?? '—' }}</strong> &bull; 
                    Class: <strong>{{ $attendance->attendance_session?->schoolClass?->name ?? '—' }}</strong>
                </p>
                <hr class="text-muted opacity-25">
                
                <div class="row text-start mt-4">
                    <div class="col-6 mb-3">
                        <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Date</small>
                        <div class="fw-medium">{{ $attendance->attendance_session?->attendance_date?->format('l, F d, Y') ?? '—' }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Period</small>
                        <div class="fw-medium">Period {{ $attendance->attendance_session?->period ?? 1 }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Status</small>
                        <div>
                            @php
                                $code = strtoupper($attendance->attendance_status?->code ?? '');
                                $badgeClass = match($code) {
                                    'P' => 'badge bg-success',
                                    'A' => 'badge bg-danger',
                                    'L' => 'badge bg-warning text-dark',
                                    default => 'badge bg-info text-dark'
                                };
                            @endphp
                            <span class="{{ $badgeClass }} px-2 py-1 fs-6">
                                {{ $attendance->attendance_status?->name ?? 'Unknown' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Status Code</small>
                        <div class="fw-medium">{{ $attendance->attendance_status?->code ?? '—' }}</div>
                    </div>
                    <div class="col-12 mt-2">
                        <small class="text-muted text-uppercase fw-bold" style="font-size:0.7rem; letter-spacing:0.5px;">Remarks / Reason</small>
                        <div class="p-3 bg-light rounded mt-1 border text-secondary">
                            {{ $attendance->remarks ?: 'No remarks entered for this attendance record.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
