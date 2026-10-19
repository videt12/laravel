@extends('layouts.app')

@section('title', 'កែសម្រួលកាលវិភាគប្រឡង')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'schedules']) }}" class="text-decoration-none text-muted">កាលវិភាគប្រឡង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">កែសម្រួល</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0" style="max-width: 720px;">
    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">កែសម្រួលកាលវិភាគប្រឡង</h2>
            <p class="text-muted small mb-0 mt-0.5" style="font-size: 0.8rem;">កែប្រែថ្នាក់រៀន ឬបន្ទប់សិក្សាសម្រាប់ការប្រឡង</p>
        </div>
        <a href="{{ route('examinations.index', ['tab' => 'schedules']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
            <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
        </a>
    </div>

    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white">
        <div class="card-body p-3.5">
            <form action="{{ route('exam-schedules.update', $examSchedule->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="exam_id" class="form-label fw-semibold text-dark small">ការប្រឡង <span class="text-danger">*</span></label>
                    <select name="exam_id" id="exam_id" class="form-select @error('exam_id') is-invalid @enderror" style="height: 36px; font-size: 0.85rem;" required>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $examSchedule->exam_id) == $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} ({{ $exam->academic_year?->name ?? 'ឆ្នាំសិក្សា' }})
                            </option>
                        @endforeach
                    </select>
                    @error('exam_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-2.5 mb-3">
                    <div class="col-12 col-md-8">
                        <label for="class_id" class="form-label fw-semibold text-dark small">ថ្នាក់រៀន <span class="text-danger">*</span></label>
                        <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" style="height: 36px; font-size: 0.85rem;" required>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id', $examSchedule->class_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->academic_year?->name ?? 'ឆ្នាំសិក្សា' }})
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="section_id" class="form-label fw-semibold text-dark small">បន្ទប់/វេន</label>
                        <select name="section_id" id="section_id" class="form-select @error('section_id') is-invalid @enderror" style="height: 36px; font-size: 0.85rem;">
                            <option value="">ទាំងអស់ / លំនាំដើម</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ old('section_id', $examSchedule->section_id) == $sec->id ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('examinations.index', ['tab' => 'schedules']) }}" class="btn btn-sm btn-light border px-3" style="height: 34px; font-size: 0.82rem; border-radius: 6px;">បោះបង់</a>
                    <button type="submit" class="btn btn-sm text-white px-3.5 shadow-sm" style="height: 34px; font-size: 0.84rem; font-weight: 600; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px;">
                        <i class="bi bi-save me-1"></i>រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
