@extends('layouts.app')

@section('title', 'កត់ត្រាវត្តមានបុគ្គលិក — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('staff-attendance.index', ['date' => $selectedDate]) }}" class="text-decoration-none text-muted">វត្តមានបុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កត់ត្រាវត្តមាន</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

* { box-sizing: border-box; }

.att-create-wrap {
    max-width: 1100px;
    margin: 0 auto;
    font-family: 'Inter', 'Hanuman', sans-serif;
}

/* ── Page Header ─────────────────────────────── */
.att-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.att-page-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.att-header-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(245,158,11,0.3);
    flex-shrink: 0;
}
.att-header-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.2rem;
    line-height: 1.2;
}
.att-header-sub {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0;
}
.att-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 1.1rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    color: #475569;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.15s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.att-back-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
    text-decoration: none;
}

/* ── Grid Layout ─────────────────────────────── */
.att-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 900px) {
    .att-grid {
        grid-template-columns: 1fr;
    }
}

/* ── Main Form Panel ─────────────────────────── */
.att-form-panel {
    background: #fff;
    border: 1px solid #e8edf3;
    border-radius: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.04);
    overflow: hidden;
}
.att-form-panel-header {
    padding: 1.5rem 1.75rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.att-panel-step-badge {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: 0.8rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.att-panel-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.att-panel-sub {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0.1rem 0 0;
}
.att-form-panel-body {
    padding: 1.5rem 1.75rem;
}
.att-form-panel + .att-form-panel {
    margin-top: 1.25rem;
}

/* ── Form Fields ─────────────────────────────── */
.att-label {
    font-size: 0.8rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.att-label .req { color: #ef4444; }
.att-input {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.6rem 0.9rem;
    font-size: 0.875rem;
    color: #1e293b;
    background: #fff;
    transition: border-color 0.15s, box-shadow 0.15s;
    appearance: none;
    -webkit-appearance: none;
}
.att-input:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
}
.att-input.is-invalid { border-color: #ef4444; }
.att-input-prefix {
    display: flex;
    align-items: stretch;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    transition: border-color 0.15s, box-shadow 0.15s;
    background: #fff;
}
.att-input-prefix:focus-within {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
}
.att-input-prefix-icon {
    width: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border-right: 1.5px solid #e2e8f0;
    font-size: 0.95rem;
    flex-shrink: 0;
}
.att-input-prefix .att-bare-input {
    border: none;
    outline: none;
    padding: 0.6rem 0.9rem;
    font-size: 0.875rem;
    color: #1e293b;
    background: transparent;
    flex: 1;
    min-width: 0;
}
.invalid-feedback { font-size: 0.75rem; }

/* ── Status Cards ────────────────────────────── */
.status-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
@media (max-width: 600px) {
    .status-grid { grid-template-columns: repeat(2, 1fr); }
}
.status-card-label {
    cursor: pointer;
    display: block;
    margin: 0;
}
.status-card-label input { display: none; }
.status-card-box {
    border: 2px solid #e8edf3;
    border-radius: 12px;
    padding: 1rem 0.5rem 0.9rem;
    text-align: center;
    transition: all 0.18s ease;
    background: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.35rem;
}
.status-card-label:hover .status-card-box {
    transform: translateY(-2px);
    border-color: #cbd5e1;
    background: #f9fafb;
}
.status-card-icon {
    font-size: 1.6rem;
    color: #cbd5e1;
    transition: color 0.15s;
    line-height: 1;
}
.status-card-name {
    font-size: 0.82rem;
    font-weight: 700;
    color: #64748b;
    transition: color 0.15s;
}
.status-card-desc {
    font-size: 0.68rem;
    color: #94a3b8;
}
/* Checked states */
.status-card-label input[value="Present"]:checked ~ .status-card-box,
.status-card-label:has(input[value="Present"]:checked) .status-card-box {
    border-color: #10b981; background: #f0fdf4;
    box-shadow: 0 4px 14px rgba(16,185,129,0.13);
}
.status-card-label:has(input[value="Present"]:checked) .status-card-icon { color: #10b981; }
.status-card-label:has(input[value="Present"]:checked) .status-card-name { color: #065f46; }

.status-card-label:has(input[value="Late"]:checked) .status-card-box {
    border-color: #f59e0b; background: #fffbeb;
    box-shadow: 0 4px 14px rgba(245,158,11,0.13);
}
.status-card-label:has(input[value="Late"]:checked) .status-card-icon { color: #f59e0b; }
.status-card-label:has(input[value="Late"]:checked) .status-card-name { color: #92400e; }

.status-card-label:has(input[value="Half Day"]:checked) .status-card-box {
    border-color: #8b5cf6; background: #f5f3ff;
    box-shadow: 0 4px 14px rgba(139,92,246,0.13);
}
.status-card-label:has(input[value="Half Day"]:checked) .status-card-icon { color: #8b5cf6; }
.status-card-label:has(input[value="Half Day"]:checked) .status-card-name { color: #4c1d95; }

.status-card-label:has(input[value="Absent"]:checked) .status-card-box {
    border-color: #ef4444; background: #fef2f2;
    box-shadow: 0 4px 14px rgba(239,68,68,0.13);
}
.status-card-label:has(input[value="Absent"]:checked) .status-card-icon { color: #ef4444; }
.status-card-label:has(input[value="Absent"]:checked) .status-card-name { color: #991b1b; }

/* ── Time Presets ────────────────────────────── */
.preset-chip-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}
.preset-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.32rem 0.75rem;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.14s;
    white-space: nowrap;
}
.preset-chip:hover {
    background: #e8f0fe;
    color: #1d4ed8;
    border-color: #bfdbfe;
}
.preset-chip.danger:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}

/* ── Duration Display ────────────────────────── */
.duration-display {
    margin-top: 1.1rem;
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border: 1px solid #bae6fd;
    border-radius: 12px;
    padding: 0.9rem 1.1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.duration-display-left {
    display: flex;
    align-items: center;
    gap: 0.65rem;
}
.duration-icon-wrap {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: #0ea5e9;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.duration-label { font-size: 0.8rem; font-weight: 700; color: #0c4a6e; }
.duration-sub { font-size: 0.7rem; color: #0369a1; margin-top: 0.1rem; }
.duration-value {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0369a1;
    background: #fff;
    padding: 0.3rem 0.85rem;
    border-radius: 999px;
    border: 1px solid #bae6fd;
}

/* ── Remark Tags ─────────────────────────────── */
.tag-chip-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    margin-bottom: 0.85rem;
}
.tag-chip {
    padding: 0.28rem 0.7rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    font-size: 0.73rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.14s;
}
.tag-chip:hover { background: #fef3c7; border-color: #fde68a; color: #92400e; }

/* ── Form Actions ────────────────────────────── */
.att-form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.75rem;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
}
.btn-att-cancel {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1.1rem;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-att-cancel:hover { background: #f8fafc; color: #374151; text-decoration: none; }
.btn-att-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.6rem 1.5rem;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s;
    box-shadow: 0 4px 12px rgba(245,158,11,0.3);
}
.btn-att-submit:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    box-shadow: 0 6px 16px rgba(245,158,11,0.4);
    transform: translateY(-1px);
}

/* ── Right Preview Panel ─────────────────────── */
.att-preview-sticky { position: sticky; top: 1rem; }

.att-preview-card {
    background: #fff;
    border: 1px solid #e8edf3;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05);
}
.att-preview-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 1.5rem 1rem 3rem;
    text-align: center;
    position: relative;
}
.att-preview-hero::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 28px;
    background: #fff;
    border-radius: 50% 50% 0 0 / 100% 100% 0 0;
}
.att-preview-hero-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    margin-bottom: 0.3rem;
}
.att-preview-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 800;
    border: 3px solid #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    margin-top: -42px;
    position: relative;
    z-index: 2;
}
.att-preview-body {
    padding: 0.75rem 1.4rem 1.25rem;
    text-align: center;
}
.att-preview-name {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.att-preview-pos {
    font-size: 0.75rem;
    color: #94a3b8;
    margin: 0 0 1rem;
}
.att-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 1rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 1.1rem;
    background: #ecfdf5;
    color: #065f46;
    transition: all 0.2s;
}

.att-preview-rows { border-top: 1px solid #f1f5f9; }
.att-preview-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.55rem 0;
    border-bottom: 1px dashed #f1f5f9;
    font-size: 0.8rem;
}
.att-preview-row:last-child { border-bottom: none; }
.att-row-label { color: #94a3b8; display: flex; align-items: center; gap: 0.35rem; }
.att-row-val { font-weight: 700; color: #1e293b; text-align: right; max-width: 55%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ── Rule card ───────────────────────────────── */
.att-rule-card {
    margin-top: 1rem;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 0.9rem 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
}
.att-rule-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f59e0b;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.att-rule-title { font-size: 0.8rem; font-weight: 700; color: #92400e; margin: 0 0 0.15rem; }
.att-rule-text { font-size: 0.72rem; color: #b45309; margin: 0; line-height: 1.5; }
</style>
@endpush

@section('content')
<div class="att-create-wrap">

{{-- ── Page Header ──────────────────────────────────── --}}
<div class="att-page-header">
    <div class="att-page-header-left">
        <div class="att-header-icon"><i class="bi bi-calendar-check-fill"></i></div>
        <div>
            <h1 class="att-header-title">កត់ត្រាវត្តមានបុគ្គលិក</h1>
            <p class="att-header-sub">ចុះបញ្ជីវត្តមានប្រចាំថ្ងៃ ម៉ោងចូល-ចេញ សម្រាប់បុគ្គលិកសាលា</p>
        </div>
    </div>
    <a href="{{ route('staff-attendance.index', ['date' => $selectedDate]) }}" class="att-back-btn">
        <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
    </a>
</div>

{{-- ── Error Alerts ─────────────────────────────────── --}}
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
    <div class="d-flex gap-2">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5 mt-1 flex-shrink-0"></i>
        <div>
            <strong class="d-block mb-1">សូមពិនិត្យកែកំហុស:</strong>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-x-octagon-fill fs-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('staff-attendance.store') }}" method="POST" id="attForm" novalidate>
@csrf

<div class="att-grid">

    {{-- ════════════════════════════════════════════════
         LEFT COLUMN — Form sections
    ════════════════════════════════════════════════ --}}
    <div>

        {{-- Section 1: Employee + Date --}}
        <div class="att-form-panel">
            <div class="att-form-panel-header">
                <div class="att-panel-step-badge">1</div>
                <div>
                    <p class="att-panel-title">ព័ត៌មានបុគ្គលិក & កាលបរិច្ឆេទ</p>
                    <p class="att-panel-sub">ជ្រើសរើសបុគ្គលិក និងថ្ងៃដែលត្រូវកត់ត្រា</p>
                </div>
            </div>
            <div class="att-form-panel-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="att-label" for="employee_id">
                            <i class="bi bi-person-fill text-warning"></i>
                            បុគ្គលិក <span class="req">*</span>
                        </label>
                        <select name="employee_id" id="employee_id"
                                class="att-input form-select @error('employee_id') is-invalid @enderror"
                                required onchange="onEmployeeChange()">
                            <option value="">— ជ្រើសរើសបុគ្គលិក —</option>
                            @foreach($employees as $emp)
                                @php
                                    $empName = $emp->user->name ?? 'Staff';
                                    $empDept = $emp->employee_department->name ?? 'ទូទៅ';
                                    $empPos  = $emp->employee_position->name ?? 'បុគ្គលិក';
                                    $initials = mb_substr($empName, 0, 2);
                                @endphp
                                <option value="{{ $emp->id }}"
                                        data-name="{{ $empName }}"
                                        data-code="{{ $emp->employee_code }}"
                                        data-dept="{{ $empDept }}"
                                        data-pos="{{ $empPos }}"
                                        data-initials="{{ $initials }}"
                                        {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $empName }} ({{ $emp->employee_code }}) — {{ $empPos }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="min-height:1.6rem;">
                            <label class="att-label mb-0" for="attendance_date">
                                <i class="bi bi-calendar3 text-warning"></i>
                                កាលបរិច្ឆេទ <span class="req">*</span>
                            </label>
                            <button type="button" onclick="setDateToday()"
                                    style="font-size:0.72rem; color:#d97706; background:none; border:none; cursor:pointer; font-weight:600; padding:0;">
                                ថ្ងៃនេះ
                            </button>
                        </div>
                        <input type="date" name="attendance_date" id="attendance_date"
                               class="att-input @error('attendance_date') is-invalid @enderror"
                               value="{{ old('attendance_date', $selectedDate) }}"
                               required onchange="onDateChange()">
                        @error('attendance_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Status --}}
        <div class="att-form-panel">
            <div class="att-form-panel-header">
                <div class="att-panel-step-badge">2</div>
                <div>
                    <p class="att-panel-title">ស្ថានភាពវត្តមាន</p>
                    <p class="att-panel-sub">ជ្រើសរើសប្រភេទស្ថានភាពប្រចាំថ្ងៃ</p>
                </div>
            </div>
            <div class="att-form-panel-body">
                <div class="status-grid">

                    <label class="status-card-label">
                        <input type="radio" name="status" value="Present"
                               {{ old('status', 'Present') == 'Present' ? 'checked' : '' }}
                               onchange="onStatusChange('Present')">
                        <div class="status-card-box">
                            <i class="bi bi-check-circle-fill status-card-icon"></i>
                            <span class="status-card-name">មានវត្តមាន</span>
                            <span class="status-card-desc">ពេញមួយថ្ងៃ</span>
                        </div>
                    </label>

                    <label class="status-card-label">
                        <input type="radio" name="status" value="Late"
                               {{ old('status') == 'Late' ? 'checked' : '' }}
                               onchange="onStatusChange('Late')">
                        <div class="status-card-box">
                            <i class="bi bi-clock-fill status-card-icon"></i>
                            <span class="status-card-name">យឺតពេល</span>
                            <span class="status-card-desc">មកមិនទាន់ម៉ោង</span>
                        </div>
                    </label>

                    <label class="status-card-label">
                        <input type="radio" name="status" value="Half Day"
                               {{ old('status') == 'Half Day' ? 'checked' : '' }}
                               onchange="onStatusChange('Half Day')">
                        <div class="status-card-box">
                            <i class="bi bi-brightness-alt-high-fill status-card-icon"></i>
                            <span class="status-card-name">កន្លះថ្ងៃ</span>
                            <span class="status-card-desc">ពាក់កណ្ដាល</span>
                        </div>
                    </label>

                    <label class="status-card-label">
                        <input type="radio" name="status" value="Absent"
                               {{ old('status') == 'Absent' ? 'checked' : '' }}
                               onchange="onStatusChange('Absent')">
                        <div class="status-card-box">
                            <i class="bi bi-x-circle-fill status-card-icon"></i>
                            <span class="status-card-name">អវត្តមាន</span>
                            <span class="status-card-desc">គ្មានវត្តមាន</span>
                        </div>
                    </label>

                </div>
                @error('status')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Section 3: Time --}}
        <div class="att-form-panel">
            <div class="att-form-panel-header">
                <div class="att-panel-step-badge">3</div>
                <div>
                    <p class="att-panel-title">ម៉ោងចូល & ម៉ោងចេញ</p>
                    <p class="att-panel-sub">ម៉ោងចូលធ្វើការ និងម៉ោងចេញពីធ្វើការ</p>
                </div>
            </div>
            <div class="att-form-panel-body">

                <div class="preset-chip-row">
                    <span style="font-size:0.72rem; font-weight:700; color:#94a3b8; align-self:center;">កំណត់រហ័ស:</span>
                    <button type="button" class="preset-chip" onclick="applyStandardShift()">
                        <i class="bi bi-briefcase"></i> 08:00 – 17:00
                    </button>
                    <button type="button" class="preset-chip" onclick="applyMorningHalfDay()">
                        <i class="bi bi-brightness-high"></i> 08:00 – 12:00
                    </button>
                    <button type="button" class="preset-chip" onclick="setInNow()">
                        <i class="bi bi-box-arrow-in-right"></i> ចូលឥឡូវ
                    </button>
                    <button type="button" class="preset-chip" onclick="setOutNow()">
                        <i class="bi bi-box-arrow-right"></i> ចេញឥឡូវ
                    </button>
                    <button type="button" class="preset-chip danger" onclick="clearTimes()">
                        <i class="bi bi-eraser"></i> សម្អាត
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="att-label" for="in_time">
                            <i class="bi bi-box-arrow-in-right text-success"></i> ម៉ោងចូល
                        </label>
                        <div class="att-input-prefix @error('in_time') is-invalid @enderror">
                            <div class="att-input-prefix-icon text-success"><i class="bi bi-arrow-right-circle-fill"></i></div>
                            <input type="time" name="in_time" id="in_time"
                                   class="att-bare-input"
                                   value="{{ old('in_time', '08:00') }}" onchange="recalc()">
                        </div>
                        @error('in_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="att-label" for="out_time">
                            <i class="bi bi-box-arrow-right text-danger"></i> ម៉ោងចេញ
                        </label>
                        <div class="att-input-prefix @error('out_time') is-invalid @enderror">
                            <div class="att-input-prefix-icon text-danger"><i class="bi bi-arrow-left-circle-fill"></i></div>
                            <input type="time" name="out_time" id="out_time"
                                   class="att-bare-input"
                                   value="{{ old('out_time', '17:00') }}" onchange="recalc()">
                        </div>
                        @error('out_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="duration-display">
                    <div class="duration-display-left">
                        <div class="duration-icon-wrap"><i class="bi bi-hourglass-split"></i></div>
                        <div>
                            <div class="duration-label">រយៈពេលបម្រើការងារសរុប</div>
                            <div class="duration-sub" id="shiftSubtitle">គណនាដោយស្វ័យប្រវត្តិ</div>
                        </div>
                    </div>
                    <div class="duration-value" id="durationBadge">9 ម៉ោង 0 នាទី</div>
                </div>

            </div>
        </div>

        {{-- Section 4: Remarks --}}
        <div class="att-form-panel">
            <div class="att-form-panel-header">
                <div class="att-panel-step-badge">4</div>
                <div>
                    <p class="att-panel-title">កំណត់ចំណាំ</p>
                    <p class="att-panel-sub">ព័ត៌មានបន្ថែមឬមូលហេតុ (ជាជម្រើស)</p>
                </div>
            </div>
            <div class="att-form-panel-body">
                <div class="tag-chip-row">
                    <span style="font-size:0.72rem; font-weight:700; color:#94a3b8; align-self:center;">ស្លាករហ័ស:</span>
                    <button type="button" class="tag-chip" onclick="addTag('បំពេញការងារធម្មតា')">បំពេញការងារធម្មតា</button>
                    <button type="button" class="tag-chip" onclick="addTag('មានធុរៈផ្ទាល់ខ្លួន')">មានធុរៈផ្ទាល់ខ្លួន</button>
                    <button type="button" class="tag-chip" onclick="addTag('មានបញ្ហាសុខភាព')">មានបញ្ហាសុខភាព</button>
                    <button type="button" class="tag-chip" onclick="addTag('ចុះបេសកកម្ម')">ចុះបេសកកម្ម</button>
                    <button type="button" class="tag-chip" onclick="addTag('បានអនុញ្ញាតមកយឺត')">បានអនុញ្ញាតមកយឺត</button>
                </div>
                <textarea name="remarks" id="remarks" rows="3"
                          class="att-input @error('remarks') is-invalid @enderror"
                          style="resize:vertical;"
                          placeholder="បញ្ចូលកំណត់ចំណាំ ឬមូលហេតុ..."
                          oninput="updateRemarks()">{{ old('remarks') }}</textarea>
                @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="att-form-actions">
                <p class="mb-0 small text-muted" style="font-size:0.75rem;">
                    <span class="text-danger fw-bold">*</span> ប្រអប់ចាំបាច់
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ route('staff-attendance.index', ['date' => $selectedDate]) }}" class="btn-att-cancel">
                        <i class="bi bi-x-lg"></i> បោះបង់
                    </a>
                    <button type="submit" class="btn-att-submit">
                        <i class="bi bi-check2-circle"></i> រក្សាទុកវត្តមាន
                    </button>
                </div>
            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ════════════════════════════════════════════════
         RIGHT COLUMN — Live Preview
    ════════════════════════════════════════════════ --}}
    <div class="att-preview-sticky">

        <div class="att-preview-card">
            <div class="att-preview-hero">
                <div class="att-preview-hero-label">ទិដ្ឋភាពទូទៅ</div>
            </div>

            <div class="text-center" style="margin-top:-36px; position:relative; z-index:3;">
                <div class="att-preview-avatar" id="prevAvatar">--</div>
            </div>

            <div class="att-preview-body">
                <h3 class="att-preview-name" id="prevName">ជ្រើសរើសបុគ្គលិក</h3>
                <p class="att-preview-pos" id="prevPos">ផ្នែក • មុខតំណែង</p>

                <div class="mb-3">
                    <span class="att-status-pill" id="prevStatusPill">
                        <i class="bi bi-check-circle-fill"></i> មានវត្តមាន
                    </span>
                </div>

                <div class="att-preview-rows">
                    <div class="att-preview-row">
                        <span class="att-row-label"><i class="bi bi-calendar-date"></i> ថ្ងៃ</span>
                        <span class="att-row-val" id="prevDate">{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</span>
                    </div>
                    <div class="att-preview-row">
                        <span class="att-row-label"><i class="bi bi-box-arrow-in-right text-success"></i> ចូល</span>
                        <span class="att-row-val text-success" id="prevIn">08:00</span>
                    </div>
                    <div class="att-preview-row">
                        <span class="att-row-label"><i class="bi bi-box-arrow-right text-danger"></i> ចេញ</span>
                        <span class="att-row-val text-danger" id="prevOut">17:00</span>
                    </div>
                    <div class="att-preview-row">
                        <span class="att-row-label"><i class="bi bi-hourglass-split text-info"></i> រយៈពេល</span>
                        <span class="att-row-val text-info" id="prevDur">9 ម៉ោង 0 នាទី</span>
                    </div>
                    <div class="att-preview-row">
                        <span class="att-row-label"><i class="bi bi-chat-square-text"></i> ចំណាំ</span>
                        <span class="att-row-val text-muted fst-italic" id="prevNote">គ្មាន</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="att-rule-card">
            <div class="att-rule-icon"><i class="bi bi-info-lg"></i></div>
            <div>
                <p class="att-rule-title">កត់ត្រាម្ដងក្នុងមួយថ្ងៃ</p>
                <p class="att-rule-text">
                    បុគ្គលិកម្នាក់ៗអាចមានកំណត់ត្រាបានតែមួយក្នុងមួយថ្ងៃ។
                    ប្រសិនបើចង់កែ ត្រូវចូលទៅកែប្រែដោយផ្ទាល់ក្នុងបញ្ជីវត្តមាន។
                </p>
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end att-grid --}}
</form>

</div>{{-- end wrap --}}
@endsection

@push('scripts')
<script>
// ── Employee change ───────────────────────────────────────────
function onEmployeeChange() {
    const sel = document.getElementById('employee_id');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('prevAvatar').textContent = opt.dataset.initials || 'បុ';
        document.getElementById('prevName').textContent   = opt.dataset.name;
        document.getElementById('prevPos').textContent    = opt.dataset.pos + ' • ' + opt.dataset.dept;
    } else {
        document.getElementById('prevAvatar').textContent = '--';
        document.getElementById('prevName').textContent   = 'ជ្រើសរើសបុគ្គលិក';
        document.getElementById('prevPos').textContent    = 'ផ្នែក • មុខតំណែង';
    }
}

// ── Date change ───────────────────────────────────────────────
function onDateChange() {
    const val = document.getElementById('attendance_date').value;
    if (!val) return;
    const d = new Date(val);
    const dd = String(d.getDate()).padStart(2,'0');
    const mm = String(d.getMonth()+1).padStart(2,'0');
    document.getElementById('prevDate').textContent = `${dd}/${mm}/${d.getFullYear()}`;
}
function setDateToday() {
    document.getElementById('attendance_date').value = new Date().toISOString().split('T')[0];
    onDateChange();
}

// ── Status change ─────────────────────────────────────────────
const statusConfig = {
    'Present':  { bg:'#ecfdf5', color:'#065f46', icon:'bi-check-circle-fill',           label:'មានវត្តមាន' },
    'Late':     { bg:'#fffbeb', color:'#92400e', icon:'bi-clock-fill',                  label:'យឺតពេល' },
    'Half Day': { bg:'#f5f3ff', color:'#4c1d95', icon:'bi-brightness-alt-high-fill',    label:'កន្លះថ្ងៃ' },
    'Absent':   { bg:'#fef2f2', color:'#991b1b', icon:'bi-x-circle-fill',               label:'អវត្តមាន' },
};
function onStatusChange(status) {
    const c = statusConfig[status];
    if (!c) return;
    const pill = document.getElementById('prevStatusPill');
    pill.style.background = c.bg;
    pill.style.color = c.color;
    pill.innerHTML = `<i class="bi ${c.icon}"></i> ${c.label}`;

    if (status === 'Present')  applyStandardShift();
    if (status === 'Late')     { document.getElementById('in_time').value='09:00'; document.getElementById('out_time').value='17:00'; }
    if (status === 'Half Day') { document.getElementById('in_time').value='08:00'; document.getElementById('out_time').value='12:00'; }
    if (status === 'Absent')   clearTimes();
    recalc();
}

// ── Time helpers ──────────────────────────────────────────────
function applyStandardShift()  { set('in_time','08:00'); set('out_time','17:00'); recalc(); }
function applyMorningHalfDay() { set('in_time','08:00'); set('out_time','12:00'); recalc(); }
function clearTimes()          { set('in_time','');      set('out_time','');      recalc(); }
function setInNow()  { set('in_time',  now()); recalc(); }
function setOutNow() { set('out_time', now()); recalc(); }
function set(id, val) { document.getElementById(id).value = val; }
function now() {
    const n = new Date();
    return String(n.getHours()).padStart(2,'0') + ':' + String(n.getMinutes()).padStart(2,'0');
}

// ── Recalculate duration ──────────────────────────────────────
function recalc() {
    const inV  = document.getElementById('in_time').value;
    const outV = document.getElementById('out_time').value;
    document.getElementById('prevIn').textContent  = inV  || '—';
    document.getElementById('prevOut').textContent = outV || '—';

    if (!inV || !outV) {
        document.getElementById('durationBadge').textContent = '—';
        document.getElementById('prevDur').textContent = '—';
        document.getElementById('shiftSubtitle').textContent = 'ត្រូវការម៉ោងចូល និងម៉ោងចេញ';
        return;
    }
    const [ih, im] = inV.split(':').map(Number);
    const [oh, om] = outV.split(':').map(Number);
    const diff = (oh*60+om) - (ih*60+im);
    if (diff <= 0) {
        document.getElementById('durationBadge').textContent = 'ម៉ោងមិនត្រឹមត្រូវ';
        document.getElementById('prevDur').textContent = '—';
        document.getElementById('shiftSubtitle').textContent = 'ម៉ោងចេញត្រូវធំជាងម៉ោងចូល';
        return;
    }
    const h = Math.floor(diff/60), m = diff%60;
    const txt = `${h} ម៉ោង ${m} នាទី`;
    document.getElementById('durationBadge').textContent  = txt;
    document.getElementById('prevDur').textContent        = txt;
    document.getElementById('shiftSubtitle').textContent  = `ពី ${inV} ដល់ ${outV}`;
}

// ── Remarks ───────────────────────────────────────────────────
function addTag(t) {
    const ta = document.getElementById('remarks');
    ta.value = ta.value ? ta.value + ' ' + t : t;
    updateRemarks();
}
function updateRemarks() {
    const v = document.getElementById('remarks').value.trim();
    document.getElementById('prevNote').textContent = v || 'គ្មាន';
}

// ── Init ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    onEmployeeChange();
    recalc();
    updateRemarks();
    // Init status pill colour
    const checked = document.querySelector('input[name="status"]:checked');
    if (checked) onStatusChange(checked.value);
});
</script>
@endpush
