@extends('layouts.app')

@push('styles')
<style>
.table-card {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    width: 100% !important;
}
.table-custom { width: 100% !important; margin: 0; min-width: 100%; }
.table-custom thead th {
    background: #f8fafc;
    color: var(--text-dark, #1e293b);
    font-size: 0.82rem;
    font-weight: 700;
    padding: 0.65rem 0.85rem;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.table-custom tbody td {
    padding: 0.75rem 0.85rem;
    font-size: 0.85rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    white-space: nowrap;
}
.table-custom tbody tr:hover td { background-color: #f8fafc; }

@media print {
    body { background: #fff !important; font-size: 11pt; }
    .sidebar, .navbar, .d-print-none, .btn, form, .pagination { display: none !important; }
    .table-card, .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 10px !important; }
    .table { width: 100% !important; }
    .table th, .table td { padding: 4px 6px !important; }
}
</style>
@endpush

@section('content')
<div class="container-fluid py-3">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-wallet2 text-warning me-2"></i>Student Fee Balances
            </h4>
            <small class="text-muted">Monitor invoiced fees, collections, and outstanding student balances across academic years</small>
        </div>
        <div class="d-flex gap-2 mt-2 mt-md-0 d-print-none">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export CSV
            </a>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-printer me-1"></i>Print Report
            </button>
            @if(Route::has('student-invoices.index'))
                <a href="{{ route('student-invoices.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-receipt me-1"></i>Invoices
                </a>
            @endif
            @if(Route::has('student-payments.index'))
                <a href="{{ route('student-payments.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-clock-history me-1"></i>Payments
                </a>
            @endif
            @if(Route::has('student-payments.create'))
                <a href="{{ route('student-payments.create') }}" class="btn btn-sm btn-warning text-dark fw-semibold">
                    <i class="bi bi-cash-coin me-1"></i>Record Payment
                </a>
            @endif
        </div>
    </div>

    {{-- Summary Metrics Cards (Student counts + Financial amounts) --}}
    <div class="row g-2 mb-2">
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-primary bg-opacity-10 p-2 text-primary me-2">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Total Students</div>
                        <div class="fw-bold fs-6 text-dark">{{ number_format($totalStudentsWithInvoices) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-success bg-opacity-10 p-2 text-success me-2">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Paid Students</div>
                        <div class="fw-bold fs-6 text-success">{{ number_format($paidCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-info bg-opacity-10 p-2 text-info me-2">
                        <i class="bi bi-pie-chart-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Partial Students</div>
                        <div class="fw-bold fs-6 text-info">{{ number_format($partialCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-secondary bg-opacity-10 p-2 text-secondary me-2">
                        <i class="bi bi-dash-circle-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Unpaid Students</div>
                        <div class="fw-bold fs-6 text-secondary">{{ number_format($unpaidCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-xl">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-danger bg-opacity-10 p-2 text-danger me-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Overdue Students</div>
                        <div class="fw-bold fs-6 text-danger">{{ number_format($overdueCount) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-secondary bg-opacity-10 p-2 text-secondary me-2">
                        <i class="bi bi-receipt fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Total Invoiced</div>
                        <div class="fw-bold fs-6 text-dark">${{ number_format($totalInvoicedSum, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-success bg-opacity-10 p-2 text-success me-2">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Total Collected</div>
                        <div class="fw-bold fs-6 text-success">${{ number_format($totalPaidSum, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-light">
                <div class="card-body p-2 d-flex align-items-center">
                    <div class="rounded-2 bg-warning bg-opacity-10 p-2 text-warning me-2">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small" style="font-size:0.75rem;">Total Outstanding</div>
                        <div class="fw-bold fs-6 text-dark">${{ number_format($totalOutstanding, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-2 p-md-3">
            <form method="GET" action="{{ route('finance.student-balances') }}" id="filterForm">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Student name, admission #..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ (string)$selectedYearId === (string)$year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->status === 'active' ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Classes</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-sm-3 col-md-1">
                        <select name="section_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Section</option>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}" {{ request('section_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <select name="student_type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Student Type</option>
                            <option value="new" {{ request('student_type') === 'new' ? 'selected' : '' }}>New Student</option>
                            <option value="returning" {{ request('student_type') === 'returning' ? 'selected' : '' }}>Returning</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-3 col-md-2">
                        <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Payment Status</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="overdue" {{ request('payment_status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'academic_year_id', 'class_id', 'section_id', 'student_type', 'payment_status']))
                    <div class="d-flex align-items-center gap-2 mt-2 pt-2 border-top">
                        <span class="small text-muted">Active filters:</span>
                        <a href="{{ route('finance.student-balances') }}" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">
                            <i class="bi bi-x-circle me-1"></i>Clear all filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Main Balances Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0 w-100" style="width: 100%;">
                <thead class="table-light text-muted text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-3" style="width: 40px;">#</th>
                        <th>Student</th>
                        <th>Admission No</th>
                        <th>Class & Section</th>
                        <th>Academic Year</th>
                        <th class="text-center">Type</th>
                        <th class="text-end">Invoiced</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $activeEnrollment = $student->enrollments->first();
                            $className = $activeEnrollment?->classes?->name ?? '—';
                            $sectionName = $activeEnrollment?->section?->name;
                            $academicYearName = $activeEnrollment?->academicYear?->name ?? '—';
                            $studentType = $student->calculated_student_type ?? 'new';
                            $paymentStatus = $student->calculated_payment_status ?? 'unpaid';
                            $statusBadgeClass = match($paymentStatus) {
                                'paid' => 'bg-success text-white',
                                'partial' => 'bg-info text-dark',
                                'overdue' => 'bg-danger text-white',
                                default => 'bg-secondary text-white',
                            };
                        @endphp
                        <tr>
                            <td class="ps-3 text-muted">{{ $students->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light border text-secondary d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('students.show', $student->id) }}" class="text-decoration-none fw-semibold text-dark">
                                            {{ $student->user->name ?? 'Unknown' }}
                                        </a>
                                        @if($student->user->email ?? false)
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $student->user->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><span class="font-monospace text-muted">{{ $student->admission_number }}</span></td>
                            <td>
                                <span class="fw-medium text-dark">{{ $className }}</span>
                                @if($sectionName)
                                    <span class="badge bg-light text-secondary border ms-1">{{ $sectionName }}</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $academicYearName }}</small></td>
                            <td class="text-center">
                                @if($studentType === 'returning')
                                    <span class="badge bg-purple-subtle text-primary border border-primary border-opacity-25" style="font-size:0.7rem;">Returning</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning border-opacity-50" style="font-size:0.7rem;">New</span>
                                @endif
                            </td>
                            <td class="text-end fw-medium text-dark">${{ number_format($student->calculated_invoiced, 2) }}</td>
                            <td class="text-end text-success fw-medium">${{ number_format($student->calculated_paid, 2) }}</td>
                            <td class="text-end fw-bold {{ $student->calculated_balance > 0 ? 'text-danger' : 'text-muted' }}">
                                ${{ number_format($student->calculated_balance, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $statusBadgeClass }} text-uppercase px-2 py-1" style="font-size: 0.68rem; letter-spacing: 0.3px;">
                                    {{ $paymentStatus }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-secondary" title="Student 360 View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(Route::has('student-invoices.index'))
                                        <a href="{{ route('student-invoices.index', ['student_id' => $student->id]) }}" class="btn btn-outline-secondary" title="View Invoices">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                    @endif
                                    @if(Route::has('student-payments.create'))
                                        <a href="{{ route('student-payments.create', ['student_id' => $student->id]) }}" class="btn btn-warning text-dark" title="Record Payment">
                                            <i class="bi bi-cash-coin"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <div class="mb-2"><i class="bi bi-wallet2 fs-1 text-secondary opacity-50"></i></div>
                                <h6 class="fw-semibold text-dark">No student fee records found</h6>
                                <p class="small text-muted mb-0">Try changing your academic year, class filter, or search keywords.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($students->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex flex-wrap justify-content-between align-items-center">
                <small class="text-muted">
                    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
                </small>
                <div>
                    {{ $students->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
