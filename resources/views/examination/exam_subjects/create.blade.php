@extends('layouts.app')

@section('title', 'បន្ថែមវិញ្ញាសាប្រឡង')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'subjects']) }}" class="text-decoration-none text-muted">វិញ្ញាសាប្រឡង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">បង្កើតថ្មី</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0" style="max-width: 720px;">
    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">បន្ថែមវិញ្ញាសាប្រឡង</h2>
            <p class="text-muted small mb-0 mt-0.5" style="font-size: 0.8rem;">ភ្ជាប់មុខវិជ្ជាប្រឡងទៅនឹងកាលវិភាគប្រឡងនៃថ្នាក់រៀន</p>
        </div>
        <a href="{{ route('examinations.index', ['tab' => 'subjects']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
            <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>

    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white">
        <div class="card-body p-3.5">
            <form action="{{ route('exam-subjects.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="exam_schedule_id" class="form-label fw-semibold text-dark small">កាលវិភាគប្រឡង (ការប្រឡង & ថ្នាក់រៀន) <span class="text-danger">*</span></label>
                    <select name="exam_schedule_id" id="exam_schedule_id" class="form-select @error('exam_schedule_id') is-invalid @enderror" style="height: 36px; font-size: 0.85rem;" required>
                        <option value="">— ជ្រើសរើសកាលវិភាគ —</option>
                        @foreach($schedules as $s)
                            <option value="{{ $s->id }}" {{ (old('exam_schedule_id') ?? $selectedScheduleId) == $s->id ? 'selected' : '' }}>
                                {{ $s->exam?->name }} — {{ $s->schoolClass?->name }} {{ $s->section && !str_contains($s->schoolClass?->name ?? '', $s->section->name) ? "({$s->section->name})" : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('exam_schedule_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subject_id" class="form-label fw-semibold text-dark small">មុខវិជ្ជា <span class="text-danger">*</span></label>
                    <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" style="height: 36px; font-size: 0.85rem;" required>
                        <option value="">— ជ្រើសរើសមុខវិជ្ជា —</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ old('subject_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }} ({{ $sub->code ?? 'SUB' }})
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-2.5 mb-3">
                    <div class="col-12 col-md-4">
                        <label for="exam_date" class="form-label fw-semibold text-dark small">កាលបរិច្ឆេទប្រឡង</label>
                        <input type="date"
                               name="exam_date"
                               id="exam_date"
                               class="form-control @error('exam_date') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('exam_date') }}">
                        @error('exam_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="start_time" class="form-label fw-semibold text-dark small">ម៉ោងចាប់ផ្តើម</label>
                        <input type="time"
                               name="start_time"
                               id="start_time"
                               class="form-control @error('start_time') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('start_time') }}">
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="end_time" class="form-label fw-semibold text-dark small">ម៉ោងបញ្ចប់</label>
                        <input type="time"
                               name="end_time"
                               id="end_time"
                               class="form-control @error('end_time') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('end_time') }}">
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-2.5 mb-3">
                    <div class="col-12 col-md-6">
                        <label for="max_marks" class="form-label fw-semibold text-dark small">ពិន្ទុអតិបរមា <span class="text-danger">*</span></label>
                        <input type="number"
                               step="0.01"
                               min="1"
                               max="1000"
                               name="max_marks"
                               id="max_marks"
                               class="form-control @error('max_marks') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('max_marks', '100.00') }}"
                               required>
                        @error('max_marks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="pass_marks" class="form-label fw-semibold text-dark small">ពិន្ទុជាប់ <span class="text-danger">*</span></label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               max="1000"
                               name="pass_marks"
                               id="pass_marks"
                               class="form-control @error('pass_marks') is-invalid @enderror"
                               style="height: 36px; font-size: 0.85rem;"
                               value="{{ old('pass_marks', '33.00') }}"
                               required>
                        @error('pass_marks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('examinations.index', ['tab' => 'subjects']) }}" class="btn btn-sm btn-light border px-3" style="height: 34px; font-size: 0.82rem; border-radius: 6px;">បោះបង់</a>
                    <button type="submit" class="btn btn-sm text-white px-3.5 shadow-sm" style="height: 34px; font-size: 0.84rem; font-weight: 600; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px;">
                        <i class="bi bi-save me-1"></i>រក្សាទុកវិញ្ញាសា
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
