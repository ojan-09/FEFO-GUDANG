<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FindExact29RowSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        $sheet = $spreadsheet->getSheetByName('STO 29 Juli 2026') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $col0 = trim((string)($row[0] ?? ''));
            $col2 = trim((string)($row[2] ?? ''));
            if (!empty($col0) || !empty($col2)) {
                echo "Row " . ($i+1) . " | Col0: $col0 | Col2: $col2\n";
            }
        }
    }
}
