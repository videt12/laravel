@extends('layouts.app')

@section('title', 'បន្ថែមសៀវភៅថ្មី')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">បន្ថែមសៀវភៅថ្មី</h1>
            <p class="text-muted small mb-0 mt-1">ចុះបញ្ជីចំណងជើងសៀវភៅគោលក្នុងកាតាឡុកបណ្ណាល័យ</p>
        </div>
        <a href="{{ route('library.books.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅបញ្ជីសៀវភៅ
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-xs rounded-3 bg-white p-4">
                <form method="POST" action="{{ route('library.books.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">ចំណងជើងសៀវភៅ <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="ឧ. Clean Code: A Handbook of Agile Software Craftsmanship" required maxlength="255">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium text-dark">អ្នកនិពន្ធ</label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}" placeholder="ឧ. Robert C. Martin" maxlength="255">
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium text-dark">រោងពុម្ព</label>
                            <input type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror" value="{{ old('publisher') }}" placeholder="ឧ. Prentice Hall" maxlength="255">
                            @error('publisher')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium text-dark">ISBN</label>
                            <input type="text" name="isbn" class="form-control font-monospace @error('isbn') is-invalid @enderror" value="{{ old('isbn') }}" placeholder="ឧ. 978-0132350884" maxlength="20">
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium text-dark">ប្រភេទសៀវភៅ</label>
                            <select name="library_category_id" class="form-select @error('library_category_id') is-invalid @enderror">
                                <option value="">-- ជ្រើសរើសប្រភេទ (មិនបង្ខំ) --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string)old('library_category_id') === (string)$category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('library_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('library.books.index') }}" class="btn btn-sm btn-outline-secondary">បោះបង់</a>
                        <button type="submit" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                            <i class="bi bi-check-lg me-1"></i> រក្សាទុកសៀវភៅ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
