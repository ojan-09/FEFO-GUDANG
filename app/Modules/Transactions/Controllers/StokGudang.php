<?php

namespace App\Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use App\Modules\Transactions\Models\BatchModel;
use App\Modules\MasterData\Models\BarangModel;

class StokGudang extends BaseController
{
    protected $batchModel;
    protected $barangModel;

    public function __construct()
    {
        $this->batchModel  = new BatchModel();
        $this->barangModel = new BarangModel();
    }

    /**
     * Tampilkan data Stok Gudang per-batch (Spreadsheet Style)
     */
    public function index()
    {
        helper('format');
        $db = \Config\Database::connect();
        $kategoriList = $db->table('kategori')->select('nama_kategori')->orderBy('nama_kategori', 'ASC')->get()->getResultArray();

        $data = [
            'title'    => 'Monitoring Stok Gudang',
            'kategori' => $kategoriList
        ];
        
        return view('App\Modules\Transactions\Views\stok_gudang\index', $data);
    }

    /**
     * Tampilkan daftar lengkap batch untuk suatu barang (Read-Only)
     */
    public function detail($idBarang)
    {
        $barang = $this->barangModel->find($idBarang);
        
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        // Ambil batch lengkap dari barang terpilih yang stoknya lebih dari 0
        $batches = $this->batchModel
            ->select('batch.*, donatur.nama_donatur')
            ->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left')
            ->where('batch.id_barang', $idBarang)
            ->where('batch.stok_saat_ini >', 0)
            ->orderBy('batch.tanggal_kedaluwarsa', 'ASC')
            ->findAll();
            
        // Hitung status dinamis tiap batch
        $today = new \DateTime(date('Y-m-d'));
        foreach ($batches as &$batch) {
            $batchStatus = 'Aman';
            $stok = (float) $batch['stok_saat_ini'];
            
            if ($stok > 0) {
                $expiredDate = new \DateTime($batch['tanggal_kedaluwarsa']);
                $diff = $today->diff($expiredDate);
                $days = (int) $diff->format('%R%a');
                
                if ($days < 0) {
                    $batchStatus = 'Expired';
                } elseif ($days <= 30) {
                    $batchStatus = 'Hampir Expired';
                }
            }
            
            $batch['status_dinamis'] = $batchStatus;
        }

        $data = [
            'title'   => 'Detail Stok: ' . $barang['nama_barang'],
            'barang'  => $barang,
            'batches' => $batches,
        ];
        
        return view('App\Modules\Transactions\Views\stok_gudang\detail', $data);
    }

    /**
     * AJAX endpoint untuk DataTables server-side
     */
    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        helper('format');
        $postData = $this->request->getPost();
        $list     = $this->batchModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        $today = new \DateTime(date('Y-m-d'));

