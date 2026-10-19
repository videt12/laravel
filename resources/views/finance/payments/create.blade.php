@extends('layouts.app')

@section('title', 'កត់ត្រាការបង់ប្រាក់របស់សិស្ស — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item"><a href="{{ route('student-payments.index') }}" class="text-decoration-none text-muted">ការបង់ប្រាក់របស់សិស្ស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កត់ត្រាការបង់ប្រាក់</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   PAYMENT WORKFLOW STYLES (COMPACT, FAST, ERGONOMIC)
═══════════════════════════════════════════════════════════════════ */
.pay-card {
    background: #ffffff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.pay-card-header {
    padding: 0.75rem 1.15rem;
    border-bottom: 1px solid var(--border-soft, #e2e8f0);
    background: #ffffff;
}
.pay-card-body {
    padding: 1.15rem;
}
.form-label-custom {
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.25rem;
    display: block;
}
.form-control-custom, .form-select-custom {
    font-size: 0.85rem;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0.45rem 0.75rem;
    min-height: 38px;
    height: 38px;
    transition: all 0.2s;
    background-color: #ffffff;
}
.form-control-custom:focus, .form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.2);
    outline: none;
}
.form-control-custom-lg {
    font-size: 1.15rem;
    font-weight: 700;
    height: 44px;
    min-height: 44px;
}
.quick-chip {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.22rem 0.55rem;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}
.quick-chip:hover {
    background: #fef3c7;
    border-color: #fde68a;
    color: #b45309;
}
.quick-chip.active {
    background: #e8a838;
    border-color: #d97706;
    color: #ffffff;
}
.step-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: #fef3c7;
    color: #b45309;
    border-radius: 50%;
    font-size: 0.72rem;
    font-weight: 700;
    margin-right: 0.4rem;
}
.receipt-preview-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    position: sticky;
    top: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.live-khr-pill {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.35rem 0.65rem;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
.btn-gold {
    background-color: #e8a838;
    border-color: #e8a838;
    color: #ffffff;
    font-weight: 600;
    transition: all 0.2s;
}
.btn-gold:hover, .btn-gold:focus {
    background-color: #d18d1f;
    border-color: #d18d1f;
    color: #ffffff;
    box-shadow: 0 2px 6px rgba(232, 168, 56, 0.35);
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="page-title fw-bold text-dark mb-0" style="font-size: 1.35rem;">
                កត់ត្រាការបង់ប្រាក់របស់សិស្ស (Record Payment)
            </h1>
            <p class="text-muted small mb-0 mt-0.5">
                ទទួលការបង់ថ្លៃសិក្សារបស់សិស្ស បង្កើតបង្កាន់ដៃ និងធ្វើបច្ចុប្បន្នភាពសមតុល្យវិក្កយបត្រ
            </p>
        </div>
        <a href="{{ route('student-payments.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1.5 px-3 py-1.5" style="border-radius: 4px;">
            <i class="bi bi-arrow-left"></i>
            <span>ត្រឡប់ក្រោយ (Back)</span>
        </a>
    </div>

    {{-- Error Banner --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-3 p-3" style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong style="font-size:0.88rem;">សូមពិនិត្យព័ត៌មានខាងក្រោមមុនពេលរក្សាទុក៖</strong>
            </div>
            <ul class="mb-0 small ps-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('student-payments.store') }}" method="POST" id="paymentForm">
        @csrf

        <div class="row g-3">
            {{-- ═════════════════════════════════════════════════════════════════
                 LEFT COLUMN: SMART CASHIER WORKFLOW
            ═════════════════════════════════════════════════════════════════ --}}
            <div class="col-xl-7 col-lg-7">

                {{-- SECTION 1: STUDENT & INVOICE SELECTION --}}
                <div class="pay-card mb-3">
                    <div class="pay-card-header d-flex align-items-center justify-content-between">
                        <span class="fw-bold text-dark d-flex align-items-center" style="font-size:0.88rem;">
                            <span class="step-badge">1</span> ជ្រើសរើសសិស្ស និងវិក្កយបត្រ (Student & Invoice)
                        </span>
                        @if($selectedStudent)
                            <a href="{{ route('student-payments.create') }}" class="btn btn-link btn-sm text-danger text-decoration-none p-0" style="font-size:0.78rem;">
                                <i class="bi bi-arrow-repeat me-1"></i>ប្តូរសិស្សផ្សេង
                            </a>
                        @endif
                    </div>
                    <div class="pay-card-body">

                        {{-- Step 1A: Student Selection --}}
                        @if($selectedStudent)
                            <div class="p-2.5 rounded-3 d-flex align-items-center justify-content-between mb-3" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold flex-shrink-0" style="width: 38px; height: 38px; background: #fde68a;">
                                        {{ strtoupper(substr($selectedStudent->user?->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size:0.92rem;">{{ $selectedStudent->user?->name }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            អត្តលេខ #：<span class="fw-semibold text-dark font-monospace">{{ $selectedStudent->admission_number }}</span>
                                            @if($selectedStudent->schoolClass)
                                                &bull; ថ្នាក់៖ <span class="fw-semibold text-dark">{{ $selectedStudent->schoolClass->name }}</span>
                                            @endif
                                            @if($selectedStudent->schoolShift)
                                                &bull; វេន៖ <span class="fw-semibold text-dark">{{ $selectedStudent->schoolShift->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="badge" style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; font-size:0.72rem; padding:0.25rem 0.5rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i>បានជ្រើស
                                </span>
                            </div>
                            <input type="hidden" id="selected_student_id" value="{{ $selectedStudent->id }}">
                        @else
                            <div class="mb-3">
                                <label class="form-label-custom">
                                    ជ្រើសរើសសិស្សដែលមានបំណុលត្រូវបង់ <span class="text-danger">*</span>
                                </label>
                                
                                {{-- Quick Search Input for instantaneous filtering --}}
                                <div class="input-group input-group-sm mb-1.5">
                                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-color:#cbd5e1;">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="studentSearchInput" class="form-control form-control-sm border-start-0" 
                                           style="border-color:#cbd5e1; font-size:0.83rem;"
                                           placeholder="វាយអត្តលេខ (ID) ឬឈ្មោះសិស្ស ដើម្បីស្វែងរកលឿន..." 
                                           onkeyup="filterStudentOptions(this.value)">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="clearStudentSearch()" title="សម្អាត">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>

                                <select id="student_selector" class="form-select form-select-custom @error('student_id') is-invalid @enderror" onchange="onStudentSelected(this.value)">
                                    <option value="">-- ចុចជ្រើសរើសសិស្ស (សរុប {{ count($studentsWithInvoices) }} នាក់) --</option>
                                    @foreach($studentsWithInvoices as $stu)
                                        <option value="{{ $stu->id }}" 
                                                data-search="{{ strtolower($stu->admission_number . ' ' . ($stu->user?->name ?? '')) }}"
                                                {{ old('student_id') == $stu->id ? 'selected' : '' }}>
                                            [{{ $stu->admission_number }}] {{ $stu->user?->name ?? 'Student #'.$stu->id }} 
                                            @if($stu->schoolClass) — ថ្នាក់៖ {{ $stu->schoolClass->name }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="text-muted" style="font-size:0.72rem;">* បង្ហាញសិស្សដែលមានវិក្កយបត្រមិនទាន់បង់ ឬបង់ខ្លះ</span>
                                    <span id="matchCountBadge" class="text-muted" style="font-size:0.72rem;"></span>
                                </div>
                            </div>
                        @endif

                        {{-- Step 1B: Invoice Selection --}}
                        <div>
                            <label class="form-label-custom">
                                វិក្កយបត្រដែលត្រូវបង់ (Payable Invoice) <span class="text-danger">*</span>
                            </label>

                            @if($invoice)
                                <input type="hidden" name="student_invoice_id" id="student_invoice_id" value="{{ $invoice->id }}">
                                <div class="p-2.5 rounded-3 d-flex align-items-center justify-content-between" style="background:#f1f5f9; border:1px solid #cbd5e1;">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark font-monospace" style="font-size:0.95rem;">{{ $invoice->invoice_number }}</strong>
                                            <span class="badge {{ $invoice->status === 'partial' ? 'bg-warning text-dark' : 'bg-danger text-white' }}" style="font-size:0.7rem; padding:0.2rem 0.4rem;">
                                                {{ $invoice->status === 'partial' ? 'បង់ខ្លះ (Partial)' : 'មិនទាន់បង់ (Unpaid)' }}
                                            </span>
                                        </div>
                                        <div class="text-muted mt-0.5" style="font-size:0.75rem;">
                                            {{ $invoice->fee_structure?->name ?? $invoice->invoice_details->first()?->fee_category?->name ?? 'ថ្លៃសិក្សា' }}
                                            &bull; កាលបរិច្ឆេទកំណត់៖ {{ $invoice->due_date?->format('d/m/Y') ?? '—' }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted d-block" style="font-size:0.72rem;">សមតុល្យនៅសល់</span>
                                        <span class="fw-bold text-danger fs-6">${{ number_format($invoice->balance, 2) }}</span>
                                    </div>
                                </div>
                            @else
                                <select name="student_invoice_id" id="student_invoice_id" class="form-select form-select-custom @error('student_invoice_id') is-invalid @enderror" required onchange="onInvoiceSelected(this)">
                                    <option value="">-- ជ្រើសរើសវិក្កយបត្រ --</option>
                                    @if(isset($studentInvoices) && $studentInvoices->isNotEmpty())
                                        @foreach($studentInvoices as $inv)
                                            @php
                                                $totalPaid = (float) $inv->student_payments->sum('amount');
                                                $bal = max(0.0, round((float) $inv->net_amount - $totalPaid, 2));
                                                $feeTitle = $inv->fee_structure?->name ?? $inv->invoice_details->first()?->fee_category?->name ?? 'Fee';
                                            @endphp
                                            <option value="{{ $inv->id }}"
                                                    data-balance="{{ $bal }}"
                                                    data-net="{{ $inv->net_amount }}"
                                                    data-paid="{{ $totalPaid }}"
                                                    data-number="{{ $inv->invoice_number }}"
                                                    data-title="{{ $feeTitle }}"
                                                    data-year="{{ $inv->academic_year?->name ?? '—' }}"
                                                    data-due="{{ $inv->due_date?->format('d/m/Y') ?? '—' }}"
                                                    data-status="{{ $inv->status }}"
                                                    {{ old('student_invoice_id') == $inv->id ? 'selected' : '' }}>
                                                {{ $inv->invoice_number }} ({{ $feeTitle }}) — នៅសល់៖ ${{ number_format($bal, 2) }}
                                            </option>
                                        @endforeach
                                    @elseif(isset($invoices) && $invoices->isNotEmpty())
                                        @foreach($invoices as $inv)
                                            <option value="{{ $inv->id }}"
                                                    data-balance="{{ $inv->balance }}"
                                                    data-net="{{ $inv->net_amount }}"
                                                    data-paid="{{ $inv->total_paid }}"
                                                    data-number="{{ $inv->invoice_number }}"
                                                    data-title="Fee"
                                                    data-year="{{ $inv->academicYear?->name ?? '—' }}"
                                                    data-due="{{ $inv->due_date?->format('d/m/Y') ?? '—' }}"
                                                    data-status="{{ $inv->status }}"
                                                    {{ old('student_invoice_id') == $inv->id ? 'selected' : '' }}>
                                                {{ $inv->invoice_number }} - {{ $inv->student?->user?->name }} (នៅសល់៖ ${{ number_format($inv->balance, 2) }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('student_invoice_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                    </div>
                </div>

                {{-- SECTION 2: PAYMENT AMOUNT & METHOD --}}
                <div class="pay-card mb-3">
                    <div class="pay-card-header">
                        <span class="fw-bold text-dark d-flex align-items-center" style="font-size:0.88rem;">
                            <span class="step-badge">2</span> ព័ត៌មានទឹកប្រាក់ និងការទូទាត់ (Amount & Method)
                        </span>
                    </div>
                    <div class="pay-card-body">
                        
                        {{-- Amount Field with Quick Buttons --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="amount" class="form-label-custom mb-0">
                                    ចំនួនទឹកប្រាក់បង់ ($) <span class="text-danger">*</span>
                                </label>
                                <span class="text-muted" style="font-size:0.75rem;" id="maxPayableText">
                                    @if($invoice)
                                        អតិបរមាអាចបង់៖ <strong class="text-dark">${{ number_format($invoice->balance, 2) }}</strong>
                                    @endif
                                </span>
                            </div>

                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold text-dark" style="border-color:#cbd5e1; font-size:1.1rem;">$</span>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount"
                                       value="{{ old('amount', $invoice ? $invoice->balance : '') }}"
                                       class="form-control form-control-custom form-control-custom-lg @error('amount') is-invalid @enderror"
                                       placeholder="0.00" required autocomplete="off">
                            </div>

                            {{-- Quick Amount Shortcuts & Live KHR Equivalent --}}
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-2">
                                <div class="d-flex align-items-center gap-1.5">
                                    <span class="text-muted small me-1" style="font-size:0.75rem;">ចុចរហ័ស៖</span>
                                    <button type="button" class="quick-chip" id="btnQuickFull" onclick="applyQuickAmount(1.0)">បង់ពេញ (100%)</button>
                                    <button type="button" class="quick-chip" id="btnQuickHalf" onclick="applyQuickAmount(0.5)">50%</button>
                                    <button type="button" class="quick-chip" id="btnQuickQuarter" onclick="applyQuickAmount(0.25)">25%</button>
                                    <button type="button" class="quick-chip text-danger" onclick="clearAmount()">សម្អាត</button>
                                </div>
                                <div class="live-khr-pill" id="liveKhrPill">
                                    <i class="bi bi-cash-coin text-success"></i>
                                    <span>ស្មើនឹងប្រាក់រៀល៖ <strong id="khrEquivalentDisplay">0 ៛</strong></span>
                                </div>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date & Payment Method --}}
                        <div class="row g-2 mb-2">
                            <div class="col-md-6 col-12">
                                <label for="payment_date" class="form-label-custom">កាលបរិច្ឆេទបង់ប្រាក់ <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" id="payment_date" 
                                       value="{{ old('payment_date', date('Y-m-d')) }}" 
                                       class="form-control form-control-custom @error('payment_date') is-invalid @enderror" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-12">
                                <label for="payment_method_id" class="form-label-custom">វិធីសាស្ត្របង់ប្រាក់ <span class="text-danger">*</span></label>
                                <select name="payment_method_id" id="payment_method_id" class="form-select form-select-custom @error('payment_method_id') is-invalid @enderror">
                                    <option value="">សាច់ប្រាក់ (Cash)</option>
                                    @foreach($paymentMethods as $pm)
                                        @php
                                            $pmName = [
                                                'Direct Payment' => 'ការបង់ផ្ទាល់ (Cash / Direct)',
                                                'Direct Cash'    => 'សាច់ប្រាក់សុទ្ធ (Cash)',
                                                'Cash'           => 'សាច់ប្រាក់ (Cash)',
                                                'Bank Transfer'  => 'ផ្ទេរតាមធនាគារ (ABA / Bank Transfer)',
                                                'Check'          => 'មូលប្បទានប័ត្រ (Cheque)',
                                            ][$pm->name] ?? $pm->name;
                                        @endphp
                                        <option value="{{ $pm->id }}" {{ old('payment_method_id') == $pm->id ? 'selected' : '' }}>
                                            {{ $pmName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-12">
                                <label for="transaction_id" class="form-label-custom">លេខប្រតិបត្តិការ / លេខយោង # (Transaction ID / Reference)</label>
                                <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}" 
                                       class="form-control form-control-custom @error('transaction_id') is-invalid @enderror" 
                                       placeholder="ឧ. ABA-10928, SLIP #4829 (ប្រសិនបើមាន)">
                                @error('transaction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- SECTION 3: BPS RECEIPT DETAILS (COLLAPSIBLE / OPTIONAL DETAILS) --}}
                <div class="pay-card mb-3">
                    <div class="pay-card-header d-flex align-items-center justify-content-between" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapseReceiptDetails">
                        <span class="fw-bold text-dark d-flex align-items-center" style="font-size:0.88rem;">
                            <span class="step-badge">3</span> ព័ត៌មានបង្កាន់ដៃ BPS (Receipt Specifics)
                        </span>
                        <span class="badge bg-light text-muted border" style="font-size:0.72rem;">
                            <i class="bi bi-chevron-down me-1"></i>ព័ត៌មានលម្អិតបន្ថែម
                        </span>
                    </div>
                    <div class="collapse show" id="collapseReceiptDetails">
                        <div class="pay-card-body bg-light bg-opacity-25">
                            
                            <div class="row g-2 mb-2">
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">ប្រភេទសិស្ស (Student Type)</label>
                                    <select name="student_type" class="form-select form-select-custom">
                                        <option value="old" {{ old('student_type') === 'old' ? 'selected' : '' }}>សិស្សចាស់ (Old Student)</option>
                                        <option value="new" {{ old('student_type') === 'new' ? 'selected' : '' }}>សិស្សថ្មី (New Student)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">វេនសិក្សា (Shift)</label>
                                    <select name="shift" class="form-select form-select-custom">
                                        <option value="morning_7_1030" {{ old('shift') === 'morning_7_1030' ? 'selected' : '' }}>ព្រឹក (៧:០០ - ១០:៣០)</option>
                                        <option value="afternoon_1_400" {{ old('shift') === 'afternoon_1_400' ? 'selected' : '' }}>រសៀល (១:០០ - ៤:០០)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">ម៉ោងអង់គ្លេសក្រៅម៉ោង (English Class Time)</label>
                                    <input type="text" name="english_class_time" value="{{ old('english_class_time') }}" 
                                           class="form-control form-control-custom" placeholder="ឧ. 11:00 - 12:00">
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">កម្រិតសៀវភៅ (Book Level)</label>
                                    <input type="text" name="book_level" value="{{ old('book_level') }}" 
                                           class="form-control form-control-custom" placeholder="ឧ. Level 1, Starter">
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">រយៈពេលបង់ប្រាក់ (Period)</label>
                                    <select name="period_type" class="form-select form-select-custom" id="period_type" onchange="onPeriodTypeChange(this.value)">
                                        <option value="month" {{ old('period_type', 'month') === 'month' ? 'selected' : '' }}>បង់ជាខែ (Monthly - 1 Month)</option>
                                        <option value="trimester" {{ old('period_type') === 'trimester' ? 'selected' : '' }}>១ ត្រីមាស (Trimester - 3 Months)</option>
                                        <option value="semester" {{ old('period_type') === 'semester' ? 'selected' : '' }}>៦ ខែ (Semester - 6 Months)</option>
                                        <option value="year" {{ old('period_type') === 'year' ? 'selected' : '' }}>១ ឆ្នាំ (Year - 12 Months)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12" id="monthsInputCol">
                                    <label class="form-label-custom">ចំនួនខែ (Number of Months)</label>
                                    <input type="number" name="period_months" id="period_months" min="1" max="12" 
                                           value="{{ old('period_months', 1) }}" class="form-control form-control-custom"
                                           onchange="recalculateDates()">
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">ចាប់ផ្ដើមពីថ្ងៃទី (Start Date)</label>
                                    <input type="date" name="period_start_date" id="period_start_date" 
                                           value="{{ old('period_start_date', date('Y-m-d')) }}" 
                                           class="form-control form-control-custom" onchange="recalculateDates()">
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">រហូតដល់ថ្ងៃទី (End Date)</label>
                                    <input type="date" name="period_end_date" id="period_end_date" 
                                           value="{{ old('period_end_date', date('Y-m-d', strtotime('+1 month'))) }}" 
                                           class="form-control form-control-custom">
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">ថ្ងៃត្រូវបង់បន្ត (Next Payment Due)</label>
                                    <input type="date" name="next_payment_date" id="next_payment_date" 
                                           value="{{ old('next_payment_date', date('Y-m-d', strtotime('+1 month'))) }}" 
                                           class="form-control form-control-custom">
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label-custom">ទឹកប្រាក់ជារៀលជាក់ស្តែង (KHR Input)</label>
                                    <input type="number" step="100" name="amount_khr" id="amount_khr" 
                                           value="{{ old('amount_khr') }}" class="form-control form-control-custom" 
                                           placeholder="ស្វ័យប្រវត្តិតាមអត្រា ៤,១០០ ៛/$">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- ═════════════════════════════════════════════════════════════════
                 RIGHT COLUMN: LIVE RECEIPT SUMMARY & ACTIONS (STICKY)
            ═════════════════════════════════════════════════════════════════ --}}
            <div class="col-xl-5 col-lg-5">
                <div class="receipt-preview-card p-3">
                    
                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                        <span class="fw-bold text-dark d-flex align-items-center gap-1.5" style="font-size:0.9rem;">
                            <i class="bi bi-receipt text-warning"></i>
                            <span>សង្ខេបការបង់ប្រាក់ (Payment Summary)</span>
                        </span>
                        <span id="previewStatusBadge" class="badge" style="background:#f1f5f9; color:#475569; font-size:0.7rem; padding:0.2rem 0.4rem;">
                            រង់ចាំការបង់
                        </span>
                    </div>

                    {{-- Student & Invoice Brief --}}
                    <div class="mb-3 p-2.5 rounded-2" style="background:#f8fafc; border:1px dashed #cbd5e1;">
                        <div class="d-flex justify-content-between py-0.5" style="font-size:0.8rem;">
                            <span class="text-muted">សិស្ស៖</span>
                            <span class="fw-bold text-dark" id="summaryStudentName">
                                {{ $selectedStudent ? $selectedStudent->user?->name : '— មិនទាន់ជ្រើស —' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-0.5" style="font-size:0.8rem;">
                            <span class="text-muted">អត្តលេខ ID៖</span>
                            <span class="font-monospace text-dark fw-semibold" id="summaryStudentAdmission">
                                {{ $selectedStudent ? $selectedStudent->admission_number : '—' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-0.5" style="font-size:0.8rem;">
                            <span class="text-muted">វិក្កយបត្រ៖</span>
                            <span class="fw-bold text-primary font-monospace" id="summaryInvoiceNumber">
                                {{ $invoice ? $invoice->invoice_number : '—' }}
                            </span>
                        </div>
                    </div>

                    {{-- Live Financial Matrix --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:0.82rem;">
                            <span class="text-muted">ទឹកប្រាក់សរុប (Net Amount)៖</span>
                            <span class="fw-semibold text-dark" id="summaryNetAmount">${{ $invoice ? number_format($invoice->net_amount, 2) : '0.00' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:0.82rem;">
                            <span class="text-muted">បានបង់រួចពីមុន (Total Paid)៖</span>
                            <span class="fw-semibold text-success" id="summaryPaidAmount">${{ $invoice ? number_format($invoice->total_paid, 2) : '0.00' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:0.82rem;">
                            <span class="text-muted">សមតុល្យបច្ចុប្បន្ន (Balance)៖</span>
                            <span class="fw-bold text-danger" id="summaryCurrentBalance">${{ $invoice ? number_format($invoice->balance, 2) : '0.00' }}</span>
                        </div>

                        {{-- BIG HIGHLIGHT ROW --}}
                        <div class="p-2.5 rounded-2 mt-2" style="background:#fffbeb; border:1px solid #fde68a;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark" style="font-size:0.88rem;">ទឹកប្រាក់បង់លើកនេះ៖</span>
                                <span class="fw-bold fs-5" style="color:#b45309;" id="summaryPayNowDisplay">$0.00</span>
                            </div>
                            <div class="text-end text-muted mt-0.5" style="font-size:0.75rem;" id="summaryPayNowKhr">
                                ≈ 0 ៛
                            </div>
                        </div>

                        <div class="d-flex justify-content-between py-1 mt-2 text-muted" style="font-size:0.82rem;">
                            <span>សមតុល្យនៅសល់ក្រោយបង់៖</span>
                            <span class="fw-bold text-dark" id="summaryRemainingAfterPay">$0.00</span>
                        </div>
                    </div>

                    {{-- Print Checkbox --}}
                    <div class="form-check p-2 mb-3 rounded-2" style="background:#f8fafc; border:1px solid #e2e8f0;">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="print_receipt" value="1" id="print_receipt" checked>
                        <label class="form-check-label small fw-bold text-dark" for="print_receipt" style="cursor:pointer; font-size:0.82rem;">
                            <i class="bi bi-printer text-success me-1"></i> បើកព្រីនបង្កាន់ដៃភ្លាមៗពេលរក្សាទុក
                        </label>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-gold py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm" id="submitBtn">
                            <i class="bi bi-printer-fill fs-6"></i>
                            <span style="font-size:0.92rem;">រក្សាទុក និងបោះពុម្ព (Save & Print)</span>
                        </button>

                        <button type="button" class="btn btn-sm btn-outline-secondary py-1.5" onclick="submitWithoutPrint()">
                            <i class="bi bi-check2 me-1"></i> រក្សាទុកតែប៉ុណ្ណោះ (Save Only)
                        </button>
                    </div>

                    {{-- Fast Keyboard Shortcut Tip --}}
                    <div class="text-center text-muted mt-2.5" style="font-size:0.72rem;">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>គន្លឹះរហ័ស៖ ចុច <strong>Alt + S</strong> ដើម្បីរក្សាទុកភ្លាមៗ
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let currentInvoiceBalance = parseFloat("{{ $invoice ? $invoice->balance : 0 }}") || 0;
let currentInvoiceNet = parseFloat("{{ $invoice ? $invoice->net_amount : 0 }}") || 0;
let currentInvoicePaid = parseFloat("{{ $invoice ? $invoice->total_paid : 0 }}") || 0;

// Filter student dropdown options live
function filterStudentOptions(query) {
    const q = query.trim().toLowerCase();
    const select = document.getElementById('student_selector');
    if (!select) return;

    let matchCount = 0;
    const options = select.options;

    for (let i = 1; i < options.length; i++) {
        const opt = options[i];
        const searchStr = opt.getAttribute('data-search') || opt.text.toLowerCase();
        if (q === '' || searchStr.includes(q)) {
            opt.style.display = '';
            matchCount++;
        } else {
            opt.style.display = 'none';
        }
    }

    const badge = document.getElementById('matchCountBadge');
    if (badge) {
        badge.textContent = q ? `រកឃើញ ${matchCount} នាក់` : '';
    }
}

function clearStudentSearch() {
    const input = document.getElementById('studentSearchInput');
    if (input) {
        input.value = '';
        filterStudentOptions('');
        input.focus();
    }
}

// Student Selection
function onStudentSelected(studentId) {
    if (!studentId) return;
    const url = new URL(window.location.href);
    url.searchParams.set('student_id', studentId);
    url.searchParams.delete('invoice_id');
    window.location.href = url.toString();
}

// Invoice Selection
function onInvoiceSelected(select) {
    const selected = select.options[select.selectedIndex];
    if (!selected || !selected.value) {
        resetSummary();
        return;
    }

    const balance = parseFloat(selected.getAttribute('data-balance')) || 0;
    const net = parseFloat(selected.getAttribute('data-net')) || 0;
    const paid = parseFloat(selected.getAttribute('data-paid')) || 0;
    const number = selected.getAttribute('data-number') || '—';
    const status = selected.getAttribute('data-status') || 'unpaid';

    currentInvoiceBalance = balance;
    currentInvoiceNet = net;
    currentInvoicePaid = paid;

    document.getElementById('summaryInvoiceNumber').textContent = number;
    document.getElementById('summaryNetAmount').textContent = '$' + net.toFixed(2);
    document.getElementById('summaryPaidAmount').textContent = '$' + paid.toFixed(2);
    document.getElementById('summaryCurrentBalance').textContent = '$' + balance.toFixed(2);

    const maxText = document.getElementById('maxPayableText');
    if (maxText) {
        maxText.innerHTML = `អតិបរមាអាចបង់៖ <strong class="text-dark">$${balance.toFixed(2)}</strong>`;
    }

    const amountInput = document.getElementById('amount');
    amountInput.max = balance;
    amountInput.value = balance.toFixed(2);

    updateAmounts(balance);
}

// Quick amount shortcut buttons (100%, 50%, 25%)
function applyQuickAmount(fraction) {
    if (currentInvoiceBalance <= 0) return;
    const calc = Math.round(currentInvoiceBalance * fraction * 100) / 100;
    const amountInput = document.getElementById('amount');
    amountInput.value = calc.toFixed(2);
    updateAmounts(calc);
}

function clearAmount() {
    const amountInput = document.getElementById('amount');
    amountInput.value = '';
    updateAmounts(0);
    amountInput.focus();
}

// Calculate KHR conversion & Summary preview
function updateAmounts(val) {
    const payVal = parseFloat(val) || 0;
    const khrEquivalent = Math.round(payVal * 4100 / 100) * 100;

    // Display in Pill
    document.getElementById('khrEquivalentDisplay').textContent = khrEquivalent.toLocaleString() + ' ៛';

    // Auto-update amount_khr input if user hasn't explicitly overwritten it
    const khrInput = document.getElementById('amount_khr');
    if (khrInput && !khrInput.dataset.userEdited) {
        khrInput.value = khrEquivalent > 0 ? khrEquivalent : '';
    }

    // Right side live preview
    document.getElementById('summaryPayNowDisplay').textContent = '$' + payVal.toFixed(2);
    document.getElementById('summaryPayNowKhr').textContent = '≈ ' + khrEquivalent.toLocaleString() + ' ៛';

    const remain = Math.max(0, currentInvoiceBalance - payVal);
    const remainEl = document.getElementById('summaryRemainingAfterPay');
    remainEl.textContent = '$' + remain.toFixed(2);

    const statusBadge = document.getElementById('previewStatusBadge');
    if (statusBadge) {
        if (payVal <= 0) {
            statusBadge.className = 'badge bg-secondary';
            statusBadge.textContent = 'រង់ចាំការបង់';
        } else if (remain <= 0) {
            statusBadge.className = 'badge bg-success';
            statusBadge.textContent = 'បង់គ្រប់ (Full Paid)';
            remainEl.className = 'fw-bold text-success';
        } else {
            statusBadge.className = 'badge bg-warning text-dark';
            statusBadge.textContent = 'បង់ខ្លះ (Partial)';
            remainEl.className = 'fw-bold text-danger';
        }
    }
}

function resetSummary() {
    currentInvoiceBalance = 0;
    currentInvoiceNet = 0;
    currentInvoicePaid = 0;
    document.getElementById('summaryInvoiceNumber').textContent = '—';
    document.getElementById('summaryNetAmount').textContent = '$0.00';
    document.getElementById('summaryPaidAmount').textContent = '$0.00';
    document.getElementById('summaryCurrentBalance').textContent = '$0.00';
    document.getElementById('summaryPayNowDisplay').textContent = '$0.00';
    document.getElementById('summaryRemainingAfterPay').textContent = '$0.00';
    document.getElementById('khrEquivalentDisplay').textContent = '0 ៛';
}

// Period recalculation helper
function onPeriodTypeChange(type) {
    const monthsInput = document.getElementById('period_months');
    const monthsCol = document.getElementById('monthsInputCol');

    let months = 1;
    if (type === 'month') {
        months = 1;
        if (monthsCol) monthsCol.style.display = 'block';
    } else if (type === 'trimester') {
        months = 3;
        if (monthsCol) monthsCol.style.display = 'none';
    } else if (type === 'semester') {
        months = 6;
        if (monthsCol) monthsCol.style.display = 'none';
    } else if (type === 'year') {
        months = 12;
        if (monthsCol) monthsCol.style.display = 'none';
    }

    if (monthsInput) monthsInput.value = months;
    recalculateDates();
}

function recalculateDates() {
    const startDateInput = document.getElementById('period_start_date');
    const endDateInput = document.getElementById('period_end_date');
    const nextDueInput = document.getElementById('next_payment_date');
    const monthsInput = document.getElementById('period_months');

    if (!startDateInput || !startDateInput.value) return;

    const months = parseInt(monthsInput?.value) || 1;
    const start = new Date(startDateInput.value);

    // Calculate end date (+months)
    const end = new Date(start);
    end.setMonth(end.getMonth() + months);

    const formatYmd = d => d.toISOString().split('T')[0];
    const endYmd = formatYmd(end);

    if (endDateInput) endDateInput.value = endYmd;
    if (nextDueInput) nextDueInput.value = endYmd;
}

function submitWithoutPrint() {
    const printCheck = document.getElementById('print_receipt');
    if (printCheck) printCheck.checked = false;
    document.getElementById('paymentForm').submit();
}

// Keyboard Shortcut: Alt + S to submit
document.addEventListener('keydown', function(e) {
    if ((e.altKey && e.key.toLowerCase() === 's') || (e.ctrlKey && e.key === 'Enter')) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) submitBtn.click();
    }
});

// Event Listeners on Load
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            updateAmounts(this.value);
        });
    }

    const khrInput = document.getElementById('amount_khr');
    if (khrInput) {
        khrInput.addEventListener('input', function() {
            this.dataset.userEdited = 'true';
        });
    }

    // Auto-select invoice if there is only 1 option available in the dropdown
    const invoiceSelect = document.getElementById('student_invoice_id');
    if (invoiceSelect && invoiceSelect.tagName === 'SELECT') {
        if (invoiceSelect.options.length === 2 && !invoiceSelect.value) {
            // Option 0 is placeholder, Option 1 is the single invoice
            invoiceSelect.selectedIndex = 1;
            onInvoiceSelected(invoiceSelect);
        } else if (invoiceSelect.value) {
            onInvoiceSelected(invoiceSelect);
        }
    } else {
        // Invoice is already fixed as hidden input
        updateAmounts(currentInvoiceBalance);
    }
});
</script>
@endpush
@endsection
