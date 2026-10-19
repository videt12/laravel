<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កាតសិស្សថ្នាក់ {{ $schoolClass->name }}</title>
    <link rel="shortcut icon" href="{{ $school && $school->logo_path && file_exists(public_path($school->logo_path)) ? asset($school->logo_path) : asset('images/school_logo.png') }}">

    <!-- Google Fonts for Khmer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&family=Moul&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #1e3a8a;
            --font-khmer: 'Kantumruy Pro', sans-serif;
            --font-moul: 'Moul', 'Kantumruy Pro', serif;
            --font-latin: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-khmer);
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 20px 10px;
        }

        .action-toolbar {
            max-width: 900px;
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
            font-family: var(--font-khmer);
        }
        .btn-primary { background: #2563eb; color: #ffffff; }
        .btn-outline { background: #ffffff; border: 1px solid #cbd5e1; color: #475569; }

        /* A4 Badge Grid Layout */
        .id-cards-grid {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
        }

        .id-badge {
            width: 250px;
            height: 380px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            page-break-inside: avoid;
        }

        .badge-header {
            background: linear-gradient(180deg, #f59e0b 0%, #eab308 25%, #d97706 70%, #b45309 100%);
            border-bottom: 2px solid #92400e;
            box-shadow: 0 2px 6px rgba(180, 83, 9, 0.2);
            padding: 6px 8px 5px 8px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-corner {
            position: absolute;
            top: 0;
            width: 45px;
            height: 45px;
            pointer-events: none;
            z-index: 1;
        }
        .header-corner.left {
            left: 0;
        }
        .header-corner.right {
            right: 0;
        }

        .badge-logo {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            object-fit: cover;
            background: #ffffff;
            border: 2.5px solid #ffffff;
            margin: 0 auto 3px auto;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
            display: block;
            position: relative;
            z-index: 2;
        }

        .badge-school-name {
            font-family: var(--font-moul);
            font-size: 9.5px;
            color: #261503;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
            position: relative;
            z-index: 2;
        }
        .badge-school-sub {
            font-family: var(--font-latin);
            font-size: 6.5px;
            letter-spacing: 0.8px;
            color: #261503;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.3);
            font-weight: 800;
            text-transform: uppercase;
            position: relative;
            z-index: 2;
            margin-top: 1px;
        }

        .badge-body {
            flex: 1;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .student-photo-wrapper {
            width: 75px;
            height: 90px;
            border-radius: 6px;
            border: 2px solid #1e3a8a;
            overflow: hidden;
            margin-bottom: 8px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .student-photo { width: 100%; height: 100%; object-fit: cover; }

        .student-name-khmer {
            font-family: var(--font-moul);
            font-size: 11px;
            color: var(--primary);
            line-height: 1.3;
            margin-bottom: 2px;
        }
        .student-name-latin {
            font-family: var(--font-latin);
            font-size: 9px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .student-info-table {
            width: 100%;
            font-size: 10px;
            text-align: left;
            border-collapse: collapse;
            margin-top: auto;
        }
        .student-info-table td { padding: 2px 0; }
        .info-lbl { color: #64748b; font-weight: 600; width: 65px; }
        .info-val { color: #0f172a; font-weight: 700; }

        .badge-footer {
            background: #f8fafc;
            border-top: 1px dashed #cbd5e1;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 8px;
            color: #64748b;
        }

        @media print {
            body { background: none; padding: 0; }
            .action-toolbar { display: none !important; }
            .id-cards-grid {
                max-width: 100% !important;
                gap: 15px !important;
            }
            .id-badge {
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <div class="action-toolbar">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>ត្រឡប់ក្រោយ
            </a>
            <span style="font-weight: 600; margin-left: 12px;">សរុបសិស្សថ្នាក់ {{ $schoolClass->name }}៖ {{ $students->count() }} នាក់</span>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer-fill"></i>បោះពុម្ពកាតទាំងអស់ (Print Grid)
            </button>
        </div>
    </div>

    @php
        $schoolLogo = ($school && $school->logo_path && file_exists(public_path($school->logo_path)))
            ? asset($school->logo_path)
            : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : asset('images/logo.svg'));
    @endphp

    <div class="id-cards-grid">
        @foreach($students as $student)
            @php
                $profile = $student->studentProfile;
                $khmerName = trim(($profile?->last_name_khmer ?? '') . ' ' . ($profile?->first_name_khmer ?? ''));
                if (empty($khmerName)) {
                    $khmerName = trim(($profile?->last_name ?? '') . ' ' . ($profile?->first_name ?? $student->user?->name));
                }
                $latinName = trim(($profile?->last_name ?? '') . ' ' . ($profile?->first_name ?? ''));
                $gender = strtolower($profile?->gender ?? '');
                $schoolTitle = 'សាលា ប្រទីបបញ្ញា';
                if (!empty($school?->name)) {
                    $baseName = trim(preg_replace('/^(សាលារៀន|សាលា)\s*/u', '', $school->name));
                    $schoolTitle = 'សាលា ' . ($baseName ?: 'ប្រទីបបញ្ញា');
                }
            @endphp

            <div class="id-badge">
                <div class="badge-header">
                    <svg class="header-corner left" viewBox="0 0 50 50" fill="none">
                        <path d="M0,0 Q30,10 42,48" stroke="rgba(255,255,255,0.32)" stroke-width="3.5" stroke-linecap="round"/>
                        <path d="M0,10 Q24,18 34,48" stroke="rgba(254,240,138,0.28)" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M0,20 Q18,26 25,48" stroke="rgba(255,255,255,0.2)" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <svg class="header-corner right" viewBox="0 0 50 50" fill="none">
                        <path d="M50,0 Q20,10 8,48" stroke="rgba(255,255,255,0.32)" stroke-width="3.5" stroke-linecap="round"/>
                        <path d="M50,10 Q26,18 16,48" stroke="rgba(254,240,138,0.28)" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M50,20 Q32,26 25,48" stroke="rgba(255,255,255,0.2)" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <img src="{{ $schoolLogo }}" alt="Logo" class="badge-logo">
                    <div class="badge-school-name">{{ $schoolTitle }}</div>
                    <div class="badge-school-sub">BRATIB PANHA SCHOOL</div>
                </div>

                <div class="badge-body">
                    <div class="student-photo-wrapper">
                        @if($profile && $profile->photo && file_exists(storage_path('app/public/' . $profile->photo)))
                            <img src="{{ asset('storage/' . $profile->photo) }}" alt="Photo" class="student-photo">
                        @elseif($profile && $profile->photo && file_exists(public_path($profile->photo)))
                            <img src="{{ asset($profile->photo) }}" alt="Photo" class="student-photo">
                        @else
                            <i class="bi bi-person-fill" style="font-size: 45px; color: #94a3b8;"></i>
                        @endif
                    </div>

                    <div class="student-name-khmer">{{ $khmerName }}</div>
                    <div class="student-name-latin">{{ strtoupper($latinName ?: 'STUDENT') }}</div>

                    <table class="student-info-table">
                        <tr>
                            <td class="info-lbl">អត្តលេខ៖</td>
                            <td class="info-val">{{ $student->admission_number }}</td>
                        </tr>
                        <tr>
                            <td class="info-lbl">ថ្នាក់រៀន៖</td>
                            <td class="info-val">{{ $student->schoolClass?->name }}</td>
                        </tr>
                        <tr>
                            <td class="info-lbl">ទម្រង់៖</td>
                            <td class="info-val" style="color: {{ $student->study_mode === 'part_time' ? '#b45309' : '#1e40af' }}; font-weight: 800;">
                                {{ $student->study_mode === 'part_time' ? 'ក្រៅម៉ោង' : 'ពេញម៉ោង' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="info-lbl">ភេទ៖</td>
                            <td class="info-val">{{ $khmerGender }}</td>
                        </tr>
                    </table>
                </div>

                <div class="badge-footer">
                    <div>BPS-CARD</div>
                    <div>{{ $student->academicYear?->name ?? 'ឆ្នាំសិក្សា' }}</div>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>
