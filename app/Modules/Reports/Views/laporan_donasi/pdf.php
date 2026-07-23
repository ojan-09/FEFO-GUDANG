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
                <?php 
                    $bulanIndo = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                    if($filters['use_custom_date']) {
                        echo date('d/m/Y', strtotime($filters['start_date'])) . ' s.d ' . date('d/m/Y', strtotime($filters['end_date']));
                    } else {
                        $nmBulan = isset($bulanIndo[sprintf('%02d', $filters['bulan'])]) ? $bulanIndo[sprintf('%02d', $filters['bulan'])] : $filters['bulan'];
                        echo $nmBulan . ' ' . $filters['tahun'];
                    }
                ?>
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
                    if(!empty($filters['nomor_donasi'])) $f[] = "Nomor: " . $filters['nomor_donasi'];
                    if(!empty($filters['donatur'])) $f[] = "Donatur: " . $filters['donatur'];
                    if(!empty($filters['search'])) $f[] = "Barang: " . $filters['search'];
                    if(!empty($filters['kategori'])) $f[] = "Kategori: " . $filters['kategori'];
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
                <th width="7%">Tgl Masuk</th>
                <th width="9%">Nomor Donasi</th>
                <th width="10%">Donatur</th>
                <th width="12%">Nama Barang</th>
                <th width="7%">Kategori</th>
                <th width="5%">Jumlah</th>
                <th width="5%">Satuan</th>
                <th width="4%">CTN</th>
                <th width="5%">Berat Bersih</th>
                <th width="5%">Total Berat</th>
                <th width="7%">Kadaluarsa</th>
                <th width="8%">Nilai Satuan</th>
                <th width="9%">Total Nilai</th>
                <th width="5%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php helper('format'); ?>
            <?php if (empty($laporan)): ?>
                <tr>
                    <td colspan="15" class="text-center">Data tidak ditemukan.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($laporan as $item): ?>
                    <?php 
                        $bisaDipecah    = (int) ($item['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float) $item['berat_per_satuan'];
                        if ($bisaDipecah === 1) {
                            $totalBeratRow = (float) $item['jumlah'];
                            if (strtolower($item['satuan_berat']) === 'gram') {
                                $totalBeratRow *= 1000;
                            }
                        } else {
                            $totalBeratRow = $item['jumlah'] * $beratPerSatuan;
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= $item['tanggal_masuk'] ? date('d/m/Y', strtotime($item['tanggal_masuk'])) : '-' ?></td>
                        <td class="text-center"><?= esc($item['nomor_transaksi']) ?></td>
                        <td><?= esc($item['nama_donatur'] ?? '-') ?></td>
                        <td><?= esc($item['nama_barang']) ?></td>
                        <td><?= esc($item['kategori_batch']) ?></td>
                        <td class="text-center"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                        <td class="text-center"><?= esc($item['satuan']) ?></td>
                        <td class="text-center"><?= !empty($item['jumlah_ctn']) ? $item['jumlah_ctn'] : '-' ?></td>
                        <td class="text-right"><?= $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-' ?></td>
                        <td class="text-right"><?= $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-' ?></td>
                        <td class="text-center"><?= $item['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($item['tanggal_kedaluwarsa'])) : '-' ?></td>
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
            <td width="20%" class="font-bold">Total Transaksi</td>
            <td width="80%">: <?= number_format($summary['total_transaksi'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td class="font-bold">Total Barang</td>
            <td>: <?= number_format($summary['total_barang'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td class="font-bold">Total Berat</td>
            <td>: <?= format_berat($summary['total_berat'], 'Kg') ?></td>
        </tr>
        <tr>
            <td class="font-bold">Total Nilai Donasi Masuk</td>
            <td>: Rp <?= number_format($summary['total_nilai_donasi'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="footer text-right">
        Halaman <span id="page-number"></span>
    </div>

</body>
</html>


