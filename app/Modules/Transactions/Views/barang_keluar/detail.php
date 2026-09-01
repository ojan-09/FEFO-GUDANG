<?php helper('format'); ?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

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
    padding:16px 20px;gap:12px;flex-wrap:wrap;
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
.det-topbar-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.det-btn{
    display:inline-flex;align-items:center;gap:6px;
    height:34px;padding:0 14px;font-size:12px;font-weight:500;
    border-radius:999px;border:0.5px solid;text-decoration:none;
    transition:background .12s,color .12s;white-space:nowrap;
}
.det-btn.back  {background:#F8FAFC;color:#64748B;border-color:#CBD5E1}
.det-btn.back:hover{background:#F1F5F9;color:#1E293B}
.det-btn.red   {background:#DC2626;color:#fff;border-color:#DC2626}
.det-btn.red:hover{background:#B91C1C;color:#fff}
.det-btn.blue  {background:#2563EB;color:#fff;border-color:#2563EB}
.det-btn.blue:hover{background:#1D4ED8;color:#fff}

/* ── KPI ── */
.det-kpi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.det-kpi{
    background:#fff;border:0.5px solid #E2E8F0;border-radius:12px;
    padding:14px 16px;display:flex;align-items:center;gap:12px;
}
.det-kpi-icon{
    width:36px;height:36px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;font-size:14px;
}
.det-kpi.red   .det-kpi-icon{background:#FEF2F2;color:#DC2626}
.det-kpi.amber .det-kpi-icon{background:#FFFBEB;color:#D97706}
.det-kpi.blue  .det-kpi-icon{background:#EFF6FF;color:#2563EB}
.det-kpi-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;display:block;margin-bottom:3px}
.det-kpi-value{font-size:20px;font-weight:500;line-height:1.2;color:#0F172A}
.det-kpi.red   .det-kpi-value{color:#DC2626}
.det-kpi.amber .det-kpi-value{color:#D97706}
.det-kpi.blue  .det-kpi-value{color:#2563EB}
.det-kpi-unit{font-size:11px;font-weight:400;color:#94A3B8;margin-left:2px}
.det-kpi-sub{font-size:12px;color:#DC2626;line-height:1.4}
.det-kpi-sub .det-kpi-unit{color:#EF4444}

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
.det-card-badge.blue  {background:#EFF6FF;color:#2563EB;border-color:#BFDBFE}
.det-card-badge.red   {background:#FEF2F2;color:#DC2626;border-color:#FECACA}

/* ── META GRID ── */
.det-meta-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0}
.det-meta-item{padding:14px 18px;border-right:0.5px solid #F1F5F9;border-bottom:0.5px solid #F1F5F9}
.det-meta-item:nth-child(4n){border-right:none}
.det-meta-item:last-child{border-bottom:none}
.det-meta-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:5px}
.det-meta-value{font-size:13px;font-weight:500;color:#0F172A}

/* ── KETERANGAN BOX ── */
.det-ket-box{
    margin:0 18px 16px;padding:12px 16px;
    background:#F8FAFC;border:0.5px solid #E2E8F0;border-radius:10px;
}
.det-ket-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:6px}
.det-ket-text{font-size:13px;color:#334155;font-style:italic;line-height:1.6}

/* ── JENIS BADGE ── */
.det-jenis{
    display:inline-flex;align-items:center;height:22px;padding:0 9px;
    font-size:11px;font-weight:500;border-radius:999px;border:0.5px solid;
}
.det-jenis-internal{background:#F3E8FF;color:#7E22CE;border-color:#E9D5FF}
.det-jenis-relawan {background:#EFF6FF;color:#1D4ED8;border-color:#BFDBFE}

/* ── TABLE ── */
#tabelBatchKeluar{width:100%;border-collapse:collapse;margin:0}
#tabelBatchKeluar thead tr{background:#F8FAFC}
#tabelBatchKeluar thead th{
    padding:10px 14px;font-size:11px;font-weight:500;
    letter-spacing:.04em;text-transform:uppercase;color:#94A3B8;
    white-space:nowrap;border:none;border-bottom:0.5px solid #E2E8F0;
    vertical-align:middle;
}
#tabelBatchKeluar tbody tr{border-bottom:0.5px solid #F1F5F9;transition:background .1s}
#tabelBatchKeluar tbody tr:last-child{border-bottom:none}
#tabelBatchKeluar tbody tr:hover td{background:#F8FAFC}
#tabelBatchKeluar tbody td{
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
.det-nama{font-size:13px;font-weight:500;color:#0F172A}
.det-keluar-val{font-size:13px;font-weight:600;color:#DC2626}
.det-stok-unit{font-size:11px;font-weight:400;color:#94A3B8;margin-left:2px}
.det-habis{
    display:inline-flex;align-items:center;height:22px;padding:0 9px;
    font-size:11px;font-weight:500;border-radius:999px;
    background:#FEF2F2;color:#DC2626;
}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
    .det-kpi-grid{grid-template-columns:repeat(3,1fr)}
    .det-meta-grid{grid-template-columns:repeat(2,1fr)}
    .det-meta-item:nth-child(2n){border-right:none}
}
@media(max-width:640px){
    .det-kpi-grid{grid-template-columns:1fr}
    .det-meta-grid{grid-template-columns:1fr}
    .det-meta-item{border-right:none}
    .det-topbar{flex-direction:column;align-items:flex-start}
    .det-wrap{padding:14px 16px}
}
</style>

<?php
    $totalItemPerSatuan    = [];
    $totalKgRepack         = 0;
    $totalBeratKeseluruhan = 0;

    foreach ($details as $d) {
        $bisaDipecah    = (int)($d['bisa_dipecah'] ?? 0);
        $beratPerSatuan = (float)$d['berat_per_satuan'];

        if ($bisaDipecah === 1) {
            $totalKgRepack         += (float)$d['jumlah_keluar'];
            $beratBaris             = (float)$d['jumlah_keluar'];
        } else {
            $satuan = $d['satuan'] ?: 'Pcs';
            $totalItemPerSatuan[$satuan] = ($totalItemPerSatuan[$satuan] ?? 0) + (float)$d['jumlah_keluar'];
            $beratBaris = $d['jumlah_keluar'] * $beratPerSatuan;
            if (in_array(strtolower(trim($d['satuan_berat'] ?? '')), ['gram','g','gr','ml']))
                $beratBaris /= 1000;
        }
        $totalBeratKeseluruhan += $beratBaris;
    }
    $isInternal = ($barangKeluar['jenis_penyaluran'] ?? '') === 'Penyaluran Internal';
?>

<div class="det-wrap">

    <!-- TOPBAR -->
    <div class="det-topbar">
        <div class="det-topbar-left">
            <div class="det-icon-box">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div>
                <h1 class="det-title"><?= esc($title) ?></h1>
                <span class="det-subtitle">
                    Detail transaksi pengeluaran &middot; metode FEFO &middot;
                    <strong><?= esc($barangKeluar['nomor_transaksi']) ?></strong>
                </span>
            </div>
        </div>
        <div class="det-topbar-actions">
            <a href="<?= site_url('transaksi/barang-keluar/berita-acara/' . $barangKeluar['id']) ?>" target="_blank" class="det-btn red">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </a>
            <a href="<?= site_url('transaksi/barang-keluar/berita-acara-word/' . $barangKeluar['id']) ?>" class="det-btn blue">
                <i class="fa-solid fa-file-word"></i> Export Word
            </a>
            <a href="<?= site_url('transaksi/barang-keluar') ?>" class="det-btn back">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- KPI -->
    <div class="det-kpi-grid">
        <div class="det-kpi red">
            <div class="det-kpi-icon"><i class="fa-solid fa-scissors"></i></div>
            <div>
                <span class="det-kpi-label">Batch Dipotong</span>
                <div class="det-kpi-value"><?= count($details) ?></div>
            </div>
        </div>
        <div class="det-kpi red">
            <div class="det-kpi-icon"><i class="fa-solid fa-arrow-up-from-bracket"></i></div>
            <div>
                <span class="det-kpi-label">Total Barang Keluar</span>
                <div class="det-kpi-value">
                    <?php
                        $kpiParts = [];
                        foreach ($totalItemPerSatuan as $satuan => $jml)
                            $kpiParts[] = ['val' => number_format($jml, 0, ',', '.'), 'unit' => esc($satuan)];
                        if ($totalKgRepack > 0)
                            $kpiParts[] = ['val' => number_format($totalKgRepack, 2, ',', '.'), 'unit' => 'Kg'];
                        if (!empty($kpiParts)) {
                            $first = array_shift($kpiParts);
                            echo $first['val'] . ' <span class="det-kpi-unit">' . $first['unit'] . '</span>';
                            foreach ($kpiParts as $p)
                                echo '<div class="det-kpi-sub">' . $p['val'] . ' <span class="det-kpi-unit">' . $p['unit'] . '</span></div>';
                        } else { echo '0'; }
                    ?>
                </div>
            </div>
        </div>
        <div class="det-kpi amber">
            <div class="det-kpi-icon"><i class="fa-solid fa-weight-hanging"></i></div>
            <div>
                <span class="det-kpi-label">Total Berat Keluar</span>
                <div class="det-kpi-value">
                    <?php
                        $fw = format_berat($totalBeratKeseluruhan, 'Kg');
                        preg_match('/^([\d,\.]+)\s*(.*)$/', $fw, $m);
                        echo count($m) === 3
                            ? $m[1] . ' <span class="det-kpi-unit">' . $m[2] . '</span>'
                            : $fw;
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- INFO TRANSAKSI -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-circle-info"></i> Informasi Transaksi
            </span>
            <span class="det-card-badge blue"><?= esc($barangKeluar['nomor_transaksi']) ?></span>
        </div>
        <div class="det-meta-grid">
            <div class="det-meta-item">
                <div class="det-meta-label">Jenis Penyaluran</div>
                <div class="det-meta-value">
                    <span class="det-jenis <?= $isInternal ? 'det-jenis-internal' : 'det-jenis-relawan' ?>">
                        <?= $isInternal ? 'Penyaluran Internal' : 'Penyaluran Relawan' ?>
                    </span>
                </div>
            </div>
            <?php if (!empty($barangKeluar['penerima_relawan'])) : ?>
            <div class="det-meta-item">
                <div class="det-meta-label">Nama Relawan / Penerima</div>
                <div class="det-meta-value"><?= esc($barangKeluar['penerima_relawan']) ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($barangKeluar['unit_internal'])) : ?>
            <div class="det-meta-item">
                <div class="det-meta-label">Unit / Bagian Internal</div>
                <div class="det-meta-value"><?= esc($barangKeluar['unit_internal']) ?></div>
            </div>
            <?php endif; ?>
            <div class="det-meta-item">
                <div class="det-meta-label">Tujuan Penyaluran</div>
                <div class="det-meta-value"><?= esc($barangKeluar['tujuan_penyaluran']) ?></div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Wilayah Tujuan</div>
                <div class="det-meta-value"><?= esc($barangKeluar['nama_wilayah'] ?? '-') ?></div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Tanggal Keluar</div>
                <div class="det-meta-value">
                    <i class="fa-regular fa-calendar" style="color:#94A3B8;margin-right:4px"></i>
                    <?= date('d M Y', strtotime($barangKeluar['tanggal_keluar'])) ?>
                </div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Petugas</div>
                <div class="det-meta-value">
                    <i class="fa-regular fa-user" style="color:#94A3B8;margin-right:4px"></i>
                    <?= esc($barangKeluar['petugas']) ?>
                </div>
            </div>
        </div>
        <?php if (!empty($barangKeluar['keterangan'])) : ?>
        <div class="det-ket-box">
            <div class="det-ket-label"><i class="fa-solid fa-note-sticky" style="margin-right:4px"></i>Keterangan</div>
            <div class="det-ket-text"><?= nl2br(esc($barangKeluar['keterangan'])) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- TABEL BATCH -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-scissors"></i> Batch yang Dipotong oleh FEFO
            </span>
            <span class="det-card-badge red"><?= count($details) ?> Batch</span>
        </div>
        <div>
            <table id="tabelBatchKeluar">
                <thead>
                    <tr>
                        <th width="45" class="text-center">No</th>
                        <th>No. Batch</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Tgl Kedaluwarsa</th>
                        <th class="text-end">Diambil</th>
                        <th class="text-end">Berat</th>
                        <th class="text-end">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $i => $d) :
                        $bisaDipecah    = (int)($d['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float)$d['berat_per_satuan'];

                        if ($bisaDipecah === 1) {
                            $beratBarisDisplay = format_berat((float)$d['jumlah_keluar'], 'Kg');
                            $jumlahDisplay     = format_jumlah($d['jumlah_keluar']);
                            $stokDisplay       = format_jumlah($d['stok_saat_ini']);
                        } else {
                            $beratBaris        = $d['jumlah_keluar'] * $beratPerSatuan;
                            $beratBarisDisplay = format_berat($beratBaris, $d['satuan_berat']);
                            $jumlahDisplay     = format_jumlah($d['jumlah_keluar']);
                            $stokDisplay       = format_jumlah($d['stok_saat_ini']);
                        }
                    ?>
                    <tr>
                        <td class="text-center"><span class="det-no"><?= $i + 1 ?></span></td>
                        <td><span class="det-batch-no"><?= esc($d['nomor_batch']) ?></span></td>
                        <td><span class="det-nama"><?= esc($d['nama_barang']) ?></span></td>
                        <td class="text-center" style="font-size:12px;font-weight:500;color:#DC2626">
                            <?= date('d M Y', strtotime($d['tanggal_kedaluwarsa'])) ?>
                        </td>
                        <td class="text-end">
                            <span class="det-keluar-val">-<?= $jumlahDisplay ?></span>
                            <span class="det-stok-unit"><?= esc($d['satuan']) ?></span>
                        </td>
                        <td class="text-end" style="color:#64748B;font-size:12px">
                            <?= $beratBarisDisplay ?>
                        </td>
                        <td class="text-end">
                            <?php if ($d['stok_saat_ini'] <= 0) : ?>
                                <span class="det-habis">Habis</span>
                            <?php else : ?>
                                <span style="font-size:13px;font-weight:500;color:#0F172A"><?= $stokDisplay ?></span>
                                <span class="det-stok-unit"><?= esc($d['satuan']) ?></span>
                            <?php endif; ?>
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
    $('#tabelBatchKeluar').DataTable({
        language: {
            emptyTable:   "Tidak ada data",
            info:         "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty:    "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            lengthMenu:   "Tampilkan _MENU_ data",
            search:       "Cari:",
            zeroRecords:  "Tidak ditemukan data yang sesuai",
            paginate:{ first:"Pertama", last:"Terakhir", next:"Selanjutnya", previous:"Sebelumnya" }
        },
        order: [[3, "asc"]],
        paging: false,
        info: false,
        searching: false,
        scrollX: false
    });
});
</script>
<?= $this->endSection() ?>