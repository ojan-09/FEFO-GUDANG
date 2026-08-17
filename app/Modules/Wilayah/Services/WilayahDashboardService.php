<?php

namespace App\Modules\Wilayah\Services;

use App\Modules\Wilayah\Models\MasterGudangWilayahModel;
use App\Modules\Wilayah\Models\StokGudangWilayahModel;
use App\Modules\Wilayah\Models\BarangMasukWilayahModel;
use App\Modules\Wilayah\Models\BarangKeluarWilayahModel;

class WilayahDashboardService
{
    protected $gudangModel;
    protected $stokModel;
    protected $masukModel;
    protected $keluarModel;
    protected $db;

    public function __construct()
    {
        $this->db          = \Config\Database::connect();
        $this->gudangModel = new MasterGudangWilayahModel();
        $this->stokModel   = new StokGudangWilayahModel();
        $this->masukModel  = new BarangMasukWilayahModel();
        $this->keluarModel = new BarangKeluarWilayahModel();
    }

    /**
     * 1. Global Summary Cards (Header Top Stats)
     */
    public function getGlobalStats($isAdmin, $userGudangId)
    {
        $today = date('Y-m-d');

        if (!$isAdmin && $userGudangId) {
            $totalGudang    = 1;
            $gudangAktif    = $this->gudangModel->where('id', $userGudangId)->where('status', 'Aktif')->countAllResults();
            $gudangNonaktif = $this->gudangModel->where('id', $userGudangId)->where('status', 'Nonaktif')->countAllResults();
            
            $totalStokObj   = $this->stokModel->selectSum('jumlah')->where('id_gudang', $userGudangId)->get()->getRow();
            $totalStok      = (float) ($totalStokObj->jumlah ?? 0);

            $totalMasukObj  = $this->db->table('detail_barang_masuk_wilayah d')
                ->join('barang_masuk_wilayah m', 'm.id = d.id_masuk')
                ->where('m.id_gudang', $userGudangId)
                ->where('m.tanggal', $today)
                ->where('m.deleted_at', null)
                ->selectSum('d.jumlah')
                ->get()->getRow();
            $totalMasukToday = (float) ($totalMasukObj->jumlah ?? 0);

            $totalKeluarObj = $this->db->table('detail_barang_keluar_wilayah d')
                ->join('barang_keluar_wilayah k', 'k.id = d.id_keluar')
                ->where('k.id_gudang', $userGudangId)
                ->where('k.tanggal', $today)
                ->where('k.deleted_at', null)
                ->selectSum('d.jumlah')
                ->get()->getRow();
            $totalKeluarToday = (float) ($totalKeluarObj->jumlah ?? 0);

            $gudangList = $this->gudangModel->where('id', $userGudangId)->findAll();
        } else {
            $totalGudang    = $this->gudangModel->countAllResults();
            $gudangAktif    = $this->gudangModel->where('status', 'Aktif')->countAllResults();
            $gudangNonaktif = $this->gudangModel->where('status', 'Nonaktif')->countAllResults();

            $totalStokObj   = $this->stokModel->selectSum('jumlah')->get()->getRow();
            $totalStok      = (float) ($totalStokObj->jumlah ?? 0);

            $totalMasukObj  = $this->db->table('detail_barang_masuk_wilayah d')
                ->join('barang_masuk_wilayah m', 'm.id = d.id_masuk')
                ->where('m.tanggal', $today)
                ->where('m.deleted_at', null)
                ->selectSum('d.jumlah')
                ->get()->getRow();
            $totalMasukToday = (float) ($totalMasukObj->jumlah ?? 0);

            $totalKeluarObj = $this->db->table('detail_barang_keluar_wilayah d')
                ->join('barang_keluar_wilayah k', 'k.id = d.id_keluar')
                ->where('k.tanggal', $today)
                ->where('k.deleted_at', null)
                ->selectSum('d.jumlah')
                ->get()->getRow();
            $totalKeluarToday = (float) ($totalKeluarObj->jumlah ?? 0);

            $gudangList = $this->gudangModel->where('status', 'Aktif')->findAll();
        }

        return [
            'totalGudang'      => $totalGudang,
            'gudangAktif'      => $gudangAktif,
            'gudangNonaktif'   => $gudangNonaktif,
            'totalStok'        => $totalStok,
            'totalMasukToday'  => $totalMasukToday,
            'totalKeluarToday' => $totalKeluarToday,
            'gudangList'       => $gudangList
        ];
    }

