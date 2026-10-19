@extends('layouts.app')
@section('title', 'បង្កើតរចនាសម្ព័ន្ធប្រាក់បៀវត្ស')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('salary-structures.index') }}" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធប្រាក់បៀវត្ស</a></li>
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
            <i class="bi bi-layers"></i>
        </span>
        <h4 class="mb-0 fw-bold" style="font-size:1.15rem;">បង្កើតរចនាសម្ព័ន្ធប្រាក់បៀវត្ស</h4>
    </div>
    <a href="{{ route('salary-structures.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="height:35px; border-radius:6px; font-size:0.82rem;">
        <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
    </a>
</div>
@if($errors->any())
    <div class="alert alert-danger mb-2.5 py-2 px-3 small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="row g-2.5">
    <div class="col-lg-7">
        <form method="POST" action="{{ route('salary-structures.store') }}">
            @csrf
            <div class="form-card mb-2.5">
                <div class="section-title"><i class="bi bi-layers me-2 text-warning"></i>ព័ត៌មានលម្អិតនៃរចនាសម្ព័ន្ធ</div>
                <div class="row g-2 mb-2">
                    <div class="col-md-8">
                        <label class="form-label">ឈ្មោះរចនាសម្ព័ន្ធ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="ឧ. គ្រូបង្រៀនកម្រិត A">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>កូដសម្គាល់ <span class="text-danger">*</span></span>
                            <span class="text-muted fw-normal" style="font-size:0.7rem;"><i class="bi bi-lock-fill text-warning me-0.5"></i>ស្វ័យប្រវត្តិ</span>
                        </label>
                        <input type="text"
                               name="code"
                               class="form-control bg-light text-muted font-monospace fw-semibold text-center @error('code') is-invalid @enderror"
                               value="{{ old('code', $defaultCode ?? 'TSG00001') }}"
                               required
                               readonly
                               tabindex="-1"
                               title="កូដសម្គាល់ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ">
                        @error('code')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="employee_department_id">
                            <i class="bi bi-diagram-2 text-warning me-1"></i>ផ្នែក
                        </label>
                        <select name="employee_department_id" id="employee_department_id" class="form-select @error('employee_department_id') is-invalid @enderror">
                            <option value="">— ជ្រើសរើសផ្នែក (ទូទៅ/ទាំងអស់) —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" @selected(old('employee_department_id') == $dept->id)>{{ $dept->name }}</option>
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
                                <option value="{{ $pos->id }}" @selected(old('employee_position_id') == $pos->id)>{{ $pos->name }}</option>
                            @endforeach
                        </select>
                        @error('employee_position_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ប្រាក់បៀវត្សគោល ($) <span class="text-danger">*</span></label>
                        <input type="number" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', 0) }}" step="0.01" min="0" required>
                        @error('base_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-center pt-3">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" @checked(old('is_active', 1))>
                            <label class="form-check-label small fw-semibold text-dark" for="is_active" role="button">សកម្ម</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">ការពិពណ៌នា</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="ការពិពណ៌នាបន្ថែម (ស្រេចចិត្ត)...">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- សមាសភាគប្រាក់បៀវត្ស --}}
                <div class="section-title border-top pt-3 mt-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;background:#e0f2fe;color:#0369a1;font-size:0.75rem;">
                            <i class="bi bi-list-check"></i>
                        </span>
                        <span>សមាសភាគប្រាក់បៀវត្ស <small class="text-muted fw-normal" style="font-size:0.78rem;">(ប្រាក់ឧបត្ថម្ភ / ការកាត់ប្រាក់ — ស្រេចចិត្ត)</small></span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 px-2.5 py-1" style="font-size:0.78rem; border-radius:6px;" onclick="addComponent()">
                        <i class="bi bi-plus-lg"></i><span>បន្ថែមសមាសភាគ</span>
                    </button>
                </div>
                <div class="mb-2.5 p-2 rounded-2" style="background:#f8fafc; border:1px dashed #cbd5e1;">
                    <div class="small text-muted mb-1.5" style="font-size:0.75rem;">
                        <i class="bi bi-magic me-1 text-warning"></i>គំរូសមាសភាគរហ័ស (ចុចដើម្បីបន្ថែមភ្លាមៗ)៖
                    </div>
                    <div class="d-flex gap-1.5 flex-wrap">
                        <button type="button" class="btn btn-xs btn-light border py-1 px-2 preset-btn" style="font-size:0.75rem; border-radius:4px;" data-preset-name="ថ្លៃធ្វើដំណើរ" data-preset-label="ថ្លៃធ្វើដំណើរ ($50)" onclick="addPresetComponent('ថ្លៃធ្វើដំណើរ', 'earning', 'fixed', 50)">+ ថ្លៃធ្វើដំណើរ ($50)</button>
                        <button type="button" class="btn btn-xs btn-light border py-1 px-2 preset-btn" style="font-size:0.75rem; border-radius:4px;" data-preset-name="ថ្លៃអាហារ" data-preset-label="ថ្លៃអាហារ ($60)" onclick="addPresetComponent('ថ្លៃអាហារ', 'earning', 'fixed', 60)">+ ថ្លៃអាហារ ($60)</button>
                        <button type="button" class="btn btn-xs btn-light border py-1 px-2 preset-btn" style="font-size:0.75rem; border-radius:4px;" data-preset-name="ប្រាក់លើកទឹកចិត្ត" data-preset-label="ប្រាក់លើកទឹកចិត្ត ($30)" onclick="addPresetComponent('ប្រាក់លើកទឹកចិត្ត', 'earning', 'fixed', 30)">+ ប្រាក់លើកទឹកចិត្ត ($30)</button>
                        <button type="button" class="btn btn-xs btn-light border py-1 px-2 preset-btn" style="font-size:0.75rem; border-radius:4px;" data-preset-name="ប.ស.ស." data-preset-label="ប.ស.ស. (4%)" onclick="addPresetComponent('ប.ស.ស.', 'deduction', 'percentage', 4)">+ ប.ស.ស. (4%)</button>
                        <button type="button" class="btn btn-xs btn-light border py-1 px-2 preset-btn" style="font-size:0.75rem; border-radius:4px;" data-preset-name="ពន្ធលើប្រាក់បៀវត្ស" data-preset-label="ពន្ធលើប្រាក់បៀវត្ស (5%)" onclick="addPresetComponent('ពន្ធលើប្រាក់បៀវត្ស', 'deduction', 'percentage', 5)">+ ពន្ធលើប្រាក់បៀវត្ស (5%)</button>
                    </div>
                </div>

                <div id="componentsList"></div>
                <div id="noComponentsNotice" class="text-center py-3 text-muted small border rounded-2 mb-2" style="background:#fafafa; border-style:dashed !important;">
                    <i class="bi bi-layers-half text-secondary d-block fs-4 mb-1 opacity-50"></i>
                    មិនទាន់មានសមាសភាគត្រូវបានបន្ថែមនៅឡើយទេ។ ចុច <strong>«+ បន្ថែមសមាសភាគ»</strong> ឬចុច <strong>គំរូរហ័ស</strong> ខាងលើ។
                </div>

                <div class="mt-3 pt-2.5 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('salary-structures.index') }}" class="btn btn-sm btn-light border px-3" style="border-radius:6px; font-size:0.82rem;">បោះបង់</a>
                    <button type="submit" class="btn btn-sm btn-primary-action px-3.5 py-1.5 shadow-sm" style="border-radius:6px; font-size:0.84rem;">
                        <i class="bi bi-save me-1"></i>បង្កើតរចនាសម្ព័ន្ធ
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
                <li>លោកអ្នកអាចបន្ថែម <strong>សមាសភាគប្រាក់បៀវត្ស</strong> (ប្រាក់ឧបត្ថម្ភ/ការកាត់ប្រាក់) ដោយផ្ទាល់ក្នុងទម្រង់នេះតែម្តង ឬបន្ថែមនៅពេលក្រោយក៏បាន។</li>
                <li>សមាសភាគនីមួយៗអាចគណនាជា <em>ទឹកប្រាក់ថេរ ($)</em> ឬ <em>ភាគរយនៃប្រាក់បៀវត្សគោល (%)</em>។</li>
                <li>ការបិទដំណើរការរចនាសម្ព័ន្ធ មិនប៉ះពាល់ដល់បញ្ជីប្រាក់បៀវត្សដែលបានបើករួចនោះឡើយ។</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let compIdx = 0;

function syncPresetButtons() {
    const existingNames = Array.from(document.querySelectorAll('#componentsList input[name$="[name]"]'))
        .map(input => input.value.trim().toLowerCase());

    document.querySelectorAll('.preset-btn').forEach(btn => {
        const presetName = (btn.dataset.presetName || '').trim().toLowerCase();
        if (existingNames.includes(presetName)) {
            btn.classList.add('btn-secondary', 'disabled');
            btn.classList.remove('btn-light');
            btn.innerHTML = `<i class="bi bi-check2"></i> ${btn.dataset.presetLabel} (បានបន្ថែម)`;
        } else {
            btn.classList.remove('btn-secondary', 'disabled');
            btn.classList.add('btn-light');
            btn.innerHTML = `+ ${btn.dataset.presetLabel}`;
        }
    });
}

function updateComponentsVisibility() {
    const list = document.getElementById('componentsList');
    const notice = document.getElementById('noComponentsNotice');
    if (list.children.length === 0) {
        notice.classList.remove('d-none');
    } else {
        notice.classList.add('d-none');
    }
    syncPresetButtons();
}

function addComponent(name = '', type = 'earning', calcType = 'fixed', val = 0) {
    if (name.trim() !== '') {
        const existingInputs = document.querySelectorAll('#componentsList input[name$="[name]"]');
        for (let input of existingInputs) {
            if (input.value.trim().toLowerCase() === name.trim().toLowerCase()) {
                input.focus();
                input.classList.add('is-invalid');
                setTimeout(() => input.classList.remove('is-invalid'), 1200);
                return;
            }
        }
    }

    const idx = compIdx++;
    const isEarning = type === 'earning';
    const isFixed = calcType === 'fixed';

    const html = `
        <div class="p-2.5 mb-2 rounded border" id="comp-${idx}" style="background:#fcfcfd; border-color:#e2e8f0;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge ${isEarning ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'} fw-semibold px-2 py-0.5" id="comp-badge-${idx}" style="font-size:.75rem;">
                    ${isEarning ? 'ប្រាក់ឧបត្ថម្ភ (+)' : 'ការកាត់ប្រាក់ (-)'}
                </span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none d-flex align-items-center gap-1" onclick="removeComponent(${idx})" title="លុបសមាសភាគ" style="font-size:0.78rem;">
                    <i class="bi bi-trash"></i> <span>លុប</span>
                </button>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-nowrap d-block mb-1" style="font-size:0.75rem; height:18px; line-height:18px;">ឈ្មោះសមាសភាគ <span class="text-danger">*</span></label>
                    <input type="text" name="components[${idx}][name]" class="form-control comp-name-input" value="${name}" placeholder="ឧ. ថ្លៃធ្វើដំណើរ" style="height:36px; font-size:.82rem;" oninput="syncPresetButtons()" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-nowrap d-block mb-1" style="font-size:0.75rem; height:18px; line-height:18px;">ប្រភេទ</label>
                    <select name="components[${idx}][type]" class="form-select" style="height:36px; font-size:.82rem;" onchange="updateCompType(${idx}, this.value)">
                        <option value="earning" ${isEarning ? 'selected' : ''}>ប្រាក់ឧបត្ថម្ភ (+)</option>
                        <option value="deduction" ${!isEarning ? 'selected' : ''}>ការកាត់ប្រាក់ (-)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-nowrap d-block mb-1" style="font-size:0.75rem; height:18px; line-height:18px;">រូបមន្ត</label>
                    <select name="components[${idx}][calculation_type]" class="form-select px-2" style="height:36px; font-size:.82rem;" onchange="updateCompCalc(${idx}, this.value)">
                        <option value="fixed" ${isFixed ? 'selected' : ''}>ទឹកប្រាក់ ($)</option>
                        <option value="percentage" ${!isFixed ? 'selected' : ''}>ភាគរយ (%)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-nowrap d-block mb-1" style="font-size:0.75rem; height:18px; line-height:18px;" id="val-label-${idx}">${isFixed ? 'ចំនួនទឹកប្រាក់ ($)' : 'ភាគរយ (%)'}</label>
                    <input type="number" name="${isFixed ? `components[${idx}][amount]` : `components[${idx}][percentage]`}" id="val-input-${idx}" class="form-control" value="${val}" step="0.01" min="0" ${!isFixed ? 'max="100"' : ''} style="height:36px; font-size:.82rem;" required>
                </div>
            </div>
        </div>`;

    document.getElementById('componentsList').insertAdjacentHTML('beforeend', html);
    updateComponentsVisibility();
}

function addPresetComponent(name, type, calcType, val) {
    addComponent(name, type, calcType, val);
}

function removeComponent(idx) {
    const el = document.getElementById(`comp-${idx}`);
    if (el) el.remove();
    updateComponentsVisibility();
}

function updateCompType(idx, val) {
    const badge = document.getElementById(`comp-badge-${idx}`);
    if (!badge) return;
    if (val === 'earning') {
        badge.className = 'badge bg-success-subtle text-success fw-semibold px-2 py-0.5';
        badge.textContent = 'ប្រាក់ឧបត្ថម្ភ / Earning (+)';
    } else {
        badge.className = 'badge bg-danger-subtle text-danger fw-semibold px-2 py-0.5';
        badge.textContent = 'ការកាត់ប្រាក់ / Deduction (-)';
    }
}

function updateCompCalc(idx, val) {
    const label = document.getElementById(`val-label-${idx}`);
    const input = document.getElementById(`val-input-${idx}`);
    if (!label || !input) return;

    if (val === 'percentage') {
        label.textContent = 'ភាគរយ (%)';
        input.name = `components[${idx}][percentage]`;
        input.max = '100';
    } else {
        label.textContent = 'ចំនួនទឹកប្រាក់ ($)';
        input.name = `components[${idx}][amount]`;
        input.removeAttribute('max');
    }
}
</script>
@endpush
