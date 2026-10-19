@extends('layouts.app')

@push('styles')
<style>
.lib-stat-card {
    background: #fff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: 8px;
    padding: 0.85rem 1.1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.lib-stat-card .lib-stat-title {
    font-size: 0.93rem;
    font-weight: 600;
    color: var(--text-muted, #64748b);
    line-height: 1.4;
}
.lib-stat-card .lib-stat-value {
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1.25;
    margin-top: 0.25rem;
    margin-bottom: 0.25rem;
}
.lib-stat-card .lib-stat-sub {
    font-size: 0.88rem;
    line-height: 1.4;
}
.lib-stat-card .lib-stat-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    flex-shrink: 0;
}
.lib-stat-card.interactive {
    cursor: pointer;
    user-select: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
}
.lib-stat-card.interactive:hover {
    border-color: var(--color-brand-500, #f59e0b) !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15) !important;
    transform: translateY(-2px);
}
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-0">ផ្ទាំងបណ្ណាល័យ</h1>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <button type="button" onclick="toggleCurrencyDisplay()" id="btnCurrencyToggle" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 shadow-2xs" style="height: 34px; font-size: 0.82rem; padding: 0.25rem 0.75rem; border-radius: 6px;" title="ចុចប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <i class="bi bi-arrow-left-right text-primary"></i>
                <span id="currencyModeText">បង្ហាញ៖ <strong class="text-primary">$ USD</strong></span>
            </button>
            <a href="{{ route('library.issues.create') }}" class="btn btn-sm btn-gold text-white d-inline-flex align-items-center" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838); border-radius: 6px; height: 34px; padding: 0.25rem 0.85rem;">
                <i class="bi bi-box-arrow-right me-1"></i> ខ្ចីសៀវភៅ
            </a>
            <a href="{{ route('library.books.create') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center" style="border-radius: 6px; height: 34px; padding: 0.25rem 0.85rem; background-color: #fff;">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមសៀវភៅ
            </a>
            <a href="{{ route('library.reports.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" style="border-radius: 6px; height: 34px; padding: 0.25rem 0.85rem; background-color: #fff;">
                <i class="bi bi-bar-chart-line me-1"></i> របាយការណ៍
            </a>
        </div>
    </div>

    {{-- Overdue Alert if any --}}
    @if($overdueIssues > 0)
        <div class="alert alert-warning border-0 shadow-xs rounded-3 d-flex align-items-center justify-content-between p-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
                <div>
                    <strong class="text-dark">{{ $overdueIssues }} កាលកំណត់សងសៀវភៅ</strong>
                    <span class="text-muted ms-2">ហួសកាលកំណត់ដែលត្រូវប្រគល់ត្រឡប់មកវិញ។</span>
                </div>
            </div>
            <a href="{{ route('library.issues.index', ['status' => 'overdue']) }}" class="btn btn-sm btn-outline-dark">
                ពិនិត្យមើលការសងសៀវភៅហួសកំណត់
            </a>
        </div>
    @endif

    {{-- KPI Cards Row 1: Catalogue & Inventory --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="lib-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title">ចំណងជើងសៀវភៅសរុប</span>
                    <div class="lib-stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                </div>
                <div class="lib-stat-value text-dark">{{ number_format($totalBooks) }}</div>
                <span class="lib-stat-sub text-muted text-truncate">ចំណងជើងសៀវភៅ</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="lib-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title">ចំនួនច្បាប់សៀវភៅសរុប</span>
                    <div class="lib-stat-icon bg-info-subtle text-info">
                        <i class="bi bi-stack"></i>
                    </div>
                </div>
                <div class="lib-stat-value text-dark">{{ number_format($totalCopies) }}</div>
                <span class="lib-stat-sub text-muted text-truncate">សៀវភៅក្នុងស្តុក</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="lib-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title">សៀវភៅនៅសល់</span>
                    <div class="lib-stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="lib-stat-value text-success">{{ number_format($availableCopies) }}</div>
                <span class="lib-stat-sub text-muted text-truncate">សម្រាប់ខ្ចី</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="lib-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title">សៀវភៅកំពុងខ្ចី</span>
                    <div class="lib-stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                </div>
                <div class="lib-stat-value text-warning">{{ number_format($issuedCopies) }}</div>
                <span class="lib-stat-sub text-muted text-truncate">កំពុងខ្ចីសៀវភៅ</span>
            </div>
        </div>
    </div>

    {{-- KPI Cards Row 2: Status & Liabilities --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="lib-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title">កំពុងខ្ចីសកម្ម</span>
                    <span class="badge bg-primary-subtle text-primary" style="font-size: 0.72rem; padding: 3px 6px;">មិនទាន់សង</span>
                </div>
                <div class="lib-stat-value text-dark">{{ number_format($activeIssues) }}</div>
                <a href="{{ route('library.issues.index', ['status' => 'active']) }}" class="lib-stat-sub text-decoration-none">មើលការខ្ចីសៀវភៅ &rarr;</a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="lib-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title">ហួសកាលកំណត់</span>
                    <span class="badge bg-danger-subtle text-danger" style="font-size: 0.72rem; padding: 3px 6px;">ត្រូវការចំណាត់ការ</span>
                </div>
                <div class="lib-stat-value text-danger">{{ number_format($overdueIssues) }}</div>
                <a href="{{ route('library.issues.index', ['status' => 'overdue']) }}" class="lib-stat-sub text-danger text-decoration-none">មើលការហួសកាលកំណត់ &rarr;</a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="lib-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title">ខូចខាត / បាត់បង់</span>
                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.72rem; padding: 3px 6px;">ស្តុកសៀវភៅ</span>
                </div>
                <div class="lib-stat-value text-secondary">{{ number_format($damagedCopies + $lostCopies) }}</div>
                <span class="lib-stat-sub text-muted text-truncate">បាត់បង់ {{ $lostCopies }}, ខូចខាត {{ $damagedCopies }}</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="lib-stat-card interactive" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="lib-stat-title d-flex align-items-center gap-1">
                        <span>ប្រាក់ពិន័យសរុប</span>
                        <i class="bi bi-arrow-left-right opacity-75" style="font-size: 0.65rem;"></i>
                    </span>
                    <div class="lib-stat-icon bg-success-subtle text-success">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
                <div class="currency-display" data-usd="${{ number_format($totalFines, 2) }}" data-khr="{{ number_format(round($totalFines * 4100, -2)) }} ៛">
                    <div class="lib-stat-value text-dark curr-primary">${{ number_format($totalFines, 2) }}</div>
                    <div class="text-success fw-semibold curr-secondary" style="font-size: 0.75rem; line-height: 1;">{{ number_format(round($totalFines * 4100, -2)) }} ៛</div>
                </div>
                <a href="{{ route('library.returns.index') }}" class="lib-stat-sub text-decoration-none mt-1 d-inline-block" onclick="event.stopPropagation();">មើលកំណត់ត្រាសង &rarr;</a>
            </div>
        </div>
    </div>

    {{-- Main Section: Recent Activity & Popular Books --}}
    <div class="row g-3 mb-4">
        {{-- Left: Recent Issues & Recent Returns --}}
        <div class="col-12 col-xl-8">
            {{-- Recent Issues --}}
            <div class="card border-0 shadow-xs rounded-2 bg-white mb-3">
                <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between pt-3 px-3 pb-2">
                    <h6 class="fw-bold text-dark mb-0">ការខ្ចីសៀវភៅ</h6>
                    <a href="{{ route('library.issues.index') }}" class="small text-decoration-none" style="font-size: 0.85rem;">មើលការខ្ចីទាំងអស់ &rarr;</a>
                </div>
                <div class="card-body px-0 pb-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th class="ps-3">សៀវភៅ & លេខសម្គាល់</th>
                                    <th>អ្នកខ្ចី</th>
                                    <th>ថ្ងៃខ្ចី</th>
                                    <th>ថ្ងៃកំណត់សង</th>
                                    <th>ស្ថានភាព</th>
                                    <th class="pe-3 text-end">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentIssues as $issue)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold text-dark">{{ $issue->library_book_copy->library_book->title ?? 'N/A' }}</div>
                                            <span class="badge bg-light text-dark font-monospace">{{ $issue->library_book_copy->accession_number ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $issue->user->name ?? 'N/A' }}</div>
                                            <span class="text-muted small">{{ $issue->user->email ?? '' }}</span>
                                        </td>
                                        <td>{{ $issue->issue_date ? $issue->issue_date->format('M d, Y') : '-' }}</td>
                                        <td>{{ $issue->due_date ? $issue->due_date->format('M d, Y') : '-' }}</td>
                                        <td>
                                            @if($issue->is_returned)
                                                <span class="badge bg-success-subtle text-success">បានសងរួច</span>
                                            @elseif($issue->is_overdue)
                                                <span class="badge bg-danger-subtle text-danger">ហួសកំណត់ ({{ $issue->overdue_days }}ថ្ងៃ)</span>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary">កំពុងខ្ចី</span>
                                            @endif
                                        </td>
                                        <td class="pe-3 text-end">
                                            @if(!$issue->is_returned)
                                                <a href="{{ route('library.returns.create', $issue) }}" class="btn btn-xs btn-outline-success">
                                                    <i class="bi bi-box-arrow-in-left me-1"></i> សងសៀវភៅ
                                                </a>
                                            @else
                                                <a href="{{ route('library.issues.show', $issue) }}" class="btn btn-xs btn-outline-secondary">
                                                    មើល
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">មិនមានកំណត់ត្រាខ្ចីសៀវភៅនៅឡើយទេ។</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Recent Returns --}}
            <div class="card border-0 shadow-xs rounded-2 bg-white">
                <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between pt-2 px-3 pb-1">
                    <h6 class="fw-bold text-dark mb-0">ការសងសៀវភៅ</h6>
                    <a href="{{ route('library.returns.index') }}" class="small text-decoration-none" style="font-size: 0.78rem;">មើលការសងទាំងអស់ &rarr;</a>
                </div>
                <div class="card-body px-0 pb-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th class="ps-3">សៀវភៅ & លេខសម្គាល់</th>
                                    <th>អ្នកខ្ចី</th>
                                    <th>ថ្ងៃសង</th>
                                    <th>ប្រាក់ពិន័យ</th>
                                    <th class="pe-3 text-end">ព័ត៌មានលម្អិត</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReturns as $ret)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold text-dark">{{ $ret->library_issue->library_book_copy->library_book->title ?? 'N/A' }}</div>
                                            <span class="badge bg-light text-dark font-monospace">{{ $ret->library_issue->library_book_copy->accession_number ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $ret->library_issue->user->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>{{ $ret->return_date ? $ret->return_date->format('M d, Y') : '-' }}</td>
                                        <td>
                                            @if($ret->fine_amount > 0)
                                                <div class="currency-display" data-usd="${{ number_format($ret->fine_amount, 2) }}" data-khr="{{ number_format(round($ret->fine_amount * 4100, -2)) }} ៛">
                                                    <span class="badge bg-danger-subtle text-danger curr-primary">${{ number_format($ret->fine_amount, 2) }}</span>
                                                    <span class="badge bg-danger-subtle text-danger curr-secondary d-none">{{ number_format(round($ret->fine_amount * 4100, -2)) }} ៛</span>
                                                </div>
                                            @else
                                                <span class="text-muted">$0.00</span>
                                            @endif
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="{{ route('library.issues.show', $ret->library_issue_id) }}" class="btn btn-xs btn-outline-secondary">
                                                មើលការខ្ចី
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">មិនទាន់មានប្រតិបត្តិការសងសៀវភៅនៅឡើយទេ។</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Popular Books & Quick Navigation --}}
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-xs rounded-2 bg-white mb-2">
                <div class="card-header bg-transparent border-0 pt-2 px-3 pb-1">
                    <h6 class="fw-bold text-dark mb-0">សៀវភៅដែលត្រូវបានខ្ចីច្រើនជាងគេ</h6>
                </div>
                <div class="card-body p-2">
                    @forelse($popularBooks as $index => $pBook)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-2 mb-1 {{ $index === 0 ? 'bg-light' : '' }}">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-muted" style="font-size: 0.85rem;">#{{ $index + 1 }}</span>
                                <div>
                                    <a href="{{ route('library.books.show', $pBook) }}" class="fw-semibold text-dark text-decoration-none" style="font-size: 0.85rem;">
                                        {{ $pBook->title }}
                                    </a>
                                    <div class="text-muted small" style="font-size: 0.75rem;">
                                        {{ $pBook->author ?? 'មិនស្គាល់អ្នកនិពន្ធ' }} &bull; {{ $pBook->library_category->name ?? 'គ្មានប្រភេទ' }}
                                    </div>
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill" style="font-size: 0.72rem;">{{ $pBook->library_issues_count }} លើក</span>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">មិនទាន់មានស្ថិតិនៃការខ្ចីនៅឡើយទេ។</div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Links Card --}}
            <div class="card border-0 shadow-xs rounded-2 bg-white">
                <div class="card-header bg-transparent border-0 pt-2 px-3 pb-1">
                    <h6 class="fw-bold text-dark mb-0">សកម្មភាព</h6>
                </div>
                <div class="card-body p-2">
                    <div class="d-grid gap-2">
                        <a href="{{ route('library.issues.create') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> ខ្ចីសៀវភៅ
                        </a>
                        <a href="{{ route('library.books.create') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-plus-circle me-2"></i> បន្ថែមសៀវភៅថ្មី
                        </a>
                        <a href="{{ route('library.categories.index') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-folder-plus me-2"></i> ប្រភេទសៀវភៅ
                        </a>
                        <a href="{{ route('library.borrowers.index') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-people me-2"></i> បញ្ជីឈ្មោះ ប្រវត្តិអ្នកខ្ចី
                        </a>
                        <a href="{{ route('library.reports.index') }}" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-file-earmark-text me-2"></i> របាយការណ៍បណ្ណាល័យ
                        </a>
                    </div>
                </div>
            </div>
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
