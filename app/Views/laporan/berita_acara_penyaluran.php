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
            max-width: 60px;
            /* dari 100px */
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

        .data-table th,
        .data-table td {
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
            padding-bottom: 0;
            /* hapus padding bottom */
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
    $logoPath = FCPATH . 'assets/img/LogoFOI.webp';
    $logoSrc = '';
    if (file_exists($logoPath) && is_readable($logoPath)) {
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoSrc = 'data:image/webp;base64,' . $logoData;
    }
    ?>
    <div class="header">
        <?php if (!empty($logoSrc)): ?>
            <img src="<?= $logoSrc ?>" alt="FOI Logo">
        <?php else: ?>
        <?php endif; ?>
        <h1 style="margin-top: 10px;"><u>BERITA ACARA SERAH TERIMA DONASI</u></h1>
    </div>

    <!-- Tabel Nomor Dokumen & Judul -->
    <table class="title-table">
        <tr>
            <td class="col-dokumen" style="border-bottom: 1px solid #000; padding: 10px;">
                No Dokumen:
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?= esc($document_number ?? $barangKeluar['nomor_transaksi']) ?></strong>
            </td>
            <td class="col-judul" rowspan="2" style="padding: 10px;">
                BERITA ACARA<br>
                PENDISTRIBUSIAN DONASI<br>
                KEPADA MITRA
            </td>
        </tr>
        <tr>
            <td class="col-dokumen" style="padding: 10px;">
                Tanggal Donasi : <strong><?= date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'] ?? date('Y-m-d'))) ?></strong>
            </td>
        </tr>
    </table>
    <!-- Info Mitra -->
    <div class="info-section">
        <table class="info-table">
            <tr>
                <td>Telah diterima dari</td>
                <td>: Yayasan Lumbung Pangan Indonesia Wilayah : <?= esc($barangKeluar['nama_wilayah'] ?? '-') ?></td>
            </tr>
            <?php if (!empty($barangKeluar['penerima_relawan'])): ?>
            <tr>
                <td>Nama Relawan / Penerima</td>
                <td>: <?= esc($barangKeluar['penerima_relawan']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($barangKeluar['unit_internal'])): ?>
            <tr>
                <td>Unit / Bagian Internal</td>
                <td>: <?= esc($barangKeluar['unit_internal']) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Ditujukan kepada</td>
                <td>: <?= esc($barangKeluar['tujuan_penyaluran'] ?? 'Mitra Penerima') ?></td>
            </tr>
        </table>
    </div>

    <?php
    // Hitung total berat dalam array details
    $totalBeratGram = 0;
    $totalCtn = 0;
    $totalQty = 0;
    $details = !empty($details) ? $details : ($items ?? []);
    foreach ($details as $d) {
        $isRepack = (isset($d['bisa_dipecah']) && $d['bisa_dipecah'] == 1);
        $satuanB = strtolower($d['satuan_berat'] ?? 'kg');
        $jumlahKeluar = (float) ($d['jumlah_keluar'] ?? 0);

        if ($isRepack) {
            $beratG = $jumlahKeluar;
            if ($satuanB === 'kg' || $satuanB === 'kilogram') {
                $beratG *= 1000;
            }
            $totalBeratGram += $beratG;
        } else {
            $beratItem = (float) ($d['berat_per_satuan'] ?? 1);
            if ($satuanB === 'kg' || $satuanB === 'kilogram') {
                $beratItem *= 1000;
            }
            $totalBeratGram += ($beratItem * $jumlahKeluar);
        }
        $totalCtn += (int) ($d['jumlah_ctn'] ?? 0);
        $totalQty += $jumlahKeluar;
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
                    <td><?= date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'] ?? date('Y-m-d'))) ?></td>
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
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>CTN</th>
                <th>Total Berat</th>
                <th>Kondisi<br>Donasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($details as $d): ?>
                <?php
                $isRepack = (isset($d['bisa_dipecah']) && $d['bisa_dipecah'] == 1);
                $satuanB = strtolower($d['satuan_berat'] ?? 'kg');

                if ($isRepack) {
                    if ($satuanB === 'gram') {
                        $subTotalKg = $d['jumlah_keluar'] / 1000;
                    } else {
                        $subTotalKg = $d['jumlah_keluar'];
                    }
                    $satuanTampil = ucfirst($d['satuan_berat'] ?? 'Kg');
                } else {
                    $beratS = (float) ($d['berat_per_satuan'] ?? 1);
                    if ($satuanB === 'gram') {
                        $beratSKg = $beratS / 1000;
                    } else {
                        $beratSKg = $beratS;
                    }
                    $subTotalKg = $beratSKg * $d['jumlah_keluar'];
                    $satuanTampil = esc($d['satuan'] ?? 'Pcs');
                }
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="text-align: left;"><?= esc($d['nama_barang']) ?></td>
                    <td><?= format_jumlah($d['jumlah_keluar']) ?></td>
                    <td><?= $satuanTampil ?></td>
                    <td><?= !empty($d['jumlah_ctn']) ? esc($d['jumlah_ctn']) : '-' ?></td>
                    <td><?= format_berat($subTotalKg, 'Kg') ?></td>
                    <td>Baik</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;">Total</td>
                <td style="text-align: center; font-weight: bold;"><?= format_jumlah($totalQty) ?></td>
                <td style="text-align: center; font-weight: bold;">-</td>
                <td style="font-weight: bold;"><?= $totalCtn ?></td>
                <td style="font-weight: bold;"><?= format_berat($totalBeratKg, 'Kg') ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- Ketentuan -->
    <div class="ketentuan">
        <p><strong>KETENTUAN:</strong></p>
        <ol>
            <?php if (!empty($provisions) && is_array($provisions)): ?>
                <?php foreach ($provisions as $prov): ?>
                    <li><?= esc($prov) ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Donasi yang telah diserahkan <strong>TIDAK BOLEH DIPERJUALBELIKAN.</strong></li>
                <li>Donasi digunakan sesuai peruntukannya untuk program bagi anak-anak, lansia, dan kaum papa.</li>
                <li>Apabila terjadi kerusakan barang, Mitra bertanggungjawab untuk membuat Berita Acara Pemeriksaan (BAP) serta melaporkannya kepada pengurus YLPI.</li>
                <li>Mitra bertanggungjawab atas penyaluran donasi dan pelaporannya melalui tautan Survei123 setelah didistribusikan.</li>
            <?php endif; ?>
        </ol>
    </div>

    <p>Dengan demikian, dokumen serah terima barang ini telah ditandatangani dan disepakati oleh kedua belah pihak pada
        tanggal <?= date('d/m/Y', strtotime($barangKeluar['tanggal_keluar'])) ?></p>

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>Yang Menerima</td>
                <td>Yang Menyerahkan</td>
            </tr>
            <tr>
                <td style="padding-top: 70px;">(.................................................)</td>
                <td style="padding-top: 70px;">(.................................................)</td>
            </tr>
        </table>
    </div>

    <!-- Footer Note -->
    <div class="footer-note">
        <p>Foodbank of Indonesia</p>
        <p>Jl. Bendi Utama No.9, RT.7/RW.10, Kby. Lama Utara, Kec. Kebayoran Lama, Kota Jakarta Selatan, Daerah Khusus
            Ibukota Jakarta 12240</p>
    </div>

</body>

</html>