@extends('layouts.app')

@section('title', 'View Enrollment — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Enrollment</li>
        <li class="breadcrumb-item"><a href="{{ route('enrollments.index') }}" class="text-decoration-none text-muted">Enrollments</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">View Details</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-dark">Enrollment Details</h1>
        <p class="text-muted mb-0">
            For: <span class="fw-medium text-dark">{{ $enrollment->student->user->name ?? 'Unknown Student' }}</span>
            <span class="ms-2">|</span> ID: <span class="badge bg-secondary">{{ $enrollment->student->admission_number ?? 'Unknown' }}</span>
        </p>
    </div>
    <div>
        <a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('enrollments.edit', $enrollment) }}" class="btn btn-primary-action btn-sm shadow-sm ms-2" style="background:#d97706; border-color:#b45309;">
            <i class="bi bi-pencil me-1"></i>Edit Enrollment
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px">
                        <i class="bi bi-calendar3 text-secondary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted small text-uppercase fw-bold">Academic Year</h6>
                        <div class="fs-5 fw-bold text-dark">{{ $enrollment->academic_year->name ?? '—' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px">
                        <i class="bi bi-diagram-3 text-secondary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted small text-uppercase fw-bold">Assigned Class</h6>
                        <div class="fs-5 fw-bold text-dark">
                            @if($enrollment->enrollmentDetail && $enrollment->enrollmentDetail->class)
                                {{ $enrollment->enrollmentDetail->class->grade->name ?? '' }} — {{ $enrollment->enrollmentDetail->class->section->name ?? '' }}
                            @else
                                <span class="text-danger">Unassigned</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="text-muted opacity-25 my-4">
        
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-muted small text-uppercase fw-bold">Enrollment Date</h6>
                <p class="fw-medium text-dark">{{ $enrollment->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('F d, Y') : '—' }}</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted small text-uppercase fw-bold">Status</h6>
                @php
                    $statusClass = 'bg-secondary';
                    if($enrollment->status == 'Active') $statusClass = 'bg-success';
                    elseif($enrollment->status == 'Pending') $statusClass = 'bg-warning text-dark';
                @endphp
                <p><span class="badge {{ $statusClass }} px-3 py-2">{{ $enrollment->status }}</span></p>
            </div>
            <div class="col-md-4">
                <h6 class="text-muted small text-uppercase fw-bold">Roll Number</h6>
                <p class="fw-medium text-dark">{{ $enrollment->enrollmentDetail->roll_number ?? 'Not assigned yet' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
