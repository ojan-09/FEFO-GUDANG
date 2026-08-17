<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReadExcelDetailSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) {
            echo "No xlsx file found in .agents/\n";
            return;
        }

        $filePath = $files[0];
        echo "Reading file: " . basename($filePath) . "\n\n";

        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();
        echo "Sheets in file: " . implode(", ", $sheetNames) . "\n\n";

        foreach ($sheetNames as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $data = $sheet->toArray();
            echo "=== Sheet: $sheetName (Total Rows: " . count($data) . ") ===\n";
            
            $validRows = 0;
            $sampleRows = [];

            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];
                $nonEmpty = array_filter($row, function($val) {
                    return $val !== null && trim((string)$val) !== '';
                });

                if (!empty($nonEmpty)) {
                    $validRows++;
                    if (count($sampleRows) < 10) {
                        $sampleRows[] = [
                            'row_idx' => $i + 1,
                            'cols' => array_slice($row, 0, 12)
                        ];
                    }
                }
            }

            echo "Non-empty rows: $validRows\n";
            echo "Sample Rows Data:\n";
            print_r($sampleRows);
            echo "\n-----------------------------------------\n";
        }
    }
}
