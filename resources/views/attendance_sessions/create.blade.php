@extends('layouts.app')

@section('title', 'Create Attendance Session — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attendance-sessions.index') }}" class="text-decoration-none text-muted">Attendance Sessions</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Create Session</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.form-card { background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: var(--shadow-sm); }
.form-section-title { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--text-light); margin-bottom: .75rem; padding-bottom: .5rem; border-bottom: 1px solid var(--border-soft); }
.period-selector { display: grid; grid-template-columns: repeat(4, 1fr); gap: .5rem; }
.period-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: .6rem .5rem; border: 2px solid var(--border-soft); border-radius: var(--radius-md); background: #fafafa; cursor: pointer; transition: all .15s; font-size: .8rem; font-weight: 600; color: var(--text-muted); gap: .15rem; }
.period-btn:hover { border-color: #3b82f6; background: #eff6ff; color: #1d4ed8; }
.period-btn.selected { border-color: #3b82f6; background: #eff6ff; color: #1d4ed8; }
.period-btn .period-icon { font-size: 1rem; }
.assignment-select { transition: box-shadow .15s; }
.teacher-info-card { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-md); padding: .85rem 1rem; margin-top: .5rem; display: none; }
.teacher-info-card.show { display: block; }
.teacher-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .75rem; }
.info-label { font-size: .7rem; color: #4ade80; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
.info-val   { font-size: .88rem; font-weight: 600; color: #14532d; }
</style>
@endpush

@section('content')

<div class="page-header d-flex align-items-start justify-content-between mb-4">
    <div>
        <h1 class="page-title">Create Attendance Session</h1>
        <p class="page-subtitle">Set up a new attendance session for a class.</p>
    </div>
    <a href="{{ route('attendance-sessions.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-exclamation-triangle-fill text-danger mt-1"></i>
    <div>
        <strong>Please fix the following:</strong>
        <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('attendance-sessions.store') }}" method="POST" id="createSessionForm">
            @csrf

            {{-- SESSION DETAILS --}}
            <div class="form-card mb-4">
                <div class="form-section-title">Session Details</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select name="academic_year_id" id="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror" required>
                            <option value="">Select Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    {{ (old('academic_year_id', $defaultYear?->id) == $year->id) ? 'selected' : '' }}>
                                    {{ $year->name }}{{ $year->is_active ? ' (Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="attendance_date" class="form-label">Attendance Date <span class="text-danger">*</span></label>
                        <input type="date" name="attendance_date" id="attendance_date"
                               value="{{ old('attendance_date', date('Y-m-d')) }}"
                               class="form-control @error('attendance_date') is-invalid @enderror" required>
                        @error('attendance_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Period <span class="text-danger">*</span></label>
                    <div class="period-selector">
                        @php $selectedPeriod = old('period', 1); @endphp
                        @foreach([1 => ['icon'=>'bi-1-circle','lbl'=>'1st Period'],
                                   2 => ['icon'=>'bi-2-circle','lbl'=>'2nd Period'],
                                   3 => ['icon'=>'bi-3-circle','lbl'=>'3rd Period'],
                                   4 => ['icon'=>'bi-4-circle','lbl'=>'4th Period']] as $p => $info)
                        <label class="period-btn {{ $selectedPeriod == $p ? 'selected' : '' }}" for="period_{{ $p }}" id="period-label-{{ $p }}">
                            <i class="{{ $info['icon'] }} period-icon"></i>
                            <span>{{ $info['lbl'] }}</span>
                            <input type="radio" name="period" id="period_{{ $p }}" value="{{ $p }}"
                                   {{ $selectedPeriod == $p ? 'checked' : '' }}
                                   class="d-none" required>
                        </label>
                        @endforeach
                    </div>
                    @error('period')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- CLASS ASSIGNMENT --}}
            <div class="form-card mb-4">
                <div class="form-section-title">Class & Teacher Assignment</div>

                <div class="mb-3">
                    <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                    <select name="class_id" id="class_id"
                            class="form-select @error('class_id') is-invalid @enderror" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->grade?->name }}{{ $class->section?->name ? ' — '.$class->section->name : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="teacher_assignment_id" class="form-label">
                        Teaching Assignment
                        <span class="text-muted fw-normal" style="font-size:.78rem">(optional — links Subject & Teacher)</span>
                    </label>
                    <select name="teacher_assignment_id" id="teacher_assignment_id"
                            class="form-select assignment-select @error('teacher_assignment_id') is-invalid @enderror">
                        <option value="">— None / Unassigned —</option>
                        @foreach($teacherAssignments as $ta)
                            @php
                                $classObj = $ta->class;
                                $className = ($classObj?->grade?->name ?? '') . ($classObj?->section?->name ? ' '.$classObj->section->name : '');
                                $subjectName = $ta->class_subject?->subject?->name ?? 'គ្រូបន្ទុកថ្នាក់';
                                $teacherName = $ta->employee?->user?->name ?? 'Unknown';
                                $taLabel = "{$teacherName} — {$className} ({$subjectName})";
                            @endphp
                            <option value="{{ $ta->id }}"
                                data-class-id="{{ $ta->class_id ?? $ta->class_subject?->class_id }}"
                                data-subject="{{ $ta->class_subject?->subject?->name ?? 'គ្រូបន្ទុកថ្នាក់' }}"
                                data-teacher="{{ $ta->employee?->user?->name }}"
                                data-year="{{ $ta->academic_year?->name }}"
                                {{ old('teacher_assignment_id') == $ta->id ? 'selected' : '' }}>
                                {{ $taLabel }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_assignment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    {{-- Teacher Info Preview --}}
                    <div class="teacher-info-card" id="teacherInfoCard">
                        <div class="teacher-info-grid">
                            <div>
                                <div class="info-label">Subject</div>
                                <div class="info-val" id="infoSubject">—</div>
                            </div>
                            <div>
                                <div class="info-label">Teacher</div>
                                <div class="info-val" id="infoTeacher">—</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary-action" id="btn-submit-session">
                    <i class="bi bi-check-lg me-1"></i>Create Session & Take Attendance
                </button>
                <a href="{{ route('attendance-sessions.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="form-card">
            <div class="form-section-title">How It Works</div>
            <div class="d-flex flex-column gap-3" style="font-size:.85rem;color:var(--text-muted)">
                <div class="d-flex gap-2">
                    <span class="text-primary fw-bold">1</span>
                    <span>Select the <strong>Academic Year</strong> and <strong>Date</strong>.</span>
                </div>
                <div class="d-flex gap-2">
                    <span class="text-primary fw-bold">2</span>
                    <span>Choose which <strong>Period</strong> (1–4) this session is for.</span>
                </div>
                <div class="d-flex gap-2">
                    <span class="text-primary fw-bold">3</span>
                    <span>Select the <strong>Class</strong> and optionally the <strong>Teaching Assignment</strong> to auto-link the Subject and Teacher.</span>
                </div>
                <div class="d-flex gap-2">
                    <span class="text-primary fw-bold">4</span>
                    <span>Click <strong>Create</strong> — you'll be taken directly to the attendance entry form.</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Period button visual selection
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
        });
    });

    // Teaching Assignment → show info card + auto-select class
    const taSelect    = document.getElementById('teacher_assignment_id');
    const classSelect = document.getElementById('class_id');
    const infoCard    = document.getElementById('teacherInfoCard');
    const infoSubject = document.getElementById('infoSubject');
    const infoTeacher = document.getElementById('infoTeacher');

    function updateAssignmentInfo() {
        const selected = taSelect.options[taSelect.selectedIndex];
        const classId  = selected?.dataset?.classId;
        const subject  = selected?.dataset?.subject;
        const teacher  = selected?.dataset?.teacher;

        if (classId && subject) {
            infoCard.classList.add('show');
            infoSubject.textContent = subject || '—';
            infoTeacher.textContent = teacher || '—';

            // Auto-select matching class
            Array.from(classSelect.options).forEach(opt => {
                if (opt.value == classId) {
                    classSelect.value = opt.value;
                }
            });
        } else {
            infoCard.classList.remove('show');
        }
    }

    taSelect.addEventListener('change', updateAssignmentInfo);

    // On class change: filter the assignment dropdown to show only relevant options
    classSelect.addEventListener('change', function () {
        const cid = this.value;
        Array.from(taSelect.options).forEach(opt => {
            if (!opt.value) return;
            opt.style.display = (!cid || opt.dataset.classId == cid) ? '' : 'none';
        });
        // If current selection no longer belongs to this class, reset
        const sel = taSelect.options[taSelect.selectedIndex];
        if (sel?.value && sel.dataset.classId != cid) {
            taSelect.value = '';
            infoCard.classList.remove('show');
        }
    });

    // Init
    updateAssignmentInfo();
});
</script>
@endpush