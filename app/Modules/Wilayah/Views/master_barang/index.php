<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Master Barang Wilayah</h1>
            <p class="text-muted mb-0">Kelola daftar barang khusus untuk Gudang Wilayah</p>
        </div>
        <a href="<?= site_url('wilayah/master-barang/create') ?>" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-plus me-2"></i>Tambah Barang
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover align-middle datatable w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th width="15%">Kategori</th>
                            <th width="15%">Satuan</th>
                            <th width="15%">Berat/Satuan</th>
                            <th width="10%">Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function() {
        var table = $('.datatable').DataTable({
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('wilayah/master-barang/ajax-list') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash;
                }
            },
            drawCallback: function (settings) {
                var response = settings.json;
                if (response && response[csrfName]) {
                    csrfHash = response[csrfName];
                }
            },
            columns: [
                {data: null, searchable: false, orderable: false, render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {data: 'kode_barang'},
                {data: 'nama_barang'},
                {data: 'kategori_nama'},
                {data: 'satuan'},
                {data: 'berat_per_satuan', render: function(data, type, row) {
                    if (data) {
                        return data + ' ' + (row.satuan_berat || '');
                    }
                    return '-';
                }},
                {data: 'status', render: function(data) {
                    if (data === 'Aktif') {
                        return '<span class="badge bg-success">Aktif</span>';
                    }
                    return '<span class="badge bg-danger">Nonaktif</span>';
                }},
                {data: 'id', orderable: false, searchable: false, className: 'text-center', render: function(data) {
                    return `
                        <div class="btn-group" role="group">
                            <a href="<?= site_url('wilayah/master-barang/edit/') ?>${data}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="${data}" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `;
                }}
            ],
            order: [[1, 'asc']],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            }
        });

        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var deleteData = {};
                    deleteData[csrfName] = csrfHash;
                    $.ajax({
                        url: '<?= site_url('wilayah/master-barang/delete/') ?>' + id,
                        type: 'POST',
                        data: deleteData,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire('Terhapus!', response.message, 'success');
                                table.ajax.reload();
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
