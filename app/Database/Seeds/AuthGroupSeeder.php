<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthGroupSeeder extends Seeder
{
    public function run()
    {
        // 1. Kosongkan tabel auth_groups jika ada sisa grup lama yang tidak dipakai
        $this->db->table('auth_groups')->emptyTable();
        
        // 2. Insert standard groups
        $data = [
            [
                'id'          => 1,
                'name'        => 'Administrator',
                'description' => 'Akses penuh ke seluruh modul sistem.'
            ],
            [
                'id'          => 2,
                'name'        => 'Petugas Gudang',
                'description' => 'Akses terbatas untuk operasional gudang dan transaksi.'
            ]
        ];

        $this->db->table('auth_groups')->insertBatch($data);
    }
}

