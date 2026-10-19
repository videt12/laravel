

<?php $__env->startSection('title', 'គ្រប់គ្រងវត្តមានបុគ្គលិក Staff Attendance — សាលារៀន ប្រទីបបញ្ញា'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo e(route('students.index')); ?>" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">គ្រប់គ្រងបុគ្គលិក</li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">គ្រប់គ្រងវត្តមានបុគ្គលិក</li>
    </ol>
</nav>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ═══════════════════════════════════════════════════════════════════
   PROFESSIONAL HR TERMINAL THEME
═══════════════════════════════════════════════════════════════════ */
:root {
    --terminal-dark: #090d16;
    --terminal-card: #0f172a;
    --terminal-border: #1e293b;
    --terminal-emerald: #10b981;
    --terminal-gold: #f59e0b;
}

/* Page Header & Live Status */
.terminal-header {
    background: #ffffff;
    border: 1px solid var(--border-soft, #e2e8f0);
    border-radius: var(--radius-lg, 16px);
    padding: 1.15rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}

.terminal-brand-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.terminal-brand-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    border: 1px solid #fcd34d;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
    flex-shrink: 0;
}

.terminal-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.15rem;
    line-height: 1.2;
}

.terminal-title span {
    font-size: 0.95rem;
    font-weight: 600;
    color: #64748b;
    margin-left: 0.35rem;
}

.terminal-subtitle {
    font-size: 0.8125rem;
    color: #64748b;
    margin-bottom: 0;
}

.terminal-meta-group {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    flex-wrap: wrap;
}

.live-pulse-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
    letter-spacing: 0.3px;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: livePulse 1.8s infinite;
}

@keyframes livePulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.live-digital-clock-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: #0f172a;
    color: #f8fafc;
    padding: 0.45rem 1rem;
    border-radius: 999px;
    font-family: var(--font-mono, monospace);
    font-size: 0.95rem;
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
}

.live-digital-clock-pill i {
    color: #fbbf24;
}

.live-date-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #334155;
    padding: 0.45rem 0.85rem;
    border-radius: 999px;
    font-size: 0.8125rem;
    font-weight: 600;
}

