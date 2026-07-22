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

/* ═══════════════════════════════════════════════
   FILTER CARD
═══════════════════════════════════════════════ */
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
.dm-btn-filter {
    height: 36px;
    padding: 0 18px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    background: #2563eb;
    border: 1px solid #2563eb;
    color: #fff;
    transition: background .12s;
}
.dm-btn-filter:hover { background: #1d4ed8; color: #fff; }
.dm-btn-reset {
    height: 36px;
    padding: 0 14px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    background: #fff;
    border: 1px solid #e5e7eb;
    color: #374151;
    text-decoration: none;
    transition: background .12s;
}
.dm-btn-reset:hover { background: #f3f4f6; color: #374151; }

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
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 12px;
}


/* ═══════════════════════════════════════════════
   TABLE
═══════════════════════════════════════════════ */
#tabelStokGudang {
    width: 100% !important;
    border-collapse: collapse;
    font-size: 13px;
}
#tabelStokGudang thead th {
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
#tabelStokGudang thead th:first-child { border-left: 1px solid #e5e7eb; }
#tabelStokGudang thead th:last-child  { border-right: 1px solid #e5e7eb; }

#tabelStokGudang tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background .1s;
}
#tabelStokGudang tbody tr:hover { background: #f8fafc; }
#tabelStokGudang tbody td {
    padding: 10px 12px;
    vertical-align: middle;
    color: #1e293b;
    font-size: 13px;
    border: none;
    border-bottom: 1px solid #f1f5f9;
}

/* ═══════════════════════════════════════════════
   STATUS BADGES
═══════════════════════════════════════════════ */
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
}
.wh-badge i { font-size: 10px; }
.wh-badge.aman    { background: #ecfdf5; color: #15803d; }
.wh-badge.hampir  { background: #fffbeb; color: #b45309; }
.wh-badge.expired { background: #fef2f2; color: #ef4444; }
.wh-badge.default { background: #f3f4f6; color: #6b7280; }

/* ═══════════════════════════════════════════════
   EXPIRED INDICATOR
═══════════════════════════════════════════════ */
.wh-expired-ok   { color: #1e293b; font-weight: 500; }
.wh-expired-soon { color: #b45309; font-weight: 600; }
.wh-expired-over { color: #dc2626; font-weight: 700; }
.wh-expired-date { display: block; font-size: 11px; color: #6b7280; margin-top: 1px; }

/* ═══════════════════════════════════════════════
   STOCK BAR
═══════════════════════════════════════════════ */
.wh-stock-wrap  { min-width: 110px; }
.wh-stock-value { font-weight: 700; font-size: 13px; color: #0f172a; }
.wh-progress {
    width: 100%;
    height: 5px;
    border-radius: 999px;
    background: #f3f4f6;
    overflow: hidden;
    margin-top: 4px;
}
.wh-progress > span { display: block; height: 100%; border-radius: 999px; }
.wh-progress.green > span { background: #22c55e; }
.wh-progress.amber > span { background: #f59e0b; }
.wh-progress.red   > span { background: #ef4444; }

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
.dm-btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.10);
}
.dm-btn-action.view { color: #0284c7; border-color: #bae6fd; }
.dm-btn-action.view:hover { background: #e0f2fe; }

/* ═══════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════ */
.dm-empty {
    text-align: center;
    padding: 60px 20px;
}
.dm-empty i {
    font-size: 2.5rem;
    color: #e5e7eb;
}
.dm-empty p { margin-top: 14px; color: #0f172a; font-weight: 600; }
.dm-empty small { color: #6b7280; font-size: 13px; }

/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
@media (max-width: 767px) {
    .dm-page { padding: 10px 10px; }
    .dm-topbar { padding: 12px 14px; }
    .dm-topbar__title { font-size: 16px; }
    .dm-topbar__right { text-align: left; }
    .dataTables_wrapper .dataTables_filter input { width: 160px; }
}
</style>

<div class="dm-page">

<!-- ── Topbar ── -->
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

<!-- ── Filter Panel ── -->
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
            <div class="d-flex gap-2">
                <button type="submit" class="dm-btn-filter flex-fill">
                    <i class="fa-solid fa-filter"></i> Terapkan
                </button>
                <button type="button" id="btnResetFilter" class="dm-btn-reset">Reset</button>
            </div>
        </div>
    </form>
</div>

<!-- ── Data Card ── -->
<div class="dm-card">
    <table class="table mb-0" id="tabelStokGudang" style="width:100%;">
            <thead>
                <tr>
                    <th class="text-center" style="width:40px;">No</th>
                    <th class="text-center">Status</th>
                    <th style="min-width:140px;">Donatur / Asal Barang</th>
                    <th>Kategori</th>
                    <th class="text-center">Kedaluwarsa</th>
                    <th style="min-width:140px;">Nama Barang</th>
                    <th class="text-center">Kemasan</th>
                    <th class="text-end">Berat / Kemasan</th>
                    <th class="text-center">Kemasan Awal</th>
                    <th style="min-width:120px;">Stok &amp; Berat</th>
                    <th>Catatan</th>
                    <th class="text-center" style="width:60px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate this tbody via AJAX -->
            </tbody>
    </table>
</div>

</div><!-- /.dm-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var csrfName = '<?= csrf_token() ?>';
var csrfHash = '<?= csrf_hash() ?>';

$(document).ready(function () {
    var table = $('#tabelStokGudang').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('transaksi/stok-gudang/ajaxData') ?>",
            type: "POST",
            data: function (d) {
                d[csrfName] = csrfHash;
                d.kategori  = $('#filterKategori').val();
                d.status    = $('#filterStatus').val();
                d.donatur   = $('#filterDonatur').val();
                // Override default search with our own custom search input
                d.search.value = $('#filterSearch').val();
            }
        },
        drawCallback: function (settings) {
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
        order: [[4, 'asc']], // Default order Kedaluwarsa ASC
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [0, 11] },
            { className: "text-center", targets: [0, 1, 4, 6, 8, 11] },
            { className: "text-end", targets: [7] }
        ],
        // Hide default DataTables search box since we use custom one
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"l><"table-responsive"rt><"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
    });

    // Handle Custom Filters Form Submission
    $('#formFilterStok').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // Reset Filters
    $('#btnResetFilter').on('click', function() {
        $('#formFilterStok')[0].reset();
        table.ajax.reload();
    });
});
</script>
<?= $this->endSection() ?>