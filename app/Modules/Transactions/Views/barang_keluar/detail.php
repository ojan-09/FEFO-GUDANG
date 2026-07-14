<?php helper('format'); ?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1 class="page-title mb-1" style="font-size:1.4rem;"><i class="fa-solid fa-file-lines me-2"></i><?= $title ?></h1>
        <span class="subtle" style="font-size:0.85rem;">Detail transaksi pengeluaran barang &middot; metode FEFO</span>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('transaksi/barang-keluar/berita-acara/' . $barangKeluar['id']) ?>" target="_blank" class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-file-pdf me-1"></i> Cetak Berita Acara
        </a>
        <a href="<?= site_url('transaksi/barang-keluar') ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Header Transaksi -->
<div class="panel-card mb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <h5 class="panel-title mb-0" style="font-size:1rem;"><i class="fa-solid fa-receipt me-2"></i>Informasi Transaksi</h5>
        <span class="badge-soft" style="font-size:0.8rem;"><?= esc($barangKeluar['nomor_transaksi']) ?></span>
    </div>

    <div class="row g-2" style="font-size:0.9rem;">
        <div class="col-6 col-md-3">
            <small class="text-muted d-block mb-1" style="font-size:0.75rem;">Tujuan Penyaluran</small>
            <strong><?= esc($barangKeluar['tujuan_penyaluran']) ?></strong>
        </div>
        <div class="col-6 col-md-3">
            <small class="text-muted d-block mb-1" style="font-size:0.75rem;">Wilayah Tujuan</small>
            <strong><?= esc($barangKeluar['nama_wilayah'] ?? '-') ?></strong>
        </div>
        <div class="col-6 col-md-3">
            <small class="text-muted d-block mb-1" style="font-size:0.75rem;">Tanggal Keluar</small>
            <strong><?= date('d M Y', strtotime($barangKeluar['tanggal_keluar'])) ?></strong>
        </div>
        <div class="col-6 col-md-3">
            <small class="text-muted d-block mb-1" style="font-size:0.75rem;">Petugas</small>
            <strong><?= esc($barangKeluar['petugas']) ?></strong>
        </div>
    </div>

    <?php if (!empty($barangKeluar['keterangan'])) : ?>
        <div class="mt-2 p-2" style="background:#f8fafc; border-radius:10px; font-size:0.85rem;">
            <small class="text-muted d-block mb-1"><i class="fa-solid fa-note-sticky me-1"></i>Keterangan</small>
            <span><?= esc($barangKeluar['keterangan']) ?></span>
        </div>
    <?php endif; ?>
</div>

<!-- Ringkasan -->
<div class="row mb-3 g-2">
    <div class="col-md-4">
        <div class="kpi-card text-center p-2" style="border-top: 3px solid #f87171;">
            <i class="fa-solid fa-boxes-stacked text-danger mb-1" style="font-size:0.9rem;"></i>
            <small class="text-muted d-block mb-1" style="font-size:0.75rem;">Total Batch Dipotong</small>
            <strong class="text-danger fs-5"><?= count($details) ?></strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card text-center p-2" style="border-top: 3px solid #f87171;">
            <i class="fa-solid fa-arrow-up-from-bracket text-danger mb-1" style="font-size:0.9rem;"></i>
            <small class="text-muted d-block mb-1" style="font-size:0.75rem;">Total Barang Keluar</small>
            <strong class="text-danger fs-5">
                <?= array_sum(array_column($details, 'jumlah_keluar')) ?> <span class="fs-6">Item</span>
            </strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card text-center p-2" style="border-top: 3px solid #f87171;">
            <i class="fa-solid fa-weight-hanging text-danger mb-1" style="font-size:0.9rem;"></i>
            <small class="text-muted d-block mb-1" style="font-size:0.75rem;">Total Berat Keluar</small>
            <strong class="text-danger fs-5">
                <?php
                      $totalBeratRow = 0;
                      foreach ($details as $d) {
                          $berat = $d['jumlah_keluar'] * $d['berat_per_satuan'];
                          if (strtolower(trim($d['satuan_berat'])) === 'gram') {
                              $totalBeratRow += $berat / 1000;
                          } else {
                              $totalBeratRow += $berat;
                          }
                      }
                      $formattedWeight = format_berat($totalBeratRow, 'Kg');
                    preg_match('/^([\d,\.]+)\s*(.*)$/', $formattedWeight, $matches);
                    if (count($matches) == 3) {
                        echo $matches[1] . ' <span class="fs-6">' . $matches[2] . '</span>';
                    } else {
                        echo $formattedWeight;
                    }
                ?>
            </strong>
        </div>
    </div>
</div>

<!-- Daftar Batch yang Dipotong (Hasil FEFO) -->
<div class="panel-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <h5 class="panel-title mb-0" style="font-size:1rem;"><i class="fa-solid fa-scissors me-2"></i>Batch yang Dipotong oleh FEFO</h5>
        <span class="badge bg-danger rounded-pill px-3" style="font-size:0.75rem;"><?= count($details) ?> Batch</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:0.88rem;">
            <thead>
                <tr style="font-size:0.75rem;">
                    <th width="3%" class="text-muted">No</th>
                    <th width="15%" class="text-muted">No. Batch</th>
                    <th class="text-muted">Nama Barang</th>
                    <th width="15%" class="text-muted">Tgl Kedaluwarsa</th>
                    <th width="12%" class="text-muted">Diambil</th>
                    <th width="12%" class="text-muted">Berat</th>
                    <th width="15%" class="text-muted">Sisa Stok Batch</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $i => $d) : ?>
                    <tr>
                        <td class="text-muted"><?= $i + 1 ?></td>
                        <td class="text-nowrap"><span class="badge-soft" style="font-size:0.8rem;"><?= esc($d['nomor_batch']) ?></span></td>
                        <td><strong><?= esc($d['nama_barang']) ?></strong></td>
                        <td><?= date('d M Y', strtotime($d['tanggal_kedaluwarsa'])) ?></td>
                        <td>
                            <span class="text-danger fw-bold">-<?= $d['jumlah_keluar'] ?></span>
                            <span class="text-muted"><?= esc($d['satuan']) ?></span>
                        </td>
                        <td class="text-muted"><?= format_berat($d['jumlah_keluar'] * $d['berat_per_satuan'], $d['satuan_berat']) ?></td>
                        <td>
                            <?php if ($d['stok_saat_ini'] <= 0) : ?>
                                <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.7rem;">
                                    <i class="fa-solid fa-circle me-1" style="font-size:0.4rem; vertical-align: middle;"></i>Habis
                                </span>
                            <?php else : ?>
                                <strong><?= $d['stok_saat_ini'] ?></strong> <span class="text-muted"><?= esc($d['satuan']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
