@extends('layouts.app')

@section('title', 'Attendance Sessions — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student Services</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Attendance Sessions</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.session-status-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    border-radius: 999px; padding: .18rem .65rem;
    font-size: .72rem; font-weight: 600; white-space: nowrap;
}
.session-status-open   { background: #dcfce7; color: #15803d; }
.session-status-closed { background: #f1f5f9; color: #475569; }
.period-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    background: #eff6ff; color: #1d4ed8;
    border-radius: var(--radius-sm); padding: .18rem .6rem;
    font-size: .75rem; font-weight: 600;
}
.filter-select {
    font-size: .8rem; padding: .3rem .65rem;
    border-radius: var(--radius-sm); border: 1px solid var(--border);
    color: var(--text-dark); background-color: #fff; height: 32px;
}
.filter-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); outline: none; }
.stat-chip {
    display: inline-flex; align-items: center; gap: .25rem;
    font-size: .78rem; font-weight: 500; padding: .2rem .55rem;
    border-radius: var(--radius-sm); background: #f8fafc; border: 1px solid #e2e8f0;
    color: var(--text-muted);
}
.stat-chip-success { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
.subject-chip {
    display: inline-flex; align-items: center; gap: .2rem;
    background: #faf5ff; color: #7c3aed; border: 1px solid #e9d5ff;
    border-radius: var(--radius-sm); padding: .15rem .5rem;
    font-size: .72rem; font-weight: 600;
}
</style>
@endpush

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Attendance Sessions</h1>
        <p class="page-subtitle">Manage class attendance sessions and daily attendance records.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('attendance.monthly-register') }}" class="btn btn-outline-secondary" id="btn-monthly-register">
            <i class="bi bi-table me-1"></i>Monthly Register
        </a>
        <a href="{{ route('attendance-sessions.create') }}" class="btn btn-primary-action" id="btn-create-session">
            <i class="bi bi-plus-lg me-1"></i>Create Session
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="toolbar-card mb-4">
    <form method="GET" action="{{ route('attendance-sessions.index') }}" id="filterForm">
        <div class="row g-2 align-items-center">
            <div class="col-xl-2 col-lg-3 col-md-4">
                <select name="academic_year_id" class="filter-select w-100" onchange="this.form.submit()" id="filter-year">
                    <option value="">All Years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4">
                <select name="class_id" class="filter-select w-100" onchange="this.form.submit()" id="filter-class">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->grade?->name }}{{ $class->section?->name ? ' — '.$class->section->name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-2">
                <select name="status" class="filter-select w-100" onchange="this.form.submit()" id="filter-status">
                    <option value="">All Status</option>
                    <option value="open"   {{ request('status') === 'open'   ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-3">
                <input type="date" name="date" id="filter-date"
                       value="{{ request('date') }}"
                       class="filter-select w-100"
                       onchange="this.form.submit()">
            </div>
            <div class="col-auto">
                @if(request()->hasAny(['academic_year_id','class_id','status','date','month']))
                    <a href="{{ route('attendance-sessions.index') }}" class="btn btn-sm btn-link text-muted text-decoration-none px-2">
                        <i class="bi bi-x-circle me-1"></i>Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">Sessions</span>
            <span class="count-badge">{{ $sessions->total() }}</span>
        </div>
        <small class="text-muted">
            @if($sessions->total() > 0)
                Showing {{ $sessions->firstItem() }}–{{ $sessions->lastItem() }} of {{ $sessions->total() }}
            @endif
        </small>
    </div>

    @if($sessions->isEmpty())
        <div class="empty-state" id="empty-state-sessions">
            <div class="empty-icon"><i class="bi bi-calendar2-check"></i></div>
            <p class="empty-title">No Attendance Sessions Found</p>
            <p class="empty-subtitle">
                @if(request()->hasAny(['academic_year_id','class_id','status','date']))
                    No sessions match your filters. <a href="{{ route('attendance-sessions.index') }}" class="text-decoration-none">Clear filters</a>
                @else
                    Create your first attendance session to get started.
                @endif
            </p>
            @if(!request()->hasAny(['academic_year_id','class_id','status','date']))
                <a href="{{ route('attendance-sessions.create') }}" class="btn btn-primary-action btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Create Session
                </a>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table" id="sessions-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40px">#</th>
                        <th>Date</th>
                        <th>Class</th>
                        <th>Subject / Teacher</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Students</th>
                        <th style="width:52px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $i => $session)
                    @php
                        $classLabel  = ($session->schoolClass?->grade?->name ?? '—');
                        $sectionName = $session->schoolClass?->section?->name ?? $session->section?->name ?? '';
                        if ($sectionName) $classLabel .= ' — ' . $sectionName;
                        $totalStudents = $classStudentCounts[$session->class_id] ?? 0;
                        $subjectName   = $session->teacherAssignment?->class_subject?->subject?->name;
                        $teacherName   = $session->teacherAssignment?->employee?->user?->name;
                    @endphp
                    <tr id="session-row-{{ $session->id }}">
                        <td class="text-center text-muted" style="font-size:.75rem">{{ $sessions->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold text-dark" style="font-size:.875rem">
                                {{ $session->attendance_date?->format('d M Y') ?? '—' }}
                            </div>
                            <div class="text-muted" style="font-size:.73rem">{{ $session->attendance_date?->format('l') ?? '' }}</div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark" style="font-size:.875rem">{{ $classLabel }}</div>
                            @if($session->academic_year)
                                <div class="text-muted" style="font-size:.73rem">{{ $session->academic_year->name }}</div>
                            @endif
                        </td>
                        <td>
                            @if($subjectName)
                                <span class="subject-chip">
                                    <i class="bi bi-book" style="font-size:.65rem"></i>
                                    {{ $subjectName }}
                                </span>
                                @if($teacherName)
                                    <div class="text-muted mt-1" style="font-size:.75rem">
                                        <i class="bi bi-person me-1"></i>{{ $teacherName }}
                                    </div>
                                @endif
                            @else
                                <span class="text-muted" style="font-size:.8rem">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="period-badge">
                                <i class="bi bi-clock" style="font-size:.65rem"></i>
                                {{ $session->display_period }}
                            </span>
                        </td>
                        <td>
                            @if($session->status === 'closed')
                                <span class="session-status-badge session-status-closed">
                                    <i class="bi bi-check-circle-fill" style="font-size:.55rem"></i> Closed
                                </span>
                            @else
                                <span class="session-status-badge session-status-open">
                                    <i class="bi bi-circle-fill" style="font-size:.45rem"></i> Open
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($totalStudents > 0)
                                <span class="stat-chip {{ $session->total_marked >= $totalStudents ? 'stat-chip-success' : '' }}">
                                    {{ $session->total_marked }} / {{ $totalStudents }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size:.8rem">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn-action-dots" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false"
                                        id="session-action-{{ $session->id }}">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:168px;font-size:.875rem">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('attendance-sessions.show', $session->id) }}">
                                            <i class="bi bi-eye me-2 text-muted"></i>View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('attendance-sessions.take-attendance', $session->id) }}">
                                            <i class="bi bi-pencil-square me-2 text-muted"></i>Take Attendance
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('attendance-sessions.edit', $session->id) }}">
                                            <i class="bi bi-pencil me-2 text-muted"></i>Edit Session
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    @if($session->status === 'open')
                                    <li>
                                        <form action="{{ route('attendance-sessions.close', $session->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="dropdown-item py-2 text-warning">
                                                <i class="bi bi-lock me-2"></i>Close Session
                                            </button>
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <form action="{{ route('attendance-sessions.reopen', $session->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="dropdown-item py-2 text-success">
                                                <i class="bi bi-unlock me-2"></i>Reopen Session
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    <li>
                                        <button type="button" class="dropdown-item py-2 text-danger"
                                                data-bs-toggle="modal" data-bs-target="#delModal{{ $session->id }}">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="modal fade" id="delModal{{ $session->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body p-4 text-center">
                                            <div class="mb-3">
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:48px;height:48px">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-1">Delete Session?</h6>
                                            <p class="text-muted small mb-4">
                                                <strong>{{ $session->attendance_date?->format('d M Y') }}</strong><br>
                                                {{ $classLabel }} — {{ $session->display_period }}
                                            </p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('attendance-sessions.destroy', $session->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger px-3">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
        <div class="data-card-footer d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.8rem">Page {{ $sessions->currentPage() }} of {{ $sessions->lastPage() }}</div>
            {{ $sessions->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif
</div>

@endsection