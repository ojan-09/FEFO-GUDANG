<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        $indexes = [
            ['detail_penyesuaian_stok', 'idx_dps_penyesuaian', 'id_penyesuaian'],
            ['detail_penyesuaian_stok', 'idx_dps_barang', 'id_barang'],
            ['detail_penyesuaian_stok', 'idx_dps_batch', 'id_batch'],
            ['penyesuaian_stok', 'idx_ps_tanggal', 'tanggal'],
            ['batch', 'idx_batch_barang', 'id_barang'],
            ['batch', 'idx_batch_kedaluwarsa', 'tanggal_kedaluwarsa'],
            ['detail_barang_keluar', 'idx_dbk_batch', 'id_batch']
        ];

        foreach ($indexes as $idx) {
            $table = $idx[0];
            $indexName = $idx[1];
            $column = $idx[2];
            
            $exists = $db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = '{$indexName}'")->getRow();
            if (!$exists) {
                $db->query("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` (`{$column}`)");
            }
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        $indexes = [
            ['detail_penyesuaian_stok', 'idx_dps_penyesuaian'],
            ['detail_penyesuaian_stok', 'idx_dps_barang'],
            ['detail_penyesuaian_stok', 'idx_dps_batch'],
            ['penyesuaian_stok', 'idx_ps_tanggal'],
            ['batch', 'idx_batch_barang'],
            ['batch', 'idx_batch_kedaluwarsa'],
            ['detail_barang_keluar', 'idx_dbk_batch']
        ];

        foreach ($indexes as $idx) {
            $table = $idx[0];
            $indexName = $idx[1];
            
            $exists = $db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = '{$indexName}'")->getRow();
            if ($exists) {
                $db->query("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
            }
        }
    }
}
