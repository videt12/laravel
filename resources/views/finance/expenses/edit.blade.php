@extends('layouts.app')

@section('title', 'កែសម្រួលចំណាយរបស់សាលា')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('school-expenses.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-2">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅការចំណាយ
        </a>
        <h1 class="h3 fw-bold text-dark mb-0">កែសម្រួលចំណាយរបស់សាលា</h1>
        <p class="text-muted small mb-0 mt-1">កែប្រែកំណត់ត្រាចំណាយប្រតិបត្តិការ</p>
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
                <form action="{{ route('school-expenses.update', $schoolExpense->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="expense_category_id" class="form-label small fw-bold text-dark">ប្រភេទចំណាយ <span class="text-danger">*</span></label>
                        <select name="expense_category_id" id="expense_category_id" class="form-select @error('expense_category_id') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('expense_category_id', $schoolExpense->expense_category_id) == $cat->id ? 'selected' : '' }}>
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
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount', $schoolExpense->amount) }}" class="form-control @error('amount') is-invalid @enderror" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="expense_date" class="form-label small fw-bold text-dark">កាលបរិច្ឆេទចំណាយ <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', $schoolExpense->expense_date?->format('Y-m-d')) }}" class="form-control @error('expense_date') is-invalid @enderror" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label small fw-bold text-dark">ការពិពណ៌នា / គោលបំណង</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $schoolExpense->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="receipt" class="form-label small fw-bold text-dark">ប្ដូរឯកសារបង្កាន់ដៃ / វិក្កយបត្រ</label>
                        @if($schoolExpense->receipt_path)
                            <div class="mb-2 p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                <span class="small text-muted"><i class="bi bi-file-earmark-check text-success me-1"></i> មានបង្កាន់ដៃភ្ជាប់រួចហើយ</span>
                                <a href="{{ asset('storage/' . $schoolExpense->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info p-1 px-2 text-decoration-none">មើលឯកសារ</a>
                            </div>
                        @endif
                        <input type="file" name="receipt" id="receipt" class="form-control @error('receipt') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp">
                        <div class="form-text small">ទុកនៅទំនេរប្រសិនបើមិនចង់ប្ដូរឯកសារចាស់។ ទំហំឯកសារអតិបរមា៖ 5MB។</div>
                        @error('receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('school-expenses.index') }}" class="btn btn-sm btn-light border">បោះបង់</a>
                        <button type="submit" class="btn btn-sm btn-primary px-4">ធ្វើបច្ចុប្បន្នភាពចំណាយ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
