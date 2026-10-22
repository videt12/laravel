@extends('layouts.app')

@section('title', 'បញ្ជីបុគ្គលិក — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បុគ្គលិក</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
    /* Uniform Table & Badges sizing - Compact (Matching Student Table) */
    .data-table thead th {
        font-size: 0.82rem !important;
        padding: 0.55rem 0.75rem !important;
        white-space: nowrap;
        background-color: #fafafa !important;
        color: #64748b !important;
        font-weight: 600 !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .data-table tbody td {
        font-size: 0.82rem !important;
        padding: 0.45rem 0.75rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }
    .data-table .adm-badge {
        font-size: 0.78rem !important;
        padding: 0.15rem 0.45rem !important;
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════
     PAGE HEADER (CLEAN SHADCN/UI STYLE)
═══════════════════════════════════════════ --}}
<div class="page-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 text-muted small mb-1" style="font-size: 0.82rem;">
            <i class="bi bi-calendar3 text-muted"></i>
            <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
        <h1 class="page-title fw-bold text-dark mb-1" style="font-size: 1.45rem;">បញ្ជីបុគ្គលិក</h1>
        <p class="page-subtitle text-muted small mb-0">បញ្ជីរាយនាមបុគ្គលិកបង្រៀន រដ្ឋបាល និងបុគ្គលិកជំនួយការទាំងអស់</p>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center">
        <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary-action px-3" style="background-color: #F59E0B; border-color: #F59E0B; color: #ffffff; font-weight: 600; border-radius: 8px; height: 38px; display: inline-flex; align-items: center;">
            <i class="bi bi-plus-lg me-1"></i>បន្ថែមបុគ្គលិក
        </a>
    </div>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-none border" role="alert" style="border-radius: 8px; border-color: #bbf7d0 !important;">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div class="flex-grow-1 text-dark" style="font-size: 0.88rem;">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-none border" role="alert" style="border-radius: 8px; border-color: #fecaca !important;">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <div class="flex-grow-1 text-dark" style="font-size: 0.88rem;">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ═══════════════════════════════════════════
     METRIC SUMMARY CARDS (CLEAN SHADCN/UI STYLE)
═══════════════════════════════════════════ --}}
<div class="row g-2.5 mb-3">
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 bg-white py-2.5 px-3" style="border: 1px solid #e2e8f0 !important; border-radius: 10px !important; box-shadow: none !important;">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-medium text-truncate" style="font-size: 0.78rem; color: #64748b;">បុគ្គលិកសរុប</span>
                <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 6px; background-color: #eff6ff; color: #2563eb; flex-shrink: 0;">
                    <i class="bi bi-person-badge" style="font-size: 0.78rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0.5" style="font-size: 1.35rem; line-height: 1.15; color: #0f172a;">{{ number_format($stats['total'] ?? $employees->total()) }}</h3>
            <div class="d-flex align-items-center gap-1 text-truncate" style="font-size: 0.72rem; color: #64748b;">
                <span class="text-dark fw-medium">សកម្ម: {{ number_format($stats['active'] ?? 0) }}</span>
                <span>·</span>
                <span>អសកម្ម: {{ number_format(max(0, ($stats['total'] ?? 0) - ($stats['active'] ?? 0))) }}</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 bg-white py-2.5 px-3" style="border: 1px solid #e2e8f0 !important; border-radius: 10px !important; box-shadow: none !important;">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-medium text-truncate" style="font-size: 0.78rem; color: #64748b;">បុគ្គលិកសកម្ម</span>
                <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 6px; background-color: #f0fdf4; color: #16a34a; flex-shrink: 0;">
                    <i class="bi bi-check2-circle" style="font-size: 0.78rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0.5" style="font-size: 1.35rem; line-height: 1.15; color: #16a34a;">{{ number_format($stats['active'] ?? 0) }}</h3>
            <div class="text-truncate" style="font-size: 0.72rem; color: #64748b;">កំពុងបំពេញការងារជាក់ស្តែង</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 bg-white py-2.5 px-3" style="border: 1px solid #e2e8f0 !important; border-radius: 10px !important; box-shadow: none !important;">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-medium text-truncate" style="font-size: 0.78rem; color: #64748b;">ផ្នែក / ដេប៉ាតឺម៉ង់</span>
                <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 6px; background-color: #fffbeb; color: #d97706; flex-shrink: 0;">
                    <i class="bi bi-building" style="font-size: 0.78rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0.5" style="font-size: 1.35rem; line-height: 1.15; color: #0f172a;">{{ number_format($stats['departments'] ?? $departments->count()) }}</h3>
            <div class="text-truncate" style="font-size: 0.72rem; color: #64748b;">ផ្នែកនិងការិយាល័យការងារ</div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card h-100 bg-white py-2.5 px-3" style="border: 1px solid #e2e8f0 !important; border-radius: 10px !important; box-shadow: none !important;">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-medium text-truncate" style="font-size: 0.78rem; color: #64748b;">មុខតំណែង</span>
                <div class="d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 6px; background-color: #faf5ff; color: #9333ea; flex-shrink: 0;">
                    <i class="bi bi-briefcase" style="font-size: 0.78rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-0.5" style="font-size: 1.35rem; line-height: 1.15; color: #0f172a;">{{ number_format($stats['positions'] ?? 0) }}</h3>
            <div class="text-truncate" style="font-size: 0.72rem; color: #64748b;">តួនាទីនិងការទទួលខុសត្រូវ</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     DATA TABLE CARD (COMPACT LIKE STUDENT TABLE)
