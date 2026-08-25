<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php helper('format'); ?>

<style>
/* ── Topbar ── */
.topbar { padding: 12px 18px; }
.topbar .page-title { font-size: 1.15rem; font-weight: 600; color: #1e293b; }
.topbar .subtle { font-size: 0.8rem; color: #94a3b8; }

/* ── Panel card ── */
.panel-card { padding: 16px 18px; }
.panel-title {
    font-size: 0.78rem;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 0 !important;
}

/* ── KPI cards ── */
.kpi-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    border-top: 3px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    padding: 14px 16px;
    min-height: 84px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.kpi-card.blue  { border-top-color: #3b82f6; }
.kpi-card.green { border-top-color: #22c55e; }
.kpi-card.amber { border-top-color: #f59e0b; }
.kpi-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.kpi-card.blue  .kpi-icon { background: #eff6ff; color: #2563eb; }
.kpi-card.green .kpi-icon { background: #f0fdf4; color: #16a34a; }
.kpi-card.amber .kpi-icon { background: #fffbeb; color: #d97706; }
.kpi-icon i { font-size: 1rem; }
.kpi-body { display: flex; flex-direction: column; gap: 2px; }
.kpi-label { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
.kpi-value { font-size: 1.4rem; font-weight: 700; color: #0f172a; line-height: 1.2; }
.kpi-value.blue  { color: #2563eb; }
.kpi-value.green { color: #16a34a; }
.kpi-value.amber { color: #d97706; }
.kpi-unit { font-size: 0.78rem; font-weight: 400; margin-left: 2px; opacity: 0.7; }

/* ── Tabel batch ── */
#tabelBatch thead th,
#tabelRiwayatPenyaluran thead th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
    background: #f8fafc;
    padding: 10px 12px;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
    vertical-align: middle;
}
#tabelBatch tbody td,
#tabelRiwayatPenyaluran tbody td {
    font-size: 13px;
    padding: 10px 12px;
    vertical-align: middle;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}
#tabelBatch tbody tr:hover td,
#tabelRiwayatPenyaluran tbody tr:hover td { background: #f8fafc; }

/* ── Section divider ── */
.section-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0 16px;
}
.section-divider hr {
    flex: 1;
    border-color: #e2e8f0;
    margin: 0;
}
.section-divider-label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    white-space: nowrap;
}
</style>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-layer-group me-2 text-primary"></i><?= esc($title) ?>
        </h1>
        <span class="subtle">
            Rincian batch &amp; riwayat penyaluran &middot;
            <strong class="text-slate-700"><?= esc($barang['nama_barang']) ?></strong>
        </span>
    </div>
    <a href="<?= site_url('transaksi/stok-gudang') ?>" class="btn btn-outline-secondary rounded-pill px-4" style="font-size:13px; height:38px; display:inline-flex; align-items:center; gap:6px;">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- KPI -->
<?php
    $totalBatch      = count($batches);
    $totalStok       = array_sum(array_column($batches, 'stok_saat_ini'));
    $totalPenyaluran = count($riwayat_penyaluran);
    $totalKeluar     = array_sum(array_column($riwayat_penyaluran, 'jumlah_keluar'));
?>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card blue">
            <div class="kpi-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="kpi-body">
                <span class="kpi-label">Batch Aktif</span>
                <span class="kpi-value blue"><?= $totalBatch ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card green">
            <div class="kpi-icon"><i class="fa-solid fa-warehouse"></i></div>
            <div class="kpi-body">
                <span class="kpi-label">Total Stok</span>
                <span class="kpi-value green">
                    <?= number_format($totalStok, 0, ',', '.') ?>
                    <span class="kpi-unit"><?= esc($batches[0]['satuan'] ?? '') ?></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card amber">
            <div class="kpi-icon"><i class="fa-solid fa-arrow-up-from-line"></i></div>
            <div class="kpi-body">
                <span class="kpi-label">Transaksi Keluar</span>
                <span class="kpi-value amber"><?= $totalPenyaluran ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card" style="border-top-color:#ef4444;">
            <div class="kpi-icon" style="background:#fef2f2; color:#dc2626;"><i class="fa-solid fa-weight-hanging"></i></div>
            <div class="kpi-body">
                <span class="kpi-label">Total Disalurkan</span>
                <span class="kpi-value" style="color:#dc2626;">
                    <?= number_format($totalKeluar, 0, ',', '.') ?>
                    <span class="kpi-unit"><?= esc($batches[0]['satuan'] ?? '') ?></span>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Batch -->
<div class="panel-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="panel-title">
            <i class="fa-solid fa-layer-group me-2"></i>Daftar Batch Aktif
        </h5>
        <span class="badge rounded-pill px-3 py-2"
              style="background:#eff6ff; color:#2563eb; font-size:11.5px; border:1px solid #bfdbfe;">
            <?= $totalBatch ?> Batch
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tabelBatch">
            <thead>
                <tr>
                    <th width="45" class="text-center">No</th>
                    <th>Nomor Batch</th>
                    <th>Donatur</th>
                    <th class="text-center">Tgl Masuk</th>
                    <th class="text-center">Tgl Expired</th>
                    <th class="text-center">Berat/Satuan</th>
                    <th class="text-center">Jml Awal</th>
                    <th class="text-end">Stok Saat Ini</th>
                    <th class="text-end">Total Berat</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($batches as $batch) : ?>
                    <?php
                        $bisaDipecah    = (int)($batch['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float)$batch['berat_per_satuan'];
                        $isDesimal      = ($bisaDipecah === 1 && floor($batch['stok_saat_ini']) != $batch['stok_saat_ini']);
                        $decimals       = $isDesimal ? 2 : 0;

                        if ($bisaDipecah === 1) {
                            $totalBeratRow = (float)$batch['stok_saat_ini'];
                            if (in_array(strtolower(trim($batch['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) $totalBeratRow *= 1000;
                        } else {
                            $totalBeratRow = $batch['stok_saat_ini'] * $beratPerSatuan;
                        }
                    ?>
                    <tr>
                        <td class="text-center text-muted"><?= $no++ ?></td>
                        <td><span class="badge bg-secondary"><?= esc($batch['nomor_batch']) ?></span></td>
                        <td><?= esc($batch['nama_donatur'] ?? '-') ?></td>
                        <td class="text-center"><?= date('d M Y', strtotime($batch['tanggal_masuk'])) ?></td>
                        <td class="text-center fw-bold text-danger"><?= date('d M Y', strtotime($batch['tanggal_kedaluwarsa'])) ?></td>
                        <td class="text-center"><?= $beratPerSatuan > 0 ? $beratPerSatuan . ' ' . esc($batch['satuan_berat']) : '-' ?></td>
                        <td class="text-center"><?= number_format($batch['jumlah_awal'], $decimals, ',', '.') ?> <small class="text-muted"><?= esc($batch['satuan']) ?></small></td>
                        <td class="text-end fw-bold" style="font-size:1.05rem;">
                            <?= number_format($batch['stok_saat_ini'], $decimals, ',', '.') ?>
                            <small class="text-muted fw-normal"><?= esc($batch['satuan']) ?></small>
                            <?php if (!empty($batch['menggunakan_kemasan']) && !empty($batch['jumlah_ctn']) && !empty($batch['isi_per_ctn'])) : ?>
                                <?php
                                    $isiCtn  = (int)$batch['isi_per_ctn'];
                                    $stkAkt  = (float)$batch['stok_saat_ini'];
                                    $ctnSisa = floor($stkAkt / $isiCtn);
                                    $pcsSisa = fmod($stkAkt, $isiCtn);
                                    if ($ctnSisa > 0 && $pcsSisa > 0)     $sisaStr = number_format($ctnSisa,0,',','.') . ' CTN + ' . number_format($pcsSisa,0,',','.') . ' ' . esc($batch['satuan']);
                                    elseif ($ctnSisa > 0)                  $sisaStr = number_format($ctnSisa,0,',','.') . ' CTN';
                                    elseif ($pcsSisa > 0)                  $sisaStr = number_format($pcsSisa,0,',','.') . ' ' . esc($batch['satuan']);
                                    else                                   $sisaStr = '0 CTN';
                                ?>
                                <div class="text-secondary fw-normal mt-1" style="font-size:11px;">
                                    <i class="fa-solid fa-box text-muted me-1"></i>Awal: <?= (int)$batch['jumlah_ctn'] ?> CTN &times; <?= (int)$batch['isi_per_ctn'] ?> <?= esc($batch['satuan']) ?><br>
                                    <i class="fa-solid fa-box-open text-primary me-1"></i>Sisa: <strong><?= $sisaStr ?></strong>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-muted">
                            <?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $batch['satuan_berat']) : '-' ?>
                        </td>
                        <td class="text-center">
                            <?php
                                $bc = 'bg-secondary';
                                if ($batch['status_dinamis'] == 'Aman')           $bc = 'bg-success';
                                elseif ($batch['status_dinamis'] == 'Hampir Expired') $bc = 'bg-warning text-dark';
                                elseif ($batch['status_dinamis'] == 'Expired')    $bc = 'bg-danger';
                            ?>
                            <span class="badge <?= $bc ?> px-3 py-2 rounded-pill" style="font-size:11.5px;">
                                <?= esc($batch['status_dinamis']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($batches)): ?>
                <tr>
                    <td colspan="10" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-box-open fs-3 mb-2 d-block"></i>
                        Belum ada batch aktif.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Divider -->
<div class="section-divider">
    <span class="section-divider-label"><i class="fa-solid fa-arrow-up-from-line me-1"></i>Riwayat Penyaluran</span>
    <hr>
</div>

<!-- Tabel Riwayat Penyaluran -->
<div class="panel-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="panel-title">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>Histori Transaksi Keluar
        </h5>
        <span class="badge rounded-pill px-3 py-2"
              style="background:#fef2f2; color:#dc2626; font-size:11.5px; border:1px solid #fecaca;">
            <?= $totalPenyaluran ?> Transaksi
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tabelRiwayatPenyaluran">
            <thead>
                <tr>
                    <th width="45" class="text-center">No</th>
                    <th>No. Transaksi</th>
                    <th>Tujuan Penyaluran</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">No. Batch</th>
                    <th class="text-center">Tgl Expired</th>
                    <th class="text-center">Tgl Keluar</th>
                    <th class="text-end">Jumlah Keluar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($riwayat_penyaluran)): ?>
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-inbox fs-3 mb-2 d-block"></i>
                        Belum ada riwayat penyaluran untuk barang ini.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($riwayat_penyaluran as $i => $r): ?>
                <tr>
                    <td class="text-center text-muted"><?= $i + 1 ?></td>
                    <td>
                        <a href="<?= site_url('transaksi/barang-keluar/detail/' . $r['id_barang_keluar']) ?>"
                           style="display:inline-block; background:#EEF4FF; color:#2563eb; font-size:12px;
                                  padding:3px 10px; border-radius:999px; text-decoration:none; font-weight:600;
                                  border:1px solid #bfdbfe;">
                            <?= esc($r['nomor_transaksi']) ?>
                        </a>
                    </td>
                    <td><?= esc($r['tujuan_penyaluran']) ?></td>
                    <td class="text-center">
                        <?php if ($r['jenis_penyaluran'] === 'Penyaluran Internal'): ?>
                            <span class="badge rounded-pill px-2 py-1" style="background:#F3E8FF; color:#7E22CE; font-size:11px;">Internal</span>
                        <?php else: ?>
                            <span class="badge rounded-pill px-2 py-1" style="background:#EFF6FF; color:#1D4ED8; font-size:11px;">Relawan</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary" style="font-size:11px;"><?= esc($r['nomor_batch']) ?></span>
                    </td>
                    <td class="text-center text-danger fw-bold" style="font-size:12px;">
                        <?= date('d M Y', strtotime($r['tanggal_kedaluwarsa'])) ?>
                    </td>
                    <td class="text-center" style="color:#475569;">
                        <?= date('d M Y', strtotime($r['tanggal_keluar'])) ?>
                    </td>
                    <td class="text-end fw-bold" style="color:#dc2626;">
                        <?php
                            $isDesimal = (int)($r['bisa_dipecah'] ?? 0) === 1
                                || floor($r['jumlah_keluar']) != $r['jumlah_keluar'];
                        ?>
                        -<?= number_format($r['jumlah_keluar'], $isDesimal ? 2 : 0, ',', '.') ?>
                        <small class="text-muted fw-normal"><?= esc($r['satuan']) ?></small>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    var dtLang = {
        emptyTable:   "Tidak ada data",
        info:         "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty:    "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered: "(disaring dari _MAX_ total data)",
        lengthMenu:   "Tampilkan _MENU_ data",
        loadingRecords: "Memuat...",
        processing:   "Memproses...",
        search:       "Cari:",
        zeroRecords:  "Tidak ditemukan data yang sesuai",
        paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
    };

    $('#tabelBatch').DataTable({
        language: dtLang,
        order: [[4, "asc"]],
        paging: false,
        info: false,
        searching: false
    });

    $('#tabelRiwayatPenyaluran').DataTable({
        language: dtLang,
        order: [[6, "desc"]],
        paging: false,
        info: false,
        searching: false
    });
});
</script>
<?= $this->endSection() ?>