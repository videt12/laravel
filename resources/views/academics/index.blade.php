@extends('layouts.app')

@section('title', 'គ្រប់គ្រងការសិក្សា — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការសិក្សា</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════════════════════════ */
.page-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    background: #eef2ff;
    color: #4f46e5;
    border-radius: var(--radius-md);
    font-size: 1.15rem;
}
.page-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   TAB NAV (TABLE HEADER GRID STYLE - រចនាជាក្រឡាតារាង)
═══════════════════════════════════════════════════════════════════ */
.academics-tabs-wrapper {
    background: #ffffff;
    border-bottom: 2px solid var(--form-card-border, #fcd34d);
    overflow: hidden;
}

.academics-tabs {
    display: flex;
    flex-wrap: wrap;
    margin: 0;
    padding: 0;
    background: #f8fafc;
}

.tab-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    padding: 0.62rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border-right: 1px solid #e2e8f0;
    background: #f8fafc;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
    text-align: center;
    position: relative;
    white-space: nowrap;
}

.tab-link:last-child {
    border-right: none;
}

.tab-link:hover {
    background: #ffffff;
    color: #0f172a;
}

.tab-link.active {
    background: #ffffff;
    color: #b45309;
    font-weight: 700;
    box-shadow: inset 0 3px 0 #f59e0b;
}

.tab-link .tab-icon {
    font-size: 0.95rem;
    color: #64748b;
}

.tab-link.active .tab-icon {
    color: #d97706;
}

.tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 18px;
    padding: 0 5px;
    background: #e2e8f0;
    color: #475569;
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
}

.tab-link.active .tab-count {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

@media (max-width: 1199.98px) {
    .academics-tabs {
        display: flex;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        white-space: nowrap;
        min-width: max-content;
    }
    .academics-tabs::-webkit-scrollbar { display: none; }
    .tab-link {
        flex: 0 0 auto;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
    }
}

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: var(--form-input-bg, #fffbeb);
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: var(--radius-sm);
    padding: 0.45rem 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.search-wrapper {
    position: relative;
    min-width: 200px;
    flex: 1 1 200px;
    max-width: 300px;
}
.search-wrapper i {
    position: absolute;
    left: 0.65rem; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.75rem;
}
.search-wrapper input {
    width: 100%;
    padding: 0.25rem 0.65rem 0.25rem 1.8rem;
    font-size: 0.8rem;
    min-height: 32px;
    height: 32px;
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: var(--radius-sm);
    transition: all 0.2s;
    background: #ffffff;
    line-height: 1.3;
}
.search-wrapper input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 2px var(--gold-subtle);
    outline: none;
}
.filter-select {
    font-size: 0.8rem;
    padding: 0.25rem 1.8rem 0.25rem 0.65rem;
    min-height: 32px;
    height: 32px;
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: var(--radius-sm);
    background: #ffffff;
    flex: 0 0 auto;
    max-width: 250px;
    line-height: 1.3;
}
.filter-select:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 2px var(--gold-subtle);
    outline: none;
}
.btn-filter {
    padding: 0.25rem 0.85rem;
    font-size: 0.8rem;
    min-height: 32px;
    height: 32px;
    font-weight: 600;
    background: var(--gold);
    border: 1px solid var(--gold);
    border-radius: var(--radius-sm);
    color: #ffffff;
    cursor: pointer;
    transition: all 0.15s;
    line-height: 1.3;
}
.btn-filter:hover { background: var(--gold-dark); border-color: var(--gold-dark); color: #ffffff; }

/* ═══════════════════════════════════════════════════════════════════
   TABLE & CARDS
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: var(--form-card-bg, #ffffff);
    border: 1px solid var(--form-card-border, #e2e8f0);
    border-radius: var(--radius-md);
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    overflow: hidden;
}
.table-custom { margin: 0; width: 100%; }
.table-custom thead th {
    background: var(--form-card-header-bg, #f8fafc);
    color: var(--text-dark, #0f172a);
    font-size: 0.875rem !important;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    padding: 0.5rem 0.65rem !important;
    border-bottom: 1px solid var(--form-card-border, #e2e8f0);
    white-space: nowrap;
    line-height: 1.4 !important;
}
.table-custom tbody tr {
    background-color: #ffffff;
}
.table-custom tbody tr:nth-child(even) {
    background-color: #f8fafc;
}
.table-custom tbody td {
    padding: 0.45rem 0.65rem !important;
    font-size: 0.875rem !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #f1f5f9;
    color: var(--text-dark);
    line-height: 1.4 !important;
}
.table-custom tbody tr:last-child td { border-bottom: none; }
.table-custom tbody tr:hover td { background-color: #f1f5f9 !important; }

/* ═══════════════════════════════════════════════════════════════════
   BADGES & PILLS
═══════════════════════════════════════════════════════════════════ */
.class-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.18rem 0.55rem; border-radius: 4px;
    font-size: 0.85rem; font-weight: 600;
    background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe;
    line-height: 1.3;
}
.subject-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.18rem 0.55rem; border-radius: 4px;
    font-size: 0.85rem; font-weight: 600;
    background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;
    line-height: 1.3;
}
.year-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.15rem 0.45rem; border-radius: 4px;
    font-size: 0.82rem; font-weight: 500;
    background: #f8fafc; color: var(--text-muted); border: 1px solid var(--border-soft);
    line-height: 1.3;
}
.metric-pill {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.15rem 0.5rem; border-radius: 4px;
    background: #f8fafc; border: 1px solid var(--border-soft);
    white-space: nowrap;
}
.metric-val { font-size: 0.85rem; font-weight: 700; color: var(--text-dark); line-height: 1.2; }
.metric-lbl { font-size: 0.75rem; color: var(--text-muted); }

