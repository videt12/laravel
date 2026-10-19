@extends('layouts.app')

@section('title', 'រចនាសម្ព័ន្ធបុគ្គលិក (ផ្នែក និងមុខតំណែង) — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">បុគ្គលិក</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ផ្នែក និងមុខតំណែង</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   STUDENT-STYLE DESIGN SYSTEM FOR DUAL/COMBINED DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.page-subtitle { color: #64748b; font-size: 0.8rem; margin-bottom: 0; }

/* Metric cards (Student style) */
.metric-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.65rem 0.95rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.03);
    height: 100%;
}
.metric-val {
    font-size: 1.55rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 0.15rem;
}
.metric-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
}
.metric-sub {
    font-size: 0.78rem;
    line-height: 1.25;
    color: #64748b;
}

/* Toolbar (Student style) */
.toolbar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.03);
}
.toolbar-card label.form-label {
    height: 21px;
    line-height: 21px;
    margin-bottom: 0.25rem !important;
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    color: #475569 !important;
}
.toolbar-card .search-input-wrap {
    position: relative !important;
    width: 100% !important;
    display: flex !important;
    align-items: center !important;
}
.toolbar-card .search-input-wrap i.bi-search {
    position: absolute !important;
    left: 0.75rem !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #64748b !important;
    font-size: 0.85rem !important;
    pointer-events: none !important;
    z-index: 3 !important;
}
.toolbar-card .search-input-wrap .form-control {
    padding-left: 2.15rem !important;
}
.toolbar-card .form-control-sm,
.toolbar-card .form-select-sm,
.toolbar-card .input-group-text,
.toolbar-card .input-group .form-control,
.toolbar-card .filter-action-btn {
    height: 38px !important;
    min-height: 38px !important;
    font-size: 0.88rem !important;
    border-radius: 6px !important;
}
.toolbar-card .filter-action-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    line-height: 1 !important;
}

/* Data card (Student style) */
.data-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.data-card-header {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}

/* Data Table */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.data-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 0.5rem 0.65rem;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.data-table tbody td {
    padding: 0.55rem 0.65rem;
    color: #1e293b;
    font-size: 0.82rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.table-row-hover:hover {
    background-color: #fafbfc;
}
.border-col-divider {
    border-right: 2px solid #e2e8f0 !important;
}

/* Count badge */
.count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #e2e8f0;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    border-radius: 999px;
    padding: 0.1rem 0.5rem;
    min-width: 22px;
}

/* Filter chips */
.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 999px;
    padding: 0.15rem 0.6rem;
}

/* Type icon */
.type-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    font-size: 0.85rem;
    flex-shrink: 0;
}

/* Action buttons */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 26px;
    height: 26px;
    border-radius: 4px;
    color: #64748b;
    transition: all 0.15s;
    background: transparent;
    border: none;
    font-size: 0.80rem;
}
.action-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.action-btn-danger:hover {
    background: #fef2f2;
    color: #dc2626;
}
.action-btn-primary:hover {
    background: #fef3c7;
    color: #c98b1e;
}

/* Modal styles */
.modal-form-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.25rem;
    display: block;
}
.modal-form-control {
    font-size: 0.82rem;
    border-radius: 4px;
    border: 1px solid #cbd5e1;
    padding: 0.4rem 0.65rem;
    color: #1e293b;
    background-color: #fff;
    width: 100%;
}
.detail-item {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 4px;
    padding: 0.45rem 0.75rem;
    margin-bottom: 0.4rem;
}
.detail-item-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 700;
    color: #64748b;
    margin-bottom: 0.1rem;
}
.detail-item-value {
    font-size: 0.88rem;
    font-weight: 600;
    color: #0f172a;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════ --}}
<div class="page-header d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="page-title">រចនាសម្ព័ន្ធបុគ្គលិក (Staff Organization)</h1>
        <p class="page-subtitle">គ្រប់គ្រងផ្នែក និងមុខតំណែងបុគ្គលិកក្នុងទំព័រ និងតារាងតែមួយ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('departments.create') }}" class="btn btn-primary-action btn-sm">
            <i class="bi bi-building me-1"></i>+ បន្ថែមផ្នែក
        </a>
        <a href="{{ route('positions.create') }}" class="btn btn-sm text-white" style="background:#d97706; border-color:#d97706;">
            <i class="bi bi-award me-1"></i>+ បន្ថែមមុខតំណែង
        </a>
    </div>
</div>

