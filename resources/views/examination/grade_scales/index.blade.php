@extends('layouts.app')

@section('title', 'Grade Scales')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Grade Scales</h1>
            <p class="text-muted small mb-0 mt-1">Configure academic grading bands, letter marks, and GPA points</p>
        </div>
        <a href="{{ route('grade-scales.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
            <i class="bi bi-plus-lg me-1"></i> Add Grade Band
        </a>
    </div>

    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Grade Label</th>
                            <th>Scale Name</th>
                            <th>Min Percentage</th>
                            <th>Max Percentage</th>
                            <th>GPA Point</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gradeScales as $scale)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge {{ in_array($scale->grade, ['A+', 'A', 'B+']) ? 'bg-success' : (in_array($scale->grade, ['B', 'C+']) ? 'bg-primary' : ($scale->grade === 'F' ? 'bg-danger' : 'bg-warning text-dark')) }} fs-6">
                                        {{ $scale->grade }}
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark">{{ $scale->name }}</td>
                                <td>{{ number_format($scale->min_percentage, 2) }}%</td>
                                <td>{{ number_format($scale->max_percentage, 2) }}%</td>
                                <td><strong>{{ number_format($scale->gpa_point ?? 0, 2) }}</strong></td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('grade-scales.edit', $scale->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('grade-scales.destroy', $scale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this grade scale?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No grade scales defined yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
