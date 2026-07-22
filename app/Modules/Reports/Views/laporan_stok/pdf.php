<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        @page { size: A4 landscape; margin: 15mm 10mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10pt; color: #000; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .header-table td { border: 1px solid #999; vertical-align: middle; padding: 6px; }
        .header-left { width: 140px; font-style: italic; font-size: 10pt; text-align: center; }
        .header-title { text-align: center; font-size: 10pt; font-weight: bold; }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { border: 1px solid #999; padding: 3px 4px; }
        .data-table th { background-color: #f3f3f3; text-align: center; font-weight: bold; }

        .total-row td { font-weight: bold; }

        .footer-note { font-size: 10pt; border: 1px solid #999; border-top: none; padding: 4px 6px; }

        .sign-table { width: 100%; border-collapse: collapse; margin-top: 0; }
        .sign-table td { border: 1px solid #999; border-top: none; width: 33%; text-align: center; padding: 40px 6px 10px 6px; }

        .badge-danger  { color: #c00000; font-weight: bold; }
        .badge-warning { color: #e08a00; font-weight: bold; }
        .badge-success { color: #2e7d32; font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-left">STO <?= date('j F Y') ?></td>
            <td class="header-title">STO <?= date('j F Y') ?></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="22">No</th>
                <th>Status</th>
                <th>Asal/ Lokasi Barang/ Donatur</th>
                <th>Kategori</th>
                <th>Kadaluarsa</th>
                <th>Nama Barang</th>
                <th>Jumlah (pcs)</th>
                <th>SKU</th>
                <th>Gram</th>
                <th>Total Berat</th>
                <th>Jumlah (CTN)</th>
                <th>Catatan</th>
                <th>Week <?= date('W') ?> (pcs)</th>
            </tr>
        </thead>
        <tbody>
            <?php
                helper('format');
                $no = 1;
                $totalBatch        = count($laporan);
                $totalStok         = 0;
                $totalBeratSeluruh = 0;
            ?>
            <?php if ($totalBatch == 0): ?>
                <tr>
                    <td colspan="13" class="text-center">Data tidak ditemukan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($laporan as $stok): ?>
                    <?php
                        $bisaDipecah    = (int) ($stok['bisa_dipecah'] ?? 0);
                        $beratPerSatuan = (float) $stok['berat_per_satuan'];
                        if ($bisaDipecah === 1) {
                            $totalBeratRow = (float) $stok['stok_saat_ini'];
                            if (strtolower($stok['satuan_berat']) === 'gram') {
                                $totalBeratRow *= 1000;
                            }
                        } else {
                            $totalBeratRow  = $stok['stok_saat_ini'] * $beratPerSatuan;
                        }
                        $totalKg    = (strtolower($stok['satuan_berat']) === 'gram') ? $totalBeratRow / 1000 : $totalBeratRow;
                        $totalStok += $stok['stok_saat_ini'];
                        $totalBeratSeluruh += $totalKg;

                        $statusClass = '';
                        if ($stok['status'] == 'Expired')            $statusClass = 'badge-danger';
                        elseif ($stok['status'] == 'Hampir Expired') $statusClass = 'badge-warning';
                        elseif ($stok['status'] == 'Aman')           $statusClass = 'badge-success';
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center <?= $statusClass ?>"><?= esc($stok['status']) ?></td>
                        <td><?= esc($stok['donatur'] ?? '-') ?></td>
                        <td><?= esc($stok['kategori']) ?></td>
                        <td class="text-center">
                            <?= $stok['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($stok['tanggal_kedaluwarsa'])) : '-' ?>
                        </td>
                        <td><?= esc($stok['nama_barang']) ?></td>
                        <td class="text-center"><strong><?= number_format($stok['stok_saat_ini'], 0, ',', '.') ?></strong></td>
                        <td class="text-center"><?= esc($stok['satuan']) ?></td>
                        <td class="text-right"><?= $beratPerSatuan > 0 ? number_format($beratPerSatuan, 2, ',', '.') : '-' ?></td>
                        <td class="text-right"><strong><?= $totalKg > 0 ? number_format($totalKg, 2, ',', '.') : '-' ?></strong></td>
                        <td class="text-center"><?= !empty($stok['jumlah_ctn']) ? esc($stok['jumlah_ctn']) : '-' ?></td>
                        <td><?= esc($stok['catatan'] ?? '-') ?></td>
                        <td class="text-center">-</td>
                    </tr>
                <?php endforeach; ?>

                <tr class="total-row">
                    <td colspan="9" class="text-right">Total</td>
                    <td class="text-right"><?= number_format($totalBeratSeluruh, 2, ',', '.') ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-note">
        Berikut adalah data stock opname barang donasi di gudang logistik Foodbank of Indonesia 
        per tanggal <?= date('j F Y') ?> pukul <?= date('H.i') ?> WIB.
    </div>

    <table class="sign-table">
        <tr>
            <td>Menyusun,</td>
            <td>Mengetahui,</td>
            <td>Menyetujui,</td>
        </tr>
    </table>

</body>
</html>