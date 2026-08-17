<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title"><i class="fa-solid fa-cubes me-2"></i><?= $title ?></h1>
        <span class="subtle">Kelola data barang gudang</span>
    </div>
    <div>
        <?php if (in_groups('Administrator')): ?>
            <button type="button" class="btn btn-warning rounded-pill px-4 me-2" data-bs-toggle="modal" data-bs-target="#modalMerge">
                <i class="fa-solid fa-code-merge me-1"></i> Gabungkan Barang
            </button>
        <?php endif; ?>
        <a href="<?= site_url('masterdata/barang/create') ?>" class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-plus me-1"></i> Tambah Barang
        </a>
    </div>
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
        <i class="fa-solid fa-circle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tabel Data -->
<div class="panel-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <label class="fw-bold mb-0 text-secondary" style="font-size:0.9em;"><i class="fa-solid fa-filter me-1"></i>Filter Status:</label>
            <select id="filterStatus" class="form-select form-select-sm rounded-pill" style="width: 160px;">
                <option value="">Semua Barang</option>
                <option value="active">Hanya Aktif</option>
                <option value="merged">Hanya Merged</option>
            </select>
        </div>
    </div>
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

</div>

<!-- Modal Merge -->
<?php if (in_groups('Administrator')): ?>
<div class="modal fade" id="modalMerge" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formMerge" action="<?= site_url('masterdata/barang/merge') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fa-solid fa-code-merge me-2"></i>Gabungkan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <i class="fa-solid fa-circle-info me-1"></i> Master barang sumber akan digabungkan ke target tanpa menghapus histori.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Barang Target (Utama)</label>
                        <select name="id_target" class="form-select select2-merge" style="width: 100%;" required>
                            <option value="">-- Pilih Barang Target --</option>
                            <?php if(isset($active_barang)): ?>
                                <?php foreach ($active_barang as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= esc($b['kode_barang']) ?> - <?= esc($b['nama_barang']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Barang Sumber (Yang Digabungkan)</label>
                        <select name="id_sumber[]" class="form-select select2-merge" style="width: 100%;" multiple required>
                            <?php if(isset($active_barang)): ?>
                                <?php foreach ($active_barang as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= esc($b['kode_barang']) ?> - <?= esc($b['nama_barang']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted">Bisa pilih lebih dari satu.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fa-solid fa-check me-1"></i>Gabungkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function () {
        var table = $('#tabelBarang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('masterdata/barang/ajaxData') ?>",
                type: "POST",
                data: function (d) {
                    d[csrfName] = csrfHash;
                    d['status_filter'] = $('#filterStatus').val();
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
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
            responsive: true
        });

        $('#filterStatus').on('change', function() {
            table.ajax.reload();
        });
    });

    function confirmDeleteBarang(id) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: 'Data yang sudah dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url("masterdata/barang/delete/") ?>' + id;
            }
        });
    }

    $(document).ready(function() {
        if ($('.select2-merge').length > 0) {
            $('.select2-merge').select2({
                dropdownParent: $('#modalMerge')
            });
        }

        $('#formMerge').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var modalEl = document.getElementById('modalMerge');

            // Hide modal first so backdrop doesn't block SweetAlert
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                var modalObj = bootstrap.Modal.getInstance(modalEl);
                if (modalObj) modalObj.hide();
                else $('#modalMerge').modal('hide');
            } else {
                $('#modalMerge').modal('hide');
            }

            Swal.fire({
                title: 'Gabungkan Barang?',
                text: 'Barang yang dipilih akan digabungkan ke Master Barang Target. Batch, stok, dan histori transaksi tetap dipertahankan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F59E0B',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, Gabungkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else {
                    $('#modalMerge').modal('show');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
