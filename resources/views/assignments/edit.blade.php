@extends('layouts.app')

@section('title', 'Edit Assignment — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}" class="text-decoration-none text-muted">Assignments</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Edit Assignment</h1>
        <p class="page-subtitle">Update assignment information.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="form-card">
            <div class="form-card-header">
                <h5 class="mb-0 fw-semibold text-dark">Assignment Details</h5>
            </div>
            
            <form action="{{ route('assignments.update', $assignment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $assignment->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Class <span class="text-danger">*</span></label>
                            <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $assignment->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->grade?->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Section (Optional)</label>
                            <select name="section_id" class="form-select @error('section_id') is-invalid @enderror">
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $assignment->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $assignment->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Teacher <span class="text-danger">*</span></label>
                            <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $assignment->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $assignment->due_date?->format('Y-m-d')) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Total Marks <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="total_marks" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks', $assignment->total_marks) }}" required>
                            @error('total_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions-bar bg-light p-3 border-top d-flex justify-content-end gap-2" style="border-radius: 0 0 var(--radius-md) var(--radius-md);">
                    <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary-action">Update Assignment</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Attachments Section (if supported in backend) -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold text-dark"><i class="bi bi-paperclip me-2 text-primary"></i>Current Attachments</h6>
            </div>
            <div class="card-body">
                @if(isset($assignment->assignment_attachments) && $assignment->assignment_attachments->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($assignment->assignment_attachments as $attachment)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0 border-bottom">
                            <div class="d-flex align-items-center gap-2 text-truncate">
                                <i class="bi bi-file-earmark-text text-muted"></i>
                                <span class="text-truncate" style="font-size: .875rem;">{{ $attachment->file_name }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-link text-danger p-0" title="Remove"><i class="bi bi-trash"></i></button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted text-center py-3" style="font-size:.875rem;">
                        No attachments available.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
