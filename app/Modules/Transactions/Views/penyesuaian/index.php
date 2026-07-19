<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
/* ═══════════════════════════════════════════════
   DESIGN SYSTEM — Riwayat Penyesuaian Stok
   Konsisten dengan: Dashboard, Donatur, Wilayah,
   Donasi Masuk, Penyaluran Barang, Log Aktivitas
═══════════════════════════════════════════════ */

.dm-page {
    font-size: 13px;
    line-height: 1.5;
    max-width: 1500px;
    width: 100%;
    margin: 0 auto;
    padding: 6px 4px 20px;
    box-sizing: border-box;
}

/* ── TOPBAR ── */
.dm-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 13px 18px;
    margin-bottom: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    min-height: 72px;
}

.dm-topbar__left { display: flex; align-items: center; gap: 11px; }

.dm-topbar__icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border: 1px solid #bfdbfe;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.dm-topbar__icon i { font-size: 18px; color: #2563eb; }

.dm-topbar__title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 2px;
    line-height: 1.2;
}

.dm-topbar__sub {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}

.dm-btn-add {
    height: 36px;
    padding: 0 18px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    text-decoration: none;
    transition: transform .15s, box-shadow .15s;
    flex-shrink: 0;
}

.dm-btn-add i { font-size: 12px; }
.dm-btn-add:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.25); }

/* ── CARD ── */
.dm-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 16px 18px;
    box-shadow: 0 2px 8px rgba(15,23,42,.05);
}

/* ── DATATABLE TOOLBAR ── */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 10px;
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12.5px;
    color: #475569;
    margin: 0;
}

.dataTables_wrapper .dataTables_length select {
    height: 34px;
    width: 72px;
    border-radius: 8px;
    padding: 0 8px;
    border: 1px solid #cbd5e1;
    font-size: 12.5px;
    color: #334155;
    outline: none;
    background: #f8fafc;
    transition: border-color .15s;
}

.dataTables_wrapper .dataTables_length select:focus { border-color: #2563eb; }

.dataTables_wrapper .dataTables_filter input {
    height: 34px;
    width: 220px;
    border-radius: 8px;
    padding: 0 10px;
    border: 1px solid #cbd5e1;
    font-size: 12.5px;
    color: #334155;
    outline: none;
    background: #f8fafc;
    transition: border-color .15s, box-shadow .15s;
    margin-left: 5px;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.09);
    background: #fff;
}

/* Align length + filter on same row */
.dataTables_wrapper .row:first-child {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 2px;
}

/* ── TABLE HEADER ── */
.dm-table {
    width: 100% !important;
    border-collapse: separate;
    border-spacing: 0;
}

.dm-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 10px 12px;
    border-bottom: 2px solid #e2e8f0;
    border-top: 1px solid #e2e8f0;
    white-space: nowrap;
    height: 40px;
    vertical-align: middle;
}

.dm-table thead th:first-child { border-radius: 8px 0 0 0; }
.dm-table thead th:last-child  { border-radius: 0 8px 0 0; }

/* ── TABLE BODY ── */
.dm-table tbody td {
    padding: 9px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 13px;
    height: 44px;
}

.dm-table tbody tr:last-child td { border-bottom: none; }

.dm-table tbody tr:hover td {
    background: #f8fafc;
    transition: background .12s;
}

/* ── BADGES — JENIS ── */
.badge-jenis {
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    height: 24px;
    white-space: nowrap;
    line-height: 1;
}

