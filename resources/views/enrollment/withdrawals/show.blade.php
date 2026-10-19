@extends('layouts.app')

@section('title', 'Withdrawal Details — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការចុះឈ្មោះ</li>
        <li class="breadcrumb-item"><a href="{{ route('withdrawals.index') }}" class="text-decoration-none text-muted">ការឈប់រៀន</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ព័ត៌មានលម្អិត</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
:root {
    --gold-primary: #D4AF37;
    --gold-hover: #C49E2B;
    --gold-light: #FBF7EB;
    --gold-border: #F1E3B8;
    --navy-text: #172033;
    --secondary-text: #64748B;
    --border-color: #E5E7EB;
}

.detail-card {
    background: #ffffff;
    border: 1px solid var(--border-color);
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    overflow: hidden;
}

.detail-card-header {
    padding: 0.95rem 1.25rem;
    border-bottom: 1px solid var(--border-color);
    background: #F8FAFC;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.detail-card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--navy-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    line-height: 1.4;
}

.detail-card-body {
    padding: 1.25rem 1.4rem;
}

.avatar-large {
    width: 76px;
    height: 76px;
    border-radius: 50%;
    background: #F1F5F9;
    color: #334155;
    font-weight: 800;
    font-size: 1.6rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #E2E8F0;
    overflow: hidden;
}
.avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.info-label {
    font-size: 0.8125rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--secondary-text);
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.info-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--navy-text);
    margin: 0;
    line-height: 1.5;
}

.btn-gold {
    background: var(--gold-primary);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.52rem 1.2rem;
    border-radius: 8px;
    border: 1px solid var(--gold-hover);
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    transition: all 0.2s ease;
    text-decoration: none;
}
.btn-gold:hover {
    background: var(--gold-hover);
    color: #ffffff;
}

.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.32rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.78rem;
    font-weight: 600;
}
.badge-status-completed { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }
.badge-status-pending   { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
.badge-status-cancelled { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }

.data-row {
    padding: 0.65rem 0;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.88rem;
}
.data-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.data-label {
    color: var(--secondary-text);
    font-weight: 500;
}
.data-val {
    font-weight: 600;
    color: var(--navy-text);
    text-align: right;
}
</style>
@endpush

@section('content')

@php
    $student = $withdrawal->student;
    $profile = $student?->profile;
    $address = $student?->address;
    $guardians = $student?->guardians ?? collect();

    $studentName = $student?->user?->name ?? 'Unknown Student';
    $khmerName = trim(($profile?->first_name_khmer ?? '') . ' ' . ($profile?->last_name_khmer ?? ''));
    $admissionNo = $student?->admission_number ?? 'No ID';

    // Calculate age if DOB exists
    $ageText = null;
    if ($profile?->dob) {
        $age = \Carbon\Carbon::parse($profile->dob)->age;
        $ageText = "({$age} ឆ្នាំ / yrs)";
    }

    // Address text
    $addressParts = array_filter([
        $address?->village ? 'ភូមិ ' . $address->village : null,
        $address?->commune ? 'ឃុំ/សង្កាត់ ' . $address->commune : null,
        $address?->district ? 'ស្រុក/ខណ្ឌ ' . $address->district : null,
        $address?->province ? 'ខេត្ត/រាជធានី ' . $address->province : null,
        $address?->address_line_1,
    ]);
    $fullAddress = !empty($addressParts) ? implode(', ', $addressParts) : null;

    // Resolve Last Enrolled Class & Academic Year
    $lastClassName = null;
    $lastYearName = null;
    if ($student) {
        if ($student->schoolClass) {
            $lastClassName = ($student->schoolClass->grade?->name ?? '') . ($student->schoolClass->section?->name ? ' — ' . $student->schoolClass->section->name : '');
        }
        $lastYearName = $student->academicYear?->name;

        if (!$lastClassName && $student->enrollments && $student->enrollments->isNotEmpty()) {
            $latestEnr = $student->enrollments->sortByDesc('enrollment_date')->first();
            if ($latestEnr) {
                $enrClass = $latestEnr->enrollmentDetail?->class;
                if ($enrClass) {
                    $lastClassName = ($enrClass->grade?->name ?? '') . ($enrClass->section?->name ? ' — ' . $enrClass->section->name : '');
                }
                $lastYearName = $lastYearName ?: $latestEnr->academicYear?->name;
            }
        }
    }

    // Initials
    $nameParts = explode(' ', trim($studentName));
    $initials = '';
    if (count($nameParts) >= 2) {
        $initials = strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr(end($nameParts), 0, 1));
    } else {
        $initials = strtoupper(mb_substr($studentName, 0, 2));
    }

    $status = strtolower($withdrawal->status ?? '');
    $statusClass = 'badge-status-pending';
    if (in_array($status, ['completed', 'approved'])) {
        $statusClass = 'badge-status-completed';
    } elseif (in_array($status, ['cancelled', 'rejected'])) {
        $statusClass = 'badge-status-cancelled';
    }
