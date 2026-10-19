@extends('layouts.app')

@section('title', 'Manage Class Subjects — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Academic</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Class Subjects</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.page-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    background: #eef2ff;
    color: #4f46e5;
    border-radius: var(--radius-md);
    font-size: 1.15rem;
}
.page-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0; }

.filter-bar {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.table-card {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc;
    color: var(--text-muted);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-soft);
    white-space: nowrap;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fafbfc; }
.table-custom tbody td {
    padding: 1rem 1.25rem;
    font-size: 0.85rem;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft);
    vertical-align: middle;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

.class-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
    border-radius: var(--radius-sm);
    padding: 0.2rem 0.6rem;
    font-weight: 700;
    font-size: 0.8rem;
}
.subject-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: var(--radius-sm);
    padding: 0.2rem 0.6rem;
    font-weight: 600;
    font-size: 0.8rem;
}
.year-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: #f1f5f9;
    color: #475569;
    border-radius: 999px;
    padding: 0.15rem 0.6rem;
    font-size: 0.73rem;
    font-weight: 600;
}
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    color: var(--text-muted);
    transition: all 0.2s;
    background: transparent; border: none;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-journal-bookmark"></i></div>
            Class Subjects
        </h1>
        <p class="page-subtitle">Configure subject curriculum allocations for each class</p>
    </div>
    <div>
        <a href="{{ route('class-subjects.create') }}" class="btn btn-primary-action shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Assign Subject to Class
        </a>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="filter-bar mb-4">
    <form action="{{ route('class-subjects.index') }}" method="GET" class="filter-inputs w-100 justify-content-between">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- Search input --}}
            <div class="input-group input-group-sm" style="width: 220px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search subject or class..." value="{{ request('search') }}">
            </div>

            {{-- Filter by Class --}}
            <select name="class_id" class="form-select form-select-sm" style="width: 170px;">
                <option value="">All Classes</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->grade->name ?? 'Grade' }} — {{ $c->section->name ?? 'Section' }}
                    </option>
                @endforeach
            </select>

            {{-- Filter by Subject --}}
            <select name="subject_id" class="form-select form-select-sm" style="width: 170px;">
                <option value="">All Subjects</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }} ({{ $s->code }})
                    </option>
                @endforeach
            </select>

            {{-- Filter by Academic Year --}}
            <select name="academic_year_id" class="form-select form-select-sm" style="width: 170px;">
                <option value="">All Academic Years</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                        {{ $ay->name }} {{ $ay->is_active ? '(Active)' : '' }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request()->anyFilled(['search', 'class_id', 'subject_id', 'academic_year_id']))
                <a href="{{ route('class-subjects.index') }}" class="btn btn-sm btn-link text-muted">Clear</a>
            @endif
        </div>
    </form>
</div>

{{-- DATA TABLE --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Subject Code</th>
                    <th>Academic Year</th>
                    <th>Assigned Teachers</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classSubjects as $index => $item)
                    <tr>
                        <td class="text-muted" style="width: 50px;">{{ $classSubjects->firstItem() + $index }}</td>
                        <td>
                            <span class="class-badge">
                                <i class="bi bi-diagram-3"></i>
                                {{ $item->class->grade->name ?? '—' }} — {{ $item->class->section->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="subject-badge">
                                <i class="bi bi-book"></i>
                                {{ $item->subject->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <code class="text-muted fw-bold">{{ $item->subject->code ?? '—' }}</code>
                        </td>
                        <td>
                            <span class="year-badge">
                                <i class="bi bi-calendar-event"></i>
                                {{ $item->class->academic_year->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->teacher_assignments_count > 0 ? 'bg-info text-dark' : 'bg-secondary' }}">
                                {{ $item->teacher_assignments_count }} {{ Str::plural('teacher', $item->teacher_assignments_count) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('class-subjects.show', $item) }}" class="action-btn" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('class-subjects.edit', $item) }}" class="action-btn" title="Edit Allocation">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('class-subjects.destroy', $item) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to remove this subject from the class?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger" title="Delete Allocation">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-journal-x fs-1"></i></div>
                            <h6 class="fw-bold mb-1">No Class Subjects Found</h6>
                            <p class="text-muted small mb-3">No subjects have been mapped to classes matching your criteria.</p>
                            <a href="{{ route('class-subjects.create') }}" class="btn btn-sm btn-primary-action">
                                <i class="bi bi-plus-lg me-1"></i>Assign Subject to Class
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($classSubjects->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $classSubjects->firstItem() }} to {{ $classSubjects->lastItem() }} of {{ $classSubjects->total() }} records
            </div>
            <div>
                {{ $classSubjects->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

@endsection
