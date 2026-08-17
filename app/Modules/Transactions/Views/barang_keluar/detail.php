<?php helper('format'); ?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    /* ── Topbar ── */
    .topbar { padding: 12px 18px; }
    .topbar .page-title { font-size: 1.15rem; font-weight: 600; color: #1e293b; }
    .topbar .subtle { font-size: 0.8rem; color: #94a3b8; }
    .topbar .btn { font-size: 14px; padding: 0.45rem 1.25rem; height: 42px; display: inline-flex; align-items: center; }

    /* ── Panel card ── */
    .panel-card { padding: 16px 18px; }
    .panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #475569;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        margin-bottom: 18px !important;
    }

    /* ── Badge nomor transaksi ── */
    .badge-soft {
        font-size: 13px;
        color: #3b82f6;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: 3px 12px;
        border-radius: 18px;
        font-weight: 500;
    }

    /* ── Info transaksi ── */
    .info-item small { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; }
    .info-item strong { font-size: 0.88rem; color: #1e293b; font-weight: 600; }

    /* ── KPI cards ── */
    .kpi-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        border-top: 2px solid #f87171;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 14px 16px;
        min-height: 90px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #fef2f2;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .kpi-icon i {
        font-size: 1.1rem;
        color: #dc2626;
    }
    .kpi-body {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .kpi-label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 400;
    }
    .kpi-value {
        font-size: 1.35rem;
        font-weight: 600;
        color: #dc2626;
        line-height: 1.2;
    }
    .kpi-value .kpi-unit {
        font-size: 0.8rem;
        color: #ef4444;
        font-weight: 400;
        margin-left: 2px;
    }
    .kpi-sub {
        font-size: 0.82rem;
        color: #ef4444;
        line-height: 1.3;
    }
    .kpi-sub .kpi-unit {
        font-size: 0.72rem;
        color: #f87171;
        font-weight: 400;
        margin-left: 2px;
    }

    /* ── Tabel batch keluar ── */
    #tabelBatchKeluar thead th {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 0 0.6rem;
        height: 46px;
        vertical-align: middle;
        white-space: nowrap;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    #tabelBatchKeluar tbody td {
        font-size: 14px;
        padding: 10px 0.6rem;
        height: 48px;
        vertical-align: middle;
        color: #334155;
        border-color: #f1f5f9;
    }
    #tabelBatchKeluar tbody tr:hover td { background: #f8fafc; }
    #tabelBatchKeluar .badge-soft { font-size: 0.78rem; }

    /* Lebar kolom proporsional */
    #tabelBatchKeluar th:nth-child(1),
    #tabelBatchKeluar td:nth-child(1) { width: 45px; }
    #tabelBatchKeluar th:nth-child(2),
    #tabelBatchKeluar td:nth-child(2) { width: 110px; }
    #tabelBatchKeluar th:nth-child(3),
    #tabelBatchKeluar td:nth-child(3) { width: 28%; }
    #tabelBatchKeluar th:nth-child(4),
    #tabelBatchKeluar td:nth-child(4) { width: 130px; }
    #tabelBatchKeluar th:nth-child(5),
    #tabelBatchKeluar td:nth-child(5) { width: 120px; text-align: right; }
    #tabelBatchKeluar th:nth-child(6),
    #tabelBatchKeluar td:nth-child(6) { width: 110px; text-align: right; }
    #tabelBatchKeluar th:nth-child(7),
    #tabelBatchKeluar td:nth-child(7) { width: 120px; text-align: right; }
    #tabelBatchKeluar th:nth-child(5),
    #tabelBatchKeluar th:nth-child(6),
    #tabelBatchKeluar th:nth-child(7) { text-align: right; }

    /* Badge habis */
    #tabelBatchKeluar .badge.bg-danger {
        font-size: 12px;
        padding: 0.3em 12px;
        background: #fef2f2 !important;
        color: #dc2626 !important;
        border: 1px solid #fecaca;
        font-weight: 500;
    }

    /* Badge jumlah batch */
    .badge-batch-count {
        font-size: 12px;
        padding: 0.3em 12px;
        font-weight: 500;
    }

    /* Keterangan box */
    .keterangan-box {
        background: #f8fafc;
        border-left: 3px solid #e2e8f0;
        border-radius: 0 6px 6px 0;
        padding: 8px 12px;
        font-size: 0.82rem;
        color: #475569;
    }
    .keterangan-box small { font-size: 0.68rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; }
