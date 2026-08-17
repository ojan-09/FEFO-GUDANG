<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InspectKecapMashuriSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $barang = $db->query("SELECT * FROM barang WHERE LOWER(nama_barang) LIKE '%kecap%'")->getResultArray();
        echo "=== BARANG MASTER (KECAP) ===\n";
        print_r($barang);

        $batches = $db->query("SELECT * FROM batch WHERE LOWER(nama_barang) LIKE '%kecap%'")->getResultArray();
        echo "\n=== BATCHES (KECAP) ===\n";
        print_r($batches);
    }
}
