<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white p-3 p-md-4 border-bottom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-circle bg-warning bg-opacity-20 text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.35rem;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">
                        កំណត់ពេលវេលាចូល និងចេញធ្វើការបុគ្គលិក (Staff Shift & Working Hours)
                    </h5>
                    <p class="text-muted small mb-0">
                        កំណត់ម៉ោងផ្លូវការសម្រាប់ចូល-ចេញធ្វើការ និងរយៈពេលអនុគ្រោះយឺតសម្រាប់វេននីមួយៗ
                    </p>
                </div>
            </div>

            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill font-monospace" style="font-size: 0.78rem;">
                <i class="bi bi-shield-check text-success me-1"></i> ប្រព័ន្ធស្វ័យប្រវត្តិកំណត់វត្តមាន
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-0 m-0 py-2.5 px-4 d-flex align-items-center gap-2 shadow-xs">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span class="fw-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card-body p-3 p-md-4 bg-light bg-opacity-30">
        <form action="{{ route('staff-attendance.shift-settings.update') }}" method="POST" id="shiftSettingsForm">
            @csrf

            <div class="row g-4">
                {{-- ═══════════════════════════════════════════════
                     ☀️ វេនព្រឹក (MORNING SHIFT)
                ═══════════════════════════════════════════════ --}}
                <div class="col-lg-6">
                    <div class="card border border-warning-subtle shadow-xs rounded-4 bg-white h-100 overflow-hidden">
                        <div class="card-header bg-warning bg-opacity-10 py-2.5 px-3 border-bottom border-warning-subtle d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="fs-5">☀️</span> វេនព្រឹក (Morning Shift)
                            </span>
                            <span class="badge bg-warning text-dark font-monospace px-2 py-0.5 rounded-pill" style="font-size: 0.72rem;">
                                ពេលព្រឹក
                            </span>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            {{-- Check In Official Time --}}
                            <div class="row g-2 mb-3">
                                <div class="col-sm-7">
                                    <label class="form-label fw-bold text-dark small d-flex align-items-center justify-content-between mb-1">
                                        <span><i class="bi bi-box-arrow-in-right text-success me-1"></i> ម៉ោងកំណត់ចូលផ្លូវការ <span class="text-danger">*</span></span>
                                        <span class="badge bg-light text-secondary border font-monospace" id="previewMorningIn">{{ \Carbon\Carbon::parse($shiftSettings['staff_morning_in'])->format('h:i A') }}</span>
                                    </label>
                                    <input type="time" 
                                           name="staff_morning_in" 
                                           id="inputMorningIn"
                                           class="form-control form-control-lg font-monospace rounded-3 border-secondary-subtle" 
                                           value="{{ $shiftSettings['staff_morning_in'] }}" 
                                           required
                                           onchange="updateTimePreview('inputMorningIn', 'previewMorningIn')">
                                    <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                        ក្រោយម៉ោងនេះ = យឺត (Late)
                                    </small>
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        <i class="bi bi-hourglass-split text-warning me-1"></i> លើកលែង (Grace)
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" 
                                               name="staff_morning_grace" 
                                               class="form-control font-monospace rounded-start-3" 
                                               value="{{ $shiftSettings['staff_morning_grace'] }}" 
                                               min="0" 
                                               max="120" 
                                               placeholder="0">
                                        <span class="input-group-text bg-light text-muted font-monospace small px-2">នាទី</span>
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                        អនុគ្រោះយឺត
                                    </small>
                                </div>
                            </div>

                            {{-- Morning Scan-In Window --}}
                            <div class="p-2.5 rounded-3 border border-success border-opacity-25 bg-success bg-opacity-10 mb-3">
                                <label class="form-label fw-bold text-success-emphasis small d-flex align-items-center justify-content-between mb-1.5">
                                    <span><i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចូល (Check-In Window) <span class="text-danger">*</span></span>
                                    <span class="badge bg-white text-success border border-success-subtle font-monospace" id="previewMorningScanIn">
                                        {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_in_start'])->format('h:i A') }} – {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_in_end'])->format('h:i A') }}
                                    </span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ចាប់ពី</span>
                                            <input type="time" 
                                                   name="staff_morning_scan_in_start" 
                                                   id="inputMorningScanInStart" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_morning_scan_in_start'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputMorningScanInStart', 'inputMorningScanInEnd', 'previewMorningScanIn')">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ដល់</span>
                                            <input type="time" 
                                                   name="staff_morning_scan_in_end" 
                                                   id="inputMorningScanInEnd" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_morning_scan_in_end'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputMorningScanInStart', 'inputMorningScanInEnd', 'previewMorningScanIn')">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1.5" style="font-size: 0.72rem;">
                                    <i class="bi bi-info-circle me-1"></i> ការស្កេនក្នុងចន្លោះម៉ោងនេះ ប្រព័ន្ធនឹងកត់ត្រាជា «Check In ចូលធ្វើការ» (ស្កេនលើសពី ១ ដង ប្រព័ន្ធនឹងការពារមិនឱ្យស្ទួន)។
                                </small>
                            </div>

                            <hr class="my-3 text-secondary opacity-25">

                            {{-- Check Out Official Time --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small d-flex align-items-center justify-content-between mb-1">
                                    <span><i class="bi bi-box-arrow-right text-danger me-1"></i> ម៉ោងកំណត់ចេញផ្លូវការ (Official Check Out) <span class="text-danger">*</span></span>
                                    <span class="badge bg-light text-secondary border font-monospace" id="previewMorningOut">{{ \Carbon\Carbon::parse($shiftSettings['staff_morning_out'])->format('h:i A') }}</span>
                                </label>
                                <input type="time" 
                                       name="staff_morning_out" 
                                       id="inputMorningOut"
                                       class="form-control form-control-lg font-monospace rounded-3 border-secondary-subtle" 
                                       value="{{ $shiftSettings['staff_morning_out'] }}" 
                                       required
                                       onchange="updateTimePreview('inputMorningOut', 'previewMorningOut')">
                                <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                    ម៉ោងបញ្ចប់វេនព្រឹក សម្រាប់ចេញសម្រាកបាយថ្ងៃត្រង់
                                </small>
                            </div>

                            {{-- Morning Scan-Out Window --}}
                            <div class="p-2.5 rounded-3 border border-danger border-opacity-25 bg-danger bg-opacity-10 mb-0">
                                <label class="form-label fw-bold text-danger-emphasis small d-flex align-items-center justify-content-between mb-1.5">
                                    <span><i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចេញ (Check-Out Window) <span class="text-danger">*</span></span>
                                    <span class="badge bg-white text-danger border border-danger-subtle font-monospace" id="previewMorningScanOut">
                                        {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_out_start'])->format('h:i A') }} – {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_out_end'])->format('h:i A') }}
                                    </span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ចាប់ពី</span>
                                            <input type="time" 
                                                   name="staff_morning_scan_out_start" 
                                                   id="inputMorningScanOutStart" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_morning_scan_out_start'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputMorningScanOutStart', 'inputMorningScanOutEnd', 'previewMorningScanOut')">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ដល់</span>
                                            <input type="time" 
                                                   name="staff_morning_scan_out_end" 
                                                   id="inputMorningScanOutEnd" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_morning_scan_out_end'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputMorningScanOutStart', 'inputMorningScanOutEnd', 'previewMorningScanOut')">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1.5" style="font-size: 0.72rem;">
                                    <i class="bi bi-info-circle me-1"></i> ការស្កេនក្នុងចន្លោះម៉ោងនេះ ប្រព័ន្ធនឹងកត់ត្រាជា «Check Out ចេញសម្រាក»។ បើមិនទាន់ដល់ម៉ោងនេះ ប្រព័ន្ធមិនអនុញ្ញាតឱ្យស្កេនចេញឡើយ។
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════
                     🌤️ វេនរសៀល (AFTERNOON SHIFT)
                ═══════════════════════════════════════════════ --}}
                <div class="col-lg-6">
                    <div class="card border border-info-subtle shadow-xs rounded-4 bg-white h-100 overflow-hidden">
                        <div class="card-header bg-info bg-opacity-10 py-2.5 px-3 border-bottom border-info-subtle d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="fs-5">🌤️</span> វេនរសៀល (Afternoon Shift)
                            </span>
                            <span class="badge bg-info text-dark font-monospace px-2 py-0.5 rounded-pill" style="font-size: 0.72rem;">
                                ពេលរសៀល
                            </span>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            {{-- Afternoon Check In Official Time --}}
                            <div class="row g-2 mb-3">
                                <div class="col-sm-7">
                                    <label class="form-label fw-bold text-dark small d-flex align-items-center justify-content-between mb-1">
                                        <span><i class="bi bi-box-arrow-in-right text-success me-1"></i> ម៉ោងកំណត់ចូលវិញផ្លូវការ <span class="text-danger">*</span></span>
                                        <span class="badge bg-light text-secondary border font-monospace" id="previewAfternoonIn">{{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_in'])->format('h:i A') }}</span>
                                    </label>
                                    <input type="time" 
                                           name="staff_afternoon_in" 
                                           id="inputAfternoonIn"
                                           class="form-control form-control-lg font-monospace rounded-3 border-secondary-subtle" 
                                           value="{{ $shiftSettings['staff_afternoon_in'] }}" 
                                           required
                                           onchange="updateTimePreview('inputAfternoonIn', 'previewAfternoonIn')">
                                    <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                        ក្រោយម៉ោងនេះ = យឺត (Late)
                                    </small>
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        <i class="bi bi-hourglass-split text-info me-1"></i> លើកលែង (Grace)
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" 
                                               name="staff_afternoon_grace" 
                                               class="form-control font-monospace rounded-start-3" 
                                               value="{{ $shiftSettings['staff_afternoon_grace'] }}" 
                                               min="0" 
                                               max="120" 
                                               placeholder="0">
                                        <span class="input-group-text bg-light text-muted font-monospace small px-2">នាទី</span>
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                        អនុគ្រោះយឺត
                                    </small>
                                </div>
                            </div>

                            {{-- Afternoon Scan-In Window --}}
                            <div class="p-2.5 rounded-3 border border-success border-opacity-25 bg-success bg-opacity-10 mb-3">
                                <label class="form-label fw-bold text-success-emphasis small d-flex align-items-center justify-content-between mb-1.5">
                                    <span><i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចូលវិញ (Check-In Window) <span class="text-danger">*</span></span>
                                    <span class="badge bg-white text-success border border-success-subtle font-monospace" id="previewAfternoonScanIn">
                                        {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_in_start'])->format('h:i A') }} – {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_in_end'])->format('h:i A') }}
                                    </span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ចាប់ពី</span>
                                            <input type="time" 
                                                   name="staff_afternoon_scan_in_start" 
                                                   id="inputAfternoonScanInStart" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_afternoon_scan_in_start'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputAfternoonScanInStart', 'inputAfternoonScanInEnd', 'previewAfternoonScanIn')">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ដល់</span>
                                            <input type="time" 
                                                   name="staff_afternoon_scan_in_end" 
                                                   id="inputAfternoonScanInEnd" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_afternoon_scan_in_end'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputAfternoonScanInStart', 'inputAfternoonScanInEnd', 'previewAfternoonScanIn')">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1.5" style="font-size: 0.72rem;">
                                    <i class="bi bi-info-circle me-1"></i> ការស្កេនក្នុងចន្លោះម៉ោងនេះ ប្រព័ន្ធនឹងកត់ត្រាជា «Check In ចូលវិញវេនរសៀល» (ការពារការស្កេនចូលស្ទួន)។
                                </small>
                            </div>

                            <hr class="my-3 text-secondary opacity-25">

                            {{-- Afternoon Check Out Official Time --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small d-flex align-items-center justify-content-between mb-1">
                                    <span><i class="bi bi-box-arrow-right text-danger me-1"></i> ម៉ោងកំណត់ចេញផ្លូវការ (Official Check Out) <span class="text-danger">*</span></span>
                                    <span class="badge bg-light text-secondary border font-monospace" id="previewAfternoonOut">{{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_out'])->format('h:i A') }}</span>
                                </label>
                                <input type="time" 
                                       name="staff_afternoon_out" 
                                       id="inputAfternoonOut"
                                       class="form-control form-control-lg font-monospace rounded-3 border-secondary-subtle" 
                                       value="{{ $shiftSettings['staff_afternoon_out'] }}" 
                                       required
                                       onchange="updateTimePreview('inputAfternoonOut', 'previewAfternoonOut')">
                                <small class="text-muted d-block mt-1" style="font-size: 0.73rem;">
                                    ម៉ោងបញ្ចប់ការងារប្រចាំថ្ងៃ សម្រាប់ចេញត្រឡប់ទៅផ្ទះ
                                </small>
                            </div>

                            {{-- Afternoon Scan-Out Window --}}
                            <div class="p-2.5 rounded-3 border border-danger border-opacity-25 bg-danger bg-opacity-10 mb-0">
                                <label class="form-label fw-bold text-danger-emphasis small d-flex align-items-center justify-content-between mb-1.5">
                                    <span><i class="bi bi-clock-history me-1"></i> ចន្លោះម៉ោងអនុញ្ញាតស្កេនចេញ (Check-Out Window) <span class="text-danger">*</span></span>
                                    <span class="badge bg-white text-danger border border-danger-subtle font-monospace" id="previewAfternoonScanOut">
                                        {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_out_start'])->format('h:i A') }} – {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_out_end'])->format('h:i A') }}
                                    </span>
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ចាប់ពី</span>
                                            <input type="time" 
                                                   name="staff_afternoon_scan_out_start" 
                                                   id="inputAfternoonScanOutStart" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_afternoon_scan_out_start'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputAfternoonScanOutStart', 'inputAfternoonScanOutEnd', 'previewAfternoonScanOut')">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white small text-muted">ដល់</span>
                                            <input type="time" 
                                                   name="staff_afternoon_scan_out_end" 
                                                   id="inputAfternoonScanOutEnd" 
                                                   class="form-control font-monospace bg-white" 
                                                   value="{{ $shiftSettings['staff_afternoon_scan_out_end'] }}" 
                                                   required
                                                   onchange="updateRangePreview('inputAfternoonScanOutStart', 'inputAfternoonScanOutEnd', 'previewAfternoonScanOut')">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1.5" style="font-size: 0.72rem;">
                                    <i class="bi bi-info-circle me-1"></i> ការស្កេនក្នុងចន្លោះម៉ោងនេះ ប្រព័ន្ធនឹងកត់ត្រាជា «Check Out បញ្ចប់ការងារ»។ មិនអនុញ្ញាតឱ្យស្កេនចេញមុនម៉ោងកំណត់ឡើយ។
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════
                     ⏱️ ម៉ោងបែងចែកវេន (SHIFT DIVIDER)
                ═══════════════════════════════════════════════ --}}
                <div class="col-12">
                    <div class="card border shadow-xs rounded-4 bg-white overflow-hidden">
                        <div class="card-body p-3 p-md-4">
                            <div class="row align-items-center g-3">
                                <div class="col-md-7">
                                    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="bi bi-arrow-left-right text-primary"></i> ម៉ោងកំណត់បែងចែកវេនព្រឹក និងវេនរសៀល (Shift Divider Cutoff)
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        ការស្កេនមុនម៉ោងនេះ ប្រព័ន្ធនឹងចាត់ទុកជា <strong>វេនព្រឹក</strong> ហើយការស្កេនចាប់ពីម៉ោងនេះតទៅ នឹងចាត់ទុកជា <strong>វេនរសៀល</strong>។
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="time" 
                                               name="staff_shift_split" 
                                               id="inputShiftSplit"
                                               class="form-control form-control-lg font-monospace rounded-3 border-secondary-subtle" 
                                               value="{{ $shiftSettings['staff_shift_split'] }}" 
                                               required
                                               onchange="updateTimePreview('inputShiftSplit', 'previewShiftSplit')">
                                        <span class="badge bg-dark text-white px-2.5 py-2 font-monospace" id="previewShiftSplit" style="font-size: 0.85rem;">
                                            {{ \Carbon\Carbon::parse($shiftSettings['staff_shift_split'])->format('h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══════════════════════════════════════════════
                     📊 VISUAL SCHEDULE PREVIEW TIMELINE
                ═══════════════════════════════════════════════ --}}
                <div class="col-12">
                    <div class="p-3.5 rounded-4 border bg-white shadow-xs">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                <i class="bi bi-diagram-3-fill text-warning"></i> តារាងដំណើរការស្កេនវត្តមានប្រចាំថ្ងៃ និងចន្លោះម៉ោងអនុញ្ញាត (Daily Attendance & Scan Windows Workflow)
                            </span>
                            <span class="text-muted font-monospace" style="font-size: 0.72rem;">៤ ដំណាក់កាលស្កេន (4 Scanning Stages / Day)</span>
                        </div>

                        <div class="row g-2 text-center">
                            {{-- Step 1 --}}
                            <div class="col-md-3 col-6">
                                <div class="p-2.5 rounded-3 border border-warning-subtle bg-warning bg-opacity-10 h-100 text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-warning text-dark font-monospace">១. ព្រឹក ចូល</span>
                                        <small class="text-success fw-bold font-monospace" id="timelineMorningScanIn">
                                            {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_in_start'])->format('h:i') }}–{{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_in_end'])->format('h:i A') }}
                                        </small>
                                    </div>
                                    <div class="fw-bold text-dark" id="timelineMorningIn">ចូល: {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_in'])->format('h:i A') }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">លើកលែង {{ $shiftSettings['staff_morning_grace'] }} នាទី</small>
                                    <small class="text-secondary d-block mt-1 font-monospace" style="font-size: 0.68rem;">
                                        <i class="bi bi-qr-code-scan text-success me-1"></i>ស្កេនចូល
                                    </small>
                                </div>
                            </div>
                            {{-- Step 2 --}}
                            <div class="col-md-3 col-6">
                                <div class="p-2.5 rounded-3 border border-warning-subtle bg-warning bg-opacity-10 h-100 text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-warning text-dark font-monospace">២. ព្រឹក ចេញ</span>
                                        <small class="text-danger fw-bold font-monospace" id="timelineMorningScanOut">
                                            {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_out_start'])->format('h:i') }}–{{ \Carbon\Carbon::parse($shiftSettings['staff_morning_scan_out_end'])->format('h:i A') }}
                                        </small>
                                    </div>
                                    <div class="fw-bold text-dark" id="timelineMorningOut">ចេញ: {{ \Carbon\Carbon::parse($shiftSettings['staff_morning_out'])->format('h:i A') }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">សម្រាកបាយថ្ងៃត្រង់</small>
                                    <small class="text-secondary d-block mt-1 font-monospace" style="font-size: 0.68rem;">
                                        <i class="bi bi-box-arrow-right text-danger me-1"></i>ស្កេនចេញ
                                    </small>
                                </div>
                            </div>
                            {{-- Step 3 --}}
                            <div class="col-md-3 col-6">
                                <div class="p-2.5 rounded-3 border border-info-subtle bg-info bg-opacity-10 h-100 text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-info text-dark font-monospace">៣. រសៀល ចូល</span>
                                        <small class="text-success fw-bold font-monospace" id="timelineAfternoonScanIn">
                                            {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_in_start'])->format('h:i') }}–{{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_in_end'])->format('h:i A') }}
                                        </small>
                                    </div>
                                    <div class="fw-bold text-dark" id="timelineAfternoonIn">ចូល: {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_in'])->format('h:i A') }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">លើកលែង {{ $shiftSettings['staff_afternoon_grace'] }} នាទី</small>
                                    <small class="text-secondary d-block mt-1 font-monospace" style="font-size: 0.68rem;">
                                        <i class="bi bi-qr-code-scan text-success me-1"></i>ស្កេនចូលវិញ
                                    </small>
                                </div>
                            </div>
                            {{-- Step 4 --}}
                            <div class="col-md-3 col-6">
                                <div class="p-2.5 rounded-3 border border-info-subtle bg-info bg-opacity-10 h-100 text-start">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-info text-dark font-monospace">៤. រសៀល ចេញ</span>
                                        <small class="text-danger fw-bold font-monospace" id="timelineAfternoonScanOut">
                                            {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_out_start'])->format('h:i') }}–{{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_out_end'])->format('h:i A') }}
                                        </small>
                                    </div>
                                    <div class="fw-bold text-dark" id="timelineAfternoonOut">ចេញ: {{ \Carbon\Carbon::parse($shiftSettings['staff_afternoon_out'])->format('h:i A') }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">បញ្ចប់ការងារប្រចាំថ្ងៃ</small>
                                    <small class="text-secondary d-block mt-1 font-monospace" style="font-size: 0.68rem;">
                                        <i class="bi bi-box-arrow-right text-danger me-1"></i>ស្កេនចេញ
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════
                 FOOTER ACTIONS BAR
            ═══════════════════════════════════════════════ --}}
            <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top flex-wrap gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 btn-sm" onclick="resetDefaultHours()">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> កំណត់ឡើងវិញជាលំនាំដើម (Reset to Defaults)
                </button>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('staff-attendance.index', ['tab' => 'daily']) }}" class="btn btn-light rounded-pill px-3 py-1.5 btn-sm">
                        បោះបង់
                    </a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-check-circle-fill fs-6"></i>
                        <span>រក្សាទុកការកំណត់ម៉ោង និងចន្លោះស្កេន (Save Hours & Scan Windows)</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function format12Hour(time24) {
    if (!time24) return '—';
    const parts = time24.split(':');
    let h = parseInt(parts[0], 10);
    const m = parts[1] || '00';
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12;
    h = h ? h : 12;
    const hStr = h < 10 ? '0' + h : h;
    return `${hStr}:${m} ${ampm}`;
}

function updateTimePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (input && preview) {
        preview.textContent = format12Hour(input.value);
    }

    if (inputId === 'inputMorningIn') document.getElementById('timelineMorningIn').textContent = 'ចូល: ' + format12Hour(input.value);
    if (inputId === 'inputMorningOut') document.getElementById('timelineMorningOut').textContent = 'ចេញ: ' + format12Hour(input.value);
    if (inputId === 'inputAfternoonIn') document.getElementById('timelineAfternoonIn').textContent = 'ចូល: ' + format12Hour(input.value);
    if (inputId === 'inputAfternoonOut') document.getElementById('timelineAfternoonOut').textContent = 'ចេញ: ' + format12Hour(input.value);
}

function updateRangePreview(startId, endId, previewId) {
    const startInput = document.getElementById(startId);
    const endInput = document.getElementById(endId);
    const preview = document.getElementById(previewId);
    if (startInput && endInput && preview) {
        preview.textContent = `${format12Hour(startInput.value)} – ${format12Hour(endInput.value)}`;
    }

    if (startId === 'inputMorningScanInStart' && document.getElementById('timelineMorningScanIn')) {
        document.getElementById('timelineMorningScanIn').textContent = `${format12Hour(startInput.value)} – ${format12Hour(endInput.value)}`;
    }
    if (startId === 'inputMorningScanOutStart' && document.getElementById('timelineMorningScanOut')) {
        document.getElementById('timelineMorningScanOut').textContent = `${format12Hour(startInput.value)} – ${format12Hour(endInput.value)}`;
    }
    if (startId === 'inputAfternoonScanInStart' && document.getElementById('timelineAfternoonScanIn')) {
        document.getElementById('timelineAfternoonScanIn').textContent = `${format12Hour(startInput.value)} – ${format12Hour(endInput.value)}`;
    }
    if (startId === 'inputAfternoonScanOutStart' && document.getElementById('timelineAfternoonScanOut')) {
        document.getElementById('timelineAfternoonScanOut').textContent = `${format12Hour(startInput.value)} – ${format12Hour(endInput.value)}`;
    }
}

