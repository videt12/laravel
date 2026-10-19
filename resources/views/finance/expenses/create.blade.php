@extends('layouts.app')

@section('title', 'កត់ត្រាចំណាយរបស់សាលា')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('school-expenses.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-2">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅការចំណាយ
        </a>
        <h1 class="h3 fw-bold text-dark mb-0">កត់ត្រាចំណាយរបស់សាលា</h1>
        <p class="text-muted small mb-0 mt-1">កត់ត្រាការចំណាយប្រតិបត្តិការជាមួយប្រភេទចំណាយ និងវិក្កយបត្រ/បង្កាន់ដៃភ្ជាប់</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-xs rounded-3 mb-4">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white p-4">
                <form action="{{ route('school-expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="expense_category_id" class="form-label small fw-bold text-dark">ប្រភេទចំណាយ <span class="text-danger">*</span></label>
                        <select name="expense_category_id" id="expense_category_id" class="form-select @error('expense_category_id') is-invalid @enderror" required>
                            <option value="">ជ្រើសរើសប្រភេទចំណាយ...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('expense_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label small fw-bold text-dark">ចំនួនទឹកប្រាក់ ($) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" placeholder="0.00" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="expense_date" class="form-label small fw-bold text-dark">កាលបរិច្ឆេទចំណាយ <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" class="form-control @error('expense_date') is-invalid @enderror" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label small fw-bold text-dark">ការពិពណ៌នា / គោលបំណង</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="ឧ. ថ្លៃទឹក-ភ្លើងប្រចាំខែ, លាបថ្នាំបន្ទប់រៀន, សម្ភារៈពិសោធន៍">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="receipt" class="form-label small fw-bold text-dark">ភ្ជាប់បង្កាន់ដៃ / វិក្កយបត្រ (PDF ឬរូបភាព)</label>
                        <input type="file" name="receipt" id="receipt" class="form-control @error('receipt') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp">
                        <div class="form-text small">ទំហំឯកសារអតិបរមា៖ 5MB។ ប្រភេទឯកសារអនុញ្ញាត៖ PDF, JPG, PNG, WEBP។</div>
                        @error('receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('school-expenses.index') }}" class="btn btn-sm btn-light border">បោះបង់</a>
                        <button type="submit" class="btn btn-sm btn-primary px-4">កត់ត្រាចំណាយ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
