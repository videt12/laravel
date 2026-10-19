@extends('layouts.app')

@section('title', 'ការបង់ប្រាក់របស់សិស្ស និងការប្រមូលប្រាក់ — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការបង់ប្រាក់របស់សិស្ស</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.table-card {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    width: 100% !important;
}
.table-custom { width: 100% !important; margin: 0; min-width: 100%; }
.table-custom thead th {
    background: #f8fafc;
    color: var(--text-dark, #1e293b);
    font-size: 0.82rem;
    font-weight: 700;
    padding: 0.65rem 0.85rem;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.table-custom tbody td {
    padding: 0.75rem 0.85rem;
    font-size: 0.85rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    white-space: nowrap;
}
.table-custom tbody tr:hover td { background-color: #f8fafc; }

.metric-card {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-sm, 6px);
    padding: 0.5rem 0.75rem;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    height: 100%;
    display: flex;
    align-items: center;
}
.metric-card-interactive {
    cursor: pointer;
    user-select: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
}
.metric-card-interactive:hover {
    border-color: var(--color-brand-500, #3b82f6) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.12) !important;
    transform: translateY(-2px);
}
.metric-icon-wrap {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 0.65rem;
}

@media print {
    body { background: #fff !important; font-size: 11pt; }
    .sidebar, .navbar, .d-print-none, .btn, form, .pagination { display: none !important; }
    .table-card, .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 10px !important; }
    .table { width: 100% !important; }
    .table th, .table td { padding: 4px 6px !important; }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-2.5 pb-2 border-bottom d-print-none">
        <div>
            <h1 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">ការបង់ប្រាក់របស់សិស្ស និងការប្រមូលប្រាក់</h1>
            <p class="text-muted small mb-0 mt-0.5" style="font-size: 0.76rem;">
                សវនកម្មការប្រមូលកម្រៃសេវា បង្កាន់ដៃ វិធីសាស្ត្រប្រតិបត្តិការ និងកំណត់ត្រាបេឡា
            </p>
        </div>
        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <button type="button" onclick="toggleCurrencyDisplay()" id="btnCurrencyToggle" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 shadow-2xs" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 5px;" title="ចុចប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <i class="bi bi-arrow-left-right text-primary"></i>
                <span id="currencyModeText">បង្ហាញ៖ <strong class="text-primary">$ USD</strong></span>
            </button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 5px;">
                <i class="bi bi-file-earmark-spreadsheet"></i><span>ទាញយក CSV</span>
            </a>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.55rem; border-radius: 5px;">
                <i class="bi bi-printer"></i><span>បោះពុម្ពរបាយការណ៍</span>
            </button>
            <a href="{{ route('student-payments.create') }}" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-1 shadow-sm" style="height: 28px; font-size: 0.75rem; padding: 0.2rem 0.65rem; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 5px;">
                <i class="bi bi-plus-lg"></i><span>កត់ត្រាការបង់ប្រាក់</span>
            </a>
        </div>
    </div>

    {{-- Collection Summary Cards --}}
    <div class="row g-2 mb-2.5">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="metric-card metric-card-interactive" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div class="metric-icon-wrap" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                    <i class="bi bi-cash-stack" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                        <span>ចំណូលប្រមូលបានសរុប</span>
                        <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.62rem;"></i>
                    </div>
                    <div class="currency-display" data-usd="${{ number_format($totalAmountCollected, 2) }}" data-khr="{{ number_format(round($totalAmountCollected * 4100, -2)) }} ៛">
                        <h5 class="fw-bold text-success mb-0 curr-primary" style="font-size: 1.05rem;">${{ number_format($totalAmountCollected, 2) }}</h5>
                        <div class="text-success fw-semibold curr-secondary" style="font-size: 0.65rem; opacity: .85;">{{ number_format(round($totalAmountCollected * 4100, -2)) }} ៛</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="metric-card">
                <div class="metric-icon-wrap" style="background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                    <i class="bi bi-receipt-cutoff" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.72rem;">បង្កាន់ដៃចេញសរុប</div>
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">{{ number_format($totalPaymentsCount) }}</h5>
                    <div class="text-muted" style="font-size: 0.65rem;">បង្កាន់ដៃ</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4">
            <div class="metric-card">
                <div class="metric-icon-wrap" style="background: rgba(232, 168, 56, 0.15); color: #d97706;">
                    <i class="bi bi-funnel-fill" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size: 0.72rem;">លទ្ធផលចម្រាញ់</div>
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">{{ $payments->total() }} កំណត់ត្រា</h5>
                    <div class="text-muted" style="font-size: 0.65rem;">
                        @if(request()->hasAny(['search', 'payment_method_id', 'academic_year_id', 'class_id', 'date_from', 'date_to']))
                            (បានចម្រាញ់តាមលក្ខខណ្ឌ)
                        @else
                            (ទិន្នន័យសរុប)
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-2 d-print-none">
        <div class="card-body py-2 px-3">
            <form action="{{ route('student-payments.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="ស្វែងរក #វិក្កយបត្រ, #បង្កាន់ដៃ, ឈ្មោះសិស្ស...">
                </div>
                <div class="col-6 col-md-2">
                    <select name="academic_year_id" class="form-select form-select-sm">
                        <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">ថ្នាក់ទាំងអស់</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="payment_method_id" class="form-select form-select-sm">
                        <option value="">វិធីសាស្ត្រទាំងអស់</option>
                        @foreach($paymentMethods as $pm)
                            @php
                                $pmName = [
                                    'Direct Payment' => 'ការបង់ផ្ទាល់',
                                    'Direct Cash'    => 'សាច់ប្រាក់សុទ្ធ',
                                    'Cash'           => 'សាច់ប្រាក់',
                                    'Bank Transfer'  => 'ផ្ទេរតាមធនាគារ',
                                    'Check'          => 'មូលប្បទានប័ត្រ',
                                    'Credit Card'    => 'កាតឥណទាន',
                                    'ABA Pay'        => 'ABA Pay',
                                ][$pm->name] ?? $pm->name;
                            @endphp
                            <option value="{{ $pm->id }}" {{ request('payment_method_id') == $pm->id ? 'selected' : '' }}>
                                {{ $pmName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm" title="កាលបរិច្ឆេទចាប់ពី">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm" title="ដល់កាលបរិច្ឆេទ">
                </div>
                <div class="col-12 col-md-2 col-lg-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white w-100" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);" title="ចម្រាញ់">
                        <i class="bi bi-filter"></i>
                    </button>
                    @if(request()->hasAny(['search', 'payment_method_id', 'academic_year_id', 'class_id', 'date_from', 'date_to']))
                        <a href="{{ route('student-payments.index') }}" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0 w-100" style="width: 100%;">
                <thead class="table-light small text-muted text-uppercase" style="font-size: 0.72rem;">
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>កាលបរិច្ឆេទបង់ប្រាក់</th>
                        <th>លេខបង្កាន់ដៃ</th>
                        <th>សិស្ស</th>
                        <th>អត្តលេខ</th>
                        <th>ថ្នាក់</th>
                        <th>វិក្កយបត្រ</th>
                        <th>វិធីសាស្ត្រ</th>
                        <th>លេខយោង</th>
                        <th>អ្នកកត់ត្រា</th>
                        <th class="text-end">ចំនួនទឹកប្រាក់បង់</th>
                        <th class="text-end d-print-none" style="width: 110px;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        @php
                            $methodName = $payment->payment_method?->name ?? 'Direct Payment';
                            $methodKhmer = [
                                'Direct Payment' => 'ការបង់ផ្ទាល់',
                                'Direct Cash'    => 'សាច់ប្រាក់សុទ្ធ',
                                'Cash'           => 'សាច់ប្រាក់',
                                'Bank Transfer'  => 'ផ្ទេរតាមធនាគារ',
                                'Check'          => 'មូលប្បទានប័ត្រ',
                            ][$methodName] ?? $methodName;
                        @endphp
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
                            <td class="fw-medium text-dark">{{ $payment->payment_date?->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    {{ $payment->formatted_receipt_number }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $payment->student_invoice?->student?->user?->name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="text-muted font-monospace">{{ $payment->student_invoice?->student?->admission_number ?? '—' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">
                                    {{ $payment->student_invoice?->student?->schoolClass?->name ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('student-invoices.show', $payment->student_invoice_id) }}" class="fw-bold text-decoration-none">
                                    #INV-{{ $payment->student_invoice_id }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $methodKhmer }}
                                </span>
                            </td>
                            <td class="small text-muted font-monospace">{{ $payment->transaction_id ?: '—' }}</td>
                            <td class="small text-muted">{{ $payment->receiver_name ?: 'បុគ្គលិក' }}</td>
                            <td class="text-end">
                                @php
                                    $pmtKhr = number_format($payment->calculated_amount_khr ?? round($payment->amount * 4100, -2)) . ' ៛';
                                @endphp
                                <div class="currency-display" data-usd="${{ number_format($payment->amount, 2) }}" data-khr="{{ $pmtKhr }}">
                                    <div class="fw-bold text-success curr-primary">${{ number_format($payment->amount, 2) }}</div>
                                    <div class="text-muted curr-secondary" style="font-size: .7rem;">{{ $pmtKhr }}</div>
                                </div>
                            </td>
                            <td class="text-end d-print-none">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('student-payments.receipt', $payment->id) }}" class="btn btn-outline-success" title="បោះពុម្ពបង្កាន់ដៃផ្លូវការ BPS">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <a href="{{ route('student-invoices.show', $payment->student_invoice_id) }}" class="btn btn-outline-primary" title="មើលរបាយការណ៍វិក្កយបត្រ">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="លុបការបង់ប្រាក់"
                                            onclick="openDeletePaymentModal('{{ route('student-payments.destroy', $payment->id) }}', '{{ addslashes($payment->receipt_number ?? ('#' . $payment->id)) }}', '{{ addslashes($payment->student_invoice?->student?->full_name_khmer ?? $payment->student_invoice?->student?->full_name_latin ?? '') }}', '{{ number_format($payment->amount, 2) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4 text-muted">មិនមានការបង់ប្រាក់ដែលត្រូវនឹងលក្ខខណ្ឌជ្រើសរើសឡើយ។</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-print-none">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="modalDeletePayment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deletePaymentForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបការបង់ប្រាក់</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបបង្កាន់ដៃ <strong id="delPaymentReceipt" class="text-dark"></strong> មែនទេ?
                    </p>
                    <div class="p-2 mb-2 rounded bg-light border text-start" style="font-size: 0.78rem;">
                        <div class="text-muted d-flex justify-content-between mb-1">
                            <span>សិស្ស:</span>
                            <span id="delPaymentStudent" class="fw-bold text-dark"></span>
                        </div>
                        <div class="text-muted d-flex justify-content-between">
                            <span>ចំនួនទឹកប្រាក់:</span>
                            <span id="delPaymentAmount" class="fw-bold text-success"></span>
                        </div>
                    </div>
                    <div class="text-danger" style="font-size: 0.73rem;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>សមតុល្យ និងវិក្កយបត្រនឹងត្រូវគណនាឡើងវិញដោយស្វ័យប្រវត្តិ។
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

function openDeletePaymentModal(actionUrl, receipt, student, amount) {
    const form = document.getElementById('deletePaymentForm');
    if (form) form.action = actionUrl;

    const receiptEl = document.getElementById('delPaymentReceipt');
    if (receiptEl) receiptEl.textContent = receipt || '';

    const studentEl = document.getElementById('delPaymentStudent');
    if (studentEl) studentEl.textContent = student || '—';

    const amountEl = document.getElementById('delPaymentAmount');
    if (amountEl) amountEl.textContent = amount ? ('$' + amount) : '—';

    const modal = new bootstrap.Modal(document.getElementById('modalDeletePayment'));
    modal.show();
}
</script>
@endpush
@endsection
