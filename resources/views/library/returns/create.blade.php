@extends('layouts.app')

@section('title', 'កត់ត្រាការសងសៀវភៅ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">កត់ត្រាការសងសៀវភៅ</h1>
            <p class="text-muted small mb-0 mt-1">ទទួលសៀវភៅដែលបានខ្ចីមកវិញ ពិនិត្យការហួសកាលកំណត់ និងកត់ត្រាប្រាក់ពិន័យ</p>
        </div>
        <a href="{{ route('library.issues.show', $issue) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅការខ្ចី
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            {{-- Loan Summary Card --}}
            <div class="card border-0 shadow-xs rounded-3 bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3">ព័ត៌មានលម្អិតនៃការខ្ចី</h5>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="text-muted small">ចំណងជើងសៀវភៅ</div>
                        <div class="h6 fw-bold text-dark mb-0">{{ $issue->library_book_copy->library_book->title ?? '—' }}</div>
                        <span class="badge bg-light text-dark border font-monospace mt-1">
                            លេខកូដ #{{ $issue->library_book_copy->accession_number }}
                        </span>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="text-muted small">អ្នកខ្ចី</div>
                        <div class="fw-bold text-dark mb-0">{{ $issue->user->name ?? '—' }}</div>
                        <div class="text-muted small">
                            @if($issue->user && $issue->user->student)
                                សិស្ស &bull; អត្តលេខ #{{ $issue->user->student->admission_number }}
                            @elseif($issue->user && $issue->user->employee)
                                បុគ្គលិក &bull; កូដ #{{ $issue->user->employee->employee_code }}
                            @else
                                {{ $issue->user->email ?? '' }}
                            @endif
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="text-muted small">ថ្ងៃខ្ចី</div>
                        <div class="fw-semibold text-dark">{{ $issue->issue_date ? $issue->issue_date->format('d/m/Y') : '-' }}</div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="text-muted small">ថ្ងៃកំណត់សង</div>
                        <div class="fw-semibold {{ $overdueDays > 0 ? 'text-danger' : 'text-dark' }}">
                            {{ $effectiveDueDate ? $effectiveDueDate->format('d/m/Y') : 'គ្មានថ្ងៃកំណត់' }}
                            @if(!$hasOriginalDueDate)
                                <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.68rem;">(គោល ៧ថ្ងៃ)</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="text-muted small">ការគណនាការហួសកាលកំណត់</div>
                        @if($overdueDays > 0)
                            <span class="badge bg-danger text-white fs-6">
                                <i class="bi bi-exclamation-triangle me-1"></i> ហួសកាលកំណត់ {{ $overdueDays }} ថ្ងៃ
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success fs-6">
                                <i class="bi bi-check-circle me-1"></i> ទាន់ពេលវេលា
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Check-in Form Card --}}
            <div class="card border-0 shadow-xs rounded-3 bg-white p-4">
                <form method="POST" action="{{ route('library.returns.store') }}">
                    @csrf
                    <input type="hidden" name="library_issue_id" value="{{ $issue->id }}">

                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-clock-history text-primary me-1"></i> ព័ត៌មានការសងត្រឡប់ និងគណនារយៈពេលពិន័យ
                    </h5>

                    {{-- Due Date, Return Date & Daily Rate --}}
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-dark">
                                ថ្ងៃកំណត់ត្រូវសង (Due Date) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-danger"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" id="dueDateInput" class="form-control" 
                                       value="{{ $effectiveDueDate ? $effectiveDueDate->format('Y-m-d') : '' }}" required>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.74rem;">
                                ថ្ងៃកំណត់ដែលត្រូវសងសៀវភៅ
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-dark">
                                កាលបរិច្ឆេទសងជាក់ស្តែង <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" name="return_date" id="returnDateInput" 
                                       class="form-control @error('return_date') is-invalid @enderror" 
                                       value="{{ old('return_date', $defaultReturnDate) }}" required>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.74rem;">
                                ថ្ងៃដែលយកសៀវភៅមកសងជាក់ស្តែង
                            </div>
                            @error('return_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-bold text-dark">
                                អត្រាប្រាក់ពិន័យក្នុង ១ ថ្ងៃ
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold text-dark">$</span>
                                <input type="number" step="0.05" min="0" id="dailyRateInput" class="form-control" value="{{ $defaultDailyRate }}" placeholder="0.50">
                                <span class="input-group-text bg-light text-muted small">/ ថ្ងៃ</span>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.74rem;">
                                អត្រាគិតពិន័យក្នុងមួយថ្ងៃហួសកំណត់
                            </div>
                        </div>
                    </div>

                    {{-- Live Duration & Overdue Analysis Card --}}
                    <div class="card border rounded-3 p-3 mb-3 bg-light">
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                            <span class="fw-bold text-dark small">
                                <i class="bi bi-calendar-range text-primary me-1"></i> ការគណនារយៈពេលខ្ចី និងការហួសកាលកំណត់
                            </span>
                            <span id="overdueBadge" class="badge bg-danger fs-6 px-2.5 py-1">
                                ហួសកំណត់ 0 ថ្ងៃ
                            </span>
                        </div>

                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size: 0.75rem;">ថ្ងៃខ្ចីសៀវភៅ</div>
                                <div class="fw-semibold text-dark small" id="textIssueDate">
                                    {{ $issue->issue_date ? $issue->issue_date->format('d/m/Y') : '—' }}
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size: 0.75rem;">ថ្ងៃកំណត់ត្រូវសង</div>
                                <div class="fw-semibold text-dark small" id="textDueDate">
                                    {{ $issue->due_date ? $issue->due_date->format('d/m/Y') : 'គ្មានថ្ងៃកំណត់' }}
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size: 0.75rem;">ថ្ងៃសងជាក់ស្តែង</div>
                                <div class="fw-semibold text-dark small" id="textReturnDate">
                                    —
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted" style="font-size: 0.75rem;">រយៈពេលខ្ចីសរុប</div>
                                <div class="fw-bold text-primary small" id="textTotalDuration">
                                    0 ថ្ងៃ
                                </div>
                            </div>
                        </div>

                        {{-- Penalty Timeline Detail Box --}}
                        <div id="penaltyDetailBox" class="mt-3 pt-2.5 border-top">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                <div>
                                    <div class="text-muted small" style="font-size: 0.78rem;">
                                        <i class="bi bi-clock-history text-danger me-1"></i>
                                        ចំនួនថ្ងៃហួសកាលកំណត់: 
                                        <strong id="overdueDurationText" class="text-danger fs-6">{{ $overdueDays }} ថ្ងៃ</strong>
                                        <span id="overduePeriodRange" class="text-muted ms-1 small"></span>
                                    </div>
                                    <div class="text-muted small mt-0.5" id="formulaCalculationText" style="font-size: 0.78rem;">
                                        រូបមន្តគណនា: {{ $overdueDays }} ថ្ងៃ × ${{ number_format($defaultDailyRate, 2) }} = <strong class="text-danger">${{ $defaultFineAmount }}</strong>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5" style="font-size: 0.8rem;">
                                        <i class="bi bi-lightning-charge-fill me-1"></i> គណនា Auto: <strong id="calculatedFineSummary" class="text-danger">${{ $defaultFineAmount }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fine Amount Input --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold text-dark mb-0">
                                ចំនួនប្រាក់ពិន័យ ($) <span class="text-danger">*</span>
                                <span class="badge bg-light text-success border ms-1" style="font-size: 0.7rem; font-weight: normal;">
                                    <i class="bi bi-check-circle me-1"></i>គណនា Auto
                                </span>
                            </label>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-xs btn-outline-danger py-0.5 px-2" style="font-size: 0.74rem;" onclick="setFineAmount(0)">
                                    <i class="bi bi-x-circle me-1"></i>លើកលែងពិន័យ ($0.00)
                                </button>
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-white fw-bold text-danger fs-5">$</span>
                            <input type="number" step="0.01" min="0" name="fine_amount" id="fineAmountInput" 
                                   class="form-control form-control-lg fw-bold text-danger @error('fine_amount') is-invalid @enderror" 
                                   value="{{ old('fine_amount', $defaultFineAmount) }}" required>
                            <span class="input-group-text bg-light text-muted" id="fineAmountKhr" style="font-size: 0.88rem; min-width: 120px;">
                                ≈ {{ number_format(round($defaultFineAmount * 4100, -2)) }} ៛
                            </span>
                        </div>
                        <div class="form-text text-muted d-flex flex-column flex-sm-row justify-content-between mt-1">
                            <span>តម្លៃពិន័យត្រូវបានគណនាដោយស្វ័យប្រវត្តិតាមចំនួនថ្ងៃហួសកំណត់ និងអត្រាក្នុងមួយថ្ងៃ។</span>
                            <span class="text-muted" style="font-size: 0.75rem;">អត្រាប្តូរប្រាក់: ៤,១០០ ៛ / $</span>
                        </div>
                        @error('fine_amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-4">
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <i class="bi bi-info-circle text-primary fs-5"></i>
                            <div>
                                បន្ទាប់ពីកត់ត្រាការសងនេះរួច ស្ថានភាពច្បាប់សៀវភៅនឹងផ្លាស់ប្តូរទៅជា <strong class="text-dark">នៅសល់</strong> ដោយស្វ័យប្រវត្តិ (លើកលែងតែត្រូវបានកំណត់ថាខូចខាត ឬបាត់បង់)។
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('library.issues.show', $issue) }}" class="btn btn-sm btn-outline-secondary">បោះបង់</a>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-check-lg me-1"></i> បញ្ជាក់ការសងសៀវភៅ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ISSUE_DATE = '{{ $issue->issue_date ? $issue->issue_date->format('Y-m-d') : '' }}';
const DUE_DATE = '{{ $issue->due_date ? $issue->due_date->format('Y-m-d') : '' }}';
const KHR_RATE = 4100;

let currentCalculatedFine = '{{ $defaultFineAmount }}';

function parseDateParts(dateStr) {
    if (!dateStr) return null;
    const parts = dateStr.split('-');
    if (parts.length !== 3) return null;
    return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
}

function formatDisplayDate(dateStr) {
    if (!dateStr) return '—';
    const parts = dateStr.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
}

function calculateDuration() {
    const returnDateVal = document.getElementById('returnDateInput')?.value;
    const dailyRateVal = parseFloat(document.getElementById('dailyRateInput')?.value) || 0;

    // Display Return Date
    const displayReturnDateEl = document.getElementById('textReturnDate');
    if (displayReturnDateEl) {
        displayReturnDateEl.textContent = formatDisplayDate(returnDateVal);
    }

    if (!returnDateVal) return;

    // Total loan duration
    if (ISSUE_DATE) {
        const issueDate = parseDateParts(ISSUE_DATE);
        const returnDate = parseDateParts(returnDateVal);
        if (issueDate && returnDate) {
            const diffTime = returnDate.getTime() - issueDate.getTime();
            const diffDays = Math.max(0, Math.round(diffTime / (1000 * 60 * 60 * 24)));
            const totalDurationEl = document.getElementById('textTotalDuration');
            if (totalDurationEl) {
                totalDurationEl.textContent = `${diffDays} ថ្ងៃ`;
            }
        }
    }

    // Overdue calculation
    const overdueBadge = document.getElementById('overdueBadge');
    const overdueDurationText = document.getElementById('overdueDurationText');
    const overduePeriodRange = document.getElementById('overduePeriodRange');
    const formulaCalculationText = document.getElementById('formulaCalculationText');
    const summaryBadge = document.getElementById('calculatedFineSummary');
    const dueDateVal = document.getElementById('dueDateInput')?.value || DUE_DATE;

    const textDueDateEl = document.getElementById('textDueDate');
    if (textDueDateEl && dueDateVal) {
        textDueDateEl.textContent = formatDisplayDate(dueDateVal);
    }

    let overdueDays = 0;
    if (dueDateVal) {
        const dueDate = parseDateParts(dueDateVal);
        const returnDate = parseDateParts(returnDateVal);
        if (dueDate && returnDate) {
            const diffTime = returnDate.getTime() - dueDate.getTime();
            overdueDays = Math.max(0, Math.round(diffTime / (1000 * 60 * 60 * 24)));
        }
    }

    if (overdueDays > 0) {
        if (overdueBadge) {
            overdueBadge.className = 'badge bg-danger fs-6 px-2.5 py-1';
            overdueBadge.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> ហួសកំណត់ ${overdueDays} ថ្ងៃ`;
        }
        if (overdueDurationText) {
            overdueDurationText.className = 'text-danger fw-bold fs-6';
            overdueDurationText.textContent = `${overdueDays} ថ្ងៃ`;
        }
        if (overduePeriodRange) {
            overduePeriodRange.textContent = `(ចាប់ពី ${formatDisplayDate(dueDateVal)} ដល់ ${formatDisplayDate(returnDateVal)})`;
        }

        currentCalculatedFine = (overdueDays * dailyRateVal).toFixed(2);

        if (formulaCalculationText) {
            formulaCalculationText.innerHTML = `រូបមន្តគណនា: <strong>${overdueDays} ថ្ងៃ</strong> × <strong>$${dailyRateVal.toFixed(2)}</strong> = <strong class="text-danger fs-6">$${currentCalculatedFine}</strong>`;
        }
        if (summaryBadge) {
            summaryBadge.textContent = '$' + currentCalculatedFine;
        }
    } else {
        if (overdueBadge) {
            overdueBadge.className = 'badge bg-success-subtle text-success fs-6 px-2.5 py-1 border border-success-subtle';
            overdueBadge.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> ទាន់ពេលវេលា`;
        }
        if (overdueDurationText) {
            overdueDurationText.className = 'text-success fw-bold';
            overdueDurationText.textContent = `០ ថ្ងៃ (មិនហួសកំណត់)`;
        }
        if (overduePeriodRange) {
            overduePeriodRange.textContent = '';
        }
        currentCalculatedFine = '0.00';
        if (formulaCalculationText) {
            formulaCalculationText.innerHTML = `<span class="text-success"><i class="bi bi-check2-circle me-1"></i>ការសងនេះទាន់ពេលវេលា គ្មានការពិន័យយឺតយ៉ាវឡើយ</span>`;
        }
        if (summaryBadge) {
            summaryBadge.textContent = '$0.00';
        }
    }

    updateKhrEquivalent();
}

function setLoanDurationDays(days) {
    if (!ISSUE_DATE) return;
    const issueDate = parseDateParts(ISSUE_DATE);
    const dueDateInput = document.getElementById('dueDateInput');
    if (!dueDateInput || !issueDate) return;

    if (days === 0) {
        dueDateInput.value = ISSUE_DATE;
    } else {
        const targetDate = new Date(issueDate.getTime());
        targetDate.setDate(targetDate.getDate() + days);
        const y = targetDate.getFullYear();
        const m = String(targetDate.getMonth() + 1).padStart(2, '0');
        const d = String(targetDate.getDate()).padStart(2, '0');
        dueDateInput.value = `${y}-${m}-${d}`;
    }

    calculateDuration();
    applyCalculatedFine();
}

function updateKhrEquivalent() {
    const fineInput = document.getElementById('fineAmountInput');
    const khrEl = document.getElementById('fineAmountKhr');
    if (!fineInput || !khrEl) return;

    const val = parseFloat(fineInput.value) || 0;
    const khr = Math.round(val * KHR_RATE);
    khrEl.textContent = `≈ ${khr.toLocaleString()} ៛`;
}

function setDailyRate(rate) {
    const dailyRateInput = document.getElementById('dailyRateInput');
    if (dailyRateInput) {
        dailyRateInput.value = rate.toFixed(2);
        calculateDuration();
        applyCalculatedFine();
    }
}

function applyCalculatedFine() {
    const fineInput = document.getElementById('fineAmountInput');
    if (fineInput) {
        fineInput.value = currentCalculatedFine;
        updateKhrEquivalent();
    }
}

function setFineAmount(val) {
    const fineInput = document.getElementById('fineAmountInput');
    if (fineInput) {
        fineInput.value = Number(val).toFixed(2);
        updateKhrEquivalent();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const returnDateInput = document.getElementById('returnDateInput');
    const dueDateInput = document.getElementById('dueDateInput');
    const dailyRateInput = document.getElementById('dailyRateInput');
    const fineAmountInput = document.getElementById('fineAmountInput');

    if (returnDateInput) {
        ['input', 'change'].forEach(evt => {
            returnDateInput.addEventListener(evt, function() {
                calculateDuration();
                applyCalculatedFine();
            });
        });
    }
    if (dueDateInput) {
        ['input', 'change'].forEach(evt => {
            dueDateInput.addEventListener(evt, function() {
                calculateDuration();
                applyCalculatedFine();
            });
        });
    }
    if (dailyRateInput) {
        ['input', 'change'].forEach(evt => {
            dailyRateInput.addEventListener(evt, function() {
                calculateDuration();
                applyCalculatedFine();
            });
        });
    }
    if (fineAmountInput) {
        fineAmountInput.addEventListener('input', updateKhrEquivalent);
    }

    // Initial calculation on page load
    calculateDuration();
    applyCalculatedFine();
});
</script>
@endpush
