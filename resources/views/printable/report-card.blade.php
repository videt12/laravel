<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ព្រឹត្តិបត្រពិន្ទុ - {{ $student->studentProfile?->first_name ?? $student->admission_number }} - {{ $exam->name }}</title>
    <link rel="shortcut icon" href="{{ $school && $school->logo_path && file_exists(public_path($school->logo_path)) ? asset($school->logo_path) : asset('images/school_logo.png') }}">

    <!-- Google Fonts for Khmer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&family=Moul&family=Siemreap&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --font-body: 'Kantumruy Pro', 'Siemreap', sans-serif;
            --font-title: 'Moul', 'Kantumruy Pro', serif;
            --primary-color: #1e3a8a;
            --gold-color: #b45309;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 13px;
            line-height: 1.5;
            padding: 20px 10px;
        }

        .action-toolbar {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
            font-family: var(--font-body);
        }

        .btn-primary { background: #2563eb; color: #ffffff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-outline { background: #ffffff; border: 1px solid #cbd5e1; color: #475569; }
        .btn-outline:hover { background: #f8fafc; color: #0f172a; }

        /* Report Card Paper (A4 size look) */
        .report-card-paper {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 35px 45px;
            border-radius: 4px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        /* Decorative Double Frame */
        .inner-border {
            border: 2px solid #1e3a8a;
            padding: 25px 30px;
            border-radius: 4px;
            position: relative;
        }
        .inner-border::after {
            content: "";
            position: absolute;
            top: 3px; left: 3px; right: 3px; bottom: 3px;
            border: 1px solid #cbd5e1;
            pointer-events: none;
        }

        /* Header Layout */
        .header-section {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }

        .school-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .school-logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fcd34d;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .school-name-khmer {
            font-family: var(--font-title);
            font-size: 16px;
            color: var(--primary-color);
            margin-bottom: 2px;
        }

        .school-name-latin {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1px;
        }

        .kingdom-motto {
            text-align: center;
        }
        .kingdom-title {
            font-family: var(--font-title);
            font-size: 14px;
            color: #0f172a;
        }
        .kingdom-sub {
            font-family: var(--font-title);
            font-size: 12px;
            color: #b45309;
            margin-top: 4px;
        }

        /* Report Title Banner */
        .report-banner {
            text-align: center;
            margin: 15px 0 20px 0;
        }
        .report-title-khmer {
            font-family: var(--font-title);
            font-size: 18px;
            color: var(--primary-color);
            margin-bottom: 4px;
        }
        .report-title-latin {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .exam-badge {
            display: inline-block;
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #b45309;
            font-weight: 700;
            font-size: 12px;
            padding: 3px 14px;
            border-radius: 20px;
            margin-top: 6px;
        }

        /* Student Details Grid */
        .student-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 24px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            font-size: 12.5px;
        }
        .detail-label {
            font-weight: 600;
            color: #475569;
            width: 140px;
            flex-shrink: 0;
        }
        .detail-val {
            color: #0f172a;
            font-weight: 700;
        }

        /* Grades Table */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12.5px;
        }
        .grades-table th {
            background-color: #f1f5f9;
            color: var(--primary-color);
            font-weight: 700;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            text-align: center;
        }
        .grades-table td {
            padding: 7px 10px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .grades-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        /* Summary / Performance Box */
        .summary-performance-box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .perf-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .perf-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
        }
        .perf-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-color);
        }
        .perf-card.highlight {
            background: #eff6ff;
            border-color: #93c5fd;
        }
        .perf-card.highlight .perf-value {
            color: #1d4ed8;
        }

        /* Attendance & Remarks Box */
        .remarks-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }
        .comment-section {
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 10px 14px;
            min-height: 75px;
            background: #fafafa;
        }
        .comment-title {
            font-weight: 700;
            font-size: 12px;
            color: #475569;
            margin-bottom: 6px;
        }

        /* Signatures Section */
        .signatures-section {
            display: flex;
            justify-content: space-between;
            text-align: center;
            margin-top: 30px;
            padding: 0 20px;
        }
        .sig-block {
            width: 200px;
        }
        .sig-title {
            font-weight: 700;
            font-size: 12px;
            color: #0f172a;
            margin-bottom: 60px;
        }
        .sig-line {
            border-top: 1px dotted #94a3b8;
            padding-top: 4px;
            font-size: 11px;
            color: #64748b;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .action-toolbar {
                display: none !important;
            }
            .report-card-paper {
                box-shadow: none !important;
                border: none !important;
                padding: 15px !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    {{-- Top Action Toolbar (Hidden during printing) --}}
    <div class="action-toolbar">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>ត្រឡប់ក្រោយ
            </a>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer-fill"></i>បោះពុម្ពព្រឹត្តិបត្រពិន្ទុ (Print)
            </button>
        </div>
    </div>

    {{-- Report Card Sheet --}}
    <div class="report-card-paper">
        <div class="inner-border">

            {{-- 1. Official Header --}}
            <div class="header-section">
                <div class="school-info">
                    @php
                        $schoolLogo = ($school && $school->logo_path && file_exists(public_path($school->logo_path)))
                            ? asset($school->logo_path)
                            : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : asset('images/logo.svg'));
                    @endphp
                    <img src="{{ $schoolLogo }}" alt="Logo" class="school-logo">
                    <div>
                        <div class="school-name-khmer">{{ $school->name ?? 'សាលារៀន ប្រទីបបញ្ញា' }}</div>
                        <div class="school-name-latin">BRATIB PANHA SCHOOL</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                            {{ $school->address ?? 'ភូមិគោកទ្រាស ឃុំឈ្នួរមានជ័យ ស្រុកព្រះនេត្រព្រះ ខេត្តបន្ទាយមានជ័យ' }}
                        </div>
                    </div>
                </div>

                <div class="kingdom-motto">
                    <div class="kingdom-title">ព្រះរាជាណាចក្រកម្ពុជា</div>
                    <div class="kingdom-sub">ជាតិ សាសនា ព្រះមហាក្សត្រ</div>
                </div>
            </div>

            {{-- 2. Report Banner --}}
            <div class="report-banner">
                <h1 class="report-title-khmer">ព្រឹត្តិបត្រពិន្ទុប្រឡង</h1>
                <div class="report-title-latin">STUDENT REPORT CARD</div>
                <div class="exam-badge">
                    <i class="bi bi-award me-1"></i>{{ $exam->name }} ({{ $exam->academic_year?->name ?? 'ឆ្នាំសិក្សាបច្ចុប្បន្ន' }})
                </div>
            </div>

            {{-- 3. Student Details --}}
            @php
                $profile = $student->studentProfile;
                $khmerName = trim(($profile?->last_name_khmer ?? '') . ' ' . ($profile?->first_name_khmer ?? ''));
                if (empty($khmerName)) {
                    $khmerName = trim(($profile?->last_name ?? '') . ' ' . ($profile?->first_name ?? $student->user?->name));
                }
                $latinName = trim(($profile?->last_name ?? '') . ' ' . ($profile?->first_name ?? ''));
                $gender = strtolower($profile?->gender ?? '');
                $khmerGender = ($gender === 'male' || $gender === 'm' || $gender === 'ប្រុស') ? 'ប្រុស' : (($gender === 'female' || $gender === 'f' || $gender === 'ស្រី') ? 'ស្រី' : '—');
            @endphp
            <div class="student-details-grid">
                <div class="detail-item">
                    <span class="detail-label">ឈ្មោះសិស្ស (Khmer)៖</span>
                    <span class="detail-val" style="font-family: var(--font-title); font-size: 13px;">{{ $khmerName }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">អត្តលេខសិស្ស (ID)៖</span>
                    <span class="detail-val">{{ $student->admission_number }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ឈ្មោះឡាតាំង (Latin)៖</span>
                    <span class="detail-val" style="font-family: 'Inter', sans-serif;">{{ strtoupper($latinName ?: '—') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ថ្នាក់រៀន (Class)៖</span>
                    <span class="detail-val">{{ $student->schoolClass?->name ?? '—' }} {{ $student->section ? '(' . $student->section->name . ')' : '' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ភេទ (Gender)៖</span>
                    <span class="detail-val">{{ $khmerGender }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ឆ្នាំសិក្សា (Year)៖</span>
                    <span class="detail-val">{{ $student->academicYear?->name ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">ទម្រង់សិក្សា (Mode)៖</span>
                    <span class="detail-val" style="color: {{ $student->study_mode === 'part_time' ? '#b45309' : '#1e3a8a' }}; font-weight: 700;">
                        {{ $student->study_mode_label }}
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">វេនសិក្សា (Shift)៖</span>
                    <span class="detail-val">{{ $student->schoolShift?->khmer_name ?? $student->schoolShift?->name ?? 'ពេញមួយថ្ងៃ' }}</span>
                </div>
            </div>

            {{-- 4. Grades Table --}}
            <table class="grades-table">
                <thead>
                    <tr>
                        <th style="width: 45px;">ល.រ</th>
                        <th>មុខវិជ្ជា (Subject)</th>
                        <th style="width: 85px;">ពិន្ទុពេញ</th>
                        <th style="width: 85px;">ពិន្ទុជាប់</th>
                        <th style="width: 95px;">ពិន្ទុទទួលបាន</th>
                        <th style="width: 75px;">និទ្ទេស</th>
                        <th style="width: 120px;">ចំណាត់ថ្នាក់</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examResults as $idx => $res)
                        @php
                            $sub = $res->exam_subject;
                            $pass = $sub ? (float)$sub->pass_marks : 50;
                            $marks = (float)$res->marks_obtained;
                            $isPassed = $marks >= $pass;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="text-left fw-bold">
                                {{ $sub?->subject?->name ?? 'មុខវិជ្ជាទូទៅ' }}
                            </td>
                            <td class="text-center">{{ $sub ? number_format($sub->max_marks, 0) : '100' }}</td>
                            <td class="text-center text-muted">{{ $sub ? number_format($sub->pass_marks, 0) : '50' }}</td>
                            <td class="text-center fw-bold {{ $isPassed ? 'text-dark' : 'text-danger' }}">
                                {{ number_format($marks, 1) }}
                            </td>
                            <td class="text-center fw-bold">
                                <span style="color: {{ in_array($res->grade, ['A', 'B']) ? '#16a34a' : ($res->grade === 'F' ? '#dc2626' : '#2563eb') }}">
                                    {{ $res->grade ?? '—' }}
                                </span>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $res->remarks ?? ($isPassed ? 'ជាប់' : 'ធ្លាក់') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8;">
                                មិនទាន់មានលទ្ធផលប្រឡងមុខវិជ្ជាត្រូវបានបញ្ចូលនៅឡើយទេ។
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- 5. Performance Summary Cards --}}
            <div class="summary-performance-box">
                <div class="perf-card">
                    <div class="perf-label">ពិន្ទុសរុប (Total Marks)</div>
                    <div class="perf-value">{{ $studentGrade ? number_format($studentGrade->total_marks, 1) : '—' }}</div>
                </div>
                <div class="perf-card">
                    <div class="perf-label">មធ្យមភាគ (Average)</div>
                    <div class="perf-value">{{ $studentGrade ? number_format($studentGrade->percentage, 1) . '%' : '—' }}</div>
                </div>
                <div class="perf-card highlight">
                    <div class="perf-label">ចំណាត់ថ្នាក់ (Rank)</div>
                    <div class="perf-value">
                        @if($rank)
                            លេខ {{ $rank }} <span style="font-size: 11px; color: #64748b;">/ {{ $totalInClass }}</span>
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="perf-card">
                    <div class="perf-label">និទ្ទេសរួម (Overall Grade)</div>
                    <div class="perf-value" style="color: #b45309;">{{ $studentGrade->grade ?? '—' }}</div>
                </div>
            </div>

            {{-- 6. Remarks & Attendance Box --}}
            <div class="remarks-box">
                <div class="comment-section">
                    <div class="comment-title"><i class="bi bi-chat-left-dots me-1"></i>មតិយោបល់របស់គ្រូបន្ទុកថ្នាក់៖</div>
                    <div style="font-style: italic; color: #475569; font-size: 11.5px;">
                        @if($studentGrade && $studentGrade->percentage >= 80)
                            សិស្សមានការខិតខំប្រឹងប្រែងរៀនសូត្របានល្អប្រសើរណាស់ សូមបន្តរក្សាលទ្ធផលនេះ។
                        @elseif($studentGrade && $studentGrade->percentage >= 65)
                            សិស្សមានការយល់ដឹងល្អ និងយកចិត្តទុកដាក់ក្នុងម៉ោងសិក្សា។
                        @elseif($studentGrade && $studentGrade->percentage >= 50)
                            លទ្ធផលមធ្យម គួរយកចិត្តទុកដាក់បន្ថែមលើមុខវិជ្ជាដែលខ្សោយ។
                        @else
                            គួរពង្រឹងការសិក្សាបន្ថែមនៅផ្ទះ និងសហការជាមួយគ្រូបង្រៀន។
                        @endif
                    </div>
                </div>
                <div class="comment-section">
                    <div class="comment-title"><i class="bi bi-calendar-check me-1"></i>ស្ថិតិវត្តមានក្នុងវគ្គសិក្សា៖</div>
                    <div style="display: flex; justify-content: space-around; font-size: 12px; margin-top: 8px;">
                        <div>វត្តមាន៖ <strong style="color: #16a34a;">{{ $attendanceStats?->present_count ?? 0 }} ថ្ងៃ</strong></div>
                        <div>ច្បាប់៖ <strong style="color: #2563eb;">{{ $attendanceStats?->excused_count ?? 0 }} ថ្ងៃ</strong></div>
                        <div>អវត្តមាន៖ <strong style="color: #dc2626;">{{ $attendanceStats?->absent_count ?? 0 }} ថ្ងៃ</strong></div>
                    </div>
                </div>
            </div>

            {{-- 7. Signatures Area --}}
            <div class="signatures-section">
                <div class="sig-block">
                    <div class="sig-title">ហត្ថលេខាអាណាព្យាបាល</div>
                    <div class="sig-line">កាលបរិច្ឆេទ៖ ....../....../......</div>
                </div>
                <div class="sig-block">
                    <div class="sig-title">គ្រូបន្ទុកថ្នាក់</div>
                    <div class="sig-line">ឈ្មោះគ្រូ៖ .................................</div>
                </div>
                <div class="sig-block">
                    <div class="sig-title">នាយកសាលា</div>
                    <div class="sig-line">ឈ្មោះ និងត្រាសាលា</div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
