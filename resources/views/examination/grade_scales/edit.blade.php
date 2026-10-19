@extends('layouts.app')

@section('title', 'កែសម្រួលកម្រិតនិទ្ទេស')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales']) }}" class="text-decoration-none text-muted">កម្រិតនិទ្ទេស</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">កែសម្រួល</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0" style="max-width: 680px;">
    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">កែសម្រួលកម្រិតនិទ្ទេស</h2>
            <p class="text-muted small mb-0 mt-0.5" style="font-size: 0.8rem;">កែប្រែចន្លោះភាគរយ និងនិទ្ទេសប្រឡង</p>
        </div>
        <a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
            <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>

    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('grade-scales.update', $gradeScale->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-2.5 mb-2.5">
                    <div class="col-12 col-md-8">
                        <label for="name" class="form-label small fw-semibold text-dark mb-1">
                            ឈ្មោះកម្រិត <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control form-control-sm @error('name') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('name', $gradeScale->name) }}"
                               placeholder="ឧ. ល្អប្រសើរ, ល្អណាស់, មធ្យម"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="grade" class="form-label small fw-semibold text-dark mb-1">
                            និទ្ទេស <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="grade"
                               id="grade"
                               class="form-control form-control-sm @error('grade') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('grade', $gradeScale->grade) }}"
                               placeholder="ឧ. A+, B, C"
                               required>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-2.5 mb-3">
                    <div class="col-12 col-md-4">
                        <label for="min_percentage" class="form-label small fw-semibold text-dark mb-1">
                            ភាគរយអប្បបរមា (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               max="100"
                               name="min_percentage"
                               id="min_percentage"
                               class="form-control form-control-sm @error('min_percentage') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('min_percentage', $gradeScale->min_percentage) }}"
                               required>
                        @error('min_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="max_percentage" class="form-label small fw-semibold text-dark mb-1">
                            ភាគរយអតិបរមា (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               max="100"
                               name="max_percentage"
                               id="max_percentage"
                               class="form-control form-control-sm @error('max_percentage') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('max_percentage', $gradeScale->max_percentage) }}"
                               required>
                        @error('max_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="gpa_point" class="form-label small fw-semibold text-dark mb-1">
                            ពិន្ទុ GPA
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               max="10"
                               name="gpa_point"
                               id="gpa_point"
                               class="form-control form-control-sm @error('gpa_point') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('gpa_point', $gradeScale->gpa_point) }}">
                        @error('gpa_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'grade-scales']) }}" class="btn btn-sm btn-outline-secondary" style="height: 32px; font-size: 0.82rem; border-radius: 6px;">
                        បោះបង់
                    </a>
                    <button type="submit" class="btn btn-sm text-white fw-semibold px-3 d-inline-flex align-items-center gap-1 shadow-sm" style="height: 32px; font-size: 0.82rem; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px;">
                        <i class="bi bi-check-lg"></i><span>ធ្វើបច្ចុប្បន្នភាព</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
