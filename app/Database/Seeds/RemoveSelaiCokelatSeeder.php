<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RemoveSelaiCokelatSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $db->transStart();
        
        // Find batch for Selai Cokelat
        $db->query("DELETE FROM batch WHERE nama_barang LIKE '%selai cokelat%' OR nama_barang LIKE '%selai coklat%'");
        $db->query("DELETE FROM barang WHERE nama_barang LIKE '%selai cokelat%' OR nama_barang LIKE '%selai coklat%'");
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            echo "Failed to remove Selai Cokelat.\n";
        } else {
            echo "SUCCESS: Selai Cokelat removed from DB! Only Selai Strawberry remains.\n";
        }
    }
}
