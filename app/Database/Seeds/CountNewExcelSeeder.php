<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CountNewExcelSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        $filePath = $files[0];
        
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName('UPDATE STOK') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        
        $validItems = 0;
        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];
            $namaBarang = trim((string)($row[5] ?? ''));
            $jumlah = (float)($row[6] ?? 0);
            
            if (!empty($namaBarang) && $namaBarang !== 'Nama Barang' && $jumlah > 0) {
                $validItems++;
            }
        }
        
        echo "Found $validItems valid stock items in sheet 'UPDATE STOK'\n";
    }
}
