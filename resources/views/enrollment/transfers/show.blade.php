@extends('layouts.app')

@section('title', 'View Transfer — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Enrollment</li>
        <li class="breadcrumb-item"><a href="{{ route('transfers.index') }}" class="text-decoration-none text-muted">Transfers</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">View Details</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-dark">Transfer Details</h1>
        <p class="text-muted mb-0">
            For: <span class="fw-medium text-dark">{{ $transfer->student->user->name ?? 'Unknown Student' }}</span>
            <span class="ms-2">|</span> ID: <span class="badge bg-secondary">{{ $transfer->student->admission_number ?? 'Unknown' }}</span>
        </p>
    </div>
    <div>
        <a href="{{ route('transfers.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('transfers.edit', $transfer) }}" class="btn btn-primary-action btn-sm shadow-sm ms-2" style="background:#d97706; border-color:#b45309;">
            <i class="bi bi-pencil me-1"></i>Edit Transfer
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
                <h5 class="fw-bold mb-1"><i class="bi bi-building me-2 text-muted"></i>{{ $transfer->from_school->name ?? 'Unknown School' }}</h5>
            </div>
            
            {{-- Arrow --}}
            <div class="col-md-2 d-none d-md-block">
                <i class="bi bi-arrow-right-circle text-muted" style="font-size: 2.5rem; opacity: 0.5;"></i>
            </div>
            
            {{-- To --}}
            <div class="col-md-5 mt-4 mt-md-0">
                <div class="mb-3">
                    <span class="badge text-white px-3 py-2 text-uppercase letter-spacing-1" style="background:#d97706 !important;">To</span>
                </div>
                <h5 class="fw-bold mb-1" style="color: #b45309;"><i class="bi bi-building me-2 opacity-50"></i>{{ $transfer->to_school->name ?? 'Unknown School' }}</h5>
            </div>
        </div>
        
        <hr class="text-muted opacity-25 my-4">
        
        <div class="row text-center mb-4">
            <div class="col-md-6 border-end">
                <h6 class="text-muted small text-uppercase fw-bold">Transfer Date</h6>
                <p class="fw-medium text-dark mb-0 fs-5">{{ $transfer->transfer_date ? \Carbon\Carbon::parse($transfer->transfer_date)->format('F d, Y') : '—' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted small text-uppercase fw-bold mb-2">Status</h6>
                @php
                    $statusClass = 'bg-secondary';
                    if($transfer->status == 'Completed') $statusClass = 'bg-success';
                    elseif($transfer->status == 'Rejected') $statusClass = 'bg-danger';
                    elseif($transfer->status == 'Approved') $statusClass = 'bg-primary';
                    elseif($transfer->status == 'Pending') $statusClass = 'bg-warning text-dark';
                @endphp
                <span class="badge {{ $statusClass }} px-3 py-2 fs-6">{{ $transfer->status }}</span>
            </div>
        </div>

        @if($transfer->reason)
        <div class="p-3 bg-light rounded-3 text-center mx-auto" style="max-width: 800px;">
            <h6 class="text-muted small text-uppercase fw-bold mb-2">Reason for Transfer</h6>
            <p class="mb-0 text-dark">{{ $transfer->reason }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
