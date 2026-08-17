<?php

namespace App\Modules\Wilayah\Controllers;

use App\Controllers\BaseController;
use App\Modules\Wilayah\Models\MasterGudangWilayahModel;
use App\Modules\Wilayah\Models\MasterBarangWilayahModel;
use App\Modules\MasterData\Models\KategoriModel;
use App\Modules\MasterData\Models\DonaturModel;

class StokWilayah extends BaseController
{
    protected $gudangModel;
    protected $barangModel;
    protected $kategoriModel;
    protected $donaturModel;

    public function __construct()
    {
        $this->gudangModel   = new MasterGudangWilayahModel();
        $this->barangModel   = new MasterBarangWilayahModel();
        $this->kategoriModel = new KategoriModel();
        $this->donaturModel  = new DonaturModel();
        helper(["auth", "format"]);
    }

    public function index()
    {
        $isAdmin = function_exists("in_groups") ? in_groups("Administrator") : true;
        $userGudangId = (function_exists("user") && user()) ? user()->id_gudang_wilayah : null;

        $db = \Config\Database::connect();
        $builderCards = $db->table("stok_gudang_wilayah s");
        if (!$isAdmin && $userGudangId) {
            $builderCards->where("s.id_gudang", $userGudangId);
        }
        $builderCards->where("s.jumlah >", 0);

        // Summary Cards
        $totalJenisBarang = (clone $builderCards)->select("COUNT(DISTINCT s.id_barang) as total", false)->get()->getRow()->total ?? 0;
        $totalSisaStok    = (clone $builderCards)->selectSum("s.jumlah", "total")->get()->getRow()->total ?? 0;
        $totalRecordStok  = (clone $builderCards)->countAllResults();

        // Warehouse reference list with single key cache
        $cacheKey = "gudang_wilayah_list_all";
        $allGudang = cache()->get($cacheKey);
        if ($allGudang === null) {
            $allGudang = $this->gudangModel->findAll();
            cache()->save($cacheKey, $allGudang, 3600);
        }

        if (!$isAdmin && $userGudangId) {
            $gudang = array_filter($allGudang, function($g) use ($userGudangId) {
                return $g["id"] == $userGudangId;
            });
        } else {
            $gudang = $allGudang;
        }

        $data = [
            "title"        => "Monitoring Stok Gudang Wilayah",
            "gudang"       => $gudang,
            "kategori"     => $this->kategoriModel->findAll(),
            "barang"       => $this->barangModel->where("deleted_at", null)->findAll(),
            "donatur"      => $this->donaturModel->findAll(),
            "isAdmin"      => $isAdmin,
            "userGudangId" => $userGudangId
        ];

        return view("App\Modules\Wilayah\Views\stok\index", $data);
    }

