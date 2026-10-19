@extends('layouts.app')

@section('title', ($student->studentProfile?->first_name ?? 'Student') . ' — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Students</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $student->admission_number }}</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
    /* Compact, elegant styling for Student Details */
    .student-profile-hero {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .hero-avatar {
        width: 50px !important;
        height: 50px !important;
        min-width: 50px !important;
        font-size: 1.15rem !important;
        border-radius: 50% !important;
        border: 2px solid #f1f5f9 !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05) !important;
    }
    .hero-name {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        margin-bottom: 0.15rem !important;
        line-height: 1.2;
    }
    .hero-meta {
        font-size: 0.74rem !important;
        color: #64748b !important;
    }
    .data-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        margin-bottom: 0.75rem !important;
        overflow: hidden;
    }
    .data-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #edf2f7;
        padding: 0.45rem 0.85rem !important;
        font-size: 0.78rem !important;
        font-weight: 600 !important;
        color: #1e293b !important;
    }
    .data-card-header i {
        font-size: 0.85rem;
        color: #3b82f6;
    }
    .detail-grid {
        padding: 0.45rem 0.75rem !important;
    }
    .detail-row {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 0.5rem !important;
        padding: 0.25rem 0.4rem !important;
        border-radius: 5px !important;
        border-bottom: 1px solid #f8fafc !important;
        transition: background-color 0.12s ease;
    }
    .detail-row:hover {
        background-color: #f8fafc !important;
    }
    .detail-label {
        font-size: 0.7rem !important;
        font-weight: 600 !important;
        color: #64748b !important;
        text-transform: uppercase !important;
        letter-spacing: 0.25px !important;
        min-width: 115px !important;
        flex-shrink: 0 !important;
        line-height: 1.2 !important;
    }
    .detail-value {
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        line-height: 1.35 !important;
    }
    .guardian-item-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 0.55rem 0.75rem !important;
        background: #fafbfc !important;
        transition: all 0.15s ease;
    }
    .guardian-item-card:hover {
        border-color: #cbd5e1 !important;
        background: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04) !important;
    }
    .badge {
        font-size: 0.7rem !important;
        padding: 0.18rem 0.45rem !important;
        font-weight: 500 !important;
    }
    .status-badge {
        font-size: 0.7rem !important;
        padding: 0.18rem 0.5rem !important;
    }
</style>
@endpush

@section('content')

@php
    $firstName  = $student->studentProfile?->first_name ?? '';
    $lastName   = $student->studentProfile?->last_name ?? '';
    $firstNameKh = $student->studentProfile?->first_name_khmer ?? '';
    $lastNameKh  = $student->studentProfile?->last_name_khmer ?? '';
    $khmerFullName = trim($lastNameKh . ' ' . $firstNameKh);
    $latinFullName = trim($firstName . ' ' . $lastName);
    $name = $khmerFullName ?: ($latinFullName ?: ($student->user?->name ?? 'Unknown'));
    $initials   = strtoupper(substr($firstName ?: ($lastNameKh ?: ($student->user?->name ?? 'S')), 0, 1) . (strlen($lastName) > 0 ? substr($lastName, 0, 1) : ''));
    $statusName = $student->studentStatus?->name ?? 'Active';
@endphp

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert" style="border-radius: var(--radius-md);">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert" style="border-radius: var(--radius-md);">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Validation Errors Modal / Alert --}}
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="border-radius: var(--radius-md);">
        <h6 class="alert-heading fw-bold mb-2"><i class="bi bi-exclamation-circle me-1"></i>Please correct the following errors:</h6>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Student Profile Hero --}}
