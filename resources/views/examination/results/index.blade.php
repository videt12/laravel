@extends('layouts.app')

@section('title', 'Exam Score Entry & Results')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Exam Score Entry & Results</h1>
            <p class="text-muted small mb-0 mt-1">
                @if($authContext['isTeacher'])
                    Select one of your assigned subject papers to enter or update student marks
                @else
                    Browse scheduled exam papers and enter or review student marks
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('student-grades.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-award me-1"></i> View Overall Student Grades
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Exam</th>
                            <th>Class</th>
                            <th>Subject Paper</th>
                            <th>Date</th>
                            <th>Max Marks</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Score Sheet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examSubjects as $esub)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $esub->exam_schedule?->exam?->name ?? '—' }}
                                    <div class="text-muted small font-normal">{{ $esub->exam_schedule?->exam?->academic_year?->name ?? '—' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $esub->exam_schedule?->schoolClass?->name ?? '—' }}
                                        @if($esub->exam_schedule?->section)
                                            ({{ $esub->exam_schedule->section->name }})
                                        @endif
                                    </span>
                                </td>
                                <td class="fw-semibold text-primary">
                                    {{ $esub->subject?->name ?? '—' }}
                                </td>
                                <td class="small text-muted">
                                    {{ $esub->exam_date?->format('d M Y') ?? 'TBD' }}
                                </td>
                                <td>
                                    <strong>{{ number_format($esub->max_marks, 2) }}</strong>
                                    <span class="text-muted small">(Pass: {{ number_format($esub->pass_marks, 2) }})</span>
                                </td>
                                <td>
                                    @if($esub->exam_results_count > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i> {{ $esub->exam_results_count }} Graded
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border">
                                            Not Graded
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('exam-subjects.scores', $esub->id) }}" class="btn btn-sm btn-gold text-white py-1 px-3" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                                        <i class="bi bi-pencil-square me-1"></i> Enter Marks
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                                    @if($authContext['isTeacher'])
                                        No scheduled exam papers found for your assigned classes and subjects.
                                    @else
                                        No scheduled exam papers found.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($examSubjects->hasPages())
                <div class="p-3 border-top">
                    {{ $examSubjects->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
