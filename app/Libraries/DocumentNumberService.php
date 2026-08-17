<?php

namespace App\Libraries;

use Config\Database;

class DocumentNumberService
{
    /**
     * Get global configuration for single shared counter.
     */
    public function getGlobalConfig(): array
    {
        $db = Database::connect();
        $config = $db->table('document_configs')->where('document_key', 'global_counter')->get()->getRowArray();

        if (!$config) {
            // Default fallback
            return [
                'id'              => 1,
                'document_key'    => 'global_counter',
                'title'           => 'Nomor Dokumen Bersama',
                'number_format'   => '{nomor}/{semester}/{bulan}/{tahun}',
                'current_number'  => 400,
                'reset_rule'      => 'none',
                'active_semester' => 'SEM2',
                'active_month'    => '8',
                'active_year'     => '26',
                'current_version' => 1
            ];
        }

        return $config;
    }

    /**
     * Format current active number without incrementing counter (for display & preview ONLY).
     * Opening preview multiple times DOES NOT increase counter.
     */
    public function getFormattedNumber(?string $documentKey = null, ?int $customNumber = null): string
    {
        $config = $this->getGlobalConfig();
        $num = $customNumber ?? (intval($config['current_number']) + 1);
        return $this->renderPattern($config, $num);
    }

    /**
     * Get next document number with atomic row-locking & transaction counter increment.
     * Shared globally between Berita Acara and Barang Keluar.
     * Prevents duplicate numbers under concurrent requests.
     */
    public function getNextDocumentNumber(string $documentType, ?int $refId = null, ?string $createdBy = null, array $provisions = []): string
    {
        $db = Database::connect();
        $db->transStart();

        // FOR UPDATE lock on single global counter
        $config = $db->query("SELECT * FROM document_configs WHERE document_key = 'global_counter' FOR UPDATE")->getRowArray();

        if (!$config) {
            $config = [
                'number_format'   => '{nomor}/{semester}/{bulan}/{tahun}',
                'current_number'  => 400,
                'reset_rule'      => 'none',
                'active_semester' => 'SEM2',
                'active_month'    => '8',
                'active_year'     => '26',
            ];
        }

        $currentNum = intval($config['current_number']);
        $lastReset = $config['last_reset_date'] ?? null;
        $nowDate = date('Y-m-d');
        $shouldReset = false;

        if ($lastReset) {
            $lastM = date('m', strtotime($lastReset));
            $lastY = date('Y', strtotime($lastReset));
            $currM = date('m');
            $currY = date('Y');

            if ($config['reset_rule'] === 'monthly' && ($lastM !== $currM || $lastY !== $currY)) {
                $shouldReset = true;
            } elseif ($config['reset_rule'] === 'yearly' && $lastY !== $currY) {
                $shouldReset = true;
            } elseif ($config['reset_rule'] === 'semester') {
                $lastSem = ($lastM <= 6) ? 1 : 2;
                $currSem = ($currM <= 6) ? 1 : 2;
                if ($lastSem !== $currSem || $lastY !== $currY) {
                    $shouldReset = true;
                }
            }
        }

        if ($shouldReset) {
            $currentNum = 1;
            $config['last_reset_date'] = $nowDate;
        } else {
            $currentNum = $currentNum + 1;
        }

        // Render Pattern
        $formattedNumber = $this->renderPattern($config, $currentNum);

        // Update database global counter atomically
        $db->table('document_configs')->where('document_key', 'global_counter')->update([
            'current_number'  => $currentNum,
            'last_reset_date' => $nowDate,
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        // Keep berita_acara & barang_keluar configs in sync with current_number for UI display
        $db->table('document_configs')->whereIn('document_key', ['berita_acara', 'barang_keluar'])->update([
            'current_number'  => $currentNum,
            'last_reset_date' => $nowDate,
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        // Record permanent document history
        $docName = ($documentType === 'berita_acara') ? 'Berita Acara' : 'Barang Keluar';
        $user = $createdBy ?? (user()->username ?? 'Petugas');

        $db->table('document_history')->insert([
            'document_key'        => $documentType,
            'document_name'       => $docName,
            'document_number'     => $formattedNumber,
            'ref_id'              => $refId,
            'config_version'      => intval($config['current_version'] ?? 1),
            'provisions_snapshot' => json_encode($provisions),
            'created_by'          => $user,
            'status'              => 'Terbit',
            'created_at'          => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        return $formattedNumber;
    }

    /**
     * Render tags pattern into final document number string.
     */
    public function renderPattern(array $config, int $number): string
    {
        $pattern = $config['number_format'] ?? '{nomor}/{semester}/{bulan}/{tahun}';
        
        $prefix = $config['number_prefix'] ?? '';
        $semester = $config['active_semester'] ?? 'SEM2';
        $bulan = $config['active_month'] ?? date('n');
        $tahun = $config['active_year'] ?? date('y');
        $tahunFull = date('Y');

        $replacements = [
            '{prefix}'     => $prefix,
            '{nomor}'      => $number,
            '{nomor_pad3}' => str_pad($number, 3, '0', STR_PAD_LEFT),
            '{nomor_pad4}' => str_pad($number, 4, '0', STR_PAD_LEFT),
            '{nomor_pad5}' => str_pad($number, 5, '0', STR_PAD_LEFT),
            '{nomor_pad6}' => str_pad($number, 6, '0', STR_PAD_LEFT),
            '{semester}'   => $semester,
            '{bulan}'      => $bulan,
            '{tahun}'      => $tahun,
            '{tahun_full}' => $tahunFull,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $pattern);
    }

    /**
     * Get active provisions snapshot for Berita Acara versioning.
     */
    public function getActiveProvisions(?int $version = null): array
    {
        $db = Database::connect();

        if ($version === null) {
            $config = $db->table('document_configs')->where('document_key', 'berita_acara')->get()->getRowArray();
            $version = $config ? intval($config['current_version']) : 1;
        }

        return $db->table('document_provisions')
            ->where('document_key', 'berita_acara')
            ->where('version', $version)
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Log Admin configuration changes.
     */
    public function logActivity(string $username, string $documentKey, string $actionType, ?string $fieldName = null, ?string $beforeVal = null, ?string $afterVal = null): void
    {
        $db = Database::connect();
        $db->table('document_activity_logs')->insert([
            'username'     => $username,
            'document_key' => $documentKey,
            'action_type'  => $actionType,
            'field_name'   => $fieldName,
            'before_value' => $beforeVal,
            'after_value'  => $afterVal,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
    }
}
