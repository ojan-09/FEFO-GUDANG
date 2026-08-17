<?= $this->extend('App\Views\layout\main') ?>

<?= $this->section('content') ?>

<?php
    $bulanID = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];
    $hariID = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $bulanIniLabelTeks = strtr($bulanIniLabel, $bulanID);
    $tanggalHariIni = strtr(date('l'), $hariID) . ', ' . date('d ') . strtr(date('F'), $bulanID) . date(' Y');
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ── Reset & base ─────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; }

.wb {
    --bg:          #F5F7FA;
    --surface:     #FFFFFF;
    --border:      #E5E7EB;
    --border-focus:#D1D5DB;
    --text:        #111827;
    --muted:       #6B7280;
    --subtle:      #9CA3AF;
    --green:       #16A34A;
    --green-bg:    #DCFCE7;
    --amber:       #D97706;
    --amber-bg:    #FEF3C7;
    --red:         #DC2626;
    --red-bg:      #FEE2E2;
    --blue:        #2563EB;
    --blue-bg:     #DBEAFE;
    --slate:       #475569;
    --slate-bg:    #F1F5F9;

    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    color: var(--text);
    background: var(--bg);
    padding: 0 0 2.5rem;
    -webkit-font-smoothing: antialiased;
}

/* ── Header ─── target: 110px tall ───────────────────── */
.wb-header {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    min-height: 72px;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.wb-header-left h1 {
    font-size: 22px;
    font-weight: 600;
    letter-spacing: -0.025em;
    margin: 0 0 .15rem;
    color: var(--text);
    line-height: 1.1;
}
.wb-header-left p {
    font-size: 11px;
    color: var(--muted);
    margin: 0;
}
.wb-header-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: .1rem;
}
.wb-date {
    font-size: 11px;
    font-weight: 500;
    color: var(--muted);
}
.wb-clock {
    font-size: 1.15rem;
    font-weight: 600;
    letter-spacing: -.03em;
    color: var(--text);
    font-variant-numeric: tabular-nums;
}
.wb-user-chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: var(--slate-bg);
    color: var(--slate);
    font-size: 11px;
    font-weight: 500;
    padding: .25rem .6rem;
    border-radius: 999px;
    margin-top: .2rem;
}
.wb-user-chip i { font-size: .8rem; }

/* ── Page body ─────────────────────────────────────────── */
.wb-body {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* ── Card ──────────────────────────────────────────────── */
.wb-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    overflow: hidden;
    transition: box-shadow 180ms ease, transform 180ms ease;
}
.wb-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); transform: translateY(-1px); }
.wb-card-body { padding: 14px 18px; }
.wb-card-header {
    padding: 11px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: .75rem;
    border-bottom: 1px solid var(--border);
}
.wb-card-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    margin: 0;
    letter-spacing: -.01em;
}

/* ── KPI GRID ── target card height: 130px ────────────── */
.wb-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
.wb-kpi {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 14px 18px;
    min-height: 108px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    transition: box-shadow 180ms ease, transform 180ms ease;
    cursor: default;
}
.wb-kpi:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); transform: translateY(-2px); }
.wb-kpi-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.wb-kpi-label {
    font-size: 10px;
    font-weight: 500;
    color: var(--muted);
    letter-spacing: .01em;
    text-transform: uppercase;
}
.wb-kpi-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}
.wb-kpi-icon.green  { background: var(--green-bg);  color: var(--green); }
.wb-kpi-icon.amber  { background: var(--amber-bg);  color: var(--amber); }
.wb-kpi-icon.red    { background: var(--red-bg);    color: var(--red); }
.wb-kpi-icon.blue   { background: var(--blue-bg);   color: var(--blue); }
.wb-kpi-icon.slate  { background: var(--slate-bg);  color: var(--slate); }

.wb-kpi-num {
    font-size: 30px;
    font-weight: 700;
    letter-spacing: -.04em;
    color: var(--text);
    line-height: 1;
}
.wb-kpi-sub {
    font-size: 10px;
    color: var(--muted);
    margin-top: .15rem;
}

