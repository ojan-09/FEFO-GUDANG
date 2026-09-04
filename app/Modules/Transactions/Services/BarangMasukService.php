<?php

namespace App\Modules\Transactions\Services;

use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\BarangModel;

class BarangMasukService
{
    protected BarangMasukModel $barangMasukModel;
    protected BatchModel       $batchModel;
    protected KategoriModel    $kategoriModel;
    protected BarangModel      $barangModel;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
        $this->batchModel       = new BatchModel();
        $this->kategoriModel    = new KategoriModel();
        $this->barangModel      = new BarangModel();
    }

    public function validateAndCleanItems(array $items, string $tanggalMasuk): array
    {
        if (empty($items)) {
            return ['ok' => false, 'errors' => ['items' => 'Minimal harus ada 1 barang.'], 'items' => []];
        }

        $kategoriValid    = array_column($this->kategoriModel->findAll(), 'nama_kategori');
        $satuanBeratValid = ['Gram', 'Kg', 'ml', 'Liter'];

        // [FIX #8] Hapus 'KG' dari satuan kemasan — KG hanya valid sebagai satuan BERAT,
        // bukan satuan kemasan. Sebelumnya ada di kedua list sehingga membingungkan.
        $satuanValidUpper = [
            'PCS', 'KOTAK', 'KARUNG', 'DUS', 'BOX', 'PACK', 'BOTOL',
            'KALENG', 'TRAY', 'POUCH', 'SACHET', 'RENCENG', 'KANTONG', 'REPACK',
        ];

        $cleanItems = [];

        foreach ($items as $key => $item) {
            $namaBarang         = trim($item['nama_barang'] ?? '');
            $kategori           = trim($item['kategori'] ?? '');
            $satuan             = trim($item['satuan'] ?? '');
            $beratPerSatuan     = (float) ($item['berat_per_satuan'] ?? 0);
            $satuanBerat        = trim($item['satuan_berat'] ?? '');
            $tanggalKedaluwarsa = trim($item['tanggal_kedaluwarsa'] ?? '');
            $bisaDipecah        = (int) ($item['bisa_dipecah'] ?? 0);

            $jumlahCtn    = trim($item['jumlah_ctn'] ?? '');
            $isiPerCtn    = trim($item['isi_per_ctn'] ?? '');
            $jumlahCtnVal = ($jumlahCtn !== '' && (int) $jumlahCtn > 0) ? (int) $jumlahCtn : null;
            $isiPerCtnVal = ($isiPerCtn !== '' && (int) $isiPerCtn > 0) ? (int) $isiPerCtn : null;
            $menggunakanKemasan = ($jumlahCtnVal !== null && $isiPerCtnVal !== null) ? 1 : 0;

            $jumlah = ($menggunakanKemasan === 1)
                ? $jumlahCtnVal * $isiPerCtnVal
                : (int) ($item['jumlah'] ?? 0);

            $prefix = "Item baris ke-{$key}:";

            if ($namaBarang === '') {
                return $this->validationError("{$prefix} Nama Barang wajib diisi.");
            }
            if ($kategori === '') {
                return $this->validationError("{$prefix} Kategori wajib diisi.");
            }
            if (!in_array($kategori, $kategoriValid, true)) {
                return $this->validationError("{$prefix} Kategori tidak valid.");
            }
            if ($jumlah <= 0) {
                return $this->validationError("{$prefix} Jumlah harus lebih besar dari 0.");
            }
            if ($satuan === '') {
                return $this->validationError("{$prefix} Satuan wajib diisi.");
            }
            if (!in_array(strtoupper($satuan), $satuanValidUpper, true)) {
                return $this->validationError("{$prefix} Satuan tidak valid.");
            }
            if ($beratPerSatuan <= 0) {
                return $this->validationError("{$prefix} Berat per Satuan harus lebih besar dari 0.");
            }
            if ($satuanBerat === '') {
                return $this->validationError("{$prefix} Satuan Berat wajib diisi.");
            }
            if (!in_array($satuanBerat, $satuanBeratValid, true)) {
                return $this->validationError("{$prefix} Satuan Berat tidak valid.");
            }
            if ($tanggalKedaluwarsa === '') {
                return $this->validationError("{$prefix} Tanggal Kedaluwarsa wajib diisi.");
            }
            if (!strtotime($tanggalKedaluwarsa)) {
                return $this->validationError("{$prefix} Tanggal Kedaluwarsa tidak valid.");
            }
            if ($tanggalKedaluwarsa <= $tanggalMasuk) {
                return $this->validationError("{$prefix} Tanggal Kedaluwarsa harus lebih besar dari Tanggal Masuk.");
            }
            if ($bisaDipecah === 1 && !in_array(strtolower($satuan), ['karung', 'repack'])) {
                return $this->validationError("{$prefix} Repack (Bisa Dipecah) hanya berlaku untuk kemasan Karung.");
            }

            $rawNilai    = $item['nilai_satuan'] ?? '0';
            $rawNilai    = str_replace(['Rp', ' ', '.'], '', $rawNilai);
            $rawNilai    = str_replace(',', '.', $rawNilai);
            $nilaiSatuan = (float) $rawNilai;
            if ($nilaiSatuan < 0) {
                return $this->validationError("{$prefix} Nilai Satuan tidak boleh negatif.");
            }

            $idBatch      = trim($item['id'] ?? '');
            $cleanItems[] = [
                'id'                  => ($idBatch === '') ? null : (int) $idBatch,
                'nama_barang'         => $namaBarang,
                'kategori'            => $kategori,
                'menggunakan_kemasan' => $menggunakanKemasan,
                'jumlah_ctn'          => $jumlahCtnVal,
                'isi_per_ctn'         => $isiPerCtnVal,
                'jumlah'              => $jumlah,
                'satuan'              => $satuan,
                'berat_per_satuan'    => $beratPerSatuan,
                'satuan_berat'        => $satuanBerat,
                'tanggal_kedaluwarsa' => $tanggalKedaluwarsa,
                'nilai_satuan'        => $nilaiSatuan,
                'bisa_dipecah'        => $bisaDipecah,
            ];
        }

        return ['ok' => true, 'errors' => [], 'items' => $cleanItems];
    }

    public function store(array $headerData, array $cleanItems): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $today          = date('Ymd');
        $nomorTransaksi = $this->barangMasukModel->generateNomorTransaksiAtomic($today);

        $this->barangMasukModel->insert([
            'nomor_transaksi' => $nomorTransaksi,
            'id_donatur'      => $headerData['id_donatur'],
            'id_user'         => $headerData['id_user'],
            'tanggal_masuk'   => $headerData['tanggal_masuk'],
            'eta'             => $headerData['eta'] ?: null,
            'keterangan'      => $headerData['keterangan'],
        ]);
        $idBarangMasuk = $this->barangMasukModel->getInsertID();

        [$kategoriMap, $barangMap, $lastBrgNumber] = $this->loadMasterMaps($cleanItems);

        $prefixBatch = "BT-{$today}-";
        $lastNumber  = $this->getLastBatchNumber($db, $prefixBatch);

        $batchInsertData = [];
        foreach ($cleanItems as $item) {
            [$idBarang, $bisaDipecah, $lastBrgNumber] = $this->resolveOrCreateBarang(
                $item, $kategoriMap, $barangMap, $lastBrgNumber
            );

            $lastNumber++;
            $nomorBatch = $prefixBatch . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
            [$jumlahAwal, $stokSaatIni] = $this->hitungStokAwal($item, $bisaDipecah);

            $batchInsertData[] = $this->buildBatchRow(
                $item, $idBarangMasuk, $idBarang, $nomorBatch,
                $jumlahAwal, $stokSaatIni, $bisaDipecah,
                $headerData['tanggal_masuk']
            );
        }

        if (!empty($batchInsertData)) {
            $this->batchModel->insertBatch($batchInsertData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['ok' => false, 'errors' => ['db' => 'Gagal menyimpan transaksi. Silakan coba lagi.'],
                    'nomor_transaksi' => '', 'total_item' => 0];
        }

        return [
            'ok'              => true,
            'errors'          => [],
            'nomor_transaksi' => $nomorTransaksi,
            'total_item'      => array_sum(array_column($cleanItems, 'jumlah')),
        ];
    }

    public function update(int $id, array $headerData, array $cleanItems): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->barangMasukModel->update($id, [
            'id_donatur'    => $headerData['id_donatur'],
            'tanggal_masuk' => $headerData['tanggal_masuk'],
            'eta'           => $headerData['eta'] ?: null,
            'keterangan'    => $headerData['keterangan'],
        ]);

        $existingBatches  = $this->batchModel->where('id_barang_masuk', $id)->findAll();
        $existingBatchMap = array_column($existingBatches, null, 'id');

        $submittedIds    = array_filter(array_column($cleanItems, 'id'));
        $deletedBatchIds = array_diff(array_keys($existingBatchMap), $submittedIds);

        foreach ($deletedBatchIds as $dbId) {
            $eb = $existingBatchMap[$dbId];
            if ((float) $eb['stok_saat_ini'] < (float) $eb['jumlah_awal']) {
                $db->transRollback();
                return ['ok' => false, 'errors' => ['items' =>
                    "Barang '{$eb['nama_barang']}' tidak dapat dihapus karena stoknya sudah digunakan pada proses Penyaluran atau Penyesuaian Stok."],
                    'total_item' => 0];
            }
            $this->batchModel->delete($dbId);
        }

        [$kategoriMap, $barangMap, $lastBrgNumber] = $this->loadMasterMaps($cleanItems);

        $today       = date('Ymd');
        $prefixBatch = "BT-{$today}-";
        $lastNumber  = $this->getLastBatchNumber($db, $prefixBatch);

        foreach ($cleanItems as $item) {
            [$idBarang, $bisaDipecah, $lastBrgNumber] = $this->resolveOrCreateBarang(
                $item, $kategoriMap, $barangMap, $lastBrgNumber
            );

            if (!empty($item['id']) && isset($existingBatchMap[$item['id']])) {
                $result = $this->updateExistingBatch(
                    $existingBatchMap[$item['id']], $item,
                    $idBarang, $bisaDipecah, $headerData['tanggal_masuk'], $db
                );
                if (!$result['ok']) {
                    return ['ok' => false, 'errors' => $result['errors'], 'total_item' => 0];
                }
            } else {
                $lastNumber++;
                $nomorBatch = $prefixBatch . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
                [$jumlahAwal, $stokSaatIni] = $this->hitungStokAwal($item, $bisaDipecah);

                $this->batchModel->insert(
                    $this->buildBatchRow(
                        $item, $id, $idBarang, $nomorBatch,
                        $jumlahAwal, $stokSaatIni, $bisaDipecah,
                        $headerData['tanggal_masuk']
                    )
                );
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['ok' => false, 'errors' => ['db' => 'Gagal memperbarui transaksi. Silakan coba lagi.'],
                    'total_item' => 0];
        }

        return [
            'ok'         => true,
            'errors'     => [],
            'total_item' => array_sum(array_column($cleanItems, 'jumlah')),
        ];
    }

    // =========================================================
    // Private helpers
    // =========================================================

    private function validationError(string $message): array
    {
        return ['ok' => false, 'errors' => ['items' => $message], 'items' => []];
    }

    private function loadMasterMaps(array $cleanItems): array
    {
        $kategoriList = $this->kategoriModel->findAll();
        $kategoriMap  = array_column($kategoriList, 'id', 'nama_kategori');

        $namaBarangUnik = array_unique(array_column($cleanItems, 'nama_barang'));

        // [FIX #16] Gunakan LOWER() di SQL agar case-insensitive tanpa bergantung
        // pada collation database. Sebelumnya whereIn() dengan strtolower() di PHP
        // gagal menemukan barang jika DB menyimpan "Beras" tapi dicari "beras".
        $barangList = [];
        if (!empty($namaBarangUnik)) {
            $placeholders = implode(',', array_fill(0, count($namaBarangUnik), '?'));
            $lowerNames   = array_map('strtolower', $namaBarangUnik);
            $barangList   = \Config\Database::connect()
                ->query(
                    "SELECT id, nama_barang, bisa_dipecah, satuan
                     FROM barang
                     WHERE LOWER(nama_barang) IN ({$placeholders})",
                    $lowerNames
                )
                ->getResultArray();
        }

        $barangMap = [];
        foreach ($barangList as $b) {
            // Key pakai lowercase agar match konsisten dari sisi PHP
            $key             = strtolower($b['nama_barang']) . '_' . (int) $b['bisa_dipecah'];
            $barangMap[$key] = ['id' => $b['id'], 'bisa_dipecah' => (int) $b['bisa_dipecah']];
        }

        $maxBrgRow = \Config\Database::connect()
            ->table('barang')
            ->select('MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num')
            ->where('kode_barang LIKE', 'BRG-%')
            ->get()->getRowArray();
        $lastBrgNumber = $maxBrgRow ? (int) $maxBrgRow['max_num'] : 0;

        return [$kategoriMap, $barangMap, $lastBrgNumber];
    }

    private function resolveOrCreateBarang(
        array $item,
        array $kategoriMap,
        array &$barangMap,
        int $lastBrgNumber
    ): array {
        $key        = strtolower($item['nama_barang']) . '_' . (int) $item['bisa_dipecah'];
        $idKategori = $kategoriMap[$item['kategori']] ?? 1;

        if (isset($barangMap[$key])) {
            return [$barangMap[$key]['id'], $barangMap[$key]['bisa_dipecah'], $lastBrgNumber];
        }

        $lastBrgNumber++;
        $kodeBarang = 'BRG-' . str_pad($lastBrgNumber, 6, '0', STR_PAD_LEFT);
        $this->barangModel->insert([
            'kode_barang'      => $kodeBarang,
            'id_kategori'      => $idKategori,
            'nama_barang'      => $item['nama_barang'],
            'satuan'           => $item['satuan'],
            'berat_per_satuan' => $item['berat_per_satuan'],
            'satuan_berat'     => $item['satuan_berat'],
            'minimum_stok'     => 0,
            'bisa_dipecah'     => $item['bisa_dipecah'],
        ]);
        $idBarang    = $this->barangModel->getInsertID();
        $bisaDipecah = (int) $item['bisa_dipecah'];

        $barangMap[$key] = ['id' => $idBarang, 'bisa_dipecah' => $bisaDipecah];

        return [$idBarang, $bisaDipecah, $lastBrgNumber];
    }

    private function hitungStokAwal(array $item, int $bisaDipecah): array
    {
        if ($bisaDipecah !== 1) {
            $qty = (float) $item['jumlah'];
            return [$qty, $qty];
        }

        $berat   = (float) $item['berat_per_satuan'];
        $satuanB = strtolower($item['satuan_berat']);
        $qty     = (float) $item['jumlah'];

        $totalKg = in_array(strtolower($item['satuan']), ['kg', 'repack'])
            ? $qty
            : (($satuanB === 'gram') ? ($qty * $berat / 1000) : ($qty * $berat));

        return [$totalKg, $totalKg];
    }

    private function getLastBatchNumber(\CodeIgniter\Database\BaseConnection $db, string $prefixBatch): int
    {
        $row = $db->query(
            "SELECT nomor_batch FROM batch
             WHERE nomor_batch LIKE ?
             ORDER BY id DESC LIMIT 1 FOR UPDATE",
            [$prefixBatch . '%']
        )->getRowArray();

        return $row ? (int) substr($row['nomor_batch'], -4) : 0;
    }

    private function buildBatchRow(
        array  $item,
        int    $idBarangMasuk,
        int    $idBarang,
        string $nomorBatch,
        float  $jumlahAwal,
        float  $stokSaatIni,
        int    $bisaDipecah,
        string $tanggalMasuk
    ): array {
        return [
            'id_barang_masuk'     => $idBarangMasuk,
            'id_barang'           => $idBarang,
            'nomor_batch'         => $nomorBatch,
            'nama_barang'         => $item['nama_barang'],
            'kategori'            => $item['kategori'],
            'tanggal_masuk'       => $tanggalMasuk,
            'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
            'jumlah_awal'         => $jumlahAwal,
            'stok_saat_ini'       => $stokSaatIni,
            'menggunakan_kemasan' => $item['menggunakan_kemasan'],
            'jumlah_ctn'          => $item['jumlah_ctn'],
            'isi_per_ctn'         => $item['isi_per_ctn'],
            'satuan'              => $item['satuan'],
            'berat_per_satuan'    => $item['berat_per_satuan'],
            'satuan_berat'        => $item['satuan_berat'],
            'nilai_satuan'        => $item['nilai_satuan'],
            'status'              => 'Aktif',
            'bisa_dipecah'        => $bisaDipecah,
        ];
    }

    private function updateExistingBatch(
        array  $eb,
        array  $item,
        int    $idBarang,
        int    $bisaDipecah,
        string $tanggalMasuk,
        \CodeIgniter\Database\BaseConnection $db
    ): array {
        [$submittedQty] = $this->hitungStokAwal($item, $bisaDipecah);

        if ((int) $eb['id_barang'] !== $idBarang) {
            if ((float) $eb['stok_saat_ini'] < (float) $eb['jumlah_awal']) {
                $db->transRollback();
                return ['ok' => false, 'errors' => ['items' =>
                    "Barang '{$eb['nama_barang']}' tidak dapat diganti ke barang lain karena sebagian stoknya sudah digunakan."]];
            }

            $this->batchModel->update($eb['id'], array_merge(
                $this->buildBatchUpdateFields($item, $tanggalMasuk, $submittedQty, $bisaDipecah),
                ['id_barang' => $idBarang, 'stok_saat_ini' => $submittedQty]
            ));
        } else {
            $delta    = $submittedQty - (float) $eb['jumlah_awal'];
            $stokBaru = (float) $eb['stok_saat_ini'] + $delta;

            if ($stokBaru < 0) {
                $db->transRollback();
                return ['ok' => false, 'errors' => ['items' =>
                    "Jumlah barang '{$item['nama_barang']}' tidak dapat dikurangi menjadi {$item['jumlah']} karena sisa stok gudang tinggal {$eb['stok_saat_ini']}."]];
            }

            $this->batchModel->update($eb['id'], array_merge(
                $this->buildBatchUpdateFields($item, $tanggalMasuk, $submittedQty, $bisaDipecah),
                ['stok_saat_ini' => $stokBaru]
            ));
        }

        return ['ok' => true, 'errors' => []];
    }

    private function buildBatchUpdateFields(
        array  $item,
        string $tanggalMasuk,
        float  $jumlahAwal,
        int    $bisaDipecah
    ): array {
        return [
            'nama_barang'         => $item['nama_barang'],
            'kategori'            => $item['kategori'],
            'tanggal_masuk'       => $tanggalMasuk,
            'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
            'jumlah_awal'         => $jumlahAwal,
            'menggunakan_kemasan' => $item['menggunakan_kemasan'],
            'jumlah_ctn'          => $item['jumlah_ctn'],
            'isi_per_ctn'         => $item['isi_per_ctn'],
            'satuan'              => $item['satuan'],
            'berat_per_satuan'    => $item['berat_per_satuan'],
            'satuan_berat'        => $item['satuan_berat'],
            'nilai_satuan'        => $item['nilai_satuan'],
            'bisa_dipecah'        => $bisaDipecah,
        ];
    }
}