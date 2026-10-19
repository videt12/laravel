<?php $__env->startSection('title', 'ម៉ាស៊ីនស្កេនកាត QR Code វត្តមានបុគ្គលិក — ប្រព័ន្ធគ្រប់គ្រងសាលារៀន'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white" style="border-color: #e2e8f0 !important;">
                
                <div class="card-header text-white p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-25 text-warning shadow-xs" style="width: 38px; height: 38px; font-size: 1.15rem; flex-shrink: 0;">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-white mb-0" style="font-size: 0.98rem; line-height: 1.2;">ម៉ាស៊ីនស្កេនវត្តមានបុគ្គលិក</h6>
                            <small class="text-light text-opacity-75" style="font-size: 0.72rem;">ស្កេន QR Code ឬវាយបញ្ចូលអត្តលេខ</small>
                        </div>
                    </div>

                    
                    <div class="d-flex align-items-center gap-2.5 bg-black bg-opacity-40 px-3 py-1.5 rounded-3 border border-secondary border-opacity-30 shadow-xs">
                        <div class="text-end">
                            <div class="text-warning font-monospace fw-bold lh-1" id="liveClockTime" style="font-size: 1.25rem; letter-spacing: 0.5px;">
                                <?php echo e(now()->format('h:i:s A')); ?>

                            </div>
                            <div class="text-light text-opacity-75 font-monospace mt-1" id="liveClockDate" style="font-size: 0.68rem;">
                                <?php echo e(now()->format('d-M-Y')); ?>

                            </div>
                        </div>
                        <div class="vr bg-secondary opacity-50" style="height: 30px;"></div>
                        <div class="d-flex flex-column align-items-start">
                            <span class="badge rounded-pill px-2 py-0.5 fw-bold font-monospace shadow-xs" id="liveShiftBadge" style="font-size: 0.72rem;">
                                <span class="spinner-grow spinner-grow-sm me-1" role="status"></span> កំពុងពិនិត្យ...
                            </span>
                            <small class="text-light text-opacity-75 font-monospace mt-0.5" id="liveShiftRange" style="font-size: 0.65rem;">
                                --:-- – --:--
                            </small>
                        </div>
                    </div>
                </div>

                
                <div class="bg-light px-3 py-1.5 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2 text-muted" style="font-size: 0.72rem;">
                    <div class="d-flex align-items-center gap-2.5 flex-wrap">
                        <span>
                            ☀️ <strong>ព្រឹក:</strong> ចូល <span class="text-success fw-bold font-monospace"><?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_morning_scan_in_start'])->format('h:i')); ?>–<?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_morning_scan_in_end'])->format('h:i A')); ?></span>
                            · ចេញ <span class="text-danger fw-bold font-monospace"><?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_morning_scan_out_start'])->format('h:i')); ?>–<?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_morning_scan_out_end'])->format('h:i A')); ?></span>
                        </span>
                        <span class="text-secondary opacity-50">|</span>
                        <span>
                            🌤️ <strong>រសៀល:</strong> ចូល <span class="text-success fw-bold font-monospace"><?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_in_start'])->format('h:i')); ?>–<?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_in_end'])->format('h:i A')); ?></span>
                            · ចេញ <span class="text-danger fw-bold font-monospace"><?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_out_start'])->format('h:i')); ?>–<?php echo e(\Carbon\Carbon::parse($shiftSettings['staff_afternoon_scan_out_end'])->format('h:i A')); ?></span>
                        </span>
                    </div>
                    <span class="text-muted font-monospace" style="font-size: 0.68rem;">
                        <i class="bi bi-clock-history text-primary me-1"></i>ម៉ោងប្រព័ន្ធផ្ទាល់
                    </span>
                </div>

                <div class="card-body p-3 text-center">
                    
                    <div class="position-relative mx-auto rounded-4 overflow-hidden shadow-lg bg-black d-flex align-items-center justify-content-center" style="max-width:380px; height:280px;">
                        <video id="kioskVideo" class="w-100 h-100 object-fit-cover" playsinline autoplay muted></video>
                        <canvas id="kioskCanvas" class="d-none"></canvas>

                        
                        <div class="position-absolute" style="width:200px; height:200px; pointer-events:none;">
                            <div style="position:absolute; top:0; left:0; width:28px; height:28px; border-top:4px solid #f59e0b; border-left:4px solid #f59e0b; border-top-left-radius:12px;"></div>
                            <div style="position:absolute; top:0; right:0; width:28px; height:28px; border-top:4px solid #f59e0b; border-right:4px solid #f59e0b; border-top-right-radius:12px;"></div>
                            <div style="position:absolute; bottom:0; left:0; width:28px; height:28px; border-bottom:4px solid #f59e0b; border-left:4px solid #f59e0b; border-bottom-left-radius:12px;"></div>
                            <div style="position:absolute; bottom:0; right:0; width:28px; height:28px; border-bottom:4px solid #f59e0b; border-right:4px solid #f59e0b; border-bottom-right-radius:12px;"></div>
                            <div class="scanner-laser" id="scannerLaser"></div>
                        </div>

                        
                        <button type="button" id="switchCamBtn" class="btn btn-sm btn-dark bg-opacity-75 text-white position-absolute top-0 end-0 m-2 rounded-pill px-3 border border-secondary border-opacity-50 d-none" onclick="toggleCamera()">
                            <i class="bi bi-arrow-repeat me-1"></i>ប្តូរ Camera
                        </button>
                    </div>

                    
                    <div id="kioskStatusBox" class="d-flex align-items-center justify-content-center gap-2 text-warning fw-semibold small my-2.5">
                        <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
                        <span id="kioskStatusText">កំពុងបើក Camera...</span>
                    </div>

                    
                    <div id="kioskInsecureNotice" class="alert alert-warning border-0 small py-2 px-3 text-start mb-2 d-none">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>ចំណាំ៖</strong> Browser បិទមិនឱ្យបើក Camera លើ IP ធម្មតា (HTTP) ទេ។ សូមចូលតាម <code>http://localhost:8000</code> ឬប្រើ HTTPS ឬប្រើម៉ាស៊ីនបាញ់កូដ / វាយអត្តលេខខាងក្រោម។
                    </div>

                    
                    <div class="p-2.5 rounded-3 border text-start mt-2" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                        <label class="form-label text-secondary small mb-1 fw-semibold" style="font-size: 0.78rem;">
                            <i class="bi bi-keyboard me-1 text-warning"></i>វាយបញ្ចូល / បាញ់អត្តលេខបុគ្គលិកដោយ Scanner Gun៖
                        </label>
                        <form id="kioskManualForm" onsubmit="handleKioskSubmit(event)">
                            <div class="input-group input-group-sm">
                                <input type="text" id="kioskStaffCode" class="form-control" placeholder="ឧ. EMP-1001" autofocus autocomplete="off">
                                <button class="btn btn-warning fw-bold px-3" type="submit" id="kioskSubmitBtn">
                                    <i class="bi bi-check-circle-fill me-1"></i>កត់ត្រាវត្តមាន
                                </button>
                            </div>
                        </form>
                        <div id="kioskErrorMsg" class="text-danger small mt-1.5 d-none"></div>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent text-center pb-3 pt-0">
                    <a href="<?php echo e(route('staff-attendance.index')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.78rem;">
                        <i class="bi bi-arrow-left me-1"></i>ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រងវត្តមាន
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="kioskSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content rounded-4 border-0 shadow-lg position-relative overflow-hidden">
            <div class="p-4 text-center text-white position-relative" id="kioskSuccessHeader" style="background:#064e3b">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" onclick="kioskScanNext()"></button>
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:68px; height:68px; background:#10b981; font-size:2.2rem;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h4 class="fw-bold mb-0" id="kioskSuccessTitle">បានកត់ត្រាវត្តមានជោគជ័យ</h4>
            </div>
            <div class="p-4 bg-white text-center">
                <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4 text-secondary bg-light border shadow-sm overflow-hidden" id="kioskSuccessAvatar" style="width:78px; height:78px;">
                    SD
                </div>
                <h5 class="fw-bold text-dark mb-0" id="kioskSuccessName">សុខ តារា</h5>
                <div class="text-muted small mb-1" id="kioskSuccessCode">EMP-1001</div>
                <span class="badge bg-light text-secondary border px-3 py-1 fw-semibold" id="kioskSuccessRole">គ្រូបង្រៀន</span>

                <div class="fs-3 fw-bold font-monospace text-dark my-3">
                    <i class="bi bi-clock text-warning me-1"></i>
                    <span id="kioskSuccessTime">07:12:34 AM</span>
                </div>

                <div class="p-2 rounded-3 fw-semibold small mb-3 bg-success-subtle text-success" id="kioskSuccessNote">
                    សូមជូនពរឱ្យមានថ្ងៃដ៏រីករាយក្នុងការបំពេញការងារ!
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-50 fw-bold py-2" data-bs-dismiss="modal" onclick="kioskScanNext()">
                        <i class="bi bi-x-circle me-1"></i>បិទ
                    </button>
                    <button type="button" class="btn btn-warning w-50 fw-bold py-2 shadow-sm" onclick="kioskScanNext()">
                        <i class="bi bi-qr-code me-1"></i>បន្ទាប់ (<span id="autoNextTimer">3</span>s)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="kioskRejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content rounded-4 border-0 shadow-lg position-relative overflow-hidden">
            <div class="p-4 text-center text-white position-relative" id="kioskRejectHeader" style="background:#7f1d1d; transition: background 0.3s ease;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" onclick="kioskScanNext()"></button>
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2 shadow-sm" id="kioskRejectIconBox" style="width:68px; height:68px; background:#ef4444; font-size:2.2rem; transition: background 0.3s ease;">
                    <i class="bi bi-x-lg" id="kioskRejectIcon"></i>
                </div>
                <h4 class="fw-bold mb-0" id="kioskRejectTitle">មិនអាចចុះវត្តមានបានទេ!</h4>
            </div>
            <div class="p-4 bg-white text-center">
                <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4 text-danger bg-danger-subtle border shadow-sm overflow-hidden" id="kioskRejectAvatar" style="width:78px; height:78px;">
                    <i class="bi bi-person-x-fill fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1" id="kioskRejectEmpName">អ្នកមិនមែនជាម្ចាស់កាតនេះទេ!</h5>
                <div class="small mb-2 fw-semibold font-monospace" id="kioskRejectCode">MISMATCH • NOT VERIFIED</div>

                <div class="p-3 rounded-3 small mb-3 text-start" id="kioskRejectMsg">
                    មុខរបស់អ្នកមិនត្រូវគ្នានឹងរូបថតម្ចាស់កាតក្នុងប្រព័ន្ធឡើយ។
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-50 fw-bold py-2" data-bs-dismiss="modal" onclick="kioskScanNext()">
                        <i class="bi bi-x-circle me-1"></i>បិទ
                    </button>
                    <button type="button" class="btn btn-danger w-50 fw-bold py-2 shadow-sm" id="kioskRejectActionBtn" onclick="kioskScanNext()">
                        <i class="bi bi-arrow-repeat me-1"></i>ស្កេនម្តងទៀត (<span id="autoRejectTimer">4</span>s)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.scanner-laser {
    position: absolute;
    left: 10px;
    right: 10px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #f59e0b, #fbbf24, transparent);
    box-shadow: 0 0 12px #f59e0b;
    top: 20%;
    animation: scanAnim 2.2s infinite ease-in-out;
}
@keyframes scanAnim {
    0%, 100% { top: 15%; opacity: 0.8; }
    50% { top: 85%; opacity: 1; }
}
</style>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
let kioskStream = null;
let kioskAnimFrame = null;
let isProcessingKiosk = false;
let currentFacingMode = 'environment';
let autoCloseInterval = null;
let rejectCloseInterval = null;
let nativeBarcodeDetector = null;

