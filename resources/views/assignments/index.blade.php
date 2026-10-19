@extends('layouts.app')

@section('title', 'កិច្ចការសាលា — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កិច្ចការសាលា</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.filter-select, .filter-input {
    font-size: .8rem; padding: .3rem .65rem;
    border-radius: var(--radius-sm); border: 1px solid var(--border);
    color: var(--text-dark); background-color: #fff; height: 32px;
}
.filter-select:focus, .filter-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); outline: none; }
</style>
@endpush

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">កិច្ចការសាលា</h1>
        <p class="page-subtitle">គ្រប់គ្រងកិច្ចការសាលា និងការងារសិស្ស។</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="btn btn-primary-action">
        <i class="bi bi-plus-lg me-1"></i>បង្កើតកិច្ចការថ្មី
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="toolbar-card mb-4">
    <form method="GET" action="{{ route('assignments.index') }}" id="filterForm">
        <div class="row g-2 align-items-center">
            <div class="col-xl-3 col-lg-3 col-md-4">
                <input type="text" name="search" class="filter-input w-100" placeholder="ស្វែងរកតាមចំណងជើង..." value="{{ request('search') }}">
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4">
                <select name="class_id" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">ថ្នាក់ទាំងអស់</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->grade?->name }}{{ $class->section?->name ? ' — '.$class->section->name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-4">
                <select name="subject_id" class="filter-select w-100" onchange="this.form.submit()">
                    <option value="">មុខវិជ្ជាទាំងអស់</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-outline-secondary px-3" style="height: 32px;">ចម្រាញ់</button>
                @if(request()->hasAny(['search', 'class_id', 'subject_id']))
                    <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-link text-muted text-decoration-none px-2">
                        <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark">កិច្ចការសាលា</span>
            <span class="count-badge">{{ $assignments->total() }}</span>
        </div>
        <small class="text-muted">
            @if($assignments->total() > 0)
                បង្ហាញពី {{ $assignments->firstItem() }}–{{ $assignments->lastItem() }} នៃ {{ $assignments->total() }}
            @endif
        </small>
    </div>

    @if($assignments->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i class="bi bi-journal-text"></i></div>
            <p class="empty-title">មិនមានកិច្ចការសាលាឡើយ</p>
            <p class="empty-subtitle">
                @if(request()->hasAny(['search', 'class_id', 'subject_id']))
                    មិនមានកិច្ចការសាលាណាដែលត្រូវនឹងតម្រងស្វែងរកឡើយ។ <a href="{{ route('assignments.index') }}" class="text-decoration-none">សម្អាតតម្រង</a>
                @else
                    បង្កើតកិច្ចការដំបូងរបស់អ្នកដើម្បីចាប់ផ្ដើម។
                @endif
            </p>
            @if(!request()->hasAny(['search', 'class_id', 'subject_id']))
                <a href="{{ route('assignments.create') }}" class="btn btn-primary-action btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>បង្កើតកិច្ចការថ្មី
                </a>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40px">#</th>
                        <th>កិច្ចការសាលា</th>
                        <th>ថ្នាក់រៀន</th>
                        <th>មុខវិជ្ជា</th>
                        <th>កាលបរិច្ឆេទផុតកំណត់</th>
                        <th>ពិន្ទុសរុប</th>
                        <th style="width:52px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $i => $assignment)
                    @php
                        $classLabel = ($assignment->class?->grade?->name ?? '—');
                        $sectionName = $assignment->section?->name ?? '';
                        if ($sectionName) $classLabel .= ' — ' . $sectionName;
                    @endphp
                    <tr>
                        <td class="text-center text-muted" style="font-size:.75rem">{{ $assignments->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold text-dark" style="font-size:.875rem">{{ $assignment->title }}</div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark" style="font-size:.875rem">{{ $classLabel }}</div>
                        </td>
                        <td>
                            <div class="text-muted" style="font-size:.84rem">{{ $assignment->subject?->name ?? '—' }}</div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark" style="font-size:.875rem">
                                {{ $assignment->due_date?->format('d/m/Y') ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:.84rem">{{ $assignment->total_marks }}</span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn-action-dots" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="min-width:168px;font-size:.875rem">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('assignments.show', $assignment->id) }}">
                                            <i class="bi bi-eye me-2 text-muted"></i>មើលព័ត៌មានលម្អិត
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('assignments.edit', $assignment->id) }}">
                                            <i class="bi bi-pencil me-2 text-muted"></i>កែសម្រួល
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <button type="button" class="dropdown-item py-2 text-danger"
                                                data-bs-toggle="modal" data-bs-target="#delModal{{ $assignment->id }}">
                                            <i class="bi bi-trash me-2"></i>លុប
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="modal fade" id="delModal{{ $assignment->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body p-4 text-center">
                                            <div class="mb-3">
                                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:48px;height:48px">
                                                    <i class="bi bi-trash text-danger fs-5"></i>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold mb-1">លុបកិច្ចការនេះមែនទេ?</h6>
                                            <p class="text-muted small mb-4">
                                                <strong>{{ $assignment->title }}</strong>
                                            </p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                                                <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger px-3">លុប</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($assignments->hasPages())
        <div class="data-card-footer d-flex align-items-center justify-content-between">
            <div class="text-muted" style="font-size:.8rem">ទំព័រ {{ $assignments->currentPage() }} នៃ {{ $assignments->lastPage() }}</div>
            {{ $assignments->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @endif
</div>

@endsection
