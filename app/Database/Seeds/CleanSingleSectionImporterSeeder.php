<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Modules\MasterData\Models\DonaturModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;

class CleanSingleSectionImporterSeeder extends Seeder
{
    public function run()
    {
        $dir = ROOTPATH . '.agents/';
        $files = glob($dir . '*.xlsx');
        if (empty($files)) {
            echo "File Excel tidak ditemukan.\n";
            return;
        }

        $filePath = $files[0];
        echo "Clean Import from file: " . basename($filePath) . "\n";

        $db = \Config\Database::connect();

        // 1. ROLLBACK PREVIOUS IMPORT (Delete batches created today with DM- transaction)
        $db->transStart();
        $db->query("DELETE FROM batch WHERE id_barang_masuk IN (SELECT id FROM barang_masuk WHERE keterangan LIKE '%Import dari Excel%')");
        $db->query("DELETE FROM barang_masuk WHERE keterangan LIKE '%Import dari Excel%'");
        $db->transComplete();

        echo "Previous Excel import cleaned successfully.\n";

        // 2. PARSE EXCEL - STO 29 JULI SECTION ONLY
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName('STO 29 Juli 2026') ?? $spreadsheet->getSheetByName('UPDATE STOK') ?? $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Find the start of "STO 29 Juli" section
        $sto29StartRow = -1;
        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $col0 = trim((string)($row[0] ?? ''));
            $col2 = trim((string)($row[2] ?? ''));
            if (stristr($col0, '29') !== false || stristr($col2, '29') !== false || stristr($col0, '26 Juli') !== false || stristr($col2, '26 Juli') !== false) {
                $sto29StartRow = $i;
                break;
            }
        }

        if ($sto29StartRow === -1) {
            // Fallback to start from header row
            $sto29StartRow = 0;
        }

        echo "Starting import from row " . ($sto29StartRow + 1) . " (STO 29 Juli Section)...\n";

        $donaturModel = new DonaturModel();
        $kategoriModel = new KategoriModel();
        $barangModel = new BarangModel();
        $bmModel = new BarangMasukModel();
        $batchModel = new BatchModel();

        $userId = 1;
        $tglMasuk = date('Y-m-d');

        $donaturCache = [];
        foreach ($donaturModel->findAll() as $d) {
            $donaturCache[strtolower(trim($d['nama_donatur']))] = $d['id'];
        }

        $kategoriCache = [];
        foreach ($kategoriModel->findAll() as $k) {
            $kategoriCache[strtolower(trim($k['nama_kategori']))] = $k['id'];
        }

        $barangCache = [];
        foreach ($barangModel->findAll() as $b) {
            $key = strtolower(trim($b['nama_barang'])) . '_' . (float)$b['berat_per_satuan'] . '_' . strtolower(trim($b['satuan']));
            $barangCache[$key] = $b['id'];
        }

        $bmCache = [];
        $insertedCount = 0;

        $db->transStart();

        for ($i = $sto29StartRow; $i < count($data); $i++) {
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
                continue;
            }

            if (empty($namaDonatur)) $namaDonatur = 'Donatur Umum';
            if (empty($namaKategori)) $namaKategori = 'Bahan Pokok';
            if (empty($satuan)) $satuan = 'Pcs';

            // Donatur
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

            // Kategori
            $keyKategori = strtolower($namaKategori);
            if (!isset($kategoriCache[$keyKategori])) {
                $kategoriModel->insert(['nama_kategori' => $namaKategori]);
                $kategoriCache[$keyKategori] = $kategoriModel->getInsertID();
            }
            $idKategori = $kategoriCache[$keyKategori];

            // Barang (Unique: nama + gram + satuan)
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

            // Barang Masuk Header
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

            // Date
            $expDate = $this->parseExpDate($rawExp);

            // Batch
            $lastBatch = $batchModel->like('nomor_batch', 'BT-' . date('Ymd') . '-', 'after')->orderBy('id', 'DESC')->first();
            $lastBatchNo = $lastBatch ? (int)substr($lastBatch['nomor_batch'], -4) : 0;
            $noBatch = 'BT-' . date('Ymd') . '-' . str_pad($lastBatchNo + 1 + $insertedCount, 4, '0', STR_PAD_LEFT);

            // Status default: Aktif
            $status = 'Aktif';
            if (stristr($catatan, 'Tidak Aman') !== false || stristr($catatan, 'Rusak') !== false) {
                $status = 'Tidak Aktif';
            }

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
                'status' => $status,
                'bisa_dipecah' => 0,
                'nilai_satuan' => 0
            ]);

            $insertedCount++;
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            echo "Gagal mengimpor data! (Rollback)\n";
        } else {
            echo "BERHASIL KELAR! $insertedCount batch stok unik dari Laporan 29 Juli telah masuk!\n";
        }
    }

    private function parseExpDate(string $raw): string
    {
        if (empty($raw)) return date('Y-m-d', strtotime('+1 year'));

        $time = strtotime($raw);
        if ($time !== false) {
            return date('Y-m-d', $time);
        }

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
