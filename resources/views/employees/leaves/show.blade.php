@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតច្បាប់ឈប់សម្រាក — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">បុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('staff-leaves.index') }}" class="text-decoration-none text-muted">ច្បាប់ឈប់សម្រាក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ព័ត៌មានលម្អិត</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-dark">ព័ត៌មានលម្អិតនៃសំណើសុំច្បាប់</h1>
        <p class="text-muted mb-0">ស្នើសុំដោយ {{ $staff_leaf->employee?->user?->name ?? 'មិនស្គាល់' }}</p>
    </div>
    <div>
        <a href="{{ route('staff-leaves.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
        </a>
        <a href="{{ route('staff-leaves.edit', $staff_leaf) }}" class="btn btn-primary-action btn-sm shadow-sm ms-2 text-white" style="background:#e8a838; border-color:#e8a838;">
            <i class="bi bi-pencil me-1"></i>កែប្រែសំណើ
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-secondary" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-badge fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $staff_leaf->employee?->user?->name ?? 'មិនស្គាល់' }}</h4>
                        <div class="text-muted small">
                            {{ $staff_leaf->employee?->employee_position?->name ?? 'គ្មានតួនាទី' }} &bull; {{ $staff_leaf->employee?->employee_department?->name ?? 'គ្មានដេប៉ាតឺម៉ង់' }}
                        </div>
                    </div>
                    <div class="ms-auto text-end">
                        @php
                            $statusLabel = match(strtolower($staff_leaf->status)) {
                                'approved' => 'បានអនុម័ត (Approved)',
                                'rejected' => 'បដិសេធ (Rejected)',
                                'pending' => 'រង់ចាំការពិនិត្យ (Pending)',
                                default => $staff_leaf->status
                            };
                            $statusClass = match(strtolower($staff_leaf->status)) {
                                'approved' => 'bg-success bg-opacity-10 text-success border border-success-subtle',
                                'rejected' => 'bg-danger bg-opacity-10 text-danger border border-danger-subtle',
                                'pending' => 'bg-warning bg-opacity-10 text-warning border border-warning-subtle',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} rounded-pill px-3 py-2" style="font-size: 0.85rem">
                            {{ $statusLabel }}
                        </span>
                        @if($staff_leaf->status == 'Approved' && $staff_leaf->approvedBy)
                            <div class="mt-1 small text-muted">បានអនុម័តដោយ៖ {{ $staff_leaf->approvedBy->name }}</div>
                        @endif
                    </div>
                </div>

                <hr class="text-muted opacity-25">
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-4">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size:0.7rem; letter-spacing:0.5px;">ប្រភេទច្បាប់</small>
                        <div class="fw-medium p-2 bg-light rounded d-inline-block border">
                            <i class="bi bi-tag text-muted me-1"></i> {{ $staff_leaf->leave_type?->khmer_name ?? $staff_leaf->leave_type?->name ?? 'មិនស្គាល់' }}
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size:0.7rem; letter-spacing:0.5px;">រយៈពេលឈប់</small>
                        @php
                            $days = $staff_leaf->start_date->diffInDays($staff_leaf->end_date) + 1;
                        @endphp
                        <div class="fw-medium">
                            {{ $staff_leaf->start_date->format('M d, Y') }} - {{ $staff_leaf->end_date->format('M d, Y') }}
                            <span class="badge bg-secondary ms-2">{{ $days }} ថ្ងៃ</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size:0.7rem; letter-spacing:0.5px;">មូលហេតុ / កំណត់សម្គាល់</small>
                        <div class="p-3 bg-light rounded border text-secondary" style="font-size: 0.9rem">
                            {{ $staff_leaf->reason }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
