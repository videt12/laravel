<header class="app-topbar bg-white border-bottom sticky-top">
    <div class="container-fluid px-3 px-lg-4 d-flex align-items-center justify-content-between h-100">
        
        {{-- Left: Sidebar Toggle, School Selector & Breadcrumb --}}
        <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
            <button type="button" class="btn btn-topbar-icon" id="sidebarToggleBtn" aria-label="Toggle navigation" title="បិទ/បើកម៉ឺនុយចំហៀង">
                <i class="bi bi-list fs-5"></i>
            </button>

            @php
                $navSchool = \App\Models\Core\School::first();
                $navSchoolName = $navSchool?->name ?? 'សាលារៀនប្រទីបបញ្ញា';
            @endphp
            {{-- School Selector --}}
            <div class="dropdown d-none d-sm-block">
                <button class="btn btn-sm d-flex align-items-center gap-2 px-2.5 py-1.5 rounded-pill border bg-light text-dark fw-medium" 
                        type="button" id="schoolSelectorDropdown" data-bs-toggle="dropdown" aria-expanded="false" 
                        style="font-size: 0.82rem; border-color: #e5e7eb !important;">
                    <i class="bi bi-building text-muted"></i>
                    <span class="text-truncate" style="max-width: 170px;">{{ $navSchoolName }}</span>
                    <i class="bi bi-chevron-down text-muted ms-0.5" style="font-size: 0.7rem;"></i>
                </button>
                <ul class="dropdown-menu shadow-sm border-0 py-1" style="min-width: 220px; border-radius: 10px;">
                    <li class="px-3 py-1.5 text-muted small fw-semibold" style="font-size: 0.72rem;">ជ្រើសរើសសាខាសាលារៀន</li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between py-1.5 active" href="javascript:void(0)" 
                           style="background-color: var(--gold, #f3b63a) !important; color: #181b20 !important; font-weight: 500;">
                            <span>{{ $navSchoolName }} (ចម្បង)</span>
                            <i class="bi bi-check2"></i>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Topbar Breadcrumbs / Page Context --}}
            <div class="topbar-breadcrumb d-none d-lg-flex align-items-center">
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ទំព័រដើម</a></li>
                            @if(request()->routeIs('dashboard'))
                                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ផ្ទាំងព័ត៌មាន</li>
                            @elseif(request()->routeIs('students.*'))
                                <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-decoration-none text-muted">ការសិក្សា</a></li>
                                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">សិស្ស</li>
                            @else
                                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">ផ្ទាំងព័ត៌មាន</li>
                            @endif
                        </ol>
                    </nav>
                @endif
            </div>
        </div>

        {{-- Right: Search, Notifications & User Dropdown --}}
        <div class="d-flex align-items-center gap-2 gap-sm-3">

            {{-- Global Search Form / Trigger --}}
            @if(auth()->user()?->canAccess('students'))
            <div class="topbar-search d-none d-sm-block">
                <form action="{{ route('students.index') }}" method="GET" class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: .85rem;"></i>
                    <input type="text"
                           name="search"
                           class="form-control form-control-sm topbar-search-input ps-5 pe-3"
                           placeholder="ស្វែងរក..."
                           autocomplete="off">
                </form>
            </div>

            {{-- Mobile Search Trigger Icon --}}
            <div class="d-sm-none">
                <button type="button" class="btn btn-topbar-icon" data-bs-toggle="collapse" data-bs-target="#mobileSearchCollapse" aria-expanded="false" title="ស្វែងរក">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            @endif

            {{-- Notification Bell Icon --}}
            <div class="dropdown">
                <button type="button" class="btn btn-topbar-icon position-relative" id="notificationsDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" title="ការជូនដំណឹង" aria-label="Notifications">
                    <i class="bi bi-bell fs-6"></i>
                    <span class="position-absolute p-1 bg-warning border border-light rounded-circle" style="top: 8px; right: 8px;">
                        <span class="visually-hidden">ការជូនដំណឹងថ្មី</span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2" aria-labelledby="notificationsDropdownBtn" style="min-width: 280px; border-radius: 12px;">
                    <li class="px-3 py-1.5 d-flex align-items-center justify-content-between border-bottom">
                        <span class="fw-semibold text-dark small">ការជូនដំណឹង</span>
                        <span class="badge bg-light text-muted border small">ប្រព័ន្ធ</span>
                    </li>
                    <li class="p-3 text-center text-muted small">
                        <i class="bi bi-bell-slash d-block fs-3 mb-1 text-muted opacity-50"></i>
                        គ្មានការជូនដំណឹងថ្មីនៅឡើយទេ
                    </li>
                </ul>
            </div>

            {{-- User Profile Dropdown --}}
            @php
                $authUser = auth()->user();
                $userPhoto = $authUser?->photo_url ?? $authUser?->employee?->photo_url ?? $authUser?->student?->photo_url;
                $userName = $authUser?->name ?? 'អ្នកគ្រប់គ្រង';
                $userRoleName = $authUser?->roles?->first()?->display_name ?? $authUser?->roles?->first()?->name ?? $authUser?->role?->name ?? 'អ្នកប្រើប្រាស់';
                $firstChar = $userName ? mb_substr(trim($userName), 0, 1, 'UTF-8') : '';
                $isEnglishName = preg_match('/^[a-zA-Z]/', $firstChar);
            @endphp
            <div class="dropdown">
                <button type="button" class="btn btn-topbar-user d-flex align-items-center gap-2 p-1" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar-topbar position-relative overflow-hidden">
                        @if($userPhoto)
                            <img src="{{ $userPhoto }}" alt="{{ $userName }}" class="w-100 h-100 object-fit-cover rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                            <span class="d-none">
                                @if($isEnglishName)
                                    {{ mb_strtoupper(mb_substr(trim($userName), 0, 2, 'UTF-8')) }}
                                @else
                                    <i class="bi bi-person-fill" style="font-size: 1rem;"></i>
                                @endif
                            </span>
                        @elseif($isEnglishName)
                            <span>{{ mb_strtoupper(mb_substr(trim($userName), 0, 2, 'UTF-8')) }}</span>
                        @else
                            <i class="bi bi-person-fill" style="font-size: 1rem;"></i>
                        @endif
                    </div>
                    <div class="d-none d-lg-block text-start lh-1 me-1">
                        <div class="fw-semibold text-dark user-name-topbar">{{ $userName }}</div>
                        <small class="text-muted user-role-topbar">{{ $userRoleName }}</small>
                    </div>
                    <i class="bi bi-chevron-down text-muted ms-1" style="font-size: .75rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2 user-dropdown-menu" aria-labelledby="userMenuDropdown">
                    <li class="px-3 py-2 border-bottom mb-1 d-lg-none">
                        <div class="fw-semibold text-dark">{{ auth()->user()?->name ?? 'អ្នកគ្រប់គ្រង' }}</div>
                        <small class="text-muted">{{ auth()->user()?->email ?? 'admin@school.edu' }}</small>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#userAccountModal" onclick="switchAccountTab('profile')">
                            <i class="bi bi-person text-muted"></i>
                            <span>គណនីរបស់ខ្ញុំ</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#userAccountModal" onclick="switchAccountTab('security')">
                            <i class="bi bi-shield-lock text-muted"></i>
                            <span>ការកំណត់គណនី</span>
                        </a>
                    </li>
                    @if(auth()->user()?->canAccess(['settings', 'roles']))
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('settings.index') }}">
                            <i class="bi bi-gear text-muted"></i>
                            <span>ការកំណត់ប្រព័ន្ធ</span>
                        </a>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger border-0 bg-transparent w-100">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>ចាកចេញពីប្រព័ន្ធ</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Mobile Expandable Search Row --}}
    @if(auth()->user()?->canAccess('students'))
    <div class="collapse bg-white border-top px-3 py-2 d-sm-none" id="mobileSearchCollapse">
        <form action="{{ route('students.index') }}" method="GET" class="position-relative">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: .85rem;"></i>
            <input type="text"
                   name="search"
                   class="form-control form-control-sm ps-5 pe-3"
                   placeholder="ស្វែងរកសិស្ស, ថ្នាក់រៀន, កំណត់ត្រា..."
                   autocomplete="off">
        </form>
    </div>
    @endif
</header>

{{-- User Account & Profile Modal --}}
@include('layouts.profile-modal')
