@extends('layouts.app')

@php
    $staffName = $employee->full_name ?? $employee->user->name ?? $employee->employee_code;
    $parts = explode(' ', trim($staffName));
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    $contact = $employee->employeeContact;
    $hasPhoto = !empty($employee->photo_url);

    // Unpack permanent address if available
    $permRaw = $contact->permanent_address ?? '';
    $permStreet = '';
    $permVillage = '';
    $permCommune = '';
    $permDistrict = '';
    $permProvince = '';
    if (!empty($permRaw)) {
        $rawParts = array_map('trim', explode(',', $permRaw));
        $streetArr = [];
        foreach ($rawParts as $p) {
            if (str_starts_with($p, 'ភូមិ ')) {
                $permVillage = trim(substr($p, strlen('ភូមិ ')));
            } elseif (str_starts_with($p, 'ឃុំ/សង្កាត់ ')) {
                $permCommune = trim(substr($p, strlen('ឃុំ/សង្កាត់ ')));
            } elseif (str_starts_with($p, 'ស្រុក/ខណ្ឌ ')) {
                $permDistrict = trim(substr($p, strlen('ស្រុក/ខណ្ឌ ')));
            } elseif (str_starts_with($p, 'ខេត្ត/រាជធានី ')) {
                $permProvince = trim(substr($p, strlen('ខេត្ត/រាជធានី ')));
            } else {
                $streetArr[] = $p;
            }
        }
        $permStreet = implode(', ', $streetArr);
    }
@endphp

@section('title', 'កែប្រែព័ត៌មានបុគ្គលិក — ' . $staffName . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}" class="text-decoration-none text-muted">បុគ្គលិក</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.show', $employee) }}" class="text-decoration-none text-muted">{{ $staffName }}</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កែប្រែ</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
:root {
    --amber-brand: #e8a838;
    --amber-dark: #c98b1e;
    --amber-subtle: #fef3c7;
    --amber-text: #b45309;
    --border-color: #e2e8f0;
}

.employee-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-lg, 12px);
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.75rem;
    position: relative;
    overflow: hidden;
    color: #fff;
}
.employee-banner .banner-icon {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.16);
    border-radius: var(--radius-md, 8px);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #fff;
    margin-bottom: 0.25rem;
}
.employee-banner h1 {
    font-size: 1.05rem;
    font-weight: 800;
    color: #fff;
    margin: 0 0 0.15rem;
}
.employee-banner p {
    font-size: 0.76rem;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
}
.employee-banner .banner-actions {
    position: absolute;
    top: 0.75rem;
    right: 1rem;
    z-index: 2;
    display: flex;
    gap: 0.5rem;
}

/* MULTI-STEP WIZARD */
.wizard-stepper {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.65rem 0.85rem;
    margin-bottom: 1.15rem;
}
.wizard-steps-track {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
    padding-bottom: 2px;
}
.wizard-steps-track::-webkit-scrollbar {
    height: 3px;
}
.wizard-steps-track::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.wizard-step-tab {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 20px;
    padding: 0.25rem 0.6rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.73rem;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    user-select: none;
    white-space: nowrap;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.wizard-step-tab:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
    color: #1e293b;
}
.wizard-step-tab .step-num {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #cbd5e1;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.68rem;
    font-weight: 700;
    transition: all 0.2s ease;
}
.wizard-step-tab.active {
    background: #fffdf5;
    border-color: #e8a838;
    color: #92400e;
    box-shadow: 0 1px 3px rgba(232, 168, 56, 0.18);
}
.wizard-step-tab.active .step-num {
    background: #e8a838;
    color: #ffffff;
}
.wizard-step-tab.completed {
    background: #f0fdf4;
    border-color: #86efac;
    color: #166534;
}
.wizard-step-tab.completed .step-num {
    background: #22c55e;
    color: #ffffff;
}
.step-connector {
    flex-grow: 1;
    height: 2px;
    background: #e2e8f0;
    min-width: 8px;
}
.wizard-step-pane {
    display: none;
    animation: wizardFadeIn 0.22s ease-in-out;
}
.wizard-step-pane.active {
    display: block;
}
@keyframes wizardFadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

.unified-form-card {
    background: #ffffff;
    border: 1px solid var(--border-color, #e2e8f0);
    border-top: 3px solid #e8a838;
    border-radius: 8px;
    padding: 1.25rem 1.35rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(0, 0, 0, 0.02);
}
.form-section-block {
    position: relative;
}
.section-divider {
    height: 1px;
    background: #f1f5f9;
    border-top: 1px dashed #e2e8f0;
    margin: 1.35rem 0 1.15rem 0;
}
.step-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.4rem;
    border-bottom: 1px solid #f1f5f9;
}
.step-badge {
    width: 24px;
    height: 24px;
    background: #e8a838;
    color: #ffffff;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.76rem;
    font-weight: 700;
    flex-shrink: 0;
}
.step-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.step-subtitle {
    font-size: 0.76rem;
    color: #64748b;
    font-weight: 400;
    margin-left: 0.25rem;
}

.form-label-kh {
    font-size: 0.78rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.25rem;
}
.required-star {
    color: #ef4444;
    font-weight: bold;
}
.form-control-custom,
.form-select-custom {
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0.35rem 0.65rem;
    font-size: 0.82rem;
    height: 35px;
    min-height: 35px;
    color: #1e293b;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
textarea.form-control-custom {
    height: auto;
    min-height: 65px;
    padding: 0.45rem 0.65rem;
}
.form-control-custom:focus,
.form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.18);
    outline: 0;
}

.student-photo-box {
    width: 130px;
    height: 165px;
    background-color: #f8fafc;
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
}
.student-photo-box:hover {
    border-color: #e8a838;
    background-color: #fffdf5;
    box-shadow: 0 4px 12px rgba(232, 168, 56, 0.16);
}
.student-photo-box img#thumbPhotoPreview {
    object-fit: cover !important;
    object-position: center top !important;
}

.preview-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 0.85rem 1.15rem;
    position: sticky;
    top: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}
