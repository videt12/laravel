@extends('layouts.app')

@section('title', ($readOnly ? 'បញ្ជីវត្តមានប្រចាំខែ' : 'បញ្ជីវត្តមានសិស្ស') . ' — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">សេវាកម្មសិស្ស</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">
            {{ $readOnly ? 'បញ្ជីវត្តមានប្រចាំខែ' : 'បញ្ជីវត្តមានសិស្ស' }}
        </li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   ATTENDANCE REGISTER — CORE STYLES
═══════════════════════════════════════════════════════════════ */

/* ── Filter Bar ─────────────────────────────────────────────── */
.att-filter-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 0px !important;
    padding: 16px 20px;
    margin-bottom: 14px;
    box-shadow: var(--shadow-xs);
}
.att-filter-label {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-dark, #0f172a);
    margin-bottom: 6px;
}
.att-filter-select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    font-size: 0.96rem !important;
    min-height: 42px !important;
    height: 42px !important;
    line-height: 1.4 !important;
    padding: 0.38rem 2.35rem 0.45rem 0.85rem !important;
    border-radius: 0px !important;
    border: 1px solid var(--border-input, #cbd5e1) !important;
    color: var(--text-dark) !important;
    background-color: #fff !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 14px 10px !important;
    vertical-align: middle !important;
    cursor: pointer;
    width: 100%;
    box-sizing: border-box !important;
}
.att-filter-select::-ms-expand {
    display: none !important;
}
input[type="month"].att-filter-select {
    -webkit-appearance: auto !important;
    appearance: auto !important;
    background-image: none !important;
    padding-right: 0.85rem !important;
    cursor: default;
}
.att-filter-select:focus {
    border-color: var(--gold, #f59e0b) !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
    outline: none;
}

/* ── Register Header Card ───────────────────────────────────── */
.register-info-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 0px !important;
    padding: 16px 20px;
    margin-bottom: 12px;
    box-shadow: var(--shadow-xs);
}
.register-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 10px 20px;
}
.register-info-item { display: flex; flex-direction: column; gap: 2px; }
.register-info-label {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .04em;
}
.register-info-value {
    font-size: 0.98rem;
    font-weight: 700;
    color: var(--text-dark);
}

