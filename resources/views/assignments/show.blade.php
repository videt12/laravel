@extends('layouts.app')

@section('title', 'Assignment Details — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}" class="text-decoration-none text-muted">Assignments</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Details</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">{{ $assignment->title }}</h1>
        <p class="page-subtitle">View assignment details and submission statistics.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assignments.submissions.index', $assignment->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-people me-1"></i>Submissions
        </a>
        <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-primary-action">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Assignment Information</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless table-striped mb-0">
                    <tbody>
                        <tr>
                            <th class="ps-4 text-muted" style="width: 30%;">Title</th>
                            <td class="fw-medium text-dark">{{ $assignment->title }}</td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted">Class</th>
                            <td>{{ $assignment->class?->grade?->name ?? '—' }} {{ $assignment->section?->name ? ' — ' . $assignment->section->name : '' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted">Subject</th>
                            <td>{{ $assignment->subject?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted">Created By</th>
                            <td>{{ $assignment->teacher?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted">Due Date</th>
                            <td>
                                @if($assignment->due_date)
                                    <span class="fw-medium {{ $assignment->due_date < now() ? 'text-danger' : 'text-dark' }}">
                                        {{ $assignment->due_date->format('d M Y') }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted">Total Marks</th>
                            <td><span class="badge bg-light text-dark border">{{ $assignment->total_marks }}</span></td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted align-middle">Status</th>
                            <td>
                                @if($assignment->due_date && $assignment->due_date < now())
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-2">Closed</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-2">Open</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr class="my-0">
                <div class="p-4">
                    <h6 class="text-muted fw-semibold mb-2">Description</h6>
                    <div class="text-dark" style="font-size: .9rem; white-space: pre-wrap;">{{ $assignment->description ?? 'No description provided.' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Submissions Summary -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold text-dark"><i class="bi bi-bar-chart-steps me-2 text-primary"></i>Submissions Summary</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted" style="font-size: .875rem;">Total Students</span>
                    <span class="fw-bold fs-5 text-dark">{{ $totalStudents }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted" style="font-size: .875rem;">Submitted</span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1">{{ $submittedCount }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="text-muted" style="font-size: .875rem;">Pending</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-2 py-1">{{ $pendingCount }}</span>
                </div>
                
                <a href="{{ route('assignments.submissions.index', $assignment->id) }}" class="btn btn-light w-100 border text-dark fw-medium" style="font-size: .875rem;">
                    View All Submissions
                </a>
            </div>
        </div>

        <!-- Attachments Section -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold text-dark"><i class="bi bi-paperclip me-2 text-primary"></i>Attachments</h6>
            </div>
            <div class="card-body p-0">
                @if(isset($assignment->assignment_attachments) && $assignment->assignment_attachments->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($assignment->assignment_attachments as $attachment)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-3 border-0 border-bottom">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <div class="bg-light rounded p-2 text-primary">
                                    <i class="bi bi-file-earmark-text fs-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-dark fw-medium text-truncate" style="font-size: .875rem;">{{ $attachment->file_name }}</div>
                                    <div class="text-muted" style="font-size: .75rem;">Attachment</div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                @if(!empty($attachment->file_path))
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" download="{{ $attachment->file_name }}" class="btn btn-sm btn-outline-secondary px-2" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary px-2" disabled title="File not available"><i class="bi bi-download"></i></button>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted text-center py-4" style="font-size:.875rem;">
                        <i class="bi bi-journal-x fs-4 d-block mb-2 text-light-subtle"></i>
                        No attachments available.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
