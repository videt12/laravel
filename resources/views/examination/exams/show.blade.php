@extends('layouts.app')

@section('title', $exam->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'exams']) }}" class="text-decoration-none text-muted">ការប្រឡង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">ព័ត៌មានលម្អិត</li>
    </ol>
</nav>
@endsection

@section('content')
@php
    $user = auth()->user();
    $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
    $canManageExams = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || (! $isTeacher && $user->canAccess('exams.create')));
@endphp
<div class="container-fluid px-0">
    {{-- Top Header --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2.5 mb-3 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">{{ $exam->name }}</h2>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 0.75rem;">
                    {{ $exam->exam_type?->name ?? 'ការប្រឡង' }}
                </span>
            </div>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem;">
                ឆ្នាំសិក្សា៖ <strong class="text-dark">{{ $exam->academic_year?->name ?? '—' }}</strong> &bull;
                ឆមាស/វគ្គ៖ <strong class="text-dark">{{ $exam->school_term?->name ?? 'ពេញមួយឆ្នាំ' }}</strong>
                @if($exam->start_date && $exam->end_date)
                    &bull; កាលបរិច្ឆេទ៖ <strong class="text-dark">{{ $exam->start_date->format('d/m/Y') }} — {{ $exam->end_date->format('d/m/Y') }}</strong>
                @endif
            </p>
        </div>

        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <a href="{{ route('examinations.index', ['tab' => 'exams']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
            </a>
            @if($canManageExams)
                <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                    <i class="bi bi-pencil"></i><span>កែសម្រួល</span>
                </a>
                <a href="{{ route('exam-schedules.create', ['exam_id' => $exam->id]) }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1 shadow-sm" style="height: 32px; font-size: 0.82rem; font-weight: 600; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px;">
                    <i class="bi bi-calendar-plus"></i><span>កំណត់កាលវិភាគថ្នាក់</span>
                </a>
            @endif
            <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'grades', 'exam_id' => $exam->id]) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-award"></i><span>លទ្ធផលរួម</span>
            </a>
        </div>
    </div>

    {{-- Scheduled Classes and Subjects --}}
    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white mb-3">
        <div class="card-header bg-white border-bottom border-light-subtle py-2 px-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #fef3c7; color: #b45309; font-size: 0.95rem;">
                    <i class="bi bi-diagram-3-fill"></i>
                </span>
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">
                    កាលវិភាគតាមថ្នាក់ និងវិញ្ញាសាប្រឡង
                </h6>
            </div>
            <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.75rem;">
                {{ $exam->exam_schedules->count() }} ថ្នាក់រៀន
            </span>
        </div>
        <div class="card-body p-3">
            @forelse($exam->exam_schedules as $schedule)
                <div class="border rounded-2 p-2.5 mb-2.5 bg-light-subtle">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 pb-2 mb-2 border-bottom border-light-subtle">
                        <div>
                            <span class="badge bg-dark text-white px-2 py-0.5 fw-semibold" style="font-size: 0.8rem;">
                                {{ $schedule->schoolClass?->name ?? 'ថ្នាក់ #' . $schedule->class_id }}
                                @if($schedule->section && ! str_contains($schedule->schoolClass?->name ?? '', $schedule->section->name))
                                    <span class="text-warning">({{ $schedule->section->name }})</span>
                                @endif
                            </span>
                            <span class="text-muted small ms-2" style="font-size: 0.78rem;">
                                {{ $schedule->exam_subjects->count() }} មុខវិជ្ជា
                            </span>
                        </div>
                        @if($canManageExams)
                        <div class="d-flex gap-1">
                            <a href="{{ route('exam-subjects.create', ['exam_schedule_id' => $schedule->id]) }}" class="btn btn-sm btn-outline-primary py-0.5 px-2" style="font-size: 0.78rem; border-radius: 5px;">
                                <i class="bi bi-plus-circle me-1"></i>បន្ថែមវិញ្ញាសា
                            </a>
                            <a href="{{ route('exam-schedules.edit', $schedule->id) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-2" style="font-size: 0.78rem; border-radius: 5px;">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                        @endif
                    </div>

                    @if($schedule->exam_subjects->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 bg-white rounded border" style="font-size: 0.82rem;">
                                <thead class="table-light text-muted small" style="font-size: 0.76rem;">
                                    <tr>
                                        <th class="ps-3 py-1.5">មុខវិជ្ជា</th>
                                        <th class="py-1.5">កាលបរិច្ឆេទប្រឡង</th>
                                        <th class="py-1.5">ពេលវេលា</th>
                                        <th class="py-1.5">ពិន្ទុអតិបរមា</th>
                                        <th class="py-1.5">ពិន្ទុជាប់</th>
                                        <th class="py-1.5">ពិន្ទុសិស្ស</th>
                                        <th class="text-end pe-3 py-1.5">សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedule->exam_subjects as $esub)
                                        <tr>
                                            <td class="ps-3 py-1.5 fw-semibold text-dark">
                                                {{ $esub->subject?->name ?? '—' }}
                                            </td>
                                            <td class="py-1.5 text-nowrap">{{ $esub->exam_date?->format('d/m/Y') ?? 'មិនទាន់កំណត់' }}</td>
                                            <td class="py-1.5 text-muted text-nowrap">
                                                @if($esub->start_time && $esub->end_time)
                                                    {{ $esub->start_time }} — {{ $esub->end_time }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="py-1.5"><strong>{{ number_format($esub->max_marks, 2) }}</strong></td>
                                            <td class="py-1.5"><span class="text-muted">{{ number_format($esub->pass_marks, 2) }}</span></td>
                                            <td class="py-1.5">
                                                <span class="badge {{ $esub->exam_results->count() > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }}" style="font-size: 0.72rem;">
                                                    {{ $esub->exam_results->count() > 0 ? 'បញ្ចូលបាន ' . $esub->exam_results->count() . ' នាក់' : 'មិនទាន់បញ្ចូល' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3 py-1.5">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('exam-subjects.scores', $esub->id) }}" class="btn btn-sm text-white py-0.5 px-2" style="background-color: var(--gold, #e8a838); font-size: 0.78rem; border-radius: 5px;">
                                                        <i class="bi bi-pencil-square me-1"></i>បញ្ចូលពិន្ទុ
                                                    </a>
                                                    @if($canManageExams)
                                                    <a href="{{ route('exam-subjects.edit', $esub->id) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-1.5" style="font-size: 0.78rem; border-radius: 5px;">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted small py-2 text-center" style="font-size: 0.8rem;">
                            មិនទាន់មានវិញ្ញាសាប្រឡងត្រូវបានបន្ថែមក្នុងកាលវិភាគថ្នាក់នេះនៅឡើយទេ។ សូមចុច <strong>"បន្ថែមវិញ្ញាសា"</strong> ខាងលើ។
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-4 text-muted small">
                    <i class="bi bi-calendar-x fs-3 d-block mb-1 opacity-50"></i>
                    មិនទាន់មានកាលវិភាគថ្នាក់ត្រូវបានកំណត់សម្រាប់ការប្រឡងនេះនៅឡើយទេ។
                    @if($canManageExams)
                    <div class="mt-2">
                        <a href="{{ route('exam-schedules.create', ['exam_id' => $exam->id]) }}" class="btn btn-sm text-white px-3" style="background-color: var(--gold, #e8a838); font-size: 0.8rem; border-radius: 6px;">
                            <i class="bi bi-calendar-plus me-1"></i> កំណត់កាលវិភាគថ្នាក់ដំបូង
                        </a>
                    </div>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
