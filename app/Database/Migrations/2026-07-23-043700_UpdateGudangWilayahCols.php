<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateGudangWilayahCols extends Migration
{
    public function up()
    {
        $fieldsMasukKeluar = [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'updated_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
        ];

        $fieldsMaster = [
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'updated_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
        ];

        // master_gudang_wilayah
        $this->forge->addColumn('master_gudang_wilayah', $fieldsMaster);

        // barang_masuk_wilayah
        $this->forge->addColumn('barang_masuk_wilayah', $fieldsMasukKeluar);

        // barang_keluar_wilayah
        $this->forge->addColumn('barang_keluar_wilayah', $fieldsMasukKeluar);

        // stok_gudang_wilayah (NO deleted_at for stok)
        $stokFields = [
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'updated_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('stok_gudang_wilayah', $stokFields);
    }

    public function down()
    {
        $fieldsMasukKeluar = ['deleted_at', 'updated_by', 'created_ip', 'updated_ip'];
        $fieldsMaster = ['updated_by', 'created_ip', 'updated_ip'];
        $stokFields = ['updated_by', 'created_ip', 'updated_ip'];
        
        $this->forge->dropColumn('master_gudang_wilayah', $fieldsMaster);
        $this->forge->dropColumn('barang_masuk_wilayah', $fieldsMasukKeluar);
        $this->forge->dropColumn('barang_keluar_wilayah', $fieldsMasukKeluar);
        $this->forge->dropColumn('stok_gudang_wilayah', $stokFields);
    }
}
