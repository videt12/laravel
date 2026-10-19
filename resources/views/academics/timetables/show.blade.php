@extends('layouts.app')

@php
    $canEditTimetable = auth()->user()?->canAccess('timetables.edit') ?? false;
    $isReadOnlyView = ! $canEditTimetable || request()->boolean('preview');
@endphp

@section('title', 'Timetable: ' . ($timetable->class->grade->name ?? 'Class') . ' ' . ($timetable->class->section->name ?? '') . ' — School Management')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">ផ្ទាំងគ្រប់គ្រង</a></li>
        <li class="breadcrumb-item text-muted">ការសិក្សា</li>
        <li class="breadcrumb-item"><a href="{{ route('timetables.index') }}" class="text-decoration-none text-muted">កាលវិភាគ</a></li>
        <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">
            {{ $timetable->class->grade->name ?? 'ថ្នាក់' }} — {{ $timetable->class->section->name ?? 'បន្ទប់' }}
        </li>
    </ol>
</nav>
@endsection

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Kantumruy+Pro:wght@400;600;700&family=Moul&family=Siemreap&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* ─────────────────────────────────────────────────────────────
   KHMER OFFICIAL TYPOGRAPHY SYSTEM
───────────────────────────────────────────────────────────── */
.font-moul {
    font-family: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif !important;
}
.font-siemreap {
    font-family: 'Siemreap', 'Battambang', 'Khmer OS Siemreap', 'Kantumruy Pro', sans-serif !important;
}
.font-battambang {
    font-family: 'Battambang', 'Siemreap', 'Khmer OS Battambang', 'Kantumruy Pro', sans-serif !important;
}

/* ─────────────────────────────────────────────────────────────
   TIMETABLE VIEW MODERN DESIGN SYSTEM
───────────────────────────────────────────────────────────── */
.timetable-hero-banner {
    background: #ffffff;
    border: 1px solid var(--border-soft, #cbd5e1);
    border-top: 4px solid var(--gold, #f59e0b);
    border-radius: 0px !important;
    padding: 0.75rem 1.15rem;
    margin-bottom: 0.65rem;
    position: relative;
    overflow: hidden;
    color: var(--text-dark, #0f172a);
    box-shadow: 0 1px 3px rgba(0,0,0,.03);
}
.timetable-hero-banner::before,
.timetable-hero-banner::after {
    display: none;
}

.kpi-card {
    background: #fff;
    border: 1px solid var(--border-soft, #cbd5e1);
    border-radius: 0px !important;
    padding: 0.55rem 0.85rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.03);
    transition: transform .15s ease, box-shadow .15s ease;
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.06);
}
.kpi-icon {
    width: 36px;
    height: 36px;
    border-radius: 0px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

/* ─────────────────────────────────────────────────────────────
   TIMETABLE MATRIX GRID
───────────────────────────────────────────────────────────── */
.timetable-grid-wrapper {
    background: #fff;
    border: 1px solid var(--border-soft, #cbd5e1);
    border-radius: 0px !important;
    box-shadow: 0 2px 10px rgba(0,0,0,.03);
    overflow: hidden;
}

.timetable-matrix {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-family: 'Siemreap', 'Battambang', 'Khmer OS Siemreap', 'Kantumruy Pro', sans-serif;
}

.timetable-matrix th,
.timetable-matrix td {
    border-right: 1px solid #eef2f6;
    border-bottom: 1px solid #eef2f6;
    padding: 0.75rem 0.65rem;
    vertical-align: top;
}
.timetable-matrix th:last-child,
.timetable-matrix td:last-child {
    border-right: none;
}
.timetable-matrix tr:last-child td {
    border-bottom: none;
}

.timetable-matrix thead th {
    background: #f8fafc;
    color: #1e293b;
    font-size: 0.95rem;
    font-weight: 700;
    font-family: 'Siemreap', 'Battambang', 'Khmer OS Siemreap', 'Kantumruy Pro', sans-serif;
    letter-spacing: 0.3px;
    text-align: center;
    padding: 0.9rem 0.65rem;
    position: sticky;
    top: 0;
    z-index: 10;
    line-height: 1.4;
}

.slot-time-col {
    width: 145px;
    min-width: 135px;
    background: #f8fafc;
    text-align: center;
    font-weight: 600;
}
.slot-period-badge {
    font-size: 0.825rem;
    letter-spacing: 0.3px;
    font-weight: 700;
    text-transform: uppercase;
    color: #475569;
    margin-bottom: 0.25rem;
    line-height: 1.35;
}
.slot-time-range {
    font-size: 0.875rem;
    color: #0f172a;
    font-weight: 600;
}

/* Break Rows */
.break-row td {
    background: #fefce8 !important;
    border-top: 1px dashed #fde047;
    border-bottom: 1px dashed #fde047;
    padding: 0.75rem 1rem !important;
}
.break-banner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #854d0e;
    font-weight: 600;
    font-size: 0.925rem;
}

/* Slot Cell & Cards */
.slot-cell {
    min-width: 160px;
    height: 105px;
    position: relative;
    transition: background-color 0.15s ease;
}
.slot-cell:hover {
    background-color: #f8fafc;
}

.schedule-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid var(--accent-color, #3b82f6);
    border-radius: 8px;
    padding: 0.75rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .15s ease;
}
.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,.08);
}
.schedule-card .subject-title {
    font-family: 'Siemreap', 'Battambang', 'Khmer OS Siemreap', 'Kantumruy Pro', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 0;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.schedule-card .subject-title i {
    font-size: 0.85rem;
    color: var(--accent-color, #2563eb);
    flex-shrink: 0;
}
.schedule-card .subject-code {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.18rem 0.45rem;
    border-radius: 4px;
    background: #f1f5f9;
    color: #475569;
    display: inline-block;
}
.schedule-card .card-meta {
    font-size: 0.8125rem;
    color: #64748b;
    margin-top: 0.4rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    line-height: 1.3;
}
.schedule-card .meta-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.schedule-card .meta-item i {
    color: #475569;
    font-size: 0.825rem;
}
.schedule-card .card-actions {
    position: absolute;
    top: 0.35rem;
    right: 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.15s ease;
    z-index: 2;
}
.schedule-card:hover .card-actions {
    opacity: 1;
}
.schedule-card .card-action-btn {
    border: none;
    border-radius: 4px;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    line-height: 1;
    padding: 0;
    cursor: pointer;
    transition: all 0.15s ease;
}
.schedule-card .card-edit-btn {
    background: rgba(37, 99, 235, 0.1);
    color: #2563eb;
}
.schedule-card .card-edit-btn:hover {
    background: #2563eb;
    color: #fff;
}
.schedule-card .card-delete-btn {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}
.schedule-card .card-delete-btn:hover {
    background: #ef4444;
    color: #fff;
}

/* Empty Cell Add Button */
.empty-slot-btn {
    width: 100%;
    height: 100%;
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    background: transparent;
    color: #94a3b8;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    font-size: 0.825rem;
    transition: all 0.15s ease;
    cursor: pointer;
    padding: 0.5rem;
}
.empty-slot-btn:hover {
    border-color: #3b82f6;
    color: #2563eb;
    background: #eff6ff;
}

/* ─────────────────────────────────────────────────────────────
   PRINT MEDIA STYLES (OPTIMIZED FOR F4 / A4 / GOVERNMENT SIZE)
───────────────────────────────────────────────────────────── */
.print-official-header,
.print-signatures-footer {
    display: none;
}

@media print {
    @page {
        size: auto; /* Adapts cleanly to F4 (210x330mm) or A4 in portrait/landscape */
        margin: 8mm 10mm 10mm 10mm;
    }

    html, body {
        background: #fff !important;
        color: #000 !important;
        font-family: 'Inter', 'Kantumruy Pro', 'Battambang', 'Khmer OS', 'Siemreap', sans-serif !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Hide ALL UI elements: sidebar, topbar, buttons, banner, tabs, modals */
    .app-sidebar,
    .app-topbar,
    .sidebar-backdrop,
    .topbar-search,
    .topbar-breadcrumb,
    nav,
    .breadcrumb,
    .timetable-hero-banner,
    .no-print,
    .banner-actions,
    .kpi-card,
    .nav-pills,
    .nav-tabs,
    .btn,
    .empty-slot-btn,
    .card-delete-btn,
    .alert,
    .modal,
    .modal-backdrop,
    footer,
    #printPreviewFloatingBar,
    #list-view {
        display: none !important;
    }

    /* Reset layout wrappers to 100% full width and force pure white background */
    #app-layout {
        display: block !important;
        width: 100% !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
    }

    .app-main-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        display: block !important;
        min-height: auto !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
    }

    .main-content,
    .app-content,
    .container-fluid,
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
    }

    .tab-content,
    .tab-pane {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
    }

    /* OFFICIAL KHMER SCHOOL PRINT HEADER */
    .print-official-header {
        display: block !important;
        width: 100% !important;
        margin-bottom: 12px !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
    }
    .print-header-top {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        width: 100% !important;
        background: #ffffff !important;
    }
    .print-school-title {
        text-align: left !important;
        background: #ffffff !important;
    }
    .print-school-title .school-logo {
        height: 80px !important;
        width: auto !important;
        object-fit: contain !important;
        margin-bottom: 5px !important;
        display: block !important;
    }
    .print-school-title .school-kh {
        font-family: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif !important;
        font-size: 13pt !important;
        font-weight: normal !important;
        color: #1e3a8a !important;
        line-height: 1.4 !important;
    }
    .print-school-title .school-en {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
        font-size: 10pt !important;
        font-weight: 700 !important;
        color: #dc2626 !important;
        line-height: 1.2 !important;
    }
    .print-kingdom-title {
        text-align: center !important;
        background: #ffffff !important;
    }
    .print-kingdom-title .kingdom-kh {
        font-family: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif !important;
        font-size: 12pt !important;
        font-weight: normal !important;
        color: #000000 !important;
        line-height: 1.6 !important;
    }
    .print-kingdom-title .kingdom-main {
        margin-bottom: 7px !important;
    }
    .print-motto-divider {
        margin-top: 6px !important;
        line-height: 1 !important;
        text-align: center !important;
    }
    .print-motto-divider img {
        display: inline-block !important;
        height: 13px !important;
        width: auto !important;
        max-width: 95px !important;
        object-fit: contain !important;
    }
    .print-main-title {
        text-align: center !important;
        font-family: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif !important;
        font-size: 14pt !important;
        font-weight: normal !important;
        color: #000000 !important;
        margin: 10px 0 4px 0 !important;
        line-height: 1.5 !important;
        background: #ffffff !important;
    }
    .print-main-title .title-paren {
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Inter', sans-serif !important;
        font-weight: normal !important;
        font-size: 13pt !important;
        display: inline-block !important;
        vertical-align: baseline !important;
        color: #000000 !important;
    }
    .print-academic-title {
        text-align: center !important;
        font-family: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif !important;
        font-size: 12pt !important;
        font-weight: normal !important;
        color: #000000 !important;
        margin-bottom: 12px !important;
        line-height: 1.5 !important;
        background: #ffffff !important;
    }

    /* TABLE FIT FOR F4 PAPER (100% NO HORIZONTAL SCROLL) */
    .table-responsive {
        overflow: visible !important;
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }

    .timetable-grid-wrapper {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
    }

    .timetable-matrix {
        width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        border: 1px solid #000000 !important;
        background-color: #ffffff !important;
    }

    .timetable-matrix th,
    .timetable-matrix td {
        border: 1px solid #000000 !important;
        padding: 5px 3px !important;
        vertical-align: middle !important;
        text-align: center !important;
        overflow: visible !important;
        word-wrap: break-word !important;
        background-color: #ffffff !important;
        position: static !important;
    }

    .timetable-matrix thead th {
        background-color: #ffffff !important;
        color: #000000 !important;
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
        font-weight: 700 !important;
        font-size: 11pt !important;
        position: static !important;
        height: 38px !important;
        padding: 6px 2px !important;
        border: 1px solid #000000 !important;
        line-height: 1.4 !important;
    }

    .timetable-matrix thead th div:first-child {
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
        font-size: 11pt !important;
        font-weight: 700 !important;
        color: #000000 !important;
    }

    /* 6 COLUMNS: 15% time column, 17% per day column (Total = 100%) */
    .slot-time-col {
        width: 15% !important;
        min-width: 0 !important;
        background-color: #ffffff !important;
        border: 1px solid #000000 !important;
        position: static !important;
    }

    .timetable-matrix th:not(.slot-time-col) {
        width: 17% !important;
        min-width: 0 !important;
        background-color: #ffffff !important;
        border: 1px solid #000000 !important;
        position: static !important;
    }

    .slot-cell {
        min-width: 0 !important;
        height: auto !important;
        min-height: 52px !important;
        padding: 5px 2px !important;
        background-color: #ffffff !important;
        border: 1px solid #000000 !important;
        position: static !important;
        vertical-align: middle !important;
    }

    .slot-period-badge {
        display: none !important;
    }

    .slot-time-range {
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
        font-size: 11pt !important;
        color: #000000 !important;
        font-weight: 700 !important;
        background: transparent !important;
        line-height: 1.3 !important;
        display: block !important;
        text-align: center !important;
    }

    .slot-time-range span {
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
        font-weight: 700 !important;
    }

    /* CARD INSIDE CELL - MATCHING OFFICIAL SAMPLE */
    .schedule-card {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        height: 100% !important;
        width: 100% !important;
        background: transparent !important;
        background-color: transparent !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        position: static !important;
    }

    .schedule-card .subject-title {
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
        font-size: 10.5pt !important;
        font-weight: 700 !important;
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1.3 !important;
        white-space: normal !important;
        text-align: center !important;
        background: transparent !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 3px !important;
    }

    /* IN PRINT: Display Teacher Name and Phone Number clearly */
    .schedule-card .subject-code,
    .schedule-card .card-actions {
        display: none !important;
    }

    .schedule-card .card-meta {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 1.5px !important;
        margin-top: 2px !important;
        padding: 0 !important;
        width: 100% !important;
        text-align: center !important;
    }

    .schedule-card .card-meta .meta-item {
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
        line-height: 1.25 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        white-space: normal !important;
        margin: 0 !important;
        padding: 0 !important;
        gap: 3px !important;
    }

    .schedule-card .card-meta .meta-classroom {
        font-size: 8pt !important;
        font-weight: 500 !important;
        color: #475569 !important;
    }

    .schedule-card .card-meta .meta-teacher {
        font-size: 8.5pt !important;
        font-weight: 600 !important;
        color: #111827 !important;
    }

    .schedule-card .card-meta .meta-phone {
        font-size: 7.8pt !important;
        font-weight: 500 !important;
        color: #374151 !important;
    }

    .schedule-card .card-meta i,
    .schedule-card .subject-title i {
        display: inline-block !important;
        font-size: 7.5pt !important;
        margin-right: 2px !important;
        opacity: 0.85 !important;
        vertical-align: middle !important;
    }

    /* BREAK ROW IN PRINT */
    .break-row td {
        background-color: #ffffff !important;
        background: #ffffff !important;
        border: 1px solid #000000 !important;
        padding: 5px !important;
        vertical-align: middle !important;
    }
    .break-banner {
        font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
        font-size: 9.5pt !important;
        color: #000000 !important;
        font-weight: 700 !important;
        background: transparent !important;
    }

    /* SIGNATURES FOOTER AT BOTTOM */
    .print-signatures-footer {
        display: flex !important;
        justify-content: flex-end !important;
        align-items: flex-start !important;
        width: 100% !important;
        margin-top: 18px !important;
        padding: 0 5px !important;
        page-break-inside: avoid !important;
        font-family: 'Siemreap', 'Battambang', sans-serif !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
    }
    .print-sig-block {
        text-align: center !important;
        font-family: 'Siemreap', 'Battambang', sans-serif !important;
        background: #ffffff !important;
        margin-left: auto !important;
    }
    .print-sig-block .sig-lunar-date,
    .print-sig-block .sig-solar-date {
        font-family: 'Siemreap', 'Battambang', sans-serif !important;
        font-size: 10pt !important;
        line-height: 1.5 !important;
        color: #000000 !important;
        font-weight: 600 !important;
        text-align: center !important;
    }
    .print-sig-block .sig-title {
        font-family: 'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif !important;
        font-size: 11pt !important;
        font-weight: normal !important;
        color: #000000 !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
        position: relative !important;
        z-index: 2 !important;
        text-align: center !important;
    }
    .print-sig-block .stamp-signature-wrap {
        position: relative !important;
        height: auto !important;
        margin-top: -18px !important;
        z-index: 1 !important;
    }
    .print-sig-block .stamp-signature-img {
        height: 120px !important;
        width: auto !important;
        object-fit: contain !important;
        display: inline-block !important;
    }
    .print-sig-space {
        height: 55px !important;
        background: transparent !important;
    }
}

