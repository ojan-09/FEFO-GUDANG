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

    /* ---------- Header ---------- */
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

    /* ---------- Filter ---------- */
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

    /* ---------- Buttons ---------- */
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

    /* ---------- Toolbar ---------- */
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

    /* ---------- Table ---------- */
    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 8px 8px 4px 8px; overflow: hidden;
    }
    #tabelPenyaluran { border-collapse: separate; border-spacing: 0; }
    #tabelPenyaluran thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tabelPenyaluran tbody td {
        font-size: 0.82rem; padding: 0 14px; height: 56px;
        vertical-align: middle; border-bottom: 1px solid var(--wh-border);
        border-top: none; color: var(--wh-text);
    }
    #tabelPenyaluran, #tabelPenyaluran th, #tabelPenyaluran td {
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
    #tabelPenyaluran tbody tr { transition: background 120ms ease; }
    #tabelPenyaluran tbody tr:hover { background: #F3F4F6; }

    /* ---------- Summary Cards ---------- */
    .wh-summary-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 16px; margin-top: 20px;
    }
    .wh-summary-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 18px 20px; min-height: 110px;
        display: flex; flex-direction: column; justify-content: space-between;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        transition: transform 180ms ease, box-shadow 180ms ease;
    }
    .wh-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 24, 40, 0.08);
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

    /* ---------- DataTables overrides ---------- */
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

    /* ---------- Mobile ---------- */
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
            <h1><i class="fa-solid fa-file-export"></i><?= esc($title) ?></h1>
            <p>Histori transaksi pengeluaran dan penyaluran barang gudang</p>
        </div>
        <div class="wh-updated">
            <div class="lbl">Update Terakhir</div>
            <div class="val"><?= date('d F Y') ?><br><?= date('H:i') ?> WIB</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="wh-filter-card">
        <form id="filterForm" action="" method="GET" class="row g-3 align-items-end">
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
            <div class="col-6 col-md-3">
                <label for="filterWilayah" class="form-label">Wilayah Tujuan</label>
                <input type="text" id="filterWilayah" name="wilayah" class="form-control"
                       placeholder="Cari wilayah..." value="<?= esc($filters['wilayah'] ?? '') ?>">
            </div>
            <div class="col-6 col-md-3">
                <label for="filterProgram" class="form-label">Program / Tujuan</label>
                <input type="text" id="filterProgram" name="program" class="form-control"
                       placeholder="Cari program..." value="<?= esc($filters['program'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-6">
                <label for="filterSearch" class="form-label">Nama Barang</label>
                <input type="text" id="filterSearch" name="search" class="form-control"
                       placeholder="Cari nama barang..." value="<?= esc($filters['search'] ?? '') ?>">
            </div>

            <div class="col-12 d-flex align-items-center flex-wrap gap-2 pt-2 filter-actions">
                <button type="submit" class="wh-btn-primary">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
                <a href="<?= site_url('laporan/penyaluran') ?>" class="wh-btn-outline">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>

                <?php if (in_groups('Administrator')): ?>
                    <div class="d-flex align-items-center gap-2 ms-auto filter-export">
                        <?php $queryParams = http_build_query($filters); ?>
                        <a href="<?= site_url('laporan/penyaluran/pdf?' . $queryParams) ?>" class="wh-btn-danger" target="_blank">
                            <i class="fa-solid fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="<?= site_url('laporan/penyaluran/excel?' . $queryParams) ?>" class="wh-btn-success" target="_blank">
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
        <table class="table table-hover align-middle mb-0" id="tabelPenyaluran" style="width:100%;">
            <thead>
                <tr>
                    <th width="30" class="text-center">No</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Nomor Penyaluran</th>
                    <th style="min-width:150px;">Wilayah Tujuan</th>
                    <th style="min-width:150px;">Program</th>
                    <th style="min-width:150px;">Nama Barang</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-end">Berat / Satuan</th>
                    <th class="text-end">Total Berat</th>
                    <th style="min-width:150px;">Keterangan</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Summary Cards -->
        <div class="wh-summary-grid mt-4">
            <div class="wh-summary-card">
                <div class="wh-kpi-icon blue"><i class="fa-solid fa-truck-fast"></i></div>
                <div>
                    <div class="s-value" id="summaryTotalPenyaluran">0</div>
                    <div class="s-label">Total Penyaluran</div>
                </div>
            </div>
            <div class="wh-summary-card">
                <div class="wh-kpi-icon green"><i class="fa-solid fa-boxes-stacked"></i></div>
                <div>
                    <div class="s-value" id="summaryTotalBarang" style="line-height: 1.5;">0</div>
                    <div class="s-label">Total Barang Disalurkan</div>
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
    let table = $('#tabelPenyaluran').DataTable({
        processing: true,
        serverSide: true,
        stateSave: false,
        responsive: true,
        deferRender: true,
        order: [[1, 'desc']],
        ajax: {
            url: '<?= site_url("laporan/penyaluran/ajaxData") ?>',
            type: 'POST',
            data: function (d) {
                d[csrfTokenName] = csrfHash;
                d.start_date = $('#filterStartDate').val();
                d.end_date = $('#filterEndDate').val();
                d.wilayah = $('#filterWilayah').val();
                d.program = $('#filterProgram').val();
                d.search_custom = $('#filterSearch').val();
            },
            dataSrc: function (json) {
                if (json.csrf_hash) {
                    csrfHash = json.csrf_hash;
                }
                if (json.summary) {
                    $('#summaryTotalPenyaluran').text(new Intl.NumberFormat('id-ID').format(json.summary.total_penyaluran || 0));
                    
                    let parts = [];
                    if (json.summary.total_barang_utuh_per_satuan) {
                        $.each(json.summary.total_barang_utuh_per_satuan, function(satuan, jml) {
                            parts.push(new Intl.NumberFormat('id-ID').format(jml) + ' ' + satuan);
                        });
                    }
                    if (json.summary.total_barang_repack > 0) {
                        parts.push(new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(json.summary.total_barang_repack) + ' Kg');
                    }
                    $('#summaryTotalBarang').html(parts.length > 0 ? parts.join('<br>') : '0');
                    
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
            processing: '<div class="py-1 text-center"><div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem; border-width: 0.22em;"><span class="visually-hidden">Memuat...</span></div><div class="mt-2 text-secondary fw-medium" style="font-size: 13px;">Memuat data laporan penyaluran…</div></div>'
        },
        columns: [
            { data: 'no', orderable: false, className: 'text-center' },
            { data: 'tanggal_keluar', className: 'text-center' },
            { data: 'nomor_transaksi', className: 'text-center fw-medium' },
            { data: 'nama_wilayah' },
            { data: 'program' },
            { data: 'nama_barang' },
            { data: 'jumlah', className: 'text-center fw-bold' },
            { data: 'satuan', className: 'text-center' },
            { data: 'berat_per_satuan', className: 'text-end', defaultContent: '-' },
            { data: 'total_berat', className: 'text-end fw-medium' },
            { data: 'keterangan' },
            { data: 'petugas' }
        ],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"lf><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        drawCallback: function() {
            $('#tabelPenyaluran').closest('.wh-table-card').addClass('loaded');
        }
    });

    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        $('#tabelPenyaluran').closest('.wh-table-card').removeClass('loaded');
        table.ajax.reload();
    });
});
</script>
<?= $this->endSection() ?>