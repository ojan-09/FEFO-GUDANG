<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesToTables extends Migration
{
    public function up()
    {
        // Indexes for 'barang' table
        $this->db->query("ALTER TABLE `barang` ADD INDEX `idx_kategori` (`id_kategori`)");
        $this->db->query("ALTER TABLE `barang` ADD INDEX `idx_status` (`status`)");
        
        // Indexes for 'batch' table
        $this->db->query("ALTER TABLE `batch` ADD INDEX `idx_id_barang` (`id_barang`)");
        $this->db->query("ALTER TABLE `batch` ADD INDEX `idx_batch_status` (`status`)");
        $this->db->query("ALTER TABLE `batch` ADD INDEX `idx_tgl_kedaluwarsa` (`tanggal_kedaluwarsa`)");
        
        // Indexes for 'barang_masuk' table
        $this->db->query("ALTER TABLE `barang_masuk` ADD INDEX `idx_tgl_masuk` (`tanggal_masuk`)");
        
        // Indexes for 'barang_keluar' table
        $this->db->query("ALTER TABLE `barang_keluar` ADD INDEX `idx_tgl_keluar` (`tanggal_keluar`)");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `barang` DROP INDEX `idx_kategori`");
        $this->db->query("ALTER TABLE `barang` DROP INDEX `idx_status`");
        
        $this->db->query("ALTER TABLE `batch` DROP INDEX `idx_id_barang`");
        $this->db->query("ALTER TABLE `batch` DROP INDEX `idx_batch_status`");
        $this->db->query("ALTER TABLE `batch` DROP INDEX `idx_tgl_kedaluwarsa`");
        
        $this->db->query("ALTER TABLE `barang_masuk` DROP INDEX `idx_tgl_masuk`");
        
        $this->db->query("ALTER TABLE `barang_keluar` DROP INDEX `idx_tgl_keluar`");
    }
}
