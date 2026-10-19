@extends('layouts.app')

@section('title', 'Edit Class — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Academic</li>
        <li class="breadcrumb-item"><a href="{{ route('classes.index') }}" class="text-decoration-none text-muted">Classes</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Edit Class</li>
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
    position: relative; overflow: hidden;
    box-shadow: 0 2px 8px rgba(217, 119, 6, 0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.note-page-banner::before {
    content: ''; position: absolute;
    top: -40px; right: -40px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,.18); border-radius: 50%;
    pointer-events: none;
}
.note-page-banner::after {
    content: ''; position: absolute;
    bottom: -30px; right: 80px;
    width: 80px; height: 80px;
    background: rgba(255,255,255,.10); border-radius: 50%;
    pointer-events: none;
}
.note-page-banner .banner-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,.22);
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #fff;
    margin-bottom: 0 !important;
    border: 1px solid rgba(255,255,255,.3);
    flex-shrink: 0;
}
.note-page-banner h1 { font-size: 1.1rem !important; font-weight: 700; color: #fff; margin: 0 0 .15rem !important; line-height: 1.25; text-shadow: 0 1px 2px rgba(0,0,0,0.15); }
.note-page-banner p  { font-size: .78rem !important; color: rgba(255,255,255,.95); margin: 0 !important; line-height: 1.2; }
.note-page-banner .banner-actions { position: static !important; z-index: 2; }
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
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 12px rgba(0,0,0,.04);
}
.form-section-head { display: flex; align-items: center; gap: .7rem; margin-bottom: 1.1rem; }
.form-section-head-bar { width: 3.5px; height: 1.2rem; border-radius: 999px; background: var(--gold); flex-shrink: 0; }
.form-section-head-label { font-size: .88rem; font-weight: 700; letter-spacing: .2px; color: var(--text-dark); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }
.form-section { padding: 1.35rem 1.5rem; border-bottom: 1px solid var(--border-soft); }
.form-section:last-child { border-bottom: none; }

