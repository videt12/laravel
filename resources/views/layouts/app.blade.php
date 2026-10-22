<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $appSchool = \App\Models\Core\School::first();
        $appFavicon = ($appSchool && $appSchool->logo_path && file_exists(public_path($appSchool->logo_path)))
            ? asset($appSchool->logo_path)
            : (file_exists(public_path('images/school_logo.png')) ? asset('images/school_logo.png') : asset('images/logo.svg'));
    @endphp
    <link rel="shortcut icon" href="{{ $appFavicon }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Flatpickr Datepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Fonts: Inter for Latin + Kantumruy Pro & Siemreap & Moul for Khmer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&family=Moul&family=Siemreap&display=swap" rel="stylesheet">

    <style>
        /* ─────────────────────────────────────────────────────────────
           1. DESIGN TOKENS & SYSTEM VARIABLES
        ───────────────────────────────────────────────────────────── */
        :root {
            /* ── Fonts ──────────────────────────────────────────── */
            --font-sans:     'Kantumruy Pro', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-heading:  'Siemreap', 'Kantumruy Pro', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-moul:     'Moul', 'Khmer OS Muol', 'Khmer OS Muol Light', serif;
            --font-latin:    'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-mono:     'JetBrains Mono', 'Consolas', monospace;

            /* ── Brand Color Scale ──────────────────────────────── */
            --color-brand-50:  #fffbeb;
            --color-brand-100: #fef3c7;
            --color-brand-200: #fde68a;
            --color-brand-300: #fcd34d;
            --color-brand-400: #fbbf24;
            --color-brand-500: #f59e0b;
            --color-brand-600: #d97706;
            --color-brand-700: #b45309;
            --color-brand-800: #92400e;
            --color-brand-900: #78350f;

            /* ── Brand Semantic Shortcuts ───────────────────────── */
            --gold:         #f5bb41;
            --gold-dark:    #eab308;
            --gold-text:    #18181b;
            --gold-light:   #fef3c7;
            --gold-subtle:  rgba(245, 187, 65, 0.15);

            /* ── កន្លែងកែពណ៌ Sidebar (របារម៉ឺនុយខាងឆ្វេង - Deep Charcoal) ─────────────── */
            --sidebar-bg:             #18181b; /* ពណ៌ផ្ទៃ Sidebar ទាំងមូល (Dark Charcoal #18181B) */
            --sidebar-header:         #18181b; /* ពណ៌ក្បាល Sidebar ខាងលើដែលមាន Logo */
            --sidebar-border:         #27272a; /* ពណ៌បន្ទាត់ខណ្ឌ Sidebar */
            --sidebar-text:           #94a3b8; /* ពណ៌អក្សរ Menu ធម្មតា (Muted Slate) */
            --sidebar-text-hover:     #ffffff; /* ពណ៌អក្សរពេលដាក់ Mouse លើ */
            --sidebar-item-hover:     rgba(255, 255, 255, 0.06); /* ពណ៌ផ្ទៃពេលដាក់ Mouse លើ Menu */
            --sidebar-active-bg:      #f5bb41; /* ពណ៌ផ្ទៃ Menu ដែលកំពុងជ្រើសរើស (Solid Warm Yellow #F5BB41) */
            --sidebar-active-text:    #18181b; /* ពណ៌អក្សរ Menu ដែលកំពុងជ្រើសរើស (Dark Charcoal) */
            --sidebar-section-text:   #71717a; /* ពណ៌ចំណងជើងផ្នែក */
            --sidebar-width:          260px;
            --sidebar-collapsed-width: 68px;

            /* ── Topbar & Footer ────────────────────────────────── */
            --topbar-height:  60px;
            --topbar-bg:      #ffffff;
            --topbar-border:  #e2e8f0;
            --footer-bg:      #ffffff;
            --footer-border:  #e2e8f0;

            /* ── App Surfaces (ផ្ទៃខាងក្រោយទំព័រ - Soft Light Slate) ──────── */
            --app-bg:       #f8fafc; /* ផ្ទៃខាងក្រោយទំព័រទាំងមូល (#F8FAFC) */
            --surface:      #ffffff; /* ពណ៌ផ្ទៃ Card ពណ៌ស */
            --surface-raised: #ffffff;

            /* ── កន្លែងកែពណ៌ Form & តារាង (White / ពណ៌ស) ───────── */
            --form-card-bg:        #ffffff; /* ផ្ទៃខាងក្នុង Form និងផ្ទាំងតារាង (Card Body ជាពណ៌ស) */
            --form-card-header-bg: #ffffff; /* ពណ៌ក្បាល Form និង Header តារាង */
            --form-card-border:    #e5e7eb; /* ពណ៌បន្ទាត់គែមជុំវិញ (Thin Light-Gray Border) */
            --form-input-bg:       #ffffff; /* ពណ៌ផ្ទៃប្រអប់បញ្ចូលទិន្នន័យ */

            /* ── Borders ────────────────────────────────────────── */
            --border:       #e5e7eb;
            --border-soft:  #f1f5f9;
            --border-input: #d1d5db;

            /* ── Text ───────────────────────────────────────────── */
            --text-dark:        #0f172a;
            --text-body:        #334155;
            --text-muted:       #64748b;
            --text-light:       #94a3b8;
            --text-placeholder: #94a3b8;

            /* ── Semantic Colors ────────────────────────────────── */
            --color-success-bg:     #dcfce7;
            --color-success-text:   #15803d;
            --color-success-border: #86efac;
            --color-warning-bg:     #fef9c3;
            --color-warning-text:   #a16207;
            --color-warning-border: #fde047;
            --color-error-bg:       #fef2f2;
            --color-error-text:     #b91c1c;
            --color-error-border:   #fca5a5;
            --color-info-bg:        #f0f9ff;
            --color-info-text:      #0369a1;
            --color-info-border:    #bae6fd;

            /* ── Border Radius (Rounded Corners) ────────────────── */
            --radius-xs:   4px;
            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   12px;
            --radius-xl:   16px;
            --radius-full: 9999px;

            /* ── Shadows ────────────────────────────────────────── */
            --shadow-xs:    0 1px 2px rgba(0,0,0,.03);
            --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.03);
            --shadow-md:    0 4px 6px -1px rgba(0,0,0,.06), 0 2px 4px -2px rgba(0,0,0,.03);
            --shadow-lg:    0 10px 15px -3px rgba(0,0,0,.06), 0 4px 6px -4px rgba(0,0,0,.03);
            --shadow-brand: 0 4px 12px rgba(243, 182, 58, 0.28);

            /* ── Transitions ────────────────────────────────────── */
            --transition-fast: 0.12s ease;
            --transition-base: 0.18s ease;
            --transition-slow: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ─────────────────────────────────────────────────────────────
           2. BASE & LAYOUT STRUCTURE (STRICT STATIC & SHADCN UX/UI)
        ───────────────────────────────────────────────────────────── */
        *, *::before, *::after {
            box-sizing: border-box;
            transition: none !important;
            animation: none !important;
        }

        :hover, :focus, :active {
            transform: none !important;
        }

        .modal.fade, .modal.fade .modal-dialog, .collapse, .collapsing {
            transition: none !important;
        }

        /* Flat instant background changes on hover (#F9FAFB) */
        .dropdown-item:hover,
        .dropdown-item:focus,
        .table tbody tr:hover,
        .data-table tbody tr:hover,
        .table-row-hover:hover,
        .nav-tabs .nav-link:hover,
        .list-group-item-action:hover {
            background-color: #f9fafb !important;
            color: #0f172a !important;
        }

        /* Clean Modern Spacious White Cards (shadcn/ui style) */
        .card,
        .dash-card,
        .data-card {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: none !important;
        }

        /* Primary Action Buttons: Solid Warm Amber/Yellow (#F59E0B) with dark text (#111827) */
        .btn-primary,
        .btn-primary-action,
        .btn-gold {
            background-color: #f59e0b !important;
            border: 1px solid #f59e0b !important;
            color: #111827 !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
        }
        .btn-primary:hover,
        .btn-primary-action:hover,
        .btn-gold:hover,
        .btn-primary:focus,
        .btn-primary-action:focus,
        .btn-gold:focus,
        .btn-primary:active,
        .btn-primary-action:active,
        .btn-gold:active {
            background-color: #d97706 !important;
            border-color: #d97706 !important;
            color: #111827 !important;
            box-shadow: none !important;
            transform: none !important;
        }

        /* Numbered pagination with amber active page button */
        .pagination {
            margin-bottom: 0 !important;
            gap: 4px;
        }
        .pagination .page-item .page-link {
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
            border-radius: 6px !important;
            padding: 0.35rem 0.75rem !important;
            font-size: 0.85rem !important;
            font-weight: 500 !important;
            box-shadow: none !important;
        }
        .pagination .page-item .page-link:hover {
            background-color: #f9fafb !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }
        .pagination .page-item.active .page-link {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #111827 !important;
            font-weight: 700 !important;
        }
        .pagination .page-item.disabled .page-link {
            color: #94a3b8 !important;
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }

        html {
            font-size: 16.5px; /* Base 16.5px: អក្សរខ្មែររីកធំល្មមសមសួនទូទាំងប្រព័ន្ធ ងាយស្រួលមើល មិនចង្អៀតជើងអក្សរ */
            -webkit-text-size-adjust: 100%;
        }

        body {
            min-height: 100%;
            margin: 0;
            padding: 0;
            background-color: var(--app-bg);
            color: var(--text-body);
            font-family: var(--font-sans);
            font-size: 1rem;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            max-width: 100vw;
        }

        /* Headings and Titles use Siemreap font */
        h1, h2, h3, h4, h6,
        .h1, .h2, .h3, .h4, .h6,
        .page-title,
        .brand-title,
        .card-title,
        .modal-title,
        .hero-name {
            font-family: var(--font-heading);
            letter-spacing: 0.2px;
        }

        /* Titles using Khmer Moul font */
        .font-moul,
        .font-khmer-moul,
        .nav-section-title,
        .form-card-header h5,
        .form-card-title {
            font-family: var(--font-moul, 'Moul', 'Khmer OS Muol', serif) !important;
            font-weight: 400 !important;
            color: #78350f;
            letter-spacing: 0.3px;
        }

        .text-gold { color: var(--gold) !important; }
        .text-gold-dark { color: var(--gold-dark) !important; }
        .bg-gold-light { background-color: var(--gold-light) !important; }

        #app-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
            max-width: 100vw;
            overflow-x: hidden !important;
            position: relative;
            background-color: var(--app-bg);
        }

        /* ─────────────────────────────────────────────────────────────
           3. SIDEBAR NAVIGATION
        ───────────────────────────────────────────────────────────── */
        .app-sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            min-height: 82px;
            background-color: var(--sidebar-header);
            border-bottom: 1px solid var(--sidebar-border);
        }

        .brand-logo-img {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            object-fit: contain;
            flex-shrink: 0;
            background-color: #ffffff;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.95);
            display: block;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .brand-link:hover .brand-logo-img {
            transform: scale(1.08);
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.22);
        }

        .brand-logo-icon {
            width: 34px;
            height: 34px;
            background: #0f172a;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #facc15;
            font-size: 1.15rem;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.25);
        }

        .brand-name,
        .brand-name.font-khmer-moul {
            font-family: var(--font-moul, 'Moul', 'Khmer OS Muol', serif) !important;
            font-size: 0.95rem;
            font-weight: 400 !important;
            letter-spacing: 0.2px;
            line-height: 1.35;
            color: #ffffff !important;
            white-space: nowrap;
        }

        .brand-name .text-gold {
            color: var(--gold, #f3b63a) !important;
        }

        .brand-tagline {
            font-family: var(--font-heading);
            font-size: 0.72rem;
            color: #9ca3af !important;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .sidebar-nav-wrapper {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.12) transparent;
        }

        .sidebar-nav-wrapper::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav-wrapper::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .nav-section-header {
            padding: 1.1rem 0.85rem 0.35rem;
            list-style: none;
        }

        .nav-section-title {
            font-family: var(--font-heading, 'Siemreap', sans-serif) !important;
            font-weight: 700 !important;
            font-size: 0.70rem;
            color: #6b7280 !important;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
        }

        .nav-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #262a33;
            opacity: 1;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.55rem 0.8rem;
            color: var(--sidebar-text, #9ca3af);
            font-size: 0.92rem;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            margin-bottom: 2px;
            border: 1px solid transparent;
        }

        .sidebar-link .nav-icon {
            font-size: 1.15rem;
            width: 28px;
            height: 28px;
            margin-right: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            flex-shrink: 0;
            color: #9ca3af;
            background-color: rgba(255, 255, 255, 0.04);
            transition: all 0.15s ease;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-item-hover, rgba(255, 255, 255, 0.06));
        }

        .sidebar-link:hover .nav-icon {
            color: var(--gold, #f3b63a);
            background-color: rgba(243, 182, 58, 0.15);
        }

        .sidebar-link:not(.collapsed) {
            color: #ffffff;
            font-weight: 600;
        }

        .sidebar-link:not(.collapsed) .nav-icon {
            color: var(--gold, #f3b63a);
            background-color: rgba(243, 182, 58, 0.15);
        }

        .sidebar-link.active {
            background-color: var(--sidebar-active-bg, #f3b63a) !important;
            color: var(--sidebar-active-text, #181b20) !important;
            font-weight: 600 !important;
            border-color: var(--sidebar-active-bg, #f3b63a) !important;
            box-shadow: 0 2px 8px rgba(243, 182, 58, 0.3) !important;
        }

        .sidebar-link.active .nav-icon {
            color: #181b20 !important;
            background-color: rgba(24, 27, 32, 0.15) !important;
        }

        .sidebar-link.active .nav-arrow {
            color: #181b20 !important;
        }

        .sidebar-link .nav-arrow {
            font-size: 0.8rem;
            transition: transform var(--transition-base);
            color: #6b7280;
        }

        .sidebar-link:not(.collapsed) .nav-arrow {
            transform: rotate(180deg);
            color: var(--gold, #f3b63a);
        }

        .sub-nav {
            padding-left: 0.65rem;
            margin-top: 2px;
            margin-bottom: 6px;
            border-left: 2px solid rgba(255, 255, 255, 0.08);
            margin-left: 1.35rem;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sub-link {
            padding: 0.45rem 0.75rem;
            color: #9ca3af;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
        }

        .sub-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.06);
            transform: translateX(2px);
        }

        .sub-link.active {
            color: #181b20 !important;
            font-weight: 600 !important;
            background-color: var(--gold, #f3b63a) !important;
            border-radius: 6px !important;
            margin-left: 0 !important;
        }

        .sidebar-user-footer {
            background-color: #14171c;
            border-top: 1px solid #262a33 !important;
        }

        .user-avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2a2f38;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold, #f3b63a);
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.15);
        }

        .user-avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .user-name {
            font-size: 0.85rem;
            line-height: 1.25;
            color: #f3f4f6 !important;
            font-weight: 600;
        }

        .user-role {
            font-size: 0.72rem;
            line-height: 1.2;
            color: #9ca3af !important;
        }

        .status-indicator-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
            flex-shrink: 0;
        }

        /* ─────────────────────────────────────────────────────────────
           4. COLLAPSED SIDEBAR STATE (DESKTOP)
        ───────────────────────────────────────────────────────────── */
        @media (min-width: 992px) {
            #app-layout.sidebar-collapsed .app-sidebar {
                width: var(--sidebar-collapsed-width);
            }

            #app-layout.sidebar-collapsed .brand-text,
            #app-layout.sidebar-collapsed .nav-text,
            #app-layout.sidebar-collapsed .nav-arrow,
            #app-layout.sidebar-collapsed .nav-section-title,
            #app-layout.sidebar-collapsed .sub-nav,
            #app-layout.sidebar-collapsed .user-info-text,
            #app-layout.sidebar-collapsed .status-indicator-dot {
                display: none !important;
            }

            #app-layout.sidebar-collapsed .nav-section-header {
                padding: 0.5rem 0.25rem;
                text-align: center;
                border-top: 1px solid var(--sidebar-border);
                margin: 0.25rem 0.5rem;
            }

            #app-layout.sidebar-collapsed .sidebar-link {
                justify-content: center;
                padding: 0.6rem;
            }

            #app-layout.sidebar-collapsed .sidebar-link .nav-icon {
                margin-right: 0;
            }

            #app-layout.sidebar-collapsed .app-main-wrapper {
                margin-left: var(--sidebar-collapsed-width);
            }

            #app-layout.sidebar-collapsed .sidebar-link[data-bs-toggle="collapse"],
            #app-layout.sidebar-collapsed .sidebar-link[data-bs-toggle="collapse"].active {
                border-radius: var(--radius-sm);
            }
        }

        /* ─────────────────────────────────────────────────────────────
           5. MOBILE SIDEBAR OFF-CANVAS
        ───────────────────────────────────────────────────────────── */
        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.show {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background-color: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(2px);
                z-index: 1030;
                display: none;
            }

            .sidebar-backdrop.show {
                display: block;
            }

            .app-main-wrapper {
                margin-left: 0 !important;
            }
        }

        /* ─────────────────────────────────────────────────────────────
           6. MAIN WRAPPER & TOPBAR
        ───────────────────────────────────────────────────────────── */
        .app-main-wrapper {
            flex-grow: 1;
            min-width: 0;
            max-width: 100%;
            overflow-x: hidden !important;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: transparent;
            position: relative;
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ស្មើគ្នាទាំងសងខាង និងគ្រប់ទំព័រ (Centered perfectly in visible workspace) */
        .app-main-wrapper::before {
            content: '';
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            bottom: 0;
            background-color: var(--app-bg, #f8fafc);
            pointer-events: none;
            z-index: 0;
            transition: left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #app-layout.sidebar-collapsed .app-main-wrapper::before {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 991.98px) {
            .app-main-wrapper::before {
                left: 0;
            }
        }

        .app-topbar {
            height: var(--topbar-height);
            border-bottom: 1px solid var(--topbar-border);
            position: fixed !important;
            top: 0 !important;
            right: 0 !important;
            left: var(--sidebar-width) !important;
            z-index: 1025 !important;
            background-color: var(--topbar-bg, #ffffff) !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            transition: left 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        #app-layout.sidebar-collapsed .app-topbar {
            left: var(--sidebar-collapsed-width) !important;
        }

        @media (max-width: 991.98px) {
            .app-topbar {
                left: 0 !important;
            }
        }

        .btn-topbar-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            background: transparent;
            transition: all 0.15s ease;
        }

        .btn-topbar-icon:hover {
            color: var(--text-dark);
            background-color: #f1f5f9;
        }

        .topbar-breadcrumb .breadcrumb {
            font-size: 0.8125rem;
        }

        .topbar-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            font-size: 0.95rem;
            color: var(--text-light);
            line-height: 1;
            padding: 0 0.4rem;
        }

        .topbar-search {
            width: 260px;
        }

        @media (min-width: 1200px) {
            .topbar-search {
                width: 320px;
            }
        }

        .topbar-search-input {
            border-radius: 20px;
            border: 1px solid var(--border);
            background-color: #f8fafc;
            font-size: 0.8125rem;
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
            transition: all 0.15s ease;
        }

        .topbar-search-input:focus {
            background-color: #ffffff;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-subtle);
            outline: none;
        }

        .search-shortcut {
            font-size: 0.7rem;
            background: #e2e8f0;
            border-radius: 4px;
            padding: 0.1rem 0.4rem;
            font-family: monospace;
            pointer-events: none;
        }

        .notification-badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
            transform: translate(-30%, -20%) !important;
        }

        .notification-dropdown-menu {
            width: 320px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
        }

        .badge-icon-sm {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .notification-text {
            font-size: 0.8125rem;
            line-height: 1.3;
        }

        .notification-time {
            font-size: 0.7rem;
        }

        .unread-notification {
            background-color: rgba(232, 168, 56, 0.04);
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background-color: var(--border);
            margin: 0 0.25rem;
        }

        .btn-topbar-user {
            border: none;
            background: transparent;
            border-radius: var(--radius-md);
            transition: background-color 0.15s ease;
        }

        .btn-topbar-user:hover {
            background-color: #f1f5f9;
        }

        .user-avatar-topbar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.75rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .user-avatar-topbar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .user-name-topbar {
            font-size: 0.8125rem;
        }

        .user-role-topbar {
            font-size: 0.7rem;
        }

        .user-dropdown-menu {
            min-width: 190px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
        }

        /* ─────────────────────────────────────────────────────────────
           7. PAGE HEADERS & COMMON CONTAINERS
        ───────────────────────────────────────────────────────────── */
        .app-content {
            flex-grow: 1;
            background-color: transparent;
            position: relative;
            z-index: 1;
            padding-top: calc(var(--topbar-height) + 1.25rem) !important;
            padding-bottom: 4.5rem !important;
            overflow-x: hidden !important;
            max-width: 100%;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.25;
        }

        .page-subtitle {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin: 0.25rem 0 0;
        }

        .breadcrumb-sm {
            font-size: 0.75rem;
            margin: 0;
            padding: 0;
            background: none;
        }

        .breadcrumb-sm .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            font-size: 0.85rem;
            padding: 0 0.35rem;
        }

        /* ─────────────────────────────────────────────────────────────
           8. REUSABLE CARDS & TABLES
        ───────────────────────────────────────────────────────────── */
        .card {
            background-color: #ffffff;
            border: 1px solid var(--border, #e5e7eb) !important;
            border-radius: 12px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .toolbar-card {
            background: var(--surface, #ffffff);
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 12px !important;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: #f8fafc;
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 20px;
            padding: 0.2rem 0.75rem;
            font-size: 0.75rem;
            color: var(--text-dark, #0f172a);
        }

        .data-card {
            background: var(--surface, #ffffff);
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 12px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .data-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border, #e5e7eb);
            background: #ffffff;
        }

        .data-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1.25rem;
            border-top: 1px solid var(--border, #e5e7eb);
            flex-wrap: wrap;
            gap: 0.5rem;
            background: #ffffff;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .data-table thead th, .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            padding: 0.8rem 1rem;
            border-bottom: 1px solid var(--border, #e5e7eb);
            white-space: nowrap;
        }

        .data-table tbody td, .table tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 0.90rem;
        }

        .data-table tbody tr:last-child td, .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Selected row and table hover: Pale Yellow */
        .data-table tbody tr:hover,
        .data-table tbody tr.selected,
        .data-table tbody tr.table-row-selected,
        .table tbody tr:hover,
        .table-hover tbody tr:hover {
            background-color: #fefce8 !important; /* Pale Yellow selected/hover row */
        }

        .student-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.82rem;
            flex-shrink: 0;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .student-name-link:hover {
            color: #d97706 !important;
        }

        /* ─────────────────────────────────────────────────────────────
           9. BADGES & LABELS
        ───────────────────────────────────────────────────────────── */
        .adm-badge {
            background: #f8fafc;
            border: 1px solid var(--border, #e5e7eb);
            padding: 0.22em 0.6em;
            border-radius: 6px;
            font-size: 0.78rem;
            color: #334155;
            font-family: monospace;
        }

        .badge {
            font-size: 0.78rem;
            line-height: 1.4;
            font-weight: 500;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.28em 0.75em;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
        }

        .badge-gender-male   { background: #e0f2fe; color: #0369a1; }
        .badge-gender-female { background: #fce7f3; color: #be185d; }
        .badge-gender-other  { background: #f1f5f9; color: #334155; }

        .badge-blood {
            background: #fef2f2;
            color: #dc2626;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 0.18em 0.55em;
            border-radius: 4px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25em 0.75em;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #334155;
        }

        .status-active, .status-approved, .status-paid { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
        .status-inactive, .status-unpaid, .status-rejected { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
        .status-pending, .status-warning { background: #fef9c3; color: #a16207; border-color: #fef08a; }
        .status-unknown { background: #f1f5f9; color: #64748b; border-color: #e2e8f0; }

        .count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: var(--text-muted, #64748b);
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.15em 0.6em;
            min-width: 22px;
        }

        .tab-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--gold-light, #fef3c7);
            color: var(--gold-text, #181b20);
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0 0.45em;
            margin-left: 0.25rem;
            min-width: 18px;
        }

        /* ─────────────────────────────────────────────────────────────
           10. BUTTONS
        ───────────────────────────────────────────────────────────── */
        .btn-primary {
            background-color: var(--gold, #f3b63a) !important;
            border-color: var(--gold, #f3b63a) !important;
            color: #181b20 !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            box-shadow: 0 1px 3px rgba(243, 182, 58, 0.25) !important;
            transition: all var(--transition-base) !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #e0a32e !important;
            border-color: #e0a32e !important;
            color: #111827 !important;
            box-shadow: 0 4px 12px rgba(243, 182, 58, 0.35) !important;
            transform: translateY(-1px);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }

        /* Global .btn-gold utility — used across many view files */
        .btn-gold {
            background-color: var(--gold, #f3b63a) !important;
            border-color: var(--gold, #f3b63a) !important;
            color: #181b20 !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            box-shadow: 0 1px 3px rgba(243, 182, 58, 0.25) !important;
            transition: all var(--transition-base) !important;
        }
        .btn-gold:hover, .btn-gold:focus {
            background-color: #e0a32e !important;
            border-color: #e0a32e !important;
            color: #111827 !important;
            box-shadow: 0 4px 12px rgba(243, 182, 58, 0.35) !important;
            transform: translateY(-1px);
        }
        .btn-gold:active {
            transform: scale(0.98);
        }
        .badge.bg-primary {
            background-color: var(--gold, #f3b63a) !important;
            color: #181b20 !important;
        }

        .btn-primary-action {
            background: var(--gold, #f3b63a) !important;
            border: 1px solid var(--gold, #f3b63a) !important;
            color: #181b20 !important;
            font-weight: 600 !important;
            font-size: 0.875rem;
            padding: 0.5rem 1.125rem;
            border-radius: 10px !important;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            text-decoration: none;
            transition: all var(--transition-base);
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(243, 182, 58, 0.25);
        }

        .btn-primary-action:hover {
            background: #e0a32e !important;
            border-color: #e0a32e !important;
            color: #111827 !important;
            box-shadow: 0 4px 12px rgba(243, 182, 58, 0.35);
            transform: translateY(-1px);
        }

        .btn-primary-action:active {
            transform: scale(0.98);
        }

        .btn-primary-action.btn-sm {
            font-size: 0.8125rem;
            padding: 0.35rem 0.95rem;
            border-radius: 8px !important;
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            text-decoration: none;
            transition: background-color var(--transition-base), border-color var(--transition-base), color var(--transition-base);
            cursor: pointer;
        }

        .btn-ghost:hover {
            background: #f1f5f9;
            border-color: var(--border-input);
            color: var(--text-dark);
        }

        .btn-ghost:active {
            transform: scale(0.98);
        }

        .btn-ghost.btn-sm {
            font-size: 0.8125rem;
            padding: 0.3rem 0.75rem;
        }

        .btn-link-sm {
            color: var(--gold-dark);
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-link-sm:hover {
            text-decoration: underline;
        }

        .btn-action-dots {
            background: transparent;
            border: 1px solid transparent;
            color: var(--text-muted);
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.9rem;
        }

        .btn-action-dots:hover {
            background: #ebf0b8ff;
            border-color: var(--border);
            color: var(--text-dark);
        }

        /* ─────────────────────────────────────────────────────────────
           11. FORMS & INPUTS — WHITE THEME (កាត Form និងតារាងពណ៌សលើផ្ទៃលឿង)
        ───────────────────────────────────────────────────────────── */
        /* CRUD / Data Entry Form Cards & Containers (White & Square / ពណ៌សរាងជ្រុង) */
        .form-card,
        .form-card.bg-white,
        .form-step-card,
        .form-step-card.bg-white,
        .note-form-card,
        .note-form-card.bg-white,
        .employee-form-card,
        .employee-form-card.bg-white,
        .as-form-card,
        .as-form-card.bg-white,
        .attendance-form-card,
        .attendance-form-card.bg-white,
        /* All cards inside data-modifying forms */
        form:not([method="GET" i]) .card,
        form:not([method="GET" i]) .card.bg-white,
        form:not([method="GET" i]) > div.bg-white,
        form:not([method="GET" i]) > div.p-3.bg-white,
        form:not([method="GET" i]) > div.p-4.bg-white,
        /* Containers wrapping data-modifying forms */
        .card:has(> form:not([method="GET" i])),
        .card:has(form:not([method="GET" i])),
        .card:has(form:not([method="GET" i])).bg-white,
        div:has(> form[action*="store"]):not(.modal-dialog):not(#app-layout):not(.container):not(.container-fluid):not(.row):not([class*="col-"]):not(body):not(html),
        div:has(> form[action*="update"]):not(.modal-dialog):not(#app-layout):not(.container):not(.container-fluid):not(.row):not([class*="col-"]):not(body):not(html),
        .col-12.col-lg-6 > div.bg-white:has(form),
        form.card:not([method="GET" i]),
        .page-banner,
        .note-page-banner,
        /* Modal dialog forms */
        .modal-content:has(form:not([method="GET" i])) {
            background-color: var(--form-card-bg, #ffffff) !important;
            border: 1px solid var(--form-card-border, #e2e8f0) !important;
            border-radius: 8px !important;
            box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,0.05));
        }

        /* Form Headers */
        .form-card-header,
        .form-card-header.bg-white,
        .form-step-card .step-header,
        .note-form-card .form-card-header,
        .employee-form-card .form-card-header,
        .employee-form-card .card-section-header,
        .as-form-card .form-card-header,
        form:not([method="GET" i]) .card-header,
        form:not([method="GET" i]) .card-header.bg-white,
        .card:has(form:not([method="GET" i])) .card-header,
        .card:has(form:not([method="GET" i])) .card-header.bg-white,
        .modal-content:has(form:not([method="GET" i])) .modal-header {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid var(--form-card-border, #e2e8f0) !important;
            background-color: var(--form-card-header-bg, #ffffff) !important;
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }

        .form-card-icon {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .form-card-title {
            font-family: var(--font-moul, 'Moul', 'Khmer OS Muol', serif);
            font-weight: 400;
            font-size: 1rem;
            color: #0f172a;
            line-height: 1.3;
        }

        .font-moul,
        .font-khmer-moul,
        .form-card-header h5 {
            font-family: var(--font-moul, 'Moul', 'Khmer OS Muol', serif) !important;
            font-weight: 400 !important;
            color: #0f172a;
            line-height: 1.4;
            white-space: nowrap;
        }

        .form-card-header h5 span {
            white-space: nowrap;
        }

        .form-card-header h5 .badge,
        .form-card-header h5 .badge-primary,
        .form-card-header h5 .text-muted,
        .form-card-header h5 small {
            font-family: var(--font-sans) !important;
            letter-spacing: normal;
        }

        .form-card-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.3;
        }

        /* ── Standard Harmonious Form Containers (កុំឲ្យមានតូចមានធំ ស្មើគ្នា និងនៅចំកណ្តាល) ── */
        .form-card,
        .note-form-card,
        .employee-form-card,
        .as-form-card,
        .form-step-card,
        .form-container-proportional,
        form:not([method="GET" i]).card,
        .card:has(> form:not([method="GET" i])):not(.table-card):not(:has(table)):not(:has(.table-responsive)) {
            max-width: 980px !important;
            width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            border-radius: 8px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
            background-color: #ffffff !important;
        }

        /* Page headers and banners above forms align to exact same width */
        .page-header:has(+ form),
        .page-header:has(+ * form),
        .form-page-header,
        .note-page-banner,
        .wizard-stepper,
        .alert-card {
            max-width: 980px !important;
            width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* Forms in col-12 or containers center uniformly at 980px */
        .col-12 > .form-card,
        .col-12 > form .form-card,
        .col-12 .form-card,
        .col-12 .note-form-card,
        .col-12 .employee-form-card,
        .col-12 .as-form-card {
            max-width: 980px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* Simpler single-column or compact forms */
        .form-card.compact-form,
        .form-card-compact,
        .card.card-form-compact {
            max-width: 640px !important;
            width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* Form spacing between fields */
        form:not([method="GET" i]) .mb-4,
        .form-card .mb-4 {
            margin-bottom: 1.15rem !important;
        }
        form:not([method="GET" i]) .mb-3,
        .form-card .mb-3 {
            margin-bottom: 0.95rem !important;
        }

        .form-card-body,
        .form-step-card .step-body,
        .employee-form-card .card-section-body,
        form:not([method="GET" i]) .card-body,
        .card:has(form:not([method="GET" i])) .card-body,
        .modal-content:has(form:not([method="GET" i])) .modal-body {
            padding: 1.15rem 1.35rem !important;
            background-color: var(--form-card-bg, #ffffff) !important;
        }

        /* ── All Modal Dialogs (Pure White, Sharp Shadow, on Top of Backdrop, Compact Sizing) ── */
        .modal {
            z-index: 1055 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-dialog {
            max-width: 480px;
            margin: 1.75rem auto;
        }
        .modal-dialog.modal-sm {
            max-width: 360px;
        }
        .modal-dialog.modal-lg {
            max-width: 720px;
        }
        .modal-dialog.modal-xl {
            max-width: 920px;
        }
        .modal-content {
            background-color: #ffffff !important;
            border: 1px solid var(--border, #e2e8f0) !important;
            border-radius: 8px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            color: var(--text-dark, #0f172a) !important;
            overflow: hidden !important;
        }
        .modal-content .modal-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid var(--border, #e2e8f0) !important;
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
            padding: 0.75rem 1.25rem !important;
        }
        .modal-content .modal-body {
            background-color: #ffffff !important;
            padding: 1.15rem 1.35rem !important;
        }
        .modal-content .modal-footer {
            background-color: #ffffff !important;
            border-top: 1px solid var(--border, #e2e8f0) !important;
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
            padding: 0.75rem 1.25rem !important;
        }

        /* Content & Table Cards (ផ្ទៃកាតតារាងជាពណ៌ស ស្អាតបាតលើផ្ទៃលឿង) */
        .table-card,
        .card:has(table),
        .card:has(.table-responsive) {
            background-color: var(--form-card-bg, #ffffff) !important;
            border: 1px solid var(--form-card-border, #e2e8f0) !important;
            border-radius: 8px !important;
            box-shadow: var(--shadow-xs, 0 1px 2px rgba(0,0,0,0.05));
            overflow: hidden;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* ── Soft & Harmonious Rounded Box Styles Across Dashboards, Forms & Cards ── */
        .card,
        .fin-card,
        .dash-card,
        .stat-card,
        .kpi-card,
        .data-card,
        .toolbar-card,
        .form-card,
        .note-form-card,
        .employee-form-card,
        .as-form-card,
        .form-step-card,
        .modal-content,
        .page-banner,
        .note-page-banner,
        .alert {
            border-radius: 8px !important;
        }

        .card-header,
        .form-card-header,
        .modal-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
        }

        .card-footer,
        .modal-footer {
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }

        .btn:not(.input-group > *),
        .form-control:not(.input-group > *),
        .form-select:not(.input-group > *),
        .form-control-custom:not(.input-group > *),
        .form-select-custom:not(.input-group > *),
        .flatpickr-input {
            border-radius: 6px !important;
        }

        /* ── Input Group Seamless Joining (ជាប់គ្នាជាប្រអប់តែមួយ មិនបែករាងមូលដាច់ពីគ្នា) ── */
        .input-group {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: stretch !important;
            position: relative;
        }

        .input-group > :first-child {
            border-top-left-radius: 6px !important;
            border-bottom-left-radius: 6px !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .input-group > :last-child {
            border-top-right-radius: 6px !important;
            border-bottom-right-radius: 6px !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .input-group > :not(:first-child):not(:last-child) {
            border-radius: 0 !important;
        }

        .input-group > .input-group-text,
        .input-group > .form-control,
        .input-group > .form-select,
        .input-group > .btn {
            height: 42px !important;
            min-height: 42px !important;
        }

        .input-group.input-group-sm > .input-group-text,
        .input-group.input-group-sm > .form-control,
        .input-group.input-group-sm > .form-select,
        .input-group.input-group-sm > .btn {
            height: 34px !important;
            min-height: 34px !important;
            font-size: 0.875rem !important;
        }

        .input-group .input-group-text {
            background-color: #ffffff !important;
            border: 1px solid var(--border-input, #cbd5e1) !important;
            color: #64748b !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 0.75rem !important;
        }

        .input-group > .border-end-0,
        .input-group-text.border-end-0 {
            border-right: none !important;
        }

        .input-group > .border-start-0,
        .form-control.border-start-0 {
            border-left: none !important;
            padding-left: 0.4rem !important;
        }

        .input-group:focus-within > .input-group-text {
            border-color: var(--gold, #f59e0b) !important;
        }
        .input-group:focus-within > .form-control {
            border-color: var(--gold, #f59e0b) !important;
            box-shadow: none !important;
        }

        .badge {
            border-radius: 4px !important;
        }

        .dropdown-menu {
            border-radius: 8px !important;
        }

        .rounded-pill,
        .wizard-step-tab,
        .nav-pills .nav-link {
            border-radius: 20px !important;
        }

        /* Circular elements: avatars and numbering badges only */
        .user-avatar,
        .student-avatar,
        .student-avatar-xs,
        .rounded-circle,
        .brand-logo-img,
        .step-badge,
        .avatar-circle {
            border-radius: 50% !important;
        }

        .table-card .filter-bar {
            background-color: var(--form-input-bg, #ffffff) !important;
            border-bottom: 1px solid var(--border, #e2e8f0) !important;
            padding: 0.75rem 1rem !important;
        }
        .filter-bar:not(.table-card .filter-bar) {
            background-color: var(--form-input-bg, #ffffff) !important;
            border: 1px solid var(--border, #e2e8f0) !important;
            border-radius: 8px !important;
            padding: 0.85rem 1.15rem !important;
            box-shadow: var(--shadow-xs);
        }

        .table-card .table-custom thead th,
        .table-card table thead th {
            background-color: #f8fafc !important;
            color: var(--text-dark, #0f172a) !important;
            border-bottom: 1px solid var(--border, #e2e8f0) !important;
        }

        .table-card .table-custom tbody tr,
        .table-card table tbody tr {
            background-color: #ffffff;
        }

        .table-card .table-custom tbody tr:nth-child(even),
        .table-card table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .table-card .table-custom tbody td,
        .table-card table tbody td {
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .table-card .table-custom tbody tr:hover td,
        .table-card table tbody tr:hover td {
            background-color: #f1f5f9 !important;
        }

        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        /* ─────────────────────────────────────────────────────────────
           MASTER UNIFIED FORM & UI TYPOGRAPHY (ស្មើគ្នាទូទាំងប្រព័ន្ធ - ច្បាស់ ស្រួលមើល មិនតូចពេក)
        ───────────────────────────────────────────────────────────── */
        /* Page Header Titles */
        .page-title,
        h1.page-title {
            font-size: 1.35rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            line-height: 1.35 !important;
            letter-spacing: normal !important;
        }

        .page-subtitle,
        p.page-subtitle {
            font-size: 0.92rem !important;
            color: #64748b !important;
            line-height: 1.45 !important;
        }

        /* Section Headings & Card Headers */
        .form-card-title,
        .section-heading,
        .card-title,
        .form-card-header h5 {
            font-size: 1.125rem !important;
            font-weight: 600 !important;
            line-height: 1.4 !important;
        }

        .section-subheading,
        .form-card-subtitle {
            font-size: 0.88rem !important;
            color: #64748b !important;
            font-weight: 400 !important;
        }

        /* All Form Labels (ស្មើគ្នា ០.៩៨rem / ~16.2px លើគ្រប់ Form ទាំងអស់) */
        .form-label,
        .form-label-custom,
        label:not(.form-check-label):not(.btn) {
            font-family: var(--font-sans) !important;
            font-size: 0.98rem !important; /* ~16.2px ងាយស្រួលអានសម្រាប់អក្សរខ្មែរ */
            font-weight: 600 !important;
            color: #1e293b !important;
            margin-bottom: 0.35rem !important;
            display: inline-flex !important;
            align-items: center !important;
            line-height: 1.45 !important;
        }

        .form-check-label {
            font-family: var(--font-sans) !important;
            font-size: 0.96rem !important;
            color: #1e293b !important;
            line-height: 1.45 !important;
        }

        /* All Form Inputs & Selects (ស្មើគ្នា 42px និង 0.96rem / ~15.8px ទូទាំងប្រព័ន្ធ មិនកៀបជើងអក្សរ) */
        .form-control:not(.input-group > *),
        .form-control-custom,
        .modal-form-control,
        .flatpickr-input:not([type="hidden"]),
        input:not(.input-group > *):not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="range"]):not([type="file"]):not([type="hidden"]),
        select:not(.input-group > *),
        .form-select:not(.input-group > *),
        .form-select-custom,
        .filter-select {
            font-family: var(--font-sans) !important;
            background-color: #ffffff;
            font-size: 0.96rem !important; /* ~15.8px */
            line-height: 1.45 !important;
            padding: 0.42rem 0.85rem;
            height: 42px !important;
            min-height: 42px !important;
            border: 1px solid var(--border-input, #cbd5e1) !important;
            border-radius: 6px !important;
            color: #0f172a !important;
            box-sizing: border-box !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
        }

        /* ── Modern Integrated Search Input (ប្រអប់ស្វែងរកទំនើប មាន Icon នៅខាងក្នុង មិនបែកប្រអប់) ── */
        .search-input-wrap {
            position: relative !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
        }
        .search-input-wrap > i,
        .search-input-wrap > .bi,
        .search-input-wrap i.bi-search {
            position: absolute !important;
            left: 0.75rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #64748b !important;
            font-size: 0.85rem !important;
            pointer-events: none !important;
            z-index: 3 !important;
            line-height: 1 !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .search-input-wrap > .form-control,
        .search-input-wrap > input {
            padding-left: 2.15rem !important;
            padding-right: 0.75rem !important;
            width: 100% !important;
            border-radius: 6px !important;
            border: 1px solid var(--border-input, #cbd5e1) !important;
            background-color: #ffffff !important;
        }

        /* Dropdown Select Controls */
        .form-select,
        .form-select-custom,
        .filter-select,
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 14px 10px !important;
            padding-right: 2.3rem !important;
            cursor: pointer !important;
        }

        select::-ms-expand,
        .form-select::-ms-expand {
            display: none !important;
        }

        /* Textarea inputs maintain auto-height with proportional minimum */
        textarea,
        textarea.form-control,
        textarea.form-control-custom {
            height: auto !important;
            min-height: 85px !important;
            padding: 0.5rem 0.85rem !important;
            line-height: 1.55 !important;
            font-size: 0.96rem !important;
        }

        /* Compact small inputs when explicitly requested with .form-control-sm */
        .form-control-sm,
        .form-select-sm,
        input.form-control-sm,
        select.form-select-sm {
            height: 35px !important;
            min-height: 35px !important;
            font-size: 0.9rem !important;
            padding: 0.25rem 0.65rem;
        }

        /* Focus states across all forms */
        .form-control:focus,
        .form-select:focus,
        .form-control-custom:focus,
        .form-select-custom:focus,
        .modal-form-control:focus,
        input:focus,
        select:focus,
        textarea:focus {
            background-color: #ffffff !important;
            border-color: var(--gold, #f59e0b) !important;
            box-shadow: 0 0 0 2.5px rgba(245, 158, 11, 0.18) !important;
            outline: none !important;
        }

        /* Standard Form Action Buttons (មិនប៉ះពាល់លើប៊ូតុង Icon, Navbar, ឬ Sidebar ឡើយ) */
        .btn:not(.btn-topbar-icon):not(#sidebarCloseBtn):not(#sidebarToggleBtn):not(.btn-icon):not(.btn-link):not(.btn-close):not(.dropdown-toggle):not(.btn-sm):not(.page-link),
        .btn-save-custom,
        .btn-cancel-custom,
        .btn-primary-action {
            font-family: var(--font-sans) !important;
            font-size: 0.98rem !important; /* ~16.2px */
            font-weight: 600 !important;
            line-height: 1.4 !important;
            height: 42px !important;
            min-height: 42px !important;
            padding: 0.42rem 1.15rem !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px !important;
            box-sizing: border-box !important;
        }

        /* Small Buttons (.btn-sm) */
        .btn-sm,
        .btn-cancel-custom.btn-sm,
        .btn-save-custom.btn-sm {
            height: 34px !important;
            min-height: 34px !important;
            font-size: 0.9rem !important;
            padding: 0.25rem 0.8rem !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .btn-outline-primary,
        .btn-outline-gold {
            border-color: var(--gold, #f59e0b) !important;
            color: var(--gold-dark, #b45309) !important;
            background-color: transparent !important;
        }
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-gold:hover,
        .btn-outline-gold:focus {
            background-color: var(--gold, #f59e0b) !important;
            border-color: var(--gold, #f59e0b) !important;
            color: #ffffff !important;
        }

        /* ── Fractional Spacing Utilities (Tailwind-like classes in Bootstrap templates) ── */
        .gap-1\.5 { gap: 0.5rem !important; }
        .gap-2\.5 { gap: 0.75rem !important; }
        .gap-3\.5 { gap: 1rem !important; }
        .p-1\.5   { padding: 0.375rem !important; }
        .p-2\.5   { padding: 0.625rem !important; }
        .p-3\.5   { padding: 0.875rem !important; }
        .py-1\.5  { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
        .py-2\.5  { padding-top: 0.625rem !important; padding-bottom: 0.625rem !important; }
        .px-1\.5  { padding-left: 0.375rem !important; padding-right: 0.375rem !important; }
        .px-2\.5  { padding-left: 0.625rem !important; padding-right: 0.625rem !important; }
        .px-3\.5  { padding-left: 0.875rem !important; padding-right: 0.875rem !important; }
        .mb-1\.5  { margin-bottom: 0.375rem !important; }
        .mb-2\.5  { margin-bottom: 0.75rem !important; }
        .mb-3\.5  { margin-bottom: 1rem !important; }
        .mt-1\.5  { margin-top: 0.375rem !important; }
        .mt-2\.5  { margin-top: 0.75rem !important; }
        .pt-1\.5  { padding-top: 0.375rem !important; }
        .g-1\.5   { --bs-gutter-x: 0.5rem; --bs-gutter-y: 0.5rem; }
        .g-2\.5   { --bs-gutter-x: 0.75rem; --bs-gutter-y: 0.75rem; }
        .g-3\.5   { --bs-gutter-x: 1rem; --bs-gutter-y: 1rem; }

        /* Topbar & Sidebar Icon Buttons Protected Sizing */
        .btn-topbar-icon,
        #sidebarToggleBtn {
            width: 36px !important;
            height: 36px !important;
            min-height: 36px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border: 1px solid transparent !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        #sidebarCloseBtn {
            width: 32px !important;
            height: 32px !important;
            min-height: 32px !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        @media (min-width: 992px) {
            #sidebarCloseBtn,
            .d-lg-none {
                display: none !important;
            }
        }

        /* Error feedback & Help texts (0.9rem / ~14.9px ច្បាស់ មិនតូចកំប៉ិចកំប៉ុក) */
        .form-hint,
        .form-text,
        .field-hint,
        .invalid-feedback,
        .step-error-feedback,
        small,
        .small {
            font-family: var(--font-sans) !important;
            font-size: 0.9rem !important; /* ~14.9px */
            line-height: 1.45 !important;
        }

        .step-error-feedback,
        .invalid-feedback {
            color: #dc2626 !important;
            margin-top: 0.3rem !important;
        }

        /* Table font normalization */
        .table-custom thead th,
        .table thead th,
        table thead th {
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            padding: 0.75rem 0.95rem !important;
        }

        .table-custom tbody td,
        .table tbody td,
        table tbody td {
            font-size: 0.98rem !important;
            padding: 0.75rem 0.95rem !important;
            line-height: 1.6 !important;
        }

        .badge {
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            padding: 0.35em 0.65em !important;
        }

        /* ── Search Input Containers and Icons (ធានាកុំឱ្យ Icon ជាន់លើអក្សរ) ── */
        .search-wrapper,
        .topbar-search,
        .topbar-search form,
        .filter-inputs .search-wrapper,
        .filter-bar .search-wrapper,
        div:has(> i.bi-search),
        div:has(> .bi-search) {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
        }

        .search-wrapper > i,
        .search-wrapper > .bi,
        .search-wrapper i.bi-search,
        .topbar-search i,
        .topbar-search .bi,
        .topbar-search i.bi-search,
        .filter-inputs .search-wrapper i,
        .filter-inputs .search-wrapper .bi,
        div:has(> i.bi-search) > i.bi-search,
        div:has(> .bi-search) > .bi-search {
            position: absolute !important;
            left: 0.85rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #64748b !important;
            font-size: 1rem !important;
            pointer-events: none !important;
            z-index: 10 !important;
            line-height: 1 !important;
            margin: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Search inputs: Must have at least 2.75rem (44px) padding-left so placeholder/text never overlaps with icon */
        .search-wrapper input,
        .search-wrapper input[type="text"],
        .search-wrapper input[type="search"],
        .search-wrapper .form-control,
        .filter-inputs .search-wrapper input,
        .filter-bar .search-wrapper input,
        .topbar-search input,
        .topbar-search .form-control,
        .topbar-search-input,
        input.topbar-search-input,
        input.ps-5 {
            padding-left: 2.75rem !important;
            padding-right: 1rem !important;
            font-size: 0.95rem !important;
            height: 42px !important;
            min-height: 42px !important;
            box-sizing: border-box !important;
            width: 100% !important;
        }

        .topbar-search-input,
        input.topbar-search-input {
            padding-right: 2.5rem !important; /* space for the '/' shortcut badge */
            height: 38px !important;
            min-height: 38px !important;
        }

        input:disabled,
        select:disabled,
        textarea:disabled,
        input[readonly],
        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f1f5f9 !important;
        }

        .required-mark {
            color: var(--color-error-text, #dc2626);
        }

        .form-control-custom {
            border: 1px solid var(--border-input);
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            height: 38px !important;
            min-height: 38px !important;
            padding: 0.35rem 0.75rem !important;
            transition: border-color var(--transition-base), box-shadow var(--transition-base);
            color: var(--text-dark);
            line-height: 1.45;
        }

        .form-control-custom:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-subtle);
            outline: none;
        }

        .form-control-custom.is-invalid {
            border-color: var(--color-error-text);
        }

        .info-note {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.875rem;
            font-size: 0.78rem;
            color: #0369a1;
        }

        /* ─────────────────────────────────────────────────────────────
           12. ALERTS & EMPTY STATES
        ───────────────────────────────────────────────────────────── */
        .alert-card {
            border-radius: var(--radius-md);
            padding: 0.875rem 1.125rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .alert-card-danger {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .empty-state {
            padding: 3.5rem 2rem;
            text-align: center;
        }

        .empty-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #f1f5f9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 0.35rem;
        }

        .empty-subtitle {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin: 0 0 1.25rem;
        }

        .empty-inline {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.8125rem;
        }

        /* ─────────────────────────────────────────────────────────────
           13. DETAILS PAGE (HERO, TABS, STATS)
        ───────────────────────────────────────────────────────────── */
        .student-profile-hero {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-xs);
        }

        .hero-avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: var(--gold-light);
            color: var(--gold-dark);
            font-size: 1.4rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-name {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
        }

        .hero-meta {
            font-size: 0.78rem;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
        }

        .nav-tabs-custom {
            border-bottom: 2px solid var(--border);
            gap: 0;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 0;
            margin: 0;
            list-style: none;
            display: flex;
        }

        .nav-tabs-custom .nav-item { flex-shrink: 0; }

        .nav-link-custom {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.6rem 1rem;
            font-size: 0.825rem;
            font-weight: 500;
            color: var(--text-muted);
            border: none;
            border-bottom: 2px solid transparent;
            background: none;
            cursor: pointer;
            margin-bottom: -2px;
            transition: color 0.15s, border-color 0.15s;
            white-space: nowrap;
        }

        .nav-link-custom:hover {
            color: var(--gold-text);
        }

        .nav-link-custom.active {
            color: var(--gold-text);
            border-bottom-color: var(--gold);
            font-weight: 600;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.125rem 1.375rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow-xs);
            transition: transform var(--transition-base), box-shadow var(--transition-base);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 0.15rem;
        }

        .stat-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .detail-row {
            display: flex;
            align-items: baseline;
            gap: 1rem;
            padding: 0.65rem 0;
            border-bottom: 1px solid var(--border-soft);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            min-width: 130px;
            flex-shrink: 0;
        }

        .detail-value {
            font-size: 0.875rem;
            color: var(--text-dark);
        }

        .medical-section {
            background: #fafafa;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 1rem;
            height: 100%;
        }

        .medical-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 0.65rem;
            display: flex;
            align-items: center;
        }

        .medical-section-content {
            font-size: 0.875rem;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* ─────────────────────────────────────────────────────────────
           14. GLOBAL PAGINATION & DROPDOWNS
        ───────────────────────────────────────────────────────────── */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            font-size: 0.8rem;
            padding: 0.3rem 0.65rem;
            border-radius: var(--radius-sm);
            border-color: var(--border);
            color: var(--text-muted);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--gold);
            border-color: var(--gold);
            color: #ffffff;
            font-weight: 600;
        }

        .pagination .page-link:focus {
            box-shadow: 0 0 0 3px var(--gold-subtle);
        }

        .dropdown-menu {
            border-radius: var(--radius-md);
            border-color: var(--border);
        }

        .dropdown-item {
            font-size: 0.8125rem;
            border-radius: 4px;
            padding: 0.4rem 0.85rem;
        }

        .dropdown-item:active {
            background-color: var(--gold-light);
            color: var(--gold-dark);
        }

        .app-footer {
            position: fixed !important;
            bottom: 0 !important;
            right: 0 !important;
            left: var(--sidebar-width) !important;
            z-index: 1020 !important;
            background-color: var(--footer-bg, #ffffff) !important;
            border-top: 1px solid var(--footer-border, #e2e8f0) !important;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.04) !important;
            transition: left 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        #app-layout.sidebar-collapsed .app-footer {
            left: var(--sidebar-collapsed-width) !important;
        }

        @media (max-width: 991.98px) {
            .app-footer {
                left: 0 !important;
            }
        }

        /* ─────────────────────────────────────────────────────────────
           GLOBAL PRINT DEFAULTS (បោះពុម្ពជាពណ៌សសុទ្ធ គ្មានផ្ទៃលឿង)
        ───────────────────────────────────────────────────────────── */
        @media print {
            .app-main-wrapper::before {
                display: none !important;
            }
            html, body, #app-layout, .app-main-wrapper, .app-content, .container-fluid, .content-wrapper {
                background: #ffffff !important;
                background-color: #ffffff !important;
                background-image: none !important;
                color: #000000 !important;
            }
            .app-sidebar, .app-topbar, .app-footer, .sidebar-backdrop {
                display: none !important;
            }
        }
        /* ─────────────────────────────────────────────────────────────
           FLATPICKR DATEPICKER (ទម្រង់ថ្ងៃមុនខែឆ្នាំ dd/mm/yyyy)
        ───────────────────────────────────────────────────────────── */
        .flatpickr-calendar {
            font-family: var(--font-sans);
            border-radius: 10px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            font-size: 13px;
        }
        .flatpickr-calendar .flatpickr-months {
            background: #f8fafc;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding: 4px 0;
        }
        .flatpickr-current-month {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
            padding-top: 4px;
        }
        .flatpickr-day {
            border-radius: 6px;
            font-weight: 500;
            color: #334155;
            height: 36px;
            line-height: 36px;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        .flatpickr-day.today {
            border-color: #f59e0b !important;
        }
        .flatpickr-day:hover {
            background: #fef3c7 !important;
            border-color: #fef3c7 !important;
            color: #78350f !important;
        }
        .flatpickr-input[readonly], .flatpickr-input:not([type="hidden"]) {
            background-color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 16px 16px !important;
            padding-right: 2.25rem !important;
            cursor: pointer;
        }
        .flatpickr-input.form-control-sm {
            background-position: right 0.5rem center !important;
            padding-right: 1.8rem !important;
        }
        .flatpickr-input.form-control-custom {
            height: 35px !important;
            line-height: 35px !important;
            font-size: 0.8rem;
        }
        .flatpickr-input.is-invalid {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.15) !important;
        }

        /* ═══════════════════════════════════════════════════════════════
           SHADCN-INSPIRED CLEAN, SPACIOUS, STATIC UX/UI SYSTEM
           Strict zero animations, pure white cards, Warm Amber (#F59E0B)
           ═══════════════════════════════════════════════════════════════ */
        .app-main-wrapper,
        .app-main-wrapper *,
        .app-content,
        .card,
        .data-card,
        .toolbar-card,
        .kpi-card,
        .btn,
        .table,
        .table tr,
        .table td,
        .table th,
        .dropdown-menu,
        .dropdown-item,
        .modal,
        .modal-dialog,
        .modal-content,
        .nav-tabs,
        .tab-pane,
        .form-control,
        .form-select,
        .badge {
            transition: none !important;
            animation: none !important;
            transform: none !important;
        }

        /* Instant flat hover backgrounds without lift, movement or scaling */
        .table tbody tr:hover,
        .data-table tbody tr:hover,
        .table-row-hover:hover {
            background-color: #f9fafb !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .dropdown-menu .dropdown-item:hover,
        .dropdown-menu .dropdown-item:focus {
            background-color: #f9fafb !important;
            color: #0f172a !important;
        }

        /* Pure white cards with 1px border (#E2E8F0) and 12px rounded corners */
        .card,
        .data-card,
        .toolbar-card,
        .dash-card,
        .dash-kpi-card,
        .stat-card,
        .kpi-card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: none !important;
            transform: none !important;
        }
        .card:hover,
        .data-card:hover,
        .dash-card:hover,
        .dash-kpi-card:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        /* Primary Action Buttons: Solid Warm Amber/Yellow (#F59E0B) with dark/black text (#111827) */
        .btn-primary-action,
        .btn-gold,
        .btn-primary {
            background: #f59e0b !important;
            border: 1px solid #f59e0b !important;
            color: #111827 !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            transform: none !important;
        }
        .btn-primary-action:hover,
        .btn-gold:hover,
        .btn-primary:hover,
        .btn-primary-action:focus,
        .btn-gold:focus,
        .btn-primary:focus {
            background: #d97706 !important;
            border-color: #d97706 !important;
            color: #111827 !important;
            box-shadow: none !important;
            transform: none !important;
        }

        /* Clean Outline Action Buttons */
        .btn-outline-white,
        .btn-outline-secondary,
        .btn-outline-action {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #334155 !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
        }
        .btn-outline-white:hover,
        .btn-outline-secondary:hover,
        .btn-outline-action:hover {
            background-color: #f9fafb !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        /* Numbered pagination with amber active button */
        .pagination {
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
            margin-bottom: 0 !important;
        }
        .pagination .page-item.active .page-link,
        .pagination .page-link.active,
        .page-square.active {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #111827 !important;
            font-weight: 700 !important;
            border-radius: 6px !important;
            box-shadow: none !important;
        }
        .pagination .page-link {
            color: #334155 !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
            border-radius: 6px !important;
            padding: 0.35rem 0.75rem !important;
            font-size: 0.84rem !important;
            transition: none !important;
            box-shadow: none !important;
        }
        .pagination .page-link:hover {
            background-color: #f9fafb !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        .pagination .page-item.disabled .page-link {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #94a3b8 !important;
            opacity: 0.6 !important;
        }
    </style>

    @stack('styles')
</head>
<body>

    <div id="app-layout">

        {{-- 1. Sidebar --}}
        @include('layouts.sidebar')

        {{-- 2. Main Application Wrapper --}}
        <div class="app-main-wrapper">

            {{-- Top Navbar --}}
            @include('layouts.navbar')

            {{-- Main Content Viewport --}}
            <main class="app-content py-3 py-lg-4">
                <div class="container-fluid px-3 px-lg-4">

                    {{-- Flash Messages Banner --}}
                    @if(session('success'))
                        <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-4 py-2 px-3 shadow-xs"
                             style="background:#dcfce7;border:1px solid #bbf7d0;color:#15803d;border-radius:var(--radius-md);font-size:.85rem"
                             role="alert">
                            <i class="bi bi-check-circle-fill flex-shrink-0 fs-6"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close ms-auto btn-close-sm" data-bs-dismiss="alert" aria-label="Close" style="font-size:.7rem"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-4 py-2 px-3 shadow-xs"
                             style="background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;border-radius:var(--radius-md);font-size:.85rem"
                             role="alert">
                            <i class="bi bi-exclamation-circle-fill flex-shrink-0 fs-6"></i>
                            <span>{{ session('error') }}</span>
                            <button type="button" class="btn-close ms-auto btn-close-sm" data-bs-dismiss="alert" aria-label="Close" style="font-size:.7rem"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 mb-4 py-2 px-3 shadow-xs"
                             style="background:#fef9c3;border:1px solid #fef08a;color:#854d0e;border-radius:var(--radius-md);font-size:.85rem"
                             role="alert">
                            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-6"></i>
                            <span>{{ session('warning') }}</span>
                            <button type="button" class="btn-close ms-auto btn-close-sm" data-bs-dismiss="alert" aria-label="Close" style="font-size:.7rem"></button>
                        </div>
                    @endif

                    {{-- Optional Page Header slot --}}
                    @yield('page-header')

                    {{-- View Body Content --}}
                    @yield('content')

                </div>
            </main>

            {{-- Footer --}}
            @include('layouts.footer')

        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Flatpickr JS (System-wide dd/mm/yyyy date format) -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function initGlobalDatePicker(container) {
            if (typeof flatpickr === 'undefined') return;
            const scope = container || document;
            const dateInputs = scope.querySelectorAll('input[type="date"]');

            dateInputs.forEach(function (el) {
                if (el._flatpickr) return;

                const initialVal = el.value || el.getAttribute('value') || '';
                const placeholder = el.getAttribute('placeholder') || 'dd/mm/yyyy';
                const minDate = el.getAttribute('min') || undefined;
                const maxDate = el.getAttribute('max') || undefined;
                const baseClass = el.className || 'form-control';

                const fp = flatpickr(el, {
                    dateFormat: "Y-m-d",      // Backend submission format (Laravel Y-m-d)
                    altInput: true,          // User-facing display input
                    altFormat: "d/m/Y",       // Strictly display as dd/mm/yyyy (ថ្ងៃ/ខែ/ឆ្នាំ)
                    allowInput: true,         // Allows keyboard typing
                    altInputClass: baseClass, // Preserve input styling classes
                    defaultDate: initialVal || undefined,
                    minDate: minDate,
                    maxDate: maxDate,
                    parseDate: function(dateStr, format) {
                        if (typeof dateStr === 'string' && /^\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4}$/.test(dateStr.trim())) {
                            const parts = dateStr.trim().split(/[\/\-\.]/);
                            const day = parseInt(parts[0], 10);
                            const month = parseInt(parts[1], 10) - 1;
                            const year = parseInt(parts[2], 10);
                            return new Date(year, month, day);
                        }
                        return flatpickr.parseDate(dateStr, format);
                    },
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.altInput) {
                            instance.altInput.placeholder = placeholder;
                            if (el.classList.contains('is-invalid')) {
                                instance.altInput.classList.add('is-invalid');
                            }
                            if (el.disabled) {
                                instance.altInput.disabled = true;
                            }
                            if (el.readOnly) {
                                instance.altInput.readOnly = true;
                            }
                        }
                    },
                    onChange: function(selectedDates, dateStr, instance) {
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });

                // Dynamically sync class changes (e.g. form validation adding is-invalid)
                const observer = new MutationObserver(function () {
                    if (fp && fp.altInput) {
                        if (el.classList.contains('is-invalid')) {
                            fp.altInput.classList.add('is-invalid');
                        } else {
                            fp.altInput.classList.remove('is-invalid');
                        }
                        if (el.disabled !== fp.altInput.disabled) {
                            fp.altInput.disabled = el.disabled;
                        }
                    }
                });
                observer.observe(el, { attributes: true, attributeFilter: ['class', 'disabled', 'readonly'] });

                // Connect label click to open flatpickr
                if (el.id) {
                    const label = document.querySelector(`label[for="${el.id}"]`);
                    if (label) {
                        label.addEventListener('click', function(e) {
                            if (fp && fp.altInput) {
                                e.preventDefault();
                                fp.altInput.focus();
                                fp.open();
                            }
                        });
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initGlobalDatePicker();
        });

        document.addEventListener('shown.bs.modal', function (e) {
            initGlobalDatePicker(e.target);
        });

        window.initGlobalDatePicker = initGlobalDatePicker;
    </script>

    <!-- Shared Layout Navigation Interactivity Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const layout = document.getElementById('app-layout');
            const sidebar = document.getElementById('app-sidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            const backdrop = document.getElementById('sidebarBackdrop');
            const searchInput = document.querySelector('.topbar-search-input');

            // Initialize Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (el) {
                return new bootstrap.Tooltip(el, { boundary: document.body });
            });

            // Restore collapsed state on desktop from localStorage
            const COLLAPSED_KEY = 'school_sidebar_collapsed';
            if (window.innerWidth >= 992 && localStorage.getItem(COLLAPSED_KEY) === 'true') {
                layout.classList.add('sidebar-collapsed');
            }

            // Desktop & Mobile toggle handler
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    if (window.innerWidth >= 992) {
                        layout.classList.toggle('sidebar-collapsed');
                        localStorage.setItem(COLLAPSED_KEY, layout.classList.contains('sidebar-collapsed'));
                    } else {
                        sidebar.classList.toggle('show');
                        backdrop.classList.toggle('show');
                    }
                });
            }

            // Mobile close button handler
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }

            // Backdrop click handler
            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }

            // Global '/' shortcut for Search focus
            document.addEventListener('keydown', function (e) {
                if (e.key === '/' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
                    if (searchInput) {
                        e.preventDefault();
                        searchInput.focus();
                    }
                }
            });

            // Auto dismiss success alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    if (bsAlert) bsAlert.close();
                });
            }, 5000);

            // ── Sidebar Scroll & Menu State Persistence ──
            const sidebarNavWrapper = document.querySelector('.sidebar-nav-wrapper');
            if (sidebarNavWrapper) {
                const SCROLL_KEY = 'school_sidebar_scroll';
                const OPEN_MENUS_KEY = 'school_sidebar_open_menus';
                let isRestoring = true;

                // 1. First, expand all remembered collapse menus so scroll height is accurate immediately
                let openMenus = [];
                try {
                    openMenus = JSON.parse(sessionStorage.getItem(OPEN_MENUS_KEY) || '[]');
                } catch(e) { openMenus = []; }

                openMenus.forEach(function (menuId) {
                    const el = document.getElementById(menuId);
                    if (el && el.classList.contains('collapse')) {
                        el.classList.add('show');
                        const toggle = document.querySelector(`[data-bs-toggle="collapse"][href="#${menuId}"]`);
                        if (toggle) {
                            toggle.classList.remove('collapsed');
                            toggle.setAttribute('aria-expanded', 'true');
                        }
                    }
                });

                // 2. Ensure the active item's parent collapse menu is open
                const activeItem = sidebarNavWrapper.querySelector('.sub-link.active, .sidebar-link.active');
                if (activeItem) {
                    const parentCollapse = activeItem.closest('.collapse');
                    if (parentCollapse) {
                        parentCollapse.classList.add('show');
                        const toggleTrigger = document.querySelector(`[data-bs-toggle="collapse"][href="#${parentCollapse.id}"]`);
                        if (toggleTrigger) {
                            toggleTrigger.classList.remove('collapsed');
                            toggleTrigger.setAttribute('aria-expanded', 'true');
                        }
                    }
                }

                // 3. Restore saved scroll position reliably across layout rendering phases
                const savedScroll = sessionStorage.getItem(SCROLL_KEY);
                const applyScroll = function () {
                    if (savedScroll !== null) {
                        sidebarNavWrapper.scrollTop = parseInt(savedScroll, 10);
                    } else if (activeItem) {
                        activeItem.scrollIntoView({ block: 'nearest', behavior: 'instant' });
                    }
                };

                applyScroll();
                requestAnimationFrame(applyScroll);
                setTimeout(applyScroll, 80);
                setTimeout(function () {
                    applyScroll();
                    isRestoring = false;
                }, 350);

                // 4. Save scroll position on user scroll (ignoring spurious resets during initial load)
                sidebarNavWrapper.addEventListener('scroll', function () {
                    if (!isRestoring) {
                        sessionStorage.setItem(SCROLL_KEY, sidebarNavWrapper.scrollTop);
                    }
                }, { passive: true });

                // 5. Prevent hash jumping when toggling accordion headers
                sidebarNavWrapper.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (toggle) {
                    toggle.addEventListener('click', function () {
                        const curScroll = sidebarNavWrapper.scrollTop;
                        setTimeout(function () {
                            sidebarNavWrapper.scrollTop = curScroll;
                            if (!isRestoring) sessionStorage.setItem(SCROLL_KEY, curScroll);
                        }, 0);
                    });
                });

                // 6. Save scroll position immediately on ANY click or form submission anywhere on the page
                document.addEventListener('click', function (e) {
                    if (e.target.closest('a') || e.target.closest('button') || e.target.closest('.sub-link') || e.target.closest('.sidebar-link')) {
                        sessionStorage.setItem(SCROLL_KEY, sidebarNavWrapper.scrollTop);
                    }
                }, true);

                document.addEventListener('submit', function () {
                    sessionStorage.setItem(SCROLL_KEY, sidebarNavWrapper.scrollTop);
                }, true);

                window.addEventListener('beforeunload', function () {
                    sessionStorage.setItem(SCROLL_KEY, sidebarNavWrapper.scrollTop);
                });

                window.addEventListener('pagehide', function () {
                    sessionStorage.setItem(SCROLL_KEY, sidebarNavWrapper.scrollTop);
                });

                // 7. Track opened / closed collapse menus
                document.querySelectorAll('.sidebar-nav-wrapper .collapse').forEach(function (collapseEl) {
                    collapseEl.addEventListener('shown.bs.collapse', function () {
                        let current = [];
                        try { current = JSON.parse(sessionStorage.getItem(OPEN_MENUS_KEY) || '[]'); } catch(e) { current = []; }
                        if (!current.includes(this.id)) {
                            current.push(this.id);
                            sessionStorage.setItem(OPEN_MENUS_KEY, JSON.stringify(current));
                        }
                    });

                    collapseEl.addEventListener('hidden.bs.collapse', function () {
                        let current = [];
                        try { current = JSON.parse(sessionStorage.getItem(OPEN_MENUS_KEY) || '[]'); } catch(e) { current = []; }
                        current = current.filter(function (id) { return id !== collapseEl.id; });
                        sessionStorage.setItem(OPEN_MENUS_KEY, JSON.stringify(current));
                    });
                });
            }
        });

        // Ensure all Bootstrap modals are always appended to body when shown so they are NEVER trapped behind modal-backdrop
        document.addEventListener('show.bs.modal', function (event) {
            if (event.target && event.target.parentElement !== document.body) {
                document.body.appendChild(event.target);
            }
        });
    </script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>
