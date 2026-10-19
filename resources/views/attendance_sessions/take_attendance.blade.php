@extends('layouts.app')

@section('title', 'Take Attendance — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Student Services</li>
        <li class="breadcrumb-item"><a href="{{ route('attendance-sessions.index') }}" class="text-decoration-none text-muted">Attendance Sessions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('attendance-sessions.show', $session->id) }}" class="text-decoration-none text-muted">Session Details</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Take Attendance</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ── Take Attendance Page ── */
.ta-session-info {
    background: #fff; border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg); padding: 1.1rem 1.5rem;
    margin-bottom: 1.25rem; box-shadow: var(--shadow-xs);
    display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
}
.ta-session-info .info-piece { display: flex; flex-direction: column; gap: .05rem; }
.ta-session-info .info-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-light); }
.ta-session-info .info-value { font-size: .9rem; font-weight: 600; color: var(--text-dark); }
.ta-sep { width: 1px; height: 36px; background: var(--border); }

/* ── Status Radio Buttons ── */
.status-radios { display: flex; gap: 0; }
.status-radios input[type="radio"] { display: none; }
.status-radios label {
    cursor: pointer;
    border: 1px solid #e2e8f0;
    border-right: none;
    padding: .3rem .6rem;
    font-size: .75rem;
    font-weight: 600;
    color: var(--text-muted);
    background: #fff;
    transition: all .12s;
    white-space: nowrap;
    user-select: none;
}
.status-radios label:first-of-type  { border-radius: var(--radius-sm) 0 0 var(--radius-sm); }
.status-radios label:last-of-type   { border-radius: 0 var(--radius-sm) var(--radius-sm) 0; border-right: 1px solid #e2e8f0; }
.status-radios label:hover          { background: #f8fafc; }

/* Checked states */
.status-radios input[value="1"]:checked + label { background: #dcfce7; border-color: #22c55e; color: #15803d; }
.status-radios input[value="2"]:checked + label { background: #fee2e2; border-color: #ef4444; color: #dc2626; }
.status-radios input[value="3"]:checked + label { background: #fef3c7; border-color: #f59e0b; color: #d97706; }
.status-radios input[value="4"]:checked + label,
.status-radios input[value="5"]:checked + label,
.status-radios input[value="6"]:checked + label,
.status-radios input[value="7"]:checked + label,
.status-radios input[value="8"]:checked + label { background: #ede9fe; border-color: #8b5cf6; color: #7c3aed; }

/* ── Remarks Input ── */
.remarks-input {
    border: 1px solid #e2e8f0; border-radius: var(--radius-sm);
    padding: .28rem .6rem; font-size: .8rem; color: var(--text-dark);
    background: #fff; width: 100%; max-width: 220px;
    transition: border-color .12s;
}
.remarks-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.1); outline: none; }
.remarks-input::placeholder { color: #94a3b8; }

/* ── Toolbar ── */
.ta-toolbar {
    background: #fff; border: 1px solid var(--border-soft);
    border-radius: var(--radius-md); padding: .65rem 1rem;
    margin-bottom: 1rem; display: flex; align-items: center;
    justify-content: space-between; gap: .75rem; flex-wrap: wrap;
    box-shadow: var(--shadow-xs);
}

/* ── Row Highlight ── */
tr.ta-row td { padding-top: .65rem; padding-bottom: .65rem; vertical-align: middle; }
tr.ta-row:hover { background: #f8fafc; }
tr.ta-row-absent td { background: #fff5f5; }
tr.ta-row-late   td { background: #fffbeb; }

/* ── Save Bar ── */
.ta-save-bar {
    position: sticky; bottom: 0; z-index: 100;
    background: #fff; border-top: 1px solid var(--border);
    padding: .85rem 1.5rem;
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    box-shadow: 0 -2px 8px rgba(0,0,0,.06);
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
}
</style>
@endpush

@section('content')

@php
    $classLabel  = $session->schoolClass?->grade?->name ?? '—';
    $sectionName = $session->schoolClass?->section?->name ?? $session->section?->name ?? '';
    if ($sectionName) $classLabel .= ' — ' . $sectionName;

    // Find Present status ID (code='P')
    $presentStatusId = $statuses->firstWhere('code', 'P')?->id ?? $statuses->first()?->id ?? 1;
@endphp

{{-- SESSION INFO BAR --}}
<div class="ta-session-info">
    <div class="info-piece">
        <div class="info-label">Class</div>
        <div class="info-value">{{ $classLabel }}</div>
    </div>
    <div class="ta-sep"></div>
    <div class="info-piece">
        <div class="info-label">Period</div>
        <div class="info-value">{{ $session->display_period }}</div>
    </div>
    @if($session->teacherAssignment?->class_subject?->subject?->name)
    <div class="ta-sep"></div>
    <div class="info-piece">
        <div class="info-label">Subject</div>
        <div class="info-value">{{ $session->teacherAssignment->class_subject->subject->name }}</div>
    </div>
    @endif
    @if($session->teacherAssignment?->employee?->user?->name)
    <div class="ta-sep"></div>
    <div class="info-piece">
        <div class="info-label">Teacher</div>
        <div class="info-value">{{ $session->teacherAssignment->employee->user->name }}</div>
    </div>
    @endif
    <div class="ta-sep"></div>
    <div class="info-piece">
        <div class="info-label">Date</div>
        <div class="info-value">{{ $session->attendance_date?->format('d M Y') ?? '—' }}</div>
    </div>
    <div class="ta-sep"></div>
    <div class="info-piece">
        <div class="info-label">Academic Year</div>
        <div class="info-value">{{ $session->academic_year?->name ?? '—' }}</div>
    </div>
    <div class="ms-auto">
        @if($session->status === 'open')
            <span class="badge bg-success-subtle text-success border border-success-subtle me-2" style="font-size:.72rem">
                <i class="bi bi-circle-fill me-1" style="font-size:.45rem"></i>Open
            </span>
        @else
            <span class="badge bg-secondary-subtle text-secondary border me-2" style="font-size:.72rem">
                <i class="bi bi-lock me-1" style="font-size:.72rem"></i>Closed
            </span>
        @endif
        <a href="{{ route('attendance-sessions.show', $session->id) }}" class="btn btn-sm btn-outline-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 border-0 shadow-sm mb-4 py-2 px-3" role="alert" style="border-radius:var(--radius-md);font-size:.875rem">
    <i class="bi bi-check-circle-fill text-success"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

@if($students->isEmpty())
<div class="data-card">
    <div class="empty-state" id="empty-students">
        <div class="empty-icon"><i class="bi bi-people"></i></div>
        <p class="empty-title">No Students Found</p>
        <p class="empty-subtitle">There are no students enrolled in this class ({{ $classLabel }}).</p>
    </div>
</div>
@else

<form method="POST" action="{{ route('attendance-sessions.save-attendance', $session->id) }}" id="attendanceForm">
    @csrf

    {{-- TOOLBAR --}}
    <div class="ta-toolbar">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold text-dark" style="font-size:.9rem">
                <i class="bi bi-people me-1 text-muted"></i>
                {{ $students->count() }} students
            </span>
            @if($existing->isNotEmpty())
                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25" style="font-size:.72rem">
                    {{ $existing->count() }} previously recorded
                </span>
            @endif
        </div>
        <div class="d-flex gap-2 align-items-center">
            <button type="button" class="btn btn-sm btn-outline-success px-3" id="btn-mark-all-present" onclick="markAllPresent()">
                <i class="bi bi-check2-all me-1"></i>Mark All Present
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary px-3" id="btn-reset-all" onclick="resetAll()">
                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
            </button>
        </div>
    </div>

    {{-- STUDENT TABLE --}}
    <div class="data-card" style="border-radius:var(--radius-lg) var(--radius-lg) 0 0">
        <div class="data-card-header">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold text-dark">Take Attendance</span>
                <span class="count-badge">{{ $students->count() }}</span>
            </div>
            <small class="text-muted">{{ $classLabel }} — {{ $session->display_period }} — {{ $session->attendance_date?->format('d M Y') }}</small>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="attendance-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40px">#</th>
                        <th>Student Name</th>
                        <th>Attendance Status</th>
                        <th style="width:220px">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php
                        $existingRecord    = $existing[$student->id] ?? null;
                        $currentStatusId   = $existingRecord?->attendance_status_id ?? $presentStatusId;
                        $currentRemarks    = $existingRecord?->remarks ?? '';
                        $studentName       = $student->user?->name ?? ('Student #' . $student->id);
                    @endphp
                    <tr class="ta-row" id="ta-row-{{ $student->id }}" data-student-id="{{ $student->id }}">
                        <td class="text-center text-muted" style="font-size:.75rem">{{ $i + 1 }}</td>
                        <td>
                            <span class="fw-medium text-dark" style="font-size:.875rem">{{ $studentName }}</span>
                        </td>
                        <td>
                            <div class="status-radios" id="radios-{{ $student->id }}">
                                @foreach($statuses as $status)
                                <input type="radio"
                                       name="attendance[{{ $student->id }}][attendance_status_id]"
                                       id="status-{{ $student->id }}-{{ $status->id }}"
                                       value="{{ $status->id }}"
                                       {{ $currentStatusId == $status->id ? 'checked' : '' }}
                                       onchange="updateRowStyle({{ $student->id }}, {{ $status->id }}, '{{ $status->code }}')">
                                <label for="status-{{ $student->id }}-{{ $status->id }}"
                                       title="{{ $status->name }}">
                                    {{ $status->code }}
                                </label>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <input type="text"
                                   name="attendance[{{ $student->id }}][remarks]"
                                   class="remarks-input"
                                   id="remarks-{{ $student->id }}"
                                   placeholder="Optional remark…"
                                   value="{{ old('attendance.'.$student->id.'.remarks', $currentRemarks) }}"
                                   maxlength="500">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- STICKY SAVE BAR --}}
        <div class="ta-save-bar">
            <div class="text-muted" style="font-size:.82rem">
                <i class="bi bi-info-circle me-1"></i>
                All changes are saved when you click "Save Attendance".
                The session status will be set to <strong>Closed</strong>.
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('attendance-sessions.show', $session->id) }}" class="btn btn-outline-secondary px-4">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-medium" id="btn-save-attendance">
                    <i class="bi bi-floppy me-2"></i>Save Attendance
                </button>
            </div>
        </div>
    </div>

</form>
@endif

@endsection

@push('scripts')
<script>
const presentStatusId = {{ $presentStatusId }};
const absentCodes  = ['A'];
const lateCodes    = ['L'];

function markAllPresent() {
    document.querySelectorAll('tr.ta-row').forEach(row => {
        const studentId = row.dataset.studentId;
        const radio = document.getElementById('status-' + studentId + '-' + presentStatusId);
        if (radio) {
            radio.checked = true;
            updateRowStyle(studentId, presentStatusId, 'P');
        }
    });
}

function resetAll() {
    // Reset to original state (present for all)
    markAllPresent();
}

function updateRowStyle(studentId, statusId, code) {
    const row = document.getElementById('ta-row-' + studentId);
    if (!row) return;
    row.classList.remove('ta-row-absent', 'ta-row-late');
    if (absentCodes.includes(code))  row.classList.add('ta-row-absent');
    if (lateCodes.includes(code))    row.classList.add('ta-row-late');
}

// Init row styles on page load
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tr.ta-row').forEach(row => {
        const studentId = row.dataset.studentId;
        const checked   = row.querySelector('input[type="radio"]:checked');
        if (checked) {
            const label = row.querySelector('label[for="' + checked.id + '"]');
            updateRowStyle(studentId, checked.value, label ? label.textContent.trim() : '');
        }
    });
});
</script>
@endpush