<?php

namespace App\Modules\Wilayah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Wilayah\Services\WilayahDashboardService;

class DashboardWilayah extends BaseController
{
    protected $dashboardService;

    public function __construct()
    {
        helper(['auth', 'url']);
        $this->dashboardService = new WilayahDashboardService();
    }

    public function index()
    {
        $isAdmin      = function_exists('in_groups') ? in_groups('Administrator') : true;
        $userGudangId = (function_exists('user') && user()) ? user()->id_gudang_wilayah : null;

        $globalStats     = $this->dashboardService->getGlobalStats($isAdmin, $userGudangId);
        $nationalSummary = $isAdmin ? $this->dashboardService->getNationalSummary() : null;

        $data = [
            'title'           => 'Monitoring Center Gudang Wilayah',
            'globalStats'     => $globalStats,
            'nationalSummary' => $nationalSummary,
            'isAdmin'         => $isAdmin,
            'userGudangId'    => $userGudangId
        ];

        return view('App\Modules\Wilayah\Views\dashboard\index', $data);
    }

    public function ajaxSummary($idGudang = null)
    {
        $isAdmin      = function_exists('in_groups') ? in_groups('Administrator') : true;
        $userGudangId = (function_exists('user') && user()) ? user()->id_gudang_wilayah : null;

        // Server-side authorization check: Petugas Gudang is strictly locked to own gudang
        if (function_exists('in_groups') && in_groups('Petugas Gudang') && $userGudangId) {
            $idGudang = $userGudangId;
        }

        if (!$idGudang) {
            $idGudang = $this->request->getGet('id_gudang') ?: $this->request->getPost('id_gudang');
        }

        if (!$idGudang) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'ID Gudang tidak valid']);
        }

        $summary            = $this->dashboardService->getGudangSummary($idGudang);
        $chart              = $this->dashboardService->getGudangChartData($idGudang);
        $topBarang          = $this->dashboardService->getGudangTopBarang($idGudang);
        $recentTransactions = $this->dashboardService->getGudangRecentTransactions($idGudang);

        if (!$summary) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Gudang tidak ditemukan']);
        }

        return $this->response->setJSON([
            'status'              => 'success',
            'summary'             => $summary,
            'chart'               => $chart,
            'top_barang'          => $topBarang,
            'recent_transactions' => $recentTransactions
        ]);
    }

    public function detail($idGudang = null)
    {
        $isAdmin      = function_exists('in_groups') ? in_groups('Administrator') : true;
        $userGudangId = (function_exists('user') && user()) ? user()->id_gudang_wilayah : null;

        // Server-side authorization check: Petugas Gudang is strictly locked to own gudang
        if (function_exists('in_groups') && in_groups('Petugas Gudang') && $userGudangId) {
            $idGudang = $userGudangId;
        }

        if (!$idGudang) {
            return redirect()->to('wilayah/dashboard')->with('error', 'Gudang tidak ditentukan.');
        }

        $detail = $this->dashboardService->getGudangDetail($idGudang);

        if (!$detail) {
            return redirect()->to('wilayah/dashboard')->with('error', 'Gudang tidak ditemukan atau akses ditolak.');
        }

        $data = [
            'title'        => 'Detail Gudang Wilayah',
            'gudang'       => $detail['gudang'],
            'stok'         => $detail['stok'],
            'masuk'        => $detail['masuk'],
            'keluar'       => $detail['keluar'],
            'isAdmin'      => $isAdmin,
            'userGudangId' => $userGudangId
        ];

        return view('App\Modules\Wilayah\Views\dashboard\detail', $data);
    }
}
