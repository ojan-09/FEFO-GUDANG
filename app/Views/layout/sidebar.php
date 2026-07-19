<script>
    (function () {
        if (window.innerWidth > 768 && localStorage.getItem('wms-sidebar-collapsed') === 'true') {
            document.documentElement.classList.add('sb-pre-collapsed');
        }
    })();
</script>

<aside class="sidebar" id="sidebar">

    <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
        <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>

    <div class="sb-logo">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo FOI">
        <div class="sb-logo-text">
            <span class="sb-logo-title">Foodbank of Indonesia</span>
            <span class="sb-logo-sub">Warehouse Management</span>
        </div>
    </div>

    <nav class="sb-nav">

        <div class="sb-group">
            <a href="<?= site_url('/') ?>"
               class="sb-link <?= (url_is('/') || url_is('dashboard*')) ? 'active' : '' ?>"
               data-tooltip="Dashboard">
                <span class="sb-icon"><i data-lucide="layout-dashboard"></i></span>
                <span class="sb-label">Dashboard</span>
            </a>
        </div>

        <?php if (in_groups('Administrator')): ?>
        <div class="sb-group sb-divider">
            <a href="<?= site_url('masterdata/donatur') ?>"
               class="sb-link <?= url_is('masterdata/donatur*') ? 'active' : '' ?>"
               data-tooltip="Donatur">
                <span class="sb-icon"><i data-lucide="users"></i></span>
                <span class="sb-label">Donatur</span>
            </a>
            <a href="<?= site_url('masterdata/kategori') ?>"
               class="sb-link <?= url_is('masterdata/kategori*') ? 'active' : '' ?>"
               data-tooltip="Kategori">
                <span class="sb-icon"><i data-lucide="tag"></i></span>
                <span class="sb-label">Kategori</span>
            </a>
            <a href="<?= site_url('masterdata/wilayah') ?>"
               class="sb-link <?= url_is('masterdata/wilayah*') ? 'active' : '' ?>"
               data-tooltip="Wilayah">
                <span class="sb-icon"><i data-lucide="map-pin"></i></span>
                <span class="sb-label">Wilayah</span>
            </a>
        </div>
        <?php endif; ?>

        <?php if (in_groups(['Administrator', 'Petugas Gudang'])): ?>
        <div class="sb-group sb-divider">
            <a href="<?= site_url('transaksi/barang-masuk') ?>"
               class="sb-link <?= url_is('transaksi/barang-masuk*') ? 'active' : '' ?>"
               data-tooltip="Donasi Masuk">
                <span class="sb-icon"><i data-lucide="arrow-down-to-line"></i></span>
                <span class="sb-label">Donasi Masuk</span>
            </a>
            <a href="<?= site_url('transaksi/barang-keluar') ?>"
               class="sb-link <?= url_is('transaksi/barang-keluar*') ? 'active' : '' ?>"
               data-tooltip="Penyaluran Barang">
                <span class="sb-icon"><i data-lucide="arrow-up-from-line"></i></span>
                <span class="sb-label">Penyaluran Barang</span>
            </a>
            <a href="<?= site_url('transaksi/penyesuaian') ?>"
               class="sb-link <?= url_is('transaksi/penyesuaian*') ? 'active' : '' ?>"
               data-tooltip="Penyesuaian Stok">
                <span class="sb-icon"><i data-lucide="scale"></i></span>
                <span class="sb-label">Penyesuaian Stok</span>
            </a>
        </div>
        <?php endif; ?>

        <div class="sb-group sb-divider">
            <a href="<?= site_url('transaksi/stok-gudang') ?>"
               class="sb-link <?= url_is('transaksi/stok-gudang*') ? 'active' : '' ?>"
               data-tooltip="Stok Gudang">
                <span class="sb-icon"><i data-lucide="warehouse"></i></span>
                <span class="sb-label">Stok Gudang</span>
            </a>
        </div>

        <div class="sb-group sb-divider">
            <a href="<?= site_url('laporan/donasi') ?>"
               class="sb-link <?= url_is('laporan/donasi*') ? 'active' : '' ?>"
               data-tooltip="Laporan Donasi Masuk">
                <span class="sb-icon"><i data-lucide="file-down"></i></span>
                <span class="sb-label">Laporan Donasi Masuk</span>
            </a>
            <a href="<?= site_url('laporan/penyaluran') ?>"
               class="sb-link <?= url_is('laporan/penyaluran*') ? 'active' : '' ?>"
               data-tooltip="Laporan Penyaluran">
                <span class="sb-icon"><i data-lucide="file-up"></i></span>
                <span class="sb-label">Laporan Penyaluran</span>
            </a>
            <a href="<?= site_url('laporan/stok') ?>"
               class="sb-link <?= url_is('laporan/stok*') ? 'active' : '' ?>"
               data-tooltip="Laporan Stok Gudang">
                <span class="sb-icon"><i data-lucide="file-bar-chart-2"></i></span>
                <span class="sb-label">Laporan Stok Gudang</span>
            </a>
            <a href="<?= site_url('laporan/expired') ?>"
               class="sb-link <?= url_is('laporan/expired*') ? 'active' : '' ?>"
               data-tooltip="Laporan Barang Expired">
                <span class="sb-icon"><i data-lucide="file-warning"></i></span>
                <span class="sb-label">Laporan Expired</span>
            </a>
            <a href="<?= site_url('laporan/penyesuaian') ?>"
               class="sb-link <?= url_is('laporan/penyesuaian*') ? 'active' : '' ?>"
               data-tooltip="Laporan Penyesuaian">
                <span class="sb-icon"><i data-lucide="scale"></i></span>
                <span class="sb-label">Laporan Penyesuaian</span>
            </a>
        </div>

        <?php if (in_groups('Administrator')): ?>
        <div class="sb-group sb-divider">
            <a href="<?= site_url('masterdata/maintenance-barang') ?>"
               class="sb-link <?= url_is('masterdata/maintenance-barang*') ? 'active' : '' ?>"
               data-tooltip="Maintenance Master">
                <span class="sb-icon"><i data-lucide="wrench"></i></span>
                <span class="sb-label">Maintenance Master</span>
            </a>
            <a href="<?= site_url('manajemen-user') ?>"
               class="sb-link <?= url_is('manajemen-user*') ? 'active' : '' ?>"
               data-tooltip="Manajemen User">
                <span class="sb-icon"><i data-lucide="user-cog"></i></span>
                <span class="sb-label">Manajemen User</span>
            </a>
            <a href="<?= site_url('log-aktivitas') ?>"
               class="sb-link <?= url_is('log-aktivitas*') ? 'active' : '' ?>"
               data-tooltip="Log Aktivitas">
                <span class="sb-icon"><i data-lucide="history"></i></span>
                <span class="sb-label">Log Aktivitas</span>
            </a>
        </div>
        <?php endif; ?>

    </nav>

    <!-- User info + logout di bawah sidebar (mobile) -->
    <div class="sb-footer">
        <?php if (logged_in()): ?>
        <div class="sb-user">
            <div class="sb-user-avatar">
                <?= strtoupper(substr(user()->username, 0, 1)) ?>
            </div>
            <div class="sb-user-info">
                <span class="sb-user-name"><?= esc(user()->username) ?></span>
                <span class="sb-user-role"><?= esc(get_user_role()) ?></span>
            </div>
            <a href="<?= site_url('logout') ?>" class="sb-logout" title="Logout">
                <i data-lucide="log-out"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>

