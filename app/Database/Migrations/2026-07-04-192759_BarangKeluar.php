<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BarangKeluar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nomor_transaksi'   => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'id_user'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal_keluar'    => ['type' => 'DATE'],
            'tujuan_penyaluran' => ['type' => 'VARCHAR', 'constraint' => 255],
            'keterangan'        => ['type' => 'TEXT', 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('barang_keluar');
    }

    public function down()
    {
        $this->forge->dropTable('barang_keluar');
    }
}

