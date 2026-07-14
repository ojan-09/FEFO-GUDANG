<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-lines me-2"></i><?= $title ?></h1>
        <span class="subtle">Detail transaksi donasi masuk</span>
    </div>
    <a href="<?= site_url('transaksi/barang-masuk') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Header Transaksi -->
<div class="panel-card mb-3">
    <h5 class="panel-title mb-3"><i class="fa-solid fa-receipt me-2"></i>Informasi Transaksi</h5>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-borderless mb-0">
                <tr>
                    <td class="text-muted" width="180">No. Transaksi</td>
                    <td><strong><span class="badge-soft"><?= esc($barangMasuk['nomor_transaksi']) ?></span></strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Donatur</td>
                    <td><strong><?= esc($barangMasuk['nama_donatur']) ?></strong></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-borderless mb-0">
                <tr>
                    <td class="text-muted" width="180">Tanggal Masuk</td>
                    <td><?= date('d M Y', strtotime($barangMasuk['tanggal_masuk'])) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">ETA</td>
                    <td><?= $barangMasuk['eta'] ? date('d M Y', strtotime($barangMasuk['eta'])) : '-' ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Petugas</td>
                    <td><?= esc($barangMasuk['petugas']) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php if (!empty($barangMasuk['keterangan'])) : ?>
        <div class="mt-2 p-3" style="background:#f8fafc; border-radius:12px;">
            <small class="text-muted d-block mb-1">Keterangan:</small>
            <?= esc($barangMasuk['keterangan']) ?>
        </div>
    <?php endif; ?>
</div>

<!-- Daftar Batch -->
<div class="panel-card">
    <h5 class="panel-title mb-3"><i class="fa-solid fa-boxes-stacked me-2"></i>Daftar Batch (<?= count($batches) ?> item)</h5>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>No. Batch</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Berat Total</th>
                    <th>Stok Saat Ini</th>
                    <th>Tgl Kedaluwarsa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($batches as $i => $b) : ?>
                    <?php
                        $beratTotal = $b['jumlah_awal'] * $b['berat_per_satuan'];
                        $statusClass = match($b['status']) {
                            'Aktif'   => 'bg-success',
                            'Habis'   => 'bg-secondary',
                            'Expired' => 'bg-danger',
                            default   => 'bg-secondary',
                        };
                    ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><span class="badge-soft"><?= esc($b['nomor_batch']) ?></span></td>
                        <td><strong><?= esc($b['nama_barang']) ?></strong></td>
                        <td><?= esc($b['kategori'] ?? '-') ?></td>
                        <td><?= $b['jumlah_awal'] ?> <?= esc($b['satuan']) ?></td>
                        <td><?= $beratTotal ?> <?= esc($b['satuan_berat']) ?></td>
                        <td>
                            <?php if ($b['stok_saat_ini'] < $b['jumlah_awal']) : ?>
                                <span class="text-warning fw-bold"><?= $b['stok_saat_ini'] ?></span> / <?= $b['jumlah_awal'] ?>
                            <?php else : ?>
                                <?= $b['stok_saat_ini'] ?>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($b['tanggal_kedaluwarsa'])) ?></td>
                        <td><span class="badge <?= $statusClass ?> rounded-pill"><?= $b['status'] ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

