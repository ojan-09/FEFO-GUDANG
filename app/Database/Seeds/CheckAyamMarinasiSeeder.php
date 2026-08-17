<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckAyamMarinasiSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        echo "=== CHECKING AYAM MARINASI IN DB ===\n";
        $dbAyam = $db->query("SELECT * FROM barang WHERE nama_barang LIKE '%ayam%'")->getResultArray();
        print_r($dbAyam);
        
        $dbBatch = $db->query("SELECT * FROM batch WHERE nama_barang LIKE '%ayam%'")->getResultArray();
        print_r($dbBatch);
        
        echo "\n=== CHECKING AYAM MARINASI IN EXCEL ===\n";
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            foreach ($sheet->toArray() as $idx => $row) {
                $nama = trim((string)($row[5] ?? ''));
                if (stristr($nama, 'ayam') !== false) {
                    echo "Sheet: $sheetName | Row " . ($idx+1) . " | Donatur: " . ($row[2]??'') . " | Nama: $nama | Qty(Col6): " . ($row[6]??'') . " | Unit(Col7): " . ($row[7]??'') . " | Gram(Col8): " . ($row[8]??'') . " | Kg(Col9): " . ($row[9]??'') . "\n";
                }
            }
        }
    }
}
