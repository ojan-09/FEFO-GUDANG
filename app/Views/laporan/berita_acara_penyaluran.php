<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        @page {
            margin: 15mm 20mm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .header img {
            max-width: 100px;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .title-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .title-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .title-table .col-dokumen {
            width: 50%;
        }
        .title-table .col-judul {
            width: 50%;
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
        }
        .info-section {
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .info-table {
            width: 100%;
            border: none;
            font-size: 10pt;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 130px;
        }
        
        .table-container {
            page-break-inside: avoid;
            margin-bottom: 15px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            text-align: center;
            font-size: 10pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            line-height: 1.2;
        }
        .data-table th {
            font-weight: bold;
        }
        .table-title {
            margin-bottom: 3px;
            font-size: 10pt;
        }
        .ketentuan {
            margin-top: 15px;
            margin-bottom: 15px;
            font-size: 10pt;
            page-break-inside: avoid;
        }
        .ketentuan p {
            margin: 0 0 3px 0;
        }
        .ketentuan ol {
            margin: 0;
            padding-left: 15px;
        }
        .ketentuan li {
            margin-bottom: 2px;
        }
        .signature-section {
            margin-top: 15px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            text-align: center;
            font-size: 10pt;
        }
        .signature-table td {
            width: 50%;
            padding-bottom: 60px;
        }
        .footer-note {
            margin-top: 20px;
            font-size: 10pt;
            page-break-inside: avoid;
        }
        .footer-note p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <?php helper('format'); ?>

    <!-- Logo / Kop Surat -->
    <?php
        $logoPath = FCPATH . 'assets/img/logo_foi.png';
        $logoSrc = '';
        if (file_exists($logoPath) && is_readable($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;
        }
    ?>
    <div class="header">
        <?php if (!empty($logoSrc)): ?>
            <img src="<?= $logoSrc ?>" alt="FOI Logo">
        <?php else: ?>
            <div style="font-weight: bold; font-size: 16pt; letter-spacing: 1px; color: #333; margin-bottom: 5px;">FOOD CYCLE INDONESIA</div>
        <?php endif; ?>
        <h1 style="margin-top: 10px;">BERITA ACARA SERAH TERIMA DONASI</h1>
    </div>

    <!-- Tabel Nomor Dokumen & Judul -->
    <table class="title-table">
        <tr>
            <td class="col-dokumen" style="border-bottom: 1px solid #000; padding: 10px;">
                No Dokumen: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?= esc($barangKeluar['nomor_transaksi']) ?></strong>
            </td>
            <td class="col-judul" rowspan="2" style="padding: 10px;">
                BERITA ACARA<br>
                PENDISTRIBUSIAN DONASI<br>
                KEPADA MITRA
            </td>
        </tr>
        <tr>
            <td class="col-dokumen" style="padding: 10px;">
                Tanggal Donasi : <strong><?= date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'])) ?></strong>
            </td>
        </tr>
    </table>

    <!-- Info Mitra -->
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td>Telah diterima dari</td>
                <td>: Yayasan Lumbung Pangan Indonesia</td>
            </tr>
            <tr>
                <td>Ditujukan kepada</td>
                <td>: <?= esc($barangKeluar['nama_wilayah'] ?? '-') ?> (<?= esc($barangKeluar['tujuan_penyaluran']) ?>)</td>
            </tr>
        </table>
    </div>

    <?php
        // Hitung total berat dalam array details
        $totalBeratGram = 0;
        $totalCtn = 0;
        foreach ($details as $d) {
            // Konversi ke gram jika belum
            $beratItem = (float)$d['berat_per_satuan'];
            $satuanB = strtolower($d['satuan_berat']);
            if ($satuanB === 'kg' || $satuanB === 'kilogram') {
                $beratItem *= 1000;
            }
            $totalBeratGram += ($beratItem * $d['jumlah_keluar']);
            $totalCtn += (int)($d['jumlah_ctn'] ?? 0);
        }
        $totalBeratKg = $totalBeratGram / 1000;
    ?>

    <!-- Tabel Jumlah keseluruhan donasi -->
    <div class="table-container">
        <div class="table-title">Jumlah keseluruhan donasi:</div>
        <table class="data-table" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Penerimaan</th>
                    <th>Total CTN</th>
                    <th>Total Berat</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><?= date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'])) ?></td>
                    <td><?= $totalCtn ?></td>
                    <td><?= format_berat($totalBeratKg, 'Kg') ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tabel Rincian donasi -->
    <div class="table-title">Rincian donasi sebagai berikut:</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>CTN</th>
                <th>Total Berat</th>
                <th>Kondisi<br>Donasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($details as $d) : ?>
                <?php
                    // Convert berat_per_satuan to Kg for subtotal
                    $beratS = (float)$d['berat_per_satuan'];
                    $satuanB = strtolower($d['satuan_berat']);
                    if ($satuanB === 'gram') {
                        $beratSKg = $beratS / 1000;
                    } else {
                        $beratSKg = $beratS;
                    }
                    $subTotalKg = $beratSKg * $d['jumlah_keluar'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= esc($d['nama_barang']) ?></td>
                    <td><?= esc($d['jumlah_keluar']) ?></td>
                    <td><?= esc($d['satuan']) ?></td>
                    <td><?= !empty($d['jumlah_ctn']) ? esc($d['jumlah_ctn']) : '-' ?></td>
                    <td><?= format_berat($subTotalKg, 'Kg') ?></td>
                    <td>Baik</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;">Total</td>
                <td colspan="2" style="text-align: center; font-weight: bold;">-</td>
                <td style="font-weight: bold;"><?= $totalCtn ?></td>
                <td style="font-weight: bold;"><?= format_berat($totalBeratKg, 'Kg') ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- Ketentuan -->
    <div class="ketentuan">
        <p>Ketentuan:</p>
        <ol>
            <li>Donasi yang telah diserahkan <strong>TIDAK BOLEH DIPERJUALBELIKAN.</strong></li>
            <li>Donasi digunakan sesuai peruntukannya untuk program bagi anak-anak, lansia, dan kaum papa.</li>
            <li>Apabila terjadi kerusakan barang, Mitra bertanggungjawab untuk membuat Berita Acara Pemeriksaan (BAP) serta melaporkannya kepada pengurus YLPI.</li>
            <li>Mitra bertanggungjawab atas penyaluran donasi dan pelaporannya melalui tautan Survei123 setelah didistribusikan.</li>
        </ol>
    </div>

    <p>Dengan demikian, dokumen serah terima barang ini telah ditandatangani dan disepakati oleh kedua belah pihak pada tanggal <?= date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'])) ?></p>

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>Yang menerima</td>
                <td>Yang menyerahkan</td>
            </tr>
            <tr>
                <td style="padding-top: 80px;">(.................................................)</td>
                <td style="padding-top: 80px;">(.................................................)</td>
            </tr>
        </table>
    </div>

    <!-- Footer Note -->
    <div class="footer-note">
        <p>Foodbank of Indonesia</p>
        <p>Jl. Bendi Utama No.9, RT.7/RW.10, Kby. Lama Utara, Kec. Kebayoran Lama, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12240</p>
    </div>

</body>
</html>


