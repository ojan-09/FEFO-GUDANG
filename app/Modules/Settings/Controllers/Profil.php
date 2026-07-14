<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;

class Profil extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Profil Pengguna',
            'user'  => user()
        ];

        return view('App\Modules\Settings\Views\profil\index', $data);
    }

    public function update()
    {
        $user = user();
        $rules = [
            'username' => "required|min_length[3]|max_length[30]|is_unique[users.username,id,{$user->id}]",
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]';
            $rules['pass_confirm'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $user->username = $this->request->getPost('username');
        
        if ($this->request->getPost('password')) {
            $user->setPassword($this->request->getPost('password'));
        }
        
        if ($user->hasChanged()) {
            $userModel->skipValidation(true)->save($user);
            
            \App\Libraries\ActivityLogger::log(
                'Ubah Profil',
                'Profil',
                "Mengubah data profil / password sendiri."
            );
        }

        return redirect()->to('profil')->with('success', 'Profil berhasil diperbarui.');
    }
}

