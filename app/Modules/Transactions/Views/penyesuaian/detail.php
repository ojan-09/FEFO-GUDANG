<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
.detail-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    margin-bottom: 24px;
    overflow: hidden;
}
.detail-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.detail-body {
    padding: 24px;
}
.info-label {
    font-size: 12px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
    margin-bottom: 4px;
}
.info-value {
    font-size: 15px;
    color: #0f172a;
    font-weight: 500;
}
.table-custom {
    width: 100%;
    border-collapse: collapse;
}
.table-custom th {
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    padding: 12px 16px;
    border-bottom: 2px solid #cbd5e1;
}
.table-custom td {
    padding: 14px 16px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 14px;
    vertical-align: middle;
}

/* ── RESPONSIVE MOBILE ── */
@media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center.mb-4 { flex-direction: column; align-items: flex-start !important; gap: 12px; }
    .btn-outline-secondary { width: 100%; justify-content: center; }
    .detail-header .row > div { margin-bottom: 12px; }
    .detail-header .row > div:last-child { margin-bottom: 0; }
    .detail-body { padding: 16px; }
    .table-custom th, .table-custom td { font-size: 12px; padding: 10px; }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0 fw-bold text-gray-800"><i class="fa-solid fa-file-lines text-primary me-2"></i>Detail Penyesuaian Stok</h2>
        <p class="text-muted small mb-0 mt-1">Rincian dari transaksi penyesuaian stok nomor <?= esc($penyesuaian['nomor_penyesuaian']) ?></p>
    </div>
    <a href="<?= site_url('transaksi/penyesuaian') ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="detail-card">
    <div class="detail-header">
        <div class="row w-100">
            <div class="col-md-3">
                <div class="info-label">Nomor Penyesuaian</div>
                <div class="info-value fw-bold text-primary fs-5"><?= esc($penyesuaian['nomor_penyesuaian']) ?></div>
            </div>
            <div class="col-md-3">
                <div class="info-label">Tanggal</div>
                <div class="info-value"><i class="fa-regular fa-calendar me-2 text-muted"></i><?= date('d M Y', strtotime($penyesuaian['tanggal'])) ?></div>
            </div>
            <div class="col-md-3">
                <div class="info-label">Jenis Penyesuaian</div>
                <div class="info-value"><span class="badge bg-secondary"><?= esc($penyesuaian['jenis_penyesuaian']) ?></span></div>
            </div>
            <div class="col-md-3">
                <div class="info-label">Petugas</div>
                <div class="info-value"><i class="fa-regular fa-user me-2 text-muted"></i><?= esc($penyesuaian['username'] ?? '-') ?></div>
            </div>
        </div>
    </div>
    <div class="detail-body">
        <div class="mb-4 p-3 bg-light rounded border border-light">
            <div class="info-label">Keterangan / Alasan</div>
            <div class="info-value fst-italic text-dark mt-1">"<?= nl2br(esc($penyesuaian['keterangan'])) ?>"</div>
        </div>

        <h5 class="fw-bold mb-3 mt-4 text-gray-800">Daftar Item Penyesuaian</h5>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Barang</th>
                        <th>Nomor Batch</th>
                        <th class="text-center">Tgl Expired</th>
                        <th class="text-end">Stok Sebelum</th>
                        <th class="text-end text-danger">Jumlah Penyesuaian</th>
                        <th class="text-end text-success">Stok Sesudah</th>
                        <th>Catatan Item</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($details as $row) : 
                        $isPlus = ($penyesuaian['jenis_penyesuaian'] === 'Koreksi Positif');
                        $colorClass = $isPlus ? 'text-success' : 'text-danger';
                        $sign = $isPlus ? '+' : '-';
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="fw-bold"><?= esc($row['nama_barang']) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= esc($row['nomor_batch']) ?></span></td>
                        <td class="text-center"><?= $row['tanggal_kedaluwarsa'] ? date('d M Y', strtotime($row['tanggal_kedaluwarsa'])) : '-' ?></td>
                        
                        <td class="text-end text-muted fw-bold">
                            <?= ($row['bisa_dipecah'] == 1) ? $row['stok_sebelum'] : number_format($row['stok_sebelum'], 0, ',', '.') ?> 
                            <small class="fw-normal"><?= esc($row['satuan']) ?><?= ($row['bisa_dipecah'] == 1) ? ' (Repack)' : '' ?></small>
                        </td>
                        
                        <td class="text-end fw-bold <?= $colorClass ?>">
                            <?= $sign ?> <?= ($row['bisa_dipecah'] == 1) ? $row['jumlah'] : number_format($row['jumlah'], 0, ',', '.') ?> 
                            <small class="fw-normal"><?= esc($row['satuan']) ?><?= ($row['bisa_dipecah'] == 1) ? ' (Repack)' : '' ?></small>
                        </td>

                        <td class="text-end fw-bold text-dark">
                            <?= ($row['bisa_dipecah'] == 1) ? $row['stok_sesudah'] : number_format($row['stok_sesudah'], 0, ',', '.') ?> 
                            <small class="fw-normal text-muted"><?= esc($row['satuan']) ?><?= ($row['bisa_dipecah'] == 1) ? ' (Repack)' : '' ?></small>
                        </td>
                        
                        <td class="fst-italic text-muted" style="font-size: 13px;">
                            <?= esc($row['keterangan'] ?? '-') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
