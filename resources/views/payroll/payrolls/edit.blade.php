@extends('layouts.app')

@section('title', 'Edit Payroll #{{ $payroll->payroll_number }} — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}" class="text-decoration-none text-muted">Payrolls</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payrolls.show', $payroll) }}" class="text-decoration-none text-muted">{{ $payroll->payroll_number }}</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.form-card { background:#fff; border:1px solid var(--border-soft,#e5e7eb); border-radius:var(--radius-lg,12px); padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,.04); margin-bottom:1.5rem; }
.section-title { font-size:.88rem; font-weight:700; margin-bottom:1rem; padding-bottom:.5rem; border-bottom:2px solid #eff6ff; }
.form-label { font-size:.85rem; font-weight:600; }
.form-control, .form-select { border:1px solid var(--border,#d1d5db); border-radius:var(--radius-md,8px); font-size:.875rem; padding:.5rem .85rem; }
.form-control:focus, .form-select:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); outline:none; }
.deduction-row { background:#fff8f8; border:1px solid #fee2e2; border-radius:var(--radius-md,8px); padding:.85rem; margin-bottom:.5rem; }
.summary-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:var(--radius-md,8px); padding:1rem; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
    <div>
        <h4 class="mb-0 fw-bold">Edit Payroll <span class="text-muted">{{ $payroll->payroll_number }}</span></h4>
        <div class="text-muted" style="font-size:.85rem;">{{ $payroll->period }} &mdash; {{ $payroll->employee?->user?->name }}</div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="summary-box mb-4">
    <div class="row g-3 text-center">
        <div class="col"><div class="fw-bold">{{ number_format($payroll->basic_salary, 2) }}</div><div style="font-size:.78rem;color:#6b7280;">Basic Salary</div></div>
        <div class="col"><div class="fw-bold text-success">{{ number_format($payroll->total_earnings, 2) }}</div><div style="font-size:.78rem;color:#6b7280;">Total Earnings</div></div>
        <div class="col"><div class="fw-bold text-danger">{{ number_format($payroll->total_deductions, 2) }}</div><div style="font-size:.78rem;color:#6b7280;">Total Deductions</div></div>
        <div class="col"><div class="fw-bold text-primary" style="font-size:1.1rem;">{{ number_format($payroll->net_salary, 2) }}</div><div style="font-size:.78rem;color:#6b7280;">Net Salary</div></div>
    </div>
    <div style="font-size:.78rem;color:#6b7280;margin-top:.5rem;text-align:center;">Salary components (earnings/deductions from structure) are read-only snapshots. Only ad-hoc deductions and notes can be modified.</div>
</div>

<form method="POST" action="{{ route('payrolls.update', $payroll) }}">
@csrf
@method('PUT')

<div class="form-card">
    <div class="section-title"><i class="bi bi-pencil me-2 text-primary"></i>Notes & Pay Date</div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Pay Date</label>
            <input type="date" name="pay_date" class="form-control" value="{{ old('pay_date', $payroll->pay_date?->toDateString()) }}">
        </div>
        <div class="col-md-8">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $payroll->notes) }}</textarea>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="section-title"><i class="bi bi-dash-circle me-2 text-danger"></i>Ad-hoc Deductions</div>
    <div id="deductionsList">
        @foreach($payroll->payrollDeductions as $i => $ded)
        <div class="deduction-row" id="ded-{{ $i }}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold text-danger" style="font-size:.82rem;">Deduction #{{ $i + 1 }}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="document.getElementById('ded-{{ $i }}').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="deductions[{{ $i }}][deduction_type]" class="form-select" style="font-size:.82rem;">
                        @foreach(\App\Models\Payroll\PayrollDeduction::$deductionTypes as $val => $label)
                            <option value="{{ $val }}" @selected($ded->deduction_type === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="deductions[{{ $i }}][description]" class="form-control" value="{{ $ded->description }}" placeholder="Description" style="font-size:.82rem;" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="deductions[{{ $i }}][amount]" class="form-control" value="{{ $ded->amount }}" placeholder="Amount" step="0.01" min="0.01" style="font-size:.82rem;" required>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-danger mt-1" onclick="addDeduction()">
        <i class="bi bi-plus-lg me-1"></i>Add Deduction
    </button>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary fw-semibold">
        <i class="bi bi-save me-1"></i>Save Changes
    </button>
    <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-outline-secondary">Cancel</a>
</div>
</form>
@endsection

@push('scripts')
<script>
let deductionIdx = {{ $payroll->payrollDeductions->count() }};
const deductionTypes = @json(\App\Models\Payroll\PayrollDeduction::$deductionTypes);

function addDeduction() {
    const idx = deductionIdx++;
    const typeOptions = Object.entries(deductionTypes).map(([v, l]) => `<option value="${v}">${l}</option>`).join('');
    const html = `
        <div class="deduction-row" id="ded-${idx}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold text-danger" style="font-size:.82rem;">Deduction</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="document.getElementById('ded-${idx}').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-4"><select name="deductions[${idx}][deduction_type]" class="form-select" style="font-size:.82rem;">${typeOptions}</select></div>
                <div class="col-md-5"><input type="text" name="deductions[${idx}][description]" class="form-control" placeholder="Description" style="font-size:.82rem;" required></div>
                <div class="col-md-3"><input type="number" name="deductions[${idx}][amount]" class="form-control" placeholder="Amount" step="0.01" min="0.01" style="font-size:.82rem;" required></div>
            </div>
        </div>`;
    document.getElementById('deductionsList').insertAdjacentHTML('beforeend', html);
}
</script>
@endpush