/* ── Summary Strip ──────────────────────────────────────────── */
.summary-strip {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.sum-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 0px !important;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-muted);
    box-shadow: var(--shadow-xs);
}
.sum-chip .sum-val {
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-dark);
}
.sum-chip.chip-present { border-color: #bbf7d0; background: #f0fdf4; }
.sum-chip.chip-present .sum-val { color: #15803d; }
.sum-chip.chip-absent  { border-color: #fecaca; background: #fef2f2; }
.sum-chip.chip-absent  .sum-val { color: #dc2626; }
.sum-chip.chip-late    { border-color: #fed7aa; background: #fff7ed; }
.sum-chip.chip-late    .sum-val { color: #ea580c; }
.sum-chip.chip-perm    { border-color: #fde68a; background: #fefce8; }
.sum-chip.chip-perm    .sum-val { color: #a16207; }

/* ── Register Table Wrapper ─────────────────────────────────── */
.register-wrapper {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 360px);
    border: 1px solid #dee2e6;
    border-radius: 0px !important;
    box-shadow: var(--shadow-sm);
    background: #fff;
}

/* ── Register Table Core ────────────────────────────────────── */
.register-table {
    border-collapse: separate;
    border-spacing: 0;
    font-size: 10px;
    white-space: nowrap;
    width: auto !important;
    min-width: unset !important;
    max-width: none !important;
    table-layout: fixed !important;
}

.register-table th,
.register-table td {
    box-sizing: border-box !important;
}

/* ── Header Rows ────────────────────────────────────────────── */
.register-table thead tr.header-row-1 {
    height: 38px !important;
}
.register-table thead tr.header-row-2 {
    height: 18px !important;
}

.register-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: #1e293b;
    color: #f1f5f9;
    border: 1px solid #334155;
    padding: 0;
    text-align: center;
    font-weight: 600;
    vertical-align: middle;
    box-sizing: border-box !important;
}

/* Row 2 headers (Day of week) */
.register-table thead tr.header-row-2 th,
.register-table thead tr.header-row-2 td {
    position: sticky !important;
    top: 38px !important; /* exactly below first header row */
    z-index: 10 !important;
    background: #0f172a !important;
    font-size: 10px;
    color: #94a3b8;
    padding: 0;
    height: 18px !important;
    line-height: 18px !important;
    vertical-align: middle !important;
    border: 1px solid #334155 !important;
    box-sizing: border-box !important;
}

.register-table tbody tr {
    height: 38px !important;
    max-height: 38px !important;
}
.register-table tbody td {
    height: 38px !important;
    max-height: 38px !important;
    box-sizing: border-box !important;
    vertical-align: middle !important;
}

/* ── Sticky Left Columns ────────────────────────────────────── */
.sc-no  { position: sticky; left: 0px;   z-index: 12; width: 38px;  min-width: 38px;  max-width: 38px; }
.sc-khm { position: sticky; left: 38px;  z-index: 12; width: 155px; min-width: 155px; max-width: 155px; text-align: left !important; }
.sc-eng { position: sticky; left: 193px; z-index: 12; width: 145px; min-width: 145px; max-width: 145px; text-align: left !important; }
.sc-sex { position: sticky; left: 338px; z-index: 12; width: 44px;  min-width: 44px;  max-width: 44px; }
.sc-dob { position: sticky; left: 382px; z-index: 12; width: 115px; min-width: 115px; max-width: 115px; }

/* Corner cells (sticky in both axes) - spanning full 56px header height */
thead th.sc-no, thead th.sc-khm, thead th.sc-eng, thead th.sc-sex, thead th.sc-dob {
    position: sticky !important;
    top: 0 !important;
    z-index: 30 !important;
    background: #1e293b !important;
    color: #f1f5f9 !important;
    border: 1px solid #334155 !important;
    font-size: 12px !important;
    font-weight: 600;
    height: 56px !important;
    vertical-align: middle !important;
    box-sizing: border-box !important;
}

/* Body sticky cells — need explicit bg to cover scrolled content */
tbody td.sc-no, tbody td.sc-khm, tbody td.sc-eng, tbody td.sc-sex, tbody td.sc-dob {
    background: #fff;
    border-right: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
    padding: 0 6px !important;
    vertical-align: middle !important;
    height: 38px !important;
    max-height: 38px !important;
    line-height: 1.3 !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    box-sizing: border-box !important;
}
tbody tr:nth-child(even) td.sc-no,
tbody tr:nth-child(even) td.sc-khm,
tbody tr:nth-child(even) td.sc-eng,
tbody tr:nth-child(even) td.sc-sex,
tbody tr:nth-child(even) td.sc-dob {
    background: #f8fafc;
}
tbody tr:hover td.sc-no,
tbody tr:hover td.sc-khm,
tbody tr:hover td.sc-eng,
tbody tr:hover td.sc-sex,
tbody tr:hover td.sc-dob {
    background: #eff6ff;
}

/* Specific column font sizing and alignment */
tbody td.sc-no {
    text-align: center;
    color: #64748b;
    font-weight: 600;
    font-size: 12px !important;
}
tbody td.sc-khm {
    text-align: left !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    color: #0f172a !important;
    padding: 0 8px !important;
}
tbody td.sc-eng {
    text-align: left !important;
    font-size: 12.5px !important;
    font-weight: 500 !important;
    color: #1e293b !important;
    padding: 0 8px !important;
}
tbody td.sc-sex {
    text-align: center;
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #334155 !important;
}
tbody td.sc-dob {
    text-align: center;
    font-size: 12px !important;
    padding: 0 4px !important;
    color: #475569 !important;
    white-space: nowrap;
}

/* ── Date Column Header ─────────────────────────────────────── */
.date-col-th {
    width: 38px !important;
    min-width: 38px !important;
    max-width: 38px !important;
    padding: 2px 0 0 0 !important;
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    height: 38px !important;
    max-height: 38px !important;
    vertical-align: middle !important;
    cursor: default;
    box-sizing: border-box !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 10 !important;
}
.date-col-th .day-num  { font-size: 12px; font-weight: 700; line-height: 1.15; }
.date-col-th .day-date { font-size: 8.5px; color: #94a3b8; line-height: 1.1; margin-bottom: 1px; }
.date-col-th.weekend   { background: #1a2744 !important; }
.date-col-th.has-session-th { background: #1e3a5f !important; border-bottom: 2px solid #3b82f6; }

.date-col-sub {
    width: 38px !important;
    min-width: 38px !important;
    max-width: 38px !important;
    font-size: 10px;
    color: #94a3b8;
    text-align: center;
    padding: 0 !important;
    background: #0f172a !important;
    height: 18px !important;
    line-height: 18px !important;
    vertical-align: middle !important;
    box-sizing: border-box !important;
    position: sticky !important;
    top: 38px !important;
    z-index: 10 !important;
    border: 1px solid #334155 !important;
}
.date-col-sub.weekend  { color: #4b5563; }

/* ── Total Columns (spans 56px across both header rows) ──────── */
.total-col-th {
    width: 38px !important;
    min-width: 38px !important;
    max-width: 38px !important;
    padding: 0 !important;
    font-size: 11px;
    font-weight: 700;
    text-align: center;
    height: 56px !important;
    vertical-align: middle !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 15 !important;
    box-sizing: border-box !important;
}
.total-col-th.perm-col { width: 44px !important; min-width: 44px !important; max-width: 44px !important; font-size: 10px; }
.total-col-th.pct-col  { width: 46px !important; min-width: 46px !important; max-width: 46px !important; font-size: 11px; }

tbody td.total-col {
    width: 38px !important;
    min-width: 38px !important;
    max-width: 38px !important;
    height: 38px !important;
    text-align: center;
    font-size: 12px;
    font-weight: 600;
    padding: 0 !important;
    vertical-align: middle;
    border: 1px solid #e2e8f0;
    line-height: 36px !important;
    box-sizing: border-box !important;
}
tbody td.total-col.tp { color: #15803d; }
tbody td.total-col.ta { color: #dc2626; }
tbody td.total-col.tl { color: #ea580c; }
tbody td.total-col.tperm { color: #a16207; width: 44px !important; min-width: 44px !important; max-width: 44px !important; }
tbody td.total-col.tpct {
    font-size: 11.5px;
    color: var(--text-muted);
    background: #f8fafc;
    width: 46px !important;
    min-width: 46px !important;
    max-width: 46px !important;
}

/* ── Attendance Cells (SQUARE 38px x 38px) ──────────────────── */
.att-cell {
    width: 38px !important;
    min-width: 38px !important;
    max-width: 38px !important;
    height: 38px !important;
    min-height: 38px !important;
    max-height: 38px !important;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid #e2e8f0;
    padding: 0 !important;
    user-select: none;
    transition: background-color .1s, outline .1s;
    position: relative;
    font-size: 13.5px;
    font-weight: 700;
    line-height: 36px !important;
    box-sizing: border-box !important;
}
.att-cell:hover { outline: 2px solid #3b82f6; outline-offset: -1px; z-index: 2; }

.att-cell.present    { background: #dcfce7; color: #15803d; }
.att-cell.absent     { background: #fee2e2; color: #dc2626; }
.att-cell.late       { background: #ffedd5; color: #ea580c; }
.att-cell.permission { background: #fef9c3; color: #a16207; }
.att-cell.unmarked   { background: #fff; color: #cbd5e1; font-size: 13px; }
.att-cell.no-session { background: #fff; color: #94a3b8; cursor: pointer; }
.att-cell.weekend-cell { background: #f8fafc; }
.att-cell.weekend-cell.no-session { background: #f8fafc; }

/* Modified cell indicator (pending save) */
.att-cell.modified { outline: 2px solid #3b82f6; outline-offset: -1px; }

/* ── Status Picker (floating) ───────────────────────────────── */
#statusPicker {
    position: fixed;
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 0px !important;
    box-shadow: 0 10px 30px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.08);
    padding: 7px;
    z-index: 9999;
    display: none;
    min-width: 230px;
}
.picker-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
}
.picker-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border: none;
    border-radius: 0px !important;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: filter .12s, transform .1s;
    letter-spacing: .01em;
    white-space: nowrap;
}
.picker-btn:hover { filter: brightness(.88); transform: scale(1.02); }
.picker-btn.pb-present    { background: #dcfce7; color: #15803d; }
.picker-btn.pb-absent     { background: #fee2e2; color: #dc2626; }
.picker-btn.pb-late       { background: #ffedd5; color: #ea580c; }
.picker-btn.pb-permission { background: #fef9c3; color: #a16207; }
.picker-btn.pb-clear      { background: #f1f5f9; color: #475569; grid-column: 1/-1; justify-content: center; font-weight: 600; font-size: 12.5px; padding: 7px 12px; }
.picker-btn.active        { outline: 2px solid currentColor; outline-offset: 1px; }

/* ── Save Section ───────────────────────────────────────────── */
.save-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0 4px;
    gap: 12px;
}
.pending-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .78rem;
    color: #3b82f6;
    font-weight: 500;
    opacity: 0;
    transition: opacity .3s;
}
.pending-badge.visible { opacity: 1; }

/* ── Empty State ────────────────────────────────────────────── */
.empty-register {
    background: #fff;
    border: 1px dashed #d1d5db;
    border-radius: 0px !important;
    padding: 60px 24px;
    text-align: center;
    color: var(--text-muted);
}
.empty-register i { font-size: 3rem; opacity: .3; margin-bottom: 12px; }

/* ── Print Styles ───────────────────────────────────────────── */
@media print {
    @page { size: A3 landscape; margin: 1.2cm; }
    .no-print { display: none !important; }
    .register-wrapper { overflow: visible !important; max-height: none !important; border: none; box-shadow: none; }
    .register-table { font-size: 9px !important; }
    .att-cell { width: 22px !important; min-width: 22px !important; height: 20px !important; font-size: 9px !important; }
    .date-col-th { width: 22px !important; min-width: 22px !important; }
    thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .att-cell.present, .att-cell.absent, .att-cell.late, .att-cell.permission {
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
}
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════════ --}}
<div class="page-header d-flex align-items-start justify-content-between mb-3 no-print">
    <div>
        <h1 class="page-title">
            {{ $readOnly ? 'បញ្ជីវត្តមានសិស្សប្រចាំខែ' : 'បញ្ជីវត្តមានសិស្ស' }}
        </h1>
        <p class="page-subtitle">
            {{ $readOnly ? 'មើលទិន្នន័យវត្តមានសិស្សប្រចាំខែតាមទម្រង់បញ្ជីសាលារៀន។' : 'កត់ត្រា និងរក្សាទុកវត្តមានសិស្សតាមទម្រង់បញ្ជីសាលារៀន។' }}
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        @if(!$readOnly)
        <a href="{{ route('attendance.monthly-register', request()->query()) }}" class="btn btn-outline-secondary btn-sm" id="btn-monthly-view">
            <i class="bi bi-table me-1"></i>ទិដ្ឋភាពប្រចាំខែ
        </a>
        @else
        <a href="{{ route('attendance.index', request()->query()) }}" class="btn btn-outline-primary btn-sm" id="btn-edit-view">
            <i class="bi bi-pencil-square me-1"></i>កែសម្រួលវត្តមាន
        </a>
        @endif
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()" id="btn-print">
            <i class="bi bi-printer me-1"></i>បោះពុម្ព
        </button>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     ALERTS
════════════════════════════════════════════════════════════ --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-3 py-2 px-3 no-print" role="alert" style="border-radius:0px !important;font-size:.9rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible d-flex gap-2 border-0 shadow-sm mb-3 py-2 px-3 no-print" role="alert" style="border-radius:0px !important;font-size:.9rem">
    <i class="bi bi-exclamation-circle-fill text-danger mt-1 flex-shrink-0"></i>
    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     FILTER BAR
════════════════════════════════════════════════════════════ --}}
<div class="att-filter-card no-print">
    <form method="GET" action="{{ route($readOnly ? 'attendance.monthly-register' : 'attendance.index') }}" id="filterForm">
        <div class="row g-2 align-items-end">

            {{-- Academic Year --}}
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="att-filter-label">ឆ្នាំសិក្សា</div>
                <select name="academic_year_id" id="academic_year_id" class="att-filter-select">
                    <option value="">— ឆ្នាំសិក្សាទាំងអស់ —</option>
                    @foreach($academicYears as $yr)
                    <option value="{{ $yr->id }}" {{ (string)$yr->id === (string)$selectedYearId ? 'selected' : '' }}>
                        {{ $yr->name }}{{ $yr->is_active ? ' (បច្ចុប្បន្ន)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Month --}}
            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                <div class="att-filter-label">ខែ</div>
                <input type="month"
                       name="month"
                       id="month_input"
                       class="att-filter-select"
                       value="{{ $selectedMonth }}"
                       style="height:42px; min-height:42px; border-radius:0px !important;">
            </div>

            {{-- Class --}}
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="att-filter-label">ថ្នាក់រៀន</div>
                <select name="class_id" id="class_id" class="att-filter-select">
                    <option value="">— ជ្រើសរើសថ្នាក់ —</option>
                    @foreach(($allClasses ?? $classes) as $cls)
                    <option value="{{ $cls->id }}"
                            data-academic-year-id="{{ $cls->academic_year_id }}"
                            {{ (string)$cls->id === (string)$selectedClassId ? 'selected' : '' }}>
                        {{ $cls->grade?->name ?? 'ថ្នាក់' }}{{ $cls->section ? ' — '.$cls->section->name : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="period" value="1">

            {{-- Load Button --}}
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary-action btn-sm px-3 w-100" style="height:42px; min-height:42px; border-radius:0px !important; font-size:0.96rem; font-weight:600; white-space:nowrap;" id="btn-load">
                    <i class="bi bi-search me-1"></i>ទាញយកបញ្ជីវត្តមាន
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ════════════════════════════════════════════════════════════
     REGISTER CONTENT (only when class + month selected)
════════════════════════════════════════════════════════════ --}}
@if($selectedClassId && $parsedMonth)

{{-- ── Print Header (visible only in print) ─────────────────── --}}
<div class="d-none d-print-block mb-3">
    <div class="text-center mb-2">
        <h4 class="fw-bold mb-1">SCHOOL MANAGEMENT SYSTEM</h4>
        <h5 class="fw-semibold">STUDENT ATTENDANCE REGISTER</h5>
    </div>
    <div class="d-flex justify-content-between" style="font-size:12px;">
        <div>
            <strong>Academic Year:</strong>
            {{ $academicYears->firstWhere('id', $selectedYearId)?->name ?? '—' }}
        </div>
        <div>
            <strong>Month:</strong>
            {{ $parsedMonth->format('F Y') }}
        </div>
        <div>
            <strong>Class:</strong>
            {{ $selectedClass?->grade?->name ?? '—' }}{{ $selectedClass?->section ? ' — '.$selectedClass->section->name : '' }}
        </div>
        @if($selectedAssignment)
        <div><strong>Teacher:</strong> {{ $selectedAssignment->employee?->user?->name ?? '—' }}</div>
        <div><strong>Subject:</strong> {{ $selectedAssignment->class_subject?->subject?->name ?? '—' }}</div>
        @endif
    </div>
</div>

{{-- ── Register Info Card ────────────────────────────────────── --}}
<div class="register-info-card no-print">
    <div class="register-info-grid">
        <div class="register-info-item">
            <span class="register-info-label"><i class="bi bi-calendar-range me-1"></i>ឆ្នាំសិក្សា</span>
            <span class="register-info-value">{{ $academicYears->firstWhere('id', $selectedYearId)?->name ?? '—' }}</span>
        </div>
        <div class="register-info-item">
            <span class="register-info-label"><i class="bi bi-calendar3 me-1"></i>ខែ</span>
            <span class="register-info-value">{{ $parsedMonth->format('F Y') }}</span>
        </div>
        <div class="register-info-item">
            <span class="register-info-label"><i class="bi bi-diagram-3 me-1"></i>ថ្នាក់រៀន</span>
            <span class="register-info-value">
                {{ $selectedClass?->grade?->name ?? '—' }}
                {{ $selectedClass?->section ? '— '.$selectedClass->section->name : '' }}
            </span>
        </div>
        @if($selectedAssignment)
        <div class="register-info-item">
            <span class="register-info-label"><i class="bi bi-person-badge me-1"></i>គ្រូបង្រៀន</span>
            <span class="register-info-value">{{ $selectedAssignment->employee?->user?->name ?? '—' }}</span>
        </div>
        <div class="register-info-item">
            <span class="register-info-label"><i class="bi bi-book me-1"></i>មុខវិជ្ជា</span>
            <span class="register-info-value">{{ $selectedAssignment->class_subject?->subject?->name ?? '—' }}</span>
        </div>
        @endif
        <div class="register-info-item">
            <span class="register-info-label"><i class="bi bi-people me-1"></i>សិស្សសរុប</span>
            <span class="register-info-value">{{ $students->count() }} នាក់</span>
        </div>
        <div class="register-info-item">
            <span class="register-info-label"><i class="bi bi-calendar-check me-1"></i>ថ្ងៃសិក្សា</span>
            <span class="register-info-value">{{ $attendanceDays }} ថ្ងៃ</span>
        </div>
    </div>
</div>

{{-- ── Summary Strip ─────────────────────────────────────────── --}}
@if($students->count())
<div class="summary-strip no-print">
    <div class="sum-chip">
        <i class="bi bi-people text-muted" style="font-size:.85rem;"></i>
        <span>សិស្ស</span>
        <span class="sum-val">{{ $classStats['total_students'] }}</span>
    </div>
    <div class="sum-chip">
        <i class="bi bi-calendar-event text-muted" style="font-size:.85rem;"></i>
        <span>វេនសិក្សា</span>
        <span class="sum-val">{{ $sessionCount }}</span>
    </div>
    <div class="sum-chip chip-present">
        <span>វត្តមាន</span>
        <span class="sum-val">{{ $classStats['P'] }}</span>
    </div>
    <div class="sum-chip chip-absent">
        <span>អវត្តមាន</span>
        <span class="sum-val">{{ $classStats['A'] }}</span>
    </div>
    <div class="sum-chip chip-late">
        <span>មកយឺត</span>
        <span class="sum-val">{{ $classStats['L'] }}</span>
    </div>
    <div class="sum-chip chip-perm">
        <span>ច្បាប់</span>
        <span class="sum-val">{{ $classStats['Perm'] }}</span>
    </div>
    @if($classStats['total'] > 0)
    <div class="sum-chip">
        <span>មធ្យមភាគវត្តមាន</span>
        <span class="sum-val">{{ round(($classStats['P'] / $classStats['total']) * 100) }}%</span>
    </div>
    @endif
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     ATTENDANCE FORM + REGISTER TABLE
════════════════════════════════════════════════════════════ --}}
@if($students->count())

<form method="POST" action="{{ route('attendance.save') }}" id="attendanceForm">
    @csrf
    <input type="hidden" name="academic_year_id"      value="{{ $selectedYearId }}">
    <input type="hidden" name="class_id"              value="{{ $selectedClassId }}">
    <input type="hidden" name="teacher_assignment_id" value="{{ $selectedAssignmentId }}">
    <input type="hidden" name="period"                value="{{ $selectedPeriod }}">
    <input type="hidden" name="month"                 value="{{ $selectedMonth }}">

    {{-- ── Register Table ──────────────────────────────────── --}}
    <div class="register-wrapper" id="registerWrapper">
        <table class="register-table" id="registerTable">

            {{-- ─── HEADER ROW 1 — Column Labels + Day Numbers ─ --}}
            <thead>
            <tr class="header-row-1">
                {{-- Sticky left columns --}}
                <th class="sc-no" rowspan="2">#</th>
                <th class="sc-khm" rowspan="2">ឈ្មោះខ្មែរ</th>
                <th class="sc-eng" rowspan="2">ឈ្មោះឡាតាំង</th>
                <th class="sc-sex" rowspan="2">ភេទ</th>
                <th class="sc-dob" rowspan="2">ថ្ងៃកំណើត</th>

                {{-- Date columns (day numbers) --}}
                @foreach($monthDates as $date)
                @php $ds = $date->format('Y-m-d'); @endphp
                <th class="date-col-th {{ $date->isWeekend() ? 'weekend' : '' }} {{ $hasSession[$ds] ? 'has-session-th' : '' }}"
                    data-date="{{ $ds }}"
                    title="{{ $date->format('D, d M Y') }} — ចុចទ្វេដងដើម្បីដាក់វត្តមានទាំងអស់">
                    <div class="day-num">{{ $date->format('d') }}</div>
                    <div class="day-date">{{ $date->format('j/n') }}</div>
                </th>
                @endforeach

                {{-- Total columns --}}
                <th class="total-col-th" rowspan="2" style="background:#1a3a1a;color:#86efac;">P</th>
                <th class="total-col-th" rowspan="2" style="background:#3a1a1a;color:#fca5a5;">A</th>
                <th class="total-col-th" rowspan="2" style="background:#3a2a1a;color:#fdba74;">L</th>
                <th class="total-col-th perm-col" rowspan="2" style="background:#3a3a1a;color:#fde68a;">Perm</th>
                <th class="total-col-th pct-col" rowspan="2" style="background:#1e2a3a;color:#93c5fd;">%</th>
            </tr>

            {{-- ─── HEADER ROW 2 — Day of Week sub-labels ──── --}}
            <tr class="header-row-2">
                @foreach($monthDates as $date)
                <th class="date-col-sub {{ $date->isWeekend() ? 'weekend' : '' }}"
                    title="{{ $date->format('D') }}">
                    {{ $date->format('D')[0] }}{{-- Mon→M, Tue→T, etc. --}}
                </th>
                @endforeach
            </tr>
            </thead>

            {{-- ─── BODY — Student Rows ──────────────────────── --}}
            <tbody>
            @foreach($students as $i => $student)
            @php
                $sid   = $student->id;
                $stats = $studentStats[$sid] ?? ['P'=>0,'A'=>0,'L'=>0,'Perm'=>0,'pct'=>0];
                $nameKhmer = trim(
                    ($student->profile?->last_name_khmer ?? '') . ' ' .
                    ($student->profile?->first_name_khmer ?? '')
                );
                $nameKhmer = $nameKhmer ?: '—';
                $nameEn    = $student->user?->name ?? '—';
                $sex       = match(strtolower($student->profile?->gender ?? '')) {
                    'male'   => 'M',
                    'female' => 'F',
                    default  => '—',
                };
                $dob = $student->profile?->dob?->format('d/m/Y') ?? '—';
            @endphp
            <tr data-student="{{ $sid }}">

                {{-- Sticky: # --}}
                <td class="sc-no">{{ $i + 1 }}</td>

                {{-- Sticky: Name Khmer --}}
                <td class="sc-khm" title="{{ $nameKhmer }}">
                    {{ $nameKhmer }}
                </td>

                {{-- Sticky: Name English --}}
                <td class="sc-eng" title="{{ $nameEn }}">
                    {{ $nameEn }}
                </td>

                {{-- Sticky: Sex --}}
                <td class="sc-sex">{{ $sex }}</td>

                {{-- Sticky: DOB --}}
                <td class="sc-dob">{{ $dob }}</td>

                {{-- Date Cells --}}
                @foreach($monthDates as $date)
                @php
                    $ds   = $date->format('Y-m-d');
                    $cell = $matrix[$sid][$ds] ?? null;
                    $isWeekend = $date->isWeekend();

                    if ($cell === null) {
                        // No session for this day
                        $cellCss      = 'no-session' . ($isWeekend ? ' weekend-cell' : '');
                        $cellSymbol   = '';
                        $statusId     = '';
                        $cellHasSession = false;
                    } elseif (empty($cell['status_id'])) {
                        // Session exists but not marked
                        $cellCss      = 'unmarked' . ($isWeekend ? ' weekend-cell' : '');
                        $cellSymbol   = '·';
                        $statusId     = '';
                        $cellHasSession = true;
                    } else {
                        $cellCss      = $cell['css_class'] . ($isWeekend ? ' weekend-cell' : '');
                        $cellSymbol   = $cell['symbol'];
                        $statusId     = $cell['status_id'];
                        $cellHasSession = true;
                    }

                    $editable = !$readOnly;
                @endphp
                <td class="att-cell {{ $cellCss }}"
                    @if($editable) data-editable="1" @endif
                    data-date="{{ $ds }}"
                    data-student="{{ $sid }}"
                    data-has-session="{{ $cellHasSession ? '1' : '0' }}"
                    title="{{ $date->format('D, d M Y') }} — {{ $nameKhmer ?: $nameEn }}">
                    <input type="hidden"
                           name="attendance[{{ $ds }}][{{ $sid }}]"
                           value="{{ $statusId }}"
                           class="att-input">
                    <span class="att-symbol">{{ $cellSymbol }}</span>
                </td>
                @endforeach

                {{-- Totals --}}
                <td class="total-col tp" data-total="P">{{ $stats['P'] }}</td>
                <td class="total-col ta" data-total="A">{{ $stats['A'] }}</td>
                <td class="total-col tl" data-total="L">{{ $stats['L'] }}</td>
                <td class="total-col tperm" data-total="Perm">{{ $stats['Perm'] }}</td>
                <td class="total-col tpct" data-total="pct">{{ $stats['pct'] }}%</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>{{-- /.register-wrapper --}}

    {{-- ── Save Section (hidden in read-only / print) ──────── --}}
    @if(!$readOnly)
    <div class="save-section no-print">
        <div class="d-flex align-items-center gap-3">
            <span class="pending-badge" id="pendingBadge">
                <i class="bi bi-pencil-square"></i>
                <span id="pendingCount">0</span> ការផ្លាស់ប្ដូរមិនទាន់រក្សាទុក
            </span>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnClearChanges" style="display:none;" data-bs-toggle="modal" data-bs-target="#discardConfirmModal">
                <i class="bi bi-arrow-counterclockwise me-1"></i>បោះបង់ការផ្លាស់ប្ដូរ
            </button>
            <button type="submit" class="btn btn-primary px-4" id="btnSave">
                <i class="bi bi-save me-1"></i>រក្សាទុកវត្តមាន
            </button>
        </div>
    </div>
    @endif

</form>{{-- /#attendanceForm --}}

@else
{{-- No students enrolled in this class --}}
<div class="empty-register">
    <i class="bi bi-people d-block mb-2"></i>
    <p class="mb-0 fw-semibold">មិនមានទិន្នន័យសិស្សនៅក្នុងថ្នាក់នេះឡើយ។</p>
    <p class="text-muted small">សូមប្រាកដថាសិស្សត្រូវបានចុះឈ្មោះចូលរៀនក្នុងថ្នាក់នេះនៅក្នុងផ្នែកគ្រប់គ្រងសិស្ស។</p>
</div>
@endif

@else
{{-- No class/month selected yet — empty state --}}
<div class="empty-register">
    <i class="bi bi-clipboard2-check d-block mb-2"></i>
    <p class="mb-0 fw-semibold text-muted">សូមជ្រើសរើសថ្នាក់រៀន និងខែ ដើម្បីទាញយកបញ្ជីវត្តមានសិស្ស។</p>
    <p class="text-muted small mt-1">ប្រើប្រាស់តម្រងខាងលើដើម្បីជ្រើសរើសថ្នាក់រៀន ឆ្នាំសិក្សា ខែ និងម៉ោងសិក្សា។</p>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     STATUS PICKER (floating panel, shared)
════════════════════════════════════════════════════════════ --}}
@if(!$readOnly)
<div id="statusPicker">
    <div class="picker-grid">
        @php
            $presentStatus = $statuses->first(fn($s) => strtoupper(trim($s->code ?? '')) === 'P' || strtolower(trim($s->name ?? '')) === 'present');
            $absentStatus  = $statuses->first(fn($s) => strtoupper(trim($s->code ?? '')) === 'A' || strtolower(trim($s->name ?? '')) === 'absent');
            $lateStatus    = $statuses->first(fn($s) => strtoupper(trim($s->code ?? '')) === 'L' || strtolower(trim($s->name ?? '')) === 'late');
            
            // Prefer On Leave (OL) or Permission for "សុំច្បាប់"
            $leaveStatus   = $statuses->first(fn($s) => strtoupper(trim($s->code ?? '')) === 'OL' || strtolower(trim($s->name ?? '')) === 'on leave')
                ?? $statuses->first(fn($s) => in_array(strtoupper(trim($s->code ?? '')), ['PERM', 'PERMISSION']) || strtolower(trim($s->name ?? '')) === 'permission')
                ?? $statuses->first(fn($s) => strtoupper(trim($s->code ?? '')) === 'EA' || strtolower(trim($s->name ?? '')) === 'excused absent');

            // Collect all permission-like status IDs so any marked leave highlights "សុំច្បាប់"
            $allPermStatusIds = $statuses->filter(function($s) {
                $c = strtoupper(trim($s->code ?? ''));
                return !in_array($c, ['P', 'A', 'L']) && !in_array(strtolower(trim($s->name ?? '')), ['present', 'absent', 'late']);
            })->pluck('id')->push($leaveStatus?->id)->filter()->unique()->values()->implode(',');

            $pickerItems = [
                [
                    'status'    => $presentStatus,
                    'name'      => 'វត្តមាន',
                    'symbol'    => '✓',
                    'css'       => 'present',
                    'btnClass'  => 'pb-present',
                    'statusIds' => (string)($presentStatus?->id ?? ''),
                ],
                [
                    'status'    => $absentStatus,
                    'name'      => 'អវត្តមាន',
                    'symbol'    => 'A',
                    'css'       => 'absent',
                    'btnClass'  => 'pb-absent',
                    'statusIds' => (string)($absentStatus?->id ?? ''),
                ],
                [
                    'status'    => $lateStatus,
                    'name'      => 'មកយឺត',
                    'symbol'    => 'L',
                    'css'       => 'late',
                    'btnClass'  => 'pb-late',
                    'statusIds' => (string)($lateStatus?->id ?? ''),
                ],
                [
                    'status'    => $leaveStatus,
                    'name'      => 'សុំច្បាប់',
                    'symbol'    => 'P',
                    'css'       => 'permission',
                    'btnClass'  => 'pb-permission',
                    'statusIds' => $allPermStatusIds,
                ],
            ];
        @endphp

        @foreach($pickerItems as $item)
        @if($item['status'])
        <button type="button"
                class="picker-btn {{ $item['btnClass'] }}"
                data-status-id="{{ $item['status']->id }}"
                data-status-ids="{{ $item['statusIds'] }}"
                data-code="{{ strtoupper($item['status']->code ?? '') }}"
                data-symbol="{{ $item['symbol'] }}"
                data-css="{{ $item['css'] }}"
                title="{{ $item['name'] }}">
            <span>{{ $item['symbol'] }}</span>
            <span>{{ $item['name'] }}</span>
        </button>
        @endif
        @endforeach

        <button type="button" class="picker-btn pb-clear" data-status-id="" data-status-ids="" data-code="" data-symbol="" data-css="" title="សម្អាតវត្តមាន">
            <i class="bi bi-x-circle me-1"></i> សម្អាត
        </button>
    </div>
</div>
@endif

{{-- ── Custom Confirmation Modal: Discard Changes ──────────── --}}
<div class="modal fade" id="discardConfirmModal" tabindex="-1" aria-labelledby="discardConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #fff;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                          style="width: 60px; height: 60px; background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                        <i class="bi bi-arrow-counterclockwise fs-2"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-2 text-dark" id="discardConfirmModalLabel" style="font-family: var(--font-heading, sans-serif);">
                    បោះបង់ការផ្លាស់ប្ដូរ?
                </h5>
                <p class="text-muted small mb-3" style="line-height: 1.6;">
                    តើអ្នកពិតជាចង់បោះបង់រាល់ការកែប្រែវត្តមានដែលមិនទាន់រក្សាទុកមែនទេ? តម្លៃទាំងអស់នឹងត្រឡប់ទៅសភាពដើមវិញភ្លាមៗ។
                </p>
                <div class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 mb-4 rounded-pill small fw-medium">
                    <i class="bi bi-pencil-square me-1"></i>មាន <span id="modalPendingCount">0</span> ការកែប្រែនឹងត្រូវបាត់បង់
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-3 py-2 fw-semibold text-secondary flex-grow-1" 
                            data-bs-dismiss="modal" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                        <i class="bi bi-x-lg me-1"></i>មិនបោះបង់
                    </button>
                    <button type="button" class="btn btn-danger px-3 py-2 fw-semibold flex-grow-1" 
                            id="btnConfirmDiscardAction" data-bs-dismiss="modal" 
                            style="border-radius: 8px; background: #dc2626; border: none; box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25);">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>បោះបង់
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Custom Confirmation Modal: Leave Page with Unsaved Changes ── --}}
<div class="modal fade" id="leaveConfirmModal" tabindex="-1" aria-labelledby="leaveConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #fff;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                          style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="bi bi-box-arrow-right fs-2"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-2 text-dark" id="leaveConfirmModalLabel" style="font-family: var(--font-heading, sans-serif);">
                    ចាកចេញពីទំព័រនេះ?
                </h5>
                <p class="text-muted small mb-3" style="line-height: 1.6;">
                    អ្នកមានការកែប្រែវត្តមានសិស្សដែលមិនទាន់បានរក្សាទុក។ ប្រសិនបើអ្នកចាកចេញ ការកែប្រែទាំងនោះនឹងត្រូវបាត់បង់។
                </p>
                <div class="badge bg-warning-subtle text-dark border border-warning-subtle px-3 py-2 mb-4 rounded-pill small fw-medium">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>មាន <span id="leaveModalPendingCount">0</span> ការកែប្រែមិនទាន់រក្សាទុក
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-3 py-2 fw-semibold text-secondary flex-grow-1" 
                            data-bs-dismiss="modal" style="border-radius: 8px; border: 1px solid #e2e8f0;">
                        <i class="bi bi-arrow-left me-1"></i>នៅទីនេះ
                    </button>
                    <button type="button" class="btn btn-warning px-3 py-2 fw-semibold text-dark flex-grow-1" 
                            id="btnConfirmLeaveAction" data-bs-dismiss="modal" 
                            style="border-radius: 8px; background: #f59e0b; border: none; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.25);">
                        <i class="bi bi-box-arrow-right me-1"></i>ចាកចេញ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Status meta (from PHP) ─────────────────────────────── */
    const statusMeta = @json($statusMeta ?? []);

    /* ── Status picker ─────────────────────────────────────── */
    const picker    = document.getElementById('statusPicker');
    const pendBadge = document.getElementById('pendingBadge');
    const pendCount = document.getElementById('pendingCount');
    const btnClear  = document.getElementById('btnClearChanges');

    let activeCell      = null;
    let pendingChanges  = 0;
    const originalValues = {}; // key: "date_studentId" → original status_id

    /* ── Attach click to editable cells ────────────────────── */
    document.querySelectorAll('.att-cell[data-editable]').forEach(cell => {
        cell.addEventListener('click', function (e) {
            e.stopPropagation();
            if (!picker) return;

            activeCell = this;

            /* Position picker near cell */
            picker.style.visibility = 'hidden';
            picker.style.display = 'block';

            const rect = this.getBoundingClientRect();
            const pickerW = picker.offsetWidth || 230;
            const pickerH = picker.offsetHeight || 135;

            let top  = rect.bottom + 4;
            let left = rect.left;

            if (top + pickerH > window.innerHeight - 10) top = rect.top - pickerH - 4;
            if (left + pickerW > window.innerWidth - 10) left = window.innerWidth - pickerW - 10;
            if (left < 4) left = 4;

            picker.style.top    = top + 'px';
            picker.style.left   = left + 'px';
            picker.style.visibility = 'visible';

            /* Highlight current selection */
            const currentId = this.querySelector('.att-input').value;
            picker.querySelectorAll('.picker-btn').forEach(btn => {
                const ids = (btn.dataset.statusIds || btn.dataset.statusId || '').split(',').filter(Boolean);
                btn.classList.toggle('active', currentId !== '' && ids.includes(currentId));
            });
        });
    });

    /* ── Picker button click ────────────────────────────────── */
    if (picker) {
        picker.querySelectorAll('.picker-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (!activeCell) return;

                const statusId = this.dataset.statusId;
                const symbol   = this.dataset.symbol;
                const css      = this.dataset.css;

                const input  = activeCell.querySelector('.att-input');
                const symEl  = activeCell.querySelector('.att-symbol');
                const cellKey = activeCell.dataset.date + '_' + activeCell.dataset.student;

                /* Save original value on first change */
                if (!(cellKey in originalValues)) {
                    originalValues[cellKey] = input.value;
                }

                const prev = input.value;
                input.value = statusId;

                /* Update cell visual */
                const baseClasses = ['att-cell'];
                if (activeCell.classList.contains('weekend-cell')) baseClasses.push('weekend-cell');
                if (css) {
                    baseClasses.push(css);
                } else if (statusId === '') {
                    baseClasses.push(activeCell.dataset.hasSession === '1' ? 'unmarked' : 'no-session');
                }
                activeCell.className = baseClasses.join(' ');
                activeCell.dataset.editable = '1';

                symEl.textContent = statusId === '' ? (activeCell.dataset.hasSession === '1' ? '·' : '') : symbol;

                /* Track modification */
                const isDifferent = originalValues[cellKey] !== statusId;
                if (isDifferent) {
                    if (!activeCell.classList.contains('modified')) {
                        activeCell.classList.add('modified');
                        pendingChanges++;
                    }
                } else {
                    if (activeCell.classList.contains('modified')) {
                        activeCell.classList.remove('modified');
                        pendingChanges = Math.max(0, pendingChanges - 1);
                    }
                }

                updatePendingUI();
                updateRowTotals(activeCell.dataset.student);

                picker.style.display = 'none';
                activeCell = null;
            });
        });

        /* Close picker on outside click */
        document.addEventListener('click', function () {
            if (picker) picker.style.display = 'none';
            activeCell = null;
        });

        picker.addEventListener('click', e => e.stopPropagation());
    }

    /* ── Discard changes ────────────────────────────────────── */
    const modalPendingCount = document.getElementById('modalPendingCount');
    const btnConfirmDiscard = document.getElementById('btnConfirmDiscardAction');

    function executeDiscardAllChanges() {
        document.querySelectorAll('.att-cell.modified').forEach(cell => {
            const key    = cell.dataset.date + '_' + cell.dataset.student;
            const input  = cell.querySelector('.att-input');
            const origVal = originalValues[key] ?? '';
            input.value = origVal;
            cell.classList.remove('modified');
            /* Revert cell appearance based on original status */
            revertCellToStatus(cell, origVal);
        });

        // Recalculate row totals for all students
        document.querySelectorAll('tr[data-student]').forEach(row => {
            if (row.dataset.student) {
                updateRowTotals(row.dataset.student);
            }
        });

        pendingChanges = 0;
        updatePendingUI();
    }

    if (btnClear) {
        btnClear.addEventListener('click', function () {
            if (modalPendingCount) {
                modalPendingCount.textContent = pendingChanges;
            }
        });
    }

    if (btnConfirmDiscard) {
        btnConfirmDiscard.addEventListener('click', function () {
            executeDiscardAllChanges();
        });
    }

    function revertCellToStatus(cell, statusId) {
        const baseClasses = ['att-cell'];
        if (cell.classList.contains('weekend-cell')) baseClasses.push('weekend-cell');

        const symEl = cell.querySelector('.att-symbol');

        if (!statusId || statusId === '') {
            const hasSession = cell.dataset.hasSession === '1';
            baseClasses.push(hasSession ? 'unmarked' : 'no-session');
            cell.className = baseClasses.join(' ');
            cell.dataset.editable = '1';
            symEl.textContent = hasSession ? '·' : '';
            return;
        }

        const meta = statusMeta.find(s => s.id == statusId);
        if (meta) {
            baseClasses.push(meta.css);
            cell.className = baseClasses.join(' ');
            cell.dataset.editable = '1';
            symEl.textContent = meta.symbol;
        }
    }

    /* ── Double-click date header: mark all students Present on that date ──── */
    document.querySelectorAll('.date-col-th[data-date]').forEach(th => {
        th.style.cursor = 'pointer';
        th.addEventListener('dblclick', function () {
            const dateStr = this.dataset.date;
            if (!dateStr) return;
            const presentMeta = statusMeta.find(s => s.code === 'P' || s.css === 'present');
            if (!presentMeta) return;

            document.querySelectorAll(`.att-cell[data-date="${dateStr}"]`).forEach(cell => {
                const input = cell.querySelector('.att-input');
                const symEl = cell.querySelector('.att-symbol');
                const cellKey = dateStr + '_' + cell.dataset.student;
                if (!(cellKey in originalValues)) {
                    originalValues[cellKey] = input.value;
                }
                input.value = presentMeta.id;
                cell.className = 'att-cell present' + (cell.classList.contains('weekend-cell') ? ' weekend-cell' : '');
                cell.dataset.editable = '1';
                symEl.textContent = presentMeta.symbol;
                if (!cell.classList.contains('modified')) {
                    cell.classList.add('modified');
                    pendingChanges++;
                }
                updateRowTotals(cell.dataset.student);
            });
            updatePendingUI();
        });
    });

    /* ── Update pending-change badge ────────────────────────── */
    function updatePendingUI() {
        if (!pendBadge) return;
        if (pendingChanges > 0) {
            pendCount.textContent = pendingChanges;
            if (modalPendingCount) modalPendingCount.textContent = pendingChanges;
            pendBadge.classList.add('visible');
            if (btnClear) btnClear.style.display = 'inline-flex';
        } else {
            pendBadge.classList.remove('visible');
            if (btnClear) btnClear.style.display = 'none';
        }
    }

    /* ── Recalculate row totals after a cell change ─────────── */
    function updateRowTotals(studentId) {
        const row = document.querySelector(`tr[data-student="${studentId}"]`);
        if (!row) return;

        let p = 0, a = 0, l = 0, perm = 0, total = 0;

        row.querySelectorAll('.att-cell').forEach(cell => {
            if (cell.classList.contains('no-session')) return;
            const input = cell.querySelector('.att-input');
            if (!input || !input.value) return;

            total++;
            if (cell.classList.contains('present'))    p++;
            else if (cell.classList.contains('absent')) a++;
            else if (cell.classList.contains('late'))   l++;
            else if (cell.classList.contains('permission')) perm++;
        });

        const pct = total > 0 ? Math.round((p / total) * 100) : 0;

        const tp   = row.querySelector('[data-total="P"]');
        const ta   = row.querySelector('[data-total="A"]');
        const tl   = row.querySelector('[data-total="L"]');
        const tprm = row.querySelector('[data-total="Perm"]');
        const tpct = row.querySelector('[data-total="pct"]');

        if (tp)   tp.textContent   = p;
        if (ta)   ta.textContent   = a;
        if (tl)   tl.textContent   = l;
        if (tprm) tprm.textContent = perm;
        if (tpct) tpct.textContent = pct + '%';
    }

    /* ── Dynamic Filter: Show classes by selected academic year ─ */
    const yearSelect  = document.getElementById('academic_year_id');
    const classSelect = document.getElementById('class_id');
    const assignSelect = document.getElementById('teacher_assignment_id');

    if (yearSelect && classSelect) {
        // Cache all class options rendered with data-academic-year-id
        const allClassItems = Array.from(classSelect.querySelectorAll('option'))
            .filter(opt => opt.value !== '')
            .map(opt => ({
                value: opt.value,
                text: opt.textContent.trim(),
                yearId: opt.getAttribute('data-academic-year-id') || '',
                selected: opt.selected
            }));

        function filterClassesByYear(isInitial = false) {
            const selectedYearId = String(yearSelect.value).trim();
            const currentSelectedClass = String(classSelect.value).trim();

            classSelect.innerHTML = '<option value="">— ជ្រើសរើសថ្នាក់ —</option>';

            let hasSelectedMatch = false;

            allClassItems.forEach(item => {
                if (!selectedYearId || String(item.yearId) === selectedYearId) {
                    const opt = document.createElement('option');
                    opt.value = item.value;
                    opt.textContent = item.text;
                    opt.setAttribute('data-academic-year-id', item.yearId);

                    if (isInitial && item.selected) {
                        opt.selected = true;
                        hasSelectedMatch = true;
                    } else if (!isInitial && currentSelectedClass && String(item.value) === currentSelectedClass) {
                        opt.selected = true;
                        hasSelectedMatch = true;
                    }
                    classSelect.appendChild(opt);
                }
            });

            if (!hasSelectedMatch) {
                classSelect.value = '';
            }
        }

        yearSelect.addEventListener('change', function () {
            filterClassesByYear(false);
        });

        // Run on page load so dropdown immediately matches the selected year
        filterClassesByYear(true);
    }

    if (classSelect && assignSelect) {
        classSelect.addEventListener('change', function () {
            const classId  = this.value;
            const yearId   = yearSelect ? yearSelect.value : '';

            if (!classId) {
                assignSelect.innerHTML = '<option value="">— All Assignments —</option>';
                return;
            }

            assignSelect.innerHTML = '<option value="">Loading…</option>';
            assignSelect.disabled  = true;

            const url = `/api/attendance/teacher-assignments?class_id=${classId}&academic_year_id=${yearId}`;

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    assignSelect.innerHTML = '<option value="">— All Assignments —</option>';
                    data.forEach(a => {
                        const opt = document.createElement('option');
                        opt.value       = a.id;
                        opt.textContent = `${a.subject_name} · ${a.teacher_name}`;
                        assignSelect.appendChild(opt);
                    });
                    assignSelect.disabled = false;
                })
                .catch(() => {
                    assignSelect.innerHTML = '<option value="">— Error loading —</option>';
                    assignSelect.disabled = false;
                });
        });
    }

    /* ── In-App Leave Confirmation for Unsaved Changes ───────── */
    const leaveModalEl = document.getElementById('leaveConfirmModal');
    const leaveModal = leaveModalEl && window.bootstrap ? new bootstrap.Modal(leaveModalEl) : null;
    const leavePendingCount = document.getElementById('leaveModalPendingCount');
    const btnConfirmLeave = document.getElementById('btnConfirmLeaveAction');
    let pendingNavigationAction = null;

    function promptUnsavedLeave(onConfirm) {
        if (pendingChanges > 0 && leaveModal) {
            pendingNavigationAction = onConfirm;
            if (leavePendingCount) {
                leavePendingCount.textContent = pendingChanges;
            }
            leaveModal.show();
            return false;
        }
        return true;
    }

    if (btnConfirmLeave) {
        btnConfirmLeave.addEventListener('click', function () {
            pendingChanges = 0; // prevent re-prompt
            if (leaveModal) leaveModal.hide();
            if (typeof pendingNavigationAction === 'function') {
                pendingNavigationAction();
            }
        });
    }

    // Intercept clicks on links that leave the page
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
        if (link.target === '_blank') return;
        if (link.getAttribute('download') !== null) return;

        if (pendingChanges > 0) {
            e.preventDefault();
            e.stopPropagation();
            promptUnsavedLeave(function () {
                window.location.href = href;
            });
        }
    }, true);

    // Intercept filter form submit when there are unsaved changes
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            if (pendingChanges > 0) {
                e.preventDefault();
                promptUnsavedLeave(function () {
                    pendingChanges = 0;
                    filterForm.submit();
                });
            }
        });
    }

})();
</script>
@endpush
