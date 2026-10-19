@extends('layouts.app')

@section('title', 'ប្រភេទសៀវភៅ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">ប្រភេទសៀវភៅ</h1>
            <p class="text-muted small mb-0 mt-1">រៀបចំសៀវភៅតាមមុខវិជ្ជាសិក្សា ប្រភេទអក្សរសិល្ប៍ និងឯកសារយោង</p>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមប្រភេទសៀវភៅ
            </button>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('library.categories.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="ស្វែងរកតាមឈ្មោះប្រភេទសៀវភៅ ឬការពិពណ៌នា..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">ច្រោះទិន្នន័យ</button>
                    @if(request('search'))
                        <a href="{{ route('library.categories.index') }}" class="btn btn-sm btn-outline-secondary">សម្អាត</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th>ឈ្មោះប្រភេទសៀវភៅ</th>
                            <th>ការពិពណ៌នា</th>
                            <th>ចំនួនសៀវភៅ</th>
                            <th class="pe-3 text-end" style="width: 150px;">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $category->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $category->name }}</div>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $category->description ?? '—' }}</span>
                                </td>
                                <td>
                                    @if($category->library_books_count > 0)
                                        <a href="{{ route('library.books.index', ['category_id' => $category->id]) }}" class="badge bg-primary-subtle text-primary text-decoration-none">
                                            {{ $category->library_books_count }} ក្បាល
                                        </a>
                                    @else
                                        <span class="badge bg-light text-muted">0 ក្បាល</span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}" title="កែសម្រួល">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($category->library_books_count === 0)
                                            <button type="button" class="btn btn-outline-danger" title="លុប"
                                                    onclick="openDeleteCategoryModal('{{ route('library.categories.destroy', $category) }}', '{{ addslashes($category->name) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary" disabled title="មិនអាចលុបប្រភេទដែលមានសៀវភៅនៅឡើយទេ">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Edit Category Modal --}}
                                    <div class="modal fade text-start" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow">
                                                <form method="POST" action="{{ route('library.categories.update', $category) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">កែសម្រួលប្រភេទសៀវភៅ</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">ឈ្មោះប្រភេទសៀវភៅ <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required maxlength="255">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">ការពិពណ៌នា</label>
                                                            <textarea name="description" class="form-control" rows="3" maxlength="1000">{{ old('description', $category->description) }}</textarea>
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
                                <td colspan="5" class="text-center py-4 text-muted">មិនទាន់មានប្រភេទសៀវភៅនៅឡើយទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-3 border-top">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Create Category Modal --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('library.categories.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">បន្ថែមប្រភេទសៀវភៅថ្មី</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">ឈ្មោះប្រភេទសៀវភៅ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="ឧ. វិទ្យាសាស្ត្រកុំព្យូទ័រ" value="{{ old('name') }}" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">ការពិពណ៌នា</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="ការពិពណ៌នាបន្ថែមអំពីប្រភេទសៀវភៅនេះ..." maxlength="1000">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">បង្កើតប្រភេទសៀវភៅ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Category Confirmation Modal --}}
<div class="modal fade" id="modalDeleteCategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deleteCategoryForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបប្រភេទសៀវភៅ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបប្រភេទសៀវភៅ <strong id="delCategoryName" class="text-dark"></strong> នេះមែនទេ?
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
function openDeleteCategoryModal(actionUrl, name) {
    const form = document.getElementById('deleteCategoryForm');
    if (form) form.action = actionUrl;

    const nameEl = document.getElementById('delCategoryName');
    if (nameEl) nameEl.textContent = name || '';

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteCategory'));
    modal.show();
}
</script>
@endpush
@endsection
