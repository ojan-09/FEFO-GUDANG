<script>
    // Terapkan status collapsed SEBELUM sidebar dirender agar tidak ada flash ukuran
    (function () {
        if (window.innerWidth > 768 && localStorage.getItem('wms-sidebar-collapsed') === 'true') {
            document.documentElement.classList.add('sb-pre-collapsed');
        }
    })();
</script>

<aside class="sidebar" id="sidebar">
    <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Ciutkan sidebar">
        <i class="fa-solid fa-angles-left"></i>
    </button>

    <div class="brand">
        <div class="brand-icon" style="background: transparent;">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo FOI" style="max-width: 100%; height: auto; object-fit: contain;">
        </div>
        <div class="brand-text">
            <h5 class="mb-0">FEFO Gudang</h5>
            <small class="text-white-50">Foodbank Of Indonesia</small>
        </div>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Utama</div>
        <a href="<?= site_url('/') ?>" data-title="Dashboard" title="Dashboard" class="nav-link <?= (url_is('/') || url_is('dashboard*')) ? 'active' : '' ?>"><i class="fa-solid fa-gauge-high"></i> <span class="nav-label">Dashboard</span></a>
    </div>

    <?php if (in_groups('Administrator')): ?>
    <div class="sidebar-section">
        <div class="sidebar-section-title">Master Data</div>
        <a href="<?= site_url('masterdata/donatur') ?>" data-title="Donatur" title="Donatur" class="nav-link <?= url_is('masterdata/donatur*') ? 'active' : '' ?>"><i class="fa-solid fa-people-group"></i> <span class="nav-label">Donatur</span></a>
        <a href="<?= site_url('masterdata/kategori') ?>" data-title="Kategori" title="Kategori" class="nav-link <?= url_is('masterdata/kategori*') ? 'active' : '' ?>"><i class="fa-solid fa-tags"></i> <span class="nav-label">Kategori</span></a>
        <a href="<?= site_url('masterdata/wilayah') ?>" data-title="Wilayah" title="Wilayah" class="nav-link <?= url_is('masterdata/wilayah*') ? 'active' : '' ?>"><i class="fa-solid fa-map-location-dot"></i> <span class="nav-label">Wilayah</span></a>
    </div>
    <?php endif; ?>

    <?php if (in_groups(['Administrator', 'Petugas Gudang'])): ?>
    <div class="sidebar-section">
        <div class="sidebar-section-title">Transaksi</div>
        <a href="<?= site_url('transaksi/barang-masuk') ?>" data-title="Donasi Masuk" title="Donasi Masuk" class="nav-link <?= url_is('transaksi/barang-masuk*') ? 'active' : '' ?>"><i class="fa-solid fa-hand-holding-heart"></i> <span class="nav-label">Donasi Masuk</span></a>
        <a href="<?= site_url('transaksi/barang-keluar') ?>" data-title="Penyaluran Barang" title="Penyaluran Barang" class="nav-link <?= url_is('transaksi/barang-keluar*') ? 'active' : '' ?>"><i class="fa-solid fa-arrow-up"></i> <span class="nav-label">Penyaluran Barang</span></a>
    </div>
    <?php endif; ?>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Gudang</div>
        <a href="<?= site_url('transaksi/stok-gudang') ?>" data-title="Stok Gudang" title="Stok Gudang" class="nav-link <?= url_is('transaksi/stok-gudang*') ? 'active' : '' ?>"><i class="fa-solid fa-warehouse"></i> <span class="nav-label">Stok Gudang</span></a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Laporan</div>
        <a href="<?= site_url('laporan/donasi') ?>" data-title="Laporan Donasi" title="Laporan Donasi" class="nav-link <?= url_is('laporan/donasi*') ? 'active' : '' ?>"><i class="fa-solid fa-file-import"></i> <span class="nav-label">Laporan Donasi Masuk</span></a>
        <a href="<?= site_url('laporan/penyaluran') ?>" data-title="Laporan Penyaluran" title="Laporan Penyaluran" class="nav-link <?= url_is('laporan/penyaluran*') ? 'active' : '' ?>"><i class="fa-solid fa-file-export"></i> <span class="nav-label">Laporan Penyaluran Barang</span></a>
        <a href="<?= site_url('laporan/stok') ?>" data-title="Laporan Stok Gudang" title="Laporan Stok Gudang" class="nav-link <?= url_is('laporan/stok*') ? 'active' : '' ?>"><i class="fa-solid fa-file-lines"></i> <span class="nav-label">Laporan Stok Gudang</span></a>
        <a href="<?= site_url('laporan/expired') ?>" data-title="Laporan Barang Expired" title="Laporan Barang Expired" class="nav-link <?= url_is('laporan/expired*') ? 'active' : '' ?>"><i class="fa-solid fa-file-circle-exclamation"></i> <span class="nav-label">Laporan Barang Expired</span></a>
    </div>

    <?php if (in_groups('Administrator')): ?>
    <div class="sidebar-section">
        <div class="sidebar-section-title">Pengaturan</div>
        <a href="<?= site_url('masterdata/maintenance-barang') ?>" data-title="Maintenance Master" title="Maintenance Master" class="nav-link <?= url_is('masterdata/maintenance-barang*') ? 'active' : '' ?>"><i class="fa-solid fa-broom"></i> <span class="nav-label">Maintenance Master</span></a>
        <a href="<?= site_url('manajemen-user') ?>" data-title="Manajemen User" title="Manajemen User" class="nav-link <?= url_is('manajemen-user*') ? 'active' : '' ?>"><i class="fa-solid fa-user"></i> <span class="nav-label">Manajemen User</span></a>
        <a href="<?= site_url('log-aktivitas') ?>" data-title="Log Aktivitas" title="Log Aktivitas" class="nav-link <?= url_is('log-aktivitas*') ? 'active' : '' ?>"><i class="fa-solid fa-clock-rotate-left"></i> <span class="nav-label">Log Aktivitas</span></a>
    </div>
    <?php endif; ?>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-label="Buka menu">
    <i class="fa-solid fa-bars"></i>
