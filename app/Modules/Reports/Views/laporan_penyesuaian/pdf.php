<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penyesuaian Stok</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-success { color: green; }
        .text-danger { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENYESUAIAN STOK GUDANG</h1>
        <p>Foodbank of Indonesia</p>
        <p>Periode: <?= date('d M Y', strtotime($start_date)) ?> s/d <?= date('d M Y', strtotime($end_date)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="70">Tanggal</th>
                <th width="90">No. Transaksi</th>
                <th width="90">Jenis Penyesuaian</th>
                <th width="150">Barang</th>
                <th width="100">Batch & Exp</th>
                <th width="60" class="text-right">Jumlah</th>
                <th width="40">Satuan</th>
                <th width="150">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($laporan as $row) : 
                $isPlus = ($row['jenis_penyesuaian'] === 'Koreksi Positif');
                $sign = $isPlus ? '+' : '-';
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                <td><?= $row['nomor_penyesuaian'] ?></td>
                <td><?= $row['jenis_penyesuaian'] ?></td>
                <td><?= $row['nama_barang'] ?></td>
                <td>
                    <?= $row['nomor_batch'] ?><br>
                    <small>Exp: <?= $row['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($row['tanggal_kedaluwarsa'])) : '-' ?></small>
                </td>
                <td class="text-right"><?= $sign ?><?= (isset($row['bisa_dipecah']) && $row['bisa_dipecah'] == 1) ? $row['jumlah'] : number_format($row['jumlah'], 0, ',', '.') ?></td>
                <td class="text-center"><?= $row['satuan'] ?></td>
                <td><?= $row['ket_umum'] ?><br><i><?= $row['keterangan'] ?></i></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($laporan)): ?>
            <tr>
                <td colspan="9" class="text-center">Tidak ada data penyesuaian stok pada periode ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
