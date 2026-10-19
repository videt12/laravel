@extends('layouts.app')

@section('title', 'ការចាត់តាំងគ្រូបង្រៀន — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការចាត់តាំងគ្រូបង្រៀន</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.page-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    background: #eef2ff;
    color: #4f46e5;
    border-radius: var(--radius-md);
    font-size: 1.15rem;
}
.page-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0; }

.filter-bar {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.filter-inputs {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.table-card {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.table-custom { margin-bottom: 0; width: 100%; border-collapse: separate; border-spacing: 0; }
.table-custom thead th {
    background: #f8fafc;
    color: var(--text-muted);
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-soft);
    white-space: nowrap;
}
.table-custom tbody tr { transition: background-color 0.15s; }
.table-custom tbody tr:hover { background-color: #fafbfc; }
.table-custom tbody td {
    padding: 1rem 1.25rem;
    font-size: 0.85rem;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-soft);
    vertical-align: middle;
}
.table-custom tbody tr:last-child td { border-bottom: none; }

.teacher-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-weight: 700;
    color: #1e293b;
}
.class-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
    border-radius: var(--radius-sm);
    padding: 0.2rem 0.6rem;
    font-weight: 700;
    font-size: 0.8rem;
}
.subject-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: var(--radius-sm);
    padding: 0.2rem 0.6rem;
    font-weight: 600;
    font-size: 0.8rem;
}
.year-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: #f1f5f9;
    color: #475569;
    border-radius: 999px;
    padding: 0.15rem 0.6rem;
    font-size: 0.73rem;
    font-weight: 600;
}
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    color: var(--text-muted);
    transition: all 0.2s;
    background: transparent; border: none;
}
.action-btn:hover { background: #f1f5f9; color: var(--text-dark); }
.action-btn-danger:hover { background: #fef2f2; color: #dc2626; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
    <div>{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <div>{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h1 class="page-title">
            <div class="page-title-icon"><i class="bi bi-person-workspace"></i></div>
            ការចាត់តាំងគ្រូបង្រៀន
        </h1>
        <p class="page-subtitle">ចាត់តាំងគ្រូបង្រៀនទៅតាមថ្នាក់រៀន និងមុខវិជ្ជាសម្រាប់ឆ្នាំសិក្សា</p>
    </div>
    <div>
        <a href="{{ route('teacher-assignments.create') }}" class="btn btn-primary-action shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>ចាត់តាំងគ្រូបង្រៀនថ្មី
        </a>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="filter-bar mb-4">
    <form action="{{ route('teacher-assignments.index') }}" method="GET" class="filter-inputs w-100 justify-content-between">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- Search input --}}
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="ស្វែងរក..." value="{{ request('search') }}">
            </div>

            {{-- Filter by Teacher --}}
            <select name="employee_id" class="form-select form-select-sm" style="width: 180px;">
                <option value="">គ្រូបង្រៀនទាំងអស់</option>
                @foreach($employees as $e)
                    <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>
                        {{ $e->user->name ?? $e->employee_code }}
                    </option>
                @endforeach
            </select>

            {{-- Filter by Class --}}
            <select name="class_id" class="form-select form-select-sm" style="width: 170px;">
                <option value="">ថ្នាក់រៀនទាំងអស់</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->grade->name ?? 'ថ្នាក់' }} — {{ $c->section->name ?? 'បន្ទប់' }}
                    </option>
                @endforeach
            </select>

            {{-- Filter by Subject --}}
            <select name="subject_id" class="form-select form-select-sm" style="width: 170px;">
                <option value="">មុខវិជ្ជាទាំងអស់</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>

            {{-- Filter by Academic Year --}}
            <select name="academic_year_id" class="form-select form-select-sm" style="width: 180px;">
                <option value="">ឆ្នាំសិក្សាទាំងអស់</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                        {{ $ay->name }} {{ $ay->is_active ? '(ឆ្នាំសកម្ម)' : '' }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-sm btn-outline-secondary">ស្វែងរក</button>
            @if(request()->anyFilled(['search', 'employee_id', 'class_id', 'subject_id', 'academic_year_id']))
                <a href="{{ route('teacher-assignments.index') }}" class="btn btn-sm btn-link text-muted">សម្អាត</a>
            @endif
        </div>
    </form>
</div>

{{-- DATA TABLE --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>គ្រូបង្រៀន</th>
                    <th>ថ្នាក់រៀន</th>
                    <th>មុខវិជ្ជា</th>
                    <th>ឆ្នាំសិក្សា</th>
                    <th>វត្តមាន</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teacherAssignments as $index => $item)
                    <tr>
                        <td class="text-muted" style="width: 50px;">{{ $teacherAssignments->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-primary fw-bold" style="width:34px;height:34px;font-size:0.8rem;">
                                    {{ substr($item->employee->user->name ?? 'T', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $item->employee->user->name ?? 'មិនស្គាល់គ្រូ' }}</div>
                                    <small class="text-muted">{{ $item->employee->employee_code ?? '—' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="class-badge">
                                <i class="bi bi-diagram-3"></i>
                                {{ $item->class?->grade->name ?? $item->class_subject?->class->grade->name ?? '—' }} — {{ $item->class?->section->name ?? $item->class_subject?->class->section->name ?? '—' }}
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
                                {{ $item->attendance_sessions_count }} លើក
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('teacher-assignments.show', $item) }}" class="action-btn" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('teacher-assignments.edit', $item) }}" class="action-btn" title="កែសម្រួល">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('teacher-assignments.destroy', $item) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('តើអ្នកពិតជាចង់លុបការចាត់តាំងគ្រូបង្រៀននេះមែនទេ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger" title="លុប">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-person-x fs-1"></i></div>
                            <h6 class="fw-bold mb-1">មិនមានទិន្នន័យការចាត់តាំងគ្រូបង្រៀន</h6>
                            <p class="text-muted small mb-3">មិនមានការចាត់តាំងគ្រូបង្រៀនត្រូវនឹងលក្ខខណ្ឌស្វែងរកទេ</p>
                            <a href="{{ route('teacher-assignments.create') }}" class="btn btn-sm btn-primary-action">
                                <i class="bi bi-plus-lg me-1"></i>ចាត់តាំងគ្រូបង្រៀនថ្មី
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($teacherAssignments->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                បង្ហាញ {{ $teacherAssignments->firstItem() }} ដល់ {{ $teacherAssignments->lastItem() }} នៃ {{ $teacherAssignments->total() }} កំណត់ត្រា
            </div>
            <div>
                {{ $teacherAssignments->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

@endsection