const kioskSuccessModalEl = document.getElementById('kioskSuccessModal');
const kioskSuccess = new bootstrap.Modal(kioskSuccessModalEl);
const kioskRejectModalEl = document.getElementById('kioskRejectModal');
const kioskReject = new bootstrap.Modal(kioskRejectModalEl);

function showKioskReject(title, empName, code, message, emp = null, isWarning = false) {
    playBeep(isWarning ? 'warning' : 'error');
    isProcessingKiosk = true;

    const header = document.getElementById('kioskRejectHeader');
    const iconBox = document.getElementById('kioskRejectIconBox');
    const icon = document.getElementById('kioskRejectIcon');
    const avatar = document.getElementById('kioskRejectAvatar');
    const codeEl = document.getElementById('kioskRejectCode');
    const msgEl = document.getElementById('kioskRejectMsg');
    const actionBtn = document.getElementById('kioskRejectActionBtn');

    document.getElementById('kioskRejectTitle').textContent = title;
    document.getElementById('kioskRejectEmpName').textContent = empName;
    if (codeEl) codeEl.textContent = code;
    if (msgEl) msgEl.textContent = message;

    if (isWarning) {
        if (header) header.style.background = 'linear-gradient(135deg, #78350f, #92400e)';
        if (iconBox) iconBox.style.background = '#d97706';
        if (icon) icon.className = 'bi bi-clock-history';
        if (codeEl) codeEl.className = 'text-warning-emphasis small mb-2 fw-semibold font-monospace';
        if (msgEl) msgEl.className = 'p-3 rounded-3 small mb-3 bg-warning-subtle text-dark border border-warning text-start';
        if (actionBtn) actionBtn.className = 'btn btn-warning text-dark w-50 fw-bold py-2 shadow-sm';

        if (avatar) {
            if (emp && emp.photo_url) {
                avatar.className = 'rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center border border-warning shadow-sm overflow-hidden';
                avatar.innerHTML = `<img src="${emp.photo_url}" class="w-100 h-100 rounded-circle" style="object-fit: cover;">`;
            } else if (emp && emp.initials) {
                avatar.className = 'rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4 text-warning-emphasis bg-warning-subtle border border-warning shadow-sm';
                avatar.textContent = emp.initials;
            } else {
                avatar.className = 'rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4 text-warning bg-warning-subtle border shadow-sm';
                avatar.innerHTML = '<i class="bi bi-hourglass-split fs-2"></i>';
            }
        }
    } else {
        if (header) header.style.background = '#7f1d1d';
        if (iconBox) iconBox.style.background = '#ef4444';
        if (icon) icon.className = 'bi bi-x-lg';
        if (codeEl) codeEl.className = 'text-danger small mb-2 fw-semibold font-monospace';
        if (msgEl) msgEl.className = 'p-3 rounded-3 small mb-3 bg-danger-subtle text-danger text-start';
        if (actionBtn) actionBtn.className = 'btn btn-danger w-50 fw-bold py-2 shadow-sm';

        if (avatar) {
            avatar.className = 'rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold fs-4 text-danger bg-danger-subtle border shadow-sm overflow-hidden';
            avatar.innerHTML = '<i class="bi bi-person-x-fill fs-2"></i>';
        }
    }

    kioskReject.show();

    let countdown = 4;
    const timerEl = document.getElementById('autoRejectTimer');
    if (timerEl) timerEl.textContent = countdown;
    if (rejectCloseInterval) clearInterval(rejectCloseInterval);
    rejectCloseInterval = setInterval(() => {
        countdown--;
        if (timerEl) timerEl.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(rejectCloseInterval);
            kioskScanNext();
        }
    }, 1000);
}

