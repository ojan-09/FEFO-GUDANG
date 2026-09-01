<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id_user',
        'modul',
        'aktivitas',
        'deskripsi',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    // --- DataTables Variables ---
    protected $column_order  = ['activity_logs.created_at', 'users.username', 'activity_logs.modul', 'activity_logs.aktivitas', 'activity_logs.deskripsi'];
    protected $column_search = ['users.username', 'activity_logs.deskripsi'];
    protected $order         = ['activity_logs.created_at' => 'DESC'];

    /**
     * Builder dasar dengan join + filter — dipakai semua method query
     */
    public function _getDatatablesQuery($postData)
    {
        $builder = $this->db->table($this->table);
        $builder->select('activity_logs.*, users.username as nama_user, auth_groups.name as role');
        $builder->join('users', 'users.id = activity_logs.id_user', 'left');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left');

        // Custom filters
        if (!empty($postData['tanggal_mulai'])) {
            $builder->where('DATE(activity_logs.created_at) >=', $postData['tanggal_mulai']);
        }
        if (!empty($postData['tanggal_selesai'])) {
            $builder->where('DATE(activity_logs.created_at) <=', $postData['tanggal_selesai']);
        }
        if (!empty($postData['modul'])) {
            $builder->where('activity_logs.modul', $postData['modul']);
        }
        if (!empty($postData['aktivitas'])) {
            $builder->where('activity_logs.aktivitas', $postData['aktivitas']);
        }
        if (!empty($postData['role'])) {
            $builder->where('auth_groups.name', $postData['role']);
        }

        // DataTables Search
        $i = 0;
        if (isset($postData['search']['value']) && $postData['search']['value']) {
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $postData['search']['value']);
                } else {
                    $builder->orLike($item, $postData['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $builder->groupEnd();
                }
                $i++;
            }
        }

        // DataTables Ordering
        if (isset($postData['order'])) {
            $orderCol = $this->column_order[$postData['order']['0']['column']] ?? null;
            if ($orderCol) {
                $builder->orderBy($orderCol, $postData['order']['0']['dir']);
            }
        } else {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }

    /**
     * Ambil list rows untuk DataTables (dengan limit/offset)
     */
    public function getDatatables($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        if (isset($postData['length']) && $postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }
        return $builder->get()->getResultArray();
    }

    /**
     * [OPTIMASI] Gabungan getDatatables + countAll + countFiltered dalam 2 query.
     * Dipakai oleh ajaxData() di controller — menggantikan 3 query terpisah.
     *
     * @return array ['list' => array, 'total' => int, 'filtered' => int]
     */
    public function getDatatablesWithCount(array $postData): array
    {
        // Query 1: total semua baris tanpa filter
        $total = $this->db->table($this->table)->countAllResults(false);

        // Query 2: builder dengan filter — hitung filtered, lalu ambil data
        $builder = $this->_getDatatablesQuery($postData);

        // countAllResults() dengan false = tidak reset builder
        $filtered = $builder->countAllResults(false);

        // Terapkan limit + offset setelah count
        $length = isset($postData['length']) ? (int)$postData['length'] : 25;
        $start  = isset($postData['start'])  ? (int)$postData['start']  : 0;
        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $list = $builder->get()->getResultArray();

        return [
            'list'     => $list,
            'total'    => $total,
            'filtered' => $filtered,
        ];
    }

    /**
     * @deprecated Gunakan getDatatablesWithCount() untuk efisiensi.
     * Dipertahankan agar tidak breaking jika dipakai di tempat lain.
     */
    public function countFiltered($postData)
    {
        $builder = $this->_getDatatablesQuery($postData);
        return $builder->countAllResults();
    }

    /**
     * @deprecated Gunakan getDatatablesWithCount() untuk efisiensi.
     */
    public function countAllData()
    {
        return $this->db->table($this->table)->countAllResults();
    }
}