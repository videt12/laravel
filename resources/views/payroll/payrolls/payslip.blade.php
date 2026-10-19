<!DOCTYPE html>
<html lang="km">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ប័ណ្ណបើកប្រាក់បៀវត្សរ៍ (Payslip) — {{ $payroll->payroll_number }}</title>
@php
    $khmerMonths = [
        1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
        5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
        9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ',
    ];
    $khmerPeriod = isset($khmerMonths[$payroll->month]) ? 'ខែ' . $khmerMonths[$payroll->month] . ' ឆ្នាំ' . $payroll->year : $payroll->period;

    $statusLabels = [
        'draft' => 'ព្រាង (Draft)',
        'pending' => 'រង់ចាំអនុម័ត (Pending)',
        'approved' => 'បានអនុម័ត (Approved)',
        'paid' => 'បានទូទាត់ (Paid)',
        'cancelled' => 'បានបោះបង់ (Cancelled)',
    ];

    $paymentMethodLabels = [
        'bank_transfer' => 'ផ្ទេរតាមធនាគារ (Bank Transfer)',
        'cash' => 'សាច់ប្រាក់សុទ្ធ (Cash)',
        'cheque' => 'មូលប្បទានប័ត្រ (Cheque)',
    ];
@endphp
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Khmer OS Siemreap', 'Siemreap', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #111827; background: #f3f4f6; }
.page { max-width: 720px; margin: 20px auto; background: #fff; padding: 0; border-radius: 8px; box-shadow: 0 4px 24px rgba(0,0,0,.12); overflow: hidden; }
.header { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color: #fff; padding: 28px 32px; }
.header h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 2px; }
.header .subtitle { font-size: .85rem; opacity: .85; }
.header .payslip-meta { margin-top: 14px; display: flex; gap: 32px; flex-wrap: wrap; }
.header .payslip-meta div { font-size: .8rem; }
.header .payslip-meta strong { display: block; font-size: 1rem; font-weight: 700; }

.body { padding: 24px 32px; }
.section-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6b7280; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb; }

