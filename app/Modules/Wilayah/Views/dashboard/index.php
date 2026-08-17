<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

.mc-wrap * { box-sizing: border-box; }
.mc-wrap {
    --bg: #F8FAFC;
    --card: #fff;
    --border: #E5E7EB;
    --border-strong: #D1D5DB;
    --text-1: #0F172A;
    --text-2: #475569;
    --text-3: #94A3B8;
    --blue: #2563EB;
    --blue-bg: #EFF6FF;
    --blue-text: #1D4ED8;
    --green: #16A34A;
    --green-bg: #F0FDF4;
    --green-text: #15803D;
    --amber: #D97706;
    --amber-bg: #FFFBEB;
    --amber-text: #B45309;
    --cyan: #0891B2;
    --cyan-bg: #ECFEFF;
    --cyan-text: #0E7490;
    --slate: #64748B;
    --r: 16px;
    --r-sm: 10px;
    --shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    padding: 28px 24px;
    color: var(--text-1);
    min-height: 100vh;
}

/* ── Page header ── */
.mc-page-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-1);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 3px;
}
.mc-page-title i { font-size: 18px; color: var(--blue); }
.mc-page-sub { font-size: 13px; color: var(--text-2); margin-bottom: 28px; }

/* ── Stat grid ── */
.mc-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.mc-stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 16px;
    box-shadow: var(--shadow);
}
.mc-stat-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 6px;
}
.mc-stat-val { font-size: 24px; font-weight: 600; line-height: 1; }
.mc-stat-val.blue  { color: var(--blue); }
.mc-stat-val.green { color: var(--green); }
.mc-stat-val.slate { color: var(--slate); }
.mc-stat-val.cyan  { color: var(--cyan); }
.mc-stat-val.amber { color: var(--amber); }

/* ── Card base ── */
.mc-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
}
.mc-card-header {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.mc-card-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-1);
}
.mc-card-title i { color: var(--blue); font-size: 15px; }
.mc-card-body { padding: 18px 20px; }

/* ── National summary ── */
.mc-nat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    padding: 18px 20px;
}
.mc-nat-label {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 4px;
}
.mc-nat-val { font-size: 13px; font-weight: 500; }
.mc-nat-val.blue  { color: var(--blue-text); }
.mc-nat-val.green { color: var(--green-text); }
.mc-nat-val.amber { color: var(--amber-text); }
.mc-nat-val.cyan  { color: var(--cyan-text); }
.mc-nat-val.slate { color: var(--text-2); }

/* ── Search bar ── */
.mc-search-row {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    padding: 16px 20px;
}
.mc-search-label {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-2);
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.mc-search-label i { color: var(--blue); font-size: 15px; }
.mc-search-select {
    flex: 1;
    min-width: 200px;
    height: 36px;
    border: 1px solid var(--border-strong);
    border-radius: var(--r-sm);
    padding: 0 12px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: var(--text-1);
    background: #fff;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    cursor: pointer;
}
.mc-search-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px #DBEAFE; }

/* ── Empty & loading states ── */
.mc-empty {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    padding: 56px 20px;
    text-align: center;
    margin-bottom: 20px;
}
.mc-empty i { font-size: 40px; color: var(--text-3); display: block; margin-bottom: 12px; }
.mc-empty-title { font-size: 15px; font-weight: 500; color: var(--text-2); margin-bottom: 6px; }
.mc-empty-sub { font-size: 13px; color: var(--text-3); max-width: 360px; margin: 0 auto; }
.mc-loading {
    text-align: center;
    padding: 56px 20px;
}
.mc-loading .spinner-border { width: 2.5rem; height: 2.5rem; }
.mc-loading p { font-size: 13px; color: var(--text-2); margin-top: 12px; font-weight: 500; }

/* ── Gudang header ── */
.mc-g-top {
    padding: 18px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}
