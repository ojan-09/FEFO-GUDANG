<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use App\Libraries\BackupManager;
use App\Libraries\ActivityLogger;
use Config\Services;

class Backup extends BaseController
{
    protected BackupManager $backupMgr;

    public function __construct()
    {
        $this->backupMgr = new BackupManager();
    }

    public function index()
    {
        $db    = \Config\Database::connect();
        $cache = Services::cache();

        if (!$dbInfo = $cache->get('db_size_info')) {
            $dbName = $db->getDatabase();

            // FIX #1: Prepared statement — cegah SQL Injection
            $query = $db->query(
                "SELECT COUNT(*) AS table_count,
                        SUM(data_length + index_length) / 1024 / 1024 AS size_mb
                 FROM information_schema.TABLES
                 WHERE table_schema = ?",
                [$dbName]
            );
            $row    = $query->getRow();
            $dbInfo = [
                'name'    => $dbName,
                'tables'  => $row->table_count ?? 0,
                'size_mb' => round($row->size_mb ?? 0, 2),
            ];
            $cache->save('db_size_info', $dbInfo, 600);
        }

        $allFiles      = $this->backupMgr->getBackupFiles();
        $history       = [];
        $restorePoints = [];
        $folderSize    = 0;

        foreach ($allFiles as $f) {
            $folderSize += $f['size'];
            if ($f['is_restore_point']) {
                $restorePoints[] = $f;
            } else {
                $history[] = $f;
            }
        }

        return view('App\Modules\Settings\Views\backup\index', [
            'title'         => 'Backup & Restore Database',
            'dbInfo'        => $dbInfo,
            'history'       => $history,
            'restorePoints' => $restorePoints,
            'totalFiles'    => count($history),
            'folderSizeMb'  => round($folderSize / 1024 / 1024, 2),
            'lastBackup'    => count($history) > 0 ? date('d M Y - H:i', $history[0]['date']) : 'Belum Ada',
        ]);
    }

    public function doBackup()
    {
        $notes = $this->request->getPost('notes') ?? 'Manual Backup via Web';
        try {
            $filename = $this->backupMgr->generateBackup('backup', $notes);

            // FIX #11: Panggil cleanup — simpan maks 10 backup & 5 restore point
            $this->backupMgr->cleanupRotations('backup', 10);
            $this->backupMgr->cleanupRotations('restore_point', 5);

            // FIX #8: Hapus cache ukuran DB agar statistik akurat setelah backup
            Services::cache()->delete('db_size_info');

            ActivityLogger::log('Tambah', 'Backup Database', "Melakukan Backup Database\nNama File: {$filename}\nCatatan: {$notes}");

            return redirect()->to('pengaturan/backup')->with('success', 'Backup berhasil dibuat: ' . $filename);
        } catch (\Exception $e) {
            return redirect()->to('pengaturan/backup')->with('error', $e->getMessage());
        }
    }

