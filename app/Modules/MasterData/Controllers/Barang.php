<?php

namespace App\Modules\MasterData\Controllers;

use App\Controllers\BaseController;
use App\Modules\MasterData\Models\BarangModel;
use App\Modules\MasterData\Models\KategoriModel;

class Barang extends BaseController
{
    protected $barangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->barangModel   = new BarangModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Data Barang'
        ];
        return view('App\Modules\MasterData\Views\barang\index', $data);
    }

    public function create()
    {
        $data = [
            'title'       => 'Tambah Barang',
            'kode_barang' => $this->generateKodeBarang(),
            'kategori'    => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\barang\form', $data);
    }

    public function store()
    {
        $rules = [
            'id_kategori'      => 'required|integer',
            'nama_barang'      => 'required|min_length[3]|max_length[100]|is_unique[barang.nama_barang]',
            'satuan'           => 'required|in_list[Karung,Dus,Box,Pack,Pcs,Botol,Kaleng,Sak,Tray,Pouch]',
            'berat_per_satuan' => 'required|decimal|greater_than[0]',
            'satuan_berat'     => 'required|in_list[Gram,Kg]',
            'minimum_stok'     => 'required|integer|greater_than_equal_to[0]',
            'bisa_dipecah'     => 'required|in_list[0,1]',
        ];

        $errors = [
            'nama_barang' => [
                'required'   => 'Nama Barang wajib diisi.',
                'min_length' => 'Nama Barang minimal 3 karakter.',
                'max_length' => 'Nama Barang maksimal 100 karakter.',
                'is_unique'  => 'Nama Barang sudah digunakan.',
            ],
            'id_kategori' => [
                'required' => 'Kategori wajib dipilih.',
            ],
            'satuan' => [
                'required' => 'Satuan wajib dipilih.',
                'in_list'  => 'Satuan tidak valid.',
            ],
            'berat_per_satuan' => [
                'required'     => 'Berat per satuan wajib diisi.',
                'decimal'      => 'Berat harus berupa angka desimal.',
                'greater_than' => 'Berat harus lebih besar dari 0.',
            ],
            'satuan_berat' => [
                'required' => 'Satuan berat wajib dipilih.',
                'in_list'  => 'Satuan berat tidak valid.',
            ],
            'minimum_stok' => [
                'required'               => 'Minimum stok wajib diisi.',
                'integer'                => 'Minimum stok harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Minimum stok tidak boleh negatif.',
            ],
            'bisa_dipecah' => [
                'required' => 'Status Bisa Dipecah wajib dipilih.',
                'in_list'  => 'Status Bisa Dipecah tidak valid.',
            ],
        ];

        // Trim nama_barang
        $namaBarang = trim($this->request->getPost('nama_barang') ?? '');
        $_POST['nama_barang'] = $namaBarang;

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Repack (Bisa Dipecah -> konversi ke Kg) hanya boleh untuk kemasan Karung.
        $satuan = $this->request->getPost('satuan');
        $bisaDipecah = (int) $this->request->getPost('bisa_dipecah');
        if ($bisaDipecah === 1 && strtolower($satuan) !== 'karung') {
            return redirect()->back()->withInput()->with('errors', [
                'bisa_dipecah' => 'Status "Bisa Dipecah" (Repack) hanya berlaku untuk barang berkemasan Karung.',
            ]);
        }

        $kodeBarang = $this->generateKodeBarang();

        $this->barangModel->insert([
            'kode_barang'      => $kodeBarang,
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'nama_barang'      => $namaBarang,
            'satuan'           => $satuan,
            'berat_per_satuan' => $this->request->getPost('berat_per_satuan'),
            'satuan_berat'     => $this->request->getPost('satuan_berat'),
            'minimum_stok'     => $this->request->getPost('minimum_stok'),
            'bisa_dipecah'     => $bisaDipecah,
        ]);

        return redirect()->to(site_url('masterdata/barang'))->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Barang',
            'barang'   => $barang,
            'kategori' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ];
        return view('App\Modules\MasterData\Views\barang\form', $data);
    }

    public function update($id)
    {
        $rules = [
            'id_kategori'      => 'required|integer',
            'nama_barang'      => "required|min_length[3]|max_length[100]|is_unique[barang.nama_barang,id,{$id}]",
            'satuan'           => 'required|in_list[Karung,Dus,Box,Pack,Pcs,Botol,Kaleng,Sak,Tray,Pouch]',
            'berat_per_satuan' => 'required|decimal|greater_than[0]',
            'satuan_berat'     => 'required|in_list[Gram,Kg]',
            'minimum_stok'     => 'required|integer|greater_than_equal_to[0]',
            'bisa_dipecah'     => 'required|in_list[0,1]',
        ];

        $errors = [
            'nama_barang' => [
                'required'   => 'Nama Barang wajib diisi.',
                'min_length' => 'Nama Barang minimal 3 karakter.',
                'max_length' => 'Nama Barang maksimal 100 karakter.',
                'is_unique'  => 'Nama Barang sudah digunakan.',
            ],
            'id_kategori' => [
                'required' => 'Kategori wajib dipilih.',
            ],
            'satuan' => [
                'required' => 'Satuan wajib dipilih.',
                'in_list'  => 'Satuan tidak valid.',
            ],
            'berat_per_satuan' => [
                'required'     => 'Berat per satuan wajib diisi.',
                'decimal'      => 'Berat harus berupa angka desimal.',
                'greater_than' => 'Berat harus lebih besar dari 0.',
            ],
            'satuan_berat' => [
                'required' => 'Satuan berat wajib dipilih.',
                'in_list'  => 'Satuan berat tidak valid.',
            ],
            'minimum_stok' => [
                'required'               => 'Minimum stok wajib diisi.',
                'integer'                => 'Minimum stok harus berupa bilangan bulat.',
                'greater_than_equal_to' => 'Minimum stok tidak boleh negatif.',
            ],
            'bisa_dipecah' => [
                'required' => 'Status Bisa Dipecah wajib dipilih.',
                'in_list'  => 'Status Bisa Dipecah tidak valid.',
            ],
        ];

        $namaBarang = trim($this->request->getPost('nama_barang') ?? '');
        $_POST['nama_barang'] = $namaBarang;

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $barang = $this->barangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        // Repack (Bisa Dipecah -> konversi ke Kg) hanya boleh untuk kemasan Karung.
        $satuan = $this->request->getPost('satuan');
        $bisaDipecah = (int) $this->request->getPost('bisa_dipecah');
        if ($bisaDipecah === 1 && strtolower($satuan) !== 'karung') {
            return redirect()->back()->withInput()->with('errors', [
                'bisa_dipecah' => 'Status "Bisa Dipecah" (Repack) hanya berlaku untuk barang berkemasan Karung.',
            ]);
        }

        $this->barangModel->update($id, [
            'id_kategori'      => $this->request->getPost('id_kategori'),
            'nama_barang'      => $namaBarang,
            'satuan'           => $satuan,
            'berat_per_satuan' => $this->request->getPost('berat_per_satuan'),
            'satuan_berat'     => $this->request->getPost('satuan_berat'),
            'minimum_stok'     => $this->request->getPost('minimum_stok'),
            'bisa_dipecah'     => $bisaDipecah,
        ]);

        return redirect()->to(site_url('masterdata/barang'))->with('success', 'Barang berhasil diperbarui.');
    }

    public function delete($id)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }

        $this->barangModel->delete($id);
        return redirect()->to('/masterdata/barang')->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * AJAX endpoint untuk DataTables server-side
     */
    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $postData = $this->request->getPost();
        $list     = $this->barangModel->getDatatables($postData);
        $data     = [];
        $no       = $postData['start'];

        foreach ($list as $row) {
            $no++;
            $rowData = [];

            $bisaDipecah = $row['bisa_dipecah'] == 1 ? '<span class="badge" style="background:#dcfce7; color:#16a34a;"><i class="fa-solid fa-check me-1"></i>Ya</span>' : '<span class="badge" style="background:#fee2e2; color:#dc2626;"><i class="fa-solid fa-xmark me-1"></i>Tidak</span>';
            $beratPerSatuan = $row['berat_per_satuan'] > 0 ? rtrim(rtrim(number_format($row['berat_per_satuan'], 2, ',', '.'), '0'), ',') . ' ' . esc($row['satuan_berat']) : '-';

            $rowData[] = '<div class="text-center text-secondary">' . $no . '</div>';
            $rowData[] = '<span class="fw-bold" style="color:#2563eb;">' . esc($row['kode_barang']) . '</span>';
            $rowData[] = '<span class="fw-semibold" style="color:#0f172a;">' . esc($row['nama_barang']) . '</span>';
            $rowData[] = '<span class="badge bg-light text-dark border">' . esc($row['nama_kategori']) . '</span>';
            $rowData[] = '<span style="color:#475569;">' . esc($row['satuan']) . '</span>';
            $rowData[] = '<span style="color:#475569;">' . $beratPerSatuan . '</span>';
            $rowData[] = '<span class="badge" style="background:#fef3c7; color:#d97706;">' . esc($row['minimum_stok']) . ' ' . esc($row['satuan']) . '</span>';
            $rowData[] = '<div class="text-center">' . $bisaDipecah . '</div>';
            
            $aksi = '<div class="d-flex justify-content-center align-items-center gap-1">
                        <a href="' . site_url('masterdata/barang/edit/' . $row['id_barang']) . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="' . site_url('masterdata/barang/delete/' . $row['id_barang']) . '" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm(\'Yakin menghapus barang ini?\')"><i class="fa-solid fa-trash"></i></a>
                     </div>';
            $rowData[] = $aksi;
            $data[] = $rowData;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->barangModel->countAllData(),
            "recordsFiltered" => $this->barangModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }

    private function generateKodeBarang(): string
    {
        $db = \Config\Database::connect();
        $row = $db->table('barang')
            ->select('MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num')
            ->where('kode_barang LIKE', 'BRG-%')
            ->get()->getRowArray();
        $lastNumber = $row ? (int)$row['max_num'] : 0;
        $newNumber = $lastNumber + 1;
        return 'BRG-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}