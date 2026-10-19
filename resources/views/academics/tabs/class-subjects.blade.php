<div class="filter-bar border-0 border-bottom rounded-0" style="border-radius:0!important">
    <form action="{{ route('academics.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap w-100 m-0">
        <input type="hidden" name="tab" value="class-subjects">

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះថ្នាក់ ឬមុខវិជ្ជា...">
        </div>

        <select name="grade_id" class="form-select filter-select" style="width:160px">
            <option value="">កម្រិតថ្នាក់ទាំងអស់</option>
            @foreach($grades as $g)
                <option value="{{ $g->id }}" {{ request('grade_id') == $g->id ? 'selected' : '' }}>
                    {{ $g->name }}
                </option>
            @endforeach
        </select>

        <select name="academic_year_id" class="form-select filter-select" style="min-width:160px; width:auto;">
            <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
            @foreach($academicYears as $ay)
                <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                    {{ $ay->name }}{{ $ay->is_active ? ' (បច្ចុប្បន្ន)' : '' }}
                </option>
            @endforeach
        </select>

        <select name="subject_id" class="form-select filter-select" style="width:160px">
            <option value="">មុខវិជ្ជាទាំងអស់</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->name }} ({{ $s->code }})
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-filter">ស្វែងរក</button>

        @if(request()->anyFilled(['search', 'grade_id', 'academic_year_id', 'subject_id']))
            <a href="{{ route('academics.index', ['tab' => 'class-subjects']) }}" class="btn btn-sm btn-link text-danger p-0" style="font-size:0.8rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-custom align-middle mb-0">
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th style="min-width:140px">ថ្នាក់រៀន</th>
                <th style="min-width:120px">ឆ្នាំសិក្សា</th>
                <th style="min-width:320px">មុខវិជ្ជាដែលរៀន</th>
                <th class="text-center" style="min-width:110px">ចំនួនមុខវិជ្ជា</th>
                <th class="text-end pe-4" style="min-width:160px">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classSubjects as $index => $classItem)
                @php
                    $assignedSubjectIds = $classItem->class_subjects->pluck('subject_id')->toArray();
                @endphp
                <tr>
                    <td class="text-muted" style="font-size:0.875rem">{{ $classSubjects->firstItem() + $index }}</td>
                    <td>
                        <span class="class-badge">
                            <i class="bi bi-diagram-3"></i>
                            {{ $classItem->grade->name ?? '—' }} — {{ $classItem->section->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="year-badge">
                            <i class="bi bi-calendar-event"></i>
                            {{ $classItem->academic_year->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        @if($classItem->class_subjects->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1.5 align-items-center py-1">
                                @foreach($classItem->class_subjects as $cs)
                                    @if($cs->subject)
                                        <a href="{{ route('class-subjects.show', $cs) }}" 
                                           class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2.5 py-1.5 shadow-xs d-inline-flex align-items-center gap-1.5"
                                           style="font-size: 0.85rem;"
                                           title="ចុចដើម្បីមើលព័ត៌មានលម្អិតមុខវិជ្ជានេះ">
                                            <i class="bi bi-book"></i>
                                            <span>{{ $cs->subject->name }}</span>
                                            <span class="opacity-75 font-monospace" style="font-size:0.75rem">({{ $cs->subject->code }})</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <span class="badge bg-light text-muted border px-2.5 py-1.5" style="font-size: 0.825rem;">
                                <i class="bi bi-exclamation-circle me-1"></i>មិនទាន់មានមុខវិជ្ជា
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $classItem->class_subjects->count() > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-muted border' }} px-2.5 py-1.5" style="font-size: 0.825rem;">
                            {{ $classItem->class_subjects->count() }} មុខវិជ្ជា
                        </span>
                    </td>
                    <td class="pe-4">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <button type="button"
                                    class="btn btn-primary-action shadow-xs py-1.5 px-3"
                                    style="font-size: 0.875rem;"
                                    onclick='openAssignSubjectsModal({{ $classItem->id }}, "{{ addslashes(($classItem->grade->name ?? '') . " — " . ($classItem->section->name ?? "")) }}", {{ json_encode($assignedSubjectIds) }})'>
                                <i class="bi bi-plus-circle me-1"></i>ដាក់មុខវិជ្ជា
                            </button>
                            <a href="{{ route('classes.show', $classItem) }}" class="action-btn" title="មើលថ្នាក់រៀន">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state-cell py-5 text-center">
                        <div class="empty-icon text-muted mb-2 fs-1"><i class="bi bi-journal-x"></i></div>
                        <h6 class="fw-bold text-dark mb-1">មិនមានទិន្នន័យថ្នាក់រៀនឡើយ</h6>
                        <p class="text-muted small mb-0">មិនទាន់មានថ្នាក់រៀនណាមួយត្រូវគ្នានឹងលក្ខខណ្ឌស្វែងរកនេះទេ។</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($classSubjects && $classSubjects->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
        <div class="text-muted small">
            បង្ហាញ {{ $classSubjects->firstItem() }} ដល់ {{ $classSubjects->lastItem() }} នៃ {{ $classSubjects->total() }} ថ្នាក់
        </div>
        {{ $classSubjects->links('pagination::bootstrap-5') }}
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════
     MODAL: ASSIGN SUBJECTS TO CLASS (MULTI-SELECT CHECKBOXES)
═══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="assignSubjectsModal" tabindex="-1" aria-labelledby="assignSubjectsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('class-subjects.sync') }}" method="POST" id="assignSubjectsForm">
                @csrf
                <input type="hidden" name="class_id" id="modalClassId">
                
                <div class="modal-header bg-white py-3 border-bottom">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="assignSubjectsModalLabel">
                            <i class="bi bi-journal-check text-primary me-2"></i>ដាក់មុខវិជ្ជាសម្រាប់ថ្នាក់រៀន
                        </h5>
                        <div class="text-muted small mt-1">
                            ថ្នាក់រៀន៖ <span id="modalClassName" class="fw-bold text-primary"></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <div class="fw-bold text-dark small">
                            <i class="bi bi-check2-square text-primary me-1"></i>ជ្រើសរើសមុខវិជ្ជាដែលត្រូវរៀនក្នុងថ្នាក់នេះ៖
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2.5" onclick="toggleAllSubjects(true)" style="font-size:0.78rem">
                                <i class="bi bi-check-all me-1"></i>ជ្រើសរើសទាំងអស់
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5" onclick="toggleAllSubjects(false)" style="font-size:0.78rem">
                                <i class="bi bi-x me-1"></i>ដកទាំងអស់
                            </button>
                        </div>
                    </div>

                    <div class="row g-2" id="subjectsCheckboxesContainer" style="max-height: 380px; overflow-y: auto;">
                        @foreach($subjects as $s)
                            <div class="col-md-6 col-lg-4">
                                <label class="subject-card-option d-flex align-items-center gap-2.5 p-2.5 rounded-3 border h-100 cursor-pointer text-start w-100" 
                                       for="sub_check_{{ $s->id }}"
                                       id="sub_label_{{ $s->id }}">
                                    <input type="checkbox" 
                                           name="subject_ids[]" 
                                           value="{{ $s->id }}" 
                                           id="sub_check_{{ $s->id }}" 
                                           class="form-check-input mt-0 subject-checkbox flex-shrink-0"
                                           onchange="updateCardStyle(this)">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-dark text-truncate small">{{ $s->name }}</div>
                                        <div class="d-flex align-items-center gap-1 mt-0.5">
                                             <span class="badge bg-light text-secondary border font-monospace" style="font-size:0.65rem">{{ $s->code }}</span>
                                            @if($s->type)
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size:0.65rem">{{ $s->type }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer bg-white py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកមុខវិជ្ជា
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.subject-card-option {
    cursor: pointer;
    background: #ffffff;
    border-color: #e2e8f0;
    transition: all 0.15s ease;
    user-select: none;
}
.subject-card-option:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.subject-card-option.is-selected {
    background: #eff6ff;
    border-color: #3b82f6;
}
</style>

<script>
function openAssignSubjectsModal(classId, className, assignedSubjectIds) {
    document.getElementById('modalClassId').value = classId;
    document.getElementById('modalClassName').innerText = className;
    
    const checkboxes = document.querySelectorAll('.subject-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = assignedSubjectIds.includes(parseInt(cb.value));
        updateCardStyle(cb);
    });

    const modalEl = document.getElementById('assignSubjectsModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

function updateCardStyle(checkbox) {
    const label = document.getElementById('sub_label_' + checkbox.value);
    if (!label) return;
    if (checkbox.checked) {
        label.classList.add('is-selected');
    } else {
        label.classList.remove('is-selected');
    }
}

function toggleAllSubjects(select) {
    const checkboxes = document.querySelectorAll('.subject-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = select;
        updateCardStyle(cb);
    });
}
</script>
