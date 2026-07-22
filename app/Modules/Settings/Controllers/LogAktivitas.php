<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;
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

        foreach ($list as $log) {
            $rowData = [];
            
            $modul = strtolower($log['modul'] ?? '');
            
            $icon = 'fa-circle-dot'; $colorCls = 'mod-login'; $bgCls = 'bg-login';
            if (strpos($modul, 'donasi') !== false || strpos($modul, 'masuk') !== false) {
                $icon = 'fa-hand-holding-heart'; $colorCls = 'mod-donasi'; $bgCls = 'bg-donasi';
            } elseif (strpos($modul, 'penyaluran') !== false || strpos($modul, 'keluar') !== false) {
                $icon = 'fa-truck-fast'; $colorCls = 'mod-penyaluran'; $bgCls = 'bg-penyaluran';
            } elseif (strpos($modul, 'penyesuaian') !== false) {
                $icon = 'fa-scale-balanced'; $colorCls = 'mod-penyesuaian'; $bgCls = 'bg-penyesuaian';
            } elseif (strpos($modul, 'user') !== false || strpos($modul, 'profil') !== false) {
                $icon = 'fa-users-gear'; $colorCls = 'mod-user'; $bgCls = 'bg-user';
            } elseif (strpos($modul, 'auth') !== false || strpos($modul, 'login') !== false) {
                $icon = 'fa-shield-halved'; $colorCls = 'mod-login'; $bgCls = 'bg-login';
            }

            $namaUser = $log['nama_user'] ?? 'Sistem';
            $initials = strtoupper(substr($namaUser, 0, 1));
            
            // JSON Data for Drawer
            $drawerData = htmlspecialchars(json_encode([
                'modul' => $log['modul'],
                'aksi' => $log['aktivitas'],
                'tanggal' => date('d M Y - H:i', strtotime($log['created_at'])) . ' WIB',
                'operator' => $namaUser,
                'deskripsi' => $log['deskripsi']
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
                        <i class="fa-solid ' . $icon . ' ' . $colorCls . ' tl-icon"></i>
                        <div>
                            <div class="tl-mod-name">' . esc($log['modul']) . '</div>
                            <div class="tl-action ' . $bgCls . '">' . esc($log['aktivitas']) . '</div>
                        </div>
                    </div>
                    <div class="tl-desc">
                        ' . nl2br(esc($log['deskripsi'])) . '
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

