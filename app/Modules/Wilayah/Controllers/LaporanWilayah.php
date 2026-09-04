<?php

namespace App\Modules\Wilayah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Wilayah\Models\MasterGudangWilayahModel;
use App\Libraries\ActivityLogger;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanWilayah extends BaseController
{
    protected $gudangModel;

    public function __construct()
    {
        $this->gudangModel = new MasterGudangWilayahModel();
        helper(['auth', 'format']);
    }

    public function index()
    {
        $isAdmin      = function_exists('in_groups') ? in_groups('Administrator') : true;
        $userGudangId = (function_exists('user') && user()) ? user()->id_gudang_wilayah : null;

        $cacheKey  = 'gudang_wilayah_list_all';
        $allGudang = cache()->get($cacheKey);

        if ($allGudang === null) {
            $allGudang = $this->gudangModel->findAll();
            cache()->save($cacheKey, $allGudang, 3600);
        }

        if (!$isAdmin && $userGudangId) {
            $gudang = array_filter($allGudang, function ($g) use ($userGudangId) {
                return $g['id'] == $userGudangId;
            });
        } else {
            $gudang = $allGudang;
        }

        $data = [
            'title'        => 'Laporan Gudang Wilayah',
            'gudang'       => $gudang,
            'isAdmin'      => $isAdmin,
            'userGudangId' => $userGudangId
        ];
        return view('App\Modules\Wilayah\Views\laporan\index', $data);
    }

    /**
     * Cek apakah perlu include data internal:
     * Hanya kalau filter id_gudang kosong (Seluruh Gudang) dan user adalah Admin
     */
    private function includeInternal()
    {
        $idGudang = $this->request->getVar('id_gudang');
        $isAdmin  = function_exists('in_groups') ? in_groups('Administrator') : false;
        return $isAdmin && empty($idGudang);
    }

    /**
     * Selalu return int
     */
    private function getTotalCount(): int
    {
        $idGudang  = $this->request->getVar('id_gudang');
        $provinsi  = $this->request->getVar('provinsi');
        $jenis     = $this->request->getVar('jenis');
        $startDate = $this->request->getVar('start_date');
        $endDate   = $this->request->getVar('end_date');

        if (!in_groups('Administrator')) {
            $idGudang = user()->id_gudang_wilayah;
        }

        $db = \Config\Database::connect();

        if ($jenis == 'stok') {
            $builder = $db->table("stok_gudang_wilayah s");
            $builder->where('s.jumlah >', 0);
            if (!empty($provinsi)) {
                $builder->join('master_gudang_wilayah m', 'm.id = s.id_gudang');
                $builder->like('m.provinsi', $provinsi);
            }
            if (!empty($idGudang)) {
                $builder->where('s.id_gudang', $idGudang);
            }
            return $builder->countAllResults();
        }

        $table       = ($jenis == 'keluar') ? 'barang_keluar_wilayah'        : 'barang_masuk_wilayah';
        $detailTable = ($jenis == 'keluar') ? 'detail_barang_keluar_wilayah' : 'detail_barang_masuk_wilayah';
        $foreignKey  = ($jenis == 'keluar') ? 'id_keluar'                    : 'id_masuk';

        $builder = $db->table("$table t");
        $builder->join("$detailTable d", "d.$foreignKey = t.id");
        if (!empty($provinsi)) {
            $builder->join('master_gudang_wilayah m', 'm.id = t.id_gudang');
            $builder->like('m.provinsi', $provinsi);
        }
        $builder->where('t.deleted_at', null);
        if (!empty($idGudang)) {
            $builder->where('t.id_gudang', $idGudang);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $builder->where('t.tanggal >=', $startDate);
            $builder->where('t.tanggal <=', $endDate);
        }
        $count = $builder->countAllResults();

        if ($this->includeInternal()) {
            if ($jenis == 'keluar') {
                $internalBuilder = $db->table('barang_keluar bk')
                    ->join('detail_barang_keluar dk', 'dk.id_barang_keluar = bk.id');
                if (!empty($startDate) && !empty($endDate)) {
                    $internalBuilder->where('bk.tanggal_keluar >=', $startDate);
                    $internalBuilder->where('bk.tanggal_keluar <=', $endDate);
                }
            } else {
                $internalBuilder = $db->table('barang_masuk bm')
                    ->join('batch b', 'b.id_barang_masuk = bm.id');
                if (!empty($startDate) && !empty($endDate)) {
                    $internalBuilder->where('bm.tanggal_masuk >=', $startDate);
                    $internalBuilder->where('bm.tanggal_masuk <=', $endDate);
                }
            }
            $count += (int) $internalBuilder->countAllResults();
        }

        return $count;
    }

    /**
     * Selalu return array of rows (sudah handle UNION)
     */
    private function getRows(int $start, int $length): array
    {
        $idGudang  = $this->request->getVar('id_gudang');
        $provinsi  = $this->request->getVar('provinsi');
        $jenis     = $this->request->getVar('jenis');
        $startDate = $this->request->getVar('start_date');
        $endDate   = $this->request->getVar('end_date');

        if (!in_groups('Administrator')) {
            $idGudang = user()->id_gudang_wilayah;
        }

        $db = \Config\Database::connect();

        // ── STOK ─────────────────────────────────────────────────────────────
        if ($jenis == 'stok') {
            $builder = $db->table("stok_gudang_wilayah s");
            $builder->select('s.*, m.nama as nama_gudang, m.kota, m.provinsi, brg.nama_barang, brg.kode_barang, kat.nama_kategori as kategori, brg.satuan, brg.berat_per_satuan as berat');
            $builder->join('master_gudang_wilayah m', 'm.id = s.id_gudang');
            $builder->join('master_barang_wilayah brg', 'brg.id = s.id_barang');
            $builder->join('kategori kat', 'kat.id = brg.id_kategori', 'left');
            $builder->where('s.jumlah >', 0);
            if (!empty($idGudang)) $builder->where('s.id_gudang', $idGudang);
            if (!empty($provinsi)) $builder->like('m.provinsi', $provinsi);
            $builder->orderBy('m.nama', 'ASC')->orderBy('brg.nama_barang', 'ASC');

            $builder->limit($length, $start);
            return $builder->get()->getResultArray();
        }

        // ── MASUK / KELUAR ───────────────────────────────────────────────────
        $table       = ($jenis == 'keluar') ? 'barang_keluar_wilayah'        : 'barang_masuk_wilayah';
        $detailTable = ($jenis == 'keluar') ? 'detail_barang_keluar_wilayah' : 'detail_barang_masuk_wilayah';
        $foreignKey  = ($jenis == 'keluar') ? 'id_keluar'                    : 'id_masuk';

        $builder = $db->table("$table t");

        if ($jenis == 'keluar') {
            $builder->select('t.id, t.nomor_dokumen, t.tanggal, t.keterangan, t.created_at, t.updated_at,
                d.jumlah, d.satuan,
                COALESCE(d.berat_per_satuan, s.berat_per_satuan, 0) as berat_referensi,
                COALESCE(d.satuan_berat, s.satuan_berat, "Kg") as satuan_berat,
                m.nama as nama_gudang, m.kota, m.provinsi,
                brg.nama_barang, brg.kode_barang,
                kat.nama_kategori as kategori,
                u.username as nama_user,
                t.tujuan as tujuan,
                NULL as donatur,
                NULL as harga_satuan,
                NULL as subtotal_nilai');
            $builder->join('users u', 'u.id = t.created_by', 'left');
        } else {
            $builder->select('t.id, t.nomor_dokumen, t.tanggal, t.keterangan, t.created_at, t.updated_at,
                d.jumlah, d.satuan, d.berat_per_satuan,
                COALESCE(d.satuan_berat, "Kg") as satuan_berat,
                d.harga_satuan, d.subtotal_nilai,
                m.nama as nama_gudang, m.kota, m.provinsi,
                brg.nama_barang, brg.kode_barang,
                kat.nama_kategori as kategori,
                u.username as nama_user,
                NULL as tujuan,
                dn.nama_donatur as donatur,
                NULL as berat_referensi');
            $builder->join('donatur dn', 'dn.id = t.id_donatur', 'left');
            $builder->join('users u', 'u.id = t.created_by', 'left');
        }

        $builder->join('master_gudang_wilayah m', 'm.id = t.id_gudang');
        $builder->join("$detailTable d", "d.$foreignKey = t.id");
        $builder->join('master_barang_wilayah brg', 'brg.id = d.id_barang');
        $builder->join('kategori kat', 'kat.id = brg.id_kategori', 'left');

        if ($jenis == 'keluar') {
            $builder->join('stok_gudang_wilayah s', 's.id_gudang = t.id_gudang AND s.id_barang = d.id_barang', 'left');
        }

        $builder->where('t.deleted_at', null);
        if (!empty($idGudang)) $builder->where('t.id_gudang', $idGudang);
        if (!empty($provinsi)) $builder->like('m.provinsi', $provinsi);
        if (!empty($startDate) && !empty($endDate)) {
            $builder->where('t.tanggal >=', $startDate);
            $builder->where('t.tanggal <=', $endDate);
        }
        $builder->orderBy('t.tanggal', 'DESC');

        if ($this->includeInternal()) {
            $sqlWilayah = $builder->getCompiledSelect();

            $dateFilter = '';
            if (!empty($startDate) && !empty($endDate)) {
                $dateFilter = "AND tanggal >= '{$startDate}' AND tanggal <= '{$endDate}'";
            }

            if ($jenis == 'keluar') {
                $sqlInternal = "
                    SELECT
                        bk.id,
                        bk.nomor_transaksi                                     as nomor_dokumen,
                        bk.tanggal_keluar                                      as tanggal,
                        bk.keterangan, bk.created_at, bk.updated_at,
                        dk.jumlah_keluar                                       as jumlah,
                        COALESCE(b.satuan, brg.satuan)                         as satuan,
                        COALESCE(b.berat_per_satuan, brg.berat_per_satuan, 0)  as berat_referensi,
                        COALESCE(b.satuan_berat, brg.satuan_berat, 'Kg')       as satuan_berat,
                        'Gudang Pusat'                                         as nama_gudang,
                        'Jakarta'                                              as kota,
                        'DKI Jakarta'                                          as provinsi,
                        COALESCE(b.nama_barang, brg.nama_barang)               as nama_barang,
                        brg.kode_barang,
                        kat.nama_kategori                                      as kategori,
                        u.username                                             as nama_user,
                        bk.tujuan_penyaluran                                   as tujuan,
                        NULL                                                   as donatur,
                        NULL                                                   as harga_satuan,
                        NULL                                                   as subtotal_nilai
                    FROM barang_keluar bk
                    JOIN detail_barang_keluar dk ON dk.id_barang_keluar = bk.id
                    JOIN batch b ON b.id = dk.id_batch
                    LEFT JOIN barang brg ON brg.id = b.id_barang
                    LEFT JOIN kategori kat ON kat.id = brg.id_kategori
                    LEFT JOIN users u ON u.id = bk.id_user
                    WHERE 1=1
                    " . (!empty($startDate) && !empty($endDate) ? "AND bk.tanggal_keluar >= '{$startDate}' AND bk.tanggal_keluar <= '{$endDate}'" : "");
            } else {
                $sqlInternal = "
                    SELECT
                        bm.id,
                        bm.nomor_transaksi                                      as nomor_dokumen,
                        bm.tanggal_masuk                                        as tanggal,
                        bm.keterangan,
                        bm.created_at,
                        bm.updated_at,
                        b.jumlah_awal                                           as jumlah,
                        COALESCE(b.satuan, brg.satuan)                          as satuan,
                        COALESCE(b.berat_per_satuan, brg.berat_per_satuan)      as berat_per_satuan,
                        COALESCE(b.satuan_berat, brg.satuan_berat, 'Kg')        as satuan_berat,
                        b.nilai_satuan                                          as harga_satuan,
                        (b.jumlah_awal * b.nilai_satuan)                        as subtotal_nilai,
                        'Gudang Pusat'                                          as nama_gudang,
                        'Jakarta'                                               as kota,
                        'DKI Jakarta'                                           as provinsi,
                        COALESCE(b.nama_barang, brg.nama_barang)                as nama_barang,
                        brg.kode_barang,
                        kat.nama_kategori                                       as kategori,
                        u.username                                              as nama_user,
                        NULL                                                    as tujuan,
                        dn.nama_donatur                                         as donatur,
                        NULL                                                    as berat_referensi
                    FROM barang_masuk bm
                    JOIN batch b ON b.id_barang_masuk = bm.id
                    LEFT JOIN barang brg ON brg.id = b.id_barang
                    LEFT JOIN kategori kat ON kat.id = brg.id_kategori
                    LEFT JOIN donatur dn ON dn.id = bm.id_donatur
                    LEFT JOIN users u ON u.id = bm.id_user
                    WHERE 1=1
                    " . (!empty($startDate) && !empty($endDate) ? "AND bm.tanggal_masuk >= '{$startDate}' AND bm.tanggal_masuk <= '{$endDate}'" : "");
            }

            $sql = "SELECT * FROM (({$sqlWilayah}) UNION ALL ({$sqlInternal})) AS combined
                    ORDER BY tanggal DESC
                    LIMIT {$length} OFFSET {$start}";
            return $db->query($sql)->getResultArray();
        }

        $builder->limit($length, $start);
        return $builder->get()->getResultArray();
    }

    public function ajaxData()
    {
        try {
            $jenis  = $this->request->getVar('jenis');
            $length = (int)($this->request->getPost('length') ?? 10);
            $start  = (int)($this->request->getPost('start')  ?? 0);

            $totalRecords    = $this->getTotalCount();
            $filteredRecords = $totalRecords; // search belum di-implement untuk UNION, sama dengan total

            $data       = $this->getRows($start, $length);
            $resultData = [];
            $no         = $start + 1;

            foreach ($data as $row) {
                if ($jenis == 'stok') {
                    $statusHtml = $row['jumlah'] > 0
                        ? '<span class="wh-badge aman"><i class="fa-solid fa-check-circle"></i>Tersedia</span>'
                        : '<span class="wh-badge expired"><i class="fa-solid fa-times-circle"></i>Habis</span>';

                    $resultData[] = [
                        $no++,
                        esc($row['nama_gudang']),
                        esc($row['kode_barang']) . '<br>' . esc($row['nama_barang']),
                        esc($row['kategori']),
                        number_format($row['jumlah'], 0, ',', '.'),
                        esc($row['satuan']),
                        esc($row['berat']) . ' kg',
                        $statusHtml
                    ];
                } elseif ($jenis == 'masuk') {
                    $beratPerSat = (float)($row['berat_per_satuan'] ?? 0);
                    $satuanBerat = $row['satuan_berat'] ?? 'Kg';
                    $beratInKg   = strtolower($satuanBerat) === 'gram' ? ($beratPerSat / 1000) : $beratPerSat;
                    $totalBerat  = (float)$row['jumlah'] * $beratInKg;
                    $hargaSatuan = (float)($row['harga_satuan'] ?? 0);
                    $subtotalVal = (float)($row['subtotal_nilai'] ?? ($row['jumlah'] * $hargaSatuan));

                    $resultData[] = [
                        $no++,
                        date('d/m/Y', strtotime($row['tanggal'])),
                        esc($row['nomor_dokumen']),
                        esc($row['nama_gudang']),
                        esc($row['donatur'] ?? '-'),
                        esc($row['kode_barang']) . '<br>' . esc($row['nama_barang']),
                        esc($row['kategori']),
                        number_format($row['jumlah'], 0, ',', '.'),
                        esc($row['satuan']),
                        $beratPerSat > 0 ? number_format($beratPerSat, 2, ',', '.') . ' ' . $satuanBerat : '-',
                        $totalBerat  > 0 ? number_format($totalBerat,  2, ',', '.') . ' Kg'              : '-',
                        esc($row['nama_user'])
                    ];
                } elseif ($jenis == 'keluar') {
                    $beratRef    = (float)($row['berat_referensi'] ?? 0);
                    $satuanBerat = $row['satuan_berat'] ?? 'Kg';
                    $beratInKg   = strtolower($satuanBerat) === 'gram' ? ($beratRef / 1000) : $beratRef;
                    $totalBerat  = (float)$row['jumlah'] * $beratInKg;

                    $resultData[] = [
                        $no++,
                        date('d/m/Y', strtotime($row['tanggal'])),
                        esc($row['nomor_dokumen']),
                        esc($row['nama_gudang']),
                        esc($row['tujuan'] ?? '-'),
                        esc($row['kode_barang']) . '<br>' . esc($row['nama_barang']),
                        esc($row['kategori']),
                        number_format($row['jumlah'], 0, ',', '.'),
                        esc($row['satuan']),
                        $beratRef   > 0 ? number_format($beratRef,   2, ',', '.') . ' ' . $satuanBerat : '-',
                        $totalBerat > 0 ? number_format($totalBerat, 2, ',', '.') . ' Kg'              : '-',
                        esc($row['nama_user'])
                    ];
                }
            }

            return $this->response->setJSON([
                'draw'            => intval($this->request->getPost('draw')),
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data'            => $resultData,
                csrf_token()      => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', '[LaporanWilayah] ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                'error'   => 'Internal Server Error',
                'message' => ENVIRONMENT === 'development' ? $e->getMessage() : 'Terjadi kesalahan sistem saat memuat laporan.'
            ]);
        }
    }

    private function getLaporanTitle()
    {
        $idGudang = $this->request->getVar('id_gudang');
        $jenisStr = strtoupper(str_replace('_', ' ', $this->request->getVar('jenis')));

        if (!in_groups('Administrator')) {
            $idGudang = user()->id_gudang_wilayah;
        }

        if (empty($idGudang)) {
            return "LAPORAN BARANG $jenisStr - SELURUH GUDANG (PUSAT + WILAYAH)";
        } else {
            $gudang     = $this->gudangModel->find($idGudang);
            $namaGudang = strtoupper($gudang['nama'] ?? 'GUDANG');
            return "LAPORAN BARANG $jenisStr - $namaGudang";
        }
    }

    private function getAllRows(): array
    {
        // Ambil semua data tanpa limit untuk PDF/Excel
        $idGudang  = $this->request->getVar('id_gudang');
        $provinsi  = $this->request->getVar('provinsi');
        $jenis     = $this->request->getVar('jenis');
        $startDate = $this->request->getVar('start_date');
        $endDate   = $this->request->getVar('end_date');

        if (!in_groups('Administrator')) {
            $idGudang = user()->id_gudang_wilayah;
        }

        $db = \Config\Database::connect();

        if ($jenis == 'stok') {
            $builder = $db->table("stok_gudang_wilayah s");
            $builder->select('s.*, m.nama as nama_gudang, m.kota, m.provinsi, brg.nama_barang, brg.kode_barang, kat.nama_kategori as kategori, brg.satuan, brg.berat_per_satuan as berat');
            $builder->join('master_gudang_wilayah m', 'm.id = s.id_gudang');
            $builder->join('master_barang_wilayah brg', 'brg.id = s.id_barang');
            $builder->join('kategori kat', 'kat.id = brg.id_kategori', 'left');
            $builder->where('s.jumlah >', 0);
            if (!empty($idGudang)) $builder->where('s.id_gudang', $idGudang);
            if (!empty($provinsi)) $builder->like('m.provinsi', $provinsi);
            $builder->orderBy('m.nama', 'ASC')->orderBy('brg.nama_barang', 'ASC');

            return $builder->get()->getResultArray();
        }

        $table       = ($jenis == 'keluar') ? 'barang_keluar_wilayah'        : 'barang_masuk_wilayah';
        $detailTable = ($jenis == 'keluar') ? 'detail_barang_keluar_wilayah' : 'detail_barang_masuk_wilayah';
        $foreignKey  = ($jenis == 'keluar') ? 'id_keluar'                    : 'id_masuk';

        $builder = $db->table("$table t");
        if ($jenis == 'keluar') {
            $builder->select('t.id, t.nomor_dokumen, t.tanggal, t.keterangan, t.created_at, t.updated_at,
                d.jumlah, d.satuan,
                COALESCE(d.berat_per_satuan, s.berat_per_satuan, 0) as berat_referensi,
                COALESCE(d.satuan_berat, s.satuan_berat, "Kg") as satuan_berat,
                m.nama as nama_gudang, m.kota, m.provinsi,
                brg.nama_barang, brg.kode_barang, kat.nama_kategori as kategori,
                u.username as nama_user, t.tujuan as tujuan,
                NULL as donatur, NULL as harga_satuan, NULL as subtotal_nilai');
            $builder->join('users u', 'u.id = t.created_by', 'left');
        } else {
            $builder->select('t.id, t.nomor_dokumen, t.tanggal, t.keterangan, t.created_at, t.updated_at,
                d.jumlah, d.satuan, d.berat_per_satuan,
                COALESCE(d.satuan_berat, "Kg") as satuan_berat,
                d.harga_satuan, d.subtotal_nilai,
                m.nama as nama_gudang, m.kota, m.provinsi,
                brg.nama_barang, brg.kode_barang, kat.nama_kategori as kategori,
                u.username as nama_user, NULL as tujuan, dn.nama_donatur as donatur, NULL as berat_referensi');
            $builder->join('donatur dn', 'dn.id = t.id_donatur', 'left');
            $builder->join('users u', 'u.id = t.created_by', 'left');
        }
        $builder->join('master_gudang_wilayah m', 'm.id = t.id_gudang');
        $builder->join("$detailTable d", "d.$foreignKey = t.id");
        $builder->join('master_barang_wilayah brg', 'brg.id = d.id_barang');
        $builder->join('kategori kat', 'kat.id = brg.id_kategori', 'left');
        if ($jenis == 'keluar') {
            $builder->join('stok_gudang_wilayah s', 's.id_gudang = t.id_gudang AND s.id_barang = d.id_barang', 'left');
        }
        $builder->where('t.deleted_at', null);
        if (!empty($idGudang)) $builder->where('t.id_gudang', $idGudang);
        if (!empty($provinsi)) $builder->like('m.provinsi', $provinsi);
        if (!empty($startDate) && !empty($endDate)) {
            $builder->where('t.tanggal >=', $startDate);
            $builder->where('t.tanggal <=', $endDate);
        }
        $builder->orderBy('t.tanggal', 'DESC');

        if ($this->includeInternal()) {
            $sqlWilayah = $builder->getCompiledSelect();
            if ($jenis == 'keluar') {
                $sqlInternal = "
                    SELECT bk.id, bk.nomor_transaksi as nomor_dokumen, bk.tanggal_keluar as tanggal,
                        bk.keterangan, bk.created_at, bk.updated_at,
                        dk.jumlah_keluar as jumlah,
                        COALESCE(b.satuan, brg.satuan) as satuan,
                        COALESCE(b.berat_per_satuan, brg.berat_per_satuan, 0) as berat_referensi,
                        COALESCE(b.satuan_berat, brg.satuan_berat, 'Kg') as satuan_berat,
                        'Gudang Pusat' as nama_gudang, 'Jakarta' as kota, 'DKI Jakarta' as provinsi,
                        COALESCE(b.nama_barang, brg.nama_barang) as nama_barang, brg.kode_barang,
                        kat.nama_kategori as kategori, u.username as nama_user,
                        bk.tujuan_penyaluran as tujuan, NULL as donatur,
                        NULL as harga_satuan, NULL as subtotal_nilai
                    FROM barang_keluar bk
                    JOIN detail_barang_keluar dk ON dk.id_barang_keluar = bk.id
                    JOIN batch b ON b.id = dk.id_batch
                    LEFT JOIN barang brg ON brg.id = b.id_barang
                    LEFT JOIN kategori kat ON kat.id = brg.id_kategori
                    LEFT JOIN users u ON u.id = bk.id_user
                    " . (!empty($startDate) && !empty($endDate) ? "WHERE bk.tanggal_keluar >= '{$startDate}' AND bk.tanggal_keluar <= '{$endDate}'" : "");
            } else {
                $sqlInternal = "
                    SELECT
                        bm.id,
                        bm.nomor_transaksi                                      as nomor_dokumen,
                        bm.tanggal_masuk                                        as tanggal,
                        bm.keterangan,
                        bm.created_at,
                        bm.updated_at,
                        b.jumlah_awal                                           as jumlah,
                        COALESCE(b.satuan, brg.satuan)                          as satuan,
                        COALESCE(b.berat_per_satuan, brg.berat_per_satuan)      as berat_per_satuan,
                        COALESCE(b.satuan_berat, brg.satuan_berat, 'Kg')        as satuan_berat,
                        b.nilai_satuan                                          as harga_satuan,
                        (b.jumlah_awal * b.nilai_satuan)                        as subtotal_nilai,
                        'Gudang Pusat'                                          as nama_gudang,
                        'Jakarta'                                               as kota,
                        'DKI Jakarta'                                           as provinsi,
                        COALESCE(b.nama_barang, brg.nama_barang)                as nama_barang,
                        brg.kode_barang,
                        kat.nama_kategori                                       as kategori,
                        u.username                                              as nama_user,
                        NULL                                                    as tujuan,
                        dn.nama_donatur                                         as donatur,
                        NULL                                                    as berat_referensi
                    FROM barang_masuk bm
                    JOIN batch b ON b.id_barang_masuk = bm.id
                    LEFT JOIN barang brg ON brg.id = b.id_barang
                    LEFT JOIN kategori kat ON kat.id = brg.id_kategori
                    LEFT JOIN donatur dn ON dn.id = bm.id_donatur
                    LEFT JOIN users u ON u.id = bm.id_user
                    " . (!empty($startDate) && !empty($endDate) ? "WHERE bm.tanggal_masuk >= '{$startDate}' AND bm.tanggal_masuk <= '{$endDate}'" : "");
            }
            return $db->query("SELECT * FROM (({$sqlWilayah}) UNION ALL ({$sqlInternal})) AS combined ORDER BY tanggal DESC")->getResultArray();
        }

        return $builder->get()->getResultArray();
    }

    public function pdf()
    {
        $dataLaporan = $this->getAllRows();
        $jenis       = $this->request->getVar('jenis');
        $title       = $this->getLaporanTitle();

        $data = [
            'title'   => $title,
            'laporan' => $dataLaporan,
            'jenis'   => $jenis,
            'filters' => $this->request->getGet()
        ];

        $html = view('App\Modules\Wilayah\Views\laporan\pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', [FCPATH, ROOTPATH]);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Wilayah_{$jenis}_" . date('Ymd_His') . ".pdf", ["Attachment" => false]);
    }

    public function excel()
    {
        $dataLaporan = $this->getAllRows();
        $jenis       = $this->request->getVar('jenis');
        $title       = $this->getLaporanTitle();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        if ($jenis == 'stok') {
            $headers = ['No', 'Gudang', 'Barang', 'Kategori', 'Jumlah', 'Satuan', 'Berat'];
        } elseif ($jenis == 'masuk') {
            $headers = ['No', 'Tanggal', 'Kode Transaksi', 'Gudang', 'Donatur', 'Barang', 'Kategori', 'Jumlah Masuk', 'Satuan', 'Berat/Satuan', 'Total Berat (Kg)', 'Harga Satuan (Rp)', 'Total Nilai (Rp)', 'Operator'];
        } else {
            $headers = ['No', 'Tanggal', 'Kode Transaksi', 'Gudang', 'Tujuan', 'Barang', 'Kategori', 'Jumlah Keluar', 'Satuan', 'Berat Referensi', 'Total Berat (Kg)', 'Operator'];
        }

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $col++;
        }

        $row = 4;
        $no  = 1;
        foreach ($dataLaporan as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            if ($jenis == 'stok') {
                $sheet->setCellValue('B' . $row, $item['nama_gudang']);
                $sheet->setCellValue('C' . $row, $item['kode_barang'] . ' - ' . $item['nama_barang']);
                $sheet->setCellValue('D' . $row, $item['kategori']);
                $sheet->setCellValue('E' . $row, $item['jumlah']);
                $sheet->setCellValue('F' . $row, $item['satuan']);
                $sheet->setCellValue('G' . $row, $item['berat'] . ' kg');
            } elseif ($jenis == 'masuk') {
                $beratPerSat = (float)($item['berat_per_satuan'] ?? 0);
                $satuanBerat = $item['satuan_berat'] ?? 'Kg';
                $beratInKg   = strtolower($satuanBerat) === 'gram' ? ($beratPerSat / 1000) : $beratPerSat;
                $totalBerat  = (float)$item['jumlah'] * $beratInKg;
                $hargaSatuan = (float)($item['harga_satuan'] ?? 0);
                $subtotalVal = (float)($item['subtotal_nilai'] ?? ($item['jumlah'] * $hargaSatuan));

                $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($item['tanggal'])));
                $sheet->setCellValue('C' . $row, $item['nomor_dokumen']);
                $sheet->setCellValue('D' . $row, $item['nama_gudang']);
                $sheet->setCellValue('E' . $row, $item['donatur'] ?? '-');
                $sheet->setCellValue('F' . $row, $item['kode_barang'] . ' - ' . $item['nama_barang']);
                $sheet->setCellValue('G' . $row, $item['kategori']);
                $sheet->setCellValue('H' . $row, $item['jumlah']);
                $sheet->setCellValue('I' . $row, $item['satuan']);
                $sheet->setCellValue('J' . $row, $beratPerSat > 0 ? number_format($beratPerSat, 2, ',', '.') . ' ' . $satuanBerat : '-');
                $sheet->setCellValue('K' . $row, $totalBerat);
                $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->setCellValue('L' . $row, $hargaSatuan);
                $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->setCellValue('M' . $row, $subtotalVal);
                $sheet->getStyle('M' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->setCellValue('N' . $row, $item['nama_user']);
            } elseif ($jenis == 'keluar') {
                $beratRef    = (float)($item['berat_referensi'] ?? 0);
                $satuanBerat = $item['satuan_berat'] ?? 'Kg';
                $beratInKg   = strtolower($satuanBerat) === 'gram' ? ($beratRef / 1000) : $beratRef;
                $totalBerat  = (float)$item['jumlah'] * $beratInKg;

                $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($item['tanggal'])));
                $sheet->setCellValue('C' . $row, $item['nomor_dokumen']);
                $sheet->setCellValue('D' . $row, $item['nama_gudang']);
                $sheet->setCellValue('E' . $row, $item['tujuan'] ?? '-');
                $sheet->setCellValue('F' . $row, $item['kode_barang'] . ' - ' . $item['nama_barang']);
                $sheet->setCellValue('G' . $row, $item['kategori']);
                $sheet->setCellValue('H' . $row, $item['jumlah']);
                $sheet->setCellValue('I' . $row, $item['satuan']);
                $sheet->setCellValue('J' . $row, $beratRef > 0 ? number_format($beratRef, 2, ',', '.') . ' ' . $satuanBerat : '-');
                $sheet->setCellValue('K' . $row, $totalBerat);
                $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->setCellValue('L' . $row, $item['nama_user']);
            }
            $row++;
        }

        if ($jenis == 'masuk' && !empty($dataLaporan)) {
            $lastDataRow = $row - 1;
            $sheet->setCellValue('A' . $row, 'TOTAL REKAPITULASI BARANG MASUK');
            $sheet->mergeCells("A{$row}:G{$row}");
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('H' . $row, "=SUM(H4:H{$lastDataRow})");
            $sheet->getStyle('H' . $row)->getFont()->setBold(true);
            $sheet->setCellValue('K' . $row, "=SUM(K4:K{$lastDataRow})");
            $sheet->getStyle('K' . $row)->getFont()->setBold(true);
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0.00" Kg"');
            $sheet->setCellValue('M' . $row, "=SUM(M4:M{$lastDataRow})");
            $sheet->getStyle('M' . $row)->getFont()->setBold(true);
            $sheet->getStyle('M' . $row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        } elseif ($jenis == 'keluar' && !empty($dataLaporan)) {
            $lastDataRow = $row - 1;
            $sheet->setCellValue('A' . $row, 'TOTAL REKAPITULASI BARANG KELUAR');
            $sheet->mergeCells("A{$row}:G{$row}");
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('H' . $row, "=SUM(H4:H{$lastDataRow})");
            $sheet->getStyle('H' . $row)->getFont()->setBold(true);
            $sheet->setCellValue('K' . $row, "=SUM(K4:K{$lastDataRow})");
            $sheet->getStyle('K' . $row)->getFont()->setBold(true);
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0.00" Kg"');
        }

        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer   = new Xlsx($spreadsheet);
        $filename = "Laporan_Wilayah_{$jenis}_" . date('Ymd_His') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}