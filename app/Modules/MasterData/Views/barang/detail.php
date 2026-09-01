<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <a href="<?= site_url('masterdata/barang') ?>" class="btn btn-sm btn-outline-secondary rounded-pill me-2">
            <i class="fa-solid fa-arrow-left me-1"></i>Kembali
        </a>
        <h1 class="page-title d-inline ms-1">
            <i class="fa-solid fa-box me-2"></i><?= esc($barang['nama_barang']) ?>
        </h1>
        <span class="subtle d-block mt-1">Detail informasi barang dan daftar batch stok</span>
    </div>
</div>

<!-- Info Barang -->
<div class="panel-card mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="fa-solid fa-circle-info text-primary"></i>
        <span class="fw-bold" style="font-size:1em;">Informasi Barang</span>
    </div>

    <div class="row g-3">
        <div class="col-6 col-md-4">
            <div class="info-item">
                <span class="info-label">Kode Barang</span>
                <span class="info-value fw-bold text-primary"><?= esc($barang['kode_barang']) ?></span>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="info-item">
                <span class="info-label">Nama Barang</span>
                <span class="info-value fw-semibold"><?= esc($barang['nama_barang']) ?></span>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="info-item">
                <span class="info-label">Kategori</span>
                <span class="info-value">
                    <span class="badge bg-light text-dark border"><?= esc($barang['nama_kategori'] ?? '—') ?></span>
                </span>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="info-item">
                <span class="info-label">Satuan Utama</span>
                <span class="info-value"><?= esc($barang['satuan']) ?></span>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="info-item">
                <span class="info-label">Total Stok</span>
                <span class="info-value fw-bold text-dark">
                    <?= number_format($totalStok, 0, ',', '.') ?> <?= esc($barang['satuan']) ?>
                </span>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="info-item">
                <span class="info-label">Batch Aktif</span>
                <span class="info-value">
                    <span class="badge bg-info text-dark"><?= $activeBatch ?> Batch</span>
                </span>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value">
                    <?php if ($barang['status'] === 'active'): ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Merged</span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Batch -->
<div class="panel-card">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="fa-solid fa-layer-group text-primary"></i>
        <span class="fw-bold" style="font-size:1em;">Daftar Batch</span>
        <span class="badge bg-secondary ms-1"><?= count($batches) ?> total batch</span>
        <span class="ms-auto text-secondary" style="font-size:0.82em;">
            <i class="fa-solid fa-sort-amount-up me-1"></i>Diurutkan berdasarkan expired terdekat (FEFO)
        </span>
    </div>

    <?php if (empty($batches)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>
            Belum ada batch untuk barang ini.
        </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tabelBatch">
            <thead>
                <tr>
                    <th width="45">No</th>
                    <th>Nomor Batch</th>
                    <th>Tgl Masuk</th>
                    <th>Expired</th>
                    <th class="text-end">Jumlah Awal</th>
                    <th class="text-end">Stok Saat Ini</th>
                    <th>Kemasan</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($batches as $b): ?>
                <?php
                    $satuan = esc($b['satuan'] ?? $barang['satuan']);
                    $expired = $b['tanggal_kedaluwarsa'];
                    $today   = date('Y-m-d');
                    $isExpired = ($expired < $today);
                    $isNearExpired = (!$isExpired && $expired <= date('Y-m-d', strtotime('+30 days')));

                    // Status badge
                    if ($b['status'] === 'Expired' || $isExpired) {
                        $statusBadge = '<span class="badge bg-danger">Expired</span>';
                    } elseif ($b['status'] === 'Habis') {
                        $statusBadge = '<span class="badge bg-secondary">Habis</span>';
                    } else {
                        $statusBadge = '<span class="badge bg-success">Aktif</span>';
                    }

                    // Expired highlight
                    $rowClass = '';
                    if ($isExpired) $rowClass = 'table-danger';
                    elseif ($isNearExpired) $rowClass = 'table-warning';

                    // Kemasan
                    if ($b['menggunakan_kemasan'] && $b['jumlah_ctn'] > 0 && $b['isi_per_ctn'] > 0) {
                        $kemasanHtml = '<span class="badge bg-light text-dark border">'
                            . $b['jumlah_ctn'] . ' CTN &times; ' . number_format($b['isi_per_ctn'], 0, ',', '.') . ' ' . $satuan
                            . '</span>';
                    } else {
                        $kemasanHtml = '<span class="text-secondary" style="font-size:0.85em;">Tidak menggunakan kemasan</span>';
                    }
                ?>
                <tr class="<?= $rowClass ?>">
                    <td class="text-center text-secondary"><?= $no++ ?></td>
                    <td>
                        <span class="fw-semibold" style="color:#1e40af;"><?= esc($b['nomor_batch']) ?></span>
                        <?php if ($isNearExpired && !$isExpired): ?>
                            <span class="badge bg-warning text-dark ms-1" style="font-size:0.72em;">Hampir Expired</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($b['tanggal_masuk'])) ?></td>
                    <td>
                        <span class="<?= $isExpired ? 'text-danger fw-bold' : ($isNearExpired ? 'text-warning fw-semibold' : '') ?>">
                            <?= date('d/m/Y', strtotime($expired)) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <?= number_format($b['jumlah_awal'], 0, ',', '.') ?> <small class="text-secondary"><?= $satuan ?></small>
                    </td>
                    <td class="text-end">
                        <span class="fw-bold <?= $b['stok_saat_ini'] <= 0 ? 'text-secondary' : 'text-dark' ?>">
                            <?= number_format($b['stok_saat_ini'], 0, ',', '.') ?>
                        </span>
                        <small class="text-secondary"> <?= $satuan ?></small>
                    </td>
                    <td><?= $kemasanHtml ?></td>
                    <td class="text-center"><?= $statusBadge ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<style>
.info-item { display: flex; flex-direction: column; gap: 2px; }
.info-label { font-size: 0.78em; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; font-weight: 600; }
.info-value { font-size: 0.95em; color: #0f172a; }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    $('#tabelBatch').DataTable({
        order: [],
        pageLength: 25,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        columnDefs: [{ orderable: false, targets: [0, 7] }]
    });
});
</script>
<?= $this->endSection() ?>