<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Modules\MasterData\Models\DonaturModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;

class FinalExcelImporterSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) {
            echo "File Excel tidak ditemukan di folder .agents/\n";
            return;
        }

        $filePath = $files[0];
        echo "Processing file: " . basename($filePath) . "\n";

        $spreadsheet = IOFactory::load($filePath);
        $db = \Config\Database::connect();

        $donaturModel = new DonaturModel();
        $kategoriModel = new KategoriModel();
        $barangModel = new BarangModel();
        $bmModel = new BarangMasukModel();
        $batchModel = new BatchModel();

        $userId = 1; // Default Admin User ID

        // Caches
        $donaturCache = [];
        $kategoriCache = [];
        $barangCache = [];
        $bmCache = [];

        // Load existing donatur into cache
        foreach ($donaturModel->findAll() as $d) {
            $donaturCache[strtolower(trim($d['nama_donatur']))] = $d['id'];
        }

        // Load existing kategori into cache
        foreach ($kategoriModel->findAll() as $k) {
            $kategoriCache[strtolower(trim($k['nama_kategori']))] = $k['id'];
        }

        // Load existing barang into cache (Key: nama_gram_satuan)
        foreach ($barangModel->findAll() as $b) {
            $key = strtolower(trim($b['nama_barang'])) . '_' . (float)$b['berat_per_satuan'] . '_' . strtolower(trim($b['satuan']));
            $barangCache[$key] = $b['id'];
        }

        $db->transStart();

        $totalInserted = 0;
        $tglMasuk = date('Y-m-d');

        // Prefer STO 29 Juli 2026 or UPDATE STOK
        $sheetsToProcess = ['STO 29 Juli 2026', 'UPDATE STOK', 'Format'];
        $processedSheets = [];

        foreach ($sheetsToProcess as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            if (!$sheet || in_array($sheetName, $processedSheets)) continue;
            $processedSheets[] = $sheetName;

            $data = $sheet->toArray();
            echo "Importing sheet: $sheetName (Total rows: " . count($data) . ")...\n";

            // Find header row
            $startRow = 2;
            for ($i = 0; $i < min(10, count($data)); $i++) {
                if (isset($data[$i][5]) && stristr((string)$data[$i][5], 'Nama Barang') !== false) {
                    $startRow = $i + 1;
                    break;
                }
            }

            for ($i = $startRow; $i < count($data); $i++) {
                $row = $data[$i];

                $namaDonatur = trim((string)($row[2] ?? ''));
                $namaKategori = trim((string)($row[3] ?? ''));
                $rawExp = trim((string)($row[4] ?? ''));
                $namaBarang = trim((string)($row[5] ?? ''));
                $jumlah = (float)($row[6] ?? 0);
                $satuan = trim((string)($row[7] ?? 'Pcs'));
                $gram = (float)($row[8] ?? 0);
                $catatan = trim((string)($row[11] ?? ''));

                if (empty($namaBarang) || $namaBarang === 'Nama Barang' || $namaBarang === 'Mengetahui,' || $jumlah <= 0) {
                    continue; // Skip non-data rows
                }

                if (empty($namaDonatur)) $namaDonatur = 'Donatur Umum';
                if (empty($namaKategori)) $namaKategori = 'Bahan Pokok';
                if (empty($satuan)) $satuan = 'Pcs';

                // 1. Resolve Donatur
                $keyDonatur = strtolower($namaDonatur);
                if (!isset($donaturCache[$keyDonatur])) {
                    $donaturModel->insert([
                        'nama_donatur' => $namaDonatur,
                        'jenis_donatur' => 'Perusahaan/Organisasi',
                        'kontak' => '-'
                    ]);
                    $donaturCache[$keyDonatur] = $donaturModel->getInsertID();
                }
                $idDonatur = $donaturCache[$keyDonatur];

                // 2. Resolve Kategori
                $keyKategori = strtolower($namaKategori);
                if (!isset($kategoriCache[$keyKategori])) {
                    $kategoriModel->insert(['nama_kategori' => $namaKategori]);
                    $kategoriCache[$keyKategori] = $kategoriModel->getInsertID();
                }
                $idKategori = $kategoriCache[$keyKategori];

                // 3. Resolve Master Barang (Unique Key: nama + gram + satuan)
                $keyBarang = strtolower($namaBarang) . '_' . $gram . '_' . strtolower($satuan);
                if (!isset($barangCache[$keyBarang])) {
                    $lastBarang = $barangModel->orderBy('id', 'DESC')->first();
                    $lastCode = $lastBarang ? (int)substr($lastBarang['kode_barang'], -6) : 0;
                    $newCode = 'BRG-' . str_pad($lastCode + 1, 6, '0', STR_PAD_LEFT);

                    $barangModel->insert([
                        'kode_barang' => $newCode,
                        'nama_barang' => $namaBarang,
                        'id_kategori' => $idKategori,
                        'satuan' => $satuan,
                        'berat_per_satuan' => $gram,
                        'satuan_berat' => 'Gram',
                        'minimum_stok' => 10,
                        'bisa_dipecah' => 0
                    ]);
                    $barangCache[$keyBarang] = $barangModel->getInsertID();
                }
                $idBarang = $barangCache[$keyBarang];

                // 4. Resolve Header Barang Masuk (1 per donatur)
                $keyBm = $idDonatur . '_' . $tglMasuk;
                if (!isset($bmCache[$keyBm])) {
                    $lastBm = $bmModel->like('nomor_transaksi', 'DM-' . date('Ymd') . '-', 'after')->orderBy('id', 'DESC')->first();
                    $lastNo = $lastBm ? (int)substr($lastBm['nomor_transaksi'], -4) : 0;
                    $newTrx = 'DM-' . date('Ymd') . '-' . str_pad($lastNo + 1, 4, '0', STR_PAD_LEFT);

                    $bmModel->insert([
                        'nomor_transaksi' => $newTrx,
                        'id_donatur' => $idDonatur,
                        'id_user' => $userId,
                        'tanggal_masuk' => $tglMasuk,
                        'keterangan' => 'Import dari Excel ' . basename($filePath)
                    ]);
                    $bmCache[$keyBm] = $bmModel->getInsertID();
                }
                $idBm = $bmCache[$keyBm];

                // 5. Parse Date
                $expDate = $this->parseExpDate($rawExp);

                // 6. Insert Batch
                $lastBatch = $batchModel->like('nomor_batch', 'BT-' . date('Ymd') . '-', 'after')->orderBy('id', 'DESC')->first();
                $lastBatchNo = $lastBatch ? (int)substr($lastBatch['nomor_batch'], -4) : 0;
                $noBatch = 'BT-' . date('Ymd') . '-' . str_pad($lastBatchNo + 1 + $totalInserted, 4, '0', STR_PAD_LEFT);

                $batchModel->insert([
                    'id_barang_masuk' => $idBm,
                    'id_barang' => $idBarang,
                    'nomor_batch' => $noBatch,
                    'nama_barang' => $namaBarang,
                    'kategori' => $namaKategori,
                    'tanggal_masuk' => $tglMasuk,
                    'tanggal_kedaluwarsa' => $expDate,
                    'jumlah_awal' => $jumlah,
                    'stok_saat_ini' => $jumlah,
                    'satuan' => $satuan,
                    'berat_per_satuan' => $gram,
                    'satuan_berat' => 'Gram',
                    'status' => (stristr($catatan, 'Tidak Aman') !== false) ? 'Tidak Aktif' : 'Aktif',
                    'bisa_dipecah' => 0,
                    'nilai_satuan' => 0
                ]);

                $totalInserted++;
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            echo "Gagal mengimpor data stok! (Rollback)\n";
        } else {
            echo "BERHASIL! Total $totalInserted batch barang stok telah diimpor ke database!\n";
        }
    }

    private function parseExpDate(string $raw): string
    {
        if (empty($raw)) return date('Y-m-d', strtotime('+1 year'));

        // Handle formats like 31-Jan-2026, 7-Jan-2027
        $time = strtotime($raw);
        if ($time !== false) {
            return date('Y-m-d', $time);
        }

        // Handle slash formats like 24/6 -> 2026-06-24
        if (strpos($raw, '/') !== false) {
            $parts = explode('/', $raw);
            if (count($parts) == 2) {
                $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $month = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                return "2026-$month-$day";
            }
        }

        return date('Y-m-d', strtotime('+1 year'));
    }
}
