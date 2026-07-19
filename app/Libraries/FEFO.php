<?php

namespace App\Libraries;

use App\Modules\Transactions\Models\BatchModel;

/**
 * Library FEFO (First Expired First Out)
 * 
 * Seluruh algoritma pengeluaran barang berdasarkan tanggal kedaluwarsa
 * terdekat berada di sini. Controller TIDAK BOLEH berisi logika FEFO.
 */
class FEFO
{
    protected $batchModel;
    protected $detailModel;

    public function __construct()
    {
        $this->batchModel = new BatchModel();
        $this->detailModel = new \App\Modules\Transactions\Models\DetailBarangKeluarModel();
    }

    /**
     * Hitung total stok aktif suatu barang dari seluruh batch.
     *
     * @param int $idBarang
     * @return float Total stok tersedia
     */
    public function getStokBarang(int $idBarang): float
    {
        $result = $this->batchModel
            ->selectSum('stok_saat_ini')
            ->where('id_barang', $idBarang)
            ->where('status', 'Aktif')
            ->first();

        return (float) ($result['stok_saat_ini'] ?? 0);
    }

    /**
     * Cek apakah stok aktif mencukupi untuk jumlah yang diminta.
     *
     * @param int $idBarang
     * @param float $jumlah
     * @return bool
     */
    public function cekKetersediaan(int $idBarang, float $jumlah): bool
    {
        return $this->getStokBarang($idBarang) >= $jumlah;
    }

    /**
     * Mengecek ketersediaan stok untuk beberapa barang sekaligus.
     * Mengurangi query berulang di dalam loop.
     * 
     * @param array $items Array asosiatif: ['id_barang' => jumlah, ...]
     * @return array Array berisi id_barang yang stoknya kurang, kosong jika semua cukup
     */
    public function cekKetersediaanBulk(array $items): array
    {
        $idBarangList = array_keys($items);
        if (empty($idBarangList)) return [];

        $results = $this->batchModel
            ->select('id_barang, SUM(stok_saat_ini) as total_stok')
            ->whereIn('id_barang', $idBarangList)
            ->where('status', 'Aktif')
            ->groupBy('id_barang')
            ->findAll();

        $stokMap = [];
        foreach ($results as $row) {
            $stokMap[$row['id_barang']] = (float)$row['total_stok'];
        }

        $kurang = [];
        foreach ($items as $idBarang => $jumlah) {
            $tersedia = $stokMap[$idBarang] ?? 0;
            if ($tersedia < $jumlah) {
                $kurang[] = $idBarang;
            }
        }

        return $kurang;
    }

    /**
     * Jalankan algoritma FEFO secara batch (banyak barang sekaligus).
     * Melakukan perhitungan di memori dan menyimpannya menggunakan insertBatch/updateBatch.
     * 
     * @param int $idBarangKeluar
     * @param array $items Array of ['id_barang' => ID, 'jumlah_keluar' => JUMLAH]
     * @return bool True jika berhasil, false jika stok tidak cukup
     */
    public function prosesBarangKeluarBulk(int $idBarangKeluar, array $items): bool
    {
        // Siapkan mapping untuk cek ketersediaan
        $kebutuhan = [];
        foreach ($items as $item) {
            $idBrg = (int) $item['id_barang'];
            $kebutuhan[$idBrg] = ($kebutuhan[$idBrg] ?? 0) + (float) $item['jumlah_keluar'];
        }

        // Validasi massal
        if (!empty($this->cekKetersediaanBulk($kebutuhan))) {
            return false;
        }

        $idBarangList = array_keys($kebutuhan);
        
        // Ambil semua batch aktif sekaligus, urutkan FEFO
        $batches = $this->batchModel
            ->whereIn('id_barang', $idBarangList)
            ->where('status', 'Aktif')
            ->where('stok_saat_ini >', 0)
            ->orderBy('id_barang', 'ASC')
            ->orderBy('tanggal_kedaluwarsa', 'ASC')
            ->findAll();

        // Kelompokkan batch per barang
        $batchPerBarang = [];
        foreach ($batches as $batch) {
            $batchPerBarang[$batch['id_barang']][] = $batch;
        }

        $updateBatchData = [];
        $insertDetailData = [];

        foreach ($kebutuhan as $idBarang => $jumlahKeluar) {
            $sisaKebutuhan = $jumlahKeluar;
            
            if (empty($batchPerBarang[$idBarang])) {
                return false; // Seharusnya tidak terjadi karena sudah di-cekKetersediaanBulk
            }

            foreach ($batchPerBarang[$idBarang] as $batch) {
                if ($sisaKebutuhan <= 0) {
                    break;
                }

                $diambil = min($sisaKebutuhan, $batch['stok_saat_ini']);
                $stokBaru = $batch['stok_saat_ini'] - $diambil;

                $updateBatchData[] = [
                    'id'            => $batch['id'],
                    'stok_saat_ini' => $stokBaru,
                    'status'        => ($stokBaru <= 0) ? 'Habis' : 'Aktif'
                ];

                $insertDetailData[] = [
                    'id_barang_keluar' => $idBarangKeluar,
                    'id_batch'         => $batch['id'],
                    'jumlah_keluar'    => $diambil,
                ];

                $sisaKebutuhan -= $diambil;
            }

            if ($sisaKebutuhan > 0) return false;
        }

        // Simpan hasil sekaligus (Batch mode)
        if (!empty($updateBatchData)) {
            $this->batchModel->updateBatch($updateBatchData, 'id');
        }
        if (!empty($insertDetailData)) {
            $this->detailModel->insertBatch($insertDetailData);
        }

        return true;
    }

