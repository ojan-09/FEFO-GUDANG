<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailBarangKeluar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_barang_keluar' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_batch'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah_keluar'    => ['type' => 'INT', 'constraint' => 11],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_barang_keluar', 'barang_keluar', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_batch', 'batch', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('detail_barang_keluar');
    }

    public function down()
    {
        $this->forge->dropTable('detail_barang_keluar');
    }
}

