<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>លិខិតបញ្ជាក់ការសិក្សា - {{ $student->studentProfile?->first_name ?? $student->admission_number }}</title>
    <link rel="shortcut icon" href="{{ $school && $school->logo_path && file_exists(public_path($school->logo_path)) ? asset($school->logo_path) : asset('images/school_logo.png') }}">

    <!-- Google Fonts for Khmer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&family=Moul&family=Siemreap&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --font-body: 'Siemreap', 'Kantumruy Pro', sans-serif;
            --font-moul: 'Moul', serif;
            --primary: #1e3a8a;
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
            font-size: 14.5px;
            line-height: 1.8;
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
            font-family: 'Kantumruy Pro', sans-serif;
        }
        .btn-primary { background: #2563eb; color: #ffffff; }
        .btn-outline { background: #ffffff; border: 1px solid #cbd5e1; color: #475569; }

        /* Certificate Paper (A4 size) */
        .cert-paper {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 50px;
            border-radius: 4px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        /* Certificate Border */
        .cert-border {
            border: 3px double #1e3a8a;
            padding: 35px 40px;
            position: relative;
        }
        .cert-border::after {
            content: "";
            position: absolute;
            top: 4px; left: 4px; right: 4px; bottom: 4px;
            border: 1px solid #b45309;
            pointer-events: none;
        }

        /* Top Heading */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .school-header-left {
            text-align: center;
            width: 260px;
        }
        .ministry-title {
            font-family: var(--font-moul);
            font-size: 11px;
            color: #0f172a;
            line-height: 1.5;
        }
        .school-title {
            font-family: var(--font-moul);
            font-size: 13px;
            color: var(--primary);
            line-height: 1.5;
            margin-top: 4px;
        }
        .cert-number {
            font-size: 12.5px;
            color: #475569;
            margin-top: 6px;
        }

        .kingdom-header-right {
            text-align: center;
            width: 260px;
        }
        .kingdom-main {
            font-family: var(--font-moul);
            font-size: 14px;
            color: #0f172a;
            line-height: 1.6;
        }
        .kingdom-sub {
            font-family: var(--font-moul);
            font-size: 12px;
            color: #b45309;
            line-height: 1.5;
            margin-top: 4px;
        }

        /* Title */
        .cert-title-container {
            text-align: center;
            margin: 20px 0 30px 0;
        }
        .cert-main-title {
            font-family: var(--font-moul);
            font-size: 21px;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .cert-sub-title {
            font-family: 'Inter', sans-serif;
            font-size: 11.5px;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Certificate Body Content */
        .cert-body-text {
            font-size: 15px;
            line-height: 2.2;
            text-align: justify;
            margin-bottom: 30px;
        }

        .highlight-text {
            font-weight: 700;
            color: #0f172a;
        }
        .khmer-name-bold {
            font-family: var(--font-moul);
            font-size: 14.5px;
            color: var(--primary);
            padding: 0 4px;
        }

        /* Photo & Seal Area */
        .photo-and-signature-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            padding: 0 10px;
        }

        .photo-seal-box {
            width: 100px;
            height: 125px;
            border: 1px dashed #94a3b8;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            overflow: hidden;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        .student-cert-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .signature-block {
            text-align: center;
            width: 240px;
        }
        .cert-date {
            font-size: 13px;
            color: #334155;
            margin-bottom: 8px;
        }
        .principal-title {
            font-family: var(--font-moul);
            font-size: 13px;
            color: #0f172a;
            margin-bottom: 75px;
        }
        .principal-seal-line {
            font-size: 12px;
            color: #64748b;
            border-top: 1px dotted #94a3b8;
            padding-top: 4px;
        }

        @media print {
            body { background: none; padding: 0; }
            .action-toolbar { display: none !important; }
            .cert-paper {
                box-shadow: none !important;
                border: none !important;
                padding: 10px !important;
                max-width: 100% !important;
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
                <i class="bi bi-printer-fill"></i>បោះពុម្ពលិខិតបញ្ជាក់ (Print Certificate)
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

        // Khmer months mapping
        $khmerMonths = [
            1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
            5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
            9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ'
        ];
        $khmerDigits = ['0' => '០', '1' => '១', '2' => '២', '3' => '៣', '4' => '៤', '5' => '៥', '6' => '៦', '7' => '៧', '8' => '៨', '9' => '៩'];
        $toKhmerNum = function($val) use ($khmerDigits) {
            return strtr((string)$val, $khmerDigits);
        };

        // DOB in Khmer
        $dobFormatted = '—';
        if ($profile && $profile->dob) {
            $c = \Carbon\Carbon::parse($profile->dob);
            $dobFormatted = 'ថ្ងៃទី ' . $toKhmerNum($c->format('d')) . ' ខែ ' . ($khmerMonths[(int)$c->format('m')] ?? '') . ' ឆ្នាំ ' . $toKhmerNum($c->format('Y'));
        }

        // Today in Khmer
        $now = now();
        $todayKhmer = 'ថ្ងៃទី ' . $toKhmerNum($now->format('d')) . ' ខែ ' . ($khmerMonths[(int)$now->format('m')] ?? '') . ' ឆ្នាំ ' . $toKhmerNum($now->format('Y'));

        $schoolLogo = ($school && $school->logo_path && file_exists(public_path($school->logo_path)))
            ? asset($school->logo_path)
            : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : asset('images/logo.svg'));
    @endphp

    <div class="cert-paper">
        <div class="cert-border">

            {{-- 1. Top Headers --}}
            <div class="top-header">
                <div class="school-header-left">
                    <div class="ministry-title">ក្រសួងអប់រំ យុវជន និងកីឡា</div>
                    <div class="school-title">{{ $school->name ?? 'សាលារៀន ប្រទីបបញ្ញា' }}</div>
                    <div class="cert-number">លេខ៖ .........../{{ $toKhmerNum($now->format('y')) }} ស.ប.ប</div>
                </div>

                <div class="kingdom-header-right">
                    <div class="kingdom-main">ព្រះរាជាណាចក្រកម្ពុជា</div>
                    <div class="kingdom-sub">ជាតិ សាសនា ព្រះមហាក្សត្រ</div>
                </div>
            </div>

            {{-- 2. Certificate Title --}}
            <div class="cert-title-container">
                <h1 class="cert-main-title">លិខិតបញ្ជាក់ការសិក្សា</h1>
                <div class="cert-sub-title">CERTIFICATE OF ENROLLMENT / STUDY</div>
            </div>

            {{-- 3. Body Text --}}
            <div class="cert-body-text">
                <p>
                    <strong>នាយក{{ $school->name ?? 'សាលារៀន ប្រទីបបញ្ញា' }}</strong> សូមបញ្ជាក់ថា ៖
                </p>
                <p>
                    សិស្សឈ្មោះ <span class="khmer-name-bold">{{ $khmerName }}</span> 
                    អក្សរឡាតាំង <strong style="font-family: 'Inter', sans-serif;">{{ strtoupper($latinName ?: '—') }}</strong> 
                    ភេទ <span class="highlight-text">{{ $khmerGender }}</span> 
                    សញ្ជាតិ <span class="highlight-text">{{ $profile?->nationality ?? 'ខ្មែរ' }}</span>
                </p>
                <p>
                    កើតថ្ងៃទី <span class="highlight-text">{{ $dobFormatted }}</span> 
                    អត្តលេខសិស្ស <span class="highlight-text">{{ $student->admission_number }}</span>
                </p>
                <p>
                    បច្ចុប្បន្នពិតជាកំពុងសិក្សារៀនសូត្រនៅ <strong>ថ្នាក់ទី {{ $student->schoolClass?->name ?? '—' }} {{ $student->section ? '(' . $student->section->name . ')' : '' }}</strong> ជាលក្ខណៈ <strong>{{ $student->study_mode === 'part_time' ? 'ក្រៅម៉ោង (Part-Time)' : 'ពេញម៉ោង (Full-Time)' }}</strong> នៃ{{ $school->name ?? 'សាលារៀន ប្រទីបបញ្ញា' }} សម្រាប់ <strong>{{ $student->academicYear?->name ?? 'ឆ្នាំសិក្សាបច្ចុប្បន្ន' }}</strong> ពិតប្រាកដមែន។
                </p>
                <p>
                    លិខិតបញ្ជាក់នេះ ចេញជូនសាមីខ្លួនយកទៅប្រើប្រាស់តាមផ្លូវច្បាប់ និងតាមការដែលអាចប្រើប្រាស់បាន។
                </p>
            </div>

            {{-- 4. Photo & Signature Footer --}}
            <div class="photo-and-signature-grid">
                <div class="photo-seal-box">
                    @if($profile && $profile->photo && file_exists(storage_path('app/public/' . $profile->photo)))
                        <img src="{{ asset('storage/' . $profile->photo) }}" alt="Photo" class="student-cert-photo">
                    @elseif($profile && $profile->photo && file_exists(public_path($profile->photo)))
                        <img src="{{ asset($profile->photo) }}" alt="Photo" class="student-cert-photo">
                    @else
                        <div>រូបថត (4x6)<br>បោះត្រាត្រួតលើ</div>
                    @endif
                </div>

                <div class="signature-block">
                    <div class="cert-date">រាជធានីភ្នំពេញ, {{ $todayKhmer }}</div>
                    <div class="principal-title">នាយកសាលា</div>
                    <div class="principal-seal-line">ហត្ថលេខា និងត្រា</div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