    /**
     * 2. Ringkasan Nasional (Card Informasi Nasional untuk Admin)
     */
    public function getNationalSummary()
    {
        $today = date('Y-m-d');

        // Gudang Teraktif Hari Ini (Paling banyak transaksi masuk/keluar)
        $teraktifMasuk = $this->db->table('barang_masuk_wilayah m')
            ->select('m.id_gudang, g.nama as nama_gudang, count(m.id) as total_tx')
            ->join('master_gudang_wilayah g', 'g.id = m.id_gudang')
            ->where('m.tanggal', $today)
            ->where('m.deleted_at', null)
            ->groupBy('m.id_gudang')
            ->orderBy('total_tx', 'DESC')
            ->get()->getRowArray();

        $gudangTeraktif = $teraktifMasuk['nama_gudang'] ?? 'Belum ada transaksi';

        // Gudang dengan Stok Terbesar
        $stokTerbesar = $this->db->table('stok_gudang_wilayah s')
            ->select('g.nama as nama_gudang, sum(s.jumlah) as total_stok')
            ->join('master_gudang_wilayah g', 'g.id = s.id_gudang')
            ->groupBy('s.id_gudang')
            ->orderBy('total_stok', 'DESC')
            ->get()->getRowArray();

        $gudangStokTerbesar = $stokTerbesar ? $stokTerbesar['nama_gudang'] . ' (' . number_format($stokTerbesar['total_stok'], 0, ',', '.') . ')' : '-';

        // Barang Paling Banyak Masuk (All Time)
        $topMasuk = $this->db->table('detail_barang_masuk_wilayah d')
            ->select('b.nama_barang, sum(d.jumlah) as total_qty, d.satuan')
            ->join('master_barang_wilayah b', 'b.id = d.id_barang')
            ->join('barang_masuk_wilayah m', 'm.id = d.id_masuk')
            ->where('m.deleted_at', null)
            ->groupBy('d.id_barang')
            ->orderBy('total_qty', 'DESC')
            ->get()->getRowArray();

        $barangTopMasuk = $topMasuk ? $topMasuk['nama_barang'] . ' (' . number_format($topMasuk['total_qty'], 0, ',', '.') . ' ' . $topMasuk['satuan'] . ')' : '-';

        // Barang Paling Banyak Keluar (All Time)
        $topKeluar = $this->db->table('detail_barang_keluar_wilayah d')
            ->select('b.nama_barang, sum(d.jumlah) as total_qty, d.satuan')
            ->join('master_barang_wilayah b', 'b.id = d.id_barang')
            ->join('barang_keluar_wilayah k', 'k.id = d.id_keluar')
            ->where('k.deleted_at', null)
            ->groupBy('d.id_barang')
            ->orderBy('total_qty', 'DESC')
            ->get()->getRowArray();

        $barangTopKeluar = $topKeluar ? $topKeluar['nama_barang'] . ' (' . number_format($topKeluar['total_qty'], 0, ',', '.') . ' ' . $topKeluar['satuan'] . ')' : '-';

        // Donatur Terbanyak Hari Ini
        $topDonatur = $this->db->table('barang_masuk_wilayah m')
            ->select('dn.nama_donatur, count(m.id) as total_donasi')
            ->join('donatur dn', 'dn.id = m.id_donatur')
            ->where('m.tanggal', $today)
            ->where('m.deleted_at', null)
            ->groupBy('m.id_donatur')
            ->orderBy('total_donasi', 'DESC')
            ->get()->getRowArray();

        $donaturTerbanyak = $topDonatur ? $topDonatur['nama_donatur'] : '-';

        // Update Terakhir Sistem
        $lastUpdateStok = $this->stokModel->selectMax('updated_at')->get()->getRow()->updated_at ?? null;

        return [
            'gudangTeraktif'     => $gudangTeraktif,
            'gudangStokTerbesar' => $gudangStokTerbesar,
            'barangTopMasuk'     => $barangTopMasuk,
            'barangTopKeluar'    => $barangTopKeluar,
            'donaturTerbanyak'   => $donaturTerbanyak,
            'lastUpdate'         => $lastUpdateStok ? date('d M Y, H:i', strtotime($lastUpdateStok)) : date('d M Y, H:i')
        ];
    }

