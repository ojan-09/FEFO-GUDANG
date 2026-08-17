<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClearCacheAndCheckDashboardSeeder extends Seeder
{
    public function run()
    {
        cache()->clean();
        echo "Cache cleared!\n\n";

        $db = \Config\Database::connect();
        
        $rawTopBarang = $db->table('batch')
            ->select('barang.id, barang.nama_barang, batch.satuan, batch.stok_saat_ini, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
            ->join('barang', 'barang.id = batch.id_barang')
            ->where('batch.stok_saat_ini >', 0)
            ->get()->getResultArray();

        $barangConsolidated = [];
        foreach ($rawTopBarang as $b) {
            $id = $b['id'];
            $stok = (float)$b['stok_saat_ini'];
            $beratPerSatuan = (float)$b['berat_per_satuan'];
            $satuanBerat = strtolower($b['satuan_berat'] ?? '');
            $bisaDipecah = (int)$b['bisa_dipecah'];

            if ($bisaDipecah === 1) {
                $beratKg = $stok;
            } else {
                $beratKg = $stok * $beratPerSatuan;
                if ($satuanBerat === 'gram') {
                    $beratKg = $beratKg / 1000;
                }
            }

            if (isset($barangConsolidated[$id])) {
                $barangConsolidated[$id]['total_stok'] += $stok;
                $barangConsolidated[$id]['total_berat'] += $beratKg;
            } else {
                $barangConsolidated[$id] = [
                    'nama_barang' => $b['nama_barang'],
                    'satuan'      => $bisaDipecah === 1 ? 'Kg' : $b['satuan'],
                    'total_stok'  => $stok,
                    'total_berat' => $beratKg
                ];
            }
        }

        echo "=== FRESH DASHBOARD TOP BARANG CALCULATION ===\n";
        foreach ($barangConsolidated as $item) {
            if (stristr($item['nama_barang'], 'ayam marinasi') !== false) {
                echo "Nama: " . $item['nama_barang'] . " | Total Stok: " . $item['total_stok'] . " " . $item['satuan'] . " | Total Berat: " . $item['total_berat'] . " Kg\n";
            }
        }
    }
}
