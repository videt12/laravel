@extends('layouts.app')

@section('title', 'គ្រប់គ្រងការឈប់រៀន — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការចុះឈ្មោះ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការឈប់រៀន</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   ERP COLOR PALETTE & VARIABLES
═══════════════════════════════════════════════════════════════════ */
:root {
    --gold-primary: #D4AF37;
    --gold-hover: #C49E2B;
    --gold-light: #FBF7EB;
    --gold-border: #F1E3B8;
    --navy-text: #172033;
    --secondary-text: #64748B;
    --border-color: #E5E7EB;
    --card-bg: #FFFFFF;
}

/* ═══════════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════════ */
.page-header-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.75rem;
}
.page-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--navy-text);
    margin-bottom: 0.1rem;
    display: flex;
    align-items: center;
    gap: 0.45rem;
}
.page-title-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    background: var(--gold-light);
    color: var(--gold-primary);
    border: 1px solid var(--gold-border);
    border-radius: 6px;
    font-size: 0.95rem;
    flex-shrink: 0;
}
.page-subtitle {
    color: var(--secondary-text);
    font-size: 0.78rem;
    margin-bottom: 0;
}

/* Primary Action Button (School ERP Gold) */
.btn-gold {
    background: var(--gold-primary);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 0.28rem 0.75rem;
    border-radius: 6px;
    border: 1px solid var(--gold-hover);
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(212, 175, 55, 0.2);
    text-decoration: none;
}
.btn-gold:hover {
    background: var(--gold-hover);
    color: #ffffff;
    border-color: #B89327;
    box-shadow: 0 3px 8px rgba(212, 175, 55, 0.35);
}

/* ═══════════════════════════════════════════════════════════════════
   SUMMARY STATS CARDS
═══════════════════════════════════════════════════════════════════ */
.stat-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    height: 100%;
}
.stat-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.04);
}
.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.stat-title {
    font-size: 0.90rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 0.15rem;
    line-height: 1.35;
}
.stat-value {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--navy-text);
    line-height: 1.2;
    margin-bottom: 0.15rem;
}
.stat-desc {
    font-size: 0.82rem;
    color: #64748B;
    margin: 0;
    line-height: 1.35;
}

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 0.35rem 0.65rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    margin-bottom: 0.5rem;
}
.search-wrapper {
    position: relative;
    min-width: 240px;
}
.search-wrapper i {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94A3B8;
    font-size: 0.85rem;
}
.search-wrapper input {
    width: 100%;
    padding: 0.35rem 0.75rem 0.35rem 2rem;
    font-size: 0.85rem;
    min-height: 36px;
    height: 36px;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    color: var(--navy-text);
    transition: all 0.2s;
    line-height: 1.4;
}
.search-wrapper input:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.18);
    outline: none;
}
.filter-select {
    font-size: 0.85rem;
    min-height: 36px;
    height: 36px;
    padding: 0.35rem 2rem 0.35rem 0.75rem;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    background-color: #ffffff;
    color: var(--navy-text);
    min-width: 150px;
    transition: all 0.2s;
    line-height: 1.4;
}
.filter-select:focus {
    border-color: var(--gold-primary);
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.18);
    outline: none;
}

/* ═══════════════════════════════════════════════════════════════════
   TABLE CARD
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}
.table-custom {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}
.table-custom thead th {
    background: #F8FAFC;
    color: var(--secondary-text);
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 0.4rem 0.75rem;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
    line-height: 1.4;
}
.table-custom tbody tr {
    transition: background-color 0.15s ease;
}
.table-custom tbody tr:hover {
    background-color: #F8FAFC;
}
.table-custom tbody td {
    padding: 0.35rem 0.75rem;
    font-size: 0.86rem;
    color: var(--navy-text);
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
    line-height: 1.4;
}
.table-custom tbody tr:last-child td {
    border-bottom: none;
}

/* Student Presentation */
.avatar-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #F1F5F9;
    color: #334155;
    font-weight: 700;
    font-size: 0.82rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E2E8F0;
    flex-shrink: 0;
}
.student-name {
    font-weight: 600;
    color: var(--navy-text);
    text-decoration: none;
    font-size: 0.88rem;
    line-height: 1.25;
    transition: color 0.15s;
}
.student-name:hover {
    color: var(--gold-primary);
}
.student-id-sub {
    font-size: 0.78rem;
    color: var(--secondary-text);
    font-family: var(--font-monospace, monospace);
    line-height: 1.1;
}

/* Date Presentation */
.date-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.82rem;
    color: #334155;
    line-height: 1.3;
}

