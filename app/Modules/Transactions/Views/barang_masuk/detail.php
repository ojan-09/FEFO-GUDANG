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

    /* ── Info item (konsisten dengan detail keluar) ── */
    .info-item small { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; }
    .info-item strong { font-size: 0.88rem; color: #1e293b; font-weight: 600; }

    /* ── Keterangan box ── */
    .keterangan-box {
        background: #f8fafc;
        border-left: 3px solid #e2e8f0;
        border-radius: 0 6px 6px 0;
        padding: 8px 12px;
        font-size: 0.82rem;
        color: #475569;
    }
    .keterangan-box small { font-size: 0.68rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; }

    /* ── Tabel batch ── */
    #tabelBatch thead th {
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
    #tabelBatch tbody td {
        font-size: 14px;
        padding: 10px 0.6rem;
        height: 48px;
        vertical-align: middle;
        color: #334155;
        border-color: #f1f5f9;
    }
    #tabelBatch tbody tr:hover td { background: #f8fafc; }
    #tabelBatch .badge:not(.badge-soft) {
        font-size: 12px;
        padding: 0.3em 12px;
        font-weight: 500;
    }

    /* Lebar kolom proporsional */
    #tabelBatch th:nth-child(1), #tabelBatch td:nth-child(1) { width: 45px; }
    #tabelBatch th:nth-child(2), #tabelBatch td:nth-child(2) { width: 110px; }
    #tabelBatch th:nth-child(3), #tabelBatch td:nth-child(3) { width: 22%; }
    #tabelBatch th:nth-child(4), #tabelBatch td:nth-child(4) { width: 12%; }
    #tabelBatch th:nth-child(5), #tabelBatch td:nth-child(5) { width: 110px; text-align: right; }
    #tabelBatch th:nth-child(6), #tabelBatch td:nth-child(6) { width: 110px; text-align: right; }
    #tabelBatch th:nth-child(7), #tabelBatch td:nth-child(7) { width: 110px; text-align: right; }
    #tabelBatch th:nth-child(8), #tabelBatch td:nth-child(8) { width: 120px; }
    #tabelBatch th:nth-child(9), #tabelBatch td:nth-child(9) { width: 90px; }
    #tabelBatch th:nth-child(5),
    #tabelBatch th:nth-child(6),
    #tabelBatch th:nth-child(7) { text-align: right; }

    /* Badge jumlah item */
    .badge-batch-count { font-size: 12px; padding: 0.3em 12px; font-weight: 500; }
</style>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-lines me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Detail transaksi donasi masuk</span>
    </div>
    <a href="<?= site_url('transaksi/barang-masuk') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Header Transaksi -->
<div class="panel-card mb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <h5 class="panel-title mb-0"><i class="fa-solid fa-receipt me-2"></i>Informasi Transaksi</h5>
        <span class="badge-soft"><?= esc($barangMasuk['nomor_transaksi']) ?></span>
    </div>

    <div class="row g-3">
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Donatur</small>
            <strong><?= esc($barangMasuk['nama_donatur']) ?></strong>
        </div>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Tanggal Masuk</small>
            <strong><?= date('d M Y', strtotime($barangMasuk['tanggal_masuk'])) ?></strong>
        </div>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">ETA</small>
            <strong><?= $barangMasuk['eta'] ? date('d M Y', strtotime($barangMasuk['eta'])) : '-' ?></strong>
        </div>
        <div class="col-6 col-md-3 info-item">
            <small class="text-muted d-block mb-1">Petugas</small>
            <strong><?= esc($barangMasuk['petugas']) ?></strong>
        </div>
    </div>

    <?php if (!empty($barangMasuk['keterangan'])) : ?>
        <div class="keterangan-box mt-3">
            <small class="d-block mb-1"><i class="fa-solid fa-note-sticky me-1"></i>Keterangan</small>
            <?= esc($barangMasuk['keterangan']) ?>
        </div>
    <?php endif; ?>
</div>

<!-- Daftar Batch -->
<div class="panel-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="panel-title mb-0"><i class="fa-solid fa-boxes-stacked me-2"></i>Daftar Batch</h5>
        <span class="badge bg-primary rounded-pill badge-batch-count"><?= count($batches) ?> Item</span>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle mb-0" id="tabelBatch">
            <thead class="table-light">
                <tr>
                    <th width="45" class="text-center">No</th>
                    <th class="text-nowrap">No. Batch</th>
                    <th class="text-nowrap">Nama Barang</th>
                    <th class="text-nowrap">Kategori</th>
                    <th class="text-nowrap">Jumlah</th>
                    <th class="text-nowrap">Berat Total</th>
                    <th class="text-nowrap">Stok Saat Ini</th>
                    <th class="text-center text-nowrap">Tgl Kedaluwarsa</th>
                    <th class="text-center text-nowrap">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php helper('format'); ?>
                <?php foreach ($batches as $i => $b) : ?>
                    <?php
                        $bisaDipecah = (int)($b['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float) $b['berat_per_satuan'];

                        if ($bisaDipecah === 1) {
                            $beratTotal = (float) $b['jumlah_awal'];
                            $satuanJumlah = 'Kg';
                            $jumlahDisplay = number_format($b['jumlah_awal'], 2, ',', '.');
                            $stokDisplay = number_format($b['stok_saat_ini'], 2, ',', '.');
                        } else {
                            $beratTotal = $b['jumlah_awal'] * $beratPerSatuan;
                            if (in_array(strtolower(trim($b['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                                $beratTotal = $beratTotal / 1000;
                            }
                            $satuanJumlah = esc($b['satuan']);
                            $jumlahDisplay = number_format($b['jumlah_awal'], 0, ',', '.');
                            $stokDisplay = number_format($b['stok_saat_ini'], 0, ',', '.');
                        }

                        $statusClass = match($b['status']) {
                            'Aktif'   => 'bg-success',
                            'Habis'   => 'bg-secondary',
                            'Expired' => 'bg-danger',
                            default   => 'bg-secondary',
                        };
                    ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-nowrap"><span class="badge-soft"><?= esc($b['nomor_batch']) ?></span></td>
                        <td class="text-nowrap fw-medium"><?= esc($b['nama_barang']) ?></td>
                        <td class="text-nowrap"><?= esc($b['kategori'] ?? '-') ?></td>
                        <td class="text-nowrap fw-bold"><?= $jumlahDisplay ?> <?= $satuanJumlah ?></td>
                        <td class="text-nowrap"><?= $beratTotal > 0 ? format_berat($beratTotal, 'Kg') : '-' ?></td>
                        <td class="text-nowrap">
                            <?php if ($b['stok_saat_ini'] < $b['jumlah_awal']) : ?>
                                <span class="text-warning fw-bold"><?= $stokDisplay ?></span> / <?= $jumlahDisplay ?>
                            <?php else : ?>
                                <?= $stokDisplay ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center text-nowrap"><?= date('d M Y', strtotime($b['tanggal_kedaluwarsa'])) ?></td>
                        <td class="text-center"><span class="badge <?= $statusClass ?> rounded-pill"><?= esc($b['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>