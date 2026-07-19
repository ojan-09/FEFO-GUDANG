<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-cubes me-2"></i><?= $title ?></h1>
        <span class="subtle">Kelola data barang gudang</span>
    </div>
    <a href="<?= site_url('masterdata/barang/create') ?>" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-1"></i> Tambah Barang
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
        <table class="table table-hover mb-0" id="tabelBarang">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Berat/Satuan</th>
                    <th>Min. Stok</th>
                    <th>Bisa Dipecah</th>
                    <th width="140" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $i => $b) : ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><span class="badge-soft"><?= esc($b['kode_barang']) ?></span></td>
                            <td><strong><?= esc($b['nama_barang']) ?></strong></td>
                            <td><?= esc($b['nama_kategori']) ?></td>
                            <td><?= esc($b['satuan']) ?></td>
                            <td><?= esc($b['berat_per_satuan']) ?> <?= esc($b['satuan_berat']) ?></td>
                            <td><?= esc($b['minimum_stok']) ?></td>
                            <td>
                                <?php if ($b['bisa_dipecah'] == 1) : ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">Repack</span>
                                <?php else : ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2">Utuh</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('masterdata/barang/edit/' . $b['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="<?= site_url('masterdata/barang/delete/' . $b['id']) ?>" class="btn btn-sm btn-outline-danger rounded-pill" title="Hapus" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                    <i class="fa-solid fa-trash"></i>
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
    $(document).ready(function () {
        $('#tabelBarang').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: { previous: "Prev", next: "Next" },
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data barang"
            }
        });
    });
</script>
<?= $this->endSection() ?>
