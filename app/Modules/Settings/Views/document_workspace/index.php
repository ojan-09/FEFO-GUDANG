<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* ── Tokens ──────────────────────────────────────────────── */
:root {
    --bg:          #F8FAFC;
    --surface:     #FFFFFF;
    --border:      #E5E7EB;
    --text:        #111827;
    --text-soft:   #6B7280;
    --text-subtle: #9CA3AF;
    --primary:     #2563EB;
    --primary-bg:  #EFF6FF;
    --success:     #16A34A;
    --success-bg:  #ECFDF5;
    --indigo:      #6366F1;
    --indigo-bg:   #EEF2FF;
    --teal-bg:     #F0FDFA;
    --teal:        #0D9488;
    --muted-bg:    #F3F4F6;
    --shadow:      0 1px 3px rgba(0,0,0,.05), 0 4px 12px rgba(0,0,0,.04);
    --radius:      16px;
    --radius-lg:   20px;
    --radius-sm:   10px;
}

/* ── Shell ───────────────────────────────────────────────── */
.dw {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--bg);
    margin: -1.5rem -1.5rem 0;
    padding: 20px 24px 48px;
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
}

/* ── Page Header ─────────────────────────────────────────── */
.dw-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}
.dw-header-left h1 {
    font-size: 22px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 4px;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.dw-header-left h1 i { color: var(--indigo); font-size: 19px; }
.dw-header-left p    { font-size: 13px; color: var(--text-soft); margin: 0; }
.dw-header-actions   { display: flex; gap: 8px; flex-wrap: wrap; }

/* ── Buttons ─────────────────────────────────────────────── */
.dw-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: transform .12s, box-shadow .12s, background .12s;
    white-space: nowrap;
}
.dw-btn:hover  { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,.1); }
.dw-btn:active { transform: scale(.98); }
.dw-btn-outline {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-soft);
}
.dw-btn-outline:hover { background: var(--muted-bg); color: var(--text); }
.dw-btn-primary { background: var(--primary); color: #fff; }
.dw-btn-primary:hover { background: #1D4ED8; color: #fff; }
.dw-btn-ghost {
    background: var(--muted-bg);
    border: 1px solid var(--border);
    color: var(--text-soft);
}
.dw-btn-ghost:hover { background: var(--border); color: var(--text); }

/* ── Layout ──────────────────────────────────────────────── */
.dw-layout {
    display: grid;
    grid-template-columns: 228px 1fr;
    gap: 16px;
    align-items: start;
}
@media (max-width: 992px) { .dw-layout { grid-template-columns: 1fr; } }

/* ── Sidebar ─────────────────────────────────────────────── */
.dw-sidebar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 12px;
    box-shadow: var(--shadow);
    position: sticky;
    top: 20px;
}
.dw-nav-section {
    font-size: 10.5px;
    font-weight: 600;
    color: var(--text-subtle);
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 10px 10px 5px;
    margin-top: 4px;
}
.dw-nav-section:first-child { margin-top: 0; }
.dw-nav-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 12px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-soft);
    text-decoration: none;
    transition: background .13s, color .13s;
    margin-bottom: 2px;
    gap: 8px;
}
.dw-nav-item-left { display: flex; align-items: center; gap: 9px; }
.dw-nav-item-left i { font-size: 13.5px; width: 16px; text-align: center; color: var(--text-subtle); }
.dw-nav-item:hover { background: var(--muted-bg); color: var(--text); }
.dw-nav-item:hover i { color: var(--text-soft); }
.dw-nav-item.active { background: var(--primary-bg); color: var(--primary); font-weight: 600; }
.dw-nav-item.active i { color: var(--primary); }
.dw-nav-count {
    background: var(--muted-bg);
    color: var(--text-subtle);
    font-size: 10.5px;
    font-weight: 600;
    padding: 1px 7px;
    border-radius: 999px;
}
.dw-nav-item.active .dw-nav-count {
    background: #DBEAFE;
    color: var(--primary);
}

/* ── Content area ────────────────────────────────────────── */
.dw-content { display: flex; flex-direction: column; gap: 16px; }

/* ── Global Counter Banner ───────────────────────────────── */
.dw-counter-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 22px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.dw-counter-left {}
.dw-counter-eyebrow {
    font-size: 11px;
    font-weight: 600;
    color: var(--indigo);
    text-transform: uppercase;
    letter-spacing: .5px;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 5px;
}
.dw-counter-eyebrow i { font-size: 12px; }
.dw-counter-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 3px;
    letter-spacing: -.2px;
}
.dw-counter-sub { font-size: 12.5px; color: var(--text-subtle); margin: 0; }
.dw-counter-right {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.dw-counter-stat { text-align: right; }
.dw-counter-stat-lbl {
    font-size: 10.5px;
    font-weight: 500;
    color: var(--text-subtle);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 3px;
}
.dw-counter-stat-val {
    font-size: 17px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -.3px;
    font-variant-numeric: tabular-nums;
}
.dw-counter-stat-val.next { color: var(--indigo); }
.dw-counter-divider {
    width: 1px;
    height: 40px;
    background: var(--border);
}
.dw-global-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--indigo-bg);
    color: var(--indigo);
    border: 1px solid #C7D2FE;
    font-size: 11.5px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 999px;
}

