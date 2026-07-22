<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php helper('format'); ?>

<style>
    :root {
        --wh-bg: #F8FAFC;
        --wh-card: #FFFFFF;
        --wh-border: #E5E7EB;
        --wh-primary: #2563EB;
        --wh-primary-soft: #EFF6FF;
        --wh-success: #22C55E;
        --wh-success-soft: #ECFDF3;
        --wh-warning: #F59E0B;
        --wh-warning-soft: #FFFBEB;
        --wh-danger: #EF4444;
        --wh-danger-soft: #FEF2F2;
        --wh-dark-soft: #F3F4F6;
        --wh-dark: #374151;
        --wh-text: #111827;
        --wh-text-soft: #6B7280;
    }

    .wh-page {
        background: var(--wh-bg);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 20px 24px 40px 24px;
    }

    /* Header */
    .wh-header {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 18px; padding: 28px;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 20px;
    }
    .wh-header h1 {
        font-size: 1.35rem; font-weight: 700; color: var(--wh-text);
        margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px;
    }
    .wh-header h1 i { color: var(--wh-primary); }
    .wh-header p { margin: 0; font-size: 0.85rem; color: var(--wh-text-soft); }
    .wh-header .wh-updated .lbl {
        font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em;
        color: var(--wh-text-soft); font-weight: 600;
    }
    .wh-header .wh-updated .val {
        font-size: 0.9rem; font-weight: 600; color: var(--wh-text); text-align: right;
    }

    /* Filter */
    .wh-filter-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 20px; margin-bottom: 16px;
    }
    .wh-filter-card .form-label {
        font-size: 0.78rem; font-weight: 600; color: var(--wh-text); margin-bottom: 6px;
    }
    .wh-filter-card .form-control,
    .wh-filter-card .form-select {
        height: 44px; border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.85rem; padding: 0.5rem 0.75rem;
    }
    .wh-filter-card .form-control:focus,
    .wh-filter-card .form-select:focus {
        border-color: var(--wh-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .wh-btn-primary {
        background: var(--wh-primary); border: 1px solid var(--wh-primary); color: #fff;
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:active { transform: scale(0.98); }
    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-outline:active { transform: scale(0.98); }
    .wh-btn-success {
        background: #16A34A; border: 1px solid #16A34A; color: #fff;
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-success:hover { background: #15803D; color: #fff; }
    .wh-btn-success:active { transform: scale(0.98); }

    /* Toolbar */
    .wh-toolbar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 14px;
    }
    .wh-toolbar-count .num { font-size: 1.1rem; font-weight: 700; color: var(--wh-text); }
    .wh-toolbar-count .lbl { font-size: 0.8rem; color: var(--wh-text-soft); margin-left: 6px; }
    .wh-tb-btn {
        height: 40px; border-radius: 10px; border: 1px solid var(--wh-border);
        background: #fff; color: var(--wh-text); font-size: 0.82rem; font-weight: 600;
        padding: 0 14px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-tb-btn:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-tb-btn:active { transform: scale(0.98); }

    /* Table */
    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 8px 8px 4px 8px; overflow: hidden;
    }
    #tabelLaporanExpired { border-collapse: separate; border-spacing: 0; }
    #tabelLaporanExpired thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tabelLaporanExpired tbody td {
        font-size: 0.82rem; padding: 0 14px; height: 56px;
        vertical-align: middle; border-bottom: 1px solid var(--wh-border);
        border-top: none; color: var(--wh-text);
    }
    #tabelLaporanExpired, #tabelLaporanExpired th, #tabelLaporanExpired td {
        border-left: none; border-right: none;
    }
    .wh-table-card { position: relative; min-height: 300px; }
    .wh-table-spinner {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        background: var(--wh-card); z-index: 50;
        color: var(--wh-text-soft); gap: 12px; font-weight: 500;
    }
    .wh-table-spinner i { font-size: 2.2rem; color: var(--wh-primary); }
    .wh-table-card.loaded .wh-table-spinner { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
    .wh-table-card:not(.loaded) { max-height: 400px; overflow: hidden; }
    .wh-table-card:not(.loaded) table { opacity: 0; }
    #tabelLaporanExpired tbody tr { transition: background 120ms ease; }
    #tabelLaporanExpired tbody tr:hover { background: #F3F4F6; }

    /* Badge */
    .wh-badge {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 999px; padding: 4px 10px 4px 8px;
        font-size: 0.7rem; font-weight: 600; white-space: nowrap;
    }
    .wh-badge i { font-size: 0.62rem; }
    .wh-badge.danger  { background: var(--wh-danger-soft); color: #B91C1C; }
    .wh-badge.warning { background: var(--wh-warning-soft); color: #B45309; }
    .wh-badge.success { background: var(--wh-success-soft); color: #15803D; }

    /* Sisa hari */
    .sisa-danger { color: var(--wh-danger); font-weight: 700; }
    .sisa-warning { color: #B45309; font-weight: 700; }

    /* Summary Cards */
    .wh-summary-grid {
        display: grid; grid-template-columns: repeat(5, 1fr);
        gap: 16px; margin-top: 20px;
    }
    .wh-summary-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 18px 20px; min-height: 110px;
        display: flex; flex-direction: column; justify-content: space-between;
        box-shadow: 0 1px 2px rgba(16,24,40,0.04);
        transition: transform 180ms ease, box-shadow 180ms ease;
    }
    .wh-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16,24,40,0.08);
    }
    .wh-kpi-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 0.95rem;
    }
    .wh-kpi-icon.blue   { background: var(--wh-primary-soft); color: var(--wh-primary); }
    .wh-kpi-icon.green  { background: var(--wh-success-soft); color: #15803D; }
    .wh-kpi-icon.teal   { background: #ECFEFF; color: #0891B2; }
    .wh-kpi-icon.red    { background: var(--wh-danger-soft); color: #B91C1C; }
    .wh-kpi-icon.amber  { background: var(--wh-warning-soft); color: #B45309; }
    .wh-summary-card .s-value {
        font-size: 1.5rem; font-weight: 700; color: var(--wh-text); line-height: 1.2;
    }
    .wh-summary-card .s-label {
        font-size: 0.78rem; color: var(--wh-text-soft); font-weight: 500; margin-top: 2px;
    }

    /* DataTables overrides */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.82rem; color: var(--wh-text-soft); padding: 10px 6px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 10px; border: 1px solid var(--wh-border);
        padding: 0.4rem 0.65rem; font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.82rem; padding: 0.3rem 1.75rem 0.3rem 0.6rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important; padding: 0.35rem 0.7rem !important;
        margin-left: 2px; border: 1px solid transparent !important;
        background: transparent !important; color: var(--wh-text) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--wh-primary) !important; color: #fff !important;
        border-color: var(--wh-primary) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: var(--wh-dark-soft) !important; color: var(--wh-text) !important;
    }

    @media (max-width: 1024px) { .wh-summary-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .wh-summary-grid { grid-template-columns: 1fr; } .wh-header { flex-direction: column; align-items: flex-start; } .wh-header .wh-updated .val { text-align: left; } }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-file-circle-exclamation"></i><?= esc($title) ?></h1>
            <p>Laporan audit untuk monitor barang yang telah atau akan kedaluwarsa</p>
        </div>
        <div class="wh-updated">
            <div class="lbl">Update Terakhir</div>
            <div class="val"><?= date('d F Y') ?><br><?= date('H:i') ?> WIB</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="wh-filter-card">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="filterStatus" class="form-label">Status Kedaluwarsa</label>
                <select id="filterStatus" name="status" class="form-select">
                    <option value="Semua" <?= ($filters['status'] == 'Semua') ? 'selected' : '' ?>>Semua</option>
                    <option value="Akan Expired (<= 30 Hari)" <?= ($filters['status'] == 'Akan Expired (<= 30 Hari)') ? 'selected' : '' ?>>Akan Expired (≤30 Hari)</option>
                    <option value="Akan Expired (<= 60 Hari)" <?= ($filters['status'] == 'Akan Expired (<= 60 Hari)') ? 'selected' : '' ?>>Akan Expired (≤60 Hari)</option>
                    <option value="Akan Expired (<= 90 Hari)" <?= ($filters['status'] == 'Akan Expired (<= 90 Hari)') ? 'selected' : '' ?>>Akan Expired (≤90 Hari)</option>
                    <option value="Sudah Expired" <?= ($filters['status'] == 'Sudah Expired') ? 'selected' : '' ?>>Sudah Expired</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filterSearch" class="form-label">Nama Barang</label>
                <input type="text" id="filterSearch" name="search" class="form-control" placeholder="Cari nama barang..." value="<?= esc($filters['search']) ?>">
            </div>
            <div class="col-md-3">
                <label for="filterDonatur" class="form-label">Donatur</label>
                <input type="text" id="filterDonatur" name="donatur" class="form-control" placeholder="Cari donatur..." value="<?= esc($filters['donatur']) ?>">
            </div>
            <div class="col-md-3">
                <label for="filterKategori" class="form-label">Kategori</label>
                <select id="filterKategori" name="kategori" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= esc($k['nama_kategori']) ?>" <?= ($filters['kategori'] == $k['nama_kategori']) ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 d-flex gap-2 flex-wrap">
                <button type="submit" class="wh-btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter</button>
                <?php if ($filters['status'] != 'Semua' || !empty($filters['search']) || !empty($filters['donatur']) || !empty($filters['kategori'])): ?>
                    <a href="<?= site_url('laporan/expired') ?>" class="wh-btn-outline"><i class="fa-solid fa-arrow-rotate-left"></i> Reset</a>
                <?php endif; ?>
                <div style="margin-left: auto; display: flex; gap: 8px;">
                    <a href="<?= site_url('laporan/expired/pdf') ?>?<?= http_build_query($filters) ?>" target="_blank" class="wh-tb-btn btn-export-loading" data-loading-text="Membuat PDF..."><i class="fa-solid fa-file-pdf"></i> Export PDF</a>
                    <a href="<?= site_url('laporan/expired/excel') ?>?<?= http_build_query($filters) ?>" target="_blank" class="wh-btn-success btn-export-loading" data-loading-text="Membuat Excel..."><i class="fa-solid fa-file-excel"></i> Export Excel</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Toolbar -->
    <div class="wh-toolbar">
        <div class="wh-toolbar-count">
            <span class="num"><?= number_format(count($laporan), 0, ',', '.') ?></span>
            <span class="lbl">Total Data</span>
        </div>
    </div>

    <!-- Table -->
    <div class="wh-table-card">
        <div class="wh-table-spinner">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <div>Memuat data laporan...</div>
        </div>
        <table class="table table-hover align-middle mb-0" id="tabelLaporanExpired" style="width:100%;">
                <thead>
                    <tr>
                        <th width="30" class="text-center">No</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tgl Kedaluwarsa</th>
                        <th class="text-center">Sisa Hari</th>
                        <th style="min-width: 150px;">Donatur</th>
                        <th style="min-width: 150px;">Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">CTN</th>
                        <th class="text-end">Berat Bersih</th>
                        <th class="text-end">Total Berat</th>
                        <th style="min-width: 150px;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan)): ?>
                        <tr>
                            <td colspan="13" style="text-align:center; padding: 60px 20px;">
                                <i class="fa-solid fa-box-open" style="font-size: 2.5rem; color: var(--wh-border);"></i>
                                <p style="margin-top: 14px; color: var(--wh-text); font-weight: 600;">Data tidak ditemukan.</p>
                                <p style="color: var(--wh-text-soft); font-size: 0.85rem;">Coba ubah filter pencarian Anda.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($laporan as $item) : ?>
                            <?php
                                $bisaDipecah    = (int) ($item['bisa_dipecah'] ?? 0);
                                $beratPerSatuan = (float) $item['berat_per_satuan'];
                                if ($bisaDipecah === 1) {
                                    $totalBeratRow = (float) $item['jumlah'];
                                    if (strtolower($item['satuan_berat']) === 'gram') {
                                        $totalBeratRow *= 1000;
                                    }
                                } else {
                                    $totalBeratRow  = $item['jumlah'] * $beratPerSatuan;
                                }

                                // Badge (identik dengan logika asli, hanya ganti render)
                                if (strpos($item['status_label'], '🔴') !== false) {
                                    $badgeClass = 'danger'; $badgeIcon = 'fa-circle-exclamation';
                                } elseif (strpos($item['status_label'], '🟡') !== false) {
                                    $badgeClass = 'warning'; $badgeIcon = 'fa-triangle-exclamation';
                                } else {
                                    $badgeClass = 'success'; $badgeIcon = 'fa-circle-check';
                                }
                                $badgeLabel = preg_replace('/^(🔴|🟡|🟢)\s*/', '', $item['status_label']);

                                // Sisa hari class
                                $sisaClass = '';
                                if ($item['sisa_hari'] !== null) {
                                    if ($item['sisa_hari'] < 0)      $sisaClass = 'sisa-danger';
                                    elseif ($item['sisa_hari'] <= 30) $sisaClass = 'sisa-warning';
                                }
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>
                                    <span class="wh-badge <?= $badgeClass ?>">
                                        <i class="fa-solid <?= $badgeIcon ?>"></i><?= esc($badgeLabel) ?>
                                    </span>
                                </td>
                                <td class="text-center" style="font-weight:500; white-space:nowrap;">
                                    <?= $item['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($item['tanggal_kedaluwarsa'])) : '-' ?>
                                </td>
                                <td class="text-center <?= $sisaClass ?>">
                                    <?= $item['sisa_hari'] !== null ? $item['sisa_hari'] . ' Hari' : '-' ?>
                                </td>
                                <td><?= esc($item['nama_donatur'] ?? '-') ?></td>
                                <td><strong><?= esc($item['nama_barang']) ?></strong></td>
                                <td><?= esc($item['kategori_batch']) ?></td>
                                <td class="text-center" style="font-weight:700;"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                                <td class="text-center"><?= esc($item['satuan']) ?></td>
                                <td class="text-center"><?= !empty($item['jumlah_ctn']) ? $item['jumlah_ctn'] : '-' ?></td>
                                <td class="text-end"><?= $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-' ?></td>
                                <td class="text-end" style="font-weight:500;"><?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-' ?></td>
                                <td class="text-muted"><small><?= esc($item['keterangan'] ?? '-') ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>


    <!-- Summary Cards -->
    <div class="wh-summary-grid">
        <div class="wh-summary-card">
            <div class="wh-kpi-icon blue"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <div class="s-value"><?= number_format($summary['total_batch'], 0, ',', '.') ?></div>
                <div class="s-label">Total Batch</div>
            </div>
        </div>
        <div class="wh-summary-card">
            <div class="wh-kpi-icon green"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div>
                <div class="s-value"><?= number_format($summary['total_barang'], 0, ',', '.') ?></div>
                <div class="s-label">Total Barang</div>
            </div>
        </div>
        <div class="wh-summary-card">
            <div class="wh-kpi-icon teal"><i class="fa-solid fa-weight-hanging"></i></div>
            <div>
                <div class="s-value"><?= format_berat($summary['total_berat'], 'Kg') ?></div>
                <div class="s-label">Total Berat</div>
            </div>
        </div>
        <div class="wh-summary-card">
            <div class="wh-kpi-icon red"><i class="fa-solid fa-ban"></i></div>
            <div>
                <div class="s-value"><?= number_format($summary['total_expired'], 0, ',', '.') ?></div>
                <div class="s-label">Barang Expired</div>
            </div>
        </div>
        <div class="wh-summary-card">
            <div class="wh-kpi-icon amber"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <div class="s-value"><?= number_format($summary['total_hampir_expired'], 0, ',', '.') ?></div>
                <div class="s-label">Hampir Expired</div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#tabelLaporanExpired').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        "order": [],
        "columnDefs": [
            { "orderable": false, "targets": [0] }
        ],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        initComplete: function() {
            $('#tabelLaporanExpired').closest('.wh-table-card').addClass('loaded');
        }
    });
});
</script>
<?= $this->endSection() ?>