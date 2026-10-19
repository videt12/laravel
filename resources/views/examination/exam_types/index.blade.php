@extends('layouts.app')

@section('title', 'Exam Types')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Exam Types</h1>
            <p class="text-muted small mb-0 mt-1">Manage assessment types and examination categories</p>
        </div>
        <div>
            <a href="{{ route('exam-types.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-plus-lg me-1"></i> Add Exam Type
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Linked Exams</th>
                            <th class="text-end pe-4" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examTypes as $type)
                            <tr>
                                <td class="ps-4 text-muted">#{{ $type->id }}</td>
                                <td class="fw-semibold text-dark">{{ $type->name }}</td>
                                <td class="text-muted">{{ $type->description ?: '—' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $type->exams_count }} exam{{ $type->exams_count === 1 ? '' : 's' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('exam-types.edit', $type->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($type->exams_count === 0)
                                            <form action="{{ route('exam-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this exam type?');">
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
                                <td colspan="5" class="text-center py-4 text-muted">No exam types defined yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($examTypes->hasPages())
                <div class="p-3 border-top">
                    {{ $examTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
