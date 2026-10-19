<div class="filter-bar border-0 border-bottom rounded-0" style="border-radius:0!important">
    <form action="{{ route('academics.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap w-100 m-0">
        <input type="hidden" name="tab" value="teacher-assignments">

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះគ្រូ ឬមុខវិជ្ជា...">
        </div>

        <select name="employee_id" class="form-select filter-select" style="width:170px">
            <option value="">គ្រូបង្រៀនទាំងអស់</option>
            @foreach($employees as $e)
                <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>
                    {{ $e->user->name ?? $e->employee_code }}
                </option>
            @endforeach
        </select>

        <select name="academic_year_id" id="ta_filter_year_id" class="form-select filter-select" style="min-width:160px; width:auto;">
            <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
            @foreach($academicYears as $ay)
                <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                    {{ $ay->name }}{{ $ay->is_active ? ' (បច្ចុប្បន្ន)' : '' }}
                </option>
            @endforeach
        </select>

        <select name="class_id" id="ta_filter_class_id" class="form-select filter-select" style="width:160px">
            <option value="">ថ្នាក់រៀនទាំងអស់</option>
            @foreach($classes as $c)
                <option value="{{ $c->id }}" data-year-id="{{ $c->academic_year_id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->grade->name ?? 'Grade' }} — {{ $c->section->name ?? 'Section' }}
                </option>
            @endforeach
        </select>

        <select name="subject_id" class="form-select filter-select" style="width:155px">
            <option value="">មុខវិជ្ជាទាំងអស់</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                    {{ $s->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-filter">ស្វែងរក</button>

        @if(request()->anyFilled(['search', 'employee_id', 'class_id', 'subject_id', 'academic_year_id']))
            <a href="{{ route('academics.index', ['tab' => 'teacher-assignments']) }}" class="btn btn-sm btn-link text-danger p-0" style="font-size:0.8rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-custom mb-0">
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>គ្រូបង្រៀន</th>
                <th>ថ្នាក់រៀន</th>
                <th>មុខវិជ្ជា</th>
                <th>ឆ្នាំសិក្សា</th>
                <th>ចំនួនវេន/ម៉ោង</th>
                <th class="text-end">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teacherAssignments as $index => $item)
                <tr>
                    <td class="text-muted" style="font-size:0.75rem">{{ $teacherAssignments->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-primary fw-bold"
                                 style="width:34px;height:34px;font-size:0.8rem;flex-shrink:0">
                                {{ mb_substr($item->employee?->name ?: ($item->employee?->user?->name ?? 'T'), 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $item->employee?->name ?: ($item->employee?->user?->name ?? 'មិនស្គាល់គ្រូ') }}</div>
                                <small class="text-muted">{{ $item->employee->employee_code ?? '—' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="class-badge">
                            <i class="bi bi-diagram-3"></i>
                            {{ $item->class?->grade?->name ?? $item->class_subject?->class?->grade?->name ?? '—' }} — {{ $item->class?->section?->name ?? $item->class_subject?->class?->section?->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        @if($item->class_subject?->subject)
                            <span class="subject-badge">
                                <i class="bi bi-book"></i>
                                {{ $item->class_subject->subject->name }}
                            </span>
                        @else
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-person-check me-1 text-success"></i>គ្រូបន្ទុកថ្នាក់
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="year-badge">
                            <i class="bi bi-calendar-event"></i>
                            {{ $item->academic_year->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $item->attendance_sessions_count > 0 ? 'bg-success bg-opacity-10 text-success border border-success' : 'bg-light text-muted border' }}">
                            {{ $item->attendance_sessions_count }} វេន
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('teacher-assignments.show', $item) }}" class="action-btn" data-bs-toggle="tooltip" title="ព័ត៌មានលម្អិត">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()?->canAccess(['classes.edit', 'classes.create']))
                            <a href="{{ route('teacher-assignments.edit', $item) }}" class="action-btn" data-bs-toggle="tooltip" title="កែសម្រួលការបែងចែក">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                            @if(auth()->user()?->canAccess('classes.delete'))
                            <button type="button" class="action-btn action-btn-danger"
                                    data-bs-toggle="tooltip" title="លុបការបែងចែក"
                                    onclick="confirmDelete('{{ route('teacher-assignments.destroy', $item) }}', '{{ addslashes($item->employee->user->name ?? 'Teacher') }}', 'ការបែងចែកគ្រូបង្រៀន')">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state-cell">
                        <div class="empty-icon"><i class="bi bi-person-x"></i></div>
                        <h6 class="fw-bold text-dark mb-1">មិនមានការបែងចែកគ្រូបង្រៀនឡើយ</h6>
                        <p class="text-muted small mb-3">មិនមានទិន្នន័យការបែងចែកត្រូវនឹងលក្ខខណ្ឌស្វែងរករបស់អ្នកឡើយ។</p>
                        @if(auth()->user()?->canAccess('classes.create'))
                        <a href="{{ route('teacher-assignments.create') }}" class="btn-primary-action">
                            <i class="bi bi-plus-lg"></i>បែងចែកគ្រូបង្រៀន
                        </a>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($teacherAssignments && $teacherAssignments->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
        <div class="text-muted small">
            បង្ហាញ {{ $teacherAssignments->firstItem() }} – {{ $teacherAssignments->lastItem() }} នៃ {{ $teacherAssignments->total() }}
        </div>
        {{ $teacherAssignments->links('pagination::bootstrap-5') }}
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterYear = document.getElementById('ta_filter_year_id');
    const filterClass = document.getElementById('ta_filter_class_id');
    if (!filterYear || !filterClass) return;

    const allOptions = Array.from(filterClass.querySelectorAll('option')).map(opt => ({
        value: opt.value,
        text: opt.textContent.trim(),
        yearId: opt.getAttribute('data-year-id') || ''
    }));

    function filterTabClasses() {
        const selectedYear = filterYear.value;
        const currentVal = filterClass.value;

        filterClass.innerHTML = '';
        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.textContent = 'ថ្នាក់រៀនទាំងអស់';
        filterClass.appendChild(defaultOpt);

        allOptions.forEach(item => {
            if (!item.value) return;
            if (!selectedYear || String(item.yearId) === String(selectedYear)) {
                const opt = document.createElement('option');
                opt.value = item.value;
                opt.textContent = item.text;
                opt.setAttribute('data-year-id', item.yearId);
                if (String(item.value) === String(currentVal)) {
                    opt.selected = true;
                }
                filterClass.appendChild(opt);
            }
        });
    }

    filterYear.addEventListener('change', filterTabClasses);
    if (filterYear.value) {
        filterTabClasses();
    }
});
</script>
