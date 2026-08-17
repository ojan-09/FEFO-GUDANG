<?php helper('format'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Permintaan Barang Keluar') ?></title>
    <style>
        @page { margin: 10mm 12mm; size: A4; }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .form-block {
            border: 2px solid #000;
        }

        /* ===== HEADER ===== */
        .header-wrap {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .logo-col {
            display: table-cell;
            width: 115px;
            border-right: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 6px 4px;
        }
        .logo-img { max-width: 85px; max-height: 42px; }
        .foi-label {
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            display: block;
            margin-top: 3px;
        }
        .info-col {
            display: table-cell;
            vertical-align: top;
            width: 100%;
        }
        .info-title {
            font-weight: bold;
            font-size: 10.5pt;
            text-align: center;
            padding: 4px 6px;
            border-bottom: 1px solid #000;
        }
        .info-rows { display: table; width: 100%; border-collapse: collapse; }
        .info-row { display: table-row; }
        .info-row .cell {
            display: table-cell;
            width: 50%;
            padding: 3px 8px;
            font-size: 8.5pt;
            border-bottom: 1px solid #000;
            vertical-align: middle;
        }
        .info-row .cell:first-child { border-right: 1px solid #000; }
        .info-row:last-child .cell { border-bottom: none; }

        /* ===== ITEMS TABLE ===== */
        table.tbl-items {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #000;
        }
        table.tbl-items th,
        table.tbl-items td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 8.5pt;
        }
        table.tbl-items th { font-weight: bold; text-align: center; }
        .item-row td { height: 17px; line-height: 17px; }

        /* ===== SIGNATURE ===== */
        table.tbl-sig {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #000;
        }
        table.tbl-sig td { border: 1px solid #000; font-size: 8.5pt; }
        .sig-header td { padding: 2px 6px; text-align: center; }
        .sig-space td  { height: 50px; }
        .sig-tgl td    { padding: 2px 6px; text-align: left; }

        .note-text {
            font-size: 7.5pt;
            font-style: italic;
            padding: 3px 6px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>

<?php
$logoPath = FCPATH . 'assets/img/LogoFOI.webp';
$logoSrc  = '';
if (file_exists($logoPath) && is_readable($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoSrc  = 'data:image/webp;base64,' . $logoData;
}

$detailsList = !empty($details) ? $details : ($items ?? []);
$maxRows     = 12;
?>

<div class="form-block">

    <!-- HEADER -->
    <div class="header-wrap">
        <div class="logo-col">
            <?php if (!empty($logoSrc)): ?>
                <img src="<?= $logoSrc ?>" class="logo-img" alt="FOI Logo">
            <?php else: ?>
                <div style="width:80px;height:40px;display:inline-block;"></div>
            <?php endif; ?>
            <span class="foi-label">FOI NETWORK</span>
        </div>
        <div class="info-col">
            <div class="info-title">PERMINTAAN BARANG KELUAR</div>
            <div class="info-rows">
                <div class="info-row">
                    <div class="cell">No : <strong><?= esc($document_number ?? $barangKeluar['nomor_transaksi'] ?? '') ?></strong></div>
                    <div class="cell">Nama : <?= esc($barangKeluar['petugas'] ?? '') ?></div>
                </div>
                <div class="info-row">
                    <div class="cell">Week : </div>
                    <div class="cell">Divisi : <?= esc($barangKeluar['divisi_petugas'] ?? $barangKeluar['divisi'] ?? '') ?></div>
                </div>
                <div class="info-row">
                    <div class="cell">Project : <?= esc($barangKeluar['tujuan_penyaluran'] ?? '') ?></div>
                    <div class="cell">Tanggal : <?= !empty($barangKeluar['tanggal_keluar']) ? date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'])) : '' ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ITEMS -->
    <table class="tbl-items">
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th>Deskripsi</th>
                <th style="width:175px;">Jenis Barang</th>
                <th style="width:75px;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($detailsList as $d):
                $satuanTampil = ucfirst($d['satuan'] ?? 'Pcs');
            ?>
            <tr class="item-row">
                <td style="text-align:center;"><?= $no++ ?></td>
                <td><?= esc($d['nama_barang']) ?></td>
                <td style="text-align:center;"><?= esc($d['nomor_batch'] ?? 'Donasi') ?></td>
                <td style="text-align:center;"><?= format_jumlah($d['jumlah_keluar']) ?> <?= $satuanTampil ?></td>
            </tr>
            <?php endforeach; ?>

            <?php for ($i = $no; $i <= $maxRows; $i++): ?>
            <tr class="item-row">
                <td style="text-align:center;">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- SIGNATURE -->
    <table class="tbl-sig">
        <tr class="sig-header">
            <td style="width:33.33%;">Menyusun,</td>
            <td style="width:33.33%;">Mengetahui,</td>
            <td style="width:33.33%;">Menyetujui,</td>
        </tr>
        <tr class="sig-space">
            <td></td><td></td><td></td>
        </tr>
        <tr class="sig-tgl">
            <td>Tgl.</td>
            <td>Tgl.</td>
            <td>Tgl.</td>
        </tr>
    </table>

    <div class="note-text">Note : cost location wajib diisi berdasarkan nama program dan klien (jika ada)</div>

</div>

</body>
</html>