/* ═══════════════════════════════════════════════════════════════════
   MAIN TERMINAL KIOSK CARDS
═══════════════════════════════════════════════════════════════════ */
.kiosk-card {
    background: #ffffff;
    border: 1px solid var(--border, #e2e8f0);
    border-radius: var(--radius-lg, 18px);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.kiosk-card-header {
    padding: 1.15rem 1.4rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border-soft, #f1f5f9);
    background: #ffffff;
}

.kiosk-header-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.kiosk-icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #fef3c7;
    color: #92400e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.kiosk-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.1rem;
}

.kiosk-card-sub {
    font-size: 0.76rem;
    color: #64748b;
    margin-bottom: 0;
}

/* Camera Bezel HUD Container */
.scanner-viewport-bezel {
    position: relative;
    width: 100%;
    height: 330px;
    background: radial-gradient(circle at center, #0f172a 0%, #020617 100%);
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #1e293b;
    box-shadow: inset 0 0 40px rgba(0, 0, 0, 0.9), 0 8px 24px -4px rgba(0, 0, 0, 0.12);
}

.scanner-video-feed {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Precision Scanner HUD Frame */
.scanner-hud-overlay {
    position: absolute;
    width: 220px;
    height: 220px;
    pointer-events: none;
}

.hud-corner {
    position: absolute;
    width: 32px;
    height: 32px;
    border-color: #10b981;
}

.hud-tl { top: 0; left: 0; border-top: 3.5px solid #10b981; border-left: 3.5px solid #10b981; border-top-left-radius: 10px; }
.hud-tr { top: 0; right: 0; border-top: 3.5px solid #10b981; border-right: 3.5px solid #10b981; border-top-right-radius: 10px; }
.hud-bl { bottom: 0; left: 0; border-bottom: 3.5px solid #10b981; border-left: 3.5px solid #10b981; border-bottom-left-radius: 10px; }
.hud-br { bottom: 0; right: 0; border-bottom: 3.5px solid #10b981; border-right: 3.5px solid #10b981; border-bottom-right-radius: 10px; }

.hud-crosshair {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 24px; height: 24px;
    opacity: 0.35;
}
.hud-crosshair::before, .hud-crosshair::after {
    content: '';
    position: absolute;
    background: #10b981;
}
.hud-crosshair::before { width: 100%; height: 1.5px; top: 50%; left: 0; transform: translateY(-50%); }
.hud-crosshair::after { height: 100%; width: 1.5px; left: 50%; top: 0; transform: translateX(-50%); }

.hud-scan-laser {
    position: absolute;
    left: 4px;
    right: 4px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #10b981, #6ee7b7, #10b981, transparent);
    box-shadow: 0 0 12px #10b981, 0 0 4px #a7f3d0;
    top: 20%;
    animation: laserScanHud 2.2s infinite ease-in-out;
}

@keyframes laserScanHud {
    0%, 100% { top: 10%; opacity: 0.4; }
    50% { top: 90%; opacity: 1; }
}

.scanner-hud-status {
    position: absolute;
    bottom: 12px;
    background: rgba(3, 7, 18, 0.85);
    backdrop-filter: blur(8px);
    color: #f8fafc;
    font-size: 0.76rem;
    font-weight: 600;
    padding: 0.32rem 0.85rem;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.scanner-hud-status .live-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #10b981;
}

/* Fast Input Barcode Bar */
.barcode-input-container {
    margin-top: 0.85rem;
}

.barcode-input-group {
    display: flex;
    align-items: stretch;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.barcode-input-group:focus-within {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
}

.barcode-input-prefix {
    padding: 0 0.9rem;
    display: flex;
    align-items: center;
    color: #64748b;
    background: #f8fafc;
    border-right: 1px solid #e2e8f0;
}

.barcode-input-field {
    flex: 1;
    border: none;
    outline: none;
    padding: 0.6rem 0.9rem;
    font-family: var(--font-mono, monospace);
    font-size: 0.92rem;
    color: #0f172a;
    background: transparent;
    text-transform: uppercase;
}

.barcode-input-field[readonly] {
    cursor: default;
    user-select: none;
    background: #f8fafc;
}

.barcode-input-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
    border: none;
    font-weight: 700;
    font-size: 0.875rem;
    padding: 0 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    cursor: pointer;
    transition: filter 0.15s ease;
}

.barcode-input-btn:hover {
    filter: brightness(1.05);
}

/* ═══════════════════════════════════════════════════════════════════
   HR SHIFT SCHEDULE & ATTENDANCE MONITOR (Right of camera)
═══════════════════════════════════════════════════════════════════ */
.shift-monitor-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.15rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.shift-section-title {
    font-size: 0.85rem;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.shift-badge-active {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.65rem 0.85rem;
    margin-bottom: 0.65rem;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}

.shift-badge-active.is-current {
    border-color: #fde68a;
    background: #fffbeb;
}

.shift-info-title {
    font-size: 0.84rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.1rem;
}

.shift-info-sub {
    font-size: 0.73rem;
    color: #64748b;
}

.shift-status-pill {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
    background: #ecfdf5;
    color: #065f46;
}

/* Shift Progress Bar */
.attendance-progress-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.75rem 0.85rem;
    margin-top: 0.5rem;
}

.progress-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.78rem;
    margin-bottom: 0.4rem;
}

.progress-custom-bar {
    height: 7px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    border-radius: 999px;
    transition: width 0.4s ease;
}

/* ═══════════════════════════════════════════════════════════════════
   EXECUTIVE EMPLOYEE IDENTITY VERIFICATION BADGE (RIGHT SIDE)
═══════════════════════════════════════════════════════════════════ */
.verification-badge-card {
    background: #ffffff;
    border: 1px solid var(--border, #e2e8f0);
    border-radius: var(--radius-lg, 18px);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
}

.verification-header {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: #ffffff;
    padding: 1.15rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: background 0.3s ease;
}

.verification-header.is-checkout {
    background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
}

.verification-check-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.22);
    backdrop-filter: blur(4px);
    border: 1.5px solid rgba(255, 255, 255, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    color: #ffffff;
    flex-shrink: 0;
}

.verification-title-kh {
    font-size: 1.1rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 0.1rem;
}

.verification-title-en {
    font-size: 0.78rem;
    font-weight: 600;
    opacity: 0.9;
    margin-bottom: 0;
}

.verification-body {
    padding: 1.5rem 1.4rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* Standby Radar Ring */
.standby-radar-ring {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    margin: 0 auto;
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border: 2px solid #a7f3d0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #059669;
    box-shadow: 0 0 0 10px rgba(236, 253, 245, 0.9), 0 0 0 20px rgba(236, 253, 245, 0.45);
    animation: standbyRadarPulse 2.8s infinite ease-in-out;
}

@keyframes standbyRadarPulse {
    0%, 100% { box-shadow: 0 0 0 8px rgba(236, 253, 245, 0.9), 0 0 0 16px rgba(236, 253, 245, 0.4); transform: scale(1); }
    50% { box-shadow: 0 0 0 14px rgba(236, 253, 245, 0.95), 0 0 0 28px rgba(236, 253, 245, 0.2); transform: scale(1.03); }
}

.standby-hints-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.85rem 1rem;
    text-align: left;
    width: 100%;
}

/* Executive Photo Frame */
.staff-avatar-ring {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    padding: 3px;
    background: linear-gradient(135deg, #10b981, #f59e0b);
    margin-bottom: 0.85rem;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
}

.staff-avatar-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: #f8fafc;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    font-weight: 800;
    color: #0f172a;
    border: 2px solid #ffffff;
}

.staff-name-kh {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.15rem;
    line-height: 1.2;
}

.staff-name-en {
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.45rem;
}

.staff-code-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-family: var(--font-mono, monospace);
    font-weight: 700;
    font-size: 0.8125rem;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #0f172a;
    padding: 0.2rem 0.65rem;
    border-radius: 6px;
    margin-bottom: 0.35rem;
}

.staff-position-dept {
    font-size: 0.84rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 1.15rem;
}

/* 2x2 Details Grid */
.verification-grid {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.65rem;
    margin-bottom: 1.15rem;
}

.v-metric-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.65rem 0.5rem;
    text-align: center;
}

.v-metric-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.15rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.v-metric-val {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
}

.v-metric-val.mono {
    font-family: var(--font-mono, monospace);
}

.verification-footer-ribbon {
    width: 100%;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    border-radius: 10px;
    padding: 0.65rem 0.85rem;
    font-size: 0.84rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    margin-top: auto;
}

/* ═══════════════════════════════════════════════════════════════════
   KPI SUMMARY CARDS (ROW 2 LEFT)
═══════════════════════════════════════════════════════════════════ */
.stat-kpi-card {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.15rem 0.75rem;
    text-align: center;
    height: 100%;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}

.stat-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.06);
}

.stat-kpi-present { background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%); border-color: #bbf7d0; }
.stat-kpi-late    { background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%); border-color: #fde68a; }
.stat-kpi-absent  { background: linear-gradient(180deg, #fef2f2 0%, #ffffff 100%); border-color: #fecaca; }
.stat-kpi-leave   { background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%); border-color: #bfdbfe; }

.stat-kpi-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 0.6rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.icon-present { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; }
.icon-late    { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; }
.icon-absent  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; }
.icon-leave   { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #fff; }

.stat-kpi-num {
    font-size: 1.85rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 0.2rem;
    font-family: var(--font-sans);
}

.stat-kpi-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #0f172a;
}

.stat-kpi-sub {
    font-size: 0.72rem;
    color: #64748b;
    font-weight: 600;
}

/* ═══════════════════════════════════════════════════════════════════
   RECENT ACTIVITY STREAM (ROW 2 RIGHT)
═══════════════════════════════════════════════════════════════════ */
.activity-stream-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05), 0 4px 10px -2px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.activity-stream-header {
    padding: 1.15rem 1.4rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stream-table {
    width: 100%;
    margin: 0;
}

.stream-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.stream-table tbody td {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    color: #1e293b;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.stream-table tbody tr:last-child td {
    border-bottom: none;
}

.stream-avatar-mini {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: #334155;
    margin-right: 0.5rem;
    flex-shrink: 0;
}

.stream-pill-in {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.stream-pill-out {
    background: #eff6ff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.stream-pill-ontime {
    background: #f0fdf4;
    color: #15803d;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
}

.stream-pill-late {
    background: #fffbeb;
    color: #b45309;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.2rem 0.6rem;
    border-radius: 999px;
}

/* ═══════════════════════════════════════════════════════════════════
   FACE VERIFICATION & LIVENESS DETECTION HUD
═══════════════════════════════════════════════════════════════════ */
.face-verification-hud {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.82);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 25;
    padding: 1rem;
    animation: fadeIn 0.25s ease;
}

.face-hud-card {
    background: rgba(15, 23, 42, 0.95);
    border: 1px solid rgba(245, 158, 11, 0.5);
    border-radius: 20px;
    padding: 1.25rem;
    width: 100%;
    max-width: 340px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 20px rgba(245, 158, 11, 0.25);
    text-align: center;
}

.face-oval-target {
    width: 150px;
    height: 190px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.face-oval-ring {
    width: 100%;
    height: 100%;
    border-radius: 50% / 60%;
    border: 3px dashed #f59e0b;
    box-shadow: 0 0 18px rgba(245, 158, 11, 0.45);
    animation: ovalPulse 2s infinite ease-in-out;
}

.face-oval-ring.matched {
    border-color: #10b981;
    border-style: solid;
    box-shadow: 0 0 25px rgba(16, 185, 129, 0.8);
}

@keyframes ovalPulse {
    0%, 100% { transform: scale(0.97); opacity: 0.85; }
    50% { transform: scale(1.02); opacity: 1; }
}

.face-prompt-badge {
    position: absolute;
    bottom: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: #0f172a;
    border: 1px solid #f59e0b;
    color: #fbbf24;
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0,0,0,0.5);
}

.step-pill {
    padding: 0.25rem 0.6rem;
    border-radius: 999px;
    font-size: 0.68rem;
    font-weight: 700;
    background: rgba(255, 255, 255, 0.08);
    color: #94a3b8;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.step-pill.active {
    background: rgba(245, 158, 11, 0.25);
    color: #fbbf24;
    border-color: #f59e0b;
}

.step-pill.completed {
    background: rgba(16, 185, 129, 0.25);
    color: #34d399;
    border-color: #10b981;
}

/* Rejection Overlay */
.verification-reject-overlay {
    position: absolute;
    inset: 0;
    background: rgba(127, 29, 29, 0.92);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 30;
    padding: 1.5rem;
    animation: shakeAnim 0.4s ease;
}

.reject-box {
    background: #450a0a;
    border: 2px solid #ef4444;
    border-radius: 20px;
    max-width: 340px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7), 0 0 30px rgba(239, 68, 68, 0.5);
}

.reject-icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #ef4444;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
}

@keyframes shakeAnim {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-10px); }
    40%, 80% { transform: translateX(10px); }
}

