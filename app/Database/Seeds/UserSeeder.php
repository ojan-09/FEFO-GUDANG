<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = new UserModel();

        $data = [
            [
                'email'    => 'fauzan.foodbankindo@gmail.com',
                'username' => 'admin',
                'password' => 'admin123',
                'active'   => 1,
            ],
            [
                'email'    => 'petugas@foodbank.id',
                'username' => 'petugas',
                'password' => 'petugas123',
                'active'   => 1,
            ],
            [
                'email'    => 'pimpinan@foodbank.id',
                'username' => 'pimpinan',
                'password' => 'pimpinan123',
                'active'   => 1,
            ],
        ];

        foreach ($data as $k => $u) {
            $user = new User($u);
            $users->insert($user);
            
            // Assign roles
            $userId = $users->getInsertID();
            $roleId = $k + 1; // 1: Administrator, 2: Petugas, 3: Pimpinan
            $this->db->table('auth_groups_users')->insert([
                'group_id' => $roleId,
                'user_id'  => $userId
            ]);
        }
    }
}

