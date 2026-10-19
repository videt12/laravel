@extends('layouts.app')

@section('title', 'របាយការណ៍បណ្ណាល័យ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
        <div>
            <h1 class="h4 fw-bold text-dark mb-0">របាយការណ៍បណ្ណាល័យ</h1>
            <p class="text-muted small mb-0 mt-0.5" style="font-size: 0.8125rem;">ការវិភាគលើចំនួនសៀវភៅក្នុងស្តុក ការខ្ចីហួសកាលកំណត់ ប្រវត្តិការសង និងស្ថិតិចលនាសៀវភៅ</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <button type="button" onclick="toggleCurrencyDisplay()" id="btnCurrencyToggle" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1 shadow-2xs" style="height: 34px; font-size: 0.82rem; padding: 0.25rem 0.75rem; border-radius: 6px;" title="ចុចប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)">
                <i class="bi bi-arrow-left-right text-primary"></i>
                <span id="currencyModeText">បង្ហាញ៖ <strong class="text-primary">$ USD</strong></span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-dark d-inline-flex align-items-center" onclick="window.print();" style="border-radius: 6px; height: 34px; padding: 0.25rem 0.85rem;">
                <i class="bi bi-printer me-1"></i> បោះពុម្ពរបាយការណ៍
            </button>
            <a href="{{ route('library.dashboard') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" style="border-radius: 6px; height: 34px; padding: 0.25rem 0.85rem; background-color: #fff;">
                <i class="bi bi-arrow-left me-1"></i> ផ្ទាំងបណ្ណាល័យ
            </a>
        </div>
    </div>

    {{-- Report Navigation Tabs --}}
    <div class="card border-0 shadow-xs rounded-2 bg-white mb-2">
        <div class="card-body" style="padding: 6px 8px;">
            <ul class="nav nav-pills gap-1 flex-nowrap overflow-x-auto align-items-center" style="scrollbar-width: none;">
                <li class="nav-item">
                    <a class="nav-link py-1.5 px-2.5 text-nowrap {{ $tab === 'inventory' ? 'active' : 'text-muted' }}" 
                       href="{{ route('library.reports.index', ['tab' => 'inventory']) }}" style="font-size: 0.8125rem;">
                        <i class="bi bi-stack me-1"></i> សង្ខេបស្តុក
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1.5 px-2.5 text-nowrap {{ $tab === 'checkouts' ? 'active' : 'text-muted' }}" 
                       href="{{ route('library.reports.index', ['tab' => 'checkouts']) }}" style="font-size: 0.8125rem;">
                        <i class="bi bi-box-arrow-right me-1"></i> កំពុងខ្ចី & ហួសកំណត់ <span class="badge {{ $tab === 'checkouts' ? 'bg-white text-primary' : 'bg-light text-muted' }} ms-1" style="font-size: 0.72rem;">{{ $activeIssues->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1.5 px-2.5 text-nowrap {{ $tab === 'returns' ? 'active' : 'text-muted' }}" 
                       href="{{ route('library.reports.index', ['tab' => 'returns']) }}" style="font-size: 0.8125rem;">
                        <i class="bi bi-box-arrow-in-left me-1"></i> ការសង & ពិន័យ 
                        <span class="badge {{ $tab === 'returns' ? 'bg-white text-primary' : 'bg-light text-muted' }} ms-1 currency-display" 
                              style="font-size: 0.72rem;"
                              data-usd="${{ number_format($totalFinesCollected, 2) }}"
                              data-khr="{{ number_format(round($totalFinesCollected * 4100, -2)) }} ៛">
                            <span class="curr-primary">${{ number_format($totalFinesCollected, 2) }}</span>
                            <span class="curr-secondary d-none">{{ number_format(round($totalFinesCollected * 4100, -2)) }} ៛</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1.5 px-2.5 text-nowrap {{ $tab === 'popular' ? 'active' : 'text-muted' }}" 
                       href="{{ route('library.reports.index', ['tab' => 'popular']) }}" style="font-size: 0.8125rem;">
                        <i class="bi bi-trophy me-1"></i> សៀវភៅខ្ចីច្រើន
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-1.5 px-2.5 text-nowrap {{ $tab === 'borrowers' ? 'active' : 'text-muted' }}" 
                       href="{{ route('library.reports.index', ['tab' => 'borrowers']) }}" style="font-size: 0.8125rem;">
                        <i class="bi bi-people me-1"></i> អ្នកខ្ចី
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if($tab === 'inventory')
        {{-- TAB 1: INVENTORY SUMMARY --}}
        <div class="row g-2 mb-2">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-xs rounded-2 bg-white text-center" style="padding: 10px 12px;">
                    <span class="text-muted small d-block mb-1" style="font-size: 0.8125rem;">ចំណងជើងសៀវភៅសរុប</span>
                    <span class="h4 fw-bold text-dark mb-0">{{ number_format($totalBooks) }}</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-xs rounded-2 bg-white text-center" style="padding: 10px 12px;">
                    <span class="text-muted small d-block mb-1" style="font-size: 0.8125rem;">ច្បាប់សៀវភៅជាក់ស្តែង</span>
                    <span class="h4 fw-bold text-info mb-0">{{ number_format($totalCopies) }}</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-xs rounded-2 bg-white text-center" style="padding: 10px 12px;">
                    <span class="text-muted small d-block mb-1" style="font-size: 0.8125rem;">នៅសល់</span>
                    <span class="h4 fw-bold text-success mb-0">{{ number_format($availableCopies) }}</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-xs rounded-2 bg-white text-center" style="padding: 10px 12px;">
                    <span class="text-muted small d-block mb-1" style="font-size: 0.8125rem;">បានខ្ចី</span>
                    <span class="h4 fw-bold text-warning mb-0">{{ number_format($issuedCopies) }}</span>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-xs rounded-2 bg-white">
            <div class="card-header bg-transparent border-0 pt-2 px-3 pb-1">
                <h6 class="fw-bold text-dark mb-0">បែងចែកកាតាឡុកតាមប្រភេទសៀវភៅ</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-3">ឈ្មោះប្រភេទសៀវភៅ</th>
                                <th>ការពិពណ៌នា</th>
                                <th>ចំនួនចំណងជើង</th>
                                <th class="pe-3 text-end">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoriesSummary as $c)
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">{{ $c->name }}</td>
                                    <td class="text-muted small">{{ $c->description ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">{{ $c->library_books_count }} ចំណងជើង</span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="{{ route('library.books.index', ['category_id' => $c->id]) }}" class="btn btn-xs btn-outline-secondary">
                                            មើលសៀវភៅ
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">មិនទាន់មានប្រភេទសៀវភៅនៅឡើយទេ។</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @elseif($tab === 'checkouts')
        {{-- TAB 2: ACTIVE & OVERDUE CHECKOUTS --}}
        <div class="card border-0 shadow-xs rounded-2 bg-white">
            <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between pt-2.5 px-3 pb-1">
                <div>
                    <h6 class="fw-bold text-dark mb-0">ការខ្ចីសៀវភៅសកម្ម & ហួសកាលកំណត់</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">បញ្ជីសៀវភៅដែលកំពុងខ្ចីទាំងអស់ដោយសិស្ស និងបុគ្គលិក</p>
                </div>
                <span class="badge bg-danger px-2.5 py-1" style="font-size: 0.78rem;">{{ $overdueIssues->count() }} ហួសកាលកំណត់</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-3">សៀវភៅ & លេខកូដ</th>
                                <th>អ្នកខ្ចី</th>
                                <th>ថ្ងៃខ្ចី</th>
                                <th>ថ្ងៃកំណត់សង</th>
                                <th>ស្ថានភាព</th>
                                <th class="pe-3 text-end">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeIssues as $aIssue)
                                <tr class="{{ $aIssue->is_overdue ? 'table-danger-subtle' : '' }}">
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">{{ $aIssue->library_book_copy->library_book->title ?? '—' }}</div>
                                        <span class="badge bg-light text-dark font-monospace border">
                                            {{ $aIssue->library_book_copy->accession_number ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $aIssue->user->name ?? '—' }}</div>
                                        <div class="text-muted small">
                                            @if($aIssue->user && $aIssue->user->student)
                                                សិស្ស &bull; អត្តលេខ៖ {{ $aIssue->user->student->admission_number }}
                                            @elseif($aIssue->user && $aIssue->user->employee)
                                                បុគ្គលិក &bull; កូដ៖ {{ $aIssue->user->employee->employee_code }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $aIssue->issue_date ? $aIssue->issue_date->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $aIssue->due_date ? $aIssue->due_date->format('d/m/Y') : 'គ្មានថ្ងៃកំណត់' }}</td>
                                    <td>
                                        @if($aIssue->is_overdue)
                                            <span class="badge bg-danger text-white">
                                                ហួសកាលកំណត់ {{ $aIssue->overdue_days }} ថ្ងៃ
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary">កំពុងខ្ចី</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="{{ route('library.returns.create', $aIssue) }}" class="btn btn-xs btn-outline-success">
                                            សងសៀវភៅ
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">មិនមានការខ្ចីសៀវភៅសកម្មនៅឡើយទេ។</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @elseif($tab === 'returns')
        {{-- TAB 3: RETURNS & FINES --}}
        <div class="card border-0 shadow-xs rounded-2 bg-white">
            <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between pt-2.5 px-3 pb-1">
                <div>
                    <h6 class="fw-bold text-dark mb-0">កំណត់ត្រាការសងសៀវភៅ និងប្រាក់ពិន័យ</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.78rem;">ប្រវត្តិការសងសៀវភៅជាមួយនឹងកំណត់ត្រាប្រាក់ពិន័យ</p>
                </div>
                <span class="badge bg-success px-2.5 py-1 currency-display" 
                      style="font-size: 0.78rem; cursor: pointer;"
                      onclick="toggleCurrencyDisplay()"
                      title="ចុចដើម្បីប្ដូររូបិយប័ណ្ណបង្ហាញ ($ ⇄ ៛)"
                      data-usd="សរុបប្រាក់ពិន័យ៖ ${{ number_format($totalFinesCollected, 2) }}"
                      data-khr="សរុបប្រាក់ពិន័យ៖ {{ number_format(round($totalFinesCollected * 4100, -2)) }} ៛">
                    <span class="curr-primary">សរុបប្រាក់ពិន័យ៖ ${{ number_format($totalFinesCollected, 2) }}</span>
                    <span class="curr-secondary d-none">សរុបប្រាក់ពិន័យ៖ {{ number_format(round($totalFinesCollected * 4100, -2)) }} ៛</span>
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-3">ចំណងជើងសៀវភៅ</th>
                                <th>លេខកូដសៀវភៅ</th>
                                <th>អ្នកខ្ចី</th>
                                <th>ថ្ងៃសងត្រឡប់</th>
                                <th class="pe-3 text-end">ចំនួនប្រាក់ពិន័យ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returnsSummary as $ret)
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        {{ $ret->library_issue->library_book_copy->library_book->title ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-monospace border">
                                            {{ $ret->library_issue->library_book_copy->accession_number ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $ret->library_issue->user->name ?? '—' }}</td>
                                    <td>{{ $ret->return_date ? $ret->return_date->format('d/m/Y') : '-' }}</td>
                                    <td class="pe-3 text-end">
                                        @if($ret->fine_amount > 0)
                                            <div class="currency-display" data-usd="${{ number_format($ret->fine_amount, 2) }}" data-khr="{{ number_format(round($ret->fine_amount * 4100, -2)) }} ៛">
                                                <span class="text-danger fw-bold curr-primary">${{ number_format($ret->fine_amount, 2) }}</span>
                                                <span class="text-danger fw-bold curr-secondary d-none">{{ number_format(round($ret->fine_amount * 4100, -2)) }} ៛</span>
                                            </div>
                                        @else
                                            <span class="text-muted">$0.00</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">មិនទាន់មានកំណត់ត្រាប្រតិបត្តិការសងសៀវភៅនៅឡើយទេ។</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @elseif($tab === 'popular')
        {{-- TAB 4: MOST BORROWED BOOKS --}}
        <div class="card border-0 shadow-xs rounded-2 bg-white">
            <div class="card-header bg-transparent border-0 pt-2.5 px-3 pb-1">
                <h6 class="fw-bold text-dark mb-0">សៀវភៅដែលត្រូវបានខ្ចីច្រើនជាងគេ</h6>
                <p class="text-muted small mb-0" style="font-size: 0.78rem;">ចំណាត់ថ្នាក់ចំណងជើងសៀវភៅដែលមានការខ្ចីច្រើនបំផុត</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-3" style="width: 60px;">លំដាប់</th>
                                <th>ចំណងជើងសៀវភៅ</th>
                                <th>អ្នកនិពន្ធ</th>
                                <th>ប្រភេទ</th>
                                <th class="pe-3 text-end">ចំនួនដងនៃការខ្ចីសរុប</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularBooks as $index => $b)
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">#{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('library.books.show', $b) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $b->title }}
                                        </a>
                                    </td>
                                    <td>{{ $b->author ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $b->library_category->name ?? 'គ្មានប្រភេទ' }}</span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <span class="badge bg-primary rounded-pill fs-6">{{ $b->library_issues_count }} លើក</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">មិនទាន់មានទិន្នន័យនៃការខ្ចីនៅឡើយទេ។</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($tab === 'borrowers')
        {{-- TAB 5: BORROWERS REPORT --}}
        {{-- Filter Bar --}}
        <div class="card border-0 shadow-xs rounded-2 bg-white mb-2">
            <div class="card-body py-2 px-3">
                <form method="GET" action="{{ route('library.reports.index') }}" class="d-flex flex-wrap align-items-center gap-1.5" style="max-width: 440px;">
                    <input type="hidden" name="tab" value="borrowers">
                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text bg-light border-end-0 pe-2"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 border-end-0 ps-1" 
                               placeholder="វាយបញ្ចូល ID / អត្តលេខ, ឈ្មោះ..." 
                               value="{{ request('search') }}" style="font-size: 0.8125rem;">
                        <button type="submit" class="btn btn-primary px-3 text-nowrap" style="font-size: 0.8125rem;">
                            <i class="bi bi-search me-1"></i>ស្វែងរក
                        </button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('library.reports.index', ['tab' => 'borrowers']) }}" class="btn btn-sm btn-outline-secondary text-nowrap py-1 px-2" style="font-size: 0.78rem;">
                            <i class="bi bi-x-circle me-1"></i>កំណត់ឡើងវិញ
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-xs rounded-2 bg-white">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-3">ព័ត៌មានអ្នកខ្ចី</th>
                                <th>តួនាទី / ផ្នែក</th>
                                <th>លេខសម្គាល់ (ID)</th>
                                <th>កំពុងខ្ចី</th>
                                <th>ហួសកាលកំណត់</th>
                                <th>ខ្ចីសរុប</th>
                                <th class="pe-3 text-end">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowers as $borrower)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark">
                                            <a href="{{ route('library.borrowers.show', $borrower) }}" class="text-decoration-none">
                                                {{ $borrower->name }}
                                            </a>
                                        </div>
                                        <span class="text-muted small">{{ $borrower->email }}</span>
                                    </td>
                                    <td>
                                        @if($borrower->student)
                                            <span class="badge bg-info-subtle text-info">សិស្ស</span>
                                        @elseif($borrower->employee)
                                            <span class="badge bg-secondary-subtle text-secondary">បុគ្គលិក / គ្រូ</span>
                                        @else
                                            <span class="badge bg-light text-dark">អ្នកប្រើប្រាស់</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($borrower->student)
                                            <span class="font-monospace small">អត្តលេខ៖ {{ $borrower->student->admission_number }}</span>
                                        @elseif($borrower->employee)
                                            <span class="font-monospace small">កូដ៖ {{ $borrower->employee->employee_code }}</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($borrower->active_issues_count > 0)
                                            <span class="badge bg-primary rounded-pill">{{ $borrower->active_issues_count }} កំពុងខ្ចី</span>
                                        @else
                                            <span class="text-muted small">0 កំពុងខ្ចី</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($borrower->overdue_issues_count > 0)
                                            <span class="badge bg-danger rounded-pill">{{ $borrower->overdue_issues_count }} ហួសកំណត់</span>
                                        @else
                                            <span class="text-muted small">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $borrower->total_issues_count }} លើក</span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="{{ route('library.borrowers.show', $borrower) }}" class="btn btn-xs btn-outline-secondary" title="មើលព័ត៌មានលម្អិត & ប្រវត្តិ">
                                            <i class="bi bi-eye me-1"></i> មើលប្រវត្តិ
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">រកមិនឃើញអ្នកខ្ចីដែលត្រូវគ្នានឹងលក្ខខណ្ឌនោះទេ។</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($borrowers->hasPages())
                    <div class="p-3 border-top">
                        {{ $borrowers->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endif
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
