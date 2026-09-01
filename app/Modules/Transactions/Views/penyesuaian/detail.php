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
.det-card-badge.amber {background:#FFFBEB;color:#D97706;border-color:#FDE68A}
.det-card-badge.red   {background:#FEF2F2;color:#DC2626;border-color:#FECACA}

/* ── META GRID ── */
.det-meta-grid{
    display:grid;grid-template-columns:repeat(4,1fr);
    gap:0;border-bottom:0.5px solid #E2E8F0;
}
.det-meta-item{padding:16px 20px;border-right:0.5px solid #F1F5F9}
.det-meta-item:last-child{border-right:none}
.det-meta-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:5px}
.det-meta-value{font-size:13px;font-weight:500;color:#0F172A}
.det-meta-value.mono{font-size:15px;color:#2563EB;font-weight:600;letter-spacing:.01em}

/* ── KETERANGAN BOX ── */
.det-ket-box{
    margin:16px 20px;padding:12px 16px;
    background:#F8FAFC;border:0.5px solid #E2E8F0;border-radius:10px;
}
.det-ket-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:6px}
.det-ket-text{font-size:13px;color:#334155;font-style:italic;line-height:1.6}

/* ── TABLE ── */
#tabelDetail{width:100%;border-collapse:collapse;margin:0}
#tabelDetail thead tr{background:#F8FAFC}
#tabelDetail thead th{
    padding:10px 14px;font-size:11px;font-weight:500;
    letter-spacing:.04em;text-transform:uppercase;color:#94A3B8;
    white-space:nowrap;border:none;border-bottom:0.5px solid #E2E8F0;
    vertical-align:middle;
}
#tabelDetail tbody tr{border-bottom:0.5px solid #F1F5F9;transition:background .1s}
#tabelDetail tbody tr:last-child{border-bottom:none}
#tabelDetail tbody tr:hover td{background:#F8FAFC}
#tabelDetail tbody td{
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
.det-plus{font-size:13px;font-weight:600;color:#16A34A}
.det-minus{font-size:13px;font-weight:600;color:#DC2626}
.det-jenis-badge{
    display:inline-flex;align-items:center;height:22px;padding:0 10px;
    font-size:11px;font-weight:500;border-radius:999px;border:0.5px solid;
}
.det-jenis-positif{background:#F0FDF4;color:#15803D;border-color:#BBF7D0}
.det-jenis-negatif{background:#FEF2F2;color:#DC2626;border-color:#FECACA}

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
    .det-meta-item{border-right:none;border-bottom:0.5px solid #F1F5F9}
    .det-topbar{flex-direction:column;align-items:flex-start}
    .det-wrap{padding:14px 16px}
}
</style>

<div class="det-wrap">

    <!-- TOPBAR -->
    <div class="det-topbar">
        <div class="det-topbar-left">
            <div class="det-icon-box">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div>
                <h1 class="det-title">Detail Penyesuaian Stok</h1>
                <span class="det-subtitle">
                    Rincian transaksi penyesuaian &middot;
                    <strong><?= esc($penyesuaian['nomor_penyesuaian']) ?></strong>
                </span>
            </div>
        </div>
        <a href="<?= site_url('transaksi/penyesuaian') ?>" class="det-btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- KPI -->
    <?php
        $totalItem   = count($details);
        $isPositif   = ($penyesuaian['jenis_penyesuaian'] === 'Koreksi Positif');
        $totalJumlah = array_sum(array_column($details, 'jumlah'));
        $totalBatch  = count(array_unique(array_column($details, 'nomor_batch')));
    ?>
    <div class="det-kpi-grid">
        <div class="det-kpi blue">
            <div class="det-kpi-icon"><i class="fa-solid fa-hashtag"></i></div>
            <div>
                <span class="det-kpi-label">No. Penyesuaian</span>
                <div class="det-kpi-value" style="font-size:14px"><?= esc($penyesuaian['nomor_penyesuaian']) ?></div>
            </div>
        </div>
        <div class="det-kpi <?= $isPositif ? 'green' : 'amber' ?>">
            <div class="det-kpi-icon">
                <i class="fa-solid <?= $isPositif ? 'fa-circle-plus' : 'fa-circle-minus' ?>"></i>
            </div>
            <div>
                <span class="det-kpi-label">Jenis</span>
                <div class="det-kpi-value" style="font-size:13px"><?= esc($penyesuaian['jenis_penyesuaian']) ?></div>
            </div>
        </div>
        <div class="det-kpi blue">
            <div class="det-kpi-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div>
                <span class="det-kpi-label">Total Item</span>
                <div class="det-kpi-value"><?= $totalItem ?></div>
            </div>
        </div>
        <div class="det-kpi purple">
            <div class="det-kpi-icon"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <span class="det-kpi-label">Batch Terlibat</span>
                <div class="det-kpi-value"><?= $totalBatch ?></div>
            </div>
        </div>
    </div>

    <!-- INFO CARD -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-circle-info"></i> Informasi Penyesuaian
            </span>
            <span class="det-card-badge <?= $isPositif ? 'green' : 'red' ?>">
                <i class="fa-solid <?= $isPositif ? 'fa-circle-plus' : 'fa-circle-minus' ?> me-1"></i>
                <?= esc($penyesuaian['jenis_penyesuaian']) ?>
            </span>
        </div>
        <div class="det-meta-grid">
            <div class="det-meta-item">
                <div class="det-meta-label">Nomor Penyesuaian</div>
                <div class="det-meta-value mono"><?= esc($penyesuaian['nomor_penyesuaian']) ?></div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Tanggal</div>
                <div class="det-meta-value">
                    <i class="fa-regular fa-calendar" style="color:#94A3B8;margin-right:5px"></i>
                    <?= date('d M Y', strtotime($penyesuaian['tanggal'])) ?>
                </div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Petugas</div>
                <div class="det-meta-value">
                    <i class="fa-regular fa-user" style="color:#94A3B8;margin-right:5px"></i>
                    <?= esc($penyesuaian['username'] ?? '-') ?>
                </div>
            </div>
            <div class="det-meta-item">
                <div class="det-meta-label">Jenis Penyesuaian</div>
                <div class="det-meta-value">
                    <span class="det-jenis-badge <?= $isPositif ? 'det-jenis-positif' : 'det-jenis-negatif' ?>">
                        <i class="fa-solid <?= $isPositif ? 'fa-circle-plus' : 'fa-circle-minus' ?>" style="margin-right:4px"></i>
                        <?= esc($penyesuaian['jenis_penyesuaian']) ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="det-ket-box">
            <div class="det-ket-label"><i class="fa-solid fa-quote-left" style="margin-right:4px"></i>Keterangan / Alasan</div>
            <div class="det-ket-text"><?= nl2br(esc($penyesuaian['keterangan'])) ?></div>
        </div>
    </div>

    <!-- TABEL DETAIL -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-list-check"></i> Daftar Item Penyesuaian
            </span>
            <span class="det-card-badge blue"><?= $totalItem ?> Item</span>
        </div>
        <div>
            <table id="tabelDetail">
                <thead>
                    <tr>
                        <th width="45" class="text-center">No</th>
                        <th>Barang</th>
                        <th class="text-center">Nomor Batch</th>
                        <th class="text-center">Tgl Expired</th>
                        <th class="text-end">Stok Sebelum</th>
                        <th class="text-end">Jumlah Penyesuaian</th>
                        <th class="text-end">Stok Sesudah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($details as $row) :
                        $isPlus    = ($penyesuaian['jenis_penyesuaian'] === 'Koreksi Positif');
                        $isRepack  = (int)($row['bisa_dipecah'] ?? 0) === 1;
                        $decimals  = $isRepack ? 2 : 0;
                        $sign      = $isPlus ? '+' : '-';
                    ?>
                    <tr>
                        <td class="text-center"><span class="det-no"><?= $no++ ?></span></td>
                        <td>
                            <span class="det-nama"><?= esc($row['nama_barang']) ?></span>
                            <?php if ($isRepack) : ?>
                            <div style="font-size:11px;color:#94A3B8;margin-top:2px">
                                <i class="fa-solid fa-recycle" style="color:#2563EB"></i> Repack
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="det-batch-no"><?= esc($row['nomor_batch']) ?></span>
                        </td>
                        <td class="text-center" style="font-size:12px;font-weight:500;color:#DC2626">
                            <?= !empty($row['tanggal_kedaluwarsa']) ? date('d M Y', strtotime($row['tanggal_kedaluwarsa'])) : '-' ?>
                        </td>
                        <td class="text-end">
                            <span class="det-stok-val">
                                <?= number_format($row['stok_sebelum'], $decimals, ',', '.') ?>
                            </span>
                            <span class="det-stok-unit"><?= esc($row['satuan']) ?></span>
                        </td>
                        <td class="text-end">
                            <span class="<?= $isPlus ? 'det-plus' : 'det-minus' ?>">
                                <?= $sign ?><?= number_format($row['jumlah'], $decimals, ',', '.') ?>
                            </span>
                            <span class="det-stok-unit"><?= esc($row['satuan']) ?></span>
                        </td>
                        <td class="text-end">
                            <span class="det-stok-val">
                                <?= number_format($row['stok_sesudah'], $decimals, ',', '.') ?>
                            </span>
                            <span class="det-stok-unit"><?= esc($row['satuan']) ?></span>
                        </td>
                        <td style="font-size:12px;color:#94A3B8;font-style:italic">
                            <?= esc($row['keterangan'] ?? '-') ?>
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

    $('#tabelDetail').DataTable({
        language: dtLang,
        order: [[3, "asc"]],
        paging: false,
        info: false,
        searching: false,
        scrollX: false
    });
});
</script>
<?= $this->endSection() ?>