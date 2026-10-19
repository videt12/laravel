@extends('layouts.app')

@section('title', 'Session Details — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student Services</li>
        <li class="breadcrumb-item"><a href="{{ route('attendance-sessions.index') }}" class="text-decoration-none text-muted">Attendance Sessions</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Session Details</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ── Session Header Card ── */
.session-header-card {
    background: #fff; border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg); padding: 1.5rem 1.75rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow-sm);
}
.session-class-title { font-size: 1.35rem; font-weight: 700; color: var(--text-dark); margin: 0 0 .15rem; }
.session-period-label {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #eff6ff; color: #1d4ed8;
    border-radius: var(--radius-sm); padding: .2rem .7rem;
    font-size: .8rem; font-weight: 600; margin-bottom: .5rem;
}
.session-date-label { font-size: .9rem; color: var(--text-muted); }
.status-badge-open   { background: #dcfce7; color: #15803d; border-radius: 999px; padding: .2rem .75rem; font-size: .75rem; font-weight: 700; display: inline-flex; align-items: center; gap: .3rem; }
.status-badge-closed { background: #f1f5f9; color: #475569; border-radius: 999px; padding: .2rem .75rem; font-size: .75rem; font-weight: 700; display: inline-flex; align-items: center; gap: .3rem; }

/* ── Info Grid ── */
.info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 1rem; }
.info-item-card {
    background: var(--surface); border: 1px solid var(--border-soft);
    border-radius: var(--radius-md); padding: .85rem 1rem;
}
.info-item-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-light); margin-bottom: .25rem; }
.info-item-value { font-size: .9rem; font-weight: 600; color: var(--text-dark); }

