<?php

namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Modules\Dashboard\Models\DashboardModel;

class Dashboard extends BaseController
{
    private DashboardModel $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $bulanIni  = date('m');
        $tahunIni  = date('Y');

        // ── KPI counts ───────────────────────────────────────────────────────
        $totalJenisBarang = $db->table('batch')
            ->select('id_barang')
            ->where('stok_saat_ini >', 0)
            ->distinct()
            ->countAllResults();

        $totalBatch = $db->table('batch')
            ->where('stok_saat_ini >', 0)
            ->where('status', 'Aktif')
            ->countAllResults();

        $donasiBulanIni = $db->table('barang_masuk')
            ->where('MONTH(tanggal_masuk)', $bulanIni)
            ->where('YEAR(tanggal_masuk)', $tahunIni)
            ->countAllResults();

        $penyaluranBulanIni = $db->table('barang_keluar')
            ->where('MONTH(tanggal_keluar)', $bulanIni)
            ->where('YEAR(tanggal_keluar)', $tahunIni)
            ->countAllResults();

        // ── [FIX #2] Berat gudang via SQL aggregation ────────────────────────
        $totalBeratGudang = $this->model->getTotalBeratGudangKg();

        // ── Donatur & Wilayah ─────────────────────────────────────────────────
        $totalDonatur  = $db->table('donatur')->countAllResults();
        $totalWilayah  = $db->table('wilayah')->countAllResults();
        $totalKategori = $db->table('kategori')->countAllResults();

        // ── FEFO / expired stats ──────────────────────────────────────────────
        $notifService = new \App\Services\ExpiredNotificationService();
        $expiredStats = $notifService->getDashboardStats();

        $countExpired      = ($expiredStats['CRITICAL'] ?? 0) + ($expiredStats['HIGH'] ?? 0);
        $countHampirExpired = ($expiredStats['WARNING'] ?? 0);
        $totalProblem      = $countExpired + $countHampirExpired + ($expiredStats['INFO'] ?? 0);
        $countAman         = $totalBatch - ($countExpired + $countHampirExpired);

        $hampirExpiredList = $this->model->getHampirExpiredList(5);

        // ── Popup sekali sehari ───────────────────────────────────────────────
        $showPopup = false;
        if (session()->get('expired_notification_date') !== date('Y-m-d') && $totalProblem > 0) {
            $showPopup = true;
            session()->set('expired_notification_date', date('Y-m-d'));
        }

        // ── [FIX #2 + #12] Top 5 via SQL GROUP BY ────────────────────────────
        $topBarang = $this->model->getTopBarang(5);

        // ── [FIX #3] Grafik 12 bulan: 2 query GROUP BY ───────────────────────
        $grafik            = $this->model->getGrafikData();
        $grafikLabels      = $grafik['labels'];
        $grafikDonasiData  = $grafik['donasiData'];
        $grafikPenyaluranData = $grafik['keluarData'];

        // ── Kategori donut ────────────────────────────────────────────────────
        $kategori      = $this->model->getKategoriStok();
        $kategoriLabels = $kategori['labels'];
        $kategoriData  = $kategori['data'];

        helper('format');

        $data = [
            'title'                => 'Dashboard',
            'totalJenisBarang'     => $totalJenisBarang,
            'totalBatch'           => $totalBatch,
            'donasiBulanIni'       => $donasiBulanIni,
            'penyaluranBulanIni'   => $penyaluranBulanIni,
            'totalBeratGudang'     => $totalBeratGudang,
            'totalDonatur'         => $totalDonatur,
            'totalWilayah'         => $totalWilayah,
            'totalKategori'        => $totalKategori,
            'expiredStats'         => $expiredStats,
            'countExpired'         => $countExpired,
            'countHampirExpired'   => $countHampirExpired,
            'hampirExpiredList'    => $hampirExpiredList,
            'countAman'            => $countAman,
            'showPopup'            => $showPopup,
            'topBarang'            => $topBarang,
            'kategoriLabels'       => json_encode($kategoriLabels),
            'kategoriData'         => json_encode($kategoriData),
            'grafikLabels'         => json_encode($grafikLabels),
            'grafikDonasiData'     => json_encode($grafikDonasiData),
            'grafikPenyaluranData' => json_encode($grafikPenyaluranData),
            'bulanIniLabel'        => date('F Y'),
        ];