// Initialize Native BarcodeDetector if available
if ('BarcodeDetector' in window) {
    try {
        nativeBarcodeDetector = new BarcodeDetector({ formats: ['qr_code', 'code_128', 'ean_13', 'code_39'] });
    } catch (e) {
        nativeBarcodeDetector = null;
    }
}

// Audio Feedback (Beep using Web Audio API)
function playBeep(type = 'success') {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain);
        gain.connect(audioCtx.destination);

        if (type === 'success') {
            osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
            gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.18);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.18);
        } else {
            osc.frequency.setValueAtTime(300, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.35);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.35);
        }
    } catch (e) {}
}

function updateKioskStatus(status, text, showSpinner = true) {
    const box = document.getElementById('kioskStatusBox');
    const txt = document.getElementById('kioskStatusText');
    if (!box || !txt) return;

    box.className = 'd-flex align-items-center justify-content-center gap-2 fw-semibold small my-3 ' +
        (status === 'success' ? 'text-success' : (status === 'error' ? 'text-danger' : 'text-warning'));

    box.innerHTML = (showSpinner ? `<span class="spinner-grow spinner-grow-sm" role="status"></span>` : `<i class="bi bi-info-circle-fill"></i>`) +
        `<span>${text}</span>`;
}

function initKioskCamera(facing = 'environment') {
    currentFacingMode = facing;
    const video = document.getElementById('kioskVideo');

    // Check if secure context
    if (!window.isSecureContext && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
        document.getElementById('kioskInsecureNotice').classList.remove('d-none');
        updateKioskStatus('error', 'Browser ត្រូវការ HTTPS ដើម្បីបើក Camera', false);
        return;
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        updateKioskStatus('error', 'ឧបករណ៍នេះមិនគាំទ្រ Camera តាម Browser ទេ', false);
        return;
    }

    // Stop existing stream if switching
    if (kioskStream) {
        kioskStream.getTracks().forEach(track => track.stop());
        kioskStream = null;
    }

    updateKioskStatus('warning', 'កំពុងភ្ជាប់ Camera...');

    // Try ideal facingMode first, then fallback to any available camera
    const constraints = {
        video: {
            facingMode: { ideal: facing },
            width: { ideal: 1280 },
            height: { ideal: 720 }
        },
        audio: false
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(stream) {
            setupStream(stream);
        })
        .catch(function(err) {
            console.warn('Preferred camera failed, trying generic video constraints:', err);
            // Fallback: try plain video without constraints
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                .then(function(fallbackStream) {
                    setupStream(fallbackStream);
                })
                .catch(function(finalErr) {
                    console.error('Final camera error:', finalErr);
                    let msg = 'មិនអាចបើក Camera បានទេ (សូមពិនិត្យសិទ្ធិ Camera លើ Browser)';
                    if (finalErr.name === 'NotAllowedError' || finalErr.name === 'PermissionDeniedError') {
                        msg = 'សូមអនុញ្ញាតសិទ្ធិ Camera (Allow) លើ Browser ដើម្បីស្កេន';
                    } else if (finalErr.name === 'NotFoundError' || finalErr.name === 'DevicesNotFoundError') {
                        msg = 'រកមិនឃើញ Camera លើឧបករណ៍នេះទេ';
                    }
                    updateKioskStatus('error', msg, false);
                });
        });

    function setupStream(stream) {
        kioskStream = stream;
        video.srcObject = stream;
        video.setAttribute('playsinline', 'true');
        video.setAttribute('autoplay', 'true');
        video.muted = true;

        video.play().then(() => {
            updateKioskStatus('success', 'ម៉ាស៊ីនស្កេនរួចរាល់ &bull; សូមដាក់កូដ QR ក្នុងស៊ុមស្កេន', true);

            // Show Switch Camera button if multiple video devices exist
            if (navigator.mediaDevices.enumerateDevices) {
                navigator.mediaDevices.enumerateDevices().then(devices => {
                    const videoDevices = devices.filter(d => d.kind === 'videoinput');
                    if (videoDevices.length > 1) {
                        document.getElementById('switchCamBtn').classList.remove('d-none');
                    }
                });
            }

            if (kioskAnimFrame) cancelAnimationFrame(kioskAnimFrame);
            kioskAnimFrame = requestAnimationFrame(kioskTick);
        }).catch(e => {
            console.warn('Video play error:', e);
            updateKioskStatus('warning', 'សូមចុចលើអេក្រង់ដើម្បីចាប់ផ្តើម Camera', false);
        });
    }
}