/* ─────────────────────────────────────────────────────────────
   ON-SCREEN PRINT PREVIEW MODE (Live Inspection On Screen)
───────────────────────────────────────────────────────────── */
html.print-preview-mode,
body.print-preview-mode,
html:has(body.print-preview-mode) {
    background: #ffffff !important;
    background-color: #ffffff !important;
}
body.print-preview-mode .app-sidebar,
body.print-preview-mode .app-topbar,
body.print-preview-mode nav[aria-label="breadcrumb"],
body.print-preview-mode .timetable-hero-banner,
body.print-preview-mode .kpi-card,
body.print-preview-mode .nav-pills,
body.print-preview-mode .nav-tabs,
body.print-preview-mode footer,
body.print-preview-mode .alert,
body.print-preview-mode .no-print {
    display: none !important;
}
body.print-preview-mode #app,
body.print-preview-mode #app-layout,
body.print-preview-mode .app-main-wrapper,
body.print-preview-mode .main-content,
body.print-preview-mode .app-content,
body.print-preview-mode .container-fluid,
body.print-preview-mode .content-wrapper,
body.print-preview-mode .tab-content {
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
    background-color: #ffffff !important;
    box-shadow: none !important;
    border: none !important;
    width: 100% !important;
}
body.print-preview-mode #printPreviewFloatingBar {
    display: flex !important;
    background: #ffffff !important;
    background-color: #ffffff !important;
    color: #0f172a !important;
    border: 1px solid #cbd5e1 !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15) !important;
    z-index: 999999 !important;
}
/* Modal & Backdrop Z-Indexes & Clickability */
.modal {
    z-index: 1055 !important;
}
.modal-backdrop {
    z-index: 1050 !important;
}
body.print-preview-mode .modal {
    z-index: 1000005 !important;
}
body.print-preview-mode .modal.show {
    display: block !important;
}
body.print-preview-mode .modal-backdrop {
    z-index: 1000000 !important;
}
body.print-preview-mode .modal-backdrop.show {
    opacity: 0.5 !important;
}
.modal-dialog {
    pointer-events: auto !important;
}
.modal-content {
    pointer-events: auto !important;
}
body.print-preview-mode #grid-view,
body.print-preview-mode #matrix-view {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    width: 100% !important;
    max-width: 1040px !important;
    margin: 65px auto 40px auto !important;
    background: #ffffff !important;
    background-color: #ffffff !important;
    padding: 30px 40px 40px 40px !important;
    box-shadow: none !important;
    border: 1px solid #e2e8f0 !important;
    box-sizing: border-box !important;
}
body.print-preview-mode .print-official-header,
body.print-preview-mode .print-signatures-footer {
    display: block !important;
    background: #ffffff !important;
    background-color: #ffffff !important;
}
body.print-preview-mode .print-signatures-footer {
    cursor: pointer !important;
}
body.print-preview-mode .print-header-top {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    width: 100% !important;
}
body.print-preview-mode .print-school-title .school-logo {
    height: 80px !important;
    display: block !important;
}
body.print-preview-mode .print-school-title .school-kh {
    font-size: 13pt !important;
    color: #1e3a8a !important;
}
body.print-preview-mode .print-kingdom-title .kingdom-kh {
    font-size: 12pt !important;
    color: #000000 !important;
}
body.print-preview-mode .print-main-title {
    font-size: 14pt !important;
    text-align: center !important;
}
body.print-preview-mode .print-main-title .title-paren {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Inter', sans-serif !important;
    font-weight: normal !important;
    font-size: 13pt !important;
    display: inline-block !important;
    vertical-align: baseline !important;
    color: #000000 !important;
}
body.print-preview-mode .print-academic-title {
    font-size: 12pt !important;
    text-align: center !important;
}
body.print-preview-mode .timetable-matrix {
    width: 100% !important;
    table-layout: fixed !important;
    border-collapse: collapse !important;
    border: 1px solid #000000 !important;
    background-color: #ffffff !important;
}
body.print-preview-mode .timetable-matrix th,
body.print-preview-mode .timetable-matrix td {
    border: 1px solid #000000 !important;
    padding: 6px 3px !important;
    vertical-align: middle !important;
    text-align: center !important;
}
body.print-preview-mode .timetable-matrix thead th {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-weight: 700 !important;
    font-size: 11pt !important;
    height: 38px !important;
    border: 1px solid #000000 !important;
}
body.print-preview-mode .timetable-matrix thead th div:first-child {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-size: 11pt !important;
    font-weight: 700 !important;
}
body.print-preview-mode .slot-time-col {
    width: 15% !important;
    border: 1px solid #000000 !important;
}
body.print-preview-mode .timetable-matrix th:not(.slot-time-col) {
    width: 17% !important;
    border: 1px solid #000000 !important;
}
body.print-preview-mode .slot-cell {
    height: auto !important;
    min-height: 52px !important;
    border: 1px solid #000000 !important;
    vertical-align: middle !important;
    padding: 5px 2px !important;
}
body.print-preview-mode .slot-time-range {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-size: 11pt !important;
    font-weight: 700 !important;
}
body.print-preview-mode .slot-time-range .d-none.d-print-inline {
    display: inline !important;
}
body.print-preview-mode .slot-time-range .d-print-none {
    display: none !important;
}
body.print-preview-mode .schedule-card {
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    height: 100% !important;
    background: transparent !important;
}
body.print-preview-mode .schedule-card .subject-title {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-size: 10.5pt !important;
    font-weight: 700 !important;
    color: #000000 !important;
    text-align: center !important;
    line-height: 1.3 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 3px !important;
}
body.print-preview-mode .schedule-card .card-meta {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 1.5px !important;
    margin-top: 2px !important;
    padding: 0 !important;
    width: 100% !important;
    text-align: center !important;
}
body.print-preview-mode .schedule-card .card-meta .meta-item {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    line-height: 1.25 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    white-space: normal !important;
    margin: 0 !important;
    padding: 0 !important;
    gap: 3px !important;
}
body.print-preview-mode .schedule-card .card-meta .meta-classroom {
    font-size: 8.2pt !important;
    font-weight: 500 !important;
    color: #475569 !important;
}
body.print-preview-mode .schedule-card .card-meta .meta-teacher {
    font-size: 8.8pt !important;
    font-weight: 600 !important;
    color: #111827 !important;
}
body.print-preview-mode .schedule-card .card-meta .meta-phone {
    font-size: 8.2pt !important;
    font-weight: 500 !important;
    color: #374151 !important;
}
body.print-preview-mode .schedule-card .card-meta i,
body.print-preview-mode .schedule-card .subject-title i {
    display: inline-block !important;
    font-size: 7.8pt !important;
    margin-right: 2px !important;
    opacity: 0.85 !important;
    vertical-align: middle !important;
}
body.print-preview-mode .schedule-card .subject-code,
body.print-preview-mode .schedule-card .card-actions {
    display: none !important;
}
body.print-preview-mode .print-signatures-footer {
    display: flex !important;
    justify-content: flex-end !important;
    width: 100% !important;
    margin-top: 24px !important;
}
body.print-preview-mode .print-sig-block .stamp-signature-wrap {
    margin-top: -18px !important;
}
body.print-preview-mode .print-sig-block .stamp-signature-img {
    height: 120px !important;
}
.agenda-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0.55rem;
}
@media (max-width: 1200px) {
    .agenda-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}
