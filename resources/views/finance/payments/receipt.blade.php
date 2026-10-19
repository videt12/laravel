@extends('layouts.app')

@section('title', 'បង្កាន់ដៃបង់ប្រាក់ - ' . ($studentPayment->formatted_receipt_number ?? 'BPS Receipt'))

@section('content')
@php
    $student = $studentPayment->student;
    $invoice = $studentPayment->student_invoice;
    $profile = $student?->profile;
    $schoolClass = $student?->schoolClass;
    $shiftModel = $student?->schoolShift;

    // Khmer months mapping
    $khmerMonths = [
        1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
        5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
        9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ'
    ];

    // Khmer digits
    $khmerDigits = ['0' => '០', '1' => '១', '2' => '២', '3' => '៣', '4' => '៤', '5' => '៥', '6' => '៦', '7' => '៧', '8' => '៨', '9' => '៩'];
    $toKhmerNum = function($val) use ($khmerDigits) {
        return strtr((string)$val, $khmerDigits);
    };

    $formatKhmerDate = function($date) use ($khmerMonths, $toKhmerNum) {
        if (!$date) return ['day' => '.......', 'month' => '..........', 'year' => '២០.....', 'short_year' => '..'];
        $c = \Carbon\Carbon::parse($date);
        return [
            'day' => $toKhmerNum($c->format('d')),
            'month' => $khmerMonths[(int)$c->format('m')] ?? $c->format('m'),
            'year' => $toKhmerNum($c->format('Y')),
            'short_year' => $toKhmerNum($c->format('y')),
        ];
    };

    $paymentDate = $formatKhmerDate($studentPayment->payment_date ?? now());
    $startDate = $formatKhmerDate($studentPayment->period_start_date);
    $endDate = $formatKhmerDate($studentPayment->period_end_date);
    $nextDate = $formatKhmerDate($studentPayment->next_payment_date ?? $invoice?->due_date);

    // Gender in Khmer
    $rawGender = strtolower($profile?->gender ?? '');
    $khmerGender = ($rawGender === 'male' || $rawGender === 'm' || $rawGender === 'ប្រុស') ? 'ប្រុស' : (($rawGender === 'female' || $rawGender === 'f' || $rawGender === 'ស្រី') ? 'ស្រី' : '—');

    // Student type: old or new
    $isOldStudent = $studentPayment->student_type === 'old' || (!$studentPayment->student_type && $student?->admission_date && $student->admission_date->lt(now()->subMonths(6)));
    $isNewStudent = $studentPayment->student_type === 'new' || (!$isOldStudent);

    // Shift
    $shiftName = strtolower($studentPayment->shift ?? ($shiftModel?->name ?? ''));
    $isMorning = str_contains($shiftName, 'morn') || str_contains($shiftName, 'ព្រឹក') || empty($shiftName);
    $isAfternoon = str_contains($shiftName, 'after') || str_contains($shiftName, 'រសៀល') || str_contains($shiftName, 'ល្ងាច');

    // Period type
    $periodType = $studentPayment->period_type ?? 'month';
    $periodMonths = $studentPayment->period_months ?? 1;

    // Academic Year display
    $academicYearName = $student?->academicYear?->name ?? now()->format('Y') . ' - ' . (now()->year + 1);

    // Amounts
    $amountUsd = (float)$studentPayment->amount;
    $amountKhr = $studentPayment->calculated_amount_khr;
    $amountWords = $studentPayment->spelled_amount_words;

    // Invoice details / Services mapping
    $details = $invoice?->invoice_details ?? collect();
    $kindergartenAmount = null;
    $primaryAmount = null;
    $englishAmount = null;

    foreach ($details as $d) {
        $catName = mb_strtolower($d->fee_category?->name ?? '');
        if (str_contains($catName, 'kindergarten') || str_contains($catName, 'មត្តេយ្យ')) {
            $kindergartenAmount = (float)$d->amount;
        } elseif (str_contains($catName, 'primary') || str_contains($catName, 'បឋម')) {
            $primaryAmount = (float)$d->amount;
        } elseif (str_contains($catName, 'english') || str_contains($catName, 'អង់គ្លេស')) {
            $englishAmount = (float)$d->amount;
        }
    }

    // Default fallback if no category matched: put payment amount into relevant row based on grade
    $gradeName = mb_strtolower($schoolClass?->grade?->name ?? ($schoolClass?->name ?? ''));
    if ($kindergartenAmount === null && $primaryAmount === null && $englishAmount === null) {
        if (str_contains($gradeName, 'kindergarten') || str_contains($gradeName, 'មត្តេយ្យ') || str_contains($gradeName, 'k')) {
            $kindergartenAmount = $amountUsd;
        } elseif (str_contains($gradeName, 'english') || str_contains($gradeName, 'អង់គ្លេស')) {
            $englishAmount = $amountUsd;
        } else {
            $primaryAmount = $amountUsd;
        }
    }
