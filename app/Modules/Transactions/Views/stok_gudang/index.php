<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    helper('format');
    $totalBatchAktif = count($stokGudang);
?>

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
        margin: -24px;
        padding: 20px 24px 40px 24px;
        font-size: 13px;
        overflow-x: hidden;
    }

    /* ---------- Header ---------- */
    .wh-header {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 14px;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 10px;
    }
    .wh-header h1 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--wh-text);
        margin: 0 0 2px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .wh-header h1 i { color: var(--wh-primary); }
    .wh-header p {
        margin: 0;
        font-size: 0.82rem;
        color: var(--wh-text-soft);
    }
    .wh-header .wh-updated {
        text-align: right;
    }
    .wh-header .wh-updated .lbl {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--wh-text-soft);
        font-weight: 700;
    }
    .wh-header .wh-updated .val {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--wh-text);
        line-height: 1.4;
    }

    /* ---------- Filter Panel ---------- */
    .wh-filter-card {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 10px;
    }
    .wh-filter-card .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--wh-text);
        margin-bottom: 5px;
    }
    .wh-filter-card .form-control,
    .wh-filter-card .form-select {
        height: 38px;
        border-radius: 8px;
        border: 1px solid var(--wh-border);
        font-size: 0.82rem;
        padding: 0 0.7rem;
    }
    .wh-filter-card .form-control:focus,
    .wh-filter-card .form-select:focus {
        border-color: var(--wh-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .wh-btn-primary {
        background: var(--wh-primary);
        border: 1px solid var(--wh-primary);
        color: #fff;
        height: 38px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background 120ms ease;
        white-space: nowrap;
    }
    .wh-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .wh-btn-outline {
        background: #fff;
        border: 1px solid var(--wh-border);
        color: var(--wh-text);
        height: 38px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background 120ms ease;
        white-space: nowrap;
        text-decoration: none;
    }
    .wh-btn-outline:hover { background: var(--wh-dark-soft); color: var(--wh-text); }

    /* ---------- Table Card ---------- */
    .wh-table-card {
        background: var(--wh-card);
        border: 1px solid var(--wh-border);
        border-radius: 14px;
        padding: 14px 16px;
        overflow: hidden;
    }

    /* DataTables controls */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.8rem;
        color: var(--wh-text-soft);
    }
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        color: var(--wh-text);
    }
    .dataTables_wrapper .dataTables_filter input {
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--wh-border);
        padding: 0 8px;
        font-size: 0.8rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--wh-primary);
        box-shadow: 0 0 0 2px rgba(37,99,235,.1);
    }
    .dataTables_wrapper .dataTables_length select {
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--wh-border);
        font-size: 0.8rem;
        padding: 0 24px 0 8px;
        appearance: auto;
        outline: none;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 10px;
        font-size: 0.78rem;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 6px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        height: 32px;
        min-width: 32px;
        border-radius: 8px !important;
        padding: 0 10px !important;
        font-size: 0.78rem !important;
        border: 1px solid var(--wh-border) !important;
        background: #fff !important;
        color: var(--wh-text) !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        box-sizing: border-box;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--wh-primary) !important;
        border-color: var(--wh-primary) !important;
        color: #fff !important;
        font-weight: 600;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background: var(--wh-dark-soft) !important;
        color: var(--wh-text) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    /* ---------- Table ---------- */
    #tabelStokGudang {
        width: 100% !important;
        border-collapse: collapse;
        font-size: 0.8rem;
    }
    #tabelStokGudang thead th {
        background: #f8fafc;
        color: var(--wh-text-soft);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 10px 12px;
        border-top: 1px solid var(--wh-border);
        border-bottom: 2px solid var(--wh-border);
        border-left: none;
        border-right: none;
        white-space: nowrap;
        vertical-align: middle;
    }
    #tabelStokGudang tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 100ms;
    }
    #tabelStokGudang tbody tr:hover { background: #f8fafc; }
    #tabelStokGudang tbody td {
        padding: 10px 12px;
        vertical-align: middle;
        color: var(--wh-text);
        border: none;
        border-bottom: 1px solid #f1f5f9;
    }
    
    /* ---------- Status badges ---------- */
    .wh-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 999px;
        padding: 4px 10px 4px 8px;
        font-size: 0.68rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .wh-badge i { font-size: 0.6rem; }
    .wh-badge.aman    { background: var(--wh-success-soft); color: #15803D; }
    .wh-badge.hampir  { background: var(--wh-warning-soft); color: #B45309; }
    .wh-badge.expired { background: var(--wh-danger-soft);    color: var(--wh-danger); }
    .wh-badge.default { background: var(--wh-dark-soft);    color: var(--wh-text-soft); }

    /* ---------- Expired indicator ---------- */
    .wh-expired-ok   { color: var(--wh-text); font-weight: 500; }
    .wh-expired-soon { color: #B45309; font-weight: 600; }
    .wh-expired-over { color: var(--wh-danger); font-weight: 700; }
    .wh-expired-date { display: block; font-size: 0.68rem; color: var(--wh-text-soft); margin-top: 1px; }

    /* ---------- Stock ---------- */
    .wh-stock-wrap { min-width: 110px; }
    .wh-stock-value { font-weight: 700; font-size: 0.8rem; color: var(--wh-text); }
    .wh-progress {
        width: 100%;
        height: 5px;
        border-radius: 999px;
        background: var(--wh-dark-soft);
        overflow: hidden;
        margin-top: 4px;
    }
    .wh-progress > span { display: block; height: 100%; border-radius: 999px; }
    .wh-progress.green > span { background: var(--wh-success); }
    .wh-progress.amber > span { background: var(--wh-warning); }
    .wh-progress.red   > span { background: var(--wh-danger); }

    /* ---------- Action button ---------- */
    .wh-action-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--wh-border);
        background: #fff;
        color: var(--wh-text-soft);
        font-size: 0.75rem;
        transition: background 120ms ease, color 120ms ease;
        text-decoration: none;
    }
    .wh-action-btn:hover {
        background: var(--wh-dark-soft);
        color: var(--wh-primary);
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 768px) {
        .wh-page { padding: 12px; }
        .wh-header { padding: 14px 16px; }
        .wh-header h1 { font-size: 1rem; }
        .wh-header .wh-updated { text-align: left; }
    }
</style>

<div class="wh-page">

    <!-- Header -->
    <div class="wh-header">
        <div>
            <h1><i class="fa-solid fa-box"></i><?= esc($title) ?></h1>
            <p><?= isset($subtitle) ? esc($subtitle) : 'Monitoring seluruh persediaan barang yang tersedia di gudang.' ?></p>
        </div>
        <div class="wh-updated">
            <div class="lbl">Update Terakhir</div>
            <div class="val"><?= date('d F Y') ?><br><?= date('H:i') ?> WIB</div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="wh-filter-card">
        <form action="" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="filterSearch" class="form-label">Nama Barang</label>
                <input type="text" id="filterSearch" name="search" class="form-control" placeholder="Cari barang..." value="<?= esc($filters['search']) ?>">
            </div>
            <div class="col-md-3">
                <label for="filterDonatur" class="form-label">Donatur / Asal Barang</label>
                <input type="text" id="filterDonatur" name="donatur" class="form-control" placeholder="Cari donatur..." value="<?= esc($filters['donatur']) ?>">
            </div>
            <div class="col-md-2">
                <label for="filterKategori" class="form-label">Kategori</label>
                <select id="filterKategori" name="kategori" class="form-select">
                    <option value="">-- Semua --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= esc($k['nama_kategori']) ?>" <?= ($filters['kategori'] == $k['nama_kategori']) ? 'selected' : '' ?>><?= esc($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="filterStatus" class="form-label">Status</label>
                <select id="filterStatus" name="status" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="Aman"          <?= ($filters['status'] == 'Aman')           ? 'selected' : '' ?>>Aman</option>
                    <option value="Hampir Expired" <?= ($filters['status'] == 'Hampir Expired') ? 'selected' : '' ?>>Hampir Expired</option>
                    <option value="Expired"        <?= ($filters['status'] == 'Expired')        ? 'selected' : '' ?>>Expired</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="wh-btn-primary flex-fill"><i class="fa-solid fa-filter"></i> Terapkan</button>
                    <?php if (!empty($filters['kategori']) || !empty($filters['status']) || !empty($filters['search']) || !empty($filters['donatur'])): ?>
                        <a href="<?= site_url('transaksi/stok-gudang') ?>" class="wh-btn-outline">Reset</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="wh-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelStokGudang">
                <thead>
                    <tr>
                        <th width="30" class="text-center">No</th>
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
                        <th class="text-center" width="50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($stokGudang as $stok) : ?>
                        <?php
                            $bisaDipecah    = (int) $stok['bisa_dipecah'];
                            $beratPerSatuan = (float) $stok['berat_per_satuan'];

                            if ($bisaDipecah === 1) {
                                $totalBerat  = (float) $stok['stok_saat_ini'];
                                $kemasanAwal = !empty($stok['jumlah_ctn']) ? number_format($stok['jumlah_ctn'], 0, ',', '.') : '-';
                                $stokSaatIni = number_format($stok['stok_saat_ini'], 2, ',', '.');
                                $satuanStok  = 'Kg';
                            } else {
                                $beratKg = $stok['stok_saat_ini'] * $beratPerSatuan;
                                if (strtolower($stok['satuan_berat']) === 'gram') $beratKg /= 1000;
                                $totalBerat  = $beratKg;
                                $kemasanAwal = !empty($stok['jumlah_ctn']) ? number_format($stok['jumlah_ctn'], 0, ',', '.') : '-';
                                $stokSaatIni = number_format($stok['stok_saat_ini'], 0, ',', '.');
                                $satuanStok  = esc($stok['satuan']);
                            }

                            $pctStok  = null;
                            $pctClass = 'green';
                            if (!empty($stok['jumlah_ctn']) && $beratPerSatuan > 0) {
                                $beratAwalKg = $stok['jumlah_ctn'] * $beratPerSatuan;
                                if (strtolower($stok['satuan_berat']) === 'gram') $beratAwalKg /= 1000;
                                if ($beratAwalKg > 0) {
                                    $pctStok = max(0, min(100, ($totalBerat / $beratAwalKg) * 100));
                                    if ($pctStok < 30) $pctClass = 'red';
                                    elseif ($pctStok < 70) $pctClass = 'amber';
                                }
                            }

                            $expiredHtml = '-';
                            if ($stok['tanggal_kedaluwarsa']) {
                                $tglFormatted = date('d M Y', strtotime($stok['tanggal_kedaluwarsa']));
                                $diffDays     = floor((strtotime($stok['tanggal_kedaluwarsa']) - strtotime(date('Y-m-d'))) / 86400);
                                if ($diffDays < 0)      $expiredHtml = '<span class="wh-expired-over">Expired</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                                elseif ($diffDays === 0) $expiredHtml = '<span class="wh-expired-soon">Hari Ini</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                                elseif ($diffDays === 1) $expiredHtml = '<span class="wh-expired-soon">Besok</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                                elseif ($diffDays <= 7)  $expiredHtml = '<span class="wh-expired-soon">' . $diffDays . ' Hari Lagi</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                                else                     $expiredHtml = '<span class="wh-expired-ok">' . $tglFormatted . '</span>';
                            }

                            $badgeClass = 'default'; $badgeIcon = 'fa-circle';
                            if ($stok['status'] == 'Aman')           { $badgeClass = 'aman';   $badgeIcon = 'fa-circle-check'; }
                            elseif ($stok['status'] == 'Hampir Expired') { $badgeClass = 'hampir'; $badgeIcon = 'fa-triangle-exclamation'; }
                            elseif ($stok['status'] == 'Expired')    { $badgeClass = 'expired'; $badgeIcon = 'fa-ban'; }
                        ?>
                        <tr>
                            <td class="text-center text-secondary"><?= $no++ ?></td>
                            <td class="text-center">
                                <span class="wh-badge <?= $badgeClass ?>"><i class="fa-solid <?= $badgeIcon ?>"></i><?= esc($stok['status']) ?></span>
                            </td>
                            <td><?= esc($stok['donatur'] ?? '-') ?></td>
                            <td><?= esc($stok['kategori']) ?></td>
                            <td class="text-center"><?= $expiredHtml ?></td>
                            <td><strong><?= esc($stok['nama_barang']) ?></strong></td>
                            <td class="text-center"><?= esc($stok['satuan']) ?></td>
                            <td class="text-end"><?= $beratPerSatuan > 0 ? number_format($beratPerSatuan, 2, ',', '.') . ' ' . esc($stok['satuan_berat']) : '-' ?></td>
                            <td class="text-center"><?= $kemasanAwal ?></td>
                            <td>
                                <div class="wh-stock-wrap">
                                    <div class="wh-stock-value"><?= $stokSaatIni ?> <?= $satuanStok ?></div>
                                    <div style="font-size:0.7rem;color:var(--wh-text-soft);"><?= $totalBerat > 0 ? format_berat($totalBerat, 'Kg') : '-' ?></div>
                                    <?php if ($pctStok !== null): ?>
                                        <div class="wh-progress <?= $pctClass ?>"><span style="width:<?= round($pctStok) ?>%;"></span></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-muted"><small><?= esc($stok['catatan'] ?? '-') ?></small></td>
                            <td class="text-center">
                                <a href="<?= site_url('transaksi/stok-gudang/detail/' . $stok['id_barang']) ?>" class="wh-action-btn" title="Detail">
                                    <i class="fa-solid fa-list"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($stokGudang)): ?>
                <div style="text-align:center;padding:60px 20px;">
                    <i class="fa-solid fa-box-open" style="font-size:2.5rem;color:var(--wh-border);"></i>
                    <p style="margin-top:14px;color:var(--wh-text);font-weight:600;">Belum ada stok tersedia.</p>
                    <p style="color:var(--wh-text-soft);font-size:0.82rem;">Tambahkan barang untuk mulai mengelola inventaris gudang.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#tabelStokGudang').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
            order: [],
            columnDefs: [{ orderable: false, targets: [11] }],
            dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf>rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
        });
    });
</script>
<?= $this->endSection() ?>