<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-map-location-dot me-2"></i><?= esc($title) ?></h1>
        <span class="subtle">Kelola data referensi wilayah untuk distribusi barang</span>
    </div>
    <a href="<?= site_url('masterdata/wilayah/create') ?>" class="btn btn-primary rounded-pill px-4">
        <i class="fa-solid fa-plus me-1"></i> Tambah Wilayah
    </a>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success rounded-3"><i class="fa-solid fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger rounded-3"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="panel-card">
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-0" id="tabelWilayah">
            <thead class="table-light">
                <tr>
                    <th width="50" class="text-center">No</th>
                    <th>Nama Wilayah</th>
                    <th width="100">Status</th>
                    <th width="150" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($wilayah as $w) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($w['nama_wilayah']) ?></td>
                        <td>
                            <?php if ($w['status'] == 'Aktif') : ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?= site_url('masterdata/wilayah/edit/' . $w['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="confirmDelete(<?= $w['id'] ?>)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
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
        $('#tabelWilayah').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Wilayah?',
            text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url("masterdata/wilayah/delete/") ?>' + id;
            }
        })
    }
</script>
<?= $this->endSection() ?>

