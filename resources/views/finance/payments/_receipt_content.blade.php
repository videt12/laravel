{{-- School Header --}}
<div class="school-header">
    <div class="school-logo-col">
        @php
            $schoolLogo = (file_exists(public_path('images/school_logo.png')))
                ? asset('images/school_logo.png')
                : (file_exists(public_path('images/logo.png')) ? asset('images/logo.png') : asset('images/logo.svg'));
        @endphp
        <img src="{{ $schoolLogo }}" alt="School Logo" class="school-logo-img">
    </div>

    <div class="school-title-col">
        <div class="school-khmer-name">សាលារៀន ប្រទីបបញ្ញា</div>
        <div class="school-eng-name">BRATIB PANHA SCHOOL</div>
        <div class="school-address">ភូមិគោកទ្រាស ឃុំឈ្នួរមានជ័យ ស្រុកព្រះនេត្រព្រះ ខេត្តបន្ទាយមានជ័យ</div>
    </div>

    <div class="school-contact-col">
        <div class="tell-header">Tell : 066-946-060</div>
        <div>: 071-566-5148</div>
        <div>: 012-671-217</div>
        <div>: 097-692-9048</div>
        <div class="receipt-no-box">
            No: <span class="text-dark">{{ $studentPayment->formatted_receipt_number }}</span>
        </div>
    </div>
</div>

