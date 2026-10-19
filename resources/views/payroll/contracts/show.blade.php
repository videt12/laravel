@extends('layouts.app')
@section('title', 'Contract {{ $employee_contract->contract_number }}')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('employee-contracts.index') }}" class="text-decoration-none text-muted">Contracts</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">{{ $employee_contract->contract_number }}</li>
    </ol>
</nav>
@endsection
@push('styles')
<style>
.header-card { background:linear-gradient(135deg,#b45309 0%,#d97706 100%); border-radius:var(--radius-lg,12px); padding:1.6rem 2rem; margin-bottom:1.5rem; color:#fff; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.info-card { background:#fff; border:1px solid var(--border-soft,#e5e7eb); border-radius:var(--radius-lg,12px); padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,.04); }
.section-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6b7280; margin-bottom:1rem; }
.info-row { display:flex; justify-content:space-between; padding:.45rem 0; font-size:.875rem; border-bottom:1px solid #f3f4f6; }
.info-row:last-child { border-bottom:none; }
.info-label { color:#6b7280; }
.info-value { font-weight:600; text-align:right; }
</style>
@endpush
@section('content')
<div class="header-card">
    <div>
        <h1 style="font-size:1.4rem;font-weight:800;margin:0 0 .25rem;">{{ $employee_contract->contract_number }}</h1>
        <div style="opacity:.85;font-size:.85rem;">{{ $employee_contract->employee?->user?->name ?? 'N/A' }} ({{ $employee_contract->employee?->employee_code }})</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('employee-contracts.print', $employee_contract) }}" target="_blank" class="btn btn-warning btn-sm fw-semibold text-white" style="background:#e8a838; border-color:#d97706;">
            <i class="bi bi-printer me-1"></i>បោះពុម្ពកិច្ចសន្យា (Print Contract)
        </a>
        <a href="{{ route('employee-contracts.edit', $employee_contract) }}" class="btn btn-light btn-sm fw-semibold">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @if($employee_contract->isActive())
        <form method="POST" action="{{ route('employee-contracts.renew', $employee_contract) }}" onsubmit="return confirm('Renew this contract?')">
            @csrf
            <button class="btn btn-light btn-sm fw-semibold"><i class="bi bi-arrow-repeat me-1"></i>Renew</button>
        </form>
        <a href="{{ route('payrolls.create', ['employee_id' => $employee_contract->employee_id]) }}" class="btn btn-light btn-sm fw-semibold">
            <i class="bi bi-cash-coin me-1"></i>Generate Payroll
        </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-5">
        <div class="info-card mb-4">
            <div class="section-title">Contract Details</div>
            <div class="info-row"><span class="info-label">Contract #</span><span class="info-value">{{ $employee_contract->contract_number }}</span></div>
            <div class="info-row"><span class="info-label">ប្រភេទ</span><span class="info-value">{{ $employee_contract->contract_type_kh }}</span></div>
            <div class="info-row">
                <span class="info-label">Status</span>
                @php $stMap = ['active'=>'bg-success-subtle text-success-emphasis','draft'=>'bg-secondary-subtle text-secondary-emphasis','expired'=>'bg-danger-subtle text-danger-emphasis','terminated'=>'bg-danger-subtle text-danger-emphasis','renewed'=>'bg-info-subtle text-info-emphasis']; @endphp
                <span class="badge {{ $stMap[$employee_contract->status] ?? '' }}">{{ ucfirst($employee_contract->status) }}</span>
            </div>
            <div class="info-row"><span class="info-label">Start Date</span><span class="info-value">{{ $employee_contract->start_date->format('d M Y') }}</span></div>
            <div class="info-row"><span class="info-label">End Date</span><span class="info-value">{{ $employee_contract->end_date?->format('d M Y') ?? 'Ongoing' }}</span></div>
            <div class="info-row"><span class="info-label">Agreed Salary</span><span class="info-value">{{ $employee_contract->salary ? number_format($employee_contract->salary, 2) : '—' }}</span></div>
            <div class="info-row"><span class="info-label">Work Hours/Week</span><span class="info-value">{{ $employee_contract->work_hours_per_week ?? '—' }}</span></div>
            <div class="info-row"><span class="info-label">Salary Structure</span><span class="info-value">{{ $employee_contract->salaryStructure?->name ?? '—' }}</span></div>
            @if($employee_contract->notes)
            <div class="mt-2 p-2 bg-light rounded" style="font-size:.82rem;"><strong>Notes:</strong> {{ $employee_contract->notes }}</div>
            @endif
        </div>

        @if($employee_contract->document_path)
        <div class="info-card">
            <div class="section-title">Contract Document</div>
            <a href="{{ asset('storage/' . $employee_contract->document_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i>View Document
            </a>
        </div>
        @endif
    </div>

    <div class="col-lg-7">
        @if($employee_contract->salaryStructure)
        <div class="info-card mb-4">
            <div class="section-title">Salary Structure: {{ $employee_contract->salaryStructure->name }}</div>
            <div class="info-row"><span class="info-label">Base Salary</span><span class="info-value">{{ number_format($employee_contract->salaryStructure->base_salary, 2) }}</span></div>
            <div class="info-row"><span class="info-label">Effective Salary (this contract)</span><span class="info-value fw-bold" style="color:#1e40af;">{{ number_format($employee_contract->effectiveSalary(), 2) }}</span></div>
            <div class="mt-3">
                @foreach($employee_contract->salaryStructure->salaryComponents->where('is_active', true) as $comp)
                <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:.85rem;">
                    <span>
                        @if($comp->type === 'earning')
                            <span class="badge bg-success-subtle text-success-emphasis" style="font-size:.7rem;">+</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger-emphasis" style="font-size:.7rem;">−</span>
                        @endif
                        {{ $comp->name }}
                    </span>
                    <span class="text-muted">
                        @if($comp->calculation_type === 'percentage')
                            {{ number_format($comp->percentage, 2) }}% of basic
                        @else
                            Fixed: {{ number_format($comp->amount, 2) }}
                        @endif
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="info-card">
            <div class="section-title">Payroll History</div>
            @php $payrolls = $employee_contract->employee?->payrolls()->orderByDesc('year')->orderByDesc('month')->take(5)->get(); @endphp
            @if($payrolls && $payrolls->isNotEmpty())
                @foreach($payrolls as $pr)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <div>
                        <a href="{{ route('payrolls.show', $pr) }}" class="fw-medium text-decoration-none">{{ $pr->payroll_number }}</a>
                        <div class="small text-muted">{{ $pr->period }}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">{{ number_format($pr->net_salary, 2) }}</div>
                        <span class="badge {{ $pr->status === 'paid' ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}" style="font-size:.7rem;">{{ ucfirst($pr->status) }}</span>
                    </div>
                </div>
                @endforeach
                <a href="{{ route('payrolls.index', ['search' => $employee_contract->employee?->employee_code]) }}" class="d-block text-center text-primary mt-2" style="font-size:.82rem;">View all payrolls →</a>
            @else
                <div class="text-muted text-center py-3" style="font-size:.85rem;">No payrolls generated yet.</div>
            @endif
        </div>
    </div>
</div>
@endsection
