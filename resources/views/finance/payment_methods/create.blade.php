@extends('layouts.app')

@section('title', 'បន្ថែមវិធីសាស្ត្របង់ប្រាក់ — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item"><a href="{{ route('payment-methods.index') }}" class="text-decoration-none text-muted">វិធីសាស្ត្របង់ប្រាក់</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បន្ថែមវិធីសាស្ត្របង់ប្រាក់</li>
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
.form-control-custom {
    font-size: 0.83rem;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    padding: 0.42rem 0.75rem;
    transition: border-color .15s ease, box-shadow .15s ease;
}
.form-control-custom:focus {
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
                <div class="banner-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <h1>បន្ថែមវិធីសាស្ត្របង់ប្រាក់</h1>
                    <p>កំណត់បណ្តាញទូទាត់ប្រាក់ថ្មីសម្រាប់ការប្រមូលកម្រៃសេវា</p>
                </div>
            </div>
            <div>
                <a href="{{ route('payment-methods.index') }}" class="btn-banner-back">
                    <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅកាន់វិធីសាស្ត្របង់ប្រាក់
                </a>
            </div>
        </div>
    </div>

    {{-- Form Content - Centered and Compact --}}
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="form-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <span class="d-flex align-items-center justify-content-center rounded-2" style="width:26px;height:26px;background:rgba(232,168,56,0.15);color:#c98b1e;font-size:0.85rem;">
                        <i class="bi bi-plus-circle-fill"></i>
                    </span>
                    <span class="fw-bold text-dark" style="font-size:0.88rem;">ព័ត៌មានវិធីសាស្ត្របង់ប្រាក់</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('payment-methods.store') }}" method="POST">
                        @csrf

                        <div class="mb-2.5">
                            <label for="name" class="form-label form-label-custom">ឈ្មោះវិធីសាស្ត្រ <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control form-control-custom @error('name') is-invalid @enderror" placeholder="ឧ. សាច់ប្រាក់, ផ្ទេរតាមធនាគារ, Visa/Mastercard, ABA Pay" required autofocus>
                            @error('name')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label form-label-custom">ការពិពណ៌នា / ព័ត៌មានលម្អិតគណនី</label>
                            <textarea name="description" id="description" rows="3" class="form-control form-control-custom @error('description') is-invalid @enderror" placeholder="សេចក្តីណែនាំអំពីការបង់ប្រាក់ ឬព័ត៌មានគណនីធនាគារ (ស្រេចចិត្ត)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('payment-methods.index') }}" class="btn btn-sm btn-light border px-3" style="font-size:0.82rem;border-radius:6px;">បោះបង់</a>
                            <button type="submit" class="btn btn-sm btn-gold px-3.5" style="font-size:0.82rem;border-radius:6px;">
                                <i class="bi bi-check-lg me-1"></i>បង្កើតវិធីសាស្ត្រ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
