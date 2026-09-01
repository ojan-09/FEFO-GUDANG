<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php helper('format'); ?>

<style>
*{box-sizing:border-box}

.det-wrap{
    max-width:1500px;margin:0 auto;padding:20px 28px;
    display:flex;flex-direction:column;gap:16px;
    font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;
}

/* ── TOPBAR ── */
.det-topbar{
    display:flex;align-items:center;justify-content:space-between;
    background:#fff;border:0.5px solid #E2E8F0;border-radius:14px;
    padding:16px 20px;gap:12px;
}
.det-topbar-left{display:flex;align-items:center;gap:12px}
.det-icon-box{
    width:38px;height:38px;background:#2563EB;border-radius:10px;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:15px;flex-shrink:0;
}
.det-title{font-size:17px;font-weight:500;color:#0F172A;margin:0;line-height:1.2}
.det-subtitle{font-size:12px;color:#64748B;display:block;margin-top:2px}
.det-subtitle strong{color:#334155;font-weight:500}
.det-btn-back{
    display:inline-flex;align-items:center;gap:6px;
    height:34px;padding:0 14px;font-size:12px;font-weight:500;
    border-radius:999px;background:#F8FAFC;color:#64748B;
    border:0.5px solid #CBD5E1;text-decoration:none;
    transition:background .12s,color .12s;
}
.det-btn-back:hover{background:#F1F5F9;color:#1E293B}

/* ── KPI ── */
.det-kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.det-kpi{
    background:#fff;border:0.5px solid #E2E8F0;border-radius:12px;
    padding:14px 16px;display:flex;align-items:center;gap:12px;
}
.det-kpi-icon{
    width:36px;height:36px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;font-size:14px;
}
.det-kpi.blue  .det-kpi-icon{background:#EFF6FF;color:#2563EB}
.det-kpi.green .det-kpi-icon{background:#F0FDF4;color:#16A34A}
.det-kpi.amber .det-kpi-icon{background:#FFFBEB;color:#D97706}
.det-kpi.red   .det-kpi-icon{background:#FEF2F2;color:#DC2626}
.det-kpi-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;display:block;margin-bottom:3px}
.det-kpi-value{font-size:20px;font-weight:500;line-height:1;color:#0F172A}
.det-kpi.blue  .det-kpi-value{color:#2563EB}
.det-kpi.green .det-kpi-value{color:#16A34A}
.det-kpi.amber .det-kpi-value{color:#D97706}
.det-kpi.red   .det-kpi-value{color:#DC2626}
.det-kpi-unit{font-size:11px;font-weight:400;color:#94A3B8;margin-left:2px}

/* ── CARD ── */
.det-card{background:#fff;border:0.5px solid #E2E8F0;border-radius:14px;overflow:hidden}
.det-card-header{
    display:flex;align-items:center;justify-content:space-between;
    padding:12px 18px;border-bottom:0.5px solid #E2E8F0;background:#F8FAFC;
}
.det-card-title{
    font-size:11px;font-weight:500;text-transform:uppercase;
    letter-spacing:.06em;color:#64748B;display:flex;align-items:center;gap:7px;
}
.det-card-badge{
    display:inline-flex;align-items:center;height:22px;padding:0 9px;
    font-size:11px;font-weight:500;border-radius:999px;border:0.5px solid;
}
.det-card-badge.blue{background:#EFF6FF;color:#2563EB;border-color:#BFDBFE}
.det-card-badge.red {background:#FEF2F2;color:#DC2626;border-color:#FECACA}

/* ── TABLES — tanpa scrollbar ── */
#tabelBatch,
#tabelRiwayatPenyaluran{width:100%;border-collapse:collapse;margin:0}

#tabelBatch thead tr,
#tabelRiwayatPenyaluran thead tr{background:#F8FAFC}

#tabelBatch thead th,
#tabelRiwayatPenyaluran thead th{
    padding:10px 14px;font-size:11px;font-weight:500;
    letter-spacing:.04em;text-transform:uppercase;color:#94A3B8;
    white-space:nowrap;border:none;border-bottom:0.5px solid #E2E8F0;
    vertical-align:middle;
}
#tabelBatch tbody tr,
#tabelRiwayatPenyaluran tbody tr{border-bottom:0.5px solid #F1F5F9;transition:background .1s}
#tabelBatch tbody tr:last-child,
#tabelRiwayatPenyaluran tbody tr:last-child{border-bottom:none}
#tabelBatch tbody tr:hover td,
#tabelRiwayatPenyaluran tbody tr:hover td{background:#F8FAFC}
#tabelBatch tbody td,
#tabelRiwayatPenyaluran tbody td{
    padding:10px 14px;font-size:13px;color:#334155;
    vertical-align:middle;border:none;
}

/* ── CELL HELPERS ── */
.det-no{color:#CBD5E1;font-size:12px}
.det-batch-no{
    display:inline-flex;align-items:center;height:20px;padding:0 8px;
    background:#F1F5F9;color:#475569;font-size:11px;font-weight:500;
    border-radius:5px;border:0.5px solid #CBD5E1;letter-spacing:.02em;
}
.det-stok-val{font-size:14px;font-weight:500;color:#0F172A}
.det-stok-unit{font-size:11px;font-weight:400;color:#94A3B8;margin-left:2px}
.det-ctn-meta{font-size:11px;color:#94A3B8;margin-top:3px;line-height:1.5}
.det-status{
    display:inline-flex;align-items:center;height:22px;padding:0 9px;
    font-size:11px;font-weight:500;border-radius:999px;
}
.det-status-aman   {background:#F0FDF4;color:#15803D}
.det-status-hampir {background:#FFFBEB;color:#B45309}
.det-status-expired{background:#FEF2F2;color:#DC2626}
.det-status-default{background:#F1F5F9;color:#64748B}
.det-trx-link{
    display:inline-flex;align-items:center;height:20px;padding:0 8px;
    background:#EFF6FF;color:#2563EB;font-size:11px;font-weight:500;
    border-radius:999px;text-decoration:none;border:0.5px solid #BFDBFE;
    transition:background .1s,color .1s;
}
.det-trx-link:hover{background:#DBEAFE;color:#1D4ED8}
.det-jenis{
    display:inline-flex;align-items:center;height:20px;padding:0 8px;
    font-size:11px;font-weight:500;border-radius:999px;
}
.det-jenis-internal{background:#F3E8FF;color:#7E22CE}
.det-jenis-relawan {background:#EFF6FF;color:#1D4ED8}
.det-keluar-val{font-size:13px;font-weight:500;color:#DC2626}

/* ── DIVIDER ── */
.det-divider{display:flex;align-items:center;gap:10px}
.det-divider hr{flex:1;border:none;border-top:0.5px solid #E2E8F0;margin:0}
.det-divider-label{
    font-size:11px;font-weight:500;text-transform:uppercase;
    letter-spacing:.07em;color:#94A3B8;white-space:nowrap;
    display:flex;align-items:center;gap:6px;
}

/* ── RESPONSIVE ── */
@media(max-width:1024px){.det-kpi-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:640px){
    .det-kpi-grid{grid-template-columns:repeat(2,1fr)}
    .det-topbar{flex-direction:column;align-items:flex-start}
    .det-wrap{padding:14px 16px}
}
</style>

<div class="det-wrap">

    <!-- TOPBAR -->
    <div class="det-topbar">
        <div class="det-topbar-left">
            <div class="det-icon-box">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <h1 class="det-title"><?= esc($title) ?></h1>
                <span class="det-subtitle">
                    Rincian batch &amp; riwayat penyaluran &middot;
                    <strong><?= esc($barang['nama_barang']) ?></strong>
                </span>
            </div>
        </div>
        <a href="<?= site_url('transaksi/stok-gudang') ?>" class="det-btn-back">
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
    <div class="det-kpi-grid">
        <div class="det-kpi blue">
            <div class="det-kpi-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div>
                <span class="det-kpi-label">Batch Aktif</span>
                <div class="det-kpi-value"><?= $totalBatch ?></div>
            </div>
        </div>
        <div class="det-kpi green">
            <div class="det-kpi-icon"><i class="fa-solid fa-warehouse"></i></div>
            <div>
                <span class="det-kpi-label">Total Stok</span>
                <div class="det-kpi-value">
                    <?= number_format($totalStok, 0, ',', '.') ?>
                    <span class="det-kpi-unit"><?= esc($batches[0]['satuan'] ?? '') ?></span>
                </div>
            </div>
        </div>
        <div class="det-kpi amber">
    <div class="det-kpi-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
            <div>
                <span class="det-kpi-label">Transaksi Keluar</span>
                <div class="det-kpi-value"><?= $totalPenyaluran ?></div>
            </div>
        </div>
        <div class="det-kpi red">
            <div class="det-kpi-icon"><i class="fa-solid fa-weight-hanging"></i></div>
            <div>
                <span class="det-kpi-label">Total Disalurkan</span>
                <div class="det-kpi-value">
                    <?= number_format($totalKeluar, 0, ',', '.') ?>
                    <span class="det-kpi-unit"><?= esc($batches[0]['satuan'] ?? '') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL BATCH -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-layer-group"></i> Daftar Batch Aktif
            </span>
            <span class="det-card-badge blue"><?= $totalBatch ?> Batch</span>
        </div>
        <div>
            <table id="tabelBatch">
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
                            if (in_array(strtolower(trim($batch['satuan_berat'] ?? '')), ['gram','g','gr','ml']))
                                $totalBeratRow *= 1000;
                        } else {
                            $totalBeratRow = $batch['stok_saat_ini'] * $beratPerSatuan;
                        }

                        $sd = $batch['status_dinamis'];
                        $statusClass = match(true) {
                            $sd === 'Aman'           => 'det-status-aman',
                            $sd === 'Hampir Expired' => 'det-status-hampir',
                            $sd === 'Expired'        => 'det-status-expired',
                            default                  => 'det-status-default',
                        };
                    ?>
                    <tr>
                        <td class="text-center"><span class="det-no"><?= $no++ ?></span></td>
                        <td><span class="det-batch-no"><?= esc($batch['nomor_batch']) ?></span></td>
                        <td style="color:#475569"><?= esc($batch['nama_donatur'] ?? '-') ?></td>
                        <td class="text-center" style="color:#64748B;font-size:12px">
                            <?= !empty($batch['tanggal_masuk']) ? date('d M Y', strtotime($batch['tanggal_masuk'])) : '-' ?>
                        </td>
                        <td class="text-center" style="font-size:12px;font-weight:500;color:#DC2626">
                            <?= !empty($batch['tanggal_kedaluwarsa']) ? date('d M Y', strtotime($batch['tanggal_kedaluwarsa'])) : '-' ?>
                        </td>
                        <td class="text-center" style="color:#64748B;font-size:12px">
                            <?= $beratPerSatuan > 0 ? $beratPerSatuan . ' ' . esc($batch['satuan_berat']) : '-' ?>
                        </td>
                        <td class="text-center" style="font-size:13px">
                            <?= number_format($batch['jumlah_awal'], $decimals, ',', '.') ?>
                            <small style="color:#94A3B8"><?= esc($batch['satuan']) ?></small>
                        </td>
                        <td class="text-end">
                            <span class="det-stok-val">
                                <?= number_format($batch['stok_saat_ini'], $decimals, ',', '.') ?>
                            </span>
                            <span class="det-stok-unit"><?= esc($batch['satuan']) ?></span>
                            <?php if (!empty($batch['menggunakan_kemasan']) && !empty($batch['jumlah_ctn']) && !empty($batch['isi_per_ctn'])) : ?>
                            <?php
                                $isiCtn  = (int)$batch['isi_per_ctn'];
                                $stkAkt  = (float)$batch['stok_saat_ini'];
                                $ctnSisa = floor($stkAkt / $isiCtn);
                                $pcsSisa = fmod($stkAkt, $isiCtn);
                                if ($ctnSisa > 0 && $pcsSisa > 0)
                                    $sisaStr = number_format($ctnSisa,0,',','.') . ' CTN + ' . number_format($pcsSisa,0,',','.') . ' ' . esc($batch['satuan']);
                                elseif ($ctnSisa > 0) $sisaStr = number_format($ctnSisa,0,',','.') . ' CTN';
                                elseif ($pcsSisa > 0) $sisaStr = number_format($pcsSisa,0,',','.') . ' ' . esc($batch['satuan']);
                                else                  $sisaStr = '0 CTN';
                            ?>
                            <div class="det-ctn-meta">
                                <i class="fa-solid fa-box" style="color:#94A3B8"></i>
                                Awal: <?= (int)$batch['jumlah_ctn'] ?> CTN &times; <?= (int)$batch['isi_per_ctn'] ?> <?= esc($batch['satuan']) ?><br>
                                <i class="fa-solid fa-box-open" style="color:#2563EB"></i>
                                Sisa: <strong style="color:#0F172A"><?= $sisaStr ?></strong>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-end" style="color:#64748B;font-size:12px">
                            <?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $batch['satuan_berat']) : '-' ?>
                        </td>
                        <td class="text-center">
                            <span class="det-status <?= $statusClass ?>">
                                <?= esc($batch['status_dinamis']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- DIVIDER -->
    <div class="det-divider">
        <span class="det-divider-label">
            <i class="fa-solid fa-arrow-up-from-line"></i> Riwayat Penyaluran
        </span>
        <hr>
    </div>

    <!-- TABEL RIWAYAT -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-clock-rotate-left"></i> Histori Transaksi Keluar
            </span>
            <span class="det-card-badge red"><?= $totalPenyaluran ?> Transaksi</span>
        </div>
        <div>
            <table id="tabelRiwayatPenyaluran">
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
                    <?php foreach ($riwayat_penyaluran as $i => $r) : ?>
                    <tr>
                        <td class="text-center"><span class="det-no"><?= $i + 1 ?></span></td>
                        <td>
                            <a href="<?= site_url('transaksi/barang-keluar/detail/' . $r['id_barang_keluar']) ?>" class="det-trx-link">
                                <?= esc($r['nomor_transaksi'] ?? '-') ?>
                            </a>
                        </td>
                        <td style="color:#475569"><?= esc($r['tujuan_penyaluran'] ?? '-') ?></td>
                        <td class="text-center">
                            <?php if (($r['jenis_penyaluran'] ?? '') === 'Penyaluran Internal') : ?>
                                <span class="det-jenis det-jenis-internal">Internal</span>
                            <?php else : ?>
                                <span class="det-jenis det-jenis-relawan">Relawan</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="det-batch-no"><?= esc($r['nomor_batch'] ?? '-') ?></span>
                        </td>
                        <td class="text-center" style="font-size:12px;font-weight:500;color:#DC2626">
                            <?= !empty($r['tanggal_kedaluwarsa']) ? date('d M Y', strtotime($r['tanggal_kedaluwarsa'])) : '-' ?>
                        </td>
                        <td class="text-center" style="font-size:12px;color:#64748B">
                            <?= !empty($r['tanggal_keluar']) ? date('d M Y', strtotime($r['tanggal_keluar'])) : '-' ?>
                        </td>
                        <td class="text-end">
                            <?php
                                $jmlKeluar = $r['jumlah_keluar'] ?? 0;
                                $isDesimal = (int)($r['bisa_dipecah'] ?? 0) === 1 || floor($jmlKeluar) != $jmlKeluar;
                            ?>
                            <span class="det-keluar-val">
                                -<?= number_format($jmlKeluar, $isDesimal ? 2 : 0, ',', '.') ?>
                            </span>
                            <small style="color:#94A3B8;font-weight:400"><?= esc($r['satuan'] ?? '') ?></small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    var dtLang = {
        emptyTable:     "Tidak ada data",
        info:           "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty:      "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered:   "(disaring dari _MAX_ total data)",
        lengthMenu:     "Tampilkan _MENU_ data",
        loadingRecords: "Memuat...",
        processing:     "Memproses...",
        search:         "Cari:",
        zeroRecords:    "Tidak ditemukan data yang sesuai",
        paginate:{ first:"Pertama", last:"Terakhir", next:"Selanjutnya", previous:"Sebelumnya" }
    };

    $('#tabelBatch').DataTable({
        language: dtLang,
        order: [[4, "asc"]],
        paging: false,
        info: false,
        searching: false,
        scrollX: false
    });

    $('#tabelRiwayatPenyaluran').DataTable({
        language: dtLang,
        order: [[6, "desc"]],
        paging: false,
        info: false,
        searching: false,
        scrollX: false
    });
});
</script>
<?= $this->endSection() ?>