    public function verify($filename)
    {
        // FIX #2: Sanitasi filename sebelum diteruskan ke library
        try {
            $filename = $this->backupMgr->sanitizeFilename($filename);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => false, 'message' => $e->getMessage()]);
        }

        $result = $this->backupMgr->verifyFile($filename);
        return $this->response->setJSON($result);
    }

    public function download($filename)
    {
        // FIX #2: Sanitasi — cegah path traversal
        try {
            $filename = $this->backupMgr->sanitizeFilename($filename);
        } catch (\Exception $e) {
            return redirect()->to('pengaturan/backup')->with('error', 'Nama file tidak valid: [' . htmlspecialchars($filename ?? '') . ']');
        }

        $filepath = WRITEPATH . 'backups/' . $filename;
        if (file_exists($filepath)) {
            return $this->response->download($filepath, null);
        }
        return redirect()->to('pengaturan/backup')->with('error', 'File tidak ditemukan.');
    }

    public function delete($filename)
    {
        // FIX #2: Sanitasi — cegah path traversal
        try {
            $filename = $this->backupMgr->sanitizeFilename($filename);
        } catch (\Exception $e) {
            return redirect()->to('pengaturan/backup')->with('error', 'Nama file tidak valid: [' . htmlspecialchars($filename ?? '') . ']');
        }

        $filepath = WRITEPATH . 'backups/' . $filename;
        if (file_exists($filepath)) {
            $size    = filesize($filepath);
            $sizeMb  = round($size / 1024 / 1024, 2) . ' MB';
            unlink($filepath);
            $shaFile = str_replace('.sql', '.sha256', $filepath);
            if (file_exists($shaFile)) unlink($shaFile);

            ActivityLogger::log('Hapus', 'Backup Database', "Menghapus Backup Database\nNama File: {$filename}\nUkuran: {$sizeMb}");

            return redirect()->to('pengaturan/backup')->with('success', 'File backup berhasil dihapus.');
        }
        return redirect()->to('pengaturan/backup')->with('error', 'File tidak ditemukan.');
    }

    public function restore()
    {
        $filename = $this->request->getPost('filename');
        $isUpload = false;

        $file = $this->request->getFile('backup_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($file->getExtension() !== 'sql' && $file->getClientExtension() !== 'sql') {
                return redirect()->to('pengaturan/backup')->with('error', 'Hanya file ber-ekstensi .sql yang diperbolehkan.');
            }

            $newName = time() . '_' . bin2hex(random_bytes(8)) . '.sql';
            $file->move(WRITEPATH . 'backups/', $newName);
            $filename = $newName;
            $isUpload = true;

            // Validasi konten diserahkan ke verifyFile() di dalam restore()
            // yang membaca hingga 100 baris — lebih robust dari cek baris pertama saja
        }

        // FIX #2: Sanitasi filename dari POST
        try {
            $filename = $this->backupMgr->sanitizeFilename($filename ?? '');
        } catch (\Exception $e) {
            if ($isUpload) @unlink(WRITEPATH . 'backups/' . ($filename ?? ''));
            return redirect()->to('pengaturan/backup')->with('error', 'Nama file tidak valid: [' . htmlspecialchars($filename ?? '') . ']');
        }

        if (!file_exists(WRITEPATH . 'backups/' . $filename)) {
            return redirect()->to('pengaturan/backup')->with('error', 'File backup tidak valid atau tidak ditemukan.');
        }

        // FIX #4: Hitung ukuran SEBELUM apapun dihapus
        $sizeMb = round(filesize(WRITEPATH . 'backups/' . $filename) / 1024 / 1024, 2) . ' MB';

        try {
            // FIX #9: Controller tidak lagi memanggil verifyFile() sendiri —
            // verifikasi dilakukan sekali di dalam backupMgr->restore()
            $rpName = $this->backupMgr->generateBackup(
                'restore_point',
                'Auto-backup sebelum restore dari: ' . ($isUpload ? $file->getClientName() : $filename)
            );

            $this->backupMgr->restore($filename);

            $logFilename = $filename;
            if ($isUpload) {
                @unlink(WRITEPATH . 'backups/' . $filename);
                $logFilename = $file->getClientName();
            }

            // FIX #8: Hapus cache setelah restore agar statistik akurat
            Services::cache()->delete('db_size_info');

            ActivityLogger::log('Edit', 'Restore Database', "Melakukan Restore Database\nFile Sumber: {$logFilename}\nUkuran: {$sizeMb}");

            return redirect()->to('pengaturan/backup')->with('success', 'Database berhasil di-restore! Restore point diamankan: ' . $rpName);
        } catch (\Exception $e) {
            if ($isUpload) @unlink(WRITEPATH . 'backups/' . $filename);
            return redirect()->to('pengaturan/backup')->with('error', 'Proses Restore Gagal: ' . $e->getMessage());
        }
    }
}