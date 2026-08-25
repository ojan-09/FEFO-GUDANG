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

    public function index()
    {
        helper('format');
        $db = \Config\Database::connect();
        $kategoriList = $db->table('kategori')
            ->select('nama_kategori')
            ->orderBy('nama_kategori', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'    => 'Monitoring Stok Gudang',
            'kategori' => $kategoriList,
        ];

        return view('App\Modules\Transactions\Views\stok_gudang\index', $data);
    }

    public function detail($idBarang)
    {
        $barang = $this->barangModel->find($idBarang);

        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $batches = $this->batchModel
            ->select('batch.*, donatur.nama_donatur')
            ->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left')
            ->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left')
            ->where('batch.id_barang', $idBarang)
            ->where('batch.stok_saat_ini >', 0)
            ->orderBy('batch.tanggal_kedaluwarsa', 'ASC')
            ->findAll();

        $today = new \DateTime(date('Y-m-d'));
        foreach ($batches as &$batch) {
            $batchStatus = 'Aman';
            $stok = (float) $batch['stok_saat_ini'];

            if ($stok > 0) {
                $expiredDate = new \DateTime($batch['tanggal_kedaluwarsa']);
                $diff        = $today->diff($expiredDate);
                $days        = (int) $diff->format('%R%a');

                if ($days < 0)       $batchStatus = 'Expired';
                elseif ($days <= 30) $batchStatus = 'Hampir Expired';
            }

            $batch['status_dinamis'] = $batchStatus;
        }

        $riwayatPenyaluran = $this->batchModel->getRiwayatPenyaluranByBarang((int) $idBarang);

        $data = [
            'title'              => 'Detail Stok: ' . $barang['nama_barang'],
            'barang'             => $barang,
            'batches'            => $batches,
            'riwayat_penyaluran' => $riwayatPenyaluran,
        ];

        return view('App\Modules\Transactions\Views\stok_gudang\detail', $data);
    }

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

            $status      = 'Aman';
            $diffDays    = 0;
            $expiredHtml = '-';

            if (!empty($stok['tanggal_kedaluwarsa'])) {
                $expiredDate = new \DateTime($stok['tanggal_kedaluwarsa']);
                $diff        = $today->diff($expiredDate);
                $diffDays    = (int) $diff->format('%R%a');

                if ($diffDays < 0)       $status = 'Expired';
                elseif ($diffDays <= 30) $status = 'Hampir Expired';

                $tglFormatted = date('d M Y', strtotime($stok['tanggal_kedaluwarsa']));

                if ($diffDays < 0) {
                    $expiredHtml = '<span class="wh-expired-over">Expired</span>'
                                 . '<span class="wh-expired-date">' . $tglFormatted . '</span>';
                } elseif ($diffDays === 0) {
                    $expiredHtml = '<span class="wh-expired-soon">Hari Ini</span>'
                                 . '<span class="wh-expired-date">' . $tglFormatted . '</span>';
                } elseif ($diffDays === 1) {
                    $expiredHtml = '<span class="wh-expired-soon">Besok</span>'
                                 . '<span class="wh-expired-date">' . $tglFormatted . '</span>';
                } elseif ($diffDays <= 7) {
                    $expiredHtml = '<span class="wh-expired-soon">' . $diffDays . ' Hari Lagi</span>'
                                 . '<span class="wh-expired-date">' . $tglFormatted . '</span>';
                } else {
                    $expiredHtml = '<span class="wh-expired-ok">' . $tglFormatted . '</span>';
                }
            }

            $umurStokHari = 0;
            if (isset($stok['umur_stok_hari']) && $stok['umur_stok_hari'] !== null) {
                $umurStokHari = max(0, (int) $stok['umur_stok_hari']);
            } elseif (!empty($stok['tanggal_masuk'])) {
                $tglMasuk     = new \DateTime($stok['tanggal_masuk']);
                $diffMasuk    = $tglMasuk->diff($today);
                $umurStokHari = max(0, (int) $diffMasuk->format('%r%a'));
            }

            $ageBadgeClass = 'normal';
            $ageBadgeIcon  = 'fa-clock';
            if ($umurStokHari > 90) {
                $ageBadgeClass = 'danger';
                $ageBadgeIcon  = 'fa-hourglass-end';
            } elseif ($umurStokHari > 30) {
                $ageBadgeClass = 'warning';
                $ageBadgeIcon  = 'fa-clock-rotate-left';
            }

            $umurStokHtml = '<span class="wh-badge-age ' . $ageBadgeClass . '">'
                          . '<i class="fa-solid ' . $ageBadgeIcon . ' me-1"></i>'
                          . $umurStokHari . ' Hari</span>';

            $bisaDipecah    = (int)   $stok['bisa_dipecah'];
            $beratPerSatuan = (float) $stok['berat_per_satuan'];
            $stokAktual     = (float) $stok['stok_saat_ini'];

            if (!empty($stok['jumlah_ctn']) && !empty($stok['isi_per_ctn'])) {
                $kemasanAwal = number_format($stok['jumlah_ctn'], 0, ',', '.') . ' CTN (' . number_format($stok['isi_per_ctn'], 0, ',', '.') . ' ' . esc($stok['satuan']) . '/CTN)';
            } elseif (!empty($stok['jumlah_ctn'])) {
                $kemasanAwal = number_format($stok['jumlah_ctn'], 0, ',', '.') . ' CTN';
            } else {
                $kemasanAwal = '-';
            }

            if ($bisaDipecah === 1) {
                $totalBerat  = $stokAktual;
                $stokSaatIni = number_format($stokAktual, 2, ',', '.');
                $satuanStok  = 'Kg';
            } else {
                $beratKg = $stokAktual * $beratPerSatuan;
                $satB = strtolower(trim($stok['satuan_berat'] ?? ''));
                if (in_array($satB, ['gram', 'g', 'gr', 'ml'])) $beratKg /= 1000;
                $totalBerat  = $beratKg;
                $stokSaatIni = number_format($stokAktual, 0, ',', '.');
                $satuanStok  = esc($stok['satuan']);
            }

            $pctStok   = null;
            $barColor  = 'green';
            $pillClass = 'pill-green';
            $pillIcon  = 'fa-check';

            $maxAwal = null;
            if (!empty($stok['jumlah_awal']) && (float)$stok['jumlah_awal'] > 0) {
                $maxAwal = (float) $stok['jumlah_awal'];
            } elseif (!empty($stok['jumlah_ctn']) && (float)$stok['jumlah_ctn'] > 0) {
                $maxAwal = (float) $stok['jumlah_ctn'];
            }

            if ($maxAwal !== null && $maxAwal > 0) {
                $pctStok = max(0, min(100, ($stokAktual / $maxAwal) * 100));
                if ($pctStok < 30) {
                    $barColor  = 'red';
                    $pillClass = 'pill-red';
                    $pillIcon  = 'fa-circle';
                } elseif ($pctStok < 60) {
                    $barColor  = 'amber';
                    $pillClass = 'pill-amber';
                    $pillIcon  = 'fa-triangle-exclamation';
                }
            } else {
                if (!empty($stok['jumlah_ctn']) && $beratPerSatuan > 0) {
                    $beratAwalKg = $stok['jumlah_ctn'] * $beratPerSatuan;
                    $satB = strtolower(trim($stok['satuan_berat'] ?? ''));
                    if (in_array($satB, ['gram', 'g', 'gr', 'ml'])) $beratAwalKg /= 1000;
                    if ($beratAwalKg > 0) {
                        $pctStok = max(0, min(100, ($totalBerat / $beratAwalKg) * 100));
                        if ($pctStok < 30) {
                            $barColor  = 'red';
                            $pillClass = 'pill-red';
                            $pillIcon  = 'fa-circle';
                        } elseif ($pctStok < 60) {
                            $barColor  = 'amber';
                            $pillClass = 'pill-amber';
                            $pillIcon  = 'fa-triangle-exclamation';
                        }
                    }
                }
            }

            $pctRound = $pctStok !== null ? round($pctStok) : 0;

            $badgeClass = 'default';
            $badgeIcon  = 'fa-circle';
            if ($status === 'Aman') {
                $badgeClass = 'aman';
                $badgeIcon  = 'fa-circle-check';
            } elseif ($status === 'Hampir Expired') {
                $badgeClass = 'hampir';
                $badgeIcon  = 'fa-triangle-exclamation';
            } elseif ($status === 'Expired') {
                $badgeClass = 'expired';
                $badgeIcon  = 'fa-ban';
            }

            $row[] = '<div class="text-center text-secondary">' . $no . '</div>';
            $row[] = '<div class="text-center"><span class="wh-badge ' . $badgeClass . '"><i class="fa-solid ' . $badgeIcon . '"></i>' . esc($status) . '</span></div>';
            $row[] = '<div style="color:#475569;">' . esc($stok['donatur'] ?: '-') . '</div>';
            $row[] = '<div style="color:#475569;">' . esc($stok['kategori']) . '</div>';
            $row[] = '<div class="text-center">' . $expiredHtml . '</div>';
            $row[] = '<div class="text-center">' . $umurStokHtml . '</div>';
            $row[] = '<span class="fw-semibold" style="color:#0f172a;">' . esc($stok['nama_barang']) . '</span>';

            $satDisplay = esc($stok['satuan']);
            if ($bisaDipecah === 1) $satDisplay .= '<br><small class="text-success">(Repack)</small>';
            $row[] = '<div class="text-center" style="color:#475569;">' . $satDisplay . '</div>';

            $beratPerSatuanHtml = $beratPerSatuan > 0
                ? number_format($beratPerSatuan, 2, ',', '.') . ' ' . esc($stok['satuan_berat'])
                : '-';
            $row[] = '<div class="text-end" style="color:#475569;">' . $beratPerSatuanHtml . '</div>';
            $row[] = '<div class="text-center" style="color:#475569;">' . $kemasanAwal . '</div>';

            $totalBeratHtml = $totalBerat > 0 ? format_berat($totalBerat, 'Kg') : '-';
            $stockHtml  = '<div class="wh-stock-wrap">';
            $stockHtml .= '<div class="wh-stok-row">';
            $stockHtml .= '<span class="wh-stok-val">' . $stokSaatIni . ' <span class="wh-stok-sat">' . $satuanStok . '</span></span>';
            if ($maxAwal !== null && $maxAwal > 0) {
                $stockHtml .= '<span class="wh-stok-max">/ ' . number_format($maxAwal, 0, ',', '.') . '</span>';
            }
            $stockHtml .= '</div>';
            if ($pctStok !== null) {
                $stockHtml .= '<div class="wh-bar-track"><div class="wh-bar-fill ' . $barColor . '" style="width:' . $pctRound . '%"></div></div>';
            }
            $stockHtml .= '<div class="wh-berat-row">';
            $stockHtml .= '<span class="wh-berat-val">' . $totalBeratHtml . '</span>';
            if ($pctStok !== null) {
                $stockHtml .= '<span class="wh-pill ' . $pillClass . '"><i class="fa-solid ' . $pillIcon . ' me-1"></i>' . $pctRound . '%</span>';
            }
            $stockHtml .= '</div>';

            if (!empty($stok['isi_per_ctn']) && (int)$stok['isi_per_ctn'] > 0) {
                $isiCtn  = (int)$stok['isi_per_ctn'];
                $ctnSisa = floor($stokAktual / $isiCtn);
                $pcsSisa = fmod($stokAktual, $isiCtn);
                if ($ctnSisa > 0 && $pcsSisa > 0) {
                    $sisaText = number_format($ctnSisa, 0, ',', '.') . ' CTN + ' . number_format($pcsSisa, 0, ',', '.') . ' ' . esc($stok['satuan']);
                } elseif ($ctnSisa > 0) {
                    $sisaText = number_format($ctnSisa, 0, ',', '.') . ' CTN';
                } elseif ($pcsSisa > 0) {
                    $sisaText = number_format($pcsSisa, 0, ',', '.') . ' ' . esc($stok['satuan']);
                } else {
                    $sisaText = '0 CTN';
                }
                $stockHtml .= '<div class="text-secondary fw-semibold mt-1" style="font-size: 11px;"><i class="fa-solid fa-box-open me-1 text-primary"></i>Sisa Kemasan: ' . $sisaText . '</div>';
            }

            $stockHtml .= '</div>';
            $row[] = $stockHtml;

            $row[] = '<div style="min-width:180px;white-space:normal;"><small style="color:#6b7280;">' . esc($stok['catatan'] ?: '-') . '</small></div>';

            $aksi  = '<div class="dm-action-group">'
                   . '<a href="' . site_url('transaksi/stok-gudang/detail/' . $stok['id_barang']) . '" '
                   . 'class="dm-btn-action view" title="Lihat Detail">'
                   . '<i class="fa-solid fa-list"></i>'
                   . '</a></div>';
            $row[] = '<div class="text-center">' . $aksi . '</div>';

            $data[] = $row;
        }

        $output = [
            'draw'            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            'recordsTotal'    => $this->batchModel->countAllData(),
            'recordsFiltered' => $this->batchModel->countFiltered($postData),
            'data'            => $data,
            csrf_token()      => csrf_hash(),
        ];

        return $this->response->setJSON($output);
    }
}