<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
/* ═══════════════════════════════════════════════
   PAGE CONTAINER
═══════════════════════════════════════════════ */
.dm-page {
    font-size: 13px;
    line-height: 1.45;
    max-width: 1500px;
    width: 100%;
    margin: 0 auto;
    padding: 14px 16px 24px;
    box-sizing: border-box;
}

/* ═══════════════════════════════════════════════
   TOPBAR
═══════════════════════════════════════════════ */
.dm-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 14px 20px;
    margin-bottom: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
}
.dm-topbar__title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.2;
}
.dm-topbar__title i { font-size: 18px; margin-right: 8px; }
.dm-topbar__sub {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0 0;
}
.dm-btn-add {
    height: 38px;
    padding: 0 18px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: transform .15s, box-shadow .15s;
    text-decoration: none;
}
.dm-btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37,99,235,.25);
}

/* ═══════════════════════════════════════════════
   DATA CARD
═══════════════════════════════════════════════ */
.dm-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 16px 18px;
    box-shadow: 0 6px 18px rgba(15,23,42,.05);
}

/* ═══════════════════════════════════════════════
   TABLE LOADING SPINNER
═══════════════════════════════════════════════ */
.dm-table-wrap {
    position: relative;
    min-height: 260px;
}
.dm-table-spinner {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: #fff;
    z-index: 50;
    color: #6b7280;
    gap: 10px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    transition: opacity 0.3s ease;
}
.dm-table-spinner i {
    font-size: 1.9rem;
    color: #2563eb;
}
.dm-table-wrap.loaded .dm-table-spinner {
    opacity: 0;
    pointer-events: none;
}
.dm-table-wrap:not(.loaded) #tabelBarangKeluar,
.dm-table-wrap:not(.loaded) .dataTables_wrapper {
    opacity: 0;
}

/* ═══════════════════════════════════════════════
   DATATABLE OVERRIDES
═══════════════════════════════════════════════ */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 12px;
}
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    font-size: 13px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.dataTables_wrapper .dataTables_length select {
    height: 36px;
    font-size: 13px;
    padding: 0 28px 0 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #111827;
    outline: none;
    appearance: auto;
}
.dataTables_wrapper .dataTables_filter input {
    height: 36px;
    width: 220px;
    font-size: 13px;
    padding: 0 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #111827;
    outline: none;
    transition: border-color .15s;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.12);
}
.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #6b7280;
    padding-top: 10px;
}
.dataTables_wrapper .dataTables_paginate { margin-top: 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0 !important; border: none !important; background: transparent !important; margin: 0 1px !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button .page-link { height: 32px !important; min-width: 32px; padding: 0 8px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; border: 1px solid #e2e8f0 !important; font-size: 12.5px; font-weight: 500; color: #334155 !important; background: #fff !important; transition: background-color .15s ease, border-color .15s ease, color .15s ease; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link,
.dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover .page-link { background: #eff6ff !important; border-color: #bfdbfe !important; color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled .page-link { opacity: .45; cursor: default; }

/* ═══════════════════════════════════════════════
   TABLE
═══════════════════════════════════════════════ */
#tabelBarangKeluar {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 13px;
}
#tabelBarangKeluar thead th {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: #6b7280;
    background: #f8fafc;
    padding: 10px 12px;
    border-top: 1px solid #e5e7eb;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
    vertical-align: middle;
}
#tabelBarangKeluar thead th:first-child { border-left: 1px solid #e5e7eb; }
#tabelBarangKeluar thead th:last-child  { border-right: 1px solid #e5e7eb; }

#tabelBarangKeluar tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background .1s;
}
#tabelBarangKeluar tbody tr:hover { background: #f8fafc; }
#tabelBarangKeluar tbody td {
    padding: 10px 12px;
    vertical-align: middle;
    color: #1e293b;
    font-size: 13px;
    border: none;
    border-bottom: 1px solid #f1f5f9;
}

/* column widths */
#tabelBarangKeluar .col-no      { width: 50px;  text-align: center; }
#tabelBarangKeluar .col-notrx   { width: 200px; }
#tabelBarangKeluar .col-tujuan  { /* auto */ }
#tabelBarangKeluar .col-wilayah { width: 150px; }
#tabelBarangKeluar .col-tgl     { width: 130px; }
#tabelBarangKeluar .col-item    { width: 90px;  text-align: center; }
#tabelBarangKeluar .col-petugas { width: 155px; }
#tabelBarangKeluar .col-aksi    { width: 120px; text-align: center; }

/* ═══════════════════════════════════════════════
   BADGES
═══════════════════════════════════════════════ */
.badge-notrx {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    background: #EEF4FF;
    color: #2563eb;
    letter-spacing: .01em;
    white-space: nowrap;
}
.badge-item {
    display: inline-flex;
    align-items: center;
    height: 24px;
    padding: 0 10px;
    font-size: 11.5px;
    font-weight: 600;
    border-radius: 999px;
    background: #FEE2E2;
    color: #dc2626;
    white-space: nowrap;
}

