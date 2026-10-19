@extends('layouts.app')
@section('title', 'កែសម្រួលរចនាសម្ព័ន្ធប្រាក់បៀវត្ស')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('salary-structures.index') }}" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</a></li>
        <li class="breadcrumb-item"><a href="{{ route('salary-structures.show', $salary_structure) }}" class="text-decoration-none text-muted">{{ $salary_structure->name }}</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">កែសម្រួល</li>
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
    padding:1rem 1.15rem; 
    box-shadow:0 1px 3px rgba(0,0,0,.04); 
}
.section-title { font-size:.88rem; font-weight:700; color:var(--text-dark,#0f172a); margin-bottom:.75rem; padding-bottom:.4rem; border-bottom:2px solid #fef3c7; }
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
            <i class="bi bi-pencil-square"></i>
        </span>
        <h4 class="mb-0 fw-bold" style="font-size:1.15rem;">កែសម្រួលរចនាសម្ព័ន្ធប្រាក់បៀវត្ស</h4>
    </div>
    <a href="{{ route('salary-structures.show', $salary_structure) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="height:35px; border-radius:6px; font-size:0.82rem;">
        <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
    </a>
</div>
@if($errors->any())
    <div class="alert alert-danger mb-2.5 py-2 px-3 small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="row g-2.5">
    <div class="col-lg-7">
        <form method="POST" action="{{ route('salary-structures.update', $salary_structure) }}">
            @csrf @method('PUT')
            <div class="form-card mb-2.5">
                <div class="section-title"><i class="bi bi-layers me-2 text-warning"></i>ព័ត៌មានលម្អិតនៃរចនាសម្ព័ន្ធ</div>
                <div class="row g-2 mb-2">
                    <div class="col-md-8">
                        <label class="form-label">ឈ្មោះរចនាសម្ព័ន្ធ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $salary_structure->name) }}" required placeholder="ឧ. គ្រូបង្រៀនកម្រិត A">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>កូដសម្គាល់ <span class="text-danger">*</span></span>
                            <span class="text-muted fw-normal" style="font-size:0.7rem;"><i class="bi bi-lock-fill text-warning me-0.5"></i>ចាក់សោ</span>
                        </label>
                        <input type="text"
                               name="code"
                               class="form-control bg-light text-muted font-monospace fw-semibold text-center @error('code') is-invalid @enderror"
                               value="{{ old('code', $salary_structure->code) }}"
                               required
                               readonly
                               tabindex="-1"
                               title="កូដសម្គាល់ត្រូវបានចាក់សោ">
                        @error('code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="employee_department_id">
                            <i class="bi bi-diagram-2 text-warning me-1"></i>ផ្នែក
                        </label>
                        <select name="employee_department_id" id="employee_department_id" class="form-select @error('employee_department_id') is-invalid @enderror">
                            <option value="">— ជ្រើសរើសផ្នែក (ទូទៅ/ទាំងអស់) —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" @selected(old('employee_department_id', $salary_structure->employee_department_id) == $dept->id)>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="employee_position_id">
                            <i class="bi bi-briefcase text-warning me-1"></i>តួនាទី
                        </label>
                        <select name="employee_position_id" id="employee_position_id" class="form-select @error('employee_position_id') is-invalid @enderror">
                            <option value="">— ជ្រើសរើសតួនាទី (ទូទៅ/ទាំងអស់) —</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" @selected(old('employee_position_id', $salary_structure->employee_position_id) == $pos->id)>{{ $pos->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_position_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ប្រាក់បៀវត្សគោល ($) <span class="text-danger">*</span></label>
                        <input type="number" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $salary_structure->base_salary) }}" step="0.01" min="0" required>
                        @error('base_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-center pt-3">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" @checked(old('is_active', $salary_structure->is_active))>
                            <label class="form-check-label small fw-semibold text-dark" for="is_active" role="button">សកម្ម</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ការពិពណ៌នា</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="ការពិពណ៌នាបន្ថែម (ស្រេចចិត្ត)...">{{ old('description', $salary_structure->description) }}</textarea>
                    </div>
                </div>
                <div class="mt-3 pt-2.5 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('salary-structures.show', $salary_structure) }}" class="btn btn-sm btn-light border px-3" style="border-radius:6px; font-size:0.82rem;">បោះបង់</a>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3.5 py-1.5 shadow-sm" style="border-radius:6px; font-size:0.84rem;">
                        <i class="bi bi-save me-1"></i>រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-5">
        <div class="form-card mb-2.5" style="background:#fffbeb;border-color:#fde68a;">
            <div class="section-title border-bottom-0 pb-0" style="color:#92400e;"><i class="bi bi-info-circle me-1"></i>កំណត់សម្គាល់អំពីរចនាសម្ព័ន្ធ</div>
            <ul class="ps-3 mb-0 mt-2 text-muted" style="font-size:.8rem;line-height:1.7;">
                <li><strong>ប្រាក់បៀវត្សគោល</strong> គឺជាតម្លៃលំនាំដើម ប្រសិនបើកិច្ចសន្យាការងាររបស់បុគ្គលិកមិនបានបញ្ជាក់។</li>
                <li>ការកែសម្រួលនៅទីនេះ នឹងអនុវត្តចំពោះការគណនាប្រាក់បៀវត្សនាពេលខាងមុខ។</li>
                <li>ការបិទដំណើរការរចនាសម្ព័ន្ធ មិនប៉ះពាល់ដល់បញ្ជីប្រាក់បៀវត្សដែលបានបើករួចនោះឡើយ។</li>
            </ul>
        </div>
    </div>
</div>
@endsection
