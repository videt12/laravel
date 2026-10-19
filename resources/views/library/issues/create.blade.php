@extends('layouts.app')

@section('title', 'កត់ត្រាការខ្ចីសៀវភៅ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">កត់ត្រាការខ្ចីសៀវភៅ</h1>
            <p class="text-muted small mb-0 mt-1">ផ្តល់សៀវភៅដែលមានក្នុងស្តុកឱ្យសិស្ស ឬបុគ្គលិកខ្ចី</p>
        </div>
        <a href="{{ route('library.issues.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅការខ្ចីសៀវភៅ
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-xs rounded-3 bg-white p-4">
                <form method="POST" action="{{ route('library.issues.store') }}">
                    @csrf

                    {{-- Borrower Identification (ID & Auto Name) --}}
                    <div class="card border rounded-3 p-3 mb-3 bg-light">
                        <div class="row g-3">
                            {{-- Col 1: Borrower ID input --}}
                            <div class="col-12 col-md-5">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-upc-scan text-primary me-1"></i> អត្តលេខ / ID អ្នកខ្ចី <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-primary"><i class="bi bi-person-vcard"></i></span>
                                    <input type="text" id="borrowerCodeInput" class="form-control form-control-lg fw-bold font-monospace" 
                                           placeholder="វាយអត្តលេខ..." 
                                           autocomplete="off" autofocus>
                                    <button type="button" class="btn btn-outline-secondary" id="btnClearBorrower" title="លុបសម្អាត">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Col 2: Borrower Name (Auto Display) --}}
                            <div class="col-12 col-md-7">
                                <label class="form-label fw-bold text-dark">
                                    <i class="bi bi-person-check text-success me-1"></i> ឈ្មោះអ្នកខ្ចី
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white" id="nameInputIcon"><i class="bi bi-person text-muted fs-5"></i></span>
                                    <input type="text" id="borrowerNameDisplay" class="form-control form-control-lg fw-bold text-dark bg-white" 
                                           readonly placeholder="ឈ្មោះអ្នកខ្ចីនឹងបង្ហាញស្វ័យប្រវត្តិពេលវាយ ID...">
                                </div>
                            </div>
                        </div>

                        {{-- Alert if not found --}}
                        <div id="borrowerNotFoundAlert" class="alert alert-warning py-2 px-3 mt-2 mb-0 d-none small">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                            <span id="borrowerNotFoundMsg">រកមិនឃើញអ្នកខ្ចីដែលមានអត្តលេខ/កូដនេះទេ!</span>
                        </div>

                        <input type="hidden" name="user_id" id="hiddenUserId" value="{{ old('user_id') }}" required>
                        <input type="hidden" name="borrower_code" id="hiddenBorrowerCode" value="{{ old('borrower_code') }}">
                        @error('user_id')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Available Copy Selector --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark">ជ្រើសរើសច្បាប់សៀវភៅដែលមានក្នុងស្តុក <span class="text-danger">*</span></label>
                        <select name="library_book_copy_id" class="form-select @error('library_book_copy_id') is-invalid @enderror" required>
                            <option value="">-- ជ្រើសរើសច្បាប់សៀវភៅ --</option>
                            @foreach($availableCopies as $copy)
                                <option value="{{ $copy->id }}" {{ (string)old('library_book_copy_id', $preselectedCopyId) === (string)$copy->id ? 'selected' : '' }}>
                                    {{ $copy->library_book->title ?? 'មិនស្គាល់សៀវភៅ' }} — លេខកូដ #{{ $copy->accession_number }}
                                </option>
                            @endforeach
                        </select>
                        @if($availableCopies->isEmpty())
                            <div class="form-text text-danger">បច្ចុប្បន្នគ្មានច្បាប់សៀវភៅណាមួយនៅសល់សម្រាប់ខ្ចីនោះទេ។</div>
                        @else
                            <div class="form-text text-muted">កំពុងបង្ហាញច្បាប់សៀវភៅនៅសល់ចំនួន {{ $availableCopies->count() }} ក្បាល។ មានតែសៀវភៅដែលនៅសល់ប៉ុណ្ណោះដែលអាចខ្ចីបាន។</div>
                        @endif
                        @error('library_book_copy_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Dates Row --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium text-dark">ថ្ងៃខ្ចី <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control @error('issue_date') is-invalid @enderror" value="{{ old('issue_date', $defaultIssueDate) }}" required>
                            @error('issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium text-dark">ថ្ងៃកំណត់សង</label>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $defaultDueDate) }}">
                            <div class="form-text text-muted">រយៈពេលខ្ចីជាទូទៅគឺ ១៤ ថ្ងៃ។</div>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('library.issues.index') }}" class="btn btn-sm btn-outline-secondary">បោះបង់</a>
                        <button type="submit" id="btnSubmitIssue" class="btn btn-sm btn-gold text-white" style="background-color: var(--gold, #e8a838); border-color: var(--gold, #e8a838);" {{ $availableCopies->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-right me-1"></i> កត់ត្រាការខ្ចី
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@php
    $borrowersJson = $borrowers->map(function($b) {
        $code = '';
        $roleName = 'អ្នកប្រើប្រាស់';
        $details = $b->email ?? '';
        $khmerName = '';

        if ($b->student) {
            $code = (string)($b->student->admission_number ?? '');
            $roleName = 'សិស្ស';
            $prof = $b->student->studentProfile;
            if ($prof && ($prof->last_name_khmer || $prof->first_name_khmer)) {
                $khmerName = trim(($prof->last_name_khmer ?? '') . ' ' . ($prof->first_name_khmer ?? ''));
            }
            $className = $b->student->schoolClass->name ?? '';
            $details = $className ? "សិស្ស • ថ្នាក់៖ {$className}" : "សិស្ស";
            if ($b->email) {
                $details .= " • អ៊ីមែល៖ {$b->email}";
            }
        } elseif ($b->employee) {
            $code = (string)($b->employee->employee_code ?? '');
            $roleName = 'បុគ្គលិក / គ្រូ';
            $dept = $b->employee->employee_department->name ?? '';
            $details = $dept ? "បុគ្គលិក • ផ្នែក៖ {$dept}" : "បុគ្គលិក / គ្រូ";
            if ($b->email) {
                $details .= " • អ៊ីមែល៖ {$b->email}";
            }
        }

        $displayName = $khmerName ? "{$khmerName} ({$b->name})" : $b->name;

        return [
            'id' => $b->id,
            'name' => $displayName,
            'raw_name' => $b->name,
            'khmer_name' => $khmerName,
            'code' => $code,
            'role_name' => $roleName,
            'details' => $details,
        ];
    });
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const borrowers = @json($borrowersJson);

    const borrowerCodeInput = document.getElementById('borrowerCodeInput');
    const borrowerNameDisplay = document.getElementById('borrowerNameDisplay');
    const nameInputIcon = document.getElementById('nameInputIcon');
    const borrowerNotFoundAlert = document.getElementById('borrowerNotFoundAlert');
    const borrowerNotFoundMsg = document.getElementById('borrowerNotFoundMsg');
    const btnClearBorrower = document.getElementById('btnClearBorrower');
    const hiddenUserId = document.getElementById('hiddenUserId');
    const hiddenBorrowerCode = document.getElementById('hiddenBorrowerCode');
    const issueForm = document.querySelector('form[action="{{ route('library.issues.store') }}"]');

    function setBorrowerFound(b) {
        hiddenUserId.value = b.id;
        hiddenBorrowerCode.value = b.code || b.id;

        borrowerNameDisplay.value = b.name;
        borrowerNameDisplay.classList.add('border-success');

        if (nameInputIcon) {
            nameInputIcon.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5"></i>';
        }

        if (borrowerNotFoundAlert) {
            borrowerNotFoundAlert.classList.add('d-none');
        }
    }

    function setBorrowerNotFound(raw) {
        hiddenUserId.value = '';
        hiddenBorrowerCode.value = '';

        borrowerNameDisplay.value = '';
        borrowerNameDisplay.placeholder = `មិនទាន់រកឃើញអ្នកខ្ចីដែលមាន ID "${raw}" ទេ...`;
        borrowerNameDisplay.classList.remove('border-success');

        if (nameInputIcon) {
            nameInputIcon.innerHTML = '<i class="bi bi-person text-muted fs-5"></i>';
        }
    }

    function clearBorrower(focus = true) {
        hiddenUserId.value = '';
        hiddenBorrowerCode.value = '';
        borrowerCodeInput.value = '';
        borrowerNameDisplay.value = '';
        borrowerNameDisplay.placeholder = 'ឈ្មោះអ្នកខ្ចីនឹងបង្ហាញស្វ័យប្រវត្តិពេលវាយ ID...';
        borrowerNameDisplay.classList.remove('border-success');

        if (nameInputIcon) {
            nameInputIcon.innerHTML = '<i class="bi bi-person text-muted fs-5"></i>';
        }

        if (borrowerNotFoundAlert) {
            borrowerNotFoundAlert.classList.add('d-none');
        }

        if (focus) {
            borrowerCodeInput.focus();
        }
    }

    function performLookup(rawTerm) {
        const term = (rawTerm || '').trim();
        if (!term) {
            clearBorrower(false);
            return;
        }

        const lower = term.toLowerCase();

        // 1. Exact match on code (admission_number or employee_code)
        let found = borrowers.find(b => b.code && b.code.toLowerCase() === lower);

        // 2. Exact match on user ID
        if (!found) {
            found = borrowers.find(b => String(b.id) === lower);
        }

        // 3. Prefix match on code (if at least 2 chars)
        if (!found && lower.length >= 2) {
            found = borrowers.find(b => b.code && b.code.toLowerCase().startsWith(lower));
        }

        // 4. Partial match on name (if at least 3 chars)
        if (!found && lower.length >= 3) {
            found = borrowers.find(b => 
                (b.raw_name && b.raw_name.toLowerCase().includes(lower)) ||
                (b.khmer_name && b.khmer_name.toLowerCase().includes(lower))
            );
        }

        if (found) {
            setBorrowerFound(found);
        } else {
            setBorrowerNotFound(term);
        }
    }

    // Real-time instant auto-search on EVERY keystroke
    borrowerCodeInput.addEventListener('input', function () {
        performLookup(this.value);
    });

    // Handle Enter key inside ID input
    borrowerCodeInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performLookup(this.value);
        }
    });

    // Clear button
    if (btnClearBorrower) {
        btnClearBorrower.addEventListener('click', function () {
            clearBorrower(true);
        });
    }

    // Form submission validation
    if (issueForm) {
        issueForm.addEventListener('submit', function (e) {
            if (!hiddenUserId.value) {
                const currentTerm = borrowerCodeInput.value.trim();
                if (currentTerm) {
                    performLookup(currentTerm);
                }
                if (!hiddenUserId.value) {
                    e.preventDefault();
                    borrowerNotFoundMsg.textContent = 'សូមវាយបញ្ចូល ID ឬជ្រើសរើសអ្នកខ្ចីជាមុនសិន!';
                    borrowerNotFoundAlert.classList.remove('d-none');
                    borrowerCodeInput.focus();
                }
            }
        });
    }

    // Initial check (e.g. on validation errors or pre-filled value)
    const initialUserId = hiddenUserId.value;
    if (initialUserId) {
        const b = borrowers.find(item => String(item.id) === String(initialUserId));
        if (b) {
            borrowerCodeInput.value = b.code || b.id;
            setBorrowerFound(b);
        }
    }
});
</script>
@endpush
@endsection
