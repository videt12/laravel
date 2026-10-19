@extends('layouts.app')

@section('title', 'បង្កើតបញ្ជីបើកប្រាក់បៀវត្ស — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}" class="text-decoration-none text-muted">ប្រាក់បៀវត្ស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បង្កើតបញ្ជីបើកប្រាក់</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.page-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-md, 8px); padding: 0.75rem 1.25rem; margin-bottom: 0.65rem;
    position: relative; overflow: hidden; color: #fff;
    box-shadow: 0 2px 8px rgba(232, 168, 56, 0.15);
}
.page-banner::before { content:''; position:absolute; top:-30px; right:-30px; width:130px; height:130px; background:rgba(255,255,255,.12); border-radius:50%; pointer-events:none; }
.page-banner::after { content:''; position:absolute; bottom:-25px; right:70px; width:90px; height:90px; background:rgba(255,255,255,.07); border-radius:50%; pointer-events:none; }
.page-banner .banner-icon { width:36px; height:36px; background:rgba(255,255,255,.2); border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:1.15rem; color:#fff; margin-bottom:0; flex-shrink:0; }
.page-banner h1 { font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 .15rem; line-height:1.2; }
.page-banner p { font-size:.78rem; color:rgba(255,255,255,.9); margin:0; line-height:1.3; }

.form-card { 
    background:#fff; 
    border:1px solid var(--border-soft,#e5e7eb); 
    border-top:3px solid var(--gold,#e8a838);
    border-radius:var(--radius-md,8px); 
    padding:1rem 1.15rem; 
    box-shadow:0 1px 3px rgba(0,0,0,.04); 
}
.section-title { font-size:.88rem; font-weight:700; color:var(--text-dark,#111827); margin-bottom:.75rem; padding-bottom:.4rem; border-bottom:2px solid #fef3c7; }
.form-label { font-size:.82rem; font-weight:600; color:var(--text-dark,#111827); margin-bottom:.25rem; }
.form-control, .form-select { border:1px solid var(--border,#cbd5e1); border-radius:6px; font-size:.85rem; padding:.38rem .75rem; height:36px; transition:all .15s ease-in-out; }
.form-control:focus, .form-select:focus { border-color:#e8a838; box-shadow:0 0 0 3px rgba(232,168,56,.18); outline:none; }
.info-box { background:#fffbeb; border:1px solid #fde68a; border-radius:6px; padding:.75rem; font-size:.82rem; }
.deduction-row { background:#fff8f8; border:1px solid #fee2e2; border-radius:6px; padding:.65rem; margin-bottom:.4rem; }
.btn-primary-action { background:#e8a838; border-color:#e8a838; color:#fff; font-weight:600; }
.btn-primary-action:hover { background:#c98b1e; border-color:#c98b1e; color:#fff; }
</style>
@endpush

@section('content')
<div class="page-banner d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2.5">
        <div class="banner-icon"><i class="bi bi-cash-coin"></i></div>
        <div>
            <h1>បង្កើតបញ្ជីបើកប្រាក់បៀវត្ស</h1>
            <p>ជ្រើសរើសបុគ្គលិក វដ្តកាលបរិច្ឆេទ និងបន្ថែមការកាត់ប្រាក់ផ្សេងៗ (បើមាន)។</p>
        </div>
    </div>
    <div>
        <a href="{{ route('payrolls.index') }}" class="btn btn-light btn-sm fw-semibold d-flex align-items-center gap-1.5 shadow-sm px-3 py-1.5" style="border-radius:6px; font-size:0.82rem;">
            <i class="bi bi-arrow-left"></i>
            <span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

@php
    $khmerMonths = [
        1 => 'មករា (January)', 2 => 'កុម្ភៈ (February)', 3 => 'មីនា (March)', 4 => 'មេសា (April)',
        5 => 'ឧសភា (May)', 6 => 'មិថុនា (June)', 7 => 'កក្កដា (July)', 8 => 'សីហា (August)',
        9 => 'កញ្ញា (September)', 10 => 'តុលា (October)', 11 => 'វិច្ឆិកា (November)', 12 => 'ធ្នូ (December)'
    ];
@endphp

<form method="POST" action="{{ route('payrolls.store') }}" id="payrollForm">
@csrf
<div class="row g-2.5">
    <div class="col-lg-7">
        {{-- Unified Form Card --}}
        <div class="form-card mb-2.5">
            <div class="section-title d-flex align-items-center gap-2">
                <span class="badge rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;background:#fef3c7;color:#92400e;font-size:0.75rem;">
                    <i class="bi bi-person-badge"></i>
                </span>
                <span>ព័ត៌មានបុគ្គលិក និងវដ្តកាលបរិច្ឆេទ</span>
            </div>

            {{-- ផ្នែក & តួនាទី --}}
            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <label class="form-label" for="filter_department_id">
                        <i class="bi bi-diagram-2 text-warning me-1"></i>ផ្នែក (Department)
                    </label>
                    <select id="filter_department_id" class="form-select">
                        <option value="">— គ្រប់ផ្នែកទាំងអស់ —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="filter_position_id">
                        <i class="bi bi-briefcase text-warning me-1"></i>តួនាទី (Role / Position)
                    </label>
                    <select id="filter_position_id" class="form-select">
                        <option value="">— គ្រប់តួនាទីទាំងអស់ —</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label" for="employee_id">បុគ្គលិក <span class="text-danger">*</span></label>
                <select name="employee_id" id="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                    <option value="">— ជ្រើសរើសបុគ្គលិក —</option>
                    @foreach($employees as $emp)
                        @php
                            $deptName = $emp->employee_department?->name ?? 'គ្មានផ្នែក';
                            $posName = $emp->employee_position?->name ?? 'គ្មានតួនាទី';
                        @endphp
                        <option value="{{ $emp->id }}"
                            data-department-id="{{ $emp->employee_department_id ?? '' }}"
                            data-department="{{ $deptName }}"
                            data-position-id="{{ $emp->employee_position_id ?? '' }}"
                            data-position="{{ $posName }}"
                            data-contract="{{ $emp->activeContract ? 'yes' : 'no' }}"
                            data-salary="{{ $emp->activeContract?->effectiveSalary() ?? 0 }}"
                            data-structure="{{ $emp->activeContract?->salaryStructure?->name ?? 'គ្មានរចនាសម្ព័ន្ធ' }}"
                            @selected(old('employee_id') == $emp->id)>
                            {{ $emp->user?->name ?? 'បុគ្គលិក' }} ({{ $emp->employee_code }}) — [{{ $posName }} • {{ $deptName }}]
                        </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div id="contractInfo" class="info-box mb-2 d-none">
                <div class="d-flex gap-3 flex-wrap small">
                    <div><span class="text-muted">ផ្នែក៖</span> <span id="ci-department" class="fw-semibold text-dark"></span></div>
                    <div><span class="text-muted">តួនាទី៖</span> <span id="ci-position" class="badge bg-white border text-dark"></span></div>
                    <div><span class="text-muted">កិច្ចសន្យា៖</span> <span id="ci-status" class="fw-semibold text-success">មានសុពលភាព</span></div>
                    <div><span class="text-muted">ប្រាក់បៀវត្សគោល៖</span> $<span id="ci-salary" class="fw-semibold"></span></div>
                    <div><span class="text-muted">រចនាសម្ព័ន្ធ៖</span> <span id="ci-structure" class="fw-semibold"></span></div>
                </div>
            </div>
            <div id="noContractWarning" class="alert alert-warning d-none mb-2 py-2 px-3 small">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                បុគ្គលិកនេះមិនទាន់មានកិច្ចសន្យាការងារសកម្មនៅឡើយទេ។ សូមបង្កើតកិច្ចសន្យាការងារជាមុនសិន មុននឹងបង្កើតបញ្ជីបើកប្រាក់បៀវត្ស។
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label" for="month">ខែ <span class="text-danger">*</span></label>
                    <select name="month" id="month" class="form-select @error('month') is-invalid @enderror" required>
                        <option value="">— ជ្រើសរើសខែ —</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" @selected(old('month', now()->month) == $m)>{{ $khmerMonths[$m] ?? $m }}</option>
                        @endforeach
                    </select>
                    @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="year">ឆ្នាំ <span class="text-danger">*</span></label>
                    <select name="year" id="year" class="form-select @error('year') is-invalid @enderror" required>
                        @foreach(range(now()->year, now()->year - 3) as $y)
                            <option value="{{ $y }}" @selected(old('year', now()->year) == $y)>ឆ្នាំ {{ $y }}</option>
                        @endforeach
                    </select>
                    @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Ad-hoc deductions section within same card --}}
            <div class="section-title border-top pt-2.5 mt-2 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;background:#fee2e2;color:#991b1b;font-size:0.75rem;">
                        <i class="bi bi-dash-circle"></i>
                    </span>
                    <span>ការកាត់ប្រាក់បន្ថែម <small class="text-muted fw-normal" style="font-size:0.78rem;">(ស្រេចចិត្ត)</small></span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 px-2.5 py-1" style="font-size:0.78rem; border-radius:6px;" onclick="addDeduction()">
                    <i class="bi bi-plus-lg"></i><span>បន្ថែមការកាត់ប្រាក់</span>
                </button>
            </div>

            <div id="deductionsList"></div>

            {{-- Form Actions inside card footer --}}
            <div class="border-top pt-2.5 mt-3 d-flex align-items-center justify-content-end gap-2">
                <a href="{{ route('payrolls.index') }}" class="btn btn-sm btn-light border px-3" style="border-radius:6px; font-size:0.82rem;">
                    <i class="bi bi-x me-1"></i>បោះបង់
                </a>
                <button type="submit" class="btn btn-sm btn-primary-action px-3.5 py-1.5 shadow-sm d-flex align-items-center gap-1.5" id="submitBtn" style="border-radius:6px; font-size:0.84rem;">
                    <i class="bi bi-cash-coin"></i>
                    <span>បង្កើតបញ្ជីបើកប្រាក់បៀវត្ស</span>
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="form-card mb-2.5">
            <div class="section-title d-flex align-items-center gap-2">
                <span class="badge rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;background:#e0f2fe;color:#0369a1;font-size:0.75rem;">
                    <i class="bi bi-info-circle"></i>
                </span>
                <span>របៀបដំណើរការ</span>
            </div>
            <ol class="ps-3 mb-2" style="font-size:.8rem; line-height:1.65;">
                <li>ជ្រើសរើសបុគ្គលិក — ប្រព័ន្ធនឹងប្រើប្រាស់<strong>កិច្ចសន្យាសកម្ម</strong>របស់ពួកគាត់ដោយស្វ័យប្រវត្តិ។</li>
                <li><strong>ប្រាក់បៀវត្សតាមកិច្ចសន្យា</strong> នឹងក្លាយជាប្រាក់បៀវត្សគោល។ ប្រសិនបើមិនទាន់កំណត់ ប្រព័ន្ធនឹងយកប្រាក់បៀវត្សគោលពីរចនាសម្ព័ន្ធបៀវត្ស។</li>
                <li>សមាសភាគប្រាក់បៀវត្សសកម្មទាំងអស់ពីរចនាសម្ព័ន្ធ នឹងត្រូវបានចម្លងចូលក្នុងបញ្ជីបើកប្រាក់បៀវត្ស។</li>
                <li>អ្នកអាចបន្ថែមការកាត់ប្រាក់ផ្ទាល់ខ្លួនបាន (ឧ. ច្បាប់គ្មានប្រាក់បៀវត្ស ការផាកពិន័យ)។</li>
                <li>បញ្ជីប្រាក់បៀវត្សនឹងចាប់ផ្តើមពីស្ថានភាព <strong>ពង្រាង (Draft)</strong>។ បន្ទាប់មក ដាក់ស្នើ → អនុម័ត → បើកប្រាក់ ដើម្បីបញ្ចប់។</li>
            </ol>
            <div class="alert alert-warning mt-2 mb-0 py-2 px-2.5" style="font-size:.78rem;">
                <i class="bi bi-shield-lock me-1"></i>
                <strong>មិនអាចកែប្រែបានទេនៅពេលបើកប្រាក់រួច។</strong> ការកែប្រែរចនាសម្ព័ន្ធប្រាក់បៀវត្សនៅពេលក្រោយ នឹងមិនប៉ះពាល់ដល់បញ្ជីដែលបានបើករួចនោះទេ។
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
let deductionIdx = 0;
const deductionTypes = {
    unpaid_leave: 'ឈប់គ្មានប្រាក់បៀវត្ស (Unpaid Leave)',
    advance_repayment: 'សងប្រាក់បុរេប្រទាន (Advance Repayment)',
    late_penalty: 'ពិន័យមកយឺត (Late Penalty)',
    fine: 'ប្រាក់ពិន័យ (Fine)',
    tax_adjustment: 'កែតម្រូវពន្ធ (Tax Adjustment)',
    other: 'ផ្សេងៗ (Other)'
};

function addDeduction() {
    const idx = deductionIdx++;
    const typeOptions = Object.entries(deductionTypes).map(([v, l]) => `<option value="${v}">${l}</option>`).join('');
    const html = `
        <div class="deduction-row" id="ded-${idx}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold text-danger" style="font-size:.82rem;">ការកាត់ប្រាក់ #${idx + 1}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="document.getElementById('ded-${idx}').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="deductions[${idx}][deduction_type]" class="form-select" style="font-size:.82rem;">${typeOptions}</select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="deductions[${idx}][description]" class="form-control" placeholder="ពិពណ៌នាការកាត់ប្រាក់..." style="font-size:.82rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="deductions[${idx}][amount]" class="form-control" placeholder="ចំនួនទឹកប្រាក់ ($)" step="0.01" min="0.01" style="font-size:.82rem;" required>
                </div>
            </div>
        </div>`;
    document.getElementById('deductionsList').insertAdjacentHTML('beforeend', html);
}

// Employee info preview & department/position filtering
const empSelect = document.getElementById('employee_id');
const deptFilter = document.getElementById('filter_department_id');
const posFilter = document.getElementById('filter_position_id');
const contractInfo = document.getElementById('contractInfo');
const warning = document.getElementById('noContractWarning');

function filterEmployees(syncFromFilters = true) {
    const selectedDept = deptFilter ? deptFilter.value : '';
    const selectedPos = posFilter ? posFilter.value : '';
    let hasSelectedVisible = false;

    Array.from(empSelect.options).forEach((opt, index) => {
        if (index === 0) return; // Keep placeholder option

        const empDept = opt.dataset.departmentId || '';
        const empPos = opt.dataset.positionId || '';

        const matchDept = !selectedDept || empDept === selectedDept;
        const matchPos = !selectedPos || empPos === selectedPos;

        if (matchDept && matchPos) {
            opt.hidden = false;
            opt.disabled = false;
            opt.style.display = '';
            if (opt.selected) {
                hasSelectedVisible = true;
            }
        } else {
            opt.hidden = true;
            opt.disabled = true;
            opt.style.display = 'none';
        }
    });

    if (syncFromFilters && empSelect.value && !hasSelectedVisible) {
        empSelect.value = '';
        updateEmployeePreview();
    }
}

function updateEmployeePreview() {
    if (!empSelect.value) {
        contractInfo.classList.add('d-none');
        warning.classList.add('d-none');
        return;
    }

    const opt = empSelect.options[empSelect.selectedIndex];
    const hasContract = opt.dataset.contract === 'yes';

    const deptEl = document.getElementById('ci-department');
    const posEl = document.getElementById('ci-position');
    if (deptEl) deptEl.textContent = opt.dataset.department || '—';
    if (posEl) posEl.textContent = opt.dataset.position || '—';

    if (hasContract) {
        document.getElementById('ci-status').textContent = 'មានសុពលភាព';
        document.getElementById('ci-salary').textContent = parseFloat(opt.dataset.salary || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('ci-structure').textContent = opt.dataset.structure || '—';
        contractInfo.classList.remove('d-none');
        warning.classList.add('d-none');
    } else {
        contractInfo.classList.add('d-none');
        warning.classList.remove('d-none');
    }
}

if (deptFilter) {
    deptFilter.addEventListener('change', function() {
        filterEmployees(true);
    });
}

if (posFilter) {
    posFilter.addEventListener('change', function() {
        filterEmployees(true);
    });
}

empSelect.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (this.value !== '') {
        // Automatically sync department and position filters to the chosen employee
        if (deptFilter && opt.dataset.departmentId) {
            deptFilter.value = opt.dataset.departmentId;
        }
        if (posFilter && opt.dataset.positionId) {
            posFilter.value = opt.dataset.positionId;
        }
        filterEmployees(false);
    }
    updateEmployeePreview();
});

// Initial trigger if old value exists on load
if (empSelect.value) {
    const opt = empSelect.options[empSelect.selectedIndex];
    if (deptFilter && opt.dataset.departmentId) deptFilter.value = opt.dataset.departmentId;
    if (posFilter && opt.dataset.positionId) posFilter.value = opt.dataset.positionId;
    filterEmployees(false);
    updateEmployeePreview();
}
</script>
@endpush
