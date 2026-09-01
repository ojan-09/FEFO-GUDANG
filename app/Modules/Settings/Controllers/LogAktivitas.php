<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;
use App\Services\ActivityLogFormatter;
use Myth\Auth\Models\GroupModel;

class LogAktivitas extends BaseController
{
    protected $activityLogModel;
    protected $groupModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
        $this->groupModel       = new GroupModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $request         = \Config\Services::request();
        $tanggal_mulai   = $request->getGet('tanggal_mulai');
        $tanggal_selesai = $request->getGet('tanggal_selesai');
        $modul           = $request->getGet('modul');
        $aktivitas       = $request->getGet('aktivitas');
        $role            = $request->getGet('role');
        $search          = $request->getGet('search');

        // KPI query
        $kpiBuilder = $db->table('activity_logs');
        $kpiBuilder->select('
            COUNT(activity_logs.id) as totalLog,
            SUM(CASE WHEN activity_logs.modul LIKE "%login%" OR activity_logs.modul LIKE "%auth%" OR activity_logs.aktivitas LIKE "%login%" THEN 1 ELSE 0 END) as countLogin,
            SUM(CASE WHEN activity_logs.modul LIKE "%penyaluran%" OR activity_logs.modul LIKE "%keluar%" THEN 1 ELSE 0 END) as countPenyaluran,
            SUM(CASE WHEN activity_logs.modul LIKE "%donasi%" OR activity_logs.modul LIKE "%masuk%" THEN 1 ELSE 0 END) as countDonasi,
            SUM(CASE WHEN activity_logs.modul LIKE "%penyesuaian%" THEN 1 ELSE 0 END) as countPenyesuaian
        ');
        $kpiBuilder->join('users', 'users.id = activity_logs.id_user', 'left');
        $kpiBuilder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left');
        $kpiBuilder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left');

        if (!empty($tanggal_mulai))   $kpiBuilder->where('DATE(activity_logs.created_at) >=', $tanggal_mulai);
        if (!empty($tanggal_selesai)) $kpiBuilder->where('DATE(activity_logs.created_at) <=', $tanggal_selesai);
        if (!empty($modul))           $kpiBuilder->where('activity_logs.modul', $modul);
        if (!empty($aktivitas))       $kpiBuilder->where('activity_logs.aktivitas', $aktivitas);
        if (!empty($role))            $kpiBuilder->where('auth_groups.name', $role);
        if (!empty($search)) {
            $kpiBuilder->groupStart()
                ->like('users.username', $search)
                ->orLike('activity_logs.deskripsi', $search)
                ->groupEnd();
        }

        $kpi = $kpiBuilder->get()->getRowArray();

        $data = [
            'title'            => 'Log Aktivitas (Audit Trail)',
            'kpi'              => $kpi,
            'roles'            => $this->groupModel->findAll(),
            'moduls'           => ['Autentikasi', 'Manajemen User', 'Profil', 'Donasi Masuk', 'Penyaluran Barang', 'Penyesuaian Stok'],
            'tanggal_mulai'    => $tanggal_mulai,
            'tanggal_selesai'  => $tanggal_selesai,
            'filter_modul'     => $modul,
            'filter_aktivitas' => $aktivitas,
            'filter_role'      => $role,
            'search'           => $search,
        ];

        return view('App\Modules\Settings\Views\log_aktivitas\index', $data);
    }

    /**
     * AJAX endpoint untuk DataTables server-side.
     *
     * Optimasi vs versi lama:
     *   1. gudangMap di-cache 1 jam  → tidak query DB tiap request
     *   2. getDatatablesWithCount()  → 2 query (bukan 3)
     *   3. strtotime() 1x per baris  → bukan 3x
     */
    public function ajaxData()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $postData = $this->request->getPost();

        // ── FIX 1: Cache gudangMap ────────────────────────────────────────────
        $gudangMap = cache('gudang_map');
        if (!$gudangMap) {
            $db         = \Config\Database::connect();
            $gudangList = $db->table('master_gudang_wilayah')
                             ->select('id, nama')
                             ->get()
                             ->getResultArray();
            $gudangMap  = array_column($gudangList, 'nama', 'id');
            cache()->save('gudang_map', $gudangMap, 3600); // TTL 1 jam
        }

        // ── FIX 2: 2 query (list + filtered + total) bukan 3 ─────────────────
        $result          = $this->activityLogModel->getDatatablesWithCount($postData);
        $list            = $result['list'];
        $recordsTotal    = $result['total'];
        $recordsFiltered = $result['filtered'];

        $data = [];

        foreach ($list as $log) {
            // ── FIX 3: strtotime() 1x per baris ──────────────────────────────
            $ts       = strtotime($log['created_at'] ?? 'now');
            $namaUser = $log['nama_user'] ?? 'Sistem';
            $initials = strtoupper(substr($namaUser, 0, 1));

            $formatted = ActivityLogFormatter::format($log, $gudangMap);

            $drawerData = htmlspecialchars(json_encode([
                'modul'    => $log['modul'],
                'aksi'     => $log['aktivitas'],
                'tanggal'  => date('d M Y - H:i', $ts) . ' WIB',
                'operator' => $namaUser,
                'summary'  => $formatted['summary'],
                'details'  => $formatted['details'],
                'raw_json' => $formatted['raw_json'],
            ]), ENT_QUOTES, 'UTF-8');

            $html = '
            <div class="timeline-card" onclick="openDrawer(this)" data-info="' . $drawerData . '">
                <div class="tl-time">
                    <div class="tl-date">' . date('d M Y', $ts) . '</div>
                    <div class="tl-hour">' . date('H:i:s', $ts) . ' WIB</div>
                </div>
                <div class="tl-content">
                    <div class="tl-user">
                        <div class="tl-avatar">' . $initials . '</div>
                        <div>
                            <div class="tl-uname">' . esc($namaUser) . '</div>
                            <div class="tl-urole">' . esc($log['role'] ?? 'Sistem') . '</div>
                        </div>
                    </div>
                    <div class="tl-module">
                        <i class="fa-solid ' . $formatted['icon'] . ' ' . $formatted['color_class'] . ' tl-icon"></i>
                        <div>
                            <div class="tl-mod-name">' . esc($log['modul']) . '</div>
                            <div class="tl-action ' . $formatted['badge_class'] . '">' . esc($log['aktivitas']) . '</div>
                        </div>
                    </div>
                    <div class="tl-desc">
                        ' . esc($formatted['summary']) . '
                    </div>
                </div>
            </div>';

            $data[] = [$html];
        }

        return $this->response->setJSON([
            'draw'            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
            csrf_token()      => csrf_hash(),
        ]);
    }
}