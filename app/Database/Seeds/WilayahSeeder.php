<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_wilayah'   => 'Jakarta Timur',
                'status'         => 'Aktif',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nama_wilayah'   => 'Jakarta Barat',
                'status'         => 'Aktif',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nama_wilayah'   => 'Bekasi Timur',
                'status'         => 'Aktif',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nama_wilayah'   => 'Bekasi Barat',
                'status'         => 'Aktif',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nama_wilayah'   => 'Depok',
                'status'         => 'Aktif',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'nama_wilayah'   => 'Bogor',
                'status'         => 'Aktif',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]
        ];

        // Ensure table is empty to avoid UNIQUE constraint conflicts on re-seeding
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('wilayah')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        
        $this->db->table('wilayah')->insertBatch($data);
    }
}

