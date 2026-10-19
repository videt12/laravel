@extends('layouts.app')

@section('title', 'ប្រវត្តិរូបអ្នកខ្ចី — ' . $user->name)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold text-dark mb-0">{{ $user->name }}</h1>
                @if($user->student)
                    <span class="badge bg-info-subtle text-info">សិស្ស</span>
                @elseif($user->employee)
                    <span class="badge bg-secondary-subtle text-secondary">បុគ្គលិក</span>
                @endif
            </div>
            <p class="text-muted small mb-0 mt-1">ប្រវត្តិរូបអ្នកខ្ចី ការខ្ចីសកម្ម និងប្រវត្តិនៃការខ្ចី-សងសៀវភៅទាំងមូល</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @can('manage-library', $user)
                <a href="{{ route('library.issues.create') }}" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);">
                    <i class="bi bi-box-arrow-right me-1"></i> ខ្ចីសៀវភៅ
                </a>
            @endcan
            <a href="{{ route('library.borrowers.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅបញ្ជីអ្នកខ្ចី
            </a>
        </div>
    </div>

    {{-- Borrower Profile & Stats Row --}}
    <div class="row g-4 mb-4">
        {{-- Profile Details --}}
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white h-100 p-4">
                <h5 class="fw-bold text-dark mb-3">ព័ត៌មានអ្នកខ្ចី</h5>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-muted small">ឈ្មោះពេញ</div>
                        <div class="fw-semibold text-dark">{{ $user->name }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">អាសយដ្ឋានអ៊ីមែល</div>
                        <div class="fw-semibold text-dark">{{ $user->email }}</div>
                    </div>

                    @if($user->student)
                        <div class="col-6">
                            <div class="text-muted small">អត្តលេខសិស្ស #</div>
                            <div class="fw-semibold font-monospace text-dark">{{ $user->student->admission_number }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">ថ្នាក់ & បន្ទប់</div>
                            <div class="fw-semibold text-dark">
                                {{ $user->student->schoolClass->name ?? '—' }}
                                @if($user->student->section)
                                    ({{ $user->student->section->name }})
                                @endif
                            </div>
                        </div>
                    @elseif($user->employee)
                        <div class="col-6">
                            <div class="text-muted small">កូដបុគ្គលិក</div>
                            <div class="fw-semibold font-monospace text-dark">{{ $user->employee->employee_code }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">ដេប៉ាតឺម៉ង់ & តួនាទី</div>
                            <div class="fw-semibold text-dark">
                                {{ $user->employee->employeeDepartment->name ?? '—' }}
                                @if($user->employee->employeePosition)
                                    &bull; {{ $user->employee->employeePosition->name }}
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Borrowing Metrics --}}
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-xs rounded-3 bg-white h-100 p-4">
                <h5 class="fw-bold text-dark mb-3">ស្ថិតិនៃការខ្ចីសៀវភៅ</h5>
                <div class="row g-2">
                    <div class="col-4">
                        <div class="p-3 bg-primary-subtle rounded-3 text-center">
                            <span class="text-primary small d-block">កំពុងខ្ចី</span>
                            <span class="h4 fw-bold text-primary mb-0">{{ $activeIssues->count() }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-success-subtle rounded-3 text-center">
                            <span class="text-success small d-block">បានសងរួច</span>
                            <span class="h4 fw-bold text-success mb-0">{{ $returnedIssues->total() }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-danger-subtle rounded-3 text-center" style="cursor: pointer;" onclick="toggleCurrencyDisplay()" title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                            <span class="text-danger small d-block">សរុបប្រាក់ពិន័យ <i class="bi bi-arrow-left-right opacity-75" style="font-size:0.65rem;"></i></span>
                            <div class="currency-display" data-usd="${{ number_format($totalFines, 2) }}" data-khr="{{ number_format(round($totalFines * 4100, -2)) }} ៛">
                                <span class="h4 fw-bold text-danger mb-0 curr-primary">${{ number_format($totalFines, 2) }}</span>
                                <span class="text-danger fw-semibold d-block curr-secondary" style="font-size:0.75rem;">{{ number_format(round($totalFines * 4100, -2)) }} ៛</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Loans Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white mb-4">
        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
            <h5 class="fw-bold text-dark mb-0">សៀវភៅកំពុងខ្ចី ({{ $activeIssues->count() }})</h5>
        </div>
        <div class="card-body px-0 pb-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">ចំណងជើងសៀវភៅ</th>
                            <th>លេខកូដសៀវភៅ</th>
                            <th>ថ្ងៃខ្ចី</th>
                            <th>ថ្ងៃកំណត់សង</th>
                            <th>ស្ថានភាព</th>
                            <th class="pe-3 text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeIssues as $aIssue)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">
                                        <a href="{{ route('library.books.show', $aIssue->library_book_copy->library_book_id) }}" class="text-decoration-none">
                                            {{ $aIssue->library_book_copy->library_book->title ?? '—' }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark font-monospace border">
                                        {{ $aIssue->library_book_copy->accession_number ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $aIssue->issue_date ? $aIssue->issue_date->format('d/m/Y') : '-' }}</td>
                                <td>{{ $aIssue->due_date ? $aIssue->due_date->format('d/m/Y') : 'គ្មានថ្ងៃកំណត់' }}</td>
                                <td>
                                    @if($aIssue->is_overdue)
                                        <span class="badge bg-danger text-white">ហួសកំណត់ ({{ $aIssue->overdue_days }}ថ្ងៃ)</span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary">កំពុងខ្ចី</span>
                                    @endif
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="{{ route('library.issues.show', $aIssue) }}" class="btn btn-xs btn-outline-secondary">
                                        មើល
                                    </a>
                                    <a href="{{ route('library.returns.create', $aIssue) }}" class="btn btn-xs btn-outline-success ms-1">
                                        សងសៀវភៅ
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">បច្ចុប្បន្នអ្នកខ្ចីរូបនេះមិនមានសៀវភៅដែលកំពុងខ្ចីនោះទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Returned History Table --}}
    <div class="card border-0 shadow-xs rounded-3 bg-white">
        <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
            <h5 class="fw-bold text-dark mb-0">ប្រវត្តិនៃការខ្ចីកន្លងមក ({{ $returnedIssues->total() }})</h5>
        </div>
        <div class="card-body px-0 pb-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">ចំណងជើងសៀវភៅ</th>
                            <th>លេខកូដសៀវភៅ</th>
                            <th>ថ្ងៃខ្ចី</th>
                            <th>ថ្ងៃកំណត់សង</th>
                            <th>ថ្ងៃសងត្រឡប់</th>
                            <th class="pe-3 text-end">ប្រាក់ពិន័យដែលបានគិត</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returnedIssues as $rIssue)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold text-dark">
                                        {{ $rIssue->library_book_copy->library_book->title ?? '—' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark font-monospace border">
                                        {{ $rIssue->library_book_copy->accession_number ?? '—' }}
                                    </span>
                                </td>
                                <td>{{ $rIssue->issue_date ? $rIssue->issue_date->format('d/m/Y') : '-' }}</td>
                                <td>{{ $rIssue->due_date ? $rIssue->due_date->format('d/m/Y') : 'គ្មានថ្ងៃកំណត់' }}</td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        {{ $rIssue->libraryReturn->return_date ? $rIssue->libraryReturn->return_date->format('d/m/Y') : '-' }}
                                    </span>
                                </td>
                                <td class="pe-3 text-end">
                                    @if($rIssue->libraryReturn && $rIssue->libraryReturn->fine_amount > 0)
                                        <div class="currency-display" data-usd="${{ number_format($rIssue->libraryReturn->fine_amount, 2) }}" data-khr="{{ number_format(round($rIssue->libraryReturn->fine_amount * 4100, -2)) }} ៛">
                                            <span class="text-danger fw-bold curr-primary">${{ number_format($rIssue->libraryReturn->fine_amount, 2) }}</span>
                                            <span class="text-danger fw-bold curr-secondary d-none">{{ number_format(round($rIssue->libraryReturn->fine_amount * 4100, -2)) }} ៛</span>
                                        </div>
                                    @else
                                        <span class="text-muted">$0.00</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">មិនទាន់មានប្រវត្តិខ្ចីសៀវភៅកន្លងមកសម្រាប់អ្នកខ្ចីរូបនេះនៅឡើយទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($returnedIssues->hasPages())
                <div class="p-3 border-top">
                    {{ $returnedIssues->links() }}
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

    document.querySelectorAll('.currency-display').forEach(el => {
        const usdVal = el.getAttribute('data-usd');
        const khrVal = el.getAttribute('data-khr');
        const primaryEl = el.querySelector('.curr-primary');
        const secondaryEl = el.querySelector('.curr-secondary');

        if (primaryEl && usdVal && khrVal) {
            if (mode === 'khr') {
                primaryEl.textContent = khrVal;
                if (secondaryEl) secondaryEl.textContent = usdVal;
            } else {
                primaryEl.textContent = usdVal;
                if (secondaryEl) secondaryEl.textContent = khrVal;
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
