@extends('layouts.app')

@section('title', 'បង្កើតវិក្កយបត្រសិស្ស — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item"><a href="{{ route('student-invoices.index') }}" class="text-decoration-none text-muted">វិក្កយបត្រសិស្ស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បង្កើតវិក្កយបត្រ</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('student-invoices.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-2">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅកាន់វិក្កយបត្រ
        </a>
        <h1 class="h3 fw-bold text-dark mb-0">បង្កើតវិក្កយបត្រសិស្ស</h1>
        <p class="text-muted small mb-0 mt-1">បង្កើតរបាយការណ៍វិក្កយបត្រតាមមុខទំនិញ/សេវា ព្រមទាំងការគណនាបញ្ចុះតម្លៃ</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-xs rounded-3 mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('student-invoices.store') }}" method="POST" id="invoiceForm">
        @csrf

        <div class="row g-4">
            {{-- Header Information --}}
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-xs rounded-3 bg-white p-4 h-100">
                    <h5 class="fw-bold text-dark mb-3">ព័ត៌មានលម្អិតវិក្កយបត្រ</h5>

                    <div class="mb-3">
                        <label for="student_id" class="form-label small fw-bold text-dark">សិស្ស <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">ជ្រើសរើសសិស្ស...</option>
                            @foreach($students as $st)
                                <option value="{{ $st->id }}" {{ old('student_id', $selectedStudentId) == $st->id ? 'selected' : '' }}>
                                    {{ $st->user?->name }} ({{ $st->admission_number }}) - {{ $st->schoolClass?->name ?? 'គ្មានថ្នាក់' }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fee_structure_id" class="form-label small fw-bold text-dark">រចនាសម្ព័ន្ធកម្រៃ (គំរូស្រេចចិត្ត)</label>
                        <select name="fee_structure_id" id="fee_structure_id" class="form-select @error('fee_structure_id') is-invalid @enderror">
                            <option value="">គ្មាន / វិក្កយបត្រផ្ទាល់ខ្លួន</option>
                            @foreach($feeStructures as $fs)
                                <option value="{{ $fs->id }}"
                                        data-category-id="{{ $fs->fee_category_id }}"
                                        data-amount="{{ $fs->amount }}"
                                        {{ old('fee_structure_id') == $fs->id ? 'selected' : '' }}>
                                    {{ $fs->fee_category?->name }} (${{ number_format($fs->amount, 2) }}) - {{ $fs->class?->name ?? 'ទូទាំងសាលា' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text small">ការជ្រើសរើសរចនាសម្ព័ន្ធកម្រៃ អាចបំពេញធាតុដំបូងដោយស្វ័យប្រវត្តិ។</div>
                        @error('fee_structure_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label small fw-bold text-dark">ថ្ងៃផុតកំណត់បង់ប្រាក់ <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', now()->addDays(14)->format('Y-m-d')) }}" class="form-control @error('due_date') is-invalid @enderror" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="discount_amount" class="form-label small fw-bold text-dark">បញ្ចុះតម្លៃ ($)</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', '0.00') }}" class="form-control @error('discount_amount') is-invalid @enderror" oninput="calculateTotals()">
                        </div>
                        @error('discount_amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Financial Summary Box --}}
                    <div class="p-3 rounded-2 bg-light border mt-4">
                        <h6 class="fw-bold text-dark mb-2">សង្ខេបវិក្កយបត្រ</h6>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>ចំនួនទឹកប្រាក់សរុប៖</span>
                            <span class="fw-medium text-dark" id="displayTotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>បញ្ចុះតម្លៃ៖</span>
                            <span class="text-danger" id="displayDiscount">-$0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold text-dark">
                            <span>ទឹកប្រាក់ត្រូវបង់សុទ្ធ៖</span>
                            <span class="text-primary fs-5" id="displayNet">$0.00</span>
                        </div>
                        <div class="text-end fw-semibold text-muted small mt-1" id="displayNetKhr">0 ៛</div>
                    </div>
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-xs rounded-3 bg-white p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">មុខទំនិញ/សេវាក្នុងវិក្កយបត្រ</h5>
                            <span class="text-muted small">ទាមទារយ៉ាងហោចណាស់ធាតុមួយ។ ផលបូកធាតុទាំងអស់នឹងបង្កើតបានជាចំនួនសរុបនៃវិក្កយបត្រ។</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">
                            <i class="bi bi-plus-circle me-1"></i>+ បន្ថែមមុខទំនិញ/សេវា
                        </button>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="lineItemsTable">
                            <thead class="table-light small text-muted">
                                <tr>
                                    <th style="min-width: 250px;">ប្រភេទកម្រៃសេវា <span class="text-danger">*</span></th>
                                    <th style="width: 180px;">ចំនួនទឹកប្រាក់ ($) <span class="text-danger">*</span></th>
                                    <th style="width: 60px;" class="text-center">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody id="lineItemsBody">
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][fee_category_id]" class="form-select form-select-sm fee-category-select" required>
                                            <option value="">ជ្រើសរើសប្រភេទកម្រៃ...</option>
                                            @foreach($feeCategories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0.01" name="items[0][amount]" class="form-control form-control-sm item-amount" placeholder="0.00" oninput="calculateTotals()" required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeRow(this)" title="លុប">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-auto pt-3 border-top">
                        <a href="{{ route('student-invoices.index') }}" class="btn btn-sm btn-light border">បោះបង់</a>
                        <button type="submit" class="btn btn-sm btn-gold text-white px-4" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                            បង្កើតវិក្កយបត្រ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let rowIndex = 1;

function addRow(categoryId = '', amount = '') {
    const tbody = document.getElementById('lineItemsBody');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td>
            <select name="items[${rowIndex}][fee_category_id]" class="form-select form-select-sm fee-category-select" required>
                <option value="">ជ្រើសរើសប្រភេទកម្រៃ...</option>
                @foreach($feeCategories as $cat)
                    <option value="{{ $cat->id }}" ${categoryId == '{{ $cat->id }}' ? 'selected' : ''}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">$</span>
                <input type="number" step="0.01" min="0.01" name="items[${rowIndex}][amount]" value="${amount}" class="form-control form-control-sm item-amount" placeholder="0.00" oninput="calculateTotals()" required>
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeRow(this)" title="លុប">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    rowIndex++;
    calculateTotals();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length <= 1) {
        alert('វិក្កយបត្រត្រូវតែមានយ៉ាងហោចណាស់មួយមុខទំនិញ/សេវា។');
        return;
    }
    btn.closest('tr').remove();
    calculateTotals();
}

function calculateTotals() {
    let total = 0.0;
    document.querySelectorAll('.item-amount').forEach(input => {
        const val = parseFloat(input.value);
        if (!isNaN(val) && val > 0) {
            total += val;
        }
    });

    let discount = parseFloat(document.getElementById('discount_amount').value);
    if (isNaN(discount) || discount < 0) {
        discount = 0.0;
    }

    if (discount > total) {
        document.getElementById('discount_amount').classList.add('is-invalid');
    } else {
        document.getElementById('discount_amount').classList.remove('is-invalid');
    }

    const net = Math.max(0.0, total - discount);
    const khr = Math.round(net * 4100 / 100) * 100;

    document.getElementById('displayTotal').textContent = '$' + total.toFixed(2);
    document.getElementById('displayDiscount').textContent = '-$' + discount.toFixed(2);
    document.getElementById('displayNet').textContent = '$' + net.toFixed(2);
    const khrElement = document.getElementById('displayNetKhr');
    if (khrElement) {
        khrElement.textContent = khr.toLocaleString() + ' ៛';
    }
}

// Pre-fill from selected fee structure if changed
document.getElementById('fee_structure_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const catId = selected.getAttribute('data-category-id');
    const amount = selected.getAttribute('data-amount');
    if (catId && amount) {
        const firstRowSelect = document.querySelector('.fee-category-select');
        const firstRowAmount = document.querySelector('.item-amount');
        if (firstRowSelect && (!firstRowSelect.value || firstRowAmount.value == '')) {
            firstRowSelect.value = catId;
            firstRowAmount.value = parseFloat(amount).toFixed(2);
            calculateTotals();
        }
    }
});

document.addEventListener('DOMContentLoaded', calculateTotals);
</script>
@endsection