        foreach ($list as $stok) {
            $no++;
            $row = [];

            // Hitung status dinamis & kedaluwarsa
            $status = 'Aman';
            $diffDays = 0;
            $expiredHtml = '-';
            if (!empty($stok['tanggal_kedaluwarsa'])) {
                $expiredDate = new \DateTime($stok['tanggal_kedaluwarsa']);
                $diff = $today->diff($expiredDate);
                $diffDays = (int) $diff->format('%R%a');
                
                if ($diffDays < 0) {
                    $status = 'Expired';
                } elseif ($diffDays <= 30) {
                    $status = 'Hampir Expired';
                }

                $tglFormatted = date('d M Y', strtotime($stok['tanggal_kedaluwarsa']));
                if ($diffDays < 0)       $expiredHtml = '<span class="wh-expired-over">Expired</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                elseif ($diffDays === 0) $expiredHtml = '<span class="wh-expired-soon">Hari Ini</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                elseif ($diffDays === 1) $expiredHtml = '<span class="wh-expired-soon">Besok</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                elseif ($diffDays <= 7)  $expiredHtml = '<span class="wh-expired-soon">' . $diffDays . ' Hari Lagi</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                else                     $expiredHtml = '<span class="wh-expired-ok">' . $tglFormatted . '</span>';
            }

            // Hitung Berat & Kemasan Awal
            $bisaDipecah    = (int) $stok['bisa_dipecah'];
            $beratPerSatuan = (float) $stok['berat_per_satuan'];
            $stokAktual     = (float) $stok['stok_saat_ini'];

            if ($bisaDipecah === 1) {
                $totalBerat  = $stokAktual;
                $kemasanAwal = !empty($stok['jumlah_ctn']) ? number_format($stok['jumlah_ctn'], 0, ',', '.') : '-';
                $stokSaatIni = number_format($stokAktual, 2, ',', '.');
                $satuanStok  = 'Kg';
            } else {
                $beratKg = $stokAktual * $beratPerSatuan;
                if (strtolower($stok['satuan_berat'] ?? '') === 'gram') $beratKg /= 1000;
                $totalBerat  = $beratKg;
                $kemasanAwal = !empty($stok['jumlah_ctn']) ? number_format($stok['jumlah_ctn'], 0, ',', '.') : '-';
                $stokSaatIni = number_format($stokAktual, 0, ',', '.');
                $satuanStok  = esc($stok['satuan']);
            }

            // Hitung Progress Bar (jika stok punya berat & kemasan awal)
            $pctStok  = null;
            $pctClass = 'green';
            if (!empty($stok['jumlah_ctn']) && $beratPerSatuan > 0) {
                $beratAwalKg = $stok['jumlah_ctn'] * $beratPerSatuan;
                if (strtolower($stok['satuan_berat'] ?? '') === 'gram') $beratAwalKg /= 1000;
                if ($beratAwalKg > 0) {
                    $pctStok = max(0, min(100, ($totalBerat / $beratAwalKg) * 100));
                    if ($pctStok < 30) $pctClass = 'red';
                    elseif ($pctStok < 70) $pctClass = 'amber';
                }
            }

            // Status Badge
            $badgeClass = 'default'; $badgeIcon = 'fa-circle';
            if ($status === 'Aman')               { $badgeClass = 'aman';    $badgeIcon = 'fa-circle-check'; }
            elseif ($status === 'Hampir Expired') { $badgeClass = 'hampir';  $badgeIcon = 'fa-triangle-exclamation'; }
            elseif ($status === 'Expired')        { $badgeClass = 'expired'; $badgeIcon = 'fa-ban'; }

            // Build Row html elements as original
            $row[] = '<div class="text-center text-secondary">' . $no . '</div>';
            $row[] = '<div class="text-center"><span class="wh-badge ' . $badgeClass . '"><i class="fa-solid ' . $badgeIcon . '"></i>' . esc($status) . '</span></div>';
            $row[] = '<div style="color:#475569;">' . esc($stok['donatur'] ?: '-') . '</div>';
            $row[] = '<div style="color:#475569;">' . esc($stok['kategori']) . '</div>';
            $row[] = '<div class="text-center">' . $expiredHtml . '</div>';
            $row[] = '<span class="fw-semibold" style="color:#0f172a;">' . esc($stok['nama_barang']) . '</span>';
            $satDisplay = esc($stok['satuan']);
            if ($bisaDipecah === 1) $satDisplay .= '<br><small class="text-success">(Repack)</small>';
            $row[] = '<div class="text-center" style="color:#475569;">' . $satDisplay . '</div>';
            
            $beratPerSatuanHtml = $beratPerSatuan > 0 ? number_format($beratPerSatuan, 2, ',', '.') . ' ' . esc($stok['satuan_berat']) : '-';
            $row[] = '<div class="text-end" style="color:#475569;">' . $beratPerSatuanHtml . '</div>';
            $row[] = '<div class="text-center" style="color:#475569;">' . $kemasanAwal . '</div>';
            
            // Kolom Stok & Berat (dengan progress bar)
            $stockHtml = '<div class="wh-stock-wrap">';
            $stockHtml .= '<div class="wh-stock-value">' . $stokSaatIni . ' ' . $satuanStok . '</div>';
            $totalBeratHtml = $totalBerat > 0 ? format_berat($totalBerat, 'Kg') : '-';
            $stockHtml .= '<div style="font-size:11px;color:#6b7280;">' . $totalBeratHtml . '</div>';
            if ($pctStok !== null) {
                $stockHtml .= '<div class="wh-progress ' . $pctClass . '"><span style="width:' . round($pctStok) . '%;"></span></div>';
            }
            $stockHtml .= '</div>';
            $row[] = $stockHtml;
            
            $row[] = '<small style="color:#6b7280;">' . esc($stok['catatan'] ?: '-') . '</small>';
            
            $aksi = '<div class="dm-action-group">
                        <a href="' . site_url('transaksi/stok-gudang/detail/' . $stok['id_barang']) . '" class="dm-btn-action view" title="Lihat Detail">
                            <i class="fa-solid fa-list"></i>
                        </a>
                     </div>';
            $row[] = '<div class="text-center">' . $aksi . '</div>';

            $data[] = $row;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->batchModel->countAllData(),
            "recordsFiltered" => $this->batchModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }
}

