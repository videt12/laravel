{{-- Filter bar --}}
<div class="filter-bar border-0 border-bottom rounded-0 py-2 px-3 bg-white" style="border-radius:0!important">
    <form action="{{ route('academics.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap w-100 m-0">
        <input type="hidden" name="tab" value="classes">

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមកម្រិតថ្នាក់ ឬបន្ទប់...">
        </div>

        {{-- Academic Year Drop List --}}
        <select name="academic_year_id" class="form-select filter-select" style="min-width:180px; width:auto;" onchange="this.form.submit()">
            @foreach($academicYears as $ay)
                <option value="{{ $ay->id }}" {{ (string)($selectedAcademicYearId ?? '') == (string)$ay->id ? 'selected' : '' }}>
                    {{ $ay->name }}{{ $ay->is_active ? ' (បច្ចុប្បន្ន)' : '' }}
                </option>
            @endforeach
            <option value="all" {{ (string)($selectedAcademicYearId ?? '') === 'all' ? 'selected' : '' }}>
                គ្រប់ឆ្នាំសិក្សាទាំងអស់
            </option>
        </select>

        <select name="grade_id" class="form-select filter-select" style="min-width:160px; width:auto;" onchange="this.form.submit()">
            <option value="">កម្រិតថ្នាក់ទាំងអស់</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                    {{ $grade->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-filter">ស្វែងរក</button>

        @if(request()->anyFilled(['search', 'grade_id']) || (request()->filled('academic_year_id') && request('academic_year_id') !== ($academicYears->firstWhere('is_active', true)?->id ?? '')))
            <a href="{{ route('academics.index', ['tab' => 'classes']) }}" class="btn btn-sm btn-link text-danger p-0" style="font-size:0.8rem">
                <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
            </a>
        @endif

        @if($classes_paginated && $classes_paginated->isNotEmpty())
            <div class="ms-auto d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group" aria-label="Accordion controls">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" style="font-size: 0.78rem;" onclick="toggleAllAccordions(true)" title="ពន្លាតកម្រិតថ្នាក់ទាំងអស់">
                        <i class="bi bi-chevron-expand me-1"></i>ពន្លាតទាំងអស់
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" style="font-size: 0.78rem;" onclick="toggleAllAccordions(false)" title="បង្រួមកម្រិតថ្នាក់ទាំងអស់">
                        <i class="bi bi-chevron-contract me-1"></i>បង្រួមទាំងអស់
                    </button>
                </div>
            </div>
        @endif
    </form>
</div>

@if($classes_paginated && $classes_paginated->isNotEmpty())
    @php
        $isAllYearsMode = (string)($selectedAcademicYearId ?? '') === 'all';
        if ($isAllYearsMode) {
            $yearGroups = $classes_paginated->getCollection()->groupBy(function($c) {
                return $c->academic_year_id ?? 0;
            });
        } else {
            $yearGroups = [
                ($selectedAcademicYearId ?? 0) => $classes_paginated->getCollection()
            ];
        }
    @endphp

    <div class="py-2.5 px-3 bg-light">
        @foreach($yearGroups as $ayId => $yearClasses)
            @php
                $yearModel = $yearClasses->first()?->academic_year ?? $academicYears->firstWhere('id', $ayId);
                $yearName = $yearModel ? $yearModel->name : 'Unassigned Year';
                $gradeGroups = $yearClasses->groupBy(function($c) {
                    return $c->grade_id ?? 0;
                });
            @endphp

            @if($isAllYearsMode)
                <div class="d-flex align-items-center justify-content-between mb-3 {{ !$loop->first ? 'mt-4 pt-3 border-top' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 bg-warning text-dark d-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;">
                            <i class="bi bi-calendar3 fs-6"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold text-dark mb-0 fs-6">ឆ្នាំសិក្សា {{ $yearName }}</h6>
                                @if($yearModel?->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0" style="font-size: 0.68rem;">
                                        សកម្ម (Active)
                                    </span>
                                @endif
                            </div>
                            <span class="text-muted small">សរុប {{ $yearClasses->count() }} ថ្នាក់ក្នុងឆ្នាំសិក្សានេះ</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="accordion d-flex flex-column gap-3 mb-3" id="gradesAccordion_{{ $ayId }}">
                @foreach($gradeGroups as $gradeId => $classGroup)
                    @php
                        $firstClass = $classGroup->first();
                        $grade = $firstClass->grade;
                        $gradeName = $grade ? $grade->name : 'ទូទៅ / មិនទាន់កំណត់';
                        $gradeLevel = $grade && $grade->level ? 'កម្រិត ' . $grade->level : null;
                        $totalSections = $classGroup->count();
                        $totalCapacity = $classGroup->sum('capacity');
                        $totalStudents = $classGroup->sum('students_count');
                        $sectionsSummary = $classGroup->map(fn($c) => $c->section?->name)->filter()->join(', ');
                        $accordionId = 'collapseGrade_' . $ayId . '_' . ($gradeId ?: 'general');
                        $isOpen = true; // Open by default for immediate overview
                    @endphp

                    <div class="card border-0 shadow-xs rounded-3 overflow-hidden bg-white">
                        <div class="card-header bg-white p-0 border-0">
                            <button class="accordion-button {{ $isOpen ? '' : 'collapsed' }} px-3 py-2 bg-white"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $accordionId }}"
                                    aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                    aria-controls="{{ $accordionId }}"
                                    style="box-shadow: none;">
                                <div class="d-flex align-items-center justify-content-between w-100 pe-2 flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-3 text-white"
                                             style="width: 32px; height: 32px; background: linear-gradient(135deg, #e8a838, #b45309);">
                                            <i class="bi bi-mortarboard-fill fs-6"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">{{ $gradeName }}</h6>
                                                @if($gradeLevel)
                                                    <span class="badge bg-secondary-subtle text-secondary border px-1.5 py-0" style="font-size: 0.65rem;">
                                                        {{ $gradeLevel }}
                                                    </span>
                                                @endif
                                                @if($isAllYearsMode)
                                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-1.5 py-0" style="font-size: 0.65rem;">
                                                        <i class="bi bi-calendar3 me-1"></i>{{ $yearName }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-muted small mt-0.5" style="font-size: 0.72rem;">
                                                <i class="bi bi-grid-fill me-1 text-warning"></i>
                                                <strong>{{ $totalSections }}</strong> បន្ទប់ / ក្រុមថ្នាក់
                                                @if($sectionsSummary)
                                                    <span class="text-muted fw-semibold">({{ $sectionsSummary }})</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-1.5">
                                        <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 0.72rem;">
                                            <i class="bi bi-people text-primary me-1"></i>
                                            <strong>{{ $totalStudents }}</strong> នាក់
                                        </span>
                                        <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 0.72rem;">
                                            <i class="bi bi-person-workspace text-success me-1"></i>
                                            <strong>{{ $totalCapacity }}</strong> កៅអីសរុប
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </div>

                        <div id="{{ $accordionId }}"
                             class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }} grade-accordion-collapse">
                            <div class="card-body p-0 border-top">
                                <div class="table-responsive">
                                    <table class="table table-custom mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th class="ps-3" style="width: 50px;">ល.រ</th>
                                                <th>ថ្នាក់រៀន</th>
                                                <th>ក្រុម / ផ្នែក</th>
                                                <th>បន្ទប់រៀនជាក់ស្តែង</th>
                                                <th>ឆ្នាំសិក្សា</th>
                                                <th class="text-center">ចំនួនសិស្ស / ចំណុះ</th>
                                                <th class="text-center">មុខវិជ្ជា</th>
                                                <th class="text-center">កាលវិភាគ</th>
                                                <th class="text-end pe-3">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($classGroup as $class)
                                                <tr>
                                                    <td class="ps-3 text-muted" style="font-size: 0.85rem;">#{{ $class->id }}</td>
                                                    <td>
                                                        <div class="d-inline-flex align-items-center gap-2">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 fw-bold" style="font-size: 0.85rem;">
                                                                {{ $class->name }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark border px-2 py-0.5" style="font-size: 0.82rem;">
                                                            <i class="bi bi-tag me-1 text-muted"></i>
                                                            {{ $class->section->name ?? 'Default' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($class->classroom)
                                                            @php
                                                                $conflictClass = ($roomConflicts[$class->id] ?? null) ?: $class->getConflictingClass();
                                                            @endphp
                                                            <div class="d-inline-flex align-items-center flex-wrap gap-1">
                                                                <span class="badge {{ $conflictClass ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-warning-subtle text-dark border border-warning-subtle' }} px-2 py-0.5 fw-semibold" style="font-size: 0.82rem;">
                                                                    <i class="bi bi-door-closed-fill {{ $conflictClass ? 'text-danger' : 'text-warning' }} me-1"></i>
                                                                    {{ $class->classroom->name }}
                                                                    @if($class->classroom->building)
                                                                        <span class="{{ $conflictClass ? 'text-danger-emphasis' : 'text-muted' }} fw-normal">({{ $class->classroom->building }})</span>
                                                                    @endif
                                                                </span>
                                                                @if($conflictClass)
                                                                    <span class="badge bg-danger text-white px-1.5 py-0.5"
                                                                          style="font-size: 0.7rem; cursor: help;"
                                                                          data-bs-toggle="tooltip"
                                                                          data-bs-placement="top"
                                                                          title="ស្ទួនបន្ទប់ជាមួយ «{{ $conflictClass->name }}» ក្នុងវេន{{ $conflictClass->shift_label }}!">
                                                                        <i class="bi bi-exclamation-triangle-fill me-0.5"></i>ស្ទួនបន្ទប់
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted small" style="font-size: 0.78rem;">— មិនទាន់កំណត់ —</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="year-badge">
                                                            <i class="bi bi-calendar3"></i>
                                                            {{ $class->academic_year->name ?? '—' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                                class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1.5 py-0 px-2 shadow-xs rounded-pill"
                                                                style="font-size: 0.82rem; line-height: 1.6;"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#classStudentsRow_{{ $class->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="classStudentsRow_{{ $class->id }}"
                                                                title="ចុចដើម្បីមើលបញ្ជីសិស្សក្នុងថ្នាក់នេះ">
                                                            <span class="fw-bold"><span class="{{ $class->students_count > 0 ? 'text-primary' : 'text-secondary' }}">{{ $class->students_count }}</span>/{{ $class->capacity ?? 0 }}</span>
                                                            <span class="text-muted" style="font-size: 0.74rem;">សិស្ស</span>
                                                            <i class="bi bi-chevron-down text-muted" style="font-size: 0.6rem;"></i>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge border fw-semibold" style="background:#fdf4ff; border-color:#f5d0fe; color:#7e22ce; font-size: 0.82rem; padding: 0.2rem 0.55rem;">
                                                            {{ $class->class_subjects_count ?? 0 }} មុខវិជ្ជា
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-secondary border fw-semibold" style="font-size: 0.82rem; padding: 0.2rem 0.55rem;">
                                                            {{ $class->timetables_count ?? 0 }} កាលវិភាគ
                                                        </span>
                                                    </td>
                                                    <td class="text-end pe-3">
                                                        <div class="d-inline-flex gap-1">
                                                            <button type="button"
                                                                    class="action-btn text-primary"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#classStudentsRow_{{ $class->id }}"
                                                                    title="បង្ហាញបញ្ជីឈ្មោះសិស្ស">
                                                                 <i class="bi bi-people-fill"></i>
                                                            </button>
                                                            <a href="{{ route('classes.show', $class) }}" class="action-btn" data-bs-toggle="tooltip" title="ព័ត៌មានលម្អិត">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            @if(auth()->user()?->canAccess('classes.edit'))
                                                            <a href="{{ route('classes.edit', $class) }}" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួលថ្នាក់">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            @endif
                                                            @if(auth()->user()?->canAccess('classes.delete'))
                                                            <button type="button" class="action-btn action-btn-danger"
                                                                    data-bs-toggle="tooltip" title="លុបថ្នាក់រៀន"
                                                                    onclick="confirmDelete('{{ route('classes.destroy', $class) }}', '{{ addslashes($class->name) }}', 'Class')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>

                                                {{-- Collapsible Student Roster Sub-Row --}}
                                                <tr id="classStudentsRow_{{ $class->id }}" class="collapse bg-light-subtle">
                                                    <td colspan="9" class="p-0 border-0">
                                                        <div class="py-2 px-3 bg-light border-top border-bottom">
                                                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1">
                                                                        <i class="bi bi-people-fill text-warning me-1"></i>បញ្ជីឈ្មោះសិស្ស (Student Roster)
                                                                    </span>
                                                                    <span class="fw-bold text-dark small">{{ $class->name }}</span>
                                                                    <span class="text-muted small">| សរុប៖ <strong>{{ $class->students->count() }}</strong> / {{ $class->capacity ?? 0 }} កៅអី</span>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-outline-secondary px-2 py-1" style="font-size: 0.78rem;">
                                                                        <i class="bi bi-box-arrow-up-right me-1"></i>ព័ត៌មានលម្អិតថ្នាក់
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            @if($class->students->isNotEmpty())
                                                                <div class="table-responsive bg-white rounded-3 border shadow-xs">
                                                                    <table class="table table-sm table-hover mb-0 align-middle" style="font-size: 0.82rem;">
                                                                        <thead class="table-light text-muted">
                                                                            <tr>
                                                                                <th class="ps-3" style="width: 45px;">#</th>
                                                                                <th>អត្តលេខ (ID)</th>
                                                                                <th>ឈ្មោះសិស្ស (Student Name)</th>
                                                                                <th>ភេទ (Gender)</th>
                                                                                <th>លេខរៀង (Roll No)</th>
                                                                                <th>លេខទូរស័ព្ទ (Contact)</th>
                                                                                <th>ស្ថានភាព (Status)</th>
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
                                                                                        <span class="badge bg-light text-dark border font-monospace">
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
                                                                                                     style="width: 28px; height: 28px;"
                                                                                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                                                                                <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-none align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                                                                    {{ mb_substr($khmerName ?: $latinName, 0, 1) }}
                                                                                                </div>
                                                                                            @else
                                                                                                <div class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                                                                    {{ mb_substr($khmerName ?: $latinName, 0, 1) }}
                                                                                                </div>
                                                                                            @endif
                                                                                            <div>
                                                                                                <div class="fw-bold text-dark">{{ $khmerName ?: $latinName }}</div>
                                                                                                @if($khmerName && $latinName && $khmerName !== $latinName)
                                                                                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $latinName }}</div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="badge {{ $genderBadge }} px-2 py-0" style="font-size: 0.7rem;">
                                                                                            {{ $genderLabel }}
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="text-muted">{{ $student->roll_number ?? '—' }}</td>
                                                                                    <td class="text-muted">{{ $profile?->phone ?? $student->user?->phone ?? '—' }}</td>
                                                                                    <td>
                                                                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0" style="font-size: 0.7rem;">
                                                                                            {{ $student->studentStatus?->name ?? 'Active' }}
                                                                                        </span>
                                                                                    </td>
                                                                                    <td class="text-end pe-3">
                                                                                        <a href="{{ route('students.show', $student) }}" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size: 0.72rem;" title="View Student Profile">
                                                                                            <i class="bi bi-eye me-1"></i>ប្រវត្តិ
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <div class="bg-white rounded-3 p-3 text-center border">
                                                                    <i class="bi bi-people text-muted opacity-50 fs-4 mb-1 d-block"></i>
                                                                    <div class="text-muted small">មិនទាន់មានសិស្សចុះឈ្មោះក្នុងថ្នាក់ <strong>{{ $class->name }}</strong> នេះនៅឡើយទេ។</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5 text-muted bg-white rounded-3 shadow-xs">
        <div class="mb-3">
            <i class="bi bi-diagram-3 fs-1 text-muted opacity-50"></i>
        </div>
        <h6 class="fw-bold text-dark mb-1">មិនមានថ្នាក់រៀនឡើយ</h6>
        <p class="text-muted small mb-3">សូមសាកល្បងផ្លាស់ប្តូរការច្រោះស្វែងរក ឬបង្កើតថ្នាក់រៀនថ្មី។</p>
        @if(auth()->user()?->canAccess('classes.create'))
        <a href="{{ route('classes.create') }}" class="btn-primary-action">
            <i class="bi bi-plus-lg"></i>បង្កើតថ្នាក់រៀន
        </a>
        @endif
    </div>
@endif

@if($classes_paginated && $classes_paginated->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
        <div class="text-muted small">
            បង្ហាញ {{ $classes_paginated->firstItem() }} – {{ $classes_paginated->lastItem() }} នៃ {{ $classes_paginated->total() }}
        </div>
        {{ $classes_paginated->links('pagination::bootstrap-5') }}
    </div>
@endif

<script>
    function toggleAllAccordions(expand) {
        const collapses = document.querySelectorAll('.grade-accordion-collapse');
        collapses.forEach(el => {
            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
            if (expand) {
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        });
    }
</script>