</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Mobile bottom bar -->
<nav class="sb-bottombar" id="sbBottombar">
    <a href="<?= site_url('/') ?>" class="sb-bb-item <?= (url_is('/') || url_is('dashboard*')) ? 'active' : '' ?>">
        <i data-lucide="layout-dashboard"></i>
        <span>Dashboard</span>
    </a>
    <a href="<?= site_url('transaksi/barang-masuk') ?>" class="sb-bb-item <?= url_is('transaksi/barang-masuk*') ? 'active' : '' ?>">
        <i data-lucide="arrow-down-to-line"></i>
        <span>Masuk</span>
    </a>
    <a href="<?= site_url('transaksi/barang-keluar') ?>" class="sb-bb-item <?= url_is('transaksi/barang-keluar*') ? 'active' : '' ?>">
        <i data-lucide="arrow-up-from-line"></i>
        <span>Keluar</span>
    </a>
    <a href="<?= site_url('transaksi/stok-gudang') ?>" class="sb-bb-item <?= url_is('transaksi/stok-gudang*') ? 'active' : '' ?>">
        <i data-lucide="warehouse"></i>
        <span>Stok</span>
    </a>
    <button class="sb-bb-item" id="mobileMenuBtn" type="button" aria-label="Buka menu">
        <i data-lucide="menu"></i>
        <span>Menu</span>
    </button>
