<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FixKecapMashuriStokSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $db->transStart();
        
        // 1. Reset detail_barang_keluar for Kecap Mashuri
        // Find existing detail_barang_keluar records for kecap
        $db->query("
            DELETE FROM detail_barang_keluar 
            WHERE id_batch IN (SELECT id FROM batch WHERE LOWER(nama_barang) LIKE '%kecap%')
        ");

        // 2. Fix Batch 1 (Exp: 2026-07-25) -> Initial 46
        $db->query("
            UPDATE batch 
            SET jumlah_awal = 46.00, stok_saat_ini = 46.00, status = 'Aktif' 
            WHERE LOWER(nama_barang) LIKE '%kecap%' AND tanggal_kedaluwarsa = '2026-07-25'
        ");

        // 3. Fix Batch 2 (Exp: 2026-09-05) -> Initial 1104
        $db->query("
            UPDATE batch 
            SET jumlah_awal = 1104.00, stok_saat_ini = 1104.00, status = 'Aktif' 
            WHERE LOWER(nama_barang) LIKE '%kecap%' AND tanggal_kedaluwarsa = '2026-09-05'
        ");

        // 4. Re-apply Barang Keluar Penyaluran Warga Kiarasari (240 pcs Kecap according to FEFO)
        // Find Barang Keluar ID for Warga Kiarasari
        $bk1 = $db->query("SELECT id FROM barang_keluar WHERE keterangan LIKE '%Warga Kiarasari%' ORDER BY id DESC LIMIT 1")->getRowArray();
        
        if ($bk1) {
            $idBk1 = $bk1['id'];

            $batch1 = $db->query("SELECT * FROM batch WHERE LOWER(nama_barang) LIKE '%kecap%' AND tanggal_kedaluwarsa = '2026-07-25' LIMIT 1")->getRowArray();
            $batch2 = $db->query("SELECT * FROM batch WHERE LOWER(nama_barang) LIKE '%kecap%' AND tanggal_kedaluwarsa = '2026-09-05' LIMIT 1")->getRowArray();

            $reqKecap = 240;

            // Take 46 from Batch 1 (Exp 25-Jul-2026) -> Batch 1 becomes 0 (Habis)
            if ($batch1) {
                $db->table('detail_barang_keluar')->insert([
                    'id_barang_keluar' => $idBk1,
                    'id_batch' => $batch1['id'],
                    'jumlah_keluar' => 46
                ]);
                $db->table('batch')->where('id', $batch1['id'])->update([
                    'stok_saat_ini' => 0,
                    'status' => 'Habis'
                ]);
                $reqKecap -= 46;
                echo "Kecap FEFO Step 1: Disalurkan 46 Sachet dari Batch ED 25-Jul-2026 (Stok Batch 1 -> 0 / Habis).\n";
            }

            // Take remaining 194 from Batch 2 (Exp 05-Sep-2026) -> Batch 2 becomes 910
            if ($batch2 && $reqKecap > 0) {
                $db->table('detail_barang_keluar')->insert([
                    'id_barang_keluar' => $idBk1,
                    'id_batch' => $batch2['id'],
                    'jumlah_keluar' => $reqKecap
                ]);
                $sisa2 = 1104 - $reqKecap;
                $db->table('batch')->where('id', $batch2['id'])->update([
                    'stok_saat_ini' => $sisa2,
                    'status' => 'Aktif'
                ]);
                echo "Kecap FEFO Step 2: Disalurkan $reqKecap Sachet dari Batch ED 05-Sep-2026 (Stok Batch 2 -> $sisa2 / Aktif).\n";
            }
        }

        $db->transComplete();

        cache()->clean();

        if ($db->transStatus() === false) {
            echo "Failed to fix Kecap Mashuri stock.\n";
        } else {
            echo "SUCCESS: Kecap Mashuri stock fixed and FEFO allocation re-applied cleanly!\n";
        }
    }
}
