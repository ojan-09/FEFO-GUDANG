<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'        => 'Administrator',
                'description' => 'Akses penuh ke seluruh modul sistem.'
            ],
            [
                'name'        => 'Petugas Gudang',
                'description' => 'Akses terbatas untuk operasional gudang dan transaksi.'
            ],
            [
                'name'        => 'Pimpinan',
                'description' => 'Akses pemantauan (Read-Only) untuk laporan dan stok.'
            ]
        ];

        foreach ($data as $group) {
            $existing = $this->db->table('auth_groups')->where('name', $group['name'])->countAllResults();
            if ($existing == 0) {
                $this->db->table('auth_groups')->insert($group);
            }
        }
    }
}

