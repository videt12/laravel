{{-- TAB 4: SYSTEM SETTINGS --}}
<div class="tab-pane fade {{ $activeTab === 'system' ? 'show active' : '' }}" id="tab-system" role="tabpanel">

    <form id="systemSettingsForm" action="{{ route('settings.system.update') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- General & Academic Defaults --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-light border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-mortarboard me-2 text-warning"></i>ការកំណត់ការសិក្សាទូទៅ (Academic Defaults)
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ឈ្មោះស្ថាប័ន (School Name)</label>
                            <input type="text" name="settings[school_name]" class="form-control" value="{{ $settings['school_name'] ?? $school->name }}" placeholder="ឈ្មោះសាលា">
                            <div class="form-text small text-muted">ឈ្មោះនេះត្រូវបានប្រើលើរបាយការណ៍ និងវិក្កយបត្រផ្លូវការ។</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ឆ្នាំសិក្សាលំនាំដើម (Default Academic Year)</label>
                            <select name="settings[academic_year]" class="form-select">
                                <option value="">-- ជ្រើសរើសឆ្នាំសិក្សា --</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->name }}" {{ ($settings['academic_year'] ?? '') === $ay->name || ($ay->is_active && !isset($settings['academic_year'])) ? 'selected' : '' }}>
                                        {{ $ay->name }} {{ $ay->is_active ? '(សកម្ម)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">ពិន្ទុជាប់អប្បបរមា (%) (Passing %)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="settings[passing_percentage]" class="form-control" value="{{ $settings['passing_percentage'] ?? '50' }}" placeholder="50">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">កម្រិតវត្តមានអប្បបរមា (%) (Attendance %)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="settings[attendance_threshold]" class="form-control" value="{{ $settings['attendance_threshold'] ?? '75' }}" placeholder="75">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Currency & Finance Settings --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-light border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-cash-coin me-2 text-warning"></i>រូបិយប័ណ្ណ & ហិរញ្ញវត្ថុ (Currency & Finance)
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">រូបិយប័ណ្ណ (Currency Code)</label>
                                <input type="text" name="settings[currency]" class="form-control" value="{{ $settings['currency'] ?? 'USD' }}" placeholder="USD ឬ KHR">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">និមិត្តសញ្ញារូបិយប័ណ្ណ (Symbol)</label>
                                <input type="text" name="settings[currency_symbol]" class="form-control" value="{{ $settings['currency_symbol'] ?? '$' }}" placeholder="$ ឬ ៛">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">ប្រាក់ពិន័យយឺតយ៉ាវប្រចាំថ្ងៃ (Late Fee / Day)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">{{ $settings['currency_symbol'] ?? '$' }}</span>
                                <input type="number" step="0.01" name="settings[late_fine_per_day]" class="form-control" value="{{ $settings['late_fine_per_day'] ?? '0' }}" placeholder="0.00">
                            </div>
                            <div class="form-text small text-muted">អនុវត្តលើវិក្កយបត្រសិស្សដែលហួសកាលកំណត់បង់ប្រាក់។</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Library Settings --}}
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-light border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-book me-2 text-warning"></i>ការកំណត់បណ្ណាល័យ (Library Settings)
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-dark">ចំនួនសៀវភៅអតិបរមាអាចខ្ចីបាន (Max Books)</label>
                                <div class="input-group">
                                    <input type="number" name="settings[max_book_issue]" class="form-control" value="{{ $settings['max_book_issue'] ?? '3' }}" min="1">
                                    <span class="input-group-text bg-light">ក្បាល</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-dark">រយៈពេលខ្ចីអតិបរមា (Max Issue Days)</label>
                                <div class="input-group">
                                    <input type="number" name="settings[max_issue_days]" class="form-control" value="{{ $settings['max_issue_days'] ?? '14' }}" min="1">
                                    <span class="input-group-text bg-light">ថ្ងៃ</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-dark">ប្រាក់ពិន័យហួសថ្ងៃកំណត់ (Fine / Day)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">{{ $settings['currency_symbol'] ?? '$' }}</span>
                                    <input type="number" step="0.01" name="settings[library_fine_per_day]" class="form-control" value="{{ $settings['library_fine_per_day'] ?? '0' }}" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                                <i class="bi bi-save me-1"></i>រក្សាទុកការកំណត់
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Staff Attendance & Shift Schedule Settings --}}
            {{-- Staff Attendance & Shift Schedule Settings --}}
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-light border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-warning bg-opacity-20 text-warning d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 1.1rem;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">
                                    ម៉ោងកំណត់ការងារ & វត្តមានបុគ្គលិក (Staff Working Hours & Shift Settings)
                                </h6>
                                <small class="text-muted">កំណត់ម៉ោងចូល-ចេញផ្លូវការ និងរយៈពេលអនុគ្រោះយឺតសម្រាប់បុគ្គលិក</small>
                            </div>
                        </div>
                        <a href="{{ route('staff-attendance.index') }}" class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-pill px-3 shadow-xs">
                            <i class="bi bi-box-arrow-up-right me-1"></i>ទៅកាន់វត្តមានបុគ្គលិក
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            {{-- ☀️ វេនព្រឹក --}}
                            <div class="col-lg-6">
                                <div class="p-3.5 rounded-3 border border-warning-subtle bg-warning bg-opacity-10 h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="fw-bold text-dark d-flex align-items-center gap-1.5">
                                            <span class="fs-5">☀️</span> វេនព្រឹក (Morning Shift)
                                        </span>
                                        <span class="badge bg-warning text-dark font-monospace px-2.5 py-1 rounded-pill">
                                            {{ \Carbon\Carbon::parse($settings['staff_morning_in'] ?? '06:50')->format('h:i A') }} – {{ \Carbon\Carbon::parse($settings['staff_morning_out'] ?? '10:30')->format('h:i A') }}
                                        </span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small text-dark">ម៉ោងចូល (Official In)</label>
                                            <input type="time" name="settings[staff_morning_in]" class="form-control font-monospace bg-white" value="{{ $settings['staff_morning_in'] ?? '06:50' }}">
                                            <div class="form-text small text-muted">ស្កេនក្រោយម៉ោងនេះ = យឺត</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small text-dark">ម៉ោងចេញ (Official Out)</label>
                                            <input type="time" name="settings[staff_morning_out]" class="form-control font-monospace bg-white" value="{{ $settings['staff_morning_out'] ?? '10:30' }}">
                                            <div class="form-text small text-muted">ចេញសម្រាកបាយថ្ងៃត្រង់</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-dark">រយៈពេលអនុគ្រោះយឺត (Grace Period)</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="settings[staff_morning_grace]" class="form-control font-monospace bg-white" value="{{ $settings['staff_morning_grace'] ?? '0' }}" min="0" max="60">
                                                <span class="input-group-text bg-white text-muted">នាទី (Minutes)</span>
                                            </div>
                                            <div class="form-text small text-muted">ចំនួននាទីលើកលែងមុនពេលកត់ត្រាថា Late (ដាក់ 0 បើមិនលើកលែង)</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-success mb-1">
                                                <i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចូល (Scan-In Window)
                                            </label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ពី</span>
                                                        <input type="time" name="settings[staff_morning_scan_in_start]" class="form-control font-monospace bg-white" value="{{ $settings['staff_morning_scan_in_start'] ?? '06:00' }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ដល់</span>
                                                        <input type="time" name="settings[staff_morning_scan_in_end]" class="form-control font-monospace bg-white" value="{{ $settings['staff_morning_scan_in_end'] ?? '09:30' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-danger mb-1">
                                                <i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចេញ (Scan-Out Window)
                                            </label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ពី</span>
                                                        <input type="time" name="settings[staff_morning_scan_out_start]" class="form-control font-monospace bg-white" value="{{ $settings['staff_morning_scan_out_start'] ?? '10:00' }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ដល់</span>
                                                        <input type="time" name="settings[staff_morning_scan_out_end]" class="form-control font-monospace bg-white" value="{{ $settings['staff_morning_scan_out_end'] ?? '12:00' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 🌤️ វេនរសៀល --}}
                            <div class="col-lg-6">
                                <div class="p-3.5 rounded-3 border border-info-subtle bg-info bg-opacity-10 h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="fw-bold text-dark d-flex align-items-center gap-1.5">
                                            <span class="fs-5">🌤️</span> វេនរសៀល (Afternoon Shift)
                                        </span>
                                        <span class="badge bg-info text-dark font-monospace px-2.5 py-1 rounded-pill">
                                            {{ \Carbon\Carbon::parse($settings['staff_afternoon_in'] ?? '13:10')->format('h:i A') }} – {{ \Carbon\Carbon::parse($settings['staff_afternoon_out'] ?? '16:30')->format('h:i A') }}
                                        </span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small text-dark">ម៉ោងចូលវិញ (Official In)</label>
                                            <input type="time" name="settings[staff_afternoon_in]" class="form-control font-monospace bg-white" value="{{ $settings['staff_afternoon_in'] ?? '13:10' }}">
                                            <div class="form-text small text-muted">ស្កេនក្រោយម៉ោងនេះ = យឺត</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label fw-semibold small text-dark">ម៉ោងចេញ (Official Out)</label>
                                            <input type="time" name="settings[staff_afternoon_out]" class="form-control font-monospace bg-white" value="{{ $settings['staff_afternoon_out'] ?? '16:30' }}">
                                            <div class="form-text small text-muted">ចេញបញ្ចប់ការងារប្រចាំថ្ងៃ</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-dark">រយៈពេលអនុគ្រោះយឺត (Grace Period)</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="settings[staff_afternoon_grace]" class="form-control font-monospace bg-white" value="{{ $settings['staff_afternoon_grace'] ?? '0' }}" min="0" max="60">
                                                <span class="input-group-text bg-white text-muted">នាទី (Minutes)</span>
                                            </div>
                                            <div class="form-text small text-muted">ចំនួននាទីលើកលែងមុនពេលកត់ត្រាថា Late (ដាក់ 0 បើមិនលើកលែង)</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-success mb-1">
                                                <i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចូលវិញ (Scan-In Window)
                                            </label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ពី</span>
                                                        <input type="time" name="settings[staff_afternoon_scan_in_start]" class="form-control font-monospace bg-white" value="{{ $settings['staff_afternoon_scan_in_start'] ?? '12:30' }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ដល់</span>
                                                        <input type="time" name="settings[staff_afternoon_scan_in_end]" class="form-control font-monospace bg-white" value="{{ $settings['staff_afternoon_scan_in_end'] ?? '15:00' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-danger mb-1">
                                                <i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចេញ (Scan-Out Window)
                                            </label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ពី</span>
                                                        <input type="time" name="settings[staff_afternoon_scan_out_start]" class="form-control font-monospace bg-white" value="{{ $settings['staff_afternoon_scan_out_start'] ?? '16:00' }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text bg-white text-muted small">ដល់</span>
                                                        <input type="time" name="settings[staff_afternoon_scan_out_end]" class="form-control font-monospace bg-white" value="{{ $settings['staff_afternoon_scan_out_end'] ?? '18:30' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ⏱️ ម៉ោងបែងចែកវេន --}}
                            <div class="col-12">
                                <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                            <i class="bi bi-arrow-left-right text-primary"></i> ម៉ោងកំណត់បែងចែកវេនព្រឹក និងរសៀល (Shift Divider Cutoff)
                                        </span>
                                        <div class="text-muted small" style="font-size: 0.76rem;">ស្កេនមុនម៉ោងនេះប្រព័ន្ធចាត់ទុកជាវេនព្រឹក ហើយស្កេនចាប់ពីម៉ោងនេះតទៅចាត់ទុកជាវេនរសៀល។</div>
                                    </div>
                                    <div style="min-width: 170px;">
                                        <input type="time" name="settings[staff_shift_split]" class="form-control form-control-sm font-monospace bg-white" value="{{ $settings['staff_shift_split'] ?? '12:00' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                                <i class="bi bi-save me-1"></i>រក្សាទុកការកំណត់
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Telegram Bot Notification Settings --}}
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-light border-bottom py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-telegram me-2 text-primary"></i>ការកំណត់ Telegram Bot Notification (Telegram Service)
                        </h6>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                            <i class="bi bi-broadcast me-1"></i>សេវាជូនដំណឹងស្វ័យប្រវត្តិ
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="settings[telegram_bot_enabled]" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="telegramBotEnabled" name="settings[telegram_bot_enabled]" value="1" {{ ($settings['telegram_bot_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="telegramBotEnabled">
                                        បើកដំណើរការ Telegram Bot Notification
                                    </label>
                                    <div class="form-text small text-muted">បើកឱ្យប្រព័ន្ធផ្ញើសារដំណឹងទៅកាន់ Telegram Channel ឬ Group ដោយស្វ័យប្រវត្តិ។</div>
                                </div>

                                <div class="p-3 bg-light rounded-3 border mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="fw-bold text-dark mb-0 small">
                                            <i class="bi bi-clock-history text-primary me-1"></i>Bot ទី ១៖ សម្រាប់វត្តមាន & ទូទៅ (Attendance & General)
                                        </h6>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace small">មេ / Default</span>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold small text-dark mb-1">Telegram Bot Token</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white"><i class="bi bi-key-fill text-muted"></i></span>
                                            <input type="password" id="telegramBotToken" name="settings[telegram_bot_token]" class="form-control font-monospace" value="{{ $settings['telegram_bot_token'] ?? '' }}" placeholder="123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ" autocomplete="new-password" spellcheck="false" data-lpignore="true">
                                        </div>
                                        <div class="form-text small text-muted">Bot Token ផ្លូវការពី <code>@BotFather</code></div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold small text-dark mb-1">Telegram Chat ID / Channel ID</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white"><i class="bi bi-chat-dots-fill text-muted"></i></span>
                                            <input type="text" id="telegramChatId" name="settings[telegram_chat_id]" class="form-control font-monospace" value="{{ $settings['telegram_chat_id'] ?? '' }}" placeholder="5736802478 ឬ -1001234567890" autocomplete="off" spellcheck="false" data-lpignore="true">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center mt-2 pt-1">
                                        <button type="button" id="btnTestTelegram" class="btn btn-outline-primary btn-sm fw-semibold" style="font-size: 0.78rem;">
                                            <i class="bi bi-send-check me-1"></i>សាកល្បង Bot ទី ១ (Test Bot)
                                        </button>
                                        <span id="testTelegramStatus" class="small"></span>
                                    </div>
                                </div>

                                {{-- Dedicated Leave Telegram --}}
                                <div class="p-3 bg-light rounded-3 border border-info-subtle mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="fw-bold text-dark mb-0 small">
                                            <i class="bi bi-calendar2-check text-info me-1"></i>Bot ទី ២៖ សម្រាប់ច្បាប់ឈប់សម្រាកបុគ្គលិក (Staff Leaves & HR)
                                        </h6>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle font-monospace small">ដាច់ដោយឡែក</span>
                                    </div>
                                    <p class="text-muted small mb-2" style="font-size: 0.75rem;">
                                        កំណត់ Telegram Group ឬ Channel ដាច់ដោយឡែកសម្រាប់ទទួលដំណឹងសុំច្បាប់ និងអនុម័តច្បាប់ (បើទុកទទេ នឹងប្រើតាម Bot ទី ១ ស្វ័យប្រវត្តិ)។
                                    </p>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold small text-dark mb-1">Telegram Leaves Chat ID / Channel ID</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white"><i class="bi bi-chat-dots text-info"></i></span>
                                            <input type="text" id="telegramLeaveChatId" name="settings[telegram_leave_chat_id]" class="form-control font-monospace" value="{{ $settings['telegram_leave_chat_id'] ?? '' }}" placeholder="Chat ID នៃ Group ឬ Chat សម្រាប់ច្បាប់..." autocomplete="off" spellcheck="false" data-lpignore="true">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label fw-semibold small text-dark mb-1">Telegram Leaves Bot Token <span class="text-muted fw-normal">(ជម្រើស - ទុកទទេបើប្រើ Bot ទី ១)</span></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white"><i class="bi bi-key text-info"></i></span>
                                            <input type="password" id="telegramLeaveBotToken" name="settings[telegram_leave_bot_token]" class="form-control font-monospace" value="{{ $settings['telegram_leave_bot_token'] ?? '' }}" placeholder="ទុកទទេ ប្រសិនបើប្រើ Bot ដដែល" autocomplete="new-password" spellcheck="false" data-lpignore="true">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center mt-2 pt-1">
                                        <button type="button" id="btnTestLeaveTelegram" class="btn btn-outline-info btn-sm fw-semibold" style="font-size: 0.78rem;">
                                            <i class="bi bi-send-check me-1"></i>សាកល្បង Bot ច្បាប់សម្រាក (Test Leave Bot)
                                        </button>
                                        <span id="testLeaveTelegramStatus" class="small"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 border-start ps-md-4">
                                <h6 class="fw-bold small text-secondary mb-3">ប្រភេទនៃការជូនដំណឹងស្វ័យប្រវត្តិ (Notification Triggers)</h6>

                                <div class="form-check form-switch mb-3 p-2 rounded bg-light border border-warning-subtle">
                                    <input type="hidden" name="settings[telegram_notify_staff_attendance]" value="0">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="notifyStaffAttendance" name="settings[telegram_notify_staff_attendance]" value="1" {{ ($settings['telegram_notify_staff_attendance'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark small" for="notifyStaffAttendance">
                                        🔔 វត្តមានបុគ្គលិកចេញ-ចូល (Staff Check-In / Check-Out)
                                    </label>
                                    <div class="form-text small text-muted ms-4 mt-1">ផ្ញើដំណឹងភ្លាមៗទៅ Telegram ពេលបុគ្គលិក Scan QR ឬកត់ត្រាវត្តមាន ចូលបម្រើការងារ (Check In) ឬ ចេញពីការងារ (Check Out)។</div>
                                </div>

                                <div class="form-check form-switch mb-3 p-2 rounded bg-light border border-info-subtle">
                                    <input type="hidden" name="settings[telegram_notify_staff_leave]" value="0">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="notifyStaffLeave" name="settings[telegram_notify_staff_leave]" value="1" {{ ($settings['telegram_notify_staff_leave'] ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark small" for="notifyStaffLeave">
                                        📝 ច្បាប់ឈប់សម្រាកបុគ្គលិក (Staff Leaves & Approvals)
                                    </label>
                                    <div class="form-text small text-muted ms-4 mt-1">ផ្ញើដំណឹងភ្លាមៗទៅ Telegram ពេលមានពាក្យស្នើសុំច្បាប់ថ្មី ឬពេលច្បាប់ត្រូវបានអនុម័ត/បដិសេធ។</div>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="settings[telegram_notify_attendance]" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="notifyAttendance" name="settings[telegram_notify_attendance]" value="1" {{ ($settings['telegram_notify_attendance'] ?? '') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-dark small" for="notifyAttendance">
                                        របាយការណ៍វត្តមានសិស្សប្រចាំថ្ងៃ (Daily Student Attendance)
                                    </label>
                                    <div class="form-text small text-muted">ផ្ញើស្ថិតិវត្តមានសិស្សតាមថ្នាក់ (វត្តមាន, អវត្តមាន, យឺត, ច្បាប់) ពេលគ្រូកត់វត្តមានរួចរាល់។</div>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input type="hidden" name="settings[telegram_notify_payment]" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="notifyPayment" name="settings[telegram_notify_payment]" value="1" {{ ($settings['telegram_notify_payment'] ?? '') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-dark small" for="notifyPayment">
                                        ដំណឹងបង្កាន់ដៃបង់ប្រាក់ថ្លៃសិក្សា (Tuition Payment Alert)
                                    </label>
                                    <div class="form-text small text-muted">ផ្ញើដំណឹងភ្លាមៗពេលមានការបង់ប្រាក់ថ្លៃសិក្សា (ឈ្មោះសិស្ស, ចំនួនប្រាក់, និងសមតុល្យនៅសល់)។</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-warning fw-bold px-4 shadow-sm">
                                <i class="bi bi-save me-1"></i>រក្សាទុកការកំណត់ Telegram
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Telegram Instant Broadcast Card --}}
    <div class="card border-0 shadow-sm rounded-3 mt-4">
        <div class="card-header bg-light border-bottom py-3 px-4">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-megaphone-fill me-2 text-warning"></i>ផ្សាយដំណឹងបន្ទាន់ទៅកាន់ Telegram Channel (Instant Broadcast)
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('settings.telegram.broadcast') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold small text-dark">ចំណងជើងដំណឹង (Announcement Title)</label>
                        <input type="text" name="title" class="form-control" placeholder="ឧ. ការឈប់សម្រាកបុណ្យភ្ជុំបិណ្ឌ" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold small text-dark">ខ្លឹមសារដំណឹង (Message Content)</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="សូមគោរពជម្រាបជូនលោកគ្រូ អ្នកគ្រូ និងមាតាបិតាសិស្សទាំងអស់មេត្តាជ្រាបថា..." required></textarea>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold shadow-xs">
                            <i class="bi bi-broadcast me-1"></i>ផ្សាយដំណឹងឥឡូវនេះ (Broadcast Now)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
