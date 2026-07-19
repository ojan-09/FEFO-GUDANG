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
        padding: 20px 24px 40px 24px;
    }

    /* ---------- Header ---------- */
    .wh-header {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 18px; padding: 28px;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 16px; margin-bottom: 20px;
    }
    .wh-header h1 { font-size: 1.35rem; font-weight: 700; color: var(--wh-text); margin: 0 0 4px 0; }
    .wh-header p  { margin: 0; font-size: 0.85rem; color: var(--wh-text-soft); }
    .wh-total-badge {
        background: var(--wh-primary-soft); border: 1px solid #BFDBFE;
        border-radius: 12px; padding: 10px 16px; text-align: right;
    }
    .wh-total-badge .lbl { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--wh-primary); }
    .wh-total-badge .val { font-size: 1.25rem; font-weight: 700; color: var(--wh-primary); }

    /* ---------- Filter ---------- */
    .wh-filter-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 18px; padding: 24px; margin-bottom: 16px;
    }
    .wh-filter-card .form-label { font-size: 0.78rem; font-weight: 600; color: var(--wh-text); margin-bottom: 6px; }
    .wh-filter-card .form-control,
    .wh-filter-card .form-select {
        height: 44px; border-radius: 10px; border: 1px solid var(--wh-border);
        font-size: 0.85rem; padding: 0.5rem 0.75rem;
    }
    .wh-filter-card .form-control:focus,
    .wh-filter-card .form-select:focus {
        border-color: var(--wh-primary); box-shadow: 0 0 0 3px rgba(37,99,235,.15);
    }
    .wh-btn-primary {
        background: var(--wh-primary); border: 1px solid var(--wh-primary); color: #fff;
        height: 44px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;
        padding: 0 22px; display: inline-flex; align-items: center; gap: 7px;
        transition: transform 120ms ease, background 120ms ease; cursor: pointer;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-primary:active { transform: scale(0.98); }
    .wh-btn-outline {
        background: #fff; border: 1px solid var(--wh-border); color: var(--wh-text);
        height: 44px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;
        padding: 0 18px; display: inline-flex; align-items: center; gap: 7px;
        transition: transform 120ms ease, background 120ms ease; text-decoration: none;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }
    .wh-btn-outline:active { transform: scale(0.98); }

    /* ---------- Mini KPI ---------- */
    .wh-kpi-row {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 14px; margin-bottom: 16px;
    }
    .wh-kpi-mini {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 16px; padding: 14px 18px; height: 90px;
        display: flex; flex-direction: column; justify-content: space-between;
        transition: transform 180ms ease, box-shadow 180ms ease;
    }
    .wh-kpi-mini:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,24,40,.08); }
    .wh-kpi-mini .k-label { font-size: 0.72rem; font-weight: 600; color: var(--wh-text-soft); text-transform: uppercase; letter-spacing: 0.04em; }
    .wh-kpi-mini .k-value { font-size: 1.3rem; font-weight: 700; color: var(--wh-text); }
    .wh-kpi-mini .k-icon  { font-size: 0.85rem; color: var(--wh-text-soft); }

    /* ---------- Table Card ---------- */
    .wh-table-card {
        background: var(--wh-card); border: 1px solid var(--wh-border);
        border-radius: 18px; box-shadow: 0 4px 20px rgba(15,23,42,.05);
        overflow: hidden; padding: 0;
    }

    /* Custom Toolbar */
    .wh-dt-toolbar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; padding: 16px 20px;
        border-bottom: 1px solid var(--wh-separator);
    }
    .wh-dt-toolbar .dt-length-wrap {
        display: flex; align-items: center; gap: 8px;
        font-size: 0.82rem; color: var(--wh-text-soft);
    }
    .wh-dt-toolbar .dt-length-wrap select {
        width: 80px; height: 42px; border-radius: 10px;
        border: 1px solid var(--wh-border); font-size: 0.82rem; padding: 0 8px;
    }
    .wh-dt-search { position: relative; }
    .wh-dt-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--wh-text-soft); font-size: 0.82rem; }
    .wh-dt-search input {
        height: 42px; width: 260px; border-radius: 10px;
        border: 1px solid var(--wh-border); font-size: 0.85rem;
        padding: 0 12px 0 34px; color: var(--wh-text);
    }
    .wh-dt-search input:focus { outline: none; border-color: var(--wh-primary); box-shadow: 0 0 0 3px rgba(37,99,235,.15); }

    /* Table */
    #dataTable { border-collapse: separate; border-spacing: 0; width: 100%; }
    #dataTable thead th {
        background: var(--wh-bg); color: var(--wh-text-soft);
        font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;
        padding: 0 20px; height: 54px;
        border-bottom: 1px solid var(--wh-separator); border-top: none; white-space: nowrap;
    }
    #dataTable tbody td {
        padding: 0 20px; height: 60px; vertical-align: middle;
        border-bottom: 1px solid var(--wh-separator); border-top: none;
        font-size: 0.83rem; color: var(--wh-text);
    }
    #dataTable, #dataTable th, #dataTable td { border-left: none; border-right: none; }
    #dataTable tbody tr { transition: background 150ms ease; }
    #dataTable tbody tr:hover { background: #F9FAFB; }
    #dataTable tbody tr:last-child td { border-bottom: none; }

    /* Avatar */
    .wh-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.82rem; font-weight: 700; color: #fff; flex-shrink: 0;
        background: linear-gradient(135deg, #2563EB, #7C3AED);
    }
    .wh-user-cell { display: flex; align-items: center; gap: 12px; }
    .wh-user-cell .name  { font-weight: 600; font-size: 0.85rem; color: var(--wh-text); }
    .wh-user-cell .role  { font-size: 0.73rem; color: var(--wh-text-soft); margin-top: 1px; }

    /* Badge role */
    .wh-role-badge {
        display: inline-flex; align-items: center; border-radius: 999px;
        padding: 4px 10px; font-size: 0.7rem; font-weight: 600; white-space: nowrap;
    }
    .wh-role-badge.admin      { background: #EEF2FF; color: #4338CA; }
    .wh-role-badge.petugas    { background: #ECFDF5; color: #047857; }
    .wh-role-badge.supervisor { background: var(--wh-warning-soft); color: #B45309; }
    .wh-role-badge.default    { background: var(--wh-dark-soft); color: var(--wh-text-soft); }

    /* Modul cell */
    .wh-modul-cell { display: flex; align-items: flex-start; gap: 10px; }
    .wh-modul-icon {
        width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 0.75rem;
    }
    .wh-modul-icon.user    { background: var(--wh-primary-soft); color: var(--wh-primary); }
    .wh-modul-icon.package { background: var(--wh-success-soft); color: #047857; }
    .wh-modul-icon.report  { background: var(--wh-cyan-soft); color: #0891B2; }
    .wh-modul-icon.auth    { background: var(--wh-purple-soft); color: #6D28D9; }
    .wh-modul-icon.system  { background: var(--wh-warning-soft); color: #B45309; }
    .wh-modul-icon.default { background: var(--wh-dark-soft); color: var(--wh-text-soft); }
    .wh-modul-name   { font-weight: 600; font-size: 0.83rem; color: var(--wh-text); }
    .wh-modul-action { font-size: 0.75rem; color: #64748B; margin-top: 2px; }

    /* Activity badge */
    .wh-act-badge {
        display: inline-flex; align-items: center; gap: 4px;
        border-radius: 999px; padding: 3px 9px;
        font-size: 0.68rem; font-weight: 600; white-space: nowrap;
    }
    .wh-act-badge i { font-size: 0.55rem; }
    .act-login  { background: var(--wh-success-soft); color: #15803D; }
    .act-tambah { background: var(--wh-primary-soft); color: #1D4ED8; }
    .act-edit   { background: var(--wh-warning-soft); color: #B45309; }
    .act-delete { background: var(--wh-danger-soft);  color: #B91C1C; }
    .act-reset  { background: var(--wh-purple-soft);  color: #6D28D9; }
    .act-export { background: var(--wh-cyan-soft);    color: #0891B2; }
    .act-other  { background: var(--wh-dark-soft);    color: var(--wh-text-soft); }

    /* Desc truncate */
    .wh-desc {
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; font-size: 0.8rem; color: var(--wh-text-soft);
        max-width: 320px; cursor: default;
    }

    /* Time cell */
    .wh-time-date { font-weight: 600; font-size: 0.83rem; color: var(--wh-text); }
    .wh-time-hour { font-size: 0.75rem; color: #64748B; margin-top: 2px; }

    /* DT bottom */
    .wh-dt-bottom {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; padding: 14px 20px;
        border-top: 1px solid var(--wh-separator);
        font-size: 0.82rem; color: var(--wh-text-soft);
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { display: none; }
    .dataTables_wrapper .dataTables_info { font-size: 0.82rem; color: var(--wh-text-soft); }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        width: 40px; height: 40px; border-radius: 10px !important;
        padding: 0 !important; margin-left: 3px;
        display: inline-flex !important; align-items: center; justify-content: center;
        border: 1px solid transparent !important; background: transparent !important;
        color: var(--wh-text) !important; font-size: 0.82rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--wh-primary) !important; color: #fff !important; border-color: var(--wh-primary) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background: var(--wh-primary-soft) !important; color: var(--wh-primary) !important;
    }

    @media (max-width: 1024px) { .wh-kpi-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px)  {
        .wh-kpi-row { grid-template-columns: 1fr; }
        .wh-header  { flex-direction: column; align-items: flex-start; }
        .wh-dt-toolbar { flex-direction: column; align-items: flex-start; }
        .wh-dt-search input { width: 100%; }
        #dataTable .wh-desc { max-width: 180px; }
    }
</style>

<?php
    // Hitung KPI mini dari array $logs (view-level aggregation, no new queries)
    $totalLog    = count($logs);
    $today       = date('Y-m-d');
    $weekStart   = date('Y-m-d', strtotime('monday this week'));
    $monthStart  = date('Y-m-01');
    $countToday  = 0; $countWeek = 0; $countMonth = 0;
    foreach ($logs as $l) {
        $d = substr($l['created_at'], 0, 10);
        if ($d === $today)               $countToday++;
        if ($d >= $weekStart)            $countWeek++;
        if ($d >= $monthStart)           $countMonth++;
    }
?>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1>Log Aktivitas</h1>
            <p>Audit trail seluruh aktivitas pengguna pada sistem Warehouse Management.</p>
        </div>
        <div class="wh-total-badge">
            <div class="lbl">Total Log</div>
            <div class="val"><?= number_format($totalLog, 0, ',', '.') ?> Aktivitas</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="wh-filter-card">
        <form method="GET" action="<?= site_url('log-aktivitas') ?>" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" name="tanggal_mulai" value="<?= esc($tanggal_mulai) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" name="tanggal_selesai" value="<?= esc($tanggal_selesai) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Modul</label>
                <select class="form-select" name="modul">
                    <option value="">Semua Modul</option>
                    <?php foreach ($moduls as $m): ?>
                        <option value="<?= $m ?>" <?= $filter_modul == $m ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Role</label>
                <select class="form-select" name="role">
                    <option value="">Semua Role</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r->name ?>" <?= $filter_role == $r->name ? 'selected' : '' ?>><?= $r->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Pencarian</label>
                <input type="text" class="form-control" name="search" placeholder="Cari nama, deskripsi..." value="<?= esc($search) ?>">
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="wh-btn-primary w-100"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
    </div>

    <!-- Mini KPI -->
    <div class="wh-kpi-row">
        <div class="wh-kpi-mini">
            <div class="k-label">Total Aktivitas</div>
            <div class="k-value"><?= number_format($totalLog, 0, ',', '.') ?></div>
        </div>
        <div class="wh-kpi-mini">
            <div class="k-label">Hari Ini</div>
            <div class="k-value"><?= number_format($countToday, 0, ',', '.') ?></div>
        </div>
        <div class="wh-kpi-mini">
            <div class="k-label">Minggu Ini</div>
            <div class="k-value"><?= number_format($countWeek, 0, ',', '.') ?></div>
        </div>
        <div class="wh-kpi-mini">
            <div class="k-label">Bulan Ini</div>
            <div class="k-value"><?= number_format($countMonth, 0, ',', '.') ?></div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="wh-table-card">

        <!-- Toolbar -->
        <div class="wh-dt-toolbar">
            <div class="dt-length-wrap">
                Tampilkan
                <select id="dtLengthSelect">
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                entri
            </div>
            <div class="wh-dt-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="dtSearchInput" placeholder="Cari pengguna, modul, aktivitas...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Modul / Aktivitas</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 60px 20px;">
                                <i class="fa-solid fa-box-open" style="font-size:2.5rem; color:var(--wh-border);"></i>
                                <p style="margin-top:14px; color:var(--wh-text); font-weight:600;">Tidak ada log aktivitas ditemukan.</p>
                                <p style="color:var(--wh-text-soft); font-size:0.85rem;">Coba ubah filter pencarian Anda.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($logs as $log): ?>
                            <?php
                                // Avatar inisial
                                $namaUser  = $log['nama_user'] ?? 'Sistem';
                                $nameParts = explode(' ', trim($namaUser));
                                $initials  = strtoupper(substr($nameParts[0], 0, 1));
                                if (count($nameParts) > 1) $initials .= strtoupper(substr(end($nameParts), 0, 1));

                                // Role badge class
                                $roleVal = strtolower($log['role'] ?? '');
                                $roleCls = 'default';
                                if (strpos($roleVal, 'admin') !== false)      $roleCls = 'admin';
                                elseif (strpos($roleVal, 'petugas') !== false) $roleCls = 'petugas';
                                elseif (strpos($roleVal, 'supervisor') !== false) $roleCls = 'supervisor';

                                // Modul icon
                                $modul    = strtolower($log['modul'] ?? '');
                                $modulCls = 'default'; $modulIcon = 'fa-circle-dot';
                                if (strpos($modul, 'user') !== false)                { $modulCls = 'user';    $modulIcon = 'fa-user'; }
                                elseif (strpos($modul, 'stok') !== false || strpos($modul, 'barang') !== false || strpos($modul, 'donasi') !== false) { $modulCls = 'package'; $modulIcon = 'fa-boxes-stacked'; }
                                elseif (strpos($modul, 'laporan') !== false || strpos($modul, 'export') !== false) { $modulCls = 'report';  $modulIcon = 'fa-file-lines'; }
                                elseif (strpos($modul, 'login') !== false || strpos($modul, 'auth') !== false || strpos($modul, 'password') !== false) { $modulCls = 'auth'; $modulIcon = 'fa-shield-halved'; }
                                elseif (strpos($modul, 'pengaturan') !== false || strpos($modul, 'log') !== false) { $modulCls = 'system'; $modulIcon = 'fa-gear'; }

                                // Activity badge
                                $aktv    = strtolower($log['aktivitas'] ?? '');
                                $actCls  = 'act-other'; $actIcon = 'fa-circle';
                                if (strpos($aktv, 'login') !== false)                           { $actCls = 'act-login';  $actIcon = 'fa-circle-check'; }
                                elseif (strpos($aktv, 'tambah') !== false || strpos($aktv, 'create') !== false || strpos($aktv, 'store') !== false) { $actCls = 'act-tambah'; $actIcon = 'fa-plus'; }
                                elseif (strpos($aktv, 'edit') !== false || strpos($aktv, 'update') !== false)    { $actCls = 'act-edit';   $actIcon = 'fa-pen'; }
                                elseif (strpos($aktv, 'hapus') !== false || strpos($aktv, 'delete') !== false)   { $actCls = 'act-delete'; $actIcon = 'fa-trash'; }
                                elseif (strpos($aktv, 'reset') !== false)                       { $actCls = 'act-reset';  $actIcon = 'fa-key'; }
                                elseif (strpos($aktv, 'export') !== false)                      { $actCls = 'act-export'; $actIcon = 'fa-file-arrow-down'; }
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>
                                    <div class="wh-time-date"><?= date('d M Y', strtotime($log['created_at'])) ?></div>
                                    <div class="wh-time-hour"><?= date('H:i:s', strtotime($log['created_at'])) ?> WIB</div>
                                </td>
                                <td>
                                    <div class="wh-user-cell">
                                        <div class="wh-avatar"><?= $initials ?></div>
                                        <div>
                                            <div class="name"><?= esc($namaUser) ?></div>
                                            <div class="role">
                                                <span class="wh-role-badge <?= $roleCls ?>"><?= esc($log['role'] ?? '-') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="wh-modul-cell">
                                        <div class="wh-modul-icon <?= $modulCls ?>"><i class="fa-solid <?= $modulIcon ?>"></i></div>
                                        <div>
                                            <div class="wh-modul-name"><?= esc($log['modul']) ?></div>
                                            <div class="wh-modul-action">
                                                <span class="wh-act-badge <?= $actCls ?>">
                                                    <i class="fa-solid <?= $actIcon ?>"></i><?= esc($log['aktivitas']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="wh-desc" title="<?= esc($log['deskripsi']) ?>"><?= esc($log['deskripsi']) ?></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Bottom bar -->
        <div class="wh-dt-bottom">
            <div id="dtInfo"></div>
            <div id="dtPaginate"></div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    var table = $('#dataTable').DataTable({
        order: [],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        pageLength: 25,
        dom: 'rt',
        columnDefs: [{ orderable: false, targets: [0] }],
        initComplete: function () {
            $('#dataTable_info').appendTo('#dtInfo');
            $('#dataTable_paginate').appendTo('#dtPaginate');
        }
    });

    $('#dtLengthSelect').on('change', function () {
        table.page.len(parseInt(this.value)).draw();
    });

    $('#dtSearchInput').on('keyup input', function () {
        table.search(this.value).draw();
    });

    table.on('draw', function () {
        var info     = $('#dataTable_info');
        var paginate = $('#dataTable_paginate');
        if (info.parent().attr('id')     !== 'dtInfo')     info.appendTo('#dtInfo');
        if (paginate.parent().attr('id') !== 'dtPaginate') paginate.appendTo('#dtPaginate');
    });
});
</script>
<?= $this->endSection() ?>