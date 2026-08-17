<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckUserComparisonSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        
        echo "=======================================================\n";
        echo "CHECKING ALL SHEETS FOR USER'S LIST (NAMA + GRAM)\n";
        echo "=======================================================\n\n";

        $userListToSearch = [
            "Selai Strawberry (300g)",
            "Teh (50g)",
            "Teh (120g)",
            "Nutrisari (200g)",
            "Nutrisari Jeruk (250g)",
            "Nutrisari Jeruk (500g)",
            "Nutrisari Jeruk Maroco (110g)",
            "Nutrisari Jeruk Nipis (110g)",
            "Tropicana Slim Sweetener Gula Buah (125g)",
            "Tropicana Slim Sweetener Gula Aren (100g)",
            "Tropicana Sweetener Lemongrass (100g)",
            "Olive Oil (500g)",
            "Tropicana Slim (Classic) (500g)",
            "L-Men Cokelat (30g)",
            "Cafe Latte (140g)",
            "Hokaido Chesse (100g)",
            "Soy Latte (150g)",
            "Coffe Drip (48g)",
            "Hilo Original (360g)",
            "Milo (22g)",
            "Gula Jawa (350g)",
            "Gula (1000g)",
            "Colagen (200g)",
            "Santan Bubuk (100g)"
        ];

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $data = $sheet->toArray();
            echo "--- SHEET: $sheetName (Total Rows: " . count($data) . ") ---\n";
            
            $foundInSheet = [];
            
            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];
                $nama = trim((string)($row[5] ?? ''));
                $gram = (float)($row[8] ?? 0);
                $qty = (float)($row[6] ?? 0);
                $satuan = trim((string)($row[7] ?? ''));
                $exp = trim((string)($row[4] ?? ''));
                
                if (!empty($nama) && $nama !== 'Nama Barang') {
                    $itemKey = strtolower($nama) . '_' . $gram;
                    $foundInSheet[] = [
                        'row' => $i + 1,
                        'nama' => $nama,
                        'gram' => $gram,
                        'satuan' => $satuan,
                        'qty' => $qty,
                        'exp' => $exp
                    ];
                }
            }

            echo "Found " . count($foundInSheet) . " items in $sheetName.\n";
            
            // Print items matching user list
            foreach ($foundInSheet as $item) {
                foreach ($userListToSearch as $target) {
                    $targetClean = strtolower(explode(' (', $target)[0]);
                    if (stristr(strtolower($item['nama']), $targetClean) !== false) {
                        echo sprintf(
                            "Row %3d | Nama: %-35s | Gram: %-6s | Satuan: %-8s | Qty: %-5s | Exp: %-10s\n",
                            $item['row'],
                            $item['nama'],
                            $item['gram'],
                            $item['satuan'],
                            $item['qty'],
                            $item['exp']
                        );
                        break;
                    }
                }
            }
            echo "\n-------------------------------------------------------\n\n";
        }
    }
}
