<?= $this->extend('App\Views\layout\main') ?>

<?= $this->section('content') ?>

<?php
    $bulanTahunID = date('F Y');
    $bulanID = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    $bulanIniLabelTeks = strtr($bulanIniLabel, $bulanID);

    $hariID = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    $tanggalHariIni = strtr(date('l'), $hariID) . ', ' . date('d ') . strtr(date('F'), $bulanID) . date(' Y');

    // Logomark kecil (motif kotak bertumpuk) dipakai di samping tiap keterangan/caption
    $logoMark = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="dash-logomark"><path d="M12 2L2 7l10 5 10-5-10-5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M2 17l10 5 10-5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M2 12l10 5 10-5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>';
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root{
        --bg-page:#F6F7F5;
        --bg-card:#FFFFFF;
        --border-soft:#E7EAE6;
        --text-primary:#1E2620;
        --text-muted:#767F78;

        --accent:#3F6C51;         /* sage green — primary */
        --accent-soft:#EAF0EA;
        --accent-slate:#5B6670;   /* neutral slate — secondary */
        --accent-slate-soft:#EEF0F1;
        --accent-amber:#B8842E;   /* muted amber — attention */
        --accent-amber-soft:#F8F0E2;
        --accent-terracotta:#B4574A; /* muted terracotta — urgent */
        --accent-terracotta-soft:#F7E9E7;
    }

    .dash-wrap{
        background:var(--bg-page);
        font-family:'Inter', sans-serif;
        color:var(--text-primary);
        padding-bottom:1rem;
    }
    .dash-wrap h1,.dash-wrap h2,.dash-wrap h3,.dash-wrap h4,.dash-wrap h5,.dash-wrap h6{
        font-family:'Plus Jakarta Sans', sans-serif;
        letter-spacing:-0.01em;
    }

    .dash-card{
        background:var(--bg-card);
        border:1px solid var(--border-soft);
        border-radius:1.1rem;
        box-shadow:0 1px 2px rgba(30,38,32,0.03);
    }

    .dash-eyebrow{
        font-size:.8rem;
        font-weight:500;
        color:var(--text-muted);
    }

    .dash-icon{
        width:42px;
        height:42px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.05rem;
        flex-shrink:0;
    }
    .dash-icon.tone-accent{ background:var(--accent-soft); color:var(--accent); }
    .dash-icon.tone-slate{ background:var(--accent-slate-soft); color:var(--accent-slate); }
    .dash-icon.tone-amber{ background:var(--accent-amber-soft); color:var(--accent-amber); }
    .dash-icon.tone-terracotta{ background:var(--accent-terracotta-soft); color:var(--accent-terracotta); }

    .dash-figure{
        font-family:'Plus Jakarta Sans', sans-serif;
        font-weight:700;
        font-size:1.7rem;
        color:var(--text-primary);
    }

    .dash-caption{
        font-size:.82rem;
        color:var(--text-muted);
        display:flex;
        align-items:center;
        gap:.35rem;
    }
    .dash-logomark{
        color:var(--accent);
        opacity:.65;
        flex-shrink:0;
    }

    .dash-btn-outline{
        border:1px solid var(--border-soft);
        color:var(--accent);
        background:transparent;
        font-weight:500;
        transition:background .15s ease, border-color .15s ease;
    }
    .dash-btn-outline:hover{
        background:var(--accent-soft);
        border-color:var(--accent);
        color:var(--accent);
    }

    .dash-badge{
        display:inline-flex;
        align-items:center;
        gap:.35rem;
        font-weight:500;
        font-size:.8rem;
        padding:.35rem .75rem;
        border-radius:999px;
    }
    .dash-badge.tone-terracotta{ background:var(--accent-terracotta-soft); color:var(--accent-terracotta); }
    .dash-badge.tone-amber{ background:var(--accent-amber-soft); color:var(--accent-amber); }
    .dash-badge.tone-accent{ background:var(--accent-soft); color:var(--accent); }

    .dash-summary-tile{
        border:1px solid var(--border-soft);
        border-radius:14px;
        background:var(--bg-card);
    }

    .dash-table thead th{
        background:var(--bg-page);
        color:var(--text-muted);
        font-weight:600;
        font-size:.78rem;
        text-transform:uppercase;
        letter-spacing:.03em;
        border-bottom:none;
        padding:.85rem 1rem;
    }
    .dash-table tbody td{
        padding:.9rem 1rem;
        border-color:var(--border-soft);
        vertical-align:middle;
    }
    .dash-table tbody tr:hover{
        background:var(--bg-page);
    }

    .dash-role-pill{
        background:var(--accent-soft);
        color:var(--accent);
        font-weight:500;
        border-radius:999px;
        padding:.4rem .9rem;
        font-size:.85rem;
}

    .text-accent{ color:var(--accent) !important; }
    .text-terracotta{ color:var(--accent-terracotta) !important; }
    .text-amber{ color:var(--accent-amber) !important; }
</style>

