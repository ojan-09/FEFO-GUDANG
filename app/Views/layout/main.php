<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WMS Foodbank Indonesia' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #fff;
            padding: 24px 18px;
            position: sticky;
            top: 0;
            max-height: 100vh;
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), #38bdf8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, .25);
        }

        .sidebar-section {
            margin-top: 18px;
        }

        .sidebar-section-title {
            font-size: .74rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 8px;
            padding: 0 10px;
        }

        .nav-link {
            color: #dbeafe;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 4px;
            transition: .2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .main-panel {
            flex: 1;
            padding: 24px;
        }

        .topbar {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 14px 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
        }

        .subtle {
            color: var(--muted);
            font-size: .92rem;
        }

        .kpi-card,
        .panel-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
        }

        .kpi-card {
            padding: 18px;
            height: 100%;
        }

        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
        }

        .kpi-value {
            font-size: 1.3rem;
            font-weight: 700;
            margin-top: 12px;
        }

        .kpi-label {
            color: var(--muted);
            font-size: .9rem;
        }

        .panel-card {
            padding: 18px;
        }

        .panel-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

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

        .mini-list .item:last-child {
            border-bottom: 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 18px;
            margin-bottom: 12px;
            border-left: 2px solid #e2e8f0;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: -6px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary);
        }

        @media (max-width: 991px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
        }
    $css
    </style>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <div class="app-shell d-flex flex-column flex-lg-row">
        <!-- Memanggil layout sidebar -->
        <?= $this->include('layout/sidebar') ?>

        <main class="main-panel">
            <?php if (logged_in()): ?>
            <div class="d-flex justify-content-end align-items-center mb-4 pb-2 border-bottom">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle bg-white shadow-sm border" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-circle me-2 text-primary"></i> 
                        <span class="fw-bold"><?= esc(user()->username) ?></span> 
                        <small class="text-muted ms-1">(<?= esc(get_user_role()) ?>)</small>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?= site_url('profil') ?>"><i class="fa-solid fa-id-card me-2"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= site_url('logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Disinilah tempat konten setiap halaman akan dimasukkan -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Remove native confirm and convert to SweetAlert
            $('a[onclick^="return confirm"]').each(function() {
                var onclickStr = $(this).attr('onclick');
                var confirmText = onclickStr.match(/confirm\(['"]([^'"]+)['"]\)/)[1];
                
                // Remove the native onclick so it doesn't fire
                $(this).removeAttr('onclick');
                // Store the text in a data attribute
                $(this).attr('data-confirm-text', confirmText);
                // Add a class for our listener
                $(this).addClass('btn-delete-swal');
            });

            // Global interceptor for SweetAlert buttons
            $(document).on('click', '.btn-delete-swal', function(e) {
                e.preventDefault();
                var link = $(this).attr('href');
                var confirmText = $(this).attr('data-confirm-text');
                
                Swal.fire({
                    title: 'Konfirmasi',
                    text: confirmText,
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link;
                    }
                });
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>



