<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddLaporanWilayahIndexes extends Migration {
    public function up() {
        $db = \Config\Database::connect();
        $indexes = [
            ["barang_masuk_wilayah", "idx_bmw_gudang_del_tgl", ["id_gudang", "deleted_at", "tanggal"]],
            ["barang_masuk_wilayah", "idx_bmw_del_tgl", ["deleted_at", "tanggal"]],
            ["barang_keluar_wilayah", "idx_bkw_gudang_del_tgl", ["id_gudang", "deleted_at", "tanggal"]],
            ["barang_keluar_wilayah", "idx_bkw_del_tgl", ["deleted_at", "tanggal"]],
            ["detail_barang_masuk_wilayah", "idx_dbmw_masuk_barang", ["id_masuk", "id_barang"]],
            ["detail_barang_keluar_wilayah", "idx_dbkw_keluar_barang", ["id_keluar", "id_barang"]],
            ["stok_gudang_wilayah", "idx_sgw_gudang_barang", ["id_gudang", "id_barang"]]
        ];
        foreach ($indexes as $idx) {
            $table = $idx[0];
            $indexName = $idx[1];
            $columns = $idx[2];
            
            $exists = false;
            if ($db->tableExists($table)) {
                $existingIndexes = $db->getIndexData($table);
                foreach ($existingIndexes as $existingIndex) {
                    if ($existingIndex->name === $indexName) {
                        $exists = true;
                        break;
                    }
                }
            }

            if (!$exists) {
                $colsString = "`" . implode("`, `", $columns) . "`";
                $db->query("ALTER TABLE `${table}` ADD INDEX `${indexName}` (${colsString})");
            }
        }
    }
    public function down() {
        $db = \Config\Database::connect();
        $indexes = [
            ["barang_masuk_wilayah", "idx_bmw_gudang_del_tgl"],
            ["barang_masuk_wilayah", "idx_bmw_del_tgl"],
            ["barang_keluar_wilayah", "idx_bkw_gudang_del_tgl"],
            ["barang_keluar_wilayah", "idx_bkw_del_tgl"],
            ["detail_barang_masuk_wilayah", "idx_dbmw_masuk_barang"],
            ["detail_barang_keluar_wilayah", "idx_dbkw_keluar_barang"],
            ["stok_gudang_wilayah", "idx_sgw_gudang_barang"]
        ];
        foreach ($indexes as $idx) {
            $table = $idx[0];
            $indexName = $idx[1];
            
            $exists = false;
            if ($db->tableExists($table)) {
                $existingIndexes = $db->getIndexData($table);
                foreach ($existingIndexes as $existingIndex) {
                    if ($existingIndex->name === $indexName) {
                        $exists = true;
                        break;
                    }
                }
            }

            if ($exists) {
                $db->query("ALTER TABLE `${table}` DROP INDEX `${indexName}`");
            }
        }
    }
}