/* ── Doc Cards Grid ──────────────────────────────────────── */
.dw-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 14px;
}

.dw-doc-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: var(--shadow);
    display: flex;
    flex-direction: column;
    gap: 16px;
    transition: box-shadow .18s, transform .18s, border-color .18s;
}
.dw-doc-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    transform: translateY(-2px);
    border-color: #D1D5DB;
}

.dw-doc-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}
.dw-doc-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}
.dw-doc-icon.indigo { background: var(--indigo-bg); color: var(--indigo); }
.dw-doc-icon.teal   { background: var(--teal-bg);   color: var(--teal); }

.dw-doc-title { font-size: 15px; font-weight: 700; color: var(--text); margin: 0 0 3px; letter-spacing: -.2px; }
.dw-doc-desc  { font-size: 12.5px; color: var(--text-soft); margin: 0; line-height: 1.45; }

/* ── Info table inside card ──────────────────────────────── */
.dw-info-block {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}
.dw-info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 9px 14px;
    border-bottom: 1px solid var(--border);
    font-size: 12.5px;
    gap: 8px;
}
.dw-info-row:last-child { border-bottom: none; }
.dw-info-lbl { color: var(--text-soft); font-weight: 500; }
.dw-info-val { font-weight: 600; color: var(--text); font-variant-numeric: tabular-nums; }
.dw-info-val.blue   { color: var(--primary); }
.dw-info-val.green  { color: var(--success); }
.dw-info-val.indigo { color: var(--indigo); }

/* ── Card actions ────────────────────────────────────────── */
.dw-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 4px;
    border-top: 1px solid var(--border);
}
.dw-card-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 36px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background .13s, transform .12s;
}
.dw-card-btn:hover { transform: translateY(-1px); }
.dw-card-btn.manage {
    background: var(--primary);
    color: #fff;
}
.dw-card-btn.manage:hover { background: #1D4ED8; color: #fff; }
.dw-card-btn.preview {
    background: var(--muted-bg);
    border: 1px solid var(--border);
    color: var(--text-soft);
}
.dw-card-btn.preview:hover { background: var(--border); color: var(--text); }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .dw { padding: 12px 14px 40px; }
    .dw-counter-card { flex-direction: column; align-items: flex-start; }
    .dw-counter-right { width: 100%; justify-content: flex-start; }
    .dw-counter-stat { text-align: left; }
    .dw-cards { grid-template-columns: 1fr; }
    .dw-header-actions .dw-btn span { display: none; }
}
</style>

