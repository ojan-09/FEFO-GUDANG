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
.det-kpi.blue   .det-kpi-icon{background:#EFF6FF;color:#2563EB}
.det-kpi.green  .det-kpi-icon{background:#F0FDF4;color:#16A34A}
.det-kpi.amber  .det-kpi-icon{background:#FFFBEB;color:#D97706}
.det-kpi.red    .det-kpi-icon{background:#FEF2F2;color:#DC2626}
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
.det-card-badge.blue  {background:#EFF6FF;color:#2563EB;border-color:#BFDBFE}
.det-card-badge.green {background:#F0FDF4;color:#16A34A;border-color:#BBF7D0}
.det-card-badge.amber {background:#FFFBEB;color:#D97706;border-color:#FDE68A}
.det-card-badge.red   {background:#FEF2F2;color:#DC2626;border-color:#FECACA}
.det-card-badge.slate {background:#F1F5F9;color:#475569;border-color:#CBD5E1}

/* ── META GRID ── */
.det-two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.det-meta-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:0}
.det-meta-item{padding:14px 18px;border-right:0.5px solid #F1F5F9;border-bottom:0.5px solid #F1F5F9}
.det-meta-item:nth-child(even){border-right:none}
.det-meta-item:last-child,.det-meta-item:nth-last-child(2):nth-child(odd){border-bottom:none}
.det-meta-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8;margin-bottom:5px}
.det-meta-value{font-size:13px;font-weight:500;color:#0F172A}

/* ── STOK BOX ── */
.det-stok-row{display:flex;gap:10px;padding:16px 18px}
.det-stok-box{
    flex:1;padding:14px 16px;border-radius:10px;border:0.5px solid;
    display:flex;flex-direction:column;gap:4px;
}
.det-stok-box.blue {background:#EFF6FF;border-color:#BFDBFE}
.det-stok-box.green{background:#F0FDF4;border-color:#BBF7D0}
.det-stok-box-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#94A3B8}
.det-stok-box-val{font-size:24px;font-weight:600;line-height:1}
.det-stok-box.blue  .det-stok-box-val{color:#2563EB}
.det-stok-box.green .det-stok-box-val{color:#16A34A}
.det-stok-box-unit{font-size:11px;color:#94A3B8;margin-top:1px}

/* ── AKSI BOX ── */
.det-aksi-box{
    margin:0 18px 16px;padding:12px 16px;
    background:#F8FAFC;border:0.5px solid #E2E8F0;border-radius:10px;
    display:flex;align-items:center;gap:8px;flex-wrap:wrap;
}
.det-aksi-label{font-size:11px;font-weight:500;color:#94A3B8;text-transform:uppercase;letter-spacing:.05em;margin-right:4px}
.det-aksi-btn{
    display:inline-flex;align-items:center;gap:6px;
    height:32px;padding:0 14px;font-size:12px;font-weight:500;
    border-radius:999px;border:0.5px solid;text-decoration:none;
    transition:background .12s,color .12s;cursor:pointer;
}
.det-aksi-btn.primary{background:#2563EB;color:#fff;border-color:#2563EB}
.det-aksi-btn.primary:hover{background:#1D4ED8;color:#fff}
.det-aksi-btn.outline{background:#fff;color:#475569;border-color:#CBD5E1}
.det-aksi-btn.outline:hover{background:#F1F5F9;color:#1E293B}
.det-aksi-btn.danger-outline{background:#fff;color:#DC2626;border-color:#FECACA}
.det-aksi-btn:disabled,.det-aksi-btn[disabled]{opacity:.5;cursor:not-allowed;pointer-events:none}

/* ── STATUS BADGE ── */
.det-status{
    display:inline-flex;align-items:center;height:22px;padding:0 9px;
    font-size:11px;font-weight:500;border-radius:999px;
}
.det-status-aman   {background:#F0FDF4;color:#15803D}
.det-status-hampir {background:#FFFBEB;color:#B45309}
.det-status-expired{background:#FEF2F2;color:#DC2626}
.det-status-default{background:#F1F5F9;color:#64748B}

/* ── TABLE ── */
#tabelRiwayat{width:100%;border-collapse:collapse;margin:0}
#tabelRiwayat thead tr{background:#F8FAFC}
#tabelRiwayat thead th{
    padding:10px 14px;font-size:11px;font-weight:500;
    letter-spacing:.04em;text-transform:uppercase;color:#94A3B8;
    white-space:nowrap;border:none;border-bottom:0.5px solid #E2E8F0;
    vertical-align:middle;
}
#tabelRiwayat tbody tr{border-bottom:0.5px solid #F1F5F9;transition:background .1s}
#tabelRiwayat tbody tr:last-child{border-bottom:none}
#tabelRiwayat tbody tr:hover td{background:#F8FAFC}
#tabelRiwayat tbody td{
    padding:10px 14px;font-size:13px;color:#334155;
    vertical-align:middle;border:none;
}
.det-no{color:#CBD5E1;font-size:12px}
.det-keluar-val{font-size:13px;font-weight:600;color:#DC2626}
.det-stok-unit{font-size:11px;font-weight:400;color:#94A3B8;margin-left:2px}

/* ── EMPTY STATE ── */
.det-empty{
    padding:40px 20px;text-align:center;
    color:#94A3B8;font-size:13px;
}
.det-empty i{font-size:28px;margin-bottom:10px;display:block;color:#CBD5E1}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
    .det-kpi-grid{grid-template-columns:repeat(2,1fr)}
    .det-two-col{grid-template-columns:1fr}
}
@media(max-width:640px){
    .det-kpi-grid{grid-template-columns:repeat(2,1fr)}
    .det-topbar{flex-direction:column;align-items:flex-start}
    .det-wrap{padding:14px 16px}
    .det-stok-row{flex-direction:column}
}
</style>

<div class="det-wrap">

    <!-- TOPBAR -->
    <div class="det-topbar">
        <div class="det-topbar-left">
            <div class="det-icon-box">
                <i class="fa-solid fa-box"></i>
            </div>
            <div>
                <h1 class="det-title"><?= esc($title) ?></h1>
                <span class="det-subtitle">
                    Detail batch &amp; riwayat pengeluaran &middot;
                    <strong><?= esc($batch['nomor_batch']) ?></strong>
                </span>
            </div>
        </div>
        <a href="<?= site_url('transaksi/monitoring-expired') ?>" class="det-btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- KPI -->
    <?php
        $diff = (int)(new \DateTime(date('Y-m-d')))->diff(new \DateTime($batch['tanggal_kedaluwarsa']))->format('%R%a');
        $isExpired = $diff < 0;
        $status = $batch['status'] ?? '-';
        $statusClass = match(true) {
            str_contains(strtolower($status), 'aman')    => 'det-status-aman',
            str_contains(strtolower($status), 'hampir')  => 'det-status-hampir',
            str_contains(strtolower($status), 'expired') => 'det-status-expired',
            default => 'det-status-default',
        };
        $totalKeluar = array_sum(array_column($riwayatKeluar ?? [], 'jumlah_keluar'));
    ?>
    <div class="det-kpi-grid">
        <div class="det-kpi blue">
            <div class="det-kpi-icon"><i class="fa-solid fa-warehouse"></i></div>
            <div>
                <span class="det-kpi-label">Stok Awal</span>
                <div class="det-kpi-value">
                    <?= number_format((float)$batch['jumlah_awal'], 0, ',', '.') ?>
                    <span class="det-kpi-unit"><?= esc($batch['satuan']) ?></span>
                </div>
            </div>
        </div>
        <div class="det-kpi green">
            <div class="det-kpi-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div>
                <span class="det-kpi-label">Sisa Stok</span>
                <div class="det-kpi-value">
                    <?= number_format((float)$batch['stok_saat_ini'], 0, ',', '.') ?>
                    <span class="det-kpi-unit"><?= esc($batch['satuan']) ?></span>
                </div>
            </div>
        </div>
        <div class="det-kpi <?= $isExpired ? 'red' : 'amber' ?>">
            <div class="det-kpi-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
            <div>
                <span class="det-kpi-label">Sisa Hari</span>
                <div class="det-kpi-value">
                    <?= $isExpired ? 'Expired' : $diff ?>
                    <?php if (!$isExpired) : ?>
                        <span class="det-kpi-unit">hari</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="det-kpi red">
            <div class="det-kpi-icon"><i class="fa-solid fa-truck-ramp-box"></i></div>
            <div>
                <span class="det-kpi-label">Total Disalurkan</span>
                <div class="det-kpi-value">
                    <?= number_format($totalKeluar, 0, ',', '.') ?>
                    <span class="det-kpi-unit"><?= esc($batch['satuan']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- TWO COLUMN -->
    <div class="det-two-col">

        <!-- INFO BATCH -->
        <div class="det-card">
            <div class="det-card-header">
                <span class="det-card-title">
                    <i class="fa-solid fa-circle-info"></i> Informasi Batch
                </span>
                <span class="det-status <?= $statusClass ?>"><?= esc($status) ?></span>
            </div>
            <div class="det-meta-grid">
                <div class="det-meta-item">
                    <div class="det-meta-label">Nomor Batch</div>
                    <div class="det-meta-value" style="color:#2563EB;font-size:14px;font-weight:600"><?= esc($batch['nomor_batch']) ?></div>
                </div>
                <div class="det-meta-item">
                    <div class="det-meta-label">Nama Barang</div>
                    <div class="det-meta-value"><?= esc($batch['nama_barang']) ?></div>
                </div>
                <div class="det-meta-item">
                    <div class="det-meta-label">Tanggal Masuk</div>
                    <div class="det-meta-value">
                        <i class="fa-regular fa-calendar" style="color:#94A3B8;margin-right:4px"></i>
                        <?= date('d M Y', strtotime($batch['tanggal_masuk'])) ?>
                    </div>
                </div>
                <div class="det-meta-item">
                    <div class="det-meta-label">Tanggal Kedaluwarsa</div>
                    <div class="det-meta-value" style="color:#DC2626;font-weight:600">
                        <i class="fa-solid fa-calendar-xmark" style="margin-right:4px"></i>
                        <?= date('d M Y', strtotime($batch['tanggal_kedaluwarsa'])) ?>
                    </div>
                </div>
                <div class="det-meta-item" style="border-bottom:none">
                    <div class="det-meta-label">Donatur</div>
                    <div class="det-meta-value"><?= esc($batch['nama_donatur'] ?? '-') ?></div>
                </div>
                <div class="det-meta-item" style="border-bottom:none">
                    <div class="det-meta-label">Satuan</div>
                    <div class="det-meta-value"><?= esc($batch['satuan']) ?></div>
                </div>
            </div>
        </div>

        <!-- INFO STOK + AKSI -->
        <div class="det-card">
            <div class="det-card-header">
                <span class="det-card-title">
                    <i class="fa-solid fa-chart-bar"></i> Informasi Stok
                </span>
                <?php if (!$isExpired && (float)$batch['stok_saat_ini'] > 0) : ?>
                    <span class="det-card-badge green">Dapat Disalurkan</span>
                <?php elseif ($isExpired) : ?>
                    <span class="det-card-badge red">Expired</span>
                <?php else : ?>
                    <span class="det-card-badge slate">Stok Habis</span>
                <?php endif; ?>
            </div>
            <div class="det-stok-row">
                <div class="det-stok-box blue">
                    <span class="det-stok-box-label">Stok Awal</span>
                    <span class="det-stok-box-val"><?= number_format((float)$batch['jumlah_awal'], 0, ',', '.') ?></span>
                    <span class="det-stok-box-unit"><?= esc($batch['satuan']) ?></span>
                </div>
                <div class="det-stok-box green">
                    <span class="det-stok-box-label">Sisa Stok</span>
                    <span class="det-stok-box-val"><?= number_format((float)$batch['stok_saat_ini'], 0, ',', '.') ?></span>
                    <span class="det-stok-box-unit"><?= esc($batch['satuan']) ?></span>
                </div>
            </div>

            <?php if (in_groups('Administrator') && (float)$batch['stok_saat_ini'] > 0) : ?>
            <div class="det-aksi-box">
                <span class="det-aksi-label">Aksi Cepat</span>
                <?php if ($isExpired) : ?>
                    <button class="det-aksi-btn danger-outline" disabled>
                        <i class="fa-solid fa-ban"></i> Expired – Tidak Dapat Disalurkan
                    </button>
                <?php else : ?>
                    <a href="<?= site_url('transaksi/barang-keluar/create?id_batch='.$batch['id']) ?>" class="det-aksi-btn primary">
                        <i class="fa-solid fa-share-from-square"></i> Gunakan Batch Ini
                    </a>
                <?php endif; ?>
                <a href="<?= site_url('transaksi/barang-masuk/detail/'.$batch['id_barang_masuk']) ?>" class="det-aksi-btn outline">
                    <i class="fa-solid fa-truck-ramp-box"></i> Lihat Barang Masuk
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- TABEL RIWAYAT -->
    <div class="det-card">
        <div class="det-card-header">
            <span class="det-card-title">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pengeluaran Batch
            </span>
            <span class="det-card-badge red"><?= count($riwayatKeluar ?? []) ?> Transaksi</span>
        </div>
        <div>
            <?php if (empty($riwayatKeluar)) : ?>
                <div class="det-empty">
                    <i class="fa-solid fa-inbox"></i>
                    Belum ada riwayat pengeluaran untuk batch ini.
                </div>
            <?php else : ?>
            <table id="tabelRiwayat">
                <thead>
                    <tr>
                        <th width="45" class="text-center">No</th>
                        <th class="text-center">Tanggal Keluar</th>
                        <th>Tujuan</th>
                        <th class="text-end">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayatKeluar as $i => $row) : ?>
                    <tr>
                        <td class="text-center"><span class="det-no"><?= $i + 1 ?></span></td>
                        <td class="text-center" style="font-size:12px;color:#64748B">
                            <?= date('d M Y', strtotime($row['tanggal_keluar'])) ?>
                        </td>
                        <td style="color:#475569"><?= esc($row['tujuan']) ?></td>
                        <td class="text-end">
                            <span class="det-keluar-val">
                                -<?= number_format((float)$row['jumlah_keluar'], 0, ',', '.') ?>
                            </span>
                            <span class="det-stok-unit"><?= esc($batch['satuan']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    <?php if (!empty($riwayatKeluar)) : ?>
    $('#tabelRiwayat').DataTable({
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
        order: [[1, "desc"]],
        paging: false,
        info: false,
        searching: false,
        scrollX: false
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>