.mc-g-title { font-size: 15px; font-weight: 600; color: var(--text-1); }
.mc-g-sub   { font-size: 12px; color: var(--text-2); margin-top: 3px; }
.mc-g-meta  { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.mc-g-update { font-size: 12px; color: var(--text-3); }
.mc-g-update b { color: var(--text-2); }

/* ── Badge ── */
.mc-badge {
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 0 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.mc-badge.green { background: var(--green-bg); color: var(--green-text); }
.mc-badge.slate { background: #F1F5F9; color: var(--slate); }

/* ── Inner stat row ── */
.mc-inner-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
}
@media (max-width: 580px) { .mc-inner-stats { grid-template-columns: repeat(2, 1fr); } }
.mc-inner-stat {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--r-sm);
    padding: 14px 12px;
    text-align: center;
}
.mc-inner-label {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: 6px;
}
.mc-inner-val { font-size: 20px; font-weight: 600; }
.mc-inner-val.ink   { color: var(--text-1); }
.mc-inner-val.cyan  { color: var(--cyan); }
.mc-inner-val.amber { color: var(--amber); }
.mc-inner-val.green { color: var(--green); }

/* ── Action bar ── */
.mc-action-bar {
    padding: 14px 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.mc-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 32px;
    padding: 0 12px;
    border-radius: var(--r-sm);
    font-size: 12px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    border: 1px solid var(--border-strong);
    cursor: pointer;
    text-decoration: none;
    transition: opacity .12s;
}
.mc-btn:hover { opacity: .8; }
.mc-btn i { font-size: 14px; }
.mc-btn.cyan  { background: var(--cyan-bg);  border-color: #A5F3FC; color: var(--cyan-text); }
.mc-btn.amber { background: var(--amber-bg); border-color: #FDE68A; color: var(--amber-text); }
.mc-btn.blue  { background: var(--blue-bg);  border-color: #BFDBFE; color: var(--blue-text); }
.mc-btn.ghost { background: #fff; color: var(--text-2); }

/* ── Two-col grid ── */
.mc-two-col {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}
@media (max-width: 640px) { .mc-two-col { grid-template-columns: 1fr; } }

/* ── Chart ── */
.mc-chart-area {
    height: 220px;
    display: flex;
    align-items: flex-end;
    gap: 6px;
    padding-bottom: 8px;
}
.mc-chart-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
}
.mc-bars { display: flex; gap: 3px; align-items: flex-end; width: 100%; }
.mc-bar { flex: 1; border-radius: 4px 4px 0 0; min-height: 4px; }
.mc-bar.in  { background: #BAE6FD; }
.mc-bar.out { background: #FDE68A; }
.mc-chart-day { font-size: 10px; color: var(--text-3); margin-top: 4px; }
.mc-chart-legend {
    display: flex;
    gap: 16px;
    padding: 10px 20px;
    border-top: 1px solid var(--border);
}
.mc-legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-2); }
.mc-legend-dot { width: 10px; height: 10px; border-radius: 2px; }
.mc-legend-dot.in  { background: #BAE6FD; border: 1px solid #7DD3FC; }
.mc-legend-dot.out { background: #FDE68A; border: 1px solid #FCD34D; }

/* ── Tables ── */
.mc-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.mc-table th {
    font-size: 10px;
    font-weight: 600;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: .05em;
    padding: 10px 16px;
    background: var(--bg);
    border-bottom: 1px solid var(--border);
    text-align: left;
    white-space: nowrap;
}
.mc-table td {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    color: var(--text-1);
    vertical-align: middle;
}
.mc-table tr:last-child td { border-bottom: none; }
.mc-table tr:hover td     { background: #FAFAFA; }
.mc-table .num { text-align: right; font-weight: 600; color: var(--green); }
.mc-overflow { overflow-x: auto; }

/* ── Rank badge ── */
.mc-rank {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    background: var(--bg);
    border: 1px solid var(--border);
    color: var(--text-3);
    margin-right: 4px;
}
.mc-rank.top { background: #FFFBEB; border-color: #FDE68A; color: #92400E; }

/* ── Jenis badge ── */
.mc-jenis {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    height: 20px;
    padding: 0 8px;
    border-radius: 5px;
    font-size: 10px;
    font-weight: 600;
}
.mc-jenis.masuk  { background: var(--cyan-bg);  color: var(--cyan-text); }
.mc-jenis.keluar { background: var(--amber-bg); color: var(--amber-text); }

/* ── Doc code ── */
.mc-doc {
    font-size: 11px;
    font-weight: 500;
    color: var(--text-2);
    background: var(--bg);
    border: 1px solid var(--border);
    padding: 2px 7px;
    border-radius: 5px;
    font-family: monospace;
}

.qty-pos { color: var(--green); font-weight: 600; }
.qty-neg { color: var(--amber); font-weight: 600; }
</style>

<div class="mc-wrap">

    <!-- ── Page header ── -->
    <div class="mc-page-title">
        <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
        Monitoring Center Gudang Wilayah
    </div>
    <p class="mc-page-sub">Pusat pemantauan real-time operasional Gudang Wilayah Foodbank Indonesia</p>

    <!-- ── 1. Global summary cards ── -->
    <div class="mc-stat-grid">
        <div class="mc-stat-card">
            <div class="mc-stat-label">Total Gudang</div>
            <div class="mc-stat-val blue"><?= number_format($globalStats['totalGudang']) ?></div>
        </div>
        <div class="mc-stat-card">
            <div class="mc-stat-label">Gudang Aktif</div>
            <div class="mc-stat-val green"><?= number_format($globalStats['gudangAktif']) ?></div>
        </div>
        <div class="mc-stat-card">
            <div class="mc-stat-label">Nonaktif</div>
            <div class="mc-stat-val slate"><?= number_format($globalStats['gudangNonaktif']) ?></div>
        </div>
        <div class="mc-stat-card">
            <div class="mc-stat-label">Total Sisa Stok</div>
            <div class="mc-stat-val cyan"><?= number_format($globalStats['totalStok'], 0, ',', '.') ?></div>
        </div>
        <div class="mc-stat-card">
            <div class="mc-stat-label">Masuk Hari Ini</div>
            <div class="mc-stat-val blue"><?= number_format($globalStats['totalMasukToday'], 0, ',', '.') ?></div>
        </div>
        <div class="mc-stat-card">
            <div class="mc-stat-label">Keluar Hari Ini</div>
            <div class="mc-stat-val amber"><?= number_format($globalStats['totalKeluarToday'], 0, ',', '.') ?></div>
        </div>
    </div>

    <!-- ── 2. Ringkasan Nasional (admin only) ── -->
    <?php if ($isAdmin && !empty($nationalSummary)): ?>
    <div class="mc-card">
        <div class="mc-card-header">
            <div class="mc-card-title">
                <i class="fa-solid fa-earth-asia" aria-hidden="true"></i>
                Ringkasan nasional gudang wilayah
            </div>
        </div>
        <div class="mc-nat-grid">
            <div>
                <div class="mc-nat-label">Gudang teraktif hari ini</div>
                <div class="mc-nat-val blue"><?= esc($nationalSummary['gudangTeraktif']) ?></div>
            </div>
            <div>
                <div class="mc-nat-label">Stok terbesar</div>
                <div class="mc-nat-val slate"><?= esc($nationalSummary['gudangStokTerbesar']) ?></div>
            </div>
            <div>
                <div class="mc-nat-label">Top barang masuk</div>
                <div class="mc-nat-val green"><?= esc($nationalSummary['barangTopMasuk']) ?></div>
            </div>
            <div>
                <div class="mc-nat-label">Top barang keluar</div>
                <div class="mc-nat-val amber"><?= esc($nationalSummary['barangTopKeluar']) ?></div>
            </div>
            <div>
                <div class="mc-nat-label">Donatur terbanyak</div>
                <div class="mc-nat-val cyan"><?= esc($nationalSummary['donaturTerbanyak']) ?></div>
            </div>
            <div>
                <div class="mc-nat-label">Update terakhir</div>
                <div class="mc-nat-val slate"><?= esc($nationalSummary['lastUpdate']) ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── 3. Pencarian Gudang ── -->
    <div class="mc-card">
        <div class="mc-search-row">
            <label class="mc-search-label" for="selectGudang">
                <i class="fa-solid fa-building-user" aria-hidden="true"></i>
                Cari / pilih gudang wilayah
            </label>
            <?php if (!$isAdmin): ?>
                <select id="selectGudang" class="mc-search-select" disabled>
                    <?php foreach ($globalStats['gudangList'] as $g): ?>
                        <option value="<?= $g['id'] ?>" selected><?= esc($g['nama']) ?> — <?= esc($g['kota']) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <select id="selectGudang" class="mc-search-select">
                    <option value="">— Pilih gudang wilayah —</option>
                    <?php foreach ($globalStats['gudangList'] as $g): ?>
                        <option value="<?= $g['id'] ?>"><?= esc($g['nama']) ?> — <?= esc($g['kota']) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Loading ── -->
    <div id="loadingSummary" class="mc-loading d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Memuat...</span>
        </div>
        <p>Memuat data real-time gudang wilayah…</p>
    </div>

    <!-- ── Empty state ── -->
    <div id="emptySummary" class="mc-empty">
        <i class="fa-solid fa-warehouse" aria-hidden="true"></i>
        <div class="mc-empty-title">Belum ada gudang dipilih</div>
        <p class="mc-empty-sub">Pilih salah satu Gudang Wilayah pada dropdown di atas untuk membuka Monitoring Center real-time.</p>
    </div>

    <!-- ── Dynamic section ── -->
    <div id="dynamicSection" class="d-none">

        <!-- ── 4. Ringkasan Gudang + Shortcuts ── -->
        <div class="mc-card">
            <div class="mc-g-top">
                <div>
                    <div class="mc-g-title" id="detGudangTitle">—</div>
                    <div class="mc-g-sub" id="detGudangSub">—</div>
                </div>
                <div class="mc-g-meta">
                    <span class="mc-badge green" id="detGudangStatus">Aktif</span>
                    <div class="mc-g-update">Update: <b id="detGudangLastUpdate">—</b></div>
                </div>
            </div>

            <div class="mc-inner-stats">
                <div class="mc-inner-stat">
                    <div class="mc-inner-label">Jenis barang</div>
                    <div class="mc-inner-val ink" id="detGudangTotalBarang">0</div>
                </div>
                <div class="mc-inner-stat">
                    <div class="mc-inner-label">Barang masuk</div>
                    <div class="mc-inner-val cyan" id="detGudangMasuk">0</div>
                </div>
                <div class="mc-inner-stat">
                    <div class="mc-inner-label">Barang keluar</div>
                    <div class="mc-inner-val amber" id="detGudangKeluar">0</div>
                </div>
                <div class="mc-inner-stat">
                    <div class="mc-inner-label">Sisa stok</div>
                    <div class="mc-inner-val green" id="detGudangSisa">0</div>
                </div>
            </div>

            <div class="mc-action-bar">
                <a href="#" id="btnMasukCreate" class="mc-btn cyan">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Input barang masuk
                </a>
                <a href="#" id="btnKeluarCreate" class="mc-btn amber">
                    <i class="fa-solid fa-minus" aria-hidden="true"></i> Input barang keluar
                </a>
                <a href="#" id="btnDetailGudang" class="mc-btn blue">
                    <i class="fa-solid fa-eye" aria-hidden="true"></i> Lihat detail gudang
                </a>
                <?php if ($isAdmin): ?>
                <a href="<?= site_url('wilayah/laporan') ?>" class="mc-btn ghost">
                    <i class="fa-solid fa-file-invoice" aria-hidden="true"></i> Laporan
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── 5 & 6. Chart + Top Barang ── -->
        <div class="mc-two-col">

            <!-- Grafik mutasi -->
            <div class="mc-card" style="margin-bottom:0">
                <div class="mc-card-header">
                    <div class="mc-card-title">
                        <i class="fa-solid fa-chart-area" aria-hidden="true"></i>
                        Mutasi 7 hari terakhir
                    </div>
                </div>
                <div class="mc-card-body">
                    <div style="height:220px;position:relative;">
                        <canvas id="chartMutasi"></canvas>
                    </div>
                </div>
                <div class="mc-chart-legend">
                    <div class="mc-legend-item"><div class="mc-legend-dot in"></div> Barang masuk</div>
                    <div class="mc-legend-item"><div class="mc-legend-dot out"></div> Barang keluar</div>
                </div>
            </div>

            <!-- Top 10 barang -->
            <div class="mc-card" style="margin-bottom:0">
                <div class="mc-card-header">
                    <div class="mc-card-title">
                        <i class="fa-solid fa-ranking-star" aria-hidden="true"></i>
                        Top 10 stok terbanyak
                    </div>
                </div>
                <div class="mc-overflow" style="max-height:320px;overflow-y:auto;">
                    <table class="mc-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Barang</th>
                                <th style="text-align:right">Stok</th>
                                <th>Sat</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyTopBarang"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ── 7. Riwayat 10 transaksi terbaru ── -->
        <div class="mc-card">
            <div class="mc-card-header">
                <div class="mc-card-title">
                    <i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>
                    10 transaksi terbaru
                </div>
            </div>
            <div class="mc-overflow">
                <table class="mc-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Kode Transaksi</th>
                            <th>Detail barang</th>
                            <th style="text-align:right">Total qty</th>
                            <th>Donatur / Tujuan</th>
                            <th>Operator</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyRecentTx"></tbody>
                </table>
            </div>
        </div>

    </div><!-- end #dynamicSection -->
</div><!-- end .mc-wrap -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let mutasiChart = null;

$(document).ready(function () {
    if ($.fn.select2) {
        $('#selectGudang').select2({ theme: 'bootstrap-5', width: '100%' });
    }

    $(document).on('change select2:select', '#selectGudang', function () {
        loadGudangData($(this).val());
    });

    const initialVal = $('#selectGudang').val();
    if (initialVal) loadGudangData(initialVal);
});

function loadGudangData(idGudang) {
    if (!idGudang) {
        $('#dynamicSection').addClass('d-none');
        $('#emptySummary').removeClass('d-none');
        return;
    }

    $('#emptySummary').addClass('d-none');
    $('#dynamicSection').addClass('d-none');
    $('#loadingSummary').removeClass('d-none');

    $.ajax({
        url: '<?= site_url('wilayah/dashboard/ajax-summary') ?>/' + idGudang,
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            $('#loadingSummary').addClass('d-none');

            if (res.error) {
                Swal.fire('Error', res.error, 'error');
                $('#emptySummary').removeClass('d-none');
                return;
            }

            const s = res.summary;

            // ── Ringkasan header ──
            $('#detGudangTitle').text(s.nama);
            $('#detGudangSub').text(s.kota + ', ' + s.provinsi + '  ·  PIC: ' + s.pic);
            $('#detGudangStatus')
                .text(s.status)
                .attr('class', s.status === 'Aktif' ? 'mc-badge green' : 'mc-badge slate');
            $('#detGudangLastUpdate').text(s.last_update);

            $('#detGudangTotalBarang').text(Number(s.total_barang).toLocaleString('id-ID'));
            $('#detGudangMasuk').text(Number(s.total_masuk).toLocaleString('id-ID'));
            $('#detGudangKeluar').text(Number(s.total_keluar).toLocaleString('id-ID'));
            $('#detGudangSisa').text(Number(s.sisa_stok).toLocaleString('id-ID'));

            // ── Shortcut URLs ──
            $('#btnMasukCreate').attr('href', '<?= site_url('wilayah/masuk/create') ?>?id_gudang=' + idGudang);
            $('#btnKeluarCreate').attr('href', '<?= site_url('wilayah/keluar/create') ?>?id_gudang=' + idGudang);
            $('#btnDetailGudang').attr('href', '<?= site_url('wilayah/dashboard/detail') ?>/' + idGudang);

            // ── Chart ──
            renderMutasiChart(res.chart);

            // ── Top 10 barang ──
            let topRows = '';
            if (res.top_barang && res.top_barang.length > 0) {
                res.top_barang.forEach(function (b, i) {
                    const rankClass = i < 3 ? 'mc-rank top' : 'mc-rank';
                    topRows += `<tr>
                        <td><span class="${rankClass}">${i + 1}</span></td>
                        <td style="font-weight:500">${b.nama_barang}<br>
                            <span style="font-size:10px;color:var(--text-3)">${b.kode_barang}</span></td>
                        <td class="num">${Number(b.jumlah).toLocaleString('id-ID')}</td>
                        <td style="color:var(--text-3)">${b.satuan}</td>
                    </tr>`;
                });
            } else {
                topRows = `<tr><td colspan="4" style="text-align:center;color:var(--text-3);padding:24px">Tidak ada data stok</td></tr>`;
            }
            $('#tbodyTopBarang').html(topRows);

            // ── Riwayat transaksi ──
            let txRows = '';
            if (res.recent_transactions && res.recent_transactions.length > 0) {
                res.recent_transactions.forEach(function (t) {
                    const badge   = `<span class="mc-jenis ${t.jenis}">${t.jenis === 'masuk' ? 'Masuk' : 'Keluar'}</span>`;
                    const qtyClass = t.jenis === 'masuk' ? 'qty-pos' : 'qty-neg';
                    const prefix   = t.jenis === 'masuk' ? '+' : '−';
                    txRows += `<tr>
                        <td style="color:var(--text-2);white-space:nowrap">${t.tanggal_formatted}</td>
                        <td>${badge}</td>
                        <td><span class="mc-doc">${t.nomor_dokumen}</span></td>
                        <td>${t.barang_summary || '—'}</td>
                        <td style="text-align:right" class="${qtyClass}">${prefix} ${Number(t.total_qty).toLocaleString('id-ID')} <span style="font-weight:400;color:var(--text-3)">${t.satuan || ''}</span></td>
                        <td style="color:var(--text-2)">${t.donatur_tujuan || '—'}</td>
                        <td style="color:var(--text-3)">${t.operator || '—'}</td>
                    </tr>`;
                });
            } else {
                txRows = `<tr><td colspan="7" style="text-align:center;color:var(--text-3);padding:32px">Belum ada riwayat transaksi di gudang ini</td></tr>`;
            }
            $('#tbodyRecentTx').html(txRows);

            $('#dynamicSection').removeClass('d-none');
        },
        error: function (xhr) {
            $('#loadingSummary').addClass('d-none');
            $('#emptySummary').removeClass('d-none');
            console.error('Gagal load summary:', xhr.status, xhr.responseText);
            Swal.fire('Error', 'Terjadi kesalahan saat memuat data gudang', 'error');
        }
    });
}

function renderMutasiChart(chartData) {
    const ctx = document.getElementById('chartMutasi').getContext('2d');

    if (mutasiChart) mutasiChart.destroy();

    mutasiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Barang masuk',
                    data: chartData.masuk,
                    backgroundColor: '#BAE6FD',
                    borderColor: '#7DD3FC',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Barang keluar',
                    data: chartData.keluar,
                    backgroundColor: '#FDE68A',
                    borderColor: '#FCD34D',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, family: 'Inter' }, color: '#94A3B8' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F1F5F9' },
                    ticks: { precision: 0, font: { size: 11, family: 'Inter' }, color: '#94A3B8' }
                }
            }
        }
    });
}
</script>
<?= $this->endSection() ?>