</style>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-lines me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Detail transaksi pengeluaran barang &middot; metode FEFO</span>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('transaksi/barang-keluar/berita-acara/' . $barangKeluar['id']) ?>" target="_blank" class="btn btn-danger rounded-pill px-3">
            <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
        </a>
        <a href="<?= site_url('transaksi/barang-keluar/berita-acara-word/' . $barangKeluar['id']) ?>" class="btn btn-primary rounded-pill px-3">
            <i class="fa-solid fa-file-word me-1"></i> Export Word (.doc)
        </a>
        <a href="<?= site_url('transaksi/barang-keluar') ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Header Transaksi -->
<div class="panel-card mb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <h5 class="panel-title mb-0"><i class="fa-solid fa-receipt me-2"></i>Informasi Transaksi</h5>
        <span class="badge-soft"><?= esc($barangKeluar['nomor_transaksi']) ?></span>
    </div>

    <div class="row g-3">
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Jenis Penyaluran</small>
            <?php if (($barangKeluar['jenis_penyaluran'] ?? '') === 'Penyaluran Internal'): ?>
                <span class="badge bg-purple-subtle text-purple border border-purple-subtle rounded-pill px-2 py-1 small" style="background:#F3E8FF; color:#7E22CE;">Penyaluran Internal</span>
            <?php else: ?>
                <span class="badge bg-blue-subtle text-blue border border-blue-subtle rounded-pill px-2 py-1 small" style="background:#EFF6FF; color:#1D4ED8;">Penyaluran Relawan</span>
            <?php endif; ?>
        </div>
        <?php if (!empty($barangKeluar['penerima_relawan'])): ?>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Nama Relawan / Penerima</small>
            <strong><?= esc($barangKeluar['penerima_relawan']) ?></strong>
        </div>
        <?php endif; ?>
        <?php if (!empty($barangKeluar['unit_internal'])): ?>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Unit / Bagian Internal</small>
            <strong><?= esc($barangKeluar['unit_internal']) ?></strong>
        </div>
        <?php endif; ?>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Tujuan Penyaluran</small>
            <strong><?= esc($barangKeluar['tujuan_penyaluran']) ?></strong>
        </div>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Wilayah Tujuan</small>
            <strong><?= esc($barangKeluar['nama_wilayah'] ?? '-') ?></strong>
        </div>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Tanggal Keluar</small>
            <strong><?= date('d M Y', strtotime($barangKeluar['tanggal_keluar'])) ?></strong>
        </div>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Petugas</small>
            <strong><?= esc($barangKeluar['petugas']) ?></strong>
        </div>
    </div>

    <?php if (!empty($barangKeluar['keterangan'])) : ?>
        <div class="keterangan-box mt-3">
            <small class="d-block mb-1"><i class="fa-solid fa-note-sticky me-1"></i>Keterangan</small>
            <?= esc($barangKeluar['keterangan']) ?>
        </div>
    <?php endif; ?>
</div>