@media (max-width: 768px) {
    .agenda-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 540px) {
    .agenda-grid {
        grid-template-columns: 1fr;
    }
}
.agenda-card {
    background: #fff;
    border: 1px solid var(--border-soft, #cbd5e1);
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.03);
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* Floating Toast Alert to prevent pushing layout and page jumping */
.app-main-wrapper .container-fluid > .alert-dismissible {
    position: fixed !important;
    top: 75px !important;
    right: 24px !important;
    z-index: 999999 !important;
    min-width: 320px !important;
    max-width: 480px !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
    animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
    margin-bottom: 0 !important;
}

@keyframes toastSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.96);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes cellGlowEffect {
    0% {
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.6);
        outline: 2px solid #2563eb;
    }
    70% {
        box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.2);
        outline: 2px solid #2563eb;
    }
    100% {
        box-shadow: none;
        outline: none;
    }
}

.cell-highlight-saved {
    animation: cellGlowEffect 2s ease-out !important;
}

@if($isReadOnlyView)
/* ─────────────────────────────────────────────────────────────
   FOR VIEWERS / TEACHERS: FORCE CLEAN OFFICIAL TIMETABLE SHEET
───────────────────────────────────────────────────────────── */
html, body {
    background: #525659 !important;
    background-color: #525659 !important;
}
.app-sidebar,
.app-topbar,
nav[aria-label="breadcrumb"],
.timetable-hero-banner,
.kpi-card,
.nav-pills,
.nav-tabs,
footer,
.alert,
.no-print {
    display: none !important;
}
#printPreviewFloatingBar {
    display: flex !important;
}
#grid-view, #matrix-view {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    width: 100% !important;
    max-width: 1040px !important;
    margin: 65px auto 40px auto !important;
    background: #ffffff !important;
    background-color: #ffffff !important;
    padding: 30px 40px 40px 40px !important;
    box-shadow: none !important;
    border: 1px solid #e2e8f0 !important;
    box-sizing: border-box !important;
}
.print-official-header,
.print-signatures-footer {
    display: block !important;
    background: #ffffff !important;
    background-color: #ffffff !important;
}
.print-signatures-footer {
    cursor: default !important;
}
.print-header-top {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    width: 100% !important;
}
.print-school-title .school-logo {
    height: 80px !important;
    display: block !important;
}
.print-school-title .school-kh {
    font-size: 13pt !important;
    color: #1e3a8a !important;
}
.print-kingdom-title .kingdom-kh {
    font-size: 12pt !important;
    color: #000000 !important;
}
.print-main-title {
    font-size: 14pt !important;
    text-align: center !important;
}
.print-main-title .title-paren {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Inter', sans-serif !important;
    font-weight: normal !important;
    font-size: 13pt !important;
    display: inline-block !important;
    vertical-align: baseline !important;
    color: #000000 !important;
}
.print-academic-title {
    font-size: 12pt !important;
    text-align: center !important;
}
.timetable-matrix {
    width: 100% !important;
    table-layout: fixed !important;
    border-collapse: collapse !important;
    border: 1px solid #000000 !important;
    background-color: #ffffff !important;
}
.timetable-matrix th,
.timetable-matrix td {
    border: 1px solid #000000 !important;
    padding: 6px 3px !important;
    vertical-align: middle !important;
    text-align: center !important;
}
.timetable-matrix thead th {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-weight: 700 !important;
    font-size: 11pt !important;
    height: 38px !important;
    border: 1px solid #000000 !important;
}
.timetable-matrix thead th div:first-child {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-size: 11pt !important;
    font-weight: 700 !important;
}
.slot-time-col {
    width: 15% !important;
    border: 1px solid #000000 !important;
}
.timetable-matrix th:not(.slot-time-col) {
    width: 17% !important;
    border: 1px solid #000000 !important;
}
.slot-cell {
    height: auto !important;
    min-height: 52px !important;
    border: 1px solid #000000 !important;
    vertical-align: middle !important;
    padding: 5px 2px !important;
}
.slot-time-range {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-size: 11pt !important;
    font-weight: 700 !important;
}
.slot-time-range .d-none.d-print-inline {
    display: inline !important;
}
.slot-time-range .d-print-none {
    display: none !important;
}
.schedule-card {
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    height: 100% !important;
    background: transparent !important;
    cursor: default !important;
}
.schedule-card .subject-title {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    font-size: 10.5pt !important;
    font-weight: 700 !important;
    color: #000000 !important;
    text-align: center !important;
    line-height: 1.3 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 3px !important;
}
.schedule-card .card-meta {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 1.5px !important;
    margin-top: 2px !important;
    padding: 0 !important;
    width: 100% !important;
    text-align: center !important;
}
.schedule-card .card-meta .meta-item {
    font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Battambang', sans-serif !important;
    line-height: 1.25 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 3px !important;
}
.schedule-card .card-meta .meta-classroom {
    font-size: 8.2pt !important;
    font-weight: 500 !important;
    color: #475569 !important;
}
.schedule-card .card-meta i,
.schedule-card .subject-title i {
    display: inline-block !important;
    font-size: 8pt !important;
    margin-right: 2px !important;
    opacity: 0.85 !important;
    vertical-align: middle !important;
}
@endif
</style>
@if($isReadOnlyView)
<script>
    document.documentElement.classList.add('print-preview-mode');
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('print-preview-mode');
    });
</script>
@endif
@endpush

@section('content')

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 no-print" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> មានបញ្ហាក្នុងការរក្សាទុក (សូមពិនិត្យទម្រង់ខាងក្រោម)៖</div>
        <ul class="mb-0 ps-3 small">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- FLOATING PRINT PREVIEW BAR --}}
