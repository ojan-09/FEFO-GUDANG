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
        $isAdmin = function_exists('in_groups') ? in_groups('Administrator') : true;
        $userGudangId = (function_exists('user') && user()) ? user()->id_gudang_wilayah : null;
        
        $cacheKey = 'gudang_wilayah_list_all';
        $allGudang = cache()->get($cacheKey);
        
        if ($allGudang === null) {
            $allGudang = $this->gudangModel->findAll();
            cache()->save($cacheKey, $allGudang, 3600);
        }

        if (!$isAdmin && $userGudangId) {
            $gudang = array_filter($allGudang, function($g) use ($userGudangId) {
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

    private function buildQuery()
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
            $builder->select('s.*, m.nama as nama_gudang, m.kota, m.provinsi, brg.nama_barang, brg.kode_barang, kat.nama_kategori as kategori, brg.satuan, brg.berat_per_satuan as berat');
            $builder->join('master_gudang_wilayah m', 'm.id = s.id_gudang');
            $builder->join('master_barang_wilayah brg', 'brg.id = s.id_barang');
            $builder->join('kategori kat', 'kat.id = brg.id_kategori', 'left');

            if (!empty($idGudang)) {
                $builder->where('s.id_gudang', $idGudang);
            }
            if (!empty($provinsi)) {
                $builder->like('m.provinsi', $provinsi);
            }
            $builder->orderBy('m.nama', 'ASC');
            $builder->orderBy('brg.nama_barang', 'ASC');
            return $builder;
        }

        $table       = ($jenis == 'keluar') ? 'barang_keluar_wilayah'        : 'barang_masuk_wilayah';
        $detailTable = ($jenis == 'keluar') ? 'detail_barang_keluar_wilayah' : 'detail_barang_masuk_wilayah';
        $foreignKey  = ($jenis == 'keluar') ? 'id_keluar'                    : 'id_masuk';

        $builder = $db->table("$table t");

        if ($jenis == 'keluar') {
            $builder->select('t.*, d.jumlah, d.satuan,
                COALESCE(d.berat_per_satuan, s.berat_per_satuan, 0) as berat_referensi,
                COALESCE(d.satuan_berat, s.satuan_berat, "Kg") as satuan_berat,
                m.nama as nama_gudang, m.kota, m.provinsi,
                brg.nama_barang, brg.kode_barang,
                kat.nama_kategori as kategori,
                u.username as nama_user');
            $builder->join('users u', 'u.id = t.created_by', 'left');
        } else {
            $builder->select('t.*, dn.nama_donatur as donatur,
                d.jumlah, d.satuan, d.berat_per_satuan,
                COALESCE(d.satuan_berat, "Kg") as satuan_berat,
                d.harga_satuan, d.subtotal_nilai,
                m.nama as nama_gudang, m.kota, m.provinsi,
                brg.nama_barang, brg.kode_barang,
                kat.nama_kategori as kategori,
                u.username as nama_user');
            $builder->join('donatur dn', 'dn.id = t.id_donatur', 'left');
            $builder->join('users u', 'u.id = t.created_by', 'left');
        }

        // JOIN utama — d harus didefinisikan sebelum stok
        $builder->join('master_gudang_wilayah m', 'm.id = t.id_gudang');
        $builder->join("$detailTable d", "d.$foreignKey = t.id");
        $builder->join('master_barang_wilayah brg', 'brg.id = d.id_barang');
        $builder->join('kategori kat', 'kat.id = brg.id_kategori', 'left');

        // JOIN stok SETELAH d didefinisikan — fix bug "Unknown column d.id_barang in on clause"
        if ($jenis == 'keluar') {
            $builder->join('stok_gudang_wilayah s', 's.id_gudang = t.id_gudang AND s.id_barang = d.id_barang', 'left');
        }

        $builder->where('t.deleted_at', null);

        if (!empty($idGudang)) {
            $builder->where('t.id_gudang', $idGudang);
        }
        if (!empty($provinsi)) {
            $builder->like('m.provinsi', $provinsi);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $builder->where('t.tanggal >=', $startDate);
            $builder->where('t.tanggal <=', $endDate);
        }

        $builder->orderBy('t.tanggal', 'DESC');

        return $builder;
    }

    private function buildCountQuery()
    {
        $idGudang  = $this->request->getVar('id_gudang');
        $provinsi  = $this->request->getVar('provinsi');
        $jenis     = $this->request->getVar('jenis');

        if (!in_groups('Administrator')) {
            $idGudang = user()->id_gudang_wilayah;
        }

        $db = \Config\Database::connect();

        if ($jenis == 'stok') {
            $builder = $db->table("stok_gudang_wilayah s");
            if (!empty($provinsi)) {
                $builder->join('master_gudang_wilayah m', 'm.id = s.id_gudang');
                $builder->like('m.provinsi', $provinsi);
            }
            if (!empty($idGudang)) {
                $builder->where('s.id_gudang', $idGudang);
            }
            return $builder;
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

        $startDate = $this->request->getVar('start_date');
        $endDate   = $this->request->getVar('end_date');
        if (!empty($startDate) && !empty($endDate)) {
            $builder->where('t.tanggal >=', $startDate);
            $builder->where('t.tanggal <=', $endDate);
        }

        return $builder;
    }

    public function ajaxData()
    {
        try {
            $countBuilder = $this->buildCountQuery();
            $totalRecords = $countBuilder->countAllResults(false);

            $length = $this->request->getPost('length') ?? 10;
            $start  = $this->request->getPost('start')  ?? 0;
            $search = $this->request->getPost('search')['value'] ?? '';
            $jenis  = $this->request->getVar('jenis');

            if (!empty($search)) {
                $builder = $this->buildQuery();
                $builder->groupStart();
                if ($jenis == 'stok') {
                    $builder->like('brg.nama_barang', $search);
                    $builder->orLike('brg.kode_barang', $search);
                    $builder->orLike('m.nama', $search);
                } else {
                    $builder->like('t.nomor_dokumen', $search);
                    $builder->orLike('brg.nama_barang', $search);
                    $builder->orLike('m.nama', $search);
                }
                $builder->groupEnd();
                $filteredBuilder = clone $builder;
                $filteredRecords = $filteredBuilder->countAllResults(false);
            } else {
                $filteredRecords = $totalRecords;
                $builder = $this->buildQuery();
            }

            // Dynamic Sorting
            $orderParam = $this->request->getPost('order');
            if (!empty($orderParam) && isset($orderParam[0]['column'])) {
                $colIdx = (int)$orderParam[0]['column'];
                $dir    = strtoupper($orderParam[0]['dir']) === 'DESC' ? 'DESC' : 'ASC';
                if ($jenis == 'stok') {
                    $stokMap = [
                        1 => 'm.nama', 2 => 'brg.nama_barang',
                        3 => 'kat.nama_kategori', 4 => 's.jumlah',
                        5 => 'brg.satuan', 6 => 'brg.berat_per_satuan'
                    ];
                    if (isset($stokMap[$colIdx])) $builder->orderBy($stokMap[$colIdx], $dir);
                } else {
                    $txMap = [
                        1 => 't.tanggal', 2 => 't.nomor_dokumen',
                        3 => 'm.nama',    5 => 'brg.nama_barang', 7 => 'd.jumlah'
                    ];
                    if (isset($txMap[$colIdx])) $builder->orderBy($txMap[$colIdx], $dir);
                }
            }

            $builder->limit($length, $start);
            $data = $builder->get()->getResultArray();

            $resultData = [];
            $no = $start + 1;
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
                        esc($row['donatur']),
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
                        esc($row['tujuan']),
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
            return "LAPORAN BARANG $jenisStr - SELURUH GUDANG WILAYAH";
        } else {
            $gudang     = $this->gudangModel->find($idGudang);
            $namaGudang = strtoupper($gudang['nama'] ?? 'GUDANG');
            return "LAPORAN BARANG $jenisStr - $namaGudang";
        }
    }

    public function pdf()
    {
        $builder     = $this->buildQuery();
        $dataLaporan = $builder->get()->getResultArray();
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
        $builder     = $this->buildQuery();
        $dataLaporan = $builder->get()->getResultArray();
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
                $sheet->setCellValue('E' . $row, $item['donatur']);
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
                $sheet->setCellValue('E' . $row, $item['tujuan']);
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