function toggleCamera() {
    const newFacing = currentFacingMode === 'environment' ? 'user' : 'environment';
    initKioskCamera(newFacing);
}

async function kioskTick() {
    const video = document.getElementById('kioskVideo');
    const canvasElement = document.getElementById('kioskCanvas');

    if (video && video.readyState === video.HAVE_ENOUGH_DATA && !isProcessingKiosk && video.videoWidth > 0) {
        canvasElement.height = video.videoHeight;
        canvasElement.width = video.videoWidth;
        const canvas = canvasElement.getContext('2d', { willReadFrequently: true });
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

        let detectedCode = null;

        // 1. Try Native BarcodeDetector if available
        if (nativeBarcodeDetector) {
            try {
                const barcodes = await nativeBarcodeDetector.detect(canvasElement);
                if (barcodes && barcodes.length > 0 && barcodes[0].rawValue) {
                    detectedCode = barcodes[0].rawValue.trim();
                }
            } catch (e) {}
        }

        // 2. Fallback to jsQR with attemptBoth for maximum recognition
        if (!detectedCode && window.jsQR) {
            try {
                const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "attemptBoth",
                });
                if (code && code.data && code.data.trim()) {
                    detectedCode = code.data.trim();
                }
            } catch (e) {}
        }

        if (detectedCode) {
            isProcessingKiosk = true;
            playBeep('success');

            let snapshot = null;
            try {
                snapshot = canvasElement.toDataURL('image/jpeg', 0.65);
            } catch (err) {}

            submitKioskScan(detectedCode, snapshot);
        }
    }

    kioskAnimFrame = requestAnimationFrame(kioskTick);
}

