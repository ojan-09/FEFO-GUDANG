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
        <h1 class="page-title"><i class="fa-solid fa-file-lines me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Pusat pencetakan dan unduh laporan operasional stok gudang</span>
    </div>
</div>

<!-- Filter Box -->
<div class="panel-card mb-4" style="background-color: #f8f9fa;">
    <form action="" method="GET" class="row align-items-end g-3">
        <div class="col-md-3">
            <label for="filterKategori" class="form-label fw-bold">Kategori</label>
            <select id="filterKategori" name="kategori" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach($kategori as $k): ?>
                    <option value="<?= esc($k['nama_kategori']) ?>" <?= ($filters['kategori'] == $k['nama_kategori']) ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filterStatus" class="form-label fw-bold">Status</label>
            <select id="filterStatus" name="status" class="form-select">
                <option value="">-- Semua --</option>
                <option value="Aman" <?= ($filters['status'] == 'Aman') ? 'selected' : '' ?>>Aman</option>
                <option value="Hampir Expired" <?= ($filters['status'] == 'Hampir Expired') ? 'selected' : '' ?>>Hampir Expired</option>
                <option value="Expired" <?= ($filters['status'] == 'Expired') ? 'selected' : '' ?>>Expired</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filterDonatur" class="form-label fw-bold">Donatur</label>
            <input type="text" id="filterDonatur" name="donatur" class="form-control" placeholder="Cari..." value="<?= esc($filters['donatur']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterSearch" class="form-label fw-bold">Nama Barang</label>
            <input type="text" id="filterSearch" name="search" class="form-control" placeholder="Cari..." value="<?= esc($filters['search']) ?>">
        </div>

        <div class="col-12 mt-2">
            <button class="btn btn-sm btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="<?= (!empty($filters['start_date']) || !empty($filters['end_date'])) ? 'true' : 'false' ?>" aria-controls="advancedFilters">
                <i class="fa-solid fa-sliders"></i> Filter Lanjutan
            </button>
        </div>

        <div class="collapse <?= (!empty($filters['start_date']) || !empty($filters['end_date'])) ? 'show' : '' ?> col-12 mt-2" id="advancedFilters">
            <div class="card card-body bg-light border-0 p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="filterStartDate" class="form-label fw-bold">Tanggal Kadaluarsa Awal</label>
                        <input type="date" id="filterStartDate" name="start_date" class="form-control" value="<?= esc($filters['start_date']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="filterEndDate" class="form-label fw-bold">Tanggal Kadaluarsa Akhir</label>
                        <input type="date" id="filterEndDate" name="end_date" class="form-control" value="<?= esc($filters['end_date']) ?>">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-12 mt-3 d-flex justify-content-between">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Terapkan Filter</button>
                <?php if(!empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['kategori']) || !empty($filters['status']) || !empty($filters['donatur']) || !empty($filters['search'])): ?>
                    <a href="<?= site_url('laporan/stok') ?>" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false">
                    Export <i class="fa-solid fa-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExport">
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/stok/pdf') ?>?<?= http_build_query($filters) ?>" target="_blank">
                            📄 Export PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/stok/excel') ?>?<?= http_build_query($filters) ?>" target="_blank">
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
                    <th class="text-center text-nowrap">Status</th>
                    <th class="text-nowrap" style="min-width: 150px;">Donatur / Asal Barang</th>
                    <th class="text-nowrap">Kategori</th>
                    <th class="text-center text-nowrap">Tanggal Kedaluwarsa</th>
                    <th class="text-nowrap" style="min-width: 150px;">Nama Barang</th>
                    <th class="text-center text-nowrap">Jumlah Stok</th>
                    <th class="text-center text-nowrap">Satuan</th>
                    <th class="text-end text-nowrap">Berat per Satuan</th>
                    <th class="text-end text-nowrap">Total Berat</th>
                    <th class="text-center text-nowrap">Jumlah CTN</th>
                    <th class="text-nowrap" style="min-width: 150px;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php helper('format'); ?>
                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($laporan as $stok) : ?>
                        <?php 
                            $beratPerSatuan = (float) $stok['berat_per_satuan'];
                            $totalBeratRow = $stok['stok_saat_ini'] * $beratPerSatuan;
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center text-nowrap">
                                <?php 
                                    $badgeClass = 'bg-secondary';
                                    if ($stok['status'] == 'Aman') $badgeClass = 'bg-success';
                                    elseif ($stok['status'] == 'Hampir Expired') $badgeClass = 'bg-warning text-dark';
                                    elseif ($stok['status'] == 'Expired') $badgeClass = 'bg-danger';
                                ?>
                                <span class="badge <?= $badgeClass ?> px-2 py-1"><?= esc($stok['status']) ?></span>
                            </td>
                            <td class="text-nowrap"><?= esc($stok['donatur'] ?? '-') ?></td>
                            <td class="text-nowrap"><?= esc($stok['kategori']) ?></td>
                            <td class="text-center text-nowrap fw-medium">
                                <?php if ($stok['tanggal_kedaluwarsa']): ?>
                                    <?= date('d/m/Y', strtotime($stok['tanggal_kedaluwarsa'])) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap"><?= esc($stok['nama_barang']) ?></td>
                            <td class="text-center text-nowrap fw-bold"><?= number_format($stok['stok_saat_ini'], 0, ',', '.') ?></td>
                            <td class="text-center text-nowrap"><?= esc($stok['satuan']) ?></td>
                            <td class="text-end text-nowrap">
                                <?= $beratPerSatuan > 0 ? $beratPerSatuan . ' ' . esc($stok['satuan_berat']) : '-' ?>
                            </td>
                            <td class="text-end text-nowrap fw-medium">
                                <?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $stok['satuan_berat']) : '-' ?>
                            </td>
                            <td class="text-center text-nowrap"><?= !empty($stok['jumlah_ctn']) ? $stok['jumlah_ctn'] : '-' ?></td>
                            <td class="text-nowrap text-muted"><small><?= esc($stok['catatan'] ?? '-') ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>



