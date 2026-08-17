<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckJuly7MissingItemsInDbSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $itemsToCheck = [
            'Gula Rose Brand' => 1000,
            'Selai Coklat' => 300,
            'Tropicana Sweetener Diabtx' => 100,
            'Tropicana Slim (Classic)' => 125
        ];

        echo "=== CHECKING JULY 7 MISSING ITEMS IN DB ===\n";
        foreach ($itemsToCheck as $nama => $gram) {
            $found = $db->table('batch')
                ->groupStart()
                    ->like('nama_barang', $nama)
                ->groupEnd()
                ->where('berat_per_satuan', $gram)
                ->get()->getResultArray();

            if (!empty($found)) {
                echo "[ADA] $nama ($gram g) -> Found " . count($found) . " batch(es) in DB\n";
            } else {
                echo "[BELUM ADA] $nama ($gram g) -> Not in DB!\n";
            }
        }
    }
}
