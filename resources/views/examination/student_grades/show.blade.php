@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <h4 class="fw-bold mb-1">Student Examination Report Card</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('examinations.index') }}">Examinations</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'grades']) }}">Student Grades</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Report Card #{{ $studentGrade->id }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('examinations.index', ['tab' => 'results', 'subtab' => 'grades']) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Grades
            </a>
            <a href="{{ route('examinations.report-card', ['exam' => $studentGrade->exam_id, 'student' => $studentGrade->student_id]) }}" target="_blank" class="btn btn-primary btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> បោះពុម្ពទម្រង់ផ្លូវការ (Official Report Card)
            </a>
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Quick Print
            </button>
        </div>
    </div>

    {{-- Printable Report Card --}}
    <div class="card border-0 shadow-sm rounded-3 p-4 bg-white" id="reportCardArea">
        {{-- Institution Header --}}
        <div class="border-bottom pb-4 mb-4 text-center">
            <h3 class="fw-bold text-dark text-uppercase mb-1">Modern School Management System</h3>
            <p class="text-muted small mb-1">Academic Examination & Progress Evaluation Report</p>
            <div class="badge bg-light text-dark border px-3 py-1">
                {{ $studentGrade->exam->name ?? 'Examination' }} ({{ $studentGrade->exam->academicYear->name ?? 'Academic Year' }})
            </div>
        </div>

        {{-- Student & Exam Context --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted ps-0" style="width: 35%;">Student Name:</th>
                        <td class="fw-bold text-dark">{{ $studentGrade->student->first_name ?? '' }} {{ $studentGrade->student->last_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Admission No:</th>
                        <td class="font-monospace">{{ $studentGrade->student->admission_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Roll Number:</th>
                        <td>{{ $studentGrade->student->roll_number ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted ps-0" style="width: 35%;">Class:</th>
                        <td class="fw-semibold">{{ $studentGrade->student->schoolClass->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Section:</th>
                        <td>{{ $studentGrade->student->section->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Exam Period:</th>
                        <td>{{ $studentGrade->exam->start_date ?? '' }} to {{ $studentGrade->exam->end_date ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Subject Results Breakdown --}}
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th class="text-start">Subject</th>
                        <th style="width: 120px;">Max Marks</th>
                        <th style="width: 120px;">Pass Marks</th>
                        <th style="width: 140px;">Marks Obtained</th>
                        <th style="width: 100px;">Grade</th>
                        <th style="width: 120px;">Status</th>
                        <th class="text-start">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumMax = 0;
                        $sumPass = 0;
                    @endphp
                    @forelse($subjectResults as $index => $res)
                        @php
                            $max = $res->examSubject->max_marks ?? 0;
                            $pass = $res->examSubject->pass_marks ?? 0;
                            $sumMax += $max;
                            $sumPass += $pass;
                            $hasPassed = $res->marks_obtained >= $pass;
                        @endphp
                        <tr class="text-center">
                            <td class="text-muted small">{{ $index + 1 }}</td>
                            <td class="text-start fw-semibold">
                                {{ $res->examSubject->subject->name ?? 'N/A' }}
                                <span class="text-muted small">({{ $res->examSubject->subject->code ?? 'SUB' }})</span>
                            </td>
                            <td>{{ number_format($max, 2) }}</td>
                            <td>{{ number_format($pass, 2) }}</td>
                            <td class="fw-bold text-dark">{{ number_format($res->marks_obtained, 2) }}</td>
                            <td>
                                @if($res->grade)
                                    <span class="badge bg-{{ in_array($res->grade, ['A+', 'A', 'B+', 'B']) ? 'success' : (in_array($res->grade, ['C+', 'C', 'D']) ? 'warning' : 'danger') }} px-2 py-1">
                                        {{ $res->grade }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $hasPassed ? 'success' : 'danger' }} px-2 py-1">
                                    {{ $hasPassed ? 'Passed' : 'Failed' }}
                                </span>
                            </td>
                            <td class="text-start text-muted small">{{ $res->remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No subject scores recorded yet for this exam.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($subjectResults) > 0)
                    <tfoot class="table-light text-center fw-bold">
                        <tr>
                            <td colspan="2" class="text-start">TOTAL AGGREGATE</td>
                            <td>{{ number_format($sumMax, 2) }}</td>
                            <td>{{ number_format($sumPass, 2) }}</td>
                            <td class="text-primary fs-6">{{ number_format($studentGrade->total_marks, 2) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Overall Performance Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="border rounded-3 p-3 text-center bg-light">
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Total Marks</span>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($studentGrade->total_marks, 2) }}</div>
                    <span class="text-muted small">Out of {{ number_format($sumMax, 2) }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded-3 p-3 text-center bg-light">
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Percentage</span>
                    <div class="fs-4 fw-bold text-primary">{{ number_format($studentGrade->percentage, 2) }}%</div>
                    <span class="text-muted small">Overall Score</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded-3 p-3 text-center bg-light">
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Overall Grade</span>
                    <div class="fs-4 fw-bold text-success">{{ $studentGrade->grade ?? 'N/A' }}</div>
                    <span class="text-muted small">Scale Standard</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded-3 p-3 text-center bg-light">
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">GPA / Status</span>
                    <div class="fs-4 fw-bold text-dark">{{ number_format($studentGrade->gpa, 2) }}</div>
                    <span class="badge bg-{{ strtolower($studentGrade->result_status) === 'passed' ? 'success' : 'danger' }} text-capitalize px-2 py-1">
                        {{ $studentGrade->result_status ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Signature Section --}}
        <div class="row mt-5 pt-4 text-center">
            <div class="col-4">
                <div class="border-top pt-2 text-muted small fw-semibold">Class Teacher's Signature</div>
            </div>
            <div class="col-4">
                <div class="border-top pt-2 text-muted small fw-semibold">Principal / Headmaster</div>
            </div>
            <div class="col-4">
                <div class="border-top pt-2 text-muted small fw-semibold">Parent / Guardian Signature</div>
            </div>
        </div>
    </div>
</div>
@endsection
