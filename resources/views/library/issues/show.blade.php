@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតនៃការខ្ចី #' . $issue->id)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold text-dark mb-0">ការខ្ចីសៀវភៅ #{{ $issue->id }}</h1>
                @if($issue->is_returned)
                    <span class="badge bg-success-subtle text-success">បានសងរួច</span>
                @elseif($issue->is_overdue)
                    <span class="badge bg-danger text-white">ហួសកាលកំណត់ ({{ $issue->overdue_days }} ថ្ងៃ)</span>
                @else
                    <span class="badge bg-primary-subtle text-primary">កំពុងខ្ចី</span>
                @endif
            </div>
            <p class="text-muted small mb-0 mt-1">កំណត់ត្រាប្រតិបត្តិការ និងប្រវត្តិខ្ចី-សង</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if(!$issue->is_returned)
                <a href="{{ route('library.returns.create', $issue) }}" class="btn btn-sm btn-success">
                    <i class="bi bi-box-arrow-in-left me-1"></i> សងសៀវភៅ
                </a>
            @endif
            <a href="{{ route('library.issues.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅការខ្ចីសៀវភៅ
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left: Book & Copy Info --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white h-100 p-4">
                <h5 class="fw-bold text-dark mb-3">ព័ត៌មានសៀវភៅ & ច្បាប់ចម្លង</h5>
                <div class="mb-3">
                    <div class="text-muted small">ចំណងជើងសៀវភៅ</div>
                    <div class="h5 fw-bold text-dark mb-0">
                        <a href="{{ route('library.books.show', $issue->library_book_copy->library_book_id) }}" class="text-decoration-none">
                            {{ $issue->library_book_copy->library_book->title ?? '—' }}
                        </a>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">លេខកូដសៀវភៅ (Accession)</div>
                        <div class="fw-bold font-monospace text-dark">{{ $issue->library_book_copy->accession_number ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ស្ថានភាពច្បាប់សៀវភៅ</div>
                        <div>
                            @if($issue->library_book_copy->status === 'available')
                                <span class="badge bg-success-subtle text-success">នៅសល់</span>
                            @elseif($issue->library_book_copy->status === 'issued')
                                <span class="badge bg-warning-subtle text-warning">បានខ្ចី</span>
                            @elseif($issue->library_book_copy->status === 'lost')
                                <span class="badge bg-secondary-subtle text-secondary">បាត់បង់</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">ខូចខាត</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">អ្នកនិពន្ធ</div>
                        <div class="fw-medium text-dark">{{ $issue->library_book_copy->library_book->author ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">រោងពុម្ព</div>
                        <div class="fw-medium text-dark">{{ $issue->library_book_copy->library_book->publisher ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ISBN</div>
                        <div class="fw-medium font-monospace text-dark">{{ $issue->library_book_copy->library_book->isbn ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ប្រភេទសៀវភៅ</div>
                        <div class="fw-medium text-dark">{{ $issue->library_book_copy->library_book->library_category->name ?? 'គ្មានប្រភេទ' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Borrower & Loan Details --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white h-100 p-4">
                <h5 class="fw-bold text-dark mb-3">ព័ត៌មានអ្នកខ្ចី & ការខ្ចី</h5>
                <div class="mb-3">
                    <div class="text-muted small">ឈ្មោះអ្នកខ្ចី</div>
                    <div class="h5 fw-bold text-dark mb-0">
                        <a href="{{ route('library.borrowers.show', $issue->user_id) }}" class="text-decoration-none">
                            {{ $issue->user->name ?? '—' }}
                        </a>
                    </div>
                    <div class="text-muted small">{{ $issue->user->email ?? '' }}</div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="text-muted small">ប្រភេទអ្នកខ្ចី</div>
                        <div>
                            @if($issue->user && $issue->user->student)
                                <span class="badge bg-info-subtle text-info">សិស្ស</span>
                                <span class="small ms-1">អត្តលេខ៖ {{ $issue->user->student->admission_number }}</span>
                            @elseif($issue->user && $issue->user->employee)
                                <span class="badge bg-secondary-subtle text-secondary">បុគ្គលិក / គ្រូ</span>
                                <span class="small ms-1">កូដ៖ {{ $issue->user->employee->employee_code }}</span>
                            @else
                                <span class="badge bg-light text-dark">អ្នកប្រើប្រាស់</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ស្ថានភាពគណនី</div>
                        <div>
                            <span class="badge bg-success-subtle text-success">សកម្ម</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ថ្ងៃខ្ចី</div>
                        <div class="fw-semibold text-dark">{{ $issue->issue_date ? $issue->issue_date->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ថ្ងៃកំណត់សង</div>
                        <div class="fw-semibold text-dark">{{ $issue->due_date ? $issue->due_date->format('d/m/Y') : 'គ្មានថ្ងៃកំណត់' }}</div>
                    </div>
                </div>

                {{-- Return Information Box --}}
                <div class="p-3 rounded-3 mt-auto {{ $issue->is_returned ? 'bg-success-subtle' : ($issue->is_overdue ? 'bg-danger-subtle' : 'bg-light') }}">
                    @if($issue->is_returned)
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-bold text-success d-block"><i class="bi bi-check-circle me-1"></i> បានសងរួច</span>
                                <span class="small text-muted">បានសងនៅថ្ងៃ៖ {{ $issue->libraryReturn->return_date ? $issue->libraryReturn->return_date->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="text-end">
                                <span class="text-muted small d-block">ចំនួនប្រាក់ពិន័យ</span>
                                <span class="h5 fw-bold text-dark mb-0">${{ number_format($issue->libraryReturn->fine_amount, 2) }}</span>
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                @if($issue->is_overdue)
                                    <span class="fw-bold text-danger d-block"><i class="bi bi-exclamation-circle me-1"></i> ហួសកាលកំណត់ចំនួន {{ $issue->overdue_days }} ថ្ងៃ</span>
                                    <span class="small text-danger">កាលបរិច្ឆេទកំណត់៖ {{ $issue->due_date ? $issue->due_date->format('d/m/Y') : '-' }}</span>
                                @else
                                    <span class="fw-bold text-dark d-block">កំពុងស្ថិតក្នុងការខ្ចី</span>
                                    <span class="small text-muted">នៅសល់ {{ now()->diffInDays($issue->due_date, false) }} ថ្ងៃទៀត</span>
                                @endif
                            </div>
                            <a href="{{ route('library.returns.create', $issue) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-box-arrow-in-left me-1"></i> សងសៀវភៅ
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
