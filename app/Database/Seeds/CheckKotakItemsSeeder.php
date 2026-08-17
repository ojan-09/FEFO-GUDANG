<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CheckKotakItemsSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $items = $db->query("
            SELECT b.id, b.nama_barang, b.satuan, b.berat_per_satuan, b.satuan_berat, k.nama_kategori 
            FROM barang b
            LEFT JOIN kategori k ON k.id = b.id_kategori
            WHERE LOWER(b.satuan) = 'kotak'
            ORDER BY b.nama_barang ASC
        ")->getResultArray();

        echo "=== ITEMS WITH SATUAN 'KOTAK' IN DATABASE ===\n";
        echo "Total: " . count($items) . " items\n\n";

        foreach ($items as $item) {
            echo sprintf(
                "ID: %-4s | Nama: %-38s | Satuan: %-8s | Berat: %-8s %-5s | Kat: %-15s\n",
                $item['id'],
                $item['nama_barang'],
                $item['satuan'],
                $item['berat_per_satuan'],
                $item['satuan_berat'],
                $item['nama_kategori'] ?? '-'
            );
        }
    }
}
