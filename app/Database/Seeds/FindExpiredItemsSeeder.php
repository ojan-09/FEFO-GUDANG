<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FindExpiredItemsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        
        // 1. Expired Items (tanggal_kedaluwarsa <= today)
        $expired = $db->table('batch')
            ->select('batch.*, donatur.nama_donatur')
            ->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left')
            ->where('batch.stok_saat_ini >', 0)
            ->where('batch.tanggal_kedaluwarsa <=', $today)
            ->orderBy('batch.tanggal_kedaluwarsa', 'ASC')
            ->get()->getResultArray();

        // 2. Near Expired Items (next 30 days)
        $nextMonth = date('Y-m-d', strtotime('+30 days'));
        $nearExpired = $db->table('batch')
            ->select('batch.*, donatur.nama_donatur')
            ->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left')
            ->where('batch.stok_saat_ini >', 0)
            ->where('batch.tanggal_kedaluwarsa >', $today)
            ->where('batch.tanggal_kedaluwarsa <=', $nextMonth)
            ->orderBy('batch.tanggal_kedaluwarsa', 'ASC')
            ->get()->getResultArray();

        echo "=== EXPIRED ITEMS (Sudah Kedaluwarsa <= $today) ===\n";
        echo "Total: " . count($expired) . " batch\n";
        foreach ($expired as $item) {
            echo sprintf(
                "ID: %-5s | Exp: %-12s | Nama: %-30s | Stok: %-5s %-6s | Donatur: %-15s\n",
                $item['id'],
                $item['tanggal_kedaluwarsa'],
                $item['nama_barang'],
                $item['stok_saat_ini'],
                $item['satuan'],
                $item['nama_donatur'] ?? '-'
            );
        }

        echo "\n=== NEAR EXPIRED ITEMS (Mendekati Kedaluwarsa 30 Hari Ke Depan) ===\n";
        echo "Total: " . count($nearExpired) . " batch\n";
        foreach ($nearExpired as $item) {
            echo sprintf(
                "ID: %-5s | Exp: %-12s | Nama: %-30s | Stok: %-5s %-6s | Donatur: %-15s\n",
                $item['id'],
                $item['tanggal_kedaluwarsa'],
                $item['nama_barang'],
                $item['stok_saat_ini'],
                $item['satuan'],
                $item['nama_donatur'] ?? '-'
            );
        }
    }
}
