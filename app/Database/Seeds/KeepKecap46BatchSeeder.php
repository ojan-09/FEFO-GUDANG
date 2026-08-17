<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KeepKecap46BatchSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $db->transStart();

        // 1. Set Batch 1 (ED 25-Jul-2026) -> 46 Sachet (Aktif)
        $db->query("
            UPDATE batch 
            SET jumlah_awal = 46.00, stok_saat_ini = 46.00, status = 'Aktif' 
            WHERE LOWER(nama_barang) LIKE '%kecap%' AND tanggal_kedaluwarsa = '2026-07-25'
        ");

        // 2. Set Batch 2 (ED 05-Sep-2026) -> 864 Sachet (Initial 1104 - 240 disalurkan = 864)
        $db->query("
            UPDATE batch 
            SET jumlah_awal = 1104.00, stok_saat_ini = 864.00, status = 'Aktif' 
            WHERE LOWER(nama_barang) LIKE '%kecap%' AND tanggal_kedaluwarsa = '2026-09-05'
        ");

        // 3. Update detail_barang_keluar so the 240 pcs is linked to Batch 2 (ED 05-Sep-2026)
        $batch2 = $db->query("SELECT id FROM batch WHERE LOWER(nama_barang) LIKE '%kecap%' AND tanggal_kedaluwarsa = '2026-09-05' LIMIT 1")->getRowArray();
        $bk1 = $db->query("SELECT id FROM barang_keluar WHERE keterangan LIKE '%Warga Kiarasari%' ORDER BY id DESC LIMIT 1")->getRowArray();

        if ($batch2 && $bk1) {
            // Remove previous kecap detail_barang_keluar entries for this transaction
            $db->query("
                DELETE FROM detail_barang_keluar 
                WHERE id_barang_keluar = {$bk1['id']} 
                AND id_batch IN (SELECT id FROM batch WHERE LOWER(nama_barang) LIKE '%kecap%')
            ");

            // Insert single entry linking 240 pcs to Batch 2
            $db->table('detail_barang_keluar')->insert([
                'id_barang_keluar' => $bk1['id'],
                'id_batch' => $batch2['id'],
                'jumlah_keluar' => 240
            ]);
        }

        $db->transComplete();

        cache()->clean();

        if ($db->transStatus() === false) {
            echo "Failed to update Kecap Mashuri batches.\n";
        } else {
            echo "SUCCESS: Batch ED 25-Jul-2026 kept at 46 Sachet, Batch ED 05-Sep-2026 set to 864 Sachet!\n";
        }
    }
}
