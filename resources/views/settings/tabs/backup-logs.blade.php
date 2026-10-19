{{-- TAB 5: BACKUP & AUDIT LOGS --}}
<div class="tab-pane fade {{ $activeTab === 'backup-logs' ? 'show active' : '' }}" id="tab-backup-logs" role="tabpanel">

    {{-- ═══ 1. DATABASE TELEMETRY CARDS ═══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-xs rounded-3 bg-white p-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted small fw-medium">មូលទិន្នន័យ (Database)</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-database-fill"></i></span>
                </div>
                <h4 class="fw-bold mb-0 text-dark font-monospace" style="font-size: 1.1rem;">{{ $databaseStats['database_name'] ?? 'Database' }}</h4>
                <span class="text-muted" style="font-size:.75rem">{{ $databaseStats['driver'] ?? 'MySQL' }} (v{{ substr($databaseStats['server_version'] ?? '', 0, 10) }})</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-xs rounded-3 bg-white p-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted small fw-medium">តារាងសរុប (Total Tables)</span>
                    <span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-table"></i></span>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $databaseStats['total_tables'] ?? 0 }}</h3>
                <span class="text-muted" style="font-size:.75rem">Tables ក្នុង Schema ទាំងមូល</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-xs rounded-3 bg-white p-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted small fw-medium">ទំហំមូលទិន្នន័យ (Data Size)</span>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="bi bi-hdd-stack-fill"></i></span>
                </div>
                <h3 class="fw-bold mb-0 text-warning-emphasis">{{ $databaseStats['size_mb'] ?? 0 }} MB</h3>
                <span class="text-muted" style="font-size:.75rem">ទំហំទិន្នន័យ & Indexes បច្ចុប្បន្ន</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-xs rounded-3 bg-white p-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted small fw-medium">ឯកសារបម្រុងទុក (Backups)</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-shield-check"></i></span>
                </div>
                <h3 class="fw-bold mb-0 text-success">{{ count($backups) }}</h3>
                <span class="text-muted" style="font-size:.75rem">ឯកសារបម្រុងទុកសរុប</span>
            </div>
        </div>
    </div>

    {{-- ═══ 2. DATABASE BACKUP MANAGER ═══ --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-light border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-cloud-arrow-down-fill me-2 text-warning"></i>ការបម្រុងទុកមូលទិន្នន័យ (Database Backup)
                </h6>
                <div class="text-muted small">ជ្រើសរើសប្រភេទឯកសារ និងបង្កើតការបម្រុងទុកទិន្នន័យនៃប្រព័ន្ធ</div>
            </div>
            <form action="{{ route('settings.backup.store') }}" method="POST" class="d-flex align-items-center gap-2 flex-wrap" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
                @csrf
                <div class="input-group input-group-sm" style="width: auto;">
                    <label class="input-group-text bg-white border-secondary-subtle text-secondary fw-semibold" for="backupFormatSelect">
                        <i class="bi bi-file-earmark-code me-1 text-primary"></i>ប្រភេទឯកសារ
                    </label>
                    <select name="format" id="backupFormatSelect" class="form-select form-select-sm fw-medium border-secondary-subtle shadow-none" style="min-width: 170px;">
                        <option value="sql" selected>SQL (.sql - Full Dump)</option>
                        <option value="zip">ZIP (.zip - Compressed SQL)</option>
                        <option value="json">JSON (.json - Structured Data)</option>
                        <option value="csv">CSV (.zip - Multi-table CSVs)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-warning fw-bold btn-sm shadow-xs px-3 text-nowrap d-inline-flex align-items-center gap-1.5" style="height: 33px;">
                    <i class="bi bi-cloud-arrow-down-fill"></i>
                    <span>បង្កើត Backup ឥឡូវនេះ</span>
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            @if(empty($backups))
                <div class="text-center py-5 px-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-archive text-muted fs-3"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">មិនទាន់មានឯកសារបម្រុងទុកនៅឡើយទេ</h6>
                    <p class="text-muted small mb-3">សូមជ្រើសរើសប្រភេទឯកសារ និងចុចប៊ូតុង "បង្កើត Backup ឥឡូវនេះ" ខាងលើដើម្បីបង្កើតការបម្រុងទុកសុវត្ថិភាពដំបូងរបស់អ្នក។</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 40px;">#</th>
                                <th>ឈ្មោះឯកសារ (File Name)</th>
                                <th>ប្រភេទ (Format)</th>
                                <th>ទំហំ (File Size)</th>
                                <th>កាលបរិច្ឆេទបង្កើត (Created Date)</th>
                                <th class="text-end pe-4">សកម្មភាព (Actions)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $index => $b)
                                @php
                                    $ext = strtolower($b['format_type'] ?? $b['extension'] ?? pathinfo($b['filename'], PATHINFO_EXTENSION));
                                    $fileExt = strtolower($b['extension'] ?? pathinfo($b['filename'], PATHINFO_EXTENSION));
                                    $iconClass = match($ext) {
                                        'zip' => 'bi-file-earmark-zip-fill text-warning',
                                        'json' => 'bi-filetype-json text-info',
                                        'csv' => 'bi-filetype-csv text-success',
                                        default => 'bi-filetype-sql text-primary',
                                    };
                                    $badgeClass = match($ext) {
                                        'zip' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                        'json' => 'bg-info-subtle text-info border border-info-subtle',
                                        'csv' => 'bg-success-subtle text-success border border-success-subtle',
                                        default => 'bg-primary-subtle text-primary border border-primary-subtle',
                                    };
                                    $formatTitle = match($ext) {
                                        'zip' => 'ZIP Archive',
                                        'json' => 'JSON Data',
                                        'csv' => 'CSV Archive',
                                        default => 'SQL Dump',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-2 bg-light border p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                                <i class="bi {{ $iconClass }} fs-4"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold text-dark font-monospace" style="font-size: 0.88rem;">{{ $b['filename'] }}</span>
                                                <div class="text-muted small">{{ $b['created_at_human'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $badgeClass }} text-uppercase font-monospace px-2 py-1" style="font-size: 0.72rem;">
                                            {{ $ext }} ({{ $formatTitle }})
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2.5 py-1 font-monospace">
                                            {{ $b['size_formatted'] }}
                                        </span>
                                    </td>
                                    <td class="small text-secondary">
                                        {{ $b['created_at']->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('settings.backup.download', $b['filename']) }}" class="btn btn-sm btn-outline-primary" title="ទាញយកឯកសារ">
                                                <i class="bi bi-download me-1"></i>ទាញយក
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="លុបឯកសារបម្រុងទុក"
                                                    onclick="confirmDeleteBackup('{{ route('settings.backup.destroy', $b['filename']) }}', '{{ $b['filename'] }}', '{{ $b['size_formatted'] }}', '{{ $ext }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══ 3. SYSTEM SECURITY & LOGIN AUDIT LOGS ═══ --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-light border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-shield-shaded me-2 text-primary"></i>កំណត់ត្រាសកម្មភាពចូលប្រើប្រព័ន្ធ (System Login Audit Trail)
                </h6>
                <div class="text-muted small">ត្រួតពិនិត្យប្រវត្តិនៃការ Login របស់បុគ្គលិក និងអ្នកប្រើប្រាស់ ដើម្បីធានាសុវត្ថិភាពទិន្នន័យ</div>
            </div>
            <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1">
                <i class="bi bi-lock me-1"></i>Security Audit
            </span>
        </div>
        <div class="card-body p-0">
            @if($loginActivities->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-shield-check text-muted fs-1 mb-2 d-block"></i>
                    <h6 class="text-muted fw-bold">មិនទាន់មានកំណត់ត្រា Login នៅឡើយទេ</h6>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">អ្នកប្រើប្រាស់ (User)</th>
                                <th>តួនាទី (Role)</th>
                                <th>អាសយដ្ឋាន IP (IP Address)</th>
                                <th>ឧបករណ៍ / Browser (Device & User Agent)</th>
                                <th class="text-end pe-4">កាលបរិច្ឆេទ & ម៉ោង (Timestamp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loginActivities as $log)
                                @php
                                    $user = $log->user;
                                    $roleName = $user?->roles->first()?->name ?? 'User';
                                    $agent = $log->user_agent ?? 'Unknown';
                                    $device = match (true) {
                                        str_contains($agent, 'Windows') => 'Windows PC',
                                        str_contains($agent, 'Macintosh') || str_contains($agent, 'Mac OS') => 'Mac OS',
                                        str_contains($agent, 'iPhone') || str_contains($agent, 'iPad') => 'iOS Device',
                                        str_contains($agent, 'Android') => 'Android Phone',
                                        str_contains($agent, 'Linux') => 'Linux',
                                        default => 'Desktop / Mobile'
                                    };
                                    $browser = match (true) {
                                        str_contains($agent, 'Edg') => 'Edge',
                                        str_contains($agent, 'Chrome') => 'Chrome',
                                        str_contains($agent, 'Safari') && !str_contains($agent, 'Chrome') => 'Safari',
                                        str_contains($agent, 'Firefox') => 'Firefox',
                                        default => 'Web Browser'
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">{{ $user?->name ?? 'User #' . $log->user_id }}</div>
                                        <div class="text-muted small">{{ $user?->email ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2.5 py-1">
                                            {{ $roleName }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-monospace small text-primary fw-medium">{{ $log->ip_address ?: '127.0.0.1' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1.5">
                                            <i class="bi bi-laptop text-muted"></i>
                                            <span class="small text-dark fw-medium">{{ $device }} ({{ $browser }})</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4 small text-secondary">
                                        <i class="bi bi-clock me-1 text-muted"></i>
                                        {{ $log->login_time ? \Carbon\Carbon::parse($log->login_time)->format('d M Y, h:i:s A') : ($log->created_at ? $log->created_at->format('d M Y, h:i:s A') : '—') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($loginActivities->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $loginActivities->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- ═══ MODAL: CONFIRM DELETE BACKUP ═══ --}}
    <div class="modal fade" id="deleteBackupModal" tabindex="-1" aria-labelledby="deleteBackupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="modal-body p-4 text-center">
                    {{-- Warning Icon Badge --}}
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger mb-3" style="width: 58px; height: 58px;">
                        <i class="bi bi-trash3-fill fs-3"></i>
                    </div>

                    {{-- Title & Subtitle --}}
                    <h6 class="fw-bold text-dark mb-1" id="deleteBackupModalLabel" style="font-size: 1.1rem;">
                        លុបឯកសារបម្រុងទុក?
                    </h6>
                    <p class="text-muted small mb-3">
                        តើអ្នកប្រាកដជាចង់លុបឯកសារបម្រុងទុកនេះមែនទេ? សកម្មភាពនេះមិនអាចត្រឡប់វិញបានឡើយ។
                    </p>

                    {{-- File Info Card --}}
                    <div class="p-2.5 mb-3 rounded-2 bg-light border text-start d-flex align-items-center gap-2.5">
                        <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center text-danger flex-shrink-0 shadow-2xs" style="width: 40px; height: 40px;">
                            <i class="bi bi-file-earmark-code fs-4" id="deleteBackupIcon"></i>
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <div class="fw-bold text-dark font-monospace small text-truncate" id="deleteBackupFilename">backup.sql</div>
                            <div class="text-muted" style="font-size: 0.75rem;">ទំហំ៖ <span id="deleteBackupSize" class="fw-semibold text-dark">0 KB</span></div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="height: 34px; font-size: 0.82rem;">
                            បោះបង់
                        </button>
                        <form id="deleteBackupForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger px-3.5 d-inline-flex align-items-center gap-1.5" style="height: 34px; font-size: 0.82rem;">
                                <i class="bi bi-trash3"></i>
                                <span>លុបឯកសារ</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteBackup(actionUrl, filename, size, ext) {
            document.getElementById('deleteBackupForm').action = actionUrl;
            document.getElementById('deleteBackupFilename').textContent = filename;
            document.getElementById('deleteBackupSize').textContent = size;

            const iconEl = document.getElementById('deleteBackupIcon');
            if (iconEl) {
                const iconMap = {
                    'zip': 'bi-file-earmark-zip-fill text-warning',
                    'json': 'bi-filetype-json text-info',
                    'csv': 'bi-filetype-csv text-success',
                    'sql': 'bi-filetype-sql text-primary'
                };
                const cleanExt = (ext || filename.split('.').pop() || 'sql').toLowerCase();
                iconEl.className = 'bi ' + (iconMap[cleanExt] || 'bi-file-earmark-code text-danger') + ' fs-4';
            }

            new bootstrap.Modal(document.getElementById('deleteBackupModal')).show();
        }
    </script>
</div>
