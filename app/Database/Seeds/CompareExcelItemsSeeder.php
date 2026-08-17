<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompareExcelItemsSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        $sheet = $spreadsheet->getSheetByName('UPDATE STOK') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        
        $itemsJuly7 = [];
        $itemsJuly26 = [];
        
        $currentSection = '7';
        
        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $col0 = trim((string)($row[0] ?? ''));
            $col2 = trim((string)($row[2] ?? ''));
            $namaBarang = trim((string)($row[5] ?? ''));
            
            if (stristr($col0, '26') !== false || stristr($col2, '26') !== false || stristr($col0, '29') !== false || stristr($col2, '29') !== false) {
                $currentSection = '26';
            }
            
            if (!empty($namaBarang) && $namaBarang !== 'Nama Barang' && $namaBarang !== 'Mengetahui,') {
                $itemInfo = [
                    'nama' => $namaBarang,
                    'kategori' => trim((string)($row[3] ?? '-')),
                    'expired' => trim((string)($row[4] ?? '-')),
                    'qty' => (float)($row[6] ?? 0),
                    'satuan' => trim((string)($row[7] ?? '-')),
                    'berat' => (float)($row[8] ?? 0),
                    'donatur' => trim((string)($row[2] ?? '-'))
                ];
                
                $key = strtolower($namaBarang);
                
                if ($currentSection === '7') {
                    $itemsJuly7[$key] = $itemInfo;
                } else {
                    $itemsJuly26[$key] = $itemInfo;
                }
            }
        }
        
        echo "=== ALL UNIQUE ITEMS IN JULY 26 / UPDATE STOK ===\n\n";
        $newCount = 0;
        foreach ($itemsJuly26 as $key => $item) {
            $isNew = !isset($itemsJuly7[$key]);
            $statusStr = $isNew ? "[BARANG BARU TANGGAL 26]" : "[SUDAH ADA DI TGL 7]";
            if ($isNew) $newCount++;
            
            echo sprintf(
                "%-25s | Nama: %-32s | Kat: %-15s | Exp: %-12s | Qty: %-5s | Unit: %-8s | Berat: %-5sg\n",
                $statusStr,
                $item['nama'],
                $item['kategori'],
                $item['expired'],
                $item['qty'],
                $item['satuan'],
                $item['berat']
            );
        }
        
        echo "\nTotal barang di tanggal 26: " . count($itemsJuly26) . "\n";
        echo "Total barang BARU (yang tidak ada di tanggal 7): " . $newCount . "\n";
    }
}
