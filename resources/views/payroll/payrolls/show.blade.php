@extends('layouts.app')

@php
    $khmerMonths = [
        1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
        5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
        9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ',
    ];
    $khmerPeriod = isset($khmerMonths[$payroll->month]) ? 'ខែ' . $khmerMonths[$payroll->month] . ' ឆ្នាំ' . $payroll->year : $payroll->period;

    $statusLabels = [
        'draft' => 'ព្រាង (Draft)',
        'pending' => 'រង់ចាំអនុម័ត (Pending)',
        'approved' => 'បានអនុម័ត (Approved)',
        'paid' => 'បានទូទាត់ (Paid)',
        'cancelled' => 'បានបោះបង់ (Cancelled)',
    ];

    $paymentMethodLabels = [
        'bank_transfer' => 'ផ្ទេរតាមធនាគារ (Bank Transfer)',
        'cash' => 'សាច់ប្រាក់សុទ្ធ (Cash)',
        'cheque' => 'មូលប្បទានប័ត្រ (Cheque)',
    ];
@endphp

@section('title', 'ប្រាក់បៀវត្សរ៍ #' . $payroll->payroll_number . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}" class="text-decoration-none text-muted">ប្រាក់បៀវត្សរ៍</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $payroll->payroll_number }}</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.page-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
.payroll-number { font-size:1.3rem; font-weight:800; color:#111827; }
.payroll-period  { font-size:.85rem; color:#6b7280; }

.info-card { background:#fff; border:1px solid var(--border-soft,#e5e7eb); border-radius:var(--radius-lg,12px); padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,.04); }
.section-title { font-size:.85rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; margin-bottom:1rem; }
.info-row { display:flex; justify-content:space-between; padding:.45rem 0; font-size:.875rem; border-bottom:1px solid #f3f4f6; }
.info-row:last-child { border-bottom:none; }
.info-label { color:#6b7280; }
.info-value { font-weight:600; text-align:right; }

.breakdown-table th { background:#f8fafc; font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; }
.breakdown-table td { font-size:.875rem; vertical-align:middle; }
.total-row td { font-weight:700; font-size:.95rem; background:#f8fafc; }
.net-row td { font-weight:800; font-size:1.1rem; color:#1e40af; background:#eff6ff; }

.badge-status { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .9rem; border-radius:20px; font-size:.82rem; font-weight:600; }
.badge-draft    { background:#f3f4f6; color:#374151; }
.badge-pending  { background:#fef3c7; color:#92400e; }
.badge-approved { background:#dbeafe; color:#1e40af; }
.badge-paid     { background:#d1fae5; color:#065f46; }
.badge-cancelled{ background:#fee2e2; color:#991b1b; }

.timeline { list-style:none; padding:0; margin:0; }
.timeline-item { display:flex; gap:1rem; position:relative; padding-bottom:1.25rem; }
.timeline-item:last-child { padding-bottom:0; }
.timeline-dot { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; flex-shrink:0; margin-top:.1rem; }
.timeline-content { font-size:.82rem; }
.timeline-content strong { display:block; font-size:.85rem; }
.timeline-content span { color:#6b7280; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="payroll-number">
            {{ $payroll->payroll_number }}
            <span class="badge-status badge-{{ $payroll->status }} ms-2">{{ $statusLabels[$payroll->status] ?? ucfirst($payroll->status) }}</span>
        </div>
        <div class="payroll-period">{{ $khmerPeriod }} &mdash; {{ $payroll->employee?->user?->name ?? 'N/A' }}</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('payrolls.payslip', $payroll) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i>ប័ណ្ណបើកប្រាក់ (Payslip)
        </a>
        @if($payroll->isEditable())
            <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i>កែសម្រួល
            </a>
        @endif
        @if($payroll->status === 'draft')
            <form method="POST" action="{{ route('payrolls.submit', $payroll) }}" class="d-inline">
                @csrf
                <button class="btn btn-warning btn-sm fw-semibold">
                    <i class="bi bi-send me-1"></i>ដាក់ស្នើសុំការអនុម័ត
                </button>
            </form>
        @endif
        @if($payroll->status === 'pending')
            <form method="POST" action="{{ route('payrolls.approve', $payroll) }}" class="d-inline">
                @csrf
                <button class="btn btn-info btn-sm fw-semibold text-dark">
                    <i class="bi bi-check-circle me-1"></i>អនុម័ត
                </button>
            </form>
        @endif
        @if($payroll->status === 'approved')
            <button class="btn btn-success btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#payModal">
                <i class="bi bi-cash me-1"></i>កត់ត្រាថាបានទូទាត់
            </button>
        @endif
        @if(!$payroll->isPaid() && $payroll->status !== 'cancelled')
            <form method="POST" action="{{ route('payrolls.cancel', $payroll) }}" class="d-inline"
                  onsubmit="return confirm('តើអ្នកពិតជាចង់បោះបង់ប្រាក់បៀវត្សរ៍នេះមែនទេ?')">
                @csrf
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-x-circle me-1"></i>បោះបង់
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i><span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Left: employee + breakdown --}}
    <div class="col-lg-8">
        {{-- Employee info --}}
        <div class="info-card mb-4">
            <div class="section-title">ព័ត៌មានបុគ្គលិក (Employee Information)</div>
            <div class="row g-0">
                <div class="col-md-6">
                    <div class="info-row pe-3">
                        <span class="info-label">ឈ្មោះបុគ្គលិក (Name)</span>
                        <span class="info-value">{{ $payroll->employee?->user?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row pe-3">
                        <span class="info-label">អត្តលេខបុគ្គលិក (Employee Code)</span>
                        <span class="info-value">{{ $payroll->employee?->employee_code }}</span>
                    </div>
                    <div class="info-row pe-3">
                        <span class="info-label">ដេប៉ាតឺម៉ង់ (Department)</span>
                        <span class="info-value">{{ $payroll->employee?->employee_department?->name ?? '—' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <span class="info-label">តួនាទី / មុខតំណែង (Position)</span>
                        <span class="info-value">{{ $payroll->employee?->employee_position?->name ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">រចនាសម្ព័ន្ធប្រាក់ខែ (Salary Structure)</span>
                        <span class="info-value">{{ $payroll->salaryStructure?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ការបរិច្ឆេទ / ខែបើក (Period)</span>
                        <span class="info-value">{{ $khmerPeriod }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Earnings --}}
        <div class="info-card mb-4">
            <div class="section-title">ប្រាក់ចំណូល (Earnings)</div>
            <table class="table table-borderless breakdown-table mb-0">
                <thead>
                    <tr>
                        <th>សមាសភាគ (Component)</th>
                        <th>ការគណនា (Calculation)</th>
                        <th class="text-end">ចំនួនទឹកប្រាក់ (Amount)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ប្រាក់បៀវត្សគោល (Basic Salary)</td>
                        <td><span class="badge bg-secondary-subtle text-secondary-emphasis">ថេរ (Fixed)</span></td>
                        <td class="text-end">{{ number_format($payroll->basic_salary, 2) }}</td>
                    </tr>
                    @foreach($payroll->payrollDetails->where('component_type', 'earning') as $detail)
                    <tr>
                        <td>{{ $detail->component_name }}</td>
                        <td>
                            @if($detail->calculation_type === 'percentage')
                                {{ number_format($detail->rate, 2) }}% នៃប្រាក់គោល
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">ថេរ (Fixed)</span>
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($detail->amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">សរុបប្រាក់ចំណូល (Total Earnings)</td>
                        <td class="text-end">{{ number_format($payroll->total_earnings, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Deductions --}}
        @if($payroll->payrollDetails->where('component_type', 'deduction')->count() > 0 || $payroll->payrollDeductions->count() > 0)
        <div class="info-card mb-4">
            <div class="section-title">ការកាត់កង (Deductions)</div>
            <table class="table table-borderless breakdown-table mb-0">
                <thead>
                    <tr>
                        <th>សមាសភាគ / ប្រភេទកាត់ (Component / Type)</th>
                        <th>ការគណនា (Calculation)</th>
                        <th class="text-end">ចំនួនទឹកប្រាក់ (Amount)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payroll->payrollDetails->where('component_type', 'deduction') as $detail)
                    <tr>
                        <td>{{ $detail->component_name }}</td>
                        <td>
                            @if($detail->calculation_type === 'percentage')
                                {{ number_format($detail->rate, 2) }}% នៃប្រាក់គោល
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">ថេរ (Fixed)</span>
                            @endif
                        </td>
                        <td class="text-end text-danger">-{{ number_format($detail->amount, 2) }}</td>
                    </tr>
                    @endforeach
                    @foreach($payroll->payrollDeductions as $ded)
                    <tr>
                        <td>
                            <span class="text-danger">{{ $ded->deduction_type_label }}</span>
                            <small class="text-muted d-block">{{ $ded->description }}</small>
                        </td>
                        <td><span class="badge bg-danger-subtle text-danger-emphasis">ជាក់ស្តែង (Ad-hoc)</span></td>
                        <td class="text-end text-danger">-{{ number_format($ded->amount, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">សរុបការកាត់កង (Total Deductions)</td>
                        <td class="text-end text-danger">-{{ number_format($payroll->total_deductions, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        {{-- Net Salary --}}
        <div class="info-card" style="border-color:#bfdbfe;background:#eff6ff;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="section-title mb-1">ប្រាក់បៀវត្សរ៍សុទ្ធ (Net Salary)</div>
                    <div style="font-size:.85rem;color:#374151;">ប្រាក់ចំណូលសរុប − ប្រាក់កាត់កងសរុប (Total Earnings − Total Deductions)</div>
                </div>
                <div style="font-size:2rem;font-weight:800;color:#1e40af;">
                    {{ number_format($payroll->net_salary, 2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Right: status / timeline --}}
    <div class="col-lg-4">
        <div class="info-card mb-4">
            <div class="section-title">ព័ត៌មានលម្អិតនៃប្រាក់បៀវត្សរ៍ (Payroll Details)</div>
            <div class="info-row">
                <span class="info-label">ស្ថានភាព (Status)</span>
                <span class="badge-status badge-{{ $payroll->status }}">{{ $statusLabels[$payroll->status] ?? ucfirst($payroll->status) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">កាលបរិច្ឆេទបើក (Pay Date)</span>
                <span class="info-value">{{ $payroll->pay_date?->format('d/m/Y') ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">វិធីសាស្ត្រទូទាត់ (Payment Method)</span>
                <span class="info-value">{{ $paymentMethodLabels[$payroll->payment_method] ?? ($payroll->payment_method ? ucfirst(str_replace('_',' ',$payroll->payment_method)) : '—') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">លេខយោងទូទាត់ (Payment Ref.)</span>
                <span class="info-value">{{ $payroll->payment_reference ?? '—' }}</span>
            </div>
            @if($payroll->notes)
            <div class="mt-2 p-2 bg-light rounded" style="font-size:.82rem;">
                <strong>ចំណាំ (Notes):</strong> {{ $payroll->notes }}
            </div>
            @endif
        </div>

        {{-- Audit trail --}}
        <div class="info-card">
            <div class="section-title">ប្រវត្តិប្រតិបត្តិការ (Audit Trail)</div>
            <ul class="timeline">
                <li class="timeline-item">
                    <div class="timeline-dot" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-plus-lg"></i></div>
                    <div class="timeline-content">
                        <strong>បានបង្កើត (Created)</strong>
                        <span>{{ $payroll->createdBy?->name ?? 'ប្រព័ន្ធ (System)' }}</span>
                        <span class="d-block">{{ $payroll->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </li>
                @if($payroll->approved_by)
                <li class="timeline-item">
                    <div class="timeline-dot" style="background:#dbeafe;color:#1e40af;"><i class="bi bi-check-circle"></i></div>
                    <div class="timeline-content">
                        <strong>បានអនុម័ត (Approved)</strong>
                        <span>{{ $payroll->approvedBy?->name ?? '—' }}</span>
                        <span class="d-block">{{ $payroll->approved_at?->format('d/m/Y H:i') }}</span>
                    </div>
                </li>
                @endif
                @if($payroll->paid_by)
                <li class="timeline-item">
                    <div class="timeline-dot" style="background:#d1fae5;color:#065f46;"><i class="bi bi-cash"></i></div>
                    <div class="timeline-content">
                        <strong>បានទូទាត់ (Paid)</strong>
                        <span>{{ $payroll->paidBy?->name ?? '—' }}</span>
                        <span class="d-block">{{ $payroll->paid_at?->format('d/m/Y H:i') }}</span>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>

{{-- Pay Modal --}}
@if($payroll->status === 'approved')
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('payrolls.pay', $payroll) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">កត់ត្រាការទូទាត់ — {{ $payroll->payroll_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">វិធីសាស្ត្រទូទាត់ <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select" required>
                            <option value="bank_transfer">ផ្ទេរតាមធនាគារ (Bank Transfer)</option>
                            <option value="cash">សាច់ប្រាក់សុទ្ធ (Cash)</option>
                            <option value="cheque">មូលប្បទានប័ត្រ (Cheque)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">លេខយោង / លេខប្រតិបត្តិការ</label>
                        <input type="text" name="payment_reference" class="form-control" placeholder="មិនទាមទារ (ស្រេចចិត្ត)">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">កាលបរិច្ឆេទបើកប្រាក់</label>
                        <input type="date" name="pay_date" class="form-control" value="{{ now()->toDateString() }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="bi bi-check-circle me-1"></i>បញ្ជាក់ការទូទាត់
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
