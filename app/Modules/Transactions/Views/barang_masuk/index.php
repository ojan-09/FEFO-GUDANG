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

    #tabelBarangMasuk thead th {
        font-size: 0.72rem;
        padding: 0.55rem 0.6rem;
    }
    #tabelBarangMasuk tbody td {
        font-size: 0.8rem;
        padding: 0.45rem 0.6rem;
        vertical-align: middle;
    }
    #tabelBarangMasuk .badge-soft {
        padding: 3px 9px;
        font-size: 0.7rem;
    }
    #tabelBarangMasuk .badge:not(.badge-soft) {
        font-size: 0.68rem;
        padding: 0.32em 0.6em;
        font-weight: 500;
    }
    #tabelBarangMasuk .btn-sm {
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
        <h1 class="page-title"><i class="fa-solid fa-hand-holding-heart me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Daftar seluruh transaksi donasi masuk</span>
    </div>
    <a href="<?= site_url('transaksi/barang-masuk/create') ?>" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-1"></i> Tambah Donasi Masuk
    </a>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tabel Data -->
<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0" id="tabelBarangMasuk" style="font-size: 10.5pt;">
            <thead class="table-light">
                <tr>
                    <th width="3%" class="text-center">No</th>
                    <th width="15%" class="text-nowrap">No. Transaksi</th>
                    <th class="text-nowrap">Donatur</th>
                    <th width="8%" class="text-center text-nowrap">Item</th>
                    <th width="12%" class="text-nowrap">Tanggal Masuk</th>
                    <th width="12%" class="text-nowrap">Petugas</th>
                    <th width="50" class="text-center text-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barangMasuk as $i => $bm) : ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-nowrap"><span class="badge-soft"><?= esc($bm['nomor_transaksi']) ?></span></td>
                        <td>
                            <div class="fw-medium"><?= esc($bm['nama_donatur']) ?></div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info rounded-pill px-3"><?= esc($bm['jumlah_item']) ?> Item</span>
                        </td>
                        <td class="text-nowrap"><?= date('d M Y', strtotime($bm['tanggal_masuk'])) ?></td>
                        <td class="text-nowrap"><?= esc($bm['petugas']) ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="<?= site_url('transaksi/barang-masuk/detail/' . $bm['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <?php if ($bm['is_used']): ?>
                                    <button type="button" class="btn btn-sm btn-secondary" disabled title="Sudah Digunakan (Tidak dapat diubah)">
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="<?= site_url('transaksi/barang-masuk/edit/' . $bm['id']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="<?= site_url('transaksi/barang-masuk/delete/' . $bm['id']) ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus transaksi ini beserta semua batch-nya?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                <?php endif; ?>
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
        $('#tabelBarangMasuk').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": [6] }
            ]
        });
    });
</script>
<?= $this->endSection() ?>