@extends('layouts.app')

@section('title', 'បញ្ជីអ្នកខ្ចីសៀវភៅ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">បញ្ជីអ្នកខ្ចីសៀវភៅ</h1>
            <p class="text-muted small mb-0 mt-1">គណនីបណ្ណាល័យរបស់សិស្ស និងបុគ្គលិក ការខ្ចីសកម្ម និងប្រវត្តិខ្ចីសៀវភៅ</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('library.issues.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-box-arrow-right me-1"></i> ខ្ចីសៀវភៅ
            </a>
            <a href="{{ route('library.issues.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-journal-text me-1"></i> បញ្ជីការខ្ចី
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('library.borrowers.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="ស្វែងរកតាមឈ្មោះ អ៊ីមែល អត្តលេខសិស្ស កូដបុគ្គលិក..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">ច្រោះទិន្នន័យ</button>
                    @if(request('search'))
                        <a href="{{ route('library.borrowers.index') }}" class="btn btn-sm btn-outline-secondary">កំណត់ឡើងវិញ</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Borrowers Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">ព័ត៌មានអ្នកខ្ចី</th>
                            <th>តួនាទី / ផ្នែក</th>
                            <th>លេខសម្គាល់ (ID)</th>
                            <th>កំពុងខ្ចី</th>
                            <th>ហួសកាលកំណត់</th>
                            <th>ខ្ចីសរុប</th>
                            <th class="pe-3 text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowers as $borrower)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">
                                        <a href="{{ route('library.borrowers.show', $borrower) }}" class="text-decoration-none">
                                            {{ $borrower->name }}
                                        </a>
                                    </div>
                                    <span class="text-muted small">{{ $borrower->email }}</span>
                                </td>
                                <td>
                                    @if($borrower->student)
                                        <span class="badge bg-info-subtle text-info">សិស្ស</span>
                                    @elseif($borrower->employee)
                                        <span class="badge bg-secondary-subtle text-secondary">បុគ្គលិក / គ្រូ</span>
                                    @else
                                        <span class="badge bg-light text-dark">អ្នកប្រើប្រាស់</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrower->student)
                                        <span class="font-monospace small">អត្តលេខ៖ {{ $borrower->student->admission_number }}</span>
                                    @elseif($borrower->employee)
                                        <span class="font-monospace small">កូដ៖ {{ $borrower->employee->employee_code }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrower->active_issues_count > 0)
                                        <span class="badge bg-primary rounded-pill">{{ $borrower->active_issues_count }} កំពុងខ្ចី</span>
                                    @else
                                        <span class="text-muted small">0 កំពុងខ្ចី</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrower->overdue_issues_count > 0)
                                        <span class="badge bg-danger rounded-pill">{{ $borrower->overdue_issues_count }} ហួសកំណត់</span>
                                    @else
                                        <span class="text-muted small">0</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $borrower->total_issues_count }}</span>
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('library.borrowers.show', $borrower) }}" class="btn btn-xs btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> មើលប្រវត្តិ
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">មិនទាន់មានគណនីអ្នកខ្ចីសៀវភៅនៅឡើយទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($borrowers->hasPages())
                <div class="p-3 border-top">
                    {{ $borrowers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