function resetDefaultHours() {
    if (confirm('តើលោកអ្នកចង់កំណត់ម៉ោងធ្វើការ និងចន្លោះម៉ោងស្កេនឡើងវិញជាលំនាំដើម ឬទេ?')) {
        document.getElementById('inputMorningIn').value = '06:50';
        document.getElementById('inputMorningOut').value = '10:30';
        document.querySelector('[name="staff_morning_grace"]').value = '0';
        document.getElementById('inputMorningScanInStart').value = '06:00';
        document.getElementById('inputMorningScanInEnd').value = '09:30';
        document.getElementById('inputMorningScanOutStart').value = '10:00';
        document.getElementById('inputMorningScanOutEnd').value = '12:00';

        document.getElementById('inputAfternoonIn').value = '13:10';
        document.getElementById('inputAfternoonOut').value = '16:30';
        document.querySelector('[name="staff_afternoon_grace"]').value = '0';
        document.getElementById('inputAfternoonScanInStart').value = '12:30';
        document.getElementById('inputAfternoonScanInEnd').value = '15:00';
        document.getElementById('inputAfternoonScanOutStart').value = '16:00';
        document.getElementById('inputAfternoonScanOutEnd').value = '18:30';

        document.getElementById('inputShiftSplit').value = '12:00';

        updateTimePreview('inputMorningIn', 'previewMorningIn');
        updateTimePreview('inputMorningOut', 'previewMorningOut');
        updateTimePreview('inputAfternoonIn', 'previewAfternoonIn');
        updateTimePreview('inputAfternoonOut', 'previewAfternoonOut');
        updateTimePreview('inputShiftSplit', 'previewShiftSplit');

        updateRangePreview('inputMorningScanInStart', 'inputMorningScanInEnd', 'previewMorningScanIn');
        updateRangePreview('inputMorningScanOutStart', 'inputMorningScanOutEnd', 'previewMorningScanOut');
        updateRangePreview('inputAfternoonScanInStart', 'inputAfternoonScanInEnd', 'previewAfternoonScanIn');
        updateRangePreview('inputAfternoonScanOutStart', 'inputAfternoonScanOutEnd', 'previewAfternoonScanOut');
    }
}
</script>