@endphp

<!-- Google Fonts for authentic Khmer typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Kantumruy+Pro:wght@400;500;600;700&family=Moul&family=Siemreap&display=swap" rel="stylesheet">

<style>
/* ─────────────────────────────────────────────────────────────
   BPS PAYMENT RECEIPT STYLES
───────────────────────────────────────────────────────────── */
.receipt-wrapper {
    font-family: 'Kantumruy Pro', 'Battambang', 'Segoe UI', Tahoma, sans-serif;
    color: #111827;
    background: #f1f5f9;
    padding: 20px 10px;
}

.receipt-sheet {
    background: #ffffff;
    width: 100%;
    max-width: 1300px;
    margin: 0 auto;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    padding: 20px;
}

/* Two-column layout matching the physical duplicate slip book */
.receipt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 24px;
    position: relative;
}

/* Vertical perforated divider line between duplicate slips */
.receipt-grid::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 1px;
    border-left: 2px dashed #cbd5e1;
    transform: translateX(-50%);
}

.single-slip-mode .receipt-grid {
    grid-template-columns: 1fr;
}
.single-slip-mode .receipt-grid::after {
    display: none;
}
.single-slip-mode .receipt-slip-copy-2 {
    display: none;
}

.receipt-slip {
    font-size: 11.5px;
    line-height: 1.45;
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Header Elements */
.school-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 8px;
}

