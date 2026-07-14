<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Barang extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_barang'      => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'id_kategori'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama_barang'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'satuan'           => ['type' => 'VARCHAR', 'constraint' => 50],
            'berat_per_satuan' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'satuan_berat'     => ['type' => 'VARCHAR', 'constraint' => 20],
            'minimum_stok'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_kategori', 'kategori', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('barang');
    }

    public function down()
    {
        $this->forge->dropTable('barang');
    }
}

