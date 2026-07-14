<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanPenyaluran extends BaseController
{
    private function _getFilteredData(){
        helper('format');
        $db = \Config\Database::connect();
        
        $builder = $db->table('detail_barang_keluar');
        $builder->select('
            detail_barang_keluar.id,
            detail_barang_keluar.jumlah_keluar as jumlah,
            barang_keluar.nomor_transaksi,
            barang_keluar.tanggal_keluar,
            barang_keluar.tujuan_penyaluran as program,
            barang_keluar.keterangan,
            wilayah.nama_wilayah,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            users.username as petugas
        ');
        $builder->join('barang_keluar', 'barang_keluar.id = detail_barang_keluar.id_barang_keluar');
        $builder->join('batch', 'batch.id = detail_barang_keluar.id_batch');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left');
        $builder->join('users', 'users.id = barang_keluar.id_user', 'left');
        
        // Filters
        $startDateFilter = $this->request->getGet('start_date');
        $endDateFilter = $this->request->getGet('end_date');
        $nomorFilter = $this->request->getGet('nomor_penyaluran');
        $wilayahFilter = $this->request->getGet('wilayah');
        $programFilter = $this->request->getGet('program');
        $searchFilter = $this->request->getGet('search'); // Nama Barang
        
        if (!empty($startDateFilter)) {
            $builder->where('barang_keluar.tanggal_keluar >=', $startDateFilter);
        }
        if (!empty($endDateFilter)) {
            $builder->where('barang_keluar.tanggal_keluar <=', $endDateFilter);
        }
        if (!empty($nomorFilter)) {
            $builder->like('barang_keluar.nomor_transaksi', $nomorFilter);
        }
        if (!empty($wilayahFilter)) {
            $builder->like('wilayah.nama_wilayah', $wilayahFilter);
        }
        if (!empty($programFilter)) {
            $builder->like('barang_keluar.tujuan_penyaluran', $programFilter);
        }
        if (!empty($searchFilter)) {
            $builder->like('barang.nama_barang', $searchFilter);
        }

        $builder->orderBy('barang_keluar.tanggal_keluar', 'DESC');
        $builder->orderBy('barang_keluar.id', 'DESC');
        $data = $builder->get()->getResultArray();

        // Calculate Totals
        $totalPenyaluran = 0;
        $totalBarang = 0;
        $totalBerat = 0;
        $transaksiUnik = [];

        foreach ($data as $row) {
            if (!in_array($row['nomor_transaksi'], $transaksiUnik)) {
                $transaksiUnik[] = $row['nomor_transaksi'];
                $totalPenyaluran++;
            }
            $totalBarang += $row['jumlah'];
            $beratPerSatuan = (float) $row['berat_per_satuan'];
            $totalBeratRow = $row['jumlah'] * $beratPerSatuan;
            $weightInKg = (strtolower($row['satuan_berat']) === 'gram') ? ($totalBeratRow / 1000) : $totalBeratRow;
            $totalBerat += $weightInKg;
        }

        return [
            'data' => $data,
            'summary' => [
                'total_penyaluran' => $totalPenyaluran,
                'total_barang' => $totalBarang,
                'total_berat' => $totalBerat
            ],
            'filters' => [
                'start_date' => $startDateFilter,
                'end_date' => $endDateFilter,
                'nomor_penyaluran' => $nomorFilter,
                'wilayah' => $wilayahFilter,
                'program' => $programFilter,
                'search' => $searchFilter
            ]
        ];
    }

    public function index()
    {
        $result = $this->_getFilteredData();
        
        $data = [
            'title'      => 'Laporan Penyaluran Barang',
            'laporan'    => $result['data'],
            'summary'    => $result['summary'],
            'filters'    => $result['filters']
        ];
        
        return view('App\Modules\Reports\Views\laporan_penyaluran\index', $data);
    }

    public function pdf()
    {
        $result = $this->_getFilteredData();
        $data = [
            'title' => 'LAPORAN PENYALURAN BARANG',
            'laporan' => $result['data'],
            'summary' => $result['summary'],
            'filters' => $result['filters']
        ];

        $html = view('App\Modules\Reports\Views\laporan_penyaluran\pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Penyaluran_Barang_" . date('Ymd_His') . ".pdf", ["Attachment" => true]);
    }

    public function excel()
    {
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
        
        $bulanIndo = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
        $tanggalCetak = date('d') . ' ' . $bulanIndo[date('m')] . ' ' . date('Y');
        
        // Baris 1: Header
        $sheet->setCellValue('A1', 'FOODBANK OF INDONESIA');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'LAPORAN PENYALURAN BARANG');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Baris 3: Table Headers
        $headers = [
            'A3' => 'No',
            'B3' => 'Tanggal Penyaluran',
            'C3' => 'Nomor Penyaluran',
            'D3' => 'Wilayah Tujuan',
            'E3' => 'Program Penyaluran',
            'F3' => 'Nama Barang',
            'G3' => 'Jumlah',
            'H3' => 'Satuan',
            'I3' => 'Berat per Satuan',
            'J3' => 'Total Berat',
            'K3' => 'Keterangan',
            'L3' => 'Petugas'
        ];
        
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                   ->setVertical(Alignment::VERTICAL_CENTER)
                                                   ->setWrapText(true);
        }
        
        $sheet->getStyle('A3:L3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Freeze panes
        $sheet->freezePane('A4');
        
        // Auto filter
        $sheet->setAutoFilter('A3:L3');

        $row = 4;
        $no = 1;
        
        foreach ($laporan as $item) {
            $beratPerSatuan = (float) $item['berat_per_satuan'];
            $totalBeratRow = $item['jumlah'] * $beratPerSatuan;

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d-M-Y', strtotime($item['tanggal_keluar'])));
            $sheet->setCellValue('C' . $row, $item['nomor_transaksi']);
            $sheet->setCellValue('D' . $row, $item['nama_wilayah'] ?? '-');
            $sheet->setCellValue('E' . $row, $item['program'] ?? '-');
            $sheet->setCellValue('F' . $row, $item['nama_barang']);
            $sheet->setCellValue('G' . $row, $item['jumlah']);
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('H' . $row, $item['satuan']);
            $sheet->setCellValue('I' . $row, $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-');
            $sheet->setCellValue('J' . $row, $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-');
            $sheet->setCellValue('K' . $row, $item['keterangan'] ?? '-');
            $sheet->setCellValue('L' . $row, $item['petugas'] ?? '-');

            // Alignment
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("A{$row}:L{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $row++;
        }

        // Auto Width for all columns
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Blank row before summary
        $row++;
        
        // Summary
        $sheet->setCellValue("B{$row}", "Total Penyaluran");
        $sheet->setCellValue("C{$row}", $summary['total_penyaluran']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Barang");
        $sheet->setCellValue("C{$row}", $summary['total_barang']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Berat");
        $sheet->setCellValue("C{$row}", format_berat($summary['total_berat'], 'Kg'));
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0.00');

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Penyaluran_Barang_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}




