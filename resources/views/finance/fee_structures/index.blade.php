@extends('layouts.app')

@section('title', 'រចនាសម្ព័ន្ធកម្រៃសេវា — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ហិរញ្ញវត្ថុ</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">រចនាសម្ព័ន្ធកម្រៃសេវា</li>
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
            <h1 class="h3 fw-bold text-dark mb-0">រចនាសម្ព័ន្ធកម្រៃសេវា</h1>
            <p class="text-muted small mb-0 mt-1">កំណត់គោលការណ៍តម្លៃ៖ កាលវិភាគកម្រៃសេវាសម្រាប់ទូទាំងសាលា និងតាមថ្នាក់នីមួយៗ</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('fee-structures.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-plus-lg me-1"></i>បន្ថែមរចនាសម្ព័ន្ធកម្រៃ
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
        <div class="card-body py-2 px-3">
            <form action="{{ route('fee-structures.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <select name="academic_year_id" class="form-select form-select-sm">
                        <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_active ? '(សកម្ម)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="fee_category_id" class="form-select form-select-sm">
                        <option value="">ប្រភេទកម្រៃទាំងអស់</option>
                        @foreach($feeCategories as $cat)
                            <option value="{{ $cat->id }}" {{ request('fee_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">វិសាលភាពទាំងអស់ (ទូទាំងសាលា និងតាមថ្នាក់)</option>
                        <option value="institution" {{ request('class_id') === 'institution' ? 'selected' : '' }}>ទូទាំងសាលា (គ្រប់ថ្នាក់ទាំងអស់)</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                ថ្នាក់៖ {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm text-white w-100" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                        <i class="bi bi-filter me-1"></i> ចម្រាញ់
                    </button>
                    @if(request()->hasAny(['academic_year_id', 'fee_category_id', 'class_id']))
                        <a href="{{ route('fee-structures.index') }}" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Fee Structures Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0 w-100" style="width: 100%;">
                <thead class="table-light small text-muted">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>ប្រភេទកម្រៃសេវា</th>
                        <th>ឆ្នាំសិក្សា</th>
                        <th>វិសាលភាព</th>
                        <th class="text-end">ចំនួនទឹកប្រាក់</th>
                        <th class="text-center">វិក្កយបត្រសកម្ម</th>
                        <th class="text-end" style="width: 140px;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feeStructures as $fs)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration + ($feeStructures->currentPage() - 1) * $feeStructures->perPage() }}</td>
                            <td class="fw-bold text-dark">{{ $fs->fee_category?->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $fs->academic_year?->name ?? '—' }}
                                    @if($fs->academic_year?->is_active)
                                        <i class="bi bi-check-circle-fill text-success ms-1" title="សកម្ម"></i>
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if(is_null($fs->class_id))
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                        <i class="bi bi-buildings me-1"></i> ទូទាំងសាលា
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <i class="bi bi-person-video3 me-1"></i> {{ $fs->class?->name ?? "ថ្នាក់ #{$fs->class_id}" }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="fw-bold text-dark">${{ number_format($fs->amount, 2) }}</div>
                                <div class="text-muted" style="font-size:.73rem;">{{ number_format(round($fs->amount * 4100, -2)) }} ៛</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $fs->student_invoices_count }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('fee-structures.edit', $fs->id) }}" class="btn btn-outline-secondary" title="កែសម្រួល">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($fs->student_invoices_count == 0)
                                        <button type="button" class="btn btn-outline-danger" title="លុប"
                                                onclick="openDeleteFeeStructureModal('{{ route('fee-structures.destroy', $fs->id) }}', '{{ addslashes($fs->name) }}', '{{ number_format($fs->amount, 2) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">មិនទាន់មានរចនាសម្ព័ន្ធកម្រៃសេវាត្រូវបានកំណត់នៅឡើយទេ។</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($feeStructures->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $feeStructures->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="modalDeleteFeeStructure" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deleteFeeStructureForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3.5 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2.5" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបរចនាសម្ព័ន្ធកម្រៃ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបរចនាសម្ព័ន្ធកម្រៃសេវា <strong id="delFeeStructureName" class="text-dark"></strong> មែនទេ?
                    </p>
                    <div class="p-2 rounded-2 text-start mb-2" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.78rem;">
                        <div class="d-flex justify-content-between py-0.5">
                            <span class="text-muted">តម្លៃកម្រៃ៖</span>
                            <strong class="text-danger" id="delFeeStructureAmount">$0.00</strong>
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
function openDeleteFeeStructureModal(actionUrl, name, amount) {
    const form = document.getElementById('deleteFeeStructureForm');
    if (form) form.action = actionUrl;

    const nameEl = document.getElementById('delFeeStructureName');
    if (nameEl) nameEl.textContent = name || '';

    const amtEl = document.getElementById('delFeeStructureAmount');
    if (amtEl) amtEl.textContent = '$' + amount;

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteFeeStructure'));
    modal.show();
}
</script>
@endpush
@endsection
