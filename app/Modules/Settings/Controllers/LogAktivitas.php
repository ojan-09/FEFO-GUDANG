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
        $builder = $db->table('activity_logs');
        $builder->select('activity_logs.*, users.username as nama_user, auth_groups.name as role');
        $builder->join('users', 'users.id = activity_logs.id_user', 'left');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left');

        // Fitur Filter dan Search
        $request = \Config\Services::request();
        $tanggal_mulai = $request->getGet('tanggal_mulai');
        $tanggal_selesai = $request->getGet('tanggal_selesai');
        $modul = $request->getGet('modul');
        $aktivitas = $request->getGet('aktivitas');
        $role = $request->getGet('role');
        $search = $request->getGet('search');

        if (!empty($tanggal_mulai)) {
            $builder->where('DATE(activity_logs.created_at) >=', $tanggal_mulai);
        }
        if (!empty($tanggal_selesai)) {
            $builder->where('DATE(activity_logs.created_at) <=', $tanggal_selesai);
        }
        if (!empty($modul)) {
            $builder->where('activity_logs.modul', $modul);
        }
        if (!empty($aktivitas)) {
            $builder->where('activity_logs.aktivitas', $aktivitas);
        }
        if (!empty($role)) {
            $builder->where('auth_groups.name', $role);
        }
        if (!empty($search)) {
            $builder->groupStart()
                ->like('users.username', $search)
                ->orLike('activity_logs.deskripsi', $search)
                ->groupEnd();
        }

        $builder->orderBy('activity_logs.created_at', 'DESC');
        $logs = $builder->get()->getResultArray();

        $data = [
            'title'     => 'Log Aktivitas (Audit Trail)',
            'logs'      => $logs,
            'roles'     => $this->groupModel->findAll(),
            'moduls'    => ['Autentikasi', 'Manajemen User', 'Profil', 'Donasi Masuk', 'Penyaluran Barang'],
            // Mengambil nilai filter untuk form
            'tanggal_mulai'   => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'filter_modul'    => $modul,
            'filter_aktivitas'=> $aktivitas,
            'filter_role'     => $role,
            'search'          => $search,
        ];

        return view('App\Modules\Settings\Views\log_aktivitas\index', $data);
    }
}

