<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Modules\Transactions\Models\BarangKeluarModel;
use App\Modules\Transactions\Models\DetailBarangKeluarModel;
use App\Modules\Transactions\Models\BatchModel;

class ProcessBarangKeluarUserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Reset previous test inserts if any
        $db->query("DELETE FROM detail_barang_keluar WHERE id_barang_keluar IN (SELECT id FROM barang_keluar WHERE keterangan LIKE '%Warga Kiarasari%' OR keterangan LIKE '%Dpf Manggarai%')");
        $db->query("DELETE FROM barang_keluar WHERE keterangan LIKE '%Warga Kiarasari%' OR keterangan LIKE '%Dpf Manggarai%'");

        // Reset stocks
        // Teh: restore to 120
        $db->query("UPDATE batch SET jumlah_awal = 120, stok_saat_ini = 120, status = 'Aktif' WHERE nama_barang = 'Teh' AND berat_per_satuan = 50");
        // Susu: restore to 6
        $db->query("UPDATE batch SET jumlah_awal = 6, stok_saat_ini = 6, status = 'Aktif' WHERE nama_barang = 'Susu' AND berat_per_satuan = 400");
        // Kecap: restore to 1104
        $db->query("UPDATE batch SET jumlah_awal = 1104, stok_saat_ini = 1104, status = 'Aktif' WHERE nama_barang LIKE '%kecap%'");
        // Ayam marinasi: restore to 189
        $db->query("UPDATE batch SET jumlah_awal = 189, stok_saat_ini = 189, status = 'Aktif' WHERE nama_barang LIKE '%ayam marinasi%'");
        // Minyak Sunco: set initial to 7 so taking 5 leaves 2
        $db->query("UPDATE batch SET jumlah_awal = 7, stok_saat_ini = 7, status = 'Aktif' WHERE nama_barang LIKE '%sunco%'");

        $bkModel = new BarangKeluarModel();
        $detailModel = new DetailBarangKeluarModel();
        $batchModel = new BatchModel();

        $userId = 1;

        $db->transStart();

        // =========================================================
        // TRANSAKSI 1: Warga Kiarasari Wilayah Bogor (2026-07-24)
        // =========================================================
        $noTrx1 = 'BK-20260724-' . str_pad(rand(100, 999), 4, '0', STR_PAD_LEFT);
        $bkModel->insert([
            'nomor_transaksi' => $noTrx1,
            'id_wilayah' => 6, // Bogor
            'id_user' => $userId,
            'tanggal_keluar' => '2026-07-24',
            'tujuan_penyaluran' => 'Warga Kiarasari',
            'keterangan' => 'Penyaluran Warga Kiarasari Wilayah Bogor'
        ]);
        $idBk1 = $bkModel->getInsertID();

        // 1a. Susu (4 Pouch -> sisa 2)
        $batchSusu = $db->query("SELECT * FROM batch WHERE nama_barang LIKE '%susu%' AND berat_per_satuan = 400 LIMIT 1")->getRowArray();
        if ($batchSusu) {
            $detailModel->insert([
                'id_barang_keluar' => $idBk1,
                'id_batch' => $batchSusu['id'],
                'jumlah_keluar' => 4
            ]);
            $batchModel->update($batchSusu['id'], ['stok_saat_ini' => 2, 'status' => 'Aktif']);
            echo "Bk 1: Susu 4 pouch disalurkan. Sisa stok: 2 Pouch.\n";
        }

        // 1b. Teh (100 Kotak -> sisa 20)
        $batchTeh = $db->query("SELECT * FROM batch WHERE nama_barang = 'Teh' AND berat_per_satuan = 50 LIMIT 1")->getRowArray();
        if ($batchTeh) {
            $detailModel->insert([
                'id_barang_keluar' => $idBk1,
                'id_batch' => $batchTeh['id'],
                'jumlah_keluar' => 100
            ]);
            $batchModel->update($batchTeh['id'], ['stok_saat_ini' => 20, 'status' => 'Aktif']);
            echo "Bk 1: Teh 100 Kotak disalurkan. Sisa stok: 20 Kotak.\n";
        }

        // 1c. Kecap Mashuri (240 Pcs -> sisa 864)
        $batchKecap = $db->query("SELECT * FROM batch WHERE nama_barang LIKE '%kecap%' AND stok_saat_ini >= 240 LIMIT 1")->getRowArray();
        if ($batchKecap) {
            $detailModel->insert([
                'id_barang_keluar' => $idBk1,
                'id_batch' => $batchKecap['id'],
                'jumlah_keluar' => 240
            ]);
            $sisaKecap = (float)$batchKecap['stok_saat_ini'] - 240;
            $batchModel->update($batchKecap['id'], ['stok_saat_ini' => $sisaKecap, 'status' => 'Aktif']);
            echo "Bk 1: Kecap Mashuri 240 Pcs disalurkan. Sisa stok: $sisaKecap Pcs.\n";
        }

        // =========================================================
        // TRANSAKSI 2: Dpf Manggarai (2026-07-17)
        // =========================================================
        $noTrx2 = 'BK-20260717-' . str_pad(rand(100, 999), 4, '0', STR_PAD_LEFT);
        $bkModel->insert([
            'nomor_transaksi' => $noTrx2,
            'id_wilayah' => 8, // Jakarta Selatan (Manggarai)
            'id_user' => $userId,
            'tanggal_keluar' => '2026-07-17',
            'tujuan_penyaluran' => 'Dpf Manggarai',
            'keterangan' => 'Penyaluran Dpf Manggarai'
        ]);
        $idBk2 = $bkModel->getInsertID();

        // 2a. Ayam Marinasi (20 Pack -> sisa 169)
        $batchAyam = $db->query("SELECT * FROM batch WHERE nama_barang LIKE '%ayam marinasi%' LIMIT 1")->getRowArray();
        if ($batchAyam) {
            $detailModel->insert([
                'id_barang_keluar' => $idBk2,
                'id_batch' => $batchAyam['id'],
                'jumlah_keluar' => 20
            ]);
            $sisaAyam = (float)$batchAyam['stok_saat_ini'] - 20;
            $batchModel->update($batchAyam['id'], ['stok_saat_ini' => $sisaAyam, 'status' => 'Aktif']);
            echo "Bk 2: Ayam Marinasi 20 Pack disalurkan. Sisa stok: $sisaAyam Pack.\n";
        }

        // 2b. Minyak Sunco (5 Pouch -> sisa 2)
        $batchSunco = $db->query("SELECT * FROM batch WHERE nama_barang LIKE '%sunco%' LIMIT 1")->getRowArray();
        if ($batchSunco) {
            $detailModel->insert([
                'id_barang_keluar' => $idBk2,
                'id_batch' => $batchSunco['id'],
                'jumlah_keluar' => 5
            ]);
            $sisaSunco = (float)$batchSunco['stok_saat_ini'] - 5;
            $statusSunco = ($sisaSunco <= 0) ? 'Habis' : 'Aktif';
            $batchModel->update($batchSunco['id'], ['stok_saat_ini' => $sisaSunco, 'status' => $statusSunco]);
            echo "Bk 2: Minyak Sunco 5 Pouch disalurkan. Sisa stok: $sisaSunco Pouch.\n";
        }

        $db->transComplete();

        cache()->clean();

        if ($db->transStatus() === false) {
            echo "Failed to record Barang Keluar!\n";
        } else {
            echo "ALL TRANSACTIONS SUCCESSFULLY RECORDED!\n";
        }
    }
}
