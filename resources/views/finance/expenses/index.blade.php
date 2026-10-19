@extends('layouts.app')

@section('title', 'ចំណាយរបស់សាលា')

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
}
.table-custom tbody tr:hover td { background-color: #f8fafc; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-2.5">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">ចំណាយរបស់សាលា</h1>
            <p class="text-muted small mb-0 mt-1">គ្រប់គ្រងចំណាយប្រតិបត្តិការសាលា វិក្កយបត្រ និងឯកសារបង្កាន់ដៃភ្ជាប់</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('school-expenses.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-plus-lg me-1"></i>កត់ត្រាចំណាយ
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
        <div class="card-body py-2 px-3">
            <form action="{{ route('school-expenses.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="ស្វែងរកប្រភេទចំណាយ...">
                </div>
                <div class="col-12 col-md-3">
                    <select name="expense_category_id" class="form-select form-select-sm">
                        <option value="">គ្រប់ប្រភេទទាំងអស់</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('expense_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm" title="ចាប់ពីថ្ងៃ" placeholder="ចាប់ពីថ្ងៃ">
                </div>
                <div class="col-6 col-md-2">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm" title="ដល់ថ្ងៃ" placeholder="ដល់ថ្ងៃ">
                </div>
                <div class="col-12 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100" title="ច្រោះទិន្នន័យ">
                        <i class="bi bi-filter"></i>
                    </button>
                    @if(request()->hasAny(['search', 'expense_category_id', 'date_from', 'date_to']))
                        <a href="{{ route('school-expenses.index') }}" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Expenses Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0 w-100" style="width: 100%;">
                <thead class="table-light small text-muted">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>កាលបរិច្ឆេទចំណាយ</th>
                        <th>ប្រភេទចំណាយ</th>
                        <th>ការពិពណ៌នា</th>
                        <th class="text-center">បង្កាន់ដៃ/វិក្កយបត្រ</th>
                        <th class="text-end">ចំនួនទឹកប្រាក់</th>
                        <th class="text-end" style="width: 120px;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $exp)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration + ($expenses->currentPage() - 1) * $expenses->perPage() }}</td>
                            <td class="fw-medium text-dark">{{ $exp->expense_date?->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $exp->expense_category?->name ?? 'គ្មានប្រភេទ' }}
                                </span>
                            </td>
                            <td>{{ $exp->description ?: '—' }}</td>
                            <td class="text-center">
                                @if($exp->receipt_path)
                                    <a href="{{ asset('storage/' . $exp->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info p-1 px-2 text-decoration-none" title="មើលឯកសារ">
                                        <i class="bi bi-file-earmark-text me-1"></i> មើលឯកសារ
                                    </a>
                                @else
                                    <span class="text-muted small">គ្មាន</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-danger">${{ number_format($exp->amount, 2) }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('school-expenses.edit', $exp->id) }}" class="btn btn-outline-secondary" title="កែសម្រួល">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="លុប"
                                            onclick="openDeleteExpenseModal('{{ route('school-expenses.destroy', $exp->id) }}', '{{ addslashes($exp->expense_category?->name ?? 'ចំណាយ') }}', '{{ number_format($exp->amount, 2) }}', '{{ $exp->expense_date?->format('d M Y') ?? '—' }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">មិនទាន់មានកំណត់ត្រាចំណាយរបស់សាលានៅឡើយទេ។</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="modalDeleteExpense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deleteExpenseForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបចំណាយ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបកំណត់ត្រាចំណាយនេះមែនទេ?
                    </p>

                    <div class="p-2 rounded-2 text-start mb-2" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.78rem;">
                        <div class="d-flex justify-content-between py-0.5">
                            <span class="text-muted">ប្រភេទចំណាយ៖</span>
                            <strong class="text-dark" id="delExpenseCategory">—</strong>
                        </div>
                        <div class="d-flex justify-content-between py-0.5">
                            <span class="text-muted">ចំនួនទឹកប្រាក់៖</span>
                            <strong class="text-danger" id="delExpenseAmount">$0.00</strong>
                        </div>
                        <div class="d-flex justify-content-between py-0.5">
                            <span class="text-muted">កាលបរិច្ឆេទ៖</span>
                            <span class="text-dark" id="delExpenseDate">—</span>
                        </div>
                    </div>

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
function openDeleteExpenseModal(actionUrl, categoryName, amount, date) {
    const form = document.getElementById('deleteExpenseForm');
    if (form) form.action = actionUrl;

    const catEl = document.getElementById('delExpenseCategory');
    if (catEl) catEl.textContent = categoryName || 'ចំណាយ';

    const amtEl = document.getElementById('delExpenseAmount');
    if (amtEl) amtEl.textContent = '$' + amount;

    const dateEl = document.getElementById('delExpenseDate');
    if (dateEl) dateEl.textContent = date || '—';

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteExpense'));
    modal.show();
}
</script>
@endpush
@endsection