/* Status Badges */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.15rem 0.55rem;
    border-radius: 9999px;
    font-size: 0.78rem;
    font-weight: 600;
    line-height: 1.3;
}
.badge-status-completed {
    background: #DCFCE7;
    color: #15803D;
    border: 1px solid #BBF7D0;
}
.badge-status-pending {
    background: #FEF3C7;
    color: #B45309;
    border: 1px solid #FDE68A;
}
.badge-status-cancelled {
    background: #F1F5F9;
    color: #475569;
    border: 1px solid #E2E8F0;
}
.badge-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}
.badge-status-completed .badge-status-dot { background: #16A34A; }
.badge-status-pending .badge-status-dot { background: #D97706; }
.badge-status-cancelled .badge-status-dot { background: #64748B; }

/* Table Action Buttons */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    color: var(--secondary-text);
    transition: all 0.15s ease;
    background: transparent;
    border: 1px solid transparent;
    text-decoration: none;
    font-size: 0.85rem;
}
.action-btn:hover {
    background: #F1F5F9;
    color: var(--navy-text);
    border-color: #E2E8F0;
}
.action-btn-danger:hover {
    background: #FEE2E2;
    color: #DC2626;
    border-color: #FECACA;
}

/* Empty State */
.empty-state-box {
    padding: 4.5rem 1.5rem;
    text-align: center;
}
.empty-state-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: var(--gold-light);
    color: var(--gold-primary);
    border: 1px solid var(--gold-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

/* Refined Alert */
.erp-alert-success {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    color: #15803D;
    border-radius: 10px;
    padding: 0.85rem 1.15rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.88rem;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════════════════ --}}
<div class="page-header-container">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-person-dash"></i></div>
            គ្រប់គ្រងការឈប់រៀន
        </h1>
        <p class="page-subtitle">តាមដាន និងកត់ត្រាការឈប់រៀនរបស់សិស្សពីសាលា។</p>
    </div>
    <div>
        <a href="{{ route('withdrawals.create') }}" class="btn-gold">
            <i class="bi bi-plus-lg"></i>
            <span>+ កត់ត្រាការឈប់រៀនថ្មី</span>
        </a>
    </div>
</div>

{{-- Success Flash Alert --}}
@if(session('success'))
    <div class="erp-alert-success mb-4">
        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size:0.75rem"></button>
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     SUMMARY STATS CARDS
═══════════════════════════════════════════════════════════════════ --}}
@if(isset($stats))
<div class="row g-3 mb-3">
    {{-- Card 1: Total Withdrawals --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="stat-title">សិស្សឈប់រៀនសរុប</div>
                <div class="stat-value">{{ number_format($stats['total'] ?? 0) }}</div>
                <div class="stat-desc">ចំនួនសិស្សឈប់រៀនសរុប</div>
            </div>
            <div class="stat-icon" style="background:#F1F5F9; color:#1E293B;">
                <i class="bi bi-clipboard2-data"></i>
            </div>
        </div>
    </div>

    {{-- Card 2: Pending --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="stat-title">រង់ចាំការពិនិត្យ</div>
                <div class="stat-value text-warning" style="color: #D97706 !important;">{{ number_format($stats['pending'] ?? 0) }}</div>
                <div class="stat-desc">កំពុងរង់ចាំការអនុម័ត</div>
            </div>
            <div class="stat-icon" style="background:#FEF3C7; color:#D97706;">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>

    {{-- Card 3: Completed / Processed --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="stat-title">បានបញ្ចប់សព្វគ្រប់</div>
                <div class="stat-value text-success" style="color: #16A34A !important;">{{ number_format($stats['completed'] ?? 0) }}</div>
                <div class="stat-desc">បានអនុម័តឈប់រៀនជាផ្លូវការ</div>
            </div>
            <div class="stat-icon" style="background:#DCFCE7; color:#16A34A;">
                <i class="bi bi-check2-circle"></i>
            </div>
        </div>
    </div>

    {{-- Card 4: This Month --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="stat-title">ក្នុងខែនេះ</div>
                <div class="stat-value" style="color: var(--gold-primary);">{{ number_format($stats['this_month'] ?? 0) }}</div>
                <div class="stat-desc">{{ now()->format('F Y') }}</div>
            </div>
            <div class="stat-icon" style="background:var(--gold-light); color:var(--gold-primary); border: 1px solid var(--gold-border);">
                <i class="bi bi-calendar3"></i>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     FILTER BAR
═══════════════════════════════════════════════════════════════════ --}}
<div class="filter-card">
    <form action="{{ route('withdrawals.index') }}" method="GET" class="m-0">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
                {{-- Search by Name / ID --}}
                <div class="search-wrapper flex-grow-1" style="max-width: 360px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះសិស្ស ឬអត្តលេខ...">
                </div>

                {{-- Filter by Status --}}
                <select name="status" class="form-select filter-select" style="width:auto" onchange="this.form.submit()">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>បានបញ្ចប់</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>រង់ចាំការពិនិត្យ</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>បានបោះបង់</option>
                </select>

                {{-- Clear Filter Action --}}
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('withdrawals.index') }}" class="btn btn-sm btn-link text-danger text-decoration-none px-2" style="font-size:0.8rem">
                        <i class="bi bi-x-circle me-1"></i>សម្អាតការស្វែងរក
                    </a>
                @endif
            </div>

            {{-- Counter info --}}
            <div class="text-muted small d-none d-md-block">
                @if($withdrawals->total() > 0)
                    បង្ហាញពី <span class="fw-semibold text-dark">{{ $withdrawals->firstItem() }}</span> ដល់ <span class="fw-semibold text-dark">{{ $withdrawals->lastItem() }}</span> នៃ <span class="fw-semibold text-dark">{{ $withdrawals->total() }}</span> កំណត់ត្រា
                @endif
            </div>
        </div>
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
                    <th style="width: 26%;">សិស្ស</th>
                    <th style="width: 22%;">ថ្នាក់ និងឆ្នាំសិក្សាចុងក្រោយ</th>
                    <th style="width: 17%;">កាលបរិច្ឆេទឈប់រៀន</th>
                    <th style="width: 21%;">មូលហេតុ</th>
                    <th style="width: 14%;">ស្ថានភាព</th>
                    <th class="text-end" style="width: 8%;">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $withdrawal)
                    @php
                        $student = $withdrawal->student;
                        $studentName = $student->user->name ?? 'មិនស្គាល់ឈ្មោះសិស្ស';
                        $admissionNo = $student->admission_number ?? 'មិនមានអត្តលេខ';
                        
                        // Extract initials
                        $nameParts = explode(' ', trim($studentName));
                        $initials = '';
                        if (count($nameParts) >= 2) {
                            $initials = strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr(end($nameParts), 0, 1));
                        } else {
                            $initials = strtoupper(mb_substr($studentName, 0, 2));
                        }

                        // Resolve Last Enrolled Class & Academic Year
                        $lastClassName = null;
                        $lastYearName = null;
                        if ($student) {
                            if ($student->schoolClass) {
                                $lastClassName = ($student->schoolClass->grade?->name ?? '') . ($student->schoolClass->section?->name ? ' — ' . $student->schoolClass->section->name : '');
                            }
                            $lastYearName = $student->academicYear?->name;

                            // Fallback to latest enrollment record
                            if (!$lastClassName && $student->enrollments && $student->enrollments->isNotEmpty()) {
                                $latestEnr = $student->enrollments->sortByDesc('enrollment_date')->first();
                                if ($latestEnr) {
                                    $enrClass = $latestEnr->enrollmentDetail?->class;
                                    if ($enrClass) {
                                        $lastClassName = ($enrClass->grade?->name ?? '') . ($enrClass->section?->name ? ' — ' . $enrClass->section->name : '');
                                    }
                                    $lastYearName = $lastYearName ?: $latestEnr->academicYear?->name;
                                }
                            }
                        }
                    @endphp
                    <tr>
                        {{-- Student --}}
                        <td>
                            @php
                                $profile = $student?->profile;
                                $khmerName = trim(($profile?->first_name_khmer ?? '') . ' ' . ($profile?->last_name_khmer ?? ''));
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">
                                    {{ $initials ?: 'ST' }}
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <a href="{{ route('withdrawals.show', $withdrawal) }}" class="student-name">
                                            {{ $studentName }}
                                        </a>
                                        @if($khmerName)
                                            <span class="fw-semibold text-primary" style="font-size:0.75rem;">({{ $khmerName }})</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-1.5 flex-wrap" style="margin-top:1px;">
                                        <span class="student-id-sub">{{ $admissionNo }}</span>
                                        @if($profile?->gender)
                                            <span class="badge bg-light text-secondary border px-1" style="font-size:0.65rem; padding-top:1px; padding-bottom:1px;">{{ $profile->gender }}</span>
                                        @endif
                                        @if($profile?->phone)
                                            <span class="text-muted" style="font-size:0.68rem;"><i class="bi bi-telephone text-secondary"></i> {{ $profile->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Last Class & Year (Quick Win) --}}
                        <td>
                            @if($lastClassName)
                                <div>
                                    <span class="badge px-1.5 py-0.5 text-dark" style="background:#F1F5F9; border:1px solid #CBD5E1; font-size:0.72rem; font-weight:600;">
                                        <i class="bi bi-mortarboard-fill text-primary me-1"></i>{{ $lastClassName }}
                                    </span>
                                </div>
                                <span class="small text-muted d-inline-flex align-items-center gap-1" style="font-size:0.7rem;">
                                    <i class="bi bi-calendar3"></i> {{ $lastYearName ?: '—' }}
                                </span>
                            @else
                                <span class="text-muted fst-italic" style="font-size:0.75rem;">មិនទាន់កំណត់</span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td>
                            <div class="date-pill">
                                <i class="bi bi-calendar-event text-secondary"></i>
                                <span>{{ $withdrawal->withdrawal_date ? \Carbon\Carbon::parse($withdrawal->withdrawal_date)->format('M d, Y') : '—' }}</span>
                            </div>
                        </td>

                        {{-- Reason --}}
                        <td>
                            @if($withdrawal->reason)
                                <span class="text-truncate d-inline-block" style="max-width: 240px;" title="{{ $withdrawal->reason }}">
                                    {{ Str::limit($withdrawal->reason, 40) }}
                                </span>
                            @else
                                <span class="text-muted fst-italic" style="font-size:0.82rem;">មិនមានមូលហេតុ</span>
                            @endif
                        </td>

                        {{-- Status Badge --}}
                        <td>
                            @php
                                $status = strtolower($withdrawal->status ?? '');
                                $statusClass = 'badge-status-pending';
                                $statusLabel = 'រង់ចាំការពិនិត្យ';
                                if (in_array($status, ['completed', 'approved'])) {
                                    $statusClass = 'badge-status-completed';
                                    $statusLabel = 'បានបញ្ចប់';
                                } elseif (in_array($status, ['cancelled', 'rejected'])) {
                                    $statusClass = 'badge-status-cancelled';
                                    $statusLabel = 'បានបោះបង់';
                                }
                            @endphp
                            <span class="badge-status {{ $statusClass }}">
                                <span class="badge-status-dot"></span>
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('withdrawals.show', $withdrawal) }}" class="action-btn" data-bs-toggle="tooltip" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('withdrawals.edit', $withdrawal) }}" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួល">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="action-btn action-btn-danger" 
                                        data-bs-toggle="tooltip" title="លុប"
                                        onclick="confirmDelete({{ $withdrawal->id }}, '{{ addslashes($studentName) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state-box">
                                <div class="empty-state-icon">
                                    <i class="bi bi-person-dash"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">រកមិនឃើញកំណត់ត្រាឈប់រៀនទេ</h6>
                                <p class="text-muted small mb-3" style="max-width: 360px; margin-inline: auto;">
                                    @if(request()->anyFilled(['search', 'status']))
                                        មិនមានទិន្នន័យសិស្សឈប់រៀនត្រូវនឹងការស្វែងរករបស់អ្នកទេ។ សូមព្យាយាមសម្អាតការស្វែងរក។
                                    @else
                                        បច្ចុប្បន្នមិនទាន់មានកំណត់ត្រាសិស្សឈប់រៀននៅក្នុងប្រព័ន្ធនៅឡើយទេ។
                                    @endif
                                </p>
                                @if(request()->anyFilled(['search', 'status']))
                                    <a href="{{ route('withdrawals.index') }}" class="btn btn-sm btn-outline-secondary px-3">
                                        <i class="bi bi-x-circle me-1"></i>សម្អាតការស្វែងរក
                                    </a>
                                @else
                                    <a href="{{ route('withdrawals.create') }}" class="btn-gold btn-sm d-inline-flex">
                                        <i class="bi bi-plus-lg"></i>
                                        <span>+ កត់ត្រាការឈប់រៀនដំបូង</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Footer --}}
    @if($withdrawals->hasPages())
        <div class="p-3 border-top bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small">
                បង្ហាញពី {{ $withdrawals->firstItem() }} ដល់ {{ $withdrawals->lastItem() }} នៃ {{ $withdrawals->total() }} កំណត់ត្រា
            </div>
            <div>
                {{ $withdrawals->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px; overflow:hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width:54px; height:54px; background:#FEE2E2; color:#DC2626;">
                        <i class="bi bi-trash3 fs-4"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1 text-dark">លុបកំណត់ត្រាឈប់រៀន?</h6>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបកំណត់ត្រាឈប់រៀនរបស់សិស្ស <strong id="deleteStudentName" class="text-dark"></strong> នេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-3" style="background:#DC2626; border-color:#DC2626;">យល់ព្រមលុប</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    });

    function confirmDelete(id, name) {
        document.getElementById('deleteStudentName').textContent = name;
        document.getElementById('deleteForm').action = `/withdrawals/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endsection