/* ── Charts row ──────────────────────────────────────── */
.wb-charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.wb-chart-wrap {
    position: relative;
    height: 260px;
}

/* ── Summary tiles ───────────────────────────────────── */
.wb-summary-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 0;
}
.wb-tile {
    padding: 14px 18px;
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    gap: .25rem;
}
.wb-tile:last-child { border-right: none; }
.wb-tile-label {
    font-size: .68rem;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .03em;
    font-weight: 500;
}
.wb-tile-val {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: -.025em;
    color: var(--text);
}
.wb-tile-unit {
    font-size: .65rem;
    color: var(--subtle);
}

/* ── Tables ──────────────────────────────────────────── */
.wb-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .78rem;
}
.wb-table thead th {
    background: #F8F9FA;
    color: var(--muted);
    font-weight: 600;
    font-size: .68rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    padding: .55rem .9rem;
    text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.wb-table tbody td {
    padding: .6rem .9rem;
    border-bottom: 1px solid var(--border);
    color: var(--text);
    vertical-align: middle;
}
.wb-table tbody tr:last-child td { border-bottom: none; }
.wb-table tbody tr:hover td { background: #FAFAFA; }
.wb-table .fw { font-weight: 500; }

/* ── Badges ──────────────────────────────────────────── */
.wb-badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    font-size: .65rem;
    font-weight: 500;
    padding: .2rem .5rem;
    border-radius: 999px;
    white-space: nowrap;
}
.wb-badge.red    { background: var(--red-bg);    color: var(--red); }
.wb-badge.amber  { background: var(--amber-bg);  color: var(--amber); }
.wb-badge.green  { background: var(--green-bg);  color: var(--green); }
.wb-badge.blue   { background: var(--blue-bg);   color: var(--blue); }
.wb-badge.slate  { background: var(--slate-bg);  color: var(--slate); }

/* ── Link button ─────────────────────────────────────── */
.wb-link-btn {
    font-size: .72rem;
    font-weight: 500;
    color: var(--muted);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .28rem .65rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: transparent;
    transition: background 140ms, border-color 140ms, color 140ms;
}
.wb-link-btn:hover {
    background: var(--bg);
    border-color: var(--border-focus);
    color: var(--text);
}

/* ── Two col row ─────────────────────────────────────── */
.wb-two-col {
    display: grid;
    grid-template-columns: 1fr 1.85fr;
    gap: 14px;
}

/* ── Divider ─────────────────────────────────────────── */
.wb-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 0;
}

/* ── Expired Summary tiles ───────────────────────────── */
.wb-exp-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    border-bottom: 1px solid var(--border);
}
.wb-exp-tile {
    padding: 14px 18px;
    border-right: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: .7rem;
}
.wb-exp-tile:last-child { border-right: none; }
.wb-exp-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    flex-shrink: 0;
}
.wb-exp-num {
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: -.025em;
    line-height: 1;
}
.wb-exp-lbl { font-size: .65rem; color: var(--muted); margin-top: .15rem; }

/* ── Responsive ──────────────────────────────────────── */
@media (max-width: 1200px) {
    .wb-kpi-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .wb-summary-grid { grid-template-columns: repeat(3, 1fr); }
    .wb-tile:nth-child(3) { border-right: none; }
    .wb-tile:nth-child(4), .wb-tile:nth-child(5), .wb-tile:nth-child(6) { border-top: 1px solid var(--border); }
    .wb-two-col { grid-template-columns: 1fr; gap: 14px; }
}
@media (max-width: 900px) {
    .wb-body { padding: 12px 14px; gap: 12px; }
    .wb-header { padding: 0 14px; min-height: auto; }
    .wb-header-left h1 { font-size: 17px; }
    .wb-charts-row { grid-template-columns: 1fr; gap: 12px; }
    .wb-kpi-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .wb-kpi-num { font-size: 24px; }
    .wb-summary-grid { grid-template-columns: repeat(2, 1fr); }
    .wb-tile:nth-child(2n) { border-right: none; }
    .wb-tile:nth-child(n+3) { border-top: 1px solid var(--border); }
    .wb-exp-row { grid-template-columns: 1fr; }
    .wb-exp-tile { border-right: none; border-bottom: 1px solid var(--border); }
    .wb-exp-tile:last-child { border-bottom: none; }
}
@media (max-width: 600px) {
    .wb-kpi-grid { grid-template-columns: 1fr; }
    .wb-kpi-num { font-size: 22px; }
    .wb-summary-grid { grid-template-columns: 1fr 1fr; }
    .wb-clock { font-size: .95rem; }
    .wb-header-left h1 { font-size: 15px; }
}
</style>

