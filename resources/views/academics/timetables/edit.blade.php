@extends('layouts.app')

@section('title', 'កែសម្រួលកាលវិភាគ — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item"><a href="{{ route('timetables.index') }}" class="text-decoration-none text-muted">កាលវិភាគ</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កែសម្រួលកាលវិភាគ</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER BANNER
═══════════════════════════════════════════════════════════════ */
.note-page-banner {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 60%, #b45309 100%) !important;
    border-radius: var(--radius-lg);
    padding: 0.85rem 1.25rem !important;
    margin-bottom: 1rem !important;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(217, 119, 6, 0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.note-page-banner::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 120px; height: 120px;
    background: rgba(255, 255, 255, 0.18);
    border-radius: 50%;
    pointer-events: none;
}
.note-page-banner::after {
    content: '';
    position: absolute;
    bottom: -30px; right: 80px;
    width: 80px; height: 80px;
    background: rgba(255, 255, 255, 0.10);
    border-radius: 50%;
    pointer-events: none;
}
.note-page-banner .banner-icon {
    width: 36px; height: 36px;
    background: rgba(255, 255, 255, 0.22);
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #ffffff;
    margin-bottom: 0 !important;
    border: 1px solid rgba(255, 255, 255, 0.3);
    flex-shrink: 0;
}
.note-page-banner h1 {
    font-size: 1.1rem !important; font-weight: 700;
    color: #ffffff; margin: 0 0 .15rem !important;
    line-height: 1.25;
    text-shadow: 0 1px 2px rgba(0,0,0,0.15);
}
.note-page-banner p {
    font-size: .78rem !important; color: rgba(255, 255, 255, 0.95);
    margin: 0 !important;
    line-height: 1.2;
}
.note-page-banner .banner-actions {
    position: static !important;
    z-index: 2;
}
.note-page-banner .banner-actions .btn {
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.45);
    color: #ffffff !important;
    font-size: 0.80rem !important;
    padding: 0.25rem 0.75rem !important;
    backdrop-filter: blur(4px);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
}
.note-page-banner .banner-actions .btn:hover {
    background: #ffffff;
    color: #b45309 !important;
    border-color: #ffffff;
}

/* ═══════════════════════════════════════════════════════════════
   FORM CARD
═══════════════════════════════════════════════════════════════ */
.note-form-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 12px rgba(0,0,0,.04);
}
.form-section-head { display: flex; align-items: center; gap: .7rem; margin-bottom: .85rem; }
.form-section-head-bar { width: 3.5px; height: 1.15rem; border-radius: 999px; background: var(--gold); flex-shrink: 0; }
.form-section-head-label { font-size: .88rem; font-weight: 700; letter-spacing: .2px; color: var(--text-dark, #1e293b); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }
.form-section { padding: 1.1rem 1.35rem; border-bottom: 1px solid var(--border-soft); }
.form-section:last-child { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════
   FIELD STYLES
═══════════════════════════════════════════════════════════════ */
.field-hint { display: flex; align-items: center; gap: .3rem; font-size: .73rem; color: var(--text-muted); margin-top: .2rem; line-height: 1.3; }
.form-select.fc, .form-control.fc {
    border-color: #e2e8f0; font-size: .875rem; border-radius: var(--radius-sm); color: var(--text-dark); background-color: #fff;
    transition: border-color .15s, box-shadow .15s; padding: .42rem .75rem; line-height: 1.45;
}
.form-select.fc { padding-right: 2.25rem; }
.form-select.fc:focus, .form-control.fc:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

/* ═══════════════════════════════════════════════════════════════
   FORM ACTIONS BAR
═══════════════════════════════════════════════════════════════ */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: .75rem 1.35rem; background: #f8fafc; border-top: 1px solid var(--border-soft);
}
.required-note {
    display: flex; align-items: center; gap: .5rem; font-size: .75rem; color: var(--text-muted);
    padding: .45rem .75rem; background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: var(--radius-sm);
}
.required-mark { color: #ef4444; font-weight: 700; }

/* SIDEBAR CARDS */
.sidebar-card { background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.03); margin-bottom: 1rem; }
.sidebar-card-header { display: flex; align-items: center; gap: .65rem; padding: .75rem 1rem; border-bottom: 1px solid var(--border-soft); background: #fafafa; }
.sidebar-card-icon { width: 28px; height: 28px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; }
.sidebar-card-title { font-size: .82rem; font-weight: 700; color: var(--text-dark); }
.sidebar-card-body  { padding: .75rem 1rem; }
.guide-item { display: flex; gap: .65rem; padding: .4rem 0; border-bottom: 1px solid var(--border-soft); font-size: .78rem; color: var(--text-body); align-items: flex-start; }
.guide-item:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="d-flex align-items-center gap-3">
        <div class="banner-icon"><i class="bi bi-pencil"></i></div>
        <div>
            <h1>កែសម្រួលកាលវិភាគ</h1>
            <p>កែប្រែថ្នាក់រៀន ឬឆមាសដែលបានភ្ជាប់ជាមួយកាលវិភាគនេះ។</p>
        </div>
    </div>
    <div class="banner-actions">
        <a href="{{ route('timetables.index') }}" class="btn btn-sm">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅកាលវិភាគ
        </a>
    </div>
</div>

{{-- VALIDATION ERRORS --}}
@if($errors->any())
    <div class="alert-card alert-card-danger mb-4">
        <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
        <div>
            <strong>សូមពិនិត្យ និងកែតម្រូវកំហុសខាងក្រោម៖</strong>
            <ul class="mb-0 mt-1 ps-4">
                @foreach($errors->all() as $error)
                    <li style="font-size:.875rem">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form action="{{ route('timetables.update', $timetable) }}" method="POST" novalidate>
@csrf
@method('PUT')

<div class="row g-4 align-items-start">
    {{-- LEFT — MAIN FORM --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- Section 1: Select Class & Classroom --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">ថ្នាក់រៀន និងបន្ទប់រៀនគោលដៅ</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3 align-items-start">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center" style="height: 26px; margin-bottom: 0.35rem;">
                            <label for="class_id" class="form-label-custom mb-0" style="margin-bottom: 0 !important;">
                                ជ្រើសរើសថ្នាក់រៀន <span class="required-mark ms-1">*</span>
                            </label>
                        </div>
                        <select name="class_id" id="class_id"
                                class="form-select fc @error('class_id') is-invalid @enderror"
                                required>
                            <option value="">— ជ្រើសរើសថ្នាក់រៀន —</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $timetable->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->grade->name ?? 'ថ្នាក់មិនស្គាល់' }} — {{ $class->section->name ?? 'បន្ទប់មិនស្គាល់' }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint" style="min-height: 18px;">ថ្នាក់រៀនជាក់លាក់ដែលត្រូវអនុវត្តកាលវិភាគនេះ</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center" style="height: 26px; margin-bottom: 0.35rem;">
                            <label for="building_filter" class="form-label-custom mb-0" style="margin-bottom: 0 !important;">
                                <i class="bi bi-building me-1 text-primary"></i>ជ្រើសរើសអគារ
                            </label>
                        </div>
                        <select id="building_filter" class="form-select fc">
                            <option value="">— គ្រប់អគារទាំងអស់ —</option>
                            @foreach($buildings as $b)
                                <option value="{{ $b }}">អគារ {{ $b }}</option>
                            @endforeach
                        </select>
                        <div class="field-hint" style="min-height: 18px;">តម្រងបន្ទប់តាមអគារ</div>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center" style="height: 26px; margin-bottom: 0.35rem;">
                            <div class="d-inline-flex align-items-center gap-1.5">
                                <label for="classroom_id" class="form-label-custom mb-0" style="margin-bottom: 0 !important;">
                                    បន្ទប់រៀន
                                </label>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-1" style="font-size:0.68rem; font-weight:normal; line-height: 1.3;">ទំនេរ</span>
                            </div>
                            @if(Route::has('classrooms.create') && (!auth()->user() || auth()->user()->canAccess('classrooms.create')))
                                <a href="{{ route('classrooms.create') }}" target="_blank" class="small text-decoration-none text-primary" style="font-size:0.75rem;" title="បង្កើតបន្ទប់រៀនថ្មី">
                                    <i class="bi bi-plus-circle me-1"></i>បង្កើតថ្មី
                                </a>
                            @endif
                        </div>
                        <select name="classroom_id" id="classroom_id"
                                class="form-select fc @error('classroom_id') is-invalid @enderror">
                            <option value="">— ជ្រើសរើសបន្ទប់រៀន —</option>
                        </select>
                        @error('classroom_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint" id="classroom_hint" style="min-height: 18px;">បង្ហាញតែបន្ទប់ដែលមិនទាន់បានបែងចែក</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Term Configuration --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#e8a838"></div>
                    <span class="form-section-head-label">ព័ត៌មានឆ្នាំសិក្សា និងឆមាស</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="academic_year_id" class="form-label-custom">
                            ឆ្នាំសិក្សា <span class="required-mark">*</span>
                        </label>
                        <select name="academic_year_id" id="academic_year_id"
                                class="form-select fc @error('academic_year_id') is-invalid @enderror"
                                required>
                            <option value="">— ជ្រើសរើសឆ្នាំសិក្សា —</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                        {{ old('academic_year_id', $timetable->academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="school_term_id" class="form-label-custom">
                            ឆមាស <span class="required-mark">*</span>
                        </label>
                        <select name="school_term_id" id="school_term_id"
                                class="form-select fc @error('school_term_id') is-invalid @enderror"
                                required>
                            <option value="">— ជ្រើសរើសឆមាស —</option>
                            @foreach($schoolTerms as $term)
                                @php
                                    $tName = $term->name;
                                    $tNameMap = [
                                        'First Term' => 'ឆមាសទី ១',
                                        'Second Term' => 'ឆមាសទី ២',
                                        'Third Term' => 'ឆមាសទី ៣',
                                        'Term 1' => 'ឆមាសទី ១',
                                        'Term 2' => 'ឆមាសទី ២',
                                        'Term 3' => 'ឆមាសទី ៣',
                                        'Semester 1' => 'ឆមាសទី ១',
                                        'Semester 2' => 'ឆមាសទី ២',
                                    ];
                                    $dispTerm = $tNameMap[$tName] ?? $tName;
                                @endphp
                                <option value="{{ $term->id }}"
                                        {{ old('school_term_id', $timetable->school_term_id) == $term->id ? 'selected' : '' }}>
                                    {{ $dispTerm }}
                                </option>
                            @endforeach
                        </select>
                        @error('school_term_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Teacher Information --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#10b981"></div>
                    <span class="form-section-head-label">ព័ត៌មានគ្រូបង្រៀន និងទំនាក់ទំនង</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="teacher_id" class="form-label-custom">
                            គ្រូបង្រៀនទទួលបន្ទុកថ្នាក់
                        </label>
                        <select name="teacher_id" id="teacher_id"
                                class="form-select fc @error('teacher_id') is-invalid @enderror">
                            <option value="">— ជ្រើសរើសគ្រូបង្រៀន —</option>
                            @foreach($teachers as $teacher)
                                @php
                                    $tName = $teacher->user?->name ?? $teacher->full_name ?? $teacher->name ?? $teacher->employee_code;
                                    $tPhone = $teacher->phone ?? $teacher->user?->phone ?? $teacher->employeeContact?->phone;
                                @endphp
                                <option value="{{ $teacher->id }}"
                                        data-phone="{{ $tPhone ?? '' }}"
                                        {{ old('teacher_id', $currentTeacherId ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $tName }}{{ $tPhone ? " ({$tPhone})" : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint">គ្រូបង្រៀនដែលទទួលបន្ទុកថ្នាក់សម្រាប់កាលវិភាគនេះ</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="teacher_phone" class="form-label-custom">
                            លេខទូរស័ព្ទគ្រូ
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input type="text" name="teacher_phone" id="teacher_phone"
                                   class="form-control fc @error('teacher_phone') is-invalid @enderror"
                                   placeholder="ឧ. 012 345 678"
                                   value="{{ old('teacher_phone', $currentPhone ?? '') }}">
                        </div>
                        @error('teacher_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint">លេខទូរស័ព្ទទំនាក់ទំនងរបស់គ្រូ (បំពេញដោយស្វ័យប្រវត្តិពេលរើសគ្រូ)</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle" style="color:#0369a1"></i>
                    ប្រអប់ដែលមានសញ្ញា <span class="required-mark mx-1">*</span> គឺតម្រូវឱ្យបំពេញ
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('timetables.index') }}" class="btn btn-ghost btn-sm">
                        <i class="bi bi-x-lg me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-primary-action btn-sm">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT — SIDEBAR --}}
    <div class="col-xl-4 col-lg-4">
        {{-- Guidelines --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="sidebar-card-icon" style="background:#fef9c3;color:#854d0e"><i class="bi bi-lightbulb"></i></div>
                <div class="sidebar-card-title">ការប្រុងប្រយ័ត្ន</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-shield-exclamation text-warning"></i>
                    <span>ការផ្លាស់ប្តូរថ្នាក់ ឬឆមាសដែលភ្ជាប់ជាមួយកាលវិភាគនេះ នឹងផ្ទេររាល់ម៉ោងបង្រៀនដែលមានស្រាប់ទៅកាន់ថ្នាក់/ឆមាសថ្មី។ សូមប្រាកដថាអ្នកពិតជាចង់ធ្វើបែបនេះ!</span>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const teacherSelect = document.getElementById('teacher_id');
        const phoneInput = document.getElementById('teacher_phone');
        const classSelect = document.getElementById('class_id');
        const buildingFilter = document.getElementById('building_filter');
        const classroomSelect = document.getElementById('classroom_id');
        const classroomHint = document.getElementById('classroom_hint');
        const allClassrooms = @json($classroomsData ?? []);
        const classTeacherMap = @json($classTeacherMap ?? []);
        const classRoomMap = @json($classRoomMap ?? []);

        function refreshClassroomOptions(targetRoomId = null) {
            if (!classroomSelect) return;

            const selectedClassId = classSelect && classSelect.value ? parseInt(classSelect.value, 10) : null;
            const selectedBuilding = buildingFilter ? buildingFilter.value.trim().toLowerCase() : '';
            
            const activeRoomId = targetRoomId !== null 
                ? (targetRoomId ? parseInt(targetRoomId, 10) : null)
                : (classroomSelect.value ? parseInt(classroomSelect.value, 10) : null);

            // Filter classrooms:
            // 1. Must match building if building is filtered
            // 2. Must NOT be assigned to another class (unallocated OR assigned to this selected class)
            const availableRooms = allClassrooms.filter(room => {
                const roomBld = (room.building || '').trim().toLowerCase();
                if (selectedBuilding && roomBld !== selectedBuilding) {
                    return false;
                }
                const assigned = room.assigned_classes || [];
                const isAssignedToOther = assigned.length > 0 && (!selectedClassId || !assigned.includes(selectedClassId));
                return !isAssignedToOther;
            });

            // Clear current options
            classroomSelect.innerHTML = '';

            // Add placeholder option
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = availableRooms.length > 0 
                ? '— ជ្រើសរើសបន្ទប់រៀន (ទំនេរ ' + availableRooms.length + ') —' 
                : '— គ្មានបន្ទប់ទំនេរ' + (selectedBuilding ? 'ក្នុងអគារនេះ' : '') + 'ទេ —';
            classroomSelect.appendChild(defaultOpt);

            let hasMatched = false;

            availableRooms.forEach(room => {
                const opt = document.createElement('option');
                opt.value = room.id;
                
                const isCurrentClassRoom = selectedClassId && (room.assigned_classes || []).includes(selectedClassId);
                opt.textContent = room.label + (isCurrentClassRoom ? ' (បន្ទប់បច្ចុប្បន្ន)' : '');
                
                if (activeRoomId && room.id === activeRoomId) {
                    opt.selected = true;
                    hasMatched = true;
                }
                classroomSelect.appendChild(opt);
            });

            if (!hasMatched && activeRoomId) {
                const forcedRoom = allClassrooms.find(r => r.id === activeRoomId);
                if (forcedRoom) {
                    const opt = document.createElement('option');
                    opt.value = forcedRoom.id;
                    opt.textContent = forcedRoom.label + ' (បន្ទប់បច្ចុប្បន្ន)';
                    opt.selected = true;
                    classroomSelect.appendChild(opt);
                    hasMatched = true;
                }
            }

            if (classroomHint) {
                if (availableRooms.length > 0) {
                    classroomHint.textContent = 'មានបន្ទប់ទំនេរ ' + availableRooms.length + ' អាចជ្រើសរើសបាន';
                    classroomHint.className = 'field-hint text-success';
                } else {
                    classroomHint.textContent = 'គ្មានបន្ទប់ទំនេរ' + (selectedBuilding ? 'ក្នុងអគារនេះ' : '') + 'ទេ';
                    classroomHint.className = 'field-hint text-muted';
                }
            }
        }

        teacherSelect?.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const phone = opt ? (opt.getAttribute('data-phone') || '') : '';
            if (phoneInput && phone) {
                phoneInput.value = phone;
            }
        });

        classSelect?.addEventListener('change', function () {
            const classId = this.value ? parseInt(this.value, 10) : null;
            const assignedRoomId = classId && classRoomMap[classId] ? parseInt(classRoomMap[classId], 10) : null;

            if (assignedRoomId) {
                const roomObj = allClassrooms.find(r => r.id === assignedRoomId);
                if (roomObj && roomObj.building && buildingFilter) {
                    buildingFilter.value = roomObj.building;
                }
            }

            refreshClassroomOptions(assignedRoomId);

            if (classTeacherMap && classId && classTeacherMap[classId]) {
                const info = classTeacherMap[classId];
                if (info.teacher_id && teacherSelect) {
                    teacherSelect.value = info.teacher_id;
                    if (phoneInput && info.phone) {
                        phoneInput.value = info.phone;
                    }
                }
            }
        });

        buildingFilter?.addEventListener('change', function () {
            refreshClassroomOptions();
        });

        // Initialize on load
        const oldRoomId = '{{ old('classroom_id', $currentClassroomId ?? '') }}';
        const initClassId = classSelect && classSelect.value ? parseInt(classSelect.value, 10) : null;
        let initRoomId = oldRoomId ? parseInt(oldRoomId, 10) : null;

        if (!initRoomId && initClassId && classRoomMap[initClassId]) {
            initRoomId = parseInt(classRoomMap[initClassId], 10);
        }

        if (initRoomId) {
            const roomObj = allClassrooms.find(r => r.id === initRoomId);
            if (roomObj && roomObj.building && buildingFilter) {
                buildingFilter.value = roomObj.building;
            }
        }

        refreshClassroomOptions(initRoomId);
    });
</script>
@endpush

@endsection
