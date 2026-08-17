<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InspectJuly7RowsSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;

        $spreadsheet = IOFactory::load($files[0]);
        $sheet = $spreadsheet->getSheetByName('STO 29 Juli 2026') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        echo "=== SCANNING JULY 7 SECTION (ROWS 1-38) ===\n";
        for ($i = 2; $i < 38; $i++) {
            $row = $data[$i];
            $nama = trim((string)($row[5] ?? ''));
            $gram = $row[8] ?? 0;
            $qty = $row[6] ?? 0;
            $satuan = $row[7] ?? '';
            $exp = $row[4] ?? '';

            if (empty($nama)) continue;

            echo "Row " . ($i+1) . " | Nama: $nama | Gram: $gram | Qty: $qty | Satuan: $satuan | Exp: $exp\n";
        }
    }
}
