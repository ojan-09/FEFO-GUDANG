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

    /* ── Filter ── */
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
    .wh-filter-card .form-select,
    .wh-filter-card .select2-container--bootstrap-5 .select2-selection {
        height: 44px !important; border-radius: 10px !important; border: 1px solid var(--wh-border) !important;
        font-size: 0.85rem !important; padding: 0.45rem 0.75rem !important;
    }
    .wh-filter-card .select2-container--bootstrap-5 .select2-selection__rendered {
        line-height: 28px !important; color: var(--wh-text) !important; padding-left: 0 !important;
    }
    .wh-filter-card .form-control:focus,
    .wh-filter-card .form-select:focus,
    .wh-filter-card .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: var(--wh-primary) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
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
    .wh-advanced-toggle {
        font-size: 0.82rem; font-weight: 600; color: var(--wh-primary);
        cursor: pointer; background: none; border: none; padding: 0;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .wh-advanced-toggle:hover { text-decoration: underline; }
    .wh-advanced-box {
        background: var(--wh-bg); border: 1px solid var(--wh-border);
        border-radius: 12px; padding: 16px; margin-top: 4px;
    }

    /* ── Toolbar ── */
    .wh-toolbar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 14px;
    }
    .wh-toolbar-count .num { font-size: 1.1rem; font-weight: 700; color: var(--wh-text); }
    .wh-toolbar-count .lbl { font-size: 0.8rem; color: var(--wh-text-soft); margin-left: 6px; }
    .wh-tb-btn {
        height: 40px; border-radius: 10px; border: 1px solid var(--wh-border);
        background: #fff; color: var(--wh-text); font-size: 0.82rem; font-weight: 600;
        padding: 0 14px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-tb-btn:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-tb-btn:active { transform: scale(0.98); }

    /* ── Table ── */
    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 8px 8px 4px 8px; overflow: hidden;
    }
    #tabelLaporanStok { border-collapse: separate; border-spacing: 0; }
    #tabelLaporanStok thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tabelLaporanStok tbody td {
        font-size: 0.82rem; padding: 0 14px; height: 56px;
        vertical-align: middle; border-bottom: 1px solid var(--wh-border);
        border-top: none; color: var(--wh-text);
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
    #tabelLaporanStok tbody tr { transition: background 120ms ease; }
    #tabelLaporanStok tbody tr:hover { background: #F3F4F6; }

    /* ── Badge ── */
    .wh-badge {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 999px; padding: 4px 10px 4px 8px;
        font-size: 0.7rem; font-weight: 600; white-space: nowrap;
    }
    .wh-badge i { font-size: 0.62rem; }
    .wh-badge.aman    { background: var(--wh-success-soft); color: #15803D; }
    .wh-badge.hampir  { background: var(--wh-warning-soft); color: #B45309; }
    .wh-badge.expired { background: var(--wh-danger-soft);  color: var(--wh-danger); }
    .wh-badge.default { background: var(--wh-dark-soft);    color: var(--wh-text-soft); }

    /* ── Expired indicator ── */
    .wh-expired-ok   { color: var(--wh-text); font-weight: 500; }
    .wh-expired-soon { color: #B45309; font-weight: 600; }
    .wh-expired-over { color: var(--wh-danger); font-weight: 700; }
    .wh-expired-date { display: block; font-size: 0.72rem; color: var(--wh-text-soft); margin-top: 1px; }

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
        .wh-toolbar { flex-direction: column; align-items: flex-start; }
        .filter-actions { flex-direction: column; width: 100%; }
        .filter-actions a,
        .filter-actions button { width: 100%; justify-content: center; }
        .filter-export { margin-left: 0 !important; width: 100%; flex-direction: column; }
        .filter-export a { width: 100%; justify-content: center; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-file-lines"></i><?= esc($title) ?></h1>
            <p>Pusat pencetakan dan unduh laporan operasional stok gudang</p>
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
                <label for="filterKategori" class="form-label">Kategori</label>
                <select id="filterKategori" name="kategori" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= esc($k['nama_kategori']) ?>" <?= ($filters['kategori'] == $k['nama_kategori']) ? 'selected' : '' ?>>
                            <?= esc($k['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label for="filterStatus" class="form-label">Status</label>
                <select id="filterStatus" name="status" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="Aman"          <?= ($filters['status'] == 'Aman')           ? 'selected' : '' ?>>Aman</option>
                    <option value="Hampir Expired" <?= ($filters['status'] == 'Hampir Expired') ? 'selected' : '' ?>>Hampir Expired</option>
                    <option value="Expired"        <?= ($filters['status'] == 'Expired')        ? 'selected' : '' ?>>Expired</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label for="filterDonatur" class="form-label">Donatur</label>
                <input type="text" id="filterDonatur" name="donatur" class="form-control"
                       placeholder="Cari..." value="<?= esc($filters['donatur']) ?>">
            </div>
            <div class="col-6 col-md-3">
                <label for="filterSearch" class="form-label">Nama Barang</label>
                <input type="text" id="filterSearch" name="search" class="form-control"
                       placeholder="Cari..." value="<?= esc($filters['search']) ?>">
            </div>

            <!-- Filter Lanjutan -->
            <div class="col-12">
                <button class="wh-advanced-toggle" type="button"
                        data-bs-toggle="collapse" data-bs-target="#advancedFilters"
                        aria-expanded="<?= (!empty($filters['start_date']) || !empty($filters['end_date'])) ? 'true' : 'false' ?>">
                    <i class="fa-solid fa-sliders"></i> Filter Lanjutan (Rentang Kedaluwarsa)
                </button>
            </div>
            <div class="collapse <?= (!empty($filters['start_date']) || !empty($filters['end_date'])) ? 'show' : '' ?> col-12" id="advancedFilters">
                <div class="wh-advanced-box">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <label for="filterStartDate" class="form-label">Tgl Kedaluwarsa Awal</label>
                            <input type="date" id="filterStartDate" name="start_date" class="form-control"
                                   value="<?= esc($filters['start_date']) ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label for="filterEndDate" class="form-label">Tgl Kedaluwarsa Akhir</label>
                            <input type="date" id="filterEndDate" name="end_date" class="form-control"
                                   value="<?= esc($filters['end_date']) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex gap-2 flex-wrap filter-actions">
                <button type="submit" class="wh-btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter
                </button>
                <?php if (!empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['kategori']) || !empty($filters['status']) || !empty($filters['donatur']) || !empty($filters['search'])): ?>
                    <a href="<?= site_url('laporan/stok') ?>" class="wh-btn-outline">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Reset
                    </a>
                <?php endif; ?>
                <div class="filter-export" style="margin-left: auto; display: flex; gap: 8px;">
                    <a href="<?= site_url('laporan/stok/pdf') ?>?<?= http_build_query($filters) ?>"
                       target="_blank" class="wh-tb-btn btn-export-loading" data-loading-text="Membuat PDF...">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="<?= site_url('laporan/stok/excel') ?>?<?= http_build_query($filters) ?>"
                       target="_blank" class="wh-btn-success btn-export-loading" data-loading-text="Membuat Excel...">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Toolbar -->
    <div class="wh-toolbar">
        <div class="wh-toolbar-count">
            <span class="num">0</span>
            <span class="lbl">Total Data Stok</span>
        </div>
    </div>

    <!-- Table -->
    <div class="wh-table-card">
        <div class="wh-table-spinner">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <div>Memuat data laporan...</div>
        </div>
        <table class="table table-hover align-middle mb-0" id="tabelLaporanStok" style="width:100%;">
            <thead>
                <tr>
                    <th width="30" class="text-center">No</th>
                    <th class="text-center">Status</th>
                    <th style="min-width:150px;">Donatur / Asal Barang</th>
                    <th>Kategori</th>
                    <th class="text-center">Kedaluwarsa</th>
                    <th style="min-width:150px;">Nama Barang</th>
                    <th class="text-center">Jumlah Stok</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-end">Berat / Satuan</th>
                    <th class="text-end">Total Berat</th>
                    <th class="text-center">Jumlah CTN</th>
                    <th style="min-width:200px;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables akan mengisi data ini secara otomatis via AJAX -->
            </tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let csrfTokenName = '<?= csrf_token() ?>';
let csrfHash = '<?= csrf_hash() ?>';

$(document).ready(function () {
    var table = $('#tabelLaporanStok').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
            url: "<?= site_url('laporan/stok/ajaxData') ?>",
            type: "POST",
            data: function (d) {
                d[csrfTokenName] = csrfHash;
                d.kategori = $('#filterKategori').val();
                d.status = $('#filterStatus').val();
                d.donatur = $('#filterDonatur').val();
                d.search_custom = $('#filterSearch').val();
                d.start_date = $('#filterStartDate').val();
                d.end_date = $('#filterEndDate').val();
            },
            dataSrc: function (json) {
                if (json.csrf_hash) {
                    csrfHash = json.csrf_hash;
                }
                $('.wh-toolbar-count .num').text(new Intl.NumberFormat('id-ID').format(json.recordsFiltered || 0));
                return json.data || [];
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables AJAX error:', error, thrown);
            }
        },
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        order: [],
        columnDefs: [
            { orderable: false, targets: [0, 1, 2, 3, 5, 7, 8, 9, 10, 11] }, // Allow sorting on kedaluwarsa(4) and jumlah stok(6)
            { className: "text-center", targets: [0, 1, 4, 6, 7, 10] },
            { className: "text-end", targets: [8, 9] }
        ],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"l><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        responsive: true,
        drawCallback: function() {
            $('#tabelLaporanStok').closest('.wh-table-card').addClass('loaded');
        }
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        $('#tabelLaporanStok').closest('.wh-table-card').removeClass('loaded');
        table.ajax.reload();
    });

    // Option: Reset button to clear form and reload
    $('.wh-btn-outline').on('click', function(e) {
        e.preventDefault();
        $('form')[0].reset();
        $('#filterSearch').val('');
        $('#filterDonatur').val('');
        $('#filterKategori').val('');
        $('#filterStatus').val('');
        $('#filterStartDate').val('');
        $('#filterEndDate').val('');
        $('#tabelLaporanStok').closest('.wh-table-card').removeClass('loaded');
        table.ajax.reload();
    });
});
</script>
<?= $this->endSection() ?>