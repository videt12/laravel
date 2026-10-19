@extends('layouts.app')

@section('title', 'បន្ថែមរចនាសម្ព័ន្ធកម្រៃ — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item"><a href="{{ route('fee-structures.index') }}" class="text-decoration-none text-muted">រចនាសម្ព័ន្ធកម្រៃសេវា</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បន្ថែមរចនាសម្ព័ន្ធកម្រៃ</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* Page Banner (Gold/Yellow Theme) */
.page-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: 8px;
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.85rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(232, 168, 56, 0.18);
}
.page-banner::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 120px; height: 120px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    pointer-events: none;
}
.page-banner::after {
    content: '';
    position: absolute;
    bottom: -25px; right: 70px;
    width: 80px; height: 80px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
    pointer-events: none;
}
.page-banner .banner-icon {
    width: 36px; height: 36px;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff;
    flex-shrink: 0;
}
.page-banner h1 {
    font-size: 1.12rem; font-weight: 700;
    color: #fff; margin: 0 0 .15rem;
    line-height: 1.25;
}
.page-banner p {
    font-size: .78rem; color: rgba(255, 255, 255, 0.92);
    margin: 0; line-height: 1.3;
}
.page-banner .btn-banner-back {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.35);
    font-size: .78rem;
    border-radius: 6px;
    padding: 0.32rem 0.75rem;
    transition: all .15s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.page-banner .btn-banner-back:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #fff;
}

/* Compact Form Card */
.form-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: 3px solid #e8a838;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    overflow: hidden;
}
.form-card .card-header {
    background: #fcfcfc;
    border-bottom: 1px solid #f1f5f9;
    padding: 0.65rem 1rem;
}
.form-card .card-body {
    padding: 1rem 1.15rem;
}
.form-label-custom {
    font-size: 0.82rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.3rem;
}
.form-control-custom, .form-select-custom {
    font-size: 0.83rem;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    padding: 0.42rem 0.75rem;
    transition: border-color .15s ease, box-shadow .15s ease;
}
.form-control-custom:focus, .form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 3px rgba(232, 168, 56, 0.18);
    outline: none;
}
.btn-gold {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(232, 168, 56, 0.25);
}
.btn-gold:hover {
    background: linear-gradient(135deg, #df9b27 0%, #b87b14 100%);
    color: #fff;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    {{-- Banner --}}
    <div class="page-banner">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2.5">
                <div class="banner-icon"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <h1>បន្ថែមរចនាសម្ព័ន្ធកម្រៃ</h1>
                    <p>កំណត់ចំនួនទឹកប្រាក់កម្រៃតាមឆ្នាំសិក្សា និងវិសាលភាពថ្នាក់</p>
                </div>
            </div>
            <div>
                <a href="{{ route('fee-structures.index') }}" class="btn-banner-back">
                    <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅកាន់រចនាសម្ព័ន្ធកម្រៃ
                </a>
            </div>
        </div>
    </div>

    {{-- Form Content - Centered and Compact --}}
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
            <div class="form-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <span class="d-flex align-items-center justify-content-center rounded-2" style="width:26px;height:26px;background:rgba(232,168,56,0.15);color:#c98b1e;font-size:0.85rem;">
                        <i class="bi bi-plus-circle-fill"></i>
                    </span>
                    <span class="fw-bold text-dark" style="font-size:0.88rem;">ព័ត៌មានរចនាសម្ព័ន្ធកម្រៃ</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('fee-structures.store') }}" method="POST">
                        @csrf

                        <div class="mb-2.5">
                            <label for="fee_category_id" class="form-label form-label-custom">ប្រភេទកម្រៃសេវា <span class="text-danger">*</span></label>
                            <select name="fee_category_id" id="fee_category_id" class="form-select form-select-custom @error('fee_category_id') is-invalid @enderror" required autofocus>
                                <option value="">ជ្រើសរើសប្រភេទកម្រៃ...</option>
                                @foreach($feeCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('fee_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('fee_category_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2.5">
                            <label for="academic_year_id" class="form-label form-label-custom">ឆ្នាំសិក្សា <span class="text-danger">*</span></label>
                            <select name="academic_year_id" id="academic_year_id" class="form-select form-select-custom @error('academic_year_id') is-invalid @enderror" required>
                                <option value="">ជ្រើសរើសឆ្នាំសិក្សា...</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }} {{ $year->is_active ? '(សកម្ម)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2.5">
                            <label for="class_id" class="form-label form-label-custom">វិសាលភាព / ថ្នាក់</label>
                            <select name="class_id" id="class_id" class="form-select form-select-custom @error('class_id') is-invalid @enderror">
                                <option value="">ទូទាំងសាលា (អនុវត្តចំពោះគ្រប់ថ្នាក់ទាំងអស់)</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                        ថ្នាក់ជាក់លាក់៖ {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text" style="font-size:0.75rem;color:#64748b;">ទុកជា "ទូទាំងសាលា" សម្រាប់ការគិតថ្លៃរួមទូទាំងសាលា (ឧ. ថ្លៃចុះឈ្មោះ)។</div>
                            @error('class_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label form-label-custom">ចំនួនទឹកប្រាក់ ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0" style="font-size:0.83rem;">$</span>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="form-control form-control-custom border-start-0 @error('amount') is-invalid @enderror" placeholder="0.00" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback d-block small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('fee-structures.index') }}" class="btn btn-sm btn-light border px-3" style="font-size:0.82rem;border-radius:6px;">បោះបង់</a>
                            <button type="submit" class="btn btn-sm btn-gold px-3.5" style="font-size:0.82rem;border-radius:6px;">
                                <i class="bi bi-check-lg me-1"></i>បង្កើតរចនាសម្ព័ន្ធកម្រៃ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
