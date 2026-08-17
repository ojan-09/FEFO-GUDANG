<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMergeStatusToBarang extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'merged'],
                'default'    => 'active',
                'null'       => false,
                'after'      => 'bisa_dipecah'
            ],
            'merged_to' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'status'
            ]
        ];

        $this->forge->addColumn('barang', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('barang', 'status');
        $this->forge->dropColumn('barang', 'merged_to');
    }
}
