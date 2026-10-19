@extends('layouts.app')

@section('title', 'កែប្រែកិច្ចសន្យាការងារ ' . $employee_contract->contract_number . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ប្រាក់បៀវត្ស និងបុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('employee-contracts.index') }}" class="text-decoration-none text-muted">កិច្ចសន្យាការងារ</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កែប្រែ ({{ $employee_contract->contract_number }})</li>
    </ol>
</nav>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title d-flex align-items-center gap-2">
            <span class="d-flex align-items-center justify-content-center rounded-2" style="width:38px;height:38px;background:#fef3c7;color:#c98b1e;font-size:1.2rem;">
                <i class="bi bi-pencil-square"></i>
            </span>
            <span>កែប្រែកិច្ចសន្យាការងារ</span>
        </h1>
        <p class="page-subtitle mb-0">កែប្រែទិន្នន័យកិច្ចសន្យាការងារលេខ <strong>{{ $employee_contract->contract_number }}</strong> — និយោជិត៖ <strong>{{ $employee_contract->employee?->user?->name ?? 'N/A' }}</strong></p>
    </div>
    <div>
        <a href="{{ route('employee-contracts.show', $employee_contract) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3 py-2 shadow-xs">
            <i class="bi bi-arrow-left"></i>
            <span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>
</div>

{{-- Validation Summary --}}
@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-start gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-octagon-fill fs-5 mt-0.5"></i>
        <div>
            <strong>សូមពិនិត្យកំហុសខាងក្រោមមុនពេលរក្សាទុក៖</strong>
            <ul class="mb-0 mt-1 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ route('employee-contracts.update', $employee_contract) }}" enctype="multipart/form-data" id="contractForm" novalidate>
@csrf
@method('PUT')

