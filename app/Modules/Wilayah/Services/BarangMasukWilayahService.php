<?php

namespace App\Modules\Wilayah\Services;

use App\Modules\Wilayah\Models\BarangMasukWilayahModel;
use App\Modules\Wilayah\Models\DetailBarangMasukWilayahModel;
use App\Modules\Wilayah\Models\MasterBarangWilayahModel;
use App\Modules\Wilayah\Repositories\StokWilayahRepository;

class BarangMasukWilayahService
{
    protected $masukModel;
    protected $detailModel;
    protected $masterBarangModel;
    protected $stokRepo;
    protected $db;

    public function __construct()
    {
        $this->db                = \Config\Database::connect();
        $this->masukModel        = new BarangMasukWilayahModel();
        $this->detailModel       = new DetailBarangMasukWilayahModel();
        $this->masterBarangModel = new MasterBarangWilayahModel();
        $this->stokRepo          = new StokWilayahRepository($this->db);
    }

    public function processBarangMasuk($headerData, $detailData, $userId, $ipAddress)
    {
        $this->db->transBegin();

        try {
            // 1. Insert Header
            $headerData['created_by'] = $userId;
            $headerData['created_ip'] = $ipAddress;
            $headerData['updated_ip'] = $ipAddress;

            $idMasuk = $this->masukModel->insert($headerData);

            if (!$idMasuk) {
                $errors = $this->masukModel->errors();
                throw new \RuntimeException('Insert header barang masuk gagal: ' . json_encode($errors));
            }

            // 2. Loop Detail & Update Stok
            foreach ($detailData as $detail) {
                $idBarang = $detail['id_barang'] ?? null;

                // Jika id_barang kosong, cari atau buat di master_barang_wilayah berdasarkan nama
                if (empty($idBarang) && !empty($detail['nama_barang'])) {
                    $existing = $this->masterBarangModel
                        ->where('nama_barang', $detail['nama_barang'])
                        ->first();

                    if ($existing) {
                        $idBarang = $existing['id'];
                    } else {
                        $idKategori = !empty($detail['id_kategori']) ? $detail['id_kategori'] : 1;
                        $kodeBarang = 'BW-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

                        $idBarang = $this->masterBarangModel->insert([
                            'kode_barang'      => $kodeBarang,
                            'nama_barang'      => $detail['nama_barang'],
                            'id_kategori'      => $idKategori,
                            'satuan'           => $detail['satuan'],
                            'berat_per_satuan' => !empty($detail['berat_per_satuan']) ? $detail['berat_per_satuan'] : null,
                            'satuan_berat'     => !empty($detail['satuan_berat']) ? $detail['satuan_berat'] : null,
                            'status'           => 'Aktif'
                        ]);
                    }
                }

                if (!$idBarang) {
                    throw new \RuntimeException("Barang tidak valid pada detail transaksi.");
                }

                $hargaSatuan = !empty($detail['harga_satuan']) ? floatval($detail['harga_satuan']) : 0.00;
                $jumlahVal   = floatval($detail['jumlah']);
                $subtotalNilai = $jumlahVal * $hargaSatuan;

                // Insert Detail
                $this->detailModel->insert([
                    'id_masuk'         => $idMasuk,
                    'id_barang'        => $idBarang,
                    'jumlah'           => $detail['jumlah'],
                    'satuan'           => $detail['satuan'],
                    'berat_per_satuan' => !empty($detail['berat_per_satuan']) ? $detail['berat_per_satuan'] : null,
                    'satuan_berat'     => !empty($detail['satuan_berat']) ? $detail['satuan_berat'] : null,
                    'harga_satuan'     => $hargaSatuan,
                    'subtotal_nilai'   => $subtotalNilai
                ]);

                // Add Stok beserta akumulasi berat
                $this->stokRepo->addStok(
                    $headerData['id_gudang'],
                    $idBarang,
                    $detail['jumlah'],
                    $userId,
                    $ipAddress,
                    !empty($detail['berat_per_satuan']) ? $detail['berat_per_satuan'] : 0,
                    !empty($detail['satuan_berat']) ? $detail['satuan_berat'] : 'Kg'
                );
            }

            $this->db->transCommit();
            log_message('info', "Barang Masuk Wilayah berhasil (ID Transaksi: {$idMasuk}, Gudang ID: {$headerData['id_gudang']})");
            return $idMasuk;

        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('critical', '[Gudang Wilayah] Gagal Barang Masuk: ' . $e->getMessage());
            throw $e;
        }
    }

    public function processDelete($idMasuk, $userId, $ipAddress)
    {
        $this->db->transBegin();

        try {
            $header = $this->masukModel->find($idMasuk);
            if (!$header) {
                throw new \RuntimeException('Transaksi tidak ditemukan.');
            }

            $details = $this->detailModel->where('id_masuk', $idMasuk)->findAll();

            foreach ($details as $detail) {
                // Cek stok sekarang sebelum dikurangi
                $stok = $this->db->table('stok_gudang_wilayah')
                    ->where('id_gudang', $header['id_gudang'])
                    ->where('id_barang', $detail['id_barang'])
                    ->get()->getRowArray();

                $stokSaat  = $stok ? (float) $stok['jumlah'] : 0;
                $jumlahMasuk = (float) $detail['jumlah'];

                if ($stokSaat < $jumlahMasuk) {
                    // Ambil nama barang untuk pesan error yang lebih jelas
                    $barang = $this->db->table('master_barang_wilayah')
                        ->where('id', $detail['id_barang'])
                        ->get()->getRowArray();
                    $namaBarang = $barang ? $barang['nama_barang'] : 'ID ' . $detail['id_barang'];

                    throw new \RuntimeException(
                        "Tidak dapat menghapus transaksi ini. Stok barang \"{$namaBarang}\" saat ini hanya {$stokSaat} {$detail['satuan']}, " .
                        "sedangkan transaksi masuk ini mencatat {$jumlahMasuk} {$detail['satuan']}. " .
                        "Hapus transaksi KELUAR yang menggunakan barang ini terlebih dahulu."
                    );
                }

                $this->stokRepo->subtractStok(
                    $header['id_gudang'],
                    $detail['id_barang'],
                    $detail['jumlah'],
                    $userId,
                    $ipAddress
                );
            }

            // Hapus Detail dan Header (Hard Delete)
            $this->detailModel->where('id_masuk', $idMasuk)->delete();
            $this->masukModel->delete($idMasuk);

            $this->db->transCommit();
            log_message('info', "Transaksi Barang Masuk Wilayah berhasil dihapus (ID Transaksi: {$idMasuk}, Gudang ID: {$header['id_gudang']})");

        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('critical', '[Gudang Wilayah] Gagal Hapus Barang Masuk: ' . $e->getMessage());
            throw $e;
        }
    }
}