<div class="wb">

    <!-- ── HEADER ─────────────────────────────────────────── -->
    <div class="wb-header">
        <div class="wb-header-left">
            <h1>Dashboard</h1>
            <p>Foodbank of Indonesia — Sistem Manajemen Stok Donasi</p>
            <div class="wb-user-chip mt-1">
                <i class="bi bi-person-circle"></i>
                <?= esc(user()->username) ?> &middot; <?= esc(get_user_role()) ?>
            </div>
        </div>
        <div class="wb-header-right">
            <span class="wb-date mb-1"><i class="bi bi-calendar3 me-1"></i><?= $tanggalHariIni ?></span>
            <div class="wb-clock" id="wb-clock">--:--:--</div>
            <span id="liveSyncStatus" class="wb-date mt-1" style="color: #16a34a; font-size: 10.5px; font-weight: 600;"><i class="bi bi-broadcast me-1" id="liveSyncIcon"></i>Live Sync Active</span>
        </div>
    </div>

    <div class="wb-body">

        <!-- ── KPI ROW ─────────────────────────────────────── -->
        <div class="wb-kpi-grid">
            <div class="wb-kpi">
                <div class="wb-kpi-top">
                    <span class="wb-kpi-label">Total Jenis Barang</span>
                    <div class="wb-kpi-icon blue"><i class="bi bi-box"></i></div>
                </div>
                <div>
                    <div class="wb-kpi-num" id="kpiTotalJenisBarang" data-count="<?= $totalJenisBarang ?>">0</div>
                    <div class="wb-kpi-sub">Barang dengan stok aktif</div>
                </div>
            </div>
            <div class="wb-kpi">
                <div class="wb-kpi-top">
                    <span class="wb-kpi-label">Total Batch Aktif</span>
                    <div class="wb-kpi-icon slate"><i class="bi bi-boxes"></i></div>
                </div>
                <div>
                    <div class="wb-kpi-num" id="kpiTotalBatch" data-count="<?= $totalBatch ?>">0</div>
                    <div class="wb-kpi-sub">Batch dengan stok tersedia</div>
                </div>
            </div>
            <div class="wb-kpi">
                <div class="wb-kpi-top">
                    <span class="wb-kpi-label">Donasi Masuk — <?= $bulanIniLabelTeks ?></span>
                    <div class="wb-kpi-icon green"><i class="bi bi-arrow-down-circle"></i></div>
                </div>
                <div>
                    <div class="wb-kpi-num" id="kpiDonasiBulanIni" data-count="<?= $donasiBulanIni ?>">0</div>
                    <div class="wb-kpi-sub">Transaksi barang masuk bulan ini</div>
                </div>
            </div>
            <div class="wb-kpi">
                <div class="wb-kpi-top">
                    <span class="wb-kpi-label">Penyaluran — <?= $bulanIniLabelTeks ?></span>
                    <div class="wb-kpi-icon amber"><i class="bi bi-arrow-up-circle"></i></div>
                </div>
                <div>
                    <div class="wb-kpi-num" id="kpiPenyaluranBulanIni" data-count="<?= $penyaluranBulanIni ?>">0</div>
                    <div class="wb-kpi-sub">Transaksi penyaluran bulan ini</div>
                </div>
            </div>
        </div>

        <!-- ── CHARTS ─────────────────────────────────────── -->
        <div class="wb-charts-row">
            <div class="wb-card">
                <div class="wb-card-header">
                    <p class="wb-card-title">Grafik Donasi Masuk · 12 Bulan</p>
                </div>
                <div class="wb-card-body">
                    <div class="wb-chart-wrap">
                        <canvas id="donasiChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="wb-card">
                <div class="wb-card-header">
                    <p class="wb-card-title">Grafik Penyaluran · 12 Bulan</p>
                </div>
                <div class="wb-card-body">
                    <div class="wb-chart-wrap">
                        <canvas id="penyaluranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── RINGKASAN GUDANG ─────────────────────────────── -->
        <div class="wb-card">
            <div class="wb-card-header">
                <p class="wb-card-title">Ringkasan Gudang</p>
            </div>
            <div class="wb-summary-grid">
                <div class="wb-tile">
                    <div class="wb-tile-label">Total Berat Gudang</div>
                    <div class="wb-tile-val" id="tileTotalBeratGudang"><?= format_berat($totalBeratGudang, 'Kg') ?></div>
                    <div class="wb-tile-unit">Seluruh stok tersedia</div>
                </div>
                <div class="wb-tile">
                    <div class="wb-tile-label">Total Donatur</div>
                    <div class="wb-tile-val" id="tileTotalDonatur"><?= number_format($totalDonatur, 0, ',', '.') ?></div>
                    <div class="wb-tile-unit">Donatur terdaftar</div>
                </div>
                <div class="wb-tile">
                    <div class="wb-tile-label">Titik Penyaluran</div>
                    <div class="wb-tile-val" id="tileTotalWilayah"><?= number_format($totalWilayah, 0, ',', '.') ?></div>
                    <div class="wb-tile-unit">Wilayah target</div>
                </div>
                <div class="wb-tile">
                    <div class="wb-tile-label">Total Kategori</div>
                    <div class="wb-tile-val" id="tileTotalKategori"><?= number_format($totalKategori, 0, ',', '.') ?></div>
                    <div class="wb-tile-unit">Klasifikasi barang</div>
                </div>
                <div class="wb-tile">
                    <div class="wb-tile-label">Batch Kadaluwarsa</div>
                    <div class="wb-tile-val" id="tileBatchKadaluwarsa" style="color:var(--red)"><?= number_format($countExpired, 0, ',', '.') ?></div>
                    <div class="wb-tile-unit">Sudah melewati tanggal</div>
                </div>
                <div class="wb-tile">
                    <div class="wb-tile-label">Hampir Kadaluwarsa</div>
                    <div class="wb-tile-val" id="tileHampirKadaluwarsa" style="color:var(--amber)"><?= number_format($countHampirExpired, 0, ',', '.') ?></div>
                    <div class="wb-tile-unit">≤ 30 hari tersisa</div>
                </div>
            </div>
        </div>

        <!-- ── BARANG HAMPIR EXPIRED ────────────────────────── -->
        <div class="wb-card">
            <div class="wb-card-header">
                <p class="wb-card-title">Barang Hampir Kedaluwarsa</p>
                <a href="<?= site_url('laporan/expired') ?>" class="wb-link-btn">
                    Lihat semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="wb-exp-row">
                <div class="wb-exp-tile">
                    <div class="wb-exp-icon red-bg" style="background:var(--red-bg);color:var(--red)">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <div class="wb-exp-num" id="expTileExpired" style="color:var(--red)"><?= number_format($countExpired, 0, ',', '.') ?> <small style="font-size:.75rem;font-weight:400;color:var(--muted)">Batch</small></div>
                        <div class="wb-exp-lbl">Sudah Expired</div>
                    </div>
                </div>
                <div class="wb-exp-tile">
                    <div class="wb-exp-icon" style="background:var(--amber-bg);color:var(--amber)">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="wb-exp-num" id="expTileHampirExpired" style="color:var(--amber)"><?= number_format($countHampirExpired, 0, ',', '.') ?> <small style="font-size:.75rem;font-weight:400;color:var(--muted)">Batch</small></div>
                        <div class="wb-exp-lbl">Hampir Expired (≤ 30 Hari)</div>
                    </div>
                </div>
                <div class="wb-exp-tile">
                    <div class="wb-exp-icon" style="background:var(--green-bg);color:var(--green)">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="wb-exp-num" id="expTileAman" style="color:var(--green)"><?= number_format($countAman, 0, ',', '.') ?> <small style="font-size:.75rem;font-weight:400;color:var(--muted)">Batch</small></div>
                        <div class="wb-exp-lbl">Aman (&gt; 30 Hari)</div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="wb-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Kedaluwarsa</th>
                            <th>Sisa Hari</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyHampirExpired">
                        <?php if(empty($hampirExpiredList)): ?>
                        <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--muted)">Tidak ada data barang yang akan kedaluwarsa.</td></tr>
                        <?php else: ?>
                            <?php foreach($hampirExpiredList as $item): ?>
                            <tr>
                                <td>
                                    <?php if($item['status_expired'] == 'Expired'): ?>
                                        <span class="wb-badge red"><i class="bi bi-x-circle"></i> Expired</span>
                                    <?php elseif($item['status_expired'] == 'Hampir Expired'): ?>
                                        <span class="wb-badge amber"><i class="bi bi-exclamation-circle"></i> ≤ 30 Hari</span>
                                    <?php else: ?>
                                        <span class="wb-badge green"><i class="bi bi-check-circle"></i> Aman</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw"><?= esc($item['nama_barang']) ?></td>
                                <td style="color:var(--muted)"><?= date('d M Y', strtotime($item['tanggal_kedaluwarsa'])) ?></td>
                                <td>
                                    <?php if($item['sisa_hari'] < 0): ?>
                                        <span style="color:var(--red);font-weight:600"><?= $item['sisa_hari'] ?> hari</span>
                                    <?php elseif($item['sisa_hari'] <= 30): ?>
                                        <span style="color:var(--amber);font-weight:600"><?= $item['sisa_hari'] ?> hari</span>
                                    <?php else: ?>
                                        <span style="color:var(--green);font-weight:600"><?= $item['sisa_hari'] ?> hari</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── KATEGORI + TOP 5 ───────────────────────────── -->
        <div class="wb-two-col">
            <div class="wb-card">
                <div class="wb-card-header">
                    <p class="wb-card-title">Distribusi Kategori (Kg)</p>
                </div>
                <div class="wb-card-body" style="display:flex;align-items:center;justify-content:center;">
                    <div class="wb-chart-wrap" style="height:260px;width:100%">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="wb-card">
                <div class="wb-card-header">
                    <p class="wb-card-title">Top 5 Barang — Stok Terbanyak</p>
                </div>
                <div class="table-responsive">
                    <table class="wb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th style="text-align:right">Total Berat</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTopBarang">
                            <?php if(empty($topBarang)): ?>
                            <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--muted)">Belum ada stok.</td></tr>
                            <?php else: ?>
                                <?php $no=1; foreach($topBarang as $b): ?>
                                <tr>
                                    <td style="color:var(--muted);font-weight:600"><?= $no++ ?></td>
                                    <td class="fw"><?= esc($b['nama_barang']) ?></td>
                                    <td>
                                        <span class="wb-badge blue"><?= number_format($b['total_stok'], 0, ',', '.') ?></span>
                                    </td>
                                    <td style="color:var(--muted)"><?= esc($b['satuan']) ?></td>
                                    <td style="text-align:right;font-weight:500"><?= format_berat($b['total_berat'], 'Kg') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- /wb-body -->
