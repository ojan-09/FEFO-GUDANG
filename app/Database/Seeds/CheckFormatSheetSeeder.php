<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckFormatSheetSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        $sheet = $spreadsheet->getSheetByName('Format');
        if (!$sheet) return;
        
        $data = $sheet->toArray();
        $validItems = 0;
        
        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $namaBarang = trim((string)($row[5] ?? ''));
            $jumlah = (float)($row[6] ?? 0);
            
            if (!empty($namaBarang) && $namaBarang !== 'Nama Barang' && $jumlah > 0) {
                $validItems++;
                if ($validItems <= 5) {
                    echo "Row " . ($i+1) . ": Donatur=" . ($row[2]??'') . ", Cat=" . ($row[3]??'') . ", Exp=" . ($row[4]??'') . ", Barang=" . $namaBarang . ", Qty=" . $jumlah . "\n";
                }
            }
        }
        
        echo "Found $validItems valid stock items in sheet 'Format'\n";
    }
}
