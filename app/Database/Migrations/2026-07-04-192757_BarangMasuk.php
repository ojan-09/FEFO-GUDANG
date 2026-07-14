<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BarangMasuk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nomor_transaksi' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'id_donatur'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_user'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal_masuk'   => ['type' => 'DATE'],
            'keterangan'      => ['type' => 'TEXT', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_donatur', 'donatur', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('barang_masuk');
    }

    public function down()
    {
        $this->forge->dropTable('barang_masuk');
    }
}

