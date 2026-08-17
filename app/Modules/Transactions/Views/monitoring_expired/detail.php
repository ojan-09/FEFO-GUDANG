<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= site_url('transaksi/monitoring-expired') ?>" class="btn btn-light border mb-3">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
    </a>
    <h1 class="page-title"><?= esc($title) ?></h1>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="panel-card">
            <h5 class="panel-title mb-4">Informasi Batch</h5>
            <table class="table table-borderless table-sm">
                <tr>
                    <td class="text-muted" width="150">Nomor Batch</td>
                    <td class="fw-bold"><?= esc($batch['nomor_batch']) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Nama Barang</td>
                    <td><?= esc($batch['nama_barang']) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Masuk</td>
                    <td><?= date('d M Y', strtotime($batch['tanggal_masuk'])) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Kedaluwarsa</td>
                    <td class="text-danger fw-bold"><?= date('d M Y', strtotime($batch['tanggal_kedaluwarsa'])) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Donatur</td>
                    <td><?= esc($batch['nama_donatur'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td><?= esc($batch['status']) ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel-card h-100">
            <h5 class="panel-title mb-4">Informasi Stok</h5>
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white p-3 rounded-3 me-3 text-center" style="width: 100px;">
                    <div class="small opacity-75">Stok Awal</div>
                    <div class="fs-4 fw-bold"><?= (float)$batch['jumlah_awal'] ?></div>
                </div>
                <div class="bg-warning text-dark p-3 rounded-3 text-center" style="width: 100px;">
                    <div class="small opacity-75">Sisa Stok</div>
                    <div class="fs-4 fw-bold"><?= (float)$batch['stok_saat_ini'] ?></div>
                </div>
            </div>
            <div class="text-muted small">Satuan: <?= esc($batch['satuan']) ?></div>
            
            <?php if (in_groups('Administrator') && (float)$batch['stok_saat_ini'] > 0): ?>
            <?php 
                $diff = (int)(new \DateTime(date('Y-m-d')))->diff(new \DateTime($batch['tanggal_kedaluwarsa']))->format('%R%a');
            ?>
            <div class="mt-4 pt-3 border-top">
                <p class="small text-muted mb-2">Aksi Cepat:</p>
                <?php if ($diff < 0): ?>
                    <button class="btn btn-outline-danger" disabled title="Barang expired tidak boleh disalurkan"><i class="fa-solid fa-share-from-square me-1"></i> Gunakan Batch (Expired)</button>
                <?php else: ?>
                    <a href="<?= site_url('transaksi/barang-keluar/create?id_batch='.$batch['id']) ?>" class="btn btn-primary"><i class="fa-solid fa-share-from-square me-1"></i> Gunakan Batch Ini</a>
                <?php endif; ?>
                <a href="<?= site_url('transaksi/barang-masuk/detail/'.$batch['id_barang_masuk']) ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-truck-ramp-box me-1"></i> Lihat Barang Masuk</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="panel-card mt-4">
    <h5 class="panel-title mb-3">Riwayat Pengeluaran Batch Ini</h5>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tanggal Keluar</th>
                    <th>Tujuan</th>
                    <th>Jumlah Keluar</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($riwayatKeluar)): ?>
                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada riwayat pengeluaran untuk batch ini.</td></tr>
                <?php else: ?>
                    <?php foreach($riwayatKeluar as $row): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($row['tanggal_keluar'])) ?></td>
                        <td><?= esc($row['tujuan']) ?></td>
                        <td><?= (float)$row['jumlah_keluar'] ?> <?= esc($batch['satuan']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
