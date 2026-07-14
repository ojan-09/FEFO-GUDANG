<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    .topbar {
        padding: 12px 18px;
    }
    .topbar .page-title {
        font-size: 1.15rem;
    }
    .topbar .subtle {
        font-size: 0.8rem;
    }
    .panel-card {
        padding: 14px 16px;
    }

    /* filter form */
    .panel-card form .form-label {
        font-size: 0.78rem;
        margin-bottom: 0.25rem;
    }
    .panel-card form .form-control,
    .panel-card form .form-select {
        font-size: 0.85rem;
        padding: 0.4rem 0.65rem;
        height: 36px;
    }
    .panel-card form .btn {
        font-size: 0.82rem;
        padding: 0.4rem 0.9rem;
        height: 36px;
    }

    /* table */
    .table thead th {
        font-size: 0.72rem;
        padding: 0.55rem 0.6rem;
    }
    .table tbody td {
        font-size: 0.8rem;
        padding: 0.45rem 0.6rem;
        vertical-align: middle;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.25rem 0.5rem;
        font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_length select {
        font-size: 0.82rem;
        padding: 0.25rem 1.75rem 0.25rem 0.5rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3rem 0.65rem;
        font-size: 0.8rem;
    }
</style>

<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-export me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Histori transaksi pengeluaran/penyaluran barang donasi</span>
    </div>
</div>

<!-- Filter Box -->
<div class="panel-card mb-4" style="background-color: #f8f9fa;">
    <form action="" method="GET" class="row align-items-end g-3">
        <div class="col-md-3">
            <label for="filterStartDate" class="form-label fw-bold">Tanggal Awal</label>
            <input type="date" id="filterStartDate" name="start_date" class="form-control" value="<?= esc($filters['start_date']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterEndDate" class="form-label fw-bold">Tanggal Akhir</label>
            <input type="date" id="filterEndDate" name="end_date" class="form-control" value="<?= esc($filters['end_date']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterNomor" class="form-label fw-bold">Nomor Penyaluran</label>
            <input type="text" id="filterNomor" name="nomor_penyaluran" class="form-control" placeholder="Cari..." value="<?= esc($filters['nomor_penyaluran']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterWilayah" class="form-label fw-bold">Wilayah Tujuan</label>
            <input type="text" id="filterWilayah" name="wilayah" class="form-control" placeholder="Cari..." value="<?= esc($filters['wilayah']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterProgram" class="form-label fw-bold">Program Penyaluran</label>
            <input type="text" id="filterProgram" name="program" class="form-control" placeholder="Cari..." value="<?= esc($filters['program']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterSearch" class="form-label fw-bold">Nama Barang</label>
            <input type="text" id="filterSearch" name="search" class="form-control" placeholder="Cari..." value="<?= esc($filters['search']) ?>">
        </div>

        <div class="col-md-6 d-flex justify-content-between align-items-end">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Terapkan Filter</button>
                <?php if(!empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['nomor_penyaluran']) || !empty($filters['wilayah']) || !empty($filters['program']) || !empty($filters['search'])): ?>
                    <a href="<?= site_url('laporan/penyaluran') ?>" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false">
                    Export <i class="fa-solid fa-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExport">
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/penyaluran/pdf') ?>?<?= http_build_query($filters) ?>" target="_blank">
                            📄 Export PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/penyaluran/excel') ?>?<?= http_build_query($filters) ?>" target="_blank">
                            📊 Export Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0" style="font-size: 10.5pt;">
            <thead class="table-light">
                <tr>
                    <th width="30" class="text-center">No</th>
                    <th class="text-center text-nowrap">Tanggal Penyaluran</th>
                    <th class="text-center text-nowrap">Nomor Penyaluran</th>
                    <th class="text-nowrap" style="min-width: 150px;">Wilayah Tujuan</th>
                    <th class="text-nowrap" style="min-width: 150px;">Program Penyaluran</th>
                    <th class="text-nowrap" style="min-width: 150px;">Nama Barang</th>
                    <th class="text-center text-nowrap">Jumlah</th>
                    <th class="text-center text-nowrap">Satuan</th>
                    <th class="text-end text-nowrap">Berat per Satuan</th>
                    <th class="text-end text-nowrap">Total Berat</th>
                    <th class="text-nowrap" style="min-width: 150px;">Keterangan</th>
                    <th class="text-nowrap">Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php helper('format'); ?>
                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($laporan as $item) : ?>
                        <?php 
                            $beratPerSatuan = (float) $item['berat_per_satuan'];
                            $totalBeratRow = $item['jumlah'] * $beratPerSatuan;
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center text-nowrap">
                                <?= $item['tanggal_keluar'] ? date('d/m/Y', strtotime($item['tanggal_keluar'])) : '-' ?>
                            </td>
                            <td class="text-center text-nowrap fw-medium"><?= esc($item['nomor_transaksi']) ?></td>
                            <td class="text-nowrap"><?= esc($item['nama_wilayah'] ?? '-') ?></td>
                            <td class="text-nowrap"><?= esc($item['program'] ?? '-') ?></td>
                            <td class="text-nowrap"><?= esc($item['nama_barang']) ?></td>
                            <td class="text-center text-nowrap fw-bold"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                            <td class="text-center text-nowrap"><?= esc($item['satuan']) ?></td>
                            <td class="text-end text-nowrap">
                                <?= $beratPerSatuan > 0 ? $beratPerSatuan : '-' ?>
                            </td>
                            <td class="text-end text-nowrap fw-medium">
                                <?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-' ?>
                            </td>
                            <td class="text-nowrap text-muted"><small><?= esc($item['keterangan'] ?? '-') ?></small></td>
                            <td class="text-nowrap"><?= esc($item['petugas'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="panel-card text-center py-3">
            <h6 class="text-muted mb-1">Total Penyaluran</h6>
            <h3 class="mb-0 text-primary"><?= number_format($summary['total_penyaluran'], 0, ',', '.') ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-card text-center py-3">
            <h6 class="text-muted mb-1">Total Barang</h6>
            <h3 class="mb-0 text-success"><?= number_format($summary['total_barang'], 0, ',', '.') ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-card text-center py-3">
            <h6 class="text-muted mb-1">Total Berat</h6>
            <h3 class="mb-0 text-info"><?= format_berat($summary['total_berat'], 'Kg') ?></h3>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


