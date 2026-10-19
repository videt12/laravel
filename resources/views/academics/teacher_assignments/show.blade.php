@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតការចាត់តាំងគ្រូបង្រៀន — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item"><a href="{{ route('teacher-assignments.index') }}" class="text-decoration-none text-muted">ការចាត់តាំងគ្រូបង្រៀន</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ព័ត៌មានលម្អិត</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-dark">
            {{ $teacherAssignment->employee?->name ?: ($teacherAssignment->employee?->user?->name ?? 'មិនស្គាល់គ្រូ') }}
        </h1>
        <p class="text-muted mb-0">
            ថ្នាក់រៀន: <span class="fw-semibold text-dark">{{ $teacherAssignment->class?->grade->name ?? $teacherAssignment->class_subject?->class->grade->name ?? '—' }} — {{ $teacherAssignment->class?->section->name ?? $teacherAssignment->class_subject?->class->section->name ?? '—' }}</span>
            &bull; ការចាត់តាំង: <span class="fw-semibold text-dark">{{ $teacherAssignment->class_subject?->subject?->name ?? 'គ្រូបន្ទុកថ្នាក់' }}</span>
            &bull; ឆ្នាំសិក្សា: <span class="badge bg-light text-dark border">{{ $teacherAssignment->academic_year->name ?? '—' }}</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher-assignments.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
        </a>
        <a href="{{ route('teacher-assignments.edit', $teacherAssignment) }}" class="btn btn-primary-action btn-sm shadow-sm">
            <i class="bi bi-pencil me-1"></i>កែសម្រួល
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-badge me-2 text-primary"></i>ព័ត៌មានគ្រូបង្រៀន</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted w-40">ឈ្មោះគ្រូ:</td>
                            <td class="fw-bold">{{ $teacherAssignment->employee->user->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">អត្តលេខ:</td>
                            <td><code>{{ $teacherAssignment->employee->employee_code ?? '—' }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">ផ្នែក:</td>
                            <td>{{ $teacherAssignment->employee->employee_department->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">តួនាទី:</td>
                            <td>{{ $teacherAssignment->employee->employee_position->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">អ៊ីមែល:</td>
                            <td>{{ $teacherAssignment->employee->user->email ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-diagram-3 me-2 text-primary"></i>ព័ត៌មានការចាត់តាំង</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted w-40">ថ្នាក់រៀន:</td>
                            <td class="fw-bold">
                                {{ $teacherAssignment->class?->grade->name ?? $teacherAssignment->class_subject?->class->grade->name ?? '—' }} — 
                                {{ $teacherAssignment->class?->section->name ?? $teacherAssignment->class_subject?->class->section->name ?? '—' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">ប្រភេទ:</td>
                            <td class="fw-bold">{{ $teacherAssignment->class_subject?->subject?->name ?? 'គ្រូបន្ទុកថ្នាក់' }}</td>
                        </tr>
                        @if($teacherAssignment->class_subject?->subject?->code)
                        <tr>
                            <td class="text-muted">កូដមុខវិជ្ជា:</td>
                            <td><code>{{ $teacherAssignment->class_subject->subject->code }}</code></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">ឆ្នាំសិក្សា:</td>
                            <td>{{ $teacherAssignment->academic_year->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">វត្តមាន:</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    {{ $teacherAssignment->attendance_sessions_count }} លើក
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Attendance Sessions --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="bi bi-calendar-check me-2 text-primary"></i>វត្តមានបង្រៀនថ្មីៗ
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">កាលបរិច្ឆេទ</th>
                        <th>ម៉ោងសិក្សា</th>
                        <th>ស្ថានភាព</th>
                        <th>ចំណាំ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teacherAssignment->attendance_sessions as $session)
                        <tr>
                            <td class="ps-4 fw-bold">
                                {{ \Carbon\Carbon::parse($session->attendance_date)->format('d/m/Y') }}
                            </td>
                            <td>ម៉ោងទី {{ $session->period }}</td>
                            <td>
                                <span class="badge {{ $session->status === 'closed' ? 'bg-secondary' : 'bg-success' }}">
                                    {{ $session->status === 'closed' ? 'បិទ' : 'សកម្ម' }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ $session->remarks ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                មិនទាន់មានកំណត់ត្រាវត្តមានបង្រៀននៅឡើយទេ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
