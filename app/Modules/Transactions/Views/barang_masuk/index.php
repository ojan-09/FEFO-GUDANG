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
   DATATABLE OVERRIDES
═══════════════════════════════════════════════ */

/* top controls row */
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

/* bottom controls row */
.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #6b7280;
    padding-top: 10px;
}
.dataTables_wrapper .dataTables_paginate { margin-top: 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0 !important; border: none !important; background: transparent !important; margin: 0 1px !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button .page-link { height: 32px !important; min-width: 32px; padding: 0 8px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; border: 1px solid #e2e8f0 !important; font-size: 12.5px; font-weight: 500; color: #334155 !important; background: #fff !important; transition: background-color .15s ease, border-color .15s ease, color .15s ease; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link, .dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover .page-link { background: #eff6ff !important; border-color: #bfdbfe !important; color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled .page-link { opacity: .45; cursor: default; }

/* ═══════════════════════════════════════════════
   TABLE
═══════════════════════════════════════════════ */
#tabelBarangMasuk {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 13px;
}
#tabelBarangMasuk thead th {
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
#tabelBarangMasuk thead th:first-child { border-left: 1px solid #e5e7eb; }
#tabelBarangMasuk thead th:last-child  { border-right: 1px solid #e5e7eb; }

#tabelBarangMasuk tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background .1s;
}
#tabelBarangMasuk tbody tr:hover { background: #f8fafc; }
#tabelBarangMasuk tbody td {
    padding: 10px 12px;
    vertical-align: middle;
    color: #1e293b;
    font-size: 13px;
    border: none;
    border-bottom: 1px solid #f1f5f9;
}

/* column widths */
#tabelBarangMasuk .col-no     { width: 50px;  text-align: center; }
#tabelBarangMasuk .col-notrx  { width: 200px; }
#tabelBarangMasuk .col-donatur{ /* auto */ }
#tabelBarangMasuk .col-item   { width: 110px; text-align: center; }
#tabelBarangMasuk .col-tgl    { width: 155px; }
#tabelBarangMasuk .col-petugas{ width: 155px; }
#tabelBarangMasuk .col-aksi   { width: 160px; text-align: center; }

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
    background: #E0F2FE;
    color: #0284c7;
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
.dm-btn-action:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.10);
}
/* Lihat — biru */
.dm-btn-action.view  { color: #0284c7; border-color: #bae6fd; }
.dm-btn-action.view:hover  { background: #e0f2fe; }
/* Edit — amber */
.dm-btn-action.edit  { color: #d97706; border-color: #fde68a; }
.dm-btn-action.edit:hover  { background: #fef3c7; }
/* Hapus — merah */
.dm-btn-action.del   { color: #dc2626; border-color: #fecaca; }
.dm-btn-action.del:hover   { background: #fee2e2; }
/* Lock — abu */
.dm-btn-action.lock  { color: #9ca3af; border-color: #e5e7eb; background: #f9fafb; cursor: not-allowed; }

/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
@media (max-width: 767px) {
    .dm-page { padding: 10px 10px; }
    .dm-topbar { padding: 12px 14px; }
    .dm-topbar__title { font-size: 16px; }
    .dataTables_wrapper .dataTables_filter input { width: 160px; }
}
</style>

<div class="dm-page">

<!-- ── Topbar ── -->
<div class="dm-topbar">
    <div>
        <h1 class="dm-topbar__title">
            <i class="fa-solid fa-hand-holding-heart"></i><?= esc($title) ?>
        </h1>
        <p class="dm-topbar__sub">Daftar seluruh transaksi donasi masuk</p>
    </div>
    <a href="<?= site_url('transaksi/barang-masuk/create') ?>" class="btn btn-primary dm-btn-add">
        <i class="fa-solid fa-plus"></i> Tambah Donasi Masuk
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

<!-- ── Data Card ── -->
<div class="dm-card">
    <table class="table mb-0" id="tabelBarangMasuk" style="width: 100%;">
            <thead>
                <tr>
                    <th class="col-no text-center">No</th>
                    <th class="col-notrx">No. Transaksi</th>
                    <th class="col-donatur">Donatur</th>
                    <th class="col-item text-center">Item</th>
                    <th class="col-tgl">Tanggal Masuk</th>
                    <th class="col-petugas">Petugas</th>
                    <th class="col-aksi text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate this tbody via AJAX -->
            </tbody>
        </table>




</div><!-- /.dm-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function () {
        $('#tabelBarangMasuk').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('transaksi/barang-masuk/ajaxData') ?>",
                type: "POST",
                data: function (d) {
                    d[csrfName] = csrfHash;
                }
            },
            // Callback setelah DataTables merender ulang
            drawCallback: function (settings) {
                // Perbarui CSRF hash dari response JSON untuk request berikutnya
                var response = settings.json;
                if (response && response[csrfName]) {
                    csrfHash = response[csrfName];
                }
            },
            language: {
            emptyTable: "Tidak ada data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            lengthMenu: "Tampilkan _MENU_ data",
            loadingRecords: "Memuat...",
            processing: "Memproses...",
            search: "Cari:",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
        },
            order: [[1, 'desc']], // Default order on "No. Transaksi"
            columnDefs: [
                { orderable: false, targets: [0, 6] },
                { className: "col-no text-center", targets: [0] },
                { className: "col-notrx", targets: [1] },
                { className: "col-donatur", targets: [2] },
                { className: "col-item text-center", targets: [3] },
                { className: "col-tgl", targets: [4] },
                { className: "col-petugas", targets: [5] },
                { className: "col-aksi text-center", targets: [6] }
            ],
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        });
    });
</script>
<?= $this->endSection() ?>