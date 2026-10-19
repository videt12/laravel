@extends('layouts.app')

@section('title', 'បន្ថែមផ្នែកថ្មី — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">បុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធបុគ្គលិក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បន្ថែមផ្នែកថ្មី</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.form-card {
    background: #ffffff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.form-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-soft, #e2e8f0);
    background: #ffffff;
}
.form-card-body {
    padding: 1.25rem;
}
.form-label-custom {
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.3rem;
    display: block;
}
.form-control-custom {
    font-size: 0.85rem;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0.45rem 0.75rem;
    min-height: 38px;
    height: 38px;
    transition: all 0.2s;
    background-color: #ffffff;
}
textarea.form-control-custom {
    min-height: 80px;
    height: auto;
}
.form-control-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.2);
    outline: none;
}
.alert-card-danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
    border-radius: 6px;
    padding: 0.75rem 1rem;
}
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="page-title fw-bold text-dark mb-1" style="font-size: 1.35rem;">
            បន្ថែមផ្នែកថ្មី (Add New Department)
        </h1>
        <p class="page-subtitle text-muted mb-0" style="font-size: 0.85rem;">
            សូមបំពេញព័ត៌មានលម្អិតនៃផ្នែកការងារខាងក្រោម
        </p>
    </div>
    <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1.5 px-3 py-1.5" style="border-radius: 4px;">
        <i class="bi bi-arrow-left"></i>
        <span>ត្រឡប់ក្រោយ (Back)</span>
    </a>
</div>

{{-- Validation Summary --}}
@if($errors->any())
    <div class="alert-card alert-card-danger mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i>
            <strong>សូមពិនិត្យ និងកែតម្រូវកំហុសឆ្គងខាងក្រោម មុនពេលរក្សាទុក៖</strong>
        </div>
        <ul class="mb-0 mt-2 ps-4" style="font-size: 0.85rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('departments.store') }}" method="POST">
    @csrf

    <div class="row g-4 justify-content-center">
        <div class="col-xl-8 col-lg-9">
            <div class="form-card mb-4">
                <div class="form-card-header bg-white pb-2">
                    <h5 class="mb-0 d-flex align-items-center">
                        <span class="badge rounded-circle p-2 me-2 d-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; background: #e8a838; font-size: 0.85rem;"><i class="bi bi-building"></i></span>
                        <span class="fw-bold text-dark">ព័ត៌មានផ្នែក</span>
                        <span class="text-muted fw-normal ms-2" style="font-size: 0.82rem;">(Department Information)</span>
                    </h5>
                </div>
                <div class="form-card-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-8 col-12">
                            <label class="form-label-custom">ឈ្មោះផ្នែក <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="ឧ. រដ្ឋបាល (Administration), គណនេយ្យ (Accounting)..." required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 col-12">
                            <label class="form-label-custom">លេខកូដ (Code ID)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted px-2.5" title="ចាក់សោស្វ័យប្រវត្តិ">
                                    <i class="bi bi-lock-fill text-warning"></i>
                                </span>
                                <input type="text" name="code" class="form-control form-control-custom bg-light border-start-0 font-monospace fw-bold @error('code') is-invalid @enderror" value="{{ old('code', $nextDepartmentCode ?? 'DEP-001') }}" readonly tabindex="-1" title="លេខកូដត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ និងចាក់សោ">
                            </div>
                            <div class="form-text text-muted small mt-1" style="font-size: 0.72rem;">
                                <i class="bi bi-lock-fill text-warning me-1"></i>បង្កើតស្វ័យប្រវត្តិ — ចាក់សោ (Locked)
                            </div>
                            @error('code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">ស្ថានភាព <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-control-custom @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>សកម្ម</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>អសកម្ម</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">ការពិពណ៌នា (Description)</label>
                            <textarea name="description" rows="3" class="form-control form-control-custom @error('description') is-invalid @enderror" placeholder="ពិពណ៌នាសង្ខេបអំពីដេប៉ាតឺម៉ង់ និងភារកិច្ចទទួលខុសត្រូវ...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-3 bg-light border-top d-flex align-items-center justify-content-between">
                    <a href="{{ route('departments.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5" style="border-radius: 4px;">
                        បោះបង់ (Cancel)
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4 py-1.5 d-flex align-items-center gap-1.5 shadow-sm">
                        <i class="bi bi-check-lg"></i>
                        <span>រក្សាទុកដេប៉ាតឺម៉ង់ (Save Department)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
