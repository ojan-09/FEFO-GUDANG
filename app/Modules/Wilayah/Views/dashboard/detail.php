<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

.dg-wrap * { box-sizing: border-box; }
.dg-wrap {
    --bg: #F8FAFC;
    --card: #fff;
    --border: #E5E7EB;
    --border-strong: #D1D5DB;
    --text-1: #0F172A;
    --text-2: #475569;
    --text-3: #94A3B8;
    --blue: #2563EB;
    --blue-bg: #EFF6FF;
    --blue-text: #1D4ED8;
    --green: #16A34A;
    --green-bg: #F0FDF4;
    --green-text: #15803D;
    --amber: #D97706;
    --amber-bg: #FFFBEB;
    --amber-text: #B45309;
    --red: #DC2626;
    --red-bg: #FEF2F2;
    --red-text: #B91C1C;
    --cyan: #0891B2;
    --cyan-bg: #ECFEFF;
    --cyan-text: #0E7490;
    --slate: #64748B;
    --r: 16px;
    --r-sm: 10px;
    --shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    padding: 28px 24px;
    color: var(--text-1);
    min-height: 100vh;
}

/* ── Page header ── */
.dg-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}
.dg-page-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
}
.dg-page-title i { color: var(--blue); font-size: 18px; }
.dg-page-sub { font-size: 13px; color: var(--text-2); }
.dg-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 34px;
    padding: 0 14px;
    border-radius: var(--r-sm);
    border: 1px solid var(--border-strong);
    background: var(--card);
    font-size: 12px;
    font-weight: 500;
    color: var(--text-2);
    text-decoration: none;
    white-space: nowrap;
    transition: background .12s;
}
.dg-back-btn:hover { background: var(--bg); color: var(--text-1); }
.dg-back-btn i { font-size: 13px; }

/* ── Card base ── */
.dg-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
}

/* ── Gudang hero ── */
.dg-hero { padding: 22px 24px; }
.dg-hero-name {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-1);
    margin-bottom: 6px;
}
.dg-hero-addr {
    font-size: 13px;
    color: var(--text-2);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 14px;
}
.dg-hero-addr i { color: var(--text-3); font-size: 13px; }
.dg-hero-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.dg-pic-row { display: flex; align-items: center; gap: 10px; }
.dg-pic-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: var(--blue-bg);
    border: 1px solid #BFDBFE;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 600; color: var(--blue-text);
    flex-shrink: 0;
}
.dg-pic-name { font-size: 13px; font-weight: 500; color: var(--text-1); }
.dg-pic-phone { font-size: 11px; color: var(--text-3); margin-top: 1px; }

/* ── Badge ── */
.dg-badge {
    display: inline-flex;
    align-items: center;
    height: 24px;
    padding: 0 10px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
}
.dg-badge.green { background: var(--green-bg); color: var(--green-text); }
.dg-badge.red   { background: var(--red-bg);   color: var(--red-text); }

/* ── Tabs ── */
.dg-tab-nav {
    display: flex;
    gap: 2px;
    padding: 14px 20px 0;
    border-bottom: 1px solid var(--border);
    overflow-x: auto;
    scrollbar-width: none;
}
.dg-tab-nav::-webkit-scrollbar { display: none; }
.dg-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 36px;
    padding: 0 14px;
    border: none;
    border-bottom: 2px solid transparent;
    background: none;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-3);
    cursor: pointer;
    white-space: nowrap;
    transition: color .12s, border-color .12s;
    margin-bottom: -1px;
    font-family: 'Inter', sans-serif;
}
.dg-tab-btn i { font-size: 14px; }
.dg-tab-btn:hover { color: var(--text-2); }
.dg-tab-btn.active { color: var(--blue); border-bottom-color: var(--blue); font-weight: 600; }

/* ── Tab panes ── */
.dg-tab-pane { display: none; padding: 24px; }
.dg-tab-pane.active { display: block; }

