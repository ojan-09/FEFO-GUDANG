<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 9pt; color: #000; margin: 0; padding: 0; }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .header { margin-bottom: 16px; text-align: center; }
        .header h1 { font-size: 13pt; margin: 0; text-transform: uppercase; }
        .header h2 { font-size: 11pt; margin: 4px 0 0 0; text-transform: uppercase; }
        hr.divider { border: none; border-top: 2px solid #000; margin: 8px 0 12px 0; }

        .info-table { width: 100%; margin-bottom: 12px; font-size: 9pt; }
        .info-table td { padding: 2px 0; vertical-align: top; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 8.5pt; }
        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table.data-table th {
            font-weight: bold;
            text-align: center;
            background-color: #dce6f1;
            white-space: normal;
        }
        table.data-table tfoot tr td { background-color: #f1f5f9; font-weight: bold; }

        @page { margin: 15mm 12mm; size: A4 landscape; }
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr    { page-break-inside: avoid; }
    </style>
</head>
<body>
    <?php
        $logoPath = FCPATH . 'assets/img/LogoFOI.png';
        if (!file_exists($logoPath)) {
            $logoPath = FCPATH . 'assets/img/LogoFOI.webp';
        }
        $logoSrc = (file_exists($logoPath) && is_readable($logoPath)) ? $logoPath : '';
    ?>

    <div class="header">
        <?php if (!empty($logoSrc)): ?>
            <img src="<?= $logoSrc ?>" alt="FOI Logo" width="65" style="margin-bottom:8px; display:block; margin-left:auto; margin-right:auto;">
        <?php endif; ?>
        <h1>FOODBANK OF INDONESIA</h1>
        <h2><?= esc($title) ?></h2>
    </div>
    <hr class="divider">

    <table class="info-table">
        <tr>
            <td width="14%"><strong>Filter Provinsi</strong></td>
            <td width="1%">:</td>
            <td width="35%"><?= !empty($filters['provinsi']) ? esc($filters['provinsi']) : 'Semua Provinsi' ?></td>

            <?php if ($jenis != 'stok'): ?>
                <?php
                    $sd = !empty($filters['start_date']) ? date('d/m/Y', strtotime($filters['start_date'])) : null;
                    $ed = !empty($filters['end_date'])   ? date('d/m/Y', strtotime($filters['end_date']))   : null;
                ?>
                <td width="14%"><strong>Periode</strong></td>
                <td width="1%">:</td>
                <td width="35%"><?= ($sd && $ed) ? "$sd s.d $ed" : 'Semua Periode' ?></td>
            <?php else: ?>
                <td width="14%"><strong>Tanggal Cetak</strong></td>
                <td width="1%">:</td>
                <td width="35%"><?= date('d/m/Y H:i:s') ?></td>
            <?php endif; ?>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <?php if ($jenis == 'stok'): ?>
                    <th style="width:18%">Gudang</th>
                    <th style="width:24%">Barang</th>
                    <th style="width:18%">Kategori</th>
                    <th style="width:13%">Jumlah</th>
                    <th style="width:10%">Satuan</th>
                    <th style="width:14%">Total Berat</th>
                <?php elseif ($jenis == 'masuk'): ?>
                    <th style="width:7%">Tanggal</th>
                    <th style="width:10%">Kode Transaksi</th>
                    <th style="width:8%">Gudang</th>
                    <th style="width:8%">Donatur</th>
                    <th style="width:11%">Barang</th>
                    <th style="width:6%">Kategori</th>
                    <th style="width:5%">Jumlah</th>
                    <th style="width:5%">Satuan</th>
                    <th style="width:8%">Berat/Sat.</th>
                    <th style="width:8%">Total Berat</th>
                    <th style="width:11%">Harga Sat.</th>
                    <th style="width:10%">Total Nilai</th>
                <?php else: ?>
                    <th style="width:7%">Tanggal</th>
                    <th style="width:12%">Kode Transaksi</th>
                    <th style="width:11%">Gudang</th>
                    <th style="width:11%">Tujuan</th>
                    <th style="width:17%">Barang</th>
                    <th style="width:10%">Kategori</th>
                    <th style="width:6%">Jumlah</th>
                    <th style="width:6%">Satuan</th>
                    <th style="width:9%">Berat Ref.</th>
                    <th style="width:9%">Total Berat</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporan)): ?>
                <tr>
                    <td colspan="<?= $jenis == 'stok' ? 7 : ($jenis == 'masuk' ? 13 : 11) ?>" class="text-center" style="padding:10px;">
                        Tidak ada data yang ditemukan
                    </td>
                </tr>
            <?php else: ?>
                <?php
                    $no = 1;
                    $totalJmlMasuk   = 0; $totalBeratMasuk  = 0; $totalNilaiMasuk  = 0;
                    $totalJmlKeluar  = 0; $totalBeratKeluar = 0;
                    foreach ($laporan as $item):
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
                            $beratPerSat  = (float)($item['berat_per_satuan'] ?? 0);
                            $satuanBerat  = $item['satuan_berat'] ?? 'Kg';
                            $beratInKg    = strtolower($satuanBerat) === 'gram' ? ($beratPerSat / 1000) : $beratPerSat;
                            $totBeratRow  = (float)$item['jumlah'] * $beratInKg;
                            $hrg          = (float)($item['harga_satuan'] ?? 0);
                            $sub          = (float)($item['subtotal_nilai'] ?? ($item['jumlah'] * $hrg));
                            $totalJmlMasuk   += (float)$item['jumlah'];
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
                            $beratRef     = (float)($item['berat_referensi'] ?? 0);
                            $satuanBerat  = $item['satuan_berat'] ?? 'Kg';
                            $beratInKg    = strtolower($satuanBerat) === 'gram' ? ($beratRef / 1000) : $beratRef;
                            $totBeratRow  = (float)$item['jumlah'] * $beratInKg;
                            $totalJmlKeluar   += (float)$item['jumlah'];
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
            <tr>
                <td colspan="7" class="text-right" style="padding:5px;">GRAND TOTAL BARANG MASUK:</td>
                <td class="text-center" style="padding:5px;"><?= number_format($totalJmlMasuk, 0, ',', '.') ?></td>
                <td class="text-center" style="padding:5px;">-</td>
                <td class="text-center" style="padding:5px;">-</td>
                <td class="text-center" style="padding:5px;"><?= number_format($totalBeratMasuk, 2, ',', '.') ?> Kg</td>
                <td class="text-center" style="padding:5px;">-</td>
                <td class="text-right"  style="padding:5px; color:#15803d;">Rp <?= number_format($totalNilaiMasuk, 0, ',', '.') ?></td>
            </tr>
        </tfoot>

        <?php elseif ($jenis == 'keluar' && !empty($laporan)): ?>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right" style="padding:5px;">GRAND TOTAL BARANG KELUAR:</td>
                <td class="text-center" style="padding:5px;"><?= number_format($totalJmlKeluar, 0, ',', '.') ?></td>
                <td class="text-center" style="padding:5px;">-</td>
                <td class="text-center" style="padding:5px;">-</td>
                <td class="text-center" style="padding:5px;"><?= number_format($totalBeratKeluar, 2, ',', '.') ?> Kg</td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</body>
</html>