.school-logo-col {
    flex: 0 0 72px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.school-logo-img,
.school-logo-svg {
    width: 68px;
    height: 68px;
    object-fit: contain;
}

.school-title-col {
    flex: 1;
    text-align: center;
}

.school-khmer-name {
    font-family: 'Moul', 'Kantumruy Pro', serif;
    font-size: 14px;
    font-weight: 700;
    color: #1e3a8a;
    line-height: 1.2;
    margin-bottom: 2px;
}

.school-eng-name {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.5px;
    color: #0f172a;
    margin-bottom: 2px;
}

.school-address {
    font-size: 8.5px;
    color: #475569;
    line-height: 1.2;
}

.school-contact-col {
    flex: 0 0 115px;
    text-align: right;
    font-size: 8px;
    line-height: 1.25;
    color: #1e293b;
}

.school-contact-col .tell-header {
    font-weight: 700;
    color: #0f172a;
}

.receipt-no-box {
    margin-top: 4px;
    font-size: 10px;
    font-weight: 700;
    color: #b91c1c;
}

/* Info lines with dotted underline */
.info-row {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    gap: 4px;
    margin-bottom: 4px;
    font-size: 11px;
}

.dotted-val {
    border-bottom: 1px dotted #475569;
    padding: 0 4px;
    min-height: 16px;
    display: inline-block;
    color: #0f172a;
    font-weight: 600;
}

.dotted-val-khmer {
    font-family: 'Kantumruy Pro', sans-serif;
    font-weight: 600;
}

.check-item {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    margin-right: 6px;
}

.check-circle {
    display: inline-block;
    width: 13px;
    height: 13px;
    border: 1.5px solid #334155;
    border-radius: 50%;
    text-align: center;
    line-height: 11px;
    font-size: 9px;
    font-weight: bold;
}

.check-circle.checked {
    background-color: #1e40af;
    color: #fff;
    border-color: #1e40af;
}

.next-due-banner {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 2px 6px;
    border-radius: 4px;
    margin: 5px 0;
    font-size: 10.5px;
    font-weight: 600;
    color: #b91c1c;
}

/* Service fee table */
.fee-table {
    width: 100%;
    border-collapse: collapse;
    margin: 6px 0;
    font-size: 10px;
}

.fee-table th, .fee-table td {
    border: 1px solid #64748b;
    padding: 3px 5px;
    vertical-align: middle;
}

.fee-table th {
    background-color: #e0f2fe;
    color: #0369a1;
    font-weight: 700;
    text-align: center;
}

.fee-table .th-amount-group {
    background-color: #bae6fd;
}

.fee-table td.center {
    text-align: center;
}

.fee-table td.right {
    text-align: right;
}

.fee-table td.bold {
    font-weight: 700;
}

.total-row {
    background-color: #f8fafc;
    font-weight: 700;
}

/* Notes section */
.notes-section {
    font-size: 8.5px;
    line-height: 1.35;
    color: #334155;
    margin: 4px 0;
    border-top: 1px solid #e2e8f0;
    padding-top: 3px;
}

.notes-title {
    font-weight: 700;
    color: #0f172a;
}

.notes-list {
    margin: 0;
    padding-left: 14px;
}

/* Footer / Signature */
.receipt-footer {
    margin-top: 8px;
    text-align: right;
    font-size: 10px;
}

.sign-box {
    margin-top: 2px;
    text-align: right;
    padding-right: 40px;
}

.sign-title {
    font-family: 'Moul', 'Kantumruy Pro', serif;
    font-size: 11px;
    color: #1e3a8a;
}

.sign-space {
    height: 40px;
}

/* Print Specific Rules */
@media print {
    @page {
        size: A4 landscape;
        margin: 6mm;
    }

    body {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .no-print, nav, .sidebar, .navbar, footer, .btn, .breadcrumb, .alert {
        display: none !important;
    }

    .receipt-wrapper {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .receipt-sheet {
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        max-width: 100% !important;
    }

    .receipt-grid {
        column-gap: 20px !important;
    }

    .receipt-grid::after {
        border-left: 1.5px dashed #94a3b8 !important;
    }

    .fee-table th {
        background-color: #e0f2fe !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .fee-table .th-amount-group {
        background-color: #bae6fd !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .next-due-banner {
        background-color: #f8fafc !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .check-circle.checked {
        background-color: #1e40af !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<div class="receipt-wrapper">
    <!-- Top Action Toolbar (Hidden during print) -->
    <div class="container-fluid max-w-1300 mb-3 no-print" style="max-width: 1300px;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-3 bg-white rounded-3 shadow-xs border">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('student-payments.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ (Payments)
                </a>
                <a href="{{ route('student-invoices.show', $studentPayment->student_invoice_id) }}" class="btn btn-sm btn-link text-decoration-none text-muted">
                    <i class="bi bi-receipt me-1"></i> មើលវិក្កយបត្រ (Invoice #INV-{{ $studentPayment->student_invoice_id }})
                </a>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                    {{ $studentPayment->formatted_receipt_number }}
                </span>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Dual / Single Copy Toggle -->
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="btnDualMode" onclick="toggleSlipMode('dual')">
                        <i class="bi bi-columns-gap me-1"></i> ច្បាប់ភ្លោះ (Dual 2-in-1)
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btnSingleMode" onclick="toggleSlipMode('single')">
                        <i class="bi bi-file-earmark me-1"></i> ច្បាប់ទោល (Single Copy)
                    </button>
                </div>

                <button type="button" class="btn btn-sm btn-primary px-3 shadow-xs" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> បោះពុម្ពបង្កាន់ដៃ (Print Receipt)
                </button>
            </div>
        </div>
    </div>

    <!-- Printable Receipt Sheet -->
    <div class="receipt-sheet" id="receiptSheet">
        <div class="receipt-grid">
            <!-- ══════════════════════════════════════════════════════════════
                 COPY 1: LEFT SLIP (អ្នកទទួល / Recipient & Student Copy)
            ══════════════════════════════════════════════════════════════ -->
            <div class="receipt-slip receipt-slip-copy-1">
                @include('finance.payments._receipt_content', [
                    'copyTitle' => 'អ្នកទទួល',
                    'copyRole' => 'receiver',
                ])
            </div>

            <!-- ══════════════════════════════════════════════════════════════
                 COPY 2: RIGHT SLIP (នាយក / School Director Copy)
            ══════════════════════════════════════════════════════════════ -->
            <div class="receipt-slip receipt-slip-copy-2">
                @include('finance.payments._receipt_content', [
                    'copyTitle' => 'នាយក',
                    'copyRole' => 'director',
                ])
            </div>
        </div>
    </div>
</div>

<script>
function toggleSlipMode(mode) {
    const sheet = document.getElementById('receiptSheet');
    const btnDual = document.getElementById('btnDualMode');
    const btnSingle = document.getElementById('btnSingleMode');

    if (mode === 'single') {
        sheet.classList.add('single-slip-mode');
        btnSingle.classList.add('active');
        btnDual.classList.remove('active');
    } else {
        sheet.classList.remove('single-slip-mode');
        btnDual.classList.add('active');
        btnSingle.classList.remove('active');
    }
}
</script>
@endsection
