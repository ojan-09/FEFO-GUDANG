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
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        // Fitur Filter dan Search
        $request = \Config\Services::request();
        $tanggal_mulai = $request->getGet('tanggal_mulai');
        $tanggal_selesai = $request->getGet('tanggal_selesai');
        $modul = $request->getGet('modul');
        $aktivitas = $request->getGet('aktivitas');
        $role = $request->getGet('role');
        $search = $request->getGet('search');

        // Builder untuk KPI
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

        if (!empty($tanggal_mulai)) {
            $kpiBuilder->where('DATE(activity_logs.created_at) >=', $tanggal_mulai);
        }
        if (!empty($tanggal_selesai)) {
            $kpiBuilder->where('DATE(activity_logs.created_at) <=', $tanggal_selesai);
        }
        if (!empty($modul)) {
            $kpiBuilder->where('activity_logs.modul', $modul);
        }
        if (!empty($aktivitas)) {
            $kpiBuilder->where('activity_logs.aktivitas', $aktivitas);
        }
        if (!empty($role)) {
            $kpiBuilder->where('auth_groups.name', $role);
        }
        if (!empty($search)) {
            $kpiBuilder->groupStart()
                ->like('users.username', $search)
                ->orLike('activity_logs.deskripsi', $search)
                ->groupEnd();
        }

        $kpi = $kpiBuilder->get()->getRowArray();

        $data = [
            'title'     => 'Log Aktivitas (Audit Trail)',
            'kpi'       => $kpi,
            'roles'     => $this->groupModel->findAll(),
            'moduls'    => ['Autentikasi', 'Manajemen User', 'Profil', 'Donasi Masuk', 'Penyaluran Barang', 'Penyesuaian Stok'],
            'tanggal_mulai'   => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'filter_modul'    => $modul,
            'filter_aktivitas'=> $aktivitas,
            'filter_role'     => $role,
            'search'          => $search,
        ];

        return view('App\Modules\Settings\Views\log_aktivitas\index', $data);
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
        $list     = $this->activityLogModel->getDatatables($postData);
        $data     = [];

        // Bulk load gudang map to prevent N+1 query
        $db = \Config\Database::connect();
        $gudangList = $db->table('master_gudang_wilayah')->get()->getResultArray();
        $gudangMap = [];
        foreach ($gudangList as $g) {
            $gudangMap[$g['id']] = $g['nama'];
        }

        foreach ($list as $log) {
            $rowData = [];
            
            $formatted = ActivityLogFormatter::format($log, $gudangMap);

            $namaUser = $log['nama_user'] ?? 'Sistem';
            $initials = strtoupper(substr($namaUser, 0, 1));
            
            // JSON Data for Drawer
            $drawerData = htmlspecialchars(json_encode([
                'modul'     => $log['modul'],
                'aksi'      => $log['aktivitas'],
                'tanggal'   => date('d M Y - H:i', strtotime($log['created_at'])) . ' WIB',
                'operator'  => $namaUser,
                'summary'   => $formatted['summary'],
                'details'   => $formatted['details'],
                'raw_json'  => $formatted['raw_json']
            ]), ENT_QUOTES, 'UTF-8');

            $html = '
            <div class="timeline-card" onclick="openDrawer(this)" data-info="' . $drawerData . '">
                <div class="tl-time">
                    <div class="tl-date">' . date('d M Y', strtotime($log['created_at'])) . '</div>
                    <div class="tl-hour">' . date('H:i:s', strtotime($log['created_at'])) . ' WIB</div>
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

            $rowData[] = $html;
            $data[] = $rowData;
        }

        $output = [
            "draw"            => isset($postData['draw']) ? intval($postData['draw']) : 0,
            "recordsTotal"    => $this->activityLogModel->countAllData(),
            "recordsFiltered" => $this->activityLogModel->countFiltered($postData),
            "data"            => $data,
            csrf_token()      => csrf_hash()
        ];

        return $this->response->setJSON($output);
    }
}

