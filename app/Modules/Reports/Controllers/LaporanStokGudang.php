<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanStokGudang extends BaseController
{
    private function _getFilteredData(){
        helper('format');
        $db = \Config\Database::connect();
        
        $builder = $db->table('batch');
        $builder->select('
            batch.id,
            batch.id_barang,
            batch.kategori,
            batch.stok_saat_ini,
            batch.tanggal_kedaluwarsa,
            batch.jumlah_ctn,
            barang_masuk.keterangan as catatan,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            donatur.nama_donatur as donatur
        ');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');
        $builder->where('batch.stok_saat_ini >', 0);
        
        // Filters
        $searchFilter = $this->request->getGet('search');
        $donaturFilter = $this->request->getGet('donatur');
        $kategoriFilter = $this->request->getGet('kategori');
        $statusFilter = $this->request->getGet('status');
        $startDateFilter = $this->request->getGet('start_date');
        $endDateFilter = $this->request->getGet('end_date');
        
        if (!empty($searchFilter)) {
            $builder->groupStart()
                ->like('batch.nama_barang', $searchFilter)
                ->orLike('barang.nama_barang', $searchFilter)
                ->groupEnd();
        }
        if (!empty($donaturFilter)) {
            $builder->like('donatur.nama_donatur', $donaturFilter);
        }
        if (!empty($kategoriFilter)) {
            $builder->where('batch.kategori', $kategoriFilter);
        }
        if (!empty($startDateFilter)) {
            $builder->where('batch.tanggal_kedaluwarsa >=', $startDateFilter);
        }
        if (!empty($endDateFilter)) {
            $builder->where('batch.tanggal_kedaluwarsa <=', $endDateFilter);
        }

        if (!empty($statusFilter)) {
            $todayStr = date('Y-m-d');
            $hampirExpiredStr = date('Y-m-d', strtotime('+30 days'));
            
            if ($statusFilter === 'Expired') {
                $builder->where('batch.tanggal_kedaluwarsa <', $todayStr);
            } elseif ($statusFilter === 'Hampir Expired') {
                $builder->where('batch.tanggal_kedaluwarsa >=', $todayStr);
                $builder->where('batch.tanggal_kedaluwarsa <=', $hampirExpiredStr);
            } elseif ($statusFilter === 'Aman') {
                $builder->where('batch.tanggal_kedaluwarsa >', $hampirExpiredStr);
            }
        }

        $builder->orderBy('batch.tanggal_kedaluwarsa', 'ASC');
        $stokGudang = $builder->get()->getResultArray();

        $filteredData = [];
        $totalBerat = 0;
        $today = new \DateTime(date('Y-m-d'));
        
        foreach ($stokGudang as &$stok) {
            $status = 'Aman';
            
            if (!empty($stok['tanggal_kedaluwarsa'])) {
                $expiredDate = new \DateTime($stok['tanggal_kedaluwarsa']);
                $diff = $today->diff($expiredDate);
                $days = (int) $diff->format('%R%a');
                
                if ($days < 0) {
                    $status = 'Expired';
                } elseif ($days <= 30) {
                    $status = 'Hampir Expired';
                }
            }
            
            $stok['status'] = $status;
            
            $filteredData[] = $stok;
            
            $bisaDipecah = (int) $stok['bisa_dipecah'];
            $beratPerSatuan = (float) $stok['berat_per_satuan'];
            
            if ($bisaDipecah === 1) {
                // For repackable items, stok_saat_ini is already in Kg
                $weightInKg = (float) $stok['stok_saat_ini'];
            } else {
                $totalBeratRow = $stok['stok_saat_ini'] * $beratPerSatuan;
                $weightInKg = (in_array(strtolower(trim($stok['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) ? ($totalBeratRow / 1000) : $totalBeratRow;
            }
            $totalBerat += $weightInKg;
        }

        return [
            'data' => $filteredData,
            'filters' => [
                'search' => $searchFilter,
                'donatur' => $donaturFilter,
                'kategori' => $kategoriFilter,
                'status' => $statusFilter,
                'start_date' => $startDateFilter,
                'total_berat' => $totalBerat,
                'end_date' => $endDateFilter
            ]
        ];
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        $kategoriList = $db->table('kategori')->select('nama_kategori')->orderBy('nama_kategori', 'ASC')->get()->getResultArray();

        $filters = [
            'search'     => $this->request->getGet('search') ?? '',
            'donatur'    => $this->request->getGet('donatur') ?? '',
            'kategori'   => $this->request->getGet('kategori') ?? '',
            'status'     => $this->request->getGet('status') ?? '',
            'start_date' => $this->request->getGet('start_date') ?? '',
            'end_date'   => $this->request->getGet('end_date') ?? ''
        ];

        $data = [
            'title'    => 'Laporan Stok Gudang',
            'kategori' => $kategoriList,
            'filters'  => $filters
        ];
        
        return view('App\Modules\Reports\Views\laporan_stok\index', $data);
    }

    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Direct access not allowed');
        }

        try {
            $postData = $this->request->getPost();
            $model = new \App\Modules\Reports\Models\LaporanStokGudangModel();
            
            $list = $model->getDatatables($postData);
            $data = [];
            $no = $postData['start'] ?? 0;
            
            foreach ($list as $stok) {
                $no++;
                $row = [];
                
                $row[] = '<div class="text-center">' . $no . '</div>';
                
                // Status
                $days = (int)$stok['sisa_hari'];
                $statusHtml = '';
                if ($days < 0) {
                    $statusHtml = '<span class="wh-badge expired"><i class="fa-solid fa-ban"></i>Expired</span>';
                } elseif ($days <= 30) {
                    $statusHtml = '<span class="wh-badge hampir"><i class="fa-solid fa-triangle-exclamation"></i>Hampir Expired</span>';
                } else {
                    $statusHtml = '<span class="wh-badge aman"><i class="fa-solid fa-circle-check"></i>Aman</span>';
                }
                $row[] = '<div class="text-center">' . $statusHtml . '</div>';
                
                // Donatur
                $row[] = esc($stok['donatur'] ?? '-');
                // Kategori
                $row[] = esc($stok['kategori']);
                
                // Kedaluwarsa
                $expiredHtml = '-';
                if ($stok['tanggal_kedaluwarsa']) {
                    $tglFormatted = date('d M Y', strtotime($stok['tanggal_kedaluwarsa']));
                    $diffDays = $days;
                    if ($diffDays < 0)       $expiredHtml = '<span class="wh-expired-over">Expired</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                    elseif ($diffDays === 0) $expiredHtml = '<span class="wh-expired-soon">Hari Ini</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                    elseif ($diffDays === 1) $expiredHtml = '<span class="wh-expired-soon">Besok</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                    elseif ($diffDays <= 7)  $expiredHtml = '<span class="wh-expired-soon">' . $diffDays . ' Hari Lagi</span><span class="wh-expired-date">' . $tglFormatted . '</span>';
                    else                     $expiredHtml = '<span class="wh-expired-ok">' . $tglFormatted . '</span>';
                }
                $row[] = '<div class="text-center">' . $expiredHtml . '</div>';
                
                // Nama Barang
                $namaBarang = '<strong>' . esc($stok['nama_barang']) . '</strong>';
                $row[] = $namaBarang;
                
                // Jumlah Stok
                $row[] = '<div class="text-center" style="font-weight:700;">' . number_format($stok['stok_saat_ini'], 0, ',', '.') . '</div>';
                
                // Satuan
                $row[] = '<div class="text-center">' . esc($stok['satuan']) . '</div>';
                
                // Berat / Satuan
                $bisaDipecah = (int) ($stok['bisa_dipecah'] ?? 0);
                $beratPerSatuan = (float) $stok['berat_per_satuan'];
                
                $row[] = '<div class="text-end">' . ($beratPerSatuan > 0 ? $beratPerSatuan . ' ' . esc($stok['satuan_berat']) : '-') . '</div>';
                
                // Total Berat
                helper('format');
                $totalBeratRow = 0;
                if ($bisaDipecah === 1) {
                    $totalBeratRow = (float) $stok['stok_saat_ini'];
                    if (in_array(strtolower(trim($stok['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                        $totalBeratRow *= 1000;
                    }
                } else {
                    $totalBeratRow = $stok['stok_saat_ini'] * $beratPerSatuan;
                }
                $row[] = '<div class="text-end" style="font-weight:500;">' . ($totalBeratRow > 0 ? format_berat($totalBeratRow, $stok['satuan_berat']) : '-') . '</div>';
                
                // Jumlah CTN
                $row[] = '<div class="text-center">' . (!empty($stok['jumlah_ctn']) ? esc($stok['jumlah_ctn']) : '-') . '</div>';
                
                // Catatan
                $row[] = '<div style="color:var(--wh-text-soft);"><small>' . esc($stok['catatan'] ?? '-') . '</small></div>';
                
                $data[] = $row;
            }
            
            $summary = $model->getSummaryData($postData);

            $output = [
                "draw" => $postData['draw'] ?? 0,
                "recordsTotal" => $model->countAllData(),
                "recordsFiltered" => $model->countFiltered($postData),
                "data" => $data,
                "summary" => $summary,
                "csrf_hash" => csrf_hash()
            ];
            
            return $this->response->setJSON($output);
        } catch (\Exception $e) {
            log_message('error', '[LaporanStokGudang::ajaxData] ' . $e->getMessage());
            return $this->response->setJSON([
                'draw' => $this->request->getPost('draw') ?? 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'summary' => ['total_berat' => 0],
                'csrf_hash' => csrf_hash(),
                'error' => 'Terjadi kesalahan saat memuat data.'
            ]);
        }
    }

    public function pdf()
    {
        $result = $this->_getFilteredData();
        $data = [
            'title' => 'Laporan Stok Gudang',
            'laporan' => $result['data'],
            'filters' => $result['filters']
        ];

        $html = view('App\Modules\Reports\Views\laporan_stok\pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Stok_Gudang_" . date('Ymd_His') . ".pdf", ["Attachment" => false]);
    }

    public function excel()
    {
        $result = $this->_getFilteredData();
        $stokGudang = $result['data'];
        
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
        
        $pageSetup->setRowsToRepeatAtTopByStartAndEnd(2, 2);
        
        $bulanIndo = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
        $tanggalCetak = date('d') . ' ' . $bulanIndo[date('m')] . ' ' . date('Y');
        $waktuCetak = date('H.i') . ' WIB';
        
        // Baris 1: STO
        $sheet->setCellValue('A1', 'STO ' . $tanggalCetak);
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Baris 2: Headers
        $headers = [
            'A2' => 'No',
            'B2' => 'Status',
            'C2' => 'Asal/ Lokasi Barang/ Donatur',
            'D2' => 'Kategori',
            'E2' => 'Kadaluarsa',
            'F2' => 'Nama Barang',
            'G2' => "Jumlah\n(pcs)",
            'H2' => 'SKU',
            'I2' => "Berat/Satuan\n(Sesuai Satuan)",
            'J2' => "Total Berat\n(Sesuai Satuan)",
            'K2' => "Jumlah\n(CTN)",
            'L2' => 'Catatan'
        ];
        
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                   ->setVertical(Alignment::VERTICAL_CENTER)
                                                   ->setWrapText(true);
        }
        
        $sheet->getStyle('A1:L2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $row = 3;
        $no = 1;
        
        foreach ($stokGudang as $item) {
            $bisaDipecah = (int) $item['bisa_dipecah'];
            $beratPerSatuan = (float) $item['berat_per_satuan'];
            if ($bisaDipecah === 1) {
                $totalBeratRow = (float) $item['stok_saat_ini'];
                if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalBeratRow *= 1000; // convert Kg back to gram if format_berat expects base units?
                }
            } else {
                $totalBeratRow = $item['stok_saat_ini'] * $beratPerSatuan;
            }

            $sheet->setCellValue('A' . $row, $no++);
            
            // Status: Emergency jika Hampir Expired atau Expired
            if ($item['status'] == 'Expired' || $item['status'] == 'Hampir Expired') {
                $sheet->setCellValue('B' . $row, 'Emergency');
                $sheet->getStyle('B' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                                  ->getStartColor()->setARGB('FFC00000'); // Merah
                $sheet->getStyle('B' . $row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            } else {
                $sheet->setCellValue('B' . $row, ''); // Kosong jika aman
            }
            
            $sheet->setCellValue('C' . $row, $item['donatur'] ?? '-');
            $sheet->setCellValue('D' . $row, $item['kategori']);
            $sheet->setCellValue('E' . $row, $item['tanggal_kedaluwarsa'] ? date('d-M-Y', strtotime($item['tanggal_kedaluwarsa'])) : '-');
            $sheet->setCellValue('F' . $row, $item['nama_barang']);
            $sheet->setCellValue('G' . $row, $item['stok_saat_ini']);
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('H' . $row, $item['satuan']);
            
            $sheet->setCellValue('I' . $row, $beratPerSatuan > 0 ? $beratPerSatuan : '-');
            $sheet->setCellValue('J' . $row, $totalBeratRow > 0 ? $totalBeratRow : '-');
            
            $sheet->setCellValue('K' . $row, $item['jumlah_ctn'] ?? '');
            $sheet->setCellValue('L' . $row, $item['catatan'] ?? '');

            // Alignment
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("A{$row}:L{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $row++;
        }

        // Lebar Kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(8);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(15);
        
        // Baris Total
        $sheet->mergeCells("A{$row}:H{$row}");
        $sheet->setCellValue("A{$row}", "Total");
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);
        $sheet->setCellValue("I{$row}", "Total Berat (Kg)");
        $sheet->setCellValue("J{$row}", $result['filters']['total_berat']);
        $sheet->getStyle("A{$row}:L{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        $row++;
        $sheet->mergeCells("A{$row}:L{$row}");
        $sheet->setCellValue("A{$row}", "Berikut adalah data stock opname barang donasi di gudang logistik Foodbank of Indonesia per tanggal {$tanggalCetak} pukul {$waktuCetak}.");
        $sheet->getStyle("A{$row}:L{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Tanda Tangan
        $row++;
        $sheet->setCellValue("C{$row}", "Menyusun,");
        $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells("C{$row}:D{$row}");
        
        $sheet->setCellValue("F{$row}", "Mengetahui,");
        $sheet->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells("F{$row}:H{$row}");
        
        $sheet->setCellValue("K{$row}", "Menyetujui,");
        $sheet->getStyle("K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells("K{$row}:L{$row}");
        
        $sheet->getStyle("A{$row}:L{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Blank space for signatures (merging rows below it to keep border clean)
        $row++;
        $sheet->mergeCells("C{$row}:D".($row+4));
        $sheet->mergeCells("F{$row}:H".($row+4));
        $sheet->mergeCells("K{$row}:L".($row+4));
        
        // Outline borders for the empty spaces
        $sheet->getStyle("A{$row}:L".($row+4))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("C".($row-1).":D".($row+4))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F".($row-1).":H".($row+4))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("K".($row-1).":L".($row+4))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Stok_Gudang_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}



