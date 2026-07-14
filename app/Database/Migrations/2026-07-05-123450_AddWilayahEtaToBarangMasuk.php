<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWilayahEtaToBarangMasuk extends Migration
{
    public function up()
    {
        $fields = [
            'id_wilayah' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id_donatur'],
            'eta'        => ['type' => 'DATE', 'null' => true, 'after' => 'tanggal_masuk'],
        ];
        $this->forge->addColumn('barang_masuk', $fields);

        // Tambahkan Foreign Key ke tabel wilayah
        $this->db->query('ALTER TABLE barang_masuk ADD CONSTRAINT barang_masuk_id_wilayah_foreign FOREIGN KEY (id_wilayah) REFERENCES wilayah(id) ON UPDATE CASCADE ON DELETE SET NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE barang_masuk DROP FOREIGN KEY barang_masuk_id_wilayah_foreign');
        $this->forge->dropColumn('barang_masuk', ['id_wilayah', 'eta']);
    }
}

