@extends('layouts.app')

@section('title', 'ការគ្រប់គ្រងការប្រឡង — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការគ្រប់គ្រងការប្រឡង</li>
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
    color: var(--text-dark, #1e293b);
    margin-bottom: 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px; height: 38px;
    background: #fef3c7;
    color: #b45309;
    border-radius: var(--radius-md, 8px);
    font-size: 1.2rem;
}
.page-subtitle { color: var(--text-muted, #64748b); font-size: 0.85rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   MAIN TAB NAV
═══════════════════════════════════════════════════════════════════ */
.examinations-tabs-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.examinations-tabs-wrapper::-webkit-scrollbar { display: none; }

.examinations-tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid var(--border-soft, #e2e8f0);
    white-space: nowrap;
    min-width: max-content;
}
.tab-link {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.75rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-muted, #64748b);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: color 0.15s, border-color 0.15s;
    cursor: pointer;
}
.tab-link:hover { color: var(--text-dark, #1e293b); }
.tab-link.active {
    color: #b45309;
    border-bottom-color: var(--gold, #e8a838);
}
.tab-link .tab-icon { font-size: 0.95rem; }
.tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 18px;
    padding: 0 5px;
    background: #f1f5f9;
    color: var(--text-muted, #64748b);
    border-radius: 999px;
    font-size: 0.68rem;
    font-weight: 700;
}
.tab-link.active .tab-count {
    background: #fef3c7;
    color: #92400e;
}

/* ═══════════════════════════════════════════════════════════════════
   SUBTAB NAV
═══════════════════════════════════════════════════════════════════ */
.subtabs-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    margin-bottom: 0.65rem;
}
.subtabs-wrapper::-webkit-scrollbar { display: none; }

.subtabs-nav {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 10px;
    gap: 4px;
}
.subtab-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.9rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-muted, #64748b);
    text-decoration: none;
    border-radius: 7px;
    transition: all 0.15s;
}
.subtab-link:hover { color: var(--text-dark, #1e293b); }
.subtab-link.active {
    background: #fff;
    color: #0f172a;
    box-shadow: 0 1px 3px rgba(0,0,0,.08);
}
.subtab-badge {
    font-size: 0.65rem;
    padding: 1px 6px;
    border-radius: 999px;
    background: #e2e8f0;
    color: #475569;
}
.subtab-link.active .subtab-badge {
    background: #fef3c7;
    color: #92400e;
}

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-lg, 10px);
    padding: 0.875rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.search-wrapper {
    position: relative;
    min-width: 220px;
    flex: 1 1 220px;
    max-width: 320px;
}
.search-wrapper i {
    position: absolute;
    left: 0.85rem; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted, #64748b);
    font-size: 0.85rem;
}
.search-wrapper input {
    width: 100%;
    padding: 0.42rem 0.85rem 0.42rem 2.2rem;
    font-size: 0.85rem;
    border: 1px solid var(--border, #cbd5e1);
    border-radius: var(--radius-md, 8px);
    transition: all 0.2s;
    background: #fff;
}
.search-wrapper input:focus {
    border-color: var(--gold, #e8a838);
    box-shadow: 0 0 0 3px rgba(232, 168, 56, 0.2);
    outline: none;
}
.filter-select {
    font-size: 0.83rem;
    padding: 0.42rem 2rem 0.42rem 0.75rem;
    border: 1px solid var(--border, #cbd5e1);
    border-radius: var(--radius-md, 8px);
    background: #fff;
    flex: 0 0 auto;
}
.filter-select:focus {
    border-color: var(--gold, #e8a838);
    box-shadow: 0 0 0 3px rgba(232, 168, 56, 0.2);
    outline: none;
}
.btn-filter {
    padding: 0.42rem 0.95rem;
    font-size: 0.83rem;
    font-weight: 600;
    background: #f8fafc;
    border: 1px solid var(--border, #cbd5e1);
    border-radius: var(--radius-md, 8px);
    color: var(--text-dark, #1e293b);
    cursor: pointer;
    transition: all 0.15s;
}
.btn-filter:hover { background: #f1f5f9; }

/* ═══════════════════════════════════════════════════════════════════
   TABLE & CARDS
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-lg, 10px);
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    overflow: hidden;
}
.table-custom { margin: 0; width: 100%; }
.table-custom thead th {
    background: #f8fafc;
    color: var(--text-muted, #64748b);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border-soft, #e2e8f0);
    white-space: nowrap;
}
.table-custom tbody td {
    padding: 0.85rem 1rem;
    font-size: 0.83rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-soft, #e2e8f0);
    color: var(--text-dark, #1e293b);
}
.table-custom tbody tr:last-child td { border-bottom: none; }
.table-custom tbody tr:hover td { background-color: #fafbfd; }

/* ═══════════════════════════════════════════════════════════════════
   ACTIONS & BUTTONS
═══════════════════════════════════════════════════════════════════ */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px;
    border-radius: var(--radius-sm, 6px);
    color: var(--text-muted, #64748b);
    transition: all 0.2s;
    background: transparent; border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark, #1e293b); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

.btn-primary-action {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 1.1rem;
    font-size: 0.85rem;
    font-weight: 600;
    background: var(--gold, #e8a838);
    color: #fff;
    border: none;
    border-radius: var(--radius-md, 8px);
    text-decoration: none;
    transition: background 0.15s, box-shadow 0.15s;
    cursor: pointer;
}
.btn-primary-action:hover {
    background: #d97706;
    color: #fff;
    box-shadow: 0 4px 12px rgba(232,168,56,0.35);
}
</style>
@endpush

@section('content')

{{-- ═══ FLASH MESSAGES ═══ --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
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
            <div class="page-title-icon"><i class="bi bi-award-fill"></i></div>
            ការគ្រប់គ្រងការប្រឡង
        </h1>
        <p class="page-subtitle">កាលវិភាគប្រឡងរួម វិញ្ញាសាប្រឡង បញ្ចូលពិន្ទុ និងការកំណត់កម្រិតនិទ្ទេស</p>
    </div>
    <div>
        @if($activeTab === 'exams' && ($canManageExams ?? false))
            <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createExamModal" id="btn-add-action">
                <i class="bi bi-plus-lg"></i>បង្កើតការប្រឡង
            </button>
        @elseif($activeTab === 'schedules' && ($canManageExams ?? false))
            <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createScheduleModal" id="btn-add-action">
                <i class="bi bi-plus-lg"></i>បង្កើតកាលវិភាគ
            </button>
        @elseif($activeTab === 'subjects' && ($canManageExams ?? false))
            <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createExamSubjectModal" id="btn-add-action">
                <i class="bi bi-plus-lg"></i>បន្ថែមមុខវិជ្ជាប្រឡង
            </button>
        @elseif($activeTab === 'results')
            @if($subtab === 'grades' && request('exam_id') && ($canManageExams ?? false))
                <form action="{{ route('exams.calculate-grades', request('exam_id')) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-primary-action shadow-sm">
                        <i class="bi bi-arrow-repeat"></i>គណនាពិន្ទុឡើងវិញ
                    </button>
                </form>
            @endif
        @elseif($activeTab === 'settings' && ($canManageSettings ?? false))
            @if($subtab === 'grade-scales')
                <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createGradeScaleModal" id="btn-add-action">
                    <i class="bi bi-plus-lg"></i>បន្ថែមកម្រិតនិទ្ទេស
                </button>
            @elseif($subtab === 'exam-types')
                <button type="button" class="btn-primary-action shadow-sm" data-bs-toggle="modal" data-bs-target="#createExamTypeModal" id="btn-add-action">
                    <i class="bi bi-plus-lg"></i>បន្ថែមប្រភេទប្រឡង
                </button>
            @endif
        @endif
    </div>
</div>

{{-- ═══ MAIN TABS ═══ --}}
<div class="examinations-tabs-wrapper mb-2.5">
    <nav class="examinations-tabs" role="tablist" aria-label="Examinations tabs">
        {{-- TAB 1: Exams --}}
        <a href="{{ route('examinations.index', ['tab' => 'exams']) }}"
           class="tab-link {{ $activeTab === 'exams' ? 'active' : '' }}"
           role="tab" aria-selected="{{ $activeTab === 'exams' ? 'true' : 'false' }}">
            <i class="bi bi-journal-check tab-icon"></i>
            <span>ការប្រឡង</span>
            <span class="tab-count">{{ $counts['exams'] ?? 0 }}</span>
        </a>

        {{-- TAB 2: Schedules --}}
        <a href="{{ route('examinations.index', ['tab' => 'schedules']) }}"
           class="tab-link {{ $activeTab === 'schedules' ? 'active' : '' }}"
           role="tab" aria-selected="{{ $activeTab === 'schedules' ? 'true' : 'false' }}">
            <i class="bi bi-calendar3 tab-icon"></i>
            <span>កាលវិភាគ</span>
            <span class="tab-count">{{ $counts['schedules'] ?? 0 }}</span>
        </a>

        {{-- TAB 3: Subjects --}}
        <a href="{{ route('examinations.index', ['tab' => 'subjects']) }}"
           class="tab-link {{ $activeTab === 'subjects' ? 'active' : '' }}"
           role="tab" aria-selected="{{ $activeTab === 'subjects' ? 'true' : 'false' }}">
            <i class="bi bi-file-earmark-ruled tab-icon"></i>
            <span>មុខវិជ្ជា</span>
            <span class="tab-count">{{ $counts['subjects'] ?? 0 }}</span>
        </a>

        {{-- TAB 4: Results & Grades --}}
        <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => ($activeTab === 'results' ? $subtab : 'results')]) }}"
           class="tab-link {{ $activeTab === 'results' ? 'active' : '' }}"
           role="tab" aria-selected="{{ $activeTab === 'results' ? 'true' : 'false' }}">
            <i class="bi bi-check2-square tab-icon"></i>
            <span>លទ្ធផល & ពិន្ទុ</span>
            <span class="tab-count">{{ ($counts['results'] ?? 0) + ($counts['grades'] ?? 0) }}</span>
        </a>

        {{-- TAB 5: Settings (Admins only) --}}
        @if($canManageSettings ?? false)
        <a href="{{ route('examinations.index', ['tab' => 'settings', 'subtab' => ($activeTab === 'settings' ? $subtab : 'grade-scales')]) }}"
           class="tab-link {{ $activeTab === 'settings' ? 'active' : '' }}"
           role="tab" aria-selected="{{ $activeTab === 'settings' ? 'true' : 'false' }}">
            <i class="bi bi-gear tab-icon"></i>
            <span>ការកំណត់</span>
            <span class="tab-count">{{ ($counts['grade-scales'] ?? 0) + ($counts['exam-types'] ?? 0) }}</span>
        </a>
        @endif
    </nav>
</div>

{{-- ═══ TAB CONTENT ═══ --}}
@if($activeTab === 'exams')
    @include('examinations.tabs.exams')
@elseif($activeTab === 'schedules')
    @include('examinations.tabs.schedules')
@elseif($activeTab === 'subjects')
    @include('examinations.tabs.subjects')
@elseif($activeTab === 'results')
    @include('examinations.tabs.results-grades')
@elseif($activeTab === 'settings' && ($canManageSettings ?? false))
    @include('examinations.tabs.settings')
@endif

{{-- ═══ MODALS ═══ --}}
@include('examinations.modals')

@endsection

@section('scripts')
<script>
    function confirmDelete(actionUrl, name, entityType) {
        document.getElementById('deleteEntityName').textContent = name;
        document.getElementById('deleteEntityType').textContent = entityType;
        document.getElementById('deleteForm').action = actionUrl;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    function openEditExamModal(id, name, examTypeId, academicYearId, schoolTermId, startDate, endDate) {
        document.getElementById('edit_exam_name').value = name || '';
        document.getElementById('edit_exam_type_id').value = examTypeId || '';
        document.getElementById('edit_exam_academic_year_id').value = academicYearId || '';
        document.getElementById('edit_exam_school_term_id').value = schoolTermId || '';
        document.getElementById('edit_exam_start_date').value = startDate || '';
        document.getElementById('edit_exam_end_date').value = endDate || '';
        document.getElementById('editExamForm').action = "{{ url('exams') }}/" + id;
        new bootstrap.Modal(document.getElementById('editExamModal')).show();
    }

    function openEditScheduleModal(id, examId, classId, sectionId) {
        document.getElementById('edit_schedule_exam_id').value = examId || '';
        document.getElementById('edit_schedule_class_id').value = classId || '';
        document.getElementById('edit_schedule_section_id').value = sectionId || '';
        document.getElementById('editScheduleForm').action = "{{ url('exam-schedules') }}/" + id;
        new bootstrap.Modal(document.getElementById('editScheduleModal')).show();
    }

    function openCreateExamSubjectForSchedule(scheduleId) {
        const scheduleSelect = document.getElementById('create_esub_schedule_id');
        if (scheduleSelect) {
            scheduleSelect.value = scheduleId || '';
        }
        new bootstrap.Modal(document.getElementById('createExamSubjectModal')).show();
    }

    function openEditExamSubjectModal(id, examScheduleId, subjectId, examDate, startTime, endTime, maxMarks, passMarks) {
        document.getElementById('edit_esub_schedule_id').value = examScheduleId || '';
        document.getElementById('edit_esub_subject_id').value = subjectId || '';
        document.getElementById('edit_esub_exam_date').value = examDate || '';
        document.getElementById('edit_esub_start_time').value = startTime || '';
        document.getElementById('edit_esub_end_time').value = endTime || '';
        document.getElementById('edit_esub_max_marks').value = maxMarks || '100.00';
        document.getElementById('edit_esub_pass_marks').value = passMarks || '33.00';
        document.getElementById('editExamSubjectForm').action = "{{ url('exam-subjects') }}/" + id;
        new bootstrap.Modal(document.getElementById('editExamSubjectModal')).show();
    }

    function openEditGradeScaleModal(elOrId, name, grade, minPercentage, maxPercentage, gpaPoint) {
        let id = elOrId;
        if (typeof elOrId === 'object' && elOrId !== null) {
            id = elOrId.getAttribute('data-id');
            name = elOrId.getAttribute('data-name');
            grade = elOrId.getAttribute('data-grade');
            minPercentage = elOrId.getAttribute('data-min');
            maxPercentage = elOrId.getAttribute('data-max');
            gpaPoint = elOrId.getAttribute('data-gpa');
        }

        const nameEl = document.getElementById('edit_gs_name');
        if (nameEl) nameEl.value = name || '';

        const gradeEl = document.getElementById('edit_gs_grade');
        if (gradeEl) gradeEl.value = grade || '';

        const minEl = document.getElementById('edit_gs_min_percentage') || document.getElementById('edit_gs_min_pct');
        if (minEl) minEl.value = minPercentage || '';

        const maxEl = document.getElementById('edit_gs_max_percentage') || document.getElementById('edit_gs_max_pct');
        if (maxEl) maxEl.value = maxPercentage || '';

        const gpaEl = document.getElementById('edit_gs_gpa_point') || document.getElementById('edit_gs_gpa');
        if (gpaEl) gpaEl.value = (gpaPoint !== undefined && gpaPoint !== null && gpaPoint !== '') ? gpaPoint : '0.00';

        const form = document.getElementById('editGradeScaleForm');
        if (form) form.action = "{{ url('grade-scales') }}/" + id;

        const modalEl = document.getElementById('editGradeScaleModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }

    function openEditExamTypeModal(elOrId, name, description) {
        let id = elOrId;
        if (typeof elOrId === 'object' && elOrId !== null) {
            id = elOrId.getAttribute('data-id');
            name = elOrId.getAttribute('data-name');
            description = elOrId.getAttribute('data-description');
        }

        const nameEl = document.getElementById('edit_et_name');
        if (nameEl) nameEl.value = name || '';

        const descEl = document.getElementById('edit_et_description');
        if (descEl) descEl.value = description || '';

        const form = document.getElementById('editExamTypeForm');
        if (form) form.action = "{{ url('exam-types') }}/" + id;

        const modalEl = document.getElementById('editExamTypeModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    }
</script>
@endsection