<div id="printPreviewFloatingBar" class="{{ $isReadOnlyView ? 'd-flex' : 'd-none' }} position-fixed top-0 start-50 translate-middle-x mt-3 bg-white text-dark py-2 px-3.5 shadow-sm rounded-pill align-items-center gap-2.5 border" style="z-index: 999999; background: #ffffff !important; border: 1px solid #cbd5e1 !important; box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;">
    <span class="small font-siemreap text-dark fw-bold d-flex align-items-center"><i class="bi bi-file-earmark-text text-primary me-1.5 fs-6"></i> កំពុងមើលទម្រង់បោះពុម្ព</span>
    <button type="button" onclick="window.print()" class="btn btn-sm btn-primary rounded-pill fw-bold px-3 py-1 text-white shadow-xs">
        <i class="bi bi-printer-fill me-1"></i> បោះពុម្ព (Print)
    </button>
    @if($canEditTimetable)
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-medium" data-bs-toggle="modal" data-bs-target="#editPrintDateModal">
        <i class="bi bi-calendar2-range me-1"></i> កែថ្ងៃខែ / ត្រា
    </button>
    <button type="button" onclick="togglePrintPreview()" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-medium">
        <i class="bi bi-x-lg me-1"></i> ត្រឡប់ទៅវិញ (Exit)
    </button>
    @else
    <button type="button" onclick="exitOrGoBack()" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-medium">
        <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅវិញ (Exit)
    </button>
    @endif
</div>

{{-- HERO BANNER --}}
@php
    $termName = $timetable->school_term->name ?? 'ឆមាស';
    $termMap = [
        'First Term' => 'ឆមាសទី ១',
        'Second Term' => 'ឆមាសទី ២',
        'Third Term' => 'ឆមាសទី ៣',
        'Term 1' => 'ឆមាសទី ១',
        'Term 2' => 'ឆមាសទី ២',
        'Term 3' => 'ឆមាសទី ៣',
        'Semester 1' => 'ឆមាសទី ១',
        'Semester 2' => 'ឆមាសទី ២',
    ];
    $displayTerm = $termMap[$termName] ?? $termName;

    $sectionName = $timetable->class->section->name ?? '';
    $shiftName = ($timetableShift ?? 'morning') === 'afternoon' ? 'ពេលរសៀល' : 'ពេលព្រឹក';
    $hasShift = str_contains($sectionName, 'ពេលព្រឹក') || str_contains($sectionName, 'ពេលរសៀល');

    // Find assigned classroom(s) outside timetable grid
    $entryClassrooms = $timetable->timetable_entries->pluck('classroom')->filter()->unique('id');
    $timetableClassroom = $timetable->class->classroom ?? $entryClassrooms->first();
    $classroomName = $timetableClassroom?->name;
    if (!$classroomName && $entryClassrooms->isNotEmpty()) {
        $classroomName = $entryClassrooms->pluck('name')->join(', ');
    }

    // Find assigned teacher(s) outside timetable grid
    $entryTeachers = $timetable->timetable_entries->pluck('teacher')->filter()->unique('id');
    $primaryTeacher = $entryTeachers->first();

    if (!$primaryTeacher) {
        $assignedTeacher = \App\Models\Academics\TeacherAssignment::with(['employee.user', 'employee.employeeContact'])
            ->where('class_id', $timetable->class_id)
            ->when($timetable->academic_year_id, fn($q) => $q->where('academic_year_id', $timetable->academic_year_id))
            ->first()?->employee;
        if (!$assignedTeacher) {
            $assignedTeacher = \App\Models\Academics\TeacherAssignment::with(['employee.user', 'employee.employeeContact'])
                ->where('class_id', $timetable->class_id)
                ->first()?->employee;
        }
        if ($assignedTeacher) {
            $primaryTeacher = $assignedTeacher;
        }
    }

    $teacherNamesList = $entryTeachers->map(function($teacher) {
        $tName = $teacher->user?->name ?? $teacher->name ?? trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? ''));
        $tPhone = $teacher->phone ?? $teacher->user?->phone ?? $teacher->employeeContact?->phone;
        return $tName . ($tPhone ? " ({$tPhone})" : '');
    })->values();

    $teachersDisplay = $teacherNamesList->join(', ');
    $primaryTeacherName = $primaryTeacher ? ($primaryTeacher->user?->name ?? $primaryTeacher->name ?? trim(($primaryTeacher->first_name ?? '') . ' ' . ($primaryTeacher->last_name ?? ''))) : null;
    $primaryTeacherPhone = $primaryTeacher ? ($primaryTeacher->phone ?? $primaryTeacher->user?->phone ?? $primaryTeacher->employeeContact?->phone) : null;
@endphp

<div class="timetable-hero-banner">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 position-relative" style="z-index: 2;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" style="border-radius:0px !important; font-size: 0.78rem;">
                    <i class="bi bi-mortarboard-fill me-1 text-warning"></i> កាលវិភាគថ្នាក់រៀន
                </span>
                <span class="badge bg-light text-dark border px-2 py-1" style="border-radius:0px !important; font-size: 0.78rem;">
                    <i class="bi bi-bookmark-star me-1 text-primary"></i> {{ $displayTerm }}
                </span>
                @if(($timetableShift ?? 'morning') === 'afternoon')
                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-1 fw-bold" style="border-radius:0px !important; font-size: 0.78rem;">
                        <i class="bi bi-cloud-sun-fill me-1 text-warning"></i> ថ្នាក់ពេលរសៀល (13:00 – 16:00)
                    </span>
                @else
                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1 fw-bold" style="border-radius:0px !important; font-size: 0.78rem;">
                        <i class="bi bi-sun-fill text-warning me-1"></i> ថ្នាក់ពេលព្រឹក (07:00 – 10:00)
                    </span>
                @endif
                @if($classroomName)
                    <span class="badge bg-light text-dark border px-2 py-1" style="border-radius:0px !important; font-size: 0.78rem;" title="បន្ទប់សិក្សា">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $classroomName }}
                    </span>
                @endif
                @if($primaryTeacherName)
                    <span class="badge bg-light text-dark border px-2 py-1" style="border-radius:0px !important; font-size: 0.78rem;" title="គ្រូបង្រៀន">
                        <i class="bi bi-person-fill text-primary me-1"></i> {{ $primaryTeacherName }}{{ $primaryTeacherPhone ? " ({$primaryTeacherPhone})" : '' }}
                    </span>
                @endif
            </div>
            @php
                $heroTitleText = ($timetable->class->grade->name ?? 'ថ្នាក់') . ' — ' . $sectionName . (!$hasShift ? " ({$shiftName})" : '');
                $styledHeroTitle = str_replace(
                    ['(', ')'], 
                    [
                        '<span style="font-family: \'Siemreap\', \'Kantumruy Pro\', sans-serif !important; font-weight: normal !important;">(</span>',
                        '<span style="font-family: \'Siemreap\', \'Kantumruy Pro\', sans-serif !important; font-weight: normal !important;">)</span>'
                    ], 
                    $heroTitleText
                );
            @endphp
            <h1 class="h3 fw-bold text-dark mb-1 font-khmer-moul" style="font-family: var(--font-moul, 'Moul', 'Khmer OS Muol', serif) !important; font-size: 1.25rem; line-height: 1.35;">
                {!! $styledHeroTitle !!}
            </h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-calendar3 me-1 text-warning"></i> ឆ្នាំសិក្សា៖ <strong class="text-dark">{{ $timetable->academic_year->name ?? '—' }}</strong>
                @if($classroomName)
                    <span class="mx-2">•</span>
                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i> បន្ទប់សិក្សា៖ <strong class="text-dark">{{ $classroomName }}</strong>
                @endif
                @if($primaryTeacherName)
                    <span class="mx-2">•</span>
                    <i class="bi bi-person-fill me-1 text-primary"></i> គ្រូបង្រៀន៖ <strong class="text-dark">{{ $teachersDisplay ?: $primaryTeacherName }}</strong>
                @endif
                <span class="mx-2">•</span>
                <i class="bi bi-clock-history me-1 text-warning"></i> ម៉ោងសិក្សា៖ <strong class="text-dark">{{ $teachingSlotsCount }} ម៉ោង / ថ្ងៃ</strong>
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2 banner-actions no-print">
            <a href="{{ route('timetables.index') }}" class="btn btn-sm btn-outline-secondary px-2.5 py-1 shadow-xs d-flex align-items-center" style="border-radius: 0px !important; font-size: 0.82rem;">
                <i class="bi bi-arrow-left me-1"></i> កាលវិភាគ
            </a>
            <button type="button" onclick="togglePrintPreview()" class="btn btn-sm btn-outline-success px-2.5 py-1 shadow-xs d-flex align-items-center fw-semibold" style="border-radius: 0px !important; font-size: 0.82rem;" id="btnTogglePreview">
                <i class="bi bi-eye-fill me-1.5"></i> មើលទម្រង់បោះពុម្ព (Preview)
            </button>
            <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-primary px-2.5 py-1 shadow-xs d-flex align-items-center fw-semibold" style="border-radius: 0px !important; font-size: 0.82rem;">
                <i class="bi bi-printer-fill me-1.5"></i> បោះពុម្ព (Print F4/A4)
            </button>
            @if(auth()->user()?->canAccess('timetables.edit'))
            <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1 shadow-xs d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#editPrintDateModal" style="border-radius: 0px !important; font-size: 0.82rem;" title="កែប្រែកាលបរិច្ឆេទសម្រាប់បោះពុម្ព">
                <i class="bi bi-calendar2-range me-1"></i> កែថ្ងៃខែបោះពុម្ព
            </button>
            <a href="{{ route('timetables.edit', $timetable) }}" class="btn btn-sm btn-outline-secondary px-2.5 py-1 shadow-xs d-flex align-items-center" style="border-radius: 0px !important; font-size: 0.82rem;">
                <i class="bi bi-gear me-1"></i> ការកំណត់
            </a>
            @endif
            @if(auth()->user()?->canAccess('timetables.create'))
            <button type="button" class="btn btn-sm btn-primary-action px-2.5 py-1 shadow-xs d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addEntryModal" style="border-radius: 0px !important; font-size: 0.82rem;">
                <i class="bi bi-plus-lg me-1"></i> + បន្ថែមម៉ោង
            </button>
            @endif
        </div>
    </div>
</div>

