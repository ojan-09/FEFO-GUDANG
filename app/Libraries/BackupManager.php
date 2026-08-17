<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

class BackupManager
{
    protected BaseConnection $db;
    protected string $backupPath;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->backupPath = WRITEPATH . 'backups/';
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // FIX #2: Sanitasi dan validasi filename — cegah path traversal
    // ─────────────────────────────────────────────────────────────
    public function sanitizeFilename(string $filename): string
    {
        $basename = basename($filename);
        if (!preg_match('/^[a-zA-Z0-9_\-]+\.sql$/', $basename)) {
            throw new \Exception('Nama file tidak valid.');
        }
        return $basename;
    }

    /**
     * @return string filename
     */
    public function generateBackup(string $prefix = 'backup', string $notes = ''): string
    {
        $notes    = mb_substr($notes, 0, 150);
        $dateStr  = date('Ymd_His');
        $filename = "{$prefix}_{$dateStr}.sql";
        $filepath = $this->backupPath . $filename;

        // FIX #5: Tulis ke file .tmp dulu, rename setelah selesai (atomic write)
        $tmpPath = $filepath . '.tmp';
        $handle  = fopen($tmpPath, 'w');
        if (!$handle) {
            throw new \Exception("Tidak dapat membuat file backup di: {$tmpPath}");
        }

        // 1. Metadata Header
        $version   = env('app.version', '1.0.0');
        $dbName    = $this->db->getDatabase();
        $timestamp = date('Y-m-d H:i:s');

        fwrite($handle, "-- Application : FEFO Gudang\n");
        fwrite($handle, "-- Version : {$version}\n");
        fwrite($handle, "-- Database : {$dbName}\n");
        fwrite($handle, "-- Date : {$timestamp}\n");
        if ($notes) {
            fwrite($handle, "-- Note : {$notes}\n");
        }
        fwrite($handle, "\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n\n");

        // 2. Semua tabel
        $tables = $this->db->listTables();

        foreach ($tables as $table) {
            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Table structure for `{$table}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");

            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n\n");

            $query = $this->db->query("SHOW CREATE TABLE `{$table}`");
            $row   = $query->getRowArray();
            if (isset($row['Create Table'])) {
                fwrite($handle, $row['Create Table'] . ";\n\n");
            } elseif (isset($row['Create View'])) {
                fwrite($handle, $row['Create View'] . ";\n\n");
                continue; // view tidak punya data
            }

            fwrite($handle, "-- Dumping data for table `{$table}`\n\n");

            $result = $this->db->table($table)->get();

            while ($row = $result->getUnbufferedRow('array')) {
                $cols = array_keys($row);
                $vals = array_values($row);

                $escapedVals = array_map(function ($val) {
                    return $val === null ? 'NULL' : $this->db->escape($val);
                }, $vals);

                $colStr = implode("`, `", $cols);
                $valStr = implode(", ", $escapedVals);
                fwrite($handle, "INSERT INTO `{$table}` (`{$colStr}`) VALUES ({$valStr});\n");
            }

            // FIX #6: Bebaskan cursor setelah streaming
            $result->freeResult();

            fwrite($handle, "\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
        fclose($handle);

        // FIX #5: Sekarang baru rename (atomic)
        if (!rename($tmpPath, $filepath)) {
            @unlink($tmpPath);
            throw new \Exception("Gagal memindahkan file backup ke lokasi akhir.");
        }

        // 3. Checksum
        $sha256 = hash_file('sha256', $filepath);
        file_put_contents($this->backupPath . str_replace('.sql', '.sha256', $filename), $sha256);

        // 4. Kompresi ZIP jika extension tersedia
        $this->createZip($filepath);

        return $filename;
    }

    public function createZip(string $filepath): ?string
    {
        if (!extension_loaded('zip')) return null;

        $zipPath = str_replace('.sql', '.zip', $filepath);
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $zip->addFile($filepath, basename($filepath));
            $shaFile = str_replace('.sql', '.sha256', $filepath);
            if (file_exists($shaFile)) {
                $zip->addFile($shaFile, basename($shaFile));
            }
            $zip->close();
            return basename($zipPath);
        }
        return null;
    }

    public function getBackupFiles(): array
    {
        $files = [];
        foreach (glob($this->backupPath . '*.sql') as $file) {
            $filename        = basename($file);
            $meta            = $this->parseMetadata($file);
            $isRestorePoint  = strpos($filename, 'restore_point') === 0;

            $files[] = [
                'filename'         => $filename,
                'path'             => $file,
                'size'             => filesize($file),
                'date'             => filemtime($file),
                'is_restore_point' => $isRestorePoint,
                'metadata'         => $meta,
            ];
        }

        usort($files, fn($a, $b) => $b['date'] <=> $a['date']);
        return $files;
    }

    public function sendBackupEmail(string $filepath, string $targetEmail): bool
    {
        if (!file_exists($filepath)) return false;

        $zipPath = str_replace('.sql', '.zip', $filepath);
        $attachment = file_exists($zipPath) ? $zipPath : $filepath;

        try {
            $email = \Config\Services::email();
            $email->clear(true);
            
            $email->setTo($targetEmail);
            $email->setSubject('FEFO Gudang - Auto Backup Database [' . date('d M Y H:i') . ']');
            $email->setMessage("Halo Admin,\n\nTerlampir file backup otomatis database FEFO Gudang.\n\nFile: " . basename($attachment) . "\nTanggal: " . date('d-m-Y H:i:s') . "\nUkuran: " . round(filesize($attachment) / 1024, 2) . " KB\n\nSalam,\nSistem FEFO Gudang");
            
            $email->attach($attachment);
            
            $result = $email->send();
            if ($result) {
                return true;
            }
            log_message('error', 'Standard SMTP send failed. Debug: ' . strip_tags($email->printDebugger(['headers', 'subject'])));
        } catch (\Throwable $e) {
            log_message('error', 'Exception in sendBackupEmail: ' . $e->getMessage());
        }

        return false;
    }

    public function parseMetadata(string $filepath): array
    {
        $meta = [
            'application' => '',
            'version'     => '',
            'database'    => '',
            'date'        => '',
            'note'        => '',
            'has_ddl'     => false,
        ];

        if (!file_exists($filepath)) return $meta;

        $handle = fopen($filepath, 'r');
        if (!$handle) return $meta;

        $linesRead = 0;
        while (($line = fgets($handle)) !== false && $linesRead < 100) {
            $linesRead++;
            if (strpos($line, '-- Application :') !== false) $meta['application'] = trim(str_replace('-- Application :', '', $line));
            if (strpos($line, '-- Version :'    ) !== false) $meta['version']      = trim(str_replace('-- Version :', '', $line));
            if (strpos($line, '-- Database :'   ) !== false) $meta['database']     = trim(str_replace('-- Database :', '', $line));
            if (strpos($line, '-- Date :'       ) !== false) $meta['date']         = trim(str_replace('-- Date :', '', $line));
            if (strpos($line, '-- Note :'       ) !== false) $meta['note']         = trim(str_replace('-- Note :', '', $line));
            if (strpos($line, 'CREATE TABLE'    ) !== false || strpos($line, 'INSERT INTO') !== false) {
                $meta['has_ddl'] = true;
            }
        }
        fclose($handle);

        return $meta;
    }

    public function verifyFile(string $filename): array
    {
        // FIX #2: Sanitasi di dalam library juga
        try {
            $filename = $this->sanitizeFilename($filename);
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }

        $filepath = $this->backupPath . $filename;
        if (!file_exists($filepath)) {
            return ['status' => false, 'message' => 'File tidak ditemukan.'];
        }

        $meta = $this->parseMetadata($filepath);
        if (!$meta['has_ddl']) {
            return ['status' => false, 'message' => 'File tidak memiliki perintah SQL yang valid.'];
        }
        if ($meta['application'] !== 'FEFO Gudang') {
            return ['status' => false, 'message' => 'Backup ini bukan berasal dari FEFO Gudang.'];
        }

        $shaFile = str_replace('.sql', '.sha256', $filepath);
        if (file_exists($shaFile)) {
            $expected = trim(file_get_contents($shaFile));
            $actual   = hash_file('sha256', $filepath);
            if ($expected !== $actual) {
                return ['status' => false, 'message' => 'Checksum tidak cocok! File rusak atau dimodifikasi.'];
            }
        }

        return [
            'status'           => true,
            'message'          => 'Backup Valid',
            'meta'             => $meta,
            'checksum_present' => file_exists($shaFile),
        ];
    }

    public function restore(string $filename): bool
    {
        // FIX #2: Sanitasi filename
        $filename = $this->sanitizeFilename($filename);
        $filepath = $this->backupPath . $filename;

        // FIX #9: Verifikasi hanya di sini — tidak dobel dengan Controller
        $verify = $this->verifyFile($filename);
        if (!$verify['status']) {
            throw new \Exception("Gagal: " . $verify['message']);
        }

        $handle = fopen($filepath, 'r');
        if (!$handle) {
            throw new \Exception("Gagal membuka file untuk dibaca.");
        }

        // FIX #3: Bungkus eksekusi dengan transaksi DB
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->db->transStart();

        $sqlBuffer = '';
        try {
            while (($line = fgets($handle)) !== false) {
                // FIX #7: Skip komentar (-- dan #) serta baris kosong
                if (preg_match('/^\s*(--|#)/', $line) || trim($line) === '') {
                    continue;
                }

                $sqlBuffer .= $line;

                if (substr(rtrim($line), -1) === ';') {
                    $this->db->query($sqlBuffer);
                    $sqlBuffer = '';
                }
            }
        } catch (\Exception $e) {
            fclose($handle);
            $this->db->transRollback();
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \Exception("Error eksekusi SQL: " . $e->getMessage() . " pada kueri: " . mb_substr($sqlBuffer, 0, 200));
        }

        fclose($handle);
        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1;");
            throw new \Exception("Transaksi gagal, database di-rollback ke kondisi semula.");
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS = 1;");

        return true;
    }

    // FIX #11: cleanupRotations — sebelumnya dibuat tapi tidak pernah dipanggil
    public function cleanupRotations(string $prefix, int $max): void
    {
        $files = glob($this->backupPath . $prefix . '_*.sql');
        if (!$files || count($files) <= $max) return;

        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));

        foreach (array_slice($files, $max) as $f) {
            @unlink($f);
            $sha = str_replace('.sql', '.sha256', $f);
            if (file_exists($sha)) @unlink($sha);
        }
    }
}