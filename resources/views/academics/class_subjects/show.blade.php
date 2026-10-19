@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតមុខវិជ្ជា ' . ($classSubject->subject->name ?? '') . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item"><a href="{{ route('academics.index', ['tab' => 'class-subjects']) }}" class="text-decoration-none text-muted">មុខវិជ្ជាតាមថ្នាក់</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $classSubject->subject->name ?? 'ព័ត៌មានលម្អិត' }}</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.search-wrapper {
    position: relative;
    width: 280px;
}
.search-wrapper i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}
.search-wrapper input {
    width: 100%;
    padding: 0.45rem 1rem 0.45rem 2.4rem;
    font-size: 0.85rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    transition: all 0.2s;
}
.search-wrapper input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px var(--gold-subtle);
    outline: none;
}
</style>
@endpush

@section('content')
{{-- ═══════════════════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h1 class="h3 fw-bold mb-0 text-dark">មុខវិជ្ជា៖ {{ $classSubject->subject->name ?? '—' }}</h1>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 fs-6 font-monospace">
                {{ $classSubject->subject->code ?? '—' }}
            </span>
            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                <i class="bi bi-diagram-3 me-1"></i>{{ $classSubject->class->name ?? '—' }}
            </span>
            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1">
                <i class="bi bi-calendar3 me-1"></i>{{ $classSubject->class->academic_year->name ?? '—' }}
            </span>
        </div>
        <p class="text-muted small mb-0 mt-1">
            ថ្នាក់៖ {{ $classSubject->class->name ?? '—' }} | 
            កម្រិត៖ {{ $classSubject->class->grade->name ?? '—' }} (បន្ទប់ {{ $classSubject->class->section->name ?? '—' }}) | 
            ឆ្នាំសិក្សា៖ {{ $classSubject->class->academic_year->name ?? '—' }} | 
            ប្រភេទ៖ {{ $classSubject->subject->type ?? 'ទូទៅ' }}
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('academics.index', ['tab' => 'class-subjects']) }}" class="btn btn-outline-secondary btn-sm shadow-xs">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
        </a>
        <a href="{{ route('teacher-assignments.create', ['class_subject_id' => $classSubject->id]) }}" class="btn btn-primary-action btn-sm shadow-xs">
            <i class="bi bi-person-plus-fill me-1"></i>ចាត់តាំងគ្រូបង្រៀន
        </a>
        <a href="{{ route('class-subjects.edit', $classSubject) }}" class="btn btn-outline-warning text-dark btn-sm shadow-xs">
            <i class="bi bi-pencil me-1"></i>កែប្រែ
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     METRIC STAT CARDS
═══════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    {{-- Card 1: Assigned Teachers --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">គ្រូបង្រៀនទទួលបន្ទុក</div>
                    <h3 class="fw-bold text-primary mb-0 mt-1">{{ $classSubject->teacher_assignments->count() }}</h3>
                </div>
                <div class="rounded-3 bg-primary-subtle text-primary p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-person-video3 fs-5"></i>
                </div>
            </div>
            <div class="mt-2 text-muted small" style="font-size: 0.72rem;">
                គ្រូបង្រៀនមុខវិជ្ជានេះក្នុងថ្នាក់
            </div>
        </div>
    </div>

    {{-- Card 2: Enrolled Students in this Class --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">សិស្សក្នុងថ្នាក់</div>
                    <h3 class="fw-bold text-success mb-0 mt-1">{{ $classSubject->class->students->count() }}</h3>
                </div>
                <div class="rounded-3 bg-success-subtle text-success p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
            </div>
            <div class="mt-2 text-muted small" style="font-size: 0.72rem;">
                សិស្សកំពុងរៀនក្នុងថ្នាក់ {{ $classSubject->class->name ?? '' }}
            </div>
        </div>
    </div>

    {{-- Card 3: Timetable Slots --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">កាលវិភាគបង្រៀន</div>
                    <h3 class="fw-bold text-warning-emphasis mb-0 mt-1">{{ $timetableEntries->count() }}</h3>
                </div>
                <div class="rounded-3 bg-warning-subtle text-warning-emphasis p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
            <div class="mt-2 text-muted small" style="font-size: 0.72rem;">
                ម៉ោងបង្រៀនប្រចាំសប្តាហ៍
            </div>
        </div>
    </div>

    {{-- Card 4: Subject Type & Details --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 p-3 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-semibold">ប្រភេទមុខវិជ្ជា</div>
                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ $classSubject->subject->type ?? 'ទូទៅ' }}</h4>
                </div>
                <div class="rounded-3 bg-info-subtle text-info p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-book-half fs-5"></i>
                </div>
            </div>
            <div class="mt-2 text-muted small" style="font-size: 0.72rem;">
                កូដ៖ {{ $classSubject->subject->code ?? '—' }}
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     MAIN DETAILS CARD WITH TABS
═══════════════════════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-xs rounded-3 overflow-hidden bg-white">
    <div class="card-header bg-white border-bottom px-4 pt-3 pb-0">
        <ul class="nav nav-tabs border-bottom-0" id="classSubjectTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold py-3 px-3 d-flex align-items-center gap-2"
                        id="teachers-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#teachers-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="teachers-tab-pane"
                        aria-selected="true">
                    <i class="bi bi-person-video3 text-primary"></i>
                    <span>គ្រូបង្រៀនទទួលបន្ទុក</span>
                    <span class="badge bg-primary rounded-pill px-2 py-0 ms-1">{{ $classSubject->teacher_assignments->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-3 px-3 d-flex align-items-center gap-2"
                        id="schedule-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#schedule-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="schedule-tab-pane"
                        aria-selected="false">
                    <i class="bi bi-calendar3-week text-warning"></i>
                    <span>កាលវិភាគបង្រៀន</span>
                    <span class="badge bg-secondary-subtle text-dark rounded-pill px-2 py-0 ms-1">{{ $timetableEntries->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-3 px-3 d-flex align-items-center gap-2"
                        id="students-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#students-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="students-tab-pane"
                        aria-selected="false">
                    <i class="bi bi-people-fill text-success"></i>
                    <span>បញ្ជីឈ្មោះសិស្សក្នុងថ្នាក់</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0 ms-1">{{ $classSubject->class->students->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold py-3 px-3 d-flex align-items-center gap-2"
                        id="overview-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#overview-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="overview-tab-pane"
                        aria-selected="false">
                    <i class="bi bi-info-circle text-info"></i>
                    <span>ព័ត៌មានទូទៅ</span>
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="classSubjectTabsContent">
        {{-- ─────────────────────────────────────────────────────────────
             TAB 1: ASSIGNED TEACHERS
        ───────────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade show active p-4" id="teachers-tab-pane" role="tabpanel" aria-labelledby="teachers-tab" tabindex="0">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div class="fw-bold text-dark fs-6">
                    <i class="bi bi-person-badge text-primary me-1"></i>បញ្ជីគ្រូបង្រៀនទទួលបន្ទុកមុខវិជ្ជា
                </div>
                <div>
                    <a href="{{ route('teacher-assignments.create', ['class_subject_id' => $classSubject->id]) }}" class="btn btn-sm btn-primary-action px-3 py-2">
                        <i class="bi bi-person-plus-fill me-1"></i>ចាត់តាំងគ្រូបង្រៀនថ្មី
                    </a>
                </div>
            </div>

            @if($classSubject->teacher_assignments->isNotEmpty())
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="ps-3" style="width: 50px;">#</th>
                                <th>គ្រូបង្រៀន</th>
                                <th>អត្តលេខគ្រូ</th>
                                <th>ផ្នែក / ដេប៉ាតឺម៉ង់</th>
                                <th>មុខតំណែង</th>
                                <th>ឆ្នាំសិក្សា</th>
                                <th class="text-end pe-3">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classSubject->teacher_assignments as $idx => $assignment)
                                @php
                                    $emp = $assignment->employee;
                                    $teacherUser = $emp?->user;
                                    $teacherName = $teacherUser?->name ?? '—';
                                    $teacherPhoto = $emp?->photo_url;
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($teacherPhoto)
                                                <img src="{{ $teacherPhoto }}"
                                                     alt="{{ $teacherName }}"
                                                     class="rounded-circle flex-shrink-0 border object-fit-cover shadow-xs"
                                                     style="width: 36px; height: 36px;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                                <div class="rounded-circle bg-primary-subtle text-primary d-none align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                                    {{ mb_substr($teacherName, 0, 1) }}
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                                    {{ mb_substr($teacherName, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $teacherName }}</div>
                                                <div class="text-muted small">{{ $teacherUser?->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                            {{ $emp?->employee_code ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border px-2 py-1">
                                            {{ $emp?->employee_department?->name ?? 'ទូទៅ' }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $emp?->employee_position?->name ?? 'គ្រូបង្រៀន' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1">
                                            {{ $assignment->academic_year->name ?? $classSubject->class->academic_year->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('teacher-assignments.show', $assignment) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="មើលលម្អិត">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('teacher-assignments.destroy', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('តើអ្នកពិតជាចង់ដកគ្រូបង្រៀនរូបនេះចេញពីមុខវិជ្ជាមែនទេ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="ដកគ្រូ">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-5 text-center bg-light rounded-3 border">
                    <div class="mb-3">
                        <i class="bi bi-person-x text-muted opacity-50 fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">មិនទាន់មានគ្រូបង្រៀនទទួលបន្ទុកនៅឡើយទេ</h5>
                    <p class="text-muted small mb-3">អ្នកអាចចាត់តាំងគ្រូបង្រៀនឱ្យទទួលបន្ទុកមុខវិជ្ជា {{ $classSubject->subject->name ?? '' }} ក្នុងថ្នាក់នេះបាន។</p>
                    <a href="{{ route('teacher-assignments.create', ['class_subject_id' => $classSubject->id]) }}" class="btn btn-primary-action">
                        <i class="bi bi-person-plus-fill me-1"></i>ចាត់តាំងគ្រូបង្រៀន
                    </a>
                </div>
            @endif
        </div>

        {{-- ─────────────────────────────────────────────────────────────
             TAB 2: TIMETABLE SCHEDULE
        ───────────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade p-4" id="schedule-tab-pane" role="tabpanel" aria-labelledby="schedule-tab" tabindex="0">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div class="fw-bold text-dark fs-6">
                    <i class="bi bi-calendar3-week text-warning me-1"></i>កាលវិភាគម៉ោងបង្រៀនប្រចាំសប្តាហ៍
                </div>
                <div>
                    @if($classSubject->class->timetables->isNotEmpty())
                        <a href="{{ route('timetables.show', $classSubject->class->timetables->first()) }}" class="btn btn-sm btn-outline-primary px-3 py-2">
                            <i class="bi bi-eye me-1"></i>មើលកាលវិភាគពេញលេញនៃថ្នាក់
                        </a>
                    @endif
                </div>
            </div>

            @if($timetableEntries->isNotEmpty())
                @php
                    $khmerDays = [
                        1 => 'ថ្ងៃចន្ទ',
                        2 => 'ថ្ងៃអង្គារ',
                        3 => 'ថ្ងៃពុធ',
                        4 => 'ថ្ងៃព្រហស្បតិ៍',
                        5 => 'ថ្ងៃសុក្រ',
                        6 => 'ថ្ងៃសៅរ៍',
                        7 => 'ថ្ងៃអាទិត្យ',
                    ];
                @endphp
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="ps-3" style="width: 50px;">#</th>
                                <th>ថ្ងៃសិក្សា</th>
                                <th>ម៉ោងសិក្សា</th>
                                <th>បន្ទប់រៀន</th>
                                <th>គ្រូបង្រៀន</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timetableEntries as $idx => $entry)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                            <i class="bi bi-calendar-day me-1"></i>{{ $khmerDays[$entry->day_of_week] ?? ('ថ្ងៃទី ' . $entry->day_of_week) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1 font-monospace fw-semibold text-dark">
                                            <i class="bi bi-clock me-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($entry->time_slot->start_time ?? '')->format('H:i') }}
                                            —
                                            {{ \Carbon\Carbon::parse($entry->time_slot->end_time ?? '')->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-door-open me-1 text-secondary"></i>{{ $entry->classroom->name ?? 'មិនទាន់កំណត់' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">
                                            {{ $entry->teacher?->user?->name ?? '—' }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-5 text-center bg-light rounded-3 border">
                    <div class="mb-3">
                        <i class="bi bi-calendar-x text-muted opacity-50 fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">មិនទាន់មានកាលវិភាគបង្រៀនសម្រាប់មុខវិជ្ជានេះទេ</h5>
                    <p class="text-muted small mb-0">មុខវិជ្ជានេះមិនទាន់ត្រូវបានបញ្ចូលក្នុងកាលវិភាគប្រចាំសប្តាហ៍នៃថ្នាក់ {{ $classSubject->class->name ?? '' }} នៅឡើយទេ។</p>
                </div>
            @endif
        </div>

        {{-- ─────────────────────────────────────────────────────────────
             TAB 3: CLASS STUDENT ROSTER
        ───────────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade p-4" id="students-tab-pane" role="tabpanel" aria-labelledby="students-tab" tabindex="0">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div class="search-wrapper" style="max-width: 300px;">
                    <i class="bi bi-search"></i>
                    <input type="text" id="classStudentSearchInput" placeholder="ស្វែងរកតាមឈ្មោះ ឬអត្តលេខ..." onkeyup="filterClassStudentTable()">
                </div>
                <div class="text-muted small">
                    សិស្សសរុបក្នុងថ្នាក់៖ <strong>{{ $classSubject->class->students->count() }} នាក់</strong>
                </div>
            </div>

            @if($classSubject->class->students->isNotEmpty())
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0" id="classStudentsTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="ps-3" style="width: 50px;">#</th>
                                <th>អត្តលេខសិស្ស</th>
                                <th>ឈ្មោះសិស្ស</th>
                                <th>ភេទ</th>
                                <th>លេខរៀង</th>
                                <th>លេខទូរស័ព្ទ</th>
                                <th>ស្ថានភាព</th>
                                <th class="text-end pe-3">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classSubject->class->students as $idx => $student)
                                @php
                                    $profile = $student->profile;
                                    $khmerName = ($profile?->first_name_khmer || $profile?->last_name_khmer) 
                                        ? trim(($profile->last_name_khmer ?? '') . ' ' . ($profile->first_name_khmer ?? ''))
                                        : null;
                                    $latinName = $student->user->name ?? trim(($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? ''));
                                    $gender = strtolower($profile?->gender ?? '');
                                    $genderLabel = $gender === 'female' || $gender === 'f' ? 'ស្រី' : ($gender === 'male' || $gender === 'm' ? 'ប្រុស' : '—');
                                    $genderBadge = $gender === 'female' || $gender === 'f' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle';
                                    $studentPhoto = $student->photo_url;
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                            {{ $student->admission_number ?? ('#' . $student->id) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($studentPhoto)
                                                <img src="{{ $studentPhoto }}"
                                                     alt="{{ $khmerName ?: $latinName }}"
                                                     class="rounded-circle flex-shrink-0 border object-fit-cover shadow-xs"
                                                     style="width: 34px; height: 34px;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                                <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-none align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                                    {{ mb_substr($khmerName ?: $latinName, 0, 1) }}
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                                    {{ mb_substr($khmerName ?: $latinName, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $khmerName ?: $latinName }}</div>
                                                @if($khmerName && $latinName && $khmerName !== $latinName)
                                                    <div class="text-muted small">{{ $latinName }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $genderBadge }} px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $genderLabel }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $student->roll_number ?? '—' }}</td>
                                    <td class="text-muted">{{ $profile?->phone ?? $student->user?->phone ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $student->studentStatus?->name ?? 'កំពុងសិក្សា' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary py-1 px-3" style="font-size: 0.78rem;">
                                            <i class="bi bi-eye me-1"></i>មើលប្រវត្តិ
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-5 text-center bg-light rounded-3 border">
                    <div class="mb-3">
                        <i class="bi bi-people text-muted opacity-50 fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">មិនទាន់មានសិស្សក្នុងថ្នាក់នេះនៅឡើយទេ</h5>
                    <p class="text-muted small mb-0">ថ្នាក់នេះមិនទាន់មានសិស្សចុះឈ្មោះចូលរៀននៅឡើយទេ។</p>
                </div>
            @endif
        </div>

        {{-- ─────────────────────────────────────────────────────────────
             TAB 4: OVERVIEW
        ───────────────────────────────────────────────────────────── --}}
        <div class="tab-pane fade p-4" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
            <div class="row g-4">
                {{-- Subject Information --}}
                <div class="col-md-6">
                    <div class="card border rounded-3 h-100">
                        <div class="card-header bg-light border-bottom py-3">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-book me-2 text-primary"></i>ព័ត៌មានមុខវិជ្ជា
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="text-muted ps-3 py-3" style="width: 40%;">ឈ្មោះមុខវិជ្ជា៖</td>
                                        <td class="fw-bold py-3 text-dark">{{ $classSubject->subject->name ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted ps-3 py-3">កូដមុខវិជ្ជា៖</td>
                                        <td class="py-3">
                                            <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                                {{ $classSubject->subject->code ?? '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted ps-3 py-3">ប្រភេទមុខវិជ្ជា៖</td>
                                        <td class="py-3">
                                            <span class="badge bg-secondary-subtle text-dark border px-2 py-1">
                                                {{ $classSubject->subject->type ?? 'ទូទៅ' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-3 py-3">កាលបរិច្ឆេទបង្កើត៖</td>
                                        <td class="py-3 text-muted">
                                            {{ $classSubject->created_at ? $classSubject->created_at->format('d/m/Y H:i') : '—' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Class Information --}}
                <div class="col-md-6">
                    <div class="card border rounded-3 h-100">
                        <div class="card-header bg-light border-bottom py-3">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-diagram-3 me-2 text-success"></i>ព័ត៌មានថ្នាក់រៀន
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr class="border-bottom">
                                        <td class="text-muted ps-3 py-3" style="width: 40%;">ឈ្មោះថ្នាក់៖</td>
                                        <td class="fw-bold py-3 text-dark">{{ $classSubject->class->name ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted ps-3 py-3">កម្រិតថ្នាក់៖</td>
                                        <td class="fw-bold py-3 text-dark">{{ $classSubject->class->grade->name ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted ps-3 py-3">បន្ទប់ / ក្រុម៖</td>
                                        <td class="py-3 text-dark">{{ $classSubject->class->section->name ?? '—' }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <td class="text-muted ps-3 py-3">ឆ្នាំសិក្សា៖</td>
                                        <td class="py-3">
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1">
                                                {{ $classSubject->class->academic_year->name ?? '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-3 py-3">ចំណុះសិស្សអតិបរមា៖</td>
                                        <td class="py-3 text-dark">{{ $classSubject->class->capacity ?? '—' }} នាក់</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterClassStudentTable() {
    const input = document.getElementById('classStudentSearchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('classStudentsTable');
    if (!table) return;
    const trs = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < trs.length; i++) {
        const text = trs[i].textContent || trs[i].innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
            trs[i].style.display = '';
        } else {
            trs[i].style.display = 'none';
        }
    }
}
</script>
@endpush
@endsection
