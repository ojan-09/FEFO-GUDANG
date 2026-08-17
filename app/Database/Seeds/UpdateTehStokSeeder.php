<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateTehStokSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $db->transStart();
        
        // Find Teh batch with stock 160
        $affected = $db->table('batch')
            ->where('nama_barang', 'Teh')
            ->where('stok_saat_ini', 160)
            ->update([
                'jumlah_awal' => 120,
                'stok_saat_ini' => 120,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
        $db->transComplete();
        
        // Clean dashboard cache
        cache()->clean();
        
        if ($db->transStatus() === false) {
            echo "Failed to update Teh stock.\n";
        } else {
            echo "SUCCESS: Teh stock updated from 160 to 120! Cache cleared.\n";
        }
    }
}
