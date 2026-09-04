<?php

namespace App\Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use App\Services\ExpiredNotificationService;
use Config\Database;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MonitoringExpired extends BaseController
{
    protected $db;
    protected $service;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->service = new ExpiredNotificationService();
    }

    public function index()
    {
        if (!in_groups(['Administrator', 'Petugas Gudang'])) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        // Ambil filter options (Kategori, Gudang Internal dll)
        $kategori = $this->db->table('kategori')->get()->getResultArray();
        $donatur = $this->db->table('donatur')->get()->getResultArray();

        $data = [
            'title' => 'Monitoring Expired',
            'kategori' => $kategori,
            'donatur' => $donatur
        ];

        return view('App\Modules\Transactions\Views\monitoring_expired\index', $data);
    }

    public function ajaxData()
    {
        if (!in_groups(['Administrator', 'Petugas Gudang'])) {
            return $this->response->setJSON(['data' => []]);
        }

        $request = \Config\Services::request();
        
        $limit = $request->getPost('length') ?: 10;
        $offset = $request->getPost('start') ?: 0;
        $search = $request->getPost('search')['value'] ?? '';
        
        $priorityFilter = $request->getPost('priority'); // CRITICAL, HIGH, WARNING, INFO
        $kategoriFilter = $request->getPost('kategori');

        // Note: For DataTables server side, since we compute 'sisa_hari' dynamically, 
        // we can compute it using DATEDIFF in SQL to allow sorting and filtering.
        // Priority logic in SQL:
        // CRITICAL: DATEDIFF(tanggal_kedaluwarsa, CURDATE()) < -30
        // HIGH: DATEDIFF(tanggal_kedaluwarsa, CURDATE()) BETWEEN -30 AND -1
        // WARNING: DATEDIFF(tanggal_kedaluwarsa, CURDATE()) BETWEEN 0 AND 30
        // INFO: DATEDIFF(tanggal_kedaluwarsa, CURDATE()) BETWEEN 31 AND 90
        
        $builder = $this->db->table('batch b');
        $builder->select('b.id, b.nomor_batch, br.nama_barang, k.nama_kategori, b.stok_saat_ini, b.satuan, b.tanggal_kedaluwarsa, DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) as sisa_hari');
        $builder->join('barang br', 'br.id = b.id_barang', 'left');
        $builder->join('kategori k', 'k.id = br.id_kategori', 'left');
        $builder->where('b.stok_saat_ini >', 0);
        $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <=', 90); // Only INFO and above

        if (!empty($search)) {
            $builder->groupStart()
                ->like('b.nomor_batch', $search)
                ->orLike('br.nama_barang', $search)
                ->groupEnd();
        }

        if (!empty($kategoriFilter)) {
            $builder->where('k.id', $kategoriFilter);
        }

        if (!empty($priorityFilter)) {
            if ($priorityFilter == 'CRITICAL') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <', -30);
            } elseif ($priorityFilter == 'HIGH') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) >=', -30);
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <', 0);
            } elseif ($priorityFilter == 'WARNING') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) >=', 0);
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <=', 30);
            } elseif ($priorityFilter == 'INFO') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) >', 30);
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <=', 90);
            }
        }

        $totalRecords = $builder->countAllResults(false);
        
        // Sorting: default is sisa_hari ASC
        $builder->orderBy('sisa_hari', 'ASC');
        $builder->limit($limit, $offset);
        
        $data = $builder->get()->getResultArray();
        
        $userId = user()->id;
        $reads = $this->db->table('notification_reads')
                          ->where('user_id', $userId)
                          ->get()->getResultArray();
        $readMap = [];
        foreach ($reads as $r) {
            $readMap[$r['batch_id'] . '_' . $r['priority']] = true;
        }

        $formattedData = [];
        $no = $offset + 1;
        foreach ($data as $row) {
            $priority = $this->service->getPriority((int)$row['sisa_hari']);
            $isRead = isset($readMap[$row['id'] . '_' . $priority]);
            
            $badge = '';
            if ($priority == 'CRITICAL') $badge = '<span class="badge bg-dark">CRITICAL</span>';
            elseif ($priority == 'HIGH') $badge = '<span class="badge bg-danger">HIGH</span>';
            elseif ($priority == 'WARNING') $badge = '<span class="badge bg-warning text-dark">WARNING</span>';
            elseif ($priority == 'INFO') $badge = '<span class="badge bg-info text-dark">INFO</span>';

            $btnSeen = !$isRead ? '<button class="btn btn-sm btn-outline-success btn-mark-seen" data-id="'.$row['id'].'" data-priority="'.$priority.'" title="Tandai Sudah Dicek"><i class="fa-solid fa-check"></i></button>' : '';
            
            $btnAction = '<div class="btn-group">';
            $btnAction .= $btnSeen;
            $btnAction .= '<a href="'.site_url('transaksi/monitoring-expired/detail/'.$row['id']).'" class="btn btn-sm btn-outline-primary" title="Detail Batch"><i class="fa-solid fa-eye"></i></a>';
            if (in_groups('Administrator')) {
                $btnAction .= '<button class="btn btn-sm btn-outline-warning btn-use-batch" data-id="'.$row['id'].'" data-sisa="'.$row['sisa_hari'].'" data-priority="'.$priority.'" title="Gunakan Batch Ini"><i class="fa-solid fa-share-from-square"></i></button>';
            }
            $btnAction .= '</div>';

            $formattedData[] = [
                $no++,
                esc($row['nomor_batch']),
                esc($row['nama_barang']),
                esc($row['nama_kategori'] ?? '-'),
                esc($row['stok_saat_ini']) . ' ' . esc($row['satuan']),
                date('d M Y', strtotime($row['tanggal_kedaluwarsa'])),
                $row['sisa_hari'] . ' hari',
                $badge,
                $btnAction
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $formattedData
        ]);
    }

    public function detail($id)
    {
        if (!in_groups(['Administrator', 'Petugas Gudang'])) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        $batch = $this->db->table('batch b')
            ->select('b.*, br.nama_barang, bm.tanggal_masuk as bm_tgl, d.nama_donatur')
            ->join('barang br', 'br.id = b.id_barang', 'left')
            ->join('barang_masuk bm', 'bm.id = b.id_barang_masuk', 'left')
            ->join('donatur d', 'd.id = bm.id_donatur', 'left')
            ->where('b.id', $id)
            ->get()->getRowArray();

        if (!$batch) {
            return redirect()->to('/transaksi/monitoring-expired')->with('error', 'Batch tidak ditemukan.');
        }

        $riwayatKeluar = $this->db->table('detail_barang_keluar dbk')
            ->select('bk.tanggal_keluar, bk.tujuan_penyaluran as tujuan, dbk.jumlah_keluar')
            ->join('barang_keluar bk', 'bk.id = dbk.id_barang_keluar', 'left')
            ->where('dbk.id_batch', $id)
            ->orderBy('bk.tanggal_keluar', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title' => 'Detail Batch: ' . $batch['nomor_batch'],
            'batch' => $batch,
            'riwayatKeluar' => $riwayatKeluar
        ];

        return view('App\Modules\Transactions\Views\monitoring_expired\detail', $data);
    }

    private function getExportData()
    {
        $priorityFilter = $this->request->getGet('priority');
        $kategoriFilter = $this->request->getGet('kategori');

        $builder = $this->db->table('batch b');
        $builder->select('b.*, br.nama_barang, k.nama_kategori, DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) as sisa_hari');
        $builder->join('barang br', 'br.id = b.id_barang', 'left');
        $builder->join('kategori k', 'k.id = br.id_kategori', 'left');
        $builder->where('b.stok_saat_ini >', 0);
        $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <=', 90);

        if (!empty($kategoriFilter)) {
            $builder->where('k.id', $kategoriFilter);
        }

        if (!empty($priorityFilter)) {
            if ($priorityFilter == 'CRITICAL') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <', -30);
            } elseif ($priorityFilter == 'HIGH') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) >=', -30);
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <', 0);
            } elseif ($priorityFilter == 'WARNING') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) >=', 0);
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <=', 30);
            } elseif ($priorityFilter == 'INFO') {
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) >', 30);
                $builder->where('DATEDIFF(b.tanggal_kedaluwarsa, CURDATE()) <=', 90);
            }
        }

        $builder->orderBy('sisa_hari', 'ASC');
        return $builder->get()->getResultArray();
    }

    public function exportExcel()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');
        
        $data = $this->getExportData();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

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

        $sheet->setCellValue('A1', 'MONITORING BARANG EXPIRED');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nomor Batch');
        $sheet->setCellValue('C3', 'Nama Barang');
        $sheet->setCellValue('D3', 'Sisa Stok');
        $sheet->setCellValue('E3', 'Tgl Kedaluwarsa');
        $sheet->setCellValue('F3', 'Sisa Hari');
        $sheet->setCellValue('G3', 'Prioritas');

        $sheet->getStyle('A3:G3')->applyFromArray($headerStyle);

        $row = 4;
        $no = 1;
        foreach ($data as $d) {
            $priority = $this->service->getPriority((int)$d['sisa_hari']);
            
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $d['nomor_batch']);
            $sheet->setCellValue('C' . $row, $d['nama_barang'] . ' (' . $d['nama_kategori'] . ')');
            $sheet->setCellValue('D' . $row, $d['stok_saat_ini'] . ' ' . $d['satuan']);
            $sheet->setCellValue('E' . $row, date('d/m/Y', strtotime($d['tanggal_kedaluwarsa'])));
            $sheet->setCellValue('F' . $row, $d['sisa_hari'] . ' hari');
            $sheet->setCellValue('G' . $row, $priority);
            
            $row++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "Monitoring_Expired_" . date('Ymd_His') . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportPDF()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');
        
        $data = $this->getExportData();
        
        // Simple HTML string for PDF
        $html = '<h2 style="text-align:center;">Monitoring Barang Expired</h2>';
        $html .= '<p style="text-align:center;">Tanggal: ' . date('d M Y') . '</p>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%" style="font-family: sans-serif; font-size: 12px; border-collapse: collapse;">';
        $html .= '<thead style="background-color: #f2f2f2;"><tr>
            <th>No</th>
            <th>Batch</th>
            <th>Barang</th>
            <th>Stok</th>
            <th>Expired</th>
            <th>Sisa Hari</th>
            <th>Prioritas</th>
        </tr></thead><tbody>';
        
        $no = 1;
        foreach($data as $d) {
            $priority = $this->service->getPriority((int)$d['sisa_hari']);
            $html .= '<tr>
                <td>'.$no++.'</td>
                <td>'.esc($d['nomor_batch']).'</td>
                <td>'.esc($d['nama_barang']).'</td>
                <td>'.esc($d['stok_saat_ini']).' '.esc($d['satuan']).'</td>
                <td>'.date('d M Y', strtotime($d['tanggal_kedaluwarsa'])).'</td>
                <td>'.$d['sisa_hari'].' hari</td>
                <td>'.$priority.'</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Monitoring_Expired_" . date('Ymd_His') . ".pdf", ["Attachment" => false]);
    }
}
