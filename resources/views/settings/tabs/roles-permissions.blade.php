{{-- TAB 2: ROLES & PERMISSIONS --}}
<div class="tab-pane fade {{ $activeTab === 'roles' ? 'show active' : '' }}" id="tab-roles" role="tabpanel">

    <div class="row g-4">
        {{-- Roles Table List --}}
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-shield-check me-2 text-warning"></i>តួនាទី និងសិទ្ធិប្រើប្រាស់ (Roles & Permissions)
                    </h5>
                    <p class="text-muted small mb-0">គ្រប់គ្រងតួនាទី និងកំណត់សិទ្ធិអនុញ្ញាតចូលប្រើប្រាស់ផ្នែកផ្សេងៗនៃប្រព័ន្ធ</p>
                </div>
                <div>
                    <button type="button" class="btn btn-warning btn-sm fw-bold d-inline-flex align-items-center gap-1 shadow-xs" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="bi bi-plus-lg"></i>+ បន្ថែមតួនាទី
                    </button>
                </div>
            </div>

            <div class="table-card mb-4">
                <div class="table-responsive">
                    <table class="table table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>ឈ្មោះតួនាទី (Role Name)</th>
                                <th>កូដសម្គាល់ (Slug)</th>
                                <th class="text-center">ចំនួនអ្នកប្រើប្រាស់</th>
                                <th class="text-center">ចំនួនសិទ្ធិ (Permissions)</th>
                                <th>កាលបរិច្ឆេទបង្កើត</th>
                                <th class="text-end text-nowrap" style="min-width: 250px;">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $index => $r)
                                <tr>
                                    <td class="text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="role-icon-box">
                                                <i class="bi bi-shield-lock text-warning"></i>
                                            </div>
                                             <div>
                                                <div class="fw-bold text-dark">{{ $r->display_name ?? $r->name }}</div>
                                                @if($r->slug === 'super-admin')
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.7rem;">Super Admin</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="text-secondary bg-light px-2 py-1 rounded small">{{ $r->slug }}</code>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5 shadow-xs d-inline-flex align-items-center gap-1.5" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#roleUsersModal{{ $r->id }}"
                                                title="ចុចដើម្បីមើលអ្នកប្រើប្រាស់ក្នុងតួនាទីនេះ"
                                                style="cursor: pointer; transition: all 0.15s ease;">
                                            <i class="bi bi-people-fill"></i>
                                            <span class="fw-semibold">{{ $r->users_count }} នាក់</span>
                                            <i class="bi bi-eye ms-0.5 text-info-emphasis" style="font-size: 0.72rem;"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-key me-1"></i>{{ $r->permissions_count }} / {{ $permissions->count() }}
                                        </span>
                                    </td>
                                    <td class="small text-muted text-nowrap">
                                        {{ $r->created_at ? $r->created_at->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <div class="d-inline-flex align-items-center justify-content-end gap-1.5 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-info px-2 py-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#roleUsersModal{{ $r->id }}" title="មើលអ្នកប្រើប្រាស់ក្នុងតួនាទីនេះ">
                                                <i class="bi bi-people me-1"></i>សមាជិក
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#permissionsModal{{ $r->id }}" title="កំណត់សិទ្ធិ">
                                                <i class="bi bi-sliders me-1"></i>សិទ្ធិ
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning px-2.5 py-1 text-dark text-nowrap" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $r->id }}" title="កែប្រែ">
                                                <i class="bi bi-pencil me-1 text-warning"></i>កែប្រែ
                                            </button>
                                            @if(!in_array($r->slug, ['super-admin', 'admin']))
                                                <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 text-nowrap" data-bs-toggle="modal" data-bs-target="#deleteRoleModal{{ $r->id }}" title="លុប">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="empty-state-cell">
                                <div class="empty-icon"><i class="bi bi-shield"></i></div>
                                <h6 class="fw-bold text-muted mb-1">មិនទាន់មានតួនាទីនៅឡើយទេ</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
