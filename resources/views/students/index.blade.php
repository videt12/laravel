@extends('layouts.app')

@section('title', 'គ្រប់គ្រងសិស្សានុសិស្ស — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">សិស្សានុសិស្ស</li>
    </ol>
</nav>
@endsection

@section('content')

{{-- ═══════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════ --}}
<div class="page-header d-flex align-items-start justify-content-between mb-3">
    <div>
        <h1 class="page-title">គ្រប់គ្រងសិស្សានុសិស្ស</h1>
        <p class="page-subtitle">គ្រប់គ្រងអត្តសញ្ញាណសិស្ស ការចុះឈ្មោះ និងសមតុល្យហិរញ្ញវត្ថុជាក់ស្តែង</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if(!empty($filterClass) && auth()->user()?->canAccess(['students.edit', 'classes.edit']))
            <a href="{{ route('classes.id-cards', $filterClass) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-printer me-1"></i>បោះពុម្ពកាតទាំងថ្នាក់
            </a>
        @endif
        @if(auth()->user()?->canAccess(['reports.view', 'students.export']))
        <a href="{{ route('students.export', request()->query()) }}" class="btn btn-sm btn-outline-success" title="ទាញយកបញ្ជីសិស្សជាឯកសារ Excel/CSV">
            <i class="bi bi-file-earmark-excel me-1"></i>ទាញយក Excel
        </a>
        @endif
        @if(auth()->user()?->canAccess('students.create'))
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importStudentModal">
            <i class="bi bi-file-earmark-arrow-up me-1"></i>នាំចូល CSV
        </button>
        @endif
        @if(auth()->user()?->canAccess(['finance.view', 'finance']))
        <a href="{{ route('finance.student-balances') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-wallet2 me-1"></i>សមតុល្យសិស្ស
        </a>
        @endif
        @if(auth()->user()?->canAccess('students.create'))
        <a href="{{ route('students.create') }}" class="btn btn-primary-action">
            <i class="bi bi-plus-lg me-1"></i>បន្ថែមសិស្ស
        </a>
        @endif
    </div>
</div>

@if(session('import_errors') && count(session('import_errors')) > 0)
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert" style="border-radius: 8px;">
        <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>បញ្ហាមួយចំនួនក្នុងការនាំចូលទិន្នន័យ៖</h6>
        <ul class="mb-0 ps-3 small">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ═══════════════════════════════════════════
     SUMMARY METRICS CARDS
═══════════════════════════════════════════ --}}
@php
    $canSeeFinance = auth()->user()?->canAccess(['finance.view', 'finance']);
    $metricCol = $canSeeFinance ? 'col-6 col-md-3' : 'col-6 col-md-4';