<div class="dash-wrap container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dash-card">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-2">Selamat datang, <?= esc(user()->username) ?> 👋</h4>
                        <span class="dash-role-pill">
                            <i class="bi bi-person-badge me-1"></i>
                            <?= esc(get_user_role()) ?>
                        </span>
                    </div>
                    <div class="text-end d-none d-md-block">
                        <div class="dash-eyebrow mb-1"><i class="bi bi-calendar3 me-2"></i>Tanggal hari ini</div>
                        <h5 class="fw-bold mb-0 text-accent"><?= $tanggalHariIni ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 1: Statistik Utama -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-box-seam me-1"></i>Total Jenis Barang</div>
                        <div class="dash-icon tone-accent"><i class="bi bi-box"></i></div>
                    </div>
                    <div class="dash-figure"><?= number_format($totalJenisBarang, 0, ',', '.') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Barang unik dengan stok &gt; 0</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-boxes me-1"></i>Total Batch Aktif</div>
                        <div class="dash-icon tone-slate"><i class="bi bi-boxes"></i></div>
                    </div>
                    <div class="dash-figure"><?= number_format($totalBatch, 0, ',', '.') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Batch dengan stok tersedia</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-box-arrow-in-down me-1"></i>Donasi Bulan Ini</div>
                        <div class="dash-icon tone-accent"><i class="bi bi-arrow-down"></i></div>
                    </div>
                    <div class="dash-figure"><?= number_format($donasiBulanIni, 0, ',', '.') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Bulan <?= $bulanIniLabelTeks ?></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-box-arrow-up me-1"></i>Penyaluran Bulan Ini</div>
                        <div class="dash-icon tone-amber"><i class="bi bi-arrow-up"></i></div>
                    </div>
                    <div class="dash-figure"><?= number_format($penyaluranBulanIni, 0, ',', '.') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Bulan <?= $bulanIniLabelTeks ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Ringkasan Gudang -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-speedometer2 me-1"></i>Total Berat Gudang</div>
                        <div class="dash-icon tone-terracotta"><i class="bi bi-cart"></i></div>
                    </div>
                    <div class="dash-figure text-truncate"><?= format_berat($totalBeratGudang, 'Kg') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Seluruh stok yang ada di gudang</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-people me-1"></i>Total Donatur</div>
                        <div class="dash-icon tone-slate"><i class="bi bi-people"></i></div>
                    </div>
                    <div class="dash-figure"><?= number_format($totalDonatur, 0, ',', '.') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Donatur terdaftar</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-building me-1"></i>Titik Penyaluran</div>
                        <div class="dash-icon tone-amber"><i class="bi bi-building"></i></div>
                    </div>
                    <div class="dash-figure"><?= number_format($totalWilayah, 0, ',', '.') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Wilayah target penyaluran</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="dash-eyebrow"><i class="bi bi-tags me-1"></i>Total Kategori</div>
                        <div class="dash-icon tone-accent"><i class="bi bi-tags"></i></div>
                    </div>
                    <div class="dash-figure"><?= number_format($totalKategori, 0, ',', '.') ?></div>
                    <div class="dash-caption"><?= $logoMark ?> Kategori klasifikasi barang</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Grafik -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Grafik Donasi Masuk (12 Bulan)</h6>
                    <div style="height: 280px;">
                        <canvas id="donasiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Grafik Penyaluran Barang (12 Bulan)</h6>
                    <div style="height: 280px;">
                        <canvas id="penyaluranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Barang Hampir Expired -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="dash-card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="fw-bold mb-0">Barang Hampir Kedaluwarsa</h6>
                    <a href="<?= base_url('laporan-expired') ?>" class="btn btn-sm dash-btn-outline rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="dash-summary-tile d-flex align-items-center p-3">
                                <div class="dash-icon tone-terracotta me-3"><i class="bi bi-exclamation-triangle"></i></div>
                                <div>
                                    <div class="dash-eyebrow mb-1">Expired</div>
                                    <h5 class="mb-0 fw-bold text-terracotta"><?= number_format($countExpired, 0, ',', '.') ?> <small class="text-muted fw-normal">Batch</small></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dash-summary-tile d-flex align-items-center p-3">
                                <div class="dash-icon tone-amber me-3"><i class="bi bi-hourglass-split"></i></div>
                                <div>
                                    <div class="dash-eyebrow mb-1">Hampir Expired (≤30 Hari)</div>
                                    <h5 class="mb-0 fw-bold text-amber"><?= number_format($countHampirExpired, 0, ',', '.') ?> <small class="text-muted fw-normal">Batch</small></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dash-summary-tile d-flex align-items-center p-3">
                                <div class="dash-icon tone-accent me-3"><i class="bi bi-check-circle"></i></div>
                                <div>
                                    <div class="dash-eyebrow mb-1">Aman (&gt;30 Hari)</div>
                                    <h5 class="mb-0 fw-bold text-accent"><?= number_format($countAman, 0, ',', '.') ?> <small class="text-muted fw-normal">Batch</small></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table dash-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="15%">Status</th>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <th>Sisa Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($hampirExpiredList)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada data barang yang akan kedaluwarsa.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach($hampirExpiredList as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if($item['status_expired'] == 'Expired'): ?>
                                                <span class="dash-badge tone-terracotta"><i class="bi bi-x-circle"></i> Expired</span>
                                            <?php elseif($item['status_expired'] == 'Hampir Expired'): ?>
                                                <span class="dash-badge tone-amber"><i class="bi bi-exclamation-circle"></i> ≤30 Hari</span>
                                            <?php else: ?>
                                                <span class="dash-badge tone-accent"><i class="bi bi-check-circle"></i> Aman</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-medium"><?= esc($item['nama_barang']) ?></td>
                                        <td class="text-muted"><?= date('d M Y', strtotime($item['tanggal_kedaluwarsa'])) ?></td>
                                        <td>
                                            <?php if($item['sisa_hari'] < 0): ?>
                                                <span class="text-terracotta fw-bold"><?= $item['sisa_hari'] ?> Hari</span>
                                            <?php elseif($item['sisa_hari'] <= 30): ?>
                                                <span class="text-amber fw-bold"><?= $item['sisa_hari'] ?> Hari</span>
                                            <?php else: ?>
                                                <span class="text-accent fw-bold"><?= $item['sisa_hari'] ?> Hari</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 5: Kategori & Top 5 Barang -->
    <div class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Stok Berdasarkan Kategori (Kg)</h6>
                    <div style="height: 280px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="dash-card h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Top 5 Barang dengan Stok Terbanyak</h6>
                    <div class="table-responsive">
                        <table class="table dash-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                    <th class="text-end">Total Berat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($topBarang)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada stok barang di gudang.</td>
                                </tr>
                                <?php else: ?>
                                    <?php $no=1; foreach($topBarang as $b): ?>
                                    <tr>
                                        <td class="fw-medium text-muted"><?= $no++ ?></td>
                                        <td class="fw-bold"><?= esc($b['nama_barang']) ?></td>
                                        <td>
                                            <span class="dash-badge tone-accent">
                                                <?= number_format($b['total_stok'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-muted"><?= esc($b['satuan']) ?></td>
                                        <td class="text-end fw-medium"><?= format_berat($b['total_berat'], 'Kg') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Shared Tooltip Config
    const sharedTooltipOptions = {
        padding: 12,
        backgroundColor: '#1E2620',
        titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13, weight: '600' },
        bodyFont: { family: "'Inter', sans-serif", size: 12 },
        displayColors: false,
        cornerRadius: 8
    };

    // Grafik Donasi
    const donasiCtx = document.getElementById('donasiChart').getContext('2d');
    const donasiLabels = <?= $grafikLabels ?>;
    const donasiData = <?= $grafikDonasiData ?>;

    new Chart(donasiCtx, {
        type: 'line',
        data: {
            labels: donasiLabels,
            datasets: [{
                label: 'Donasi Masuk',
                data: donasiData,
                borderColor: '#3F6C51',
                backgroundColor: 'rgba(63, 108, 81, 0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#3F6C51',
                pointRadius: 3,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...sharedTooltipOptions,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Transaksi';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [4, 4], color: '#E7EAE6' },
                    ticks: { precision: 0, color: '#767F78' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#767F78' }
                }
            }
        }
    });

    // Grafik Penyaluran
    const penyaluranCtx = document.getElementById('penyaluranChart').getContext('2d');
    const penyaluranLabels = <?= $grafikLabels ?>;
    const penyaluranData = <?= $grafikPenyaluranData ?>;

    new Chart(penyaluranCtx, {
        type: 'line',
        data: {
            labels: penyaluranLabels,
            datasets: [{
                label: 'Penyaluran',
                data: penyaluranData,
                borderColor: '#B8842E',
                backgroundColor: 'rgba(184, 132, 46, 0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#B8842E',
                pointRadius: 3,
                pointHoverRadius: 5,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...sharedTooltipOptions,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Transaksi';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [4, 4], color: '#E7EAE6' },
                    ticks: { precision: 0, color: '#767F78' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#767F78' }
                }
            }
        }
    });

    // Pie Chart Kategori
    const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
    const kategoriLabels = <?= $kategoriLabels ?>;
    const kategoriData = <?= $kategoriData ?>;

    new Chart(kategoriCtx, {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                data: kategoriData,
                backgroundColor: [
                    '#3b82f6',
                    '#e0a63e',
                    '#8ea3c2',
                    '#ef5b57',
                    '#22c55e',
                    '#475569'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 18,
                        color: '#1E2620',
                        font: { family: "'Inter', sans-serif", size: 12 }
                    }
                },
                tooltip: {
                    ...sharedTooltipOptions,
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => Number(a) + Number(b), 0);
                            const percentage = Math.round((value / total) * 100);
                            return ' ' + value.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 2}) + ' Kg (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>