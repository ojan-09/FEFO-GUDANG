<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php helper('format'); ?>

<style>
/* Styling based on other report pages */
.rpt-page { font-size: 13px; max-width: 1500px; margin: 0 auto; padding: 14px 16px 30px; }
.rpt-header { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center; }
.rpt-header h1 { font-size: 20px; font-weight: 700; margin: 0; color: #1e293b; }
.rpt-header h1 i { color: #2563eb; margin-right: 8px; }
.rpt-filter { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
.rpt-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
.rpt-table th { background: #f8fafc; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; padding: 12px 14px; border-bottom: 2px solid #e2e8f0; border-top: 1px solid #e2e8f0; }
.rpt-table td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; }
.badge-jenis { font-size: 11.5px; padding: 4px 10px; border-radius: 6px; font-weight: 600; display: inline-block; white-space: nowrap; }
.badge-jenis.rusak { background: #fee2e2; color: #dc2626; }
.badge-jenis.hilang { background: #ffedd5; color: #ea580c; }
.badge-jenis.kedaluwarsa { background: #f3f4f6; color: #4b5563; }
.badge-jenis.positif { background: #dcfce7; color: #16a34a; }
.badge-jenis.negatif { background: #fef08a; color: #a16207; }
.badge-jenis.opname { background: #dbeafe; color: #2563eb; }

/* ── PAGINATION ── */
.dataTables_wrapper .dataTables_paginate { margin-top: 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0 !important; border: none !important; background: transparent !important; margin: 0 1px !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button .page-link { height: 32px !important; min-width: 32px; padding: 0 8px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; border: 1px solid #e2e8f0 !important; font-size: 12.5px; font-weight: 500; color: #334155 !important; background: #fff !important; transition: background-color .15s ease, border-color .15s ease, color .15s ease; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link, .dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover .page-link { background: #eff6ff !important; border-color: #bfdbfe !important; color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled .page-link { opacity: .45; cursor: default; }
.dataTables_wrapper .dataTables_info { font-size: 12px; color: #64748b; padding-top: 8px; }
.dataTables_wrapper .row:last-child { display: flex; align-items: center; justify-content: space-between; margin-top: 2px; }

/* ── RESPONSIVE MOBILE ── */
@media (max-width: 768px) {
    .rpt-page { padding: 4px; }
    .rpt-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .rpt-filter form .col-md-6 { flex-direction: column; width: 100%; margin-top: 12px; }
    .rpt-filter form .btn { width: 100%; justify-content: center; }
    .dataTables_wrapper .row:last-child { flex-direction: column; gap: 10px; }
    .dataTables_wrapper .dataTables_paginate { margin-top: 0; align-self: center; }
}
</style>

<div class="rpt-page">
    <div class="rpt-header">
        <div>
            <h1><i class="fa-solid fa-scale-balanced"></i> Laporan Penyesuaian Stok Gudang</h1>
            <p class="text-muted mb-0 mt-1">Daftar transaksi penyesuaian (Barang Rusak, Hilang, dll) berdasarkan periode tanggal.</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="rpt-filter">
        <form action="" method="GET" class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold" style="font-size: 12px;">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="<?= esc($start_date) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" style="font-size: 12px;">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="<?= esc($end_date) ?>">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-filter"></i> Filter Data
                </button>
                <a href="<?= site_url('laporan/penyesuaian/export_pdf') ?>?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" class="btn btn-danger d-inline-flex align-items-center gap-2 btn-export-loading" data-loading-text="Membuat PDF..." target="_blank">
                    <i class="fa-regular fa-file-pdf"></i> Cetak PDF
                </a>
                <a href="<?= site_url('laporan/penyesuaian/export_excel') ?>?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>" class="btn btn-success d-inline-flex align-items-center gap-2 btn-export-loading" data-loading-text="Membuat Excel...">
                    <i class="fa-regular fa-file-excel"></i> Export Excel
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="rpt-card">
        <div class="table-responsive">
            <table class="table rpt-table w-100" id="tableLaporan">
                <thead>
                    <tr>
                    <th class="text-center" width="50">No</th>
                    <th>Tanggal</th>
                    <th>No. Transaksi</th>
                    <th>Jenis Penyesuaian</th>
                    <th>Barang</th>
                    <th>Batch</th>
                    <th class="text-end">Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($laporan as $row) : 
                    $badgeClass = 'kedaluwarsa';
                    switch ($row['jenis_penyesuaian']) {
                        case 'Barang Rusak': $badgeClass = 'rusak'; break;
                        case 'Barang Hilang': $badgeClass = 'hilang'; break;
                        case 'Koreksi Positif': $badgeClass = 'positif'; break;
                        case 'Koreksi Negatif': $badgeClass = 'negatif'; break;
                        case 'Hasil Stock Opname': $badgeClass = 'opname'; break;
                    }
                    $isPlus = ($row['jenis_penyesuaian'] === 'Koreksi Positif');
                    $sign = $isPlus ? '+' : '-';
                    $colorClass = $isPlus ? 'text-success' : 'text-danger';
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                    <td class="fw-bold text-primary"><?= esc($row['nomor_penyesuaian']) ?></td>
                    <td><span class="badge-jenis <?= $badgeClass ?>"><?= esc($row['jenis_penyesuaian']) ?></span></td>
                    <td class="fw-bold"><?= esc($row['nama_barang']) ?></td>
                    <td>
                        <div><span class="badge bg-light text-dark border"><?= esc($row['nomor_batch']) ?></span></div>
                        <small class="text-muted">Exp: <?= $row['tanggal_kedaluwarsa'] ? date('d/m/y', strtotime($row['tanggal_kedaluwarsa'])) : '-' ?></small>
                    </td>
                    <td class="text-end fw-bold <?= $colorClass ?>">
                        <?= $sign ?><?= (isset($row['bisa_dipecah']) && $row['bisa_dipecah'] == 1) ? $row['jumlah'] : number_format($row['jumlah'], 0, ',', '.') ?> <small class="text-muted fw-normal"><?= esc($row['satuan']) ?></small>
                    </td>
                    <td>
                        <div style="max-width: 200px; font-size: 11.5px;" class="text-truncate" title="<?= esc($row['ket_umum'] . ' - ' . $row['keterangan']) ?>">
                            <?= esc($row['ket_umum']) ?><br>
                            <small class="fst-italic text-muted"><?= esc($row['keterangan']) ?></small>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#tableLaporan').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        "order": []
    });
});
</script>
<?= $this->endSection() ?>
