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
        $data = [
            'title' => 'Daftar Penyesuaian Stok'
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
        $builder->where('barang.status', 'active');
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

    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $postData = $this->request->getPost();
        $list     = $this->penyesuaianModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $trx) {
            $no++;
            $row = [];

            // Badge Jenis Penyesuaian
            $badgeClass = $trx['jenis_penyesuaian'] == 'Penambahan' ? 'bg-success' : 'bg-danger';
            $icon = $trx['jenis_penyesuaian'] == 'Penambahan' ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
            $badgeJenis = '<span class="badge ' . $badgeClass . ' px-2 py-1"><i class="fa-solid ' . $icon . ' me-1"></i>' . esc($trx['jenis_penyesuaian']) . '</span>';

            $row[] = '<div class="text-center text-secondary">' . $no . '</div>';
            $row[] = '<span class="badge" style="background:#EEF4FF; color:#2563eb; font-weight:600; padding:5px 10px; font-size:11.5px;">' . esc($trx['nomor_penyesuaian']) . '</span>';
            $row[] = '<span style="color:#475569;">' . date('d M Y', strtotime($trx['tanggal'])) . '</span>';
            $row[] = '<div class="text-center">' . $badgeJenis . '</div>';
            $row[] = '<div class="text-center"><span class="badge" style="background:#FEE2E2; color:#dc2626; font-size:11.5px; font-weight:600;">' . esc($trx['total_item']) . ' Item</span></div>';
            $row[] = '<span style="color:#475569;">' . esc($trx['username'] ?? 'Sistem') . '</span>';
            $row[] = '<span style="color:#64748b; font-size:12px;">' . esc($trx['keterangan'] ?: '-') . '</span>';
            
            $aksi = '<div class="d-flex justify-content-center gap-1">
                        <a href="' . site_url('transaksi/penyesuaian/detail/' . $trx['id']) . '" class="btn btn-sm btn-info text-white" title="Lihat Detail" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; border:none; background:#0ea5e9;">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <form action="' . site_url('transaksi/penyesuaian/delete/' . $trx['id']) . '" method="POST" class="d-inline form-delete-swal" data-confirm-text="Yakin ingin menghapus riwayat penyesuaian ini? Stok batch akan dikembalikan ke kondisi sebelumnya.">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; border:none; background:#ef4444; cursor:pointer;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                     </div>';
            $row[] = $aksi;
            $data[] = $row;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->penyesuaianModel->countAllData(),
            "recordsFiltered" => $this->penyesuaianModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
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
        $db->transBegin();

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
            if (!$barang) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', "Barang dengan ID '{$item['id_barang']}' tidak ditemukan.");
            }
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
                        'satuan' => $item['satuan'] ?? $barang['satuan'],
                        'berat_per_satuan' => $barang['berat_per_satuan'],
                        'satuan_berat' => $barang['satuan_berat'],
                        'nama_barang' => $barang['nama_barang'],
                        'bisa_dipecah' => $barang['bisa_dipecah'],
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
                'satuan' => $barang['bisa_dipecah'] == 1 ? 'Kg' : ($item['satuan'] ?? $barang['satuan']),
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $item['keterangan'] ?? null
            ]);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi penyesuaian stok.');
        }
        $db->transCommit();

        // Log Activity
        $jenis = ucfirst($this->request->getPost('jenis_penyesuaian') ?: 'Lainnya');

        \App\Libraries\ActivityLogger::log(
            'Tambah',
            'Penyesuaian Stok',
            "Menambahkan Penyesuaian Stok\nNo. {$nomor}\nJenis : {$jenis}"
        );

        helper('format');
        clear_dashboard_cache();
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
        $builder->join('batch', 'batch.id = dps.id_batch', 'left');
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
        $db->transBegin();

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

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menghapus penyesuaian stok.');
        }
        $db->transCommit();

        // Log Activity
        \App\Libraries\ActivityLogger::log(
            'Hapus',
            'Penyesuaian Stok',
            "Menghapus Penyesuaian Stok\nNo. {$penyesuaian['nomor_penyesuaian']}"
        );

        helper('format');
        clear_dashboard_cache();
        return redirect()->to(site_url('transaksi/penyesuaian'))->with('success', 'Penyesuaian stok berhasil dihapus dan stok telah di-rollback.');
    }
}
