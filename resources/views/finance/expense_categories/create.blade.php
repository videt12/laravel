@extends('layouts.app')

@section('title', 'បន្ថែមប្រភេទចំណាយ')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('expense-categories.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-2">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅប្រភេទចំណាយ
        </a>
        <h1 class="h3 fw-bold text-dark mb-0">បន្ថែមប្រភេទចំណាយ</h1>
        <p class="text-muted small mb-0 mt-1">កំណត់ចំណាត់ថ្នាក់ចំណាយសម្រាប់គណនេយ្យប្រតិបត្តិការសាលា</p>
    </div>

    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white p-4">
                <form action="{{ route('expense-categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-dark">ឈ្មោះប្រភេទចំណាយ <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="ឧ. សម្ភារៈការិយាល័យ, ថ្លៃទឹក-ភ្លើង, ការជួសជុលនិងថែទាំ" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label small fw-bold text-dark">ការពិពណ៌នា</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="កំណត់សម្គាល់បន្ថែមអំពីប្រភេទចំណាយ...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('expense-categories.index') }}" class="btn btn-sm btn-light border">បោះបង់</a>
                        <button type="submit" class="btn btn-sm btn-primary px-4">បង្កើតប្រភេទចំណាយ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
