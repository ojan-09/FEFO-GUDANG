<?php

namespace App\Modules\Transactions\Controllers;

use App\Controllers\BaseController;
use App\Modules\Transactions\Models\PenyesuaianStokModel;
use App\Modules\Transactions\Models\DetailPenyesuaianStokModel;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\Transactions\Models\BatchModel;

class PenyesuaianStok extends BaseController
{
    protected $penyesuaianModel;
    protected $detailPenyesuaianModel;
    protected $barangModel;
    protected $batchModel;

    public function __construct()
    {
        $this->penyesuaianModel = new PenyesuaianStokModel();
        $this->detailPenyesuaianModel = new DetailPenyesuaianStokModel();
        $this->barangModel = new BarangModel();
        $this->batchModel = new BatchModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('penyesuaian_stok ps');
        $builder->select('ps.*, users.username, COUNT(dps.id) as total_item');
        $builder->join('users', 'users.id = ps.id_user', 'left');
        $builder->join('detail_penyesuaian_stok dps', 'dps.id_penyesuaian = ps.id', 'left');
        $builder->groupBy('ps.id');
        $builder->orderBy('ps.tanggal', 'DESC');
        $builder->orderBy('ps.id', 'DESC');

        $data = [
            'title' => 'Riwayat Penyesuaian Stok',
            'transaksi' => $builder->get()->getResultArray()
        ];

        return view('App\Modules\Transactions\Views\penyesuaian\index', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('barang');
        $builder->select('barang.*');
        $builder->join('batch', 'batch.id_barang = barang.id');
        $builder->where('batch.stok_saat_ini >', 0);
        $builder->where('batch.status', 'Aktif');
        $builder->groupBy('barang.id');
        $builder->orderBy('barang.nama_barang', 'ASC');
        
        $data = [
            'title' => 'Tambah Penyesuaian Stok',
            'barang' => $builder->get()->getResultArray()
        ];
        return view('App\Modules\Transactions\Views\penyesuaian\form', $data);
    }

    public function getBatches($idBarang)
    {
        $batches = $this->batchModel->where('id_barang', $idBarang)
                                    ->where('stok_saat_ini >', 0)
                                    ->where('status', 'Aktif')
                                    ->orderBy('tanggal_kedaluwarsa', 'ASC')
                                    ->findAll();
        
        $barang = $this->barangModel->find($idBarang);
        
        return $this->response->setJSON([
            'batches' => $batches,
            'barang' => $barang
        ]);
    }

    public function store()
    {
        $jenis = $this->request->getPost('jenis_penyesuaian');
        $keteranganUmum = $this->request->getPost('keterangan');
        $items = $this->request->getPost('items'); // array of ['id_barang', 'id_batch' (for minus), 'tanggal_kedaluwarsa' (for plus), 'jumlah', 'satuan', 'keterangan']

        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Minimal satu barang harus dipilih.');
        }

        // Validate keterangan for audit
        if (empty($keteranganUmum) && $jenis !== 'Barang Kedaluwarsa') {
            return redirect()->back()->withInput()->with('error', 'Keterangan/Alasan wajib diisi untuk audit.');
        }
        if (empty($keteranganUmum)) {
            $keteranganUmum = 'Penyesuaian otomatis untuk barang kedaluwarsa.';
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $nomor = $this->penyesuaianModel->generateNomor();
        
        $penyesuaianId = $this->penyesuaianModel->insert([
            'nomor_penyesuaian' => $nomor,
            'tanggal' => date('Y-m-d'),
            'jenis_penyesuaian' => $jenis,
            'keterangan' => $keteranganUmum,
            'id_user' => user()->id
        ]);

        foreach ($items as $item) {
            $barang = $this->barangModel->find($item['id_barang']);
            $jumlah = (float) $item['jumlah'];

            // Validasi utuh vs desimal
            if ($barang['bisa_dipecah'] == 0 && floor($jumlah) != $jumlah) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', "Barang utuh ({$barang['nama_barang']}) tidak boleh menggunakan angka desimal.");
            }

            if ($jenis === 'Koreksi Positif') {
                $expDate = $item['tanggal_kedaluwarsa'];
                if (empty($expDate)) {
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('error', "Tanggal kedaluwarsa wajib diisi untuk Koreksi Positif.");
                }

                $batch = $this->batchModel->where('id_barang', $barang['id'])->where('tanggal_kedaluwarsa', $expDate)->first();
                
                if ($batch) {
                    $stokSebelum = (float) $batch['stok_saat_ini'];
                    $stokSesudah = $stokSebelum + $jumlah;
                    $this->batchModel->update($batch['id'], [
                        'stok_saat_ini' => $stokSesudah,
                        'status' => 'Aktif'
                    ]);
                    $idBatch = $batch['id'];
                } else {
                    $stokSebelum = 0;
                    $stokSesudah = $jumlah;
                    
                    // Generate new batch number for Positive Correction
                    $prefix = 'B-' . date('Ymd') . '-';
                    $lastBatch = $this->batchModel->like('nomor_batch', $prefix, 'after')->orderBy('id', 'DESC')->first();
                    $newNum = $lastBatch ? intval(substr($lastBatch['nomor_batch'], -4)) + 1 : 1;
                    $nomorBatch = $prefix . str_pad($newNum, 4, '0', STR_PAD_LEFT);

                    $idBatch = $this->batchModel->insert([
                        'nomor_batch' => $nomorBatch,
                        'id_barang' => $barang['id'],
                        'id_barang_masuk' => 0, // 0 indicates it's from adjustment
                        'jumlah_awal' => $jumlah,
                        'stok_saat_ini' => $jumlah,
                        'tanggal_kedaluwarsa' => $expDate,
                        'status' => 'Aktif',
                        'kategori' => $barang['kategori'],
                        'satuan' => $barang['satuan'],
                        'berat_per_satuan' => $barang['berat_per_satuan'],
                        'satuan_berat' => $barang['satuan_berat'],
                        'nama_barang' => $barang['nama_barang']
                    ]);
                }

            } else {
                // Pengurangan Stok (Rusak, Hilang, dll)
                $idBatch = $item['id_batch'];
                if (empty($idBatch)) {
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('error', "Batch wajib dipilih untuk jenis penyesuaian ini.");
                }

                $batch = $this->batchModel->find($idBatch);
                $stokSebelum = (float) $batch['stok_saat_ini'];
                $stokSesudah = $stokSebelum - $jumlah;

                if ($stokSesudah < 0) {
                    $db->transRollback();
                    return redirect()->back()->withInput()->with('error', "Jumlah penyesuaian melebihi stok yang ada pada batch terpilih untuk barang {$barang['nama_barang']}.");
                }

                $this->batchModel->update($batch['id'], [
                    'stok_saat_ini' => $stokSesudah,
                    'status' => ($stokSesudah <= 0) ? 'Habis' : 'Aktif'
                ]);
            }

            $this->detailPenyesuaianModel->insert([
                'id_penyesuaian' => $penyesuaianId,
                'id_barang' => $barang['id'],
                'id_batch' => $idBatch,
                'jumlah' => $jumlah,
                'satuan' => $barang['bisa_dipecah'] == 1 ? 'Kg' : $barang['satuan'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $item['keterangan'] ?? null
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi penyesuaian stok.');
        }

        // Log Activity
        $activityModel = new \App\Models\ActivityLogModel();
        $activityModel->insert([
            'id_user'   => user()->id,
            'modul'     => 'Transaksi',
            'aktivitas' => 'Menambahkan Penyesuaian Stok',
            'deskripsi' => $nomor
        ]);

        return redirect()->to(site_url('transaksi/penyesuaian'))->with('success', 'Transaksi penyesuaian stok berhasil disimpan.');
    }

    public function detail($id)
    {
        $penyesuaian = $this->penyesuaianModel->select('penyesuaian_stok.*, users.username')
            ->join('users', 'users.id = penyesuaian_stok.id_user', 'left')
            ->find($id);

        if (!$penyesuaian) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $db = \Config\Database::connect();
        $builder = $db->table('detail_penyesuaian_stok dps');
        $builder->select('dps.*, barang.nama_barang, barang.bisa_dipecah, batch.nomor_batch, batch.tanggal_kedaluwarsa');
        $builder->join('barang', 'barang.id = dps.id_barang');
        $builder->join('batch', 'batch.id = dps.id_batch');
        $builder->where('dps.id_penyesuaian', $id);
        $details = $builder->get()->getResultArray();

        $data = [
            'title' => 'Detail Penyesuaian Stok',
            'penyesuaian' => $penyesuaian,
            'details' => $details
        ];

        return view('App\Modules\Transactions\Views\penyesuaian\detail', $data);
    }

    public function delete($id)
    {
        $penyesuaian = $this->penyesuaianModel->find($id);
        if (!$penyesuaian) {
            return redirect()->to(site_url('transaksi/penyesuaian'))->with('error', 'Data tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $details = $this->detailPenyesuaianModel->where('id_penyesuaian', $id)->findAll();
        $isKoreksiPositif = ($penyesuaian['jenis_penyesuaian'] === 'Koreksi Positif');

        foreach ($details as $detail) {
            $batch = $this->batchModel->find($detail['id_batch']);
            if (!$batch) continue;

            $stokSaatIni = (float) $batch['stok_saat_ini'];
            $stokSesudahTransaksi = (float) $detail['stok_sesudah'];
            
            // Validasi rollback: pastikan stok saat ini belum berubah oleh transaksi lain
            // Jika stok_saat_ini tidak sama dengan stok_sesudah transaksi, berarti batch sudah diotak-atik
            if (abs($stokSaatIni - $stokSesudahTransaksi) > 0.0001) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Tidak dapat menghapus transaksi karena stok pada salah satu batch sudah digunakan/dimodifikasi oleh transaksi lain.');
            }

            // Kembalikan stok ke stok_sebelum
            $stokKembali = (float) $detail['stok_sebelum'];
            
            $this->batchModel->update($batch['id'], [
                'stok_saat_ini' => $stokKembali,
                'status' => ($stokKembali <= 0) ? 'Habis' : 'Aktif'
            ]);
        }

        $this->detailPenyesuaianModel->where('id_penyesuaian', $id)->delete();
        $this->penyesuaianModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menghapus penyesuaian stok.');
        }

        // Log Activity
        $activityModel = new \App\Models\ActivityLogModel();
        $activityModel->insert([
            'id_user'   => user()->id,
            'modul'     => 'Transaksi',
            'aktivitas' => 'Menghapus Penyesuaian Stok',
            'deskripsi' => $penyesuaian['nomor_penyesuaian']
        ]);

        return redirect()->to(site_url('transaksi/penyesuaian'))->with('success', 'Penyesuaian stok berhasil dihapus dan stok telah di-rollback.');
    }
}
