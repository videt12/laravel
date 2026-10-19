@extends('layouts.app')

@section('title', 'ការកំណត់ប្រព័ន្ធ (System Settings) — School Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ការកំណត់ប្រព័ន្ធ</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════════
   PAGE HEADER & TOKENS
═══════════════════════════════════════════════════════════════════ */
.page-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--text-dark, #0f172a);
    margin-bottom: 0.2rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.page-title-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px; height: 38px;
    background: #fef3c7;
    color: #b45309;
    border-radius: 0px !important;
    font-size: 1.25rem;
}
.page-subtitle { color: var(--text-muted, #64748b); font-size: 0.85rem; margin-bottom: 0; }

/* ═══════════════════════════════════════════════════════════════════
   SETTINGS TAB NAV (GRID TABLE STYLE - SQUARE CORNERS & SINGLE LINE)
═══════════════════════════════════════════════════════════════════ */
.settings-tabs-wrapper {
    background: #ffffff;
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: 0px !important;
    border-bottom: 2px solid var(--form-card-border, #fcd34d);
    overflow: hidden;
}

.settings-tabs {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    margin: 0;
    padding: 0;
    background: #f8fafc;
    list-style: none;
}

.tab-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    padding: 0.85rem 0.4rem;
    font-size: 0.92rem;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border-right: 1px solid #e2e8f0;
    background: #f8fafc;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
    text-align: center;
    white-space: nowrap;
}

.tab-link span {
    white-space: nowrap;
}

.tab-link:last-child {
    border-right: none;
}

.tab-link:hover {
    background: #ffffff;
    color: #0f172a;
}

.tab-link.active {
    background: #ffffff;
    color: #b45309;
    font-weight: 700;
    box-shadow: inset 0 3px 0 #f59e0b;
}

.tab-link .tab-icon {
    font-size: 1.05rem;
    color: #64748b;
    flex-shrink: 0;
}

.tab-link.active .tab-icon {
    color: #d97706;
}

.tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 20px;
    padding: 0 5px;
    background: #e2e8f0;
    color: #475569;
    border-radius: 0px !important;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.tab-link.active .tab-count {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
    border-radius: 0px !important;
}

@media (max-width: 991.98px) {
    .settings-tabs {
        display: flex;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
    }
    .tab-link {
        flex: 0 0 auto;
        padding: 0.75rem 1.25rem;
    }
}

/* ═══════════════════════════════════════════════════════════════════
   FILTER BAR & SEARCH
═══════════════════════════════════════════════════════════════════ */
.filter-bar {
    background: #ffffff;
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: 0px !important;
    padding: 0.85rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: 0 1px 2px rgba(0,0,0,.02);
}
.search-wrapper {
    position: relative;
    min-width: 220px;
    flex: 1 1 220px;
    max-width: 320px;
}
.search-wrapper i {
    position: absolute;
    left: 0.85rem; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted, #64748b);
    font-size: 0.85rem;
}
.search-wrapper input {
    width: 100%;
    padding: 0.48rem 0.85rem 0.48rem 2.3rem;
    font-size: 0.9rem;
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: 0px !important;
    transition: all 0.2s;
    background: #ffffff;
}
.search-wrapper input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
    outline: none;
}
.filter-select {
    font-size: 0.875rem;
    padding: 0.48rem 2rem 0.48rem 0.75rem;
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: 0px !important;
    background: #ffffff;
    flex: 0 0 auto;
    max-width: 220px;
}
.btn-filter {
    padding: 0.48rem 1.1rem;
    font-size: 0.875rem;
    font-weight: 600;
    background: #f59e0b;
    border: 1px solid #f59e0b;
    border-radius: 0px !important;
    color: #ffffff;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-filter:hover { background: #d97706; border-color: #d97706; color: #ffffff; }

/* ═══════════════════════════════════════════════════════════════════
   TABLE & CARDS
═══════════════════════════════════════════════════════════════════ */
.table-card {
    background: #ffffff;
    border: 1px solid var(--form-card-border, #fcd34d);
    border-radius: 0px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    overflow: hidden;
}
.table-custom { margin: 0; width: 100%; }
.table-custom thead th {
    background: #fffbeb;
    color: #78350f;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.85rem 1rem;
    border-bottom: 1px solid #fcd34d;
    white-space: nowrap;
}
.table-custom tbody tr:nth-child(even) { background-color: #fefce8; }
.table-custom tbody td {
    padding: 0.85rem 1rem;
    font-size: 0.92rem;
    vertical-align: middle;
    border-bottom: 1px solid #fef3c7;
    color: var(--text-dark, #0f172a);
}
.table-custom tbody tr:hover td { background-color: #fef3c7 !important; }

/* ═══════════════════════════════════════════════════════════════════
   ICONS, AVATARS & BUTTONS
═══════════════════════════════════════════════════════════════════ */
.user-avatar-circle {
    width: 36px; height: 36px;
    border-radius: 50% !important;
    background: #fef3c7;
    color: #b45309;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    border: 1px solid #fde68a;
    flex-shrink: 0;
}
.role-icon-box {
    width: 34px; height: 34px;
    border-radius: 8px !important;
    background: #fffbeb;
    border: 1px solid #fde68a;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border-radius: 6px !important;
    color: #64748b;
    transition: all 0.2s;
    background: transparent; border: 1px solid transparent;
    cursor: pointer;
}
.action-btn:hover { background: #f8fafc; border-color: #e2e8f0; color: #0f172a; }
.action-btn-danger:hover { background: #fef2f2; border-color: #fecaca; color: #dc2626; }
.empty-state-cell { padding: 3.5rem 1.25rem !important; text-align: center; }
.empty-icon { font-size: 2.5rem; color: #cbd5e1; margin-bottom: 0.75rem; }
.btn-xs { padding: 0.2rem 0.5rem; font-size: 0.75rem; border-radius: 0px !important; }

/* ═══════════════════════════════════════════════════════════════════
   MODAL FIXES (Fix backdrop overlay / stacking context bug)
═══════════════════════════════════════════════════════════════════ */
.modal-backdrop {
    z-index: 1050 !important;
}
.modal {
    z-index: 1055 !important;
}
.modal-content {
    background-color: #ffffff !important;
    border-radius: 0px !important;
    border: 1px solid var(--form-card-border, #fcd34d) !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.05) !important;
}
.modal-content .modal-header {
    background-color: #fffbeb !important;
    border-bottom: 1px solid #fcd34d !important;
}
.modal-content .modal-footer {
    background-color: #f8fafc !important;
    border-top: 1px solid #e2e8f0 !important;
}
.modal-dialog-scrollable {
    max-height: calc(100vh - 2rem) !important;
}
.modal-dialog-scrollable .modal-content {
    max-height: calc(100vh - 2rem) !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
}
.modal-dialog-scrollable .modal-body {
    overflow-y: auto !important;
    flex: 1 1 auto !important;
    max-height: calc(100vh - 180px) !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    {{-- ═══ FLASH MESSAGES ═══ --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3 shadow-xs" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3 shadow-xs" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3 shadow-xs" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon me-1"></i> សូមពិនិត្យកំហុសខាងក្រោម៖</div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">
                <div class="page-title-icon"><i class="bi bi-gear-fill"></i></div>
                ការកំណត់ប្រព័ន្ធ
            </h1>
            <p class="page-subtitle">គ្រប់គ្រងអ្នកប្រើប្រាស់ តួនាទី ព័ត៌មានសាលា និងការកំណត់ប្រព័ន្ធ</p>
        </div>

        {{-- Dynamic Top-Right Action Button --}}
        <div>
            @if($activeTab === 'users')
                <button type="button" class="btn btn-warning fw-bold shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus-fill"></i>+ បន្ថែមអ្នកប្រើប្រាស់
                </button>
            @elseif($activeTab === 'roles')
                <button type="button" class="btn btn-warning fw-bold shadow-sm d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="bi bi-shield-plus"></i>+ បន្ថែមតួនាទី
                </button>
            @elseif($activeTab === 'school-profile')
                <button type="button" class="btn btn-warning fw-bold shadow-sm d-inline-flex align-items-center gap-1" onclick="document.getElementById('schoolProfileForm').submit();">
                    <i class="bi bi-check2-circle"></i>រក្សាទុកព័ត៌មានសាលា
                </button>
            @elseif($activeTab === 'system')
                <button type="button" class="btn btn-warning fw-bold shadow-sm d-inline-flex align-items-center gap-1" onclick="document.getElementById('systemSettingsForm').submit();">
                    <i class="bi bi-save"></i>រក្សាទុកការកំណត់
                </button>
            @elseif($activeTab === 'backup-logs')
                <form action="{{ route('settings.backup.store') }}" method="POST" onsubmit="this.querySelector('button').disabled = true;">
                    @csrf
                    <button type="submit" class="btn btn-warning fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-plus-circle-fill"></i>បង្កើត Backup ឥឡូវនេះ
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ═══ MAIN TAB NAVIGATION ═══ --}}
    <div class="settings-tabs-wrapper mb-4">
        <ul class="settings-tabs">
            <li>
                <a href="{{ route('settings.index', ['tab' => 'users']) }}" class="tab-link {{ $activeTab === 'users' ? 'active' : '' }}">
                    <i class="bi bi-people tab-icon"></i>
                    <span>អ្នកប្រើប្រាស់</span>
                    <span class="tab-count">{{ $counts['users'] }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index', ['tab' => 'roles']) }}" class="tab-link {{ $activeTab === 'roles' ? 'active' : '' }}">
                    <i class="bi bi-shield-lock tab-icon"></i>
                    <span>តួនាទី & សិទ្ធិ</span>
                    <span class="tab-count">{{ $counts['roles'] }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index', ['tab' => 'school-profile']) }}" class="tab-link {{ $activeTab === 'school-profile' ? 'active' : '' }}">
                    <i class="bi bi-building tab-icon"></i>
                    <span>ប្រវត្តិរូបសាលា</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index', ['tab' => 'system']) }}" class="tab-link {{ $activeTab === 'system' ? 'active' : '' }}">
                    <i class="bi bi-sliders tab-icon"></i>
                    <span>ការកំណត់ទូទៅ</span>
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index', ['tab' => 'backup-logs']) }}" class="tab-link {{ $activeTab === 'backup-logs' ? 'active' : '' }}">
                    <i class="bi bi-shield-check tab-icon"></i>
                    <span>បម្រុងទុក & សុវត្ថិភាព</span>
                    <span class="tab-count">{{ $counts['backups'] ?? 0 }}</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- ═══ TAB CONTENT PANES ═══ --}}
    <div class="tab-content">
        @if($activeTab === 'users')
            @include('settings.tabs.users')
        @elseif($activeTab === 'roles')
            @include('settings.tabs.roles-permissions')
        @elseif($activeTab === 'school-profile')
            @include('settings.tabs.school-profile')
        @elseif($activeTab === 'system')
            @include('settings.tabs.system-settings')
        @elseif($activeTab === 'backup-logs')
            @include('settings.tabs.backup-logs')
        @endif
    </div>

</div>

{{-- ═══ ALL SETTINGS MODALS ═══ --}}
@include('settings.modals')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Move all modals directly to document.body so they are never trapped in stacking contexts
    document.querySelectorAll('.modal').forEach(function (modal) {
        document.body.appendChild(modal);
    });

    // 1. Permissions modal: Select All & Deselect All
    document.querySelectorAll('.select-all-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);
            if (container) {
                container.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                    cb.checked = true;
                });
            }
        });
    });

    document.querySelectorAll('.deselect-all-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);
            if (container) {
                container.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
                    cb.checked = false;
                });
            }
        });
    });

    // 2. School Profile: Logo image preview
    const logoInput = document.getElementById('schoolLogoInput');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');

    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    logoPreview.src = event.target.result;
                    logoPreview.classList.remove('d-none');
                    if (logoPlaceholder) {
                        logoPlaceholder.classList.add('d-none');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. Unified Role Name & Preset Selection
    const nameInput = document.getElementById('roleNameInput');
    const slugInput = document.getElementById('roleSlugInput');

    const presetSlugs = {
        'Principal': 'principal',
        'Vice Principal': 'vice-principal',
        'Academic Coordinator': 'academic-coordinator',
        'Head of Department': 'head-of-department',
        'Teacher': 'teacher',
        'Assistant Teacher': 'assistant-teacher',
        'Discipline Officer': 'discipline-officer',
        'Librarian': 'librarian',
        'Accountant': 'accountant',
        'Cashier': 'cashier',
        'Registrar': 'registrar',
        'Administrative Officer': 'administrative-officer',
        'IT Administrator': 'it-administrator',
        'Counselor': 'counselor',
        'Staff': 'staff'
    };

    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    if (nameInput && slugInput) {
        // Preset dropdown item click
        document.querySelectorAll('.preset-role-item').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const name = this.getAttribute('data-name');
                const slug = this.getAttribute('data-slug');
                nameInput.value = name;
                slugInput.value = slug;
                slugInput.dataset.touched = 'true';
            });
        });

        // Auto slug generation on type or datalist select
        nameInput.addEventListener('input', function () {
            if (!slugInput.dataset.touched || slugInput.value === '') {
                const val = this.value.trim();
                if (presetSlugs[val]) {
                    slugInput.value = presetSlugs[val];
                } else {
                    slugInput.value = generateSlug(val);
                }
            }
        });

        slugInput.addEventListener('input', function () {
            this.dataset.touched = 'true';
        });
    }

    // ── Test Telegram Bot Connection ──
    const btnTest = document.getElementById('btnTestTelegram');
    const statusSpan = document.getElementById('testTelegramStatus');
    if (btnTest) {
        btnTest.addEventListener('click', function () {
            const token = document.getElementById('telegramBotToken')?.value;
            const chatId = document.getElementById('telegramChatId')?.value;

            btnTest.disabled = true;
            btnTest.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>កំពុងសាកល្បង...';
            if (statusSpan) {
                statusSpan.className = 'small text-muted';
                statusSpan.innerText = '';
            }

            fetch('{{ route("settings.telegram.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    bot_token: token,
                    chat_id: chatId
                })
            })
            .then(res => res.json())
            .then(data => {
                btnTest.disabled = false;
                btnTest.innerHTML = '<i class="bi bi-send-check me-1"></i>សាកល្បងការតភ្ជាប់ (Test Bot)';
                if (statusSpan) {
                    if (data.success) {
                        statusSpan.className = 'small text-success fw-bold';
                        statusSpan.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>' + data.message;
                    } else {
                        statusSpan.className = 'small text-danger fw-bold';
                        statusSpan.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>' + data.message;
                    }
                }
            })
            .catch(err => {
                btnTest.disabled = false;
                btnTest.innerHTML = '<i class="bi bi-send-check me-1"></i>សាកល្បង Bot ទី ១ (Test Bot)';
                if (statusSpan) {
                    statusSpan.className = 'small text-danger fw-bold';
                    statusSpan.innerText = 'កំហុសបណ្តាញ: ' + err.message;
                }
            });
        });
    }

    // ── Test Leave Telegram Bot Connection ──
    const btnTestLeave = document.getElementById('btnTestLeaveTelegram');
    const statusLeaveSpan = document.getElementById('testLeaveTelegramStatus');
    if (btnTestLeave) {
        btnTestLeave.addEventListener('click', function () {
            const token = document.getElementById('telegramLeaveBotToken')?.value || document.getElementById('telegramBotToken')?.value;
            const chatId = document.getElementById('telegramLeaveChatId')?.value || document.getElementById('telegramChatId')?.value;

            btnTestLeave.disabled = true;
            btnTestLeave.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>កំពុងសាកល្បង...';
            if (statusLeaveSpan) {
                statusLeaveSpan.className = 'small text-muted';
                statusLeaveSpan.innerText = '';
            }

            fetch('{{ route("settings.telegram.test") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    bot_token: token,
                    chat_id: chatId
                })
            })
            .then(res => res.json())
            .then(data => {
                btnTestLeave.disabled = false;
                btnTestLeave.innerHTML = '<i class="bi bi-send-check me-1"></i>សាកល្បង Bot ច្បាប់សម្រាក (Test Leave Bot)';
                if (statusLeaveSpan) {
                    if (data.success) {
                        statusLeaveSpan.className = 'small text-success fw-bold';
                        statusLeaveSpan.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>' + data.message;
                    } else {
                        statusLeaveSpan.className = 'small text-danger fw-bold';
                        statusLeaveSpan.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>' + data.message;
                    }
                }
            })
            .catch(err => {
                btnTestLeave.disabled = false;
                btnTestLeave.innerHTML = '<i class="bi bi-send-check me-1"></i>សាកល្បង Bot ច្បាប់សម្រាក (Test Leave Bot)';
                if (statusLeaveSpan) {
                    statusLeaveSpan.className = 'small text-danger fw-bold';
                    statusLeaveSpan.innerText = 'កំហុសបណ្តាញ: ' + err.message;
                }
            });
        });
    }
});
</script>
@endpush
