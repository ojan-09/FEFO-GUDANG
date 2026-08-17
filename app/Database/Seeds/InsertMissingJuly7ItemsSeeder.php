<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Modules\MasterData\Models\DonaturModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;

class InsertMissingJuly7ItemsSeeder extends Seeder
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

        $db = \Config\Database::connect();

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
        $addedCount = 0;

        $db->transStart();

        // Scan section 7 July (rows 1 to 38)
        for ($i = 2; $i < min(40, count($data)); $i++) {
            $row = $data[$i];

            $namaDonatur = trim((string)($row[2] ?? ''));
            $namaKategori = trim((string)($row[3] ?? ''));
            $rawExp = trim((string)($row[4] ?? ''));
            $namaBarang = trim((string)($row[5] ?? ''));
            $jumlah = $this->cleanNumber($row[6] ?? 0);
            $satuan = trim((string)($row[7] ?? 'Pcs'));
            $gram = $this->cleanNumber($row[8] ?? 0);
            $catatan = trim((string)($row[11] ?? ''));

            if (empty($namaBarang) || $namaBarang === 'Nama Barang' || $jumlah <= 0) continue;

            $keyBarang = strtolower($namaBarang) . '_' . $gram . '_' . strtolower($satuan);

            // ONLY INSERT IF NOT YET IN DATABASE
            if (!isset($barangCache[$keyBarang])) {
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

                // Barang
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
                $idBarang = $barangModel->getInsertID();
                $barangCache[$keyBarang] = $idBarang;

                // Barang Masuk
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
                        'keterangan' => 'Import Tambahan dari STO 7 Juli Excel'
                    ]);
                    $bmCache[$keyBm] = $bmModel->getInsertID();
                }
                $idBm = $bmCache[$keyBm];

                // Batch
                $expDate = $this->parseExpDate($rawExp);
                $lastBatch = $batchModel->like('nomor_batch', 'BT-' . date('Ymd') . '-', 'after')->orderBy('id', 'DESC')->first();
                $lastBatchNo = $lastBatch ? (int)substr($lastBatch['nomor_batch'], -4) : 0;
                $noBatch = 'BT-' . date('Ymd') . '-' . str_pad($lastBatchNo + 1 + $addedCount, 4, '0', STR_PAD_LEFT);

                $status = (stristr($catatan, 'Tidak Aman') !== false) ? 'Tidak Aktif' : 'Aktif';

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

                echo "Added missing July 7 item: $namaBarang ($gram g) - Qty $jumlah $satuan\n";
                $addedCount++;
            }
        }

        $db->transComplete();
        echo "Finished! Added $addedCount missing items from July 7 section.\n";
    }

    private function cleanNumber($val): float
    {
        if (is_numeric($val)) return (float)$val;
        $clean = str_replace(',', '', (string)$val);
        return (float)$clean;
    }

    private function parseExpDate(string $raw): string
    {
        if (empty($raw)) return date('Y-m-d', strtotime('+1 year'));
        $time = strtotime($raw);
        if ($time !== false) return date('Y-m-d', $time);
        if (strpos($raw, '/') !== false) {
            $parts = explode('/', $raw);
            if (count($parts) == 2) {
                return "2026-" . str_pad($parts[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            }
        }
        return date('Y-m-d', strtotime('+1 year'));
    }
}
