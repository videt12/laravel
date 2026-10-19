@extends('layouts.app')

@section('title', 'Exam Schedules')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Exam Schedules</h1>
            <p class="text-muted small mb-0 mt-1">Class-level schedules and examination groupings</p>
        </div>
        <a href="{{ route('exam-schedules.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
            <i class="bi bi-plus-lg me-1"></i> New Exam Schedule
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-body p-3">
            <form action="{{ route('exam-schedules.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <select name="exam_id" class="form-select form-select-sm">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['exam_id', 'class_id']))
                        <a href="{{ route('exam-schedules.index') }}" class="btn btn-sm btn-light border" title="Clear">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Schedules Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Exam</th>
                            <th>Academic Year</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Subject Papers</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $s)
                            <tr>
                                <td class="ps-4 text-muted">#{{ $s->id }}</td>
                                <td>
                                    <a href="{{ route('exams.show', $s->exam_id) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $s->exam?->name ?? '—' }}
                                    </a>
                                </td>
                                <td>{{ $s->exam?->academic_year?->name ?? '—' }}</td>
                                <td class="fw-semibold text-dark">{{ $s->schoolClass?->name ?? '—' }}</td>
                                <td>{{ $s->section?->name ?? 'All Sections' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $s->exam_subjects_count }} paper{{ $s->exam_subjects_count === 1 ? '' : 's' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('exam-schedules.show', $s->id) }}" class="btn btn-sm btn-outline-info py-1 px-2" title="Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('exam-subjects.create', ['exam_schedule_id' => $s->id]) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Add Subject Paper">
                                            <i class="bi bi-plus-circle"></i>
                                        </a>
                                        <a href="{{ route('exam-schedules.edit', $s->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($s->exam_subjects_count === 0)
                                            <form action="{{ route('exam-schedules.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this exam schedule?');">
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
                                <td colspan="7" class="text-center py-4 text-muted">No exam schedules recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($schedules->hasPages())
                <div class="p-3 border-top">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
