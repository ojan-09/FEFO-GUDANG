<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckKecapExcelSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;

        $spreadsheet = IOFactory::load($files[0]);

        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $data = $sheet->toArray();

            for ($i = 0; $i < count($data); $i++) {
                $row = $data[$i];
                $nama = trim((string)($row[5] ?? ''));
                if (stristr($nama, 'kecap') !== false) {
                    echo "Sheet: $sheetName | Row " . ($i+1) . " | Nama: $nama | Exp: " . ($row[4]??'') . " | Qty: " . ($row[6]??'') . " | Satuan: " . ($row[7]??'') . " | Gram: " . ($row[8]??'') . "\n";
                }
            }
        }
    }
}
