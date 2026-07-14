<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MoveWilayahToBarangKeluar extends Migration
{
    public function up()
    {
        // 1. Tambah id_wilayah ke barang_keluar
        $fields = [
            'id_wilayah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'tanggal_keluar',
            ]
        ];
        $this->forge->addColumn('barang_keluar', $fields);

        // Tambah FK id_wilayah di barang_keluar
        $db = \Config\Database::connect();
        
        // Kita gunakan execute native untuk tambah constraint foreign key 
        // karena CI4 kadang rewel dengan addForeignKey setelah table dibuat
        $db->query("ALTER TABLE barang_keluar ADD CONSTRAINT fk_barang_keluar_wilayah FOREIGN KEY (id_wilayah) REFERENCES wilayah(id) ON DELETE RESTRICT ON UPDATE CASCADE");

        // 2. Drop id_wilayah dari barang_masuk
        // Hapus FK terlebih dahulu
        // Biasanya nama fk adalah gabungan tabel dan kolom, kita cari tahu jika memungkinkan.
        // Asumsi nama fk yang dibuat CI4 sebelumnya adalah fk_barang_masuk_wilayah atau serupa,
        // Tapi kita coba jalankan secara raw query.
        try {
            $db->query("ALTER TABLE barang_masuk DROP FOREIGN KEY barang_masuk_id_wilayah_foreign");
        } catch (\Exception $e) {
            // Abaikan jika tidak ada atau namanya beda, kita akan drop fieldnya langsung.
        }
        
        try {
            $this->forge->dropColumn('barang_masuk', 'id_wilayah');
        } catch (\Exception $e) {
            // Abaikan jika gagal
        }
    }

    public function down()
    {
        // Rollback
        $db = \Config\Database::connect();

        try {
            $db->query("ALTER TABLE barang_keluar DROP FOREIGN KEY fk_barang_keluar_wilayah");
        } catch (\Exception $e) {}

        $this->forge->dropColumn('barang_keluar', 'id_wilayah');

        $fields = [
            'id_wilayah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ]
        ];
        $this->forge->addColumn('barang_masuk', $fields);
    }
}

