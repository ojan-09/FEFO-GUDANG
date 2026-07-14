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

    #tabelDonatur thead th {
        font-size: 0.72rem;
        padding: 0.55rem 0.6rem;
    }
    #tabelDonatur tbody td {
        font-size: 0.8rem;
        padding: 0.45rem 0.6rem;
        vertical-align: middle;
    }
    #tabelDonatur .badge {
        font-size: 0.68rem;
        padding: 0.32em 0.6em;
        font-weight: 500;
    }
    #tabelDonatur .btn-sm {
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
        <h1 class="page-title"><i class="fa-solid fa-people-group me-2"></i><?= $title ?></h1>
        <span class="subtle">Kelola data donatur</span>
    </div>
    <a href="<?= site_url('masterdata/donatur/create') ?>" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-1"></i> Tambah Donatur
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
        <table class="table table-bordered table-hover table-striped table-sm align-middle mb-0" id="tabelDonatur" style="font-size: 10.5pt;">
            <thead class="table-light">
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th class="text-nowrap">Nama Donatur</th>
                    <th class="text-nowrap">Jenis</th>
                    <th class="text-nowrap">Kontak</th>
                    <th class="text-nowrap">Email</th>
                    <th width="50" class="text-center text-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donatur as $i => $d) : ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td><strong><?= esc($d['nama_donatur']) ?></strong></td>
                        <td>
                            <?php if ($d['jenis_donatur'] === 'Individu') : ?>
                                <span class="badge bg-info rounded-pill"><i class="fa-solid fa-user me-1"></i>Individu</span>
                            <?php else : ?>
                                <span class="badge bg-primary rounded-pill"><i class="fa-solid fa-building me-1"></i>Perusahaan</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($d['kontak']) ?></td>
                        <td><?= esc($d['email'] ?? '-') ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="<?= site_url('masterdata/donatur/edit/' . $d['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="<?= site_url('masterdata/donatur/delete/' . $d['id']) ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus donatur ini?')">
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
        $('#tabelDonatur').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [],
            "columnDefs": [
                { "orderable": false, "targets": [5] }
            ]
        });
    });
</script>
<?= $this->endSection() ?>