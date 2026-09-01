<?php

namespace App\Modules\Transactions\Services;

use App\Modules\Transactions\Models\BarangMasukModel;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\BarangModel;

/**
 * Service Layer — Donasi Masuk
 *
 * Memindahkan seluruh business logic dari BarangMasuk controller ke sini,
 * sehingga controller hanya menangani HTTP request/response.
 *
 * Tanggung jawab:
 *  - validateAndCleanItems()  → validasi + normalisasi array item (FIX #6)
 *  - store()                  → simpan transaksi + batch baru  (FIX #7)
 *  - update()                 → perbarui transaksi + batch     (FIX #7)
 */
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

    // =========================================================
    // FIX #6 — Ekstrak validasi item ke satu private method
    //
    // store() dan update() dulu masing-masing punya ~80 baris
    // validasi yang hampir identik 100%. Sekarang keduanya
    // memanggil validateAndCleanItems() yang sama.
    // =========================================================

    /**
     * Validasi dan normalisasi array item dari form.
     *
     * @param  array  $items        Raw POST items
     * @param  string $tanggalMasuk Tanggal transaksi (YYYY-MM-DD)
     * @return array{ok: bool, errors: array<string,string>, items: array<int,array<string,mixed>>}
     *         'ok'     → true jika semua item valid
     *         'errors' → ['items' => '...pesan...'] jika ada error
     *         'items'  → array item yang sudah dibersihkan (hanya terisi jika ok=true)
     */
    public function validateAndCleanItems(array $items, string $tanggalMasuk): array
    {
        if (empty($items)) {
            return ['ok' => false, 'errors' => ['items' => 'Minimal harus ada 1 barang.'], 'items' => []];
        }

        $kategoriValid    = array_column($this->kategoriModel->findAll(), 'nama_kategori');
        $satuanBeratValid = ['Gram', 'Kg', 'ml', 'Liter'];

        // Satuan valid — case-insensitive check dilakukan via strtoupper()
        $satuanValidUpper = ['PCS', 'KOTAK', 'KARUNG', 'DUS', 'BOX', 'PACK', 'BOTOL',
                             'KALENG', 'TRAY', 'POUCH', 'SACHET', 'RENCENG', 'KANTONG',
                             'REPACK', 'KG'];

        $cleanItems = [];

        foreach ($items as $key => $item) {
            // ── Baca & trim field dasar ──────────────────────────────────────
            $namaBarang         = trim($item['nama_barang'] ?? '');
            $kategori           = trim($item['kategori'] ?? '');
            $satuan             = trim($item['satuan'] ?? '');
            $beratPerSatuan     = (float) ($item['berat_per_satuan'] ?? 0);
            $satuanBerat        = trim($item['satuan_berat'] ?? '');
            $tanggalKedaluwarsa = trim($item['tanggal_kedaluwarsa'] ?? '');
            $bisaDipecah        = (int) ($item['bisa_dipecah'] ?? 0);

            // ── Hitung jumlah (kemasan atau satuan langsung) ─────────────────
            $jumlahCtn    = trim($item['jumlah_ctn'] ?? '');
            $isiPerCtn    = trim($item['isi_per_ctn'] ?? '');
            $jumlahCtnVal = ($jumlahCtn !== '' && (int) $jumlahCtn > 0) ? (int) $jumlahCtn : null;
            $isiPerCtnVal = ($isiPerCtn !== '' && (int) $isiPerCtn > 0) ? (int) $isiPerCtn : null;
            $menggunakanKemasan = ($jumlahCtnVal !== null && $isiPerCtnVal !== null) ? 1 : 0;

            $jumlah = ($menggunakanKemasan === 1)
                ? $jumlahCtnVal * $isiPerCtnVal
                : (int) ($item['jumlah'] ?? 0);

            // ── Validasi field wajib ──────────────────────────────────────────
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

            // ── Parse nilai satuan (stripping format Rp) ─────────────────────
            $rawNilai    = $item['nilai_satuan'] ?? '0';
            $rawNilai    = str_replace(['Rp', ' ', '.'], '', $rawNilai);
            $rawNilai    = str_replace(',', '.', $rawNilai);
            $nilaiSatuan = (float) $rawNilai;
            if ($nilaiSatuan < 0) {
                return $this->validationError("{$prefix} Nilai Satuan tidak boleh negatif.");
            }

            // ── Item valid — simpan ke cleanItems ─────────────────────────────
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

    // =========================================================
    // FIX #7 — Business logic dipindah dari controller
    //
    // store() dan update() di controller turun dari ~230 baris
    // menjadi ~20 baris masing-masing — hanya HTTP glue code.
    // Semua kalkulasi, lookup/create barang, dan batch numbering
    // ada di sini.
    // =========================================================

    /**
     * Simpan transaksi baru beserta batch-nya.
     *
     * @param  array $headerData ['id_donatur', 'id_user', 'tanggal_masuk', 'eta', 'keterangan']
     * @param  array $cleanItems Output dari validateAndCleanItems()['items']
     * @return array{ok: bool, errors: array, nomor_transaksi: string, total_item: int}
     */
    public function store(array $headerData, array $cleanItems): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Nomor transaksi — atomic (SELECT … FOR UPDATE di dalam transaksi)
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

        // Resolve / buat barang master
        [$kategoriMap, $barangMap, $lastBrgNumber] = $this->loadMasterMaps($cleanItems);

        // Nomor batch — atomic (SELECT … FOR UPDATE)
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

    /**
     * Perbarui transaksi yang sudah ada beserta batch-nya.
     *
     * @param  int   $id         ID barang_masuk
     * @param  array $headerData ['id_donatur', 'tanggal_masuk', 'eta', 'keterangan']
     * @param  array $cleanItems Output dari validateAndCleanItems()['items']
     * @return array{ok: bool, errors: array, total_item: int}
     */
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

        // Map batch yang sudah ada (id → row)
        $existingBatches  = $this->batchModel->where('id_barang_masuk', $id)->findAll();
        $existingBatchMap = array_column($existingBatches, null, 'id');

        // Hapus batch yang tidak ada di submittedIds
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

        // Resolve / buat barang master
        [$kategoriMap, $barangMap, $lastBrgNumber] = $this->loadMasterMaps($cleanItems);

        // Nomor batch baru — atomic
        $today       = date('Ymd');
        $prefixBatch = "BT-{$today}-";
        $lastNumber  = $this->getLastBatchNumber($db, $prefixBatch);

        foreach ($cleanItems as $item) {
            [$idBarang, $bisaDipecah, $lastBrgNumber] = $this->resolveOrCreateBarang(
                $item, $kategoriMap, $barangMap, $lastBrgNumber
            );

            if (!empty($item['id']) && isset($existingBatchMap[$item['id']])) {
                // ── Update batch yang sudah ada ───────────────────────────────
                $result = $this->updateExistingBatch(
                    $existingBatchMap[$item['id']], $item,
                    $idBarang, $bisaDipecah, $headerData['tanggal_masuk'], $db
                );
                if (!$result['ok']) {
                    return ['ok' => false, 'errors' => $result['errors'], 'total_item' => 0];
                }
            } else {
                // ── Insert batch baru saat edit ───────────────────────────────
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

    /** Buat array hasil error validasi yang seragam. */
    private function validationError(string $message): array
    {
        return ['ok' => false, 'errors' => ['items' => $message], 'items' => []];
    }

    /**
     * Muat kategori map dan barang map dari DB, plus angka terakhir kode barang.
     *
     * @return array [kategoriMap, barangMap, lastBrgNumber]
     */
    private function loadMasterMaps(array $cleanItems): array
    {
        // Kategori map: nama_kategori → id
        $kategoriList = $this->kategoriModel->findAll();
        $kategoriMap  = array_column($kategoriList, 'id', 'nama_kategori');

        // Barang map: strtolower(nama)_bisaDipecah → ['id', 'bisa_dipecah']
        $namaBarangUnikLower = array_map('strtolower', array_unique(array_column($cleanItems, 'nama_barang')));
        $barangList = [];
        if (!empty($namaBarangUnikLower)) {
            $barangList = $this->barangModel
                ->select('id, nama_barang, bisa_dipecah, satuan')
                ->whereIn('nama_barang', $namaBarangUnikLower)
                ->findAll();
        }

        $barangMap = [];
        foreach ($barangList as $b) {
            $key             = strtolower($b['nama_barang']) . '_' . (int) $b['bisa_dipecah'];
            $barangMap[$key] = ['id' => $b['id'], 'bisa_dipecah' => (int) $b['bisa_dipecah']];
        }

        // Angka terakhir kode barang (BRG-XXXXXX)
        $maxBrgRow = \Config\Database::connect()
            ->table('barang')
            ->select('MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num')
            ->where('kode_barang LIKE', 'BRG-%')
            ->get()->getRowArray();
        $lastBrgNumber = $maxBrgRow ? (int) $maxBrgRow['max_num'] : 0;

        return [$kategoriMap, $barangMap, $lastBrgNumber];
    }

    /**
     * Cari barang master yang cocok atau buat baru jika belum ada.
     *
     * @return array [idBarang, bisaDipecah, lastBrgNumber]
     */
    private function resolveOrCreateBarang(
        array $item,
        array $kategoriMap,
        array &$barangMap,
        int $lastBrgNumber
    ): array {
        $key         = strtolower($item['nama_barang']) . '_' . (int) $item['bisa_dipecah'];
        $idKategori  = $kategoriMap[$item['kategori']] ?? 1;

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

    /**
     * Hitung jumlah_awal dan stok_saat_ini berdasarkan apakah barang bisa dipecah (repack).
     *
     * Untuk repack, stok disimpan dalam Kg; untuk barang biasa, dalam satuan aslinya.
     *
     * @return array [jumlahAwal, stokSaatIni]  (keduanya float)
     */
    private function hitungStokAwal(array $item, int $bisaDipecah): array
    {
        if ($bisaDipecah !== 1) {
            $qty = (float) $item['jumlah'];
            return [$qty, $qty];
        }

        // Konversi ke Kg untuk repack
        $berat   = (float) $item['berat_per_satuan'];
        $satuanB = strtolower($item['satuan_berat']);
        $qty     = (float) $item['jumlah'];

        $totalKg = in_array(strtolower($item['satuan']), ['kg', 'repack'])
            ? $qty
            : (($satuanB === 'gram') ? ($qty * $berat / 1000) : ($qty * $berat));

        return [$totalKg, $totalKg];
    }

    /**
     * Ambil nomor urut batch terakhir hari ini dengan FOR UPDATE (atomic).
     */
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

    /**
     * Bangun array data untuk satu baris batch (insert maupun insertBatch).
     */
    private function buildBatchRow(
        array $item,
        int   $idBarangMasuk,
        int   $idBarang,
        string $nomorBatch,
        float $jumlahAwal,
        float $stokSaatIni,
        int   $bisaDipecah,
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

    /**
     * Update batch yang sudah ada — menangani dua kasus:
     *  (a) id_barang berubah  → stok di-reset, barang lama harus belum terpakai
     *  (b) id_barang sama     → delta stok dihitung dari selisih jumlah
     */
    private function updateExistingBatch(
        array $eb,
        array $item,
        int   $idBarang,
        int   $bisaDipecah,
        string $tanggalMasuk,
        \CodeIgniter\Database\BaseConnection $db
    ): array {
        [$submittedQty] = $this->hitungStokAwal($item, $bisaDipecah);

        if ((int) $eb['id_barang'] !== $idBarang) {
            // ── (a) Barang diganti ────────────────────────────────────────────
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
            // ── (b) Barang sama — delta stok ─────────────────────────────────
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

    /**
     * Field batch yang selalu di-update (shared antara kasus barang-sama & barang-ganti).
     */
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