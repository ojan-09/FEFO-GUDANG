<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
/* ═══════════════════════════════════════════
   BASE
═══════════════════════════════════════════ */
.dm-page {
    max-width: 1500px;
    width: 100%;
    margin: 0 auto;
    padding: 14px 16px 24px;
    box-sizing: border-box;
    font-size: 13px;
    line-height: 1.45;
}

/* ═══════════════════════════════════════════
   TOPBAR
═══════════════════════════════════════════ */
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
.dm-topbar__title i {
    font-size: 18px;
    margin-right: 8px;
}
.dm-topbar__sub {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0 0;
}
.dm-topbar__right {
    text-align: right;
    flex-shrink: 0;
}
.dm-topbar__right .lbl {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #6b7280;
    font-weight: 700;
}
.dm-topbar__right .val {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.4;
}

/* ═══════════════════════════════════════════
   FILTER CARD
═══════════════════════════════════════════ */
.dm-filter-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
}
.dm-filter-card .form-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 5px;
}
.dm-filter-card .form-control,
.dm-filter-card .form-select {
    height: 36px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 13px;
    padding: 0 10px;
    color: #111827;
}
.dm-filter-card .form-control:focus,
.dm-filter-card .form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.12);
    outline: none;
}

/* ═══════════════════════════════════════════
   FILTER BUTTONS
═══════════════════════════════════════════ */
.dm-btn-filter,
.dm-btn-reset {
    height: 36px;
    padding: 0 16px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    cursor: pointer;
    transition: background .12s;
    border: 1px solid;
}
.dm-btn-filter {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}
.dm-btn-filter:hover { background: #1d4ed8; color: #fff; }
.dm-btn-reset {
    background: #fff;
    border-color: #d1d5db;
    color: #374151;
    box-shadow: 0 1px 2px rgba(0,0,0,.06);
    min-width: 70px;
    justify-content: center;
}
.dm-btn-reset:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #111827;
}

/* ═══════════════════════════════════════════
   DATA CARD
═══════════════════════════════════════════ */
.dm-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    overflow: hidden;          /* agar border-radius terapply ke tabel */
    box-shadow: 0 6px 18px rgba(15,23,42,.05);
}

/* ═══════════════════════════════════════════
   TABLE WRAPPER — horizontal scroll
═══════════════════════════════════════════ */
.dm-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* ═══════════════════════════════════════════
   TABLE CORE
═══════════════════════════════════════════ */
#tabelStokGudang {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 13px;
}

/* ── HEADER ── */
#tabelStokGudang thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #f8fafc;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #64748b;
    padding: 11px 13px;
    border-top: 1px solid #e5e7eb;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
}
#tabelStokGudang thead th:first-child { border-left: 1px solid #e5e7eb; }
#tabelStokGudang thead th:last-child  { border-right: 1px solid #e5e7eb; }

/* Sortable header: cursor pointer */
#tabelStokGudang thead th.sorting,
#tabelStokGudang thead th.sorting_asc,
#tabelStokGudang thead th.sorting_desc {
    cursor: pointer;
    padding-right: 26px;   /* ruang untuk ikon sort */
    position: relative;
}

/* ── SORT ICONS — ganti bawaan DataTables ── */
/* Hapus bawaan DataTables */
#tabelStokGudang thead th.sorting::after,
#tabelStokGudang thead th.sorting_asc::after,
#tabelStokGudang thead th.sorting_desc::after,
#tabelStokGudang thead th.sorting::before,
#tabelStokGudang thead th.sorting_asc::before,
#tabelStokGudang thead th.sorting_desc::before {
    display: none !important;
}

/* Wrapper ikon — dua segitiga ditumpuk */
#tabelStokGudang thead th.sorting .dt-sort-icon,
#tabelStokGudang thead th.sorting_asc .dt-sort-icon,
#tabelStokGudang thead th.sorting_desc .dt-sort-icon {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 2px;
    align-items: center;
}

/* Segitiga atas (ASC) */
#tabelStokGudang thead th .dt-sort-icon::before {
    content: '';
    display: block;
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-bottom: 5px solid #cbd5e1;   /* abu default */
}
/* Segitiga bawah (DESC) */
#tabelStokGudang thead th .dt-sort-icon::after {
    content: '';
    display: block;
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 5px solid #cbd5e1;   /* abu default */
}

