@extends('layouts.app')

@section('title', 'Exam Subjects')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Exam Subject Papers</h1>
            <p class="text-muted small mb-0 mt-1">Specific subject papers attached to class exam schedules</p>
        </div>
        <a href="{{ route('exam-subjects.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
            <i class="bi bi-plus-lg me-1"></i> Add Subject Paper
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-body p-3">
            <form action="{{ route('exam-subjects.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <select name="exam_schedule_id" class="form-select form-select-sm">
                        <option value="">All Schedules</option>
                        @foreach($schedules as $s)
                            <option value="{{ $s->id }}" {{ request('exam_schedule_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->exam?->name }} — {{ $s->schoolClass?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <select name="subject_id" class="form-select form-select-sm">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['exam_schedule_id', 'subject_id']))
                        <a href="{{ route('exam-subjects.index') }}" class="btn btn-sm btn-light border" title="Clear">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Papers Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Subject</th>
                            <th>Exam</th>
                            <th>Class</th>
                            <th>Date & Time</th>
                            <th>Marks (Max / Pass)</th>
                            <th>Student Scores</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examSubjects as $esub)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">
                                    <a href="{{ route('exam-subjects.show', $esub->id) }}" class="text-dark text-decoration-none">
                                        {{ $esub->subject?->name ?? '—' }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('exams.show', $esub->exam_schedule?->exam_id) }}" class="text-decoration-none">
                                        {{ $esub->exam_schedule?->exam?->name ?? '—' }}
                                    </a>
                                </td>
                                <td>{{ $esub->exam_schedule?->schoolClass?->name ?? '—' }}</td>
                                <td class="small text-muted">
                                    {{ $esub->exam_date?->format('d M Y') ?? 'Date TBD' }}
                                    @if($esub->start_time)
                                        <br>{{ $esub->start_time }} — {{ $esub->end_time }}
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ number_format($esub->max_marks, 2) }}</strong>
                                    <span class="text-muted">/ {{ number_format($esub->pass_marks, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $esub->exam_results_count > 0 ? 'bg-success-subtle text-success' : 'bg-light text-muted border' }}">
                                        {{ $esub->exam_results_count }} graded
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('exam-subjects.scores', $esub->id) }}" class="btn btn-sm btn-gold text-white py-1 px-2" style="background-color: var(--gold, #e8a838);" title="Score Sheet">
                                            <i class="bi bi-pencil-square me-1"></i> Enter Marks
                                        </a>
                                        <a href="{{ route('exam-subjects.edit', $esub->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($esub->exam_results_count === 0)
                                            <form action="{{ route('exam-subjects.destroy', $esub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this exam subject?');">
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
                                <td colspan="7" class="text-center py-4 text-muted">No subject papers found.</td>
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
