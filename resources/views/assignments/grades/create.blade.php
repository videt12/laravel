@extends('layouts.app')

@section('title', 'Grade Submission — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}" class="text-decoration-none text-muted">Assignments</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.show', $submission->assignment->id) }}" class="text-decoration-none text-muted">{{ Str::limit($submission->assignment->title, 20) }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.submissions.index', $submission->assignment->id) }}" class="text-decoration-none text-muted">Submissions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('submissions.show', $submission->id) }}" class="text-decoration-none text-muted">Details</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Grade</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Grade Submission</h1>
        <p class="page-subtitle">Evaluate the submission for <strong>{{ $submission->student?->first_name }} {{ $submission->student?->last_name }}</strong></p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <h5 class="mb-0 fw-semibold text-dark">Evaluation Form</h5>
            </div>
            
            <form action="{{ route('submissions.grades.store', $submission->id) }}" method="POST">
                @csrf
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Marks Obtained <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="marks_obtained" class="form-control @error('marks_obtained') is-invalid @enderror" value="{{ old('marks_obtained') }}" required max="{{ $submission->assignment->total_marks }}" min="0">
                                <span class="input-group-text bg-light text-muted">/ {{ $submission->assignment->total_marks }}</span>
                            </div>
                            @error('marks_obtained')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Grade (Letter / Scale)</label>
                            <input type="text" name="grade" class="form-control @error('grade') is-invalid @enderror" value="{{ old('grade') }}" placeholder="e.g. A, B+, Pass">
                            @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Feedback Comments</label>
                            <textarea name="feedback" rows="5" class="form-control @error('feedback') is-invalid @enderror" placeholder="Provide constructive feedback for the student...">{{ old('feedback') }}</textarea>
                            @error('feedback')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions-bar bg-light p-3 border-top d-flex justify-content-end gap-2" style="border-radius: 0 0 var(--radius-md) var(--radius-md);">
                    <a href="{{ route('submissions.show', $submission->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary-action">Save Grade</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Context</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item py-3 px-3">
                        <div class="text-muted small mb-1">Assignment</div>
                        <div class="fw-medium text-dark">{{ $submission->assignment->title }}</div>
                    </li>
                    <li class="list-group-item py-3 px-3">
                        <div class="text-muted small mb-1">Student</div>
                        <div class="fw-medium text-dark">{{ $submission->student?->first_name }} {{ $submission->student?->last_name }} ({{ $submission->student?->admission_number ?? '—' }})</div>
                    </li>
                    <li class="list-group-item py-3 px-3">
                        <div class="text-muted small mb-1">Max Marks</div>
                        <div class="fw-medium text-dark">{{ $submission->assignment->total_marks }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
