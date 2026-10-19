<div class="filter-bar border-0 border-bottom rounded-0" style="border-radius:0!important">
    <form action="{{ route('academics.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap w-100 m-0">
        <input type="hidden" name="tab" value="classrooms">

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះបន្ទប់ ឬអគារ...">
        </div>

        @if($buildings->isNotEmpty())
            <select name="building" class="form-select filter-select" style="min-width:170px; width:auto;" onchange="this.form.submit()">
                <option value="">អគារទាំងអស់</option>
                @foreach($buildings as $b)
                    <option value="{{ $b }}" {{ request('building') == $b ? 'selected' : '' }}>
                        {{ $b }}
                    </option>
                @endforeach
            </select>
        @endif

        <button type="submit" class="btn-filter">ស្វែងរក</button>

        @if(request()->anyFilled(['search', 'building']))
            <a href="{{ route('academics.index', ['tab' => 'classrooms']) }}" class="btn btn-sm btn-link text-danger p-0" style="font-size:0.8rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-custom mb-0">
        <thead>
            <tr>
                <th style="width:50px">ល.រ</th>
                <th>ឈ្មោះបន្ទប់</th>
                <th>អគារ / ប្លុក</th>
                <th class="text-center">ចំណុះ (កៅអី)</th>
                <th class="text-end">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classrooms_paginated as $room)
                <tr>
                    <td class="text-muted" style="font-size:0.75rem">#{{ $room->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#fef3c7;color:#b45309;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                                <i class="bi bi-door-closed"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $room->name }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted)">បន្ទប់រៀនផ្ទាល់</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($room->building)
                            <span class="badge bg-light text-dark border px-2 py-1">
                                <i class="bi bi-building me-1 text-muted"></i>{{ $room->building }}
                            </span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="metric-pill">
                            <span class="metric-val">{{ $room->capacity ?? '—' }}</span>
                            <span class="metric-lbl">កៅអី</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            @if(auth()->user()?->canAccess('classes.edit'))
                            <button type="button" class="action-btn"
                                    data-bs-toggle="tooltip" title="កែសម្រួលបន្ទប់រៀន"
                                    onclick="openEditClassroomModal('{{ $room->id }}', '{{ addslashes($room->name) }}', '{{ addslashes($room->building ?? '') }}', '{{ $room->capacity ?? '' }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @endif
                            @if(auth()->user()?->canAccess('classes.delete'))
                            <button type="button" class="action-btn action-btn-danger"
                                    data-bs-toggle="tooltip" title="លុបបន្ទប់រៀន"
                                    onclick="confirmDelete('{{ route('classrooms.destroy', $room) }}', '{{ addslashes($room->name) }}', 'បន្ទប់រៀន')">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state-cell">
                        <div class="empty-icon"><i class="bi bi-door-closed"></i></div>
                        <h6 class="fw-bold text-dark mb-1">មិនមានបន្ទប់រៀនឡើយ</h6>
                        <p class="text-muted small mb-3">មិនមានបន្ទប់រៀនដែលត្រូវនឹងលក្ខខណ្ឌស្វែងរករបស់អ្នកឡើយ។</p>
                        @if(auth()->user()?->canAccess('classes.create'))
                        <button type="button" class="btn-primary-action" data-bs-toggle="modal" data-bs-target="#createClassroomModal">
                            <i class="bi bi-plus-lg"></i>បន្ថែមបន្ទប់រៀន
                        </button>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($classrooms_paginated && $classrooms_paginated->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
        <div class="text-muted small">
            បង្ហាញ {{ $classrooms_paginated->firstItem() }} – {{ $classrooms_paginated->lastItem() }} នៃ {{ $classrooms_paginated->total() }}
        </div>
        {{ $classrooms_paginated->links('pagination::bootstrap-5') }}
    </div>
@endif
