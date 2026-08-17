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
            COALESCE(batch.bisa_dipecah, barang.bisa_dipecah) as bisa_dipecah,
            batch.nomor_batch,
            batch.nilai_satuan,
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
            $builder->groupStart()
                ->like('batch.nama_barang', $searchFilter)
                ->orLike('barang.nama_barang', $searchFilter)
                ->groupEnd();
        }

        $builder->orderBy('barang_keluar.tanggal_keluar', 'DESC');
        $builder->orderBy('barang_keluar.id', 'DESC');
        $data = $builder->get()->getResultArray();

        // Calculate Totals
        $totalPenyaluran = 0;
        $totalBarangUtuhPerSatuan = []; // ['Pcs' => 120, 'Dus' => 30, 'Botol' => 50, ...]
        $totalBarangRepack = 0;
        $totalBerat = 0;
        $totalNilaiDonasi = 0;
        $transaksiUnik = [];

        foreach ($data as $key => $row) {
            if (!in_array($row['nomor_transaksi'], $transaksiUnik)) {
                $transaksiUnik[] = $row['nomor_transaksi'];
                $totalPenyaluran++;
            }
            
            $bisaDipecah = (int)($row['bisa_dipecah'] ?? 0);
            if ($bisaDipecah === 1) {
                $totalBarangRepack += (float)$row['jumlah'];
                $totalBeratRow = (float)$row['jumlah'];
            } else {
                $satuan = $row['satuan'] ?: 'Pcs';
                $totalBarangUtuhPerSatuan[$satuan] = ($totalBarangUtuhPerSatuan[$satuan] ?? 0) + (float)$row['jumlah'];
                $beratPerSatuan = (float)$row['berat_per_satuan'];
                $totalBeratRow = $row['jumlah'] * $beratPerSatuan;
                if (in_array(strtolower(trim($row['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalBeratRow = $totalBeratRow / 1000;
                }
            }
            $totalBerat += $totalBeratRow;
            
            // Perhitungan nilai penyaluran
            $nilaiSatuan = (float)($row['nilai_satuan'] ?? 0);
            $totalNilaiBaris = (float)$row['jumlah'] * $nilaiSatuan;
            $data[$key]['total_nilai'] = $totalNilaiBaris; // save for view
            $totalNilaiDonasi += $totalNilaiBaris;
        }

        return [
            'data' => $data,
            'summary' => [
                'total_penyaluran' => $totalPenyaluran,
                'total_barang_utuh_per_satuan' => $totalBarangUtuhPerSatuan,
                'total_barang_repack' => $totalBarangRepack,
                'total_berat' => $totalBerat,
                'total_nilai_donasi' => $totalNilaiDonasi
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
        $filters = [
            'start_date'       => $this->request->getGet('start_date'),
            'end_date'         => $this->request->getGet('end_date'),
            'nomor_penyaluran' => $this->request->getGet('nomor_penyaluran'),
            'wilayah'          => $this->request->getGet('wilayah'),
            'program'          => $this->request->getGet('program'),
            'search'           => $this->request->getGet('search')
        ];

        $data = [
            'title'   => 'Laporan Penyaluran Barang',
            'filters' => $filters
        ];
        
        return view('App\Modules\Reports\Views\laporan_penyaluran\index', $data);
    }

    public function ajaxData()
    {
        try {
            $postData = $this->request->getPost();
            $model = new \App\Modules\Reports\Models\LaporanPenyaluranModel();

            $list = $model->getDatatables($postData);
            $data = [];
            $no = (int)($postData['start'] ?? 0);

            helper('format');
            foreach ($list as $item) {
                $no++;
                $bisaDipecah = (int)($item['bisa_dipecah'] ?? 0);
                if ($bisaDipecah === 1) {
                    $totalBeratRow = (float)$item['jumlah'];
                    if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                        $totalBeratRow /= 1000;
                    }
                } else {
                    $beratPerSatuan = (float)$item['berat_per_satuan'];
                    $totalBeratRow = $item['jumlah'] * $beratPerSatuan;
                    if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                        $totalBeratRow /= 1000;
                    }
                }

                $tglKeluar = $item['tanggal_keluar'] ? date('d/m/Y', strtotime($item['tanggal_keluar'])) : '-';
                $totalBeratText = $totalBeratRow > 0 ? format_berat($totalBeratRow, 'Kg') : '-';

                $data[] = [
                    'no'               => $no,
                    'tanggal_keluar'   => $tglKeluar,
                    'nomor_transaksi'  => esc($item['nomor_transaksi']),
                    'nama_wilayah'     => esc($item['nama_wilayah'] ?? '-'),
                    'program'          => esc($item['program'] ?? '-'),
                    'nama_barang'      => '<strong>' . esc($item['nama_barang']) . '</strong>',
                    'nomor_batch'      => '<span class="badge bg-light text-dark font-monospace">' . esc($item['nomor_batch']) . '</span>',
                    'jumlah'           => number_format($item['jumlah'], 0, ',', '.'),
                    'satuan'           => esc($item['satuan']),
                    'total_berat'      => $totalBeratText,
                    'keterangan'       => '<small>' . esc($item['keterangan'] ?? '-') . '</small>',
                    'petugas'          => esc($item['petugas'] ?? '-')
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
            log_message('error', 'LaporanPenyaluran::ajaxData error: ' . $e->getMessage());
            return $this->response->setJSON([
                'draw'            => intval($this->request->getPost('draw') ?? 0),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'summary'         => [
                    'total_penyaluran'           => 0,
                    'total_barang_utuh_per_satuan' => [],
                    'total_barang_repack'        => 0,
                    'total_berat'                => 0,
                    'total_nilai_donasi'         => 0
                ],
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
        $dompdf->stream("Laporan_Penyaluran_Barang_" . date('Ymd_His') . ".pdf", ["Attachment" => false]);
    }

    public function excel()
    {
        if (!in_groups(['Administrator', 'Petugas Gudang', 'Pimpinan'])) {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Administrator yang dapat mengunduh laporan ini.');
        }

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
        $sheet->mergeCells('A1:O1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('A2', 'LAPORAN PENYALURAN BARANG');
        $sheet->mergeCells('A2:O2');
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
            'G3' => 'Batch',
            'H3' => 'Jumlah',
            'I3' => 'Satuan',
            'J3' => 'Berat per Satuan',
            'K3' => 'Total Berat',
            'L3' => 'Keterangan',
            'M3' => 'Nilai Satuan',
            'N3' => 'Total Nilai',
            'O3' => 'Petugas'
        ];
        
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                                   ->setVertical(Alignment::VERTICAL_CENTER)
                                                   ->setWrapText(true);
        }
        
        $sheet->getStyle('A3:O3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Freeze panes
        $sheet->freezePane('A4');
        
        // Auto filter
        $sheet->setAutoFilter('A3:O3');

        $row = 4;
        $no = 1;
        
        foreach ($laporan as $item) {
            $bisaDipecah = (int)($item['bisa_dipecah'] ?? 0);
            $beratPerSatuan = (float) $item['berat_per_satuan'];
            if ($bisaDipecah === 1) {
                $totalKg = (float)$item['jumlah'];
                $satuanStok = 'Kg';
                $jumlahStok = $item['jumlah'];
            } else {
                $totalKg = $item['jumlah'] * $beratPerSatuan;
                if (in_array(strtolower(trim($item['satuan_berat'] ?? '')), ['gram', 'g', 'gr', 'ml'])) {
                    $totalKg = $totalKg / 1000;
                }
                $satuanStok = $item['satuan'];
                $jumlahStok = $item['jumlah'];
            }

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, date('d-M-Y', strtotime($item['tanggal_keluar'])));
            $sheet->setCellValue('C' . $row, $item['nomor_transaksi']);
            $sheet->setCellValue('D' . $row, $item['nama_wilayah'] ?? '-');
            $sheet->setCellValue('E' . $row, $item['program'] ?? '-');
            $sheet->setCellValue('F' . $row, $item['nama_barang']);
            $sheet->setCellValue('G' . $row, $item['nomor_batch']);
            $sheet->setCellValue('H' . $row, $jumlahStok);
            if ($bisaDipecah === 1) {
                $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            } else {
                $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0');
            }
            $sheet->setCellValue('I' . $row, $satuanStok);
            $sheet->setCellValue('J' . $row, $beratPerSatuan > 0 ? format_berat($beratPerSatuan, $item['satuan_berat']) : '-');
            $sheet->setCellValue('K' . $row, $totalKg > 0 ? format_berat($totalKg, 'Kg') : '-');
            $sheet->setCellValue('L' . $row, $item['keterangan'] ?? '-');
            
            $sheet->setCellValue('M' . $row, $item['nilai_satuan'] ?? 0);
            $sheet->getStyle('M' . $row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->setCellValue('N' . $row, $item['total_nilai'] ?? 0);
            $sheet->getStyle('N' . $row)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            
            $sheet->setCellValue('O' . $row, $item['petugas'] ?? '-');

            // Alignment
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("M{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("N{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("O{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("A{$row}:O{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            $row++;
        }

        // Auto Width for all columns
        foreach (range('A', 'O') as $col) {
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
        $parts = [];
        foreach ($summary['total_barang_utuh_per_satuan'] as $satuan => $jml) {
            $parts[] = number_format($jml, 0, ',', '.') . ' ' . $satuan;
        }
        if ($summary['total_barang_repack'] > 0) {
            $parts[] = number_format($summary['total_barang_repack'], 2, ',', '.') . ' Kg';
        }
        $totalBarangText = !empty($parts) ? implode(' & ', $parts) : '0';
        $sheet->setCellValue("C{$row}", $totalBarangText);
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Berat");
        $sheet->setCellValue("C{$row}", format_berat($summary['total_berat'], 'Kg'));
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("B{$row}", "Total Nilai Donasi Keluar");
        $sheet->setCellValue("C{$row}", $summary['total_nilai_donasi']);
        $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->getStyle("B{$row}:C{$row}")->getFont()->setBold(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Penyaluran_Barang_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}




