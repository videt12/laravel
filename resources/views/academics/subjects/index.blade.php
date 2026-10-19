@extends('layouts.app')

@section('title', 'Manage Subjects — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Academic</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Subjects</li>
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
    width: 36px; height: 36px; background: #fdf4ff; color: #c026d3;
    border-radius: var(--radius-md); font-size: 1.15rem;
}
.page-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
.search-wrapper { position: relative; width: 280px; }
.search-wrapper i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.search-wrapper input {
    width: 100%; padding: 0.45rem 1rem 0.45rem 2.4rem; font-size: 0.85rem;
    border: 1px solid var(--border); border-radius: var(--radius-md); transition: all 0.2s;
}
.search-wrapper input:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }
.filter-select {
    font-size: 0.85rem; padding: 0.45rem 2.25rem 0.45rem 0.85rem;
    border: 1px solid var(--border); border-radius: var(--radius-md); background-color: #fff;
}
.filter-select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }

/* ═══════════════════════════════════════════════════════════════════
   DATA TABLE
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc; color: var(--text-muted); font-size: 0.72rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;
    padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-soft); white-space: nowrap;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fafbfc; }
.table-custom tbody td {
    padding: 1rem 1.25rem; font-size: 0.85rem; color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft); vertical-align: middle;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════════
   CUSTOM UI COMPONENTS
═══════════════════════════════════════════════════════════════════ */
.subject-name-cell { display: flex; align-items: center; gap: .75rem; }
.subject-icon {
    width: 38px; height: 38px; border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    background: #f1f5f9; color: #64748b; font-size: 1.1rem;
}
.subject-info { display: flex; flex-direction: column; }
.subject-title { font-weight: 700; color: var(--text-dark); margin-bottom: 0.1rem; }
.subject-code { font-size: 0.75rem; color: var(--text-muted); font-family: monospace; }

.type-badge {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.2rem 0.6rem; border-radius: 999px;
    font-size: 0.73rem; font-weight: 600; white-space: nowrap;
}
.type-Core { background: #e0e7ff; color: #3730a3; }
.type-Elective { background: #dcfce7; color: #15803d; }
.type-Practical { background: #ffedd5; color: #c2410c; }
.type-default { background: #f1f5f9; color: #475569; }

.metric-pill {
    display: inline-flex; flex-direction: column; align-items: center; justify-content: center;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: var(--radius-sm);
    min-width: 55px; padding: 0.2rem 0.5rem;
}
.metric-val { font-weight: 700; font-size: 0.8rem; color: var(--text-dark); line-height: 1.1; }
.metric-lbl { font-size: 0.6rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }

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
            <div class="page-title-icon"><i class="bi bi-book"></i></div>
            Manage Subjects
        </h1>
        <p class="page-subtitle">View and configure academic subjects and curriculum</p>
    </div>
    <div>
        <a href="{{ route('subjects.create') }}" class="btn btn-primary-action shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Add New Subject
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
<div class="filter-bar mb-4">
    <form action="{{ route('subjects.index') }}" method="GET" class="filter-inputs m-0 w-100">
        
        {{-- Search --}}
        <div class="search-wrapper flex-grow-1" style="max-width: 380px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by subject name or code...">
        </div>

        <select name="type" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
            <option value="">ប្រភេទទាំងអស់</option>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                    {{ \App\Models\Academics\Subject::typeLabel($type) }}
                </option>
            @endforeach
            @if(!in_array('Core', $types->toArray(), true) && !in_array('core', array_map('strtolower', $types->toArray()), true))
                <option value="Core" {{ request('type') == 'Core' ? 'selected' : '' }}>ស្នូល (Core)</option>
            @endif
            @if(!in_array('Elective', $types->toArray(), true) && !in_array('elective', array_map('strtolower', $types->toArray()), true))
                <option value="Elective" {{ request('type') == 'Elective' ? 'selected' : '' }}>ជ្រើសរើស (Elective)</option>
            @endif
        </select>

        {{-- Active Filters Chip (if any) --}}
        @if(request()->anyFilled(['search', 'type']))
            <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-ghost ms-2 text-danger" style="font-size:0.75rem">
                <i class="bi bi-x-circle me-1"></i>Clear Filters
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
                    <th style="width:40px">ID</th>
                    <th>Subject Details</th>
                    <th>Subject Type</th>
                    <th class="text-center">Assigned Classes</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                    <tr>
                        <td class="text-muted" style="font-size:0.75rem">#{{ $subject->id }}</td>
                        <td>
                            <div class="subject-name-cell">
                                <div class="subject-icon">
                                    <i class="bi bi-journal-bookmark"></i>
                                </div>
                                <div class="subject-info">
                                    <span class="subject-title">{{ $subject->name }}</span>
                                    <span class="subject-code"><i class="bi bi-upc-scan me-1"></i>{{ $subject->code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $typeClass = match(strtolower($subject->type ?? '')) {
                                    'core', 'mandatory'   => 'type-Core',
                                    'elective', 'optional' => 'type-Elective',
                                    'practical', 'theory' => 'type-Practical',
                                    default               => 'type-default',
                                };
                            @endphp
                            <span class="type-badge {{ $typeClass }}">
                                {{ $subject->type_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="metric-pill">
                                <span class="metric-val">{{ $subject->class_subjects_count ?? 0 }}</span>
                                <span class="metric-lbl">Classes</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('subjects.show', $subject) }}" class="action-btn" data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('subjects.edit', $subject) }}" class="action-btn" data-bs-toggle="tooltip" title="Edit Subject">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="action-btn action-btn-danger" 
                                        data-bs-toggle="tooltip" title="Delete Subject"
                                        onclick="confirmDelete({{ $subject->id }}, '{{ addslashes($subject->name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-inbox fs-1"></i></div>
                            <h6 class="fw-bold text-dark">No Subjects Found</h6>
                            <p class="text-muted small mb-0">Try adjusting your filters or create a new subject.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($subjects->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $subjects->links('pagination::bootstrap-5') }}
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
                <h6 class="fw-bold mb-1">Delete Subject?</h6>
                <p class="text-muted small mb-4">
                    Are you sure you want to delete <strong id="deleteSubjectName"></strong>? This action cannot be undone.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">Delete Subject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    function confirmDelete(id, name) {
        document.getElementById('deleteSubjectName').textContent = name;
        document.getElementById('deleteForm').action = `/subjects/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endsection
