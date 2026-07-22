<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        echo "Memulai generate data dummy...\n";

        // Kategori & Donatur (if empty)
        $kategoriId = $db->table('kategori')->select('id')->limit(1)->get()->getRow()->id ?? 1;
        $donaturId = $db->table('donatur')->select('id')->limit(1)->get()->getRow()->id ?? 1;
        $userId = $db->table('users')->select('id')->limit(1)->get()->getRow()->id ?? 1;

        // 1. Generate Barang (500)
        echo "Generating 500 Barang...\n";
        $db->transStart();
        $barangIds = [];
        for ($i = 1; $i <= 500; $i++) {
            $db->table('barang')->insert([
                'kode_barang' => 'DUMMY-BRG-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_barang' => 'Barang Dummy ' . $i,
                'id_kategori' => $kategoriId,
                'satuan' => 'Pcs',
                'bisa_dipecah' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $barangIds[] = $db->insertID();
        }
        $db->transComplete();

        // 2. Generate Barang Masuk & Batch (5000 Batch total)
        echo "Generating Barang Masuk & 5000 Batch...\n";
        $db->transStart();
        $batchIds = [];
        for ($i = 1; $i <= 5000; $i++) {
            // Buat Barang Masuk
            $db->table('barang_masuk')->insert([
                'nomor_transaksi' => 'BM-DUMMY-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_masuk' => date('Y-m-d', strtotime('-' . rand(1, 365) . ' days')),
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
                'nomor_batch' => 'BATCH-DUMMY-' . $i,
                'tanggal_kedaluwarsa' => date('Y-m-d', strtotime('+' . rand(-30, 365) . ' days')),
                'jumlah_awal' => 100,
                'stok_saat_ini' => 100,
                'satuan' => 'Pcs',
                'status' => 'Aktif',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $batchIds[] = $db->insertID();
        }
        $db->transComplete();

        // 3. Generate Barang Keluar (8000)
        echo "Generating 8000 Barang Keluar...\n";
        $db->transStart();
        for ($i = 1; $i <= 8000; $i++) {
            $db->table('barang_keluar')->insert([
                'nomor_transaksi' => 'BK-DUMMY-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'tanggal_keluar' => date('Y-m-d', strtotime('-' . rand(1, 180) . ' days')),
                'tujuan_penyaluran' => 'Penyaluran Dummy ' . $i,
                'id_wilayah' => 1,
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

        // 4. Generate Penyesuaian (3000)
        echo "Generating 3000 Penyesuaian...\n";
        $db->transStart();
        for ($i = 1; $i <= 3000; $i++) {
            $db->table('penyesuaian_stok')->insert([
                'nomor_penyesuaian' => 'ADJ-DUMMY-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal' => date('Y-m-d', strtotime('-' . rand(1, 90) . ' days')),
                'jenis_penyesuaian' => array_rand(array_flip(['Barang Rusak','Barang Hilang','Barang Kedaluwarsa','Koreksi Positif','Koreksi Negatif'])),
                'keterangan' => 'Keterangan dummy ' . $i,
                'id_user' => $userId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $adjId = $db->insertID();

            $b_id = $barangIds[array_rand($barangIds)];
            $batch_id = $batchIds[array_rand($batchIds)];

            $db->table('detail_penyesuaian_stok')->insert([
                'id_penyesuaian' => $adjId,
                'id_barang' => $b_id,
                'id_batch' => $batch_id,
                'jumlah' => -1,
                'satuan' => 'Pcs',
                'stok_sebelum' => 10,
                'stok_sesudah' => 9,
            ]);
        }
        $db->transComplete();

        echo "Semua data dummy berhasil dibuat!\n";
    }
}
