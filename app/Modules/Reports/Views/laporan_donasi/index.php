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
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 20px; margin-bottom: 16px;
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
        transition: transform 120ms ease, background 120ms ease;
        text-decoration: none; cursor: pointer;
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
    #tabelLaporanDonasi { border-collapse: separate; border-spacing: 0; }
    #tabelLaporanDonasi thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tabelLaporanDonasi tbody td {
        font-size: 0.82rem; padding: 0 14px; height: 56px;
        vertical-align: middle; border-bottom: 1px solid var(--wh-border);
        border-top: none; color: var(--wh-text);
    }
    #tabelLaporanDonasi, #tabelLaporanDonasi th, #tabelLaporanDonasi td {
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
    #tabelLaporanDonasi tbody tr { transition: background 120ms ease; }
    #tabelLaporanDonasi tbody tr:hover { background: #F3F4F6; }

    /* ── Summary Cards ── */
    .wh-summary-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 16px; margin-top: 20px;
    }
    .wh-summary-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 18px 20px; min-height: 110px;
        display: flex; flex-direction: column; justify-content: space-between;
        box-shadow: 0 1px 2px rgba(16,24,40,0.04);
        transition: transform 180ms ease, box-shadow 180ms ease;
    }
    .wh-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16,24,40,0.08);
    }
    .wh-kpi-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 0.95rem;
    }
    .wh-kpi-icon.blue  { background: var(--wh-primary-soft); color: var(--wh-primary); }
    .wh-kpi-icon.green { background: var(--wh-success-soft); color: #15803D; }
    .wh-kpi-icon.teal  { background: #ECFEFF; color: #0891B2; }
    .wh-summary-card .s-value {
        font-size: 1.5rem; font-weight: 700; color: var(--wh-text); line-height: 1.2;
    }
    .wh-summary-card .s-label {
        font-size: 0.78rem; color: var(--wh-text-soft); font-weight: 500; margin-top: 2px;
    }

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
    /* ── Processing / Loading Overlay (Dashboard Style) ── */
    div.dataTables_wrapper { position: relative; }
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid var(--wh-border, #E5E7EB);
        border-radius: 12px;
        padding: 16px 28px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        z-index: 10;
        margin: 0;
    }

    /* ── Mobile ── */
    @media (max-width: 768px) {
        .wh-page { padding: 12px 12px 40px 12px; }
        .wh-header { flex-direction: column; align-items: flex-start; padding: 20px; }
        .wh-header h1 { font-size: 1.1rem; }
        .wh-header .wh-updated .val { text-align: left; }
        .wh-summary-grid { grid-template-columns: 1fr; }
        .filter-actions { flex-direction: column; width: 100%; }
        .filter-actions > button,
        .filter-actions > a { width: 100%; justify-content: center; }
        .filter-export { margin-left: 0 !important; width: 100%; flex-direction: column; }
        .filter-export > a { width: 100%; justify-content: center; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-file-import"></i><?= esc($title) ?></h1>
            <p>Histori transaksi penerimaan barang donasi</p>
        </div>
        <div class="wh-updated">
            <div class="lbl">Update Terakhir</div>
            <div class="val"><?= date('d F Y') ?><br><?= date('H:i') ?> WIB</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="wh-filter-card">
        <form id="filterForm" action="" method="GET" class="row g-3 align-items-end">
            <div class="col-6 col-md-2">
                <label for="filterBulan" class="form-label">Bulan</label>
                <select id="filterBulan" name="bulan" class="form-select">
                    <?php foreach ($bulanList as $num => $name): ?>
                        <option value="<?= $num ?>" <?= ($filters['bulan'] == $num) ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label for="filterTahun" class="form-label">Tahun</label>
                <select id="filterTahun" name="tahun" class="form-select">
                    <?php foreach ($tahunList as $tahun): ?>
                        <option value="<?= $tahun ?>" <?= ($filters['tahun'] == $tahun) ? 'selected' : '' ?>><?= $tahun ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label for="filterDonatur" class="form-label">Donatur</label>
                <input type="text" id="filterDonatur" name="donatur" class="form-control"
                       placeholder="Cari donatur..." value="<?= esc($filters['donatur'] ?? '') ?>">
            </div>
            <div class="col-6 col-md-3">
                <label for="filterSearch" class="form-label">Nama Barang</label>
                <input type="text" id="filterSearch" name="search" class="form-control"
                       placeholder="Cari nama barang..." value="<?= esc($filters['search'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <label for="filterKategori" class="form-label">Kategori</label>
                <select id="filterKategori" name="kategori" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= esc($k['nama_kategori']) ?>"
                            <?= (($filters['kategori'] ?? '') == $k['nama_kategori']) ? 'selected' : '' ?>>
                            <?= esc($k['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Lanjutan -->
            <div class="col-12">
                <button class="wh-advanced-toggle" type="button"
                        data-bs-toggle="collapse" data-bs-target="#advancedFilters"
                        aria-expanded="<?= ($filters['use_custom_date']) ? 'true' : 'false' ?>">
                    <i class="fa-solid fa-sliders"></i> Mode Custom (Filter Rentang Tanggal)
                </button>
            </div>

            <div class="collapse <?= ($filters['use_custom_date']) ? 'show' : '' ?>" id="advancedFilters">
                <div class="row g-3 pt-2">
                    <div class="col-6 col-md-3">
                        <label for="filterStartDate" class="form-label">Tanggal Awal</label>
                        <input type="date" id="filterStartDate" name="start_date" class="form-control"
                               value="<?= esc($filters['start_date'] ?? '') ?>">
                    </div>
                    <div class="col-6 col-md-3">
                        <label for="filterEndDate" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="filterEndDate" name="end_date" class="form-control"
                               value="<?= esc($filters['end_date'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex align-items-center flex-wrap gap-2 pt-2 filter-actions">
                <button type="submit" class="wh-btn-primary">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
                <a href="<?= site_url('laporan/donasi') ?>" class="wh-btn-outline">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>

                <?php if (in_groups('Administrator')): ?>
                    <div class="d-flex align-items-center gap-2 ms-auto filter-export">
                        <?php
                            $queryParams = http_build_query($filters);
                        ?>
                        <a href="<?= site_url('laporan/donasi/pdf?' . $queryParams) ?>" class="wh-btn-danger" target="_blank">
                            <i class="fa-solid fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="<?= site_url('laporan/donasi/excel?' . $queryParams) ?>" class="wh-btn-success" target="_blank">
                            <i class="fa-solid fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="wh-table-card">
        <div class="wh-table-spinner">
            <i class="fa-solid fa-spinner fa-spin"></i>
            <div>Memuat data laporan...</div>
        </div>
        <table class="table table-hover align-middle mb-0" id="tabelLaporanDonasi" style="width:100%;">
            <thead>
                <tr>
                    <th width="30" class="text-center">No</th>
                    <th class="text-center">Tanggal Masuk</th>
                    <th class="text-center" style="min-width:185px;">Nomor Donasi</th>
                    <th style="min-width:150px;">Donatur</th>
                    <th style="min-width:150px;">Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-center">CTN</th>
                    <th class="text-end">Berat Bersih</th>
                    <th class="text-end">Total Berat</th>
                    <th class="text-center">Tgl Kedaluwarsa</th>
                    <th style="min-width:150px;">Keterangan</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Summary Cards -->
        <div class="wh-summary-grid mt-4">
            <div class="wh-summary-card">
                <div class="wh-kpi-icon blue"><i class="fa-solid fa-hand-holding-heart"></i></div>
                <div>
                    <div class="s-value" id="summaryTotalTransaksi">0</div>
                    <div class="s-label">Total Transaksi</div>
                </div>
            </div>
            <div class="wh-summary-card">
                <div class="wh-kpi-icon green"><i class="fa-solid fa-boxes-stacked"></i></div>
                <div>
                    <div class="s-value" id="summaryTotalBarang">0</div>
                    <div class="s-label">Total Barang</div>
                </div>
            </div>
            <div class="wh-summary-card">
                <div class="wh-kpi-icon teal"><i class="fa-solid fa-weight-hanging"></i></div>
                <div>
                    <div class="s-value" id="summaryTotalBerat">0 Kg</div>
                    <div class="s-label">Total Berat</div>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let csrfTokenName = '<?= csrf_token() ?>';
let csrfHash = '<?= csrf_hash() ?>';

$(document).ready(function () {
    let table = $('#tabelLaporanDonasi').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        responsive: true,
        deferRender: true,
        order: [[1, 'desc']],
        ajax: {
            url: '<?= site_url("laporan/donasi/ajaxData") ?>',
            type: 'POST',
            data: function (d) {
                d[csrfTokenName] = csrfHash;
                d.bulan = $('#filterBulan').val();
                d.tahun = $('#filterTahun').val();
                d.donatur = $('#filterDonatur').val();
                d.search_custom = $('#filterSearch').val();
                d.kategori = $('#filterKategori').val();
                d.start_date = $('#filterStartDate').val();
                d.end_date = $('#filterEndDate').val();
            },
            dataSrc: function (json) {
                if (json.csrf_hash) {
                    csrfHash = json.csrf_hash;
                }
                if (json.summary) {
                    $('#summaryTotalTransaksi').text(new Intl.NumberFormat('id-ID').format(json.summary.total_transaksi || 0));
                    $('#summaryTotalBarang').text(new Intl.NumberFormat('id-ID').format(json.summary.total_barang || 0));
                    $('#summaryTotalBerat').text((new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(json.summary.total_berat || 0)) + ' Kg');
                }
                return json.data || [];
            },
            error: function(xhr, error, thrown) {
                console.error('DataTables AJAX error:', error, thrown);
            }
        },
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
            processing: '<div class="py-1 text-center"><div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem; border-width: 0.22em;"><span class="visually-hidden">Memuat...</span></div><div class="mt-2 text-secondary fw-medium" style="font-size: 13px;">Memuat data laporan donasi…</div></div>'
        },
        columns: [
            { data: 'no', orderable: false, className: 'text-center' },
            { data: 'tanggal_masuk', className: 'text-center' },
            { data: 'nomor_transaksi', className: 'text-center fw-medium text-nowrap', width: '185px' },
            { data: 'nama_donatur' },
            { data: 'nama_barang' },
            { data: 'kategori_batch' },
            { data: 'jumlah', className: 'text-center fw-bold' },
            { data: 'satuan', className: 'text-center' },
            { data: 'jumlah_ctn', className: 'text-center' },
            { data: 'berat_bersih', className: 'text-end' },
            { data: 'total_berat', className: 'text-end fw-medium' },
            { data: 'tanggal_kedaluwarsa', className: 'text-center' },
            { data: 'keterangan' },
            { data: 'petugas' }
        ],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        drawCallback: function() {
            $('#tabelLaporanDonasi').closest('.wh-table-card').addClass('loaded');
        }
    });

    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        $('#tabelLaporanDonasi').closest('.wh-table-card').removeClass('loaded');
        table.ajax.reload();
    });
});
</script>
<?= $this->endSection() ?>