@extends('layouts.app')

@section('title', 'ផ្ទាំងគ្រប់គ្រងហិរញ្ញវត្ថុ — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ផ្ទាំងគ្រប់គ្រងហិរញ្ញវត្ថុ</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
    .fin-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0px !important;
        box-shadow: var(--shadow-xs);
        transition: transform var(--transition-base), box-shadow var(--transition-base);
    }
    .fin-card:hover {
        box-shadow: var(--shadow-sm);
    }
    .fin-kpi-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0px !important;
        padding: 0.65rem 0.75rem;
        box-shadow: var(--shadow-xs);
        transition: transform var(--transition-base), box-shadow var(--transition-base);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .fin-kpi-card:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }
    .fin-kpi-interactive {
        cursor: pointer;
        user-select: none;
        transition: transform var(--transition-base), box-shadow var(--transition-base), border-color var(--transition-base);
    }
    .fin-kpi-interactive:hover {
        border-color: var(--color-brand-500, #3b82f6) !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.12) !important;
        transform: translateY(-2px);
    }
    .fin-icon-box {
        width: 28px;
        height: 28px;
        border-radius: 0px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .fin-currency-usd {
        font-family: var(--font-heading);
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: -0.3px;
        line-height: 1.2;
    }
    .fin-currency-khr {
        font-size: 0.72rem;
        font-weight: 600;
        opacity: 0.85;
        line-height: 1.2;
    }
    .status-mini-card {
        border-radius: 0px !important;
        padding: 0.5rem 0.35rem;
        text-align: center;
        transition: transform var(--transition-fast);
        border: 1px solid transparent;
    }
    .status-mini-card:hover {
        transform: translateY(-2px);
    }
    .status-bar-wrapper {
        height: 6px;
        border-radius: 0px !important;
        overflow: hidden;
        display: flex;
        background: #f1f5f9;
    }
    .cat-progress-bar {
        height: 5px;
        border-radius: 0px !important;
        background: #f1f5f9;
        overflow: hidden;
    }
    .student-avatar-xs {
        width: 28px;
        height: 28px;
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.7rem;
        flex-shrink: 0;
    }
    .badge {
        border-radius: 0px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h1 class="page-title mb-0" style="font-size: 1.35rem;">ផ្ទាំងគ្រប់គ្រងហិរញ្ញវត្ថុ</h1>
                <button type="button" onclick="toggleCurrencyDisplay()" id="btnCurrencyToggle" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 shadow-2xs" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 5px;" title="ចុចប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                    <i class="bi bi-arrow-left-right text-primary"></i>
                    <span id="currencyModeText">បង្ហាញ៖ <strong class="text-primary">$ USD</strong></span>
                </button>
                <span class="badge" style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-size:0.72rem; padding: 0.25rem 0.5rem;">
                    <i class="bi bi-currency-exchange me-1 text-warning"></i>$1 = 4,100 ៛
                </span>
            </div>
            <p class="page-subtitle mb-0 mt-1 small">ទិដ្ឋភាពទូទៅជាក់ស្ដែងនៃថ្លៃសិក្សា ចំណូលប្រមូលបាន ប្រាក់នៅសល់មិនទាន់បង់ និងការចំណាយ</p>
        </div>

        <div class="d-flex flex-wrap gap-1.5 align-items-center">
            <a href="{{ route('student-invoices.create') }}" class="btn btn-sm btn-primary-action shadow-sm py-1.5 px-2.5" style="font-size:0.8125rem;">
                <i class="bi bi-plus-lg me-1"></i>បង្កើតវិក្កយបត្រថ្មី
            </a>
            <a href="{{ route('student-payments.create') }}" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center shadow-sm py-1.5 px-2.5" style="background:#10b981; border:1px solid #059669; font-size:0.8125rem; border-radius: var(--radius-sm);">
                <i class="bi bi-credit-card me-1"></i>កត់ត្រាការបង់ប្រាក់
            </a>
            <a href="{{ route('school-expenses.create') }}" class="btn btn-sm btn-ghost text-danger fw-semibold d-inline-flex align-items-center py-1.5 px-2.5" style="font-size:0.8125rem;">
                <i class="bi bi-wallet2 me-1"></i>កត់ត្រាការចំណាយ
            </a>
        </div>
    </div>

    {{-- Overdue Alert if any --}}
    @if($overdueCount > 0)
        <div class="alert border-0 shadow-xs rounded-2 d-flex align-items-center justify-content-between p-2.5 px-3 mb-3"
             style="background: #fffbeb; border-left: 4px solid #f59e0b !important; color: #78350f;">
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:34px; height:34px; background:#fef3c7; color:#b45309; flex-shrink:0;">
                    <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                </div>
                <div>
                    <strong class="d-block text-dark small">មានវិក្កយបត្រហួសកាលកំណត់ចំនួន {{ $overdueCount }}</strong>
                    <span style="font-size:0.75rem; color:#92400e;">
                        សរុបទឹកប្រាក់ ${{ number_format($overdueAmount, 2) }} ({{ number_format(round($overdueAmount * 4100, -2)) }} ៛) ហួសកាលបរិច្ឆេទកំណត់នៃការបង់ប្រាក់។
                    </span>
                </div>
            </div>
            <a href="{{ route('student-invoices.index', ['status' => 'unpaid']) }}" class="btn btn-sm btn-outline-dark fw-medium py-1 px-2.5" style="font-size:0.75rem;">
                ពិនិត្យមើលវិក្កយបត្រហួសកំណត់ <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    @endif

    {{-- 1. 5 KPI Cards Row --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 mb-3" style="--bs-gutter-x: 0.85rem; --bs-gutter-y: 0.85rem;">
        {{-- Total Billed --}}
        <div class="col">
            <div class="fin-kpi-card fin-kpi-interactive" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <span class="text-muted fw-semibold text-truncate d-flex align-items-center gap-1" style="font-size:0.75rem;">
                            <span>វិក្កយបត្រសរុប</span>
                            <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                        </span>
                        <div class="fin-icon-box" style="background:#e0f2fe; color:#0284c7;">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalBilled, 2) }}" data-khr="{{ number_format(round($totalBilled * 4100, -2)) }} ៛">
                        <div class="fin-currency-usd text-dark mb-0 curr-primary">${{ number_format($totalBilled, 2) }}</div>
                        <div class="fin-currency-khr text-primary curr-secondary">{{ number_format(round($totalBilled * 4100, -2)) }} ៛</div>
                    </div>
                </div>
                <div class="pt-1.5 mt-1 border-top border-light d-flex align-items-center justify-content-between text-muted" style="font-size:0.68rem;">
                    <span class="text-truncate">វិក្កយបត្រសកម្ម</span>
                    <i class="bi bi-check-circle-fill text-primary" style="font-size:0.68rem;"></i>
                </div>
            </div>
        </div>

        {{-- Total Collected --}}
        <div class="col">
            <div class="fin-kpi-card fin-kpi-interactive" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <span class="text-muted fw-semibold text-truncate d-flex align-items-center gap-1" style="font-size:0.75rem;">
                            <span>ចំណូលប្រមូលបាន</span>
                            <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                        </span>
                        <div class="fin-icon-box" style="background:#dcfce7; color:#16a34a;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalCollected, 2) }}" data-khr="{{ number_format(round($totalCollected * 4100, -2)) }} ៛">
                        <div class="fin-currency-usd text-success mb-0 curr-primary">${{ number_format($totalCollected, 2) }}</div>
                        <div class="fin-currency-khr text-success curr-secondary">{{ number_format(round($totalCollected * 4100, -2)) }} ៛</div>
                    </div>
                </div>
                <div class="pt-1.5 mt-1 border-top border-light d-flex align-items-center justify-content-between text-muted" style="font-size:0.68rem;">
                    <span class="text-truncate">ការបង់ជាក់ស្ដែង</span>
                    @php $collectionRate = $totalBilled > 0 ? min(100, round(($totalCollected / $totalBilled) * 100)) : 0; @endphp
                    <span class="badge bg-success-subtle text-success fw-bold" style="font-size:0.65rem; padding: 0.15rem 0.4rem;">{{ $collectionRate }}%</span>
                </div>
            </div>
        </div>

        {{-- Total Outstanding --}}
        <div class="col">
            <div class="fin-kpi-card fin-kpi-interactive" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <span class="text-muted fw-semibold text-truncate d-flex align-items-center gap-1" style="font-size:0.75rem;">
                            <span>ប្រាក់នៅសល់មិនទាន់បង់</span>
                            <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                        </span>
                        <div class="fin-icon-box" style="background:#fef3c7; color:#d97706;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalOutstanding, 2) }}" data-khr="{{ number_format(round($totalOutstanding * 4100, -2)) }} ៛">
                        <div class="fin-currency-usd text-warning mb-0 curr-primary" style="color:#d97706 !important;">
                            ${{ number_format($totalOutstanding, 2) }}
                        </div>
                        <div class="fin-currency-khr text-warning curr-secondary" style="color:#b45309 !important;">
                            {{ number_format(round($totalOutstanding * 4100, -2)) }} ៛
                        </div>
                    </div>
                </div>
                <div class="pt-1.5 mt-1 border-top border-light d-flex align-items-center justify-content-between text-muted" style="font-size:0.68rem;">
                    <span class="text-truncate">រង់ចាំប្រមូល</span>
                    @if($totalOutstanding > 0)
                        <span class="badge bg-warning-subtle text-warning fw-bold" style="font-size:0.65rem; padding: 0.15rem 0.4rem;">រង់ចាំទូទាត់</span>
                    @else
                        <span class="badge bg-success-subtle text-success fw-bold" style="font-size:0.65rem; padding: 0.15rem 0.4rem;">ទូទាត់អស់</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Expenses --}}
        <div class="col">
            <div class="fin-kpi-card fin-kpi-interactive" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <span class="text-muted fw-semibold text-truncate d-flex align-items-center gap-1" style="font-size:0.75rem;">
                            <span>ការចំណាយសរុប</span>
                            <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                        </span>
                        <div class="fin-icon-box" style="background:#ffe4e6; color:#e11d48;">
                            <i class="bi bi-arrow-up-right-circle"></i>
                        </div>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalExpenses, 2) }}" data-khr="{{ number_format(round($totalExpenses * 4100, -2)) }} ៛">
                        <div class="fin-currency-usd text-danger mb-0 curr-primary">${{ number_format($totalExpenses, 2) }}</div>
                        <div class="fin-currency-khr text-danger curr-secondary">{{ number_format(round($totalExpenses * 4100, -2)) }} ៛</div>
                    </div>
                </div>
                <div class="pt-1.5 mt-1 border-top border-light d-flex align-items-center justify-content-between text-muted" style="font-size:0.68rem;">
                    <span class="text-truncate">ចំណាយប្រតិបត្តិការ</span>
                    <i class="bi bi-graph-up-arrow text-danger" style="font-size:0.68rem;"></i>
                </div>
            </div>
        </div>

        {{-- Net Cash Balance --}}
        <div class="col">
            <div class="fin-kpi-card fin-kpi-interactive" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)" style="{{ $netCashBalance >= 0 ? 'border-color:#bbf7d0;' : 'border-color:#fecdd3;' }}">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <span class="text-muted fw-semibold text-truncate d-flex align-items-center gap-1" style="font-size:0.75rem;">
                            <span>សមតុល្យសាច់ប្រាក់សុទ្ធ</span>
                            <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                        </span>
                        <div class="fin-icon-box" style="{{ $netCashBalance >= 0 ? 'background:#dcfce7; color:#15803d;' : 'background:#fee2e2; color:#b91c1c;' }}">
                            <i class="bi bi-bank"></i>
                        </div>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($netCashBalance, 2) }}" data-khr="{{ number_format(round($netCashBalance * 4100, -2)) }} ៛">
                        <div class="fin-currency-usd mb-0 curr-primary {{ $netCashBalance >= 0 ? 'text-success' : 'text-danger' }}">
                            ${{ number_format($netCashBalance, 2) }}
                        </div>
                        <div class="fin-currency-khr curr-secondary {{ $netCashBalance >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format(round($netCashBalance * 4100, -2)) }} ៛
                        </div>
                    </div>
                </div>
                <div class="pt-1.5 mt-1 border-top border-light d-flex align-items-center justify-content-between text-muted" style="font-size:0.68rem;">
                    <span class="text-truncate">ចំណូលដកចំណាយ</span>
                    <span class="badge {{ $netCashBalance >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-bold" style="font-size:0.65rem; padding: 0.15rem 0.4rem; white-space: nowrap;">
                        {{ $netCashBalance >= 0 ? 'ចំណេញ' : 'ខាត' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Status Breakdown & Category Breakdown --}}
    <div class="row mb-3" style="--bs-gutter-x: 0.85rem; --bs-gutter-y: 0.85rem;">
        {{-- Invoice Status Summary Card --}}
        <div class="col-12 col-lg-6">
            <div class="card fin-card p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2.5">
                    <h6 class="fw-bold text-dark mb-0" style="font-family: var(--font-heading); font-size: 0.95rem;">សង្ខេបស្ថានភាពវិក្កយបត្រ</h6>
                    @php
                        $allInvoicesCount = $paidCount + $partialCount + $unpaidCount + $cancelledCount;
                    @endphp
                    <span class="badge bg-light text-dark border" style="font-size: 0.72rem; padding: 0.25rem 0.5rem;">សរុប {{ $allInvoicesCount }} វិក្កយបត្រ</span>
                </div>

                {{-- Visual Distribution Bar --}}
                @if($allInvoicesCount > 0)
                    <div class="status-bar-wrapper mb-3">
                        <div style="width: {{ ($paidCount / $allInvoicesCount) * 100 }}%; background: #22c55e;" title="បានបង់រួច: {{ $paidCount }}"></div>
                        <div style="width: {{ ($partialCount / $allInvoicesCount) * 100 }}%; background: #38bdf8;" title="បង់ខ្លះ: {{ $partialCount }}"></div>
                        <div style="width: {{ ($unpaidCount / $allInvoicesCount) * 100 }}%; background: #f59e0b;" title="មិនទាន់បង់: {{ $unpaidCount }}"></div>
                        <div style="width: {{ ($cancelledCount / $allInvoicesCount) * 100 }}%; background: #94a3b8;" title="បានបោះបង់: {{ $cancelledCount }}"></div>
                    </div>
                @endif

                <div class="row g-2">
                    {{-- Paid --}}
                    <div class="col-6 col-sm-3">
                        <div class="status-mini-card" style="background:#f0fdf4; border-color:#bbf7d0;">
                            <span class="badge bg-success text-white mb-1.5 px-1.5 py-0.5" style="font-size:0.7rem;">
                                <i class="bi bi-check-circle-fill me-1"></i>បានបង់រួច
                            </span>
                            <div class="fw-bold fs-5 text-success mb-0" style="font-family: var(--font-heading); line-height: 1.2;">{{ $paidCount }}</div>
                            <span class="text-muted" style="font-size:0.68rem;">វិក្កយបត្រ</span>
                        </div>
                    </div>

                    {{-- Partial --}}
                    <div class="col-6 col-sm-3">
                        <div class="status-mini-card" style="background:#f0f9ff; border-color:#bae6fd;">
                            <span class="badge bg-info text-white mb-1.5 px-1.5 py-0.5" style="font-size:0.7rem;">
                                <i class="bi bi-pie-chart-fill me-1"></i>បង់ខ្លះ
                            </span>
                            <div class="fw-bold fs-5 text-info mb-0" style="font-family: var(--font-heading); line-height: 1.2;">{{ $partialCount }}</div>
                            <span class="text-muted" style="font-size:0.68rem;">វិក្កយបត្រ</span>
                        </div>
                    </div>

                    {{-- Unpaid --}}
                    <div class="col-6 col-sm-3">
                        <div class="status-mini-card" style="background:#fffbeb; border-color:#fde68a;">
                            <span class="badge bg-warning text-dark mb-1.5 px-1.5 py-0.5" style="font-size:0.7rem;">
                                <i class="bi bi-clock-fill me-1"></i>មិនទាន់បង់
                            </span>
                            <div class="fw-bold fs-5 text-warning mb-0" style="color:#d97706 !important; font-family: var(--font-heading); line-height: 1.2;">{{ $unpaidCount }}</div>
                            <span class="text-muted" style="font-size:0.68rem;">វិក្កយបត្រ</span>
                        </div>
                    </div>

                    {{-- Cancelled --}}
                    <div class="col-6 col-sm-3">
                        <div class="status-mini-card" style="background:#f8fafc; border-color:#e2e8f0;">
                            <span class="badge bg-secondary text-white mb-1.5 px-1.5 py-0.5" style="font-size:0.7rem;">
                                <i class="bi bi-x-circle-fill me-1"></i>បានបោះបង់
                            </span>
                            <div class="fw-bold fs-5 text-secondary mb-0" style="font-family: var(--font-heading); line-height: 1.2;">{{ $cancelledCount }}</div>
                            <span class="text-muted" style="font-size:0.68rem;">វិក្កយបត្រ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expenses by Category Card --}}
        <div class="col-12 col-lg-6">
            <div class="card fin-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2.5">
                    <h6 class="fw-bold text-dark mb-0" style="font-family: var(--font-heading); font-size: 0.95rem;">ការចំណាយតាមប្រភេទ</h6>
                    <a href="{{ route('school-expenses.index') }}" class="btn btn-sm btn-ghost p-1 px-2 text-decoration-none" style="font-size: 0.78rem;">
                        មើលទាំងអស់ <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                @if($expensesByCategory->isEmpty())
                    <div class="text-center py-3">
                        <i class="bi bi-wallet2 text-muted fs-3 d-block mb-1"></i>
                        <p class="text-muted small mb-0">មិនទាន់មានការចំណាយប្រតិបត្តិការត្រូវបានកត់ត្រានៅឡើយទេ។</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-2">
                        @php
                            $maxExpense = $expensesByCategory->max('total_amount') ?: 1;
                        @endphp
                        @foreach($expensesByCategory->take(5) as $catExpense)
                            @php
                                $pct = round(($catExpense->total_amount / $maxExpense) * 100);
                            @endphp
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 0.8125rem;">
                                    <span class="fw-semibold text-dark d-flex align-items-center gap-1.5">
                                        <span class="badge rounded-circle p-1 bg-danger-subtle text-danger" style="width:16px;height:16px;font-size:0.55rem;display:inline-flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-tag-fill"></i>
                                        </span>
                                        {{ $catExpense->name }}
                                    </span>
                                    <div class="text-end currency-display" 
                                         data-usd="${{ number_format($catExpense->total_amount, 2) }}" 
                                         data-khr="{{ number_format(round($catExpense->total_amount * 4100, -2)) }} ៛"
                                         data-sec-usd="(${{ number_format($catExpense->total_amount, 2) }})"
                                         data-sec-khr="({{ number_format(round($catExpense->total_amount * 4100, -2)) }} ៛)">
                                        <span class="fw-bold text-dark curr-primary">${{ number_format($catExpense->total_amount, 2) }}</span>
                                        <span class="text-muted ms-1 curr-secondary" style="font-size:0.7rem;">({{ number_format(round($catExpense->total_amount * 4100, -2)) }} ៛)</span>
                                    </div>
                                </div>
                                <div class="cat-progress-bar">
                                    <div style="width: {{ $pct }}%; height: 100%; background: linear-gradient(90deg, #f43f5e, #fb7185); border-radius: 0px;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 3. Recent Transactions: Invoices & Payments --}}
    <div class="row mb-3" style="--bs-gutter-x: 0.85rem; --bs-gutter-y: 0.85rem;">
        {{-- Recent Invoices --}}
        <div class="col-12 col-lg-6">
            <div class="card fin-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2.5">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-1.5" style="font-family: var(--font-heading); font-size: 0.95rem;">
                        <i class="bi bi-receipt text-primary"></i>វិក្កយបត្រថ្មីៗ
                    </h6>
                    <a href="{{ route('student-invoices.index') }}" class="btn btn-sm btn-ghost p-1 px-2 text-decoration-none" style="font-size: 0.78rem;">
                        មើលទាំងអស់ <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                @if($recentInvoices->isEmpty())
                    <div class="text-center py-3">
                        <i class="bi bi-receipt text-muted fs-3 d-block mb-1"></i>
                        <p class="text-muted small mb-0">មិនទាន់មានវិក្កយបត្រត្រូវបានបង្កើតនៅឡើយទេ។</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0" style="font-size: 0.8125rem;">
                            <thead>
                                <tr class="text-muted" style="border-bottom: 2px solid var(--border-soft); font-size:0.75rem;">
                                    <th class="ps-0 py-1.5">វិក្កយបត្រ</th>
                                    <th class="py-1.5">សិស្ស</th>
                                    <th class="py-1.5">ទឹកប្រាក់សុទ្ធ</th>
                                    <th class="py-1.5">ប្រាក់នៅសល់</th>
                                    <th class="text-end pe-0 py-1.5">ស្ថានភាព</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $inv)
                                    <tr>
                                        <td class="ps-0 py-1.5">
                                            <a href="{{ route('student-invoices.show', $inv->id) }}" class="fw-bold text-decoration-none" style="color: var(--color-brand-800);">
                                                {{ $inv->invoice_number }}
                                            </a>
                                        </td>
                                        <td class="py-1.5">
                                            <div class="d-flex align-items-center gap-1.5">
                                                <span class="student-avatar-xs" style="background:#fef3c7; color:#b45309;">
                                                    {{ mb_substr($inv->student?->user?->name ?? 'S', 0, 1) }}
                                                </span>
                                                <div>
                                                    <span class="fw-medium text-dark d-block text-truncate" style="max-width:140px;">
                                                        {{ $inv->student?->user?->name ?? 'N/A' }}
                                                    </span>
                                                    <small class="text-muted" style="font-size:0.68rem;">{{ $inv->student?->admission_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-1.5">
                                            <div class="currency-display" data-usd="${{ number_format($inv->net_amount, 2) }}" data-khr="{{ number_format(round($inv->net_amount * 4100, -2)) }} ៛">
                                                <div class="fw-semibold text-dark curr-primary">${{ number_format($inv->net_amount, 2) }}</div>
                                                <div class="text-muted curr-secondary" style="font-size:.68rem;">{{ number_format(round($inv->net_amount * 4100, -2)) }} ៛</div>
                                            </div>
                                        </td>
                                        <td class="py-1.5">
                                            @if($inv->balance > 0)
                                                <div class="currency-display" data-usd="${{ number_format($inv->balance, 2) }}" data-khr="{{ number_format(round($inv->balance * 4100, -2)) }} ៛">
                                                    <div class="fw-bold text-warning curr-primary" style="color:#d97706 !important;">${{ number_format($inv->balance, 2) }}</div>
                                                    <div class="text-muted curr-secondary" style="font-size:.68rem;">{{ number_format(round($inv->balance * 4100, -2)) }} ៛</div>
                                                </div>
                                            @else
                                                <div class="currency-display" data-usd="$0.00" data-khr="0 ៛">
                                                    <span class="text-muted small curr-primary">$0.00</span>
                                                    <span class="d-none curr-secondary">0 ៛</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-end pe-0 py-1.5">
                                            @if($inv->status === 'paid')
                                                <span class="status-badge status-active" style="font-size:0.7rem; padding: 0.2rem 0.5rem;">បានបង់រួច</span>
                                            @elseif($inv->status === 'partial')
                                                <span class="status-badge status-pending" style="font-size:0.7rem; padding: 0.2rem 0.5rem;">បង់ខ្លះ</span>
                                            @elseif($inv->status === 'cancelled')
                                                <span class="status-badge status-unknown" style="font-size:0.7rem; padding: 0.2rem 0.5rem;">បានបោះបង់</span>
                                            @else
                                                <span class="status-badge status-inactive" style="font-size:0.7rem; padding: 0.2rem 0.5rem;">មិនទាន់បង់</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Payments --}}
        <div class="col-12 col-lg-6">
            <div class="card fin-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2.5">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-1.5" style="font-family: var(--font-heading); font-size: 0.95rem;">
                        <i class="bi bi-credit-card text-success"></i>ការបង់ប្រាក់ថ្មីៗ
                    </h6>
                    <a href="{{ route('student-payments.index') }}" class="btn btn-sm btn-ghost p-1 px-2 text-decoration-none" style="font-size: 0.78rem;">
                        មើលទាំងអស់ <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>

                @if($recentPayments->isEmpty())
                    <div class="text-center py-3">
                        <i class="bi bi-credit-card text-muted fs-3 d-block mb-1"></i>
                        <p class="text-muted small mb-0">មិនទាន់មានបង្កាន់ដៃបង់ប្រាក់ត្រូវបានកត់ត្រានៅឡើយទេ។</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0" style="font-size: 0.8125rem;">
                            <thead>
                                <tr class="text-muted" style="border-bottom: 2px solid var(--border-soft); font-size:0.75rem;">
                                    <th class="ps-0 py-1.5">កាលបរិច្ឆេទ</th>
                                    <th class="py-1.5">វិក្កយបត្រ</th>
                                    <th class="py-1.5">សិស្ស</th>
                                    <th class="py-1.5">វិធីសាស្ត្រ</th>
                                    <th class="text-end pe-0 py-1.5">ចំនួនទឹកប្រាក់</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $pmt)
                                    @php
                                        $methodName = $pmt->payment_method?->name ?? 'Direct Cash';
                                        $methodKhmer = [
                                            'Direct Cash'   => 'សាច់ប្រាក់សុទ្ធ',
                                            'Cash'          => 'សាច់ប្រាក់',
                                            'Bank Transfer' => 'ផ្ទេរតាមធនាគារ',
                                            'Check'         => 'មូលប្បទានប័ត្រ',
                                        ][$methodName] ?? $methodName;
                                    @endphp
                                    <tr>
                                        <td class="ps-0 py-1.5 text-muted" style="font-size:0.75rem;">
                                            {{ $pmt->payment_date?->format('d/m/Y') ?? '—' }}
                                        </td>
                                        <td class="py-1.5">
                                            <a href="{{ route('student-invoices.show', $pmt->student_invoice_id) }}" class="fw-bold text-decoration-none" style="color: var(--color-brand-800);">
                                                #INV-{{ $pmt->student_invoice_id }}
                                            </a>
                                        </td>
                                        <td class="py-1.5">
                                            <span class="fw-medium text-dark d-block text-truncate" style="max-width:130px;">
                                                {{ $pmt->student_invoice?->student?->user?->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-1.5">
                                            <span class="badge" style="background:#f1f5f9; color:#334155; font-size:0.7rem; border:1px solid #e2e8f0; padding: 0.2rem 0.4rem;">
                                                {{ $methodKhmer }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-0 py-1.5">
                                            @php
                                                $pmtKhr = number_format($pmt->calculated_amount_khr ?? round($pmt->amount * 4100, -2)) . ' ៛';
                                            @endphp
                                            <div class="currency-display" data-usd="${{ number_format($pmt->amount, 2) }}" data-khr="{{ $pmtKhr }}">
                                                <div class="fw-bold text-success curr-primary">${{ number_format($pmt->amount, 2) }}</div>
                                                <div class="text-muted curr-secondary" style="font-size:.68rem;">{{ $pmtKhr }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ─── Currency Switcher Logic ($ USD ⇄ ៛ KHR) ───
let currentCurrencyMode = localStorage.getItem('invoice_currency_display_mode') || 'usd';

function applyCurrencyMode(mode) {
    currentCurrencyMode = mode;
    localStorage.setItem('invoice_currency_display_mode', mode);

    const btnText = document.getElementById('currencyModeText');
    if (btnText) {
        btnText.innerHTML = mode === 'usd' 
            ? 'បង្ហាញ៖ <strong class="text-primary">$ USD</strong>' 
            : 'បង្ហាញ៖ <strong class="text-success">៛ KHR</strong>';
    }

    document.querySelectorAll('.currency-display').forEach(el => {
        const usdVal = el.getAttribute('data-usd');
        const khrVal = el.getAttribute('data-khr');
        const secUsdVal = el.getAttribute('data-sec-usd') || usdVal;
        const secKhrVal = el.getAttribute('data-sec-khr') || khrVal;
        const primaryEl = el.querySelector('.curr-primary');
        const secondaryEl = el.querySelector('.curr-secondary');

        if (primaryEl && secondaryEl && usdVal && khrVal) {
            if (mode === 'khr') {
                primaryEl.textContent = khrVal;
                secondaryEl.textContent = secUsdVal;
            } else {
                primaryEl.textContent = usdVal;
                secondaryEl.textContent = secKhrVal;
            }
        }
    });
}

function toggleCurrencyDisplay() {
    applyCurrencyMode(currentCurrencyMode === 'usd' ? 'khr' : 'usd');
}

document.addEventListener('DOMContentLoaded', () => {
    applyCurrencyMode(currentCurrencyMode);
});
</script>
@endpush
