@extends('layouts.app')

@section('title', 'Create Exam')

@section('content')
<div class="container-fluid px-0" style="max-width: 800px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Create Exam</h1>
            <p class="text-muted small mb-0 mt-1">Set up a new examination period</p>
        </div>
        <a href="{{ route('examinations.index', ['tab' => 'exams']) }}" class="btn btn-sm btn-outline-secondary">
            &larr; Back to Exams
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-4">
            <form action="{{ route('exams.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-8">
                        <label for="name" class="form-label fw-semibold text-dark">Exam Name <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. First Semester Final Examination 2026"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="exam_type_id" class="form-label fw-semibold text-dark">Exam Type <span class="text-danger">*</span></label>
                        <select name="exam_type_id" id="exam_type_id" class="form-select @error('exam_type_id') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            @foreach($examTypes as $type)
                                <option value="{{ $type->id }}" {{ old('exam_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('exam_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label for="academic_year_id" class="form-label fw-semibold text-dark">Academic Year <span class="text-danger">*</span></label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                            <option value="">Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="school_term_id" class="form-label fw-semibold text-dark">School Term</label>
                        <select name="school_term_id" id="school_term_id" class="form-select @error('school_term_id') is-invalid @enderror">
                            <option value="">Full Academic Year / None</option>
                            @foreach($schoolTerms as $term)
                                <option value="{{ $term->id }}" {{ old('school_term_id') == $term->id ? 'selected' : '' }}>
                                    {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_term_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="start_date" class="form-label fw-semibold text-dark">Start Date</label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date') }}">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="end_date" class="form-label fw-semibold text-dark">End Date</label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('examinations.index', ['tab' => 'exams']) }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                        <i class="bi bi-check-lg me-1"></i> Save Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
