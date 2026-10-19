@extends('layouts.app')

@section('title', $book->title . ' — ព័ត៌មានលម្អិតសៀវភៅ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold text-dark mb-0">{{ $book->title }}</h1>
                @if($book->library_category)
                    <span class="badge bg-light text-dark border">{{ $book->library_category->name }}</span>
                @endif
            </div>
            <p class="text-muted small mb-0 mt-1">ព័ត៌មានកាតាឡុក ចំនួនច្បាប់ចម្លងជាក់ស្តែងក្នុងស្តុក និងប្រវត្តិខ្ចីសៀវភៅ</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);" data-bs-toggle="modal" data-bs-target="#addCopyModal">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមច្បាប់ចម្លង
            </button>
            <a href="{{ route('library.books.edit', $book) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> កែសម្រួលសៀវភៅ
            </a>
            <a href="{{ route('library.books.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅបញ្ជីសៀវភៅ
            </a>
        </div>
    </div>

    {{-- Metadata & Inventory Summary Row --}}
    <div class="row g-4 mb-4">
        {{-- Metadata Card --}}
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white h-100 p-4">
                <h5 class="fw-bold text-dark mb-3">ព័ត៌មានសៀវភៅ</h5>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">អ្នកនិពន្ធ</div>
                        <div class="fw-semibold text-dark">{{ $book->author ?? 'មិនបានបញ្ជាក់' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">រោងពុម្ព</div>
                        <div class="fw-semibold text-dark">{{ $book->publisher ?? 'មិនបានបញ្ជាក់' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ISBN</div>
                        <div class="fw-semibold font-monospace text-dark">{{ $book->isbn ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">ប្រភេទសៀវភៅ</div>
                        <div class="fw-semibold text-dark">{{ $book->library_category->name ?? 'គ្មានប្រភេទ' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Inventory Breakdown Card --}}
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white h-100 p-4">
                <h5 class="fw-bold text-dark mb-3">សង្ខេបចំនួនច្បាប់សៀវភៅ</h5>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="p-3 bg-light rounded-3 text-center">
                            <span class="text-muted small d-block">ច្បាប់សរុប</span>
                            <span class="h4 fw-bold text-dark mb-0">{{ $book->total_copies_count }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-success-subtle rounded-3 text-center">
                            <span class="text-success small d-block">នៅសល់</span>
                            <span class="h4 fw-bold text-success mb-0">{{ $book->available_copies_count }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-warning-subtle rounded-3 text-center">
                            <span class="text-warning small d-block">បានខ្ចី</span>
                            <span class="h4 fw-bold text-warning mb-0">{{ $book->issued_copies_count }}</span>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="p-2 bg-secondary-subtle rounded-3 text-center">
                            <span class="text-muted small">បាត់បង់៖ <strong>{{ $book->lost_copies_count }}</strong></span>
                        </div>
                    </div>
                    <div class="col-6 mt-2">
                        <div class="p-2 bg-danger-subtle rounded-3 text-center">
                            <span class="text-danger small">ខូចខាត៖ <strong>{{ $book->damaged_copies_count }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Physical Copies Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between pt-3 px-3 pb-0">
            <h5 class="fw-bold text-dark mb-0">បញ្ជីច្បាប់សៀវភៅក្នុងស្តុក ({{ $copies->total() }})</h5>
            <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCopyModal">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមច្បាប់ចម្លង
            </button>
        </div>
        <div class="card-body px-0 pb-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th>លេខកូដសម្គាល់សៀវភៅ (Barcode/Accession)</th>
                            <th>ស្ថានភាព</th>
                            <th>ការខ្ចីបច្ចុប្បន្ន / អ្នកខ្ចី</th>
                            <th class="pe-3 text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($copies as $copy)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $copy->id }}</td>
                                <td>
                                    <span class="fw-bold font-monospace text-dark fs-6">{{ $copy->accession_number }}</span>
                                </td>
                                <td>
                                    @if($copy->status === 'available')
                                        <span class="badge bg-success-subtle text-success">នៅសល់</span>
                                    @elseif($copy->status === 'issued')
                                        <span class="badge bg-warning-subtle text-warning">បានខ្ចី</span>
                                    @elseif($copy->status === 'lost')
                                        <span class="badge bg-secondary-subtle text-secondary">បាត់បង់</span>
                                    @elseif($copy->status === 'damaged')
                                        <span class="badge bg-danger-subtle text-danger">ខូចខាត</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $latestIssue = $copy->latestIssue;
                                    @endphp
                                    @if($latestIssue && !$latestIssue->is_returned)
                                        <div class="fw-medium text-dark">{{ $latestIssue->user->name ?? 'អ្នកខ្ចី' }}</div>
                                        <span class="text-muted small">ថ្ងៃកំណត់សង៖ {{ $latestIssue->due_date ? $latestIssue->due_date->format('d/m/Y') : '-' }}</span>
                                        @if($latestIssue->is_overdue)
                                            <span class="badge bg-danger ms-1">ហួសកាលកំណត់</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="btn-group btn-group-sm">
                                        @if($copy->status === 'available')
                                            <a href="{{ route('library.issues.create', ['copy_id' => $copy->id]) }}" class="btn btn-xs btn-outline-success" title="កត់ត្រាការខ្ចីច្បាប់នេះ">
                                                <i class="bi bi-box-arrow-right me-1"></i> ខ្ចីសៀវភៅ
                                            </a>
                                        @elseif($latestIssue && !$latestIssue->is_returned)
                                            <a href="{{ route('library.returns.create', $latestIssue) }}" class="btn btn-xs btn-outline-warning" title="កត់ត្រាការសងច្បាប់នេះ">
                                                <i class="bi bi-box-arrow-in-left me-1"></i> សងសៀវភៅ
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCopyModal{{ $copy->id }}" title="ផ្លាស់ប្តូរស្ថានភាព">
                                            <i class="bi bi-sliders"></i>
                                        </button>

                                        @if(!$copy->hasActiveIssue())
                                            <button type="button" class="btn btn-xs btn-outline-danger" title="លុបច្បាប់សៀវភៅ"
                                                    onclick="openDeleteCopyModal('{{ route('library.copies.destroy', $copy) }}', '{{ addslashes($copy->accession_number) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-xs btn-outline-secondary" disabled title="មិនអាចលុបច្បាប់សៀវភៅដែលកំពុងខ្ចីបានទេ">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Edit Copy Status Modal --}}
                                    <div class="modal fade text-start" id="editCopyModal{{ $copy->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow">
                                                <form method="POST" action="{{ route('library.copies.update', $copy) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">ធ្វើបច្ចុប្បន្នភាពស្ថានភាពច្បាប់សៀវភៅ</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-muted small mb-3">លេខកូដច្បាប់សៀវភៅ៖ <strong class="font-monospace text-dark">{{ $copy->accession_number }}</strong></p>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">ស្ថានភាពជាក់ស្តែង <span class="text-danger">*</span></label>
                                                            <select name="status" class="form-select">
                                                                <option value="available" {{ $copy->status === 'available' ? 'selected' : '' }}>នៅសល់</option>
                                                                <option value="issued" {{ $copy->status === 'issued' ? 'selected' : '' }}>បានខ្ចី</option>
                                                                <option value="lost" {{ $copy->status === 'lost' ? 'selected' : '' }}>បាត់បង់</option>
                                                                <option value="damaged" {{ $copy->status === 'damaged' ? 'selected' : '' }}>ខូចខាត</option>
                                                            </select>
                                                            <div class="form-text small">ចំណាំ៖ មិនអាចកំណត់ទៅជា "នៅសល់" បានទេ ប្រសិនបើមានកំណត់ត្រាខ្ចីដែលមិនទាន់បានសង។</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                                                        <button type="submit" class="btn btn-sm btn-primary">រក្សាទុកការផ្លាស់ប្តូរ</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">មិនទាន់មានច្បាប់សៀវភៅសម្រាប់ចំណងជើងនេះនៅឡើយទេ។ សូមចុច "+ បន្ថែមច្បាប់ចម្លង" ខាងលើដើម្បីចុះបញ្ជី។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($copies->hasPages())
                <div class="p-3 border-top">
                    {{ $copies->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Loan History for this Book --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
            <h5 class="fw-bold text-dark mb-0">ប្រវត្តិការខ្ចីសម្រាប់ចំណងជើងសៀវភៅនេះ</h5>
        </div>
        <div class="card-body px-0 pb-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">លេខកូដសៀវភៅ</th>
                            <th>អ្នកខ្ចី</th>
                            <th>ថ្ងៃខ្ចី</th>
                            <th>ថ្ងៃកំណត់សង</th>
                            <th>ថ្ងៃសងត្រឡប់</th>
                            <th class="pe-3 text-end">ប្រាក់ពិន័យ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentIssues as $bIssue)
                            <tr>
                                <td class="ps-3 font-monospace fw-medium text-dark">{{ $bIssue->library_book_copy->accession_number ?? '—' }}</td>
                                <td>{{ $bIssue->user->name ?? '—' }}</td>
                                <td>{{ $bIssue->issue_date ? $bIssue->issue_date->format('d/m/Y') : '-' }}</td>
                                <td>{{ $bIssue->due_date ? $bIssue->due_date->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($bIssue->libraryReturn)
                                        <span class="badge bg-success-subtle text-success">{{ $bIssue->libraryReturn->return_date->format('d/m/Y') }}</span>
                                    @elseif($bIssue->is_overdue)
                                        <span class="badge bg-danger-subtle text-danger">ហួសកាលកំណត់</span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary">កំពុងខ្ចី</span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    @if($bIssue->libraryReturn && $bIssue->libraryReturn->fine_amount > 0)
                                        <span class="text-danger fw-bold">${{ number_format($bIssue->libraryReturn->fine_amount, 2) }}</span>
                                    @else
                                        <span class="text-muted">$0.00</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">មិនទាន់មានប្រវត្តិនៃការខ្ចីសម្រាប់ចំណងជើងសៀវភៅនេះនៅឡើយទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Physical Copy Modal --}}
<div class="modal fade" id="addCopyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('library.books.copies.store', $book) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">បន្ថែមច្បាប់ចម្លងសៀវភៅ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">បន្ថែមច្បាប់សៀវភៅសម្រាប់ចំណងជើង៖ <strong class="text-dark">{{ $book->title }}</strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">លេខកូដសម្គាល់សៀវភៅ / Barcode <span class="text-danger">*</span></label>
                        <input type="text" name="accession_number" class="form-control font-monospace" placeholder="ឧ. LIB-{{ strtoupper(substr($book->title, 0, 3)) }}-{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" required maxlength="50">
                        <div class="form-text small">សៀវភៅនីមួយៗត្រូវតែមានលេខកូដសម្គាល់ ឬបាកូដជាក់លាក់មិនជាន់គ្នា។</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">ស្ថានភាពដំបូង <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available" selected>នៅសល់</option>
                            <option value="issued">បានខ្ចី</option>
                            <option value="lost">បាត់បង់</option>
                            <option value="damaged">ខូចខាត</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">បន្ថែមច្បាប់សៀវភៅ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Copy Confirmation Modal --}}
<div class="modal fade" id="modalDeleteCopy" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deleteCopyForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបច្បាប់សៀវភៅ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបច្បាប់សៀវភៅ <strong id="delCopyAccession" class="text-dark"></strong> នេះមែនទេ?
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
function openDeleteCopyModal(actionUrl, accessionNumber) {
    const form = document.getElementById('deleteCopyForm');
    if (form) form.action = actionUrl;

    const accEl = document.getElementById('delCopyAccession');
    if (accEl) accEl.textContent = accessionNumber || '';

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteCopy'));
    modal.show();
}
</script>
@endpush
@endsection
