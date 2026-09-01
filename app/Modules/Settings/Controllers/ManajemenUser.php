<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Entities\User;

class ManajemenUser extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('users');
        $builder->select('users.id, users.username, users.email, users.divisi, users.active, users.created_at, users.id_gudang_wilayah, (SELECT MAX(date) FROM auth_logins WHERE email = users.email AND success = 1) as last_login_at');
        $builder->select('auth_groups.name as role_name');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left');
        $builder->orderBy('users.id', 'DESC');

        $users = $builder->get()->getResultArray();
        $roles = $this->groupModel->findAll();

        $gudangWilayahModel = new \App\Modules\Wilayah\Models\MasterGudangWilayahModel();
        $gudang = $gudangWilayahModel->where('status', 'Aktif')->findAll();

        $data = [
            'title'  => 'Manajemen User',
            'users'  => $users,
            'roles'  => $roles,
            'gudang' => $gudang,
        ];

        return view('App\Modules\Settings\Views\manajemen_user\index', $data);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active'   => 1,
        ]);

        $this->userModel->save($user);
        $newUserId = $this->userModel->getInsertID();

        $idGudangWilayah = $this->request->getPost('id_gudang_wilayah') ?: null;
        $divisi          = $this->request->getPost('divisi') ?: null;
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $newUserId)->update([
            'id_gudang_wilayah' => $idGudangWilayah,
            'divisi'            => $divisi,
        ]);

        $roleId = $this->request->getPost('role');
        $group  = $this->groupModel->find($roleId);
        if ($group) {
            $groupId  = is_object($group) ? $group->id   : $group['id'];
            $roleName = is_object($group) ? $group->name : $group['name'];
            $this->groupModel->addUserToGroup((int)$newUserId, (int)$groupId);
        } else {
            $roleName = 'Tidak Diketahui';
        }

        \App\Libraries\ActivityLogger::log(
            'Tambah User',
            'Manajemen User',
            "Menambahkan pengguna baru: {$this->request->getPost('username')} dengan role {$roleName}."
        );

        helper('format'); clear_dashboard_cache();
        return redirect()->to('manajemen-user')->with('success', 'User berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'username' => "required|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            'role'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('manajemen-user')->with('error', 'User tidak ditemukan.');
        }

        $user->username = $this->request->getPost('username');
        if ($user->hasChanged()) {
            $this->userModel->skipValidation(true)->save($user);
        }

        $idGudangWilayah = $this->request->getPost('id_gudang_wilayah') ?: null;
        $divisi          = $this->request->getPost('divisi') ?: null;
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->update([
            'id_gudang_wilayah' => $idGudangWilayah,
            'divisi'            => $divisi,
        ]);

        $roleId = $this->request->getPost('role');
        $group  = $this->groupModel->find($roleId);
        if ($group) {
            $groupId  = is_object($group) ? $group->id   : $group['id'];
            $roleName = is_object($group) ? $group->name : $group['name'];
            $this->groupModel->removeUserFromAllGroups((int)$id);
            $this->groupModel->addUserToGroup((int)$id, (int)$groupId);
        } else {
            $roleName = 'Tidak Diketahui';
        }

        \App\Libraries\ActivityLogger::log(
            'Edit User / Ubah Role',
            'Manajemen User',
            "Mengubah profil atau role pengguna {$user->username} menjadi {$roleName}."
        );

        helper('format'); clear_dashboard_cache();
        return redirect()->to('manajemen-user')->with('success', 'User berhasil diperbarui.');
    }

    public function resetPassword($id)
    {
        $rules = ['password' => 'required|min_length[8]'];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('manajemen-user')->with('error', 'User tidak ditemukan.');
        }

        $user->setPassword($this->request->getPost('password'));
        $this->userModel->save($user);

        \App\Libraries\ActivityLogger::log(
            'Reset Password',
            'Manajemen User',
            "Mereset password pengguna {$user->username}."
        );

        helper('format'); clear_dashboard_cache();
        return redirect()->to('manajemen-user')->with('success', 'Password berhasil direset.');
    }

    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('manajemen-user')->with('error', 'User tidak ditemukan.');
        }

        // ── PROTEKSI: tidak boleh menonaktifkan akun sendiri ─────────────────
        if ((int)$id === (int)user()->id) {
            return redirect()->to('manajemen-user')->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // ── PROTEKSI: tidak boleh menonaktifkan satu-satunya admin aktif ─────
        $db      = \Config\Database::connect();
        $isAdmin = $db->table('auth_groups_users')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where('auth_groups_users.user_id', $id)
            ->where('auth_groups.name', 'administrator')
            ->countAllResults() > 0;

        if ($isAdmin && $user->active == 1) {
            $activeAdmins = $db->table('users')
                ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                ->where('auth_groups.name', 'administrator')
                ->where('users.active', 1)
                ->countAllResults();

            if ($activeAdmins <= 1) {
                return redirect()->to('manajemen-user')->with('error', 'Tidak dapat menonaktifkan satu-satunya Administrator aktif.');
            }
        }

        $user->active = ($user->active == 1) ? 0 : 1;
        $this->userModel->save($user);

        $statusMsg = $user->active ? 'diaktifkan' : 'dinonaktifkan';

        \App\Libraries\ActivityLogger::log(
            $user->active ? 'Aktifkan User' : 'Nonaktifkan User',
            'Manajemen User',
            ucfirst($statusMsg) . " akun pengguna {$user->username}."
        );

        helper('format'); clear_dashboard_cache();
        return redirect()->to('manajemen-user')->with('success', "Akun berhasil {$statusMsg}.");
    }
}