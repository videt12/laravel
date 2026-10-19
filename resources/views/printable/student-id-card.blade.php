<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កាតសិស្ស - {{ $student->studentProfile?->first_name ?? $student->admission_number }}</title>
    <link rel="shortcut icon" href="{{ $school && $school->logo_path && file_exists(public_path($school->logo_path)) ? asset($school->logo_path) : asset('images/school_logo.png') }}">

    <!-- Google Fonts for Khmer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&family=Moul&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #0f172a;
            --gold: #f59e0b;
            --gold-dark: #b45309;
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
            max-width: 600px;
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

        .cards-wrapper {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: center;
        }

        /* CR80 Badge (Vertical: 54mm x 85.6mm) */
        .id-badge {
            width: 280px;
            height: 440px;
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Front Card Header */
        .badge-header {
            background: linear-gradient(180deg, #f59e0b 0%, #eab308 25%, #d97706 70%, #b45309 100%);
            border-bottom: 2px solid #92400e;
            box-shadow: 0 2px 6px rgba(180, 83, 9, 0.2);
            padding: 6px 10px 7px 10px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-corner {
            position: absolute;
            top: 0;
            width: 55px;
            height: 55px;
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
            width: 66px;
            height: 66px;
            border-radius: 50%;
            object-fit: cover;
            background: #ffffff;
            border: 2px solid #ffffff;
            margin: 0 auto 2px auto;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.22);
            display: block;
            position: relative;
            z-index: 2;
        }

        .badge-school-name {
            font-family: var(--font-moul);
            font-size: 11.5px;
            line-height: 1.2;
            color: #261503;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
            position: relative;
            z-index: 2;
            letter-spacing: 0.2px;
        }
        .badge-school-sub {
            font-family: var(--font-latin);
            font-size: 7.5px;
            letter-spacing: 0.9px;
            color: #261503;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.3);
            font-weight: 800;
            text-transform: uppercase;
            position: relative;
            z-index: 2;
            margin-top: 2px;
        }

        /* Photo Area */
        .badge-body {
            flex: 1;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: radial-gradient(circle at 50% 30%, #f8fafc 0%, #ffffff 100%);
        }

        .student-photo-wrapper {
            width: 90px;
            height: 105px;
            border-radius: 8px;
            border: 3px solid #1e3a8a;
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
            overflow: hidden;
            margin-bottom: 10px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .student-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-name-khmer {
            font-family: var(--font-moul);
            font-size: 13px;
            color: var(--primary);
            line-height: 1.3;
            margin-bottom: 2px;
        }
        .student-name-latin {
            font-family: var(--font-latin);
            font-size: 10px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .badge-role-tag {
            display: inline-block;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 14px;
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .student-info-table {
            width: 100%;
            font-size: 11px;
            text-align: left;
            border-collapse: collapse;
            margin-bottom: auto;
        }
        .student-info-table td {
            padding: 3px 0;
        }
        .info-lbl {
            color: #64748b;
            font-weight: 600;
            width: 75px;
        }
        .info-val {
            color: #0f172a;
            font-weight: 700;
        }

        /* Badge Footer */
        .badge-footer {
            background: #f8fafc;
            border-top: 1px dashed #cbd5e1;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .barcode-box {
            font-family: 'Inter', monospace;
            font-size: 10px;
            letter-spacing: 3px;
            font-weight: 800;
            color: #334155;
        }

        /* Back Card */
        .badge-back {
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 16px;
        }
        .back-title {
            font-family: var(--font-moul);
            font-size: 10.5px;
            color: var(--primary);
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .rules-list {
            list-style: none;
            font-size: 9.5px;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 12px;
        }
        .rules-list li {
            margin-bottom: 5px;
            padding-left: 12px;
            position: relative;
        }
        .rules-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #b45309;
            font-size: 12px;
        }

        .back-signature {
            text-align: center;
            margin-top: 10px;
        }
        .back-signature .sig-txt {
            font-size: 10px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 35px;
        }
        .back-signature .sig-line {
            font-size: 9px;
            color: #64748b;
            border-top: 1px dotted #cbd5e1;
            padding-top: 2px;
        }

        .back-contact {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 8px;
            font-size: 8.5px;
            text-align: center;
            color: #64748b;
            line-height: 1.4;
        }

        /* Print Media */
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .action-toolbar {
                display: none !important;
            }
            .cards-wrapper {
                flex-direction: row;
                justify-content: center;
                gap: 20px;
                max-width: 100%;
            }
            .id-badge {
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm;
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
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer-fill"></i>បោះពុម្ពកាតសិស្ស (Print Badge)
            </button>
        </div>
    </div>

    @php
        $profile = $student->studentProfile;
        $khmerName = trim(($profile?->last_name_khmer ?? '') . ' ' . ($profile?->first_name_khmer ?? ''));
        if (empty($khmerName)) {
            $khmerName = trim(($profile?->last_name ?? '') . ' ' . ($profile?->first_name ?? $student->user?->name));
        }
        $latinName = trim(($profile?->last_name ?? '') . ' ' . ($profile?->first_name ?? ''));
        $gender = strtolower($profile?->gender ?? '');
        $khmerGender = ($gender === 'male' || $gender === 'm' || $gender === 'ប្រុស') ? 'ប្រុស' : (($gender === 'female' || $gender === 'f' || $gender === 'ស្រី') ? 'ស្រី' : '—');

        $schoolLogo = ($school && $school->logo_path && file_exists(public_path($school->logo_path)))
            ? asset($school->logo_path)
            : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : asset('images/logo.svg'));

        $schoolTitle = 'សាលា ប្រទីបបញ្ញា';
        if (!empty($school?->name)) {
            $baseName = trim(preg_replace('/^(សាលារៀន|សាលា)\s*/u', '', $school->name));
            $schoolTitle = 'សាលា ' . ($baseName ?: 'ប្រទីបបញ្ញា');
        }
    @endphp

    <div class="cards-wrapper">

        {{-- 1. FRONT OF ID BADGE --}}
        <div class="id-badge">
            <div class="badge-header">
                {{-- Decorative Corner Swooshes --}}
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
                        <i class="bi bi-person-fill" style="font-size: 55px; color: #94a3b8;"></i>
                    @endif
                </div>

                <div class="student-name-khmer">{{ $khmerName }}</div>
                <div class="student-name-latin">{{ strtoupper($latinName ?: 'STUDENT') }}</div>

                <div class="badge-role-tag">សិស្ស (STUDENT)</div>

                <table class="student-info-table">
                    <tr>
                        <td class="info-lbl">អត្តលេខ ID៖</td>
                        <td class="info-val">{{ $student->admission_number }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">ថ្នាក់រៀន៖</td>
                        <td class="info-val">{{ $student->schoolClass?->name ?? '—' }} {{ $student->section ? '(' . $student->section->name . ')' : '' }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">ទម្រង់សិក្សា៖</td>
                        <td class="info-val" style="color: {{ $student->study_mode === 'part_time' ? '#b45309' : '#1e40af' }}; font-weight: 800;">
                            {{ $student->study_mode_label }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-lbl">ភេទ៖</td>
                        <td class="info-val">{{ $khmerGender }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">ឆ្នាំសិក្សា៖</td>
                        <td class="info-val">{{ $student->academicYear?->name ?? '—' }}</td>
                    </tr>
                </table>
            </div>

            <div class="badge-footer">
                <div class="barcode-box">||| | |||| | |||||</div>
                <div style="font-size: 9.5px; font-weight: 700; color: var(--primary);">BPS-CARD</div>
            </div>
        </div>

        {{-- 2. BACK OF ID BADGE --}}
        <div class="id-badge badge-back">
            <div>
                <div class="back-title">លក្ខខណ្ឌនៃការប្រើប្រាស់កាត</div>
                <ul class="rules-list">
                    <li>កាតនេះត្រូវតែពាក់ជាប់ខ្លួនជានិច្ចពេលនៅក្នុងបរិវេណសាលា។</li>
                    <li>មិនត្រូវផ្ទេរ ឬឱ្យអ្នកដទៃខ្ចីកាតនេះប្រើប្រាស់ឡើយ។</li>
                    <li>ក្នុងករណីបាត់បង់ ឬខូចខាត សូមរាយការណ៍ជូនការិយាល័យរដ្ឋបាលជាបន្ទាន់។</li>
                    <li>កាតនេះជាទ្រព្យសម្បត្តិរបស់សាលារៀន ប្រទីបបញ្ញា។</li>
                </ul>

                <div class="back-signature">
                    <div class="sig-txt">នាយកសាលា</div>
                    <div class="sig-line">ហត្ថលេខា និងត្រា</div>
                </div>
            </div>

            <div class="back-contact">
                <div style="font-weight: 700; color: #1e3a8a; margin-bottom: 2px;">{{ $school->name ?? 'សាលារៀន ប្រទីបបញ្ញា' }}</div>
                <div>ទូរស័ព្ទ៖ {{ $school->phone ?? '+855 23 000 000' }}</div>
                <div>អាសយដ្ឋាន៖ {{ $school->address ?? 'ភូមិគោកទ្រាស ឃុំឈ្នួរមានជ័យ ស្រុកព្រះនេត្រព្រះ ខេត្តបន្ទាយមានជ័យ' }}</div>
            </div>
        </div>

    </div>

</body>
</html>