.tab-nav-btn {
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.35rem 0.85rem;
    transition: all 0.2s ease;
    text-decoration: none;
}
.tab-nav-btn.active {
    background: #f59e0b;
    color: #0f172a;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.35);
}
.tab-nav-btn:not(.active) {
    color: #64748b;
    background: transparent;
}
.tab-nav-btn:not(.active):hover {
    background: #f1f5f9;
    color: #0f172a;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $cDate = \Carbon\Carbon::parse($currentDate);
    $khmerDays = [
        'Monday' => 'ថ្ងៃច័ន្ទ', 'Tuesday' => 'ថ្ងៃអង្គារ', 'Wednesday' => 'ថ្ងៃពុធ',
        'Thursday' => 'ថ្ងៃព្រហស្បតិ៍', 'Friday' => 'ថ្ងៃសុក្រ', 'Saturday' => 'ថ្ងៃសៅរ៍', 'Sunday' => 'ថ្ងៃអាទិត្យ'
    ];
    $khmerMonths = [
        'January' => 'មករា', 'February' => 'កុម្ភៈ', 'March' => 'មីនា', 'April' => 'មេសា',
        'May' => 'ឧសភា', 'June' => 'មិថុនា', 'July' => 'កក្កដា', 'August' => 'សីហា',
        'September' => 'កញ្ញា', 'October' => 'តុលា', 'November' => 'វិច្ឆិកា', 'December' => 'ធ្នូ'
    ];
    $dayKh = $khmerDays[$cDate->format('l')] ?? $cDate->format('l');
    $monthKh = $khmerMonths[$cDate->format('F')] ?? $cDate->format('F');
    $khmerDateFormatted = $cDate->format('d') . ' ' . $monthKh . ' ' . $cDate->format('Y');

    // Default display employee for top-right card (latest scanned or fallback)
    $scannedEmp = $latestScanned?->employee;
    $scannedUser = $scannedEmp?->user;
    $defaultNameKh = $scannedUser?->name ?? 'សួន ដារ៉ា';
    $defaultNameEn = $scannedEmp ? ($scannedUser?->name ?? 'Sok Dara') : 'Sok Dara';
    $defaultCode = $scannedEmp?->employee_code ?? 'BPS00001';
    $defaultPos = $scannedEmp?->employee_position?->name ?? 'គ្រូបង្រៀន (Teacher)';
    $defaultDept = $scannedEmp?->employee_department?->name ?? 'ដេប៉ាតឺម៉ង់បង្រៀន';
    $defaultAvatar = $scannedEmp?->photo_url ?? null;
    $defaultInitials = strtoupper(substr($defaultNameKh, 0, 2));
    $defaultTime = $latestScanned ? \Carbon\Carbon::parse($latestScanned->updated_at)->format('h:i:s A') : now()->format('h:i:s A');
    $defaultAction = ($latestScanned && !empty($latestScanned->out_time)) ? 'Check Out' : 'Check In';
    $defaultStatus = $latestScanned?->status ?? 'Present';

    // Current shift calculation
    $currentHour = (int) now()->format('H');
    $isMorning = ($currentHour >= 6 && $currentHour < 12);
?>


<div class="terminal-header">
    <div class="terminal-brand-group">
        <div class="terminal-brand-icon">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <div>
            <h1 class="terminal-title">
                គ្រប់គ្រងវត្តមានបុគ្គលិក
            </h1>
            <p class="terminal-subtitle">
                តារាងវត្តមានប្រចាំថ្ងៃ និងរបាយការណ៍ស្កេនវត្តមានបុគ្គលិក
            </p>
        </div>
    </div>

    <div class="terminal-meta-group">
        
        <div class="live-digital-clock-pill">
            <i class="bi bi-clock-fill"></i>
            <span id="terminalMasterClock"><?php echo e(now()->format('h:i:s A')); ?></span>
        </div>

        
        <div class="live-date-pill">
            <i class="bi bi-calendar3 text-warning"></i>
            <span><?php echo e($dayKh); ?>, <?php echo e($khmerDateFormatted); ?></span>
        </div>
    </div>
</div>


<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2 p-1.5 bg-white rounded-4 border shadow-xs">
    <div class="d-flex align-items-center flex-wrap gap-1">
        <a href="<?php echo e(route('staff-attendance.index', ['tab' => 'daily', 'date' => $currentDate])); ?>" 
           class="tab-nav-btn d-inline-flex align-items-center gap-1.5 <?php echo e(($tab === 'daily' || empty($tab)) ? 'active' : ''); ?>">
            <i class="bi bi-calendar-check-fill"></i>
            <span>តារាងវត្តមានប្រចាំថ្ងៃ</span>
        </a>
        <a href="<?php echo e(route('staff-attendance.index', ['tab' => 'cards'])); ?>" 
           class="tab-nav-btn d-inline-flex align-items-center gap-1.5 <?php echo e($tab === 'cards' ? 'active' : ''); ?>">
            <i class="bi bi-person-vcard-fill"></i>
            <span>កាតបុគ្គលិក</span>
            <?php if(isset($allEmployees)): ?>
                <span class="badge bg-dark text-white rounded-pill px-1.5 py-0.5 ms-1" style="font-size: 0.68rem;"><?php echo e($allEmployees->count()); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo e(route('staff-attendance.index', ['tab' => 'monthly', 'month' => $currentMonth])); ?>" 
           class="tab-nav-btn d-inline-flex align-items-center gap-1.5 <?php echo e($tab === 'monthly' ? 'active' : ''); ?>">
            <i class="bi bi-calendar2-month"></i>
            <span>របាយការណ៍ប្រចាំខែ</span>
        </a>
    </div>

    <div class="d-flex align-items-center gap-1.5 flex-wrap ms-auto">
        <a href="<?php echo e(route('staff-attendance.scanner')); ?>" target="_blank" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1.5 shadow-xs" style="font-size: 0.82rem;">
            <i class="bi bi-fullscreen fs-7"></i>
            <span>🖥️ Kiosk ពេញអេក្រង់</span>
        </a>
    </div>
</div>

<?php if($tab === 'daily' || empty($tab)): ?>
    <?php echo $__env->make('employees.attendance.partials.daily_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php elseif($tab === 'cards'): ?>
    <?php echo $__env->make('employees.attendance.partials.cards_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php elseif($tab === 'monthly'): ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white p-3 p-md-4 border-bottom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">
                    <i class="bi bi-calendar2-month text-warning me-2"></i>របាយការណ៍វត្តមានប្រចាំខែ (Monthly Attendance)
                </h5>
                <p class="text-muted small mb-0">ខែ៖ <strong><?php echo e(\Carbon\Carbon::parse($currentMonth . '-01')->format('F Y')); ?></strong></p>
            </div>
            <form action="<?php echo e(route('staff-attendance.index')); ?>" method="GET" class="d-flex align-items-center flex-wrap gap-2">
                <input type="hidden" name="tab" value="monthly">
                <input type="month" name="month" class="form-control form-control-sm rounded-pill" value="<?php echo e($currentMonth); ?>" onchange="this.form.submit()">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control rounded-start-pill ps-3" placeholder="ស្វែងរកតាមឈ្មោះ/កូដ..." value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-warning rounded-end-pill px-3" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($monthlyStats)): ?>
        <div class="row g-0 border-bottom bg-light bg-opacity-50">
            <div class="col-3 p-3 px-4 border-end text-center">
                <div class="fs-4 fw-bold text-success"><?php echo e($monthlyStats['present']); ?></div>
                <div class="text-muted small">វត្តមាន (Present)</div>
            </div>
            <div class="col-3 p-3 px-4 border-end text-center">
                <div class="fs-4 fw-bold text-warning"><?php echo e($monthlyStats['late']); ?></div>
                <div class="text-muted small">យឺត (Late)</div>
            </div>
            <div class="col-3 p-3 px-4 border-end text-center">
                <div class="fs-4 fw-bold text-danger"><?php echo e($monthlyStats['absent']); ?></div>
                <div class="text-muted small">អវត្តមាន (Absent)</div>
            </div>
            <div class="col-3 p-3 px-4 text-center">
                <div class="fs-4 fw-bold text-primary"><?php echo e($monthlyStats['leave']); ?></div>
                <div class="text-muted small">ច្បាប់ (Leave)</div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">កាលបរិច្ឆេទ</th>
                        <th>បុគ្គលិក</th>
                        <th>មុខតំណែង</th>
                        <th>ម៉ោងចូល</th>
                        <th>ម៉ោងចេញ</th>
                        <th>ស្ថានភាព</th>
                        <th>កំណត់សម្គាល់</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $monthlyRecords ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $mEmp = $m->employee;
                            $mName = $mEmp?->full_name ?? $mEmp?->user?->name ?? 'បុគ្គលិក';
                        ?>
                        <tr>
                            <td class="ps-4 font-monospace text-muted small"><?php echo e($m->attendance_date?->format('d-M-Y')); ?></td>
                            <td>
                                <div class="fw-bold text-dark small"><?php echo e($mName); ?></div>
                                <div class="text-muted small font-monospace" style="font-size: 0.72rem;"><?php echo e($mEmp?->employee_code); ?></div>
                            </td>
                            <td class="small"><?php echo e($mEmp?->employee_position?->name ?? '—'); ?></td>
                            <td class="font-monospace text-primary small"><?php echo e($m->in_time ? \Carbon\Carbon::parse($m->in_time)->format('h:i A') : '—'); ?></td>
                            <td class="font-monospace text-secondary small"><?php echo e($m->out_time ? \Carbon\Carbon::parse($m->out_time)->format('h:i A') : '—'); ?></td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-0.5"><?php echo e(ucfirst($m->status)); ?></span>
                            </td>
                            <td class="small text-muted"><?php echo e(Str::limit($m->remarks, 30) ?: '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">មិនមានទិន្នន័យសម្រាប់ខែនេះឡើយ។</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if(isset($monthlyRecords) && $monthlyRecords->hasPages()): ?>
        <div class="card-footer bg-white p-3 border-top">
            <?php echo e($monthlyRecords->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="modal fade" id="deactivateCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-danger text-white p-3 px-4">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-shield-lock-fill me-1"></i>បិទដំណើរការកាតបុគ្គលិក
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deactivateCardForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger mb-3" style="width: 58px; height: 58px; font-size: 1.6rem;">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1" id="deactivateEmpName">ឈ្មោះបុគ្គលិក</h6>
                    <p class="text-muted small mb-3">លេខកាត៖ <strong id="deactivateCardNum" class="font-monospace text-danger">CRD-0000</strong></p>

                    <div class="text-start mb-3">
                        <label class="form-label small fw-bold text-secondary">មូលហេតុនៃការបិទកាត <span class="text-danger">*</span></label>
                        <select name="reason" class="form-select" required>
                            <option value="បាត់កាត">បាត់កាត</option>
                            <option value="ខូចខាត ឬបាក់បែក">ខូចខាត ឬបាក់បែក</option>
                            <option value="សង្ស័យមានការក្លែងបន្លំ">សង្ស័យមានការក្លែងបន្លំ</option>
                            <option value="ផ្អាកការងារបណ្តោះអាសន្ន">ផ្អាកការងារបណ្តោះអាសន្ន</option>
                            <option value="ឈប់ពីការងារ">ឈប់ពីការងារ</option>
                            <option value="ប្តូរកាតថ្មី">ប្តូរកាតថ្មី</option>
                        </select>
                    </div>

                    <div class="alert alert-warning border-0 small text-start py-2 px-3 mb-0">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        នៅពេលបិទកាតនេះ បុគ្គលិកនឹងមិនអាចយកកាតនេះមកស្កេនវត្តមានបានទៀតឡើយ។
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-bold px-4">
                        <i class="bi bi-lock-fill me-1"></i>បិទកាតឥឡូវនេះ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="generateCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-warning text-dark p-3 px-4">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-person-vcard-fill fs-5"></i>
                    <span>បង្កើតកាតសម្គាល់ថ្មី</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="generateCardForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning-emphasis mb-3 shadow-xs" style="width: 62px; height: 62px; font-size: 1.8rem;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="generateEmpName">ឈ្មោះបុគ្គលិក</h5>
                    <p class="text-muted small mb-3" id="generateCardNumWrapper">
                        កាតបច្ចុប្បន្ន៖ <strong id="generateCardNum" class="font-monospace text-warning-emphasis bg-warning-subtle px-2 py-0.5 rounded border border-warning-subtle">CRD-0000</strong>
                    </p>

                    <div id="generateOldCardWarning" class="alert alert-warning border-0 rounded-3 text-start small mb-3 py-2.5 px-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-5 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <div class="fw-bold text-dark mb-1">ការព្រមានអំពីសុពលភាពកាតចាស់</div>
                                <div class="text-secondary" style="font-size: 0.82rem; line-height: 1.45;">
                                    តើអ្នកប្រាកដជាចង់បង្កើតកាតថ្មីជូនបុគ្គលិកនេះមែនទេ? <strong class="text-dark">កាតចាស់នឹងត្រូវបានបិទដំណើរការស្វ័យប្រវត្តិ</strong> ភ្លាមៗ ដើម្បីសុវត្ថិភាព និងការពារការស្កេនជាន់គ្នា!
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-2.5 bg-light rounded-3 text-start small text-muted d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-success fs-5 flex-shrink-0"></i>
                        <span style="font-size: 0.8rem;">លេខកូដ QR សម្ងាត់ថ្មីនឹងត្រូវបានបង្កើតឡើង និងដាក់ឱ្យប្រើប្រាស់ភ្លាមៗ។</span>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>បោះបង់
                    </button>
                    <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold px-4 rounded-pill shadow-xs">
                        <i class="bi bi-check2-circle me-1"></i>បង្កើតកាតថ្មីឥឡូវនេះ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="activateCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-success text-white p-3 px-4">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-unlock-fill fs-5"></i>
                    <span>បើកដំណើរការកាតឡើងវិញ</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="activateCardForm" method="POST" action="">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success-subtle text-success mb-3 shadow-xs" style="width: 62px; height: 62px; font-size: 1.8rem;">
                        <i class="bi bi-unlock-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="activateEmpName">ឈ្មោះបុគ្គលិក</h5>
                    <p class="text-muted small mb-3">
                        លេខកាត៖ <strong id="activateCardNum" class="font-monospace text-success bg-success-subtle px-2 py-0.5 rounded border border-success-subtle">CRD-0000</strong>
                    </p>

                    <div class="alert alert-success-subtle border-0 rounded-3 text-start small mb-0 py-2.5 px-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill text-success fs-5 flex-shrink-0 mt-0.5"></i>
                            <div class="text-dark" style="font-size: 0.82rem; line-height: 1.45;">
                                តើអ្នកចង់បើកដំណើរការកាតនេះឡើងវិញមែនទេ? បុគ្គលិកនឹងអាចយកកាតនេះមកស្កេនកត់ត្រាវត្តមានចូល-ចេញការងារបានធម្មតាវិញ។
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>បោះបង់
                    </button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold px-4 rounded-pill shadow-xs">
                        <i class="bi bi-check2-circle me-1"></i>បើកដំណើរការឡើងវិញ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="copyTokenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden text-center">
            <div class="modal-header border-0 bg-success bg-gradient text-white p-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-clipboard-check-fill fs-5"></i>
                    <span>ចម្លង Token ជោគជ័យ</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success-subtle text-success mb-3 shadow-xs" style="width: 64px; height: 64px; font-size: 2rem;">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1" id="copyModalEmpName">ឈ្មោះបុគ្គលិក</h5>
                <p class="text-success fw-bold small mb-3">
                    <i class="bi bi-check-circle-fill me-1"></i>បានចម្លង Token សម្រាប់ស្កេនរួចរាល់!
                </p>

                <div class="bg-light rounded-3 p-2.5 border text-start mb-3">
                    <label class="form-label small text-muted mb-1 fw-semibold" style="font-size: 0.75rem;">លេខកូដសម្ងាត់ Token៖</label>
                    <div class="d-flex align-items-center justify-content-between gap-2 bg-white px-2.5 py-1.5 rounded border">
                        <code class="font-monospace text-primary text-break small user-select-all" id="copyModalTokenValue"></code>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-monospace" style="font-size: 0.68rem;">បានចម្លង</span>
                    </div>
                </div>

                <div class="alert alert-info border-0 rounded-3 text-start small mb-0 py-2 px-3" style="font-size: 0.8rem;">
                    <i class="bi bi-info-circle-fill text-info me-1"></i>
                    លោកអ្នកអាចយក Token នេះទៅបិទភ្ជាប់ (Paste) ក្នុងប្រព័ន្ធស្កេនវត្តមាន ឬបញ្ចូលដោយដៃបាន។
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3 px-4 d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-success fw-bold px-4 rounded-pill shadow-xs w-100" data-bs-dismiss="modal">
                    <i class="bi bi-check-lg me-1"></i>យល់ព្រម
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
const staffCardsBaseUrl = "<?php echo e(url('staff-attendance/cards')); ?>";

function copyCardToken(token, empName, btnEl) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(token);
    } else {
        const textarea = document.createElement('textarea');
        textarea.value = token;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }

    if (btnEl) {
        const icon = btnEl.querySelector('i');
        if (icon) {
            const oldClass = icon.className;
            icon.className = 'bi bi-check2 text-success fw-bold';
            setTimeout(() => {
                icon.className = oldClass;
            }, 2500);
        }
    }

    const empNameEl = document.getElementById('copyModalEmpName');
    const tokenValEl = document.getElementById('copyModalTokenValue');
    if (empNameEl) empNameEl.textContent = empName || 'បុគ្គលិក';
    if (tokenValEl) tokenValEl.textContent = token;

    const modalEl = document.getElementById('copyTokenModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function openDeactivateModal(cardId, empName, cardNum) {
    const nameEl = document.getElementById('deactivateEmpName');
    const numEl = document.getElementById('deactivateCardNum');
    if (nameEl) nameEl.textContent = empName;
    if (numEl) numEl.textContent = cardNum;
    const form = document.getElementById('deactivateCardForm');
    if (form) form.action = staffCardsBaseUrl + '/' + cardId + '/toggle-status';
    const modalEl = document.getElementById('deactivateCardModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function openGenerateModal(empId, empName, currentCardNum) {
    const nameEl = document.getElementById('generateEmpName');
    const numEl = document.getElementById('generateCardNum');
    const wrapperEl = document.getElementById('generateCardNumWrapper');
    const warningBox = document.getElementById('generateOldCardWarning');
    const form = document.getElementById('generateCardForm');

    if (nameEl) nameEl.textContent = empName;
    if (form) form.action = staffCardsBaseUrl + '/' + empId + '/generate';

    if (currentCardNum) {
        if (numEl) numEl.textContent = currentCardNum;
        if (wrapperEl) wrapperEl.classList.remove('d-none');
        if (warningBox) warningBox.classList.remove('d-none');
    } else {
        if (wrapperEl) wrapperEl.classList.add('d-none');
        if (warningBox) warningBox.classList.add('d-none');
    }

    const modalEl = document.getElementById('generateCardModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function openActivateModal(cardId, empName, cardNum) {
    const nameEl = document.getElementById('activateEmpName');
    const numEl = document.getElementById('activateCardNum');
    if (nameEl) nameEl.textContent = empName;
    if (numEl) numEl.textContent = cardNum;
    const form = document.getElementById('activateCardForm');
    if (form) form.action = staffCardsBaseUrl + '/' + cardId + '/toggle-status';
    const modalEl = document.getElementById('activateCardModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

// Master Digital Clock ticking in header
setInterval(function() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
    const masterClock = document.getElementById('terminalMasterClock');
    if (masterClock) masterClock.textContent = timeStr;
}, 1000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\School\laravel\resources\views/employees/attendance/index.blade.php ENDPATH**/ ?>