<div class="student-profile-hero mb-3">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        {{-- Avatar --}}
        @if($student->studentProfile && $student->studentProfile->photo)
            <img src="{{ Storage::url($student->studentProfile->photo) }}" class="hero-avatar" style="object-fit: cover;" alt="Avatar">
        @else
            <div class="hero-avatar">{{ $initials }}</div>
        @endif

        {{-- Identity --}}
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <h1 class="hero-name d-flex align-items-baseline gap-2 flex-wrap mb-1">
                        <span>{{ $name }}</span>
                        @if($khmerFullName && $latinFullName && $khmerFullName !== $latinFullName)
                            <span class="text-muted fw-normal" style="font-size: 0.88rem;">({{ $latinFullName }})</span>
                        @endif
                    </h1>
                    <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                        @if($student->admission_number)
                            <span class="hero-meta">
                                <i class="bi bi-card-text me-1"></i>{{ $student->admission_number }}
                            </span>
                        @endif
                        @if($student->enrollment_date)
                            <span class="hero-meta">
                                <i class="bi bi-calendar3 me-1"></i>Enrolled {{ \Carbon\Carbon::parse($student->enrollment_date)->format('d M Y') }}
                            </span>
                        @endif
                        @if(($studentType ?? 'new') === 'returning')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Returning Student</span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">New Student</span>
                        @endif

                        @if($student->study_mode === 'part_time')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                <i class="bi bi-clock me-1"></i>ក្រៅម៉ោង (Part-Time)
                            </span>
                        @elseif($student->study_mode === 'dual')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                <i class="bi bi-arrow-repeat me-1"></i>ពេញម៉ោង & ក្រៅម៉ោង (Dual Study)
                            </span>
                        @else
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">
                                <i class="bi bi-mortarboard me-1"></i>ពេញម៉ោង (Full-Time)
                            </span>
                        @endif

                        @if(($paymentStatus ?? 'unpaid') === 'paid')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Paid</span>
                        @elseif(($paymentStatus ?? 'unpaid') === 'partial')
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">Partial</span>
                        @elseif(($paymentStatus ?? 'unpaid') === 'overdue')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Overdue</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Unpaid</span>
                        @endif

                        <span class="status-badge status-active">{{ $statusName }}</span>
                        @if(auth()->user()?->canAccess('students.edit'))
                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill shadow-none" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#updateStatusModal" title="ផ្លាស់ប្តូរស្ថានភាពសិស្ស">
                            <i class="bi bi-pencil-square me-1"></i>ប្តូរស្ថានភាព
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 align-items-center flex-shrink-0">
                    @if(auth()->user()?->canAccess(['finance.create', 'finance']))
                    <a href="{{ route('student-payments.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-credit-card me-1"></i>Record Payment
                    </a>
                    @endif
                    @if(auth()->user()?->canAccess('students.edit'))
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary-action btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Student
                    </a>
                    @endif
                    <div class="dropdown">
                        <button class="btn btn-ghost btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            More
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1" style="font-size:.875rem">
                            @if(auth()->user()?->canAccess('students.edit'))
                            <li>
                                <button type="button" class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                    <i class="bi bi-file-earmark-arrow-up me-2 text-primary"></i>Upload Document
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#attachGuardianModal">
                                    <i class="bi bi-person-plus me-2 text-success"></i>Attach Guardian
                                </button>
                            </li>
                            <li>
                                <a href="{{ route('student-notes.create', ['student_id' => $student->id]) }}" class="dropdown-item py-2">
                                    <i class="bi bi-journal-plus me-2 text-info"></i>Add Note
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @endif
                            @if(auth()->user()?->canAccess(['students.edit', 'students.create']))
                            <li>
                                <a href="{{ route('students.id-card', $student) }}" target="_blank" class="dropdown-item py-2">
                                    <i class="bi bi-person-badge me-2 text-primary"></i>បោះពុម្ពកាតសិស្ស (Print ID Card)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('students.certificate', $student) }}" target="_blank" class="dropdown-item py-2">
                                    <i class="bi bi-award me-2 text-warning"></i>លិខិតបញ្ជាក់ការសិក្សា (Certificate)
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()?->canAccess('students.delete'))
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <button type="button" class="dropdown-item py-2 text-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal">
                                    <i class="bi bi-trash me-2"></i>Delete Student
                                </button>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- LEFT COLUMN --}}
    <div class="col-xl-8">
        
        {{-- 1. Basic Information --}}
        <div class="data-card mb-3">
            <div class="data-card-header">
                <span class="fw-semibold"><i class="bi bi-person me-2"></i>Basic Information</span>
            </div>
            <div class="detail-grid">
                <div class="row g-2">
                    <div class="col-md-6 detail-row"><span class="detail-label">Last Name (Khmer)</span><span class="detail-value">{{ $student->studentProfile?->last_name_khmer ?? '—' }}</span></div>
                    <div class="col-md-6 detail-row"><span class="detail-label">First Name (Khmer)</span><span class="detail-value">{{ $student->studentProfile?->first_name_khmer ?? '—' }}</span></div>
                    <div class="col-md-6 detail-row"><span class="detail-label">First Name</span><span class="detail-value">{{ $student->studentProfile?->first_name ?? '—' }}</span></div>
                    <div class="col-md-6 detail-row"><span class="detail-label">Last Name</span><span class="detail-value">{{ $student->studentProfile?->last_name ?? '—' }}</span></div>
                    <div class="col-md-4 detail-row"><span class="detail-label">Gender</span><span class="detail-value">{{ $student->studentProfile?->gender ?? '—' }}</span></div>
                    <div class="col-md-4 detail-row"><span class="detail-label">Date of Birth</span><span class="detail-value">{{ $student->studentProfile?->dob ? \Carbon\Carbon::parse($student->studentProfile->dob)->format('d M Y') : '—' }}</span></div>
                    <div class="col-md-4 detail-row"><span class="detail-label">Nationality</span><span class="detail-value">{{ $student->studentProfile?->nationality ?? '—' }}</span></div>
                </div>
            </div>
        </div>

        {{-- 2. Contact & Address --}}
        <div class="data-card mb-3">
            <div class="data-card-header">
                <span class="fw-semibold"><i class="bi bi-geo-alt me-2"></i>Contact & Address</span>
            </div>
            <div class="detail-grid">
                <div class="row g-2">
                    <div class="col-md-6 detail-row"><span class="detail-label">Email</span><span class="detail-value">{{ $student->user?->email ?? '—' }}</span></div>
                    <div class="col-md-6 detail-row"><span class="detail-label">Phone</span><span class="detail-value">{{ $student->studentProfile?->phone ?? '—' }}</span></div>
                    <div class="col-12 detail-row"><span class="detail-label">លេខផ្លូវ</span><span class="detail-value">{{ $student->studentAddresse?->address_line_1 ?? '—' }}</span></div>
                    <div class="col-md-3 detail-row"><span class="detail-label">ភូមិ</span><span class="detail-value">{{ $student->studentAddresse?->village ?? '—' }}</span></div>
                    <div class="col-md-3 detail-row"><span class="detail-label">ឃុំ / សង្កាត់</span><span class="detail-value">{{ $student->studentAddresse?->commune ?? '—' }}</span></div>
                    <div class="col-md-3 detail-row"><span class="detail-label">ស្រុក / ខណ្ឌ</span><span class="detail-value">{{ $student->studentAddresse?->district ?? '—' }}</span></div>
                    <div class="col-md-3 detail-row"><span class="detail-label">រាជធានី / ខេត្ត</span><span class="detail-value">{{ $student->studentAddresse?->province ?? '—' }}</span></div>
                </div>
            </div>
        </div>

        {{-- 3. Medical & Health Record --}}
        <div class="data-card mb-3">
            <div class="data-card-header">
                <span class="fw-semibold"><i class="bi bi-heart-pulse me-2"></i>ព័ត៌មានវេជ្ជសាស្ត្រ និង សុខភាព</span>
            </div>
            <div class="detail-grid">
                <div class="row g-2">
                    <div class="col-md-6 detail-row"><span class="detail-label">អាឡែហ្ស៊ី</span><span class="detail-value text-break">{{ $student->studentMedicalRecord?->allergies ?: 'មិនមានចុះ' }}</span></div>
                    <div class="col-md-6 detail-row"><span class="detail-label">ជំងឺរ៉ាំរ៉ៃ</span><span class="detail-value text-break">{{ $student->studentMedicalRecord?->chronic_diseases ?: 'មិនមានចុះ' }}</span></div>
                    <div class="col-md-6 detail-row"><span class="detail-label">ថ្នាំប្រើប្រាស់រៀងរាល់ថ្ងៃ</span><span class="detail-value text-break">{{ $student->studentMedicalRecord?->regular_medications ?: 'មិនមានចុះ' }}</span></div>
                    <div class="col-md-6 detail-row"><span class="detail-label">តម្រូវការពិសេស</span><span class="detail-value">{{ $student->studentProfile?->special_needs ?: 'មិនមាន' }}</span></div>
                    <div class="col-12 detail-row"><span class="detail-label">ព័ត៌មានវេជ្ជសាស្ត្រ</span><span class="detail-value text-break">{{ $student->studentProfile?->medical_information ?: 'មិនមានព័ត៌មានបន្ថែម' }}</span></div>
                    <div class="col-12 detail-row"><span class="detail-label">កំណត់ចំណាំ</span><span class="detail-value text-break">{{ $student->studentProfile?->notes ?: '—' }}</span></div>
                </div>
            </div>
        </div>

        {{-- 4. Student Documents --}}
        <div class="data-card mb-3">
            <div class="data-card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold"><i class="bi bi-files me-2"></i>Student Documents</span>
                    <span class="badge bg-light text-dark border">{{ $student->student_documents->count() }}</span>
                </div>
                @if(auth()->user()?->canAccess('students.edit'))
                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i class="bi bi-upload me-1"></i>Upload Document
                </button>
                @endif
            </div>
            <div class="p-0">
                @if($student->student_documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: .8125rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-2">Document Type</th>
                                    <th class="py-2">File Name</th>
                                    <th class="py-2">Uploaded At</th>
                                    <th class="text-end pe-3 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->student_documents as $doc)
                                <tr>
                                    <td class="ps-3 fw-medium text-dark">
                                        <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                        {{ $doc->type_label ?? $doc->document_type }}
                                    </td>
                                    <td>
                                        <span class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                            {{ $doc->file_name ?? basename($doc->document_path) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $doc->created_at ? $doc->created_at->format('d M Y') : '—' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ Storage::url($doc->document_path) }}" target="_blank" download class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.75rem;" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            @if(auth()->user()?->canAccess('students.edit'))
                                            <form action="{{ route('students.documents.destroy', [$student->id, $doc->id]) }}" method="POST" onsubmit="return confirm('Delete this document?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3 text-muted" style="font-size:.8125rem">
                        <i class="bi bi-folder-x fs-4 d-block mb-1 text-light-subtle"></i>
                        No documents uploaded for this student yet.
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->user()?->canAccess(['finance.view', 'finance']))
        {{-- 5. Finance & Fee History --}}
        <div class="data-card mb-3">
            <div class="data-card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold"><i class="bi bi-wallet2 me-2"></i>Finance & Fee History</span>
                    <span class="badge bg-light text-dark border">{{ $invoices->count() }} Invoices &bull; {{ $payments->count() }} Payments</span>
                </div>
                @if(auth()->user()?->canAccess(['finance.create', 'finance']))
                <div class="d-flex gap-2">
                    <a href="{{ route('student-invoices.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;">
                        <i class="bi bi-plus-lg me-1"></i>New Invoice
                    </a>
                    <a href="{{ route('student-payments.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-primary-action py-1 px-2" style="font-size: 0.78rem;">
                        <i class="bi bi-credit-card me-1"></i>Record Payment
                    </a>
                </div>
                @endif
            </div>

            <div class="p-3">
                {{-- Financial Overview Strip --}}
                <div class="row g-2 mb-3 text-center">
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded bg-light-subtle">
                            <span class="text-muted d-block small" style="font-size:.75rem">Total Invoiced</span>
                            <span class="fw-bold fs-6 text-dark">${{ number_format($totalInvoiced, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded bg-light-subtle">
                            <span class="text-muted d-block small" style="font-size:.75rem">Total Paid</span>
                            <span class="fw-bold fs-6 text-success">${{ number_format($totalPaid, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded bg-light-subtle">
                            <span class="text-muted d-block small" style="font-size:.75rem">Remaining Balance</span>
                            <span class="fw-bold fs-6 {{ $outstandingBalance > 0 ? 'text-danger' : 'text-dark' }}">${{ number_format($outstandingBalance, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="p-2 border rounded bg-light-subtle">
                            <span class="text-muted d-block small" style="font-size:.75rem">Last Payment</span>
                            <span class="fw-medium small text-muted d-block mt-1">{{ $lastPaymentDate }}</span>
                        </div>
                    </div>
                </div>

                {{-- Sub-heading: Invoices --}}
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold text-dark mb-0 small">
                        <i class="bi bi-receipt me-1 text-primary"></i> Invoices
                    </h6>
                    <a href="{{ route('student-invoices.index', ['student_id' => $student->id]) }}" class="text-decoration-none small text-muted">
                        All Invoices &rarr;
                    </a>
                </div>

                @if($invoices->isNotEmpty())
                    <div class="table-responsive mb-4 border rounded">
                        <table class="table table-hover table-sm align-middle mb-0" style="font-size: .85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Invoice #</th>
                                    <th>Year</th>
                                    <th>Fee Type</th>
                                    <th class="text-end">Net Amount</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Balance</th>
                                    <th>Due Date</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $inv)
                                @php
                                    $feeType = $inv->fee_structure?->fee_category?->name
                                        ?? ($inv->invoice_details->first()?->fee_category?->name ?? 'Tuition / Fees');
                                    $invBal = $inv->balance;
                                    $isOverdue = $inv->status !== 'paid' && $inv->status !== 'cancelled' && $inv->due_date && $inv->due_date->toDateString() < now()->toDateString();
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-medium">
                                        <a href="{{ route('student-invoices.show', $inv->id) }}" class="text-decoration-none fw-semibold">
                                            {{ $inv->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $inv->fee_structure?->academic_year?->name ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $feeType }}</span>
                                    </td>
                                    <td class="text-end">${{ number_format($inv->net_amount, 2) }}</td>
                                    <td class="text-end text-success">${{ number_format($inv->total_paid, 2) }}</td>
                                    <td class="text-end fw-bold {{ $invBal > 0 ? 'text-danger' : 'text-secondary' }}">
                                        ${{ number_format($invBal, 2) }}
                                    </td>
                                    <td class="text-muted small">
                                        {{ $inv->due_date?->format('d M Y') ?? '—' }}
                                    </td>
                                    <td class="text-center">
                                        @if($inv->status === 'cancelled')
                                            <span class="badge bg-dark-subtle text-dark-emphasis border border-dark-subtle rounded-pill px-2">Cancelled</span>
                                        @elseif($inv->status === 'paid')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">Paid</span>
                                        @elseif($isOverdue)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2">Overdue</span>
                                        @elseif($inv->status === 'partial')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2">Partial</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('student-invoices.show', $inv->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="View Invoice">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($invBal > 0 && $inv->status !== 'cancelled' && auth()->user()?->canAccess(['finance.create', 'finance']))
                                                <a href="{{ route('student-payments.create', ['invoice_id' => $inv->id]) }}" class="btn btn-sm btn-outline-success py-0 px-2" title="Pay Invoice">
                                                    <i class="bi bi-credit-card"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3 text-muted border rounded mb-4" style="font-size:.85rem">
                        <i class="bi bi-receipt-cutoff fs-4 d-block mb-1 text-light-subtle"></i>
                        No invoices billed for this student yet.
                    </div>
                @endif

                {{-- Sub-heading: Payment Receipts --}}
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold text-dark mb-0 small">
                        <i class="bi bi-credit-card me-1 text-success"></i> Payment History & Receipts
                    </h6>
                    <a href="{{ route('student-payments.index') }}" class="text-decoration-none small text-muted">
                        All Payments &rarr;
                    </a>
                </div>

                @if($payments->isNotEmpty())
                    <div class="table-responsive border rounded">
                        <table class="table table-hover table-sm align-middle mb-0" style="font-size: .85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Receipt #</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                    <th>Method</th>
                                    <th>Reference / Slip</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $pay)
                                <tr>
                                    <td class="ps-3 fw-medium">
                                        <code class="adm-badge">{{ $pay->formatted_receipt_number }}</code>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $pay->payment_date?->format('d M Y') ?? '—' }}
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        ${{ number_format($pay->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $pay->payment_method?->name ?? 'Cash' }}</span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $pay->transaction_id ?: '—' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('student-payments.receipt', $pay->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2" title="Print Dual Receipt" target="_blank">
                                            <i class="bi bi-printer me-1"></i>Receipt
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3 text-muted border rounded" style="font-size:.85rem">
                        <i class="bi bi-cash fs-4 d-block mb-1 text-light-subtle"></i>
                        No payments recorded for this student yet.
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- 6. Attendance Summary & Recent Records --}}
        <div class="data-card mb-3">
            <div class="data-card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold"><i class="bi bi-calendar-check me-2"></i>ព័ត៌មានវត្តមាន (Attendance Summary)</span>
                    <span class="badge bg-light text-dark border">{{ $attendanceStats['total'] ?? 0 }} ថ្ងៃសរុប</span>
                </div>
                <a href="{{ route('student-attendance.index', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;">
                    <i class="bi bi-eye me-1"></i>មើលទាំងអស់
                </a>
            </div>
            <div class="p-3">
                {{-- Attendance Stat Badges --}}
                <div class="row g-2 mb-3 text-center">
                    <div class="col-3">
                        <div class="p-2 border rounded bg-success bg-opacity-10">
                            <div class="fs-5 fw-bold text-success">{{ $attendanceStats['present'] ?? 0 }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">វត្តមាន (Present)</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded bg-danger bg-opacity-10">
                            <div class="fs-5 fw-bold text-danger">{{ $attendanceStats['absent'] ?? 0 }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">អវត្តមាន (Absent)</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded bg-warning bg-opacity-10">
                            <div class="fs-5 fw-bold text-warning-emphasis">{{ $attendanceStats['late'] ?? 0 }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">មកយឺត (Late)</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 border rounded bg-info bg-opacity-10">
                            <div class="fs-5 fw-bold text-info">{{ $attendanceStats['excused'] ?? 0 }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">ច្បាប់ (Excused)</div>
                        </div>
                    </div>
                </div>

                @if(isset($attendances) && $attendances->isNotEmpty())
                    <div class="table-responsive border rounded">
                        <table class="table table-hover table-sm align-middle mb-0" style="font-size: .85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">កាលបរិច្ឆេទ</th>
                                    <th>វេនសិក្សា / កម្មវិធី</th>
                                    <th>ស្ថានភាពវត្តមាន</th>
                                    <th>សម្គាល់</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $att)
                                <tr>
                                    <td class="ps-3 text-muted">
                                        {{ $att->attendance_session?->session_date ? \Carbon\Carbon::parse($att->attendance_session->session_date)->format('d M Y') : ($att->created_at ? $att->created_at->format('d M Y') : '—') }}
                                    </td>
                                    <td>
                                        {{ $att->attendance_session?->title ?? $att->attendance_session?->academic_year?->name ?? 'ធម្មតា' }}
                                    </td>
                                    <td>
                                        @php
                                            $stName = strtolower($att->attendance_status?->name ?? '');
                                        @endphp
                                        @if(str_contains($stName, 'present'))
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">វត្តមាន</span>
                                        @elseif(str_contains($stName, 'absent'))
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">អវត្តមាន</span>
                                        @elseif(str_contains($stName, 'late'))
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">មកយឺត</span>
                                        @else
                                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">{{ $att->attendance_status?->name ?? 'ច្បាប់' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $att->remarks ?: '—' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3 text-muted border rounded" style="font-size:.85rem">
                        <i class="bi bi-calendar-x fs-4 d-block mb-1 text-light-subtle"></i>
                        មិនទាន់មានកំណត់ត្រាវត្តមានសម្រាប់សិស្សនេះនៅឡើយទេ។
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-xl-4">
        
        {{-- 5. Guardians Information --}}
        <div class="data-card mb-3">
            <div class="data-card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="bi bi-people me-2"></i>ព័ត៌មានឪពុកម្តាយ និងអាណាព្យាបាល</span>
                @if(auth()->user()?->canAccess('students.edit'))
                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#attachGuardianModal">
                    <i class="bi bi-plus-lg me-1"></i>Attach
                </button>
                @endif
            </div>
            <div class="p-2.5">
                @if($student->guardians->count() > 0)
                    <div class="d-flex flex-column gap-2">
                        @foreach($student->guardians as $guardian)
                        @php
                            $rel = strtolower($guardian->pivot->relationship ?? '');
                            $isFather = in_array($rel, ['father', 'ឪពុក']);
                            $isMother = in_array($rel, ['mother', 'ម្តាយ']);
                            $relLabel = $isFather ? 'ឪពុក (Father)' : ($isMother ? 'ម្តាយ (Mother)' : ($guardian->pivot->relationship ?? 'អាណាព្យាបាល'));
                            $relBg = $isFather ? 'bg-success-subtle text-success border-success-subtle' : ($isMother ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-secondary-subtle text-secondary border-secondary-subtle');
                            $contact = $guardian->guardian_contacts->first();
                        @endphp
                        <div class="guardian-item-card position-relative">
                            <div class="d-flex align-items-start justify-content-between mb-1.5">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-1.5 flex-wrap" style="font-size: 0.88rem;">
                                        @if($isFather)
                                            <i class="bi bi-gender-male text-success"></i>
                                        @elseif($isMother)
                                            <i class="bi bi-gender-female text-danger"></i>
                                        @else
                                            <i class="bi bi-person text-secondary"></i>
                                        @endif
                                        <span>{{ $guardian->user?->name ?? 'Guardian #' . $guardian->id }}</span>
                                    </h6>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <small class="badge {{ $relBg }} border rounded-pill">{{ $relLabel }}</small>
                                        @if($guardian->pivot->is_primary)
                                            <small class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">ចម្បង (Primary)</small>
                                        @endif
                                        @if($guardian->pivot->is_emergency)
                                            <small class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">អាសន្ន</small>
                                        @endif
                                    </div>
                                </div>
                                @if(auth()->user()?->canAccess('students.edit'))
                                <form action="{{ route('students.guardians.destroy', [$student->id, $guardian->id]) }}" method="POST" onsubmit="return confirm('Detach this guardian?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger p-0 border-0" title="Detach Guardian">
                                        <i class="bi bi-x-circle fs-6"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                            
                            <div class="text-secondary d-flex flex-column gap-1 mt-1.5" style="font-size: .75rem;">
                                @if($guardian->age)
                                    <div><i class="bi bi-hourglass-split me-1.5 text-warning"></i>អាយុ៖ <strong>{{ $guardian->age }}</strong> ឆ្នាំ</div>
                                @endif
                                @if($guardian->occupation)
                                    <div><i class="bi bi-briefcase me-1.5 text-primary"></i>មុខរបរ៖ <strong>{{ $guardian->occupation }}</strong></div>
                                @endif
                                @if($contact && $contact->phone)
                                    <div><i class="bi bi-telephone me-1.5 text-success"></i>លេខទូរស័ព្ទ៖ <a href="tel:{{ $contact->phone }}" class="text-decoration-none text-dark fw-medium">{{ $contact->phone }}</a></div>
                                @endif
                                @if($guardian->user && $guardian->user->email)
                                    <div><i class="bi bi-envelope me-1.5 text-info"></i>អ៊ីមែល៖ {{ $guardian->user->email }}</div>
                                @endif
                                @if($guardian->address)
                                    <div><i class="bi bi-geo-alt me-1.5 text-danger"></i>អាសយដ្ឋាន៖ {{ $guardian->address }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center my-2" style="font-size: .8125rem;">មិនទាន់មានព័ត៌មានអាណាព្យាបាលនៅឡើយទេ។</p>
                @endif
            </div>
        </div>

        {{-- 6. Enrollment Information --}}
        <div class="data-card mb-3">
            <div class="data-card-header">
                <span class="fw-semibold"><i class="bi bi-journal-bookmark me-2"></i>Enrollment Info</span>
            </div>
            <div class="detail-grid">
                <div class="row g-1">
                    <div class="col-12 detail-row">
                        <span class="detail-label">Academic Year</span>
                        <span class="detail-value">{{ $student->academicYear?->name ?? ($student->academic_year_id ? 'Year #' . $student->academic_year_id : '—') }}</span>
                    </div>
                    <div class="col-12 detail-row">
                        <span class="detail-label">Class</span>
                        <span class="detail-value">{{ $student->schoolClass?->grade?->name ?? ($student->schoolClass ? 'Class #' . $student->schoolClass->id : '—') }}</span>
                    </div>
                    <div class="col-12 detail-row">
                        <span class="detail-label">Section</span>
                        <span class="detail-value">{{ $student->section?->name ?? ($student->schoolClass?->section?->name ?? '—') }}</span>
                    </div>
                    <div class="col-12 detail-row">
                        <span class="detail-label">វេនសិក្សា (Shift)</span>
                        <span class="detail-value">{{ $student->schoolShift?->khmer_name ?? $student->schoolShift?->name ?? '—' }}</span>
                    </div>
                    <div class="col-12 detail-row">
                        <span class="detail-label">ទម្រង់សិក្សា (Study Mode)</span>
                        <span class="detail-value">
                            @if($student->study_mode === 'part_time')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2">
                                    <i class="bi bi-clock me-1"></i>ក្រៅម៉ោង (Part-Time)
                                </span>
                            @elseif($student->study_mode === 'dual')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2">
                                    <i class="bi bi-arrow-repeat me-1"></i>ពេញម៉ោង & ក្រៅម៉ោង (Dual Study)
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2">
                                    <i class="bi bi-mortarboard me-1"></i>ពេញម៉ោង (Full-Time)
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="col-12 detail-row">
                        <span class="detail-label">ប្រភេទសិស្ស (Category)</span>
                        <span class="detail-value">
                            {{ $student->studentCategory?->khmer_name ?? $student->studentCategory?->name ?? '—' }}
                        </span>
                    </div>
                    <div class="col-12 detail-row">
                        <span class="detail-label">ស្ថានភាពសិស្ស</span>
                        <span class="detail-value"><span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill">{{ $student->studentStatus?->khmer_name ?? $student->studentStatus?->name ?? 'កំពុងសិក្សា' }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 7. Student Notes Widget --}}
        <div class="data-card mb-3">
            <div class="data-card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold"><i class="bi bi-journal-text me-2"></i>Notes</span>
                @if(auth()->user()?->canAccess('students.edit'))
                <a href="{{ route('student-notes.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;">
                    <i class="bi bi-plus-lg me-1"></i>New Note
                </a>
                @endif
            </div>
            <div class="p-2.5 px-3">
                @if($student->notes && $student->notes->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($student->notes->take(3) as $note)
                            <div class="list-group-item px-0 py-1.5 border-0 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark border">{{ $note->note_type ?? 'Note' }}</span>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $note->created_at?->format('d M Y') }}</small>
                                </div>
                                <p class="mb-0 text-dark small mt-1 text-truncate" style="font-size: 0.78rem;">{{ $note->note ?? $note->content }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('student-notes.index', ['student_id' => $student->id]) }}" class="text-decoration-none small fw-medium" style="font-size: 0.78rem;">
                            View All Notes ({{ $student->notes->count() }}) &rarr;
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center my-2" style="font-size: .8125rem;">No notes recorded.</p>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- MODAL 1: Upload Document Modal --}}
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-md);">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-semibold text-dark" id="uploadDocumentModalLabel">Upload Student Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('students.documents.store', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required>
                            <option value="">Select document type</option>
                            <option value="Birth Certificate">Birth Certificate</option>
                            <option value="National ID">National ID Card</option>
                            <option value="Passport">Passport</option>
                            <option value="Previous Academic Transcript">Previous Academic Transcript</option>
                            <option value="Transfer Certificate">Transfer Certificate</option>
                            <option value="Medical Certificate">Medical Certificate</option>
                            <option value="Other">Other Document</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">File (PDF, PNG, JPG, JPEG - max 2MB) <span class="text-danger">*</span></label>
                        <input type="file" name="document" class="form-control" accept=".pdf,.png,.jpg,.jpeg" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-action btn-sm">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: Attach Guardian Modal --}}
<div class="modal fade" id="attachGuardianModal" tabindex="-1" aria-labelledby="attachGuardianModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-md);">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-semibold text-dark" id="attachGuardianModalLabel">Attach Existing Guardian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('students.guardians.store', $student->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Select Guardian <span class="text-danger">*</span></label>
                        <select name="guardian_id" class="form-select" required>
                            <option value="">Choose a guardian...</option>
                            @foreach($availableGuardians as $ag)
                                <option value="{{ $ag->id }}">
                                    {{ $ag->user?->name ?? 'Guardian #' . $ag->id }}
                                    @if($ag->guardian_contacts->first()?->phone)
                                        ({{ $ag->guardian_contacts->first()->phone }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">Relationship <span class="text-danger">*</span></label>
                        <select name="relationship" class="form-select" required>
                            <option value="">Select relationship</option>
                            <option value="Father">Father</option>
                            <option value="Mother">Mother</option>
                            <option value="Guardian">Guardian</option>
                            <option value="Uncle">Uncle</option>
                            <option value="Aunt">Aunt</option>
                            <option value="Grandparent">Grandparent</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-action btn-sm">Attach Guardian</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 3: Update Student Status Modal --}}
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-md);">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-semibold text-dark" id="updateStatusModalLabel">
                    <i class="bi bi-person-gear me-2 text-warning"></i>ផ្លាស់ប្តូរស្ថានភាពសិស្ស (Change Status)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('students.update-status', $student->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">ស្ថានភាពសិស្សថ្មី <span class="text-danger">*</span></label>
                        <select name="student_status_id" class="form-select" required>
                            @foreach($statuses as $st)
                                <option value="{{ $st->id }}" {{ $student->student_status_id == $st->id ? 'selected' : '' }}>
                                    {{ $st->khmer_name ?? $st->name }} ({{ $st->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">មូលហេតុនៃការផ្លាស់ប្តូរ (Reason)</label>
                        <textarea name="reason" rows="3" class="form-control" placeholder="ឧទាហរណ៍៖ សិស្សបានបញ្ចប់ការសិក្សា, ផ្អាកការសិក្សាដោយសារគ្រួសារផ្លាស់ទីលំនៅ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary-action btn-sm">រក្សាទុកការផ្លាស់ប្តូរ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 4: Delete Student Modal --}}
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-md);">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-semibold text-danger" id="deleteStudentModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>លុបសិស្សចេញពីប្រព័ន្ធ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:54px;height:54px">
                        <i class="bi bi-trash text-danger fs-4"></i>
                    </span>
                </div>
                <h6 class="fw-bold mb-2">តើលោកអ្នកពិតជាចង់លុបសិស្សនេះមែនទេ?</h6>
                <p class="text-muted small mb-3">
                    សិស្ស <strong>{{ $student->user?->name ?? 'សិស្សនេះ' }}</strong> (អត្តលេខ៖ <code>{{ $student->admission_number }}</code>) នឹងត្រូវបានលុបចេញពីប្រព័ន្ធ។
                </p>
                @if($invoices->isNotEmpty() || $payments->isNotEmpty())
                    <div class="alert alert-warning text-start small mb-0 py-2 px-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>ចំណាំសំខាន់៖</strong> ប្រព័ន្ធការពារទិន្នន័យនឹងមិនអនុញ្ញាតឱ្យលុបសិស្សដែលមានវិក្កយបត្រ ({{ $invoices->count() }}) ឬការទូទាត់ប្រាក់ ({{ $payments->count() }}) ឡើយ ដើម្បីការពារការបាត់បង់ប្រវត្តិហិរញ្ញវត្ថុ។ សូមប្រើប្រាស់មុខងារ «ប្តូរស្ថានភាពទៅជា ឈប់រៀន» ជំនួសវិញ។
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light border-top py-2 px-4 justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">បោះបង់</button>
                <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash me-1"></i>បាទ/ចាស, លុបសិស្ស
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
