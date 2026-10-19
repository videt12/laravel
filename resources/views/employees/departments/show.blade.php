@extends('layouts.app')

@section('title', $department->name . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">បុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធបុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $department->name }}</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   DETAIL / SHOW CARD STYLES (MATCHING STUDENT SHOW)
═══════════════════════════════════════════════════════════════════ */
.data-card {
    background: #ffffff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.03);
}
.data-card-header {
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid var(--border-soft, #e2e8f0);
    background: #ffffff;
}
.detail-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    display: block;
    margin-bottom: 0.2rem;
}
.detail-value {
    font-size: 0.92rem;
    font-weight: 600;
    color: #1e293b;
    display: block;
}
.detail-row {
    padding: 0.5rem 0;
    border-bottom: 1px dashed #f1f5f9;
}
.detail-row:last-child {
    border-bottom: none;
}
.hero-card {
    background: #ffffff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.03);
}
</style>
@endpush

@section('content')

{{-- Header Banner / Hero --}}
<div class="hero-card mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px; background: #fef3c7; color: #c98b1e; font-size: 1.6rem; border: 1px solid #fde68a;">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h1 class="fw-bold text-dark mb-0" style="font-size: 1.35rem;">{{ $department->name }}</h1>
                    @if($department->code)
                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.8rem; font-weight: 600;">
                            <i class="bi bi-tag me-1 text-muted"></i>{{ $department->code }}
                        </span>
                    @endif
                    @if($department->status === 'active')
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Active (សកម្ម)</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1">Inactive (អសកម្ម)</span>
                    @endif
                </div>
                <p class="text-muted small mb-0 mt-1">
                    <i class="bi bi-people me-1"></i>មានបុគ្គលិកសរុបចំនួន <strong>{{ $department->employees->count() }} នាក់</strong> នៅក្នុងដេប៉ាតឺម៉ង់នេះ
                </p>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="border-radius: 4px;">
                <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ (Back)
            </a>
            <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-primary-action px-3 py-1.5 d-flex align-items-center gap-1.5 shadow-sm">
                <i class="bi bi-pencil"></i>
                <span>កែប្រែ (Edit)</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- ═══════════════════════════════════════════════════════════
         LEFT COLUMN: BASIC INFORMATION & STAFF ROSTER
    ═══════════════════════════════════════════════════════════ --}}
    <div class="col-xl-8 col-lg-8">
        
        {{-- 1. Basic Information (Matching Student Show Screenshot 2) --}}
        <div class="data-card mb-4">
            <div class="data-card-header">
                <span class="fw-semibold text-dark"><i class="bi bi-info-circle me-2 text-warning"></i>Basic Information</span>
            </div>
            <div class="detail-grid p-4">
                <div class="row g-3">
                    <div class="col-md-6 detail-row">
                        <span class="detail-label">DEPARTMENT NAME</span>
                        <span class="detail-value text-dark">{{ $department->name }}</span>
                    </div>

                    <div class="col-md-6 detail-row">
                        <span class="detail-label">CODE ID (លេខកូដ)</span>
                        <span class="detail-value">
                            @if($department->code)
                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size:0.82rem; font-weight:600;">{{ $department->code }}</span>
                            @else
                                <span class="text-muted fw-normal" style="font-size:0.85rem;">— មិនទាន់កំណត់ —</span>
                            @endif
                        </span>
                    </div>

                    <div class="col-md-6 detail-row">
                        <span class="detail-label">STATUS</span>
                        <span class="detail-value">
                            @if($department->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Inactive</span>
                            @endif
                        </span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <span class="detail-label">TOTAL STAFF</span>
                        <span class="detail-value text-primary fw-bold">{{ $department->employees->count() }} នាក់</span>
                    </div>
                    <div class="col-12 detail-row">
                        <span class="detail-label">DESCRIPTION</span>
                        <span class="detail-value text-muted fw-normal" style="font-size:0.88rem;">{{ $department->description ?: 'មិនមានការពិពណ៌នាឡើយ' }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <span class="detail-label">CREATED AT</span>
                        <span class="detail-value text-muted" style="font-size:0.82rem;">{{ $department->created_at ? $department->created_at->format('d M Y, h:i A') : '—' }}</span>
                    </div>
                    <div class="col-md-6 detail-row">
                        <span class="detail-label">LAST UPDATED</span>
                        <span class="detail-value text-muted" style="font-size:0.82rem;">{{ $department->updated_at ? $department->updated_at->format('d M Y, h:i A') : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Staff Roster Table --}}
        <div class="data-card mb-4">
            <div class="data-card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold text-dark"><i class="bi bi-people me-2 text-primary"></i>Staff Roster (បញ្ជីបុគ្គលិកក្នុងផ្នែកនេះ)</span>
                <span class="badge bg-light text-dark border">{{ $department->employees->count() }} បុគ្គលិក</span>
            </div>
            <div>
                @if($department->employees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">បុគ្គលិក (Employee)</th>
                                    <th>អត្តលេខ</th>
                                    <th>មុខតំណែង (Position)</th>
                                    <th>ស្ថានភាព</th>
                                    <th class="text-end pe-4">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($department->employees as $emp)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                            <div>
                                                <a href="{{ route('employees.show', $emp) }}" class="text-dark fw-semibold text-decoration-none">
                                                    {{ $emp->user->name ?? '—' }}
                                                </a>
                                                <div class="text-muted small" style="font-size:0.72rem;">{{ $emp->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code class="px-2 py-0.5 bg-light rounded text-dark">{{ $emp->employee_code }}</code></td>
                                    <td>{{ $emp->employee_position->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                            {{ $emp->employee_status?->khmer_name ?? 'សកម្ម' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-2" title="មើលលម្អិត">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-2 d-block mb-2 text-secondary opacity-50"></i>
                        មិនទាន់មានបុគ្គលិកណាម្នាក់ត្រូវបានចាត់តាំងចូលក្នុងដេប៉ាតឺម៉ង់នេះនៅឡើយទេ។
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════
         RIGHT COLUMN: POSITIONS CARD (MATCHING GUARDIANS IN SCREENSHOT 2)
    ═══════════════════════════════════════════════════════════ --}}
    <div class="col-xl-4 col-lg-4">
        
        {{-- Positions Card (Styled like Guardians Card in Screenshot 2) --}}
        <div class="data-card mb-4">
            <div class="data-card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold text-dark"><i class="bi bi-award me-2 text-warning"></i>Positions (មុខតំណែង)</span>
                <a href="{{ route('departments.create') }}" class="btn btn-sm btn-outline-primary py-1 px-2.5 d-flex align-items-center gap-1" style="font-size:0.8rem; border-radius:4px;">
                    <i class="bi bi-plus-lg"></i>
                    <span>+ Attach / Add</span>
                </a>
            </div>
            <div class="p-3">
                @if(isset($positions) && $positions->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($positions as $pos)
                        <div class="border rounded p-3 bg-light bg-opacity-25 position-relative" style="border-color:#e2e8f0 !important;">
                            <div class="d-flex align-items-start justify-content-between mb-1">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size:0.88rem;">{{ $pos->name }}</h6>
                                    @if($pos->code)
                                        <small class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill mt-1" style="font-size:0.72rem;">
                                            {{ $pos->code }}
                                        </small>
                                    @endif
                                    @if($pos->status === 'active')
                                        <small class="badge bg-success-subtle text-success border border-success-subtle rounded-pill mt-1" style="font-size:0.72rem;">Active</small>
                                    @endif
                                </div>
                                <span class="badge bg-light text-muted border" style="font-size:0.72rem;">
                                    <i class="bi bi-people me-1"></i>{{ $pos->employees_count ?? 0 }} នាក់
                                </span>
                            </div>
                            
                            @if($pos->description)
                                <div class="text-muted small mt-2" style="font-size: 0.78rem; line-height:1.35;">
                                    {{ \Illuminate\Support\Str::limit($pos->description, 70) }}
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center my-3 small">មិនទាន់មានមុខតំណែងត្រូវបានបង្កើតនៅឡើយទេ។</p>
                @endif
            </div>
        </div>

        {{-- Overview Card (Matching Enrollment Info in Screenshot 2) --}}
        <div class="data-card mb-4">
            <div class="data-card-header">
                <span class="fw-semibold text-dark"><i class="bi bi-card-checklist me-2 text-info"></i>Structure Summary</span>
            </div>
            <div class="p-3">
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <span class="text-muted small">ស្ថានភាពបច្ចុប្បន្ន</span>
                    <span class="fw-semibold text-dark small">{{ ucfirst($department->status) }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <span class="text-muted small">ចំនួនមុខតំណែងសរុប</span>
                    <span class="fw-semibold text-dark small">{{ isset($positions) ? $positions->count() : 0 }} មុខតំណែង</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2">
                    <span class="text-muted small">បុគ្គលិកសរុបក្នុងប្រព័ន្ធ</span>
                    <span class="fw-semibold text-dark small">{{ $department->employees->count() }} នាក់</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
