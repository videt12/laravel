{{-- Tab 2: Exam Schedules Management --}}
<div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
    <div class="card-body py-2 px-3">
        <form action="{{ route('examinations.index') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="schedules">
            <div class="col-12 col-md-5">
                <select name="exam_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ការប្រឡងទាំងអស់</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->name }} ({{ $exam->academic_year?->name ?? 'Year' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-5">
                <select name="class_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ថ្នាក់ទាំងអស់</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-filter flex-fill">
                    <i class="bi bi-filter me-1"></i> ចម្រាញ់
                </button>
                @if(request()->hasAny(['exam_id', 'class_id']))
                    <a href="{{ route('examinations.index', ['tab' => 'schedules']) }}" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 70px;">លេខសម្គាល់</th>
                    <th>ការប្រឡង</th>
                    <th>ឆ្នាំសិក្សា</th>
                    <th>ថ្នាក់រៀន</th>
                    <th>បន្ទប់/ក្រុម</th>
                    <th>វិញ្ញាសាប្រឡង</th>
                    <th class="text-end pe-4">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules_paginated as $s)
                    <tr>
                        <td class="ps-4 text-muted small">#{{ $s->id }}</td>
                        <td>
                            @if($canManageExams ?? false)
                                <a href="{{ route('exams.show', $s->exam_id) }}" class="fw-semibold text-dark text-decoration-none">
                                    {{ $s->exam?->name ?? '—' }}
                                </a>
                            @else
                                <span class="fw-semibold text-dark">{{ $s->exam?->name ?? '—' }}</span>
                            @endif
                        </td>
                        <td>{{ $s->exam?->academic_year?->name ?? '—' }}</td>
                        <td class="fw-semibold text-dark">{{ $s->schoolClass?->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $s->section?->name ?? 'គ្រប់បន្ទប់ទាំងអស់' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('examinations.index', ['tab' => 'subjects', 'exam_schedule_id' => $s->id]) }}" class="text-decoration-none">
                                <span class="badge bg-light text-dark border">
                                    {{ $s->exam_subjects_count }} វិញ្ញាសា
                                </span>
                            </a>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                @if($canManageExams ?? false)
                                <a href="{{ route('exam-schedules.show', $s->id) }}" class="action-btn" title="មើលព័ត៌មានកាលវិភាគ">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                                @if($canManageExams ?? false)
                                <button type="button" class="action-btn" title="បន្ថែមវិញ្ញាសា"
                                        onclick="openCreateExamSubjectForSchedule({{ $s->id }})">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                                <button type="button" class="action-btn" title="កែសម្រួលកាលវិភាគ"
                                        onclick="openEditScheduleModal({{ $s->id }}, {{ $s->exam_id }}, {{ $s->class_id }}, '{{ $s->section_id ?? '' }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($s->exam_subjects_count === 0)
                                    <button type="button" class="action-btn action-btn-danger" title="លុបកាលវិភាគ"
                                            onclick="confirmDelete('{{ route('exam-schedules.destroy', $s->id) }}', 'Schedule #{{ $s->id }} ({{ addslashes($s->schoolClass?->name ?? 'Class') }})', 'Exam Schedule')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                            មិនមានកាលវិភាគប្រឡងឡើយ។
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schedules_paginated && $schedules_paginated->hasPages())
        <div class="p-3 border-top">
            {{ $schedules_paginated->links() }}
        </div>
    @endif
</div>
