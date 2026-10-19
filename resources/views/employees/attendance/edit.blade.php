@extends('layouts.app')

@section('title', 'កែប្រែវត្តមានបុគ្គលិក — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('staff-attendance.index', ['date' => $staff_attendance->attendance_date->format('Y-m-d')]) }}" class="text-decoration-none text-muted">វត្តមានបុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កែប្រែទិន្នន័យ</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   HERO BANNER
═══════════════════════════════════════════════════════════════ */
.employee-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-lg, 14px);
    padding: 1.6rem 2rem;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
    color: #fff;
    box-shadow: 0 4px 15px rgba(232, 168, 56, 0.25);
}
.employee-banner::before {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 170px;
    height: 170px;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 50%;
    pointer-events: none;
}
.employee-banner::after {
    content: '';
    position: absolute;
    bottom: -35px;
    right: 90px;
    width: 110px;
    height: 110px;
    background: rgba(255, 255, 255, 0.07);
    border-radius: 50%;
    pointer-events: none;
}
.employee-banner .banner-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-md, 10px);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    color: #fff;
    margin-bottom: 0.75rem;
}
.employee-banner h1 {
    font-size: 1.35rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #fff;
}
.employee-banner p {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 0;
}
.banner-actions {
    position: absolute;
    top: 1.4rem;
    right: 1.5rem;
    display: flex;
    gap: 0.5rem;
    z-index: 2;
}

/* ═══════════════════════════════════════════════════════════════
   FORM CARDS
═══════════════════════════════════════════════════════════════ */
.employee-form-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: 4px solid #e8a838;
    border-radius: var(--radius-lg, 14px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 6px 16px rgba(0,0,0,0.03);
    margin-bottom: 1.5rem;
}
.card-section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.1rem 1.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.card-section-badge {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #e8a838;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
}
.card-section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.card-section-subtitle {
    font-size: 0.76rem;
    color: #64748b;
    margin: 0;
}
.card-section-body {
    padding: 1.5rem;
}

.form-label-custom {
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.35rem;
    display: block;
}
.required-star {
    color: #ef4444;
    font-weight: 700;
}
.form-control-custom, .form-select-custom {
    border-color: #cbd5e1;
    font-size: 0.875rem;
    border-radius: 8px;
    color: #1e293b;
    background-color: #fff;
    padding: 0.55rem 0.85rem;
    transition: all 0.15s ease-in-out;
}
.form-control-custom:focus, .form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 3px rgba(232, 168, 56, 0.18);
    outline: none;
}

/* ═══════════════════════════════════════════════════════════════
   STATUS RADIO SELECTOR CARDS
═══════════════════════════════════════════════════════════════ */
.status-radio-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.85rem;
}
@media (max-width: 768px) {
    .status-radio-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
.status-option-card {
    position: relative;
    cursor: pointer;
    margin-bottom: 0;
}
.status-option-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.status-option-box {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 1rem 0.75rem;
    text-align: center;
    transition: all 0.2s ease;
    background: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.4rem;
}
.status-option-card:hover .status-option-box {
    border-color: #cbd5e1;
    background: #f8fafc;
    transform: translateY(-2px);
}
/* Present */
.status-option-card input[value="Present"]:checked + .status-option-box {
    border-color: #10b981;
    background: #ecfdf5;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}
.status-option-card input[value="Present"]:checked + .status-option-box .status-icon {
    color: #10b981;
}
.status-option-card input[value="Present"]:checked + .status-option-box .status-title {
    color: #065f46;
}

/* Late */
.status-option-card input[value="Late"]:checked + .status-option-box {
    border-color: #f59e0b;
    background: #fffbeb;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
}
.status-option-card input[value="Late"]:checked + .status-option-box .status-icon {
    color: #f59e0b;
}
.status-option-card input[value="Late"]:checked + .status-option-box .status-title {
    color: #92400e;
}

/* Half Day */
.status-option-card input[value="Half Day"]:checked + .status-option-box {
    border-color: #6366f1;
    background: #eef2ff;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
}
.status-option-card input[value="Half Day"]:checked + .status-option-box .status-icon {
    color: #6366f1;
}
.status-option-card input[value="Half Day"]:checked + .status-option-box .status-title {
    color: #3730a3;
}

/* Absent */
.status-option-card input[value="Absent"]:checked + .status-option-box {
    border-color: #ef4444;
    background: #fef2f2;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}
.status-option-card input[value="Absent"]:checked + .status-option-box .status-icon {
    color: #ef4444;
}
.status-option-card input[value="Absent"]:checked + .status-option-box .status-title {
    color: #991b1b;
}

.status-icon {
    font-size: 1.5rem;
    color: #94a3b8;
    transition: color 0.15s;
}
.status-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #334155;
}
.status-desc {
    font-size: 0.72rem;
    color: #64748b;
}

/* ═══════════════════════════════════════════════════════════════
   PRESETS & TAGS
═══════════════════════════════════════════════════════════════ */
.preset-btn {
    font-size: 0.75rem;
    padding: 0.3rem 0.65rem;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}
.preset-btn:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.duration-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
}
.duration-badge {
    background: #dbeafe;
    color: #1e40af;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 9999px;
}

/* ═══════════════════════════════════════════════════════════════
   STICKY PREVIEW CARD
═══════════════════════════════════════════════════════════════ */
.sticky-preview {
    position: sticky;
    top: 1rem;
}
.preview-attendance-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: var(--radius-lg, 14px);
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}
.preview-card-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 1.25rem 1rem 2.75rem;
    text-align: center;
    color: #fff;
    position: relative;
}
.preview-avatar {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: #fef3c7;
    color: #b45309;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    font-weight: 700;
    border: 3px solid #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    margin-top: -34px;
    position: relative;
    z-index: 2;
}
.preview-card-body {
    padding: 0.75rem 1.25rem 1.25rem;
    text-align: center;
}
.preview-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.2rem;
}
.preview-meta {
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 1rem;
}
.preview-detail-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.45rem 0;
    border-bottom: 1px dashed #e2e8f0;
    font-size: 0.8rem;
}
.preview-detail-row:last-child {
    border-bottom: none;
}
.preview-detail-label {
    color: #64748b;
}
.preview-detail-val {
    font-weight: 600;
    color: #1e293b;
    text-align: right;
}

