<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Search4ItemsAllSheetsSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;

        $spreadsheet = IOFactory::load($files[0]);

        $targets = [
            'gula rose brand',
            'selai coklat',
            'diabtx',
            'classic'
        ];

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $data = $sheet->toArray();

            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];
                $nama = trim((string)($row[5] ?? ''));

                foreach ($targets as $t) {
                    if (stristr($nama, $t) !== false) {
                        echo "Sheet: $sheetName | Row " . ($i+1) . " | Nama: $nama | Gram: " . ($row[8]??'') . " | Qty: " . ($row[6]??'') . " | Satuan: " . ($row[7]??'') . " | Donatur: " . ($row[2]??'') . "\n";
                    }
                }
            }
        }
    }
}
