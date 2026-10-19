@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតវត្តមានបុគ្គលិក — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item"><a href="{{ route('staff-attendance.index') }}" class="text-decoration-none text-muted">វត្តមានបុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ព័ត៌មានលម្អិតបុគ្គលិក</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* Staff Detail Card */
.staff-profile-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.75rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    margin-bottom: 1.5rem;
}
.staff-profile-avatar {
    width: 84px;
    height: 84px;
    border-radius: 50%;
    background: #f1f5f9;
    border: 3px solid #f8fafc;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: #475569;
    overflow: hidden;
    flex-shrink: 0;
}
.badge-active-status {
    background: #dcfce7;
    color: #15803d;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
    display: inline-block;
}

/* Monthly Analytics Card */
.month-analytics-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 1.5rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    margin-bottom: 1.5rem;
}
.counter-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.75rem 0.5rem;
    text-align: center;
    min-width: 65px;
}
.counter-box-present { background: #f0fdf4; border-color: #bbf7d0; }
.counter-box-late    { background: #fffbeb; border-color: #fde68a; }
.counter-box-absent  { background: #fef2f2; border-color: #fecaca; }
.counter-box-leave   { background: #eff6ff; border-color: #bfdbfe; }

.counter-title {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.15rem;
}
.counter-val {
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1;
}

/* Circular Gauge */
.gauge-wrapper {
    position: relative;
    width: 96px;
    height: 96px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
.gauge-wrapper svg {
    transform: rotate(-90deg);
}
.gauge-pct-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    line-height: 1;
}

/* Daily Table */
.daily-table-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}
.status-pill-clean {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
}
.pill-clean-present { background: #dcfce7; color: #15803d; }
.pill-clean-late    { background: #fef3c7; color: #b45309; }
.pill-clean-absent  { background: #fee2e2; color: #b91c1c; }
.pill-clean-leave   { background: #e0e7ff; color: #4338ca; }
</style>
@endpush

@section('content')

@php
    $empName = $employee->user?->name ?? 'បុគ្គលិក';
    $empCode = $employee->employee_code ?? '—';
    $empPos  = $employee->employee_position?->name ?? 'គ្រូបង្រៀន';
    $empDept = $employee->employee_department?->name ?? 'ដេប៉ាតឺម៉ង់បង្រៀន';
    $empEmail = $employee->user?->email ?? 'staff@school.edu.kh';
    $empStatus = $employee->employee_status?->khmer_name ?? 'សកម្ម';
    $initials = strtoupper(substr($empName, 0, 2));

    $circumference = 238.76;
    $dashoffset = $circumference - ($circumference * min(100, max(0, $attendanceRate)) / 100);

    $khmerMonths = [
        'January' => 'មករា', 'February' => 'កុម្ភៈ', 'March' => 'មីនា', 'April' => 'មេសា',
        'May' => 'ឧសភា', 'June' => 'មិថុនា', 'July' => 'កក្កដា', 'August' => 'សីហា',
        'September' => 'កញ្ញា', 'October' => 'តុលា', 'November' => 'វិច្ឆិកា', 'December' => 'ធ្នូ'
    ];
    $monthCarbon = \Carbon\Carbon::parse($month.'-01');
    $monthKh = ($khmerMonths[$monthCarbon->format('F')] ?? $monthCarbon->format('F')) . ' ' . $monthCarbon->format('Y');
@endphp

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-bold text-dark mb-1" style="font-size: 1.45rem;">ព័ត៌មានលម្អិតវត្តមានបុគ្គលិក</h2>
        <p class="text-muted small mb-0">កំណត់ត្រាវត្តមានប្រចាំខែ និងម៉ោងបំពេញការងារលម្អិត។</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staff-attendance.index') }}" class="btn btn-light border fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅបញ្ជីវត្តមាន
        </a>
        <a href="{{ route('staff-attendance.create', ['employee_id' => $employee->id]) }}" class="btn btn-warning text-dark fw-bold">
            <i class="bi bi-plus-circle me-1"></i>កត់ត្រាវត្តមាន
        </a>
    </div>
</div>

{{-- Staff Profile Card --}}
<div class="staff-profile-card">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="staff-profile-avatar">
                @if($employee->photo_url)
                    <img src="{{ $employee->photo_url }}" alt="{{ $empName }}" class="w-100 h-100 object-fit-cover">
                @else
                    <span>{{ $initials }}</span>
                @endif
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h4 class="fw-bold text-dark mb-0">{{ $empName }}</h4>
                    <span class="badge-active-status">{{ $empStatus }}</span>
                </div>
                <div class="text-muted small mb-1">
                    <strong>អត្តលេខ៖</strong> <span class="font-monospace text-dark fw-semibold">{{ $empCode }}</span>
                </div>
                <div class="d-flex align-items-center gap-3 text-muted small">
                    <span><i class="bi bi-person-workspace me-1"></i>{{ $empPos }} ({{ $empDept }})</span>
                    <span><i class="bi bi-envelope me-1"></i>{{ $empEmail }}</span>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-person-badge me-1"></i>មើលប្រវត្តិរូបពេញលេញ
            </a>
        </div>
    </div>
</div>

{{-- Monthly Stats & Attendance Rate --}}
<div class="month-analytics-card">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar3 text-warning fs-5"></i>
            <h5 class="fw-bold text-dark mb-0">ខែ{{ $monthKh }}</h5>
        </div>
        <form action="{{ route('staff-attendance.show', $staff_attendance) }}" method="GET" class="d-flex align-items-center gap-2">
            <label class="small text-muted mb-0">ប្តូរខែ៖</label>
            <input type="month" name="month" class="form-control form-control-sm" value="{{ $month }}" onchange="this.form.submit()" style="width: 150px;">
        </form>
    </div>

    <div class="row align-items-center g-4">
        {{-- Counter chips --}}
        <div class="col-md-8">
            <div class="row g-2">
                <div class="col-3">
                    <div class="counter-box counter-box-present">
                        <div class="counter-title text-success">វត្តមាន</div>
                        <div class="counter-val text-success">{{ $presentCount }}</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="counter-box counter-box-late">
                        <div class="counter-title text-warning">មកយឺត</div>
                        <div class="counter-val text-warning">{{ $lateCount }}</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="counter-box counter-box-absent">
                        <div class="counter-title text-danger">អវត្តមាន</div>
                        <div class="counter-val text-danger">{{ $absentCount }}</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="counter-box counter-box-leave">
                        <div class="counter-title text-primary">ច្បាប់</div>
                        <div class="counter-val text-primary">{{ $leaveCount }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Circular Gauge for Attendance Rate --}}
        <div class="col-md-4 d-flex justify-content-md-end justify-content-center">
            <div class="d-flex align-items-center gap-3">
                <div class="gauge-wrapper">
                    <svg width="96" height="96" viewBox="0 0 96 96">
                        <circle cx="48" cy="48" r="38" fill="none" stroke="#e2e8f0" stroke-width="8" />
                        <circle cx="48" cy="48" r="38" fill="none" stroke="#10b981" stroke-width="8"
                                stroke-dasharray="238.76" stroke-dashoffset="{{ $dashoffset }}"
                                stroke-linecap="round" />
                    </svg>
                    <div class="gauge-pct-text">
                        <span class="fw-bold text-dark fs-5">{{ $attendanceRate }}%</span>
                    </div>
                </div>
                <div>
                    <div class="fw-bold text-dark" style="font-size:0.9rem">អត្រាវត្តមាន</div>
                    <small class="text-muted">សមាមាត្រប្រចាំខែ</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Daily Attendance Table --}}
<div class="daily-table-card">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-bold text-dark mb-0">វត្តមានប្រចាំថ្ងៃ (Daily Attendance)</h6>
        <a href="{{ route('staff-attendance.index', ['tab' => 'monthly', 'employee_id' => $employee->id]) }}" class="small fw-semibold text-decoration-none text-primary">
            មើលទាំងអស់ <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>ម៉ោងចូល</th>
                    <th>ម៉ោងចេញ</th>
                    <th>រយៈពេលធ្វើការ</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthRecords as $log)
                    @php
                        $pill = match(strtolower($log->status)) {
                            'present' => 'pill-clean-present',
                            'late'    => 'pill-clean-late',
                            'absent'  => 'pill-clean-absent',
                            default   => 'pill-clean-leave',
                        };

                        $sKh = match(strtolower($log->status)) {
                            'present' => 'វត្តមាន',
                            'late'    => 'មកយឺត',
                            'absent'  => 'អវត្តមាន',
                            'leave', 'on leave' => 'ច្បាប់',
                            default   => $log->status,
                        };

                        $dur = '—';
                        if ($log->in_time && $log->out_time) {
                            $dur = \Carbon\Carbon::parse($log->in_time)->diff(\Carbon\Carbon::parse($log->out_time))->format('%hh %im');
                        }
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $log->attendance_date->format('d/m/Y') }}</td>
                        <td class="font-monospace">{{ $log->in_time ? \Carbon\Carbon::parse($log->in_time)->format('H:i') : '—' }}</td>
                        <td class="font-monospace">{{ $log->out_time ? \Carbon\Carbon::parse($log->out_time)->format('H:i') : '—' }}</td>
                        <td class="font-monospace text-muted small">{{ $dur }}</td>
                        <td><span class="status-pill-clean {{ $pill }}">{{ $sKh }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('staff-attendance.edit', $log) }}" class="btn btn-sm btn-light border py-0 px-2" title="កែប្រែ">
                                <i class="bi bi-pencil" style="font-size:0.75rem"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            មិនមានកំណត់ត្រាវត្តមានសម្រាប់បុគ្គលិកនេះក្នុងខែនេះទេ។
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
