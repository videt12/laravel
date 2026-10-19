@extends('layouts.app')

@section('title', 'កែសម្រួលប្រភេទការប្រឡង')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types']) }}" class="text-decoration-none text-muted">ប្រភេទការប្រឡង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">កែសម្រួល</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0" style="max-width: 680px;">
    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">កែសម្រួលប្រភេទការប្រឡង</h2>
            <p class="text-muted small mb-0 mt-0.5" style="font-size: 0.8rem;">កែប្រែព័ត៌មានលម្អិតនៃប្រភេទការប្រឡង</p>
        </div>
        <a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
            <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>

    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white">
        <div class="card-body p-3">
            <form action="{{ route('exam-types.update', $examType->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-2.5">
                    <label for="name" class="form-label small fw-semibold text-dark mb-1">
                        ឈ្មោះប្រភេទ <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control form-control-sm @error('name') is-invalid @enderror"
                           style="height: 36px; font-size: 0.85rem;"
                           value="{{ old('name', $examType->name) }}"
                           placeholder="ឧ. ការប្រឡងពាក់កណ្តាលឆមាស, ការប្រឡងប្រចាំខែ"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold text-dark mb-1">
                        ការពិពណ៌នា
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="form-control form-control-sm @error('description') is-invalid @enderror"
                              style="font-size: 0.85rem;"
                              placeholder="ពិពណ៌នាសង្ខេបអំពីប្រភេទការប្រឡងនេះ...">{{ old('description', $examType->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => 'exam-types']) }}" class="btn btn-sm btn-outline-secondary" style="height: 32px; font-size: 0.82rem; border-radius: 6px;">
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
