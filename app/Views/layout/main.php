<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WMS Foodbank Indonesia' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    </noscript>

    <!-- NProgress CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">

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
        }

        body {
            background: var(--bg);
            color: var(--dark);
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
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

        /* DataTables pagination fix */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button .page-link {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            border: none !important;
            background: transparent !important;
            color: inherit !important;
            padding: 0 !important;
            box-shadow: none !important;
            border-radius: inherit !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background: transparent !important;
            color: inherit !important;
            border-color: transparent !important;
        }

        /* Modal fix */
        .modal-backdrop {
            position: fixed !important;
            top: 0 !important; left: 0 !important;
            width: 100vw !important; height: 100vh !important;
            z-index: 1070 !important;
        }
        .modal { z-index: 1075 !important; }
    </style>
</head>

<body>
    <div class="app-shell">
        <?= $this->include('layout/sidebar') ?>

        <main class="main-panel">
            <?php if (logged_in()): ?>
            <div class="d-flex justify-content-end align-items-center mb-4 pb-2 border-bottom">
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
                            <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
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

    <script>
        $(document).ready(function () {

            /* SweetAlert2: lazy load hanya saat tombol delete diklik */
            function loadSwal(callback) {
                if (window.Swal) { callback(); return; }
                var s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                s.onload = callback;
                document.head.appendChild(s);
            }

            /* Konversi confirm() inline ke data-attr */
            $('[onclick^="return confirm"], button[onclick^="return confirm"]').each(function () {
                var m = $(this).attr('onclick').match(/confirm\(['"]([^'"]+)['"]\)/);
                if (!m) return;
                $(this).removeAttr('onclick')
                       .attr('data-confirm-text', m[1])
                       .addClass('btn-delete-swal');
            });

            $(document).on('click', '.btn-delete-swal', function (e) {
                e.preventDefault();
                var $btn = $(this);
                loadSwal(function () {
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: $btn.attr('data-confirm-text'),
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Lanjutkan!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            confirmButton: 'rounded-pill px-4',
                            cancelButton: 'rounded-pill px-4 ms-2'
                        }
                    }).then(function (result) {
                        if (!result.isConfirmed) return;
                        if (typeof showOverlay === 'function') showOverlay();
                        var link = $btn.attr('href');
                        if ($btn.is('button') && $btn.closest('form').length) {
                            $btn.closest('form').submit();
                        } else if (link) {
                            window.location.href = link;
                        }
                    });
                });
            });
        });
    </script>

    <!-- Navigasi langsung, tanpa transisi, tanpa delay -->
    <script>
        (function () {
            document.addEventListener('click', function (e) {
                var a = e.target.closest('a[href]');
                if (!a) return;
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

    <?= $this->renderSection('scripts') ?>
</body>

</html>