{{-- Student & Academic Metadata Rows --}}
<div class="receipt-details">
    {{-- Row 1: Student Name, Gender, Old/New Student --}}
    <div class="info-row">
        <span>ឈ្មោះសិស្សបង់ប្រាក់</span>
        <span class="dotted-val dotted-val-khmer" style="min-width: 170px;">
            {{ $student?->user?->name ?? '..................................' }}
        </span>

        <span class="ms-1">ភេទ</span>
        <span class="dotted-val" style="min-width: 45px; text-align: center;">
            {{ $khmerGender }}
        </span>

        <span class="ms-1 check-item">
            សិស្សចាស់ <span class="check-circle {{ $isOldStudent ? 'checked' : '' }}">{!! $isOldStudent ? '&#10003;' : '' !!}</span>
        </span>
        <span class="check-item">
            សិស្សថ្មី <span class="check-circle {{ $isNewStudent ? 'checked' : '' }}">{!! $isNewStudent ? '&#10003;' : '' !!}</span>
        </span>
    </div>

    {{-- Row 2: Class, Shifts --}}
    <div class="info-row">
        <span>ថ្នាក់ទី</span>
        <span class="dotted-val dotted-val-khmer" style="min-width: 80px;">
            {{ $schoolClass?->name ?? ($schoolClass?->grade?->name ?? '............') }}
        </span>

        <span class="ms-1">,វេន</span>
        <span class="check-item ms-1">
            ព្រឹកម៉ោង <span class="check-circle {{ $isMorning ? 'checked' : '' }}">{!! $isMorning ? '&#10003;' : '' !!}</span> ៧:០០-១០:៣០នាទី
        </span>
        <span class="check-item">
            រសៀល ម៉ោង <span class="check-circle {{ $isAfternoon ? 'checked' : '' }}">{!! $isAfternoon ? '&#10003;' : '' !!}</span> ១:០០-៤:០០នាទី
        </span>
    </div>

    {{-- Row 3: English hours, Book level --}}
    <div class="info-row">
        <span>ម៉ោងអង់គ្លេសក្រៅម៉ោង</span>
        <span class="dotted-val" style="min-width: 140px;">
            {{ $studentPayment->english_class_time ?: '.....................................' }}
        </span>

        <span class="ms-1">កម្រិតសៀវភៅ</span>
        <span class="dotted-val" style="min-width: 120px;">
            {{ $studentPayment->book_level ?: '................................' }}
        </span>
    </div>

    {{-- Row 4: Payment period, Academic year --}}
    <div class="info-row">
        <span>បង់ប្រាក់សម្រាប់</span>
        <span class="dotted-val" style="min-width: 25px; text-align: center;">
            {{ $periodType === 'month' ? $toKhmerNum($periodMonths) : '...' }}
        </span>
        <span>ខែ</span>
        <span class="check-circle {{ $periodType === 'month' ? 'checked' : '' }} ms-1">{!! $periodType === 'month' ? '&#10003;' : '' !!}</span>

        <span class="check-item ms-1">
            សម្រាប់១ត្រីមាស <span class="check-circle {{ $periodType === 'trimester' ? 'checked' : '' }}">{!! $periodType === 'trimester' ? '&#10003;' : '' !!}</span>
        </span>
        <span class="check-item">
            សម្រាប់៦ខែ <span class="check-circle {{ $periodType === 'semester' ? 'checked' : '' }}">{!! $periodType === 'semester' ? '&#10003;' : '' !!}</span>
        </span>
        <span class="check-item">
            សម្រាប់១ឆ្នាំ <span class="check-circle {{ $periodType === 'year' ? 'checked' : '' }}">{!! $periodType === 'year' ? '&#10003;' : '' !!}</span>
        </span>
        <span class="ms-1">ក្នុងឆ្នាំសិក្សា</span>
        <span class="dotted-val dotted-val-khmer" style="min-width: 70px; text-align: center;">
            {{ $academicYearName }}
        </span>
    </div>

    {{-- Row 5: Period dates from ... to ... --}}
    <div class="info-row">
        <span>ចាប់ផ្ដើមពីថ្ងៃទី</span>
        <span class="dotted-val" style="min-width: 26px; text-align: center;">{{ $startDate['day'] }}</span>
        <span>ខែ</span>
        <span class="dotted-val dotted-val-khmer" style="min-width: 60px; text-align: center;">{{ $startDate['month'] }}</span>
        <span>ឆ្នាំ២០</span>
        <span class="dotted-val" style="min-width: 24px; text-align: center;">{{ $startDate['short_year'] }}</span>

        <span class="ms-1">រហូតដល់ ថ្ងៃទី</span>
        <span class="dotted-val" style="min-width: 26px; text-align: center;">{{ $endDate['day'] }}</span>
        <span>ខែ</span>
        <span class="dotted-val dotted-val-khmer" style="min-width: 60px; text-align: center;">{{ $endDate['month'] }}</span>
        <span>ឆ្នាំ២០</span>
        <span class="dotted-val" style="min-width: 24px; text-align: center;">{{ $endDate['short_year'] }}</span>
    </div>

    {{-- Row 6: Next payment continuation date --}}
    <div class="next-due-banner">
        <span class="me-1">&rarr;</span>
        <span>ថ្ងៃត្រូវបង់ថ្ងៃសិក្សាបន្តគឺ ថ្ងៃទី</span>
        <span class="dotted-val mx-1" style="min-width: 28px; text-align: center;">{{ $nextDate['day'] }}</span>
        <span>ខែ</span>
        <span class="dotted-val dotted-val-khmer mx-1" style="min-width: 60px; text-align: center;">{{ $nextDate['month'] }}</span>
        <span>ឆ្នាំ ២០</span>
        <span class="dotted-val mx-1" style="min-width: 26px; text-align: center;">{{ $nextDate['short_year'] }}</span>
    </div>
</div>

