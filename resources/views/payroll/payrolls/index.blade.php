@extends('layouts.app')

@section('title', 'គ្រប់គ្រងប្រាក់បៀវត្ស — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ប្រាក់បៀវត្ស</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.page-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-md, 8px);
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.65rem;
    position: relative;
    overflow: hidden;
    color: #fff;
    box-shadow: 0 2px 8px rgba(232, 168, 56, 0.15);
}
.page-banner::before {
    content: '';
    position: absolute; top: -30px; right: -30px;
    width: 130px; height: 130px;
    background: rgba(255,255,255,.12); border-radius: 50%;
    pointer-events: none;
}
.page-banner::after {
    content: '';
    position: absolute; bottom: -25px; right: 70px;
    width: 90px; height: 90px;
    background: rgba(255,255,255,.07); border-radius: 50%;
    pointer-events: none;
}
.page-banner .banner-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,.2);
    border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: #fff; margin-bottom: 0;
    flex-shrink: 0;
}
.page-banner h1 { font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0 0 .15rem; line-height: 1.2; }
.page-banner p { font-size: .78rem; color: rgba(255,255,255,.9); margin: 0; line-height: 1.3; }

.stat-card {
    background: #fff; border: 1px solid var(--border-soft, #e5e7eb);
    border-radius: var(--radius-md, 8px); padding: 0.55rem 0.75rem;
    display: flex; align-items: center; gap: 0.65rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.03);
}
.stat-icon {
    width: 34px; height: 34px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;
}
.stat-value { font-size: 1.15rem; font-weight: 700; line-height: 1.1; color: var(--text-dark, #0f172a); }
.stat-label { font-size: .72rem; color: var(--text-muted, #64748b); margin-top: .1rem; }

.filter-bar {
    background: #fff; border: 1px solid var(--border-soft, #e5e7eb);
    border-radius: var(--radius-md, 8px); padding: 0.45rem 0.75rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.02); flex-wrap: wrap;
    margin-bottom: 0.65rem;
}
.filter-inputs { display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap; width: 100%; }
.search-wrapper { position: relative; width: 220px; }
.search-wrapper i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-muted, #64748b); font-size: 0.82rem; }
.search-wrapper input {
    width: 100%; height: 35px; padding: 0.35rem 0.75rem 0.35rem 2.1rem; font-size: 0.82rem;
    border: 1px solid var(--border, #cbd5e1); border-radius: 6px; transition: all .15s ease-in-out;
}
.search-wrapper input:focus, .form-select-custom:focus {
    border-color: #e8a838; box-shadow: 0 0 0 3px rgba(232,168,56,.18); outline: none;
}
.form-select-custom {
    height: 35px; padding: 0.35rem 1.8rem 0.35rem 0.65rem; font-size: 0.82rem;
    border: 1px solid var(--border, #cbd5e1); border-radius: 6px;
    background-color: #fff; transition: all .15s ease-in-out;
}

.table-card {
    background: #fff; border: 1px solid var(--border-soft, #e5e7eb);
    border-radius: var(--radius-md, 8px); overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    margin-bottom: 0.65rem;
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc; padding: .55rem .75rem; font-size: .76rem;
    font-weight: 700; text-transform: uppercase; letter-spacing: .03em;
    color: var(--text-muted, #64748b); border-bottom: 1px solid var(--border-soft, #e5e7eb); white-space: nowrap;
}
.table-custom tbody td { padding: .55rem .75rem; font-size: .82rem; vertical-align: middle; border-bottom: 1px solid var(--border-soft, #e5e7eb); }
.table-custom tbody tr:last-child td { border-bottom: none; }
.table-custom tbody tr:hover { background: #fdfdfd; }

.badge-status { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .65rem; border-radius: 20px; font-size: .75rem; font-weight: 600; }
.badge-draft   { background: #f1f5f9; color: #475569; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-approved{ background: #dbeafe; color: #1e40af; }
.badge-paid    { background: #dcfce7; color: #15803d; }
.badge-cancelled{ background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="page-banner d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2.5">
        <div class="banner-icon"><i class="bi bi-cash-coin"></i></div>
        <div>
            <h1>គ្រប់គ្រងប្រាក់បៀវត្ស</h1>
            <p>បង្កើត អនុម័ត និងបើកប្រាក់បៀវត្សជូនបុគ្គលិក។</p>
        </div>
    </div>
    <div>
        <a href="{{ route('payrolls.create') }}" class="btn btn-light btn-sm fw-semibold d-flex align-items-center gap-1.5 shadow-sm px-3 py-1.5" style="border-radius:6px; font-size:0.82rem;">
            <i class="bi bi-plus-lg"></i>
            <span>បង្កើតបញ្ជីបើកប្រាក់បៀវត្ស</span>
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-2 mb-2">
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#92400e;"><i class="bi bi-receipt"></i></div>
            <div><div class="stat-value">{{ number_format($stats['total']) }}</div><div class="stat-label">សរុប</div></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f1f5f9;color:#475569;"><i class="bi bi-pencil-square"></i></div>
            <div><div class="stat-value">{{ number_format($stats['draft']) }}</div><div class="stat-label">ពង្រាង</div></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ number_format($stats['pending']) }}</div><div class="stat-label">រង់ចាំការពិនិត្យ</div></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0f2fe;color:#0369a1;"><i class="bi bi-check-circle"></i></div>
            <div><div class="stat-value">{{ number_format($stats['approved']) }}</div><div class="stat-label">បានអនុម័ត</div></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check2-all"></i></div>
            <div><div class="stat-value">{{ number_format($stats['paid']) }}</div><div class="stat-label">បានបើកប្រាក់</div></div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-currency-dollar"></i></div>
            <div><div class="stat-value" style="font-size:0.95rem;">${{ number_format($stats['total_net'], 0) }}</div><div class="stat-label">ប្រាក់សុទ្ធបានបើក</div></div>
        </div>
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-2 py-2 px-3 small" role="alert">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-2 py-2 px-3 small" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i><span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@php
    $khmerMonths = [
        1 => 'មករា (January)', 2 => 'កុម្ភៈ (February)', 3 => 'មីនា (March)', 4 => 'មេសា (April)',
        5 => 'ឧសភា (May)', 6 => 'មិថុនា (June)', 7 => 'កក្កដា (July)', 8 => 'សីហា (August)',
        9 => 'កញ្ញា (September)', 10 => 'តុលា (October)', 11 => 'វិច្ឆិកា (November)', 12 => 'ធ្នូ (December)'
    ];
    $payrollStatuses = [
        'draft' => 'ពង្រាង',
        'pending' => 'រង់ចាំ',
        'approved' => 'បានអនុម័ត',
        'paid' => 'បានបើក',
        'cancelled' => 'បានបោះបង់'
    ];
@endphp

{{-- Filter --}}
<div class="filter-bar">
    <form method="GET" class="filter-inputs" id="filterForm">
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកប្រាក់បៀវត្ស / បុគ្គលិក…" id="searchInput">
        </div>
        <select name="month" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">ខែទាំងអស់</option>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" @selected(request('month') == $m)>{{ $khmerMonths[$m] ?? $m }}</option>
            @endforeach
        </select>
        <select name="year" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">ឆ្នាំទាំងអស់</option>
            @foreach(range(now()->year, now()->year - 5) as $y)
                <option value="{{ $y }}" @selected(request('year') == $y)>ឆ្នាំ {{ $y }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select-custom" onchange="document.getElementById('filterForm').submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <option value="draft"     @selected(request('status') === 'draft')>ពង្រាង (Draft)</option>
            <option value="pending"   @selected(request('status') === 'pending')>រង់ចាំការពិនិត្យ (Pending)</option>
            <option value="approved"  @selected(request('status') === 'approved')>បានអនុម័ត (Approved)</option>
            <option value="paid"      @selected(request('status') === 'paid')>បានបើកប្រាក់ (Paid)</option>
            <option value="cancelled" @selected(request('status') === 'cancelled')>បានបោះបង់ (Cancelled)</option>
        </select>
        @if(request()->hasAny(['search','month','year','status']))
            <a href="{{ route('payrolls.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="height:35px; border-radius:6px; font-size:0.8rem;">
                <i class="bi bi-x-circle"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="table-card">
    <table class="table-custom table">
        <thead>
            <tr>
                <th>លេខកូដបៀវត្ស</th>
                <th>បុគ្គលិក</th>
                <th>កាលបរិច្ឆេទ / វដ្ត</th>
                <th class="text-end">ប្រាក់បៀវត្សគោល</th>
                <th class="text-end">ប្រាក់បៀវត្សសុទ្ធ</th>
                <th>ស្ថានភាព</th>
                <th>កាលបរិច្ឆេទបើក</th>
                <th class="text-center">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payrolls as $payroll)
            <tr>
                <td>
                    <a href="{{ route('payrolls.show', $payroll) }}" class="fw-semibold text-decoration-none text-dark">
                        {{ $payroll->payroll_number }}
                    </a>
                </td>
                <td>
                    <div class="fw-medium">{{ $payroll->employee?->user?->name ?? 'បុគ្គលិក' }}</div>
                    <div class="small text-muted">
                        {{ $payroll->employee?->employee_code }}
                        @if($payroll->employee?->employee_position || $payroll->employee?->employee_department)
                            • {{ $payroll->employee?->employee_position?->name ?? '—' }} ({{ $payroll->employee?->employee_department?->name ?? '—' }})
                        @endif
                    </div>
                </td>
                <td>{{ $payroll->period }}</td>
                <td class="text-end">${{ number_format($payroll->basic_salary, 2) }}</td>
                <td class="text-end fw-semibold text-dark">${{ number_format($payroll->net_salary, 2) }}</td>
                <td>
                    <span class="badge-status badge-{{ $payroll->status }}">
                        <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
                        {{ $payrollStatuses[$payroll->status] ?? ucfirst($payroll->status) }}
                    </span>
                </td>
                <td>{{ $payroll->pay_date?->format('d M Y') ?? '—' }}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-outline-primary" title="មើលលម្អិត">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('payrolls.payslip', $payroll) }}" class="btn btn-sm btn-outline-secondary" title="បោះពុម្ពប័ណ្ណបើកប្រាក់">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-2 opacity-50"></i>
                    មិនទាន់មានទិន្នន័យប្រាក់បៀវត្សឡើយ។
                    <a href="{{ route('payrolls.create') }}" class="d-block mt-1 text-primary">បង្កើតបញ្ជីបើកប្រាក់បៀវត្សដំបូង →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($payrolls->hasPages())
    <div class="mt-3 d-flex justify-content-center">
        {{ $payrolls->withQueryString()->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
let searchTimer;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => document.getElementById('filterForm').submit(), 500);
});
</script>
@endpush
