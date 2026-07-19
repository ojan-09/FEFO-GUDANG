<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PenyesuaianStok extends Migration
{
    public function up()
    {
        // 1. Tabel penyesuaian_stok
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_penyesuaian' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jenis_penyesuaian' => [
                'type'       => 'ENUM',
                'constraint' => ['Barang Rusak', 'Barang Hilang', 'Barang Kedaluwarsa', 'Koreksi Positif', 'Koreksi Negatif', 'Hasil Stock Opname'],
            ],
            'keterangan' => [
                'type' => 'TEXT',
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('penyesuaian_stok');

        // 2. Tabel detail_penyesuaian_stok
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_penyesuaian' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_barang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_batch' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'stok_sebelum' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'stok_sesudah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('detail_penyesuaian_stok');
    }

    public function down()
    {
        $this->forge->dropTable('detail_penyesuaian_stok', true);
        $this->forge->dropTable('penyesuaian_stok', true);
    }
}
