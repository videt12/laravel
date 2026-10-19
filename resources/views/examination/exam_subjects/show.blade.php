@extends('layouts.app')

@section('title', 'ព័ត៌មានលម្អិតវិញ្ញាសាប្រឡង')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'subjects']) }}" class="text-decoration-none text-muted">វិញ្ញាសាប្រឡង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">ព័ត៌មានលម្អិត</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2.5 mb-3 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">{{ $examSubject->subject?->name ?? 'វិញ្ញាសាប្រឡង' }}</h2>
                <span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5" style="font-size: 0.75rem;">{{ $examSubject->subject?->code ?? 'SUB' }}</span>
            </div>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem;">
                ការប្រឡង៖ <a href="{{ route('exams.show', $examSubject->exam_schedule?->exam_id) }}" class="fw-semibold text-dark text-decoration-none">{{ $examSubject->exam_schedule?->exam?->name ?? '—' }}</a> &bull;
                ថ្នាក់៖ <strong class="text-dark">{{ $examSubject->exam_schedule?->schoolClass?->name ?? '—' }}</strong> &bull;
                ពិន្ទុអតិបរមា៖ <strong class="text-dark">{{ number_format($examSubject->max_marks, 2) }}</strong> &bull;
                ពិន្ទុជាប់៖ <strong class="text-muted">{{ number_format($examSubject->pass_marks, 2) }}</strong>
            </p>
        </div>

        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <a href="{{ route('examinations.index', ['tab' => 'subjects']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
            </a>
            <a href="{{ route('exam-subjects.scores', $examSubject->id) }}" class="btn btn-sm text-white d-inline-flex align-items-center gap-1 shadow-sm" style="height: 32px; font-size: 0.82rem; font-weight: 600; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px;">
                <i class="bi bi-pencil-square"></i><span>បញ្ចូល / កែពិន្ទុ</span>
            </a>
            <a href="{{ route('exam-subjects.edit', $examSubject->id) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-pencil"></i><span>កែសម្រួល</span>
            </a>
        </div>
    </div>

    {{-- Recorded Scores Table --}}
    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white mb-3">
        <div class="card-header bg-white border-bottom border-light-subtle py-2 px-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #fef3c7; color: #b45309; font-size: 0.95rem;">
                    <i class="bi bi-award-fill"></i>
                </span>
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">
                    ពិន្ទុសិស្សដែលបានបញ្ចូល
                </h6>
            </div>
            <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.75rem;">
                បានបញ្ចូល {{ $examSubject->exam_results->count() }} នាក់
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                    <thead class="table-light text-muted small" style="font-size: 0.76rem;">
                        <tr>
                            <th class="ps-3 py-2">សិស្ស</th>
                            <th class="py-2">អត្តលេខ</th>
                            <th class="py-2">ពិន្ទុទទួលបាន</th>
                            <th class="py-2">ភាគរយ</th>
                            <th class="py-2">និទ្ទេស</th>
                            <th class="py-2">លទ្ធផល</th>
                            <th class="pe-3 py-2">ចំណាំ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examSubject->exam_results as $res)
                            @php
                                $pct = $examSubject->max_marks > 0 ? round(($res->marks_obtained / $examSubject->max_marks) * 100, 1) : 0;
                                $passed = $res->marks_obtained >= $examSubject->pass_marks;
                            @endphp
                            <tr>
                                <td class="ps-3 py-2 fw-semibold text-dark">{{ $res->student?->user?->name ?? '—' }}</td>
                                <td class="py-2"><span class="font-monospace text-muted">{{ $res->student?->admission_number ?? '—' }}</span></td>
                                <td class="py-2">
                                    <strong>{{ number_format($res->marks_obtained, 2) }}</strong>
                                    <span class="text-muted small">/ {{ number_format($examSubject->max_marks, 2) }}</span>
                                </td>
                                <td class="py-2">{{ $pct }}%</td>
                                <td class="py-2">
                                    <span class="badge {{ in_array($res->grade, ['A+', 'A', 'B+']) ? 'bg-success' : (in_array($res->grade, ['B', 'C+']) ? 'bg-primary' : 'bg-warning text-dark') }}" style="font-size: 0.72rem;">
                                        {{ $res->grade ?? '—' }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <span class="badge {{ $passed ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}" style="font-size: 0.72rem;">
                                        {{ $passed ? 'ជាប់' : 'ធ្លាក់' }}
                                    </span>
                                </td>
                                <td class="pe-3 py-2 text-muted small">{{ $res->remarks ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted small">
                                    <i class="bi bi-award fs-4 d-block mb-1 opacity-50"></i>
                                    មិនទាន់មានពិន្ទុត្រូវបានបញ្ចូលសម្រាប់វិញ្ញាសានេះនៅឡើយទេ។
                                    <div class="mt-2">
                                        <a href="{{ route('exam-subjects.scores', $examSubject->id) }}" class="btn btn-sm text-white px-3" style="background-color: var(--gold, #e8a838); font-size: 0.8rem; border-radius: 6px;">
                                            <i class="bi bi-pencil-square me-1"></i> បញ្ចូលពិន្ទុឥឡូវនេះ
                                        </a>
                                    </div>
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
