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
        <h1 class="page-title"><i class="fa-solid fa-file-import me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Histori transaksi penerimaan barang donasi</span>
    </div>
</div>

<!-- Filter Box -->
<div class="panel-card mb-4" style="background-color: #f8f9fa;">
    <form action="" method="GET" class="row align-items-end g-3">
        <!-- Default Period Filters -->
        <div class="col-md-2">
            <label for="filterBulan" class="form-label fw-bold">Bulan</label>
            <select id="filterBulan" name="bulan" class="form-select">
                <?php foreach($bulanList as $num => $name): ?>
                    <option value="<?= $num ?>" <?= ($filters['bulan'] == $num) ? 'selected' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filterTahun" class="form-label fw-bold">Tahun</label>
            <select id="filterTahun" name="tahun" class="form-select">
                <?php foreach($tahunList as $tahun): ?>
                    <option value="<?= $tahun ?>" <?= ($filters['tahun'] == $tahun) ? 'selected' : '' ?>><?= $tahun ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-2">
            <label for="filterDonatur" class="form-label fw-bold">Donatur</label>
            <input type="text" id="filterDonatur" name="donatur" class="form-control" placeholder="Cari donatur..." value="<?= esc($filters['donatur']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterSearch" class="form-label fw-bold">Nama Barang</label>
            <input type="text" id="filterSearch" name="search" class="form-control" placeholder="Cari nama barang..." value="<?= esc($filters['search']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterKategori" class="form-label fw-bold">Kategori</label>
            <select id="filterKategori" name="kategori" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach($kategori as $k): ?>
                    <option value="<?= esc($k['nama_kategori']) ?>" <?= ($filters['kategori'] == $k['nama_kategori']) ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-12 mt-2">
            <button class="btn btn-sm btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="<?= ($filters['use_custom_date']) ? 'true' : 'false' ?>" aria-controls="advancedFilters">
                <i class="fa-solid fa-sliders"></i> Mode Custom (Filter Rentang Tanggal)
            </button>
        </div>

        <div class="collapse <?= ($filters['use_custom_date']) ? 'show' : '' ?> col-12 mt-2" id="advancedFilters">
            <div class="card card-body bg-light border-0 p-3">
                <div class="row g-3">
                    <div class="col-12 text-muted small mb-2">
                        <i class="fa-solid fa-circle-info me-1"></i> Jika tanggal awal dan tanggal akhir diisi, filter bulan dan tahun di atas akan diabaikan.
                    </div>
                    <div class="col-md-3">
                        <label for="filterStartDate" class="form-label fw-bold">Tanggal Awal</label>
                        <input type="date" id="filterStartDate" name="start_date" class="form-control" value="<?= esc($filters['start_date']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filterEndDate" class="form-label fw-bold">Tanggal Akhir</label>
                        <input type="date" id="filterEndDate" name="end_date" class="form-control" value="<?= esc($filters['end_date']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex justify-content-between">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass me-1"></i> Terapkan Filter</button>
                <?php if(!empty($filters['donatur']) || !empty($filters['search']) || !empty($filters['kategori']) || $filters['use_custom_date'] || $filters['bulan'] != date('m') || $filters['tahun'] != date('Y')): ?>
                    <a href="<?= site_url('laporan/donasi') ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left me-1"></i> Reset Filter</a>
                <?php endif; ?>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false">
                    Export <i class="fa-solid fa-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExport">
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/donasi/pdf') ?>?<?= http_build_query($filters) ?>" target="_blank">
                            📄 Export PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/donasi/excel') ?>?<?= http_build_query($filters) ?>" target="_blank">
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
                    <th class="text-center text-nowrap">Tanggal Masuk</th>
                    <th class="text-center text-nowrap">Nomor Donasi</th>
                    <th class="text-nowrap" style="min-width: 150px;">Donatur</th>
                    <th class="text-nowrap" style="min-width: 150px;">Nama Barang</th>
                    <th class="text-nowrap">Kategori</th>
                    <th class="text-center text-nowrap">Jumlah</th>
                    <th class="text-center text-nowrap">Satuan</th>
                    <th class="text-center text-nowrap">CTN</th>
                    <th class="text-end text-nowrap">Berat Bersih</th>
                    <th class="text-end text-nowrap">Total Berat</th>
                    <th class="text-center text-nowrap">Tanggal Kedaluwarsa</th>
                    <th class="text-nowrap" style="min-width: 150px;">Keterangan</th>
                    <th class="text-nowrap">Petugas</th>
                </tr>
            </thead>
            <tbody>
                <?php helper('format'); ?>
                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="14" class="text-center text-muted py-4">Data tidak ditemukan.</td>
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
                                <?= $item['tanggal_masuk'] ? date('d/m/Y', strtotime($item['tanggal_masuk'])) : '-' ?>
                            </td>
                            <td class="text-center text-nowrap fw-medium"><?= esc($item['nomor_transaksi']) ?></td>
                            <td class="text-nowrap"><?= esc($item['nama_donatur'] ?? '-') ?></td>
                            <td class="text-nowrap"><?= esc($item['nama_barang']) ?></td>
                            <td class="text-nowrap"><?= esc($item['kategori_batch']) ?></td>
                            <td class="text-center text-nowrap fw-bold"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                            <td class="text-center text-nowrap"><?= esc($item['satuan']) ?></td>
                            <td class="text-center text-nowrap"><?= !empty($item['jumlah_ctn']) ? $item['jumlah_ctn'] : '-' ?></td>
                            <td class="text-end text-nowrap">
                                <?= $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-' ?>
                            </td>
                            <td class="text-end text-nowrap fw-medium">
                                <?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-' ?>
                            </td>
                            <td class="text-center text-nowrap">
                                <?= $item['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($item['tanggal_kedaluwarsa'])) : '-' ?>
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
            <h6 class="text-muted mb-1">Total Transaksi</h6>
            <h3 class="mb-0 text-primary"><?= number_format($summary['total_transaksi'], 0, ',', '.') ?></h3>
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


