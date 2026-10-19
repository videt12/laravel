@extends('layouts.app')

@section('title', 'វិក្កយបត្រសិស្ស និងបំណុលត្រូវប្រមូល — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">វិក្កយបត្រសិស្ស</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ─── Screen Table & Card Styles ─── */
.table-card {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,.03);
    width: 100% !important;
}
.table-custom {
    width: 100% !important;
    margin: 0;
    min-width: 100%;
}
.table-custom thead th {
    background: #f8fafc;
    color: var(--text-dark, #1e293b);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.45rem 0.65rem;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.table-custom tbody td {
    padding: 0.45rem 0.65rem;
    font-size: 0.76rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}
.table-custom tbody tr:hover td {
    background-color: #f8fafc;
}

/* ─── Financial Metric Cards ─── */
.metric-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.55rem 0.75rem;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.metric-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}
.metric-card-interactive {
    cursor: pointer;
    user-select: none;
    transition: all 0.15s ease-in-out;
}
.metric-card-interactive:hover {
    border-color: #2563eb !important;
    background-color: #f8faff !important;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.08) !important;
}
.metric-icon-wrap {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ─── Uniform Filter Controls ─── */
.filter-ctrl {
    height: 31px !important;
    font-size: 0.76rem !important;
    line-height: 1.4 !important;
    padding: 0.25rem 0.5rem !important;
    border: 1px solid #ced4da !important;
    border-radius: 5px !important;
    background-color: #ffffff !important;
    color: #1e293b !important;
    box-shadow: none !important;
    vertical-align: middle !important;
}
.filter-select {
    padding-right: 1.7rem !important;
}

/* ─── Print Styles ─── */
@media print {
    @page {
        size: A4 portrait;
        margin: 12mm 15mm 15mm 15mm;
    }
    html, body {
        background: #ffffff !important;
        color: #0f172a !important;
        font-family: 'Kantumruy Pro', 'Siemreap', sans-serif !important;
        font-size: 9pt !important;
        line-height: 1.4 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .sidebar, .navbar, .header, header, .breadcrumb, .d-print-none, .btn, form, .pagination {
        display: none !important;
    }
    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }

    /* Print Header */
    .print-header {
        display: block !important;
        margin-bottom: 1rem !important;
        border-bottom: 2px solid #0f172a !important;
        padding-bottom: 0.75rem !important;
    }
    .print-header h2 {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        margin: 0 !important;
        color: #0f172a !important;
    }

    /* Print Summary Strip */
    .print-summary-box {
        display: table !important;
        width: 100% !important;
        border: 1px solid #cbd5e1 !important;
        border-collapse: collapse !important;
        margin-bottom: 1rem !important;
        page-break-inside: avoid !important;
    }
    .print-summary-box th {
        background: #f8fafc !important;
        color: #475569 !important;
        font-size: 8pt !important;
        font-weight: 600 !important;
        padding: 6px 10px !important;
        border: 1px solid #cbd5e1 !important;
        text-align: center !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .print-summary-box td {
        font-size: 9.5pt !important;
        font-weight: 700 !important;
        padding: 6px 10px !important;
        border: 1px solid #cbd5e1 !important;
        text-align: center !important;
        color: #0f172a !important;
    }

    /* Table Container - Prevent scrollbars in print */
    .table-responsive {
        overflow: visible !important;
        overflow-x: visible !important;
        display: block !important;
        width: 100% !important;
    }
    .table-card {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        margin: 0 !important;
    }
    .table-custom {
        width: 100% !important;
        border: 1px solid #cbd5e1 !important;
        border-collapse: collapse !important;
        font-size: 8pt !important;
        margin-bottom: 1rem !important;
    }
    .table-custom thead th {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
        font-size: 7.8pt !important;
        font-weight: 700 !important;
        padding: 5px 6px !important;
        white-space: nowrap !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .table-custom tbody td {
        border: 1px solid #e2e8f0 !important;
        padding: 5px 6px !important;
        font-size: 8pt !important;
        white-space: normal !important;
    }
    .badge {
        border: 1px solid #cbd5e1 !important;
        padding: 2px 4px !important;
        font-size: 7.2pt !important;
        color: #0f172a !important;
        background: transparent !important;
    }

    /* Print Signatures Block */
    .print-signatures {
        display: block !important;
        margin-top: 2rem !important;
        page-break-inside: avoid !important;
    }
}
</style>
@endpush

@section('content')
@php
    $appSchool = \App\Models\Core\School::first();
    $logoUrl = ($appSchool && $appSchool->logo_path && file_exists(public_path($appSchool->logo_path)))
        ? asset($appSchool->logo_path)
        : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : null);
@endphp

<div class="container-fluid px-0">

    {{-- ═══ 1. OFFICIAL PRINT HEADER (Only visible when printing) ═══ --}}
    <div class="print-header d-none d-print-block">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-3">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" style="height: 52px; width: auto; object-fit: contain;">
                @endif
                <div>
                    <h2 class="fw-bold mb-0 text-dark" style="font-family: 'Siemreap', 'Kantumruy Pro', sans-serif;">
                        {{ $appSchool?->name ?? 'ប្រព័ន្ធគ្រប់គ្រងសាលារៀន' }}
                    </h2>
                    <div class="text-muted" style="font-size: 0.85rem;">
                        របាយការណ៍វិក្កយបត្រសិស្ស និងបំណុលត្រូវប្រមូល (Student Invoices & Receivables Report)
                    </div>
                </div>
            </div>
            <div class="text-end small" style="font-size: 0.78rem; line-height: 1.45; color: #475569;">
                <div>កាលបរិច្ឆេទបោះពុម្ព៖ <strong class="text-dark">{{ now()->format('d/m/Y H:i') }}</strong></div>
                <div>អ្នកចេញរបាយការណ៍៖ <strong class="text-dark">{{ auth()->user()->name ?? 'រដ្ឋបាល' }}</strong></div>
                <div>ទិន្នន័យសរុប៖ <strong class="text-dark">{{ number_format($totalInvoicesCount) }} វិក្កយបត្រ</strong></div>
            </div>
        </div>
    </div>

    {{-- ═══ 2. SCREEN TOP HEADER (Hidden when printing) ═══ --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-2.5 pb-2 border-bottom d-print-none">
        <div>
            <h1 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">វិក្កយបត្រសិស្ស និងបំណុលត្រូវប្រមូល</h1>
            <p class="text-muted small mb-0 mt-0.5" style="font-size: 0.76rem;">
                គ្រប់គ្រងការចេញវិក្កយបត្រ តាមដានបំណុលត្រូវទូទាត់ និងដំណើរការប្រមូលប្រាក់
            </p>
        </div>
        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <button type="button" onclick="toggleCurrencyDisplay()" id="btnCurrencyToggle" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 shadow-2xs" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 5px;" title="ចុចប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <i class="bi bi-arrow-left-right text-primary"></i>
                <span id="currencyModeText">រូបិយប័ណ្ណ៖ <strong class="text-primary">$ USD</strong></span>
            </button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 5px;">
                <i class="bi bi-file-earmark-spreadsheet"></i><span>ទាញយក CSV</span>
            </a>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 5px;">
                <i class="bi bi-printer"></i><span>បោះពុម្ពរបាយការណ៍</span>
            </button>
            <a href="{{ route('student-invoices.create') }}" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-1 shadow-sm" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.65rem; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 5px;">
                <i class="bi bi-plus-lg"></i><span>បង្កើតវិក្កយបត្រ</span>
            </a>
        </div>
    </div>

    {{-- ═══ 3. FINANCIAL SUMMARY FOR PRINT (Clean, executive 1-line strip) ═══ --}}
    <table class="print-summary-box d-none d-print-table">
        <thead>
            <tr>
                <th style="width: 25%;">វិក្កយបត្រសរុប</th>
                <th style="width: 25%;">ទឹកប្រាក់វិក្កយបត្រសរុប</th>
                <th style="width: 25%;">ចំណូលទទួលបានសរុប</th>
                <th style="width: 25%;">សមតុល្យនៅសល់មិនទាន់បង់</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($totalInvoicesCount) }} វិក្កយបត្រ</td>
                <td>
                    <div class="currency-display" data-usd="${{ number_format($totalInvoicedAmount, 2) }}" data-khr="{{ number_format(round($totalInvoicedAmount * 4100, -2)) }} ៛">
                        <span class="curr-primary">${{ number_format($totalInvoicedAmount, 2) }}</span>
                        <span class="small text-muted fw-normal d-block curr-secondary" style="font-size: 7.5pt;">{{ number_format(round($totalInvoicedAmount * 4100, -2)) }} ៛</span>
                    </div>
                </td>
                <td style="color: #059669;">
                    <div class="currency-display" data-usd="${{ number_format($totalCollectedAmount, 2) }}" data-khr="{{ number_format(round($totalCollectedAmount * 4100, -2)) }} ៛">
                        <span class="curr-primary">${{ number_format($totalCollectedAmount, 2) }}</span>
                        <span class="small text-muted fw-normal d-block curr-secondary" style="font-size: 7.5pt;">{{ number_format(round($totalCollectedAmount * 4100, -2)) }} ៛</span>
                    </div>
                </td>
                <td style="color: #dc2626;">
                    <div class="currency-display" data-usd="${{ number_format($totalOutstandingAmount, 2) }}" data-khr="{{ number_format(round($totalOutstandingAmount * 4100, -2)) }} ៛">
                        <span class="curr-primary">${{ number_format($totalOutstandingAmount, 2) }}</span>
                        <span class="small text-muted fw-normal d-block curr-secondary" style="font-size: 7.5pt;">{{ number_format(round($totalOutstandingAmount * 4100, -2)) }} ៛</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ═══ 4. FINANCIAL SUMMARY CARDS FOR SCREEN (Hidden on print) ═══ --}}
    <div class="row g-2 mb-2.5 d-print-none">
        <div class="col-6 col-md-3">
            <div class="metric-card h-100 d-flex align-items-center">
                <div class="metric-icon-wrap me-2" style="background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                    <i class="bi bi-receipt" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.72rem;">វិក្កយបត្រសរុប</div>
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">{{ number_format($totalInvoicesCount) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card metric-card-interactive h-100 d-flex align-items-center" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div class="metric-icon-wrap me-2" style="background: rgba(100, 116, 139, 0.1); color: #475569;">
                    <i class="bi bi-calculator" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                        <span>ទឹកប្រាក់វិក្កយបត្រសរុប</span>
                        <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalInvoicedAmount, 2) }}" data-khr="{{ number_format(round($totalInvoicedAmount * 4100, -2)) }} ៛">
                        <h5 class="fw-bold text-dark mb-0 curr-primary" style="font-size: 1.05rem;">${{ number_format($totalInvoicedAmount, 2) }}</h5>
                        <div class="text-muted curr-secondary" style="font-size: 0.65rem;">{{ number_format(round($totalInvoicedAmount * 4100, -2)) }} ៛</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card metric-card-interactive h-100 d-flex align-items-center" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div class="metric-icon-wrap me-2" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                    <i class="bi bi-check-circle-fill" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                        <span>ចំណូលទទួលបានសរុប</span>
                        <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalCollectedAmount, 2) }}" data-khr="{{ number_format(round($totalCollectedAmount * 4100, -2)) }} ៛">
                        <h5 class="fw-bold text-success mb-0 curr-primary" style="font-size: 1.05rem;">${{ number_format($totalCollectedAmount, 2) }}</h5>
                        <div class="text-success curr-secondary" style="font-size: 0.65rem; opacity: .85;">{{ number_format(round($totalCollectedAmount * 4100, -2)) }} ៛</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card metric-card-interactive h-100 d-flex align-items-center" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div class="metric-icon-wrap me-2" style="background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                        <span>សមតុល្យនៅសល់មិនទាន់បង់</span>
                        <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalOutstandingAmount, 2) }}" data-khr="{{ number_format(round($totalOutstandingAmount * 4100, -2)) }} ៛">
                        <h5 class="fw-bold text-danger mb-0 curr-primary" style="font-size: 1.05rem;">${{ number_format($totalOutstandingAmount, 2) }}</h5>
                        <div class="text-danger curr-secondary" style="font-size: 0.65rem; opacity: .85;">{{ number_format(round($totalOutstandingAmount * 4100, -2)) }} ៛</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ 5. FILTER CARD (Hidden on print) ═══ --}}
    <div class="card border border-light-subtle shadow-xs rounded-2 bg-white mb-2 d-print-none">
        <div class="card-body py-1.5 px-2.5">
            <form action="{{ route('student-invoices.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-1.5">
                <div style="flex: 0 0 180px; width: 180px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm filter-ctrl w-100" placeholder="ស្វែងរក #វិក្កយបត្រ សិស្ស...">
                </div>
                <div style="flex: 0 0 140px; width: 140px;">
                    <select name="academic_year_id" class="form-select form-select-sm filter-ctrl filter-select w-100">
                        <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 0 0 120px; width: 120px;">
                    <select name="class_id" class="form-select form-select-sm filter-ctrl filter-select w-100">
                        <option value="">ថ្នាក់ទាំងអស់</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 0 0 135px; width: 135px;">
                    <select name="status" class="form-select form-select-sm filter-ctrl filter-select w-100">
                        <option value="">ស្ថានភាពទាំងអស់</option>
                        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>មិនទាន់បង់</option>
                        <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>បង់ខ្លះ</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>បានបង់រួច</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>ហួសកាលកំណត់</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>បានបោះបង់</option>
                    </select>
                </div>
                <div style="flex: 0 0 130px; width: 130px;">
                    <input type="date" name="due_date_from" value="{{ request('due_date_from') }}" class="form-control form-control-sm filter-ctrl w-100" title="ថ្ងៃផុតកំណត់ ចាប់ពីថ្ងៃ">
                </div>
                <div style="flex: 0 0 130px; width: 130px;">
                    <input type="date" name="due_date_to" value="{{ request('due_date_to') }}" class="form-control form-control-sm filter-ctrl w-100" title="ថ្ងៃផុតកំណត់ ដល់ថ្ងៃ">
                </div>
                <div class="d-flex gap-1 ms-auto ms-md-0">
                    <button type="submit" class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center" style="height: 31px; width: 33px; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 5px; font-size: 0.82rem;" title="ចម្រាញ់">
                        <i class="bi bi-filter"></i>
                    </button>
                    @if(request()->hasAny(['search', 'status', 'academic_year_id', 'class_id', 'due_date_from', 'due_date_to', 'outstanding']))
                        <a href="{{ route('student-invoices.index') }}" class="btn btn-sm btn-light border d-inline-flex align-items-center justify-content-center" style="height: 31px; width: 33px; border-radius: 5px; font-size: 0.75rem;" title="សម្អាតតម្រង">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
            <div class="d-flex align-items-center gap-1.5 mt-1.5 pt-1.5 border-top flex-wrap">
                <span class="text-muted me-1" style="font-size: 0.72rem;">តម្រងរហ័ស៖</span>
                <a href="{{ route('student-invoices.index') }}" class="badge {{ !request()->has('status') && !request()->has('outstanding') ? 'bg-primary' : 'bg-light text-dark border' }} text-decoration-none py-1 px-2" style="font-size: 0.7rem; border-radius: 4px;">
                    វិក្កយបត្រទាំងអស់
                </a>
                <a href="{{ route('student-invoices.index', ['outstanding' => 1]) }}" class="badge {{ request('outstanding') == 1 ? 'bg-warning text-dark' : 'bg-light text-dark border' }} text-decoration-none py-1 px-2" style="font-size: 0.7rem; border-radius: 4px;">
                    <i class="bi bi-exclamation-circle me-1"></i>នៅសល់មិនទាន់បង់
                </a>
                <a href="{{ route('student-invoices.index', ['status' => 'overdue']) }}" class="badge {{ request('status') === 'overdue' ? 'bg-danger text-white' : 'bg-light text-dark border' }} text-decoration-none py-1 px-2" style="font-size: 0.7rem; border-radius: 4px;">
                    <i class="bi bi-clock-history me-1"></i>ហួសកាលកំណត់
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ 6. INVOICES TABLE ═══ --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0 w-100">
                <thead>
                    <tr>
                        <th class="ps-2.5" style="width: 120px;">លេខវិក្កយបត្រ</th>
                        <th>សិស្ស</th>
                        <th>ថ្នាក់</th>
                        <th>ឆ្នាំសិក្សា</th>
                        <th>ថ្ងៃផុតកំណត់</th>
                        <th class="text-end">ទឹកប្រាក់សុទ្ធ</th>
                        <th class="text-end">បានបង់</th>
                        <th class="text-end">នៅសល់</th>
                        <th class="text-center" style="width: 90px;">ស្ថានភាព</th>
                        <th class="text-end pe-2.5 d-print-none" style="width: 120px;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                        @php
                            $isOverdue = $inv->due_date && $inv->due_date->isPast() && in_array($inv->status, ['unpaid', 'partial']);
                            $academicYearName = $inv->fee_structure?->academic_year?->name ?? $inv->student?->academicYear?->name ?? '—';
                        @endphp
                        <tr>
                            <td class="ps-2.5">
                                <a href="{{ route('student-invoices.show', $inv->id) }}" class="fw-bold text-decoration-none font-monospace text-dark">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $inv->student?->user?->name ?? '—' }}</div>
                                <div class="small text-muted font-monospace" style="font-size: 0.72rem;">{{ $inv->student?->admission_number }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">
                                    {{ $inv->student?->schoolClass?->name ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted">{{ $academicYearName }}</span>
                            </td>
                            <td class="small {{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}">
                                {{ $inv->due_date?->format('d/m/Y') ?? '—' }}
                                @if($isOverdue)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-1" style="font-size: 0.65rem;">ហួសកំណត់</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="currency-display" data-usd="${{ number_format($inv->net_amount, 2) }}" data-khr="{{ number_format(round($inv->net_amount * 4100, -2)) }} ៛">
                                    <div class="fw-bold text-dark curr-primary">${{ number_format($inv->net_amount, 2) }}</div>
                                    <div class="text-muted curr-secondary" style="font-size: .7rem;">{{ number_format(round($inv->net_amount * 4100, -2)) }} ៛</div>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="currency-display" data-usd="${{ number_format($inv->total_paid, 2) }}" data-khr="{{ number_format(round($inv->total_paid * 4100, -2)) }} ៛">
                                    <div class="text-success fw-semibold curr-primary">${{ number_format($inv->total_paid, 2) }}</div>
                                    <div class="text-muted curr-secondary" style="font-size: .7rem;">{{ number_format(round($inv->total_paid * 4100, -2)) }} ៛</div>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="currency-display" data-usd="${{ number_format($inv->balance, 2) }}" data-khr="{{ number_format(round($inv->balance * 4100, -2)) }} ៛">
                                    <div class="fw-bold curr-primary {{ $inv->balance > 0 ? ($isOverdue ? 'text-danger' : 'text-warning') : 'text-muted' }}">
                                        ${{ number_format($inv->balance, 2) }}
                                    </div>
                                    <div class="text-muted curr-secondary" style="font-size: .7rem;">{{ number_format(round($inv->balance * 4100, -2)) }} ៛</div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($inv->status === 'paid')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">បានបង់រួច</span>
                                @elseif($isOverdue)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 fw-bold" style="font-size: 0.72rem;">ហួសកាលកំណត់</span>
                                @elseif($inv->status === 'partial')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5" style="font-size: 0.72rem;">បង់ខ្លះ</span>
                                @elseif($inv->status === 'cancelled')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0.5" style="font-size: 0.72rem;">បានបោះបង់</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5" style="font-size: 0.72rem;">មិនទាន់បង់</span>
                                @endif
                            </td>
                            <td class="text-end pe-2.5 d-print-none">
                                <div class="d-inline-flex gap-1">
                                    @if($inv->student_id)
                                        <a href="{{ route('students.show', $inv->student_id) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-1.5" style="font-size: 0.74rem; border-radius: 4px;" title="មើលព័ត៌មានសិស្ស">
                                            <i class="bi bi-person"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('student-invoices.show', $inv->id) }}" class="btn btn-sm btn-outline-primary py-0.5 px-1.5" style="font-size: 0.74rem; border-radius: 4px;" title="មើលវិក្កយបត្រ">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($inv->balance > 0 && $inv->status !== 'cancelled')
                                        <a href="{{ route('student-payments.create', ['invoice_id' => $inv->id]) }}" class="btn btn-sm text-white py-0.5 px-1.5" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); font-size: 0.74rem; border-radius: 4px;" title="កត់ត្រាការបង់ប្រាក់">
                                            <i class="bi bi-cash-coin me-1"></i>បង់
                                        </a>
                                    @endif
                                    @if($inv->student_payments->count() == 0 && $inv->status !== 'cancelled')
                                        <a href="{{ route('student-invoices.edit', $inv->id) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-1.5" style="font-size: 0.74rem; border-radius: 4px;" title="កែសម្រួល">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger py-0.5 px-1.5" style="font-size: 0.74rem; border-radius: 4px;" title="លុប"
                                                onclick="openDeleteInvoiceModal('{{ route('student-invoices.destroy', $inv->id) }}', '{{ addslashes($inv->invoice_number) }}', '{{ addslashes($inv->student?->full_name_khmer ?? $inv->student?->full_name_latin ?? '') }}', '{{ number_format($inv->total_amount, 2) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-3 text-muted" style="font-size: 0.78rem;">
                                <i class="bi bi-receipt-cutoff fs-4 d-block mb-1 opacity-50"></i>
                                មិនមានវិក្កយបត្រសិស្សដែលត្រូវនឹងលក្ខខណ្ឌជ្រើសរើសឡើយ។
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="card-footer bg-white border-top py-2 px-3 d-print-none">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    {{-- ═══ 7. OFFICIAL SIGNATURES BLOCK (Only visible when printing) ═══ --}}
    <div class="print-signatures d-none d-print-block">
        <div class="row text-center" style="margin-top: 2.5rem; font-size: 8.5pt;">
            <div class="col-4">
                <div class="fw-bold mb-1">អ្នករៀបចំរបាយការណ៍</div>
                <div class="text-muted" style="font-size: 7.5pt;">(ហត្ថលេខា និងឈ្មោះ)</div>
                <div style="height: 60px;"></div>
                <div class="fw-semibold text-dark">{{ auth()->user()->name ?? '................................' }}</div>
            </div>
            <div class="col-4">
                <div class="fw-bold mb-1">ប្រធានគណនេយ្យ / ហិរញ្ញវត្ថុ</div>
                <div class="text-muted" style="font-size: 7.5pt;">(ហត្ថលេខា និងឈ្មោះ)</div>
                <div style="height: 60px;"></div>
                <div class="fw-semibold text-dark">................................</div>
            </div>
            <div class="col-4">
                <div class="text-muted mb-1" style="font-size: 7.5pt;">ថ្ងៃទី....... ខែ....... ឆ្នាំ២០...</div>
                <div class="fw-bold mb-1">នាយកសាលា</div>
                <div class="text-muted" style="font-size: 7.5pt;">(ហត្ថលេខា និងត្រា)</div>
                <div style="height: 50px;"></div>
                <div class="fw-semibold text-dark">................................</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ 8. DELETE CONFIRMATION MODAL ═══ --}}
