<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BackupManager;
use App\Libraries\ActivityLogger;
use Config\Services;

class BackupRestore extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'backup:restore';
    protected $description = 'Restore database from a backup file';
    protected $usage       = 'backup:restore [filename.sql]';

    public function run(array $params)
    {
        if (empty($params[0])) {
            CLI::error('Silakan sebutkan nama file. Contoh: php spark backup:restore daily_20260720.sql');
            return;
        }

        $filename = $params[0];
        CLI::write("PERINGATAN: Ini akan menimpa seluruh database Anda!", 'red');
        $confirm = CLI::prompt('Ketik "RESTORE" untuk melanjutkan');
        
        if ($confirm !== 'RESTORE') {
            CLI::write('Dibatalkan.', 'yellow');
            return;
        }

        CLI::write("Memproses restorasi dari {$filename}...", 'cyan');

        $mgr = new BackupManager();
        $cache = Services::cache();

        try {
            // Verify
            $verify = $mgr->verifyFile($filename);
            if (!$verify['status']) {
                throw new \Exception("Validasi gagal: " . $verify['message']);
            }

            // Restore Point
            CLI::write("Membuat restore point otomatis...", 'cyan');
            $rpName = $mgr->generateBackup('restore_point', 'Auto-backup sebelum restore via CLI');
            CLI::write("Restore point dibuat: {$rpName}", 'green');

            // Execute
            CLI::write("Mengeksekusi SQL...", 'cyan');
            $mgr->restore($filename);

            CLI::write("Database berhasil dipulihkan!", 'green');
            
            ActivityLogger::log('Edit', 'Restore Database', "Melakukan Restore Database via CLI\nFile Sumber: {$filename}");
        } catch (\Exception $e) {
            CLI::error("Gagal: " . $e->getMessage());
        }
    }
}
