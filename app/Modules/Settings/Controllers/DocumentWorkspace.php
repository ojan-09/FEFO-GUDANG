<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DocumentNumberService;
use App\Libraries\ActivityLogger;
use Config\Database;
use Dompdf\Dompdf;
use Dompdf\Options;

class DocumentWorkspace extends BaseController
{
    protected DocumentNumberService $docService;
    protected $db;

    public function __construct()
    {
        if (function_exists('in_groups')) {
            if (!\in_groups('Administrator')) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
        $this->docService = new DocumentNumberService();
        $this->db = Database::connect();
    }

    /**
     * Halaman Utama Workspace Dokumen & PDF (Hanya Berita Acara & Barang Keluar)
     */
    public function index()
    {
        $globalConfig = $this->docService->getGlobalConfig();
        $currentNum   = intval($globalConfig['current_number']);
        $nextFormatted = $this->docService->getFormattedNumber(null, $currentNum + 1);
        $lastFormatted = $this->docService->getFormattedNumber(null, $currentNum);

        $baConfig = $this->db->table('document_configs')->where('document_key', 'berita_acara')->get()->getRowArray();
        $bkConfig = $this->db->table('document_configs')->where('document_key', 'barang_keluar')->get()->getRowArray();

        $baProvisionCount = $this->db->table('document_provisions')
            ->where('document_key', 'berita_acara')
            ->where('version', $baConfig['current_version'] ?? 1)
            ->where('is_active', 1)
            ->countAllResults();

        $totalHistory = $this->db->table('document_history')->countAllResults();
        $totalLogs    = $this->db->table('document_activity_logs')->countAllResults();

        return view('App\Modules\Settings\Views\document_workspace\index', [
            'title'            => 'Workspace Dokumen & PDF',
            'globalConfig'     => $globalConfig,
            'lastFormatted'    => $lastFormatted,
            'nextFormatted'    => $nextFormatted,
            'baConfig'         => $baConfig,
            'bkConfig'         => $bkConfig,
            'baProvisionCount' => $baProvisionCount,
            'totalHistory'     => $totalHistory,
            'totalLogs'        => $totalLogs,
            'activeDocKey'     => 'dashboard',
        ]);
    }

    /**
     * Detail Workspace (Berita Acara atau Barang Keluar)
     */
    public function detail(string $documentKey)
    {
        if (!in_array($documentKey, ['berita_acara', 'barang_keluar'])) {
            return redirect()->to(site_url('pengaturan/dokumen'))->with('error', 'Hanya Berita Acara dan Barang Keluar yang dikelola.');
        }

        $globalConfig = $this->docService->getGlobalConfig();
        $config = $this->db->table('document_configs')->where('document_key', $documentKey)->get()->getRowArray();

        $currentNum    = intval($globalConfig['current_number']);
        $nextFormatted = $this->docService->getFormattedNumber(null, $currentNum + 1);

        $provisions = [];
        if ($documentKey === 'berita_acara') {
            $provisions = $this->db->table('document_provisions')
                ->where('document_key', 'berita_acara')
                ->where('version', $config['current_version'] ?? 1)
                ->orderBy('sort_order', 'ASC')
                ->get()
                ->getResultArray();
        }

        $history = $this->db->table('document_history')
            ->where('document_key', $documentKey)
            ->orderBy('id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        $activityLogs = $this->db->table('document_activity_logs')
            ->whereIn('document_key', ['global_counter', $documentKey])
            ->orderBy('id', 'DESC')
            ->limit(50)
            ->get()
            ->getResultArray();

        $sampleRefList = $this->getSampleTransactions();

        return view('App\Modules\Settings\Views\document_workspace\detail', [
            'title'         => 'Workspace ' . $config['title'],
            'config'        => $config,
            'globalConfig'  => $globalConfig,
            'nextFormatted' => $nextFormatted,
            'provisions'    => $provisions,
            'history'       => $history,
            'activityLogs'  => $activityLogs,
            'sampleRefList' => $sampleRefList,
            'activeDocKey'  => $documentKey,
        ]);
    }

    /**
     * Update Global Counter & Pattern Format (Berlaku Bersama BA & Barang Keluar)
     */
    public function updateNumbering(string $documentKey)
    {
        $globalConfig = $this->docService->getGlobalConfig();

        $oldFormat   = $globalConfig['number_format'];
        $oldCurrent  = $globalConfig['current_number'];
        $oldReset    = $globalConfig['reset_rule'];
        $oldSemester = $globalConfig['active_semester'];
        $oldMonth    = $globalConfig['active_month'];
        $oldYear     = $globalConfig['active_year'];

        $newFormat   = $this->request->getPost('number_format');
        $newCurrent  = intval($this->request->getPost('current_number'));
        $newReset    = $this->request->getPost('reset_rule');
        $newSemester = $this->request->getPost('active_semester');
        $newMonth    = $this->request->getPost('active_month');
        $newYear     = $this->request->getPost('active_year');

        $updateData = [
            'number_format'   => $newFormat,
            'current_number'  => $newCurrent,
            'reset_rule'      => $newReset,
            'active_semester' => $newSemester,
            'active_month'    => $newMonth,
            'active_year'     => $newYear,
            'updated_at'      => date('Y-m-d H:i:s'),
        ];

        // Sync all document configs to maintain single counter consistency
        $this->db->table('document_configs')->whereIn('document_key', ['global_counter', 'berita_acara', 'barang_keluar'])->update($updateData);

        $username = user()->username ?? 'Administrator';

        if ($oldFormat !== $newFormat) {
            $this->docService->logActivity($username, 'global_counter', 'Perubahan Format Penomoran', 'number_format', $oldFormat, $newFormat);
        }
        if ($oldCurrent !== $newCurrent) {
            $this->docService->logActivity($username, 'global_counter', 'Perubahan Counter Global', 'current_number', (string)$oldCurrent, (string)$newCurrent);
        }
        if ($oldReset !== $newReset) {
            $this->docService->logActivity($username, 'global_counter', 'Perubahan Aturan Reset', 'reset_rule', $oldReset, $newReset);
        }
        if ($oldSemester !== $newSemester) {
            $this->docService->logActivity($username, 'global_counter', 'Perubahan Semester', 'active_semester', $oldSemester, $newSemester);
        }
        if ($oldMonth !== $newMonth) {
            $this->docService->logActivity($username, 'global_counter', 'Perubahan Bulan', 'active_month', $oldMonth, $newMonth);
        }
        if ($oldYear !== $newYear) {
            $this->docService->logActivity($username, 'global_counter', 'Perubahan Tahun', 'active_year', $oldYear, $newYear);
        }

        ActivityLogger::log('Edit', 'Document Workspace', "Mengubah Pengaturan Penomoran Bersama Dokumen (Counter Global: {$newCurrent})");

        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('pengaturan/dokumen/' . $documentKey))->with('success', 'Konfigurasi penomoran bersama berhasil disimpan.');
    }

    /**
     * Tambah Ketentuan Berita Acara
     */
    public function addProvision(string $documentKey)
    {
        if ($documentKey !== 'berita_acara') {
            return redirect()->back()->with('error', 'Ketentuan hanya untuk Berita Acara.');
        }

        $config = $this->db->table('document_configs')->where('document_key', 'berita_acara')->get()->getRowArray();
        $content   = trim($this->request->getPost('content') ?? '');
        $sortOrder = intval($this->request->getPost('sort_order') ?? 1);

        if (empty($content)) {
            return redirect()->back()->with('error', 'Ketentuan tidak boleh kosong.');
        }

        $currentVer = intval($config['current_version'] ?? 1);

        $this->db->table('document_provisions')->insert([
            'document_key' => 'berita_acara',
            'version'      => $currentVer,
            'content'      => $content,
            'sort_order'   => $sortOrder,
            'is_active'    => 1,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $username = user()->username ?? 'Administrator';
        $this->docService->logActivity($username, 'berita_acara', 'Penambahan Ketentuan', 'content', '', $content);

        ActivityLogger::log('Tambah', 'Document Workspace', "Menambahkan Ketentuan Berita Acara: {$content}");

        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('pengaturan/dokumen/berita_acara'))->with('success', 'Ketentuan baru Berita Acara berhasil ditambahkan.');
    }

    /**
     * Edit Ketentuan Berita Acara
     */
    public function updateProvision(string $documentKey, int $provisionId)
    {
        $provision = $this->db->table('document_provisions')->where('id', $provisionId)->get()->getRowArray();
        if (!$provision) {
            return redirect()->back()->with('error', 'Ketentuan tidak ditemukan.');
        }

        $oldContent = $provision['content'];
        $newContent = trim($this->request->getPost('content') ?? '');
        $sortOrder  = intval($this->request->getPost('sort_order') ?? $provision['sort_order']);
        $isActive   = intval($this->request->getPost('is_active') ?? $provision['is_active']);

        $this->db->table('document_provisions')->where('id', $provisionId)->update([
            'content'    => $newContent,
            'sort_order' => $sortOrder,
            'is_active'  => $isActive,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $username = user()->username ?? 'Administrator';
        if ($oldContent !== $newContent) {
            $this->docService->logActivity($username, 'berita_acara', 'Perubahan Ketentuan #' . $provisionId, 'content', $oldContent, $newContent);
        }

        ActivityLogger::log('Edit', 'Document Workspace', "Mengubah Ketentuan Berita Acara #{$provisionId}: {$newContent}");

        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('pengaturan/dokumen/berita_acara'))->with('success', 'Ketentuan Berita Acara berhasil diperbarui.');
    }

    /**
     * Hapus Ketentuan Berita Acara
     */
    public function deleteProvision(string $documentKey, int $provisionId)
    {
        $provision = $this->db->table('document_provisions')->where('id', $provisionId)->get()->getRowArray();
        if (!$provision) {
            return redirect()->back()->with('error', 'Ketentuan tidak ditemukan.');
        }

        $this->db->table('document_provisions')->where('id', $provisionId)->delete();

        $username = user()->username ?? 'Administrator';
        $this->docService->logActivity($username, 'berita_acara', 'Penghapusan Ketentuan', 'content', $provision['content'], 'DELETED');

        ActivityLogger::log('Hapus', 'Document Workspace', "Menghapus Ketentuan Berita Acara #{$provisionId}");

        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('pengaturan/dokumen/berita_acara'))->with('success', 'Ketentuan Berita Acara berhasil dihapus.');
    }

    /**
     * Versioning Ketentuan Berita Acara
     */
    public function newVersion(string $documentKey)
    {
        if ($documentKey !== 'berita_acara') {
            return redirect()->back()->with('error', 'Versioning ketentuan hanya untuk Berita Acara.');
        }

        $config = $this->db->table('document_configs')->where('document_key', 'berita_acara')->get()->getRowArray();
        $oldVer = intval($config['current_version'] ?? 1);
        $newVer = $oldVer + 1;

        $oldProvisions = $this->db->table('document_provisions')
            ->where('document_key', 'berita_acara')
            ->where('version', $oldVer)
            ->get()
            ->getResultArray();

        foreach ($oldProvisions as $p) {
            $this->db->table('document_provisions')->insert([
                'document_key' => 'berita_acara',
                'version'      => $newVer,
                'content'      => $p['content'],
                'sort_order'   => $p['sort_order'],
                'is_active'    => $p['is_active'],
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->table('document_configs')->where('document_key', 'berita_acara')->update([
            'current_version' => $newVer,
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        $username = user()->username ?? 'Administrator';
        $this->docService->logActivity($username, 'berita_acara', 'Rilis Versi Ketentuan Baru', 'current_version', (string)$oldVer, (string)$newVer);

        ActivityLogger::log('Tambah', 'Document Workspace', "Merapikan Versi Baru Ketentuan Berita Acara Versi {$newVer}");

        helper('format'); clear_dashboard_cache();
        return redirect()->to(site_url('pengaturan/dokumen/berita_acara'))->with('success', "Versi ketentuan baru Berita Acara (v{$newVer}) berhasil dirilis!");
    }

    /**
     * Live Preview PDF Menggunakan Template PDF Existing.
     * PENTING: Live Preview HANYA menampilkan nomor simulasi (current_number + 1) TANPA menaikkan counter DB.
     */
    public function previewPdf(string $documentKey)
    {
        $sampleRef = $this->request->getGet('ref_id');

        // Simulasi Nomor Berikutnya (Counter DB TIDAK BERUBAH)
        $previewNumber = $this->docService->getFormattedNumber();

        if ($documentKey === 'berita_acara') {
            $provisions = $this->docService->getActiveProvisions();
            $html = $this->renderBeritaAcaraHtml($previewNumber, $provisions, $sampleRef);
        } else {
            $html = $this->renderBarangKeluarHtml($previewNumber, $sampleRef);
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream("PREVIEW_" . strtoupper($documentKey) . ".pdf", ["Attachment" => false]);
        exit;
    }

    /**
     * Halaman Riwayat Dokumen Global (Berita Acara & Barang Keluar)
     */
    public function history()
    {
        $history = $this->db->table('document_history')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('App\Modules\Settings\Views\document_workspace\history', [
            'title'        => 'Riwayat Dokumen & PDF',
            'history'      => $history,
            'activeDocKey' => 'riwayat',
        ]);
    }

    /**
     * Halaman Activity Log Pengaturan Dokumen Global
     */
    public function activityLog()
    {
        $logs = $this->db->table('document_activity_logs')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('App\Modules\Settings\Views\document_workspace\activity_log', [
            'title'        => 'Activity Log Workspace Dokumen',
            'logs'         => $logs,
            'activeDocKey' => 'activity_log',
        ]);
    }

    private function getSampleTransactions(): array
    {
        $res = $this->db->table('barang_keluar')->orderBy('id', 'DESC')->limit(10)->get()->getResultArray();
        $list = [];
        foreach ($res as $r) {
            $list[] = [
                'id' => $r['id'],
                'label' => "No. " . ($r['nomor_transaksi'] ?? $r['id']) . " - " . ($r['tujuan_penyaluran'] ?? 'Penyaluran') . " (" . date('d/m/Y', strtotime($r['tanggal_keluar'])) . ")"
            ];
        }
        return $list;
    }

    private function renderBeritaAcaraHtml(string $docNumber, array $provisions, ?string $sampleRef = null): string
    {
        $bkModel = new \App\Modules\Transactions\Models\BarangKeluarModel();
        $bkDetailModel = new \App\Modules\Transactions\Models\DetailBarangKeluarModel();

        $barangKeluar = null;
        if ($sampleRef) {
            $barangKeluar = $bkModel
                ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
                ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
                ->join('users', 'users.id = barang_keluar.id_user', 'left')
                ->find($sampleRef);
        }
        if (!$barangKeluar) {
            $barangKeluar = $bkModel
                ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
                ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
                ->join('users', 'users.id = barang_keluar.id_user', 'left')
                ->orderBy('barang_keluar.id', 'DESC')
                ->first();
        }

        $items = [];
        if ($barangKeluar) {
            $items = $bkDetailModel
                ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
                ->join('batch', 'batch.id = detail_barang_keluar.id_batch')
                ->join('barang', 'barang.id = batch.id_barang', 'left')
                ->where('detail_barang_keluar.id_barang_keluar', $barangKeluar['id'])
                ->findAll();
        }

        $data = [
            'title'           => 'Berita Acara Pendistribusian Donasi',
            'barangKeluar'    => $barangKeluar,
            'details'         => $items,
            'document_number' => $docNumber,
            'provisions'      => array_column($provisions, 'content'),
        ];

        return view('laporan/berita_acara_penyaluran', $data);
    }

    private function renderBarangKeluarHtml(string $docNumber, ?string $sampleRef = null): string
    {
        $bkModel = new \App\Modules\Transactions\Models\BarangKeluarModel();
        $bkDetailModel = new \App\Modules\Transactions\Models\DetailBarangKeluarModel();

        $barangKeluar = null;
        if ($sampleRef) {
            $barangKeluar = $bkModel
                ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
                ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
                ->join('users', 'users.id = barang_keluar.id_user', 'left')
                ->find($sampleRef);
        }
        if (!$barangKeluar) {
            $barangKeluar = $bkModel
                ->select('barang_keluar.*, wilayah.nama_wilayah, users.username as petugas')
                ->join('wilayah', 'wilayah.id = barang_keluar.id_wilayah', 'left')
                ->join('users', 'users.id = barang_keluar.id_user', 'left')
                ->orderBy('barang_keluar.id', 'DESC')
                ->first();
        }

        $items = [];
        if ($barangKeluar) {
            $items = $bkDetailModel
                ->select('detail_barang_keluar.*, batch.nomor_batch, batch.tanggal_kedaluwarsa, batch.jumlah_awal, batch.stok_saat_ini, COALESCE(barang.nama_barang, batch.nama_barang) as nama_barang, batch.satuan, batch.berat_per_satuan, batch.satuan_berat, batch.bisa_dipecah')
                ->join('batch', 'batch.id = detail_barang_keluar.id_batch')
                ->join('barang', 'barang.id = batch.id_barang', 'left')
                ->where('detail_barang_keluar.id_barang_keluar', $barangKeluar['id'])
                ->findAll();
        }

        $data = [
            'title'           => 'Surat Jalan / Laporan Barang Keluar',
            'barangKeluar'    => $barangKeluar,
            'details'         => $items,
            'document_number' => $docNumber,
        ];

        return view('laporan/permintaan_barang_keluar_internal', $data);
    }
}
