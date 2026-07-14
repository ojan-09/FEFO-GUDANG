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
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('users');
        $builder->select('users.id, users.username, users.email, users.active, users.created_at, (SELECT MAX(date) FROM auth_logins WHERE email = users.email AND success = 1) as last_login_at');
        $builder->select('auth_groups.name as role_name');
        $builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left');
        $builder->orderBy('users.id', 'DESC');
        
        $users = $builder->get()->getResultArray();
        
        $roles = $this->groupModel->findAll();

        $data = [
            'title' => 'Manajemen User',
            'users' => $users,
            'roles' => $roles
        ];

        return view('App\Modules\Settings\Views\manajemen_user\index', $data);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active'   => 1
        ]);

        $this->userModel->save($user);

        // Tambahkan Role
        $roleId = $this->request->getPost('role');
        $group = $this->groupModel->find($roleId);
        if ($group) {
            $groupId = is_object($group) ? $group->id : $group['id'];
            $this->groupModel->addUserToGroup((int)$this->userModel->getInsertID(), (int)$groupId);
            $roleName = is_object($group) ? $group->name : $group['name'];
        } else {
            $roleName = 'Tidak Diketahui';
        }

        \App\Libraries\ActivityLogger::log(
            'Tambah User',
            'Manajemen User',
            "Menambahkan pengguna baru: {$this->request->getPost('username')} dengan role {$roleName}."
        );

        return redirect()->to('manajemen-user')->with('success', 'User berhasil ditambahkan.');
    }

    public function update($id)
    {
        $rules = [
            'username' => "required|min_length[3]|max_length[30]|is_unique[users.username,id,{$id}]",
            'role'     => 'required'
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

        // Update Role
        $roleId = $this->request->getPost('role');
        $group = $this->groupModel->find($roleId);
        if ($group) {
            $groupId = is_object($group) ? $group->id : $group['id'];
            $this->groupModel->removeUserFromAllGroups((int)$id);
            $this->groupModel->addUserToGroup((int)$id, (int)$groupId);
            
            // Nama role untuk activity log
            $roleName = is_object($group) ? $group->name : $group['name'];
        } else {
            $roleName = "Tidak Diketahui";
        }

        \App\Libraries\ActivityLogger::log(
            'Edit User / Ubah Role',
            'Manajemen User',
            "Mengubah profil atau role pengguna {$user->username} menjadi {$roleName}."
        );

        return redirect()->to('manajemen-user')->with('success', 'User berhasil diperbarui.');
    }

    public function resetPassword($id)
    {
        $rules = [
            'password' => 'required|min_length[8]'
        ];

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

        return redirect()->to('manajemen-user')->with('success', 'Password berhasil direset.');
    }

    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('manajemen-user')->with('error', 'User tidak ditemukan.');
        }

        $user->active = ($user->active == 1) ? 0 : 1;
        $this->userModel->save($user);

        $statusMsg = $user->active ? 'diaktifkan' : 'dinonaktifkan';
        
        \App\Libraries\ActivityLogger::log(
            $user->active ? 'Aktifkan User' : 'Nonaktifkan User',
            'Manajemen User',
            ucfirst($statusMsg) . " akun pengguna {$user->username}."
        );
        
        return redirect()->to('manajemen-user')->with('success', "Akun berhasil $statusMsg.");
    }
}