.form-action-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    border-radius: 0 0 var(--radius-lg, 14px) var(--radius-lg, 14px);
}
</style>
@endpush

@section('content')

@php
    $empName = $staff_attendance->employee?->user?->name ?? 'បុគ្គលិក';
    $empDept = $staff_attendance->employee?->employee_department?->name ?? 'ទូទៅ';
    $empPos  = $staff_attendance->employee?->employee_position?->name ?? 'បុគ្គលិក';
    $initials = mb_substr($empName, 0, 2);
    $inTime = $staff_attendance->in_time ? \Carbon\Carbon::parse($staff_attendance->in_time)->format('H:i') : '';
    $outTime = $staff_attendance->out_time ? \Carbon\Carbon::parse($staff_attendance->out_time)->format('H:i') : '';
@endphp

{{-- HERO BANNER --}}
<div class="employee-banner">
    <div class="banner-actions">
        <a href="{{ route('staff-attendance.show', $staff_attendance) }}" class="btn btn-sm text-white" style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3); backdrop-filter:blur(4px);">
            <i class="bi bi-eye me-1"></i>មើលលម្អិត
        </a>
        <a href="{{ route('staff-attendance.index', ['date' => $staff_attendance->attendance_date->format('Y-m-d')]) }}" class="btn btn-sm text-white" style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3); backdrop-filter:blur(4px);">
            <i class="bi bi-arrow-left me-1"></i>បញ្ជីវត្តមាន
        </a>
    </div>
    <div class="banner-icon"><i class="bi bi-pencil-square"></i></div>
    <h1>កែប្រែវត្តមានបុគ្គលិក</h1>
    <p>កែប្រែកំណត់ត្រាវត្តមានសម្រាប់ <strong>{{ $empName }}</strong> នៅថ្ងៃ {{ $staff_attendance->attendance_date->format('d/m/Y') }}</p>
</div>

{{-- VALIDATION / ERROR ALERTS --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-octagon-fill text-danger fs-5 mt-1"></i>
            <div>
                <strong class="d-block mb-1">សូមពិនិត្យកែកំហុសខាងក្រោម៖</strong>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('staff-attendance.update', $staff_attendance) }}" method="POST" id="attendanceForm" novalidate>
@csrf
@method('PUT')

