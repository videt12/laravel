@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតថ្នាក់ ' . ($class->name ?? '') . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Academics</li>
        <li class="breadcrumb-item"><a href="{{ route('academics.index', ['tab' => 'classes']) }}" class="text-decoration-none text-muted">Classes</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $class->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h1 class="h4 fw-bold mb-0 text-dark">ថ្នាក់៖ {{ $class->name }}</h1>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.78rem;">
                {{ $class->section->name ?? 'Default' }}
            </span>
            <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1" style="font-size: 0.78rem;">
                <i class="bi bi-calendar3 me-1"></i>{{ $class->academic_year->name ?? '—' }}
            </span>
            @if($class->classroom)
                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-2 py-1" style="font-size: 0.78rem;">
                    <i class="bi bi-door-closed-fill me-1 text-success"></i>បន្ទប់៖ {{ $class->classroom->name }}{{ $class->classroom->building ? ' (អគារ ' . $class->classroom->building . ')' : '' }}
                </span>
            @endif
        </div>
        <p class="text-muted small mb-0 mt-1" style="font-size: 0.78rem;">
            កម្រិត៖ {{ $class->grade->name ?? '—' }} | ឆ្នាំសិក្សា៖ {{ $class->academic_year->name ?? '—' }}
            @if($class->classroom)
                | បន្ទប់រៀនជាក់ស្តែង៖ <strong>{{ $class->classroom->name }}</strong>{{ $class->classroom->building ? ' (អគារ ' . $class->classroom->building . ')' : '' }}
            @endif
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('academics.index', ['tab' => 'classes']) }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 shadow-xs" style="font-size: 0.78rem;">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
        </a>
        @if(auth()->user()?->canAccess('students.create'))
        <a href="{{ route('students.create', ['school_class_id' => $class->id]) }}" class="btn btn-primary-action btn-sm py-1 px-2.5 shadow-xs" style="font-size: 0.78rem;">
            <i class="bi bi-person-plus-fill me-1"></i>+ ចុះឈ្មោះសិស្សថ្មី
        </a>
        @endif
        @if(auth()->user()?->canAccess('classes.edit'))
        <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-warning text-dark btn-sm py-1 px-2.5 shadow-xs" style="font-size: 0.78rem;">
            <i class="bi bi-pencil me-1"></i>កែប្រែថ្នាក់
        </a>
        @endif
    </div>
</div>

{{-- Metric Stat Cards --}}
<div class="row g-2 mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 bg-white stat-metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold stat-title">សិស្សសរុប</div>
                    <div class="fw-bold text-primary stat-value">{{ $class->students->count() }}</div>
                </div>
                <div class="stat-icon-wrap rounded-3 bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="mt-1 text-muted stat-sub">
                កៅអីសរុប៖ {{ $class->capacity ?? 0 }} នាក់
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 bg-white stat-metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold stat-title">កៅអីទំនេរ</div>
                    @php $remaining = max(0, ($class->capacity ?? 0) - $class->students->count()); @endphp
                    <div class="fw-bold text-success stat-value">{{ $remaining }}</div>
                </div>
                <div class="stat-icon-wrap rounded-3 bg-success-subtle text-success">
                    <i class="bi bi-person-workspace"></i>
                </div>
            </div>
            <div class="mt-1 text-muted stat-sub">
                ចំណុះអតិបរមា៖ {{ $class->capacity ?? 0 }}
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 bg-white stat-metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold stat-title">មុខវិជ្ជា</div>
                    <div class="fw-bold text-dark stat-value">{{ $class->class_subjects->count() }}</div>
                </div>
                <div class="stat-icon-wrap rounded-3 bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-book-fill"></i>
                </div>
            </div>
            <div class="mt-1 text-muted stat-sub">
                មុខវិជ្ជាកំពុងរៀនក្នុងថ្នាក់
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-xs rounded-3 bg-white stat-metric-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted fw-semibold stat-title">កាលវិភាគ</div>
                    <div class="fw-bold text-dark stat-value">{{ $class->timetables->count() }}</div>
                </div>
                <div class="stat-icon-wrap rounded-3 bg-info-subtle text-info">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="mt-1 text-muted stat-sub">
                ម៉ោងបង្រៀនបានកំណត់
            </div>
        </div>
    </div>
