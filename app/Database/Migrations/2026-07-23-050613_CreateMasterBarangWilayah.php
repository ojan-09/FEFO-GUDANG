<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterBarangWilayah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Aktif', 'Nonaktif'],
                'default'    => 'Aktif',
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
        $this->forge->createTable('master_barang_wilayah', true);

        // We also need to truncate existing transactions since they point to old IDs
        $db = \Config\Database::connect();
        
        // Truncate tables to ensure a clean slate for the new architecture
        try {
            $db->query("SET FOREIGN_KEY_CHECKS=0");
            $db->table('barang_masuk_wilayah')->truncate();
            $db->table('barang_keluar_wilayah')->truncate();
            $db->table('stok_gudang_wilayah')->truncate();
            $db->query("SET FOREIGN_KEY_CHECKS=1");
        } catch (\Exception $e) {
            // Ignore if tables don't exist yet
        }
    }

    public function down()
    {
        $this->forge->dropTable('master_barang_wilayah', true);
    }
}