<div class="modal fade" id="modalDeleteInvoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deleteInvoiceForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបវិក្កយបត្រ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបវិក្កយបត្រលេខ <strong id="delInvoiceNum" class="text-dark"></strong> មែនទេ?
                    </p>
                    <div class="p-2 mb-2 rounded bg-light border text-start" style="font-size: 0.78rem;">
                        <div class="text-muted d-flex justify-content-between mb-1">
                            <span>សិស្ស:</span>
                            <span id="delInvoiceStudent" class="fw-bold text-dark"></span>
                        </div>
                        <div class="text-muted d-flex justify-content-between">
                            <span>សរុបទឹកប្រាក់:</span>
                            <span id="delInvoiceTotal" class="fw-bold text-danger"></span>
                        </div>
                    </div>
                    <div class="text-danger" style="font-size: 0.73rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានឡើយ។
                    </div>
                </div>
                <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 4px; font-size: 0.82rem;" data-bs-dismiss="modal">
                        បោះបង់
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger px-3.5" style="border-radius: 4px; font-size: 0.82rem;">
                        <i class="bi bi-trash me-1"></i>លុបចេញ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteInvoiceModal(actionUrl, invoiceNum, student, total) {
    const form = document.getElementById('deleteInvoiceForm');
    if (form) form.action = actionUrl;

    const numEl = document.getElementById('delInvoiceNum');
    if (numEl) numEl.textContent = invoiceNum || '';

    const studentEl = document.getElementById('delInvoiceStudent');
    if (studentEl) studentEl.textContent = student || '—';

    const totalEl = document.getElementById('delInvoiceTotal');
    if (totalEl) totalEl.textContent = total ? ('$' + total) : '—';

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteInvoice'));
    modal.show();
}

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
        const primaryEl = el.querySelector('.curr-primary');
        const secondaryEl = el.querySelector('.curr-secondary');

        if (primaryEl && secondaryEl && usdVal && khrVal) {
            if (mode === 'khr') {
                primaryEl.textContent = khrVal;
                secondaryEl.textContent = usdVal;
            } else {
                primaryEl.textContent = usdVal;
                secondaryEl.textContent = khrVal;
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
@endsection
