<?php

namespace App\Modules\Reports\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class LaporanPenyesuaian extends BaseController
{
    public function index()
    {
        helper('format');
        $start_date = $this->request->getGet('start_date') ?: date('Y-m-01');
        $end_date = $this->request->getGet('end_date') ?: date('Y-m-t');

        $db = \Config\Database::connect();
        $builder = $db->table('detail_penyesuaian_stok dps');
        $builder->select('dps.*, ps.nomor_penyesuaian, ps.tanggal, ps.jenis_penyesuaian, ps.keterangan as ket_umum, b.nama_barang, b.satuan, b.bisa_dipecah, batch.nomor_batch, batch.tanggal_kedaluwarsa');
        $builder->join('penyesuaian_stok ps', 'ps.id = dps.id_penyesuaian');
        $builder->join('barang b', 'b.id = dps.id_barang');
        $builder->join('batch', 'batch.id = dps.id_batch');
        
        if ($start_date && $end_date) {
            $builder->where('ps.tanggal >=', $start_date);
            $builder->where('ps.tanggal <=', $end_date);
        }
        
        $builder->orderBy('ps.tanggal', 'DESC');
        $laporan = $builder->get()->getResultArray();

        $data = [
            'title'      => 'Laporan Penyesuaian Stok',
            'laporan'    => $laporan,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ];

        return view('App\Modules\Reports\Views\laporan_penyesuaian\index', $data);
    }

    public function export_pdf()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $db = \Config\Database::connect();
        $builder = $db->table('detail_penyesuaian_stok dps');
        $builder->select('dps.*, ps.nomor_penyesuaian, ps.tanggal, ps.jenis_penyesuaian, ps.keterangan as ket_umum, b.nama_barang, b.satuan, b.bisa_dipecah, batch.nomor_batch, batch.tanggal_kedaluwarsa');
        $builder->join('penyesuaian_stok ps', 'ps.id = dps.id_penyesuaian');
        $builder->join('barang b', 'b.id = dps.id_barang');
        $builder->join('batch', 'batch.id = dps.id_batch');
        
        if ($start_date && $end_date) {
            $builder->where('ps.tanggal >=', $start_date);
            $builder->where('ps.tanggal <=', $end_date);
        }
        
        $builder->orderBy('ps.tanggal', 'DESC');
        $laporan = $builder->get()->getResultArray();

        $data = [
            'title'      => 'Laporan Penyesuaian Stok',
            'laporan'    => $laporan,
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ];

        $html = view('App\Modules\Reports\Views\laporan_penyesuaian\pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Penyesuaian_Stok_" . date('Ymd', strtotime($start_date)) . "-" . date('Ymd', strtotime($end_date)) . ".pdf", ["Attachment" => true]);
    }

    public function export_excel()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $db = \Config\Database::connect();
        $builder = $db->table('detail_penyesuaian_stok dps');
        $builder->select('dps.*, ps.nomor_penyesuaian, ps.tanggal, ps.jenis_penyesuaian, ps.keterangan as ket_umum, b.nama_barang, b.satuan, b.bisa_dipecah, batch.nomor_batch, batch.tanggal_kedaluwarsa');
        $builder->join('penyesuaian_stok ps', 'ps.id = dps.id_penyesuaian');
        $builder->join('barang b', 'b.id = dps.id_barang');
        $builder->join('batch', 'batch.id = dps.id_batch');
        
        if ($start_date && $end_date) {
            $builder->where('ps.tanggal >=', $start_date);
            $builder->where('ps.tanggal <=', $end_date);
        }
        
        $builder->orderBy('ps.tanggal', 'DESC');
        $laporan = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Style
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE2EFDA']
            ]
        ];

        // Title
        $sheet->setCellValue('A1', 'LAPORAN PENYESUAIAN STOK GUDANG');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'Periode: ' . date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date)));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header Rows
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Tanggal');
        $sheet->setCellValue('C4', 'Nomor Transaksi');
        $sheet->setCellValue('D4', 'Jenis Penyesuaian');
        $sheet->setCellValue('E4', 'Barang');
        $sheet->setCellValue('F4', 'Batch');
        $sheet->setCellValue('G4', 'Tgl Expired');
        $sheet->setCellValue('H4', 'Jumlah');
        $sheet->setCellValue('I4', 'Satuan');
        $sheet->setCellValue('J4', 'Keterangan');

        $sheet->getStyle('A4:J4')->applyFromArray($headerStyle);

        // Body
        $row = 5;
        $no = 1;
        foreach ($laporan as $data) {
            $sign = ($data['jenis_penyesuaian'] === 'Koreksi Positif') ? '' : '-';
            
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($data['tanggal'])));
            $sheet->setCellValue('C' . $row, $data['nomor_penyesuaian']);
            $sheet->setCellValue('D' . $row, $data['jenis_penyesuaian']);
            $sheet->setCellValue('E' . $row, $data['nama_barang']);
            $sheet->setCellValue('F' . $row, $data['nomor_batch']);
            $sheet->setCellValue('G' . $row, $data['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($data['tanggal_kedaluwarsa'])) : '-');
            $sheet->setCellValue('H' . $row, $sign . $data['jumlah']);
            $sheet->setCellValue('I' . $row, $data['satuan']);
            $sheet->setCellValue('J' . $row, $data['ket_umum'] . ' - ' . $data['keterangan']);
            
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $writer = new Xlsx($spreadsheet);
        $filename = "Laporan_Penyesuaian_Stok_" . date('Ymd', strtotime($start_date)) . "-" . date('Ymd', strtotime($end_date)) . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
