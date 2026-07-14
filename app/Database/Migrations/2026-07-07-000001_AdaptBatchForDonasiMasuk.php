<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdaptBatchForDonasiMasuk extends Migration
{
    public function up()
    {
        $this->dropForeignKeyIfExists('batch', 'id_barang');

        $this->forge->modifyColumn('batch', [
            'id_barang' => [
                'name'       => 'id_barang',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        $this->forge->addColumn('batch', [
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'nomor_batch',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'nama_barang',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'stok_saat_ini',
            ],
            'berat_per_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'after'      => 'satuan',
            ],
            'satuan_berat' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'berat_per_satuan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('batch', ['nama_barang', 'kategori', 'satuan', 'berat_per_satuan', 'satuan_berat']);

        $this->forge->modifyColumn('batch', [
            'id_barang' => [
                'name'       => 'id_barang',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

        $this->forge->addForeignKey('id_barang', 'barang', 'id', 'CASCADE', 'RESTRICT', 'batch_id_barang_foreign');
        $this->forge->processIndexes('batch');
    }

    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        if ($this->db->DBDriver !== 'MySQLi') {
            return;
        }

        $database = $this->db->database;
        $result = $this->db->query(
            "SELECT CONSTRAINT_NAME
             FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$database, $table, $column]
        )->getRowArray();

        if ($result && !empty($result['CONSTRAINT_NAME'])) {
            $constraint = str_replace('`', '``', $result['CONSTRAINT_NAME']);
            $this->db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
        }
    }
}