    public function ajaxData()
    {
        try {
            $isAdmin = function_exists("in_groups") ? in_groups("Administrator") : true;
            $userGudangId = (function_exists("user") && user()) ? user()->id_gudang_wilayah : null;

            $idGudang   = $this->request->getVar("id_gudang");
            $idKategori = $this->request->getVar("id_kategori");
            $idBarang   = $this->request->getVar("id_barang");
            $idDonatur  = $this->request->getVar("id_donatur");
            $search     = $this->request->getPost("search")["value"] ?? '';

            // Strict backend access control override
            if (!$isAdmin) {
                $idGudang = $userGudangId;
            }

            $db = \Config\Database::connect();
            
            // Build count builder
            $countBuilder = $db->table("stok_gudang_wilayah s");
            $countBuilder->join("master_gudang_wilayah m", "m.id = s.id_gudang");
            $countBuilder->join("master_barang_wilayah brg", "brg.id = s.id_barang");
            $countBuilder->join("kategori kat", "kat.id = brg.id_kategori", "left");

            if (!empty($idGudang)) {
                $countBuilder->where("s.id_gudang", $idGudang);
            }
            if (!empty($idKategori)) {
                $countBuilder->where("brg.id_kategori", $idKategori);
            }
            if (!empty($idBarang)) {
                $countBuilder->where("s.id_barang", $idBarang);
            }
            if (!empty($idDonatur)) {
                $countBuilder->whereIn("s.id_barang", function($subQuery) use ($idDonatur, $idGudang) {
                    $subQuery->select("dbm.id_barang")
                             ->from("barang_masuk_wilayah bm")
                             ->join("detail_barang_masuk_wilayah dbm", "dbm.id_masuk = bm.id")
                             ->where("bm.id_donatur", $idDonatur)
                             ->where("bm.deleted_at", null);
                    if (!empty($idGudang)) {
                        $subQuery->where("bm.id_gudang", $idGudang);
                    }
                    return $subQuery;
                });
            }

            $totalRecords = (clone $countBuilder)->countAllResults(false);

            if (!empty($search)) {
                $countBuilder->groupStart();
                $countBuilder->like("brg.nama_barang", $search);
                $countBuilder->orLike("brg.kode_barang", $search);
                $countBuilder->orLike("m.nama", $search);
                $countBuilder->groupEnd();
                $filteredRecords = $countBuilder->countAllResults(false);
            } else {
                $filteredRecords = $totalRecords;
            }

            // Data Query
            $builder = $db->table("stok_gudang_wilayah s");
            $builder->select("s.*, m.nama as nama_gudang, brg.nama_barang, brg.kode_barang, brg.satuan, brg.berat_per_satuan as brg_berat_per_satuan, brg.satuan_berat as brg_satuan_berat, kat.nama_kategori as kategori");
            $builder->join("master_gudang_wilayah m", "m.id = s.id_gudang");
            $builder->join("master_barang_wilayah brg", "brg.id = s.id_barang");
            $builder->join("kategori kat", "kat.id = brg.id_kategori", "left");

            if (!empty($idGudang)) {
                $builder->where("s.id_gudang", $idGudang);
            }
            if (!empty($idKategori)) {
                $builder->where("brg.id_kategori", $idKategori);
            }
            if (!empty($idBarang)) {
                $builder->where("s.id_barang", $idBarang);
            }
            if (!empty($idDonatur)) {
                $builder->whereIn("s.id_barang", function($subQuery) use ($idDonatur, $idGudang) {
                    $subQuery->select("dbm.id_barang")
                             ->from("barang_masuk_wilayah bm")
                             ->join("detail_barang_masuk_wilayah dbm", "dbm.id_masuk = bm.id")
                             ->where("bm.id_donatur", $idDonatur)
                             ->where("bm.deleted_at", null);
                    if (!empty($idGudang)) {
                        $subQuery->where("bm.id_gudang", $idGudang);
                    }
                    return $subQuery;
                });
            }

            if (!empty($search)) {
                $builder->groupStart();
                $builder->like("brg.nama_barang", $search);
                $builder->orLike("brg.kode_barang", $search);
                $builder->orLike("m.nama", $search);
                $builder->groupEnd();
            }

            // Dynamic Sorting from DataTables request
            $orderParam = $this->request->getPost("order");
            $columnsMap = [
                1 => 'brg.kode_barang',
                2 => 'brg.nama_barang',
                4 => 'kat.nama_kategori',
                5 => 'm.nama',
                6 => 's.jumlah',
                7 => 'brg.satuan',
                9 => 's.updated_at'
            ];
            if (!empty($orderParam) && isset($orderParam[0]['column'])) {
                $colIdx = (int)$orderParam[0]['column'];
                $dir = strtoupper($orderParam[0]['dir']) === 'DESC' ? 'DESC' : 'ASC';
                if (isset($columnsMap[$colIdx])) {
                    $builder->orderBy($columnsMap[$colIdx], $dir);
                } else {
                    $builder->orderBy("m.nama", "ASC");
                    $builder->orderBy("brg.nama_barang", "ASC");
                }
            } else {
                // Default order: Gudang ASC, Nama Barang ASC
                $builder->orderBy("m.nama", "ASC");
                $builder->orderBy("brg.nama_barang", "ASC");
            }

            $length = $this->request->getPost("length") ?? 10;
            $start  = $this->request->getPost("start") ?? 0;
            $builder->limit($length, $start);

            $dataRows = $builder->get()->getResultArray();

            // Page-level Donatur & Total Masuk Mapping (Efficient, avoids full-table GROUP_CONCAT)
            $donaturMap = [];
            $totalMasukMap = [];
            if (!empty($dataRows)) {
                $itemGudangPairs = [];
                foreach ($dataRows as $r) {
                    $itemGudangPairs[] = [
                        "id_gudang" => $r["id_gudang"],
                        "id_barang" => $r["id_barang"]
                    ];
                }

                $donaturQuery = $db->table("barang_masuk_wilayah bm")
                                  ->select("bm.id_gudang, dbm.id_barang, dn.nama_donatur, dbm.jumlah")
                                  ->join("detail_barang_masuk_wilayah dbm", "dbm.id_masuk = bm.id")
                                  ->join("donatur dn", "dn.id = bm.id_donatur", "left")
                                  ->where("bm.deleted_at", null);

                $donaturQuery->groupStart();
                foreach ($itemGudangPairs as $pair) {
                    $donaturQuery->orGroupStart()
                                 ->where("bm.id_gudang", $pair["id_gudang"])
                                 ->where("dbm.id_barang", $pair["id_barang"])
                                 ->groupEnd();
                }
                $donaturQuery->groupEnd();

                $donaturResults = $donaturQuery->get()->getResultArray();

                foreach ($donaturResults as $dr) {
                    $key = $dr["id_gudang"] . "_" . $dr["id_barang"];
                    if (!isset($donaturMap[$key])) {
                        $donaturMap[$key] = [];
                        $totalMasukMap[$key] = 0;
                    }
                    if (!empty($dr["nama_donatur"]) && !in_array($dr["nama_donatur"], $donaturMap[$key])) {
                        $donaturMap[$key][] = $dr["nama_donatur"];
                    }
                    $totalMasukMap[$key] += floatval($dr["jumlah"]);
                }
            }

            $resultData = [];
            $no = $start + 1;
            foreach ($dataRows as $row) {
                $mapKey = $row["id_gudang"] . "_" . $row["id_barang"];
                $donaturList = $donaturMap[$mapKey] ?? [];
                $totalMasuk = $totalMasukMap[$mapKey] ?? 0;

                if (empty($donaturList)) {
                    $donaturStr = "-";
                } elseif (count($donaturList) > 2) {
                    $donaturStr = '<span class="wh-badge text-secondary" title="' . esc(implode(', ', $donaturList)) . '">Multi Donatur (' . count($donaturList) . ')</span>';
                } else {
                    $donaturStr = esc(implode(', ', $donaturList));
                }

                $lastUpdate = !empty($row["updated_at"]) ? date("d/m/Y H:i", strtotime($row["updated_at"])) : "-";

                $totalBerat = floatval($row["total_berat"] ?? 0);
                if ($totalBerat <= 0 && !empty($row["brg_berat_per_satuan"])) {
                    $satuanBerat = $row["brg_satuan_berat"] ?? 'Kg';
                    $beratInKg   = strtolower($satuanBerat) === 'gram' ? (floatval($row["brg_berat_per_satuan"]) / 1000) : floatval($row["brg_berat_per_satuan"]);
                    $totalBerat  = floatval($row["jumlah"]) * $beratInKg;
                }
                $totalBeratFmt = number_format($totalBerat, 2, ",", ".") . ' Kg';

                $resultData[] = [
                    $no++,
                    esc($row["kode_barang"]),
                    esc($row["nama_barang"]),
                    $donaturStr,
                    esc($row["kategori"] ?? "-"),
                    esc($row["nama_gudang"]),
                    number_format($row["jumlah"], 0, ",", "."),
                    esc($row["satuan"]),
                    $totalBeratFmt,
                    $lastUpdate
                ];
            }

            return $this->response->setJSON([
                "draw"            => intval($this->request->getPost("draw")),
                "recordsTotal"    => $totalRecords,
                "recordsFiltered" => $filteredRecords,
                "data"            => $resultData,
                csrf_token()        => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message("error", "[StokWilayah] " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                "error"   => "Internal Server Error",
                "message" => ENVIRONMENT === "development" ? $e->getMessage() : "Terjadi kesalahan sistem saat memuat data stok."
            ]);
        }
    }
}