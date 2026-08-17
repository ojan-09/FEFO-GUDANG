<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompareJuly7Vs29FullSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;

        $spreadsheet = IOFactory::load($files[0]);

        $sheet7 = $spreadsheet->getSheetByName('STO 7 Juli 2026');
        $sheet29 = $spreadsheet->getSheetByName('STO 29 Juli 2026');

        $data7 = $sheet7 ? $sheet7->toArray() : [];
        $data29 = $sheet29 ? $sheet29->toArray() : [];

        $items7 = [];
        foreach ($data7 as $i => $row) {
            $nama = trim((string)($row[5] ?? ''));
            $gram = (float)($row[8] ?? 0);
            $qty = (float)($row[6] ?? 0);
            $satuan = trim((string)($row[7] ?? 'Pcs'));
            $exp = trim((string)($row[4] ?? '-'));

            if (!empty($nama) && $nama !== 'Nama Barang' && $qty > 0) {
                $key = strtolower($nama) . '_' . $gram . '_' . strtolower($satuan);
                $items7[$key] = [
                    'nama' => $nama,
                    'gram' => $gram,
                    'satuan' => $satuan,
                    'qty' => $qty,
                    'exp' => $exp
                ];
            }
        }

        $items29 = [];
        foreach ($data29 as $i => $row) {
            $nama = trim((string)($row[5] ?? ''));
            $gram = (float)($row[8] ?? 0);
            $qty = (float)($row[6] ?? 0);
            $satuan = trim((string)($row[7] ?? 'Pcs'));
            $exp = trim((string)($row[4] ?? '-'));

            if (!empty($nama) && $nama !== 'Nama Barang' && $nama !== 'Mengetahui,' && $qty > 0) {
                $key = strtolower($nama) . '_' . $gram . '_' . strtolower($satuan);
                $items29[$key] = [
                    'nama' => $nama,
                    'gram' => $gram,
                    'satuan' => $satuan,
                    'qty' => $qty,
                    'exp' => $exp
                ];
            }
        }

        echo "=== DIFFERENCES BETWEEN 7 JULI VS 29 JULI ===\n\n";

        // 1. Items in 7 July but NOT in 29 July
        echo "--- 1. BARANG DI 7 JULI TAPI TIDAK ADA DI 29 JULI ---\n";
        foreach ($items7 as $key => $item) {
            if (!isset($items29[$key])) {
                echo sprintf("Nama: %-30s | Gram: %-6s | Satuan: %-8s | Qty: %-5s | Exp: %-12s\n", $item['nama'], $item['gram'], $item['satuan'], $item['qty'], $item['exp']);
            }
        }

        // 2. Items in 29 July but NOT in 7 July
        echo "\n--- 2. BARANG DI 29 JULI TAPI TIDAK ADA DI 7 JULI (BARANG BARU) ---\n";
        foreach ($items29 as $key => $item) {
            if (!isset($items7[$key])) {
                echo sprintf("Nama: %-30s | Gram: %-6s | Satuan: %-8s | Qty: %-5s | Exp: %-12s\n", $item['nama'], $item['gram'], $item['satuan'], $item['qty'], $item['exp']);
            }
        }

        // 3. Items in BOTH but Qty/Exp changed
        echo "\n--- 3. BARANG ADA DI KEDUA TANGGAL TAPI QTY ATAU EXP BERBEDA ---\n";
        foreach ($items7 as $key => $item7) {
            if (isset($items29[$key])) {
                $item29 = $items29[$key];
                if ($item7['qty'] != $item29['qty'] || $item7['exp'] != $item29['exp']) {
                    echo sprintf(
                        "Nama: %-30s | Gram: %-5s | Qty 7 Jul: %-4s -> Qty 29 Jul: %-4s | Exp 7 Jul: %-11s -> Exp 29 Jul: %-11s\n",
                        $item7['nama'],
                        $item7['gram'],
                        $item7['qty'],
                        $item29['qty'],
                        $item7['exp'],
                        $item29['exp']
                    );
                }
            }
        }
    }
}
