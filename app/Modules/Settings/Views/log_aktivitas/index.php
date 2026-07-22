<?php
    $totalLog    = $kpi['totalLog'] ?? 0;
    $countLogin  = $kpi['countLogin'] ?? 0;
    $countPenyaluran = $kpi['countPenyaluran'] ?? 0;
    $countDonasi = $kpi['countDonasi'] ?? 0;
    $countPenyesuaian = $kpi['countPenyesuaian'] ?? 0;
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    :root {
        --wh-bg: #F8FAFC;
        --wh-card: #FFFFFF;
        --wh-border: #E5E7EB;
        --wh-separator: #EEF2F7;
        --wh-primary: #2563EB;
        --wh-primary-soft: #EFF6FF;
        --wh-success-soft: #ECFDF5;
        --wh-warning-soft: #FEF3C7;
        --wh-danger-soft: #FEF2F2;
        --wh-purple-soft: #F5F3FF;
        --wh-cyan-soft: #ECFEFF;
        --wh-dark-soft: #F3F4F6;
        --wh-text: #111827;
        --wh-text-soft: #6B7280;
    }

    .wh-page {
        background: var(--wh-bg);
        margin: -1.5rem -1.5rem 0 -1.5rem;
        padding: 16px 20px 32px 20px;
        min-height: 100vh;
    }

    /* ---------- Header ---------- */
    .wh-header {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 14px; padding: 18px 22px;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 16px;
    }
    .wh-header h1 { font-size: 1rem; font-weight: 700; color: var(--wh-text); margin: 0 0 2px 0; }
    .wh-header p  { margin: 0; font-size: 0.75rem; color: var(--wh-text-soft); }

    /* ---------- Filter ---------- */
    .wh-filter-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 14px; padding: 16px 18px; margin-bottom: 12px;
    }
    .wh-filter-card .form-label { font-size: 0.7rem; font-weight: 600; color: var(--wh-text); margin-bottom: 5px; }
    .wh-filter-card .form-control,
    .wh-filter-card .form-select {
        height: 36px; border-radius: 8px; border: 1px solid var(--wh-border);
        font-size: 0.78rem; padding: 0.375rem 0.65rem;
    }
    .wh-filter-card .form-control:focus,
    .wh-filter-card .form-select:focus {
        border-color: var(--wh-primary); box-shadow: 0 0 0 3px rgba(37,99,235,.12);
    }
    .wh-btn-primary {
        background: var(--wh-primary); color: #fff;
        height: 36px; border-radius: 9px; font-size: 0.78rem; font-weight: 600;
        padding: 0 16px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; cursor: pointer; border: none;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:active { transform: scale(0.98); }
    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 36px; border-radius: 9px; font-size: 0.78rem; font-weight: 600;
        padding: 0 14px; display: inline-flex; align-items: center; gap: 6px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-outline:active { transform: scale(0.98); }

    /* KPI Dashboard */
    .kpi-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }
    .kpi-card {
        background: #fff;
        border: 1px solid var(--wh-border);
        border-radius: 12px;
        padding: 12px 14px;
        text-align: center;
    }
    .kpi-card .kpi-val {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--wh-text);
        line-height: 1.2;
    }
    .kpi-card .kpi-lbl {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--wh-text-soft);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-top: 3px;
    }

    /* Colors */
    .mod-donasi { color: #16A34A; }
    .mod-penyaluran { color: #2563EB; }
    .mod-penyesuaian { color: #9333EA; }
    .mod-user { color: #EA580C; }
    .mod-login { color: #1F2937; }

    .bg-donasi { background: #DCFCE7; color: #16A34A; }
    .bg-penyaluran { background: #DBEAFE; color: #2563EB; }
    .bg-penyesuaian { background: #F3E8FF; color: #9333EA; }
    .bg-user { background: #FFEDD5; color: #EA580C; }
    .bg-login { background: #F3F4F6; color: #1F2937; }

    /* Table Toolbar */
    .wh-dt-toolbar {
        background: #fff; border: 1px solid var(--wh-border); border-bottom: none;
        border-radius: 14px 14px 0 0; padding: 12px 16px;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;
    }
    .wh-dt-toolbar .dt-length-wrap { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: var(--wh-text-soft); }
    .wh-dt-toolbar select { width: 80px; height: 34px; border-radius: 8px; border: 1px solid var(--wh-border); padding: 0 6px; font-size: 0.75rem; }
    .wh-dt-search { position: relative; }
    .wh-dt-search i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--wh-text-soft); font-size: 13px; }
    .wh-dt-search input { height: 34px; width: 220px; border-radius: 8px; border: 1px solid var(--wh-border); padding: 0 10px 0 30px; font-size: 0.78rem; }

    /* Bottom Bar DataTables */
    .wh-dt-bottom {
        background: #fff; border: 1px solid var(--wh-border); border-top: none;
        border-radius: 0 0 14px 14px; padding: 12px 16px;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;
    }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { display: none; }
    .dataTables_wrapper .dataTables_info { font-size: 0.75rem; color: var(--wh-text-soft); }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        width: 30px; height: 30px; border-radius: 7px !important; padding: 0 !important; margin-left: 3px;
        display: inline-flex !important; align-items: center; justify-content: center;
        border: 1px solid var(--wh-border) !important; background: #fff !important; color: var(--wh-text) !important; font-size: 0.75rem; transition: background .12s;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--wh-primary) !important; color: #fff !important; border-color: var(--wh-primary) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background: var(--wh-primary-soft) !important; border-color: #BFDBFE !important; color: var(--wh-primary) !important;
    }

    /* Timeline Cards */
    #dataTable { border: none !important; margin: 0 !important; }
    #dataTable thead { display: none; }
    #dataTable tbody tr { background: transparent !important; }
    #dataTable tbody td { border: none !important; padding: 0 0 8px 0 !important; }
    #dataTable.dataTable.no-footer { border-bottom: none; }

    .timeline-card {
        background: #F8FAFC;
        border: 1px solid var(--wh-border);
        border-radius: 12px;
        padding: 13px 16px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .timeline-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(15,23,42,0.07);
        border-color: #CBD5E1;
        background: #fff;
    }

    .tl-time {
        width: 105px;
        flex-shrink: 0;
        text-align: right;
        position: relative;
    }
    .tl-time::after {
        content: '';
        position: absolute;
        right: -26px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #E2E8F0;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #CBD5E1;
    }
    .timeline-card:hover .tl-time::after { background: var(--wh-primary); box-shadow: 0 0 0 1px var(--wh-primary); }
    .tl-date { font-weight: 700; font-size: 0.78rem; color: var(--wh-text); }
    .tl-hour { font-size: 0.7rem; color: var(--wh-text-soft); font-weight: 500; margin-top: 1px; }

    .tl-content {
        flex-grow: 1;
        padding-left: 14px;
        border-left: 2px solid #F1F5F9;
        display: flex;
        gap: 16px;
    }

    .tl-user {
        width: 140px;
        flex-shrink: 0;
        display: flex;
        gap: 8px;
        align-items: flex-start;
    }
    .tl-avatar {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: var(--wh-primary-soft);
        color: var(--wh-primary);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.78rem;
        flex-shrink: 0;
    }
    .tl-uname { font-weight: 700; font-size: 0.8rem; color: var(--wh-text); }
    .tl-urole { font-size: 0.68rem; color: var(--wh-text-soft); font-weight: 500; margin-top: 1px; }

    .tl-module {
        width: 160px;
        flex-shrink: 0;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .tl-icon { font-size: 1.1rem; margin-top: 1px; }
    .tl-mod-name { font-weight: 700; font-size: 0.8rem; color: var(--wh-text); }
    .tl-action { font-size: 0.63rem; font-weight: 700; padding: 2px 6px; border-radius: 5px; margin-top: 4px; display: inline-block; }

    .tl-desc {
        flex-grow: 1;
        font-size: 0.78rem;
        line-height: 1.55;
        color: #334155;
    }

    /* Drawer */
    .drawer-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(15,23,42,0.4); z-index: 1040;
        opacity: 0; visibility: hidden; transition: opacity 0.3s;
    }
    .drawer-overlay.show { opacity: 1; visibility: visible; }

    .detail-drawer {
        position: fixed; top: 0; right: -400px; width: 400px; height: 100vh;
        background: #fff; z-index: 1050; box-shadow: -8px 0 30px rgba(0,0,0,0.1);
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex; flex-direction: column;
    }
    .detail-drawer.show { right: 0; }
    .drawer-header { padding: 18px 20px; border-bottom: 1px solid var(--wh-border); display: flex; justify-content: space-between; align-items: center; }
    .drawer-header h3 { margin: 0; font-size: 1rem; font-weight: 800; color: var(--wh-text); }
    .drawer-close { background: #F1F5F9; border: none; width: 28px; height: 28px; border-radius: 7px; font-size: 0.9rem; cursor: pointer; color: var(--wh-text-soft); transition: background 0.2s; }
    .drawer-close:hover { background: #E2E8F0; color: var(--wh-text); }
    .drawer-body { padding: 20px; overflow-y: auto; flex-grow: 1; }
    .drawer-item { margin-bottom: 18px; }
    .drawer-lbl { font-size: 0.68rem; font-weight: 700; color: var(--wh-text-soft); text-transform: uppercase; margin-bottom: 5px; letter-spacing: 0.04em; }
    .drawer-val { font-size: 0.88rem; color: var(--wh-text); font-weight: 500; line-height: 1.6; }

    .drawer-action-btn {
        margin-top: 20px; width: 100%; padding: 12px; border-radius: 10px;
        background: var(--wh-primary-soft); color: var(--wh-primary);
        border: 1px solid #BFDBFE; font-weight: 700; font-size: 0.85rem; text-align: center;
        text-decoration: none; display: inline-block; transition: background 0.2s, color 0.2s;
    }
    .drawer-action-btn:hover { background: var(--wh-primary); color: #fff; }

    @media (max-width: 992px) {
        .tl-content { flex-direction: column; gap: 12px; }
        .tl-user, .tl-module { width: 100%; }
        .kpi-container { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .kpi-container { grid-template-columns: 1fr; }
        .timeline-card { flex-direction: column; gap: 10px; padding: 12px 14px; }
        .tl-time { text-align: left; width: 100%; }
        .tl-time::after { display: none; }
        .tl-content { border-left: none; padding-left: 0; }
        .detail-drawer { width: 100%; right: -100%; }
    }
</style>

<div class="wh-page">
    <div class="wh-header">
        <div>
            <h1>Log Aktivitas (Audit Trail)</h1>
            <p>Pantau seluruh riwayat aktivitas operasional dalam gudang.</p>
        </div>
    </div>

    <!-- Mini Dashboard KPI -->
    <div class="kpi-container">
        <div class="kpi-card">
            <div class="kpi-val"><?= $totalLog ?></div>
            <div class="kpi-lbl">Total Aktivitas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val"><?= $countLogin ?></div>
            <div class="kpi-lbl">Login</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val"><?= $countPenyaluran ?></div>
            <div class="kpi-lbl">Penyaluran</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val"><?= $countDonasi ?></div>
            <div class="kpi-lbl">Donasi Masuk</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val"><?= $countPenyesuaian ?></div>
            <div class="kpi-lbl">Penyesuaian</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="wh-filter-card">
        <form action="" method="get" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="<?= esc($tanggal_mulai) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="<?= esc($tanggal_selesai) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Modul</label>
                <select name="modul" class="form-select">
                    <option value="">Semua Modul</option>
                    <?php foreach ($moduls as $m): ?>
                        <option value="<?= $m ?>" <?= ($filter_modul == $m) ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Aktivitas</label>
                <select name="aktivitas" class="form-select">
                    <option value="">Semua Aktivitas</option>
                    <option value="Login" <?= ($filter_aktivitas == 'Login') ? 'selected' : '' ?>>Login</option>
                    <option value="Tambah" <?= ($filter_aktivitas == 'Tambah') ? 'selected' : '' ?>>Tambah</option>
                    <option value="Edit" <?= ($filter_aktivitas == 'Edit') ? 'selected' : '' ?>>Edit</option>
                    <option value="Hapus" <?= ($filter_aktivitas == 'Hapus') ? 'selected' : '' ?>>Hapus</option>
                    <option value="Reset" <?= ($filter_aktivitas == 'Reset') ? 'selected' : '' ?>>Reset Password</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Role User</label>
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r->name ?>" <?= ($filter_role == $r->name) ? 'selected' : '' ?>><?= ucfirst($r->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="wh-btn-primary w-100 justify-content-center"><i class="fa-solid fa-filter"></i> Terapkan</button>
                <a href="<?= site_url('pengaturan/log-aktivitas') ?>" class="wh-btn-outline px-3" title="Reset Filter"><i class="fa-solid fa-rotate-right"></i></a>
            </div>
        </form>
    </div>

    <!-- Table Toolbar (DataTables Dom) -->
    <div class="wh-dt-toolbar">
        <div class="dt-length-wrap">
            Tampilkan
            <select id="dtLengthSelect">
                <option value="10">10</option>
                <option value="25" selected>25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            data
        </div>
        <div class="wh-dt-search">
            <i class="fa-solid fa-search"></i>
            <input type="text" id="dtSearchInput" placeholder="Cari aktivitas atau user..." value="<?= esc($search) ?>">
        </div>
    </div>

    <!-- Timeline Wrapper -->
    <div style="background: transparent;">
        <table id="dataTable" class="w-100 m-0">
            <thead><tr><th>Log</th></tr></thead>
            <tbody>
                    <!-- DataTables will populate this tbody via AJAX -->
            </tbody>
        </table>
    </div>

</div>

<!-- Drawer Elements -->
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="detail-drawer" id="detailDrawer">
    <div class="drawer-header">
        <h3 id="drwTitle">Ringkasan Aktivitas</h3>
        <button class="drawer-close" onclick="closeDrawer()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="drawer-body">
        <div class="drawer-item">
            <div class="drawer-lbl">Modul</div>
            <div class="drawer-val" id="drwModul">-</div>
        </div>
        <div class="drawer-item">
            <div class="drawer-lbl">Aksi</div>
            <div class="drawer-val" id="drwAksi">-</div>
        </div>
        <div class="drawer-item">
            <div class="drawer-lbl">Waktu</div>
            <div class="drawer-val" id="drwTanggal">-</div>
        </div>
        <div class="drawer-item">
            <div class="drawer-lbl">Operator</div>
            <div class="drawer-val" id="drwOperator">-</div>
        </div>
        <div class="drawer-item">
            <div class="drawer-lbl">Detail & Keterangan</div>
            <div class="drawer-val" id="drwDeskripsi" style="background:#F8FAFC; padding:16px; border-radius:12px; border:1px solid var(--wh-border); margin-top:8px;">-</div>
        </div>
        
        <div id="drwActionContainer" style="display:none; margin-top:32px;">
            <a href="#" id="drwActionBtn" class="drawer-action-btn">Lihat Halaman Transaksi <i class="fa-solid fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openDrawer(element) {
    const data = JSON.parse(element.getAttribute('data-info'));
    
    document.getElementById('drwModul').innerText = data.modul;
    document.getElementById('drwAksi').innerText = data.aksi;
    document.getElementById('drwTanggal').innerText = data.tanggal;
    document.getElementById('drwOperator').innerText = data.operator;
    
    // Parse deskripsi back to HTML
    document.getElementById('drwDeskripsi').innerHTML = data.deskripsi.replace(/\n/g, "<br>");
    
    // Action Button Logic
    const btn = document.getElementById('drwActionBtn');
    const container = document.getElementById('drwActionContainer');
    
    // Try to extract Transaction Number (e.g. BM-2026..., BK-2026..., ADJ-2026...)
    let trxNo = '';
    const lines = data.deskripsi.split('\n');
    for(let line of lines) {
        if(line.includes('No.')) {
            trxNo = line.replace('No.', '').trim();
            break;
        }
    }
    
    if (trxNo !== '') {
        container.style.display = 'block';
        if (trxNo.startsWith('BM-')) {
            btn.href = '<?= site_url('transaksi/barang-masuk') ?>'; 
        } else if (trxNo.startsWith('BK-')) {
            btn.href = '<?= site_url('transaksi/barang-keluar') ?>';
        } else if (trxNo.startsWith('ADJ-')) {
            btn.href = '<?= site_url('transaksi/penyesuaian') ?>';
        } else {
            container.style.display = 'none';
        }
    } else {
        container.style.display = 'none';
    }

    document.getElementById('drawerOverlay').classList.add('show');
    document.getElementById('detailDrawer').classList.add('show');
}

function closeDrawer() {
    document.getElementById('drawerOverlay').classList.remove('show');
    document.getElementById('detailDrawer').classList.remove('show');
}

$(document).ready(function() {
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('log-aktivitas/ajaxData') ?>",
            type: "POST",
            data: function (d) {
                d[csrfName] = csrfHash;
                d.tanggal_mulai = '<?= esc($tanggal_mulai) ?>';
                d.tanggal_selesai = '<?= esc($tanggal_selesai) ?>';
                d.modul = '<?= esc($filter_modul) ?>';
                d.aktivitas = '<?= esc($filter_aktivitas) ?>';
                d.role = '<?= esc($filter_role) ?>';
            }
        },
        drawCallback: function (settings) {
            var response = settings.json;
            if (response && response[csrfName]) {
                csrfHash = response[csrfName];
            }
        },
        order: [],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        pageLength: 25,
        dom: 'rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4"ip>', 
        columnDefs: [{ orderable: false, targets: [0] }]
    });

    // Custom Length
    $('#dtLengthSelect').on('change', function () {
        table.page.len(parseInt(this.value)).draw();
    });

    // Custom Search
    $('#dtSearchInput').on('keyup input', function () {
        table.search(this.value).draw();
    });
});
</script>
<?= $this->endSection() ?>