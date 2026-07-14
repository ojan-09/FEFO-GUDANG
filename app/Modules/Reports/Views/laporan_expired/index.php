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

<!-- sisanya tidak diubah sama sekali -->
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-file-circle-exclamation me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Laporan audit untuk monitor barang yang telah atau akan kedaluwarsa</span>
    </div>
</div>

<!-- Filter Box -->
<div class="panel-card mb-4" style="background-color: #f8f9fa;">
    <form action="" method="GET" class="row align-items-end g-3">
        <div class="col-md-3">
            <label for="filterStatus" class="form-label fw-bold">Status Kedaluwarsa</label>
            <select id="filterStatus" name="status" class="form-select">
                <option value="Semua" <?= ($filters['status'] == 'Semua') ? 'selected' : '' ?>>Semua</option>
                <option value="Akan Expired (<= 30 Hari)" <?= ($filters['status'] == 'Akan Expired (<= 30 Hari)') ? 'selected' : '' ?>>Akan Expired (≤30 Hari)</option>
                <option value="Akan Expired (<= 60 Hari)" <?= ($filters['status'] == 'Akan Expired (<= 60 Hari)') ? 'selected' : '' ?>>Akan Expired (≤60 Hari)</option>
                <option value="Akan Expired (<= 90 Hari)" <?= ($filters['status'] == 'Akan Expired (<= 90 Hari)') ? 'selected' : '' ?>>Akan Expired (≤90 Hari)</option>
                <option value="Sudah Expired" <?= ($filters['status'] == 'Sudah Expired') ? 'selected' : '' ?>>Sudah Expired</option>
            </select>
        </div>
        
        <div class="col-md-3">
            <label for="filterSearch" class="form-label fw-bold">Nama Barang</label>
            <input type="text" id="filterSearch" name="search" class="form-control" placeholder="Cari nama barang..." value="<?= esc($filters['search']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterDonatur" class="form-label fw-bold">Donatur</label>
            <input type="text" id="filterDonatur" name="donatur" class="form-control" placeholder="Cari donatur..." value="<?= esc($filters['donatur']) ?>">
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

        <div class="col-md-12 mt-3 d-flex justify-content-between">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass me-1"></i> Terapkan Filter</button>
                <?php if($filters['status'] != 'Semua' || !empty($filters['search']) || !empty($filters['donatur']) || !empty($filters['kategori'])): ?>
                    <a href="<?= site_url('laporan/expired') ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-rotate-left me-1"></i> Reset Filter</a>
                <?php endif; ?>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false">
                    Export <i class="fa-solid fa-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExport">
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/expired/pdf') ?>?<?= http_build_query($filters) ?>" target="_blank">
                            📄 Export PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= site_url('laporan/expired/excel') ?>?<?= http_build_query($filters) ?>" target="_blank">
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
                    <th class="text-center text-nowrap">Tanggal Kedaluwarsa</th>
                    <th class="text-center text-nowrap">Sisa Hari</th>
                    <th class="text-nowrap" style="min-width: 150px;">Donatur</th>
                    <th class="text-nowrap" style="min-width: 150px;">Nama Barang</th>
                    <th class="text-nowrap">Kategori</th>
                    <th class="text-center text-nowrap">Jumlah</th>
                    <th class="text-center text-nowrap">Satuan</th>
                    <th class="text-center text-nowrap">CTN</th>
                    <th class="text-end text-nowrap">Berat Bersih</th>
                    <th class="text-end text-nowrap">Total Berat</th>
                    <th class="text-nowrap" style="min-width: 150px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php helper('format'); ?>
                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="13" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($laporan as $item) : ?>
                        <?php 
                            $beratPerSatuan = (float) $item['berat_per_satuan'];
                            $totalBeratRow = $item['jumlah'] * $beratPerSatuan;
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-nowrap">
                                <?php if (strpos($item['status_label'], '🔴') !== false): ?>
                                    <span class="badge bg-danger rounded-pill"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= esc(str_replace('🔴 ', '', $item['status_label'])) ?></span>
                                <?php elseif (strpos($item['status_label'], '🟡') !== false): ?>
                                    <span class="badge bg-warning text-dark rounded-pill"><i class="fa-solid fa-triangle-exclamation me-1"></i> <?= esc(str_replace('🟡 ', '', $item['status_label'])) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success rounded-pill"><i class="fa-solid fa-check-circle me-1"></i> <?= esc(str_replace('🟢 ', '', $item['status_label'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center text-nowrap fw-medium">
                                <?= $item['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($item['tanggal_kedaluwarsa'])) : '-' ?>
                            </td>
                            <td class="text-center text-nowrap fw-bold <?= $item['sisa_hari'] !== null && $item['sisa_hari'] < 0 ? 'text-danger' : ($item['sisa_hari'] !== null && $item['sisa_hari'] <= 30 ? 'text-warning' : '') ?>">
                                <?= $item['sisa_hari'] !== null ? $item['sisa_hari'] . ' Hari' : '-' ?>
                            </td>
                            <td class="text-nowrap"><?= esc($item['nama_donatur'] ?? '-') ?></td>
                            <td class="text-nowrap fw-medium"><?= esc($item['nama_barang']) ?></td>
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
                            <td class="text-nowrap text-muted"><small><?= esc($item['keterangan'] ?? '-') ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-2">
        <div class="panel-card text-center py-3 h-100 d-flex flex-column justify-content-center">
            <h6 class="text-muted mb-1" style="font-size: 0.85rem;">Total Batch</h6>
            <h4 class="mb-0 text-primary"><?= number_format($summary['total_batch'], 0, ',', '.') ?></h4>
        </div>
    </div>
    <div class="col-md-2">
        <div class="panel-card text-center py-3 h-100 d-flex flex-column justify-content-center">
            <h6 class="text-muted mb-1" style="font-size: 0.85rem;">Total Barang</h6>
            <h4 class="mb-0 text-success"><?= number_format($summary['total_barang'], 0, ',', '.') ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel-card text-center py-3 h-100 d-flex flex-column justify-content-center">
            <h6 class="text-muted mb-1" style="font-size: 0.85rem;">Total Berat</h6>
            <h4 class="mb-0 text-info"><?= format_berat($summary['total_berat'], 'Kg') ?></h4>
        </div>
    </div>
    <div class="col-md-2">
        <div class="panel-card text-center py-3 h-100 d-flex flex-column justify-content-center" style="border-left: 4px solid #dc3545;">
            <h6 class="text-muted mb-1" style="font-size: 0.85rem;">Barang Expired</h6>
            <h4 class="mb-0 text-danger"><?= number_format($summary['total_expired'], 0, ',', '.') ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel-card text-center py-3 h-100 d-flex flex-column justify-content-center" style="border-left: 4px solid #ffc107;">
            <h6 class="text-muted mb-1" style="font-size: 0.85rem;">Hampir Expired</h6>
            <h4 class="mb-0 text-warning" style="color: #d39e00 !important;"><?= number_format($summary['total_hampir_expired'], 0, ',', '.') ?></h4>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


