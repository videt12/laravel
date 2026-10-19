@extends('layouts.app')

@section('title', 'Manage Positions — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Staff</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Positions</li>
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
.page-subtitle { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   ORGANIZATION TABS
═══════════════════════════════════════════════════════════════════ */
.org-tabs-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    margin-bottom: 1.5rem;
}
.org-tabs-wrapper::-webkit-scrollbar { display: none; }
.org-tabs {
    display: flex;
    gap: 0.5rem;
    border-bottom: 2px solid var(--border-soft);
    white-space: nowrap;
}
.org-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.35rem;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-muted);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s ease;
}
.org-tab:hover { color: #c98b1e; }
.org-tab.active {
    color: #b45309;
    border-bottom-color: #e8a838;
}
.org-tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 22px;
    padding: 0 7px;
    background: #f1f5f9;
    color: var(--text-muted);
    border-radius: 999px;
    font-size: 0.8125rem;
    font-weight: 700;
}
.org-tab.active .org-tab-count {
    background: #fef3c7;
    color: #b45309;
}

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
.search-wrapper { position: relative; width: 320px; }
.search-wrapper i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-wrapper input {
    width: 100%; padding: 0.5rem 1rem 0.5rem 2.4rem; font-size: 0.9rem;
    border: 1px solid var(--border); border-radius: var(--radius-md); transition: all 0.2s;
}
.search-wrapper input:focus { border-color: #e8a838; box-shadow: 0 0 0 3px rgba(232, 168, 56, .15); outline: none; }

/* ═══════════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc; color: var(--text-muted); font-size: 0.85rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-soft); white-space: nowrap;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fafbfc; }
.table-custom tbody td {
    padding: 1rem 1.25rem; font-size: 0.9375rem; color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft); vertical-align: middle;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════════
   CUSTOM UI COMPONENTS
═══════════════════════════════════════════════════════════════════ */
.pos-name { font-weight: 600; color: #1e293b; font-size: 0.95rem; }
.pos-icon-wrapper {
    width: 38px; height: 38px; border-radius: 8px; background: #f1f5f9;
    display: flex; align-items: center; justify-content: center; color: #475569;
}
.staff-count {
    display: inline-flex; align-items: center; padding: 0.3rem 0.8rem;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 999px;
    font-size: 0.8125rem; font-weight: 600; color: #334155; gap: 0.4rem;
}

/* Table Actions */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 34px; height: 34px; border-radius: var(--radius-sm);
    color: var(--text-muted); transition: all 0.2s; background: transparent; border: none;
    font-size: 0.95rem;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }

/* Modal Custom Styling */
.modal-form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.35rem;
}
.modal-form-control {
    font-size: 0.9375rem;
    border-radius: var(--radius-sm, 6px);
    border: 1px solid #cbd5e1;
    padding: 0.55rem 0.85rem;
    color: #1e293b;
    background-color: #fff;
    width: 100%;
}
.modal-form-control:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 3px rgba(232, 168, 56, .15);
    outline: none;
}
.detail-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.75rem;
}
.detail-item-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}
.detail-item-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-award"></i></div>
            Manage Positions
        </h1>
        <p class="page-subtitle">Define job titles and positions for your staff.</p>
    </div>
    <div>
        <a href="{{ route('positions.create') }}" class="btn btn-primary-action shadow-sm text-white" style="background:#e8a838; border-color:#e8a838; font-size:0.875rem;">
            <i class="bi bi-plus-circle me-2"></i>New Position
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-card alert-card-success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="alert-card alert-card-danger mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     ORGANIZATION TABS (DEPARTMENTS & POSITIONS)
═══════════════════════════════════════════════════════════════════ --}}
<div class="org-tabs-wrapper mb-4">
    <div class="org-tabs">
        <a href="{{ route('departments.index') }}" class="org-tab {{ request()->routeIs('departments.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>Departments (ដេប៉ាតឺម៉ង់ / ផ្នែក)</span>
            <span class="org-tab-count">{{ \App\Models\Employees\EmployeeDepartment::count() }}</span>
        </a>
        <a href="{{ route('positions.index') }}" class="org-tab {{ request()->routeIs('positions.*') ? 'active' : '' }}">
            <i class="bi bi-award"></i>
            <span>Positions (មុខតំណែង / តួនាទី)</span>
            <span class="org-tab-count">{{ \App\Models\Employees\EmployeePosition::count() }}</span>
        </a>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     FILTER BAR
═══════════════════════════════════════════════════════════════════ --}}
<div class="filter-bar mb-4">
    <form action="{{ route('positions.index') }}" method="GET" class="filter-inputs m-0 w-100">
        
        {{-- Search --}}
        <div class="search-wrapper flex-grow-1" style="max-width: 350px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by position name...">
        </div>

        {{-- Active Filters Chip (if any) --}}
        @if(request()->filled('search'))
            <a href="{{ route('positions.index') }}" class="btn btn-sm btn-ghost ms-2 text-danger" style="font-size:0.75rem">
                <i class="bi bi-x-circle me-1"></i>Clear Search
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
                    <th>Position Title</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Staff Count</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $pos)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="pos-icon-wrapper flex-shrink-0">
                                    <i class="bi bi-award"></i>
                                </div>
                                <a href="{{ route('positions.show', $pos) }}" class="pos-name text-decoration-none">
                                    {{ $pos->name }}
                                </a>
                            </div>
                        </td>
                        <td>
                            @if($pos->code)
                                <span class="badge" style="background:#e0f2fe; color:#0369a1; font-weight:600; font-family:monospace; font-size:0.8125rem;">{{ $pos->code }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if(($pos->status ?? 'active') === 'active')
                                <span class="badge" style="background:#ecfdf5; color:#047857; font-weight:600; font-size:0.8125rem;"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                            @else
                                <span class="badge" style="background:#f1f5f9; color:#64748b; font-weight:600; font-size:0.8125rem;"><i class="bi bi-dash-circle me-1"></i>Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($pos->description)
                                <span class="text-truncate d-inline-block" style="max-width: 250px;" title="{{ $pos->description }}">
                                    {{ Str::limit($pos->description, 50) }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">No description</span>
                            @endif
                        </td>
                        <td>
                            <div class="staff-count">
                                <i class="bi bi-people-fill text-muted"></i>
                                {{ $pos->employees_count }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('positions.show', $pos) }}" class="action-btn" data-bs-toggle="tooltip" title="View Position">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('positions.edit', $pos) }}" class="action-btn" data-bs-toggle="tooltip" title="Edit Position">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($pos->employees_count == 0)
                                    <button type="button" class="action-btn action-btn-danger" 
                                            data-bs-toggle="tooltip" title="Delete Position"
                                            onclick="confirmDelete({{ $pos->id }}, '{{ addslashes($pos->name) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="action-btn" disabled 
                                            data-bs-toggle="tooltip" title="Cannot delete position with active staff"
                                            style="opacity: 0.4; cursor: not-allowed;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-award fs-1"></i></div>
                            <h6 class="fw-bold text-dark">No Positions Found</h6>
                            <p class="text-muted small mb-0">Try adjusting your search or create a new position.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($positions, 'hasPages') && $positions->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $positions->links('pagination::bootstrap-5') }}
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
                <h6 class="fw-bold mb-1">Delete Position?</h6>
                <p class="text-muted small mb-4">
                    Are you sure you want to delete the <strong id="deletePosName"></strong> position?
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    function confirmDelete(id, name) {
        document.getElementById('deletePosName').textContent = name;
        document.getElementById('deleteForm').action = `/positions/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endsection
