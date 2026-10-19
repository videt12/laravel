<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កាតបុគ្គលិក - {{ $employee->user->name ?? $employee->name ?? $employee->employee_code }}</title>
    <link rel="shortcut icon" href="{{ $school && $school->logo_path && file_exists(public_path($school->logo_path)) ? asset($school->logo_path) : asset('images/school_logo.png') }}">

    <!-- Google Fonts for Khmer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Moul&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --font-khmer: 'Kantumruy Pro', sans-serif;
            --font-moul: 'Moul', 'Khmer OS Muol', serif;
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
            padding: 24px 10px 40px 10px;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Action Toolbar ── */
        .action-toolbar {
            max-width: 620px;
            margin: 0 auto 24px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            padding: 10px 18px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s ease;
            font-family: var(--font-khmer);
        }
        .btn-outline {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        .btn-outline:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }
        .btn-primary {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
        }
        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.4);
        }

        /* ── Cards Container ── */
        .cards-wrapper {
            max-width: 620px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 32px;
            align-items: center;
        }

        /* ── CR80 Badge (Vertical: 54mm x 85.6mm standard ratio) ── */
        .id-badge {
            width: 280px;
            height: 440px;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 12px 30px -6px rgba(0, 0, 0, 0.12), 0 4px 10px -2px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            user-select: none;
        }

        /* ── Watermark Background (Authentic Khmer Emblem) ── */
        .card-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -34%);
            width: 245px;
            height: 245px;
            object-fit: contain;
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
            filter: grayscale(100%);
        }

        /* ══════════════════════════════════════════════════════════
           FRONT CARD STYLES
        ══════════════════════════════════════════════════════════ */
        .front-header {
            min-height: 126px;
            background: linear-gradient(180deg, #f59e0b 0%, #eab308 25%, #d97706 70%, #b45309 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5px 10px 7px 10px;
            border-bottom: 2px solid #92400e;
            box-shadow: 0 2px 6px rgba(180, 83, 9, 0.2);
            flex-shrink: 0;
            overflow: hidden;
            z-index: 1;
        }

        .header-corner {
            position: absolute;
            top: 0;
            width: 60px;
            height: 60px;
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
            object-fit: contain;
            background: #ffffff;
            border: 2px solid #ffffff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.22);
            margin: 0 auto 2px auto;
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
            text-align: center;
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
            text-align: center;
        }

        /* Front Card Body */
        .front-body {
            flex: 1;
            padding: 8px 16px 4px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            background: transparent;
            z-index: 1;
        }

        /* Photo Frame */
        .photo-container {
            width: 80px;
            height: 98px;
            border-radius: 8px;
            border: 2.5px solid #0f172a;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            margin-bottom: 4px;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }
        .employee-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            display: block;
        }
        .photo-avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e2e8f0;
            color: #94a3b8;
        }

        /* Staff Role & Tag */
        .staff-label {
            font-family: var(--font-latin);
            font-size: 11px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.8px;
            line-height: 1.2;
            position: relative;
            z-index: 2;
            margin-top: 2px;
        }

        .staff-role-pill {
            display: inline-block;
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
            font-family: var(--font-khmer);
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 18px;
            border-radius: 14px;
            margin-top: 3px;
            margin-bottom: 7px;
            position: relative;
            z-index: 2;
            line-height: 1.35;
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }

        /* Employee Info Table */
        .info-table {
            width: 216px;
            border-collapse: collapse;
            font-size: 10px;
            margin: 0 auto auto auto;
            position: relative;
            z-index: 2;
        }
        .info-table tr td {
            padding: 2.5px 0;
            vertical-align: middle;
        }
        .info-lbl {
            font-weight: 700;
            color: #0f172a;
            width: 102px;
            white-space: nowrap;
        }
        .info-val {
            font-weight: 700;
            color: #0f172a;
            word-break: break-word;
        }
        .info-val.val-code {
            font-family: var(--font-latin);
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        /* Front Card Footer Bar */
        .badge-footer-bar {
            height: 28px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 0 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }
        .footer-staff-id {
            font-size: 8.5px;
            font-weight: 800;
            color: #475569;
            letter-spacing: 0.3px;
            font-family: var(--font-latin);
        }
        .footer-org-tag {
            font-size: 8.5px;
            font-weight: 800;
            color: #475569;
            letter-spacing: 0.4px;
            font-family: var(--font-latin);
        }

        /* ══════════════════════════════════════════════════════════
           BACK CARD STYLES
        ══════════════════════════════════════════════════════════ */
        .back-badge {
            padding: 16px 14px 12px 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            background: #ffffff;
        }

        .back-header-wrap {
            text-align: center;
            margin-bottom: 12px;
            position: relative;
            z-index: 2;
        }

        .back-top-kbach {
            display: flex;
            justify-content: center;
            margin-bottom: 4px;
        }

        .back-title {
            font-family: var(--font-moul);
            font-size: 10.5px;
            color: #0f172a;
            line-height: 1.45;
            letter-spacing: 0.2px;
            text-align: center;
        }

        /* Conditions List (Strictly text-align left, NO justify gaps) */
        .conditions-list {
            list-style: none;
            padding: 0;
            margin: 0 0 16px 0;
            position: relative;
            z-index: 2;
            text-align: left !important;
        }
        .conditions-list li {
            font-size: 8.8px;
            line-height: 1.6;
            color: #1e293b;
            font-weight: 500;
            margin-bottom: 7px;
            padding-left: 12px;
            position: relative;
            text-align: left !important;
        }
        .conditions-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            top: 0;
            color: #0f172a;
            font-weight: 700;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Back Card Bottom Row */
        .back-bottom-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-top: auto;
            padding: 0 6px 4px 6px;
            position: relative;
            z-index: 2;
        }

        .qr-wrapper {
            text-align: center;
        }
        .qr-image {
            width: 68px;
            height: 68px;
            border: 1px solid #cbd5e1;
            padding: 2px;
            border-radius: 4px;
            background: #ffffff;
            display: block;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .qr-subtext {
            font-size: 6.5px;
            color: #64748b;
            font-weight: 700;
            margin-top: 3px;
            font-family: var(--font-latin);
            letter-spacing: 0.3px;
        }

        .principal-block {
            text-align: center;
            width: 105px;
        }
        .principal-title {
            font-family: var(--font-moul);
            font-size: 10px;
            color: #0f172a;
            margin-bottom: 34px;
        }
        .principal-sig-line {
            font-size: 8.5px;
            color: #64748b;
            font-weight: 600;
            border-top: 1px dotted #94a3b8;
            padding-top: 3px;
            display: block;
            width: 100%;
        }

        /* ── Print Media Query ── */
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
            }
            .action-toolbar {
                display: none !important;
            }
            .cards-wrapper {
                flex-direction: row !important;
                justify-content: center !important;
                gap: 20px !important;
                max-width: 100% !important;
                margin-top: 10mm;
            }
            .id-badge {
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
        }
    </style>