/* ── Summary Tiles ── */
.summary-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: .85rem; margin-bottom: 1.5rem; }
@media(max-width:768px) { .summary-grid { grid-template-columns: repeat(2, 1fr); } }
.summary-tile {
    background: #fff; border: 1px solid var(--border-soft);
    border-radius: var(--radius-md); padding: 1rem;
    text-align: center; box-shadow: var(--shadow-xs);
}
.summary-tile-count { font-size: 1.75rem; font-weight: 700; line-height: 1; margin-bottom: .2rem; }
.summary-tile-label { font-size: .73rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: .4px; }
.tile-total   .summary-tile-count { color: #0f172a; }
.tile-present .summary-tile-count { color: #15803d; }
.tile-absent  .summary-tile-count { color: #dc2626; }
.tile-late    .summary-tile-count { color: #d97706; }
.tile-permission .summary-tile-count { color: #7c3aed; }
.tile-total   { border-top: 3px solid #334155; }
.tile-present { border-top: 3px solid #22c55e; }
.tile-absent  { border-top: 3px solid #ef4444; }
.tile-late    { border-top: 3px solid #f59e0b; }
.tile-permission { border-top: 3px solid #8b5cf6; }

/* ── Attendance status badge ── */
.att-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: var(--radius-sm);
    font-size: .72rem; font-weight: 700;
}
.att-present { background: #dcfce7; color: #15803d; }
.att-absent  { background: #fee2e2; color: #dc2626; }
.att-late    { background: #fef3c7; color: #d97706; }
.att-other   { background: #ede9fe; color: #7c3aed; }
</style>
@endpush

@section('content')

@php
    $classLabel  = $session->schoolClass?->grade?->name ?? '—';
    $sectionName = $session->schoolClass?->section?->name ?? $session->section?->name ?? '';
    if ($sectionName) $classLabel .= ' — ' . $sectionName;
@endphp

{{-- FLASH --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- PAGE HEADER --}}
<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Session Details</h1>
        <p class="page-subtitle">{{ $classLabel }} — {{ $session->display_period }}</p>
    </div>
    <div class="d-flex gap-2">
        @if($session->status === 'open')
        <form action="{{ route('attendance-sessions.close', $session->id) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-warning" id="btn-close-session">
                <i class="bi bi-lock me-1"></i>Close Session
            </button>
        </form>
        @else
        <form action="{{ route('attendance-sessions.reopen', $session->id) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-outline-success" id="btn-reopen-session">
                <i class="bi bi-unlock me-1"></i>Reopen
            </button>
        </form>
        @endif
        <a href="{{ route('attendance-sessions.take-attendance', $session->id) }}" class="btn btn-primary-action" id="btn-take-attendance">
            <i class="bi bi-pencil-square me-1"></i>Take Attendance
        </a>
        <a href="{{ route('attendance-sessions.edit', $session->id) }}" class="btn btn-outline-secondary" id="btn-edit-session">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('attendance-sessions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

{{-- SESSION HEADER CARD --}}
<div class="session-header-card d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="session-period-label">
            <i class="bi bi-clock"></i> {{ $session->display_period }}
        </div>
        <div class="session-class-title">{{ $classLabel }}</div>
        <div class="session-date-label">
            <i class="bi bi-calendar3 me-1"></i>
            {{ $session->attendance_date?->format('d F Y') ?? '—' }}
            <span class="text-muted ms-1">({{ $session->attendance_date?->format('l') ?? '' }})</span>
        </div>
    </div>
    <div>
        @if($session->status === 'closed')
            <span class="status-badge-closed"><i class="bi bi-check-circle-fill" style="font-size:.6rem"></i> Closed</span>
        @else
            <span class="status-badge-open"><i class="bi bi-circle-fill" style="font-size:.5rem"></i> Open</span>
        @endif
    </div>
</div>

{{-- SESSION INFO GRID --}}
<div class="info-grid mb-4">
    <div class="info-item-card">
        <div class="info-item-label">Academic Year</div>
        <div class="info-item-value">{{ $session->academic_year?->name ?? '—' }}</div>
    </div>
    <div class="info-item-card">
        <div class="info-item-label">Class</div>
        <div class="info-item-value">{{ $session->schoolClass?->grade?->name ?? '—' }}</div>
    </div>
    <div class="info-item-card">
        <div class="info-item-label">Section</div>
        <div class="info-item-value">{{ $sectionName ?: '—' }}</div>
    </div>
    <div class="info-item-card">
        <div class="info-item-label">Attendance Date</div>
        <div class="info-item-value">{{ $session->attendance_date?->format('d/m/Y') ?? '—' }}</div>
    </div>
    <div class="info-item-card">
        <div class="info-item-label">Period</div>
        <div class="info-item-value">{{ $session->display_period }}</div>
    </div>
    <div class="info-item-card">
        <div class="info-item-label">Subject</div>
        <div class="info-item-value">{{ $session->teacherAssignment?->class_subject?->subject?->name ?? '—' }}</div>
    </div>
    <div class="info-item-card">
        <div class="info-item-label">Teacher</div>
        <div class="info-item-value">{{ $session->teacherAssignment?->employee?->user?->name ?? '—' }}</div>
    </div>
    <div class="info-item-card">
        <div class="info-item-label">Recorded By</div>
        <div class="info-item-value">{{ $session->recordedBy?->name ?? '—' }}</div>
    </div>
</div>

{{-- ATTENDANCE SUMMARY --}}
<div class="summary-grid">
    <div class="summary-tile tile-total">
        <div class="summary-tile-count">{{ $summary['total'] }}</div>
        <div class="summary-tile-label">Total</div>
    </div>
    <div class="summary-tile tile-present">
        <div class="summary-tile-count">{{ $summary['present'] }}</div>
        <div class="summary-tile-label">Present</div>
    </div>
    <div class="summary-tile tile-absent">
        <div class="summary-tile-count">{{ $summary['absent'] }}</div>
        <div class="summary-tile-label">Absent</div>
    </div>
    <div class="summary-tile tile-late">
        <div class="summary-tile-count">{{ $summary['late'] }}</div>
        <div class="summary-tile-label">Late</div>
    </div>
    <div class="summary-tile tile-permission">
        <div class="summary-tile-count">{{ $summary['permission'] + ($summary['other'] ?? 0) }}</div>
        <div class="summary-tile-label">Other</div>
    </div>
</div>

{{-- STUDENT ATTENDANCE LIST --}}
<div class="data-card">
    <div class="data-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">Student Attendance</span>
            <span class="count-badge">{{ $summary['total'] }}</span>
        </div>
        <small class="text-muted">{{ $classStudentCount }} total students in class</small>
    </div>

    @if($session->student_attendances->isEmpty())
        <div class="empty-state" id="empty-attendance-list">
            <div class="empty-icon"><i class="bi bi-people"></i></div>
            <p class="empty-title">No Attendance Records</p>
            <p class="empty-subtitle">Attendance has not been taken for this session yet.</p>
            <a href="{{ route('attendance-sessions.take-attendance', $session->id) }}" class="btn btn-primary-action btn-sm">
                <i class="bi bi-pencil-square me-1"></i>Take Attendance Now
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40px">#</th>
                        <th>Student Name</th>
                        <th>Status</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($session->student_attendances as $i => $att)
                    @php
                        $code = strtoupper($att->attendance_status?->code ?? '');
                        $attName = $att->attendance_status?->name ?? '—';
                        $badgeClass = match($code) {
                            'P'       => 'att-present',
                            'A'       => 'att-absent',
                            'L'       => 'att-late',
                            default   => 'att-other',
                        };
                    @endphp
                    <tr>
                        <td class="text-center text-muted" style="font-size:.75rem">{{ $i + 1 }}</td>
                        <td>
                            <span class="fw-medium text-dark" style="font-size:.875rem">
                                {{ $att->student?->user?->name ?? ('Student #' . $att->student_id) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="att-badge {{ $badgeClass }}">{{ $code ?: '?' }}</span>
                                <span class="text-muted" style="font-size:.82rem">{{ $attName }}</span>
                            </div>
                        </td>
                        <td class="text-muted" style="font-size:.82rem">{{ $att->remarks ?: '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection