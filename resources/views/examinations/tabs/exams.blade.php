{{-- Tab 1: Exams Management --}}
<div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
    <div class="card-body py-2 px-3">
        <form action="{{ route('examinations.index') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="exams">
            <div class="col-12 col-md-4">
                <div class="search-wrapper w-100" style="max-width: 100%;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="ស្វែងរកឈ្មោះការប្រឡង...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="academic_year_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_active ? '(សកម្ម)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="exam_type_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ប្រភេទប្រឡងទាំងអស់</option>
                    @foreach($examTypes as $type)
                        <option value="{{ $type->id }}" {{ request('exam_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-filter flex-fill">
                    <i class="bi bi-filter me-1"></i> ចម្រាញ់
                </button>
                @if(request()->hasAny(['search', 'academic_year_id', 'exam_type_id']))
                    <a href="{{ route('examinations.index', ['tab' => 'exams']) }}" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
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
                    <th class="ps-4">ឈ្មោះការប្រឡង</th>
                    <th>ប្រភេទ</th>
                    <th>ឆ្នាំសិក្សា</th>
                    <th>ឆមាស / វគ្គ</th>
                    <th>កាលបរិច្ឆេទប្រឡង</th>
                    <th>ថ្នាក់ដែលបានកំណត់</th>
                    <th>សិស្សដែលបានបញ្ចូលពិន្ទុ</th>
                    <th class="text-end pe-4">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams_paginated as $exam)
                    <tr>
                        <td class="ps-4">
                            @if($canManageExams ?? false)
                                <a href="{{ route('exams.show', $exam->id) }}" class="fw-semibold text-dark text-decoration-none">
                                    {{ $exam->name }}
                                </a>
                            @else
                                <span class="fw-semibold text-dark">{{ $exam->name }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                {{ $exam->exam_type?->name ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $exam->academic_year?->name ?? '—' }}</td>
                        <td>{{ $exam->school_term?->name ?? 'ពេញមួយឆ្នាំ' }}</td>
                        <td class="text-muted small">
                            @if($exam->start_date && $exam->end_date)
                                {{ $exam->start_date->format('d M') }} — {{ $exam->end_date->format('d M Y') }}
                            @elseif($exam->start_date)
                                ចាប់ផ្ដើម {{ $exam->start_date->format('d M Y') }}
                            @else
                                មិនទាន់កំណត់
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('examinations.index', ['tab' => 'schedules', 'exam_id' => $exam->id]) }}" class="text-decoration-none">
                                <span class="badge bg-light text-dark border">
                                    {{ $exam->exam_schedules_count }} ថ្នាក់
                                </span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'grades', 'exam_id' => $exam->id]) }}" class="text-decoration-none">
                                <span class="badge {{ $exam->student_grades_count > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }}">
                                    {{ $exam->student_grades_count }} នាក់
                                </span>
                            </a>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                @if($canManageExams ?? false)
                                <a href="{{ route('exams.show', $exam->id) }}" class="action-btn" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endif
                                @if($canManageExams ?? false)
                                <button type="button" class="action-btn" title="កែសម្រួលការប្រឡង"
                                        onclick="openEditExamModal({{ $exam->id }}, '{{ addslashes($exam->name) }}', {{ $exam->exam_type_id }}, {{ $exam->academic_year_id }}, '{{ $exam->school_term_id ?? '' }}', '{{ $exam->start_date ? $exam->start_date->format('Y-m-d') : '' }}', '{{ $exam->end_date ? $exam->end_date->format('Y-m-d') : '' }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($exam->exam_schedules_count === 0 && $exam->student_grades_count === 0)
                                    <button type="button" class="action-btn action-btn-danger" title="លុបការប្រឡង"
                                            onclick="confirmDelete('{{ route('exams.destroy', $exam->id) }}', '{{ addslashes($exam->name) }}', 'Exam')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2 text-muted opacity-50"></i>
                            មិនមានទិន្នន័យការប្រឡងដែលត្រូវនឹងលក្ខខណ្ឌស្វែងរកឡើយ។
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($exams_paginated && $exams_paginated->hasPages())
        <div class="p-3 border-top">
            {{ $exams_paginated->links() }}
        </div>
    @endif
</div>
