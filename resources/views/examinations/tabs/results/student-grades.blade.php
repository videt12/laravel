{{-- Secondary Tab: Student Grades & Overall Results --}}
<div class="card border-0 shadow-xs rounded-3 bg-white mb-2">
    <div class="card-body py-2 px-3">
        <form method="GET" action="{{ route('examinations.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="results">
            <input type="hidden" name="subtab" value="grades">
            <div class="col-12 col-md-3">
                <select name="exam_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ការប្រឡងទាំងអស់</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->name }} ({{ $exam->academic_year?->name ?? 'Year' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="class_id" class="form-select form-select-sm filter-select w-100">
                    <option value="">ថ្នាក់ទាំងអស់</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="result_status" class="form-select form-select-sm filter-select w-100">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <option value="passed" {{ request('result_status') == 'passed' ? 'selected' : '' }}>ជាប់</option>
                    <option value="failed" {{ request('result_status') == 'failed' ? 'selected' : '' }}>ធ្លាក់</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <div class="search-wrapper w-100" style="max-width: 100%;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ឈ្មោះ / លេខអត្តសញ្ញាណ" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-filter flex-fill">
                    <i class="bi bi-filter me-1"></i> ចម្រាញ់
                </button>
                @if(request()->hasAny(['search', 'exam_id', 'class_id', 'result_status']))
                    <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'grades']) }}" class="btn btn-sm btn-light border" title="សម្អាតតម្រង">
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
                    <th class="ps-4" style="width: 70px;">#</th>
                    <th>សិស្ស</th>
                    <th>ការប្រឡង</th>
                    <th>ថ្នាក់រៀន</th>
                    <th>ពិន្ទុសរុប</th>
                    <th>ភាគរយ</th>
                    <th>និទ្ទេសរួម</th>
                    <th>GPA</th>
                    <th>លទ្ធផល</th>
                    <th class="text-end pe-4">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($studentGrades_paginated as $grade)
                    <tr>
                        <td class="ps-4 text-muted small">#{{ $grade->id }}</td>
                        <td>
                            <div class="fw-semibold text-dark">
                                {{ $grade->student?->first_name }} {{ $grade->student?->last_name }}
                            </div>
                            <div class="text-muted small">អត្តលេខ: {{ $grade->student?->admission_number ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="fw-medium text-dark">{{ $grade->exam?->name ?? '—' }}</span>
                            <div class="text-muted small">{{ $grade->exam?->academic_year?->name ?? '' }}</div>
                        </td>
                        <td>
                            {{ $grade->student?->schoolClass?->name ?? '—' }}
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ number_format($grade->total_marks, 2) }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-primary">{{ number_format($grade->percentage, 2) }}%</span>
                        </td>
                        <td>
                            @if($grade->grade)
                                @php
                                    $sgBadges = [
                                        'A+' => ['bg' => '#167a48', 'color' => '#ffffff'],
                                        'A'  => ['bg' => '#1c8f53', 'color' => '#ffffff'],
                                        'B+' => ['bg' => '#5ba860', 'color' => '#ffffff'],
                                        'B'  => ['bg' => '#f6b825', 'color' => '#111827'],
                                        'C+' => ['bg' => '#f07926', 'color' => '#ffffff'],
                                        'C'  => ['bg' => '#ff7d25', 'color' => '#ffffff'],
                                        'D'  => ['bg' => '#fa4e24', 'color' => '#ffffff'],
                                        'F'  => ['bg' => '#dc2638', 'color' => '#ffffff'],
                                    ];
                                    $sgStyle = $sgBadges[$grade->grade] ?? ['bg' => '#64748b', 'color' => '#ffffff'];
                                @endphp
                                <span class="badge rounded-pill fw-bold" style="background-color: {{ $sgStyle['bg'] }}; color: {{ $sgStyle['color'] }}; min-width: 44px; padding: 4px 10px; font-size: 0.85rem;">
                                    {{ $grade->grade }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ number_format($grade->gpa, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ strtolower($grade->result_status) === 'passed' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2 py-1 text-capitalize">
                                {{ strtolower($grade->result_status) === 'passed' ? 'ជាប់' : (strtolower($grade->result_status) === 'failed' ? 'ធ្លាក់' : ($grade->result_status ?? '—')) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('student-grades.show', $grade->id) }}" class="btn btn-sm btn-outline-info py-1 px-2" style="font-size: 0.8rem;" title="មើលព្រឹត្តិបត្រពិន្ទុ">
                                <i class="bi bi-file-earmark-text me-1"></i> ព្រឹត្តិបត្រពិន្ទុ
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-card-heading fs-2 d-block mb-2 text-muted opacity-50"></i>
                            មិនមានទិន្នន័យនិទ្ទេសប្រឡងរបស់សិស្សឡើយ។
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($studentGrades_paginated && $studentGrades_paginated->hasPages())
        <div class="p-3 border-top">
            {{ $studentGrades_paginated->links() }}
        </div>
    @endif
</div>
