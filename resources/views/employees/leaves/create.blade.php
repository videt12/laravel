@extends('layouts.app')

@section('title', 'កត់ត្រាច្បាប់ឈប់សម្រាក — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item"><a href="{{ route('staff-leaves.index') }}" class="text-decoration-none text-muted">ច្បាប់ឈប់សម្រាក</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">កត់ត្រាច្បាប់ឈប់សម្រាក</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   HERO BANNER
═══════════════════════════════════════════════════════════════ */
.employee-banner {
    background: linear-gradient(135deg, #e8a838 0%, #c98b1e 100%);
    border-radius: var(--radius-md, 8px);
    padding: 0.55rem 1rem;
    margin-bottom: 0.85rem;
    position: relative;
    overflow: hidden;
    color: #fff;
    box-shadow: 0 1px 4px rgba(232, 168, 56, 0.15);
}
.employee-banner::before {
    content: '';
    position: absolute;
    top: -30px;
    right: -30px;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    pointer-events: none;
}
.employee-banner::after {
    content: '';
    position: absolute;
    bottom: -25px;
    right: 70px;
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.06);
    border-radius: 50%;
    pointer-events: none;
}
.employee-banner .banner-icon {
    width: 30px;
    height: 30px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    color: #fff;
    margin-bottom: 0;
    flex-shrink: 0;
}
.employee-banner h1 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 0.1rem;
    line-height: 1.2;
}
.employee-banner p {
    font-size: 0.72rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
    line-height: 1.2;
}

/* ═══════════════════════════════════════════════════════════════
   FORM CARDS
═══════════════════════════════════════════════════════════════ */
.employee-form-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: 3px solid #e8a838;
    border-radius: var(--radius-md, 8px);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    margin-bottom: 1rem;
}
.card-section-header {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.55rem 0.9rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.card-section-badge {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #e8a838;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 700;
    flex-shrink: 0;
}
.card-section-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    line-height: 1.25;
}
.card-section-subtitle {
    font-size: 0.7rem;
    color: #64748b;
    margin: 0;
    line-height: 1.2;
}
.card-section-body {
    padding: 0.85rem 0.9rem;
}

.form-label-custom {
    font-size: 0.76rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.22rem;
    display: block;
}
.required-star {
    color: #ef4444;
    font-weight: 700;
}
.form-control-custom, .form-select-custom {
    border-color: #cbd5e1;
    font-size: 0.8rem;
    border-radius: var(--radius-sm, 5px);
    color: #1e293b;
    background-color: #fff;
    padding: 0.35rem 0.65rem;
    transition: all 0.15s ease-in-out;
}
.form-control-custom:focus, .form-select-custom:focus {
    border-color: #e8a838;
    box-shadow: 0 0 0 2px rgba(232, 168, 56, 0.18);
    outline: none;
}

/* Duration display card */
.duration-summary-box {
    background: #f0fdfa;
    border: 1px solid #99f6e4;
    border-radius: var(--radius-sm, 6px);
    padding: 0.45rem 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.duration-number {
    font-size: 1.15rem;
    font-weight: 800;
    color: #c98b1e;
    line-height: 1;
}

.form-action-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.55rem 0.9rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    border-radius: 0 0 var(--radius-md, 8px) var(--radius-md, 8px);
}
</style>
@endpush

@section('content')

{{-- HERO BANNER (Compact) --}}
<div class="employee-banner">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="banner-icon"><i class="bi bi-calendar-x"></i></div>
            <div>
                <h1>កត់ត្រាសំណើសុំច្បាប់ឈប់សម្រាក</h1>
                <p>ដាក់ពាក្យស្នើសុំច្បាប់ឈប់សម្រាក ឬកត់ត្រាការឈប់សម្រាកផ្លូវការសម្រាប់បុគ្គលិក។</p>
            </div>
        </div>
        <div>
            <a href="{{ route('staff-leaves.index') }}" class="btn btn-sm text-white" style="background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.3); font-size:0.75rem; padding:0.25rem 0.65rem; border-radius:5px;">
                <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅច្បាប់ឈប់សម្រាក
            </a>
        </div>
    </div>
</div>

