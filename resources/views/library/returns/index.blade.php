@extends('layouts.app')

@section('title', 'ការសង & ផាកពិន័យ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">ការសង & ផាកពិន័យ</h1>
            <p class="text-muted small mb-0 mt-1">កំណត់ត្រាប្រតិបត្តិការសងសៀវភៅ និងការផាកពិន័យការយឺតយ៉ាវ</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <button type="button" onclick="toggleCurrencyDisplay()" id="btnCurrencyToggle" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 shadow-2xs" style="height: 34px; font-size: 0.82rem; padding: 0.25rem 0.75rem; border-radius: 6px;" title="ចុចប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <i class="bi bi-arrow-left-right text-primary"></i>
                <span id="currencyModeText">បង្ហាញ៖ <strong class="text-primary">$ USD</strong></span>
            </button>
            <a href="{{ route('library.issues.index', ['status' => 'active']) }}" class="btn btn-sm btn-gold text-white d-inline-flex align-items-center" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px; height: 34px; padding: 0.25rem 0.85rem;">
                <i class="bi bi-box-arrow-in-left me-1"></i> កត់ត្រាការសងសៀវភៅ
            </a>
            <a href="{{ route('library.issues.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" style="border-radius: 6px; height: 34px; padding: 0.25rem 0.85rem; background-color: #fff;">
                <i class="bi bi-arrow-left me-1"></i> ការខ្ចី-សងសៀវភៅ
            </a>
        </div>
    </div>

    {{-- Stats Banner --}}
    <div class="d-flex flex-wrap align-items-stretch gap-2 mb-3">
        <div class="card border-0 shadow-xs rounded-2 bg-white d-flex flex-column justify-content-between" style="min-width: 180px; padding: 0.65rem 0.85rem;">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="text-muted small fw-medium d-block" style="font-size: 0.75rem; line-height: 1.35;">សរុបការសងដែលបានកត់ត្រា</span>
                <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                    <i class="bi bi-check2-all" style="font-size: 0.85rem;"></i>
                </div>
            </div>
            <div class="mt-1">
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem; line-height: 1.2;">{{ number_format($returns->total()) }}</h5>
                <div class="text-muted" style="font-size: 0.68rem; line-height: 1.35;">កំណត់ត្រាសងសរុប</div>
            </div>
        </div>

        <div class="card border-0 shadow-xs rounded-2 bg-white d-flex flex-column justify-content-between" style="min-width: 180px; padding: 0.65rem 0.85rem; cursor: pointer; transition: transform 0.15s ease, box-shadow 0.15s ease;" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <span class="text-muted small fw-medium d-flex align-items-center gap-1" style="font-size: 0.75rem; line-height: 1.35;">
                    <span>សរុបប្រាក់ពិន័យ</span>
                    <i class="bi bi-arrow-left-right text-muted opacity-75" style="font-size: 0.65rem;"></i>
                </span>
                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                    <i class="bi bi-cash-stack" style="font-size: 0.85rem;"></i>
                </div>
            </div>
            <div class="mt-1">
                <div class="currency-display" data-usd="${{ number_format($totalFines, 2) }}" data-khr="{{ number_format(round($totalFines * 4100, -2)) }} ៛">
                    <h5 class="fw-bold text-dark mb-0 curr-primary" style="font-size: 1.15rem; line-height: 1.2;">${{ number_format($totalFines, 2) }}</h5>
                    <div class="text-muted curr-secondary" style="font-size: 0.68rem; line-height: 1.35;">{{ number_format(round($totalFines * 4100, -2)) }} ៛</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('library.returns.index') }}" class="d-flex flex-wrap align-items-center gap-2">
                <div class="input-group input-group-sm" style="max-width: 320px;">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" 
                           placeholder="ស្វែងរក ID, ឈ្មោះ, កូដសៀវភៅ..." 
                           value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-search me-1"></i>ស្វែងរក
                </button>
                @if(request('search'))
                    <a href="{{ route('library.returns.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Returns Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th>ចំណងជើងសៀវភៅ & លេខកូដ</th>
                            <th>អ្នកខ្ចី</th>
                            <th>ថ្ងៃសងត្រឡប់</th>
                            <th>ប្រាក់ពិន័យ</th>
                            <th class="pe-3 text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $ret)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $ret->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">
                                        {{ $ret->library_issue->library_book_copy->library_book->title ?? '—' }}
                                    </div>
                                    <span class="badge bg-light text-dark font-monospace border">
                                        {{ $ret->library_issue->library_book_copy->accession_number ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $ret->library_issue->user->name ?? '—' }}</div>
                                    <div class="text-muted small">
                                        @if($ret->library_issue->user && $ret->library_issue->user->student)
                                            <span class="badge bg-info-subtle text-info">សិស្ស</span>
                                            អត្តលេខ៖ {{ $ret->library_issue->user->student->admission_number }}
                                        @elseif($ret->library_issue->user && $ret->library_issue->user->employee)
                                            <span class="badge bg-secondary-subtle text-secondary">បុគ្គលិក</span>
                                            កូដ៖ {{ $ret->library_issue->user->employee->employee_code }}
                                        @else
                                            {{ $ret->library_issue->user->email ?? '' }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $ret->return_date ? $ret->return_date->format('d/m/Y') : '-' }}</span>
                                    <div class="text-muted small">
                                        បានខ្ចី៖ {{ $ret->library_issue->issue_date ? $ret->library_issue->issue_date->format('d/m/Y') : '-' }}
                                    </div>
                                </td>
                                <td>
                                    @if($ret->fine_amount > 0)
                                        <div class="currency-display" data-usd="${{ number_format($ret->fine_amount, 2) }}" data-khr="{{ number_format(round($ret->fine_amount * 4100, -2)) }} ៛">
                                            <span class="badge bg-danger text-white fs-6 curr-primary">${{ number_format($ret->fine_amount, 2) }}</span>
                                            <span class="badge bg-danger text-white fs-6 curr-secondary d-none">{{ number_format(round($ret->fine_amount * 4100, -2)) }} ៛</span>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted">$0.00</span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('library.issues.show', $ret->library_issue_id) }}" class="btn btn-xs btn-outline-secondary" title="មើលប្រតិបត្តិការខ្ចី">
                                        <i class="bi bi-eye me-1"></i> មើល
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">មិនទាន់មានកំណត់ត្រាសងសៀវភៅនៅឡើយទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($returns->hasPages())
                <div class="p-3 border-top">
                    {{ $returns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ─── Currency Switcher Logic ($ USD ⇄ ៛ KHR) ───
let currentCurrencyMode = localStorage.getItem('invoice_currency_display_mode') || 'usd';

function applyCurrencyMode(mode) {
    currentCurrencyMode = mode;
    localStorage.setItem('invoice_currency_display_mode', mode);

    const btnText = document.getElementById('currencyModeText');
    if (btnText) {
        btnText.innerHTML = mode === 'usd' 
            ? 'បង្ហាញ៖ <strong class="text-primary">$ USD</strong>' 
            : 'បង្ហាញ៖ <strong class="text-success">៛ KHR</strong>';
    }

    document.querySelectorAll('.currency-display').forEach(el => {
        const usdVal = el.getAttribute('data-usd');
        const khrVal = el.getAttribute('data-khr');
        const primaryEl = el.querySelector('.curr-primary');
        const secondaryEl = el.querySelector('.curr-secondary');

        if (primaryEl && secondaryEl && usdVal && khrVal) {
            if (mode === 'khr') {
                primaryEl.textContent = khrVal;
                secondaryEl.textContent = usdVal;
            } else {
                primaryEl.textContent = usdVal;
                secondaryEl.textContent = khrVal;
            }
        }
    });
}

function toggleCurrencyDisplay() {
    applyCurrencyMode(currentCurrencyMode === 'usd' ? 'khr' : 'usd');
}

document.addEventListener('DOMContentLoaded', () => {
    applyCurrencyMode(currentCurrencyMode);
});
</script>
@endpush
