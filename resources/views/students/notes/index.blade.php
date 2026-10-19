@extends('layouts.app')

@section('title', 'កំណត់ចំណាំសិស្ស — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">សេវាកម្មសិស្ស</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កំណត់ចំណាំសិស្ស</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   NOTE TYPE BADGES
═══════════════════════════════════════════════════════════════════ */
.note-type-badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    border-radius: 999px;
    padding: .18rem .6rem;
    font-size: .72rem;
    font-weight: 600;
    white-space: nowrap;
    letter-spacing: .1px;
}
.note-type-academic       { background: #dbeafe; color: #1e40af; }
.note-type-behavioral     { background: #fce7f3; color: #9d174d; }
.note-type-health         { background: #d1fae5; color: #065f46; }
.note-type-administrative { background: #e0e7ff; color: #3730a3; }
.note-type-parent         { background: #fef3c7; color: #92400e; }
.note-type-general        { background: #f1f5f9; color: #475569; }

/* ═══════════════════════════════════════════════════════════════════
   PRIORITY BADGES
═══════════════════════════════════════════════════════════════════ */
.note-priority-badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    border-radius: var(--radius-sm);
    padding: .2rem .55rem;
    font-size: .7rem;
    font-weight: 700;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.note-priority-urgent { background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }
.note-priority-high   { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; }
.note-priority-normal { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
.note-priority-low    { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

/* ═══════════════════════════════════════════════════════════════════
   STATUS BADGES
═══════════════════════════════════════════════════════════════════ */
.note-status-badge {
    display: inline-flex;
    align-items: center;
    gap: .28rem;
    border-radius: 999px;
    padding: .18rem .65rem;
    font-size: .72rem;
    font-weight: 600;
    white-space: nowrap;
}
.note-status-active   { background: #dcfce7; color: #15803d; }
.note-status-resolved { background: #e0e7ff; color: #4338ca; }
.note-status-archived { background: #f1f5f9; color: #64748b; }

/* ═══════════════════════════════════════════════════════════════════
   FLAG ICONS
═══════════════════════════════════════════════════════════════════ */
.note-flag { font-size: .8rem; }
.note-flag-important  { color: #d97706; }
.note-flag-followup   { color: #2563eb; }
.note-flag-inactive   { color: #cbd5e1; }

/* ═══════════════════════════════════════════════════════════════════
   FILTER SELECTS
═══════════════════════════════════════════════════════════════════ */
.filter-select {
    font-size: .8rem;
    padding: .3rem .65rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    color: var(--text-dark);
    background-color: #fff;
    cursor: pointer;
    height: 30px;
}
.filter-select:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 2px var(--gold-subtle);
    outline: none;
}

/* Active filter chip */
.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: var(--gold-light);
    color: var(--gold-dark);
    border-radius: 999px;
    padding: .15rem .65rem;
    font-size: .73rem;
    font-weight: 500;
}
</style>
@endpush

@section('content')

@php
    $khmerNoteTypes = [
        'academic'       => 'ការសិក្សា',
        'behavioral'     => 'អាកប្បកិរិយា',
        'health'         => 'សុខភាព',
        'administrative' => 'រដ្ឋបាល',
        'parent'         => 'មាតាបិតា',
        'general'        => 'ទូទៅ',
    ];
    $khmerPriorities = [
        'urgent' => 'បន្ទាន់',
        'high'   => 'ខ្ពស់',
        'normal' => 'ធម្មតា',
        'low'    => 'ទាប',
    ];
    $khmerStatuses = [
        'active'   => 'សកម្ម',
        'resolved' => 'បានដោះស្រាយ',
        'archived' => 'ទុកជាឯកសារ',
    ];
@endphp

{{-- PAGE HEADER --}}
<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">កំណត់ចំណាំសិស្ស</h1>
        <p class="page-subtitle">គ្រប់គ្រង និងពិនិត្យមើលកំណត់ចំណាំរបស់សិស្ស</p>
    </div>
    <a href="{{ route('student-notes.create') }}" class="btn btn-primary-action">
        <i class="bi bi-plus-lg me-1"></i>+ បន្ថែមកំណត់ចំណាំ
    </a>
</div>

{{-- FILTER TOOLBAR --}}
<div class="toolbar-card mb-4">
    <form method="GET" action="{{ route('student-notes.index') }}" id="filterForm">
        <div class="row g-2 align-items-center">

            {{-- Search --}}
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="search-input-wrap">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" name="search" id="searchInput"
                           value="{{ $search }}"
                           class="form-control form-control-sm"
                           placeholder="ឈ្មោះសិស្ស អត្តលេខ ឬកំណត់ចំណាំ..."
                           autocomplete="off">
                </div>
            </div>

            {{-- Type filter --}}
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                <select name="type" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">ប្រភេទទាំងអស់</option>
                    @foreach(\App\Models\Students\StudentNote::NOTE_TYPES as $val => $label)
                        <option value="{{ $val }}" {{ $type === $val ? 'selected' : '' }}>{{ $khmerNoteTypes[$val] ?? $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Priority filter --}}
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                <select name="priority" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">អាទិភាពទាំងអស់</option>
                    @foreach(\App\Models\Students\StudentNote::PRIORITIES as $val => $label)
                        <option value="{{ $val }}" {{ $priority === $val ? 'selected' : '' }}>{{ $khmerPriorities[$val] ?? $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status filter --}}
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4">
                <select name="status" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    @foreach(\App\Models\Students\StudentNote::STATUSES as $val => $label)
                        <option value="{{ $val }}" {{ $status === $val ? 'selected' : '' }}>{{ $khmerStatuses[$val] ?? $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Actions --}}
            <div class="col-xl-2 col-lg-1 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-secondary px-3">
                    <i class="bi bi-funnel me-1"></i>ចម្រាញ់
                </button>
                @if($search || $type || $priority || $status)
                    <a href="{{ route('student-notes.index') }}" class="btn btn-sm btn-link text-muted text-decoration-none px-2">
                        <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
                    </a>
                @endif
            </div>
        </div>

        {{-- Active filter chips --}}
        @if($search || $type || $priority || $status)
        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted" style="font-size:.75rem">តម្រងសកម្ម:</span>
            @if($search)
                <span class="filter-chip">ស្វែងរក: "{{ $search }}"
                    <a href="{{ route('student-notes.index', array_filter(['type'=>$type,'priority'=>$priority,'status'=>$status])) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($type)
                <span class="filter-chip">ប្រភេទ: {{ $khmerNoteTypes[$type] ?? (\App\Models\Students\StudentNote::NOTE_TYPES[$type] ?? $type) }}
                    <a href="{{ route('student-notes.index', array_filter(['search'=>$search,'priority'=>$priority,'status'=>$status])) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($priority)
                <span class="filter-chip">អាទិភាព: {{ $khmerPriorities[$priority] ?? (\App\Models\Students\StudentNote::PRIORITIES[$priority] ?? $priority) }}
                    <a href="{{ route('student-notes.index', array_filter(['search'=>$search,'type'=>$type,'status'=>$status])) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($status)
                <span class="filter-chip">ស្ថានភាព: {{ $khmerStatuses[$status] ?? (\App\Models\Students\StudentNote::STATUSES[$status] ?? $status) }}
                    <a href="{{ route('student-notes.index', array_filter(['search'=>$search,'type'=>$type,'priority'=>$priority])) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
        </div>
        @endif
    </form>
</div>

{{-- NOTES TABLE CARD --}}
<div class="data-card">

    <div class="data-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">កំណត់ចំណាំសិស្ស</span>
            <span class="count-badge">{{ $notes->total() }}</span>
            @if($search || $type || $priority || $status)
                <span class="text-muted" style="font-size:.75rem">· បានចម្រាញ់</span>
            @endif
        </div>
        <small class="text-muted">
            @if($notes->total() > 0)
                បង្ហាញពី {{ $notes->firstItem() }}–{{ $notes->lastItem() }} នៃ {{ $notes->total() }}
            @endif
        </small>
    </div>

    @if($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-journal-text"></i></div>
            <p class="empty-title">
                @if($search || $type || $priority || $status) មិនមានកំណត់ចំណាំដែលត្រូវនឹងតម្រងស្វែងរកឡើយ @else មិនមានកំណត់ចំណាំសិស្សឡើយ @endif
            </p>
            <p class="empty-subtitle">
                @if($search || $type || $priority || $status)
                    សូមសាកល្បងកែសម្រួល ឬ <a href="{{ route('student-notes.index') }}" class="text-decoration-none">សម្អាតតម្រង</a>។
                @else
                    មិនទាន់មានកំណត់ចំណាំសិស្សនៅឡើយទេ។
                @endif
            </p>
            @if(!$search && !$type && !$priority && !$status)
                <a href="{{ route('student-notes.create') }}" class="btn btn-primary-action btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>+ បន្ថែមកំណត់ចំណាំសិស្ស
                </a>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:36px"></th>
                        <th>សិស្ស</th>
                        <th>កំណត់ចំណាំ</th>
                        <th>ប្រភេទ</th>
                        <th>អាទិភាព</th>
                        <th>ស្ថានភាព</th>
                        <th style="width:70px">ទង់សម្គាល់</th>
                        <th style="width:90px">កាលបរិច្ឆេទ</th>
                        <th style="width:52px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notes as $i => $note)
                    @php
                        $name         = $note->student?->user?->name ?? '—';
                        $initials     = strtoupper(substr($name, 0, 1));
                        $avatarColors = ['#e8a838','#3b82f6','#10b981','#8b5cf6','#ef4444','#f59e0b'];
                        $color        = $avatarColors[crc32($name) % count($avatarColors)];
                    @endphp
                    <tr class="table-row-hover">

                        {{-- Row # --}}
                        <td class="text-muted text-center" style="font-size:.75rem">{{ $notes->firstItem() + $i }}</td>

                        {{-- Student --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $photoUrl = $note->student?->photo_url;
                                @endphp
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}"
                                         alt="{{ $name }}"
                                         class="rounded-circle flex-shrink-0 border object-fit-cover shadow-xs"
                                         style="width:34px;height:34px;"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                    <div class="student-avatar d-none flex-shrink-0" style="background:{{ $color }}1a;color:{{ $color }}">{{ $initials }}</div>
                                @else
                                    <div class="student-avatar flex-shrink-0" style="background:{{ $color }}1a;color:{{ $color }}">{{ $initials }}</div>
                                @endif
                                <div class="lh-sm">
                                    <a href="{{ route('students.show', $note->student) }}"
                                       class="fw-semibold text-dark text-decoration-none student-name-link">
                                        {{ $name }}
                                    </a>
                                    @if($note->student?->admission_number)
                                        <div class="text-muted" style="font-size:.73rem">{{ $note->student->admission_number }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Note preview --}}
                        <td style="max-width:240px">
                            <a href="{{ route('student-notes.show', $note) }}"
                               class="text-secondary text-decoration-none d-block text-truncate"
                               style="font-size:.84rem;max-width:230px"
                               title="{{ $note->note }}">
                                {{ $note->preview }}
                            </a>
                        </td>

                        {{-- Type badge --}}
                        <td>
                            @if($note->note_type)
                                <span class="note-type-badge {{ $note->note_type_class }}">
                                    {{ $khmerNoteTypes[$note->note_type] ?? $note->note_type_label }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size:.8rem">—</span>
                            @endif
                        </td>

                        {{-- Priority badge --}}
                        <td>
                            <span class="note-priority-badge {{ $note->priority_class }}">
                                @if($note->priority === 'urgent') <i class="bi bi-exclamation-circle-fill"></i>
                                @elseif($note->priority === 'high') <i class="bi bi-arrow-up-circle-fill"></i>
                                @elseif($note->priority === 'low') <i class="bi bi-arrow-down-circle"></i>
                                @else <i class="bi bi-dash-circle"></i>
                                @endif
                                {{ $khmerPriorities[$note->priority] ?? $note->priority_label }}
                            </span>
                        </td>

                        {{-- Status badge --}}
                        <td>
                            <span class="note-status-badge {{ $note->status_class }}">
                                @if($note->status === 'active') <i class="bi bi-circle-fill" style="font-size:.45rem"></i>
                                @elseif($note->status === 'resolved') <i class="bi bi-check-circle-fill" style="font-size:.7rem"></i>
                                @else <i class="bi bi-archive" style="font-size:.7rem"></i>
                                @endif
                                {{ $khmerStatuses[$note->status] ?? $note->status_label }}
                            </span>
                        </td>

                        {{-- Flags --}}
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-bookmark-star-fill note-flag {{ $note->is_important ? 'note-flag-important' : 'note-flag-inactive' }}"
                                   title="{{ $note->is_important ? 'សំខាន់' : 'មិនសំខាន់' }}"
                                   data-bs-toggle="tooltip"></i>
                                <i class="bi bi-clock-history note-flag {{ $note->follow_up_required ? 'note-flag-followup' : 'note-flag-inactive' }}"
                                   title="{{ $note->follow_up_required ? 'ត្រូវការតាមដានបន្ត' : 'មិនត្រូវការតាមដាន' }}"
                                   data-bs-toggle="tooltip"></i>
                            </div>
                        </td>

                        {{-- Date --}}
                        <td class="text-muted" style="font-size:.8rem;white-space:nowrap">
                            {{ ($note->note_date ?? $note->created_at)?->format('d/m/Y') ?? '—' }}
                        </td>

                        {{-- Actions --}}
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn-action-dots" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:140px;font-size:.875rem">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('student-notes.show', $note) }}">
                                            <i class="bi bi-eye me-2 text-muted"></i>មើលលម្អិត
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('student-notes.edit', $note) }}">
                                            <i class="bi bi-pencil me-2 text-muted"></i>កែសម្រួល
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-2 text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delModal{{ $note->id }}">
                                            <i class="bi bi-trash me-2"></i>លុប
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            {{-- Delete modal --}}
                            <div class="modal fade" id="delModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body p-4 text-center">
                                            <div class="mb-3">
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:48px;height:48px">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-1">លុបកំណត់ចំណាំនេះមែនទេ?</h6>
                                            <p class="text-muted small mb-4">
                                                កំណត់ចំណាំសម្រាប់សិស្ស <strong>{{ $name }}</strong> នឹងត្រូវបានលុបចេញជាអចិន្ត្រៃយ៍។
                                            </p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                                                <form action="{{ route('student-notes.destroy', $note) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger px-3">លុប</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($notes->hasPages())
        <div class="data-card-footer">
            <div class="text-muted" style="font-size:.8rem">ទំព័រ {{ $notes->currentPage() }} នៃ {{ $notes->lastPage() }}</div>
            {{ $notes->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif
</div>

@endsection

@section('scripts')
<script>
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => document.getElementById('filterForm').submit(), 500);
    });
</script>
@endsection
