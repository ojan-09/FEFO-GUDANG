<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GudangWilayah extends Migration
{
    public function up()
    {
        // 1. Add id_gudang_wilayah to users
        $fields = [
            'id_gudang_wilayah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'active'
            ],
        ];
        $this->forge->addColumn('users', $fields);

        // 2. Table master_gudang_wilayah
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'provinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'kota' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'pic' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
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
        $this->forge->createTable('master_gudang_wilayah', true);

        // 3. Table stok_gudang_wilayah
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_gudang' => [
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
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        // Foreign keys can be omitted for simplicity or added later, but adding indices is good
        $this->forge->addKey(['id_gudang', 'id_barang']);
        $this->forge->createTable('stok_gudang_wilayah', true);

        // 4. Table barang_masuk_wilayah
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'id_gudang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_barang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'donatur' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_gudang');
        $this->forge->createTable('barang_masuk_wilayah', true);

        // 5. Table barang_keluar_wilayah
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'id_gudang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_barang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tujuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jumlah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_gudang');
        $this->forge->createTable('barang_keluar_wilayah', true);
    }

    public function down()
    {
        $this->forge->dropTable('barang_keluar_wilayah', true);
        $this->forge->dropTable('barang_masuk_wilayah', true);
        $this->forge->dropTable('stok_gudang_wilayah', true);
        $this->forge->dropTable('master_gudang_wilayah', true);
        
        $this->forge->dropColumn('users', 'id_gudang_wilayah');
    }
}
