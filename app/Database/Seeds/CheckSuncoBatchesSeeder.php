<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CheckSuncoBatchesSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $batches = $db->query("SELECT * FROM batch WHERE nama_barang LIKE '%sunco%'")->getResultArray();
        echo "=== ALL SUNCO BATCHES ===\n";
        print_r($batches);
    }
}
