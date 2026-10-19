{{-- TAB 1: USERS --}}
<div class="tab-pane fade {{ $activeTab === 'users' ? 'show active' : '' }}" id="tab-users" role="tabpanel">

    {{-- Filter & Search Bar --}}
    <div class="filter-bar mb-3">
        <form method="GET" action="{{ route('settings.index') }}" class="d-flex align-items-center gap-2 flex-wrap w-100">
            <input type="hidden" name="tab" value="users">

            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកឈ្មោះ ឬអ៊ីមែល..." aria-label="Search users">
            </div>

            <select name="role" class="form-select filter-select">
                <option value="">-- គ្រប់តួនាទី (All Roles) --</option>
                @foreach($rolesList as $r)
                    <option value="{{ $r->id }}" {{ request('role') == $r->id ? 'selected' : '' }}>
                        {{ $r->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-select filter-select">
                <option value="">-- គ្រប់ស្ថានភាព --</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>សកម្ម (Active)</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>អសកម្ម (Inactive)</option>
            </select>

            <button type="submit" class="btn btn-filter">
                <i class="bi bi-funnel me-1"></i>ត្រង
            </button>

            @if(request('search') || request('role') || request('status'))
                <a href="{{ route('settings.index', ['tab' => 'users']) }}" class="btn btn-outline-secondary btn-sm" title="កំណត់ឡើងវិញ">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>សម្អាត
                </a>
            @endif

            <div class="ms-auto">
                <span class="text-muted small">សរុប៖ <strong class="text-dark">{{ $users->total() }}</strong> នាក់</span>
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="table-card mb-4">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>អ្នកប្រើប្រាស់</th>
                        <th>តួនាទី</th>
                        <th>ស្ថានភាព</th>
                        <th>ចូលចុងក្រោយ (Last Login)</th>
                        <th>ថ្ងៃបង្កើត</th>
                        <th class="text-end" style="width: 130px;">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                        <tr>
                            <td class="text-muted small">{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-circle">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        <div class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @forelse($u->roles as $role)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                        <i class="bi bi-shield me-1"></i>{{ $role->name }}
                                    </span>
                                @empty
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">គ្មានតួនាទី</span>
                                @endforelse
                            </td>
                            <td>
                                @if($u->status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i>សកម្ម
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i>អសកម្ម
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($u->login_activities_max_login_time)
                                    <span class="small text-dark fw-medium">
                                        <i class="bi bi-clock-history me-1 text-muted"></i>
                                        {{ \Carbon\Carbon::parse($u->login_activities_max_login_time)->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-muted small">មិនធ្លាប់ចូល</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $u->created_at ? $u->created_at->format('d/m/Y') : '—' }}
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="ជម្រើស">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <button type="button" class="dropdown-item small" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}">
                                                <i class="bi bi-pencil me-2 text-warning"></i>កែប្រែព័ត៌មាន
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item small" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $u->id }}">
                                                <i class="bi bi-key me-2 text-info"></i>ប្តូរពាក្យសម្ងាត់
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('settings.users.toggle-status', $u->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item small {{ $u->status === 'active' ? 'text-danger' : 'text-success' }}" {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                                    <i class="bi bi-power me-2"></i>{{ $u->status === 'active' ? 'ផ្អាកដំណើរការ (Deactivate)' : 'បើកដំណើរការ (Activate)' }}
                                                </button>
                                            </form>
                                        </li>
                                        @if($u->id !== auth()->id())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $u->id }}">
                                                    <i class="bi bi-trash me-2"></i>លុបគណនី
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="empty-state-cell">
                                <div class="empty-icon"><i class="bi bi-people"></i></div>
                                <h6 class="fw-bold text-muted mb-1">មិនមានអ្នកប្រើប្រាស់នៅឡើយទេ</h6>
                                <p class="text-muted small mb-0">សូមចុចប៊ូតុង "+ បន្ថែមអ្នកប្រើប្រាស់" ដើម្បីបង្កើតគណនីថ្មី។</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3 border-top bg-white d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