@endphp

{{-- ═══════════════════════════════════════════════════════════════════
     HEADER & ACTION TOOLBAR
═══════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h4 fw-bold mb-1 text-dark d-flex align-items-center gap-2">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3" style="width:34px;height:34px;background:#FBF7EB;color:#D4AF37;border:1px solid #F1E3B8;font-size:1.1rem;">
                <i class="bi bi-person-dash"></i>
            </span>
            Withdrawal Details (ព័ត៌មានការឈប់រៀន)
        </h1>
        <p class="text-muted small mb-0">Record #{{ $withdrawal->id }} &bull; Recorded on {{ $withdrawal->created_at ? $withdrawal->created_at->format('M d, Y') : '' }}</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('withdrawals.index') }}" class="btn btn-sm btn-light border px-3">
            <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ក្រោយ
        </a>
        @if($student)
            <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-light border px-3" target="_blank" title="មើលព័ត៌មានរូបសង្ខេបសិស្ស">
                <i class="bi bi-person-badge me-1 text-primary"></i>ព័ត៌មានរូបសង្ខេបសិស្ស
            </a>
        @endif
        <a href="{{ route('withdrawals.edit', $withdrawal) }}" class="btn btn-sm btn-outline-secondary px-3">
            <i class="bi bi-pencil me-1"></i>កែសម្រួល
        </a>
        <a href="{{ route('enrollments.create') }}" class="btn-gold shadow-sm" title="ចុះឈ្មោះចូលរៀនឡើងវិញសម្រាប់សិស្សនេះ">
            <i class="bi bi-arrow-repeat"></i>
            <span>ចុះឈ្មោះរៀនឡើងវិញ</span>
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT COLUMN: STUDENT QUICK IDENTITY --}}
    <div class="col-lg-4">
        {{-- Profile Identity Card --}}
        <div class="detail-card text-center p-4 mb-4">
            <div class="avatar-large mx-auto mb-3">
                @if($profile?->photo)
                    <img src="{{ asset('storage/' . $profile->photo) }}" alt="{{ $studentName }}">
                @else
                    {{ $initials ?: 'ST' }}
                @endif
            </div>

            <h5 class="fw-bold mb-1 text-dark">{{ $studentName }}</h5>
            @if($khmerName)
                <div class="text-primary fw-semibold mb-2" style="font-size:1rem;">
                    {{ $khmerName }}
                </div>
            @endif

            <div class="d-inline-flex align-items-center gap-2 mb-3">
                <span class="badge bg-light text-secondary border px-3 py-1" style="font-family: monospace; font-size:0.82rem;">
                    ID: {{ $admissionNo }}
                </span>
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size:0.75rem;">
                    <i class="bi bi-person-x me-1"></i>Withdrawn
                </span>
            </div>

            {{-- Quick Summary List --}}
            <div class="pt-3 border-top text-start">
                <div class="data-row">
                    <span class="data-label"><i class="bi bi-gender-ambiguous me-1"></i>ភេទ (Gender)</span>
                    <span class="data-val">{{ $profile?->gender ?? '—' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label"><i class="bi bi-telephone me-1"></i>ទូរស័ព្ទ (Phone)</span>
                    <span class="data-val">{{ $profile?->phone ?: ($student?->user?->phone ?? '—') }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label"><i class="bi bi-envelope me-1"></i>Email</span>
                    <span class="data-val" style="font-size:0.8rem;">{{ $student?->user?->email ?? '—' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label"><i class="bi bi-calendar-date me-1"></i>Admission</span>
                    <span class="data-val">{{ $student?->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') : '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Parent / Guardian Card --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <h6 class="detail-card-title">
                    <i class="bi bi-people text-secondary"></i>
                    ព័ត៌មានអាណាព្យាបាល (Guardian)
                </h6>
            </div>
            <div class="detail-card-body">
                @if($guardians->isNotEmpty())
                    @foreach($guardians as $g)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="fw-bold text-dark mb-1">
                                {{ $g->user->name ?? 'Guardian' }}
                                @if(isset($g->pivot->relationship))
                                    <span class="badge bg-light text-muted border ms-1" style="font-size:0.72rem;">{{ $g->pivot->relationship }}</span>
                                @endif
                            </div>
                            <div class="text-muted small mb-1">
                                <i class="bi bi-telephone me-1"></i> {{ $g->user->phone ?? 'No phone' }}
                            </div>
                            @if($g->occupation)
                                <div class="text-muted small">
                                    <i class="bi bi-briefcase me-1"></i> {{ $g->occupation }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-muted small fst-italic">
                        <i class="bi bi-info-circle me-1"></i> មិនមានព័ត៌មានអាណាព្យាបាលកត់ត្រាឡើយ (No guardian recorded)
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: COMPREHENSIVE DETAILS --}}
    <div class="col-lg-8">
        {{-- SECTION 1: WITHDRAWAL DETAILS --}}
        <div class="detail-card mb-4">
            <div class="detail-card-header">
                <h6 class="detail-card-title">
                    <i class="bi bi-file-earmark-text text-danger"></i>
                    ព័ត៌មាននៃការឈប់រៀន (Withdrawal Record)
                </h6>
                <span class="badge-status {{ $statusClass }}">
                    <span class="badge-status-dot" style="width:7px;height:7px;border-radius:50%;display:inline-block;background:currentColor"></span>
                    {{ $withdrawal->status ?? 'Pending' }}
                </span>
            </div>
            <div class="detail-card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#FEF2F2; border:1px solid #FECACA;">
                            <div class="info-label text-danger"><i class="bi bi-calendar-event me-1"></i>កាលបរិច្ឆេទឈប់ (Withdrawal Date)</div>
                            <div class="info-value fs-6 text-danger">
                                {{ $withdrawal->withdrawal_date ? \Carbon\Carbon::parse($withdrawal->withdrawal_date)->format('F d, Y') : '—' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#F8FAFC; border:1px solid #E2E8F0;">
                            <div class="info-label"><i class="bi bi-check-circle me-1"></i>ស្ថានភាព (Status)</div>
                            <div class="info-value fs-6">
                                {{ $withdrawal->status ?? 'Pending' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="info-label mb-2">មូលហេតុនៃការឈប់រៀន (Reason for Withdrawal)</div>
                    @if($withdrawal->reason)
                        <div class="p-3 rounded-3" style="background:#FFFBEB; border:1px solid #FDE68A; color:#92400E; font-size:0.92rem; line-height:1.6;">
                            <i class="bi bi-chat-quote-fill me-2 opacity-75"></i>
                            {{ $withdrawal->reason }}
                        </div>
                    @else
                        <div class="p-3 bg-light rounded-3 text-muted fst-italic">
                            No detailed reason provided for this withdrawal.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SECTION 2: ACADEMIC TRACK AT WITHDRAWAL --}}
        <div class="detail-card mb-4">
            <div class="detail-card-header">
                <h6 class="detail-card-title">
                    <i class="bi bi-mortarboard-fill text-primary"></i>
                    ព័ត៌មានសិក្សាចុងក្រោយមុនពេលឈប់ (Academic Track at Withdrawal)
                </h6>
                <span class="badge bg-primary bg-opacity-10 text-primary border px-2 py-1" style="font-size:0.72rem;">
                    ថ្នាក់ចុងក្រោយពេលឈប់
                </span>
            </div>
            <div class="detail-card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#EFF6FF; border:1px solid #BFDBFE;">
                            <div class="info-label text-primary"><i class="bi bi-building me-1"></i>ថ្នាក់រៀនចុងក្រោយ (Last Enrolled Class)</div>
                            <div class="info-value fs-6 text-primary">
                                {{ $lastClassName ?: 'មិនទាន់កំណត់ (Not assigned)' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#F8FAFC; border:1px solid #E2E8F0;">
                            <div class="info-label"><i class="bi bi-calendar-check me-1"></i>ឆ្នាំសិក្សា (Academic Year)</div>
                            <div class="info-value fs-6">
                                {{ $lastYearName ?: '—' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($profile?->previous_school)
                    <div class="p-2 px-3 rounded-3 bg-light border text-muted small">
                        <i class="bi bi-arrow-return-right me-1 text-secondary"></i>
                        សាលាចាស់ពីមុន៖ <strong class="text-dark">{{ $profile->previous_school }}</strong>
                    </div>
                @endif
            </div>
        </div>

        {{-- SECTION 3: FULL PERSONAL DETAILS --}}
        <div class="detail-card">
            <div class="detail-card-header">
                <h6 class="detail-card-title">
                    <i class="bi bi-person-vcard text-secondary"></i>
                    ព័ត៌មានផ្ទាល់ខ្លួនលម្អិតរបស់សិស្ស (Detailed Personal Information)
                </h6>
            </div>
            <div class="detail-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="info-label">ឈ្មោះជាភាសាខ្មែរ (Khmer Name)</div>
                        <div class="info-value">{{ $khmerName ?: '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">ឈ្មោះជាឡាតាំង (Latin Name)</div>
                        <div class="info-value">{{ $studentName }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">ភេទ (Gender)</div>
                        <div class="info-value">{{ $profile?->gender ?? '—' }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-label">ថ្ងៃខែឆ្នាំកំណើត (Date of Birth)</div>
                        <div class="info-value">
                            {{ $profile?->dob ? \Carbon\Carbon::parse($profile->dob)->format('M d, Y') : '—' }}
                            @if($ageText)
                                <small class="text-muted fw-normal">{{ $ageText }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">សញ្ជាតិ (Nationality)</div>
                        <div class="info-value">{{ $profile?->nationality ?? 'ខ្មែរ (Cambodian)' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-label">ប្រភេទឈាម (Blood Group)</div>
                        <div class="info-value">
                            @if($profile?->blood_group)
                                <span class="badge bg-danger bg-opacity-10 text-danger border">{{ $profile->blood_group }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-label">អត្តសញ្ញាណប័ណ្ណ (National ID)</div>
                        <div class="info-value" style="font-family:monospace;">{{ $profile?->national_id ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">លេខទូរស័ព្ទ (Phone Number)</div>
                        <div class="info-value">{{ $profile?->phone ?: ($student?->user?->phone ?? '—') }}</div>
                    </div>

                    {{-- Address --}}
                    <div class="col-12 mt-2 pt-2 border-top">
                        <div class="info-label"><i class="bi bi-geo-alt me-1 text-danger"></i>អាសយដ្ឋានបច្ចុប្បន្ន (Address)</div>
                        <div class="info-value fw-normal text-muted" style="font-size:0.9rem;">
                            {{ $fullAddress ?: ($address?->permanent_address ?? 'មិនមានអាសយដ្ឋានកត់ត្រាឡើយ (No address recorded)') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
