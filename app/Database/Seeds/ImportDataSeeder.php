<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Modules\MasterData\Models\DonaturModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;
use App\Models\UserModel;

class ImportDataSeeder extends Seeder
{
    public function run()
    {
        $filePath = ROOTPATH . '.agents/JULI7-29.xlsx';
        
        if (!file_exists($filePath)) {
            echo "File Excel tidak ditemukan.\n";
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        
        $db = \Config\Database::connect();
        
        $donaturModel = new DonaturModel();
        $kategoriModel = new KategoriModel();
        $barangModel = new BarangModel();
        $bmModel = new BarangMasukModel();
        $batchModel = new BatchModel();

        // Admin User
        $userId = 1;
        
        // Cache for lookup
        $donaturCache = [];
        $kategoriCache = [];
        $barangCache = [];
        $bmCache = [];
        
        $db->transStart();
        
        $count = 0;
        
        // Loop from row 3 (index 2)
        for ($i = 2; $i < count($data); $i++) {
            $row = $data[$i];
            
            $namaDonatur = trim((string)($row[2] ?? ''));
            $namaKategori = trim((string)($row[3] ?? ''));
            $tanggalExpiredRaw = trim((string)($row[5] ?? ''));
            $namaBarang = trim((string)($row[6] ?? ''));
            $jumlah = (float)($row[7] ?? 0);
            $beratPerSatuan = (float)($row[8] ?? 0);
            $satuan = trim((string)($row[9] ?? ''));
            $hargaRaw = trim((string)($row[12] ?? '0'));
            
            if (empty($namaBarang) || $jumlah <= 0) {
                continue; // Skip invalid row
            }
            
            if (empty($namaDonatur)) $namaDonatur = 'Donatur Umum (Import)';
            if (empty($namaKategori)) $namaKategori = 'Umum';
            
            // 1. Get/Create Donatur
            if (!isset($donaturCache[$namaDonatur])) {
                $existing = $donaturModel->where('nama_donatur', $namaDonatur)->first();
                if ($existing) {
                    $donaturCache[$namaDonatur] = $existing['id'];
                } else {
                    $donaturModel->insert([
                        'nama_donatur' => $namaDonatur,
                        'jenis_donatur' => 'Perusahaan/Organisasi',
                        'kontak' => '-'
                    ]);
                    $donaturCache[$namaDonatur] = $donaturModel->getInsertID();
                }
            }
            $idDonatur = $donaturCache[$namaDonatur];
            
            // 2. Get/Create Kategori
            if (!isset($kategoriCache[$namaKategori])) {
                $existing = $kategoriModel->where('nama_kategori', $namaKategori)->first();
                if ($existing) {
                    $kategoriCache[$namaKategori] = $existing['id'];
                } else {
                    $kategoriModel->insert(['nama_kategori' => $namaKategori]);
                    $kategoriCache[$namaKategori] = $kategoriModel->getInsertID();
                }
            }
            $idKategori = $kategoriCache[$namaKategori];
            
            // 3. Get/Create Barang
            $keyBarang = strtolower($namaBarang);
            if (!isset($barangCache[$keyBarang])) {
                $existing = $barangModel->where('LOWER(nama_barang)', $keyBarang)->first();
                if ($existing) {
                    $barangCache[$keyBarang] = $existing['id'];
                } else {
                    $lastBarang = $barangModel->orderBy('id', 'DESC')->first();
                    $lastCode = $lastBarang ? (int)substr($lastBarang['kode_barang'], -6) : 0;
                    $newCode = 'BRG-' . str_pad($lastCode + 1, 6, '0', STR_PAD_LEFT);
                    
                    $barangModel->insert([
                        'kode_barang' => $newCode,
                        'nama_barang' => $namaBarang,
                        'id_kategori' => $idKategori,
                        'satuan' => empty($satuan) ? 'Pcs' : $satuan,
                        'berat_per_satuan' => $beratPerSatuan,
                        'satuan_berat' => 'Gram',
                        'minimum_stok' => 10,
                        'bisa_dipecah' => 0
                    ]);
                    $barangCache[$keyBarang] = $barangModel->getInsertID();
                }
            }
            $idBarang = $barangCache[$keyBarang];
            
            // 4. Get/Create Barang Masuk (1 per donatur per hari ini)
            $tglMasuk = date('Y-m-d');
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
                    'keterangan' => 'Import dari Excel JULI7-29.xlsx'
                ]);
                $bmCache[$keyBm] = $bmModel->getInsertID();
            }
            $idBm = $bmCache[$keyBm];
            
            // 5. Create Batch
            // Clean Harga (Rp345,600 -> 345600)
            $hargaStr = preg_replace('/[^0-9]/', '', $hargaRaw);
            $harga = (float)$hargaStr;
            
            // Parse Expired Date (DD/MM/YYYY -> YYYY-MM-DD)
            $expDate = '2027-12-31'; // fallback
            if (!empty($tanggalExpiredRaw)) {
                $parts = explode('/', $tanggalExpiredRaw);
                if (count($parts) == 3) {
                    $expDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                }
            }
            
            $lastBatch = $batchModel->like('nomor_batch', 'BT-' . date('Ymd') . '-', 'after')->orderBy('id', 'DESC')->first();
            $lastBatchNo = $lastBatch ? (int)substr($lastBatch['nomor_batch'], -4) : 0;
            $noBatch = 'BT-' . date('Ymd') . '-' . str_pad($lastBatchNo + 1 + $count, 4, '0', STR_PAD_LEFT);
            
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
                'satuan' => empty($satuan) ? 'Pcs' : $satuan,
                'berat_per_satuan' => $beratPerSatuan,
                'satuan_berat' => 'Gram',
                'status' => 'Aktif',
                'bisa_dipecah' => 0,
                'nilai_satuan' => $harga
            ]);
            
            $count++;
        }
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            echo "Gagal import data (Rollback).\n";
        } else {
            echo "Berhasil import $count batch barang ke database!\n";
        }
    }
}
