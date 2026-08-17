<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefactorWilayahToHeaderDetail extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Truncate to avoid constraint issues during column drops
        $db->table('barang_masuk_wilayah')->truncate();
        $db->table('barang_keluar_wilayah')->truncate();
        $db->table('master_barang_wilayah')->truncate();

        // 1. Refactor master_barang_wilayah
        $this->forge->dropColumn('master_barang_wilayah', 'kategori');
        $this->forge->addColumn('master_barang_wilayah', [
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'nama_barang',
                'null'       => true
            ]
        ]);

        // 2. Refactor barang_masuk_wilayah
        $this->forge->dropColumn('barang_masuk_wilayah', ['id_barang', 'jumlah', 'satuan', 'donatur']);
        $this->forge->addColumn('barang_masuk_wilayah', [
            'id_donatur' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'id_gudang',
                'null'       => true
            ]
        ]);

        // 3. Create detail_barang_masuk_wilayah
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_masuk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_barang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_masuk');
        $this->forge->addKey('id_barang');
        $this->forge->createTable('detail_barang_masuk_wilayah', true);

        // 4. Refactor barang_keluar_wilayah
        $this->forge->dropColumn('barang_keluar_wilayah', ['id_barang', 'jumlah', 'satuan']);

        // 5. Create detail_barang_keluar_wilayah
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_keluar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_barang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_keluar');
        $this->forge->addKey('id_barang');
        $this->forge->createTable('detail_barang_keluar_wilayah', true);
    }

    public function down()
    {
        $this->forge->dropTable('detail_barang_masuk_wilayah', true);
        $this->forge->dropTable('detail_barang_keluar_wilayah', true);
    }
}
