<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កិច្ចសន្យាការងារ — {{ $employee_contract->contract_number }} — {{ $employee_contract->employee?->user?->name ?? 'បុគ្គលិក' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Moul&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --khmer-body-font: 'Kantumruy Pro', 'Khmer OS Battambang', 'Segoe UI', sans-serif;
            --khmer-title-font: 'Moul', 'Khmer OS Muol Light', 'Kantumruy Pro', serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f1f5f9;
            font-family: var(--khmer-body-font);
            font-size: 13.5pt;
            line-height: 1.85;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Top Action Bar (hidden in print) */
        .top-action-bar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            font-family: var(--khmer-body-font);
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .btn-print {
            background: #e8a838;
            color: #ffffff;
        }
        .btn-print:hover {
            background: #d97706;
            color: #ffffff;
        }
        .btn-back {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
        }
        .btn-back:hover {
            background: #f8fafc;
        }

        /* A4 Page Container */
        .page-container {
            width: 210mm;
            min-height: 297mm;
            padding: 22mm 24mm 22mm 24mm;
            margin: 24px auto;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 2px;
            position: relative;
        }

        /* Official Document Header */
        .doc-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .doc-header h2 {
            font-family: var(--khmer-title-font);
            font-size: 15.5pt;
            font-weight: normal;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .doc-header h3 {
            font-family: var(--khmer-title-font);
            font-size: 13.5pt;
            font-weight: normal;
            margin-bottom: 12px;
        }
        .doc-header-divider {
            display: inline-block;
            width: 70px;
            height: 1.5px;
            background: #0f172a;
            margin-bottom: 18px;
        }
        .doc-title {
            font-family: var(--khmer-title-font);
            font-size: 16.5pt;
            font-weight: normal;
            margin: 10px 0 20px;
            text-decoration: underline;
            text-underline-offset: 6px;
        }

        /* Content Styling */
        p, .article-content {
            text-align: justify;
            margin-bottom: 0.85rem;
            text-indent: 1.5rem;
        }
        .no-indent {
            text-indent: 0 !important;
        }

        .party-block {
            margin-bottom: 1rem;
            line-height: 1.9;
        }

        .section-title {
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            text-indent: 0;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }
        .section-number {
            font-weight: 700;
            min-width: 85px;
        }

        .highlight-val {
            font-weight: 600;
            color: #0f172a;
            border-bottom: 1px dotted #475569;
            padding: 0 4px;
        }

        /* Signatures */
        .signature-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 3.5rem;
            text-align: center;
        }
        .signature-col {
            width: 45%;
        }
        .signature-col p {
            text-indent: 0;
            font-weight: 600;
            margin-bottom: 5rem;
        }

        .page-number {
            position: absolute;
            bottom: 12mm;
            right: 20mm;
            font-size: 10pt;
            color: #64748b;
        }

        /* Print Settings */
        @media print {
            body {
                background: #ffffff;
                font-size: 12.5pt;
                line-height: 1.75;
            }
            .top-action-bar {
                display: none !important;
            }
            .page-container {
                width: 100%;
                min-height: 100vh;
                margin: 0;
                padding: 16mm 18mm;
                box-shadow: none;
                border-radius: 0;
                page-break-after: always;
            }
            .page-container:last-child {
                page-break-after: avoid;
            }
            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body>

@php
    $khmerMonths = [
        1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
        5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
        9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ'
    ];

    $khmerDigits = ['0'=>'០','1'=>'១','2'=>'២','3'=>'៣','4'=>'៤','5'=>'៥','6'=>'៦','7'=>'៧','8'=>'៨','9'=>'៩'];
    $toKhmerNum = function($num) use ($khmerDigits) {
        return strtr((string)$num, $khmerDigits);
    };

    $emp = $employee_contract->employee;
    $empName = $emp?->user?->name ?? '..................................';
    $gender = ($emp?->gender == 'Female') ? 'ស្រី' : (($emp?->gender == 'Male') ? 'ប្រុស' : '...........');
    $age = $emp?->date_of_birth ? $toKhmerNum(\Carbon\Carbon::parse($emp->date_of_birth)->age) : '២៤';
    
    $startDate = $employee_contract->start_date ?? now();
    $startDay = $toKhmerNum($startDate->format('d'));
    $startMonth = $khmerMonths[(int)$startDate->format('m')] ?? 'មករា';
    $startYear = $toKhmerNum($startDate->format('Y'));

    $endDate = $employee_contract->end_date ?? $startDate->copy()->addYear();
    $endDay = $toKhmerNum($endDate->format('d'));
    $endMonth = $khmerMonths[(int)$endDate->format('m')] ?? 'មករា';
    $endYear = $toKhmerNum($endDate->format('Y'));

    $durationMonths = $startDate->diffInMonths($endDate);
    if ($durationMonths <= 0) $durationMonths = 12;
    $durationYears = max(1, round($durationMonths / 12));

    $durationMonthsKh = $toKhmerNum($durationMonths);
    $durationYearsKh = $toKhmerNum($durationYears);

    $position = $emp?->employee_position?->name ?? 'គ្រូបង្រៀន';
    $department = $emp?->employee_department?->name ?? 'ភាសាខ្មែរ អង់គ្លេស';
    
    $salary = $employee_contract->salary ? $toKhmerNum(number_format($employee_contract->salary, 0)) : '២៥០';
@endphp

{{-- TOP ACTION BAR --}}
<div class="top-action-bar">
    <div style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-file-earmark-text me-1 text-warning"></i>
        កិច្ចសន្យាការងារផ្លូវការ — {{ $employee_contract->contract_number }} ({{ $empName }})
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('employee-contracts.show', $employee_contract) }}" class="btn-action btn-back">
            <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="bi bi-printer"></i> បោះពុម្ព / ទាញយកជា PDF
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     PAGE 1
═══════════════════════════════════════════════════════════════ --}}
<div class="page-container">
    <div class="doc-header">
        <h2>ព្រះរាជាណាចក្រកម្ពុជា</h2>
        <h3>ជាតិ សាសនា ព្រះមហាក្សត្រ</h3>
        <div class="doc-header-divider"></div>
        <div class="doc-title">កិច្ចសន្យាការងារ</div>
    </div>

    <div class="party-block">
        <p class="no-indent">
            កិច្ចសន្យានេះធ្វើឡើង និង មានសុពលភាពគិតចាប់ពីថ្ងៃទី <span class="highlight-val">{{ $startDay }}</span> ខែ <span class="highlight-val">{{ $startMonth }}</span> ឆ្នាំ <span class="highlight-val">{{ $startYear }}</span> នេះតទៅដោយមានភាគី៖
        </p>
        <p class="no-indent" style="margin-top: 6px;">
            <strong>សាលារៀន ប្រទីបបញ្ញា</strong> តំណាងដោយឈ្មោះ <strong>សូ វាសនា</strong> ភេទ <strong>ស្រី</strong> តួនាទីជា <strong>នាយិកាសាលា</strong> ហៅកាត់ភាគី.ក. (និយោជក)។
        </p>
        <p class="no-indent" style="margin-top: 10px; text-align: center; font-weight: 700;">
            និង
        </p>
        <p class="no-indent" style="margin-top: 10px;">
            ឈ្មោះ <strong><span class="highlight-val">{{ $empName }}</span></strong> ភេទ <strong>{{ $gender }}</strong> អាយុ <strong>{{ $age }}</strong> ឆ្នាំ សញ្ជាតិ <strong>ខ្មែរ</strong> កាន់អត្តសញ្ញាណប័ណ្ណលេខ................................... ចុះថ្ងៃទី.......... ខែ.......... ឆ្នាំ........... កាន់លិខិតឆ្លងដែនលេខ........................ ចុះថ្ងៃទី.......... ខែ.......... ឆ្នាំ........... ហៅកាត់ថាភាគី.ខ. (និយោជិត)។
        </p>
    </div>

    <div style="margin: 1.25rem 0;">
        <h4 style="font-weight: 700; text-align: center; margin-bottom: 0.5rem; text-decoration: underline; text-underline-offset: 4px;">បុព្វកថា</h4>
        <p>
            ដោយហេតុថា និយោជក ភាគី.ក. និង និយោជិត ភាគី.ខ. បានយល់ព្រមចុះកិច្ចសន្យាការងារតាមច្បាប់ស្តីពីការងារ នៃព្រះរាជាណាចក្រកម្ពុជា ដូចមានចែងក្នុងប្រការខាងក្រោមនេះ៖
        </p>
    </div>

    <div>
        <div class="section-title">
            <span class="section-number">ប្រការ១ ៖</span>
            <span>រយៈពេលនៃកិច្ចសន្យាការងារ</span>
        </div>
        <p>
            ១.១ កិច្ចសន្យាការងារសម្រាប់រយៈពេល <span class="highlight-val">{{ $durationMonthsKh }}</span> ខែ ឬ <span class="highlight-val">{{ $durationYearsKh }}</span> ឆ្នាំ ដោយគិតចាប់ពីថ្ងៃដំបូងនៃការចូលបម្រើការងារដែលនឹង គិត ចាប់ពីថ្ងៃទី <span class="highlight-val">{{ $startDay }}</span> ខែ <span class="highlight-val">{{ $startMonth }}</span> ឆ្នាំ <span class="highlight-val">{{ $startYear }}</span> រហូតដល់ថ្ងៃទី <span class="highlight-val">{{ $endDay }}</span> ខែ <span class="highlight-val">{{ $endMonth }}</span> ឆ្នាំ <span class="highlight-val">{{ $endYear }}</span> ។ ការធ្វើកិច្ចសន្យាថ្មីនឹងធ្វើទៅបាន លុះត្រាតែមានការយល់ព្រមដោយភាគីទាំងពីរ។ យ៉ាងហោចណាស់ភាគីនិយោជិតត្រូវជូនដំណឹង ក្នុងរយៈពេល១ខែមុនពេលបញ្ចប់កិច្ចសន្យានេះ។ ក្នុងករណី និយោជិតខកខានមិនបានជូនដំណឹងអំពីការបន្តឬ បញ្ចប់កិច្ចសន្យាការងារឲ្យបានរយៈពេល១ខែមុន អាណត្តិកិច្ចសន្យានេះ មានន័យថានិយោជក និង និយោជិតបានយល់ព្រមបន្តកិច្ចសន្យាថ្មីរយៈពេល <span class="highlight-val">{{ $durationMonthsKh }}</span> ខែ ឬ <span class="highlight-val">{{ $durationYearsKh }}</span> ឆ្នាំទៀតដោយស្វ័យប្រវត្តិ។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ២ ៖</span>
            <span>ការសាកល្បងការងារ</span>
        </div>
        <p>
            ២.១: និយោជិត ត្រូវស្ថិតក្នុងរយៈពេលសាកល្បងការងារ រយៈពេល៣ (បី)ខែដោយគិតចាប់ ពី ថ្ងៃទី <span class="highlight-val">{{ $startDay }}</span> ខែ <span class="highlight-val">{{ $startMonth }}</span> ឆ្នាំ <span class="highlight-val">{{ $startYear }}</span> នៃការចាប់ផ្តើមចូលបម្រើការងារនៅក្នុងរយៈពេលសាកល្បងនេះ ប្រសិនបើពិនិត្យឃើញថា ភាគី.ខ. ពុំមានសមត្ថភាពគ្រប់គ្រាន់ក្នុងការបំពេញការងារ នោះភាគី.ក. មានសិទ្ធិបញ្ចប់ កិច្ចសន្យាជាមួយ ភាគី.ខ. ឬ អនុញ្ញាតឲ្យភាគី.ខ. បន្តការសាកល្បងបន្ថែមទៀត។ ប៉ុន្តែក្រោយពេលអនុញ្ញាតឲ្យបន្តការសាកល្បងភាគី.ខ.នៅតែពុំមានសមត្ថភាពគ្រប់គ្រាន់ក្នុងការបំពេញភារកិច្ចនោះ
        </p>
    </div>

    <div class="page-number">ទំព័រទី ១</div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     PAGE 2
