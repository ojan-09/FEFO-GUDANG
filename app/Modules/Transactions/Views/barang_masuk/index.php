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
.dataTables_wrapper .dataTables_paginate {
    padding-top: 6px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    height: 34px;
    min-width: 34px;
    padding: 0 10px !important;
    font-size: 12.5px !important;
    border-radius: 8px !important;
    border: 1px solid #e5e7eb !important;
    background: #fff !important;
    color: #374151 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    transition: background .12s, border-color .12s;
    box-sizing: border-box;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
    background: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #fff !important;
    font-weight: 600;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: .4;
    cursor: not-allowed;
}

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
    <div class="table-responsive">
        <table class="table mb-0" id="tabelBarangMasuk">
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
                <?php foreach ($barangMasuk as $i => $bm) : ?>
                    <tr>
                        <td class="col-no text-center text-secondary"><?= $i + 1 ?></td>
                        <td class="col-notrx">
                            <span class="badge-notrx"><?= esc($bm['nomor_transaksi']) ?></span>
                        </td>
                        <td class="col-donatur">
                            <span class="fw-semibold" style="color:#0f172a;"><?= esc($bm['nama_donatur']) ?></span>
                        </td>
                        <td class="col-item text-center">
                            <span class="badge-item"><?= esc($bm['jumlah_item']) ?> Item</span>
                        </td>
                        <td class="col-tgl" style="color:#475569;">
                            <?= date('d M Y', strtotime($bm['tanggal_masuk'])) ?>
                        </td>
                        <td class="col-petugas" style="color:#475569;">
                            <?= esc($bm['petugas']) ?>
                        </td>
                        <td class="col-aksi">
                            <div class="dm-action-group">
                                <a href="<?= site_url('transaksi/barang-masuk/detail/' . $bm['id']) ?>"
                                   class="dm-btn-action view" title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <?php if ($bm['is_used']): ?>
                                    <button type="button" class="dm-btn-action lock" disabled title="Sudah Digunakan">
                                        <i class="fa-solid fa-lock"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="<?= site_url('transaksi/barang-masuk/edit/' . $bm['id']) ?>"
                                       class="dm-btn-action edit" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="<?= site_url('transaksi/barang-masuk/delete/' . $bm['id']) ?>"
                                       class="dm-btn-action del" title="Hapus"
                                       onclick="return confirm('Yakin ingin menghapus transaksi ini beserta semua batch-nya?')">
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

</div><!-- /.dm-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $('#tabelBarangMasuk').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            order: [],
            columnDefs: [
                { orderable: false, targets: [6] }
            ],
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf>rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        });
    });
</script>
<?= $this->endSection() ?>