    /**
     * 4. Ringkasan Gudang (Gudang Info & Basic Stats)
     */
    public function getGudangSummary($idGudang)
    {
        $gudang = $this->gudangModel->find($idGudang);
        if (!$gudang) return null;

        $totalBarang = $this->stokModel
            ->where('id_gudang', $idGudang)
            ->where('jumlah >', 0)
            ->countAllResults();

        $totalMasukObj = $this->db->table('detail_barang_masuk_wilayah d')
            ->join('barang_masuk_wilayah m', 'm.id = d.id_masuk')
            ->where('m.id_gudang', $idGudang)
            ->where('m.deleted_at', null)
            ->selectSum('d.jumlah')
            ->get()->getRow();
        $totalMasuk = (float) ($totalMasukObj->jumlah ?? 0);

        $totalKeluarObj = $this->db->table('detail_barang_keluar_wilayah d')
            ->join('barang_keluar_wilayah k', 'k.id = d.id_keluar')
            ->where('k.id_gudang', $idGudang)
            ->where('k.deleted_at', null)
            ->selectSum('d.jumlah')
            ->get()->getRow();
        $totalKeluar = (float) ($totalKeluarObj->jumlah ?? 0);

        $sisaStokObj = $this->stokModel->selectSum('jumlah')->where('id_gudang', $idGudang)->get()->getRow();
        $sisaStok    = (float) ($sisaStokObj->jumlah ?? 0);

        $lastUpdate = $this->stokModel->selectMax('updated_at')->where('id_gudang', $idGudang)->get()->getRow()->updated_at ?? null;

        return [
            'id'            => $gudang['id'],
            'nama'          => $gudang['nama'],
            'kota'          => $gudang['kota'] ?? '-',
            'provinsi'      => $gudang['provinsi'] ?? '-',
            'alamat'        => $gudang['alamat'] ?? '-',
            'pic'           => $gudang['pic'] ?? '-',
            'status'        => $gudang['status'] ?? 'Aktif',
            'created_at'    => date('d M Y', strtotime($gudang['created_at'] ?? 'now')),
            'total_barang'  => $totalBarang,
            'total_masuk'   => $totalMasuk,
            'total_keluar'  => $totalKeluar,
            'sisa_stok'     => $sisaStok,
            'last_update'   => $lastUpdate ? date('d M Y, H:i', strtotime($lastUpdate)) : '-'
        ];
    }

    /**
     * 5. Grafik Mutasi Gudang (7 Hari Terakhir)
     */
    public function getGudangChartData($idGudang)
    {
        $dates = [];
        $masukData = [];
        $keluarData = [];

        // Generate last 7 days (including today)
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-$i days"));
        }

        foreach ($dates as $d) {
            $mObj = $this->db->table('detail_barang_masuk_wilayah det')
                ->join('barang_masuk_wilayah m', 'm.id = det.id_masuk')
                ->where('m.id_gudang', $idGudang)
                ->where('m.tanggal', $d)
                ->where('m.deleted_at', null)
                ->selectSum('det.jumlah')
                ->get()->getRow();

            $kObj = $this->db->table('detail_barang_keluar_wilayah det')
                ->join('barang_keluar_wilayah k', 'k.id = det.id_keluar')
                ->where('k.id_gudang', $idGudang)
                ->where('k.tanggal', $d)
                ->where('k.deleted_at', null)
                ->selectSum('det.jumlah')
                ->get()->getRow();

            $masukData[]  = (float) ($mObj->jumlah ?? 0);
            $keluarData[] = (float) ($kObj->jumlah ?? 0);
        }

        // Format dates for chart display (e.g. 24 Jul)
        $labels = array_map(function($dateStr) {
            return date('d M', strtotime($dateStr));
        }, $dates);

