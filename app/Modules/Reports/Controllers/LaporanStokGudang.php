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
            barang.bisa_dipecah,
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

        $builder->orderBy('batch.tanggal_kedaluwarsa', 'ASC');
        $stokGudang = $builder->get()->getResultArray();

        $filteredData = [];
        $totalBerat = 0;
        $today = new \DateTime(date('Y-m-d'));
        
        foreach ($stokGudang as &$stok) {
            $status = 'Aman';
            $stokTotal = (float) $stok['stok_saat_ini'];
            
            if ($stokTotal > 0) {
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
            
            if (!empty($statusFilter) && $status !== $statusFilter) {
                continue;
            }
            
            $filteredData[] = $stok;
            
            $bisaDipecah = (int) $stok['bisa_dipecah'];
            $beratPerSatuan = (float) $stok['berat_per_satuan'];
            
            if ($bisaDipecah === 1) {
                // For repackable items, stok_saat_ini is already in Kg
                $weightInKg = (float) $stok['stok_saat_ini'];
            } else {
                $totalBeratRow = $stok['stok_saat_ini'] * $beratPerSatuan;
                $weightInKg = (strtolower($stok['satuan_berat']) === 'gram') ? ($totalBeratRow / 1000) : $totalBeratRow;
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
        $result = $this->_getFilteredData();
        
        $kategoriList = $db->table('kategori')->select('nama_kategori')->orderBy('nama_kategori', 'ASC')->get()->getResultArray();

        $data = [
            'title'      => 'Laporan Stok Gudang',
            'laporan'    => $result['data'],
            'filters'    => $result['filters'],
            'kategori'   => $kategoriList
        ];
        
        return view('App\Modules\Reports\Views\laporan_stok\index', $data);
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
        $dompdf->stream("Laporan_Stok_Gudang_" . date('Ymd_His') . ".pdf", ["Attachment" => true]);
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
            'I2' => 'Gram',
            'J2' => 'Total Berat',
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
                if (strtolower($item['satuan_berat']) === 'gram') {
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
            
            $sheet->setCellValue('I' . $row, $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-');
            $sheet->setCellValue('J' . $row, $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-');
            
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
        $sheet->mergeCells("A{$row}:I{$row}");
        $sheet->setCellValue("A{$row}", "Total");
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$row}:J{$row}")->getFont()->setBold(true);
        $sheet->setCellValue("B{$row}", "Total Berat");
        $sheet->setCellValue("C{$row}", format_berat($result['filters']['total_berat'], 'Kg'));
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



