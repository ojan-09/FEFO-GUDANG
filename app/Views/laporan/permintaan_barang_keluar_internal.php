<?php helper('format'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Permintaan Barang Keluar') ?></title>
    <style>
        @page {
            margin: 8mm 12mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .form-block {
            border: 1px solid #000;
            padding: 0;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        /* ===== HEADER TABLE ===== */
        table.tbl-head {
            width: 100%;
            border-collapse: collapse;
        }
        table.tbl-head td {
            border: 1px solid #000;
            padding: 3px 6px;
            vertical-align: middle;
            font-size: 8.5pt;
        }
        .logo-cell {
            width: 110px;
            text-align: center;
            padding: 4px !important;
            vertical-align: middle;
        }
        .logo-img {
            max-width: 85px;
            max-height: 42px;
        }
        .foi-network-label {
            font-size: 7pt;
            font-weight: bold;
            display: block;
            margin-top: 2px;
            letter-spacing: 0.5px;
        }
        .title-cell {
            font-weight: bold;
            font-size: 10.5pt;
            text-align: center;
            background: #ffffff;
            border-bottom: 1px solid #000;
        }
        .half-left {
            width: 50%;
            border-right: 1px solid #000;
        }
        .half-right {
            width: 50%;
        }

        /* ===== ITEMS TABLE ===== */
        table.tbl-items {
            width: 100%;
            border-collapse: collapse;
        }
        table.tbl-items th, table.tbl-items td {
            border: 1px solid #000;
            padding: 3px 6px;
            font-size: 8.5pt;
        }
        table.tbl-items th {
            font-weight: bold;
            text-align: center;
            background: #ffffff;
        }
        table.tbl-items td.no-col {
            text-align: center;
            width: 28px;
        }
        table.tbl-items td.jenis-col {
            text-align: center;
            width: 170px;
        }
        table.tbl-items td.jumlah-col {
            text-align: center;
            width: 80px;
        }
        .item-row td {
            height: 16px;
        }

        /* ===== SIGNATURE TABLE ===== */
        table.tbl-sig {
            width: 100%;
            border-collapse: collapse;
        }
        table.tbl-sig th, table.tbl-sig td {
            border: 1px solid #000;
            padding: 3px 6px;
            text-align: center;
            font-size: 8.5pt;
        }
        table.tbl-sig th {
            font-weight: normal;
        }
        .sig-space {
            height: 45px;
        }
        .tgl-row td {
            text-align: left;
            padding: 2px 6px;
        }

        .note-text {
            font-size: 7.5pt;
            font-style: italic;
            padding: 2px 4px 3px 4px;
        }
    </style>
</head>
<body>

<?php 
$logoPath = FCPATH . 'assets/img/LogoFOI.webp';
$logoSrc = '';
if (file_exists($logoPath) && is_readable($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoSrc = 'data:image/webp;base64,' . $logoData;
}

$detailsList = !empty($details) ? $details : ($items ?? []);
$maxRows = 12; // sesuai template asli FOI

for ($k = 0; $k < 2; $k++): ?>

<div class="form-block">

    <!-- ===== HEADER ===== -->
    <table class="tbl-head">
        <tr>
            <!-- Logo: rowspan 4 -->
            <td class="logo-cell" rowspan="4">
                <?php if (!empty($logoSrc)): ?>
                    <img src="<?= $logoSrc ?>" class="logo-img" alt="FOI Logo">
                <?php endif; ?>
                <span class="foi-network-label">FOI NETWORK</span>
            </td>
            <!-- Judul span 2 kolom -->
            <td class="title-cell" colspan="2">PERMINTAAN BARANG KELUAR</td>
        </tr>
        <tr>
            <td class="half-left">No : <strong><?= esc($document_number ?? $barangKeluar['nomor_transaksi'] ?? 'Sem 2-&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;/ 2026') ?></strong></td>
            <td class="half-right">Nama : <?= esc($barangKeluar['petugas'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="half-left">Week : </td>
            <td class="half-right">Divisi : </td>
        </tr>
        <tr>
            <td class="half-left">Project : <?= esc($barangKeluar['tujuan_penyaluran'] ?? '') ?></td>
            <td class="half-right">Tanggal : <?= !empty($barangKeluar['tanggal_keluar']) ? date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'])) : '' ?></td>
        </tr>
    </table>

    <!-- ===== ITEMS TABLE ===== -->
    <table class="tbl-items">
        <thead>
            <tr>
                <th style="width: 28px;">No</th>
                <th style="text-align: left;">Deskripsi</th>
                <th style="width: 170px;">Jenis Barang</th>
                <th style="width: 80px;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($detailsList as $d): 
                $satuanTampil = ucfirst($d['satuan'] ?? 'Pcs');
            ?>
            <tr class="item-row">
                <td class="no-col"><?= $no++ ?></td>
                <td><?= esc($d['nama_barang']) ?></td>
                <td class="jenis-col"><?= esc($d['nomor_batch'] ?? 'Donasi') ?></td>
                <td class="jumlah-col"><?= format_jumlah($d['jumlah_keluar']) ?> <?= $satuanTampil ?></td>
            </tr>
            <?php endforeach; ?>

            <?php for ($i = $no; $i <= $maxRows; $i++): ?>
            <tr class="item-row">
                <td class="no-col">&nbsp;</td>
                <td>&nbsp;</td>
                <td class="jenis-col">&nbsp;</td>
                <td class="jumlah-col">&nbsp;</td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- ===== SIGNATURE TABLE ===== -->
    <table class="tbl-sig">
        <thead>
            <tr>
                <th style="width: 33.33%;">Menyusun,</th>
                <th style="width: 33.33%;">Mengetahui,</th>
                <th style="width: 33.33%;">Menyetujui,</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="sig-space"></td>
                <td class="sig-space"></td>
                <td class="sig-space"></td>
            </tr>
            <tr class="tgl-row">
                <td>Tgl.</td>
                <td>Tgl.</td>
                <td>Tgl.</td>
            </tr>
        </tbody>
    </table>

    <div class="note-text">Note : cost location wajib diisi berdasarkan nama program dan klien (jika ada)</div>

</div>

<?php endfor; ?>

</body>
</html>