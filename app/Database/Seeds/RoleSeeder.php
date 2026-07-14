<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Administrator', 'description' => 'Akses penuh sistem'],
            ['name' => 'Petugas Gudang', 'description' => 'Mengelola barang masuk dan keluar'],
            ['name' => 'Pimpinan', 'description' => 'Melihat laporan'],
        ];

        $this->db->table('auth_groups')->insertBatch($data);
    }
}

