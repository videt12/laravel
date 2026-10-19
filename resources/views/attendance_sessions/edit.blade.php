@extends('layouts.app')

@section('title', 'Edit Attendance Session — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student Services</li>
        <li class="breadcrumb-item"><a href="{{ route('attendance-sessions.index') }}" class="text-decoration-none text-muted">Attendance Sessions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attendance-sessions.show', $session->id) }}" class="text-decoration-none text-muted">Session Details</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Session</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.as-page-banner {
    background: linear-gradient(135deg, #1e293b 0%, #334155 60%, #475569 100%);
    border-radius: var(--radius-lg); padding: 1.5rem 1.75rem; margin-bottom: 1.75rem;
    position: relative; overflow: hidden;
}
.as-page-banner::before {
    content: ''; position: absolute; top: -40px; right: -40px;
    width: 140px; height: 140px;
    background: rgba(232,168,56,.12); border-radius: 50%;
}
.as-page-banner h1 { font-size: 1.2rem; font-weight: 700; color: #fff; margin: 0 0 .2rem; }
.as-page-banner p  { font-size: .82rem; color: rgba(255,255,255,.6); margin: 0; }
.as-page-banner .banner-icon {
    width: 42px; height: 42px; background: rgba(232,168,56,.2);
    border-radius: var(--radius-md); display: flex; align-items: center;
    justify-content: center; font-size: 1.1rem; color: var(--gold); margin-bottom: .8rem;
}
.as-page-banner .banner-actions { position: absolute; top: 1.2rem; right: 1.25rem; z-index: 2; }
.as-form-card {
    background: #fff; border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg); overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 12px rgba(0,0,0,.04);
}
.form-section-head { display: flex; align-items: center; gap: .7rem; margin-bottom: 1.1rem; }
.form-section-head-bar { width: 3px; height: 1rem; border-radius: 999px; background: var(--gold); flex-shrink: 0; }
.form-section-head-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--text-muted); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }
.form-section { padding: 1.35rem 1.5rem; border-bottom: 1px solid var(--border-soft); }
.form-section:last-child { border-bottom: none; }
.field-hint { display: flex; align-items: center; gap: .3rem; font-size: .73rem; color: var(--text-muted); margin-top: .3rem; }
.form-select.fc, .form-control.fc {
    border-color: #e2e8f0; font-size: .875rem; border-radius: var(--radius-sm);
    color: var(--text-dark); background-color: #fff; transition: border-color .15s, box-shadow .15s; padding: .48rem .85rem;
}
.form-select.fc:focus, .form-control.fc:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(232,168,56,.12); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-soft);
}
.required-mark { color: #ef4444; font-weight: 700; }
</style>
@endpush

@section('content')

<div class="as-page-banner">
    <div class="banner-icon"><i class="bi bi-pencil-square"></i></div>
    <h1>Edit Attendance Session</h1>
    <p>Update session details. Changes will not affect existing attendance records.</p>
    <div class="banner-actions">
        <a href="{{ route('attendance-sessions.show', $session->id) }}" class="btn btn-sm btn-outline-light px-3">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

@if($hasAttendance)
<div class="alert d-flex align-items-start gap-3 border-warning border mb-4 py-3 px-4" style="background:#fffbeb;border-radius:var(--radius-md)" role="alert">
    <i class="bi bi-exclamation-triangle-fill text-warning mt-1" style="font-size:1rem;flex-shrink:0"></i>
    <div>
        <div class="fw-semibold text-dark mb-1">Attendance Records Exist</div>
        <div class="text-muted small">Attendance records already exist for this session. Changing the class or date may affect how records are grouped, but existing attendance records will not be deleted.</div>
    </div>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('attendance-sessions.update', $session->id) }}" method="POST" id="editSessionForm">
    @csrf @method('PUT')

    <div class="as-form-card">
        <div class="form-section">
            <div class="form-section-head">
                <div class="form-section-head-bar"></div>
                <span class="form-section-head-label">Session Information</span>
                <div class="form-section-head-line"></div>
            </div>

            <div class="row g-3">

                <div class="col-md-12">
                    <label class="form-label fw-medium" for="academic_year_id">
                        Academic Year <span class="required-mark">*</span>
                    </label>
                    <select name="academic_year_id" id="academic_year_id"
                            class="form-select fc {{ $errors->has('academic_year_id') ? 'is-invalid' : '' }}"
                            required>
                        <option value="">— Select Academic Year —</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}"
                                {{ old('academic_year_id', $session->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}{{ $year->is_active ? ' (Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-medium" for="class_id">
                        Class <span class="required-mark">*</span>
                    </label>
                    <select name="class_id" id="class_id"
                            class="form-select fc {{ $errors->has('class_id') ? 'is-invalid' : '' }}"
                            required onchange="updateSectionFromClass(this)">
                        <option value="">— Select Class —</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                    data-section-id="{{ $class->section_id }}"
                                    data-section-name="{{ $class->section?->name }}"
                                {{ old('class_id', $session->class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->grade?->name }}{{ $class->section?->name ? ' — '.$class->section->name : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium" for="section_display">Section</label>
                    <input type="text" id="section_display" class="form-control fc bg-light" readonly
                           value="{{ $session->section?->name ?? $session->schoolClass?->section?->name ?? '' }}">
                    <input type="hidden" name="section_id" id="section_id" value="{{ old('section_id', $session->section_id) }}">
                    <div class="field-hint"><i class="bi bi-info-circle"></i> Derived from selected class.</div>
                </div>

                {{-- Teaching Assignment --}}
                <div class="col-md-12">
                    <label class="form-label fw-medium" for="teacher_assignment_id">
                        Teaching Assignment
                        <span class="text-muted fw-normal" style="font-size:.78rem">(optional — links Subject &amp; Teacher to this session)</span>
                    </label>
                    <select name="teacher_assignment_id" id="teacher_assignment_id"
                            class="form-select fc {{ $errors->has('teacher_assignment_id') ? 'is-invalid' : '' }}">
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
                                {{ old('teacher_assignment_id', $session->teacher_assignment_id) == $ta->id ? 'selected' : '' }}>
                                {{ $taLabel }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_assignment_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-2 p-2 rounded" id="editTeacherInfo"
                         style="background:#f0fdf4;border:1px solid #bbf7d0;display:none;font-size:.83rem">
                        <span class="text-muted me-2">Subject:</span>
                        <strong id="editInfoSubject" class="text-success">—</strong>
                        <span class="text-muted ms-3 me-2">Teacher:</span>
                        <strong id="editInfoTeacher" class="text-success">—</strong>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium" for="attendance_date">
                        Attendance Date <span class="required-mark">*</span>
                    </label>
                    <input type="date" name="attendance_date" id="attendance_date"
                           class="form-control fc {{ $errors->has('attendance_date') ? 'is-invalid' : '' }}"
                           value="{{ old('attendance_date', $session->attendance_date?->format('Y-m-d')) }}"
                           required>
                    @error('attendance_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium" for="period">
                        Period <span class="required-mark">*</span>
                    </label>
                    <select name="period" id="period"
                            class="form-select fc {{ $errors->has('period') ? 'is-invalid' : '' }}"
                            required>
                        <option value="">— Select Period —</option>
                        @foreach([1,2,3,4] as $p)
                            <option value="{{ $p }}" {{ old('period', $session->period) == $p ? 'selected' : '' }}>Period {{ $p }}</option>
                        @endforeach
                    </select>
                    @error('period')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-medium" for="recorded_by">Recorded By</label>
                    <select name="recorded_by" id="recorded_by"
                            class="form-select fc {{ $errors->has('recorded_by') ? 'is-invalid' : '' }}">
                        <option value="">— Select User —</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('recorded_by', $session->recorded_by) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('recorded_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-medium" for="status">
                        Status <span class="required-mark">*</span>
                    </label>
                    <select name="status" id="status"
                            class="form-select fc {{ $errors->has('status') ? 'is-invalid' : '' }}"
                            required>
                        <option value="open"   {{ old('status', $session->status) === 'open'   ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status', $session->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="field-hint"><i class="bi bi-toggle-on"></i> Open = active session, Closed = completed.</div>
                </div>

            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('attendance-sessions.show', $session->id) }}" class="btn btn-outline-secondary px-4">
                Cancel
            </a>
            <button type="submit" class="btn btn-warning px-4 fw-medium" id="btn-update-submit">
                <i class="bi bi-check2 me-2"></i>Save Changes
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function updateSectionFromClass(selectEl) {
    const opt = selectEl.options[selectEl.selectedIndex];
    document.getElementById('section_id').value      = opt.dataset.sectionId   || '';
    document.getElementById('section_display').value = opt.dataset.sectionName || '—';
}

document.addEventListener('DOMContentLoaded', function () {
    const classSelect = document.getElementById('class_id');
    if (classSelect && classSelect.value) {
        if (!document.getElementById('section_display').value) {
            updateSectionFromClass(classSelect);
        }
    }

    // Teaching assignment preview
    const taSelect     = document.getElementById('teacher_assignment_id');
    const infoBox      = document.getElementById('editTeacherInfo');
    const infoSubject  = document.getElementById('editInfoSubject');
    const infoTeacher  = document.getElementById('editInfoTeacher');

    function refreshAssignmentInfo() {
        const sel = taSelect.options[taSelect.selectedIndex];
        if (sel?.value && sel.dataset.subject) {
            infoBox.style.display = 'block';
            infoSubject.textContent = sel.dataset.subject || '—';
            infoTeacher.textContent = sel.dataset.teacher || '—';
        } else {
            infoBox.style.display = 'none';
        }
    }

    taSelect.addEventListener('change', refreshAssignmentInfo);
    refreshAssignmentInfo();
});
</script>
@endpush