</div>

{{-- Main Details Card with Tabs --}}
<div class="card border-0 shadow-xs rounded-3 overflow-hidden bg-white">
    <div class="card-header bg-white border-bottom px-3 pt-2 pb-0">
        <ul class="nav nav-tabs border-bottom-0 class-nav-tabs" id="classTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold d-flex align-items-center gap-1.5"
                        id="students-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#students-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="students-tab-pane"
                        aria-selected="true">
                    <i class="bi bi-people-fill text-primary"></i>
                    <span>បញ្ជីឈ្មោះសិស្ស</span>
                    <span class="badge bg-primary rounded-pill px-2 py-0 ms-1" style="font-size: 0.7rem;">{{ $class->students->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold d-flex align-items-center gap-1.5"
                        id="subjects-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#subjects-tab-pane"
                        type="button"
                        role="tab"
                        aria-controls="subjects-tab-pane"
                        aria-selected="false">
                    <i class="bi bi-book text-warning"></i>
                    <span>មុខវិជ្ជា</span>
                    <span class="badge bg-secondary-subtle text-dark rounded-pill px-2 py-0 ms-1" style="font-size: 0.7rem;">{{ $class->class_subjects->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold d-flex align-items-center gap-1.5"
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

    <div class="tab-content" id="classTabsContent">
        {{-- TAB 1: STUDENT ROSTER --}}
        <div class="tab-pane fade show active p-3" id="students-tab-pane" role="tabpanel" aria-labelledby="students-tab" tabindex="0">
            <div class="d-flex align-items-center justify-content-between mb-2.5 flex-wrap gap-2">
                <div class="search-wrapper-compact">
                    <i class="bi bi-search"></i>
                    <input type="text" id="studentSearchInput" placeholder="ស្វែងរកតាមឈ្មោះ ឬអត្តលេខ..." onkeyup="filterStudentTable()">
                </div>
                @if(auth()->user()?->canAccess('students.create'))
                <div>
                    <a href="{{ route('students.create', ['school_class_id' => $class->id]) }}" class="btn btn-sm btn-primary-action py-1 px-2.5" style="font-size: 0.78rem;">
                        <i class="bi bi-person-plus-fill me-1"></i>+ ចុះឈ្មោះសិស្សថ្មី
                    </a>
                </div>
                @endif
            </div>

            @if($class->students->isNotEmpty())
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover table-sm align-middle mb-0 class-table-compact" id="studentsTable">
                        <thead class="table-light text-muted" style="font-size: 0.78rem;">
                            <tr>
                                <th class="ps-3" style="width: 45px;">#</th>
                                <th>អត្តលេខ</th>
                                <th>ឈ្មោះសិស្ស</th>
                                <th>ភេទ</th>
                                <th>លេខរៀង</th>
                                <th>លេខទូរស័ព្ទ</th>
                                <th>ស្ថានភាព</th>
                                <th class="text-end pe-3">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $idx => $student)
                                @php
                                    $profile = $student->profile;
                                    $khmerName = ($profile?->first_name_khmer || $profile?->last_name_khmer) 
                                        ? trim(($profile->last_name_khmer ?? '') . ' ' . ($profile->first_name_khmer ?? ''))
                                        : null;
                                    $latinName = $student->user->name ?? trim(($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? ''));
                                    $gender = strtolower($profile?->gender ?? '');
                                    $genderLabel = $gender === 'female' || $gender === 'f' ? 'ស្រី' : ($gender === 'male' || $gender === 'm' ? 'ប្រុស' : '—');
                                    $genderBadge = $gender === 'female' || $gender === 'f' ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle';
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace px-1.5 py-0.5" style="font-size: 0.75rem;">
                                            {{ $student->admission_number ?? ('#' . $student->id) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @php
                                                $studentPhoto = $student->photo_url;
                                            @endphp
                                            @if($studentPhoto)
                                                <img src="{{ $studentPhoto }}"
                                                     alt="{{ $khmerName ?: $latinName }}"
                                                     class="rounded-circle flex-shrink-0 border object-fit-cover shadow-xs"
                                                     style="width: 30px; height: 30px;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                                <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-none align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 30px; height: 30px; font-size: 0.78rem;">
                                                    {{ mb_substr($khmerName ?: $latinName, 0, 1) }}
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 30px; height: 30px; font-size: 0.78rem;">
                                                    {{ mb_substr($khmerName ?: $latinName, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.8125rem;">{{ $khmerName ?: $latinName }}</div>
                                                @if($khmerName && $latinName && $khmerName !== $latinName)
                                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $latinName }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $genderBadge }} px-2 py-0.5" style="font-size: 0.72rem;">
                                            {{ $genderLabel }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $student->roll_number ?? '—' }}</td>
                                    <td class="text-muted">{{ $profile?->phone ?? $student->user?->phone ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                            {{ $student->studentStatus?->name ?? 'Active' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary py-0.5 px-2.5" style="font-size: 0.75rem;">
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
                    <p class="text-muted small mb-3">អ្នកអាចចុះឈ្មោះសិស្សថ្មី ឬផ្ទេរសិស្សចូលរៀនក្នុងថ្នាក់ {{ $class->name }} នេះបាន។</p>
                    @if(auth()->user()?->canAccess('students.create'))
                    <a href="{{ route('students.create', ['school_class_id' => $class->id]) }}" class="btn btn-primary-action">
                        <i class="bi bi-person-plus-fill me-1"></i>+ ចុះឈ្មោះសិស្សចូលរៀន
                    </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- TAB 2: SUBJECTS --}}
        <div class="tab-pane fade p-3" id="subjects-tab-pane" role="tabpanel" aria-labelledby="subjects-tab" tabindex="0">
            <div class="d-flex align-items-center justify-content-between mb-2.5 flex-wrap gap-2">
                <div class="fw-bold text-dark fs-6" style="font-size: 0.88rem !important;">
                    <i class="bi bi-book text-warning me-1"></i>មុខវិជ្ជាសិក្សាទាំងអស់ក្នុងថ្នាក់ {{ $class->name }}
                </div>
                @if(auth()->user()?->canAccess(['subjects.create', 'subjects.edit', 'classes.edit']))
                <div>
                    <a href="{{ route('academics.index', ['tab' => 'class-subjects', 'class_id' => $class->id]) }}" class="btn btn-sm btn-primary-action py-1 px-2.5" style="font-size: 0.78rem;">
                        <i class="bi bi-plus-circle me-1"></i>+ ដាក់មុខវិជ្ជាសម្រាប់ថ្នាក់
                    </a>
                </div>
                @endif
            </div>

            @if($class->class_subjects->isNotEmpty())
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover table-sm align-middle mb-0 class-table-compact">
                        <thead class="table-light text-muted" style="font-size: 0.78rem;">
                            <tr>
                                <th class="ps-3" style="width: 45px;">#</th>
                                <th>កូដមុខវិជ្ជា</th>
                                <th>ឈ្មោះមុខវិជ្ជា</th>
                                <th>ប្រភេទ</th>
                                <th>ក្រេឌីត / ម៉ោង</th>
                                <th class="text-end pe-3">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->class_subjects as $i => $cs)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace px-1.5 py-0.5" style="font-size: 0.75rem;">
                                            {{ $cs->subject->code ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">{{ $cs->subject->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-dark border px-2 py-0.5" style="font-size: 0.72rem;">
                                            {{ $cs->subject->type ?? 'ទូទៅ' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $cs->subject->credit_hours ?? '—' }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('class-subjects.show', $cs) }}" class="btn btn-sm btn-outline-primary py-0.5 px-2.5" style="font-size: 0.75rem;">
                                            <i class="bi bi-eye me-1"></i>មើលលម្អិត
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-4 text-center bg-light rounded-3 border">
                    <i class="bi bi-book text-muted opacity-50 fs-2 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">មិនទាន់មានមុខវិជ្ជាត្រូវបានកំណត់សម្រាប់ថ្នាក់នេះនៅឡើយទេ</h6>
                    <p class="text-muted small mb-2.5">អ្នកអាចជ្រើសរើស និងចាត់តាំងមុខវិជ្ជាសិក្សាសម្រាប់ថ្នាក់នេះបាន។</p>
                    @if(auth()->user()?->canAccess(['subjects.create', 'subjects.edit', 'classes.edit']))
                    <a href="{{ route('academics.index', ['tab' => 'class-subjects', 'class_id' => $class->id]) }}" class="btn btn-sm btn-primary-action py-1 px-3">
                        <i class="bi bi-plus-circle me-1"></i>+ ដាក់មុខវិជ្ជាសម្រាប់ថ្នាក់
                    </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- TAB 3: OVERVIEW --}}
        <div class="tab-pane fade p-3" id="overview-tab-pane" role="tabpanel" aria-labelledby="overview-tab" tabindex="0">
            <div class="row g-3">
                <div class="col-md-6">
                    <table class="table table-sm table-bordered mb-0 class-table-compact">
                        <tbody>
                            <tr>
                                <th class="bg-light w-40 text-muted">កម្រិតថ្នាក់</th>
                                <td class="fw-bold">{{ $class->grade->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">បន្ទប់ / ផ្នែក</th>
                                <td class="fw-bold">{{ $class->section->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">ឆ្នាំសិក្សា</th>
                                <td>{{ $class->academic_year->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">ចំណុះកៅអី</th>
                                <td>{{ $class->capacity }} នាក់អតិបរមា</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-bordered mb-0 class-table-compact">
                        <tbody>
                            <tr>
                                <th class="bg-light w-40 text-muted">ចំនួនសិស្សចុះឈ្មោះ</th>
                                <td class="fw-bold text-primary">{{ $class->students->count() }} នាក់</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">កៅអីនៅសល់</th>
                                <td class="fw-bold text-success">{{ max(0, ($class->capacity ?? 0) - $class->students->count()) }} កៅអី</td>
                            </tr>
                            <tr>
                                <th class="bg-light text-muted">កាលបរិច្ឆេទបង្កើត</th>
                                <td>{{ $class->created_at ? $class->created_at->format('d/m/Y H:i') : '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
#classTabsContent > .tab-pane {
    display: none !important;
}
#classTabsContent > .tab-pane.active {
    display: block !important;
}
.stat-metric-card {
    padding: 0.75rem 1rem !important;
}
.stat-title {
    font-size: 0.78rem;
}
.stat-value {
    font-size: 1.35rem;
    line-height: 1.2;
    margin-top: 0.2rem;
}
.stat-icon-wrap {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    flex-shrink: 0;
}
.stat-sub {
    font-size: 0.7rem;
}
.class-nav-tabs .nav-link {
    padding: 0.5rem 0.95rem !important;
    font-size: 0.825rem !important;
}
.class-table-compact th, .class-table-compact td {
    padding: 0.45rem 0.65rem !important;
    font-size: 0.8125rem !important;
}
.search-wrapper-compact {
    position: relative;
    max-width: 260px;
    width: 100%;
}
.search-wrapper-compact i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.8rem;
    pointer-events: none;
}
.search-wrapper-compact input {
    width: 100%;
    padding: 0.32rem 0.75rem 0.32rem 2rem;
    font-size: 0.8rem;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.search-wrapper-compact input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 2.5px rgba(245, 158, 11, 0.15);
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('#classTabs button[data-bs-toggle="tab"]');
        tabButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                tabButtons.forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                document.querySelectorAll('#classTabsContent > .tab-pane').forEach(p => {
                    p.classList.remove('show', 'active');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                const targetSelector = this.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetSelector);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    });

    function filterStudentTable() {
        const input = document.getElementById('studentSearchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('studentsTable');
        if (!table) return;
        const trs = table.getElementsByTagName('tr');

        for (let i = 1; i < trs.length; i++) {
            const td = trs[i];
            if (td) {
                const text = td.textContent || td.innerText;
                trs[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
            }
        }
    }
</script>
@endpush
@endsection