.badge-jenis.rusak        { background: #fee2e2; color: #dc2626; }
.badge-jenis.hilang       { background: #ffedd5; color: #ea580c; }
.badge-jenis.kedaluwarsa  { background: #f3f4f6; color: #4b5563; }
.badge-jenis.positif      { background: #dcfce7; color: #16a34a; }
.badge-jenis.negatif      { background: #fef9c3; color: #a16207; }
.badge-jenis.opname       { background: #dbeafe; color: #2563eb; }

/* ── BADGE TOTAL ITEM ── */
.badge-count {
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 0 8px;
    background: #f1f5f9;
    color: #475569;
    border-radius: 10px;
    font-size: 11.5px;
    font-weight: 600;
}

/* ── ACTION BUTTONS ── */
.dm-btn-action {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    text-decoration: none;
    border: 1.5px solid;
    transition: background .15s, color .15s, border-color .15s, transform .1s;
    flex-shrink: 0;
}

.dm-btn-action:hover { transform: translateY(-1px); }

.dm-btn-action.view  { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; }
.dm-btn-action.view:hover  { background: #2563eb; color: #fff; border-color: #2563eb; }
.dm-btn-action.edit  { background: #fffbeb; border-color: #fde68a; color: #d97706; }
.dm-btn-action.edit:hover  { background: #f59e0b; color: #fff; border-color: #f59e0b; }
.dm-btn-action.delete { background: #fff5f5; border-color: #fecaca; color: #ef4444; }
.dm-btn-action.delete:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

/* ── PAGINATION ── */
.dataTables_wrapper .dataTables_paginate { margin-top: 10px; }

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    margin: 0 1px !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button .page-link {
    height: 32px !important;
    min-width: 32px;
    padding: 0 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 8px !important;
    border: 1px solid #e2e8f0 !important;
    font-size: 12.5px;
    font-weight: 500;
    color: #334155 !important;
    background: #fff !important;
    transition: all .15s;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link,
.dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link {
    background: #2563eb !important;
    color: #fff !important;
    border-color: #2563eb !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover .page-link {
    background: #eff6ff !important;
    border-color: #bfdbfe !important;
    color: #2563eb !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled .page-link {
    opacity: .45;
    cursor: default;
}

/* ── INFO TEXT ── */
.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #64748b;
    padding-top: 8px;
}

/* Bottom row align */
.dataTables_wrapper .row:last-child {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 2px;
}

/* ── MISC ── */
.text-primary-fw { color: #2563eb; font-weight: 600; }
.col-no   { width: 44px; }
.col-sm   { width: 80px; }
.col-md   { width: 120px; }
.col-act  { width: 70px; }

/* ── RESPONSIVE MOBILE ── */
@media (max-width: 768px) {
    .dm-page { padding: 4px; }
    .dm-topbar { flex-direction: column; align-items: flex-start; gap: 12px; }
    .dm-btn-add { width: 100%; justify-content: center; }
    .dm-table thead th, .dm-table tbody td { font-size: 12px; padding: 8px; }
    .dataTables_wrapper .row:last-child { flex-direction: column; gap: 10px; }
    .dataTables_wrapper .dataTables_paginate { margin-top: 0; align-self: center; }
    .dm-card { padding: 12px; }
}
</style>

<div class="dm-page">

    <!-- ── TOPBAR ── -->
    <div class="dm-topbar">
        <div class="dm-topbar__left">
            <div class="dm-topbar__icon">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <div>
                <h1 class="dm-topbar__title"><?= esc($title) ?></h1>
                <p class="dm-topbar__sub">Histori penyesuaian stok di luar penyaluran — rusak, hilang, opname &amp; koreksi.</p>
            </div>
        </div>
        <a href="<?= site_url('transaksi/penyesuaian/create') ?>" class="btn btn-primary dm-btn-add shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Penyesuaian
        </a>
    </div>

    <!-- ── TABLE CARD ── -->
    <div class="dm-card">
        <div class="table-responsive">
            <table class="table dm-table" id="tablePenyesuaian">
                <thead>
                <tr>
                    <th class="text-center col-no">No</th>
                    <th style="min-width:160px">Nomor Penyesuaian</th>
                    <th class="col-md">Tanggal</th>
                    <th style="min-width:150px">Jenis</th>
                    <th style="min-width:220px">Keterangan</th>
                    <th class="text-center col-sm">Total Item</th>
                    <th style="min-width:120px">Petugas</th>
                    <th class="text-center col-act">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($transaksi as $row) :
                    $badgeClass = 'kedaluwarsa';
                    switch ($row['jenis_penyesuaian']) {
                        case 'Barang Rusak':       $badgeClass = 'rusak';    break;
                        case 'Barang Hilang':      $badgeClass = 'hilang';   break;
                        case 'Koreksi Positif':    $badgeClass = 'positif';  break;
                        case 'Koreksi Negatif':    $badgeClass = 'negatif';  break;
                        case 'Hasil Stock Opname': $badgeClass = 'opname';   break;
                    }
                ?>
                <tr>
                    <td class="text-center text-muted"><?= $no++ ?></td>
                    <td class="text-primary-fw"><?= esc($row['nomor_penyesuaian']) ?></td>
                    <td>
                        <span class="text-muted" style="font-size:13px">
                            <i class="fa-regular fa-calendar me-1"></i><?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </span>
                    </td>
                    <td><span class="badge-jenis <?= $badgeClass ?>"><?= esc($row['jenis_penyesuaian']) ?></span></td>
                    <td>
                        <span class="d-inline-block text-truncate" style="max-width:260px; font-size:13.5px; color:#475569">
                            <?= esc($row['keterangan']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge-count"><?= esc($row['total_item']) ?> Barang</span>
                    </td>
                    <td style="font-size:13.5px"><?= esc($row['username']) ?></td>
                    <td class="text-center">
                        <div class="d-inline-flex gap-1">
                            <a href="<?= site_url('transaksi/penyesuaian/detail/' . $row['id']) ?>"
                               class="dm-btn-action view" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="<?= site_url('transaksi/penyesuaian/delete/' . $row['id']) ?>"
                               class="dm-btn-action delete" title="Hapus (Rollback)"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus dan merollback stok ini? Transaksi hanya bisa dihapus jika stok belum digunakan oleh transaksi lain.')">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#tablePenyesuaian').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        "order": [[2, "desc"], [0, "asc"]],
        "pageLength": 25,
        "drawCallback": function(settings) {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm mb-0');
        }
    });
});
</script>
<?= $this->endSection() ?>