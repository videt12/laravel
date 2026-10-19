@extends('layouts.app')

@section('title', 'View Subject Details — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item text-muted">Academic</li>
        <li class="breadcrumb-item"><a href="{{ route('subjects.index') }}" class="text-decoration-none text-muted">Subjects</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">View Subject</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold mb-0 text-dark">Subject: {{ $subject->name }}</h1>
        <p class="text-muted mb-0">Code: <span class="badge bg-secondary font-monospace">{{ $subject->code }}</span></p>
    </div>
    <div>
        <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-primary-action btn-sm shadow-sm ms-2">
            <i class="bi bi-pencil me-1"></i>Edit Subject
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT: SUBJECT INFORMATION --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-info-circle text-primary me-2"></i>ព័ត៌មានទូទៅនៃមុខវិជ្ជា
                </h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light w-40 small text-muted">ឈ្មោះមុខវិជ្ជា</th>
                            <td class="fw-bold text-dark">{{ $subject->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light small text-muted">កូដមុខវិជ្ជា</th>
                            <td><span class="badge bg-light text-dark border font-monospace">{{ $subject->code }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light small text-muted">ប្រភេទមុខវិជ្ជា</th>
                            <td>
                                @php
                                    $typeClass = 'bg-secondary text-white';
                                    if($subject->type == 'Core') $typeClass = 'bg-primary text-white';
                                    elseif($subject->type == 'Elective') $typeClass = 'bg-success text-white';
                                    elseif($subject->type == 'Practical') $typeClass = 'bg-warning text-dark';
                                @endphp
                                <span class="badge {{ $typeClass }}">{{ $subject->type ?? 'មិនបានបញ្ជាក់' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light small text-muted">ចំនួនថ្នាក់ដែលរៀន</th>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    {{ $subject->class_subjects->count() }} ថ្នាក់
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RIGHT: ASSIGNED CLASSES LIST --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-diagram-3 text-primary me-2"></i>ថ្នាក់រៀនដែលរៀនមុខវិជ្ជានេះ ({{ $subject->class_subjects->count() }})
                </h6>
                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-outline-primary py-1 px-2.5" style="font-size:0.78rem">
                    <i class="bi bi-pencil me-1"></i>កែសម្រួលថ្នាក់
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px">#</th>
                                <th>ថ្នាក់រៀន</th>
                                <th>ឆ្នាំសិក្សា</th>
                                <th class="text-end pe-3">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subject->class_subjects as $index => $cs)
                                @php $cls = $cs->class; @endphp
                                <tr>
                                    <td class="text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            <i class="bi bi-mortarboard text-primary me-1"></i>
                                            {{ $cls->grade->name ?? 'ថ្នាក់' }}{{ $cls->section ? ' — ' . $cls->section->name : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($cls && $cls->academic_year)
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-calendar-event text-muted me-1"></i>{{ $cls->academic_year->name }}
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        @if($cls)
                                            <a href="{{ route('classes.show', $cls) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-2" style="font-size:0.75rem" title="មើលព័ត៌មានថ្នាក់">
                                                <i class="bi bi-eye me-1"></i>មើលថ្នាក់
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <div class="fs-3 text-muted mb-2"><i class="bi bi-journal-x"></i></div>
                                        <p class="mb-2 small">មុខវិជ្ជានេះមិនទាន់ត្រូវបានកំណត់សម្រាប់ថ្នាក់រៀនណាមួយនៅឡើយទេ។</p>
                                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-primary-action">
                                            <i class="bi bi-plus-circle me-1"></i>ដាក់មុខវិជ្ជាសម្រាប់ថ្នាក់រៀន
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
