@extends('layouts.app')

@section('title', 'បញ្ចូលពិន្ទុប្រឡង')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងព័ត៌មាន</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index') }}" class="text-decoration-none text-muted">ការប្រឡង</a></li>
        <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'subjects']) }}" class="text-decoration-none text-muted">វិញ្ញាសាប្រឡង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium">បញ្ចូលពិន្ទុ</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2.5 mb-3 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h2 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">តារាងបញ្ចូលពិន្ទុ</h2>
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5 fw-semibold" style="font-size: 0.75rem;">
                    {{ $examSubject->subject?->name ?? 'វិញ្ញាសា' }} ({{ $examSubject->subject?->code ?? 'SUB' }})
                </span>
            </div>
            <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem;">
                ការប្រឡង៖ <strong class="text-dark">{{ $examSubject->examSchedule?->exam?->name ?? '—' }}</strong> &bull;
                ថ្នាក់៖ <strong class="text-dark">{{ $examSubject->examSchedule?->schoolClass?->name ?? '—' }}</strong>
                @if($examSubject->examSchedule?->section)
                    <span class="text-warning">({{ $examSubject->examSchedule->section->name }})</span>
                @endif
                &bull; ពិន្ទុពេញ៖ <strong class="text-dark">{{ number_format($examSubject->max_marks, 2) }}</strong>
                &bull; ពិន្ទុជាប់៖ <strong class="text-muted">{{ number_format($examSubject->pass_marks, 2) }}</strong>
            </p>
        </div>

        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            @php
                $user = auth()->user();
                $isTeacher = $user && method_exists($user, 'hasRole') && ($user->hasRole('teacher') || $user->hasRole('Teacher'));
                $canManageExams = $user && ($user->isSuperAdmin() || $user->hasRole('admin') || (! $isTeacher && $user->canAccess('exams.create')));
            @endphp
            @if($canManageExams)
                <a href="{{ route('exam-subjects.show', $examSubject) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                    <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
                </a>
            @else
                <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'results']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                    <i class="bi bi-arrow-left"></i><span>ត្រឡប់ក្រោយ</span>
                </a>
            @endif
            <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'results']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="height: 32px; font-size: 0.8rem; border-radius: 6px;">
                <i class="bi bi-list-check"></i><span>បញ្ជីលទ្ធផល</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3 small d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-6"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3 small d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3 small" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon me-1"></i>សូមពិនិត្យ និងកែតម្រូវកំហុសខាងក្រោម៖</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close py-2.5" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Score Entry Form --}}
    <div class="card border border-light-subtle shadow-xs rounded-3 bg-white mb-3">
        <div class="card-header bg-white border-bottom border-light-subtle py-2 px-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #fef3c7; color: #b45309; font-size: 0.95rem;">
                    <i class="bi bi-pencil-square"></i>
                </span>
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">
                    តារាងពិន្ទុសិស្សក្នុងថ្នាក់ ({{ count($students) }} នាក់)
                </h6>
            </div>
            <span class="text-muted small" style="font-size: 0.76rem;">
                <i class="bi bi-info-circle me-1"></i>ពិន្ទុដែលបញ្ចូលនឹងគណនានិទ្ទេសដោយស្វ័យប្រវត្តិ
            </span>
        </div>
        <div class="card-body p-0">
            @if(count($students) > 0)
                <form action="{{ route('exam-subjects.scores.store', $examSubject) }}" method="POST" id="scoreEntryForm">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.82rem;">
                            <thead class="table-light text-muted small" style="font-size: 0.76rem;">
                                <tr>
                                    <th class="ps-3 py-2" style="width: 45px;">#</th>
                                    <th class="py-2">សិស្ស</th>
                                    <th class="py-2">អត្តលេខ</th>
                                    <th class="py-2">លេខរៀង</th>
                                    <th class="py-2" style="width: 120px;">ពិន្ទុពេញ</th>
                                    <th class="py-2" style="width: 150px;">ពិន្ទុទទួលបាន</th>
                                    <th class="py-2" style="width: 90px;">និទ្ទេស</th>
                                    <th class="pe-3 py-2">ចំណាំ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    @php
                                        $result = $existingResults->get($student->id);
                                        $marksVal = old("scores.{$student->id}.marks_obtained", $result ? $result->marks_obtained : '');
                                        $remarksVal = old("scores.{$student->id}.remarks", $result ? $result->remarks : '');
                                    @endphp
                                    <tr>
                                        <td class="ps-3 py-1.5 text-muted small">{{ $index + 1 }}</td>
                                        <td class="py-1.5">
                                            <div class="fw-semibold text-dark">{{ $student->first_name }} {{ $student->last_name }}</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">ID: #{{ $student->id }}</div>
                                        </td>
                                        <td class="py-1.5">
                                            <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.75rem;">{{ $student->admission_number ?? '—' }}</span>
                                        </td>
                                        <td class="py-1.5 text-muted small">
                                            {{ $student->roll_number ?? '—' }}
                                        </td>
                                        <td class="py-1.5">
                                            <span class="fw-bold text-dark">{{ number_format($examSubject->max_marks, 2) }}</span>
                                        </td>
                                        <td class="py-1.5">
                                            <input type="number" 
                                                   step="0.01" 
                                                   min="0" 
                                                   max="{{ $examSubject->max_marks }}" 
                                                   name="scores[{{ $student->id }}][marks_obtained]" 
                                                   value="{{ $marksVal }}" 
                                                   placeholder="0.00" 
                                                   class="form-control form-control-sm text-end score-input @error("scores.{$student->id}.marks_obtained") is-invalid @enderror"
                                                   style="height: 32px; font-size: 0.82rem;"
                                                   data-max="{{ $examSubject->max_marks }}"
                                                   data-pass="{{ $examSubject->pass_marks }}"
                                            >
                                            @error("scores.{$student->id}.marks_obtained")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td class="py-1.5">
                                            @if($result && $result->grade)
                                                <span class="badge bg-{{ in_array($result->grade, ['A+', 'A', 'B+', 'B']) ? 'success' : (in_array($result->grade, ['C+', 'C', 'D']) ? 'warning' : 'danger') }} px-2 py-0.5" style="font-size: 0.72rem;">
                                                    {{ $result->grade }}
                                                </span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="pe-3 py-1.5">
                                            <input type="text" 
                                                   name="scores[{{ $student->id }}][remarks]" 
                                                   value="{{ $remarksVal }}" 
                                                   placeholder="ចំណាំបន្ថែម (បើមាន)" 
                                                   maxlength="500" 
                                                   class="form-control form-control-sm"
                                                   style="height: 32px; font-size: 0.82rem;"
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 bg-light border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                        <div class="text-muted small" style="font-size: 0.78rem;">
                            <i class="bi bi-shield-check text-success me-1"></i>មានតែគ្រូបង្រៀន និងអ្នកគ្រប់គ្រងប៉ុណ្ណោះដែលអាចបញ្ចូល ឬរក្សាទុកពិន្ទុបាន។
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($canManageExams ?? false)
                                <a href="{{ route('exam-subjects.show', $examSubject) }}" class="btn btn-sm btn-outline-secondary" style="height: 32px; font-size: 0.82rem; border-radius: 6px;">
                                    បោះបង់
                                </a>
                            @else
                                <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'results']) }}" class="btn btn-sm btn-outline-secondary" style="height: 32px; font-size: 0.82rem; border-radius: 6px;">
                                    បោះបង់
                                </a>
                            @endif
                            <button type="submit" class="btn btn-sm text-white fw-semibold px-3 d-inline-flex align-items-center gap-1 shadow-sm" style="height: 32px; font-size: 0.82rem; background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px;">
                                <i class="bi bi-save"></i><span>រក្សាទុកពិន្ទុទាំងអស់</span>
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-4 text-muted small">
                    <i class="bi bi-people text-muted fs-3 mb-1 d-block opacity-50"></i>
                    <h6 class="text-muted fw-bold mb-1" style="font-size: 0.88rem;">មិនមានសិស្សក្នុងថ្នាក់នេះទេ</h6>
                    <p class="text-muted small mb-0">មិនទាន់មានសិស្សសកម្មត្រូវបានចាត់ចូលក្នុងថ្នាក់ ឬក្រុមនេះនៅឡើយទេ។</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
