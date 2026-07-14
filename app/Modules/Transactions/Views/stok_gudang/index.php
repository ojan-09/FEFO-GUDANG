<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    /* --- compact sizing pass for this page (sizes only, no colors changed) --- */
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
    #tabelStokGudang thead th {
        font-size: 0.72rem;
        padding: 0.55rem 0.6rem;
    }
    #tabelStokGudang tbody td {
        font-size: 0.8rem;
        padding: 0.45rem 0.6rem;
        vertical-align: middle;
    }
    #tabelStokGudang .badge {
        font-size: 0.68rem;
        padding: 0.32em 0.6em;
        font-weight: 500;
    }
    #tabelStokGudang .btn-sm {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
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

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-warehouse me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Pantau kondisi agregat stok barang dan kadaluwarsa secara terpusat</span>
    </div>
</div>

<!-- Filter Box -->
<div class="panel-card mb-4" style="background-color: #f8f9fa;">
    <form action="" method="GET" class="row align-items-end g-3">
        <div class="col-md-3">
            <label for="filterSearch" class="form-label fw-bold">Nama Barang</label>
            <input type="text" id="filterSearch" name="search" class="form-control" placeholder="Cari barang..." value="<?= esc($filters['search']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterDonatur" class="form-label fw-bold">Donatur / Asal Barang</label>
            <input type="text" id="filterDonatur" name="donatur" class="form-control" placeholder="Cari donatur..." value="<?= esc($filters['donatur']) ?>">
        </div>
        <div class="col-md-2">
            <label for="filterKategori" class="form-label fw-bold">Kategori</label>
            <select id="filterKategori" name="kategori" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach($kategori as $k): ?>
                    <option value="<?= esc($k['nama_kategori']) ?>" <?= ($filters['kategori'] == $k['nama_kategori']) ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filterStatus" class="form-label fw-bold">Status</label>
            <select id="filterStatus" name="status" class="form-select">
                <option value="">-- Semua --</option>
                <option value="Aman" <?= ($filters['status'] == 'Aman') ? 'selected' : '' ?>>Aman</option>
                <option value="Hampir Expired" <?= ($filters['status'] == 'Hampir Expired') ? 'selected' : '' ?>>Hampir Expired</option>
                <option value="Expired" <?= ($filters['status'] == 'Expired') ? 'selected' : '' ?>>Expired</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 mb-1"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            <?php if(!empty($filters['kategori']) || !empty($filters['status']) || !empty($filters['search']) || !empty($filters['donatur'])): ?>
                <a href="<?= site_url('transaksi/stok-gudang') ?>" class="btn btn-outline-secondary w-100">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Table -->
<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0" id="tabelStokGudang" style="font-size: 10.5pt;">
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
                    <th class="text-center text-nowrap" width="50">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php helper('format'); ?>
                <?php $no = 1; foreach ($stokGudang as $stok) : ?>
                    <?php 
                        $beratPerSatuan = (float) $stok['berat_per_satuan'];
                        $totalBerat = $stok['stok_saat_ini'] * $beratPerSatuan;
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
                            <?= $totalBerat > 0 ? (floor($totalBerat) == $totalBerat ? number_format($totalBerat, 0, ',', '.') : number_format($totalBerat, 2, ',', '.')) . ' ' . esc($stok['satuan_berat']) : '-' ?>
                        </td>
                        <td class="text-center text-nowrap"><?= !empty($stok['jumlah_ctn']) ? $stok['jumlah_ctn'] : '-' ?></td>
                        <td class="text-nowrap text-muted"><small><?= esc($stok['catatan'] ?? '-') ?></small></td>
                        <td class="text-center">
                            <a href="<?= site_url('transaksi/stok-gudang/detail/' . $stok['id_barang']) ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                <i class="fa-solid fa-list"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Karena data disorting dari backend, kita nonaktifkan initial sort di DataTables
        // agar tidak membatalkan urutan tanggal_kedaluwarsa ASC dari controller.
        $('#tabelStokGudang').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [], // Biarkan sorting default dari DOM (backend)
            "columnDefs": [
                { "orderable": false, "targets": [12] } // Disable sorting on Action column (index 12)
            ]
        });
    });
</script>
<?= $this->endSection() ?>