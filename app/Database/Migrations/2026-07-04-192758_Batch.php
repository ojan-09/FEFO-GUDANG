<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Batch extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_barang_masuk'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_barang'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nomor_batch'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal_masuk'       => ['type' => 'DATE'],
            'tanggal_kedaluwarsa' => ['type' => 'DATE'],
            'jumlah_awal'         => ['type' => 'INT', 'constraint' => 11],
            'stok_saat_ini'       => ['type' => 'INT', 'constraint' => 11],
            'status'              => ['type' => 'ENUM', 'constraint' => ['Aktif', 'Habis', 'Expired'], 'default' => 'Aktif'],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
            'updated_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_barang_masuk', 'barang_masuk', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_barang', 'barang', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('batch');
    }

    public function down()
    {
        $this->forge->dropTable('batch');
    }
}

