<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimestampsToTransaksiWilayah extends Migration
{
    public function up()
    {
        $fields = [
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ];

        $this->forge->addColumn('barang_masuk_wilayah', $fields);
        $this->forge->addColumn('barang_keluar_wilayah', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('barang_masuk_wilayah', ['updated_at']);
        $this->forge->dropColumn('barang_keluar_wilayah', ['updated_at']);
    }
}