</nav>

<style>
/* ─── Variables ─────────────────────────────────────────── */
:root {
    --sb-w-expanded:  220px;
    --sb-w-collapsed:  62px;

    --sb-bg:               #0F172A;
    --sb-border:           rgba(255,255,255,.05);
    --sb-icon-color:       rgba(255,255,255,.72);
    --sb-icon-active:      #FFFFFF;
    --sb-hover-bg:         rgba(255,255,255,.07);
    --sb-active-bg:        rgba(255,255,255,.11);
    --sb-active-bar:       rgba(255,255,255,.9);
    --sb-divider:          rgba(255,255,255,.07);
    --sb-label-color:      rgba(255,255,255,.82);
    --sb-tooltip-bg:       #1E293B;
    --sb-tooltip-text:     rgba(255,255,255,.92);
    --sb-section-color:    rgba(148,163,184,.55);
}

/* ─── Flash prevention ──────────────────────────────────── */
html.sb-pre-collapsed body            { margin-left: var(--sb-w-collapsed) !important; }
html.sb-pre-collapsed .sidebar        { width: var(--sb-w-collapsed); transition: none !important; }
html.sb-pre-collapsed .sidebar .sb-label,
html.sb-pre-collapsed .sidebar .sb-section-title { opacity: 0; width: 0; overflow: hidden; }
html.sb-pre-collapsed .sidebar .sb-link  { justify-content: center; }

/* ─── Body offset ───────────────────────────────────────── */
body {
    margin-left: var(--sb-w-expanded);
    transition: margin-left 200ms ease;
}
body.sb-collapsed { margin-left: var(--sb-w-collapsed); }

/* ─── Sidebar shell ─────────────────────────────────────── */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sb-w-expanded);
    height: 100vh;
    background: var(--sb-bg);
    border-right: 1px solid var(--sb-border);
    display: flex;
    flex-direction: column;
    align-items: stretch;
    padding: 14px 8px 12px;
    z-index: 1000;
    overflow-y: auto;
    overflow-x: visible;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.12) transparent;
    transition: width 200ms ease;
    will-change: width;
    box-sizing: border-box;
}
.sidebar::-webkit-scrollbar       { width: 4px; }
.sidebar::-webkit-scrollbar-track { background: transparent; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.13); border-radius: 10px; }
.sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.28); }

/* ─── Collapsed state ───────────────────────────────────── */
.sidebar.collapsed { width: var(--sb-w-collapsed); }
.sidebar.collapsed .sb-label,
.sidebar.collapsed .sb-section-title {
    opacity: 0;
    width: 0;
    pointer-events: none;
    overflow: hidden;
    white-space: nowrap;
}
.sidebar.collapsed .sb-link {
    justify-content: center;
    padding: 0;
    box-sizing: border-box;
    width: 44px;
    height: 44px;
    margin: 0 auto;
    border-radius: 8px;
}
.sidebar.collapsed .sb-icon { width: 20px; height: 20px; flex-shrink: 0; }
.sidebar.collapsed .sb-icon svg { width: 20px; height: 20px; }
.sidebar.collapsed .sb-logo { justify-content: center; }
.sidebar.collapsed .sidebar-toggle #toggleIcon { transform: rotate(180deg); }
.sidebar.collapsed .sb-user-info,
.sidebar.collapsed .sb-logout { display: none; }
.sidebar.collapsed .sb-footer { padding: 8px 0; }
.sidebar.collapsed .sb-user { justify-content: center; }

/* ─── Toggle button (desktop) ──────────────────────────── */
.sidebar-toggle {
    position: absolute;
    top: 18px;
    right: -12px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,.15);
    background: #1E293B;
    color: rgba(255,255,255,.8);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1100;
    transition: background 150ms ease;
    flex-shrink: 0;
    padding: 0;
    outline: none;
}
.sidebar-toggle:hover { background: #2D3F55; color: #fff; }
.sidebar-toggle:focus-visible { outline: 2px solid rgba(255,255,255,.5); outline-offset: 2px; }
.sidebar-toggle #toggleIcon {
    transition: transform 200ms ease;
    display: block;
    pointer-events: none;
}