</button>

<style>
    :root {
        --sb-w-expanded: 250px;
        --sb-w-collapsed: 64px;

        --fs-brand-title: 0.95rem;
        --fs-brand-sub: 0.72rem;
        --fs-section-title: 0.7rem;
        --fs-nav: 0.85rem;
        --fs-icon: 0.85rem;
        --fs-tooltip: 0.75rem;

        /* --- warna, disamakan dengan screenshot --- */
        --sb-bg: #0d1420;
        --sb-active-bg: #232f42;
        --sb-hover-bg: rgba(255,255,255,0.05);
        --sb-border: rgba(255,255,255,0.06);
        --sb-text: rgba(255,255,255,0.9);
        --sb-text-muted: rgba(255,255,255,0.85);
        --sb-section-title: rgba(148,163,184,0.65);
        --sb-icon: #ffffff;
    }

    /* --- shape + warna --- */
    .sidebar {
        width: var(--sb-w-expanded);
        transition: width 0.22s ease;
        overflow-x: hidden;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 1000;
        padding: 0.6rem 0.6rem 1rem;
        font-family: 'Inter', sans-serif;
        scrollbar-width: thin;
        background: var(--sb-bg);
        color: var(--sb-text);
        border-right: 1px solid var(--sb-border);
    }
    .sidebar::-webkit-scrollbar { width: 5px; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }

    body {
        margin-left: var(--sb-w-expanded);
        transition: margin-left 0.22s ease;
    }
    body:has(.sidebar.collapsed) {
        margin-left: var(--sb-w-collapsed);
    }
    /* Terapkan lebar collapsed SEBELUM JS jalan, berdasarkan class di <html> */
    html.sb-pre-collapsed body {
        margin-left: var(--sb-w-collapsed);
    }
    html.sb-pre-collapsed .sidebar {
        width: var(--sb-w-collapsed);
        transition: none; /* hindari animasi saat load pertama */
    }
    html.sb-pre-collapsed .sidebar .nav-label,
    html.sb-pre-collapsed .sidebar .brand-text,
    html.sb-pre-collapsed .sidebar .sidebar-section-title {
        opacity: 0;
        position: absolute;
        pointer-events: none;
    }
    html.sb-pre-collapsed .sidebar .nav-link { justify-content: center; padding: 0.55rem; }
    html.sb-pre-collapsed .sidebar .brand { justify-content: center; padding-bottom: 0.7rem; }

    .main-content {
        transition: margin-left 0.22s ease;
    }

    /* --- brand (sizes only) --- */
    .sidebar .brand {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 0.5rem 0.9rem;
        margin-bottom: 0.4rem;
    }
    .sidebar .brand-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--fs-icon);
        background: rgba(255,255,255,0.08);
    }
    .sidebar .brand-text h5 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: var(--fs-brand-title);
        font-weight: 700;
        line-height: 1.2;
        color: var(--sb-text);
    }
    .sidebar .brand-text small {
        font-size: var(--fs-brand-sub);
        color: var(--sb-text-muted) !important;
    }

    /* --- section --- */
    .sidebar-section { margin-bottom: 0.5rem; }
    .sidebar-section-title {
        font-size: var(--fs-section-title);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.6rem 0.65rem 0.3rem;
        color: var(--sb-section-title);
    }

    /* --- nav link (warna biru) --- */
    .sidebar .nav-link {
        white-space: nowrap;
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.5rem 0.65rem;
        margin: 0.05rem 0;
        border-radius: 8px;
        font-size: var(--fs-nav);
        font-weight: 500;
        line-height: 1.3;
        min-width: 0;
        color: var(--sb-text-muted);
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
    }
    .sidebar .nav-link:hover {
        background: var(--sb-hover-bg);
        color: #ffffff;
    }
    .sidebar .nav-link.active {
        background: var(--sb-active-bg);
        color: #ffffff;
        font-weight: 500;
    }
    .sidebar .nav-link.active i {
        color: var(--sb-icon);
    }
    .sidebar .nav-link i {
        width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-size: var(--fs-icon);
        line-height: 1;
        flex-shrink: 0;
        color: var(--sb-icon);
    }
    .sidebar .nav-label {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sidebar .nav-label,
    .sidebar .brand-text,
    .sidebar .sidebar-section-title {
        transition: opacity 0.15s ease;
        opacity: 1;
    }

    /* --- toggle button (desktop) --- */
    .sidebar-toggle {
        position: absolute;
        top: 16px;
        right: -12px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: var(--sb-active-bg);
        color: #fff;
        font-size: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: transform 0.22s ease, background 0.15s ease;
    }
    .sidebar-toggle:hover { background: var(--sb-active-bg); }

    /* --- collapsed state (desktop icon-only) --- */
    .sidebar.collapsed {
        width: var(--sb-w-collapsed);
    }
    .sidebar.collapsed .nav-label,
    .sidebar.collapsed .brand-text,
    .sidebar.collapsed .sidebar-section-title {
        opacity: 0;
        position: absolute;
        pointer-events: none;
    }
    .sidebar.collapsed .nav-link { justify-content: center; padding: 0.55rem; }
    .sidebar.collapsed .brand { justify-content: center; padding-bottom: 0.7rem; }
    .sidebar.collapsed .sidebar-toggle i { transform: rotate(180deg); }

    /* tooltip on hover when collapsed */
    .sidebar.collapsed .nav-link[data-title]:hover::after {
        content: attr(data-title);
        position: absolute;
        left: calc(100% + 10px);
        top: 50%;
        transform: translateY(-50%);
        background: var(--sb-active-bg);
        color: #fff;
        font-size: var(--fs-tooltip);
        padding: 5px 10px;
        border-radius: 6px;
        white-space: nowrap;
        z-index: 20;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }

    /* --- mobile menu button (hidden on desktop) --- */
    .mobile-menu-btn {
        display: none;
        position: fixed;
        top: 14px;
        left: 14px;
        z-index: 1051;
        width: 38px;
        height: 38px;
        border-radius: 8px;
        border: none;
        background: var(--sb-bg);
        color: #fff;
        font-size: 0.95rem;
        align-items: center;
        justify-content: center;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 1049;
    }

    /* --- mobile behavior --- */
    @media (max-width: 768px) {
        .mobile-menu-btn { display: flex; }

        body, body:has(.sidebar.collapsed) { margin-left: 0 !important; }
        html.sb-pre-collapsed body { margin-left: 0 !important; }
        html.sb-pre-collapsed .sidebar { width: var(--sb-w-expanded) !important; }
        .main-content { margin-left: 0 !important; }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sb-w-expanded) !important;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            z-index: 1050;
        }
        .sidebar.mobile-open { transform: translateX(0); }
        .sidebar.mobile-open ~ .sidebar-overlay { display: block; }

        .sidebar-toggle { display: none; }
        .sidebar .nav-label,
        .sidebar .brand-text,
        .sidebar .sidebar-section-title { opacity: 1; position: static; }
    }