.type-badge {
    display: inline-flex; align-items: center; gap: 0.25rem;
    padding: 0.12rem 0.45rem; border-radius: 4px;
    font-size: 0.70rem; font-weight: 600; white-space: nowrap;
}
.type-Core     { background: #e0e7ff; color: #3730a3; }
.type-Elective { background: #dcfce7; color: #15803d; }
.type-Practical{ background: #ffedd5; color: #c2410c; }
.type-default  { background: #f1f5f9; color: #475569; }

/* ═══════════════════════════════════════════════════════════════════
   ACTION BUTTONS
═══════════════════════════════════════════════════════════════════ */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px;
    border-radius: 4px;
    font-size: 0.78rem;
    color: var(--text-muted);
    transition: all 0.2s;
    background: transparent; border: none;
    cursor: pointer;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }

/* ═══════════════════════════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════════════════════════ */
.empty-state-cell { padding: 3.5rem 1.25rem !important; text-align: center; }
.empty-icon { font-size: 2.5rem; color: var(--text-light); margin-bottom: 0.75rem; }

/* ═══════════════════════════════════════════════════════════════════
   ADD BUTTON
═══════════════════════════════════════════════════════════════════ */
.btn-primary-action {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.48rem 1.15rem;
    font-size: 0.875rem;
    font-weight: 600;
    background: var(--gold);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: background 0.15s, box-shadow 0.15s;
    cursor: pointer;
    line-height: 1.45;
}
.btn-primary-action:hover {
    background: var(--gold-dark);
    color: #fff;
    box-shadow: 0 4px 12px rgba(232,168,56,0.35);
}
</style>
@endpush

@section('content')

{{-- ═══ FLASH MESSAGES ═══ --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon me-1"></i> សូមពិនិត្យ និងកែតម្រូវកំហុសខាងក្រោម៖</div>
        <ul class="mb-0 ps-3 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ═══ HEADER ═══ --}}
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-mortarboard-fill"></i></div>
            គ្រប់គ្រងការសិក្សា
        </h1>
        <p class="page-subtitle">គ្រប់គ្រងថ្នាក់រៀន មុខវិជ្ជា ការបង្រៀន និងបន្ទប់រៀន</p>
    </div>
    <div>
        @if($activeTab === 'classes')
            @if(auth()->user()?->canAccess('classes.create'))
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-xs" data-bs-toggle="modal" data-bs-target="#createGradeModal">
                    <i class="bi bi-mortarboard text-warning"></i>បន្ថែមកម្រិតថ្នាក់
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-xs" data-bs-toggle="modal" data-bs-target="#createSectionModal">
                    <i class="bi bi-door-open text-primary"></i>បន្ថែមបន្ទប់/ក្រុម
                </button>
                <a href="{{ route('classes.create') }}" class="btn-primary-action shadow-sm" id="btn-add-action">
                    <i class="bi bi-plus-lg me-1"></i>បង្កើតថ្នាក់រៀន
                </a>
            </div>
            @endif
        @elseif($activeTab === 'subjects')
            @if(auth()->user()?->canAccess('subjects.create'))
            <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createSubjectModal" id="btn-add-action">
                <i class="bi bi-plus-lg me-1"></i>បន្ថែមមុខវិជ្ជា
            </button>
            @endif
        @elseif($activeTab === 'teacher-assignments')
            @if(auth()->user()?->canAccess(['classes.create', 'classes.edit']))
            <a href="{{ route('teacher-assignments.create') }}" class="btn-primary-action shadow-sm" id="btn-add-action">
                <i class="bi bi-plus-lg me-1"></i>បែងចែកគ្រូបង្រៀន
            </a>
            @endif
        @elseif($activeTab === 'classrooms')
            @if(auth()->user()?->canAccess(['classes.create', 'classes.edit']))
            <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createClassroomModal" id="btn-add-action">
                <i class="bi bi-plus-lg me-1"></i>បន្ថែមបន្ទប់រៀន
            </button>
            @endif
        @elseif($activeTab === 'academic-years')
            @if(auth()->user()?->canAccess('academic-years.create'))
            <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createAcademicYearModal" id="btn-add-action">
                <i class="bi bi-plus-lg me-1"></i>បន្ថែមឆ្នាំសិក្សា
            </button>
            @endif
        @endif
    </div>
</div>

{{-- ═════════════════════════════════════════════════════════════════
     TABBED TABLE CARD (តារាងគ្រប់គ្រងការសិក្សា)
═════════════════════════════════════════════════════════════════ --}}
<div class="table-card">
    {{-- TABS AS TABLE HEADER (ជួរក្បាលតារាង) --}}
    <div class="academics-tabs-wrapper mb-0">
        <nav class="academics-tabs" role="tablist" aria-label="Academics tabs">
            @if(auth()->user()?->canAccess(['classes.view', 'classes']))
            <a href="{{ route('academics.index', ['tab' => 'classes'] + request()->except(['tab', 'page', 'search', 'grade_id', 'academic_year_id', 'type', 'class_id', 'subject_id', 'employee_id', 'building', 'status'])) }}"
               class="tab-link {{ $activeTab === 'classes' ? 'active' : '' }}"
               role="tab" aria-selected="{{ $activeTab === 'classes' ? 'true' : 'false' }}">
                <i class="bi bi-diagram-3 tab-icon"></i>
                <span>ថ្នាក់រៀន</span>
                <span class="tab-count">{{ $counts['classes'] ?? 0 }}</span>
            </a>
            @endif

            @if(auth()->user()?->canAccess(['subjects.view', 'subjects']))
            <a href="{{ route('academics.index', ['tab' => 'subjects'] + request()->except(['tab', 'page', 'search', 'grade_id', 'academic_year_id', 'type', 'class_id', 'subject_id', 'employee_id', 'building', 'status'])) }}"
               class="tab-link {{ $activeTab === 'subjects' ? 'active' : '' }}"
               role="tab" aria-selected="{{ $activeTab === 'subjects' ? 'true' : 'false' }}">
                <i class="bi bi-book tab-icon"></i>
                <span>មុខវិជ្ជា</span>
                <span class="tab-count">{{ $counts['subjects'] ?? 0 }}</span>
            </a>
            @endif

            @if(auth()->user()?->canAccess(['teacher-assignments.view', 'teacher-assignments', 'classes.create', 'classes.edit']))
            <a href="{{ route('academics.index', ['tab' => 'teacher-assignments'] + request()->except(['tab', 'page', 'search', 'grade_id', 'academic_year_id', 'type', 'class_id', 'subject_id', 'employee_id', 'building', 'status'])) }}"
               class="tab-link {{ $activeTab === 'teacher-assignments' ? 'active' : '' }}"
               role="tab" aria-selected="{{ $activeTab === 'teacher-assignments' ? 'true' : 'false' }}">
                <i class="bi bi-person-workspace tab-icon"></i>
                <span>ការបែងចែកគ្រូបង្រៀន</span>
                <span class="tab-count">{{ $counts['teacher-assignments'] ?? 0 }}</span>
            </a>
            @endif

            @if(auth()->user()?->canAccess(['classrooms.view', 'classrooms', 'classes.create', 'classes.edit']))
            <a href="{{ route('academics.index', ['tab' => 'classrooms'] + request()->except(['tab', 'page', 'search', 'grade_id', 'academic_year_id', 'type', 'class_id', 'subject_id', 'employee_id', 'building', 'status'])) }}"
               class="tab-link {{ $activeTab === 'classrooms' ? 'active' : '' }}"
               role="tab" aria-selected="{{ $activeTab === 'classrooms' ? 'true' : 'false' }}">
                <i class="bi bi-door-closed tab-icon"></i>
                <span>បន្ទប់រៀន</span>
                <span class="tab-count">{{ $counts['classrooms'] ?? 0 }}</span>
            </a>
            @endif

            @if(auth()->user()?->canAccess(['academic-years.view', 'academic-years', 'academic-years.create']))
            <a href="{{ route('academics.index', ['tab' => 'academic-years'] + request()->except(['tab', 'page', 'search', 'grade_id', 'academic_year_id', 'type', 'class_id', 'subject_id', 'employee_id', 'building', 'status'])) }}"
               class="tab-link {{ $activeTab === 'academic-years' ? 'active' : '' }}"
               role="tab" aria-selected="{{ $activeTab === 'academic-years' ? 'true' : 'false' }}">
                <i class="bi bi-calendar-range tab-icon"></i>
                <span>ឆ្នាំសិក្សា</span>
                <span class="tab-count">{{ $counts['academic-years'] ?? 0 }}</span>
            </a>
            @endif
        </nav>
    </div>

    {{-- TAB CONTENT PANEL --}}
    @include('academics.tabs.' . $activeTab)
</div>

{{-- ═══ CREATE CLASSROOM MODAL ═══ --}}
<div class="modal fade" id="createClassroomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6">
                    <i class="bi bi-door-closed text-warning me-2"></i>បន្ថែមបន្ទប់រៀនថ្មី
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createClassroomForm" action="{{ route('classrooms.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_modal" value="create_classroom">
                <div class="modal-body p-4">
                    <div id="create_classroom_duplicate_alert" class="alert alert-danger py-2 px-3 small d-none mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>ប្រុងប្រយ័ត្ន៖</strong> បន្ទប់រៀន <span id="create_classroom_duplicate_name" class="fw-bold"></span> នេះមានរួចហើយ! សូមកុំបង្កើតដូចគ្នា។
                    </div>
                    <div class="mb-3">
                        <label for="create_classroom_name" class="form-label small fw-bold text-dark">
                            ឈ្មោះ / លេខបន្ទប់ <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="create_classroom_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="ឧ. Room101, បន្ទប់ ១០១...">
                        <div class="invalid-feedback" id="create_classroom_error_msg">
                            @error('name') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="create_classroom_building" class="form-label small fw-bold text-dark">
                            អគារ / ប្លុក <span class="text-muted fw-normal">(ស្រេចចិត្ត)</span>
                        </label>
                        <input type="text" name="building" id="create_classroom_building" class="form-control" value="{{ old('building') }}" placeholder="ឧ. A, អគារធំ, អគារវិទ្យាសាស្ត្រ...">
                    </div>
                    <div class="mb-2">
                        <label for="create_classroom_capacity" class="form-label small fw-bold text-dark">
                            ចំណុះសិស្ស (កៅអី) <span class="text-muted fw-normal">(ស្រេចចិត្ត)</span>
                        </label>
                        <input type="number" name="capacity" id="create_classroom_capacity" class="form-control" min="1" max="1000" placeholder="ឧ. ៤០">
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកបន្ទប់រៀន
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ EDIT CLASSROOM MODAL ═══ --}}
<div class="modal fade" id="editClassroomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលបន្ទប់រៀន
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editClassroomForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_classroom_id" value="">
                <div class="modal-body p-4">
                    <div id="edit_classroom_duplicate_alert" class="alert alert-danger py-2 px-3 small d-none mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>ប្រុងប្រយ័ត្ន៖</strong> បន្ទប់រៀន <span id="edit_classroom_duplicate_name" class="fw-bold"></span> នេះមានរួចហើយ!
                    </div>
                    <div class="mb-3">
                        <label for="edit_classroom_name" class="form-label small fw-bold text-dark">
                            ឈ្មោះ / លេខបន្ទប់ <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="edit_classroom_name" class="form-control" required>
                        <div class="invalid-feedback" id="edit_classroom_error_msg"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_classroom_building" class="form-label small fw-bold text-dark">
                            អគារ / ប្លុក <span class="text-muted fw-normal">(ស្រេចចិត្ត)</span>
                        </label>
                        <input type="text" name="building" id="edit_classroom_building" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="edit_classroom_capacity" class="form-label small fw-bold text-dark">
                            ចំណុះសិស្ស (កៅអី) <span class="text-muted fw-normal">(ស្រេចចិត្ត)</span>
                        </label>
                        <input type="number" name="capacity" id="edit_classroom_capacity" class="form-control" min="1" max="1000">
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>ធ្វើបច្ចុប្បន្នភាពបន្ទប់រៀន
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ CREATE ACADEMIC YEAR MODAL ═══ --}}
<div class="modal fade" id="createAcademicYearModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6">
                    <i class="bi bi-calendar-plus text-warning me-2"></i>បន្ថែមឆ្នាំសិក្សាថ្មី
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createAcademicYearForm" action="{{ route('academic-years.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_modal" value="create_academic_year">
                <div class="modal-body p-4">
                    <div id="create_ay_duplicate_alert" class="alert alert-danger py-2 px-3 small d-none mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>ប្រុងប្រយ័ត្ន៖</strong> ឆ្នាំសិក្សា <span id="create_ay_duplicate_name" class="fw-bold"></span> នេះមានក្នុងប្រព័ន្ធរួចហើយ! សូមកុំបង្កើតឆ្នាំសិក្សាដែលដូចគ្នា។
                    </div>
                    <div class="mb-3">
                        <label for="create_ay_name" class="form-label small fw-bold text-dark">
                            ឆ្នាំសិក្សា <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="create_ay_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="ឧ. 2026-2027">
                        <div class="invalid-feedback" id="create_ay_error_msg">
                            @error('name') {{ $message }} @enderror
                        </div>
                        <div class="form-text text-muted small">ទម្រង់ទូទៅគឺ ឆ្នាំ-ឆ្នាំ (YYYY-YYYY)។</div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="create_ay_start_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទចាប់ផ្តើម <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="start_date" id="create_ay_start_date" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="create_ay_end_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទបញ្ចប់ <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="end_date" id="create_ay_end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="create_ay_is_active">
                        <label class="form-check-label small fw-semibold text-dark" for="create_ay_is_active">
                            កំណត់ជាឆ្នាំសិក្សាសកម្ម (Active)
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកឆ្នាំសិក្សា
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ EDIT ACADEMIC YEAR MODAL ═══ --}}
<div class="modal fade" id="editAcademicYearModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលឆ្នាំសិក្សា
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAcademicYearForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_ay_id" value="">
                <div class="modal-body p-4">
                    <div id="edit_ay_duplicate_alert" class="alert alert-danger py-2 px-3 small d-none mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>ប្រុងប្រយ័ត្ន៖</strong> ឆ្នាំសិក្សា <span id="edit_ay_duplicate_name" class="fw-bold"></span> នេះមានក្នុងប្រព័ន្ធរួចហើយ! សូមកុំដាក់ឈ្មោះដូចគ្នា។
                    </div>
                    <div class="mb-3">
                        <label for="edit_ay_name" class="form-label small fw-bold text-dark">
                            ឆ្នាំសិក្សា <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="edit_ay_name" class="form-control" required>
                        <div class="invalid-feedback" id="edit_ay_error_msg"></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="edit_ay_start_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទចាប់ផ្តើម <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="start_date" id="edit_ay_start_date" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="edit_ay_end_date" class="form-label small fw-bold text-dark">
                                កាលបរិច្ឆេទបញ្ចប់ <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="end_date" id="edit_ay_end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-check form-switch pt-1">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_ay_is_active">
                        <label class="form-check-label small fw-semibold text-dark" for="edit_ay_is_active">
                            កំណត់ជាឆ្នាំសិក្សាសកម្ម (Active)
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>ធ្វើបច្ចុប្បន្នភាពឆ្នាំសិក្សា
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ CREATE SUBJECT MODAL ═══ --}}
<div class="modal fade" id="createSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 720px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark mb-0" style="font-size: 1rem;">
                    <i class="bi bi-book text-primary me-2"></i>បន្ថែមមុខវិជ្ជាថ្មី
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body p-3.5 px-4">
                    {{-- 1. Subject Code --}}
                    <div class="mb-2.5">
                        <label for="create_subject_code" class="form-label small fw-bold text-dark mb-1">
                            កូដមុខវិជ្ជា <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="height: 38px;">
                                <i class="bi bi-lock-fill text-warning"></i>
                            </span>
                            <input type="text" name="code" id="create_subject_code" class="form-control font-monospace bg-light border-start-0 fw-bold" value="{{ $nextSubjectCode ?? '' }}" required readonly tabindex="-1" placeholder="e.g. S001" style="height: 38px; font-size: 0.88rem; cursor: not-allowed;" title="កូដមុខវិជ្ជាត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ និងចាក់សោ">
                        </div>
                        <div class="form-text text-muted small mt-1" style="font-size: 0.74rem;">
                            <i class="bi bi-lock-fill text-warning me-1"></i>បង្កើតដោយស្វ័យប្រវត្តិ — ចាក់សោ (Locked) មិនអាចកែប្រែបានទេ
                        </div>
                    </div>

                    {{-- 2. Subject Name --}}
                    <div class="mb-2.5">
                        <label for="create_subject_name" class="form-label small fw-bold text-dark mb-1">
                            ឈ្មោះមុខវិជ្ជា <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="create_subject_name" class="form-control" required placeholder="ឧ. Mathematics, ភាសាខ្មែរ..." style="height: 38px; font-size: 0.88rem;">
                    </div>

                    {{-- 3. Subject Type --}}
                    <div class="mb-2.5">
                        <label for="create_subject_type" class="form-label small fw-bold text-dark mb-1">
                            ប្រភេទ
                        </label>
                        <select name="type" id="create_subject_type" class="form-select" style="height: 38px; font-size: 0.88rem;">
                            <option value="Core" selected>មុខវិជ្ជាស្នូល (Core)</option>
                            <option value="Mandatory">កាតព្វកិច្ច (Mandatory)</option>
                        </select>
                    </div>

                    {{-- 4. Assign to Classes --}}
                    <div class="mb-1">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label small fw-bold text-dark mb-0">
                                ដាក់សម្រាប់ថ្នាក់
                            </label>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary py-0.5 px-2" onclick="toggleAllSubjectClasses('create', true)" style="font-size:0.75rem">
                                    <i class="bi bi-check-all me-1"></i>ជ្រើសរើសទាំងអស់
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2" onclick="toggleAllSubjectClasses('create', false)" style="font-size:0.75rem">
                                    <i class="bi bi-x me-1"></i>ដកទាំងអស់
                                </button>
                            </div>
                        </div>

                        <div class="row g-2" style="max-height: 220px; overflow-y: auto;">
                            @forelse($classes as $classItem)
                                <div class="col-md-4 col-sm-6">
                                    <label class="class-card-option d-flex align-items-center gap-2 p-2 rounded-2 border h-100 cursor-pointer w-100"
                                           for="create_class_{{ $classItem->id }}"
                                           id="create_class_label_{{ $classItem->id }}">
                                        <input type="checkbox"
                                               name="class_ids[]"
                                               value="{{ $classItem->id }}"
                                               id="create_class_{{ $classItem->id }}"
                                               class="form-check-input mt-0 create-class-checkbox flex-shrink-0"
                                               onchange="updateClassCardStyle(this, 'create_class_label_')">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.83rem;">
                                                {{ $classItem->grade->name ?? 'ថ្នាក់' }}{{ $classItem->section ? ' — ' . $classItem->section->name : '' }}
                                            </div>
                                            @if($classItem->academic_year)
                                                <div class="text-muted" style="font-size:0.70rem; line-height: 1.2;">{{ $classItem->academic_year->name }}</div>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12 text-muted small p-2">មិនទាន់មានថ្នាក់រៀនក្នុងប្រព័ន្ធនៅឡើយទេ។</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-2.5 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3.5 py-1.5" style="font-size:0.84rem;" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4 py-1.5" style="font-size:0.84rem;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ EDIT SUBJECT MODAL ═══ --}}
<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 720px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark mb-0" style="font-size: 1rem;">
                    <i class="bi bi-pencil-square text-primary me-2"></i>កែសម្រួលមុខវិជ្ជា
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubjectForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-3.5 px-4">
                    {{-- 1. Subject Code --}}
                    <div class="mb-2.5">
                        <label for="edit_subject_code" class="form-label small fw-bold text-dark mb-1">
                            កូដមុខវិជ្ជា (ID) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="height: 38px;">
                                <i class="bi bi-lock-fill text-warning"></i>
                            </span>
                            <input type="text" name="code" id="edit_subject_code" class="form-control font-monospace bg-light border-start-0 fw-bold" required readonly tabindex="-1" placeholder="e.g. S001" style="height: 38px; font-size: 0.88rem; cursor: not-allowed;" title="កូដមុខវិជ្ជាត្រូវបានចាក់សោ">
                        </div>
                        <div class="form-text text-muted small mt-1" style="font-size: 0.74rem;">
                            <i class="bi bi-lock-fill text-warning me-1"></i>ចាក់សោ (Locked) — មិនអាចកែប្រែកូដសម្គាល់បានទេ
                        </div>
                    </div>

                    {{-- 2. Subject Name --}}
                    <div class="mb-2.5">
                        <label for="edit_subject_name" class="form-label small fw-bold text-dark mb-1">
                            ឈ្មោះមុខវិជ្ជា <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="edit_subject_name" class="form-control" required placeholder="ឧ. Mathematics..." style="height: 38px; font-size: 0.88rem;">
                    </div>

                    {{-- 3. Subject Type --}}
                    <div class="mb-2.5">
                        <label for="edit_subject_type" class="form-label small fw-bold text-dark mb-1">
                            ប្រភេទ
                        </label>
                        <select name="type" id="edit_subject_type" class="form-select" style="height: 38px; font-size: 0.88rem;">
                            <option value="Core">មុខវិជ្ជាស្នូល (Core)</option>
                            <option value="Mandatory">កាតព្វកិច្ច (Mandatory)</option>
                        </select>
                    </div>

                    {{-- 4. Assign to Classes --}}
                    <div class="mb-1">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label small fw-bold text-dark mb-0">
                                ដាក់សម្រាប់ថ្នាក់
                            </label>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary py-0.5 px-2" onclick="toggleAllSubjectClasses('edit', true)" style="font-size:0.75rem">
                                    <i class="bi bi-check-all me-1"></i>ជ្រើសរើសទាំងអស់
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2" onclick="toggleAllSubjectClasses('edit', false)" style="font-size:0.75rem">
                                    <i class="bi bi-x me-1"></i>ដកទាំងអស់
                                </button>
                            </div>
                        </div>

                        <div class="row g-2" style="max-height: 220px; overflow-y: auto;">
                            @forelse($classes as $classItem)
                                <div class="col-md-4 col-sm-6">
                                    <label class="class-card-option d-flex align-items-center gap-2 p-2 rounded-2 border h-100 cursor-pointer w-100"
                                           for="edit_class_{{ $classItem->id }}"
                                           id="edit_class_label_{{ $classItem->id }}">
                                        <input type="checkbox"
                                               name="class_ids[]"
                                               value="{{ $classItem->id }}"
                                               id="edit_class_{{ $classItem->id }}"
                                               class="form-check-input mt-0 edit-class-checkbox flex-shrink-0"
                                               onchange="updateClassCardStyle(this, 'edit_class_label_')">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.83rem;">
                                                {{ $classItem->grade->name ?? 'ថ្នាក់' }}{{ $classItem->section ? ' — ' . $classItem->section->name : '' }}
                                            </div>
                                            @if($classItem->academic_year)
                                                <div class="text-muted" style="font-size:0.70rem; line-height: 1.2;">{{ $classItem->academic_year->name }}</div>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @empty
                                <div class="col-12 text-muted small p-2">មិនទាន់មានថ្នាក់រៀនក្នុងប្រព័ន្ធនៅឡើយទេ។</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-2.5 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3.5 py-1.5" style="font-size:0.84rem;" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary-action px-4 py-1.5" style="font-size:0.84rem;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.class-card-option {
    cursor: pointer;
    background: #ffffff;
    border-color: #e2e8f0;
    transition: all 0.15s ease;
    user-select: none;
}
.class-card-option:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.class-card-option.is-selected {
    background: #eff6ff;
    border-color: #3b82f6;
}
</style>

