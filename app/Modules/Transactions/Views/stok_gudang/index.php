<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
    helper('format');
    $totalBatchAktif = count($stokGudang);
?>

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
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    font-size: 13px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}
.dataTables_wrapper .dataTables_length select {
    height: 36px;
    font-size: 13px;
    padding: 0 28px 0 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #111827;
    outline: none;
    appearance: auto;
}
.dataTables_wrapper .dataTables_filter input {
    height: 36px;
    width: 220px;
    font-size: 13px;
    padding: 0 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    color: #111827;
    outline: none;
    transition: border-color .15s;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99,102,241,.12);
}
.dataTables_wrapper .dataTables_info {
    font-size: 12px;
    color: #6b7280;
    padding-top: 10px;
}
.dataTables_wrapper .dataTables_paginate { margin-top: 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0 !important; border: none !important; background: transparent !important; margin: 0 1px !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button .page-link { height: 32px !important; min-width: 32px; padding: 0 8px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; border: 1px solid #e2e8f0 !important; font-size: 12.5px; font-weight: 500; color: #334155 !important; background: #fff !important; transition: background-color .15s ease, border-color .15s ease, color .15s ease; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link,
.dataTables_wrapper .dataTables_paginate .paginate_button.active .page-link { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover .page-link { background: #eff6ff !important; border-color: #bfdbfe !important; color: #2563eb !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled .page-link { opacity: .45; cursor: default; }

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
    <form action="" method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label for="filterSearch" class="form-label">Nama Barang</label>
            <input type="text" id="filterSearch" name="search" class="form-control"
                   placeholder="Cari barang..." value="<?= esc($filters['search']) ?>">
        </div>
        <div class="col-md-3">
            <label for="filterDonatur" class="form-label">Donatur / Asal Barang</label>
            <input type="text" id="filterDonatur" name="donatur" class="form-control"
                   placeholder="Cari donatur..." value="<?= esc($filters['donatur']) ?>">
        </div>
        <div class="col-md-2">
            <label for="filterKategori" class="form-label">Kategori</label>
            <select id="filterKategori" name="kategori" class="form-select">
                <option value="">-- Semua --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= esc($k['nama_kategori']) ?>"
                        <?= ($filters['kategori'] == $k['nama_kategori']) ? 'selected' : '' ?>>
                        <?= esc($k['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filterStatus" class="form-label">Status</label>
            <select id="filterStatus" name="status" class="form-select">
                <option value="">-- Semua --</option>
                <option value="Aman"           <?= ($filters['status'] == 'Aman')           ? 'selected' : '' ?>>Aman</option>
                <option value="Hampir Expired" <?= ($filters['status'] == 'Hampir Expired') ? 'selected' : '' ?>>Hampir Expired</option>
                <option value="Expired"        <?= ($filters['status'] == 'Expired')        ? 'selected' : '' ?>>Expired</option>
            </select>
        </div>
        <div class="col-md-2">
            <div class="d-flex gap-2">
                <button type="submit" class="dm-btn-filter flex-fill">
                    <i class="fa-solid fa-filter"></i> Terapkan
                </button>
                <?php if (!empty($filters['kategori']) || !empty($filters['status']) || !empty($filters['search']) || !empty($filters['donatur'])): ?>
                    <a href="<?= site_url('transaksi/stok-gudang') ?>" class="dm-btn-reset">Reset</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- ── Data Card ── -->
<div class="dm-card">
    <div class="table-responsive">
        <table class="table mb-0" id="tabelStokGudang">
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
                            if ($diffDays < 0)       $expiredHtml = '<span class="wh-expired-over">Expired</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                            elseif ($diffDays === 0) $expiredHtml = '<span class="wh-expired-soon">Hari Ini</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                            elseif ($diffDays === 1) $expiredHtml = '<span class="wh-expired-soon">Besok</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                            elseif ($diffDays <= 7)  $expiredHtml = '<span class="wh-expired-soon">' . $diffDays . ' Hari Lagi</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                            else                     $expiredHtml = '<span class="wh-expired-ok">' . $tglFormatted . '</span>';
                        }

                        $badgeClass = 'default'; $badgeIcon = 'fa-circle';
                        if ($stok['status'] == 'Aman')               { $badgeClass = 'aman';    $badgeIcon = 'fa-circle-check'; }
                        elseif ($stok['status'] == 'Hampir Expired') { $badgeClass = 'hampir';  $badgeIcon = 'fa-triangle-exclamation'; }
                        elseif ($stok['status'] == 'Expired')        { $badgeClass = 'expired'; $badgeIcon = 'fa-ban'; }
                    ?>
                    <tr>
                        <td class="text-center text-secondary"><?= $no++ ?></td>
                        <td class="text-center">
                            <span class="wh-badge <?= $badgeClass ?>">
                                <i class="fa-solid <?= $badgeIcon ?>"></i><?= esc($stok['status']) ?>
                            </span>
                        </td>
                        <td style="color:#475569;"><?= esc($stok['donatur'] ?? '-') ?></td>
                        <td style="color:#475569;"><?= esc($stok['kategori']) ?></td>
                        <td class="text-center"><?= $expiredHtml ?></td>
                        <td><span class="fw-semibold" style="color:#0f172a;"><?= esc($stok['nama_barang']) ?></span></td>
                        <td class="text-center" style="color:#475569;"><?= esc($stok['satuan']) ?></td>
                        <td class="text-end" style="color:#475569;">
                            <?= $beratPerSatuan > 0 ? number_format($beratPerSatuan, 2, ',', '.') . ' ' . esc($stok['satuan_berat']) : '-' ?>
                        </td>
                        <td class="text-center" style="color:#475569;"><?= $kemasanAwal ?></td>
                        <td>
                            <div class="wh-stock-wrap">
                                <div class="wh-stock-value"><?= $stokSaatIni ?> <?= $satuanStok ?></div>
                                <div style="font-size:11px;color:#6b7280;"><?= $totalBerat > 0 ? format_berat($totalBerat, 'Kg') : '-' ?></div>
                                <?php if ($pctStok !== null): ?>
                                    <div class="wh-progress <?= $pctClass ?>">
                                        <span style="width:<?= round($pctStok) ?>%;"></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><small style="color:#6b7280;"><?= esc($stok['catatan'] ?? '-') ?></small></td>
                        <td class="text-center">
                            <div class="dm-action-group">
                                <a href="<?= site_url('transaksi/stok-gudang/detail/' . $stok['id_barang']) ?>"
                                   class="dm-btn-action view" title="Lihat Detail">
                                    <i class="fa-solid fa-list"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($stokGudang)): ?>
            <div class="dm-empty">
                <i class="fa-solid fa-box-open"></i>
                <p>Belum ada stok tersedia.</p>
                <small>Tambahkan barang untuk mulai mengelola inventaris gudang.</small>
            </div>
        <?php endif; ?>
    </div>
</div>

</div><!-- /.dm-page -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    $('#tabelStokGudang').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        order: [],
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [11] }
        ],
        dom: '<"d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2"lf>rt<"d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3"ip>',
    });
});
</script>
<?= $this->endSection() ?>