{{-- KPI METRICS CARDS --}}
<div class="row g-2 mb-2.5 no-print">
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card d-flex align-items-center gap-2.5">
            <div class="kpi-icon" style="background:#eff6ff;color:#2563eb;">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium" style="font-size: 0.78rem;">ម៉ោងសិក្សាសរុប (ប្រចាំខែ)</div>
                <div class="fs-5 fw-bold text-dark lh-1 mt-0.5" title="{{ $totalEntries }} ម៉ោង/សប្ដាហ៍">{{ $totalEntries * 4 }} <span class="small fw-normal text-muted" style="font-size: 0.78rem;">ម៉ោង/ខែ</span></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card d-flex align-items-center gap-2.5">
            <div class="kpi-icon" style="background:#ecfdf5;color:#059669;">
                <i class="bi bi-journal-bookmark"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium" style="font-size: 0.78rem;">មុខវិជ្ជាសរុប</div>
                <div class="fs-5 fw-bold text-dark lh-1 mt-0.5">{{ $uniqueSubjects }} <span class="small fw-normal text-muted" style="font-size: 0.78rem;">មុខ</span></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card d-flex align-items-center gap-2.5">
            <div class="kpi-icon" style="background:#fef3c7;color:#d97706;">
                <i class="bi bi-person-video3"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium" style="font-size: 0.78rem;">គ្រូបង្រៀនទទួលបន្ទុក</div>
                <div class="fs-5 fw-bold text-dark lh-1 mt-0.5" title="{{ $teachersDisplay }}">{{ $primaryTeacherName ?? ($uniqueTeachers . ' នាក់') }} @if($primaryTeacherPhone)<span class="small fw-normal text-muted" style="font-size: 0.78rem;">({{ $primaryTeacherPhone }})</span>@endif</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card d-flex align-items-center gap-2.5">
            <div class="kpi-icon" style="background:#f3e8ff;color:#7c3aed;">
                <i class="bi bi-door-open"></i>
            </div>
            <div>
                <div class="text-muted small fw-medium" style="font-size: 0.78rem;">បន្ទប់សិក្សា</div>
                <div class="fs-5 fw-bold text-dark lh-1 mt-0.5">{{ $classroomName ?? ($timetable->class->section->name ?? 'A') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- VIEW TABS NAVIGATION --}}
<div class="d-flex justify-content-between align-items-center mb-2 no-print">
    <ul class="nav nav-pills bg-white p-1 rounded-2 border shadow-xs" id="timetableTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active py-1 px-3 fw-medium text-dark small" id="grid-view-tab" data-bs-toggle="pill" data-bs-target="#grid-view" type="button" role="tab">
                <i class="bi bi-grid-3x3-gap me-1 text-primary"></i> កាលវិភាគប្រចាំខែ
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link py-1 px-3 fw-medium text-dark small" id="list-view-tab" data-bs-toggle="pill" data-bs-target="#list-view" type="button" role="tab">
                <i class="bi bi-list-columns-reverse me-1 text-success"></i> បញ្ជីលម្អិត
            </button>
        </li>
    </ul>

    <div class="text-muted small d-none d-md-flex align-items-center gap-2" style="font-size: 0.8rem;">
        @if($classroomName)
            <span class="badge bg-white text-dark border px-2.5 py-1 me-2 shadow-2xs d-flex align-items-center gap-1 font-siemreap" style="border-radius: 4px; font-size: 0.82rem;">
                <i class="bi bi-geo-alt-fill text-danger"></i>
                <span class="text-muted">បន្ទប់សិក្សា៖</span>
                <strong class="text-dark">{{ $classroomName }}</strong>
            </span>
        @endif
        @if($primaryTeacherName)
            <span class="badge bg-white text-dark border px-2.5 py-1 me-2 shadow-2xs d-flex align-items-center gap-1 font-siemreap" style="border-radius: 4px; font-size: 0.82rem;">
                <i class="bi bi-person-fill text-primary"></i>
                <span class="text-muted">គ្រូបង្រៀន៖</span>
                <strong class="text-dark">{{ $primaryTeacherName }}{{ $primaryTeacherPhone ? " ({$primaryTeacherPhone})" : '' }}</strong>
            </span>
        @endif
        <span class="d-inline-block rounded-circle" style="width:9px;height:9px;background:#3b82f6"></span> ម៉ោងបង្រៀន
        <span class="d-inline-block rounded-circle ms-2" style="width:9px;height:9px;background:#eab308"></span> ម៉ោងសម្រាក
    </div>
</div>

