<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { margin-bottom: 20px; }
        .header h1 { font-size: 14pt; margin: 0; padding: 0; text-transform: uppercase; }
        .header h2 { font-size: 12pt; margin: 5px 0 0 0; padding: 0; text-transform: uppercase; }
        .info-table { width: 100%; margin-bottom: 15px; font-size: 10pt; }
        .info-table td { padding: 2px 0; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 5px; font-size: 10pt; }
        table.data-table th { font-weight: bold; text-align: center; background-color: #f2f2f2; }
        @page { margin: 20mm 15mm; }
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>
    <?php
        $logoPath = FCPATH . 'assets/img/LogoFOI.png';
        if (!file_exists($logoPath)) {
            $logoPath = FCPATH . 'assets/img/LogoFOI.webp';
        }
        $logoSrc = '';
        if (file_exists($logoPath) && is_readable($logoPath)) {
            $logoSrc = $logoPath;
        }
    ?>
    <div class="header text-center">
        <?php if (!empty($logoSrc)): ?>
            <img src="<?= $logoSrc ?>" alt="FOI Logo" width="70" style="margin-bottom: 10px;">
        <?php endif; ?>
        <h1>FOODBANK OF INDONESIA</h1>
        <h2><?= esc($title) ?></h2>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Filter Provinsi</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= !empty($filters['provinsi']) ? esc($filters['provinsi']) : 'Semua Provinsi' ?></td>
            
            <?php if($jenis != 'stok'): ?>
            <td width="15%"><strong>Periode</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= date('d/m/Y', strtotime($filters['start_date'])) ?> s.d <?= date('d/m/Y', strtotime($filters['end_date'])) ?></td>
            <?php else: ?>
            <td width="15%"><strong>Tanggal Cetak</strong></td>
            <td width="2%">:</td>
            <td width="33%"><?= date('d/m/Y H:i:s') ?></td>
            <?php endif; ?>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <?php if ($jenis == 'stok'): ?>
                    <th width="15%">Gudang</th>
                    <th width="20%">Barang</th>
                    <th width="15%">Kategori</th>
                    <th width="10%">Jumlah</th>
                    <th width="8%">Satuan</th>
                    <th width="10%">Total Berat</th>
                <?php elseif ($jenis == 'masuk'): ?>
                    <th width="7%">Tanggal</th>
                    <th width="10%">Kode Transaksi</th>
                    <th width="10%">Gudang</th>
                    <th width="10%">Donatur</th>
                    <th width="14%">Barang</th>
                    <th width="8%">Kategori</th>
                    <th width="6%">Jumlah</th>
                    <th width="5%">Satuan</th>
                    <th width="7%">Berat/Sat.</th>
                    <th width="7%">Total Berat</th>
                    <th width="8%">Harga Sat.</th>
                    <th width="8%">Total Nilai</th>
                <?php else: ?>
                    <th width="8%">Tanggal</th>
                    <th width="11%">Kode Transaksi</th>
                    <th width="11%">Gudang</th>
                    <th width="11%">Tujuan</th>
                    <th width="15%">Barang</th>
                    <th width="10%">Kategori</th>
                    <th width="7%">Jumlah</th>
                    <th width="5%">Satuan</th>
                    <th width="8%">Berat Ref.</th>
                    <th width="9%">Total Berat</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="<?= $jenis == 'stok' ? 7 : ($jenis == 'masuk' ? 13 : 10) ?>" class="text-center">Tidak ada data yang ditemukan</td>
                </tr>
            <?php else: ?>
                <?php 
                    $no = 1; 
                    $totalJmlMasuk = 0;
                    $totalBeratMasuk = 0;
                    $totalNilaiMasuk = 0;

                    $totalJmlKeluar = 0;
                    $totalBeratKeluar = 0;

                    foreach($laporan as $item): 
                ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        
                        <?php if ($jenis == 'stok'): ?>
                            <td><?= esc($item['nama_gudang']) ?></td>
                            <td><?= esc($item['kode_barang']) ?><br><?= esc($item['nama_barang']) ?></td>
                            <td><?= esc($item['kategori']) ?></td>
                            <td class="text-center"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= esc($item['satuan']) ?></td>
                            <td class="text-center"><?= number_format($item['total_berat'] ?? 0, 2, ',', '.') ?> kg</td>
                        <?php elseif ($jenis == 'masuk'): ?>
                            <?php
                                $beratPerSat = (float)($item['berat_per_satuan'] ?? 0);
                                $satuanBerat = $item['satuan_berat'] ?? 'Kg';
                                $beratInKg   = strtolower($satuanBerat) === 'gram' ? ($beratPerSat / 1000) : $beratPerSat;
                                $totBeratRow = (float)$item['jumlah'] * $beratInKg;

                                $hrg = (float) ($item['harga_satuan'] ?? 0);
                                $sub = (float) ($item['subtotal_nilai'] ?? ($item['jumlah'] * $hrg));
                                
                                $totalJmlMasuk += (float)$item['jumlah'];
                                $totalBeratMasuk += $totBeratRow;
                                $totalNilaiMasuk += $sub;
                            ?>
                            <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                            <td><?= esc($item['nomor_dokumen']) ?></td>
                            <td><?= esc($item['nama_gudang']) ?></td>
                            <td><?= esc($item['donatur'] ?? '-') ?></td>
                            <td><?= esc($item['kode_barang']) ?><br><?= esc($item['nama_barang']) ?></td>
                            <td><?= esc($item['kategori']) ?></td>
                            <td class="text-center"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= esc($item['satuan']) ?></td>
                            <td class="text-center"><?= $beratPerSat > 0 ? number_format($beratPerSat, 2, ',', '.') . ' ' . $satuanBerat : '-' ?></td>
                            <td class="text-center"><?= $totBeratRow > 0 ? number_format($totBeratRow, 2, ',', '.') . ' Kg' : '-' ?></td>
                            <td class="text-right"><?= $hrg > 0 ? 'Rp ' . number_format($hrg, 0, ',', '.') : '-' ?></td>
                            <td class="text-right"><?= $sub > 0 ? 'Rp ' . number_format($sub, 0, ',', '.') : '-' ?></td>
                        <?php else: ?>
                            <?php
                                $beratRef    = (float)($item['berat_referensi'] ?? 0);
                                $satuanBerat = $item['satuan_berat'] ?? 'Kg';
                                $beratInKg   = strtolower($satuanBerat) === 'gram' ? ($beratRef / 1000) : $beratRef;
                                $totBeratRow = (float)$item['jumlah'] * $beratInKg;

                                $totalJmlKeluar += (float)$item['jumlah'];
                                $totalBeratKeluar += $totBeratRow;
                            ?>
                            <td class="text-center"><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                            <td><?= esc($item['nomor_dokumen']) ?></td>
                            <td><?= esc($item['nama_gudang']) ?></td>
                            <td><?= esc($item['tujuan'] ?? '-') ?></td>
                            <td><?= esc($item['kode_barang']) ?><br><?= esc($item['nama_barang']) ?></td>
                            <td><?= esc($item['kategori']) ?></td>
                            <td class="text-center"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= esc($item['satuan']) ?></td>
                            <td class="text-center"><?= $beratRef > 0 ? number_format($beratRef, 2, ',', '.') . ' ' . $satuanBerat : '-' ?></td>
                            <td class="text-center"><?= $totBeratRow > 0 ? number_format($totBeratRow, 2, ',', '.') . ' Kg' : '-' ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if ($jenis == 'masuk' && !empty($laporan)): ?>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="6" style="text-align: right; padding: 5px;">GRAND TOTAL BARANG MASUK:</td>
                <td class="text-center" style="padding: 5px;"><?= number_format($totalJmlMasuk, 0, ',', '.') ?></td>
                <td style="padding: 5px;">-</td>
                <td style="padding: 5px;">-</td>
                <td class="text-center" style="padding: 5px;"><?= number_format($totalBeratMasuk, 2, ',', '.') ?> Kg</td>
                <td style="padding: 5px;">-</td>
                <td class="text-right" style="padding: 5px; color: #15803d;">Rp <?= number_format($totalNilaiMasuk, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
        <?php elseif ($jenis == 'keluar' && !empty($laporan)): ?>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="6" style="text-align: right; padding: 5px;">GRAND TOTAL BARANG KELUAR:</td>
                <td class="text-center" style="padding: 5px;"><?= number_format($totalJmlKeluar, 0, ',', '.') ?></td>
                <td style="padding: 5px;">-</td>
                <td style="padding: 5px;">-</td>
                <td class="text-center" style="padding: 5px;"><?= number_format($totalBeratKeluar, 2, ',', '.') ?> Kg</td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</body>
</html>
