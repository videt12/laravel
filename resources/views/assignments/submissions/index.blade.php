@extends('layouts.app')

@section('title', 'Submissions: ' . $assignment->title . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}" class="text-decoration-none text-muted">Assignments</a></li>
        <li class="breadcrumb-item"><a href="{{ route('assignments.show', $assignment->id) }}" class="text-decoration-none text-muted">{{ Str::limit($assignment->title, 20) }}</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Submissions</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Assignment Submissions</h1>
        <p class="page-subtitle">Submissions for <strong>{{ $assignment->title }}</strong></p>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">Student Submissions</span>
            <span class="count-badge">{{ $submissions->total() }}</span>
        </div>
        <small class="text-muted">
            @if($submissions->total() > 0)
                Showing {{ $submissions->firstItem() }}–{{ $submissions->lastItem() }} of {{ $submissions->total() }}
            @endif
        </small>
    </div>

    @if($submissions->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-inboxes"></i></div>
            <p class="empty-title">No Submissions Found</p>
            <p class="empty-subtitle">There are no student submissions for this assignment yet.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40px">#</th>
                        <th>Student Name</th>
                        <th>Admission No.</th>
                        <th>Submitted At</th>
                        <th>Status</th>
                        <th>Grade</th>
                        <th style="width:100px" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $i => $submission)
                    <tr>
                        <td class="text-center text-muted" style="font-size:.75rem">{{ $submissions->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold text-dark" style="font-size:.875rem">
                                {{ $submission->student?->first_name }} {{ $submission->student?->last_name }}
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:.84rem">{{ $submission->student?->admission_number ?? '—' }}</span>
                        </td>
                        <td>
                            @if($submission->submission_date)
                                <div class="fw-medium text-dark" style="font-size:.875rem">
                                    {{ $submission->submission_date->format('d M Y') }}
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($submission->status === 'Submitted')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-2">Submitted</span>
                            @elseif($submission->status === 'Late')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle rounded-pill px-2">Late</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill px-2">{{ $submission->status ?? 'Pending' }}</span>
                            @endif
                        </td>
                        <td>
                            @if($submission->assignmentGrade)
                                <span class="badge bg-light text-dark border">{{ $submission->assignmentGrade->marks_obtained }} / {{ $assignment->total_marks }}</span>
                            @else
                                <span class="text-muted" style="font-size:.84rem">Not Graded</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('submissions.show', $submission->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: .75rem;">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
        <div class="data-card-footer d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.8rem">Page {{ $submissions->currentPage() }} of {{ $submissions->lastPage() }}</div>
            {{ $submissions->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif
</div>

@endsection