/* Aktif ASC → panah atas biru, bawah pudar */
#tabelStokGudang thead th.sorting_asc .dt-sort-icon::before {
    border-bottom-color: #2563eb;
}
#tabelStokGudang thead th.sorting_asc .dt-sort-icon::after {
    border-top-color: #cbd5e1;
    opacity: .35;
}

/* Aktif DESC → panah bawah biru, atas pudar */
#tabelStokGudang thead th.sorting_desc .dt-sort-icon::after {
    border-top-color: #2563eb;
}
#tabelStokGudang thead th.sorting_desc .dt-sort-icon::before {
    border-bottom-color: #cbd5e1;
    opacity: .35;
}

/* Hover efek ringan di header sortable */
#tabelStokGudang thead th.sorting:hover,
#tabelStokGudang thead th.sorting_asc:hover,
#tabelStokGudang thead th.sorting_desc:hover {
    background: #f1f5f9;
    color: #334155;
}

/* ── BODY ROWS — zebra striping ── */
#tabelStokGudang tbody tr:nth-child(even) td {
    background: #f8fafc;
}
#tabelStokGudang tbody tr:nth-child(odd) td {
    background: #ffffff;
}

/* Hover row */
#tabelStokGudang tbody tr:hover td {
    background: #eff6ff !important;
    transition: background .08s;
}

/* Selected row (DataTables) */
#tabelStokGudang tbody tr.selected td {
    background: #dbeafe !important;
}

/* ── CELLS ── */
#tabelStokGudang tbody td {
    padding: 10px 13px;
    vertical-align: middle;
    color: #1e293b;
    border: none;
    border-bottom: 1px solid #f1f5f9;   /* garis pemisah tipis tiap baris */
}
#tabelStokGudang tbody tr:last-child td {
    border-bottom: none;
}

/* ── KOLOM ALIGNMENT ── */
#tabelStokGudang .col-center { text-align: center; }
#tabelStokGudang .col-right  { text-align: right; }
#tabelStokGudang .col-no     { text-align: center; width: 40px; color: #94a3b8; font-size: 11.5px; }

