@extends('layouts.app')

@section('title', 'ចាត់តាំងគ្រូបង្រៀនថ្មី — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item"><a href="{{ route('teacher-assignments.index') }}" class="text-decoration-none text-muted">ការចាត់តាំងគ្រូបង្រៀន</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ចាត់តាំងគ្រូបង្រៀនថ្មី</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
.page-banner {
    background: linear-gradient(135deg, #b45309 0%, #d97706 45%, #f59e0b 80%, #fbbf24 100%);
    border-radius: var(--radius-lg); padding: 1.6rem 1.75rem; margin-bottom: 1.75rem;
    position: relative; overflow: hidden;
    border: 1px solid rgba(251, 191, 36, 0.5);
    box-shadow: 0 4px 20px rgba(217, 119, 6, 0.25);
}
.page-banner::before {
    content: ''; position: absolute; top: -40px; right: -40px; width: 160px; height: 160px;
    background: rgba(255, 255, 255, 0.2); border-radius: 50%;
    pointer-events: none;
}
.page-banner::after {
    content: ''; position: absolute; bottom: -30px; right: 80px; width: 100px; height: 100px;
    background: rgba(255, 255, 255, 0.12); border-radius: 50%;
    pointer-events: none;
}
.page-banner > * { position: relative; z-index: 2; }
.page-banner h1 { font-size: 1.35rem; font-weight: 700; color: #ffffff; margin: 0 0 .25rem; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25); }
.page-banner p { font-size: .84rem; color: rgba(255, 255, 255, 0.95); margin: 0; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); }

.form-card {
    background: #fff; border: 1px solid var(--border-soft); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.04);
}
.form-section { padding: 1.15rem 1.35rem; }
.form-section-head { display: flex; align-items: center; gap: .7rem; margin-bottom: .85rem; }
.form-section-head-bar { width: 3.5px; height: 1.15rem; border-radius: 999px; background: var(--gold, #f59e0b); flex-shrink: 0; }
.form-section-head-label { font-size: .88rem; font-weight: 700; letter-spacing: .2px; color: var(--text-dark, #1e293b); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }

.field-hint { font-size: .73rem; color: var(--text-muted); margin-top: .15rem; }
.fc {
    border-color: #e2e8f0; font-size: .875rem; border-radius: var(--radius-sm);
    padding: .45rem .8rem;
}
.fc:focus { border-color: var(--gold, #f59e0b); box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15); outline: none; }
</style>
@endpush

@section('content')

{{-- PAGE BANNER --}}
<div class="page-banner">
    <div class="d-flex align-items-center justify-content-between position-relative" style="z-index: 2;">
        <div>
            <h1>ចាត់តាំងគ្រូបង្រៀន</h1>
            <p>កំណត់ទំនាក់ទំនងផ្លូវការ៖ គ្រូបង្រៀន &rarr; ថ្នាក់រៀន &rarr; ឆ្នាំសិក្សា</p>
        </div>
        <div>
            <a href="{{ route('teacher-assignments.index') }}" class="btn btn-outline-light btn-sm position-relative" style="z-index: 5;">
                <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9" style="max-width: 680px;">
        <div class="form-card">
            <form action="{{ route('teacher-assignments.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-head-bar"></div>
                        <span class="form-section-head-label">ព័ត៌មានលម្អិតនៃការចាត់តាំង</span>
                        <div class="form-section-head-line"></div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mb-3 py-2">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Teacher Selection --}}
                    <div class="mb-3">
                        <label for="employee_id" class="form-label fw-bold small text-dark mb-1">
                            គ្រូបង្រៀន <span class="text-danger">*</span>
                        </label>
                        <select name="employee_id" id="employee_id" class="form-select fc @error('employee_id') is-invalid @enderror" required>
                            <option value="">-- ជ្រើសរើសគ្រូបង្រៀន --</option>
                            @forelse($employees as $e)
                                <option value="{{ $e->id }}" {{ old('employee_id') == $e->id ? 'selected' : '' }}>
                                    {{ $e->name ?: ($e->user?->name ?? 'មិនស្គាល់') }} (កូដ: {{ $e->employee_code }})
                                </option>
                            @empty
                                <option value="" disabled>-- មិនមានបុគ្គលិកដែលមានតួនាទីជាគ្រូបង្រៀនទេ --</option>
                            @endforelse
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Academic Year Selection --}}
                    <div class="mb-3">
                        <label for="academic_year_id" class="form-label fw-bold small text-dark mb-1">
                            ឆ្នាំសិក្សា <span class="text-danger">*</span>
                        </label>
                        @php
                            $defaultYearId = old('academic_year_id', request('academic_year_id', $academicYears->firstWhere('is_active', true)?->id ?? $academicYears->first()?->id));
                        @endphp
                        <select name="academic_year_id" id="academic_year_id" class="form-select fc @error('academic_year_id') is-invalid @enderror" required>
                            <option value="">-- ជ្រើសរើសឆ្នាំសិក្សា --</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $defaultYearId == $ay->id ? 'selected' : '' }}>
                                    {{ $ay->name }} {{ $ay->is_active ? '(ឆ្នាំសកម្ម)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Class Selection --}}
                    <div class="mb-2">
                        <label for="class_id" class="form-label fw-bold small text-dark mb-1">
                            ថ្នាក់រៀន <span class="text-danger">*</span>
                        </label>
                        @php
                            $preselectedClassId = old('class_id', request('class_id'));
                        @endphp
                        <select name="class_id" id="class_id" class="form-select fc @error('class_id') is-invalid @enderror" required>
                            <option value="">-- ជ្រើសរើសថ្នាក់រៀន --</option>
                            @foreach($classes as $c)
                                @php
                                    $yearId = $c->academic_year_id ?? '';
                                    $isSelected = $preselectedClassId == $c->id;
                                    $className = ($c->grade->name ?? 'ថ្នាក់') . ' — ' . ($c->section->name ?? 'បន្ទប់');
                                @endphp
                                <option value="{{ $c->id }}" data-year-id="{{ $yearId }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $className }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="px-3.5 py-2.5 bg-light border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('teacher-assignments.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5">បោះបង់</a>
                    <button type="submit" class="btn btn-sm btn-primary shadow-sm px-3.5 py-1.5">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការចាត់តាំង
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const aySelect = document.getElementById('academic_year_id');
    const classSelect = document.getElementById('class_id');

    if (!aySelect || !classSelect) return;

    // Cache all initial class options
    const allClassOptions = Array.from(classSelect.querySelectorAll('option')).map(opt => ({
        value: opt.value,
        text: opt.textContent.trim(),
        yearId: opt.getAttribute('data-year-id') || '',
        selected: opt.selected
    }));

    function filterClassesByYear() {
        const selectedYearId = aySelect.value;
        const currentClassValue = classSelect.value;

        // Reset class select options
        classSelect.innerHTML = '';

        const placeholderOpt = document.createElement('option');
        placeholderOpt.value = '';
        if (!selectedYearId) {
            placeholderOpt.textContent = '-- សូមជ្រើសរើសឆ្នាំសិក្សាជាមុនសិន --';
            classSelect.appendChild(placeholderOpt);
            classSelect.disabled = true;
            return;
        }

        classSelect.disabled = false;
        placeholderOpt.textContent = '-- ជ្រើសរើសថ្នាក់រៀន --';
        classSelect.appendChild(placeholderOpt);

        let matchCount = 0;
        let preservedSelection = false;

        allClassOptions.forEach(item => {
            if (!item.value) return; // skip placeholder

            if (String(item.yearId) === String(selectedYearId)) {
                const opt = document.createElement('option');
                opt.value = item.value;
                opt.textContent = item.text;
                opt.setAttribute('data-year-id', item.yearId);

                // Preserve previously selected class if it matches the newly selected year
                if (String(item.value) === String(currentClassValue)) {
                    opt.selected = true;
                    preservedSelection = true;
                }
                classSelect.appendChild(opt);
                matchCount++;
            }
        });

        if (matchCount === 0) {
            placeholderOpt.textContent = '-- មិនមានថ្នាក់រៀនក្នុងឆ្នាំសិក្សានេះទេ --';
        }

        if (!preservedSelection && currentClassValue) {
            classSelect.value = '';
        }
    }

    aySelect.addEventListener('change', filterClassesByYear);

    // Initial filter on load
    filterClassesByYear();
});
</script>
@endpush

@endsection