{{-- VALIDATION ERRORS --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3 border-0 shadow-sm py-2 px-3" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-octagon-fill text-danger fs-6 mt-1"></i>
            <div>
                <strong class="d-block mb-1" style="font-size:0.8rem;">សូមកែតម្រូវកំហុសឆ្គងខាងក្រោម៖</strong>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li style="font-size:0.75rem;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding:0.75rem;"></button>
    </div>
@endif

<form action="{{ route('staff-leaves.store') }}" method="POST" id="leaveForm" novalidate>
@csrf

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-8 col-md-10" style="max-width: 760px;">
        <div class="employee-form-card">

            {{-- SECTION 1: Staff & Type --}}
            <div class="card-section-header">
                <div class="card-section-badge">1</div>
                <div>
                    <h2 class="card-section-title">ព័ត៌មានបុគ្គលិក និងប្រភេទច្បាប់</h2>
                    <p class="card-section-subtitle">ជ្រើសរើសបុគ្គលិក និងប្រភេទច្បាប់ឈប់សម្រាកដែលត្រូវស្នើសុំ</p>
                </div>
            </div>

            <div class="card-section-body">
                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label-custom">
                            បុគ្គលិក / លោកគ្រូអ្នកគ្រូ <span class="required-star">*</span>
                        </label>
                        <select name="employee_id" id="employee_id" class="form-select form-select-custom @error('employee_id') is-invalid @enderror" required>
                            <option value="">ជ្រើសរើសបុគ្គលិក</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->user->name ?? 'Staff' }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback" style="font-size:0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="leave_type_id" class="form-label-custom">
                            ប្រភេទច្បាប់ឈប់សម្រាក <span class="required-star">*</span>
                        </label>
                        <select name="leave_type_id" id="leave_type_id" class="form-select form-select-custom @error('leave_type_id') is-invalid @enderror" required onchange="updateLeaveInfo()">
                            <option value="">ជ្រើសរើសប្រភេទច្បាប់</option>
                            @foreach($leaveTypes as $type)
                                @php
                                    $khName = $type->khmer_name ?? $type->name;
                                    $daysText = $type->days_allowed ? $type->days_allowed . ' ថ្ងៃ/ឆ្នាំ' : '';
                                    $paidText = $type->is_paid ? 'មានប្រាក់បៀវត្ស' : 'គ្មានប្រាក់បៀវត្ស';
                                    $meta = $daysText ? ($daysText . ' • ' . $paidText) : $paidText;
                                @endphp
                                <option value="{{ $type->id }}" 
                                        data-days="{{ $type->days_allowed }}"
                                        data-paid="{{ $type->is_paid ? 'Paid' : 'Unpaid' }}"
                                        {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $khName }} ({{ $meta }})
                                </option>
                            @endforeach
                        </select>
                        @error('leave_type_id')
                            <div class="invalid-feedback" style="font-size:0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Dates & Duration --}}
            <div class="card-section-header">
                <div class="card-section-badge">2</div>
                <div>
                    <h2 class="card-section-title">កាលបរិច្ឆេទ និងមូលហេតុ</h2>
                    <p class="card-section-subtitle">បញ្ជាក់កាលបរិច្ឆេទចាប់ផ្តើម បញ្ចប់ និងមូលហេតុនៃការឈប់សម្រាក</p>
                </div>
            </div>

            <div class="card-section-body">
                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label-custom">
                            កាលបរិច្ឆេទចាប់ផ្តើម <span class="required-star">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date"
                               class="form-control form-control-custom @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date', date('Y-m-d')) }}" required onchange="calculateDays()">
                        @error('start_date')
                            <div class="invalid-feedback" style="font-size:0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label-custom">
                            កាលបរិច្ឆេទបញ្ចប់ <span class="required-star">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date"
                               class="form-control form-control-custom @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date', date('Y-m-d')) }}" required onchange="calculateDays()">
                        @error('end_date')
                            <div class="invalid-feedback" style="font-size:0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-2">
                        <div class="duration-summary-box">
                            <div class="d-flex align-items-center gap-2">
                                <div class="banner-icon mb-0" style="background:#e8a838; width:28px; height:28px; border-radius:5px; display:inline-flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-calendar2-range text-white" style="font-size:0.8rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size:0.78rem;">រយៈពេលឈប់សរុប</div>
                                    <div class="text-muted" style="font-size:0.7rem;" id="durationSub">គណនាចំនួនថ្ងៃប្រតិទិនសរុប</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="duration-number" id="calcDays">1</span>
                                <span class="text-muted fw-medium ms-1" style="font-size:0.75rem;" id="calcDaysLabel">ថ្ងៃ</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="reason" class="form-label-custom">
                            មូលហេតុ / កំណត់សម្គាល់ <span class="required-star">*</span>
                        </label>
                        <textarea name="reason" id="reason" rows="2"
                                  class="form-control form-control-custom @error('reason') is-invalid @enderror"
                                  placeholder="សូមបញ្ជាក់មូលហេតុច្បាស់លាស់ ឬព័ត៌មានលម្អិតសម្រាប់ការសុំច្បាប់នេះ..." required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback" style="font-size:0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Status --}}
            <div class="card-section-header">
                <div class="card-section-badge">3</div>
                <div>
                    <h2 class="card-section-title">ការសម្រេចចិត្តអនុម័ត</h2>
                    <p class="card-section-subtitle">កំណត់ស្ថានភាពដំបូង និងការអនុម័តច្បាប់</p>
                </div>
            </div>

            <div class="card-section-body">
                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label for="status" class="form-label-custom">
                            ស្ថានភាព <span class="required-star">*</span>
                        </label>
                        <select name="status" id="status" class="form-select form-select-custom @error('status') is-invalid @enderror" required>
                            <option value="Pending" {{ old('status', 'Pending') == 'Pending' ? 'selected' : '' }}>⏳ រង់ចាំការពិនិត្យ (Pending)</option>
                            <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>✅ បានអនុម័ត (Approved)</option>
                            <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>❌ បដិសេធ (Rejected)</option>
                        </select>
                        <div class="form-text text-muted mt-1" style="font-size:0.7rem;">ប្រសិនបើជ្រើសរើស "បានអនុម័ត" គណនីអ្នកគ្រប់គ្រងបច្ចុប្បន្ននឹងត្រូវបានកត់ត្រាជាអ្នកអនុម័ត។</div>
                        @error('status')
                            <div class="invalid-feedback" style="font-size:0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="form-action-footer">
                <div class="d-flex align-items-center gap-1.5 text-muted" style="font-size:0.73rem;">
                    <i class="bi bi-info-circle text-primary"></i>
                    <span>ប្រអប់ដែលមានសញ្ញា <span class="required-star">*</span> គឺតម្រូវឱ្យបំពេញជាចាំបាច់</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('staff-leaves.index') }}" class="btn btn-sm btn-light border py-1 px-2.5" style="font-size:0.75rem;">
                        <i class="bi bi-x me-1"></i>បោះបង់
                    </a>
                    <button type="submit" class="btn btn-sm text-white py-1 px-3" style="background:#e8a838; border-color:#e8a838; font-size:0.75rem;">
                        <i class="bi bi-check-lg me-1"></i>រក្សាទុកសំណើសុំច្បាប់
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

</form>

@push('scripts')
<script>
function calculateDays() {
    const s = document.getElementById('start_date').value;
    const e = document.getElementById('end_date').value;
    if (!s || !e) return;

    const startDate = new Date(s);
    const endDate = new Date(e);

    if (endDate >= startDate) {
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('calcDays').textContent = diffDays;
        document.getElementById('calcDaysLabel').textContent = 'ថ្ងៃ';
        document.getElementById('durationSub').textContent = `ពី ${s} ដល់ ${e} (${diffDays} ថ្ងៃ)`;
    } else {
        document.getElementById('calcDays').textContent = '—';
        document.getElementById('durationSub').textContent = 'កាលបរិច្ឆេទបញ្ចប់មិនអាចមុនកាលបរិច្ឆេទចាប់ផ្តើមបានទេ';
    }
}

function updateLeaveInfo() {
    const sel = document.getElementById('leave_type_id');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.value) {
        const days = opt.getAttribute('data-days');
        const paid = opt.getAttribute('data-paid');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    calculateDays();

    const form = document.getElementById('leaveForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');
            if (btn && form.checkValidity()) {
                setTimeout(() => {
                    btn.disabled = true;
                }, 0);
            }
        });
    }
});
</script>
@endpush

@endsection
