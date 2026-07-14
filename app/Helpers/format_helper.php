<?php

if (!function_exists('format_berat')) {
    /**
     * Format berat barang dari Gram ke Kg jika >= 1000, 
     * atau biarkan Kg jika aslinya Kg.
     *
     * @param float $berat
     * @param string $satuanBerat
     * @return string
     */
    function format_berat($berat, $satuanBerat)
    {
        $satuan = trim($satuanBerat);
        $nilai = (float) $berat;

        if (strtolower($satuan) === 'gram') {
            return (floor($nilai) == $nilai ? number_format($nilai, 0, ',', '.') : number_format($nilai, 2, ',', '.')) . ' Gram';
        }

        // Jika sudah Kg atau satuan lainnya
        return (strpos(strval($nilai), '.') !== false ? rtrim(rtrim(number_format($nilai, 2, ',', '.'), '0'), ',') : number_format($nilai, 0, ',', '.')) . ' ' . $satuan;
    }
}

