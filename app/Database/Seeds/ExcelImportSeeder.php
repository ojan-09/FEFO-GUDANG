<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImportSeeder extends Seeder
{
    public function run()
    {
        $filePath = ROOTPATH . '.agents/JULI7-29.xlsx';
        
        if (!file_exists($filePath)) {
            echo "File not found: " . $filePath . "\n";
            return;
        }
        
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        
        echo "Total Rows: " . count($data) . "\n\n";
        
        // Print first 5 rows to see structure
        foreach (array_slice($data, 0, 5) as $rowIndex => $row) {
            echo "--- ROW " . ($rowIndex + 1) . " ---\n";
            foreach ($row as $colIndex => $cellValue) {
                echo "Col $colIndex: " . $cellValue . "\n";
            }
        }
    }
}
