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
        padding: 24px 28px;
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
        font-size: 0.88rem; font-weight: 600; color: var(--wh-primary); text-align: right;
    }

    /* ── KPI Cards ── */
    .wh-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .wh-kpi-card {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .wh-kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .wh-kpi-icon.blue   { background: var(--wh-primary-soft); color: var(--wh-primary); }
    .wh-kpi-icon.green  { background: var(--wh-success-soft); color: var(--wh-success); }
    .wh-kpi-icon.purple { background: #F3E8FF; color: #9333EA; }
    
    .wh-kpi-info .lbl { font-size: 0.78rem; font-weight: 600; color: var(--wh-text-soft); margin-bottom: 2px; }
    .wh-kpi-info .val { font-size: 1.4rem; font-weight: 700; color: var(--wh-text); line-height: 1.2; }

    /* ── Filter ── */
    .wh-filter-card {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .wh-filter-card .form-label {
        font-size: 0.78rem; font-weight: 600; color: var(--wh-text); margin-bottom: 6px;
    }
    .wh-filter-card .form-control,
    .wh-filter-card .form-select,
    .wh-filter-card .select2-container--bootstrap-5 .select2-selection {
        height: 42px !important; border-radius: 10px !important; border: 1px solid var(--wh-border) !important;
        font-size: 0.85rem !important; padding: 0.4rem 0.75rem !important;
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
    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 40px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
        padding: 0 16px; display: inline-flex; align-items: center; gap: 8px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
        cursor: pointer;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-outline:active { transform: scale(0.98); }

    /* ── Table Card ── */
    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 12px 16px; overflow: hidden; position: relative; min-height: 320px;
    }
    #tabelStok { border-collapse: separate; border-spacing: 0; width: 100%; }
    #tabelStok thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tabelStok tbody td {
        font-size: 0.83rem; padding: 10px 14px;
        vertical-align: middle; border-bottom: 1px solid var(--wh-border);
        border-top: none; color: var(--wh-text);
    }
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
    #tabelStok tbody tr { transition: background 120ms ease; }
    #tabelStok tbody tr:hover { background: #F8FAFC; }

    /* ── Badge ── */
    .wh-badge {
        display: inline-flex; align-items: center; gap: 5px;
        border-radius: 999px; padding: 4px 10px;
        font-size: 0.72rem; font-weight: 600; white-space: nowrap;
        background: var(--wh-dark-soft); color: var(--wh-text-soft);
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

    @media (max-width: 768px) {
        .wh-page { padding: 12px 12px 40px 12px; }
        .wh-header { flex-direction: column; align-items: flex-start; padding: 20px; }
        .wh-header h1 { font-size: 1.1rem; }
        .wh-header .wh-updated .val { text-align: left; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-boxes-stacked"></i>Monitoring Stok Gudang Wilayah</h1>
            <p>Pemantauan sisa persediaan barang gudang wilayah secara real-time.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="wh-updated text-end">
                <div class="lbl">Terakhir Diperbarui</div>
                <div class="val" id="lastRefreshTime"><i class="fa-regular fa-clock me-1"></i>-</div>
            </div>
            <button type="button" class="wh-btn-outline" id="btnRefresh">
                <i class="fa-solid fa-arrows-rotate"></i>Refresh Data
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="wh-filter-card">
        <form id="formFilter" class="row g-3 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label">Gudang Wilayah</label>
                <select class="form-select select2" name="id_gudang" id="filterGudang" <?= !$isAdmin ? 'disabled' : '' ?>>
                    <?php if ($isAdmin): ?>
                        <option value="">-- Seluruh Gudang --</option>
                    <?php endif; ?>
                    <?php foreach ($gudang as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= (!$isAdmin && $g['id'] == $userGudangId) ? 'selected' : '' ?>>
                            <?= esc($g['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <label class="form-label">Donatur</label>
                <select class="form-select select2" name="id_donatur" id="filterDonatur">
                    <option value="">-- Semua Donatur --</option>
                    <?php foreach ($donatur as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= esc($d['nama_donatur']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <label class="form-label">Kategori</label>
                <select class="form-select select2" name="id_kategori" id="filterKategori">
                    <option value="">-- Semua Kategori --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= esc($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <label class="form-label">Barang</label>
                <select class="form-select select2" name="id_barang" id="filterBarang">
                    <option value="">-- Semua Barang --</option>
                    <?php foreach ($barang as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= esc($b['kode_barang']) ?> - <?= esc($b['nama_barang']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="wh-table-card" id="tableCard">
        <div class="wh-table-spinner">
            <i class="fa-solid fa-circle-notch fa-spin"></i>
            <span>Memuat data stok...</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle w-100" id="tabelStok">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="11%">Kode Barang</th>
                        <th width="18%">Nama Barang</th>
                        <th width="14%">Donatur</th>
                        <th width="10%">Kategori</th>
                        <th width="12%">Gudang</th>
                        <th width="8%" class="text-end">Sisa Stok</th>
                        <th width="5%">Satuan</th>
                        <th width="11%" class="text-end">Total Berat Tersedia</th>
                        <th width="12%">Terakhir Update</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let dtTable = null;
    let csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';
    let isInitialized = false;

    $(document).ready(function() {
        if($.fn.select2) {
            $('.select2').select2({ theme: 'bootstrap-5' });
        }

        initTable();

        $('#btnRefresh').on('click', function() {
            if (dtTable) {
                dtTable.ajax.reload(null, false);
            }
        });

        $('#filterGudang, #filterDonatur, #filterKategori, #filterBarang').on('change', function() {
            if (dtTable && isInitialized) {
                dtTable.ajax.reload();
            }
        });
    });

    function updateRefreshTime() {
        let now = new Date();
        let months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        let dateStr = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
        let timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0') + ':' + String(now.getSeconds()).padStart(2, '0');
        $('#lastRefreshTime').html('<i class="fa-regular fa-clock me-1"></i>' + dateStr + ' ' + timeStr);
    }

    function initTable() {
        isInitialized = false;
        if (dtTable) {
            dtTable.destroy();
        }

        $('#tableCard').removeClass('loaded');

        dtTable = $('#tabelStok').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url("wilayah/stok/ajaxData") ?>',
                type: 'POST',
                timeout: 30000,
                data: function(d) {
                    let formArray = $('#formFilter').serializeArray();
                    $.each(formArray, function() {
                        if (this.value !== '') {
                            d[this.name] = this.value;
                        }
                    });
                    d[csrfName] = csrfHash;
                },
                error: function(xhr, error, thrown) {
                    $('#tableCard').addClass('loaded');
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Bermasalah',
                        text: 'Gagal memuat data stok dari server.',
                        confirmButtonText: '<i class="fa-solid fa-arrows-rotate"></i> Muat Ulang',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            dtTable.ajax.reload(null, false);
                        }
                    });
                }
            },
            columns: [
                { data: 0, orderable: false, className: 'text-center' },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6, className: 'text-end fw-bold text-primary' },
                { data: 7 },
                { data: 8, className: 'text-end fw-semibold text-dark' },
                { data: 9 }
            ],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampil _MENU_ data',
                info: 'Menampilkan _START_ s/d _END_ dari _TOTAL_ entri',
                infoEmpty: 'Tidak ada data tersedia',
                infoFiltered: '(difilter dari _MAX_ total entri)',
                paginate: { first: '«', last: '»', next: '›', previous: '‹' },
                emptyTable: 'Belum ada stok tersedia di gudang ini.'
            },
            dom: '<"d-flex justify-content-between align-items-center mb-2"l<"wh-toolbar-count">f>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            drawCallback: function(settings) {
                $('#tableCard').addClass('loaded');
                let count = settings.fnRecordsTotal();
                $('.wh-toolbar-count').html('<span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">Total Item: ' + count + '</span>');
                
                var response = settings.json;
                if (response && response[csrfName]) {
                    csrfHash = response[csrfName];
                }

                updateRefreshTime();
                isInitialized = true;
            }
        });
    }
</script>
<?= $this->endSection() ?>
