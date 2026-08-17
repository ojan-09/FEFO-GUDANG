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
use PhpOffice\PhpSpreadsheet\Style\Color;

class LaporanExpired extends BaseController
{
    private function _getFilteredData()
    {
        helper('format');
        $db = \Config\Database::connect();
        
        $builder = $db->table('batch');
        $builder->select('
            batch.id,
            batch.stok_saat_ini as jumlah,
            batch.jumlah_ctn,
            batch.tanggal_kedaluwarsa,
            batch.kategori as kategori_batch,
            barang_masuk.keterangan,
            COALESCE(batch.nama_barang, barang.nama_barang) as nama_barang,
            COALESCE(batch.satuan, barang.satuan) as satuan,
            COALESCE(batch.berat_per_satuan, barang.berat_per_satuan) as berat_per_satuan,
            COALESCE(batch.satuan_berat, barang.satuan_berat) as satuan_berat,
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            donatur.nama_donatur
        ');
        $builder->join('barang_masuk', 'barang_masuk.id = batch.id_barang_masuk', 'left');
        $builder->join('barang', 'barang.id = batch.id_barang');
        $builder->join('donatur', 'donatur.id = barang_masuk.id_donatur', 'left');
        
        // Hanya yang stoknya > 0
        $builder->where('batch.stok_saat_ini >', 0);
        
        // Filters
        $statusFilter = $this->request->getGet('status') ?: 'Semua';
        $donaturFilter = $this->request->getGet('donatur');
        $searchFilter = $this->request->getGet('search');
        $kategoriFilter = $this->request->getGet('kategori');
        
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

        $builder->orderBy('batch.tanggal_kedaluwarsa', 'ASC');
        $rawData = $builder->get()->getResultArray();

        $data = [];
        $totalBatch = 0;
        $totalBarang = 0;
        $totalBerat = 0;
        $totalExpired = 0;
        $totalHampirExpired = 0;

        $todayStr = date('Y-m-d');
        $todayTime = strtotime($todayStr);

        foreach ($rawData as $row) {
            $sisaHari = null;
            $statusStr = '';
            $statusLabel = '';
            
            if ($row['tanggal_kedaluwarsa']) {
                $expTime = strtotime($row['tanggal_kedaluwarsa']);
                $sisaHari = floor(($expTime - $todayTime) / (60 * 60 * 24));
                
                if ($sisaHari < 0) {
                    $statusStr = 'Expired';
                    $statusLabel = '🔴 Expired';
                } elseif ($sisaHari <= 30) {
                    $statusStr = 'Hampir Expired (<= 30 Hari)';
                    $statusLabel = '🟡 Hampir Expired';
                } elseif ($sisaHari <= 60) {
                    $statusStr = 'Akan Expired (<= 60 Hari)';
                    $statusLabel = '🟢 Aman';
                } elseif ($sisaHari <= 90) {
                    $statusStr = 'Akan Expired (<= 90 Hari)';
                    $statusLabel = '🟢 Aman';
                } else {
                    $statusStr = 'Aman';
                    $statusLabel = '🟢 Aman';
                }
            } else {
                $statusStr = 'Aman';
                $statusLabel = '🟢 Aman';
            }

            // Terapkan Filter Status PHP level (karena logic dinamis hari ini)
            $includeRow = false;
            if ($statusFilter === 'Semua') {
                $includeRow = true;
            } elseif ($statusFilter === 'Sudah Expired' && $sisaHari !== null && $sisaHari < 0) {
                $includeRow = true;
            } elseif ($statusFilter === 'Akan Expired (<= 30 Hari)' && $sisaHari !== null && $sisaHari >= 0 && $sisaHari <= 30) {
                $includeRow = true;
            } elseif ($statusFilter === 'Akan Expired (<= 60 Hari)' && $sisaHari !== null && $sisaHari >= 0 && $sisaHari <= 60) {
                $includeRow = true;
            } elseif ($statusFilter === 'Akan Expired (<= 90 Hari)' && $sisaHari !== null && $sisaHari >= 0 && $sisaHari <= 90) {
                $includeRow = true;
            }
            
            if ($includeRow) {
                $row['sisa_hari'] = $sisaHari;
                $row['status_label'] = $statusLabel;
                
                $data[] = $row;

                $totalBatch++;
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
                
                if ($sisaHari !== null && $sisaHari < 0) {
                    $totalExpired += $row['jumlah'];
                } elseif ($sisaHari !== null && $sisaHari >= 0 && $sisaHari <= 30) {
                    $totalHampirExpired += $row['jumlah'];
                }
            }
        }

        return [
            'data' => $data,
            'summary' => [
                'total_batch' => $totalBatch,
                'total_barang' => $totalBarang,
                'total_berat' => $totalBerat,
                'total_expired' => $totalExpired,
                'total_hampir_expired' => $totalHampirExpired
            ],
            'filters' => [
                'status' => $statusFilter,
                'donatur' => $donaturFilter,
                'search' => $searchFilter,
                'kategori' => $kategoriFilter
            ]
        ];
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $filters = [
            'status'   => $this->request->getGet('status') ?: 'Semua',
            'donatur'  => $this->request->getGet('donatur'),
            'search'   => $this->request->getGet('search'),
            'kategori' => $this->request->getGet('kategori')
        ];

        $kategoriList = $db->table('kategori')->select('nama_kategori')->orderBy('nama_kategori', 'ASC')->get()->getResultArray();

        $data = [
            'title'    => 'Laporan Barang Expired',
            'filters'  => $filters,
            'kategori' => $kategoriList
        ];
        
        return view('App\Modules\Reports\Views\laporan_expired\index', $data);
    }

    public function ajaxData()
    {
        try {
            $postData = $this->request->getPost();
            $model = new \App\Modules\Reports\Models\LaporanExpiredModel();

            $list = $model->getDatatables($postData);
            $data = [];
            $no = (int)($postData['start'] ?? 0);

            helper('format');
            $todayStr = date('Y-m-d');
            $todayTime = strtotime($todayStr);

            foreach ($list as $item) {
                $no++;
                $bisaDipecah = (int)($item['bisa_dipecah'] ?? 0);
                $beratPerSatuan = (float)$item['berat_per_satuan'];

                if ($bisaDipecah === 1) {
                    $weightInKg = (float)$item['jumlah'];
                    if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                        $weightInKg /= 1000;
                    }
                    $jumlahStokText = number_format($item['jumlah'], 2, ',', '.');
                    $satuanText     = 'Kg';
                    $beratBersihText = '-';
                } else {
                    $totalBeratRow = $item['jumlah'] * $beratPerSatuan;
                    $weightInKg = (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) ? ($totalBeratRow / 1000) : $totalBeratRow;
                    $jumlahStokText = number_format($item['jumlah'], 0, ',', '.');
                    $satuanText     = esc($item['satuan']);
                    $beratBersihText = $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-';
                }

                $sisaHari = null;
                $statusBadge = '<span class="badge bg-secondary">Tidak Diketahui</span>';

                if ($item['tanggal_kedaluwarsa']) {
                    $expTime = strtotime($item['tanggal_kedaluwarsa']);
                    $sisaHari = floor(($expTime - $todayTime) / (60 * 60 * 24));
                    
                    if ($sisaHari < 0) {
                        $statusBadge = '<span class="badge bg-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>Expired (' . abs($sisaHari) . ' hr lalu)</span>';
                    } elseif ($sisaHari <= 30) {
                        $statusBadge = '<span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i>Hampir Expired (' . $sisaHari . ' hr)</span>';
                    } else {
                        $statusBadge = '<span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Aman (' . $sisaHari . ' hr lagi)</span>';
                    }
                }

                $tglExp = $item['tanggal_kedaluwarsa'] ? date('d/m/Y', strtotime($item['tanggal_kedaluwarsa'])) : '-';
                $totalBeratText = $weightInKg > 0 ? format_berat($weightInKg, 'Kg') : '-';

                $data[] = [
                    'no'                  => $no,
                    'tanggal_kedaluwarsa' => $tglExp,
                    'status_badge'        => $statusBadge,
                    'nama_barang'         => '<strong>' . esc($item['nama_barang']) . '</strong>',
                    'kategori'            => '<span class="badge bg-light text-dark">' . esc($item['kategori_batch'] ?? '-') . '</span>',
                    'donatur'             => esc($item['nama_donatur'] ?? '-'),
                    'stok'                => $jumlahStokText,
                    'satuan'              => $satuanText,
                    'jumlah_ctn'          => number_format($item['jumlah_ctn'] ?? 0, 0, ',', '.'),
                    'berat_bersih'        => $beratBersihText,
                    'total_berat'         => $totalBeratText,
                    'keterangan'          => '<small>' . esc($item['keterangan'] ?? '-') . '</small>'
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
            log_message('error', 'LaporanExpired::ajaxData error: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw'            => intval($this->request->getPost('draw') ?? 0),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'summary'         => [
                    'total_batch'          => 0,
                    'total_barang'         => 0,
                    'total_berat'          => 0,
                    'total_expired'        => 0,
                    'total_hampir_expired' => 0
                ],
                'error'           => 'Terjadi kesalahan saat memuat data.',
                'csrf_hash'       => csrf_hash()
            ]);
        }
    }

    public function pdf()
    {
        $result = $this->_getFilteredData();
        $data = [
            'title' => 'LAPORAN BARANG EXPIRED',
            'laporan' => $result['data'],
            'summary' => $result['summary'],
            'filters' => $result['filters']
        ];

        $html = view('App\Modules\Reports\Views\laporan_expired\pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Laporan_Barang_Expired_" . date('Ymd_His') . ".pdf", ["Attachment" => false]);
    }

    public function excel()
    {
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
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'LAPORAN BARANG EXPIRED');
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Baris 3: Table Headers
        $headers = [
            'A3' => 'No',
            'B3' => 'Status',
            'C3' => 'Tanggal Kedaluwarsa',
            'D3' => 'Sisa Hari',
            'E3' => 'Donatur',
            'F3' => 'Nama Barang',
            'G3' => 'Kategori',
            'H3' => 'Jumlah',
            'I3' => 'Satuan',
            'J3' => 'CTN',
            'K3' => 'Berat Bersih',
            'L3' => 'Total Berat',
            'M3' => 'Keterangan'
        ];
        
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                   ->setVertical(Alignment::VERTICAL_CENTER)
                                                   ->setWrapText(true);
        }
        
        $sheet->getStyle('A3:M3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Freeze panes
        $sheet->freezePane('A4');
        
        // Auto filter
        $sheet->setAutoFilter('A3:M3');

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

            // Status label string clean for excel
            $statusText = str_replace(['🔴 ', '🟡 ', '🟢 '], '', $item['status_label']);

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $statusText);
            $sheet->setCellValue('C' . $row, $item['tanggal_kedaluwarsa'] ? date('d-M-Y', strtotime($item['tanggal_kedaluwarsa'])) : '-');
            $sheet->setCellValue('D' . $row, $item['sisa_hari'] !== null ? $item['sisa_hari'] . ' Hari' : '-');
            $sheet->setCellValue('E' . $row, $item['nama_donatur'] ?? '-');
            $sheet->setCellValue('F' . $row, $item['nama_barang']);
            $sheet->setCellValue('G' . $row, $item['kategori_batch']);
            $sheet->setCellValue('H' . $row, $item['jumlah']);
            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('I' . $row, $item['satuan']);
            $sheet->setCellValue('J' . $row, $item['jumlah_ctn'] ?? '-');
            $sheet->setCellValue('K' . $row, $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-');
            $sheet->setCellValue('L' . $row, $totalBeratRow > 0 ? format_berat($totalBeratRow, $item['satuan_berat']) : '-');
            $sheet->setCellValue('M' . $row, $item['keterangan'] ?? '-');

            // Alignment
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle("A{$row}:M{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            if (isset($item['sisa_hari']) && $item['sisa_hari'] !== null) {
                if ($item['sisa_hari'] < 0) {
                    $sheet->getStyle("A{$row}:M{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8D7DA');
                    $sheet->getStyle("B{$row}")->getFont()->setColor(new Color('FF842029'))->setBold(true);
                } elseif ($item['sisa_hari'] <= 30) {
                    $sheet->getStyle("A{$row}:M{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF3CD');
                    $sheet->getStyle("B{$row}")->getFont()->setColor(new Color('FF664D03'))->setBold(true);
                } else {
                    $sheet->getStyle("A{$row}:M{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD1E7DD');
                    $sheet->getStyle("B{$row}")->getFont()->setColor(new Color('FF0F5132'));
                }
            }

            $row++;
        }

        // Auto Width for all columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Blank row before summary
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Batch");
        $sheet->setCellValue("C{$row}", $summary['total_batch']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Barang");
        $sheet->setCellValue("C{$row}", $summary['total_barang']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Berat");
        $sheet->setCellValue("C{$row}", format_berat($summary['total_berat'], 'Kg'));
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;

        $sheet->setCellValue("B{$row}", "Total Barang Expired");
        $sheet->setCellValue("C{$row}", $summary['total_expired']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;

        $sheet->setCellValue("B{$row}", "Total Barang Hampir Expired");
        $sheet->setCellValue("C{$row}", $summary['total_hampir_expired']);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Barang_Expired_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