function handleKioskSubmit(e) {
    e.preventDefault();
    const code = document.getElementById('kioskStaffCode').value.trim();
    if (!code) return;

    let snapshot = null;
    const canvasElement = document.getElementById('kioskCanvas');
    const video = document.getElementById('kioskVideo');
    if (canvasElement && video && video.readyState === video.HAVE_ENOUGH_DATA && video.videoWidth > 0) {
        try {
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
            const ctx = canvasElement.getContext('2d');
            ctx.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            snapshot = canvasElement.toDataURL('image/jpeg', 0.65);
        } catch (err) {}
    }

    isProcessingKiosk = true;
    submitKioskScan(code, snapshot);
}

function submitKioskScan(codeString, snapshot = null) {
    const btn = document.getElementById('kioskSubmitBtn');
    btn.disabled = true;
    const errDiv = document.getElementById('kioskErrorMsg');
    errDiv.classList.add('d-none');
    updateKioskStatus('warning', 'កំពុងផ្ទៀងផ្ទាត់កាត...');

    // Step 1: Verify Card
    fetch('<?php echo e(route("staff-attendance.verify-card")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: codeString })
    })
    .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, data })))
    .then(({ status, ok, data }) => {
        btn.disabled = false;
        if (!ok || !data.success) {
            const emp = data.employee || null;
            const empName = emp ? emp.name : 'បុគ្គលិក';
            const empCode = emp ? (emp.code + (emp.position ? ' • ' + emp.position : '')) : (data.status ? 'CARD ' + data.status.toUpperCase() : 'INVALID');

            let title = 'មិនអាចចុះវត្តមានបានទេ!';
            let isWarning = false;

            if (data.error === 'card_deactivated') {
                title = 'កាតត្រូវបានបិទដំណើរការ!';
            } else if (data.error === 'invalid_card' || data.error === 'not_found') {
                title = 'កាតគ្មានសុពលភាព!';
            } else if (data.error && data.error.includes('expired')) {
                title = 'ផុតកំណត់ម៉ោងស្កេនចូល!';
                isWarning = true;
            } else if (data.error && (data.error.includes('not_started') || data.error === 'too_early')) {
                title = 'មិនទាន់ដល់ពេលកំណត់ទេ!';
                isWarning = true;
            } else if (data.error && data.error.includes('duplicate')) {
                title = 'បានស្កេនរួចរាល់ហើយ!';
                isWarning = true;
            } else if (data.error && data.error.includes('no_in')) {
                title = 'មិនទាន់បានស្កេនចូលទេ!';
                isWarning = true;
            } else if (data.action_label) {
                title = data.action_label;
                isWarning = true;
            }

            showKioskReject(
                title,
                empName,
                empCode,
                data.message || 'រកមិនឃើញទិន្នន័យកាតបុគ្គលិកនេះទេ។',
                emp,
                isWarning
            );
            return;
        }

        if (data.already_completed) {
            showKioskReject(
                'បានចុះវត្តមានរួចរាល់ហើយ!',
                data.employee.name,
                data.employee.code + ' • ' + (data.employee.position || 'បញ្ចប់ការងារ'),
                data.status_note,
                data.employee,
                true
            );
            return;
        }

        // Step 2: Record Verified Attendance
        updateKioskStatus('success', 'កាតត្រឹមត្រូវ! កំពុងកត់ត្រាវត្តមាន...');
        return fetch('<?php echo e(route("staff-attendance.record-verified")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                card_token: data.card.card_token,
                employee_id: data.employee.id,
                liveness_verified: true,
                verification_score: 95,
                device_info: 'Standalone Scanner Kiosk'
            })
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                playBeep('success');
                const emp = result.employee;
                const isCheckIn = result.action === 'Check In';
                document.getElementById('kioskSuccessTitle').textContent = isCheckIn ? 'បានកត់ត្រាវត្តមានចូលជោគជ័យ (Check In)' : 'បានកត់ត្រាវត្តមានចេញជោគជ័យ (Check Out)';
                document.getElementById('kioskSuccessHeader').style.background = isCheckIn ? '#064e3b' : '#1e3a8a';
                document.getElementById('kioskSuccessName').textContent = emp.name;
                document.getElementById('kioskSuccessCode').textContent = emp.code + ' • ' + emp.department;
                document.getElementById('kioskSuccessRole').textContent = emp.position;
                document.getElementById('kioskSuccessTime').textContent = result.time;

                const avatarBox = document.getElementById('kioskSuccessAvatar');
                if (emp.photo_url) {
                    avatarBox.innerHTML = `<img src="${emp.photo_url}" class="w-100 h-100 rounded-circle" style="object-fit: cover;">`;
                } else {
                    avatarBox.textContent = emp.initials;
                }

                document.getElementById('kioskSuccessNote').textContent = isCheckIn ? 'សូមជូនពរឱ្យមានថ្ងៃដ៏រីករាយក្នុងការបំពេញការងារ!' : 'សូមធ្វើដំណើរត្រឡប់ទៅផ្ទះវិញដោយសុវត្ថិភាព! ជួបគ្នានៅថ្ងៃស្អែក!';
                kioskSuccess.show();

                let countdown = 3;
                const timerEl = document.getElementById('autoNextTimer');
                if (timerEl) timerEl.textContent = countdown;
                if (autoCloseInterval) clearInterval(autoCloseInterval);
                autoCloseInterval = setInterval(() => {
                    countdown--;
                    if (timerEl) timerEl.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(autoCloseInterval);
                        kioskScanNext();
                    }
                }, 1000);
            } else {
                showKioskReject(
                    result.action_label || 'កត់ត្រាមិនជោគជ័យ!',
                    (result.employee && result.employee.name) || (data.employee && data.employee.name) || 'បុគ្គលិក',
                    'ATTENDANCE NOTICE',
                    result.message || 'មានបញ្ហាក្នុងការកត់ត្រាវត្តមាន។',
                    result.employee || data.employee,
                    true
                );
            }
        });
    })
    .catch(err => {
        playBeep('error');
        btn.disabled = false;
        showKioskReject('មានបញ្ហាតភ្ជាប់!', 'ប្រព័ន្ធម៉ាស៊ីនបម្រើ', 'NETWORK ERROR', 'មិនអាចទាក់ទងម៉ាស៊ីនបម្រើបានទេ។ សូមព្យាយាមម្តងទៀត។');
    });
}