/* ═══════════════════════════════════════════════
   ACTION BUTTONS
═══════════════════════════════════════════════ */
.dm-action-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    flex-wrap: nowrap;
}
.dm-btn-action {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    border: 1.5px solid;
    background: #fff;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s;
    text-decoration: none;
    flex-shrink: 0;
}
.dm-btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.10);
}
.dm-btn-action.pdf   { color: #dc2626; border-color: #ef4444; background: #fff5f5; }
.dm-btn-action.pdf:hover   { background: #fee2e2; border-color: #dc2626; }
.dm-btn-action.word  { color: #2563eb; border-color: #3b82f6; background: #eff6ff; }
.dm-btn-action.word:hover  { background: #dbeafe; border-color: #2563eb; }
.dm-btn-action.view  { color: #0284c7; border-color: #bae6fd; }
.dm-btn-action.view:hover  { background: #e0f2fe; }
.dm-btn-action.edit  { color: #d97706; border-color: #fde68a; }
.dm-btn-action.edit:hover  { background: #fef3c7; }
.dm-btn-action.del   { color: #dc2626; border-color: #fecaca; }
.dm-btn-action.del:hover   { background: #fee2e2; }

/* ── Processing Overlay ── */
div.dataTables_wrapper { position: relative; }
div.dataTables_wrapper div.dataTables_processing {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(255, 255, 255, 0.96);
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px 28px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,.1), 0 8px 10px -6px rgba(0,0,0,.1);
    z-index: 10;
    margin: 0;
}

/* ---------- Mobile ---------- */
@media (max-width: 768px) {
    .dm-page { padding: 12px 12px 40px 12px; }
    .dataTables_wrapper .dataTables_filter input { width: 160px; }
}
</style>

<div class="dm-page">

    <!-- ── Topbar ── -->
    <div class="dm-topbar">
        <div>
            <h1 class="dm-topbar__title">
                <i class="fa-solid fa-arrow-up"></i><?= esc($title) ?>
            </h1>
            <p class="dm-topbar__sub">Daftar seluruh transaksi pengeluaran barang (metode FEFO)</p>
        </div>
        <a href="<?= site_url('transaksi/barang-keluar/create') ?>" class="btn btn-primary dm-btn-add">
            <i class="fa-solid fa-plus"></i> Tambah Barang Keluar
        </a>
    </div>

    <!-- ── Flash Messages ── -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert" style="font-size:13px;">
            <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert" style="font-size:13px;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ── Filter ── -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <label for="filterJenis" class="fw-semibold text-secondary mb-0" style="font-size:12.5px;">
                <i class="fa-solid fa-filter me-1"></i>Filter Jenis:
            </label>
            <select id="filterJenis" class="form-select form-select-sm rounded-pill" style="width: auto; min-width: 170px; font-size:12.5px;">
                <option value="">Semua Penyaluran</option>
                <option value="Penyaluran Relawan">Penyaluran Relawan</option>
                <option value="Penyaluran Internal">Penyaluran Internal</option>
            </select>
        </div>
    </div>

    <!-- ── Data Card ── -->
    <div class="dm-card">
        <!-- Wrapper spinner hanya di area tabel -->
        <div class="dm-table-wrap">
            <div class="dm-table-spinner">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <div>Memuat data barang keluar...</div>
            </div>
            <table class="table mb-0" id="tabelBarangKeluar" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="col-no text-center">No</th>
                        <th class="col-notrx">No. Transaksi</th>
                        <th class="col-tujuan">Tujuan Penyaluran</th>
                        <th class="col-wilayah">Wilayah Tujuan</th>
                        <th class="col-tgl">Tgl Keluar</th>
                        <th class="col-item text-center">Item</th>
                        <th class="col-petugas">Petugas</th>
                        <th class="col-aksi text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables populate via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

</div><!-- /.dm-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function () {
        var table = $('#tabelBarangKeluar').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('transaksi/barang-keluar/ajaxData') ?>",
                type: "POST",
                data: function (d) {
                    d[csrfName] = csrfHash;
                    d.filter_jenis = $('#filterJenis').val();
                }
            },
            drawCallback: function (settings) {
                var response = settings.json;
                if (response && response[csrfName]) {
                    csrfHash = response[csrfName];
                }
                // Spinner hilang setelah data selesai dimuat
                $('#tabelBarangKeluar').closest('.dm-table-wrap').addClass('loaded');
            },
            language: {
                emptyTable:   "Tidak ada data",
                info:         "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty:    "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                lengthMenu:   "Tampilkan _MENU_ data",
                loadingRecords: "Memuat...",
                processing:   "Memproses...",
                search:       "Cari:",
                zeroRecords:  "Tidak ditemukan data yang sesuai",
                paginate: {
                    first:    "Pertama",
                    last:     "Terakhir",
                    next:     "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            order: [[1, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { className: "col-no text-center", targets: [0] },
                { className: "col-notrx",          targets: [1] },
                { className: "col-tujuan",         targets: [2] },
                { className: "col-wilayah",        targets: [3] },
                { className: "col-tgl",            targets: [4] },
                { className: "col-item text-center", targets: [5] },
                { className: "col-petugas",        targets: [6] },
                { className: "col-aksi text-center", targets: [7] }
            ],
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        });

        // Spinner muncul lagi saat filter berubah
        $('#filterJenis').on('change', function () {
            $('#tabelBarangKeluar').closest('.dm-table-wrap').removeClass('loaded');
            table.ajax.reload();
        });

        // Konfirmasi hapus dengan SweetAlert2
        $(document).on('submit', '.form-delete-swal', function (e) {
            e.preventDefault();
            var form = this;
            var confirmText = $(form).data('confirm-text') || 'Apakah Anda yakin ingin menghapus data ini?';

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>