<div class="tab-content" id="timetableTabContent">
    {{-- TAB 1: WEEKLY GRID MATRIX --}}
    <div class="tab-pane fade show active" id="grid-view" role="tabpanel">
        {{-- OFFICIAL PRINT HEADER (Visible ONLY during print) --}}
        @php
            $toKhmerNum = function($str) {
                if ($str === null || $str === '') return '';
                $arabic = ['0','1','2','3','4','5','6','7','8','9'];
                $khmer  = ['០','១','២','៣','៤','៥','៦','៧','៨','៩'];
                return str_replace($arabic, $khmer, (string)$str);
            };

            $rawGrade = $timetable->class->grade->name ?? '០១';
            $cleanGradeOnly = preg_replace('/^(ថ្នាក់ទី|ថ្នាក់|Grade)\s*/ui', '', trim($rawGrade));
            $displayGrade = $toKhmerNum($cleanGradeOnly ?: $rawGrade);

            $rawSection = trim($timetable->class->section->name ?? '');

            // Format section nicely: e.g. "ក (ពេលព្រឹក)" -> "(ក) (ពេលព្រឹក)"
            if (preg_match('/^([^\(\)]+)\s*\((.+)\)$/u', $rawSection, $secMatches)) {
                $secName = trim($secMatches[1]);
                $secShift = trim($secMatches[2]);
                $displaySectionPart = "({$toKhmerNum($secName)}) ({$toKhmerNum($secShift)})";
            } elseif (str_contains($rawSection, '(') || str_contains($rawSection, ')')) {
                $displaySectionPart = $toKhmerNum($rawSection);
            } elseif ($rawSection !== '') {
                $displaySectionPart = "({$toKhmerNum($rawSection)})";
            } else {
                $displaySectionPart = '';
            }

            $rawTitleText = "កាលវិភាគប្រចាំខែ សម្រាប់ថ្នាក់ទី {$displayGrade}" . ($displaySectionPart !== '' ? " {$displaySectionPart}" : '');

            $parenInlineStyle = "font-family: 'Siemreap', 'Kantumruy Pro', 'Khmer OS Siemreap', 'Inter', sans-serif !important; font-weight: normal !important; font-size: 13pt !important; display: inline-block !important; vertical-align: baseline !important;";

            $styledTitle = str_replace(
                ['(', ')'],
                [
                    '<span class="title-paren" style="' . $parenInlineStyle . '">(</span>',
                    '<span class="title-paren" style="' . $parenInlineStyle . '">)</span>'
                ],
                $rawTitleText
            );
        @endphp
        <div class="print-official-header">
            <div class="print-header-top">
                <div class="print-school-title text-start">
                    <img src="{{ asset('images/school_logo.png') }}" alt="School Logo" class="school-logo" style="height: 80px !important; width: auto !important; object-fit: contain !important;" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                    <div class="school-kh font-moul">សាលារៀនប្រទីបបញ្ញា</div>
                    <div class="school-en">Pratib Panha School</div>
                </div>
                <div class="print-kingdom-title text-center">
                    <div class="kingdom-kh kingdom-main font-moul" style="margin-bottom: 7px; line-height: 1.6;">ព្រះរាជាណាចក្រកម្ពុជា</div>
                    <div class="kingdom-kh kingdom-motto font-moul" style="line-height: 1.6;">ជាតិ សាសនា ព្រះមហាក្សត្រ</div>
                    <div class="print-motto-divider" style="margin-top: 6px; line-height: 1;">
                        <img src="{{ asset('images/khmer_divider_trans.png') }}" alt="Divider" style="height: 13px; width: auto; max-width: 95px; object-fit: contain; display: inline-block;" onerror="this.onerror=null;this.src='{{ asset('images/khmer_divider.png') }}';">
                    </div>
                </div>
            </div>

            <div class="print-main-title font-moul">
                {!! $styledTitle !!}
            </div>
            <div class="print-academic-title font-moul">
                ឆ្នាំសិក្សា {{ $toKhmerNum($timetable->academic_year->name ?? '២០២៥ - ២០២៦') }}
                @if($classroomName)
                    <span class="mx-2 font-siemreap" style="font-size: 11pt; font-weight: normal;">•</span>
                    <span class="font-siemreap" style="font-size: 11pt; font-weight: 700;">បន្ទប់សិក្សា៖ {{ $toKhmerNum($classroomName) }}</span>
                @endif
                @if($primaryTeacherName)
                    <span class="mx-2 font-siemreap" style="font-size: 11pt; font-weight: normal;">•</span>
                    <span class="font-siemreap" style="font-size: 11pt; font-weight: 700;">គ្រូបង្រៀន៖ {{ $toKhmerNum($primaryTeacherName) }}{{ $primaryTeacherPhone ? ' (' . $toKhmerNum($primaryTeacherPhone) . ')' : '' }}</span>
                @endif
            </div>
        </div>

        <div class="timetable-grid-wrapper">
            <div class="table-responsive">
                <table class="timetable-matrix">
                    <thead>
                        <tr>
                            <th class="slot-time-col">
                                <div class="font-siemreap fw-bold">ម៉ោងសិក្សា</div>
                                <div style="font-size: 0.7rem; font-weight: 500; color: #64748b;" class="no-print">Time & Period</div>
                            </th>
                            <th>
                                <div class="font-siemreap fw-bold">ថ្ងៃចន្ទ</div>
                                <div style="font-size: 0.7rem; font-weight: 500; color: #64748b;" class="no-print">Monday</div>
                            </th>
                            <th>
                                <div class="font-siemreap fw-bold">ថ្ងៃអង្គារ</div>
                                <div style="font-size: 0.7rem; font-weight: 500; color: #64748b;" class="no-print">Tuesday</div>
                            </th>
                            <th>
                                <div class="font-siemreap fw-bold">ថ្ងៃពុធ</div>
                                <div style="font-size: 0.7rem; font-weight: 500; color: #64748b;" class="no-print">Wednesday</div>
                            </th>
                            <th>
                                <div class="font-siemreap fw-bold">ថ្ងៃព្រហស្បតិ៍</div>
                                <div style="font-size: 0.7rem; font-weight: 500; color: #64748b;" class="no-print">Thursday</div>
                            </th>
                            <th>
                                <div class="font-siemreap fw-bold">ថ្ងៃសុក្រ</div>
                                <div style="font-size: 0.7rem; font-weight: 500; color: #64748b;" class="no-print">Friday</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timeSlots as $slot)
                            @if($slot->is_break)
                                {{-- BREAK ROW --}}
                                <tr class="break-row">
                                    <td class="slot-time-col text-center">
                                        <div class="slot-period-badge" style="color:#a16207;">សម្រាក</div>
                                        <div class="slot-time-range font-siemreap fw-bold" style="color:#854d0e;">
                                            {{ $toKhmerNum(\Carbon\Carbon::parse($slot->start_time)->format('G:i')) }} - {{ $toKhmerNum(\Carbon\Carbon::parse($slot->end_time)->format('G:i')) }}
                                        </div>
                                    </td>
                                    <td colspan="5">
                                        <div class="break-banner font-siemreap fw-bold">
                                            <i class="bi bi-cup-hot-fill text-warning fs-6 no-print"></i>
                                            <span>ម៉ោងសម្រាកលម្ហែកាយ ({{ $toKhmerNum(\Carbon\Carbon::parse($slot->start_time)->format('G:i')) }} – {{ $toKhmerNum(\Carbon\Carbon::parse($slot->end_time)->format('G:i')) }})</span>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                {{-- TEACHING PERIOD ROW --}}
                                <tr>
                                    <td class="slot-time-col">
                                        <div class="slot-period-badge no-print" style="font-weight: 600;">
                                            ម៉ោងទី {{ $loop->iteration }} ({{ \Carbon\Carbon::parse($slot->start_time)->hour < 12 ? 'ព្រឹក' : 'រសៀល' }})
                                        </div>
                                        <div class="slot-time-range font-siemreap fw-bold">
                                            <span class="d-none d-print-inline">{{ $toKhmerNum(\Carbon\Carbon::parse($slot->start_time)->format('G:i')) }}- {{ $toKhmerNum(\Carbon\Carbon::parse($slot->end_time)->format('G:i')) }}</span>
                                            <span class="d-print-none font-siemreap">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</span>
                                        </div>
                                    </td>

                                    {{-- Day columns 1 to 5 (Monday to Friday) --}}
                                    @for($day = 1; $day <= 5; $day++)
                                        @php
                                            $entry = $scheduleMatrix[$day][$slot->id] ?? null;
                                            // Color palette based on subject_id
                                            $palette = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#6366f1'];
                                            $color = $entry ? $palette[($entry->subject_id ?? 0) % count($palette)] : '#3b82f6';
                                        @endphp
                                        <td class="slot-cell" id="slot-cell-{{ $day }}-{{ $slot->id }}" @if(!$entry && auth()->user()?->canAccess('timetables.create')) onclick="openEntryModal({{ $day }}, {{ $slot->id }})" title="ចុចដើម្បីបន្ថែមម៉ោងបង្រៀន" style="cursor: pointer;" @endif>
                                            @if($entry)
                                                <div class="schedule-card" style="--accent-color: {{ $color }};" 
                                                     @if(auth()->user()?->canAccess('timetables.edit')) onclick="openEditEntryModal({{ $day }}, {{ $slot->id }}, {{ $entry->subject_id }}, '{{ $entry->teacher_id ?? '' }}', '{{ $entry->classroom_id ?? '' }}')" title="ចុចដើម្បីកែសម្រួលម៉ោងបង្រៀន" style="cursor: pointer;" @else style="cursor: default;" @endif>
                                                    <div>
                                                        <div class="subject-title text-truncate" title="{{ $entry->subject->name ?? 'មុខវិជ្ជា' }}">
                                                            <i class="bi bi-book"></i><span>{{ $entry->subject->name ?? 'មុខវិជ្ជា' }}</span>
                                                        </div>
                                                    </div>

                                                    {{-- Card Actions: Edit & Delete --}}
                                                    @if(auth()->user()?->canAccess('timetables.edit') || auth()->user()?->canAccess('timetables.delete'))
                                                    <div class="card-actions no-print" onclick="event.stopPropagation()">
                                                        @if(auth()->user()?->canAccess('timetables.edit'))
                                                        <button type="button" class="card-action-btn card-edit-btn" 
                                                                onclick="openEditEntryModal({{ $day }}, {{ $slot->id }}, {{ $entry->subject_id }}, '{{ $entry->teacher_id ?? '' }}', '{{ $entry->classroom_id ?? '' }}')" 
                                                                title="កែសម្រួលម៉ោងបង្រៀន">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        @endif
                                                        @if(auth()->user()?->canAccess('timetables.delete'))
                                                        <form action="{{ route('timetables.entries.destroy', [$timetable, $entry]) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុប {{ $entry->subject->name ?? 'មុខវិជ្ជានេះ' }} ចេញពីម៉ោងនេះមែនទេ?')" class="d-inline m-0 p-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="card-action-btn card-delete-btn" title="លុបម៉ោងបង្រៀន">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>
                                            @else
                                                @if(auth()->user()?->canAccess('timetables.create'))
                                                <button type="button" class="empty-slot-btn no-print" onclick="openEntryModal({{ $day }}, {{ $slot->id }})" title="បន្ថែមមុខវិជ្ជា">
                                                    <i class="bi bi-plus-circle-dotted fs-5"></i>
                                                    <span>បន្ថែម</span>
                                                </button>
                                                @else
                                                <div class="empty-slot-placeholder text-muted font-siemreap small py-2 text-center opacity-25">—</div>
                                                @endif
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                                    <h6>មិនមានម៉ោងសិក្សាឡើយ</h6>
                                    <p class="small mb-0">សូមកំណត់វេនសិក្សា និងម៉ោងសិក្សានៅក្នុងការកំណត់ប្រព័ន្ធ។</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- OFFICIAL PRINT SIGNATURES FOOTER (Visible ONLY during print) --}}
        <div class="print-signatures-footer font-siemreap">
            <div class="print-sig-block ms-auto text-center" style="min-width: 330px;">
                <div class="sig-lunar-date" id="printSigLunarDate" style="font-family: 'Siemreap', 'Battambang', sans-serif; font-size: 10pt; line-height: 1.5; color: #000; font-weight: 600; white-space: nowrap;">
                    ថ្ងៃ....... ....... ខែ....... ឆ្នាំ...... ......ស័ក ពុទ្ធសករាជ ២៥.....
                </div>
                <div class="sig-solar-date" id="printSigSolarDate" style="font-family: 'Siemreap', 'Battambang', sans-serif; font-size: 10pt; line-height: 1.5; color: #000; font-weight: 600; white-space: nowrap;">
                    សាលារៀនប្រទីបបញ្ញា ថ្ងៃទី....... ខែ............ ឆ្នាំ២០.....
                </div>
                <div style="position: relative; display: inline-block; margin-top: 4px; min-width: 260px;">
                    <div class="sig-title font-moul" id="printSigTitle" style="font-size: 11pt; color: #000; margin-bottom: 0; position: relative; z-index: 2;">
                        នាយិកាសាលា
                    </div>
                    <div class="stamp-signature-wrap" style="position: relative; height: auto; margin-top: -18px; z-index: 1;">
                        <img src="{{ asset('images/school_stamp_3x.png') }}?v={{ file_exists(public_path('images/school_stamp_3x.png')) ? filemtime(public_path('images/school_stamp_3x.png')) : time() }}" alt="ហត្ថលេខា និងត្រា" class="stamp-signature-img" style="height: 120px; width: auto; object-fit: contain; pointer-events: none; display: inline-block;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: DAY-BY-DAY AGENDA LIST --}}
    <div class="tab-pane fade" id="list-view" role="tabpanel">
        <div class="agenda-grid">
            @php
                $dayNames = [
                    1 => 'ថ្ងៃចន្ទ',
                    2 => 'ថ្ងៃអង្គារ',
                    3 => 'ថ្ងៃពុធ',
                    4 => 'ថ្ងៃព្រហស្បតិ៍',
                    5 => 'ថ្ងៃសុក្រ',
                ];
            @endphp

            @foreach($dayNames as $dayNum => $dayName)
                @php
                    $dayEntries = $timetable->timetable_entries->where('day_of_week', $dayNum)->sortBy(function($e) {
                        return $e->time_slot->start_time ?? '';
                    });
                @endphp
                <div class="agenda-card">
                    <div class="bg-white border-bottom py-1.5 px-2.5 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark d-flex align-items-center gap-1.5" style="font-size: 0.88rem;">
                            <i class="bi bi-calendar-event text-primary"></i> {{ $dayName }}
                        </span>
                        <span class="badge" style="background: #f59e0b; color: #fff; font-size: 0.72rem; font-weight: 700; padding: 0.18rem 0.45rem; border-radius: 4px;">
                            {{ $dayEntries->count() }} ម៉ោង
                        </span>
                    </div>
                    <div class="p-2 flex-grow-1" style="background: #ffffff;">
                        @forelse($dayEntries as $entry)
                            <div class="d-flex align-items-center justify-content-between p-1.5 px-2 mb-1.5 border rounded-1 bg-white hover-shadow-xs" style="transition: all 0.15s ease;">
                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                    <div class="text-center px-1.5 py-0.5 border rounded text-secondary flex-shrink-0" style="min-width: 48px; background: #f8fafc; line-height: 1.15;">
                                        <div class="fw-bold text-dark" style="font-size: 0.76rem;">
                                            {{ \Carbon\Carbon::parse($entry->time_slot->start_time ?? '')->format('H:i') }}
                                        </div>
                                        <div style="font-size: 0.62rem; color: #64748b;">
                                            {{ \Carbon\Carbon::parse($entry->time_slot->end_time ?? '')->format('H:i') }}
                                        </div>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.82rem; line-height: 1.25;">
                                            {{ $entry->subject->name ?? 'មុខវិជ្ជា' }}
                                        </div>
                                        <div class="text-muted text-truncate d-flex align-items-center gap-1.5 mt-0.5" style="font-size: 0.70rem; line-height: 1.2;">
                                            <span><i class="bi bi-geo-alt"></i> {{ $entry->classroom->name ?? 'បន្ទប់' }}</span>
                                            <span>•</span>
                                            <span><i class="bi bi-person"></i> {{ $entry->teacher->user?->name ?? 'មិនទាន់ចាត់តាំង' }}</span>
                                            @if($entry->teacher?->phone)
                                                <span>•</span>
                                                <span><i class="bi bi-telephone"></i> {{ $entry->teacher->phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(auth()->user()?->canAccess('timetables.edit') || auth()->user()?->canAccess('timetables.delete'))
                                <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-1">
                                    @if(auth()->user()?->canAccess('timetables.edit'))
                                    <button type="button" class="btn btn-sm btn-outline-primary border-0 p-1" 
                                            onclick="openEditEntryModal({{ $entry->day_of_week }}, {{ $entry->time_slot_id }}, {{ $entry->subject_id }}, '{{ $entry->teacher_id ?? '' }}', '{{ $entry->classroom_id ?? '' }}')" 
                                            style="line-height: 1;" title="កែសម្រួល">
                                        <i class="bi bi-pencil fs-6"></i>
                                    </button>
                                    @endif
                                    @if(auth()->user()?->canAccess('timetables.delete'))
                                    <form action="{{ route('timetables.entries.destroy', [$timetable, $entry]) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបម៉ោងបង្រៀននេះមែនទេ?')" class="d-inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" style="line-height: 1;" title="លុប">
                                            <i class="bi bi-trash fs-6"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted" style="font-size: 0.78rem;">
                                <i class="bi bi-calendar2-x fs-5 d-block mb-1 text-secondary opacity-50"></i>
                                មិនទាន់មានម៉ោងបង្រៀន
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- MODAL: EDIT PRINT DATES --}}
<div class="modal fade" id="editPrintDateModal" tabindex="-1" aria-labelledby="editPrintDateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 py-2.5 px-3" style="background: linear-gradient(135deg, #1e293b, #2563eb) !important;">
                <h5 class="modal-title fw-bold fs-6" id="editPrintDateModalLabel">
                    <i class="bi bi-calendar2-range me-2"></i> កែសម្រួលកាលបរិច្ឆេទសម្រាប់បោះពុម្ព
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 font-siemreap">
                <p class="text-muted small mb-3">
                    អ្នកអាចជ្រើសរើសទម្រង់ទុកចន្លោះ (...) សម្រាប់សរសេរបំពេញដោយដៃពេលបោះពុម្ព ឬវាយបញ្ចូលកាលបរិច្ឆេទផ្ទាល់។
                </p>

                <div class="mb-3">
                    <label for="input_sig_lunar" class="form-label small fw-bold text-dark mb-1">
                        កាលបរិច្ឆេទចន្ទគតិ (ជួរទី ១) <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="input_sig_lunar" class="form-control form-control-sm font-siemreap" value="ថ្ងៃ....... ....... ខែ....... ឆ្នាំ...... ......ស័ក ពុទ្ធសករាជ ២៥.....">
                </div>

                <div class="mb-3">
                    <label for="input_sig_solar" class="form-label small fw-bold text-dark mb-1">
                        កាលបរិច្ឆេទសុរិយគតិ (ជួរទី ២) <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="input_sig_solar" class="form-control form-control-sm font-siemreap" value="សាលារៀនប្រទីបបញ្ញា ថ្ងៃទី....... ខែ............ ឆ្នាំ២០.....">
                </div>

                <div class="mb-3">
                    <label for="input_sig_title" class="form-label small fw-bold text-dark mb-1">
                        តួនាទីអ្នកចុះហត្ថលេខា
                    </label>
                    <input type="text" id="input_sig_title" class="form-control form-control-sm font-siemreap" value="នាយិកាសាលា">
                </div>

                <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary py-1" onclick="setDottedDatePreset()">
                        <i class="bi bi-dash-lg me-1"></i> ទម្រង់ទុកចន្លោះ (...)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary py-1" onclick="setSampleDatePreset()">
                        <i class="bi bi-calendar-check me-1"></i> គំរូកាលបរិច្ឆេទពេញលេញ
                    </button>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light border-top">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">បិទ</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="savePrintDates()">
                    <i class="bi bi-check-lg me-1"></i> រក្សាទុក
                </button>
                <button type="button" class="btn btn-sm btn-success fw-semibold" onclick="saveAndPrint()">
                    <i class="bi bi-printer me-1"></i> រក្សាទុក & បោះពុម្ព
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: ADD / EDIT SCHEDULE ENTRY --}}
<div class="modal fade" id="addEntryModal" tabindex="-1" aria-labelledby="addEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 py-2.5 px-3" style="background: linear-gradient(135deg, #1e293b, #2563eb) !important;">
                <h5 class="modal-title fw-bold fs-6" id="addEntryModalLabel">
                    <i class="bi bi-calendar-plus me-2" id="modalTitleIcon"></i> <span id="modalTitleText">បន្ថែមម៉ោងបង្រៀនថ្មី</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('timetables.entries.store', $timetable) }}" method="POST">
                @csrf
                <div class="modal-body p-3">
                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 mb-2.5 small rounded-2">
                            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> មានបញ្ហាក្នុងការរក្សាទុក (សូមពិនិត្យឡើងវិញ)៖</div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <p class="text-muted small mb-2.5" id="modalSubtitle">ចាត់តាំងមុខវិជ្ជាទៅក្នុងកាលវិភាគប្រចាំខែ។</p>

                    <div class="row g-2.5">
                        {{-- Day of Week --}}
                        <div class="col-md-6">
                            <label for="modal_day_of_week" class="form-label small fw-bold text-dark mb-1">ថ្ងៃនៃសប្តាហ៍ <span class="text-danger">*</span></label>
                            <select name="day_of_week" id="modal_day_of_week" class="form-select form-select-sm" required>
                                <option value="">— ជ្រើសរើសថ្ងៃ —</option>
                                <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>ចន្ទ (Monday)</option>
                                <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>អង្គារ (Tuesday)</option>
                                <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>ពុធ (Wednesday)</option>
                                <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>ព្រហស្បតិ៍ (Thursday)</option>
                                <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>សុក្រ (Friday)</option>
                            </select>
                        </div>

                        {{-- Time Slot --}}
                        <div class="col-md-6">
                            <label for="modal_time_slot_id" class="form-label small fw-bold text-dark mb-1">ម៉ោងសិក្សា <span class="text-danger">*</span></label>
                            <select name="time_slot_id" id="modal_time_slot_id" class="form-select form-select-sm" required>
                                <option value="">— ជ្រើសរើសម៉ោង —</option>
                                @foreach($timeSlots as $slot)
                                    @if(!$slot->is_break)
                                        <option value="{{ $slot->id }}" {{ old('time_slot_id') == $slot->id ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        {{-- Subject --}}
                        <div class="col-12">
                            <label for="modal_subject_id" class="form-label small fw-bold text-dark mb-1">មុខវិជ្ជា <span class="text-danger">*</span></label>
                            <select name="subject_id" id="modal_subject_id" class="form-select form-select-sm" required>
                                <option value="">— ជ្រើសរើសមុខវិជ្ជា —</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0 py-2 px-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-ghost text-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm" id="modalSubmitBtn" style="background:#2563eb;border-color:#1d4ed8;">
                        <i class="bi bi-save me-1" id="modalSubmitIcon"></i> <span id="modalSubmitText">រក្សាទុកចូលកាលវិភាគ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEntryModal(dayOfWeek, timeSlotId) {
    const titleText = document.getElementById('modalTitleText');
    const titleIcon = document.getElementById('modalTitleIcon');
    const subtitle = document.getElementById('modalSubtitle');
    const submitText = document.getElementById('modalSubmitText');
    const submitIcon = document.getElementById('modalSubmitIcon');

    if (titleText) titleText.textContent = 'បន្ថែមម៉ោងបង្រៀនថ្មី';
    if (titleIcon) titleIcon.className = 'bi bi-calendar-plus me-2';
    if (subtitle) subtitle.textContent = 'ចាត់តាំងមុខវិជ្ជាទៅក្នុងកាលវិភាគប្រចាំខែ។';
    if (submitText) submitText.textContent = 'រក្សាទុកចូលកាលវិភាគ';
    if (submitIcon) submitIcon.className = 'bi bi-save me-1';

    const daySelect = document.getElementById('modal_day_of_week');
    const slotSelect = document.getElementById('modal_time_slot_id');
    const subjectSelect = document.getElementById('modal_subject_id');
    
    if (daySelect && dayOfWeek) daySelect.value = dayOfWeek;
    if (slotSelect && timeSlotId) slotSelect.value = timeSlotId;
    if (subjectSelect) subjectSelect.value = '';

    const modalElement = document.getElementById('addEntryModal');
    if (modalElement) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        modal.show();
    }
}

function openEditEntryModal(dayOfWeek, timeSlotId, subjectId, teacherId, classroomId) {
    const titleText = document.getElementById('modalTitleText');
    const titleIcon = document.getElementById('modalTitleIcon');
    const subtitle = document.getElementById('modalSubtitle');
    const submitText = document.getElementById('modalSubmitText');
    const submitIcon = document.getElementById('modalSubmitIcon');

    if (titleText) titleText.textContent = 'កែសម្រួលម៉ោងបង្រៀន';
    if (titleIcon) titleIcon.className = 'bi bi-pencil-square me-2';
    if (subtitle) subtitle.textContent = 'ផ្លាស់ប្តូរមុខវិជ្ជាសម្រាប់ម៉ោងសិក្សានេះ។';
    if (submitText) submitText.textContent = 'រក្សាទុកការកែប្រែ';
    if (submitIcon) submitIcon.className = 'bi bi-check-circle me-1';

    const daySelect = document.getElementById('modal_day_of_week');
    const slotSelect = document.getElementById('modal_time_slot_id');
    const subjectSelect = document.getElementById('modal_subject_id');

    if (daySelect && dayOfWeek) daySelect.value = dayOfWeek;
    if (slotSelect && timeSlotId) slotSelect.value = timeSlotId;
    if (subjectSelect && subjectId) subjectSelect.value = subjectId;

    const modalElement = document.getElementById('addEntryModal');
    if (modalElement) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        modal.show();
    }
}

