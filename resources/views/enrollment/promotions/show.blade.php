@extends('layouts.app')

@section('title', 'View Promotion — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Enrollment</li>
        <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}" class="text-decoration-none text-muted">Promotions</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">View Details</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-dark">Promotion Details</h1>
        <p class="text-muted mb-0">
            For: <span class="fw-medium text-dark">{{ $promotion->student->user->name ?? 'Unknown Student' }}</span>
            <span class="ms-2">|</span> ID: <span class="badge bg-secondary">{{ $promotion->student->admission_number ?? 'Unknown' }}</span>
        </p>
    </div>
    <div>
        <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-primary-action btn-sm shadow-sm ms-2" style="background:#d97706; border-color:#b45309;">
            <i class="bi bi-pencil me-1"></i>Edit Promotion
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        
        <div class="row text-center align-items-center py-3">
            {{-- From --}}
            <div class="col-md-5">
                <div class="mb-3">
                    <span class="badge bg-secondary px-3 py-2 text-uppercase letter-spacing-1">From</span>
                </div>
                <h5 class="fw-bold mb-1">{{ $promotion->from_class->grade->name ?? '' }} — {{ $promotion->from_class->section->name ?? '' }}</h5>
                <p class="text-muted mb-0">{{ $promotion->from_academic_year->name ?? '—' }}</p>
            </div>
            
            {{-- Arrow --}}
            <div class="col-md-2 d-none d-md-block">
                <i class="bi bi-arrow-right-circle text-muted" style="font-size: 2.5rem; opacity: 0.5;"></i>
            </div>
            
            {{-- To --}}
            <div class="col-md-5 mt-4 mt-md-0">
                <div class="mb-3">
                    <span class="badge bg-warning text-dark px-3 py-2 text-uppercase letter-spacing-1" style="background:#f59e0b !important;">To</span>
                </div>
                <h5 class="fw-bold mb-1" style="color: #b45309;">{{ $promotion->to_class->grade->name ?? '' }} — {{ $promotion->to_class->section->name ?? '' }}</h5>
                <p class="text-muted mb-0">{{ $promotion->to_academic_year->name ?? '—' }}</p>
            </div>
        </div>
        
        <hr class="text-muted opacity-25 my-4">
        
        <div class="row text-center">
            <div class="col-md-6 border-end">
                <h6 class="text-muted small text-uppercase fw-bold">Promotion Date</h6>
                <p class="fw-medium text-dark mb-0 fs-5">{{ $promotion->promotion_date ? \Carbon\Carbon::parse($promotion->promotion_date)->format('F d, Y') : '—' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted small text-uppercase fw-bold mb-2">Status</h6>
                @php
                    $statusClass = 'bg-secondary';
                    if($promotion->status == 'Promoted') $statusClass = 'bg-success';
                    elseif($promotion->status == 'Demoted') $statusClass = 'bg-danger';
                    elseif($promotion->status == 'Transferred') $statusClass = 'bg-primary';
                    elseif($promotion->status == 'Pending') $statusClass = 'bg-warning text-dark';
                @endphp
                <span class="badge {{ $statusClass }} px-3 py-2 fs-6">{{ $promotion->status }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
