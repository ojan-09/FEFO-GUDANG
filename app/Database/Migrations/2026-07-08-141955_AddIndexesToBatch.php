<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesToBatch extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE `batch` ADD INDEX `idx_tanggal_kedaluwarsa` (`tanggal_kedaluwarsa`)');
        $this->db->query('ALTER TABLE `batch` ADD INDEX `idx_stok_saat_ini` (`stok_saat_ini`)');
        $this->db->query('ALTER TABLE `batch` ADD INDEX `idx_kategori` (`kategori`)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `batch` DROP INDEX `idx_tanggal_kedaluwarsa`');
        $this->db->query('ALTER TABLE `batch` DROP INDEX `idx_stok_saat_ini`');
        $this->db->query('ALTER TABLE `batch` DROP INDEX `idx_kategori`');
    }
}