</style>

<script>
(function () {
    var sidebar = document.getElementById('sidebar');
    var toggleBtn = document.getElementById('sidebarToggle');
    var mobileBtn = document.getElementById('mobileMenuBtn');
    var overlay = document.getElementById('sidebarOverlay');

    var STORAGE_KEY = 'wms-sidebar-collapsed';

    // Sinkronkan class collapsed sekarang bahwa DOM sudah siap, lepas class sementara di <html>
    if (window.innerWidth > 768 && localStorage.getItem(STORAGE_KEY) === 'true') {
        sidebar.classList.add('collapsed');
    }
    // Aktifkan kembali transisi setelah state awal diterapkan (1 frame berikutnya)
    requestAnimationFrame(function () {
        document.documentElement.classList.remove('sb-pre-collapsed');
    });

    toggleBtn.addEventListener('click', function () {
        var collapsed = sidebar.classList.toggle('collapsed');
        localStorage.setItem(STORAGE_KEY, collapsed);
    });

    function openMobile() {
        sidebar.classList.add('mobile-open');
        overlay.style.display = 'block';
    }
    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.style.display = 'none';
    }

    mobileBtn.addEventListener('click', function () {
        sidebar.classList.contains('mobile-open') ? closeMobile() : openMobile();
    });
    overlay.addEventListener('click', closeMobile);

    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) closeMobile();
    });
})();
</script>