/* ═══════════════════════════════════════════════════════════════
   FIELD STYLES
═══════════════════════════════════════════════════════════════ */
.field-hint { display: flex; align-items: center; gap: .3rem; font-size: .73rem; color: var(--text-muted); margin-top: .3rem; }
.form-select.fc, .form-control.fc {
    border-color: #e2e8f0; font-size: .875rem; border-radius: var(--radius-sm); color: var(--text-dark); background-color: #fff;
    transition: border-color .15s, box-shadow .15s; padding: .48rem .85rem;
}
.form-select.fc { padding-right: 2.25rem; }
.form-select.fc:focus, .form-control.fc:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-subtle); outline: none; }
.form-select.fc.is-invalid, .form-control.fc.is-invalid { border-color: #ef4444; }

/* ═══════════════════════════════════════════════════════════════
   FORM ACTIONS BAR
═══════════════════════════════════════════════════════════════ */
.form-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid var(--border-soft);
}
.required-note {
    display: flex; align-items: center; gap: .5rem; font-size: .75rem; color: var(--text-muted);
    padding: .65rem .9rem; background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: var(--radius-sm);
}
.required-mark { color: #ef4444; font-weight: 700; }

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR CARDS
═══════════════════════════════════════════════════════════════ */
.sidebar-card { background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.03); margin-bottom: 1rem; }
.sidebar-card-header { display: flex; align-items: center; gap: .65rem; padding: .9rem 1.1rem; border-bottom: 1px solid var(--border-soft); background: #fafafa; }
.sidebar-card-icon { width: 30px; height: 30px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; }
.sidebar-card-title { font-size: .82rem; font-weight: 700; color: var(--text-dark); }
.sidebar-card-body { padding: .85rem 1.1rem; }
.guide-item { display: flex; gap: .65rem; padding: .5rem 0; border-bottom: 1px solid var(--border-soft); font-size: .79rem; color: var(--text-body); align-items: flex-start; }
.guide-item:last-child { border-bottom: none; }
.guide-item i { flex-shrink: 0; margin-top: .15rem; font-size: .85rem; }
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="note-page-banner">
    <div class="d-flex align-items-center gap-3">
        <div class="banner-icon"><i class="bi bi-diagram-3"></i></div>
        <div>
            <h1>កែសម្រួលថ្នាក់រៀន</h1>
            <p>កែប្រែព័ត៌មាន និងការកំណត់សម្រាប់ {{ $class->grade->name ?? '' }} — {{ $class->section->name ?? '' }}</p>
        </div>
    </div>
    <div class="banner-actions">
        <a href="{{ route('classes.index') }}" class="btn btn-sm btn-ghost text-white">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅបញ្ជីថ្នាក់
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

<form action="{{ route('classes.update', $class) }}" method="POST" novalidate id="classForm">
@csrf
@method('PUT')

<div class="row g-4 align-items-start">

    {{-- LEFT — MAIN FORM --}}
    <div class="col-xl-8 col-lg-8">
        <div class="note-form-card">

            {{-- Section 1: Academic Year --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#3b82f6"></div>
                    <span class="form-section-head-label">ឆ្នាំសិក្សា / ឆមាស</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="academic_year_id" class="form-label-custom">
                            ឆ្នាំសិក្សា <span class="required-mark">*</span>
                        </label>
                        <select name="academic_year_id" id="academic_year_id"
                                class="form-select fc @error('academic_year_id') is-invalid @enderror"
                                required>
                            <option value="">ជ្រើសរើសឆ្នាំសិក្សា</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                        {{ old('academic_year_id', $class->academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint"><i class="bi bi-calendar3"></i>ឆ្នាំសិក្សាដែលថ្នាក់នេះស្ថិតនៅ</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Grade & Section --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#e8a838"></div>
                    <span class="form-section-head-label">កម្រិតថ្នាក់ និងក្រុមអក្សរ</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="grade_id" class="form-label-custom">
                            កម្រិតថ្នាក់ <span class="required-mark">*</span>
                        </label>
                        <select name="grade_id" id="grade_id"
                                class="form-select fc @error('grade_id') is-invalid @enderror"
                                required>
                            <option value="">ជ្រើសរើសកម្រិតថ្នាក់</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}"
                                        {{ old('grade_id', $class->grade_id) == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('grade_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="section_id" class="form-label-custom">
                            ក្រុមអក្សរ / ផ្នែក <span class="required-mark">*</span>
                        </label>
                        <select name="section_id" id="section_id"
                                class="form-select fc @error('section_id') is-invalid @enderror"
                                required>
                            <option value="">ជ្រើសរើសក្រុមអក្សរ</option>
                            @foreach($sections as $section)
                                @php
                                    $secShift = \App\Models\Academics\SchoolClass::detectShift($section->name);
                                    $secShiftLabel = \App\Models\Academics\SchoolClass::getShiftLabel($secShift);
                                @endphp
                                <option value="{{ $section->id }}"
                                        data-shift="{{ $secShift }}"
                                        data-shift-label="{{ $secShiftLabel }}"
                                        {{ old('section_id', $class->section_id) == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Classroom & Capacity --}}
            <div class="form-section">
                <div class="form-section-head">
                    <div class="form-section-head-bar" style="background:#16a34a"></div>
                    <span class="form-section-head-label">បន្ទប់រៀនជាក់ស្តែង និងចំណុះសិស្ស</span>
                    <div class="form-section-head-line"></div>
                </div>

                <div class="row g-3">
                    {{-- ជ្រើសរើសអគារ (Building) ជាមុន --}}
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-1" style="min-height: 24px;">
                            <label for="building_filter" class="form-label-custom mb-0">
                                អគារ (Building) <span class="required-mark text-danger">*</span>
                            </label>
                        </div>
                        <select id="building_filter" class="form-select fc" required>
                            <option value="">— ជ្រើសរើសអគារ —</option>
                            @foreach($buildings as $b)
                                <option value="{{ $b }}">អគារ {{ $b }}</option>
                            @endforeach
                        </select>
                        <div class="field-hint"><i class="bi bi-building"></i>រើសអគារដើម្បីចម្រាញ់បន្ទប់</div>
                    </div>

                    {{-- ជ្រើសរើសបន្ទប់រៀន (Classroom) --}}
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-1" style="min-height: 24px;">
                            <label for="classroom_id" class="form-label-custom mb-0">
                                បន្ទប់រៀន (Classroom) <span class="required-mark text-danger">*</span>
                            </label>
                        </div>
                        <select name="classroom_id" id="classroom_id"
                                class="form-select fc @error('classroom_id') is-invalid @enderror"
                                required>
                            <option value="">— ជ្រើសរើសបន្ទប់រៀន —</option>
                            @foreach($classrooms as $room)
                                <option value="{{ $room->id }}"
                                        data-building="{{ $room->building }}"
                                        data-capacity="{{ $room->capacity }}"
                                        data-room-name="{{ $room->name }}"
                                        {{ old('classroom_id', $class->classroom_id) == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="classroomConflictAlert" class="alert alert-danger py-1 px-2 mt-2 mb-0 d-none" style="font-size: 0.78rem;"></div>
                        @error('classroom_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="field-hint" id="classroomHint"><i class="bi bi-door-closed"></i>បន្ទប់រៀនក្នុងអគារដែលបានជ្រើស</div>
                        @enderror
                    </div>

                    {{-- ចំនួនសិស្សអតិបរមា (Capacity) --}}
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-between mb-1" style="min-height: 24px;">
                            <label for="capacity" class="form-label-custom mb-0">
                                ចំនួនសិស្សអតិបរមា <span class="required-mark">*</span>
                            </label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text" style="background:#f8fafc; border-color:#e2e8f0"><i class="bi bi-people"></i></span>
                            <input type="number" name="capacity" id="capacity"
                                   class="form-control fc @error('capacity') is-invalid @enderror"
                                   min="1" max="100"
                                   value="{{ old('capacity', $class->capacity) }}" required>
                        </div>
                        @error('capacity')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @else
                            <div class="field-hint"><i class="bi bi-info-circle"></i>ចំនួនសិស្សអតិបរមាក្នុងថ្នាក់</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-footer">
                <div class="required-note">
                    <i class="bi bi-info-circle" style="color:#0369a1"></i>
                    ប្រអប់ដែលមានសញ្ញា <span class="required-mark mx-1">*</span> ត្រូវតែបំពេញជាដាច់ខាត
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('classes.index') }}" class="btn btn-ghost btn-sm">
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
                <div class="sidebar-card-title">ការណែនាំកែប្រែ</div>
            </div>
            <div class="sidebar-card-body">
                <div class="guide-item">
                    <i class="bi bi-shield-exclamation text-warning"></i>
                    <span>ថ្នាក់រៀននីមួយៗត្រូវតែមានការផ្គុំគ្នាតែមួយគត់រវាង កម្រិតថ្នាក់, ក្រុមអក្សរ និងឆ្នាំសិក្សា។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-door-closed text-info"></i>
                    <span>អ្នកអាចប្តូរបន្ទប់រៀនជាក់ស្តែងបានគ្រប់ពេលដោយមិនប៉ះពាល់ដល់ទិន្នន័យសិស្សឡើយ។</span>
                </div>
                <div class="guide-item">
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                    <span>ការផ្លាស់ប្តូរកម្រិតថ្នាក់ ឬក្រុមអក្សរ អាចជះឥទ្ធិពលដល់សិស្សដែលកំពុងចុះឈ្មោះក្នុងថ្នាក់នេះ។</span>
                </div>
            </div>
        </div>

    </div>
</div>
</form>

@endsection

@section('scripts')
<script>
(function () {
    const yearSel = document.getElementById('academic_year_id');
    const sectionSel = document.getElementById('section_id');
    const buildingFilter = document.getElementById('building_filter');
    const roomSel = document.getElementById('classroom_id');
    const classroomHint = document.getElementById('classroomHint');
    const conflictAlert = document.getElementById('classroomConflictAlert');
    const capacityInp = document.getElementById('capacity');

    const occupiedRooms = @json($occupiedRooms ?? []);

    // Store all initial options of roomSel
    const originalRoomOptions = roomSel ? Array.from(roomSel.options) : [];

    function filterRoomsByBuilding() {
        if (!roomSel) return;

        const selectedBuilding = buildingFilter ? buildingFilter.value.trim().toLowerCase() : '';
        const selectedYearId = yearSel ? parseInt(yearSel.value, 10) : null;
        const selectedSecOpt = sectionSel ? sectionSel.options[sectionSel.selectedIndex] : null;
        const selectedShift = selectedSecOpt ? (selectedSecOpt.getAttribute('data-shift') || 'all') : 'all';
        const currentSelectedId = roomSel.value;

        // Clear current options
        roomSel.innerHTML = '';

        let matchCount = 0;
        let stillHasCurrent = false;
        let currentHasConflict = false;
        let currentConflictMessage = '';

        originalRoomOptions.forEach((opt, idx) => {
            if (idx === 0) {
                roomSel.appendChild(opt);
                return;
            }

            const roomBuilding = (opt.getAttribute('data-building') || '').trim().toLowerCase();
            if (selectedBuilding && roomBuilding !== selectedBuilding) {
                return;
            }

            const roomId = parseInt(opt.value, 10);
            const baseName = opt.getAttribute('data-room-name') || opt.text.split('(')[0].trim();

            // Check if room has conflict in selected academic year + shift (excluding current class)
            let conflict = null;
            if (selectedYearId && occupiedRooms && occupiedRooms.length > 0) {
                conflict = occupiedRooms.find(item => {
                    if (item.classroom_id !== roomId || item.academic_year_id !== selectedYearId) {
                        return false;
                    }
                    const itemShift = item.shift || 'all';
                    return selectedShift === 'all' || itemShift === 'all' || selectedShift === itemShift;
                });
            }

            if (conflict) {
                // Hide rooms that already have a class in this shift
                if (String(roomId) === String(currentSelectedId)) {
                    currentHasConflict = true;
                    currentConflictMessage = `<i class="bi bi-exclamation-triangle-fill me-1"></i>បន្ទប់ <strong>${baseName}</strong> ត្រូវបានជ្រើសរើសដោយ «${conflict.class_name}» ក្នុងវេន${conflict.shift_label} រួចហើយ! សូមជ្រើសរើសបន្ទប់ទំនេរផ្សេង។`;
                }
                return;
            }

            opt.textContent = baseName;
            opt.disabled = false;
            opt.style.color = '';
            roomSel.appendChild(opt);
            matchCount++;

            if (opt.value === currentSelectedId) {
                opt.selected = true;
                stillHasCurrent = true;
            }
        });

        if (!stillHasCurrent && currentSelectedId !== '') {
            roomSel.value = '';
        }

        if (conflictAlert) {
            if (currentHasConflict) {
                conflictAlert.innerHTML = currentConflictMessage;
                conflictAlert.classList.remove('d-none');
            } else {
                conflictAlert.classList.add('d-none');
                conflictAlert.innerHTML = '';
            }
        }

        if (classroomHint) {
            const shiftLabel = selectedSecOpt ? selectedSecOpt.getAttribute('data-shift-label') : '';
            const shiftText = shiftLabel ? ` (${shiftLabel})` : '';
            if (selectedBuilding) {
                const bName = buildingFilter.options[buildingFilter.selectedIndex].text.trim();
                if (matchCount > 0) {
                    classroomHint.innerHTML = `<i class="bi bi-door-closed text-success"></i>មាន <strong>${matchCount}</strong> បន្ទប់ទំនេរក្នុង ${bName}${shiftText}`;
                } else {
                    classroomHint.innerHTML = `<i class="bi bi-exclamation-circle text-warning"></i>គ្មានបន្ទប់ទំនេរក្នុង ${bName}${shiftText} ទេ`;
                }
            } else {
                if (matchCount > 0) {
                    classroomHint.innerHTML = `<i class="bi bi-door-closed text-success"></i>មាន <strong>${matchCount}</strong> បន្ទប់ទំនេរ${shiftText}`;
                } else {
                    classroomHint.innerHTML = `<i class="bi bi-exclamation-circle text-warning"></i>គ្មានបន្ទប់ទំនេរសម្រាប់វេននេះទេ`;
                }
            }
        }
    }

    if (roomSel) {
        roomSel.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const cap = opt ? opt.getAttribute('data-capacity') : null;
            if (cap && !capacityInp.value) {
                capacityInp.value = cap;
            }
            filterRoomsByBuilding();
        });
    }

    if (buildingFilter) {
        buildingFilter.addEventListener('change', filterRoomsByBuilding);

        // Pre-select building if room is already assigned to this class
        if (roomSel && roomSel.value) {
            const initialOpt = roomSel.options[roomSel.selectedIndex];
            const initialBuilding = initialOpt ? (initialOpt.getAttribute('data-building') || '').trim().toLowerCase() : '';
            if (initialBuilding) {
                for (let i = 0; i < buildingFilter.options.length; i++) {
                    if (buildingFilter.options[i].value.trim().toLowerCase() === initialBuilding) {
                        buildingFilter.selectedIndex = i;
                        break;
                    }
                }
            }
        }
    }

    if (yearSel) {
        yearSel.addEventListener('change', filterRoomsByBuilding);
    }
    if (sectionSel) {
        sectionSel.addEventListener('change', filterRoomsByBuilding);
    }

    filterRoomsByBuilding();
})();
</script>
@endsection