</div><!-- /wb -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Clock ─────────────────────────────────────────── */
    const clockEl = document.getElementById('wb-clock');
    function tick() {
        const now = new Date();
        clockEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    tick(); setInterval(tick, 1000);

    /* ── Count-Up ──────────────────────────────────────── */
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count, 10) || 0;
        if (target === 0) { el.textContent = '0'; return; }
        let start = 0;
        const duration = 900;
        const step = Math.ceil(target / (duration / 16));
        const timer = setInterval(() => {
            start = Math.min(start + step, target);
            el.textContent = start.toLocaleString('id-ID');
            if (start >= target) clearInterval(timer);
        }, 16);
    });

    /* ── Shared chart defaults ─────────────────────────── */
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size   = 12;

    const tooltip = {
        padding: 10,
        backgroundColor: '#111827',
        titleColor: '#F9FAFB',
        bodyColor: '#D1D5DB',
        cornerRadius: 8,
        displayColors: false,
        titleFont: { weight: '600', size: 12 },
        bodyFont:  { size: 11 }
    };

    const gridColor  = '#F3F4F6';
    const tickColor  = '#9CA3AF';

    /* ── Donasi Chart ──────────────────────────────────── */
    const donasiChartObj = new Chart(document.getElementById('donasiChart'), {
        type: 'line',
        data: {
            labels: <?= $grafikLabels ?>,
            datasets: [{
                label: 'Donasi Masuk',
                data:  <?= $grafikDonasiData ?>,
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37,99,235,.07)',
                borderWidth: 2,
                pointBackgroundColor: '#2563EB',
                pointRadius: 3,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { ...tooltip, callbacks: { label: c => c.parsed.y + ' transaksi' } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { precision: 0, color: tickColor } },
                x: { grid: { display: false }, ticks: { color: tickColor } }
            }
        }
    });

    /* ── Penyaluran Chart ──────────────────────────────── */
    const penyaluranChartObj = new Chart(document.getElementById('penyaluranChart'), {
        type: 'line',
        data: {
            labels: <?= $grafikLabels ?>,
            datasets: [{
                label: 'Penyaluran',
                data:  <?= $grafikPenyaluranData ?>,
                borderColor: '#D97706',
                backgroundColor: 'rgba(217,119,6,.07)',
                borderWidth: 2,
                pointBackgroundColor: '#D97706',
                pointRadius: 3,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { ...tooltip, callbacks: { label: c => c.parsed.y + ' transaksi' } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { precision: 0, color: tickColor } },
                x: { grid: { display: false }, ticks: { color: tickColor } }
            }
        }
    });

    /* ── Kategori Donut ────────────────────────────────── */
    const kategoriChartObj = new Chart(document.getElementById('kategoriChart'), {
        type: 'doughnut',
        data: {
            labels: <?= $kategoriLabels ?>,
            datasets: [{
                data: <?= $kategoriData ?>,
                backgroundColor: ['#3B82F6','#F59E0B','#10B981','#EF4444','#8B5CF6','#6B7280'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 16, color: '#374151', font: { size: 11 } }
                },
                tooltip: {
                    ...tooltip,
                    callbacks: {
                        label: function(c) {
                            const total = c.dataset.data.reduce((a, b) => +a + +b, 0);
                            const pct = Math.round((c.parsed / total) * 100);
                            return ' ' + Number(c.parsed).toLocaleString('id-ID', {maximumFractionDigits:2}) + ' Kg (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    /* ── LIVE SYNC AUTO REFRESH DYNAMIC DATA ──────────── */
    function fetchLiveData() {
        fetch('<?= site_url("api/dashboard/live-data") ?>')
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const m = res.metrics;
                    
                    const elJenis = document.getElementById('kpiTotalJenisBarang');
                    if (elJenis) elJenis.textContent = m.totalJenisBarang;

                    const elBatch = document.getElementById('kpiTotalBatch');
                    if (elBatch) elBatch.textContent = m.totalBatch;

                    const elDonasi = document.getElementById('kpiDonasiBulanIni');
                    if (elDonasi) elDonasi.textContent = m.donasiBulanIni;

                    const elPenyaluran = document.getElementById('kpiPenyaluranBulanIni');
                    if (elPenyaluran) elPenyaluran.textContent = m.penyaluranBulanIni;

                    const elBerat = document.getElementById('tileTotalBeratGudang');
                    if (elBerat) elBerat.textContent = m.totalBeratGudang;

                    const elDonatur = document.getElementById('tileTotalDonatur');
                    if (elDonatur) elDonatur.textContent = m.totalDonatur;

                    const elWilayah = document.getElementById('tileTotalWilayah');
                    if (elWilayah) elWilayah.textContent = m.totalWilayah;

                    const elKategori = document.getElementById('tileTotalKategori');
                    if (elKategori) elKategori.textContent = m.totalKategori;

                    const elExpCount = document.getElementById('expTileExpired');
                    if (elExpCount) elExpCount.innerHTML = m.countExpired + ' <small style="font-size:.75rem;font-weight:400;color:var(--muted)">Batch</small>';

                    const elHampirExpCount = document.getElementById('expTileHampirExpired');
                    if (elHampirExpCount) elHampirExpCount.innerHTML = m.countHampirExpired + ' <small style="font-size:.75rem;font-weight:400;color:var(--muted)">Batch</small>';

                    const elAmanCount = document.getElementById('expTileAman');
                    if (elAmanCount) elAmanCount.innerHTML = m.countAman + ' <small style="font-size:.75rem;font-weight:400;color:var(--muted)">Batch</small>';

                    if (kategoriChartObj && res.kategoriLabels && res.kategoriData) {
                        kategoriChartObj.data.labels = res.kategoriLabels;
                        kategoriChartObj.data.datasets[0].data = res.kategoriData;
                        kategoriChartObj.update('none');
                    }

                    if (donasiChartObj && res.grafikLabels && res.grafikDonasiData) {
                        donasiChartObj.data.labels = res.grafikLabels;
                        donasiChartObj.data.datasets[0].data = res.grafikDonasiData;
                        donasiChartObj.update('none');
                    }

                    if (penyaluranChartObj && res.grafikLabels && res.grafikPenyaluranData) {
                        penyaluranChartObj.data.labels = res.grafikLabels;
                        penyaluranChartObj.data.datasets[0].data = res.grafikPenyaluranData;
                        penyaluranChartObj.update('none');
                    }

                    const tbodyTop = document.getElementById('tbodyTopBarang');
                    if (tbodyTop && res.topBarang) {
                        if (res.topBarang.length === 0) {
                            tbodyTop.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--muted)">Belum ada stok.</td></tr>';
                        } else {
                            let html = '';
                            res.topBarang.forEach((b, idx) => {
                                html += `<tr>
                                    <td style="color:var(--muted);font-weight:600">${idx + 1}</td>
                                    <td class="fw">${b.nama_barang}</td>
                                    <td><span class="wb-badge blue">${b.total_stok}</span></td>
                                    <td style="color:var(--muted)">${b.satuan}</td>
                                    <td style="text-align:right;font-weight:500">${b.total_berat}</td>
                                </tr>`;
                            });
                            tbodyTop.innerHTML = html;
                        }
                    }

                    const tbodyExp = document.getElementById('tbodyHampirExpired');
                    if (tbodyExp && res.hampirExpiredList) {
                        if (res.hampirExpiredList.length === 0) {
                            tbodyExp.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--muted)">Tidak ada data barang yang akan kedaluwarsa.</td></tr>';
                        } else {
                            let html = '';
                            res.hampirExpiredList.forEach(item => {
                                const badgeHtml = item.status_badge || item.badge || '';
                                html += `<tr>
                                    <td>${badgeHtml}</td>
                                    <td class="fw">${item.nama_barang}</td>
                                    <td style="color:var(--muted)">${item.tanggal_kedaluwarsa}</td>
                                    <td>${item.sisa_hari_html || ''}</td>
                                </tr>`;
                            });
                            tbodyExp.innerHTML = html;
                        }
                    }

                    const syncIcon = document.getElementById('liveSyncIcon');
                    if (syncIcon) {
                        syncIcon.style.transform = 'rotate(180deg)';
                        syncIcon.style.transition = 'transform 0.4s ease';
                        setTimeout(() => { syncIcon.style.transform = 'rotate(0deg)'; }, 400);
                    }
                }
            })
            .catch(err => console.log('Dashboard Sync:', err));
    }

    // Run initial sync & set polling interval every 10 seconds
    setInterval(fetchLiveData, 10000);

    // Instant refresh when user returns focus to Dashboard tab
    window.addEventListener('focus', fetchLiveData);
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            fetchLiveData();
        }
    });

});
</script>

<?= $this->endSection() ?>