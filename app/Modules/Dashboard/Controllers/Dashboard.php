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
        $totalJenisBarang = cache()->remember('dashboard_total_jenis_barang', 300, function() use ($db) {
            return $db->table('batch')
                ->select('id_barang')
                ->where('stok_saat_ini >', 0)
                ->distinct()
                ->countAllResults();
        });
            
        // 2. Total Batch Aktif
        $totalBatch = cache()->remember('dashboard_total_batch', 300, function() use ($db) {
            return $db->table('batch')
                ->where('stok_saat_ini >', 0)
                ->countAllResults();
        });
            
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
            
        // Total Berat Gudang & Total Unit Barang
        $totalBeratGudang = cache()->remember('dashboard_total_berat', 300, function() use ($db) {
            $stokAktif = $db->table('batch')
                ->select('stok_saat_ini, berat_per_satuan, satuan_berat, bisa_dipecah')
                ->where('stok_saat_ini >', 0)
                ->get()->getResultArray();
                
            $total = 0;
            foreach ($stokAktif as $s) {
                if ((int)$s['bisa_dipecah'] === 1) {
                    $total += (float)$s['stok_saat_ini'];
                } else {
                    $berat = $s['stok_saat_ini'] * $s['berat_per_satuan'];
                    if (strtolower($s['satuan_berat'] ?? '') === 'gram') {
                        $berat = $berat / 1000;
                    }
                    $total += $berat;
                }
            }
            return $total;
        });
        
        // Total Donatur & Wilayah
        $totalDonatur = $db->table('donatur')->countAllResults();
        $totalWilayah = $db->table('wilayah')->countAllResults();
        
        // FEFO Summary (Expired, Hampir Expired, Aman)
        $batches = $db->table('batch')
            ->select('batch.id, batch.tanggal_kedaluwarsa, batch.stok_saat_ini, barang.nama_barang')
            ->join('barang', 'barang.id = batch.id_barang', 'left')
            ->where('batch.stok_saat_ini >', 0)
            ->orderBy('batch.tanggal_kedaluwarsa', 'ASC')
            ->get()->getResultArray();
            
        $today = new \DateTime(date('Y-m-d'));
        $countExpired = 0;
        $countHampirExpired = 0;
        $countAman = 0;
        $hampirExpiredList = [];
        
        foreach ($batches as $b) {
            $expDate = new \DateTime($b['tanggal_kedaluwarsa']);
            $diff = (int) $today->diff($expDate)->format('%R%a');
            
            if ($diff < 0) {
                $countExpired++;
                $status = 'Expired';
            } elseif ($diff <= 30) {
                $countHampirExpired++;
                $status = 'Hampir Expired';
            } else {
                $countAman++;
                $status = 'Aman';
            }
            
            if ($status !== 'Aman' && count($hampirExpiredList) < 5) {
                $b['status_expired'] = $status;
                $b['sisa_hari'] = $diff;
                $hampirExpiredList[] = $b;
            }
        }
        
        // Total Kategori
        $totalKategori = $db->table('kategori')->countAllResults();
        
        // Top 5 Barang dengan Stok Terbanyak (dikonsolidasi berdasarkan ID Barang)
        $topBarangProcessed = cache()->remember('dashboard_top_barang', 300, function() use ($db) {
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
                $satuanBerat = strtolower($b['satuan_berat'] ?? '');
                $bisaDipecah = (int)$b['bisa_dipecah'];

                if ($bisaDipecah === 1) {
                    $beratKg = $stok; // Stok sudah dalam Kg
                } else {
                    $beratKg = $stok * $beratPerSatuan;
                    if ($satuanBerat === 'gram') {
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

            return array_slice($barangConsolidated, 0, 5);
        });
        
        // Stok Kategori (Konversi ke Total Kg)
        $kategoriDataArr = cache()->remember('dashboard_kategori', 300, function() use ($db) {
            $rawKategoriStok = $db->table('batch')
                ->select('kategori.nama_kategori as label, batch.stok_saat_ini, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
                ->join('barang', 'barang.id = batch.id_barang')
                ->join('kategori', 'kategori.id = barang.id_kategori')
                ->where('batch.stok_saat_ini >', 0)
                ->get()->getResultArray();

            $kategoriBerat = [];
            foreach ($rawKategoriStok as $row) {
                $label = $row['label'];
                $stok = (float)$row['stok_saat_ini'];
                $beratPerSatuan = (float)$row['berat_per_satuan'];
                $satuanBerat = strtolower($row['satuan_berat'] ?? '');
                $bisaDipecah = (int)$row['bisa_dipecah'];

                if ($bisaDipecah === 1) {
                    $beratKg = $stok; // Stok sudah dalam Kg
                } else {
                    $beratKg = $stok * $beratPerSatuan;
                    if ($satuanBerat === 'gram') {
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

            return [
                'labels' => array_keys($kategoriBerat),
                'data'   => array_values($kategoriBerat)
            ];
        });

        $kategoriLabels = $kategoriDataArr['labels'];
        $kategoriData = $kategoriDataArr['data'];
        
        // Grafik Donasi & Penyaluran 12 Bulan Terakhir
        $grafikDataArr = cache()->remember('dashboard_grafik_12bln', 300, function() use ($db) {
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

            return [
                'labels'     => $gLabels,
                'donasi'     => $gDonasi,
                'penyaluran' => $gPenyaluran
            ];
        });

        $grafikLabels = $grafikDataArr['labels'];
        $grafikDonasiData = $grafikDataArr['donasi'];
        $grafikPenyaluranData = $grafikDataArr['penyaluran'];
        
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
            'countExpired' => $countExpired,
            'countHampirExpired' => $countHampirExpired,
            'countAman' => $countAman,
            'hampirExpiredList' => $hampirExpiredList,
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
}