{{-- Service Fees Table --}}
<table class="fee-table">
    <thead>
        <tr>
            <th style="width: 32px;" rowspan="2">ល.រ</th>
            <th rowspan="2">ឈ្មោះសេវាកម្ម</th>
            <th colspan="2" class="th-amount-group">ចំនួនទឹកប្រាក់</th>
            <th rowspan="2" style="width: 170px;">ចំនួនសរសេរជាអក្សរ</th>
        </tr>
        <tr>
            <th style="width: 75px;">រៀល</th>
            <th style="width: 65px;">ដុល្លារ</th>
        </tr>
    </thead>
    <tbody>
        {{-- Row 1: Kindergarten fee --}}
        <tr>
            <td class="center">១</td>
            <td>ថ្លៃផ្នែកមត្តេយ្យសិក្សា</td>
            <td class="right">
                {{ $kindergartenAmount ? number_format(round($kindergartenAmount * 4100, -2)) : '' }}
            </td>
            <td class="right">
                {{ $kindergartenAmount ? '$' . number_format($kindergartenAmount, 2) : '' }}
            </td>
            <td style="font-size: 8.5px;" class="center">
                {{ $kindergartenAmount ? \App\Services\Finance\KhmerNumberSpeller::spellCurrency($kindergartenAmount, 'USD') : '' }}
            </td>
        </tr>

        {{-- Row 2: Primary school fee --}}
        <tr>
            <td class="center">២</td>
            <td>ថ្លៃផ្នែកបឋមសិក្សា</td>
            <td class="right">
                {{ $primaryAmount ? number_format(round($primaryAmount * 4100, -2)) : '' }}
            </td>
            <td class="right">
                {{ $primaryAmount ? '$' . number_format($primaryAmount, 2) : '' }}
            </td>
            <td style="font-size: 8.5px;" class="center">
                {{ $primaryAmount ? \App\Services\Finance\KhmerNumberSpeller::spellCurrency($primaryAmount, 'USD') : '' }}
            </td>
        </tr>

        {{-- Row 3: Extra English fee --}}
        <tr>
            <td class="center">៣</td>
            <td>ថ្លៃផ្នែកអង់គ្លេសក្រៅម៉ោង</td>
            <td class="right">
                {{ $englishAmount ? number_format(round($englishAmount * 4100, -2)) : '' }}
            </td>
            <td class="right">
                {{ $englishAmount ? '$' . number_format($englishAmount, 2) : '' }}
            </td>
            <td style="font-size: 8.5px;" class="center">
                {{ $englishAmount ? \App\Services\Finance\KhmerNumberSpeller::spellCurrency($englishAmount, 'USD') : '' }}
            </td>
        </tr>

        {{-- Total Row --}}
        <tr class="total-row">
            <td colspan="2" class="center bold">ទឹកប្រាក់សរុប</td>
            <td class="right bold text-primary">{{ number_format($amountKhr) }} ៛</td>
            <td class="right bold text-primary">${{ number_format($amountUsd, 2) }}</td>
            <td class="bold center" style="font-size: 9px; color: #1e3a8a;">{{ $amountWords }}</td>
        </tr>
    </tbody>
</table>

{{-- Notes (កំណត់សម្គាល់) --}}
<div class="notes-section">
    <div class="notes-title">កំណត់សម្គាល់៖</div>
    <ol class="notes-list">
        <li>បង់រូបប័យ្យវត្ថុមិនអាចដកវិញបានទេ។</li>
        <li>រក្សាបង្កាន់ដៃនេះដើម្បីផ្ទៀងផ្ទាត់។</li>
        <li>ហួសថ្ងៃកំណត់នៃការបង់ប្រាក់ចំនួន ៥ថ្ងៃ អាណាព្យាបាលសិស្ស ត្រូវទាក់ទងមកការិយាល័យ។</li>
        <li>តម្លៃសេវាខាងលើសម្រាប់តែសេវាបំប៉នប៉ុណ្ណោះ ចំពោះការព្យាបាលរបួសធ្ងន់ធ្ងរ មិនមែនជាការទទួលខុសត្រូវរបស់សាលាឡើយ។</li>
        <li>សូមមាតាបិតាអាណាព្យាបាលសិស្ស ញ៉ាំអាហារពេលព្រឹកឲ្យរួចរាល់... ពេលព្រឹកសុំឱ្យបានមុនម៉ោង ១០:៣០នាទី ពេលល្ងាចសុំឱ្យបានមុនម៉ោង ៤:០០នាទី (សម្រាប់ថ្នាក់បណ្តុះបណ្តាល និងបឋមសិក្សា)</li>
    </ol>
</div>

{{-- Footer and Signatures --}}
<div class="receipt-footer">
    <div>
        ធ្វើនៅថ្ងៃទី <span class="dotted-val" style="min-width: 26px; text-align: center;">{{ $paymentDate['day'] }}</span>
        ខែ <span class="dotted-val dotted-val-khmer" style="min-width: 60px; text-align: center;">{{ $paymentDate['month'] }}</span>
        ឆ្នាំ២០<span class="dotted-val" style="min-width: 24px; text-align: center;">{{ $paymentDate['short_year'] }}</span>
    </div>

    <div class="sign-box">
        <div class="sign-title">{{ $copyTitle }}</div>
        <div class="sign-space"></div>
        @if($copyRole === 'receiver')
            <div style="font-size: 9px; color: #64748b;">
                {{ $studentPayment->receiver_name ?? (auth()->user()?->name ?? '') }}
            </div>
        @endif
    </div>
</div>
