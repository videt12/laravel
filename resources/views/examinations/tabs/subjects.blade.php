{{-- Tab 3: Exam Subjects Management --}}
<div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
    <div class="card-body py-2 px-3">
        <form action="{{ route('examinations.index') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="subjects">
            <div class="col-12 col-md-3">
                <div class="search-wrapper w-100" style="max-width: 100%;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="ស្វែងរកមុខវិជ្ជា ឬការប្រឡង...">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <select name="exam_schedule_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">កាលវិភាគទាំងអស់</option>
                    @foreach($allSchedules as $s)
                        <option value="{{ $s->id }}" {{ request('exam_schedule_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->exam?->name }} — {{ $s->schoolClass?->name }} {{ $s->section ? '('.$s->section->name.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="subject_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">មុខវិជ្ជាទាំងអស់</option>
                    @foreach($allSubjects as $sub)
                        <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>
                            {{ $sub->name }} ({{ $sub->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-filter flex-fill">
                    <i class="bi bi-filter me-1"></i> ចម្រាញ់
                </button>
                @if(request()->hasAny(['search', 'exam_schedule_id', 'subject_id']))
                    <a href="{{ route('examinations.index', ['tab' => 'subjects']) }}" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
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
                    <th class="ps-4">មុខវិជ្ជា</th>
                    <th>ការប្រឡង</th>
                    <th>ថ្នាក់រៀន</th>
                    <th>កាលបរិច្ឆេទ & ម៉ោង</th>
                    <th>ពិន្ទុ (អតិបរមា / ជាប់)</th>
                    <th>ស្ថានភាពបញ្ចូលពិន្ទុ</th>
                    <th class="text-end pe-4">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($examSubjects_paginated as $esub)
                    <tr>
                        <td class="ps-4">
                            @if($canManageExams ?? false)
                                <a href="{{ route('exam-subjects.show', $esub->id) }}" class="fw-semibold text-dark text-decoration-none">
                                    {{ $esub->subject?->name ?? '—' }}
                                </a>
                            @else
                                <span class="fw-semibold text-dark">{{ $esub->subject?->name ?? '—' }}</span>
                            @endif
                            <div class="text-muted small">{{ $esub->subject?->code ?? '' }}</div>
                        </td>
                        <td>
                            @if($canManageExams ?? false)
                                <a href="{{ route('exams.show', $esub->exam_schedule?->exam_id) }}" class="text-decoration-none">
                                    {{ $esub->exam_schedule?->exam?->name ?? '—' }}
                                </a>
                            @else
                                <span>{{ $esub->exam_schedule?->exam?->name ?? '—' }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $esub->exam_schedule?->schoolClass?->name ?? '—' }}
                            @if($esub->exam_schedule?->section)
                                <span class="text-muted small">({{ $esub->exam_schedule->section->name }})</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $esub->exam_date?->format('d/m/Y') ?? 'មិនទាន់កំណត់កាលបរិច្ឆេទ' }}
                            @if($esub->start_time)
                                <br>{{ $esub->start_time }} — {{ $esub->end_time }}
                            @endif
                        </td>
                        <td>
                            <strong>{{ number_format($esub->max_marks, 2) }}</strong>
                            <span class="text-muted small">/ {{ number_format($esub->pass_marks, 2) }}</span>
                        </td>
                        <td>
                            @if($esub->exam_results_count > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> បញ្ចូលពិន្ទុរួច ({{ $esub->exam_results_count }})
                                </span>
                            @else
                                <span class="badge bg-light text-muted border px-2 py-1">
                                    មិនទាន់បញ្ចូលពិន្ទុ
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('exam-subjects.scores', $esub->id) }}" class="btn btn-sm btn-gold text-white py-1 px-2" style="background-color: var(--gold, #e8a838); font-size: 0.78rem;" title="តារាងពិន្ទុ">
                                    <i class="bi bi-pencil-square me-1"></i> បញ្ចូលពិន្ទុ
                                </a>
                                @if($canManageExams ?? false)
                                <a href="{{ route('exam-subjects.show', $esub->id) }}" class="action-btn" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                                @if($canManageExams ?? false)
                                <button type="button" class="action-btn" title="កែសម្រួលវិញ្ញាសា"
                                        onclick="openEditExamSubjectModal({{ $esub->id }}, {{ $esub->exam_schedule_id }}, {{ $esub->subject_id }}, '{{ $esub->exam_date ? $esub->exam_date->format('Y-m-d') : '' }}', '{{ $esub->start_time ? substr($esub->start_time, 0, 5) : '' }}', '{{ $esub->end_time ? substr($esub->end_time, 0, 5) : '' }}', '{{ $esub->max_marks }}', '{{ $esub->pass_marks }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($esub->exam_results_count === 0)
                                    <button type="button" class="action-btn action-btn-danger" title="លុបវិញ្ញាសា"
                                            onclick="confirmDelete('{{ route('exam-subjects.destroy', $esub->id) }}', '{{ addslashes($esub->subject?->name ?? 'Subject') }} Paper', 'Exam Subject Paper')">
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
                            <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                            មិនមានវិញ្ញាសាប្រឡងឡើយ។
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($examSubjects_paginated && $examSubjects_paginated->hasPages())
        <div class="p-3 border-top">
            {{ $examSubjects_paginated->links() }}
        </div>
    @endif
</div>
