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

    #tabelBarangKeluar thead th {
        font-size: 0.72rem;
        padding: 0.55rem 0.6rem;
    }
    #tabelBarangKeluar tbody td {
        font-size: 0.8rem;
        padding: 0.45rem 0.6rem;
        vertical-align: middle;
    }
    #tabelBarangKeluar .badge-soft {
        padding: 3px 9px;
        font-size: 0.7rem;
    }
    #tabelBarangKeluar .badge:not(.badge-soft) {
        font-size: 0.68rem;
        padding: 0.32em 0.6em;
        font-weight: 500;
    }
    #tabelBarangKeluar .btn-sm {
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
        <h1 class="page-title"><i class="fa-solid fa-arrow-up me-2"></i><?= $title ?></h1>
        <span class="subtle">Daftar seluruh transaksi pengeluaran barang (metode FEFO)</span>
    </div>
    <a href="<?= site_url('transaksi/barang-keluar/create') ?>" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-1"></i> Tambah Barang Keluar
    </a>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tabel Data -->
<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0" id="tabelBarangKeluar" style="font-size: 10.5pt;">
            <thead class="table-light">
                <tr>
                    <th width="3%" class="text-center">No</th>
                    <th width="15%" class="text-nowrap">No. Transaksi</th>
                    <th class="text-nowrap">Tujuan Penyaluran</th>
                    <th width="15%" class="text-nowrap">Wilayah Tujuan</th>
                    <th width="12%" class="text-nowrap">Tgl Keluar</th>
                    <th width="8%" class="text-center text-nowrap">Item</th>
                    <th width="12%" class="text-nowrap">Petugas</th>
                    <th width="50" class="text-center text-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barangKeluar as $i => $bk) : ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-nowrap"><span class="badge-soft"><?= esc($bk['nomor_transaksi']) ?></span></td>
                        <td><strong><?= esc($bk['tujuan_penyaluran']) ?></strong></td>
                        <td class="text-nowrap"><?= esc($bk['nama_wilayah'] ?? '-') ?></td>
                        <td class="text-nowrap"><?= date('d M Y', strtotime($bk['tanggal_keluar'])) ?></td>
                        <td class="text-center"><span class="badge bg-danger rounded-pill px-3"><?= $bk['jumlah_item'] ?> Item</span></td>
                        <td class="text-nowrap"><?= esc($bk['petugas']) ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="<?= site_url('transaksi/barang-keluar/detail/' . $bk['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="<?= site_url('transaksi/barang-keluar/edit/' . $bk['id']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="<?= site_url('transaksi/barang-keluar/delete/' . $bk['id']) ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus transaksi ini? Stok batch akan dikembalikan.')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
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
    $(document).ready(function () {
        $('#tabelBarangKeluar').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": [7] }
            ]
        });
    });
</script>
<?= $this->endSection() ?>