<div class="dw">

    <!-- ── Page Header ─────────────────────────────────────── -->
    <div class="dw-header">
        <div class="dw-header-left">
            <h1><i class="fa-solid fa-folder-tree"></i> Workspace Dokumen &amp; PDF</h1>
            <p>Kelola penomoran dokumen, ketentuan, dan konfigurasi Berita Acara &amp; Barang Keluar</p>
        </div>
        <div class="dw-header-actions">
            <a href="<?= site_url('pengaturan/dokumen/riwayat') ?>" class="dw-btn dw-btn-outline">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Riwayat</span>
                <span class="dw-nav-count" style="margin-left:2px"><?= $totalHistory ?></span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/activity-log') ?>" class="dw-btn dw-btn-outline">
                <i class="fa-solid fa-user-clock"></i>
                <span>Activity Log</span>
                <span class="dw-nav-count" style="margin-left:2px"><?= $totalLogs ?></span>
            </a>
        </div>
    </div>

    <!-- ── Main Layout ─────────────────────────────────────── -->
    <div class="dw-layout">

        <!-- Sidebar -->
        <div class="dw-sidebar">
            <div class="dw-nav-section">Menu Workspace</div>
            <a href="<?= site_url('pengaturan/dokumen') ?>"
               class="dw-nav-item <?= $activeDocKey === 'dashboard' ? 'active' : '' ?>">
                <div class="dw-nav-item-left"><i class="fa-solid fa-table-columns"></i> Dashboard</div>
            </a>

            <div class="dw-nav-section">Dokumen</div>
            <a href="<?= site_url('pengaturan/dokumen/berita_acara') ?>"
               class="dw-nav-item <?= $activeDocKey === 'berita_acara' ? 'active' : '' ?>">
                <div class="dw-nav-item-left"><i class="fa-solid fa-file-signature"></i> Berita Acara</div>
                <span class="dw-nav-count"><?= $baProvisionCount ?> Aktif</span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/barang_keluar') ?>"
               class="dw-nav-item <?= $activeDocKey === 'barang_keluar' ? 'active' : '' ?>">
                <div class="dw-nav-item-left"><i class="fa-solid fa-truck-ramp-box"></i> Barang Keluar</div>
            </a>

            <div class="dw-nav-section">Audit</div>
            <a href="<?= site_url('pengaturan/dokumen/riwayat') ?>"
               class="dw-nav-item <?= $activeDocKey === 'riwayat' ? 'active' : '' ?>">
                <div class="dw-nav-item-left"><i class="fa-solid fa-list-check"></i> Riwayat Terbit</div>
                <span class="dw-nav-count"><?= $totalHistory ?></span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/activity-log') ?>"
               class="dw-nav-item <?= $activeDocKey === 'activity_log' ? 'active' : '' ?>">
                <div class="dw-nav-item-left"><i class="fa-solid fa-user-clock"></i> Activity Log</div>
                <span class="dw-nav-count"><?= $totalLogs ?></span>
            </a>
        </div>

        <!-- Content -->
        <div class="dw-content">

            <!-- Global Counter Card -->
            <div class="dw-counter-card">
                <div class="dw-counter-left">
                    <div class="dw-counter-eyebrow">
                        <i class="fa-solid fa-link"></i> Single Global Counter
                    </div>
                    <p class="dw-counter-title">Nomor Dokumen Bersama</p>
                    <p class="dw-counter-sub">Berita Acara &amp; Barang Keluar menggunakan satu penomoran berurutan global.</p>
                </div>
                <div class="dw-counter-right">
                    <div class="dw-counter-stat">
                        <div class="dw-counter-stat-lbl">Nomor Terakhir</div>
                        <div class="dw-counter-stat-val" style="color:var(--text-subtle)"><?= esc($lastFormatted) ?></div>
                    </div>
                    <div class="dw-counter-divider"></div>
                    <div class="dw-counter-stat">
                        <div class="dw-counter-stat-lbl">Nomor Berikutnya</div>
                        <div class="dw-counter-stat-val next"><?= esc($nextFormatted) ?></div>
                    </div>
                    <div class="dw-counter-divider"></div>
                    <div class="dw-global-pill">
                        <i class="fa-solid fa-globe" style="font-size:11px"></i> GLOBAL
                    </div>
                </div>
            </div>

            <!-- Doc Cards -->
            <div class="dw-cards">

                <!-- Berita Acara -->
                <div class="dw-doc-card">
                    <div class="dw-doc-card-top">
                        <div>
                            <div class="dw-doc-title">Berita Acara</div>
                            <div class="dw-doc-desc">Dokumen resmi serah terima barang pendistribusian donasi kepada mitra.</div>
                        </div>
                        <div class="dw-doc-icon indigo">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                    </div>

                    <div class="dw-info-block">
                        <div class="dw-info-row">
                            <span class="dw-info-lbl">Nomor berikutnya</span>
                            <span class="dw-info-val blue"><?= esc($nextFormatted) ?></span>
                        </div>
                        <div class="dw-info-row">
                            <span class="dw-info-lbl">Ketentuan aktif</span>
                            <span class="dw-info-val green"><?= $baProvisionCount ?> aktif &middot; v<?= $baConfig['current_version'] ?? 1 ?></span>
                        </div>
                        <div class="dw-info-row">
                            <span class="dw-info-lbl">Counter</span>
                            <span class="dw-info-val indigo">Bersama (Global)</span>
                        </div>
                    </div>

                    <div class="dw-card-actions">
                        <a href="<?= site_url('pengaturan/dokumen/berita_acara') ?>" class="dw-card-btn manage">
                            <i class="fa-solid fa-gear"></i> Kelola
                        </a>
                        <a href="<?= site_url('pengaturan/dokumen/berita_acara/preview') ?>" target="_blank" class="dw-card-btn preview">
                            <i class="fa-solid fa-eye"></i> Preview
                        </a>
                    </div>
                </div>

                <!-- Barang Keluar -->
                <div class="dw-doc-card">
                    <div class="dw-doc-card-top">
                        <div>
                            <div class="dw-doc-title">Barang Keluar</div>
                            <div class="dw-doc-desc">Surat jalan &amp; laporan transaksi penyaluran barang keluar dari gudang.</div>
                        </div>
                        <div class="dw-doc-icon teal">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </div>
                    </div>

                    <div class="dw-info-block">
                        <div class="dw-info-row">
                            <span class="dw-info-lbl">Nomor berikutnya</span>
                            <span class="dw-info-val blue"><?= esc($nextFormatted) ?></span>
                        </div>
                        <div class="dw-info-row">
                            <span class="dw-info-lbl">Template PDF</span>
                            <span class="dw-info-val green">Bawaan Existing</span>
                        </div>
                        <div class="dw-info-row">
                            <span class="dw-info-lbl">Counter</span>
                            <span class="dw-info-val indigo">Bersama (Global)</span>
                        </div>
                    </div>

                    <div class="dw-card-actions">
                        <a href="<?= site_url('pengaturan/dokumen/barang_keluar') ?>" class="dw-card-btn manage">
                            <i class="fa-solid fa-gear"></i> Kelola
                        </a>
                        <a href="<?= site_url('pengaturan/dokumen/barang_keluar/preview') ?>" target="_blank" class="dw-card-btn preview">
                            <i class="fa-solid fa-eye"></i> Preview
                        </a>
                    </div>
                </div>

            </div><!-- /dw-cards -->

        </div><!-- /dw-content -->

    </div><!-- /dw-layout -->

</div><!-- /dw -->

<?= $this->endSection() ?>