═══════════════════════════════════════════ --}}
<div class="card bg-white" style="border: 1px solid #e2e8f0 !important; border-radius: 12px !important; overflow: hidden; box-shadow: none !important;">

    {{-- Card Header: Title & Total Count --}}
    <div class="p-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2 border-bottom" style="border-color: #e2e8f0 !important;">
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.12rem;">បញ្ជីបុគ្គលិកទាំងអស់</h5>
            <div class="text-muted small" style="font-size: 0.82rem;">បង្ហាញបុគ្គលិកចំនួន {{ number_format($employees->total()) }} នាក់ ក្នុងប្រព័ន្ធទិន្នន័យ</div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="p-3 px-4 bg-light bg-opacity-50 border-bottom" style="border-color: #e2e8f0 !important;">
        <form action="{{ route('employees.index') }}" method="GET" id="employeeFilterForm">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                {{-- Search with magnifying glass --}}
                <div class="position-relative" style="width: 320px; max-width: 100%;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 0.82rem;"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control form-control-sm ps-5 pe-3"
                           style="border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; height: 38px;"
                           placeholder="ស្វែងរកតាមឈ្មោះ ឬលេខកូដ..."
                           autocomplete="off">
                </div>

                {{-- Right controls: Department dropdown + Status dropdown + Search button --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <select name="department_id" class="form-select form-select-sm" style="border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff; width: auto; min-width: 140px; height: 38px;" onchange="document.getElementById('employeeFilterForm').submit()">
                        <option value="">គ្រប់ផ្នែកទាំងអស់</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status_id" class="form-select form-select-sm" style="border: 1px solid #e2e8f0; border-radius: 8px; background-color: #ffffff; width: auto; min-width: 130px; height: 38px;" onchange="document.getElementById('employeeFilterForm').submit()">
                        <option value="">ស្ថានភាពទាំងអស់</option>
                        @foreach($statuses as $stat)
                            <option value="{{ $stat->id }}" {{ request('status_id') == $stat->id ? 'selected' : '' }}>
                                {{ $stat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary-action px-3" style="background-color: #F59E0B; border-color: #F59E0B; color: #ffffff; font-weight: 600; border-radius: 8px; height: 38px;">
                        ស្វែងរក
                    </button>

                    @if(request()->anyFilled(['search', 'department_id', 'status_id']))
                        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center" style="border-radius: 8px; height: 38px; width: 38px;" title="សម្អាតតម្រងទាំងអស់">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    @if($employees->isEmpty())
        <div class="py-5 text-center">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-people" style="opacity: 0.65;"></i></div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">
                @if(request()->anyFilled(['search', 'department_id', 'status_id']))
                    រកមិនឃើញបុគ្គលិកត្រូវនឹងលក្ខខណ្ឌស្វែងរកឡើយ
                @else
                    មិនទាន់មានបុគ្គលិកចុះឈ្មោះនៅឡើយទេ
                @endif
            </h6>
            <p class="text-muted small mb-3">
                @if(request()->anyFilled(['search', 'department_id', 'status_id']))
                    សូមសាកល្បងផ្លាស់ប្តូរ ឬសម្អាតប៉ារ៉ាម៉ែត្រស្វែងរក។
                @else
                    ចាប់ផ្តើមដោយបន្ថែមបុគ្គលិកដំបូងរបស់អ្នកចូលទៅក្នុងប្រព័ន្ធ។
                @endif
            </p>
            @if(request()->anyFilled(['search', 'department_id', 'status_id']))
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">សម្អាតការស្វែងរកទាំងអស់</a>
            @else
                <a href="{{ route('employees.create') }}" class="btn btn-sm text-white" style="background-color: #F59E0B; border-radius: 8px;">
                    <i class="bi bi-plus-lg me-1"></i>បន្ថែមបុគ្គលិកដំបូង
                </a>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table align-middle w-100" style="border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ឈ្មោះបុគ្គលិក</th>
                        <th>លេខសម្គាល់</th>
                        <th>ផ្នែក / ដេប៉ាតឺម៉ង់</th>
                        <th>មុខតំណែង</th>
                        <th>លេខទូរស័ព្ទ</th>
                        <th>កាលបរិច្ឆេទចូល</th>
                        <th>ស្ថានភាព</th>
                        <th style="width: 60px;" class="text-end">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    @php
                        $name = $employee->user->name ?? $employee->name ?? 'មិនស្គាល់';
                        $email = $employee->user?->email ?? '';
                        $phone = $employee->employeeContact?->phone ?? $employee->user?->phone ?? '—';
                        $initials = mb_strtoupper(mb_substr($name, 0, 1));

                        // Pastel palettes matching student avatars
                        $pastelPalettes = [
                            ['bg' => '#eff6ff', 'color' => '#2563eb', 'border' => '#dbeafe'],
                            ['bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '#dcfce7'],
                            ['bg' => '#fefce8', 'color' => '#ca8a04', 'border' => '#fef08a'],
                            ['bg' => '#faf5ff', 'color' => '#9333ea', 'border' => '#f3e8ff'],
                            ['bg' => '#fff1f2', 'color' => '#e11d48', 'border' => '#ffe4e6'],
                            ['bg' => '#ecfeff', 'color' => '#0891b2', 'border' => '#cffafe'],
                        ];
                        $palette = $pastelPalettes[abs(crc32($name)) % count($pastelPalettes)];

                        $rawStatus = $employee->employee_status->name ?? 'Active';
                        $statusLower = strtolower($rawStatus);
                        $statusText = $employee->employee_status?->khmer_name ?? \App\Models\Employees\EmployeeStatus::translateToKhmer($rawStatus);

                        $isAct = str_contains($statusLower, 'active') || str_contains($statusText, 'សកម្ម');
                        $isLeave = str_contains($statusLower, 'leave') || str_contains($statusText, 'ច្បាប់');
                        $dotColor = $isAct ? '#22c55e' : ($isLeave ? '#f59e0b' : '#94a3b8');
                        $textColor = $isAct ? '#15803d' : ($isLeave ? '#b45309' : '#475569');
                    @endphp
                    <tr class="table-row-hover">
                        {{-- Employee Avatar + Name (Compact 36px circular avatar like student) --}}
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                @if($employee->photo_url)
                                    <img src="{{ $employee->photo_url }}"
                                         alt="{{ $name }}"
                                         class="rounded-circle flex-shrink-0 border object-fit-cover shadow-xs"
                                         style="width: 36px; height: 36px; border-color: #e5e7eb !important;"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                    <div class="flex-shrink-0 d-none" style="background-color:{{ $palette['bg'] }}; color:{{ $palette['color'] }}; border:1px solid {{ $palette['border'] }}; width:36px; height:36px; font-size:.84rem; font-weight:600; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                        {{ $initials }}
                                    </div>
                                @else
                                    <div class="flex-shrink-0" style="background-color:{{ $palette['bg'] }}; color:{{ $palette['color'] }}; border:1px solid {{ $palette['border'] }}; width:36px; height:36px; font-size:.84rem; font-weight:600; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div class="lh-sm min-w-0">
                                    <a href="{{ route('employees.show', $employee) }}"
                                       class="fw-semibold text-dark text-decoration-none text-truncate d-block"
                                       style="max-width: 200px; font-size: .88rem;"
                                       title="{{ $name }}">
                                        {{ $name }}
                                    </a>
                                    @if($email)
                                        <div class="text-muted text-truncate" style="font-size: .74rem; max-width: 200px;">
                                            {{ $email }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Employee Code (Separate Column with clean badge like student) --}}
                        <td>
                            <code class="adm-badge font-monospace fw-semibold" style="font-size: 0.82rem; background-color: #f1f5f9; padding: 2px 7px; border-radius: 5px; color: #1e293b;">
                                {{ $employee->employee_code }}
                            </code>
                        </td>

                        {{-- Department --}}
                        <td>
                            <span class="fw-medium text-dark" style="font-size: 0.86rem;">
                                {{ $employee->employee_department->name ?? '—' }}
                            </span>
                        </td>

                        {{-- Position --}}
                        <td>
                            <span class="text-dark" style="font-size: 0.85rem;">
                                {{ $employee->employee_position->name ?? '—' }}
                            </span>
                        </td>

                        {{-- Phone (Single Line) --}}
                        <td>
                            <span class="text-dark" style="font-size: 0.85rem;">{{ $phone }}</span>
                        </td>

                        {{-- Date of Joining --}}
                        <td>
                            <span class="text-muted" style="font-size: 0.84rem;">
                                {{ $employee->date_of_joining ? $employee->date_of_joining->format('M d, Y') : '—' }}
                            </span>
                        </td>

                        {{-- Minimal Dot Status ("• សកម្ម") --}}
                        <td>
                            <div class="d-inline-flex align-items-center gap-1.5" style="font-size: 0.84rem; font-weight: 500; color: {{ $textColor }};">
                                <span class="rounded-circle d-inline-block flex-shrink-0" style="width: 7px; height: 7px; background-color: {{ $dotColor }};"></span>
                                <span>{{ $statusText }}</span>
                            </div>
                        </td>

                        {{-- Actions "..." Dropdown Menu --}}
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0 py-1 px-2 text-muted" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false"
                                        style="border-radius: 6px;"
                                        title="សកម្មភាព">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border py-1" style="min-width: 165px; border-color: #e2e8f0; border-radius: 8px; font-size: 0.86rem;">
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('employees.show', $employee) }}">
                                            <i class="bi bi-eye me-2 text-muted"></i>មើលព័ត៌មានលម្អិត
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('employees.id-card', $employee) }}" target="_blank">
                                            <i class="bi bi-person-badge me-2 text-muted"></i>បោះពុម្ពកាតបុគ្គលិក
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('employees.edit', $employee) }}">
                                            <i class="bi bi-pencil me-2 text-muted"></i>កែសម្រួល
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-1.5 text-danger"
                                                onclick="confirmDelete({{ $employee->id }}, '{{ addslashes($name) }}', '{{ addslashes($employee->employee_code) }}')">
                                            <i class="bi bi-trash me-2 text-danger"></i>លុបបុគ្គលិក
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
            <div class="p-3 px-4 border-top bg-light bg-opacity-50 d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-color: #e2e8f0 !important;">
                <div class="text-muted small" style="font-size: 0.82rem;">
                    បង្ហាញពី {{ $employees->firstItem() }} ដល់ {{ $employees->lastItem() }} នៃ {{ $employees->total() }} នាក់
                </div>
                <div>
                    {{ $employees->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    @endif
</div>

{{-- ═══════════════════════════════════════════
     DELETE CONFIRMATION MODAL
═══════════════════════════════════════════ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger" style="width: 56px; height: 56px;">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-1 text-dark">លុបប្រវត្តិរូបបុគ្គលិក?</h5>
                <p class="text-muted small mb-4">
                    តើអ្នកពិតជាចង់លុបបុគ្គលិក <strong class="text-dark" id="deleteEmployeeName"></strong> (<span id="deleteEmployeeCode" class="font-monospace text-danger fw-semibold"></span>) នេះមែនទេ?<br>
                    សកម្មភាពនេះនឹងផ្អាកគណនី និងលុបចេញពីបញ្ជី ប៉ុន្តែនៅរក្សាទុកប្រវត្តិនៃវត្តមាន និងប្រាក់បៀវត្សរ៍ដដែល។
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4" style="border-radius: 8px;" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm px-4 shadow-none" style="border-radius: 8px;">
                            <i class="bi bi-trash me-1"></i>យល់ព្រមលុប
                        </button>
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

    function confirmDelete(id, name, code) {
        document.getElementById('deleteEmployeeName').textContent = name;
        document.getElementById('deleteEmployeeCode').textContent = code;
        document.getElementById('deleteForm').action = `{{ url('employees') }}/${id}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endsection
