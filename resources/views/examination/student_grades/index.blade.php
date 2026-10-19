@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Student Exam Grades & Overall Results</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('exams.index') }}">Examinations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Student Grades</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if(request('exam_id'))
                <a href="{{ route('examinations.export-grades', ['exam' => request('exam_id'), 'class_id' => request('class_id')]) }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i>ទាញយក Excel ពិន្ទុ
                </a>
            @endif
            @if(request('exam_id') && request('class_id'))
                <a href="{{ route('examinations.class-report-cards', ['exam' => request('exam_id'), 'school_class' => request('class_id')]) }}" target="_blank" class="btn btn-success btn-sm">
                    <i class="bi bi-printer-fill me-1"></i>បោះពុម្ពព្រឹត្តិបត្រទាំងថ្នាក់ (Print All)
                </a>
            @endif
            @if(request('exam_id'))
                <form action="{{ route('exams.calculate-grades', request('exam_id')) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning text-dark fw-semibold btn-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Recalculate Exam Grades
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('student-grades.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Filter by Exam</label>
                    <select name="exam_id" class="form-select form-select-sm">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} ({{ $exam->academicYear->name ?? 'Year' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Filter by Class</label>
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Result Status</label>
                    <select name="result_status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="passed" {{ request('result_status') == 'passed' ? 'selected' : '' }}>Passed</option>
                        <option value="failed" {{ request('result_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted mb-1">Student Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Adm No" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('student-grades.index') }}" class="btn btn-outline-secondary btn-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Grades Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Class</th>
                            <th>Total Marks</th>
                            <th>Percentage</th>
                            <th>Overall Grade</th>
                            <th>GPA</th>
                            <th>Result Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentGrades as $grade)
                            <tr>
                                <td class="ps-4 text-muted small">{{ $grade->id }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        {{ $grade->student->first_name ?? '' }} {{ $grade->student->last_name ?? 'N/A' }}
                                    </div>
                                    <div class="text-muted small">Adm: {{ $grade->student->admission_number ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $grade->exam->name ?? 'N/A' }}</span>
                                    <div class="text-muted small">{{ $grade->exam->academicYear->name ?? '' }}</div>
                                </td>
                                <td>
                                    {{ $grade->student->schoolClass->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ number_format($grade->total_marks, 2) }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">{{ number_format($grade->percentage, 2) }}%</span>
                                </td>
                                <td>
                                    @if($grade->grade)
                                        <span class="badge bg-{{ in_array($grade->grade, ['A+', 'A', 'B+', 'B']) ? 'success' : (in_array($grade->grade, ['C+', 'C', 'D']) ? 'warning' : 'danger') }} px-2 py-1">
                                            {{ $grade->grade }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ number_format($grade->gpa, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ strtolower($grade->result_status) === 'passed' ? 'success' : 'danger' }} px-2 py-1 text-capitalize">
                                        {{ $grade->result_status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('student-grades.show', $grade) }}" class="btn btn-outline-info" title="View Summary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ route('examinations.report-card', ['exam' => $grade->exam_id, 'student' => $grade->student_id]) }}" target="_blank" class="btn btn-outline-primary" title="បោះពុម្ពព្រឹត្តិបត្រផ្លូវការ">
                                            <i class="bi bi-printer"></i> បោះពុម្ព
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bi bi-award text-muted fs-1 mb-3 d-block"></i>
                                    <h6 class="text-muted fw-bold">No Overall Grades Found</h6>
                                    <p class="text-muted small mb-3">Overall student grades are derived automatically when raw scores are submitted for exam subjects.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($studentGrades->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $studentGrades->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