<?php
    $totalItemPerSatuan = [];
    $totalKgRepack = 0;
    $totalBeratKeseluruhan = 0;

    foreach ($details as $d) {
        $bisaDipecah = (int)($d['bisa_dipecah'] ?? 0);
        $beratPerSatuan = (float) $d['berat_per_satuan'];

        if ($bisaDipecah === 1) {
            $totalKgRepack += (float) $d['jumlah_keluar'];
            $beratBaris = (float) $d['jumlah_keluar'];
        } else {
            $satuan = $d['satuan'] ?: 'Pcs';
            $totalItemPerSatuan[$satuan] = ($totalItemPerSatuan[$satuan] ?? 0) + (float) $d['jumlah_keluar'];
            $beratBaris = $d['jumlah_keluar'] * $beratPerSatuan;
            if (in_array(strtolower(trim($d['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                $beratBaris = $beratBaris / 1000;
            }
        }
        $totalBeratKeseluruhan += $beratBaris;
    }
?>

<!-- Ringkasan KPI -->
<div class="row mb-3 g-2">
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-icon">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="kpi-body">
                <span class="kpi-label">Total Batch Dipotong</span>
                <span class="kpi-value"><?= count($details) ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-icon">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
            </div>
            <div class="kpi-body">
                <span class="kpi-label">Total Barang Keluar</span>
                <span class="kpi-value">
                    <?php
                        $kpiParts = [];
                        foreach ($totalItemPerSatuan as $satuan => $jml) {
                            $kpiParts[] = ['val' => number_format($jml, 0, ',', '.'), 'unit' => esc($satuan)];
                        }
                        if ($totalKgRepack > 0) {
                            $kpiParts[] = ['val' => number_format($totalKgRepack, 2, ',', '.'), 'unit' => 'Kg'];
                        }
                        if (!empty($kpiParts)) {
                            $first = array_shift($kpiParts);
                            echo $first['val'] . ' <span class="kpi-unit">' . $first['unit'] . '</span>';
                            foreach ($kpiParts as $part) {
                                echo '<br><span class="kpi-sub">' . $part['val'] . ' <span class="kpi-unit">' . $part['unit'] . '</span></span>';
                            }
                        } else {
                            echo '0';
                        }
                    ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-icon">
                <i class="fa-solid fa-weight-hanging"></i>
            </div>
            <div class="kpi-body">
                <span class="kpi-label">Total Berat Keluar</span>
                <span class="kpi-value">
                    <?php
                        $formattedWeight = format_berat($totalBeratKeseluruhan, 'Kg');
                        preg_match('/^([\d,\.]+)\s*(.*)$/', $formattedWeight, $matches);
                        if (count($matches) == 3) {
                            echo $matches[1] . ' <span class="kpi-unit">' . $matches[2] . '</span>';
                        } else {
                            echo $formattedWeight;
                        }
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Batch yang Dipotong (Hasil FEFO) -->
<div class="panel-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="panel-title mb-0"><i class="fa-solid fa-scissors me-2"></i>Batch yang Dipotong oleh FEFO</h5>
        <span class="badge bg-danger rounded-pill badge-batch-count"><?= count($details) ?> Batch</span>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle mb-0" id="tabelBatchKeluar">
            <thead class="table-light">
                <tr>
                    <th width="45" class="text-center">No</th>
                    <th class="text-nowrap">No. Batch</th>
                    <th class="text-nowrap">Nama Barang</th>
                    <th class="text-center text-nowrap">Tgl Kedaluwarsa</th>
                    <th class="text-nowrap">Diambil</th>
                    <th class="text-nowrap">Berat</th>
                    <th class="text-nowrap">Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $i => $d) : ?>
                    <?php
                        $bisaDipecah = (int)($d['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float) $d['berat_per_satuan'];

                        if ($bisaDipecah === 1) {
                            $beratBarisDisplay = format_berat((float) $d['jumlah_keluar'], 'Kg');
                            $jumlahDisplay = format_jumlah($d['jumlah_keluar']);
                            $stokDisplay = format_jumlah($d['stok_saat_ini']);
                        } else {
                            $beratBaris = $d['jumlah_keluar'] * $beratPerSatuan;
                            $beratBarisDisplay = format_berat($beratBaris, $d['satuan_berat']);
                            $jumlahDisplay = format_jumlah($d['jumlah_keluar']);
                            $stokDisplay = format_jumlah($d['stok_saat_ini']);
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-nowrap"><span class="badge-soft"><?= esc($d['nomor_batch']) ?></span></td>
                        <td class="fw-medium"><?= esc($d['nama_barang']) ?></td>
                        <td class="text-center text-nowrap"><?= date('d M Y', strtotime($d['tanggal_kedaluwarsa'])) ?></td>
                        <td class="text-nowrap">
                            <span class="text-danger fw-bold">-<?= $jumlahDisplay ?></span>
                            <span class="text-muted"><?= esc($d['satuan']) ?></span>
                        </td>
                        <td class="text-nowrap text-muted"><?= $beratBarisDisplay ?></td>
                        <td class="text-nowrap">
                            <?php if ($d['stok_saat_ini'] <= 0) : ?>
                                <span class="badge bg-danger rounded-pill px-2">
                                    <i class="fa-solid fa-circle me-1" style="font-size:0.4rem; vertical-align: middle;"></i>Habis
                                </span>
                            <?php else : ?>
                                <strong><?= $stokDisplay ?></strong> <span class="text-muted"><?= esc($d['satuan']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>