.preview-avatar-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #e2e8f0;
    margin: 0.5rem auto 0.75rem;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.preview-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.35rem 0;
    border-bottom: 1px dashed #f1f5f9;
    font-size: 0.78rem;
}
.preview-row:last-child {
    border-bottom: none;
}
.preview-label {
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.preview-val {
    color: #1e293b;
    font-weight: 500;
    text-align: right;
    max-width: 58%;
    word-break: break-word;
}
</style>
@endpush

@section('content')

{{-- TOP BANNER --}}
<div class="employee-banner">
    <div class="banner-actions">
        <button type="button" onclick="document.getElementById('employeeEditForm').requestSubmit()" class="btn btn-sm text-dark fw-bold shadow-xs" style="background: #ffffff; border: 1px solid rgba(255, 255, 255, 0.9);">
            <i class="bi bi-check2-circle me-1 text-success"></i>រក្សាទុកការកែប្រែ
        </button>
        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm text-white" style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.28);">
            <i class="bi bi-eye me-1"></i>មើលព័ត៌មាន
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-sm text-white" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25);">
            <i class="bi bi-arrow-left me-1"></i>បញ្ជីបុគ្គលិក
        </a>
    </div>
    <div class="banner-icon"><i class="bi bi-pencil-square"></i></div>
    <h1>កែប្រែព័ត៌មានបុគ្គលិក</h1>
    <p>កែប្រែទិន្នន័យសម្រាប់ <strong class="text-white">{{ $staffName }}</strong> (<span class="font-monospace text-white-50">{{ $employee->employee_code }}</span>)</p>
</div>

{{-- ERROR SUMMARY --}}
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-3 mb-3 shadow-sm" role="alert" style="border-radius: var(--radius-md, 8px);">
        <i class="bi bi-exclamation-octagon-fill fs-5 mt-1 text-danger"></i>
        <div class="flex-grow-1">
            <h6 class="alert-heading fw-bold mb-1">សូមកែតម្រូវកំហុសឆ្គងខាងក្រោម៖</h6>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('employees.update', $employee) }}" method="POST" id="employeeEditForm" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <div class="row g-2.5">
        {{-- LEFT COLUMN: FORM SECTIONS --}}
        <div class="col-xl-8 col-lg-8">

            {{-- ═══════════════════════════════════════════════════════════════
                 MULTI-STEP WIZARD FORM CARD (ទម្រង់បែបបទជាជំហាន)
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="unified-form-card">

                {{-- WIZARD STEPPER TRACK --}}
                <div class="wizard-stepper">
                    <div class="d-flex align-items-center justify-content-between wizard-steps-track gap-1 flex-nowrap overflow-x-auto">
                        <div class="wizard-step-tab active" data-step="1" onclick="showStep(1)" role="button" tabindex="0">
                            <span class="step-num">1</span>
                            <span class="step-label">ព័ត៌មានផ្ទាល់ខ្លួន</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="2" onclick="showStep(2)" role="button" tabindex="0">
                            <span class="step-num">2</span>
                            <span class="step-label">អាសយដ្ឋាន</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="3" onclick="showStep(3)" role="button" tabindex="0">
                            <span class="step-num">3</span>
                            <span class="step-label">ការងារ</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="4" onclick="showStep(4)" role="button" tabindex="0">
                            <span class="step-num">4</span>
                            <span class="step-label">ប្រាក់ខែ</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="5" onclick="showStep(5)" role="button" tabindex="0">
                            <span class="step-num">5</span>
                            <span class="step-label">ការសិក្សា</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="6" onclick="showStep(6)" role="button" tabindex="0">
                            <span class="step-num">6</span>
                            <span class="step-label">ទំនាក់ទំនងបន្ទាន់</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="wizard-step-tab" data-step="7" onclick="showStep(7)" role="button" tabindex="0">
                            <span class="step-num">7</span>
                            <span class="step-label">គណនីប្រព័ន្ធ</span>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 4px; background-color: #e2e8f0; border-radius: 999px;">
                        <div class="progress-bar" id="wizardProgressBar" role="progressbar" style="width: 14.28%; background-color: #e8a838; transition: width 0.3s ease;"></div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 1: PERSONAL INFORMATION
                ═══════════════════════════════════════════════════════════ --}}
                <div class="wizard-step-pane active" id="stepPane1" data-step="1">

                {{-- SECTION 1: PERSONAL INFORMATION --}}
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">1</span>
                        <h4 class="step-title">១. ព័ត៌មានផ្ទាល់ខ្លួន</h4>
                    </div>

                <div class="row g-2">
                    {{-- EMPLOYEE ID --}}
                    <div class="col-md-4">
                        <label for="employee_code" class="form-label-kh">អត្តលេខបុគ្គលិក <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-2 py-1">
                                <i class="bi bi-lock-fill text-warning"></i>
                            </span>
                            <input type="text"
                                   name="employee_code"
                                   id="employee_code"
                                   class="form-control form-control-custom bg-light border-start-0 font-monospace fw-bold @error('employee_code') is-invalid @enderror"
                                   value="{{ old('employee_code', $employee->employee_code) }}"
                                   readonly
                                   tabindex="-1">
                        </div>
                        <div class="form-text text-muted small mt-0.5" style="font-size: 0.68rem;">អត្តលេខចាក់សោកុំឲ្យកែបាន</div>
                        @error('employee_code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- LAST NAME KHMER --}}
                    <div class="col-md-4">
                        <label for="last_name_khmer" class="form-label-kh">គោត្តនាម (ខ្មែរ) <span class="required-star">*</span></label>
                        <input type="text" name="last_name_khmer" id="last_name_khmer" class="form-control form-control-custom @error('last_name_khmer') is-invalid @enderror" value="{{ old('last_name_khmer', $employee->last_name_khmer) }}" placeholder="ឧ. សុខ" oninput="updateLivePreview()">
                        @error('last_name_khmer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FIRST NAME KHMER --}}
                    <div class="col-md-4">
                        <label for="first_name_khmer" class="form-label-kh">នាម (ខ្មែរ) <span class="required-star">*</span></label>
                        <input type="text" name="first_name_khmer" id="first_name_khmer" class="form-control form-control-custom @error('first_name_khmer') is-invalid @enderror" value="{{ old('first_name_khmer', $employee->first_name_khmer) }}" placeholder="ឧ. ចាន់ដា" oninput="updateLivePreview()">
                        @error('first_name_khmer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- LAST NAME LATIN --}}
                    <div class="col-md-6">
                        <label for="last_name" class="form-label-kh">គោត្តនាម (អក្សរឡាតាំង) <span class="required-star">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control form-control-custom @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name) }}" placeholder="SOK" oninput="updateLivePreview()">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FIRST NAME LATIN --}}
                    <div class="col-md-6">
                        <label for="first_name" class="form-label-kh">នាម (អក្សរឡាតាំង) <span class="required-star">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control form-control-custom @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name) }}" placeholder="CHANDA" oninput="updateLivePreview()">
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- GENDER --}}
                    <div class="col-md-4">
                        <label for="gender" class="form-label-kh">ភេទ <span class="required-star">*</span></label>
                        <select name="gender" id="gender" class="form-select form-select-custom @error('gender') is-invalid @enderror" required onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសភេទ</option>
                            <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>ប្រុស</option>
                            <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>ស្រី</option>
                            <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>ផ្សេងៗ</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DATE OF BIRTH --}}
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label-kh">ថ្ងៃខែឆ្នាំកំណើត <span class="required-star">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control form-control-custom @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '') }}" onchange="updateLivePreview()">
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PHONE --}}
                    <div class="col-md-4">
                        <label for="phone" class="form-label-kh">លេខទូរស័ព្ទ <span class="required-star">*</span></label>
                        <input type="text" name="phone" id="phone" class="form-control form-control-custom @error('phone') is-invalid @enderror" value="{{ old('phone', $contact->phone ?? '') }}" placeholder="012 345 678" oninput="updateLivePreview()">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-4">
                        <label for="contact_email" class="form-label-kh">អ៊ីមែលបុគ្គលិក</label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control form-control-custom @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $contact->email ?? '') }}" placeholder="staff@example.com">
                        @error('contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MARITAL STATUS --}}
                    <div class="col-md-4">
                        <label for="marital_status" class="form-label-kh">ស្ថានភាពអាពាហ៍ពិពាហ៍</label>
                        <select name="marital_status" id="marital_status" class="form-select form-select-custom @error('marital_status') is-invalid @enderror">
                            <option value="">ជ្រើសរើសស្ថានភាព</option>
                            <option value="Single" {{ old('marital_status', $employee->marital_status) == 'Single' ? 'selected' : '' }}>នៅលីវ</option>
                            <option value="Married" {{ old('marital_status', $employee->marital_status) == 'Married' ? 'selected' : '' }}>រៀបការរួច</option>
                            <option value="Divorced" {{ old('marital_status', $employee->marital_status) == 'Divorced' ? 'selected' : '' }}>លែងលះ</option>
                            <option value="Widowed" {{ old('marital_status', $employee->marital_status) == 'Widowed' ? 'selected' : '' }}>មេម៉ាយ / ពោះម៉ាយ</option>
                        </select>
                        @error('marital_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NATIONAL ID / PASSPORT --}}
                    <div class="col-md-4">
                        <label for="national_id" class="form-label-kh">អត្តសញ្ញាណប័ណ្ណ / លិខិតឆ្លងដែន <span class="required-star">*</span></label>
                        <input type="text" name="national_id" id="national_id" class="form-control form-control-custom @error('national_id') is-invalid @enderror" value="{{ old('national_id', $employee->national_id) }}" placeholder="លេខអត្តសញ្ញាណប័ណ្ណ ឬ លិខិតឆ្លងដែន">
                        @error('national_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PLACE OF BIRTH (បំបែក ខេត្ត ស្រុក ឃុំ ភូមិ) --}}
                    <div class="col-12 mt-2 pt-2 border-top">
                        <label class="form-label-kh d-block mb-1 text-muted" style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-geo-alt-fill text-warning me-1"></i> ទីកន្លែងកំណើត
                        </label>
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="birth_province" class="form-label-kh">ខេត្ត/រាជធានីកំណើត <span class="required-star">*</span></label>
                        <select name="birth_province" id="birth_province" class="form-select form-control-custom @error('birth_province') is-invalid @enderror">
                            @include('partials.cambodia_province_options', ['selected' => old('birth_province', $employee->birth_province)])
                        </select>
                        @error('birth_province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="birth_district" class="form-label-kh">ស្រុក/ខណ្ឌកំណើត <span class="required-star">*</span></label>
                        <select name="birth_district" id="birth_district" class="form-select form-control-custom @error('birth_district') is-invalid @enderror" data-selected="{{ old('birth_district', $employee->birth_district) }}">
                            <option value="">ជ្រើសខេត្តជាមុន</option>
                        </select>
                        @error('birth_district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="birth_commune" class="form-label-kh">ឃុំ/សង្កាត់កំណើត <span class="required-star">*</span></label>
                        <select name="birth_commune" id="birth_commune" class="form-select form-control-custom @error('birth_commune') is-invalid @enderror" data-selected="{{ old('birth_commune', $employee->birth_commune) }}">
                            <option value="">ជ្រើសស្រុកជាមុន</option>
                        </select>
                        @error('birth_commune')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="birth_village" class="form-label-kh">ភូមិកំណើត <span class="required-star">*</span></label>
                        <select name="birth_village" id="birth_village" class="form-select form-control-custom @error('birth_village') is-invalid @enderror" data-selected="{{ old('birth_village', $employee->birth_village) }}">
                            <option value="">ជ្រើសឃុំជាមុន</option>
                        </select>
                        @error('birth_village')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PHOTO UPLOAD --}}
                    <div class="col-12 mt-1">
                        <label class="form-label-kh d-block mb-1">រូបថតបុគ្គលិក <span class="required-star">*</span> <span class="text-muted fw-normal" style="font-size: 0.72rem;">(ទំហំ 3x4 ឬ 4x6)</span></label>
                        <div style="width: 130px;">
                            <div class="student-photo-box text-center p-2" onclick="document.getElementById('photo_input').click()" title="ចុចដើម្បីជ្រើសរើស ឬប្តូររូបថត">
                                <img id="thumbPhotoPreview" src="{{ $employee->photo_url ?? '' }}" alt="Preview" class="position-absolute w-100 h-100 {{ $hasPhoto ? '' : 'd-none' }}" style="object-fit: cover; object-position: center top; top: 0; left: 0;">
                                <div id="thumbPhotoPlaceholder" class="d-flex flex-column align-items-center text-muted w-100 {{ $hasPhoto ? 'd-none' : '' }}">
                                    <i class="bi bi-person-bounding-box mb-1 text-warning" style="font-size: 2rem; line-height: 1;"></i>
                                    <span class="fw-medium text-dark" style="font-size: 0.78rem; line-height: 1.3;">ចុចដើម្បីប្តូររូបថត</span>
                                    <span class="text-muted" style="font-size: 0.68rem; line-height: 1.2;">3x4 ឬ 4x6</span>
                                </div>
                            </div>
                            
                            {{-- Photo Action Buttons below the box: Never overlaps the face! --}}
                            <div class="d-flex align-items-center gap-1 mt-1.5 w-100">
                                <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1 d-flex align-items-center justify-content-center gap-1 py-1" onclick="document.getElementById('photo_input').click()" style="font-size: 0.72rem; border-radius: 4px;">
                                    <i class="bi bi-camera"></i> <span id="photoActionLabel">{{ $hasPhoto ? 'ប្តូររូប' : 'ជ្រើសរូប' }}</span>
                                </button>
                                <button type="button" id="btnRemovePhoto" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center py-1 px-2 {{ $hasPhoto ? '' : 'd-none' }}" onclick="removeSelectedPhoto()" style="font-size: 0.72rem; border-radius: 4px;" title="លុបរូបថត">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photo_input" class="d-none @error('photo') is-invalid @enderror" accept="image/png, image/jpeg, image/webp" onchange="previewEmployeePhoto(this)">
                        @error('photo')
                            <div class="text-danger small mt-1" style="font-size: 0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                </div>
                </div> {{-- /stepPane1 --}}

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 2: ADDRESS INFORMATION
                ═══════════════════════════════════════════════════════════ --}}
                <div class="wizard-step-pane" id="stepPane2" data-step="2" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">2</span>
                        <h4 class="step-title">២. ព័ត៌មានអាសយដ្ឋាន</h4>
                    </div>

                    {{-- SUBSECTION A: CURRENT ADDRESS (អាសយដ្ឋានបច្ចុប្បន្ន) --}}
                    <div class="mb-3">
                        <label class="form-label-kh d-flex align-items-center gap-1.5 text-primary mb-2" style="font-size: 0.8rem; font-weight: 700;">
                            <i class="bi bi-geo-alt-fill"></i> អាសយដ្ឋានបច្ចុប្បន្ន (Current Address)
                        </label>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="current_address" class="form-label-kh">លេខផ្លូវ</label>
                                <input type="text" name="current_address" id="current_address" class="form-control form-control-custom @error('current_address') is-invalid @enderror" value="{{ old('current_address', $contact->current_address ?? '') }}" placeholder="បញ្ចូលលេខផ្លូវ..." oninput="syncPermanentAddress()">
                                @error('current_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="province" class="form-label-kh">ខេត្ត / រាជធានី <span class="required-star">*</span></label>
                                <select name="province" id="province" class="form-select form-control-custom @error('province') is-invalid @enderror" required onchange="syncPermanentAddress()">
                                    @include('partials.cambodia_province_options', ['selected' => old('province', $contact->province ?? '')])
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="district" class="form-label-kh">ស្រុក / ខណ្ឌ <span class="required-star">*</span></label>
                                <select name="district" id="district" class="form-select form-control-custom @error('district') is-invalid @enderror" data-selected="{{ old('district', $contact->district ?? '') }}" required onchange="syncPermanentAddress()">
                                    <option value="">ជ្រើសខេត្តជាមុន</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="commune" class="form-label-kh">ឃុំ / សង្កាត់ <span class="required-star">*</span></label>
                                <select name="commune" id="commune" class="form-select form-control-custom @error('commune') is-invalid @enderror" data-selected="{{ old('commune', $contact->commune ?? '') }}" required onchange="syncPermanentAddress()">
                                    <option value="">ជ្រើសស្រុកជាមុន</option>
                                </select>
                                @error('commune')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="village" class="form-label-kh">ភូមិ <span class="required-star">*</span></label>
                                <select name="village" id="village" class="form-select form-control-custom @error('village') is-invalid @enderror" data-selected="{{ old('village', $contact->village ?? '') }}" required onchange="syncPermanentAddress()">
                                    <option value="">ជ្រើសឃុំជាមុន</option>
                                </select>
                                @error('village')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SUBSECTION B: PERMANENT ADDRESS (អាសយដ្ឋានអចិន្ត្រៃយ៍) --}}
                    <div class="pt-2.5 border-top">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <label class="form-label-kh d-flex align-items-center gap-1.5 text-secondary mb-0" style="font-size: 0.8rem; font-weight: 700;">
                                <i class="bi bi-house-door-fill text-warning"></i> អាសយដ្ឋានអចិន្ត្រៃយ៍ (Permanent Address)
                            </label>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="checkbox" id="sameAsCurrentAddress" onchange="toggleSameAddress(this)" style="cursor: pointer; accent-color: #e8a838;">
                                <label class="form-check-label text-dark fw-medium small" for="sameAsCurrentAddress" style="cursor: pointer; font-size: 0.76rem;">
                                    ដូចគ្នានឹងអាសយដ្ឋានបច្ចុប្បន្ន
                                </label>
                            </div>
                        </div>

                        {{-- Hidden field to hold compiled permanent address for database --}}
                        <input type="hidden" name="permanent_address" id="permanent_address" value="{{ old('permanent_address', $contact->permanent_address ?? '') }}">

                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="permanent_street" class="form-label-kh">លេខផ្លូវ</label>
                                <input type="text" name="permanent_street" id="permanent_street" class="form-control form-control-custom" value="{{ old('permanent_street', $permStreet) }}" placeholder="បញ្ចូលលេខផ្លូវ..." oninput="updateCompiledPermanentAddress()">
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_province" class="form-label-kh">ខេត្ត / រាជធានី <span class="required-star">*</span></label>
                                <select name="permanent_province" id="permanent_province" class="form-select form-control-custom" onchange="updateCompiledPermanentAddress()">
                                    @include('partials.cambodia_province_options', ['selected' => old('permanent_province', $permProvince)])
                                </select>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_district" class="form-label-kh">ស្រុក / ខណ្ឌ <span class="required-star">*</span></label>
                                <select name="permanent_district" id="permanent_district" class="form-select form-control-custom" data-selected="{{ old('permanent_district', $permDistrict) }}" onchange="updateCompiledPermanentAddress()">
                                    <option value="">ជ្រើសខេត្តជាមុន</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_commune" class="form-label-kh">ឃុំ / សង្កាត់ <span class="required-star">*</span></label>
                                <select name="permanent_commune" id="permanent_commune" class="form-select form-control-custom" data-selected="{{ old('permanent_commune', $permCommune) }}" onchange="updateCompiledPermanentAddress()">
                                    <option value="">ជ្រើសស្រុកជាមុន</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-6">
                                <label for="permanent_village" class="form-label-kh">ភូមិ <span class="required-star">*</span></label>
                                <select name="permanent_village" id="permanent_village" class="form-select form-control-custom" data-selected="{{ old('permanent_village', $permVillage) }}" onchange="updateCompiledPermanentAddress()">
                                    <option value="">ជ្រើសឃុំជាមុន</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </div> {{-- /stepPane2 --}}

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 3: EMPLOYMENT INFORMATION
                ═══════════════════════════════════════════════════════════ --}}
                <div class="wizard-step-pane" id="stepPane3" data-step="3" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">3</span>
                        <h4 class="step-title">៣. ព័ត៌មានការងារ</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="employee_position_id" class="form-label-kh">តួនាទី / មុខតំណែង <span class="required-star">*</span></label>
                        <select name="employee_position_id" id="employee_position_id" class="form-select form-select-custom @error('employee_position_id') is-invalid @enderror" required onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសមុខតំណែង</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}" data-name="{{ $pos->name }}" {{ old('employee_position_id', $employee->employee_position_id) == $pos->id ? 'selected' : '' }}>
                                    {{ $pos->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="employee_department_id" class="form-label-kh">ផ្នែក</label>
                        <select name="employee_department_id" id="employee_department_id" class="form-select form-select-custom @error('employee_department_id') is-invalid @enderror" onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសផ្នែក</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" data-name="{{ $dept->name }}" {{ old('employee_department_id', $employee->employee_department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="employee_type" class="form-label-kh">ប្រភេទបុគ្គលិក <span class="required-star">*</span></label>
                        <select name="employee_type" id="employee_type" class="form-select form-select-custom @error('employee_type') is-invalid @enderror">
                            <option value="">ជ្រើសរើសប្រភេទបុគ្គលិក</option>
                            <option value="Teacher" {{ old('employee_type', $employee->employee_type) == 'Teacher' ? 'selected' : '' }}>គ្រូបង្រៀន</option>
                            <option value="Administrative Staff" {{ old('employee_type', $employee->employee_type) == 'Administrative Staff' ? 'selected' : '' }}>បុគ្គលិករដ្ឋបាល</option>
                            <option value="Accountant" {{ old('employee_type', $employee->employee_type) == 'Accountant' ? 'selected' : '' }}>គណនេយ្យករ</option>
                            <option value="Receptionist" {{ old('employee_type', $employee->employee_type) == 'Receptionist' ? 'selected' : '' }}>បុគ្គលិកទទួលភ្ញៀវ</option>
                            <option value="Librarian" {{ old('employee_type', $employee->employee_type) == 'Librarian' ? 'selected' : '' }}>បណ្ណារក្ស</option>
                            <option value="IT Support" {{ old('employee_type', $employee->employee_type) == 'IT Support' ? 'selected' : '' }}>ព័ត៌មានវិទ្យា</option>
                            <option value="Support Staff" {{ old('employee_type', $employee->employee_type) == 'Support Staff' ? 'selected' : '' }}>បុគ្គលិកជំនួយ</option>
                            <option value="Other" {{ old('employee_type', $employee->employee_type) == 'Other' ? 'selected' : '' }}>ផ្សេងៗ</option>
                        </select>
                        @error('employee_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date_of_joining" class="form-label-kh">ថ្ងៃចូលបម្រើការងារ <span class="required-star">*</span></label>
                        <input type="date" name="date_of_joining" id="date_of_joining" class="form-control form-control-custom @error('date_of_joining') is-invalid @enderror" value="{{ old('date_of_joining', $employee->date_of_joining ? $employee->date_of_joining->format('Y-m-d') : '') }}" required onchange="updateLivePreview()">
                        @error('date_of_joining')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="employee_status_id" class="form-label-kh">ស្ថានភាពការងារ <span class="required-star">*</span></label>
                        <select name="employee_status_id" id="employee_status_id" class="form-select form-select-custom @error('employee_status_id') is-invalid @enderror" required onchange="updateLivePreview()">
                            <option value="">ជ្រើសរើសស្ថានភាពការងារ</option>
                            @foreach($statuses as $stat)
                                <option value="{{ $stat->id }}" data-name="{{ $stat->khmer_name }}" {{ old('employee_status_id', $employee->employee_status_id) == $stat->id ? 'selected' : '' }}>
                                    {{ $stat->khmer_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="contract_type" class="form-label-kh">កិច្ចសន្យាការងារ</label>
                        <select name="contract_type" id="contract_type" class="form-select form-select-custom @error('contract_type') is-invalid @enderror">
                            <option value="">ជ្រើសរើសប្រភេទកិច្ចសន្យា</option>
                            <option value="Permanent" {{ old('contract_type', $employee->contract_type) == 'Permanent' ? 'selected' : '' }}>កិច្ចសន្យាអចិន្ត្រៃយ៍</option>
                            <option value="Fixed Term" {{ old('contract_type', $employee->contract_type) == 'Fixed Term' ? 'selected' : '' }}>កិច្ចសន្យាមានកំណត់</option>
                            <option value="Probation" {{ old('contract_type', $employee->contract_type) == 'Probation' ? 'selected' : '' }}>សាកល្បងការងារ</option>
                            <option value="Part-time" {{ old('contract_type', $employee->contract_type) == 'Part-time' ? 'selected' : '' }}>ក្រៅម៉ោង</option>
                            <option value="Internship" {{ old('contract_type', $employee->contract_type) == 'Internship' ? 'selected' : '' }}>កម្មសិក្សា</option>
                        </select>
                        @error('contract_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="supervisor_name" class="form-label-kh">អ្នកគ្រប់គ្រងផ្ទាល់</label>
                        <input type="text" name="supervisor_name" id="supervisor_name" class="form-control form-control-custom @error('supervisor_name') is-invalid @enderror" value="{{ old('supervisor_name', $employee->supervisor_name) }}" placeholder="ឈ្មោះអ្នកគ្រប់គ្រងផ្ទាល់">
                        @error('supervisor_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="work_location" class="form-label-kh">ទីតាំងបំពេញការងារ</label>
                        <input type="text" name="work_location" id="work_location" class="form-control form-control-custom @error('work_location') is-invalid @enderror" value="{{ old('work_location', $employee->work_location) }}" placeholder="ឧ. សាខាចម្បង, អគារ A, បន្ទប់រដ្ឋបាល...">
                        @error('work_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                </div>
                </div> {{-- /stepPane3 --}}

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 4: PAYROLL INFORMATION
                ═══════════════════════════════════════════════════════════ --}}
                <div class="wizard-step-pane" id="stepPane4" data-step="4" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">4</span>
                        <h4 class="step-title">៤. ព័ត៌មានប្រាក់ខែ</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-3">
                        <label for="basic_salary" class="form-label-kh">ប្រាក់ខែគោល ($) <span class="required-star">*</span></label>
                        <input type="number" step="0.01" min="0" name="basic_salary" id="basic_salary" class="form-control form-control-custom @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary', $employee->basic_salary) }}" placeholder="ឧ. 500.00">
                        @error('basic_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="salary_type" class="form-label-kh">ប្រភេទប្រាក់ខែ</label>
                        <select name="salary_type" id="salary_type" class="form-select form-select-custom @error('salary_type') is-invalid @enderror">
                            <option value="Monthly" {{ old('salary_type', $employee->salary_type ?? 'Monthly') == 'Monthly' ? 'selected' : '' }}>ប្រចាំខែ</option>
                            <option value="Daily" {{ old('salary_type', $employee->salary_type) == 'Daily' ? 'selected' : '' }}>ប្រចាំថ្ងៃ</option>
                            <option value="Hourly" {{ old('salary_type', $employee->salary_type) == 'Hourly' ? 'selected' : '' }}>ប្រចាំម៉ោង</option>
                        </select>
                        @error('salary_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="allowance" class="form-label-kh">ប្រាក់ឧបត្ថម្ភ ($)</label>
                        <input type="number" step="0.01" min="0" name="allowance" id="allowance" class="form-control form-control-custom @error('allowance') is-invalid @enderror" value="{{ old('allowance', $employee->allowance ?? 0) }}" placeholder="0.00">
                        @error('allowance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="payment_method" class="form-label-kh">វិធីសាស្ត្របើកប្រាក់ខែ</label>
                        <select name="payment_method" id="payment_method" class="form-select form-select-custom @error('payment_method') is-invalid @enderror">
                            <option value="Bank Transfer" {{ old('payment_method', $employee->payment_method ?? 'Bank Transfer') == 'Bank Transfer' ? 'selected' : '' }}>ផ្ទេរតាមធនាគារ</option>
                            <option value="Cash" {{ old('payment_method', $employee->payment_method) == 'Cash' ? 'selected' : '' }}>សាច់ប្រាក់ផ្ទាល់</option>
                            <option value="Cheque" {{ old('payment_method', $employee->payment_method) == 'Cheque' ? 'selected' : '' }}>មូលប្បទានប័ត្រ</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="bank_name" class="form-label-kh">ឈ្មោះធនាគារ</label>
                        <input type="text" name="bank_name" id="bank_name" list="bank_suggestions" class="form-control form-control-custom @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $employee->bank_name) }}" placeholder="ABA, ACLEDA, Canadia...">
                        <datalist id="bank_suggestions">
                            <option value="ABA Bank"></option>
                            <option value="ACLEDA Bank"></option>
                            <option value="Canadia Bank"></option>
                            <option value="Wing Bank"></option>
                            <option value="Sathapana Bank"></option>
                            <option value="J Trust Royal Bank"></option>
                            <option value="Vattanac Bank"></option>
                        </datalist>
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="bank_account_number" class="form-label-kh">លេខគណនីធនាគារ</label>
                        <input type="text" name="bank_account_number" id="bank_account_number" class="form-control form-control-custom font-monospace @error('bank_account_number') is-invalid @enderror" value="{{ old('bank_account_number', $employee->bank_account_number) }}" placeholder="000 123 456">
                        @error('bank_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="salary_effective_date" class="form-label-kh">កាលបរិច្ឆេទមានប្រសិទ្ធភាព</label>
                        <input type="date" name="salary_effective_date" id="salary_effective_date" class="form-control form-control-custom @error('salary_effective_date') is-invalid @enderror" value="{{ old('salary_effective_date', $employee->salary_effective_date ? $employee->salary_effective_date->format('Y-m-d') : '') }}">
                        @error('salary_effective_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                </div>
                </div> {{-- /stepPane4 --}}

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 5: EDUCATION & SKILLS
                ═══════════════════════════════════════════════════════════ --}}
                <div class="wizard-step-pane" id="stepPane5" data-step="5" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">5</span>
                        <h4 class="step-title">៥. ព័ត៌មានការសិក្សា និងជំនាញ</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="highest_qualification" class="form-label-kh">កម្រិតវប្បធម៌ខ្ពស់បំផុត</label>
                        <select name="highest_qualification" id="highest_qualification" class="form-select form-select-custom @error('highest_qualification') is-invalid @enderror">
                            <option value="">ជ្រើសរើសកម្រិតវប្បធម៌</option>
                            <option value="Doctorate" {{ old('highest_qualification', $employee->highest_qualification) == 'Doctorate' ? 'selected' : '' }}>បណ្ឌិត</option>
                            <option value="Master" {{ old('highest_qualification', $employee->highest_qualification) == 'Master' ? 'selected' : '' }}>អនុបណ្ឌិត</option>
                            <option value="Bachelor" {{ old('highest_qualification', $employee->highest_qualification) == 'Bachelor' ? 'selected' : '' }}>បរិញ្ញាបត្រ</option>
                            <option value="Associate" {{ old('highest_qualification', $employee->highest_qualification) == 'Associate' ? 'selected' : '' }}>បរិញ្ញាបត្ររង</option>
                            <option value="High School" {{ old('highest_qualification', $employee->highest_qualification) == 'High School' ? 'selected' : '' }}>មធ្យមសិក្សាទុតិយភូមិ (បាក់ឌុប)</option>
                            <option value="Other" {{ old('highest_qualification', $employee->highest_qualification) == 'Other' ? 'selected' : '' }}>ផ្សេងៗ</option>
                        </select>
                        @error('highest_qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="university" class="form-label-kh">សាកលវិទ្យាល័យ / សាលារៀន</label>
                        <input type="text" name="university" id="university" class="form-control form-control-custom @error('university') is-invalid @enderror" value="{{ old('university', $employee->university) }}" placeholder="ឈ្មោះគ្រឹះស្ថានសិក្សា / សាកលវិទ្យាល័យ">
                        @error('university')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="graduation_year" class="form-label-kh">ឆ្នាំបញ្ចប់ការសិក្សា</label>
                        <input type="text" name="graduation_year" id="graduation_year" class="form-control form-control-custom @error('graduation_year') is-invalid @enderror" value="{{ old('graduation_year', $employee->graduation_year) }}" placeholder="ឧ. 2022">
                        @error('graduation_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="major" class="form-label-kh">ជំនាញឯកទេស / មុខវិជ្ជា</label>
                        <input type="text" name="major" id="major" class="form-control form-control-custom @error('major') is-invalid @enderror" value="{{ old('major', $employee->major) }}" placeholder="ឧ. អក្សរសាស្ត្រខ្មែរ, គណិតវិទ្យា, ព័ត៌មានវិទ្យា...">
                        @error('major')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="teaching_qualification" class="form-label-kh">គរុកោសល្យ / គរុវិជ្ជា</label>
                        <input type="text" name="teaching_qualification" id="teaching_qualification" list="teaching_qual_list" class="form-control form-control-custom @error('teaching_qualification') is-invalid @enderror" value="{{ old('teaching_qualification', $employee->teaching_qualification) }}" placeholder="ឧ. គរុកោសល្យជាន់ខ្ពស់ NIE, មូលដ្ឋាន...">
                        <datalist id="teaching_qual_list">
                            <option value="វិទ្យាស្ថានជាតិអប់រំ (NIE - ឧត្តម)"></option>
                            <option value="គរុកោសល្យភូមិភាគ (RTTC - មូលដ្ឋាន)"></option>
                            <option value="គរុកោសល្យខេត្ត (PTTC - បឋម)"></option>
                            <option value="គ្មាន (មិនមាន)"></option>
                        </datalist>
                        @error('teaching_qualification')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="professional_certificate" class="form-label-kh">វិញ្ញាបនបត្រវិជ្ជាជីវៈ</label>
                        <textarea name="professional_certificate" id="professional_certificate" rows="2" class="form-control form-control-custom @error('professional_certificate') is-invalid @enderror" placeholder="វិញ្ញាបនបត្របណ្តុះបណ្តាលវិជ្ជាជីវៈផ្សេងៗ...">{{ old('professional_certificate', $employee->professional_certificate) }}</textarea>
                        @error('professional_certificate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="skills" class="form-label-kh">ជំនាញ និងសមត្ថភាព</label>
                        <textarea name="skills" id="skills" rows="2" class="form-control form-control-custom @error('skills') is-invalid @enderror" placeholder="ជំនាញកុំព្យូទ័រ, ភាសាបរទេស, ភាពជាអ្នកដឹកនាំ...">{{ old('skills', $employee->skills) }}</textarea>
                        @error('skills')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="experience_years" class="form-label-kh">បទពិសោធន៍ការងារ (ឆ្នាំ)</label>
                        <input type="text" name="experience_years" id="experience_years" class="form-control form-control-custom @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $employee->experience_years) }}" placeholder="ឧ. បទពិសោធន៍បង្រៀន ៥ ឆ្នាំ, រដ្ឋបាល ៣ ឆ្នាំ...">
                        @error('experience_years')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                </div>
                </div> {{-- /stepPane5 --}}

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 6: EMERGENCY CONTACT
                ═══════════════════════════════════════════════════════════ --}}
                <div class="wizard-step-pane" id="stepPane6" data-step="6" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">6</span>
                        <h4 class="step-title">៦. ទំនាក់ទំនងបន្ទាន់</h4>
                    </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="emergency_contact_name" class="form-label-kh">ឈ្មោះអ្នកទំនាក់ទំនង <span class="required-star">*</span></label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control form-control-custom @error('emergency_contact_name') is-invalid @enderror" value="{{ old('emergency_contact_name', $contact->emergency_contact_name ?? '') }}" placeholder="ឈ្មោះអ្នកត្រូវទាក់ទង">
                        @error('emergency_contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="emergency_relationship" class="form-label-kh">ត្រូវជា / ទំនាក់ទំនង</label>
                        <select name="emergency_relationship" id="emergency_relationship" class="form-select form-select-custom @error('emergency_relationship') is-invalid @enderror">
                            <option value="">ជ្រើសរើសទំនាក់ទំនង</option>
                            <option value="Parent" {{ old('emergency_relationship', $contact->emergency_relationship ?? '') == 'Parent' ? 'selected' : '' }}>ឪពុក / ម្តាយ</option>
                            <option value="Spouse" {{ old('emergency_relationship', $contact->emergency_relationship ?? '') == 'Spouse' ? 'selected' : '' }}>ប្តី / ប្រពន្ធ</option>
                            <option value="Sibling" {{ old('emergency_relationship', $contact->emergency_relationship ?? '') == 'Sibling' ? 'selected' : '' }}>បងប្អូន</option>
                            <option value="Child" {{ old('emergency_relationship', $contact->emergency_relationship ?? '') == 'Child' ? 'selected' : '' }}>កូន</option>
                            <option value="Relative" {{ old('emergency_relationship', $contact->emergency_relationship ?? '') == 'Relative' ? 'selected' : '' }}>សាច់ញាតិ</option>
                            <option value="Friend" {{ old('emergency_relationship', $contact->emergency_relationship ?? '') == 'Friend' ? 'selected' : '' }}>មិត្តភក្តិ</option>
                            <option value="Other" {{ old('emergency_relationship', $contact->emergency_relationship ?? '') == 'Other' ? 'selected' : '' }}>ផ្សេងៗ</option>
                        </select>
                        @error('emergency_relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="emergency_phone" class="form-label-kh">លេខទូរស័ព្ទបន្ទាន់ <span class="required-star">*</span></label>
                        <input type="text" name="emergency_phone" id="emergency_phone" class="form-control form-control-custom @error('emergency_phone') is-invalid @enderror" value="{{ old('emergency_phone', $contact->emergency_phone ?? '') }}" placeholder="012 345 678">
                        @error('emergency_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="emergency_address" class="form-label-kh">អាសយដ្ឋានអ្នកទំនាក់ទំនង</label>
                        <input type="text" name="emergency_address" id="emergency_address" class="form-control form-control-custom @error('emergency_address') is-invalid @enderror" value="{{ old('emergency_address', $contact->emergency_address ?? '') }}" placeholder="អាសយដ្ឋានអ្នកទំនាក់ទំនងបន្ទាន់...">
                        @error('emergency_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                </div>
                </div> {{-- /stepPane6 --}}

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 7: SYSTEM ACCOUNT (គណនីប្រព័ន្ធ)
                ═══════════════════════════════════════════════════════════ --}}
                <div class="wizard-step-pane" id="stepPane7" data-step="7" style="display: none;">
                <div class="form-section-block">
                    <div class="step-header">
                        <span class="step-badge">7</span>
                        <h4 class="step-title">៧. គណនីប្រើប្រាស់ប្រព័ន្ធ</h4>
                        <span class="step-subtitle text-muted small">(កែប្រែព័ត៌មានគណនីចូលប្រព័ន្ធ)</span>
                    </div>

                    @if($employee->user)
                        <div class="alert alert-light border d-flex align-items-center gap-2 mb-3 py-2 px-3" style="border-radius: 6px;">
                            <i class="bi bi-shield-check text-success fs-5"></i>
                            <div class="small">
                                បុគ្គលិកនេះមានគណនីចូលប្រព័ន្ធរួចហើយ (<strong class="text-primary">{{ $employee->user->email }}</strong>)។ លោកអ្នកអាចកែប្រែឈ្មោះ និងអ៊ីមែលខាងក្រោមបាន៖
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="user_name" class="form-label-kh">ឈ្មោះគណនី <span class="required-star">*</span></label>
                                <input type="text" name="user_name" id="user_name" class="form-control form-control-custom @error('user_name') is-invalid @enderror" value="{{ old('user_name', $employee->user->name ?? '') }}" placeholder="ឈ្មោះគណនី" oninput="updateLivePreview()">
                                @error('user_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="user_email" class="form-label-kh">អ៊ីមែលគណនី <span class="required-star">*</span></label>
                                <input type="email" name="user_email" id="user_email" class="form-control form-control-custom @error('user_email') is-invalid @enderror" value="{{ old('user_email', $employee->user->email ?? '') }}" placeholder="user@school.edu.kh" oninput="updateLivePreview()">
                                @error('user_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mt-2">
                                <span class="fw-semibold text-dark small me-2">តួនាទីបច្ចុប្បន្ន (Current Role)៖</span>
                                @forelse($employee->user->roles as $role)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                        <i class="bi bi-shield-lock me-1"></i>{{ $role->display_name ?? $role->name }}
                                    </span>
                                @empty
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">មិនទាន់មានតួនាទី</span>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border d-flex align-items-center gap-2 mb-3 py-2.5 px-3" style="border-radius: 6px;">
                            <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                            <div>
                                <strong>មិនមានគណនីប្រព័ន្ធ៖</strong> បុគ្គលិកនេះមិនទាន់មានគណនីសម្រាប់ចូលប្រើប្រាស់ប្រព័ន្ធឡើយ (ជាបុគ្គលិកទូទៅ)។
                            </div>
                        </div>
                    @endif
                </div>
                </div> {{-- /stepPane7 --}}

                {{-- WIZARD NAVIGATION / ACTIONS --}}
                <div class="d-flex justify-content-between align-items-center gap-2 pt-3 mt-4 border-top">
                    <div class="d-flex align-items-center gap-1.5">
                        <button type="button" class="btn btn-outline-secondary px-3 py-1.5 fw-medium align-items-center gap-1 shadow-xs" id="btnPrevStep" onclick="navigateStep(-1)" style="border-radius: 6px; font-size: 0.82rem; display: none !important;">
                            <i class="bi bi-arrow-left"></i> ថយក្រោយ
                        </button>
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-light text-secondary border border-secondary-subtle px-3 py-1.5 fw-medium align-items-center gap-1 shadow-xs" id="btnCancelStep" style="border-radius: 6px; font-size: 0.82rem; display: inline-flex !important;">
                            <i class="bi bi-x-lg"></i> បោះបង់
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" class="btn text-white px-3.5 py-1.5 fw-medium align-items-center gap-1.5 shadow-sm" id="btnSaveAnytime" style="background: #e8a838; border: none; border-radius: 6px; font-size: 0.82rem; display: inline-flex !important;">
                            <i class="bi bi-file-earmark-check"></i> រក្សាទុកទិន្នន័យ
                        </button>
                        <button type="button" class="btn text-white px-3.5 py-1.5 fw-medium align-items-center gap-1.5 shadow-sm" id="btnNextStep" onclick="navigateStep(1)" style="background: #2563eb; border: none; border-radius: 6px; font-size: 0.82rem; display: inline-flex !important;">
                            បន្ទាប់ <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </div> {{-- END UNIFIED FORM CARD --}}
        </div>

        {{-- RIGHT COLUMN: LIVE PREVIEW --}}
        <div class="col-xl-4 col-lg-4">
            <div class="preview-card">
                <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 28px; height: 28px; background: #e8a838;">
                        <i class="bi bi-person-fill fs-6"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.86rem;">មើលជាមុន</h6>
                        <small class="text-muted" style="font-size: 0.72rem;">(កាតព័ត៌មានសង្ខេប)</small>
                    </div>
                </div>

                {{-- AVATAR --}}
                <div class="text-center my-2">
                    <div class="preview-avatar-wrap mx-auto" id="previewAvatarContainer">
                        <i class="bi bi-person-fill text-white {{ $hasPhoto ? 'd-none' : '' }}" style="font-size: 2.6rem;" id="previewAvatarIcon"></i>
                        <img id="previewAvatarImg" src="{{ $employee->photo_url ?? '' }}" alt="Avatar" class="{{ $hasPhoto ? '' : 'd-none' }} w-100 h-100" style="object-fit: cover; object-position: center top;">
                    </div>
                </div>

                {{-- DETAILS LIST --}}
                <div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-person text-secondary"></i> ឈ្មោះ</span>
                        <span class="preview-val fw-bold text-dark" id="previewName">{{ $staffName }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-person-vcard text-secondary"></i> អត្តលេខបុគ្គលិក</span>
                        <span class="preview-val font-monospace" id="previewCode">{{ $employee->employee_code }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-briefcase text-secondary"></i> មុខតំណែង</span>
                        <span class="preview-val" id="previewPosition">{{ $employee->employee_position->name ?? '—' }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-building text-secondary"></i> ផ្នែក</span>
                        <span class="preview-val" id="previewDepartment">{{ $employee->employee_department->name ?? '—' }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-shield-check text-secondary"></i> ស្ថានភាព</span>
                        <span class="badge bg-success text-white px-2 py-1" id="previewStatus">{{ $employee->employee_status->khmer_name ?? 'សកម្ម' }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-envelope text-secondary"></i> អ៊ីមែល</span>
                        <span class="preview-val" id="previewEmail">{{ $employee->user->email ?? '—' }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-telephone text-secondary"></i> ទូរស័ព្ទ</span>
                        <span class="preview-val" id="previewPhone">{{ $contact->phone ?? '—' }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-calendar-check text-secondary"></i> ថ្ងៃចូលធ្វើការ</span>
                        <span class="preview-val" id="previewJoinDate">{{ $employee->date_of_joining ? $employee->date_of_joining->format('d/m/Y') : '—' }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-gender-ambiguous text-secondary"></i> ភេទ</span>
                        <span class="preview-val" id="previewGender">{{ $employee->gender ?? '—' }}</span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label"><i class="bi bi-currency-dollar text-secondary"></i> ប្រាក់ខែ</span>
                        <span class="preview-val text-success fw-bold" id="previewSalary">{{ $employee->basic_salary ? '$' . number_format($employee->basic_salary, 2) : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@endsection

@section('scripts')
<script>
    function previewEmployeePhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxMb = 20;
            if (file.size > maxMb * 1024 * 1024) {
                alert(`ទំហំរូបថតធំពេក (${(file.size / (1024 * 1024)).toFixed(1)}MB)។ សូមជ្រើសរើសរូបថតដែលមានទំហំមិនលើសពី ${maxMb}MB`);
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const liveImg = document.getElementById('previewAvatarImg');
                const liveIcon = document.getElementById('previewAvatarIcon');
                liveImg.src = e.target.result;
                liveImg.classList.remove('d-none');
                liveIcon.classList.add('d-none');

                const thumbImg = document.getElementById('thumbPhotoPreview');
                const thumbIcon = document.getElementById('thumbPhotoPlaceholder');
                thumbImg.src = e.target.result;
                thumbImg.classList.remove('d-none');
                thumbIcon.classList.add('d-none');

                const btnRemove = document.getElementById('btnRemovePhoto');
                if (btnRemove) btnRemove.classList.remove('d-none');

                const actionLabel = document.getElementById('photoActionLabel');
                if (actionLabel) actionLabel.textContent = 'ប្តូររូប';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeSelectedPhoto() {
        const input = document.getElementById('photo_input');
        if (input) input.value = '';

        const thumbImg = document.getElementById('thumbPhotoPreview');
        const thumbIcon = document.getElementById('thumbPhotoPlaceholder');
        if (thumbImg) {
            thumbImg.src = '';
            thumbImg.classList.add('d-none');
        }
        if (thumbIcon) thumbIcon.classList.remove('d-none');

        const btnRemove = document.getElementById('btnRemovePhoto');
        if (btnRemove) btnRemove.classList.add('d-none');

        const actionLabel = document.getElementById('photoActionLabel');
        if (actionLabel) actionLabel.textContent = 'ជ្រើសរូប';

        const liveImg = document.getElementById('previewAvatarImg');
        const liveIcon = document.getElementById('previewAvatarIcon');
        if (liveImg) {
            liveImg.src = '';
            liveImg.classList.add('d-none');
        }
        if (liveIcon) liveIcon.classList.remove('d-none');
    }

    function updateLivePreview() {
        const lastNameKh = document.getElementById('last_name_khmer')?.value?.trim() || '';
        const firstNameKh = document.getElementById('first_name_khmer')?.value?.trim() || '';
        const fullNameKh = (lastNameKh + ' ' + firstNameKh).trim();

        const lastNameLat = document.getElementById('last_name')?.value?.trim() || '';
        const firstNameLat = document.getElementById('first_name')?.value?.trim() || '';
        const fullNameLat = (lastNameLat + ' ' + firstNameLat).trim();

        let name = fullNameKh || fullNameLat || document.getElementById('user_name')?.value?.trim() || '—';
        let email = document.getElementById('user_email')?.value?.trim() || '—';

        document.getElementById('previewName').textContent = name;
        document.getElementById('previewEmail').textContent = email;

        // Position
        const posSelect = document.getElementById('employee_position_id');
        const posText = posSelect?.selectedIndex > 0 ? posSelect.options[posSelect.selectedIndex].text : '—';
        document.getElementById('previewPosition').textContent = posText;

        // Department
        const deptSelect = document.getElementById('employee_department_id');
        const deptText = deptSelect?.selectedIndex > 0 ? deptSelect.options[deptSelect.selectedIndex].text : '—';
        document.getElementById('previewDepartment').textContent = deptText;

        // Status
        const statSelect = document.getElementById('employee_status_id');
        const statBadge = document.getElementById('previewStatus');
        if (statSelect && statSelect.selectedIndex > 0) {
            statBadge.textContent = statSelect.options[statSelect.selectedIndex].text;
        }

        // Phone
        const phone = document.getElementById('phone')?.value?.trim();
        document.getElementById('previewPhone').textContent = phone ? phone : '—';

        // Gender
        const genderSelect = document.getElementById('gender');
        let genderText = '—';
        if (genderSelect && genderSelect.value) {
            genderText = genderSelect.options[genderSelect.selectedIndex].text;
        }
        document.getElementById('previewGender').textContent = genderText;

        // Salary
        const sal = document.getElementById('basic_salary')?.value;
        document.getElementById('previewSalary').textContent = sal ? '$' + parseFloat(sal).toFixed(2) : '—';

        // Joining date
        const joinDate = document.getElementById('date_of_joining')?.value;
        if (joinDate) {
            const parts = joinDate.split('-');
            if (parts.length === 3) {
                document.getElementById('previewJoinDate').textContent = `${parts[2]}/${parts[1]}/${parts[0]}`;
            } else {
                document.getElementById('previewJoinDate').textContent = joinDate;
            }
        }
    }

    /* WIZARD LOGIC */
    let currentWizardStep = 1;
    const totalWizardSteps = 7;

    function showStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > totalWizardSteps) return;
        currentWizardStep = stepNumber;

        // Toggle panes
        for (let i = 1; i <= totalWizardSteps; i++) {
            const pane = document.getElementById('stepPane' + i);
            if (pane) {
                if (i === currentWizardStep) {
                    pane.classList.add('active');
                    pane.style.display = 'block';
                } else {
                    pane.classList.remove('active');
                    pane.style.display = 'none';
                }
            }
        }

        // Toggle tabs
        document.querySelectorAll('.wizard-step-tab').forEach(tab => {
            const step = parseInt(tab.getAttribute('data-step'));
            tab.classList.remove('active', 'completed');
            if (step === currentWizardStep) {
                tab.classList.add('active');
            } else if (step < currentWizardStep) {
                tab.classList.add('completed');
            }
        });

        // Update progress bar
        const pct = Math.round((currentWizardStep / totalWizardSteps) * 100);
        const pBar = document.getElementById('wizardProgressBar');
        if (pBar) pBar.style.width = pct + '%';

        // Update button states
        const btnPrev = document.getElementById('btnPrevStep');
        const btnNext = document.getElementById('btnNextStep');
        const btnSave = document.getElementById('btnSaveAnytime');
        const btnCancel = document.getElementById('btnCancelStep');

        function setBtnDisplay(el, isVisible) {
            if (!el) return;
            if (isVisible) {
                el.style.setProperty('display', 'inline-flex', 'important');
            } else {
                el.style.setProperty('display', 'none', 'important');
            }
        }

        setBtnDisplay(btnPrev, currentWizardStep > 1);
        setBtnDisplay(btnCancel, true);
        setBtnDisplay(btnNext, currentWizardStep < totalWizardSteps);
        setBtnDisplay(btnSave, true); // Always visible on every step in edit form

        // Smooth scroll to top of form card
        const formCard = document.querySelector('.unified-form-card');
        if (formCard && window.scrollY > formCard.offsetTop) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function validateStep(stepNumber) {
        let isValid = true;
        let firstInvalidEl = null;

        function markInvalid(inputEl, message) {
            isValid = false;
            inputEl.classList.add('is-invalid');
            
            let parent = inputEl.closest('.input-group') || inputEl.parentElement;
            let feedback = parent.querySelector('.step-error-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block step-error-feedback';
                parent.appendChild(feedback);
            }
            feedback.textContent = message;

            if (!firstInvalidEl) {
                firstInvalidEl = inputEl;
            }

            const clearHandler = function() {
                inputEl.classList.remove('is-invalid');
                if (feedback) feedback.remove();
                inputEl.removeEventListener('input', clearHandler);
                inputEl.removeEventListener('change', clearHandler);
            };
            inputEl.addEventListener('input', clearHandler);
            inputEl.addEventListener('change', clearHandler);
        }

        if (stepNumber === 1) {
            const lastNameKh = document.getElementById('last_name_khmer');
            if (lastNameKh && !lastNameKh.value.trim()) {
                markInvalid(lastNameKh, 'សូមបញ្ចូលគោត្តនាម (ខ្មែរ)');
            }

            const firstNameKh = document.getElementById('first_name_khmer');
            if (firstNameKh && !firstNameKh.value.trim()) {
                markInvalid(firstNameKh, 'សូមបញ្ចូលនាម (ខ្មែរ)');
            }

            const genderEl = document.getElementById('gender');
            if (genderEl && !genderEl.value) {
                markInvalid(genderEl, 'សូមជ្រើសរើសភេទ');
            }

            const dobEl = document.getElementById('date_of_birth');
            if (dobEl && !dobEl.value) {
                markInvalid(dobEl, 'សូមជ្រើសរើសថ្ងៃខែឆ្នាំកំណើត');
            }

            const phoneEl = document.getElementById('phone');
            if (phoneEl && !phoneEl.value.trim()) {
                markInvalid(phoneEl, 'សូមបញ្ចូលលេខទូរស័ព្ទ');
            }
        } else if (stepNumber === 3) {
            const posEl = document.getElementById('employee_position_id');
            if (posEl && !posEl.value) {
                markInvalid(posEl, 'សូមជ្រើសរើសមុខតំណែង');
            }
            const statEl = document.getElementById('employee_status_id');
            if (statEl && !statEl.value) {
                markInvalid(statEl, 'សូមជ្រើសរើសស្ថានភាពការងារ');
            }
            const joinEl = document.getElementById('date_of_joining');
            if (joinEl && !joinEl.value) {
                markInvalid(joinEl, 'សូមជ្រើសរើសថ្ងៃចូលបម្រើការងារ');
            }
        } else if (stepNumber === 7) {
            const userNameEl = document.getElementById('user_name');
            if (userNameEl && !userNameEl.value.trim()) {
                markInvalid(userNameEl, 'សូមបញ្ចូលឈ្មោះគណនី');
            }

            const userEmailEl = document.getElementById('user_email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (userEmailEl && (!userEmailEl.value.trim() || !emailRegex.test(userEmailEl.value.trim()))) {
                markInvalid(userEmailEl, 'សូមបញ្ចូលអ៊ីមែលឱ្យបានត្រឹមត្រូវ');
            }
        }

        if (!isValid && firstInvalidEl) {
            firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidEl.focus();
        }

        return isValid;
    }

    function navigateStep(direction) {
        showStep(currentWizardStep + direction);
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateLivePreview();

        // If validation errors exist, automatically jump to the step containing the first error
        const firstInvalid = document.querySelector('.wizard-step-pane .is-invalid');
        if (firstInvalid) {
            const pane = firstInvalid.closest('.wizard-step-pane');
            if (pane && pane.getAttribute('data-step')) {
                showStep(parseInt(pane.getAttribute('data-step')));
                firstInvalid.focus();
                return;
            }
        }
        showStep(1);
    });
</script>
@include('students.partials.cambodia_districts_js')
@endsection