.emp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 24px; }
.emp-grid .field label { font-size: .75rem; color: #6b7280; display: block; }
.emp-grid .field span { font-weight: 600; font-size: .875rem; }

table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
th { background: #f8fafc; padding: 8px 12px; text-align: left; font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
td { padding: 9px 12px; font-size: .875rem; border-bottom: 1px solid #f3f4f6; }
.text-right { text-align: right; }
.total-row td { font-weight: 700; background: #f8fafc; border-top: 2px solid #e5e7eb; }
.net-row td { font-weight: 800; font-size: 1rem; color: #1e40af; background: #eff6ff; border-top: 2px solid #bfdbfe; }

.signature-section { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 32px; }
.sig-box { border-top: 2px solid #d1d5db; padding-top: 8px; }
.sig-box label { font-size: .78rem; color: #6b7280; }
.sig-box .sig-name { font-weight: 700; margin-top: 4px; }

.footer { padding: 14px 32px; background: #f8fafc; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; font-size: .75rem; color: #9ca3af; }

.status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: .75rem; font-weight: 700; }
.status-paid { background: #d1fae5; color: #065f46; }
.status-approved { background: #dbeafe; color: #1e40af; }
.status-other { background: #f3f4f6; color: #374151; }

@media print {
    body { background: #fff; }
    .page { box-shadow: none; margin: 0; border-radius: 0; }
    .no-print { display: none; }
}
</style>
</head>
<body>

<div class="no-print" style="text-align:center; padding:16px; background:#f3f4f6;">
    <button onclick="window.print()" style="background:#2563eb;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-weight:600;cursor:pointer;margin-right:8px;">
        🖨️ បោះពុម្ព / រក្សាទុកជា PDF
    </button>
    <a href="{{ route('payrolls.show', $payroll) }}" style="background:#fff;color:#374151;border:1px solid #d1d5db;padding:8px 20px;border-radius:6px;text-decoration:none;font-weight:600;">
        ← ត្រឡប់ក្រោយ
    </a>
</div>

<div class="page">
    <div class="header">
        <h1>ប័ណ្ណបើកប្រាក់បៀវត្សរ៍ (PAYSLIP)</h1>
        <div class="subtitle">{{ config('app.name', 'School Management System') }}</div>
        <div class="payslip-meta">
            <div><label style="opacity:.7;font-size:.7rem;display:block;">លេខបញ្ជី (Payroll No.)</label><strong>{{ $payroll->payroll_number }}</strong></div>
            <div><label style="opacity:.7;font-size:.7rem;display:block;">កាលបរិច្ឆេទ / ខែបើក (Pay Period)</label><strong>{{ $khmerPeriod }}</strong></div>
            <div><label style="opacity:.7;font-size:.7rem;display:block;">កាលបរិច្ឆេទបើក (Pay Date)</label><strong>{{ $payroll->pay_date?->format('d/m/Y') ?? 'មិនទាន់បើក' }}</strong></div>
            <div>
                <label style="opacity:.7;font-size:.7rem;display:block;">ស្ថានភាព (Status)</label>
                <span class="status-badge {{ $payroll->status === 'paid' ? 'status-paid' : ($payroll->status === 'approved' ? 'status-approved' : 'status-other') }}">
                    {{ $statusLabels[$payroll->status] ?? strtoupper($payroll->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="body">
        {{-- Employee details --}}
        <div class="section-title" style="margin-top:4px;">ព័ត៌មានបុគ្គលិក (Employee Information)</div>
        <div class="emp-grid">
            <div class="field"><label>ឈ្មោះបុគ្គលិក (Name)</label><span>{{ $payroll->employee?->user?->name ?? 'N/A' }}</span></div>
            <div class="field"><label>អត្តលេខបុគ្គលិក (Employee Code)</label><span>{{ $payroll->employee?->employee_code ?? '—' }}</span></div>
            <div class="field"><label>ដេប៉ាតឺម៉ង់ (Department)</label><span>{{ $payroll->employee?->employee_department?->name ?? '—' }}</span></div>
            <div class="field"><label>តួនាទី / មុខតំណែង (Position)</label><span>{{ $payroll->employee?->employee_position?->name ?? '—' }}</span></div>
            <div class="field"><label>រចនាសម្ព័ន្ធប្រាក់ខែ (Salary Structure)</label><span>{{ $payroll->salaryStructure?->name ?? '—' }}</span></div>
            <div class="field"><label>ទំនាក់ទំនង (Contact)</label><span>{{ $payroll->employee?->employeeContact?->phone ?? '—' }}</span></div>
        </div>

        {{-- Earnings --}}
        <div class="section-title">ប្រាក់ចំណូល (Earnings)</div>
        <table>
            <thead>
                <tr>
                    <th>សមាសភាគ / បរិយាយ (Description)</th>
                    <th>ការគណនា (Calculation)</th>
                    <th class="text-right">ចំនួនទឹកប្រាក់ (Amount)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ប្រាក់បៀវត្សគោល (Basic Salary)</td>
                    <td>ថេរ (Fixed)</td>
                    <td class="text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                </tr>
                @foreach($payroll->payrollDetails->where('component_type', 'earning') as $detail)
                <tr>
                    <td>{{ $detail->component_name }}</td>
                    <td>{{ $detail->calculation_type === 'percentage' ? number_format($detail->rate, 2).'% នៃប្រាក់គោល' : 'ថេរ (Fixed)' }}</td>
                    <td class="text-right">{{ number_format($detail->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">សរុបប្រាក់ចំណូល (Total Earnings)</td>
                    <td class="text-right">{{ number_format($payroll->total_earnings, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Deductions --}}
        @if($payroll->payrollDetails->where('component_type','deduction')->count() > 0 || $payroll->payrollDeductions->count() > 0)
        <div class="section-title">ការកាត់កង (Deductions)</div>
        <table>
            <thead>
                <tr>
                    <th>សមាសភាគ / ប្រភេទកាត់ (Description)</th>
                    <th>ការគណនា (Calculation)</th>
                    <th class="text-right">ចំនួនទឹកប្រាក់ (Amount)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payroll->payrollDetails->where('component_type','deduction') as $detail)
                <tr>
                    <td>{{ $detail->component_name }}</td>
                    <td>{{ $detail->calculation_type === 'percentage' ? number_format($detail->rate, 2).'% នៃប្រាក់គោល' : 'ថេរ (Fixed)' }}</td>
                    <td class="text-right text-danger">-{{ number_format($detail->amount, 2) }}</td>
                </tr>
                @endforeach
                @foreach($payroll->payrollDeductions as $ded)
                <tr>
                    <td>{{ $ded->deduction_type_label }}: {{ $ded->description }}</td>
                    <td>ជាក់ស្តែង (Ad-hoc)</td>
                    <td class="text-right text-danger">-{{ number_format($ded->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">សរុបការកាត់កង (Total Deductions)</td>
                    <td class="text-right text-danger">-{{ number_format($payroll->total_deductions, 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        {{-- Net Salary --}}
        <table>
            <tbody>
                <tr class="net-row">
                    <td>ប្រាក់បៀវត្សរ៍សុទ្ធ (NET SALARY)</td>
                    <td></td>
                    <td class="text-right">{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Payment info --}}
        @if($payroll->payment_method)
        <div style="font-size:.8rem;color:#6b7280;margin-top:8px;">
            វិធីសាស្ត្រទូទាត់: {{ $paymentMethodLabels[$payroll->payment_method] ?? ucfirst(str_replace('_', ' ', $payroll->payment_method)) }}
            @if($payroll->payment_reference) | លេខយោង: {{ $payroll->payment_reference }}@endif
        </div>
        @endif

        {{-- Signature --}}
        <div class="signature-section">
            <div class="sig-box">
                <label>អ្នករៀបចំ (Prepared By)</label>
                <div class="sig-name">{{ $payroll->createdBy?->name ?? '_______________' }}</div>
            </div>
            <div class="sig-box">
                <label>ហត្ថលេខាបុគ្គលិក (Employee Signature)</label>
                <div class="sig-name">_______________</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <span>កាលបរិច្ឆេទបង្កើត: {{ now()->format('d/m/Y H:i') }}</span>
        <span>{{ $payroll->payroll_number }} | ឯកសារសម្ងាត់</span>
    </div>
</div>
</body>
</html>
