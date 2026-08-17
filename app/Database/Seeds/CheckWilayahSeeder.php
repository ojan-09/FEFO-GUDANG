<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CheckWilayahSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $wilayah = $db->table('wilayah')->get()->getResultArray();
        echo "=== EXISTING WILAYAH ===\n";
        print_r($wilayah);
    }
}
