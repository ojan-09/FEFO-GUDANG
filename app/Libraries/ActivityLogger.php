<?php

namespace App\Libraries;

use App\Models\ActivityLogModel;
use CodeIgniter\HTTP\IncomingRequest;

class ActivityLogger
{
    /**
     * Mencatat log aktivitas ke database
     *
     * @param string $aktivitas (Contoh: "Tambah Donasi", "Login")
     * @param string $modul (Contoh: "Donasi Masuk", "Autentikasi")
     * @param string|null $deskripsi (Penjelasan detail)
     * @param int|null $userId (Opsional, jika null akan mencoba ambil dari session login saat ini)
     */
    public static function log(string $aktivitas, string $modul, ?string $deskripsi = null, ?int $userId = null)
    {
        // Jika userId tidak diberikan, ambil dari user yang sedang login
        if ($userId === null) {
            // Kita coba load helper auth jika belum ada
            if (!function_exists('user_id')) {
                helper('auth');
            }
            if (function_exists('user_id')) {
                $userId = user_id();
            }
        }

        $model = new ActivityLogModel();
        
        $model->insert([
            'id_user'    => $userId,
            'modul'      => $modul,
            'aktivitas'  => $aktivitas,
            'deskripsi'  => $deskripsi,
        ]);
    }
}

