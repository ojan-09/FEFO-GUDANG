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
    <div>
        <table class="table table-hover mb-0" id="tabelBarang" style="width: 100%;">
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
                <!-- DataTables will populate this tbody via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function () {
        $('#tabelBarang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('masterdata/barang/ajaxData') ?>",
                type: "POST",
                data: function (d) {
                    d[csrfName] = csrfHash;
                }
            },
            drawCallback: function (settings) {
                var response = settings.json;
                if (response && response[csrfName]) {
                    csrfHash = response[csrfName];
                }
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            order: [],
            columnDefs: [
                { orderable: false, targets: [0, 8] }
            ],
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf>rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>'
        });
    });
</script>
<?= $this->endSection() ?>