<div class="row g-4 align-items-start">
    {{-- LEFT COLUMN: FORM FIELDS --}}
    <div class="col-lg-8">
        <div class="employee-form-card">

            {{-- SECTION 1: Staff Member & Date --}}
            <div class="card-section-header">
                <div class="card-section-badge">1</div>
                <div>
                    <h2 class="card-section-title">ព័ត៌មានបុគ្គលិក & កាលបរិច្ឆេទ</h2>
                    <p class="card-section-subtitle">បុគ្គលិក និងកាលបរិច្ឆេទនៃកំណត់ត្រាវត្តមាន</p>
                </div>
            </div>

            <div class="card-section-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label for="employee_id" class="form-label-custom">
                            បុគ្គលិក <span class="required-star">*</span>
                        </label>
                        <select name="employee_id" id="employee_id" 
                                class="form-select form-select-custom @error('employee_id') is-invalid @enderror" 
                                required onchange="onEmployeeChange()">
                            @foreach($employees as $emp)
                                @php
                                    $eName = $emp->user->name ?? 'Staff';
                                    $eDept = $emp->employee_department->name ?? 'ទូទៅ';
                                    $ePos  = $emp->employee_position->name ?? 'បុគ្គលិក';
                                    $eInit = mb_substr($eName, 0, 2);
                                @endphp
                                <option value="{{ $emp->id }}" 
                                        data-name="{{ $eName }}"
                                        data-code="{{ $emp->employee_code }}"
                                        data-dept="{{ $eDept }}"
                                        data-pos="{{ $ePos }}"
                                        data-initials="{{ $eInit }}"
                                        {{ old('employee_id', $staff_attendance->employee_id) == $emp->id ? 'selected' : '' }}>
                                    {{ $eName }} ({{ $emp->employee_code }}) — {{ $ePos }} ({{ $eDept }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="attendance_date" class="form-label-custom">
                            កាលបរិច្ឆេទ <span class="required-star">*</span>
                        </label>
                        <input type="date" name="attendance_date" id="attendance_date"
                               class="form-control form-control-custom @error('attendance_date') is-invalid @enderror"
                               value="{{ old('attendance_date', $staff_attendance->attendance_date->format('Y-m-d')) }}" required onchange="onDateChange()">
                        @error('attendance_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Attendance Status --}}
            <div class="card-section-header">
                <div class="card-section-badge">2</div>
                <div>
                    <h2 class="card-section-title">ស្ថានភាពវត្តមាន</h2>
                    <p class="card-section-subtitle">កែប្រែស្ថានភាពវត្តមានប្រចាំថ្ងៃរបស់បុគ្គលិក</p>
                </div>
            </div>

            <div class="card-section-body">
                <div class="status-radio-grid mb-2">
                    {{-- Present --}}
                    <label class="status-option-card">
                        <input type="radio" name="status" value="Present" 
                               {{ old('status', $staff_attendance->status) == 'Present' ? 'checked' : '' }} 
                               onchange="onStatusChange('Present')">
                        <div class="status-option-box">
                            <i class="bi bi-check-circle-fill status-icon"></i>
                            <span class="status-title">មានវត្តមាន</span>
                            <span class="status-desc">ពេញមួយថ្ងៃ</span>
                        </div>
                    </label>

                    {{-- Late --}}
                    <label class="status-option-card">
                        <input type="radio" name="status" value="Late" 
                               {{ old('status', $staff_attendance->status) == 'Late' ? 'checked' : '' }} 
                               onchange="onStatusChange('Late')">
                        <div class="status-option-box">
                            <i class="bi bi-clock-fill status-icon"></i>
                            <span class="status-title">យឺត</span>
                            <span class="status-desc">មកយឺតពេល</span>
                        </div>
                    </label>

                    {{-- Half Day --}}
                    <label class="status-option-card">
                        <input type="radio" name="status" value="Half Day" 
                               {{ old('status', $staff_attendance->status) == 'Half Day' ? 'checked' : '' }} 
                               onchange="onStatusChange('Half Day')">
                        <div class="status-option-box">
                            <i class="bi bi-brightness-alt-high-fill status-icon"></i>
                            <span class="status-title">កន្លះថ្ងៃ</span>
                            <span class="status-desc">បម្រើការកន្លះថ្ងៃ</span>
                        </div>
                    </label>

                    {{-- Absent --}}
                    <label class="status-option-card">
                        <input type="radio" name="status" value="Absent" 
                               {{ old('status', $staff_attendance->status) == 'Absent' ? 'checked' : '' }} 
                               onchange="onStatusChange('Absent')">
                        <div class="status-option-box">
                            <i class="bi bi-x-circle-fill status-icon"></i>
                            <span class="status-title">អវត្តមាន</span>
                            <span class="status-desc">គ្មានវត្តមាន</span>
                        </div>
                    </label>
                </div>
                @error('status')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            {{-- SECTION 3: Time Tracking --}}
            <div class="card-section-header">
                <div class="card-section-badge">3</div>
                <div>
                    <h2 class="card-section-title">ម៉ោងបម្រើការងារ (ចូល-ចេញ)</h2>
                    <p class="card-section-subtitle">កំណត់ត្រាម៉ោងចូល និងម៉ោងចេញជាក់ស្ដែង</p>
                </div>
            </div>

            <div class="card-section-body">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="text-muted small fw-medium" style="font-size:0.75rem;">កំណត់រហ័ស:</span>
                    <button type="button" class="preset-btn" onclick="applyStandardShift()">
                        <i class="bi bi-briefcase me-1"></i>ស្តង់ដារ (08:00 – 17:00)
                    </button>
                    <button type="button" class="preset-btn" onclick="applyMorningHalfDay()">
                        <i class="bi bi-brightness-high me-1"></i>ពេលព្រឹក (08:00 – 12:00)
                    </button>
                    <button type="button" class="preset-btn" onclick="setInNow()">
                        <i class="bi bi-box-arrow-in-right me-1"></i>ចូល: ឥឡូវនេះ
                    </button>
                    <button type="button" class="preset-btn" onclick="setOutNow()">
                        <i class="bi bi-box-arrow-right me-1"></i>ចេញ: ឥឡូវនេះ
                    </button>
                    <button type="button" class="preset-btn text-danger" onclick="clearTimes()">
                        <i class="bi bi-trash3 me-1"></i>សម្អាត
                    </button>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label for="in_time" class="form-label-custom">
                            ម៉ោងចូល (Check-in)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success border-end-0">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </span>
                            <input type="time" name="in_time" id="in_time"
                                   class="form-control form-control-custom @error('in_time') is-invalid @enderror"
                                   value="{{ old('in_time', $inTime) }}" onchange="calculateShiftDuration()">
                            @error('in_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="out_time" class="form-label-custom">
                            ម៉ោងចេញ (Check-out)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-danger border-end-0">
                                <i class="bi bi-box-arrow-right"></i>
                            </span>
                            <input type="time" name="out_time" id="out_time"
                                   class="form-control form-control-custom @error('out_time') is-invalid @enderror"
                                   value="{{ old('out_time', $outTime) }}" onchange="calculateShiftDuration()">
                            @error('out_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="duration-box">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-hourglass-split text-muted fs-5"></i>
                                <div>
                                    <div class="small fw-bold text-dark">រយៈពេលបម្រើការងារសរុប</div>
                                    <div class="text-muted" style="font-size:0.75rem;" id="shiftSubtitle">គណនាដោយស្វ័យប្រវត្តិតាមម៉ោងចូល និងម៉ោងចេញ</div>
                                </div>
                            </div>
                            <div class="duration-badge" id="durationBadge">—</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: Remarks --}}
            <div class="card-section-header">
                <div class="card-section-badge">4</div>
                <div>
                    <h2 class="card-section-title">កំណត់ចំណាំ & មូលហេតុបន្ថែម</h2>
                    <p class="card-section-subtitle">បន្ថែមការបញ្ជាក់ ឬកំណត់ត្រារបស់រដ្ឋបាល (ជាជម្រើស)</p>
                </div>
            </div>

            <div class="card-section-body">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="text-muted small" style="font-size:0.75rem;">ស្លាករហ័ស:</span>
                    <button type="button" class="preset-btn" onclick="addRemarkTag('បំពេញការងារធម្មតា')">បំពេញការងារធម្មតា</button>
                    <button type="button" class="preset-btn" onclick="addRemarkTag('មានធុរៈផ្ទាល់ខ្លួន')">មានធុរៈផ្ទាល់ខ្លួន</button>
                    <button type="button" class="preset-btn" onclick="addRemarkTag('មានបញ្ហាសុខភាព / ឈឺ')">មានបញ្ហាសុខភាព / ឈឺ</button>
                    <button type="button" class="preset-btn" onclick="addRemarkTag('ចុះបេសកកម្មខាងក្រៅ')">ចុះបេសកកម្មខាងក្រៅ</button>
                    <button type="button" class="preset-btn" onclick="addRemarkTag('បានអនុញ្ញាតមកយឺត')">បានអនុញ្ញាតមកយឺត</button>
                </div>
                <textarea name="remarks" id="remarks" rows="2"
                          class="form-control form-control-custom @error('remarks') is-invalid @enderror"
                          placeholder="បញ្ចូលកំណត់ចំណាំ ឬមូលហេតុលម្អិតបន្ថែមនៅទីនេះ..."
                          oninput="updatePreviewRemarks()">{{ old('remarks', $staff_attendance->remarks) }}</textarea>
                @error('remarks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Form Footer --}}
            <div class="form-action-footer">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i>លុបកំណត់ត្រានេះ
                    </button>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('staff-attendance.index', ['date' => $staff_attendance->attendance_date->format('Y-m-d')]) }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-x me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-sm text-white px-3" style="background:#e8a838; border-color:#e8a838; font-weight:600;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT COLUMN: STICKY PREVIEW CARD --}}
    <div class="col-lg-4">
        <div class="sticky-preview">
            <div class="preview-attendance-card">
                <div class="preview-card-header">
                    <div class="small text-white-50 text-uppercase fw-bold" style="font-size:0.68rem; letter-spacing:0.8px;">
                        ទិដ្ឋភាពទូទៅនៃវត្តមាន
                    </div>
                </div>

                <div class="text-center">
                    <div class="preview-avatar" id="prevAvatar">{{ $initials }}</div>
                </div>

                <div class="preview-card-body">
                    <h3 class="preview-name" id="prevName">{{ $empName }}</h3>
                    <div class="preview-meta" id="prevMeta">{{ $empPos }} • {{ $empDept }}</div>

                    <div class="mb-3">
                        <span class="badge px-3 py-2" id="prevStatusBadge" style="font-size:0.8rem; font-weight:700;">
                            {{ $staff_attendance->status }}
                        </span>
                    </div>

                    <div class="preview-detail-row">
                        <span class="preview-detail-label"><i class="bi bi-calendar-event me-1"></i>កាលបរិច្ឆេទ</span>
                        <span class="preview-detail-val" id="prevDate">{{ $staff_attendance->attendance_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="preview-detail-row">
                        <span class="preview-detail-label"><i class="bi bi-box-arrow-in-right text-success me-1"></i>ម៉ោងចូល</span>
                        <span class="preview-detail-val" id="prevIn">{{ $inTime ?: '—' }}</span>
                    </div>
                    <div class="preview-detail-row">
                        <span class="preview-detail-label"><i class="bi bi-box-arrow-right text-danger me-1"></i>ម៉ោងចេញ</span>
                        <span class="preview-detail-val" id="prevOut">{{ $outTime ?: '—' }}</span>
                    </div>
                    <div class="preview-detail-row">
                        <span class="preview-detail-label"><i class="bi bi-hourglass-split text-primary me-1"></i>រយៈពេលបម្រើការ</span>
                        <span class="preview-detail-val text-primary" id="prevDuration">—</span>
                    </div>
                    <div class="preview-detail-row">
                        <span class="preview-detail-label"><i class="bi bi-chat-left-text me-1"></i>កំណត់ចំណាំ</span>
                        <span class="preview-detail-val text-muted fst-italic" id="prevRemarks">{{ $staff_attendance->remarks ?: 'គ្មាន' }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 bg-light rounded-3 p-3 mt-3 shadow-none">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span class="fw-bold small text-dark">ព័ត៌មានប្រវត្តិ</span>
                </div>
                <div class="small text-muted" style="font-size:0.75rem;">
                    លេខសម្គាល់កំណត់ត្រា: #{{ $staff_attendance->id }}<br>
                    កាលបរិច្ឆេទបង្កើត: {{ $staff_attendance->created_at ? $staff_attendance->created_at->format('d/m/Y H:i') : '—' }}<br>
                    កែប្រែចុងក្រោយ: {{ $staff_attendance->updated_at ? $staff_attendance->updated_at->diffForHumans() : '—' }}
                </div>
            </div>
        </div>
    </div>
</div>

</form>

{{-- DELETE CONFIRMATION MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width:50px; height:50px; font-size:1.5rem;">
                    <i class="bi bi-trash"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">តើអ្នកពិតជាចង់លុបឬ?</h5>
                <p class="text-muted small mb-4">តើអ្នកពិតជាចង់លុបកំណត់ត្រាវត្តមាននេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <form action="{{ route('staff-attendance.destroy', $staff_attendance) }}" method="POST" class="d-inline m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3">យល់ព្រមលុប</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function onEmployeeChange() {
    const sel = document.getElementById('employee_id');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('prevName').textContent = opt.getAttribute('data-name');
        document.getElementById('prevMeta').textContent = `${opt.getAttribute('data-pos')} • ${opt.getAttribute('data-dept')}`;
        document.getElementById('prevAvatar').textContent = opt.getAttribute('data-initials') || 'បុ';
    }
}

function onDateChange() {
    const val = document.getElementById('attendance_date').value;
    if (val) {
        const d = new Date(val);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        document.getElementById('prevDate').textContent = `${day}/${month}/${year}`;
    }
}

function onStatusChange(status) {
    const badge = document.getElementById('prevStatusBadge');
    if (status === 'Present') {
        badge.style.background = '#ecfdf5';
        badge.style.color = '#065f46';
        badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>មានវត្តមាន';
        if (!document.getElementById('in_time').value) applyStandardShift();
    } else if (status === 'Late') {
        badge.style.background = '#fffbeb';
        badge.style.color = '#92400e';
        badge.innerHTML = '<i class="bi bi-clock-fill me-1"></i>យឺត';
    } else if (status === 'Half Day') {
        badge.style.background = '#eef2ff';
        badge.style.color = '#3730a3';
        badge.innerHTML = '<i class="bi bi-brightness-alt-high-fill me-1"></i>កន្លះថ្ងៃ';
    } else if (status === 'Absent') {
        badge.style.background = '#fef2f2';
        badge.style.color = '#991b1b';
        badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>អវត្តមាន';
        clearTimes();
    }
    calculateShiftDuration();
}

function applyStandardShift() {
    document.getElementById('in_time').value = '08:00';
    document.getElementById('out_time').value = '17:00';
    calculateShiftDuration();
}

function applyMorningHalfDay() {
    document.getElementById('in_time').value = '08:00';
    document.getElementById('out_time').value = '12:00';
    calculateShiftDuration();
}

function setInNow() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('in_time').value = `${h}:${m}`;
    calculateShiftDuration();
}

function setOutNow() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('out_time').value = `${h}:${m}`;
    calculateShiftDuration();
}

function clearTimes() {
    document.getElementById('in_time').value = '';
    document.getElementById('out_time').value = '';
    calculateShiftDuration();
}

function calculateShiftDuration() {
    const inVal = document.getElementById('in_time').value;
    const outVal = document.getElementById('out_time').value;

    document.getElementById('prevIn').textContent = inVal || '—';
    document.getElementById('prevOut').textContent = outVal || '—';

    if (!inVal || !outVal) {
        document.getElementById('durationBadge').textContent = '—';
        document.getElementById('prevDuration').textContent = '—';
        return;
    }

    const [inH, inM] = inVal.split(':').map(Number);
    const [outH, outM] = outVal.split(':').map(Number);

    const inTotal = inH * 60 + inM;
    const outTotal = outH * 60 + outM;

    if (outTotal >= inTotal) {
        const diff = outTotal - inTotal;
        const hours = Math.floor(diff / 60);
        const mins = diff % 60;
        const durText = `${hours} ម៉ោង ${mins} នាទី`;
        document.getElementById('durationBadge').textContent = durText;
        document.getElementById('prevDuration').textContent = durText;
        document.getElementById('shiftSubtitle').textContent = `រយៈពេលបម្រើការងារសរុប៖ ${hours} ម៉ោង ${mins} នាទី`;
    } else {
        document.getElementById('durationBadge').textContent = 'ម៉ោងមិនត្រឹមត្រូវ';
        document.getElementById('prevDuration').textContent = 'មិនត្រឹមត្រូវ';
        document.getElementById('shiftSubtitle').textContent = 'ម៉ោងចេញត្រូវតែធំជាងម៉ោងចូល';
    }
}

function addRemarkTag(tag) {
    const textarea = document.getElementById('remarks');
    if (textarea.value) {
        textarea.value += ' ' + tag;
    } else {
        textarea.value = tag;
    }
    updatePreviewRemarks();
}

function updatePreviewRemarks() {
    const val = document.getElementById('remarks').value;
    document.getElementById('prevRemarks').textContent = val ? val : 'គ្មាន';
}

document.addEventListener('DOMContentLoaded', function() {
    const currentStatus = "{{ old('status', $staff_attendance->status) }}";
    onStatusChange(currentStatus);
    calculateShiftDuration();
});
</script>
@endpush

@endsection