{{-- Notifications --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 py-2 px-3 mb-3" style="font-size:0.85rem; border-radius:8px;" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-2 py-2 px-3 mb-3" style="font-size:0.85rem; border-radius:8px;" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ═══════════════════════════════════════════
     METRIC CARDS (Student style)
═══════════════════════════════════════════ --}}
<div class="row g-2 mb-2">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="metric-label">ផ្នែកសរុប</span>
                <span class="badge" style="background:#fef3c7;color:#c98b1e;font-size:.70rem;"><i class="bi bi-building"></i></span>
            </div>
            <h4 class="metric-val text-dark my-1">{{ number_format($totalDepartments) }}</h4>
            <div class="metric-sub"><span class="text-dark fw-medium">{{ $activeDepartments }}</span> សកម្ម</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="metric-label">មុខតំណែងសរុប</span>
                <span class="badge" style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;font-size:.70rem;"><i class="bi bi-award"></i></span>
            </div>
            <h4 class="metric-val text-dark my-1">{{ number_format($totalPositions) }}</h4>
            <div class="metric-sub"><span class="text-dark fw-medium">{{ $activePositions }}</span> សកម្ម</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="metric-label">បុគ្គលិកសរុប</span>
                <span class="badge" style="background:#e0f2fe;color:#0284c7;font-size:.70rem;"><i class="bi bi-people-fill"></i></span>
            </div>
            <h4 class="metric-val text-dark my-1">{{ number_format($totalEmployees) }}</h4>
            <div class="metric-sub"><span class="text-dark fw-medium">នាក់</span> កំពុងបំពេញការងារ</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <span class="metric-label">រចនាសម្ព័ន្ធសរុប</span>
                <span class="badge" style="background:#f1f5f9;color:#475569;font-size:.70rem;"><i class="bi bi-diagram-3-fill"></i></span>
            </div>
            <h4 class="metric-val text-dark my-1">{{ number_format($totalAll) }}</h4>
            <div class="metric-sub">ផ្នែក + មុខតំណែង</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     FILTER TOOLBAR (Student style)
═══════════════════════════════════════════ --}}
<div class="toolbar-card mb-2">
    <form method="GET" action="{{ route('departments.index') }}" id="filterForm">
        <div class="row g-2">
            {{-- Search --}}
            <div class="col-12 col-md-4 col-lg-4">
                <label class="form-label d-block">ស្វែងរក</label>
                <div class="search-input-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text"
                           name="search"
                           id="searchInput"
                           value="{{ $search }}"
                           class="form-control form-control-sm"
                           placeholder="ស្វែងរក ផ្នែក ឬ មុខតំណែង..."
                           autocomplete="off">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-12 col-md-4 col-lg-4">
                <label class="form-label d-block">ស្ថានភាព</label>
                <select name="status_filter" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <option value="active" {{ request('status_filter') === 'active' ? 'selected' : '' }}>សកម្ម (Active)</option>
                    <option value="inactive" {{ request('status_filter') === 'inactive' ? 'selected' : '' }}>អសកម្ម (Inactive)</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="col-12 col-md-4 col-lg-4">
                <label class="form-label d-none d-md-block" style="visibility:hidden;">&nbsp;</label>
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-3 shadow-xs filter-action-btn">
                        <i class="bi bi-funnel me-1"></i>ស្វែងរក
                    </button>
                    @if($search || request('status_filter'))
                        <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-secondary px-2 filter-action-btn">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>កំណត់ឡើងវិញ
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Active filter chips --}}
        @if($search || request('status_filter'))
        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted" style="font-size:.75rem">តម្រងកំពុងប្រើ៖</span>
            @if($search)
                <span class="filter-chip">
                    ស្វែងរក៖ "{{ $search }}"
                    <a href="{{ route('departments.index', request()->except('search')) }}" class="ms-1 text-muted text-decoration-none"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if(request('status_filter'))
                <span class="filter-chip">
                    ស្ថានភាព៖ {{ request('status_filter') === 'active' ? 'សកម្ម' : 'អសកម្ម' }}
                    <a href="{{ route('departments.index', request()->except('status_filter')) }}" class="ms-1 text-muted text-decoration-none"><i class="bi bi-x"></i></a>
                </span>
            @endif
        </div>
        @endif
    </form>
</div>

{{-- ═══════════════════════════════════════════
     DATA CARD: COMBINED DUAL-DATA TABLE (ផ្នែក & មុខតំណែង រួមគ្នាក្នុងតារាងតែមួយ)
═══════════════════════════════════════════ --}}
<div class="data-card">

    {{-- Card Header --}}
    <div class="data-card-header d-flex align-items-center justify-content-start">
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:#fef3c7; color:#b45309; font-size:0.7rem; font-weight:600;">
                <i class="bi bi-building me-1"></i>{{ $totalDepartments }} ផ្នែក
            </span>
            <span class="badge" style="background:#fffbeb; color:#d97706; border:1px solid #fde68a; font-size:0.7rem; font-weight:600;">
                <i class="bi bi-award me-1"></i>{{ $totalPositions }} មុខតំណែង
            </span>
        </div>
    </div>

    {{-- Table / Empty State --}}
    @if($pairedRows->isEmpty())
        <div class="empty-state py-5 text-center">
            <div class="empty-icon fs-1 text-muted mb-2"><i class="bi bi-folder2-open"></i></div>
            <p class="empty-title fw-bold text-dark fs-6 mb-1">
                @if($search || request('status_filter'))
                    មិនមានទិន្នន័យត្រូវនឹងលក្ខខណ្ឌស្វែងរកឡើយ
                @else
                    មិនទាន់មានផ្នែក ឬមុខតំណែងនៅឡើយទេ
                @endif
            </p>
            <p class="empty-subtitle text-muted small mb-3">
                @if($search || request('status_filter'))
                    សូមសាកល្បងផ្លាស់ប្តូរ ឬសម្អាតប៉ារ៉ាម៉ែត្រស្វែងរក។
                @else
                    ចាប់ផ្តើមដោយបន្ថែមផ្នែក និងមុខតំណែងដំបូងរបស់អ្នក។
                @endif
            </p>
            @if($search || request('status_filter'))
                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary btn-sm">សម្អាតការស្វែងរកទាំងអស់</a>
            @else
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('departments.create') }}" class="btn btn-primary-action btn-sm">
                        <i class="bi bi-building me-1"></i>+ បន្ថែមផ្នែក
                    </a>
                    <a href="{{ route('positions.create') }}" class="btn btn-sm text-white" style="background:#d97706; border-color:#d97706;">
                        <i class="bi bi-award me-1"></i>+ បន្ថែមមុខតំណែង
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table align-middle">
                <thead>
                    {{-- Row 1: Section Header --}}
                    <tr>
                        <th rowspan="2" style="width:36px" class="text-center align-middle border-col-divider">#</th>
                        <th colspan="4" class="text-center border-col-divider" style="background:#fefce8; color:#92400e; font-size:0.78rem; font-weight:700; border-bottom:1px solid #fef08a;">
                            <i class="bi bi-building me-1"></i> ផ្នែក (Department)
                        </th>
                        <th colspan="4" class="text-center" style="background:#fffbeb; color:#b45309; font-size:0.78rem; font-weight:700; border-bottom:1px solid #fde68a;">
                            <i class="bi bi-award me-1"></i> មុខតំណែង (Position)
                        </th>
                    </tr>
                    {{-- Row 2: Sub-headers --}}
                    <tr>
                        {{-- Department Columns --}}
                        <th style="min-width:180px;">ឈ្មោះ</th>
                        <th style="width:95px;" class="text-center">បុគ្គលិក</th>
                        <th style="width:85px;" class="text-center">ស្ថានភាព</th>
                        <th style="width:85px;" class="text-end border-col-divider">សកម្មភាព</th>

                        {{-- Position Columns --}}
                        <th style="min-width:180px;">ឈ្មោះ</th>
                        <th style="width:95px;" class="text-center">បុគ្គលិក</th>
                        <th style="width:85px;" class="text-center">ស្ថានភាព</th>
                        <th style="width:85px;" class="text-end">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pairedRows as $i => $row)
                    @php
                        $dept = $row['department'] ?? null;
                        $pos = $row['position'] ?? null;
                    @endphp
                    <tr class="table-row-hover">
                        {{-- Row Index --}}
                        <td class="text-muted text-center border-col-divider" style="font-size:0.75rem;">
                            {{ $pairedRows->firstItem() + $i }}
                        </td>

                        {{-- DEPARTMENT HALF --}}
                        @if($dept)
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="type-icon" style="background:#fef3c7; color:#c98b1e;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <button type="button" class="btn p-0 text-start fw-bold text-dark text-decoration-none border-0 bg-transparent"
                                                style="font-size:0.83rem;"
                                                data-item="{{ json_encode($dept) }}"
                                                data-type="department"
                                                onclick="openViewUnified(this)">
                                            {{ $dept->name }}
                                        </button>
                                        @if($dept->code)
                                            <span class="badge ms-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem; font-weight:600; padding:0.15rem 0.35rem; border:1px solid #e2e8f0;">{{ $dept->code }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @php $dStaff = $dept->employees_count ?? 0; @endphp
                                <span class="badge" style="{{ $dStaff > 0 ? 'background:#e0f2fe;color:#0369a1;' : 'background:#f1f5f9;color:#64748b;' }} font-size:0.72rem; padding:0.2rem 0.45rem;">
                                    <i class="bi bi-people me-1"></i>{{ $dStaff }} នាក់
                                </span>
                            </td>
                            <td class="text-center">
                                @if($dept->status === 'active')
                                    <span class="badge" style="background:#ecfdf5; color:#047857; font-size:0.70rem; border-radius:999px; padding:0.15rem 0.5rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>សកម្ម
                                    </span>
                                @else
                                    <span class="badge" style="background:#f1f5f9; color:#64748b; font-size:0.70rem; border-radius:999px; padding:0.15rem 0.5rem;">
                                        <i class="bi bi-dash-circle me-1"></i>អសកម្ម
                                    </span>
                                @endif
                            </td>
                            <td class="text-end border-col-divider">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <button type="button" class="action-btn" title="មើលព័ត៌មានផ្នែក"
                                            data-item="{{ json_encode($dept) }}"
                                            data-type="department"
                                            onclick="openViewUnified(this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="action-btn" title="កែប្រែផ្នែក"
                                            data-item="{{ json_encode($dept) }}"
                                            data-type="department"
                                            onclick="openEditUnified(this)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if(($dept->employees_count ?? 0) == 0)
                                        <button type="button" class="action-btn action-btn-danger" title="លុបផ្នែក"
                                                onclick="openDeleteModal('department', {{ $dept->id }}, '{{ addslashes($dept->name) }}', {{ $dept->employees_count ?? 0 }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @else
                            <td colspan="4" class="text-center text-muted border-col-divider" style="font-size:0.75rem; background:#fafbfc;">
                                <span class="fst-italic">— គ្មានផ្នែក —</span>
                            </td>
                        @endif

                        {{-- POSITION HALF --}}
                        @if($pos)
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="type-icon" style="background:#fffbeb; color:#d97706;">
                                        <i class="bi bi-award"></i>
                                    </div>
                                    <div>
                                        <button type="button" class="btn p-0 text-start fw-bold text-dark text-decoration-none border-0 bg-transparent"
                                                style="font-size:0.83rem;"
                                                data-item="{{ json_encode($pos) }}"
                                                data-type="position"
                                                onclick="openViewUnified(this)">
                                            {{ $pos->name }}
                                        </button>
                                        @if($pos->code)
                                            <span class="badge ms-1" style="background:#f1f5f9; color:#475569; font-size:0.68rem; font-weight:600; padding:0.15rem 0.35rem; border:1px solid #e2e8f0;">{{ $pos->code }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @php $pStaff = $pos->employees_count ?? 0; @endphp
                                <span class="badge" style="{{ $pStaff > 0 ? 'background:#e0f2fe;color:#0369a1;' : 'background:#f1f5f9;color:#64748b;' }} font-size:0.72rem; padding:0.2rem 0.45rem;">
                                    <i class="bi bi-people me-1"></i>{{ $pStaff }} នាក់
                                </span>
                            </td>
                            <td class="text-center">
                                @if($pos->status === 'active')
                                    <span class="badge" style="background:#ecfdf5; color:#047857; font-size:0.70rem; border-radius:999px; padding:0.15rem 0.5rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>សកម្ម
                                    </span>
                                @else
                                    <span class="badge" style="background:#f1f5f9; color:#64748b; font-size:0.70rem; border-radius:999px; padding:0.15rem 0.5rem;">
                                        <i class="bi bi-dash-circle me-1"></i>អសកម្ម
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <button type="button" class="action-btn" title="មើលព័ត៌មានមុខតំណែង"
                                            data-item="{{ json_encode($pos) }}"
                                            data-type="position"
                                            onclick="openViewUnified(this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="action-btn" title="កែប្រែមុខតំណែង"
                                            data-item="{{ json_encode($pos) }}"
                                            data-type="position"
                                            onclick="openEditUnified(this)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @if(($pos->employees_count ?? 0) == 0)
                                        <button type="button" class="action-btn action-btn-danger" title="លុបមុខតំណែង"
                                                onclick="openDeleteModal('position', {{ $pos->id }}, '{{ addslashes($pos->name) }}', {{ $pos->employees_count ?? 0 }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @else
                            <td colspan="4" class="text-center text-muted" style="font-size:0.75rem; background:#fafbfc;">
                                <span class="fst-italic">— គ្មានមុខតំណែង —</span>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pairedRows->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-muted small">
                    បង្ហាញ {{ $pairedRows->firstItem() }} ដល់ {{ $pairedRows->lastItem() }} នៃ {{ $pairedRows->total() }} ជួរ
                </span>
                {{ $pairedRows->links() }}
            </div>
        @endif
    @endif
</div>

{{-- ═══════════════════════════════════════════
     MODAL 1: CREATE MODAL (Department or Position)
═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalCreateUnified" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:8px;">
            <div class="modal-header border-bottom px-3 py-2.5 d-flex justify-content-between align-items-center" style="background:#f8fafc;">
                <ul class="nav nav-pills" id="createModalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-1 px-3 fw-semibold" id="tab-create-dept-btn" data-bs-toggle="pill" data-bs-target="#tab-create-dept" type="button" role="tab" style="font-size:0.82rem;">
                            <i class="bi bi-building me-1"></i>បន្ថែមផ្នែក
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 px-3 fw-semibold" id="tab-create-pos-btn" data-bs-toggle="pill" data-bs-target="#tab-create-pos" type="button" role="tab" style="font-size:0.82rem;">
                            <i class="bi bi-award me-1"></i>បន្ថែមមុខតំណែង
                        </button>
                    </li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="tab-content" id="createModalTabContent">
                {{-- Form 1: Department --}}
                <div class="tab-pane fade show active" id="tab-create-dept" role="tabpanel">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-3">
                            <div class="p-2.5 rounded-2" style="background:#fafbfc; border:1px solid #eef2f6;">
                                <div class="d-flex align-items-center gap-2 mb-2 pb-1.5 border-bottom">
                                    <span class="d-flex align-items-center justify-content-center rounded-1" style="width:22px;height:22px;background:#fef3c7;color:#c98b1e;font-size:0.85rem;">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size:0.85rem;">ព័ត៌មានផ្នែក (Department)</h6>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-8">
                                        <label class="modal-form-label">ឈ្មោះផ្នែក <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="modal-form-control" value="{{ old('name') }}" placeholder="ឧ. រដ្ឋបាល (Administration)..." required>
                                        @error('name')<div class="text-danger mt-1" style="font-size:0.72rem;">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-4">
                                        <label class="modal-form-label">Code ID</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-end-0 text-muted px-2" title="ចាក់សោស្វ័យប្រវត្តិ">
                                                <i class="bi bi-lock-fill text-warning"></i>
                                            </span>
                                            <input type="text" name="code" class="modal-form-control bg-light border-start-0 font-monospace fw-bold" value="{{ old('code', $nextDepartmentCode ?? 'DEP-001') }}" readonly tabindex="-1" title="លេខកូដត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ និងចាក់សោ">
                                        </div>
                                        @error('code')<div class="text-danger mt-1" style="font-size:0.72rem;">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="modal-form-label">ស្ថានភាព</label>
                                    <select name="status" class="modal-form-control">
                                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>សកម្ម</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>អសកម្ម</option>
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="modal-form-label">ការពិពណ៌នា</label>
                                    <textarea name="description" class="modal-form-control" rows="2" placeholder="ពិពណ៌នាបន្ថែមអំពីផ្នែក...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:4px; font-size:0.82rem;" data-bs-dismiss="modal">បោះបង់</button>
                            <button type="submit" class="btn btn-sm btn-primary-action px-3.5" style="border-radius:4px; font-size:0.82rem;">
                                <i class="bi bi-check2-circle me-1"></i> រក្សាទុកផ្នែក
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Form 2: Position --}}
                <div class="tab-pane fade" id="tab-create-pos" role="tabpanel">
                    <form action="{{ route('positions.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-3">
                            <div class="p-2.5 rounded-2" style="background:#fafbfc; border:1px solid #eef2f6;">
                                <div class="d-flex align-items-center gap-2 mb-2 pb-1.5 border-bottom">
                                    <span class="d-flex align-items-center justify-content-center rounded-1" style="width:22px;height:22px;background:#fffbeb;color:#d97706;font-size:0.85rem;">
                                        <i class="bi bi-award"></i>
                                    </span>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size:0.85rem;">ព័ត៌មានមុខតំណែង (Position)</h6>
                                </div>

                                <div class="row g-2 mb-2">
                                    <div class="col-8">
                                        <label class="modal-form-label">ឈ្មោះមុខតំណែង <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="modal-form-control" value="{{ old('name') }}" placeholder="ឧ. នាយកសាលា, គ្រូបង្រៀន..." required>
                                        @error('name')<div class="text-danger mt-1" style="font-size:0.72rem;">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-4">
                                        <label class="modal-form-label">Code ID</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-end-0 text-muted px-2" title="ចាក់សោស្វ័យប្រវត្តិ">
                                                <i class="bi bi-lock-fill text-warning"></i>
                                            </span>
                                            <input type="text" name="code" class="modal-form-control bg-light border-start-0 font-monospace fw-bold" value="{{ old('code', $nextPositionCode ?? 'POS-001') }}" readonly tabindex="-1" title="លេខកូដត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ និងចាក់សោ">
                                        </div>
                                        @error('code')<div class="text-danger mt-1" style="font-size:0.72rem;">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="modal-form-label">ស្ថានភាព</label>
                                    <select name="status" class="modal-form-control">
                                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>សកម្ម</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>អសកម្ម</option>
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="modal-form-label">ការពិពណ៌នាការងារ</label>
                                    <textarea name="description" class="modal-form-control" rows="2" placeholder="ពិពណ៌នាអំពីតួនាទី និងភារកិច្ច...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:4px; font-size:0.82rem;" data-bs-dismiss="modal">បោះបង់</button>
                            <button type="submit" class="btn btn-sm px-3.5 text-white" style="border-radius:4px; font-size:0.82rem; background:#d97706; border-color:#d97706;">
                                <i class="bi bi-check2-circle me-1"></i> រក្សាទុកមុខតំណែង
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL 2: UNIFIED VIEW / DETAIL MODAL
═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalViewUnified" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:8px;">
            <div class="modal-header border-bottom px-3 py-2.5 d-flex justify-content-between align-items-center" style="background:#f8fafc;">
                <div class="d-flex align-items-center gap-2">
                    <span id="viewModalIconWrapper" class="d-flex align-items-center justify-content-center rounded-1" style="width:28px;height:28px;background:#fef3c7;color:#c98b1e;font-size:0.95rem;">
                        <i class="bi bi-eye-fill"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" style="font-size:0.95rem;" id="viewModalTitle">ព័ត៌មានលម្អិត</h5>
                        <span class="text-muted" style="font-size:0.72rem;" id="viewModalSubtitle">Department / Position Detail</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="p-2.5 rounded-2" style="background:#fafbfc; border:1px solid #eef2f6;">
                    <div class="d-flex align-items-center gap-2 mb-2 pb-1.5 border-bottom">
                        <span id="viewModalIconSection" class="d-flex align-items-center justify-content-center rounded-1" style="width:22px;height:22px;background:#fef3c7;color:#c98b1e;font-size:0.85rem;">
                            <i class="bi bi-building"></i>
                        </span>
                        <span class="badge" id="viewTypeBadge" style="background:#fef3c7; color:#b45309; font-size:0.72rem; border-radius:4px; padding:0.2rem 0.45rem;">
                            ផ្នែក
                        </span>
                        <span class="text-muted ms-auto" style="font-size:0.7rem;" id="viewUnifiedId"></span>
                    </div>

                    <div class="detail-item">
                        <div class="detail-item-label" id="viewNameLabel">ឈ្មោះ</div>
                        <div class="detail-item-value" id="viewUnifiedName">—</div>
                    </div>

                    <div class="detail-item mb-2" id="viewCodeWrapper" style="display:none;">
                        <div class="detail-item-label">លេខកូដ (Code ID)</div>
                        <div class="detail-item-value" id="viewUnifiedCode">—</div>
                    </div>

                    <div class="mb-2" id="viewStatusCol">
                        <div class="detail-item mb-1">
                            <div class="detail-item-label">ស្ថានភាព (Status)</div>
                            <div class="detail-item-value" id="viewUnifiedStatus">—</div>
                        </div>
                    </div>

                    <div class="detail-item mb-2">
                        <div class="detail-item-label">បុគ្គលិកសរុប (Staff Assigned)</div>
                        <div class="detail-item-value" id="viewUnifiedStaffCount">0 នាក់</div>
                    </div>

                    <div class="detail-item mb-0">
                        <div class="detail-item-label">ការពិពណ៌នា (Description)</div>
                        <div class="detail-item-value text-muted fw-normal" style="font-size:0.82rem;" id="viewUnifiedDescription">គ្មានព័ត៌មានពិពណ៌នា</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-between align-items-center">
                <a href="#" id="viewFullPageLink" class="btn btn-sm btn-outline-secondary px-2.5 py-1 text-dark text-decoration-none" style="border-radius:4px; font-size:0.82rem;">
                    <i class="bi bi-box-arrow-up-right me-1"></i>ទំព័រពេញ
                </a>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:4px; font-size:0.82rem;" data-bs-dismiss="modal">បិទ</button>
                    <button type="button" class="btn btn-sm btn-primary-action px-3" style="border-radius:4px; font-size:0.82rem;" onclick="switchToEditUnified()">
                        <i class="bi bi-pencil me-1"></i>កែប្រែ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL 3: UNIFIED EDIT MODAL (Department or Position)
═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditUnified" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:8px;">
            <form id="formEditUnified" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom px-3 py-2.5 d-flex justify-content-between align-items-center" style="background:#f8fafc;">
                    <div class="d-flex align-items-center gap-2">
                        <span id="editModalIconWrapper" class="d-flex align-items-center justify-content-center rounded-1" style="width:28px;height:28px;background:#fef3c7;color:#c98b1e;font-size:0.95rem;">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" style="font-size:0.95rem;" id="editModalTitle">កែប្រែព័ត៌មាន</h5>
                            <span class="text-muted" style="font-size:0.72rem;" id="editModalSubtitle">កែប្រែ Department / Position</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="p-2.5 rounded-2" style="background:#fafbfc; border:1px solid #eef2f6;">
                        <div class="d-flex align-items-center gap-2 mb-2 pb-1.5 border-bottom">
                            <span id="editModalIconSection" class="d-flex align-items-center justify-content-center rounded-1" style="width:22px;height:22px;background:#fef3c7;color:#c98b1e;font-size:0.85rem;">
                                <i class="bi bi-building"></i>
                            </span>
                            <h6 class="fw-bold text-dark mb-0" style="font-size:0.85rem;" id="editSectionTitle">ព័ត៌មានផ្នែក</h6>
                            <span class="badge" id="editTypeBadge" style="background:#fef3c7; color:#b45309; font-size:0.7rem; border-radius:4px; padding:0.2rem 0.4rem;">Department</span>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-8">
                                <label class="modal-form-label" id="editNameLabel">ឈ្មោះ <span class="text-danger">*</span></label>
                                <input type="text" id="editUnifiedName" name="name" class="modal-form-control" required placeholder="ឈ្មោះ...">
                            </div>
                            <div class="col-4">
                                <label class="modal-form-label">Code ID</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0 text-muted px-2" id="editCodeLockIcon" style="display:none;" title="ចាក់សោកុំឱ្យកែប្រែ">
                                        <i class="bi bi-lock-fill text-warning"></i>
                                    </span>
                                    <input type="text" id="editUnifiedCode" name="code" class="modal-form-control" placeholder="ឧ. DEP-001...">
                                </div>
                            </div>
                        </div>

                        <div class="mb-2" id="editStatusCol">
                            <label class="modal-form-label">ស្ថានភាព</label>
                            <select id="editUnifiedStatus" name="status" class="modal-form-control">
                                <option value="active">សកម្ម</option>
                                <option value="inactive">អសកម្ម</option>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="modal-form-label">ការពិពណ៌នា</label>
                            <textarea id="editUnifiedDescription" name="description" class="modal-form-control" rows="2" placeholder="ពិពណ៌នាបន្ថែម..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:4px; font-size:0.82rem;" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3.5" style="border-radius:4px; font-size:0.82rem;">
                        <i class="bi bi-check2-circle me-1"></i> រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL 4: DELETE CONFIRMATION MODAL
═══════════════════════════════════════════ --}}
<div class="modal fade" id="modalDeleteConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius:8px;">
            <form id="deleteConfirmForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width:48px;height:48px;background:#fef2f2;color:#dc2626;font-size:1.4rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" id="deleteModalTitle">លុបទិន្នន័យ</h6>
                    <p class="text-muted small mb-2" style="font-size:0.8rem;">
                        តើអ្នកពិតជាចង់លុប <strong id="deleteTargetName" class="text-dark"></strong> មែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានឡើយ។
                    </p>
                </div>
                <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:4px; font-size:0.82rem;" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-danger px-3" style="border-radius:4px; font-size:0.82rem;">
                        <i class="bi bi-trash me-1"></i>លុបចេញ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentViewItem = null;
    let currentViewType = null;

    function getEntityData(el, dataKey) {
        if (!el) return null;
        if (el.dataset && el.dataset[dataKey]) {
            try {
                return JSON.parse(el.dataset[dataKey]);
            } catch(e) {
                return el.dataset[dataKey];
            }
        }
        return null;
    }

    function openCreateUnifiedModal(defaultFocus) {
        if (defaultFocus === 'position') {
            const posTabBtn = document.getElementById('tab-create-pos-btn');
            if (posTabBtn) {
                const tab = new bootstrap.Tab(posTabBtn);
                tab.show();
            }
        } else {
            const deptTabBtn = document.getElementById('tab-create-dept-btn');
            if (deptTabBtn) {
                const tab = new bootstrap.Tab(deptTabBtn);
                tab.show();
            }
        }
        const modal = new bootstrap.Modal(document.getElementById('modalCreateUnified'));
        modal.show();
    }

    function openViewUnified(el) {
        let item = null;
        let type = null;
        if (el instanceof HTMLElement) {
            item = getEntityData(el, 'item');
            type = el.dataset ? el.dataset.type : null;
        } else if (typeof el === 'object') {
            item = el;
            type = el.entity_type;
        }

        if (!item) return;
        type = type || item.entity_type || 'department';
        currentViewItem = item;
        currentViewType = type;

        const isDept = (type === 'department');
        const titleEl   = document.getElementById('viewModalTitle');
        const subtitleEl = document.getElementById('viewModalSubtitle');
        const badge     = document.getElementById('viewTypeBadge');
        const nameLabel = document.getElementById('viewNameLabel');
        const iconWrapper  = document.getElementById('viewModalIconWrapper');
        const iconSection  = document.getElementById('viewModalIconSection');
        const idEl      = document.getElementById('viewUnifiedId');

        if (isDept) {
            titleEl.textContent = 'ព័ត៌មានផ្នែក';
            subtitleEl.textContent = 'Department Detail';
            badge.style.cssText = 'background:#fef3c7; color:#b45309; font-size:0.72rem; border-radius:4px; padding:0.2rem 0.45rem;';
            badge.innerHTML = '<i class="bi bi-building me-1"></i>ផ្នែក';
            nameLabel.textContent = 'ឈ្មោះផ្នែក';
            if (iconWrapper) { iconWrapper.style.background = '#fef3c7'; iconWrapper.style.color = '#c98b1e'; iconWrapper.innerHTML = '<i class="bi bi-building"></i>'; }
            if (iconSection) { iconSection.style.background = '#fef3c7'; iconSection.style.color = '#c98b1e'; iconSection.innerHTML = '<i class="bi bi-building"></i>'; }
        } else {
            titleEl.textContent = 'ព័ត៌មានមុខតំណែង';
            subtitleEl.textContent = 'Position Detail';
            badge.style.cssText = 'background:#fffbeb; color:#d97706; border:1px solid #fde68a; font-size:0.72rem; border-radius:4px; padding:0.2rem 0.45rem;';
            badge.innerHTML = '<i class="bi bi-award me-1"></i>មុខតំណែង';
            nameLabel.textContent = 'ឈ្មោះមុខតំណែង (Position Title)';
            if (iconWrapper) { iconWrapper.style.background = '#fffbeb'; iconWrapper.style.color = '#d97706'; iconWrapper.innerHTML = '<i class="bi bi-award"></i>'; }
            if (iconSection) { iconSection.style.background = '#fffbeb'; iconSection.style.color = '#d97706'; iconSection.innerHTML = '<i class="bi bi-award"></i>'; }
        }

        if (idEl) idEl.textContent = 'ID: #' + (item.id || '—');
        document.getElementById('viewUnifiedName').textContent = item.name || '—';

        const codeWrapper = document.getElementById('viewCodeWrapper');
        const codeEl = document.getElementById('viewUnifiedCode');
        if (codeWrapper && codeEl) {
            if (item.code) {
                codeWrapper.style.display = 'block';
                codeEl.innerHTML = `<span class="badge" style="background:#f1f5f9; color:#475569; font-size:0.75rem; border:1px solid #e2e8f0; font-weight:600;">${item.code}</span>`;
            } else {
                codeWrapper.style.display = 'none';
                codeEl.textContent = '—';
            }
        }


        const staffCount = item.employees_count || 0;
        const staffEl = document.getElementById('viewUnifiedStaffCount');
        staffEl.textContent = staffCount + ' នាក់';
        staffEl.style.color = staffCount > 0 ? '#0369a1' : '#475569';

        const statusEl = document.getElementById('viewUnifiedStatus');
        if (item.status === 'active') {
            statusEl.innerHTML = '<span style="color:#047857; font-weight:600;"><i class="bi bi-check-circle-fill me-1"></i>សកម្ម (Active)</span>';
        } else {
            statusEl.innerHTML = '<span style="color:#64748b;"><i class="bi bi-dash-circle me-1"></i>អសកម្ម (Inactive)</span>';
        }
        document.getElementById('viewUnifiedDescription').textContent = item.description || 'គ្មានព័ត៌មានពិពណ៌នា';

        const fullPageLink = document.getElementById('viewFullPageLink');
        if (fullPageLink) {
            fullPageLink.href = isDept ? `/departments/${item.id}` : `/positions/${item.id}`;
        }

        new bootstrap.Modal(document.getElementById('modalViewUnified')).show();
    }

    function openEditUnified(el, explicitType) {
        let item = null;
        let type = explicitType;
        if (el instanceof HTMLElement) {
            item = getEntityData(el, 'item');
            type = type || (el.dataset ? el.dataset.type : null);
        } else if (typeof el === 'object') {
            item = el;
        }
        if (!item || !item.id) return;
        type = type || item.entity_type || 'department';
        const isDept = (type === 'department');
        const form = document.getElementById('formEditUnified');
        const iconWrapper     = document.getElementById('editModalIconWrapper');
        const iconSection     = document.getElementById('editModalIconSection');
        const sectionTitleEl  = document.getElementById('editSectionTitle');
        const badge           = document.getElementById('editTypeBadge');
        const subtitleEl      = document.getElementById('editModalSubtitle');


        const codeInput       = document.getElementById('editUnifiedCode');
        const lockIcon        = document.getElementById('editCodeLockIcon');

        if (isDept) {
            form.action = `/departments/${item.id}`;
            document.getElementById('editModalTitle').textContent = 'កែប្រែផ្នែក';
            if (subtitleEl) subtitleEl.textContent = 'Edit Department';
            if (sectionTitleEl) sectionTitleEl.textContent = 'ព័ត៌មានផ្នែក';
            document.getElementById('editNameLabel').innerHTML = 'ឈ្មោះផ្នែក <span class="text-danger">*</span>';
            document.getElementById('editUnifiedName').placeholder = 'ឧ. រដ្ឋបាល (Administration)...';
            badge.style.cssText = 'background:#fef3c7; color:#b45309; font-size:0.7rem; border-radius:4px; padding:0.2rem 0.4rem;';
            badge.textContent = 'Department';
            if (iconWrapper) { iconWrapper.style.background = '#fef3c7'; iconWrapper.style.color = '#c98b1e'; iconWrapper.innerHTML = '<i class="bi bi-pencil-square"></i>'; }
            if (iconSection) { iconSection.style.background = '#fef3c7'; iconSection.style.color = '#c98b1e'; iconSection.innerHTML = '<i class="bi bi-building"></i>'; }
        } else {
            form.action = `/positions/${item.id}`;
            document.getElementById('editModalTitle').textContent = 'កែប្រែមុខតំណែង';
            if (subtitleEl) subtitleEl.textContent = 'Edit Position';
            if (sectionTitleEl) sectionTitleEl.textContent = 'ព័ត៌មានមុខតំណែង';
            document.getElementById('editNameLabel').innerHTML = 'ឈ្មោះមុខតំណែង (Position Title) <span class="text-danger">*</span>';
            document.getElementById('editUnifiedName').placeholder = 'ឧ. នាយកសាលា, គ្រូបង្រៀន...';
            badge.style.cssText = 'background:#fffbeb; color:#d97706; border:1px solid #fde68a; font-size:0.7rem; border-radius:4px; padding:0.2rem 0.4rem;';
            badge.textContent = 'Position';
            if (iconWrapper) { iconWrapper.style.background = '#fffbeb'; iconWrapper.style.color = '#d97706'; iconWrapper.innerHTML = '<i class="bi bi-pencil-square"></i>'; }
            if (iconSection) { iconSection.style.background = '#fffbeb'; iconSection.style.color = '#d97706'; iconSection.innerHTML = '<i class="bi bi-award"></i>'; }
        }

        if (codeInput) {
            codeInput.readOnly = true;
            codeInput.tabIndex = -1;
            codeInput.classList.add('bg-light', 'border-start-0', 'font-monospace', 'fw-bold');
        }
        if (lockIcon) lockIcon.style.display = 'inline-flex';

        document.getElementById('editUnifiedName').value = item.name || '';
        document.getElementById('editUnifiedCode').value = item.code || '';
        document.getElementById('editUnifiedStatus').value = item.status || 'active';
        document.getElementById('editUnifiedDescription').value = item.description || '';

        new bootstrap.Modal(document.getElementById('modalEditUnified')).show();
    }

    function switchToEditUnified() {
        const viewEl = document.getElementById('modalViewUnified');
        const viewModal = bootstrap.Modal.getInstance(viewEl);
        if (viewModal) {
            viewModal.hide();
        }
        setTimeout(() => {
            if (currentViewItem) {
                openEditUnified(currentViewItem, currentViewType);
            }
        }, 300);
    }

    function openDeleteModal(type, id, name, staffCount) {
        const form = document.getElementById('deleteConfirmForm');
        const titleEl = document.getElementById('deleteModalTitle');
        const nameEl = document.getElementById('deleteTargetName');

        nameEl.textContent = name;

        if (type === 'department') {
            titleEl.textContent = 'លុបផ្នែក (Delete Department)';
            form.action = `/departments/${id}`;
        } else {
            titleEl.textContent = 'លុបមុខតំណែង (Delete Position)';
            form.action = `/positions/${id}`;
        }

        new bootstrap.Modal(document.getElementById('modalDeleteConfirm')).show();
    }
</script>
@endpush
@endsection
