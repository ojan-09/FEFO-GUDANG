<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CheckMasterBarangDuplicatesSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $results = $db->query("
            SELECT nama_barang, berat_per_satuan, satuan, COUNT(*) as total_count 
            FROM barang 
            GROUP BY nama_barang, berat_per_satuan, satuan 
            HAVING count(*) > 1
        ")->getResultArray();
        
        echo "=== CHECKING MASTER BARANG DUPLICATES ===\n";
        if (empty($results)) {
            echo "PASSED 100%! Master Barang is 100% clean with NO DUPLICATES.\n";
        } else {
            echo "Found duplicate Master Barang:\n";
            print_r($results);
        }
    }
}
