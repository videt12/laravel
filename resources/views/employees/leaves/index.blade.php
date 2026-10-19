@extends('layouts.app')

@section('title', 'ច្បាប់ឈប់សម្រាកបុគ្គលិក — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">បុគ្គលិក</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ច្បាប់ឈប់សម្រាក</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════════ */
.page-title {
    font-size: 1.4rem; font-weight: 800; color: var(--text-dark);
    margin-bottom: 0.2rem; display: flex; align-items: center; gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; background: #fef3c7; color: #b45309;
    border-radius: var(--radius-md); font-size: 1.15rem;
}
.page-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #fff; border: 1px solid var(--border-soft, #e2e8f0); border-radius: var(--radius-lg, 10px);
    padding: 0.75rem 1.25rem; display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
.search-wrapper { position: relative; width: 280px; }
.search-wrapper i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-wrapper input {
    width: 100%; height: 38px; padding: 0.45rem 1rem 0.45rem 2.4rem; font-size: 0.85rem;
    border: 1px solid var(--border, #cbd5e1); border-radius: 6px; transition: all 0.2s;
}
.search-wrapper input:focus, .form-select-custom:focus { border-color: #e8a838; box-shadow: 0 0 0 3px rgba(232, 168, 56, .15); outline: none; }
.form-select-custom {
    width: auto !important; min-width: 180px; height: 38px;
    padding: 0.45rem 2rem 0.45rem 1rem; font-size: 0.85rem;
    border: 1px solid var(--border, #cbd5e1); border-radius: 6px; transition: all 0.2s;
    background-color: #fff;
}

/* ═══════════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff; border: 1px solid var(--border-soft); border-top: 3px solid #e8a838; border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #fffbeb; color: #78350f; font-size: 0.72rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;
    padding: 1rem 1.25rem; border-bottom: 1px solid #fde68a; white-space: nowrap;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fffdf5; }
.table-custom tbody td {
    padding: 1rem 1.25rem; font-size: 0.85rem; color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft); vertical-align: middle;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════════
   CUSTOM UI COMPONENTS
═══════════════════════════════════════════════════════════════════ */
.emp-name { font-weight: 600; color: #1e293b; text-decoration: none; }
.emp-name:hover { color: #c98b1e; text-decoration: underline; }
.emp-id { font-size: 0.75rem; color: var(--text-muted); }
.emp-avatar {
    width: 36px; height: 36px; border-radius: 50%; background: #fef3c7;
    display: flex; align-items: center; justify-content: center; color: #b45309;
    font-weight: 600; font-size: 0.85rem; flex-shrink: 0;
}

/* Badges */
.status-badge {
    display: inline-flex; align-items: center; justify-content: center;
    padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
}
.status-approved { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.status-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.status-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }

.leave-type-badge {
    display: inline-flex; align-items: center; padding: 0.2rem 0.6rem;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: var(--radius-sm);
    font-size: 0.75rem; color: #475569; font-weight: 500;
}

/* Date Range */
.date-range {
    display: flex; flex-direction: column; gap: 0.2rem; font-size: 0.8rem;
}
.date-val { display: flex; align-items: center; gap: 0.35rem; color: #334155; }
.duration-chip {
    display: inline-block; font-size: 0.7rem; color: #b45309; background: #fef3c7;
    padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600; margin-top: 0.2rem;
}

/* Table Actions */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: var(--radius-sm);
    color: var(--text-muted); transition: all 0.2s; background: transparent; border: none;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-calendar-x"></i></div>
            ច្បាប់ឈប់សម្រាកបុគ្គលិក
        </h1>
        <p class="page-subtitle">តាមដាន និងគ្រប់គ្រងពាក្យស្នើសុំច្បាប់ឈប់សម្រាករបស់បុគ្គលិក។</p>
    </div>
    <div>
        <a href="{{ route('staff-leaves.create') }}" class="btn btn-primary-action shadow-sm text-white" style="background:#e8a838; border-color:#e8a838;">
            <i class="bi bi-plus-circle me-2"></i>+ កត់ត្រាច្បាប់ឈប់សម្រាក
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-card alert-card-success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     FILTER BAR
═══════════════════════════════════════════════════════════════════ --}}
<div class="filter-bar mb-3">
    <form action="{{ route('staff-leaves.index') }}" method="GET" class="filter-inputs m-0 w-100 align-items-center" id="filterForm">
        
        {{-- Search --}}
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះបុគ្គលិក...">
        </div>

        {{-- Status Filter --}}
        <select name="status" class="form-select form-select-custom" style="width: auto; min-width: 180px; height: 38px;" onchange="document.getElementById('filterForm').submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>រង់ចាំការអនុម័ត</option>
            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>បានអនុម័ត</option>
            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>បដិសេធ</option>
        </select>
        
        <button type="submit" class="btn btn-sm btn-outline-secondary px-3 d-inline-flex align-items-center" style="height: 38px; font-size: 0.82rem; border-radius: 6px;">
            <i class="bi bi-funnel me-1"></i>ស្វែងរក
        </button>

        {{-- Active Filters Chip (if any) --}}
        @if(request()->filled('search') || request()->filled('status'))
            <a href="{{ route('staff-leaves.index') }}" class="btn btn-sm btn-outline-danger ms-auto d-inline-flex align-items-center" style="height: 38px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-arrow-counterclockwise me-1"></i>កំណត់ឡើងវិញ
            </a>
        @endif
    </form>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     DATA TABLE
═══════════════════════════════════════════════════════════════════ --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>បុគ្គលិក</th>
                    <th>ព័ត៌មានលម្អិតនៃច្បាប់</th>
                    <th>រយៈពេលឈប់</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="emp-avatar">
                                    {{ substr($leave->employee?->user?->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    @if($leave->employee)
                                        <a href="{{ route('employees.show', $leave->employee) }}" class="emp-name d-block">
                                            {{ $leave->employee->user?->name ?? 'មិនស្គាល់' }}
                                        </a>
                                        <div class="emp-id">{{ $leave->employee->employee_code }}</div>
                                    @else
                                        <span class="emp-name d-block text-muted">
                                            {{ $leave->employee?->user?->name ?? 'មិនស្គាល់' }}
                                        </span>
                                        <div class="emp-id text-muted">—</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="leave-type-badge">
                                    <i class="bi bi-tag me-1 text-muted"></i> {{ $leave->leave_type?->khmer_name ?? $leave->leave_type?->name ?? 'មិនស្គាល់ប្រភេទ' }}
                                </span>
                            </div>
                            @if($leave->reason)
                                <div class="text-truncate" style="max-width: 250px; font-size: 0.75rem; color: #64748b;" title="{{ $leave->reason }}">
                                    "{{ $leave->reason }}"
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="date-range">
                                <div class="date-val"><i class="bi bi-calendar-event text-secondary"></i> {{ $leave->start_date->format('M d, Y') }}</div>
                                <div class="date-val"><i class="bi bi-arrow-right text-secondary"></i> {{ $leave->end_date->format('M d, Y') }}</div>
                                @php
                                    // Calculate days inclusive
                                    $days = $leave->start_date->diffInDays($leave->end_date) + 1;
                                @endphp
                                <div><span class="duration-chip">{{ $days }} ថ្ងៃ</span></div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = match(strtolower($leave->status)) {
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected',
                                    'pending' => 'status-pending',
                                    default => 'bg-secondary text-white'
                                };
                                $statusLabel = match(strtolower($leave->status)) {
                                    'approved' => 'បានអនុម័ត',
                                    'rejected' => 'បដិសេធ',
                                    'pending' => 'រង់ចាំការអនុម័ត',
                                    default => $leave->status
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                            @if($leave->status == 'Approved' && $leave->approvedBy)
                                <div class="mt-1" style="font-size:0.65rem; color:#64748b;">
                                    ដោយ {{ $leave->approvedBy->name }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('staff-leaves.show', $leave) }}" class="action-btn" data-bs-toggle="tooltip" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('staff-leaves.edit', $leave) }}" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួល">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="action-btn action-btn-danger" 
                                        data-bs-toggle="tooltip" title="លុប"
                                        onclick="confirmDelete({{ $leave->id }}, '{{ addslashes($leave->employee?->user?->name ?? 'Unknown') }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-calendar-x fs-1"></i></div>
                            <h6 class="fw-bold text-dark">រកមិនឃើញពាក្យស្នើសុំច្បាប់ទេ</h6>
                            <p class="text-muted small mb-0">សូមព្យាយាមផ្លាស់ប្តូរការច្រោះស្វែងរក ឬកត់ត្រាច្បាប់ឈប់សម្រាកថ្មី។</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($leaves->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $leaves->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:56px;height:56px">
                        <i class="bi bi-trash text-danger fs-3"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1">លុបពាក្យស្នើសុំច្បាប់?</h6>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបពាក្យស្នើសុំច្បាប់របស់បុគ្គលិក <strong id="deleteEmpName"></strong> នេះមែនទេ?
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">យល់ព្រមលុប</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    function confirmDelete(id, name) {
        document.getElementById('deleteEmpName').textContent = name;
        document.getElementById('deleteForm').action = `/staff-leaves/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endsection
