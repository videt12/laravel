@extends('layouts.app')

@section('title', 'Monthly Attendance Register — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student Services</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Monthly Register</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ── Filter bar ── */
.register-filter { background:#fff; border:1px solid var(--border-soft); border-radius:var(--radius-lg); padding:1rem 1.25rem; margin-bottom:1.25rem; box-shadow:var(--shadow-xs); }

/* ── Stats row ── */
.reg-stat-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:.75rem; margin-bottom:1.25rem; }
@media(max-width:640px) { .reg-stat-grid { grid-template-columns:repeat(2,1fr); } }
.reg-stat-tile { background:#fff; border:1px solid var(--border-soft); border-radius:var(--radius-md); padding:.85rem 1rem; text-align:center; box-shadow:var(--shadow-xs); }
.reg-stat-count { font-size:1.55rem; font-weight:700; line-height:1; margin-bottom:.15rem; }
.reg-stat-label { font-size:.7rem; color:var(--text-muted); font-weight:500; text-transform:uppercase; letter-spacing:.4px; }
.tile-total   .reg-stat-count { color:#0f172a; } .tile-total   { border-top:3px solid #334155; }
.tile-present .reg-stat-count { color:#15803d; } .tile-present { border-top:3px solid #22c55e; }
.tile-absent  .reg-stat-count { color:#dc2626; } .tile-absent  { border-top:3px solid #ef4444; }
.tile-late    .reg-stat-count { color:#d97706; } .tile-late    { border-top:3px solid #f59e0b; }
.tile-other   .reg-stat-count { color:#7c3aed; } .tile-other   { border-top:3px solid #8b5cf6; }

/* ── Register Table ── */
.register-wrap { overflow-x:auto; }
.register-table { width:100%; border-collapse:collapse; font-size:.78rem; min-width:900px; }
.register-table th, .register-table td { border:1px solid #e2e8f0; padding:.3rem .4rem; text-align:center; white-space:nowrap; }
.register-table thead th { background:#1e293b; color:#fff; font-weight:600; font-size:.72rem; position:sticky; top:0; z-index:2; }
.register-table thead th.col-student { text-align:left; min-width:160px; max-width:200px; position:sticky; left:0; z-index:3; background:#1e293b; }
.register-table tbody td.col-student { text-align:left; font-weight:600; color:var(--text-dark); position:sticky; left:0; background:#fff; z-index:1; border-right:2px solid #cbd5e1; padding-left:.6rem; }
.register-table tbody tr:hover td.col-student { background:#f8fafc; }
.register-table tbody tr:hover td { background:#f8fafc; }
.register-table tfoot td { font-weight:700; font-size:.74rem; background:#f8fafc; }
.register-table tfoot td.col-student { background:#f1f5f9; text-align:left; font-size:.72rem; font-weight:600; color:var(--text-muted); }

/* ── Cell badges ── */
.cell-P { background:#dcfce7; color:#15803d; border-radius:3px; padding:1px 4px; font-weight:700; display:inline-block; }
.cell-A { background:#fee2e2; color:#dc2626; border-radius:3px; padding:1px 4px; font-weight:700; display:inline-block; }
.cell-L { background:#fef3c7; color:#d97706; border-radius:3px; padding:1px 4px; font-weight:700; display:inline-block; }
.cell-X { background:#ede9fe; color:#7c3aed; border-radius:3px; padding:1px 4px; font-weight:700; display:inline-block; }
.cell-blank { color:#cbd5e1; font-size:.7rem; }

/* ── Summary columns ── */
.col-total  { background:#f8fafc!important; font-weight:700; color:#0f172a; }
.col-P      { background:#f0fdf4!important; color:#15803d; font-weight:700; }
.col-A      { background:#fff5f5!important; color:#dc2626; font-weight:700; }
.col-L      { background:#fffbeb!important; color:#d97706; font-weight:700; }
.col-perm   { background:#f5f3ff!important; color:#7c3aed; font-weight:700; }
.col-pct    { background:#eff6ff!important; color:#1d4ed8; font-weight:700; }

/* ── Header date column ── */
.date-hdr { font-size:.68rem; }
.date-hdr .day-num { font-size:.8rem; font-weight:700; display:block; }
.date-hdr .day-name { font-size:.6rem; color:rgba(255,255,255,.6); display:block; }
.date-hdr.weekend { background:#374151!important; }

/* ── Print ── */
@media print {
    .register-filter, .no-print { display:none!important; }
    .register-table { font-size:.65rem; }
    .register-table th, .register-table td { padding:.2rem .3rem; }
}
</style>
@endpush

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Monthly Attendance Register</h1>
        <p class="page-subtitle">
            {{ $parsedMonth->format('F Y') }}
            @php
                $selectedClass = $classes->firstWhere('id', $selectedClassId);
                $classLabel = $selectedClass?->grade?->name ?? '—';
                if ($selectedClass?->section?->name) $classLabel .= ' — '.$selectedClass->section->name;
            @endphp
            — {{ $classLabel }}
        </p>
    </div>
    <div class="d-flex gap-2 no-print">
        <button onclick="window.print()" class="btn btn-outline-secondary" id="btn-print-register">
            <i class="bi bi-printer me-1"></i>Print
        </button>
        <a href="{{ route('attendance-sessions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-calendar2-check me-1"></i>Sessions
        </a>
    </div>
</div>

{{-- FILTERS --}}
<div class="register-filter no-print">
    <form method="GET" action="{{ route('attendance.monthly-register') }}" id="registerFilterForm">
        <div class="row g-2 align-items-end">
            <div class="col-xl-3 col-md-4">
                <label class="form-label small fw-medium mb-1">Academic Year</label>
                <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()" id="reg-filter-year">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}{{ $year->is_active ? ' (Active)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3 col-md-4">
                <label class="form-label small fw-medium mb-1">Class</label>
                <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()" id="reg-filter-class">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->grade?->name }}{{ $class->section?->name ? ' — '.$class->section->name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-md-3">
                <label class="form-label small fw-medium mb-1">Month</label>
                <input type="month" name="month" id="reg-filter-month"
                       value="{{ $selectedMonth }}"
                       class="form-control form-control-sm"
                       onchange="this.form.submit()">
            </div>
        </div>
    </form>
</div>

{{-- SUMMARY TILES --}}
<div class="reg-stat-grid">
    <div class="reg-stat-tile tile-total">
        <div class="reg-stat-count">{{ $classStats['total_students'] }}</div>
        <div class="reg-stat-label">Students</div>
    </div>
    <div class="reg-stat-tile tile-present">
        <div class="reg-stat-count">{{ $classStats['P'] }}</div>
        <div class="reg-stat-label">Present (all days)</div>
    </div>
    <div class="reg-stat-tile tile-absent">
        <div class="reg-stat-count">{{ $classStats['A'] }}</div>
        <div class="reg-stat-label">Absent</div>
    </div>
    <div class="reg-stat-tile tile-late">
        <div class="reg-stat-count">{{ $classStats['L'] }}</div>
        <div class="reg-stat-label">Late</div>
    </div>
    <div class="reg-stat-tile tile-other">
        <div class="reg-stat-count">{{ $classStats['Perm'] }}</div>
        <div class="reg-stat-label">Permission / Leave</div>
    </div>
</div>

{{-- META INFO --}}
<div class="d-flex align-items-center gap-3 mb-3 no-print" style="font-size:.8rem;color:var(--text-muted)">
    <span><i class="bi bi-calendar3 me-1"></i>{{ $attendanceDays }} attendance days taken ({{ $sessionCount }} sessions)</span>
    <span>|</span>
    <span><i class="bi bi-people me-1"></i>{{ $classStats['total_students'] }} students enrolled</span>
</div>

{{-- REGISTER TABLE --}}
<div class="data-card">
    @if($students->isEmpty())
        <div class="empty-state" id="empty-register">
            <div class="empty-icon"><i class="bi bi-people"></i></div>
            <p class="empty-title">No Students Found</p>
            <p class="empty-subtitle">No students are enrolled in the selected class.</p>
        </div>
    @else
    <div class="register-wrap" id="register-table-wrapper">
        <table class="register-table" id="monthly-register-table">
            <thead>
                <tr>
                    <th class="col-student" rowspan="1" style="width:50px">#</th>
                    <th class="col-student" style="min-width:150px">Student Name</th>
                    {{-- Day columns --}}
                    @foreach($monthDates as $date)
                    <th class="date-hdr {{ in_array($date->dayOfWeek, [0,6]) ? 'weekend' : '' }}">
                        <span class="day-num">{{ $date->format('d') }}</span>
                        <span class="day-name">{{ $date->format('D') }}</span>
                    </th>
                    @endforeach
                    {{-- Totals --}}
                    <th class="col-total" title="Total marked">T</th>
                    <th class="col-P" title="Present">P</th>
                    <th class="col-A" title="Absent">A</th>
                    <th class="col-L" title="Late">L</th>
                    <th class="col-perm" title="Permission / Leave">Pm</th>
                    <th class="col-pct" title="Attendance %">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $idx => $student)
                @php
                    $sid   = $student->id;
                    $stats = $studentStats[$sid] ?? ['P'=>0,'A'=>0,'L'=>0,'Perm'=>0,'total'=>0];
                    $pct   = $stats['total'] > 0 ? round(($stats['P'] / $stats['total']) * 100) : null;
                @endphp
                <tr id="student-reg-row-{{ $sid }}">
                    <td class="col-student text-muted" style="font-size:.7rem">{{ $idx + 1 }}</td>
                    <td class="col-student" style="max-width:180px;overflow:hidden;text-overflow:ellipsis">
                        {{ $student->user?->name ?? ('Student #' . $sid) }}
                    </td>
                    @foreach($monthDates as $date)
                    @php
                        $code = $matrix[$sid][$date->format('Y-m-d')] ?? null;
                        $isWeekend = in_array($date->dayOfWeek, [0, 6]);
                    @endphp
                    <td style="{{ $isWeekend ? 'background:#f8fafc;' : '' }}">
                        @if($code === 'P')
                            <span class="cell-P">P</span>
                        @elseif($code === 'A')
                            <span class="cell-A">A</span>
                        @elseif($code === 'L')
                            <span class="cell-L">L</span>
                        @elseif($code !== null)
                            <span class="cell-X">{{ $code }}</span>
                        @else
                            <span class="cell-blank">·</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="col-total">{{ $stats['total'] }}</td>
                    <td class="col-P">{{ $stats['P'] }}</td>
                    <td class="col-A">{{ $stats['A'] }}</td>
                    <td class="col-L">{{ $stats['L'] }}</td>
                    <td class="col-perm">{{ $stats['Perm'] }}</td>
                    <td class="col-pct">
                        @if($pct !== null)
                            {{ $pct }}%
                        @else
                            <span class="cell-blank">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="col-student" colspan="2">Class Total</td>
                    @foreach($monthDates as $date)
                    @php
                        $dateStr    = $date->format('Y-m-d');
                        $dayTotal   = 0;
                        foreach ($matrix as $sid => $days) {
                            if (isset($days[$dateStr]) && $days[$dateStr] !== null) $dayTotal++;
                        }
                    @endphp
                    <td style="{{ in_array($date->dayOfWeek,[0,6]) ? 'background:#f8fafc;' : '' }}">
                        {{ $dayTotal > 0 ? $dayTotal : '' }}
                    </td>
                    @endforeach
                    <td class="col-total">{{ $classStats['total'] }}</td>
                    <td class="col-P">{{ $classStats['P'] }}</td>
                    <td class="col-A">{{ $classStats['A'] }}</td>
                    <td class="col-L">{{ $classStats['L'] }}</td>
                    <td class="col-perm">{{ $classStats['Perm'] }}</td>
                    <td class="col-pct">
                        @if($classStats['total'] > 0)
                            {{ round(($classStats['P'] / $classStats['total']) * 100) }}%
                        @else
                            —
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- LEGEND --}}
    <div class="data-card-footer d-flex align-items-center gap-3 flex-wrap no-print" style="font-size:.78rem">
        <span class="text-muted">Legend:</span>
        <span><span class="cell-P">P</span> Present</span>
        <span><span class="cell-A">A</span> Absent</span>
        <span><span class="cell-L">L</span> Late</span>
        <span><span class="cell-X">Pm</span> Permission / Leave</span>
        <span><span class="cell-blank">·</span> No session recorded</span>
        <span class="ms-auto text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Shaded columns are weekends. Only sessions that have been created and attendance saved will show marks.
        </span>
    </div>
    @endif
</div>

@endsection
