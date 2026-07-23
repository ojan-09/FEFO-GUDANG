<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php helper('format'); ?>

<style>
    :root {
        --wh-bg: #F8FAFC;
        --wh-card: #FFFFFF;
        --wh-border: #E5E7EB;
        --wh-primary: #2563EB;
        --wh-primary-soft: #EFF6FF;
        --wh-success: #22C55E;
        --wh-success-soft: #ECFDF3;
        --wh-warning: #F59E0B;
        --wh-warning-soft: #FFFBEB;
        --wh-danger: #EF4444;
        --wh-danger-soft: #FEF2F2;
        --wh-dark-soft: #F3F4F6;
        --wh-dark: #374151;
        --wh-text: #111827;
        --wh-text-soft: #6B7280;
    }

    .wh-page {
        background: var(--wh-bg);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 20px 24px 40px 24px;
    }

    /* ── Header ── */
    .wh-header {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 18px;
        padding: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 20px;
    }
    .wh-header h1 {
        font-size: 1.35rem; font-weight: 700; color: var(--wh-text);
        margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px;
    }
    .wh-header h1 i { color: var(--wh-primary); }
    .wh-header p { margin: 0; font-size: 0.85rem; color: var(--wh-text-soft); }
    .wh-header .wh-updated .lbl {
        font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em;
        color: var(--wh-text-soft); font-weight: 600;
    }
    .wh-header .wh-updated .val {
        font-size: 0.9rem; font-weight: 600; color: var(--wh-text); text-align: right;
    }

    /* ── Filter Card ── */
    .wh-filter-card {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
    }
    .wh-filter-card .form-label {
        font-size: 0.78rem; font-weight: 600; color: var(--wh-text); margin-bottom: 6px;
    }
    .wh-filter-card .form-control,
    .wh-filter-card .form-select {
        height: 44px; border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.85rem; padding: 0.5rem 0.75rem;
    }
    .wh-filter-card .form-control:focus,
    .wh-filter-card .form-select:focus {
        border-color: var(--wh-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* ── Buttons ── */
    .wh-btn-primary {
        background: var(--wh-primary); border: 1px solid var(--wh-primary); color: #fff;
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
        cursor: pointer;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:active { transform: scale(0.98); }

    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-outline:active { transform: scale(0.98); }

    .wh-btn-success {
        background: #16A34A; border: 1px solid #16A34A; color: #fff;
        height: 44px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-success:hover { background: #15803D; color: #fff; }
    .wh-btn-success:active { transform: scale(0.98); }

    .wh-tb-btn {
        height: 40px; border-radius: 10px; border: 1px solid var(--wh-border);
        background: #fff; color: var(--wh-text); font-size: 0.82rem; font-weight: 600;
        padding: 0 14px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-tb-btn:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-tb-btn:active { transform: scale(0.98); }

    /* ── Toolbar ── */
    .wh-toolbar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 14px;
    }
    .wh-toolbar-count .num { font-size: 1.1rem; font-weight: 700; color: var(--wh-text); }
    .wh-toolbar-count .lbl { font-size: 0.8rem; color: var(--wh-text-soft); margin-left: 6px; }

    /* ── Table Card ── */
    .wh-table-card {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 16px;
        padding: 8px 8px 4px 8px;
        overflow: hidden;
    }
    #tableLaporan { border-collapse: separate; border-spacing: 0; }
    #tableLaporan thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tableLaporan tbody td {
        font-size: 0.82rem; padding: 0 14px; height: 56px;
        vertical-align: middle; border-bottom: 1px solid var(--wh-border);
        border-top: none; color: var(--wh-text);
    }
    #tableLaporan, #tableLaporan th, #tableLaporan td {
        border-left: none; border-right: none;
    }
    .wh-table-card { position: relative; min-height: 300px; }
    .wh-table-spinner {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        background: var(--wh-card); z-index: 50;
        color: var(--wh-text-soft); gap: 12px; font-weight: 500;
    }
    .wh-table-spinner i { font-size: 2.2rem; color: var(--wh-primary); }
    .wh-table-card.loaded .wh-table-spinner { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
    .wh-table-card:not(.loaded) { max-height: 400px; overflow: hidden; }
    .wh-table-card:not(.loaded) table { opacity: 0; }
    #tableLaporan tbody tr { transition: background 120ms ease; }
    #tableLaporan tbody tr:hover { background: #F3F4F6; }

    /* ── Badge Jenis ── */
    .badge-jenis {
        font-size: 0.72rem; padding: 4px 10px; border-radius: 6px;
        font-weight: 600; display: inline-block; white-space: nowrap;
    }
    .badge-jenis.rusak       { background: var(--wh-danger-soft);  color: #DC2626; }
    .badge-jenis.hilang      { background: var(--wh-warning-soft); color: #B45309; }
    .badge-jenis.kedaluwarsa { background: var(--wh-dark-soft);    color: var(--wh-dark); }
    .badge-jenis.positif     { background: var(--wh-success-soft); color: #15803D; }
    .badge-jenis.negatif     { background: #FEF9C3;                color: #A16207; }
    .badge-jenis.opname      { background: var(--wh-primary-soft); color: var(--wh-primary); }

    /* ── DataTables overrides ── */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.82rem; color: var(--wh-text-soft); padding: 10px 6px;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 10px; border: 1px solid var(--wh-border);
        padding: 0.4rem 0.65rem; font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.82rem; padding: 0.3rem 1.75rem 0.3rem 0.6rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important; padding: 0.35rem 0.7rem !important;
        margin-left: 2px; border: 1px solid transparent !important;
        background: transparent !important; color: var(--wh-text) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--wh-primary) !important; color: #fff !important;
        border-color: var(--wh-primary) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: var(--wh-dark-soft) !important; color: var(--wh-text) !important;
    }

    /* ── Mobile ── */
    @media (max-width: 768px) {
        .wh-page { padding: 12px 12px 40px 12px; }
        .wh-header { flex-direction: column; align-items: flex-start; padding: 20px; }
        .wh-header h1 { font-size: 1.1rem; }
        .wh-header .wh-updated .val { text-align: left; }
        .wh-filter-card .filter-actions {
            flex-direction: column; width: 100%;
        }
        .wh-filter-card .filter-actions a,
        .wh-filter-card .filter-actions button {
            width: 100%; justify-content: center;
        }
        .wh-toolbar { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-scale-balanced"></i> Laporan Penyesuaian Stok</h1>
            <p>Daftar transaksi penyesuaian (Barang Rusak, Hilang, dll) berdasarkan periode tanggal.</p>
        </div>
        <div class="wh-updated">
            <div class="lbl">Update Terakhir</div>
            <div class="val"><?= date('d F Y') ?><br><?= date('H:i') ?> WIB</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="wh-filter-card">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="<?= esc($start_date) ?>">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="<?= esc($end_date) ?>">
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex gap-2 flex-wrap filter-actions">
                    <button type="submit" class="wh-btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter
                    </button>
                    <a href="<?= site_url('laporan/penyesuaian/export_pdf') ?>?start_date=<?= esc($start_date) ?>&end_date=<?= esc($end_date) ?>"
                       target="_blank" class="wh-tb-btn btn-export-loading" data-loading-text="Membuat PDF...">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="<?= site_url('laporan/penyesuaian/export_excel') ?>?start_date=<?= esc($start_date) ?>&end_date=<?= esc($end_date) ?>"
                       class="wh-btn-success btn-export-loading" data-loading-text="Membuat Excel...">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Toolbar -->
    <div class="wh-toolbar">
        <div class="wh-toolbar-count">
            <span class="num"><?= number_format(count($laporan), 0, ',', '.') ?></span>
            <span class="lbl">Total Transaksi Penyesuaian</span>
        </div>
    </div>

    <!-- Table -->
    <div class="wh-table-card">
        <div class="wh-table-spinner">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <div>Memuat data laporan...</div>
        </div>
        <table class="table align-middle mb-0" id="tableLaporan" style="width:100%;">
            <thead>
                <tr>
                    <th class="text-center" width="40">No</th>
                    <th>Tanggal</th>
                    <th>No. Transaksi</th>
                    <th>Jenis Penyesuaian</th>
                    <th style="min-width:150px;">Barang</th>
                    <th>Batch</th>
                    <th class="text-end">Jumlah</th>
                    <th style="min-width:160px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center; padding: 60px 20px;">
                            <i class="fa-solid fa-scale-balanced" style="font-size: 2.5rem; color: var(--wh-border);"></i>
                            <p style="margin-top: 14px; color: var(--wh-text); font-weight: 600;">Data tidak ditemukan.</p>
                            <p style="color: var(--wh-text-soft); font-size: 0.85rem;">Coba ubah rentang tanggal filter.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($laporan as $row):
                        $badgeClass = 'kedaluwarsa';
                        switch ($row['jenis_penyesuaian']) {
                            case 'Barang Rusak':      $badgeClass = 'rusak';    break;
                            case 'Barang Hilang':     $badgeClass = 'hilang';   break;
                            case 'Koreksi Positif':   $badgeClass = 'positif';  break;
                            case 'Koreksi Negatif':   $badgeClass = 'negatif';  break;
                            case 'Hasil Stock Opname':$badgeClass = 'opname';   break;
                        }
                        $isPlus = ($row['jenis_penyesuaian'] === 'Koreksi Positif');
                        $sign   = $isPlus ? '+' : '-';
                        $jumlahFormatted = (isset($row['bisa_dipecah']) && $row['bisa_dipecah'] == 1)
                            ? $row['jumlah']
                            : number_format($row['jumlah'], 0, ',', '.');
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td style="white-space:nowrap;"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                        <td style="font-weight:600; color:var(--wh-primary); white-space:nowrap;">
                            <?= esc($row['nomor_penyesuaian']) ?>
                        </td>
                        <td>
                            <span class="badge-jenis <?= $badgeClass ?>"><?= esc($row['jenis_penyesuaian']) ?></span>
                        </td>
                        <td><strong><?= esc($row['nama_barang']) ?></strong></td>
                        <td>
                            <span style="background:var(--wh-dark-soft); border:1px solid var(--wh-border); border-radius:6px; padding:2px 8px; font-size:0.75rem; font-weight:600;">
                                <?= esc($row['nomor_batch']) ?>
                            </span><br>
                            <small style="color:var(--wh-text-soft); font-size:0.72rem;">
                                Exp: <?= $row['tanggal_kedaluwarsa'] ? date('d/m/y', strtotime($row['tanggal_kedaluwarsa'])) : '-' ?>
                            </small>
                        </td>
                        <td class="text-end" style="font-weight:700; color:<?= $isPlus ? '#15803D' : '#DC2626' ?>;">
                            <?= $sign ?><?= $jumlahFormatted ?>
                            <small style="color:var(--wh-text-soft); font-weight:400;"><?= esc($row['satuan']) ?></small>
                        </td>
                        <td>
                            <div style="max-width:200px; font-size:0.8rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                                 title="<?= esc($row['ket_umum'] . ' - ' . $row['keterangan']) ?>">
                                <?= esc($row['ket_umum']) ?>
                            </div>
                            <small style="color:var(--wh-text-soft); font-style:italic; font-size:0.75rem;">
                                <?= esc($row['keterangan']) ?>
                            </small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    <?php if (!empty($laporan)): ?>
    $('#tableLaporan').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        order: [],
        columnDefs: [{ orderable: false, targets: [0] }],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        initComplete: function() {
            $('#tableLaporan').closest('.wh-table-card').addClass('loaded');
        }
    });
    <?php else: ?>
    $('#tableLaporan').closest('.wh-table-card').addClass('loaded');
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>