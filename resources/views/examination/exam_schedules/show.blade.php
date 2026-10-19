@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតកាលវិភាគប្រឡង')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'schedules']) }}" class="text-decoration-none text-muted">កាលវិភាគប្រឡង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">ព័ត៌មានលម្អិត</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0">
    {{-- Header Banner --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2.5 mb-3 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">
                    {{ $examSchedule->schoolClass?->name ?? 'ថ្នាក់ #' . $examSchedule->class_id }}
                    @if($examSchedule->section && ! str_contains($examSchedule->schoolClass?->name ?? '', $examSchedule->section->name))
                        <span class="text-warning">({{ $examSchedule->section->name }})</span>
                    @endif
                </h2>
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5 fw-semibold" style="font-size: 0.75rem;">
                    កាលវិភាគ #{{ $examSchedule->id }}
                </span>
            </div>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem;">
                ការប្រឡង៖ <a href="{{ route('exams.show', $examSchedule->exam_id) }}" class="fw-semibold text-dark text-decoration-none">{{ $examSchedule->exam?->name ?? '—' }}</a> &bull;
                ឆ្នាំសិក្សា៖ <strong class="text-dark">{{ $examSchedule->exam?->academic_year?->name ?? '—' }}</strong>
            </p>
        </div>

        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <a href="{{ route('examinations.index', ['tab' => 'schedules']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
            </a>
            <a href="{{ route('exam-subjects.create', ['exam_schedule_id' => $examSchedule->id]) }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1 shadow-sm" style="height: 32px; font-size: 0.82rem; font-weight: 600; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px;">
                <i class="bi bi-plus-circle"></i><span>បន្ថែមវិញ្ញាសា</span>
            </a>
            <a href="{{ route('exam-schedules.edit', $examSchedule->id) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-pencil"></i><span>កែសម្រួល</span>
            </a>
        </div>
    </div>

    {{-- Subject Papers Table --}}
    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white mb-3">
        <div class="card-header bg-white border-bottom border-light-subtle py-2 px-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #fef3c7; color: #b45309; font-size: 0.95rem;">
                    <i class="bi bi-journal-text"></i>
                </span>
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">
                    វិញ្ញាសាប្រឡងក្នុងកាលវិភាគនេះ
                </h6>
            </div>
            <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.75rem;">
                {{ $examSchedule->exam_subjects->count() }} មុខវិជ្ជា
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                    <thead class="table-light text-muted small" style="font-size: 0.76rem;">
                        <tr>
                            <th class="ps-3 py-2">មុខវិជ្ជា</th>
                            <th class="py-2">កាលបរិច្ឆេទប្រឡង</th>
                            <th class="py-2">ពេលវេលា</th>
                            <th class="py-2">ពិន្ទុអតិបរមា</th>
                            <th class="py-2">ពិន្ទុជាប់</th>
                            <th class="py-2">ពិន្ទុសិស្ស</th>
                            <th class="text-end pe-3 py-2">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examSchedule->exam_subjects as $esub)
                            <tr>
                                <td class="ps-3 py-2 fw-semibold text-dark">{{ $esub->subject?->name ?? '—' }}</td>
                                <td class="py-2 text-nowrap">{{ $esub->exam_date?->format('d/m/Y') ?? 'មិនទាន់កំណត់' }}</td>
                                <td class="py-2 text-nowrap">
                                    @if($esub->start_time && $esub->end_time)
                                        {{ $esub->start_time }} — {{ $esub->end_time }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="py-2"><strong>{{ number_format($esub->max_marks, 2) }}</strong></td>
                                <td class="py-2"><span class="text-muted">{{ number_format($esub->pass_marks, 2) }}</span></td>
                                <td class="py-2">
                                    @if($esub->exam_results->count() > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                            <i class="bi bi-check2 me-1"></i>បញ្ចូលបាន {{ $esub->exam_results->count() }} នាក់
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-0.5" style="font-size: 0.72rem;">
                                            មិនទាន់បញ្ចូល
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-3 py-2">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('exam-subjects.scores', $esub->id) }}" class="btn btn-sm text-white py-0.5 px-2 d-inline-flex align-items-center gap-1 shadow-xs" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); font-size: 0.78rem; border-radius: 5px;">
                                            <i class="bi bi-pencil-square"></i><span>បញ្ចូលពិន្ទុ</span>
                                        </a>
                                        <a href="{{ route('exam-subjects.edit', $esub->id) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-1.5" style="font-size: 0.78rem; border-radius: 5px;" title="កែសម្រួលវិញ្ញាសា">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted small">
                                    <i class="bi bi-journal-x fs-4 d-block mb-1 opacity-50"></i>
                                    មិនទាន់មានវិញ្ញាសាប្រឡងត្រូវបានបន្ថែមក្នុងកាលវិភាគនេះនៅឡើយទេ។
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
