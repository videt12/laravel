@extends('layouts.app')

@section('title', 'បញ្ជីសៀវភៅ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">បញ្ជីសៀវភៅ</h1>
            <p class="text-muted small mb-0 mt-1">គ្រប់គ្រងបញ្ជីចំណងជើងសៀវភៅ អ្នកនិពន្ធ រោងពុម្ព និងចំនួនច្បាប់សៀវភៅក្នុងស្តុក</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('library.books.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមសៀវភៅថ្មី
            </a>
            <a href="{{ route('library.categories.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-collection me-1"></i> ប្រភេទសៀវភៅ
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('library.books.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="ស្វែងរកតាមចំណងជើង អ្នកនិពន្ធ រោងពុម្ព ISBN..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">-- គ្រប់ប្រភេទទាំងអស់ --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">ច្រោះទិន្នន័យ</button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('library.books.index') }}" class="btn btn-sm btn-outline-secondary">កំណត់ឡើងវិញ</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Books Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">ចំណងជើងសៀវភៅ & ISBN</th>
                            <th>អ្នកនិពន្ធ</th>
                            <th>រោងពុម្ព</th>
                            <th>ប្រភេទ</th>
                            <th>ចំនួនច្បាប់ក្នុងស្តុក</th>
                            <th class="pe-3 text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('library.books.show', $book) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $book->title }}
                                    </a>
                                    @if($book->isbn)
                                        <div class="text-muted small">ISBN: <span class="font-monospace">{{ $book->isbn }}</span></div>
                                    @endif
                                </td>
                                <td>{{ $book->author ?? '—' }}</td>
                                <td>{{ $book->publisher ?? '—' }}</td>
                                <td>
                                    @if($book->library_category)
                                        <span class="badge bg-light text-dark border">{{ $book->library_category->name }}</span>
                                    @else
                                        <span class="text-muted small">គ្មានប្រភេទ</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $total = $book->total_copies_count;
                                        $available = $book->available_copies_count;
                                        $issued = $book->issued_copies_count;
                                    @endphp
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge bg-secondary-subtle text-secondary" title="ចំនួនច្បាប់សរុប">{{ $total }} សរុប</span>
                                        @if($total > 0)
                                            <span class="badge bg-success-subtle text-success" title="នៅសល់">{{ $available }} នៅសល់</span>
                                            @if($issued > 0)
                                                <span class="badge bg-warning-subtle text-warning" title="បានខ្ចី">{{ $issued }} បានខ្ចី</span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('library.books.show', $book) }}" class="btn btn-outline-secondary" title="មើលព័ត៌មានលម្អិត & ច្បាប់ចម្លង">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('library.books.edit', $book) }}" class="btn btn-outline-primary" title="កែសម្រួលសៀវភៅ">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="លុបសៀវភៅ"
                                                onclick="openDeleteBookModal('{{ route('library.books.destroy', $book) }}', '{{ addslashes($book->title) }}', '{{ addslashes($book->isbn ?? '') }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">រកមិនឃើញសៀវភៅដែលត្រូវគ្នានឹងលក្ខខណ្ឌស្វែងរកនោះទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($books->hasPages())
                <div class="p-3 border-top">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Delete Book Confirmation Modal --}}
<div class="modal fade" id="modalDeleteBook" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="deleteBookForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" 
                         style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626; font-size: 1.4rem;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">បញ្ជាក់ការលុបសៀវភៅ</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                        តើអ្នកពិតជាចង់លុបសៀវភៅ <strong id="delBookTitle" class="text-dark"></strong> នេះមែនទេ?
                    </p>
                    <div id="delBookIsbnBox" class="p-2 mb-2 rounded bg-light border text-start d-none" style="font-size: 0.78rem;">
                        <div class="text-muted d-flex justify-content-between">
                            <span>ISBN:</span>
                            <span id="delBookIsbn" class="fw-bold text-dark"></span>
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
function openDeleteBookModal(actionUrl, title, isbn) {
    const form = document.getElementById('deleteBookForm');
    if (form) form.action = actionUrl;

    const titleEl = document.getElementById('delBookTitle');
    if (titleEl) titleEl.textContent = title || '';

    const isbnBox = document.getElementById('delBookIsbnBox');
    const isbnEl = document.getElementById('delBookIsbn');
    if (isbn && isbn.trim() !== '') {
        isbnEl.textContent = isbn;
        isbnBox.classList.remove('d-none');
    } else {
        isbnBox.classList.add('d-none');
    }

    const modal = new bootstrap.Modal(document.getElementById('modalDeleteBook'));
    modal.show();
}
</script>
@endpush
@endsection