function kioskScanNext() {
    if (autoCloseInterval) clearInterval(autoCloseInterval);
    if (rejectCloseInterval) clearInterval(rejectCloseInterval);
    try { kioskSuccess.hide(); } catch(e) {}
    try { kioskReject.hide(); } catch(e) {}
    isProcessingKiosk = false;
    const staffInput = document.getElementById('kioskStaffCode');
    if (staffInput) staffInput.value = '';
    const errorMsg = document.getElementById('kioskErrorMsg');
    if (errorMsg) errorMsg.classList.add('d-none');
    refocusInput();
}

function refocusInput() {
    setTimeout(() => {
        const inp = document.getElementById('kioskStaffCode');
        if (inp) inp.focus();
    }, 150);
}

kioskSuccessModalEl.addEventListener('hidden.bs.modal', function () {
    if (autoCloseInterval) clearInterval(autoCloseInterval);
    isProcessingKiosk = false;
    refocusInput();
});

kioskRejectModalEl.addEventListener('hidden.bs.modal', function () {
    if (rejectCloseInterval) clearInterval(rejectCloseInterval);
    isProcessingKiosk = false;
    refocusInput();
});

// ⏰ REAL-TIME CLOCK & SHIFT WINDOW EVALUATOR
const shiftSettings = <?php echo json_encode($shiftSettings, 15, 512) ?>;