{{-- ═══ SHARED DELETE CONFIRM MODAL ═══ --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10"
                          style="width:56px;height:56px">
                        <i class="bi bi-trash text-danger fs-3"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-1">លុប <span id="deleteEntityType">ទិន្នន័យ</span>?</h6>
                <p class="text-muted small mb-3">
                    តើអ្នកពិតជាចង់លុប <strong id="deleteEntityName"></strong> មែនទេ?
                    សកម្មភាពនេះមិនអាចត្រឡប់វិញបានឡើយ។
                </p>
                <div id="deleteEntityExtra" class="mb-3 text-start" style="display:none"></div>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger px-4">លុប</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ SYSTEM ALERT / DUPLICATE WARNING MODAL ═══ --}}
<div class="modal fade" id="systemAlertModal" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span id="systemAlertIconContainer" class="d-inline-flex align-items-center justify-content-center rounded-circle"
                          style="width: 64px; height: 64px; background: #fffbeb; border: 3px solid #fef3c7; color: #d97706; box-shadow: 0 4px 14px rgba(217, 119, 6, 0.12);">
                        <i id="systemAlertIcon" class="bi bi-exclamation-triangle-fill fs-2"></i>
                    </span>
                </div>
                <h5 class="fw-bold text-dark mb-2" id="systemAlertTitle" style="font-size: 1.15rem;">
                    មិនអនុញ្ញាតឱ្យបង្កើតដូចគ្នាទេ!
                </h5>
                <p class="text-muted small mb-3 px-2" id="systemAlertMessage" style="line-height: 1.6;">
                    ឆ្នាំសិក្សានេះមានក្នុងប្រព័ន្ធរួចហើយ។
                </p>
                <div id="systemAlertBadge" class="p-2 mb-3 rounded-3 bg-light border text-dark fw-bold small d-none"></div>
                <div class="d-flex justify-content-center pt-1">
                    <button type="button" class="btn-primary-action px-4 py-2" data-bs-dismiss="modal" style="min-width: 140px; justify-content: center; font-size: 0.88rem;">
                        <i class="bi bi-check2-circle me-1.5"></i>យល់ព្រម
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ GRADE & SECTION MODALS ═══ --}}
@include('academics.partials.grade-section-modals')

