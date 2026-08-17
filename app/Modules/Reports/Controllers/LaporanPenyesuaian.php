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

        $data = [
            'title'      => 'Laporan Penyesuaian Stok',
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ];

        return view('App\Modules\Reports\Views\laporan_penyesuaian\index', $data);
    }
    
    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        try {
            $model = new \App\Modules\Reports\Models\LaporanPenyesuaianModel();
            
            $filters = [
                'start_date' => $this->request->getPost('start_date'),
                'end_date'   => $this->request->getPost('end_date'),
                'jenis'      => $this->request->getPost('jenis'),
                'search'     => $this->request->getPost('search'),
                'order'      => $this->request->getPost('order')
            ];

            $length = $this->request->getPost('length') ?: 10;
            $start  = $this->request->getPost('start') ?: 0;
            $draw   = $this->request->getPost('draw');

            $list = $model->getDatatables($filters, $length, $start);
            $recordsFiltered = $model->countFiltered($filters);
            $recordsTotal = $model->countAllData();

            $data = [];
            $no = $start;
            foreach ($list as $row_data) {
                $no++;
                $row = [];
                
                $sign = ($row_data['jenis_penyesuaian'] === 'Koreksi Positif') ? '' : '-';
                
                $row[] = '<div class="text-center">' . $no . '</div>';
                $row[] = '<div class="text-center">' . date('d M Y', strtotime($row_data['tanggal'])) . '</div>';
                
                $jenisHtml = '';
                if ($row_data['jenis_penyesuaian'] === 'Koreksi Positif') {
                    $jenisHtml = '<span class="wh-badge aman" style="color:var(--wh-success);background:var(--wh-success-soft)"><i class="fa-solid fa-arrow-trend-up"></i>Koreksi Positif</span>';
                } elseif ($row_data['jenis_penyesuaian'] === 'Koreksi Negatif') {
                    $jenisHtml = '<span class="wh-badge expired" style="color:var(--wh-danger);background:var(--wh-danger-soft)"><i class="fa-solid fa-arrow-trend-down"></i>Koreksi Negatif</span>';
                } else {
                    $jenisHtml = '<span class="wh-badge default">' . esc($row_data['jenis_penyesuaian']) . '</span>';
                }
                
                $row[] = '<strong>' . esc($row_data['nomor_penyesuaian']) . '</strong><br>' . $jenisHtml;
                $row[] = '<strong>' . esc($row_data['nama_barang']) . '</strong>';
                $row[] = '<div class="text-center">' . esc($row_data['nomor_batch']) . '</div>';
                
                $expiredHtml = '-';
                if (!empty($row_data['tanggal_kedaluwarsa'])) {
                    $expiredHtml = date('d M Y', strtotime($row_data['tanggal_kedaluwarsa']));
                }
                $row[] = '<div class="text-center">' . $expiredHtml . '</div>';
                
                $jumlahHtml = '<span class="text-' . ($row_data['jenis_penyesuaian'] === 'Koreksi Positif' ? 'success' : 'danger') . '" style="font-weight:600;">' . $sign . esc($row_data['jumlah']) . '</span>';
                $row[] = '<div class="text-center">' . $jumlahHtml . '</div>';
                $row[] = '<div class="text-center">' . esc($row_data['satuan']) . '</div>';
                
                $keteranganHtml = '';
                if (!empty($row_data['ket_umum'])) {
                    $keteranganHtml .= '<strong>' . esc($row_data['ket_umum']) . '</strong><br>';
                }
                if (!empty($row_data['keterangan'])) {
                    $keteranganHtml .= '<span style="color:var(--wh-text-soft);"><small>' . esc($row_data['keterangan']) . '</small></span>';
                }
                if (empty($keteranganHtml)) $keteranganHtml = '-';
                
                $row[] = $keteranganHtml;

                $data[] = $row;
            }

            return $this->response->setJSON([
                "draw"            => intval($draw),
                "recordsTotal"    => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data"            => $data,
                "summary"         => [],
                "csrf_hash"       => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'LaporanPenyesuaian ajaxData: ' . $e->getMessage());
            return $this->response->setJSON([
                "draw"            => intval($this->request->getPost('draw')),
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => [],
                "summary"         => [],
                "csrf_hash"       => csrf_hash(),
                "error"           => "Terjadi kesalahan sistem saat memuat data."
            ]);
        }
    }

    public function export_pdf()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $db = \Config\Database::connect();
        $builder = $db->table('detail_penyesuaian_stok dps');
        $builder->select('dps.*, ps.nomor_penyesuaian, ps.tanggal, ps.jenis_penyesuaian, ps.keterangan as ket_umum, b.nama_barang, b.satuan, COALESCE(batch.bisa_dipecah, b.bisa_dipecah) as bisa_dipecah, batch.nomor_batch, batch.tanggal_kedaluwarsa');
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
        $dompdf->stream("Laporan_Penyesuaian_Stok_" . date('Ymd', strtotime($start_date)) . "-" . date('Ymd', strtotime($end_date)) . ".pdf", ["Attachment" => false]);
    }

    public function export_excel()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $db = \Config\Database::connect();
        $builder = $db->table('detail_penyesuaian_stok dps');
        $builder->select('dps.*, ps.nomor_penyesuaian, ps.tanggal, ps.jenis_penyesuaian, ps.keterangan as ket_umum, b.nama_barang, b.satuan, COALESCE(batch.bisa_dipecah, b.bisa_dipecah) as bisa_dipecah, batch.nomor_batch, batch.tanggal_kedaluwarsa');
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