    /**
     * Jalankan algoritma FEFO:
     * 1. Cari batch aktif untuk barang tersebut
     * 2. Urutkan berdasarkan tanggal kedaluwarsa ASC (terdekat duluan)
     * 3. Potong stok dari batch pertama
     * 4. Jika tidak cukup, lanjut ke batch berikutnya
     * 5. Update status batch menjadi 'Habis' jika stok = 0
     *
     * @param int $idBarangKeluar
     * @param int $idBarang
     * @param float $jumlahKeluar
     * @return bool True jika berhasil, false jika stok tidak cukup
     */
    public function prosesBarangKeluar(int $idBarangKeluar, int $idBarang, float $jumlahKeluar): bool
    {
        // Validasi ketersediaan stok
        if (!$this->cekKetersediaan($idBarang, $jumlahKeluar)) {
            return false;
        }

        // Cari semua batch aktif, urutkan expired terdekat dulu
        $batches = $this->batchModel
            ->where('id_barang', $idBarang)
            ->where('status', 'Aktif')
            ->where('stok_saat_ini >', 0)
            ->orderBy('tanggal_kedaluwarsa', 'ASC')
            ->findAll();

        $sisaKebutuhan = $jumlahKeluar;
        $detailPengambilan = [];

        foreach ($batches as $batch) {
            if ($sisaKebutuhan <= 0) {
                break;
            }

            // Tentukan berapa yang diambil dari batch ini
            $diambil = min($sisaKebutuhan, $batch['stok_saat_ini']);

            // Hitung sisa stok batch setelah dipotong
            $stokBaru = $batch['stok_saat_ini'] - $diambil;

            // Update stok batch
            $updateData = ['stok_saat_ini' => $stokBaru];

            // Jika stok habis, ubah status menjadi 'Habis'
            if ($stokBaru <= 0) {
                $updateData['status'] = 'Habis';
            }

            $this->batchModel->update($batch['id'], $updateData);

            // Catat detail pengambilan langsung ke database
            $this->detailModel->insert([
                'id_barang_keluar' => $idBarangKeluar,
                'id_batch'         => $batch['id'],
                'jumlah_keluar'    => $diambil,
            ]);

            $sisaKebutuhan -= $diambil;
        }

        return true;
    }

    /**
     * Kembalikan stok batch yang sebelumnya dipotong.
     * Digunakan saat transaksi barang keluar dihapus.
     *
     * @param array $details Array dari detail_barang_keluar
     */
    public function kembalikanStok(array $details): void
    {
        foreach ($details as $detail) {
            $batch = $this->batchModel->find($detail['id_batch']);
            if ($batch) {
                $stokBaru = $batch['stok_saat_ini'] + $detail['jumlah_keluar'];
                $this->batchModel->update($batch['id'], [
                    'stok_saat_ini' => $stokBaru,
                    'status'        => 'Aktif',
                ]);
            }
        }
    }

    /**
     * Rollback penuh transaksi barang keluar:
     * 1. Kembalikan seluruh stok ke batch asal.
     * 2. Hapus histori detail_barang_keluar.
     * Wajib dijalankan di dalam Database Transaction oleh Controller.
     *
     * @param int $idBarangKeluar
     */
    public function rollbackBarangKeluar(int $idBarangKeluar): void
    {
        // 1. Ambil semua detail_barang_keluar
        $details = $this->detailModel->where('id_barang_keluar', $idBarangKeluar)->findAll();
        
        // 2. Kembalikan stok
        $this->kembalikanStok($details);
        
        // 3. Hapus histori detail lama
        $this->detailModel->where('id_barang_keluar', $idBarangKeluar)->delete();
    }
}

