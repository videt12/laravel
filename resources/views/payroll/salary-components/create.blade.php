@extends('layouts.app')
@section('title', 'បង្កើតសមាសភាគប្រាក់បៀវត្ស')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('salary-components.index') }}" class="text-decoration-none text-muted">សមាសភាគប្រាក់បៀវត្ស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">បង្កើតថ្មី</li>
    </ol>
</nav>
@endsection
@push('styles')
<style>
.form-card { 
    background:#fff; 
    border:1px solid var(--border-soft,#e5e7eb); 
    border-top:3px solid var(--gold,#e8a838);
    border-radius:var(--radius-md,8px); 
    padding:1.1rem 1.25rem; 
    box-shadow:0 1px 3px rgba(0,0,0,.04); 
}
.section-title { font-size:.88rem; font-weight:700; color:var(--text-dark,#0f172a); margin-bottom:.85rem; padding-bottom:.4rem; border-bottom:2px solid #fef3c7; }
.form-label { font-size:.82rem; font-weight:600; color:var(--text-dark,#0f172a); margin-bottom:.25rem; }
.form-control, .form-select { border:1px solid var(--border,#cbd5e1); border-radius:6px; font-size:.85rem; padding:.38rem .75rem; height:36px; transition:all .15s ease-in-out; }
textarea.form-control { height:auto; min-height:60px; }
.form-control:focus, .form-select:focus { border-color:#e8a838; box-shadow:0 0 0 3px rgba(232,168,56,.18); outline:none; }
.btn-primary-action { background:#e8a838; border-color:#e8a838; color:#fff; font-weight:600; }
.btn-primary-action:hover { background:#c98b1e; border-color:#c98b1e; color:#fff; }
</style>
@endpush
@section('content')
<div class="d-flex align-items-center justify-content-between gap-3 mb-2.5">
    <div class="d-flex align-items-center gap-2">
        <span class="d-flex align-items-center justify-content-center rounded-2" style="width:34px;height:34px;background:#fef3c7;color:#92400e;font-size:1.1rem;">
            <i class="bi bi-list-check"></i>
        </span>
        <h4 class="mb-0 fw-bold" style="font-size:1.15rem;">បន្ថែមសមាសភាគប្រាក់បៀវត្ស</h4>
    </div>
    <a href="{{ $selectedStructure ? route('salary-structures.show', $selectedStructure) : route('salary-components.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="height:35px; border-radius:6px; font-size:0.82rem;">
        <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
    </a>
</div>
@if($errors->any())
    <div class="alert alert-danger mb-2.5 py-2 px-3 small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="row g-2.5">
    <div class="col-lg-8 col-xl-7">
        <form method="POST" action="{{ route('salary-components.store') }}">
            @csrf
            {{-- Hidden defaults to keep form simple --}}
            <input type="hidden" name="order" value="{{ old('order', 0) }}">
            <input type="hidden" name="is_taxable" value="{{ old('is_taxable', 0) }}">

            <div class="form-card mb-2.5">
                <div class="section-title"><i class="bi bi-list-check me-2 text-warning"></i>ព័ត៌មានលម្អិតនៃសមាសភាគ</div>
                
                {{-- Structure --}}
                <div class="mb-2.5">
                    <label class="form-label">រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</label>
                    <select name="salary_structure_id" class="form-select">
                        <option value="">— គ្មាន (គំរូមេ) —</option>
                        @foreach($structures as $s)
                            <option value="{{ $s->id }}" @selected(old('salary_structure_id', $selectedStructure?->id) == $s->id)>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Name & Compact Code --}}
                <div class="row g-2 mb-2.5">
                    <div class="col-md-9">
                        <label class="form-label">ឈ្មោះសមាសភាគ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="ឧ. ប្រាក់ឧបត្ថម្ភការស្នាក់នៅ, ថ្លៃបេសកកម្ម...">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>កូដសម្គាល់</span>
                            <span class="text-muted fw-normal" style="font-size:0.7rem;"><i class="bi bi-lock-fill text-warning me-0.5"></i>ស្វ័យប្រវត្តិ</span>
                        </label>
                        <input type="text"
                               name="code"
                               class="form-control bg-light text-muted font-monospace fw-semibold text-center @error('code') is-invalid @enderror"
                               value="{{ old('code', $defaultCode ?? 'TS00001') }}"
                               readonly
                               tabindex="-1"
                               title="កូដសម្គាល់ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Type, Calculation & Amount/Rate --}}
                <div class="row g-2 mb-2.5">
                    <div class="col-md-4">
                        <label class="form-label">ប្រភេទ <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="earning"   @selected(old('type','earning') === 'earning')>ប្រាក់បន្ថែម / ឧបត្ថម្ភ</option>
                            <option value="deduction" @selected(old('type') === 'deduction')>ការកាត់ប្រាក់</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">រូបមន្តគណនា <span class="text-danger">*</span></label>
                        <select name="calculation_type" id="calc_type" class="form-select" required onchange="toggleCalcFields()">
                            <option value="fixed"      @selected(old('calculation_type','fixed') === 'fixed')>ទឹកប្រាក់ថេរ</option>
                            <option value="percentage" @selected(old('calculation_type') === 'percentage')>ភាគរយនៃប្រាក់គោល</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="amountField">
                        <label class="form-label">ចំនួនទឹកប្រាក់ ($) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', 0) }}" step="0.01" min="0">
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 d-none" id="percentField">
                        <label class="form-label">ភាគរយ (%) <span class="text-danger">*</span></label>
                        <input type="number" name="percentage" id="percentage" class="form-control @error('percentage') is-invalid @enderror" value="{{ old('percentage', 0) }}" step="0.01" min="0" max="100">
                        @error('percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Description / Notes --}}
                <div class="mb-3">
                    <label class="form-label">ការពិពណ៌នា / កំណត់សម្គាល់</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="ពិពណ៌នាបន្ថែមអំពីសមាសភាគនេះ (បើមាន)...">{{ old('description') }}</textarea>
                </div>

                {{-- Footer: Active Switch + Submit --}}
                <div class="pt-2.5 border-top d-flex align-items-center justify-content-between">
                    <div class="form-check mb-0">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" @checked(old('is_active', 1))>
                        <label class="form-check-label small fw-semibold text-dark" for="is_active" role="button">
                            សមាសភាគសកម្ម
                        </label>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ $selectedStructure ? route('salary-structures.show', $selectedStructure) : route('salary-components.index') }}" class="btn btn-sm btn-light border px-3" style="border-radius:6px; font-size:0.82rem;">បោះបង់</a>
                        <button type="submit" class="btn btn-sm btn-primary-action px-3.5 py-1.5 shadow-sm" style="border-radius:6px; font-size:0.84rem;">
                            <i class="bi bi-save me-1"></i>រក្សាទុកសមាសភាគ
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function toggleCalcFields() {
    const calc = document.getElementById('calc_type').value;
    document.getElementById('amountField').classList.toggle('d-none', calc === 'percentage');
    document.getElementById('percentField').classList.toggle('d-none', calc !== 'percentage');
    document.getElementById('amount').required = (calc === 'fixed');
    document.getElementById('percentage').required = (calc === 'percentage');
}
toggleCalcFields();
</script>
@endpush
