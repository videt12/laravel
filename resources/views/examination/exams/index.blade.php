@extends('layouts.app')

@section('title', 'Exams Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Exams</h1>
            <p class="text-muted small mb-0 mt-1">Manage school examinations, schedules, and grading periods</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('exams.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-plus-lg me-1"></i> Create Exam
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-body p-3">
            <form action="{{ route('exams.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search exam name...">
                </div>
                <div class="col-12 col-md-3">
                    <select name="academic_year_id" class="form-select form-select-sm">
                        <option value="">All Academic Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="exam_type_id" class="form-select form-select-sm">
                        <option value="">All Exam Types</option>
                        @foreach($examTypes as $type)
                            <option value="{{ $type->id }}" {{ request('exam_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'academic_year_id', 'exam_type_id']))
                        <a href="{{ route('exams.index') }}" class="btn btn-sm btn-light border" title="Clear Filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Exams Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Exam Name</th>
                            <th>Type</th>
                            <th>Academic Year</th>
                            <th>Term</th>
                            <th>Date Window</th>
                            <th>Classes Scheduled</th>
                            <th>Graded Students</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('exams.show', $exam->id) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $exam->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $exam->exam_type?->name ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $exam->academic_year?->name ?? '—' }}</td>
                                <td>{{ $exam->school_term?->name ?? 'Full Year' }}</td>
                                <td class="text-muted small">
                                    @if($exam->start_date && $exam->end_date)
                                        {{ $exam->start_date->format('d M') }} — {{ $exam->end_date->format('d M Y') }}
                                    @elseif($exam->start_date)
                                        Starts {{ $exam->start_date->format('d M Y') }}
                                    @else
                                        TBD
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $exam->exam_schedules_count }} class{{ $exam->exam_schedules_count === 1 ? '' : 'es' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $exam->student_grades_count > 0 ? 'bg-success-subtle text-success' : 'bg-light text-muted border' }}">
                                        {{ $exam->student_grades_count }} student{{ $exam->student_grades_count === 1 ? '' : 's' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-sm btn-outline-info py-1 px-2" title="Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($exam->exam_schedules_count === 0 && $exam->student_grades_count === 0)
                                            <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this exam?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                                    No examinations found matching the query.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($exams->hasPages())
                <div class="p-3 border-top">
                    {{ $exams->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
