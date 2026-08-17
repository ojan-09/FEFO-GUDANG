<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RollbackExcelSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $db->transStart();
        
        // Hapus batch dari transaksi import ini
        $db->query("DELETE FROM batch WHERE id_barang_masuk IN (SELECT id FROM barang_masuk WHERE keterangan = 'Import dari Excel JULI7-29.xlsx')");

        // Hapus transaksi barang masuk import ini
        $db->query("DELETE FROM barang_masuk WHERE keterangan = 'Import dari Excel JULI7-29.xlsx'");
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            echo "Gagal rollback.\n";
        } else {
            echo "Berhasil rollback data Excel!\n";
        }
    }
}
