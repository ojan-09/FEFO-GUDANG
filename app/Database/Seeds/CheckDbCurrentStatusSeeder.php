<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CheckDbCurrentStatusSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $batches = $db->table('batch')->like('nama_barang', 'L men')->get()->getResultArray();
        
        echo "=== DB BATCHES FOR L-MEN ===\n";
        print_r($batches);
    }
}
