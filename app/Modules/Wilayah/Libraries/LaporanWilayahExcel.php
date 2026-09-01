<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LaporanWilayahExcel
{
    /**
     * Generate laporan Excel.
     *
     * Controller cukup memanggil:
     * (new LaporanWilayahExcel())->download($data, $jenis, $title, $filters);
     */
    public function download(array $dataLaporan, string $jenis, string $title, array $filters = []): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        [$headers, $lastColumn, $widths] = $this->getLayout($jenis);

        $headerRow = 5;
        $dataStartRow = 6;

        // -----------------------------
        // Logo
        // -----------------------------
        $logoPath = FCPATH . 'assets/img/LogoFOI.png';
        if (!is_file($logoPath)) {
            $logoPath = FCPATH . 'assets/img/LogoFOI.webp';
        }

        if (is_file($logoPath) && is_readable($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo FOI');
            $drawing->setDescription('Logo Foodbank of Indonesia');
            $drawing->setPath($logoPath);
            $drawing->setHeight(55);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(3);
            $drawing->setWorksheet($sheet);
        }

        // -----------------------------
        // Judul
        // -----------------------------
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', 'FOODBANK OF INDONESIA');
        $sheet->getStyle("A1:{$lastColumn}1")->getFont()
            ->setBold(true)->setSize(16);
        $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', $title);
        $sheet->getStyle("A2:{$lastColumn}2")->getFont()
            ->setBold(true)->setSize(12);
        $sheet->getStyle("A2:{$lastColumn}2")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(27);
        $sheet->getRowDimension(2)->setRowHeight(23);

        // -----------------------------
        // Filter
        // -----------------------------
        $provinsi = $filters['provinsi'] ?? '';
        $startDate = $filters['start_date'] ?? '';
        $endDate = $filters['end_date'] ?? '';

        $provinsiText = $provinsi !== '' ? $provinsi : 'Semua Provinsi';

        $periodeText = 'Semua Periode';
        if ($startDate && $endDate) {
            $periodeText = date('d/m/Y', strtotime($startDate))
                . ' s.d '
                . date('d/m/Y', strtotime($endDate));
        }

        $sheet->mergeCells("A3:B3");
        $sheet->setCellValue('A3', 'Filter Provinsi');
        $sheet->getStyle('A3:B3')->getFont()->setBold(true);

        $sheet->mergeCells("C3:F3");
        $sheet->setCellValue('C3', ': ' . $provinsiText);

        $sheet->mergeCells("G3:H3");
        $sheet->setCellValue('G3', 'Periode');
        $sheet->getStyle('G3:H3')->getFont()->setBold(true);

        $sheet->mergeCells("I3:{$lastColumn}3");
        $sheet->setCellValue('I3', ': ' . $periodeText);

        $sheet->getStyle("A3:{$lastColumn}3")->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Garis pemisah
        $sheet->mergeCells("A4:{$lastColumn}4");
        $sheet->getStyle("A4:{$lastColumn}4")->getBorders()
            ->getBottom()
            ->setBorderStyle(Border::BORDER_MEDIUM)
            ->setColor(new Color('FF000000'));

        // -----------------------------
        // Header tabel
        // -----------------------------
        foreach ($headers as $index => $header) {
            $column = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue($column . $headerRow, $header);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFDCE6F1'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
            ->applyFromArray($headerStyle);
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
            ->applyFromArray($this->borderStyle());

        $sheet->getRowDimension($headerRow)->setRowHeight(34);

        // -----------------------------
        // Data
        // -----------------------------
        $row = $dataStartRow;
        $no = 1;

        $totalJumlah = 0;
        $totalBerat = 0;
        $totalNilai = 0;

        foreach ($dataLaporan as $item) {
            $sheet->setCellValue("A{$row}", $no++);

            if ($jenis === 'stok') {
                $sheet->setCellValue("B{$row}", $item['nama_gudang'] ?? '-');
                $sheet->setCellValue("C{$row}", ($item['kode_barang'] ?? '-') . ' - ' . ($item['nama_barang'] ?? '-'));
                $sheet->setCellValue("D{$row}", $item['kategori'] ?? '-');
                $sheet->setCellValue("E{$row}", (float)($item['jumlah'] ?? 0));
                $sheet->setCellValue("F{$row}", $item['satuan'] ?? '-');
                $sheet->setCellValue("G{$row}", (float)($item['berat'] ?? 0));
                $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('#,##0.00" kg"');

            } elseif ($jenis === 'masuk') {
                $berat = (float)($item['berat_per_satuan'] ?? 0);
                $satuanBerat = $item['satuan_berat'] ?? 'Kg';
                $beratKg = strtolower($satuanBerat) === 'gram' ? $berat / 1000 : $berat;
                $jumlah = (float)($item['jumlah'] ?? 0);
                $totalBeratRow = $jumlah * $beratKg;
                $harga = (float)($item['harga_satuan'] ?? 0);
                $nilai = (float)($item['subtotal_nilai'] ?? ($jumlah * $harga));

                $totalJumlah += $jumlah;
                $totalBerat += $totalBeratRow;
                $totalNilai += $nilai;

                $sheet->setCellValue("B{$row}", $this->formatDate($item['tanggal'] ?? null));
                $sheet->setCellValue("C{$row}", $item['nomor_dokumen'] ?? '-');
                $sheet->setCellValue("D{$row}", $item['nama_gudang'] ?? '-');
                $sheet->setCellValue("E{$row}", $item['donatur'] ?? '-');
                $sheet->setCellValue("F{$row}", ($item['kode_barang'] ?? '-') . ' - ' . ($item['nama_barang'] ?? '-'));
                $sheet->setCellValue("G{$row}", $item['kategori'] ?? '-');
                $sheet->setCellValue("H{$row}", $jumlah);
                $sheet->setCellValue("I{$row}", $item['satuan'] ?? '-');

                if ($berat > 0) {
                    $sheet->setCellValue("J{$row}", $berat);
                    $sheet->getStyle("J{$row}")->getNumberFormat()
                        ->setFormatCode('#,##0.00" ' . $satuanBerat . '"');
                } else {
                    $sheet->setCellValue("J{$row}", '-');
                }

                $sheet->setCellValue("K{$row}", $totalBeratRow);
                $sheet->getStyle("K{$row}")->getNumberFormat()
                    ->setFormatCode('#,##0.00" Kg"');

                $sheet->setCellValue("L{$row}", $harga);
                $sheet->getStyle("L{$row}")->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');

                $sheet->setCellValue("M{$row}", $nilai);
                $sheet->getStyle("M{$row}")->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');

                $sheet->setCellValue("N{$row}", $item['nama_user'] ?? '-');

            } else {
                $berat = (float)($item['berat_referensi'] ?? 0);
                $satuanBerat = $item['satuan_berat'] ?? 'Kg';
                $beratKg = strtolower($satuanBerat) === 'gram' ? $berat / 1000 : $berat;
                $jumlah = (float)($item['jumlah'] ?? 0);
                $totalBeratRow = $jumlah * $beratKg;

                $totalJumlah += $jumlah;
                $totalBerat += $totalBeratRow;

                $sheet->setCellValue("B{$row}", $this->formatDate($item['tanggal'] ?? null));
                $sheet->setCellValue("C{$row}", $item['nomor_dokumen'] ?? '-');
                $sheet->setCellValue("D{$row}", $item['nama_gudang'] ?? '-');
                $sheet->setCellValue("E{$row}", $item['tujuan'] ?? '-');
                $sheet->setCellValue("F{$row}", ($item['kode_barang'] ?? '-') . ' - ' . ($item['nama_barang'] ?? '-'));
                $sheet->setCellValue("G{$row}", $item['kategori'] ?? '-');
                $sheet->setCellValue("H{$row}", $jumlah);
                $sheet->setCellValue("I{$row}", $item['satuan'] ?? '-');

                if ($berat > 0) {
                    $sheet->setCellValue("J{$row}", $berat);
                    $sheet->getStyle("J{$row}")->getNumberFormat()
                        ->setFormatCode('#,##0.00" ' . $satuanBerat . '"');
                } else {
                    $sheet->setCellValue("J{$row}", '-');
                }

                $sheet->setCellValue("K{$row}", $totalBeratRow);
                $sheet->getStyle("K{$row}")->getNumberFormat()
                    ->setFormatCode('#,##0.00" Kg"');

                $sheet->setCellValue("L{$row}", $item['nama_user'] ?? '-');
            }

            $row++;
        }

        $lastDataRow = $row - 1;

        if (!empty($dataLaporan)) {
            $sheet->getStyle("A{$dataStartRow}:{$lastColumn}{$lastDataRow}")
                ->applyFromArray($this->borderStyle());

            $sheet->getStyle("A{$dataStartRow}:{$lastColumn}{$lastDataRow}")
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);

            $sheet->getStyle("A{$dataStartRow}:A{$lastDataRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // -----------------------------
        // Grand Total
        // -----------------------------
        $printLastRow = max($headerRow, $lastDataRow);

        if (($jenis === 'masuk' || $jenis === 'keluar') && !empty($dataLaporan)) {
            $totalRow = $row;

            $sheet->mergeCells("A{$totalRow}:G{$totalRow}");
            $sheet->setCellValue(
                "A{$totalRow}",
                $jenis === 'masuk'
                    ? 'TOTAL REKAPITULASI BARANG MASUK'
                    : 'TOTAL REKAPITULASI BARANG KELUAR'
            );

            $sheet->setCellValue("H{$totalRow}", $totalJumlah);
            $sheet->setCellValue("K{$totalRow}", $totalBerat);

            if ($jenis === 'masuk') {
                $sheet->setCellValue("M{$totalRow}", $totalNilai);
                $sheet->getStyle("M{$totalRow}")->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');
            }

            $sheet->getStyle("A{$totalRow}:{$lastColumn}{$totalRow}")
                ->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF1F5F9'],
                    ],
                ])
                ->applyFromArray($this->borderStyle());

            $sheet->getStyle("A{$totalRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle("H{$totalRow}")
                ->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("K{$totalRow}")
                ->getNumberFormat()->setFormatCode('#,##0.00" Kg"');

            $printLastRow = $totalRow;
        }

        // -----------------------------
        // Ukuran kolom
        // -----------------------------
        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        // -----------------------------
        // Freeze / filter
        // -----------------------------
        $sheet->freezePane("A{$dataStartRow}");
        $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$headerRow}");
        $sheet->setShowGridlines(false);

        // -----------------------------
        // Print setup
        // -----------------------------
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $sheet->getPageMargins()
            ->setTop(0.4)
            ->setRight(0.3)
            ->setBottom(0.4)
            ->setLeft(0.3);

        $sheet->getPageSetup()->setPrintArea("A1:{$lastColumn}{$printLastRow}");
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($headerRow, $headerRow);

        // -----------------------------
        // Download
        // -----------------------------
        $filename = "Laporan_Wilayah_{$jenis}_" . date('Ymd_His') . ".xlsx";

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    private function getLayout(string $jenis): array
    {
        if ($jenis === 'stok') {
            return [
                ['No', 'Gudang', 'Barang', 'Kategori', 'Jumlah', 'Satuan', 'Berat'],
                'G',
                [
                    'A'=>6, 'B'=>22, 'C'=>34, 'D'=>20,
                    'E'=>14, 'F'=>12, 'G'=>18
                ]
            ];
        }

        if ($jenis === 'masuk') {
            return [
                [
                    'No', 'Tanggal', 'Kode Transaksi', 'Gudang', 'Donatur',
                    'Barang', 'Kategori', 'Jumlah Masuk', 'Satuan',
                    'Berat/Satuan', 'Total Berat (Kg)', 'Harga Satuan (Rp)',
                    'Total Nilai (Rp)', 'Operator'
                ],
                'N',
                [
                    'A'=>6, 'B'=>13, 'C'=>24, 'D'=>20, 'E'=>22,
                    'F'=>32, 'G'=>18, 'H'=>14, 'I'=>12, 'J'=>18,
                    'K'=>18, 'L'=>21, 'M'=>22, 'N'=>16
                ]
            ];
        }

        return [
            [
                'No', 'Tanggal', 'Kode Transaksi', 'Gudang', 'Tujuan',
                'Barang', 'Kategori', 'Jumlah Keluar', 'Satuan',
                'Berat Referensi', 'Total Berat (Kg)', 'Operator'
            ],
            'L',
            [
                'A'=>6, 'B'=>13, 'C'=>24, 'D'=>20, 'E'=>24,
                'F'=>32, 'G'=>18, 'H'=>15, 'I'=>12, 'J'=>18,
                'K'=>18, 'L'=>16
            ]
        ];
    }

    private function borderStyle(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
    }

    private function formatDate($date): string
    {
        if (empty($date)) {
            return '-';
        }

        $timestamp = strtotime($date);
        return $timestamp ? date('d/m/Y', $timestamp) : '-';
    }
}
