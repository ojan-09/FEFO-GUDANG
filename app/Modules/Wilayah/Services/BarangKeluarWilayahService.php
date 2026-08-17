<?php

namespace App\Modules\Wilayah\Services;

use App\Modules\Wilayah\Models\BarangKeluarWilayahModel;
use App\Modules\Wilayah\Models\DetailBarangKeluarWilayahModel;
use App\Modules\Wilayah\Repositories\StokWilayahRepository;

class BarangKeluarWilayahService
{
    protected $keluarModel;
    protected $detailModel;
    protected $stokRepo;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->keluarModel = new BarangKeluarWilayahModel();
        $this->detailModel = new DetailBarangKeluarWilayahModel();
        $this->stokRepo   = new StokWilayahRepository($this->db);
    }

    public function getBarangAvailable($idGudang)
    {
        return $this->stokRepo->getBarangAvailable($idGudang);
    }

    public function processBarangKeluar($headerData, $detailData, $userId, $ipAddress)
    {
        $this->db->transBegin();

        try {
            $headerData['created_by'] = $userId;
            $headerData['created_ip'] = $ipAddress;
            $headerData['updated_ip'] = $ipAddress;
            $idKeluar = $this->keluarModel->insert($headerData);

            foreach ($detailData as $detail) {
                if (empty($detail['satuan'])) {
                    $barang = $this->db->table('master_barang_wilayah')
                        ->where('id', $detail['id_barang'])->get()->getRowArray();
                    $detail['satuan'] = $barang ? $barang['satuan'] : '-';
                }

                // Ambil snapshot berat dari stok sebelum dikurangi
                $stokRow = $this->db->table('stok_gudang_wilayah')
                    ->where('id_gudang', $headerData['id_gudang'])
                    ->where('id_barang', $detail['id_barang'])
                    ->get()->getRowArray();

                $beratPerSatuan = $stokRow ? $stokRow['berat_per_satuan'] : null;
                $satuanBerat    = $stokRow ? $stokRow['satuan_berat'] : 'Kg';

                $this->stokRepo->subtractStok(
                    $headerData['id_gudang'],
                    $detail['id_barang'],
                    $detail['jumlah'],
                    $userId,
                    $ipAddress
                );

                $this->detailModel->insert([
                    'id_keluar'        => $idKeluar,
                    'id_barang'       => $detail['id_barang'],
                    'jumlah'          => $detail['jumlah'],
                    'satuan'          => $detail['satuan'],
                    'berat_per_satuan' => $beratPerSatuan,
                    'satuan_berat'     => $satuanBerat
                ]);
            }

            $this->db->transCommit();
            log_message('info', "Barang Keluar berhasil (ID: {$idKeluar}, Gudang: {$headerData['id_gudang']})");

        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('warning', 'Gagal Barang Keluar: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteTransaksi($idKeluar, $userId, $ipAddress)
    {
        $this->db->transBegin();

        try {
            // Ambil header langsung via DB (bypass soft delete model)
            $header = $this->db->table('barang_keluar_wilayah')
                ->where('id', $idKeluar)
                ->get()->getRowArray();

            if (!$header) {
                throw new \RuntimeException("Transaksi Barang Keluar tidak ditemukan.");
            }

            // Ambil detail langsung via DB (bypass soft delete model)
            $details = $this->db->table('detail_barang_keluar_wilayah')
                ->where('id_keluar', $idKeluar)
                ->get()->getResultArray();

            // Kembalikan stok
            foreach ($details as $detail) {
                $this->stokRepo->addStok(
                    $header['id_gudang'],
                    $detail['id_barang'],
                    $detail['jumlah'],
                    $userId,
                    $ipAddress
                );
            }

            // Hapus dari detail baru header (HARD DELETE)
            $this->detailModel->where('id_keluar', $idKeluar)->delete(null, true);
            $this->keluarModel->delete($idKeluar, true);

            $this->db->transCommit();
            log_message('info', "Transaksi Barang Keluar dihapus (ID: {$idKeluar})");

        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('critical', 'Gagal Hapus Barang Keluar: ' . $e->getMessage());
            throw $e;
        }
    }
} 