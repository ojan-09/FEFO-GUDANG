<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        .header {
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 14pt;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12pt;
            margin: 5px 0 0 0;
            padding: 0;
            text-transform: uppercase;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        .info-table td {
            padding: 2px 0;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10pt;
        }
        table.data-table th {
            font-weight: bold;
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        
        @page {
            margin: 20mm 15mm;
        }
        
        /* Force table header to repeat on new pages */
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr { page-break-inside: avoid; }
        
        /* Page number styling via Dompdf script */
        #page-number:after { content: counter(page); }
    </style>
</head>
<body>

    <div class="header text-center">
        <h1>FOODBANK OF INDONESIA</h1>
        <h2><?= esc($title) ?></h2>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Periode</strong></td>
            <td width="2%">:</td>
            <td width="33%">
                <?php if(!empty($filters['start_date']) && !empty($filters['end_date'])): ?>
                    <?= date('d/m/Y', strtotime($filters['start_date'])) ?> s.d <?= date('d/m/Y', strtotime($filters['end_date'])) ?>
                <?php else: ?>
                    Semua Waktu
                <?php endif; ?>
            </td>
            <td width="15%"><strong>Tanggal Cetak</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= date('d/m/Y H:i:s') ?></td>
        </tr>
        <tr>
            <td><strong>Filter Aktif</strong></td>
            <td>:</td>
            <td>
                <?php 
                    $f = [];
                    if(!empty($filters['nomor_penyaluran'])) $f[] = "Nomor: " . $filters['nomor_penyaluran'];
                    if(!empty($filters['wilayah'])) $f[] = "Wilayah: " . $filters['wilayah'];
                    if(!empty($filters['program'])) $f[] = "Program: " . $filters['program'];
                    if(!empty($filters['search'])) $f[] = "Barang: " . $filters['search'];
                    echo !empty($f) ? esc(implode(', ', $f)) : '-';
                ?>
            </td>
            <td><strong>Dicetak Oleh</strong></td>
            <td>:</td>
            <td>Sistem FEFO Gudang</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="2%">No</th>
                <th width="7%">Tgl Penyaluran</th>
                <th width="8%">No Penyaluran</th>
                <th width="9%">Wilayah Tujuan</th>
                <th width="9%">Program</th>
                <th width="11%">Nama Barang</th>
                <th width="7%">Batch</th>
                <th width="5%">Jumlah</th>
                <th width="5%">Satuan</th>
                <th width="7%">Berat / Satuan</th>
                <th width="7%">Total Berat</th>
                <th width="8%">Nilai Satuan</th>
                <th width="9%">Total Nilai</th>
                <th width="6%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php helper('format'); ?>
            <?php if (empty($laporan)): ?>
                <tr>
                    <td colspan="14" class="text-center">Data tidak ditemukan.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($laporan as $item): ?>
                    <?php 
                        $bisaDipecah = (int)($item['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float) $item['berat_per_satuan'];
                        if ($bisaDipecah === 1) {
                            $totalKg = (float)$item['jumlah'];
                            $satuanStok = 'Kg';
                            $jumlahStok = number_format($item['jumlah'], 2, ',', '.');
                        } else {
                            $totalKg = $item['jumlah'] * $beratPerSatuan;
                            if (strtolower($item['satuan_berat']) === 'gram') {
                                $totalKg = $totalKg / 1000;
                            }
                            $satuanStok = esc($item['satuan']);
                            $jumlahStok = number_format($item['jumlah'], 0, ',', '.');
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= $item['tanggal_keluar'] ? date('d/m/Y', strtotime($item['tanggal_keluar'])) : '-' ?></td>
                        <td class="text-center"><?= esc($item['nomor_transaksi']) ?></td>
                        <td><?= esc($item['nama_wilayah'] ?? '-') ?></td>
                        <td><?= esc($item['program'] ?? '-') ?></td>
                        <td><?= esc($item['nama_barang']) ?></td>
                        <td class="text-center"><?= esc($item['nomor_batch']) ?></td>
                        <td class="text-center"><?= $jumlahStok ?></td>
                        <td class="text-center"><?= $satuanStok ?></td>
                        <td class="text-right"><?= $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-' ?></td>
                        <td class="text-right"><?= $totalKg > 0 ? format_berat($totalKg, 'Kg') : '-' ?></td>
                        <td class="text-right"><?= 'Rp ' . number_format($item['nilai_satuan'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-right"><?= 'Rp ' . number_format($item['total_nilai'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-center"><?= esc($item['petugas'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="info-table" style="margin-top: 20px;">
        <tr>
            <td width="25%" class="font-bold">Total Penyaluran</td>
            <td width="75%">: <?= number_format($summary['total_penyaluran'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td class="font-bold">Total Barang Disalurkan</td>
            <td>: 
                <?php
                $parts = [];
                foreach ($summary['total_barang_utuh_per_satuan'] as $satuan => $jml) {
                    $parts[] = number_format($jml, 0, ',', '.') . ' ' . esc($satuan);
                }
                if ($summary['total_barang_repack'] > 0) {
                    $parts[] = number_format($summary['total_barang_repack'], 2, ',', '.') . ' Kg';
                }
                echo !empty($parts) ? implode(' & ', $parts) : '0';
                ?>
            </td>
        </tr>
        <tr>
            <td class="font-bold">Total Berat</td>
            <td>: <?= format_berat($summary['total_berat'], 'Kg') ?></td>
        </tr>
        <tr>
            <td class="font-bold">Total Nilai Donasi Keluar</td>
            <td>: Rp <?= number_format($summary['total_nilai_donasi'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="footer text-right">
        Halaman <span id="page-number"></span>
    </div>

</body>
</html>


