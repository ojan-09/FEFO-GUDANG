<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AnalyzeExcelDeepSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            echo "=========================================\n";
            echo "SHEET: $sheetName\n";
            echo "=========================================\n";
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $data = $sheet->toArray();
            
            $sectionHeader = "Header Awal";
            
            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];
                $col0 = trim((string)($row[0] ?? ''));
                $col2 = trim((string)($row[2] ?? ''));
                $namaBarang = trim((string)($row[5] ?? ''));
                
                // Detect section headers like "STO 15 Oktober", "STO 30 Juni", etc.
                if (!empty($col0) && (stristr($col0, 'STO') !== false || stristr($col2, 'STO') !== false)) {
                    $sectionHeader = "[$col0 | $col2]";
                    echo "\n--- SECTION FOUND at row " . ($i + 1) . ": $sectionHeader ---\n";
                    continue;
                }
                
                if (!empty($namaBarang) && $namaBarang !== 'Nama Barang') {
                    echo sprintf(
                        "Row %3d | Sec: %-25s | Donatur: %-15s | Kat: %-15s | Exp: %-12s | Barang: %-30s | Qty: %-5s | Unit: %-8s | Weight: %-6s\n",
                        $i + 1,
                        substr($sectionHeader, 0, 25),
                        substr($row[2] ?? '', 0, 15),
                        substr($row[3] ?? '', 0, 15),
                        $row[4] ?? '',
                        substr($namaBarang, 0, 30),
                        $row[6] ?? '',
                        $row[7] ?? '',
                        $row[8] ?? ''
                    );
                }
            }
        }
    }
}