        return view('App\Modules\Dashboard\Views\index', $data);
    }

    // ── [FIX #5] isAJAX() guard + [FIX #2] pakai model ─────────────────────
    public function liveData()
    {
        // [FIX #5] Tolak request non-AJAX (misal: akses langsung lewat browser)
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Forbidden',
            ]);
        }

        $db = \Config\Database::connect();

        $bulanIni = date('m');
        $tahunIni = date('Y');

        // ── KPI counts ───────────────────────────────────────────────────────
        $totalJenisBarang = $db->table('batch')
            ->select('id_barang')
            ->where('stok_saat_ini >', 0)
            ->distinct()
            ->countAllResults();

        $totalBatch = $db->table('batch')
            ->where('stok_saat_ini >', 0)
            ->where('status', 'Aktif')
            ->countAllResults();

        $donasiBulanIni = $db->table('barang_masuk')
            ->where('MONTH(tanggal_masuk)', $bulanIni)
            ->where('YEAR(tanggal_masuk)', $tahunIni)
            ->countAllResults();

        $penyaluranBulanIni = $db->table('barang_keluar')
            ->where('MONTH(tanggal_keluar)', $bulanIni)
            ->where('YEAR(tanggal_keluar)', $tahunIni)
            ->countAllResults();

        $totalDonatur  = $db->table('donatur')->countAllResults();
        $totalWilayah  = $db->table('wilayah')->countAllResults();
        $totalKategori = $db->table('kategori')->countAllResults();

        // ── [FIX #2] Berat via SQL ────────────────────────────────────────────
        $totalBeratGudang = $this->model->getTotalBeratGudangKg();

        // ── Expired stats ─────────────────────────────────────────────────────
        $notifService      = new \App\Services\ExpiredNotificationService();
        $expiredStats      = $notifService->getDashboardStats();
        $countExpired      = ($expiredStats['CRITICAL'] ?? 0) + ($expiredStats['HIGH'] ?? 0);
        $countHampirExpired = ($expiredStats['WARNING'] ?? 0);
        $countAman         = $totalBatch - ($countExpired + $countHampirExpired);

        // ── [FIX #2 + #12] Top 5 via SQL ─────────────────────────────────────
        $topBarangRaw = $this->model->getTopBarang(5);

        // ── [FIX #3] Grafik via 2 query GROUP BY ─────────────────────────────
        $grafik = $this->model->getGrafikData();

        // ── Kategori ──────────────────────────────────────────────────────────
        $kategori = $this->model->getKategoriStok();

        // ── Hampir expired ────────────────────────────────────────────────────
        $hampirExpiredRaw = $this->model->getHampirExpiredList(5);

        helper('format');

        // Format top barang untuk JS
        $topBarangFormatted = [];
        foreach ($topBarangRaw as $tb) {
            $topBarangFormatted[] = [
                'nama_barang' => esc($tb['nama_barang']),
                'total_stok'  => number_format((float)$tb['total_stok'], 0, ',', '.'),
                'satuan'      => esc($tb['satuan']),
                'total_berat' => format_berat((float)$tb['total_berat'], 'Kg'),
            ];
        }

        // Format hampir expired untuk JS (termasuk badge HTML yang di-escape)
        $hampirExpiredFormatted = [];
        foreach ($hampirExpiredRaw as $item) {
            $sisaHari = (int)$item['sisa_hari'];

            if ($item['status_expired'] === 'Expired') {
                $badge    = '<span class="wb-badge red"><i class="bi bi-x-circle"></i> Expired</span>';
                $sisaHtml = '<span style="color:var(--red);font-weight:600">' . $sisaHari . ' hari</span>';
            } elseif ($item['status_expired'] === 'Hampir Expired') {
                $badge    = '<span class="wb-badge amber"><i class="bi bi-exclamation-circle"></i> ≤ 30 Hari</span>';
                $sisaHtml = '<span style="color:var(--amber);font-weight:600">' . $sisaHari . ' hari</span>';
            } else {
                $badge    = '<span class="wb-badge green"><i class="bi bi-check-circle"></i> Aman</span>';
                $sisaHtml = '<span style="color:var(--green);font-weight:600">' . $sisaHari . ' hari</span>';
            }

            $hampirExpiredFormatted[] = [
                'nama_barang'        => esc($item['nama_barang']),
                'tanggal_kedaluwarsa'=> date('d M Y', strtotime($item['tanggal_kedaluwarsa'])),
                'sisa_hari_html'     => $sisaHtml,
                'status_badge'       => $badge,
            ];
        }

        // ── Response — key "status" = 'success' (sesuai ekspektasi JS view) ──
        return $this->response->setJSON([
            'status'             => 'success',
            'metrics'            => [
                'totalJenisBarang'   => $totalJenisBarang,
                'totalBatch'         => $totalBatch,
                'donasiBulanIni'     => $donasiBulanIni,
                'penyaluranBulanIni' => $penyaluranBulanIni,
                'totalBeratGudang'   => format_berat($totalBeratGudang, 'Kg'),
                'totalDonatur'       => number_format($totalDonatur, 0, ',', '.'),
                'totalWilayah'       => number_format($totalWilayah, 0, ',', '.'),
                'totalKategori'      => number_format($totalKategori, 0, ',', '.'),
                'countExpired'       => $countExpired,
                'countHampirExpired' => $countHampirExpired,
                'countAman'          => $countAman,
            ],
            'topBarang'          => $topBarangFormatted,
            'hampirExpiredList'  => $hampirExpiredFormatted,
            'kategoriLabels'     => $kategori['labels'],
            'kategoriData'       => $kategori['data'],
            'grafikLabels'       => $grafik['labels'],
            'grafikDonasiData'   => $grafik['donasiData'],
            'grafikPenyaluranData' => $grafik['keluarData'],
            'updated_at'         => date('d F Y H:i:s'),
        ]);
    }
}