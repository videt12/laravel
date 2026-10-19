@extends('layouts.app')

@section('title', 'ការខ្ចី-សងសៀវភៅ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">ការខ្ចី-សងសៀវភៅ</h1>
            <p class="text-muted small mb-0 mt-1">តាមដានប្រតិបត្តិការខ្ចីសៀវភៅ ការសងត្រឡប់មកវិញ និងការយឺតយ៉ាវ</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('library.issues.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-box-arrow-right me-1"></i> កត់ត្រាការខ្ចីសៀវភៅ
            </a>
            <a href="{{ route('library.returns.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-clock-history me-1"></i> ប្រវត្តិសង & ផាកពិន័យ
            </a>
        </div>
    </div>

    {{-- Filter Bar & Status Tabs --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                {{-- Status Tabs --}}
                <ul class="nav nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3 {{ $status === 'all' ? 'active' : 'text-muted' }}" href="{{ route('library.issues.index', ['status' => 'all', 'search' => request('search')]) }}">
                            ទាំងអស់ <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $allCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3 {{ $status === 'active' ? 'active' : 'text-muted' }}" href="{{ route('library.issues.index', ['status' => 'active', 'search' => request('search')]) }}">
                            កំពុងខ្ចី <span class="badge bg-primary-subtle text-primary ms-1">{{ $activeCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3 {{ $status === 'overdue' ? 'active bg-danger' : 'text-danger' }}" href="{{ route('library.issues.index', ['status' => 'overdue', 'search' => request('search')]) }}">
                            ហួសកាលកំណត់ <span class="badge bg-danger-subtle text-danger ms-1">{{ $overdueCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3 {{ $status === 'returned' ? 'active' : 'text-muted' }}" href="{{ route('library.issues.index', ['status' => 'returned', 'search' => request('search')]) }}">
                            បានសងរួច <span class="badge bg-success-subtle text-success ms-1">{{ $returnedCount }}</span>
                        </a>
                    </li>
                </ul>

                {{-- Search Bar --}}
                <form method="GET" action="{{ route('library.issues.index') }}" class="d-flex align-items-center gap-1.5" style="max-width: 440px;">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text bg-light border-end-0 pe-2"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 border-end-0 ps-1" 
                               placeholder="វាយបញ្ចូល ID / អត្តលេខ, ឈ្មោះ, កូដសៀវភៅ..." 
                               value="{{ request('search') }}" style="font-size: 0.8125rem;">
                        <button type="submit" class="btn btn-primary px-3 text-nowrap" style="font-size: 0.8125rem;">
                            <i class="bi bi-search me-1"></i>ស្វែងរក
                        </button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('library.issues.index', ['status' => $status]) }}" class="btn btn-sm btn-outline-secondary text-nowrap py-1 px-2" style="font-size: 0.78rem;">
                            <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- Issues Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th>ចំណងជើងសៀវភៅ & លេខកូដ</th>
                            <th>ព័ត៌មានលម្អិតអ្នកខ្ចី</th>
                            <th>ថ្ងៃខ្ចី</th>
                            <th>ថ្ងៃកំណត់សង</th>
                            <th>ស្ថានភាពនៃការខ្ចី</th>
                            <th class="pe-3 text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $issue)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $issue->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $issue->library_book_copy->library_book->title ?? '—' }}</div>
                                    <span class="badge bg-light text-dark font-monospace border">
                                        {{ $issue->library_book_copy->accession_number ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $issue->user->name ?? '—' }}</div>
                                    <div class="text-muted small">
                                        @if($issue->user && $issue->user->student)
                                            <span class="badge bg-info-subtle text-info">សិស្ស</span>
                                            អត្តលេខ៖ {{ $issue->user->student->admission_number }}
                                        @elseif($issue->user && $issue->user->employee)
                                            <span class="badge bg-secondary-subtle text-secondary">បុគ្គលិក/គ្រូ</span>
                                            កូដ៖ {{ $issue->user->employee->employee_code }}
                                        @else
                                            <span class="text-muted">{{ $issue->user->email ?? '' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $issue->issue_date ? $issue->issue_date->format('d/m/Y') : '-' }}</td>
                                <td>{{ $issue->due_date ? $issue->due_date->format('d/m/Y') : 'គ្មានថ្ងៃកំណត់' }}</td>
                                <td>
                                    @if($issue->is_returned)
                                        <span class="badge bg-success-subtle text-success">
                                            បានសងរួច ({{ $issue->libraryReturn->return_date ? $issue->libraryReturn->return_date->format('d/m/Y') : '' }})
                                        </span>
                                    @elseif($issue->is_overdue)
                                        <span class="badge bg-danger text-white">
                                            ហួសកំណត់ ({{ $issue->overdue_days }}ថ្ងៃ)
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary">កំពុងខ្ចី</span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('library.issues.show', $issue) }}" class="btn btn-outline-secondary" title="មើលព័ត៌មានលម្អិត">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(!$issue->is_returned)
                                            <a href="{{ route('library.returns.create', $issue) }}" class="btn btn-outline-success" title="សងសៀវភៅ">
                                                <i class="bi bi-box-arrow-in-left me-1"></i> សងសៀវភៅ
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">មិនទាន់មានកំណត់ត្រាខ្ចីសៀវភៅនៅឡើយទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($issues->hasPages())
                <div class="p-3 border-top">
                    {{ $issues->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