/* ═══════════════════════════════════════════
   STATUS BADGES
═══════════════════════════════════════════ */
.wh-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 999px;
    padding: 3px 10px 3px 8px;
    font-size: 11.5px;
    font-weight: 600;
    white-space: nowrap;
    height: 24px;
    border: 1px solid;
}
.wh-badge i { font-size: 10px; }
.wh-badge .wh-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}
.wh-badge.aman    { background: #ecfdf5; color: #15803d; border-color: #bbf7d0; }
.wh-badge.aman    .wh-dot { background: #16a34a; }
.wh-badge.hampir  { background: #fffbeb; color: #b45309; border-color: #fde68a; }
.wh-badge.hampir  .wh-dot { background: #d97706; }
.wh-badge.expired { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
.wh-badge.expired .wh-dot { background: #ef4444; }

/* ═══════════════════════════════════════════
   STOCK AGING BADGE
═══════════════════════════════════════════ */
.wh-badge-age {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 600;
    white-space: nowrap;
    height: 24px;
    border: 1px solid;
}
.wh-badge-age i { font-size: 10px; }
.wh-badge-age.normal  { background: #ecfdf5; color: #15803d; border-color: #bbf7d0; }
.wh-badge-age.warning { background: #fffbeb; color: #b45309; border-color: #fde68a; }
.wh-badge-age.danger  { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

/* ═══════════════════════════════════════════
   CATEGORY PILL
═══════════════════════════════════════════ */
.wh-categ {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 500;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #475569;
    white-space: nowrap;
}

/* ═══════════════════════════════════════════
   EXPIRY TEXT
═══════════════════════════════════════════ */
.wh-expired-ok   { color: #1e293b; font-weight: 500; }
.wh-expired-soon { color: #b45309; font-weight: 600; }
.wh-expired-over { color: #dc2626; font-weight: 700; }
.wh-expired-date { display: block; font-size: 11px; color: #94a3b8; margin-top: 2px; }

/* ═══════════════════════════════════════════
   ITEM NAME
═══════════════════════════════════════════ */
.wh-item-name { font-weight: 600; color: #0f172a; }
.wh-item-sub  { font-size: 11px; color: #94a3b8; margin-top: 1px; }

/* ═══════════════════════════════════════════
   STOCK & BERAT BAR (3-LAYER REDESIGN)
═══════════════════════════════════════════ */
.wh-stock-wrap {
    min-width: 140px;
    max-width: 220px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.wh-stok-row {
    display: flex;
    align-items: baseline;
    justify-content: flex-start;
    gap: 4px;
    font-size: 13px;
    line-height: 1.2;
}
.wh-stok-val {
    font-weight: 700;
    color: #0f172a;
}
.wh-stok-sat {
    font-weight: 500;
    font-size: 12px;
    color: #64748b;
}
.wh-stok-max {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 400;
}
.wh-bar-track {
    width: 100%;
    height: 7px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
    margin: 3px 0;
}
.wh-bar-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.3s ease;
}
.wh-bar-fill.green { background: #22c55e; }
.wh-bar-fill.amber { background: #f59e0b; }
.wh-bar-fill.red   { background: #ef4444; }

.wh-berat-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    font-size: 12px;
}
.wh-berat-val {
    font-weight: 600;
    color: #334155;
}
.wh-pill {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 11px;
    font-weight: 600;
    padding: 1px 7px;
    border-radius: 999px;
    line-height: 1.3;
    white-space: nowrap;
}
.wh-pill.pill-green {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}
.wh-pill.pill-amber {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
.wh-pill.pill-red {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

/* ═══════════════════════════════════════════
   ACTION BUTTONS
═══════════════════════════════════════════ */
.dm-action-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
}
.dm-btn-action {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    border: 1px solid;
    background: #fff;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s, background .1s;
    text-decoration: none;
    flex-shrink: 0;
}
.dm-btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.10);
}
.dm-btn-action.view { color: #0284c7; border-color: #bae6fd; }
.dm-btn-action.view:hover { background: #e0f2fe; }

/* ═══════════════════════════════════════════
   DATATABLES OVERRIDES
═══════════════════════════════════════════ */
/* Hapus outline bawaan DT saat klik header */
#tabelStokGudang thead th:focus { outline: none; }

/* Paginasi & info */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 7px !important;
    font-size: 12.5px !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #2563eb !important;
    border-color: #2563eb !important;
    color: #fff !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
    background: #f1f5f9 !important;
    border-color: #e2e8f0 !important;
    color: #1e293b !important;
}
.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #64748b;
}

/* ═══════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════ */
@media (max-width: 767px) {
    .dm-page { padding: 10px; }
    .dm-topbar { padding: 12px 14px; }
    .dm-topbar__title { font-size: 16px; }
    .dm-topbar__right { text-align: left; }
}
</style>

<div class="dm-page">

    <!-- Topbar -->
    <div class="dm-topbar">
        <div>
            <h1 class="dm-topbar__title">
                <i class="fa-solid fa-box"></i><?= esc($title) ?>
            </h1>
            <p class="dm-topbar__sub">
                <?= isset($subtitle) ? esc($subtitle) : 'Monitoring seluruh persediaan barang yang tersedia di gudang.' ?>
            </p>
        </div>
        <div class="dm-topbar__right">
            <div class="lbl">Update Terakhir</div>
            <div class="val"><?= date('d F Y') ?><br><?= date('H:i') ?> WIB</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="dm-filter-card">
        <form id="formFilterStok" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="filterSearch" class="form-label">Nama Barang</label>
                <input type="text" id="filterSearch" name="search" class="form-control"
                       placeholder="Cari barang...">
            </div>
            <div class="col-md-3">
                <label for="filterDonatur" class="form-label">Donatur / Asal Barang</label>
                <input type="text" id="filterDonatur" name="donatur" class="form-control"
                       placeholder="Cari donatur...">
            </div>
            <div class="col-md-2">
                <label for="filterKategori" class="form-label">Kategori</label>
                <select id="filterKategori" name="kategori" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= esc($k['nama_kategori']) ?>">
                            <?= esc($k['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="filterStatus" class="form-label">Status</label>
                <select id="filterStatus" name="status" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="Aman">Aman</option>
                    <option value="Hampir Expired">Hampir Expired</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label d-block" style="visibility:hidden;">-</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="dm-btn-filter flex-fill">
                        <i class="fa-solid fa-filter"></i> Terapkan
                    </button>
                    <button type="button" id="btnResetFilter" class="dm-btn-reset flex-shrink-0" style="width:36px;height:36px;padding:0;min-width:unset;">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Card -->
    <div class="dm-card">
        <div class="dm-table-wrap">
            <table id="tabelStokGudang" class="table mb-0" style="width:100%;">
                <thead>
                    <tr>
                        <th class="col-no text-center" style="width:40px;">No</th>
                        <th class="col-center text-center">
                            Status
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th style="min-width:150px;">
                            Donatur / Asal Barang
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th>
                            Kategori
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th class="col-center text-center">
                            Kedaluwarsa
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th class="col-center text-center">
                            Umur Barang
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th style="min-width:150px;">
                            Nama Barang
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th class="col-center text-center">
                            Kemasan
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th class="col-right text-end">
                            Berat / Kemasan
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th class="col-center text-center">
                            Kemasan Awal
                            <span class="dt-sort-icon" aria-hidden="true"></span>
                        </th>
                        <th style="min-width:130px;">
                            Stok &amp; Berat
                        </th>
                        <th style="min-width:200px;">Catatan</th>
                        <th class="col-center text-center" style="width:60px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- populated via AJAX -->
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

    var table = $('#tabelStokGudang').DataTable({
        processing : true,
        serverSide : true,
        ajax: {
            url  : '<?= site_url('transaksi/stok-gudang/ajaxData') ?>',
            type : 'POST',
            data : function (d) {
                d[csrfName]    = csrfHash;
                d.kategori     = $('#filterKategori').val();
                d.status       = $('#filterStatus').val();
                d.donatur      = $('#filterDonatur').val();
                d.search.value = $('#filterSearch').val();
            }
        },
        drawCallback: function (settings) {
            var json = settings.json;
            if (json && json[csrfName]) csrfHash = json[csrfName];

            // Injeksi ulang .dt-sort-icon setelah setiap draw
            // (DataTables kadang rebuild thead)
            injectSortIcons();
        },
        language: {
            emptyTable    : 'Tidak ada data',
            info          : 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty     : 'Menampilkan 0 data',
            infoFiltered  : '(disaring dari _MAX_ total data)',
            lengthMenu    : 'Tampilkan _MENU_ data',
            loadingRecords: 'Memuat...',
            processing    : 'Memproses...',
            search        : 'Cari:',
            zeroRecords   : 'Tidak ditemukan data yang sesuai',
            paginate: {
                first    : 'Pertama',
                last     : 'Terakhir',
                next     : 'Selanjutnya',
                previous : 'Sebelumnya'
            }
        },
        order      : [[4, 'asc']],   // default: Kedaluwarsa ASC
        autoWidth  : false,
        columnDefs : [
            { orderable: false, targets: [0, 10, 11, 12] },
            { className: 'col-center text-center', targets: [1, 4, 5, 7, 9, 12] },
            { className: 'col-right text-end',     targets: [8] }
        ],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 px-3 pt-3"l>'
           + '<"dm-table-wrap"rt>'
           + '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 px-3 pb-3"ip>'
    });

    // Inject span .dt-sort-icon ke setiap th sortable
    function injectSortIcons() {
        $('#tabelStokGudang thead th.sorting, #tabelStokGudang thead th.sorting_asc, #tabelStokGudang thead th.sorting_desc').each(function () {
            if (!$(this).find('.dt-sort-icon').length) {
                $(this).append('<span class="dt-sort-icon" aria-hidden="true"></span>');
            }
        });
    }

    // Panggil sekali saat init
    table.on('init', function () {
        injectSortIcons();
    });

    // Apply filters
    $('#formFilterStok').on('submit', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // Reset filters
    $('#btnResetFilter').on('click', function () {
        $('#formFilterStok')[0].reset();
        table.ajax.reload();
    });

});
</script>
<?= $this->endSection() ?>