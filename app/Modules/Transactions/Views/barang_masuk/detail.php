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
.det-kpi.purple .det-kpi-icon{background:#F3E8FF;color:#7E22CE}
.det-kpi-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;display:block;margin-bottom:3px}
.det-kpi-value{font-size:20px;font-weight:500;line-height:1;color:#0F172A}
.det-kpi.blue   .det-kpi-value{color:#2563EB}
.det-kpi.green  .det-kpi-value{color:#16A34A}
.det-kpi.amber  .det-kpi-value{color:#D97706}
.det-kpi.purple .det-kpi-value{color:#7E22CE}
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
.det-card-badge.blue  {background:#EFF6FF;color:#2563EB;border-color:#BFDBFE}
.det-card-badge.green {background:#F0FDF4;color:#16A34A;border-color:#BBF7D0}
.det-card-badge.slate {background:#F1F5F9;color:#475569;border-color:#CBD5E1}

/* ── META GRID ── */
.det-meta-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0}
.det-meta-item{padding:14px 18px;border-right:0.5px solid #F1F5F9;border-bottom:0.5px solid #F1F5F9}
.det-meta-item:last-child{border-right:none}
.det-meta-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:5px}
.det-meta-value{font-size:13px;font-weight:500;color:#0F172A}

/* ── KETERANGAN BOX ── */
.det-ket-box{
    margin:0 18px 16px;padding:12px 16px;
    background:#F8FAFC;border:0.5px solid #E2E8F0;border-radius:10px;
}
.det-ket-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:6px}
.det-ket-text{font-size:13px;color:#334155;font-style:italic;line-height:1.6}

/* ── TABLE ── */
#tabelBatch{width:100%;border-collapse:collapse;margin:0}
#tabelBatch thead tr{background:#F8FAFC}
#tabelBatch thead th{
    padding:10px 14px;font-size:11px;font-weight:500;
    letter-spacing:.04em;text-transform:uppercase;color:#94A3B8;
    white-space:nowrap;border:none;border-bottom:0.5px solid #E2E8F0;
    vertical-align:middle;
}
#tabelBatch tbody tr{border-bottom:0.5px solid #F1F5F9;transition:background .1s}
#tabelBatch tbody tr:last-child{border-bottom:none}
#tabelBatch tbody tr:hover td{background:#F8FAFC}
#tabelBatch tbody td{
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
.det-stok-val{font-size:14px;font-weight:500;color:#0F172A}
.det-stok-unit{font-size:11px;font-weight:400;color:#94A3B8;margin-left:2px}
.det-stok-warn{font-size:13px;font-weight:600;color:#D97706}
.det-status{
    display:inline-flex;align-items:center;height:22px;padding:0 9px;
    font-size:11px;font-weight:500;border-radius:999px;
}
.det-status-aman   {background:#F0FDF4;color:#15803D}
.det-status-habis  {background:#F1F5F9;color:#475569}
.det-status-expired{background:#FEF2F2;color:#DC2626}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
    .det-kpi-grid{grid-template-columns:repeat(2,1fr)}
    .det-meta-grid{grid-template-columns:repeat(2,1fr)}
    .det-meta-item:nth-child(2){border-right:none}
    .det-meta-item:nth-child(3){border-top:0.5px solid #F1F5F9}
}
@media(max-width:640px){
    .det-kpi-grid{grid-template-columns:repeat(2,1fr)}
    .det-meta-grid{grid-template-columns:1fr}
    .det-meta-item{border-right:none}
    .det-topbar{flex-direction:column;align-items:flex-start}
    .det-wrap{padding:14px 16px}
}
</style>

<div class="det-wrap">

    <!-- TOPBAR -->
    <div class="det-topbar">
        <div class="det-topbar-left">
            <div class="det-icon-box">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <h1 class="det-title"><?= esc($title) ?></h1>
                <span class="det-subtitle">
                    Detail transaksi donasi masuk &middot;
                    <strong><?= esc($barangMasuk['nomor_transaksi']) ?></strong>
                </span>
            </div>
        </div>
        <a href="<?= site_url('transaksi/barang-masuk') ?>" class="det-btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- KPI -->
    <?php
        $totalBatch     = count($batches);
        $totalJumlah    = array_sum(array_column($batches, 'jumlah_awal'));
        $totalStokNow   = array_sum(array_column($batches, 'stok_saat_ini'));
        $batchAktif     = count(array_filter($batches, fn($b) => $b['status'] === 'Aktif'));
    ?>
    <div class="det-kpi-grid">
        <div class="det-kpi blue">
            <div class="det-kpi-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div>
                <span class="det-kpi-label">Total Batch</span>
                <div class="det-kpi-value"><?= $totalBatch ?></div>
            </div>
        </div>
        <div class="det-kpi green">
            <div class="det-kpi-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <span class="det-kpi-label">Batch Aktif</span>
                <div class="det-kpi-value"><?= $batchAktif ?></div>
            </div>
        </div>
        <div class="det-kpi amber">
            <div class="det-kpi-icon"><i class="fa-solid fa-scale-balanced"></i></div>
            <div>
                <span class="det-kpi-label">Jumlah Masuk</span>
                <div class="det-kpi-value">
                    <?= number_format($totalJumlah, 0, ',', '.') ?>
                </div>
            </div>
        </div>
        <div class="det-kpi purple">
            <div class="det-kpi-icon"><i class="fa-solid fa-warehouse"></i></div>
            <div>
                <span class="det-kpi-label">Sisa Stok</span>
                <div class="det-kpi-value">
                    <?= number_format($totalStokNow, 0, ',', '.') ?>
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
            <span class="det-card-badge blue"><?= esc($barangMasuk['nomor_transaksi']) ?></span>
        </div>
        <div class="det-meta-grid">
            <div class="det-meta-item">
                <div class="det-meta-label">Donatur</div>
                <div class="det-meta-value"><?= esc($barangMasuk['nama_donatur']) ?></div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Tanggal Masuk</div>
                <div class="det-meta-value">
                    <i class="fa-regular fa-calendar" style="color:#94A3B8;margin-right:4px"></i>
                    <?= date('d M Y', strtotime($barangMasuk['tanggal_masuk'])) ?>
                </div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">ETA</div>
                <div class="det-meta-value">
                    <?= $barangMasuk['eta'] ? '<i class="fa-regular fa-clock" style="color:#94A3B8;margin-right:4px"></i>' . date('d M Y', strtotime($barangMasuk['eta'])) : '-' ?>
                </div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Petugas</div>
                <div class="det-meta-value">
                    <i class="fa-regular fa-user" style="color:#94A3B8;margin-right:4px"></i>
                    <?= esc($barangMasuk['petugas']) ?>
                </div>
            </div>
        </div>
        <?php if (!empty($barangMasuk['keterangan'])) : ?>
        <div class="det-ket-box">
            <div class="det-ket-label"><i class="fa-solid fa-note-sticky" style="margin-right:4px"></i>Keterangan</div>
            <div class="det-ket-text"><?= nl2br(esc($barangMasuk['keterangan'])) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- TABEL BATCH -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-boxes-stacked"></i> Daftar Batch
            </span>
            <span class="det-card-badge blue"><?= $totalBatch ?> Batch</span>
        </div>
        <div>
            <table id="tabelBatch">
                <thead>
                    <tr>
                        <th width="45" class="text-center">No</th>
                        <th>No. Batch</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-end">Berat Total</th>
                        <th class="text-end">Stok Saat Ini</th>
                        <th class="text-center">Tgl Kedaluwarsa</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($batches as $i => $b) :
                        $bisaDipecah    = (int)($b['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float)$b['berat_per_satuan'];

                        if ($bisaDipecah === 1) {
                            $beratTotal     = (float)$b['jumlah_awal'];
                            $satuanJumlah   = 'Kg';
                            $jumlahDisplay  = number_format($b['jumlah_awal'], 2, ',', '.');
                            $stokDisplay    = number_format($b['stok_saat_ini'], 2, ',', '.');
                        } else {
                            $beratTotal = $b['jumlah_awal'] * $beratPerSatuan;
                            if (in_array(strtolower(trim($b['satuan_berat'] ?? '')), ['gram','g','gr','ml']))
                                $beratTotal /= 1000;
                            $satuanJumlah  = esc($b['satuan']);
                            $jumlahDisplay = number_format($b['jumlah_awal'], 0, ',', '.');
                            $stokDisplay   = number_format($b['stok_saat_ini'], 0, ',', '.');
                        }

                        $statusClass = match($b['status']) {
                            'Aktif'   => 'det-status-aman',
                            'Habis'   => 'det-status-habis',
                            'Expired' => 'det-status-expired',
                            default   => 'det-status-habis',
                        };
                        $stokBerkurang = $b['stok_saat_ini'] < $b['jumlah_awal'];
                    ?>
                    <tr>
                        <td class="text-center"><span class="det-no"><?= $i + 1 ?></span></td>
                        <td><span class="det-batch-no"><?= esc($b['nomor_batch']) ?></span></td>
                        <td><span class="det-nama"><?= esc($b['nama_barang']) ?></span></td>
                        <td style="color:#64748B;font-size:12px"><?= esc($b['kategori'] ?? '-') ?></td>
                        <td class="text-end">
                            <span class="det-stok-val"><?= $jumlahDisplay ?></span>
                            <span class="det-stok-unit"><?= $satuanJumlah ?></span>
                        </td>
                        <td class="text-end" style="color:#64748B;font-size:12px">
                            <?= $beratTotal > 0 ? format_berat($beratTotal, 'Kg') : '-' ?>
                        </td>
                        <td class="text-end">
                            <?php if ($stokBerkurang) : ?>
                                <span class="det-stok-warn"><?= $stokDisplay ?></span>
                                <span class="det-stok-unit">/ <?= $jumlahDisplay ?> <?= $satuanJumlah ?></span>
                            <?php else : ?>
                                <span class="det-stok-val"><?= $stokDisplay ?></span>
                                <span class="det-stok-unit"><?= $satuanJumlah ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center" style="font-size:12px;font-weight:500;color:#DC2626">
                            <?= date('d M Y', strtotime($b['tanggal_kedaluwarsa'])) ?>
                        </td>
                        <td class="text-center">
                            <span class="det-status <?= $statusClass ?>"><?= esc($b['status']) ?></span>
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
    $('#tabelBatch').DataTable({
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
        order: [[7, "asc"]],
        paging: false,
        info: false,
        searching: false,
        scrollX: false
    });
});
</script>
<?= $this->endSection() ?>