@extends('layouts.app')

@section('title', 'វិធីសាស្ត្របង់ប្រាក់ — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">វិធីសាស្ត្របង់ប្រាក់</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.table-card {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { width: 100% !important; margin: 0; }
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
}
.table-custom tbody tr:hover td { background-color: #f8fafc; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-2.5">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">វិធីសាស្ត្របង់ប្រាក់</h1>
            <p class="text-muted small mb-0 mt-1">កំណត់បណ្តាញទូទាត់ប្រាក់ដែលទទួលយក (សាច់ប្រាក់, ផ្ទេរតាមធនាគារ, កាត, ធនាគារចល័ត)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('payment-methods.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-plus-lg me-1"></i>បន្ថែមវិធីសាស្ត្របង់ប្រាក់
            </a>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
        <div class="card-body py-2 px-3">
            <form action="{{ route('payment-methods.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-10">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="ស្វែងរកឈ្មោះវិធីសាស្ត្របង់ប្រាក់ ឬព័ត៌មានលម្អិត...">
                </div>
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white w-100" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                        <i class="bi bi-search me-1"></i> ស្វែងរក
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('payment-methods.index') }}" class="btn btn-sm btn-light border" title="សម្អាតការស្វែងរក">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Payment Methods Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0 w-100" style="width: 100%;">
                <thead class="table-light small text-muted">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>ឈ្មោះវិធីសាស្ត្រ</th>
                        <th>ការពិពណ៌នា</th>
                        <th class="text-center">ការបង់ប្រាក់ដែលបានកត់ត្រា</th>
                        <th class="text-end" style="width: 140px;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentMethods as $pm)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration + ($paymentMethods->currentPage() - 1) * $paymentMethods->perPage() }}</td>
                            <td class="fw-bold text-dark">
                                <i class="bi bi-credit-card-2-front me-2 text-primary"></i>
                                {{ $pm->name }}
                            </td>
                            <td class="text-muted small">{{ $pm->description ?: '—' }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $pm->student_payments_count }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('payment-methods.edit', $pm->id) }}" class="btn btn-outline-secondary" title="កែសម្រួល">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($pm->student_payments_count == 0)
                                        <button type="button" class="btn btn-outline-danger" title="លុប"
                                                onclick="openDeletePaymentMethodModal('{{ route('payment-methods.destroy', $pm->id) }}', '{{ addslashes($pm->name) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">មិនមានវិធីសាស្ត្របង់ប្រាក់ត្រូវបានកំណត់នៅឡើយទេ។</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paymentMethods->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $paymentMethods->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="modalDeletePaymentMethod" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deletePaymentMethodForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3.5 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2.5" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបវិធីសាស្ត្រ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបវិធីសាស្ត្របង់ប្រាក់ <strong id="delPaymentMethodName" class="text-dark"></strong> មែនទេ?
                    </p>
                    <div class="text-danger" style="font-size: 0.73rem;">
                        <i class="bi bi-exclamation-circle me-1"></i>សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានឡើយ។
                    </div>
                </div>
                <div class="modal-footer border-top px-3 py-2 bg-light d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 4px; font-size: 0.82rem;" data-bs-dismiss="modal">
                        បោះបង់
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger px-3.5" style="border-radius: 4px; font-size: 0.82rem;">
                        <i class="bi bi-trash me-1"></i>លុបចេញ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeletePaymentMethodModal(actionUrl, name) {
    const form = document.getElementById('deletePaymentMethodForm');
    if (form) form.action = actionUrl;

    const nameEl = document.getElementById('delPaymentMethodName');
    if (nameEl) nameEl.textContent = name || '';

    const modal = new bootstrap.Modal(document.getElementById('modalDeletePaymentMethod'));
    modal.show();
}
</script>
@endpush
@endsection