/* ── Info table ── */
.dg-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 640px) { .dg-info-grid { grid-template-columns: 1fr; } }
.dg-info-table { width: 100%; border-collapse: collapse; }
.dg-info-table tr { border-bottom: 1px solid var(--border); }
.dg-info-table tr:last-child { border-bottom: none; }
.dg-info-table th {
    width: 38%;
    padding: 11px 14px;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    background: var(--bg);
    text-align: left;
    vertical-align: top;
}
.dg-info-table td {
    padding: 11px 14px;
    font-size: 13px;
    color: var(--text-1);
    vertical-align: top;
}
.dg-info-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
}
.dg-info-title i { color: var(--blue); }

/* ── Data tables ── */
.dg-overflow { overflow-x: auto; }
.dg-table-wrap { padding: 0; }
.dg-dt-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 16px 24px 12px;
}
.dg-dt-toolbar label { font-size: 13px; color: var(--text-2); }
.dg-dt-toolbar select,
.dg-dt-toolbar input[type=search] {
    height: 32px;
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 0 10px;
    font-size: 12px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff;
    margin-left: 6px;
}
.dg-dt-toolbar input[type=search]:focus { outline: none; border-color: var(--blue); }

.dg-data-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.dg-data-table th {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 10px 16px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    text-align: left;
    white-space: nowrap;
}
.dg-data-table td {
    padding: 11px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-1);
    vertical-align: middle;
}
.dg-data-table tr:last-child td { border-bottom: none; }
.dg-data-table tr:hover td { background: #FAFAFA; }
.dg-data-table .center { text-align: center; }
.dg-data-table .right  { text-align: right; }

/* pills / codes */
.dg-code {
    display: inline-block;
    font-size: 11px;
    font-weight: 500;
    color: var(--text-2);
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 2px 8px;
    border-radius: 5px;
    font-family: monospace;
}
.dg-pill {
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 0 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.dg-pill.green  { background: var(--green-bg); color: var(--green-text); }
.dg-pill.cyan   { background: var(--cyan-bg);  color: var(--cyan-text); }
.dg-pill.amber  { background: var(--amber-bg); color: var(--amber-text); }
.dg-pill.slate  { background: #F1F5F9; color: var(--slate); }

.qty-pos { color: var(--green); font-weight: 600; }
.qty-neg { color: var(--amber); font-weight: 600; }

/* ── DT pagination ── */
.dg-dt-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 12px 24px 16px;
    border-top: 1px solid var(--border);
}
.dg-dt-info { font-size: 12px; color: var(--text-3); }
.dataTables_paginate .paginate_button {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 30px !important;
    height: 30px !important;
    padding: 0 8px !important;
    margin: 0 2px !important;
    border-radius: 7px !important;
    font-size: 12px !important;
    font-family: 'Inter', sans-serif !important;
    border: 1px solid var(--border) !important;
    cursor: pointer !important;
    color: var(--text-2) !important;
    background: var(--card) !important;
    box-shadow: none !important;
    text-decoration: none !important;
}
.dataTables_paginate .paginate_button.current {
    background: var(--blue-bg) !important;
    border-color: #BFDBFE !important;
    color: var(--blue-text) !important;
    font-weight: 600 !important;
}
.dataTables_paginate .paginate_button:hover:not(.current) {
    background: var(--bg) !important;
    color: var(--text-1) !important;
}
.dataTables_paginate .paginate_button.disabled { opacity: .4 !important; cursor: default !important; }

/* ── Laporan tab ── */
.dg-report-section { max-width: 560px; }
.dg-report-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
}
.dg-report-title i { color: var(--red); }
.dg-report-sub { font-size: 13px; color: var(--text-2); margin-bottom: 20px; line-height: 1.6; }
.dg-form-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 20px; }
@media (max-width: 560px) { .dg-form-grid { grid-template-columns: 1fr; } }
.dg-form-label { font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 5px; display: block; }
.dg-form-select,
.dg-form-input {
    width: 100%;
    height: 36px;
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 0 11px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff;
    appearance: none;
}
.dg-form-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    cursor: pointer;
}
.dg-form-select:focus,
.dg-form-input:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px #DBEAFE; }
.dg-export-row { display: flex; gap: 10px; flex-wrap: wrap; }
.dg-export-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 36px;
    padding: 0 16px;
    border-radius: var(--r-sm);
    font-size: 13px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .12s;
}
.dg-export-btn:hover { opacity: .85; }
.dg-export-btn i { font-size: 14px; }
.dg-export-btn.red   { background: var(--red-bg);   color: var(--red-text);   border: 1px solid #FECACA; }
.dg-export-btn.green { background: var(--green-bg); color: var(--green-text); border: 1px solid #BBF7D0; }
</style>

<div class="dg-wrap">

    <!-- ── Page header ── -->
    <div class="dg-page-header">
        <div>
            <div class="dg-page-title">
                <i class="fa-solid fa-building" aria-hidden="true"></i>
                Detail Gudang Wilayah
            </div>
            <p class="dg-page-sub">Informasi operasional, histori mutasi, dan monitoring stok gudang</p>
        </div>
        <a href="<?= site_url('wilayah/dashboard') ?>" class="dg-back-btn">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Kembali ke dashboard
        </a>
    </div>

    <!-- ── Hero card ── -->
    <div class="dg-card">
        <div class="dg-hero">
            <div class="dg-hero-name"><?= esc($gudang['nama']) ?></div>
            <div class="dg-hero-addr">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <?= esc($gudang['alamat']) ?>, <?= esc($gudang['kota']) ?>, <?= esc($gudang['provinsi']) ?>
            </div>
            <div class="dg-hero-bottom">
                <div class="dg-pic-row">
                    <?php
                        $picName  = $gudang['pic'] ?? 'PIC';
                        $picWords = explode(' ', trim($picName));
                        $initials = strtoupper(substr($picWords[0], 0, 1));
                        if (count($picWords) > 1) $initials .= strtoupper(substr(end($picWords), 0, 1));
                    ?>
                    <div class="dg-pic-avatar"><?= esc($initials) ?></div>
                    <div>
                        <div class="dg-pic-name"><?= esc($gudang['pic']) ?></div>
                        <div class="dg-pic-phone"><?= esc($gudang['telepon'] ?? '—') ?></div>
                    </div>
                </div>
                <span class="dg-badge <?= $gudang['status'] === 'Aktif' ? 'green' : 'red' ?>">
                    <?= esc($gudang['status']) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- ── Tabs ── -->
    <div class="dg-card" style="overflow:visible">
        <div class="dg-tab-nav" role="tablist">
            <button class="dg-tab-btn active" data-tab="info" role="tab" aria-selected="true">
                <i class="fa-solid fa-circle-info" aria-hidden="true"></i> Informasi gudang
            </button>
            <button class="dg-tab-btn" data-tab="stok" role="tab" aria-selected="false">
                <i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i> Stok gudang
            </button>
            <button class="dg-tab-btn" data-tab="masuk" role="tab" aria-selected="false">
                <i class="fa-solid fa-arrow-down-to-line" aria-hidden="true"></i> Barang masuk
            </button>
            <button class="dg-tab-btn" data-tab="keluar" role="tab" aria-selected="false">
                <i class="fa-solid fa-arrow-up-from-line" aria-hidden="true"></i> Barang keluar
            </button>
            <button class="dg-tab-btn" data-tab="laporan" role="tab" aria-selected="false">
                <i class="fa-solid fa-file-export" aria-hidden="true"></i> Laporan
            </button>
        </div>

        <!-- TAB 1: Informasi gudang -->
        <div class="dg-tab-pane active" id="tab-info">
            <div class="dg-info-title"><i class="fa-solid fa-building" aria-hidden="true"></i> Profil gudang wilayah</div>
            <div class="dg-info-grid">
                <div>
                    <table class="dg-info-table">
                        <tr>
                            <th>Nama gudang</th>
                            <td style="font-weight:500"><?= esc($gudang['nama']) ?></td>
                        </tr>
                        <tr>
                            <th>Penanggung jawab</th>
                            <td><?= esc($gudang['pic']) ?></td>
                        </tr>
                        <tr>
                            <th>No. telepon / WA</th>
                            <td><?= esc($gudang['telepon'] ?? '—') ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="dg-badge <?= $gudang['status'] === 'Aktif' ? 'green' : 'red' ?>">
                                    <?= esc($gudang['status']) ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="dg-info-table">
                        <tr>
                            <th>Alamat lengkap</th>
                            <td><?= esc($gudang['alamat']) ?></td>
                        </tr>
                        <tr>
                            <th>Kota / kabupaten</th>
                            <td><?= esc($gudang['kota']) ?></td>
                        </tr>
                        <tr>
                            <th>Provinsi</th>
                            <td><?= esc($gudang['provinsi']) ?></td>
                        </tr>
                        <tr>
                            <th>Dibuat pada</th>
                            <td style="color:var(--text-2)"><?= date('d F Y, H:i', strtotime($gudang['created_at'] ?? 'now')) ?> WIB</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: Stok gudang -->
        <div class="dg-tab-pane" id="tab-stok">
            <div class="dg-table-wrap">
                <div class="dg-overflow">
                    <table class="dg-data-table datatable" id="tblStok">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama barang</th>
                                <th>Kategori</th>
                                <th class="right">Jumlah stok</th>
                                <th>Update terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stok as $s): ?>
                            <tr>
                                <td><span class="dg-code"><?= esc($s['kode_barang']) ?></span></td>
                                <td style="font-weight:500"><?= esc($s['nama_barang']) ?></td>
                                <td style="color:var(--text-2)"><?= esc($s['nama_kategori'] ?? '—') ?></td>
                                <td class="right">
                                    <span class="dg-pill green">
                                        <?= number_format($s['jumlah'], 0, ',', '.') ?> <?= esc($s['satuan_default']) ?>
                                    </span>
                                </td>
                                <td style="color:var(--text-3);font-size:12px"><?= date('d/m/Y H:i', strtotime($s['updated_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: Barang masuk -->
        <div class="dg-tab-pane" id="tab-masuk">
            <div class="dg-table-wrap">
                <div class="dg-overflow">
                    <table class="dg-data-table datatable" id="tblMasuk">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Donatur</th>
                                <th class="center">Total item</th>
                                <th class="right">Jumlah masuk</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($masuk as $m): ?>
                            <tr>
                                <td style="color:var(--text-2);white-space:nowrap"><?= date('d/m/Y', strtotime($m['tanggal'])) ?></td>
                                <td><span class="dg-code"><?= esc($m['nomor_dokumen']) ?></span></td>
                                <td style="font-weight:500"><?= esc($m['nama_donatur']) ?></td>
                                <td class="center">
                                    <span class="dg-pill cyan"><?= esc($m['total_item']) ?> item</span>
                                </td>
                                <td class="right qty-pos">+ <?= number_format($m['total_qty'], 0, ',', '.') ?></td>
                                <td style="color:var(--text-2)"><?= esc($m['keterangan']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: Barang keluar -->
        <div class="dg-tab-pane" id="tab-keluar">
            <div class="dg-table-wrap">
                <div class="dg-overflow">
                    <table class="dg-data-table datatable" id="tblKeluar">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Tujuan</th>
                                <th class="center">Total item</th>
                                <th class="right">Jumlah keluar</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($keluar as $k): ?>
                            <tr>
                                <td style="color:var(--text-2);white-space:nowrap"><?= date('d/m/Y', strtotime($k['tanggal'])) ?></td>
                                <td><span class="dg-code"><?= esc($k['nomor_dokumen']) ?></span></td>
                                <td style="font-weight:500"><?= esc($k['tujuan']) ?></td>
                                <td class="center">
                                    <span class="dg-pill cyan"><?= esc($k['total_item']) ?> item</span>
                                </td>
                                <td class="right qty-neg">− <?= number_format($k['total_qty'], 0, ',', '.') ?></td>
                                <td style="color:var(--text-2)"><?= esc($k['keterangan']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 5: Laporan -->
        <div class="dg-tab-pane" id="tab-laporan">
            <div class="dg-report-section">
                <div class="dg-report-title">
                    <i class="fa-solid fa-file-export" aria-hidden="true"></i>
                    Export laporan mutasi <?= esc($gudang['nama']) ?>
                </div>
                <p class="dg-report-sub">Pilih jenis mutasi dan rentang tanggal, lalu unduh laporan dalam format PDF atau Excel.</p>
                <form action="<?= site_url('wilayah/laporan/pdf') ?>" method="get" target="_blank" id="laporanForm">
                    <input type="hidden" name="id_gudang" value="<?= esc($gudang['id']) ?>">
                    <div class="dg-form-grid">
                        <div>
                            <label class="dg-form-label" for="laporanJenis">Jenis mutasi</label>
                            <select id="laporanJenis" name="jenis" class="dg-form-select" required>
                                <option value="masuk">Barang masuk</option>
                                <option value="keluar">Barang keluar</option>
                            </select>
                        </div>
                        <div>
                            <label class="dg-form-label" for="laporanStart">Dari tanggal</label>
                            <input id="laporanStart" type="date" name="start_date" class="dg-form-input" value="<?= date('Y-m-01') ?>" required>
                        </div>
                        <div>
                            <label class="dg-form-label" for="laporanEnd">Sampai tanggal</label>
                            <input id="laporanEnd" type="date" name="end_date" class="dg-form-input" value="<?= date('Y-m-t') ?>" required>
                        </div>
                    </div>
                    <div class="dg-export-row">
                        <button type="submit" class="dg-export-btn red">
                            <i class="fa-solid fa-file-pdf" aria-hidden="true"></i> Export PDF
                        </button>
                        <button type="button" class="dg-export-btn green" id="btnExportExcel">
                            <i class="fa-solid fa-file-excel" aria-hidden="true"></i> Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- end .dg-card tabs -->
</div><!-- end .dg-wrap -->


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/* ── Custom tab switching ── */
document.querySelectorAll('.dg-tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.dg-tab-btn').forEach(function(b) {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
        });
        document.querySelectorAll('.dg-tab-pane').forEach(function(p) {
            p.classList.remove('active');
        });
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');

        /* Reinit DataTable on first reveal so column widths compute correctly */
        if (['stok','masuk','keluar'].includes(btn.dataset.tab)) {
            const tbl = document.querySelector('#tab-' + btn.dataset.tab + ' .datatable');
            if (tbl && $.fn.DataTable && !$.fn.DataTable.isDataTable(tbl)) {
                $(tbl).DataTable({
                    dom: '<"dg-dt-toolbar"lf>rt<"dg-dt-footer"ip>',
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                    pageLength: 10,
                    responsive: true
                });
            }
        }
    });
});

/* Init the default (info) tab's datatables aren't needed; init stok lazily on click */
$(document).ready(function() {
    /* Also init stok table immediately since it's the 2nd tab — user may click fast */
});

/* ── Excel export ── */
document.getElementById('btnExportExcel').addEventListener('click', function() {
    const form  = document.getElementById('laporanForm');
    const jenis = form.querySelector('[name=jenis]').value;
    const start = form.querySelector('[name=start_date]').value;
    const end   = form.querySelector('[name=end_date]').value;
    const id    = form.querySelector('[name=id_gudang]').value;
    window.location.href = '<?= site_url('wilayah/laporan/excel') ?>?id_gudang=' + id + '&jenis=' + jenis + '&start_date=' + start + '&end_date=' + end;
});
</script>
<?= $this->endSection() ?>