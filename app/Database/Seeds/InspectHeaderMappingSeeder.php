<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InspectHeaderMappingSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;
        
        $spreadsheet = IOFactory::load($files[0]);
        $sheet = $spreadsheet->getSheetByName('STO 29 Juli 2026') ?? $spreadsheet->getSheetByName('UPDATE STOK') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        
        echo "========================================================\n";
        echo "EXCEL HEADER & MAPPING INSPECTION\n";
        echo "========================================================\n\n";

        // Find header row (usually contains 'Nama Barang' or 'Status')
        $headerRowIdx = -1;
        foreach ($data as $idx => $row) {
            foreach ($row as $cell) {
                if (stristr((string)$cell, 'Nama Barang') !== false) {
                    $headerRowIdx = $idx;
                    break 2;
                }
            }
        }
        
        if ($headerRowIdx === -1) {
            echo "Header row not found!\n";
            return;
        }
        
        $headers = $data[$headerRowIdx];
        $sampleData = $data[$headerRowIdx + 1] ?? [];
        
        echo "Header Row Index: " . ($headerRowIdx + 1) . "\n\n";
        
        foreach ($headers as $colIdx => $headerName) {
            $sampleVal = $sampleData[$colIdx] ?? '';
            echo sprintf(
                "Col %2d | Header Excel: %-30s | Contoh Isi: %-25s\n",
                $colIdx,
                trim((string)$headerName),
                trim((string)$sampleVal)
            );
        }
    }
}