@endphp
<div class="row g-2 mb-2">
    <div class="{{ $metricCol }}">
        <div class="card h-100 border-0 shadow-xs rounded-3 bg-white py-2 px-3">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted fw-medium" style="font-size:0.75rem">សិស្សសរុប</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-1.5 py-0.5" style="font-size:0.70rem"><i class="bi bi-people-fill"></i></span>
            </div>
            <h4 class="fw-bold text-dark my-1" style="font-size:1.35rem;line-height:1.2">{{ number_format($totalStudents) }}</h4>
            <div class="d-flex align-items-center gap-1.5 text-truncate" style="font-size:0.70rem;line-height:1.2">
                <span class="text-primary fw-medium">ពេញម៉ោង: {{ number_format($fullTimeCount ?? 0) }}</span>
                <span class="text-muted">·</span>
                <span class="text-warning-emphasis fw-medium">ក្រៅម៉ោង: {{ number_format($partTimeCount ?? 0) }}</span>
            </div>
        </div>
    </div>
    <div class="{{ $metricCol }}">
        <div class="card h-100 border-0 shadow-xs rounded-3 bg-white py-2 px-3">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted fw-medium" style="font-size:0.75rem">សិស្សថ្មី</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5" style="font-size:0.70rem">ថ្មី</span>
            </div>
            <h4 class="fw-bold text-success my-1" style="font-size:1.35rem;line-height:1.2">{{ number_format($newStudentsCount) }}</h4>
            <span class="text-muted text-truncate d-block" style="font-size:0.70rem;line-height:1.2">ចុះឈ្មោះលើកដំបូង</span>
        </div>
    </div>
    <div class="{{ $metricCol }}">
        <div class="card h-100 border-0 shadow-xs rounded-3 bg-white py-2 px-3">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted fw-medium" style="font-size:0.75rem">សិស្សចាស់ / បន្ត</span>
                <span class="badge bg-info-subtle text-info border border-info-subtle px-1.5 py-0.5" style="font-size:0.70rem">បន្ត</span>
            </div>
            <h4 class="fw-bold text-primary my-1" style="font-size:1.35rem;line-height:1.2">{{ number_format($returningStudentsCount) }}</h4>
            <span class="text-muted text-truncate d-block" style="font-size:0.70rem;line-height:1.2">បានសិក្សាឆ្នាំមុន</span>
        </div>
    </div>
    @if($canSeeFinance)
    <div class="col-6 col-md-3">
        <div class="card h-100 border-0 shadow-xs rounded-3 bg-white py-2 px-3">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted fw-medium" style="font-size:0.75rem">សមតុល្យជំពាក់</span>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1.5 py-0.5" style="font-size:0.70rem"><i class="bi bi-cash-stack"></i></span>
            </div>
            <h4 class="fw-bold text-danger my-1" style="font-size:1.35rem;line-height:1.2">${{ number_format($totalOutstandingBalance, 2) }}</h4>
            <span class="text-muted text-truncate d-block" style="font-size:0.70rem;line-height:1.2">ប្រាក់ត្រូវប្រមូលនៅសល់</span>
        </div>
    </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════
     FILTER TOOLBAR