@endsection

@section('scripts')
<script>
    // Initialise tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    function confirmDelete(actionUrl, name, entityType, extraHtml = '') {
        document.getElementById('deleteEntityName').textContent = name;
        document.getElementById('deleteEntityType').textContent = entityType;
        document.getElementById('deleteForm').action = actionUrl;
        const extraEl = document.getElementById('deleteEntityExtra');
        if (extraHtml) {
            extraEl.innerHTML = extraHtml;
            extraEl.style.display = 'block';
        } else {
            extraEl.innerHTML = '';
            extraEl.style.display = 'none';
        }
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function confirmDeleteSubject(actionUrl, name, classNames) {
        let extra = '';
        if (classNames && classNames.length > 0) {
            extra = `<div class="p-3 rounded-3 bg-danger-subtle border border-danger-subtle text-start small">
                <div class="fw-bold text-danger mb-1.5">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    មុខវិជ្ជានេះត្រូវបានដាក់បង្រៀនក្នុង <strong>${classNames.length} ថ្នាក់</strong>៖
                </div>
                <div class="d-flex flex-wrap gap-1 mb-2">
                    ${classNames.map(c => `<span class="badge bg-white text-dark border">${c}</span>`).join('')}
                </div>
                <div class="text-danger-emphasis small">
                    <i class="bi bi-info-circle me-1"></i>ការលុបមុខវិជ្ជានេះ នឹងផ្តាច់មុខវិជ្ជានេះចេញពីថ្នាក់ទាំងអស់ខាងលើ។
                </div>
            </div>`;
        }
        confirmDelete(actionUrl, name, 'មុខវិជ្ជា', extra);
    }

    function updateClassCardStyle(checkbox, labelPrefix) {
        const label = document.getElementById(labelPrefix + checkbox.value);
        if (!label) return;
        if (checkbox.checked) {
            label.classList.add('is-selected');
        } else {
            label.classList.remove('is-selected');
        }
    }

    function toggleAllSubjectClasses(mode, select) {
        const selector = mode === 'create' ? '.create-class-checkbox' : '.edit-class-checkbox';
        const labelPrefix = mode === 'create' ? 'create_class_label_' : 'edit_class_label_';
        document.querySelectorAll(selector).forEach(cb => {
            cb.checked = select;
            updateClassCardStyle(cb, labelPrefix);
        });
    }

    function openEditSubjectModal(id, name, code, type, assignedClassIds) {
        document.getElementById('edit_subject_name').value = name;
        document.getElementById('edit_subject_code').value = code;
        document.getElementById('edit_subject_type').value = type || 'Core';
        document.getElementById('editSubjectForm').action = "{{ url('subjects') }}/" + id;

        const classIds = assignedClassIds || [];
        document.querySelectorAll('.edit-class-checkbox').forEach(cb => {
            cb.checked = classIds.includes(parseInt(cb.value));
            updateClassCardStyle(cb, 'edit_class_label_');
        });

        new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
    }

    function openEditClassroomModal(id, name, building, capacity) {
        document.getElementById('edit_classroom_id').value = id;
        document.getElementById('edit_classroom_name').value = name;
        document.getElementById('edit_classroom_building').value = building || '';
        document.getElementById('edit_classroom_capacity').value = capacity || '';
        document.getElementById('editClassroomForm').action = "{{ url('classrooms') }}/" + id;
        const alertEl = document.getElementById('edit_classroom_duplicate_alert');
        const inputEl = document.getElementById('edit_classroom_name');
        if (alertEl) alertEl.classList.add('d-none');
        if (inputEl) inputEl.classList.remove('is-invalid');
        new bootstrap.Modal(document.getElementById('editClassroomModal')).show();
    }

    function openEditAcademicYearModal(id, name, startDate, endDate, isActive) {
        document.getElementById('edit_ay_id').value = id;
        document.getElementById('edit_ay_name').value = name;
        document.getElementById('edit_ay_start_date').value = startDate || '';
        document.getElementById('edit_ay_end_date').value = endDate || '';
        document.getElementById('edit_ay_is_active').checked = (isActive == 1);
        document.getElementById('editAcademicYearForm').action = "{{ url('academic-years') }}/" + id;
        const alertEl = document.getElementById('edit_ay_duplicate_alert');
        const inputEl = document.getElementById('edit_ay_name');
        if (alertEl) alertEl.classList.add('d-none');
        if (inputEl) inputEl.classList.remove('is-invalid');
        new bootstrap.Modal(document.getElementById('editAcademicYearModal')).show();
    }

    // ── Duplicate Academic Year Detection ──
    document.addEventListener('DOMContentLoaded', function () {
        const existingYears = @json($academicYears->map(fn($y) => ['id' => $y->id, 'name' => trim(strtolower($y->name))]));

        // Create modal duplicate check
        const createInput = document.getElementById('create_ay_name');
        const createAlert = document.getElementById('create_ay_duplicate_alert');
        const createAlertName = document.getElementById('create_ay_duplicate_name');
        const createErrorMsg = document.getElementById('create_ay_error_msg');
        const createForm = document.getElementById('createAcademicYearForm');

        function checkCreateDuplicate() {
            if (!createInput) return true;
            const val = createInput.value.trim().toLowerCase();
            const exists = existingYears.some(y => y.name === val);
            if (exists && val.length > 0) {
                if (createAlert) {
                    createAlert.classList.remove('d-none');
                    if (createAlertName) createAlertName.textContent = `«${createInput.value.trim()}»`;
                }
                createInput.classList.add('is-invalid');
                if (createErrorMsg) createErrorMsg.textContent = `ឆ្នាំសិក្សា «${createInput.value.trim()}» មានរួចហើយ! សូមកុំបង្កើតឆ្នាំសិក្សាដែលដូចគ្នា។`;
                return false;
            } else {
                if (createAlert) createAlert.classList.add('d-none');
                createInput.classList.remove('is-invalid');
                if (createErrorMsg) createErrorMsg.textContent = '';
                return true;
            }
        }

        if (createInput) {
            createInput.addEventListener('input', checkCreateDuplicate);
        }

        function showSystemAlert(title, message, badgeText = '', type = 'warning') {
            document.getElementById('systemAlertTitle').textContent = title;
            document.getElementById('systemAlertMessage').innerHTML = message;
            const badgeEl = document.getElementById('systemAlertBadge');
            if (badgeText) {
                badgeEl.textContent = badgeText;
                badgeEl.classList.remove('d-none');
            } else {
                badgeEl.classList.add('d-none');
            }
            const iconContainer = document.getElementById('systemAlertIconContainer');
            const iconEl = document.getElementById('systemAlertIcon');
            if (type === 'danger' || type === 'error') {
                if (iconContainer) {
                    iconContainer.style.background = '#fef2f2';
                    iconContainer.style.borderColor = '#fee2e2';
                    iconContainer.style.color = '#dc2626';
                    iconContainer.style.boxShadow = '0 4px 14px rgba(220, 38, 38, 0.15)';
                }
                if (iconEl) iconEl.className = 'bi bi-x-octagon-fill fs-2';
            } else {
                if (iconContainer) {
                    iconContainer.style.background = '#fffbeb';
                    iconContainer.style.borderColor = '#fef3c7';
                    iconContainer.style.color = '#d97706';
                    iconContainer.style.boxShadow = '0 4px 14px rgba(217, 119, 6, 0.12)';
                }
                if (iconEl) iconEl.className = 'bi bi-exclamation-triangle-fill fs-2';
            }
            const modalEl = document.getElementById('systemAlertModal');
            const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            bsModal.show();
        }

        if (createForm) {
            createForm.addEventListener('submit', function (e) {
                if (!checkCreateDuplicate()) {
                    e.preventDefault();
                    e.stopPropagation();
                    const yearVal = createInput.value.trim();
                    showSystemAlert(
                        'មិនអនុញ្ញាតឱ្យបង្កើតដូចគ្នាទេ!',
                        `ឆ្នាំសិក្សា <strong>«${yearVal}»</strong> មានក្នុងប្រព័ន្ធរួចហើយ។ សូមកុំបង្កើតឆ្នាំសិក្សាដែលដូចគ្នា!`,
                        `ឆ្នាំសិក្សាស្ទួន៖ ${yearVal}`
                    );
                }
            });
        }

        // Edit modal duplicate check
        const editInput = document.getElementById('edit_ay_name');
        const editAlert = document.getElementById('edit_ay_duplicate_alert');
        const editAlertName = document.getElementById('edit_ay_duplicate_name');
        const editErrorMsg = document.getElementById('edit_ay_error_msg');
        const editForm = document.getElementById('editAcademicYearForm');

        function checkEditDuplicate() {
            if (!editInput) return true;
            const val = editInput.value.trim().toLowerCase();
            const currentId = parseInt(document.getElementById('edit_ay_id').value);
            const exists = existingYears.some(y => y.id !== currentId && y.name === val);
            if (exists && val.length > 0) {
                if (editAlert) {
                    editAlert.classList.remove('d-none');
                    if (editAlertName) editAlertName.textContent = `«${editInput.value.trim()}»`;
                }
                editInput.classList.add('is-invalid');
                if (editErrorMsg) editErrorMsg.textContent = `ឆ្នាំសិក្សា «${editInput.value.trim()}» មានរួចហើយ! សូមកុំដាក់ឈ្មោះដូចគ្នា។`;
                return false;
            } else {
                if (editAlert) editAlert.classList.add('d-none');
                editInput.classList.remove('is-invalid');
                if (editErrorMsg) editErrorMsg.textContent = '';
                return true;
            }
        }

        if (editInput) {
            editInput.addEventListener('input', checkEditDuplicate);
        }

        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                if (!checkEditDuplicate()) {
                    e.preventDefault();
                    e.stopPropagation();
                    const yearVal = editInput.value.trim();
                    showSystemAlert(
                        'ឈ្មោះឆ្នាំសិក្សាមានរួចហើយ!',
                        `ឆ្នាំសិក្សា <strong>«${yearVal}»</strong> មានក្នុងប្រព័ន្ធរួចហើយ។ សូមកុំដាក់ឈ្មោះដូចគ្នា!`,
                        `ឆ្នាំសិក្សាស្ទួន៖ ${yearVal}`
                    );
                }
            });
        }

        @if($errors->any() && old('_modal') === 'create_academic_year')
        const createModalEl = document.getElementById('createAcademicYearModal');
        if (createModalEl) {
            new bootstrap.Modal(createModalEl).show();
        }
        @endif

        // ── Existing Classrooms List for duplicate detection ──
        const existingClassrooms = @json(\App\Models\Academics\Classroom::all()->map(fn($c) => [
            'id' => $c->id,
            'name' => trim(strtolower($c->name)),
            'building' => trim(strtolower($c->building ?? ''))
        ]));

        function checkClassroomDuplicate(mode) {
            const isCreate = mode === 'create';
            const nameInput = document.getElementById(isCreate ? 'create_classroom_name' : 'edit_classroom_name');
            const bldInput = document.getElementById(isCreate ? 'create_classroom_building' : 'edit_classroom_building');
            const alertEl = document.getElementById(isCreate ? 'create_classroom_duplicate_alert' : 'edit_classroom_duplicate_alert');
            const alertName = document.getElementById(isCreate ? 'create_classroom_duplicate_name' : 'edit_classroom_duplicate_name');
            const errorMsg = document.getElementById(isCreate ? 'create_classroom_error_msg' : 'edit_classroom_error_msg');
            const currentId = isCreate ? null : parseInt(document.getElementById('edit_classroom_id').value);

            if (!nameInput) return true;
            const nameVal = nameInput.value.trim().toLowerCase();
            const bldVal = bldInput ? bldInput.value.trim().toLowerCase() : '';

            const exists = existingClassrooms.some(c => {
                if (!isCreate && c.id === currentId) return false;
                return c.name === nameVal && c.building === bldVal;
            });

            if (exists && nameVal.length > 0) {
                if (alertEl) {
                    alertEl.classList.remove('d-none');
                    if (alertName) alertName.textContent = `«${nameInput.value.trim()}»${bldVal ? ' (អគារ ' + bldInput.value.trim() + ')' : ''}`;
                }
                nameInput.classList.add('is-invalid');
                if (errorMsg) errorMsg.textContent = `បន្ទប់រៀននេះមានរួចហើយ! សូមកុំបង្កើតដូចគ្នា។`;
                return false;
            } else {
                if (alertEl) alertEl.classList.add('d-none');
                nameInput.classList.remove('is-invalid');
                if (errorMsg) errorMsg.textContent = '';
                return true;
            }
        }

        const createCrName = document.getElementById('create_classroom_name');
        const createCrBld = document.getElementById('create_classroom_building');
        const createCrForm = document.getElementById('createClassroomForm');

        if (createCrName) createCrName.addEventListener('input', () => checkClassroomDuplicate('create'));
        if (createCrBld) createCrBld.addEventListener('input', () => checkClassroomDuplicate('create'));

        if (createCrForm) {
            createCrForm.addEventListener('submit', function (e) {
                if (!checkClassroomDuplicate('create')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const rName = createCrName.value.trim();
                    const bName = createCrBld ? createCrBld.value.trim() : '';
                    showSystemAlert(
                        'មិនអនុញ្ញាតឱ្យបង្កើតដូចគ្នាទេ!',
                        `បន្ទប់រៀន <strong>«${rName}»</strong> ${bName ? 'ក្នុងអគារ <strong>«' + bName + '»</strong> ' : ''}មានក្នុងប្រព័ន្ធរួចហើយ។ សូមកុំបង្កើតបន្ទប់រៀនដែលដូចគ្នា!`,
                        `បន្ទប់រៀនស្ទួន៖ ${rName}${bName ? ' (' + bName + ')' : ''}`
                    );
                }
            });
        }

        const editCrName = document.getElementById('edit_classroom_name');
        const editCrBld = document.getElementById('edit_classroom_building');
        const editCrForm = document.getElementById('editClassroomForm');

        if (editCrName) editCrName.addEventListener('input', () => checkClassroomDuplicate('edit'));
        if (editCrBld) editCrBld.addEventListener('input', () => checkClassroomDuplicate('edit'));

        if (editCrForm) {
            editCrForm.addEventListener('submit', function (e) {
                if (!checkClassroomDuplicate('edit')) {
                    e.preventDefault();
                    e.stopPropagation();
                    const rName = editCrName.value.trim();
                    const bName = editCrBld ? editCrBld.value.trim() : '';
                    showSystemAlert(
                        'ឈ្មោះបន្ទប់រៀនមានរួចហើយ!',
                        `បន្ទប់រៀន <strong>«${rName}»</strong> ${bName ? 'ក្នុងអគារ <strong>«' + bName + '»</strong> ' : ''}មានក្នុងប្រព័ន្ធរួចហើយ។ សូមកុំដាក់ឈ្មោះដូចគ្នា!`,
                        `បន្ទប់រៀនស្ទួន៖ ${rName}${bName ? ' (' + bName + ')' : ''}`
                    );
                }
            });
        }

        @if($errors->any() && old('_modal') === 'create_classroom')
        const createCrModalEl = document.getElementById('createClassroomModal');
        if (createCrModalEl) {
            new bootstrap.Modal(createCrModalEl).show();
        }
        @endif
    });
</script>
@endsection