        return [
            'labels' => $labels,
            'masuk'  => $masukData,
            'keluar' => $keluarData
        ];
    }

    /**
     * 6. Top 10 Barang Gudang Berdasarkan Stok
     */
    public function getGudangTopBarang($idGudang)
    {
        return $this->db->table('stok_gudang_wilayah s')
            ->select('b.nama_barang, b.kode_barang, s.jumlah, b.satuan')
            ->join('master_barang_wilayah b', 'b.id = s.id_barang')
            ->where('s.id_gudang', $idGudang)
            ->where('s.jumlah >', 0)
            ->orderBy('s.jumlah', 'DESC')
            ->limit(10)
            ->get()->getResultArray();
    }

    /**
     * 7. Riwayat Transaksi Terbaru (10 Gabungan Masuk & Keluar)
     */
    public function getGudangRecentTransactions($idGudang)
    {
        $masukList = $this->db->table('barang_masuk_wilayah m')
            ->select('m.id, m.tanggal, m.nomor_dokumen, "masuk" as jenis, dn.nama_donatur as donatur_tujuan, u.username as operator, m.created_at')
            ->join('donatur dn', 'dn.id = m.id_donatur', 'left')
            ->join('users u', 'u.id = m.created_by', 'left')
            ->where('m.id_gudang', $idGudang)
            ->where('m.deleted_at', null)
            ->orderBy('m.created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        $keluarList = $this->db->table('barang_keluar_wilayah k')
            ->select('k.id, k.tanggal, k.nomor_dokumen, "keluar" as jenis, k.tujuan as donatur_tujuan, u.username as operator, k.created_at')
            ->join('users u', 'u.id = k.created_by', 'left')
            ->where('k.id_gudang', $idGudang)
            ->where('k.deleted_at', null)
            ->orderBy('k.created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        $combined = array_merge($masukList, $keluarList);

        // Sort by created_at DESC
        usort($combined, function($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        $top10 = array_slice($combined, 0, 10);

        // Populate barang summary & total qty for each transaction
        foreach ($top10 as &$tx) {
            if ($tx['jenis'] === 'masuk') {
                $details = $this->db->table('detail_barang_masuk_wilayah d')
                    ->select('b.nama_barang, d.jumlah, d.satuan')
                    ->join('master_barang_wilayah b', 'b.id = d.id_barang')
                    ->where('d.id_masuk', $tx['id'])
                    ->get()->getResultArray();
            } else {
                $details = $this->db->table('detail_barang_keluar_wilayah d')
                    ->select('b.nama_barang, d.jumlah, d.satuan')
                    ->join('master_barang_wilayah b', 'b.id = d.id_barang')
                    ->where('d.id_keluar', $tx['id'])
                    ->get()->getResultArray();
            }

            $barangNames = [];
            $totalQty = 0;
            $satuan = '';
            foreach ($details as $dt) {
                $barangNames[] = $dt['nama_barang'];
                $totalQty += (float) $dt['jumlah'];
                $satuan = $dt['satuan'];
            }

            $tx['barang_summary'] = implode(', ', $barangNames);
            $tx['total_qty']      = $totalQty;
            $tx['satuan']         = $satuan;
            $tx['tanggal_formatted'] = date('d/m/Y', strtotime($tx['tanggal']));
        }

        return $top10;
    }

    /**
     * 9. Gudang Detail (untuk Halaman Detail Gudang dengan Tabs)
     */
    public function getGudangDetail($idGudang)
    {
        $gudang = $this->gudangModel->find($idGudang);
        if (!$gudang) return null;

        $stok = $this->db->table('stok_gudang_wilayah s')
            ->select('s.*, b.nama_barang, b.kode_barang, b.satuan as satuan_default, k.nama_kategori')
            ->join('master_barang_wilayah b', 'b.id = s.id_barang')
            ->join('kategori k', 'k.id = b.id_kategori', 'left')
            ->where('s.id_gudang', $idGudang)
            ->get()->getResultArray();

        $masuk = $this->db->table('barang_masuk_wilayah m')
            ->select('m.*, dn.nama_donatur, count(d.id) as total_item, sum(d.jumlah) as total_qty')
            ->join('donatur dn', 'dn.id = m.id_donatur', 'left')
            ->join('detail_barang_masuk_wilayah d', 'd.id_masuk = m.id', 'left')
            ->where('m.id_gudang', $idGudang)
            ->where('m.deleted_at', null)
            ->groupBy('m.id')
            ->orderBy('m.tanggal', 'DESC')
            ->orderBy('m.id', 'DESC')
            ->get()->getResultArray();

        $keluar = $this->db->table('barang_keluar_wilayah k')
            ->select('k.*, count(d.id) as total_item, sum(d.jumlah) as total_qty')
            ->join('detail_barang_keluar_wilayah d', 'd.id_keluar = k.id', 'left')
            ->where('k.id_gudang', $idGudang)
            ->where('k.deleted_at', null)
            ->groupBy('k.id')
            ->orderBy('k.tanggal', 'DESC')
            ->orderBy('k.id', 'DESC')
            ->get()->getResultArray();

        return [
            'gudang' => $gudang,
            'stok'   => $stok,
            'masuk'  => $masuk,
            'keluar' => $keluar
        ];
    }
}
