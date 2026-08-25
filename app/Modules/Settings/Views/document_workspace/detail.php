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
    --teal:        #0D9488;
    --teal-bg:     #F0FDFA;
    --danger:      #DC2626;
    --danger-bg:   #FEF2F2;
    --warning:     #D97706;
    --warning-bg:  #FFFBEB;
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
.dw-btn-primary  { background: var(--primary); color: #fff; }
.dw-btn-primary:hover { background: #1D4ED8; color: #fff; }
.dw-btn-outline {
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-soft);
}
.dw-btn-outline:hover { background: var(--muted-bg); color: var(--text); }
.dw-btn-success  { background: var(--success); color: #fff; }
.dw-btn-success:hover { background: #15803D; color: #fff; }
.dw-btn-danger   { background: var(--danger);  color: #fff; }
.dw-btn-danger:hover  { background: #B91C1C; color: #fff; }
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

/* ── Main Panel ──────────────────────────────────────────── */
.dw-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    overflow: hidden;
}

/* ── Tabs ────────────────────────────────────────────────── */
.dw-tabs {
    display: flex;
    gap: 4px;
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    background: var(--bg);
}
.dw-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 500;
    color: var(--text-soft);
    background: transparent;
    border: 1px solid transparent;
    cursor: pointer;
    transition: background .13s, color .13s, border-color .13s;
    font-family: 'Inter', sans-serif;
}
.dw-tab-btn i { font-size: 13px; }
.dw-tab-btn:hover { background: var(--surface); color: var(--text); border-color: var(--border); }
.dw-tab-btn.active {
    background: var(--surface);
    color: var(--primary);
    border-color: var(--border);
    font-weight: 600;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

/* ── Tab pane ────────────────────────────────────────────── */
.dw-tab-content { padding: 22px; }

/* ── Preview Box ─────────────────────────────────────────── */
.dw-preview-box {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
}
.dw-preview-box-left {}
.dw-preview-lbl {
    font-size: 10.5px;
    font-weight: 600;
    color: var(--text-subtle);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
}
.dw-preview-num {
    font-size: 20px;
    font-weight: 700;
    color: var(--indigo);
    font-variant-numeric: tabular-nums;
    letter-spacing: -.3px;
}

/* ── Alert info ──────────────────────────────────────────── */
.dw-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: var(--primary-bg);
    border: 1px solid #BFDBFE;
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    margin-bottom: 20px;
    font-size: 13px;
    color: #1E40AF;
}
.dw-alert i { font-size: 16px; margin-top: 1px; flex-shrink: 0; }
.dw-alert strong { font-weight: 600; display: block; margin-bottom: 2px; }

/* ── Form ────────────────────────────────────────────────── */
.dw-form-group { margin-bottom: 16px; }
.dw-label {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 5px;
    display: block;
}
.dw-sub-label {
    font-size: 11.5px;
    color: var(--text-subtle);
    margin-top: 4px;
    display: block;
}
.dw-input {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 13px;
    color: var(--text);
    background: #FAFAFA;
    outline: none;
    font-family: 'Inter', sans-serif;
    transition: border-color .15s, box-shadow .15s, background .15s;
}
.dw-input:focus {
    border-color: var(--indigo);
    background: var(--surface);
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.dw-input[readonly] { background: var(--muted-bg); color: var(--text-soft); cursor: not-allowed; }
select.dw-input { appearance: auto; cursor: pointer; }

.dw-form-footer {
    padding-top: 16px;
    border-top: 1px solid var(--border);
    margin-top: 20px;
}

/* ── Tab section title ───────────────────────────────────── */
.dw-section-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
}
.dw-section-head-left {}
.dw-section-title { font-size: 15px; font-weight: 700; color: var(--text); margin: 0 0 3px; letter-spacing: -.2px; }
.dw-section-sub   { font-size: 12.5px; color: var(--text-subtle); margin: 0; }
.dw-section-head-right { display: flex; gap: 8px; flex-wrap: wrap; }

/* ── Table ───────────────────────────────────────────────── */
.dw-table-wrap {
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    overflow: hidden;
}
.dw-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.dw-table thead th {
    background: var(--bg);
    color: var(--text-subtle);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    text-align: left;
    white-space: nowrap;
}
.dw-table tbody td {
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    vertical-align: middle;
}
.dw-table tbody tr:last-child td { border-bottom: none; }
.dw-table tbody tr:hover td { background: #FAFAFA; }

/* ── Badge ───────────────────────────────────────────────── */
.dw-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 999px;
}
.dw-badge.green  { background: var(--success-bg); color: #15803D; }
.dw-badge.gray   { background: var(--muted-bg);   color: var(--text-soft); }
.dw-badge.blue   { background: var(--primary-bg);  color: var(--primary); }
.dw-badge.indigo { background: var(--indigo-bg);   color: var(--indigo); }

/* ── Action icon buttons ─────────────────────────────────── */
.dw-icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--surface);
    color: var(--text-soft);
    font-size: 13px;
    cursor: pointer;
    text-decoration: none;
    transition: background .12s, border-color .12s, color .12s;
}
.dw-icon-btn:hover { background: var(--muted-bg); color: var(--text); }
.dw-icon-btn.danger:hover { background: var(--danger-bg); border-color: #FECACA; color: var(--danger); }

/* ── Empty state ─────────────────────────────────────────── */
.dw-empty {
    text-align: center;
    padding: 36px 20px;
    color: var(--text-subtle);
    font-size: 13px;
}
.dw-empty i { font-size: 28px; margin-bottom: 10px; display: block; }

/* ── Mono text ───────────────────────────────────────────── */
.mono { font-family: 'SF Mono', 'Fira Code', monospace; font-size: 12px; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
    .dw { padding: 12px 14px 40px; }
    .dw-header-actions .dw-btn span { display: none; }
    .dw-tab-btn span { display: none; }
    .dw-preview-box { flex-direction: column; align-items: flex-start; }
}
</style>

<div class="dw">

    <!-- ── Page Header ─────────────────────────────────────── -->
    <div class="dw-header">
        <div class="dw-header-left">
            <h1><i class="fa-solid fa-sliders"></i> Workspace <?= esc($config['title']) ?></h1>
            <p><?= esc($config['description']) ?></p>
        </div>
        <div class="dw-header-actions">
            <a href="<?= site_url('pengaturan/dokumen/' . $config['document_key'] . '/preview') ?>"
               target="_blank" class="dw-btn dw-btn-primary">
                <i class="fa-solid fa-eye"></i> <span>Live Preview PDF</span>
            </a>
            <a href="<?= site_url('pengaturan/dokumen') ?>" class="dw-btn dw-btn-outline">
                <i class="fa-solid fa-arrow-left"></i> <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- ── Main Layout ─────────────────────────────────────── -->
    <div class="dw-layout">

        <!-- Sidebar -->
        <div class="dw-sidebar">
            <div class="dw-nav-section">Menu Workspace</div>
            <a href="<?= site_url('pengaturan/dokumen') ?>" class="dw-nav-item">
                <div class="dw-nav-item-left"><i class="fa-solid fa-table-columns"></i> Dashboard</div>
            </a>

            <div class="dw-nav-section">Dokumen</div>
            <a href="<?= site_url('pengaturan/dokumen/berita_acara') ?>"
               class="dw-nav-item <?= $activeDocKey === 'berita_acara' ? 'active' : '' ?>">
                <div class="dw-nav-item-left"><i class="fa-solid fa-file-signature"></i> Berita Acara</div>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/barang_keluar') ?>"
               class="dw-nav-item <?= $activeDocKey === 'barang_keluar' ? 'active' : '' ?>">
                <div class="dw-nav-item-left"><i class="fa-solid fa-truck-ramp-box"></i> Barang Keluar</div>
            </a>

            <div class="dw-nav-section">Audit</div>
            <a href="<?= site_url('pengaturan/dokumen/riwayat') ?>" class="dw-nav-item">
                <div class="dw-nav-item-left"><i class="fa-solid fa-list-check"></i> Riwayat Terbit</div>
            </a>
            <a href="<?= site_url('pengaturan/dokumen/activity-log') ?>" class="dw-nav-item">
                <div class="dw-nav-item-left"><i class="fa-solid fa-user-clock"></i> Activity Log</div>
            </a>
        </div>

        <!-- Main Panel -->
        <div class="dw-panel">

            <!-- Tabs nav -->
            <div class="dw-tabs" id="docTabs" role="tablist">
                <button class="dw-tab-btn active" data-bs-toggle="tab" data-bs-target="#tab-numbering" type="button" role="tab">
                    <i class="fa-solid fa-hashtag"></i> <span>Penomoran</span>
                </button>
                <?php if ($activeDocKey === 'berita_acara'): ?>
                <button class="dw-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-provisions" type="button" role="tab">
                    <i class="fa-solid fa-list-ol"></i> <span>Ketentuan</span>
                    <span style="background:var(--indigo-bg);color:var(--indigo);font-size:10.5px;font-weight:600;padding:1px 7px;border-radius:999px">v<?= $config['current_version'] ?? 1 ?></span>
                </button>
                <?php endif; ?>
                <button class="dw-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-history" type="button" role="tab">
                    <i class="fa-solid fa-clock-rotate-left"></i> <span>Riwayat</span>
                    <span style="background:var(--muted-bg);color:var(--text-subtle);font-size:10.5px;font-weight:600;padding:1px 7px;border-radius:999px"><?= count($history) ?></span>
                </button>
                <button class="dw-tab-btn" data-bs-toggle="tab" data-bs-target="#tab-logs" type="button" role="tab">
                    <i class="fa-solid fa-receipt"></i> <span>Activity Log</span>
                </button>
            </div>

            <div class="tab-content">

                <!-- ── Tab 1: Penomoran ─────────────────────── -->
                <div class="tab-pane fade show active dw-tab-content" id="tab-numbering" role="tabpanel">

                    <!-- Preview box -->
                    <div class="dw-preview-box">
                        <div class="dw-preview-box-left">
                            <div class="dw-preview-lbl">Nomor dokumen berikutnya (simulasi)</div>
                            <div class="dw-preview-num"><?= esc($nextFormatted) ?></div>
                        </div>
                        <button type="button"
                                onclick="window.open('<?= site_url('pengaturan/dokumen/' . $config['document_key'] . '/preview') ?>', '_blank')"
                                class="dw-btn dw-btn-outline">
                            <i class="fa-solid fa-eye"></i> Preview PDF
                        </button>
                    </div>

                    <!-- Alert -->
                    <div class="dw-alert">
                        <i class="fa-solid fa-circle-info"></i>
                        <div>
                            <strong>Single Global Counter</strong>
                            Berita Acara dan Barang Keluar menggunakan satu counter nomor urut global yang berurutan secara otomatis.
                        </div>
                    </div>

                    <form action="<?= site_url('pengaturan/dokumen/' . $config['document_key'] . '/update-numbering') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="dw-form-group">
                                    <label class="dw-label">Format Pattern (Terkunci)</label>
                                    <input type="text" class="dw-input" value="{nomor}/{semester}/{bulan}/{tahun}" readonly>
                                    <input type="hidden" name="number_format" value="{nomor}/{semester}/{bulan}/{tahun}">
                                    <span class="dw-sub-label">Contoh hasil: 401/SEM2/8/26</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dw-form-group">
                                    <label class="dw-label">Nomor Terakhir Terbit</label>
                                    <input type="number" name="current_number" class="dw-input"
                                           value="<?= esc($globalConfig['current_number']) ?>" required>
                                    <span class="dw-sub-label">Berikutnya: <strong style="color:var(--indigo)"><?= $globalConfig['current_number'] + 1 ?></strong></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="dw-form-group">
                                    <label class="dw-label">Semester Aktif</label>
                                    <select name="active_semester" class="dw-input">
                                        <option value="SEM1" <?= in_array($globalConfig['active_semester'], ['SEM1','1','sem 1']) ? 'selected' : '' ?>>Semester 1 (SEM1)</option>
                                        <option value="SEM2" <?= in_array($globalConfig['active_semester'], ['SEM2','2','sem 2']) ? 'selected' : '' ?>>Semester 2 (SEM2)</option>
                                    </select>
                                    <span class="dw-sub-label">Tag: {semester}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dw-form-group">
                                    <label class="dw-label">Bulan Aktif</label>
                                    <select name="active_month" class="dw-input">
                                        <?php
                                        $namaBulan = ['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                                        $currMonth = ltrim($globalConfig['active_month'], '0');
                                        foreach ($namaBulan as $val => $label):
                                        ?>
                                            <option value="<?= $val ?>" <?= $currMonth == $val ? 'selected' : '' ?>><?= $label ?> (<?= $val ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="dw-sub-label">Tag: {bulan}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dw-form-group">
                                    <label class="dw-label">Tahun Aktif</label>
                                    <input type="text" name="active_year" class="dw-input"
                                           value="<?= esc($globalConfig['active_year']) ?>" placeholder="Misal: 26 atau 2026" required>
                                    <span class="dw-sub-label">Tag: {tahun} / {tahun_full}</span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="dw-form-group">
                                    <label class="dw-label">Aturan Reset Nomor</label>
                                    <select name="reset_rule" class="dw-input">
                                        <option value="none"     <?= $globalConfig['reset_rule'] === 'none'     ? 'selected' : '' ?>>Tidak pernah reset (counter lanjut)</option>
                                        <option value="monthly"  <?= $globalConfig['reset_rule'] === 'monthly'  ? 'selected' : '' ?>>Reset setiap bulan</option>
                                        <option value="semester" <?= $globalConfig['reset_rule'] === 'semester' ? 'selected' : '' ?>>Reset setiap semester</option>
                                        <option value="yearly"   <?= $globalConfig['reset_rule'] === 'yearly'   ? 'selected' : '' ?>>Reset setiap tahun</option>
                                    </select>
                                    <span class="dw-sub-label">Dokumen lama tidak terpengaruh saat reset.</span>
                                </div>
                            </div>
                        </div>

                        <?php if (in_groups('Administrator')): ?>
                        <div class="dw-form-footer">
                            <button type="submit" class="dw-btn dw-btn-primary" style="height:40px;font-weight:600">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Penomoran
                            </button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- ── Tab 2: Ketentuan BA ──────────────────── -->
                <?php if ($activeDocKey === 'berita_acara'): ?>
                <div class="tab-pane fade dw-tab-content" id="tab-provisions" role="tabpanel">
                    <div class="dw-section-head">
                        <div class="dw-section-head-left">
                            <p class="dw-section-title">Ketentuan Berita Acara <span style="color:var(--indigo)">v<?= $config['current_version'] ?? 1 ?></span></p>
                            <p class="dw-section-sub">Ketentuan yang otomatis tertera pada Berita Acara baru.</p>
                        </div>
                        <?php if (in_groups('Administrator')): ?>
                        <div class="dw-section-head-right">
                            <form action="<?= site_url('pengaturan/dokumen/berita_acara/new-version') ?>" method="POST"
                                  onsubmit="return confirm('Rilis versi ketentuan baru?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="dw-btn dw-btn-ghost" style="height:36px;font-weight:600">
                                    <i class="fa-solid fa-code-branch"></i> Versi Baru (v<?= ($config['current_version'] ?? 1) + 1 ?>)
                                </button>
                            </form>
                            <button type="button" class="dw-btn dw-btn-success" style="height:36px;font-weight:600"
                                    data-bs-toggle="modal" data-bs-target="#addProvisionModal">
                                <i class="fa-solid fa-plus"></i> Tambah
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="dw-table-wrap">
                        <table class="dw-table">
                            <thead>
                                <tr>
                                    <th style="width:56px">Urutan</th>
                                    <th>Isi Ketentuan</th>
                                    <th style="width:90px">Status</th>
                                    <?php if (in_groups('Administrator')): ?>
                                    <th style="width:80px;text-align:center">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($provisions)): ?>
                                <tr><td colspan="4">
                                    <div class="dw-empty"><i class="fa-solid fa-list-check"></i> Belum ada ketentuan.</div>
                                </td></tr>
                                <?php else: ?>
                                <?php foreach ($provisions as $p): ?>
                                <tr>
                                    <td style="text-align:center;font-weight:600;color:var(--text-subtle)"><?= $p['sort_order'] ?></td>
                                    <td style="font-size:13px"><?= esc($p['content']) ?></td>
                                    <td>
                                        <?php if ($p['is_active'] == 1): ?>
                                            <span class="dw-badge green">Aktif</span>
                                        <?php else: ?>
                                            <span class="dw-badge gray">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (in_groups('Administrator')): ?>
                                    <td>
                                        <div style="display:flex;gap:5px;justify-content:center">
                                            <button class="dw-icon-btn" onclick="editProvision(<?= htmlspecialchars(json_encode($p)) ?>)" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <a href="<?= site_url('pengaturan/dokumen/berita_acara/provision/delete/' . $p['id']) ?>"
                                               onclick="return confirm('Hapus ketentuan ini?')" class="dw-icon-btn danger" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ── Tab 3: Riwayat ───────────────────────── -->
                <div class="tab-pane fade dw-tab-content" id="tab-history" role="tabpanel">
                    <div class="dw-section-head">
                        <div>
                            <p class="dw-section-title">Riwayat Penerbitan — <?= esc($config['title']) ?></p>
                            <p class="dw-section-sub">Dokumen terbit disimpan secara permanen.</p>
                        </div>
                    </div>
                    <div class="dw-table-wrap">
                        <table class="dw-table">
                            <thead>
                                <tr>
                                    <th>Nomor Dokumen</th>
                                    <th>Jenis</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Tanggal Terbit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($history)): ?>
                                <tr><td colspan="5">
                                    <div class="dw-empty"><i class="fa-solid fa-clock-rotate-left"></i> Belum ada riwayat.</div>
                                </td></tr>
                                <?php else: ?>
                                <?php foreach ($history as $h): ?>
                                <tr>
                                    <td><span class="mono" style="color:var(--indigo);font-weight:600"><?= esc($h['document_number']) ?></span></td>
                                    <td><?= esc($h['document_name']) ?></td>
                                    <td style="color:var(--text-soft)"><?= esc($h['created_by']) ?></td>
                                    <td style="color:var(--text-soft)"><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></td>
                                    <td><span class="dw-badge blue"><?= esc($h['status']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ── Tab 4: Activity Log ──────────────────── -->
                <div class="tab-pane fade dw-tab-content" id="tab-logs" role="tabpanel">
                    <div class="dw-section-head">
                        <div>
                            <p class="dw-section-title">Activity Audit Log</p>
                            <p class="dw-section-sub">Jejak audit perubahan oleh Administrator.</p>
                        </div>
                    </div>
                    <div class="dw-table-wrap">
                        <table class="dw-table">
                            <thead>
                                <tr>
                                    <th>Tanggal &amp; Waktu</th>
                                    <th>Administrator</th>
                                    <th>Aksi</th>
                                    <th>Nilai Sebelum</th>
                                    <th>Nilai Sesudah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($activityLogs)): ?>
                                <tr><td colspan="5">
                                    <div class="dw-empty"><i class="fa-solid fa-receipt"></i> Belum ada catatan.</div>
                                </td></tr>
                                <?php else: ?>
                                <?php foreach ($activityLogs as $log): ?>
                                <tr>
                                    <td style="color:var(--text-soft);white-space:nowrap"><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                                    <td style="font-weight:600"><?= esc($log['username']) ?></td>
                                    <td><span class="dw-badge indigo"><?= esc($log['action_type']) ?></span></td>
                                    <td><span class="mono" style="color:var(--text-soft)"><?= esc($log['before_value'] ?? '—') ?></span></td>
                                    <td><span class="mono" style="font-weight:600"><?= esc($log['after_value'] ?? '—') ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- /tab-content -->
        </div><!-- /dw-panel -->

    </div><!-- /dw-layout -->
</div><!-- /dw -->

<!-- ── Modal Tambah Ketentuan ──────────────────────────────── -->
<?php if ($activeDocKey === 'berita_acara'): ?>
<div class="modal fade" id="addProvisionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= site_url('pengaturan/dokumen/berita_acara/provision/add') ?>" method="POST"
              class="modal-content border-0 rounded-4 shadow">
            <?= csrf_field() ?>
            <div class="modal-header border-bottom" style="padding:16px 20px">
                <h5 class="modal-title fw-bold" style="font-size:15px">Tambah Ketentuan Berita Acara</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px">
                <div class="dw-form-group">
                    <label class="dw-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="dw-input" value="<?= count($provisions) + 1 ?>" required>
                </div>
                <div class="dw-form-group">
                    <label class="dw-label">Isi Teks Ketentuan</label>
                    <textarea name="content" class="dw-input" style="height:auto;padding:10px 12px;resize:vertical" rows="4"
                              placeholder="Tuliskan isi ketentuan..." required></textarea>
                </div>
            </div>
            <div class="modal-footer border-top" style="padding:14px 20px;gap:8px">
                <button type="button" class="dw-btn dw-btn-outline" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="dw-btn dw-btn-success" style="font-weight:600">Simpan Ketentuan</button>
            </div>
        </form>
    </div>
</div>

<!-- ── Modal Edit Ketentuan ──────────────────────────────────── -->
<div class="modal fade" id="editProvisionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editProvisionForm" method="POST" class="modal-content border-0 rounded-4 shadow">
            <?= csrf_field() ?>
            <div class="modal-header border-bottom" style="padding:16px 20px">
                <h5 class="modal-title fw-bold" style="font-size:15px">Edit Ketentuan Berita Acara</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px">
                <div class="dw-form-group">
                    <label class="dw-label">Urutan Tampil</label>
                    <input type="number" id="edit_sort_order" name="sort_order" class="dw-input" required>
                </div>
                <div class="dw-form-group">
                    <label class="dw-label">Isi Teks Ketentuan</label>
                    <textarea id="edit_content" name="content" class="dw-input" style="height:auto;padding:10px 12px;resize:vertical" rows="4" required></textarea>
                </div>
                <div class="dw-form-group">
                    <label class="dw-label">Status</label>
                    <select id="edit_is_active" name="is_active" class="dw-input">
                        <option value="1">Aktif (tampil di PDF baru)</option>
                        <option value="0">Nonaktif (disembunyikan)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top" style="padding:14px 20px;gap:8px">
                <button type="button" class="dw-btn dw-btn-outline" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="dw-btn dw-btn-primary" style="font-weight:600">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editProvision(p) {
    document.getElementById('edit_sort_order').value = p.sort_order;
    document.getElementById('edit_content').value    = p.content;
    document.getElementById('edit_is_active').value  = p.is_active;
    document.getElementById('editProvisionForm').action =
        '<?= site_url("pengaturan/dokumen/berita_acara/provision/update/") ?>' + p.id;
    new bootstrap.Modal(document.getElementById('editProvisionModal')).show();
}
</script>
<?php endif; ?>

<?= $this->endSection() ?> 