═══════════════════════════════════════════════════════════════ --}}
<div class="page-container">
    <div>
        <p class="no-indent">
            ភាគី.ក. នឹងបញ្ចប់កិច្ចសន្យានេះទាំងស្រុងតែម្តង។ ក្នុងរយៈពេលសាកល្បងការងារនេះមានតែភាគីនិយោជកប៉ុណ្ណោះ ដែលអាចបញ្ចប់កិច្ចព្រមព្រៀងនេះបាន ដោយជូនដំណឹងរយៈពេល១សប្តាហ៍ ហើយ ធ្វើការទូទាត់ប្រាក់ខែបង្គរគ្រប់១ខែ ដល់ភាគីនិយោជិតផ្អែកលើមូលហេតុសមត្ថភាព និង ចរិតលក្ខណៈ បំពេញការងារ។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ៣ ៖</span>
            <span>តួនាទីការងារ</span>
        </div>
        <p>
            ៣.១ ភាគី.ក. ជួលភាគី.ខ. ឱ្យធ្វើការងារហើយភាគី.ខ. ត្រូវធ្វើការដោយស្មោះត្រង់ និងអស់ពីកម្លាំងកាយចិត្តក្នុងការបំពេញការងារដល់ភាគី.ក. ក្នុងមុខងារជា <strong><span class="highlight-val">{{ $position }}</span></strong> នៅក្នុងផ្នែក <strong><span class="highlight-val">{{ $department }}</span></strong> ចាប់ពីថ្ងៃទី <span class="highlight-val">{{ $startDay }}</span> ខែ <span class="highlight-val">{{ $startMonth }}</span> ឆ្នាំ <span class="highlight-val">{{ $startYear }}</span> ។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ៤ ៖</span>
            <span>ប្រាក់បៀវត្ស និង ពន្ធលើប្រាក់បៀវត្ស</span>
        </div>
        <p>
            ៤.១ និយោជិត ត្រូវចាប់ផ្តើមការងារដោយមានប្រាក់ខែជាមូលដ្ឋានស្មើនឹង <strong><span class="highlight-val">{{ $salary }}</span></strong> គិតជាដុល្លារអាមេរិក។ ប៉ុន្តែក្នុងរយៈពេលសាកល្បងភាគី.ខ. នឹងត្រូវបានផ្តល់ជូននូវប្រាក់បៀវត្សចំនួន................................ ក្នុង១ខែ គិតជាដុល្លារអាមេរិក។
        </p>
        <p>
            ៤.២: ការបង់ពន្ធលើប្រាក់បៀវត្សគឺជា ការទទួលខុសត្រូវរបស់ភាគីនិយោជិត ប៉ុន្តែភាគីនិយោជកជាអ្នកបង់ពន្ធជំនួស នៅតាមតម្លៃពន្ធដែលរដ្ឋបានកំណត់ ហើយការបង់ពន្ធនេះទៀតសោតត្រូវកាត់ចេញពី ប្រាក់ខែរបស់និយោជិត។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ៥ ៖</span>
            <span>ការតម្លើងឋានៈ</span>
        </div>
        <p>
            ៥.១: ក្នុងអំឡុងពេលបំពេញការងារជាបុគ្គលិកពេញសិទ្ធិ និយោជកមានសិទ្ធិតម្លើងប្រាក់ខែជូនដល់និយោជិតទៅតាមសមត្ថភាពនៃការធ្វើការ និងឆន្ទៈក្នុងការបំពេញការងាររបស់និយោជិត។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ៦ ៖</span>
            <span>ប្រាក់ខែទី១៣ និងប្រាក់បំណាច់ឆ្នាំ</span>
        </div>
        <p>
            ៦.១: ប្រាក់បន្ថែមលើការអនុវត្តការងារសម្រាប់បំណាច់ឆ្នាំគឺជាការសម្រេចទាំងស្រុងរបស់ភាគីនិយោជក ហើយ វាអាស្រ័យទៅលើដំណើរការប្រព្រឹត្តទៅទាំងមូលរបស់ក្រុមហ៊ុន សហគ្រាស និងនិយោជិត។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ៧ ៖</span>
            <span>ម៉ោងធ្វើការ និងការងារបន្ថែមម៉ោង</span>
        </div>
        <p>
            ៧.១: ក្នុងរយៈពេលបំពេញការងាររបស់ខ្លួន និយោជិតត្រូវធ្វើការរយៈពេល ៨ម៉ោងក្នុង ១ថ្ងៃ និង ៥ថ្ងៃក្នុង ១សប្តាហ៍។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ៨ ៖</span>
            <span>ការឈប់សម្រាក</span>
        </div>
        <p>
            ៨.១: ភាគីនិយោជិត អាចឈប់សម្រាកនៅតាមថ្ងៃឈប់សម្រាកសាធារណៈដែលបានកំណត់ដោយរដ្ឋហើយនិង អនុលោមតាមច្បាប់ស្តីពីការងារ និងច្បាប់ផ្សេងៗទៀតនៃព្រះរាជាណាចក្រកម្ពុជា។
        </p>
        <p>
            ៨.២: ភាគីនិយោជិត អាចឈប់សម្រាកក្នុងពេលមានជំងឺ ការរៀបអាពាហ៍ពិពាហ៍ផ្ទាល់ខ្លួន បងប្អូនបង្កើត បុណ្យសពសាច់ញាតិជាដើម។
        </p>
    </div>

    <div class="page-number">ទំព័រទី ២</div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     PAGE 3