<div class="row g-2.5">

    {{-- LEFT COLUMN: FORM SECTIONS --}}
    <div class="col-12 col-xl-8 col-lg-7">

        {{-- UNIFIED CONTRACT EDIT FORM CARD --}}
        <div class="form-card border-0 shadow-sm mb-3">

            {{-- 1. CONTRACT PARTIES --}}
            <div class="form-card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-people-fill"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">១. ព័ត៌មានភាគីនៃកិច្ចសន្យា</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">និយោជក (ភាគី «ក») និង និយោជិត (ភាគី «ខ»)</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ភាគីកិច្ចសន្យា</span>
            </div>

            <div class="form-card-body p-3">
                <div class="p-2 mb-2 rounded-2 d-flex align-items-center gap-2.5" style="background:#fffbeb; border: 1px solid #fef3c7;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width:32px;height:32px;background:#fde68a;color:#92400e;">
                        <i class="bi bi-bank2 fs-6"></i>
                    </div>
                    <div class="small">
                        <div class="fw-bold text-dark" style="font-size:0.83rem;">ភាគី «ក» (និយោជក)៖ <strong>សាលារៀន ព្រះធីរបញ្ញា</strong></div>
                        <div class="text-muted" style="font-size:0.75rem;">តំណាងដោយ៖ <strong>អ្នកស្រី សូ វាសនា (នាយិកាសាលា)</strong></div>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            និយោជិត (ភាគី «ខ») <span class="text-danger">*</span>
                        </label>
                        <select name="employee_id" id="employee_select" class="form-select form-control-custom" required>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" @selected(old('employee_id', $employee_contract->employee_id) == $emp->id)>
                                    {{ $emp->user?->name ?? 'N/A' }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 2. CONTRACT TERMS --}}
            <div class="form-card-header bg-light border-top border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-calendar2-week"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">២. លក្ខខណ្ឌ និងថិរវេលាកិច្ចសន្យា</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">រយៈពេលកិច្ចសន្យា (ប្រការ១), ការសាកល្បង (ប្រការ២) និង ម៉ោងការងារ (ប្រការ៧)</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ថិរវេលា</span>
            </div>

            <div class="form-card-body p-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            លេខកិច្ចសន្យា <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-2.5" title="ចាក់សោកុំឱ្យកែប្រែ">
                                <i class="bi bi-lock-fill text-warning"></i>
                            </span>
                            <input type="text" name="contract_number" class="form-control form-control-custom bg-light border-start-0 font-monospace fw-bold @error('contract_number') is-invalid @enderror" 
                                   value="{{ old('contract_number', $employee_contract->contract_number) }}" readonly tabindex="-1" title="លេខកិច្ចសន្យាត្រូវបានចាក់សោ">
                        </div>
                        <div class="form-text text-muted small mt-1" style="font-size: 0.72rem;">
                            <i class="bi bi-lock-fill text-warning me-1"></i>ចាក់សោកុំឱ្យកែប្រែ
                        </div>
                        @error('contract_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ប្រភេទកិច្ចសន្យា <span class="text-danger">*</span>
                        </label>
                        <select name="contract_type" class="form-select form-control-custom @error('contract_type') is-invalid @enderror" required>
                            <option value="fixed_term"  @selected(old('contract_type', $employee_contract->contract_type) === 'fixed_term')>កិច្ចសន្យាមានកំណត់ថិរវេលា</option>
                            <option value="probation"   @selected(old('contract_type', $employee_contract->contract_type) === 'probation')>ការសាកល្បងការងារ</option>
                            <option value="permanent"   @selected(old('contract_type', $employee_contract->contract_type) === 'permanent')>កិច្ចសន្យាមិនកំណត់ថិរវេលា</option>
                            <option value="part_time"   @selected(old('contract_type', $employee_contract->contract_type) === 'part_time')>ការងារក្រៅម៉ោង</option>
                            <option value="intern"      @selected(old('contract_type', $employee_contract->contract_type) === 'intern')>កម្មសិក្សាការងារ</option>
                        </select>
                        @error('contract_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ថ្ងៃចាប់ផ្តើម <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="start_date" class="form-control form-control-custom @error('start_date') is-invalid @enderror" 
                               value="{{ old('start_date', $employee_contract->start_date->toDateString()) }}" required>
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ថ្ងៃបញ្ចប់ <span class="text-muted small">(ជម្រើស)</span>
                        </label>
                        <input type="date" name="end_date" class="form-control form-control-custom @error('end_date') is-invalid @enderror" 
                               value="{{ old('end_date', $employee_contract->end_date?->toDateString()) }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ម៉ោងធ្វើការក្នុងមួយសប្តាហ៍ <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="work_hours_per_week" class="form-control form-control-custom @error('work_hours_per_week') is-invalid @enderror" 
                                   value="{{ old('work_hours_per_week', $employee_contract->work_hours_per_week) }}" step="0.5" min="1" max="168" required>
                            <span class="input-group-text bg-light text-muted">ម៉ោង/សប្តាហ៍</span>
                        </div>
                        @error('work_hours_per_week')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ស្ថានភាពកិច្ចសន្យា <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select form-control-custom @error('status') is-invalid @enderror" required>
                            @foreach(['draft'=>'ពង្រាង','active'=>'ដំណើរការ (សកម្ម)','expired'=>'ផុតកំណត់','terminated'=>'បញ្ចប់កិច្ចសន្យា','renewed'=>'បានបន្តរួច'] as $stKey => $stLabel)
                                <option value="{{ $stKey }}" @selected(old('status', $employee_contract->status) === $stKey)>{{ $stLabel }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- 3. REMUNERATION & SALARY --}}
            <div class="form-card-header bg-light border-top border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-cash-stack"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">៣. ប្រាក់បៀវត្ស និងរចនាសម្ព័ន្ធប្រាក់ខែ</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">ប្រាក់ខែគោល (ប្រការ៤) និង ប្រាក់ខែទី១៣/ប្រាក់បំណាច់ឆ្នាំ (ប្រការ៦)</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ប្រាក់បៀវត្ស</span>
            </div>

            <div class="form-card-body p-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ប្រាក់បៀវត្សឯកភាព / ប្រាក់ខែគោល <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted fw-bold">$</span>
                            <input type="number" name="salary" class="form-control form-control-custom @error('salary') is-invalid @enderror" 
                                   value="{{ old('salary', $employee_contract->salary) }}" step="0.01" min="0" required>
                            <span class="input-group-text bg-light text-muted">ដុល្លារ / ខែ</span>
                        </div>
                        @error('salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            រចនាសម្ព័ន្ធប្រាក់បៀវត្ស
                        </label>
                        <select name="salary_structure_id" class="form-select form-control-custom @error('salary_structure_id') is-invalid @enderror">
                            <option value="">— គ្មាន (ប្រើប្រាក់បៀវត្សឯកភាពផ្ទាល់) —</option>
                            @foreach($structures as $s)
                                <option value="{{ $s->id }}" @selected(old('salary_structure_id', $employee_contract->salary_structure_id) == $s->id)>
                                    {{ $s->name }} (${{ number_format($s->base_salary, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('salary_structure_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- 4. DOCUMENT & NOTES --}}
            <div class="form-card-header bg-light border-top border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size:0.75rem; background:#e8a838 !important;">
                        <i class="bi bi-paperclip"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">៤. ឯកសារកិច្ចសន្យា និងកំណត់សម្គាល់</h5>
                        <span class="text-muted" style="font-size: 0.75rem;">ផ្លាស់ប្តូរឯកសារស្កេន និងកំណត់សម្គាល់បន្ថែម</span>
                    </div>
                </div>
                <span class="badge px-2 py-1" style="background:#fef3c7; color:#78350f; border: 1px solid #fde68a; font-weight:700; font-size:0.72rem;">ឯកសារ</span>
            </div>

            <div class="form-card-body p-3">
                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            ផ្លាស់ប្តូរឯកសារស្កេន
                        </label>
                        <input type="file" name="document" class="form-control form-control-custom @error('document') is-invalid @enderror" 
                               accept=".pdf,.doc,.docx,image/*">
                        @if($employee_contract->document_path)
                            <div class="mt-1.5">
                                <a href="{{ asset('storage/' . $employee_contract->document_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-1" style="font-size:0.8rem;">
                                    <i class="bi bi-file-earmark-pdf me-1 text-danger"></i>មើលឯកសារបច្ចុប្បន្ន
                                </a>
                            </div>
                        @endif
                        @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom fw-semibold text-dark mb-1">
                            កំណត់សម្គាល់ ឬលក្ខខណ្ឌពិសេស
                        </label>
                        <textarea name="notes" rows="2" class="form-control form-control-custom @error('notes') is-invalid @enderror">{{ old('notes', $employee_contract->notes) }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Bottom Actions Bar Inside Card Footer --}}
            <div class="form-card-footer border-top bg-light p-2.5 d-flex align-items-center justify-content-between">
                <div class="text-muted small" style="font-size:0.78rem;">
                    <i class="bi bi-info-circle me-1"></i>ពិនិត្យរួចចុច <strong class="text-dark">រក្សាទុកការកែប្រែ</strong>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('employee-contracts.show', $employee_contract) }}" class="btn btn-sm btn-light border px-3 d-flex align-items-center text-secondary" style="border-radius:6px; font-size:0.82rem;">
                        <i class="bi bi-x me-1 fs-5"></i> បោះបង់
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3.5 py-1.5 shadow-sm" style="border-radius:6px; font-size:0.84rem;">
                        <i class="bi bi-check-lg me-1"></i> រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </div>

        </div>

    </div>

    {{-- RIGHT COLUMN: CLAUSES REFERENCE --}}
    <div class="col-12 col-xl-4 col-lg-5">
        <div class="form-card border-0 shadow-sm p-3 bg-white mb-3">
            <h6 class="fw-bold text-dark mb-2.5 d-flex align-items-center gap-2" style="font-size:0.92rem;">
                <i class="bi bi-info-circle-fill" style="color:#e8a838;"></i>
                <span>បទប្បញ្ញត្តិនៃកិច្ចសន្យាការងារ</span>
            </h6>
            <div class="p-2.5 rounded-2 mb-2.5" style="background:#fffbeb; border:1px solid #fef3c7;">
                <div class="fw-bold text-dark mb-0.5" style="font-size:0.83rem;">សាលារៀន ព្រះធីរបញ្ញា</div>
                <div class="text-muted small" style="font-size:0.75rem;">កិច្ចសន្យានេះត្រូវបានរៀបចំឡើងស្របតាមច្បាប់ស្តីពីការងារ នៃព្រះរាជាណាចក្រកម្ពុជា។</div>
            </div>
            <ul class="ps-3 text-muted small mb-0" style="line-height:1.7; font-size:0.78rem;">
                <li><strong>ប្រការ១៖</strong> រយៈពេលកិច្ចសន្យាមានកំណត់ (FDC) ឬមិនកំណត់ (UDC)</li>
                <li><strong>ប្រការ២៖</strong> ការសាកល្បងការងាររយៈពេល ៣ខែ</li>
                <li><strong>ប្រការ៤៖</strong> ប្រាក់បៀវត្ស និងការកាត់ទុកពន្ធ</li>
                <li><strong>ប្រការ៦៖</strong> ប្រាក់ខែទី១៣ និងប្រាក់បំណាច់ឆ្នាំ</li>
                <li><strong>ប្រការ៧៖</strong> ម៉ោងធ្វើការ ៨ម៉ោង/ថ្ងៃ និង ៥ថ្ងៃ/សប្តាហ៍ (៤០ម៉ោង)</li>
                <li><strong>ប្រការ៨-៩៖</strong> ការឈប់សម្រាកបុណ្យជាតិ និងលំហែមាតុភាព</li>
            </ul>
        </div>
    </div>

</div>

</form>

@endsection

@push('styles')
<style>
.form-card {
    background: #ffffff;
    border: 1px solid var(--border, #e2e8f0);
    border-top: 3px solid var(--gold, #e8a838);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
}
.form-card-header {
    border-bottom: 1px solid var(--border-soft, #f1f5f9);
}
.form-control-custom, .form-select-custom {
    font-size: 0.85rem;
    line-height: 1.5;
    padding: 0.38rem 0.75rem;
    height: 36px;
    border-color: #cbd5e1;
    border-radius: 6px;
    transition: all 0.15s ease-in-out;
}
textarea.form-control-custom {
    height: auto;
    min-height: 65px;
}
.form-control-custom:focus, .form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 3px rgba(232, 168, 56, .18);
    outline: none;
}
.form-label-custom {
    font-size: 0.82rem;
    margin-bottom: 0.2rem;
}
.input-group-text {
    font-size: 0.82rem;
}
.btn-primary-action {
    background: #e8a838;
    border: 1px solid #e8a838;
    color: #ffffff;
    font-weight: 600;
}
.btn-primary-action:hover {
    background: #c98b1e;
    border-color: #c98b1e;
    color: #ffffff;
}
</style>
@endpush
