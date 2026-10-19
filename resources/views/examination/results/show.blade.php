@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Exam Result Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Examinations</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('exam-results.index') }}">Results</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Result #{{ $examResult->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('exam-results.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Results
            </a>
            @if($examResult->examSubject)
                <a href="{{ route('exam-subjects.scores', $examResult->examSubject) }}" class="btn btn-warning text-dark btn-sm fw-semibold">
                    <i class="bi bi-pencil-square me-1"></i> Class Score Sheet
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Student & Subject Information</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted ps-0" style="width: 35%;">Student</th>
                                <td class="fw-semibold">
                                    {{ $examResult->student->first_name ?? '' }} {{ $examResult->student->last_name ?? 'N/A' }}
                                    <div class="text-muted small">Adm: {{ $examResult->student->admission_number ?? '-' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Exam</th>
                                <td class="fw-semibold">{{ $examResult->examSubject->examSchedule->exam->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Class</th>
                                <td>{{ $examResult->examSubject->examSchedule->schoolClass->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Section</th>
                                <td>{{ $examResult->examSubject->examSchedule->section->name ?? 'All Sections' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Subject</th>
                                <td class="fw-bold text-primary">{{ $examResult->examSubject->subject->name ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0">Marks & Evaluation</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted ps-0" style="width: 35%;">Marks Obtained</th>
                                <td>
                                    <span class="fs-4 fw-bold text-dark">{{ number_format($examResult->marks_obtained, 2) }}</span>
                                    <span class="text-muted"> / {{ number_format($examResult->examSubject->max_marks ?? 100, 2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Pass Marks</th>
                                <td class="fw-semibold text-muted">{{ number_format($examResult->examSubject->pass_marks ?? 33, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Grade Awarded</th>
                                <td>
                                    @if($examResult->grade)
                                        <span class="badge bg-{{ in_array($examResult->grade, ['A+', 'A', 'B+', 'B']) ? 'success' : (in_array($examResult->grade, ['C+', 'C', 'D']) ? 'warning' : 'danger') }} fs-6 px-3 py-1">
                                            {{ $examResult->grade }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Status</th>
                                <td>
                                    @php
                                        $passMarks = $examResult->examSubject->pass_marks ?? 0;
                                        $isPassed = $examResult->marks_obtained >= $passMarks;
                                    @endphp
                                    <span class="badge bg-{{ $isPassed ? 'success' : 'danger' }} px-2 py-1">
                                        {{ $isPassed ? 'Passed' : 'Failed' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted ps-0">Remarks</th>
                                <td>{{ $examResult->remarks ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3 text-end">
                    <form action="{{ route('exam-results.destroy', $examResult) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this result? Overall student grade will be recalculated.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Delete Result
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
