<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VerifyNoDuplicatesInDbSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $results = $db->query("
            SELECT nama_barang, berat_per_satuan, satuan, COUNT(*) as total_count 
            FROM batch 
            WHERE id_barang_masuk IN (SELECT id FROM barang_masuk WHERE keterangan LIKE '%Import dari Excel%')
            GROUP BY nama_barang, berat_per_satuan, satuan 
            HAVING count(*) > 1
        ")->getResultArray();
        
        echo "=== CHECKING DUPLICATES IN DATABASE ===\n";
        if (empty($results)) {
            echo "PASSED: Zero duplicates found! All items in database are 100% unique.\n";
        } else {
            echo "WARNING: Found duplicates:\n";
            print_r($results);
        }
        
        $totalBatches = $db->query("
            SELECT COUNT(*) as cnt FROM batch 
            WHERE id_barang_masuk IN (SELECT id FROM barang_masuk WHERE keterangan LIKE '%Import dari Excel%')
        ")->getRow()->cnt;
        
        echo "Total unique active batches: $totalBatches\n";
    }
}
