@extends('layouts.app')

@section('title', 'កែប្រែការកំណត់មុខវិជ្ជាថ្នាក់រៀន — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item"><a href="{{ route('academics.index', ['tab' => 'class-subjects']) }}" class="text-decoration-none text-muted">មុខវិជ្ជាតាមថ្នាក់</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កែប្រែ</li>
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
</style>
@endpush

@section('content')

{{-- PAGE BANNER --}}
<div class="page-banner">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1>កែប្រែការកំណត់មុខវិជ្ជាថ្នាក់រៀន</h1>
            <p>ធ្វើបច្ចុប្បន្នភាពថ្នាក់រៀន និងមុខវិជ្ជាសិក្សា</p>
        </div>
        <div>
            <a href="{{ route('class-subjects.show', $classSubject) }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <form action="{{ route('class-subjects.update', $classSubject) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <div class="form-section-head">
                        <div class="form-section-head-bar"></div>
                        <span class="form-section-head-label">ព័ត៌មានមុខវិជ្ជា និងថ្នាក់រៀន</span>
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
                            ថ្នាក់រៀនគោលដៅ <span class="text-danger">*</span>
                        </label>
                        <select name="class_id" id="class_id" class="form-select fc @error('class_id') is-invalid @enderror" required>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id', $classSubject->class_id) == $c->id ? 'selected' : '' }}>
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

                    <div class="mb-4">
                        <label for="subject_id" class="form-label fw-bold small text-dark">
                            មុខវិជ្ជាសិក្សា <span class="text-danger">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" class="form-select fc @error('subject_id') is-invalid @enderror" required>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id', $classSubject->subject_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} [{{ $s->code }}] {{ $s->type ? '(' . $s->type . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="field-hint">ជ្រើសរើសមុខវិជ្ជាដែលត្រូវរៀននៅក្នុងថ្នាក់នេះ។</div>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="p-3 bg-light border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('class-subjects.show', $classSubject) }}" class="btn btn-outline-secondary">បោះបង់</a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
