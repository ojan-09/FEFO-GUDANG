<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-layer-group me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Daftar rincian batch untuk barang <strong><?= esc($barang['nama_barang']) ?></strong></span>
    </div>
    <a href="<?= site_url('transaksi/stok-gudang') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Table -->
<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" id="tabelBatch">
            <thead class="table-light">
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th>Nomor Batch</th>
                    <th>Donatur</th>
                    <th class="text-center">Tgl Masuk</th>
                    <th class="text-center">Tgl Expired</th>
                    <th class="text-center">Jml Awal</th>
                    <th class="text-end">Stok Saat Ini</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($batches as $batch) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><span class="badge bg-secondary"><?= esc($batch['nomor_batch']) ?></span></td>
                        <td><?= esc($batch['nama_donatur']) ?></td>
                        <td class="text-center"><?= date('d M Y', strtotime($batch['tanggal_masuk'])) ?></td>
                        <td class="text-center text-danger fw-bold"><?= date('d M Y', strtotime($batch['tanggal_kedaluwarsa'])) ?></td>
                        <td class="text-center"><?= number_format($batch['jumlah_awal'], 0, ',', '.') ?> <?= esc($batch['satuan']) ?></td>
                        <td class="text-end fw-bold" style="font-size: 1.1rem;">
                            <?= number_format($batch['stok_saat_ini'], 0, ',', '.') ?> <small class="text-muted fw-normal"><?= esc($batch['satuan']) ?></small>
                        </td>
                        <td class="text-center">
                            <?php 
                                $badgeClass = 'bg-secondary';
                                if ($batch['status_dinamis'] == 'Aman') $badgeClass = 'bg-success';
                                elseif ($batch['status_dinamis'] == 'Hampir Expired') $badgeClass = 'bg-warning text-dark';
                                elseif ($batch['status_dinamis'] == 'Expired') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill"><?= esc($batch['status_dinamis']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if(empty($batches)): ?>
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fa-solid fa-box-open fs-3 mb-2 d-block"></i>
                        Belum ada batch barang.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#tabelBatch').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[4, "asc"]], // Sort by Tgl Expired ASC
            "paging": false,
            "info": false,
            "searching": false
        });
    });
</script>
<?= $this->endSection() ?>