document.addEventListener('DOMContentLoaded', function () {

    // Save scroll position and active cell before submitting entry form
    const addEntryForm = document.querySelector('#addEntryModal form');
    if (addEntryForm) {
        addEntryForm.addEventListener('submit', function () {
            sessionStorage.setItem('timetable_scroll_y', window.scrollY);
            const day = document.getElementById('modal_day_of_week')?.value;
            const slot = document.getElementById('modal_time_slot_id')?.value;
            if (day && slot) {
                sessionStorage.setItem('timetable_saved_cell_id', 'slot-cell-' + day + '-' + slot);
            }
        });
    }

    // Also record scroll position on delete entry submissions
    document.querySelectorAll('form[action*="timetables.entries.destroy"]').forEach(function (f) {
        f.addEventListener('submit', function () {
            sessionStorage.setItem('timetable_scroll_y', window.scrollY);
        });
    });

    // Reopen modal automatically if validation errors occurred
    @if($errors->any())
        if (modalEl) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    @endif

    // Restore exact scroll position smoothly to prevent jumping up or down
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    const savedScrollY = sessionStorage.getItem('timetable_scroll_y');
    if (savedScrollY !== null) {
        const scrollPos = parseInt(savedScrollY, 10);
        window.scrollTo(0, scrollPos);
        requestAnimationFrame(function () {
            window.scrollTo(0, scrollPos);
        });
        setTimeout(function () {
            window.scrollTo(0, scrollPos);
        }, 50);
        sessionStorage.removeItem('timetable_scroll_y');
    }

    // Highlight the cell that was just updated
    const savedCellId = sessionStorage.getItem('timetable_saved_cell_id');
    if (savedCellId) {
        const targetCell = document.getElementById(savedCellId);
        if (targetCell) {
            targetCell.classList.add('cell-highlight-saved');
            setTimeout(function () {
                targetCell.classList.remove('cell-highlight-saved');
            }, 2500);
        }
        sessionStorage.removeItem('timetable_saved_cell_id');
    }

    // Auto dismiss floating alert toast
    setTimeout(function () {
        const floatingAlerts = document.querySelectorAll('.app-main-wrapper .container-fluid > .alert-dismissible');
        floatingAlerts.forEach(function (alert) {
            alert.style.transition = 'all 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-15px)';
            setTimeout(function () {
                alert.remove();
            }, 400);
        });
    }, 3500);
});

