@extends('layouts.app')
@section('title', 'កិច្ចសន្យាការងារបុគ្គលិក')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">កិច្ចសន្យាការងារ</li>
    </ol>
</nav>
@endsection
@push('styles')
<style>
.page-banner { background:linear-gradient(135deg,#b45309 0%,#d97706 60%,#fbbf24 100%); border-radius:var(--radius-md,8px); padding:0.75rem 1.25rem; margin-bottom:0.75rem; position:relative; overflow:hidden; color:#fff; box-shadow:0 2px 8px rgba(217,119,6,0.15); }
.page-banner::before { content:''; position:absolute; top:-30px; right:-30px; width:120px; height:120px; background:rgba(255,255,255,.1); border-radius:50%; }
.page-banner .banner-icon { width:36px; height:36px; background:rgba(255,255,255,.2); border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.page-banner h1 { font-size:1.1rem; font-weight:700; margin:0 0 .15rem; line-height:1.2; }
.page-banner p { font-size:.78rem; color:rgba(255,255,255,.9); margin:0; line-height:1.3; }

.stat-card { background:#fff; border:1px solid var(--border-soft,#e5e7eb); border-radius:var(--radius-md,8px); padding:0.65rem 0.9rem; display:flex; align-items:center; gap:0.75rem; box-shadow:0 1px 2px rgba(0,0,0,.03); transition:all .15s ease; }
.stat-card:hover { transform:translateY(-1px); box-shadow:0 3px 6px rgba(0,0,0,.06); }
.stat-icon { width:38px; height:38px; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.stat-value { font-size:1.25rem; font-weight:800; line-height:1; }
.stat-label { font-size:.75rem; color:#6b7280; margin-top:.15rem; }

.custom-contract-tabs { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; padding:3px; display:inline-flex; gap:4px; }
.custom-contract-tabs .tab-btn { border-radius:6px; font-size:0.82rem; font-weight:600; padding:0.4rem 0.85rem; color:#64748b; text-decoration:none; display:inline-flex; align-items:center; gap:0.45rem; transition:all .15s ease; border:none; }
.custom-contract-tabs .tab-btn:hover { color:#1e293b; background:rgba(255,255,255,0.7); }
.custom-contract-tabs .tab-btn.active-has { background:#16a34a !important; color:#fff !important; box-shadow:0 1px 3px rgba(22,163,74,0.3); }
.custom-contract-tabs .tab-btn.active-no { background:#dc2626 !important; color:#fff !important; box-shadow:0 1px 3px rgba(220,38,38,0.3); }

.filter-bar { background:#fff; border:1px solid var(--border-soft,#e5e7eb); border-radius:var(--radius-md,8px); padding:0.5rem 0.85rem; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; box-shadow:0 1px 2px rgba(0,0,0,.02); }
.search-wrapper { position:relative; width:250px; }
.search-wrapper i { position:absolute; left:0.85rem; top:50%; transform:translateY(-50%); color:#6b7280; font-size:0.85rem; }
.search-wrapper input { width:100%; height:34px; padding:.35rem .85rem .35rem 2.2rem; font-size:.82rem; border:1px solid #d1d5db; border-radius:6px; transition:all .2s; }
.search-wrapper input:focus { border-color:#d97706; box-shadow:0 0 0 3px rgba(217,119,6,.1); outline:none; }
.form-select-custom { height:34px; padding:.35rem 1.8rem .35rem .75rem; font-size:.82rem; border:1px solid #d1d5db; border-radius:6px; background:#fff; }

.table-card { background:#fff; border:1px solid var(--border-soft,#e5e7eb); border-radius:var(--radius-md,8px); overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.03); }
.table-custom { margin-bottom:0; width:100%; border-collapse:separate; border-spacing:0; }
.table-custom thead th { background:#f8fafc; padding:.6rem .85rem; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#6b7280; border-bottom:1px solid #e5e7eb; }
.table-custom tbody td { padding:.65rem .85rem; font-size:.83rem; vertical-align:middle; border-bottom:1px solid #f3f4f6; }
.table-custom tbody tr:last-child td { border-bottom:none; }
.table-custom tbody tr:hover { background:#f8fafc; }

.emp-avatar { width:34px; height:34px; border-radius:50%; background:#f59e0b; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:0.8rem; overflow:hidden; flex-shrink:0; }
.emp-avatar img { width:100%; height:100%; object-fit:cover; }
</style>
@endpush
@section('content')
<div class="page-banner">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2.5">
            <div class="banner-icon"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <h1>កិច្ចសន្យាការងារបុគ្គលិក</h1>
                <p>គ្រប់គ្រងកិច្ចព្រមព្រៀងការងារ តាមដានបុគ្គលិកមានកិច្ចសន្យា និងបុគ្គលិកដែលមិនទាន់មានកិច្ចសន្យា។</p>
            </div>
        </div>
        <div>
            <a href="{{ route('employee-contracts.create') }}" class="btn btn-light btn-sm fw-semibold shadow-xs text-nowrap" style="font-size:0.8rem; padding:0.35rem 0.85rem; border-radius:6px; white-space:nowrap;">
                <i class="bi bi-plus-lg me-1"></i>បង្កើតកិច្ចសន្យាថ្មី
            </a>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     STAT CARDS (បង្ហាញស្ថិតិបុគ្គលិកមាន និងគ្មានកិច្ចសន្យា)
═══════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-2">
    <div class="col-6 col-md-3">
        <a href="{{ route('employee-contracts.index', ['tab' => 'has_contract']) }}" class="text-decoration-none">
            <div class="stat-card {{ $tab === 'has_contract' ? 'border-success' : '' }}" style="{{ $tab === 'has_contract' ? 'background:#f0fdf4;' : '' }}">
                <div class="stat-icon" style="background:#d1fae5;color:#065f46;"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="stat-value text-success">{{ $stats['has_contract_count'] }}</div>
                    <div class="stat-label">បុគ្គលិកមានកិច្ចសន្យា</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('employee-contracts.index', ['tab' => 'no_contract']) }}" class="text-decoration-none">
            <div class="stat-card {{ $tab === 'no_contract' ? 'border-danger' : '' }}" style="{{ $tab === 'no_contract' ? 'background:#fff1f2;' : '' }}">
                <div class="stat-icon" style="background:#fee2e2;color:#991b1b;"><i class="bi bi-person-x-fill"></i></div>
                <div>
                    <div class="stat-value text-danger">{{ $stats['no_contract_count'] }}</div>
                    <div class="stat-label">មិនទាន់មានកិច្ចសន្យា</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#92400e;"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div>
                <div class="stat-value text-dark">{{ $stats['total'] }}</div>
                <div class="stat-label">កិច្ចសន្យាសរុប (Active: {{ $stats['active'] }})</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;color:#991b1b;"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="stat-value text-danger">{{ $stats['expiring_soon'] }}</div>
                <div class="stat-label">ជិតផុតកំណត់ ≤៣០ ថ្ងៃ</div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-2 py-2 px-3" style="font-size:0.82rem; border-radius:6px;">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-2 py-2 px-3" style="font-size:0.82rem; border-radius:6px;">
        <i class="bi bi-exclamation-triangle-fill"></i><span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     TAB NAVIGATION (ដាក់បង្ហាញបុគ្គលិកមានកិច្ចសន្យា និងមិនទាន់មានដាច់ដោយឡែកពីគ្នា)
═══════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
    <div class="custom-contract-tabs shadow-2xs">
        <a href="{{ route('employee-contracts.index', ['tab' => 'has_contract']) }}" 
           class="tab-btn {{ $tab === 'has_contract' ? 'active-has' : '' }}">
            <i class="bi bi-file-earmark-check"></i>
            <span>បុគ្គលិកមានកិច្ចសន្យា</span>
            <span class="badge {{ $tab === 'has_contract' ? 'bg-white text-success' : 'bg-success text-white' }} rounded-pill px-2" style="font-size:0.75rem;">{{ $stats['has_contract_count'] }}</span>
        </a>
        <a href="{{ route('employee-contracts.index', ['tab' => 'no_contract']) }}" 
           class="tab-btn {{ $tab === 'no_contract' ? 'active-no' : '' }}">
            <i class="bi bi-person-exclamation"></i>
            <span>បុគ្គលិកមិនទាន់មានកិច្ចសន្យា</span>
            <span class="badge {{ $tab === 'no_contract' ? 'bg-white text-danger' : 'bg-danger text-white' }} rounded-pill px-2" style="font-size:0.75rem;">{{ $stats['no_contract_count'] }}</span>
        </a>
    </div>

    @if($tab === 'no_contract' && $stats['no_contract_count'] > 0)
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fw-medium" style="font-size:0.78rem;">
            <i class="bi bi-exclamation-circle me-1"></i>មានបុគ្គលិកចំនួន {{ $stats['no_contract_count'] }} នាក់ពុំទាន់មានកិច្ចសន្យាការងារនៅឡើយ
        </span>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     TAB 1: បុគ្គលិកមានកិច្ចសន្យា (HAS CONTRACT)
═══════════════════════════════════════════════════════════════════ --}}
@if($tab === 'has_contract')
    <form method="GET" class="filter-bar mb-2" id="filterForm">
        <input type="hidden" name="tab" value="has_contract">
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកកិច្ចសន្យា / បុគ្គលិក…" id="searchInput">
        </div>
        <select name="status" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <option value="active" @selected(request('status') === 'active')>កំពុងមានសុពលភាព</option>
            <option value="draft" @selected(request('status') === 'draft')>ពង្រាង</option>
            <option value="expired" @selected(request('status') === 'expired')>ផុតកំណត់</option>
            <option value="terminated" @selected(request('status') === 'terminated')>បានបញ្ចប់</option>
            <option value="renewed" @selected(request('status') === 'renewed')>បានបន្តកិច្ចសន្យា</option>
        </select>
        <select name="contract_type" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">ប្រភេទទាំងអស់</option>
            <option value="permanent" @selected(request('contract_type') === 'permanent')>កិច្ចសន្យាមិនកំណត់ថិរវេលា</option>
            <option value="fixed_term" @selected(request('contract_type') === 'fixed_term')>កិច្ចសន្យាមានកំណត់ថិរវេលា</option>
            <option value="probation" @selected(request('contract_type') === 'probation')>ការសាកល្បងការងារ</option>
            <option value="part_time" @selected(request('contract_type') === 'part_time')>ការងារក្រៅម៉ោង</option>
            <option value="intern" @selected(request('contract_type') === 'intern')>កម្មសិក្សាការងារ</option>
        </select>
        <button type="submit" class="btn btn-sm btn-outline-secondary" style="height:34px; padding:0.35rem 0.75rem; font-size:0.8rem; border-radius:6px;"><i class="bi bi-funnel me-1"></i>ស្វែងរក</button>
        @if(request()->hasAny(['search','status','contract_type']))
            <a href="{{ route('employee-contracts.index', ['tab' => 'has_contract']) }}" class="btn btn-sm btn-outline-secondary" style="height:34px; padding:0.35rem 0.75rem; font-size:0.8rem; border-radius:6px;"><i class="bi bi-x-circle me-1"></i>សម្អាត</a>
        @endif
    </form>

    <div class="table-card">
        <table class="table-custom table">
            <thead>
                <tr>
                    <th>លេខកិច្ចសន្យា</th>
                    <th>បុគ្គលិក</th>
                    <th>ប្រភេទ</th>
                    <th>កាលបរិច្ឆេទ / រយៈពេល</th>
                    <th class="text-end">ប្រាក់បៀវត្ស</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $typeNames = [
                        'permanent' => 'កិច្ចសន្យាមិនកំណត់ថិរវេលា',
                        'fixed_term' => 'កិច្ចសន្យាមានកំណត់ថិរវេលា',
                        'probation' => 'ការសាកល្បងការងារ',
                        'part_time' => 'ការងារក្រៅម៉ោង',
                        'intern' => 'កម្មសិក្សាការងារ'
                    ];
                    $statusNames = [
                        'active' => 'មានសុពលភាព',
                        'draft' => 'ពង្រាង',
                        'expired' => 'ផុតកំណត់',
                        'terminated' => 'បានបញ្ចប់',
                        'renewed' => 'បានបន្ត'
                    ];
                @endphp
                @forelse($contracts as $contract)
                <tr>
                    <td>
                        <a href="{{ route('employee-contracts.show', $contract) }}" class="fw-semibold text-decoration-none text-dark">
                            {{ $contract->contract_number }}
                        </a>
                    </td>
                    <td>
                        @php
                            $emp = $contract->employee;
                            $khmerName = trim(($emp?->last_name_khmer ?? '').' '.($emp?->first_name_khmer ?? ''));
                            $latinName = trim(($emp?->last_name ?? '').' '.($emp?->first_name ?? '')) ?: ($emp?->user?->name ?? 'បុគ្គលិក');
                        @endphp
                        <div class="fw-medium text-dark">{{ $khmerName ?: $latinName }}</div>
                        <div class="small text-muted">
                            @if($khmerName && $latinName && $khmerName !== $latinName)
                                <span>{{ $latinName }} • </span>
                            @endif
                            <span class="font-monospace">{{ $emp?->employee_code }}</span>
                        </div>
                    </td>
                    <td>{{ $typeNames[$contract->contract_type] ?? ucfirst(str_replace('_',' ',$contract->contract_type)) }}</td>
                    <td>
                        <div>{{ $contract->start_date ? $contract->start_date->format('d M Y') : '—' }}</div>
                        <div class="small text-muted">{{ $contract->end_date ? '→ '.$contract->end_date->format('d M Y') : 'គ្មានកំណត់ (Ongoing)' }}</div>
                    </td>
                    <td class="text-end fw-semibold">{{ $contract->salary ? '$'.number_format($contract->salary, 2) : '—' }}</td>
                    <td>
                        @php $stMap = ['active'=>'bg-success-subtle text-success-emphasis','draft'=>'bg-secondary-subtle text-secondary-emphasis','expired'=>'bg-danger-subtle text-danger-emphasis','terminated'=>'bg-danger-subtle text-danger-emphasis','renewed'=>'bg-info-subtle text-info-emphasis']; @endphp
                        <span class="badge {{ $stMap[$contract->status] ?? 'bg-secondary-subtle text-secondary-emphasis' }}">
                            {{ $statusNames[$contract->status] ?? ucfirst($contract->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('employee-contracts.print', $contract) }}" target="_blank" class="btn btn-sm btn-outline-warning" title="បោះពុម្ពកិច្ចសន្យា"><i class="bi bi-printer"></i></a>
                            <a href="{{ route('employee-contracts.show', $contract) }}" class="btn btn-sm btn-outline-primary" title="មើលលម្អិត"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('employee-contracts.edit', $contract) }}" class="btn btn-sm btn-outline-secondary" title="កែសម្រួល"><i class="bi bi-pencil"></i></a>
                            @if($contract->isActive())
                            <form method="POST" action="{{ route('employee-contracts.renew', $contract) }}" onsubmit="return confirm('តើអ្នកពិតជាចង់សម្គាល់កិច្ចសន្យានេះថាបានបន្ត និងបង្កើតកិច្ចសន្យាថ្មីមែនទេ?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" title="បន្តកិច្ចសន្យា"><i class="bi bi-arrow-repeat"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-file-earmark-text fs-3 d-block mb-2 opacity-50"></i>
                        មិនមានកិច្ចសន្យាការងារឡើយ។
                        <a href="{{ route('employee-contracts.create') }}" class="d-block mt-1 text-primary">បង្កើតកិច្ចសន្យាដំបូង →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($contracts && $contracts->hasPages())
        <div class="mt-3 d-flex justify-content-center">{{ $contracts->withQueryString()->links() }}</div>
    @endif

{{-- ═══════════════════════════════════════════════════════════════════
     TAB 2: បុគ្គលិកមិនទាន់មានកិច្ចសន្យា (NO CONTRACT)
═══════════════════════════════════════════════════════════════════ --}}
@else
    <form method="GET" class="filter-bar mb-2" id="filterForm">
        <input type="hidden" name="tab" value="no_contract">
        <div class="search-wrapper" style="width:280px;">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកឈ្មោះបុគ្គលិក / លេខកូដ / ទូរស័ព្ទ…" id="searchInput">
        </div>
        <select name="department_id" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">គ្រប់ដេប៉ាតឺម៉ង់ / ផ្នែក</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-sm btn-outline-secondary" style="height:34px; padding:0.35rem 0.75rem; font-size:0.8rem; border-radius:6px;"><i class="bi bi-funnel me-1"></i>ស្វែងរក</button>
        @if(request()->hasAny(['search','department_id']))
            <a href="{{ route('employee-contracts.index', ['tab' => 'no_contract']) }}" class="btn btn-sm btn-outline-secondary" style="height:34px; padding:0.35rem 0.75rem; font-size:0.8rem; border-radius:6px;"><i class="bi bi-x-circle me-1"></i>សម្អាត</a>
        @endif
    </form>

    <div class="table-card">
        <table class="table-custom table">
            <thead>
                <tr>
                    <th style="width:110px;">លេខកូដ</th>
                    <th>បុគ្គលិក</th>
                    <th>ផ្នែក / តួនាទី</th>
                    <th>ថ្ងៃចូលបម្រើការ</th>
                    <th class="text-end">ប្រាក់ខែមូលដ្ឋាន</th>
                    <th>ស្ថានភាពកិច្ចសន្យា</th>
                    <th class="text-center text-nowrap" style="width:190px; min-width:190px; white-space:nowrap;">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employeesWithoutContract as $emp)
                    @php
                        $khmerName = trim(($emp->last_name_khmer ?? '').' '.($emp->first_name_khmer ?? ''));
                        $latinName = trim(($emp->last_name ?? '').' '.($emp->first_name ?? '')) ?: ($emp->user?->name ?? 'បុគ្គលិក');
                        $displayName = $khmerName ?: $latinName;
                        $initials = strtoupper(mb_substr($displayName, 0, 1));
                        $pastContract = $emp->employeeContracts->first();
                    @endphp
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace px-2 py-1">{{ $emp->employee_code }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="emp-avatar">
                                    @if($emp->photo_url)
                                        <img src="{{ $emp->photo_url }}" alt="{{ $displayName }}">
                                    @else
                                        <span>{{ $initials ?: 'E' }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $displayName }}</div>
                                    <div class="small text-muted">
                                        @if($khmerName && $latinName && $khmerName !== $latinName)
                                            <span>{{ $latinName }}</span>
                                        @endif
                                        @if($emp->employeeContact?->phone)
                                            <span> • <i class="bi bi-telephone text-secondary"></i> {{ $emp->employeeContact->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $emp->employee_department?->name ?? '—' }}</div>
                            <div class="small text-muted">{{ $emp->employee_position?->name ?? '—' }}</div>
                        </td>
                        <td>
                            <div>{{ $emp->date_of_joining ? \Carbon\Carbon::parse($emp->date_of_joining)->format('d M Y') : '—' }}</div>
                            <div class="small text-muted">
                                {{ $emp->date_of_joining ? \Carbon\Carbon::parse($emp->date_of_joining)->diffForHumans(null, true).' មុន' : '' }}
                            </div>
                        </td>
                        <td class="text-end fw-semibold">
                            {{ $emp->basic_salary ? '$'.number_format($emp->basic_salary, 2) : '—' }}
                        </td>
                        <td>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                <i class="bi bi-x-circle me-1"></i>មិនទាន់មានកិច្ចសន្យា
                            </span>
                            @if($pastContract)
                                <div class="text-muted small mt-0.5" style="font-size:0.7rem;">
                                    <i class="bi bi-clock-history me-1"></i>កិច្ចសន្យាចាស់៖ {{ $pastContract->contract_number }} ({{ $pastContract->status }})
                                </div>
                            @else
                                <div class="text-muted small mt-0.5" style="font-size:0.7rem;">
                                    <i class="bi bi-person-plus me-1"></i>បុគ្គលិកថ្មី (គ្មានកិច្ចសន្យា)
                                </div>
                            @endif
                        </td>
                        <td class="text-center text-nowrap" style="white-space:nowrap;">
                            <div class="d-flex justify-content-center align-items-center gap-1.5 flex-nowrap">
                                <a href="{{ route('employee-contracts.create', ['employee_id' => $emp->id]) }}" 
                                   class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1.5 shadow-2xs text-nowrap" 
                                   style="font-size:0.8rem; padding:0.35rem 0.75rem; border-radius:6px; white-space:nowrap; flex-shrink:0; text-decoration:none;"
                                   title="បង្កើតកិច្ចសន្យាការងារ">
                                    <i class="bi bi-plus-lg" style="font-size:0.75rem;"></i>
                                    <span style="white-space:nowrap;">បង្កើតកិច្ចសន្យា</span>
                                </a>
                                <a href="{{ route('employees.show', $emp) }}" 
                                   class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center" 
                                   style="width:31px; height:31px; padding:0; border-radius:6px; flex-shrink:0;" 
                                   title="មើលព័ត៌មានបុគ្គលិក">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <div class="my-2">
                                <i class="bi bi-shield-check text-success" style="font-size:2.4rem;"></i>
                                <div class="fw-semibold text-dark mt-2" style="font-size:0.95rem;">អបអរសាទរ! បុគ្គលិកទាំងអស់សុទ្ធតែមានកិច្ចសន្យាការងាររួចរាល់ហើយ</div>
                                <div class="small text-muted mt-1">គ្មានបុគ្គលិកណាដែលមិនទាន់មានកិច្ចសន្យាការងារក្នុងប្រព័ន្ធនៅឡើយទេ។</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employeesWithoutContract && $employeesWithoutContract->hasPages())
        <div class="mt-3 d-flex justify-content-center">{{ $employeesWithoutContract->withQueryString()->links() }}</div>
    @endif
@endif
@endsection
@push('scripts')
<script>
let t;
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(t); 
        t = setTimeout(() => document.getElementById('filterForm').submit(), 500);
    });
}
</script>
@endpush
