<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanDonasi extends BaseController
{
    private function _getFilteredData()
    {
        helper('format');
        $db = \Config\Database::connect();
        
        $builder = $db->table('batch');
        $builder->select('
            batch.id,
            batch.jumlah_awal as jumlah,
            batch.jumlah_ctn,
            batch.tanggal_kedaluwarsa,
            batch.kategori as kategori_batch,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            barang_masuk.nomor_transaksi,
            barang_masuk.tanggal_masuk,
            barang_masuk.keterangan,
            batch.nilai_satuan,
            donatur.nama_donatur,
            users.username as petugas
        ');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');
        $builder->join('users', 'users.id = barang_masuk.id_user', 'left');
        
        // Filters
        $bulanFilter = $this->request->getGet('bulan') ?: date('m');
        $tahunFilter = $this->request->getGet('tahun') ?: date('Y');
        
        $startDateFilter = $this->request->getGet('start_date');
        $endDateFilter = $this->request->getGet('end_date');
        
        $donaturFilter = $this->request->getGet('donatur');
        $searchFilter = $this->request->getGet('search');
        $kategoriFilter = $this->request->getGet('kategori');
        $nomorFilter = $this->request->getGet('nomor_donasi'); // Tetap di-support jika diakses, tapi sesuai prompt utamanya
        
        // Cek mode custom
        $useCustomDate = false;
        if (!empty($startDateFilter) && !empty($endDateFilter)) {
            $useCustomDate = true;
            $builder->where('barang_masuk.tanggal_masuk >=', $startDateFilter);
            $builder->where('barang_masuk.tanggal_masuk <=', $endDateFilter);
        } else {
            // Gunakan periode bulan & tahun
            $builder->where('MONTH(barang_masuk.tanggal_masuk)', $bulanFilter);
            $builder->where('YEAR(barang_masuk.tanggal_masuk)', $tahunFilter);
        }
        
        if (!empty($donaturFilter)) {
            $builder->like('donatur.nama_donatur', $donaturFilter);
        }
        if (!empty($searchFilter)) {
            $builder->groupStart()
                ->like('batch.nama_barang', $searchFilter)
                ->orLike('barang.nama_barang', $searchFilter)
                ->groupEnd();
        }
        if (!empty($kategoriFilter)) {
            $builder->where('batch.kategori', $kategoriFilter);
        }
        if (!empty($nomorFilter)) {
            $builder->like('barang_masuk.nomor_transaksi', $nomorFilter);
        }

        $builder->orderBy('barang_masuk.tanggal_masuk', 'DESC');
        $builder->orderBy('barang_masuk.id', 'DESC');
        $data = $builder->get()->getResultArray();

        // Calculate Totals
        $totalTransaksi = 0;
        $totalBarang = 0;
        $totalBerat = 0;
        $totalNilaiDonasi = 0;
        $transaksiUnik = [];

        foreach ($data as $key => $row) {
            if (!in_array($row['nomor_transaksi'], $transaksiUnik)) {
                $transaksiUnik[] = $row['nomor_transaksi'];
                $totalTransaksi++;
            }
            $totalBarang += $row['jumlah'];
            $bisaDipecah = (int) ($row['bisa_dipecah'] ?? 0);
            $beratPerSatuan = (float) $row['berat_per_satuan'];
            if ($bisaDipecah === 1) {
                $weightInKg = (float) $row['jumlah'];
            } else {
                $totalBeratRow = $row['jumlah'] * $beratPerSatuan;
                $weightInKg = (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) ? ($totalBeratRow / 1000) : $totalBeratRow;
            }
            $totalBerat += $weightInKg;
            
            // Perhitungan nilai donasi
            $nilaiSatuan = (float)($row['nilai_satuan'] ?? 0);
            $totalNilaiBaris = (float)$row['jumlah'] * $nilaiSatuan;
            $data[$key]['total_nilai'] = $totalNilaiBaris; // save for view
            $totalNilaiDonasi += $totalNilaiBaris;
        }

        return [
            'data' => $data,
            'summary' => [
                'total_transaksi' => $totalTransaksi,
                'total_barang' => $totalBarang,
                'total_berat' => $totalBerat,
                'total_nilai_donasi' => $totalNilaiDonasi
            ],
            'filters' => [
                'bulan' => $bulanFilter,
                'tahun' => $tahunFilter,
                'start_date' => $startDateFilter,
                'end_date' => $endDateFilter,
                'donatur' => $donaturFilter,
                'search' => $searchFilter,
                'kategori' => $kategoriFilter,
                'nomor_donasi' => $nomorFilter,
                'use_custom_date' => $useCustomDate
            ]
        ];
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        $kategoriList = $db->table('kategori')->select('nama_kategori')->orderBy('nama_kategori', 'ASC')->get()->getResultArray();

        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $tahunList = [];
        $currentYear = date('Y');
        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
            $tahunList[] = $i;
        }

        $filters = [
            'bulan' => $this->request->getGet('bulan') ?: date('m'),
            'tahun' => $this->request->getGet('tahun') ?: date('Y'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'donatur' => $this->request->getGet('donatur'),
            'search' => $this->request->getGet('search'),
            'kategori' => $this->request->getGet('kategori'),
            'nomor_donasi' => $this->request->getGet('nomor_donasi'),
            'use_custom_date' => (!empty($this->request->getGet('start_date')) && !empty($this->request->getGet('end_date')))
        ];

        $data = [
            'title'      => 'Laporan Donasi Masuk',
            'filters'    => $filters,
            'kategori'   => $kategoriList,
            'bulanList'  => $bulanList,
            'tahunList'  => $tahunList
        ];
        
        return view('App\Modules\Reports\Views\laporan_donasi\index', $data);
    }

    public function ajaxData()
    {
        try {
            $postData = $this->request->getPost();
            $model = new \App\Modules\Reports\Models\LaporanDonasiModel();

            $list = $model->getDatatables($postData);
            $data = [];
            $no = (int)($postData['start'] ?? 0);

            helper('format');
            foreach ($list as $item) {
                $no++;
                $bisaDipecah    = (int) ($item['bisa_dipecah'] ?? 0);
                $beratPerSatuan = (float) $item['berat_per_satuan'];
                if ($bisaDipecah === 1) {
                    $totalBeratRow = (float) $item['jumlah'];
                    if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                        $totalBeratRow /= 1000;
                    }
                } else {
                    $totalBeratRow = $item['jumlah'] * $beratPerSatuan;
                    if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                        $totalBeratRow /= 1000;
                    }
                }

                $tglMasuk = $item['tanggal_masuk'] ? date('d/m/Y', strtotime($item['tanggal_masuk'])) : '-';
                $tglExp   = $item['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($item['tanggal_kedaluwarsa'])) : '-';
                $beratBersihText = $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-';
                $totalBeratText  = $totalBeratRow > 0 ? format_berat($totalBeratRow, 'Kg') : '-';

                $data[] = [
                    'no'                  => $no,
                    'tanggal_masuk'       => $tglMasuk,
                    'nomor_transaksi'     => esc($item['nomor_transaksi']),
                    'nama_donatur'        => esc($item['nama_donatur'] ?? '-'),
                    'nama_barang'         => '<strong>' . esc($item['nama_barang']) . '</strong>',
                    'kategori_batch'      => esc($item['kategori_batch']),
                    'jumlah'              => number_format($item['jumlah'], 0, ',', '.'),
                    'satuan'              => esc($item['satuan']),
                    'jumlah_ctn'          => !empty($item['jumlah_ctn']) ? $item['jumlah_ctn'] : '-',
                    'berat_bersih'        => $beratBersihText,
                    'total_berat'         => $totalBeratText,
                    'tanggal_kedaluwarsa' => $tglExp,
                    'keterangan'          => '<small>' . esc($item['keterangan'] ?? '-') . '</small>',
                    'petugas'             => esc($item['petugas'] ?? '-')
                ];
            }

            $summary = $model->getSummaryData($postData);

            $output = [
                'draw'            => intval($postData['draw'] ?? 0),
                'recordsTotal'    => $model->countAllData($postData),
                'recordsFiltered' => $model->countFiltered($postData),
                'data'            => $data,
                'summary'         => $summary,
                'csrf_hash'       => csrf_hash()
            ];

            return $this->response->setJSON($output);
        } catch (\Throwable $e) {
            log_message('error', 'LaporanDonasi::ajaxData error: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw'            => intval($this->request->getPost('draw') ?? 0),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'summary'         => ['total_transaksi' => 0, 'total_barang' => 0, 'total_berat' => 0, 'total_nilai_donasi' => 0],
                'error'           => 'Terjadi kesalahan saat memuat data.',
                'csrf_hash'       => csrf_hash()
            ]);
        }
    }

    public function pdf()
    {
        if (!in_groups(['Administrator', 'Petugas Gudang', 'Pimpinan'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Administrator yang dapat mengunduh laporan ini.');
        }

        $result = $this->_getFilteredData();
        $data = [
            'title' => 'LAPORAN DONASI MASUK',
            'laporan' => $result['data'],
            'summary' => $result['summary'],
            'filters' => $result['filters']
        ];

        $html = view('App\Modules\Reports\Views\laporan_donasi\pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Donasi_Masuk_" . date('Ymd_His') . ".pdf", ["Attachment" => false]);
    }

    public function excel()
    {
        if (!in_groups(['Administrator', 'Petugas Gudang', 'Pimpinan'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Administrator yang dapat mengunduh laporan ini.');
        }

        helper('format');
        $result = $this->_getFilteredData();
        $laporan = $result['data'];
        $summary = $result['summary'];
        $filters = $result['filters'];
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(10);
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        $pageSetup = $sheet->getPageSetup();
        $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $pageSetup->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $pageSetup->setFitToWidth(1);
        $pageSetup->setFitToHeight(0);
        $sheet->getPageMargins()->setTop(0.75)->setRight(0.7)->setLeft(0.7)->setBottom(0.75);
        
        $pageSetup->setRowsToRepeatAtTopByStartAndEnd(3, 3);
        
        // Baris 1: Header
        $sheet->setCellValue('A1', 'FOODBANK OF INDONESIA');
        $sheet->mergeCells('A1:P1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'LAPORAN DONASI MASUK');
        $sheet->mergeCells('A2:P2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Baris 3: Table Headers
        $headers = [
            'A3' => 'No',
            'B3' => 'Tanggal Masuk',
            'C3' => 'Nomor Donasi',
            'D3' => 'Donatur',
            'E3' => 'Nama Barang',
            'F3' => 'Kategori',
            'G3' => 'Jumlah',
            'H3' => 'Satuan',
            'I3' => 'CTN',
            'J3' => 'Berat Bersih',
            'K3' => 'Total Berat',
            'L3' => 'Tanggal Kedaluwarsa',
            'M3' => 'Keterangan',
            'N3' => 'Nilai Satuan',
            'O3' => 'Total Nilai',
            'P3' => 'Petugas'
        ];
        
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                   ->setVertical(Alignment::VERTICAL_CENTER)
                                                   ->setWrapText(true);
        }
        
        $sheet->getStyle('A3:P3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Freeze panes
        $sheet->freezePane('A4');
        
        // Auto filter
        $sheet->setAutoFilter('A3:P3');

        $row = 4;
        $no = 1;
        
        foreach ($laporan as $item) {
            $bisaDipecah = (int) ($item['bisa_dipecah'] ?? 0);
            $beratPerSatuan = (float) $item['berat_per_satuan'];
            if ($bisaDipecah === 1) {
                $totalBeratRow = (float) $item['jumlah'];
                if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalBeratRow *= 1000;
                }
            } else {
                $totalBeratRow = $item['jumlah'] * $beratPerSatuan;
            }

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d-M-Y', strtotime($item['tanggal_masuk'])));
            $sheet->setCellValue('C' . $row, $item['nomor_transaksi']);
            $sheet->setCellValue('D' . $row, $item['nama_donatur'] ?? '-');
            $sheet->setCellValue('E' . $row, $item['nama_barang']);
            $sheet->setCellValue('F' . $row, $item['kategori_batch']);
            $sheet->setCellValue('G' . $row, $item['jumlah']);
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('H' . $row, $item['satuan']);
            $sheet->setCellValue('I' . $row, $item['jumlah_ctn'] ?? '-');
            $sheet->setCellValue('J' . $row, $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-');
            $sheet->setCellValue('K' . $row, $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-');
            $sheet->setCellValue('L' . $row, $item['tanggal_kedaluwarsa'] ? date('d-M-Y', strtotime($item['tanggal_kedaluwarsa'])) : '-');
            $sheet->setCellValue('M' . $row, $item['keterangan'] ?? '-');
            
            $sheet->setCellValue('N' . $row, $item['nilai_satuan'] ?? 0);
            $sheet->getStyle('N' . $row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->setCellValue('O' . $row, $item['total_nilai'] ?? 0);
            $sheet->getStyle('O' . $row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            
            $sheet->setCellValue('P' . $row, $item['petugas'] ?? '-');

            // Alignment
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("N{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("O{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("P{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("A{$row}:P{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $row++;
        }

        // Auto Width for all columns
        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Blank row before summary
        $row++;
        
        // Summary
        $sheet->setCellValue("B{$row}", "Total Transaksi");
        $sheet->setCellValue("C{$row}", $summary['total_transaksi']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Barang");
        $sheet->setCellValue("C{$row}", $summary['total_barang']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Berat");
        $sheet->setCellValue("C{$row}", $summary['total_berat']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Nilai Donasi Masuk");
        $sheet->setCellValue("C{$row}", $summary['total_nilai_donasi']);
        $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Donasi_Masuk_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}

