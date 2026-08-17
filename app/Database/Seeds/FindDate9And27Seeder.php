<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FindDate9And27Seeder extends Seeder
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

            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];
                foreach ($row as $colIdx => $cell) {
                    $cellStr = trim((string)$cell);
                    if (stristr($cellStr, '9') !== false || stristr($cellStr, '27') !== false) {
                        if (stristr($cellStr, 'STO') !== false || stristr($cellStr, 'Juli') !== false || stristr($cellStr, 'Juni') !== false || stristr($cellStr, 'Agustus') !== false || stristr($cellStr, 'Week') !== false) {
                            echo "Row " . ($i+1) . " Col $colIdx: $cellStr\n";
                        }
                    }
                }
            }
        }
    }
}
