<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

.dw-root {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 24px 48px;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #0F172A;
}

/* ── HEADER ── */
.dw-header {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #fff;
    margin-bottom: 24px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.dw-header-left { display: flex; align-items: center; gap: 16px; }
.dw-header-icon {
    width: 52px; height: 52px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #818CF8;
}
.dw-h1 { font-size: 22px; font-weight: 800; margin: 0; color: #fff; letter-spacing: -0.5px; }
.dw-p { font-size: 13.5px; color: #94A3B8; margin: 4px 0 0 0; }

/* ── LAYOUT ── */
.dw-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 24px;
}
@media (max-width: 992px) {
    .dw-layout { grid-template-columns: 1fr; }
}

/* ── SIDEBAR ── */
.dw-sidebar {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 16px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    height: fit-content;
}
.dw-nav-title {
    font-size: 11px; font-weight: 800; color: #94A3B8;
    text-transform: uppercase; letter-spacing: 0.8px;
    padding: 8px 12px 6px; margin-top: 8px;
}
.dw-nav-title:first-child { margin-top: 0; }

.dw-nav-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-radius: 12px;
    color: #475569; font-size: 13.5px; font-weight: 600;
    text-decoration: none; transition: all 0.18s ease;
    margin-bottom: 3px;
}
.dw-nav-item i { font-size: 15px; color: #64748B; margin-right: 10px; width: 18px; text-align: center; }
.dw-nav-item:hover { background: #F1F5F9; color: #0F172A; }
.dw-nav-item.active { background: #EFF6FF; color: #2563EB; font-weight: 700; }
.dw-nav-item.active i { color: #2563EB; }
.dw-nav-count { background: #E2E8F0; color: #475569; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 99px; }

/* ── GLOBAL COUNTER BANNER CARD ── */
.dw-global-banner {
    background: linear-gradient(135deg, #1E1B4B 0%, #312E81 100%);
    border-radius: 18px;
    padding: 24px;
    color: #fff;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px rgba(49, 46, 129, 0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}
.dw-gb-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #A5B4FC; letter-spacing: 0.8px; }
.dw-gb-title { font-size: 18px; font-weight: 800; color: #fff; margin: 4px 0 0 0; }
.dw-gb-badge {
    background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 6px 14px; border-radius: 99px; font-size: 12px; font-weight: 700; color: #E0E7FF;
}
.dw-gb-num { font-family: 'JetBrains Mono', monospace; font-size: 22px; font-weight: 800; color: #38BDF8; }

/* ── CARDS GRID ── */
.dw-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
}

.dw-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    display: flex; flex-direction: column; justify-content: space-between;
    transition: all 0.2s ease; position: relative; overflow: hidden;
}
.dw-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    border-color: #CBD5E1;
}

.dw-card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
.dw-card-title { font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 4px 0; }
.dw-card-desc { font-size: 13px; color: #64748B; margin: 0; line-height: 1.4; }
.dw-doc-icon {
    width: 44px; height: 44px; border-radius: 14px;
    background: #EEF2FF; color: #4F46E5; font-size: 20px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

.dw-card-info {
    background: #F8FAFC; border: 1px solid #F1F5F9;
    border-radius: 14px; padding: 14px 16px; margin: 18px 0;
    font-size: 13px;
}
.dw-info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
.dw-info-row:last-child { margin-bottom: 0; }
.dw-info-lbl { color: #64748B; font-weight: 500; }
.dw-info-val { font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0F172A; }

.dw-card-actions { display: flex; gap: 10px; }
.dw-btn {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    padding: 11px 16px; border-radius: 12px; font-size: 13.5px; font-weight: 700;
    text-decoration: none; cursor: pointer; border: none; transition: all 0.15s ease;
}
.dw-btn-manage { background: #2563EB; color: #fff; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
.dw-btn-manage:hover { background: #1D4ED8; color: #fff; }
.dw-btn-preview { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }
.dw-btn-preview:hover { background: #E2E8F0; color: #0F172A; }
</style>

<div class="dw-root">

    <!-- HEADER HERO -->
    <div class="dw-header">
        <div class="dw-header-left">
            <div class="dw-header-icon">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
            <div>
                <h1 class="dw-h1">Workspace Dokumen & PDF</h1>
                <p class="dw-p">Kelola penomoran dokumen bersama, ketentuan, dan konfigurasi Berita Acara & Barang Keluar.</p>
            </div>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="<?= site_url('pengaturan/dokumen/riwayat') ?>" class="btn btn-outline-light rounded-3 px-3 py-2 fw-semibold btn-sm">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat Dokumen (<?= $totalHistory ?>)
            </a>
            <a href="<?= site_url('pengaturan/dokumen/activity-log') ?>" class="btn btn-outline-light rounded-3 px-3 py-2 fw-semibold btn-sm">
                <i class="fa-solid fa-history me-1"></i> Activity Log (<?= $totalLogs ?>)
            </a>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="dw-layout">

        <!-- SIDEBAR WORKSPACE NAV -->
        <div class="dw-sidebar">
            <div class="dw-nav-title">Menu Workspace</div>
            <a href="<?= site_url('pengaturan/dokumen') ?>" class="dw-nav-item <?= $activeDocKey === 'dashboard' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-grid-2"></i> Dashboard Dokumen</span>
            </a>

            <div class="dw-nav-title">Dokumen Kelola</div>
            <a href="<?= site_url('pengaturan/dokumen/berita_acara') ?>" class="dw-nav-item <?= $activeDocKey === 'berita_acara' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-file-signature"></i> Berita Acara</span>
                <span class="dw-nav-count"><?= $baProvisionCount ?> Aktif</span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/barang_keluar') ?>" class="dw-nav-item <?= $activeDocKey === 'barang_keluar' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-truck-ramp-box"></i> Barang Keluar</span>
            </a>

            <div class="dw-nav-title">Audit & Histori</div>
            <a href="<?= site_url('pengaturan/dokumen/riwayat') ?>" class="dw-nav-item <?= $activeDocKey === 'riwayat' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-list-check"></i> Riwayat Terbit</span>
                <span class="dw-nav-count"><?= $totalHistory ?></span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/activity-log') ?>" class="dw-nav-item <?= $activeDocKey === 'activity_log' ? 'active' : '' ?>">
                <span><i class="fa-solid fa-user-clock"></i> Activity Log</span>
                <span class="dw-nav-count"><?= $totalLogs ?></span>
            </a>
        </div>

        <!-- CONTENT -->
        <div>

            <!-- BANNER NOMOR DOKUMEN BERSAMA (SINGLE GLOBAL COUNTER) -->
            <div class="dw-global-banner">
                <div>
                    <div class="dw-gb-label"><i class="fa-solid fa-link me-1"></i> Single Global Counter</div>
                    <h3 class="dw-gb-title">Nomor Dokumen Bersama (Berita Acara & Barang Keluar)</h3>
                    <p style="margin: 4px 0 0 0; font-size: 13px; color: #C7D2FE;">Menggunakan satu penomoran berurutan global secara otomatis.</p>
                </div>
                <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                    <div style="text-align: right;">
                        <div style="font-size: 11px; color: #A5B4FC; text-transform: uppercase;">Nomor Terakhir</div>
                        <div class="dw-gb-num" style="color: #94A3B8; font-size: 16px;"><?= esc($lastFormatted) ?></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 11px; color: #A5B4FC; text-transform: uppercase;">Nomor Berikutnya</div>
                        <div class="dw-gb-num"><?= esc($nextFormatted) ?></div>
                    </div>
                    <div class="dw-gb-badge">
                        Counter: GLOBAL
                    </div>
                </div>
            </div>

            <!-- CARDS GRID VIEW -->
            <div class="dw-card-grid">

                <!-- 1. CARD BERITA ACARA -->
                <div class="dw-card">
                    <div>
                        <div class="dw-card-head">
                            <div>
                                <h3 class="dw-card-title">Berita Acara</h3>
                                <p class="dw-card-desc">Dokumen resmi serah terima barang pendistribusian donasi kepada mitra.</p>
                            </div>
                            <div class="dw-doc-icon">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                        </div>

                        <div class="dw-card-info">
                            <div class="dw-info-row">
                                <span class="dw-info-lbl">Nomor Berikutnya</span>
                                <span class="dw-info-val" style="color: #2563EB;"><?= esc($nextFormatted) ?></span>
                            </div>
                            <div class="dw-info-row">
                                <span class="dw-info-lbl">Ketentuan Aktif</span>
                                <span class="dw-info-val" style="color: #059669;"><?= $baProvisionCount ?> Aktif (v<?= $baConfig['current_version'] ?? 1 ?>)</span>
                            </div>
                            <div class="dw-info-row">
                                <span class="dw-info-lbl">Counter Penomoran</span>
                                <span class="dw-info-val" style="color: #6366F1;">BERSAMA (GLOBAL)</span>
                            </div>
                        </div>
                    </div>

                    <div class="dw-card-actions">
                        <a href="<?= site_url('pengaturan/dokumen/berita_acara') ?>" class="dw-btn dw-btn-manage">
                            <i class="fa-solid fa-gear"></i> Kelola
                        </a>
                        <a href="<?= site_url('pengaturan/dokumen/berita_acara/preview') ?>" target="_blank" class="dw-btn dw-btn-preview">
                            <i class="fa-solid fa-eye"></i> Preview
                        </a>
                    </div>
                </div>

                <!-- 2. CARD BARANG KELUAR -->
                <div class="dw-card">
                    <div>
                        <div class="dw-card-head">
                            <div>
                                <h3 class="dw-card-title">Barang Keluar</h3>
                                <p class="dw-card-desc">Surat jalan & laporan transaksi penyaluran barang keluar dari gudang.</p>
                            </div>
                            <div class="dw-doc-icon" style="background: #ECFDF5; color: #10B981;">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                            </div>
                        </div>

                        <div class="dw-card-info">
                            <div class="dw-info-row">
                                <span class="dw-info-lbl">Nomor Berikutnya</span>
                                <span class="dw-info-val" style="color: #2563EB;"><?= esc($nextFormatted) ?></span>
                            </div>
                            <div class="dw-info-row">
                                <span class="dw-info-lbl">Template PDF</span>
                                <span class="dw-info-val" style="color: #059669;">Bawaan Existing</span>
                            </div>
                            <div class="dw-info-row">
                                <span class="dw-info-lbl">Counter Penomoran</span>
                                <span class="dw-info-val" style="color: #6366F1;">BERSAMA (GLOBAL)</span>
                            </div>
                        </div>
                    </div>

                    <div class="dw-card-actions">
                        <a href="<?= site_url('pengaturan/dokumen/barang_keluar') ?>" class="dw-btn dw-btn-manage">
                            <i class="fa-solid fa-gear"></i> Kelola
                        </a>
                        <a href="<?= site_url('pengaturan/dokumen/barang_keluar/preview') ?>" target="_blank" class="dw-btn dw-btn-preview">
                            <i class="fa-solid fa-eye"></i> Preview
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