═══════════════════════════════════════════ --}}
<div class="toolbar-card mb-2">
    <form method="GET" action="{{ route('students.index') }}" id="filterForm">
        <div class="row g-2">
            {{-- Search --}}
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">ស្វែងរក</label>
                <div class="search-input-wrap">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text"
                           name="search"
                           id="searchInput"
                           value="{{ $search }}"
                           class="form-control form-control-sm"
                           placeholder="ឈ្មោះ, អត្តលេខ…"
                           autocomplete="off">
                </div>
            </div>

            {{-- Academic Year --}}
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">ឆ្នាំសិក្សា</label>
                <select name="academic_year_id" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ឆ្នាំទាំងអស់</option>
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }}>
                            {{ $ay->name }}{{ $ay->is_active ? ' (បច្ចុប្បន្ន)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Class --}}
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">ថ្នាក់រៀន</label>
                <select name="class_id" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ថ្នាក់ទាំងអស់</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $filterClass == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Section --}}
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">បន្ទប់/ផ្នែក</label>
                <select name="section_id" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ទាំងអស់</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ $filterSection == $sec->id ? 'selected' : '' }}>
                            {{ $sec->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Student Type: New / Returning --}}
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">ប្រភេទសិស្ស</label>
                <select name="student_type" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ប្រភេទទាំងអស់</option>
                    <option value="new" {{ $filterStudentType === 'new' ? 'selected' : '' }}>សិស្សថ្មី</option>
                    <option value="returning" {{ $filterStudentType === 'returning' ? 'selected' : '' }}>សិស្សចាស់</option>
                </select>
            </div>

            {{-- Payment Status --}}
            @if($canSeeFinance)
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">ស្ថានភាពទូទាត់</label>
                <select name="payment_status" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ការទូទាត់ទាំងអស់</option>
                    <option value="paid" {{ $filterPaymentStatus === 'paid' ? 'selected' : '' }}>បានបង់គ្រប់</option>
                    <option value="partial" {{ $filterPaymentStatus === 'partial' ? 'selected' : '' }}>បង់ខ្លះ</option>
                    <option value="unpaid" {{ $filterPaymentStatus === 'unpaid' ? 'selected' : '' }}>មិនទាន់បង់</option>
                    <option value="overdue" {{ $filterPaymentStatus === 'overdue' ? 'selected' : '' }}>ហួសកំណត់</option>
                </select>
            </div>
            @endif

            {{-- Row 2: Status, Study Mode & Actions all inline --}}
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">ស្ថានភាពសិស្ស</label>
                <select name="status" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ស្ថានភាពសិស្សទាំងអស់</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ $filterStatus == $status->id ? 'selected' : '' }}>
                            {{ $status->khmer_name ?? $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                <label class="form-label text-muted small mb-1 fw-medium">ទម្រង់សិក្សា</label>
                <select name="study_mode" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                    <option value="">ទម្រង់សិក្សាទាំងអស់</option>
                    <option value="full_time" {{ $filterStudyMode === 'full_time' ? 'selected' : '' }}>🎓 ពេញម៉ោង</option>
                    <option value="part_time" {{ $filterStudyMode === 'part_time' ? 'selected' : '' }}>⏰ ក្រៅម៉ោង</option>
                    <option value="dual" {{ $filterStudyMode === 'dual' ? 'selected' : '' }}>🔄 ទាំងពេញម៉ោង និង ក្រៅម៉ោង</option>
                </select>
            </div>

            {{-- Actions and Results Count in the same row --}}
            <div class="col-xl-8 col-lg-8 col-md-12 col-12 d-flex align-items-end justify-content-between flex-wrap gap-2 pt-md-0 pt-1">
                <div class="d-flex align-items-center gap-1.5">
                    <button type="submit" class="btn btn-sm btn-primary px-3 shadow-xs" style="height:33px;">
                        <i class="bi bi-funnel me-1"></i>ស្វែងរក
                    </button>
                    @if($hasFilters)
                        <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-secondary px-2" style="height:33px;line-height:1.7;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>កំណត់ឡើងវិញ
                        </a>
                    @endif
                </div>

                <div class="text-muted small">
                    បង្ហាញលទ្ធផលត្រូវគ្នាសរុប <strong class="text-dark">{{ $students->total() }}</strong> នាក់
                </div>
            </div>
        </div>

        {{-- Active filter chips --}}
        @if($hasFilters)
        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
            <span class="text-muted" style="font-size:.75rem">តម្រងកំពុងប្រើ៖</span>
            @if($search)
                <span class="filter-chip">
                    ស្វែងរក៖ "{{ $search }}"
                    <a href="{{ route('students.index', request()->except('search')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($filterAcademicYear)
                @php $activeYearObj = $academicYears->firstWhere('id', $filterAcademicYear); @endphp
                <span class="filter-chip">
                    ឆ្នាំ៖ {{ $activeYearObj?->name ?? $filterAcademicYear }}
                    <a href="{{ route('students.index', request()->except('academic_year_id')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($filterClass)
                @php $activeClassObj = $classes->firstWhere('id', $filterClass); @endphp
                <span class="filter-chip">
                    ថ្នាក់៖ {{ $activeClassObj?->name ?? $filterClass }}
                    <a href="{{ route('students.index', request()->except('class_id')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($filterSection)
                @php $activeSecObj = $sections->firstWhere('id', $filterSection); @endphp
                <span class="filter-chip">
                    បន្ទប់៖ {{ $activeSecObj?->name ?? $filterSection }}
                    <a href="{{ route('students.index', request()->except('section_id')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($filterStudentType)
                <span class="filter-chip">
                    ប្រភេទ៖ {{ $filterStudentType === 'new' ? 'សិស្សថ្មី' : 'សិស្សចាស់' }}
                    <a href="{{ route('students.index', request()->except('student_type')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($filterPaymentStatus && $canSeeFinance)
                <span class="filter-chip">
                    ការទូទាត់៖ {{ $filterPaymentStatus }}
                    <a href="{{ route('students.index', request()->except('payment_status')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($filterStatus)
                @php $selectedStatus = $statuses->find($filterStatus) @endphp
                <span class="filter-chip">
                    ស្ថានភាព៖ {{ $selectedStatus?->khmer_name ?? $selectedStatus?->name ?? $filterStatus }}
                    <a href="{{ route('students.index', request()->except('status')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
            @if($filterStudyMode)
                <span class="filter-chip">
                    ទម្រង់៖ {{ $filterStudyMode === 'part_time' ? 'ក្រៅម៉ោង (Part-Time)' : 'ពេញម៉ោង (Full-Time)' }}
                    <a href="{{ route('students.index', request()->except('study_mode')) }}" class="ms-1 text-muted"><i class="bi bi-x"></i></a>
                </span>
            @endif
        </div>
        @endif
    </form>
</div>

{{-- ═══════════════════════════════════════════
     STUDENT TABLE CARD
═══════════════════════════════════════════ --}}
<div class="data-card">

    {{-- Card Header --}}
    <div class="data-card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">បញ្ជីឈ្មោះសិស្ស</span>
            <span class="count-badge">{{ $students->total() }}</span>
            @if($hasFilters)
                <span class="text-muted" style="font-size:.75rem">· បានច្រោះ</span>
            @endif
        </div>
        <small class="text-muted">
            @if($students->total() > 0)
                បង្ហាញ {{ $students->firstItem() }}–{{ $students->lastItem() }} នៃ {{ $students->total() }}
            @endif
        </small>
    </div>

    {{-- Table / Empty State --}}
    @if($students->isEmpty())
        <div class="empty-state py-5 text-center">
            <div class="empty-icon fs-1 text-muted mb-3"><i class="bi bi-people"></i></div>
            <p class="empty-title fw-bold text-dark fs-5 mb-1">
                @if($hasFilters) មិនមានសិស្សត្រូវនឹងលក្ខខណ្ឌស្វែងរកឡើយ @else មិនទាន់មានសិស្សចុះឈ្មោះនៅឡើយទេ @endif
            </p>
            <p class="empty-subtitle text-muted small mb-3">
                @if($hasFilters)
                    សូមសាកល្បងផ្លាស់ប្តូរ ឬសម្អាតប៉ារ៉ាម៉ែត្រស្វែងរក។
                @else
                    ចាប់ផ្តើមដោយបន្ថែមសិស្សដំបូងរបស់អ្នកចូលទៅក្នុងប្រព័ន្ធសាលា។
                @endif
            </p>
            @if($hasFilters)
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary btn-sm">សម្អាតការស្វែងរកទាំងអស់</a>
            @elseif(auth()->user()?->canAccess('students.create'))
                <a href="{{ route('students.create') }}" class="btn btn-primary-action btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>បន្ថែមសិស្សដំបូង
                </a>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table align-middle">
                <thead>
                    <tr>
                        <th style="width:36px" class="text-center">#</th>
                        <th>សិស្ស</th>
                        <th>អត្តលេខ</th>
                        <th>ថ្នាក់រៀន / បន្ទប់</th>
                        <th>ឆ្នាំសិក្សា</th>
                        <th>ប្រភេទ</th>
                        @if($canSeeFinance)
                        <th class="text-end">វិក្កយបត្រ</th>
                        <th class="text-end">បានបង់</th>
                        <th class="text-end">សមតុល្យ</th>
                        <th class="text-center">ស្ថានភាពទូទាត់</th>
                        @endif
                        <th>ស្ថានភាព</th>
                        <th style="width:60px" class="text-end">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php
                        // Resolve Khmer and Latin names
                        $firstNameKh = $student->studentProfile?->first_name_khmer ?? '';
                        $lastNameKh  = $student->studentProfile?->last_name_khmer ?? '';
                        $khmerName   = trim($lastNameKh . ' ' . $firstNameKh);

                        $firstNameLat = $student->studentProfile?->first_name ?? '';
                        $lastNameLat  = $student->studentProfile?->last_name ?? '';
                        $latinName    = trim($firstNameLat . ' ' . $lastNameLat) ?: ($student->user?->name ?? '');

                        $displayName  = $khmerName ?: ($latinName ?: '—');
                        $subName      = ($khmerName && $latinName && $khmerName !== $latinName) 
                            ? $latinName 
                            : ($student->user?->email ?? '');

                        $avatarText   = $khmerName ?: ($latinName ?: 'S');
                        $initials     = mb_strtoupper(mb_substr($avatarText, 0, 1));
                        $avatarColors = ['#e8a838','#3b82f6','#10b981','#8b5cf6','#ef4444','#f59e0b'];
                        $color        = $avatarColors[crc32($displayName) % count($avatarColors)];

                        // Class & section display
                        $className = $student->schoolClass?->grade?->name ?? ($student->schoolClass ? 'Class #'.$student->schoolClass->id : '—');
                        $secName = $student->section?->name ?? ($student->schoolClass?->section?->name ?? '');

                        // Financial values
                        $invoiced = $student->calculated_invoiced ?? 0;
                        $paid = $student->calculated_paid ?? 0;
                        $balance = $student->calculated_balance ?? 0;
                        $pStatus = $student->calculated_payment_status ?? 'unpaid';
                        $sType = $student->calculated_student_type ?? 'new';
                    @endphp
                    <tr class="table-row-hover">
                        {{-- Row number --}}
                        <td class="text-muted text-center" style="font-size:.75rem">{{ $students->firstItem() + $i }}</td>

                        {{-- Student Avatar + Name --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $photoUrl = $student->photo_url;
                                @endphp
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}"
                                         alt="{{ $displayName }}"
                                         class="rounded-circle flex-shrink-0 border object-fit-cover shadow-xs"
                                         style="width:32px;height:32px;"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                    <div class="student-avatar flex-shrink-0 d-none" style="background:{{ $color }}1a;color:{{ $color }};width:32px;height:32px;font-size:.78rem">
                                        {{ $initials }}
                                    </div>
                                @else
                                    <div class="student-avatar flex-shrink-0" style="background:{{ $color }}1a;color:{{ $color }};width:32px;height:32px;font-size:.78rem">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div class="lh-sm min-w-0">
                                    <a href="{{ route('students.show', $student) }}"
                                       class="fw-semibold text-dark text-decoration-none student-name-link text-truncate d-block" 
                                       style="max-width:190px;font-size:.83rem"
                                       title="{{ $displayName }} ({{ $student->admission_number }})">
                                        {{ $displayName }}
                                    </a>
                                    @if($subName)
                                        <div class="text-muted text-truncate" style="font-size:.72rem;max-width:190px" title="{{ $subName }}{{ $student->user?->email && $student->user->email !== $subName ? ' • ' . $student->user->email : '' }}">
                                            {{ $subName }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Admission number --}}
                        <td>
                            <code class="adm-badge">{{ $student->admission_number ?? '—' }}</code>
                        </td>

                        {{-- Class & Section --}}
                        <td>
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                <span class="fw-medium text-dark">{{ $className }}</span>
                                @if($secName)
                                    <span class="badge bg-light text-secondary border">{{ $secName }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- Academic Year --}}
                        <td>
                            <span class="text-muted small">
                                {{ $student->academicYear?->name ?? '—' }}
                            </span>
                        </td>

                        {{-- Student Type: New vs Returning & Study Mode --}}
                        <td>
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                @if($sType === 'returning')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">សិស្សចាស់</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">សិស្សថ្មី</span>
                                @endif

                                @if($student->study_mode === 'part_time')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                        <i class="bi bi-clock me-1"></i>ក្រៅម៉ោង
                                    </span>
                                @elseif($student->study_mode === 'dual')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                        <i class="bi bi-arrow-repeat me-1"></i>ពេញ & ក្រៅម៉ោង
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                        <i class="bi bi-mortarboard me-1"></i>ពេញម៉ោង
                                    </span>
                                @endif
                            </div>
                        </td>

                        @if($canSeeFinance)
                        {{-- Total Invoiced --}}
                        <td class="text-end fw-medium text-secondary" style="font-size:.85rem">
                            ${{ number_format($invoiced, 2) }}
                        </td>

                        {{-- Total Paid --}}
                        <td class="text-end fw-medium text-success" style="font-size:.85rem">
                            ${{ number_format($paid, 2) }}
                        </td>

                        {{-- Outstanding Balance --}}
                        <td class="text-end fw-bold {{ $balance > 0 ? 'text-danger' : 'text-secondary' }}" style="font-size:.85rem">
                            ${{ number_format($balance, 2) }}
                        </td>

                        {{-- Payment Status Badge --}}
                        <td class="text-center">
                            @if($pStatus === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">បានបង់គ្រប់</span>
                            @elseif($pStatus === 'partial')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">បង់ខ្លះ</span>
                            @elseif($pStatus === 'overdue')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">ហួសកំណត់</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">មិនទាន់បង់</span>
                            @endif
                        </td>
                        @endif

                        {{-- Student Status --}}
                        <td>
                            @if($student->studentStatus)
                                <span class="status-badge status-active rounded-pill">{{ $student->studentStatus->khmer_name ?? $student->studentStatus->name }}</span>
                            @else
                                <span class="status-badge status-unknown rounded-pill">—</span>
                            @endif
                        </td>

                        {{-- Actions dropdown --}}
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn-action-dots" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false"
                                        title="សកម្មភាព">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:160px;font-size:.875rem">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('students.show', $student) }}">
                                            <i class="bi bi-eye me-2 text-muted"></i>មើលប្រវត្តិរូប
                                        </a>
                                    </li>
                                    @if(auth()->user()?->canAccess('students.edit'))
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('students.edit', $student) }}">
                                            <i class="bi bi-pencil me-2 text-muted"></i>កែសម្រួលព័ត៌មាន
                                        </a>
                                    </li>
                                    @endif
                                    @if($canSeeFinance)
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('student-invoices.index', ['student_id' => $student->id]) }}">
                                            <i class="bi bi-receipt me-2 text-primary"></i>មើលវិក្កយបត្រ
                                        </a>
                                    </li>
                                    @endif
                                    @if(auth()->user()?->canAccess(['finance.create', 'finance']))
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('student-payments.create', ['student_id' => $student->id]) }}">
                                            <i class="bi bi-credit-card me-2 text-success"></i>កត់ត្រាការបង់ប្រាក់
                                        </a>
                                    </li>
                                    @endif
                                    @if(auth()->user()?->canAccess(['students.edit', 'students.create']))
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('students.id-card', $student) }}" target="_blank">
                                            <i class="bi bi-person-badge me-2 text-primary"></i>បោះពុម្ពកាតសិស្ស
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('students.certificate', $student) }}" target="_blank">
                                            <i class="bi bi-award me-2 text-warning"></i>លិខិតបញ្ជាក់ការសិក្សា
                                        </a>
                                    </li>
                                    @endif
                                    @if(auth()->user()?->canAccess('students.delete'))
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button"
                                                class="dropdown-item py-2 text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delModal{{ $student->id }}">
                                            <i class="bi bi-trash me-2"></i>លុប
                                        </button>
                                    </li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Delete confirmation modal --}}
                            @if(auth()->user()?->canAccess('students.delete'))
                            <div class="modal fade" id="delModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body p-4 text-center">
                                            <div class="mb-3">
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10"
                                                      style="width:48px;height:48px">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-1">លុបសិស្ស?</h6>
                                            <p class="text-muted small mb-3">
                                                <strong>{{ $displayName }}</strong> (<code>{{ $student->admission_number }}</code>) នឹងត្រូវបានលុបចេញពីប្រព័ន្ធ។
                                            </p>
                                            @if($invoiced > 0 || $paid > 0)
                                                <div class="alert alert-warning text-start small mb-3 py-1.5 px-2" style="font-size:0.75rem;">
                                                    <i class="bi bi-shield-exclamation me-1"></i>សិស្សនេះមានទិន្នន័យហិរញ្ញវត្ថុ (${{ number_format($invoiced, 2) }} វិក្កយបត្រ / ${{ number_format($paid, 2) }} បង់ប្រាក់)។ ប្រព័ន្ធនឹងមិនអនុញ្ញាតឱ្យលុបឡើយ។
                                                </div>
                                            @endif
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                                                <form action="{{ route('students.destroy', $student) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger px-3">លុប</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($students->hasPages())
        <div class="data-card-footer d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-4 border-top">
            <div class="text-muted" style="font-size:.8rem">
                ទំព័រ {{ $students->currentPage() }} នៃ {{ $students->lastPage() }} (សិស្សសរុប {{ $students->total() }} នាក់)
            </div>
            <div>
                {{ $students->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    @endif
</div>

{{-- ═══════════════════════════════════════════
     BULK STUDENT IMPORT MODAL
═══════════════════════════════════════════ --}}
<div class="modal fade" id="importStudentModal" tabindex="-1" aria-labelledby="importStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="importStudentModalLabel">នាំចូលទិន្នន័យសិស្សច្រើននាក់ (Bulk Student Import)</h5>
                        <div class="text-muted small">ផ្ទុកឡើងឯកសារ CSV ដើម្បីចុះឈ្មោះសិស្សជាក្រុមដោយស្វ័យប្រវត្តិ</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    {{-- Step 1: Download Sample --}}
                    <div class="p-3 mb-4 rounded-3 border" style="background: #f8fafc;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">
                                    <i class="bi bi-1-circle-fill text-primary me-1"></i>ជំហានទី ១៖ ទាញយកឯកសារគំរូ CSV
                                </h6>
                                <p class="text-muted small mb-0">សូមបំពេញទិន្នន័យសិស្សតាមទម្រង់ជួរឈរ (Columns) ដែលបានកំណត់ជាមុន។</p>
                            </div>
                            <a href="{{ route('students.import-sample') }}" class="btn btn-sm btn-outline-primary fw-semibold px-3 shadow-xs">
                                <i class="bi bi-download me-1"></i>ទាញយកគំរូ CSV (Sample)
                            </a>
                        </div>
                    </div>

                    {{-- Step 2: Upload File --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark mb-1">
                            <i class="bi bi-2-circle-fill text-primary me-1"></i>ជំហានទី ២៖ ជ្រើសរើសឯកសារ CSV របស់អ្នក
                        </label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv,text/csv" required>
                        <div class="form-text small mt-1 text-muted">គាំទ្រឯកសារ .csv ទំហំអតិបរមា 10MB (UTF-8 Encoding)</div>
                    </div>

                    {{-- Guide & Column Mapping Note --}}
                    <div class="card border border-light-subtle bg-light rounded-3 p-3">
                        <h6 class="fw-semibold text-secondary small mb-2"><i class="bi bi-info-circle me-1"></i>ព័ត៌មានបន្ថែមស្តីពីជួរឈរ (Column Specifications)៖</h6>
                        <div class="row g-2 text-muted" style="font-size: 0.8rem;">
                            <div class="col-sm-6"><strong>first_name & last_name:</strong> ឈ្មោះឡាតាំង (ទាមទារ)</div>
                            <div class="col-sm-6"><strong>first_name_khmer & last_name_khmer:</strong> ឈ្មោះខ្មែរ (ជម្រើស)</div>
                            <div class="col-sm-6"><strong>gender:</strong> Male / Female / Other (ឬ ប្រុស / ស្រី)</div>
                            <div class="col-sm-6"><strong>dob:</strong> ឆ្នាំ-ខែ-ថ្ងៃ (ឧ. 2010-05-20)</div>
                            <div class="col-sm-6"><strong>class:</strong> ឈ្មោះថ្នាក់ (ឧ. Grade 10) ឬ Class ID</div>
                            <div class="col-sm-6"><strong>section:</strong> ផ្នែក/បន្ទប់ (ឧ. A, B)</div>
                            <div class="col-sm-6"><strong>admission_number:</strong> បើទុកទំនេរ ប្រព័ន្ធនឹងបង្កើត BPS##### ជូនស្វ័យប្រវត្តិ</div>
                            <div class="col-sm-6"><strong>default password:</strong> គណនីចូលរៀនគឺ <code class="text-primary">student123</code></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-semibold shadow-xs">
                        <i class="bi bi-upload me-1"></i>ចាប់ផ្តើមនាំចូលទិន្នន័យ (Import Now)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Auto-submit search on Enter, debounce on input
    let searchTimer;
    document.getElementById('searchInput')?.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 600);
    });
</script>
@endsection

@push('styles')
<style>
    /* Uniform Filter Toolbar sizing - Proportional & Readable */
    .toolbar-card {
        padding: 0.65rem 0.85rem !important;
    }
    .toolbar-card .form-label {
        font-size: 0.875rem !important;
        margin-bottom: 0.2rem !important;
        font-weight: 600;
        color: #475569;
    }
    .toolbar-card .search-input-wrap {
        position: relative !important;
        width: 100% !important;
        display: flex !important;
        align-items: center !important;
    }
    .toolbar-card .search-input-wrap i.bi-search {
        position: absolute !important;
        left: 0.75rem !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #64748b !important;
        font-size: 0.85rem !important;
        pointer-events: none !important;
        z-index: 3 !important;
        line-height: 1 !important;
        margin: 0 !important;
    }
    .toolbar-card .search-input-wrap .form-control {
        height: 38px !important;
        min-height: 38px !important;
        padding-left: 2.15rem !important;
        padding-right: 0.65rem !important;
        font-size: 0.92rem !important;
        border: 1px solid var(--border-input, #cbd5e1) !important;
        border-radius: 6px !important;
        background-color: #ffffff !important;
    }
    .toolbar-card .search-input-wrap .form-control:focus {
        border-color: var(--gold, #f59e0b) !important;
        box-shadow: 0 0 0 2.5px rgba(245, 158, 11, 0.18) !important;
        outline: none !important;
    }
    .toolbar-card .form-control-sm,
    .toolbar-card .form-select-sm,
    .toolbar-card .input-group-text,
    .toolbar-card .input-group .form-control {
        height: 38px !important;
        min-height: 38px !important;
        font-size: 0.92rem !important;
        padding: 0.35rem 0.65rem !important;
    }
    .toolbar-card .input-group {
        height: 38px !important;
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: stretch !important;
    }
    .toolbar-card .input-group > :first-child {
        border-top-left-radius: 6px !important;
        border-bottom-left-radius: 6px !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    .toolbar-card .input-group > :last-child {
        border-top-right-radius: 6px !important;
        border-bottom-right-radius: 6px !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .toolbar-card .input-group > .border-start-0,
    .toolbar-card .input-group > input.border-start-0 {
        border-left: none !important;
    }
    .toolbar-card .input-group > .border-end-0,
    .toolbar-card .input-group-text.border-end-0 {
        border-right: none !important;
    }
    .toolbar-card .btn-sm {
        height: 38px !important;
        font-size: 0.9rem !important;
        padding: 0.35rem 0.85rem !important;
        display: inline-flex;
        align-items: center;
    }

    /* Uniform Table & Badges sizing - Compact */
    .data-card-header {
        padding: 0.5rem 0.85rem !important;
    }
    .data-table thead th {
        font-size: 0.82rem !important;
        padding: 0.45rem 0.65rem !important;
        white-space: nowrap;
    }
    .data-table tbody td {
        font-size: 0.82rem !important;
        padding: 0.35rem 0.65rem !important;
        vertical-align: middle;
    }
    .data-table .badge,
    .data-table .status-badge {
        font-size: 0.75rem !important;
        padding: 0.15rem 0.45rem !important;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        line-height: 1.25;
    }
    .data-table .adm-badge {
        font-size: 0.75rem !important;
        padding: 0.15rem 0.45rem !important;
    }
</style>
@endpush
