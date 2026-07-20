<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Insert500Seeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        echo "Memulai generate 500 data tambahan...\n";

        // Get random defaults
        $donaturId = $db->table('donatur')->select('id')->limit(1)->get()->getRow()->id ?? 1;
        $userId = $db->table('users')->select('id')->limit(1)->get()->getRow()->id ?? 1;
        $wilayahId = $db->table('wilayah')->select('id')->limit(1)->get()->getRow()->id ?? 1;

        // Get available barang
        $barangRows = $db->table('barang')->select('id')->get()->getResultArray();
        $barangIds = array_column($barangRows, 'id');
        if (empty($barangIds)) {
            die("Tidak ada barang di database.\n");
        }

        $db->transStart();

        // 1. Generate 500 Barang Masuk
        echo "Generating 500 Barang Masuk...\n";
        $batchIds = [];
        $time = time();
        for ($i = 1; $i <= 500; $i++) {
            $db->table('barang_masuk')->insert([
                'nomor_transaksi' => 'BM-EX-' . $time . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_masuk' => date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')),
                'id_donatur' => $donaturId,
                'id_user' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $bmId = $db->insertID();

            $b_id = $barangIds[array_rand($barangIds)];
            
            // Buat Batch
            $db->table('batch')->insert([
                'id_barang' => $b_id,
                'id_barang_masuk' => $bmId,
                'nomor_batch' => 'BATCH-EX-' . $time . '-' . $i,
                'tanggal_kedaluwarsa' => date('Y-m-d', strtotime('+' . rand(30, 365) . ' days')),
                'jumlah_awal' => 100,
                'stok_saat_ini' => 100,
                'satuan' => 'Pcs',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $batchIds[] = $db->insertID();
        }

        // 2. Generate 500 Barang Keluar
        echo "Generating 500 Barang Keluar...\n";
        for ($i = 1; $i <= 500; $i++) {
            $db->table('barang_keluar')->insert([
                'nomor_transaksi' => 'BK-EX-' . $time . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_keluar' => date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')),
                'tujuan_penyaluran' => 'Penyaluran Extra ' . $i,
                'id_wilayah' => $wilayahId,
                'id_user' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $bkId = $db->insertID();

            // 1 Detail per Barang Keluar
            $batch_id = $batchIds[array_rand($batchIds)];
            $db->table('detail_barang_keluar')->insert([
                'id_barang_keluar' => $bkId,
                'id_batch' => $batch_id,
                'jumlah_keluar' => rand(1, 5),
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            echo "Gagal insert data.\n";
        } else {
            echo "500 data berhasil dimasukkan ke barang_masuk dan barang_keluar!\n";
        }
    }
}
