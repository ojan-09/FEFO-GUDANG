<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckDuplicatesAndLmenSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        
        echo "=== CHECKING L-MEN LOSE WEIGHT IN EXCEL ===\n";
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $data = $sheet->toArray();
            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];
                $nama = trim((string)($row[5] ?? ''));
                if (stristr($nama, 'lmen') !== false || stristr($nama, 'l-men') !== false || stristr($nama, 'lose weight') !== false) {
                    echo "Sheet: $sheetName | Row " . ($i+1) . " | Nama: $nama | Qty: " . ($row[6]??'') . " | Gram: " . ($row[8]??'') . " | Catatan/Col11: " . ($row[11]??'') . "\n";
                }
            }
        }

        echo "\n=== CHECKING OVERLAPS BETWEEN SHEETS ===\n";
        $sheet29 = [];
        $sheetFormat = [];

        $s1 = $spreadsheet->getSheetByName('STO 29 Juli 2026');
        if ($s1) {
            foreach ($s1->toArray() as $i => $row) {
                $nama = trim((string)($row[5] ?? ''));
                $gram = (float)($row[8] ?? 0);
                if (!empty($nama) && $nama !== 'Nama Barang') {
                    $sheet29[] = strtolower($nama) . '_' . $gram;
                }
            }
        }

        $s2 = $spreadsheet->getSheetByName('Format');
        if ($s2) {
            foreach ($s2->toArray() as $i => $row) {
                $nama = trim((string)($row[5] ?? ''));
                $gram = (float)($row[8] ?? 0);
                if (!empty($nama) && $nama !== 'Nama Barang') {
                    $sheetFormat[] = strtolower($nama) . '_' . $gram;
                }
            }
        }

        $overlap = array_intersect($sheet29, $sheetFormat);
        echo "Items in both STO 29 Juli AND Format sheet (" . count($overlap) . " items):\n";
        print_r(array_values(array_unique($overlap)));
    }
}