═══════════════════════════════════════════════════════════════ --}}
<div class="page-container">
    <div>
        <div class="section-title" style="margin-top: 0;">
            <span class="section-number">ប្រការ៩ ៖</span>
            <span>ការលំហែមាតុភាព</span>
        </div>
        <p>
            ៩.១: ភាគីនិយោជិត (ស្ត្រី) អាចឈប់សម្រាកក្នុងពេលសម្រាលកូន ក្នុងរយៈពេល៣ (បី)ខែបាន ហើយត្រូវ ចូលបម្រើការងារវិញ តាមការកំណត់របស់ភាគីនិយោជក។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ១០ ៖</span>
            <span>ការលាឈប់មុនកិច្ចសន្យា</span>
        </div>
        <p>
            ១០.១: ភាគីនិយោជិតអាចលាឈប់មុនការបញ្ចប់កិច្ចសន្យានេះបាន ដោយត្រូវធ្វើការជូនដំណឹងជាមុនជាលាយលក្ខណ៍អក្សរទៅភាគីនិយោជកក្នុងរយៈពេល១ (មួយ)ខែ ចំពោះនិយោជិតដែលបំពេញការងារ ក្រោម៥ (ប្រាំ)ឆ្នាំ។
        </p>
        <p>
            ១០.២: និយោជិត ដែលបំពេញការងារលើសពី៥ (ប្រាំ)ឆ្នាំ ត្រូវធ្វើការជូនដំណឹងយ៉ាងតិច៦ (ប្រាំមួយ)ខែទៅភាគី និយោជក។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ១១ ៖</span>
            <span>ការធានាសុវត្ថិភាពក្នុងពេលបំពេញការងារ</span>
        </div>
        <p>
            ១១.១: នៅក្នុងពេលបំពេញការងារ ភាគីនិយោជកត្រូវធានានូវសុវត្ថិភាពជូនដល់ភាគីនិយោជិត។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ១២ ៖</span>
            <span>ការគោរពវិន័យ</span>
        </div>
        <p>
            ១២.១: ភាគីនិយោជិត ត្រូវគោរពទៅតាមបទបញ្ជាផ្ទៃក្នុង និងបទបញ្ញត្តិផ្សេងៗរបស់ក្រុមហ៊ុន ឬសហគ្រាស។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ១៣ ៖</span>
            <span>ការដោះស្រាយវិវាទការងារ</span>
        </div>
        <p>
            ១៣.១: ក្នុងករណីដែល មានវិវាទកើតឡើង ភាគីនិយោជក និងភាគីនិយោជិតយកការដោះស្រាយដោយក្រុមប្រឹក្សា អាជ្ញាកណ្តាល។
        </p>
        <p>
            ១៣.២: ករណី ដែលការដោះស្រាយដោយក្រុមប្រឹក្សាអាជ្ញាកណ្តាលនៅតែមិនត្រូវរ៉ូវគ្នានោះទេ ភាគីទាំងពីរយក ការដោះស្រាយតាមផ្លូវច្បាប់ហើយ ព្រមទទួលយកតាមសេចក្តីសម្រេចរបស់តុលាការ។
        </p>

        <div class="section-title">
            <span class="section-number">ប្រការ១៤ ៖</span>
            <span>បញ្ញត្តិផ្សេងៗ</span>
        </div>
        <p>
            ១៤.១: ភាគីនិយោជិត យល់ព្រមអនុវត្តតាមកិច្ចសន្យានេះទាំងស្រុង ហើយបានអានចប់សព្វគ្រប់នូវខ្លឹមសារនៃ កិច្ចសន្យានេះ។
        </p>
        <p>
            ១៤.២: កិច្ចសន្យានេះធ្វើឡើងជាភាសាខ្មែរ មានចំនួន..២..ច្បាប់។
        </p>

        {{-- SIGNATURES --}}
        <div class="signature-grid">
            <div class="signature-col">
                <p>ហត្ថលេខា និងត្រា ក្រុមហ៊ុន ឬសហគ្រាស<br><span style="font-weight: normal; font-size: 11pt; color: #64748b;">(សាលារៀន ប្រទីបបញ្ញា)</span></p>
                <div style="font-weight: 700;">សូ វាសនា</div>
            </div>
            <div class="signature-col">
                <p>ហត្ថលេខា និងឈ្មោះ (និយោជិត)<br><span style="font-weight: normal; font-size: 11pt; color: #64748b;">&nbsp;</span></p>
                <div style="font-weight: 700;">{{ $empName }}</div>
            </div>
        </div>
    </div>

    <div class="page-number">ទំព័រទី ៣</div>
</div>

</body>
</html>
