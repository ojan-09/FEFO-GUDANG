<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SeparateExpiredByDateSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) return;

        $filePath = $files[0];
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName('STO 29 Juli 2026') ?? $spreadsheet->getSheetByName('UPDATE STOK') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $today = '2026-08-05';
        $july7Expired = [];
        $july29Expired = [];

        $currentSection = '7';

        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $col0 = trim((string)($row[0] ?? ''));
            $col2 = trim((string)($row[2] ?? ''));
            $namaBarang = trim((string)($row[5] ?? ''));

            if (stristr($col0, '29') !== false || stristr($col2, '29') !== false || stristr($col0, '26') !== false || stristr($col2, '26') !== false) {
                $currentSection = '29';
            }

            if (!empty($namaBarang) && $namaBarang !== 'Nama Barang' && $namaBarang !== 'Mengetahui,') {
                $rawExp = trim((string)($row[4] ?? ''));
                $expDate = $this->parseExpDate($rawExp);
                $jumlah = (float)($row[6] ?? 0);

                if ($jumlah > 0 && !empty($expDate) && $expDate <= $today) {
                    $item = [
                        'row' => $i + 1,
                        'donatur' => trim((string)($row[2] ?? '-')),
                        'nama' => $namaBarang,
                        'qty' => $jumlah,
                        'satuan' => trim((string)($row[7] ?? '-')),
                        'exp_raw' => $rawExp,
                        'exp_parsed' => $expDate
                    ];

                    if ($currentSection === '7') {
                        $july7Expired[] = $item;
                    } else {
                        $july29Expired[] = $item;
                    }
                }
            }
        }

        echo "=== EXPIRED ITEMS IN STO 7 JULI 2026 SECTION ===\n";
        echo "Total: " . count($july7Expired) . " items\n";
        foreach ($july7Expired as $item) {
            echo sprintf("Row %3d | Exp: %-12s | Nama: %-30s | Qty: %-5s %-6s | Donatur: %-15s\n", $item['row'], $item['exp_parsed'], $item['nama'], $item['qty'], $item['satuan'], $item['donatur']);
        }

        echo "\n=== EXPIRED ITEMS IN STO 29 JULI 2026 SECTION ===\n";
        echo "Total: " . count($july29Expired) . " items\n";
        foreach ($july29Expired as $item) {
            echo sprintf("Row %3d | Exp: %-12s | Nama: %-30s | Qty: %-5s %-6s | Donatur: %-15s\n", $item['row'], $item['exp_parsed'], $item['nama'], $item['qty'], $item['satuan'], $item['donatur']);
        }
    }

    private function parseExpDate(string $raw): string
    {
        if (empty($raw)) return '';
        $time = strtotime($raw);
        if ($time !== false) return date('Y-m-d', $time);
        if (strpos($raw, '/') !== false) {
            $parts = explode('/', $raw);
            if (count($parts) == 2) {
                return "2026-" . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            }
        }
        return '';
    }
}
