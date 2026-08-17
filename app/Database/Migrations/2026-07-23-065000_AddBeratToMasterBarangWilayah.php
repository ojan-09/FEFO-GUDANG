<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBeratToMasterBarangWilayah extends Migration
{
    public function up()
    {
        $fields = [
            'berat_per_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'after'      => 'satuan'
            ],
            'satuan_berat' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
                'after'      => 'berat_per_satuan'
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'status'
            ]
        ];

        $this->forge->addColumn('master_barang_wilayah', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('master_barang_wilayah', ['berat_per_satuan', 'satuan_berat', 'keterangan']);
    }
}
