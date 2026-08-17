<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token"      content="<?= csrf_hash() ?>">
    
    <!-- SEO Best Practices -->
    <meta name="description" content="Sistem Manajemen Stok Donasi Foodbank Indonesia berbasis FEFO (First Expired First Out).">
    
    <!-- Performance Optimization (Lighthouse) -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    
    <title><?= $title ?? 'WMS Foodbank Indonesia' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    </noscript>

    <style>
        @font-face {
            font-family: 'Font Awesome 6 Free';
            font-display: swap;
        }
        @font-face {
            font-family: 'Font Awesome 6 Brands';
            font-display: swap;
        }
        #nprogress{pointer-events:none}#nprogress .bar{background:#29d;position:fixed;z-index:1031;top:0;left:0;width:100%;height:2px}#nprogress .peg{display:block;position:absolute;right:0;width:100px;height:100%;box-shadow:0 0 10px #29d,0 0 5px #29d;opacity:1;-webkit-transform:rotate(3deg) translate(0,-4px);-ms-transform:rotate(3deg) translate(0,-4px);transform:rotate(3deg) translate(0,-4px)}#nprogress .spinner{display:block;position:fixed;z-index:1031;top:15px;right:15px}#nprogress .spinner-icon{width:18px;height:18px;box-sizing:border-box;border:2px solid transparent;border-top-color:#29d;border-left-color:#29d;border-radius:50%;-webkit-animation:nprogress-spinner 400ms linear infinite;animation:nprogress-spinner 400ms linear infinite}.nprogress-custom-parent{overflow:hidden;position:relative}.nprogress-custom-parent #nprogress .bar,.nprogress-custom-parent #nprogress .spinner{position:absolute}@-webkit-keyframes nprogress-spinner{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}@keyframes nprogress-spinner{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
    </style>
    
    <script>
        (function () {
            var KEY_SIDEBAR = 'wms-sidebar-collapsed';
            var KEY_SUBMENU = 'wms-sidebar-submenu-wilayah';
            
            document.documentElement.classList.add('sb-preload');
            if (window.innerWidth > 768 && localStorage.getItem(KEY_SIDEBAR) === 'true') {
                document.documentElement.classList.add('sb-pre-collapsed');
            }

            window.WmsSidebarState = {
                init: function(isWilayahActive) {
                    var sidebar = document.getElementById('sidebar');
                    var body = document.body;
                    var wilTrigger = document.getElementById('wilayahTrigger');
                    var wilBody = document.getElementById('wilayahBody');

                    if (window.innerWidth > 768) {
                        if (localStorage.getItem(KEY_SIDEBAR) === 'true') {
                            sidebar.classList.add('collapsed');
                            body.classList.add('sb-collapsed');
                        }
                    }
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () {
                            document.documentElement.classList.remove('sb-pre-collapsed', 'sb-preload');
                        });
                    });
                },
                toggleSidebar: function() {
                    var sidebar = document.getElementById('sidebar');
                    var body = document.body;
                    var isCollapsed = sidebar.classList.toggle('collapsed');
                    body.classList.toggle('sb-collapsed', isCollapsed);
                    localStorage.setItem(KEY_SIDEBAR, isCollapsed);
                },
                toggleSubmenu: function() {
                    var sidebar = document.getElementById('sidebar');
                    var wilTrigger = document.getElementById('wilayahTrigger');
                    var wilBody = document.getElementById('wilayahBody');
                    
                    if (!wilTrigger || !wilBody) return;
                    
                    if (sidebar && sidebar.classList.contains('collapsed')) {
                        return false;
                    }
                    
                    var isOpen = wilTrigger.classList.toggle('open');
                    wilBody.classList.toggle('open');
                    localStorage.setItem(KEY_SUBMENU, isOpen);
                    return true;
                },
                clearOnLogout: function() {
                    localStorage.removeItem(KEY_SIDEBAR);
                    localStorage.removeItem(KEY_SUBMENU);
                    localStorage.removeItem('wms-sidebar-scroll');
                }
            };
        })();
    </script>

    <?= $this->renderSection('styles') ?>

    <style>
        :root {
            --primary: #2563EB;
            --primary-soft: #eff6ff;
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #DC2626;
            --dark: #0F172A;
            --muted: #64748B;
            --border: #E2E8F0;
            --bg: #F8FAFC;
            /* Sidebar layout variables (harus ada di head agar tidak blink) */
            --sb-w-expanded:  220px;
            --sb-w-collapsed:  62px;
        }

        body {
            background: var(--bg);
            color: var(--dark);
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            margin-left: var(--sb-w-expanded);
            transition: margin-left 200ms ease;
        }
        body.sb-collapsed { margin-left: var(--sb-w-collapsed); }

        /* ── Sidebar FOUC Prevention (head-level) ── */
        html.sb-preload body,
        html.sb-preload .sidebar { transition: none !important; }
        html.sb-pre-collapsed body { margin-left: var(--sb-w-collapsed) !important; transition: none !important; }

        @media (max-width: 768px) {
            body, body.sb-collapsed { margin-left: 0 !important; }
            html.sb-pre-collapsed body { margin-left: 0 !important; }
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 992px) {
            .app-shell { flex-direction: row; }
        }

        .main-panel {
            flex: 1;
            padding: 24px;
            min-width: 0;
        }

        .topbar {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 14px 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
            margin-bottom: 20px;
        }

        .page-title   { font-size: 1.4rem; font-weight: 700; margin: 0; }
        .subtle       { color: var(--muted); font-size: .92rem; }

        .kpi-card, .panel-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
        }

        .kpi-card     { padding: 18px; height: 100%; }

        .kpi-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }

        .kpi-value    { font-size: 1.3rem; font-weight: 700; margin-top: 12px; }
        .kpi-label    { color: var(--muted); font-size: .9rem; }
        .panel-card   { padding: 18px; }
        .panel-title  { font-size: 1rem; font-weight: 700; margin-bottom: 8px; }

        .hero-card {
            background: linear-gradient(130deg, var(--primary-soft), #ffffff 70%);
            border: 1px solid #dbeafe;
            border-radius: 22px;
            padding: 22px;
            margin-bottom: 20px;
        }

        .badge-soft {
            background: rgba(37, 99, 235, .1);
            color: var(--primary);
            border-radius: 999px;
            padding: 6px 10px;
            font-size: .8rem;
            font-weight: 600;
        }

        .table thead th {
            border-bottom: 0;
            color: var(--muted);
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .mini-list .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .mini-list .item:last-child { border-bottom: 0; }

        .timeline-item {
            position: relative;
            padding-left: 18px;
            margin-bottom: 12px;
            border-left: 2px solid #e2e8f0;
        }
        .timeline-item::before {
            content: "";
            position: absolute;
            left: -6px; top: 4px;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--primary);
        }

        /* DataTables Global Styling */
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label { font-size: 13px; color: #374151; display: flex; align-items: center; gap: 8px; margin: 0; }
        .dataTables_wrapper .dataTables_length select { height: 36px; font-size: 13px; padding: 0 28px 0 10px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #111827; outline: none; appearance: auto; }
        .dataTables_wrapper .dataTables_filter input { height: 36px; width: 220px; font-size: 13px; padding: 0 10px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #111827; outline: none; transition: border-color .15s; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.12); }
        .dataTables_wrapper .dataTables_info { font-size: 12px; color: #6b7280; padding-top: 10px; }
        .dataTables_wrapper .dataTables_paginate { margin-top: 10px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0 !important; border: none !important; background: transparent !important; margin: 0 1px !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button .page-link { height: 32px !important; min-width: 32px; padding: 0 8px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; border: 1px solid #e2e8f0 !important; font-size: 12.5px; font-weight: 500; color: #334155 !important; background: #fff !important; transition: background-color .15s ease, border-color .15s ease, color .15s ease; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link,
        .dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover .page-link { background: #eff6ff !important; border-color: #bfdbfe !important; color: #2563eb !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled .page-link { opacity: .45; cursor: default; }

        /* DataTables Sorting Icons */
        table.dataTable thead > tr > th.sorting,
        table.dataTable thead > tr > th.sorting_asc,
        table.dataTable thead > tr > th.sorting_desc {
            position: relative;
            padding-right: 26px;
            cursor: pointer;
        }
        table.dataTable thead > tr > th.sorting::before,
        table.dataTable thead > tr > th.sorting::after,
        table.dataTable thead > tr > th.sorting_asc::before,
        table.dataTable thead > tr > th.sorting_asc::after,
        table.dataTable thead > tr > th.sorting_desc::before,
        table.dataTable thead > tr > th.sorting_desc::after {
            position: absolute;
            bottom: 50%;
            display: block;
            opacity: 0.3;
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 0.8em;
            line-height: 1;
            transform: translateY(50%);
        }
        table.dataTable thead > tr > th.sorting::before,
        table.dataTable thead > tr > th.sorting_asc::before,
        table.dataTable thead > tr > th.sorting_desc::before {
            right: 1em;
            content: "\f0de"; /* fa-caret-up */
        }
        table.dataTable thead > tr > th.sorting::after,
        table.dataTable thead > tr > th.sorting_asc::after,
        table.dataTable thead > tr > th.sorting_desc::after {
            right: 0.5em;
            content: "\f0dd"; /* fa-caret-down */
        }
        table.dataTable thead > tr > th.sorting_asc::before { opacity: 1; color: var(--primary); }
        table.dataTable thead > tr > th.sorting_desc::after { opacity: 1; color: var(--primary); }


        /* Modal fix */
        .modal-backdrop {
            position: fixed !important;
            top: 0 !important; left: 0 !important;
            width: 100vw !important; height: 100vh !important;
            z-index: 1070 !important;
        }
        .modal { z-index: 1075 !important; }

        /* ── Notif Bell Button ────────────────────────────── */
        .notif-bell-btn {
            position: relative;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid #E5E7EB;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
            transition: background 0.13s, border-color 0.13s;
            flex-shrink: 0;
        }
        .notif-bell-btn:hover {
            background: #F3F4F6;
            border-color: #D1D5DB;
        }
        .notif-bell-btn i {
            font-size: 15px;
            color: #6B7280;
        }
        .notif-bell-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #F8FAFC;
            line-height: 1;
            font-family: 'Inter', -apple-system, sans-serif;
        }
        .notif-bell-badge.danger  { background: #DC2626; color: #fff; }
        .notif-bell-badge.warning { background: #D97706; color: #fff; }

        /* ── Notif Dropdown ───────────────────────────────── */
        .notif-dropdown {
            width: 320px !important;
            border: 1px solid #E5E7EB !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
            overflow: hidden;
            padding: 0 !important;
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 13px;
        }
        .notif-dd-header {
            padding: 12px 16px;
            border-bottom: 1px solid #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }
        .notif-dd-title {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
        }
        .notif-dd-title i {
            color: #6366F1;
            font-size: 15px;
        }
        .notif-dd-count {
            font-size: 11px;
            font-weight: 600;
            background: #FEE2E2;
            color: #991B1B;
            padding: 2px 8px;
            border-radius: 999px;
            display: none;
        }

        /* ── Notif List & Items ───────────────────────────── */
        .notif-list {
            max-height: 340px;
            overflow-y: auto;
            background: #fff;
        }
        .notif-list::-webkit-scrollbar { width: 4px; }
        .notif-list::-webkit-scrollbar-track { background: transparent; }
        .notif-list::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 4px; }

        .notif-item {
            padding: 11px 16px;
            border-bottom: 1px solid #F9FAFB;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            transition: background 0.12s;
            background: #fff;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #FAFAFA; }

        .notif-item-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 13px;
        }
        .notif-item-icon.critical { background: #FEE2E2; color: #DC2626; }
        .notif-item-icon.high     { background: #FEE2E2; color: #B91C1C; }
        .notif-item-icon.warning  { background: #FEF3C7; color: #D97706; }
        .notif-item-icon.info     { background: #EFF6FF; color: #2563EB; }

        .notif-item-body {
            flex: 1;
            min-width: 0;
        }
        .notif-item-name {
            font-size: 12.5px;
            font-weight: 500;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none;
            display: block;
        }
        .notif-item-name:hover { color: #111827; }
        .notif-item-meta {
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }
        .notif-status-pill {
            font-size: 10.5px;
            font-weight: 500;
            padding: 1px 7px;
            border-radius: 999px;
            white-space: nowrap;
        }
        .notif-status-pill.danger  { background: #FEE2E2; color: #991B1B; }
        .notif-status-pill.warning { background: #FEF3C7; color: #92400E; }

        .notif-dismiss-btn {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            border: 1px solid #E5E7EB;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            color: #9CA3AF;
            transition: background 0.12s, border-color 0.12s, color 0.12s;
            font-size: 12px;
            padding: 0;
        }
        .notif-dismiss-btn:hover {
            background: #F3F4F6;
            border-color: #D1D5DB;
            color: #374151;
        }

        /* ── Empty State ──────────────────────────────────── */
        .notif-empty {
            padding: 28px 16px;
            text-align: center;
            background: #fff;
        }
        .notif-empty-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #ECFDF5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 16px;
            color: #16A34A;
        }
        .notif-empty-title {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px;
        }
        .notif-empty-sub {
            font-size: 11.5px;
            color: #9CA3AF;
        }

        /* ── Loading ──────────────────────────────────────── */
        .notif-loading {
            padding: 24px 16px;
            text-align: center;
            background: #fff;
            color: #9CA3AF;
            font-size: 12.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .notif-loading .spinner-border {
            width: 16px;
            height: 16px;
            border-width: 2px;
        }

        /* ── Footer ───────────────────────────────────────── */
        .notif-dd-footer {
            padding: 10px 16px;
            border-top: 1px solid #F3F4F6;
            background: #FAFAFA;
            display: flex;
            justify-content: center;
        }
        .notif-dd-footer a {
            font-size: 12px;
            font-weight: 500;
            color: #6366F1;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .notif-dd-footer a:hover { color: #4F46E5; }
        .notif-dd-footer a i { font-size: 12px; }
    </style>
</head>

<body>
    <div class="app-shell">
        <?= $this->include('layout/sidebar') ?>

        <main class="main-panel">
            <?php if (logged_in()): ?>
            <div class="d-flex justify-content-end align-items-center mb-4 pb-2 border-bottom">

                <?php if (in_groups(['Administrator', 'Petugas Gudang'])): ?>
                <!-- Notification Bell -->
                <div class="dropdown me-2" id="notificationDropdownContainer">
                    <button class="notif-bell-btn"
                            type="button"
                            id="notificationBellBtn"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            aria-label="Notifikasi expired">
                        <i class="fa-solid fa-bell"></i>
                        <span id="notifBadge" class="notif-bell-badge d-none"></span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end notif-dropdown"
                         aria-labelledby="notificationBellBtn">

                        <div class="notif-dd-header">
                            <p class="notif-dd-title">
                                <i class="fa-solid fa-bell"></i>
                                Notifikasi expired
                            </p>
                            <span id="notifCountBadge" class="notif-dd-count"></span>
                        </div>

                        <div id="notifList" class="notif-list">
                            <div class="notif-loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Memuat...</span>
                                </div>
                                Memuat...
                            </div>
                        </div>

                        <div class="notif-dd-footer">
                            <a href="<?= site_url('transaksi/monitoring-expired') ?>">
                                Lihat monitoring expired
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle bg-white shadow-sm border"
                            type="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-circle me-2 text-primary"></i>
                        <span class="fw-bold"><?= esc(user()->username) ?></span>
                        <small class="text-muted ms-1">(<?= esc(get_user_role()) ?>)</small>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="<?= site_url('profil') ?>">
                                <i class="fa-solid fa-id-card me-2"></i> Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>" onclick="window.WmsSidebarState.clearOnLogout()">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // 1. Handlers for .form-delete-swal or forms with onsubmit containing confirm
            $(document).on('submit', '.form-delete-swal, form[onsubmit*="confirm"]', function (e) {
                e.preventDefault();
                var form = this;
                var text = $(form).attr('data-confirm-text');
                if (!text) {
                    var rawAttr = $(form).attr('onsubmit') || '';
                    var m = rawAttr.match(/confirm\((['"])(.*?)\1\)/);
                    text = m ? m[2] : 'Apakah Anda yakin ingin menghapus data ini?';
                }

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger rounded-pill px-4',
                        cancelButton: 'btn btn-secondary rounded-pill px-4 ms-2'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        if (typeof showOverlay === 'function') showOverlay();
                        form.submit();
                    }
                });
            });

            // 2. Handlers for .btn-delete-swal or elements with onclick containing confirm
            $(document).on('click', '.btn-delete-swal, [onclick*="confirm"]', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var text = $btn.attr('data-confirm-text');
                if (!text) {
                    var rawAttr = $btn.attr('onclick') || '';
                    var m = rawAttr.match(/confirm\((['"])(.*?)\1\)/);
                    text = m ? m[2] : 'Apakah Anda yakin ingin melanjutkan?';
                }

                Swal.fire({
                    title: 'Konfirmasi',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger rounded-pill px-4',
                        cancelButton: 'btn btn-secondary rounded-pill px-4 ms-2'
                    }
                }).then(function (result) {
                    if (!result.isConfirmed) return;
                    if (typeof showOverlay === 'function') showOverlay();
                    var link = $btn.attr('href');
                    if ($btn.is('button') && $btn.closest('form').length) {
                        $btn.closest('form')[0].submit();
                    } else if (link && link !== '#') {
                        window.location.href = link;
                    }
                });
            });
        });
    </script>

    <!-- Navigasi langsung, tanpa transisi, tanpa delay -->
    <script>
        (function () {
            document.addEventListener('click', function (e) {
                if (e.defaultPrevented) return;
                var a = e.target.closest('a[href]');
                if (!a) return;
                if (a.hasAttribute('onclick')) return;
                var href = a.href;
                if (!href
                    || a.target === '_blank'
                    || href.includes('#')
                    || href.startsWith('javascript')
                    || a.hasAttribute('data-bs-toggle')
                    || a.classList.contains('btn-delete-swal')
                ) return;

                window.location.href = href;
            });
        })();
    </script>

    <!-- Teleport modal ke body -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.modal').forEach(function (m) {
                if (m.parentElement !== document.body) document.body.appendChild(m);
            });
        });
    </script>

    <!-- NProgress JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <!-- Loading System JS -->
    <script src="<?= base_url('assets/js/loading.js') ?>"></script>

    <?php if (in_groups(['Administrator', 'Petugas Gudang'])): ?>
    <script>
        $(document).ready(function () {

            var $badge      = $('#notifBadge');
            var $countBadge = $('#notifCountBadge');
            var $list       = $('#notifList');

            /* ── Render helpers ─────────────────────────── */
            function renderLoading() {
                $list.html(
                    '<div class="notif-loading">' +
                    '<div class="spinner-border text-primary" role="status">' +
                    '<span class="visually-hidden">Memuat...</span></div>' +
                    'Memuat...' +
                    '</div>'
                );
            }

            function renderEmpty() {
                $list.html(
                    '<div class="notif-empty">' +
                    '<div class="notif-empty-icon"><i class="fa-solid fa-circle-check"></i></div>' +
                    '<div class="notif-empty-title">Semua stok aman</div>' +
                    '<div class="notif-empty-sub">Tidak ada item yang akan atau sudah expired.</div>' +
                    '</div>'
                );
            }

            function getIconClass(priority) {
                if (priority === 'CRITICAL') return 'critical fa-skull-crossbones';
                if (priority === 'HIGH')     return 'high fa-circle-exclamation';
                if (priority === 'WARNING')  return 'warning fa-clock';
                return 'info fa-circle-info';
            }

            function getStatusPill(item) {
                var isExpired = item.priority === 'CRITICAL' || item.priority === 'HIGH';
                var cls  = isExpired ? 'danger' : 'warning';
                var text = isExpired
                    ? 'Expired ' + Math.abs(item.sisa_hari) + ' hari lalu'
                    : 'Sisa ' + item.sisa_hari + ' hari';
                return '<span class="notif-status-pill ' + cls + '">' + text + '</span>';
            }

            function renderItems(items) {
                if (!items || items.length === 0) { renderEmpty(); return; }

                var html  = '';
                var limit = Math.min(items.length, 10);
                for (var i = 0; i < limit; i++) {
                    var b   = items[i];
                    var ico = getIconClass(b.priority);
                    var iconType  = ico.split(' ')[0];
                    var iconClass = ico.split(' ')[1];
                    html +=
                        '<div class="notif-item">' +
                            '<div class="notif-item-icon ' + iconType + '">' +
                                '<i class="fa-solid ' + iconClass + '"></i>' +
                            '</div>' +
                            '<div class="notif-item-body">' +
                                '<a href="<?= site_url('transaksi/monitoring-expired') ?>" class="notif-item-name">' +
                                    b.nama_barang +
                                '</a>' +
                                '<div class="notif-item-meta">' +
                                    '<span>Batch: ' + b.nomor_batch + '</span>' +
                                    getStatusPill(b) +
                                '</div>' +
                            '</div>' +
                            '<button class="notif-dismiss-btn btn-dismiss-notif" ' +
                                    'data-id="' + b.id + '" ' +
                                    'data-priority="' + b.priority + '" ' +
                                    'title="Tandai sudah dicek" ' +
                                    'aria-label="Tandai sudah dicek">' +
                                '<i class="fa-solid fa-check"></i>' +
                            '</button>' +
                        '</div>';
                }
                $list.html(html);
            }

            /* ── Load from API ──────────────────────────── */
            function loadNotifications() {
                renderLoading();
                $.ajax({
                    url: '<?= site_url('api/notifications/bell') ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        var count = res.count || 0;
                        var items = res.items || [];

                        if (count > 0) {
                            var hasCritical = items.some(function (i) {
                                return i.priority === 'CRITICAL' || i.priority === 'HIGH';
                            });
                            $badge
                                .text(count)
                                .removeClass('d-none danger warning')
                                .addClass(hasCritical ? 'danger' : 'warning');
                            $countBadge.text(count + ' item').show();
                        } else {
                            $badge.addClass('d-none').removeClass('danger warning');
                            $countBadge.hide();
                        }

                        renderItems(items);
                    },
                    error: function () {
                        $list.html(
                            '<div class="notif-empty">' +
                            '<div class="notif-empty-sub" style="padding:20px 0">Gagal memuat notifikasi.</div>' +
                            '</div>'
                        );
                    }
                });
            }

            /* ── Init & refresh on open ─────────────────── */
            if ('requestIdleCallback' in window) {
                requestIdleCallback(function () { loadNotifications(); });
            } else {
                setTimeout(loadNotifications, 800);
            }

            $('#notificationDropdownContainer').on('show.bs.dropdown', function () {
                loadNotifications();
            });

            /* ── Dismiss ────────────────────────────────── */
            $(document).on('click', '.btn-dismiss-notif', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var $btn     = $(this);
                var batchId  = $btn.data('id');
                var priority = $btn.data('priority');

                $btn.html('<i class="fa-solid fa-spinner fa-spin"></i>');

                $.post(
                    '<?= site_url('api/notifications/read') ?>',
                    {
                        batch_id: batchId,
                        priority: priority,
                        '<?= csrf_token() ?>': $('meta[name="csrf-token"]').attr('content')
                    },
                    function (res) {
                        if (res.status !== 'success') return;

                        $btn.closest('.notif-item').slideUp(160, function () {
                            $(this).remove();

                            var remaining = $list.find('.notif-item').length;
                            if (remaining === 0) {
                                renderEmpty();
                                $badge.addClass('d-none').removeClass('danger warning');
                                $countBadge.hide();
                                return;
                            }

                            var cur = parseInt($badge.text()) || 0;
                            if (cur > 1) {
                                $badge.text(cur - 1);
                                $countBadge.text((cur - 1) + ' item');
                            } else {
                                $badge.addClass('d-none').removeClass('danger warning');
                                $countBadge.hide();
                            }
                        });
                    }
                );
            });
        });
    </script>
    <?php endif; ?>

    <?= $this->renderSection('scripts') ?>
</body>

</html>