</head>
<body>

    {{-- TOP ACTION TOOLBAR --}}
    <div class="action-toolbar">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline">
                <span style="font-size: 14px; margin-right: 2px;">←</span> ត្រឡប់ក្រោយ
            </a>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer-fill"></i> បោះពុម្ពកាតបុគ្គលិក (Print Staff Badge)
            </button>
        </div>
    </div>

    @php
        // Photo URL resolution
        $photoUrl = $employee->photo_url;
        if (!$photoUrl && !empty($employee->photo)) {
            if (str_starts_with($employee->photo, 'http://') || str_starts_with($employee->photo, 'https://')) {
                $photoUrl = $employee->photo;
            } elseif (file_exists(public_path($employee->photo))) {
                $photoUrl = asset($employee->photo);
            } else {
                $photoUrl = asset('storage/' . ltrim($employee->photo, '/'));
            }
        }

        $schoolLogo = ($school && $school->logo_path && file_exists(public_path($school->logo_path)))
            ? asset($school->logo_path)
            : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : asset('images/logo.svg'));

        $phone = $employee->phone ?? $employee->employeeContact?->phone ?? $employee->contact?->phone ?? '—';
        $departmentName = $employee->department?->name ?? $employee->employee_department?->name ?? 'ទូទៅ';
        $positionName = $employee->position?->name ?? $employee->employee_position?->name ?? 'គ្រូបង្រៀន';
        $card = $card ?? \App\Models\Employees\EmployeeCard::getOrCreateActiveFor($employee);

        // Employee Name resolution (Khmer first, fallback to Latin / User name)
        $khmerName = trim(($employee->last_name_khmer ?? '') . ' ' . ($employee->first_name_khmer ?? ''));
        $latinName = trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')) ?: trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? ''));
        $employeeName = $khmerName ?: ($latinName ?: ($employee->user?->name ?? ($employee->name ?? 'បុគ្គលិក')));

        // School Name (includes 'សាលា')
        $schoolTitle = 'សាលា ប្រទីបបញ្ញា';
        if (!empty($school?->name)) {
            $baseName = trim(preg_replace('/^(សាលារៀន|សាលា)\s*/u', '', $school->name));
            $schoolTitle = 'សាលា ' . ($baseName ?: 'ប្រទីបបញ្ញា');
        }
    @endphp

    <div class="cards-wrapper">

        {{-- ════════════════════════════════════════════════════════
             1. FRONT OF ID BADGE (កាតបុគ្គលិក - ផ្ទៃខាងមុខ)
        ════════════════════════════════════════════════════════ --}}
        <div class="id-badge">

            {{-- Authentic Traditional Khmer Emblem Watermark --}}
            <img src="{{ asset('images/center_emblem.png') }}" class="card-watermark" alt="Emblem Watermark">

            {{-- Golden Header Matching Reference Design --}}
            <div class="front-header">
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

                {{-- School Logo --}}
                <img src="{{ $schoolLogo }}" alt="Logo" class="badge-logo">

                {{-- School Name --}}
                <div class="badge-school-name">{{ $schoolTitle }}</div>
                <div class="badge-school-sub">BRATIB PANHA SCHOOL</div>
            </div>

            {{-- Card Body --}}
            <div class="front-body">
                {{-- Photo Box --}}
                <div class="photo-container">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" alt="Photo" class="employee-photo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="photo-avatar-placeholder" style="display: none;">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    @else
                        <div class="photo-avatar-placeholder">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Staff Header Label --}}
                <div class="staff-label">STAFF</div>

                {{-- Staff Name Pill (Matches reference image [ ល្មាត ]) --}}
                <div class="staff-role-pill">
                    {{ $employeeName }}
                </div>

                {{-- Information Table --}}
                <table class="info-table">
                    <tr>
                        <td class="info-lbl">អត្តលេខ៖</td>
                        <td class="info-val val-code">{{ $employee->employee_code }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">តំណែង៖</td>
                        <td class="info-val">{{ $positionName ?: 'គ្រូបង្រៀន' }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">ផ្នែក/ដេប៉ាតឺម៉ង់៖</td>
                        <td class="info-val">{{ $departmentName }}</td>
                    </tr>
                    <tr>
                        <td class="info-lbl">ទូរស័ព្ទ៖</td>
                        <td class="info-val val-code">{{ $phone }}</td>
                    </tr>
                </table>
            </div>

            {{-- Bottom Footer Bar --}}
            <div class="badge-footer-bar">
                <div class="footer-staff-id">STAFF-ID: {{ $employee->employee_code }}</div>
                <div class="footer-org-tag">BPS-STAFF</div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════
             2. BACK OF ID BADGE (កាតបុគ្គលិក - ផ្ទៃខាងក្រោយ)
        ════════════════════════════════════════════════════════ --}}
        <div class="id-badge back-badge">

            {{-- Authentic Traditional Khmer Emblem Watermark --}}
            <img src="{{ asset('images/center_emblem.png') }}" class="card-watermark" alt="Emblem Watermark">

            <div>
                {{-- Back Card Title & Kbach flourish --}}
                <div class="back-header-wrap">
                    <div class="back-top-kbach">
                        <img src="{{ asset('images/khmer_divider_trans.png') }}" alt="Flourish" style="height: 14px; width: auto; opacity: 0.85; margin: 0 auto; display: block;">
                    </div>
                    <div class="back-title">លក្ខខណ្ឌនៃការប្រើប្រាស់កាតបុគ្គលិក</div>
                </div>

                {{-- Rules / Terms List --}}
                <ul class="conditions-list">
                    <li>កាតនេះត្រូវពាក់ជាប់ជាប្រចាំក្នុងពេលបំពេញការងារនៅសាលារៀន។</li>
                    <li>បុគ្គលិកត្រូវប្រើកាតនេះសម្រាប់ការស្កេនវត្តមាននិងការចេញចូល។</li>
                    <li>ក្នុងករណីបាត់បង់ សូមរាយការណ៍បន្ទាន់ទៅកាន់ការិយាល័យធនធានមនុស្ស (HR)។</li>
                    <li>កាតនេះជាកម្មសិទ្ធិរបស់សាលារៀន ប្រគល់ជូនវិញពេលឈប់។</li>
                </ul>
            </div>

            {{-- Bottom Section: QR Code + Principal Signature --}}
            <div class="back-bottom-row">
                <div class="qr-wrapper">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($card->card_token ?? $employee->employee_code) }}"
                         alt="QR Code"
                         class="qr-image">
                    <div class="qr-subtext">{{ $card->card_number ?? $employee->employee_code }}</div>
                </div>

                <div class="principal-block">
                    <div class="principal-title">នាយកសាលា</div>
                    <div class="principal-sig-line">ហត្ថលេខា និងត្រា</div>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
