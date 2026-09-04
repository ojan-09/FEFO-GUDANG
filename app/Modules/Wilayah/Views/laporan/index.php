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
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 16px;
    }
    .wh-filter-card .form-label {
        font-size: 0.75rem; font-weight: 600; color: var(--wh-text); margin-bottom: 4px;
    }
    .wh-filter-card .form-control,
    .wh-filter-card .form-select,
    .wh-filter-card .select2-container--bootstrap-5 .select2-selection {
        height: 38px !important; border-radius: 8px !important; border: 1px solid var(--wh-border) !important;
        font-size: 0.83rem !important; padding: 0.35rem 0.65rem !important;
    }
    .wh-filter-card .select2-container--bootstrap-5 .select2-selection__rendered {
        line-height: 24px !important; color: var(--wh-text) !important; padding-left: 0 !important;
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
        height: 38px; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
        padding: 0 16px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
        cursor: pointer;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:active { transform: scale(0.98); }
    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 38px; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
        padding: 0 16px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-outline:active { transform: scale(0.98); }
    .wh-btn-success {
        background: #16A34A; border: 1px solid #16A34A; color: #fff;
        height: 38px; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
        padding: 0 16px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-success:hover { background: #15803D; color: #fff; }
    .wh-btn-success:active { transform: scale(0.98); }
    .wh-btn-danger {
        background: var(--wh-danger); border: 1px solid var(--wh-danger); color: #fff;
        height: 38px; border-radius: 8px; font-size: 0.82rem; font-weight: 600;
        padding: 0 16px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-danger:hover { background: #DC2626; color: #fff; }
    .wh-btn-danger:active { transform: scale(0.98); }

    .wh-btn-danger,
.wh-btn-success {
    min-width: 140px;
    justify-content: center;
}
    /* ── Table ── */
    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 8px 8px 4px 8px; overflow: hidden;
    }
    #tabelLaporan { border-collapse: separate; border-spacing: 0; width: 100%; }
    #tabelLaporan thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.04em; padding: 12px 14px;
        border-bottom: 1px solid var(--wh-border); border-top: none; white-space: nowrap;
    }
    #tabelLaporan tbody td {
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
    #tabelLaporan tbody tr { transition: background 120ms ease; }
    #tabelLaporan tbody tr:hover { background: #F3F4F6; }

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
        .filter-actions { flex-direction: column; width: 100%; }
        .filter-actions button { width: 100%; justify-content: center; }
        .filter-export { margin-left: 0 !important; width: 100%; flex-direction: column; }
        .filter-export button { width: 100%; justify-content: center; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-map-location-dot"></i><?= esc($title) ?></h1>
            <p>Pusat pencetakan dan monitoring laporan operasional Gudang Wilayah</p>
        </div>
        <div class="wh-updated">
            <div class="lbl">Sistem Monitoring</div>
            <div class="val text-primary"><i class="fa-solid fa-shield-halved me-1"></i>Real-time Data</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="wh-filter-card">
        <form id="formFilter">
            <div class="row row-cols-1 <?= $isAdmin ? 'row-cols-md-5' : 'row-cols-md-4' ?> g-2 align-items-end mb-2">
                <div class="col">
                    <label for="filterJenis" class="form-label">Jenis Laporan</label>
                    <select id="filterJenis" name="jenis" class="form-select">
                        <option value="masuk">Barang Masuk</option>
                        <option value="keluar">Barang Keluar</option>
                        <option value="stok">Stok Gudang</option>
                    </select>
                </div>
                
                <div class="col">
                    <label for="filterGudang" class="form-label">Gudang Wilayah</label>
                    <?php if (!$isAdmin && $userGudangId): ?>
                        <select id="filterGudang" name="id_gudang" class="form-select" readonly style="pointer-events: none; background-color: #e9ecef;">
                            <?php foreach ($gudang as $g): ?>
                                <option value="<?= $g['id'] ?>" selected><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <select id="filterGudang" name="id_gudang" class="form-select select2">
                            <option value="">-- Seluruh Cabang --</option>
                            <?php foreach ($gudang as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= esc($g['nama']) ?> - <?= esc($g['kota']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <?php if ($isAdmin): ?>
                <div class="col">
                    <label for="filterProvinsi" class="form-label">Provinsi</label>
                    <input type="text" id="filterProvinsi" name="provinsi" class="form-control" placeholder="Semua Provinsi">
                </div>
                <?php endif; ?>

                <div class="col">
                    <label for="filterStartDate" class="form-label">Dari Tanggal</label>
                    <input type="date" id="filterStartDate" name="start_date" class="form-control">
                </div>
                
                <div class="col">
                    <label for="filterEndDate" class="form-label">Sampai Tanggal</label>
                    <input type="date" id="filterEndDate" name="end_date" class="form-control">
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                <button type="submit" class="wh-btn-primary">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
                <div class="d-flex gap-2 ms-auto filter-export">
                <button type="button" class="wh-btn-danger flex-fill justify-content-center" onclick="exportData('pdf')">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </button>
                <button type="button" class="wh-btn-success flex-fill justify-content-center" onclick="exportData('excel')">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </button>
            </div> 
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="wh-table-card" id="tableCard">
        <div class="wh-table-spinner">
            <i class="fa-solid fa-circle-notch fa-spin"></i>
            <div>Memuat Laporan...</div>
        </div>
        <table class="table w-100" id="tabelLaporan">
            <thead id="tabelHeader">
                <!-- Dinamis melalui JS -->
            </thead>
            <tbody>
            </tbody>
        </table>
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

        $('#formFilter').on('submit', function(e) {
            e.preventDefault();
            if (dtTable && isInitialized) {
                dtTable.ajax.reload();
            }
        });
        
        $('#filterJenis').on('change', function() {
            let jenis = $(this).val();
            if (jenis === 'stok') {
                $('#filterStartDate, #filterEndDate').closest('div').hide();
            } else {
                $('#filterStartDate, #filterEndDate').closest('div').show();
            }
            if (dtTable && isInitialized) {
                initTable();
            }
        });

        $('#filterGudang, #filterProvinsi, #filterStartDate, #filterEndDate').on('change', function() {
            if (dtTable && isInitialized) {
                dtTable.ajax.reload();
            }
        });
    });

    function getColumnsConfig(jenis) {
        if (jenis === 'masuk') {
            return [
                { title: 'No', width: '4%', orderable: false },
                { title: 'Tanggal' },
                { title: 'Kode Transaksi' },
                { title: 'Gudang' },
                { title: 'Donatur' },
                { title: 'Barang' },
                { title: 'Kategori' },
                { title: 'Jumlah Masuk' },
                { title: 'Satuan' },
                { title: 'Berat/Satuan' },
                { title: 'Total Berat' },
                { title: 'Operator' }
            ];
        } else if (jenis === 'keluar') {
            return [
                { title: 'No', width: '5%', orderable: false },
                { title: 'Tanggal' },
                { title: 'Kode Transaksi' },
                { title: 'Gudang' },
                { title: 'Tujuan' },
                { title: 'Barang' },
                { title: 'Kategori' },
                { title: 'Jumlah Keluar' },
                { title: 'Satuan' },
                { title: 'Berat Referensi' },
                { title: 'Total Berat' },
                { title: 'Operator' }
            ];
        } else if (jenis === 'stok') {
            return [
                { title: 'No', width: '5%', orderable: false },
                { title: 'Gudang' },
                { title: 'Barang' },
                { title: 'Kategori' },
                { title: 'Jumlah' },
                { title: 'Satuan' },
                { title: 'Berat' },
                { title: 'Status' }
            ];
        }
    }

    function initTable() {
        isInitialized = false;
        if (dtTable) {
            dtTable.destroy();
            $('#tabelLaporan').empty();
        }

        let jenis = $('#filterJenis').val();
        let columns = getColumnsConfig(jenis);

        $('#tableCard').removeClass('loaded');

        dtTable = $('#tabelLaporan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url("wilayah/laporan/ajaxData") ?>',
                type: 'POST',
                timeout: 30000,
                data: function(d) {
                    let formArray = $('#formFilter').serializeArray();
                    $.each(formArray, function() {
                        if (this.name === 'id_gudang' && this.value === '') return;
                        if (this.name === 'provinsi' && this.value === '') return;
                        d[this.name] = this.value;
                    });
                    d.jenis = jenis;
                    d[csrfName] = csrfHash;
                },
                error: function(xhr, error, thrown) {
                    $('#tableCard').addClass('loaded');
                    Swal.fire({
                        icon: 'error',
                        title: 'Koneksi Bermasalah',
                        text: 'Gagal memuat data laporan dari server (Timeout atau Internal Server Error).',
                        confirmButtonText: '<i class="fa-solid fa-arrows-rotate"></i> Muat Ulang',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }
            },
            columns: columns.map((col, index) => {
                return { title: col.title, orderable: col.orderable, width: col.width }
            }),
            language: {
                search: "Cari:",
                lengthMenu: "Tampil _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ entri",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(difilter dari _MAX_ total entri)",
                paginate: { first: "«", last: "»", next: "›", previous: "‹" },
                emptyTable: "Tidak ada data yang ditemukan"
            },
            dom: '<"wh-toolbar"l<"wh-toolbar-count">f><"table-responsive"rt><"d-flex justify-content-between align-items-center mt-3"ip>',
            drawCallback: function(settings) {
                $('#tableCard').addClass('loaded');
                let count = settings.fnRecordsTotal();
                $('.wh-toolbar-count').html('<span class="lbl">Total:</span><span class="num ms-2">' + count + '</span>');
                
                var response = settings.json;
                if (response && response[csrfName]) {
                    csrfHash = response[csrfName];
                }
                isInitialized = true;
            }
        });
    }

    function exportData(type) {
        let form = document.getElementById('formFilter');
        let params = new URLSearchParams(new FormData(form));
        
        let url = '';
        if(type === 'pdf') {
            url = '<?= site_url("wilayah/laporan/pdf") ?>?' + params.toString();
            window.open(url, '_blank');
        } else {
            url = '<?= site_url("wilayah/laporan/excel") ?>?' + params.toString();
            window.location.href = url;
        }
    }
</script>
<?= $this->endSection() ?>