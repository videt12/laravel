@extends('layouts.app')

@section('title', 'បង្កើតកិច្ចការ — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}" class="text-decoration-none text-muted">កិច្ចការ</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">បង្កើតថ្មី</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">បង្កើតកិច្ចការ</h1>
        <p class="page-subtitle">បង្កើតកិច្ចការថ្មីសម្រាប់សិស្ស។</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <h5 class="mb-0 fw-semibold text-dark">ព័ត៌មានលម្អិតនៃកិច្ចការ</h5>
            </div>
            
            <form action="{{ route('assignments.store') }}" method="POST">
                @csrf
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">ចំណងជើងកិច្ចការ <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="បញ្ចូលចំណងជើងកិច្ចការ...">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">ថ្នាក់រៀន <span class="text-danger">*</span></label>
                            <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                                <option value="">ជ្រើសរើសថ្នាក់រៀន</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->grade?->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">បន្ទប់/ក្រុម (ស្រេចចិត្ត)</label>
                            <select name="section_id" class="form-select @error('section_id') is-invalid @enderror">
                                <option value="">ជ្រើសរើសបន្ទប់/ក្រុម</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">មុខវិជ្ជា <span class="text-danger">*</span></label>
                            <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">ជ្រើសរើសមុខវិជ្ជា</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">គ្រូបង្រៀន <span class="text-danger">*</span></label>
                            <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">ជ្រើសរើសគ្រូបង្រៀន</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">កាលបរិច្ឆេទផុតកំណត់ <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">ពិន្ទុសរុប <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="total_marks" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks') }}" required placeholder="0.00">
                            @error('total_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">ការពិពណ៌នា</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="សរសេរការណែនាំ ឬព័ត៌មានលម្អិតពីកិច្ចការ...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions-bar bg-light p-3 border-top d-flex justify-content-end gap-2" style="border-radius: 0 0 var(--radius-md) var(--radius-md);">
                    <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary">បោះបង់</a>
                    <button type="submit" class="btn btn-primary-action">បង្កើតកិច្ចការ</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>ព័ត៌មានណែនាំ</h6>
            </div>
            <div class="card-body text-muted" style="font-size: .875rem;">
                <p>សូមប្រាកដថាអ្នកបានបំពេញគ្រប់ប្រអប់ទិន្នន័យចាំបាច់ដែលមានសញ្ញាផ្កាយក្រហម (<span class="text-danger">*</span>)។</p>
                <p>កិច្ចការនេះនឹងបង្ហាញដោយស្វ័យប្រវត្តិទៅកាន់សិស្សនៅក្នុងថ្នាក់ និងបន្ទប់ដែលបានជ្រើសរើស។</p>
                <p class="mb-0">អ្នកអាចកែសម្រួលព័ត៌មានលម្អិត ឬភ្ជាប់ឯកសារបន្ថែមនៅពេលក្រោយបានពីទំព័រព័ត៌មានលម្អិតកិច្ចការ។</p>
            </div>
        </div>
    </div>
</div>

@endsection
