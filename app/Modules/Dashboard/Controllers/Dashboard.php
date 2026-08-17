<?php

namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunLalu = date('Y', strtotime('-1 month'));
        
        // 1. Total Jenis Barang
        $totalJenisBarang = $db->table('batch')
            ->select('id_barang')
            ->where('stok_saat_ini >', 0)
            ->distinct()
            ->countAllResults();
            
        // 2. Total Batch Aktif
        $totalBatch = $db->table('batch')
            ->where('stok_saat_ini >', 0)
            ->where('status', 'Aktif')
            ->countAllResults();
            
        // 3. Donasi Bulan Ini
        $donasiBulanIni = $db->table('barang_masuk')
            ->where('MONTH(tanggal_masuk)', $bulanIni)
            ->where('YEAR(tanggal_masuk)', $tahunIni)
            ->countAllResults();
            
        // 4. Penyaluran Bulan Ini
        $penyaluranBulanIni = $db->table('barang_keluar')
            ->where('MONTH(tanggal_keluar)', $bulanIni)
            ->where('YEAR(tanggal_keluar)', $tahunIni)
            ->countAllResults();
            
        // Total Berat Gudang
        $stokAktif = $db->table('batch')
            ->select('stok_saat_ini, berat_per_satuan, satuan_berat, bisa_dipecah')
            ->where('stok_saat_ini >', 0)
            ->where('status', 'Aktif')
            ->get()->getResultArray();
            
        $totalBeratGudang = 0;
        foreach ($stokAktif as $s) {
            $satB = strtolower(trim($s['satuan_berat'] ?? ''));
            if ((int)$s['bisa_dipecah'] === 1) {
                $totalBeratGudang += (float)$s['stok_saat_ini'];
            } else {
                $berat = $s['stok_saat_ini'] * $s['berat_per_satuan'];
                if (in_array($satB, ['gram', 'g', 'gr', 'ml'])) {
                    $berat = $berat / 1000;
                }
                $totalBeratGudang += $berat;
            }
        }
        
        // Total Donatur & Wilayah
        $totalDonatur = $db->table('donatur')->countAllResults();
        $totalWilayah = $db->table('wilayah')->countAllResults();
        
        // FEFO Summary (Expired, Hampir Expired, Aman)
        $notifService = new \App\Services\ExpiredNotificationService();
        $expiredStats = $notifService->getDashboardStats();
        
        $totalActive = $totalBatch;
        $totalProblem = ($expiredStats['CRITICAL'] ?? 0) + ($expiredStats['HIGH'] ?? 0) + ($expiredStats['WARNING'] ?? 0) + ($expiredStats['INFO'] ?? 0);
        
        $countExpired = ($expiredStats['CRITICAL'] ?? 0) + ($expiredStats['HIGH'] ?? 0);
        $countHampirExpired = ($expiredStats['WARNING'] ?? 0);
        $countAman = $totalActive - ($countExpired + $countHampirExpired);

        $hampirExpiredList = $db->table('batch')
            ->select('barang.nama_barang, batch.tanggal_kedaluwarsa, DATEDIFF(batch.tanggal_kedaluwarsa, CURDATE()) as sisa_hari')
            ->select('IF(DATEDIFF(batch.tanggal_kedaluwarsa, CURDATE()) < 0, "Expired", IF(DATEDIFF(batch.tanggal_kedaluwarsa, CURDATE()) <= 30, "Hampir Expired", "Aman")) as status_expired', false)
            ->join('barang', 'barang.id = batch.id_barang')
            ->where('batch.stok_saat_ini >', 0)
            ->where('DATEDIFF(batch.tanggal_kedaluwarsa, CURDATE()) <=', 30)
            ->orderBy('sisa_hari', 'ASC')
            ->limit(5)
            ->get()->getResultArray();

        // Session check for popup
        $showPopup = false;
        if (session()->get('expired_notification_date') !== date('Y-m-d')) {
            if ($totalProblem > 0) {
                $showPopup = true;
                session()->set('expired_notification_date', date('Y-m-d'));
            }
        }
        
        // Total Kategori
        $totalKategori = $db->table('kategori')->countAllResults();
        
        // Top 5 Barang dengan Stok Terbanyak (dikonsolidasi berdasarkan ID Barang)
        $rawTopBarang = $db->table('batch')
            ->select('barang.id, barang.nama_barang, batch.satuan, batch.stok_saat_ini, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
            ->join('barang', 'barang.id = batch.id_barang')
            ->where('batch.stok_saat_ini >', 0)
            ->get()->getResultArray();

        $barangConsolidated = [];
        foreach ($rawTopBarang as $b) {
            $id = $b['id'];
            $stok = (float)$b['stok_saat_ini'];
            $beratPerSatuan = (float)$b['berat_per_satuan'];
            $satuanBerat = strtolower(trim($b['satuan_berat'] ?? ''));
            $bisaDipecah = (int)$b['bisa_dipecah'];

            if ($bisaDipecah === 1) {
                $beratKg = $stok; // Stok sudah dalam Kg
            } else {
                $beratKg = $stok * $beratPerSatuan;
                if (in_array($satuanBerat, ['gram', 'g', 'gr', 'ml'])) {
                    $beratKg = $beratKg / 1000;
                }
            }

            if (isset($barangConsolidated[$id])) {
                $barangConsolidated[$id]['total_stok'] += $stok;
                $barangConsolidated[$id]['total_berat'] += $beratKg;
            } else {
                $barangConsolidated[$id] = [
                    'nama_barang' => $b['nama_barang'],
                    'satuan'      => $bisaDipecah === 1 ? 'Kg' : $b['satuan'],
                    'total_stok'  => $stok,
                    'total_berat' => $beratKg
                ];
            }
        }

        usort($barangConsolidated, function($a, $b) {
            return $b['total_stok'] <=> $a['total_stok'];
        });

        $topBarangProcessed = array_slice($barangConsolidated, 0, 5);
        
        // Stok Kategori (Konversi ke Total Kg)
        $rawKategoriStok = $db->table('batch')
            ->select('COALESCE(NULLIF(batch.kategori, ""), kategori.nama_kategori, "Tanpa Kategori") as label, batch.stok_saat_ini, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->where('batch.stok_saat_ini >', 0)
            ->where('batch.status', 'Aktif')
            ->get()->getResultArray();

        $kategoriBerat = [];
        foreach ($rawKategoriStok as $row) {
            $label = $row['label'];
            $stok = (float)$row['stok_saat_ini'];
            $beratPerSatuan = (float)$row['berat_per_satuan'];
            $satuanBerat = strtolower(trim($row['satuan_berat'] ?? ''));
            $bisaDipecah = (int)$row['bisa_dipecah'];

            if ($bisaDipecah === 1) {
                $beratKg = $stok;
            } else {
                $beratKg = $stok * $beratPerSatuan;
                if (in_array($satuanBerat, ['gram', 'g', 'gr', 'ml'])) {
                    $beratKg = $beratKg / 1000;
                }
            }

            if (!isset($kategoriBerat[$label])) {
                $kategoriBerat[$label] = 0;
            }
            $kategoriBerat[$label] += $beratKg;
        }

        foreach ($kategoriBerat as $label => $val) {
            $kategoriBerat[$label] = round($val, 2);
        }

        $kategoriLabels = array_keys($kategoriBerat);
        $kategoriData   = array_values($kategoriBerat);
        
        // Grafik Donasi & Penyaluran 12 Bulan Terakhir
        $gLabels = [];
        $gDonasi = [];
        $gPenyaluran = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $m = date('m', strtotime("-$i months"));
            $y = date('Y', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months"));
            
            $d = $db->table('barang_masuk')->where('MONTH(tanggal_masuk)', $m)->where('YEAR(tanggal_masuk)', $y)->countAllResults();
            $p = $db->table('barang_keluar')->where('MONTH(tanggal_keluar)', $m)->where('YEAR(tanggal_keluar)', $y)->countAllResults();
            
            $gLabels[] = $label;
            $gDonasi[] = $d;
            $gPenyaluran[] = $p;
        }

        $grafikLabels = $gLabels;
        $grafikDonasiData = $gDonasi;
        $grafikPenyaluranData = $gPenyaluran;
        
        helper('format');
        
        $data = [
            'title' => 'Dashboard',
            'totalJenisBarang' => $totalJenisBarang,
            'totalBatch' => $totalBatch,
            'donasiBulanIni' => $donasiBulanIni,
            'penyaluranBulanIni' => $penyaluranBulanIni,
            'totalBeratGudang' => $totalBeratGudang,
            'totalDonatur' => $totalDonatur,
            'totalWilayah' => $totalWilayah,
            'totalKategori' => $totalKategori,
            'expiredStats' => $expiredStats,
            'countExpired' => $countExpired,
            'countHampirExpired' => $countHampirExpired,
            'hampirExpiredList' => $hampirExpiredList,
            'countAman' => $countAman,
            'showPopup' => $showPopup,
            'topBarang' => $topBarangProcessed,
            'kategoriLabels' => json_encode($kategoriLabels),
            'kategoriData' => json_encode($kategoriData),
            'grafikLabels' => json_encode($grafikLabels),
            'grafikDonasiData' => json_encode($grafikDonasiData),
            'grafikPenyaluranData' => json_encode($grafikPenyaluranData),
            'bulanIniLabel' => date('F Y')
        ];

        return view('App\Modules\Dashboard\Views\index', $data);
    }

    /**
     * Endpoint Live Data AJAX untuk Auto-Refresh Dashboard
     */
    public function liveData()
    {
        $db = \Config\Database::connect();
        
        $bulanIni = date('m');
        $tahunIni = date('Y');
        
        // 1. Total Jenis Barang
        $totalJenisBarang = $db->table('batch')
            ->select('id_barang')
            ->where('stok_saat_ini >', 0)
            ->distinct()
            ->countAllResults();
            
        // 2. Total Batch Aktif
        $totalBatch = $db->table('batch')
            ->where('stok_saat_ini >', 0)
            ->where('status', 'Aktif')
            ->countAllResults();
            
        // 3. Donasi Bulan Ini
        $donasiBulanIni = $db->table('barang_masuk')
            ->where('MONTH(tanggal_masuk)', $bulanIni)
            ->where('YEAR(tanggal_masuk)', $tahunIni)
            ->countAllResults();
            
        // 4. Penyaluran Bulan Ini
        $penyaluranBulanIni = $db->table('barang_keluar')
            ->where('MONTH(tanggal_keluar)', $bulanIni)
            ->where('YEAR(tanggal_keluar)', $tahunIni)
            ->countAllResults();
            
        // Total Berat Gudang
        $stokAktif = $db->table('batch')
            ->select('stok_saat_ini, berat_per_satuan, satuan_berat, bisa_dipecah')
            ->where('stok_saat_ini >', 0)
            ->where('status', 'Aktif')
            ->get()->getResultArray();
            
        $totalBeratGudang = 0;
        foreach ($stokAktif as $s) {
            $satB = strtolower(trim($s['satuan_berat'] ?? ''));
            if ((int)$s['bisa_dipecah'] === 1) {
                $totalBeratGudang += (float)$s['stok_saat_ini'];
            } else {
                $berat = $s['stok_saat_ini'] * $s['berat_per_satuan'];
                if (in_array($satB, ['gram', 'g', 'gr', 'ml'])) {
                    $berat = $berat / 1000;
                }
                $totalBeratGudang += $berat;
            }
        }

        // Top 5 Barang dengan Stok Terbanyak
        $rawTopBarang = $db->table('batch')
            ->select('barang.id, barang.nama_barang, batch.satuan, batch.stok_saat_ini, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
            ->join('barang', 'barang.id = batch.id_barang')
            ->where('batch.stok_saat_ini >', 0)
            ->get()->getResultArray();

        $barangConsolidated = [];
        foreach ($rawTopBarang as $b) {
            $id = $b['id'];
            $stok = (float)$b['stok_saat_ini'];
            $beratPerSatuan = (float)$b['berat_per_satuan'];
            $satuanBerat = strtolower(trim($b['satuan_berat'] ?? ''));
            $bisaDipecah = (int)$b['bisa_dipecah'];

            if ($bisaDipecah === 1) {
                $beratKg = $stok;
            } else {
                $beratKg = $stok * $beratPerSatuan;
                if (in_array($satuanBerat, ['gram', 'g', 'gr', 'ml'])) {
                    $beratKg = $beratKg / 1000;
                }
            }

            if (isset($barangConsolidated[$id])) {
                $barangConsolidated[$id]['total_stok'] += $stok;
                $barangConsolidated[$id]['total_berat'] += $beratKg;
            } else {
                $barangConsolidated[$id] = [
                    'nama_barang' => $b['nama_barang'],
                    'satuan'      => $bisaDipecah === 1 ? 'Kg' : $b['satuan'],
                    'total_stok'  => $stok,
                    'total_berat' => $beratKg
                ];
            }
        }

        usort($barangConsolidated, function($a, $b) {
            return $b['total_stok'] <=> $a['total_stok'];
        });

        $topBarangProcessed = array_slice($barangConsolidated, 0, 5);

        helper('format');
        $topBarangFormatted = [];
        foreach ($topBarangProcessed as $tb) {
            $topBarangFormatted[] = [
                'nama_barang' => $tb['nama_barang'],
                'stok_text'   => number_format($tb['total_stok'], 0, ',', '.') . ' ' . $tb['satuan'],
                'berat_text'  => format_berat($tb['total_berat'], 'Kg')
            ];
        }

        // Response JSON
        return $this->response->setJSON([
            'status'             => true,
            'totalJenisBarang'   => $totalJenisBarang,
            'totalBatch'         => $totalBatch,
            'donasiBulanIni'     => $donasiBulanIni,
            'penyaluranBulanIni' => $penyaluranBulanIni,
            'totalBeratGudang'   => format_berat($totalBeratGudang, 'Kg'),
            'topBarang'          => $topBarangFormatted,
            'updated_at'         => date('d F Y H:i:s')
        ]);
    }
}
