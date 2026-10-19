@extends('layouts.app')

@section('title', "Invoice {$studentInvoice->invoice_number}")

@section('content')
<style>
@media print {
    body {
        background-color: #fff !important;
    }
    .no-print, .sidebar, .navbar, .btn, .breadcrumb, footer, .alert {
        display: none !important;
    }
    .content, .container-fluid {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .invoice-container {
        border: 1px solid #ddd !important;
        padding: 20px !important;
    }
}
</style>

<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 no-print">
        <div>
            <a href="{{ route('student-invoices.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Invoices
            </a>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold text-dark mb-0">{{ $studentInvoice->invoice_number }}</h1>
                @if($studentInvoice->status === 'paid')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">PAID</span>
                @elseif($studentInvoice->status === 'partial')
                    <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1">PARTIAL</span>
                @elseif($studentInvoice->status === 'cancelled')
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1">CANCELLED</span>
                @else
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1">UNPAID</span>
                @endif
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('student-invoices.receipt', $studentInvoice->id) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-receipt-cutoff me-1"></i> បង្កាន់ដៃបង់ប្រាក់ (BPS Receipt)
            </a>
            <button type="button" class="btn btn-sm btn-light border" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Print Invoice
            </button>

            @if($studentInvoice->status !== 'paid' && $studentInvoice->status !== 'cancelled' && $studentInvoice->balance > 0)
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                    <i class="bi bi-credit-card me-1"></i> Record Payment
                </button>
            @endif

            @if($studentInvoice->student_payments->isEmpty() && $studentInvoice->status !== 'cancelled')
                <a href="{{ route('student-invoices.edit', $studentInvoice->id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalCancelInvoice">
                    <i class="bi bi-x-circle me-1"></i> Cancel Invoice
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteShowInvoice">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            @endif
        </div>
    </div>

    {{-- Invoice Card / Paper Layout --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white p-4 p-md-5 invoice-container mb-4">
        {{-- Invoice Header --}}
        <div class="row pb-4 border-bottom mb-4 align-items-center">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-mortarboard-fill fs-2 text-primary"></i>
                    <h3 class="fw-bold text-dark mb-0">Academix School Management</h3>
                </div>
                <div class="text-muted small">Official Student Billing Statement</div>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <h4 class="fw-bold text-primary mb-1">{{ $studentInvoice->invoice_number }}</h4>
                <div class="text-muted small">Generated: {{ $studentInvoice->created_at?->format('d M Y') }}</div>
                <div class="small fw-medium {{ $studentInvoice->due_date && $studentInvoice->due_date->isPast() && !in_array($studentInvoice->status, ['paid', 'cancelled']) ? 'text-danger' : 'text-dark' }}">
                    Due Date: {{ $studentInvoice->due_date?->format('d M Y') ?? '—' }}
                </div>
            </div>
        </div>

        {{-- Billed Student & Academic Context --}}
        <div class="row mb-4 pb-3 border-bottom">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <span class="text-muted small text-uppercase fw-bold">Billed To:</span>
                <h5 class="fw-bold text-dark mt-1 mb-1">{{ $studentInvoice->student?->user?->name ?? 'Unknown Student' }}</h5>
                <div class="text-muted small">Admission No: <strong class="text-dark">{{ $studentInvoice->student?->admission_number }}</strong></div>
                <div class="text-muted small">Roll No: <strong class="text-dark">{{ $studentInvoice->student?->roll_number ?: '—' }}</strong></div>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <span class="text-muted small text-uppercase fw-bold">Academic Scope:</span>
                <div class="mt-1">
                    <span class="badge bg-light text-dark border">
                        Class: {{ $studentInvoice->student?->schoolClass?->name ?? '—' }}
                    </span>
                </div>
                <div class="text-muted small mt-1">
                    Academic Year: <strong class="text-dark">{{ $studentInvoice->fee_structure?->academic_year?->name ?? ($studentInvoice->student?->academicYear?->name ?? 'Active') }}</strong>
                </div>
            </div>
        </div>

        {{-- Line Items Table --}}
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light small text-muted">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Fee Item & Category</th>
                        <th class="text-end" style="width: 200px;">Amount ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentInvoice->invoice_details as $detail)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $detail->fee_category?->name ?? 'Fee Item' }}</td>
                            <td class="text-end fw-medium text-dark">${{ number_format($detail->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-3 text-muted">No line item details found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Financial Summary Box --}}
        <div class="row justify-content-end mb-4">
            <div class="col-12 col-md-5">
                <div class="p-3 rounded-2 bg-light border">
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Subtotal:</span>
                        <span class="fw-medium text-dark">${{ number_format($studentInvoice->total_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Discount Applied:</span>
                        <span class="text-danger">-${{ number_format($studentInvoice->discount_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold text-dark mb-2 pt-2 border-top">
                        <span>Net Amount:</span>
                        <span>${{ number_format($studentInvoice->net_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-success small mb-2">
                        <span>Total Paid:</span>
                        <span class="fw-bold">-${{ number_format($studentInvoice->total_paid, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 text-dark pt-2 border-top">
                        <span>Outstanding Balance:</span>
                        <span class="{{ $studentInvoice->balance > 0 ? 'text-warning' : 'text-success' }}">
                            ${{ number_format($studentInvoice->balance, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment History --}}
        <div class="mt-4 pt-3 border-top">
            <h5 class="fw-bold text-dark mb-3">Payment Receipts & History</h5>
            @if($studentInvoice->student_payments->isEmpty())
                <p class="text-muted small mb-0 py-2">No payments have been recorded for this invoice statement.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th>Receipt #</th>
                                <th>Receipt Date</th>
                                <th>Payment Method</th>
                                <th>Transaction Ref</th>
                                <th class="text-end">Amount Paid</th>
                                <th class="text-end no-print" style="width: 110px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentInvoice->student_payments as $payment)
                                <tr>
                                    <td>
                                        <a href="{{ route('student-payments.receipt', $payment->id) }}" class="fw-bold font-monospace text-decoration-none">
                                            {{ $payment->formatted_receipt_number }}
                                        </a>
                                    </td>
                                    <td class="text-dark fw-medium">{{ $payment->payment_date?->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $payment->payment_method?->name ?? 'Direct Payment' }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $payment->transaction_id ?: '—' }}</td>
                                    <td class="text-end fw-bold text-success">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="text-end no-print">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('student-payments.receipt', $payment->id) }}" class="btn btn-outline-success border-0 p-1" title="បោះពុម្ពបង្កាន់ដៃ (Print Receipt)">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger border-0 p-1" title="Delete Payment"
                                                    onclick="openDeleteShowPaymentModal('{{ route('student-payments.destroy', $payment->id) }}', '{{ addslashes($payment->receipt_number ?? ('#' . $payment->id)) }}', '{{ number_format($payment->amount, 2) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
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

{{-- Payment Entry Modal --}}
@if($studentInvoice->status !== 'paid' && $studentInvoice->status !== 'cancelled' && $studentInvoice->balance > 0)
<div class="modal fade no-print" id="recordPaymentModal" tabindex="-1" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('student-payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_invoice_id" value="{{ $studentInvoice->id }}">

                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="recordPaymentModalLabel">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-3">
                    <div class="p-3 bg-light rounded-2 mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Invoice:</span>
                            <strong class="text-dark">{{ $studentInvoice->invoice_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Student:</span>
                            <span class="text-dark">{{ $studentInvoice->student?->user?->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Net Invoiced:</span>
                            <span class="text-dark">${{ number_format($studentInvoice->net_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Total Paid:</span>
                            <span class="text-success">${{ number_format($studentInvoice->total_paid, 2) }}</span>
                        </div>
                        <hr class="my-1">
                        <div class="d-flex justify-content-between fw-bold text-dark">
                            <span>Outstanding Balance:</span>
                            <span class="text-warning">${{ number_format($studentInvoice->balance, 2) }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_payment_method_id" class="form-label small fw-bold text-dark">Payment Method</label>
                        <select name="payment_method_id" id="modal_payment_method_id" class="form-select">
                            <option value="">Direct Cash / None</option>
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal_amount" class="form-label small fw-bold text-dark">Payment Amount ($) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0.01" max="{{ $studentInvoice->balance }}" name="amount" id="modal_amount" value="{{ old('amount', $studentInvoice->balance) }}" class="form-control" required>
                        </div>
                        <div class="form-text small">Cannot exceed remaining balance of ${{ number_format($studentInvoice->balance, 2) }}.</div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_payment_date" class="form-label small fw-bold text-dark">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="modal_payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label for="modal_transaction_id" class="form-label small fw-bold text-dark">Transaction ID / Reference</label>
                        <input type="text" name="transaction_id" id="modal_transaction_id" class="form-control" placeholder="e.g., TXN-98428 or Bank Slip #">
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Cancel Invoice Modal --}}
<div class="modal fade" id="modalCancelInvoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form action="{{ route('student-invoices.cancel', $studentInvoice->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fffbeb; color: #d97706; font-size: 1.4rem;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការបោះបង់វិក្កយបត្រ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់បោះបង់វិក្កយបត្រ <strong>{{ $studentInvoice->invoice_number }}</strong> មែនទេ?
                    </p>
                    <div class="text-warning text-dark" style="font-size: 0.73rem;">
                        <i class="bi bi-info-circle me-1"></i>សកម្មភាពនេះនឹងបិទមិនឱ្យទទួលការបង់ប្រាក់បន្តទៀតឡើយ។
                    </div>
                </div>
                <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 4px; font-size: 0.82rem;" data-bs-dismiss="modal">
                        ត្រឡប់ក្រោយ
                    </button>
                    <button type="submit" class="btn btn-sm btn-warning text-dark px-3.5" style="border-radius: 4px; font-size: 0.82rem;">
                        <i class="bi bi-x-circle me-1"></i>បោះបង់វិក្កយបត្រ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Invoice Modal --}}
<div class="modal fade" id="modalDeleteShowInvoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form action="{{ route('student-invoices.destroy', $studentInvoice->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបវិក្កយបត្រ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបវិក្កយបត្រ <strong>{{ $studentInvoice->invoice_number }}</strong> ជាអចិន្ត្រៃយ៍មែនទេ?
                    </p>
                    <div class="text-danger" style="font-size: 0.73rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>ទិន្នន័យនឹងត្រូវលុបចេញពីប្រព័ន្ធទាំងស្រុង។
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

{{-- Delete Payment Modal --}}
<div class="modal fade" id="modalDeleteShowPayment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deleteShowPaymentForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបការបង់ប្រាក់</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបបង្កាន់ដៃ <strong id="delShowPaymentReceipt" class="text-dark"></strong> មែនទេ?
                    </p>
                    <div class="p-2 mb-2 rounded bg-light border text-start" style="font-size: 0.78rem;">
                        <div class="text-muted d-flex justify-content-between">
                            <span>ចំនួនទឹកប្រាក់:</span>
                            <span id="delShowPaymentAmount" class="fw-bold text-success"></span>
                        </div>
                    </div>
                    <div class="text-danger" style="font-size: 0.73rem;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>សមតុល្យវិក្កយបត្រនឹងត្រូវគណនាឡើងវិញដោយស្វ័យប្រវត្តិ។
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
function openDeleteShowPaymentModal(actionUrl, receipt, amount) {
    const form = document.getElementById('deleteShowPaymentForm');
    if (form) form.action = actionUrl;

    const receiptEl = document.getElementById('delShowPaymentReceipt');
    if (receiptEl) receiptEl.textContent = receipt || '';

    const amountEl = document.getElementById('delShowPaymentAmount');
    if (amountEl) amountEl.textContent = amount ? ('$' + amount) : '—';

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteShowPayment'));
    modal.show();
}
</script>
@endpush
@endsection
