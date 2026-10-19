{{-- ═══════════════════════════════════════════════════════════════════
     SCHEDULE & SHIFT INFORMATION BANNER
═══════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between p-2.5 px-3 mb-3 rounded-3 border bg-white shadow-xs flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
        <div class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 1rem;">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">ម៉ោងកត់ត្រាវត្តមានបុគ្គលិកផ្លូវការ (Attendance Schedule)</h6>
            <small class="text-muted" style="font-size: 0.72rem;">គ្រប់គ្រង ៤ ប្រតិបត្តិការ (4 Events/Day): ព្រឹក ចូល-ចេញ និង រសៀល ចូល-ចេញ</small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-1.5 flex-wrap">
        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning-subtle px-2.5 py-1 rounded-pill font-monospace" style="font-size: 0.75rem;">
            ☀️ <strong class="text-warning-emphasis">វេនព្រឹក:</strong> ចូល {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_in'] ?? '06:50')->format('h:i A') }} · ចេញ {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_out'] ?? '10:30')->format('h:i A') }}
        </span>
        <span class="badge bg-info bg-opacity-10 text-dark border border-info-subtle px-2.5 py-1 rounded-pill font-monospace" style="font-size: 0.75rem;">
            🌤️ <strong class="text-info-emphasis">វេនរសៀល:</strong> ចូលវិញ {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_in'] ?? '13:10')->format('h:i A') }} · ចេញ {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_out'] ?? '16:30')->format('h:i A') }}
        </span>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     TODAY'S KPI OVERVIEW CARDS (Compact)
