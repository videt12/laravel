@extends('layouts.app')

@section('title', 'កំណត់មុខវិជ្ជាសម្រាប់ថ្នាក់រៀន — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item"><a href="{{ route('academics.index', ['tab' => 'class-subjects']) }}" class="text-decoration-none text-muted">មុខវិជ្ជាតាមថ្នាក់</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កំណត់មុខវិជ្ជាថ្មី</li>
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
.form-section { padding: 1.35rem 1.5rem; }
.form-section-head { display: flex; align-items: center; gap: .7rem; margin-bottom: 1.1rem; }
.form-section-head-bar { width: 3px; height: 1rem; border-radius: 999px; background: var(--gold, #f59e0b); flex-shrink: 0; }
.form-section-head-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--text-muted); }
.form-section-head-line { flex: 1; height: 1px; background: var(--border-soft); }

.field-hint { font-size: .75rem; color: var(--text-muted); margin-top: .3rem; }
.fc {
    border-color: #e2e8f0; font-size: .875rem; border-radius: var(--radius-sm);
    padding: .5rem .85rem;
}
.fc:focus { border-color: var(--gold, #f59e0b); box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15); outline: none; }

.subject-card-option {
    cursor: pointer;
    background: #fff;
    transition: all 0.15s ease;
    user-select: none;
}
.subject-card-option:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.subject-card-option.is-selected {
    background: #f0fdf4;
    border-color: #86efac;
}
</style>
@endpush

@section('content')

{{-- PAGE BANNER --}}
<div class="page-banner">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1>កំណត់មុខវិជ្ជាសម្រាប់ថ្នាក់រៀន</h1>
            <p>ជ្រើសរើសថ្នាក់រៀន និងមុខវិជ្ជាដែលត្រូវដាក់បញ្ចូលក្នុងកម្មវិធីសិក្សា</p>
        </div>
        <div>
            <a href="{{ route('academics.index', ['tab' => 'class-subjects']) }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="form-card">
            <form action="{{ route('class-subjects.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-head-bar"></div>
                        <span class="form-section-head-label">ព័ត៌មានថ្នាក់រៀនគោលដៅ</span>
                        <div class="form-section-head-line"></div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="class_id" class="form-label fw-bold small text-dark">
                            ថ្នាក់រៀន <span class="text-danger">*</span>
                        </label>
                        <select name="class_id" id="class_id" class="form-select fc @error('class_id') is-invalid @enderror" required>
                            <option value="">-- ជ្រើសរើសថ្នាក់រៀន --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->grade->name ?? 'កម្រិត' }} — {{ $c->section->name ?? 'បន្ទប់' }} 
                                    (ឆ្នាំសិក្សា៖ {{ $c->academic_year->name ?? '—' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint">ជ្រើសរើសថ្នាក់រៀន (រួមទាំងកម្រិតថ្នាក់ បន្ទប់ និងឆ្នាំសិក្សា)។</div>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section bg-light bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <div class="form-section-head mb-0">
                            <div class="form-section-head-bar" style="background:#16a34a"></div>
                            <span class="form-section-head-label">ជ្រើសរើសមុខវិជ្ជាសិក្សា</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2.5" onclick="toggleAll(true)" style="font-size:0.78rem">
                                <i class="bi bi-check-all me-1"></i>ជ្រើសរើសទាំងអស់
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5" onclick="toggleAll(false)" style="font-size:0.78rem">
                                <i class="bi bi-x me-1"></i>ដកទាំងអស់
                            </button>
                        </div>
                    </div>

                    <div class="row g-2">
                        @foreach($subjects as $s)
                            <div class="col-md-6 col-lg-4">
                                <label class="subject-card-option d-flex align-items-center gap-2.5 p-2.5 rounded-3 border h-100 cursor-pointer text-start w-100" 
                                       for="sub_check_{{ $s->id }}"
                                       id="sub_label_{{ $s->id }}">
                                    <input type="checkbox" 
                                           name="subject_ids[]" 
                                           value="{{ $s->id }}" 
                                           id="sub_check_{{ $s->id }}" 
                                           class="form-check-input mt-0 subject-checkbox flex-shrink-0"
                                           onchange="updateStyle(this)">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-bold text-dark text-truncate small">{{ $s->name }}</div>
                                        <div class="d-flex align-items-center gap-1 mt-0.5">
                                            <span class="badge bg-light text-secondary border font-monospace" style="font-size:0.65rem">{{ $s->code }}</span>
                                            @if($s->type)
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size:0.65rem">{{ $s->type }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-3 bg-light border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('academics.index', ['tab' => 'class-subjects']) }}" class="btn btn-outline-secondary">បោះបង់</a>
                    <button type="submit" class="btn btn-primary-action px-4">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកមុខវិជ្ជា
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStyle(cb) {
    const label = document.getElementById('sub_label_' + cb.value);
    if (!label) return;
    if (cb.checked) {
        label.classList.add('is-selected');
    } else {
        label.classList.remove('is-selected');
    }
}

function toggleAll(select) {
    const checkboxes = document.querySelectorAll('.subject-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = select;
        updateStyle(cb);
    });
}
</script>
@endpush

@endsection