/* ─── Logo ──────────────────────────────────────────────── */
.sb-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 2px 4px 18px;
    flex-shrink: 0;
    overflow: hidden;
    transition: padding 200ms ease;
}
.sb-logo img {
    width: 34px;
    height: 34px;
    object-fit: contain;
    flex-shrink: 0;
    display: block;
}
.sb-logo-text {
    display: flex;
    flex-direction: column;
    gap: 1px;
    overflow: hidden;
    transition: opacity 150ms ease, width 150ms ease;
}
.sb-logo-title {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    color: #FFFFFF;
    white-space: nowrap;
    line-height: 1.3;
}
.sb-logo-sub {
    font-family: 'Inter', sans-serif;
    font-size: 0.65rem;
    font-weight: 400;
    color: rgba(255,255,255,.5);
    white-space: nowrap;
    line-height: 1.3;
}
.sidebar.collapsed .sb-logo-text {
    opacity: 0;
    width: 0;
    pointer-events: none;
}

/* ─── Nav wrapper ───────────────────────────────────────── */
.sb-nav {
    display: flex;
    flex-direction: column;
    gap: 2px;
    flex: 1;
    min-width: 0;
}

/* ─── Group ─────────────────────────────────────────────── */
.sb-group {
    display: flex;
    flex-direction: column;
    gap: 1px;
}
.sb-group.sb-divider {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid var(--sb-divider);
}

/* ─── Nav link ──────────────────────────────────────────── */
.sb-link {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 8px;
    height: 44px;
    border-radius: 8px;
    color: var(--sb-label-color);
    text-decoration: none;
    transition: background 150ms ease, color 150ms ease, transform 150ms ease;
    overflow: hidden;
    white-space: nowrap;
    cursor: pointer;
    box-sizing: border-box;
}
.sb-link:hover {
    background: var(--sb-hover-bg);
    color: #fff;
    transform: translateX(2px);
}
.sb-link:focus-visible {
    outline: 2px solid rgba(255,255,255,.45);
    outline-offset: 2px;
}
.sb-link.active {
    background: var(--sb-active-bg);
    color: var(--sb-icon-active);
}
.sb-link.active::after {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 18px;
    border-radius: 0 3px 3px 0;
    background: var(--sb-active-bar);
}

/* ─── Icon ──────────────────────────────────────────────── */
.sb-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    color: var(--sb-icon-color);
}
.sb-link.active .sb-icon { color: var(--sb-icon-active); }
.sb-link:hover .sb-icon  { color: #fff; }
.sb-icon svg { width: 18px; height: 18px; stroke-width: 1.75; }

/* ─── Label ─────────────────────────────────────────────── */
.sb-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.82rem;
    font-weight: 500;
    color: inherit;
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    transition: opacity 150ms ease, width 150ms ease;
}

/* ─── Tooltip (hanya saat collapsed) ───────────────────── */
.sidebar.collapsed .sb-link[data-tooltip] { overflow: visible; }
.sidebar.collapsed .sb-link[data-tooltip]:hover::before {
    content: attr(data-tooltip);
    position: absolute;
    left: calc(var(--sb-w-collapsed) - 8px);
    top: 50%;
    transform: translateY(-50%);
    background: var(--sb-tooltip-bg);
    color: var(--sb-tooltip-text);
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    font-weight: 500;
    padding: 5px 11px;
    border-radius: 7px;
    white-space: nowrap;
    z-index: 9999;
    pointer-events: none;
    box-shadow: 0 4px 14px rgba(0,0,0,.4);
    border: 1px solid rgba(255,255,255,.08);
}