═══════════════════════════════════════════════════════════════════ --}}
<div class="row g-2 mb-3">
    {{-- Total Staff --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-xs rounded-3 px-3 py-2 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block" style="font-size: 0.74rem;">បុគ្គលិកសរុប (Total Staff)</span>
                    <div class="fs-5 fw-bold text-dark lh-1 my-1">{{ $stats['total_staff'] ?? 0 }}</div>
                    <span class="text-muted" style="font-size: 0.7rem;">បានកត់ត្រា៖ {{ $stats['recorded'] ?? 0 }} នាក់</span>
                </div>
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Present --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-xs rounded-3 px-3 py-2 bg-white h-100 border-start border-success border-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block" style="font-size: 0.74rem;">មានវត្តមាន (Present)</span>
                    <div class="fs-5 fw-bold text-success lh-1 my-1">{{ $stats['present'] ?? 0 }}</div>
                    <span class="text-success" style="font-size: 0.7rem;">អត្រា៖ {{ $stats['present_pct'] ?? 0 }}%</span>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Late --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-xs rounded-3 px-3 py-2 bg-white h-100 border-start border-warning border-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block" style="font-size: 0.74rem;">យឺត (Late)</span>
                    <div class="fs-5 fw-bold text-warning lh-1 my-1">{{ $stats['late'] ?? 0 }}</div>
                    <span class="text-warning" style="font-size: 0.7rem;">អត្រា៖ {{ $stats['late_pct'] ?? 0 }}%</span>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center text-warning" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Absent / Leave --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-xs rounded-3 px-3 py-2 bg-white h-100 border-start border-danger border-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block" style="font-size: 0.74rem;">អវត្តមាន / ច្បាប់</span>
                    <div class="fs-5 fw-bold text-danger lh-1 my-1">{{ ($stats['absent'] ?? 0) + ($stats['leave'] ?? 0) }}</div>
                    <span class="text-muted" style="font-size: 0.7rem;">អវត្តមាន: {{ $stats['absent'] ?? 0 }} | ច្បាប់: {{ $stats['leave'] ?? 0 }}</span>
                </div>
                <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center text-danger" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="bi bi-person-x-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     TAB: DAILY ATTENDANCE TABLE & FILTERS
═══════════════════════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-xs rounded-3 overflow-hidden mb-3">
    <div class="card-header bg-white px-3 py-2.5 border-bottom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">
                    <i class="bi bi-calendar-check text-warning me-1.5"></i>តារាងវត្តមានបុគ្គលិកប្រចាំថ្ងៃ (Daily Attendance)
                </h6>
                <small class="text-muted" style="font-size: 0.72rem;">កាលបរិច្ឆេទ៖ <strong>{{ \Carbon\Carbon::parse($currentDate)->format('d-M-Y') }}</strong></small>
            </div>
            
            {{-- Filter Form: Date / Staff / Status / Session / Search --}}
            <form action="{{ route('staff-attendance.index') }}" method="GET" class="d-flex align-items-center flex-wrap gap-1.5 m-0">
                <input type="hidden" name="tab" value="daily">
                
                {{-- Date Filter --}}
                <div class="d-flex align-items-center">
                    <input type="date" name="date" class="form-control form-control-sm rounded-pill py-1" value="{{ $currentDate }}" onchange="this.form.submit()" style="min-width: 132px; width: auto; font-size: 0.78rem;" title="ជ្រើសរើសកាលបរិច្ឆេទ">
                </div>

                {{-- Staff Filter --}}
                <select name="employee_id" class="form-select form-select-sm rounded-pill py-1" onchange="this.form.submit()" style="min-width: 145px; width: auto; font-size: 0.78rem;">
                    <option value="">បុគ្គលិកទាំងអស់</option>
                    @foreach($allEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ (string)request('employee_id') === (string)$emp->id ? 'selected' : '' }}>
                            {{ $emp->user ? $emp->user->name : $emp->employee_code }}
                        </option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select name="status" class="form-select form-select-sm rounded-pill py-1" onchange="this.form.submit()" style="min-width: 135px; width: auto; font-size: 0.78rem;">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>មានវត្តមាន (Present)</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>យឺត (Late)</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>អវត្តមាន (Absent)</option>
                    <option value="leave" {{ request('status') === 'leave' ? 'selected' : '' }}>ច្បាប់ (Leave)</option>
                </select>

                {{-- Session Filter --}}
                <select name="session" class="form-select form-select-sm rounded-pill py-1" onchange="this.form.submit()" style="min-width: 120px; width: auto; font-size: 0.78rem;">
                    <option value="">វេនទាំងអស់</option>
                    <option value="morning" {{ request('session') === 'morning' ? 'selected' : '' }}>☀️ វេនព្រឹក</option>
                    <option value="afternoon" {{ request('session') === 'afternoon' ? 'selected' : '' }}>🌤️ វេនរសៀល</option>
                </select>

                {{-- Search --}}
                <div class="input-group input-group-sm" style="max-width: 175px;">
                    <input type="text" name="search" class="form-control form-control-sm rounded-start-pill ps-2.5 py-1" placeholder="ស្វែងរក..." value="{{ request('search') }}" style="font-size: 0.78rem;">
                    <button class="btn btn-sm btn-warning rounded-end-pill px-2.5 py-1" type="submit"><i class="bi bi-search"></i></button>
                </div>

                @if(request()->hasAny(['search', 'status', 'employee_id', 'session']) || ($currentDate !== \Carbon\Carbon::today()->format('Y-m-d')))
                    <a href="{{ route('staff-attendance.index', ['tab' => 'daily']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="សម្អាត Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">បុគ្គលិក (Staff)</th>
                        <th>មុខតំណែង & ដេប៉ាតឺម៉ង់</th>
                        <th class="text-center" style="background: rgba(245, 158, 11, 0.05); border-left: 1px solid #f1f5f9;">
                            <span class="text-dark fw-bold">☀️ ព្រឹក ចូល</span><br>
                            <span class="badge bg-warning bg-opacity-25 text-dark font-monospace" style="font-size: 0.68rem;">{{ \Carbon\Carbon::parse($shiftSettings['staff_morning_in'] ?? '06:50')->format('h:i A') }}</span>
                        </th>
                        <th class="text-center" style="background: rgba(245, 158, 11, 0.05);">
                            <span class="text-dark fw-bold">☀️ ព្រឹក ចេញ</span><br>
                            <span class="badge bg-warning bg-opacity-25 text-dark font-monospace" style="font-size: 0.68rem;">{{ \Carbon\Carbon::parse($shiftSettings['staff_morning_out'] ?? '10:30')->format('h:i A') }}</span>
                        </th>
                        <th class="text-center" style="background: rgba(14, 165, 233, 0.05); border-left: 1px solid #f1f5f9;">
                            <span class="text-dark fw-bold">🌤️ រសៀល ចូល</span><br>
                            <span class="badge bg-info bg-opacity-25 text-dark font-monospace" style="font-size: 0.68rem;">{{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_in'] ?? '13:10')->format('h:i A') }}</span>
                        </th>
                        <th class="text-center" style="background: rgba(14, 165, 233, 0.05);">
                            <span class="text-dark fw-bold">🌤️ រសៀល ចេញ</span><br>
                            <span class="badge bg-info bg-opacity-25 text-dark font-monospace" style="font-size: 0.68rem;">{{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_out'] ?? '16:30')->format('h:i A') }}</span>
                        </th>
                        <th class="text-center">ស្ថានភាព</th>
                        <th>វិធីផ្ទៀងផ្ទាត់</th>
                        <th class="text-end pe-4">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $att)
                        @php
                            $emp = $att->employee;
                            $empName = $emp?->full_name ?? $emp?->user?->name ?? 'បុគ្គលិក';
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle overflow-hidden border shadow-xs d-flex align-items-center justify-content-center bg-light fw-bold text-secondary" style="width: 40px; height: 40px; flex-shrink: 0;">
                                        @if($att->snapshot_url)
                                            <img src="{{ $att->snapshot_url }}" class="w-100 h-100 object-fit-cover" title="រូបថតពេលស្កេនផ្ទាល់">
                                        @elseif($emp?->photo_url)
                                            <img src="{{ $emp->photo_url }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            {{ strtoupper(mb_substr($empName, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $empName }}</div>
                                        <div class="text-muted font-monospace" style="font-size: 0.72rem;">{{ $emp?->employee_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $emp?->employee_position?->name ?? '—' }}</div>
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ $emp?->employee_department?->name ?? '—' }}</div>
                            </td>
                            <td class="text-center" style="background: rgba(245, 158, 11, 0.02); border-left: 1px solid #f1f5f9;">
                                @if($att->in_time)
                                    <span class="badge bg-light text-dark border font-monospace px-2.5 py-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-box-arrow-in-right text-success me-1"></i>{{ \Carbon\Carbon::parse($att->in_time)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center" style="background: rgba(245, 158, 11, 0.02);">
                                @if($att->out_time)
                                    <span class="badge bg-light text-secondary border font-monospace px-2.5 py-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-box-arrow-right text-warning me-1"></i>{{ \Carbon\Carbon::parse($att->out_time)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center" style="background: rgba(14, 165, 233, 0.02); border-left: 1px solid #f1f5f9;">
                                @if($att->afternoon_in_time)
                                    <span class="badge bg-light text-dark border font-monospace px-2.5 py-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-box-arrow-in-right text-info me-1"></i>{{ \Carbon\Carbon::parse($att->afternoon_in_time)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center" style="background: rgba(14, 165, 233, 0.02);">
                                @if($att->afternoon_out_time)
                                    <span class="badge bg-light text-secondary border font-monospace px-2.5 py-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-box-arrow-right text-secondary me-1"></i>{{ \Carbon\Carbon::parse($att->afternoon_out_time)->format('h:i A') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $st = strtolower($att->status); @endphp
                                @if($st === 'present')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fw-bold">ទាន់ពេល (Present)</span>
                                @elseif($st === 'late')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5 fw-bold">យឺត (Late)</span>
                                @elseif($st === 'absent')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 fw-bold">អវត្តមាន (Absent)</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5 fw-bold">{{ ucfirst($att->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($att->verification_method === 'qr_face_verified')
                                    <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle d-inline-flex align-items-center gap-1 font-monospace" style="background:#ecfdf5;color:#065f46;border-color:#a7f3d0;" title="Score: {{ $att->verification_score }}%">
                                        <i class="bi bi-shield-fill-check text-success"></i> QR + Face ({{ round($att->verification_score) }}%)
                                    </span>
                                @elseif($att->verification_method === 'qr_card')
                                    <span class="badge bg-light text-secondary border font-monospace">
                                        <i class="bi bi-qr-code"></i> QR Card
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">ដោយដៃ</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('staff-attendance.show', $att->id) }}" class="btn btn-sm btn-outline-secondary py-0.5 px-2" title="ព័ត៌មានលម្អិត">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('staff-attendance.edit', $att->id) }}" class="btn btn-sm btn-outline-primary py-0.5 px-2" title="កែប្រែ">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 text-secondary d-block mb-2"></i>
                                មិនទាន់មានទិន្នន័យវត្តមានសម្រាប់លក្ខខណ្ឌដែលបានជ្រើសរើសឡើយ។
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($attendances) && $attendances->hasPages())
        <div class="card-footer bg-white p-3 border-top">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