function timeToMinutes(timeStr) {
    if (!timeStr) return 0;
    const parts = timeStr.split(':');
    return (parseInt(parts[0], 10) * 60) + parseInt(parts[1], 10);
}

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

const khmerDays = ['អាទិត្យ', 'ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'];
const khmerMonths = ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា', 'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'];
const khmerNumbers = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];

function toKhmerNum(num) {
    return String(num).split('').map(char => khmerNumbers[char] !== undefined ? khmerNumbers[char] : char).join('');
}

function updateLiveClock() {
    const now = new Date();

    // 1. Time formatted HH:mm:ss AM/PM
    let h = now.getHours();
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    const ampm = h >= 12 ? 'PM' : 'AM';
    const displayH = String(h % 12 || 12).padStart(2, '0');
    const timeFormatted = `${displayH}:${m}:${s} ${ampm}`;

    const clockTimeEl = document.getElementById('liveClockTime');
    if (clockTimeEl) clockTimeEl.textContent = timeFormatted;

    // 2. Date in Khmer
    const dayName = khmerDays[now.getDay()];
    const dateNum = toKhmerNum(now.getDate());
    const monthName = khmerMonths[now.getMonth()];
    const yearNum = toKhmerNum(now.getFullYear());
    const khmerDateStr = `ថ្ងៃ${dayName} ទី${dateNum} ខែ${monthName} ឆ្នាំ${yearNum}`;

    const clockDateEl = document.getElementById('liveClockDate');
    if (clockDateEl) clockDateEl.textContent = khmerDateStr;

    // 3. Dynamic Active Shift Window Status
    const currentMins = (h * 60) + now.getMinutes();
    const mInStart = timeToMinutes(shiftSettings.staff_morning_scan_in_start || '06:00');
    const mInEnd = timeToMinutes(shiftSettings.staff_morning_scan_in_end || '09:30');
    const mOutStart = timeToMinutes(shiftSettings.staff_morning_scan_out_start || '10:00');
    const mOutEnd = timeToMinutes(shiftSettings.staff_morning_scan_out_end || '12:00');

    const aInStart = timeToMinutes(shiftSettings.staff_afternoon_scan_in_start || '12:30');
    const aInEnd = timeToMinutes(shiftSettings.staff_afternoon_scan_in_end || '15:00');
    const aOutStart = timeToMinutes(shiftSettings.staff_afternoon_scan_out_start || '16:00');
    const aOutEnd = timeToMinutes(shiftSettings.staff_afternoon_scan_out_end || '18:30');

    const badgeEl = document.getElementById('liveShiftBadge');
    const rangeEl = document.getElementById('liveShiftRange');

    if (!badgeEl || !rangeEl) return;

    if (currentMins >= mInStart && currentMins <= mInEnd) {
        badgeEl.className = 'badge bg-success rounded-pill px-2.5 py-1 fw-bold font-monospace shadow-xs';
        badgeEl.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i> ស្កេនចូល វេនព្រឹក';
        rangeEl.textContent = `${format12Hour(shiftSettings.staff_morning_scan_in_start)} – ${format12Hour(shiftSettings.staff_morning_scan_in_end)}`;
    } else if (currentMins > mInEnd && currentMins < mOutStart) {
        badgeEl.className = 'badge bg-dark text-warning border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold font-monospace shadow-xs';
        badgeEl.innerHTML = '<i class="bi bi-hourglass-split me-1 text-warning"></i> ផុតកំណត់ចូល • មិនទាន់ដល់ម៉ោងចេញ';
        rangeEl.textContent = `ម៉ោងស្កេនចេញ: ${format12Hour(shiftSettings.staff_morning_scan_out_start)}`;
    } else if (currentMins >= mOutStart && currentMins <= mOutEnd) {
        badgeEl.className = 'badge bg-warning text-dark rounded-pill px-2.5 py-1 fw-bold font-monospace shadow-xs';
        badgeEl.innerHTML = '<i class="bi bi-box-arrow-right me-1"></i> ស្កេនចេញ វេនព្រឹក';
        rangeEl.textContent = `${format12Hour(shiftSettings.staff_morning_scan_out_start)} – ${format12Hour(shiftSettings.staff_morning_scan_out_end)}`;
    } else if (currentMins >= aInStart && currentMins <= aInEnd) {
        badgeEl.className = 'badge bg-info text-dark rounded-pill px-2.5 py-1 fw-bold font-monospace shadow-xs';
        badgeEl.innerHTML = '<i class="bi bi-box-arrow-in-right me-1"></i> ស្កេនចូល វេនរសៀល';
        rangeEl.textContent = `${format12Hour(shiftSettings.staff_afternoon_scan_in_start)} – ${format12Hour(shiftSettings.staff_afternoon_scan_in_end)}`;
    } else if (currentMins > aInEnd && currentMins < aOutStart) {
        badgeEl.className = 'badge bg-dark text-warning border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold font-monospace shadow-xs';
        badgeEl.innerHTML = '<i class="bi bi-hourglass-split me-1 text-warning"></i> ផុតកំណត់ចូល • មិនទាន់ដល់ម៉ោងចេញ';
        rangeEl.textContent = `ម៉ោងស្កេនចេញ: ${format12Hour(shiftSettings.staff_afternoon_scan_out_start)}`;
    } else if (currentMins >= aOutStart && currentMins <= aOutEnd) {
        badgeEl.className = 'badge bg-primary rounded-pill px-2.5 py-1 fw-bold font-monospace shadow-xs';
        badgeEl.innerHTML = '<i class="bi bi-box-arrow-right me-1"></i> ស្កេនចេញ វេនរសៀល';
        rangeEl.textContent = `${format12Hour(shiftSettings.staff_afternoon_scan_out_start)} – ${format12Hour(shiftSettings.staff_afternoon_scan_out_end)}`;
    } else {
        badgeEl.className = 'badge bg-secondary rounded-pill px-2.5 py-1 fw-bold font-monospace shadow-xs';
        badgeEl.innerHTML = '<i class="bi bi-pause-circle me-1"></i> ក្រៅម៉ោងស្កេនវត្តមាន';
        rangeEl.textContent = 'បិទការស្កេនជាបណ្ដោះអាសន្ន';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateLiveClock();
    setInterval(updateLiveClock, 1000);
    initKioskCamera();
    refocusInput();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/employees/attendance/scanner.blade.php ENDPATH**/ ?>