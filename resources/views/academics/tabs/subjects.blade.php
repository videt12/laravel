<div class="filter-bar border-0 border-bottom rounded-0" style="border-radius:0!important">
    <form action="{{ route('academics.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap w-100 m-0">
        <input type="hidden" name="tab" value="subjects">

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះ ឬកូដមុខវិជ្ជា...">
        </div>

        <select name="type" class="form-select filter-select" style="min-width:160px; width:auto;" onchange="this.form.submit()">
            <option value="">ប្រភេទទាំងអស់</option>
            @foreach($types as $type)
                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                    {{ \App\Models\Academics\Subject::typeLabel($type) }}
                </option>
            @endforeach
            @if(!in_array('Core', $types->toArray(), true) && !in_array('core', array_map('strtolower', $types->toArray()), true))
                <option value="Core" {{ request('type') == 'Core' ? 'selected' : '' }}>មុខវិជ្ជាស្នូល (Core)</option>
            @endif
            @if(!in_array('Mandatory', $types->toArray(), true) && !in_array('mandatory', array_map('strtolower', $types->toArray()), true))
                <option value="Mandatory" {{ request('type') == 'Mandatory' || request('type') == 'mandatory' ? 'selected' : '' }}>កាតព្វកិច្ច (Mandatory)</option>
            @endif
        </select>

        <button type="submit" class="btn-filter">ស្វែងរក</button>

        @if(request()->anyFilled(['search', 'type']))
            <a href="{{ route('academics.index', ['tab' => 'subjects']) }}" class="btn btn-sm btn-link text-danger p-0" style="font-size:0.8rem">
                <i class="bi bi-x-circle me-1"></i>សម្អាត
            </a>
        @endif
    </form>
</div>

<div class="table-responsive">
    <table class="table table-custom mb-0">
        <thead>
            <tr>
                <th style="width:40px">ល.រ</th>
                <th>មុខវិជ្ជា</th>
                <th>ប្រភេទ</th>
                <th class="text-center">ថ្នាក់ដែលបានកំណត់</th>
                <th class="text-end">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects_paginated as $subject)
                <tr>
                    <td class="text-muted" style="font-size:0.75rem">#{{ $subject->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#f1f5f9;color:#64748b;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $subject->name }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);font-family:monospace">{{ $subject->code }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $typeClass = match(strtolower($subject->type ?? '')) {
                                'core', 'mandatory'   => 'type-Core',
                                'elective', 'optional' => 'type-Elective',
                                'practical', 'theory' => 'type-Practical',
                                default               => 'type-default',
                            };
                        @endphp
                        <span class="type-badge {{ $typeClass }}">{{ $subject->type_label }}</span>
                    </td>
                    <td style="max-width: 340px;">
                        @if($subject->class_subjects->isNotEmpty())
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                @foreach($subject->class_subjects as $cs)
                                    @if($cs->class)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size:0.75rem">
                                            <i class="bi bi-mortarboard me-1"></i>{{ $cs->class->grade?->name ?? 'ថ្នាក់' }}{{ $cs->class->section?->name ? ' — ' . $cs->class->section->name : '' }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <span class="badge bg-light text-muted border px-2 py-1" style="font-size:0.75rem">
                                <i class="bi bi-dash-circle me-1"></i>មិនទាន់កំណត់ថ្នាក់
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('subjects.show', $subject) }}" class="action-btn" data-bs-toggle="tooltip" title="ព័ត៌មានលម្អិត">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()?->canAccess('subjects.edit'))
                            <button type="button" class="action-btn"
                                    data-bs-toggle="tooltip" title="កែសម្រួលមុខវិជ្ជា"
                                    onclick='openEditSubjectModal({{ $subject->id }}, "{{ addslashes($subject->name) }}", "{{ addslashes($subject->code) }}", "{{ addslashes($subject->type ?? "Core") }}", {{ json_encode($subject->class_subjects->whereNotNull("class_id")->pluck("class_id")->toArray()) }})'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            @endif
                            @php
                                $classNames = $subject->class_subjects
                                    ->filter(fn($cs) => $cs->class !== null)
                                    ->map(function($cs) {
                                        $grade = $cs->class->grade?->name ?? 'ថ្នាក់';
                                        $section = $cs->class->section?->name ? ' — ' . $cs->class->section->name : '';
                                        return $grade . $section;
                                    })
                                    ->filter()
                                    ->values();
                            @endphp
                            @if(auth()->user()?->canAccess('subjects.delete'))
                            <button type="button" class="action-btn action-btn-danger"
                                    data-bs-toggle="tooltip" title="លុបមុខវិជ្ជា"
                                    onclick='confirmDeleteSubject("{{ route('subjects.destroy', $subject) }}", "{{ addslashes($subject->name) }}", {{ json_encode($classNames) }})'>
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state-cell">
                        <div class="empty-icon"><i class="bi bi-book"></i></div>
                        <h6 class="fw-bold text-dark mb-1">មិនមានមុខវិជ្ជាឡើយ</h6>
                        <p class="text-muted small mb-3">សូមសាកល្បងផ្លាស់ប្តូរការច្រោះស្វែងរក ឬបន្ថែមមុខវិជ្ជាថ្មី។</p>
                        @if(auth()->user()?->canAccess('subjects.create'))
                        <button type="button" class="btn-primary-action" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                            <i class="bi bi-plus-lg"></i>បន្ថែមមុខវិជ្ជា
                        </button>
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($subjects_paginated && $subjects_paginated->hasPages())
    <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
        <div class="text-muted small">
            បង្ហាញ {{ $subjects_paginated->firstItem() }} – {{ $subjects_paginated->lastItem() }} នៃ {{ $subjects_paginated->total() }}
        </div>
        {{ $subjects_paginated->links('pagination::bootstrap-5') }}
    </div>
@endif