/* ─── Footer / user info ────────────────────────────────── */
.sb-footer {
    flex-shrink: 0;
    padding: 10px 4px 4px;
    border-top: 1px solid var(--sb-divider);
    margin-top: 8px;
}
.sb-user {
    display: flex;
    align-items: center;
    gap: 9px;
}
.sb-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(37,99,235,.35);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-family: 'Inter', sans-serif;
}
.sb-user-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
    flex: 1;
    min-width: 0;
    overflow: hidden;
}
.sb-user-name {
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sb-user-role {
    font-family: 'Inter', sans-serif;
    font-size: 0.68rem;
    color: rgba(255,255,255,.45);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sb-logout {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    color: rgba(255,255,255,.5);
    transition: background 150ms ease, color 150ms ease;
    flex-shrink: 0;
    text-decoration: none;
}
.sb-logout:hover { background: rgba(220,38,38,.2); color: #f87171; }
.sb-logout svg { width: 15px; height: 15px; stroke-width: 1.75; }

/* ─── Overlay ───────────────────────────────────────────── */
.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 1049;
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
}

/* ─── Bottom navigation bar (mobile only) ──────────────── */
.sb-bottombar {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: var(--sb-bg);
    border-top: 1px solid var(--sb-border);
    z-index: 1050;
    align-items: stretch;
    padding: 0 4px;
    padding-bottom: env(safe-area-inset-bottom);
}
.sb-bb-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    color: rgba(255,255,255,.5);
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    font-size: 0.62rem;
    font-weight: 500;
    border: none;
    background: transparent;
    cursor: pointer;
    border-radius: 10px;
    transition: color 150ms ease, background 150ms ease;
    padding: 6px 4px;
    -webkit-tap-highlight-color: transparent;
}
.sb-bb-item svg { width: 20px; height: 20px; stroke-width: 1.75; }
.sb-bb-item:hover,
.sb-bb-item.active { color: #fff; }
.sb-bb-item.active { color: #60a5fa; }

/* ─── Drawer slide-in (mobile) ──────────────────────────── */
@media (max-width: 768px) {
    .sb-bottombar { display: flex; }

    body,
    body.sb-collapsed { margin-left: 0 !important; padding-bottom: 60px; }
    html.sb-pre-collapsed body { margin-left: 0 !important; }

    .sidebar {
        width: 280px !important;
        transform: translateX(-100%);
        transition: transform 220ms cubic-bezier(.4,0,.2,1) !important;
        will-change: transform;
        overflow-x: hidden;
        box-shadow: none;
    }
    .sidebar.mobile-open {
        transform: translateX(0);
        box-shadow: 4px 0 24px rgba(0,0,0,.4);
    }
    .sidebar.mobile-open ~ .sidebar-overlay { display: block; }

    .sidebar-toggle { display: none; }

    .sidebar .sb-label {
        opacity: 1 !important;
        width: auto !important;
        pointer-events: auto !important;
    }
    .sidebar .sb-link {
        justify-content: flex-start !important;
        width: auto !important;
        margin: 0 !important;
        padding: 0 8px !important;
        height: 48px !important;
    }
    .sidebar .sb-link[data-tooltip]:hover::before { display: none; }

    /* Link item tap highlight */
    .sidebar .sb-link { -webkit-tap-highlight-color: transparent; }
    .sidebar .sb-link:active { background: rgba(255,255,255,.14); transform: none; }
}
</style>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
(function () {
    var STORAGE_KEY = 'wms-sidebar-collapsed';

    var sidebar    = document.getElementById('sidebar');
    var toggleBtn  = document.getElementById('sidebarToggle');
    var mobileBtn  = document.getElementById('mobileMenuBtn');
    var overlay    = document.getElementById('sidebarOverlay');

    function renderIcons() {
        if (window.lucide) lucide.createIcons();
    }
    renderIcons();
    document.addEventListener('DOMContentLoaded', renderIcons);

    /* ── Apply saved state (desktop only) ───────────────── */
    if (window.innerWidth > 768) {
        if (localStorage.getItem(STORAGE_KEY) === 'true') {
            sidebar.classList.add('collapsed');
            document.body.classList.add('sb-collapsed');
        }
    }
    requestAnimationFrame(function () {
        document.documentElement.classList.remove('sb-pre-collapsed');
    });

    /* ── Desktop toggle ─────────────────────────────────── */
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isCollapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sb-collapsed', isCollapsed);
            localStorage.setItem(STORAGE_KEY, isCollapsed);
        });
    }

    /* ── Mobile drawer open / close ─────────────────────── */
    function openMobile() {
        sidebar.classList.add('mobile-open');
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    if (mobileBtn) {
        mobileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.contains('mobile-open') ? closeMobile() : openMobile();
        });
    }

    overlay.addEventListener('click', closeMobile);

    /* Tutup drawer saat link diklik (mobile) */
    sidebar.querySelectorAll('.sb-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) closeMobile();
        });
    });

    /* Swipe kiri untuk tutup drawer */
    var touchStartX = 0;
    sidebar.addEventListener('touchstart', function (e) {
        touchStartX = e.touches[0].clientX;
    }, { passive: true });
    sidebar.addEventListener('touchend', function (e) {
        var dx = touchStartX - e.changedTouches[0].clientX;
        if (dx > 60) closeMobile();
    }, { passive: true });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            closeMobile();
            document.body.style.overflow = '';
        }
    });
})();
</script>