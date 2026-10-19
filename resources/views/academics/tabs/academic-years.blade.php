<div class="filter-bar border-0 border-bottom rounded-0" style="border-radius:0!important">
    <form action="{{ route('academics.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap w-100 m-0">
        <input type="hidden" name="tab" value="academic-years">

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកឆ្នាំសិក្សា (ឧ. 2026-2027)...">
        </div>

        <select name="status" class="form-select filter-select" style="min-width:170px; width:auto;" onchange="this.form.submit()">
            <option value="">ស្ថានភាពទាំងអស់</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>តែឆ្នាំសកម្ម</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>តែឆ្នាំអសកម្ម</option>
        </select>

        <button type="submit" class="btn-filter">ស្វែងរក</button>

        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('academics.index', ['tab' => 'academic-years']) }}" class="btn btn-sm btn-link text-danger p-0" style="font-size:0.8rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-custom mb-0">
        <thead>
            <tr>
                <th style="width:60px">ល.រ</th>
                <th>ឆ្នាំសិក្សា</th>
                <th>ថ្ងៃចាប់ផ្ដើម</th>
                <th>ថ្ងៃបញ្ចប់</th>
                <th class="text-center">ថ្នាក់រៀនពាក់ព័ន្ធ</th>
                <th class="text-center">ស្ថានភាព</th>
                <th class="text-end">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($academicYears_paginated as $year)
                <tr>
                    <td class="text-muted" style="font-size:0.75rem">#{{ $year->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                                <i class="bi bi-calendar-range"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    {{ $year->name }}
                                    @if($year->is_active)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary" style="font-size:0.65rem">បច្ចុប្បន្ន</span>
                                    @endif
                                </div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">
                                    {{ $year->start_date ? $year->start_date->format('M Y') : '?' }} &ndash; {{ $year->end_date ? $year->end_date->format('M Y') : '?' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-dark small">
                            <i class="bi bi-calendar-event me-1 text-muted"></i>{{ $year->start_date ? $year->start_date->format('M d, Y') : '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-dark small">
                            <i class="bi bi-calendar-check me-1 text-muted"></i>{{ $year->end_date ? $year->end_date->format('M d, Y') : '—' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="metric-pill">
                            <span class="metric-val">{{ $year->classes_count }}</span>
                            <span class="metric-lbl">ថ្នាក់</span>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($year->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i>សកម្ម
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">
                                <i class="bi bi-dash-circle me-1"></i>អសកម្ម
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-end align-items-center gap-1">
                            {{-- Edit Modal Button --}}
                            @if(auth()->user()?->canAccess('academic-years.edit'))
                            <button type="button" class="action-btn"
                                    data-bs-toggle="tooltip" title="កែសម្រួលឆ្នាំសិក្សា"
                                    onclick="openEditAcademicYearModal('{{ $year->id }}', '{{ addslashes($year->name) }}', '{{ $year->start_date ? $year->start_date->format('Y-m-d') : '' }}', '{{ $year->end_date ? $year->end_date->format('Y-m-d') : '' }}', {{ $year->is_active ? 1 : 0 }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @endif

                            {{-- Safe Delete Button --}}
                            @if(auth()->user()?->canAccess('academic-years.delete'))
                            <button type="button" class="action-btn action-btn-danger"
                                    data-bs-toggle="tooltip" title="លុបឆ្នាំសិក្សា"
                                    onclick="confirmDelete('{{ route('academic-years.destroy', $year) }}', '{{ addslashes($year->name) }}', 'ឆ្នាំសិក្សា')">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state-cell">
                        <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                        <h6 class="fw-bold text-dark mb-1">មិនមានឆ្នាំសិក្សាឡើយ</h6>
                        <p class="text-muted small mb-3">មិនមានឆ្នាំសិក្សាដែលត្រូវនឹងលក្ខខណ្ឌស្វែងរករបស់អ្នកឡើយ។</p>
                        @if(auth()->user()?->canAccess('academic-years.create'))
                        <button type="button" class="btn-primary-action" data-bs-toggle="modal" data-bs-target="#createAcademicYearModal">
                            <i class="bi bi-plus-lg"></i>បន្ថែមឆ្នាំសិក្សា
                        </button>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($academicYears_paginated && $academicYears_paginated->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
        <div class="text-muted small">
            បង្ហាញ {{ $academicYears_paginated->firstItem() }} – {{ $academicYears_paginated->lastItem() }} នៃ {{ $academicYears_paginated->total() }}
        </div>
        {{ $academicYears_paginated->links('pagination::bootstrap-5') }}
    </div>
@endif