function filterShift(shift) {
    document.querySelectorAll('#shiftFilterGroup button').forEach(b => b.classList.remove('active'));
    const activeBtn = document.getElementById('btn-shift-' + shift);
    if (activeBtn) activeBtn.classList.add('active');

    const morningRows = document.querySelectorAll('.shift-morning-row');
    const afternoonRows = document.querySelectorAll('.shift-afternoon-row');

    if (shift === 'morning') {
        morningRows.forEach(r => r.style.display = '');
        afternoonRows.forEach(r => r.style.display = 'none');
    } else if (shift === 'afternoon') {
        morningRows.forEach(r => r.style.display = 'none');
        afternoonRows.forEach(r => r.style.display = '');
    } else {
        morningRows.forEach(r => r.style.display = '');
        afternoonRows.forEach(r => r.style.display = '');
    }
}

// ─────────────────────────────────────────────────────────────
// PRINT SIGNATURE FOOTER DATES MANAGEMENT (DOTTED / CUSTOM)
// ─────────────────────────────────────────────────────────────
const defaultLunarDate = 'ថ្ងៃ....... ....... ខែ....... ឆ្នាំ...... ......ស័ក ពុទ្ធសករាជ ២៥.....';
const defaultSolarDate = 'សាលារៀនប្រទីបបញ្ញា ថ្ងៃទី....... ខែ............ ឆ្នាំ២០.....';
const defaultSigTitle  = 'នាយិកាសាលា';

function applyPrintDates() {
    let savedLunar = localStorage.getItem('timetable_print_lunar');
    let savedSolar = localStorage.getItem('timetable_print_solar');
    const savedTitle = localStorage.getItem('timetable_print_title') || defaultSigTitle;

    // Reset if previously cached short dots or overly long dots
    if (!savedLunar || savedLunar.includes('២៥...') || savedLunar.includes('..................')) {
        savedLunar = defaultLunarDate;
        localStorage.setItem('timetable_print_lunar', defaultLunarDate);
    }
    if (!savedSolar || savedSolar.includes('ថ្ងៃទី...') || savedSolar.includes('..................')) {
        savedSolar = defaultSolarDate;
        localStorage.setItem('timetable_print_solar', defaultSolarDate);
    }

    const elLunar = document.getElementById('printSigLunarDate');
    const elSolar = document.getElementById('printSigSolarDate');
    const elTitle = document.getElementById('printSigTitle');
    const inLunar = document.getElementById('input_sig_lunar');
    const inSolar = document.getElementById('input_sig_solar');
    const inTitle = document.getElementById('input_sig_title');

    if (elLunar) elLunar.textContent = savedLunar;
    if (elSolar) elSolar.textContent = savedSolar;
    if (elTitle) elTitle.textContent = savedTitle;

    if (inLunar) inLunar.value = savedLunar;
    if (inSolar) inSolar.value = savedSolar;
    if (inTitle) inTitle.value = savedTitle;
}

function savePrintDates(andPrint = false) {
    const inLunar = document.getElementById('input_sig_lunar');
    const inSolar = document.getElementById('input_sig_solar');
    const inTitle = document.getElementById('input_sig_title');

    const lunarVal = inLunar ? inLunar.value.trim() : defaultLunarDate;
    const solarVal = inSolar ? inSolar.value.trim() : defaultSolarDate;
    const titleVal = inTitle ? inTitle.value.trim() : defaultSigTitle;

    localStorage.setItem('timetable_print_lunar', lunarVal || defaultLunarDate);
    localStorage.setItem('timetable_print_solar', solarVal || defaultSolarDate);
    localStorage.setItem('timetable_print_title', titleVal || defaultSigTitle);

    applyPrintDates();

    const modalEl = document.getElementById('editPrintDateModal');
    if (modalEl) {
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();
    }

    if (andPrint) {
        setTimeout(() => window.print(), 350);
    }
}

function saveAndPrint() {
    savePrintDates(true);
}

function setDottedDatePreset() {
    const inLunar = document.getElementById('input_sig_lunar');
    const inSolar = document.getElementById('input_sig_solar');
    const inTitle = document.getElementById('input_sig_title');
    if (inLunar) inLunar.value = defaultLunarDate;
    if (inSolar) inSolar.value = defaultSolarDate;
    if (inTitle) inTitle.value = defaultSigTitle;
}

function setSampleDatePreset() {
    const inLunar = document.getElementById('input_sig_lunar');
    const inSolar = document.getElementById('input_sig_solar');
    const inTitle = document.getElementById('input_sig_title');
    if (inLunar) inLunar.value = 'ថ្ងៃចន្ទ ៣រោច ខែមិគសិរ ឆ្នាំម្សាញ់ សប្តស័ក ពុទ្ធសករាជ ២៥៦៩';
    if (inSolar) inSolar.value = 'សាលារៀនប្រទីបបញ្ញា ថ្ងៃទី០៨ ខែធ្នូ ឆ្នាំ២០២៥';
    if (inTitle) inTitle.value = 'នាយិកាសាលា';
}

function exitOrGoBack() {
    @if($canEditTimetable)
        togglePrintPreview();
    @else
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "{{ route('timetables.index') }}";
        }
    @endif
}

function togglePrintPreview() {
    const isPreview = document.body.classList.toggle('print-preview-mode');
    document.documentElement.classList.toggle('print-preview-mode', isPreview);
    const floatingBar = document.getElementById('printPreviewFloatingBar');
    const btn = document.getElementById('btnTogglePreview');
    if (floatingBar) {
        if (isPreview) {
            floatingBar.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            floatingBar.classList.add('d-none');
        }
    }
    if (btn) {
        if (isPreview) {
            btn.innerHTML = '<i class="bi bi-x-circle me-1.5"></i> បិទទិដ្ឋភាពគំរូ';
            btn.className = 'btn btn-sm btn-danger px-2.5 py-1 shadow-xs d-flex align-items-center fw-semibold';
        } else {
            btn.innerHTML = '<i class="bi bi-eye-fill me-1.5"></i> មើលទម្រង់បោះពុម្ព (Preview)';
            btn.className = 'btn btn-sm btn-outline-success px-2.5 py-1 shadow-xs d-flex align-items-center fw-semibold';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    applyPrintDates();

    // 1. Move modals directly under body so they are never trapped in container stacking contexts
    ['addEntryModal', 'editPrintDateModal'].forEach(function(modalId) {
        const modalEl = document.getElementById(modalId);
        if (modalEl && modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
    });

    // 2. When errors exist, exit preview mode (if active) and automatically reopen the form modal
    @if($errors->any())
        if (document.body.classList.contains('print-preview-mode')) {
            togglePrintPreview();
        }
        const addModalEl = document.getElementById('addEntryModal');
        if (addModalEl) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(addModalEl);
            modalInstance.show();
        }
    @endif

    // 3. Clean up any stuck backdrops on modal hide to ensure screen never freezes
    document.addEventListener('hidden.bs.modal', function() {
        if (!document.querySelector('.modal.show')) {
            document.querySelectorAll('.modal-backdrop').forEach(function(el) {
                el.remove();
            });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }
    });

    // 4. Fallback: clicking any stuck backdrop directly removes it
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('modal-backdrop')) {
            e.target.remove();
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }
    });
});
</script>
@endpush
