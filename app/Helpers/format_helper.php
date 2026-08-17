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

        if (strtolower($satuan) === 'gram' || strtolower($satuan) === 'ml') {
            return (floor($nilai) == $nilai ? number_format($nilai, 0, ',', '.') : number_format($nilai, 2, ',', '.')) . ' ' . $satuanBerat;
        }

        // Jika sudah Kg atau satuan lainnya
        return (strpos(strval($nilai), '.') !== false ? rtrim(rtrim(number_format($nilai, 2, ',', '.'), '0'), ',') : number_format($nilai, 0, ',', '.')) . ' ' . $satuan;
    }
}

if (!function_exists('format_jumlah')) {
    /**
     * Format angka jumlah barang tanpa .00 jika bilangan bulat
     * (mencegah salah paham 4.00 dikira 400)
     */
    function format_jumlah($jumlah)
    {
        $nilai = (float) $jumlah;
        if (floor($nilai) == $nilai) {
            return number_format($nilai, 0, ',', '.');
        }
        return rtrim(rtrim(number_format($nilai, 2, ',', '.'), '0'), ',');
    }
}

if (!function_exists('clear_dashboard_cache')) {
    /**
     * Clear Dashboard Cache
     * 
     * Driver saat ini : File (writable/cache/)
     * Lock mechanism  : Check-then-save (3 detik lock), aman untuk single-server
     * 
     * Jika migrasi ke Redis di masa depan:
     * - Ganti lock dengan: $redis->rawCommand('SET', 'cache_clearing_lock', 1, 'EX', 3, 'NX')
     * - Operasi Redis SETNX bersifat 100% atomic (zero race condition)
     */
    function clear_dashboard_cache()
    {
        try {
            $cache = \Config\Services::cache();
            
            // Anti Thundering Herd: Lock flag 3 detik untuk mencegah penumpukan penghapusan cache
            if ($cache->get('cache_clearing_lock')) {
                return;
            }
            $cache->save('cache_clearing_lock', true, 3);

            // Hapus daftar cache spesifik yang relevan saja (tidak menghapus seluruh cache sistem)
            $keys = [
                'dashboard_total_jenis_barang',
                'dashboard_total_batch',
                'dashboard_total_berat',
                'dashboard_top_barang',
                'dashboard_kategori',
                'dashboard_grafik_12bln',
                'db_size_info',
            ];

            foreach ($keys as $key) {
                $cache->delete($key);
            }
        } catch (\Throwable $e) {
            // Abaikan jika terjadi kendala pada service cache
        } finally {
            // Lock SELALU dilepas setelah proses selesai (atau jika error mid-loop)
            try {
                if (isset($cache)) {
                    $cache->delete('cache_clearing_lock');
                }
            } catch (\Throwable $e) {
                // Abaikan
            }
        }
    }
}

