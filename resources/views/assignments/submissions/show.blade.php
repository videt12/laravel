@extends('layouts.app')

@section('title', 'Student Submission — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}" class="text-decoration-none text-muted">Assignments</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.show', $submission->assignment->id) }}" class="text-decoration-none text-muted">{{ Str::limit($submission->assignment->title, 20) }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.submissions.index', $submission->assignment->id) }}" class="text-decoration-none text-muted">Submissions</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Details</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Student Submission</h1>
        <p class="page-subtitle">{{ $submission->assignment->title }}</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold text-dark">Submission Information</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless table-striped mb-0">
                    <tbody>
                        <tr>
                            <th class="ps-4 text-muted" style="width: 35%;">Student Name</th>
                            <td class="fw-medium text-dark">{{ $submission->student?->first_name }} {{ $submission->student?->last_name }}</td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted">Admission Number</th>
                            <td>{{ $submission->student?->admission_number ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted">Submitted At</th>
                            <td>
                                @if($submission->submission_date)
                                    <span class="text-dark">{{ $submission->submission_date->format('d M Y') }}</span>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-4 text-muted align-middle">Status</th>
                            <td>
                                @if($submission->status === 'Submitted')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-2">Submitted</span>
                                @elseif($submission->status === 'Late')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle rounded-pill px-2">Late</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill px-2">{{ $submission->status ?? 'Pending' }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr class="my-0">
                <div class="p-4">
                    <h6 class="text-muted fw-semibold mb-2">Remarks / Content</h6>
                    <div class="text-dark" style="font-size: .9rem; white-space: pre-wrap;">{{ $submission->remarks ?? 'No remarks provided by the student.' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <!-- Grade Section -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: var(--radius-md);">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold text-dark"><i class="bi bi-star me-2 text-warning"></i>Grade Section</h6>
                @if($submission->assignmentGrade)
                    <a href="{{ route('grades.edit', $submission->assignmentGrade->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: .75rem;">Edit Grade</a>
                @endif
            </div>
            <div class="card-body">
                @if($submission->assignmentGrade)
                    <div class="text-center mb-4">
                        <div class="display-4 fw-bold text-dark mb-1">{{ $submission->assignmentGrade->marks_obtained }}</div>
                        <div class="text-muted small">out of {{ $submission->assignment->total_marks }}</div>
                        @if($submission->assignmentGrade->grade)
                            <div class="mt-2"><span class="badge bg-primary rounded-pill px-3 py-1">{{ $submission->assignmentGrade->grade }}</span></div>
                        @endif
                    </div>
                    @if($submission->assignmentGrade->feedback)
                        <div class="bg-light p-3 rounded mb-3">
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Teacher Feedback</h6>
                            <p class="mb-0 text-dark" style="font-size: .875rem;">{{ $submission->assignmentGrade->feedback }}</p>
                        </div>
                    @endif
                    <div class="text-muted text-center" style="font-size: .75rem;">
                        Graded by {{ $submission->assignmentGrade->gradedBy?->name ?? '—' }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3 text-muted">
                            <i class="bi bi-journal-check" style="font-size: 2.5rem;"></i>
                        </div>
                        <h6 class="fw-medium text-dark">Needs Grading</h6>
                        <p class="text-muted small mb-3">This submission has not been graded yet.</p>
                        <a href="{{ route('submissions.grades.create', $submission->id) }}" class="btn btn-primary-action btn-sm">
                            Grade Submission
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
